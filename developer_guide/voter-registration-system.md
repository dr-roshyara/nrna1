# Voter Registration Flag System

## Overview

The Voter Registration Flag System adds a layer of data to the `users` table that indicates whether a user intends to participate in voting, separate from their role as a customer or committee member.

**Problem Solved:** Non-voters were appearing in voter approval lists, creating confusion and workflow issues.

**Solution:** Introduce `wants_to_vote` flag to cleanly separate customer accounts from voter intent.

---

## Database Changes

### New Columns (Users Table)

```sql
ALTER TABLE users ADD COLUMN wants_to_vote BOOLEAN DEFAULT false AFTER is_voter;
ALTER TABLE users ADD COLUMN voter_registration_at TIMESTAMP NULL AFTER wants_to_vote;
ALTER TABLE users ADD INDEX idx_wants_voter (wants_to_vote, is_voter);
```

### Column Details

| Column | Type | Default | Purpose |
|--------|------|---------|---------|
| `wants_to_vote` | boolean | false | User's intent to participate in voting |
| `voter_registration_at` | timestamp | null | When user requested voter status |

### Index Strategy

```
idx_wants_voter (wants_to_vote, is_voter)
```

This composite index enables fast filtering for:
- Customers: `where(wants_to_vote, 0) and where(is_voter, 0)`
- Pending voters: `where(wants_to_vote, 1) and where(is_voter, 0)`
- Approved voters: `where(wants_to_vote, 1) and where(is_voter, 1)`

---

## User States

### State 1: Customer

**Characteristics:**
```php
wants_to_vote = false
is_voter = 0
can_vote = 0
is_committee_member = 0
```

**What it means:** User has account but doesn't want to vote

**Query:**
```php
$customers = User::customers()->get();
// or manually:
User::where('wants_to_vote', false)
    ->where('is_committee_member', 0)
    ->where('is_voter', 0)
    ->get();
```

**Methods:**
```php
$user->isCustomer()          // true
$user->isPendingVoter()      // false
$user->isApprovedVoter()     // false
$user->getVoterState()       // 'customer'
```

### State 2: Pending Voter

**Characteristics:**
```php
wants_to_vote = true
is_voter = 0
can_vote = 0
is_committee_member = 0
voter_registration_at = created_at or register time
```

**What it means:** User requested to vote, awaiting committee approval

**Query:**
```php
$pending = User::pendingVoters()->get();
// or manually:
User::where('wants_to_vote', true)
    ->where('is_voter', 0)
    ->where('can_vote', 0)
    ->where('is_committee_member', 0)
    ->get();
```

**Methods:**
```php
$user->isCustomer()          // false
$user->isPendingVoter()      // true
$user->isApprovedVoter()     // false
$user->getVoterState()       // 'pending_voter'
```

### State 3: Approved Voter

**Characteristics:**
```php
wants_to_vote = true
is_voter = 1
can_vote = 1
is_committee_member = 0
```

**What it means:** User approved by committee and can vote

**Query:**
```php
$approved = User::approvedVoters()->get();
// or manually:
User::where('wants_to_vote', true)
    ->where('is_voter', 1)
    ->where('can_vote', 1)
    ->get();
```

**Methods:**
```php
$user->isCustomer()          // false
$user->isPendingVoter()      // false
$user->isApprovedVoter()     // true
$user->getVoterState()       // 'approved_voter'
```

### State 4: Suspended Voter (Optional)

**Characteristics:**
```php
wants_to_vote = true
is_voter = 1
can_vote = 0
is_committee_member = 0
suspended_at = timestamp
```

**What it means:** Previously approved voter but suspended from voting

**Methods:**
```php
$user->isCustomer()          // false
$user->isPendingVoter()      // false
$user->isApprovedVoter()     // false
$user->getVoterState()       // 'suspended_voter'
```

### State 5: Committee Member

**Characteristics:**
```php
wants_to_vote = false (doesn't participate as voter)
is_committee_member = 1
is_voter = 0 (different role)
```

