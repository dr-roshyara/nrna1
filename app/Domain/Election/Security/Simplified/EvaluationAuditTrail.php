<?php

namespace App\Domain\Election\Security\Simplified;

/**
 * EvaluationAuditTrail
 *
 * Audit metadata for the evidence evaluation pipeline.
 * Split from VotingTrustResult to separate evaluation outcome from audit data.
 *
 * Contains:
 * - auditContext: Hashed evidence snapshot for replay verification
 * - policyOutcomeSequence: Causality chain of which policies evaluated and their outcomes
 */
readonly class EvaluationAuditTrail
{
    public function __construct(
        public array $auditContext,
        public array $policyOutcomeSequence,
    ) {}
}
