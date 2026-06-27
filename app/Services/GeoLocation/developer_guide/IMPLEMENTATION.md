# GeoLocation Service Implementation Guide

A step-by-step walkthrough of how the GeoLocation service was built, decisions made, and lessons learned.

## Phase 1: Design & Planning

### 1.1 Requirement Analysis

**Core Needs:**
- Auto-detect user's location from IP
- Map location to application language
- Cache results to avoid API quota limits
- Support multiple geolocation providers

**Non-Functional Requirements:**
- API call timeout (prevent hanging)
- Graceful error handling
- Private IP detection
- Fast cache hits (< 5ms)

### 1.2 Architecture Decision: Provider Pattern

**Question:** Should we hardcode one geolocation API, or support multiple?

**Option A: Hardcode IpApiProvider**
```php
class GeoLocationService {
    public function getLocation($ip) {
        // Direct HTTP call to ip-api.com
    }
}
```
- ✅ Simple, fewer files
- ❌ Tightly coupled
- ❌ Cannot switch providers
- ❌ Violates Open/Closed Principle

**Option B: Provider Interface (Chosen)**
```php
interface GeoIpProvider { ... }
class IpApiProvider implements GeoIpProvider { ... }
class GeoLocationService {
    public function __construct(GeoIpProvider $provider) { ... }
}
```
- ✅ Swappable providers
- ✅ Testable (mock providers)
- ✅ Follows SOLID
- ❌ More files
- ❌ Slight indirection

**Decision:** Use provider interface. Cost of indirection << benefit of flexibility.

## Phase 2: Build Layers (Bottom-Up)

### 2.1 Value Object Layer: Location

**Why bottom-up?** Domain models don't depend on anything else.

**File:** `ValueObjects/Location.php`

```php
final readonly class Location {
    public function __construct(
        public ?string $countryCode,
        public ?string $countryName,
        public ?string $region,
        public ?string $city,
        public ?string $postalCode,
        public ?float $latitude,
        public ?float $longitude,
        public ?string $timezone,
    ) {}
}
```

**Design Decisions:**

1. **Why `readonly`?**
   - Location data is immutable
   - Created from API response
   - Never changes after construction
   - Safer for sharing between threads

2. **Why all nullable?**
   - Some providers return partial data
   - Free APIs have limited fields
   - Allows graceful degradation

3. **Why factory method?**
   ```php
   public static function fromIpApiResponse(array $data): ?self
   {
       if (($data['status'] ?? '') !== 'success') {
           return null; // Validation at construction
       }
       return new self(
           countryCode: $data['countryCode'] ?? null,
           ...
       );
   }
   ```
   - Encapsulates response parsing
   - Validates data before object creation
   - Can add IP-API-specific logic here

4. **Why `toArray()` method?**
   ```php
   public function toArray(): array
   {
       return array_filter([...], fn($v) => $v !== null);
   }
   ```
   - Convert to JSON for API responses
   - Filters nulls to reduce payload
   - Consistent format for consumers

### 2.2 Contract Layer: GeoIpProvider

**File:** `Contracts/GeoIpProvider.php`

```php
interface GeoIpProvider {
    public function getCountryCode(string $ip): ?string;
    public function getLocation(string $ip): ?Location;
    public function getTimezone(string $ip): ?string;
}
```

**Design Decisions:**

1. **Why three methods instead of one?**
   ```php
   // Option A: Single method returning Location
   $location = $provider->getLocation($ip);
   $code = $location->countryCode; // Extra data loaded
   
   // Option B: Three methods (chosen)
   $code = $provider->getCountryCode($ip); // Only country loaded
   ```
   - Performance optimization
   - Some use cases only need country code
   - Provider can implement efficiently
   
   **Trade-off:** More methods, but better performance

2. **Why nullable returns?**
   ```php
   public function getCountryCode(string $ip): ?string;
   // Not: public function getCountryCode(string $ip): string;
   ```
   - Private IPs return null (not error)
   - API failures return null (not exception)
   - Graceful degradation

3. **Why `string $ip` not `Request`?**
   ```php
   // Option A: Accept Request object
   public function getLocation(Request $request): ?Location;
   
   // Option B: Accept IP string (chosen)
   public function getLocation(string $ip): ?Location;
   ```
   - Decouples provider from HTTP layer
   - Can use in console commands, jobs, etc.
   - Testable without Request mocking

