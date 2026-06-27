<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Services;

use App\Contexts\Membership\Domain\ValueObjects\GeoReference;

/**
 * Geography Resolver Interface
 *
 * DOMAIN SERVICE - Anti-Corruption Layer
 *
 * This interface decouples the Membership context from the Geography context.
 * Geography references in Membership are simple strings (e.g., "np.3.15.234.1.2"),
 * but we need to validate them against the Geography context without creating
 * direct dependencies.
 *
 * Architecture Pattern: Anti-Corruption Layer (ACL)
 * - Membership context depends on this interface (domain layer)
 * - Geography adapter implements this interface (infrastructure layer)
 * - Prevents tight coupling to Geography context implementation
 *
 * Business Rules:
 * - Geography reference is OPTIONAL for member registration
 * - Invalid references should not block registration (log warning only)
 * - Validation is defensive (network issues shouldn't fail registration)
 *
 * Design Decision (ADR):
 * Membership stores geography as string reference, not foreign key.
 * This allows geography data to change without impacting member records.
 */
interface GeographyResolverInterface
{
    /**
     * Validate geography reference
     *
     * Calls the Geography context to validate that the reference string
     * represents a valid geographic unit in the hierarchy.
     *
     * Business Rules:
     * - Null references are valid (geography is optional)
     * - Invalid references return null + log warning (defensive)
     * - Network/service failures return null (don't block registration)
     *
     * Format Examples:
     * - "np.3.15.234.1.2" → Nepal → Province 3 → District 15 → Municipality 234 → Ward 1 → Tole 2
     * - "us.ca.la" → USA → California → Los Angeles
     *
     * @param string|null $geoReference Geography reference string
     * @return GeoReference|null Validated reference or null if invalid/unavailable
     */
    public function validate(?string $geoReference): ?GeoReference;

    /**
     * Resolve geography reference to human-readable name
     *
     * Optional: Enriches the string reference with display name
     * for UI purposes (e.g., "np.3.15.234" → "Kathmandu, Nepal")
     *
     * @param string $geoReference Geography reference string
     * @return string|null Human-readable name or null if unavailable
     */
    public function resolveName(string $geoReference): ?string;
}
