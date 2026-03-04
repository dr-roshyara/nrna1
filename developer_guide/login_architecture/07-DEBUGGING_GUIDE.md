# 7. Debugging Guide - Step by Step

## Overview

This guide provides systematic debugging methodology for login-related issues. Follow these steps in order.

## 1. Identify the Symptom

### Common Symptoms

| Symptom | Indicator | Next Step |
|---------|-----------|-----------|
| **403 Error** | "Sie haben keinen Zugang auf diese Organisation" | Section 2 |
| **Wrong Redirect** | User goes to wrong dashboard | Section 3 |
| **Stuck on Welcome** | Always redirects to welcome page | Section 4 |
| **Internal Server Error** | 500 error on login | Section 5 |
| **Pivot Issues** | Database constraint errors | Section 6 |
| **Slow Login** | Login takes > 5 seconds | Section 7 |

## 2. Diagnosing 403 Errors

### Step 2.1: Check the Middleware
```bash
# Where is the 403 coming from?
grep -r "EnsureOrganisationMember" app/Http/Middleware/
```

**Key File:** `app/Http/Middleware/EnsureOrganisationMember.php`

The middleware checks:
```php
if (!$user->organisationRoles()
    ->where('organisations.id', $organisationId)
    ->exists()) {
    abort(403);
}
```

### Step 2.2: Verify User Has Access
```php
// Check in Laravel tinker:
php artisan tinker

$user = User::find(42);
$user->organisationRoles()->pluck('organisations.id');
// Result: [1, 2, 5] means user can access orgs 1, 2, and 5

// Check specific org:
$user->organisationRoles()
    ->where('organisations.id', 1)
    ->exists();
// Result: true/false
```

### Step 2.3: Check the Pivot Table
```bash
# Connect to MySQL:
mysql -u root -p

# Query the pivot table directly:
SELECT * FROM user_organisation_roles WHERE user_id = 42;
```

**Expected Result:** At least one row with org_id that matches route

**Common Issues:**
- Empty result → pivot doesn't exist (create it)
- Has pivot for org 2 but trying to access org 1 → user assigned wrong org
- Multiple pivots → verify organisation_id matches route

### Step 2.4: Check organisation_id Field
```php
php artisan tinker

$user = User::find(42);
echo $user->organisation_id;
// Result: 1, 2, 5, or NULL
```

**If NULL:** User never assigned to organisation
```php
// Fix it:
$user->update(['organisation_id' => 1]);
```

**If > 1 but no pivot:** Run the migration or manually create pivot
```php
DB::table('user_organisation_roles')->insertOrIgnore([
    'user_id' => 42,
    'organisation_id' => $user->organisation_id,
    'role' => 'member',
    'created_at' => now(),
    'updated_at' => now(),
]);
```

### Step 2.5: Test getEffectiveOrganisationId()
```php
php artisan tinker

$user = User::find(42);
$effective = $user->getEffectiveOrganisationId();
echo $effective;  // Should be 1 or valid org_id

// Should also pass belonging check:
$user->belongsToOrganisation($effective);
// Result: true (always)
```

## 3. Diagnosing Wrong Redirects

### Step 3.1: Check Login Logs
```bash
tail -f storage/logs/laravel.log | grep "🎯 PRIORITY"
```

This shows which priority matched:
- "PRIORITY 1" = active voting
- "PRIORITY 2" = onboarded platform
- "PRIORITY 3" = needs onboarding
- "PRIORITY 4" = active election
- "PRIORITY 5" = tenant org dashboard

### Step 3.2: Check User State
```php
php artisan tinker

$user = User::find(42);

echo "ID: " . $user->id . "\n";
echo "Org ID: " . $user->organisation_id . "\n";
echo "Effective Org: " . $user->getEffectiveOrganisationId() . "\n";
echo "Onboarded At: " . $user->onboarded_at . "\n";
echo "Email Verified: " . ($user->email_verified_at ? "YES" : "NO") . "\n";

// Check active voting:
$user->votingSessions()->where('is_completed', false)->count();
```

### Step 3.3: Manually Check Priority Logic
```php
// In code or tinker, simulate the priority checks:

// Priority 1?
if ($user->votingSessions()->where('is_completed', false)->count() > 0) {
    echo "Should go to VOTING page\n";
}

// Priority 2?
elseif ($user->organisation_id == 1 && $user->onboarded_at !== null) {
    echo "Should go to DASHBOARD\n";
}

// Priority 3?
elseif ($user->organisation_id == 1 && $user->onboarded_at === null) {
    echo "Should go to WELCOME\n";
}

// Priority 4-5?
elseif ($user->organisation_id > 1) {
    echo "Should go to ORG DASHBOARD\n";
}
```

### Step 3.4: Test DashboardResolver Directly
```php
php artisan tinker

$user = User::find(42);
$resolver = app(App\Services\DashboardResolver::class);
$response = $resolver->resolve($user);

echo "Redirect URL: " . $response->getTargetUrl() . "\n";
echo "Status Code: " . $response->getStatusCode() . "\n";
```

## 4. Stuck on Welcome Page

### Step 4.1: Check onboarded_at
```php
php artisan tinker

$user = User::find(42);
echo $user->onboarded_at;  // NULL or timestamp?
```

**If NULL:** User hasn't visited welcome page yet (or controller didn't save it)

**If NOT NULL:** Something is resetting it or Priority 3 check is wrong

### Step 4.2: Check Welcome Controller
```bash
grep -r "onboarded_at" app/Http/Controllers/
```

Should find:
```php
// app/Http/Controllers/WelcomeDashboardController.php
$user->update(['onboarded_at' => now()]);
```

