<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\Exceptions;

use App\Contexts\Geography\Domain\ValueObjects\CountryCode;
use RuntimeException;

class InvalidHierarchyException extends RuntimeException
{
    public static function emptyHierarchy(CountryCode $countryCode): self
    {
        return new self(
            sprintf(
                'Geography hierarchy cannot be empty for country %s.',
                $countryCode->toString()
            )
        );
    }

    public static function unitNotFound(int $unitId, int $level): self
    {
        return new self(
            sprintf(
                'Geography unit ID %d at level %d does not exist.',
                $unitId,
                $level
            )
        );
    }

    public static function wrongCountry(int $unitId, string $expectedCountry, string $actualCountry): self
    {
        return new self(
            sprintf(
                'Geography unit ID %d belongs to country %s, but expected %s.',
                $unitId,
                $actualCountry,
                $expectedCountry
            )
        );
    }

    public static function missingRequiredLevel(CountryCode $countryCode, int $level, string $levelName): self
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
}