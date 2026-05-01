# Design Decisions & Trade-offs

Documentation of key architectural decisions, alternatives considered, and why we chose what we did.

## Decision 1: Provider Interface Pattern

### Context
Need to support multiple geolocation providers (ip-api.com, MaxMind, IPStack, etc.)

### Options

**A. Hardcode Single Provider (Rejected)**
```php
class GeoLocationService {
    public function getLocation($ip) {
        $response = Http::get('ip-api.com/...');
        return Location::fromIpApiResponse($response->json());
    }
}
```
- ✅ Simple, fewer files
- ❌ Tightly coupled to ip-api.com
- ❌ Cannot swap providers without code change
- ❌ Testing difficult (must mock Http or use real API)

**B. Provider Interface (Selected)**
```php
interface GeoIpProvider {
    public function getLocation(string $ip): ?Location;
}

class IpApiProvider implements GeoIpProvider { ... }
class MaxMindProvider implements GeoIpProvider { ... }
```
- ✅ Swappable providers (change one line in service provider)
- ✅ Testable (inject mock provider)
- ✅ Follows Open/Closed principle
- ✅ Future-proof
- ❌ More files, slight indirection

**C. Abstract Base Class (Rejected)**
```php
abstract class BaseGeoIpProvider {
    abstract protected function fetchLocation($ip);
    public function getLocation($ip) {
        return Cache::remember(..., fn() => $this->fetchLocation($ip));
    }
}
```
- ✅ Share caching logic
- ❌ Inheritance (composition preferred)
- ❌ Can only inherit from one class

### Decision: B (Provider Interface)
- Cost of indirection (one extra layer) << benefit of flexibility
- Required now for DetectLocaleUseCase (uses GeoIpProvider interface)
- Enables future provider additions without touching service layer
- Industry standard pattern (Strategy Pattern)

---

## Decision 2: Where to Cache?

### Context
Multiple layers could implement caching: Service, Provider, or Facade

### Options

**A. Cache at Facade Level (Rejected)**
```php
class GeoLocation extends Facade {
    public static function getLocation($ip) {
        return Cache::remember("geo:location:{$ip}", ..., fn() => 
            self::getFacadeRoot()->getLocation($ip)
        );
    }
}
```
- ❌ Duplicates caching logic across facade methods
- ❌ Facade becomes thick (wrong responsibility)
- ❌ Cannot reuse cache in non-facade code

**B. Cache at Service Level (Rejected)**
```php
class GeoLocationService {
    public function getLocation($ip) {
        return Cache::remember("geo:location:{$ip}", ..., fn() =>
            $this->provider->getLocation($ip)
        );
    }
}
```
- ❌ Service shouldn't know about cache (not its job)
- ❌ Cannot implement provider-specific cache strategies
- ❌ Violates Single Responsibility

**C. Cache at Provider Level (Selected)**
```php
class IpApiProvider {
    public function getLocation($ip) {
        return Cache::remember("geo:location:{$ip}", ..., fn() =>
            $this->fetchAndParse($ip)
        );
    }
}
```
- ✅ Provider owns its data freshness strategy
- ✅ Can implement provider-specific caching (Redis for IpApi, file for MaxMind)
- ✅ Cache logic close to data fetch
- ✅ Service and Facade stay thin

### Decision: C (Cache at Provider Level)
- Provider knows its API's characteristics (rate limits, response speed)
- Each provider can optimize independently
- Service layer stays focused on orchestration only
- Clear separation of concerns

---

## Decision 3: Method Granularity

### Context
Provider should have how many methods? One or three?

### Options

**A. Single Method Returning Location (Rejected)**
```php
interface GeoIpProvider {
    public function getLocation(string $ip): ?Location;
}

// Usage
$location = GeoLocation::getLocation($ip);
$code = $location->countryCode;  // Extra data loaded
```
- ✅ Simple interface (one method)
- ❌ Loads full Location even if only country code needed
- ❌ Wastes API quota for partially-used data
- ❌ Performance: Must hydrate Location object always

