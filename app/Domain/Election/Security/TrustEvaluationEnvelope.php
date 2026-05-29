<?php

namespace App\Domain\Election\Security;

use App\Application\Election\Security\ConstitutionalTrustSnapshot;
use App\Domain\Election\Security\Simplified\ConstitutionalObservationContext;
use App\Domain\Election\Security\Simplified\ParticipationEligibilityEvidence;
use App\Domain\Election\Security\VotingTrustResult;

readonly class TrustEvaluationEnvelope
{
    public function __construct(
        public VotingTrustResult                   $result,
        public ConstitutionalObservationContext           $overlayObservations,
        public ConstitutionalTrustSnapshot         $snapshot,
        public ?ParticipationEligibilityEvidence   $eligibility = null,
    ) {}
}
