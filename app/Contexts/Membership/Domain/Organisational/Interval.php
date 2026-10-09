<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * The half-open interval [from, until); until may be ∞. Empty iff until ≤ from.
 *
 * Slice 0 contract §10.
 */
final readonly class Interval
{
    private function __construct(private TimePoint $from, private TimePoint $until)
    {
        if ($from->isInfinite()) {
            throw new \InvalidArgumentException('An interval cannot start at ∞.');
        }
    }

    public static function of(TimePoint $from, TimePoint $until): self
    {
        return new self($from, $until);
    }

    public static function from(TimePoint $from): self
    {
        return new self($from, TimePoint::infinity());
    }

    public function start(): TimePoint
    {
        return $this->from;
    }

    public function until(): TimePoint
    {
        return $this->until;
    }

    public function isEmpty(): bool
    {
        return !$this->from->isBefore($this->until);
    }

    public function isOpenEnded(): bool
    {
        return $this->until->isInfinite();
    }

    public function contains(TimePoint $t): bool
    {
        return !$t->isBefore($this->from) && $t->isBefore($this->until);
    }

    public function overlaps(self $other): bool
    {
        return !$this->isEmpty() && !$other->isEmpty()
            && $this->from->isBefore($other->until)
            && $other->from->isBefore($this->until);
    }

    public function isAdjacentTo(self $other): bool
    {
        return $this->until->equals($other->from) || $other->until->equals($this->from);
    }

    /** [a, max(a, min(b, e))): never enlarges (contract Lemma 1). */
    public function truncatedAt(TimePoint $e): self
    {
        return new self($this->from, TimePoint::max($this->from, TimePoint::min($this->until, $e)));
    }

    public function intersectedWith(self $other): self
    {
        $from = TimePoint::max($this->from, $other->from);

        return new self($from, TimePoint::max($from, TimePoint::min($this->until, $other->until)));
    }

    public function equals(self $other): bool
    {
        return $this->from->equals($other->from) && $this->until->equals($other->until);
    }
}
