<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Infrastructure\Repositories;

use App\Contexts\Governance\Application\DTOs\CommitteeHierarchyRecord;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;

class CommitteeHierarchyRepository
{
    // Projection fields are eventually consistent — NOT authoritative domain truth. See docs/ARCHITECTURE.md
    /**
     * @return CommitteeHierarchyRecord[]
     */
    public function getAll(TenantId $tenantId): array
    {
        $committees = CommitteeModel::forOrganisation($tenantId->value())
            ->leftJoin(
                'committee_governance_projections as cgp',
                'committees.id',
                '=',
                'cgp.committee_id',
            )
            ->select([
                'committees.*',
                'cgp.operational_state',
                'cgp.temporal_state',
                'cgp.legitimacy',
                'cgp.can_act',
                'cgp.is_fully_operational',
                'cgp.projection_version',
                'cgp.evaluated_at',
            ])
            ->orderBy('committees.level', 'asc')
            ->get();

        $records = [];
        foreach ($committees as $committee) {
            $records[] = new CommitteeHierarchyRecord(
                id: CommitteeId::fromString($committee->id),
                name: $committee->name,
                level: $committee->level,
                parentId: $committee->parent_committee_id !== null
                    ? CommitteeId::fromString($committee->parent_committee_id)
                    : null,
                operationalState: $committee->operational_state ?? 'UNKNOWN',
                temporalState: $committee->temporal_state ?? 'UNKNOWN',
                legitimacy: $committee->legitimacy ?? 'UNAUTHORIZED',
                canAct: (bool) ($committee->can_act ?? false),
                isFullyOperational: (bool) ($committee->is_fully_operational ?? false),
                projectionVersion: (int) ($committee->projection_version ?? 0),
                termStart: null,
                termEnd: $committee->term_end_date !== null
                    ? DateTimeImmutable::createFromMutable($committee->term_end_date->toDateTime())
                    : null,
                pendingApprovals: 0,
            );
        }

        return $records;
    }

    // Projection generation is eventually consistent — cache may return stale records. See docs/ARCHITECTURE.md
    /**
     * Get the current projection generation for cache versioning.
     */
    public function getProjectionGeneration(TenantId $tenantId): string
    {
        $latest = CommitteeModel::forOrganisation($tenantId->value())
            ->leftJoin(
                'committee_governance_projections as cgp',
                'committees.id',
                '=',
                'cgp.committee_id',
            )
            ->max('cgp.updated_at');

        return $latest !== null ? md5((string) $latest) : 'none';
    }
}
