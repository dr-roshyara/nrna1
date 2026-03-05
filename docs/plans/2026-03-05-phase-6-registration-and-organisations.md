# Phase 6: Registration Flow + Organisation Creation Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Implement the demo→paid flow: users register into platform org, explore demo, create their own organisation, and switch contexts with multiple org memberships.

**Architecture:** Users belong to multiple organisations via a pivot table, with one "current" organisation (organisation_id). Registration creates a platform membership, organisation creation adds a tenant membership with owner role. The TenantContext service manages session state, and business logic (hasTenantOrganisation, getOwnedOrganisation) determines which UI to show.

**Tech Stack:** Laravel 11, UUID primary keys, TenantContext service, Eloquent relationships, Database transactions.

---

## Task 1: Remove Over-Engineering from User Model

**Files:**
- Modify: `app/Models/User.php` (remove User::booted() hook, remove getEffectiveOrganisationId, clean up)
- Modify: `tests/Unit/Models/UserTest.php` (update/remove related tests)

**Step 1: Read current User model to see what needs removal**

Run: `cat app/Models/User.php | head -100`

Expected: See User::booted(), getEffectiveOrganisationId(), and boot logic.

**Step 2: Write test verifying we can still access user's current org directly**

File: `tests/Unit/Models/UserTest.php`

```php
/** @test */
public function user_organisation_id_directly_returns_current_organisation()
{
    $platform = Organisation::factory()->create(['type' => 'platform', 'is_default' => true]);
    $user = User::factory()->create(['organisation_id' => $platform->id]);

    // Direct access to organisation_id should work
    $this->assertEquals($platform->id, $user->organisation_id);
}

/** @test */
public function user_belongs_to_organisation_returns_true_for_current_org()
{
    $platform = Organisation::factory()->create(['type' => 'platform', 'is_default' => true]);
    $user = User::factory()->create(['organisation_id' => $platform->id]);
    $user->organisations()->attach($platform->id, ['role' => 'member']);

    $this->assertTrue($user->belongsToOrganisation($platform->id));
}
```

**Step 3: Run tests to verify they fail (since belongsToOrganisation may not exist yet)**

Run: `php artisan test tests/Unit/Models/UserTest.php::user_organisation_id_directly_returns_current_organisation -v`

Expected: FAIL or PASS depending on current state. Note which tests fail.

**Step 4: Remove User::booted() hook entirely**

In `app/Models/User.php`, find and delete:

```php
protected static function booted(): void
{
    // Remove entire hook
}
```

**Step 5: Remove getEffectiveOrganisationId() method**

In `app/Models/User.php`, find and delete:

```php
public function getEffectiveOrganisationId(): string
{
    // Remove this method entirely
}
```

**Step 6: Ensure belongsToOrganisation() method exists (simple)**

In `app/Models/User.php`, add if missing:

```php
public function belongsToOrganisation(string $organisationId): bool
{
    return $this->organisations()
        ->where('organisation_id', $organisationId)
        ->exists();
}
```

**Step 7: Run tests to verify they pass**

Run: `php artisan test tests/Unit/Models/UserTest.php -v`

Expected: PASS

**Step 8: Commit**

```bash
git add app/Models/User.php tests/Unit/Models/UserTest.php
git commit -m "refactor: Remove User::booted hook and getEffectiveOrganisationId, simplify model"
```

---

## Task 2: Add Foreign Key Constraint on Users.organisation_id

**Files:**
- Create: `database/migrations/YYYY_MM_DD_HHMMSS_add_foreign_key_to_users_organisation_id.php`
- Modify: `tests/Feature/DatabaseIntegrityTest.php` (verify FK works)

**Step 1: Create migration file**

Run: `php artisan make:migration add_foreign_key_to_users_organisation_id --table=users`

**Step 2: Write the migration**

File: `database/migrations/[timestamp]_add_foreign_key_to_users_organisation_id.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // organisation_id should already exist, just add the constraint
            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('restrict'); // Never delete org if users exist
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['organisation_id']);
        });
    }
};
```

**Step 3: Write test to verify FK constraint works**

File: `tests/Feature/DatabaseIntegrityTest.php`

```php
/** @test */
public function cannot_delete_organisation_with_active_users()
{
    $org = Organisation::factory()->create(['type' => 'platform']);
    $user = User::factory()->create(['organisation_id' => $org->id]);

    // Should throw integrity constraint exception
    $this->expectException(\Illuminate\Database\QueryException::class);
    $org->delete();
}
```

