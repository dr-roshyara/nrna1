<?php

namespace App\Services\GeoLocation\Contracts;

use App\Services\GeoLocation\ValueObjects\Location;

interface GeoIpProvider
{
    public function getCountryCode(string $ip): ?string;

    public function getLocation(string $ip): ?Location;

    public function getTimezone(string $ip): ?string;
}
