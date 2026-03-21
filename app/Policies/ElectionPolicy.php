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
     * Any active officer may view results.
     */
    public function viewResults(User $user, Election $election): bool
    {
        return $this->view($user, $election);
    }

    /**
     * Chief or deputy may manage election settings, control voting period, and manage voters.
     */
    public function manageSettings(User $user, Election $election): bool
    {
        return ElectionOfficer::where('user_id', $user->id)
            ->where('organisation_id', $election->organisation_id)
            ->whereIn('role', ['chief', 'deputy'])
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Chief only may publish or unpublish results.
     */
    public function publishResults(User $user, Election $election): bool
    {
        return ElectionOfficer::where('user_id', $user->id)
            ->where('organisation_id', $election->organisation_id)
            ->where('role', 'chief')
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Chief or deputy may manage voters (approve, bulk operations).
     */
    public function manageVoters(User $user, Election $election): bool
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
