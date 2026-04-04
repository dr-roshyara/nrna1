# Phase 2 — Application Workflow: Controller, Routes, Events, Vue Pages

## Goal

Implement the complete application lifecycle:
- Public `/apply` form (no admin access required)
- Admin index/show/approve/reject (role-gated)
- Concurrent approval protection via optimistic locking
- Events fired on approval and rejection
- Three Vue pages (Apply, Applications/Index, Applications/Show)

---

## TDD Sequence

```
1. Write MembershipApplicationTest.php  (RED — 14 tests fail)
2. Create events (MembershipApplicationApproved, MembershipApplicationRejected)
3. Add routes to routes/organisations.php
4. Create MembershipApplicationController
5. Create 3 Vue pages
6. All 14 tests GREEN
```

---

## 1. Route Architecture

**File:** `routes/organisations.php`

The most critical architectural decision in Phase 2 is **where to place the public `/apply` routes**.

### The Problem

The org management routes are wrapped in an `ensure.organisation` middleware group that:
1. Reads the organisation from the URL
2. Sets `session('current_organisation_id', $organisation->id)`
3. Checks the user has an active role in the organisation

A user applying for membership does NOT yet have a role in the organisation. Placing `/apply` inside this group causes a **404 or redirect** before the controller is ever reached.

### The Solution: Two Separate Route Groups

```php
// ──────────────────────────────────────────────────────────────────
// GROUP A: Public membership routes (auth + verified, no ensure.organisation)
// ──────────────────────────────────────────────────────────────────
Route::prefix('organisations/{organisation:slug}/membership')
    ->name('organisations.membership.')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('/apply',  [MembershipApplicationController::class, 'create'])->name('apply');
        Route::post('/apply', [MembershipApplicationController::class, 'store'])->name('apply.store');
    });

// ──────────────────────────────────────────────────────────────────
// GROUP B: Protected org management routes (auth + verified + ensure.organisation)
// ──────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified', 'ensure.organisation'])
    ->prefix('organisations/{organisation:slug}')
    ->group(function () {

        // Membership Types (owner only)
        Route::prefix('/membership-types')->name('organisations.membership-types.')->group(function () {
            Route::get('/',           [MembershipTypeController::class, 'index'])->name('index');
            Route::post('/',          [MembershipTypeController::class, 'store'])->name('store');
            Route::put('/{membershipType}',    [MembershipTypeController::class, 'update'])->name('update');
            Route::delete('/{membershipType}', [MembershipTypeController::class, 'destroy'])->name('destroy');
        });

        // Membership Applications (owner, admin, commission)
        Route::prefix('/membership')->name('organisations.membership.')->group(function () {
            Route::get('/applications',                        [MembershipApplicationController::class, 'index'])->name('applications.index');
            Route::get('/applications/{application}',          [MembershipApplicationController::class, 'show'])->name('applications.show');
            Route::patch('/applications/{application}/approve',[MembershipApplicationController::class, 'approve'])->name('applications.approve');
            Route::patch('/applications/{application}/reject', [MembershipApplicationController::class, 'reject'])->name('applications.reject');
        });

        // Member fees and renewals
        Route::prefix('/members/{member}')->name('organisations.members.')->group(function () {
            Route::get('/fees',             [MembershipFeeController::class, 'index'])->name('fees.index');
            Route::post('/fees/{fee}/pay',  [MembershipFeeController::class, 'pay'])->name('fees.pay');
            Route::post('/fees/{fee}/waive',[MembershipFeeController::class, 'waive'])->name('fees.waive');
            Route::post('/renew',           [MembershipRenewalController::class, 'store'])->name('renew');
        });
    });
```

---

## 2. MembershipApplicationController

**File:** `app/Http/Controllers/Membership/MembershipApplicationController.php`

### create()

Renders the apply form with active membership types for the organisation:

```php
public function create(Organisation $organisation): Response
{
    $types = MembershipType::where('organisation_id', $organisation->id)
        ->active()
        ->orderBy('sort_order')
        ->get(['id', 'name', 'fee_amount', 'fee_currency', 'duration_months', 'description']);

    return Inertia::render('Organisations/Membership/Apply', [
        'organisation' => $organisation->only('id', 'name', 'slug'),
        'types'        => $types,
    ]);
}
```

### store() — The Public Route Problem

Because `/apply` runs WITHOUT `ensure.organisation`, `session('current_organisation_id')` is NOT set. This means any model using `BelongsToTenant` global scope will return empty results.

**Both guards must use `withoutGlobalScopes()`:**

