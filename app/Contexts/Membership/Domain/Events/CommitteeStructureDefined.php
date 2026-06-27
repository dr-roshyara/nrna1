<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Events;

use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Shared\Domain\Events\AbstractDomainEvent;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class CommitteeStructureDefined extends AbstractDomainEvent
{
    public function __construct(
        private readonly CommitteeStructureId $structureId,
        private readonly TenantId $tenantId,
    ) {
        parent::__construct();
    }

    public function structureId(): CommitteeStructureId
    {
        return $this->structureId;
    }

    public function tenantId(): TenantId
    {
        return $this->tenantId;
    }
}
