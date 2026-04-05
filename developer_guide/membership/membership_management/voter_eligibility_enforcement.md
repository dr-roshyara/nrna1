# Voter Eligibility Enforcement
## Only Active Formal Members Can Become Voters

**Branch:** `multitenancy`  
**Implemented:** 2026-04-05  
**Status:** Production-ready — 27/27 tests passing  

---

## 1. Overview

Prior to this implementation, any user who held *any* role in an organisation (staff, guest, admin, owner) could be registered as a voter in an election. This was a security gap: a person without a valid paid membership could participate in a member vote.

The rule is now enforced at every voter assignment path:

> **A user can only become a voter if they are an active formal member with full voting rights in the organisation.**

---

## 2. The Eligibility Rule

A user is an eligible voter for an organisation if and only if all of the following conditions are met simultaneously:

| # | Condition | Table / Column |
|---|-----------|----------------|
| 1 | Member record exists and is not soft-deleted | `members.deleted_at IS NULL` |
| 2 | Member status is active | `members.status = 'active'` |
| 3 | Fees are paid or exempt | `members.fees_status IN ('paid', 'exempt')` |
| 4 | Membership type grants voting rights | `membership_types.grants_voting_rights = true` |
| 5 | Membership has not expired | `members.membership_expires_at IS NULL OR > now()` |

A suspended member, a member with unpaid fees, an associate/honorary member whose type does not grant voting rights, or a member whose membership has expired will all be **rejected** from voter registration.

---

## 3. Data Model Chain

The eligibility check navigates the three-tier membership hierarchy:

```
users
  └── organisation_users          (one per organisation the user belongs to)
        └── members               (formal membership record)
              └── membership_types (defines whether the type grants voting rights)
```

The `members.organisation_user_id` foreign key links to `organisation_users.id`.  
The `members.membership_type_id` foreign key links to `membership_types.id`.

The `voting_rights` accessor on the `Member` model (`Member.php:103`) encapsulates the same logic and returns `'full'`, `'voice_only'`, or `'none'`.

---

## 4. Single Source of Truth — `User::isEligibleVoter()`

**File:** `app/Models/User.php`

```php
public function isEligibleVoter(Organisation $organisation): bool
{
    return Member::where('organisation_id', $organisation->id)
        ->whereHas('organisationUser', fn ($q) => $q->where('user_id', $this->id))
        ->whereHas('membershipType',   fn ($q) => $q->where('grants_voting_rights', true))
        ->where('status', 'active')
        ->whereIn('fees_status', ['paid', 'exempt'])
        ->where(fn ($q) => $q->whereNull('membership_expires_at')
                             ->orWhere('membership_expires_at', '>', now()))
        ->exists();
}
```

**Key points:**
- The `Member` model uses `SoftDeletes` — the default Eloquent query scope automatically adds `deleted_at IS NULL`, so soft-deleted members are excluded without an explicit condition.
- Use this method anywhere a single user's eligibility needs to be checked (e.g., form validation in controllers).
- Do **not** use `User::isPaidMember()` as a substitute — it omits the `fees_status` and `grants_voting_rights` checks.

---

## 5. Voter Assignment Paths and Their Enforcement

There are four paths through which a voter can be registered. All four now enforce the same eligibility rule.

### 5.1 Single Voter Assignment — `ElectionVoterController::store()`

**File:** `app/Http/Controllers/ElectionVoterController.php`

When an officer assigns a single voter via the UI form, the `user_id` is validated using `isEligibleVoter()` inside Laravel's custom validation closure:

```php
function ($attribute, $value, $fail) use ($organisation) {
    $user = \App\Models\User::find($value);
    if (! $user || ! $user->isEligibleVoter($organisation)) {
        $fail('The selected user is not an active formal member with full voting rights.');
    }
},
```

If validation fails, the user is redirected back with a `user_id` error displayed in the form.

---

### 5.2 Bulk Voter Assignment — `ElectionVoterController::bulkStore()`

**File:** `app/Http/Controllers/ElectionVoterController.php`

When multiple users are submitted in one request (e.g., select-all), a pre-filter query resolves only eligible users before calling `bulkAssignVoters()`:

