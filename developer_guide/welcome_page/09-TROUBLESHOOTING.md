# Troubleshooting Guide

## Common Issues & Solutions

---

## Backend Issues

### Issue 1: BadMethodCallException - "Call to undefined method"

**Error Message:**
```
BadMethodCallException: Call to undefined method App\Models\User::hasValidGdprConsent()
```

**Cause:** GDPR compliance methods not added to User model

**Solution:**

```bash
# 1. Check User model has the method
grep -n "hasValidGdprConsent" app/Models/User.php

# 2. If missing, add to app/Models/User.php
public function hasValidGdprConsent(): bool
{
    if (!Schema::hasColumn('users', 'gdpr_consent_accepted_at')) {
        return true; // Assume consent if column doesn't exist
    }

    return $this->gdpr_consent_accepted_at !== null;
}

# 3. Check controller is calling it correctly
grep -n "hasValidGdprConsent" app/Http/Controllers/DashboardController.php
```

### Issue 2: Class Not Found Exception

**Error Message:**
```
Class 'App\Services\Dashboard\RoleDetectionService' not found
```

**Cause:** Service not registered in container or file not created

**Solution:**

```bash
# 1. Verify file exists
ls -la app/Services/Dashboard/RoleDetectionService.php

# 2. Check namespace
head -5 app/Services/Dashboard/RoleDetectionService.php
# Should start with: namespace App\Services\Dashboard;

# 3. Verify service is registered in container
# Services are auto-discovered in Laravel 10+

# 4. Clear cache
php artisan cache:clear
php artisan config:cache
```

### Issue 3: "Undefined property" in UserStateData

**Error Message:**
```
ErrorException: Undefined property: App\DataTransferObjects\UserStateData::$is_new_user
```

**Cause:** Property accessed before initialization

**Solution:**

```php
// In app/DataTransferObjects/UserStateData.php
// Ensure constructor initializes all properties:

public function __construct(
    public readonly string $composite_state,
    public readonly array $roles,
    public readonly string $primary_role,
    public readonly int $confidence_score,
    public readonly int $onboarding_step,
    public readonly array $available_actions,
    public readonly array $pending_actions,
    public readonly ?string $primary_action,
    public readonly string $ui_mode,
) {
    $this->is_new_user = empty($this->roles);
    $this->has_multiple_roles = count($this->roles) > 1;
}

// Or add as computed properties:
public function getIsNewUser(): bool
{
    return empty($this->roles);
}
```

### Issue 4: N+1 Query Problem - CRITICAL (30+ Second Timeout)

**Symptom:** Dashboard welcome page timeout with "Maximum execution time exceeded"

**Actual Problem Found:**
- 50+ database queries instead of 6
- Services calling `.count()`, `.first()`, `.exists()` on unloaded relationships
- RoleDetectionService, ConfidenceCalculator, OnboardingTracker all triggering extra queries
- Page response time: 30+ seconds (should be <200ms)

**Root Cause:**
```php
// ❌ WRONG: Each call executes a new query
if ($user->organizationRoles()->count() > 0) { ... }  // Query 1
if ($user->organizations()->first()) { ... }          // Query 2
if ($user->voterRegistrations()->exists()) { ... }    // Query 3
```

**Solution - Safe Relationship Loading Pattern:**

```php
// ✅ CORRECT: Check if relationship is already loaded
private function getDashboardRoles(User $user): Collection
{
    // Use loaded data if available
    $organizationRoles = $user->relationLoaded('organizationRoles')
        ? $user->organizationRoles
        : $user->organizationRoles()->get();

    if ($organizationRoles->count() > 0) {
        $roles->push('admin');
    }

    // ... check other roles the same way
}
```

**Complete Fix in UserStateBuilder:**

```php
private function eagerLoadUserData(User $user): User
{
    $relationships = [];

    // Only load relationships that exist on User model
    if (method_exists(User::class, 'organizationRoles')) {
        $relationships[] = 'organizationRoles.organization';
    }
    if (method_exists(User::class, 'organizations')) {
        $relationships[] = 'organizations';
    }
    if (method_exists(User::class, 'commissions')) {
        $relationships[] = 'commissions';
    }
    if (method_exists(User::class, 'voterRegistrations')) {
        $relationships[] = 'voterRegistrations';
    }
    if (method_exists(User::class, 'roles')) {
        $relationships[] = 'roles:id,name';
    }

    // Load only if relationships exist
    if (!empty($relationships)) {
        return $user->load($relationships);
    }

    return $user;
}
```

