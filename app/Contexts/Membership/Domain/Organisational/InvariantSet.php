<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Organisational;

/**
 * The invariants G* checks on a resolved candidate state (contract §14–§15).
 * Domain truth is Resolution; this only judges a resolved state.
 */
final class InvariantSet
{
    /** @return list<RefusalReason> */
    public static function violations(ResolvedMember $resolved): array
    {
        $violations = [];
        if (self::periodsOverlap($resolved)) {
            $violations[] = RefusalReason::OverlappingMembershipPeriods;
        }
        if (self::someLifetimeIsFragmented($resolved)) {
            $violations[] = RefusalReason::PeriodLifetimeNotContiguous;
        }
        if (self::coverageLost($resolved)) {
            $violations[] = RefusalReason::MembershipCoverageLost;
        }
        if (self::somePeriodIsAbsorbed($resolved)) {
            $violations[] = RefusalReason::PeriodAbsorbed;
        }

        return $violations;
    }

    /**
     * ER (BD-REV-ER = refuse, PO 2026-11-02; canonical §6): no live admission has a non-empty BaseClaim and an empty
     * lifetime. Checked last, as in the oracle (refuse_absorption after the other invariants).
     */
    private static function somePeriodIsAbsorbed(ResolvedMember $resolved): bool
    {
        foreach ($resolved->periods() as $period) {
            if (!$period->baseClaim()->isEmpty() && $period->lifetime()->isEmpty()) {
                return true;
            }
        }

        return false;
    }

    /** INV-9: for q ≠ q′, lifetime(q) ∩ lifetime(q′) = ∅. */
    private static function periodsOverlap(ResolvedMember $resolved): bool
    {
        $periods = $resolved->periods();
        foreach ($periods as $i => $p) {
            foreach (array_slice($periods, $i + 1) as $q) {
                foreach ($p->lifetime()->intervals() as $a) {
                    foreach ($q->lifetime()->intervals() as $b) {
                        if ($a->overlaps($b)) {
                            return true;
                        }
                    }
                }
            }
        }

        return false;
    }

    /** INV-EP: every lifetime is at most one interval. */
    private static function someLifetimeIsFragmented(ResolvedMember $resolved): bool
    {
        foreach ($resolved->periods() as $period) {
            if (count($period->lifetime()->intervals()) > 1) {
                return true;
            }
        }

        return false;
    }

    /** INV-COV: ⋃ lifetime = ⋃ BaseClaim. */
    private static function coverageLost(ResolvedMember $resolved): bool
    {
        $lifetimes = IntervalSet::empty();
        $claims = IntervalSet::empty();
        foreach ($resolved->periods() as $period) {
            $lifetimes = $lifetimes->union($period->lifetime());
            $claims = $claims->union(IntervalSet::of($period->baseClaim()));
        }

        return !$lifetimes->equals($claims);
    }
}
