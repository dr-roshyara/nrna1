<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Membership\Ports;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\MembershipApplication;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipApplicationId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use RuntimeException;

interface MembershipApplicationRepositoryPort
{
    /**
     * Save a membership application (TenantId extracted from aggregate).
     */
    public function saveForTenant(MembershipApplication $application): void;

    /**
     * Get application by ID for a specific tenant.
     *
     * @throws RuntimeException If not found
     */
    public function getOrFailForTenant(
        MembershipApplicationId $id,
        TenantId $tenantId
    ): MembershipApplication;

    /**
     * Check if any active application exists for member+committee in this tenant.
     * Active status: SUBMITTED or UNDER_REVIEW.
     */
    public function existsActiveForTenant(
        MemberId $memberId,
        CommitteeId $committeeId,
        TenantId $tenantId
    ): bool;
}
