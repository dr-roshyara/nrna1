<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Challenge;

use InvalidArgumentException;

/**
 * Identifier of the specific ContestedOutcome being contested — an Election
 * Result id or a prior Determination id (per TargetType). Reference only;
 * NEVER a vote or voter identifier (anonymity — ADR-T11).
 */
final readonly class TargetId
{
    private function __construct(public string $value)
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException('TargetId cannot be empty.');
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
