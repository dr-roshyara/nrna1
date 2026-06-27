<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Infrastructure\Services;

use App\Contexts\Geography\Domain\Enums\GeoLevelType;
use App\Contexts\Geography\Domain\Models\Country;
use App\Contexts\Geography\Domain\Services\GeoLevelMappingResolver;

final class DatabaseGeoLevelMappingResolver implements GeoLevelMappingResolver
{
    public function resolve(string $countryCode, GeoLevelType $type): ?int
    {
        $country = Country::where('code', strtoupper($countryCode))->first();

        if (!$country || !$country->admin_levels) {
            return null;
        }

        $adminLevels = $country->admin_levels;
        $targetType = strtolower($type->value);

        foreach ($adminLevels as $dbLevel => $config) {
            if (!isset($config['name'])) {
                continue;
            }

            $levelName = strtolower($config['name']);
            if ($levelName === $targetType) {
                return (int) $dbLevel;
            }
        }

        return null;
    }
}
