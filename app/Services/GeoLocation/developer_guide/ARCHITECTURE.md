# GeoLocation Service Architecture

## System Overview

```
┌─────────────────────────────────────────────────────────────┐
│                   Application Code                           │
│                   use GeoLocation;                           │
└────────────────────────┬────────────────────────────────────┘
                         │
┌────────────────────────▼────────────────────────────────────┐
│           GeoLocation Facade                                │
│  ├─ getCountryCode(ip): ?string                            │
│  ├─ getLocation(ip): ?Location                             │
│  ├─ getTimezone(ip): ?string                               │
│  └─ mapCountryToLocale(code): string                       │
│                                                             │
│  Purpose: Static access point, method documentation       │
└────────────────────────┬────────────────────────────────────┘
                         │ resolves to
┌────────────────────────▼────────────────────────────────────┐
│           GeoLocationService                                │
│  ├─ __construct(GeoIpProvider)                            │
│  ├─ getCountryCode(ip)                                    │
│  ├─ getLocation(ip)                                       │
│  ├─ getTimezone(ip)                                       │
│  └─ mapCountryToLocale(code)                              │
│                                                             │
│  Purpose: Orchestration, caching, delegation              │
└────────────────────────┬────────────────────────────────────┘
                         │ delegates to
┌────────────────────────▼────────────────────────────────────┐
│        GeoIpProvider Interface                              │
│  ├─ getCountryCode(ip): ?string                           │
│  ├─ getLocation(ip): ?Location                            │
│  └─ getTimezone(ip): ?string                              │
│                                                             │
│  Purpose: Provider contract                               │
└────────────────────────┬────────────────────────────────────┘
         Implementations │
    ┌────────┴────────────┐
    │                     │
    ▼                     ▼
┌─────────────┐   ┌──────────────────┐
│IpApiProvider│   │MaxMindProvider   │
│(free API)  │   │(paid API)        │
└─────────────┘   └──────────────────┘
    (existing)      (can be added)
```

## Layer Breakdown

### 1. Facade Layer

**File:** `Facades/GeoLocation.php`

**Purpose:**
- Provide static method access
- Document all public methods
- Single entry point for consumers

**Key Characteristics:**
- Zero business logic
- Zero caching logic
- Only forwards calls to service
- IDE-friendly with `@method` PHPDoc

**Pattern:** Facade Pattern (Laravel)

```php
GeoLocation::getCountryCode('103.20.30.40');
// Facade resolves 'geo-location' from container
// Calls GeoLocationService::getCountryCode()
```

### 2. Service Layer

**File:** `Services/GeoLocationService.php`

**Purpose:**
- Orchestration
- Caching coordination
- Locale mapping delegation
- Error handling

**Key Characteristics:**
- Thin orchestration layer
- Constructor injection of provider
- Delegates to provider for actual work
- No caching logic (delegated to provider)

**Pattern:** Service Locator Pattern (simplified)

```php
class GeoLocationService {
    public function __construct(private readonly GeoIpProvider $provider) {}
    
    public function getCountryCode(string $ip): ?string
    {
        return $this->provider->getCountryCode($ip);
    }
}
```

### 3. Provider Interface

**File:** `Contracts/GeoIpProvider.php`

**Purpose:**
- Define provider contract
- Enable provider swapping
- Enforce consistent API

**Key Characteristics:**
- Pure interface, no implementation
- Three methods: getCountryCode, getLocation, getTimezone
- Returns domain value objects (Location)
- Providers are responsible for caching

**Pattern:** Strategy Pattern / Adapter Pattern

```php
interface GeoIpProvider {
    public function getCountryCode(string $ip): ?string;
    public function getLocation(string $ip): ?Location;
    public function getTimezone(string $ip): ?string;
}
```

### 4. Provider Implementation

**File:** `Providers/IpApiProvider.php`

**Purpose:**
- Make API calls to geolocation service
- Parse responses into domain objects
- Handle failures gracefully
- Cache results

**Key Characteristics:**
- Implements GeoIpProvider contract
- Uses Laravel Http client for API calls
- Caches with 24-hour TTL
- Detects and skips private IPs
- Logs failures, returns null

**Pattern:** Adapter Pattern (adapts external API to our interface)

## Key Design Decisions

### 1. Why Provider Interface?

✅ **Swappable implementations**
- Start with free API (ip-api.com)
- Switch to MaxMind with single binding change
- No code changes in service or facade

❌ Hardcoded API calls
- Tightly coupled to ip-api.com
- Cannot swap providers easily
- Changes everywhere

### 2. Why Service Layer?

✅ **Centralized orchestration**
- Can add caching later (currently in provider)
- Can add logging/metrics
- Single point for business logic

❌ Direct facade to provider
- Mixes concerns
- Cannot evolve without changing facade

### 3. Why Facade?

✅ **Familiar Laravel pattern**
- Developers expect `GeoLocation::method()`
- IDE autocomplete works
- Static methods feel natural

❌ Direct service usage
- Inconsistent with Laravel conventions
- Requires service injection everywhere
- Less discoverable

### 4. Caching Strategy: Why at Provider Level?

✅ **Provider owns data freshness**
- IpApiProvider knows API quirks
- Can implement provider-specific caching
- Cache invalidation logic close to data fetch

❌ Caching at service level
- Service shouldn't know about cache
- Every provider implements same cache logic
- Cache invalidation scattered

## Data Flow Examples

### Example 1: Cache Hit

```
GeoLocation::getCountryCode('103.20.30.40')
    ↓
GeoLocationService::getCountryCode('103.20.30.40')
    ↓
IpApiProvider::getCountryCode('103.20.30.40')
    ↓
Cache::remember('geo:location:103.20.30.40', ...)
    ↓ (hit)
Return cached Location object from Redis
    ↓
Extract countryCode property
    ↓
Return "NP"
```