**B. Three Methods (Selected)**
```php
interface GeoIpProvider {
    public function getCountryCode(string $ip): ?string;
    public function getLocation(string $ip): ?Location;
    public function getTimezone(string $ip): ?string;
}
```
- ✅ Performance optimization (get only what you need)
- ✅ API providers can optimize queries
- ✅ Matches real-world use case patterns
- ✅ Lazy loading possible
- ❌ More methods to implement

**C. Many Specialized Methods (Rejected)**
```php
interface GeoIpProvider {
    public function getCountryCode($ip): ?string;
    public function getTimezone($ip): ?string;
    public function getCity($ip): ?string;
    public function getCoordinates($ip): ?array;
    public function getRegion($ip): ?string;
    // ... more methods
}
```
- ❌ Interface becomes bloated
- ❌ Violates Interface Segregation principle
- ❌ Difficult to implement correctly

### Decision: B (Three Methods)
- Balances simplicity (single method) with performance (three methods)
- Most common use cases covered
- Can add more methods if needed
- Provider can implement efficiently (e.g., IpApiProvider uses same cache for all)

---

## Decision 4: Return Type: Exception vs Null

### Context
How should provider handle errors (API down, timeout, invalid response)?

### Options

**A. Throw Exception (Rejected)**
```php
public function getLocation(string $ip): ?Location {
    try {
        $response = Http::get(...);
        return Location::from($response);
    } catch (\Exception $e) {
        throw new GeoLocationException('Failed to get location', 0, $e);
    }
}

// Caller must handle
try {
    $location = GeoLocation::getLocation($ip);
} catch (GeoLocationException $e) {
    // Fallback
}
```
- ✅ Explicit error handling
- ❌ Breaks flow (caller must always try-catch)
- ❌ Non-critical failure becomes app-stopping
- ❌ Bad for optional features (geo-detection is nice-to-have)

