<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\CommitteeStructure;

use App\Contexts\Membership\Domain\Committee\CommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeStructureRepositoryInterface;

/**
 * Activate Existing Draft Structure Use Case.
 *
 * CRITICAL: This use case has ZERO transaction logic.
 * Transaction is handled by infrastructure-level decorator: TransactionalActivateCommitteeStructure
 *
 * This separation ensures:
 * - Application layer is pure orchestration
 * - Transaction boundary is structural, not disciplinary
 * - Use case is testable in isolation
 *
 * @internal DO NOT instantiate directly.
 * Always resolve via ActivateCommitteeStructureUseCase (container binding).
 */
final class ActivateCommitteeStructure implements ActivateCommitteeStructureUseCase
{
    public function __construct(
        private CommitteeStructureRepositoryInterface $repository
    ) {}

    public function execute(array $command): CommitteeStructure
    {
        $tenantId = TenantId::fromString($command['tenantId']);
        $structureId = CommitteeStructureId::fromString($command['structureId']);
        $activatedBy = $command['activatedBy'] ?? 'system';

        // Load the structure to activate
        $structure = $this->repository->findById($structureId);

        // Validate it's in DRAFT state
        if (!$structure->isDraft()) {
            throw new \DomainException('Only draft structures can be activated');
        }

        // 🔐 Find existing ACTIVE structure with pessimistic lock (if any)
        // Lock is held for entire transaction duration (at decorator level)
        $existingActive = $this->repository->findActiveByTenantForUpdate($tenantId);

        // If an ACTIVE structure exists, deprecate it
        if ($existingActive !== null) {
            $existingActive->deprecate();
            $this->repository->persist($existingActive);
        }

        // Activate the new structure
        $structure->activate($activatedBy);
        $this->repository->persist($structure);

        return $structure;
    }
}

