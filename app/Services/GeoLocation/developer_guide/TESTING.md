# Testing GeoLocation Service

Comprehensive guide to testing the GeoLocation service at different levels.

## Testing Pyramid

```
        ┌─────────────────────┐
        │   Feature Tests      │  5-10
        │   (Full integration) │
        ├─────────────────────┤
        │  Integration Tests   │  15-20
        │  (Service + Cache)   │
        ├─────────────────────┤
        │    Unit Tests       │  40-50
        │   (Isolated logic)  │
        └─────────────────────┘
```

## Level 1: Unit Tests

### Test Location Value Object

**File:** `tests/Unit/Services/GeoLocation/ValueObjects/LocationTest.php`

```php
<?php

namespace Tests\Unit\Services\GeoLocation\ValueObjects;

use App\Services\GeoLocation\ValueObjects\Location;
use PHPUnit\Framework\TestCase;

class LocationTest extends TestCase
{
    public function test_location_is_readonly()
    {
        $location = new Location(
            countryCode: 'NP',
            countryName: 'Nepal',
            region: 'Bagmati',
            city: 'Kathmandu',
            postalCode: '44600',
            latitude: 27.7172,
            longitude: 85.3240,
            timezone: 'Asia/Kathmandu',
        );

        // Cannot modify properties
        $this->expectException(\Error::class);
        $location->countryCode = 'DE';
    }

    public function test_from_ip_api_response_parses_correctly()
    {
        $response = [
            'status' => 'success',
            'countryCode' => 'NP',
            'country' => 'Nepal',
            'regionName' => 'Bagmati',
            'city' => 'Kathmandu',
            'zip' => '44600',
            'lat' => 27.7172,
            'lon' => 85.3240,
            'timezone' => 'Asia/Kathmandu',
        ];

        $location = Location::fromIpApiResponse($response);

        $this->assertNotNull($location);
        $this->assertEquals('NP', $location->countryCode);
        $this->assertEquals('Nepal', $location->countryName);
        $this->assertEquals('Asia/Kathmandu', $location->timezone);
    }

    public function test_from_ip_api_response_returns_null_on_failure()
    {
        $response = ['status' => 'fail'];

        $location = Location::fromIpApiResponse($response);

        $this->assertNull($location);
    }

    public function test_to_array_filters_null_values()
    {
        $location = new Location(
            countryCode: 'NP',
            countryName: 'Nepal',
            region: null,        // Null
            city: 'Kathmandu',
            postalCode: null,    // Null
            latitude: 27.7172,
            longitude: 85.3240,
            timezone: 'Asia/Kathmandu',
        );

        $array = $location->toArray();

        // Null values should be filtered out
        $this->assertArrayHasKey('country_code', $array);
        $this->assertArrayNotHasKey('region', $array);
        $this->assertArrayNotHasKey('postal_code', $array);
    }

    public function test_to_array_uses_snake_case()
    {
        $location = new Location(
            countryCode: 'NP',
            countryName: 'Nepal',
            region: 'Bagmati',
            city: 'Kathmandu',
            postalCode: '44600',
            latitude: 27.7172,
            longitude: 85.3240,
            timezone: 'Asia/Kathmandu',
        );

        $array = $location->toArray();

        // Check snake_case keys
        $this->assertArrayHasKey('country_code', $array);
        $this->assertArrayHasKey('country_name', $array);
        $this->assertArrayHasKey('postal_code', $array);
    }
}
```

### Test IpApiProvider

**File:** `tests/Unit/Services/GeoLocation/Providers/IpApiProviderTest.php`

