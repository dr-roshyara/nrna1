## 📝 **Claude Code Instructions: Update Election Creation with TDD**

---

## 🎯 **Task: Implement Hybrid Election Creation (Owner Creates, Chief Manages)**

### **Current Behavior:**
- Chief can create elections
- Owner can create elections

### **Desired Behavior:**
- **Only Organisation Owner/Admin** can create elections
- **Chief/Deputy** can manage elections after creation
- **Commissioner** cannot create or manage

---

## 📋 **TDD Workflow**

### **Step 1: Update Tests First**

**File:** `tests/Feature/Election/ElectionCreationTest.php`

Add these new test cases:

```php
<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ElectionCreationTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $owner;
    private User $admin;
    private User $chief;
    private User $deputy;
    private User $commissioner;
    private User $regularMember;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        // Create users with different roles
        $this->owner = $this->createUserWithRole('owner');
        $this->admin = $this->createUserWithRole('admin');
        $this->chief = $this->createOfficer('chief', 'active');
        $this->deputy = $this->createOfficer('deputy', 'active');
        $this->commissioner = $this->createOfficer('commissioner', 'active');
        $this->regularMember = $this->createUserWithRole('voter');
    }

    // =========================================================================
    // NEW TESTS: Permission Changes
    // =========================================================================

    /** @test */
    public function organisation_owner_can_create_election(): void
    {
        $response = $this->actingAs($this->owner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload());

        $response->assertRedirect();
        $this->assertDatabaseHas('elections', [
            'organisation_id' => $this->org->id,
            'name' => 'General Election 2026',
            'type' => 'real',
            'status' => 'planned',
        ]);
    }

    /** @test */
    public function organisation_admin_can_create_election(): void
    {
        $response = $this->actingAs($this->admin)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload());

        $response->assertRedirect();
        $this->assertDatabaseHas('elections', [
            'organisation_id' => $this->org->id,
            'name' => 'General Election 2026',
        ]);
    }

    /** @test */
    public function election_chief_cannot_create_election(): void
    {
        $response = $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload());

        $response->assertForbidden();
        $this->assertDatabaseMissing('elections', [
            'organisation_id' => $this->org->id,
            'name' => 'General Election 2026',
        ]);
    }

    /** @test */
    public function election_deputy_cannot_create_election(): void
    {
        $response = $this->actingAs($this->deputy)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload());

        $response->assertForbidden();
        $this->assertDatabaseMissing('elections', [
            'organisation_id' => $this->org->id,
            'name' => 'General Election 2026',
        ]);
    }

    /** @test */
    public function election_commissioner_cannot_create_election(): void
    {
        $response = $this->actingAs($this->commissioner)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload());

        $response->assertForbidden();
        $this->assertDatabaseMissing('elections', [
            'organisation_id' => $this->org->id,
            'name' => 'General Election 2026',
        ]);
    }

    /** @test */
    public function regular_member_cannot_create_election(): void
    {
        $response = $this->actingAs($this->regularMember)
            ->withSession($this->orgSession())
            ->post(route('organisations.elections.store', $this->org->slug), $this->validPayload());

        $response->assertForbidden();
        $this->assertDatabaseMissing('elections', [
            'organisation_id' => $this->org->id,
            'name' => 'General Election 2026',
        ]);
    }

    /** @test */
    public function policy_only_allows_owner_and_admin_to_create(): void
    {
        $this->assertTrue($this->owner->can('create', [Election::class, $this->org]));
        $this->assertTrue($this->admin->can('create', [Election::class, $this->org]));
        $this->assertFalse($this->chief->can('create', [Election::class, $this->org]));
        $this->assertFalse($this->deputy->can('create', [Election::class, $this->org]));
        $this->assertFalse($this->commissioner->can('create', [Election::class, $this->org]));
        $this->assertFalse($this->regularMember->can('create', [Election::class, $this->org]));
    }

    // =========================================================================
    // Existing tests remain unchanged
    // =========================================================================

    /** @test */
    public function election_requires_name(): void
    {
        // ... existing test
    }

    // ... all other existing validation tests

    // =========================================================================
    // Helpers
    // =========================================================================

    private function orgSession(): array
    {
        return ['current_organisation_id' => $this->org->id];
    }

    private function validPayload(): array
    {
        return [
            'name' => 'General Election 2026',
            'description' => 'Election for organisation leadership',
            'start_date' => now()->addDays(7)->toDateString(),
            'end_date' => now()->addDays(14)->toDateString(),
        ];
    }

    private function createUserWithRole(string $role): User
    {
        $user = User::factory()->create([
            'organisation_id' => $this->org->id,
            'email_verified_at' => now(),
        ]);
        
        UserOrganisationRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $this->org->id,
            'role' => $role,
        ]);
        
        return $user;
    }

    private function createOfficer(string $role, string $status): User
    {
        $user = User::factory()->create([
            'organisation_id' => $this->org->id,
            'email_verified_at' => now(),
        ]);
        
        UserOrganisationRole::create([
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'organisation_id' => $this->org->id,
            'role' => 'voter',
        ]);
        
        ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id' => $user->id,
            'role' => $role,
            'status' => $status,
            'appointed_by' => $user->id,
            'appointed_at' => now(),
            'accepted_at' => $status === 'active' ? now() : null,
        ]);
        
        return $user;
    }
}
```

