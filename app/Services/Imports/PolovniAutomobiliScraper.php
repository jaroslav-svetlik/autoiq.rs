<?php

namespace App\Services\Imports;

use App\Data\Imports\ListingScrapeResult;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PolovniAutomobiliScraper
{
    public function scrape(string $url): ListingScrapeResult
    {
        $config = config('autoiq.imports.polovni_automobili');

        if (! ($config['enabled'] ?? false) || ! ($config['allow_fetch'] ?? false)) {
            return new ListingScrapeResult(
                sourceName: 'polovni_automobili',
                sourceUrl: $url,
                sourceListingId: $this->extractListingId($url),
                status: 'disabled',
                notes: 'Mrežni fetch za PolovniAutomobili je isključen dok se ne potvrde dozvola i operativni uslovi.',
            );
        }

        $delayMs = (int) ($config['respectful_delay_ms'] ?? 0);

        if ($delayMs > 0) {
            usleep($delayMs * 1000);
        }

        $response = Http::accept('text/html,application/xhtml+xml')
            ->withHeaders([
                'User-Agent' => (string) ($config['user_agent'] ?? 'AutoIQ Import Monitor/1.0'),
                'Accept-Language' => 'sr-RS,sr;q=0.9,en;q=0.8',
            ])
            ->connectTimeout((int) ($config['connect_timeout_seconds'] ?? 10))
            ->timeout((int) ($config['timeout_seconds'] ?? 15))
            ->get($url);

        return $this->parseHtml(
            html: (string) $response->body(),
            url: $url,
            httpStatus: $response->status(),
            responseHeaders: $response->headers(),
        );
    }

    public function parseHtml(
        string $html,
        string $url,
        ?int $httpStatus = null,
        array $responseHeaders = [],
    ): ListingScrapeResult {
        $sourceListingId = $this->extractListingId($url);

        if ($this->isChallengeResponse($html, $httpStatus, $responseHeaders)) {
            return new ListingScrapeResult(
                sourceName: 'polovni_automobili',
                sourceUrl: $url,
                sourceListingId: $sourceListingId,
                status: 'blocked',
                httpStatus: $httpStatus,
                challengeDetected: true,
                notes: 'Izvor je vratio challenge ili blokadu umesto sadržaja oglasa.',
            );
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $ld = $this->extractStructuredData($xpath);
        $meta = $this->extractMeta($xpath);
        $bodyText = trim(preg_replace('/\s+/', ' ', $dom->textContent ?: ''));

        $listing = $this->normalizeListingData($ld, $meta, $bodyText, $url, $sourceListingId);

        if (blank($listing['title'] ?? null) && blank($listing['description'] ?? null)) {
            return new ListingScrapeResult(
                sourceName: 'polovni_automobili',
                sourceUrl: $url,
                sourceListingId: $sourceListingId,
                status: 'failed',
                httpStatus: $httpStatus,
                notes: 'Parser nije pronašao dovoljno podataka za identifikaciju oglasa.',
                payload: [
                    'meta' => $meta,
                    'structured_data' => $ld,
                ],
            );
        }

        $status = $this->hasRequiredListingFields($listing) ? 'parsed' : 'partial';

        return new ListingScrapeResult(
            sourceName: 'polovni_automobili',
            sourceUrl: $url,
            sourceListingId: $sourceListingId,
            status: $status,
            httpStatus: $httpStatus,
            payload: [
                'listing' => $listing,
                'meta' => $meta,
                'structured_data' => $ld,
            ],
            notes: $status === 'partial'
                ? 'Osnovni podaci su pronađeni, ali nisu svi obavezni atributi dovoljni za automatsko kreiranje nacrta oglasa.'
                : 'Podaci su uspešno pripremljeni za pregled i uvoz.',
        );
    }

    protected function normalizeListingData(
        array $structuredData,
        array $meta,
        string $bodyText,
        string $url,
        ?string $sourceListingId,
    ): array {
        $title = $this->coalesceString(
            $structuredData['name'] ?? null,
            $structuredData['headline'] ?? null,
            $meta['og:title'] ?? null,
            $meta['twitter:title'] ?? null,
            $meta['title'] ?? null,
        );

        $description = $this->coalesceString(
            $structuredData['description'] ?? null,
            $meta['description'] ?? null,
            $meta['og:description'] ?? null,
        );

        $brand = $this->extractBrand($structuredData, $title);
        $model = $this->extractModel($structuredData, $title, $brand);
        $year = $this->extractYear($structuredData, $title, $bodyText);
        $price = $this->extractPrice($structuredData, $meta, $bodyText);
        $mileage = $this->extractMileage($structuredData, $bodyText);

        return [
            'title' => $title,
            'brand' => $brand,
            'model' => $model,
            'year' => $year,
            'price' => $price,
            'mileage' => $mileage,
            'fuel_type' => $this->normalizeFuelType($this->extractFieldValue($structuredData, $bodyText, [
                'fuelType',
                'gorivo',
            ])),
            'transmission' => $this->normalizeTransmission($this->extractFieldValue($structuredData, $bodyText, [
                'vehicleTransmission',
                'menjač',
                'menjac',
            ])),
            'city' => $this->extractCity($structuredData, $bodyText),
            'seller_type' => $this->normalizeSellerType($this->extractFieldValue($structuredData, $bodyText, [
                'seller_type',
                'prodavac',
            ])),
            'description' => $description,
            'cover_image_url' => $this->extractPrimaryImage($structuredData, $meta),
            'image_urls' => $this->extractImages($structuredData, $meta),
            'source_url' => $url,
            'source_listing_id' => $sourceListingId,
        ];
    }

    protected function extractStructuredData(DOMXPath $xpath): array
    {
        $nodes = $xpath->query('//script[@type="application/ld+json"]');
        $items = [];

        foreach ($nodes ?: [] as $node) {
            $decoded = json_decode(trim((string) $node->textContent), true);

            if (! is_array($decoded)) {
                continue;
            }

            $items = [...$items, ...$this->flattenStructuredData($decoded)];
        }

        foreach ($items as $item) {
            $types = collect((array) ($item['@type'] ?? []))
                ->map(fn ($type) => strtolower((string) $type))
                ->all();

            if (array_intersect($types, ['vehicle', 'car', 'product'])) {
                return $item;
            }
        }

        return $items[0] ?? [];
    }

    protected function flattenStructuredData(array $value): array
    {
        if (array_is_list($value)) {
            return collect($value)
                ->flatMap(fn ($item) => is_array($item) ? $this->flattenStructuredData($item) : [])
                ->values()
                ->all();
        }

        if (isset($value['@graph']) && is_array($value['@graph'])) {
            return $this->flattenStructuredData($value['@graph']);
        }

        return [$value];
    }

    protected function extractMeta(DOMXPath $xpath): array
    {
        $meta = [];

        foreach ($xpath->query('//meta[@property or @name]') ?: [] as $node) {
            $key = trim((string) ($node->attributes?->getNamedItem('property')?->nodeValue ?: $node->attributes?->getNamedItem('name')?->nodeValue));
            $content = trim((string) ($node->attributes?->getNamedItem('content')?->nodeValue ?: ''));

            if ($key !== '' && $content !== '') {
                $meta[$key] = $content;
            }
        }

        $titleNode = $xpath->query('//title')->item(0);

        if ($titleNode) {
            $meta['title'] = trim((string) $titleNode->textContent);
        }

        return $meta;
    }

    protected function extractBrand(array $structuredData, ?string $title): ?string
    {
        $brand = data_get($structuredData, 'brand.name')
            ?? data_get($structuredData, 'brand')
            ?? data_get($structuredData, 'manufacturer.name');

        if (filled($brand)) {
            return trim((string) $brand);
        }

        if (! $title) {
            return null;
        }

        $parts = preg_split('/\s+/', trim($title)) ?: [];

        return $parts[0] ?? null;
    }

    protected function extractModel(array $structuredData, ?string $title, ?string $brand): ?string
    {
        $model = data_get($structuredData, 'model') ?? data_get($structuredData, 'vehicleModelDate');

        if (filled($model) && ! is_numeric($model)) {
            return trim((string) $model);
        }

        if (! $title || ! $brand) {
            return null;
        }

        $clean = trim(Str::after($title, $brand));
        $clean = preg_replace('/\b(19|20)\d{2}\b.*/', '', $clean) ?: $clean;

        return trim($clean) !== '' ? trim($clean) : null;
    }

    protected function extractYear(array $structuredData, ?string $title, string $bodyText): ?int
    {
        $year = data_get($structuredData, 'vehicleModelDate')
            ?? data_get($structuredData, 'productionDate');

        if (filled($year) && preg_match('/(19|20)\d{2}/', (string) $year, $matches)) {
            return (int) $matches[0];
        }

        if (preg_match('/\b(19|20)\d{2}\b/', $title.' '.$bodyText, $matches)) {
            return (int) $matches[0];
        }

        return null;
    }

    protected function extractPrice(array $structuredData, array $meta, string $bodyText): ?int
    {
        $price = data_get($structuredData, 'offers.price')
            ?? data_get($structuredData, 'price')
            ?? ($meta['product:price:amount'] ?? null);

        if (filled($price)) {
            return $this->toInteger($price);
        }

        if (preg_match('/([0-9\.\,\s]{3,})\s*€/u', $bodyText, $matches)) {
            return $this->toInteger($matches[1]);
        }

        return null;
    }

    protected function extractMileage(array $structuredData, string $bodyText): ?int
    {
        $mileage = data_get($structuredData, 'mileageFromOdometer.value')
            ?? data_get($structuredData, 'mileage');

        if (filled($mileage)) {
            return $this->toInteger($mileage);
        }

        if (preg_match('/([0-9\.\,\s]{3,})\s*km/u', $bodyText, $matches)) {
            return $this->toInteger($matches[1]);
        }

        return null;
    }

    protected function extractCity(array $structuredData, string $bodyText): ?string
    {
        $city = data_get($structuredData, 'address.addressLocality')
            ?? data_get($structuredData, 'seller.address.addressLocality')
            ?? $this->extractLabeledText($bodyText, ['Grad', 'Lokacija', 'Mesto']);

        return $this->coalesceString($city);
    }

    protected function extractFieldValue(array $structuredData, string $bodyText, array $keys): ?string
    {
        foreach ($keys as $key) {
            $value = data_get($structuredData, $key);

            if (filled($value)) {
                return is_array($value) ? null : trim((string) $value);
            }
        }

        return $this->extractLabeledText($bodyText, array_map(fn ($key) => (string) $key, $keys));
    }

    protected function extractPrimaryImage(array $structuredData, array $meta): ?string
    {
        return $this->extractImages($structuredData, $meta)[0] ?? null;
    }

    protected function extractImages(array $structuredData, array $meta): array
    {
        $images = data_get($structuredData, 'image', []);

        if (is_string($images)) {
            $images = [$images];
        }

        if (isset($meta['og:image'])) {
            $images = [...(array) $images, $meta['og:image']];
        }

        return collect($images)
            ->flatten()
            ->map(fn ($image) => is_array($image) ? ($image['url'] ?? null) : $image)
            ->filter(fn ($image) => filled($image))
            ->map(fn ($image) => trim((string) $image))
            ->unique()
            ->values()
            ->all();
    }

    protected function normalizeFuelType(?string $value): ?string
    {
        return match (Str::lower((string) $value)) {
            'benzin', 'petrol', 'gasoline' => 'petrol',
            'dizel', 'diesel' => 'diesel',
            'hibrid', 'hybrid' => 'hybrid',
            'električni', 'elektricni', 'electric' => 'electric',
            'tng', 'lpg' => 'lpg',
            default => null,
        };
    }

    protected function normalizeTransmission(?string $value): ?string
    {
        return match (true) {
            Str::contains(Str::lower((string) $value), ['automat', 'automatic']) => 'automatic',
            Str::contains(Str::lower((string) $value), ['manuel', 'manual']) => 'manual',
            default => null,
        };
    }

    protected function normalizeSellerType(?string $value): string
    {
        return Str::contains(Str::lower((string) $value), ['diler', 'salon', 'pravno lice'])
            ? 'dealer'
            : 'private';
    }

    protected function hasRequiredListingFields(array $listing): bool
    {
        foreach (['title', 'brand', 'model', 'year', 'price', 'mileage', 'fuel_type', 'transmission', 'city', 'description'] as $field) {
            if (blank($listing[$field] ?? null)) {
                return false;
            }
        }

        return true;
    }

    protected function extractListingId(string $url): ?string
    {
        return preg_match('#/auto-oglasi/(\d+)#', $url, $matches)
            ? $matches[1]
            : null;
    }

    protected function isChallengeResponse(string $html, ?int $httpStatus, array $headers): bool
    {
        $cfMitigated = collect($headers)->flatten()->contains(fn ($value) => Str::lower((string) $value) === 'challenge');

        return $cfMitigated
            || $httpStatus === 403
            || Str::contains(Str::lower($html), [
                'just a moment',
                'cf-mitigated',
                'challenge-platform',
                'enable javascript and cookies to continue',
            ]);
    }

    protected function extractLabeledText(string $bodyText, array $labels): ?string
    {
        foreach ($labels as $label) {
            if (preg_match('/'.preg_quote($label, '/').'\s*[:\-]?\s*([^\|\;\.]{2,80})/ui', $bodyText, $matches)) {
                return trim($matches[1]);
            }
        }

        return null;
    }

    protected function toInteger(mixed $value): ?int
    {
        $normalized = preg_replace('/[^\d]/', '', (string) $value);

        return $normalized !== '' ? (int) $normalized : null;
    }

    protected function coalesceString(?string ...$values): ?string
    {
        foreach ($values as $value) {
            if (filled($value)) {
                return trim($value);
            }
        }

        return null;
    }
}
