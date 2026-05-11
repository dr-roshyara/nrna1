<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\Ports;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

interface CommitteeProjectionRebuildRepository
{
    /**
     * Stream committee IDs and names for rebuild.
     *
     * @param TenantId|null $tenantId Optional tenant scope
     * @return iterable<array{id: string, name: string, organisation_id: string}>
     */
    public function streamCommitteeIds(?TenantId $tenantId = null): iterable;

    /**
     * Count committees eligible for rebuild.
     */
    public function countCommittees(?TenantId $tenantId = null): int;
}
