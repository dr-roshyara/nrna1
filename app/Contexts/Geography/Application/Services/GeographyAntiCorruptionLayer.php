<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Application\Services;

use App\Contexts\Geography\Domain\Services\GeographyPathService;
use App\Contexts\Geography\Domain\Repositories\GeoUnitRepositoryInterface;
use App\Contexts\Geography\Domain\ValueObjects\CountryCode;
use App\Contexts\Geography\Domain\ValueObjects\GeographyHierarchy;
use App\Contexts\Geography\Domain\ValueObjects\GeoPath;
use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;
use App\Contexts\Geography\Domain\Exceptions\InvalidHierarchyException;
use App\Contexts\Geography\Domain\Exceptions\InvalidParentChildException;
use App\Contexts\Geography\Domain\Exceptions\MissingRequiredLevelException;
use App\Contexts\Geography\Domain\Exceptions\MaxHierarchyDepthException;
use App\Contexts\Geography\Domain\Exceptions\CountryNotSupportedException;
use App\Contexts\Geography\Domain\Models\GeoAdministrativeUnit as GeoAdministrativeUnitModel;
use App\Contexts\Geography\Domain\Entities\GeoAdministrativeUnit as GeoAdministrativeUnitEntity;
use InvalidArgumentException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

/**
 * GeographyAntiCorruptionLayer
 *
 * Anti-corruption layer bridging old GeographyService and new DDD system.
 *
 * Responsibilities:
 * 1. Convert primitive inputs (strings, arrays) to DDD Value Objects
 * 2. Use DDD services (GeographyPathService, repositories) for business logic
 * 3. Convert DDD results back to legacy format (Eloquent models, arrays)
 * 4. Translate DDD exceptions to legacy error formats
 * 5. Gradually migrate functionality from old to new DDD system
 *
 * Migration Strategy:
 * - Phase 1: Validation methods use DDD (validateGeographyHierarchy)
 * - Phase 2: Query methods use DDD repositories (getUnitById, getUnitsAtLevel)
 * - Phase 3: Full migration with caching and performance optimization
 */
class GeographyAntiCorruptionLayer extends GeographyService
{
    /**
     * Cache TTL (Time To Live) in seconds
     */
    private const CACHE_TTL = 86400; // 24 hours

    /**
     * Cache key prefix
     */
    private const CACHE_PREFIX = 'geography';

    private GeographyPathService $pathService;
    private GeoUnitRepositoryInterface $repository;
    private GeographyService $legacyService;

    public function __construct(
        GeographyPathService $pathService,
        GeoUnitRepositoryInterface $repository,
        GeographyService $legacyService
    ) {
        $this->pathService = $pathService;
        $this->repository = $repository;
        $this->legacyService = $legacyService;
    }

    /**
     * Get the complete administrative hierarchy for a country.
     *
     * @param string $countryCode ISO 3166-1 alpha-2 code (NP, IN, US)
     * @return array Hierarchical structure with all levels
     */
    public function getCountryHierarchy(string $countryCode): array
    {
        // TODO: Phase 2 - Implement using DDD repository
        return $this->legacyService->getCountryHierarchy($countryCode);
    }

    /**
     * Get administrative units at a specific level for a country.
     *
     * @param string $countryCode
     * @param int $adminLevel
     * @param int|null $parentId Filter by parent (optional)
     * @return Collection
     */
    public function getUnitsAtLevel(
        string $countryCode,
        int $adminLevel,
        ?int $parentId = null
    ): Collection {
        // TODO: Phase 2 - Implement using DDD repository
        return $this->legacyService->getUnitsAtLevel($countryCode, $adminLevel, $parentId);
    }

    /**
     * Get a single administrative unit by ID.
     *
     * @param int $unitId
     * @return GeoAdministrativeUnitModel|null
     */
    public function getUnitById(int $unitId): ?GeoAdministrativeUnitModel
    {
        // TODO: Phase 2 - Implement using DDD repository
        // Option 1: Convert DDD Entity to old Model
        // $entity = $this->repository->findById(GeoUnitId::fromInt($unitId));
        // return $entity ? $this->convertEntityToModel($entity) : null;

        // Option 2: Delegate to legacy service (temporary)
        return $this->legacyService->getUnitById($unitId);
    }

    /**
     * Get a single administrative unit by code.
     *
     * @param string $code
     * @return GeoAdministrativeUnitModel|null
     */
    public function getUnitByCode(string $code): ?GeoAdministrativeUnitModel
    {
        // TODO: Phase 2 - Implement using DDD repository
        return $this->legacyService->getUnitByCode($code);
    }

