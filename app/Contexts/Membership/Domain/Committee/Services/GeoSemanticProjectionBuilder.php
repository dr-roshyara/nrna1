<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Services;

use App\Contexts\Membership\Domain\Committee\Ports\GeographicJurisdictionProvider;
use App\Contexts\Membership\Domain\Committee\Projections\GeoSemanticProjection;

/**
 * Builds GeoSemanticProjection from the GeographicJurisdictionProvider.
 *
 * This is the ONLY service that bridges the ACL (port/adapter) to the
 * semantic projection layer. It depends on the provider interface, not
 * on any concrete implementation.
 */
final class GeoSemanticProjectionBuilder
{
    public function __construct(
        private readonly GeographicJurisdictionProvider $provider,
    ) {}

    public function build(int $geoUnitId): ?GeoSemanticProjection
    {
        $jurisdiction = $this->provider->resolve($geoUnitId);

        if ($jurisdiction === null) {
            return null;
        }

        return new GeoSemanticProjection(
            geoUnitId: $jurisdiction->geoUnitId,
            adminLevel: $jurisdiction->adminLevel,
            regionCode: $jurisdiction->regionCode,
            countryCode: $jurisdiction->countryCode,
            path: $jurisdiction->path,
        );
    }
}
