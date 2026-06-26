<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Challenge;

use InvalidArgumentException;

/**
 * Opaque reference to a Determination owned by the Adjudication context.
 * Held by reference only (TP-1 refs-not-entities); no Adjudication code imported.
 */
final readonly class DeterminationId
{
    private function __construct(public string $value)
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException('DeterminationId cannot be empty.');
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function toString(): string
    {
        return $this->value;
    }
}
