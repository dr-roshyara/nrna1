<?php

namespace App\Contexts\Elections\Application\Ports;

use App\Contexts\Elections\Application\DTO\SecurityAssessment;
use App\Contexts\Elections\Application\DTO\SecurityAttemptContext;

/**
 * VotingSecurityAssessmentQuery — the ONE Security Verdict Authority's full assessment of one attempt
 * (PBDIGIT-240, D-240-1 / D-240-3; ADR_20261005_0950 I3).
 *
 * One evaluation of the Trust → Legitimacy chain per call. The verdict that authorization consumes
 * (VotingSecurityVerdictQuery::verdictFor) is derived from this assessment and never evaluated separately.
 * Consumers only read the assessment; none re-evaluates the policy (I3).
 */
interface VotingSecurityAssessmentQuery
{
    public function assess(SecurityAttemptContext $context): SecurityAssessment;
}
