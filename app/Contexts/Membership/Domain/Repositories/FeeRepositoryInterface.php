<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Repositories;

use App\Contexts\Membership\Domain\Fee\Fee;
use App\Contexts\Membership\Domain\Fee\FeeId;
use App\Contexts\Membership\Domain\Fee\FeeStatus;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;

interface FeeRepositoryInterface
{
    public function find(FeeId $id, TenantId $tenantId): ?Fee;

    public function save(Fee $fee, TenantId $tenantId): void;

    public function findByStatusForTenant(FeeStatus $status, TenantId $tenantId): array;

    public function findForMember(MemberId $memberId, TenantId $tenantId): array;

    public function findOverdueForTenant(TenantId $tenantId): array;

    public function findByTransactionReference(string $ref, TenantId $tenantId): ?Fee;
}
