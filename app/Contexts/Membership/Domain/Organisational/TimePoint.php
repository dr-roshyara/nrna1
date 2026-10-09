<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * A point of valid (effective) time, or ∞.
 *
 * Slice 0 contract §7: time is an abstract totally ordered set extended with ∞.
 * The ordinal's unit (day or instant) is not fixed here (EP-Q6).
 */
final readonly class TimePoint
{
    private function __construct(private ?int $ordinal)
    {
    }

    public static function of(int $ordinal): self
    {
        return new self($ordinal);
    }

    public static function infinity(): self
    {
        return new self(null);
    }

    public function isInfinite(): bool
    {
        return $this->ordinal === null;
    }

    public function ordinal(): int
    {
        if ($this->ordinal === null) {
            throw new \LogicException('∞ has no ordinal.');
        }

        return $this->ordinal;
    }

    public function isBefore(self $other): bool
    {
        if ($this->ordinal === null) {
            return false;
        }

        return $other->ordinal === null || $this->ordinal < $other->ordinal;
    }

    public function equals(self $other): bool
    {
        return $this->ordinal === $other->ordinal;
    }

    public function isAfter(self $other): bool
    {
        return $other->isBefore($this);
    }

    public static function min(self $a, self $b): self
    {
        return $b->isBefore($a) ? $b : $a;
    }

    public static function max(self $a, self $b): self
    {
        return $a->isBefore($b) ? $b : $a;
    }

    /** The next point; used to express the closed bound "≤ t" on half-open intervals. */
    public function successor(): self
    {
        return $this->ordinal === null ? $this : new self($this->ordinal + 1);
    }
}