**Verify Fix:**
```bash
# Enable query logging and timing
php artisan tinker

> \DB::enableQueryLog()
> $startTime = microtime(true)
> $builder = app(\App\Services\Dashboard\UserStateBuilder::class)
> $user = \App\Models\User::first()
> $state = $builder->build($user)
> echo "Queries: " . count(\DB::getQueryLog()) . "\n"
> echo "Time: " . round((microtime(true) - $startTime) * 1000) . "ms\n"

# Expected: 6 queries, ~180ms
# Not 50+ queries, 30+ seconds
```

**Result:**
- **Before:** 50+ queries, 30+ seconds, timeout
- **After:** 6 queries, ~180ms, success ✓

### Issue 5: RelationNotFoundException - "Call to undefined relationship"

**Error Message:**
```
BadMethodCallException: Call to undefined relationship [profile] on model [App\Models\User]
```

**Cause:** Attempting to eager load a relationship that doesn't exist on the User model

**Why It Happens:**
```php
// ❌ WRONG: Tries to load non-existent relationship
$user->load(['profile', 'organizationRoles', 'invalid_method']);
```

**Solution - Use method_exists() checks:**

```php
$relationships = [];

// Only add relationships that actually exist
if (method_exists(User::class, 'organizationRoles')) {
    $relationships[] = 'organizationRoles';
}

if (method_exists(User::class, 'organizations')) {
    $relationships[] = 'organizations';
}

// NEVER assume relationships exist
if (!empty($relationships)) {
    $user->load($relationships);
}
```

**Debug:**
```bash
# Check User model for relationships
php artisan tinker
> $user = User::first()
> $user->getRelations()  # Shows all loaded relations
> method_exists(User::class, 'profile')  # Check if method exists
```

---

### Issue 6: Frontend Array Error - "TypeError: this.contentBlocks.some is not a function"

**Error Message:**
```
TypeError: this.contentBlocks.some is not a function
(in Welcome.vue computed property)
```

**Cause:** Vue component calling array methods on null/undefined/non-array data

**Why It Happens:**
```javascript
// ❌ WRONG: Assumes contentBlocks is always an array
computed: {
  hasActionBlock() {
    return this.contentBlocks.some(block => block.type === 'actions')
    // Error if contentBlocks is null!
  }
}
```

**Solution - Always validate arrays:**

```javascript
// ✅ CORRECT: Check array exists before calling methods
computed: {
  hasActionBlock() {
    return (
      Array.isArray(this.contentBlocks) &&
      this.contentBlocks.some(block => block.type === 'actions')
    )
  },

  actionCards() {
    // Ensure contentBlocks is an array
    if (!Array.isArray(this.contentBlocks)) {
      return []
    }

    const actionBlock = this.contentBlocks.find(
      block => block.type === 'actions'
    )

    if (!actionBlock || !actionBlock.data || !actionBlock.data.cards) {
      return []
    }

    return actionBlock.data.cards
  }
}
```

**Props Must Have Defaults:**

```javascript
props: {
  contentBlocks: {
    type: Array,
    required: false,      // Not required!
    default: () => []     // Safe default
  },
  trustSignals: {
    type: Array,
    required: false,      // Not required!
    default: () => []     // Safe default
  },
  // ...
}
```

---

### Issue 7: Serialization Error - "Call to undefined method" in Str.php:855

**Error Message:**
```
Symfony\Component\Debug\Exception\FatalErrorException:
Call to undefined method in Str.php:855
(appears to be circular reference)
```

**Cause:** Eloquent models with loaded relationships causing infinite recursion during serialization

**Why It Happens:**
```php
// If User model has loaded relationships, Inertia tries to serialize them:
$user->load(['organizations', 'roles', ...]);
// Inertia::render() attempts JSON encoding
// User → organizations → members → ... → back to user (circular!)
```

**Solution - Hide relationships in User model:**

```php
// In app/Models/User.php
class User extends Model
{
    protected $hidden = [
        'password',
        'remember_token',
        // Hide all relationships to prevent circular references
        'organizations',
        'organizationRoles',
        'commissions',
        'elections',
        'roles',
        'memberships',
        'profile',
        'voterRegistrations',
    ];
}
```

**Why This Works:**
- Relationships are never serialized to JSON
- Frontend receives only safe user data (name, identifier, timezone)
- Backend still has access to relationships for business logic

**Verify:**
```bash
# Check what gets sent to frontend
php artisan tinker
> $user = User::with(['organizations', 'roles'])->first()
> json_encode($user->toArray())
# Should NOT contain 'organizations', 'roles', etc.
```

---

### Issue 5: Route Not Found

