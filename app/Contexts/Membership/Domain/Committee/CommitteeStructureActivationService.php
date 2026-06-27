<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Domain Service: Handles cross-aggregate invariant for multi-tenancy.
 *
 * Invariant: Only ONE ACTIVE CommitteeStructure per tenant
 *
 * This is a domain service (not part of aggregate) because:
 * - Invariant crosses aggregate boundaries (multiple CommitteeStructures)
 * - Aggregate cannot load other aggregates
 * - Repository dependency is acceptable in domain services
 */
final class CommitteeStructureActivationService
{
    /**
     * @param CommitteeStructureRepositoryInterface $repository Repository interface (not Eloquent)
     */
    public function __construct(
        private readonly CommitteeStructureRepositoryInterface $repository
    ) {}

    /**
     * Activate a structure for a tenant, automatically deprecating any existing ACTIVE.
     *
     * @param TenantId $tenantId The organization
     * @param CommitteeStructure $structureToActivate The structure to activate (must be DRAFT)
     * @throws \DomainException if preconditions not met
     */
    public function activate(TenantId $tenantId, CommitteeStructure $structureToActivate): void
    {
        // Precondition: structure must be DRAFT
        if (!$structureToActivate->isDraft()) {
            throw new \DomainException('Can only activate DRAFT structures');
        }

        // Step 1: Find and deprecate current ACTIVE (if any)
        $currentActive = $this->repository->findActiveByTenant($tenantId);
        if ($currentActive !== null) {
            $currentActive->deprecate();
            $this->repository->save($currentActive);
        }

        // Step 2: Activate new structure
        $structureToActivate->activate();

        // Step 3: Persist new active structure
        // (Note: repository.save() is idempotent, so OK to call even if just created)
        $this->repository->save($structureToActivate);
    }
}
