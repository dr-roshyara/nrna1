<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Infrastructure\Exceptions;

use RuntimeException;

final class ConcurrencyException extends RuntimeException
{
    public static function versionMismatch(int $unitId, int $expectedVersion, int $actualVersion): self
    {
        return new self(
            sprintf(
                'GeoUnit %d version mismatch: expected %d, actual %d',
                $unitId,
                $expectedVersion,
                $actualVersion
            )
        );
    }
}
