# Troubleshooting Guide

## Common Issues & Solutions

---

## ISSUE 1: "Undefined Route: dashboard"

### Symptom
```
InvalidArgumentException: Route [dashboard] not defined.
```

### Cause
Route `dashboard` doesn't exist in `routes/web.php`

### Solution

Check routes are registered:

```bash
php artisan route:list | grep dashboard
```

Expected output:
```
GET|HEAD   /dashboard                           dashboard
GET|HEAD   /dashboard/welcome                   dashboard.welcome
GET|HEAD   /dashboard/roles                     role.selection
GET|HEAD   /dashboard/admin                     admin.dashboard
GET|HEAD   /dashboard/commission                commission.dashboard
GET|HEAD   /vote                                vote.dashboard
```

If missing, add to `routes/web.php`:

```php
Route::middleware(['web', 'auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/dashboard/welcome', [WelcomeController::class, 'index'])
        ->name('dashboard.welcome');

    // ... other dashboard routes
});
```

---

## ISSUE 2: User Gets Stuck in Redirect Loop

### Symptom
```
Browser shows: "Too many redirects" or "Redirect loop detected"
```

### Cause
DashboardResolver is redirecting to a route that redirects back

### Solution

**Step 1:** Check DashboardResolver logic

```php
// In app/Services/DashboardResolver.php
public function resolve(User $user): RedirectResponse
{
    // If this redirects to /dashboard...
    return redirect()->route('dashboard');
}

// And /dashboard calls DashboardResolver again...
// → Infinite loop!
```

**Step 2:** Verify route priority

Check that higher priorities redirect to DIFFERENT routes:

```
PRIORITY 1 → /vote/{slug}           ✓ Different
PRIORITY 2 → /election/dashboard    ✓ Different
PRIORITY 3 → /dashboard/welcome     ✓ Different
PRIORITY 4 → /dashboard/roles       ✓ Different
PRIORITY 5 → /dashboard/admin       ✓ Different
PRIORITY 6 → /dashboard             ✓ This is the endpoint
```

**Step 3:** Check middleware isn't calling DashboardResolver twice

```php
// routes/web.php - WRONG (causes loop)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(DashboardResolver::class);  // Don't use as middleware!

// routes/web.php - RIGHT
Route::get('/dashboard', [DashboardController::class, 'index']);

// Only call DashboardResolver in LoginResponse
// app/Http/Responses/LoginResponse.php
public function toResponse($request)
{
    $resolver = app(DashboardResolver::class);
    return $resolver->resolve($request->user());
}
```

---

## ISSUE 3: "419 Page Expired" on Form Submission

### Symptom
```
TokenMismatchException
Session has expired or cookies disabled.
```

### Causes

**Cause A:** CSRF token missing from form

```html
<!-- WRONG - No CSRF token -->
<form method="POST" action="/election/update">
    <input type="text" name="title">
</form>

<!-- RIGHT - Include CSRF token -->
<form method="POST" action="/election/update">
    @csrf  <!-- Laravel blade directive -->
    <input type="text" name="title">
</form>
```

**Cause B:** Session expired (default 120 minutes)

Check `config/session.php`:
```php
'lifetime' => 120,  // 120 minutes
```

**Cause C:** Cookie settings too strict

Check cookie flags in `config/session.php`:
```php
'secure' => env('SESSION_SECURE_COOKIES', true),  // Only HTTPS?
'http_only' => true,                              // No JavaScript access?
'same_site' => 'lax',                             // CSRF protection?
```

### Solution

**For Development (localhost):**
```php
// .env
SESSION_SECURE_COOKIES=false  // Allow HTTP in development
```

**For Production:**
```php
// .env
SESSION_SECURE_COOKIES=true   // Require HTTPS
APP_URL=https://example.com   // HTTPS URL
```

**For Forms on Inertia Pages:**
```javascript
import { router } from '@inertiajs/vue3';

// Use Inertia router (auto-includes CSRF)
router.post('/endpoint', data, {
    onSuccess: () => { /* handle success */ },
    onError: (errors) => { /* handle errors */ }
});

// NOT raw fetch (which doesn't include CSRF)
```

