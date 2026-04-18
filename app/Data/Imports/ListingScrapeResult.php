<?php

namespace App\Data\Imports;

class ListingScrapeResult
{
    public function __construct(
        public readonly string $sourceName,
        public readonly string $sourceUrl,
        public readonly ?string $sourceListingId,
        public readonly string $status,
        public readonly ?int $httpStatus = null,
        public readonly bool $challengeDetected = false,
        public readonly ?string $notes = null,
        public readonly array $payload = [],
    ) {
    }

    public function toImportAttributes(): array
    {
        $listing = $this->payload['listing'] ?? [];

        return [
            'source_name' => $this->sourceName,
            'source_listing_id' => $this->sourceListingId,
            'source_url' => $this->sourceUrl,
            'status' => $this->status,
            'http_status' => $this->httpStatus,
            'challenge_detected' => $this->challengeDetected,
            'title' => $listing['title'] ?? null,
            'brand' => $listing['brand'] ?? null,
            'model' => $listing['model'] ?? null,
            'year' => $listing['year'] ?? null,
            'price' => $listing['price'] ?? null,
            'mileage' => $listing['mileage'] ?? null,
            'fuel_type' => $listing['fuel_type'] ?? null,
            'transmission' => $listing['transmission'] ?? null,
            'city' => $listing['city'] ?? null,
            'seller_type' => $listing['seller_type'] ?? null,
            'cover_image_url' => $listing['cover_image_url'] ?? null,
            'description' => $listing['description'] ?? null,
            'image_urls' => $listing['image_urls'] ?? [],
            'payload' => $this->payload,
            'notes' => $this->notes,
            'fetched_at' => now(),
        ];
    }

    public function isParsable(): bool
    {
        return in_array($this->status, ['parsed', 'partial'], true);
    }
}
