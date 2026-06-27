<?php

namespace App\Application\Election\Security;

use App\Domain\Election\Security\LegitimacyOutcome;
use App\Domain\Election\Security\TrustEvaluationState;
use App\Domain\Election\Security\VotingTrustResult;

/**
 * ConstitutionalLegitimacyDecision
 *
 * THE exclusive constitutional authority for deriving legitimacy outcomes
 * from constitutional trust evaluation.
 *
 * CONSTITUTIONAL LAW (Resolver Exclusivity Doctrine — F4):
 * No controller, middleware, service, or queue worker may derive
 * a LegitimacyOutcome from trust evaluation outside this class.
 * This is the sovereign resolver boundary.
 *
 * Invariants:
 * - Resolver Exclusivity — ONLY this class derives LegitimacyOutcome
 * - Topology Neutrality — No dependence on controller/middleware/route
 * - Replay Determinism — Same VotingTrustResult → same LegitimacyOutcome
 * - Observation ≠ Sovereignty — Telemetry must not influence outcome
 * - No Scalar Sovereignty — Deterministic constitutional states only
 */
final readonly class ConstitutionalLegitimacyDecision
{
    /**
     * Derive a sovereign legitimacy outcome from a constitutional trust evaluation.
     *
     * This is the ONLY legitimate path from trust evidence to enforcement semantics.
     * The mapping is deterministic: the four TrustEvaluationStates map to four
     * LegitimacyOutcomes as defined by constitutional interpretation.
     *
     * SUFFICIENT_EVIDENCE   → Allowed
     * INSUFFICIENT_EVIDENCE → Denied
     * REVIEW_REQUIRED       → Deferred
     * INCONCLUSIVE          → Investigate
     */
    /**
     * Derive a sovereign legitimacy outcome from a constitutional trust evaluation.
     *
     * This is the ONLY legitimate path from trust evidence to enforcement semantics.
     * The mapping is inlined here (not on LegitimacyOutcome) so that resolver
     * exclusivity is structurally enforced — no other code path can derive a
     * LegitimacyOutcome from TrustEvaluationState.
     *
     * SUFFICIENT_EVIDENCE   → Allowed
     * INSUFFICIENT_EVIDENCE → Denied
     * REVIEW_REQUIRED       → Deferred
     * INCONCLUSIVE          → Investigate
     */
    public function decide(VotingTrustResult $trustResult): LegitimacyOutcome
    {
        return match ($trustResult->evaluationState) {
            TrustEvaluationState::SUFFICIENT_EVIDENCE   => LegitimacyOutcome::Allowed,
            TrustEvaluationState::INSUFFICIENT_EVIDENCE => LegitimacyOutcome::Denied,
            TrustEvaluationState::REVIEW_REQUIRED       => LegitimacyOutcome::Deferred,
            TrustEvaluationState::INCONCLUSIVE          => LegitimacyOutcome::Investigate,
        };
    }
}
