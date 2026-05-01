<?php

namespace App\Services\GeoLocation\Services;

use App\Services\GeoLocation\Contracts\GeoIpProvider;
use App\Services\GeoLocation\ValueObjects\Location;

class GeoLocationService
{
    public function __construct(private readonly GeoIpProvider $provider) {}

    public function getCountryCode(string $ip): ?string
    {
        return $this->provider->getCountryCode($ip);
    }

    public function getLocation(string $ip): ?Location
    {
        return $this->provider->getLocation($ip);
    }

    public function getTimezone(string $ip): ?string
    {
        return $this->provider->getTimezone($ip);
    }

    public function mapCountryToLocale(?string $countryCode): string
    {
        return match ($countryCode) {
            'NP' => 'np',
            'DE', 'AT', 'CH', 'LI', 'LU', 'BE' => 'de',
            default => 'en',
        };
    }
}
