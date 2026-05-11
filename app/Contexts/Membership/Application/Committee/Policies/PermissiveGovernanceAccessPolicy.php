<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee\Policies;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * PermissiveGovernanceAccessPolicy
 *
 * Phase C default: Always permits committee creation.
 *
 * Used until Phase B implements actual governance status checks.
 * This seam allows Phase B to be plugged in later without refactoring Phase C.
 */
final class PermissiveGovernanceAccessPolicy implements GovernanceAccessPolicyInterface
{
    public function assertCanCreateCommittee(TenantId $tenantId): void
    {
        // Phase C: Allow all committee creations
        // Phase B will override this with actual governance status enforcement
    }
}
