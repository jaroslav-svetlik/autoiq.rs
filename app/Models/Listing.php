<?php

namespace App\Models;

use App\Enums\FuelType;
use App\Enums\ListingStatus;
use App\Enums\SellerType;
use App\Enums\TransmissionType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Laravel\Scout\Searchable;

class Listing extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'dealer_profile_id',
        'slug',
        'title',
        'brand',
        'model',
        'year',
        'price',
        'previous_price',
        'market_average_price',
        'price_deviation_percentage',
        'mileage',
        'fuel_type',
        'transmission',
        'city',
        'description',
        'seller_type',
        'status',
        'autoiq_score',
        'views_count',
        'is_featured',
        'featured_until',
        'published_at',
        'last_price_drop_at',
        'rejected_reason',
    ];

    protected function casts(): array
    {
        return [
            'fuel_type' => FuelType::class,
            'transmission' => TransmissionType::class,
            'seller_type' => SellerType::class,
            'status' => ListingStatus::class,
            'is_featured' => 'boolean',
            'featured_until' => 'datetime',
            'published_at' => 'datetime',
            'last_price_drop_at' => 'datetime',
            'price_deviation_percentage' => 'float',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dealerProfile(): BelongsTo
    {
        return $this->belongsTo(DealerProfile::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ListingImage::class)->orderBy('sort_order');
    }

    public function priceHistories(): HasMany
    {
        return $this->hasMany(PriceHistory::class)->orderBy('recorded_at');
    }

    public function imports(): HasMany
    {
        return $this->hasMany(ListingImport::class);
    }

    public function equipmentItems(): HasMany
    {
        return $this->hasMany(ListingEquipment::class)->orderBy('equipment_key');
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', ListingStatus::Published);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function searchableAs(): string
    {
        return 'listings';
    }

    public function shouldBeSearchable(): bool
    {
        return $this->status === ListingStatus::Published;
    }

    public function toSearchableArray(): array
    {
        $equipmentLabels = $this->equipmentLabels();

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'brand' => $this->brand,
            'model' => $this->model,
            'year' => $this->year,
            'price' => $this->price,
            'mileage' => $this->mileage,
            'city' => $this->city,
            'fuel_type' => $this->fuel_type?->value,
            'transmission' => $this->transmission?->value,
            'seller_type' => $this->seller_type?->value,
            'status' => $this->status?->value,
            'autoiq_score' => $this->autoiq_score,
            'equipment' => $equipmentLabels->all(),
            'equipment_keys' => $this->equipmentKeys()->all(),
            'description' => str($this->description)->limit(500)->toString(),
            'created_at' => optional($this->created_at)->timestamp,
        ];
    }

    public static function equipmentCatalog(): Collection
    {
        return collect(config('autoiq.listing_equipment', []))
            ->map(function (array $group, string $key) {
                return [
                    'key' => $key,
                    'label' => $group['label'] ?? str($key)->headline()->toString(),
                    'options' => collect($group['items'] ?? [])
                        ->map(fn (string $label, string $optionKey) => [
                            'key' => $optionKey,
                            'label' => $label,
                        ])
                        ->values(),
                ];
            })
            ->values();
    }

    public static function equipmentKeyMap(): array
    {
        return static::equipmentCatalog()
            ->flatMap(fn (array $group) => collect($group['options'])->pluck('label', 'key'))
            ->all();
    }

    public function equipmentKeys(): Collection
    {
        if ($this->relationLoaded('equipmentItems')) {
            return $this->equipmentItems->pluck('equipment_key')->map(fn (mixed $key) => (string) $key)->values();
        }

        return $this->equipmentItems()->pluck('equipment_key')->map(fn (mixed $key) => (string) $key)->values();
    }

    public function selectedEquipmentGroups(): Collection
    {
        $selectedKeys = $this->equipmentKeys()->flip();

        return static::equipmentCatalog()
            ->map(function (array $group) use ($selectedKeys) {
                $options = collect($group['options'])
                    ->filter(fn (array $option) => $selectedKeys->has($option['key']))
                    ->values();

                return [
                    'key' => $group['key'],
                    'label' => $group['label'],
                    'options' => $options,
                ];
            })
            ->filter(fn (array $group) => collect($group['options'])->isNotEmpty())
            ->values();
    }

    public function equipmentLabels(): Collection
    {
        return $this->selectedEquipmentGroups()
            ->flatMap(fn (array $group) => collect($group['options'])->pluck('label'))
            ->values();
    }

    public function syncEquipment(array $keys): void
    {
        $allowedKeys = collect(static::equipmentKeyMap())->keys();
        $keys = collect($keys)
            ->map(fn (mixed $key) => (string) $key)
            ->filter(fn (string $key) => $allowedKeys->contains($key))
            ->unique()
            ->values();

        $this->equipmentItems()->delete();

        if ($keys->isNotEmpty()) {
            $this->equipmentItems()->createMany(
                $keys->map(fn (string $key) => ['equipment_key' => $key])->all(),
            );
        }

        $this->unsetRelation('equipmentItems');
        $this->load('equipmentItems');
        $this->searchable();
    }

    public function primaryImageUrl(): string
    {
        $image = $this->images->sortBy('sort_order')->first();

        return $image?->url() ?? 'https://placehold.co/960x640/0f172a/f8fafc?text='.urlencode($this->brand.' '.$this->model);
    }

    public function scoreLabel(): string
    {
        return match (true) {
            $this->autoiq_score >= 75 => 'Dobra kupovina',
            $this->autoiq_score >= 50 => 'Realna cena',
            default => 'Precijenjeno',
        };
    }

    public function scoreTone(): string
    {
        return match (true) {
            $this->autoiq_score >= 75 => 'emerald',
            $this->autoiq_score >= 50 => 'amber',
            default => 'rose',
        };
    }

    public function marketDifferenceLabel(): string
    {
        if ($this->price_deviation_percentage === null) {
            return 'Bez dovoljno podataka';
        }

        $sign = $this->price_deviation_percentage > 0 ? '+' : '';

        return $sign.number_format($this->price_deviation_percentage, 1, ',', '.').'%';
    }

    public function priceDropPercentage(): ?float
    {
        if (! $this->previous_price || $this->previous_price <= $this->price) {
            return null;
        }

        return round((($this->previous_price - $this->price) / $this->previous_price) * 100, 1);
    }

    public function sparklinePoints(int $width = 112, int $height = 28): string
    {
        $values = $this->priceHistories->pluck('price')->values();

        if ($values->isEmpty()) {
            $values = collect([$this->price]);
        }

        if ($values->count() === 1) {
            $values = collect([$values->first(), $values->first()]);
        }

        $min = (float) $values->min();
        $max = (float) $values->max();
        $spread = max($max - $min, 1);
        $step = $values->count() > 1 ? $width / ($values->count() - 1) : $width;

        return $values
            ->map(function (int|float $value, int $index) use ($height, $min, $spread, $step) {
                $x = round($index * $step, 2);
                $y = round($height - (($value - $min) / $spread) * $height, 2);

                return "{$x},{$y}";
            })
            ->implode(' ');
    }
}
