<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Persistence\Repositories;

use App\Contexts\Membership\Domain\Committee\CommitteeLevel;
use App\Contexts\Membership\Domain\Committee\CommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Membership\Domain\Committee\GeoPolicy;
use App\Contexts\Membership\Domain\Committee\GeoScope;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeStructureRepositoryInterface;
use App\Contexts\Membership\Domain\Committee\StructureStatus;
use App\Contexts\Membership\Infrastructure\Persistence\Models\CommitteeStructureModel;
use App\Contexts\Membership\Infrastructure\Persistence\Models\CommitteeStructureLevelModel;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Repository: Translator between Eloquent persistence and Domain aggregate.
 *
 * CRITICAL: This repository has ZERO business logic.
 * All state transitions (draft, activate, deprecate) are orchestrated in the Application layer.
 * The database constraint (unique partial index) is the final enforcer.
 */
final class EloquentCommitteeStructureRepository implements CommitteeStructureRepositoryInterface
{
    public function persist(CommitteeStructure $structure): void
    {
        // State-agnostic persistence: DRAFT, ACTIVE, or DEPRECATED.
        // If invariant is violated (multiple ACTIVE), DB constraint will throw QueryException.
        // Application layer must handle all orchestration and state transitions.
        $this->persistStructure($structure);
    }

    public function findActiveByTenant(TenantId $tenantId): ?CommitteeStructure
    {
        $model = CommitteeStructureModel::query()
            ->where('organisation_id', $tenantId->value())
            ->where('status', StructureStatus::ACTIVE->value)
            ->with('levels')
            ->first();

        return $model ? $this->mapToDomain($model) : null;
    }

    public function findAllActiveByTenant(TenantId $tenantId): array
    {
        $models = CommitteeStructureModel::query()
            ->where('organisation_id', $tenantId->value())
            ->where('status', StructureStatus::ACTIVE->value)
            ->with('levels')
            ->get();

        return $models->map(fn ($model) => $this->mapToDomain($model))->toArray();
    }

    public function findById(CommitteeStructureId $id): ?CommitteeStructure
    {
        $model = CommitteeStructureModel::query()
            ->where('id', $id->value())
            ->with('levels')
            ->first();

        return $model ? $this->mapToDomain($model) : null;
    }

    public function findActiveByTenantForUpdate(TenantId $tenantId): ?CommitteeStructure
    {
        $model = CommitteeStructureModel::query()
            ->where('organisation_id', $tenantId->value())
            ->where('status', StructureStatus::ACTIVE->value)
            ->lockForUpdate()
            ->with('levels')
            ->first();

        return $model ? $this->mapToDomain($model) : null;
    }

    public function findDraftSuccessorOf(CommitteeStructureId $parentId): ?CommitteeStructure
    {
        $model = CommitteeStructureModel::query()
            ->where('parent_structure_id', $parentId->value())
            ->where('status', StructureStatus::DRAFT->value)
            ->with('levels')
            ->first();

        return $model ? $this->mapToDomain($model) : null;
    }

    public function findVersion(TenantId $tenantId, int $version): ?CommitteeStructure
    {
        $model = CommitteeStructureModel::query()
            ->where('organisation_id', $tenantId->value())
            ->where('version', $version)
            ->with('levels')
            ->first();

        return $model ? $this->mapToDomain($model) : null;
    }

    public function findLineageChain(CommitteeStructureId $structureId): array
    {
        $chain = [];
        $currentId = $structureId;

        while ($currentId !== null) {
            $model = CommitteeStructureModel::query()
                ->where('id', $currentId->value())
                ->with('levels')
                ->first();

            if ($model === null) {
                break;
            }

            $chain[] = $this->mapToDomain($model);
            $currentId = $model->parent_structure_id ? CommitteeStructureId::fromString($model->parent_structure_id) : null;
        }

        return array_reverse($chain);
    }

    public function hardDelete(CommitteeStructure $structure): void
    {
        CommitteeStructureModel::query()
            ->where('id', $structure->getId()->value())
            ->forceDelete();

        CommitteeStructureLevelModel::query()
            ->where('committee_structure_id', $structure->getId()->value())
            ->forceDelete();
    }

    private function persistStructure(CommitteeStructure $structure): void
    {
        $structureId = $structure->getId()->value();
        $tenantId = $structure->getTenantId()->value();

        CommitteeStructureModel::updateOrCreate(
            ['id' => $structureId],
            [
                'organisation_id' => $tenantId,
                'name' => $structure->name(),
                'status' => $structure->status()->value,
                'version' => $structure->version(),
                'parent_structure_id' => $structure->parentStructureId()?->value(),
            ]
        );

        $model = CommitteeStructureModel::findOrFail($structureId);

        $model->levels()->delete();

        foreach ($structure->levels() as $level) {
            CommitteeStructureLevelModel::create([
                'committee_structure_id' => $model->id,
                'level_index' => $level->index,
                'name' => $level->name,
                'geo_policy' => $level->geoPolicy->value,
                'geo_scope' => $level->geoScope?->code,
                'role_limits' => $level->roleLimits,
                'min_membership_years' => $level->minMembershipYears,
                'age_range_min' => $this->getAgeRangeMin($level->ageRange),
                'age_range_max' => $this->getAgeRangeMax($level->ageRange),
                'gender_requirement' => $level->genderRequirement,
            ]);
        }
    }

    private function mapToDomain(CommitteeStructureModel $model): CommitteeStructure
    {
        $levels = $model->levels
            ->map(fn (CommitteeStructureLevelModel $levelModel) => $this->mapLevelToDomain($levelModel))
            ->toArray();

        return CommitteeStructure::reconstruct(
            id: CommitteeStructureId::fromString((string)$model->id),
            tenantId: TenantId::fromString((string)$model->organisation_id),
            name: $model->name,
            version: $model->version,
            status: StructureStatus::from($model->status),
            levels: $levels
        );
    }

    private function mapLevelToDomain(CommitteeStructureLevelModel $model): CommitteeLevel
    {
        return CommitteeLevel::create(
            index: $model->level_index,
            code: $model->code ?? null,
            name: $model->name,
            geoPolicy: GeoPolicy::from($model->geo_policy),
            geoScope: $model->geo_scope ? new GeoScope($model->geo_scope) : null,
            roleLimits: $model->role_limits ?? [],
            minMembershipYears: $model->min_membership_years,
            ageRange: $this->buildAgeRange($model->age_range_min, $model->age_range_max),
            genderRequirement: $model->gender_requirement
        );
    }

    private function buildAgeRange(?int $min, ?int $max): ?array
    {
        return ($min !== null || $max !== null) ? [$min, $max] : null;
    }

    private function getAgeRangeMin(?array $ageRange): ?int
    {
        return $ageRange ? $ageRange[0] : null;
    }

    private function getAgeRangeMax(?array $ageRange): ?int
    {
        return $ageRange ? $ageRange[1] : null;
    }
}
