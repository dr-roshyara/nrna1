# Developer Guide: Public Digit Voting System Architecture

**Last Updated:** 2026-02-03
**Version:** 2.0 (Demo/Real Elections)
**Audience:** Backend & Full-Stack Developers

---

## Table of Contents

1. [Quick Start](#quick-start)
2. [System Overview](#system-overview)
3. [Core Concepts](#core-concepts)
4. [Database Schema](#database-schema)
5. [Models & Relationships](#models--relationships)
6. [Services Architecture](#services-architecture)
7. [Controller Flow](#controller-flow)
8. [Routes & Middleware](#routes--middleware)
9. [Election Context Resolution](#election-context-resolution)
10. [Code Examples](#code-examples)
11. [Testing the Voting System](#testing-the-voting-system)
12. [Common Tasks](#common-tasks)
13. [Debugging](#debugging)
14. [Best Practices](#best-practices)
15. [Troubleshooting](#troubleshooting)

---

## Quick Start

### **Essential Files**

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── VoteController.php          ← Vote creation & submission
│   │   ├── CodeController.php          ← Code verification
│   │   └── ElectionController.php      ← Election selection
│   └── Middleware/
│       └── ElectionMiddleware.php      ← Election context resolver
├── Models/
│   ├── Vote.php                        ← Real election votes
│   ├── DemoVote.php                    ← Demo election votes
│   ├── Result.php                      ← Real election results
│   ├── DemoResult.php                  ← Demo election results
│   ├── Code.php                        ← Vote authorization codes
│   └── Election.php                    ← Election metadata
└── Services/
    ├── VotingServiceFactory.php        ← Service selector
    ├── VotingService.php               ← Abstract base
    ├── RealVotingService.php           ← Real election logic
    └── DemoVotingService.php           ← Demo election logic

routes/
└── election/
    └── electionRoutes.php              ← All voting routes

database/migrations/
├── Tenant/
│   ├── *_create_votes_table.php
│   ├── *_create_demo_votes_table.php
│   ├── *_create_results_table.php
│   └── *_create_demo_results_table.php
└── Landlord/
    ├── *_create_elections_table.php
    └── *_create_codes_table.php
```

### **5-Step Voting Workflow**

```
1. Code Creation     → CodeController::create()      [GET /v/{vslug}/code/create]
2. Agreement         → CodeController::showAgreement [GET /v/{vslug}/vote/agreement]
3. Vote Creation     → VoteController::create()      [GET /v/{vslug}/vote/create]
4. Vote Verification → VoteController::verify()      [GET /v/{vslug}/vote/verify]
5. Final Submission  → VoteController::store()       [POST /v/{vslug}/vote/verify]
```

---

## System Overview

### **What Is This System?**

Public Digit's voting system allows organizations to:
- Run **real elections** with production-grade security
- Test voting flows with **demo elections** (all users eligible)
- Maintain **vote anonymity** by design
- Support **multi-election** scenarios in parallel
- Track election statistics and turnout

### **Key Innovation: Dual Separation**

```
Level 1: Physical Separation
  real elections    → votes table
  demo elections    → demo_votes table

Level 2: Logical Separation
  all tables include election_id column
  enables multi-tenant election support

Level 3: Service Abstraction
  VotingServiceFactory returns correct service
  RealVotingService or DemoVotingService

Level 4: Eligibility Enforcement
  demo: allow all users
  real: only users with can_vote_now == 1
```

### **Target Stakeholders**

| Role | Interaction |
|------|-------------|
| **Election Admin** | Selects real election via `/election/select` |
| **Test User** | Tests voting via `/election/demo/start` |
| **Committee Member** | Monitors voting via admin dashboard |
| **Regular Voter** | Votes in the default real election (auto-selected) |

---

## Core Concepts

### **1. Vote Anonymity**

**CRITICAL PRINCIPLE:** Votes contain NO `user_id`

```
Database structure:
  users table      → has user_id ✓
  codes table      → has user_id ✓ (links users to voting)
  votes table      → NO user_id ✗ (preserves anonymity)
  results table    → NO user_id ✗ (preserves anonymity)

Link chain:
  user_id → codes.code1/code2 → votes.voting_code (hash) → anonymous vote
```

**Why?** Election officials cannot link votes to users, preventing vote coercion.

### **2. Election Types**

#### **Real Elections**
- Used for actual voting
- Users must have `can_vote_now == 1` (timing restrictions apply)
- Data stored in `votes` table
- Permanent records (no cleanup)
- Results used for official aggregation

#### **Demo Elections**
- Used for testing/training
- All users eligible (no timing restrictions)
- Data stored in `demo_votes` table
- Can be reset via `DemoVotingService::reset()`
- Separate from real election results

### **3. Election Context**

Every voting request has an **election context** resolved by `ElectionMiddleware`:

```php
// Resolution order:
1. Check session('selected_election_id')     // User explicitly selected
2. Check route parameter 'election'          // URL-based selection
3. DEFAULT: First REAL active election       // Backward compatible

// Result:
$request->attributes->get('election')  // Available in all controllers
```

### **4. Vote Authorization**

**The voting_code is the authorization link, NOT user_id**

```
Step 1: User proves identity
  → Provide email/user_id
  → Receive code1 via email

Step 2: User verifies code1
  → Submit code1
  → System generates code2

Step 3: User verifies code2
  → Submit code2
  → System generates voting_code hash

Step 4: Vote is saved (ANONYMOUSLY)
  → vote.voting_code = hash(generated)
  → vote.user_id NOT SET
  → User receives voting_code_hash as receipt

This preserves anonymity while proving the user authorized the vote.
```

### **5. Eligibility Rules**

```php
// Demo Elections
if ($election->isDemo()) {
    return true;  // Always allow
}

// Real Elections
if ($user->can_vote_now == 1) {
    return true;  // Allowed
} else {
    return false; // Rejected (not in eligible time window)
}
```

---

## Database Schema

### **Elections Table (Landlord DB)**

```sql
CREATE TABLE elections (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    slug VARCHAR(255) UNIQUE NOT NULL,           -- URL-safe identifier
    name VARCHAR(255) NOT NULL,                  -- e.g., "Annual Elections 2026"
    type ENUM('real', 'demo') NOT NULL,          -- Election type
    description TEXT,
    is_active BOOLEAN DEFAULT 0,                 -- Currently active?
    voting_start_time TIMESTAMP NULL,
    voting_end_time TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    INDEX idx_type (type),
    INDEX idx_is_active (is_active),
    INDEX idx_slug (slug)
);

-- Example rows:
-- (1, 'annual-2026', 'Annual Elections 2026', 'real', '...', 1, ...)
-- (2, 'demo-testing', 'Demo - Testing Only', 'demo', '...', 1, ...)
```

### **Codes Table (Landlord DB)**

```sql
CREATE TABLE codes (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    election_id BIGINT NOT NULL,                 -- Which election
    code1 VARCHAR(255),                          -- First verification code
    code2 VARCHAR(255),                          -- Second verification code
    code1_sent_at TIMESTAMP NULL,
    code2_sent_at TIMESTAMP NULL,
    verified_at TIMESTAMP NULL,
    can_vote_now BOOLEAN DEFAULT 1,
    has_voted BOOLEAN DEFAULT 0,
    has_code1_sent BOOLEAN DEFAULT 0,
    is_code1_usable BOOLEAN DEFAULT 1,
    voting_time_in_minutes INT,
    session_name VARCHAR(255),                   -- Session storage key
    vote_submitted BOOLEAN DEFAULT 0,
    vote_submitted_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (election_id) REFERENCES elections(id),
    UNIQUE KEY unique_user_election (user_id, election_id),
    INDEX idx_election_id (election_id),
    INDEX idx_has_voted (has_voted),
    INDEX idx_verified_at (verified_at)
);
```

### **Votes Table (Tenant DB - Real Elections)**

```sql
CREATE TABLE votes (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    election_id BIGINT NOT NULL,                 -- Which election
    voting_code VARCHAR(255) NOT NULL,           -- Hash link to code (anonymously)
    -- NO user_id column (preserves anonymity)

    no_vote_option BOOLEAN DEFAULT 0,
    candidate_01 JSON,
    candidate_02 JSON,
    -- ... candidate_03 through candidate_60 ...

    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    FOREIGN KEY (election_id) REFERENCES elections(id),
    INDEX idx_voting_code (voting_code),
    INDEX idx_election_id (election_id),
    INDEX idx_created_at (created_at)
);
```

### **Demo_Votes Table (Tenant DB - Demo Elections)**

```sql
CREATE TABLE demo_votes (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    election_id BIGINT NOT NULL,                 -- Which election
    voting_code VARCHAR(255) NOT NULL,           -- Hash link to code (anonymously)
    -- NO user_id column (preserves anonymity)

    no_vote_option BOOLEAN DEFAULT 0,
    candidate_01 JSON,
    candidate_02 JSON,
    -- ... candidate_03 through candidate_60 ...

    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    FOREIGN KEY (election_id) REFERENCES elections(id),
    INDEX idx_voting_code (voting_code),
    INDEX idx_election_id (election_id),
    INDEX idx_created_at (created_at)
);

-- Can be reset via: TRUNCATE TABLE demo_votes;
```

### **Results Tables (Tenant DB)**

```sql
-- RESULTS TABLE (Real Elections)
CREATE TABLE results (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    vote_id BIGINT NOT NULL,                     -- Which vote
    election_id BIGINT NOT NULL,                 -- Which election
    post_id VARCHAR(255) NOT NULL,               -- Position/office
    candidacy_id VARCHAR(255) NOT NULL,          -- Candidate
    -- NO user_id column (preserves anonymity)

    created_at TIMESTAMP,

    FOREIGN KEY (vote_id) REFERENCES votes(id),
    FOREIGN KEY (election_id) REFERENCES elections(id),
    INDEX idx_election_id (election_id),
    INDEX idx_post_id (post_id),
    INDEX idx_candidacy_id (candidacy_id)
);

-- DEMO_RESULTS TABLE (Demo Elections)
CREATE TABLE demo_results (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    vote_id BIGINT NOT NULL,                     -- Which demo vote
    election_id BIGINT NOT NULL,                 -- Which election
    post_id VARCHAR(255) NOT NULL,
    candidacy_id VARCHAR(255) NOT NULL,

    created_at TIMESTAMP,

    FOREIGN KEY (vote_id) REFERENCES demo_votes(id),
    FOREIGN KEY (election_id) REFERENCES elections(id),
    INDEX idx_election_id (election_id),
    INDEX idx_post_id (post_id)
);
```

**Key Security Feature:**
- No `user_id` in votes, demo_votes, results, or demo_results
- Vote authenticity verified via `voting_code` hash, not user_id
- Anonymity preserved by design, not accident

---

## Models & Relationships

### **Election Model**

```php
<?php
namespace App\Models;

class Election extends Model
{
    protected $fillable = ['slug', 'name', 'type', 'description', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    // Relationships
    public function codes()
    {
        return $this->hasMany(Code::class);
    }

    public function votes()
    {
        // Polymorphic-like relationship based on type
        // But we use service factory instead
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDemo($query)
    {
        return $query->where('type', 'demo');
    }

    public function scopeReal($query)
    {
        return $query->where('type', 'real');
    }

    // Methods
    public function isDemo(): bool
    {
        return $this->type === 'demo';
    }

    public function isReal(): bool
    {
        return $this->type === 'real';
    }

    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) return false;

        $now = Carbon::now();

        if ($this->voting_start_time && $now->isBefore($this->voting_start_time)) {
            return false;
        }

        if ($this->voting_end_time && $now->isAfter($this->voting_end_time)) {
            return false;
        }

        return true;
    }

    public function totalVotesCast(): int
    {
        $voteModel = VotingServiceFactory::make($this)->getVoteModel();
        return $voteModel::where('election_id', $this->id)->count();
    }
}
```

### **Code Model**

```php
<?php
namespace App\Models;

class Code extends Model
{
    protected $fillable = [
        'user_id', 'election_id', 'code1', 'code2',
        'can_vote_now', 'has_voted', 'verified_at', 'session_name'
    ];
    protected $casts = ['can_vote_now' => 'boolean', 'has_voted' => 'boolean'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    // Scopes
    public function scopeForElection($query, Election $election)
    {
        return $query->where('election_id', $election->id);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('verified_at');
    }

    public function scopeUnverified($query)
    {
        return $query->whereNull('verified_at');
    }

    public function scopeHasVoted($query)
    {
        return $query->where('has_voted', 1);
    }

    public function scopeHasNotVoted($query)
    {
        return $query->where('has_voted', 0);
    }

    // Methods
    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }

    public function markAsVoted($votingCodeHash): void
    {
        $this->update([
            'has_voted' => 1,
            'voting_code' => $votingCodeHash,
        ]);
    }
}
```

### **Vote & DemoVote Models**

```php
<?php
namespace App\Models;

abstract class BaseVote extends Model
{
    // 60 fillable columns for each candidate
    protected $fillable = [
        'election_id', 'voting_code',
        'candidate_01', 'candidate_02', /* ... */ 'candidate_60',
    ];

    // Methods available in all vote models
    public function getSelectedCandidates(): array
    {
        $selected = [];
        for ($i = 1; $i <= 60; $i++) {
            $colName = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
            if ($this->$colName) {
                $data = json_decode($this->$colName, true);
                if ($data && isset($data['candidates'])) {
                    $selected = array_merge($selected, $data['candidates']);
                }
            }
        }
        return $selected;
    }

    public function countSelectedCandidates(): int
    {
        return count($this->getSelectedCandidates());
    }

    // Scopes
    public function scopeForElection($query, Election $election)
    {
        return $query->where('election_id', $election->id);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', Carbon::now()->subDays($days));
    }
}

// Real Elections
class Vote extends BaseVote
{
    protected $table = 'votes';

    public function isReal(): bool { return true; }
    public function isDemo(): bool { return false; }

    public function results()
    {
        return $this->hasMany(Result::class, 'vote_id');
    }
}

// Demo Elections
class DemoVote extends BaseVote
{
    protected $table = 'demo_votes';

    public function isReal(): bool { return false; }
    public function isDemo(): bool { return true; }

    public function results()
    {
        return $this->hasMany(DemoResult::class, 'vote_id');
    }

    public static function cleanupOlderThan($days = 30)
    {
        return self::where('created_at', '<', Carbon::now()->subDays($days))->delete();
    }

    public function scopeCurrentSession($query)
    {
        return $query->where('created_at', '>=', Carbon::now()->subHours(24));
    }
}
```

### **Result & DemoResult Models**

```php
<?php
namespace App\Models;

abstract class BaseResult extends Model
{
    protected $fillable = ['vote_id', 'election_id', 'post_id', 'candidacy_id'];

    // Methods available in all result models
    public function vote()
    {
        // Dynamic relationship handled by service
        return $this->belongsTo($this->getVoteModel());
    }

    public function scopeForElection($query, Election $election)
    {
        return $query->where('election_id', $election->id);
    }

    public function scopeForPost($query, $postId)
    {
        return $query->where('post_id', $postId);
    }

    public function scopeForCandidacy($query, $candidacyId)
    {
        return $query->where('candidacy_id', $candidacyId);
    }

    // Aggregations
    public static function countForCandidacy($candidacyId, Election $election): int
    {
        return static::where('candidacy_id', $candidacyId)
            ->where('election_id', $election->id)
            ->count();
    }

    public static function topCandidatesForPost($postId, Election $election, $limit = 10)
    {
        return static::where('post_id', $postId)
            ->where('election_id', $election->id)
            ->groupBy('candidacy_id')
            ->selectRaw('candidacy_id, COUNT(*) as vote_count')
            ->orderBy('vote_count', 'DESC')
            ->limit($limit)
            ->get();
    }
}

// Real Elections
class Result extends BaseResult
{
    protected $table = 'results';

    public function isReal(): bool { return true; }
    public function isDemo(): bool { return false; }
}

// Demo Elections
class DemoResult extends BaseResult
{
    protected $table = 'demo_results';

    public function isReal(): bool { return false; }
    public function isDemo(): bool { return true; }

    public static function cleanupOlderThan($days = 30)
    {
        return self::where('created_at', '<', Carbon::now()->subDays($days))->delete();
    }
}
```

---

## Services Architecture

### **VotingServiceFactory**

```php
<?php
namespace App\Services;

class VotingServiceFactory
{
    /**
     * Create appropriate voting service based on election type
     */
    public static function make(Election $election): VotingService
    {
        if ($election->isDemo()) {
            return new DemoVotingService($election);
        }

        return new RealVotingService($election);
    }
}

// Usage in controllers:
$service = VotingServiceFactory::make($election);
$voteModel = $service->getVoteModel();      // Vote or DemoVote
$resultModel = $service->getResultModel();  // Result or DemoResult
```

### **VotingService (Abstract Base)**

```php
<?php
namespace App\Services;

abstract class VotingService
{
    protected Election $election;

    public function __construct(Election $election)
    {
        $this->election = $election;
    }

    abstract public function getVoteModel(): string;
    abstract public function getResultModel(): string;
    abstract public function isDemo(): bool;
    abstract public function isReal(): bool;

    // Common operations
    public function createVote(array $data): Vote|DemoVote
    {
        $model = $this->getVoteModel();
        return $model::create($data);
    }

    public function getVotes($limit = null)
    {
        $model = $this->getVoteModel();
        $query = $model::where('election_id', $this->election->id);

        if ($limit) {
            $query->limit($limit);
        }

        return $query->get();
    }

    public function getVoteCount(): int
    {
        $model = $this->getVoteModel();
        return $model::where('election_id', $this->election->id)->count();
    }

    public function userHasVoted($userId): bool
    {
        $code = Code::where('user_id', $userId)
            ->where('election_id', $this->election->id)
            ->first();

        return $code && $code->has_voted;
    }

    public function getTopCandidates($postId, $limit = 10)
    {
        $resultModel = $this->getResultModel();
        return $resultModel::topCandidatesForPost($postId, $this->election, $limit);
    }
}
```

### **RealVotingService**

```php
<?php
namespace App\Services;

class RealVotingService extends VotingService
{
    public function getVoteModel(): string
    {
        return Vote::class;
    }

    public function getResultModel(): string
    {
        return Result::class;
    }

    public function isDemo(): bool { return false; }
    public function isReal(): bool { return true; }

    public function verifyVoteIntegrity(Vote $vote): bool
    {
        // Verify vote has required candidates
        // Verify results match vote data
        // Check for tampering
        return true;
    }

    public function getElectionStatistics(): array
    {
        return [
            'total_votes' => $this->getVoteCount(),
            'total_codes' => Code::where('election_id', $this->election->id)->count(),
            'verified_codes' => Code::where('election_id', $this->election->id)
                ->whereNotNull('verified_at')->count(),
            'turnout_percentage' => $this->calculateTurnout(),
        ];
    }

    private function calculateTurnout(): float
    {
        // Implementation
    }
}
```

### **DemoVotingService**

```php
<?php
namespace App\Services;

class DemoVotingService extends VotingService
{
    public function getVoteModel(): string
    {
        return DemoVote::class;
    }

    public function getResultModel(): string
    {
        return DemoResult::class;
    }

    public function isDemo(): bool { return true; }
    public function isReal(): bool { return false; }

    /**
     * Clean up demo votes older than N days
     * Useful for keeping demo data fresh
     */
    public function cleanupOlderThan($days = 30): int
    {
        return DemoVote::where('election_id', $this->election->id)
            ->where('created_at', '<', Carbon::now()->subDays($days))
            ->delete();
    }

    /**
     * Reset all demo voting data for this election
     * Use with caution!
     */
    public function reset(): array
    {
        $votesDeleted = DemoVote::where('election_id', $this->election->id)->delete();
        $resultsDeleted = DemoResult::where('election_id', $this->election->id)->delete();

        Code::where('election_id', $this->election->id)->update([
            'has_voted' => 0,
            'verified_at' => null,
        ]);

        return [
            'votes_deleted' => $votesDeleted,
            'results_deleted' => $resultsDeleted,
        ];
    }
}
```

---

## Controller Flow

### **VoteController: 5-Step Workflow**

```php
<?php
namespace App\Http\Controllers;

class VoteController extends Controller
{
    /**
     * STEP 1: Code Creation
     * GET /v/{vslug}/code/create
     */
    public function create(Request $request)
    {
        $auth_user = $this->getUser($request);
        $election = $this->getElection($request);

        // Check election-aware eligibility
        if (!$this->isUserEligibleToVote($auth_user, $election)) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not eligible to vote in this election.');
        }

        // Get or create code for this election
        $code = Code::firstOrCreate(
            ['user_id' => $auth_user->id, 'election_id' => $election->id],
            ['can_vote_now' => $auth_user->can_vote_now]
        );

        return Inertia::render('Vote/CreateVotingPage', [
            'user_name' => $auth_user->name,
            'election_type' => $election->type,
        ]);
    }

    /**
     * STEP 2: Agreement
     * Display terms and conditions
     */
    public function showAgreement(Request $request)
    {
        $auth_user = $this->getUser($request);
        $election = $this->getElection($request);

        return Inertia::render('Vote/AgreementPage', [
            'election_type' => $election->type,
        ]);
    }

    /**
     * STEP 3: Vote Creation (Candidates Selected)
     * GET /v/{vslug}/vote/create
     * POST /v/{vslug}/vote/submit
     */
    public function create(Request $request)
    {
        $auth_user = $this->getUser($request);
        $election = $this->getElection($request);

        // Fetch candidates from database
        $national_posts = Post::where('is_national_wide', 1)
            ->with('candidates')
            ->get();

        // Check eligibility again
        if (!$this->isUserEligibleToVote($auth_user, $election)) {
            return redirect()->route('dashboard')
                ->with('error', 'Not eligible.');
        }

        return Inertia::render('Vote/VotingPage', [
            'national_posts' => $national_posts,
            'election_type' => $election->type,
        ]);
    }

    public function first_submission(Request $request)
    {
        $auth_user = $this->getUser($request);
        $election = $this->getElection($request);

        // Get vote data from frontend
        $vote_data = $request->validate([
            'national_selected_candidates' => 'array',
            'regional_selected_candidates' => 'array',
        ]);

        // Store in session (under user auth, before anonymization)
        $code = $auth_user->code;
        $session_name = 'vote_data_' . $auth_user->id;
        $request->session()->put($session_name, $vote_data);
        $code->session_name = $session_name;
        $code->save();

        return redirect()->route('slug.vote.verify');
    }

    /**
     * STEP 4: Vote Verification
     * GET /v/{vslug}/vote/verify
     */
    public function verify(Request $request)
    {
        $auth_user = $this->getUser($request);
        $election = $this->getElection($request);
        $code = $auth_user->code;

        // Load vote data from session
        $vote_data = $request->session()->get($code->session_name);

        if (!$vote_data) {
            return redirect()->route('slug.vote.create')
                ->with('error', 'Vote session expired.');
        }

        // Show verification page with selected candidates
        return Inertia::render('Vote/VerifyPage', [
            'vote_data' => $this->processVoteData($vote_data),
            'election_type' => $election->type,
        ]);
    }

    /**
     * STEP 5: Final Submission
     * POST /v/{vslug}/vote/verify (with code2)
     */
    public function store(Request $request)
    {
        $auth_user = $this->getUser($request);
        $election = $this->getElection($request);
        $code = $auth_user->code;

        // Verify second code
        $voting_code = $request->validate([
            'voting_code' => 'required|string|min:6|max:6'
        ])['voting_code'];

        if (!$this->verifySubmittedCode($code->code2, $voting_code)) {
            return back()->withErrors(['voting_code' => 'Invalid code.']);
        }

        // Get vote data from session
        $vote_data = $request->session()->get($code->session_name);

        // Generate verification key (for vote receipt)
        $private_key = bin2hex(random_bytes(16));
        $hashed_key = hash('sha256', $private_key);

        // CRITICAL: Save vote ANONYMOUSLY using factory service
        $this->save_vote($vote_data, $hashed_key, $election, $auth_user);

        // Mark user as voted (in codes table, which has user_id)
        $code->update(['has_voted' => 1]);

        // Send vote receipt
        $auth_user->notify(new VoteConfirmation($private_key));

        // Redirect to completion
        return redirect()->route('slug.vote.complete');
    }

    /**
     * HELPER: Save vote to appropriate table
     */
    private function save_vote(
        $vote_data,
        $hashed_voting_key,
        Election $election,
        User $auth_user
    ) {
        $votingService = VotingServiceFactory::make($election);
        $voteModel = $votingService->getVoteModel();
        $resultModel = $votingService->getResultModel();

        // Create vote (ANONYMOUS - no user_id)
        $vote = new $voteModel;
        $vote->voting_code = $hashed_voting_key;
        $vote->election_id = $election->id;
        // NO: $vote->user_id = $auth_user->id;  (breaks anonymity)
        $vote->save();

        // Save results for each candidate
        foreach ($vote_data['national_selected_candidates'] as $candidacy) {
            $result = new $resultModel;
            $result->vote_id = $vote->id;
            $result->election_id = $election->id;  // Scoping
            $result->post_id = $candidacy['post_id'];
            $result->candidacy_id = $candidacy['candidacy_id'];
            // NO: $result->user_id = $auth_user->id;  (breaks anonymity)
            $result->save();
        }

        Log::info('Vote saved successfully (anonymously)', [
            'vote_id' => $vote->id,
            'election_type' => $election->type,
        ]);
    }

    /**
     * HELPER: Check election-aware eligibility
     */
    private function isUserEligibleToVote(User $user, Election $election): bool
    {
        if ($election->isDemo()) {
            return true;  // Demo: allow all
        }

        // Real: check timing
        return $user->can_vote_now == 1;
    }

    /**
     * HELPER: Get user from request
     */
    private function getUser(Request $request): User
    {
        return $request->attributes->has('voter')
            ? $request->attributes->get('voter')
            : auth()->user();
    }

    /**
     * HELPER: Get election from middleware
     */
    private function getElection(Request $request): Election
    {
        return $request->attributes->get('election')
            ?? Election::where('type', 'real')->first();
    }
}
```

---

## Routes & Middleware

### **ElectionRoutes Configuration**

```php
<?php
// routes/election/electionRoutes.php

use App\Http\Controllers\ElectionController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\VoteController;

// ============ ELECTION SELECTION ROUTES ============

Route::group(['prefix' => 'election'], function () {
    // Show election selection page
    Route::get('select', [ElectionController::class, 'selectElection'])
        ->name('election.select');

    // Handle election selection
    Route::post('select', [ElectionController::class, 'storeElection'])
        ->name('election.store');

    // Quick demo election start
    Route::get('demo/start', [ElectionController::class, 'startDemo'])
        ->name('election.demo.start');
});

// ============ SLUG-BASED VOTING WORKFLOW ============

Route::prefix('v/{vslug}')
    ->middleware([
        'voter.slug.window',        // Verify voter slug is valid
        'voter.step.order',         // Enforce step ordering
        'vote.eligibility',         // Check voting eligibility
        'validate.voting.ip',       // Verify IP if needed
        'election',                 // Resolve election context
    ])
    ->group(function () {

        // STEP 1: Code Creation
        Route::get('code/create', [CodeController::class, 'create'])
            ->name('slug.code.create');
        Route::post('code', [CodeController::class, 'store'])
            ->name('slug.code.store');

        // STEP 2: Agreement
        Route::get('vote/agreement', [CodeController::class, 'showAgreement'])
            ->name('slug.code.agreement');
        Route::post('code/agreement', [CodeController::class, 'submitAgreement'])
            ->name('slug.code.agreement.submit');

        // STEP 3: Vote Creation
        Route::get('vote/create', [VoteController::class, 'create'])
            ->name('slug.vote.create');
        Route::post('vote/submit', [VoteController::class, 'first_submission'])
            ->name('slug.vote.submit');

        // STEP 4: Vote Verification
        Route::get('vote/verify', [VoteController::class, 'verify'])
            ->name('slug.vote.verify');
        Route::post('vote/verify', [VoteController::class, 'store'])
            ->name('slug.vote.store');

        // STEP 5: Completion
        Route::get('vote/complete', function (Request $request) {
            return Inertia::render('Vote/Complete');
        })->name('slug.vote.complete');
    });

// ============ BACKWARD COMPATIBILITY ============

// Traditional voting routes (no slug) - still work, use real election by default
Route::middleware(['web', 'auth', 'election'])->group(function () {
    Route::get('/vote/create', [VoteController::class, 'create'])
        ->name('vote.create');
    Route::get('/vote/verify', [VoteController::class, 'verify'])
        ->name('vote.verify');
});
```

### **ElectionMiddleware**

```php
<?php
namespace App\Http\Middleware;

use App\Models\Election;
use Closure;

/**
 * Resolves election context for voting requests
 *
 * Resolution order:
 * 1. Session-selected election (user explicitly chose)
 * 2. Route parameter election
 * 3. DEFAULT: First real active election (backward compatibility)
 */
class ElectionMiddleware
{
    public function handle($request, Closure $next)
    {
        $election = null;

        // 1. Check session for selected election
        $electionId = session('selected_election_id');
        if ($electionId) {
            $election = Election::find($electionId);
        }

        // 2. Check route parameter
        if (!$election && $request->route('election')) {
            $election = $request->route('election');
        }

        // 3. DEFAULT: Use first real active election
        if (!$election) {
            $election = Election::where('type', 'real')
                ->where('is_active', true)
                ->orderBy('id')
                ->first();
        }

        if (!$election) {
            return redirect()->route('dashboard')
                ->with('error', 'No elections available.');
        }

        // Attach to request
        $request->attributes->set('election', $election);

        // Update session for consistency
        session(['selected_election_id' => $election->id]);

        return $next($request);
    }
}
```

---

## Election Context Resolution

### **How Election Context Works**

```
REQUEST INCOMING
    ↓
ElectionMiddleware checks (in order):
    1. session('selected_election_id')?
        └─ User explicitly selected via /election/select
    2. route('election')?
        └─ URL-based election parameter
    3. DEFAULT: First real active election
        └─ Backward compatible fallback
    ↓
$request->attributes->set('election', $election)
    ↓
Controllers access via:
    $election = $request->attributes->get('election')
```

### **User Flows**

#### **Flow 1: Regular Voter (Backward Compatible)**
```
User visits /vote/create
    ↓
ElectionMiddleware resolves:
    - No session selected
    - No route parameter
    - Uses DEFAULT real election
    ↓
User votes in real election (automatic)
```

#### **Flow 2: Demo Testing**
```
User visits /election/demo/start
    ↓
ElectionController::startDemo() sets session
    - session('selected_election_id') = demo_election->id
    ↓
User redirected to /vote/create
    ↓
ElectionMiddleware resolves:
    - Finds session('selected_election_id')
    - Uses demo election
    ↓
User votes in demo election
```

#### **Flow 3: Election Selection**
```
User visits /election/select
    ↓
Shows available elections (real + demo)
    ↓
User selects "Annual Elections 2026"
    ↓
ElectionController::storeElection() sets session
    - session('selected_election_id') = selected->id
    ↓
User redirected to /vote/create
    ↓
ElectionMiddleware resolves:
    - Finds session('selected_election_id')
    - Uses selected election
    ↓
User votes in selected election
```

---

## Code Examples

### **Example 1: Get Voting Statistics**

```php
<?php
$election = Election::find(1);
$service = VotingServiceFactory::make($election);

// Get counts
$total_votes = $service->getVoteCount();
$total_codes = Code::where('election_id', $election->id)->count();
$votes_cast = Code::where('election_id', $election->id)
    ->where('has_voted', 1)
    ->count();

echo "Election: {$election->name}";
echo "Total Codes: {$total_codes}";
echo "Votes Cast: {$votes_cast}";
echo "Turnout: " . ($votes_cast / $total_codes * 100) . "%";
```

### **Example 2: Get Top Candidates**

```php
<?php
$election = Election::find(1);
$service = VotingServiceFactory::make($election);

$postId = "president";
$topCandidates = $service->getTopCandidates($postId, 5);

foreach ($topCandidates as $candidate) {
    echo "{$candidate->candidacy_id}: {$candidate->vote_count} votes";
}
```

### **Example 3: Check if User Voted**

```php
<?php
$user = Auth::user();
$election = Election::find(1);
$service = VotingServiceFactory::make($election);

$hasVoted = $service->userHasVoted($user->id);

if ($hasVoted) {
    echo "User has already voted in {$election->name}";
} else {
    echo "User can still vote";
}
```

### **Example 4: Reset Demo Election Data**

```php
<?php
$election = Election::where('type', 'demo')->first();
$service = VotingServiceFactory::make($election);

$result = $service->reset();

echo "Deleted {$result['votes_deleted']} demo votes";
echo "Deleted {$result['results_deleted']} demo results";
```

### **Example 5: Create Election and Code**

```php
<?php
// Create election
$election = Election::create([
    'slug' => 'annual-2026',
    'name' => 'Annual Elections 2026',
    'type' => 'real',
    'is_active' => true,
    'voting_start_time' => now(),
    'voting_end_time' => now()->addDays(7),
]);

// Create code for voter
$user = User::find(1);
$code = Code::create([
    'user_id' => $user->id,
    'election_id' => $election->id,
    'can_vote_now' => $user->can_vote_now,
]);

// Send verification code
$code->code1 = str_random(6);
$code->save();
$user->notify(new SendVerificationCode($code->code1));
```

---

## Testing the Voting System

### **Setup Test Elections**

```php
<?php
// tests/Feature/VotingTest.php

class VotingTest extends TestCase
{
    protected Election $realElection;
    protected Election $demoElection;
    protected User $voter;

    public function setUp(): void
    {
        parent::setUp();

        // Create test elections
        $this->realElection = Election::create([
            'slug' => 'test-real',
            'name' => 'Test Real Election',
            'type' => 'real',
            'is_active' => true,
        ]);

        $this->demoElection = Election::create([
            'slug' => 'test-demo',
            'name' => 'Test Demo Election',
            'type' => 'demo',
            'is_active' => true,
        ]);

        // Create test voter
        $this->voter = User::factory()->create([
            'can_vote_now' => 1,
        ]);
    }

    /**
     * Test: Real election respects can_vote_now flag
     */
    public function test_real_election_respects_timing_restrictions()
    {
        $this->voter->update(['can_vote_now' => 0]);

        $response = $this->actingAs($this->voter)
            ->get('/v/{slug}/vote/create', [
                'vslug' => $this->realElection->slug,
            ]);

        $response->assertRedirect('/dashboard');
    }

    /**
     * Test: Demo election allows all users
     */
    public function test_demo_election_allows_all_users()
    {
        $this->voter->update(['can_vote_now' => 0]);  // Ineligible

        session(['selected_election_id' => $this->demoElection->id]);

        $response = $this->actingAs($this->voter)
            ->get('/v/{slug}/vote/create', [
                'vslug' => $this->demoElection->slug,
            ]);

        $response->assertOk();  // Should allow
    }

    /**
     * Test: Vote anonymity is preserved
     */
    public function test_vote_has_no_user_id()
    {
        $code = Code::create([
            'user_id' => $this->voter->id,
            'election_id' => $this->realElection->id,
        ]);

        session(['selected_election_id' => $this->realElection->id]);

        // Complete voting process
        $response = $this->actingAs($this->voter)
            ->post('/v/{slug}/vote/verify', [
                'voting_code' => '123456',  // Test code
            ]);

        // Verify vote was saved without user_id
        $vote = Vote::latest()->first();
        $this->assertNull($vote->user_id);
        $this->assertEquals($this->realElection->id, $vote->election_id);
    }

    /**
     * Test: Election scoping works correctly
     */
    public function test_election_scoping()
    {
        Vote::create([
            'election_id' => $this->realElection->id,
            'voting_code' => 'hash1',
        ]);

        Vote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => 'hash2',
        ]);

        $service = VotingServiceFactory::make($this->realElection);
        $count = $service->getVoteCount();

        $this->assertEquals(1, $count);  // Only real election vote
    }

    /**
     * Test: Demo data can be reset
     */
    public function test_demo_election_reset()
    {
        DemoVote::create([
            'election_id' => $this->demoElection->id,
            'voting_code' => 'hash1',
        ]);

        $service = VotingServiceFactory::make($this->demoElection);
        $service->reset();

        $this->assertEquals(0, DemoVote::count());
    }
}
```

---

## Common Tasks

### **Task 1: Add a New Election Type**

```php
<?php
// Step 1: Create new election
$election = Election::create([
    'slug' => 'board-election',
    'name' => 'Board Elections 2026',
    'type' => 'real',  // or 'demo'
    'is_active' => true,
]);

// Step 2: In your code, it just works!
$service = VotingServiceFactory::make($election);
$voteModel = $service->getVoteModel();  // Automatically correct

// Step 3: Query votes for this election
$votes = Vote::where('election_id', $election->id)->get();
```

### **Task 2: Add Eligibility Rule**

```php
<?php
// In VoteController.isUserEligibleToVote()

private function isUserEligibleToVote(User $user, Election $election): bool
{
    if ($election->isDemo()) {
        return true;
    }

    // Add custom rule
    if ($election->slug === 'board-election') {
        return $user->is_board_member == 1;
    }

    return $user->can_vote_now == 1;
}
```

### **Task 3: Export Election Results**

```php
<?php
$election = Election::find(1);
$service = VotingServiceFactory::make($election);

$votes = $service->getVotes();
$results = $service->getResultModel()::where('election_id', $election->id)->get();

// Group by position
$byPosition = $results->groupBy('post_id')->map(function ($items) {
    return $items->groupBy('candidacy_id')
        ->map->count()
        ->sortDesc();
});

// Export to CSV
$csv = fopen('results.csv', 'w');
fputcsv($csv, ['Position', 'Candidate', 'Votes']);
foreach ($byPosition as $position => $candidates) {
    foreach ($candidates as $candidacy_id => $count) {
        fputcsv($csv, [$position, $candidacy_id, $count]);
    }
}
fclose($csv);
```

### **Task 4: Audit Who Authorized Votes (Without Knowing How They Voted)**

```php
<?php
// Codes table has user_id (authorized a vote)
$codes = Code::where('election_id', $election->id)
    ->where('has_voted', 1)
    ->with('user')
    ->get();

foreach ($codes as $code) {
    // We know this user authorized a vote
    echo "User {$code->user->name} voted";

    // But we DON'T know what they voted for
    // (that's in votes table with no user_id)
}
```

---

## Debugging

### **Check Election Context**

```php
<?php
// In your controller
$election = $request->attributes->get('election');

dd([
    'election_id' => $election->id,
    'type' => $election->type,
    'is_demo' => $election->isDemo(),
    'is_real' => $election->isReal(),
]);
```

### **Verify Vote Anonymity**

```php
<?php
// Check if vote has user_id (it shouldn't!)
$vote = Vote::find(1);

if ($vote->user_id !== null) {
    throw new \Exception('SECURITY: Vote has user_id!');
}

// Verify election_id is set
if ($vote->election_id === null) {
    throw new \Exception('SECURITY: Vote missing election_id!');
}
```

### **Check Service Factory**

```php
<?php
$election = Election::find(1);
$service = VotingServiceFactory::make($election);

echo "Service class: " . get_class($service);
echo "Vote model: " . $service->getVoteModel();
echo "Result model: " . $service->getResultModel();

// Verify it's correct
if ($election->isDemo()) {
    assert($service instanceof DemoVotingService);
    assert($service->getVoteModel() === DemoVote::class);
} else {
    assert($service instanceof RealVotingService);
    assert($service->getVoteModel() === Vote::class);
}
```

### **Check Election Middleware Resolution**

```php
<?php
// Add this to ElectionMiddleware temporarily
Log::info('Election Resolution', [
    'session_election' => session('selected_election_id'),
    'route_election' => $request->route('election'),
    'resolved_election' => $election->slug,
]);
```

### **Monitor Logging**

```bash
# Follow voting logs in real-time
tail -f storage/logs/laravel.log | grep -i vote

# Check for anonymity issues
grep -i "user_id.*vote" storage/logs/laravel.log

# Check for election context issues
grep -i "election" storage/logs/laravel.log
```

---

## Best Practices

### **✅ DO**

1. **Always get election from request**
   ```php
   $election = $this->getElection($request);
   ```

2. **Use VotingServiceFactory for models**
   ```php
   $service = VotingServiceFactory::make($election);
   $model = $service->getVoteModel();
   ```

3. **Add election_id to all queries**
   ```php
   Vote::where('election_id', $election->id)->get();
   ```

4. **Log election context**
   ```php
   Log::info('Vote submitted', ['election_id' => $election->id]);
   ```

5. **Test both demo and real elections**
   ```php
   // In tests
   $this->testWithDemoElection();
   $this->testWithRealElection();
   ```

### **❌ DON'T**

1. **Never hardcode Vote or DemoVote**
   ```php
   // WRONG
   $vote = new Vote;

   // RIGHT
   $model = $service->getVoteModel();
   $vote = new $model;
   ```

2. **Never store user_id in votes**
   ```php
   // WRONG
   $vote->user_id = $user->id;

   // RIGHT
   // Don't set it at all
   ```

3. **Never assume election type**
   ```php
   // WRONG
   if ($user->can_vote_now) {
       // Breaks for demo elections!
   }

   // RIGHT
   if ($election->isDemo()) {
       return true;
   }
   return $user->can_vote_now == 1;
   ```

4. **Never query across elections**
   ```php
   // WRONG
   $votes = Vote::all();

   // RIGHT
   $votes = Vote::where('election_id', $election->id)->get();
   ```

5. **Never skip election middleware**
   ```php
   // Routes should always include 'election' middleware
   ->middleware(['election', ...])
   ```

---

## Troubleshooting

### **Issue: "No active elections available"**

**Cause:** No elections marked as `is_active = 1`

**Fix:**
```php
Election::find(1)->update(['is_active' => 1]);
```

### **Issue: "User not eligible to vote" (unexpected)**

**Cause:** User `can_vote_now = 0` AND using real election

**Fix:**
```php
// Check can_vote_now flag
User::find($userId)->update(['can_vote_now' => 1]);

// OR use demo election for testing
session(['selected_election_id' => $demoElection->id]);
```

### **Issue: Demo election data persists**

**Cause:** Demo data wasn't reset

**Fix:**
```php
$service = VotingServiceFactory::make($demoElection);
$service->reset();
```

### **Issue: Vote appears in wrong table**

**Cause:** Wrong service used during save

**Debug:**
```php
// Check which table the vote ended up in
$voteInRealTable = Vote::where('id', $voteId)->exists();
$voteInDemoTable = DemoVote::where('id', $voteId)->exists();

echo "In real table: $voteInRealTable";
echo "In demo table: $voteInDemoTable";
```

### **Issue: Vote has user_id (SECURITY!)**

**Cause:** Manually set or old code path

**Fix:**
```php
// Find and fix it
Vote::whereNotNull('user_id')->each(function ($vote) {
    // This is a security issue! Do not save votes with user_id
    Log::alert('Found vote with user_id!', ['vote_id' => $vote->id]);

    // Option 1: Delete it
    $vote->delete();

    // Option 2: Re-save without user_id
    $vote->user_id = null;
    $vote->save();
});
```

---

## Summary

| Concept | Key Point |
|---------|-----------|
| **Vote Anonymity** | Votes never contain user_id |
| **Dual Separation** | Physical (table names) + Logical (election_id) |
| **Service Factory** | Automatically selects Vote or DemoVote |
| **Election Context** | Resolved by middleware, available in request |
| **Eligibility** | Demo: all users, Real: can_vote_now == 1 |
| **Backward Compat** | Default to real election if not specified |
| **Authorization Chain** | User → Code → Vote (via voting_code hash) |
| **Testing** | Always test demo and real separately |

---

## Quick Reference: API Endpoints

```
GET  /election/select                        → Select election
POST /election/select                        → Store election selection
GET  /election/demo/start                    → Quick demo start

GET  /v/{vslug}/code/create                 → Code creation form
POST /v/{vslug}/code                        → Submit code request

GET  /v/{vslug}/vote/agreement              → Agreement page
POST /v/{vslug}/code/agreement              → Accept agreement

GET  /v/{vslug}/vote/create                 → Voting form
POST /v/{vslug}/vote/submit                 → Submit candidates

GET  /v/{vslug}/vote/verify                 → Verification page
POST /v/{vslug}/vote/verify                 → Final submission (with code2)

GET  /v/{vslug}/vote/complete               → Completion/receipt
```

---

**Need Help?** Check the [ADR_20260203_voting_security.md](../architecture/ADR_20260203_voting_security.md) for architectural decisions and [tests/](../tests/) for working examples.
