<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Ports;

interface GeographicJurisdictionProvider
{
    public function resolve(int $geoUnitId): ?GeographicJurisdiction;
}
