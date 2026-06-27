<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Query;

use App\Contexts\Membership\Application\Membership\Query\Ports\CommitteeGeoPathProviderPort;
use App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPathChain;

/**
 * Safe-default stub for F1 integration.
 * Returns empty path — geographic committees are ineligible until Phase F2.
 * Replace with GeoSemanticProjectionBuilder-based impl in Phase F2.
 */
final class StubCommitteeGeoPathProvider implements CommitteeGeoPathProviderPort
{
    public function resolveForCommittee(int $geoUnitId): GeoPathChain
    {
        // F1 stub: return empty path (geographic committees will be ineligible)
        // Real implementation uses GeoSemanticProjectionBuilder in F2
        return new GeoPathChain(1, '/', [1]);
    }
}
