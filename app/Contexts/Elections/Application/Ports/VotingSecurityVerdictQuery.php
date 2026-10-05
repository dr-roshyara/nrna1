<?php

namespace App\Contexts\Elections\Application\Ports;

use App\Contexts\Elections\Application\DTO\SecurityAttemptContext;
use App\Contexts\Elections\Domain\ValueObjects\SecurityVerdict;

/**
 * VotingSecurityVerdictQuery — the ONE provider of the E2 fact (the VotingSecurityDecision role,
 * ADR_20261005_0950 §3.0, I3), fulfilled by the existing Trust → Legitimacy chain (PBDIGIT-239).
 *
 * VotingAuthorization receives the verdict as a fact (CastAttempt / EntryAttempt); callers obtain it
 * only here and never manufacture one. No new security policy: the implementation extracts the
 * inline Phase-4 assembly unchanged.
 */
interface VotingSecurityVerdictQuery
{
    public function verdictFor(SecurityAttemptContext $context): SecurityVerdict;
}
