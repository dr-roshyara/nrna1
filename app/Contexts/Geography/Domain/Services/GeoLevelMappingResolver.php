<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\Services;

use App\Contexts\Geography\Domain\Enums\GeoLevelType;

interface GeoLevelMappingResolver
{
    /**
     * Resolve the database level for a given country and level type.
     * 
     * @param string $countryCode ISO 3166-1 alpha-2 country code (e.g., 'NP', 'DE')
     * @param GeoLevelType $type The geographic level type
     * @return int|null The database level (1-4), or null if not found
     */
    public function resolve(string $countryCode, GeoLevelType $type): ?int;
}
