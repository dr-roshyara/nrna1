<?php

namespace App\Policies;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;

class OrganisationPolicy
{
    /**
     * Only organisation owners and admins may update organisation settings.
     */
    public function update(User $user, Organisation $organisation): bool
    {
        return UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->whereIn('role', ['owner', 'admin'])
            ->exists();
    }
}
