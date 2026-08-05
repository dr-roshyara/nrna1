<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Determination;

use InvalidArgumentException;

/**
 * Identifier of the specific ContestedOutcome (an Election Result id or a prior
 * Determination id, per TargetType). Reference only; NEVER a vote/voter id
 * (anonymity, ADR-T11). Adjudication-local VO (ADR-T16).
 *
 * @immutable
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
