# GeoLocation Service

A Laravel service for IP-based geolocation with intelligent caching, provider abstraction, and domain integration.

## Quick Start

```php
use App\Services\GeoLocation\Facades\GeoLocation;

// Get country code from IP
$country = GeoLocation::getCountryCode('103.20.30.40'); // "NP"

// Get full location details
$location = GeoLocation::getLocation('103.20.30.40');
// Location {
//   countryCode: "NP"
//   countryName: "Nepal"
//   region: "Bagmati"
//   city: "Kathmandu"
//   timezone: "Asia/Kathmandu"
// }

// Get timezone
$tz = GeoLocation::getTimezone('103.20.30.40'); // "Asia/Kathmandu"

// Map country code to application locale
$locale = GeoLocation::mapCountryToLocale('NP'); // "np"
```

## Features

- ✅ **Provider Abstraction** — Swap IP providers without code changes
- ✅ **Intelligent Caching** — 24-hour cache per IP with automatic invalidation
- ✅ **Private IP Detection** — Gracefully handles local/private IPs
- ✅ **Domain Integration** — Returns domain value objects, not arrays
- ✅ **Locale Mapping** — Direct country-to-application-locale translation
- ✅ **Error Resilience** — Logs failures, returns null gracefully
- ✅ **Type Safety** — Fully typed, IDE-friendly

## Architecture

```
GeoLocation Facade (simplest API)
    ↓
GeoLocationService (orchestration)
    ↓
GeoIpProvider Interface (abstraction)
    ↓
IpApiProvider (implementation)
```

### Layer Responsibilities

| Layer | Purpose | Mutable? |
|-------|---------|----------|
| **Facade** | Simplified static access | Read-only |
| **Service** | Orchestration & caching | Read-only |
| **Interface** | Provider contract | Yes (swap implementations) |
| **Provider** | API calls & parsing | Read-only |

## Configuration

### Service Provider Registration

The facade is auto-registered in `config/app.php`:

```php
'providers' => [
    // ...
    App\Services\GeoLocation\GeoLocationServiceProvider::class,
],

'aliases' => [
    // ...
    'GeoLocation' => App\Services\GeoLocation\Facades\GeoLocation::class,
],
```

### Environment Variables

Currently using free `ip-api.com` (rate limit: 45 requests/minute). For production:

```bash
# .env
GEO_PROVIDER=ip-api  # or custom provider
GEO_TIMEOUT=3        # seconds
```

## Use Cases

### 1. Auto-Detect User Language (Location-Based)

```php
use App\Services\GeoLocation\Facades\GeoLocation;

$ip = $request->ip();
$countryCode = GeoLocation::getCountryCode($ip);
$locale = GeoLocation::mapCountryToLocale($countryCode);

app()->setLocale($locale);
```

### 2. Populate User Address from IP

```php
$location = GeoLocation::getLocation($ip);

if ($location) {
    $user->update([
        'country' => $location->countryName,
        'city' => $location->city,
        'timezone' => $location->timezone,
    ]);
}
```

### 3. Detect User's Timezone

```php
$tz = GeoLocation::getTimezone($request->ip());
if ($tz) {
    $user->update(['timezone' => $tz]);
}
```

### 4. Geographic Analytics

```php
// Log location for analytics
$location = GeoLocation::getLocation($ip);
event(new UserLocationDetected($user, $location));
```

## API Reference

### `getCountryCode(string $ip): ?string`

Returns 2-letter ISO country code or null.

```php
GeoLocation::getCountryCode('103.20.30.40'); // "NP"
GeoLocation::getCountryCode('192.168.1.1');  // null (private IP)
```

### `getLocation(string $ip): ?Location`

Returns Location value object with full details.

```php
$loc = GeoLocation::getLocation('103.20.30.40');
// Location {
//   countryCode: "NP"
//   countryName: "Nepal"
//   region: "Bagmati"
//   city: "Kathmandu"
//   postalCode: "44600"
//   latitude: 27.7172
//   longitude: 85.3240
//   timezone: "Asia/Kathmandu"
// }

$array = $loc->toArray(); // Convert to array for responses
```

### `getTimezone(string $ip): ?string`

Returns IANA timezone identifier.

```php
GeoLocation::getTimezone('103.20.30.40'); // "Asia/Kathmandu"
```

### `mapCountryToLocale(?string $countryCode): string`

Maps country code to application locale (delegates to LocalePolicy).

```php
GeoLocation::mapCountryToLocale('NP'); // "np"
GeoLocation::mapCountryToLocale('DE'); // "de"
GeoLocation::mapCountryToLocale(null); // "en" (fallback)
```

## Caching Strategy

### Cache Key Format
```
geo:location:{ip}
```

### TTL
24 hours (86400 seconds)

### Cache Invalidation

Manual invalidation when needed:

```php
use Illuminate\Support\Facades\Cache;

// Clear specific IP
Cache::forget('geo:location:103.20.30.40');

// Clear all geo cache
Cache::flush(); // ⚠️ clears everything, use with caution
```

### Cache Hit Rate Optimization

For high-traffic applications, most IPs are repeat visits:
- First request: API call + cache miss
- Subsequent requests (24h): Instant cache hit
- Expected hit rate: 85-95% in production

## Error Handling

### API Failures

The provider gracefully handles failures:

```php
try {
    // Timeout, network error, or API down
    $location = GeoLocation::getLocation($ip);
} catch (\Exception $e) {
    // Logged as warning, returns null
    // App continues functioning
}
```

### Private IP Detection

Private IPs return null immediately (no API call):

```php
// These return null without API call:
GeoLocation::getLocation('127.0.0.1');      // Loopback
GeoLocation::getLocation('::1');             // IPv6 loopback
GeoLocation::getLocation('192.168.1.1');    // Private range
GeoLocation::getLocation('10.20.30.40');    // Private range
GeoLocation::getLocation('172.16.0.1');     // Private range
```

## Extending the Service

### Adding a New Provider

1. Create provider class implementing `GeoIpProvider`:

```php
namespace App\Services\GeoLocation\Providers;

use App\Services\GeoLocation\Contracts\GeoIpProvider;
use App\Services\GeoLocation\ValueObjects\Location;

class MaxMindProvider implements GeoIpProvider
{
    public function getCountryCode(string $ip): ?string
    {
        // Your implementation
    }

    public function getLocation(string $ip): ?Location
    {
        // Your implementation
    }

    public function getTimezone(string $ip): ?string
    {
        // Your implementation
    }
}
```

2. Update service provider:

```php
// app/Services/GeoLocation/GeoLocationServiceProvider.php
public function register(): void
{
    // Swap the provider binding
    $this->app->bind(GeoIpProvider::class, MaxMindProvider::class);
    // ... rest unchanged
}
```

3. No code changes needed elsewhere — facade automatically uses new provider!

## Testing

### Mock the Facade

```php
use App\Services\GeoLocation\Facades\GeoLocation;

GeoLocation::shouldReceive('getCountryCode')
    ->with('103.20.30.40')
    ->andReturn('NP');

GeoLocation::shouldReceive('getLocation')
    ->with('103.20.30.40')
    ->andReturn(new Location('NP', 'Nepal', ...));
```

### Test Helper

```php
// tests/Helpers/GeoLocationTestHelper.php
public static function stubNepaliIp(string $ip = '103.20.30.40'): void
{
    GeoLocation::shouldReceive('getCountryCode')
        ->with($ip)
        ->andReturn('NP');
}
```

## Common Patterns

### With Fallback

```php
$locale = GeoLocation::mapCountryToLocale(
    GeoLocation::getCountryCode($ip) ?? 'US'
); // Fallback to US if IP fails
```

### Cache with Custom TTL

```php
use Illuminate\Support\Facades\Cache;

$location = Cache::remember("geo:location:{$ip}", 7200, function () use ($ip) {
    return GeoLocation::getLocation($ip);
});
```

### Async Detection (Queue Job)

```php
class DetectUserLocation implements ShouldQueue
{
    public function handle()
    {
        $location = GeoLocation::getLocation($this->user->last_ip);
        $this->user->update(['country' => $location->countryName]);
    }
}
```

## Troubleshooting

| Issue | Cause | Solution |
|-------|-------|----------|
| Always returns null | Private IP detected | Check if IP is in 127.0.0.1, 192.168.x.x, 10.x.x.x, 172.16-31.x.x |
| Slow response | Cache miss, slow API | First request takes 3s, subsequent instant. Normal. |
| API timeout | Network issue | Check ip-api.com status, increase TIMEOUT env var |
| Stale cache | IP location data changed | Clear cache: `Cache::forget('geo:location:...')` |
| Wrong locale | Country not mapped | Check `LocalePolicy::fromCountry()` mapping |

## Performance Notes

- **Cached request:** ~1ms (Redis)
- **Uncached request:** ~3-5s (API call)
- **Private IP:** ~0.1ms (no API call)
- **Cache efficiency:** 85-95% hit rate (most users repeat visit)

## Security Considerations

- ✅ Private IPs skipped (no external API call)
- ✅ Timeout set to 3s (prevents hanging)
- ✅ Errors logged, not exposed to users
- ✅ Cache TTL prevents stale data
- ⚠️ Free API has rate limits (use paid tier for high traffic)

## See Also

- [Developer Guide](developer_guide/) — Deep dive into architecture
- [LocalePolicy](../../Domain/Locale/Policies/LocalePolicy.php) — Country-to-locale mapping
- [DetectLocaleUseCase](../../Application/Locale/DetectLocaleUseCase.php) — Using geo with organization language priority
