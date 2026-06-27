<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Services;

/**
 * Geography Lookup Service Interface
 *
 * OPTIONAL GEOGRAPHY ARCHITECTURE - Anti-Corruption Layer
 *
 * This interface defines the contract for validating geography IDs used in member data.
 * It provides an abstraction layer between Membership and Geography contexts, enabling
 * loose coupling and independent module installation.
 *
 * Design Pattern: Dependency Inversion Principle (DIP)
 * - Membership Domain depends on this INTERFACE (abstraction)
 * - Geography Infrastructure IMPLEMENTS this interface (concrete)
 * - Enables: Membership can work WITHOUT Geography being installed
 *
 * Key Features:
 * - Application-level validation (replaces database FK constraints)
 * - Graceful handling when Geography module not installed
 * - Supports partial geography data (some levels filled, some null)
 * - Validates geography hierarchy integrity when provided
 *
 * When to Use:
 * - Before creating/updating member with geography IDs
 * - When importing member data from CSV/Excel
 * - When tenant adds custom geography units (levels 6-8)
 *
 * Implementation Notes:
 * - Returns false (not throwing) when Geography module not installed
 * - Checks tenant-specific geography data (tenant.geo_administrative_units)
 * - Validates parent-child relationships when hierarchy provided
 * - Caches validation results for performance
 *
 * @package App\Contexts\Membership\Domain\Services
 */
interface GeographyLookupInterface
{
    /**
     * Check if Geography module is installed for current tenant.
     *
     * Used to determine whether geography validation should be enforced.
     * If not installed, geography fields remain null and validation is skipped.
     *
     * @return bool True if geo_administrative_units table exists in tenant DB
     */
    public function isGeographyModuleInstalled(): bool;

    /**
     * Validate that a single geography ID exists in tenant's geography data.
     *
     * Checks if the provided ID exists in tenant.geo_administrative_units table.
     * Used for validating individual level IDs before saving member data.
     *
     * @param int $geographyId The geography unit ID to validate
     * @return bool True if ID exists in tenant geography, false otherwise
     */
    public function validateGeographyIdExists(int $geographyId): bool;

    /**
     * Validate multiple geography IDs exist in tenant's geography data.
     *
     * Batch validation for performance when checking multiple IDs at once.
     * Returns array with validation results per ID.
     *
     * @param array<int> $geographyIds Array of geography unit IDs to validate
     * @return array<int, bool> Map of [geography_id => exists] results
     *
     * Example:
     *   Input: [1, 12, 999]
     *   Output: [1 => true, 12 => true, 999 => false]
     */
    public function validateGeographyIdsExist(array $geographyIds): array;

    /**
     * Validate geography hierarchy integrity (parent-child relationships).
     *
     * When a member has multiple geography levels filled, validates that:
     * - Each level references the correct admin_level in geography table
     * - Parent-child relationships are correct (e.g., district belongs to province)
     * - No gaps in hierarchy (if level 3 filled, levels 1-2 must also be filled)
     *
     * @param array $hierarchyData Geography levels data
     *   Format: [
     *     'level1_id' => 1,   // Province
     *     'level2_id' => 12,  // District
     *     'level3_id' => 123, // Local Level
     *     // ... etc
     *   ]
     * @return array Validation result
     *   Format: [
     *     'valid' => bool,
     *     'errors' => array<string>, // Empty if valid
     *   ]
     *
     * Example Success:
     *   ['valid' => true, 'errors' => []]
     *
     * Example Failure:
     *   [
     *     'valid' => false,
     *     'errors' => [
     *       'level2_id: District 12 does not belong to Province 1',
     *       'level3_id: Hierarchy gap detected - level 3 requires levels 1-2',
     *     ]
     *   ]
     */
    public function validateGeographyHierarchy(array $hierarchyData): array;

    /**
     * Get geography unit details for display purposes.
     *
     * Retrieves geography unit information (name, code, level) for showing
     * member's location in UI or reports.
     *
     * @param int $geographyId The geography unit ID
     * @return array|null Geography unit data or null if not found
     *   Format: [
     *     'id' => int,
     *     'name' => string (localized),
     *     'code' => string,
     *     'admin_level' => int,
     *     'parent_id' => int|null,
     *   ]
     */
    public function getGeographyUnit(int $geographyId): ?array;

    /**
     * Get complete geography hierarchy path for a unit.
     *
     * Returns full path from country to specific unit (e.g., Nepal > Koshi > Dhankuta > Ward 1).
     * Used for displaying member's full address or location breadcrumbs.
     *
     * @param int $geographyId The geography unit ID
     * @return array Hierarchy path from root to unit
     *   Format: [
     *     ['id' => 1, 'name' => 'Koshi Province', 'level' => 1],
     *     ['id' => 12, 'name' => 'Dhankuta', 'level' => 2],
     *     ['id' => 123, 'name' => 'Dhankuta Municipality', 'level' => 3],
     *     ['id' => 1234, 'name' => 'Ward 1', 'level' => 4],
     *   ]
     *
     * Returns empty array if:
     * - Geography module not installed
     * - Geography ID not found
     * - Hierarchy data corrupted
     */
    public function getGeographyHierarchyPath(int $geographyId): array;
}
