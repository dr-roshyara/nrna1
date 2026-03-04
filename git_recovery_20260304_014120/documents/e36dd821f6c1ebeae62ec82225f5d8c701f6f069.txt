# 🔧 Troubleshooting Guide

## Common Issues & Solutions

---

## Authentication Issues

### Issue 1: "Method Not Allowed - The GET method is not supported for route logout"

**Symptoms**:
- User clicks logout and sees error
- Error mentions "GET method"
- Frontend redirects instead of logout working

**Root Cause**:
Frontend using `window.location.href` instead of POST request

**Solution**:
```javascript
// ❌ WRONG:
logout() {
    window.location.href = '/login';
}

// ✅ CORRECT:
logout() {
    this.$inertia.post(route('logout'), {}, {
        onSuccess: () => {
            localStorage.clear();
        }
    });
}
```

**Where to Fix**:
- `resources/js/Components/Jetstream/PublicDigitHeader.vue`
- `resources/js/Components/Jetstream/NrnaHeader.vue`
- Any custom header components

**Test**:
```bash
php artisan test tests/Feature/Auth/LogoutTest.php
```

---

### Issue 2: "Email verification notice displays instead of dashboard"

**Symptoms**:
- Verified users see email verification page
- Should see dashboard instead
- Happens after email verification

**Root Cause**:
- Email verification check too aggressive
- Or `onboarded_at` is NULL

**Solution**:

Check if email was actually verified:
```php
// In tinker
$user = User::find(1);
echo $user->email_verified_at;  // Should not be NULL
echo $user->onboarded_at;       // May be NULL for first-timers
```

If `email_verified_at` is NULL:
```php
// Force verification (DEV ONLY)
$user->email_verified_at = now();
$user->save();
```

**Verify Fix**:
```bash
# Run tests
php artisan test tests/Feature/Auth/VerifiedMiddlewareTest.php

# Check in browser: login → should see dashboard or welcome page
```

---

### Issue 3: "Unverified users can access protected routes"

**Symptoms**:
- Unverified users reach dashboard
- Should be blocked at /email/verify
- Middleware not enforced

**Root Cause**:
- Middleware not added to route
- Or middleware removed by mistake

**Solution**:

Check routes/web.php:
```php
// ❌ WRONG: Missing 'verified'
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', ...);
});

// ✅ CORRECT: Has 'verified'
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', ...);
});
```

Check all protected routes:
```bash
php artisan route:list | grep -E "(dashboard|organisations)" | grep -v verified
# Should return no results
```

**Verify Fix**:
```bash
php artisan test tests/Feature/Auth/VerifiedMiddlewareTest.php::test_unverified_user_gets_redirect_to_verification
```

---

### Issue 4: "500 error when accessing dashboard"

**Symptoms**:
- Dashboard returns 500 error
- Not a redirect loop
- Error in ElectionController

**Root Cause**:
- Missing data in database
- Unhandled exception in ElectionController::dashboard()
- User in inconsistent state

**Solution**:

Check logs:
```bash
tail -f storage/logs/laravel.log | grep "500\|Exception"
```

Common causes:
1. **Missing election data**
   ```php
   // Check if elections exist
   $user = User::find(1);
   $user->elections();  // Should return results
   ```

2. **Missing voting_ip column**
   ```bash
   php artisan migrate
   ```

3. **Organisation doesn't exist**
   ```php
   $user = User::find(1);
   $user->organisation;  // Should not be null
   ```

**Fix**:
```php
// In tinker
$user = User::find(1);

// Ensure organisation exists
if (!$user->organisation_id) {
    $org = Organisation::find(1);
    $user->organisation_id = $org->id;
    $user->save();
}

// Ensure verified
if (!$user->email_verified_at) {
    $user->email_verified_at = now();
    $user->save();
}

// Ensure onboarded
if (!$user->onboarded_at) {
    $user->onboarded_at = now();
    $user->save();
}
```

---

## Dashboard Routing Issues

### Issue 5: "User routed to wrong dashboard"

**Symptoms**:
- Admin sees voter dashboard
- Voter sees admin dashboard
- Role detection broken

**Root Cause**:
- Incorrect roles in database
- DashboardResolver logic error
- Multiple conflicting roles

**Solution**:

Check user's actual roles:
```php
$user = User::find(1);

// New system roles
$roles = DB::table('user_organisation_roles')
    ->where('user_id', $user->id)
    ->get();

echo $roles;

// Check commission
$commissions = DB::table('election_commission_members')
    ->where('user_id', $user->id)
    ->count();

echo "Commissions: " . $commissions;

// Check legacy roles
$user->roles()->get();
```

Fix roles if needed:
```php
// Remove wrong role
DB::table('user_organisation_roles')
    ->where('user_id', $user->id)
    ->where('role', 'admin')
    ->delete();

// Add correct role
DB::table('user_organisation_roles')->insert([
    'user_id' => $user->id,
    'organisation_id' => 1,
    'role' => 'member',
    'assigned_at' => now(),
]);
```

