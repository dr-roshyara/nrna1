<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Services;

/**
 * CanonicalGeoIdentityData — Parsed result from CanonicalGeoSerializer::deserialize().
 *
 * This is a pure data transfer object, NOT a domain value object.
 * It exists only to provide type-safe deserialization output and prevent
 * array-driven data leakage across infrastructure boundaries.
 */
final readonly class CanonicalGeoIdentityData
{
    public function __construct(
        public int $geoUnitId,
        public ?string $regionCode = null,
        public ?string $countryCode = null,
    ) {}
}
