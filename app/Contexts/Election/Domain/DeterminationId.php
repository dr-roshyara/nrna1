<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain;

use InvalidArgumentException;

/**
 * Election's LOCAL identity for the binding determination it reacts to (ADR-T16:
 * the identity crosses the boundary as a string; Election reconstructs its own VO —
 * it is NOT Adjudication's DeterminationId). @immutable
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
