<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\Exceptions;

use RuntimeException;

class CountryNotSupportedException extends RuntimeException
{
    public static function code(string $countryCode): self
    {
        return new self(
            sprintf('Country code %s is not supported by the system', $countryCode)
        );
    }
}