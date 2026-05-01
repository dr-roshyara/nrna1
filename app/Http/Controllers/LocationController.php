<?php

namespace App\Http\Controllers;

use App\Services\GeoLocation\Facades\GeoLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    public function detect(Request $request)
    {
        $ip = $request->ip();
        $browserTimezone = $request->input('timezone');

        $location = GeoLocation::getLocation($ip);
        $locale = GeoLocation::mapCountryToLocale($location?->countryCode);

        // Prefer IP-detected timezone, fallback to browser, then UTC
        $timezone = $location?->timezone ?? $browserTimezone ?? 'UTC';

        // 🌍 Log geo-location detection
        Log::info('🌍 Geo-location detection', [
            'ip' => $ip,
            'browser_timezone' => $browserTimezone,
            'detected_country' => $location?->countryCode,
            'detected_country_name' => $location?->countryName,
            'detected_region' => $location?->region,
            'detected_city' => $location?->city,
            'detected_timezone' => $location?->timezone,
            'resolved_locale' => $locale,
            'resolved_timezone' => $timezone,
            'location_data' => $location?->toArray(),
        ]);

        return response()->json([
            'locale' => $locale,
            'timezone' => $timezone,
            'location' => $location?->toArray(),
            'ip' => $ip,
        ]);
    }
}
