# 6. Effective Organisation ID Logic

## The Problem It Solves

Users can have a stale `organisation_id` if:
- They were removed from an organisation
- The organisation was deleted
- Database inconsistency
- Manual data manipulation

**Without this method**, DashboardResolver would route to a non-existent or invalid organisation → 403 error.

**With this method**, it automatically falls back to a valid organisation.

## The Method

### Code
```php
// app/Models/User.php
public function getEffectiveOrganisationId(): int
{
    // Rule 1: If org_id > 1, verify a valid pivot exists
    if ($this->organisation_id > 1) {
        $hasPivot = DB::table('user_organisation_roles')
            ->where('user_id', $this->id)
            ->where('organisation_id', $this->organisation_id)
            ->exists();

        if ($hasPivot) {
            // User has valid membership - use assigned org
            return $this->organisation_id;
        }

        // organisation_id is stale (no pivot)
        // Fall through to default
    }

    // Rule 2: Default to platform
    return 1;
}
```

### Logic Flow

```
                  getEffectiveOrganisationId()
                           │
                           ▼
                ┌───────────────────────────┐
                │ Is organisation_id > 1?   │
                └─┬───────────────────────┬─┘
                  │ YES                   │ NO or NULL
                  │                       │
                  ▼                       ▼
         Check pivot exists          Return 1
              │                    (platform org)
         ┌────┴────┐
         │          │
         ▼          ▼
    Exists    Doesn't exist
         │          │
         ▼          ▼
    Return      Return 1
  organisation_id (platform)
```

## Why This Method Exists

### Scenario 1: User Was Removed from Organisation
```
Before:
- User organisation_id = 2 (TechCorp)
- Pivot row: (user=42, org=2, role='member')

Admin removes user from org:
- Pivot row deleted: user_organisation_roles record removed
- organisation_id NOT updated: still = 2 (stale!)

User logs in:
- organisation_id = 2, but no pivot for org 2
- Without fallback: routing to org 2 → 403 error
- With fallback: getEffectiveOrganisationId() returns 1 → routes to platform
```

### Scenario 2: Organisation Was Deleted
```
Before:
- User organisation_id = 3 (DeletedCorp)
- Organisation deleted from system

User tries to login:
- organisation_id = 3, but org doesn't exist
- Without fallback: routing fails, 403 or 404
- With fallback: routes to platform safely
```

### Scenario 3: Database Corruption
```
Direct database manipulation:
UPDATE users SET organisation_id = 999 WHERE id = 42;
-- org_id=999 doesn't exist, no pivot created

User logs in:
- Without fallback: error
- With fallback: automatically corrected
```

## Usage in DashboardResolver

```php
class DashboardResolver
{
    public function resolve(User $user): RedirectResponse
    {
        // Get effective (validated) organisation
        $effectiveOrgId = $user->getEffectiveOrganisationId();

        // Now we KNOW $effectiveOrgId is valid:
        // 1. Has a valid pivot record
        // 2. Is either 1 (platform) or verified tenant org
        // 3. Will not cause 403 error

        // Use it for all priority checks
        if ($effectiveOrgId == 1 && $user->onboarded_at !== null) {
            return redirect()->route('dashboard');
        }

        if ($effectiveOrgId > 1) {
            return redirect()->route('organisation.dashboard', ...);
        }
    }
}
```

## The Companion Method: belongsToOrganisation()

```php
public function belongsToOrganisation(int $organisationId): bool
{
    return DB::table('user_organisation_roles')
        ->where('user_id', $this->id)
        ->where('organisation_id', $organisationId)
        ->exists();
}
```

### Purpose
- Verify user has membership in specific organisation
- Used for explicit permission checks
- Returns true/false (not auto-fallback like getEffectiveOrganisationId)

### Examples
```php
// Check if user can access specific org
if (!$user->belongsToOrganisation(2)) {
    abort(403);  // No membership, deny access
}

// Find all orgs user can access
$orgs = [];
for ($orgId = 1; $orgId <= 10; $orgId++) {
    if ($user->belongsToOrganisation($orgId)) {
        $orgs[] = $orgId;
    }
}
```

## The Backup: User Boot Method

Even if getEffectiveOrganisationId() isn't called, the boot method ensures pivot exists:

```php
protected static function booted()
{
    static::created(function ($user) {
        // Ensure pivot always exists
        $orgId = $user->organisation_id ?? 1;

        DB::table('user_organisation_roles')->insertOrIgnore([
            'user_id' => $user->id,
            'organisation_id' => $orgId,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Ensure organisation_id never NULL
        if ($user->organisation_id === null) {
            $user->update(['organisation_id' => $orgId]);
        }
    });
}
```

## The Migration: Retroactive Fix

For users who already have stale organisation_ids:

