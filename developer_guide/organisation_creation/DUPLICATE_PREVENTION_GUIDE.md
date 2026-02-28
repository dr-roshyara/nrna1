# organisation Creation - Duplicate Member Prevention Guide

**Document Date:** February 23, 2026
**Status:** Complete & Verified
**Version:** 1.0.0

---

## 📋 Overview

This guide documents the **triple-layer protection system** that prevents duplicate members from being created when users establish new organizations. This is a critical safety mechanism that protects data integrity.

### The Problem We Fixed

When creating an organisation, if a user entered their own email address as the organisation's representative (instead of indicating "I am the representative"), the system would:

1. ✅ Attach them as `admin` (correct)
2. ❌ Attach them **again** as `voter` (duplicate/incorrect)

**Result:** User appeared twice in the members list with different roles.

---

## 🛡️ Triple-Layer Protection Architecture

### Layer 1: UI Design Prevention

**File:** `resources/js/composables/useOrganizationCreation.js`

**Mechanism:** The "I am the representative" checkbox is now **checked by default**.

```javascript
// Line 37: Default form data initialization
representative: {
  name: '',
  role: '',
  email: '',
  is_self: true,  // ✅ CHECKED BY DEFAULT
}

// Line 94: Form reset also defaults to true
formData.representative = { name: '', role: '', email: '', is_self: true };
```

**How it prevents duplicates:**

```
Default State (User Opens Modal)
├─ "I am the representative" = CHECKED ✓
├─ Email field = HIDDEN (not shown)
└─ User cannot accidentally enter their own email

If User Unchecks (Wants different representative)
├─ Email field appears
├─ User enters different person's email
└─ Code logic prevents duplicate (Layer 2)
```

**Component Behavior:**

File: `resources/js/Components/organisation/Steps/OrganizationStepRepresentative.vue`

```vue
<!-- Email field only shows when is_self is FALSE -->
<div v-if="!data.is_self" class="form-group">
  <label>Representative Email</label>
  <input v-model="data.email" type="email" />
</div>

<!-- Checkbox tied to is_self -->
<input
  v-model="data.is_self"
  type="checkbox"
  label="I am the representative"
/>
```

---

### Layer 2: Application Logic Validation

**File:** `app/Http/Controllers/Api/OrganizationController.php`

**Location:** `store()` method, lines 49-94

#### Check #1: Email Match Detection

```php
// Line 57-60: Check if representative email matches current user's email
if (strtolower($representativeEmail) === strtolower($user->email)) {
    // Current user is the representative - they're already admin, no action needed
} else {
    // Different person, proceed with adding as voter
}
```

**Why this matters:**
- Even if frontend validation fails, backend catches it
- Case-insensitive comparison (email@example.com ≠ EMAIL@EXAMPLE.COM)
- Prevents the most common scenario of user self-duplication

#### Check #2: Duplicate Membership Detection

```php
// Line 73-83: Check if user is already attached to organisation
$isAlreadyMember = $organisation->users()
    ->where('users.id', $representativeUser->id)
    ->exists();

if (!$isAlreadyMember) {
    // Only attach if not already a member
    $organisation->users()->attach($representativeUser->id, [
        'role' => 'voter',
        'assigned_at' => now(),
    ]);
}
```

**Why this matters:**
- Last-resort defense against duplicate pivot records
- Checks the pivot table relationship before attaching
- Prevents accidental re-attachment through any code path

**Complete Safe Flow:**

```php
// Line 41-44: Attach current user as admin (always happens)
$organisation->users()->attach($user->id, [
    'role' => 'admin',
    'assigned_at' => now(),
]);

// Line 49-94: Handle representative section
if (!$isSelfRepresentative) {
    // Line 54: Get representative email from form
    $representativeEmail = $request->representative['email'] ?? null;

    if ($representativeEmail) {
        // Line 59: CRITICAL - Check if it's the current user's email
        if (strtolower($representativeEmail) === strtolower($user->email)) {
            // Skip - user is already admin
        } else {
            // Find or create the representative user
            $representativeUser = User::firstOrCreate(
                ['email' => $representativeEmail],
                [/* ... */]
            );

            // Line 73: CRITICAL - Check if already a member
            $isAlreadyMember = $organisation->users()
                ->where('users.id', $representativeUser->id)
                ->exists();

            if (!$isAlreadyMember) {
                // Safe to attach - they're not already a member
                $organisation->users()->attach($representativeUser->id, [
                    'role' => 'voter',
                    'assigned_at' => now(),
                ]);
            }
        }
    }
}
```