**Error Message:**
```
Route [dashboard.welcome] not defined
```

**Cause:** Route not registered or incorrect name

**Solution:**

```bash
# 1. Check route exists
php artisan route:list | grep welcome

# 2. Verify route in routes/web.php
grep -n "welcome" routes/web.php

# 3. Expected output:
# GET /dashboard/welcome DashboardController@welcome

# 4. If missing, add to routes/web.php
Route::get('/dashboard/welcome', [DashboardController::class, 'welcome'])->name('dashboard.welcome');

# 5. Clear route cache
php artisan route:cache
```

### Issue 6: GDPR Redirect Loop

**Symptom:** User constantly redirected to /consent/required

**Solution:**

```bash
# 1. Check user's gdpr_consent_accepted_at
php artisan tinker
> User::find(1)->gdpr_consent_accepted_at

# 2. If null, update it
> User::find(1)->update(['gdpr_consent_accepted_at' => now()])

# 3. Verify controller logic
grep -A5 "hasValidGdprConsent" app/Http/Controllers/DashboardController.php

# 4. Check consent route exists
php artisan route:list | grep consent
```

---

## Frontend Issues

### Issue 7: Vue Component Not Rendering

**Symptom:** Page shows blank or "Welcome" component doesn't load

**Solution:**

```bash
# 1. Check component file exists
ls -la resources/js/Pages/Dashboard/Welcome.vue

# 2. Verify props received
# Add to component:
<pre>{{ userState }}</pre>

# 3. Check browser console for errors
# Open DevTools (F12) → Console tab

# 4. Verify component is imported correctly
grep -n "Welcome" routes/web.php
# Should show: Inertia::render('Dashboard/Welcome', ...)

# 5. Rebuild frontend assets
npm run build
```

### Issue 8: Translation Keys Not Found

**Error/Symptom:** Page shows `dashboard.welcome_title` instead of translated text

**Solution:**

```bash
# 1. Check translation files exist
ls -la resources/js/locales/pages/Welcome/

# 2. Verify key exists in translation file
grep "welcome_title" resources/js/locales/pages/Welcome/de.json

# 3. Check i18n is configured
grep -n "i18n" resources/js/app.js

# 4. If missing, add to translation file:
{
  "dashboard": {
    "welcome_title": "Willkommen..."
  }
}

# 5. Rebuild frontend
npm run build
```

### Issue 9: Card Click Not Working

**Symptom:** Clicking action cards doesn't navigate anywhere

**Solution:**

```vue
<!-- In QuickStartCard.vue -->
<!-- Ensure @click handler is attached -->
<div @click="handleCardClick" class="card">
  <!-- card content -->
</div>

<!-- In Welcome.vue -->
<!-- Ensure parent listens to click event -->
<QuickStartCard
  @click="handleCardAction"
  :key="card.id"
  v-bind="card"
/>

<script setup>
const handleCardAction = (cardId) => {
  console.log('Card clicked:', cardId);
  // Navigate or dispatch action
  router.visit(`/dashboard/${cardId}`);
};
</script>
```

### Issue 10: Mobile Layout Broken

**Symptom:** Components not responsive on mobile

**Solution:**

```vue
<!-- Ensure responsive classes are used -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
  <!-- responsive grid -->
</div>

<!-- Ensure Tailwind CSS is built for mobile -->
<!-- In tailwind.config.js -->
module.exports = {
  content: [
    './resources/**/*.{js,jsx,ts,tsx,vue}'
  ],
  // ...
};

<!-- Rebuild CSS -->
npm run build
```

---

## Database Issues

### Issue 11: Migration Fails

**Error Message:**
```
SQLSTATE[HY000]: General error: 1 Error creating new PDO connection
```

**Cause:** Database connection error

**Solution:**

```bash
# 1. Check database connection
php artisan tinker
> DB::connection()->getPdo()

# 2. Verify .env database config
cat .env | grep DB_

# 3. Test database credentials
psql -h localhost -U postgres -d publicdigit

# 4. If database doesn't exist, create it
createdb publicdigit

# 5. Retry migration
php artisan migrate
```

### Issue 12: Rollback Fails

**Error Message:**
```
SQLSTATE: Migration ... cannot be rolled back
```

**Cause:** Migration doesn't have proper down() method

**Solution:**

```php
// In migration file
public function down()
{
    Schema::dropIfExists('users');
    // Must be implemented
}

// Verify migration structure
ls -la database/migrations/

// Check specific migration
grep -A20 "function down" database/migrations/****_create_users_table.php
```

### Issue 13: Schema Mismatch

