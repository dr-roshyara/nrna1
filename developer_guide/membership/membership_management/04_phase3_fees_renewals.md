# Phase 3 — Fees and Renewals

## Goal

Build the fee payment workflow and the renewal system on top of the approved membership:
- **MembershipFeeController** — index, pay (with idempotency), waive
- **MembershipRenewalController** — store (admin + self-service)
- **Member model** — `canSelfRenew()` and `endMembership()` methods
- **Events** — `MembershipFeePaid`, `MembershipRenewed`
- **2 Vue pages** — Fees list, RenewModal

---

## TDD Sequence

```
1. Write MembershipFeeTest.php (RED — 7 tests fail)
2. Write MembershipRenewalTest.php (RED — 7 tests fail)
3. Write MemberTest.php unit tests (RED — 8 tests fail)
4. Create MembershipFeePaid and MembershipRenewed events
5. Create MembershipFeeController
6. Create MembershipRenewalController
7. Extend Member model (canSelfRenew, endMembership)
8. Create Vue pages
9. All 22 tests GREEN
```

---

## 1. MembershipFeeController

**File:** `app/Http/Controllers/Membership/MembershipFeeController.php`

### index()

```php
public function index(Request $request, Organisation $organisation, Member $member): Response
{
    $this->authorizeRecordPayment($request->user(), $organisation);
    abort_if($member->organisation_id !== $organisation->id, 404);

    $fees = MembershipFee::where('member_id', $member->id)
        ->with('membershipType')
        ->orderByDesc('created_at')
        ->paginate(20);

    return Inertia::render('Organisations/Membership/Member/Fees', [
        'organisation' => $organisation->only('id', 'name', 'slug'),
        'member'       => $member->load('organisationUser.user'),
        'fees'         => $fees,
    ]);
}
```

### pay() — Idempotency Protection

```php
public function pay(Request $request, Organisation $organisation, Member $member, MembershipFee $fee): RedirectResponse
{
    $this->authorizeRecordPayment($request->user(), $organisation);
    abort_if($fee->member_id !== $member->id, 404);

    if ($fee->status !== 'pending') {
        return back()->withErrors(['error' => 'This fee has already been processed.']);
    }

    $validated = $request->validate([
        'payment_method'    => ['required', 'string', 'max:50'],
        'payment_reference' => ['nullable', 'string', 'max:200'],
        'idempotency_key'   => ['nullable', 'string', 'max:100'],
    ]);

    // Idempotency check: reject if the same key was used on a DIFFERENT fee
    if (!empty($validated['idempotency_key'])) {
        $duplicate = MembershipFee::where('idempotency_key', $validated['idempotency_key'])
            ->where('id', '!=', $fee->id)
            ->exists();

        if ($duplicate) {
            return back()->withErrors(['error' => 'Duplicate payment detected (idempotency key already used).']);
        }
    }

    DB::transaction(function () use ($fee, $validated, $request) {
        $fee->update([
            'status'            => 'paid',
            'paid_at'           => now(),
            'payment_method'    => $validated['payment_method'],
            'payment_reference' => $validated['payment_reference'] ?? null,
            'idempotency_key'   => $validated['idempotency_key'] ?? null,
            'recorded_by'       => $request->user()->id,
        ]);

        event(new MembershipFeePaid($fee->fresh()));
    });

    return back()->with('success', 'Payment recorded successfully.');
}
```

#### How Idempotency Works

The `idempotency_key` column has a `UNIQUE` constraint. The controller enforces this at the application layer (rather than relying solely on the DB constraint) to return a friendly error instead of a 500.

```
Admin records payment for Fee A with key="PAY-2025-001"
  → fee_a.idempotency_key = 'PAY-2025-001' ✓

Admin accidentally submits again with the same key for Fee B
  → WHERE idempotency_key='PAY-2025-001' AND id != fee_b.id → found!
  → Returns 'Duplicate payment detected' error
```

The same key CAN be re-submitted for the same fee (idempotent retry) — the `AND id != fee->id` clause allows this.

