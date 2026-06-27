<?php

namespace App\Domain\Election\Security\Simplified;

/**
 * TrustEvaluationEnvelope
 *
 * Bundles result, overlay signal, and snapshot into single envelope.
 * NEVER carries authority decisions. READ by the Resolver, which derives authority.
 *
 * Properties:
 * - result: EvidenceEvaluationResult (evaluation state, classification, reason, causality)
 * - overlaySignal: OverlaySignal (CONCERN_PRESENT, EVIDENCE_INCONSISTENT, ATTESTATION_AVAILABLE)
 * - snapshot: EvidenceSnapshot (read-only projection)
 */
readonly class TrustEvaluationEnvelope
{
    public function __construct(
        public EvidenceEvaluationResult $result,
        public OverlaySignal $overlaySignal,
        public EvidenceSnapshot $snapshot,
    ) {}
}