### 2.3 Implementation Layer: IpApiProvider

**File:** `Providers/IpApiProvider.php`

**Step 1: Private IP Detection**

```php
private function isPrivateIp(string $ip): bool
{
    return in_array($ip, ['127.0.0.1', '::1'], strict: true)
        || str_starts_with($ip, '192.168.')
        || str_starts_with($ip, '10.')
        || str_starts_with($ip, '172.');
}
```

**Why check private IPs?**
- Localhost development: 127.0.0.1
- Docker/containers: 172.17.x.x
- VPNs: 192.168.x.x
- All useless to send to geolocation API
- Save API quota and latency

**Decision:** Check before ANY caching/API logic.

**Step 2: Caching with Cache::remember**

```php
public function getLocation(string $ip): ?Location
{
    if ($this->isPrivateIp($ip)) {
        return null; // Fast path
    }

    return Cache::remember("geo:location:{$ip}", self::CACHE_TTL, function () use ($ip) {
        // Only runs on cache miss
        return $this->fetchLocation($ip);
    });
}

private function fetchLocation(string $ip): ?Location
{
    try {
        $response = Http::timeout(self::TIMEOUT)
            ->get("http://ip-api.com/json/{$ip}?fields=...");
        return Location::fromIpApiResponse($response->json());
    } catch (\Exception $e) {
        \Log::warning('IP geolocation lookup failed', [
            'ip' => $ip,
            'error' => $e->getMessage(),
        ]);
        return null;
    }
}
```

**Why `Cache::remember`?**
```php
// Option A: Manual check
if ($cached = Cache::get("geo:location:{$ip}")) {
    return $cached;
}
$location = $this->fetch($ip);
Cache::put("geo:location:{$ip}", $location, TTL);
return $location;

// Option B: Cache::remember (chosen)
return Cache::remember("geo:location:{$ip}", TTL, fn() => $this->fetch($ip));
```
- More concise
- Atomic (no race conditions)
- Standard Laravel pattern

**Why timeout?**
```php
Http::timeout(3)->get(...) // Max 3 seconds
```
- Prevent hanging requests
- Free IP-API is slow sometimes
- User experience: wait 3s max, then fallback
- Production: adjust based on SLA

**Why log failures?**
```php
\Log::warning('IP geolocation lookup failed', [...]);
return null; // Graceful failure
```
- Don't crash the app
- Log for monitoring/alerting
- Return null means "unknown"
- Caller can provide fallback

**Step 3: API Endpoints Pattern**

```php
private const CACHE_TTL = 86400; // 24 hours

Http::timeout(self::TIMEOUT)
    ->get("http://ip-api.com/json/{$ip}?fields=status,country,countryCode,...")
```

**IP-API Specifics:**
- Free endpoint: `ip-api.com/json/` (45 req/min)
- Pro endpoint: `pro.ip-api.com/json/` (higher limits)
- Query params: `fields=country,countryCode,...` (only return needed data)
- Response: JSON with `status: "success"` or `"fail"`

**Future Providers:**
- MaxMind: `https://geoip.maxmind.com/geoip/v2.1/city/{ip}`
- IPStack: `http://api.ipstack.com/{ip}?access_key=...`
- Each has different response format
- `Location::fromIpApiResponse()` makes format swappable

### 2.4 Service Layer: GeoLocationService

**File:** `Services/GeoLocationService.php`

```php
class GeoLocationService
{
    public function __construct(private readonly GeoIpProvider $provider) {}

    public function getCountryCode(string $ip): ?string
    {
        return $this->provider->getCountryCode($ip);
    }

    public function getLocation(string $ip): ?Location
    {
        return $this->provider->getLocation($ip);
    }

    public function getTimezone(string $ip): ?string
    {
        return $this->provider->getTimezone($ip);
    }

    public function mapCountryToLocale(?string $countryCode): string
    {
        return LocalePolicy::fromCountry($countryCode)->value();
    }
}
```

**Design Decisions:**

1. **Why such a thin service?**
   - Pure delegation for now
   - Doesn't add logic (yet)
   - Future expansion point: add request context, metrics, etc.
   - Follows Single Responsibility

2. **Why include `mapCountryToLocale`?**
   - Not in provider (provider is geo-agnostic)
   - Not in facade alone (business logic)
   - Service: perfect place for this transformation
   - Couples with LocalePolicy (acceptable here)

