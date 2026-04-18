<?php

namespace App\Enums;

enum ListingStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Rejected = 'rejected';
    case Sold = 'sold';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Nacrt',
            self::Published => 'Aktivan',
            self::Rejected => 'Odbijen',
            self::Sold => 'Prodat',
        };
    }
}
