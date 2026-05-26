<?php

namespace App\Domain\Election\Security;

use App\Application\Election\Security\ConstitutionalTrustSnapshot;
use App\Domain\Election\Security\OverlayInfluenceContext;
use App\Domain\Election\Security\VotingTrustResult;

readonly class TrustEvaluationEnvelope
{
    public function __construct(
        public VotingTrustResult           $result,
        public OverlayInfluenceContext     $overlayInfluence,
        public ConstitutionalTrustSnapshot $snapshot,
    ) {}
}
