<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Repositories;

use App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\MembershipLineage;
use App\Contexts\Membership\Domain\Membership\ValueObjects\LineageId;
use App\Contexts\Membership\Infrastructure\Models\CommitteeAssociationModel;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Database\Eloquent\Builder;

/**
 * Eloquent Implementation of MembershipLineageRepository
 *
 * Hydrates MembershipLineage aggregates from CommitteeAssociation episodes.
 *
 * Key semantic:
 * - One lineage = one or more episodes (CommitteeAssociation records)
 * - All episodes for a lineage share the same lineage_id, member_id, committee_id, organisation_id
 * - Episodes are immutable snapshots in the lineage's constitutional history
 *
 * PHASE A2.4: Lineage-Only Operations
 * - saveForTenant() saves only the LATEST episode (current state)
 * - findActiveLineagesByMemberForTenant() and findActiveLineagesByCommitteeForTenant() added
 * - All loads reconstruct FULL lineage (all episodes) for consistency
 * - No episode-level queries exposed (only lineage-level)
 */
final class EloquentMembershipLineageRepository implements MembershipLineageRepositoryPort
{
    public function saveForTenant(MembershipLineage $lineage, TenantId $tenantId): void
    {
        // CRITICAL: Only save the LATEST episode (current state).
        // When a lifecycle transition happens (suspend, restore, terminate),
        // the aggregate adds a new episode. We persist only that new episode.
        // The full history is loaded via loadEpisodesForLineage() when needed.

        $currentEpisode = $lineage->current();

        CommitteeAssociationModel::create([
            'organisation_id' => $tenantId->value(),
            'lineage_id' => $lineage->lineageId->value(),
            'member_id' => $currentEpisode->memberId->value(),
            'committee_id' => $currentEpisode->committeeId->value(),
            'association_type' => $currentEpisode->associationType->value,
            'status' => $currentEpisode->status->value,
            'associated_at' => $currentEpisode->associatedAt,
            'association_id' => $currentEpisode->associationId->value(),
            'approved_by_actor_id' => null,
            'approved_by_actor_type' => null,
            'suspended_by_actor_id' => null,
            'suspended_by_actor_type' => null,
            'suspension_reason' => null,
            'terminated_by_actor_id' => null,
            'terminated_by_actor_type' => null,
            'termination_reason' => null,
        ]);
    }

    public function findByLineageIdForTenant(LineageId $lineageId, TenantId $tenantId): ?MembershipLineage
    {
        $episodes = $this->loadEpisodesForLineage($lineageId, $tenantId);

        if (empty($episodes)) {
            return null;
        }

        // Extract member+committee from first episode (same for all in lineage)
        $firstEpisode = reset($episodes);

        return MembershipLineage::reconstitute(
            lineageId: $lineageId,
            memberId: $firstEpisode->memberId,
            committeeId: $firstEpisode->committeeId,
            tenantId: $tenantId,
            episodes: $episodes,
        );
    }

    public function findActiveByMemberAndCommitteeForTenant(
        MemberId $memberId,
        CommitteeId $committeeId,
        TenantId $tenantId,
    ): ?MembershipLineage {
        $models = CommitteeAssociationModel::where('organisation_id', $tenantId->value())
            ->where('member_id', $memberId->value())
            ->where('committee_id', $committeeId->value())
            ->where('status', 'active')
            ->orderBy('created_at', 'asc')
            ->get();

        if ($models->isEmpty()) {
            return null;
        }

        // All episodes should have the same lineage_id (enforced by schema)
        $lineageId = LineageId::fromString($models->first()->lineage_id);

        $episodes = $this->hydrateEpisodes($models);

        return MembershipLineage::reconstitute(
            lineageId: $lineageId,
            memberId: $memberId,
            committeeId: $committeeId,
            tenantId: $tenantId,
            episodes: $episodes,
        );
    }

    public function findByMemberAndCommitteeForTenant(
        MemberId $memberId,
        CommitteeId $committeeId,
        TenantId $tenantId,
    ): ?MembershipLineage {
        // Return ANY status (ACTIVE, SUSPENDED, TERMINATED)
        // Used to check for prior relationships before reapplication
        $models = CommitteeAssociationModel::where('organisation_id', $tenantId->value())
            ->where('member_id', $memberId->value())
            ->where('committee_id', $committeeId->value())
            ->orderBy('created_at', 'asc')
            ->get();

        if ($models->isEmpty()) {
            return null;
        }

        $lineageId = LineageId::fromString($models->first()->lineage_id);

        $episodes = $this->hydrateEpisodes($models);

        return MembershipLineage::reconstitute(
            lineageId: $lineageId,
            memberId: $memberId,
            committeeId: $committeeId,
            tenantId: $tenantId,
            episodes: $episodes,
        );
    }

