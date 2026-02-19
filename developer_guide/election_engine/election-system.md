# Demo/Real Election System

## Overview

The Election System provides a professional, multi-election platform supporting both demo elections (for testing) and real elections (for official voting).

**Problem Solved:** System had no way to distinguish between test elections and real elections, and couldn't track voter status separately for each election.

**Solution:** Create `elections` and `voter_registrations` tables with clean separation of concerns.

---

## Core Philosophy

### Independence Principle

The system is designed to keep components independent:

```
Users Table (User Identity)
    ↓
    └─→ Voter Registrations (Election-Specific Voter Status)
             ↓
             └─→ Elections (Election Configuration)
```

**Key Point:** Users exist independently. Elections exist independently. Voter registrations connect them without foreign key constraints, enabling:
- Multi-database architectures
- Flexible data migrations
- Clean separation of concerns

### No Foreign Keys

By design, there are **NO foreign key constraints**. This allows:

```php
// Scenario 1: Users on tenant DB, elections on landlord DB
// Without foreign keys, this is possible

// Scenario 2: Database migrations without cascading errors
// Without foreign keys, can migrate independently

// Scenario 3: Selective data import/export
// Without foreign keys, tables are independent
```

---

## Database Schema

### Elections Table

Stores election metadata and configuration.

```sql
CREATE TABLE elections (
    id                  BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name                VARCHAR(255) NOT NULL,
    slug                VARCHAR(255) UNIQUE NOT NULL,
    description         TEXT,
    type                ENUM('demo', 'real') DEFAULT 'demo',
    start_date          DATETIME,
    end_date            DATETIME,
    is_active           BOOLEAN DEFAULT true,
    settings            JSON,
    created_at          TIMESTAMP,
    updated_at          TIMESTAMP,
    INDEX (type),
    INDEX (is_active),
    INDEX (type, is_active)
);
```

### VoterRegistrations Table

Tracks voter status for each election independently.

```sql
CREATE TABLE voter_registrations (
    id                  BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id             BIGINT UNSIGNED NOT NULL,
    election_id         BIGINT UNSIGNED NOT NULL,
    status              ENUM('pending','approved','rejected','voted') DEFAULT 'pending',
    election_type       ENUM('demo', 'real') DEFAULT 'demo',
    registered_at       DATETIME,
    approved_at         DATETIME,
    voted_at            DATETIME,
    approved_by         VARCHAR(255),
    rejected_by         VARCHAR(255),
    rejection_reason    TEXT,
    metadata            JSON,
    created_at          TIMESTAMP,
    updated_at          TIMESTAMP,
    UNIQUE (user_id, election_id),
    INDEX (user_id, election_type),
    INDEX (election_id, status),
    INDEX (election_type, status),
    INDEX (status),
    INDEX (created_at)
);
```

**Note:** No `FOREIGN KEY` constraints for flexibility.

---

## Models

### Election Model

```php
class Election extends Model
{
    // Relationships
    public function voterRegistrations(): HasMany { }

    // Query Methods
    public function pendingVoters() { }
    public function approvedVoters() { }
    public function votedVoters() { }

    // Status Checks
    public function isDemo(): bool { }
    public function isReal(): bool { }
    public function isCurrentlyActive(): bool { }

    // Statistics
    public function pendingVoterCount(): int { }
    public function approvedVoterCount(): int { }
    public function votedCount(): int { }
}
```

### VoterRegistration Model

```php
class VoterRegistration extends Model
{
    // Relationships
    public function user(): BelongsTo { }
    public function election(): BelongsTo { }

    // Status Checks
    public function isPending(): bool { }
    public function isApproved(): bool { }
    public function hasVoted(): bool { }
    public function isRejected(): bool { }

    // Action Methods
    public function approve(string $approvedBy, array $metadata = []): self { }
    public function reject(string $rejectedBy, string $reason = ''): self { }
    public function markAsVoted(array $metadata = []): self { }

    // Query Scopes
    public function scopePending($query) { }
    public function scopeApproved($query) { }
    public function scopeRejected($query) { }
    public function scopeVoted($query) { }
    public function scopeDemo($query) { }
    public function scopeReal($query) { }
}
```