---

## ISSUE 4: Email Verification Not Working

### Symptom
```
User clicks verification link, gets "Invalid signature" error
```

### Cause
Link is older than 24 hours (default expiration)

### Solution

**For Development:**
Extend expiration in `config/auth.php`:
```php
'verification' => [
    'expire' => 7 * 24 * 60,  // 7 days instead of 24 hours
],
```

**For Users:**
Send new verification email:
```bash
# User can request new email
php artisan tinker
$user = User::find(1);
$user->sendEmailVerificationNotification();
```

---

## ISSUE 5: User Can't Login

### Symptom
```
Login form submitted, redirects back to login
```

### Causes

**Cause A:** Wrong credentials
```
Email/password combination doesn't exist
→ Check user record in database
```

**Cause B:** Account disabled/deactivated
```
Check users table for deleted_at column (soft delete)
User::where('email', 'test@example.com')->withTrashed()->first();
```

**Cause C:** Too many login attempts (rate limited)
```
Fortify rate limits login attempts
Default: 5 attempts per minute per IP address
```

### Solution

```bash
# Check user exists
php artisan tinker
$user = User::where('email', 'test@example.com')->first();
dd($user);

# Check password
$user = User::find(1);
Hash::check('password', $user->password);  # Should be true

# Check not soft-deleted
$user = User::where('email', 'test@example.com')
    ->withTrashed()
    ->first();
dd($user->deleted_at);  # Should be NULL

# Reset password if forgotten
$user->update(['password' => Hash::make('newpassword')]);
```

---

## ISSUE 6: "The user does not have required role"

### Symptom
```
ErrorException: User doesn't have role 'admin'
```

### Cause
User not assigned to organisation with admin role

### Solution

**Check user's roles:**
```bash
php artisan tinker

$user = User::find(1);

# Check organisation roles
DB::table('user_organisation_roles')
    ->where('user_id', $user->id)
    ->get();
# Should show: user_id, organisation_id, role

# Check roles exist
$user->roles()->get();  # Legacy Spatie roles
```

**Assign user as admin:**
```bash
php artisan tinker

$user = User::find(1);
$org = Organisation::find(1);

DB::table('user_organisation_roles')->insert([
    'user_id' => $user->id,
    'organisation_id' => $org->id,
    'role' => 'admin',
    'assigned_at' => now(),
]);
```

---

## ISSUE 7: Active Election Not Showing

### Symptom
```
User should see voting interface, but doesn't
Election says "not active" or "not started yet"
```

### Cause
Election status or dates are wrong

### Solution

```bash
php artisan tinker

$election = Election::find(1);

# Check status
dd($election->status);  # Should be 'active'

# Check dates (must be NOW between start and end)
dd([
    'start' => $election->start_date,
    'now' => now(),
    'end' => $election->end_date,
]);

# Should be: start <= now <= end

# Fix election dates
$election->update([
    'start_date' => now()->subDay(),
    'end_date' => now()->addDay(),
]);

# Fix election status
$election->update(['status' => 'active']);
```

---

## ISSUE 8: Dashboard Route Returns 404

### Symptom
```
GET /dashboard returns 404 Not Found
```

### Cause
Route not registered or middleware rejecting

### Solution

**Step 1:** Verify route exists
```bash
php artisan route:list | grep dashboard
```

**Step 2:** Check middleware stack

```php
// routes/web.php should have:
Route::get('/dashboard', ...)
    ->middleware('web', 'auth', 'verified');
```

**Step 3:** Check user is authenticated

```php
// If middleware says auth failed:
// 1. User not logged in → redirect to /login
// 2. Session expired → log in again
// 3. Remember me cookie invalid → clear cookies
```

**Step 4:** Check email verified

```php
// If middleware says verified failed:
$user = User::find(1);
dd($user->email_verified_at);  # Should not be NULL
```

---

