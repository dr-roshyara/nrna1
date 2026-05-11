<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Infrastructure\Repositories;

use App\Contexts\Governance\Application\Ports\CommitteeProjectionRebuildRepository;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class EloquentCommitteeProjectionRebuildRepository implements CommitteeProjectionRebuildRepository
{
    public function streamCommitteeIds(?TenantId $tenantId = null): iterable
    {
        return CommitteeModel::withoutGlobalScopes()
            ->when($tenantId, fn($q) => $q->where('organisation_id', $tenantId->value()))
            ->select(['id', 'name', 'organisation_id'])
            ->orderBy('id')
            ->chunkById(100, fn($chunk) => yield from $chunk);
    }

    public function countCommittees(?TenantId $tenantId = null): int
    {
        return CommitteeModel::withoutGlobalScopes()
            ->when($tenantId, fn($q) => $q->where('organisation_id', $tenantId->value()))
            ->count();
    }
}
