# Membership Management — Developer Guide

**Architecture version:** 2026-04-05  
**Reference:** `architecture/membership/20260404_2344_redefine_membership_participants_guests.md`  
**Status:** Green (all 43 domain tests passing)

---

## Table of Contents

1. [Domain Model Overview](#1-domain-model-overview)
2. [The Three-Concept Rule](#2-the-three-concept-rule)
3. [Database Schema](#3-database-schema)
4. [Models & Relationships](#4-models--relationships)
5. [Voting Rights System](#5-voting-rights-system)
6. [OrganisationParticipant — Staff / Guest / Committee](#6-organisationparticipant--staff--guest--committee)
7. [Factories & TDD Setup](#7-factories--tdd-setup)
8. [Query Patterns](#8-query-patterns)
9. [Adding a New Feature — Step by Step](#9-adding-a-new-feature--step-by-step)
10. [Common Mistakes](#10-common-mistakes)

---

## 1. Domain Model Overview

```
Organisation (Tenant)
│
├── user_organisation_roles          ← platform access (owner/admin/commission/voter)
│   └── UserOrganisationRole
│
├── organisation_participants        ← operational roles (staff/guest/election_committee)
│   └── OrganisationParticipant
│
└── members                          ← paid formal membership
    └── Member
        ├── membership_type_id  →  MembershipType  (Full / Associate)
        ├── fees_status         →  paid | unpaid | partial | exempt
        └── voting_rights       →  full | voice_only | none  (computed accessor)
```

Three completely separate tables. Each answers a different question:

| Table | Question answered |
|-------|-------------------|
| `user_organisation_roles` | "Does this user have platform access to this org?" |
| `organisation_participants` | "Is this user an operational participant (staff/guest/committee)?" |
| `members` | "Does this user hold a formal paid membership?" |

---

## 2. The Three-Concept Rule

This is the most important concept in the architecture. Violating it causes the bugs this architecture was designed to fix.

### Concept A — Platform Access (`user_organisation_roles`)

A user appears here when they are granted **any role** on the platform: `owner`, `admin`, `commission`, `member`, `voter`.

```php
// Check platform access
UserOrganisationRole::where('user_id', $user->id)
    ->where('organisation_id', $org->id)
    ->value('role');   // 'owner' | 'admin' | 'commission' | 'member' | 'voter' | null
```

Having a `member` role here does **NOT** mean the user has a formal membership. It only means they were assigned the member role on the platform.

### Concept B — Operational Participant (`organisation_participants`)

A user appears here when they have a **specific operational function**: staff member, invited guest, or election committee member. This is distinct from platform access and from formal membership.

```php
// Assign a staff member
OrganisationParticipant::create([
    'organisation_id'  => $org->id,
    'user_id'          => $user->id,
    'participant_type' => 'staff',    // staff | guest | election_committee
    'role'             => 'coordinator',
    'assigned_at'      => now(),
]);
```

### Concept C — Formal Paid Member (`members`)

A user appears here only after completing the **membership application workflow** and being enrolled. This record carries fees, expiry, membership type, and voting rights.

```php
// Check formal membership
$isMember = Member::where('organisation_id', $org->id)
    ->whereHas('organisationUser', fn($q) => $q->where('user_id', $user->id))
    ->where('status', 'active')
    ->exists();
```

---

## 3. Database Schema

### `members` table (key columns)

```
id                    uuid PK
organisation_id       uuid FK → organisations
organisation_user_id  uuid FK → organisation_users
membership_type_id    uuid FK → membership_types  (nullable)
membership_number     string
status                enum: active | expired | suspended | ended
fees_status           enum: paid | unpaid | partial | exempt
joined_at             datetime
membership_expires_at datetime (null = lifetime)
last_renewed_at       datetime
ended_at              datetime
end_reason            string
```

### `membership_types` table (key columns)

```
id                    uuid PK
organisation_id       uuid FK → organisations
name                  string
slug                  string
grants_voting_rights  boolean  DEFAULT false     ← determines Full vs Associate
fee_amount            decimal(8,2)
fee_currency          string
duration_months       integer (null = lifetime)
requires_approval     boolean
is_active             boolean
```

### `organisation_participants` table

```
id                    uuid PK
organisation_id       uuid FK → organisations
user_id               uuid FK → users
participant_type      enum: staff | guest | election_committee
role                  string (nullable)
assigned_at           datetime
expires_at            datetime (nullable — null means no expiry)
permissions           json (nullable)
```

### Migrations (run order)

```
2026_04_05_000001_add_fees_status_to_members_table.php
2026_04_05_000002_add_grants_voting_rights_to_membership_types_table.php
2026_04_05_000003_create_organisation_participants_table.php
2026_04_05_000004_add_membership_type_id_to_members_table.php
```

---

## 4. Models & Relationships

### Member

**File:** `app/Models/Member.php`

```php
// Traverse to platform user
$member->organisationUser->user;     // the User model
$member->user;                       // shortcut via hasOneThrough

// Membership type
$member->membershipType;             // MembershipType model

// Organisation
$member->organisation;               // Organisation model

// Financial history
$member->fees;                       // Collection of MembershipFee
$member->renewals;                   // Collection of MembershipRenewal

// Election registration
$member->voters;                     // Collection of Voter (election-specific)
```

### MembershipType

**File:** `app/Models/MembershipType.php`

```php
// Two types, distinguished by grants_voting_rights
MembershipType::fullMember()->get();        // where grants_voting_rights = true
MembershipType::associateMember()->get();   // where grants_voting_rights = false
MembershipType::active()->get();            // where is_active = true

// Check type
$type->isLifetime();                // duration_months === null
$type->grants_voting_rights;        // true = Full Member, false = Associate
```

### OrganisationParticipant

**File:** `app/Models/OrganisationParticipant.php`

```php
// Scopes
OrganisationParticipant::staff()->get();
OrganisationParticipant::guests()->get();
OrganisationParticipant::electionCommittee()->get();
OrganisationParticipant::active()->get();     // no expiry OR expiry in future

// Expiry
$participant->isExpired();    // true if expires_at is in the past

// Relationships
$participant->organisation;
$participant->user;
```

### Organisation (new relationships)

```php
$org->participants;          // all OrganisationParticipant records
$org->staff;                 // where participant_type = 'staff'
$org->guests;                // where participant_type = 'guest'
$org->electionCommittee;     // where participant_type = 'election_committee'
```

---

## 5. Voting Rights System

Voting rights are computed — never stored — on the `Member` model via `getVotingRightsAttribute()`.

### Decision tree

```
member.status !== 'active'
    └─→ 'none'

member.membershipType.grants_voting_rights === true   (Full Member)
    ├─ fees_status = 'paid'    → 'full'
    ├─ fees_status = 'exempt'  → 'full'
    ├─ fees_status = 'partial' → 'voice_only'
    └─ fees_status = 'unpaid'  → 'none'

member.membershipType.grants_voting_rights === false  (Associate Member)
    ├─ fees_status = 'paid'    → 'voice_only'
    ├─ fees_status = 'exempt'  → 'voice_only'
    └─ fees_status = 'unpaid'  → 'none'
```

### Usage in code

```php
// Reading voting rights
$member->voting_rights;   // 'full' | 'voice_only' | 'none'

// Checking election eligibility
$member->canVoteInElection($election);   // bool — requires 'full' + same org

// Bulk query: who can vote in an election?
Member::where('organisation_id', $election->organisation_id)
    ->where('status', 'active')
    ->whereIn('fees_status', ['paid', 'exempt'])
    ->whereHas('membershipType', fn($q) => $q->where('grants_voting_rights', true))
    ->get();
```

### Voting rights are NOT stored in the database

The `voting_rights` attribute is a computed accessor. Do not add a `voting_rights` column to `members`. If you need to query by voting rights efficiently, use the query pattern above (status + fees_status + type).

---

## 6. OrganisationParticipant — Staff / Guest / Committee

### Creating participants

```php
// Add a staff member
OrganisationParticipant::create([
    'organisation_id'  => $org->id,
    'user_id'          => $user->id,
    'participant_type' => 'staff',
    'role'             => 'event_coordinator',
    'assigned_at'      => now(),
    // expires_at => null means indefinite
]);

// Add a temporary guest (expires in 7 days)
OrganisationParticipant::create([
    'organisation_id'  => $org->id,
    'user_id'          => $guestUser->id,
    'participant_type' => 'guest',
    'role'             => null,
    'assigned_at'      => now(),
    'expires_at'       => now()->addDays(7),
]);

// Add an election committee member
OrganisationParticipant::create([
    'organisation_id'  => $org->id,
    'user_id'          => $committeeUser->id,
    'participant_type' => 'election_committee',
    'role'             => 'scrutineer',
    'assigned_at'      => now(),
    'permissions'      => ['view_results', 'validate_ballots'],
]);
```

### Querying participants

```php
// All active staff for an org
$org->staff()->active()->get();

// All non-expired guests
$org->guests()
    ->where(fn($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()))
    ->get();

// Full election committee
$org->electionCommittee()->with('user')->get();
```

### Checking if a user is a participant

```php
$isStaff = OrganisationParticipant::where('organisation_id', $org->id)
    ->where('user_id', $user->id)
    ->staff()
    ->active()
    ->exists();
```

### A user CAN be both a participant and a member

These are independent records. A full member (in `members`) can simultaneously be a staff participant (in `organisation_participants`). This is intentional and valid.

---

## 7. Factories & TDD Setup

All four models have factories. Use them in feature tests.

### MembershipType factory

```php
// Default (Associate — no voting rights)
MembershipType::factory()->create(['organisation_id' => $org->id]);

// Full Member type
MembershipType::factory()->fullMember()->create(['organisation_id' => $org->id]);

// Associate Member type (explicit)
MembershipType::factory()->associateMember()->create(['organisation_id' => $org->id]);
```

### Member factory

```php
// Default — active, unpaid, no membership type
Member::factory()->create([
    'organisation_id'      => $org->id,
    'organisation_user_id' => $orgUser->id,
]);

// With a membership type and paid fees
Member::factory()->create([
    'organisation_id'      => $org->id,
    'organisation_user_id' => $orgUser->id,
    'membership_type_id'   => $fullType->id,
    'fees_status'          => 'paid',
    'status'               => 'active',
]);

// Expired member
Member::factory()->expired()->create([...]);

// Member with expiry date
Member::factory()->withExpiry(now()->addYear())->create([...]);
```

### OrganisationUser factory (required for Member)

`Member` links through `OrganisationUser`, not directly through `User`. Always create the chain:

```php
$user    = User::factory()->create();
$orgUser = OrganisationUser::factory()->create([
    'user_id'         => $user->id,
    'organisation_id' => $org->id,
]);
$member  = Member::factory()->create([
    'organisation_id'      => $org->id,
    'organisation_user_id' => $orgUser->id,
    'membership_type_id'   => $fullType->id,
    'fees_status'          => 'paid',
]);
```

### Test class boilerplate

```php
namespace Tests\Feature\Membership;

use App\Models\Member;
use App\Models\MembershipType;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyMembershipTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private MembershipType $fullType;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);
        $this->fullType = MembershipType::factory()->fullMember()->create([
            'organisation_id' => $this->org->id,
        ]);
    }

    private function makeMember(string $feesStatus = 'paid', string $status = 'active'): Member
    {
        $user    = User::factory()->create();
        $orgUser = OrganisationUser::factory()->create([
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
        ]);
        return Member::factory()->create([
            'organisation_id'      => $this->org->id,
            'organisation_user_id' => $orgUser->id,
            'membership_type_id'   => $this->fullType->id,
            'fees_status'          => $feesStatus,
            'status'               => $status,
        ]);
    }
}
```

---

## 8. Query Patterns

### Get all active paid members of an org

```php
Member::where('organisation_id', $org->id)
    ->where('status', 'active')
    ->where('fees_status', 'paid')
    ->with('organisationUser.user', 'membershipType')
    ->get();
```

### Get members eligible to vote in an election

```php
Member::where('organisation_id', $election->organisation_id)
    ->where('status', 'active')
    ->whereIn('fees_status', ['paid', 'exempt'])
    ->whereHas('membershipType', fn($q) =>
        $q->where('grants_voting_rights', true)
    )
    ->get();
```

### Count active full members

```php
Member::where('organisation_id', $org->id)
    ->where('status', 'active')
    ->whereHas('membershipType', fn($q) =>
        $q->where('grants_voting_rights', true)
    )
    ->count();
```

### Get all participants of a specific type

```php
// Staff only, with their user data
$org->staff()->with('user')->active()->get();

// Election committee with permissions
$org->electionCommittee()
    ->with('user')
    ->whereNotNull('permissions')
    ->get();
```

### Check if a user holds any of the three concepts

```php
// 1. Platform role
$platformRole = UserOrganisationRole::where('user_id', $user->id)
    ->where('organisation_id', $org->id)
    ->value('role');

// 2. Participant status
$participantTypes = OrganisationParticipant::where('user_id', $user->id)
    ->where('organisation_id', $org->id)
    ->active()
    ->pluck('participant_type');

// 3. Formal membership
$member = Member::where('organisation_id', $org->id)
    ->whereHas('organisationUser', fn($q) => $q->where('user_id', $user->id))
    ->where('status', 'active')
    ->with('membershipType')
    ->first();
```

---

## 9. Adding a New Feature — Step by Step

This section follows the TDD workflow mandated by the architecture.

### Example: "Suspend a member"

#### Step 1 — Write the test FIRST (Red)

```php
// tests/Feature/Membership/SuspendMemberTest.php

public function test_suspending_a_member_sets_status_to_suspended(): void
{
    $member = $this->makeMember('paid', 'active');
    $member->suspend('non-payment violation');

    $this->assertEquals('suspended', $member->fresh()->status);
}

public function test_suspended_member_has_no_voting_rights(): void
{
    $member = $this->makeMember('paid', 'suspended');

    $this->assertEquals('none', $member->voting_rights);
}
```

Run: `php artisan test tests/Feature/Membership/SuspendMemberTest.php` — must **fail**.

#### Step 2 — Implement the minimum to pass (Green)

```php
// In app/Models/Member.php
public function suspend(?string $reason = null): void
{
    $this->update([
        'status'     => 'suspended',
        'end_reason' => $reason,
    ]);
}
```

Run again — must **pass**.

#### Step 3 — Refactor if needed, re-run tests

Never skip the Red step. Never write implementation before the test.

---

## 10. Common Mistakes

### Mistake 1 — Checking `user_organisation_roles` to determine if someone is a "member"

```php
// WRONG — this only checks platform access
$isMe = UserOrganisationRole::where('user_id', $user->id)
    ->where('organisation_id', $org->id)
    ->where('role', 'member')
    ->exists();

// CORRECT — checks actual formal membership
$isMember = Member::where('organisation_id', $org->id)
    ->whereHas('organisationUser', fn($q) => $q->where('user_id', $user->id))
    ->where('status', 'active')
    ->exists();
```

### Mistake 2 — Storing voting_rights as a column

`voting_rights` is a **computed accessor**, not a database column. Do not add it to migrations or `$fillable`. Read it as a property; it is always derived from `fees_status` + `membershipType.grants_voting_rights` + `status`.

### Mistake 3 — Using `OrganisationParticipant` for all "non-admin" users

`OrganisationParticipant` is **only** for: `staff`, `guest`, `election_committee`. Regular members, voters, owners, and admins live in `user_organisation_roles`. Do not add new `participant_type` values without an architecture review.

### Mistake 4 — Forgetting the `HasFactory` trait

Both `Member` and `OrganisationUser` now use `HasFactory`. If you create a new model that needs a factory, always add `use HasFactory` to the model class — the factory file alone is not enough.

### Mistake 5 — Querying `Member` without tenant scope in tests

`Member` uses the `BelongsToTenant` trait which adds a global scope filtering by `session('current_organisation_id')`. In feature tests you must set the session:

```php
session(['current_organisation_id' => $this->org->id]);
```

Do this in `setUp()` before any Member queries. If you see empty collections in tests where you expect results, this is almost always the cause.

### Mistake 6 — Creating `OrganisationParticipant` with an invalid type

The `participant_type` column only accepts `staff | guest | election_committee`. Passing any other value will fail the database enum constraint. There is no "member" participant type — members live in the `members` table.

---

## Test Suite Reference

| File | Tests | What it covers |
|------|-------|----------------|
| `tests/Feature/Membership/Domain/MemberVotingRightsTest.php` | 11 | `voting_rights` accessor — all fee/status combinations |
| `tests/Feature/Membership/Domain/MemberCanVoteInElectionTest.php` | 11 | `canVoteInElection()` — eligibility matrix |
| `tests/Feature/Membership/Domain/MembershipTypeVotingRightsTest.php` | 9 | `grants_voting_rights` column, scopes, tenant isolation |
| `tests/Feature/Membership/Domain/OrganisationParticipantTest.php` | 12 | Participant creation, expiry, scopes, org relationships |

Run the full domain suite:

```bash
php artisan test tests/Feature/Membership/Domain --no-coverage
```

Expected: **50 tests, all passing**.
