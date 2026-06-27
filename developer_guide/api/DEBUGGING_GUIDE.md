# API Debugging Guide

This guide documents all the issues encountered while building the API architecture and the debugging strategies used to resolve them.

---

## Issue #1: HTML Response Instead of JSON (302 Redirect)

### Symptom
```
Status: 302 Found
Content-Type: text/html
Location: /login
Response Body: <html><!DOCTYPE html>...Inertia Vue page...</html>
```

API endpoint returning HTML Inertia page instead of JSON response.

### Root Cause
**Inertia middleware was intercepting ALL requests**, including API requests meant to return JSON.

The routing structure had API routes defined in `routes/web.php`, which meant they went through the web middleware stack:

```php
// ❌ WRONG: Routes in web.php
Route::post('/api/governance/committees/{id}/members', [Controller::class, 'store']);

// Execution flow:
// web.php → web middleware → HandleInertiaRequests → 
// Response intercepted → Vue page rendered as HTML
```

### Investigation Steps

1. **Checked Network Tab**
   - Status: 302 Found
   - Response headers showed: `Content-Type: text/html`
   - Body showed Inertia Vue app (not JSON)

2. **Checked Route List**
   ```bash
   php artisan route:list | grep -i members
   ```
   - Found routes registered with web middleware
   - Saw `HandleInertiaRequests` in middleware list

3. **Checked Bootstrap Config**
   - Saw routes registered from `web.php`
   - No separate API routing pipeline

### Solution

**Separate API routes from web routes using `then()` callback in bootstrap/app.php:**

```php
// bootstrap/app.php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        then: function () {
            // Internal API Routes (v1) — Separated from Inertia web pipeline
            // These routes bypass HandleInertiaRequests middleware
            \Illuminate\Support\Facades\Route::middleware(['web', 'json.api', 'tenant.api', 'auth', 'verified'])
                ->prefix('api/v1')
                ->name('api.v1.')
                ->group(base_path('routes/api_v1.php'));  // ✅ Separate file
        }
    )
```

**New routing structure:**

```
routes/web.php
  ↓
web middleware stack (with HandleInertiaRequests)
  ↓
Inertia pages

routes/api_v1.php
  ↓
api middleware stack (without HandleInertiaRequests)
  ↓
JSON responses
```

### Verification

```bash
# Clear caches
php artisan route:clear

# Check routes
php artisan route:list | grep api/v1

# Test endpoint
curl -X GET http://localhost:8000/api/v1/test-auth \
  -H "Accept: application/json"

# Should return JSON, not HTML
# Status 401 (not authenticated) is OK
# Status 302 with HTML is NOT OK
```

### Key Learning
**Inertia is middleware-aware.** If your route goes through `HandleInertiaRequests`, it will render HTML. If it doesn't, it returns data as-is. The `then()` callback in withRouting is the key to separating API routes from web routes.

---

## Issue #2: X-Organisation-ID Header Required Error

### Symptom
```json
{
  "error": "X-Organisation-ID header is required for API requests.",
  "code": "MISSING_TENANT_CONTEXT"
}
```

API rejects request even though header appears to be sent.

### Root Cause (Initially)
**Header sent with wrong case** — HTTP headers are case-insensitive in theory, but Laravel's `$request->header()` has specific lookup rules.

### Investigation Steps

1. **Opened DevTools Network Tab**
   - Checked Request Headers sent by Vue component
   - Saw: `x-organisation-id: a1ca231c...` (lowercase)

2. **Checked IdentifyTenantFromHeader Middleware**
   ```php
   $organisationId = $request->header('X-Organisation-ID')
                   ?? $request->header('x-organisation-id');
   ```
   - Already handles both cases ✓

3. **Added Logging**
   ```php
   \Log::info('Headers received:', $request->header());
   \Log::info('Tenant header value:', $request->header('X-Organisation-ID'));
   ```

4. **Checked Browser Console**
   ```javascript
   [handleAddMember] Tenant ID: null
   [handleAddMember] NO TENANT ID FOUND
   ```
   - Vue component couldn't resolve tenant ID!

### Root Cause (Actual)
**TenantContext was resolving to null** because:

```javascript
// CommitteeMemberManager.vue
const effectiveTenantId = computed(() => {
  if (props.tenantId) return props.tenantId;
  if (props.organisationId) return props.organisationId;
  if (page.props.organisation?.id) return page.props.organisation.id;
  if (page.props.auth?.user?.current_organisation_id) return page.props.auth.user.current_organisation_id;
  
  console.warn('NO TENANT ID FOUND');  // ← This was executing
  return null;
});
```

