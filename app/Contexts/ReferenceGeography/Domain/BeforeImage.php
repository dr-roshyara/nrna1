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
