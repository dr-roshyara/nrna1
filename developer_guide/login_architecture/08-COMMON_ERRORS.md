# 8. Common Errors & Solutions

## Error: 403 Sie haben keinen Zugang auf diese Organisation

### When You See This

```
User logs in successfully → redirected to /organisations/publicdigit → sees 403 error
OR
User gets 403 immediately after clicking a link to an organisation
```

### Root Causes (in order of likelihood)

#### Cause 1: Missing Pivot Record (Most Common)
```
User organisation_id: 1
Route organisation_id: 1
Pivot query result: 0 rows (pivot doesn't exist)
```

**Check:**
```php
php artisan tinker

$user = User::find(42);
DB::table('user_organisation_roles')
    ->where('user_id', 42)
    ->where('organisation_id', 1)
    ->count();
// Returns 0 → PROBLEM
```

**Fix Option 1: Create Pivot Manually**
```php
DB::table('user_organisation_roles')->insertOrIgnore([
    'user_id' => 42,
    'organisation_id' => 1,
    'role' => 'member',
    'created_at' => now(),
    'updated_at' => now(),
]);
```

**Fix Option 2: Run Migration**
```bash
php artisan migrate --path=database/migrations/2026_03_04_163924_fix_user_organisation_ids.php
```

**Fix Option 3: Re-trigger User Boot**
```php
// Delete user and let registration recreate it
// Or manually call the boot logic
```

#### Cause 2: Stale organisation_id
```
User organisation_id: 2 (org deleted or user removed)
Pivot for org 2: doesn't exist
Trying to access: /organisations/deleted-org
```

**Check:**
```php
$user = User::find(42);
echo $user->organisation_id;  // 2

DB::table('user_organisation_roles')
    ->where('user_id', 42)
    ->where('organisation_id', 2)
    ->exists();  // false
```

**Fix: Reset to Platform**
```php
$user->update(['organisation_id' => 1]);

// Verify:
$user->refresh();
echo $user->getEffectiveOrganisationId();  // Should be 1
```

#### Cause 3: organisation_id is NULL
```
User organisation_id: NULL
```

**Check:**
```php
$user = User::find(42);
echo $user->organisation_id === null ? "NULL" : $user->organisation_id;
```

**Fix:**
```php
$user->update(['organisation_id' => 1]);
```

### Permanent Solution

Ensure **three conditions always true**:

1. User has valid `organisation_id`
2. Corresponding pivot record exists
3. DashboardResolver uses `getEffectiveOrganisationId()`

Run the migration:
```bash
php artisan migrate
```

---

## Error: User Stuck on Welcome Page (/dashboard/welcome)

### When You See This

```
User logs in → always redirected to /dashboard/welcome
Every login shows welcome page
After "viewing" welcome, still redirected there
```

### Root Causes

#### Cause 1: onboarded_at Never Set
```
$user->onboarded_at = NULL
Priority 3 matches → redirect to welcome
Every login: onboarded_at still NULL → stuck
```

**Check:**
```php
$user = User::find(42);
echo $user->onboarded_at;  // NULL?
```

**Fix: Manually Set Timestamp**
```php
$user->update(['onboarded_at' => now()]);
```

**Verify:**
```php
$user->refresh();
// Next login should go to /dashboard
```

#### Cause 2: Welcome Controller Not Updating
```
Welcome page controller exists but doesn't call:
$user->update(['onboarded_at' => now()]);
```

**Check:**
```bash
grep -r "onboarded_at" app/Http/Controllers/
```

**Find the controller file and verify it contains the update**

#### Cause 3: Welcome Page Redirects to Welcome
```
Route /dashboard/welcome
Controller redirects to /dashboard/welcome (infinite loop)
```

**Check:**
```bash
cat app/Http/Controllers/WelcomeDashboardController.php | grep redirect
```

**Fix:** Ensure welcome page does NOT redirect to itself

### Permanent Solution

1. Ensure WelcomeDashboardController updates `onboarded_at`
2. Test the flow:
   ```bash
   php artisan test tests/Feature/Auth/RegistrationAndLoginTest.php --filter "welcome"
   ```
3. All tests should pass

---

## Error: Internal Server Error (500)

### When You See This

```
Login appears to work
Then: "Whoops! Something went wrong"
500 Internal Server Error
```

### Root Causes

#### Cause 1: Autoloader Cache Stale
```
File deleted but composer dump-autoload not run
Autoloader tries to load non-existent class
```

**Check:**
```bash
tail -50 storage/logs/laravel.log | grep -i "class.*not found"
```

**Fix:**
```bash
composer dump-autoload
```

#### Cause 2: Database Migration Not Run
```
Code expects user_organisation_roles table
Table doesn't exist or is missing columns
```

**Check:**
```bash
php artisan migrate:status
```

