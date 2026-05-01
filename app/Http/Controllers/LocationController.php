<?php

namespace App\Http\Controllers;

use App\Application\Locale\DetectLocaleUseCase;
use App\Domain\Locale\ValueObjects\Locale;
use App\Services\GeoLocation\Facades\GeoLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LocationController extends Controller
{
    public function __construct(private readonly DetectLocaleUseCase $detectLocaleUseCase)
    {
    }

    public function detect(Request $request)
    {
        $ip = $request->ip();
        $browserTimezone = $request->input('timezone');

        // Get user's organization language if authenticated
        $orgLanguage = null;
        $user = $request->user();

        if ($user && $user->organisation_id) {
            $organisation = $user->currentOrganisation;
            $orgLanguage = $organisation?->default_language;
        }

        // Priority 1: Browser timezone (most accurate for language preference)
        $timezoneLocaleStr = $this->timezoneToLocale($browserTimezone);
        $locale = $timezoneLocaleStr ? new Locale($timezoneLocaleStr) : null;

        // Priority 2: Organization language
        // Priority 3: IP-based geo-detection
        // Fallback to use case if timezone didn't match
        if (!$locale) {
            $locale = $this->detectLocaleUseCase->execute($request, $orgLanguage);
        }

        // Get location data (for reference)
        $location = GeoLocation::getLocation($ip);
        $timezone = $location?->timezone ?? $browserTimezone ?? 'UTC';

        // Log the decision
        Log::info('🌍 Locale detection result', [
            'ip' => $ip,
            'user_id' => $user?->id,
            'org_id' => $user?->organisation_id,
            'org_language' => $orgLanguage,
            'detected_country' => $location?->countryCode,
            'final_locale' => $locale->value(),
            'decision_chain' => $orgLanguage ? 'organization' : ($location?->countryCode ? 'geo' : 'fallback'),
        ]);

        return response()->json([
            'locale' => $locale->value(),
            'timezone' => $timezone,
            'location' => $location?->toArray(),
            'ip' => $ip,
            'decision' => [
                'source' => $timezoneLocaleStr ? 'timezone' : ($orgLanguage ? 'organization' : ($location?->countryCode ? 'geo' : 'fallback')),
                'browser_timezone' => $browserTimezone,
                'org_language' => $orgLanguage,
                'detected_country' => $location?->countryCode,
            ],
        ]);
    }

    /**
     * Map browser timezone to application locale.
     * More accurate than IP geolocation because it's set by the user's device.
     */
    private function timezoneToLocale(?string $timezone): ?string
    {
        if (!$timezone) {
            return null;
        }

        // Map IANA timezone to application locale
        return match ($timezone) {
            // Nepali (UTC+5:45)
            'Asia/Kathmandu' => 'np',

            // German (UTC+1/+2)
            'Europe/Berlin',
            'Europe/Vienna',
            'Europe/Zurich',
            'Europe/Liechtenstein',
            'Europe/Luxembourg',
            'Europe/Brussels' => 'de',

            // English (UTC+0/+1)
            'Europe/London',
            'Europe/Dublin',
            'UTC',
            'GMT' => 'en',

            // Default: no timezone match
            default => null,
        };
    }
}
