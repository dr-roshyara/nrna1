# Full Membership vs Election-Only Mode — Detailed Comparison

## Executive Summary

| Aspect | Full Membership Mode | Election-Only Mode |
|--------|---------------------|-------------------|
| **Use Case** | Formal organizations tracking paid memberships | Quick elections, broad participation |
| **Voter Eligibility** | Active member + paid/exempt fees | Active org user (no member record needed) |
| **Member Records** | Required | Not required |
| **Membership Fees** | Tracked | Not tracked |
| **Setup Complexity** | Moderate | Simple |
| **Data Requirements** | More detailed | Minimal |
| **Default Setting** | Yes (true) | Optional (false) |

---

## Table of Contents

1. [Full Membership Mode](#full-membership-mode)
2. [Election-Only Mode](#election-only-mode)
3. [Side-by-Side Comparison](#side-by-side-comparison)
4. [Decision Matrix](#decision-matrix)
5. [Migration Between Modes](#migration-between-modes)
6. [Implementation Examples](#implementation-examples)

---

## Full Membership Mode

### Overview

Full Membership Mode is designed for organisations that maintain formal member registries and track membership fees. Voters must be verified members with active fee status to participate in elections.

**Configuration:**
```php
$organisation->uses_full_membership = true;
```

### Eligibility Requirements

A user is eligible to vote in Full Membership Mode if:

```
1. ✅ User has an active account (users.deleted_at IS NULL)
2. ✅ User is an active OrganisationUser (status = 'active')
3. ✅ User has an active Member record (status = 'active')
4. ✅ Member has fees_status IN ['paid', 'exempt']
5. ✅ Member has no expiry date OR expiry date > NOW()
6. ✅ (Optional) Membership type grants voting rights
```

### Database Structure

```
users
├── id (UUID)
├── email
├── name
├── organisation_id (nullable)
└── ...

organisation_users
├── id (UUID)
├── user_id (FK → users)
├── organisation_id (FK → organisations)
├── status ('active', 'inactive', 'removed')
└── ...

members
├── id (UUID)
├── user_id (FK → users)
├── organisation_id (FK → organisations)
├── status ('active', 'inactive', 'removed')
├── fees_status ('paid', 'exempt', 'pending')
├── membership_type_id (FK → membership_types, nullable)
├── membership_expires_at (nullable)
├── fees_paid_at
├── fees_expires_at
└── ...

membership_types
├── id (UUID)
├── name ('Standard', 'Premium', etc.)
├── grants_voting_rights (boolean)
└── ...

election_memberships
├── election_id
├── user_id
├── role ('voter', 'observer', 'officer')
└── status ('active', 'removed')
```

### Voter Eligibility Service Query

```php
// VoterEligibilityService::isEligibleVoter()
// Full Membership Mode path

$eligible = Member::where('organisation_id', $org->id)
    ->where('user_id', $user->id)
    ->where('status', 'active')
    ->whereIn('fees_status', ['paid', 'exempt'])
    ->where(function($q) {
        $q->whereNull('membership_type_id')
          ->orWhereHas('membershipType', function($sq) {
              $sq->where('grants_voting_rights', true);
          });
    })
    ->where(function($q) {
        $q->whereNull('membership_expires_at')
          ->orWhere('membership_expires_at', '>', now());
    })
    ->exists();
```

### Import Requirements

When importing voters in Full Membership Mode, each row must include:

```csv
email,fees_status,membership_type,membership_expires_at
voter@example.com,paid,Standard,2026-12-31
```

**Validation:**
- Email must be registered user
- `fees_status` must be 'paid' or 'exempt'
- `membership_type` must match existing type (if provided)
- `membership_expires_at` must be valid date or empty

**Creates:**
- Member record with fees tracking
- ElectionMembership record for election

### Permission Requirements

To manage Full Membership voters, user needs:
- `owner` or `admin` role in UserOrganisationRole
- Authorization via MembershipPolicy

```php
public function update(User $user, Organisation $organisation): bool
{
    return $user->hasRole(['owner', 'admin'], $organisation);
}
```

### Use Cases

#### Case 1: Professional Association

```
Organisation: "Institute of Engineers"
├── Members: 500
├── Full members: 400 (paid fees)
├── Student members: 50 (exempt fees)
├── Associate members: 50 (non-voting)

Election Setup:
├── Only full members (paid) can vote
├── Membership type 'Student' marked as non-voting
├── Election must check fees_status = 'paid'
```

#### Case 2: Cooperative with Membership Tiers

```
Organisation: "Community Cooperative"
├── Member Types:
│  ├── Founder (voting rights)
│  ├── Patron (voting rights)
│  └── Friend (no voting rights)
├── Membership Fees:
│  ├── Annual fee: $50 (status: paid)
│  ├── Waived: $0 (status: exempt)
│  └── Past due: (status: pending)

Election Setup:
├── Check fees_status IN ['paid', 'exempt']
├── Check membership_type grants_voting_rights
├── Only active, non-expired members
```

### Strengths

✅ Formal membership tracking  
✅ Compliance with fee-based governance  
✅ Ability to handle tiered membership  
✅ Fee expiration management  
✅ Membership type-based voting rights  
✅ Detailed audit trail of membership history  

### Considerations

⚠️ Requires Member records for all voters  
⚠️ More complex data validation  
⚠️ Higher data maintenance requirements  
⚠️ Import requires more columns  
⚠️ Assumes formal member registry exists  

---

## Election-Only Mode

### Overview

Election-Only Mode is designed for organisations that want to run elections without maintaining a formal member registry. Any active user in the organisation can vote without needing a Member record.

**Configuration:**
```php
$organisation->uses_full_membership = false;
```

### Eligibility Requirements

A user is eligible to vote in Election-Only Mode if:

```
1. ✅ User has an active account (users.deleted_at IS NULL)
2. ✅ User is an active OrganisationUser (status = 'active')
3. ❌ Member record NOT required
4. ❌ Fees NOT tracked
5. ❌ Expiration NOT checked
```

### Database Structure

```
users
├── id (UUID)
├── email
├── name
├── organisation_id (nullable)
└── ...

organisation_users
├── id (UUID)
├── user_id (FK → users)
├── organisation_id (FK → organisations)
├── status ('active', 'inactive', 'removed')
└── ...

members (NOT USED)
│
│

election_memberships
├── election_id
├── user_id
├── role ('voter', 'observer', 'officer')
└── status ('active', 'removed')
```

**Note:** Members table exists but is not consulted for eligibility.

### Voter Eligibility Service Query

```php
// VoterEligibilityService::isEligibleVoter()
// Election-Only Mode path

$eligible = OrganisationUser::where('organisation_id', $org->id)
    ->where('user_id', $user->id)
    ->where('status', 'active')
    ->exists();
```

### Import Requirements

When importing voters in Election-Only Mode, each row needs only:

```csv
email
voter1@example.com
voter2@example.com
```

**Validation:**
- Email must be registered user
- User must be active in organisation
- No other checks required

**Creates:**
- ElectionMembership record for election
- NO Member record created

### Permission Requirements

Same as Full Membership Mode:
- `owner` or `admin` role in UserOrganisationRole
- Authorization via MembershipPolicy

```php
public function update(User $user, Organisation $organisation): bool
{
    return $user->hasRole(['owner', 'admin'], $organisation);
}
```

### Use Cases

#### Case 1: Company-Wide Vote

```
Organisation: "Tech Company Inc."
├── Employees: 500
├── All active employees can vote
├── No membership fees
├── No formal member registry

Election Setup:
├── Just upload list of email addresses
├── All active users automatically eligible
├── Simple, fast setup
```

#### Case 2: Community Forum Poll

```
Organisation: "Community Center"
├── Registered users: 1000
├── Active in past 30 days: 800
├── Quick poll on event planning

Election Setup:
├── Email list import: 5 minutes
├── No membership data needed
├── Broad participation encouraged
```

#### Case 3: One-Time Survey

```
Organisation: "Research Institute"
├── Temporary project
├── Need input from 200 specific users
├── No permanent membership model

Election Setup:
├── Add users to org
├── Import email list
├── Run survey
├── No ongoing member tracking needed
```

### Strengths

✅ Simple setup  
✅ Minimal data requirements  
✅ Fast import (just emails)  
✅ No membership maintenance  
✅ Broad participation  
✅ Flexible eligibility  

### Considerations

⚠️ No membership fee tracking  
⚠️ No membership type differentiation  
⚠️ No fee expiration management  
⚠️ Limited membership audit trail  
⚠️ All org users equally eligible  

---

## Side-by-Side Comparison

### Data Model

| Aspect | Full Membership | Election-Only |
|--------|-----------------|---------------|
| **Tables Used** | users, org_users, members, membership_types | users, org_users |
| **Member Records** | Required | Not used |
| **Fees Tracking** | Yes | No |
| **Expiration Dates** | Yes | No |
| **Membership Types** | Yes | No |
| **Audit History** | Detailed | Minimal |

### Import Process

| Step | Full Membership | Election-Only |
|------|-----------------|---------------|
| **CSV Columns** | email, name, fees_status, membership_type, expires_at | email |
| **Validation** | Complex (7+ checks) | Simple (2-3 checks) |
| **Time to Import** | 5-10 min (500 users) | 1-2 min (500 users) |
| **Error Rate** | Higher (more validations) | Lower (fewer validations) |
| **Data Created** | Member + ElectionMembership | ElectionMembership only |

### Operational Aspects

| Operation | Full Membership | Election-Only |
|-----------|-----------------|---------------|
| **Add Voter** | Create Member + ElectionMembership | Create ElectionMembership |
| **Remove Voter** | Remove ElectionMembership, optionally deactivate Member | Remove ElectionMembership |
| **Update Fees** | Update Member.fees_status | N/A |
| **Expire Membership** | Set Member.membership_expires_at | N/A |
| **View History** | Detailed: fees paid, type, dates | Simple: election participation |

### Query Performance

| Query | Full Membership | Election-Only |
|-------|-----------------|---------------|
| **Get eligible voters** | Join users→org_users→members (slower) | Join users→org_users (faster) |
| **Check eligibility** | 5+ WHERE conditions | 2 WHERE conditions |
| **List voters for election** | Multi-join query | Simple 2-table join |
| **Index Strategy** | Composite: (org_id, user_id, status, fees_status) | Composite: (org_id, user_id, status) |

### Cost Analysis

| Cost Factor | Full Membership | Election-Only |
|------------|-----------------|---------------|
| **Data Storage** | Higher (members table) | Lower |
| **Query Complexity** | Higher | Lower |
| **Server Load** | Higher (more joins) | Lower |
| **Admin Effort** | Higher (member maintenance) | Lower |
| **Import Effort** | Higher (more data) | Lower |

---

## Decision Matrix

### Choose Full Membership Mode If:

- ✅ Organisation has formal membership registry
- ✅ Membership fees are tracked
- ✅ Different membership tiers exist
- ✅ Compliance requires fee verification
- ✅ Memberships have expiration dates
- ✅ Need detailed membership audit trail
- ✅ Want to restrict voting by membership type

**Examples:**
- Professional associations
- Credit unions
- Sports clubs
- Cultural societies
- Co-operative businesses

### Choose Election-Only Mode If:

- ✅ Quick, simple elections needed
- ✅ No formal member registry
- ✅ Fees not tracked or applicable
- ✅ All active users equally eligible
- ✅ Minimal setup required
- ✅ One-time elections or surveys
- ✅ Focus on broad participation

**Examples:**
- Company-wide votes
- Community polls
- Temporary surveys
- Ad-hoc events
- Quick decisions
- Research studies

---

## Migration Between Modes

### Full Membership → Election-Only

**Scenario:** Organisation wants to make elections open to all users without membership restrictions.

```php
// Update organisation
$organisation->update(['uses_full_membership' => false]);

// Existing ElectionMembership records remain valid
// New voters don't need Member records

// Query will now use OrganisationUser instead of Member
```

**Steps:**
1. Backup Member records (archive, don't delete)
2. Update organisation flag
3. Existing voters remain eligible
4. New voters only need OrganisationUser record
5. Test with sample election

### Election-Only → Full Membership

**Scenario:** Organisation wants formal member registry and fee tracking.

```php
// Create Member records for existing voters
$election = Election::find($electionId);

foreach ($election->voters as $user) {
    Member::firstOrCreate([
        'user_id' => $user->id,
        'organisation_id' => $organisation->id,
    ], [
        'status' => 'active',
        'fees_status' => 'paid', // Default assumption
        'membership_type_id' => null,
    ]);
}

// Update organisation
$organisation->update(['uses_full_membership' => true]);
```

**Steps:**
1. Identify all active voters
2. Create Member records for each
3. Set initial fees_status and type
4. Add expiration dates if applicable
5. Update organisation flag
6. Test with sample election

### During Migration

**Precautions:**
- Don't delete existing election records
- Keep audit trail of changes
- Test with non-critical election first
- Have rollback plan (restore from backup)
- Notify affected users if needed

---

## Implementation Examples

### Example 1: Full Membership Election Setup

```php
// 1. Create organisation in Full Membership Mode
$org = Organisation::create([
    'name' => 'IEEE Chapter',
    'slug' => 'ieee-chapter',
    'uses_full_membership' => true, // Default is true
]);

// 2. Create membership types
$founder = MembershipType::create([
    'organisation_id' => $org->id,
    'name' => 'Founder',
    'grants_voting_rights' => true,
]);

$associate = MembershipType::create([
    'organisation_id' => $org->id,
    'name' => 'Associate',
    'grants_voting_rights' => false,
]);

// 3. Create members (full membership mode)
$member = Member::create([
    'user_id' => $user->id,
    'organisation_id' => $org->id,
    'status' => 'active',
    'fees_status' => 'paid',
    'membership_type_id' => $founder->id,
    'membership_expires_at' => '2027-12-31',
]);

// 4. Create election membership
$election = Election::create([
    'organisation_id' => $org->id,
    'name' => 'Board Elections 2026',
    'uses_full_membership' => true,
]);

$eligibility = app(VoterEligibilityService::class);
if ($eligibility->isEligibleVoter($org, $user)) {
    $election->memberships()->create([
        'user_id' => $user->id,
        'organisation_id' => $org->id,
        'role' => 'voter',
        'status' => 'active',
    ]);
}
```

### Example 2: Election-Only Election Setup

```php
// 1. Create organisation in Election-Only Mode
$org = Organisation::create([
    'name' => 'Community Center',
    'slug' => 'community-center',
    'uses_full_membership' => false,
]);

// 2. Add users to organisation (no Member records needed)
$org->users()->attach($user->id, [
    'status' => 'active',
]);

// 3. Create election
$election = Election::create([
    'organisation_id' => $org->id,
    'name' => 'Community Poll 2026',
    'uses_full_membership' => false,
]);

// 4. Create election membership (no Member record consulted)
$eligibility = app(VoterEligibilityService::class);
if ($eligibility->isEligibleVoter($org, $user)) {
    $election->memberships()->create([
        'user_id' => $user->id,
        'organisation_id' => $org->id,
        'role' => 'voter',
        'status' => 'active',
    ]);
}
```

### Example 3: Check Eligibility for Both Modes

```php
$service = app(VoterEligibilityService::class);

// Both modes use same method
$org1 = Organisation::find($id1); // uses_full_membership = true
$org2 = Organisation::find($id2); // uses_full_membership = false

$eligible1 = $service->isEligibleVoter($org1, $user); // Checks Member
$eligible2 = $service->isEligibleVoter($org2, $user); // Checks OrganisationUser

// Service handles mode internally
```

### Example 4: Import Voters (Both Modes)

```php
$import = app(VoterImportService::class);
$org = Organisation::find($id);
$election = $org->elections->first();

// Full Membership: CSV with fees_status, type, dates
if ($org->uses_full_membership) {
    $file = 'members.csv'; // email, fees_status, membership_type, etc.
    $preview = $import->preview($file, $election);
    
    if ($preview['rows_valid'] === $preview['rows_total']) {
        $result = $import->import($file, $election, auth()->user());
    }
}

// Election-Only: CSV with just emails
else {
    $file = 'voters.csv'; // email only
    $preview = $import->preview($file, $election);
    
    if ($preview['rows_valid'] === $preview['rows_total']) {
        $result = $import->import($file, $election, auth()->user());
    }
}
```

---

## Related Documentation

- [Membership Import Guide](./MEMBERSHIP_IMPORT.md)
- [Main README](./README.md)
- [API Integration](./API_INTEGRATION.md)

