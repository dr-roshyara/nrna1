<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * Resolution: the pure, total, deterministic function facts → resolved state
 * (contract §11).
 *
 * The stage order is ratified (act §2): live → active → value → BaseClaim →
 * LiveCut → lifetime → segments. In Slice 0 the stages for reversals and
 * consequences are trivial; later slices add cases, not stages.
 *
 * It never consults the acceptance mechanism (contract Theorem 0), a clock,
 * randomness or storage.
 */
final class Resolution
{
    /** @param list<Fact> $facts in seq order */
    public static function of(array $facts): ResolvedMember
    {
        $live = self::live($facts);
        $active = self::active($live);

        $periods = [];
        foreach ($active as $fact) {
            if (!$fact instanceof Admit) {
                continue;
            }
            $baseClaim = self::baseClaim($fact, $active);
            $lifetime = self::lifetime($baseClaim, self::liveCuts($fact->periodId(), $active));
            $periods[] = new ResolvedPeriod($fact->periodId(), $baseClaim, $lifetime, self::term($fact, $lifetime));
        }

        return new ResolvedMember($periods);
    }

    /**
     * Fact liveness (stage 1), asked of one fact. Distinct from period activity:
     * the admission of an ended period is still live.
     *
     * @param list<Fact> $facts in seq order
     */
    public static function isLive(array $facts, FactId $id): bool
    {
        foreach (self::live($facts) as $fact) {
            if ($fact->id()->equals($id)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Voided (correction contract §10a Correct (4)): a live Void targets the fact. A Void that is itself voided no
     * longer voids its target.
     *
     * @param list<Fact> $facts in seq order
     */
    public static function isVoided(array $facts, FactId $id): bool
    {
        foreach (self::live($facts) as $fact) {
            if ($fact instanceof VoidFact && $fact->target()->equals($id)) {
                return true;
            }
        }

        return false;
    }

    /**
     * The root of a correction chain: the Admit or End that a Correct (of a Correct …) ultimately corrects;
     * any other fact is its own root.
     *
     * @param list<Fact> $facts in seq order
     */
    public static function rootOf(array $facts, FactId $id): Fact
    {
        $fact = self::find($facts, $id);

        return $fact instanceof Correct ? self::rootOf($facts, $fact->target()) : $fact;
    }

    /**
     * Stage 3, value (contract §10a Correct (2)): the value of the latest live Correct of the fact, by seq and
     * recursively along the chain; without one, the fact's own date. Defined for Admit, End and Correct.
     *
     * @param list<Fact> $facts in seq order
     */
    public static function valueOf(array $facts, FactId $id): TimePoint
    {
        $latest = null;
        foreach (self::live($facts) as $fact) {
            if ($fact instanceof Correct && $fact->target()->equals($id)) {
                $latest = $fact;
            }
        }
        if ($latest !== null) {
            return self::valueOf($facts, $latest->id());
        }
        $fact = self::find($facts, $id);

        return match (true) {
            $fact instanceof Correct => $fact->value(),
            $fact instanceof EffectiveDatedFact => $fact->effectiveAt(),
            default => throw new \LogicException('Only an Admit, End or Correct has a value.'),
        };
    }

    /** @param list<Fact> $facts */
    private static function find(array $facts, FactId $id): Fact
    {
        foreach ($facts as $fact) {
            if ($fact->id()->equals($id)) {
                return $fact;
            }
        }
        throw new \LogicException('The fact is not in the log.');
    }

    /**
     * Stage 1, liveness (FP §16.3): live(f) ⇔ no later live Annul / Void targets f.
     * A reversal can only target an earlier fact, so one backward pass decides every fact.
     *
     * @param list<Fact> $facts in seq order
     * @return list<Fact>
     */
    private static function live(array $facts): array
    {
        $reversed = [];
        $live = [];
        for ($i = count($facts) - 1; $i >= 0; $i--) {
            $fact = $facts[$i];
            if (isset($reversed[$fact->id()->value()])) {
                continue;
            }
            $live[] = $fact;
            if ($fact instanceof ReversalFact) {
                $reversed[$fact->target()->value()] = true;
            }
        }

        return array_reverse($live);
    }

    /**
     * Stage 2, activeness. Slice 0 has no Correct, so every live fact is active.
     *
     * @param list<Fact> $live
     * @return list<Fact>
     */
    private static function active(array $live): array
    {
        return $live;
    }

    /**
     * Stages 3–4, values and BaseClaim: [start, max(start, end)), or [start, ∞) without an End.
     * Totality on logs unreachable through decide(): the latest End on the period (by seq) counts.
     *
     * @param list<Fact> $active
     */
    private static function baseClaim(Admit $admit, array $active): Interval
    {
        $end = TimePoint::infinity();
        foreach ($active as $fact) {
            if ($fact instanceof End && $fact->period()->equals($admit->periodId())) {
                $end = $fact->effectiveAt();
            }
        }

        return Interval::of($admit->effectiveAt(), TimePoint::max($admit->effectiveAt(), $end));
    }

    /**
     * Stage 5, LiveCut. No Slice 0 fact carries consequences (contract §8), so it is empty.
     *
     * @param list<Fact> $active
     */
    private static function liveCuts(PeriodId $period, array $active): IntervalSet
    {
        return IntervalSet::empty();
    }

    /** Stage 6, lifetime = BaseClaim − LiveCut. */
    private static function lifetime(Interval $baseClaim, IntervalSet $cuts): IntervalSet
    {
        if (!$cuts->isEmpty()) {
            throw new \LogicException('Consequences do not exist before Slice 7.');
        }

        return IntervalSet::of($baseClaim);
    }

    /** Stage 7, term segments: the initial term [start, until), clipped to the lifetime; empty without one (C-S0-2). */
    private static function term(Admit $admit, IntervalSet $lifetime): IntervalSet
    {
        $until = $admit->initialTermUntil();
        if ($until === null) {
            return IntervalSet::empty();
        }
        $term = Interval::of($admit->effectiveAt(), $until);

        return IntervalSet::of(...array_map(static fn (Interval $l): Interval => $l->intersectedWith($term), $lifetime->intervals()));
    }
}
