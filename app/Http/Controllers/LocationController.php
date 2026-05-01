<?php

namespace App\Http\Controllers;

use App\Application\Locale\DetectLocaleUseCase;
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

        // Detect locale with organization priority
        $locale = $this->detectLocaleUseCase->execute($request, $orgLanguage);

        // Get location data (for reference, not used for locale decision)
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
                'source' => $orgLanguage ? 'organization' : ($location?->countryCode ? 'geo' : 'fallback'),
                'org_language' => $orgLanguage,
                'detected_country' => $location?->countryCode,
            ],
        ]);
    }
}
