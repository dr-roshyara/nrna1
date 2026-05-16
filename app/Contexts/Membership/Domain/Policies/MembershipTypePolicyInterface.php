<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Policies;

use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

interface MembershipTypePolicyInterface
{
    public function assertActive(MembershipTypeId $typeId, TenantId $tenantId): void;
}
