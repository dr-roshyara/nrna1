<?php

namespace App\Contexts\Elections\Infrastructure\Queries;

use App\Contexts\Elections\Application\Ports\VoterRegistrationQuery;
use App\Models\ElectionMembership;

/**
 * EloquentVoterRegistrationQuery — verifiable registered voter (PBDIGIT-213, C3-D2):
 * voter role, not soft-deleted (SoftDeletes in force), status ≠ removed. Scope is the
 * election itself (only the 'tenant' scope is removed), never ambient context.
 */
final class EloquentVoterRegistrationQuery implements VoterRegistrationQuery
{
    public function isVerifiableVoter(string $userId, string $electionId): bool
    {
        return ElectionMembership::withoutGlobalScope('tenant')
            ->where('election_id', $electionId)
            ->where('user_id', $userId)
            ->where('role', 'voter')
            ->where('status', '!=', 'removed')
            ->exists();
    }

    /**
     * C4-D1: voter role · status active · not soft-deleted (SoftDeletes in force) · NO
     * voter_verifications row of any status for this election (never verified; revoked
     * evidence — incl. suspension_confirmed — excludes the voter).
     */
    public function bulkVerifiableVoterIds(string $electionId): array
    {
        return ElectionMembership::withoutGlobalScope('tenant')
            ->where('election_memberships.election_id', $electionId)
            ->where('election_memberships.role', 'voter')
            ->where('election_memberships.status', 'active')
            ->whereNotExists(function ($evidence) {
                $evidence->selectRaw('1')
                    ->from('voter_verifications')
                    ->whereColumn('voter_verifications.election_id', 'election_memberships.election_id')
                    ->whereColumn('voter_verifications.user_id', 'election_memberships.user_id');
            })
            ->orderBy('election_memberships.user_id')
            ->pluck('election_memberships.user_id')
            ->all();
    }
}
