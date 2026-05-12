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
use App\Contexts\Geography\Infrastructure\Exceptions\ConcurrencyException;
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
     * Persist a geographic unit with optimistic locking.
     *
     * For new units: INSERT with version=1.
     * For existing units: UPDATE with WHERE version = expected, increment version.
     * Throws ConcurrencyException if the version doesn't match (record was modified).
     */
    public function save(GeoAdministrativeUnit $unit): void
    {
        $model = GeoAdministrativeUnitModel::find($unit->getId()->toInt());

        if ($model) {
            // UPDATE with optimistic locking
            $expectedVersion = $unit->getVersion();
            $attributes = $this->entityToAttributes($unit);

            // Increment version in the same UPDATE
            $attributes['version'] = $expectedVersion + 1;

            $affected = GeoAdministrativeUnitModel::where('id', $unit->getId()->toInt())
                ->where('version', $expectedVersion)
                ->update($attributes);

            if ($affected === 0) {
                // Reload to get actual version for error message
                $current = GeoAdministrativeUnitModel::find($unit->getId()->toInt());
                $actualVersion = $current ? $current->version : 0;

                throw ConcurrencyException::versionMismatch(
                    $unit->getId()->toInt(),
                    $expectedVersion,
                    $actualVersion
                );
            }

            // Sync entity version to match DB state
            $unit->incrementVersion();
        } else {
            // INSERT: repository controls identity + path explicitly (bypasses mass-assignment guard)
            $attributes = $this->entityToAttributes($unit);
            $attributes['version'] = 1;
            $attributes['is_active'] = $attributes['is_active'] ?? true;

            $model = new GeoAdministrativeUnitModel();
            $model->forceFill($attributes);
            $model->save();
        }
    }

    /**
     * Delete a geographic unit by ID.
     */
    public function delete(GeoUnitId $unitId): void
    {
        GeoAdministrativeUnitModel::where('id', $unitId->toInt())->delete();
    }

    /**
     * Find a geographic unit by country code and local code.
     */
    public function findByCode(CountryCode $countryCode, string $code): ?GeoAdministrativeUnit
    {
        $model = GeoAdministrativeUnitModel::where('country_code', $countryCode->toString())
            ->where('code', $code)
            ->first();

        if (!$model) {
            return null;
        }

        return $this->convertModelToEntity($model);
    }

    /**
     * Convert domain entity attributes to model-compatible array.
     * Does NOT include version — handled by save() for optimistic locking.
     */
    private function entityToAttributes(GeoAdministrativeUnit $unit): array
    {
        // Convert entity path (dot-notation: "100" or "100.501") to model format (slash-notation: "/100/" or "/100/501/")
        $rawPath = $unit->getPath()->toString();
        $pathStr = $rawPath === '' ? '/' : '/' . str_replace('.', '/', $rawPath) . '/';

        return [
            'id' => $unit->getId()->toInt(),
            'country_code' => $unit->getCountryCode()->toString(),
            'admin_level' => $unit->getLevel()->toInt(),
            'admin_type' => $this->levelToAdminType($unit->getLevel()),
            'parent_id' => $unit->getParentId()?->toInt(),
            'path' => $pathStr,
            'code' => $unit->getOfficialCode()?->toString(),
            'name_local' => $unit->getName()->toArray(),
            'is_active' => true,
            'valid_from' => $unit->getValidFrom(),
            'valid_to' => $unit->getValidTo(),
        ];
    }

    /**
     * Map GeographyLevel to admin_type string.
     */
    private function levelToAdminType(GeographyLevel $level): string
    {
        $types = [
            0 => 'continent',
            1 => 'country',
            2 => 'province',
            3 => 'district',
            4 => 'local_level',
            5 => 'ward',
        ];

        return $types[$level->toInt()] ?? 'custom_' . $level->toInt();
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

        // Path: handle both slash-format (/1/2/) and dot-format (1.2.3)
        $path = $this->parsePath($model->path ?? '');

        // Create LocalizedName from name_local JSON
        $nameLocal = $model->name_local ?? ['en' => ''];
        $name = LocalizedName::fromArray($nameLocal);

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
            $officialCode,
            $model->version ?? 1,
            $model->valid_from ? new \DateTimeImmutable($model->valid_from) : null,
            $model->valid_to ? new \DateTimeImmutable($model->valid_to) : null,
        );
    }

    /**
     * Parse a path string in either slash-format (/1/2/) or dot-format (1.2.3).
     */
    private function parsePath(string $pathStr): GeoPath
    {
        if (empty($pathStr)) {
            return GeoPath::empty();
        }

        // Normalize: replace slashes with dots for uniform parsing
        $normalized = str_replace('/', '.', trim($pathStr, '/'));
        if (empty($normalized)) {
            return GeoPath::empty();
        }

        return GeoPath::fromString($normalized);
    }
}