None of these properties were set, so `effectiveTenantId` was null.

### Solution

**Pass tenant ID explicitly to Vue component:**

```php
// In your Inertia controller
return Inertia::render('CommitteeManagement', [
    'organisationId' => auth()->user()->current_organisation_id,
    'committeeId' => $committee->id,
]);
```

**Or extract from URL if possible:**

```javascript
const effectiveOrganisationSlug = computed(() => {
  const path = window.location.pathname;
  const match = path.match(/\/organisations\/([^\/]+)/);
  return match ? match[1] : null;
});
```

### Verification

Add debugging to component:

```javascript
console.log('[handleAddMember] Headers being sent:', {
    'X-Organisation-ID': effectiveTenantId.value,
    'X-Requested-With': 'XMLHttpRequest',
    'Accept': 'application/json'
});
```

Check middleware logs:

```bash
tail -f storage/logs/laravel.log | grep "IdentifyTenant"
```

### Key Learning
**Don't assume data is available.** Tenant ID must be:
1. Available in Vue props from Inertia
2. Derived from URL path
3. Stored in session/localStorage
4. Or explicitly passed to API

Choose one method and stick with it.

---

## Issue #3: CSRF Token Validation on POST /api/v1/*

### Symptom
```json
{
  "message": "CSRF token mismatch.",
  "exception": "TokenMismatchException"
}
Status: 419
```

POST requests to API failing with CSRF error, but response headers show it's trying to render HTML error.

### Root Cause
**API routes were part of the web middleware group**, which includes CSRF validation. But API clients send `X-Requested-With: XMLHttpRequest` header instead of CSRF tokens.

```php
// middleware stack: ['web', ..., VerifyCsrfToken, ...]
// ↓
// Incoming POST /api/v1/...
// ↓
// VerifyCsrfToken middleware checks request
// ↓
// No CSRF token found, no X-Requested-With header → 419 error
```

### Investigation Steps

1. **Checked Response Headers**
   - Status: 419
   - Content-Type: text/html (not JSON!)
   - Body was HTML error page (Inertia rendered)

2. **Checked Exception Handler**
   - Saw default Laravel CSRF exception handling
   - Was returning HTML instead of JSON

3. **Checked Middleware Stack**
   ```bash
   php artisan route:list --verbose | grep api/v1
   ```
   - Saw: `web, session, csrf, auth, ...`
   - CSRF middleware was running

### Solution

**Exempt API routes from CSRF validation in bootstrap/app.php:**

```php
->withMiddleware(function (Middleware $middleware) {
    // Exempt /api/v1/* routes from CSRF validation
    // These routes use X-Requested-With header (AJAX), not CSRF tokens
    $middleware->validateCsrfTokens(except: [
        'api/v1/*',
    ]);
})
```

**Also ensure exception handler returns JSON for API errors:**

```php
// In exception handler
$exceptions->shouldRenderJsonWhen(function ($request, $e) {
    if ($request->is('api/v1/*')) {
        return true;  // Always JSON for API routes
    }
    return $request->expectsJson();
});
```

### Verification

```bash
# Test POST without CSRF token
curl -X POST http://localhost:8000/api/v1/test-post-debug \
  -H "Accept: application/json" \
  -H "X-Requested-With: XMLHttpRequest" \
  -H "X-Organisation-ID: org-id" \
  -b "PHPSESSID=session-id"

# Should NOT return 419 error
# Should return 200 or 401 (auth error OK, CSRF error NOT OK)
```

### Key Learning
**CSRF protection is important, but API clients can't send CSRF tokens.** Solutions:
1. Exempt API routes from CSRF (if using session auth)
2. Use stateless auth (JWT tokens, OAuth)
3. Send CSRF token in headers if using session auth

---

## Issue #4: Middleware Ordering - Tenant Context After Auth

### Symptom
```
Status: 401 Unauthenticated
Error: "Unauthenticated."
```

Auth middleware rejecting request before tenant context is set.

### Root Cause
**Middleware executed in wrong order:**

```php
// ❌ WRONG ORDER
->middleware(['web', 'json.api', 'auth', 'verified', 'tenant.api'])

// Execution:
// 1. auth middleware runs → checks if user authenticated
// 2. But TenantContext not set yet
// 3. Some listeners might try to access TenantContext
// 4. Returns 401 before tenant.api middleware runs
```

