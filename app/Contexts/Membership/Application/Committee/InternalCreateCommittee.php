<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee;

use App\Contexts\Membership\Application\Committee\DTOs\InternalCreateCommitteeCommand;
use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\Committee\CommitteeCreationPolicy;
use App\Contexts\Membership\Domain\Committee\Factories\CommitteeGeoIdentityFactory;
use App\Contexts\Membership\Domain\Committee\Policies\CommitteeClassificationPolicy;
use App\Contexts\Membership\Domain\Committee\Services\CommitteePolicyResolver;
use App\Contexts\Membership\Domain\Committee\Services\GeoSemanticProjectionBuilder;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeStructureRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeName;
use App\Contexts\Membership\Application\Committee\Policies\GovernanceAccessPolicyInterface;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;
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
 * - Delegates type/structure/level resolution to CommitteePolicyResolver
 * - Checks governance access via policy seam (Phase B preparation)
 */
final class InternalCreateCommittee implements CreateCommitteeUseCase
{
    public function __construct(
        private CommitteeStructureRepositoryInterface $structureRepo,
        private CommitteeRepositoryInterface $committeeRepo,
        private CommitteeCreationPolicy $policy,
        private GovernanceAccessPolicyInterface $accessPolicy,
        private CommitteePolicyResolver $policyResolver,
        private CommitteeGeoIdentityFactory $geoIdentityFactory,
        private GeoSemanticProjectionBuilder $projectionBuilder,
        private CommitteeClassificationPolicy $classificationPolicy,
    ) {}

    public function execute(InternalCreateCommitteeCommand $command): Committee
    {
        // Step 0: Check governance access (Phase B seam - currently permissive)
        $this->accessPolicy->assertCanCreateCommittee($command->tenantId);

        // Step 1: Load active structure with pessimistic lock (G-007, G-008)
        $structure = $this->structureRepo->findActiveByTenantForUpdate($command->tenantId);
        if ($structure === null) {
            throw new DomainException('No active committee structure defined for this organization');
        }

        // Step 2: Resolve governance policy (type, structure, level) from command
        $policy = $this->policyResolver->resolve($structure, $command);

        // Step 3: Parse geo reference using canonical Geography VO
        $geoReference = $command->geoReference !== null
            ? GeoReference::fromString($command->geoReference)
            : null;

        // Step 4: Validate against policy (CRITICAL - validates structure + level + geo)
        $this->policy->assertCanCreate($structure, $policy->level->index, $geoReference);

        // Phase 8C.2C: Assemble geo identity if geo_unit_id provided
        $geoIdentity = $command->geoUnitId !== null
            ? $this->geoIdentityFactory->create($command->geoUnitId)
            : null;

        // Step 5: Create committee with SNAPSHOT data and resolved policy
        $committee = Committee::create(
            id: CommitteeId::generate(),
            tenantId: $command->tenantId,
            policy: $policy,
            structureId: $structure->getId(),
            levelIndex: $policy->level->index,
            levelName: $policy->level->name,
            geoPolicy: $policy->level->geoPolicy,
            geoScope: $policy->level->geoScope,
            name: CommitteeName::fromString($command->committeeName),
            code: $command->committeeCode,
            operationalGeo: $geoReference,
            structureVersion: $structure->version(),
            regionCode: $command->regionCode,
            countryCode: $command->countryCode,
            geoUnitId: $command->geoUnitId,
            geoIdentity: $geoIdentity,
        );

        // Step 6: Persist (still inside transaction + lock)
        $this->committeeRepo->persist($committee);

        return $committee;
    }
}
