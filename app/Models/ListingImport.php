<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListingImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'listing_id',
        'source_name',
        'source_listing_id',
        'source_url',
        'status',
        'http_status',
        'challenge_detected',
        'title',
        'brand',
        'model',
        'year',
        'price',
        'mileage',
        'fuel_type',
        'transmission',
        'city',
        'seller_type',
        'cover_image_url',
        'description',
        'image_urls',
        'payload',
        'notes',
        'fetched_at',
        'imported_at',
    ];

    protected function casts(): array
    {
        return [
            'challenge_detected' => 'boolean',
            'image_urls' => 'array',
            'payload' => 'array',
            'fetched_at' => 'datetime',
            'imported_at' => 'datetime',
        ];
    }

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    public function sourceLabel(): string
    {
        return match ($this->source_name) {
            'mojauto_rs' => 'MojAuto',
            'polovni_automobili' => 'Polovni automobili',
            default => 'Spoljni izvor',
        };
    }

    public function publicSourceUrl(): ?string
    {
        $url = parse_url($this->source_url ?? '');
        $host = match ($this->source_name) {
            'mojauto_rs' => 'www.mojauto.rs',
            'polovni_automobili' => 'www.polovniautomobili.com',
            default => null,
        };
        $id = (string) $this->source_listing_id;

        if (! $host || ! ctype_digit($id) || ! is_array($url)
            || ($url['scheme'] ?? '') !== 'https'
            || ($url['host'] ?? '') !== $host
            || isset($url['user']) || isset($url['pass']) || isset($url['port'])) {
            return null;
        }

        $pattern = $this->source_name === 'mojauto_rs'
            ? '~^/polovni-automobili/'.$id.'_[a-zA-Z0-9_+.-]+/?$~'
            : '~^/auto-oglasi/'.$id.'/[a-zA-Z0-9_-]+/?$~';
        $path = $url['path'] ?? '';

        return preg_match($pattern, $path) ? 'https://'.$host.$path : null;
    }

    public function isReadyForDraft(): bool
    {
        return filled($this->title)
            && filled($this->brand)
            && filled($this->model)
            && filled($this->year)
            && filled($this->price)
            && filled($this->mileage)
            && filled($this->fuel_type)
            && filled($this->transmission)
            && filled($this->city)
            && filled($this->description)
            && filled($this->seller_type);
    }
}
