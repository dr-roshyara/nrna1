<?php

namespace App\Services;

use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class VoterEligibilityService
{
    /**
     * Check if a single user is eligible to vote in an organisation.
     *
     * - Election-only mode (uses_full_membership=false): Any active OrganisationUser can vote
     * - Full membership mode (uses_full_membership=true): Requires active Member with paid/exempt fees
     *
     * Used by ElectionVoterController::store() validation.
     */
    public function isEligibleVoter(Organisation $org, User $user): bool
    {
        if (!$org->uses_full_membership) {
            // Election-only mode: any active organisation user can vote
            return OrganisationUser::where('organisation_id', $org->id)
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->whereNull('deleted_at')
                ->exists();
        }

        // Full membership mode: delegate to existing User method
        return $user->isEligibleVoter($org);
    }

    /**
     * Build a query of eligible, unassigned voters for a dropdown or bulk list.
     *
     * - Election-only mode: returns all active OrganisationUsers
     * - Full membership mode: returns active Members with paid/exempt fees
     *
     * Optionally filters out users already assigned (excludeUserIds).
     *
     * Used by ElectionVoterController::index() and bulkStore().
     */
    public function unassignedEligibleQuery(
        Organisation $org,
        array $excludeUserIds = []
    ): Builder {
        if (!$org->uses_full_membership) {
            // Election-only mode: all active org users not yet assigned
            return DB::table('organisation_users')
                ->join('users', 'organisation_users.user_id', '=', 'users.id')
                ->where('organisation_users.organisation_id', $org->id)
                ->where('organisation_users.status', 'active')
                ->whereNull('organisation_users.deleted_at')
                ->whereNotIn('organisation_users.user_id', $excludeUserIds)
                ->distinct()
                ->select('users.id', 'users.name', 'users.email')
                ->orderBy('users.name');
        }

        // Full membership mode: only members with paid/exempt fees
        // This is the existing query from ElectionVoterController::index()
        return DB::table('members')
            ->join('organisation_users', 'members.organisation_user_id', '=', 'organisation_users.id')
            ->leftJoin('membership_types', 'members.membership_type_id', '=', 'membership_types.id')
            ->join('users', 'organisation_users.user_id', '=', 'users.id')
            ->where('members.organisation_id', $org->id)
            ->where('members.status', 'active')
            ->whereIn('members.fees_status', ['paid', 'exempt'])
            ->where(fn ($q) => $q->whereNull('members.membership_type_id')
                                 ->orWhere('membership_types.grants_voting_rights', true))
            ->where(fn ($q) => $q->whereNull('members.membership_expires_at')
                                 ->orWhere('members.membership_expires_at', '>', now()))
            ->whereNull('members.deleted_at')
            ->whereNotIn('organisation_users.user_id', $excludeUserIds)
            ->distinct()
            ->select('users.id', 'users.name', 'users.email')
            ->orderBy('users.name');
    }
}
