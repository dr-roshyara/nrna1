<?php

namespace App\Domain\Election\Security;

/**
 * LegitimacyOutcome
 *
 * Sovereign legitimacy outcomes. These are the ONLY valid outcomes
 * for constitutional enforcement decisions.
 *
 * CONSTITUTIONAL LAW (Sovereign Enforcement Exclusivity Doctrine):
 * - Allowed:     Voter may proceed — all constitutional rules satisfied
 * - Denied:      Voter blocked — constitutional rule violation identified
 * - Deferred:    Insufficient evidence — additional verification required
 * - Investigate: Anomaly detected — queued for manual constitutional review
 */
enum LegitimacyOutcome: string
{
    case Allowed    = 'allowed';
    case Denied     = 'denied';
    case Deferred   = 'deferred';
    case Investigate = 'investigate';

    // LegitimacyOutcome derivation from TrustEvaluationState is exclusively
    // performed by ConstitutionalLegitimacyDecision. No fromTrustState() method
    // exists — the mapping is inlined at the sole derivation point to make
    // resolver exclusivity structurally enforced rather than convention-based.
}
