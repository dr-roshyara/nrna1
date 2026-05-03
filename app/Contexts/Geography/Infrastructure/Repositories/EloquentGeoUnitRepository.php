<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Infrastructure\Repositories;

use App\Contexts\Geography\Domain\Repositories\GeoUnitRepositoryInterface;
use App\Contexts\Geography\Domain\Entities\GeoAdministrativeUnit;
use App\Contexts\Geography\Domain\ValueObjects\CountryCode;
use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;
use App\Contexts\Geography\Domain\Models\GeoAdministrativeUnit as GeoAdministrativeUnitModel;
use App\Contexts\Geography\Domain\ValueObjects\GeographyLevel;
use App\Contexts\Geography\Domain\ValueObjects\GeoPath;
use App\Contexts\Geography\Domain\ValueObjects\LocalizedName;
use App\Contexts\Geography\Domain\ValueObjects\GeographicCode;
use Illuminate\Support\Facades\DB;

/**
 * EloquentGeoUnitRepository
 *
 * Repository implementation using Eloquent models.
 * Converts between Eloquent models and DDD Entities.
 * Implements GeoUnitRepositoryInterface for geography bounded context.
 */
class EloquentGeoUnitRepository implements GeoUnitRepositoryInterface
{
    /**
     * Find a geographic unit by its ID
     */
    public function findById(GeoUnitId $unitId): ?GeoAdministrativeUnit
    {
        $model = GeoAdministrativeUnitModel::find($unitId->toInt());

        if (!$model) {
            return null;
        }

        return $this->convertModelToEntity($model);
    }

    /**
     * Check if child unit is a descendant of parent unit
     * Validates parent-child relationship in hierarchy
     */
    public function isChildOf(GeoUnitId $childId, GeoUnitId $parentId): bool
    {
        // Get child model
        $childModel = GeoAdministrativeUnitModel::find($childId->toInt());
        if (!$childModel) {
            return false;
        }

        // Get parent model
        $parentModel = GeoAdministrativeUnitModel::find($parentId->toInt());
        if (!$parentModel) {
            return false;
        }

        // Check if child's path is descendant of parent's path
        // Using materialized path pattern: parent.path must be prefix of child.path
        $parentPath = $parentModel->path ?? '';
        $childPath = $childModel->path ?? '';

        if (empty($parentPath) || empty($childPath)) {
            // Fallback to parent_id check if paths not available
            return $childModel->parent_id === $parentModel->id;
        }

        // Check if child path starts with parent path + '/'
        return str_starts_with($childPath, $parentPath . '/') || $childPath === $parentPath;
    }

    /**
     * Get country-specific configuration including:
     * - Supported hierarchy levels
     * - Required levels
     * - Level names and codes
     */
    public function getCountryConfig(CountryCode $countryCode): array
    {
        // TODO: Load from Country model or configuration
        // For now, return static configuration matching CountryCode::getHierarchyLevels()
        $configurations = [
            'NP' => [
                1 => ['name' => 'Province', 'required' => true],
                2 => ['name' => 'District', 'required' => true],
                3 => ['name' => 'Local Level', 'required' => false],
                4 => ['name' => 'Ward', 'required' => false],
                5 => ['name' => 'Neighborhood/Tole', 'required' => false],
                6 => ['name' => 'Street/Block', 'required' => false],
                7 => ['name' => 'House Number', 'required' => false],
                8 => ['name' => 'Household', 'required' => false],
            ],
            'IN' => [
                1 => ['name' => 'State', 'required' => true],
                2 => ['name' => 'District', 'required' => true],
                3 => ['name' => 'Tehsil/Taluk', 'required' => false],
                4 => ['name' => 'Village/Town', 'required' => false],
            ],
            'US' => [
                1 => ['name' => 'State', 'required' => true],
                2 => ['name' => 'County', 'required' => true],
                3 => ['name' => 'City', 'required' => false],
                4 => ['name' => 'ZIP Code', 'required' => false],
            ],
        ];

        return $configurations[$countryCode->toString()] ?? [
            1 => ['name' => 'Level 1', 'required' => true],
            2 => ['name' => 'Level 2', 'required' => true],
        ];
    }

