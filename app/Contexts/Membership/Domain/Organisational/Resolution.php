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
        $claims = self::baseClaims($active, $live);

        $periods = [];
        foreach ($active as $fact) {
            if (!$fact instanceof Admit) {
                continue;
            }
            $baseClaim = $claims[$fact->periodId()->value()];
            $lifetime = self::lifetime($baseClaim, self::liveCuts($fact->periodId(), $facts, $active, $live, $claims));
            $periods[] = new ResolvedPeriod($fact->periodId(), $baseClaim, $lifetime, self::term($fact, $baseClaim->start(), $lifetime));
        }

        return new ResolvedMember($periods);
    }

    /**
     * The period's BaseClaim as a set: empty when its admission is not live.
     *
     * @param list<Fact> $facts in seq order
     */
    public static function baseClaimOf(array $facts, PeriodId $period): IntervalSet
    {
        $resolved = self::of($facts)->period($period);

        return $resolved === null ? IntervalSet::empty() : IntervalSet::of($resolved->baseClaim());
    }

    /**
     * Stage 5 for one period: the union of the trims in force on it, each clipped to its cause's current BaseClaim.
     *
     * @param list<Fact> $facts in seq order
     */
    public static function liveCutOf(array $facts, PeriodId $period): IntervalSet
    {
        $live = self::live($facts);
        $active = self::active($live);

        return self::liveCuts($period, $facts, $active, $live, self::baseClaims($active, $live));
    }

    /**
     * The trims in force, by identity (cause, period): recorded on an active Correct that no live Correct supersedes.
     * Used to derive TrimRevived data (CFC+).
     *
     * @param list<Fact> $facts in seq order
     * @return list<TrimRevival>
     */
    public static function trimsInForce(array $facts): array
    {
        $live = self::live($facts);
        $inForce = [];
        foreach (self::causesInForce(self::active($live), $live) as $cause) {
            foreach ($cause->consequences() as $consequence) {
                $inForce[] = new TrimRevival($cause->id(), $consequence->period());
            }
        }

        return $inForce;
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
        return self::value(self::live($facts), self::find($facts, $id));
    }

    /**
     * Stage 3 over the live facts. Only live corrections count, so a correction of an annulled fact stays inert
     * until that fact is live again (§10a Correct (5)); for a live root, live and active corrections coincide.
     *
     * @param list<Fact> $live
     */
    private static function value(array $live, Fact $fact): TimePoint
    {
        $latest = null;
        foreach ($live as $candidate) {
            if ($candidate instanceof Correct && $candidate->target()->equals($fact->id())) {
                $latest = $candidate;
            }
        }
        if ($latest !== null) {
            return self::value($live, $latest);
        }

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
     * Stage 2, activeness: a live Correct is active iff its target is active; every other live fact is active.
     * A Correct targets an earlier fact, so one forward pass decides every fact.
     *
     * @param list<Fact> $live
     * @return list<Fact>
     */
    private static function active(array $live): array
    {
        $activeIds = [];
        $active = [];
        foreach ($live as $fact) {
            if ($fact instanceof Correct && !isset($activeIds[$fact->target()->value()])) {
                continue;
            }
            $activeIds[$fact->id()->value()] = true;
            $active[] = $fact;
        }

        return $active;
    }

    /**
     * Stage 4 for every active admission, keyed by period.
     *
     * @param list<Fact> $active
     * @param list<Fact> $live
     * @return array<string, Interval>
     */
    private static function baseClaims(array $active, array $live): array
    {
        $claims = [];
        foreach ($active as $fact) {
            if ($fact instanceof Admit) {
                $claims[$fact->periodId()->value()] = self::baseClaim($fact, $active, $live);
            }
        }

        return $claims;
    }

    /**
     * The causes whose trims are in force: active Corrects that no live Correct supersedes.
     *
     * @param list<Fact> $active
     * @param list<Fact> $live
     * @return list<Correct>
     */
    private static function causesInForce(array $active, array $live): array
    {
        $superseded = [];
        foreach ($live as $fact) {
            if ($fact instanceof Correct) {
                $superseded[$fact->target()->value()] = true;
            }
        }

        return array_values(array_filter(
            $active,
            static fn (Fact $f): bool => $f instanceof Correct && !isset($superseded[$f->id()->value()]),
        ));
    }

    /**
     * Stages 3–4, values and BaseClaim: [value(Admit), max(start, value(End))), or [start, ∞) without an End.
     * Totality on logs unreachable through decide(): the latest End on the period (by seq) counts.
     *
     * @param list<Fact> $active
     * @param list<Fact> $live
     */
    private static function baseClaim(Admit $admit, array $active, array $live): Interval
    {
        $start = self::value($live, $admit);
        $end = TimePoint::infinity();
        foreach ($active as $fact) {
            if ($fact instanceof End && $fact->period()->equals($admit->periodId())) {
                $end = self::value($live, $fact);
            }
        }

        return Interval::of($start, TimePoint::max($start, $end));
    }

    /**
     * Stage 5, LiveCut: the union of the trims in force on the period, each clipped to the current BaseClaim of its
     * cause's period (the period of the chain root); a cause whose admission is not live clips to nothing.
     *
     * @param list<Fact> $facts
     * @param list<Fact> $active
     * @param list<Fact> $live
     * @param array<string, Interval> $claims
     */
    private static function liveCuts(PeriodId $period, array $facts, array $active, array $live, array $claims): IntervalSet
    {
        $cuts = IntervalSet::empty();
        foreach (self::causesInForce($active, $live) as $cause) {
            foreach ($cause->consequences() as $consequence) {
                if (!$consequence->period()->equals($period)) {
                    continue;
                }
                $causeClaim = $claims[self::periodOfChain($facts, $cause)->value()] ?? null;
                if ($causeClaim !== null) {
                    $cuts = $cuts->union($consequence->removed()->intersectedWith(IntervalSet::of($causeClaim)));
                }
            }
        }

        return $cuts;
    }

    /**
     * The period a correction chain belongs to: that of its root Admit or End.
     *
     * @param list<Fact> $facts
     */
    public static function periodOfChain(array $facts, Correct $correct): PeriodId
    {
        $root = self::rootOf($facts, $correct->target());

        return match (true) {
            $root instanceof Admit => $root->periodId(),
            $root instanceof End => $root->period(),
            default => throw new \LogicException('The root of a correction chain is an Admit or an End.'),
        };
    }

    /** Stage 6, lifetime = BaseClaim − LiveCut (it may fragment; G* refuses that, INV-EP). */
    private static function lifetime(Interval $baseClaim, IntervalSet $cuts): IntervalSet
    {
        return IntervalSet::of($baseClaim)->without($cuts);
    }

    /**
     * Stage 7, term segments: the initial term [start, until), clipped to the lifetime; empty without one (C-S0-2).
     * The start is the period's resolved (possibly corrected) start; until is not correctable (D-COR-3).
     */
    private static function term(Admit $admit, TimePoint $start, IntervalSet $lifetime): IntervalSet
    {
        $until = $admit->initialTermUntil();
        if ($until === null) {
            return IntervalSet::empty();
        }
        $term = Interval::of($start, TimePoint::max($start, $until));

        return IntervalSet::of(...array_map(static fn (Interval $l): Interval => $l->intersectedWith($term), $lifetime->intervals()));
    }
}
