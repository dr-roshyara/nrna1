<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * A finite union of intervals, normalised to sorted, non-empty, non-touching intervals.
 * Two sets are equal iff they cover the same points (used by INV-EP and INV-COV).
 */
final readonly class IntervalSet
{
    /** @param list<Interval> $intervals normalised */
    private function __construct(private array $intervals)
    {
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public static function of(Interval ...$intervals): self
    {
        $nonEmpty = array_values(array_filter($intervals, static fn (Interval $i): bool => !$i->isEmpty()));
        usort($nonEmpty, static fn (Interval $a, Interval $b): int => $a->start()->ordinal() <=> $b->start()->ordinal());

        $merged = [];
        foreach ($nonEmpty as $interval) {
            $last = end($merged);
            if ($last !== false && !$last->until()->isBefore($interval->start())) {
                $merged[array_key_last($merged)] = Interval::of($last->start(), TimePoint::max($last->until(), $interval->until()));
                continue;
            }
            $merged[] = $interval;
        }

        return new self($merged);
    }

    /** @return list<Interval> */
    public function intervals(): array
    {
        return $this->intervals;
    }

    public function isEmpty(): bool
    {
        return $this->intervals === [];
    }

    public function contains(TimePoint $t): bool
    {
        foreach ($this->intervals as $interval) {
            if ($interval->contains($t)) {
                return true;
            }
        }

        return false;
    }

    public function union(self $other): self
    {
        return self::of(...$this->intervals, ...$other->intervals);
    }

    public function truncatedAt(TimePoint $e): self
    {
        return self::of(...array_map(static fn (Interval $i): Interval => $i->truncatedAt($e), $this->intervals));
    }

    public function equals(self $other): bool
    {
        if (count($this->intervals) !== count($other->intervals)) {
            return false;
        }
        foreach ($this->intervals as $k => $interval) {
            if (!$interval->equals($other->intervals[$k])) {
                return false;
            }
        }

        return true;
    }
}
