<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Repositories;

use App\Contexts\Membership\Domain\Member\Member;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Member\MemberStatus;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

interface MemberRepositoryInterface
{
    public function find(MemberId $id, TenantId $tenantId): ?Member;

    public function save(Member $member, TenantId $tenantId, ?string $organisationUserId = null): void;

    public function findByStatusForTenant(MemberStatus $status, TenantId $tenantId): array;

    public function findExpiringForTenant(TenantId $tenantId, int $withinDays): array;
}
