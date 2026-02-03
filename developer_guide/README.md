# Public Digit Developer Guide

## Overview

This guide documents two major systems implemented for Public Digit:

1. **Voter Registration Flag System** - Separates customers from voters
2. **Demo/Real Election System** - Supports both demo and real elections

## Table of Contents

- [System Architecture](#system-architecture)
- [Quick Start](#quick-start)
- [Core Concepts](#core-concepts)
- [Implementation Timeline](#implementation-timeline)
- [Documentation Structure](#documentation-structure)

---

## System Architecture

### Problem Statement

The original system mixed customer accounts with voter accounts, causing non-voters to appear in voter approval lists. Additionally, there was no distinction between demo elections (for testing) and real elections (for actual voting).

### Solution Overview

#### Phase 1: Voter Registration Flag System
- Added `wants_to_vote` flag to separate customers from voters
- Introduced `voter_registration_at` timestamp to track intent
- Created query scopes: `customers()`, `pendingVoters()`, `approvedVoters()`
- Implemented state methods: `isCustomer()`, `isPendingVoter()`, `isApprovedVoter()`

**Result:** Clean separation between customers (non-voters) and voters (those intending to participate)

#### Phase 2: Demo/Real Election System
- Created `elections` table to store election metadata
- Created `voter_registrations` table to track voter status per election
- Implemented no-foreign-key design for multi-database flexibility
- Added complete audit trails and metadata support

**Result:** Professional multi-election platform supporting both demo and real voting scenarios

---

## Quick Start

### Installation

All migrations have been executed. To verify:

```bash
# Check tables exist
php artisan tinker
>>> DB::getSchemaBuilder()->hasTable('elections')        // Should return true
>>> DB::getSchemaBuilder()->hasTable('voter_registrations') // Should return true
```

### Basic Usage

#### Register User for Demo Election

```php
$user = auth()->user();
$demoElection = Election::where('type', 'demo')->first();

// Register for demo
$registration = $user->registerForDemoElection($demoElection->id);
```

#### Approve Voter

```php
$registration = VoterRegistration::findOrFail($registrationId);

// Approve (committee member only)
$registration->approve(auth()->user()->name);
```

#### Check Voting Eligibility

```php
// Can user vote in demo?
if ($user->canVoteInDemo()) {
    // Show voting ballot
}
```

#### Record Vote

```php
// Mark user as voted
$registration = $user->demoRegistration();
$registration->markAsVoted(['ip' => request()->ip()]);
```

---

## Core Concepts

### 1. Users are Not Voters

```
Users Table (Main App)
├── All users (customers & voters)
├── wants_to_vote flag (intent indicator)
└── voter_registration_at (when intent registered)

VoterRegistrations Table (Election Specific)
├── Tracks status per election
├── Separate from user identity
└── Supports audit trail
```

### 2. Three User States

```
CUSTOMER
├── wants_to_vote = false
├── is_voter = 0, can_vote = 0
└── Use case: Uses platform, doesn't vote

PENDING VOTER
├── wants_to_vote = true
├── is_voter = 0, can_vote = 0
└── Use case: Requested voting, awaiting approval

APPROVED VOTER
├── wants_to_vote = true
├── is_voter = 1, can_vote = 1
└── Use case: Approved, ready to vote
```

### 3. Elections Have Two Types

```
DEMO ELECTION
├── type = 'demo'
├── Purpose: Testing, familiarization
├── Users: Non-sensitive data
└── Status: Always accessible

REAL ELECTION
├── type = 'real'
├── Purpose: Official voting
├── Users: Verified members only
└── Status: Controlled by dates
```

### 4. Voter Registration Lifecycle

```
User Registration → Pending Approval → Approved → Voted
     ↓                    ↓                ↓         ↓
registered_at      (waiting)         approved_at  voted_at
   timestamp      (can't vote)       approved_by  (proof)
                                     metadata
```

---

## Implementation Timeline

| Phase | Component | Status | Date | Files |
|-------|-----------|--------|------|-------|
| 1 | Voter Flag System | ✅ Complete | 2026-02-03 | Migration + Model updates |
| 2 | Election System | ✅ Complete | 2026-02-03 | 2 Migrations + 2 Models + Seeder |
| 3 | Controller Updates | ⏳ Pending | - | Voter approval UI |
| 4 | Frontend Integration | ⏳ Pending | - | Vue components |
| 5 | Reporting | ⏳ Pending | - | Analytics queries |

---

## Documentation Structure

```
developer_guide/
├── README.md (this file)
│   └── Overview & quick start
├── voter-registration-system.md
│   ├── Voter flag system details
│   ├── State management
│   └── Query scopes
├── election-system.md
│   ├── Elections & registrations
│   ├── Registration lifecycle
│   └── Approval workflow
├── database-schema.md
│   ├── Table structures
│   ├── Column descriptions
│   └── Index strategies
├── migration-guide.md
│   ├── Migration execution
│   ├── Data migration logic
│   └── Rollback procedures
├── query-examples.md
│   ├── Common queries
│   ├── Filter operations
│   └── Statistics
├── troubleshooting.md
│   ├── Common issues
│   ├── Debug techniques
│   └── Recovery procedures
└── ARCHITECTURE.md
    ├── Design decisions
    ├── Tradeoffs
    └── Future considerations
```

---

## Key Files Reference

### Migrations
```
database/migrations/
├── 2026_02_03_193521_add_wants_to_vote_flag_to_users_table.php
├── 2026_02_03_193800_create_elections_table.php
└── 2026_02_03_193900_create_voter_registrations_table.php
```

### Models
```
app/Models/
├── User.php (updated with election methods)
├── Election.php (new)
├── VoterRegistration.php (new)
└── ...
```

### Seeders
```
database/seeders/
└── ElectionSeeder.php
```

---

## Architecture Highlights

### Multi-Database Support

Design uses **no foreign keys** to support multi-tenant scenarios where:
- Users table: Tenant database
- Elections table: Landlord database
- Can be extended as needed

### Audit Trail

Complete history tracking:
```php
VoterRegistration {
    registered_at,    // When registered
    approved_at,      // When approved
    approved_by,      // Who approved
    voted_at,         // When voted
    metadata          // Additional context
}
```

### Flexible Metadata

JSON field for extensible data:
```php
$registration->metadata = [
    'ip_address' => '192.168.1.1',
    'browser' => 'Chrome',
    'device' => 'desktop',
    'notes' => 'Custom notes'
];
```

---

## Best Practices

### 1. Always Check Election Type

```php
// ❌ WRONG: Generic voter approval
if ($user->is_voter) { approve($user); }

// ✅ RIGHT: Election-specific approval
if ($registration->isDemo()) {
    if ($registration->isPending()) {
        $registration->approve($approver);
    }
}
```

### 2. Use Query Scopes

```php
// ❌ WRONG: Manual where clauses
$pending = DB::table('voter_registrations')
    ->where('status', 'pending')
    ->get();

// ✅ RIGHT: Use scopes
$pending = VoterRegistration::pending()->demo()->get();
```

### 3. Validate Transitions

```php
// ❌ WRONG: Direct status update
$registration->status = 'approved';
$registration->save();

// ✅ RIGHT: Use action methods
$registration->approve($approver);
```

### 4. Include Metadata

```php
// ❌ WRONG: No context
$registration->markAsVoted();

// ✅ RIGHT: Store context
$registration->markAsVoted([
    'ip' => request()->ip(),
    'timestamp_verified' => now()
]);
```

---

## Testing

### Verify Installation

```bash
php artisan tinker

# Check tables
>>> DB::getSchemaBuilder()->hasTable('elections')        // true
>>> DB::getSchemaBuilder()->hasTable('voter_registrations') // true

# Check models
>>> class_exists('App\Models\Election')           // true
>>> class_exists('App\Models\VoterRegistration')  // true

# Check elections seeded
>>> App\Models\Election::count()                  // 2
>>> App\Models\Election::where('type', 'demo')->exists()  // true
```

### Test User Registration

```bash
php artisan tinker

# Create user
>>> $user = App\Models\User::create([...])

# Register for demo
>>> $registration = $user->registerForDemoElection(1)

# Check status
>>> $user->wantsToVoteInDemo()  // true
>>> $user->canVoteInDemo()      // false (not approved yet)

# Approve
>>> $registration->approve('Test Admin')
>>> $user->fresh()->canVoteInDemo()  // true

# Vote
>>> $registration->fresh()->markAsVoted()
>>> $user->fresh()->hasVotedInDemo()  // true
```

---

## Common Workflows

### Workflow 1: Demo Election Registration

```
1. User visits app
2. System checks: wants_to_vote?
   - Yes → Show "Register for Demo"
   - No → Show customer features only
3. User clicks "Register for Demo"
4. Create VoterRegistration (status=pending)
5. Committee approves (status=approved)
6. User can access voting ballot
7. Vote submitted (status=voted)
```

### Workflow 2: Real Election Registration

```
1. User requests access
2. Create VoterRegistration (status=pending, election_type=real)
3. Committee reviews credentials
   - Approved → status=approved
   - Rejected → status=rejected (with reason)
4. User notified
5. On voting day: Access ballot if approved
6. Submit vote (status=voted)
```

### Workflow 3: Voter Suspension

```
1. Approved voter (status=approved)
2. Committee detects fraud/issue
3. Use reject() method
4. Send rejection reason
5. Update metadata with reason
6. User notified
```

---

## What's Next?

### Phase 3: Controller Integration
- Update voter approval controllers to use new scopes
- Add election type filters
- Update voter list views

### Phase 4: Frontend Integration
- Vue components for election selection
- Demo vs Real workflow in UI
- Registration/approval forms

### Phase 5: Advanced Features
- Bulk voter import with election type
- Election scheduling
- Results visibility settings
- Voter analytics

---

## Support & Questions

For detailed information on each system:

- **Voter Registration System** → See `voter-registration-system.md`
- **Election System** → See `election-system.md`
- **Database Details** → See `database-schema.md`
- **Query Examples** → See `query-examples.md`
- **Troubleshooting** → See `troubleshooting.md`
- **Architecture** → See `ARCHITECTURE.md`

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | 2026-02-03 | Initial release: Voter flags + Election system |

---

**Last Updated:** 2026-02-03
**Maintained By:** Development Team
**Status:** Production Ready ✅
