# 10. LoginResponse Debugging Strategy

## What is LoginResponse?

`LoginResponse` is Laravel Fortify's response handler that fires **AFTER successful authentication**. It's responsible for determining the final HTTP response (redirect or response object).

```
LoginController::store()
    ↓
Auth::attempt() ← SUCCESS
    ↓
Fortify fires LoginResponse
    ↓
resolve() method executes
    ↓
User sees redirect or fallback response
```

## File Location
```
app/Http/Responses/LoginResponse.php
```

## The 3-Level Fallback Chain

LoginResponse implements defensive programming with fallbacks:

```
┌──────────────────────────────────────┐
│ LEVEL 1: Normal Dashboard Resolution │ ← DashboardResolver
├──────────────────────────────────────┤
│ Success rate: 99.5%                  │
│ Query count: 3-5 DB queries          │
│ Cache: Enabled (1 hour TTL)          │
└────────────────┬─────────────────────┘
                 │
        ┌────────┴────────┐
        │ Exception thrown│
        └────────┬────────┘
                 ▼
┌──────────────────────────────────────┐
│ LEVEL 2: Emergency Dashboard         │ ← Reduced load
├──────────────────────────────────────┤
│ Success rate: 99%                    │
│ Query count: 1-2 DB queries          │
│ Shows: Minimal navigation            │
└────────────────┬─────────────────────┘
                 │
        ┌────────┴────────┐
        │ Exception thrown│
        └────────┬────────┘
                 ▼
┌──────────────────────────────────────┐
│ LEVEL 3: Static HTML Fallback        │ ← Database down?
├──────────────────────────────────────┤
│ Success rate: 99.9%                  │
│ Query count: 0 (no DB access)        │
│ Shows: Simple success message        │
└──────────────────────────────────────┘
```

## File Structure

```php
class LoginResponse implements FortifyLoginResponse
{
    // --- CONFIGURATION ---
    protected string $requestId;           // UUID for tracking
    protected \DateTime $startTime;        // When response created
    protected Application $app;            // Service container

    // --- PUBLIC API ---
    public function toResponse(Request $request): Response

    // --- LEVEL 1: Normal Resolution ---
    protected function resolveNormalDashboard($user): RedirectResponse
    protected function checkRateLimit($user): bool
    protected function isInMaintenanceMode($user): bool

    // --- LEVEL 2: Emergency ---
    protected function resolveEmergencyDashboard($user): RedirectResponse

    // --- LEVEL 3: Fallback ---
    protected function resolveStaticHtmlFallback(): Response

    // --- LOGGING ---
    protected function trackLoginStart($user, $request): void
    protected function trackLoginSuccess($user, $targetUrl, $resolutionLevel): void
    protected function trackCacheHit($user): void
    protected function logResolutionFailure($user, \Throwable $exception): void
    protected function logEmergencyFailure($user, \Throwable $exception): void
    protected function alertOperationsTeam($user, \Throwable $exception): void
    protected function checkPerformanceThresholds(int $durationMs): void
    protected function trackFailureCount($user): void
}
```

## Debugging LoginResponse: Step by Step

### Step 1: Identify Which Level Was Hit

```bash
# Check logs for level indicator:
grep "Using emergency dashboard fallback" storage/logs/laravel.log
# If found: LEVEL 2 was used

grep "Using static HTML fallback" storage/logs/laravel.log
# If found: LEVEL 3 was used

# No message: LEVEL 1 succeeded (normal)
```

### Step 2: Check Request ID Tracking

Every LoginResponse execution has a unique request ID:

```bash
# Find the login from a specific user:
grep "request_id" storage/logs/laravel.log | grep "user_id=42"

# Output:
# "request_id": "f8e0a1b2-c3d4-e5f6-a7b8-c9d0e1f2a3b4"

# Follow that request ID through logs:
grep "f8e0a1b2-c3d4-e5f6-a7b8-c9d0e1f2a3b4" storage/logs/laravel.log
```

### Step 3: Check Email Verification Status

```php
// LoginResponse checks email verification FIRST
if ($user->email_verified_at === null) {
    return redirect()->route('verification.notice');
}
```

**Debug:**
```php
php artisan tinker

$user = User::find(42);
echo $user->email_verified_at;  // NULL or timestamp?

// Manually verify if needed:
$user->update(['email_verified_at' => now()]);
```

### Step 4: Check Rate Limiting

