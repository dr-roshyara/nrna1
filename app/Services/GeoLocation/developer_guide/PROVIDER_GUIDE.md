# Adding New GeoLocation Providers

Complete guide to implementing custom geolocation providers.

## Quick Start: 5-Minute Provider

### Step 1: Create Provider Class

Create `app/Services/GeoLocation/Providers/YourProvider.php`:

```php
<?php

namespace App\Services\GeoLocation\Providers;

use App\Services\GeoLocation\Contracts\GeoIpProvider;
use App\Services\GeoLocation\ValueObjects\Location;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class YourProvider implements GeoIpProvider
{
    private const CACHE_TTL = 86400; // 24 hours
    private const TIMEOUT = 3;       // seconds

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
                    ->get("https://api.yourprovider.com/v1/geoip/{$ip}");

                return Location::fromYourApiResponse($response->json());
            } catch (\Exception $e) {
                \Log::warning('Geolocation lookup failed', [
                    'ip' => $ip,
                    'provider' => self::class,
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
```

### Step 2: Update Service Provider

Edit `app/Services/GeoLocation/GeoLocationServiceProvider.php`:

```php
public function register(): void
{
    // Change this line:
    $this->app->bind(GeoIpProvider::class, YourProvider::class);

    $this->app->singleton('geo-location', function ($app) {
        return new GeoLocationService($app->make(GeoIpProvider::class));
    });
}
```

### Step 3: Add Response Parser

Add factory method to `Location`:

```php
// In app/Services/GeoLocation/ValueObjects/Location.php
public static function fromYourApiResponse(array $data): ?self
{
    // Validate your API's response format
    if (empty($data['status']) || $data['status'] !== 'success') {
        return null;
    }

    return new self(
        countryCode: $data['country_code'] ?? null,
        countryName: $data['country_name'] ?? null,
        region: $data['region'] ?? null,
        city: $data['city'] ?? null,
        postalCode: $data['postal_code'] ?? null,
        latitude: $data['latitude'] ?? null,
        longitude: $data['longitude'] ?? null,
        timezone: $data['timezone'] ?? null,
    );
}
```

### Step 4: Done! 🎉

```php
// Your provider is now active
GeoLocation::getCountryCode('103.20.30.40'); // Uses YourProvider
```

## Detailed Implementation Guide

### API Response Parsing

Each provider has unique response formats. Create a dedicated factory method.

#### Example: MaxMind

```php
// MaxMind response
{
    "ip": "103.20.30.40",
    "is_in_european_union": false,
    "most_specific_subdivision_geoname_id": 1226091,
    "most_specific_subdivision_iso_code": "BA",
    "most_specific_subdivision_name": "Bagmati",
    "geoname_id": 1282508,
    "timezone": "Asia/Kathmandu",
    "locale_code": "en",
    "country_geoname_id": 1668284,
    "country_iso_code": "NP",
    "country_name": "Nepal",
    "continent_geoname_id": 6255146,
    "continent_code": "AS",
    "continent_name": "Asia",
    "city_geoname_id": 1283240,
    "city_name": "Kathmandu",
    "postal_code": "44600",
    "latitude": 27.7172,
    "longitude": 85.3240,
    "accuracy_radius": 1000,
    "time_zone": "Asia/Kathmandu"
}

// Parser
public static function fromMaxMindResponse(array $data): ?self
{
    return new self(
        countryCode: $data['country_iso_code'] ?? null,      // Note: iso_code, not code
        countryName: $data['country_name'] ?? null,
        region: $data['most_specific_subdivision_name'] ?? null, // Nested field
        city: $data['city_name'] ?? null,
        postalCode: $data['postal_code'] ?? null,
        latitude: $data['latitude'] ?? null,
        longitude: $data['longitude'] ?? null,
        timezone: $data['timezone'] ?? null,                  // Note: timezone, not time_zone
    );
}
```

#### Example: IPStack

