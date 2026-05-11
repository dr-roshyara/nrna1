<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Adapters;

use App\Contexts\Geography\Domain\ValueObjects\GeoReference as CanonicalGeoReference;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference as LegacyGeoReference;

/**
 * Adapter: Membership Context → Geography Context
 *
 * Translates the legacy GeoReference (Membership domain) to the canonical
 * GeoReference (Geography domain) for cross-context communication.
 *
 * The Membership context uses a simple string format (country.level1.level2...)
 * The Geography context uses a richer format that supports regions and composite selections.
 *
 * This adapter ensures the legacy format is correctly translated to the canonical format
 * without losing information.
 */
final class MembershipGeoReferenceAdapter
{
    /**
     * Translate legacy Membership GeoReference to canonical Geography GeoReference.
     *
     * @param LegacyGeoReference $legacy The legacy format (e.g., "np.3.15.234")
     * @return CanonicalGeoReference The canonical format (legacy style remains legacy=true)
     */
    public function toCanonical(LegacyGeoReference $legacy): CanonicalGeoReference
    {
        // The legacy format is already compatible with GeoReference::fromString()
        // which detects it as legacy format and parses it correctly
        return CanonicalGeoReference::fromString($legacy->value());
    }
}
