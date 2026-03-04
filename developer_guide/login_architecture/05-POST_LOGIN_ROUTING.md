# 5. Post-Login Routing Flow

## The Complete Journey After Authentication

```
┌─────────────────────────────────────────────────────┐
│ 1. User submits login form                          │
│    POST /login                                       │
└────────────────────┬────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────┐
│ 2. LoginController::store() validates credentials   │
│    Auth::attempt() ← Username/password check        │
└────────────────────┬────────────────────────────────┘
                     │
        ┌────────────┴────────────┐
        │                         │
        ▼                         ▼
   ✅ SUCCESS               ❌ FAILED
        │                         │
        ▼                         ▼
   Retrieve user           ValidationException
        │                 (email not found or
        │                  password incorrect)
        ▼
┌─────────────────────────────────────────────────────┐
│ 3. Check Email Verification                         │
│    if (email_verified_at === null)                  │
└────────────────────┬────────────────────────────────┘
                     │
        ┌────────────┴────────────┐
        │                         │
        ▼                         ▼
   ✅ VERIFIED         ❌ NOT VERIFIED
        │                         │
        │                    Redirect to
        │              /auth/verify-email
        │
        ▼
┌─────────────────────────────────────────────────────┐
│ 4. DashboardResolver::resolve() determines path    │
│    (See 04-LOGIN_PRIORITY_SYSTEM.md)                │
└────────────────────┬────────────────────────────────┘
                     │
        ┌────────────┴─────────────────────┬──────────┬──────────┐
        │                                  │          │          │
        ▼                                  ▼          ▼          ▼
   Priority 1:                        Priority 3:    4-5:      Fallback
   Active Voting                      Welcome        Org/     (Cache/
                                      Page         Election   Emergency)
        │                                │            │          │
        ▼                                ▼            ▼          ▼
 /organisations/                  /dashboard/    /organisations  /dashboard
  {slug}/voting/                  welcome        {slug}/dash/    (or static)
  {id}
        │                                │            │          │
        └────────────────┬───────────────┴────────────┴──────────┘
                         │
                         ▼
         ┌──────────────────────────────────┐
         │ 5. User Redirected                │
         │    HTTP 302 Found                 │
         │    Location: [resolved URL]       │
         └──────────────────┬────────────────┘
                            │
                            ▼
         ┌──────────────────────────────────┐
         │ 6. Middleware checks on each     │
         │    subsequent request            │
         │    - EnsureOrganisationMember    │
         │    - Verify pivot exists         │
         │    - Check org_id scope          │
         └──────────────────┬────────────────┘
                            │
         ┌──────────────────┴──────────────────┐
         │                                     │
         ▼                                     ▼
   ✅ User has access                  ❌ User not member
         │                                     │
         ▼                                     ▼
    Render page                        abort(403)
    "Sie haben Zugang"         "Sie haben KEINEN Zugang"
```

## Authentication Phase (LoginController::store)

### Step 1: Validate Input
```php
$validated = $request->validate([
    'email' => 'required|string|email',
    'password' => 'required|string',
]);
```

### Step 2: Rate Limiting Check
```php
$this->ensureIsNotRateLimited($request);
// Prevents brute force: max 5 attempts per minute per IP
```

### Step 3: Check Email Exists
```php
$user = User::where('email', $validated['email'])->first();

if (!$user) {
    throw ValidationException::withMessages([
        'email' => 'auth.email_not_registered',
    ]);
}
```

### Step 4: Attempt Authentication
```php
if (!Auth::attempt($validated, $request->boolean('remember'))) {
    throw ValidationException::withMessages([
        'password' => 'auth.failed',
    ]);
}
```

### Step 5: Clear Rate Limit
```php
RateLimiter::clear($this->throttleKey($request));
```

### Step 6: Check Email Verification
```php
$user = Auth::user();

if ($user->email_verified_at === null) {
    return redirect()->route('verification.notice')
        ->with('status', 'Please verify your email...');
}
```

**Why this check?**
- New users MUST verify email before accessing any dashboard
- Prevents unverified accounts from voting
- Security requirement for election integrity

### Step 7: Route via DashboardResolver
```php
return app(DashboardResolver::class)->resolve($user);
```

## Fortify Integration (LoginResponse)

After LoginController returns, Laravel Fortify fires `LoginResponse`:

```php
// LoginResponse::toResponse()
public function toResponse(Request $request): Response
{
    $user = $request->user();

    // Verify email again (defensive)
    if ($user->email_verified_at === null) {
        return redirect()->route('verification.notice');
    }

    // Check rate limit (post-login brute force prevention)
    if (!$this->checkRateLimit($user)) {
        return redirect()->route('dashboard')
            ->with('error', 'Too many login attempts...');
    }

    // Check maintenance mode
    if ($this->isInMaintenanceMode($user)) {
        return $this->redirectToMaintenanceMode();
    }

    // LEVEL 1: Normal resolution
    try {
        return $this->resolveNormalDashboard($user);
    } catch (Throwable $e) {
        // LEVEL 2: Emergency fallback
        try {
            return $this->resolveEmergencyDashboard($user);
        } catch (Throwable $emergency) {
            // LEVEL 3: Static HTML
            return $this->resolveStaticHtmlFallback();
        }
    }
}
```

## Priority Resolution Details

### Priority 1: Active Voting Session
```php
// Check if user has unfinished voting session
$activeVoting = $user->votingSessions()
    ->where('is_completed', false)
    ->first();

if ($activeVoting) {
    return redirect()->route('voting.show', $activeVoting);
    // User can continue where they left off
}
```

