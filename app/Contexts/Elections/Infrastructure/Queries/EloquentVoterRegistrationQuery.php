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
}