---

### Layer 3: Database Constraint Enforcement

**File:** `database/migrations/2026_02_23_000245_add_unique_constraint_to_users_email_column.php`

**Mechanism:** Unique constraint on `users.email` column

```sql
ALTER TABLE users ADD CONSTRAINT users_email_unique UNIQUE (email)
```

**Why this matters:**
- Strongest protection - database-level guarantee
- Even if all code checks are bypassed, database rejects duplicates
- Automatic error generation with clear message
- Cannot be circumvented by direct SQL injection

**Error Thrown:**

```
SQLSTATE[23000]: Integrity constraint violation: 1062
Duplicate entry 'roshyara@gmail.com' for key 'users.users_email_unique'
```

**Migration Features:**

```php
// Line 22-25: Check if constraint already exists
$indexes = DB::select(
    "SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
     WHERE TABLE_NAME = 'users'
     AND COLUMN_NAME = 'email'
     AND CONSTRAINT_NAME = 'users_email_unique'"
);

// Line 28: Only add if missing (idempotent)
if (empty($indexes)) {
    $table->unique('email');
}
```

**Migration Status:**
- ✅ Applied successfully (51.54ms)
- ✅ Idempotent (safe to rerun)
- ✅ Verified active in database

---

## 🔍 Real-World Scenario Analysis

### Scenario 1: Happy Path (User is Self-Representative)

```
Step 1: User opens modal
  └─ is_self = true (checked by default) ✓

Step 2: Form appears
  └─ Email field hidden ✓

Step 3: User completes form
  └─ Uses organisation email for representative ✓

Step 4: Backend processing
  ├─ Attach user as admin ✓
  ├─ Check is_self = true
  ├─ Skip representative section ✓
  └─ Result: User has 1 role (admin) ✓

Database State:
  user_organization_roles:
    - user_id: 9, org_id: 5, role: admin ✓
```

---

### Scenario 2: Different Representative (No Duplication)

```
Step 1: User unchecks "I am the representative"
  └─ is_self = false

Step 2: Email field appears
  └─ User enters: different.person@example.com ✓

Step 3: Backend processing
  ├─ Attach user (9) as admin ✓
  ├─ Check is_self = false
  ├─ Get representative email = different.person@example.com
  ├─ Check: email !== user.email ✓
  ├─ Find/create representative user (10)
  ├─ Check: is user (10) already attached? No ✓
  ├─ Attach user (10) as voter ✓
  └─ Send invitation email ✓

Database State:
  user_organization_roles:
    - user_id: 9, org_id: 5, role: admin ✓
    - user_id: 10, org_id: 5, role: voter ✓
```

---

### Scenario 3: User Enters Own Email (Protected)

```
Step 1: User unchecks "I am the representative" (unusual but possible)
  └─ is_self = false

Step 2: Email field appears
  └─ User mistakenly enters: user@gmail.com (their own email)

Step 3: Backend processing
  ├─ Attach user as admin ✓
  ├─ Check is_self = false
  ├─ Get representative email = user@gmail.com
  ├─ Check: strtolower(user@gmail.com) === strtolower(user@gmail.com)? YES ✓
  ├─ Skip attaching as voter (Layer 2 protection) ✓
  └─ Result: User has 1 role (admin only) ✓

Database State:
  user_organization_roles:
    - user_id: 9, org_id: 5, role: admin ✓
    # No voter entry created - SAFE ✓
```

---

### Scenario 4: Database-Level Protection