```php
// database/migrations/2026_03_04_163924_fix_user_organisation_ids.php
public function up(): void
{
    // Find users with org_id > 1 but no pivot
    $users = DB::table('users')->where('organisation_id', '>', 1)->get();

    foreach ($users as $user) {
        $hasPivot = DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', $user->organisation_id)
            ->exists();

        if (!$hasPivot) {
            // Reset to platform
            DB::table('users')
                ->where('id', $user->id)
                ->update(['organisation_id' => 1]);

            // Remove invalid pivot
            DB::table('user_organisation_roles')
                ->where('user_id', $user->id)
                ->where('organisation_id', '>', 1)
                ->delete();
        }
    }

    // Ensure all users have platform pivot
    $usersWithoutPlatformPivot = DB::table('users')
        ->leftJoin('user_organisation_roles', function ($join) {
            $join->on('users.id', '=', 'user_organisation_roles.user_id')
                ->where('user_organisation_roles.organisation_id', 1);
        })
        ->whereNull('user_organisation_roles.user_id')
        ->select('users.id')
        ->get();

    foreach ($usersWithoutPlatformPivot as $user) {
        DB::table('user_organisation_roles')->insertOrIgnore([
            'user_id' => $user->id,
            'organisation_id' => 1,
            'role' => 'member',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
```

## Testing getEffectiveOrganisationId()

### Test 1: Valid Tenant Organisation
```php
/** @test */
public function valid_tenant_org_returns_org_id()
{
    $user = User::create([...]);
    $user->update(['organisation_id' => 2]);

    // Create pivot for org 2
    DB::table('user_organisation_roles')->insertOrIgnore([
        'user_id' => $user->id,
        'organisation_id' => 2,
        'role' => 'member',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $effective = $user->getEffectiveOrganisationId();
    $this->assertEquals(2, $effective);
}
```

### Test 2: Stale Tenant Organisation (No Pivot)
```php
/** @test */
public function stale_org_id_falls_back_to_platform()
{
    $user = User::create([...]);
    $user->update(['organisation_id' => 999]);  // Org doesn't exist

    // No pivot for org 999

    $effective = $user->getEffectiveOrganisationId();
    $this->assertEquals(1, $effective);  // Falls back to platform
}
```

### Test 3: Platform User
```php
/** @test */
public function platform_user_returns_1()
{
    $user = User::create([...]);  // organisation_id = 1 by default

    $effective = $user->getEffectiveOrganisationId();
    $this->assertEquals(1, $effective);
}
```

### Test 4: belongsToOrganisation()
```php
/** @test */
public function belongs_to_organisation_checks_pivot()
{
    $user = User::create([...]);
    $user->update(['organisation_id' => 2]);

    DB::table('user_organisation_roles')->insertOrIgnore([
        'user_id' => $user->id,
        'organisation_id' => 2,
        'role' => 'member',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $this->assertTrue($user->belongsToOrganisation(2));
    $this->assertFalse($user->belongsToOrganisation(3));
}
```

## Debugging getEffectiveOrganisationId()

```php
php artisan tinker

$user = User::find(42);

// Check assigned org
echo "Assigned org_id: " . $user->organisation_id . "\n";

// Check if pivot exists
$hasPivot = DB::table('user_organisation_roles')
    ->where('user_id', 42)
    ->where('organisation_id', $user->organisation_id)
    ->exists();
echo "Has pivot: " . ($hasPivot ? "YES" : "NO") . "\n";

// Get effective org
$effective = $user->getEffectiveOrganisationId();
echo "Effective org: " . $effective . "\n";

// If different, debug why
if ($effective != $user->organisation_id) {
    echo "Reason: Pivot missing for org_id=$user->organisation_id\n";
    echo "Falling back to platform org\n";
}
```

## When to Use Each Method

| Method | Use Case | Returns |
|--------|----------|---------|
| `$user->organisation_id` | Raw assigned org (might be stale) | int \| null |
| `getEffectiveOrganisationId()` | Get valid org for routing | int (always valid) |
| `belongsToOrganisation($id)` | Check specific membership | bool |
| `organisationRoles()` | Get all org memberships | Collection |

## Invariants Maintained

After calling getEffectiveOrganisationId():

```
MUST be true:
1. Return value is integer > 0
2. User has pivot for returned organisation_id
3. belongsToOrganisation(return_value) === true
4. Can safely use return value for routing
```

---

**Related Documents:**
- [02-USER_MODEL_LIFECYCLE.md](02-USER_MODEL_LIFECYCLE.md) - User creation and boot
- [03-PIVOT_TABLE_SYSTEM.md](03-PIVOT_TABLE_SYSTEM.md) - Pivot table structure
- [04-LOGIN_PRIORITY_SYSTEM.md](04-LOGIN_PRIORITY_SYSTEM.md) - How this is used in priorities
