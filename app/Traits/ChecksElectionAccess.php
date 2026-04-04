<?php

namespace App\Traits;

use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\UserOrganisationRole;

trait ChecksElectionAccess
{
    protected function canAccessElection(Organisation $organisation, string $electionId, ?string $userId = null): bool
    {
        $userId = $userId ?? auth()->id();

        $orgRole = UserOrganisationRole::where('organisation_id', $organisation->id)
            ->where('user_id', $userId)
            ->value('role');

        if (in_array($orgRole, ['owner', 'admin', 'commission'])) {
            return true;
        }

        return ElectionMembership::withoutGlobalScopes()
            ->where('election_id', $electionId)
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->exists();
    }
}
