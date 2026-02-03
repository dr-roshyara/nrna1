# Query Examples & Patterns

## Table of Contents

1. [Voter State Queries](#voter-state-queries)
2. [Election Queries](#election-queries)
3. [Voter Registration Queries](#voter-registration-queries)
4. [Statistics & Reports](#statistics--reports)
5. [Advanced Patterns](#advanced-patterns)
6. [Performance Tips](#performance-tips)

---

## Voter State Queries

### Get Users by State

```php
// Customers only
$customers = User::customers()->get();
// SELECT * FROM users WHERE wants_to_vote = 0 AND is_committee_member = 0 AND is_voter = 0

// Pending voters
$pending = User::pendingVoters()->get();
// SELECT * FROM users WHERE wants_to_vote = 1 AND is_voter = 0 AND can_vote = 0 AND is_committee_member = 0

// Approved voters
$approved = User::approvedVoters()->get();
// SELECT * FROM users WHERE wants_to_vote = 1 AND is_voter = 1 AND can_vote = 1

// Committee members
$committee = User::where('is_committee_member', 1)->get();
```

### Check User State

```php
// For a single user
$user = User::find(1);

if ($user->isCustomer()) {
    // Show customer features
} elseif ($user->isPendingVoter()) {
    // Show pending status
} elseif ($user->isApprovedVoter()) {
    // Show voting ballot
} elseif ($user->is_committee_member) {
    // Show admin dashboard
}

// Or use state directly
$state = $user->getVoterState();
// Returns: 'customer', 'pending_voter', 'approved_voter', 'suspended_voter', 'committee_member'
```

### Filter by Region/Country

```php
// Pending voters from Europe
$europePending = User::pendingVoters()
    ->where('region', 'Europe')
    ->get();

// Approved voters from Germany
$germanyVoters = User::approvedVoters()
    ->where('country', 'Germany')
    ->get();

// Customers in Asia Pacific
$asiaCustomers = User::customers()
    ->where('region', 'Asia Pacific')
    ->get();
```

### Count Users by State

```php
$stats = [
    'customers' => User::customers()->count(),
    'pending_voters' => User::pendingVoters()->count(),
    'approved_voters' => User::approvedVoters()->count(),
    'committee' => User::where('is_committee_member', 1)->count(),
    'total' => User::count(),
];

// Result:
[
    'customers' => 15,
    'pending_voters' => 3,
    'approved_voters' => 42,
    'committee' => 5,
    'total' => 65,
]
```

---

## Election Queries

### Get All Elections

```php
// All elections
$all = Election::all();

// Demo elections only
$demo = Election::where('type', 'demo')->get();

// Real elections only
$real = Election::where('type', 'real')->get();

// Active elections
$active = Election::where('is_active', true)->get();
```

### Get Election by Slug

```php
$demo = Election::where('slug', 'demo-election')->first();
$real = Election::where('slug', 'real-election')->first();
```

### Check if Election is Active

```php
$election = Election::find(1);

// Is demo/real?
if ($election->isDemo()) {
    // Demo election logic
}

if ($election->isReal()) {
    // Real election logic
}

// Is currently active (considering dates)?
if ($election->isCurrentlyActive()) {
    // Can vote now
}
```

### Get Election Settings

```php
$election = Election::find(1);

// Access settings
$requireApproval = $election->settings['require_approval'] ?? true;
$showResults = $election->settings['show_results'] ?? false;

// Modify settings
$election->settings = [
    'require_approval' => true,
    'show_results' => false,
];
$election->save();
```

---

## Voter Registration Queries

### Get Registrations for Election

```php
$election = Election::where('slug', 'demo-election')->first();

// All registrations
$all = $election->voterRegistrations()->get();

// Pending only
$pending = $election->pendingVoters()->get();

// Approved only
$approved = $election->approvedVoters()->get();

// Have voted
$voted = $election->votedVoters()->get();
```

### Get User's Registration in Election

```php
$user = User::find(1);
$election = Election::find(1);

// Method 1: Via user
$registration = $user->voterRegistrations()
    ->where('election_id', $election->id)
    ->first();

// Method 2: Query directly
$registration = VoterRegistration::where('user_id', $user->id)
    ->where('election_id', $election->id)
    ->first();

// Check status
if ($registration) {
    echo $registration->status;  // 'pending', 'approved', 'voted', etc.
}
```

### Get Demo/Real Registration for User

```php
$user = User::find(1);

// Demo registration
$demoReg = $user->demoRegistration();

// Real registration
$realReg = $user->realRegistration();

// Check if exists
if ($demoReg) {
    echo "Registered for demo: " . $demoReg->status;
}
```

### Query Registrations by Status

```php
// All pending registrations
$pending = VoterRegistration::pending()->get();

// All approved registrations
$approved = VoterRegistration::approved()->get();

// All voted registrations
$voted = VoterRegistration::voted()->get();

// All rejected registrations
$rejected = VoterRegistration::rejected()->get();
```

### Query Registrations by Election Type

```php
// All demo registrations
$demo = VoterRegistration::demo()->get();

// All real registrations
$real = VoterRegistration::real()->get();

// Combination: Pending demo voters
$pendingDemo = VoterRegistration::pending()
    ->demo()
    ->get();

// Combination: Voted real voters
$votedReal = VoterRegistration::voted()
    ->real()
    ->get();
```

### Get Registrations with User Details

```php
// Eager load user
$registrations = VoterRegistration::pending()
    ->with('user')
    ->get();

// Access user data
foreach ($registrations as $reg) {
    echo $reg->user->name;
    echo $reg->user->email;
}

// Or with election details
$registrations = VoterRegistration::demo()
    ->with(['user', 'election'])
    ->get();
```

---

## Statistics & Reports

### Election Statistics

```php
$election = Election::find(1);

$stats = [
    'total_registered' => $election->voterRegistrations()->count(),
    'pending' => $election->pendingVoterCount(),
    'approved' => $election->approvedVoterCount(),
    'voted' => $election->votedCount(),
    'rejected' => $election->voterRegistrations()
        ->where('status', 'rejected')
        ->count(),
];

// Calculate percentages
$stats['approval_rate'] = ($stats['approved'] / $stats['total_registered']) * 100;
$stats['vote_rate'] = ($stats['voted'] / $stats['approved']) * 100;
```

### Voter State Summary

```php
$summary = [
    'total_users' => User::count(),
    'customers' => User::customers()->count(),
    'pending_voters' => User::pendingVoters()->count(),
    'approved_voters' => User::approvedVoters()->count(),
    'committee' => User::where('is_committee_member', 1)->count(),
];

// Calculate percentages
$summary['customer_percent'] = ($summary['customers'] / $summary['total_users']) * 100;
$summary['voter_percent'] = (($summary['pending_voters'] + $summary['approved_voters']) / $summary['total_users']) * 100;
```

### Timeline Report (by Registration Date)

```php
// Group by date
$byDate = DB::table('voter_registrations')
    ->selectRaw('DATE(registered_at) as date, COUNT(*) as count')
    ->groupBy('date')
    ->orderBy('date', 'desc')
    ->get();

// Result:
[
    ['date' => '2026-02-03', 'count' => 15],
    ['date' => '2026-02-02', 'count' => 8],
    ['date' => '2026-02-01', 'count' => 22],
]
```

### Approval Timeline Report

```php
$byApprovalDate = DB::table('voter_registrations')
    ->selectRaw('DATE(approved_at) as date, COUNT(*) as count')
    ->whereNotNull('approved_at')
    ->groupBy('date')
    ->orderBy('date', 'desc')
    ->get();
```

### Voting Timeline Report

```php
$byVoteDate = DB::table('voter_registrations')
    ->selectRaw('DATE(voted_at) as date, COUNT(*) as count')
    ->whereNotNull('voted_at')
    ->groupBy('date')
    ->orderBy('date')
    ->get();
```

---

## Advanced Patterns

### Find Users Registered for Both Demo and Real

```php
$bothElections = User::whereHas('voterRegistrations', function($q) {
    $q->where('election_type', 'demo')
      ->where('status', 'pending');
})
->whereHas('voterRegistrations', function($q) {
    $q->where('election_type', 'real')
      ->where('status', 'pending');
})
->get();
```

### Find Pending Voters Not Yet Approved (with details)

```php
$pending = VoterRegistration::pending()
    ->demo()
    ->with('user')
    ->orderBy('registered_at', 'asc')
    ->paginate(20);

// Output:
foreach ($pending as $reg) {
    echo $reg->user->name . " - Registered: " . $reg->registered_at->diffForHumans();
}
```

### Find Recently Voted Users

```php
$recentlyVoted = VoterRegistration::voted()
    ->where('voted_at', '>', now()->subHours(24))
    ->with('user', 'election')
    ->orderBy('voted_at', 'desc')
    ->get();
```

### Find Voters by Region and Status

```php
$voters = VoterRegistration::approved()
    ->demo()
    ->with('user')
    ->get()
    ->filter(function($reg) {
        return $reg->user->region === 'Europe';
    });

// Or in query:
$voters = VoterRegistration::approved()
    ->demo()
    ->with(['user' => function($q) {
        $q->where('region', 'Europe');
    }])
    ->get();
```

### Find Duplicate Registrations (Users registered multiple times)

```php
$duplicates = DB::table('voter_registrations')
    ->selectRaw('user_id, election_id, COUNT(*) as count')
    ->groupBy('user_id', 'election_id')
    ->having('count', '>', 1)
    ->get();

// With user details
$duplicates = VoterRegistration::selectRaw('user_id, election_id, COUNT(*) as count')
    ->groupBy('user_id', 'election_id')
    ->having('count', '>', 1)
    ->with('user')
    ->get();
```

### Find Users Who Registered but Never Approved

```php
$registered = User::has('voterRegistrations')
    ->doesntHave('voterRegistrations', 'and', function($q) {
        $q->where('status', 'approved');
    })
    ->get();

// Or:
$userIds = VoterRegistration::where('status', '!=', 'approved')->pluck('user_id');
$registered = User::whereIn('id', $userIds)->get();
```

### Progressive Filter (Funnel Analysis)

```php
$funnelAnalysis = [
    'total_users' => User::count(),
    'registered_for_demo' => User::has('voterRegistrations', 'and', function($q) {
        $q->where('election_type', 'demo');
    })->count(),
    'approved_for_demo' => User::has('voterRegistrations', 'and', function($q) {
        $q->where('election_type', 'demo')
          ->where('status', 'approved');
    })->count(),
    'voted_in_demo' => User::has('voterRegistrations', 'and', function($q) {
        $q->where('election_type', 'demo')
          ->where('status', 'voted');
    })->count(),
];

// Calculate drop-off
$funnelAnalysis['registration_rate'] =
    ($funnelAnalysis['registered_for_demo'] / $funnelAnalysis['total_users']) * 100;
$funnelAnalysis['approval_rate'] =
    ($funnelAnalysis['approved_for_demo'] / $funnelAnalysis['registered_for_demo']) * 100;
$funnelAnalysis['voting_rate'] =
    ($funnelAnalysis['voted_in_demo'] / $funnelAnalysis['approved_for_demo']) * 100;
```

---

## Performance Tips

### 1. Use Eager Loading

```php
// ❌ SLOW: N+1 problem
foreach (VoterRegistration::all() as $reg) {
    echo $reg->user->name;  // Extra query for each
}

// ✅ FAST: Eager load
foreach (VoterRegistration::with('user')->get() as $reg) {
    echo $reg->user->name;  // No extra queries
}
```

### 2. Use Scopes

```php
// ❌ SLOW: Multiple conditions
VoterRegistration::where('status', 'pending')
    ->where('election_type', 'demo')
    ->where('is_active', 1)
    ->get();

// ✅ FAST: Use scopes
VoterRegistration::pending()
    ->demo()
    ->get();
```

### 3. Use Pagination

```php
// ❌ SLOW: All records at once
$all = VoterRegistration::pending()->get();  // Thousands of records

// ✅ FAST: Paginate
$page = VoterRegistration::pending()->paginate(20);
```

### 4. Select Only Needed Columns

```php
// ❌ SLOW: All columns
$registrations = VoterRegistration::pending()->get();

// ✅ FAST: Only needed
$registrations = VoterRegistration::pending()
    ->select('id', 'user_id', 'status')
    ->get();

// ✅ VERY FAST: Just IDs
$ids = VoterRegistration::pending()->pluck('id');
```

### 5. Use Chunk for Large Datasets

```php
// ❌ SLOW: Load all in memory
VoterRegistration::demo()->get()->each(function($reg) {
    // Process
});

// ✅ FAST: Process in chunks
VoterRegistration::demo()->chunk(1000, function($registrations) {
    foreach ($registrations as $reg) {
        // Process
    }
});
```

### 6. Use Raw Expressions for Aggregations

```php
// ❌ SLOW: Fetch and count in PHP
$count = VoterRegistration::pending()->get()->count();

// ✅ FAST: Count at database
$count = VoterRegistration::pending()->count();
```

### 7. Use Database Indexes

```php
// Ensure queries use indexes
// Check with EXPLAIN:
EXPLAIN SELECT * FROM voter_registrations
WHERE election_id = 1 AND status = 'pending';
// Should show: type = ref, key = idx_election_status
```

---

## Common Query Patterns

### Pattern 1: List Pending Approvals

```php
public function pendingApprovals()
{
    return VoterRegistration::pending()
        ->demo()
        ->with('user')
        ->orderBy('registered_at', 'asc')
        ->paginate(50);
}
```

### Pattern 2: Check User Eligibility

```php
public function canUserVote($user, $electionId)
{
    return VoterRegistration::where('user_id', $user->id)
        ->where('election_id', $electionId)
        ->where('status', 'approved')
        ->exists();
}
```

### Pattern 3: Get User's Current Status

```php
public function getUserStatus($user, $electionId)
{
    $registration = VoterRegistration::where('user_id', $user->id)
        ->where('election_id', $electionId)
        ->first();

    return $registration?->status ?? 'not_registered';
}
```

### Pattern 4: Record a Vote

```php
public function recordVote($user, $electionId, $data)
{
    $registration = VoterRegistration::where('user_id', $user->id)
        ->where('election_id', $electionId)
        ->where('status', 'approved')
        ->first();

    if (!$registration) {
        throw new Exception('Not approved to vote');
    }

    $registration->markAsVoted([
        'ip' => request()->ip(),
        'voted_at' => now(),
    ]);

    return $registration;
}
```

### Pattern 5: Get Election Dashboard

```php
public function getElectionDashboard($electionId)
{
    $election = Election::findOrFail($electionId);

    return [
        'election' => $election,
        'stats' => [
            'registered' => $election->voterRegistrations()->count(),
            'pending' => $election->pendingVoterCount(),
            'approved' => $election->approvedVoterCount(),
            'voted' => $election->votedCount(),
        ],
        'pending_list' => $election->pendingVoters()
            ->with('user')
            ->limit(10)
            ->get(),
    ];
}
```

---

## Debugging Queries

### Enable Query Logging

```php
// Enable in tinker
DB::enableQueryLog();

// Run queries
User::pendingVoters()->get();

// View queries
dd(DB::getQueryLog());
```

### Use Explain

```bash
php artisan tinker

# Analyze query
>>> DB::connection()->enableQueryLog();
>>> $results = VoterRegistration::pending()->demo()->get();
>>> DB::getQueryLog();
```

### Check Indexes

```php
// Get index information
$indexes = DB::connection()
    ->getDoctrineSchemaManager()
    ->listTableIndexes('voter_registrations');

foreach ($indexes as $index) {
    echo $index->getName() . ": " . implode(', ', $index->getColumns());
}
```

---

## References

- Laravel Query Builder
- Eloquent ORM Documentation
- Database Schema (see `database-schema.md`)
- Migration Guide (see `migration-guide.md`)
