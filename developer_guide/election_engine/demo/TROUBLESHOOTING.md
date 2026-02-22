# Demo Auto-Creation - Troubleshooting Guide

**Last Updated**: 2026-02-22
**Use this**: When something isn't working as expected

---

## 🔴 Problem: Auto-Creation Not Happening

### Symptoms
- User goes to `/election/demo/start`
- Browser shows error: "No demo elections found"
- No auto-creation occurs
- Logs show no auto-creation event

### Diagnosis Checklist

#### 1. Is the User's Organisation Set?

```bash
php artisan tinker

$user = App\Models\User::find(123);
echo "Organisation ID: " . $user->organisation_id;  // Should not be null
```

**If NULL**: This is expected behavior!
- User without organisation falls back to platform demo
- No auto-creation for platform demos

**If has value**: Continue to step 2

#### 2. Is DemoElectionResolver Being Called?

Add temporary logging:

```php
// In app/Services/DemoElectionResolver.php::getDemoElectionForUser()
Log::info('DEBUG: getDemoElectionForUser called', [
    'user_id' => $user->id,
    'organisation_id' => $user->organisation_id,
]);
```

Check logs:
```bash
grep "getDemoElectionForUser called" storage/logs/laravel.log
```

**If no logs appear**: DemoElectionResolver isn't being called
- Check if ElectionController.startDemo() is actually executing
- Verify routes are defined correctly

**If logs appear**: Continue to step 3

#### 3. Is Service Registered Correctly?

```bash
php artisan tinker

$service = app(App\Services\DemoElectionCreationService::class);
echo get_class($service);  // Should output: App\Services\DemoElectionCreationService
```

**If error**: Service not registered
- Check AppServiceProvider::register() has the registration
- Run `php artisan cache:clear`
- Restart your server

**If works**: Continue to step 4

#### 4. Does Organisation Exist in Database?

```bash
php artisan tinker

$org = App\Models\Organization::find(5);
echo $org ? "Found: " . $org->name : "NOT FOUND";
```

**If not found**: Auto-creation will fail
- Check user.organisation_id is correct
- Verify organisation exists in database
- Check organisation_id is not deleted

**If found**: Continue to step 5

#### 5. Check for Exceptions in createOrganisationDemoElection

```php
// In app/Services/DemoElectionResolver.php, modify:

if (!$orgDemo) {
    $organization = Organization::find($user->organisation_id);
    if ($organization) {
        try {
            $orgDemo = app(DemoElectionCreationService::class)
                ->createOrganisationDemoElection($user->organisation_id, $organization);
        } catch (\Exception $e) {
            \Log::error('AUTO-CREATE FAILED', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'org_id' => $user->organisation_id,
            ]);
            // Re-throw or handle
        }
    }
}
```

Check error logs:
```bash
grep "AUTO-CREATE FAILED" storage/logs/laravel.log
```

**If error shown**:
- See "Specific Exceptions" section below
- Address the specific error

### Solution Checklist

```bash
# 1. Clear cache
php artisan cache:clear
php artisan config:cache

# 2. Verify service is registered
php artisan tinker
> app(App\Services\DemoElectionCreationService::class)

# 3. Verify user has organisation_id
> App\Models\User::find(123)->organisation_id

# 4. Verify organisation exists
> App\Models\Organization::find(5)

# 5. Manually trigger
> $resolver = app(App\Services\DemoElectionResolver::class)
> $demo = $resolver->getDemoElectionForUser($user)
> $demo->id  // Should exist now
```

---

## 🔴 Problem: Wrong Organisation Demo Created

### Symptoms
- Auto-created demo has wrong organisation_id
- User from org 5 gets org 7's demo
- Demo posts have null organisation_id

### Root Cause
Usually means `organisation_id` wasn't propagated somewhere.

### Diagnosis

```bash
php artisan tinker

# Find the problematic demo
$demo = App\Models\Election::find(123);
echo "Demo org: " . $demo->organisation_id;

# Check posts
$posts = App\Models\DemoPost::withoutGlobalScopes()
    ->where('election_id', $demo->id)
    ->get();

foreach ($posts as $post) {
    echo "Post {$post->id} org: {$post->organisation_id}";
    // Should all match $demo->organisation_id
}
```

### Solution

**If Election has wrong org_id**:
```bash
# Delete and recreate
php artisan tinker

$demo = App\Models\Election::find(123);
$demo->posts()->withoutGlobalScopes()->delete();
$demo->candidacies()->withoutGlobalScopes()->delete();
$demo->codes()->withoutGlobalScopes()->delete();
$demo->delete();

# Next access will auto-create correctly
```

**If Posts/Candidates have wrong org_id**:
```bash
# Check the service code
# In DemoElectionCreationService::createPost()
# Line 6: 'organisation_id' => $election->organisation_id,
# Line X: 'organisation_id' => $election->organisation_id,  # Must be here
```