```php
$validIds = DB::table('members')
    ->join('organisation_users',  'members.organisation_user_id', '=', 'organisation_users.id')
    ->join('membership_types',    'members.membership_type_id',   '=', 'membership_types.id')
    ->whereIn('organisation_users.user_id', $request->user_ids)
    ->where('members.organisation_id', $organisation->id)
    ->where('members.status', 'active')
    ->whereIn('members.fees_status', ['paid', 'exempt'])
    ->where('membership_types.grants_voting_rights', true)
    ->where(fn ($q) => $q->whereNull('members.membership_expires_at')
                         ->orWhere('members.membership_expires_at', '>', now()))
    ->whereNull('members.deleted_at')
    ->pluck('organisation_users.user_id')
    ->toArray();
```

Ineligible users are counted and returned in the `bulk_result.invalid` response field.

---

### 5.3 Model-Level Bulk Assignment — `ElectionMembership::bulkAssignVoters()`

**File:** `app/Models/ElectionMembership.php`

This static method is the write-path used by both `bulkStore()` and the import service. It performs its own eligibility filter inside a database transaction:

```php
$validUserIds = DB::table('members')
    ->join('organisation_users',  'members.organisation_user_id', '=', 'organisation_users.id')
    ->join('membership_types',    'members.membership_type_id',   '=', 'membership_types.id')
    ->whereIn('organisation_users.user_id', $userIds)
    ->where('members.organisation_id', $election->organisation_id)
    ->where('members.status', 'active')
    ->whereIn('members.fees_status', ['paid', 'exempt'])
    ->where('membership_types.grants_voting_rights', true)
    ->where(fn ($q) => $q->whereNull('members.membership_expires_at')
                         ->orWhere('members.membership_expires_at', '>', now()))
    ->whereNull('members.deleted_at')
    ->pluck('organisation_users.user_id')
    ->toArray();
```

This acts as a **last line of defence**: even if a caller bypasses the controller-level pre-filter (e.g., called directly from a command or service), ineligible users are never written to `election_memberships`.

The method returns a result array:
```php
[
    'success'          => int,   // rows inserted
    'already_existing' => int,   // skipped — already registered
    'invalid'          => int,   // rejected — not eligible
]
```

---

### 5.4 CSV / Excel Import — `VoterImportService::validateRow()`

**File:** `app/Services/VoterImportService.php`

During the preview step of the import wizard, each row is validated. Ineligible users produce a row-level error that is shown in the preview table before the officer confirms the import:

```php
if (! $user->isEligibleVoter($this->getOrganisation())) {
    $errors[] = "'{$email}' is not an eligible voter — must be an active formal member with full voting rights.";
}
```

The `getOrganisation()` helper lazy-loads the `Organisation` model once per import job:

```php
private ?Organisation $org = null;

private function getOrganisation(): Organisation
{
    return $this->org ??= Organisation::findOrFail($this->election->organisation_id);
}
```

Rows with errors are marked `invalid` in the preview and are **skipped** during the actual import — they never reach `bulkAssignVoters()`.

---

## 6. Enforcement Summary

| Path | File | Method Used |
|------|------|-------------|
| Single UI assignment | `ElectionVoterController::store()` | `User::isEligibleVoter()` |
| Bulk UI assignment | `ElectionVoterController::bulkStore()` | Raw SQL (same logic) |
| Model bulk write | `ElectionMembership::bulkAssignVoters()` | Raw SQL (same logic) |
| CSV/Excel import | `VoterImportService::validateRow()` | `User::isEligibleVoter()` |

---

## 7. What Was Changed (Not Changed Before)

Before this implementation, all four paths queried `user_organisation_roles` to determine if a user was "a member":

```php
// OLD — checked any platform role (owner, admin, staff, guest)
$isMember = DB::table('user_organisation_roles')
    ->where('user_id', $value)
    ->where('organisation_id', $organisation->id)
    ->exists();
```

This allowed any user with *any* organisational role to be registered as a voter, even if they had no paid membership. The fix replaces every occurrence with the proper membership eligibility check described above.

---

## 8. Membership Types and Voting Rights

Membership types are managed in the `membership_types` table. Each type has a boolean column:

```
membership_types.grants_voting_rights  (boolean, default: false)
```

| Type (example) | `grants_voting_rights` |
|----------------|------------------------|
| Full Member    | `true`                |
| Associate Member | `false`             |
| Honorary Member | `false` (unless configured otherwise) |

To grant voting rights to a new type, set `grants_voting_rights = true` when creating or editing the membership type. No code changes are needed.

---

## 9. Fee Statuses and Voting Rights

