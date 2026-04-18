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
