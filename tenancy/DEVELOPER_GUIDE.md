# 🎓 Developer Guide: Election System with Organisation ID

**Last Updated**: 2026-02-20
**Version**: 1.0 (Complete)
**Status**: Production Ready

---

## 📚 Table of Contents

1. [Architecture Overview](#architecture-overview)
2. [Core Concepts](#core-concepts)
3. [Multi-Tenancy Model](#multi-tenancy-model)
4. [4-Layer Security Architecture](#4-layer-security-architecture)
5. [Working with Elections](#working-with-elections)
6. [Working with Votes](#working-with-votes)
7. [Working with Results](#working-with-results)
8. [Code Examples](#code-examples)
9. [Best Practices](#best-practices)
10. [Common Pitfalls](#common-pitfalls)
11. [Debugging & Logging](#debugging--logging)
12. [Testing](#testing)

---

## Architecture Overview

### System Components

```
┌─────────────────────────────────────────────────────────────┐
│                    PUBLIC DIGIT PLATFORM                     │
├─────────────────────────────────────────────────────────────┤
│                                                               │
│  ┌──────────────────────────────────────────────────────┐   │
│  │         Voting System (This Guide)                    │   │
│  │  ┌────────────────────────────────────────────────┐  │   │
│  │  │  4-Layer Security Architecture                 │  │   │
│  │  │                                                │  │   │
│  │  │  Layer 4: MIDDLEWARE (Pre-Request)            │  │   │
│  │  │    └─ organisation_id validation              │  │   │
│  │  │                                                │  │   │
│  │  │  Layer 3: CONTROLLER (Application)            │  │   │
│  │  │    └─ Business logic validation               │  │   │
│  │  │                                                │  │   │
│  │  │  Layer 2: MODEL (Data Integrity)              │  │   │
│  │  │    └─ Validation hooks                        │  │   │
│  │  │                                                │  │   │
│  │  │  Layer 1: DATABASE (Physical)                 │  │   │
│  │  │    └─ Constraints & foreign keys              │  │   │
│  │  │                                                │  │   │
│  │  └────────────────────────────────────────────────┘  │   │
│  │                                                        │   │
│  │  ┌──────────────────────────────────────────────────┐  │   │
│  │  │  Data Model                                      │  │   │
│  │  │  • Election (has organisation_id)               │  │   │
│  │  │  • Vote (references election + org)             │  │   │
│  │  │  • Result (references vote + org)               │  │   │
│  │  │  • DemoVote (separate table, no org)            │  │   │
│  │  │  • DemoResult (separate table, no org)          │  │   │
│  │  └──────────────────────────────────────────────────┘  │   │
│  └──────────────────────────────────────────────────────┘   │
│                                                               │
│  ┌──────────────────────────────────────────────────────┐   │
│  │  Multi-Tenancy Features                              │   │
│  │  • BelongsToTenant trait (automatic scoping)        │   │
│  │  • Global query scopes (filter by org)              │   │
│  │  • TenantContext middleware (session-based)         │   │
│  │  • Demo vs Real voting modes                        │   │
│  └──────────────────────────────────────────────────────┘   │
│                                                               │
└─────────────────────────────────────────────────────────────┘
```

---

## Core Concepts

### 1. What is organisation_id?

**organisation_id** is a **tenant identifier** that isolates voting data between different organizations.

```php
// organisation_id values:
null     → Demo election (no organization)
1        → Organization A's real elections
2        → Organization B's real elections
etc.
```

### 2. Real Elections vs Demo Elections

```php
// REAL ELECTION
Election::create([
    'name' => 'Annual General Meeting',
    'type' => 'real',                    // ← CRITICAL
    'organisation_id' => 1,              // ← REQUIRED
    'start_date' => '2026-03-01',
    'end_date' => '2026-03-31',
]);

// DEMO ELECTION
Election::create([
    'name' => 'Demo Election',
    'type' => 'demo',                    // ← Different type
    'organisation_id' => null,           // ← Always NULL
    'start_date' => '2026-03-01',
    'end_date' => '2026-03-31',
]);
```

**Key Difference**:
- **Real Elections**: organisation_id required, 4-layer security enforced
- **Demo Elections**: organisation_id ignored, no security validation

### 3. BelongsToTenant Trait

The `BelongsToTenant` trait automatically filters records by organisation_id:

```php
class Election extends Model
{
    use BelongsToTenant;  // ← Adds automatic scoping
}

// Query automatically filtered by current organisation
$elections = Election::all();  // Only returns current org's elections
```

**How it works**:
```php
// Every query is automatically modified:
SELECT * FROM elections WHERE organisation_id = ? (current user's org)

// To query across all organisations (admin only):
Election::withoutGlobalScopes()->get();
```

### 4. User Organisation Context

Every authenticated user has an `organisation_id`:

```php
$user = auth()->user();
$user->organisation_id;  // 1, 2, null, etc.

// User's organisation is stored in session:
session('current_organisation_id');  // Set by TenantContext middleware
```

---

## Multi-Tenancy Model

### Data Isolation Pattern

```
┌─────────────────────────────────────────────────────┐
│            DATABASE STRUCTURE                        │
├─────────────────────────────────────────────────────┤
│                                                      │
│  elections table:                                    │
│  ├─ id (PK)                                          │
│  ├─ organisation_id (FK) ← CRITICAL                 │
│  ├─ name                                             │
│  └─ type ('real' or 'demo')                          │
│                                                      │
│  votes table:                                        │
│  ├─ id (PK)                                          │
│  ├─ election_id (FK)                                 │
│  ├─ organisation_id (FK) ← MUST MATCH election      │
│  ├─ voting_code                                      │
│  └─ ...                                              │
│                                                      │
│  results table:                                      │
│  ├─ id (PK)                                          │
│  ├─ vote_id (FK)                                     │
│  ├─ organisation_id (FK) ← MUST MATCH vote          │
│  ├─ post_id (FK)                                     │
│  ├─ candidacy_id (FK)                                │
│  └─ ...                                              │
│                                                      │
│  demo_votes table: (SEPARATE from votes)             │
│  ├─ id (PK)                                          │
│  ├─ election_id (FK)                                 │
│  ├─ organisation_id (always NULL)                    │
│  └─ ...                                              │
│                                                      │
│  demo_results table: (SEPARATE from results)         │
│  ├─ id (PK)                                          │
│  ├─ vote_id (FK to demo_votes)                       │
│  ├─ organisation_id (always NULL)                    │
│  └─ ...                                              │
│                                                      │
└─────────────────────────────────────────────────────┘

KEY PRINCIPLE:
  Votes and Results ALWAYS reference the election's organisation_id
  Never allow mismatches between vote and election organisation
```

### Composite Foreign Key Pattern

The system uses **composite foreign keys** to enforce organisation consistency:

```sql
-- Composite FK: votes must have matching election org
ALTER TABLE votes ADD CONSTRAINT votes_election_org_fk
FOREIGN KEY (election_id, organisation_id)
REFERENCES elections(id, organisation_id)
ON DELETE CASCADE;

-- Composite FK: results must have matching vote org
ALTER TABLE results ADD CONSTRAINT results_vote_org_fk
FOREIGN KEY (vote_id, organisation_id)
REFERENCES votes(id, organisation_id)
ON DELETE CASCADE;

-- Composite Unique Index: one election per org
ALTER TABLE elections ADD UNIQUE INDEX elections_id_org_unique
(id, organisation_id);

-- Composite Unique Index: one vote per org
ALTER TABLE votes ADD UNIQUE INDEX votes_id_org_unique
(id, organisation_id);
```

**Why composite keys?**
- Prevents cross-organisation references
- Database enforces data integrity
- Impossible to create invalid vote-election combinations

---

## 4-Layer Security Architecture

### Layer 1: Database (Physical Boundary)

**Location**: Database schema and constraints

**What it does**:
- ✅ NOT NULL constraints on organisation_id
- ✅ Composite foreign keys
- ✅ Unique indexes per organisation

**When it activates**: When data is inserted/updated

**Example**:
```sql
-- This will FAIL - violates NOT NULL constraint
INSERT INTO votes (election_id, organisation_id, voting_code)
VALUES (1, NULL, 'TEST');
-- Error: Field 'organisation_id' doesn't have a default value

-- This will FAIL - violates composite FK
INSERT INTO votes (election_id, organisation_id, voting_code)
VALUES (1, 2, 'TEST');  -- election 1 is in org 1, not 2
-- Error: Cannot add or update child row
```

### Layer 2: Model (Data Integrity)

**Location**: `app/Models/BaseVote.php`, `app/Models/BaseResult.php`

**What it does**:
```php
protected static function booted()
{
    static::creating(function ($vote) {
        // Skip for demo votes
        if (get_class($vote) !== Vote::class) return;

        // CRITICAL 1: organisation_id NOT NULL
        if (is_null($vote->organisation_id)) {
            throw new InvalidRealVoteException('Organisation context required');
        }

        // CRITICAL 2: election exists
        $election = Election::withoutGlobalScopes()->find($vote->election_id);
        if (!$election) {
            throw new InvalidRealVoteException('Election not found');
        }

        // CRITICAL 3: organisation matches
        if ($vote->organisation_id !== $election->organisation_id) {
            throw new OrganisationMismatchException('Organisation mismatch');
        }

        // CRITICAL 4: election is 'real'
        if ($election->type !== 'real') {
            throw new InvalidRealVoteException('Not a real election');
        }
    });
}
```

**When it activates**: When `Model::create()` is called

**Catches**:
- ✅ NULL organisation_id before saving
- ✅ Invalid references before saving
- ✅ Mismatched organisations before saving

### Layer 3: Controller (Application)

**Location**: `app/Http/Controllers/VoteController.php::store()`

**What it does**:
```php
public function store(Request $request)
{
    $election = $this->getElection($request);

    // PHASE 3 VALIDATION: Check election type
    if ($election->type !== 'real') {
        return redirect()->withErrors([
            'vote' => 'This election is not available for voting.'
        ]);
    }

    // PHASE 3 VALIDATION: Check organisation match
    if ($auth_user->organisation_id !== $election->organisation_id) {
        return redirect()->withErrors([
            'vote' => 'You do not have permission to vote in this election.'
        ]);
    }

    // Create vote with EXPLICIT organisation_id
    $vote = Vote::create([
        'election_id' => $election->id,
        'organisation_id' => $election->organisation_id,  // ← EXPLICIT
        'voting_code' => $votingCode,
    ]);

    // Create results with EXPLICIT organisation_id
    $result = Result::create([
        'vote_id' => $vote->id,
        'organisation_id' => $election->organisation_id,  // ← EXPLICIT
        'post_id' => $post_id,
        'candidacy_id' => $candidacy_id,
    ]);
}
```

**When it activates**: When vote submission request reaches controller

**Catches**:
- ✅ Demo elections (redirects with user-friendly message)
- ✅ Organisation mismatches (redirects with permission error)
- ✅ Sets organisation_id explicitly (visible in code)

**Key**: Sets organisation_id explicitly from election, not from user session

### Layer 4: Middleware (Pre-Request)

**Location**: `app/Http/Middleware/EnsureRealVoteOrganisation.php`

**What it does**:
```php
public function handle(Request $request, Closure $next): Response
{
    $election = $request->attributes->get('election');

    // BACKWARD COMPATIBILITY: Demo elections bypass ALL checks
    if ($election->type === 'demo') {
        return $next($request);  // No validation for demo
    }

    // Get authenticated user
    $user = auth()->user();

    // CRITICAL: User's org must match election's org
    if ($user->organisation_id !== $election->organisation_id) {
        \Log::channel('voting_security')->error(
            'Organisation mismatch blocked at middleware',
            [
                'user_org' => $user->organisation_id,
                'election_org' => $election->organisation_id,
            ]
        );

        return back()->withErrors([
            'organisation' => 'You do not have permission...'
        ]);
    }

    // Validation passed - continue to controller
    return $next($request);
}
```

**When it activates**: BEFORE request reaches controller

**Catches**:
- ✅ Invalid organisation at EARLIEST point
- ✅ Blocks request before controller execution
- ✅ Logs security incident
- ✅ Bypasses for demo elections

**Key**: Validation happens at request entry point

---

## Working with Elections

### Creating Elections

#### Real Election (For an Organisation)

```php
$election = Election::create([
    'name' => 'Annual General Meeting 2026',
    'slug' => 'agm-2026',
    'description' => 'Organisation-wide voting',
    'type' => 'real',                    // ← CRITICAL
    'organisation_id' => 1,              // ← REQUIRED
    'is_active' => true,
    'start_date' => '2026-03-01 00:00:00',
    'end_date' => '2026-03-31 23:59:59',
]);

// ✅ Valid - real election with organisation
```

#### Demo Election (No Organisation)

```php
$election = Election::create([
    'name' => 'Demo Election',
    'slug' => 'demo-election',
    'description' => 'For testing voting system',
    'type' => 'demo',                   // ← Demo type
    'organisation_id' => null,          // ← Always NULL
    'is_active' => true,
    'start_date' => '2026-03-01 00:00:00',
    'end_date' => '2026-03-31 23:59:59',
]);

// ✅ Valid - demo election without organisation
```

### Querying Elections

#### As Authenticated User (Scoped)

```php
// User with organisation_id = 1
auth()->user()->organisation_id;  // 1

// All queries automatically filtered
$elections = Election::all();
// SQL: SELECT * FROM elections WHERE organisation_id = 1

// Only sees this user's organisation's elections
$elections->each(fn($e) => $e->organisation_id === 1);  // ✅ true for all
```

#### Cross-Organisation Query (Admin Only)

```php
// Must explicitly bypass global scope
$allElections = Election::withoutGlobalScopes()->get();

// Includes elections from ALL organisations
$election1 = $allElections->find(1);  // organisation_id = 1
$election2 = $allElections->find(2);  // organisation_id = 2
$demoElection = $allElections->find(3);  // organisation_id = null
```

### Finding Elections

```php
// For current user's organisation (RECOMMENDED)
$election = Election::find(1);  // Automatically filtered

// For specific election regardless of organisation (admin)
$election = Election::withoutGlobalScopes()->find(1);

// For demo election (special case)
$demoElection = Election::withoutGlobalScopes()
    ->where('type', 'demo')
    ->first();

// For real elections only
$realElections = Election::where('type', 'real')->get();
```

---

## Working with Votes

### Creating Real Votes

#### ✅ Correct Way (Sets organisation_id explicitly)

```php
$election = Election::find($electionId);  // Must be real election

$vote = Vote::create([
    'election_id' => $election->id,
    'organisation_id' => $election->organisation_id,  // ← EXPLICIT
    'voting_code' => $votingCode,
    'no_vote_option' => false,
]);

// ✅ Vote created with explicit organisation_id
// ✓ Passes all 4 layers of validation
```

#### ❌ Incorrect Ways

```php
// WRONG: Relying on BelongsToTenant trait only
$vote = Vote::create([
    'election_id' => $electionId,
    'voting_code' => $votingCode,
    // organisation_id not set explicitly
]);
// ❌ Fails Phase 3 validation (not explicit)
// ❌ Bad practice - hidden behavior

// WRONG: Setting organisation_id from user
$vote = Vote::create([
    'election_id' => $electionId,
    'organisation_id' => auth()->user()->organisation_id,  // WRONG SOURCE
    'voting_code' => $votingCode,
]);
// ❌ Could mismatch if user and election in different orgs
// ❌ Election's organisation_id is the source of truth

// WRONG: NULL organisation_id for real vote
$vote = Vote::create([
    'election_id' => $electionId,
    'organisation_id' => null,  // NULL not allowed
    'voting_code' => $votingCode,
]);
// ❌ Fails Layer 2 validation (NULL not allowed)
// ❌ Fails Layer 1 constraint (NOT NULL)
```

### Creating Demo Votes

```php
$demoElection = Election::withoutGlobalScopes()
    ->where('type', 'demo')
    ->first();

$demoVote = DemoVote::create([
    'election_id' => $demoElection->id,
    'organisation_id' => null,  // ← Always NULL for demo
    'voting_code' => $votingCode,
]);

// ✅ Demo vote created
// ✓ Uses separate DemoVote model
// ✓ No organisation validation applied
// ✓ No security checks
```

### Querying Votes

```php
// As regular user (scoped by organisation)
$votes = Vote::all();
// Only sees votes from user's organisation

// As admin (all organisations)
$allVotes = Vote::withoutGlobalScopes()->get();

// Find specific vote
$vote = Vote::find($voteId);  // Filtered by org
$vote = Vote::withoutGlobalScopes()->find($voteId);  // Not filtered

// For demo votes (different table)
$demoVotes = DemoVote::all();
```

---

## Working with Results

### Creating Results

#### ✅ Correct Way

```php
$vote = Vote::find($voteId);  // Real vote with organisation_id

$result = Result::create([
    'vote_id' => $vote->id,
    'organisation_id' => $vote->organisation_id,  // ← EXPLICIT, matches vote
    'post_id' => $postId,
    'candidacy_id' => $candidacyId,
]);

// ✅ Result created with matching organisation_id
// ✓ Passes all 4 layers of validation
```

#### ❌ Incorrect Ways

```python
// WRONG: Mismatched organisation
$result = Result::create([
    'vote_id' => $vote->id,  // vote in org 1
    'organisation_id' => 2,  // result in org 2
    'post_id' => $postId,
    'candidacy_id' => $candidacyId,
]);
// ❌ Fails Layer 2 validation (mismatch)
// ❌ Fails Layer 1 composite FK

// WRONG: NULL organisation
$result = Result::create([
    'vote_id' => $vote->id,
    'organisation_id' => null,  // NULL not allowed
    'post_id' => $postId,
    'candidacy_id' => $candidacyId,
]);
// ❌ Fails Layer 2 validation (NULL)
// ❌ Fails Layer 1 constraint (NOT NULL)
```

### Querying Results

```php
// As regular user (scoped)
$results = Result::all();
// Only sees results from user's organisation

// Get results for specific vote
$vote = Vote::find($voteId);
$results = $vote->results()->get();  // Automatically org-scoped

// Aggregate queries (also scoped)
$voteCount = Result::where('candidacy_id', $candidacyId)->count();
// Only counts results in user's organisation

// As admin (all organisations)
$allResults = Result::withoutGlobalScopes()->get();
```

---

## Code Examples

### Example 1: Complete Real Voting Flow

```php
// 1. Get election
$election = Election::find($electionId);  // type = 'real', organisation_id = 1

// 2. Verify user's organisation matches
$user = auth()->user();  // organisation_id = 1
assert($user->organisation_id === $election->organisation_id);

// 3. Create vote with explicit organisation_id
$vote = Vote::create([
    'election_id' => $election->id,
    'organisation_id' => $election->organisation_id,  // ← EXPLICIT
    'voting_code' => $votingCode,
    'no_vote_option' => false,
]);

// 4. Create results with matching organisation_id
foreach ($selectedCandidates as $post => $candidacyId) {
    $result = Result::create([
        'vote_id' => $vote->id,
        'organisation_id' => $election->organisation_id,  // ← EXPLICIT
        'post_id' => $post,
        'candidacy_id' => $candidacyId,
    ]);
}

// ✅ Vote and results created successfully
// ✓ Passed all 4 layers of validation
```

### Example 2: Demo Voting (No Security Checks)

```php
// 1. Get demo election
$demoElection = Election::withoutGlobalScopes()
    ->where('type', 'demo')
    ->first();  // organisation_id = null

// 2. No organisation check needed for demo!
// User can vote regardless of their organisation_id

// 3. Create demo vote
$demoVote = DemoVote::create([
    'election_id' => $demoElection->id,
    'organisation_id' => null,  // ← Always NULL
    'voting_code' => $votingCode,
]);

// 4. Create demo results
foreach ($selectedCandidates as $post => $candidacyId) {
    $demoResult = DemoResult::create([
        'vote_id' => $demoVote->id,
        'organisation_id' => null,  // ← Always NULL
        'post_id' => $post,
        'candidacy_id' => $candidacyId,
    ]);
}

// ✅ Demo vote created
// ✓ No security validation applied
// ✓ Separate tables used
```

### Example 3: Admin Queries (Cross-Organisation)

```php
// Admin needs to see all organisations' data
// Must use withoutGlobalScopes()

// Get all elections
$allElections = Election::withoutGlobalScopes()->get();

// Get statistics across organisations
$stats = [
    'total_elections' => Election::withoutGlobalScopes()->count(),
    'total_votes' => Vote::withoutGlobalScopes()->count(),
    'total_results' => Result::withoutGlobalScopes()->count(),
];

// Get specific organisation's data
$org1Elections = Election::withoutGlobalScopes()
    ->where('organisation_id', 1)
    ->get();

$org1Votes = Vote::withoutGlobalScopes()
    ->whereIn('election_id', $org1Elections->pluck('id'))
    ->get();

// ✅ All data accessible
// ✓ withoutGlobalScopes() required for admin access
```

### Example 4: Error Handling

```php
use App\Exceptions\InvalidRealVoteException;
use App\Exceptions\OrganisationMismatchException;

try {
    // Attempt to create vote
    $vote = Vote::create([
        'election_id' => $electionId,
        'organisation_id' => 2,
        'voting_code' => $code,
    ]);
} catch (InvalidRealVoteException $e) {
    // Caught at Layer 2 (Model)
    \Log::error('Vote validation failed', [
        'reason' => $e->getContext()['reason'],  // e.g., 'election_not_found'
        'message' => $e->getMessage(),
    ]);

    return redirect()->back()->withErrors([
        'vote' => $e->getMessage(),
    ]);
} catch (OrganisationMismatchException $e) {
    // Caught at Layer 2 (Model)
    $context = $e->getContext();

    \Log::error('Organisation mismatch', [
        'user_org' => $context['vote_organisation_id'],
        'election_org' => $context['election_organisation_id'],
    ]);

    return redirect()->back()->withErrors([
        'vote' => 'Your organisation does not match this election.',
    ]);
} catch (\Exception $e) {
    // Other errors
    return redirect()->back()->withErrors([
        'error' => 'An error occurred. Please try again.',
    ]);
}
```

---

## Best Practices

### 1. Always Set organisation_id Explicitly

```php
// ✅ CORRECT
$vote = Vote::create([
    'election_id' => $election->id,
    'organisation_id' => $election->organisation_id,  // ← Explicit
    'voting_code' => $code,
]);

// ❌ WRONG - Implicit (relies on trait)
$vote = Vote::create([
    'election_id' => $election->id,
    'voting_code' => $code,
    // organisation_id missing
]);
```

**Why**: Makes code intent clear and passes controller validation.

### 2. Use Election's organisation_id as Source of Truth

```php
// ✅ CORRECT - Election is source of truth
$result = Result::create([
    'vote_id' => $vote->id,
    'organisation_id' => $vote->election->organisation_id,  // ← From election
    'post_id' => $post,
    'candidacy_id' => $candidacy,
]);

// ❌ WRONG - User's organisation (may mismatch)
$result = Result::create([
    'vote_id' => $vote->id,
    'organisation_id' => auth()->user()->organisation_id,  // ← Could mismatch
    'post_id' => $post,
    'candidacy_id' => $candidacy,
]);
```

### 3. Distinguish Real from Demo Elections

```php
// ✅ CORRECT - Check type first
if ($election->type === 'demo') {
    // Use DemoVote, skip validation
    return $this->saveDemoVote(...);
} else {
    // Use Vote, enforce validation
    return $this->saveRealVote(...);
}

// ❌ WRONG - No distinction
$vote = Vote::create([...]);  // Fails for demo
```

### 4. Use withoutGlobalScopes() Only When Needed

```php
// ✅ CORRECT - Regular queries (user context)
$elections = Election::all();

// ✅ CORRECT - Admin queries (with explicit reason)
// Admin needs cross-organisation access
$allElections = Election::withoutGlobalScopes()->get();

// ❌ WRONG - Unnecessary scope bypass
Election::withoutGlobalScopes()->where('name', 'AGM')->first();
// Just use: Election::where('name', 'AGM')->first();
```

### 5. Log organisation_id in Security Events

```php
// ✅ CORRECT - Include org context
\Log::channel('voting_security')->error('Vote rejected', [
    'reason' => 'organisation_mismatch',
    'user_organisation_id' => $user->organisation_id,
    'election_organisation_id' => $election->organisation_id,
    'user_id' => $user->id,
    'election_id' => $election->id,
]);

// ❌ WRONG - Missing organisation context
\Log::error('Vote rejected');
```

### 6. Test with Multiple Organisations

```php
// ✅ CORRECT - Test isolation
public function test_votes_isolated_by_organisation()
{
    $org1Election = Election::factory()->create(['organisation_id' => 1]);
    $org2Election = Election::factory()->create(['organisation_id' => 2]);

    $org1Vote = Vote::factory()->create([
        'election_id' => $org1Election->id,
        'organisation_id' => 1,
    ]);

    $org2Vote = Vote::factory()->create([
        'election_id' => $org2Election->id,
        'organisation_id' => 2,
    ]);

    // Login as org1 user
    $this->actingAs(User::factory()->create(['organisation_id' => 1]));

    // Should only see org1 votes
    $this->assertCount(1, Vote::all());
}
```

---

## Common Pitfalls

### ❌ Pitfall 1: Forgetting organisation_id

```php
// WRONG
$vote = Vote::create([
    'election_id' => $electionId,
    'voting_code' => $code,
    // Missing organisation_id
]);
// ERROR at Layer 2: InvalidRealVoteException

// CORRECT
$vote = Vote::create([
    'election_id' => $electionId,
    'organisation_id' => $election->organisation_id,
    'voting_code' => $code,
]);
```

### ❌ Pitfall 2: Using User's organisation_id Instead of Election's

```php
// WRONG
$vote = Vote::create([
    'election_id' => $election->id,
    'organisation_id' => auth()->user()->organisation_id,  // WRONG!
    'voting_code' => $code,
]);
// If user's org ≠ election's org, fails validation

// CORRECT
$vote = Vote::create([
    'election_id' => $election->id,
    'organisation_id' => $election->organisation_id,  // Correct source
    'voting_code' => $code,
]);
```

### ❌ Pitfall 3: Not Checking Election Type

```php
// WRONG
$vote = Vote::create([  // Works for real, fails for demo!
    'election_id' => $electionId,
    'organisation_id' => $election->organisation_id,
    'voting_code' => $code,
]);

// CORRECT
if ($election->type === 'demo') {
    $vote = DemoVote::create([...]);  // Use different model
} else {
    $vote = Vote::create([...]);
}
```

### ❌ Pitfall 4: Querying Across Organisations Without withoutGlobalScopes

```php
// WRONG - Assumes you're seeing all data (but you're not!)
$allVotes = Vote::all();
// Only returns votes from YOUR organisation!

// CORRECT (for admin)
$allVotes = Vote::withoutGlobalScopes()->get();
// Now returns votes from ALL organisations
```

### ❌ Pitfall 5: Creating Mismatched Results

```php
// WRONG
$result = Result::create([
    'vote_id' => $vote->id,  // vote in org 1
    'organisation_id' => 2,   // result in org 2
    'post_id' => $post,
    'candidacy_id' => $candidacy,
]);
// ERROR: Composite FK prevents this!

// CORRECT
$result = Result::create([
    'vote_id' => $vote->id,
    'organisation_id' => $vote->organisation_id,  // Match vote's org
    'post_id' => $post,
    'candidacy_id' => $candidacy,
]);
```

### ❌ Pitfall 6: Missing Null Checks

```php
// WRONG
$election = Election::find($id);
$election->organisation_id;  // Could be null!

// CORRECT
$election = Election::find($id);
if (!$election) {
    throw new Exception('Election not found');
}

if ($election->type === 'demo') {
    // organisation_id is null - that's OK for demo
} else {
    // organisation_id must not be null for real
    assert($election->organisation_id !== null);
}
```

---

## Debugging & Logging

### Understanding Logs

#### voting_security Channel (Errors & Warnings)

```log
[2026-02-20 12:34:56] voting_security.WARNING: Real vote rejected: NULL organisation_id {
    "reason": "Organisation context is required for real votes",
    "timestamp": "2026-02-20T12:34:56Z",
    "ip": "192.168.1.1"
}

[2026-02-20 12:35:10] voting_security.ERROR: Organisation mismatch blocked at middleware {
    "user_id": 5,
    "user_organisation_id": 2,
    "election_id": 1,
    "election_organisation_id": 1,
    "blocked_at": "middleware_layer"
}
```

**What it means**:
- Check reason field to understand validation failure
- user_id + organisation_id help identify the problem user
- blocked_at tells you which layer caught the error

#### voting_audit Channel (Successes)

```log
[2026-02-20 12:36:00] voting_audit.INFO: Real vote passed model validation {
    "vote_id": 42,
    "election_id": 1,
    "organisation_id": 1,
    "timestamp": "2026-02-20T12:36:00Z"
}

[2026-02-20 12:36:05] voting_audit.INFO: Vote and results saved successfully {
    "vote_id": 42,
    "election_id": 1,
    "organisation_id": 1,
    "results_count": 3,
    "timestamp": "2026-02-20T12:36:05Z"
}
```

**What it means**:
- Track successful votes with audit trail
- vote_id + organisation_id confirm data integrity
- results_count shows how many positions voted

### Debugging Tips

#### 1. Check organisation_id in Database

```sql
-- Is organisation_id set correctly?
SELECT id, election_id, organisation_id FROM votes WHERE id = 42;

-- Do vote and election match?
SELECT v.id, v.organisation_id, e.id, e.organisation_id
FROM votes v
JOIN elections e ON v.election_id = e.id
WHERE v.id = 42;
```

#### 2. Check User Context

```php
$user = User::find($userId);
echo $user->organisation_id;  // What is user's org?
echo session('current_organisation_id');  // What's in session?

// Are they different?
if ($user->organisation_id !== session('current_organisation_id')) {
    echo "MISMATCH - User org and session org don't match!";
}
```

#### 3. Check Election Type

```php
$election = Election::find($electionId);
echo $election->type;  // 'real' or 'demo'?
echo $election->organisation_id;  // What's the org?

// For demo elections, org should be null
if ($election->type === 'demo' && $election->organisation_id !== null) {
    echo "ERROR - Demo election has organisation_id!";
}

// For real elections, org should not be null
if ($election->type === 'real' && is_null($election->organisation_id)) {
    echo "ERROR - Real election has no organisation_id!";
}
```

#### 4. Check Global Scope

```php
// Is global scope active?
$scoped = Vote::all();
echo count($scoped);

// Without scope
$unscoped = Vote::withoutGlobalScopes()->get();
echo count($unscoped);

// If unscoped count > scoped count, scope is working
if (count($unscoped) > count($scoped)) {
    echo "✓ Global scope is active and filtering data";
} else {
    echo "✗ Global scope may not be working";
}
```

---

## Testing

### Unit Test Template: Real Voting

```php
use Tests\TestCase;
use App\Models\Vote;
use App\Models\Election;

class RealVotingTest extends TestCase
{
    public function test_real_vote_with_matching_organisation()
    {
        // Setup
        $election = Election::factory()->create([
            'organisation_id' => 1,
            'type' => 'real',
        ]);

        $user = User::factory()->create(['organisation_id' => 1]);
        $this->actingAs($user);

        // Action
        $vote = Vote::create([
            'election_id' => $election->id,
            'organisation_id' => $election->organisation_id,
            'voting_code' => 'TEST001',
        ]);

        // Assert
        $this->assertNotNull($vote->id);
        $this->assertEquals($election->organisation_id, $vote->organisation_id);
        $this->assertDatabaseHas('votes', [
            'id' => $vote->id,
            'organisation_id' => 1,
        ]);
    }

    public function test_real_vote_with_mismatched_organisation_fails()
    {
        // Setup
        $election = Election::factory()->create([
            'organisation_id' => 1,
            'type' => 'real',
        ]);

        // Action & Assert
        $this->expectException(OrganisationMismatchException::class);

        Vote::create([
            'election_id' => $election->id,
            'organisation_id' => 2,  // Mismatch
            'voting_code' => 'TEST001',
        ]);
    }
}
```

### Feature Test Template: Demo Voting

```php
class DemoVotingTest extends TestCase
{
    public function test_demo_voting_no_organisation_required()
    {
        // Setup
        $demoElection = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => null,
        ]);

        $user = User::factory()->create(['organisation_id' => 99]);  // Any org
        $this->actingAs($user);

        // Action - should work despite organisation mismatch!
        $demoVote = DemoVote::create([
            'election_id' => $demoElection->id,
            'organisation_id' => null,
            'voting_code' => 'DEMO001',
        ]);

        // Assert
        $this->assertNotNull($demoVote->id);
        $this->assertNull($demoVote->organisation_id);

        // Demo voting should not be filtered by scope
        $this->assertCount(1, DemoVote::all());
    }
}
```

### Integration Test Template: 4-Layer Protection

```php
class FourLayerProtectionTest extends TestCase
{
    public function test_all_four_layers_protect_against_invalid_votes()
    {
        // Setup: Election in org 1, user in org 2
        $election = Election::factory()->create(['organisation_id' => 1, 'type' => 'real']);
        $user = User::factory()->create(['organisation_id' => 2]);
        $this->actingAs($user);

        // Layer 4 (Middleware) - Would block request
        $this->assertNotEquals($user->organisation_id, $election->organisation_id);

        // Layer 3 (Controller) - Would reject with error
        $this->assertNotEquals($user->organisation_id, $election->organisation_id);

        // Layer 2 (Model) - Would throw exception
        $this->expectException(OrganisationMismatchException::class);

        Vote::create([
            'election_id' => $election->id,
            'organisation_id' => $user->organisation_id,
            'voting_code' => 'TEST001',
        ]);

        // Layer 1 (Database) - Would fail FK constraint
        // (if we got here, which we shouldn't)
    }
}
```

---

## Quick Reference

### Key Commands

```bash
# Test voting system
php artisan test --filter=Vote

# Test real voting
php artisan test --filter=RealVoteEnforcement

# Test demo voting
php artisan test --filter=Demo

# Test middleware
php artisan test --filter=EnsureRealVoteOrganisation

# View voting_security logs
tail -f storage/logs/voting-security.log

# View voting_audit logs
tail -f storage/logs/voting-audit.log
```

### Key Files

```
app/
├── Http/
│   ├── Controllers/VoteController.php      (Layer 3: Controller)
│   └── Middleware/
│       └── EnsureRealVoteOrganisation.php (Layer 4: Middleware)
├── Models/
│   ├── BaseVote.php                        (Layer 2: Model)
│   ├── BaseResult.php                      (Layer 2: Model)
│   ├── Vote.php
│   ├── Result.php
│   ├── DemoVote.php
│   └── DemoResult.php
└── Exceptions/
    ├── InvalidRealVoteException.php
    └── OrganisationMismatchException.php

database/
└── migrations/
    ├── *_add_composite_fk.php              (Layer 1: Database)
    └── *_ensure_organisation_not_null.php  (Layer 1: Database)

tests/
├── Unit/
│   ├── Controllers/VoteControllerValidationTest.php
│   ├── Models/VoteValidationTest.php
│   └── Middleware/EnsureRealVoteOrganisationTest.php
└── Feature/
    └── RealVoteEnforcementTest.php
```

---

## Support & Resources

### Getting Help

1. **Check logs first**: voting_security and voting_audit channels
2. **Review error message**: Custom exceptions have context
3. **Test in isolation**: Write unit test to reproduce
4. **Consult this guide**: Check Best Practices section
5. **Check Phase Reports**: See tenancy/ folder for architecture docs

### Documentation Files

- `PHASE_1_COMPLETION_REPORT.md` - Database layer details
- `PHASE_2_COMPLETION_REPORT.md` - Model validation details
- `PHASE_3_COMPLETION_REPORT.md` - Controller validation details
- `PHASE_4_COMPLETION_REPORT.md` - Middleware validation details
- `DEVELOPER_GUIDE.md` - This file

---

## Conclusion

The election system with organisation_id implements a **production-grade, four-layer security architecture**:

1. **Database Layer** - Physical constraints prevent impossible states
2. **Model Layer** - Data integrity validation with custom exceptions
3. **Controller Layer** - Business logic validation with user-friendly errors
4. **Middleware Layer** - Pre-request validation at the earliest point

This design ensures **it is impossible to create invalid real votes** while maintaining **100% backward compatibility with demo voting**.

Follow the best practices in this guide, and your voting system will be secure, maintainable, and reliable.

---

**Happy Voting! 🗳️**
