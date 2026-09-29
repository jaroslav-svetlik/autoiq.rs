<?php

namespace App\Rules;

use App\Support\ListingDescription;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ListingDescriptionLength implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strlen($value) > ListingDescription::MAX_INPUT_BYTES) {
            $fail('Opis je prevelik. Skratite tekst ili uklonite suvišno formatiranje.');

            return;
        }

        $length = mb_strlen(ListingDescription::text($value));

        if ($length < 30) {
            $fail('Opis treba da sadrži makar 30 karaktera teksta.');
        } elseif ($length > 5000) {
            $fail('Opis može da sadrži najviše 5.000 karaktera teksta.');
        }
    }
}
