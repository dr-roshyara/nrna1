<?php

namespace App\Application\Election\Security;

use App\Application\Election\Security\Simplified\ConstitutionalPolicy;
use App\Domain\Election\Security\Simplified\ConstitutionalEvidenceSnapshot;
use App\Domain\Election\Security\Simplified\EvidenceEvaluationState;
use App\Domain\Election\Security\Simplified\EvidenceClassification;
use App\Domain\Election\Security\Simplified\EvidenceEvaluationResult;
use App\Domain\Election\Security\Simplified\EvaluationReasonCode;
use Illuminate\Support\Facades\Log;

/**
 * SimplifiedPolicySequence
 *
 * Orchestrates simplified policy evaluation in constitutional order:
 * 1. VerificationPolicy (Article 6 — evidence legitimacy)
 * 2. NetworkBindingPolicy (Article 1/4 — IP limits and continuity)
 * 3. DeviceBindingPolicy (Article 5 — device continuity)
 *
 * ALL 3 policies are evaluated independently on every call (no short-circuiting).
 * This preserves the full causality chain for replay audit and divergence detection.
 * If any policy fails, the MOST SEVERE failure's reason is used.
 *
 * INVARIANT: EvidenceClassification is always Initial (placeholder).
 * The Resolver (EvidenceCapabilityPolicy) derives the actual classification.
 *
 * INVARIANT: evaluationState is SUFFICIENT_EVIDENCE or INSUFFICIENT_EVIDENCE only.
 * REVIEW_REQUIRED and INCONCLUSIVE are determined by the Resolver from overlay signals.
 */
final class SimplifiedPolicySequence
{
    public function __construct(
        private readonly ConstitutionalPolicy $verificationPolicy,
        private readonly ConstitutionalPolicy $networkPolicy,
        private readonly ConstitutionalPolicy $devicePolicy,
    ) {}

    public function evaluate(ConstitutionalEvidenceSnapshot $evidence): EvidenceEvaluationResult
    {
        $outcomes = [];

        // Run ALL 3 policies independently — no short-circuiting
        // This preserves full causality chain for replay audit

        // Step 1: Verification (Article 6 — establishes evidence legitimacy)
        $verification = $this->verificationPolicy->evaluate($evidence);
        $outcomes[$verification->policyIdentifier] = $verification->passed ? 'passed' : 'failed';

        // Step 2: Network (Article 1/4 — IP limits and continuity)
        $network = $this->networkPolicy->evaluate($evidence);
        $outcomes[$network->policyIdentifier] = $network->passed ? 'passed' : 'failed';

        // Step 3: Device (Article 5 — device continuity)
        $device = $this->devicePolicy->evaluate($evidence);
        $outcomes[$device->policyIdentifier] = $device->passed ? 'passed' : 'failed';

        // STEP A.2 FIX F1: Preserve constitutional failure lineage without scalar collapse
        // Collect all failures with their constitutional basis and reason codes
        $failures = [];
        $auditContext = [];

        if (!$verification->passed) {
            $failures['verification'] = EvaluationReasonCode::VERIFICATION_REQUIRED_NOT_SATISFIED;
            $auditContext[] = $verification->constitutionalBasis;
        }

        if (!$network->passed) {
            $failures['network'] = EvaluationReasonCode::NETWORK_EVIDENCE_INSUFFICIENT;
            $auditContext[] = $network->constitutionalBasis;
        }

        if (!$device->passed) {
            $failures['device'] = EvaluationReasonCode::DEVICE_EVIDENCE_INSUFFICIENT;
            $auditContext[] = $device->constitutionalBasis;
        }

        if (!empty($failures)) {
            // CONSTITUTIONAL PRECEDENCE (deterministic, explicit, documented)
            // Order of precedence reflects constitutional hierarchy:
            // 1. Verification (Article 6 — foundational evidence legitimacy)
            // 2. Network (Article 1/4 — IP continuity constraints)
            // 3. Device (Article 5 — device binding)
            //
            // Why this order: Verification failure is most fundamental.
            // If evidence source cannot be verified, other tests are meaningless.
            $primaryReason = $failures['verification'] ??
                            $failures['network'] ??
                            $failures['device'] ??
                            EvaluationReasonCode::UNKNOWN_REASON;

            // RF8: UNKNOWN_REASON requires audit-critical telemetry
            if ($primaryReason === EvaluationReasonCode::UNKNOWN_REASON) {
                Log::critical('Constitutional Unclassified Finding', [
                    'failures' => array_keys($failures),
                    'audit_context' => $auditContext,
                    'policy_sequence' => $outcomes,
                    'evaluation_state' => EvidenceEvaluationState::INSUFFICIENT_EVIDENCE,
                    'timestamp' => now(),
                ]);
            }

            return new EvidenceEvaluationResult(
                evaluationState: EvidenceEvaluationState::INSUFFICIENT_EVIDENCE,
                classification: EvidenceClassification::Initial,
                reason: $primaryReason,
                auditContext: $auditContext,
                policyOutcomeSequence: $outcomes,
            );
        }

        // All 3 policies passed — sufficient evidence
        return new EvidenceEvaluationResult(
            evaluationState: EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            classification: EvidenceClassification::Initial,
            reason: EvaluationReasonCode::ALL_POLICIES_PASSED,
            auditContext: [],
            policyOutcomeSequence: $outcomes,
        );
    }
}
