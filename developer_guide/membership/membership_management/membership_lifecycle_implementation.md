# Membership Lifecycle — Implementation Guide

**Branch:** `multitenancy`
**Date:** 2026-04-05
**Tests:** 124 passing · 258 assertions

This guide documents four critical gaps that were identified, tested, and fixed in the membership system.
Each fix follows strict TDD (Red → Green): tests written first, implementation second.

---

## Table of Contents

1. [What Was Broken](#1-what-was-broken)
2. [Phase 1 — membership_type_id & membership_number on Approval](#2-phase-1--membership_type_id--membership_number-on-approval)
3. [Phase 2 — fees_status Recalculation After Payment](#3-phase-2--fees_status-recalculation-after-payment)
4. [Phase 3 — Participant Management (staff / guest / committee)](#4-phase-3--participant-management)
5. [Phase 4 — Automated Active→Expired Transition](#5-phase-4--automated-activeexpired-transition)
6. [Running the Tests](#6-running-the-tests)
7. [Quick Reference: New Routes](#7-quick-reference-new-routes)

---

## 1. What Was Broken

| # | Gap | Symptom | Impact |
|---|-----|---------|--------|
| 1 | `membership_type_id` never set at approval | Approved members had `NULL` type | `voting_rights` accessor always returned wrong value |
| 2 | `fees_status` never updated after fee payment | Paying a fee didn't advance voting rights | Members paid their fees but remained ineligible to vote |
| 3 | No web interface for `OrganisationParticipant` | Staff/guests/committee couldn't be managed | The model existed but was unreachable from the UI |
| 4 | No automated expiry | Members never transitioned `active → expired` | Expired memberships continued to grant voting rights |

---

## 2. Phase 1 — `membership_type_id` & `membership_number` on Approval

### File Modified
`app/Http/Controllers/Membership/MembershipApplicationController.php` — `approve()` method

### The Bug
`Member::create([...])` in `approve()` was missing two fields:
- `membership_type_id` — the `$type` variable was fetched (line ~156) but never passed to `Member::create()`
- `membership_number` — no unique identifier was generated
- `fees_status` — was not initialised to `'unpaid'`

### The Fix

```php
// Before (broken)
$member = Member::create([
    'id'                    => (string) Str::uuid(),
    'organisation_id'       => $organisation->id,
    'organisation_user_id'  => $orgUser->id,
    'status'                => 'active',
    'joined_at'             => now(),
    'membership_expires_at' => $expiresAt,
    'created_by'            => $request->user()->id,
]);

// After (fixed)
$member = Member::create([
    'id'                    => (string) Str::uuid(),
    'organisation_id'       => $organisation->id,
    'organisation_user_id'  => $orgUser->id,
    'membership_type_id'    => $type->id,                        // ← ADDED
    'membership_number'     => 'M' . strtoupper(Str::random(8)), // ← ADDED
    'status'                => 'active',
    'fees_status'           => 'unpaid',                         // ← ADDED
    'joined_at'             => now(),
    'membership_expires_at' => $expiresAt,
    'created_by'            => $request->user()->id,
]);
```

### Why `fees_status = 'unpaid'`?
The `voting_rights` accessor on `Member` checks `fees_status`. If the field is `NULL` the accessor falls through with incorrect results. Initialising it to `'unpaid'` ensures that newly approved members correctly show `voting_rights = 'none'` until their fee is paid.

### Test File
`tests/Feature/Membership/MemberApprovalCreatesCorrectMemberTest.php`

Key assertions:
- Full Member type → `membership_type_id` matches `$type->id`
- Associate type → `membership_type_id` matches associate type id
- `membership_number` is set and starts with `'M'`
- Two approved members get different `membership_number` values
- Freshly approved member has `voting_rights = 'none'` (fee unpaid)
- `member->membershipType` relationship loads correctly

---

## 3. Phase 2 — `fees_status` Recalculation After Payment

### Files Created / Modified

| File | Action |
|------|--------|
| `app/Listeners/Membership/RecalculateMemberFeeStatus.php` | Created |
| `app/Providers/EventServiceProvider.php` | Registered listener |

### The Problem
`MembershipFeeController::pay()` dispatched the `MembershipFeePaid` event, but no listener handled it. As a result, paying a fee never updated `member.fees_status`, so `voting_rights` never advanced from `'none'` to `'full'`.

### How the Listener Works

```php
// app/Listeners/Membership/RecalculateMemberFeeStatus.php

public function handle(MembershipFeePaid $event): void
{
    $member = $event->fee->member;
    if (! $member) { return; }

    $fees = $member->fees()->get(['status']);

    if ($fees->isEmpty()) {
        $member->fees_status = 'unpaid';
        $member->save();
        return;
    }

    $hasPending = $fees->contains('status', 'pending');
    $hasPaid    = $fees->contains('status', 'paid');
    $allWaived  = $fees->every(fn ($f) => in_array($f->status, ['waived', 'exempt']));

    $newStatus = match (true) {
        $allWaived               => 'exempt',
        $hasPaid && !$hasPending => 'paid',
        $hasPaid && $hasPending  => 'partial',
        default                  => 'unpaid',
    };

    $member->fees_status = $newStatus;
    $member->save();
}
```

### `fees_status` State Machine

```
All fees pending              → 'unpaid'
Some paid, some pending       → 'partial'
All paid                      → 'paid'
All waived/exempt             → 'exempt'
```

### Effect on `voting_rights`

`voting_rights` is a computed accessor on `Member`. After the listener runs:

| `fees_status` | `membershipType.grants_voting_rights` | `voting_rights` |
|---------------|--------------------------------------|-----------------|
| `unpaid`      | any                                  | `'none'`        |
| `partial`     | any                                  | `'none'`        |
| `paid`        | `true`                               | `'full'`        |
| `paid`        | `false`                              | `'none'`        |
| `exempt`      | `true`                               | `'full'`        |

### Registration in EventServiceProvider

```php
protected $listen = [
    MembershipFeePaid::class => [
        InvalidateMembershipDashboardCache::class,
        RecalculateMemberFeeStatus::class,   // ← ADDED
    ],
];
```

### Test File
`tests/Feature/Membership/FeeStatusRecalculationTest.php`

Key scenarios tested:
- Paying the only pending fee → `fees_status` becomes `'paid'`
- After `fees_status = 'paid'`, `voting_rights` returns `'full'`
- Paying one of two fees → `fees_status` becomes `'partial'`
- Paying all fees → `fees_status` becomes `'paid'`
- Waiving all fees → `fees_status` becomes `'exempt'`
- Recalculation is isolated — other members in the same org are not affected

---

## 4. Phase 3 — Participant Management

Staff, guests, and election committee members are tracked via `OrganisationParticipant` (separate from `Member`). Before this phase, the model existed but had no web interface.

### Files Created / Modified

| File | Action |
|------|--------|
| `app/Http/Controllers/Membership/OrganisationParticipantController.php` | Created |
| `routes/organisations.php` | Added 3 routes |

### The Three-Concept Rule (Reminder)

```
user_organisation_roles   →  platform access (owner/admin/member role)
organisation_participants →  operational role (staff/guest/election_committee)
members                   →  formal paid membership
```

A person can be all three simultaneously. These are independent concepts.

### New Routes

```
GET    /organisations/{slug}/membership/participants
POST   /organisations/{slug}/membership/participants
DELETE /organisations/{slug}/membership/participants/{participant}
```

Named:
- `organisations.membership.participants.index`
- `organisations.membership.participants.store`
- `organisations.membership.participants.destroy`

### Controller: `OrganisationParticipantController`

**`index()`** — Lists all participants for an organisation, paginated.
- Owner/admin only (403 for commission/member roles)
- Accepts `?type=staff|guest|election_committee` filter
- Accepts `?direction=asc|desc` sort
- Returns stats counts (staff, active guests, committee)

**`store()`** — Creates a new `OrganisationParticipant`.
- Validates: `email` (must exist in `users` table), `participant_type` (in:staff,guest,election_committee), optional `role`, `expires_at`, `permissions`
- Looks up user by email, then creates the record

**`destroy()`** — Soft-deletes the participant record.
- Validates that the participant belongs to the current organisation

### Access Control

The controller uses `UserOrganisationRole` to check the acting user's role:

```php
private function authorizeAdmin(mixed $user, Organisation $organisation): void
{
    $role = UserOrganisationRole::where('user_id', $user->id)
        ->where('organisation_id', $organisation->id)
        ->value('role');

    abort_if(! in_array($role, ['owner', 'admin']), 403);
}
```

### Inertia Page (Frontend)
The Vue page lives at:
`resources/js/Pages/Organisations/Membership/Participants/Index.vue`

It receives these props from `index()`:
```js
{
  organisation: { id, name, slug },
  participants: { data: [...], links, meta },  // paginated
  filters:      { type, direction },
  stats:        { staff, guests, election_committee }
}
```

### Test File
`tests/Feature/Membership/ParticipantManagementTest.php`

Key scenarios tested:
- Owner can list participants
- Admin can list participants
- Commission member cannot create (403)
- Create staff → appears in `staff()` scope
- Create guest with `expires_at` → `isExpired()` returns false initially
- Create election_committee → appears in `electionCommittee()` scope
- Invalid `participant_type` → 422 validation error
- Nonexistent email → 422 validation error
- Owner can soft-delete → record not hard-deleted
- Tenant isolation: cannot see another org's participants

---

## 5. Phase 4 — Automated Active→Expired Transition

### Files Created / Modified

| File | Action |
|------|--------|
| `app/Console/Commands/ExpireMemberships.php` | Created |
| `routes/console.php` | Added `Schedule::command(...)->daily()` |

### The Command

```php
// app/Console/Commands/ExpireMemberships.php

protected $signature   = 'membership:expire';
protected $description = 'Mark active members with a past expiry date as expired';

public function handle(): int
{
    $count = Member::withoutGlobalScopes()
        ->where('status', 'active')
        ->whereNotNull('membership_expires_at')
        ->where('membership_expires_at', '<', today())
        ->update(['status' => 'expired']);

    $this->info("Expired {$count} membership(s).");

    return Command::SUCCESS;
}
```

### Key Design Decisions

**`withoutGlobalScopes()`** — The `BelongsToTenant` global scope requires a session, which is not available in CLI context. This ensures the command processes all organisations.

**`< today()` not `<= today()`** — A member whose `membership_expires_at` is today still has access today. They expire at midnight when tomorrow's run fires. This matches the expectation: "expires on 2026-04-05" means they have access on April 5th.

**`whereNotNull('membership_expires_at')`** — Members with `NULL` expiry are lifetime members and must never be expired.

**Bulk `update()`** — More efficient than loading individual records. No Eloquent model events are fired. If you need events on expiry, change this to a loop with individual `->save()` calls.

### Schedule Registration

```php
// routes/console.php  (Laravel 11 — no Kernel.php)
Schedule::command('membership:expire')->daily();
```

### Running Manually

```bash
php artisan membership:expire
# Output: Expired 3 membership(s).
```

### Effect on Voting Rights

After the command runs, expired members have `status = 'expired'`. The `voting_rights` accessor on `Member` returns `'none'` for any non-active status:

```php
// Member::getVotingRightsAttribute()
if ($this->status !== 'active') {
    return 'none';
}
```

### Test File
`tests/Feature/Membership/ExpireMembershipsCommandTest.php`

Key scenarios tested:
- Member with `membership_expires_at` yesterday → `status` becomes `'expired'`
- Member with `NULL` expires_at (lifetime) → stays `'active'`
- Member already `'expired'` → not double-processed, stays `'expired'`
- Member expiring today (midnight today) → stays `'active'` (strict `<` rule)
- Member with future expiry → stays `'active'`
- Expired member has `voting_rights = 'none'`
- Command output contains the count of expired members

---

## 6. Running the Tests

```bash
# All membership tests (fast feedback loop)
php artisan test tests/Feature/Membership --no-coverage

# Individual phase test files
php artisan test tests/Feature/Membership/MemberApprovalCreatesCorrectMemberTest.php
php artisan test tests/Feature/Membership/FeeStatusRecalculationTest.php
php artisan test tests/Feature/Membership/ParticipantManagementTest.php
php artisan test tests/Feature/Membership/ExpireMembershipsCommandTest.php
```

**Expected output:** 124 tests, 258 assertions, 0 failures.

### Common Test Setup Pattern

All membership tests require:

```php
use RefreshDatabase;

protected function setUp(): void
{
    parent::setUp();
    $this->org  = Organisation::factory()->create(['type' => 'tenant']);
    session(['current_organisation_id' => $this->org->id]);  // ← required by BelongsToTenant scope
    $this->type = MembershipType::factory()->fullMember()->create([
        'organisation_id' => $this->org->id,
    ]);
}
```

Without `session(['current_organisation_id' => ...])` the global scope filters all queries and your factories will appear to create empty results.

---

## 7. Quick Reference: New Routes

| Method | URI | Name | Controller | Access |
|--------|-----|------|------------|--------|
| `GET` | `/organisations/{slug}/membership/participants` | `organisations.membership.participants.index` | `OrganisationParticipantController@index` | owner, admin |
| `POST` | `/organisations/{slug}/membership/participants` | `organisations.membership.participants.store` | `OrganisationParticipantController@store` | owner, admin |
| `DELETE` | `/organisations/{slug}/membership/participants/{participant}` | `organisations.membership.participants.destroy` | `OrganisationParticipantController@destroy` | owner, admin |

All routes are inside the `organisations/{organisation:slug}` middleware group (`auth`, `verified`, `ensure.organisation`).

---

## See Also

- `developer_guide/membership/membership_management/README.md` — Domain architecture, Three-Concept Rule, database schema
- `architecture/membership/20260405_0918_membership_ablauf.md` — Original architecture specification
- `app/Models/Member.php` — `getVotingRightsAttribute()`, `canVoteInElection()`
- `app/Models/OrganisationParticipant.php` — `staff()`, `guests()`, `electionCommittee()`, `active()` scopes
