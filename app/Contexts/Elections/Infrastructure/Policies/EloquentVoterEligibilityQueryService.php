<?php

namespace App\Contexts\Elections\Infrastructure\Policies;

use App\Contexts\Elections\Domain\Policies\VoterEligibilityPolicy;
use App\Contexts\Elections\Domain\ValueObjects\EligibilityContext;
use App\Domain\Election\Enum\VoterSourceStrategy;
use App\Models\OrganisationUser;
use Illuminate\Support\Facades\DB;

/**
 * EloquentVoterEligibilityQueryService — Bridge Between Infrastructure & Domain
 *
 * Responsibility:
 * - Query Eloquent models for eligibility data
 * - Build EligibilityContext (pure DTO)
 * - Delegate decision to ElectionOnlyPolicy or FullMembershipPolicy
 *
 * This is the INFRASTRUCTURE layer implementation of VoterEligibilityPolicy.
 * It handles all database queries and model orchestration.
 *
 * For election-only mode:
 * - Queries organisation_users table
 * - Checks: active status, not soft-deleted
 *
 * For full membership mode:
 * - Queries members table
 * - Checks: active status, fees paid/exempt, voting rights granted
 *
 * Architecture: Policy pattern with adapter
 * - Domain policies contain pure decision logic (no DB)
 * - This service wraps policies with infrastructure concerns
 */
final class EloquentVoterEligibilityQueryService implements VoterEligibilityPolicy
{
    public function __construct(
        private readonly \App\Contexts\Elections\Domain\Policies\ElectionOnlyPolicy $electionOnlyPolicy,
        private readonly \App\Contexts\Elections\Domain\Policies\FullMembershipPolicy $fullMembershipPolicy,
    ) {}

    /**
     * Determine eligibility via Eloquent queries + domain policy
     *
     * Phase B: Policy governs reads. Model methods stay unchanged (they remain for now).
     * Phase C: Model methods will delegate to this policy when called.
     */
    public function isEligible(
        string $userId,
        string $organisationId,
        VoterSourceStrategy $mode
    ): bool {
        // Build context from database queries
        $context = $this->buildContext($userId, $organisationId, $mode);

        // Delegate to appropriate policy
        if ($mode->isImportedVoterRegistry()) {
            return $this->electionOnlyPolicy->decideForContext($context);
        }

        // Full membership mode
        return $this->fullMembershipPolicy->decideForContext($context);
    }

    /**
     * Bulk eligibility filter — returns only qualifying user IDs.
     * Single DB query per mode. No N+1.
     */
    public function qualifyingSubset(
        array $userIds,
        string $organisationId,
        VoterSourceStrategy $mode
    ): array {
        if (empty($userIds)) {
            return [];
        }

        if ($mode->isImportedVoterRegistry()) {
            return $this->qualifyingSubsetElectionOnly($userIds, $organisationId);
        }

        return $this->qualifyingSubsetFullMembership($userIds, $organisationId);
    }

    /**
     * Filter users for election-only mode with single query
     */
    private function qualifyingSubsetElectionOnly(
        array $userIds,
        string $organisationId
    ): array {
        return DB::table('organisation_users')
            ->whereIn('user_id', $userIds)
            ->where('organisation_id', $organisationId)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->distinct()
            ->pluck('user_id')
            ->toArray();
    }

    /**
     * Filter users for full membership mode with single query
     */
    private function qualifyingSubsetFullMembership(
        array $userIds,
        string $organisationId
    ): array {
        return DB::table('members')
            ->join('organisation_users', 'members.organisation_user_id', '=', 'organisation_users.id')
            ->leftJoin('membership_types', 'members.membership_type_id', '=', 'membership_types.id')
            ->whereIn('organisation_users.user_id', $userIds)
            ->where('members.organisation_id', $organisationId)
            ->where('members.status', 'active')
            ->whereIn('members.fees_status', ['paid', 'exempt'])
            ->where(fn ($q) => $q->whereNull('members.membership_type_id')
                                 ->orWhere('membership_types.grants_voting_rights', true))
            ->where(fn ($q) => $q->whereNull('members.membership_expires_at')
                                 ->orWhere('members.membership_expires_at', '>', now()))
            ->whereNull('members.deleted_at')
            ->distinct()
            ->pluck('organisation_users.user_id')
            ->toArray();
    }

    /**
     * Build EligibilityContext from database queries
     *
     * No decision logic here — just data gathering.
     * Queries are mode-specific but context structure is unified.
     */
    private function buildContext(
        string $userId,
        string $organisationId,
        VoterSourceStrategy $mode
    ): EligibilityContext {
        if ($mode->isImportedVoterRegistry()) {
            return $this->buildElectionOnlyContext($userId, $organisationId);
        }

        return $this->buildFullMembershipContext($userId, $organisationId);
    }

    /**
     * Build context for election-only mode
     *
     * Queries organisation_users table only.
     * Uses withoutGlobalScopes since organisationId is explicit parameter.
     */
    private function buildElectionOnlyContext(
        string $userId,
        string $organisationId
    ): EligibilityContext {
        $record = OrganisationUser::query()
            ->withoutGlobalScopes()
            ->where('user_id', $userId)
            ->where('organisation_id', $organisationId)
            ->first();

        return new EligibilityContext(
            userId: $userId,
            organisationId: $organisationId,
            mode: VoterSourceStrategy::ImportedVoterRegistry,
            isActive: $record?->status === 'active',
            isDeleted: $record?->deleted_at !== null,
        );
    }

    /**
     * Build context for full membership mode
     *
     * Queries members table + fees + voting rights.
     * (Implementation follows FullMembershipPolicy wiring in Phase B.1b)
     */
    private function buildFullMembershipContext(
        string $userId,
        string $organisationId
    ): EligibilityContext {
        // First find the organisation_user_id for this user
        $orgUser = OrganisationUser::query()
            ->withoutGlobalScopes()
            ->where('user_id', $userId)
            ->where('organisation_id', $organisationId)
            ->whereNull('deleted_at')
            ->first();

        if (! $orgUser) {
            return new EligibilityContext(
                userId: $userId,
                organisationId: $organisationId,
                mode: VoterSourceStrategy::MembershipRegistry,
                isActive: false,
                isDeleted: true,
            );
        }

        // Then find the member record
        $member = DB::table('members')
            ->where('organisation_user_id', $orgUser->id)
            ->where('organisation_id', $organisationId)
            ->whereNull('deleted_at')
            ->first();

        $feesStatus = $member?->fees_status;
        $membershipStatus = $member?->status;

        return new EligibilityContext(
            userId: $userId,
            organisationId: $organisationId,
            mode: VoterSourceStrategy::MembershipRegistry,
            isActive: $membershipStatus === 'active',
            isDeleted: false,
            membershipStatus: $membershipStatus,
            feesStatus: $feesStatus,
        );
    }
}