```php
<?php

namespace Tests\Unit\Services\GeoLocation\Providers;

use App\Services\GeoLocation\Providers\IpApiProvider;
use App\Services\GeoLocation\ValueObjects\Location;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IpApiProviderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_get_country_code_from_location()
    {
        Http::fake([
            'ip-api.com/json/*' => Http::response([
                'status' => 'success',
                'countryCode' => 'NP',
                'country' => 'Nepal',
                'timezone' => 'Asia/Kathmandu',
            ]),
        ]);

        $provider = new IpApiProvider();
        $code = $provider->getCountryCode('103.20.30.40');

        $this->assertEquals('NP', $code);
    }

    public function test_get_location_returns_location_object()
    {
        Http::fake([
            'ip-api.com/json/*' => Http::response([
                'status' => 'success',
                'countryCode' => 'NP',
                'country' => 'Nepal',
                'regionName' => 'Bagmati',
                'city' => 'Kathmandu',
                'zip' => '44600',
                'lat' => 27.7172,
                'lon' => 85.3240,
                'timezone' => 'Asia/Kathmandu',
            ]),
        ]);

        $provider = new IpApiProvider();
        $location = $provider->getLocation('103.20.30.40');

        $this->assertInstanceOf(Location::class, $location);
        $this->assertEquals('NP', $location->countryCode);
        $this->assertEquals('Nepal', $location->countryName);
    }

    public function test_get_timezone_from_location()
    {
        Http::fake([
            'ip-api.com/json/*' => Http::response([
                'status' => 'success',
                'countryCode' => 'NP',
                'timezone' => 'Asia/Kathmandu',
            ]),
        ]);

        $provider = new IpApiProvider();
        $tz = $provider->getTimezone('103.20.30.40');

        $this->assertEquals('Asia/Kathmandu', $tz);
    }

    public function test_private_ip_returns_null_immediately()
    {
        $provider = new IpApiProvider();
        $location = $provider->getLocation('127.0.0.1');

        $this->assertNull($location);
        Http::assertNothingSent(); // No HTTP request made
    }

    public function test_private_ip_ranges()
    {
        $provider = new IpApiProvider();

        // Test common private ranges
        $this->assertNull($provider->getLocation('127.0.0.1'));      // Loopback
        $this->assertNull($provider->getLocation('::1'));            // IPv6 loopback
        $this->assertNull($provider->getLocation('192.168.1.1'));   // Private
        $this->assertNull($provider->getLocation('10.0.0.1'));      // Private
        $this->assertNull($provider->getLocation('172.16.0.1'));    // Private

        Http::assertNothingSent();
    }

    public function test_timeout_is_respected()
    {
        Http::fake([
            'ip-api.com/json/*' => Http::response([
                'status' => 'success',
                'countryCode' => 'NP',
            ]),
        ]);

        $provider = new IpApiProvider();
        $provider->getLocation('103.20.30.40');

        // Verify timeout was applied
        Http::assertSent(function ($request) {
            return $request->timeout === 3;
        });
    }

    public function test_api_failure_returns_null()
    {
        Http::fake([
            'ip-api.com/json/*' => Http::response([], 500),
        ]);

        $provider = new IpApiProvider();
        $location = $provider->getLocation('103.20.30.40');

        $this->assertNull($location);
    }

    public function test_invalid_json_response_returns_null()
    {
        Http::fake([
            'ip-api.com/json/*' => Http::response('invalid json', 200, [
                'Content-Type' => 'text/plain',
            ]),
        ]);

        $provider = new IpApiProvider();
        $location = $provider->getLocation('103.20.30.40');

        $this->assertNull($location);
    }

    public function test_caches_location_for_24_hours()
    {
        Http::fake([
            'ip-api.com/json/*' => Http::response([
                'status' => 'success',
                'countryCode' => 'NP',
                'country' => 'Nepal',
                'timezone' => 'Asia/Kathmandu',
            ]),
        ]);

        $provider = new IpApiProvider();

        // First request: API call
        $location1 = $provider->getLocation('103.20.30.40');
        Http::assertSentCount(1);

        // Second request: Cache hit (no new request)
        $location2 = $provider->getLocation('103.20.30.40');
        Http::assertSentCount(1); // Still 1, not 2

        $this->assertEquals($location1->countryCode, $location2->countryCode);
    }

    public function test_each_ip_has_separate_cache()
    {
        Http::fake([
            'ip-api.com/json/*' => Http::response([
                'status' => 'success',
                'countryCode' => 'NP',
                'country' => 'Nepal',
            ]),
        ]);

        $provider = new IpApiProvider();

        $provider->getLocation('103.20.30.40');  // IP 1
        $provider->getLocation('1.1.1.1');       // IP 2

        Http::assertSentCount(2); // Two separate API calls
    }

    public function test_connection_timeout()
    {
        Http::fake([
            'ip-api.com/json/*' => function () {
                throw new \Illuminate\Http\Client\ConnectionException();
            },
        ]);

        $provider = new IpApiProvider();
        $location = $provider->getLocation('103.20.30.40');

        $this->assertNull($location);
    }
}
```

