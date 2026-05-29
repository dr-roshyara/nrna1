<?php

namespace App\Domain\Election\Security\Simplified;

/**
 * EvidenceSnapshot
 *
 * Immutable projection of evidence evaluation.
 * NO behavioral methods. Read-only properties only.
 * Controllers/UI read this snapshot; they do NOT derive authority from it directly.
 *
 * Replaces ConstitutionalTrustSnapshot in the simplified/constitutional runtime.
 */
readonly class EvidenceSnapshot
{
    public function __construct(
        public EvidenceEvaluationState $evaluationState,
        public EvidenceClassification $classification,
        public bool $attestationValid,
        public bool $continuityPreserved,
        public ?string $activeOverlay,
        public string $denialReason,
        public array $evidenceProvenance,
    ) {}
}