**Step 4: Run migration**

Run: `php artisan migrate`

Expected: Migration succeeds, no errors.

**Step 5: Run test to verify FK works**

Run: `php artisan test tests/Feature/DatabaseIntegrityTest.php::cannot_delete_organisation_with_active_users -v`

Expected: PASS (exception is thrown as expected)

**Step 6: Commit**

```bash
git add database/migrations/[timestamp]_add_foreign_key_to_users_organisation_id.php tests/Feature/DatabaseIntegrityTest.php
git commit -m "feat: Add foreign key constraint on users.organisation_id"
```

---

## Task 3: Add User Helper Methods

**Files:**
- Modify: `app/Models/User.php` (add three helper methods)
- Modify: `tests/Unit/Models/UserTest.php` (add tests for new methods)

**Step 1: Write tests for new helper methods**

File: `tests/Unit/Models/UserTest.php`

```php
/** @test */
public function has_tenant_organisation_returns_true_if_user_belongs_to_tenant_org()
{
    $platform = Organisation::factory()->create(['type' => 'platform']);
    $tenant = Organisation::factory()->create(['type' => 'tenant']);

    $user = User::factory()->create(['organisation_id' => $platform->id]);
    $user->organisations()->attach($platform->id, ['role' => 'member']);
    $user->organisations()->attach($tenant->id, ['role' => 'member']);

    $this->assertTrue($user->hasTenantOrganisation());
}

/** @test */
public function has_tenant_organisation_returns_false_if_only_platform_member()
{
    $platform = Organisation::factory()->create(['type' => 'platform']);
    $user = User::factory()->create(['organisation_id' => $platform->id]);
    $user->organisations()->attach($platform->id, ['role' => 'member']);

    $this->assertFalse($user->hasTenantOrganisation());
}

/** @test */
public function get_owned_organisation_returns_org_where_user_is_owner()
{
    $platform = Organisation::factory()->create(['type' => 'platform']);
    $tenant = Organisation::factory()->create(['type' => 'tenant']);

    $user = User::factory()->create(['organisation_id' => $platform->id]);
    $user->organisations()->attach($platform->id, ['role' => 'member']);
    $user->organisations()->attach($tenant->id, ['role' => 'owner']);

    $owned = $user->getOwnedOrganisation();

    $this->assertNotNull($owned);
    $this->assertEquals($tenant->id, $owned->id);
}

/** @test */
public function get_owned_organisation_returns_null_if_no_owned_org()
{
    $platform = Organisation::factory()->create(['type' => 'platform']);
    $user = User::factory()->create(['organisation_id' => $platform->id]);
    $user->organisations()->attach($platform->id, ['role' => 'member']);

    $this->assertNull($user->getOwnedOrganisation());
}

/** @test */
public function switch_to_organisation_updates_current_org()
{
    $platform = Organisation::factory()->create(['type' => 'platform']);
    $tenant = Organisation::factory()->create(['type' => 'tenant']);

    $user = User::factory()->create(['organisation_id' => $platform->id]);
    $user->organisations()->attach($platform->id, ['role' => 'member']);
    $user->organisations()->attach($tenant->id, ['role' => 'owner']);

    $user->switchToOrganisation($tenant);

    $this->assertEquals($tenant->id, $user->fresh()->organisation_id);
}

/** @test */
public function switch_to_organisation_throws_if_user_doesnt_belong()
{
    $platform = Organisation::factory()->create(['type' => 'platform']);
    $other = Organisation::factory()->create(['type' => 'tenant']);

    $user = User::factory()->create(['organisation_id' => $platform->id]);
    $user->organisations()->attach($platform->id, ['role' => 'member']);

    $this->expectException(\Exception::class);
    $user->switchToOrganisation($other);
}
```

**Step 2: Run tests to verify they fail**

Run: `php artisan test tests/Unit/Models/UserTest.php -v`

Expected: FAIL - methods don't exist yet

**Step 3: Implement the three helper methods**

File: `app/Models/User.php`

```php
/**
 * Check if user belongs to any tenant organisation
 */
public function hasTenantOrganisation(): bool
{
    return $this->organisations()
        ->where('type', 'tenant')
        ->exists();
}

/**
 * Get the organisation where user is owner (their "real" org)
 */
public function getOwnedOrganisation(): ?Organisation
{
    return $this->organisations()
        ->wherePivot('role', 'owner')
        ->where('type', 'tenant')
        ->first();
}

/**
 * Switch user's current organisation context
 *
 * @throws \Exception if user doesn't belong to organisation
 */
public function switchToOrganisation(Organisation $organisation): void
{
    if (!$this->belongsToOrganisation($organisation->id)) {
        throw new \Exception("Cannot switch to organisation you don't belong to");
    }

    $this->update(['organisation_id' => $organisation->id]);
    app(TenantContext::class)->setContext($this, $organisation);
}
```

