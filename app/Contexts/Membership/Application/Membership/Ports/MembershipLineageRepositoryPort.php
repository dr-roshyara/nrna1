<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Membership\Ports;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\MembershipLineage;
use App\Contexts\Membership\Domain\Membership\ValueObjects\LineageId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * MembershipLineageRepositoryPort
 *
 * LINEAGE-ONLY repository contract. All queries and operations are on the complete
 * aggregate, never on individual episodes. This enforces the aggregate boundary:
 * application layer can only access membership state via the lineage.
 *
 * PHASE A2.4 (Current): Aggregate Enforcement Lock
 * - No episode-level operations (no episode queries)
 * - Only lineage-level queries (findActiveLineagesByMember, etc.)
 * - Repository reconstructs full lineage (all episodes) on every load
 * - Replaces CommitteeAssociationRepositoryPort (strangler pattern)
 *
 * All method names reflect: these are LINEAGE operations, not episode operations.
 */
interface MembershipLineageRepositoryPort
{
    /**
     * Persist a lineage (create or update).
     *
     * Called after any lifecycle transition:
     * - establish(): creates new lineage with initial ACTIVE episode
     * - suspend(): adds SUSPENDED episode
     * - restore(): adds ACTIVE episode
     * - terminate(): adds TERMINATED episode
     * - reapply(): caller creates new lineage with reapplied episode
     *
     * Repository saves the latest episode to record current state.
     */
    public function saveForTenant(MembershipLineage $lineage, TenantId $tenantId): void;

    /**
     * Load a lineage by its identity.
     *
     * Returns the complete aggregate with full episode history (all episodes).
     * Used for lifecycle mutations (suspend, restore, terminate).
     *
     * Returns null if lineage not found.
     */
    public function findByLineageIdForTenant(LineageId $lineageId, TenantId $tenantId): ?MembershipLineage;

    /**
     * Find all ACTIVE lineages for a member.
     *
     * Only returns lineages where currentStatus() == ACTIVE.
     * Used for checking current membership, eligibility queries.
     *
     * Returns complete aggregates with full episode histories.
     *
     * @return MembershipLineage[]
     */
    public function findActiveLineagesByMemberForTenant(
        MemberId $memberId,
        TenantId $tenantId,
    ): array;

    /**
     * Find all ACTIVE lineages for a committee.
     *
     * Only returns lineages where currentStatus() == ACTIVE.
     * Used for committee roster queries, committee member lists.
     *
     * Returns complete aggregates with full episode histories.
     *
     * @return MembershipLineage[]
     */
    public function findActiveLineagesByCommitteeForTenant(
        CommitteeId $committeeId,
        TenantId $tenantId,
    ): array;

    /**
     * Find any lineage (any status) linking member + committee + tenant.
     *
     * Returns the lineage if it exists (ACTIVE, SUSPENDED, or TERMINATED).
     * Used to check if member has prior relationship history with a committee:
     * - During application: check if already ACTIVE (reject)
     * - During reapplication: check if TERMINATED (allow new lineage)
     *
     * Returns complete aggregate with full episode history.
     * Returns null if no relationship exists.
     */
    public function findLineageByMemberAndCommitteeForTenant(
        MemberId $memberId,
        CommitteeId $committeeId,
        TenantId $tenantId,
    ): ?MembershipLineage;

    /**
     * Find all lineages for a member (all statuses, all committees).
     *
     * Returns complete aggregates (ACTIVE, SUSPENDED, TERMINATED).
     * Used for member profile: "Show all my committee memberships (past and present)".
     * Used for audit trails and historical queries.
     *
     * Returns complete aggregates with full episode histories.
     *
     * @return MembershipLineage[]
     */
    public function findAllByMemberForTenant(MemberId $memberId, TenantId $tenantId): array;
}