```php
// LoginResponse enforces rate limit AFTER verification
protected function checkRateLimit($user): bool
{
    $key = 'login_attempts:' . $user->id . ':' . now()->format('Y-m-d-H');
    $maxAttempts = config('login-routing.rate_limiting.max_attempts', 10);
    $attempts = Cache::increment($key);

    return $attempts <= $maxAttempts;
}
```

**Debug:**
```php
php artisan tinker

$user = User::find(42);

// Check current attempt count:
$key = 'login_attempts:42:' . now()->format('Y-m-d-H');
Cache::get($key);  // Returns number or NULL

// Clear it:
Cache::forget($key);
```

### Step 5: Trace DashboardResolver Execution

```bash
# Find DashboardResolver logs:
grep "DashboardResolver returned redirect" storage/logs/laravel.log

# Output shows:
# "target_url": "/dashboard/welcome"
# "status_code": 302
# "user_id": 42

# Compare with what user actually saw by checking browser redirect
```

### Step 6: Check Cache Usage

```bash
# Find cache hit logs:
grep "Using cached dashboard URL" storage/logs/laravel.log

# Shows which users hit the cache:
# "cached_url": "/dashboard"
# "user_id": 42
```

**Clear cache for testing:**
```php
php artisan tinker

$user = User::find(42);
$cacheKey = 'dashboard_resolution_' . $user->id;

Cache::forget($cacheKey);  // Force fresh resolution on next login
```

### Step 7: Check Maintenance Mode

```bash
# Is the app in maintenance?
ls storage/framework/down  # If exists, app is down

# Check if user is in allowlist:
grep "allow_user_ids" app/config/login-routing.php

# If user is in allowlist, they can still login during maintenance
```

### Step 8: Analyze Performance Thresholds

```bash
# Find performance warnings:
grep "Login resolution took" storage/logs/laravel.log

# Output:
# "Login resolution took longer than expected"
# "duration_ms": 2500
# "threshold_ms": 2000

# If critical:
# "Login resolution took critical time"
# "duration_ms": 5500
```

**Thresholds are configured in:**
```php
// config/login-routing.php
'analytics' => [
    'performance_thresholds' => [
        'warning_ms' => 2000,
        'critical_ms' => 5000,
    ]
]
```

### Step 9: Check for High Failure Rate

```bash
# Find failure tracking:
grep "High login failure rate detected" storage/logs/laravel.log

# Shows:
# "New login failure rate: 150/hour"
# This would trigger alerts to ops team
```

## Debugging Each Level

### Level 1 Debugging (Normal Dashboard Resolution)

**Check if DashboardResolver is working:**
```php
php artisan tinker

$user = User::find(42);
$resolver = app(App\Services\DashboardResolver::class);
$response = $resolver->resolve($user);

echo "Redirect to: " . $response->getTargetUrl() . "\n";
// Expected: /dashboard, /dashboard/welcome, /organisations/..., etc.
```

**Check if cache is being used:**
```php
// First login - cache miss
// Check log: "DashboardResolver returned redirect"

// Second login immediately after - cache hit
// Check log: "Using cached dashboard URL"
```

**Check cache TTL:**
```php
// Default TTL is 300 seconds (5 minutes)
php artisan tinker

$cacheKey = 'dashboard_resolution_42';
Cache::get($cacheKey);          // Returns cached URL
Cache::getStore()->get($cacheKey, ['expiration' => true]);  // See expiration time
```

### Level 2 Debugging (Emergency Dashboard)

**When it gets used:**
- DashboardResolver throws any Exception
- Database connectivity is partial
- Some queries work, others fail

**Check logs for:**
```bash
grep "Using emergency dashboard fallback" storage/logs/laravel.log
# Shows what exception caused fallback

grep "Dashboard resolution failed" storage/logs/laravel.log
# Shows the original error before fallback attempt
```

**Reproduce:**
```php
php artisan tinker

// Temporarily break DashboardResolver:
try {
    throw new Exception("Simulated DashboardResolver error");
} catch (Throwable $e) {
    // Would trigger Level 2 fallback
}
```

### Level 3 Debugging (Static HTML Fallback)

**When it gets used:**
- Both Level 1 AND Level 2 failed
- Database is completely down
- System is in severe degradation

**Check logs:**
```bash
grep "Using static HTML fallback" storage/logs/laravel.log
# If this appears, system is severely degraded

grep "IMMEDIATE ATTENTION REQUIRED" storage/logs/critical-alerts.log
# Ops team would see this alert
```

