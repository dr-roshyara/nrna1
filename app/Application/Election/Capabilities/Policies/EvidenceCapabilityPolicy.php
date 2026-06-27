<?php

namespace App\Application\Election\Capabilities\Policies;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\CapabilityDecision;
use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\CapabilityPolicyLayer;
use App\Application\Election\Capabilities\CapabilitySeverity;
use App\Application\Election\Capabilities\Policy\CapabilityPolicy;
use App\Domain\Election\Security\Simplified\EvidenceEvaluationState;
use App\Domain\Election\Security\Simplified\OverlaySignal;
use App\Domain\Election\Security\Simplified\ConstitutionalObservationContext;
use App\Domain\Election\Security\TrustEvaluationState;

/**
 * EvidenceCapabilityPolicy
 *
 * Interprets evidence state into capability decisions.
 * Renamed from TrustCapabilityPolicy per C10.
 *
 * INVARIANT: This policy INTERPRETS evidence state returned by TrustPolicyEvaluator.
 * It does NOT derive new authority. The evaluator returns facts. This policy reads facts.
 *
 * During strangler fig migration, this policy supports both:
 * 1. OLD pipeline: $context->trust (TrustEvaluationEnvelope) — authoritative
 * 2. Simplified pipeline: actionMetadata['simplified_overlay_signal'] — shadow evaluation
 *
 * Evaluation state → Capability decision mapping:
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
final class EvidenceCapabilityPolicy implements CapabilityPolicy
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
            $observationContext = new ConstitutionalObservationContext([$simplifiedOverlay]);
            return $this->evaluateSimplifiedOverlay(
                EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
                $observationContext,
            );
        }

        // INVARIANT 7: No trust evidence → abstain (non-voting action)
        if (is_null($context->trust)) {
            return null;
        }

        // OLD pipeline (authoritative during strangler fig)
        $result = $context->trust->result;

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
     * This method interprets descriptive evidence through set-based signal presence checking.
     * It receives the complete ConstitutionalObservationContext (flat, unranked collection
     * of all overlay signals) and interprets based on signal presence, not ordering.
     *
     * CONSTITUTIONAL INVARIANT: Signal ordering must NOT affect resolver outcome.
     * Interpretation is set-based (signal presence), not sequence-based (first match, ordering).
     *
     * Interpretation logic:
     * - Check for ADDITIONAL_ATTESTATION_PRESENT anywhere in context → reverification required → prohibit
     * - All other signals are influence-only → delegate to evaluation state
     * - Severity, ordering, and signal count have no sovereign meaning
     */
    public function evaluateSimplifiedOverlay(
        EvidenceEvaluationState $evaluationState,
        ConstitutionalObservationContext $context,
    ): ?CapabilityDecision {
        // Step 1: Check signal PRESENCE (set membership) across ALL signals
        // This is an explicit interpretation — developers can see it's a resolver decision
        $hasAttestation = false;

        foreach ($context->all() as $signal) {
            if ($signal->signalType === 'ADDITIONAL_ATTESTATION_PRESENT') {
                $hasAttestation = true;
                break;
            }
        }

        // Step 2: Interpret based on presence, NOT ordering or count
        if ($hasAttestation) {
            return CapabilityDecision::prohibited(
                CapabilityDenialReason::ConstitutionalReviewPending,
                'Re-verification required: attestation evidence present',
                CapabilitySeverity::HardBlock,
            );
        }

        // Step 3: All other signals are influence-only — delegate to evaluation state
        // Severity, ordering, and signal properties do not determine capability

        return match ($evaluationState) {
            EvidenceEvaluationState::SUFFICIENT_EVIDENCE => null,
            EvidenceEvaluationState::INSUFFICIENT_EVIDENCE => CapabilityDecision::prohibited(
                CapabilityDenialReason::TrustDenied,
                'Trust evaluation failed',
                CapabilitySeverity::HardBlock,
            ),
            EvidenceEvaluationState::REVIEW_REQUIRED => CapabilityDecision::prohibited(
                CapabilityDenialReason::ConstitutionalReviewPending,
                'Constitutional review required',
                CapabilitySeverity::HardBlock,
            ),
            EvidenceEvaluationState::INCONCLUSIVE => CapabilityDecision::prohibited(
                CapabilityDenialReason::TrustEvaluationInconclusive,
                'Trust cannot be established',
                CapabilitySeverity::HardBlock,
            ),
        };
    }
}
