<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Repositories;

use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

interface CommitteeRepositoryInterface
{
    public function persist(Committee $committee): void;

    public function findById(CommitteeId $id): ?Committee;

    public function findByTenant(TenantId $tenantId): array;
}
