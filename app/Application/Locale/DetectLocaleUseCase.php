<?php

namespace App\Application\Locale;

use App\Domain\Locale\Policies\LocalePolicy;
use App\Domain\Locale\ValueObjects\Locale;
use App\Services\GeoLocation\Contracts\GeoIpProvider;

final class DetectLocaleUseCase
{
    public function __construct(private readonly GeoIpProvider $geoIpProvider)
    {
    }

    public function execute($request, ?string $orgLanguage = null): Locale
    {
        // Priority 1: Organization default language (highest priority)
        if ($orgLanguage && Locale::isSupported($orgLanguage)) {
            return new Locale($orgLanguage);
        }

        // Priority 2: Geo-detected language from IP (for non-private IPs)
        $ip = $request->ip() ?? '127.0.0.1';

        if (!$this->isPrivateIp($ip)) {
            $countryCode = $this->geoIpProvider->getCountryCode($ip);
            $geoLocale = LocalePolicy::fromCountry($countryCode);

            if (Locale::isSupported($geoLocale->value())) {
                return $geoLocale;
            }
        }

        // Priority 3: Browser Accept-Language header
        $browserLocale = $this->detectFromBrowser($request);
        if ($browserLocale && Locale::isSupported($browserLocale)) {
            return new Locale($browserLocale);
        }

        // Priority 4: English fallback
        return Locale::default();
    }

    private function detectFromBrowser($request): ?string
    {
        $acceptLanguage = $request->header('Accept-Language', '');

        if (!$acceptLanguage) {
            return null;
        }

        $lang = explode(',', $acceptLanguage)[0];
        $base = strtolower(explode('-', $lang)[0]);

        return Locale::isSupported($base) ? $base : null;
    }

    private function isPrivateIp(string $ip): bool
    {
        return in_array($ip, ['127.0.0.1', '::1'], strict: true)
            || str_starts_with($ip, '192.168.')
            || str_starts_with($ip, '10.')
            || str_starts_with($ip, '172.');
    }
}