**Verify**:
```bash
php artisan test tests/Unit/Services/DashboardResolverRoleTest.php
```

---

### Issue 6: "Platform members incorrectly routed as admins"

**Symptoms**:
- Platform organisation members see admin dashboard
- Should see welcome/regular dashboard
- Platform org (id=1) members getting special treatment

**Root Cause**:
- DashboardResolver not excluding platform org

**Solution**:

Check getDashboardRoles() in DashboardResolver.php:
```php
// ✅ CORRECT: Excludes platform org (id=1)
$adminRoleExists = DB::table('user_organisation_roles')
    ->where('user_id', $user->id)
    ->where('role', 'admin')
    ->whereNot('organisation_id', 1)  // Excludes platform!
    ->exists();
```

If missing the `whereNot` clause, add it:
```php
// Line in getDashboardRoles():
->whereNot('organisation_id', 1)
```

**Verify**:
```bash
php artisan test tests/Unit/Services/DashboardResolverRoleTest.php::test_platform_member_does_not_get_admin_role
```

---

## Email Verification Issues

### Issue 7: "Verification link expired"

**Symptoms**:
- User clicks verification link
- Gets "Link expired" or "Invalid signature"
- Need to resend

**Root Cause**:
- Link TTL expired (default: 60 minutes)
- Link was modified
- User email changed

**Solution**:

Check link validity:
```php
// Verification URLs are signed with hash
// If signature doesn't match current email, it fails

$user = User::find(1);
// If email changed since link was sent, old link won't work
```

User should click "Resend Email":
```
POST /email/verification-notification
```

Or manually verify (DEV ONLY):
```php
$user = User::find(1);
$user->email_verified_at = now();
$user->save();
```

---

### Issue 8: "Verification email not received"

**Symptoms**:
- User registers but doesn't get email
- Email provider issue
- Configuration problem

**Root Cause**:
- Mail driver not configured
- SMTP credentials wrong
- Email provider rejecting messages

**Solution**:

Check mail configuration:
```php
// In tinker
config('mail.default');  // Should be 'smtp' or 'sendmail'
config('mail.mailers.smtp.host');
config('mail.mailers.smtp.port');
```

Test email sending:
```bash
php artisan tinker
>>> Mail::raw('Test email', function($m) { $m->to('test@example.com'); });
>>> exit
```

Check logs:
```bash
tail -f storage/logs/laravel.log | grep -i "mail\|email"
```

For development (MAILHOG):
```bash
docker-compose up mailhog  # Or use Laravel Herd
# Then check http://localhost:1025
```

---

## Session & Cache Issues

### Issue 9: "User logged out but session still active"

**Symptoms**:
- User logs out
- Can still access protected routes
- Session not properly invalidated

**Root Cause**:
- Logout didn't POST to server
- Session cache not cleared
- CSRF token issue

**Solution**:

Verify logout endpoint is called:
```javascript
// Check Network tab in DevTools
// POST /logout should appear

// If not, logout is using wrong method:
// ❌ WRONG:
window.location.href = '/login';

// ✅ CORRECT:
this.$inertia.post(route('logout'));
```

Manually clear sessions:
```bash
php artisan session:clear
```

Clear cache:
```bash
php artisan cache:clear
```

**Verify**:
```bash
php artisan test tests/Feature/Auth/LogoutTest.php
```

---

### Issue 10: "Dashboard resolution cached incorrectly"

**Symptoms**:
- User gets wrong dashboard after role change
- Cache not invalidated
- Old routing persists

**Root Cause**:
- Cache not invalidated on role change
- Session freshness check failing
- Stale cache entry

**Solution**:

Clear cache:
```bash
php artisan cache:clear
```

Or manually:
```php
// In tinker
Cache::forget('login_routing:' . $user_id);
```

Check cache config:
```php
// config/login-routing.php
'cache' => [
    'dashboard_resolution_ttl' => 300,  // 5 minutes
],
'session' => [
    'validate_freshness' => true,
    'freshness_threshold' => 60,  // seconds
]
```

If cache issues persist, disable it (SLOW):
```php
// config/login-routing.php
'cache' => [
    'dashboard_resolution_ttl' => 0,  // Disabled
]
```

---

## Database Issues

### Issue 11: "Columns missing after migration"

**Symptoms**:
- Getting "unknown column" errors
- Migration didn't run
- Fresh install missing tables

**Root Cause**:
- Migration not run
- Migration failed
- Wrong database targeted

**Solution**:

Run migrations:
```bash
php artisan migrate
```

If already migrated:
```bash
php artisan migrate:refresh  # Dev only! Clears data
```

Check migration status:
```bash
php artisan migrate:status
```

If specific migration failed:
```bash
php artisan migrate:rollback
php artisan migrate
```

Check columns exist:
```php
// In tinker
Schema::hasColumn('users', 'email_verified_at');  // Should be true
Schema::hasColumn('users', 'onboarded_at');       // Should be true
```

