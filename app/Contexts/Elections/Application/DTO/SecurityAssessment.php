<?php

namespace App\Contexts\Elections\Application\DTO;

use App\Contexts\Elections\Domain\ValueObjects\SecurityVerdict;
use App\Domain\Election\Security\LegitimacyOutcome;
use App\Domain\Election\Security\TrustEvaluationEnvelope;

/**
 * SecurityAssessment — the result of one Trust → Legitimacy evaluation (PBDIGIT-240, D-240-1 / D-240-2).
 *
 * Ownership (D-240-2): the security context owns the outcome and the envelope (produced only by the
 * evaluator and the resolver); this DTO carries them read-only. The verdict is derived from the outcome by
 * the authority — consumers read it, they never map or re-evaluate.
 */
final readonly class SecurityAssessment
{
    public function __construct(
        public SecurityVerdict $verdict,
        public LegitimacyOutcome $outcome,
        public TrustEvaluationEnvelope $envelope,
    ) {}
}
