<?php

namespace App\Services\Imports;

use App\Data\Imports\ListingScrapeResult;
use App\Enums\ListingStatus;
use App\Models\Listing;
use App\Models\ListingImport;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ListingImportService
{
    protected const IMPORT_IMAGE_DIRECTORY = 'imports/listings';

    protected const IMPORT_IMAGE_MAX_BYTES = 8_388_608;

    public function storeResult(ListingScrapeResult $result): ListingImport
    {
        $attributes = $result->toImportAttributes();
        $normalizedSourceUrl = $this->normalizeSourceUrl($result->sourceUrl);
        $attributes['source_url'] = $normalizedSourceUrl;

        $record = ListingImport::query()->updateOrCreate(
            [
                'source_name' => $result->sourceName,
                'source_url' => $normalizedSourceUrl,
            ],
            $attributes,
        );

        return $record->fresh();
    }

    public function createDraftListing(ListingImport $record, User $owner): ListingImport
    {
        if (! $record->isReadyForDraft()) {
            $record->forceFill([
                'status' => 'review',
                'notes' => trim(($record->notes ? $record->notes.' ' : '').'Nema dovoljno podataka za automatsko kreiranje nacrta oglasa.'),
            ])->save();

            return $record->fresh();
        }

        $slug = Str::slug($record->title.'-'.$record->source_listing_id);
        $listing = $this->resolveDraftListing($record, $owner, $slug);

        $listing->fill([
            'title' => $record->title,
            'brand' => $record->brand,
            'model' => $record->model,
            'year' => $record->year,
            'price' => $record->price,
            'mileage' => $record->mileage,
            'fuel_type' => $record->fuel_type,
            'transmission' => $record->transmission,
            'city' => $record->city,
            'description' => $record->description,
            'seller_type' => $record->seller_type ?: 'private',
            'status' => $this->statusForImportedListing($record),
        ]);
        $listing->save();

        $this->syncListingImages($listing, $record, $record->title);
        $this->syncListingEquipment($listing, (array) data_get($record->payload, 'listing.equipment_keys', []));

        $record->forceFill([
            'listing_id' => $listing->id,
            'status' => 'imported',
            'imported_at' => now(),
            'notes' => trim(($record->notes ? $record->notes.' ' : '').$this->importNote($listing)),
        ])->save();

        return $record->fresh();
    }

    protected function resolveDraftListing(ListingImport $record, User $owner, string $slug): Listing
    {
        if ($record->listing_id) {
            $existing = Listing::query()->find($record->listing_id);

            if ($existing) {
                return $existing;
            }
        }

        $existingBySource = Listing::query()
            ->where('user_id', $owner->id)
            ->whereHas('imports', function ($query) use ($record) {
                $query->where('source_name', $record->source_name)
                    ->where('source_listing_id', $record->source_listing_id);
            })
            ->latest('id')
            ->first();

        if ($existingBySource) {
            return $existingBySource;
        }

        return Listing::query()->firstOrNew([
            'user_id' => $owner->id,
            'slug' => $slug,
        ]);
    }

    protected function statusForImportedListing(ListingImport $record): ListingStatus
    {
        return $this->shouldAutoPublish($record)
            ? ListingStatus::Published
            : ListingStatus::Draft;
    }

    protected function shouldAutoPublish(ListingImport $record): bool
    {
        return match ($record->source_name) {
            'mojauto_rs' => (bool) config('autoiq.imports.mojauto.auto_publish', true),
            'polovni_automobili' => (bool) config('autoiq.imports.polovni_automobili.auto_publish', false),
            default => false,
        };
    }

    protected function importNote(Listing $listing): string
    {
        return $listing->status === ListingStatus::Published
            ? 'Kreiran je i objavljen lokalni oglas iz uvezenih podataka.'
            : 'Kreiran je nacrt oglasa za dalji pregled.';
    }

    protected function syncListingImages(Listing $listing, ListingImport $record, ?string $title): void
    {
        $imageUrls = collect((array) $record->image_urls)
            ->map(fn (mixed $url) => is_string($url) ? trim($url) : null)
            ->filter(fn (?string $url) => filled($url))
            ->unique()
            ->take(20)
            ->values();

        if ($imageUrls->isEmpty()) {
            return;
        }

        $paths = $this->storeImportedImages($imageUrls, $record);

        if ($paths->isEmpty()) {
            $paths = $imageUrls
                ->filter(fn (string $path) => ! Str::startsWith($path, ['http://', 'https://']))
                ->values();
        }

        if ($paths->isEmpty()) {
            return;
        }

        $this->deleteStoredImportImages($listing->images()->pluck('path')->all());
        $listing->images()->delete();

        $listing->images()->createMany(
            $paths->map(fn (string $path, int $index) => [
                'path' => $path,
                'alt_text' => $title,
                'sort_order' => $index,
            ])->all(),
        );

        $listing->unsetRelation('images');
        $listing->load('images');
    }

    protected function storeImportedImages(Collection $imageUrls, ListingImport $record): Collection
    {
        return $imageUrls
            ->map(fn (string $url, int $index) => $this->storeImportedImage($url, $record, $index))
            ->filter(fn (?string $path) => filled($path))
            ->values();
    }

    protected function storeImportedImage(string $url, ListingImport $record, int $index): ?string
    {
        if (! Str::startsWith($url, ['http://', 'https://'])) {
            return $url;
        }

        try {
            $response = Http::accept('image/avif,image/webp,image/apng,image/*,*/*;q=0.8')
                ->withHeaders([
                    'User-Agent' => 'AutoIQ Import Monitor/1.0',
                    'Referer' => $record->source_url,
                ])
                ->connectTimeout(10)
                ->timeout(20)
                ->get($url);
        } catch (Throwable) {
            return null;
        }

        if (! $response->successful()) {
            return null;
        }

        $contentType = Str::lower((string) $response->header('Content-Type', ''));

        if (! str_starts_with($contentType, 'image/')) {
            return null;
        }

        $contents = $response->body();

        if ($contents === '' || strlen($contents) > self::IMPORT_IMAGE_MAX_BYTES) {
            return null;
        }

        $directory = implode('/', array_filter([
            self::IMPORT_IMAGE_DIRECTORY,
            $record->source_name,
            $record->source_listing_id ?: 'unknown',
        ]));

        $path = sprintf(
            '%s/%02d-%s.%s',
            $directory,
            $index + 1,
            substr(md5($url), 0, 16),
            $this->imageExtension($url, $contentType),
        );

        Storage::disk('public')->put($path, $contents);

        return $path;
    }

    protected function imageExtension(string $url, string $contentType): string
    {
        return match (true) {
            Str::contains($contentType, 'jpeg') => 'jpg',
            Str::contains($contentType, 'png') => 'png',
            Str::contains($contentType, 'webp') => 'webp',
            Str::contains($contentType, 'gif') => 'gif',
            Str::contains($contentType, 'avif') => 'avif',
            default => $this->extensionFromUrl($url),
        };
    }

    protected function extensionFromUrl(string $url): string
    {
        $path = (string) parse_url($url, PHP_URL_PATH);
        $extension = strtolower((string) pathinfo($path, PATHINFO_EXTENSION));

        return in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'avif'], true)
            ? ($extension === 'jpeg' ? 'jpg' : $extension)
            : 'jpg';
    }

    protected function deleteStoredImportImages(array $paths): void
    {
        $toDelete = collect($paths)
            ->filter(fn (mixed $path) => is_string($path) && str_starts_with($path, self::IMPORT_IMAGE_DIRECTORY.'/'))
            ->values()
            ->all();

        if ($toDelete !== []) {
            Storage::disk('public')->delete($toDelete);
        }
    }

    protected function syncListingEquipment(Listing $listing, array $equipmentKeys): void
    {
        if ($equipmentKeys === []) {
            return;
        }

        $listing->syncEquipment($equipmentKeys);
    }

    protected function normalizeSourceUrl(string $url): string
    {
        $parts = parse_url($url);

        if ($parts === false || blank($parts['scheme'] ?? null) || blank($parts['host'] ?? null)) {
            return $url;
        }

        $path = $parts['path'] ?? '/';

        return sprintf(
            '%s://%s%s%s',
            strtolower((string) $parts['scheme']),
            strtolower((string) $parts['host']),
            filled($parts['port'] ?? null) ? ':'.$parts['port'] : '',
            $path !== '' ? $path : '/',
        );
    }
}
