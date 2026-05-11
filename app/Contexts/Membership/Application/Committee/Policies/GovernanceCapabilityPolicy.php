<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\Policies;

use App\Contexts\Membership\Domain\Committee\Capability\InstitutionalCapabilityPolicy;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class GovernanceCapabilityPolicy implements GovernanceAccessPolicyInterface
{
    public function __construct(
        private readonly GovernanceCapabilityContextFactory $factory,
        private readonly InstitutionalCapabilityPolicy $engine,
    ) {}

    public function assertCanCreateCommittee(TenantId $tenantId): void
    {
        $userId = auth()->id() ?? 'system';
        $ctx = $this->factory->build($userId, $tenantId);
        $this->engine->canCreateCommittee($ctx)->assertAllowed();
    }
}
