<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Ports;

final readonly class GeographicJurisdiction
{
    public function __construct(
        public int $geoUnitId,
        public int $adminLevel,
        public string $regionCode,
        public string $countryCode,
        public string $path = '',
    ) {}
}
