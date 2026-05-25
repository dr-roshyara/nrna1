<?php

namespace App\Services;

use App\Contexts\Elections\Domain\Policies\VoterEligibilityPolicy;
use App\Domain\Election\Enum\VoterSourceStrategy;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class VoterEligibilityService
{
    public function __construct(
        private readonly VoterEligibilityPolicy $policy,
    ) {}

    /**
     * Check if a single user is eligible to vote in an election.
     *
     * Phase 3A: Routes through policy layer (adapter pattern).
     * Policy governs all eligibility decisions using the election's constitutional snapshot.
     *
     * - Election-only mode: Any active OrganisationUser can vote
     * - Full membership mode: Requires active Member with paid/exempt fees
     *
     * IMPORTANT: Mode must be passed from election context — never derive internally.
     * Used by ElectionVoterController::store() validation.
     */
    public function isEligibleVoter(Organisation $org, User $user, VoterSourceStrategy $mode): bool
    {
        return $this->policy->isEligible(
            $user->id,
            $org->id,
            $mode
        );
    }

    /**
     * Build a query of eligible, unassigned voters for a dropdown or bulk list.
     *
     * - Election-only mode: returns all active OrganisationUsers
     * - Full membership mode: returns active Members with paid/exempt fees
     *
     * Optionally filters out users already assigned (excludeUserIds).
     *
     * CRITICAL: Mode must be passed from election context — never derive internally.
     * Respects election's voter_source_strategy snapshot, not org's mutable mode.
     *
     * Used by ElectionVoterController::index() and bulkStore().
     */
    public function unassignedEligibleQuery(
        Organisation $org,
        array $excludeUserIds = [],
        ?VoterSourceStrategy $mode = null
    ): Builder {
        $mode ??= VoterSourceStrategy::MembershipRegistry;

        if ($mode->isImportedVoterRegistry()) {
            // Election-only mode: all active org users not yet assigned
            // Use raw DB query to bypass BelongsToTenant global scope
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
