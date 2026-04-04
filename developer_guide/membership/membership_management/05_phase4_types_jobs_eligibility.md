# Phase 4 — Types Management, Expiry Command, and Election Eligibility

## Goal

Complete the system with:
- **MembershipTypeController** — full CRUD, owner-only, slug-unique-per-org
- **ProcessMembershipExpiryCommand** — daily Artisan command, auto-rejects stale applications, marks overdue fees
- **ElectionMembership::scopeEligible()** — hardened to also check `members.status = active`
- **46 tests** across 3 test files

---

## TDD Sequence

```
1. Write MembershipTypeTest.php (RED — 6 tests fail)
2. Write ProcessMembershipExpiryJobTest.php (RED — 4 tests fail)
3. Write ElectionMembershipTest.php extensions (RED — 4 new tests fail)
4. Create MembershipTypeController
5. Create ProcessMembershipExpiryCommand
6. Register command schedule in routes/console.php
7. Extend ElectionMembership::scopeEligible()
8. All 46 tests GREEN
```

---

## 1. MembershipTypeController

**File:** `app/Http/Controllers/Membership/MembershipTypeController.php`

All five methods are gated to `owner` only via `MembershipPolicy::manageMembershipTypes()`.

### index()

```php
public function index(Request $request, Organisation $organisation): Response
{
    $this->authorizeManageTypes($request->user(), $organisation);

    $types = MembershipType::where('organisation_id', $organisation->id)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->paginate(20);

    return Inertia::render('Organisations/Membership/Types/Index', [
        'organisation' => $organisation->only('id', 'name', 'slug'),
        'types'        => $types,
    ]);
}
```

### store()

Validates slug uniqueness scoped to the organisation:

```php
$validated = $request->validate([
    'name'              => ['required', 'string', 'max:100'],
    'slug'              => [
        'required', 'string', 'max:100',
        Rule::unique('membership_types')->where('organisation_id', $organisation->id),
    ],
    'description'       => ['nullable', 'string'],
    'fee_amount'        => ['required', 'numeric', 'min:0'],
    'fee_currency'      => ['required', 'string', 'size:3'],
    'duration_months'   => ['nullable', 'integer', 'min:1'],
    'requires_approval' => ['boolean'],
    'is_active'         => ['boolean'],
    'sort_order'        => ['integer', 'min:0'],
]);

MembershipType::create([
    'id'              => (string) Str::uuid(),
    'organisation_id' => $organisation->id,
    // ... all validated fields
    'created_by'      => $request->user()->id,
]);
```

### update()

Uses `Rule::unique(...)->ignore($membershipType->id)` to allow updating a type without its own slug triggering a uniqueness violation:

```php
$validated = $request->validate([
    'slug' => [
        'sometimes', 'string', 'max:100',
        Rule::unique('membership_types')
            ->where('organisation_id', $organisation->id)
            ->ignore($membershipType->id),
    ],
    // ...
]);

$membershipType->update($validated);
```

### destroy()

Prevents deletion if the type has any associated applications or fees (data integrity guard):

```php
public function destroy(Request $request, Organisation $organisation, MembershipType $membershipType): RedirectResponse
{
    $this->authorizeManageTypes($request->user(), $organisation);
    abort_if($membershipType->organisation_id !== $organisation->id, 404);

    if ($membershipType->applications()->exists() || $membershipType->fees()->exists()) {
        return back()->withErrors(['error' => 'Cannot delete a type with existing applications or fees.']);
    }

    $membershipType->delete(); // soft delete

    return back()->with('success', 'Membership type deleted successfully.');
}
```

### Authorization Helper

> **Critical naming:** Do NOT name this method `authorize()` — it collides with the `authorize()` method inherited from `Illuminate\Foundation\Auth\Access\AuthorizesRequests` (used via the base `Controller`). Use `authorizeManageTypes()`:

```php
private function authorizeManageTypes($user, Organisation $organisation): void
{
    abort_if(!(new MembershipPolicy())->manageMembershipTypes($user, $organisation), 403);
}
```

---

## 2. ProcessMembershipExpiryCommand

**File:** `app/Console/Commands/ProcessMembershipExpiryCommand.php`