**Step 4: Run tests to verify they pass**

Run: `php artisan test tests/Unit/Models/UserTest.php -v`

Expected: PASS

**Step 5: Commit**

```bash
git add app/Models/User.php tests/Unit/Models/UserTest.php
git commit -m "feat: Add hasTenantOrganisation, getOwnedOrganisation, switchToOrganisation methods"
```

---

## Task 4: Implement Registration Flow with Platform Pivot

**Files:**
- Modify: `app/Http/Controllers/Auth/RegisterController.php` (implement registration with pivot)
- Modify: `tests/Feature/Auth/RegistrationTest.php` (test registration creates pivot)

**Step 1: Write test for registration creates platform membership**

File: `tests/Feature/Auth/RegistrationTest.php`

```php
/** @test */
public function registration_creates_user_with_platform_organisation()
{
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $user = User::where('email', 'test@example.com')->first();
    $platform = Organisation::getPlatformOrganisation();

    // User should be created
    $this->assertNotNull($user);

    // User's current org should be platform
    $this->assertEquals($platform->id, $user->organisation_id);

    // User should have pivot record (member role)
    $this->assertTrue(
        $user->organisations()
            ->where('organisation_id', $platform->id)
            ->wherePivot('role', 'member')
            ->exists()
    );

    // Should redirect to dashboard
    $response->assertRedirect('/dashboard');
}
```

**Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/Auth/RegistrationTest.php::registration_creates_user_with_platform_organisation -v`

Expected: FAIL - registration doesn't create pivot yet

**Step 3: Add getPlatformOrganisation helper to Organisation model**

File: `app/Models/Organisation.php`

```php
public static function getPlatformOrganisation(): self
{
    return static::where('type', 'platform')
        ->where('is_default', true)
        ->firstOrFail();
}
```

**Step 4: Modify RegisterController to create platform pivot**

File: `app/Http/Controllers/Auth/RegisterController.php`

```php
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Organisation;
use App\Models\UserOrganisationRole;
use App\Services\TenantContext;

