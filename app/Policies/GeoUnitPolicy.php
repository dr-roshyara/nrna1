<?php

namespace App\Policies;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;

/**
 * GeoUnitPolicy
 *
 * Authorization policy for geographic administrative unit management.
 *
 * Permission model:
 *   - view:      Any member of the organisation may read geography data.
 *   - create:    Organisation admins and owners may create new units.
 *   - update:    Organisation admins and owners may modify existing units.
 *   - delete:    Only organisation owners may delete geographic units.
 *   - manage:    Organisation owners may perform structural operations (import, move, re-parent).
 *
 * Geography is a shared data structure across the entire organisation —
 * write access is restricted to administrative roles.
 */
class GeoUnitPolicy
{
    /**
     * Any authenticated member may view geography data.
     */
    public function view(User $user, Organisation $organisation): bool
    {
        return UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->exists();
    }

    /**
     * Organisation admins and owners may create geographic units.
     */
    public function create(User $user, Organisation $organisation): bool
    {
        return $this->hasRole($user, $organisation, ['owner', 'admin']);
    }

    /**
     * Organisation admins and owners may update geographic units.
     */
    public function update(User $user, Organisation $organisation): bool
    {
        return $this->hasRole($user, $organisation, ['owner', 'admin']);
    }

    /**
     * Only organisation owners may delete geographic units.
     * Deletion is further constrained in the application layer (e.g. units bound to committees).
     */
    public function delete(User $user, Organisation $organisation): bool
    {
        return $this->hasRole($user, $organisation, ['owner']);
    }

    /**
     * Structural operations (bulk import, subtree move, re-parenting).
     * Requires owner-level access due to potential for wide-reaching data changes.
     */
    public function manageStructure(User $user, Organisation $organisation): bool
    {
        return $this->hasRole($user, $organisation, ['owner']);
    }

    private function hasRole(User $user, Organisation $organisation, array $roles): bool
    {
        return UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->whereIn('role', $roles)
            ->exists();
    }
}