---

### Issue 12: "Unique constraint violation"

**Symptoms**:
- "UNIQUE constraint failed"
- Duplicate email or code
- Cannot create user/record

**Root Cause**:
- Duplicate entry exists
- Migration created constraint
- Test data conflict

**Solution**:

Find duplicates:
```php
// Find duplicate emails
DB::table('users')
    ->select('email', DB::raw('count(*) as count'))
    ->groupBy('email')
    ->having('count', '>', 1)
    ->get();
```

Remove duplicates (DEV):
```php
// Keep one, delete others
$toDelete = DB::table('users')
    ->where('email', 'duplicate@example.com')
    ->where('id', '!=', $firstId)
    ->delete();
```

Or use fresh database:
```bash
php artisan migrate:refresh --seed
```

---

## Testing Issues

### Issue 13: "Tests failing locally but passing in CI"

**Symptoms**:
- Test passes on CI/CD
- Test fails locally
- Database state different

**Root Cause**:
- Local database has state
- CI uses fresh database
- Cache from previous runs

**Solution**:

Clear local database:
```bash
php artisan migrate:refresh
```

Clear cache:
```bash
php artisan cache:clear
php artisan config:clear
```

Run tests:
```bash
php artisan test
```

Or use fresh database in tests:
```php
class TestCase extends BaseTestCase {
    use RefreshDatabase;  // Clears between tests
}
```

---

### Issue 14: "Test timeout errors"

**Symptoms**:
- "Test took longer than 30 seconds"
- Slow database queries
- Deadlock

**Root Cause**:
- Test making real database queries
- Inefficient queries in setUp
- Missing indexes

**Solution**:

Optimize test setup:
```php
protected function setUp(): void {
    parent::setUp();
    // Avoid expensive operations
    // Use factories instead of real operations
}
```

Use in-memory SQLite:
```php
// config/database.php (test environment)
'sqlite' => [
    'driver' => 'sqlite',
    'database' => ':memory:',
]
```

Run tests without cache:
```bash
php artisan test --parallel
```

---

## Performance Issues

### Issue 15: "Dashboard loads slowly"

**Symptoms**:
- Dashboard takes >2 seconds to load
- DashboardResolver is slow
- Multiple database queries

**Root Cause**:
- Missing database indexes
- N+1 queries in DashboardResolver
- Cache miss

**Solution**:

Check query performance:
```php
// In tinker
$start = microtime(true);
$user = User::find(1);
DashboardResolver::resolve($user);
echo microtime(true) - $start;
```

Enable query logging:
```php
// In tinker or config
DB::enableQueryLog();
$user = User::find(1);
DashboardResolver::resolve($user);
dd(DB::getQueryLog());
```

Add missing indexes:
```php
// In migration
$table->index('organisation_id');
$table->index(['user_id', 'organisation_id']);
```

Enable caching:
```php
// config/login-routing.php
'cache' => [
    'dashboard_resolution_ttl' => 300,  // Cache for 5 min
]
```

---

## Quick Diagnostic Commands

```bash
# Check all registered routes
php artisan route:list

# Check middleware configuration
php artisan route:list | grep middleware

# Check database schema
php artisan tinker
>>> Schema::getColumns('users');

# Check migrations status
php artisan migrate:status

# Clear all caches
php artisan cache:clear && php artisan config:clear

# Run all authentication tests
php artisan test tests/Feature/Auth/

# Run with verbose output
php artisan test --verbose

# Run specific test
php artisan test --filter test_name

# Run tests in parallel
php artisan test --parallel
```

---

## Getting Help

### If Issue Not Listed Here

1. **Check Logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Run Tests**
   ```bash
   php artisan test tests/Feature/Auth/
   ```

3. **Check Database**
   ```php
   // In tinker
   $user = User::find(1);
   echo $user->email_verified_at;
   ```

4. **Consult Documentation**
   - `01_LOGIN_RESPONSE_ARCHITECTURE.md`
   - `02_DASHBOARD_RESOLVER_ARCHITECTURE.md`
   - `03_AUTHENTICATION_FLOW.md`
   - `05_SECURITY_GUIDELINES.md`

5. **Contact Development Team**
   - Include full error message
   - Include steps to reproduce
   - Include local environment (PHP version, Laravel version, etc.)

---

## Related Documentation

- **Overview**: See `00_OVERVIEW.md`
- **LoginResponse**: See `01_LOGIN_RESPONSE_ARCHITECTURE.md`
- **DashboardResolver**: See `02_DASHBOARD_RESOLVER_ARCHITECTURE.md`
- **Authentication Flow**: See `03_AUTHENTICATION_FLOW.md`
- **Test Suite**: See `04_TEST_SUITE_GUIDE.md`
- **Security**: See `05_SECURITY_GUIDELINES.md`

---

**Last Updated**: 2026-03-03
**Status**: Production Ready
**Maintenance**: Update with new issues as they arise