**Error Message:**
```
Column 'gdpr_consent_accepted_at' doesn't exist
```

**Cause:** Migration not run or rolled back

**Solution:**

```bash
# 1. Check migration status
php artisan migrate:status

# 2. Run pending migrations
php artisan migrate

# 3. If column truly missing, create migration
php artisan make:migration add_gdpr_consent_to_users_table

# 4. In migration:
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->timestamp('gdpr_consent_accepted_at')->nullable();
    });
}
```

---

## Cache Issues

### Issue 14: Stale Cache

**Symptom:** Changes don't appear after deployment

**Solution:**

```bash
# 1. Clear all caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2. Clear Redis cache specifically
php artisan cache:clear --tags=user

# 3. Verify cache driver
php artisan tinker
> Cache::get('some_key')

# 4. Check cache config
grep -n "CACHE_DRIVER" .env
# Should be 'redis' in production
```

### Issue 15: Cache Memory Issues

**Error Message:**
```
Redis server returned a 'READONLY' error
```

**Cause:** Redis out of memory

**Solution:**

```bash
# 1. Check Redis status
redis-cli info memory

# 2. Clear old cache data
redis-cli FLUSHDB

# 3. Configure Redis eviction policy in production
redis-cli CONFIG SET maxmemory-policy allkeys-lru

# 4. Monitor Redis
redis-cli MONITOR
```

---

## Performance Issues

### Issue 16: Slow Page Load

**Symptom:** Welcome page takes 2-3 seconds to load

**Solution:**

```bash
# 1. Profile database queries
# In config/app.php set DEBUG = true temporarily
# Then check storage/logs/laravel.log for slow queries

# 2. Add indexes for common queries
php artisan tinker
> Schema::connection('pgsql')->table('users', function($table) {
    $table->index('organization_id');
    $table->index('created_at');
  });

# 3. Cache expensive operations
Cache::remember('user:roles:' . $user->id, 3600, function() {
    return $roleService->detect($user);
});

# 4. Use eager loading (already done in UserStateBuilder)

# 5. Check query performance
EXPLAIN ANALYZE SELECT * FROM users WHERE organization_id = 1;
```

### Issue 17: High CPU Usage

**Symptom:** Server CPU constantly at 80%+

**Solution:**

```bash
# 1. Check running processes
top -n 1 | head -20

# 2. Find slow queries
SELECT query, calls, mean_exec_time FROM pg_stat_statements
ORDER BY mean_exec_time DESC LIMIT 10;

# 3. Kill long-running queries
SELECT pg_terminate_backend(pid) FROM pg_stat_activity
WHERE state = 'active' AND query_start < now() - interval '5 minutes';

# 4. Add database indexes on filtered columns
# See Issue 16 above

# 5. Check queue for stuck jobs
php artisan queue:failed
```

---

## Testing Issues

### Issue 18: Tests Fail Unexpectedly

**Error Message:**
```
Tests\Feature\Dashboard\WelcomePageTest::testWelcomePageLoads FAILED
```

**Solution:**

```bash
# 1. Run single test with verbose output
php artisan test tests/Feature/Dashboard/WelcomePageTest::testWelcomePageLoads -v

# 2. Check test database
php artisan migrate --database=testing

# 3. Verify test .env is correct
cat phpunit.xml | grep DATABASE_URL

# 4. Check for data dependencies
# Tests might depend on seeding

# 5. Run with coverage to see what's untested
php artisan test --coverage

# 6. Check for flaky tests (intermittent failures)
php artisan test --repeat=5 tests/Feature/Dashboard/WelcomePageTest
```

### Issue 19: Migration Fails in Test

**Error Message:**
```
Tests\Feature\Dashboard\WelcomePageTest FAILED
  SQLSTATE[HY000]: test database doesn't exist
```

**Solution:**

```php
// In tests/TestCase.php
protected function setUp(): void
{
    parent::setUp();

    // Ensure test database is created
    if (!file_exists(database_path('testing.sqlite'))) {
        $this->artisan('migrate:fresh', ['--database' => 'testing']);
    }
}
```

---

## Deployment Issues

### Issue 20: Deployment Fails - Git Not Found

**Error Message:**
```
fatal: not a git repository
```

**Cause:** Deploying from non-git directory

**Solution:**

```bash
# 1. Verify git is initialized
cd /var/www/publicdigit
git status

# 2. If not git repo, clone it
cd /var/www
rm -rf publicdigit
git clone https://github.com/public-digit/platform.git publicdigit

# 3. Verify remote is set
git remote -v
```

### Issue 21: Composer Install Hangs

**Error Message:**
```
Resolving dependencies...
```