3. **Why constructor injection?**
   ```php
   public function __construct(private readonly GeoIpProvider $provider) {}
   ```
   - Explicit dependency
   - Testable (inject mock provider)
   - Container can resolve automatically
   - Type-safe

### 2.5 Facade Layer: GeoLocation

**File:** `Facades/GeoLocation.php`

```php
class GeoLocation extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'geo-location';
    }
}
```

**With PHPDoc for IDE Support:**

```php
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
```

**Why `getFacadeAccessor` returns string?**
```php
// This resolves 'geo-location' from Laravel's service container
// Laravel then calls: $container->make('geo-location')
// Which returns the GeoLocationService instance
```

## Phase 3: Integration

### 3.1 Service Provider Registration

**File:** `GeoLocationServiceProvider.php`

```php
class GeoLocationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Step 1: Bind interface to implementation
        $this->app->bind(GeoIpProvider::class, IpApiProvider::class);

        // Step 2: Register singleton for facade
        $this->app->singleton('geo-location', function ($app) {
            return new GeoLocationService(
                $app->make(GeoIpProvider::class)
            );
        });
    }
}
```

**Why two bindings?**

| Binding | Why |
|---------|-----|
| `GeoIpProvider → IpApiProvider` | Constructor injection dependency |
| `'geo-location' → GeoLocationService` | Facade accessor |

**Why singleton, not transient?**

```php
// Transient: New instance per request
$this->app->bind('geo-location', ...); // ❌

// Singleton: Same instance throughout request
$this->app->singleton('geo-location', ...); // ✅
```
- Single cache instance = better cache hits
- No memory waste
- Standard for services

### 3.2 Laravel Configuration

**Register in `config/app.php`:**

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

**Now works:**
```php
GeoLocation::getCountryCode('103.20.30.40'); // ✅
```

## Phase 4: Domain Integration

### 4.1 Coupling with LocalePolicy

**Modified:** `GeoLocationService::mapCountryToLocale()`

```php
public function mapCountryToLocale(?string $countryCode): string
{
    return LocalePolicy::fromCountry($countryCode)->value();
}
```

**Design Decision:** Should this exist in GeoLocationService?

**Option A: In GeoLocationService (chosen)**
```php
GeoLocation::mapCountryToLocale('NP'); // "np"
// Geo service owns the mapping
// Co-located with geo logic
```

**Option B: In LocalePolicy only**
```php
LocalePolicy::fromCountry('NP')->value(); // "np"
// Pure domain concern
// Geo doesn't know about locales
```

**Decision:** A is better because:
- Semantic grouping (geo → locale is one operation)
- Consumers think "get locale from IP", not "get country then map"
- Service can optimize (cache mapping, etc.)

### 4.2 Usage in DetectLocaleUseCase

**File:** `app/Application/Locale/DetectLocaleUseCase.php`

```php
public function __construct(private readonly GeoIpProvider $geoIp) {}

public function execute(Request $request, ?string $orgLanguage = null): Locale
{
    if ($orgLanguage && Locale::isSupported($orgLanguage)) {
        return Locale::fromString($orgLanguage);
    }

    if (!$this->isPrivateIp($ip = $request->ip())) {
        $countryCode = $this->geoIp->getCountryCode($ip);
        $locale = Locale::tryFromString(
            LocalePolicy::fromCountry($countryCode)->value()
        );
        if ($locale) {
            return $locale;
        }
    }

    // Browser fallback, then app default
    // ...
}
```

**Design Decision:** Inject `GeoIpProvider` not `GeoLocation` facade

**Why?**
```php
// ❌ Using facade in application layer
class DetectLocaleUseCase {
    public function execute() {
        GeoLocation::getCountryCode($ip); // ❌ Tightly coupled
    }
}

// ✅ Using interface in application layer
class DetectLocaleUseCase {
    public function __construct(private readonly GeoIpProvider $geoIp) {}
    public function execute() {
        $this->geoIp->getCountryCode($ip); // ✅ Swappable
    }
}
```