### waive()

```php
public function waive(Request $request, Organisation $organisation, Member $member, MembershipFee $fee): RedirectResponse
{
    $this->authorizeRecordPayment($request->user(), $organisation);
    abort_if($fee->member_id !== $member->id, 404);

    if ($fee->status !== 'pending') {
        return back()->withErrors(['error' => 'This fee has already been processed.']);
    }

    $fee->update([
        'status'      => 'waived',
        'recorded_by' => $request->user()->id,
    ]);

    return back()->with('success', 'Fee waived successfully.');
}
```

---

## 2. MembershipRenewalController

**File:** `app/Http/Controllers/Membership/MembershipRenewalController.php`

```php
public function store(Request $request, Organisation $organisation, Member $member): RedirectResponse
{
    abort_if($member->organisation_id !== $organisation->id, 404);

    $user   = $request->user();
    $policy = new MembershipPolicy();

    // Determine if this is a self-renewal
    $isSelf = $user->id === $member->organisationUser->user_id;

    if (!$policy->initiateRenewal($user, $organisation, $isSelf)) {
        abort(403);
    }

    // Self-renewal window check
    if ($isSelf && !$member->canSelfRenew()) {
        return back()->withErrors(['error' => 'You are not eligible to self-renew at this time.']);
    }

    // Lifetime members cannot be renewed by anyone
    if ($member->membership_expires_at === null) {
        return back()->withErrors(['error' => 'Lifetime members cannot be renewed.']);
    }

    $validated = $request->validate([
        'membership_type_id' => ['required', 'uuid'],
        'notes'              => ['nullable', 'string', 'max:1000'],
    ]);

    $type = MembershipType::where('id', $validated['membership_type_id'])
        ->where('organisation_id', $organisation->id)
        ->where('is_active', true)
        ->firstOrFail();

    DB::transaction(function () use ($member, $type, $user, $validated) {
        $oldExpiry = $member->membership_expires_at;

        // Extend from old expiry if still in future; otherwise from now
        $base      = $oldExpiry->isFuture() ? $oldExpiry : now();
        $newExpiry = $base->addMonths($type->duration_months);

        $fee = MembershipFee::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $member->organisation_id,
            'member_id'          => $member->id,
            'membership_type_id' => $type->id,
            'amount'             => $type->fee_amount,
            'currency'           => $type->fee_currency,
            'fee_amount_at_time' => $type->fee_amount,   // snapshot
            'currency_at_time'   => $type->fee_currency, // snapshot
            'status'             => 'pending',
            'recorded_by'        => $user->id,
        ]);

        $renewal = MembershipRenewal::create([
            'id'                 => (string) Str::uuid(),
            'organisation_id'    => $member->organisation_id,
            'member_id'          => $member->id,
            'membership_type_id' => $type->id,
            'renewed_by'         => $user->id,
            'old_expires_at'     => $oldExpiry,
            'new_expires_at'     => $newExpiry,
            'fee_id'             => $fee->id,
            'notes'              => $validated['notes'] ?? null,
        ]);

        $member->update([
            'membership_expires_at' => $newExpiry,
            'last_renewed_at'       => now(),
        ]);

        event(new MembershipRenewed($renewal));
    });

    return back()->with('success', 'Membership renewed successfully.');
}
```

### Renewal Expiry Calculation

```
Member expires 2025-06-01, renews on 2025-05-15 (still active):
  base = 2025-06-01 (future)
  newExpiry = 2025-06-01 + 12 months = 2026-06-01   ✓ (stacks from old expiry)

Member expired 2025-03-01, renews on 2025-04-15 (lapsed):
  base = now() (2025-04-15) — old expiry is in the past
  newExpiry = 2025-04-15 + 12 months = 2026-04-15   ✓ (starts fresh from today)
```

### Self-Renewal Policy

The `initiateRenewal` policy method accepts an `$isSelf` boolean:

```php
public function initiateRenewal(User $user, Organisation $organisation, bool $isSelf = false): bool
{
    if ($this->hasRole($user, $organisation, ['owner', 'admin'])) {
        return true;
    }

    // Member can only renew their own membership
    return $isSelf && $this->hasRole($user, $organisation, ['member']);
}
```

The controller determines `$isSelf`:

```php
$isSelf = $user->id === $member->organisationUser->user_id;
```

This compares the logged-in user's ID against the user linked to the member being renewed.

---

## 3. Member Model Business Methods

**File:** `app/Models/Member.php`

### canSelfRenew()

```php
public function canSelfRenew(): bool
{
    if ($this->status !== 'active' || $this->membership_expires_at === null) {
        return false; // lifetime members cannot renew
    }

    $windowDays = config('membership.self_renewal_window_days', 90);

    return $this->membership_expires_at->isAfter(now()->subDays($windowDays));
}
```

The self-renewal window logic:

```
now()               = 2025-04-15
subDays(90)         = 2025-01-15
membership_expires_at > 2025-01-15 → can renew
membership_expires_at < 2025-01-15 → cannot renew (too long lapsed)
```

Effectively: "If the member expired more than 90 days ago, they can no longer self-renew."

### endMembership()

```php
public function endMembership(?string $reason = null): void
{
    DB::transaction(function () use ($reason) {
        // 1. Mark member as ended
        $this->update([
            'status'     => 'ended',
            'ended_at'   => now(),
            'end_reason' => $reason,
        ]);

        // 2. Waive all pending fees
        $this->fees()->where('status', 'pending')->update(['status' => 'waived']);

        // 3. Remove from active elections
        // CRITICAL: election_memberships has NO member_id column — use user_id
        $userId = $this->organisationUser?->user_id;
        if ($userId) {
            ElectionMembership::where('user_id', $userId)
                ->where('status', 'active')
                ->update(['status' => 'removed']);
        }
    });
}
```

> **Why user_id and not member_id?** The `election_memberships` table was designed before the membership system existed. It links directly to `users`, not to `members`. Walking the relationship `member → organisationUser → user_id` is the correct path.

---

## 4. Events

### MembershipFeePaid

**File:** `app/Events/Membership/MembershipFeePaid.php`

```php
class MembershipFeePaid
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly MembershipFee $fee
    ) {}
}
```

### MembershipRenewed

**File:** `app/Events/Membership/MembershipRenewed.php`

```php
class MembershipRenewed
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly MembershipRenewal $renewal
    ) {}
}
```

---

## 5. ENUM Migration (Critical)

The `members.status` column was originally `ENUM('active', 'expired', 'suspended')`. The `endMembership()` method sets `status = 'ended'`, which would fail with a database error unless the ENUM is extended.

**File:** `database/migrations/2026_04_03_155711_add_ended_status_to_members_table.php`

```php
public function up(): void
{
    DB::statement(
        "ALTER TABLE `members` MODIFY COLUMN `status` ENUM('active', 'expired', 'suspended', 'ended') NOT NULL DEFAULT 'active'"
    );
}

public function down(): void
{
    DB::statement(
        "ALTER TABLE `members` MODIFY COLUMN `status` ENUM('active', 'expired', 'suspended') NOT NULL DEFAULT 'active'"
    );
}
```

> **Must run on the test database too.** If `php artisan migrate` does not apply this to the test database used by `RefreshDatabase`, run `php artisan migrate --env=testing` or ensure the test database is fresh.

---

## 6. Test Coverage — Phase 3

### MembershipFeeTest (7 tests)

| Test | What It Verifies |
|------|-----------------|
| `admin_can_record_payment` | `paid_at` set, status=paid |
| `member_cannot_record_own_payment` | 403 response |
| `payment_fires_MembershipFeePaid_event` | Event dispatched |
| `waive_sets_status_waived` | status=waived in DB |
| `already_paid_fee_cannot_be_paid_again` | Returns error |
| `duplicate_idempotency_key_returns_error` | Returns error with 'Duplicate payment' |
| `fee_with_same_idempotency_key_retry_succeeds` | Same key on same fee → succeeds |

