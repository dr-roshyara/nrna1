# 3. Pivot Table System (user_organisation_roles)

## What is the Pivot Table?

The `user_organisation_roles` table is the **source of truth** for organisation membership. It answers: "Which organisations does this user belong to?"

```sql
CREATE TABLE user_organisation_roles (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,           -- Foreign key to users
    organisation_id BIGINT NOT NULL,   -- Foreign key to organisations
    role VARCHAR(255) NOT NULL,        -- 'member', 'admin', etc.
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(id),
    CONSTRAINT fk_org FOREIGN KEY (organisation_id) REFERENCES organisations(id),
    CONSTRAINT unique_pivot UNIQUE (user_id, organisation_id)  -- CRITICAL
);
```

## The UNIQUE Constraint

```sql
UNIQUE (user_id, organisation_id)
```

**Meaning:** A user can only belong to the same organisation once
**Benefit:** Prevents duplicate membership records
**Consequence:** Second `INSERT` with same (user_id, org_id) fails with integrity error

## How Pivot Records Are Created

### Creation Point 1: RegisterController (Primary)

```php
// app/Http/Controllers/Auth/RegisterController.php
public function store(Request $request)
{
    // Create user
    $user = User::create($validated);

    // CRITICAL: Create pivot immediately (synchronous)
    $orgId = $user->organisation_id
        ?? DB::table('organisations')->where('slug', 'publicdigit')->value('id')
        ?? 1;

    // Check if pivot already exists (from User::created hook)
    $pivotExists = DB::table('user_organisation_roles')
        ->where('user_id', $user->id)
        ->where('organisation_id', $orgId)
        ->exists();

    // Safe insert - ignores if duplicate exists
    DB::table('user_organisation_roles')->insertOrIgnore([
        'user_id' => $user->id,
        'organisation_id' => $orgId,
        'role' => 'member',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}
```

**Why here?**
- Pivot MUST exist before response/redirect
- If User::created() hook fails, this ensures fallback
- Runs in same request/transaction context

### Creation Point 2: User::created() Hook (Backup)

```php
// app/Models/User.php
class User extends Model
{
    protected static function booted()
    {
        static::created(function ($user) {
            // Fallback: create pivot if RegisterController didn't
            $orgId = $user->organisation_id ?? 1;

            DB::table('user_organisation_roles')->insertOrIgnore([
                'user_id' => $user->id,
                'organisation_id' => $orgId,
                'role' => 'member',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Ensure org_id is set
            if ($user->organisation_id === null) {
                $user->update(['organisation_id' => $orgId]);
            }
        });
    }
}
```

**Why here?**
- Catches cases where User::create() is called outside RegisterController
- Examples: Command, seeder, direct model usage
- Uses `insertOrIgnore()` to avoid duplicate errors

### Why insertOrIgnore()?

```php
// Problem: Both RegisterController AND User::created() try to insert
// Solution: Use insertOrIgnore()

DB::table('user_organisation_roles')->insertOrIgnore([
    // This runs twice but second attempt silently succeeds
    // because UNIQUE constraint already satisfied
]);

// Result: No duplicate errors, pivot guaranteed to exist
```

## Migration: Fixing Existing Users

```php
// database/migrations/2026_03_04_163924_fix_user_organisation_ids.php
public function up(): void
{
    // Find users with stale organisation_ids
    $users = DB::table('users')->where('organisation_id', '>', 1)->get();

    foreach ($users as $user) {
        $hasPivot = DB::table('user_organisation_roles')
            ->where('user_id', $user->id)
            ->where('organisation_id', $user->organisation_id)
            ->exists();

        if (!$hasPivot) {
            // Reset org_id to platform and remove invalid pivot
            DB::table('users')
                ->where('id', $user->id)
                ->update(['organisation_id' => 1]);

            DB::table('user_organisation_roles')
                ->where('user_id', $user->id)
                ->where('organisation_id', $user->organisation_id)
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

## Querying Pivot Data

### Find All Organisations a User Belongs To

```php
$organisations = DB::table('user_organisation_roles')
    ->where('user_id', $user->id)
    ->select('organisation_id')
    ->pluck('organisation_id')
    ->toArray();
