<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * A calendar date of valid time (v3.11: "what was valid at a date t"), as 'YYYY-MM-DD', proleptic Gregorian.
 * Received as a value; the domain never reads a clock.
 */
final readonly class ValidDate
{
    private function __construct(private string $value)
    {
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value, $m) !== 1 || !checkdate((int) $m[2], (int) $m[3], (int) $m[1])) {
            throw new \InvalidArgumentException('A valid date is a real calendar date written YYYY-MM-DD.');
        }
    }

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    /** Four-digit ISO dates order lexicographically. */
    public function isBefore(self $other): bool
    {
        return $this->value < $other->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