**What it means:** User manages elections, doesn't vote

**Methods:**
```php
$user->isCustomer()          // false
$user->isPendingVoter()      // false
$user->isApprovedVoter()     // false
$user->getVoterState()       // 'committee_member'
```

---

## Query Scopes

### Scope: customers()

Get all users who are NOT voters

```php
// Get all customers
$customers = User::customers()->get();

// Filter with conditions
$customers = User::customers()
    ->where('region', 'Europe')
    ->get();

// Count
$count = User::customers()->count();

// Check if user is customer
if ($user->isCustomer()) {
    // Show customer dashboard
}
```

**SQL Generated:**
```sql
SELECT * FROM users
WHERE wants_to_vote = false
  AND is_committee_member = 0
  AND is_voter = 0
```

### Scope: pendingVoters()

Get all users requesting to vote but not yet approved

```php
// Get all pending voters
$pending = User::pendingVoters()->get();

// With pagination
$pending = User::pendingVoters()
    ->paginate(20);

// With sorting
$pending = User::pendingVoters()
    ->orderBy('created_at', 'desc')
    ->get();

// Count pending
$count = User::pendingVoters()->count();
```

**SQL Generated:**
```sql
SELECT * FROM users
WHERE wants_to_vote = true
  AND is_voter = 0
  AND can_vote = 0
  AND is_committee_member = 0
```

**Use Cases:**
- Display pending voter list to committee
- Send approval reminders
- Generate approval reports

### Scope: approvedVoters()

Get all users approved to vote

```php
// Get all approved voters
$approved = User::approvedVoters()->get();

// Check if user approved
if ($user->isApprovedVoter()) {
    // Show voting ballot
}

// Count approved
$count = User::approvedVoters()->count();
```

**SQL Generated:**
```sql
SELECT * FROM users
WHERE wants_to_vote = true
  AND is_voter = 1
  AND can_vote = 1
```

**Use Cases:**
- Display voting eligible users
- Generate eligible voter reports
- Send voting reminders

---

## State Methods

### isCustomer()

```php
public function isCustomer(): bool
{
    return !$this->wants_to_vote && !$this->is_voter && !$this->is_committee_member;
}
```

**Usage:**
```php
if ($user->isCustomer()) {
    return redirect('/customer-dashboard');
}
```

### isPendingVoter()

```php
public function isPendingVoter(): bool
{
    return $this->wants_to_vote && !$this->is_voter && !$this->can_vote;
}
```

**Usage:**
```php
if ($user->isPendingVoter()) {
    return view('voting.pending-approval');
}
```

### isApprovedVoter()

```php
public function isApprovedVoter(): bool
{
    return $this->wants_to_vote && $this->is_voter && $this->can_vote;
}
```

**Usage:**
```php
if ($user->isApprovedVoter()) {
    return view('voting.ballot');
}
```

### getVoterState()

Returns one of: `'customer'`, `'pending_voter'`, `'approved_voter'`, `'suspended_voter'`, `'committee_member'`

```php
public function getVoterState(): string
{
    if ($this->is_committee_member) {
        return 'committee_member';
    }

    if ($this->wants_to_vote) {
        if (!$this->is_voter) {
            return 'pending_voter';
        }

        if ($this->is_voter && $this->can_vote) {
            return 'approved_voter';
        }

        if ($this->is_voter && !$this->can_vote) {
            return 'suspended_voter';
        }
    }

    return 'customer';
}
```

**Usage:**
```php
$state = $user->getVoterState();

$stateLabels = [
    'customer' => 'Customer',
    'pending_voter' => 'Pending Approval',
    'approved_voter' => 'Approved Voter',
    'suspended_voter' => 'Suspended',
    'committee_member' => 'Committee',
];

echo $stateLabels[$state];
```

---

## Migration Data Logic

### Initial Population

When the migration runs, it automatically sets `wants_to_vote` based on existing data:

