<?php

namespace App\Policies;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;

class OrganisationPolicy
{
    /**
     * Any authenticated member may view the organisation.
     */
    public function view(User $user, Organisation $organisation): bool
    {
        return UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->exists();
    }

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

    /**
     * Only organisation owners and admins may manage membership settings.
     */
    public function manageMembership(User $user, Organisation $organisation): bool
    {
        return UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->whereIn('role', ['owner', 'admin'])
            ->exists();
    }

    /**
     * Only organisation owners and admins may manage committees.
     */
    public function manageCommittee(User $user, Organisation $organisation): bool
    {
        $roles = UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->get();

        \Log::info('ManageCommittee policy check', [
            'user_id' => $user->id,
            'org_id' => $organisation->id,
            'roles_count' => $roles->count(),
            'roles' => $roles->pluck('role')->toArray(),
        ]);

        return $roles->whereIn('role', ['owner', 'admin'])->count() > 0;
    }

    /**
     * Only organisation owners may setup governance structure.
     */
    public function setupGovernance(User $user, Organisation $organisation): bool
    {
        return UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->where('role', 'owner')
            ->exists() &&
            $organisation->governance_status !== 'active';
    }

    /**
     * Only organisation owners may activate governance.
     */
    public function activateGovernance(User $user, Organisation $organisation): bool
    {
        return UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->where('role', 'owner')
            ->exists() &&
            $organisation->governance_status === 'governance_configured';
    }

    /**
     * View membership applications (owner, admin, or commission role).
     */
    public function viewApplications(User $user, Organisation $organisation): bool
    {
        return UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->whereIn('role', ['owner', 'admin', 'commission'])
            ->exists();
    }

    /**
     * Approve membership applications (owner or admin role).
     */
    public function approveApplication(User $user, Organisation $organisation): bool
    {
        return UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->whereIn('role', ['owner', 'admin'])
            ->exists();
    }

    /**
     * Reject membership applications (owner or admin role).
     */
    public function rejectApplication(User $user, Organisation $organisation): bool
    {
        return UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->whereIn('role', ['owner', 'admin'])
            ->exists();
    }

    /**
     * Record fee payments (owner or admin role).
     */
    public function recordFeePayment(User $user, Organisation $organisation): bool
    {
        return UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->whereIn('role', ['owner', 'admin'])
            ->exists();
    }

    /**
     * Manage membership types (owner only).
     */
    public function manageMembershipTypes(User $user, Organisation $organisation): bool
    {
        return UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->where('role', 'owner')
            ->exists();
    }

    /**
     * Initiate renewal (admin, owner, or member themselves).
     */
    public function initiateRenewal(User $user, Organisation $organisation, bool $isSelf = false): bool
    {
        if (UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->whereIn('role', ['owner', 'admin'])
            ->exists()) {
            return true;
        }

        if ($isSelf && UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->where('role', 'member')
            ->exists()) {
            return true;
        }

        return false;
    }
}