### Investigation Steps

1. **Checked Middleware Stack in bootstrap/app.php**
   - Found auth came before tenant.api

2. **Added Logging to TenantContext**
   ```php
   public static function get(): ?string
   {
       \Log::info('TenantContext::get() called', ['value' => self::$tenantId]);
       return self::$tenantId;
   }
   ```

3. **Checked Logs**
   - Logs showed null values

4. **Traced Middleware Execution**
   ```bash
   php artisan middleware:list | grep -E "(auth|tenant)"
   ```

### Solution

**Reorder middleware to set tenant context BEFORE auth checks:**

```php
// ✅ CORRECT ORDER
->middleware(['web', 'json.api', 'tenant.api', 'auth', 'verified'])
//                                 ↑ tenant before auth

// Execution:
// 1. json.api: Force JSON responses
// 2. tenant.api: Extract and validate tenant → set TenantContext
// 3. auth: Check authentication (can access TenantContext if needed)
// 4. verified: Check email verified
```

### Verification

Add logging to middleware:

```php
// In IdentifyTenantFromHeader middleware
public function handle(Request $request, Closure $next): Response
{
    \Log::info('[tenant.api] Before - TenantContext:', ['value' => TenantContext::get()]);
    
    $organisationId = $request->header('X-Organisation-ID');
    TenantContext::set($organisationId);
    
    \Log::info('[tenant.api] After - TenantContext:', ['value' => TenantContext::get()]);
    
    return $next($request);
}
```

Check logs:

```bash
tail -f storage/logs/laravel.log | grep "tenant.api"
```

### Key Learning
**Middleware order matters.** General rule:
1. Infrastructure setup (session, cookies)
2. Context setting (tenant, locale)
3. Validation (auth, permissions)
4. Business logic handlers

---

## Issue #5: Route Prefix Duplication

### Symptom
```
Status: 404 Not Found
```

Valid routes returning 404, even though they're registered.

### Root Cause
**Nested prefix groups created duplicate prefixes:**

```php
// bootstrap/app.php
->prefix('api/v1')  // Sets prefix

// routes/api_v1.php
Route::prefix('api/v1')->group(function () {  // ❌ Nested prefix!
    Route::post('/governance/committees/{id}/members', ...);
});

// Result: /api/v1/api/v1/governance/committees/{id}/members
```

### Investigation Steps

1. **Checked Route List**
   ```bash
   php artisan route:list | grep members
   ```
   - Showed: `/api/v1/governance/committees/{id}/members`
   - Looked correct, but...

2. **Tested in Browser**
   ```javascript
   fetch('/api/v1/governance/committees/123/members')
   // → 404 Not Found
   ```

3. **Checked Verbose Route List**
   ```bash
   php artisan route:list --verbose | grep governance
   ```
   - Still showed correct path
   - But route wasn't matching!

4. **Checked Route File**
   - Found nested prefix in routes/api_v1.php

### Solution

**Remove nested prefix from routes/api_v1.php:**

```php
// ❌ BEFORE (nested)
Route::prefix('api/v1')->group(function () {
    Route::post('/governance/committees/{id}/members', ...);
});

// ✅ AFTER (direct)
Route::post('/governance/committees/{id}/members', ...);
Route::post('/governance/committees/{id}/members', ...);
Route::delete('/governance/committees/{id}/members/{memberId}', ...);
```

**Why?**
- bootstrap/app.php already applies the `api/v1` prefix globally
- routes/api_v1.php should contain only the path AFTER the prefix
- Nesting creates `/api/v1/api/v1/...`

### Verification

```bash
php artisan route:clear
php artisan route:list | grep -E "POST.*members"

# Should show: api/v1/governance/committees/{committeeId}/members
# NOT: api/v1/api/v1/governance/...

# Test in browser
curl -X GET http://localhost:8000/api/v1/test-auth \
  -H "Accept: application/json"

# Should return 401 (auth required)
# NOT 404
```

### Key Learning
**Prefix handling in Laravel:**
- `->prefix()` in bootstrap's `withRouting()` applies to all routes in that group
- routes/api_v1.php is already inside that group
- Don't nest prefixes - they concatenate!

---

## Issue #6: Member ID vs User ID Mismatch

### Symptom
```json
{
  "error": "User not found",
  "status": 404
}
```

Search returns members, but adding them fails with "User not found".

### Root Cause
**Two different ID systems:**
1. **Member model** - Governance domain entity
2. **User model** - Authentication entity

