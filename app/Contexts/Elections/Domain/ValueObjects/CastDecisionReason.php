<?php

namespace App\Contexts\Elections\Domain\ValueObjects;

/**
 * CastDecisionReason — the ONE outcome of a final-cast decision (VotingAuthorization::forCast()).
 *
 * `Allowed` is the only success; otherwise the first failed predicate (order fixed by
 * VotingAuthorization). Derived per attempt, never stored. Exactly 14 cases
 * (ADR_20261005_0950 §3.3 without AgreementMissing — Q5 = A — plus CodeMissing and
 * BallotMissing — P4-D2; decision record E2-D3). "Participation already consumed" is
 * not a reason: it is the outcome of the compare-and-set on codes.has_voted (E4).
 */
enum CastDecisionReason: string
{
    case Allowed = 'allowed';

    // Authorization — forwarded 1:1 from VotingEntitlementReason (same names and values).
    case NotRegistered        = 'not_registered';
    case NotVoterRole         = 'not_voter_role';
    case OrganisationMismatch = 'organisation_mismatch';
    case NotActive            = 'not_active';
    case NotAdmitted          = 'not_admitted';
    case ElectionNotVotable   = 'election_not_votable';

    // Authorization — the verdict of the existing Trust → Legitimacy chain.
    case SecurityDenied         = 'security_denied';
    case SecurityReviewRequired = 'security_review_required'; // Deferred / Investigate — a denial (Q2)
    case IpBindingDenied        = 'ip_binding_denied';        // PBDIGIT-247 — not the voter's captured IP

    // Process validation.
    case CodeMissing          = 'code_missing';
    case AlreadyVotedPreCheck = 'already_voted_pre_check'; // non-authoritative; the CAS decides (E4)
    case CodeMismatch         = 'code_mismatch';
    case CodeStepIncomplete   = 'code_step_incomplete';
    case BallotMissing        = 'ballot_missing';

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
