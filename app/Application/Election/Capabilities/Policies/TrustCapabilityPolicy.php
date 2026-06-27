<?php

namespace App\Application\Election\Capabilities\Policies;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDecision;
use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\CapabilityPolicyLayer;
use App\Application\Election\Capabilities\CapabilitySeverity;
use App\Application\Election\Capabilities\Policy\CapabilityPolicy;
use App\Domain\Election\Security\RecommendedProceduralPath;
use App\Domain\Election\Security\Simplified\OverlaySignal;
use App\Domain\Election\Security\TrustEvaluationState;

/**
 * TrustCapabilityPolicy
 *
 * Interprets evidence state into capability decisions.
 * INVARIANT: This policy INTERPRETS evidence state returned by TrustPolicyEvaluator.
 * It does NOT derive new authority. The evaluator returns facts. This policy reads facts.
 *
 * During strangler fig migration, this policy supports both:
 * 1. OLD pipeline: $context->trust (TrustEvaluationEnvelope) — authoritative
 * 2. Simplified pipeline: actionMetadata['simplified_overlay_signal'] — shadow evaluation
 *
 * EvidenceEvaluationState → Capability decision mapping:
 * - SUFFICIENT_EVIDENCE → abstain (let other policies run)
 * - INSUFFICIENT_EVIDENCE → prohibited(TrustDenied)
 * - REVIEW_REQUIRED → prohibited(ConstitutionalReviewPending)
 * - INCONCLUSIVE → prohibited(TrustEvaluationInconclusive)
 *
 * Overlay signal precedence:
 * - ATTESTATION_AVAILABLE → prohibited(ConstitutionalReviewPending)
 * - EVIDENCE_INCONSISTENT → abstain (elevation is influence only)
 * - CONCERN_PRESENT → delegate to evaluation state
 */
final class TrustCapabilityPolicy implements CapabilityPolicy
{
    public function layer(): CapabilityPolicyLayer
    {
        return CapabilityPolicyLayer::Trust;
    }

    public function evaluate(CapabilityContext $context): ?CapabilityDecision
    {
        // Check for Simplified pipeline overlay signal (shadow evaluation)
        $simplifiedOverlay = $context->actionMetadata['simplified_overlay_signal'] ?? null;

        if ($simplifiedOverlay instanceof OverlaySignal) {
            return $this->evaluateSimplifiedOverlay(
                \App\Domain\Election\Security\Simplified\EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
                $simplifiedOverlay,
            );
        }

        // INVARIANT 7: No trust evidence → abstain (non-voting action)
        if (is_null($context->trust)) {
            return null;
        }

        // OLD pipeline (authoritative during strangler fig)
        $overlay = $context->trust->OverlaySignalCategory;
        $result = $context->trust->result;

        // Check overlay procedural path (descriptive recommendation)
        if ($overlay->strongestProceduralPath === RecommendedProceduralPath::DEFER_TO_GOVERNANCE) {
            return CapabilityDecision::prohibited(
                CapabilityDenialReason::ConstitutionalReviewPending,
                'Constitutional review required before participation',
                CapabilitySeverity::HardBlock,
            );
        }

        if ($overlay->strongestProceduralPath === RecommendedProceduralPath::REQUEST_REVERIFICATION) {
            return CapabilityDecision::prohibited(
                CapabilityDenialReason::ConstitutionalReviewPending,
                'Re-verification required before participation',
                CapabilitySeverity::HardBlock,
            );
        }

        // MAIN LOGIC: Match on evidence evaluation state
        return match ($result->evaluationState) {
            TrustEvaluationState::SUFFICIENT_EVIDENCE => null,
            TrustEvaluationState::INSUFFICIENT_EVIDENCE => CapabilityDecision::prohibited(
                CapabilityDenialReason::TrustDenied,
                $result->reason,
                CapabilitySeverity::HardBlock,
            ),
            TrustEvaluationState::REVIEW_REQUIRED => CapabilityDecision::prohibited(
                CapabilityDenialReason::ConstitutionalReviewPending,
                'Constitutional review required before participation: ' . $result->reason,
                CapabilitySeverity::HardBlock,
            ),
            TrustEvaluationState::INCONCLUSIVE => CapabilityDecision::prohibited(
                CapabilityDenialReason::TrustEvaluationInconclusive,
                'Trust cannot be established at this time: ' . $result->reason,
                CapabilitySeverity::HardBlock,
            ),
        };
    }

