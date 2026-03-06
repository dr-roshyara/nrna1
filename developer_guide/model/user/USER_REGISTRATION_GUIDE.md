# 📋 User Registration Developer Guide

**Last Updated:** 2026-03-06
**Status:** Production Ready
**Version:** 1.0

---

## Table of Contents

1. [Overview](#overview)
2. [Architecture](#architecture)
3. [Problems Encountered & Fixes](#problems-encountered--fixes)
4. [Registration Flow](#registration-flow)
5. [Critical Considerations](#critical-considerations)
6. [Code Examples](#code-examples)
7. [Testing Guide](#testing-guide)
8. [Common Pitfalls](#common-pitfalls)
9. [Best Practices](#best-practices)
10. [Troubleshooting](#troubleshooting)

---

## Overview

User registration in Public Digit is a **multi-tenant aware process** that:
- Creates users in the default "Public Digit" platform organisation
- Establishes organisation membership via pivot table
- Sends email verification before user can access the system
- Maintains strict tenant isolation from day one

### Key Features

✅ **Organisation Assignment** - All users auto-assigned to default organisation
✅ **Pivot Relationship** - UserOrganisationRole ensures membership
✅ **Email Verification** - Users must verify email before dashboard access
✅ **Tenant Isolation** - No cross-tenant data leakage from registration
✅ **Audit Logging** - Registration events logged for security

---

## Architecture

### Registration Flow Diagram

```
┌─────────────────────────────────────────────────────────────────┐
│                    User Registration Flow                        │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  1. User submits registration form                               │
│     └─> POST /register (RegisterController::store)               │
│                                                                   │
│  2. Validate input                                               │
│     └─> firstName, lastName, email, password, region, terms      │
│                                                                   │
│  3. Get default platform organisation                            │
│     └─> Organisation::getDefaultPlatform()                       │
│         (Must exist - created by migration)                      │
│                                                                   │
│  4. Prepare user data                                            │
│     └─> Add organisation_id to validated array                   │
│     └─> Hash password                                            │
│     └─> Combine firstName + lastName → name                      │
│                                                                   │
│  5. Create user in single operation                              │
│     └─> User::create($validated)                                 │
│     └─> organisation_id MUST be in attributes (see Fix #3)      │
│                                                                   │
│  6. Emit Registered event                                        │
│     └─> Sends email verification link                            │
│                                                                   │
│  7. Create pivot relationship (transaction)                      │
│     └─> UserOrganisationRole::create([...])                      │
│     └─> User is MEMBER (not OWNER/ADMIN)                         │
│     └─> Allows EnsureOrganisationMember middleware to work       │
│                                                                   │
│  8. Redirect to verification notice                              │
│     └─> User CANNOT login until email verified                   │
│     └─> Email verification required before dashboard access      │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

### Database Relationships

```
users                          organisations                user_organisation_roles
┌──────────────────┐         ┌──────────────────┐         ┌──────────────────────┐
│ id (UUID)        │────┐    │ id (UUID)        │◄────┐   │ id (UUID)            │
│ name             │    │    │ name             │     │   │ user_id (FK)         │
│ email            │    │    │ slug             │     │   │ organisation_id (FK) │
│ password         │    │    │ type (platform)  │     │   │ role (member)        │
│ organisation_id  │────┼────│ is_default (true)│     │   │ created_at           │
│ region           │    │    │ created_at       │     │   │ updated_at           │
│ email_verified_at│    │    │ updated_at       │     │   └──────────────────────┘
│ created_at       │    │    └──────────────────┘     │
│ updated_at       │    │                             │
└──────────────────┘    └─────────────────────────────┘
```

---

## Problems Encountered & Fixes

### 🔴 Problem #1: Missing Organisation Assignment

**Error:**
```
SQLSTATE[HY000]: General error: 1364 Field 'organisation_id' doesn't have a default value
```

**Root Cause:**
User::create() was called WITHOUT organisation_id in the validated array.

**Fix:**
```php
// ❌ WRONG - organisation_id is NULL
$user = User::create($validated);

// ✅ CORRECT - organisation_id added before creation
$platformOrg = Organisation::getDefaultPlatform();
$validated['organisation_id'] = $platformOrg->id;
$user = User::create($validated);
```

**Location:** `app/Http/Controllers/Auth/RegisterController.php` (line 49)

**Lesson:** Always include required foreign keys BEFORE calling create()

---

### 🔴 Problem #2: Duplicate RegisterController

**Root Cause:**
Two RegisterController files existed:
1. `app/Http/Controllers/Auth/RegisterController.php` (fixed)
2. `app/Http/Controllers/RegisterController.php` (old code path)

The second file still used old logic without organisation assignment.

**Fix:**
Updated both files to be identical with the organisation assignment logic.

**Lesson:** Maintain single source of truth. Remove/consolidate duplicate files.

---

### 🔴 Problem #3: HasOrganisation Trait Unsetting organisation_id (CRITICAL)

**Error:**
Even after adding organisation_id to validated array, the SQL insert still didn't include it.

**Root Cause:**
```php
// WRONG CODE in HasOrganisation::bootHasOrganisation()
static::creating(function (Model $model) {
    if (isset($model->attributes['organisation_id'])) {
        $model->organisation_id = $model->attributes['organisation_id'];
        unset($model->attributes['organisation_id']);  // ❌ PROBLEM!
    }
});
```

The `unset()` removed organisation_id from the attributes array, which Laravel uses for SQL inserts.

**Fix:**
```php
// ✅ CORRECT - preserve organisation_id in attributes
static::creating(function (Model $model) {
    // Ensure organisation_id is in attributes for database insert
    if ($model->organisation_id && !isset($model->attributes['organisation_id'])) {
        $model->attributes['organisation_id'] = $model->organisation_id;
    }
});
```

**Location:** `app/Traits/HasOrganisation.php` (lines 25-36)

**Lesson:** Understand Laravel's attribute vs property distinction. Attributes go to database. Properties don't.

---

## Registration Flow

### Step-by-Step Implementation

#### 1. Validate Input

```php
$validated = $request->validate([
    'firstName' => ['required', 'string', 'max:255'],
    'lastName' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:App\Models\User'],
    'region' => ['required', 'string', 'max:255'],
    'password' => ['required', 'confirmed', Rules\Password::defaults()],
    'terms' => ['required', 'accepted'],
]);
```

**Important:**
- Email MUST be unique (checked via database constraint)
- Password MUST be confirmed (Laravel validation rule)
- Region is required (used for election filtering)
- Terms acceptance is required (legal requirement)

#### 2. Get Default Organisation

```php
$platformOrg = \App\Models\Organisation::getDefaultPlatform();

if (!$platformOrg) {
    throw new \Exception('Platform organisation not found. Please ensure the platform org is created.');
}
```

**Important:**
- Default organisation MUST exist (created by migration `2026_03_05_000001_create_uuid_organisations_table.php`)
- Name: "Public Digit"
- Slug: "publicdigit"
- Type: "platform"
- is_default: true

#### 3. Prepare User Data

```php
$validated['name'] = $validated['firstName'] . ' ' . $validated['lastName'];
$validated['password'] = Hash::make($validated['password']);
$validated['organisation_id'] = $platformOrg->id;

// Remove unnecessary fields
unset($validated['firstName']);
unset($validated['lastName']);
unset($validated['terms']);
```

**Important:**
- Password MUST be hashed (use Hash::make())
- Combine firstName + lastName into name field
- organisation_id MUST be added BEFORE create()
- Remove validation-only fields (terms, firstName, lastName)

#### 4. Create User

```php
$user = User::create($validated);
```

**What happens:**
1. User model's boot hook checks organisation_id
2. HasOrganisation trait ensures organisation_id stays in attributes
3. SQL INSERT includes all required fields
4. User is created with UUID id
5. Timestamps (created_at, updated_at) are auto-set

#### 5. Emit Registered Event

```php
event(new Registered($user));
```

**What happens:**
1. Laravel Fortify listens to Registered event
2. Verification email is sent automatically
3. Email contains verification link
4. User CANNOT login until they verify email

#### 6. Create Pivot Relationship

```php
DB::transaction(function () use ($user, $platformOrg) {
    \App\Models\UserOrganisationRole::create([
        'user_id' => $user->id,
        'organisation_id' => $platformOrg->id,
        'role' => 'member',
    ]);

    Log::info('User registration - platform membership created', [
        'user_id' => $user->id,
        'organisation_id' => $platformOrg->id,
        'email' => $user->email,
    ]);
});
```

**Important:**
- Use transaction for data consistency
- User is MEMBER role (not owner/admin)
- This pivot entry is REQUIRED for EnsureOrganisationMember middleware
- Log for audit trail

#### 7. Redirect to Verification

```php
return redirect()->route('verification.notice');
```

**Important:**
- DO NOT auto-login after registration
- Email verification MUST happen first
- Fortify requirement: verified email before dashboard access
- User will receive verification email automatically

---

## Critical Considerations

### 1. The Default Organisation Must Exist

**How it's created:**
```php
// Migration: 2026_03_05_000001_create_uuid_organisations_table.php
// Automatically creates default org when migration runs
DB::table('organisations')->insert([
    'id' => Str::uuid(),
    'name' => 'Public Digit',
    'slug' => 'publicdigit',
    'type' => 'platform',
    'is_default' => true,
    'languages' => json_encode(['en', 'de', 'np']),
    'created_at' => now(),
    'updated_at' => now(),
]);
```

**Verification:**
```bash
php artisan tinker
>>> \App\Models\Organisation::getDefaultPlatform()
```

**Should return:** Organisation model with is_default = true

---

### 2. Organisation ID Must Be in Attributes (Not Just Properties)

**Why it matters:**
Laravel's mass assignment uses the `$attributes` array for SQL INSERT. If organisation_id is only in properties (not attributes), it won't be saved.

**Check:**
```php
// ✅ CORRECT - organisation_id in both
$model->attributes['organisation_id'] = $value;
$model->organisation_id = $value;

// ❌ WRONG - only in properties
$model->organisation_id = $value;  // Not saved to database!
```

**See:** HasOrganisation trait boot method

---

### 3. Pivot Relationship MUST Be Created

**Why it matters:**
The EnsureOrganisationMember middleware checks:
```php
$isMember = $user->organisationRoles()
    ->where('organisation_id', $organisation->id)
    ->exists();
```

**Without pivot entry:**
- Middleware returns 403 Forbidden
- User cannot access any organisation-scoped routes
- Dashboard access is blocked

**Verify:**
```bash
php artisan tinker
>>> $user = \App\Models\User::first()
>>> $user->organisationRoles()->count()
# Should return >= 1
```

---

### 4. Email Verification is Mandatory

**Flow:**
1. User registers
2. Verification email sent
3. User clicks link in email
4. Email marked as verified
5. User can now access dashboard

**Important:**
- Verify email BEFORE dashboard access
- Use Fortify's MustVerifyEmail contract
- Check email_verified_at field

---

### 5. Region Field is Required

**Purpose:**
- Used to filter elections (regional vs national posts)
- Affects which candidates voter can see
- Part of voter profile

**Valid values:**
```
'Bayern', 'Baden', 'Hamburg', 'Europa', etc.
```

**Used in:**
- Post filtering (regional posts for voter's region)
- Voter assignment to elections
- Regional election reporting

---

## Code Examples

### Complete Registration Controller Example

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;

class RegisterController
{
    public function show()
    {
        return inertia('Auth/Register');
    }

    public function store(Request $request): RedirectResponse
    {
        // 1️⃣ VALIDATE INPUT
        $validated = $request->validate([
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:App\Models\User'],
            'region' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'],
        ]);

        // 2️⃣ GET DEFAULT ORGANISATION (CRITICAL!)
        $platformOrg = \App\Models\Organisation::getDefaultPlatform();
        if (!$platformOrg) {
            throw new \Exception('Platform organisation not found.');
        }

        // 3️⃣ PREPARE USER DATA
        $validated['name'] = $validated['firstName'] . ' ' . $validated['lastName'];
        $validated['password'] = Hash::make($validated['password']);
        $validated['organisation_id'] = $platformOrg->id;  // ✅ ADD BEFORE CREATE!

        // 4️⃣ CREATE USER
        $user = User::create($validated);

        // 5️⃣ EMIT REGISTERED EVENT (sends verification email)
        event(new Registered($user));

        // 6️⃣ CREATE PIVOT RELATIONSHIP
        DB::transaction(function () use ($user, $platformOrg) {
            \App\Models\UserOrganisationRole::create([
                'user_id' => $user->id,
                'organisation_id' => $platformOrg->id,
                'role' => 'member',
            ]);

            Log::info('User registration - membership created', [
                'user_id' => $user->id,
                'organisation_id' => $platformOrg->id,
                'email' => $user->email,
            ]);
        });

        // 7️⃣ REDIRECT TO EMAIL VERIFICATION
        return redirect()->route('verification.notice');
    }
}
```

### Testing Registration

```php
<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'region' => 'Bayern',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => true,
        ]);

        // User created
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
            'region' => 'Bayern',
        ]);

        // Organisation assigned
        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user->organisation_id);

        // Pivot relationship created
        $this->assertDatabaseHas('user_organisation_roles', [
            'user_id' => $user->id,
            'role' => 'member',
        ]);

        // Redirected to verification
        $response->assertRedirect(route('verification.notice'));
    }

    public function test_email_must_be_unique()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'organisation_id' => Organisation::getDefaultPlatform()->id,
        ]);

        $response = $this->post('/register', [
            'firstName' => 'Jane',
            'lastName' => 'Doe',
            'email' => 'john@example.com',  // ❌ Already exists
            'region' => 'Bayern',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => true,
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_organisation_id_is_persisted()
    {
        $response = $this->post('/register', [
            'firstName' => 'Test',
            'lastName' => 'User',
            'email' => 'test@example.com',
            'region' => 'Europa',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'terms' => true,
        ]);

        $user = User::where('email', 'test@example.com')->first();

        // ✅ Verify organisation_id is set
        $this->assertNotNull($user->organisation_id);
        $this->assertEquals(
            Organisation::getDefaultPlatform()->id,
            $user->organisation_id
        );
    }
}
```

---

## Testing Guide

### Unit Tests

Test the registration logic in isolation:

```bash
php artisan test tests/Feature/Auth/RegisterTest.php
```

### Manual Testing with Tinker

```bash
php artisan tinker

# Create a user directly
$user = \App\Models\User::create([
    'name' => 'Test User',
    'email' => 'test-' . time() . '@example.com',
    'password' => bcrypt('password123'),
    'region' => 'Europe',
    'organisation_id' => \App\Models\Organisation::getDefaultPlatform()->id,
]);

# Verify organisation_id was saved
dd($user->organisation_id);

# Verify pivot relationship
$user->organisationRoles;
```

### Integration Testing

Test the full HTTP flow:

```php
$response = $this->post('/register', [
    'firstName' => 'John',
    'lastName' => 'Doe',
    'email' => 'john@example.com',
    'region' => 'Bayern',
    'password' => 'Password123!',
    'password_confirmation' => 'Password123!',
    'terms' => true,
]);

$response->assertStatus(302);  // Redirect
$response->assertRedirect(route('verification.notice'));
```

---

## Common Pitfalls

### ❌ Pitfall #1: Forgetting organisation_id Before create()

```php
// WRONG
$user = User::create($validated);  // organisation_id is null!
$user->organisation_id = $platformOrg->id;  // Too late, not saved
```

**Fix:** Add organisation_id BEFORE create()
```php
// CORRECT
$validated['organisation_id'] = $platformOrg->id;
$user = User::create($validated);
```

---

### ❌ Pitfall #2: Not Creating Pivot Relationship

```php
// WRONG - creates user but no pivot entry
$user = User::create($validated);
event(new Registered($user));
return redirect()->route('verification.notice');
```

**Fix:** Create pivot relationship
```php
// CORRECT
$user = User::create($validated);
event(new Registered($user));
DB::transaction(function () use ($user, $platformOrg) {
    \App\Models\UserOrganisationRole::create([
        'user_id' => $user->id,
        'organisation_id' => $platformOrg->id,
        'role' => 'member',
    ]);
});
```

---

### ❌ Pitfall #3: Auto-logging In After Registration

```php
// WRONG
Auth::login($user);
return redirect()->route('dashboard');
```

**Fix:** Require email verification first
```php
// CORRECT
event(new Registered($user));
return redirect()->route('verification.notice');
```

**Why:** Laravel Fortify requires email verification before dashboard access.

---

### ❌ Pitfall #4: Not Hashing Password

```php
// WRONG
$validated['password'] = $request->password;
$user = User::create($validated);  // Plaintext password!
```

**Fix:** Hash the password
```php
// CORRECT
$validated['password'] = Hash::make($validated['password']);
$user = User::create($validated);
```

---

### ❌ Pitfall #5: Missing Default Organisation

```php
// WRONG - assumes default org exists
$platformOrg = Organisation::where('is_default', true)->first();
// If null, $user->organisation_id will be null!
```

**Fix:** Check and throw exception
```php
// CORRECT
$platformOrg = Organisation::getDefaultPlatform();
if (!$platformOrg) {
    throw new \Exception('Platform organisation not found.');
}
```

---

## Best Practices

### 1. Always Use Transactions for Multi-Step Operations

```php
DB::transaction(function () use ($user, $platformOrg) {
    // Create pivot
    UserOrganisationRole::create([...]);

    // Log event
    Log::info('User registered', [...]);

    // If any step fails, entire transaction rolls back
});
```

---

### 2. Validate Required Fields

```php
$validated = $request->validate([
    'firstName' => ['required', 'string', 'max:255'],
    'lastName' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:App\Models\User'],
    'region' => ['required', 'string', 'max:255'],
    'password' => ['required', 'confirmed', Rules\Password::defaults()],
    'terms' => ['required', 'accepted'],
]);
```

---

### 3. Hash Passwords Immediately

```php
$validated['password'] = Hash::make($validated['password']);
$user = User::create($validated);
```

Never store plaintext passwords!

---

### 4. Log Registration Events

```php
Log::info('User registration - platform membership created', [
    'user_id' => $user->id,
    'organisation_id' => $platformOrg->id,
    'email' => $user->email,
]);
```

Helps with:
- Debugging registration issues
- Audit trails
- Security monitoring

---

### 5. Handle Exceptions Gracefully

```php
try {
    $platformOrg = Organisation::getDefaultPlatform();
    if (!$platformOrg) {
        throw new \Exception('Platform organisation not found.');
    }

    // Registration logic...
} catch (\Exception $e) {
    Log::error('Registration failed', [
        'email' => $request->email,
        'error' => $e->getMessage(),
    ]);

    return back()->withErrors(['error' => 'Registration failed. Please try again.']);
}
```

---

### 6. Use Meaningful Error Messages

```php
// User-facing
return back()->withErrors(['email' => 'This email is already registered.']);

// Application logging
Log::error('Duplicate registration attempt', [
    'email' => $request->email,
    'ip' => $request->ip(),
]);
```

---

### 7. Require Email Verification

```php
// Always redirect to verification, never auto-login
event(new Registered($user));
return redirect()->route('verification.notice');
```

---

## Troubleshooting

### Issue: "Field 'organisation_id' doesn't have a default value"

**Causes:**
1. organisation_id not added to $validated before create()
2. HasOrganisation trait issue (fixed in our codebase)
3. organisation_id not in User model's $fillable array

**Fix:**
```php
// Check 1: organisation_id in validated data
dd($validated);  // Should include organisation_id

// Check 2: User fillable array
dd(\App\Models\User::class);  // Check $fillable includes 'organisation_id'

// Check 3: Verify default organisation exists
\App\Models\Organisation::getDefaultPlatform();  // Should not be null
```

---

### Issue: User Created but No Pivot Entry

**Causes:**
1. Pivot creation skipped
2. Transaction rolled back
3. Database error during pivot insert

**Fix:**
```php
// Verify pivot exists
$user = User::find($userId);
dd($user->organisationRoles()->count());  // Should be >= 1

// Re-create pivot if missing
UserOrganisationRole::create([
    'user_id' => $user->id,
    'organisation_id' => $platformOrg->id,
    'role' => 'member',
]);
```

---

### Issue: User Sees 403 Forbidden on Dashboard

**Causes:**
1. No pivot entry (see above)
2. organisation_id doesn't match middleware check
3. User not verified

**Fix:**
1. Check pivot relationship exists
2. Verify organisation_id matches default org
3. Check email_verified_at is not null

---

### Issue: Duplicate RegisterController Files

**Causes:**
1. Both files edited independently
2. Changes in one file not replicated to other
3. Confusion about which file is being used

**Prevention:**
- Remove duplicate RegisterController
- Use namespaces consistently: `App\Http\Controllers\Auth\RegisterController`
- Only one file per class

---

## Migration Checklist

When deploying user registration changes:

- [ ] Run migrations: `php artisan migrate`
- [ ] Verify default organisation exists: `php artisan tinker` → `Organisation::getDefaultPlatform()`
- [ ] Clear caches: `php artisan cache:clear`
- [ ] Test registration: Use test credentials
- [ ] Check verification email is sent
- [ ] Verify user can access dashboard after verification
- [ ] Verify organisation_id is set on user record
- [ ] Verify pivot entry exists

---

## Summary

User registration is a **critical system component** that must:

1. ✅ Create user with organisation_id assigned
2. ✅ Hash password before storage
3. ✅ Create pivot relationship for organisation membership
4. ✅ Send verification email
5. ✅ Require email verification before dashboard access
6. ✅ Log all events for audit trail
7. ✅ Handle errors gracefully

The key issues we fixed:
- **Problem #1:** Missing organisation assignment → Fixed by adding org_id before create()
- **Problem #2:** Duplicate files → Fixed by updating both files consistently
- **Problem #3:** HasOrganisation trait → Fixed by preserving org_id in attributes array

---

## References

- **RegisterController:** `app/Http/Controllers/Auth/RegisterController.php`
- **User Model:** `app/Models/User.php`
- **HasOrganisation Trait:** `app/Traits/HasOrganisation.php`
- **Tests:** `tests/Feature/Auth/RegisterTest.php`
- **Migration:** `database/migrations/2026_03_05_000001_create_uuid_organisations_table.php`

---

**Document Version:** 1.0
**Last Updated:** 2026-03-06
**Maintained By:** Development Team
**Status:** Production Ready ✅
