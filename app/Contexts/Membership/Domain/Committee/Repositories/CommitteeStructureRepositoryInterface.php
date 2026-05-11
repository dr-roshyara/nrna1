<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Repositories;

use App\Contexts\Membership\Domain\Committee\CommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

interface CommitteeStructureRepositoryInterface
{
    public function persist(CommitteeStructure $structure): void;

    public function findActiveByTenant(TenantId $tenantId): ?CommitteeStructure;

    public function findAllActiveByTenant(TenantId $tenantId): array;

    public function findById(CommitteeStructureId $id): ?CommitteeStructure;

    public function findActiveByTenantForUpdate(TenantId $tenantId): ?CommitteeStructure;

    /**
     * Find the DRAFT successor of a given structure (Invariant G-003)
     *
     * @param CommitteeStructureId $parentId Parent structure ID
     * @return CommitteeStructure|null The DRAFT successor, or null if none exists
     */
    public function findDraftSuccessorOf(CommitteeStructureId $parentId): ?CommitteeStructure;

    /**
     * Find a structure by its version number within a tenant (Invariant G-002)
     *
     * @param TenantId $tenantId Tenant identifier
     * @param int $version Version number to find
     * @return CommitteeStructure|null The structure with this version, or null if not found
     */
    public function findVersion(TenantId $tenantId, int $version): ?CommitteeStructure;

    /**
     * Retrieve the complete lineage chain of a structure
     *
     * Returns all ancestors plus the structure itself, ordered from root to current.
     * Example: calling on v4 returns [v1, v2, v3, v4]
     *
     * @param CommitteeStructureId $structureId The structure to trace
     * @return array Array of CommitteeStructure ordered root-first
     */
    public function findLineageChain(CommitteeStructureId $structureId): array;

    /**
     * Hard delete a structure (use with caution)
     *
     * Only legal for DRAFT structures without descendants.
     * Used when discarding unpublished evolution attempts.
     *
     * @param CommitteeStructure $structure The structure to delete
     * @throws \DomainException If structure cannot be deleted
     */
    public function hardDelete(CommitteeStructure $structure): void;
}
