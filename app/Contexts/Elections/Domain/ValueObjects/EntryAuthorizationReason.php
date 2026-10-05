<?php

namespace App\Contexts\Elections\Domain\ValueObjects;

/**
 * EntryAuthorizationReason — the ONE outcome of an entry decision (VotingAuthorization::forEntry()).
 *
 * `Allowed` is the only success; otherwise the first failed predicate (E1 entitlement before E2 security).
 * Derived per attempt, never stored. Authorization reasons only (ADR_20261005_0950 §3.1, §3.3): no process
 * reason and never "already voted" — those are outside authorization (PBDIGIT-239). Values are identical to
 * the authorization subset of CastDecisionReason (one reason family).
 */
enum EntryAuthorizationReason: string
{
    case Allowed = 'allowed';

    // E1 — forwarded 1:1 from VotingEntitlementReason (same names and values).
    case NotRegistered        = 'not_registered';
    case NotVoterRole         = 'not_voter_role';
    case OrganisationMismatch = 'organisation_mismatch';
    case NotActive            = 'not_active';
    case NotAdmitted          = 'not_admitted';
    case ElectionNotVotable   = 'election_not_votable';

    // E2 — the verdict of the one Security Verdict Authority.
    case SecurityDenied         = 'security_denied';
    case SecurityReviewRequired = 'security_review_required'; // Deferred / Investigate — a denial (Q2)

    public function isAllowed(): bool
    {
        return $this === self::Allowed;
    }

    /** The forwarded authorization reason for a failed entitlement decision. */
    public static function fromEntitlement(VotingEntitlementReason $reason): self
    {
        return self::from($reason->value);
    }
}
