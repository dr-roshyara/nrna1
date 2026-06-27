<?php

namespace App\Services\GeoLocation\Providers;

use App\Services\GeoLocation\Contracts\GeoIpProvider;
use App\Services\GeoLocation\ValueObjects\Location;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class IpApiProvider implements GeoIpProvider
{
    private const CACHE_TTL = 86400; // 24 hours
    private const TIMEOUT = 3; // seconds

    public function getCountryCode(string $ip): ?string
    {
        return $this->getLocation($ip)?->countryCode;
    }

    public function getLocation(string $ip): ?Location
    {
        if ($this->isPrivateIp($ip)) {
            return null;
        }

        return Cache::remember("geo:location:{$ip}", self::CACHE_TTL, function () use ($ip) {
            try {
                $response = Http::timeout(self::TIMEOUT)
                    ->get("http://ip-api.com/json/{$ip}?fields=status,country,countryCode,regionName,city,zip,lat,lon,timezone");

                return Location::fromIpApiResponse($response->json());
            } catch (\Exception $e) {
                \Log::warning('IP geolocation lookup failed', [
                    'ip' => $ip,
                    'error' => $e->getMessage(),
                ]);

                return null;
            }
        });
    }

    public function getTimezone(string $ip): ?string
    {
        return $this->getLocation($ip)?->timezone;
    }

    private function isPrivateIp(string $ip): bool
    {
        return in_array($ip, ['127.0.0.1', '::1'], strict: true)
            || str_starts_with($ip, '192.168.')
            || str_starts_with($ip, '10.')
            || str_starts_with($ip, '172.');
    }
}