```php
namespace App\Console\Commands;

use App\Models\MembershipApplication;
use Illuminate\Console\Command;

class ProcessMembershipExpiryCommand extends Command
{
    protected $signature   = 'membership:process-expiry';
    protected $description = 'Auto-reject expired membership applications and mark overdue fees.';

    public function handle(): int
    {
        // 1. Auto-reject stale applications
        $count = MembershipApplication::query()
            ->whereIn('status', ['submitted', 'under_review', 'draft'])
            ->where('expires_at', '<', now())
            ->update([
                'status'           => 'rejected',
                'rejection_reason' => 'Application expired automatically.',
                'reviewed_at'      => now(),
            ]);

        $this->info("Rejected {$count} expired membership application(s).");

        // 2. Mark overdue fees
        $overdueFees = \App\Models\MembershipFee::overdue()->update(['status' => 'overdue']);
        $this->info("Marked {$overdueFees} overdue fee(s).");

        return Command::SUCCESS;
    }
}
```

### What It Does

| Step | Query | Effect |
|------|-------|--------|
| 1 | `status IN (submitted, under_review, draft) AND expires_at < now()` | `status = rejected`, `rejection_reason = 'Application expired automatically.'` |
| 2 | `status = pending AND due_date < now()` (`scopeOverdue`) | `status = overdue` |

**Note:** The command does NOT fire `MembershipApplicationRejected` events for bulk auto-rejections. This is intentional — firing individual events per application at scale would queue thousands of notification emails. If notifications are needed for auto-rejections, a separate event with a different listener (e.g., batch digest) should be introduced.

### Scheduling

