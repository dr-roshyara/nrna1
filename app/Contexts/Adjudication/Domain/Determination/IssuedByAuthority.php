<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Determination;

use InvalidArgumentException;

/**
 * The authority that issues the determination (50-05 `issuedByAuthority`).
 * Opaque, stable reference to a constitutional oversight body — no determination
 * exists without one.
 */
final readonly class IssuedByAuthority
{
    private function __construct(public string $value)
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException('A determination requires an issuing authority.');
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
