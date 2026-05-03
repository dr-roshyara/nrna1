<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\Exceptions;

use App\Contexts\Geography\Domain\ValueObjects\CountryCode;
use RuntimeException;

class MissingRequiredLevelException extends RuntimeException
{
    public static function forCountry(CountryCode $countryCode, int $level, string $levelName): self
    {
        return new self(
            sprintf(
                'Level %d (%s) is required for country %s.',
                $level,
                $levelName,
                $countryCode->toString()
            )
        );
    }

    public static function withContext(CountryCode $countryCode, int $level, array $availableLevels): self
    {
        $available = implode(', ', array_map(
            fn($lvl) => "Level $lvl",
            $availableLevels
        ));

        return new self(
            sprintf(
                'Level %d is required for country %s. Available levels: %s.',
                $level,
                $countryCode->toString(),
                $available ?: 'none'
            )
        );
    }
}