```php
public function store(Request $request, Organisation $organisation): RedirectResponse
{
    $validated = $request->validate([
        'membership_type_id' => ['required', 'uuid'],
        'application_data'   => ['nullable', 'array'],
    ]);

    $user = $request->user();

    // Guard 1: already an active member
    $alreadyMember = OrganisationUser::withoutGlobalScopes()  // ← must bypass tenant scope
        ->where('organisation_id', $organisation->id)          // ← must be explicit
        ->where('user_id', $user->id)
        ->where('status', 'active')
        ->whereHas('member', fn ($q) =>
            $q->withoutGlobalScopes()                          // ← and here too
              ->where('organisation_id', $organisation->id)
              ->where('status', 'active')
        )
        ->exists();

    if ($alreadyMember) {
        return back()->withErrors(['error' => 'You are already an active member of this organisation.']);
    }

    // Guard 2: already has a pending application
    $hasPending = MembershipApplication::withoutGlobalScopes() // ← bypass tenant scope
        ->where('organisation_id', $organisation->id)
        ->where('user_id', $user->id)
        ->whereIn('status', ['draft', 'submitted', 'under_review'])
        ->exists();

    if ($hasPending) {
        return back()->withErrors(['error' => 'You already have a pending application.']);
    }

    // Verify type belongs to this org and is active
    $type = MembershipType::where('id', $validated['membership_type_id'])
        ->where('organisation_id', $organisation->id)
        ->where('is_active', true)
        ->first(); // ← first(), not firstOrFail() — returns redirect, not 404

    if (!$type) {
        return back()->withErrors(['membership_type_id' => 'The selected membership type is not available.']);
    }

    MembershipApplication::create([
        'id'                 => (string) Str::uuid(),
        'organisation_id'    => $organisation->id,
        'user_id'            => $user->id,
        'membership_type_id' => $type->id,
        'status'             => 'submitted',
        'application_data'   => $validated['application_data'] ?? null,
        'expires_at'         => now()->addDays(config('membership.application_expiry_days', 30)),
        'submitted_at'       => now(),
    ]);

    return redirect()->route('organisations.voter-hub', $organisation->slug)
        ->with('success', 'Your membership application has been submitted.');
}
```

### approve() — Optimistic Locking + Side Effects

```php
public function approve(Request $request, Organisation $organisation, MembershipApplication $application): RedirectResponse
{
    $this->authorizeForOrg($request->user(), $organisation, 'approveApplication');
    abort_if($application->organisation_id !== $organisation->id, 404);

    if (!$application->isPending()) {
        return back()->withErrors(['error' => 'This application has already been processed.']);
    }

    try {
        DB::transaction(function () use ($application, $request, $organisation) {
            $application->approve($request->user()->id);  // ← throws if concurrent

            $type = $application->membershipType;

            // 1. Create OrganisationUser (or reactivate existing)
            $orgUser = OrganisationUser::firstOrCreate(
                ['organisation_id' => $organisation->id, 'user_id' => $application->user_id],
                ['id' => (string) Str::uuid(), 'role' => 'member', 'status' => 'active']
            );

            // 2. Create UserOrganisationRole (RBAC entry)
            UserOrganisationRole::firstOrCreate(
                ['organisation_id' => $organisation->id, 'user_id' => $application->user_id],
                ['id' => (string) Str::uuid(), 'role' => 'member']
            );

            // 3. Create Member record
            $expiresAt = $type->duration_months ? now()->addMonths($type->duration_months) : null;
            $member = Member::create([
                'id'                    => (string) Str::uuid(),
                'organisation_id'       => $organisation->id,
                'organisation_user_id'  => $orgUser->id,
                'status'                => 'active',
                'joined_at'             => now(),
                'membership_expires_at' => $expiresAt,
                'created_by'            => $request->user()->id,
            ]);

            // 4. Create pending fee (with fee snapshot)
            MembershipFee::create([
                'id'                 => (string) Str::uuid(),
                'organisation_id'    => $organisation->id,
                'member_id'          => $member->id,
                'membership_type_id' => $type->id,
                'amount'             => $type->fee_amount,
                'currency'           => $type->fee_currency,
                'fee_amount_at_time' => $type->fee_amount,   // snapshot
                'currency_at_time'   => $type->fee_currency, // snapshot
                'status'             => 'pending',
                'recorded_by'        => $request->user()->id,
            ]);

            // 5. Fire event
            event(new MembershipApplicationApproved($application));
        });
    } catch (ApplicationAlreadyProcessedException) {
        return back()->withErrors(['error' => 'This application was already processed by another administrator.']);
    }

    return redirect()->route('organisations.membership.applications.index', $organisation->slug)
        ->with('success', 'Application approved successfully.');
}
```

#### Approval Side Effects (Order Matters)

```
1. application.approve()      → status = approved, lock_version++
2. OrganisationUser created   → user now belongs to org
3. UserOrganisationRole created → user now has 'member' role (RBAC)
4. Member created             → membership record with expiry
5. MembershipFee created      → pending fee with frozen snapshot
6. MembershipApplicationApproved event fired
```

All of this runs inside a single `DB::transaction`. If any step fails, all steps are rolled back.

---

## 3. Events

### MembershipApplicationApproved

**File:** `app/Events/Membership/MembershipApplicationApproved.php`

```php
namespace App\Events\Membership;

use App\Models\MembershipApplication;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MembershipApplicationApproved
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly MembershipApplication $application
    ) {}
}
```

### MembershipApplicationRejected

**File:** `app/Events/Membership/MembershipApplicationRejected.php`

