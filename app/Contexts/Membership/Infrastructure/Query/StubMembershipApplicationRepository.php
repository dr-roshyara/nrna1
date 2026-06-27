<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Query;

use App\Contexts\Membership\Application\Membership\Ports\MembershipApplicationRepositoryPort;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\MembershipApplication;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipApplicationId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Safe-default stub for F1 integration.
 * Returns no active applications. Replace with real Eloquent impl in Phase F2.
 */
final class StubMembershipApplicationRepository implements MembershipApplicationRepositoryPort
{
    public function saveForTenant(MembershipApplication $application): void {}

    public function getOrFailForTenant(
        MembershipApplicationId $id,
        TenantId $tenantId
    ): MembershipApplication {
        throw new \RuntimeException('Not implemented in F1 stub');
    }

    public function existsActiveForTenant(
        MemberId $memberId,
        CommitteeId $committeeId,
        TenantId $tenantId
    ): bool {
        return false;
    }
}
