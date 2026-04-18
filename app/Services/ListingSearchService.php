<?php

namespace App\Services;

use App\Models\Listing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ListingSearchService
{
    public function search(array $filters, int $perPage = 12): LengthAwarePaginator
    {
        $query = Listing::query()
            ->published()
            ->with(['images', 'priceHistories', 'dealerProfile'])
            ->withCount('favoritedByUsers');

        $searchTerm = trim((string) ($filters['search'] ?? ''));
        $scoutIds = collect();

        if ($searchTerm !== '') {
            $scoutIds = $this->searchIds($searchTerm);

            if ($scoutIds->isEmpty()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereIn('id', $scoutIds->all());
            }
        }

        $query
            ->when($filters['brand'] ?? null, fn (Builder $builder, string $brand) => $builder->where('brand', $brand))
            ->when($filters['model'] ?? null, fn (Builder $builder, string $model) => $builder->where('model', $model))
            ->when($filters['city'] ?? null, fn (Builder $builder, string $city) => $builder->where('city', $city))
            ->when($filters['fuel_type'] ?? null, fn (Builder $builder, string $fuelType) => $builder->where('fuel_type', $fuelType))
            ->when($filters['transmission'] ?? null, fn (Builder $builder, string $transmission) => $builder->where('transmission', $transmission))
            ->when($filters['min_price'] ?? null, fn (Builder $builder, $price) => $builder->where('price', '>=', (int) $price))
            ->when($filters['max_price'] ?? null, fn (Builder $builder, $price) => $builder->where('price', '<=', (int) $price))
            ->when($filters['min_year'] ?? null, fn (Builder $builder, $year) => $builder->where('year', '>=', (int) $year))
            ->when($filters['max_mileage'] ?? null, fn (Builder $builder, $mileage) => $builder->where('mileage', '<=', (int) $mileage));

        foreach ($this->equipmentFilters($filters) as $equipmentKey) {
            $query->whereHas('equipmentItems', fn (Builder $builder) => $builder->where('equipment_key', $equipmentKey));
        }

        $sort = $filters['sort'] ?? 'newest';

        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'best' => $query->orderByDesc('autoiq_score')->orderBy('price'),
            default => $query->orderByDesc('published_at')->orderByDesc('created_at'),
        };

        if ($sort === 'relevance' && $scoutIds->isNotEmpty()) {
            $this->applyIdOrdering($query, $scoutIds);
        }

        return $query->paginate($perPage);
    }

    public function brands(): array
    {
        return Listing::query()
            ->published()
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand')
            ->all();
    }

    public function models(?string $brand = null): array
    {
        return Listing::query()
            ->published()
            ->when($brand, fn (Builder $builder, string $brand) => $builder->where('brand', $brand))
            ->distinct()
            ->orderBy('model')
            ->pluck('model')
            ->all();
    }

    protected function searchIds(string $term): Collection
    {
        return collect(Listing::search($term)->take(250)->keys())
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values();
    }

    protected function equipmentFilters(array $filters): array
    {
        $allowed = array_keys(Listing::equipmentKeyMap());

        return collect($filters['equipment'] ?? [])
            ->map(fn (mixed $key) => (string) $key)
            ->filter(fn (string $key) => in_array($key, $allowed, true))
            ->unique()
            ->values()
            ->all();
    }

    protected function applyIdOrdering(Builder $query, Collection $ids): void
    {
        $case = $ids
            ->values()
            ->map(fn (int $id, int $index) => "WHEN {$id} THEN {$index}")
            ->implode(' ');

        if ($case === '') {
            return;
        }

        $query->orderByRaw("CASE id {$case} ELSE ".($ids->count() + 1).' END');
    }
}
