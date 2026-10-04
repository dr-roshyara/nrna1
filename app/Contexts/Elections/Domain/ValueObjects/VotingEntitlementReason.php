<?php

namespace App\Contexts\Elections\Domain\ValueObjects;

/**
 * VotingEntitlementReason — the ONE deterministic outcome of a voting-entitlement decision.
 *
 * Exactly one value per decision: `Entitled`, or the first condition that failed
 * (order fixed by VotingEntitlementPolicy). PBDIGIT-213, ADR_20261004_1549 §2.2.
 */
enum VotingEntitlementReason: string
{
    case Entitled             = 'entitled';
    case NotRegistered        = 'not_registered';
    case NotVoterRole         = 'not_voter_role';
    case OrganisationMismatch = 'organisation_mismatch';
    case NotActive            = 'not_active';
    case NotAdmitted          = 'not_admitted';         // PBDIGIT-213 C3: admission requirements unsatisfied
    case ElectionNotVotable   = 'election_not_votable';

    public function isEntitled(): bool
    {
        return $this === self::Entitled;
    }
}