**B. Return Null (Selected)**
```php
public function getLocation(string $ip): ?Location {
    try {
        // ...
    } catch (\Exception $e) {
        \Log::warning('...', ['error' => $e->getMessage()]);
        return null;
    }
}

// Caller can ignore
$location = GeoLocation::getLocation($ip);
$locale = $location?->countryCode ?? 'en'; // Fallback
```
- ✅ Graceful degradation (app continues)
- ✅ Non-blocking (don't break other features)
- ✅ Simple caller code (no try-catch needed)
- ✅ Logged for monitoring
- ✅ Matches "fail open, log it" philosophy

### Decision: B (Return Null)
- Geo-detection is enhancement, not requirement
- User experience better: slightly slower (no auto-language) vs. broken (exception)
- Still logged for alerts/monitoring
- Follows Laravel philosophy (graceful failures)

---

## Decision 5: Singleton vs Transient

### Context
How often should GeoLocationService be instantiated?

### Options

**A. Transient (New instance per request)**
```php
$this->app->bind('geo-location', function ($app) {
    return new GeoLocationService($app->make(GeoIpProvider::class));
});
```
- ✅ Fresh state per request
- ❌ New HTTP client per call
- ❌ Worse cache locality
- ❌ Wastes memory if called multiple times
- ❌ Standard practice is singleton for services

**B. Singleton (One instance per application lifetime) (Selected)**
```php
$this->app->singleton('geo-location', function ($app) {
    return new GeoLocationService($app->make(GeoIpProvider::class));
});
```
- ✅ Single cache instance per request
- ✅ Better memory efficiency
- ✅ Better performance (connection reuse if applicable)
- ✅ Industry standard for services
- ✅ Thread-safe in Laravel (one thread per request)

### Decision: B (Singleton)
- Laravel architecture: one singleton per request
- No shared state between requests (new container per request anyway)
- Standard practice for all services
- Performance optimization (connection reuse)

---

## Decision 6: Private IP Detection Position

### Context
When should we detect private IPs? Early or late?

### Options

**A. Late Detection (In each method)**
```php
public function getLocation($ip) {
    // ... API call logic ...
    
    if ($this->isPrivateIp($ip)) {  // Check AFTER attempt?
        return null;
    }
}
```
- ❌ Wastes time, might make API call first
- ❌ Wrong order (should check first)

**B. Early Detection (Before cache/API) (Selected)**
```php
public function getLocation($ip): ?Location
{
    if ($this->isPrivateIp($ip)) {  // Check FIRST
        return null;
    }
    
    return Cache::remember(..., fn() => $this->fetch($ip));
}
```
- ✅ Fail fast (0.1ms vs 3-5s)
- ✅ Save cache roundtrip
- ✅ Save API quota
- ✅ Save bandwidth

### Decision: B (Early Detection)
- ~50% of development requests are localhost (127.0.0.1)
- Biggest performance win
- Zero downside
- Should be first check always

---

## Decision 7: Facade vs Direct Service Injection

### Context
How should consumers access GeoLocation?

### Options

**A. Facade Only (Rejected)**
```php
// app/Http/Controllers/LocationController.php
use App\Services\GeoLocation\Facades\GeoLocation;

public function detect(Request $request) {
    $code = GeoLocation::getCountryCode($request->ip());
}
```
- ✅ Familiar Laravel syntax
- ✅ Simple for controllers
- ❌ Application layer should not use facades
- ❌ Non-testable in application services without Laravel container

**B. Interface Injection Only (Rejected)**
```php
// Everywhere need GeoLocation
class LocationDetector {
    public function __construct(private readonly GeoIpProvider $geoIp) {}
}

class DetectLocaleUseCase {
    public function __construct(private readonly GeoIpProvider $geoIp) {}
}
```
- ✅ Application layer can use
- ✅ Testable
- ❌ Controllers must also inject (verbose)
- ❌ Inconsistent with Laravel conventions

**C. Both (Selected)**
- Controllers use Facade: `GeoLocation::getCountryCode($ip)`
- Application layer uses Interface: `GeoIpProvider` constructor injection
- Facade internally uses interface (consistent implementation)

```php
// In controller (Infrastructure layer)
class LocationController {
    public function detect(Request $request) {
        GeoLocation::getCountryCode($request->ip());
    }
}

// In use case (Application layer)
class DetectLocaleUseCase {
    public function __construct(private readonly GeoIpProvider $geoIp) {}
    
    public function execute() {
        $this->geoIp->getCountryCode($ip);
    }
}
```

### Decision: C (Both)
- Respects layer boundaries (infrastructure vs application)
- Facade for convenience in controllers (Infrastructure layer)
- Interface injection for application layer logic
- Single implementation (no duplication)

---

## Decision 8: Cache TTL Duration

### Context
How long should we cache geolocation data? 1 hour? 24 hours? Forever?

### Options

**A. Short TTL (1 hour) (Rejected)**
```php
private const CACHE_TTL = 3600; // 1 hour
```
- ✅ More current data (user might move)
- ❌ Many cache misses
- ❌ More API calls
- ❌ Higher API costs
- ❌ Worse performance

**B. Medium TTL (24 hours) (Selected)**
```php
private const CACHE_TTL = 86400; // 24 hours
```
- ✅ Good balance of freshness vs efficiency
- ✅ IP-to-location mapping rarely changes
- ✅ Reduces API calls ~24x
- ✅ Reduces cost ~24x
- ✅ Industry standard

**C. Long TTL (30 days) (Rejected)**
```php
private const CACHE_TTL = 2592000; // 30 days
```
- ✅ Minimal API calls
- ❌ Stale data for long time
- ❌ User might have moved

**D. No Cache (Rejected)**
- ❌ Every request = API call
- ❌ Rate limits exceeded (free tier = 45/min)
- ❌ 3-5s latency every request
- ❌ Expensive

### Decision: B (24 hours)
- IP-to-location mappings change slowly (migrations rare)
- Good cost-to-freshness ratio
- Typical hit rate: 85-95% (most users repeat visit)
- Industry standard
- Can increase for paid tiers (lower cost)

---

## Decision 9: Timeout Duration

### Context
How long should we wait for geolocation API? 1s? 3s? 30s?

### Options

**A. No Timeout (Rejected)**
```php
Http::get(...); // Can hang forever
```
- ❌ Parent request might timeout
- ❌ Cascading failures
- ❌ Resource exhaustion

**B. Very Short (1 second) (Rejected)**
```php
Http::timeout(1)->get(...);
```
- ❌ Frequently times out (API sometimes slow)
- ❌ High failure rate
- ❌ Poor user experience

**C. Short (3 seconds) (Selected)**
```php
Http::timeout(3)->get(...);
```
- ✅ Reasonable wait time for user
- ✅ Prevents hanging
- ✅ Most API calls complete < 500ms
- ✅ Failures logged, fallback works
- ✅ Industry standard

**D. Long (30 seconds) (Rejected)**
```php
Http::timeout(30)->get(...);
```
- ❌ User waits too long
- ❌ Parent request might timeout
- ❌ Bad UX

### Decision: C (3 seconds)
- Balances reliability with user experience
- Free API response time typically 100-500ms
- 3s prevents hanging, allows slow API
- Common timeout for external services
- Can be configured per provider

---

## Decision 10: Value Object for Location

### Context
Should Location data be array or value object?

### Options

**A. Array (Rejected)**
```php
public function getLocation($ip): ?array {
    return [
        'country_code' => 'NP',
        'country_name' => 'Nepal',
        'city' => 'Kathmandu',
        'timezone' => 'Asia/Kathmandu',
    ];
}
```
- ❌ No type safety (`$location['typo']` fails at runtime)
- ❌ No IDE autocomplete
- ❌ No validation
- ❌ Can be modified unexpectedly

**B. stdClass (Rejected)**
```php
$location = (object) [
    'countryCode' => 'NP',
    'countryName' => 'Nepal',
];
```
- ❌ Minimal type safety
- ❌ IDE autocomplete partial
- ❌ Still mutable

**C. Value Object (Selected)**
```php
final readonly class Location {
    public function __construct(
        public readonly ?string $countryCode,
        public readonly ?string $countryName,
        // ...
    ) {}
}
```
- ✅ Type safety (IDE knows all properties)
- ✅ Immutable (readonly)
- ✅ Validation in constructor
- ✅ Methods for transformation (toArray)
- ✅ Domain-driven design

### Decision: C (Value Object)
- Type safety and IDE support critical for good DX
- Immutable prevents accidental changes
- Standard in modern PHP (7.4+ readonly)
- Enables clean domain modeling
- Easy to add methods (toString, toArray, etc.)

---

## Decision 11: Location::fromIpApiResponse vs Direct Parsing

### Context
Should we parse API response in provider or factory?

### Options

**A. Parse in Provider (Rejected)**
```php
class IpApiProvider {
    public function getLocation($ip) {
        $response = Http::get(...)->json();
        
        return new Location(
            countryCode: $response['countryCode'],
            countryName: $response['country'],
            // ... more parsing
        );
    }
}
```
- ❌ Provider knows Location class details
- ❌ Cannot reuse parser
- ❌ Cannot test parsing separately

**B. Factory Method (Selected)**
```php
class IpApiProvider {
    public function getLocation($ip) {
        $response = Http::get(...)->json();
        return Location::fromIpApiResponse($response);
    }
}

// In Location class
public static function fromIpApiResponse(array $data): ?self {
    if (($data['status'] ?? '') !== 'success') {
        return null;
    }
    return new self(
        countryCode: $data['countryCode'] ?? null,
        countryName: $data['country'] ?? null,
        // ...
    );
}
```
- ✅ Parsing logic in one place (Location)
- ✅ Can be tested independently
- ✅ Validation in factory
- ✅ Encapsulation (Location owns its construction)

### Decision: B (Factory Method)
- Single Responsibility: Location knows how to construct from IP-API response
- Easy to test parser independently
- Enables multiple parsers: `fromIpApiResponse()`, `fromMaxMindResponse()`, etc.
- Cleaner provider code
- Reusable parsing logic

---

## Decision 12: Nullable Fields in Location

### Context
Should all fields be nullable, or only some?

### Options

**A. All Nullable (Selected)**
```php
final readonly class Location {
    public function __construct(
        public ?string $countryCode,      // nullable
        public ?string $countryName,      // nullable
        public ?string $region,           // nullable
        public ?float $latitude,          // nullable
        // ...
    ) {}
}
```
- ✅ Accommodates limited providers (free APIs)
- ✅ Graceful degradation
- ✅ Flexible

**B. Some Required (Rejected)**
```php
public function __construct(
    public string $countryCode,     // required
    public ?string $region,         // optional
) {}
```
- ❌ Some providers can't provide country code
- ❌ Breaks for limited APIs
- ❌ Stricter but less practical

### Decision: A (All Nullable)
- Accommodate free and paid APIs
- Some providers return subset of fields
- Better for graceful degradation
- Still type-safe (nullable is explicit)

---

## Decision 13: toArray() vs Direct JSON Serialization

### Context
How to convert Location to JSON for API responses?

### Options

**A. Implement JsonSerializable (Rejected)**
```php
class Location implements JsonSerializable {
    public function jsonSerialize(): array {
        return [...];
    }
}

// Usage: json_encode($location) works automatically
```
- ✅ Automatic with json_encode
- ❌ Doesn't filter nulls easily
- ❌ Less explicit

**B. Manual toArray() (Selected)**
```php
public function toArray(): array {
    return array_filter([...], fn($v) => $v !== null);
}

// Usage in controller
return response()->json($location->toArray());
```
- ✅ Explicit transformation
- ✅ Can filter nulls (cleaner JSON)
- ✅ Can use snake_case
- ✅ Testable

### Decision: B (toArray with filtering)
- More explicit and controlled
- Removes null values (cleaner API response)
- Can transform keys (camelCase → snake_case)
- Laravel convention
- Testable

---

## Summary of Trade-offs

| Decision | Selected | Why |
|----------|----------|-----|
| Architecture | Provider Interface | Future-proof, testable |
| Caching | At Provider Level | Each provider can optimize |
| Method Count | 3 Methods | Performance + simplicity |
| Errors | Return Null | Graceful degradation |
| Lifecycle | Singleton | Standard, efficient |
| IP Check | Early | Fast path optimization |
| Consumer API | Both Facade + Interface | Respects layer boundaries |
| Cache TTL | 24 hours | Balance freshness vs. efficiency |
| Timeout | 3 seconds | Balance UX with reliability |
| Data Type | Value Object | Type safety, immutable |
| Parsing | Factory Method | Testable, reusable |
| Nullable | All fields | Graceful degradation |
| JSON | toArray() | Explicit, filtered |

---

## Would We Do It Different?

### ✅ Decisions We'd Make Again
- Provider interface (enables extensibility)
- Early private IP check (huge performance win)
- Factory methods for parsing (testable, reusable)
- Value objects for Location (type safety)
- Graceful error handling (fail open, log it)

### ⚠️ Decisions Worth Revisiting
- **Cache location**: Could move to service layer later if providers become stateful
- **Singleton vs Transient**: Fine now but revisit if request-scoped state needed
- **Timeout**: Should be configurable per provider

### ❌ Mistakes to Avoid Next Time
- Don't hardcode API details (we did this right with providers)
- Don't skip error handling (we handled this well)
- Don't forget monitoring/logging (we included this)

---

## Future Decisions

### When Adding New Provider
- Use same factory pattern: `Location::fromProviderResponse()`
- Implement all three methods from interface
- Add comprehensive tests (see TESTING.md)
- Update service provider binding only

### When Performance Matters
- Consider request-scoped cache (Redis per minute)
- Consider batch requests if provider supports
- Consider local database fallback (MaxMind)

### When Cost Becomes Issue
- Switch to paid provider (MaxMind, GeoIP2)
- Or increase cache TTL (30 days instead of 24 hours)
- Or implement two-tier caching (Redis + database)

---

## References

- Design Patterns: Strategy (providers), Factory (Location parsing), Facade (static access)
- SOLID Principles: Everything designed to follow them
- Laravel Conventions: Service provider, facade, injection patterns
