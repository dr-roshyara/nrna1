<?php

namespace App\Domain\Election\Security;

/**
 * VotingTrustResult
 *
 * Result of trust evaluation. Contains evidence state, NOT authority decisions.
 *
 * CONSTITUTIONAL LAW:
 * This result NEVER decides whether the voter can participate.
 * It returns what the evidence supports. The Resolver interprets the evidence state.
 *
 * - evaluationState: SUFFICIENT_EVIDENCE | INSUFFICIENT_EVIDENCE | REVIEW_REQUIRED | INCONCLUSIVE
 * - reason: why the evidence supports or fails to support participation
 * - trustLevel: the assessed trust level (Unverified, Attested, ContinuityVerified, RegistrarAttested)
 * - auditContext: minimized evidence for audit trail
 * - policyOutcomeSequence: causality chain showing which policies evaluated and their results
 */
readonly class VotingTrustResult
{
    public function __construct(
        public TrustEvaluationState $evaluationState,
        public string              $reason,
        public TrustLevel          $trustLevel,
        public array               $auditContext,
        public array               $policyOutcomeSequence,
    ) {}

    public static function sufficientEvidence(TrustLevel $trustLevel, array $context, array $sequence): self
    {
        return new self(
            evaluationState: TrustEvaluationState::SUFFICIENT_EVIDENCE,
            reason: 'All trust policies satisfied',
            trustLevel: $trustLevel,
            auditContext: $context,
            policyOutcomeSequence: $sequence,
        );
    }

    public static function insufficientEvidence(string $reason, TrustLevel $trustLevel, array $context, array $sequence): self
    {
        return new self(
            evaluationState: TrustEvaluationState::INSUFFICIENT_EVIDENCE,
            reason: $reason,
            trustLevel: $trustLevel,
            auditContext: $context,
            policyOutcomeSequence: $sequence,
        );
    }

    public static function reviewRequired(string $reason, array $context, array $sequence): self
    {
        return new self(
            evaluationState: TrustEvaluationState::REVIEW_REQUIRED,
            reason: $reason,
            trustLevel: TrustLevel::Unverified,
            auditContext: $context,
            policyOutcomeSequence: $sequence,
        );
    }

    public static function inconclusive(string $reason, array $context, array $sequence): self
    {
        return new self(
            evaluationState: TrustEvaluationState::INCONCLUSIVE,
            reason: $reason,
            trustLevel: TrustLevel::Unverified,
            auditContext: $context,
            policyOutcomeSequence: $sequence,
        );
    }
}
