<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Organisational\Support;

/**
 * Independent test oracle (contract §19): answers each Slice 0 predicate at one
 * time point, straight from the definitions.
 *
 * Deliberately shares NO code with production. It uses no Interval, IntervalSet,
 * Resolution or value object: plain integers, with null for ∞.
 *
 * Facts: ['A', id, start, termUntil|null, termInfinite(bool)] and ['E', id, periodId, end].
 */
final class PointwiseOracle
{
    /** @param list<array> $facts in seq order */
    public function __construct(private readonly array $facts)
    {
    }

    /** @return list<string> period ids in admission order */
    public function periods(): array
    {
        $ids = [];
        foreach ($this->facts as $f) {
            if ($f[0] === 'A') {
                $ids[] = $f[1];
            }
        }

        return $ids;
    }

    private function admit(string $period): array
    {
        foreach ($this->facts as $f) {
            if ($f[0] === 'A' && $f[1] === $period) {
                return $f;
            }
        }
        throw new \LogicException("no period $period");
    }

    /** The end of the period, or null when it never ended (the last End on it counts). */
    private function endOf(string $period): ?int
    {
        $end = null;
        foreach ($this->facts as $f) {
            if ($f[0] === 'E' && $f[2] === $period) {
                $end = $f[3];
            }
        }

        return $end;
    }

    public function inLifetime(string $period, int $t): bool
    {
        $start = $this->admit($period)[2];
        $end = $this->endOf($period);

        return $start <= $t && ($end === null || $t < $end);
    }

    public function ended(string $period, int $t): bool
    {
        $end = $this->endOf($period);

        return $end !== null && $t >= $end;
    }

    public function termCovered(string $period, int $t): bool
    {
        [, , $start, $until, $infinite] = $this->admit($period);
        if ($until === null && !$infinite) {
            return false;
        }

        return $this->inLifetime($period, $t) && $t >= $start && ($infinite || $t < $until);
    }

    public function inForce(string $period, int $t): bool
    {
        return $this->inLifetime($period, $t) && $this->termCovered($period, $t);
    }

    public function lapsed(string $period, int $t): bool
    {
        return $this->inLifetime($period, $t) && !$this->termCovered($period, $t);
    }

    public function isMember(int $t): bool
    {
        foreach ($this->periods() as $p) {
            if ($this->inForce($p, $t)) {
                return true;
            }
        }

        return false;
    }

    /** @return list<string> every period whose lifetime contains t (INV-9 says at most one) */
    public function periodsAt(int $t): array
    {
        return array_values(array_filter($this->periods(), fn (string $p): bool => $this->inLifetime($p, $t)));
    }

    /** Contract Theorem 3: Admit(s) is acceptable iff every period has ended at or before s. */
    public function admissionAcceptable(int $s): bool
    {
        foreach ($this->periods() as $p) {
            $end = $this->endOf($p);
            if ($end === null || $end > $s) {
                return false;
            }
        }

        return true;
    }

    /** Breakpoints of all predicates, for the Lemma 4 sampling grid. */
    public function breakpoints(): array
    {
        $b = [];
        foreach ($this->facts as $f) {
            if ($f[0] === 'A') {
                $b[] = $f[2];
                if ($f[3] !== null) {
                    $b[] = $f[3];
                }
            } else {
                $b[] = $f[3];
            }
        }

        return $b;
    }
}
