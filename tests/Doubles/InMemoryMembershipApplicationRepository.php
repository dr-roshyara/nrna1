<?php

declare(strict_types=1);

namespace Tests\Doubles;

use App\Contexts\Membership\Application\Membership\Ports\MembershipApplicationRepositoryPort;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\MembershipApplication;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipApplicationId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use RuntimeException;

final class InMemoryMembershipApplicationRepository implements MembershipApplicationRepositoryPort
{
    /** @var array<string, MembershipApplication> */
    private array $applications = [];

    public function saveForTenant(MembershipApplication $application): void
    {
        $this->applications[$application->id()->value()] = $application;
    }

    public function getOrFailForTenant(
        MembershipApplicationId $id,
        TenantId $tenantId
    ): MembershipApplication {
        if (!isset($this->applications[$id->value()])) {
            throw new RuntimeException(sprintf('Application %s not found', $id->value()));
        }

        $application = $this->applications[$id->value()];
        if (!$application->tenantId()->equals($tenantId)) {
            throw new RuntimeException(sprintf('Application %s not found in tenant', $id->value()));
        }

        return $application;
    }

    public function existsActiveForTenant(
        MemberId $memberId,
        CommitteeId $committeeId,
        TenantId $tenantId
    ): bool {
        foreach ($this->applications as $application) {
            if ($application->tenantId()->equals($tenantId)
                && $application->memberId()->equals($memberId)
                && $application->committeeId()->equals($committeeId)
                && $application->status()->isActive()) {
                return true;
            }
        }

        return false;
    }
}
