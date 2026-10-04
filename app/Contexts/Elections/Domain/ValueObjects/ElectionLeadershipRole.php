<?php

namespace App\Contexts\Elections\Domain\ValueObjects;

/**
 * ElectionLeadershipRole — the leadership role a person holds IN ONE ELECTION
 * (ADR_20261004_1549 §3: the Election Committee leadership — Chief, Deputy Chief).
 *
 * Supplied by the VerificationAuthoritySource port. v1's technical source is the
 * election-scoped election_officers rows; this is not a complete Election Committee
 * model (gap G10) and not the acceptance Committee (EM-GOV-029).
 */
enum ElectionLeadershipRole: string
{
    case Chief       = 'chief';
    case DeputyChief = 'deputy_chief';
}