### User Model Extensions

```php
class User extends Authenticatable
{
    // Relationships
    public function voterRegistrations() { }

    // Registration Methods
    public function demoRegistration() { }
    public function realRegistration() { }
    public function registerForDemoElection(int $electionId): VoterRegistration { }
    public function registerForRealElection(int $electionId): VoterRegistration { }

    // Status Checks
    public function wantsToVoteInDemo(): bool { }
    public function wantsToVoteInReal(): bool { }
    public function canVoteInDemo(): bool { }
    public function canVoteInReal(): bool { }
    public function hasVotedInDemo(): bool { }
    public function hasVotedInReal(): bool { }

    // Query Method
    public function getElectionStatus(int $electionId): ?string { }
}
```

---

## Election Types

### Demo Election

```php
$demo = Election::where('type', 'demo')->first();
// Name: 'Demo Election'
// Purpose: Testing and familiarization
// Typical Behavior:
// - Always active
// - Non-sensitive data
// - Multiple test cycles allowed
// - Results visible immediately
```

**Use Cases:**
- User learns voting process
- System testing
- UI/UX validation
- Committee training

**Configuration Example:**
```php
Election::create([
    'name' => 'Demo Election',
    'slug' => 'demo-election',
    'type' => 'demo',
    'is_active' => true,
    'settings' => [
        'allow_multiple_registrations' => false,
        'require_approval' => true,
        'show_results' => true,
    ]
]);
```

### Real Election

```php
$real = Election::where('type', 'real')->first();
// Name: 'Real Election'
// Purpose: Official voting
// Typical Behavior:
// - Controlled by dates
// - Verified voters only
// - Single vote per person
// - Results confidential until closing
```

**Use Cases:**
- Board elections
- Officer elections
- General membership voting
- Policy decisions

**Configuration Example:**
```php
Election::create([
    'name' => 'Real Election',
    'slug' => 'real-election',
    'type' => 'real',
    'is_active' => false,
    'start_date' => '2026-03-01 09:00:00',
    'end_date' => '2026-03-07 17:00:00',
    'settings' => [
        'allow_multiple_registrations' => false,
        'require_approval' => true,
        'show_results' => false,
        'show_results_after' => true,
    ]
]);
```

---

## Voter Registration Lifecycle

### State: Pending

User has registered but not yet approved.

```php
$registration = VoterRegistration::create([
    'user_id' => $user->id,
    'election_id' => $election->id,
    'status' => 'pending',
    'election_type' => 'demo',
    'registered_at' => now(),
]);

$registration->isPending();       // true
$registration->isApproved();      // false
$registration->hasVoted();        // false

// User can see their pending status
$user->wantsToVoteInDemo();       // true
$user->canVoteInDemo();           // false (not approved yet)
```

### State: Approved

Committee has approved the voter.

```php
$registration->approve('Admin Name', ['verified' => true]);

// After approval
$registration->isApproved();       // true
$registration->approvedBy;         // 'Admin Name'
$registration->approved_at;        // now()

// User can now vote
$user->canVoteInDemo();            // true
```

### State: Voted

User has submitted their vote.

```php
$registration->markAsVoted(['ip' => '192.168.1.1']);

// After voting
$registration->hasVoted();         // true
$registration->voted_at;           // now()

// User cannot vote again
$user->hasVotedInDemo();           // true
```

### State: Rejected

Committee rejected the voter request.

```php
$registration->reject('Admin Name', 'Duplicate registration', [
    'reviewed_at' => now()
]);

// After rejection
$registration->isRejected();       // true
$registration->rejectedBy;         // 'Admin Name'
$registration->rejection_reason;   // 'Duplicate registration'

// User cannot vote
$user->canVoteInDemo();            // false
```

---

## Common Workflows

### Workflow 1: Demo Election User Journey

