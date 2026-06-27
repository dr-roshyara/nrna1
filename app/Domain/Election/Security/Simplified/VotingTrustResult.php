<?php

namespace App\Domain\Election\Security\Simplified;

use App\Domain\Election\Security\TrustEvaluationState;
use App\Domain\Election\Security\TrustLevel;

readonly class VotingTrustResult
{
    public function __construct(
        public TrustEvaluationState $evaluationState,
        public TrustLevel $trustLevel,
        public EvaluationReasonCode $reason,
        public array $auditContext,
        public array $policyOutcomeSequence,
    ) {}
}