// Result: [1, 2, 5] (platform + 2 orgs)
```

### Check if User Belongs to Specific Organisation

```php
$isMember = DB::table('user_organisation_roles')
    ->where('user_id', $user->id)
    ->where('organisation_id', 2)
    ->exists();
// Result: true/false
```

### Get User's Role in Organisation

```php
$role = DB::table('user_organisation_roles')
    ->where('user_id', $user->id)
    ->where('organisation_id', 2)
    ->value('role');
// Result: 'member', 'admin', etc.
```

### Find All Users in Organisation

```php
$users = DB::table('user_organisation_roles')
    ->where('organisation_id', 2)
    ->join('users', 'users.id', '=', 'user_organisation_roles.user_id')
    ->select('users.*')
    ->get();
```

## Common Pivot Problems

### Problem 1: Missing Pivot (403 Error)

**Symptom:**
```
User ID: 42, Organisation ID: 1
Trying to access: /organisations/publicdigit
Error: 403 Sie haben keinen Zugang auf diese Organisation
```

**Root Cause:**
```sql
-- This query returns 0 rows:
SELECT * FROM user_organisation_roles
WHERE user_id = 42 AND organisation_id = 1;
```

**Solution:**
```sql
-- Create the missing pivot
INSERT INTO user_organisation_roles (user_id, organisation_id, role, created_at, updated_at)
VALUES (42, 1, 'member', NOW(), NOW());
```

### Problem 2: Duplicate Pivot Errors

**Symptom:**
```
SQLSTATE[23000]: Integrity constraint violation:
1062 Duplicate entry '42-1' for key 'unique_pivot'
```

**Root Cause:**
```php
// Both RegisterController AND User::created() tried to INSERT
// Second attempt violated UNIQUE constraint
```

**Solution:**
```php
// Use insertOrIgnore() - silently succeeds if duplicate
DB::table('user_organisation_roles')->insertOrIgnore([...]);
```

### Problem 3: Stale organisation_id

**Symptom:**
```sql
-- User has org_id > 1 but no matching pivot
SELECT * FROM users WHERE id = 42;
-- Result: organisation_id = 3

SELECT * FROM user_organisation_roles
WHERE user_id = 42 AND organisation_id = 3;
-- Result: (empty)
```

**Root Cause:**
- User belonged to org 3
- Was removed from org 3
- organisation_id was never reset to 1

**Solution:**
- Migration detects this automatically
- getEffectiveOrganisationId() falls back to 1
- DashboardResolver routes correctly

## Testing Pivot Integrity

```php
/** @test */
public function registration_creates_pivot_record()
{
    $response = $this->post('/register', [
        'firstName' => 'Test',
        'lastName' => 'User',
        'email' => 'test@example.com',
        'region' => 'Bayern',
        'password' => 'Password@123',
        'password_confirmation' => 'Password@123',
        'terms' => true,
    ]);

    $user = User::where('email', 'test@example.com')->first();
    $this->assertNotNull($user);

    // CRITICAL: Verify pivot exists
    $pivotExists = DB::table('user_organisation_roles')
        ->where('user_id', $user->id)
        ->where('organisation_id', 1)
        ->exists();

    $this->assertTrue($pivotExists, 'Pivot must exist after registration');
}
```

## Pivot Invariants (MUST ALWAYS BE TRUE)

```
1. For every user with organisation_id > 1:
   ├─ Must have a row in user_organisation_roles with:
   │  ├─ user_id = users.id
   │  ├─ organisation_id = users.organisation_id
   │  └─ role != NULL
   └─ If violated → 403 error

2. Every user must have organisation_id set (not NULL)
   ├─ Enforced by User::created() hook
   └─ Default value: 1

3. Every user must have at least one pivot to org_id = 1
   ├─ This is the platform organisation
   ├─ Even if assigned to other org, platform pivot exists
   └─ Used as fallback in getEffectiveOrganisationId()

4. UNIQUE constraint (user_id, organisation_id)
   ├─ Prevents duplicate membership records
   ├─ Enforced by database
   └─ insertOrIgnore() handles gracefully
```

---

**Next:** [04-LOGIN_PRIORITY_SYSTEM.md](04-LOGIN_PRIORITY_SYSTEM.md)