```
1. User Views App
   └─ Not registered for demo

2. User Clicks "Register for Demo Voting"
   └─ VoterRegistration created (status: pending)
   └─ voter_registration_at = now()

3. Committee Reviews Pending List
   └─ VoterRegistration::pending()->demo()->get()
   └─ Committee approves user

4. User Receives "Approved" Notification
   └─ VoterRegistration.status = approved
   └─ VoterRegistration.approved_at = now()

5. User Clicks "Vote" Button
   └─ Ballot displayed
   └─ User completes voting

6. Vote Submitted
   └─ VoterRegistration.status = voted
   └─ VoterRegistration.voted_at = now()

7. Voting Complete
   └─ User sees confirmation
   └─ Results visible (demo setting)
```

### Workflow 2: Real Election Admin Setup

```
1. Admin Creates Real Election
   Election::create([...])
   └─ type: 'real'
   └─ is_active: false
   └─ start_date: future
   └─ end_date: future

2. Admin Configures Settings
   election.settings = [
       'show_results' => false,
       'show_results_after' => true
   ]

3. Voters Request Access
   user->registerForRealElection($election->id)
   └─ VoterRegistration(status: pending)

4. Admin Reviews and Approves
   registration->approve($approver)

5. Election Start Time Arrives
   election.isCurrentlyActive() returns true

6. Voters Can Access Ballot
   if (user.canVoteInReal()) {
       show_ballot()
   }

7. Voting Submitted
   registration->markAsVoted()

8. Election End Time Arrives
   election.isCurrentlyActive() returns false

9. Results Shown
   Show election results to all
```

### Workflow 3: Bulk Voter Import

```
1. Admin Prepares Voter List (CSV)
   email, name, election_type

2. System Processes Import
   foreach($voters as $voter) {
       $user = User::findByEmail($voter['email']);
       VoterRegistration::create([
           'user_id' => $user->id,
           'election_id' => $election->id,
           'election_type' => $voter['type'],
           'status' => 'pending'
       ]);
   }

3. Pre-approval for Verified Users
   VoterRegistration::where('status', 'pending')
       ->whereIn('user_id', $verified_ids)
       ->update(['status' => 'approved'])

4. Manual Approval for New Users
   Committee reviews remaining pending voters
```

---

## Query Examples

### Get Pending Voters for Demo

```php
// Using relationships
$election = Election::where('type', 'demo')->first();
$pending = $election->pendingVoters()->get();

// Using scopes
$pending = VoterRegistration::pending()
    ->demo()
    ->with('user')
    ->get();

// Manual query
$pending = VoterRegistration::where('status', 'pending')
    ->where('election_type', 'demo')
    ->with('user')
    ->get();
```

### Get User's Real Election Status

```php
$user = auth()->user();

// Method 1: Using relationship
$realReg = $user->realRegistration();
if ($realReg) {
    $status = $realReg->status; // 'pending', 'approved', 'voted', etc.
}

// Method 2: Using query method
$status = $user->getElectionStatus($realElectionId);

// Method 3: Status checks
if ($user->canVoteInReal()) {
    // Show ballot
} elseif ($user->hasVotedInReal()) {
    // Show vote confirmation
} elseif ($user->wantsToVoteInReal()) {
    // Show pending status
}
```

### Get Election Statistics

```php
$election = Election::find($electionId);

$stats = [
    'total_registered' => $election->voterRegistrations()->count(),
    'pending' => $election->pendingVoterCount(),
    'approved' => $election->approvedVoterCount(),
    'voted' => $election->votedCount(),
    'rejected' => $election->voterRegistrations()
        ->where('status', 'rejected')
        ->count(),
];

// Percentages
$stats['voted_percentage'] = ($stats['voted'] / $stats['approved']) * 100;
```

### Filter Voters by Status and Election