    /**
     * Evaluate using Simplified pipeline inputs.
     *
     * ⚠️ CONSTITUTIONAL VIOLATION — DORMANT SOVEREIGN TOPOLOGY QUARANTINE (Phase E.3 deletion)
     *
     * This method is in the STRANGLER FIG BOUNDARY and will be deleted in Phase E.3
     * after the old TrustEvaluationEnvelope pipeline is fully decommissioned.
     *
     * CRITICAL ISSUE F2 (QUARANTINED):
     * This resolver path checks for 'ATTESTATION_AVAILABLE' but overlays emit
     * 'ADDITIONAL_ATTESTATION_PRESENT' — signal type mismatch creates permanently
     * blind resolver path (attestation presence undetectable).
     *
     * WHY NOT REFACTORED NOW:
     * Refactoring would couple the new Simplified path to the old path's lifecycle.
     * Strangler fig pattern requires isolation until both paths can be safely decommissioned.
     *
     * RESOLUTION PLAN:
     * Phase E.3: Delete this entire method and TrustCapabilityPolicy class.
     * Use EvidenceCapabilityPolicy instead (signal types match, logic correct).
     *
     * This method interprets descriptive OverlaySignal types alongside
     * EvidenceEvaluationState from SimplifiedPolicySequence.
     * It is called during strangler fig migration when the Simplified pipeline
     * is active but the old $context->trust is not populated.
     *
     * Overlay signals are checked FIRST (higher precedence than evaluation state):
     * - ATTESTATION_AVAILABLE → reverification required → prohibit [DEAD PATH — never matches]
     * - EVIDENCE_INCONSISTENT → influence only → delegate to evaluation state
     * - CONCERN_PRESENT → neutral → delegate to evaluation state
     */
    public function evaluateSimplifiedOverlay(
        \App\Domain\Election\Security\Simplified\EvidenceEvaluationState $evaluationState,
        OverlaySignal $overlay,
    ): ?CapabilityDecision {
        // Overlay signal takes precedence over evaluation state
        // ISSUE F2: This checks 'ATTESTATION_AVAILABLE' but overlays emit 'ADDITIONAL_ATTESTATION_PRESENT'
        // This condition NEVER evaluates true — dormant dead code in strangler fig boundary
        if ($overlay->signalType === 'ATTESTATION_AVAILABLE') {
            return CapabilityDecision::prohibited(
                CapabilityDenialReason::ConstitutionalReviewPending,
                'Re-verification available: ' . $overlay->constitutionalBasis,
                CapabilitySeverity::HardBlock,
            );
        }

        // EVIDENCE_INCONSISTENT and CONCERN_PRESENT are influence-only:
        // they do not block on their own, delegate to evaluation state

        return match ($evaluationState) {
            \App\Domain\Election\Security\Simplified\EvidenceEvaluationState::SUFFICIENT_EVIDENCE => null,
            \App\Domain\Election\Security\Simplified\EvidenceEvaluationState::INSUFFICIENT_EVIDENCE => CapabilityDecision::prohibited(
                CapabilityDenialReason::TrustDenied,
                'Trust evaluation failed',
                CapabilitySeverity::HardBlock,
            ),
            \App\Domain\Election\Security\Simplified\EvidenceEvaluationState::REVIEW_REQUIRED => CapabilityDecision::prohibited(
                CapabilityDenialReason::ConstitutionalReviewPending,
                'Constitutional review required',
                CapabilitySeverity::HardBlock,
            ),
            \App\Domain\Election\Security\Simplified\EvidenceEvaluationState::INCONCLUSIVE => CapabilityDecision::prohibited(
                CapabilityDenialReason::TrustEvaluationInconclusive,
                'Trust cannot be established',
                CapabilitySeverity::HardBlock,
            ),
        };
    }
}
