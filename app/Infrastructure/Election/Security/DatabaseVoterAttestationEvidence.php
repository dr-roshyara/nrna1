<?php

namespace App\Infrastructure\Election\Security;

use App\Application\Election\Security\VoterAttestationEvidence;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-286 P5 (D3-P): reads `voter_verifications` with the same predicate admission uses
 * (EloquentVotingEntitlementQuery::isAdmitted): an `active` row for this election and voter, any method, any date.
 * A query failure is not caught, so it reaches the fail-closed callers (D3-E).
 */
final class DatabaseVoterAttestationEvidence implements VoterAttestationEvidence
{
    public function hasActiveAttestation(string $electionId, string $userId): bool
    {
        return DB::table('voter_verifications')
            ->where('election_id', $electionId)
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->exists();
    }
}
