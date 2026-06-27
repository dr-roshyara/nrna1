<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\CommitteeStructure;

use App\Contexts\Membership\Domain\Committee\CommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Membership\Domain\Committee\CommitteeLevel;
use App\Contexts\Membership\Domain\Committee\GeoPolicy;
use App\Contexts\Membership\Domain\Committee\GeoScope;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeStructureRepositoryInterface;

final class DefineCommitteeStructure
{
    public function __construct(
        private CommitteeStructureRepositoryInterface $repository
    ) {}

    public function execute(array $command): CommitteeStructure
    {
        $tenantId = TenantId::fromString($command['tenantId']);
        $levels = $this->buildLevels($command['levels']);

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $tenantId,
            name: $command['name'],
            levels: $levels
        );

        $this->repository->persist($structure);

        return $structure;
    }

    private function buildLevels(array $levelsData): array
    {
        return array_map(function (array $levelData) {
            $geoPolicy = GeoPolicy::from($levelData['geoPolicy']);
            $geoScope = $levelData['geoScope'] ?? null;

            return CommitteeLevel::create(
                index: $levelData['index'],
                code: $levelData['code'] ?? null,
                name: $levelData['name'],
                geoPolicy: $geoPolicy,
                geoScope: $geoScope ? new GeoScope($geoScope) : null,
                roleLimits: $levelData['roleLimits'] ?? [],
                minMembershipYears: $levelData['minMembershipYears'] ?? 0,
                ageRange: $levelData['ageRange'] ?? null,
                genderRequirement: $levelData['genderRequirement'] ?? null
            );
        }, $levelsData);
    }
}