**File:** `routes/console.php`

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('membership:process-expiry')->daily();
```

In Laravel 11+, scheduling is defined in `routes/console.php` rather than in `app/Console/Kernel.php`.

### Running Manually

```bash
php artisan membership:process-expiry
```

Example output:
```
Rejected 3 expired membership application(s).
Marked 7 overdue fee(s).
```

---

## 3. ElectionMembership::scopeEligible() — Hardened

**File:** `app/Models/ElectionMembership.php`

### The Problem (Issue 3)

The original `scopeEligible()` only checked `election_memberships.status = active` and whether the membership's own `expires_at` had passed. It did NOT check whether the underlying member record was still active.

A member whose membership had expired (but whose `ElectionMembership` record hadn't been updated) would still appear eligible.

### The Fix

```php
public function scopeEligible($query)
{
    return $query->where('election_memberships.status', 'active')
        ->where(function ($q) {
            $q->whereNull('election_memberships.expires_at')
              ->orWhere('election_memberships.expires_at', '>', now());
        })
        // Also enforce the member's own status (Issue 3 fix)
        ->whereExists(function ($sub) {
            $sub->select(\DB::raw(1))
                ->from('organisation_users')
                ->join('members', 'members.organisation_user_id', '=', 'organisation_users.id')
                ->whereColumn('organisation_users.user_id', 'election_memberships.user_id')
                ->whereColumn('organisation_users.organisation_id', 'election_memberships.organisation_id')
                ->where('members.status', 'active')
                ->where(function ($mq) {
                    $mq->whereNull('members.membership_expires_at')
                       ->orWhere('members.membership_expires_at', '>', now());
                });
        });
}
```

### What the EXISTS Subquery Does

```sql
EXISTS (
    SELECT 1
    FROM organisation_users
    JOIN members ON members.organisation_user_id = organisation_users.id
    WHERE organisation_users.user_id = election_memberships.user_id
      AND organisation_users.organisation_id = election_memberships.organisation_id
      AND members.status = 'active'
      AND (
          members.membership_expires_at IS NULL           -- lifetime member
          OR members.membership_expires_at > now()        -- active, not yet expired
      )
)
```

This ensures that even if `election_memberships.status = active`, the voter is only eligible if:
1. They have an `OrganisationUser` record in this org
2. That `OrganisationUser` has an active `Member` record
3. That `Member` is not expired

### Before vs After

| Scenario | Before Fix | After Fix |
|----------|-----------|-----------|
| Member active, ElectionMembership active | ✓ eligible | ✓ eligible |
| Member expired, ElectionMembership still active | ✓ eligible (WRONG) | ✗ not eligible |
| Member active, no Member record | ✓ eligible (WRONG) | ✗ not eligible |
| Lifetime member | ✓ eligible | ✓ eligible |

---

## 4. Test Coverage — Phase 4

### MembershipTypeTest (6 tests)

**File:** `tests/Feature/Membership/MembershipTypeTest.php`

| Test | What It Verifies |
|------|-----------------|
| `only_owner_can_create_type` | admin gets 403; owner creates successfully |
| `only_owner_can_update_type` | admin gets 403; owner updates successfully |
| `only_owner_can_delete_type` | admin gets 403; owner deletes successfully |
| `slug_is_unique_per_organisation` | duplicate slug within same org → validation error |
| `deactivated_type_cannot_be_used_for_new_applications` | apply with inactive type → error |
| `existing_members_unaffected_by_type_deactivation` | deactivate type → existing active members unchanged |

### ProcessMembershipExpiryJobTest (4 tests)

**File:** `tests/Unit/Jobs/ProcessMembershipExpiryJobTest.php`

```php
class ProcessMembershipExpiryJobTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_rejects_expired_applications(): void
    {
        $app = MembershipApplication::factory()->create([
            'status'     => 'submitted',
            'expires_at' => now()->subDay(), // expired yesterday
        ]);

        $this->artisan('membership:process-expiry');

        $this->assertDatabaseHas('membership_applications', [
            'id'               => $app->id,
            'status'           => 'rejected',
            'rejection_reason' => 'Application expired automatically.',
        ]);
    }

    /** @test */
    public function it_does_not_touch_non_expired_applications(): void
    {
        $app = MembershipApplication::factory()->create([
            'status'     => 'submitted',
            'expires_at' => now()->addDay(), // still valid
        ]);

        $this->artisan('membership:process-expiry');

        $this->assertDatabaseHas('membership_applications', [
            'id'     => $app->id,
            'status' => 'submitted', // unchanged
        ]);
    }

    /** @test */
    public function it_marks_overdue_fees(): void
    {
        $fee = MembershipFee::factory()->create([
            'status'   => 'pending',
            'due_date' => now()->subDay()->toDateString(),
        ]);

        $this->artisan('membership:process-expiry');

        $this->assertDatabaseHas('membership_fees', [
            'id'     => $fee->id,
            'status' => 'overdue',
        ]);
    }

    /** @test */
    public function it_does_not_mark_paid_fees_as_overdue(): void
    {
        $fee = MembershipFee::factory()->create([
            'status'   => 'paid',
            'due_date' => now()->subDay()->toDateString(),
        ]);

        $this->artisan('membership:process-expiry');

        $this->assertDatabaseHas('membership_fees', [
            'id'     => $fee->id,
            'status' => 'paid', // unchanged
        ]);
    }
}
```

### ElectionMembershipTest Extensions (4 new tests)

**File:** `tests/Unit/Models/ElectionMembershipTest.php` (existing file, extended)

```php
/** @test */
public function eligible_scope_excludes_expired_members(): void
{
    // ElectionMembership active, but underlying Member is expired
    $em = $this->createElectionMembershipWithMemberStatus('expired');

    $result = ElectionMembership::eligible()->where('id', $em->id)->exists();

    $this->assertFalse($result);
}

/** @test */
public function eligible_scope_includes_active_members(): void
{
    $em = $this->createElectionMembershipWithMemberStatus('active');

    $result = ElectionMembership::eligible()->where('id', $em->id)->exists();

    $this->assertTrue($result);
}

/** @test */
public function eligible_scope_includes_lifetime_active_members(): void
{
    $em = $this->createElectionMembershipWithMemberStatus('active', null); // null = lifetime

    $result = ElectionMembership::eligible()->where('id', $em->id)->exists();

    $this->assertTrue($result);
}