```php
// All pending demo voters with user details
$pending = VoterRegistration::pending()
    ->demo()
    ->with('user')
    ->orderBy('created_at', 'asc')
    ->paginate(20);

// All real election voters who have voted
$voted = VoterRegistration::voted()
    ->real()
    ->with('user')
    ->orderBy('voted_at', 'desc')
    ->get();

// Users from specific region requesting demo voting
$europePending = VoterRegistration::pending()
    ->demo()
    ->with(['user' => function($q) {
        $q->where('region', 'Europe');
    }])
    ->get();
```

---

## Seeding

### Default Elections

The `ElectionSeeder` creates two default elections:

```php
// Demo Election
Election::create([
    'name' => 'Demo Election',
    'slug' => 'demo-election',
    'type' => 'demo',
    'is_active' => true,
]);

// Real Election
Election::create([
    'name' => 'Real Election',
    'slug' => 'real-election',
    'type' => 'real',
    'is_active' => false,
]);
```

### Run Seeder

```bash
php artisan db:seed --class=ElectionSeeder
```

### Create Custom Elections

```php
// Create test election for development
Election::create([
    'name' => 'Test Board Election',
    'slug' => 'test-board-2026',
    'type' => 'real',
    'start_date' => now()->addDays(7),
    'end_date' => now()->addDays(14),
    'settings' => [
        'positions' => ['President', 'Vice President', 'Treasurer'],
    ]
]);
```

---

## Testing

### Test Election Creation

```php
public function test_can_create_demo_election()
{
    $election = Election::create([
        'name' => 'Test Demo',
        'slug' => 'test-demo',
        'type' => 'demo',
    ]);

    $this->assertTrue($election->isDemo());
    $this->assertFalse($election->isReal());
}
```

### Test Voter Registration

```php
public function test_user_can_register_for_demo()
{
    $user = User::factory()->create();
    $election = Election::factory()->create(['type' => 'demo']);

    $registration = $user->registerForDemoElection($election->id);

    $this->assertNotNull($registration);
    $this->assertTrue($registration->isPending());
    $this->assertTrue($user->wantsToVoteInDemo());
    $this->assertFalse($user->canVoteInDemo());
}
```

### Test Approval Workflow

```php
public function test_voter_approval_workflow()
{
    $user = User::factory()->create();
    $election = Election::factory()->create(['type' => 'demo']);
    $registration = $user->registerForDemoElection($election->id);

    // Before approval
    $this->assertTrue($registration->isPending());
    $this->assertFalse($user->canVoteInDemo());

    // Approve
    $registration->approve('Admin');

    // After approval
    $this->assertTrue($registration->fresh()->isApproved());
    $this->assertTrue($user->fresh()->canVoteInDemo());
}
```

### Test Vote Recording

```php
public function test_voter_can_submit_vote()
{
    $user = User::factory()->create();
    $election = Election::factory()->create(['type' => 'demo']);
    $registration = $user->registerForDemoElection($election->id);
    $registration->approve('Admin');

    // Submit vote
    $registration->markAsVoted(['ip' => '127.0.0.1']);

    // Verify
    $this->assertTrue($registration->fresh()->hasVoted());
    $this->assertTrue($user->fresh()->hasVotedInDemo());
}
```

---

## Metadata Field Usage

The `metadata` JSON field stores additional context:

```php
// On approval
$registration->approve('Admin', [
    'verification_method' => 'email',
    'verified_at' => now(),
]);

// On vote submission
$registration->markAsVoted([
    'ip_address' => request()->ip(),
    'browser' => request()->header('User-Agent'),
    'vote_timestamp' => now(),
]);

// On rejection
$registration->reject('Admin', 'Duplicate', [
    'duplicate_user_id' => 123,
    'checked_at' => now(),
]);

// Query metadata
$registration = VoterRegistration::find($id);
$ip = $registration->metadata['ip_address'] ?? null;
```

---

## Performance Optimization

### Indexing Strategy

```sql
-- For pending voter queries
INDEX (election_id, status)
-- Covers: WHERE election_id = ? AND status = 'pending'

-- For user registration lookups
INDEX (user_id, election_type)
-- Covers: WHERE user_id = ? AND election_type = 'demo'

-- For status filtering across elections
INDEX (election_type, status)
-- Covers: WHERE election_type = 'demo' AND status = 'voted'

-- Unique constraint prevents duplicates
UNIQUE (user_id, election_id)
-- Enforces: One registration per user per election
```

