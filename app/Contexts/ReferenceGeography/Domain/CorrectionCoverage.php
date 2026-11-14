<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * D-90 correction (design revision 3 §8, §9; Principal decision 1, 2026-11-14: FULL COVERAGE). Pure.
 *
 * "A correction replaces them" (the flagged facts): for EVERY claim c of the flagged publication, the correcting
 * revision's claims in c's slot must together cover every day of c's valid interval [from, to). `to` is exclusive; no
 * `to` means "until further notice", which only an open-ended replacement covers (no far-future date stands in for it).
 * Several claims may cover jointly: a revision holds non-overlapping claims per slot, so [a, b) and [b, c) cover [a, c).
 * Coverage says nothing about whether the new values are right: that is ordinary validation and approval.
 *
 * The link itself is allowed only while the publication has an OPEN finding (§8.2 rows 5 to 7: resolved once).
 */
final class CorrectionCoverage
{
    /**
     * @param list<Claim> $flagged the claims of the flagged publication
     * @param list<Claim> $replacements the claims of the correcting revision
     * @return list<Claim> the flagged claims NOT fully covered (empty = full coverage)
     */
    public static function uncovered(array $flagged, array $replacements): array
    {
        return array_values(array_filter($flagged, static fn (Claim $c): bool => !self::covers($c, $replacements)));
    }

    /**
     * @param list<Claim> $flagged
     * @param list<Claim> $replacements
     * @return list<GovernanceRefusal> empty = the link may be published
     */
    public static function refusals(FindingLifecycle $lifecycle, array $flagged, array $replacements): array
    {
        if ($lifecycle->openFindingAt() === null) {
            return [GovernanceRefusal::CorrectionWithoutOpenFinding];
        }

        return self::uncovered($flagged, $replacements) === [] ? [] : [GovernanceRefusal::CorrectionIncomplete];
    }

    /** @param list<Claim> $replacements */
    private static function covers(Claim $flagged, array $replacements): bool
    {
        $inSlot = array_values(array_filter($replacements, static fn (Claim $r): bool => $r->slot->equals($flagged->slot)));
        usort($inSlot, static fn (Claim $a, Claim $b): int => $a->interval->from->value() <=> $b->interval->from->value());
        $end = $flagged->interval->to;
        $cursor = $flagged->interval->from;   // the first day not yet covered
        foreach ($inSlot as $r) {
            if ($cursor->isBefore($r->interval->from)) {
                return false;                  // a gap: sorted by start, nothing later can fill it
            }
            if ($r->interval->to === null) {
                return true;                   // covers from the cursor until further notice
            }
            if ($cursor->isBefore($r->interval->to)) {
                $cursor = $r->interval->to;
            }
            if ($end !== null && !$cursor->isBefore($end)) {
                return true;
            }
        }

        return false;
    }
}
