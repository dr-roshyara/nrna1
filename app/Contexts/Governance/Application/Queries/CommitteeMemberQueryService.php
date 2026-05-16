<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\Queries;

use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Models\CommitteeMemberProjection;

/**
 * CommitteeMemberQueryService
 *
 * CQRS Read Side: Projection-only queries for committee membership.
 *
 * Invariants:
 * - Zero domain logic (no Committee aggregate loaded)
 * - No joins to domain tables
 * - Projection table is source of truth for reads
 * - Tenant-scoped by contract
 */
final readonly class CommitteeMemberQueryService
{
    public function getMembersForCommittee(
        CommitteeId $committeeId,
        TenantId $tenantId
    ): array
    {
        return CommitteeMemberProjection::query()
            ->where('committee_id', $committeeId->value())
            ->where('tenant_id', $tenantId->value())
            ->orderBy('assigned_at', 'desc')
            ->get()
            ->map(fn ($row) => [
                'memberId' => $row->member_id,
                'assignedAt' => $row->assigned_at?->toIso8601String(),
            ])
            ->toArray();
    }
}