Search returns **Member IDs**, but controller expects **User IDs**.

```php
// Search returns:
[{ id: 'e7deab61...', name: 'Krishna', email: '...' }]
// This is a Member ID!

// Controller tries:
$user = User::find('e7deab61...');  // ❌ User doesn't exist
```

### Investigation Steps

1. **Checked Error Message**
   - "User not found" from controller

2. **Checked Search Code**
   ```php
   $members = Member::where('organisation_id', $org)
       ->where('name', 'ILIKE', "%$q%")
       ->get();
   ```
   - Returns Member model, ID is Member ID

3. **Checked Controller**
   ```php
   $user = User::find($memberId);  // ❌ Wrong model!
   ```

4. **Checked Database**
   ```sql
   SELECT id FROM members WHERE id = 'e7deab61...';  -- ✓ Exists
   SELECT id FROM users WHERE id = 'e7deab61...';    -- ✗ Doesn't exist
   ```

### Solution

**Resolve Member ID to User ID through relationship:**

```php
// In CommitteeMemberController::store()

// Step 1: Load Member (not User)
$member = Member::withoutGlobalScopes()
    ->where('id', $memberId)
    ->where('organisation_id', $tenantId)
    ->first();

if (!$member) {
    return response()->json(['error' => 'Member not found'], 404);
}

// Step 2: Get User through relationship
$user = $member->user();  // Has-one-through relationship

if (!$user) {
    return response()->json(['error' => 'User not found'], 404);
}

// Step 3: Use User in domain logic
$committee->addMember(
    MemberId::fromString($member->id),  // Still use Member ID for domain
    CommitteeRole::from($role)
);
```

### Database Structure

```
users table
  ↓
  id (UUID)

organisation_users table
  ↓
  user_id → users.id
  organisation_id

members table
  ↓
  id (UUID)
  organisation_user_id → organisation_users.id
  organisation_id
  personal_info (JSON)
```

### Verification

```bash
# Check Member exists
php artisan tinker
>>> Member::find('e7deab61...')->name
=> "Krishna Sharma"

# Check User relationship
>>> $member = Member::find('e7deab61...');
>>> $member->user()->name
=> "Krishna Sharma"

# Try adding member again
# Should succeed now
```

### Key Learning
**Model IDs must be consistent.** When searching:
1. Be clear which model you're returning
2. Document the ID type in API responses
3. Convert between IDs if needed at boundaries

---

## Issue #7: Database Column Name Mismatch

### Symptom
```sql
SQLSTATE[42703]: Undefined column: 7 ERROR: column "tenant_id" 
does not exist in "member_directories" table
```

SQL query fails because column has different name than expected.

### Root Cause
**Code uses `tenant_id` but table has `organisation_id`:**

```php
// CommitteeMemberProjectionListener.php
$member = DB::table('member_directories')
    ->where('member_id', $memberId)
    ->where('tenant_id', $memberId)  // ❌ Column doesn't exist
    ->first();

// Database actually has:
// member_directories table:
// - id
// - member_id
// - organisation_id  ← Not tenant_id!
// - display_name
// - email
```

### Investigation Steps

1. **Checked Error**
   - SQL error: column "tenant_id" doesn't exist

2. **Checked Table Schema**
   ```bash
   php artisan tinker
   >>> DB::select("SELECT * FROM information_schema.columns WHERE table_name='member_directories'");
   
   // Output shows columns: id, member_id, organisation_id, display_name, email
   // NO tenant_id column!
   ```

3. **Grepped for Usage**
   ```bash
   grep -r "tenant_id" app/Contexts/Governance/Infrastructure/
   
   // Found in CommitteeMemberProjectionListener.php
   ```

4. **Checked Recent Changes**
   - Code was written expecting `tenant_id`
   - But migrations created `organisation_id`

### Solution

**Change query to use correct column name:**

```php
// CommitteeMemberProjectionListener.php
public function onMemberAssigned(MemberAssignedToCommittee $event): void
{
    // Change tenant_id to organisation_id
    $member = DB::table('member_directories')
        ->where('member_id', $event->memberId->value())
        ->where('organisation_id', $event->tenantId->value())  // ✅ Correct column
        ->first(['display_name', 'email']);
    
    // ... rest of code
}
```

### Prevention

1. **Define column names early** - Create a schema reference document
2. **Use constants** - Avoid hardcoding column names
3. **Check migrations first** - Before writing queries
4. **Add tests** - Query tests would catch this immediately

### Verification

