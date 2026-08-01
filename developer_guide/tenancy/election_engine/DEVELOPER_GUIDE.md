# 🗳️ Election Engine Developer Guide

**A comprehensive guide for developers working with the new multi-tenant election architecture.**

---

## Table of Contents

1. [Architecture Overview](#architecture-overview)
2. [Multi-Tenancy System](#multi-tenancy-system)
3. [5-Step Voting Workflow](#5-step-voting-workflow)
4. [Vote Anonymity & Security](#vote-anonymity--security)
5. [Working with Models](#working-with-models)
6. [Working with Controllers](#working-with-controllers)
7. [Database & Queries](#database--queries)
8. [Testing](#testing)
9. [Common Patterns](#common-patterns)
10. [Troubleshooting](#troubleshooting)

---

## Architecture Overview

### Core Design Principles

The election engine is built on **Domain-Driven Design** with **strict multi-tenancy** and **vote anonymity**:

```
┌─────────────────────────────────────────────────────────┐
│           Election Engine Architecture                   │
├─────────────────────────────────────────────────────────┤
│                                                          │
│  Presentation Layer (Controllers, Views)                │
│       ↓                                                  │
│  Middleware Layer (TenantContext, Authenticate)         │
│       ↓                                                  │
│  Application Layer (Services, Business Logic)           │
│       ↓                                                  │
│  Domain Layer (Models, BelongsToTenant Trait)           │
│       ↓                                                  │
│  Infrastructure Layer (Database, Migrations)            │
│       ↓                                                  │
│  Data Layer (Tenant-Scoped, Anonymous Votes)            │
│                                                          │
└─────────────────────────────────────────────────────────┘
```

### Two Operating Modes

The system supports two distinct modes:

| Mode | Description | organisation_id | Use Case |
|------|-------------|-----------------|----------|
| **MODE 1** | Demo (No Org) | `NULL` | Customer testing without organisation |
| **MODE 2** | Live (With Org) | `1, 2, 3...` | Production multi-tenant elections |

**Both modes share the same codebase** - no branching logic needed!

---

## Multi-Tenancy System

### How Tenancy Works

#### 1. **Session Context**
```php
// TenantContext Middleware sets this on every request
session('current_organisation_id')  // NULL for MODE 1, or org_id for MODE 2
```

#### 2. **Global Scoping**
```php
// BelongsToTenant trait adds global scope to ALL queries
class Election extends Model {
    use BelongsToTenant;  // ← Automatically scopes queries
}

// ANY query is automatically filtered:
Election::all();          // Only returns elections for current org/demo
Election::find(5);        // Only if election 5 is in current org
Election::create([...]);  // Auto-fills organisation_id from session
```

#### 3. **Data Isolation**
```php
// MODE 1: Demo mode (organisation_id = NULL)
User A (org = NULL)
  → session = NULL
  → Sees: Elections with org = NULL
  → Cannot see: Elections with org = 1, 2, 3...

// MODE 2: Tenant 1
User B (org = 1)
  → session = 1
  → Sees: Elections with org = 1
  → Cannot see: Elections with org = NULL, 2, 3...

// MODE 2: Tenant 2
User C (org = 2)
  → session = 2
  → Sees: Elections with org = 2
  → Cannot see: Elections with org = NULL, 1, 3...
```

### Helper Functions

```php
// Check current mode
if (is_demo_mode()) {
    // MODE 1: No organisation
} else if (is_tenant_mode()) {
    // MODE 2: With organisation
}

// Get current mode label
$mode = current_mode();  // 'MODE_1_DEMO' or 'MODE_2_TENANT_5'

// Get tenant ID
$orgId = get_tenant_id();  // NULL or 1, 2, 3...
```

---

## 5-Step Voting Workflow

The voting system follows a **strict 5-step process** to ensure security and prevent double voting:

### **STEP 1: Code Verification**
```
User submits verification code (received via email)
  ↓
CodeController::store()
  ├─ Validate code matches Code record
  ├─ Check code hasn't expired (30 minutes)
  ├─ Verify not already voted (for real elections)
  ├─ Mark code as can_vote_now = 1
  └─ Record step in voter_slug_steps table

Route: POST /v/{slug}/code
Result: Code verified, user can proceed to agreement
```

### **STEP 2: Agreement Acceptance**
```
User reads and accepts voting agreement
  ↓
CodeController::submitAgreement()
  ├─ Validate agreement checkbox accepted
  ├─ Mark code as has_agreed_to_vote = 1
  ├─ Set voting_started_at timestamp
  └─ Record step in voter_slug_steps table

Route: POST /v/{slug}/code/agreement
Result: Agreement accepted, user can proceed to voting
```

### **STEP 3: Candidate Selection**
```
User selects candidates for each position
  ↓
VoteController::create() [GET - shows form]
  ├─ Load election with positions
  ├─ Load available candidates
  ├─ Store selections in session
  └─ Record preliminary step

Route: GET /v/{slug}/vote
Result: Voting page displayed with candidates
```

### **STEP 4: Vote Preview & Confirmation**
```
User reviews selected candidates
  ↓
VoteController::preview() [GET - shows preview]
  ├─ Display selected candidates
  ├─ Show vote summary
  └─ Await final confirmation

Route: GET /v/{slug}/vote/confirm
Result: Vote preview shown, awaiting submission
```

### **STEP 5: Final Vote Submission**
```
User submits second verification code and votes are saved
  ↓
VoteController::store()
  ├─ Validate second code (code2)
  ├─ Verify pre-conditions:
  │   ├─ User not already voted
  │   ├─ Code still valid
  │   ├─ Election still active
  │   └─ No tampering detected
  ├─ Generate vote hashes (ANONYMOUSLY)
  ├─ Save vote to votes/demo_votes table (NO user_id)
  ├─ Save results to results/demo_results table (NO user_id)
  ├─ Mark code as has_voted = 1
  ├─ Record step in voter_slug_steps table
  └─ Send verification code to email

Route: POST /v/{slug}/vote
Result: Vote permanently saved, election complete
```

### **Voter Slug Steps Tracking**

The system tracks progress through `voter_slug_steps` table:

```php
// Each step is recorded with metadata
VoterSlugStep::create([
    'voter_slug_id' => $slug->id,
    'election_id' => $election->id,
    'step' => 1,  // Step number (1-5)
    'data' => [   // Step-specific data
        'code_verified' => true,
        'verified_at' => now()->toIso8601String(),
    ],
]);
```

**Steps:**
- Step 1: Code verification complete
- Step 2: Agreement acceptance complete
- Step 3: Candidate selection complete
- Step 4: Vote preview confirmed
- Step 5: Vote final submission complete

---

## Vote Anonymity & Security

### **Vote Anonymity Guaranteed**

The system ensures votes are **completely anonymous**:

#### ✅ **What's Stored (Safe)**
```sql
-- votes table (real elections)
votes:
  id:              BIGINT          -- Vote ID
  election_id:     BIGINT          -- Which election
  organisation_id: BIGINT (NULL)   -- Which org (isolation only, NOT user tracking)
  voting_code:     VARCHAR (HASH)  -- Hashed verification code for audit trail
  ip_address:      VARCHAR         -- For security audit
  user_agent:      VARCHAR         -- For security audit
  created_at:      TIMESTAMP       -- Audit timestamp
  updated_at:      TIMESTAMP       -- Audit timestamp

-- NO user_id column - ANONYMOUS!
-- NO member_id column - ANONYMOUS!
-- NO voter_slug_id column - ANONYMOUS!
-- organisation_id is for DATA ISOLATION ONLY, not user identification
```

#### ❌ **What's NOT Stored (Anonymity Preserved)**
```
❌ user_id          -- No way to link vote to user
❌ member_id        -- No member identification
❌ voter_slug_id    -- No slug identification
❌ personal_data    -- No PII stored with votes
```

#### 🔐 **Audit Trail (Separate)**
```php
// Voting code flow:
1. User receives code1 via email
2. User submits code1
3. System marks code.can_vote_now = 1
4. User selects candidates
5. User receives code2 via email
6. User submits code2
7. System verifies code2
8. Vote saved with HASHED code combination
9. voting_code_hash = password_hash(code2 . '_' . vote_id, PASSWORD_BCRYPT)

Result:
  ✓ Vote is completely anonymous
  ✓ Voting codes tied to user (for fraud detection)
  ✓ Hashing prevents reverse-engineering
  ✓ codes table has user_id, votes table does NOT
```

### **Security Features**

#### 1. **One Vote Per Voter Per Election**
```php
// Code model tracks voting status
$code = Code::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->first();

// Real elections: One vote per lifetime
if ($election->type === 'real' && $code->has_voted) {
    // BLOCK: Already voted, cannot vote again
    throw new Exception('Already voted in this election');
}

// Demo elections: Allow revoting (for testing)
if ($election->type === 'demo' && $code->has_voted) {
    // Allow reset for testing/demo purposes
    $code->update(['has_voted' => false, 'can_vote_now' => 0]);
}
```

#### 2. **Code Expiration**
```php
// Codes expire after 30 minutes
$minutesSinceSent = now()->diffInMinutes($code->code1_sent_at);
if ($minutesSinceSent >= 30) {
    // Generate new code and resend
}
```

#### 3. **Rate Limiting**
```php
// Prevent abuse from single IP address
$votesFromIP = Code::where('client_ip', $this->clientIP)
                   ->where('has_voted', 1)
                   ->count();

if ($votesFromIP >= 7) {  // Max 7 votes from one IP
    throw new Exception('Too many votes from this IP');
}
```

#### 4. **Dual Code System**
```php
// Two separate codes for 2FA protection:

code1: Sent for code verification (Step 1)
  - Verified immediately
  - User proceeds to agreement

code2: Sent for final submission (Step 5)
  - Verified at submission
  - Vote only saved when code2 verified
  - Prevents tampering between selection and submission
```

---

## Working with Models

### Model Hierarchy

```
BaseModel
├── BaseVote
│   ├── Vote         (for real elections)
│   └── DemoVote     (for demo elections)
├── BaseResult
│   ├── Result       (for real elections)
│   └── DemoResult   (for demo elections)
└── [Other Models]
    ├── Code
    ├── Election
    ├── VoterSlug
    ├── VoterSlugStep
    ├── Post
    ├── Candidacy
    └── ...
```

### Critical Models

#### **Code Model**
```php
// Tracks verification codes and voting status for each user per election
Code::create([
    'user_id' => $user->id,
    'election_id' => $election->id,
    'code1' => 'ABC123',                    // First verification code
    'code2' => 'XYZ789',                    // Second verification code
    'code1_sent_at' => now(),
    'code2_sent_at' => now(),
    'has_code1_sent' => true,
    'is_code1_usable' => true,
    'can_vote_now' => 0,                    // Step 1 completion flag
    'has_agreed_to_vote' => 0,              // Step 2 completion flag
    'has_voted' => 0,                       // Step 5 completion flag
    'vote_submitted' => 0,
    'client_ip' => '192.168.1.1',
    'voting_time_in_minutes' => 30,
    'is_codemodel_valid' => true,
]);
```

**Usage in Controllers:**
```php
// CodeController.php
public function store(Request $request)
{
    $code = Code::where('user_id', $user->id)
                ->where('election_id', $election->id)
                ->first();

    // Automatically scoped by BelongsToTenant trait!
    // Only returns codes in current organisation/demo
}
```

#### **Election Model**
```php
// Represents an election (demo or real)
Election::create([
    'organisation_id' => null,          // AUTO-FILLED by trait
    'name' => 'Presidential Election',
    'slug' => 'presidential-2026',
    'description' => 'Vote for president',
    'type' => 'real',                   // 'real' or 'demo'
    'is_active' => true,
    'start_date' => now(),
    'end_date' => now()->addDays(30),
    'settings' => [                     // JSON configuration
        'allow_revoting' => false,
        'max_candidates_per_position' => 3,
    ],
]);
```

**Useful Methods:**
```php
$election->isDemo();              // Check if demo election
$election->isReal();              // Check if real election
$election->isCurrentlyActive();   // Check if active and within date range

$election->posts();               // Get positions (president, vice-pres, etc)
$election->votes();               // Get votes (real or demo based on type)
$election->results();             // Get results
$election->codes();               // Get all codes for this election

$election->votedCount();          // How many people voted
$election->voterTurnout();        // Turnout percentage (approved voters)
$election->getStatistics();       // Full election statistics
```

#### **Vote Models (Vote & DemoVote)**
```php
// Real Election Votes
Vote::create([
    'organisation_id' => 1,         // AUTO-FILLED by trait (MODE 2)
    'election_id' => $election->id,
    'voting_code' => 'hashed_code_combination',
    'ip_address' => '192.168.1.1',
    'user_agent' => 'Mozilla/5.0...',
    // NO user_id - ANONYMOUS!
]);

// Demo Election Votes
DemoVote::create([
    'organisation_id' => null,      // AUTO-FILLED by trait (MODE 1)
    'election_id' => $election->id,
    'voting_code' => 'hashed_code_combination',
    'ip_address' => '127.0.0.1',
    'user_agent' => 'Mozilla/5.0...',
    // NO user_id - ANONYMOUS!
]);
```

#### **Result Models (Result & DemoResult)**
```php
// Real Election Results
Result::create([
    'organisation_id' => 1,         // AUTO-FILLED by trait (MODE 2)
    'vote_id' => $vote->id,
    'candidate_id' => $candidate->id,
    'ip_address' => '192.168.1.1',
    // NO user_id - ANONYMOUS!
]);

// Demo Election Results
DemoResult::create([
    'organisation_id' => null,      // AUTO-FILLED by trait (MODE 1)
    'vote_id' => $demoVote->id,
    'candidate_id' => $candidate->id,
    'ip_address' => '127.0.0.1',
    // NO user_id - ANONYMOUS!
]);
```

### BelongsToTenant Trait

Every model with the `BelongsToTenant` trait automatically:

#### **1. Scopes All Queries**
```php
// Automatically applies WHERE clause:
Election::all();
// ↓ Becomes:
// SELECT * FROM elections WHERE organisation_id IS NULL  [if session = NULL]
// SELECT * FROM elections WHERE organisation_id = 1      [if session = 1]
```

#### **2. Auto-Fills organisation_id**
```php
// Automatically sets organisation_id from session:
$election = Election::create(['name' => 'Test']);
// ↓ Actually saved as:
// INSERT INTO elections (name, organisation_id) VALUES ('Test', NULL)  [if MODE 1]
// INSERT INTO elections (name, organisation_id) VALUES ('Test', 1)     [if MODE 2]
```

#### **3. Provides Scoping Methods**
```php
// Bypass global scope if needed (admin only!)
Election::withoutGlobalScopes()->get();

// Get records from specific organisation
Election::forOrganisation(1)->get();

// Get demo records only
Election::forDefaultPlatform()->get();  // organisation_id IS NULL
```

---

## Working with Controllers

### Pattern: Request Flow

All controllers follow this pattern:

```php
class ElectionController extends Controller
{
    public function show(Request $request, $id)
    {
        // 1. Get authenticated user (TenantContext already set session)
        $user = auth()->user();

        // 2. Query model (automatically scoped by trait)
        $election = Election::findOrFail($id);
        // ↓ Only returns election if:
        //   - election.id = $id AND
        //   - election.organisation_id matches session('current_organisation_id')
        // ↓ Returns 404 if not found OR not in current org (secure!)

        // 3. Perform business logic
        $turnout = $election->voterTurnout();
        $stats = $election->getStatistics();

        // 4. Return response
        return view('election.show', [
            'election' => $election,
            'turnout' => $turnout,
            'stats' => $stats,
        ]);
    }
}
```

### Pattern: Creating Records

```php
public function store(Request $request)
{
    // 1. Validate input
    $validated = $request->validate([
        'name' => 'required|string',
        'type' => 'required|in:demo,real',
    ]);

    // 2. Create record
    $election = Election::create($validated);
    // ↓ organisation_id is automatically filled by trait
    // ↓ from session('current_organisation_id')

    // 3. Verify it was created in current org
    $this->assertTrue(
        $election->organisation_id === session('current_organisation_id')
    );

    // 4. Return response
    return redirect()->route('election.show', $election);
}
```

### Pattern: Querying Related Data

```php
public function index(Request $request)
{
    // Get all elections for current org/demo
    $elections = Election::with([
        'posts',      // Automatically scoped
        'votes',      // Automatically scoped
        'codes',      // Automatically scoped
    ])
    ->where('type', 'real')  // Additional filter
    ->get();
    // ↓ Only returns elections with:
    //   - organisation_id matching session
    //   - type = 'real'

    return view('election.index', ['elections' => $elections]);
}
```

### Common Controller Methods

#### **Get Election with Stats**
```php
public function showStats(Request $request, Election $election)
{
    // $election is auto-scoped (404 if wrong org)

    return response()->json([
        'election' => $election,
        'stats' => $election->getStatistics(),
        'turnout' => $election->voterTurnout(),
        'voted_count' => $election->votedCount(),
        'approved_voters' => $election->approvedVoterCount(),
    ]);
}
```

#### **Get Election Votes**
```php
public function getVotes(Request $request, Election $election)
{
    // Get votes (real or demo based on election type)
    $votes = $election->votes()
                      ->with('results')
                      ->paginate();

    return response()->json($votes);
}
```

#### **Get Election Results**
```php
public function getResults(Request $request, Election $election)
{
    // Get results (real or demo based on election type)
    $results = $election->results()
                        ->with('candidate')
                        ->groupBy('candidate_id')
                        ->selectRaw('candidate_id, COUNT(*) as vote_count')
                        ->get();

    return response()->json($results);
}
```

---

## Database & Queries

### All 8 Election Tables

All tables have `organisation_id` column (NULLABLE) and proper indexes:

```sql
-- Core Tables
elections           -- organisation_id NULLABLE, INDEX
codes               -- organisation_id NULLABLE, INDEX
votes               -- organisation_id NULLABLE, INDEX (real elections)
demo_votes          -- organisation_id NULLABLE, INDEX (demo elections)
results             -- organisation_id NULLABLE, INDEX (real elections)
demo_results        -- organisation_id NULLABLE, INDEX (demo elections)

-- Voter Management
voter_slugs         -- organisation_id NULLABLE, INDEX
voter_slug_steps    -- organisation_id NULLABLE, INDEX
```

### Query Examples

#### **All Elections in Current Org**
```php
$elections = Election::all();
// Automatically becomes:
// MODE 1: SELECT * FROM elections WHERE organisation_id IS NULL
// MODE 2: SELECT * FROM elections WHERE organisation_id = 1
```

#### **Count Votes for Election**
```php
$voteCount = $election->votes()->count();
// Automatically scoped to current org
```

#### **Find Specific Vote**
```php
$vote = Vote::find($voteId);
// Returns NULL if not in current org (secure!)
```

#### **Get Voter Turnout**
```php
$turnout = $election->voterTurnout();
// Calculates: (voted / approved) * 100
// Automatically uses org-scoped data
```

#### **Search Across Elections**
```php
$elections = Election::where('type', 'real')
                     ->where('is_active', true)
                     ->get();
// Global scope automatically filters by organisation_id
// WHERE organisation_id = ? is added automatically
```

### Complex Queries

#### **Elections with Vote Counts**
```php
$elections = Election::withCount([
    'votes',       // Count scoped by org
    'codes',       // Count scoped by org
])
->get()
->map(fn($e) => [
    'id' => $e->id,
    'name' => $e->name,
    'votes_count' => $e->votes_count,
    'eligible_voters' => $e->codes_count,
]);
```

#### **Results by Candidate**
```php
$results = $election->results()
                    ->groupBy('candidate_id')
                    ->selectRaw('candidate_id, COUNT(*) as votes')
                    ->with('candidate')
                    ->get();
// Automatically scoped to current org
```

---

## Testing

### Testing Pattern

All tests inherit from `TestCase` with `RefreshDatabase`:

```php
class ElectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_election_is_scoped_to_current_org()
    {
        // Create MODE 2 user with org 1
        $user1 = User::factory()->create(['organisation_id' => 1]);
        $this->actingAs($user1);

        // Create election (auto-scoped to org 1)
        $election1 = Election::create([
            'name' => 'Org 1 Election',
            'slug' => 'org1-election',
            'type' => 'real',
        ]);

        // Verify scoped correctly
        $this->assertEquals(1, $election1->organisation_id);

        // Create MODE 2 user with org 2
        $user2 = User::factory()->create(['organisation_id' => 2]);
        $this->actingAs($user2);

        // Create election (auto-scoped to org 2)
        $election2 = Election::create([
            'name' => 'Org 2 Election',
            'slug' => 'org2-election',
            'type' => 'real',
        ]);

        // Verify scoped correctly
        $this->assertEquals(2, $election2->organisation_id);

        // User 1 cannot see User 2's election
        $this->actingAs($user1);
        $this->assertNull(Election::find($election2->id));

        // User 2 cannot see User 1's election
        $this->actingAs($user2);
        $this->assertNull(Election::find($election1->id));
    }
}
```

### Testing Both Modes

```php
public function test_mode1_and_mode2_isolation()
{
    // MODE 1: Demo user
    $demoUser = User::factory()->create(['organisation_id' => null]);
    $this->actingAs($demoUser);

    $demoElection = Election::create([
        'name' => 'Demo',
        'slug' => 'demo',
        'type' => 'demo',
    ]);
    $this->assertNull($demoElection->organisation_id);

    // MODE 2: Org 1 user
    $orgUser = User::factory()->create(['organisation_id' => 1]);
    $this->actingAs($orgUser);

    $orgElection = Election::create([
        'name' => 'Real',
        'slug' => 'real',
        'type' => 'real',
    ]);
    $this->assertEquals(1, $orgElection->organisation_id);

    // Verify isolation
    $this->actingAs($demoUser);
    $this->assertTrue(Election::all()->contains($demoElection));
    $this->assertFalse(Election::all()->contains($orgElection));

    $this->actingAs($orgUser);
    $this->assertFalse(Election::all()->contains($demoElection));
    $this->assertTrue(Election::all()->contains($orgElection));
}
```

### Testing Vote Anonymity

```php
public function test_votes_are_anonymous()
{
    $user = User::factory()->create(['organisation_id' => 1]);
    $this->actingAs($user);

    $election = Election::create([
        'name' => 'Real Election',
        'type' => 'real',
    ]);

    $vote = Vote::create([
        'election_id' => $election->id,
        'voting_code' => 'hash_code',
        'ip_address' => '192.168.1.1',
    ]);

    // Verify no user_id in votes
    $this->assertFalse(in_array('user_id', array_keys($vote->getAttributes())));

    // Verify organisation_id is for isolation only
    $this->assertEquals(1, $vote->organisation_id);

    // Verify audit trail exists
    $this->assertNotNull($vote->voting_code);
    $this->assertNotNull($vote->ip_address);
}
```

---

## Common Patterns

### Pattern 1: Get Election with All Related Data

```php
$election = Election::with([
    'posts' => fn($q) => $q->with('candidacies'),
    'votes',
    'codes',
    'voterRegistrations' => fn($q) => $q->where('status', 'approved'),
])->findOrFail($id);
```

### Pattern 2: Create Election with Positions

```php
$election = Election::create([
    'name' => 'Presidential Election',
    'type' => 'real',
    'slug' => 'presidential-' . now()->timestamp,
]);

// Create positions
$positions = [
    ['name' => 'President', 'nepali_name' => 'राष्ट्रपति', 'required_number' => 1],
    ['name' => 'Vice President', 'nepali_name' => 'उप-राष्ट्रपति', 'required_number' => 1],
];

foreach ($positions as $pos) {
    Post::create([
        'election_id' => $election->id,
        'name' => $pos['name'],
        'nepali_name' => $pos['nepali_name'],
        'required_number' => $pos['required_number'],
    ]);
}
```

### Pattern 3: Check Voting Eligibility

```php
public function canVote(User $user, Election $election): bool
{
    // Check if user has valid code
    $code = Code::where('user_id', $user->id)
                ->where('election_id', $election->id)
                ->first();

    if (!$code) {
        return false;  // No code generated yet
    }

    // Real elections: Can only vote once
    if ($election->type === 'real' && $code->has_voted) {
        return false;  // Already voted
    }

    // Check code still valid
    if (!$code->can_vote_now) {
        return false;  // Code not verified
    }

    // Check election is active
    if (!$election->isCurrentlyActive()) {
        return false;  // Election closed
    }

    return true;
}
```

### Pattern 4: Get Election Statistics

```php
$stats = [
    'total_eligible' => $election->codes()->count(),
    'approved_voters' => $election->approvedVoterCount(),
    'votes_cast' => $election->votedCount(),
    'turnout_percent' => $election->voterTurnout(),
    'active' => $election->isCurrentlyActive(),
];
```

### Pattern 5: Record Voter Activity

```php
// Create voter slug step record
VoterSlugStep::create([
    'voter_slug_id' => $slug->id,
    'election_id' => $election->id,
    'step' => 1,
    'data' => [
        'action' => 'code_verified',
        'verified_at' => now()->toIso8601String(),
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ],
]);
```

---

## Troubleshooting

### Problem: "Election not found" (404) for valid ID

**Cause:** Election is in a different organisation

**Solution:**
```php
// Debug: Check election's organisation_id
$election = Election::withoutGlobalScopes()->find($id);
dd($election->organisation_id);  // Compare with session('current_organisation_id')

// Fix: Make sure correct user is authenticated
auth()->user()->organisation_id  // Should match election.organisation_id
```

### Problem: Votes not appearing in election results

**Cause:** Votes in wrong organisation scope or election type mismatch

**Solution:**
```php
// Debug: Check vote organisation_id
$vote = Vote::withoutGlobalScopes()->find($voteId);
dd([
    'vote_org_id' => $vote->organisation_id,
    'election_org_id' => $election->organisation_id,
    'session_org_id' => session('current_organisation_id'),
]);

// Check election type matches vote type
// Real elections → votes table
// Demo elections → demo_votes table
```

### Problem: "Too many votes from this IP"

**Cause:** Rate limiting triggered (max 7 votes per IP)

**Solution:**
```php
// Check current votes from IP
$votesFromIP = Code::where('client_ip', $clientIP)
                   ->where('has_voted', 1)
                   ->count();

dd(['votes_from_ip' => $votesFromIP, 'client_ip' => $clientIP]);

// For testing: Use different IPs or clear codes table
Code::where('client_ip', $clientIP)->delete();
```

### Problem: "Code has expired"

**Cause:** 30-minute voting window exceeded

**Solution:**
```php
// Check code age
$minutesSinceSent = now()->diffInMinutes($code->code1_sent_at);
dd(['minutes_since_sent' => $minutesSinceSent, 'max_minutes' => 30]);

// Resend code
$code->update([
    'code1' => Str::random(6),
    'code1_sent_at' => now(),
    'is_code1_usable' => 1,
    'can_vote_now' => 0,
]);
```

### Problem: Session returns NULL when expecting org_id

**Cause:** TenantContext middleware not executing or user has NULL organisation_id

**Solution:**
```php
// Check if middleware is registered in Kernel.php
// App\Http\Kernel::$middlewareGroups['web']
// Should contain: \App\Http\Middleware\TenantContext::class

// Check user's organisation_id
dd(auth()->user()->organisation_id);  // Should be 1, 2, 3... or null

// Check session was set
dd(session('current_organisation_id'));  // Should match user's org_id
```

### Problem: BelongsToTenant trait not scoping queries

**Cause:** Model doesn't have trait or trait not imported

**Solution:**
```php
// Verify model has trait
// class MyModel extends Model {
//     use BelongsToTenant;  // ← Must be here
// }

// Debug: Check global scope is applied
$query = MyModel::query();
dd($query->getBindings());  // Should include organisation_id filter

// Force reset model if needed
MyModel::flushEventListeners();
```

---

## Best Practices

### ✅ DO:

- ✅ Always use Eloquent models (scoping is automatic)
- ✅ Trust the trait to handle organisation filtering
- ✅ Test with both MODE 1 (NULL) and MODE 2 (org_id)
- ✅ Verify data isolation in tests
- ✅ Use `findOrFail()` (returns 404 if wrong org)
- ✅ Log mode changes and tenant context
- ✅ Test vote anonymity separately
- ✅ Use helper functions for mode checking

### ❌ DON'T:

- ❌ Don't manually check organisation_id in controllers
- ❌ Don't use `withoutGlobalScopes()` in production
- ❌ Don't store user_id with votes
- ❌ Don't assume global `session()` is set (use middleware)
- ❌ Don't hardcode organisation checks
- ❌ Don't mix MODE 1 and MODE 2 logic
- ❌ Don't query across organisations in single request
- ❌ Don't expose organisation_id in API responses

---

## Summary

The election engine provides:

✅ **Automatic tenant scoping** via `BelongsToTenant` trait
✅ **Complete vote anonymity** (no user_id stored)
✅ **Secure 5-step voting workflow** with code verification
✅ **Dual-mode operation** (demo + live)
✅ **Clean architecture** (no manual org checks needed)
✅ **Easy testing** (MODE 1 and MODE 2)
✅ **Production-ready security** (rate limiting, code expiration)

**For questions, see:** `tenancy/DEMO_MODE_IMPLEMENTATION.md` and `tenancy/election_engine/VOTING_WORKFLOW.md`
