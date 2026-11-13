<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * The published claims of ONE slot, by publication position (v3.11 §3, §6). Immutable: with() returns a new history.
 * - Claims in this slot supersede each other, only for the valid time they cover: at date t, the claim with the
 *   highest position (≤ the knowledge position asked) whose interval covers t is the answer.
 * - A claim of another slot is refused: claims in different slots never interact.
 * - A position is used once and is positive.
 */
final class SlotHistory
{
    /** @param array<int, Claim> $byPosition */
    private function __construct(private readonly SlotKey $slot, private readonly array $byPosition)
    {
    }

    public static function for(SlotKey $slot): self
    {
        return new self($slot, []);
    }

    public function with(Claim $claim, int $position): self
    {
        if (!$claim->slot->equals($this->slot)) {
            throw new \InvalidArgumentException('Claims in different slots never interact.');
        }
        if ($position < 1 || isset($this->byPosition[$position])) {
            throw new \InvalidArgumentException('A publication position is positive and used once.');
        }
        $next = $this->byPosition;
        $next[$position] = $claim;
        krsort($next);

        return new self($this->slot, $next);
    }

    /** The value valid at t, as known at the given position (default: everything here); null if none or withdrawn. */
    public function resolve(ValidDate $t, ?int $upToPosition = null): ?ClaimValue
    {
        $top = $this->topCovering($t, $upToPosition);

        return ($top === null || $top->value->isNone()) ? null : $top->value;
    }

    public function isWithdrawnAt(ValidDate $t, ?int $upToPosition = null): bool
    {
        return $this->topCovering($t, $upToPosition)?->value->isNone() ?? false;
    }

    /** The effective prior value at t as known at the position: a value, NONE (withdrawn) or ABSENT (never claimed). */
    public function priorAt(ValidDate $t, ?int $upToPosition = null): PriorValue
    {
        $top = $this->topCovering($t, $upToPosition);

        return match (true) {
            $top === null => PriorValue::absent(),
            $top->value->isNone() => PriorValue::none(),
            default => PriorValue::of($top->value->value()),
        };
    }

    /**
     * @return list<ValidDate> every claim start or end (known at the position) strictly inside the interval, sorted;
     *   the effective value can change only there
     */
    public function boundariesWithin(ValidInterval $interval, ?int $upToPosition = null): array
    {
        $points = [];
        foreach ($this->byPosition as $position => $claim) {
            if ($upToPosition !== null && $position > $upToPosition) {
                continue;
            }
            foreach ([$claim->interval->from, $claim->interval->to] as $d) {
                if ($d !== null && $interval->from->isBefore($d) && ($interval->to === null || $d->isBefore($interval->to))) {
                    $points[$d->value()] = $d;
                }
            }
        }
        ksort($points);

        return array_values($points);
    }

    private function topCovering(ValidDate $t, ?int $upToPosition): ?Claim
    {
        foreach ($this->byPosition as $position => $claim) {
            if (($upToPosition === null || $position <= $upToPosition) && $claim->interval->covers($t)) {
                return $claim;
            }
        }

        return null;
    }
}