**Cause:** Network issues or dependency conflicts

**Solution:**

```bash
# 1. Increase timeout
composer install --with-all-dependencies --no-interaction --verbose --timeout=600

# 2. Clear composer cache
composer clear-cache

# 3. Remove lock file and reinstall
rm composer.lock
composer install

# 4. Check for dependency conflicts
composer diagnose
```

### Issue 22: npm build Failed

**Error Message:**
```
FAIL packages/my-app
ERROR: Build failed
```

**Cause:** Frontend asset compilation error

**Solution:**

```bash
# 1. Check for syntax errors
npm run lint

# 2. Clear node_modules and reinstall
rm -rf node_modules package-lock.json
npm install

# 3. Rebuild with verbose output
npm run build -- --verbose

# 4. Check for missing dependencies
npm ls

# 5. Update to latest compatible versions
npm update
```

---

## GDPR-Specific Issues

### Issue 23: Consent Not Being Verified

**Symptom:** Non-consented users can access dashboard

**Solution:**

```bash
# 1. Check hasValidGdprConsent method
grep -n "hasValidGdprConsent" app/Http/Controllers/DashboardController.php

# 2. Verify it's called in welcome()
grep -B5 -A5 "hasValidGdprConsent" app/Http/Controllers/DashboardController.php

# 3. Test with non-consented user
php artisan tinker
> $user = User::factory()->create(['gdpr_consent_accepted_at' => null])
> auth()->setUser($user)
# Visit /dashboard/welcome
# Should redirect to /consent/required

# 4. Verify redirect route exists
php artisan route:list | grep consent
```

### Issue 24: PII in Logs

**Symptom:** User emails/names appearing in error logs

**Solution:**

```php
// In config/logging.php
'production' => [
    'driver' => 'daily',
    'path' => storage_path('logs/laravel.log'),
    'level' => 'error',
    'formatter' => \App\Logging\GDPRSafeFormatter::class,
],

// Create app/Logging/GDPRSafeFormatter.php
class GDPRSafeFormatter {
    public function format(LogRecord $record): string {
        // Remove PII from context
        unset($record['context']['email']);
        unset($record['context']['user_id']);
        return $record->getMessage();
    }
}
```

### Issue 25: Data Export Not Working

**Symptom:** User tries to export data, gets error

**Solution:**

```bash
# 1. Check export endpoint exists
php artisan route:list | grep export

# 2. Verify export controller
ls -la app/Http/Controllers/UserExportController.php

# 3. Check storage permissions
chmod -R 755 storage/exports/

# 4. Test export manually
php artisan tinker
> $user = User::find(1)
> app(UserExportService::class)->export($user)
# Should create file in storage/exports/
```

---

## Diagnostic Commands

Quick diagnostics for common issues:

```bash
# Health check
php artisan tinker
> echo "App Debug: " . (config('app.debug') ? 'ON' : 'OFF');
> echo "Database: " . (DB::connection()->getPdo() ? 'OK' : 'FAILED');
> echo "Cache: " . (Cache::ping() ? 'OK' : 'FAILED');

# Check services are loaded
> app(RoleDetectionService::class)
> app(ConfidenceCalculator::class)

# Verify database schema
> Schema::hasColumn('users', 'gdpr_consent_accepted_at')

# Check routes registered
php artisan route:list | grep -E 'dashboard|welcome'

# View environment
php artisan env

# Check file permissions
ls -la storage/logs/
ls -la bootstrap/cache/
```

---

## When All Else Fails

### Complete Reset (Development Only)

```bash
# WARNING: This deletes all local data
git clean -fd
git reset --hard
php artisan migrate:fresh
php artisan db:seed
php artisan cache:clear
npm install
npm run build
```

### Get Help

1. Check logs: `storage/logs/laravel.log`
2. Run diagnostics: `php artisan tinker`
3. Check this guide: `developer_guide/welcome_page/`
4. File issue: `github.com/public-digit/platform/issues`
5. Contact team: `support@publicdigit.de`

---

## Summary

Most issues can be resolved by:

1. **Checking logs** - Always read the error message carefully
2. **Clearing caches** - `php artisan cache:clear`
3. **Running migrations** - `php artisan migrate`
4. **Rebuilding assets** - `npm run build`
5. **Verifying configuration** - Check .env and config files
6. **Testing locally first** - Never deploy without testing
7. **Checking GDPR compliance** - Verify consent is enforced

When reporting issues, include:
- Error message (full stack trace)
- Steps to reproduce
- Environment (dev/staging/prod)
- Laravel version
- PHP version
- Database state