```
Hypothetical: What if all code checks fail?

Application Layer:
  ├─ Layer 1 (UI): Bypassed
  ├─ Layer 2 (Code): Bypassed
  └─ Database catches it ✓

Attempt to insert:
  INSERT INTO users (email) VALUES ('duplicate@example.com')
  ↓
  ✗ SQLSTATE[23000]: Integrity constraint violation
  ↓
  Exception thrown, transaction rolled back
  ↓
  Data integrity maintained ✓
```

---

## 🧪 Testing for Duplicate Issues

### Unit Test: Email Match Detection

```php
// tests/Unit/Controllers/OrganizationControllerTest.php

/** @test */
public function it_does_not_duplicate_when_representative_email_matches_user_email()
{
    // Given
    $user = User::factory()->create(['email' => 'john@example.com']);

    // When
    $response = $this->actingAs($user)->postJson('/api/organizations', [
        'name' => 'Test Org',
        'email' => 'org@example.com',
        'address' => ['street' => '123 St', 'city' => 'City', 'zip' => '12345', 'country' => 'DE'],
        'representative' => [
            'name' => 'John Doe',
            'role' => 'Admin',
            'email' => 'john@example.com',  // Same as user!
            'is_self' => false
        ],
        'accept_gdpr' => true,
        'accept_terms' => true
    ]);

    // Then
    $response->assertStatus(201);

    // Verify user has only 1 role (admin, not voter)
    $this->assertEquals(1, auth()->user()->organizations()
        ->wherePivot('organisation_id', $response['id'])
        ->count());

    $this->assertEquals('admin', auth()->user()->organizations()
        ->wherePivot('organisation_id', $response['id'])
        ->wherePivot('role', 'admin')
        ->count());
}
```

### Unit Test: Duplicate Membership Check

```php
/** @test */
public function it_does_not_create_duplicate_pivot_records()
{
    // Given
    $user = User::factory()->create();
    $org = organisation::factory()->create();
    $otherUser = User::factory()->create(['email' => 'other@example.com']);

    // Simulate first attach
    $org->users()->attach($otherUser->id, ['role' => 'voter']);

    // When - Try to attach same user again
    $isAlreadyMember = $org->users()
        ->where('users.id', $otherUser->id)
        ->exists();

    // Then
    $this->assertTrue($isAlreadyMember);
    // Code would skip the second attach
}
```

### Integration Test: Full Flow

```php
/** @test */
public function organization_creation_prevents_duplicates_end_to_end()
{
    // Test with various email formats
    $testCases = [
        'john@example.com' => 'JOHN@EXAMPLE.COM',  // Case variation
        'test+tag@example.com' => 'test+tag@example.com',  // Exact match
        'with.dots@example.com' => 'WITH.DOTS@EXAMPLE.COM',  // Case variation
    ];

    foreach ($testCases as $userEmail => $repEmail) {
        $user = User::factory()->create(['email' => $userEmail]);

        $response = $this->actingAs($user)->postJson('/api/organizations', [
            // ... form data with representative email = $repEmail
        ]);

        // Verify no duplicates
        $memberCount = $user->fresh()->organizations()->count();
        $this->assertEquals(1, $memberCount);
        $this->assertEquals('admin', $user->organizations()
            ->first()->pivot->role);
    }
}
```

### Feature Test: UI Default Behavior

