<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\Repositories;

use App\Contexts\Geography\Domain\Entities\GeoAdministrativeUnit;
use App\Contexts\Geography\Domain\ValueObjects\CountryCode;
use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;

/**
 * GeoUnitRepository Interface
 *
 * Repository for geographic administrative units.
 * Returns Domain Entities for geography hierarchy validation.
 */
interface GeoUnitRepositoryInterface
{
    /**
     * Find a geographic unit by its ID
     */
    public function findById(GeoUnitId $unitId): ?GeoAdministrativeUnit;

    /**
     * Check if child unit is a descendant of parent unit
     * Validates parent-child relationship in hierarchy
     */
    public function isChildOf(GeoUnitId $childId, GeoUnitId $parentId): bool;

    /**
     * Get country-specific configuration including:
     * - Supported hierarchy levels
     * - Required levels
     * - Level names and codes
     */
    public function getCountryConfig(CountryCode $countryCode): array;

    /**
     * Validate that unit IDs form a valid hierarchy chain
     * Checks that each unit is child of previous level
     *
     * @param CountryCode $countryCode ISO country code
     * @param array<int> $unitIds Array of unit IDs in hierarchy order
     * @return bool True if valid hierarchy, false otherwise
     */
    public function validateHierarchy(CountryCode $countryCode, array $unitIds): bool;

    /**
     * Get unit's immediate parent (if any)
     */
    public function getParentId(GeoUnitId $unitId): ?GeoUnitId;

    /**
     * Get all units at a specific level for a country
     * Useful for validation and dropdowns
     *
     * @return array<GeoAdministrativeUnit>
     */
    public function getUnitsByLevel(CountryCode $countryCode, int $level): array;

    /**
     * Batch find multiple units by IDs
     * Performance optimization for path generation
     *
     * @param array<GeoUnitId> $unitIds
     * @return array<GeoAdministrativeUnit>
     */
    public function findMany(array $unitIds): array;

    /**
     * Check if unit exists and is active
     */
    public function unitExists(GeoUnitId $unitId): bool;

    /**
     * Get unit's country code
     */
    public function getUnitCountryCode(GeoUnitId $unitId): ?CountryCode;

    /**
     * Get unit's administrative level
     */
    public function getUnitLevel(GeoUnitId $unitId): ?int;
}