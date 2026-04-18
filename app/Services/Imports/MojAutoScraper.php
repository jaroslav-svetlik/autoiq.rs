<?php

namespace App\Services\Imports;

use App\Data\Imports\ListingScrapeResult;
use App\Models\Listing;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MojAutoScraper
{
    public function scrape(string $url): ListingScrapeResult
    {
        $config = config('autoiq.imports.mojauto');

        if (! ($config['enabled'] ?? false) || ! ($config['allow_fetch'] ?? false)) {
            return new ListingScrapeResult(
                sourceName: 'mojauto_rs',
                sourceUrl: $url,
                sourceListingId: $this->extractListingId($url),
                status: 'disabled',
                notes: 'Mrezni fetch za MojAuto je iskljucen dok se ne potvrde dozvola i operativni uslovi.',
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

        if ($this->isBlockedResponse($html, $httpStatus, $responseHeaders)) {
            return new ListingScrapeResult(
                sourceName: 'mojauto_rs',
                sourceUrl: $url,
                sourceListingId: $sourceListingId,
                status: 'blocked',
                httpStatus: $httpStatus,
                challengeDetected: true,
                notes: 'Izvor je vratio blokadu ili nekompletan odgovor umesto sadrzaja oglasa.',
            );
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">'.$html);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $meta = $this->extractMeta($xpath);
        $technicalSpecs = $this->extractDefinitionList($xpath, 'Tehnički podaci');
        $history = $this->extractDefinitionList($xpath, 'Poreklo i istorija vozila');
        $equipmentRaw = $this->extractFlatList($xpath, 'Oprema');
        $listing = $this->normalizeListingData($xpath, $meta, $technicalSpecs, $history, $equipmentRaw, $url, $sourceListingId);

        if (blank($listing['title'] ?? null) && blank($listing['description'] ?? null)) {
            return new ListingScrapeResult(
                sourceName: 'mojauto_rs',
                sourceUrl: $url,
                sourceListingId: $sourceListingId,
                status: 'failed',
                httpStatus: $httpStatus,
                notes: 'Parser nije pronasao dovoljno podataka za identifikaciju oglasa.',
                payload: [
                    'meta' => $meta,
                    'technical_specs' => $technicalSpecs,
                    'history' => $history,
                ],
            );
        }

        $status = $this->hasRequiredListingFields($listing) ? 'parsed' : 'partial';

        return new ListingScrapeResult(
            sourceName: 'mojauto_rs',
            sourceUrl: $url,
            sourceListingId: $sourceListingId,
            status: $status,
            httpStatus: $httpStatus,
            payload: [
                'listing' => $listing,
                'meta' => $meta,
                'technical_specs' => $technicalSpecs,
                'history' => $history,
                'equipment' => $equipmentRaw,
            ],
            notes: $status === 'partial'
                ? 'Osnovni podaci su pronađeni, ali nisu svi atributi dovoljni za automatsko kreiranje nacrta oglasa.'
                : 'Podaci su uspešno pripremljeni za pregled i uvoz.',
        );
    }

    protected function normalizeListingData(
        DOMXPath $xpath,
        array $meta,
        array $technicalSpecs,
        array $history,
        array $equipmentRaw,
        string $url,
        ?string $sourceListingId,
    ): array {
        $title = $this->extractTitle($xpath, $meta);
        [$brandFromUrl, $modelFromUrl, $yearFromUrl] = $this->extractBrandModelYearFromUrl($url);
        $basicItems = $this->extractBasicItems($xpath);
        $description = $this->extractDescription($xpath, $meta);
        $equipmentKeys = $this->matchEquipmentKeys($equipmentRaw, $technicalSpecs);

        return [
            'title' => $title,
            'brand' => $brandFromUrl ?? $this->extractBrandFromTitle($title),
            'model' => $modelFromUrl ?? $this->extractModelFromTitle($title, $brandFromUrl),
            'year' => $this->extractYear($basicItems, $technicalSpecs, $title) ?? $yearFromUrl,
            'price' => $this->extractPrice($xpath, $meta),
            'mileage' => $this->extractMileage($basicItems, $technicalSpecs),
            'fuel_type' => $this->extractFuelType($basicItems, $technicalSpecs, $description),
            'transmission' => $this->extractTransmission($technicalSpecs, $basicItems),
            'city' => $this->extractCity($xpath),
            'seller_type' => $this->extractSellerType($history, $xpath),
            'description' => $description,
            'cover_image_url' => $this->extractImages($xpath, $meta, $url)[0] ?? null,
            'image_urls' => $this->extractImages($xpath, $meta, $url),
            'equipment_raw' => $equipmentRaw,
            'equipment_keys' => $equipmentKeys,
            'source_url' => $url,
            'source_listing_id' => $sourceListingId,
        ];
    }

    protected function extractTitle(DOMXPath $xpath, array $meta): ?string
    {
        $title = $this->queryText($xpath, "(//ul[contains(@class, 'basicSingleData')])[1]//li[contains(@class, 'basicTitle')][1]//strong[1]");

        if (filled($title)) {
            return $title;
        }

        $fallback = $meta['og:title'] ?? $meta['title'] ?? null;

        if (! filled($fallback)) {
            return null;
        }

        $fallback = preg_replace('/\s+-\s+MojAuto\s+-\s+\d+$/u', '', (string) $fallback) ?: (string) $fallback;
        $fallback = preg_replace('/^Polovni\s+/u', '', $fallback) ?: $fallback;

        return $this->cleanText($fallback);
    }

    protected function extractBasicItems(DOMXPath $xpath): array
    {
        $items = [];
        $nodes = $xpath->query("(//ul[contains(@class, 'basicSingleData')])[1]//li/span");

        foreach ($nodes ?: [] as $node) {
            $text = $this->cleanText((string) $node->textContent);

            if ($text !== '') {
                $items[] = $text;
            }
        }

        return $items;
    }

    protected function extractPrice(DOMXPath $xpath, array $meta): ?int
    {
        $price = $this->queryText($xpath, "(//span[contains(@class, 'priceReal')])[1]");

        if (filled($price)) {
            return $this->toInteger($price);
        }

        foreach ([$meta['og:description'] ?? null, $meta['description'] ?? null] as $description) {
            if (filled($description) && preg_match('/([0-9\\.,\\s]{3,})\\s*(EUR|€)/ui', (string) $description, $matches)) {
                return $this->toInteger($matches[1]);
            }
        }

        return null;
    }

    protected function extractYear(array $basicItems, array $technicalSpecs, ?string $title): ?int
    {
        foreach ([...$basicItems, $technicalSpecs['Godište'] ?? null, $title] as $value) {
            if (filled($value) && preg_match('/\\b(19|20)\\d{2}\\b/', (string) $value, $matches)) {
                return (int) $matches[0];
            }
        }

        return null;
    }

    protected function extractMileage(array $basicItems, array $technicalSpecs): ?int
    {
        $candidates = [
            $technicalSpecs['Prešao kilometara'] ?? null,
            ...$basicItems,
        ];

        foreach ($candidates as $value) {
            if (filled($value) && preg_match('/([0-9\\.,\\s]{3,})\\s*km?/ui', (string) $value, $matches)) {
                return $this->toInteger($matches[1]);
            }

            if (filled($value)) {
                $digits = preg_replace('/[^\\d]/', '', (string) $value) ?: '';

                if (preg_match('/^\\d{4,}$/', $digits)) {
                    return (int) $digits;
                }
            }
        }

        return null;
    }

    protected function extractFuelType(array $basicItems, array $technicalSpecs, ?string $description): ?string
    {
        $candidates = [
            ...$basicItems,
            $technicalSpecs['Gorivo'] ?? null,
            $description,
        ];

        foreach ($candidates as $value) {
            $normalized = $this->normalizeFuelType($value);

            if ($normalized) {
                return $normalized;
            }
        }

        return null;
    }

    protected function extractTransmission(array $technicalSpecs, array $basicItems): ?string
    {
        foreach ([
            $technicalSpecs['Menjač'] ?? null,
            $technicalSpecs['Menjac'] ?? null,
            ...$basicItems,
        ] as $value) {
            $normalized = $this->normalizeTransmission($value);

            if ($normalized) {
                return $normalized;
            }
        }

        return null;
    }

    protected function extractCity(DOMXPath $xpath): ?string
    {
        $nodes = $xpath->query("//*[contains(@class, 'sellerInfoText')][1]/text()");
        $values = [];

        foreach ($nodes ?: [] as $node) {
            $text = $this->cleanText((string) $node->nodeValue);

            if ($text !== '') {
                $values[] = $text;
            }
        }

        return $this->normalizeCity($values !== [] ? end($values) : null);
    }

    protected function extractSellerType(array $history, DOMXPath $xpath): string
    {
        $name = $this->queryText($xpath, "(//*[contains(@class, 'sellerInfoText')][1]//h4)[1]");
        $dealerHint = $this->queryAttribute($xpath, "(//*[contains(@class, 'sellerInfoText')][1]//h4//a)[1]", 'title');
        $value = $history['Oglašivač'] ?? $dealerHint ?? $name;

        return $this->normalizeSellerType($value);
    }

    protected function extractDescription(DOMXPath $xpath, array $meta): ?string
    {
        $description = $this->queryText(
            $xpath,
            "//h1[contains(@class, 'singleBoxHeader') and normalize-space() = 'Opis']/following-sibling::*[1]",
        );

        $description = $description ?: ($meta['og:description'] ?? $meta['description'] ?? null);

        return filled($description) ? $this->cleanText((string) $description) : null;
    }

    protected function extractImages(DOMXPath $xpath, array $meta, string $url): array
    {
        $images = [];

        $nodes = $xpath->query("//a[starts-with(@id, 'galleryDialogAdvertThumb_') or starts-with(@id, 'advertThumb_')]");

        foreach ($nodes ?: [] as $node) {
            $href = trim((string) ($node->attributes?->getNamedItem('href')?->nodeValue ?: ''));

            if ($href !== '') {
                $images[] = $this->absoluteUrl($href, $url);
            }
        }

        if (isset($meta['og:image'])) {
            $images[] = $this->absoluteUrl((string) $meta['og:image'], $url);
        }

        return collect($images)
            ->filter(fn (?string $image) => filled($image))
            ->unique(fn (string $image) => $this->imageFingerprint($image))
            ->values()
            ->all();
    }

    protected function extractDefinitionList(DOMXPath $xpath, string $heading): array
    {
        $items = [];
        $query = sprintf(
            "//h1[contains(@class, 'singleBoxHeader') and normalize-space() = '%s']/following-sibling::ul[1]/li",
            $heading,
        );

        $nodes = $xpath->query($query);

        foreach ($nodes ?: [] as $node) {
            $label = $this->queryText($xpath, './span[1]', $node);
            $value = $this->queryText($xpath, './strong[1]', $node);

            if ($label !== null && $value !== null) {
                $items[$label] = $value;
            }
        }

        return $items;
    }

    protected function extractFlatList(DOMXPath $xpath, string $heading): array
    {
        $items = [];
        $query = sprintf(
            "//h1[contains(@class, 'singleBoxHeader') and normalize-space() = '%s']/following-sibling::ul[1]/li",
            $heading,
        );

        $nodes = $xpath->query($query);

        foreach ($nodes ?: [] as $node) {
            $text = $this->cleanText((string) $node->textContent);

            if ($text !== '') {
                $items[] = $text;
            }
        }

        return $items;
    }

    protected function matchEquipmentKeys(array $equipmentRaw, array $technicalSpecs): array
    {
        $labelMap = collect(Listing::equipmentKeyMap())
            ->mapWithKeys(fn (string $label, string $key) => [$this->normalizeLookupValue($label) => $key])
            ->all();

        $aliases = [
            'automatska klima' => 'air_conditioning',
            'klima' => 'air_conditioning',
            'dvozonska klima' => 'dual_zone_climate',
            'grejanje sedista' => 'heated_seats',
            'kozna sedista' => 'leather_seats',
            'tempomat' => 'cruise_control',
            'adaptivni tempomat' => 'adaptive_cruise_control',
            'parking senzori' => 'parking_sensors',
            'kamera za rikverc' => 'parking_camera',
            'abs' => 'abs',
            'esp' => 'esp',
            'air bag za vozaca' => 'airbags',
            'air bag za suvozaca' => 'airbags',
            'bocni air bag' => 'airbags',
            'navigacija' => 'navigation',
            'bluetooth' => 'bluetooth',
            'apple carplay' => 'apple_carplay',
            'android auto' => 'android_auto',
            'usb' => 'usb',
            'digitalna instrument tabla' => 'digital_cockpit',
            'digitalni kokpit' => 'digital_cockpit',
            'led svetla' => 'led_headlights',
            'xenon svetla' => 'xenon_headlights',
            'maglenke' => 'fog_lights',
            'panoramski krov' => 'panoramic_roof',
            'alu felne' => 'alloy_wheels',
            'krovni nosaci' => 'roof_rails',
            'kuka za vucu' => 'tow_hook',
            'senzori za kisu' => 'rain_sensor',
            'senzor kise' => 'rain_sensor',
            'senzori za svetla' => 'light_sensor',
            'senzor svetla' => 'light_sensor',
            'start stop sistem' => 'start_stop',
            'head up displej' => 'head_up_display',
            'pracenje mrtvog ugla' => 'blind_spot_monitor',
            'pomoc zadrzavanja trake' => 'lane_assist',
        ];

        if (filled($technicalSpecs['Klima'] ?? null)) {
            $equipmentRaw[] = (string) $technicalSpecs['Klima'];
        }

        return collect($equipmentRaw)
            ->map(function (mixed $item) use ($labelMap, $aliases) {
                $normalized = $this->normalizeLookupValue((string) $item);

                return $labelMap[$normalized] ?? $aliases[$normalized] ?? null;
            })
            ->filter(fn (?string $key) => filled($key))
            ->unique()
            ->values()
            ->all();
    }

    protected function extractBrandModelYearFromUrl(string $url): array
    {
        $path = (string) parse_url($url, PHP_URL_PATH);

        if (! preg_match('#/polovni-automobili/\\d+_([^/]+)#', $path, $matches)) {
            return [null, null, null];
        }

        $parts = collect(explode('_', rawurldecode($matches[1])))
            ->filter(fn (string $part) => $part !== '')
            ->values();

        if ($parts->isEmpty()) {
            return [null, null, null];
        }

        $brand = $this->formatToken((string) $parts->shift());
        $modelParts = [];
        $year = null;

        foreach ($parts as $part) {
            if ($year === null && preg_match('/^(19|20)\\d{2}$/', (string) $part)) {
                $year = (int) $part;
                break;
            }

            if (Str::lower((string) $part) === 'god') {
                break;
            }

            $modelParts[] = $this->formatToken((string) $part);
        }

        return [
            $brand,
            $modelParts !== [] ? implode(' ', $modelParts) : null,
            $year,
        ];
    }

    protected function extractBrandFromTitle(?string $title): ?string
    {
        if (! filled($title)) {
            return null;
        }

        $parts = preg_split('/\\s+/', trim((string) $title)) ?: [];

        return $parts[0] ?? null;
    }

    protected function extractModelFromTitle(?string $title, ?string $brand): ?string
    {
        if (! filled($title) || ! filled($brand)) {
            return null;
        }

        $clean = trim(Str::after((string) $title, (string) $brand));
        $clean = preg_replace('/\\b(19|20)\\d{2}\\b.*/', '', $clean) ?: $clean;
        $parts = preg_split('/\\s+/', $clean) ?: [];

        return $parts[0] ?? null;
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
            $meta['title'] = $this->cleanText((string) $titleNode->textContent);
        }

        return $meta;
    }

    protected function queryText(DOMXPath $xpath, string $query, ?object $contextNode = null): ?string
    {
        $nodes = $xpath->query($query, $contextNode);
        $node = $nodes?->item(0);

        return $node ? $this->cleanText((string) $node->textContent) : null;
    }

    protected function queryAttribute(DOMXPath $xpath, string $query, string $attribute, ?object $contextNode = null): ?string
    {
        $nodes = $xpath->query($query, $contextNode);
        $node = $nodes?->item(0);
        $value = $node?->attributes?->getNamedItem($attribute)?->nodeValue;

        return filled($value) ? $this->cleanText((string) $value) : null;
    }

    protected function cleanText(?string $value): string
    {
        if (! is_string($value)) {
            return '';
        }

        $decoded = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim(preg_replace('/\\s+/u', ' ', $decoded) ?: '');
    }

    protected function normalizeFuelType(?string $value): ?string
    {
        $value = $this->normalizeLookupValue((string) $value);

        return match (true) {
            Str::contains($value, ['tng', 'lpg', 'plin']) => 'lpg',
            Str::contains($value, ['hibrid', 'hybrid']) => 'hybrid',
            Str::contains($value, ['elektric', 'electric', 'struja']) => 'electric',
            Str::contains($value, ['dizel', 'diesel']) => 'diesel',
            Str::contains($value, ['benzin', 'petrol', 'gasoline']) => 'petrol',
            default => null,
        };
    }

    protected function normalizeTransmission(?string $value): ?string
    {
        $value = $this->normalizeLookupValue((string) $value);

        return match (true) {
            Str::contains($value, ['automat', 'automatic']) => 'automatic',
            Str::contains($value, ['manuel', 'manual']) => 'manual',
            default => null,
        };
    }

    protected function normalizeSellerType(?string $value): string
    {
        $value = $this->normalizeLookupValue((string) $value);

        return Str::contains($value, ['diler', 'salon', 'auto kuca', 'auto plac', 'pravno lice', 'firma', 'doo', 'd o o', 's r o'])
            ? 'dealer'
            : 'private';
    }

    protected function normalizeCity(?string $city): ?string
    {
        if (! filled($city)) {
            return null;
        }

        $normalized = $this->normalizeLookupValue((string) $city);

        foreach ((array) config('autoiq.cities', []) as $knownCity) {
            if ($this->normalizeLookupValue((string) $knownCity) === $normalized) {
                return (string) $knownCity;
            }
        }

        return Str::title((string) $city);
    }

    protected function normalizeLookupValue(string $value): string
    {
        $value = Str::ascii(Str::lower($value));
        $value = preg_replace('/[^a-z0-9]+/', ' ', $value) ?: $value;

        return trim($value);
    }

    protected function absoluteUrl(string $path, string $baseUrl): string
    {
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $parts = parse_url($baseUrl);
        $scheme = $parts['scheme'] ?? 'https';
        $host = $parts['host'] ?? 'www.mojauto.rs';

        return $scheme.'://'.$host.'/'.ltrim($path, '/');
    }

    protected function imageFingerprint(string $image): string
    {
        $path = (string) parse_url($image, PHP_URL_PATH);
        $basename = basename($path);

        return preg_replace('/^(orig|biggest|big)_/i', '', $basename) ?: $basename;
    }

    protected function toInteger(mixed $value): ?int
    {
        $normalized = preg_replace('/[^\\d]/', '', (string) $value);

        return $normalized !== '' ? (int) $normalized : null;
    }

    protected function extractListingId(string $url): ?string
    {
        return preg_match('#/polovni-automobili/(\\d+)#', $url, $matches)
            ? $matches[1]
            : null;
    }

    protected function formatToken(string $value): string
    {
        return str_replace('-', ' ', trim($value));
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

    protected function isBlockedResponse(string $html, ?int $httpStatus, array $headers): bool
    {
        $headerValues = collect($headers)->flatten()->map(fn (mixed $value) => Str::lower((string) $value));
        $html = Str::lower($html);

        return in_array($httpStatus, [401, 403, 429, 503], true)
            || $headerValues->contains(fn (string $value) => Str::contains($value, ['captcha', 'challenge']))
            || Str::contains($html, ['captcha', 'access denied', 'forbidden']);
    }
}