    /**
     * Get child units of a parent unit.
     *
     * @param int $parentId
     * @return Collection
     */
    public function getChildUnits(int $parentId): Collection
    {
        // TODO: Phase 2 - Implement using DDD repository
        return $this->legacyService->getChildUnits($parentId);
    }

    /**
     * Get all ancestors of a unit.
     *
     * @param int $unitId
     * @return Collection
     */
    public function getAncestors(int $unitId): Collection
    {
        // TODO: Phase 2 - Implement using DDD repository
        return $this->legacyService->getAncestors($unitId);
    }

    /**
     * Get all descendants of a unit.
     *
     * @param int $unitId
     * @return Collection
     */
    public function getDescendants(int $unitId): Collection
    {
        // TODO: Phase 2 - Implement using DDD repository
        return $this->legacyService->getDescendants($unitId);
    }

    /**
     * Validate geographic hierarchy using DDD Value Objects and services.
     *
     * Converts primitive inputs to DDD Value Objects, validates using
     * GeographyPathService, and returns legacy boolean result.
     *
     * @param string $countryCode
     * @param array $unitIds [level1_id, level2_id, level3_id, level4_id]
     * @return bool
     */
    public function validateGeographyHierarchy(string $countryCode, array $unitIds): bool
    {
        try {
            // 1. Convert primitive inputs to DDD Value Objects
            $countryCodeVO = CountryCode::fromString($countryCode);

            // Pad to 8 levels for GeographyHierarchy (null for missing)
            // Note: Old service expected up to 4 levels, new system supports 8
            $paddedUnitIds = array_pad($unitIds, 8, null);

            // 2. Check if hierarchy is empty (all nulls) - old service returns true for empty
            // Need to check before creating GeographyHierarchy since empty hierarchy
            // fails required levels validation in GeographyHierarchy constructor
            if (!array_filter($paddedUnitIds, fn($id) => $id !== null)) {
                return true;
            }

            // 3. Create GeographyHierarchy - validates gaps and required levels
            $hierarchy = GeographyHierarchy::fromLevelIds($countryCodeVO, $paddedUnitIds);

            // 4. Use DDD service to validate parent-child relationships and unit existence
            // This will throw exceptions if invalid
            $this->pathService->generatePath($hierarchy);

            return true;

        } catch (InvalidArgumentException $e) {
            // GeographyHierarchy validation failed (gaps, required levels, invalid inputs)
            return false;
        } catch (InvalidHierarchyException $e) {
            // GeographyPathService validation failed (unit not found, wrong country)
            return false;
        } catch (InvalidParentChildException $e) {
            // Invalid parent-child relationship
            return false;
        } catch (MissingRequiredLevelException $e) {
            // Missing required level for country
            return false;
        } catch (MaxHierarchyDepthException $e) {
            // Exceeds maximum hierarchy depth
            return false;
        } catch (CountryNotSupportedException $e) {
            // Country not supported in system
            return false;
        }
    }

    /**
     * Generate GeoPath from country code and geography IDs.
     *
     * Converts primitive inputs to DDD Value Objects, validates using
     * GeographyPathService, and returns GeoPath Value Object.
     * Throws geography domain exceptions for validation failures.
     *
     * @param CountryCode $countryCode
     * @param array $geographyIds Array of geographic unit IDs
     * @return GeoPath Validated materialized path
     * @throws InvalidHierarchyException
     * @throws CountryNotSupportedException
     * @throws MissingRequiredLevelException
     * @throws MaxHierarchyDepthException
     * @throws InvalidParentChildException
     * @throws InvalidArgumentException
     */
    public function generatePath(CountryCode $countryCode, array $geographyIds): GeoPath
    {
        // Pad to 8 levels for GeographyHierarchy (null for missing)
        $paddedUnitIds = array_pad($geographyIds, 8, null);

        // Check if hierarchy is empty (all nulls)
        if (!array_filter($paddedUnitIds, fn($id) => $id !== null)) {
            throw new InvalidArgumentException('Empty geography hierarchy');
        }

        // Create GeographyHierarchy - validates gaps and required levels
        $hierarchy = GeographyHierarchy::fromLevelIds($countryCode, $paddedUnitIds);

        // Use DDD service to generate path - throws geography domain exceptions
        return $this->pathService->generatePath($hierarchy);
    }

    /**
     * Validate specific hierarchy (level by level) with detailed error messages.
     *
     * @param string $countryCode
     * @param int|null $level1Id
     * @param int|null $level2Id
     * @param int|null $level3Id
     * @param int|null $level4Id
     * @return array Returns ['valid' => bool, 'errors' => array]
     */
    public function validateHierarchyDetailed(
        string $countryCode,
        ?int $level1Id = null,
        ?int $level2Id = null,
        ?int $level3Id = null,
        ?int $level4Id = null
    ): array {
        // TODO: Phase 1 - Implement using DDD with detailed error messages
        return $this->legacyService->validateHierarchyDetailed(
            $countryCode,
            $level1Id,
            $level2Id,
            $level3Id,
            $level4Id
        );
    }

