<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ListingEquipment extends Model
{
    protected $table = 'listing_equipment';

    protected $fillable = [
        'listing_id',
        'equipment_key',
    ];

    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }
}