### Test GeoLocationService

**File:** `tests/Unit/Services/GeoLocation/Services/GeoLocationServiceTest.php`

```php
<?php

namespace Tests\Unit\Services\GeoLocation\Services;

use App\Services\GeoLocation\Contracts\GeoIpProvider;
use App\Services\GeoLocation\Services\GeoLocationService;
use App\Services\GeoLocation\ValueObjects\Location;
use Mockery;
use Tests\TestCase;

class GeoLocationServiceTest extends TestCase
{
    public function test_get_country_code_delegates_to_provider()
    {
        $provider = Mockery::mock(GeoIpProvider::class);
        $provider->shouldReceive('getCountryCode')
            ->with('103.20.30.40')
            ->once()
            ->andReturn('NP');

        $service = new GeoLocationService($provider);
        $code = $service->getCountryCode('103.20.30.40');

        $this->assertEquals('NP', $code);
    }

    public function test_get_location_delegates_to_provider()
    {
        $location = new Location(
            countryCode: 'NP',
            countryName: 'Nepal',
            region: 'Bagmati',
            city: 'Kathmandu',
            postalCode: '44600',
            latitude: 27.7172,
            longitude: 85.3240,
            timezone: 'Asia/Kathmandu',
        );

        $provider = Mockery::mock(GeoIpProvider::class);
        $provider->shouldReceive('getLocation')
            ->with('103.20.30.40')
            ->once()
            ->andReturn($location);

        $service = new GeoLocationService($provider);
        $result = $service->getLocation('103.20.30.40');

        $this->assertEquals($location, $result);
    }

    public function test_get_timezone_delegates_to_provider()
    {
        $provider = Mockery::mock(GeoIpProvider::class);
        $provider->shouldReceive('getTimezone')
            ->with('103.20.30.40')
            ->once()
            ->andReturn('Asia/Kathmandu');

        $service = new GeoLocationService($provider);
        $tz = $service->getTimezone('103.20.30.40');

        $this->assertEquals('Asia/Kathmandu', $tz);
    }

    public function test_map_country_to_locale()
    {
        $provider = Mockery::mock(GeoIpProvider::class);
        $service = new GeoLocationService($provider);

        $locale = $service->mapCountryToLocale('NP');
        $this->assertEquals('np', $locale);

        $locale = $service->mapCountryToLocale('DE');
        $this->assertEquals('de', $locale);

        $locale = $service->mapCountryToLocale(null);
        $this->assertEquals('en', $locale); // Default fallback
    }
}
```

## Level 2: Integration Tests

### Test with Real Cache + Mocked HTTP

**File:** `tests/Integration/Services/GeoLocation/GeoLocationServiceIntegrationTest.php`

