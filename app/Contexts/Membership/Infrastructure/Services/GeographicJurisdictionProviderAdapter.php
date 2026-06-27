<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Services;

use App\Contexts\Geography\Domain\Models\GeoAdministrativeUnit;
use App\Contexts\Membership\Domain\Committee\Ports\GeographicJurisdiction;
use App\Contexts\Membership\Domain\Committee\Ports\GeographicJurisdictionProvider;

final class GeographicJurisdictionProviderAdapter implements GeographicJurisdictionProvider
{
    public function __construct(
        private readonly GeoAdministrativeUnit $model,
    ) {}

    public function resolve(int $geoUnitId): ?GeographicJurisdiction
    {
        $unit = $this->model->find($geoUnitId);

        if ($unit === null) {
            return null;
        }

        return new GeographicJurisdiction(
            geoUnitId: (int) $unit->id,
            adminLevel: (int) $unit->admin_level,
            regionCode: (string) ($unit->region_code ?? ''),
            countryCode: (string) $unit->country_code,
            path: (string) ($unit->path ?? ''),
        );
    }
}
