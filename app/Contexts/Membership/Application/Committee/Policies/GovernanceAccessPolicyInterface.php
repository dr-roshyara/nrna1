<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\Policies;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * GovernanceAccessPolicyInterface
 *
 * Phase B preparation: Policy enforcement for governance-aware actions.
 *
 * Currently (Phase C): All implementations are permissive (always allow).
 * In Phase B: Will enforce organization governance status and user permissions.
 *
 * Seam in constructor allows Phase B to override without refactoring Phase C code.
 */
interface GovernanceAccessPolicyInterface
{
    /**
     * Assert that a committee can be created under the given tenant's governance.
     *
     * @param TenantId $tenantId
     * @throws \DomainException if governance status forbids creation
     */
    public function assertCanCreateCommittee(TenantId $tenantId): void;
}
