<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Services;

use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

interface MembershipTypeValidationInterface
{
    /**
     * Validate that a membership type exists and is active for the tenant.
     *
     * @param MembershipTypeId $typeId The membership type to validate
     * @param TenantId $tenantId The tenant context
     * @throws \InvalidArgumentException If type not found or not active
     */
    public function ensureValid(MembershipTypeId $typeId, TenantId $tenantId): void;
}
