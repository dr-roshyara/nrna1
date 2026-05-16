<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Policies;

use App\Contexts\Membership\Domain\Policies\MembershipTypePolicyInterface;
use App\Contexts\Membership\Domain\Repositories\MembershipTypeRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use InvalidArgumentException;

final class MembershipTypePolicy implements MembershipTypePolicyInterface
{
    public function __construct(
        private MembershipTypeRepositoryInterface $repository
    ) {}

    public function assertActive(MembershipTypeId $typeId, TenantId $tenantId): void
    {
        $type = $this->repository->findByIdForTenant($typeId, $tenantId);

        if ($type === null) {
            throw new InvalidArgumentException("Membership type not found");
        }

        if (!$type->isActive()) {
            throw new InvalidArgumentException("Membership type not active");
        }
    }
}
