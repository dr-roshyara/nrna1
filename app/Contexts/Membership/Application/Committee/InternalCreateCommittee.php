<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee;

use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeName;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeStructureRepositoryInterface;
use App\Contexts\Membership\Domain\Committee\CommitteeCreationPolicy;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Application\Committee\Policies\GovernanceAccessPolicyInterface;
use DomainException;

/**
 * InternalCreateCommittee
 *
 * @internal DO NOT instantiate directly. Always resolve via CreateCommitteeUseCase interface.
 *
 * Core orchestration for committee creation. Must always be wrapped in TransactionalCreateCommittee
 * for governance epoch snapshot safety.
 *
 * Key points:
 * - Uses findActiveByTenantForUpdate() for pessimistic locking (MUST run inside transaction)
 * - Captures complete governance snapshot at creation time
 * - Delegates validation to CommitteeCreationPolicy
 * - Checks governance access via policy seam (Phase B preparation)
 *
 * Phase C — Transactional Hardening
 */
final class InternalCreateCommittee implements CreateCommitteeUseCase
{
    public function __construct(
        private CommitteeStructureRepositoryInterface $structureRepo,
        private CommitteeRepositoryInterface $committeeRepo,
        private CommitteeCreationPolicy $policy,
        private GovernanceAccessPolicyInterface $accessPolicy
    ) {}

    public function execute(array $command): Committee
    {
        $tenantId = TenantId::fromString($command['tenantId']);

        // Step 0: Check governance access (Phase B seam - currently permissive)
        $this->accessPolicy->assertCanCreateCommittee($tenantId);

        // Step 1: Load active structure with pessimistic lock (G-007, G-008)
        // CRITICAL: This method MUST only be called inside an active transaction.
        // The TransactionalCreateCommittee decorator ensures this invariant.
        $structure = $this->structureRepo->findActiveByTenantForUpdate($tenantId);
        if ($structure === null) {
            throw new DomainException('No active committee structure defined for this organization');
        }

        // Step 2: Get level from structure
        $level = $structure->getLevel($command['levelIndex']);

        // Step 3: Parse geo reference (if provided)
        $geoReference = isset($command['geoReference']) && $command['geoReference'] !== null
            ? GeoReference::fromString($command['geoReference'])
            : null;

        // Step 4: Validate against policy (CRITICAL - validates structure + level + geo)
        $this->policy->assertCanCreate($structure, $level->index, $geoReference);

        // Step 5: Create committee with SNAPSHOT data (not structure object)
        // Snapshot captures governance epoch at this moment (lock held until transaction commits)
        $committee = Committee::create(
            id: CommitteeId::generate(),
            tenantId: $tenantId,
            structureId: $structure->getId(),
            levelIndex: $level->index,
            levelName: $level->name,
            geoPolicy: $level->geoPolicy,
            geoScope: $level->geoScope,
            name: CommitteeName::fromString($command['name']),
            code: $command['code'] ?? $this->generateCommitteeCode(),
            operationalGeo: $geoReference,
            structureVersion: $structure->version()
        );

        // Step 6: Persist (still inside transaction + lock)
        $this->committeeRepo->persist($committee);

        return $committee;
    }

    private function generateCommitteeCode(): string
    {
        return strtoupper(bin2hex(random_bytes(4)));
    }
}
