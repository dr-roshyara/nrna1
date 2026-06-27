<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\ValueObjects;

enum GeographicScope: string
{
    case WORLDWIDE = 'worldwide';
    case MULTI_COUNTRY = 'multi_country';
    case SINGLE_COUNTRY = 'single_country';
    case SUB_COUNTRY = 'sub_country';

    public function requiresCountrySelector(): bool
    {
        return match($this) {
            self::WORLDWIDE, self::MULTI_COUNTRY => true,
            default => false,
        };
    }

    public function isSingleCountry(): bool
    {
        return $this === self::SINGLE_COUNTRY;
    }

    public static function fromString(string $value): self
    {
        return self::from($value);
    }
}
