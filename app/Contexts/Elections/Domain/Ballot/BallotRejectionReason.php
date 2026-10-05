<?php

namespace App\Contexts\Elections\Domain\Ballot;

/**
 * Why a submitted ballot is not valid for the voter's authoritative ballot (VE-01). Derived, never stored.
 */
enum BallotRejectionReason: string
{
    case Malformed               = 'malformed';                 // D3
    case PostNotOnBallot         = 'post_not_on_ballot';        // other election / other region / unknown post (rules 2, 5; N5)
    case DuplicatePost           = 'duplicate_post';            // the same post answered twice
    case CandidacyNotOnPost      = 'candidacy_not_on_post';     // other election / other post / unknown / not electable (rules 3, 4; D5)
    case DuplicateCandidacy      = 'duplicate_candidacy';       // rule 6
    case OverMaximum             = 'over_maximum';              // rule 7 (D2: required_number is the maximum)
    case AbstentionWithCandidates = 'abstention_with_candidates'; // N1
    case AbstentionNotAllowed    = 'abstention_not_allowed';    // N4
}