    /**
     * Clear geography cache for a specific country.
     *
     * @param string|null $countryCode If null, clears all geography cache
     * @return void
     */
    public function clearCache(?string $countryCode = null): void
    {
        // TODO: Phase 3 - Implement using DDD caching strategy
        $this->legacyService->clearCache($countryCode);
    }

    /**
     * Get formatted geography for display.
     *
     * @param int $unitId
     * @param string $language
     * @return array
     */
    public function getUnitWithAncestors(int $unitId, string $language = 'en'): array
    {
        // TODO: Phase 2 - Implement using DDD repository
        return $this->legacyService->getUnitWithAncestors($unitId, $language);
    }

    /**
     * Get cache key with prefix.
     *
     * @param string $key
     * @return string
     */
    private function getCacheKey(string $key): string
    {
        return self::CACHE_PREFIX . ':' . $key;
    }

    /**
     * Convert DDD Entity to legacy model format (for backward compatibility).
     *
     * @param GeoAdministrativeUnitEntity $entity
     * @return GeoAdministrativeUnitModel
     */
    private function convertEntityToModel(GeoAdministrativeUnitEntity $entity): GeoAdministrativeUnitModel
    {
        $model = new GeoAdministrativeUnitModel();

        // Map DDD Entity properties to Eloquent Model
        $model->id = $entity->getId()->toInt();
        $model->country_code = $entity->getCountryCode()->toString();
        $model->admin_level = $entity->getLevel()->toInt();

        // Map parent ID
        $parentId = $entity->getParentId();
        $model->parent_id = $parentId ? $parentId->toInt() : null;

        // Map path
        $model->path = $entity->getPath()->toString();

        // Map name - need to convert LocalizedName Value Object to JSON array
        // For now, use English name as primary
        $model->name_local = ['en' => $entity->getName()->getEnglish()];

        // Map official code if available
        $officialCode = $entity->getOfficialCode();
        if ($officialCode) {
            $model->code = $officialCode->toString();
            $model->local_code = $officialCode->toString();
        }

        // Default values for required model fields
        $model->admin_type = $this->determineAdminType($entity->getLevel()->toInt(), $entity->getCountryCode()->toString());
        $model->is_active = true;

        return $model;
    }

    /**
     * Convert legacy model to DDD Entity (for DDD processing).
     *
     * @param GeoAdministrativeUnitModel $model
     * @return GeoAdministrativeUnitEntity
     */
    private function convertModelToEntity(GeoAdministrativeUnitModel $model): GeoAdministrativeUnitEntity
    {
        // Build Value Objects from model data
        $id = GeoUnitId::fromInt($model->id);
        $countryCode = CountryCode::fromString($model->country_code);
        $level = \App\Contexts\Geography\Domain\ValueObjects\GeographyLevel::fromInt($model->admin_level);

        $parentId = $model->parent_id ? GeoUnitId::fromInt($model->parent_id) : null;
        $path = \App\Contexts\Geography\Domain\ValueObjects\GeoPath::fromString($model->path ?? '');

        // Create LocalizedName from name_local JSON
        $nameLocal = $model->name_local ?? ['en' => ''];
        $name = \App\Contexts\Geography\Domain\ValueObjects\LocalizedName::english($nameLocal['en'] ?? '');

        // Create official code if available
        $officialCode = null;
        if ($model->code) {
            $officialCode = \App\Contexts\Geography\Domain\ValueObjects\GeographicCode::fromString($model->code);
        }

        return new GeoAdministrativeUnitEntity(
            $id,
            $countryCode,
            $level,
            $parentId,
            $path,
            $name,
            $officialCode
        );
    }

    /**
     * Determine admin_type based on level and country.
     *
     * @param int $level
     * @param string $countryCode
     * @return string
     */
    private function determineAdminType(int $level, string $countryCode): string
    {
        // Simplified mapping - should come from country configuration
        $mappings = [
            'NP' => [
                1 => 'province',
                2 => 'district',
                3 => 'local_level',
                4 => 'ward',
                5 => 'neighborhood',
                6 => 'street',
                7 => 'house_number',
                8 => 'household',
            ],
            'IN' => [
                1 => 'state',
                2 => 'district',
                3 => 'tehsil',
                4 => 'village',
            ],
            'US' => [
                1 => 'state',
                2 => 'county',
                3 => 'city',
                4 => 'zip_code',
            ],
        ];

        return $mappings[$countryCode][$level] ?? 'unknown';
    }
}