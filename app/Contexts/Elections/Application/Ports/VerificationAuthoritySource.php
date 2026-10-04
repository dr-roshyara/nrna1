<?php

namespace App\Contexts\Elections\Application\Ports;

use App\Contexts\Elections\Domain\ValueObjects\ElectionLeadershipRole;

/**
 * VerificationAuthoritySource — the leadership role a user holds IN ONE ELECTION.
 *
 * v1 adapter: election-scoped election_officers (chief / deputy). When the Election
 * Committee leadership model exists (gap G10), only this adapter changes
 * (ADR_20261004_1549 §3).
 */
interface VerificationAuthoritySource
{
    public function leadershipRoleOf(string $userId, string $electionId): ?ElectionLeadershipRole;
}
