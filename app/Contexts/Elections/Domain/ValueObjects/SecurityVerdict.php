<?php

namespace App\Contexts\Elections\Domain\ValueObjects;

/**
 * SecurityVerdict — the outcome of the ONE security authority for a cast attempt, as a fact.
 *
 * The verdict is produced by the existing Trust → Legitimacy chain (ADR_20261005_0950
 * §3.0: VotingSecurityDecision is a role, not a new engine); the caller maps its
 * LegitimacyOutcome onto these three values. No sub-reason is carried — the detail stays
 * in that chain's own logging (decision record E2-D4, option A).
 */
enum SecurityVerdict: string
{
    case Allowed        = 'allowed';
    case Denied         = 'denied';
    case ReviewRequired = 'review_required'; // Deferred / Investigate — denied (Q2)
}
