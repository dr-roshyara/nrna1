<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Services;

use App\Contexts\Membership\Domain\Services\GeographyResolverInterface;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;

/**
 * Geography Validation Adapter (Test/Stub Implementation)
 *
 * INFRASTRUCTURE LAYER - Implements GeographyResolverInterface
 *
 * Anti-Corruption Layer for Geography Context
 *
 * TEST IMPLEMENTATION - For now, performs basic validation.
 * Later, this will call the actual Geography context via:
 * - API call to Geography service
 * - Database query to geo_units table
 * - Cache lookup for validated references
 *
 * Business Rule: Geography reference is OPTIONAL for member registration.
 * If provided, it must be validated before member creation.
 *
 * This adapter ensures:
 * - Membership context doesn't know Geography's internal structure
 * - Geography context can evolve independently
 * - Changes in Geography don't break Membership
 */
final class GeographyValidationAdapter implements GeographyResolverInterface
{
    /**
     * Validate a geography reference string
     *
     * TEST IMPLEMENTATION:
     * - Performs basic format validation
     * - Returns GeoReference value object if valid
     * - Returns null if invalid
     *
     * FUTURE IMPLEMENTATION:
     * - Call Geography context API/Service
     * - Validate reference exists in geo_units hierarchy
     * - Check geography is active/published
     * - Cache validated references (Redis/Memcached)
     * - Return GeoReference or throw GeographyNotFoundException
     *
     * @param string|null $geoReference Geography reference (e.g., "np.3.15.234")
     * @return GeoReference|null Validated geography reference or null
     */
    public function validate(?string $geoReference): ?GeoReference
    {
        // If null, return null (geography is optional)
        if ($geoReference === null) {
            return null;
        }

        // TEST IMPLEMENTATION: Basic format validation
        // Pattern: country.level1.level2... (e.g., "np.3.15.234")
        $pattern = '/^[a-z]{2}(\.[a-z0-9\-_]+)+$/';

        if (!preg_match($pattern, strtolower($geoReference))) {
            // Invalid format - return null (or throw exception in production)
            \Log::warning('GeographyValidationAdapter: Invalid geography reference format', [
                'geo_reference' => $geoReference,
            ]);

            return null; // Invalid format
        }

        // TEST IMPLEMENTATION: Assume valid, return GeoReference
        // In production, this would query Geography context to verify existence

        \Log::debug('GeographyValidationAdapter: Validated geography reference', [
            'geo_reference' => $geoReference,
        ]);

        try {
            return GeoReference::fromString($geoReference);
        } catch (\InvalidArgumentException $e) {
            \Log::warning('GeographyValidationAdapter: Failed to create GeoReference', [
                'geo_reference' => $geoReference,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Resolve geography name from reference
     *
     * TEST IMPLEMENTATION:
     * - Returns null (not implemented yet)
     *
     * FUTURE IMPLEMENTATION:
     * - Call Geography context to get human-readable name
     * - Example: "np.3.15.234" → "Nepal, Province 3, Kathmandu, Ward 234"
     * - Use for display purposes only
     *
     * @param string $geoReference Geography reference
     * @return string|null Human-readable geography name or null
     */
    public function resolveName(string $geoReference): ?string
    {
        // TEST IMPLEMENTATION: Not implemented yet
        // In production, this would call Geography context API

        \Log::debug('GeographyValidationAdapter: Resolve name (not implemented)', [
            'geo_reference' => $geoReference,
        ]);

        return null; // Not implemented in test version
    }
}