Same structure, fired in `reject()` after the `reject()` model method completes.

---

## 4. Authorization Helper

Rather than registering the policy in `AuthServiceProvider`, the controller instantiates `MembershipPolicy` directly:

```php
private function authorizeForOrg($user, Organisation $organisation, string $ability): void
{
    $policy = new MembershipPolicy();

    $allowed = match ($ability) {
        'viewApplications'   => $policy->viewApplications($user, $organisation),
        'approveApplication' => $policy->approveApplication($user, $organisation),
        'rejectApplication'  => $policy->rejectApplication($user, $organisation),
        default              => false,
    };

    abort_if(!$allowed, 403);
}
```

---

## 5. Vue Pages

### Apply.vue

**File:** `resources/js/Pages/Organisations/Membership/Apply.vue`

Renders a type selection form. On submit, uses `router.post()` (Inertia 2.0):

```vue
<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props  = defineProps({ organisation: Object, types: Array });
const form   = ref({ membership_type_id: '', application_data: {} });
const errors = ref({});

const submit = () => {
    router.post(
        route('organisations.membership.apply.store', props.organisation.slug),
        form.value,
        {
            onError: (e) => errors.value = e,
            onSuccess: () => { /* redirected by controller */ },
        }
    );
};
</script>
```

### Applications/Index.vue

**File:** `resources/js/Pages/Organisations/Membership/Applications/Index.vue`

Paginated list of applications with status badges and links to show page.

### Applications/Show.vue

**File:** `resources/js/Pages/Organisations/Membership/Applications/Show.vue`

Application detail view with approve/reject action buttons. Reject includes a textarea for the rejection reason.

---

## 6. Test Coverage — Phase 2

**14 feature tests in `tests/Feature/Membership/MembershipApplicationTest.php`:**

| Test | What It Verifies |
|------|-----------------|
| `guest_cannot_submit_application` | 302 redirect to login |
| `existing_member_cannot_apply_again` | Returns error: already a member |
| `user_with_pending_cannot_apply_again` | Returns error: already pending |
| `valid_application_creates_record` | DB has application with status=submitted |
| `admin_can_view_all_applications` | 200 with Inertia component |
| `member_cannot_view_other_applications` | 403 |
| `approve_creates_organisation_user_and_member` | DB assertions for OrganisationUser + Member |
| `approve_creates_pending_membership_fee` | DB assertion for fee with snapshot |
| `approve_fires_MembershipApplicationApproved_event` | `Event::assertDispatched(...)` |
| `reject_sets_status_to_rejected_with_reason` | DB assertion for status=rejected |
| `reject_fires_MembershipApplicationRejected_event` | `Event::assertDispatched(...)` |
| `approved_application_cannot_be_approved_again` | Returns error: already processed |
| `concurrent_approval_throws_ApplicationAlreadyProcessedException` | Manually set lock_version mismatch |
| `expired_application_auto_rejected_by_daily_command` | Run artisan command, assert status=rejected |

---

## Common Mistakes

### Mistake 1: Placing /apply inside the ensure.organisation group

```php
// WRONG — non-members cannot pass ensure.organisation
Route::middleware(['auth', 'verified', 'ensure.organisation'])
    ->group(function () {
        Route::get('/apply', [...]); // ← user without org role hits 403/redirect
    });

// CORRECT — two separate groups
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/organisations/{org:slug}/membership/apply', [...]);
});
Route::middleware(['auth', 'verified', 'ensure.organisation'])->group(function () {
    // Admin routes here
});
```

### Mistake 2: Using firstOrFail() for type lookup on public route

```php
// WRONG — inactive type returns 404, not a user-friendly error
$type = MembershipType::where('id', $id)->where('is_active', true)->firstOrFail();

// CORRECT — return redirect with error message
$type = MembershipType::where('id', $id)->where('is_active', true)->first();
if (!$type) {
    return back()->withErrors(['membership_type_id' => 'The selected membership type is not available.']);
}
```

### Mistake 3: Not faking events in tests

```php
// WRONG — real listeners fire (emails sent, queues dispatched)
$this->post(route('...approve', [$org->slug, $app->id]));

// CORRECT — fake before the action
Event::fake([MembershipApplicationApproved::class]);
$this->patch(route('...approve', [$org->slug, $app->id]));
Event::assertDispatched(MembershipApplicationApproved::class);
```

### Mistake 4: Concurrent approval test — using real race condition

Testing concurrent approval does not require threads. Simulate by manually decrementing `lock_version` after loading the application:

```php
/** @test */
public function concurrent_approval_throws_exception(): void
{
    $application = MembershipApplication::factory()->create([...]);

    // Simulate another admin approving first by changing lock_version
    DB::table('membership_applications')
        ->where('id', $application->id)
        ->update(['lock_version' => 999]);

    // Now our approval attempt fails (lock_version mismatch)
    $response = $this->actingAs($this->adminUser)
        ->patch(route('organisations.membership.applications.approve', [$org->slug, $application->id]));

    $response->assertSessionHasErrors(['error']);
}
```