```php
<?php

namespace Tests\Integration\Services\GeoLocation;

use App\Services\GeoLocation\Facades\GeoLocation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GeoLocationServiceIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush(); // Fresh cache for each test
    }

    public function test_facade_integration_with_real_service()
    {
        Http::fake([
            'ip-api.com/json/*' => Http::response([
                'status' => 'success',
                'countryCode' => 'NP',
                'country' => 'Nepal',
                'regionName' => 'Bagmati',
                'city' => 'Kathmandu',
                'timezone' => 'Asia/Kathmandu',
            ]),
        ]);

        // Using the facade
        $code = GeoLocation::getCountryCode('103.20.30.40');
        $this->assertEquals('NP', $code);

        $location = GeoLocation::getLocation('103.20.30.40');
        $this->assertEquals('Nepal', $location->countryName);

        $tz = GeoLocation::getTimezone('103.20.30.40');
        $this->assertEquals('Asia/Kathmandu', $tz);

        $locale = GeoLocation::mapCountryToLocale('NP');
        $this->assertEquals('np', $locale);
    }

    public function test_cache_is_used_across_multiple_calls()
    {
        Http::fake([
            'ip-api.com/json/*' => Http::response([
                'status' => 'success',
                'countryCode' => 'NP',
                'country' => 'Nepal',
                'timezone' => 'Asia/Kathmandu',
            ]),
        ]);

        // First call
        GeoLocation::getCountryCode('103.20.30.40');
        $this->assertEquals(1, Http::assertSentCount());

        // Second call should use cache
        GeoLocation::getCountryCode('103.20.30.40');
        $this->assertEquals(1, Http::assertSentCount()); // Still 1

        // Third call should use cache
        GeoLocation::getCountryCode('103.20.30.40');
        $this->assertEquals(1, Http::assertSentCount()); // Still 1
    }

    public function test_different_ips_make_separate_requests()
    {
        Http::fake([
            'ip-api.com/json/*' => Http::response([
                'status' => 'success',
                'countryCode' => 'NP',
                'country' => 'Nepal',
            ]),
        ]);

        GeoLocation::getCountryCode('103.20.30.40');
        GeoLocation::getCountryCode('1.1.1.1');
        GeoLocation::getCountryCode('8.8.8.8');

        $this->assertEquals(3, Http::assertSentCount());
    }

    public function test_locale_mapping_with_geo_lookup()
    {
        Http::fake([
            'ip-api.com/json/*' => Http::response([
                'status' => 'success',
                'countryCode' => 'NP',
                'country' => 'Nepal',
            ]),
        ]);

        $countryCode = GeoLocation::getCountryCode('103.20.30.40');
        $locale = GeoLocation::mapCountryToLocale($countryCode);

        $this->assertEquals('np', $locale);
    }
}
```

## Level 3: Feature Tests

### Test User Location Auto-Detection in Request

**File:** `tests/Feature/Http/Controllers/LocationControllerTest.php`

```php
<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LocationControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_detect_location_endpoint()
    {
        Http::fake([
            'ip-api.com/json/*' => Http::response([
                'status' => 'success',
                'countryCode' => 'NP',
                'country' => 'Nepal',
                'city' => 'Kathmandu',
                'timezone' => 'Asia/Kathmandu',
            ]),
        ]);

        $response = $this->get('/api/detect-location');

        $response->assertOk();
        $response->assertJsonPath('locale', 'np');
        $response->assertJsonPath('country_code', 'NP');
        $response->assertJsonPath('timezone', 'Asia/Kathmandu');
    }

    public function test_detect_location_with_custom_ip()
    {
        Http::fake([
            'ip-api.com/json/*' => Http::response([
                'status' => 'success',
                'countryCode' => 'DE',
                'country' => 'Germany',
                'timezone' => 'Europe/Berlin',
            ]),
        ]);

        $response = $this->getJson('/api/detect-location', [
            'X-Forwarded-For' => '103.20.30.40',
        ]);

        $response->assertOk();
        $response->assertJsonPath('locale', 'de');
    }

    public function test_detect_location_with_private_ip()
    {
        // Private IP should not make API call
        $response = $this->get('/api/detect-location', [
            'X-Forwarded-For' => '192.168.1.1',
        ]);

        $response->assertOk();
        $response->assertJsonPath('locale', 'en'); // Fallback
        Http::assertNothingSent();
    }
}
```

### Test Locale Setting in Middleware

**File:** `tests/Feature/Middleware/SetLocaleMiddlewareTest.php`