```php
// Committee members: NOT voters
DB::table('users')
    ->where('is_committee_member', 1)
    ->update([
        'wants_to_vote' => false,
        'voter_registration_at' => null,
    ]);

// Pending voters (not approved)
DB::table('users')
    ->where('is_voter', 0)
    ->where('can_vote', 0)
    ->where('is_committee_member', 0)
    ->update([
        'wants_to_vote' => true,
        'voter_registration_at' => DB::raw('created_at'),
    ]);

// Approved voters
DB::table('users')
    ->where('is_voter', 1)
    ->where('can_vote', 1)
    ->update([
        'wants_to_vote' => true,
        'voter_registration_at' => DB::raw('created_at'),
    ]);
```

---

## Common Queries

### Get all users by state

```php
// Customers
$customers = User::customers()->count();

// Pending voters
$pendingVoters = User::pendingVoters()->count();

// Approved voters
$approvedVoters = User::approvedVoters()->count();

// Committee members
$committee = User::where('is_committee_member', 1)->count();

// Summary
$summary = [
    'customers' => User::customers()->count(),
    'pending_voters' => User::pendingVoters()->count(),
    'approved_voters' => User::approvedVoters()->count(),
    'committee' => User::where('is_committee_member', 1)->count(),
];
```

### Filter by region (combining scopes)

```php
// Pending voters in Europe
$europePending = User::pendingVoters()
    ->where('region', 'Europe')
    ->get();

// Approved voters by country
$germanVoters = User::approvedVoters()
    ->where('country', 'Germany')
    ->get();
```

### Get user status quickly

```php
$user = User::find(1);

// Quick state check
$state = $user->getVoterState();

// Route decision
if ($user->isCustomer()) {
    // Customer features
} elseif ($user->isPendingVoter()) {
    // Show pending status
} elseif ($user->isApprovedVoter()) {
    // Show voting ballot
}
```

---

## Guarded Fields

The following fields are protected from mass assignment:

```php
protected $guarded = [
    'id',
    'can_vote',              // CRITICAL
    'has_voted',             // CRITICAL
    'is_voter',              // CRITICAL
    'is_committee_member',   // CRITICAL
    'wants_to_vote',         // NEW: Voter intent
    'approvedBy',            // Audit trail
    'suspendedBy',           // Audit trail
    'suspended_at',          // Audit trail
    'voting_ip',             // Security
    'has_candidacy',         // Candidate status
    'voter_registration_at', // NEW: Registration tracking
    // ... other fields
];
```

### To Set wants_to_vote

```php
// ❌ WRONG: Mass assignment fails
$user = User::create(['wants_to_vote' => true]);

// ✅ RIGHT: Use update() or query
DB::table('users')->where('id', $user->id)->update(['wants_to_vote' => true]);

// ✅ RIGHT: Use direct assignment then save
$user->wants_to_vote = true;
$user->save();
```

---

## Integration Points

### Voter Approval Controller

Update to use `pendingVoters()` scope:

```php
// BEFORE
public function index(Request $request)
{
    $users = User::where('is_voter', 1)->paginate();
}

// AFTER
public function index(Request $request)
{
    $users = User::pendingVoters()->paginate();
}
```

### Voter List Views

Filter to exclude non-voters:

```php
// BEFORE: Shows all users
@foreach($users as $user)

// AFTER: Only voters
@forelse(User::pendingVoters()->get() as $user)
```

### Dashboard Widgets

Show state breakdowns:

```php
$stats = [
    'total_customers' => User::customers()->count(),
    'pending_voters' => User::pendingVoters()->count(),
    'approved_voters' => User::approvedVoters()->count(),
];
```

---

## Testing

### Unit Test Example

