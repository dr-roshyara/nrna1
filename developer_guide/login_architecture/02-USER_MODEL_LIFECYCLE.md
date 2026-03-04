# 2. User Model Lifecycle

## The Complete User Journey

### Creation Phase

```php
// 1. User::create() is called
$user = User::create([
    'name' => 'Alice',
    'email' => 'alice@example.com',
    'password' => Hash::make('password'),
    'organisation_id' => null,  // Initially unassigned
]);

// 2. Eloquent fires 'created' event
// 3. User::boot() static::created() hook executes
```

### Boot Method: The Safety Net

```php
// app/Models/User.php
class User extends Model
{
    protected static function booted()
    {
        // This runs after User::create() completes
        static::created(function ($user) {
            // CRITICAL: Ensure pivot record exists for platform org

            // Determine which org to assign to
            $orgId = $user->organisation_id ?? 1;  // Default to platform

            // Create pivot using insertOrIgnore (safe if duplicate exists)
            DB::table('user_organisation_roles')->insertOrIgnore([
                'user_id' => $user->id,
                'organisation_id' => $orgId,
                'role' => 'member',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Ensure organisation_id is never null
            if ($user->organisation_id === null) {
                $user->update(['organisation_id' => $orgId]);
            }
        });
    }
}
```

**Why `insertOrIgnore()`?**
- Registration creates the pivot first (RegisterController)
- Then User::created() tries to create it again (backup)
- `insertOrIgnore()` silently skips if it already exists
- **Result:** Pivot is guaranteed to exist without duplicate errors

## New Methods: Effective Organisation Logic

### getEffectiveOrganisationId()

```php
public function getEffectiveOrganisationId(): int
{
    // Rule 1: If user's assigned org_id > 1, verify they have a pivot for it
    if ($this->organisation_id > 1) {
        $hasPivot = DB::table('user_organisation_roles')
            ->where('user_id', $this->id)
            ->where('organisation_id', $this->organisation_id)
            ->exists();

        if ($hasPivot) {
            // They belong to this org, use it
            return $this->organisation_id;
        }

        // They have stale org_id (no pivot) - fall back to platform
    }

    // Rule 2: Return platform organisation
    return 1;
}
```

**Use Case:** When DashboardResolver needs to know which org to route to
**Returns:** Always a valid organisation_id with a corresponding pivot

### belongsToOrganisation()

```php
public function belongsToOrganisation(int $organisationId): bool
{
    // Simple check: does this user have a pivot for the org?
    return DB::table('user_organisation_roles')
        ->where('user_id', $this->id)
        ->where('organisation_id', $organisationId)
        ->exists();
}
```

**Use Case:** Validation before routing or permission checks
**Returns:** `true` if user has membership, `false` otherwise

## Relationships

### One: User has Many Organisations

```php
public function organisationRoles()
{
    return $this->belongsToMany(
        Organisation::class,
        'user_organisation_roles',  // Pivot table
        'user_id',
        'organisation_id'
    )
    ->withPivot('role')
    ->withTimestamps();
}
```

**Usage:**
```php
// What organisations does Alice belong to?
$orgs = $user->organisationRoles()->get();
// Returns: [1 (platform), 2 (OrgA)]

// Does Alice belong to OrgB?
$user->organisationRoles()
    ->where('organisation_id', 3)
    ->exists();
// Returns: false
```

## State Machine: User Progression

```
┌─────────────┐
│   Created   │
│ org_id=NULL │  ← New user registered
└──────┬──────┘
       │ RegisterController + User::created() fires
       ↓
┌──────────────────┐
│ Platform Member  │
│  org_id = 1      │  ← Has pivot for platform
│  onboarded_at=NULL  ← Not yet seen welcome
└──────┬───────────┘
       │ User logs in
       ↓
┌──────────────────┐
│ Viewing Welcome  │
│  org_id = 1      │  ← WelcomeDashboardController visited
│  onboarded_at=NOW()  ← Timestamp set
└──────┬───────────┘
       │ Next login
       ↓
┌──────────────────┐
│ Onboarded User   │
│  org_id = 1      │  ← Routes to /dashboard (not welcome)
│  onboarded_at!=NULL │  ← Skips welcome screen
└──────────────────┘
```

## Critical Invariants

### MUST BE TRUE for every user:

1. **Pivot Exists for Assigned Org**
   ```sql
   -- For every user with org_id > 1, this must be true:
   SELECT COUNT(*) FROM user_organisation_roles
   WHERE user_id = ? AND organisation_id = ?
   -- Result must be >= 1
   ```

2. **Assigned Org Has Corresponding Pivot**
   ```php
   // In code:
   $user->belongsToOrganisation($user->organisation_id) === true
   ```

3. **Platform Pivot Always Exists**
   ```sql
   -- After registration, this must be true:
   SELECT COUNT(*) FROM user_organisation_roles
   WHERE user_id = ? AND organisation_id = 1
   -- Result must be >= 1
   ```

4. **organisation_id Never NULL**
   ```sql
   -- After registration/creation:
   SELECT organisation_id FROM users WHERE id = ?
   -- Result must NOT be NULL
   ```

## What Can Go Wrong

### Problem 1: Missing Pivot After Registration

**Symptom:** User gets 403 immediately after registering

**Root Cause:**
- RegisterController didn't create pivot
- User::created() hook didn't run
- Both failed silently

**How it's fixed:**
- RegisterController creates pivot synchronously
- User::created() hook creates it as backup
- Both use `insertOrIgnore()` to prevent duplicates

### Problem 2: Stale organisation_id

**Symptom:** User has org_id=2 but no pivot for org 2

**Root Cause:**
- User joined an org (pivot created)
- Org was deleted or user removed from it
- organisation_id was never reset

**How it's fixed:**
- Migration `2026_03_04_163924_fix_user_organisation_ids.php` resets stale org_ids
- getEffectiveOrganisationId() detects and falls back to platform
- DashboardResolver uses getEffectiveOrganisationId() instead of raw org_id

### Problem 3: organisation_id is NULL

**Symptom:** Query fails because organisation_id is NULL

**Root Cause:**
- User created without going through normal creation flow
- Seeder didn't set organisation_id
- Manual database manipulation

**Prevention:**
- Always use User::create() (triggers boot method)
- Always set organisation_id before saving
- Tests verify this invariant

## Testing User Lifecycle

```php
/** @test */
public function user_boot_method_assigns_organisation()
{
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    // MUST have pivot
    $this->assertTrue(
        DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', 1)
            ->exists()
    );

    // MUST have organisation_id set
    $this->assertNotNull($user->fresh()->organisation_id);

    // MUST return 1 for effective org
    $this->assertEquals(1, $user->getEffectiveOrganisationId());
}
```

---

**Next:** [03-PIVOT_TABLE_SYSTEM.md](03-PIVOT_TABLE_SYSTEM.md)