## Common LoginResponse Issues

### Issue 1: Stuck on Email Verification

**Symptom:** Every login redirects to verification page

**Check:**
```php
php artisan tinker
$user = User::find(42);
echo $user->email_verified_at;  // NULL?
```

**Fix:**
```php
$user->update(['email_verified_at' => now()]);
```

### Issue 2: Rate Limit False Positive

**Symptom:** "Too many login attempts" on first login

**Check:**
```php
php artisan tinker

$key = 'login_attempts:42:' . now()->format('Y-m-d-H');
Cache::get($key);  // Should be 1 on first attempt
```

**Fix:**
```php
// Clear the cache key:
Cache::forget($key);

// OR disable rate limiting in testing:
// config('login-routing.rate_limiting.enabled', false)
```

### Issue 3: Wrong Redirect Despite Correct Priority

**Symptom:** DashboardResolver works but cache serves wrong URL

**Check:**
```bash
grep "Using cached dashboard URL" storage/logs/laravel.log
# Shows which URL was cached
```

**Fix:**
```php
php artisan tinker

// Clear cache for user:
Cache::forget('dashboard_resolution_42');

// Or clear all dashboard caches:
Cache::forget('dashboard_resolution_*');  // Pattern not direct
```

### Issue 4: Always Falls Back to Level 2 or 3

**Symptom:** Every login uses emergency or static fallback

**Check logs for:**
```bash
grep "exception" storage/logs/laravel.log
# Find the underlying exception
```

**Likely causes:**
- DashboardResolver throwing unhandled exception
- Database connection dropping
- Missing route configuration

## Monitoring LoginResponse Health

### Key Metrics to Track

```php
// In config/login-routing.php

'analytics' => [
    'enabled' => true,
    'channel' => 'single',
    'track_cache_metrics' => true,
    'performance_thresholds' => [
        'warning_ms' => 2000,
        'critical_ms' => 5000,
    ]
]
```

### Dashboard Metrics

Track these metrics daily:

```
1. Level 1 Success Rate: Should be 99%+
2. Cache Hit Rate: Should be 70%+ for returning users
3. Average Response Time: Should be < 200ms
4. Failure Rate: Should be < 1%
5. Emergency Fallback Count: Should be < 5/hour
```

### Alert Thresholds

Set up alerts for:

```
- Level 3 fallbacks: Even 1 per day = investigate
- Failure rate > 5/hour: Emergency
- Response time > 5000ms: Performance degradation
- Rate limit hits > 100/hour: Brute force attempt or misconfiguration
```

## Testing LoginResponse

### Unit Test

```php
// tests/Feature/Auth/LoginResponseTest.php

/** @test */
public function login_response_redirects_onboarded_user_to_dashboard()
{
    $user = User::create([...onboarded user...]);

    $response = LoginResponse::new()->toResponse(
        request()->setUserResolver(fn() => $user)
    );

    $this->assertEquals('/dashboard', $response->getTargetUrl());
}

/** @test */
public function login_response_redirects_unboarded_user_to_welcome()
{
    $user = User::create([...not onboarded...]);

    $response = LoginResponse::new()->toResponse(
        request()->setUserResolver(fn() => $user)
    );

    $this->assertEquals('/dashboard/welcome', $response->getTargetUrl());
}
```

### Integration Test

```php
/** @test */
public function complete_login_flow_hits_level_1_resolution()
{
    $user = User::create([...]);
    $user->update(['email_verified_at' => now()]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    // Should hit Level 1 (check logs)
    $this->assertNotNull($response->getSession()->get('user_id'));
}
```

## Quick Debugging Checklist

- [ ] Check which level was hit (grep logs)
- [ ] Check request ID (track through logs)
- [ ] Verify email_verified_at is set
- [ ] Check rate limit not triggered
- [ ] Verify DashboardResolver works
- [ ] Check cache status
- [ ] Verify no maintenance mode
- [ ] Check performance thresholds
- [ ] Look for exceptions in logs
- [ ] Review recent code changes

---

**Related Documents:**
- [04-LOGIN_PRIORITY_SYSTEM.md](04-LOGIN_PRIORITY_SYSTEM.md) - DashboardResolver details
- [07-DEBUGGING_GUIDE.md](07-DEBUGGING_GUIDE.md) - General debugging methodology
