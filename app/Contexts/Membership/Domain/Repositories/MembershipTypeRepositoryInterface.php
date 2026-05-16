<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Repositories;

use App\Contexts\Membership\Domain\ValueObjects\MembershipType;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

interface MembershipTypeRepositoryInterface
{
    public function findByIdForTenant(MembershipTypeId $typeId, TenantId $tenantId): ?MembershipType;
}
