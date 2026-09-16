<?php

namespace App\Traits;

use App\Models\ElectionMembership;
use App\Models\ElectionOfficer;
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

        if (ElectionMembership::withoutGlobalScopes()
            ->where('election_id', $electionId)
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->exists()) {
            return true;
        }

        // Election officers (chief/deputy/commissioner) administer or observe
        // this election but are not necessarily on its voter roster — the
        // read-only voter list is documented as being "for ALL election
        // members (including officers)" (OrganisationController::voters()).
        return ElectionOfficer::where('election_id', $electionId)
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->exists();
    }
}
