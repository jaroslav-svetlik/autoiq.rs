<?php

namespace App\Enums;

enum SellerType: string
{
    case Private = 'private';
    case Dealer = 'dealer';

    public function label(): string
    {
        return match ($this) {
            self::Private => 'Privatno lice',
            self::Dealer => 'Diler',
        };
    }
}