```php
<?php

namespace Tests\Feature\Middleware;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SetLocaleMiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_geo_location_sets_locale()
    {
        Http::fake([
            'ip-api.com/json/*' => Http::response([
                'status' => 'success',
                'countryCode' => 'NP',
            ]),
        ]);

        // Make request with Nepal IP
        $this->withHeaders(['X-Forwarded-For' => '103.20.30.40'])
            ->get('/dashboard');

        // Locale should be set to Nepali
        $this->assertEquals('np', app()->getLocale());
    }

    public function test_org_language_overrides_geo_detection()
    {
        $user = User::factory()->create();
        $organisation = \App\Models\Organisation::factory()->create([
            'default_language' => 'np',
        ]);
        $user->update(['organisation_id' => $organisation->id]);

        $this->actingAs($user);

        // Even though IP is Germany
        Http::fake([
            'ip-api.com/json/*' => Http::response([
                'status' => 'success',
                'countryCode' => 'DE',
            ]),
        ]);

        $this->withHeaders(['X-Forwarded-For' => '1.1.1.1'])
            ->get('/dashboard');

        // Org language wins
        $this->assertEquals('np', app()->getLocale());
    }
}
```

## Testing Best Practices

### 1. Isolate External Dependencies

```php
// ❌ Bad: Makes real HTTP request
public function test_something() {
    $provider = new IpApiProvider();
    $provider->getLocation('...');  // Real API call!
}

// ✅ Good: Mock HTTP
public function test_something() {
    Http::fake([...]);
    $provider = new IpApiProvider();
    $provider->getLocation('...');
}
```

### 2. Clear Cache Between Tests

```php
protected function setUp(): void
{
    parent::setUp();
    Cache::flush(); // Important!
}
```

### 3. Test Both Happy Path and Edge Cases

```php
public function test_success_case() { ... }           // Happy path
public function test_api_timeout() { ... }            // Edge case
public function test_invalid_response() { ... }       // Edge case
public function test_private_ip() { ... }             // Edge case
public function test_cache_hit() { ... }              // Performance
public function test_concurrent_requests() { ... }    // Load
```

### 4. Verify HTTP Calls

```php
// Verify a call was made
Http::assertSent(function ($request) {
    return $request->url() === 'https://...';
});

// Verify no calls were made
Http::assertNothingSent();

// Verify count
Http::assertSentCount(1);
```

### 5. Use Mockery for Dependencies

```php
$provider = Mockery::mock(GeoIpProvider::class);
$provider->shouldReceive('getLocation')
    ->with('103.20.30.40')
    ->once()
    ->andReturn($location);
```

## Running Tests

```bash
# All tests
php artisan test

# Only GeoLocation tests
php artisan test --filter=GeoLocation

# Only unit tests
php artisan test tests/Unit

# Only feature tests
php artisan test tests/Feature

# With coverage
php artisan test --coverage

# Specific test file
php artisan test tests/Unit/Services/GeoLocation/Providers/IpApiProviderTest.php

# Stop on first failure
php artisan test --fail-on-incomplete
```

## Coverage Goals

| Layer | Target | Why |
|-------|--------|-----|
| Unit | 90%+ | Core logic must be covered |
| Integration | 80%+ | Cache + service interaction |
| Feature | 70%+ | End-to-end flows |
| Overall | 85%+ | Confidence in reliability |

## Common Test Patterns

### Pattern 1: Mocking with Http::fake()

```php
Http::fake([
    'ip-api.com/json/*' => Http::response([
        'status' => 'success',
        'countryCode' => 'NP',
    ]),
]);
```

### Pattern 2: Verifying Calls

```php
Http::assertSent(function ($request) {
    return str_contains($request->url(), 'ip-api.com');
});
```

### Pattern 3: Testing Exceptions

```php
Http::fake([
    'ip-api.com/json/*' => function () {
        throw new \Illuminate\Http\Client\RequestException();
    },
]);

$this->assertNull($provider->getLocation('...'));
```

### Pattern 4: Testing Cache

```php
Cache::flush();
$provider->getLocation('103.20.30.40');

// Verify cached
$this->assertTrue(Cache::has('geo:location:103.20.30.40'));
```

## See Also

- [ARCHITECTURE.md](ARCHITECTURE.md) — Design patterns
- [IMPLEMENTATION.md](IMPLEMENTATION.md) — Development process
- [PROVIDER_GUIDE.md](PROVIDER_GUIDE.md) — Adding new providers