```php
// IPStack response
{
    "ip": "103.20.30.40",
    "type": "ipv4",
    "continent_code": "AS",
    "continent_name": "Asia",
    "country_code": "NP",
    "country_name": "Nepal",
    "region_code": "BA",
    "region_name": "Bagmati",
    "city": "Kathmandu",
    "zip": "44600",
    "latitude": 27.7172,
    "longitude": 85.3240,
    "location": {
        "geoname_id": 1282508,
        "capital": "Kathmandu",
        "languages": [{...}],
        "country_flag": "https://...",
        "country_flag_emoji": "🇳🇵",
        "country_flag_emoji_unicode": "U+1F1F3 U+1F1F5",
        "calling_code": "977",
        "is_eu": false,
        "timezone": "Asia/Kathmandu"
    },
    "time_zone": {
        "id": "Asia/Kathmandu",
        "current_time": "2024-03-10T12:34:56+05:45",
        "gmt_offset": 20700,
        "code": "NPT",
        "is_daylight_saving": false
    }
}

// Parser
public static function fromIpStackResponse(array $data): ?self
{
    return new self(
        countryCode: $data['country_code'] ?? null,
        countryName: $data['country_name'] ?? null,
        region: $data['region_name'] ?? null,
        city: $data['city'] ?? null,
        postalCode: $data['zip'] ?? null,
        latitude: $data['latitude'] ?? null,
        longitude: $data['longitude'] ?? null,
        timezone: $data['time_zone']['id'] ?? null,        // Nested in time_zone object
    );
}
```

### Testing Your Provider

Create `tests/Unit/Services/GeoLocation/Providers/YourProviderTest.php`:

```php
<?php

namespace Tests\Unit\Services\GeoLocation\Providers;

use App\Services\GeoLocation\Providers\YourProvider;
use App\Services\GeoLocation\ValueObjects\Location;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\TestCase;

class YourProviderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush(); // Clear cache between tests
    }

    public function test_get_country_code_returns_country_from_api()
    {
        Http::fake([
            'api.yourprovider.com/*' => Http::response([
                'status' => 'success',
                'country_code' => 'NP',
                'country_name' => 'Nepal',
                'timezone' => 'Asia/Kathmandu',
            ]),
        ]);

        $provider = new YourProvider();
        $code = $provider->getCountryCode('103.20.30.40');

        $this->assertEquals('NP', $code);
    }

    public function test_get_location_returns_complete_data()
    {
        Http::fake([
            'api.yourprovider.com/*' => Http::response([
                'status' => 'success',
                'country_code' => 'NP',
                'country_name' => 'Nepal',
                'region' => 'Bagmati',
                'city' => 'Kathmandu',
                'postal_code' => '44600',
                'latitude' => 27.7172,
                'longitude' => 85.3240,
                'timezone' => 'Asia/Kathmandu',
            ]),
        ]);

        $provider = new YourProvider();
        $location = $provider->getLocation('103.20.30.40');

        $this->assertInstanceOf(Location::class, $location);
        $this->assertEquals('NP', $location->countryCode);
        $this->assertEquals('Nepal', $location->countryName);
        $this->assertEquals('Kathmandu', $location->city);
        $this->assertEquals('Asia/Kathmandu', $location->timezone);
    }

    public function test_private_ip_returns_null_without_api_call()
    {
        $provider = new YourProvider();
        $location = $provider->getLocation('192.168.1.1');

        $this->assertNull($location);
        Http::assertNothingSent(); // No API call made
    }

    public function test_api_failure_returns_null_and_logs()
    {
        Http::fake([
            'api.yourprovider.com/*' => Http::response([], 500),
        ]);

        $provider = new YourProvider();
        $location = $provider->getLocation('103.20.30.40');

        $this->assertNull($location);
        // Verify logged warning
    }

    public function test_caches_location_for_24_hours()
    {
        Http::fake(['api.yourprovider.com/*' => Http::response([...])]);

        $provider = new YourProvider();

        // First call: API hit
        $location1 = $provider->getLocation('103.20.30.40');
        Http::assertSentCount(1);

        // Second call: Cache hit (no new request)
        $location2 = $provider->getLocation('103.20.30.40');
        Http::assertSentCount(1); // Still 1, not 2
    }

    public function test_timeout_is_respected()
    {
        Http::fake([
            'api.yourprovider.com/*' => Http::response([...]),
        ]);

        $provider = new YourProvider();
        $provider->getLocation('103.20.30.40');

        // Verify timeout was set
        Http::assertSent(function ($request) {
            return $request->timeout === 3; // Your timeout value
        });
    }
}
```

### Environment Configuration

If your provider needs API keys, add to `.env`:

```bash
GEO_PROVIDER_API_KEY=your_key_here
GEO_PROVIDER_TIMEOUT=3
```

Access in provider:

