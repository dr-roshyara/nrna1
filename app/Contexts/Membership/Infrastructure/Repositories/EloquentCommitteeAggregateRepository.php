<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Repositories;

use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\Committee\GeoPolicy;
use App\Contexts\Membership\Domain\Committee\GeoScope;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeGeoIdentity;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Contexts\Membership\Infrastructure\Services\CanonicalGeoSerializer;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Committee Aggregate Repository (Phase 5)
 *
 * CRITICAL: This is purely mechanical persistence.
 * Zero business logic.
 * Zero structure coupling.
 * Zero re-validation.
 *
 * Committee snapshots are immutable after creation.
 */
final class EloquentCommitteeAggregateRepository implements CommitteeRepositoryInterface
{
    public function __construct(
        private readonly CanonicalGeoSerializer $serializer,
    ) {}

    public function persist(Committee $committee): void
    {
        CommitteeModel::updateOrCreate(
            ['id' => $committee->getId()->value()],
            [
                'organisation_id' => $committee->getTenantId()->value(),
                'name' => $committee->getName()->value(),
                'code' => $committee->code(),
                'operational_geo_reference' => $committee->getOperationalGeoReference()?->value(),
                'region_code' => $committee->getRegionCode(),
                'country_code' => $committee->getCountryCode(),
                'type' => $committee->type()->value(),
                'status' => $committee->getStatus()->value(),
                // Governance snapshot fields (immutable temporal identity - write-once)
                'created_from_structure_id' => $committee->structureId()?->value(),
                'snapshot_level_index' => $committee->levelIndex(),
                'snapshot_level_name' => $committee->levelName(),
                'snapshot_geo_policy' => $committee->geoPolicy()?->value,
                'snapshot_geo_scope' => $committee->geoScope()?->code,
                'snapshot_structure_version' => $committee->structureVersion(),
                'snapshot_taken_at' => now(),
                'geo_unit_id' => $committee->getGeoUnitId(),
                'canonical_geo_id' => $this->serializer->serialize(
                    $committee->getGeoUnitId(),
                    $committee->getRegionCode(),
                    $committee->getCountryCode(),
                ),
            ]
        );
    }

    public function findById(CommitteeId $id): ?Committee
    {
        $model = CommitteeModel::withoutGlobalScopes()
            ->where('id', $id->value())
            ->first();

        return $model ? $this->mapToDomain($model) : null;
    }

    public function findByTenant(TenantId $tenantId): array
    {
        $models = CommitteeModel::query()
            ->where('organisation_id', $tenantId->value())
            ->get();

        return $models->map(fn($model) => $this->mapToDomain($model))->toArray();
    }

    private function mapToDomain(CommitteeModel $model): Committee
    {
        return Committee::reconstruct(
            id: CommitteeId::fromString($model->id),
            tenantId: TenantId::fromString($model->organisation_id),
            type: \App\Contexts\Membership\Domain\ValueObjects\CommitteeType::fromString($model->type),
            name: \App\Contexts\Membership\Domain\ValueObjects\CommitteeName::fromString($model->name),
            code: $model->code,
            operationalGeo: $model->operational_geo_reference
                ? \App\Contexts\Membership\Domain\ValueObjects\GeoReference::fromString($model->operational_geo_reference)
                : null,
            status: \App\Contexts\Membership\Domain\ValueObjects\CommitteeStatus::fromString($model->status),
            structure: new \App\Contexts\Membership\Domain\Committee\Strategies\CentralCommitteeStructure(),
            assignments: [],
            structureId: $model->created_from_structure_id ? CommitteeStructureId::fromString($model->created_from_structure_id) : null,
            levelIndex: $model->snapshot_level_index,
            levelName: $model->snapshot_level_name,
            geoPolicy: $model->snapshot_geo_policy ? GeoPolicy::from($model->snapshot_geo_policy) : null,
            geoScope: $model->snapshot_geo_scope ? new GeoScope($model->snapshot_geo_scope) : null,
            structureVersion: $model->snapshot_structure_version,
            regionCode: $model->region_code,
            countryCode: $model->country_code,
            geoUnitId: $model->geo_unit_id,
            geoIdentity: $model->geo_unit_id !== null
                ? new CommitteeGeoIdentity((int) $model->geo_unit_id)
                : null,
        );
    }
}
