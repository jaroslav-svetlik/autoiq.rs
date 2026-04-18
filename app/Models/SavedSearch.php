<?php

namespace App\Models;

use App\Enums\FuelType;
use App\Enums\TransmissionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedSearch extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'query',
        'filters',
        'notify_new_matches',
        'notify_price_drops',
        'last_notified_at',
    ];

    protected function casts(): array
    {
        return [
            'filters' => 'array',
            'notify_new_matches' => 'boolean',
            'notify_price_drops' => 'boolean',
            'last_notified_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function matchesListing(Listing $listing): bool
    {
        $filters = $this->filters ?? [];

        if (($filters['brand'] ?? null) && $filters['brand'] !== $listing->brand) {
            return false;
        }

        if (($filters['model'] ?? null) && $filters['model'] !== $listing->model) {
            return false;
        }

        if (($filters['min_price'] ?? null) && $listing->price < (int) $filters['min_price']) {
            return false;
        }

        if (($filters['max_price'] ?? null) && $listing->price > (int) $filters['max_price']) {
            return false;
        }

        if (($filters['min_year'] ?? null) && $listing->year < (int) $filters['min_year']) {
            return false;
        }

        if (($filters['max_mileage'] ?? null) && $listing->mileage > (int) $filters['max_mileage']) {
            return false;
        }

        if (($filters['fuel_type'] ?? null) && $listing->fuel_type?->value !== $filters['fuel_type']) {
            return false;
        }

        if (($filters['transmission'] ?? null) && $listing->transmission?->value !== $filters['transmission']) {
            return false;
        }

        if (($filters['city'] ?? null) && $listing->city !== $filters['city']) {
            return false;
        }

        $equipment = collect($filters['equipment'] ?? [])
            ->map(fn (mixed $key) => (string) $key)
            ->filter()
            ->unique()
            ->values();

        if ($equipment->isNotEmpty()) {
            $listingEquipment = $listing->equipmentKeys();

            if ($equipment->diff($listingEquipment)->isNotEmpty()) {
                return false;
            }
        }

        $haystack = mb_strtolower(implode(' ', [
            $listing->title,
            $listing->brand,
            $listing->model,
            $listing->description,
            $listing->city,
            $listing->equipmentLabels()->implode(' '),
        ]));

        $needle = mb_strtolower(trim((string) ($filters['search'] ?? $this->query ?? '')));

        return $needle === '' || str_contains($haystack, $needle);
    }

    public function queryString(): string
    {
        return http_build_query(array_filter($this->filters ?? [], function (mixed $value): bool {
            if (is_array($value)) {
                return $value !== [];
            }

            return $value !== null && $value !== '';
        }));
    }

    public function summary(): string
    {
        $filters = $this->filters ?? [];
        $parts = array_filter([
            $filters['brand'] ?? null,
            $filters['model'] ?? null,
            isset($filters['max_price']) ? 'do '.number_format((int) $filters['max_price'], 0, ',', '.').' €' : null,
            $filters['city'] ?? null,
            ! empty($filters['equipment']) ? count($filters['equipment']).' stavki opreme' : null,
        ]);

        return $parts !== [] ? implode(' · ', $parts) : 'Bez dodatnih filtera';
    }
}
