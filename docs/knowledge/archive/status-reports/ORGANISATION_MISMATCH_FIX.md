# Organisation Mismatch Fix - Complete Summary

## Problem

Users couldn't vote with error: **"No verification code found"**

**Root Cause:** User's `organisation_id` didn't match election's `organisation_id`

## Why It Happened

### Issue 1: Stale Cache After Organisation Creation
- User creates new organisation
- `OrganisationController.store()` updates `user.organisation_id`
- But `TenantContext` middleware cache still has **old organisation_id**
- Middleware checks: `Cache.get("user.{id}.organisation_id")` → returns stale value
- User is in different organisation than they think → 403 error

### Issue 2: UserFactory Missing UserOrganisationRole
- `User::factory()->create()` sets `organisation_id`
- But doesn't create corresponding `UserOrganisationRole` record
- `TenantContext` middleware checks: `UserOrganisationRole::where('user_id', $id)->where('organisation_id', $org_id)->exists()`
- Returns FALSE → middleware blocks with 403
- Result: User can't access anything after creation

## Solutions Implemented

### 1. **Cache Clearing (OrganisationController)**

```php
// After updating user's organisation_id
Cache::forget("user.{$user->id}.organisation_id");
```

**Impact:** Stale cache no longer blocks new organisations ✅

### 2. **Factory Auto-Creates Role (UserFactory)**

```php
public function configure(): static
{
    return $this->afterCreating(function (User $user) {
        UserOrganisationRole::firstOrCreate(
            ['user_id' => $user->id, 'organisation_id' => $user->organisation_id],
            ['role' => 'voter']
        );
    });
}
```

**Impact:** TenantContext middleware no longer returns 403 ✅

### 3. **Test Cleanup (CodeControllerTest)**

Removed manual `UserOrganisationRole::create()` since factory now handles it.

**Impact:** Tests no longer fail with unique constraint violations ✅

## Test Coverage

### ✅ 3 New Organisation Tests
1. **User can create organisation and immediately access it**
   - Verifies cache clearing works
   - Confirms no 403 after creation

2. **Organisation show page accessible after creation**
   - Tests the redirect target is accessible
   - Confirms middleware passes after org creation

3. **Code verification works after organisation switch**
   - Tests organisation assignment enables code lookup
   - Verifies user/election matching

### ✅ 11 Existing Code Tests
- Code generation (8 chars, safe charset)
- Code expiration handling
- Verified code protection
- has_voted blocking
- IP rate limiting
- Unique code generation

**Total: 14/14 tests passing ✅**

## Diagnostic Commands

### Check Your Organisations

```bash
php artisan organisation:diagnose [user-id] [election-id]
```

Shows:
- Your current organisation
- All organisations you belong to
- Elections in your organisations
- Mismatch detection

**Example output:**
```
USER'S CURRENT ORGANISATION:
   ID: a19577fa-0966-465d-aa09-5916a691a71e
   Name: Namaste Nepal GmbH
   
ALL ORGANISATIONS USER BELONGS TO:
   • Namaste Nepal GmbH (CURRENT) - Role: owner
   • Public Digit - Role: member
   
ELECTIONS IN YOUR CURRENT ORGANISATION:
   • Demo Election (ID: ...)
```

### Assign User to Organisation

```bash
php artisan organisation:assign-user <user-id> <org-id>
php artisan organisation:assign-user <user-id> <org-id> --role=admin
```

Changes:
- Creates `UserOrganisationRole` (if not exists)
- Updates `users.organisation_id`
- Clears cache

### List All Elections

```bash
php artisan elections:list
php artisan elections:list --type=demo
php artisan elections:list --type=real
```

Shows which organisation each election belongs to.

## Verification Steps

### Step 1: Check Current State
```bash
php artisan organisation:diagnose
```

### Step 2: If Elections Missing - Create Demo Election
```bash
php artisan tinker
```

```php
use App\Models\Election;
use Illuminate\Support\Str;

Election::create([
    'id' => (string) Str::uuid(),
    'organisation_id' => 'a19577fa-0966-465d-aa09-5916a691a71e',
    'name' => 'Demo Election',
    'slug' => 'demo-election',
    'type' => 'demo',
    'status' => 'active',
    'start_date' => now(),
    'end_date' => now()->addDays(30),
]);
```

### Step 3: If User in Wrong Organisation - Switch Them
```bash
php artisan organisation:assign-user your-user-id correct-org-id
```

### Step 4: Test Voting Flow
1. Visit your organisation dashboard
2. Click "Vote Now" on demo election
3. Complete 5-step voting process
4. Verify code verification works (no 403) ✅

## Files Changed

| File | Change |
|------|--------|
| `app/Http/Controllers/OrganisationController.php` | Added cache clear, Cache import |
| `database/factories/UserFactory.php` | Added configure() hook to create role |
| `tests/Feature/CodeControllerTest.php` | Removed manual role creation |
| `tests/Feature/OrganisationCreationFixTest.php` | NEW: TDD tests for org creation |
| `app/Console/Commands/DiagnoseOrganisationMismatch.php` | NEW: Diagnostic tool |
| `app/Console/Commands/AssignUserToOrganisation.php` | NEW: Fix tool |
| `app/Console/Commands/ListAllElections.php` | NEW: List elections |

## Database Compatibility

✅ All fixes use Eloquent - **PostgreSQL and MySQL compatible**

No raw SQL, no database-specific functions.

## What Users Should Do

### If Creating New Organisation

The fix handles it automatically:
1. Click "Create Organisation"
2. Fill in name and details
3. Click submit
4. Cache is cleared ✅
5. You're immediately assigned ✅
6. UserOrganisationRole created ✅

### If Voting Fails

```bash
# Diagnose the issue
php artisan organisation:diagnose your-user-id election-id

# If user in wrong organisation:
php artisan organisation:assign-user your-user-id election-org-id

# If election missing in your org:
# Create one using tinker (see Step 2 above)
```

## Commit Message

```
fix: organisation mismatch and middleware 403 errors

Critical fixes:
1. Cache clearing after organisation creation
2. UserFactory creates UserOrganisationRole automatically
3. CodeControllerTest cleanup (removes duplicate role creation)
4. Added TDD tests for organisation creation workflow

All 14 tests passing.
```

---

**Status: ✅ FIXED AND TESTED**

Users can now:
- Create organisations without 403 errors
- Vote immediately after org creation
- Verify codes correctly
- Switch organisations without issues