```bash
php artisan cache:clear

# Try adding member again
# Should complete successfully now
```

### Key Learning
**Naming consistency matters.** Use one term:
- Either `tenant_id` everywhere
- Or `organisation_id` everywhere
- Not mixed in different tables/code

---

## Debugging Checklist

When API endpoint isn't working:

### 1. Check Route Matching
```bash
# Is the route registered?
php artisan route:list | grep "your-route"

# Are prefixes correct?
php artisan route:list --verbose | grep "your-route"
```

### 2. Check Response Type
```bash
# Is it JSON or HTML?
curl -i http://localhost:8000/api/v1/your-endpoint

# Check Content-Type header
# Should be: application/json
# Not: text/html
```

### 3. Check Middleware Execution
```bash
# Add temporary logging
\Log::info('Middleware check', ['tenant' => TenantContext::get()]);

# Check logs
tail -f storage/logs/laravel.log
```

### 4. Check Headers
```bash
# Open DevTools → Network tab
# Click your request
# Go to "Request Headers"
# Verify X-Organisation-ID is there
```

### 5. Check Controller Code
```bash
# Is controller being called?
# Add dd() or Log to first line
dd('Controller reached');

# Does it return JSON?
return response()->json(['test' => 'ok']);
```

### 6. Check Domain Logic
```bash
# Is business rule failing?
try {
    $aggregate->doSomething();
} catch (\DomainException $e) {
    \Log::error('Domain error', ['message' => $e->getMessage()]);
    throw;
}
```

### 7. Check Database
```bash
# Does data actually exist?
php artisan tinker
>>> Model::find('id')->first()

# Is it in right organisation?
>>> Model::where('organisation_id', 'org-id')->count()
```

### 8. Check Cache
```bash
# Clear all caches
php artisan cache:clear
php artisan route:clear
php artisan config:clear

# Sometimes cache causes phantom issues
```

---

## Debugging Tools

### 1. Laravel Telescope (Development)
```php
// Install
composer require laravel/telescope --dev

// Run migrations
php artisan telescope:install

// Access at: http://localhost:8000/telescope
// See all requests, SQL queries, logs
```

### 2. Logging
```php
\Log::info('Message', ['context' => 'data']);

// Check logs
tail -f storage/logs/laravel.log

// Filter logs
tail -f storage/logs/laravel.log | grep "keyword"
```

### 3. Browser DevTools
```javascript
// Network tab: See all requests, responses, headers
// Console: See client-side errors
// Storage: See cookies, localStorage

// Log everything
console.log('Message', { data });
console.error('Error', error);
```

### 4. Database Logs
```bash
# Enable query logging
DB::listen(function ($query) {
    \Log::info('SQL', ['sql' => $query->sql, 'bindings' => $query->bindings]);
});
```

---

## Common Error Messages & Fixes

| Error | Likely Cause | Fix |
|-------|-------------|-----|
| `404 Not Found` | Route not found or has wrong prefix | Check `php artisan route:list` |
| `401 Unauthenticated` | Not logged in or middleware order wrong | Check session cookies and middleware order |
| `403 Unauthorized` | Permission denied or tenant mismatch | Check user's organisations, X-Organisation-ID header |
| `419 Token Mismatch` | CSRF validation failed | Exempt API routes or send CSRF token |
| `500 Internal Server Error` | Unhandled exception | Check logs in `storage/logs/laravel.log` |
| `Column doesn't exist` | Query references wrong column name | Check table schema: `PRAGMA table_info(table_name)` |
| `Call to undefined method` | Wrong class name or import | Check imports and class names |
| `Property undefined` | Accessing non-existent property | Check model relationships and casts |

---

## Performance Debugging

### Slow Queries
```bash
# Enable query logging
DB::listen(function ($query) {
    if ($query->time > 100) {  // Queries over 100ms
        \Log::warning('Slow query', ['sql' => $query->sql, 'time' => $query->time]);
    }
});
```

### Memory Usage
```php
// Log memory before/after operation
$before = memory_get_usage(true);
// ... do something ...
$after = memory_get_usage(true);
\Log::info('Memory', ['before' => $before, 'after' => $after, 'diff' => $after - $before]);
```

### Request Time
```php
// Log request duration
$start = microtime(true);
// ... handle request ...
$duration = microtime(true) - $start;
\Log::info('Request took', ['seconds' => $duration]);
```

---

**Next:** Read [BEST_PRACTICES.md](./BEST_PRACTICES.md) for development guidelines.
