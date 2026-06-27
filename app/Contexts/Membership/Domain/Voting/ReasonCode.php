<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Voting;

/**
 * Voting Eligibility Reason Codes
 *
 * Machine-readable codes explaining why a member is eligible or ineligible for voting.
 * Used by VotingEligibilityResult to communicate decisions to Elections context.
 */
enum ReasonCode: string
{
    case NONE = 'none';
    case NO_MEMBERSHIP = 'no_membership';
    case SUSPENDED = 'suspended';
    case TERMINATED = 'terminated';
    case NOT_ACTIVE = 'not_active';
}
