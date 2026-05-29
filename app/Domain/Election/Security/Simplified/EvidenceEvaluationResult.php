<?php

namespace App\Domain\Election\Security\Simplified;

/**
 * EvidenceEvaluationResult
 *
 * The outcome of evidence evaluation in the simplified pipeline.
 * Contains the evaluation state, classification, reason, and audit context.
 *
 * This replaces VotingTrustResult in the simplified/constitutional runtime.
 * The audit trail has been split into EvaluationAuditTrail.
 *
 * INVARIANT: EvidenceClassification is always Initial (placeholder).
 * The Resolver (EvidenceCapabilityPolicy) derives the actual classification.
 */
readonly class EvidenceEvaluationResult
{
    public function __construct(
        public EvidenceEvaluationState $evaluationState,
        public EvidenceClassification $classification,
        public EvaluationReasonCode $reason,
        public array $auditContext,
        public array $policyOutcomeSequence,
    ) {}
}