public function store(Request $request)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    DB::transaction(function () use ($request) {
        $platform = Organisation::getPlatformOrganisation();

        // 1. Create user with platform as current organisation
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'organisation_id' => $platform->id,
        ]);

        // 2. Create pivot - user is member of platform
        UserOrganisationRole::create([
            'user_id' => $user->id,
            'organisation_id' => $platform->id,
            'role' => 'member',
        ]);

        // 3. Set tenant context to platform
        app(TenantContext::class)->setContext($user, $platform);

        // 4. Login the user
        Auth::login($user);
    });

    return redirect('/dashboard');
}
```

**Step 5: Run test to verify it passes**

Run: `php artisan test tests/Feature/Auth/RegistrationTest.php::registration_creates_user_with_platform_organisation -v`

Expected: PASS

**Step 6: Run all registration tests**

Run: `php artisan test tests/Feature/Auth/RegistrationTest.php -v`

Expected: All pass

**Step 7: Commit**

```bash
git add app/Http/Controllers/Auth/RegisterController.php app/Models/Organisation.php tests/Feature/Auth/RegistrationTest.php
git commit -m "feat: Implement registration flow with platform organisation membership"
```

---

## Task 5: Create OrganisationController for Tenant Creation

**Files:**
- Create: `app/Http/Controllers/OrganisationController.php`
- Create: `tests/Feature/OrganisationControllerTest.php`
- Modify: `routes/web.php` (add POST /organisations route)

**Step 1: Write test for creating organisation**

File: `tests/Feature/OrganisationControllerTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganisationControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_create_organisation()
    {
        $platform = Organisation::factory()->create(['type' => 'platform', 'is_default' => true]);
        $user = User::factory()->create(['organisation_id' => $platform->id]);
        $user->organisations()->attach($platform->id, ['role' => 'member']);

        $response = $this->actingAs($user)->post('/organisations', [
            'name' => 'Acme Inc',
        ]);

        // Organisation should be created
        $acme = Organisation::where('name', 'Acme Inc')->first();
        $this->assertNotNull($acme);
        $this->assertEquals('tenant', $acme->type);
        $this->assertFalse($acme->is_default);

        // User should be owner of new org
        $this->assertTrue(
            $user->organisations()
                ->where('organisation_id', $acme->id)
                ->wherePivot('role', 'owner')
                ->exists()
        );

        // User's current org should be switched to new org
        $this->assertEquals($acme->id, $user->fresh()->organisation_id);

        // Should redirect to dashboard
        $response->assertRedirect('/dashboard');
    }

    /** @test */
    public function user_retains_platform_membership_after_creating_organisation()
    {
        $platform = Organisation::factory()->create(['type' => 'platform', 'is_default' => true]);
        $user = User::factory()->create(['organisation_id' => $platform->id]);
        $user->organisations()->attach($platform->id, ['role' => 'member']);

        $this->actingAs($user)->post('/organisations', [
            'name' => 'New Org',
        ]);

        // User should still belong to platform
        $this->assertTrue($user->fresh()->belongsToOrganisation($platform->id));
    }

    /** @test */
    public function unauthenticated_user_cannot_create_organisation()
    {
        $response = $this->post('/organisations', [
            'name' => 'Acme Inc',
        ]);

        $response->assertRedirect('/login');
    }
}
```

**Step 2: Run test to verify it fails**

Run: `php artisan test tests/Feature/OrganisationControllerTest.php -v`

Expected: FAIL - controller doesn't exist

**Step 3: Create the OrganisationController**

File: `app/Http/Controllers/OrganisationController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\UserOrganisationRole;
use App\Services\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganisationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($request) {
            $user = auth()->user();

            // 1. Create new tenant organisation
            $org = Organisation::create([
                'name' => $request->name,
                'type' => 'tenant',
                'is_default' => false,
            ]);

            // 2. Add user as OWNER of new organisation
            UserOrganisationRole::create([
                'user_id' => $user->id,
                'organisation_id' => $org->id,
                'role' => 'owner',
            ]);

            // 3. Switch user to their new organisation
            $user->update(['organisation_id' => $org->id]);

            // 4. Update session context
            app(TenantContext::class)->setContext($user, $org);

            // User still retains platform membership (already created at registration)
        });

        return redirect('/dashboard');
    }
}
```

**Step 4: Add route to web.php**

File: `routes/web.php`

Add this route in the authenticated routes group:

```php
Route::middleware('auth')->group(function () {
    Route::post('/organisations', [OrganisationController::class, 'store'])->name('organisations.store');
    // ... existing routes
});
```

**Step 5: Run tests to verify they pass**

Run: `php artisan test tests/Feature/OrganisationControllerTest.php -v`

Expected: PASS

**Step 6: Commit**

```bash
git add app/Http/Controllers/OrganisationController.php tests/Feature/OrganisationControllerTest.php routes/web.php
git commit -m "feat: Create OrganisationController for tenant organisation creation"
```

---

## Task 6: Add Dashboard Intelligence

**Files:**
- Modify: `app/Http/Controllers/DashboardController.php` (add logic to detect user state)
- Modify: `tests/Feature/DashboardControllerTest.php` (test different dashboard flows)

**Step 1: Write tests for dashboard intelligence**

File: `tests/Feature/DashboardControllerTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function new_user_in_platform_context_sees_demo_dashboard()
    {
        $platform = Organisation::factory()->create(['type' => 'platform', 'is_default' => true]);
        $user = User::factory()->create(['organisation_id' => $platform->id]);
        $user->organisations()->attach($platform->id, ['role' => 'member']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        // Should show demo elections (from platform org)
        $response->assertViewHas('elections');
    }

    /** @test */
    public function user_in_tenant_context_sees_tenant_dashboard()
    {
        $platform = Organisation::factory()->create(['type' => 'platform', 'is_default' => true]);
        $tenant = Organisation::factory()->create(['type' => 'tenant']);

        $user = User::factory()->create(['organisation_id' => $tenant->id]);
        $user->organisations()->attach($platform->id, ['role' => 'member']);
        $user->organisations()->attach($tenant->id, ['role' => 'owner']);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        // Should show tenant elections
        $response->assertViewHas('elections');
        $response->assertViewHas('organisation', $tenant);
    }

    /** @test */
    public function user_can_switch_organisations_from_dashboard()
    {
        $platform = Organisation::factory()->create(['type' => 'platform', 'is_default' => true]);
        $tenant = Organisation::factory()->create(['type' => 'tenant']);

        $user = User::factory()->create(['organisation_id' => $platform->id]);
        $user->organisations()->attach($platform->id, ['role' => 'member']);
        $user->organisations()->attach($tenant->id, ['role' => 'owner']);

        $response = $this->actingAs($user)->post('/organisations/' . $tenant->id . '/switch');

        $response->assertRedirect('/dashboard');
        $this->assertEquals($tenant->id, $user->fresh()->organisation_id);
    }
}
```

**Step 2: Run tests to verify they fail**

Run: `php artisan test tests/Feature/DashboardControllerTest.php -v`

Expected: FAIL - logic doesn't exist

**Step 3: Update DashboardController with intelligent logic**

File: `app/Http/Controllers/DashboardController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Election;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = Auth::user();
        $currentOrg = $user->organisation;

        // Determine which dashboard to show
        if ($currentOrg->type === 'platform') {
            // User is in platform context
            if ($user->hasTenantOrganisation()) {
                // They own a tenant org but are viewing platform
                return Inertia::render('Dashboard/SetupComplete', [
                    'ownedOrganisation' => $user->getOwnedOrganisation(),
                    'organisations' => $user->organisations()->get(),
                ]);
            } else {
                // New user - show demo elections
                return Inertia::render('Dashboard/Demo', [
                    'elections' => Election::where('organisation_id', $currentOrg->id)->get(),
                    'organisations' => $user->organisations()->get(),
                ]);
            }
        } else {
            // User is in tenant context - show their real dashboard
            return Inertia::render('Dashboard/Index', [
                'organisation' => $currentOrg,
                'elections' => Election::where('organisation_id', $currentOrg->id)->get(),
                'organisations' => $user->organisations()->get(),
            ]);
        }
    }
}
```

**Step 4: Add organisations relation to User model if missing**

File: `app/Models/User.php`

```php
public function organisation()
{
    return $this->belongsTo(Organisation::class, 'organisation_id');
}