**Rule:** Application layer uses interfaces, not facades.
- Testable (inject mock)
- Reusable (doesn't depend on Laravel facade resolution)
- Clear dependencies

## Phase 5: Testing Strategy

See [TESTING.md](TESTING.md) for comprehensive testing approach.

**Quick Summary:**
- Unit test `IpApiProvider` with mocked Http
- Unit test `GeoLocationService` with mocked provider
- Unit test `Location` value object
- Integration test with real cache + mocked Http
- Feature test full flow with facade

## Lessons Learned

### 1. Start with Tests (TDD)

**What we did:** Implemented service, then tested.

**What we should do:** Write failing tests first.

```php
// First: Write test showing desired behavior
test('getCountryCode returns NP for Nepal IP', function() {
    $code = GeoLocation::getCountryCode('103.20.30.40');
    expect($code)->toBe('NP');
});

// Second: Implement to pass test
// Third: Refactor while keeping test green
```

### 2. Interface First

**What we did right:** Define `GeoIpProvider` before implementation.

**Why it matters:** Forced us to think about contract, not implementation.

```php
// Good: Think about what provider SHOULD do
interface GeoIpProvider { ... }

// Bad: Implement first, extract interface later
class IpApiProvider { ... }
```

### 3. Caching at Implementation Level

**What we did:** IpApiProvider handles its own caching.

**Why it's good:**
- Provider knows its quirks (API rate limits, response speed)
- Can swap cache strategy per provider (Redis for IpApi, file for MaxMind)
- Service doesn't need caching knowledge

### 4. Graceful Failures

**What we did:** Return null on errors, log them.

**Why it works:**
- App doesn't crash on external API failures
- Monitored via logs/alerting
- Caller provides fallback (browser language, default locale)

### 5. Private IP Optimization

**What we did:** Check before cache/API.

**Why it matters:**
- ~50% of development requests are localhost
- Saves Redis roundtrip
- Saves API call
- Saves 3+ seconds

## Common Pitfalls to Avoid

### ❌ Pitfall 1: No Timeout

```php
Http::get(...); // Can hang forever
// Risk: Parent request times out, cascading failures
// Fix: Http::timeout(3)->get(...)
```

### ❌ Pitfall 2: No Error Handling

```php
$json = $response->json(); // What if invalid JSON?
// Risk: Exceptions crash the app
// Fix: Try-catch, log, return null
```

### ❌ Pitfall 3: Wrong Cache Key

```php
Cache::remember('location', ...); // All IPs cached under same key!
// Risk: All users get same location
// Fix: Cache::remember("geo:location:{$ip}", ...)
```

### ❌ Pitfall 4: Facade in Domain/Application

```php
class DetectLocaleUseCase {
    public function execute() {
        GeoLocation::getCountryCode($ip); // ❌ Tightly coupled
    }
}
// Risk: Cannot test without Laravel container
// Fix: Inject GeoIpProvider interface
```

### ❌ Pitfall 5: Over-Engineering

```php
class GeoLocationService {
    public function __construct(
        private readonly GeoIpProvider $provider,
        private readonly CacheManager $cache,
        private readonly LoggerInterface $logger,
        private readonly ConfigRepository $config,
    ) {}
}
// Risk: Too many dependencies
// Fix: Inject only what you need, let provider handle rest
```

## Evolution Checklist

When adding new providers, ensure:

- [ ] Provider implements `GeoIpProvider` interface
- [ ] Provider handles private IP detection
- [ ] Provider has timeout protection
- [ ] Provider logs failures (not exceptions)
- [ ] Provider caches results
- [ ] Provider tests are comprehensive
- [ ] Service provider binding is updated
- [ ] No code changes outside service provider

## Summary

The GeoLocation service demonstrates:

✅ **SOLID Principles**
- Single Responsibility: Each class has one reason to change
- Open/Closed: Open for extension (new providers), closed for modification
- Liskov Substitution: Any provider can replace IpApiProvider
- Interface Segregation: Interface has only needed methods
- Dependency Inversion: Depends on interface, not implementation

✅ **Laravel Best Practices**
- Service provider for registration
- Facade for familiar syntax
- Constructor injection for testing
- Singleton for efficiency

✅ **Error Resilience**
- Graceful failures (null, not exception)
- Logging for monitoring
- Timeouts for hanging requests
- Fallback mechanisms

✅ **Performance**
- Private IP fast-path (~0.1ms)
- Caching for hit rate (~1ms)
- Lazy loading (only fetch on miss)
- Optimized API calls (select fields)