| `fees_status` | Eligible to vote? |
|---------------|-------------------|
| `paid`        | Yes               |
| `exempt`      | Yes               |
| `unpaid`      | **No**            |
| `partial`     | **No**            |
| `waived`      | **No** (unless changed to `exempt`) |

If a member should be exempt from fees but still vote (e.g., a lifetime member), set their `fees_status` to `'exempt'`.

---

## 10. Test Coverage

**File:** `tests/Feature/Election/VoterEligibilityTest.php`

The implementation is covered by 27 tests across five groups:

### Group A — `User::isEligibleVoter()` unit (11 tests)
Tests each eligibility condition independently:

| Test | Condition Tested |
|------|-----------------|
| `eligible voter with paid fees and voting type` | Happy path — paid fees, grants_voting_rights = true |
| `eligible voter with exempt fees` | `fees_status = 'exempt'` is accepted |
| `eligible voter with null expiry lifetime member` | `membership_expires_at = NULL` means no expiry |
| `eligible voter with future expiry` | Not-yet-expired memberships are accepted |
| `ineligible if fees status is unpaid` | `fees_status = 'unpaid'` is rejected |
| `ineligible if fees status is partial` | `fees_status = 'partial'` is rejected |
| `ineligible if membership type does not grant voting` | `grants_voting_rights = false` is rejected |
| `ineligible if membership expired` | Past `membership_expires_at` is rejected |
| `ineligible if member status is suspended` | `status = 'suspended'` is rejected |
| `ineligible if member is soft deleted` | Soft-deleted member record is rejected |
| `ineligible if no member record exists` | User with only `UserOrganisationRole`, no `Member`, is rejected |

### Group B — `store()` HTTP (5 tests)
POST to `elections.voters.store` with various member states.

### Group C — `bulkStore()` HTTP (3 tests)
POST to `elections.voters.bulk` with mixed-eligibility user arrays.

### Group D — `ElectionMembership::bulkAssignVoters()` direct (4 tests)
Calls the model method directly, verifying database-level enforcement.

### Group E — Import preview (4 tests)
Posts a CSV to `elections.voters.import.preview` and asserts validation results.

---

## 11. Adding a New Voter Assignment Path

If a new code path is added in the future that writes to `election_memberships`, it **must** enforce the same eligibility rule. Use one of these two patterns:

**Option A — Use the method (recommended for single users):**
```php
if (! $user->isEligibleVoter($organisation)) {
    // reject
}
```

**Option B — Use the raw SQL filter (recommended for bulk operations):**
```php
$validIds = DB::table('members')
    ->join('organisation_users', 'members.organisation_user_id', '=', 'organisation_users.id')
    ->join('membership_types',   'members.membership_type_id',   '=', 'membership_types.id')
    ->whereIn('organisation_users.user_id', $candidateUserIds)
    ->where('members.organisation_id', $organisation->id)
    ->where('members.status', 'active')
    ->whereIn('members.fees_status', ['paid', 'exempt'])
    ->where('membership_types.grants_voting_rights', true)
    ->where(fn ($q) => $q->whereNull('members.membership_expires_at')
                         ->orWhere('members.membership_expires_at', '>', now()))
    ->whereNull('members.deleted_at')
    ->pluck('organisation_users.user_id')
    ->toArray();
```

Always add tests for the new path in the same style as `VoterEligibilityTest.php`.

---

## 12. Known Limitations (Out of Scope)

The **voter selection dropdown** in `ElectionVoterController::index()` (lines 56–62) still populates from `user_organisation_roles`, meaning ineligible users may appear in the UI dropdown. They will be rejected on form submission, but the dropdown itself has not yet been filtered. This is tracked as a separate improvement.

---

## Related Files

| File | Purpose |
|------|---------|
| `app/Models/User.php` | `isEligibleVoter()` method |
| `app/Models/Member.php` | `voting_rights` accessor (line 103) |
| `app/Models/MembershipType.php` | `grants_voting_rights` column |
| `app/Models/ElectionMembership.php` | `bulkAssignVoters()` model method |
| `app/Http/Controllers/ElectionVoterController.php` | `store()` and `bulkStore()` |
| `app/Services/VoterImportService.php` | CSV/Excel import validation |
| `tests/Feature/Election/VoterEligibilityTest.php` | Full test coverage (27 tests) |
| `architecture/membership/20260405_2256_only_active_member_can_become_voters.md` | Original architecture decision |
