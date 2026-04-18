<?php

namespace App\Enums;

enum FuelType: string
{
    case Petrol = 'petrol';
    case Diesel = 'diesel';
    case Hybrid = 'hybrid';
    case Electric = 'electric';
    case Lpg = 'lpg';

    public function label(): string
    {
        return match ($this) {
            self::Petrol => 'Benzin',
            self::Diesel => 'Dizel',
            self::Hybrid => 'Hibrid',
            self::Electric => 'Električni',
            self::Lpg => 'TNG',
        };
    }
}