---

### **Step 2: Run Tests (They Will FAIL)**

```bash
php artisan test --filter=ElectionCreationTest
```

**Expected:** Tests 1-2 (owner/admin) should pass, tests 3-7 (chief/deputy/etc.) should fail with 403 or assertion errors.

---

### **Step 3: Update Policy**

**File:** `app/Policies/ElectionPolicy.php`

```php
/**
 * Chief or deputy may create elections for this organisation.
 * Receives Organisation (not Election) because no election exists yet.
 */
public function create(User $user, Organisation $organisation): bool
{
    // Only organisation owner or admin can create elections
    return UserOrganisationRole::where('user_id', $user->id)
        ->where('organisation_id', $organisation->id)
        ->whereIn('role', ['owner', 'admin'])
        ->exists();
}
```

---

### **Step 4: Update OrganisationController to Pass canCreateElection**

**File:** `app/Http/Controllers/OrganisationController.php`

```php
public function show(Organisation $organisation)
{
    // ... existing code ...

    $userRole = UserOrganisationRole::where('user_id', auth()->id())
        ->where('organisation_id', $organisation->id)
        ->value('role');

    $canManage = in_array($userRole, ['owner', 'admin', 'commission']);
    $canCreateElection = in_array($userRole, ['owner', 'admin']); // ← NEW

    return inertia('Organisations/Show', [
        // ... existing props ...
        'canCreateElection' => $canCreateElection,
    ]);
}
```

---

### **Step 5: Update ActionButtons.vue**

**File:** `resources/js/Pages/Organisations/Partials/ActionButtons.vue`

```vue
<script setup>
const props = defineProps({
    organisation: Object,
    canManage: Boolean,
    canCreateElection: Boolean,  // ← NEW prop
})

// ... existing code ...
</script>

<template>
    <!-- Step 3: Create Election - Only show if canCreateElection -->
    <Link
        v-if="canCreateElection"
        :href="createElectionLink"
        class="group relative flex flex-col bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 text-left border-2 border-gray-200 hover:border-green-400 focus-within:ring-2 focus-within:ring-green-500 focus-within:ring-offset-2 focus-within:rounded-xl overflow-hidden"
        :aria-label="$t('pages.organisation-show.actions.create_election')"
    >
        <!-- Card content unchanged -->
    </Link>
</template>
```

---

### **Step 6: Update Show.vue to Pass canCreateElection**

**File:** `resources/js/Pages/Organisations/Show.vue`

```vue
<script setup>
const props = defineProps({
    organisation: Object,
    stats: Object,
    demoStatus: Object,
    canManage: Boolean,
    canCreateElection: Boolean,  // ← NEW prop
    officers: Array,
    orgMembers: Array,
    elections: Array,
})
</script>

<template>
    <ActionButtons
        :organisation="organisation"
        :can-manage="canManage"
        :can-create-election="canCreateElection"  // ← NEW
        @appoint-officer="openOfficerModal"
    />
</template>
```

---

### **Step 7: Run Tests (They Should PASS)**

```bash
php artisan test --filter=ElectionCreationTest
```

**Expected:** All 25+ tests passing (existing 18 + new 7)

---

### **Step 8: Manual Testing**

```bash
# Login as Organisation Owner
php artisan test --filter=ElectionCreationTest --testsuite=Feature

# Manual test:
# 1. Login as owner → See "Create Election" button
# 2. Click → Fill form → Submit → Election created
# 3. Login as chief → No "Create Election" button
# 4. Try to access /organisations/{slug}/elections/create directly → 403
```

---

## ✅ **Success Criteria**

| Test | Status |
|------|--------|
| owner_can_create_election | ✅ PASS |
| admin_can_create_election | ✅ PASS |
| chief_cannot_create_election | ✅ PASS |
| deputy_cannot_create_election | ✅ PASS |
| commissioner_cannot_create_election | ✅ PASS |
| regular_member_cannot_create_election | ✅ PASS |
| policy_only_allows_owner_and_admin | ✅ PASS |
| All existing validation tests | ✅ PASS |

---

## 🚀 **Ready to Execute!**

This TDD approach ensures:
- ✅ Tests written first
- ✅ Clear expected behavior
- ✅ No regression
- ✅ Complete coverage

**Proceed with implementation!** 🚀