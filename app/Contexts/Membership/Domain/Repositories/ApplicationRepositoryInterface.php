<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Repositories;

use App\Contexts\Membership\Domain\Application\Application;
use App\Contexts\Membership\Domain\Application\ApplicationId;
use App\Contexts\Membership\Domain\Application\ApplicationStatus;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;

interface ApplicationRepositoryInterface
{
    public function find(ApplicationId $id, TenantId $tenantId): ?Application;

    public function save(Application $application, TenantId $tenantId): void;

    public function findByStatusForTenant(ApplicationStatus $status, TenantId $tenantId): array;

    public function findPendingForTenant(TenantId $tenantId): array;
}