### MembershipRenewalTest (7 tests)

| Test | What It Verifies |
|------|-----------------|
| `admin_can_renew_any_member` | new_expires_at updated in DB |
| `member_can_self_renew_within_window` | succeeds for active member within 90 days |
| `member_cannot_renew_more_than_90_days_after_expiry` | Returns error |
| `renewal_updates_member_expires_at` | DB assertion on member |
| `renewal_creates_linked_fee` | Fee created with renewal.fee_id set |
| `renewal_fires_MembershipRenewed_event` | Event dispatched |
| `lifetime_member_cannot_be_renewed` | Returns error |

### MemberTest (8 unit tests)

| Test | What It Verifies |
|------|-----------------|
| `can_self_renew_before_expiry` | returns true |
| `can_self_renew_within_90_day_window` | returns true at day 89 |
| `cannot_self_renew_at_91_days_after_expiry` | returns false |
| `lifetime_member_cannot_self_renew` | returns false when expires_at=null |
| `end_membership_sets_status_ended` | status=ended in DB |
| `end_membership_sets_ended_at` | ended_at not null |
| `end_membership_waives_pending_fees` | fee status=waived |
| `end_membership_removes_from_active_elections` | ElectionMembership status=removed |

---

## 7. Test Setup: UserOrganisationRole Requirement

Tests that use `Member` with `ensure.organisation` middleware require a `UserOrganisationRole` entry for the test user. Without it, the user fails the middleware before reaching the controller.

```php
protected function setUp(): void
{
    parent::setUp();

    $this->org = Organisation::factory()->create(['type' => 'tenant']);

    $this->adminUser = User::factory()->create();
    UserOrganisationRole::create([
        'id'              => Str::uuid(),
        'user_id'         => $this->adminUser->id,
        'organisation_id' => $this->org->id,
        'role'            => 'admin',
    ]);

    $this->memberUser = User::factory()->create();
    UserOrganisationRole::create([
        'id'              => Str::uuid(),
        'user_id'         => $this->memberUser->id,
        'organisation_id' => $this->org->id,
        'role'            => 'member',  // ← passes middleware, fails 403 for admin routes
    ]);
    // ... create OrganisationUser, Member, MembershipFee, etc.
}
```

---

## Common Mistakes

### Mistake 1: Idempotency check rejects a retry on the same fee

```php
// WRONG — blocks any re-submission with the same key
MembershipFee::where('idempotency_key', $key)->exists()

// CORRECT — only blocks if the key was used on a DIFFERENT fee
MembershipFee::where('idempotency_key', $key)
    ->where('id', '!=', $fee->id)
    ->exists()
```

### Mistake 2: canSelfRenew() returning wrong value for expired members

```php
// WRONG — compares wrong direction
return $this->membership_expires_at->isBefore(now()->addDays($windowDays));
// ↑ true for any membership that expires before 90 days from now (too broad)

// CORRECT — check if expiry is within the window from now going backwards
return $this->membership_expires_at->isAfter(now()->subDays($windowDays));
// ↑ true only if expiry date is less than 90 days in the past
```

### Mistake 3: endMembership() using member_id on election_memberships

```php
// WRONG — column does not exist on election_memberships
ElectionMembership::where('member_id', $this->id)->update(['status' => 'removed']);

// CORRECT — use user_id via organisationUser relationship
$userId = $this->organisationUser?->user_id;
if ($userId) {
    ElectionMembership::where('user_id', $userId)->update(['status' => 'removed']);
}
```

### Mistake 4: Renewal extending from now when member is still active

```php
// WRONG — loses remaining active period
$newExpiry = now()->addMonths($type->duration_months);

// CORRECT — extend from old expiry if it's still in the future
$base      = $oldExpiry->isFuture() ? $oldExpiry : now();
$newExpiry = $base->addMonths($type->duration_months);
```
