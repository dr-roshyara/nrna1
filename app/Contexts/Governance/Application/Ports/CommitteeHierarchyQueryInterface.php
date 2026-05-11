<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\Ports;

use App\Contexts\Governance\Application\DTOs\CommitteeHierarchyRecord;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

interface CommitteeHierarchyQueryInterface
{
    /** @return CommitteeHierarchyRecord[] */
    public function execute(TenantId $tenantId): array;

    public function getProjectionGeneration(TenantId $tenantId): string;

    public function invalidateCache(TenantId $tenantId): void;
}
