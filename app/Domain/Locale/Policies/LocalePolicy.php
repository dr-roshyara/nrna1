<?php

namespace App\Domain\Locale\Policies;

use App\Domain\Locale\ValueObjects\Locale;

final class LocalePolicy
{
    public static function fromCountry(?string $countryCode): Locale
    {
        return new Locale(match ($countryCode) {
            'NP' => 'np',
            'DE', 'AT', 'CH', 'LI', 'LU', 'BE' => 'de',
            default => 'en',
        });
    }
}