    /**
     * Validate that unit IDs form a valid hierarchy chain
     * Checks that each unit is child of previous level
     *
     * @param CountryCode $countryCode ISO country code
     * @param array<int> $unitIds Array of unit IDs in hierarchy order
     * @return bool True if valid hierarchy, false otherwise
     */
    public function validateHierarchy(CountryCode $countryCode, array $unitIds): bool
    {
        // Filter out null/empty values
        $unitIds = array_filter($unitIds);
        if (empty($unitIds)) {
            return true; // Empty hierarchy is valid
        }

        // Fetch all units at once
        $models = GeoAdministrativeUnitModel::whereIn('id', $unitIds)
            ->get()
            ->keyBy('id');

        // Verify all units exist and belong to the correct country
        if ($models->count() !== count($unitIds)) {
            return false; // Some units don't exist
        }

        if (!$models->every(fn($model) => $model->country_code === $countryCode->toString())) {
            return false; // Units from different country
        }

        // Sort by admin level
        $sortedModels = $models->sortBy('admin_level')->values();

        // Validate parent-child relationships
        for ($i = 1; $i < $sortedModels->count(); $i++) {
            $child = $sortedModels[$i];
            $parent = $sortedModels[$i - 1];

            if ($child->parent_id !== $parent->id) {
                return false; // Invalid hierarchy
            }
        }

        return true;
    }

    /**
     * Get unit's immediate parent (if any)
     */
    public function getParentId(GeoUnitId $unitId): ?GeoUnitId
    {
        $model = GeoAdministrativeUnitModel::find($unitId->toInt());

        if (!$model || !$model->parent_id) {
            return null;
        }

        return GeoUnitId::fromInt($model->parent_id);
    }

    /**
     * Get all units at a specific level for a country
     * Useful for validation and dropdowns
     *
     * @return array<GeoAdministrativeUnit>
     */
    public function getUnitsByLevel(CountryCode $countryCode, int $level): array
    {
        $models = GeoAdministrativeUnitModel::where('country_code', $countryCode->toString())
            ->where('admin_level', $level)
            ->where('is_active', true)
            ->orderBy('name_local->en')
            ->get();

        return $models->map(function ($model) {
            return $this->convertModelToEntity($model);
        })->toArray();
    }

    /**
     * Batch find multiple units by IDs
     * Performance optimization for path generation
     *
     * @param array<GeoUnitId> $unitIds
     * @return array<GeoAdministrativeUnit>
     */
    public function findMany(array $unitIds): array
    {
        $ids = array_map(fn(GeoUnitId $id) => $id->toInt(), $unitIds);

        $models = GeoAdministrativeUnitModel::whereIn('id', $ids)
            ->get();

        return $models->map(function ($model) {
            return $this->convertModelToEntity($model);
        })->toArray();
    }

    /**
     * Check if unit exists and is active
     */
    public function unitExists(GeoUnitId $unitId): bool
    {
        return GeoAdministrativeUnitModel::where('id', $unitId->toInt())
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Get unit's country code
     */
    public function getUnitCountryCode(GeoUnitId $unitId): ?CountryCode
    {
        $model = GeoAdministrativeUnitModel::find($unitId->toInt());

        if (!$model) {
            return null;
        }

        return CountryCode::fromString($model->country_code);
    }

    /**
     * Get unit's administrative level
     */
    public function getUnitLevel(GeoUnitId $unitId): ?int
    {
        $model = GeoAdministrativeUnitModel::find($unitId->toInt());

        if (!$model) {
            return null;
        }

        return $model->admin_level;
    }

    /**
     * Convert Eloquent model to DDD Entity
     */
    private function convertModelToEntity(GeoAdministrativeUnitModel $model): GeoAdministrativeUnit
    {
        // Build Value Objects from model data
        $id = GeoUnitId::fromInt($model->id);
        $countryCode = CountryCode::fromString($model->country_code);
        $level = GeographyLevel::fromInt($model->admin_level);

        $parentId = $model->parent_id ? GeoUnitId::fromInt($model->parent_id) : null;
        $path = GeoPath::fromString($model->path ?? '');

        // Create LocalizedName from name_local JSON
        $nameLocal = $model->name_local ?? ['en' => ''];
        $name = LocalizedName::english($nameLocal['en'] ?? '');

        // Create official code if available
        $officialCode = null;
        if ($model->code) {
            $officialCode = GeographicCode::fromString($model->code);
        }

        return new GeoAdministrativeUnit(
            $id,
            $countryCode,
            $level,
            $parentId,
            $path,
            $name,
            $officialCode
        );
    }
}