```php
class YourProvider implements GeoIpProvider
{
    private function getApiKey(): string
    {
        return config('services.geo.api_key') 
            ?? env('GEO_PROVIDER_API_KEY');
    }

    private function getTimeout(): int
    {
        return config('services.geo.timeout', 3);
    }

    public function getLocation(string $ip): ?Location
    {
        return Cache::remember("geo:location:{$ip}", self::CACHE_TTL, function () use ($ip) {
            $response = Http::timeout($this->getTimeout())
                ->get("https://api.yourprovider.com/v1/geoip/{$ip}", [
                    'api_key' => $this->getApiKey(),
                ]);
        });
    }
}
```

## Provider Comparison

| Provider | Cost | Accuracy | Rate Limit | Response Time |
|----------|------|----------|-----------|----------------|
| **ip-api.com** | Free | 95% | 45/min | 100-500ms |
| **MaxMind** | $40/mo | 99.9% | Unlimited | 10-50ms |
| **IPStack** | $9.99/mo | 98% | 500/mo | 100-300ms |
| **GeoIP2** | $19/mo | 99.8% | 100k/mo | 20-100ms |

### When to Use Each

- **ip-api.com:** Development, testing, low-traffic apps
- **MaxMind:** Production, high accuracy needed, budget available
- **IPStack:** Budget-conscious, adequate accuracy
- **GeoIP2:** Enterprise, needs fine-grained control

## Migration Strategy

### Old Code Using IpApiProvider

```php
// Direct import (tightly coupled)
$provider = new IpApiProvider();
$location = $provider->getLocation('...');
```

### New Code Using Facade (loosely coupled)

```php
// Use facade (swappable)
$location = GeoLocation::getLocation('...');
```

### Migration Steps

1. **Update LocationController & DetectLocaleUseCase** to use `GeoIpProvider` interface (not concrete class)
2. **Update all direct imports** to facade or inject interface
3. **Test with new provider** — just change service provider binding
4. **Switch providers** — single line change in service provider

## Handling Provider-Specific Edge Cases

### Example: MaxMind with Offline Database

```php
class MaxMindProvider implements GeoIpProvider
{
    private $reader;

    public function __construct(private readonly string $dbPath)
    {
        $this->reader = new Reader($dbPath);
    }

    public function getLocation(string $ip): ?Location
    {
        try {
            $record = $this->reader->city($ip);
            return Location::fromMaxMindRecord($record);
        } catch (\Exception $e) {
            \Log::warning('MaxMind lookup failed', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
```

Update service provider:

```php
public function register(): void
{
    // Bind with factory for initialization
    $this->app->bind(GeoIpProvider::class, function ($app) {
        $dbPath = storage_path('geo-databases/GeoLite2-City.mmdb');
        return new MaxMindProvider($dbPath);
    });
}
```

## Performance Optimization

### Query-Specific Caching

If provider charges per query, cache more aggressively:

```php
class PaidApiProvider implements GeoIpProvider
{
    private const CACHE_TTL = 604800; // 7 days instead of 24 hours
    // Or even: 2592000 (30 days)
}
```

### Request Batching

For providers supporting batch requests:

```php
public function getMultipleLocations(array $ips): array
{
    return Http::post('https://api.provider.com/v1/batch', [
        'ips' => $ips,
    ])->json(); // Single API call for 100 IPs
}
```

### Local Database Fallback

```php
class HybridProvider implements GeoIpProvider
{
    public function __construct(
        private readonly MaxMindProvider $local,
        private readonly IpApiProvider $remote,
    ) {}

    public function getLocation(string $ip): ?Location
    {
        // Try local first (instant, offline)
        if ($location = $this->local->getLocation($ip)) {
            return $location;
        }
        // Fall back to remote API
        return $this->remote->getLocation($ip);
    }
}
```

## Checklist for New Provider

- [ ] Implement `GeoIpProvider` interface
- [ ] Handle private IPs (return null immediately)
- [ ] Set reasonable timeout (3-5 seconds)
- [ ] Parse response format correctly
- [ ] Create `Location::fromYourApiResponse()` factory
- [ ] Implement caching with `Cache::remember()`
- [ ] Log errors (not exceptions)
- [ ] Write comprehensive tests
- [ ] Update service provider binding
- [ ] Document any API key requirements
- [ ] Benchmark with real IPs
- [ ] Test cache invalidation
- [ ] Handle rate limit scenarios
- [ ] Add to `README.md` provider comparison table

## See Also

- [ARCHITECTURE.md](ARCHITECTURE.md) — Design patterns used
- [TESTING.md](TESTING.md) — Testing strategies
- [README.md](../README.md) — Quick start guide
