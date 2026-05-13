<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Services;

/**
 * CanonicalGeoSerializer — Pure string transformer for geo identity projection.
 *
 * ZERO dependencies. No DB calls, no provider resolution.
 * Deterministic: same input always produces same output.
 *
 * The canonical_geo_id string is a read-optimized projection field,
 * NOT an authoritative data source. Source of truth remains:
 * geo_unit_id, region_code, country_code columns.
 *
 * Format: "region:{region}.country:{country}.geo:{geoUnitId}"
 * Example: "region:asia.country:IN.geo:7"
 */
final readonly class CanonicalGeoSerializer
{
    /**
     * Serialize geo identity components into a canonical string.
     *
     * Returns null when geoUnitId is null (no geo anchor).
     * Omits region/country segments when those values are null.
     */
    public function serialize(?int $geoUnitId, ?string $regionCode = null, ?string $countryCode = null): ?string
    {
        if ($geoUnitId === null) {
            return null;
        }

        $parts = [];

        if ($regionCode !== null) {
            $parts[] = "region:{$regionCode}";
        }

        if ($countryCode !== null) {
            $parts[] = "country:{$countryCode}";
        }

        $parts[] = "geo:{$geoUnitId}";

        return implode('.', $parts);
    }

    /**
     * Deserialize a canonical string back into its components.
     *
     * Returns null for null/empty/invalid input (fail-safe).
     * Unknown keys are silently ignored for forward compatibility.
     *
     * NOTE: This does NOT resolve a CommitteeGeoIdentity — that requires
     * a GeographicJurisdictionProvider call for adminLevel, which is
     * the repository's responsibility during aggregate reconstruction.
     */
    public function deserialize(?string $value): ?CanonicalGeoIdentityData
    {
        if ($value === null || $value === '') {
            return null;
        }

        $region = null;
        $country = null;
        $geoUnitId = null;

        foreach (explode('.', $value) as $segment) {
            if (!str_contains($segment, ':')) {
                return null;
            }

            [$key, $val] = explode(':', $segment, 2);

            match ($key) {
                'region' => $region = $val,
                'country' => $country = $val,
                'geo' => $geoUnitId = (int) $val,
                default => null,
            };
        }

        if ($geoUnitId === null) {
            return null;
        }

        return new CanonicalGeoIdentityData(
            geoUnitId: $geoUnitId,
            regionCode: $region,
            countryCode: $country,
        );
    }
}