**Fix:**
```bash
php artisan migrate
```

#### Cause 3: Method Doesn't Exist
```
Code calls $user->getEffectiveOrganisationId()
But User model was not updated with this method
```

**Check:**
```php
php artisan tinker

User::find(1)->getEffectiveOrganisationId();
// Error: Call to undefined method
```

**Fix:**
```bash
# Verify User model has the method
grep -A 10 "getEffectiveOrganisationId" app/Models/User.php
```

### Permanent Solution

1. Run all migrations: `php artisan migrate`
2. Clear autoloader cache: `composer dump-autoload`
3. Verify all model methods exist
4. Check logs: `tail -100 storage/logs/laravel.log`

---

## Error: SQLSTATE[23000] Duplicate Entry

### When You See This

```
SQLSTATE[23000]: Integrity constraint violation:
1062 Duplicate entry '42-1' for key 'unique_pivot'
```

### Root Cause

```
Tried to INSERT same (user_id, organisation_id) twice
First insert succeeded
Second insert failed (UNIQUE constraint violated)
```

### Solution: Use insertOrIgnore()

**Wrong:**
```php
DB::table('user_organisation_roles')->insert([
    'user_id' => 42,
    'organisation_id' => 1,
    ...
]);

// If called twice → error on second call
```

**Right:**
```php
DB::table('user_organisation_roles')->insertOrIgnore([
    'user_id' => 42,
    'organisation_id' => 1,
    ...
]);

// Called twice → first insert succeeds, second silently ignored
```

### Why This Happens

```
RegisterController creates pivot
User::created() hook also tries to create pivot
Both fire in same request

Solution: Both use insertOrIgnore()
```

---

## Error: Wrong Redirect After Login

### When You See This

```
Platform user (org_id=1) redirected to:
/organisations/publicdigit
OR
/organisations/some-org

Instead of:
/dashboard/welcome (if not onboarded)
/dashboard (if onboarded)
```

### Root Cause

```
getEffectiveOrganisationId() returning wrong org
OR
Priority check has wrong condition
OR
organisation_id is stale (pivot missing)
```

### Debug Steps

```php
php artisan tinker

$user = User::find(42);

// 1. Check assigned org
echo "Organisation ID: " . $user->organisation_id . "\n";

// 2. Check effective org
echo "Effective Org: " . $user->getEffectiveOrganisationId() . "\n";

// 3. Check onboarded status
echo "Onboarded At: " . $user->onboarded_at . "\n";

// 4. Check active voting
$voting = $user->votingSessions()
    ->where('is_completed', false)
    ->count();
echo "Active Voting: " . $voting . "\n";

// 5. Manually test DashboardResolver
$resolver = app(App\Services\DashboardResolver::class);
$response = $resolver->resolve($user);
echo "Resolved URL: " . $response->getTargetUrl() . "\n";
```

### Common Fixes

#### Platform User (org_id=1) Going to Wrong Page

**Issue:** Redirecting to `/organisations/publicdigit` instead of `/dashboard`

**Check:**
```php
// Is organisation_id really 1?
$user->organisation_id;  // Should be 1

// Does pivot exist for org 1?
$user->belongsToOrganisation(1);  // Should be true

// Is onboarded_at set correctly?
$user->onboarded_at;  // null = welcome, timestamp = dashboard
```

**Fix:**
```php
// If organisation_id > 1:
$user->update(['organisation_id' => 1]);

// If no pivot:
DB::table('user_organisation_roles')->insertOrIgnore([
    'user_id' => $user->id,
    'organisation_id' => 1,
    'role' => 'member',
    'created_at' => now(),
    'updated_at' => now(),
]);
```

#### Tenant User (org_id>1) Going to Wrong Org

**Issue:** Redirecting to different organisation

**Check:**
```php
// Verify organisation_id
$user->organisation_id;  // Should be the target org

// Verify pivot
$user->belongsToOrganisation($user->organisation_id);  // Should be true
```

**Fix:**
```php
// If pivot missing:
DB::table('user_organisation_roles')->insertOrIgnore([
    'user_id' => $user->id,
    'organisation_id' => $user->organisation_id,
    'role' => 'member',
    'created_at' => now(),
    'updated_at' => now(),
]);

// Clear cache:
Cache::forget('dashboard_resolution_' . $user->id);
```

---

## Error: Slow Login (> 5 seconds)

### When You See This

```
User clicks login
Waits 5+ seconds for redirect
Performance degradation
```

### Check What's Slow

```bash
# Enable query logging
# In .env or config:
DB_LOG_QUERIES=true

# Check storage/logs/laravel.log for slow queries
```

### Common Causes

#### Cause 1: N+1 Query Problem
```
1 query to fetch user
N queries to fetch related data (for each pivot, election, etc.)
```

