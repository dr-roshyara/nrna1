<?php

namespace App\Contexts\Membership\Domain\Exceptions;

use Exception;

/**
 * Invalid Geography Exception
 *
 * Thrown when member registration has invalid geography hierarchy.
 * For example:
 * - Province and District don't belong to same hierarchy
 * - District is not a child of specified Province
 * - Invalid unit IDs that don't exist in landlord.geo_administrative_units
 */
class InvalidGeographyException extends Exception
{
    /**
     * Create exception for invalid hierarchy
     */
    public static function invalidHierarchy(string $countryCode, array $unitIds): self
    {
        $unitIdsStr = implode(', ', $unitIds);
        return new self(
            "Invalid geography hierarchy for country {$countryCode}. " .
            "Unit IDs [{$unitIdsStr}] do not form a valid hierarchical relationship."
        );
    }

    /**
     * Create exception for missing required levels
     */
    public static function missingRequiredLevels(): self
    {
        return new self(
            "Province (level 1) and District (level 2) are required for member registration."
        );
    }

    /**
     * Create exception for non-existent unit ID
     */
    public static function unitNotFound(int $unitId): self
    {
        return new self(
            "Administrative unit with ID {$unitId} does not exist in the geography system."
        );
    }
}
