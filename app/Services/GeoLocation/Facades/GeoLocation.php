<?php

namespace App\Services\GeoLocation\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string|null getCountryCode(string $ip)
 * @method static \App\Services\GeoLocation\ValueObjects\Location|null getLocation(string $ip)
 * @method static string|null getTimezone(string $ip)
 * @method static string mapCountryToLocale(string|null $countryCode)
 *
 * @see \App\Services\GeoLocation\Services\GeoLocationService
 */
class GeoLocation extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'geo-location';
    }
}