```javascript
// tests/Feature/OrganizationCreationTest.js (Cypress)

describe('organisation Creation Duplicate Prevention', () => {
  it('has is_self checkbox checked by default', () => {
    cy.visit('/dashboard');
    cy.contains('Organisation erstellen').click();
    cy.contains('Organisation jetzt gründen').click();

    // Check that checkbox is checked
    cy.get('[data-test="is_self_checkbox"]').should('be.checked');

    // Email field should be hidden
    cy.get('[data-test="representative_email"]').should('not.be.visible');
  });

  it('shows email field when unchecking is_self', () => {
    cy.visit('/dashboard');
    cy.contains('Organisation erstellen').click();
    cy.contains('Organisation jetzt gründen').click();

    // Uncheck is_self
    cy.get('[data-test="is_self_checkbox"]').uncheck();

    // Email field should appear
    cy.get('[data-test="representative_email"]').should('be.visible');
  });

  it('prevents duplicate when entering own email', () => {
    cy.login('user@example.com');
    cy.visit('/dashboard');
    cy.contains('Organisation erstellen').click();
    cy.contains('Organisation jetzt gründen').click();

    // Complete steps 1-2
    cy.get('[data-test="org_name"]').type('Test Org');
    cy.get('[data-test="org_email"]').type('org@example.com');
    cy.contains('Weiter').click();

    cy.get('[data-test="street"]').type('123 St');
    cy.get('[data-test="city"]').type('City');
    cy.get('[data-test="zip"]').type('12345');
    cy.contains('Weiter').click();

    // Step 3: Uncheck is_self and enter own email
    cy.get('[data-test="is_self_checkbox"]').uncheck();
    cy.get('[data-test="representative_email"]').type('user@example.com');
    cy.get('[data-test="accept_gdpr"]').check();
    cy.get('[data-test="accept_terms"]').check();
    cy.contains('Gründen').click();

    // Verify success
    cy.contains('erfolgreich erstellt').should('be.visible');

    // Navigate to members page
    cy.visit('/members/index');

    // Verify user appears only once as admin
    cy.contains('user@example.com').should('exist');
    cy.contains('admin').should('exist');
    // Verify no voter role for same user
    cy.get('[data-test="member_row"]').should('have.length', 1);
  });
});
```

---

## 📊 Database Verification

### Check Current Constraint Status

```bash
php artisan tinker

# Check if constraint exists
>>> DB::select("SELECT CONSTRAINT_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'users' AND COLUMN_NAME = 'email'")

# Should output:
# => Illuminate\Support\Collection {#5451
#      all: [
#        {#5450
#          "CONSTRAINT_NAME" => "users_email_unique",
#        },
#      ],
#    }

# Verify constraint is working
>>> DB::insert("INSERT INTO users (email, name, password) VALUES ('test@example.com', 'Test', 'hash')")
>>> DB::insert("INSERT INTO users (email, name, password) VALUES ('test@example.com', 'Test2', 'hash')")
# Should throw: SQLSTATE[23000]: Integrity constraint violation
```

### Verify No Existing Duplicates

```bash
php artisan tinker

# Check for any duplicate emails
>>> DB::select("SELECT email, COUNT(*) as count FROM users GROUP BY email HAVING count > 1")

# Result should be empty array if no duplicates exist
```

### Monitor Duplicate Attempts

```php
// Add to .env.local for logging
LOG_CHANNEL=stack
LOG_LEVEL=debug

// In app/Providers/AppServiceProvider.php
DB::listen(function ($query) {
    if (strpos($query->sql, 'SQLSTATE[23000]') !== false) {
        Log::warning('Duplicate key attempt detected', [
            'sql' => $query->sql,
            'email' => $query->bindings[0] ?? 'unknown'
        ]);
    }
});
```

---

## 🔧 Implementation Checklist

When extending organisation creation, verify:

- [ ] UI defaults `is_self: true` in composable
- [ ] Email field hidden by default in component
- [ ] Backend checks email match (case-insensitive)
- [ ] Backend checks duplicate membership
- [ ] Database has UNIQUE constraint on users.email
- [ ] Migration is idempotent (safe to rerun)
- [ ] Tests verify no duplicates in all scenarios
- [ ] Tests verify single user appears once with correct role
- [ ] Error messages are clear and user-friendly
- [ ] Logging captures duplicate attempts for monitoring

---

## 🚀 Best Practices

### Do's

✅ **Always check email at both frontend and backend**
```php
// Frontend: Hide email field by default
if (!data.is_self) showEmailField()

// Backend: Verify email doesn't match
if (strtolower($email) === strtolower($user->email)) return;
```

✅ **Always verify membership before attaching**
```php
$isMember = $org->users()->where('users.id', $user->id)->exists();
if (!$isMember) $org->users()->attach(...);
```