### Query Optimization

```php
// ✅ FAST: Use with() to eager load
$registrations = VoterRegistration::with('user', 'election')
    ->pending()
    ->demo()
    ->get();

// ❌ SLOW: N+1 query problem
$registrations = VoterRegistration::pending()->demo()->get();
foreach ($registrations as $reg) {
    echo $reg->user->name;  // Additional query for each!
}

// ✅ FAST: Use chunk for large datasets
VoterRegistration::pending()->chunk(1000, function($registrations) {
    // Process chunk
});

// ✅ FAST: Select only needed columns
$ids = VoterRegistration::pending()
    ->demo()
    ->pluck('user_id');
```

---

## Common Issues & Solutions

### Issue: User Can Register Multiple Times

**Problem:** User registers twice for same election

**Solution:** Check existing registration first

```php
// ✅ CORRECT
public function registerForDemo(Request $request)
{
    $existing = VoterRegistration::where('user_id', auth()->id())
        ->where('election_type', 'demo')
        ->first();

    if ($existing) {
        return back()->with('info', 'Already registered');
    }

    $reg = auth()->user()->registerForDemoElection($demo->id);
}
```

### Issue: Approval Not Working

**Debug:**
```php
$registration = VoterRegistration::find($id);
dd([
    'id' => $registration->id,
    'status' => $registration->status,
    'user_id' => $registration->user_id,
    'election_id' => $registration->election_id,
    'before' => $registration->isPending(),
]);

$registration->approve('Admin');

dd([
    'after' => $registration->fresh()->status,
    'approved_by' => $registration->fresh()->approved_by,
    'approved_at' => $registration->fresh()->approved_at,
]);
```

---

## Migration Strategy

### Step 1: Create Elections

```php
php artisan migrate --step  # Runs create_elections_table migration
```

### Step 2: Create Voter Registrations

```php
# Already included in same migration execution
```

### Step 3: Seed Default Elections

```php
php artisan db:seed --class=ElectionSeeder
```

### Step 4: Verify

```bash
php artisan tinker
>>> Election::count()               // Should be 2
>>> Election::where('type', 'demo')->exists()  // true
```

---

## Security Considerations

### Voter Isolation

Each user can only see/modify their own registrations:

```php
// User can see only their registrations
$myRegistrations = auth()->user()->voterRegistrations()->get();

// User cannot modify others' registrations
// Validate ownership before approval
$registration = VoterRegistration::find($id);
if ($registration->user_id !== auth()->id() && !auth()->user()->is_committee_member) {
    abort(403);
}
```

### Metadata Safety

Store sensitive data carefully:

```php
// ✅ SAFE: Store anonymized IP
$registration->metadata = ['ip_masked' => mask_ip($ip)];

// ❌ UNSAFE: Store passwords or tokens
$registration->metadata = ['password' => $password];  // NEVER!
```

### Audit Trail

Always record who approves:

```php
// ✅ CORRECT: Include approver
$registration->approve(auth()->user()->name);

// ✅ BETTER: Include ID too
$registration->approve(auth()->user()->name, [
    'approver_id' => auth()->id(),
    'approved_at' => now(),
]);
```

---

## Future Enhancements

1. **Election Phases**
   - Registration phase
   - Voting phase
   - Results phase

2. **Voter Categories**
   - Student voters
   - Alumni voters
   - Faculty voters

3. **Batch Operations**
   - Bulk approve pending voters
   - Bulk export results
   - Bulk import registrations

4. **Advanced Analytics**
   - Voter turnout by category
   - Registration trends
   - Vote distribution

---

## References

- See `voter-registration-system.md` for individual voter state management
- See `database-schema.md` for complete schema documentation
- See `query-examples.md` for advanced query patterns
- See `ARCHITECTURE.md` for design decisions and tradeoffs