```php
public function test_customer_state()
{
    $user = User::factory()->create([
        'wants_to_vote' => false,
        'is_voter' => 0,
        'can_vote' => 0,
        'is_committee_member' => 0,
    ]);

    $this->assertTrue($user->isCustomer());
    $this->assertFalse($user->isPendingVoter());
    $this->assertFalse($user->isApprovedVoter());
    $this->assertEquals('customer', $user->getVoterState());
}

public function test_pending_voter_state()
{
    $user = User::factory()->create([
        'wants_to_vote' => true,
        'is_voter' => 0,
        'can_vote' => 0,
        'is_committee_member' => 0,
        'voter_registration_at' => now(),
    ]);

    $this->assertFalse($user->isCustomer());
    $this->assertTrue($user->isPendingVoter());
    $this->assertFalse($user->isApprovedVoter());
    $this->assertEquals('pending_voter', $user->getVoterState());
}
```

### Scope Test Example

```php
public function test_pending_voters_scope()
{
    User::factory()->create(['wants_to_vote' => false]); // Customer
    User::factory()->create([
        'wants_to_vote' => true,
        'is_voter' => 0,
        'can_vote' => 0,
    ]); // Pending
    User::factory()->create([
        'wants_to_vote' => true,
        'is_voter' => 1,
        'can_vote' => 1,
    ]); // Approved

    $pending = User::pendingVoters()->count();
    $this->assertEquals(1, $pending);
}
```

---

## Troubleshooting

### Issue: User appears in wrong category

**Symptom:** Customer showing as pending voter

**Debug:**
```php
$user = User::find($userId);
dd([
    'wants_to_vote' => $user->wants_to_vote,
    'is_voter' => $user->is_voter,
    'can_vote' => $user->can_vote,
    'is_committee_member' => $user->is_committee_member,
    'isCustomer' => $user->isCustomer(),
    'isPending' => $user->isPendingVoter(),
    'isApproved' => $user->isApprovedVoter(),
]);
```

**Fix:** Use direct DB update if mass assignment is blocked

### Issue: Queries returning wrong results

**Debug:**
```php
// Check actual values
DB::table('users')->select('id', 'wants_to_vote', 'is_voter', 'can_vote')->get();

// Check index usage
EXPLAIN SELECT * FROM users WHERE wants_to_vote = 1 AND is_voter = 0;
```

---

## Performance Considerations

### Query Performance

All scopes use indexed columns:
- `wants_to_vote` (part of composite index)
- `is_voter` (part of composite index)

Composite index `idx_wants_voter(wants_to_vote, is_voter)` covers:
- State filtering
- Pending voter queries
- Approved voter queries

### Optimization Tips

```php
// ❌ SLOW: Fetching all then filtering in PHP
$users = User::all()->filter(function($u) {
    return $u->isPendingVoter();
});

// ✅ FAST: Filter at database level
$users = User::pendingVoters()->get();

// ✅ FAST: Use pluck for large datasets
$customerIds = User::customers()->pluck('id');

// ✅ FAST: Use chunk for memory efficiency
User::pendingVoters()->chunk(1000, function($users) {
    // Process chunk
});
```

---

## Migration Rollback

If needed to rollback this system:

```bash
php artisan migrate:rollback --step=1
```

This removes:
- `wants_to_vote` column
- `voter_registration_at` column
- `idx_wants_voter` index

**Warning:** All data in these columns is lost. Export if needed first:

```php
// Export before rollback
$data = User::select(['id', 'wants_to_vote', 'voter_registration_at'])->get()->toArray();
// Save to file or database
```

---

## Future Enhancements

1. **Voter Grade/Tier System**
   - Add `voter_tier` column (basic, silver, gold)
   - Different approval requirements per tier

2. **Registration Batch Import**
   - Import `wants_to_vote` from CSV
   - Bulk set `voter_registration_at`

3. **Voter Statistics**
   - Dashboard showing state distribution
   - Trends over time

4. **Automated Transitions**
   - Auto-move pending to approved based on criteria
   - Auto-suspend on inactivity

---

## References

- See `election-system.md` for multi-election voter tracking
- See `database-schema.md` for complete schema details
- See `query-examples.md` for advanced queries
