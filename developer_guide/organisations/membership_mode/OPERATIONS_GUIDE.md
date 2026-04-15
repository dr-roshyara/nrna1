# Membership Mode Operations Guide

## Overview

This guide covers day-to-day operational tasks, maintenance, troubleshooting, and best practices for managing both Full Membership and Election-Only organisations.

---

## Table of Contents

1. [Operational Tasks](#operational-tasks)
2. [Member Management](#member-management)
3. [Election Setup](#election-setup)
4. [Monitoring & Maintenance](#monitoring--maintenance)
5. [Troubleshooting](#troubleshooting)
6. [Performance Optimization](#performance-optimization)

---

## Operational Tasks

### Task: Add Voters to Election

#### Full Membership Mode

**Manual Addition (One at a Time):**

```php
// Controller: ElectionVoterController::store()
$user = User::where('email', $request->email)->firstOrFail();
$org = $election->organisation;

// Check Full Membership eligibility
$isMember = Member::where('user_id', $user->id)
    ->where('organisation_id', $org->id)
    ->where('status', 'active')
    ->whereIn('fees_status', ['paid', 'exempt'])
    ->exists();

if (!$isMember) {
    return back()->withErrors(['email' => 'User is not an active member']);
}

// Add to election
$election->memberships()->create([
    'user_id' => $user->id,
    'organisation_id' => $org->id,
    'role' => 'voter',
    'status' => 'active',
]);
```

**Bulk Import:**

```php
// Use VoterImportService with CSV:
// email,fees_status,membership_type,membership_expires_at
// voter@example.com,paid,Standard,2026-12-31

$service = app(VoterImportService::class);
$result = $service->import($file, $election, auth()->user());
```

#### Election-Only Mode

**Manual Addition:**

```php
// Controller: ElectionVoterController::store()
$user = User::where('email', $request->email)->firstOrFail();
$org = $election->organisation;

// Check Election-Only eligibility (just org membership)
$isOrgUser = OrganisationUser::where('user_id', $user->id)
    ->where('organisation_id', $org->id)
    ->where('status', 'active')
    ->exists();

if (!$isOrgUser) {
    return back()->withErrors(['email' => 'User is not in this organisation']);
}

// Add to election
$election->memberships()->create([
    'user_id' => $user->id,
    'organisation_id' => $org->id,
    'role' => 'voter',
    'status' => 'active',
]);
```

**Bulk Import:**

```php
// Use VoterImportService with CSV:
// email
// voter1@example.com
// voter2@example.com

$service = app(VoterImportService::class);
$result = $service->import($file, $election, auth()->user());
```

### Task: Remove Voters from Election

#### Both Modes

```php
// Controller: ElectionVoterController::destroy()
$membership = $election->memberships()
    ->where('user_id', $userId)
    ->firstOrFail();

$membership->delete(); // or soft-delete

// Log the removal
activity('voters.removed')
    ->withProperties(['user_id' => $userId, 'election_id' => $election->id])
    ->log();
```

### Task: View Election Voters

#### Full Membership Mode

```php
// Query: All members eligible for this election
$voters = $election->memberships()
    ->with('user')
    ->whereHas('user', function($q) {
        $q->whereHas('member', function($sq) {
            $sq->where('organisation_id', $this->election->organisation_id)
               ->where('status', 'active')
               ->whereIn('fees_status', ['paid', 'exempt']);
        });
    })
    ->paginate(50);
```

#### Election-Only Mode

```php
// Query: All org users assigned to this election
$voters = $election->memberships()
    ->with('user')
    ->whereHas('user', function($q) {
        $q->whereHas('organisationUser', function($sq) {
            $sq->where('organisation_id', $this->election->organisation_id)
               ->where('status', 'active');
        });
    })
    ->paginate(50);
```

---

## Member Management

### Full Membership Mode Only

#### Task: Update Member Fees Status

```php
// Change from pending to paid
$member = Member::findOrFail($memberId);

$member->update([
    'fees_status' => 'paid',
    'fees_paid_at' => now(),
    'fees_expires_at' => now()->addYear(),
]);

// Log change
activity('member.fees_updated')
    ->withProperties([
        'old_status' => $member->getOriginal('fees_status'),
        'new_status' => 'paid',
    ])
    ->log();
```

#### Task: Extend Membership Expiration

```php
$member = Member::findOrFail($memberId);

$member->update([
    'membership_expires_at' => now()->addYear(),
]);

// Affected elections: User now eligible for voting
```

#### Task: Set Membership Type

```php
$membershipType = MembershipType::where('name', 'Premium')->firstOrFail();

$member = Member::findOrFail($memberId);
$member->update(['membership_type_id' => $membershipType->id]);

// If type doesn't grant voting rights, user becomes ineligible
```

#### Task: Bulk Update Member Fees

```php
// Mark all members with past-due fees
$expiredMembers = Member::where('organisation_id', $orgId)
    ->where('fees_expires_at', '<', now())
    ->where('status', 'active')
    ->update(['fees_status' => 'pending']);

// These members are now ineligible to vote
```

#### Task: Deactivate Member

```php
$member = Member::findOrFail($memberId);

$member->update(['status' => 'inactive']);

// User is now ineligible to vote in any election
// Existing votes are not affected (audit trail preserved)
```

---

## Election Setup

### Configure Election for Full Membership Mode

```php
// Backend: Create election
$election = Election::create([
    'organisation_id' => $org->id,
    'name' => 'Board Elections 2026',
    'status' => 'active',
    'type' => 'real',
    'start_date' => now(),
    'end_date' => now()->addDays(30),
    'uses_full_membership' => true, // Inherits from org
]);

// Frontend: Admin sees Full Membership import interface
// CSV requires: email, fees_status, membership_type, membership_expires_at
```

### Configure Election for Election-Only Mode

```php
// Backend: Create election
$election = Election::create([
    'organisation_id' => $org->id,
    'name' => 'Community Survey 2026',
    'status' => 'active',
    'type' => 'real',
    'start_date' => now(),
    'end_date' => now()->addDays(7),
    'uses_full_membership' => false, // Inherits from org
]);

// Frontend: Admin sees Election-Only import interface
// CSV requires: email only
```

### Set Voting Rules (Both Modes)

```php
$election->update([
    // Selection constraints
    'selection_constraint_type' => 'exact', // 'any', 'exact', 'range'
    'selection_constraint_min' => 1,
    'selection_constraint_max' => 5,
    
    // Additional features
    'no_vote_option_enabled' => true,
    'ip_restriction_enabled' => true,
    'ip_restriction_max_per_ip' => 3,
]);
```

---

## Monitoring & Maintenance

### Dashboard Metrics

#### Full Membership Mode

```php
// Key metrics to monitor
$metrics = [
    'total_members' => Member::where('organisation_id', $org->id)
        ->where('status', 'active')
        ->count(),
    
    'paid_members' => Member::where('organisation_id', $org->id)
        ->where('status', 'active')
        ->where('fees_status', 'paid')
        ->count(),
    
    'exempt_members' => Member::where('organisation_id', $org->id)
        ->where('status', 'active')
        ->where('fees_status', 'exempt')
        ->count(),
    
    'expired_memberships' => Member::where('organisation_id', $org->id)
        ->where('status', 'active')
        ->whereNotNull('membership_expires_at')
        ->where('membership_expires_at', '<', now())
        ->count(),
    
    'election_voters' => ElectionMembership::where('election_id', $election->id)
        ->count(),
];
```

#### Election-Only Mode

```php
// Key metrics to monitor
$metrics = [
    'total_org_users' => OrganisationUser::where('organisation_id', $org->id)
        ->where('status', 'active')
        ->count(),
    
    'eligible_voters' => OrganisationUser::where('organisation_id', $org->id)
        ->where('status', 'active')
        ->count(),
    
    'election_voters' => ElectionMembership::where('election_id', $election->id)
        ->count(),
    
    'participation_rate' => round(
        (ElectionMembership::where('election_id', $election->id)->count() / 
         OrganisationUser::where('organisation_id', $org->id)->where('status', 'active')->count()) 
        * 100
    ),
];
```

### Health Checks

#### Verify Eligibility Logic

```php
// Test Full Membership eligibility
$testUser = User::factory()->create();
$org = Organisation::where('uses_full_membership', true)->first();

// Should be ineligible without Member record
$service = app(VoterEligibilityService::class);
$eligible = $service->isEligibleVoter($org, $testUser);
assert($eligible === false, 'Full Membership: User without Member should be ineligible');

// Add Member record
Member::create([
    'user_id' => $testUser->id,
    'organisation_id' => $org->id,
    'status' => 'active',
    'fees_status' => 'paid',
]);

// Should be eligible with active Member
$eligible = $service->isEligibleVoter($org, $testUser);
assert($eligible === true, 'Full Membership: User with Member should be eligible');
```

#### Verify Import Validation

```php
// Test CSV validation
$csvContent = "email,fees_status\ntest@example.com,paid";
$file = UploadedFile::fake()->createWithContent('test.csv', $csvContent);

$election = Election::where('uses_full_membership', true)->first();
$service = app(VoterImportService::class);
$preview = $service->preview($file, $election);

// Should detect any validation errors
if ($preview['rows_invalid'] > 0) {
    foreach ($preview['errors'] as $error) {
        \Log::warning("Import validation error: {$error['error']}");
    }
}
```

### Regular Maintenance Tasks

#### Weekly: Check for Expired Memberships

```php
// Find members with past-due fees
$expiredCount = Member::where('organisation_id', $org->id)
    ->where('fees_status', 'paid')
    ->where('fees_expires_at', '<', now())
    ->count();

if ($expiredCount > 0) {
    \Log::warning("Found {$expiredCount} expired memberships needing attention");
}
```

#### Monthly: Audit Member Records

```php
// Verify Member records for all active users
$orgUsers = OrganisationUser::where('organisation_id', $org->id)
    ->where('status', 'active')
    ->pluck('user_id');

$membersWithoutRecord = $orgUsers
    ->filter(function($userId) use ($org) {
        return !Member::where('user_id', $userId)
            ->where('organisation_id', $org->id)
            ->exists();
    })
    ->count();

if ($membersWithoutRecord > 0) {
    \Log::warning("{$membersWithoutRecord} org users missing Member records");
}
```

#### Quarterly: Backup Member Data

```php
$backup = Member::where('organisation_id', $org->id)->get();
$filename = "backups/members_{$org->id}_" . now()->format('Y-m-d') . ".json";

Storage::put($filename, json_encode($backup, JSON_PRETTY_PRINT));
```

---

## Troubleshooting

### Issue: User Shows as Ineligible but Should Be Eligible

#### Full Membership Mode

**Possible Causes & Fixes:**

```php
$user = User::where('email', $email)->first();
$org = $election->organisation;

// Check 1: OrganisationUser exists
$orgUser = OrganisationUser::where('user_id', $user->id)
    ->where('organisation_id', $org->id)
    ->first();
    
if (!$orgUser) {
    // Fix: Add user to organisation
    OrganisationUser::create([
        'user_id' => $user->id,
        'organisation_id' => $org->id,
        'status' => 'active',
    ]);
}

// Check 2: Member record exists
$member = Member::where('user_id', $user->id)
    ->where('organisation_id', $org->id)
    ->first();

if (!$member) {
    // Fix: Create Member record
    Member::create([
        'user_id' => $user->id,
        'organisation_id' => $org->id,
        'status' => 'active',
        'fees_status' => 'paid',
    ]);
}

// Check 3: Fees status is valid
if (!in_array($member->fees_status, ['paid', 'exempt'])) {
    // Fix: Update fees status
    $member->update(['fees_status' => 'paid']);
}

// Check 4: Membership not expired
if ($member->membership_expires_at && $member->membership_expires_at < now()) {
    // Fix: Extend membership
    $member->update([
        'membership_expires_at' => now()->addYear(),
    ]);
}

// Check 5: Membership type grants voting rights
if ($member->membership_type_id) {
    $type = MembershipType::find($member->membership_type_id);
    if (!$type->grants_voting_rights) {
        // Fix: Change membership type
        $votingType = MembershipType::where('organisation_id', $org->id)
            ->where('grants_voting_rights', true)
            ->first();
        $member->update(['membership_type_id' => $votingType->id]);
    }
}
```

#### Election-Only Mode

**Possible Causes & Fixes:**

```php
$user = User::where('email', $email)->first();
$org = $election->organisation;

// Check 1: OrganisationUser exists
$orgUser = OrganisationUser::where('user_id', $user->id)
    ->where('organisation_id', $org->id)
    ->first();

if (!$orgUser) {
    // Fix: Add user to organisation
    OrganisationUser::create([
        'user_id' => $user->id,
        'organisation_id' => $org->id,
        'status' => 'active',
    ]);
} else if ($orgUser->status !== 'active') {
    // Fix: Activate user
    $orgUser->update(['status' => 'active']);
}
```

### Issue: Import File Rejected with Validation Errors

**Diagnosis:**

```php
$file = $request->file('file');
$election = Election::find($electionId);

$service = app(VoterImportService::class);
$preview = $service->preview($file, $election);

// Show detailed errors
foreach ($preview['errors'] as $error) {
    echo "Row {$error['row']}: {$error['email']} - {$error['error']}";
}
```

**Common Errors & Fixes:**

| Error | Cause | Fix |
|-------|-------|-----|
| "Email not registered" | User doesn't exist in users table | User must sign up first |
| "User already assigned" | Voter already in election | Remove from CSV or skip |
| "Invalid fees_status" | Not 'paid' or 'exempt' | Correct in CSV |
| "Unknown membership type" | Type doesn't exist | Check MembershipType names |
| "Invalid date format" | Not YYYY-MM-DD | Reformat dates |

### Issue: Sudden Ineligibility of Voters

**Cause: Membership Expired**

```php
// Find voters whose membership just expired
$membershipExpiredToday = Member::where('organisation_id', $org->id)
    ->where('membership_expires_at', '=', now()->toDateString())
    ->with('user')
    ->get();

foreach ($membershipExpiredToday as $member) {
    \Log::warning("Membership expired for {$member->user->email}");
    // Notify admin to renew membership
}
```

**Solution:**

```php
$member = Member::where('user_id', $userId)->first();
$member->update([
    'membership_expires_at' => now()->addYear(),
]);
```

### Issue: Mode Change Breaks Existing Elections

**Scenario:** Changed organisation from Full Membership to Election-Only (or vice versa)

**Risk:** Existing voter list may become invalid

**Solution:**

```php
// Don't delete existing ElectionMembership records
// They remain valid for historical elections
// Only affects NEW elections

$org = Organisation::find($orgId);

// Change mode
$org->update(['uses_full_membership' => false]);

// Existing election memberships remain in database
// But eligibility checks use new mode for future assignments
```

---

## Performance Optimization

### Query Optimization

#### Full Membership Mode Queries

```php
// ❌ SLOW: N+1 queries
$members = Member::where('organisation_id', $org->id)->get();
foreach ($members as $member) {
    echo $member->user->email; // Separate query per member
}

// ✅ FAST: Single query with eager loading
$members = Member::where('organisation_id', $org->id)
    ->with('user', 'membershipType')
    ->get();
```

#### Election-Only Mode Queries

```php
// ❌ SLOW: Multiple joins
$voters = ElectionMembership::where('election_id', $election->id)
    ->leftJoin('members', ...)
    ->leftJoin('users', ...)
    ->get();

// ✅ FAST: Simple join
$voters = ElectionMembership::where('election_id', $election->id)
    ->with('user')
    ->get();
```

### Indexing Strategy

#### Full Membership Mode

```sql
-- Critical indexes for member eligibility queries
CREATE INDEX idx_members_org_status_fees 
ON members(organisation_id, status, fees_status);

CREATE INDEX idx_members_expires 
ON members(membership_expires_at);

CREATE INDEX idx_organisation_users_org_user_status
ON organisation_users(organisation_id, user_id, status);
```

#### Election-Only Mode

```sql
-- Simpler indexes for org user queries
CREATE INDEX idx_organisation_users_org_user_status
ON organisation_users(organisation_id, user_id, status);

CREATE INDEX idx_election_memberships_election_user
ON election_memberships(election_id, user_id);
```

### Caching Strategy

```php
// Cache eligible voter count for Dashboard
$cacheKey = "eligible_voters_{$org->id}_{$election->id}";

$eligibleCount = Cache::remember($cacheKey, 3600, function() use ($org, $election) {
    $service = app(VoterEligibilityService::class);
    
    // Get all org users and check eligibility
    return OrganisationUser::where('organisation_id', $org->id)
        ->where('status', 'active')
        ->count(); // Simplified
});

// Invalidate cache when Member or OrganisationUser changes
Cache::forget("eligible_voters_{$org->id}_{$election->id}");
```

---

## Related Documentation

- [Membership Modes Comparison](./MEMBERSHIP_MODES.md)
- [Membership Import Guide](./MEMBERSHIP_IMPORT.md)
- [Main Architecture Guide](./README.md)