**Check logs:**
```
SELECT * FROM user_organisation_roles WHERE user_id = 42;
SELECT * FROM elections WHERE organisation_id = 1;
SELECT * FROM elections WHERE organisation_id = 1;
...repeated
```

**Fix:** Use eager loading
```php
$user->load('organisationRoles', 'elections');
```

#### Cause 2: Database Connection Issues
```
Query takes 3+ seconds
Usually network latency
```

**Check:**
```bash
# Test database connection
php artisan tinker
DB::connection()->getPdo()->getAttribute(PDO::ATTR_CONNECTION_STATUS);
```

#### Cause 3: Missing Indexes
```
Query not using index
Full table scan happening
```

**Check:**
```bash
# Verify indexes exist
php artisan migrate:status
```

### Performance Thresholds

LoginResponse tracks performance:

```
< 200ms: Excellent
200-2000ms: Good (warning threshold)
2000-5000ms: Slow (critical threshold)
> 5000ms: Very slow (needs investigation)
```

**Check thresholds in logs:**
```bash
grep "Login resolution took" storage/logs/laravel.log
```

---

## Error: User Created But Cannot Login

### When You See This

```
Registration succeeds
User tries to login
"Email not registered" error
OR
Login succeeds but 403 error
```

### Root Cause

```
Pivot record not created during registration
OR
organisation_id not set
OR
Email not verified
```

### Diagnostic Checklist

```php
php artisan tinker

$user = User::where('email', 'test@example.com')->first();

// 1. User exists?
echo $user ? "YES" : "NO";

// 2. Has pivot for org 1?
$hasPivot = DB::table('user_organisation_roles')
    ->where('user_id', $user->id)
    ->where('organisation_id', 1)
    ->exists();
echo $hasPivot ? "YES" : "NO";

// 3. Email verified?
echo $user->email_verified_at ? "YES" : "NO";

// 4. Has valid organisation_id?
echo $user->organisation_id;
```

### Fixes (in order)

1. Create missing pivot:
   ```php
   DB::table('user_organisation_roles')->insertOrIgnore([
       'user_id' => $user->id,
       'organisation_id' => 1,
       'role' => 'member',
       'created_at' => now(),
       'updated_at' => now(),
   ]);
   ```

2. Set organisation_id:
   ```php
   $user->update(['organisation_id' => 1]);
   ```

3. Verify email:
   ```php
   $user->update(['email_verified_at' => now()]);
   ```

---

## Error: Database Constraint Violation

### Common Constraint Errors

#### Foreign Key Error
```
SQLSTATE[23000]: Integrity constraint violation:
1452 Cannot add or update a child row:
foreign key constraint fails
```

**Cause:** Trying to insert user_id or organisation_id that doesn't exist

**Fix:** Verify IDs exist before insert

#### Unique Constraint Error
```
SQLSTATE[23000]: Integrity constraint violation:
1062 Duplicate entry '42-1' for key 'unique_pivot'
```

**Cause:** Already have (user_id=42, organisation_id=1)

**Fix:** Use `insertOrIgnore()` instead of `insert()`

---

## Quick Error Decision Tree

```
┌─ 403 Error?
│  ├─ Check pivot: DB::table('user_organisation_roles')->where(user, org)
│  ├─ Check org_id: $user->organisation_id
│  └─ Check middleware: grep EnsureOrganisationMember
│
├─ Stuck on Welcome?
│  ├─ Check onboarded_at: echo $user->onboarded_at
│  ├─ Check controller: grep -r onboarded_at app/Http/Controllers
│  └─ Check no redirect loop
│
├─ 500 Error?
│  ├─ Check logs: tail -50 storage/logs/laravel.log
│  ├─ Run migration: php artisan migrate
│  └─ Clear cache: composer dump-autoload
│
├─ Duplicate Entry?
│  └─ Use insertOrIgnore() instead of insert()
│
├─ Wrong Redirect?
│  ├─ Check org_id: $user->organisation_id
│  ├─ Check effective: $user->getEffectiveOrganisationId()
│  ├─ Check onboarded: $user->onboarded_at
│  └─ Check cache: Cache::forget('dashboard_resolution_*')
│
└─ Slow Login?
   ├─ Check queries: DB::LOG_QUERIES=true
   ├─ Check indexes: php artisan migrate:status
   └─ Check network: test DB connection
```

---

**Related Documents:**
- [07-DEBUGGING_GUIDE.md](07-DEBUGGING_GUIDE.md) - Step-by-step debugging methodology
- [03-PIVOT_TABLE_SYSTEM.md](03-PIVOT_TABLE_SYSTEM.md) - Pivot table details
- [04-LOGIN_PRIORITY_SYSTEM.md](04-LOGIN_PRIORITY_SYSTEM.md) - Priority routing