### Priority 2: Onboarded Platform User
```php
if ($user->organisation_id == 1 && $user->onboarded_at !== null) {
    return redirect()->route('dashboard');
    // Main platform dashboard
}
```

### Priority 3: Non-Onboarded Platform User
```php
if ($user->organisation_id == 1 && $user->onboarded_at === null) {
    return redirect()->route('dashboard.welcome');
    // Onboarding/welcome page
}
```

### Priority 4: Tenant with Active Election
```php
if ($user->organisation_id > 1) {
    $election = Election::where('organisation_id', $user->organisation_id)
        ->where('is_active', true)
        ->first();

    if ($election) {
        return redirect()->route('election.show', $election);
        // Live election voting interface
    }
}
```

### Priority 5: Tenant Organisation Dashboard
```php
if ($user->organisation_id > 1) {
    return redirect()->route('organisation.dashboard', $organisation);
    // Organisation admin dashboard
}
```

## Middleware Checks on Subsequent Requests

### EnsureOrganisationMember Middleware

After redirect, every route is protected:

```php
// app/Http/Middleware/EnsureOrganisationMember.php
class EnsureOrganisationMember
{
    public function handle(Request $request, Closure $next)
    {
        // Extract organisation from URL: /organisations/{slug}/...
        $organisationSlug = $request->route('slug');

        if (!$organisationSlug) {
            return $next($request);  // Platform route
        }

        $organisation = Organisation::where('slug', $organisationSlug)->first();

        if (!$organisation) {
            return abort(404);  // Organisation doesn't exist
        }

        // CRITICAL CHECK: Does user belong to this organisation?
        $user = Auth::user();

        if (!$user->organisationRoles()
            ->where('organisations.id', $organisation->id)
            ->exists()) {
            return abort(403);  // User not member!
        }

        // Store in request for later use
        $request->setRouteResolver(function () use ($organisation) {
            return $organisation;
        });

        return $next($request);
    }
}
```

## The 403 Rejection

```
IF user has NO pivot for route organisation
THEN return 403 "Sie haben keinen Zugang auf diese Organisation"
```

**Example:**
```
User ID: 42
User organisation_id: 1 (platform)
Tries to access: /organisations/publicdigit

Check pivot:
SELECT * FROM user_organisation_roles
WHERE user_id = 42 AND organisation_id = 1;

Result: HAS pivot
✅ Access granted

---

User ID: 42
User organisation_id: 1 (platform)
Tries to access: /organisations/mycompany

Check pivot:
SELECT * FROM user_organisation_roles
WHERE user_id = 42 AND organisation_id = (select id from organisations where slug = 'mycompany');
Result: mycompany has id=2

SELECT * FROM user_organisation_roles
WHERE user_id = 42 AND organisation_id = 2;

Result: NO pivot
❌ Access denied (403)
```

## Cache Integration

Login redirect URLs are cached for performance:

```php
// resolveNormalDashboard() uses cache

$cacheKey = 'dashboard_resolution_' . $user->id;
$cacheTtl = 300;  // 5 minutes

// First login: cache miss
if (!$cached = Cache::get($cacheKey)) {
    $redirect = app(DashboardResolver::class)->resolve($user);
    $targetUrl = $redirect->getTargetUrl();
    Cache::put($cacheKey, $targetUrl, $cacheTtl);
}

// Subsequent logins within 5 minutes: cache hit
return redirect($cached);
```

**Why cache?**
- Prevents redundant DashboardResolver calculations
- Reduces database queries
- Speeds up frequent logins

**Cache invalidation:**
- User updates organisation_id
- User gets onboarded
- Manually cleared if needed

## What Can Go Wrong

### Problem 1: User Lands on Wrong Page
**Root Cause:** Priority logic didn't match expected condition
**Solution:** Follow [04-LOGIN_PRIORITY_SYSTEM.md](04-LOGIN_PRIORITY_SYSTEM.md#debugging-priorities)

### Problem 2: 403 on First Login
**Root Cause:** Pivot record missing
**Solution:** Follow [07-DEBUGGING_GUIDE.md](07-DEBUGGING_GUIDE.md#diagnosing-403-errors)

### Problem 3: Redirect Loop
**Root Cause:** Page keeps redirecting to itself
**Solution:** Check if welcome page is redirecting to welcome, or dashboard to dashboard

### Problem 4: Cached Wrong URL
**Root Cause:** Cache hit but organisation changed
**Solution:** Clear cache: `Cache::forget('dashboard_resolution_' . $user->id)`

## Testing the Complete Flow

```php
/** @test */
public function complete_login_flow_for_new_platform_user()
{
    // Register
    $response = $this->post('/register', [
        'firstName' => 'Test',
        'lastName' => 'User',
        'email' => 'test@example.com',
        'region' => 'Bayern',
        'password' => 'Password@123',
        'password_confirmation' => 'Password@123',
        'terms' => true,
    ]);

    $user = User::where('email', 'test@example.com')->first();

    // Verify email (simulate clicking link)
    $user->update(['email_verified_at' => now()]);

    // Login
    $response = $this->post('/login', [
        'email' => 'test@example.com',
        'password' => 'Password@123',
        'remember' => false,
    ]);

    // Should redirect to welcome (Priority 3)
    $response->assertRedirect('/dashboard/welcome');

    // User is authenticated
    $this->actingAs($user);
    $this->assertTrue(Auth::check());
}
```

---

**Related Documents:**
- [04-LOGIN_PRIORITY_SYSTEM.md](04-LOGIN_PRIORITY_SYSTEM.md) - Priority logic in detail
- [10-LOGIN_RESPONSE_DEBUGGING.md](10-LOGIN_RESPONSE_DEBUGGING.md) - LoginResponse debugging
