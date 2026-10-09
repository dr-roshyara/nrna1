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

        return $violations;
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
}