### Step 4.3: Check if Update is Happening
```php
// Add test request to welcome page:

$user = User::find(42);
echo "Before: " . $user->onboarded_at . "\n";

// Simulate visiting welcome (if it's a GET):
$response = $this->actingAs($user)->get('/dashboard/welcome');

$user->refresh();  // Reload from DB
echo "After: " . $user->onboarded_at . "\n";
```

### Step 4.4: Check for Redirect in Welcome Page
```bash
# Look at the welcome page controller
cat app/Http/Controllers/WelcomeDashboardController.php
```

**Issue:** Maybe welcome page is redirecting back to itself?

## 5. Internal Server Error (500)

### Step 5.1: Check Error Logs
```bash
tail -50 storage/logs/laravel.log
```

Look for:
- Class not found
- Method doesn't exist
- Database connection error

### Step 5.2: Check Specific Error
```bash
# See full stack trace:
cat storage/logs/laravel.log | grep -A 20 "Exception"
```

### Step 5.3: Common Causes

**Cause 1: Autoloader cache wrong**
```bash
composer dump-autoload
```

**Cause 2: Model methods missing**
```php
// Check if these exist in User model:
- getEffectiveOrganisationId()
- belongsToOrganisation()

php artisan tinker
User::find(1)->getEffectiveOrganisationId();
```

**Cause 3: Database not migrated**
```bash
php artisan migrate --force
php artisan migrate --path=database/migrations/2026_03_04_163924_fix_user_organisation_ids.php
```

## 6. Pivot Table Issues

### Step 6.1: Find Affected Users
```sql
-- Users with missing pivots:
SELECT users.id, users.email, users.organisation_id FROM users
WHERE organisation_id > 1
AND id NOT IN (
    SELECT DISTINCT user_id FROM user_organisation_roles
    WHERE organisation_id = users.organisation_id
);
```

### Step 6.2: Fix Missing Pivots
```php
php artisan tinker

$affectedUserIds = [42, 43, 44];

foreach ($affectedUserIds as $userId) {
    $user = User::find($userId);

    DB::table('user_organisation_roles')->insertOrIgnore([
        'user_id' => $userId,
        'organisation_id' => $user->organisation_id,
        'role' => 'member',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
```

### Step 6.3: Run Migration
```bash
php artisan migrate --path=database/migrations/2026_03_04_163924_fix_user_organisation_ids.php
```

## 7. Performance Issues (Slow Login)

### Step 7.1: Add Timing Logs
```php
// In DashboardResolver::resolve()
$start = microtime(true);

// ... check priorities ...

$duration = microtime(true) - $start;
Log::info('DashboardResolver duration', [
    'user_id' => $user->id,
    'duration_ms' => ($duration * 1000),
]);
```

### Step 7.2: Check for N+1 Queries
```bash
# Enable query logging:
DB::listen(function ($query) {
    \Log::info($query->sql, $query->bindings);
});
```

### Step 7.3: Check Cache
```php
php artisan tinker

// Check if dashboard resolution is cached:
Cache::get('dashboard_resolution_' . $user->id);

// Clear cache to force re-resolution:
Cache::forget('dashboard_resolution_' . $user->id);
```

## 8. Using the Test Suite

### Run Specific Tests
```bash
# Run all registration/login tests:
php artisan test tests/Feature/Auth/RegistrationAndLoginTest.php

# Run one test:
php artisan test tests/Feature/Auth/RegistrationAndLoginTest.php --filter registration_creates_pivot_record

# Run with verbose output:
php artisan test tests/Feature/Auth/RegistrationAndLoginTest.php -v
```

### Simulate Registration and Login
```php
php artisan tinker

// Step 1: Register
$response = app('Illuminate\Testing\TestResponse')
    ->from('/register')
    ->followingRedirects()
    ->post('/register', [
        'firstName' => 'Test',
        'lastName' => 'User',
        'email' => 'test@example.com',
        'region' => 'Bayern',
        'password' => 'Password@123',
        'password_confirmation' => 'Password@123',
        'terms' => true,
    ]);

// Step 2: Check pivot was created
$user = User::where('email', 'test@example.com')->first();
DB::table('user_organisation_roles')
    ->where('user_id', $user->id)
    ->where('organisation_id', 1)
    ->exists();  // Should be true
```

## 9. Checklist for Every Bug

- [ ] Identified exact symptom
- [ ] Checked logs (`storage/logs/`)
- [ ] Ran tests (`php artisan test`)
- [ ] Checked database state
- [ ] Verified pivot records exist
- [ ] Confirmed organisation_id is valid
- [ ] Tested getEffectiveOrganisationId()
- [ ] Checked priority logic
- [ ] Reviewed recent code changes
- [ ] Cleared cache if needed

## 10. When to Escalate

If after following this guide you still can't find the issue:

1. **Export debug info:**
   ```bash
   php artisan tinker --export=debug_info.txt
   ```

2. **Collect logs:**
   ```bash
   cp storage/logs/laravel.log /tmp/laravel-debug.log
   ```

3. **Run full test suite:**
   ```bash
   php artisan test tests/Feature/Auth/ -v
   ```

4. **Check git recent changes:**
   ```bash
   git log --oneline -10
   git diff HEAD~5
   ```

---

**Related Documents:**
- [04-LOGIN_PRIORITY_SYSTEM.md](04-LOGIN_PRIORITY_SYSTEM.md) - Priority logic details
- [03-PIVOT_TABLE_SYSTEM.md](03-PIVOT_TABLE_SYSTEM.md) - Pivot table queries
- [02-USER_MODEL_LIFECYCLE.md](02-USER_MODEL_LIFECYCLE.md) - User creation process
