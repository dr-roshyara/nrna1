<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Projections;

use DomainException;

/**
 * GeoSemanticProjection
 *
 * Enriched read model providing geographic context for classification.
 * NOT identity — identity is CommitteeGeoIdentity (geoUnitId only).
 * NOT classification — this projection is consumed BY classification.
 *
 * This is the explicit boundary layer between geo interpretation and
 * governance decisions. It can be cached, versioned, or refreshed
 * independently of the identity and classification layers.
 */
final readonly class GeoSemanticProjection
{
    public function __construct(
        public int $geoUnitId,
        public int $adminLevel,
        public string $regionCode,
        public string $countryCode,
        public string $path = '',
    ) {
        if ($geoUnitId < 1) {
            throw new DomainException('geoUnitId must be a positive integer');
        }
        if ($adminLevel < 0 || $adminLevel > 10) {
            throw new DomainException('adminLevel must be between 0 and 10');
        }
    }

    public function equals(self $other): bool
    {
        return $this->geoUnitId === $other->geoUnitId
            && $this->adminLevel === $other->adminLevel
            && $this->regionCode === $other->regionCode
            && $this->countryCode === $other->countryCode
            && $this->path === $other->path;
    }
}
