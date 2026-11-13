<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * A claim's before-image (v3.11 §5.1): a partition of its interval into segments, each with the effective value it
 * replaced there, computed against p₀. A boundary falls wherever the prior value changes; equal neighbours merge.
 * Stored with the claim forever. Invariant for G-4: it equals the belief just before the claim's publication.
 */
final readonly class BeforeImage
{
    /** @param list<array{ValidInterval, PriorValue}> $segments */
    private function __construct(private array $segments)
    {
    }

    public static function of(SlotHistory $history, ValidInterval $interval, int $atPosition): self
    {
        $starts = [$interval->from, ...$history->boundariesWithin($interval, $atPosition)];
        $segments = [];
        foreach ($starts as $i => $from) {
            $to = $starts[$i + 1] ?? $interval->to;
            $prior = $history->priorAt($from, $atPosition);
            $last = array_key_last($segments);
            if ($last !== null && $segments[$last][1]->equals($prior)) {
                $segments[$last][0] = new ValidInterval($segments[$last][0]->from, $to);
                continue;
            }
            $segments[] = [new ValidInterval($from, $to), $prior];
        }

        return new self($segments);
    }

    /**
     * Rebuilds a stored before-image (G-4b). Only what `of()` can produce: at least one segment, contiguous and
     * ascending (each starts where the previous ended), only the last one open.
     *
     * @param list<array{ValidInterval, PriorValue}> $segments
     */
    public static function fromSegments(array $segments): self
    {
        if ($segments === []) {
            throw new \InvalidArgumentException('A before-image has at least one segment.');
        }
        $previous = null;
        foreach (array_values($segments) as [$interval, $prior]) {
            if (!$interval instanceof ValidInterval || !$prior instanceof PriorValue
                || ($previous !== null && ($previous->to === null || !$previous->to->equals($interval->from)))) {
                throw new \InvalidArgumentException('Before-image segments are contiguous and ascending.');
            }
            $previous = $interval;
        }

        return new self(array_values($segments));
    }

    /** @return list<array{ValidInterval, PriorValue}> */
    public function segments(): array
    {
        return $this->segments;
    }

    /** Did the claim replace this value anywhere within the interval? (a loss for "value = X" there) */
    public function hadValue(string $value, ValidInterval $within): bool
    {
        foreach ($this->segments as [$segment, $prior]) {
            if ($prior->isValue($value) && $segment->overlaps($within)) {
                return true;
            }
        }

        return false;
    }
}