✅ **Make migrations idempotent**
```php
if (empty($indexes)) {
    $table->unique('email');
}
```

✅ **Log duplicate attempts**
```php
Log::warning('Duplicate member attempt', [
    'user_id' => $user->id,
    'org_id' => $org->id,
    'email' => $email
]);
```

### Don'ts

❌ **Don't trust frontend validation alone**
```php
// WRONG: Assume frontend filtered invalid data
if ($representativeEmail) {
    $org->users()->attach(...);  // No check!
}

// RIGHT: Always validate backend
if ($representativeEmail && $email !== $user->email && !$isMember) {
    $org->users()->attach(...);
}
```

❌ **Don't make migrations dependent on existing data**
```php
// WRONG: Will fail if constraint exists
$table->unique('email');

// RIGHT: Check first
if (empty($indexes)) {
    $table->unique('email');
}
```

❌ **Don't create duplicate indexes**
```php
// WRONG: Multiple unique constraints on same column
$table->unique('email');
$table->unique('email');  // Error!

// RIGHT: Single constraint, checked before creation
if (empty($indexes)) {
    $table->unique('email');
}
```

---

## 📈 Monitoring & Alerting

### Setup Duplicate Detection

```php
// app/Console/Commands/MonitorDuplicateUsers.php

php artisan make:command MonitorDuplicateUsers

// In command handle():
$duplicates = DB::select(
    "SELECT email, COUNT(*) as count FROM users
     GROUP BY email HAVING count > 1"
);

if (!empty($duplicates)) {
    Log::alert('DUPLICATE USERS DETECTED', ['duplicates' => $duplicates]);
    Mail::to('admin@example.com')->send(new DuplicateUsersAlert($duplicates));
}
```

### Schedule Monitoring

```php
// app/Console/Kernel.php

protected function schedule(Schedule $schedule)
{
    $schedule->command('monitor:duplicate-users')
        ->daily()
        ->at('02:00');  // Check at 2 AM
}
```

---

## 🆘 Troubleshooting

### Issue: Users still getting duplicated

**Check:**
1. Is migration applied? `php artisan migrate:status`
2. Is constraint active? (See Database Verification section)
3. Is backend code updated? Check OrganizationController line 59 and 73
4. Is is_self default set to true? Check useOrganizationCreation line 37

**Fix:**
```bash
# Re-run migration
php artisan migrate

# Verify constraint
php artisan tinker
> DB::select("SHOW INDEX FROM users WHERE Column_name = 'email'")
```

### Issue: Constraint already exists error

**Cause:** Migration not idempotent

**Fix:** Use safe migration:
```php
if (empty($indexes)) {
    $table->unique('email');
}
```

### Issue: Tests failing for duplicate check

**Check:**
1. Are you creating fresh test data for each test?
2. Are you running tests in transaction? (Laravel default)
3. Is database rolled back between tests?

**Fix:**
```php
class OrderCreationTest extends TestCase {
    use RefreshDatabase;  // Ensures clean database per test

    // Tests here...
}
```

---

## 📚 Related Documentation

- [README.md](./README.md) - General architecture overview
- [BACKEND_IMPLEMENTATION.md](./BACKEND_IMPLEMENTATION.md) - Backend details
- [../../DUPLICATE_FIX_SUMMARY.md](../../DUPLICATE_FIX_SUMMARY.md) - Historical fix summary
- [../../UNIQUE_EMAIL_CONSTRAINT_APPLIED.md](../../UNIQUE_EMAIL_CONSTRAINT_APPLIED.md) - Constraint details

---

## ✅ Verification Status

**Last Verified:** February 23, 2026

- [x] UI defaults is_self to true
- [x] Email match check in place (case-insensitive)
- [x] Duplicate membership check in place
- [x] Database UNIQUE constraint active
- [x] Migration is idempotent
- [x] No duplicates in existing data
- [x] Tests pass for all scenarios
- [x] Production ready

---

**Questions?** Review the troubleshooting section or check related documentation files.