## ISSUE 9: Tests Failing with "Unknown column"

### Symptom
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'code1_used_at'
```

### Cause
Test data using non-existent database column

### Solution

**Step 1:** Check actual table schema

```bash
php artisan migrate:status
php artisan tinker

Schema::getColumnListing('voter_slugs');
```

**Step 2:** Update test data to use actual columns

```php
// WRONG - Column doesn't exist
DB::table('voter_slugs')->insert([
    'code1_used_at' => now(),
]);

// RIGHT - Use actual columns
DB::table('voter_slugs')->insert([
    'is_active' => true,
    'current_step' => 2,
    'expires_at' => now()->addDay(),
]);
```

**Step 3:** Run tests again

```bash
php artisan test tests/Feature/Auth/DashboardResolverPriorityTest
```

---

## ISSUE 10: "REMOTE_ADDR not defined"

### Symptom
```
ErrorException: Undefined array key "REMOTE_ADDR" in ElectionController.php:294
```

### Cause
Controller trying to access `$_SERVER['REMOTE_ADDR']` which doesn't exist in test environment

### Solution

**Use Laravel request helper instead:**

```php
// WRONG - Doesn't work in tests
$ip = $_SERVER['REMOTE_ADDR'];

// RIGHT - Works everywhere
$ip = $request->ip();

// Or
$ip = request()->ip();
```

**In controller:**
```php
public function dashboard(Request $request)
{
    $userIp = $request->ip();  # Correct method

    // Log the IP
    Log::info('User accessed dashboard', ['ip' => $userIp]);
}
```

---

## ISSUE 11: Organisation Context Not Set

### Symptom
```
session('current_organisation_id') returns NULL
```

### Cause
TenantContext middleware not running

### Solution

**Verify middleware is registered in routes:**

```php
// routes/web.php
Route::prefix('/{organisation}')
    ->middleware(['web', 'auth', 'verified', 'tenant-context'])
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
    });
```

**Check middleware file exists:**
```bash
ls app/Http/Middleware/TenantContext.php
```

**Verify middleware runs:**
```php
// app/Http/Middleware/TenantContext.php
public function handle($request, $next)
{
    // Add debug logging
    Log::info('TenantContext running', [
        'organisation' => $request->route('organisation'),
    ]);

    // Set session
    session(['current_organisation_id' => $organisation->id]);

    return $next($request);
}
```

---

## Debugging Commands

### PHPUnit Tests

```bash
# Run specific test file
php artisan test tests/Feature/Auth/DashboardResolverPriorityTest

# Run single test
php artisan test tests/Feature/Auth/DashboardResolverPriorityTest::priority_1_active_voting_session_redirects_to_voting_portal

# Run with verbose output
php artisan test tests/Feature/Auth/DashboardResolverPriorityTest -vv

# Run with filter
php artisan test tests/Feature/Auth/DashboardResolverPriorityTest --filter="priority_1"
```

### Database

```bash
php artisan tinker

# Check user
$user = User::find(1);

# Check organisation
$org = Organisation::find(1);

# Check election
$election = Election::find(1);

# Check voter slug
$slug = DB::table('voter_slugs')->where('user_id', 1)->first();

# Check all routes
php artisan route:list

# Check database
php artisan db:seed  # Seed test data
```

### Logs

```bash
# Tail logs in real-time
tail -f storage/logs/laravel.log

# Search logs
grep "DashboardResolver" storage/logs/laravel.log

# Clear old logs
rm storage/logs/laravel*.log
```

---

## Quick Checklist

If something is broken:

- [ ] Check error message carefully
- [ ] Search this troubleshooting guide
- [ ] Check Laravel documentation
- [ ] Run tests: `php artisan test`
- [ ] Check logs: `tail -f storage/logs/laravel.log`
- [ ] Clear cache: `php artisan cache:clear`
- [ ] Run migrations: `php artisan migrate`
- [ ] Check database: `php artisan tinker`
- [ ] Restart PHP server: `php artisan serve`

---

**Last Updated:** March 4, 2026