/** @test */
public function eligible_scope_excludes_membership_expired_at_in_past(): void
{
    $em = $this->createElectionMembershipWithMemberStatus(
        'active',
        now()->subMonth() // membership_expires_at in the past
    );

    $result = ElectionMembership::eligible()->where('id', $em->id)->exists();

    $this->assertFalse($result);
}
```

---

## 5. Vue Page — Types/Index.vue

**File:** `resources/js/Pages/Organisations/Membership/Types/Index.vue`

```vue
<template>
    <div>
        <h1>Membership Types</h1>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Fee</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="type in types.data" :key="type.id">
                    <td>{{ type.name }}</td>
                    <td>{{ type.fee_amount }} {{ type.fee_currency }}</td>
                    <td>{{ type.duration_months ? type.duration_months + ' months' : 'Lifetime' }}</td>
                    <td>{{ type.is_active ? 'Active' : 'Inactive' }}</td>
                    <td>
                        <button @click="edit(type)">Edit</button>
                        <button @click="destroy(type)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    organisation: Object,
    types: Object,
});

const destroy = (type) => {
    if (confirm('Delete this membership type?')) {
        router.delete(
            route('organisations.membership-types.destroy', [props.organisation.slug, type.id])
        );
    }
};
</script>
```

---

## 6. Full Test Run

After all four phases are complete, run the entire suite:

```bash
php artisan test \
  tests/Unit/Policies/MembershipPolicyTest.php \
  tests/Unit/Models/MembershipTypeTest.php \
  tests/Unit/Models/MembershipApplicationTest.php \
  tests/Unit/Models/MembershipFeeTest.php \
  tests/Unit/Models/MembershipRenewalTest.php \
  tests/Unit/Models/MemberTest.php \
  tests/Unit/Models/ElectionMembershipTest.php \
  tests/Unit/Jobs/ProcessMembershipExpiryJobTest.php \
  tests/Feature/Membership/ \
  --no-coverage
```

Expected output:
```
Tests:  135 passed
Time:   ~4.2s
```

---

## Common Mistakes

### Mistake 1: Method collision with base Controller

```php
// WRONG — collides with Illuminate\Foundation\Auth\Access\AuthorizesRequests::authorize()
private function authorize($user, Organisation $org): void { ... }

// CORRECT — use a specific method name
private function authorizeManageTypes($user, Organisation $org): void { ... }
```

### Mistake 2: Slug uniqueness rule doesn't exclude current record on update

```php
// WRONG — updating a type with its own slug triggers a uniqueness error
Rule::unique('membership_types')->where('organisation_id', $org->id)

// CORRECT — ignore the current record
Rule::unique('membership_types')
    ->where('organisation_id', $org->id)
    ->ignore($membershipType->id)
```

### Mistake 3: scopeEligible only checks election_memberships table

The original scope checked only `election_memberships.expires_at`. This misses the case where a member's `members.membership_expires_at` has passed but their ElectionMembership record was never updated. The fix adds a correlated `EXISTS` subquery that joins through `organisation_users` to `members` and checks both `members.status = active` and `members.membership_expires_at`.

### Mistake 4: Scheduling in Kernel.php (Laravel 11+)

```php
// WRONG — Laravel 11 removed Kernel.php scheduling
// app/Console/Kernel.php no longer exists by default

// CORRECT — use routes/console.php
use Illuminate\Support\Facades\Schedule;
Schedule::command('membership:process-expiry')->daily();
```

### Mistake 5: Deleting a type with associated fees (data loss risk)

```php
// WRONG — soft deletes a type while fees still reference it
$membershipType->delete();

// CORRECT — check for associated records first
if ($membershipType->applications()->exists() || $membershipType->fees()->exists()) {
    return back()->withErrors(['error' => 'Cannot delete a type with existing applications or fees.']);
}
$membershipType->delete();
```

---

## End-to-End Verification Checklist

After all phases pass, verify manually:

| Check | How to Verify |
|-------|--------------|
| Non-member can submit application | Visit `/organisations/{slug}/membership/apply` as a logged-in user |
| Admin sees application in list | Login as admin, visit `/organisations/{slug}/membership/applications` |
| Approval creates Member + Fee | Check DB after clicking Approve |
| Fee payment records correctly | Click Pay on a pending fee, check `paid_at` in DB |
| Self-renewal blocked after 90 days | Set `membership_expires_at` to 91 days ago, attempt renewal |
| Daily command rejects stale apps | Set `expires_at` to yesterday, run `php artisan membership:process-expiry` |
| Expired member not eligible for election | Create ElectionMembership, expire Member, run `ElectionMembership::eligible()->exists()` |
| Cross-tenant isolation | Org A admin cannot see Org B applications (global scope + policy) |
