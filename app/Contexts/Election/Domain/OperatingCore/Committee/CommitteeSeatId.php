<?php

declare(strict_types=1);

namespace App\Contexts\Election\Domain\OperatingCore\Committee;

use InvalidArgumentException;

/**
 * Identity of a constituted Committee seat — the seat survives its occupant and is
 * the unit that expresses at most one position per acceptance decision
 * (EM-GOV-056, 066; EM-ARCH-001 §2a). @immutable
 */
final readonly class CommitteeSeatId
{
    private function __construct(public string $value)
    {
        if (trim($value) === '') {
            throw new InvalidArgumentException('CommitteeSeatId cannot be empty.');
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

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
