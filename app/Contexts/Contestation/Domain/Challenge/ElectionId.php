<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Challenge;

use InvalidArgumentException;

/**
 * Contestation's LOCAL identity for the single Election a ContestedOutcome
 * belongs to (ADR-T16: identities cross contexts as strings; each context
 * reconstructs its own VO). Reference only — carries no vote content.
 */
final readonly class ElectionId
{
    private function __construct(public string $value)
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException('ElectionId cannot be empty.');
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