**Time:** ~1ms

### Example 2: Cache Miss

```
GeoLocation::getCountryCode('103.20.30.40')
    ↓
GeoLocationService::getCountryCode('103.20.30.40')
    ↓
IpApiProvider::getCountryCode('103.20.30.40')
    ↓
Cache::remember('geo:location:103.20.30.40', ...)
    ↓ (miss)
isPrivateIp('103.20.30.40') → false
    ↓
Http::get('http://ip-api.com/json/103.20.30.40?fields=...')
    ↓ (timeout: 3s)
Location::fromIpApiResponse($response->json())
    ↓
Store in cache (24h TTL)
    ↓
Extract countryCode property
    ↓
Return "NP"
```

**Time:** ~3-5 seconds (API call)

### Example 3: Private IP

```
GeoLocation::getCountryCode('192.168.1.1')
    ↓
GeoLocationService::getCountryCode('192.168.1.1')
    ↓
IpApiProvider::getCountryCode('192.168.1.1')
    ↓
isPrivateIp('192.168.1.1') → true
    ↓
Return null (no API call, no cache)
```

**Time:** ~0.1ms

## Dependency Injection & IoC Container

### Service Provider Registration

**File:** `GeoLocationServiceProvider.php`

```php
public function register(): void
{
    // 1. Bind interface to implementation
    $this->app->bind(GeoIpProvider::class, IpApiProvider::class);
    
    // 2. Register singleton facade accessor
    $this->app->singleton('geo-location', function ($app) {
        // Container resolves dependencies automatically
        return new GeoLocationService(
            $app->make(GeoIpProvider::class) // Resolved from binding
        );
    });
}
```

### Why Singleton?

- Same instance used throughout request
- Caching works efficiently
- Database connection reuse (if used)
- Memory efficient

### How to Swap Providers

```php
// In service provider or testing:
$this->app->bind(GeoIpProvider::class, MaxMindProvider::class);

// Now all code automatically uses MaxMindProvider!
GeoLocation::getCountryCode('...'); // Uses MaxMind
```

## Error Handling Philosophy

### Non-Critical Failures

```php
try {
    Http::timeout(3)->get(...) → timeout after 3s
    // OR network unreachable
    // OR invalid JSON response
} catch (\Exception $e) {
    \Log::warning('IP geolocation lookup failed', [
        'ip' => $ip,
        'error' => $e->getMessage(),
    ]);
    return null; // Graceful failure
}
```

**Design:** Fail open, not closed
- User experience degraded slightly (no auto-language)
- App continues functioning
- Error logged for monitoring
- Cache avoids repeated failures

### Private IP Detection

```php
private function isPrivateIp(string $ip): bool
{
    return in_array($ip, ['127.0.0.1', '::1'], strict: true)
        || str_starts_with($ip, '192.168.')
        || str_starts_with($ip, '10.')
        || str_starts_with($ip, '172.');
}
```

**Design:** Fail fast
- No API call for known-local IPs
- Saves bandwidth and latency
- Prevents API quota waste

## Cache Architecture

### Key Format
```
geo:location:{ip}
```

Enables:
- Per-IP expiration
- Selective cache clearing
- Easy debugging

### TTL: 24 Hours (86400s)

**Reasoning:**
- IP geolocation data changes slowly
- Cost-per-lookup varies by provider
- Balances freshness vs. efficiency
- Typical hit rate: 85-95%

**Calculation:**
- 1000 users/day
- 80% cache hit rate = 800 hits (free)
- 200 misses = 200 API calls/day
- At $0.0001/call = $0.02/day
- At 30 days = $0.60/month (very cheap)

## Testing Architecture

### Unit Testing Provider

```php
// Mock Http client
Http::fake([
    'ip-api.com/json/*' => Http::response([
        'status' => 'success',
        'countryCode' => 'NP',
        ...
    ]),
]);

$provider = new IpApiProvider();
$code = $provider->getCountryCode('103.20.30.40');
assertEquals('NP', $code);
```

### Integration Testing Service

```php
// Use real provider but fake Http
Http::fake([...]);

GeoLocation::shouldReceive('getCountryCode')
    ->with('103.20.30.40')
    ->andReturn('NP');

// Test code using facade
```

### See Also

See [TESTING.md](TESTING.md) for comprehensive testing guide.

## Evolution Path

### Current State (Phase 1)
- Single provider (IpApiProvider)
- Facade + Service + Provider pattern
- Cache at provider level

### Future (Phase 2)
- Add MaxMind provider
- Add database caching (slower, persistent)
- Add request tracking/analytics

### Never (Anti-Patterns)
- ❌ Hardcode API URLs
- ❌ Skip interface for "simplicity"
- ❌ HTTP calls without timeout
- ❌ No error handling

## Related Patterns

| Pattern | Where Used | Why |
|---------|-----------|-----|
| Facade | GeoLocation class | Static access, method docs |
| Service Locator | GeoLocationService | Orchestration, thin layer |
| Strategy | GeoIpProvider interface | Swappable implementations |
| Adapter | IpApiProvider | Adapt external API |
| Value Object | Location | Typed, immutable data |
| Singleton | 'geo-location' binding | Efficient caching |

## Performance Characteristics

| Operation | Time | Factors |
|-----------|------|---------|
| Private IP check | ~0.1ms | String comparison |
| Cache hit | ~1ms | Redis round-trip |
| API call | 3-5s | Network latency |
| Parsing response | ~10ms | JSON decode |

**Optimization:** Private IP check first (cheapest, ~50% of requests)