### Prevention
Verify all creates include org_id:
```php
// Every model::create() should have:
'organisation_id' => $election->organisation_id,
```

---

## 🔴 Problem: Duplicate Demos for Same Organisation

### Symptoms
```bash
php artisan tinker

$count = App\Models\Election::withoutGlobalScopes()
    ->where('type', 'demo')
    ->where('organisation_id', 5)
    ->count();

echo $count;  // Shows 2, 3, or more (should be 1)
```

### Root Cause
- Multiple users accessed simultaneously
- Race condition in auto-creation
- Deleted and recreated manually multiple times

### Solution

```bash
php artisan tinker

# Get all demos for this org
$demos = App\Models\Election::withoutGlobalScopes()
    ->where('type', 'demo')
    ->where('organisation_id', 5)
    ->get();

echo "Found: " . count($demos) . " demos";

# Delete all except the newest
$keep = $demos->sortByDesc('created_at')->first();
foreach ($demos as $demo) {
    if ($demo->id !== $keep->id) {
        # Delete related data first
        $demo->posts()->withoutGlobalScopes()->delete();
        $demo->candidacies()->withoutGlobalScopes()->delete();
        $demo->codes()->withoutGlobalScopes()->delete();
        $demo->delete();
        echo "Deleted: {$demo->id}";
    }
}
```

### Prevention
Use database transaction:

```php
// Modify DemoElectionResolver to use transaction
$orgDemo = DB::transaction(function () use ($user, $organization) {
    // Check again after acquiring lock
    $existing = Election::withoutGlobalScopes()
        ->where('type', 'demo')
        ->where('organisation_id', $user->organisation_id)
        ->lockForUpdate()
        ->first();

    if (!$existing) {
        return app(DemoElectionCreationService::class)
            ->createOrganisationDemoElection($user->organisation_id, $organization);
    }
    return $existing;
});
```

---

## 🔴 Problem: Global Scope Filtering Issues

### Symptoms
```bash
php artisan tinker

# In production (where organisation_id is set in context):
$posts = App\Models\DemoPost::where('election_id', 123)->get();
echo "Posts: " . count($posts);  // Returns 0 but should return 3

# Same query without global scopes:
$posts = App\Models\DemoPost::withoutGlobalScopes()
    ->where('election_id', 123)
    ->get();
echo "Posts: " . count($posts);  // Returns 3 ✅
```

### Root Cause
The `BelongsToTenant` trait adds automatic filtering by `organisation_id`.

### Understanding BelongsToTenant

```php
// What you write:
DemoPost::where('election_id', 123)->get();

// What Laravel actually executes (due to global scope):
DemoPost::where('election_id', 123)
         ->where('organisation_id', current_context_org_id)
         ->get();

// If current context org_id doesn't match the demo's org_id:
// Result: Empty query
```

### Solution: Use Correct Context

```php
// In controller, set context first
auth()->user()->setCurrentTenant($election->organisation_id);

// Or specify org_id explicitly
DemoPost::where('election_id', $demo->id)
    ->where('organisation_id', $demo->organisation_id)
    ->get();

// Or bypass for queries that need to
DemoPost::withoutGlobalScopes()->where('election_id', $demo->id)->get();
```

### When to Use `withoutGlobalScopes()`

**DO use**:
- ✅ In tests (when verifying all data was created)
- ✅ In tinker (when debugging)
- ✅ In admin queries (when need cross-org view)
- ✅ In logging/audit queries

**DON'T use**:
- ❌ In production controllers (security risk)
- ❌ In normal voting flow (breaks isolation)
- ❌ Without good reason (security principle)

---

## 🔴 Problem: Organisation ID Null in Logs

### Symptoms
```
Log entry: {
  "level": "info",
  "message": "Vote created",
  "organisation_id": null,  // Should have value
  "election_id": 42
}
```

### Root Cause
Code is reading organisation_id from wrong source:

```php
// ❌ WRONG: Platform demo has NULL organisation_id
Log::info('Vote created', [
    'organisation_id' => $election->organisation_id,  // NULL for platform demo
]);

// ✅ CORRECT: Get from voter slug
Log::info('Vote created', [
    'organisation_id' => $voter_slug->organisation_id,  // Preserved from user's org
]);
```

### Solution
Use voter slug's organisation_id instead of election's:

```php
$orgId = $voter_slug ? $voter_slug->organisation_id : $election->organisation_id;
Log::info('Vote created', [
    'organisation_id' => $orgId,  // Will have value if voter_slug is set
]);
```

---

## 🔴 Problem: Tests Failing with Global Scope Issues

### Symptoms
```
Test: test_creates_national_posts_with_candidates
Failed asserting that 0 matches expected 2
```

When querying demo tables without `withoutGlobalScopes()`.

### Solution
Use `withoutGlobalScopes()` in test assertions:

```php
// ❌ WRONG: Returns empty due to global scope
$posts = DemoPost::where('election_id', $demo->id)->count();  // 0

// ✅ CORRECT: Bypasses global scope
$posts = DemoPost::withoutGlobalScopes()
    ->where('election_id', $demo->id)
    ->count();  // 3
```

### Prevention
Add comment to remind future developers:

```php
// NOTE: Use withoutGlobalScopes() due to BelongsToTenant trait
$posts = DemoPost::withoutGlobalScopes()
    ->where('election_id', $demo->id)
    ->count();
```

---

## ⚠️ Specific Exceptions

### Exception 1: "Model Not Found"

```
Exception: Model [App\Models\Organization] not found

Cause: User.organisation_id points to non-existent org
Solution: Verify organisation exists
```

```bash
php artisan tinker
> App\Models\Organization::find(123)  # Check it exists
```

### Exception 2: "SQLSTATE[HY000]: General Error"

```
Exception: SQLSTATE[HY000]: General error: ...

Cause: Usually database connection or permissions issue
Solution:
1. Check database is running
2. Check credentials in .env
3. Try php artisan migrate
```

### Exception 3: "Table 'demo_posts' doesn't exist"

```
Exception: SQLSTATE[42S02]: Table 'demo_posts' not found

Cause: Database not migrated
Solution: php artisan migrate
```

---

## ✅ Verification Checklist

After fixing issues, verify:

```bash
php artisan tinker

# 1. Service is registered
app(App\Services\DemoElectionCreationService::class)  # Should work

# 2. All tests pass
# Run: php artisan test --filter="DemoElection"

# 3. Create test demo
$org = App\Models\Organization::factory()->create();
$user = App\Models\User::factory()->create(['organisation_id' => $org->id]);
$resolver = app(App\Services\DemoElectionResolver::class);
$demo = $resolver->getDemoElectionForUser($user);

# 4. Verify structure
$posts = DemoPost::withoutGlobalScopes()
    ->where('election_id', $demo->id)
    ->count();
echo $posts;  # Should be 3

# 5. Verify organisation_id everywhere
$candidates = DemoCandidacy::withoutGlobalScopes()
    ->where('election_id', $demo->id)
    ->pluck('organisation_id')
    ->unique()
    ->toArray();
echo count($candidates) === 1 ? "OK" : "PROBLEM";  # Should be OK
```

---

## 🆘 When Nothing Works

### Last Resort Troubleshooting

```bash
# 1. Clear everything
php artisan cache:clear
php artisan config:cache
php artisan view:clear

# 2. Check recent logs for errors
tail -100 storage/logs/laravel.log

# 3. Manually test each component
php artisan tinker

# Create test org
$org = App\Models\Organization::factory()->create();

# Create test user
$user = App\Models\User::factory()->create(['organisation_id' => $org->id]);

# Try service directly
$service = app(App\Services\DemoElectionCreationService::class);
$election = $service->createOrganisationDemoElection($org->id, $org);
echo $election->id;  # Should have ID

# Try resolver
$resolver = app(App\Services\DemoElectionResolver::class);
$demo = $resolver->getDemoElectionForUser($user);
echo $demo->id;  # Should match $election->id
```

### If Still Failing

1. Check git log for recent changes:
   ```bash
   git log --oneline | head -10
   ```

2. Review the implementation commit:
   ```bash
   git show da2bcc0a1  # The auto-creation commit
   ```

3. Run tests with verbose output:
   ```bash
   php artisan test tests/Feature/Services/DemoElectionAutoCreationTest.php --verbose
   ```

4. Check system requirements:
   ```bash
   php -v
   php -m | grep pdo  # Check PDO
   php -m | grep mysql  # Check MySQL
   ```

---

## 📞 Getting Help

### Information to Provide

When asking for help, include:
1. Error message (complete)
2. Stack trace (full)
3. Relevant logs (last 50 lines)
4. Your PHP version
5. Your database type
6. Steps to reproduce

### Debugging Script

```bash
# Save this as debug-auto-creation.sh
#!/bin/bash

echo "=== PHP Version ==="
php -v

echo "=== Composer Check ==="
composer show | grep -i "demo\|election"

echo "=== Database Check ==="
php artisan tinker <<EOF
echo "Database: " . env('DB_DATABASE');
echo "Connection: " . config('database.default');
EOF

echo "=== Service Check ==="
php artisan tinker <<EOF
app(App\Services\DemoElectionCreationService::class);
app(App\Services\DemoElectionResolver::class);
EOF

echo "=== Test Status ==="
php artisan test --filter="DemoElection" --quiet

echo "=== Recent Errors ==="
tail -30 storage/logs/laravel.log | grep -i "error"
```

---

**Status**: ✅ Complete Troubleshooting Guide
**Last Updated**: 2026-02-22