public function organisations()
{
    return $this->belongsToMany(
        Organisation::class,
        'user_organisation_roles',
        'user_id',
        'organisation_id'
    )
    ->withPivot('role')
    ->withTimestamps();
}
```

**Step 5: Add switch route to routes/web.php**

```php
Route::middleware('auth')->group(function () {
    Route::post('/organisations/{organisation}/switch', [OrganisationController::class, 'switch'])->name('organisations.switch');
    // ...
});
```

**Step 6: Implement switch method in OrganisationController**

File: `app/Http/Controllers/OrganisationController.php`

```php
public function switch(Organisation $organisation)
{
    $user = auth()->user();

    $user->switchToOrganisation($organisation);

    return redirect('/dashboard');
}
```

**Step 7: Run tests to verify they pass**

Run: `php artisan test tests/Feature/DashboardControllerTest.php -v`

Expected: PASS

**Step 8: Commit**

```bash
git add app/Http/Controllers/DashboardController.php app/Http/Controllers/OrganisationController.php tests/Feature/DashboardControllerTest.php routes/web.php
git commit -m "feat: Add dashboard intelligence and organisation switching"
```

---

## Task 7: Verify Multi-Tenancy Tests Still Pass

**Files:**
- Test: `tests/Unit/Contexts/*/` (existing UUID tests)

**Step 1: Run existing UUID multi-tenancy tests**

Run: `php artisan test tests/Unit/Contexts/ -v`

Expected: All 17 tests pass (no regressions)

**Step 2: Run all feature tests**

Run: `php artisan test tests/Feature/ -v`

Expected: All pass

**Step 3: Run full test suite**

Run: `php artisan test --coverage`

Expected: Coverage >= 80%, all tests green

**Step 4: No commit needed** (verification step)

---

## Execution Summary

**Total tasks:** 7
**Estimated time:** 60-90 minutes
**TDD approach:** Test-first for all new functionality
**Commits:** 6 feature commits, each atomic and deployable

---

## Success Criteria

- ✅ All 17 UUID tests still pass
- ✅ Registration creates platform pivot with member role
- ✅ User can create organisation and becomes owner
- ✅ User switches to new org, retains platform membership
- ✅ Dashboard shows appropriate UI based on user context
- ✅ Foreign key prevents orphaned users
- ✅ Helper methods simplify business logic

---

## Next: Phase 7 (Seeders & Verification)

After completing Phase 6, Phase 7 will:
1. Create seeders for demo organisations and elections
2. Create test data for manual testing
3. Verify the demo→paid flow end-to-end
4. Document system architecture in ADRs

