<?php

namespace App\Enums;

enum UserRole: string
{
    case User = 'user';
    case Dealer = 'dealer';
    case Admin = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::User => 'Korisnik',
            self::Dealer => 'Diler',
            self::Admin => 'Administrator',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function fromMixed(self|string $role): self
    {
        return $role instanceof self ? $role : self::from($role);
    }
}
