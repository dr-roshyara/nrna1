<?php

namespace App\Domain\Election\Security\Simplified;

/**
 * EvaluationEnvelope
 *
 * Bundles result, observation context, and snapshot into single envelope.
 * NEVER carries authority decisions. READ by the Resolver, which derives authority.
 *
 * Replaces TrustEvaluationEnvelope in the simplified/constitutional runtime.
 *
 * Properties:
 * - result: EvidenceEvaluationResult (evaluation state, classification, reason, causality)
 * - observations: ConstitutionalObservationContext (ALL overlay signals — flat, unranked collection)
 * - snapshot: EvidenceSnapshot (read-only projection)
 */
readonly class EvaluationEnvelope
{
    public function __construct(
        public EvidenceEvaluationResult $result,
        public ConstitutionalObservationContext $observations,
        public EvidenceSnapshot $snapshot,
    ) {}
}
