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
use DomainException;

/**
 * @deprecated Use CreateCommitteeHandler in UseCases\CreateCommittee instead.
 * This legacy class will be removed after all consumers are migrated to the new
 * CreateCommitteeHandler which uses GovernancePolicy and ConstitutionalCommittee.
 * The new handler is matrix-aware and event-sourced.
 */
final class CreateCommittee
{
    public function __construct(
        private CommitteeStructureRepositoryInterface $structureRepo,
        private CommitteeRepositoryInterface $committeeRepo,
        private CommitteeCreationPolicy $policy
    ) {}

    public function execute(array $command): Committee
    {
        $tenantId = TenantId::fromString($command['tenantId']);

        // Step 1: Load active structure (REQUIRED)
        $structure = $this->structureRepo->findActiveByTenant($tenantId);
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
            operationalGeo: $geoReference
        );

        // Step 6: Persist
        $this->committeeRepo->persist($committee);

        return $committee;
    }

    private function generateCommitteeCode(): string
    {
        return strtoupper(bin2hex(random_bytes(4)));
    }
}