    public function findActiveLineagesByMemberForTenant(
        MemberId $memberId,
        TenantId $tenantId,
    ): array {
        // Find all lineages where the member is CURRENTLY ACTIVE (not SUSPENDED or TERMINATED)
        // Strategy: group by lineage_id, load all episodes for each, return only if current status is ACTIVE

        $models = CommitteeAssociationModel::where('organisation_id', $tenantId->value())
            ->where('member_id', $memberId->value())
            ->orderBy('lineage_id', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        if ($models->isEmpty()) {
            return [];
        }

        // Group by lineage_id and reconstruct each lineage
        $activeLineages = [];
        $currentLineageId = null;
        $lineageModels = [];

        foreach ($models as $model) {
            $modelLineageId = $model->lineage_id;

            if ($currentLineageId !== null && $modelLineageId !== $currentLineageId) {
                // New lineage encountered, check and reconstruct previous
                $lineage = $this->reconstructLineageFromModels($lineageModels, $tenantId);
                if ($lineage->isActive()) {
                    $activeLineages[] = $lineage;
                }
                $lineageModels = [];
            }

            $currentLineageId = $modelLineageId;
            $lineageModels[] = $model;
        }

        // Don't forget the last lineage
        if (!empty($lineageModels)) {
            $lineage = $this->reconstructLineageFromModels($lineageModels, $tenantId);
            if ($lineage->isActive()) {
                $activeLineages[] = $lineage;
            }
        }

        return $activeLineages;
    }

    public function findActiveLineagesByCommitteeForTenant(
        CommitteeId $committeeId,
        TenantId $tenantId,
    ): array {
        // Find all lineages for a committee where members are CURRENTLY ACTIVE
        $models = CommitteeAssociationModel::where('organisation_id', $tenantId->value())
            ->where('committee_id', $committeeId->value())
            ->orderBy('lineage_id', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        if ($models->isEmpty()) {
            return [];
        }

        // Group by lineage_id and reconstruct each lineage
        $activeLineages = [];
        $currentLineageId = null;
        $lineageModels = [];

        foreach ($models as $model) {
            $modelLineageId = $model->lineage_id;

            if ($currentLineageId !== null && $modelLineageId !== $currentLineageId) {
                // New lineage encountered, check and reconstruct previous
                $lineage = $this->reconstructLineageFromModels($lineageModels, $tenantId);
                if ($lineage->isActive()) {
                    $activeLineages[] = $lineage;
                }
                $lineageModels = [];
            }

            $currentLineageId = $modelLineageId;
            $lineageModels[] = $model;
        }

        // Don't forget the last lineage
        if (!empty($lineageModels)) {
            $lineage = $this->reconstructLineageFromModels($lineageModels, $tenantId);
            if ($lineage->isActive()) {
                $activeLineages[] = $lineage;
            }
        }

        return $activeLineages;
    }

    public function findLineageByMemberAndCommitteeForTenant(
        MemberId $memberId,
        CommitteeId $committeeId,
        TenantId $tenantId,
    ): ?MembershipLineage {
        // Delegate to existing method (same logic, single result)
        return $this->findByMemberAndCommitteeForTenant($memberId, $committeeId, $tenantId);
    }

    public function findAllByMemberForTenant(MemberId $memberId, TenantId $tenantId): array
    {
        $models = CommitteeAssociationModel::where('organisation_id', $tenantId->value())
            ->where('member_id', $memberId->value())
            ->orderBy('lineage_id', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        if ($models->isEmpty()) {
            return [];
        }

        // Group by lineage_id and reconstruct each lineage
        $lineages = [];
        $currentLineageId = null;
        $lineageModels = [];

        foreach ($models as $model) {
            $modelLineageId = $model->lineage_id;

            if ($currentLineageId !== null && $modelLineageId !== $currentLineageId) {
                // New lineage encountered, reconstruct previous
                $lineages[] = $this->reconstructLineageFromModels($lineageModels, $tenantId);
                $lineageModels = [];
            }

            $currentLineageId = $modelLineageId;
            $lineageModels[] = $model;
        }

        // Don't forget the last lineage
        if (!empty($lineageModels)) {
            $lineages[] = $this->reconstructLineageFromModels($lineageModels, $tenantId);
        }

        return $lineages;
    }

    /**
     * Load all episodes for a given lineage.
     *
     * @return CommitteeAssociation[]
     */
    private function loadEpisodesForLineage(LineageId $lineageId, TenantId $tenantId): array
    {
        $models = CommitteeAssociationModel::where('organisation_id', $tenantId->value())
            ->where('lineage_id', $lineageId->value())
            ->orderBy('created_at', 'asc')
            ->get();

        return $this->hydrateEpisodes($models);
    }

    /**
     * Hydrate CommitteeAssociation episodes from Eloquent models.
     *
     * @param \Illuminate\Database\Eloquent\Collection<CommitteeAssociationModel> $models
     * @return CommitteeAssociation[]
     */
    private function hydrateEpisodes($models): array
    {
        $episodes = [];

        foreach ($models as $model) {
            $episodes[] = new CommitteeAssociation(
                associationId: \App\Contexts\Membership\Domain\Membership\ValueObjects\AssociationId::fromString($model->association_id),
                memberId: MemberId::fromString($model->member_id),
                committeeId: CommitteeId::fromString($model->committee_id),
                associationType: \App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason::from($model->association_type),
                associatedAt: $model->associated_at,
                status: \App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus::from($model->status),
            );
        }

        return $episodes;
    }

    /**
     * Reconstruct a MembershipLineage from a collection of models.
     */
    private function reconstructLineageFromModels($models, TenantId $tenantId): MembershipLineage
    {
        $firstModel = reset($models);

        $lineageId = LineageId::fromString($firstModel->lineage_id);
        $memberId = MemberId::fromString($firstModel->member_id);
        $committeeId = CommitteeId::fromString($firstModel->committee_id);

        $episodes = $this->hydrateEpisodes($models);

        return MembershipLineage::reconstitute(
            lineageId: $lineageId,
            memberId: $memberId,
            committeeId: $committeeId,
            tenantId: $tenantId,
            episodes: $episodes,
        );
    }
}
