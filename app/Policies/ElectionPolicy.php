<?php

namespace App\Policies;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;

/**
 * ElectionPolicy
 *
 * Authorization for election management actions.
 * All roles are sourced from the election_officers table.
 *
 * chief      → all actions
 * deputy     → all except publishResults
 * commissioner → view and viewResults only
 */
class ElectionPolicy
{
    /**
     * Any active officer for this organisation may view voter management pages.
     */
    public function view(User $user, Election $election): bool
    {
        return ElectionOfficer::where('user_id', $user->id)
            ->where('organisation_id', $election->organisation_id)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Org owner/admin or any active officer may view results.
     */
    public function viewResults(User $user, Election $election): bool
    {
        return $this->view($user, $election);
    }

    /**
     * Chief, deputy, or organisation owner/admin may manage election settings.
     */
    public function manageSettings(User $user, Election $election): bool
    {
        $isOwnerAdmin = UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $election->organisation_id)
            ->whereIn('role', ['owner', 'admin'])
            ->exists();

        $isChiefDeputy = ElectionOfficer::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->whereIn('role', ['chief', 'deputy'])
            ->where('status', 'active')
            ->exists();

        return $isOwnerAdmin || $isChiefDeputy;
    }

    /**
     * Chief only may publish or unpublish results.
     */
    public function publishResults(User $user, Election $election): bool
    {
        return ElectionOfficer::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->where('role', 'chief')
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Chief or deputy may manage voters (add/remove).
     */
    public function manageVoters(User $user, Election $election): bool
    {
        return ElectionOfficer::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->whereIn('role', ['chief', 'deputy'])
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Chief or deputy may manage posts and candidacies.
     */
    public function managePosts(User $user, Election $election): bool
    {
        return $this->manageSettings($user, $election);
    }

    /**
     * Generic manage method for state machine operations.
     * Delegates to manageSettings for permission check.
     */
    public function manage(User $user, Election $election): bool
    {
        return $this->manageSettings($user, $election);
    }

    /**
     * Chief or deputy may create a new election for this organisation.
     * Receives Organisation (not Election) because no election exists yet.
     */
    public function create(User $user, Organisation $organisation): bool
    {
        return UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->whereIn('role', ['owner', 'admin'])
            ->exists();
    }
}
