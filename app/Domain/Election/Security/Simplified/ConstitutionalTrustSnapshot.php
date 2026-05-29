<?php

namespace App\Domain\Election\Security\Simplified;

use App\Domain\Election\Security\TrustEvaluationState;
use App\Domain\Election\Security\TrustLevel;

/**
 * ConstitutionalTrustSnapshot
 *
 * Immutable projection of trust evaluation.
 * NO behavioral methods. Read-only properties only.
 * Controllers/UI read this snapshot; they do NOT derive authority from it directly.
 */
readonly class ConstitutionalTrustSnapshot
{
    public function __construct(
        public TrustEvaluationState $evaluationState,
        public TrustLevel $trustLevel,
        public bool $attestationValid,
        public bool $continuityPreserved,
        public ?string $activeOverlay,
        public string $denialReason,
        public array $trustProvenance,
    ) {}
}
