# 📚 Single Election Architecture - Comprehensive Developer Guide

**Version:** 1.0
**Date:** 2026-02-04
**Status:** Production Ready
**Architecture:** Laravel + Inertia + Vue 3

---

## 📖 Table of Contents

1. [System Overview](#system-overview)
2. [Architecture Principles](#architecture-principles)
3. [Database Schema](#database-schema)
4. [Code Organization](#code-organization)
5. [Data Flow](#data-flow)
6. [Key Components](#key-components)
7. [Authentication & Authorization](#authentication--authorization)
8. [Voting System](#voting-system)
9. [Demo vs Real Elections](#demo-vs-real-elections)
10. [API Endpoints & Routes](#api-endpoints--routes)
11. [Election Lifecycle](#election-lifecycle)
12. [Security Architecture](#security-architecture)
13. [Error Handling](#error-handling)
14. [Testing Strategy](#testing-strategy)
15. [Common Tasks](#common-tasks)
16. [Troubleshooting](#troubleshooting)
17. [Performance Considerations](#performance-considerations)
18. [Future Enhancements](#future-enhancements)

---

## System Overview

### What is This System?

A **multi-election digital voting platform** with physical table separation between demo (testing) and real (production) voting. The system is built on a simplified **single real election + single demo election** model, eliminating the complexity of simultaneous multiple elections.

### Core Philosophy

```
┌─────────────────────────────────────────────────────────┐
│                    VOTING SYSTEM PHILOSOPHY             │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  1. SIMPLICITY: One real election, one demo election  │
│  2. SEPARATION: Demo data isolated from real data     │
│  3. SECURITY: Anonymous voting, code-based access    │
│  4. EFFICIENCY: Direct login → voting path            │
│  5. AUDITABILITY: All votes traceable and verifiable  │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

### Key Features

| Feature | Implementation |
|---------|-----------------|
| **Multi-Language** | English, German, Nepali |
| **Anonymous Voting** | No user_id stored in votes |
| **Verification Codes** | Unique hex codes for vote lookup |
| **Time-Based Eligibility** | Elections have start/end dates |
| **Role-Based Access** | Admin, voter, user, election_officer |
| **Demo/Real Separation** | Separate tables, separate data stores |
| **Accessibility** | WCAG AA compliant UI |
| **Responsive Design** | Mobile, tablet, desktop optimized |

---

## Architecture Principles

### 1. Simplified Single Election Model

```
OLD (Complex):
  Real Election 1 ─┐
  Real Election 2 ─┼─→ SelectElection Page ─→ Voting Page
  Real Election 3 ─┘

NEW (Simple):
  Real Election ──→ Dashboard ──→ Election Page ──→ Voting
```

**Why?** Reduces conditional logic, faster user flow, fewer edge cases.

### 2. Physical Table Separation

```
Production (votes table):          Testing (demo_votes table):
  - Real election data              - Demo election data
  - Official results                - Test data
  - Permanent audit trail           - Can be reset anytime
  - Anonymous votes                 - Anonymous votes
  - Verification codes              - Verification codes
```

**Why?** Prevents test data corruption of official results. Allows independent reset of demo elections.

### 3. Code-Based Voting Access

```
Flow:
  1. User logs in
  2. Enters verification code (from email/SMS)
  3. Code validity checked in Code model
  4. User granted voting token
  5. Vote recorded anonymously
  6. Verification code becomes verification_code in votes table
```

**Why?** Decouples user identity from vote recording. Enables vote verification without exposing voter identity.

### 4. Election Lifecycle Stages

```
Creation → Configuration → Activation → Voting → Closure → Results
   ↓            ↓             ↓          ↓         ↓        ↓
Create     Set candidates  Enable      Accept    Close    Publish
election   Set posts       voting      votes     voting   results
```

### 5. Multi-Level Authorization

```
Access Level 1: Authentication (are you logged in?)
                ↓
Access Level 2: User Role (admin/voter/user/officer?)
                ↓
Access Level 3: Voter Registration (is_voter flag?)
                ↓
Access Level 4: Voting Code (can_vote_now in Code model?)
                ↓
Access Level 5: Election Activity (is election currently active?)
                ↓
Access Level 6: One-Vote Rule (already voted?)
```

---

## Database Schema

### Core Tables

#### 1. `elections` Table

```sql
CREATE TABLE elections (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    slug VARCHAR(255) UNIQUE,
    type ENUM('demo', 'real'),
    description TEXT,
    is_active BOOLEAN DEFAULT false,
    start_date TIMESTAMP,
    end_date TIMESTAMP,
    settings JSON,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    INDEX (type),
    INDEX (is_active),
    INDEX (start_date, end_date)
);
```

**Key Concept:** Elections are the root aggregates. All voting data branches from here.

```
Election
  ├─ CodeController (generates verification codes)
  ├─ VoteController (records votes)
  ├─ CandidacyController (manages candidates)
  └─ ResultController (publishes results)
```

#### 2. `users` Table (Voting-Related Fields)

```sql
-- Voting-related columns (PROTECTED from mass assignment):
is_voter          BOOLEAN       -- Is user registered as voter?
can_vote          BOOLEAN       -- Is user approved to vote?
has_voted         BOOLEAN       -- Has user already voted?
wants_to_vote     BOOLEAN       -- User intention flag

-- Audit fields:
voter_registration_at TIMESTAMP -- When registered as voter
approvedBy        VARCHAR(255)  -- Which admin approved?
suspendedBy       VARCHAR(255)  -- Which admin suspended?
suspended_at      TIMESTAMP     -- When suspended?

-- Security fields:
voting_ip         VARCHAR(255)  -- IP address during voting
user_ip           VARCHAR(255)  -- Current IP address
```

**Security Note:** These fields are in `$guarded` array to prevent mass assignment manipulation.

```php
// ❌ WRONG: Won't work due to guarding
User::create(['is_voter' => true, 'can_vote' => true]);

// ✅ RIGHT: Direct database update
DB::table('users')
    ->where('id', $userId)
    ->update(['is_voter' => true, 'can_vote' => true]);

// ✅ RIGHT: Update loaded model
$user = User::find($userId);
$user->is_voter = true;
$user->save();
```

#### 3. `codes` Table (Voting Verification)

```sql
CREATE TABLE codes (
    id BIGINT PRIMARY KEY,
    user_id VARCHAR(255),           -- Foreign key to users.user_id
    election_id BIGINT,             -- Which election?
    code VARCHAR(255) UNIQUE,       -- The verification code
    voting_code_hash VARCHAR(255),  -- Hashed for security
    can_vote_now BOOLEAN DEFAULT 0, -- Is user eligible?
    has_voted BOOLEAN DEFAULT 0,    -- Has user voted?
    has_agreed_to_vote BOOLEAN,     -- User agreed to terms?
    voting_started_at TIMESTAMP,    -- When did voting start?
    voting_time_in_minutes INT DEFAULT 20,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    UNIQUE (user_id, election_id),  -- Only one code per user per election
    INDEX (election_id),
    INDEX (can_vote_now),
    FOREIGN KEY (election_id) REFERENCES elections(id)
);
```

**Key Concept:** The Code model is the **gatekeeper** for voting eligibility. Voting happens through codes, not direct user access.

```
Code Model Responsibilities:
  ├─ Store verification codes
  ├─ Track voting eligibility (can_vote_now)
  ├─ Track vote status (has_voted)
  ├─ Measure voting time (voting_started_at)
  └─ Record voter agreement (has_agreed_to_vote)
```

#### 4. `votes` Table (Real Election Votes)

```sql
CREATE TABLE votes (
    id BIGINT PRIMARY KEY,
    election_id BIGINT,
    verification_code VARCHAR(255),   -- Link to vote without user_id
    voting_code_hash VARCHAR(255),    -- Hashed code for security

    -- Vote selections (60 candidate columns for up to 60 posts)
    selected_candidate_1 BIGINT,
    selected_candidate_2 BIGINT,
    ... (up to 60)

    -- Audit fields:
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    INDEX (election_id),
    INDEX (verification_code),
    FOREIGN KEY (election_id) REFERENCES elections(id)
);
```

**Key Concept:** Votes are **anonymous** - no user_id field. Voter identified only by verification_code.

```
Vote Recording Flow:
  1. User enters verification_code
  2. Code is verified (exists in Code model, can_vote_now=1)
  3. Vote created with verification_code (not user_id)
  4. User can later retrieve vote using verification_code
  5. No one can connect vote to user (anonymous)
```

#### 5. `demo_votes` Table (Demo Election Votes)

```sql
-- Same structure as votes table, separate storage
CREATE TABLE demo_votes (
    -- Identical to votes table
    -- Separate storage allows independent reset
);
```

#### 6. `candidacies` Table (Real Election Candidates)

```sql
CREATE TABLE candidacies (
    id BIGINT PRIMARY KEY,
    candidacy_id VARCHAR(255) UNIQUE,
    user_id VARCHAR(255),             -- Candidate's user_id
    election_id BIGINT,
    post_id VARCHAR(255),             -- Which position?
    proposer_id VARCHAR(255),
    supporter_id VARCHAR(255),
    image_path_1 VARCHAR(255),
    image_path_2 VARCHAR(255),
    image_path_3 VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    UNIQUE (user_id, election_id, post_id),
    FOREIGN KEY (election_id) REFERENCES elections(id),
    INDEX (post_id)
);
```

#### 7. `demo_candidacies` Table (Demo Election Candidates)

```sql
-- Same as candidacies, separate for demo isolation
CREATE TABLE demo_candidacies (
    -- Identical to candidacies table
);
```

### Data Flow Diagram

```
┌──────────────┐
│   Election   │
├──────────────┤
│ - 1 real     │
│ - 1 demo     │
└──────┬───────┘
       │
       ├─────────────────────┬────────────────┐
       │                     │                │
   ┌───▼────┐         ┌─────▼──┐      ┌─────▼─────┐
   │ Codes  │         │ Votes  │      │Candidacies│
   └────┬───┘         └────┬───┘      └───────────┘
        │                  │
    ┌───▼────────────────────▼───┐
    │  Vote Recording Process    │
    ├───────────────────────────┤
    │ 1. User enters code       │
    │ 2. Code validated         │
    │ 3. Vote created with code │
    │    (no user_id)           │
    │ 4. Vote stored            │
    │ 5. Verification code saved│
    │    in votes table         │
    └──────────────────────────┘
```

### Relationship Mapping

```
Election (1)
  ├── Codes (Many)
  │   └── User (1) [linked by user_id]
  ├── Votes (Many)
  │   └── No direct user link (anonymous)
  ├── DemoVotes (Many)
  ├── Candidacies (Many)
  ├── DemoCandidacies (Many)
  └── Results (Many)
```

---

## Code Organization

### Directory Structure

```
app/
├── Models/
│   ├── Election.php          # Election aggregate root
│   ├── Code.php              # Voting code model
│   ├── Vote.php              # Real election vote
│   ├── DemoVote.php          # Demo election vote
│   ├── BaseVote.php          # Abstract base for votes
│   ├── Candidacy.php         # Real election candidate
│   ├── DemoCandidate.php     # Demo election candidate
│   ├── User.php              # User with voter flags
│   └── Result.php            # Election results
│
├── Http/
│   ├── Controllers/
│   │   ├── Election/
│   │   │   └── ElectionController.php
│   │   ├── CodeController.php
│   │   ├── VoteController.php
│   │   └── ResultController.php
│   │
│   ├── Requests/
│   │   ├── StoreVoteRequest.php
│   │   ├── StoreCodeRequest.php
│   │   └── VerifyCodeRequest.php
│   │
│   ├── Responses/
│   │   └── LoginResponse.php  # Post-login routing
│   │
│   └── Middleware/
│       ├── HandleInertiaRequests.php
│       ├── VerifyVotingCode.php
│       └── CheckElectionActive.php
│
├── Services/
│   ├── ElectionService.php
│   ├── VotingCodeService.php
│   └── VoteVerificationService.php
│
└── Events/
    ├── VoteRecorded.php
    ├── CodeGenerated.php
    └── ElectionStarted.php

routes/
├── web.php                 # Main web routes
└── election/
    └── electionRoutes.php  # Election-specific routes

resources/
├── js/
│   ├── Pages/
│   │   ├── Election/
│   │   │   ├── ElectionPage.vue      # Voting page
│   │   │   └── SelectElection.vue    # Multi-election selector
│   │   └── Dashboard/
│   │       └── ElectionDashboard.vue
│   │
│   └── locales/
│       └── pages/
│           └── Election/
│               ├── en.json
│               ├── de.json
│               └── np.json

database/
├── migrations/
│   ├── create_elections_table.php
│   ├── create_codes_table.php
│   ├── create_votes_table.php
│   ├── create_demo_votes_table.php
│   ├── create_candidacies_table.php
│   └── create_demo_candidacies_table.php
│
└── seeders/
    ├── ElectionSeeder.php
    ├── DemoCandidateSeeder.php
    └── DatabaseSeeder.php
```

### Naming Conventions

```
✅ DO:
  - Election models: singular (Election, Code, Vote)
  - Model relationships: plural (votes(), codes())
  - Demo-specific: DemoVote, DemoCandidate, demo_votes
  - Controllers: singular + action (CodeController::create)
  - Routes: resources (Route::resource('codes', CodeController))
  - Events: past tense (VoteRecorded, CodeGenerated)
  - Services: action + Service (VotingCodeService)
  - Requests: action + Request (StoreVoteRequest)

❌ DON'T:
  - Mixed case in table names (UsersVotes is wrong, user_votes)
  - Generic names (DataModel, VoteModel)
  - Abbreviated names (Elec, Cand, Usr)
  - Services as controllers (vote-service handling HTTP)
```

---

## Data Flow

### 1. Login Flow

```
User visits /login
    ↓
User enters credentials
    ↓
Fortify\Authenticates user (session/JWT)
    ↓
LoginResponse::toResponse() [CUSTOM]
    ├─ Check: hasRole('admin')?
    │   ├─ YES → redirect admin.dashboard
    │   └─ NO ↓
    ├─ Check: is_voter = true?
    │   ├─ YES → redirect dashboard
    │   └─ NO → redirect dashboard
    ↓
User at dashboard
```

**File:** `app/Http/Responses/LoginResponse.php`

```php
public function toResponse($request)
{
    $user = $request->user();

    // Admin flow
    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    // All others (voter or non-voter) → dashboard
    return redirect()->route('dashboard');
}
```

### 2. Dashboard Display Flow

```
User at / or /dashboard
    ↓
ElectionController::dashboard()
    ├─ Get authenticated user
    ├─ Get real election
    │
    ├─ Check conditions:
    │   ├─ is_voter = true?
    │   ├─ Real election active?
    │   └─ Real election currentlyActive()?
    │
    ├─ IF all true:
    │   └─ Render ElectionPage [VOTING]
    │
    └─ ELSE:
        └─ Render Dashboard [NO VOTING]
```

**File:** `app/Http/Controllers/Election/ElectionController.php`

```php
public function dashboard()
{
    $authUser = Auth::user();
    $realElection = Election::where('type', 'real')
        ->where('is_active', true)
        ->first();

    // Election day
    if ($realElection && $realElection->isCurrentlyActive()
        && $authUser->is_voter) {
        return Inertia::render('Election/ElectionPage', [
            'activeElection' => $realElection,
            'authUser' => $authUser,
        ]);
    }

    // Non-voting day
    return Inertia::render('Dashboard/ElectionDashboard', [
        'authUser' => $authUser,
        'ballotAccess' => $ballotAccess,
    ]);
}
```

### 3. Voting Code Generation Flow

```
User clicks "Get Verification Code" on ElectionPage
    ↓
CodeController::create($vslug)
    ├─ Find election by slug
    ├─ Get authenticated user
    ├─ Check: is_voter? (must be true)
    ├─ Check: not already voted?
    │
    ├─ Generate verification code
    │   └─ bin2hex(random_bytes(16)) → 32-char hex
    │
    ├─ Create Code record
    │   ├── user_id = $user->user_id
    │   ├── election_id = $election->id
    │   ├── code = generated_code
    │   ├── can_vote_now = 1
    │   └── has_voted = 0
    │
    ├─ Send code to user
    │   └─ Email or SMS
    │
    └─ Redirect to code agreement page
```

**File:** `app/Http/Controllers/CodeController.php`

```php
public function create($vslug)
{
    $election = Election::where('slug', $vslug)->first();
    $user = Auth::user();

    // Generate unique code
    $code = bin2hex(random_bytes(16));

    // Create code record
    Code::create([
        'user_id' => $user->user_id,
        'election_id' => $election->id,
        'code' => $code,
        'can_vote_now' => true,
        'voting_started_at' => now(),
    ]);

    // Send to user
    SendVerificationCode::dispatch($user, $code);

    return Inertia::render('CodeAgreement', [
        'code' => $code,
        'election' => $election,
    ]);
}
```

### 4. Vote Recording Flow

```
User enters code and clicks "Vote"
    ↓
VoteController::store(StoreVoteRequest $request)
    ├─ Validate: Code exists?
    ├─ Validate: Code matches user?
    ├─ Validate: can_vote_now = 1?
    ├─ Validate: has_voted = 0?
    ├─ Validate: Candidate selections valid?
    │
    ├─ Create Vote record
    │   ├── election_id = from code
    │   ├── verification_code = code (NO user_id)
    │   ├── selected_candidate_1 = user selection
    │   ├── selected_candidate_2 = user selection
    │   └── ... (up to 60 candidates)
    │
    ├─ Update Code: has_voted = 1
    ├─ Update User: has_voted = 1
    │
    ├─ Record verification_code for later lookup
    │   └─ Vote can be verified by entering code again
    │
    ├─ Fire VoteRecorded event
    │   └─ Triggers result calculations
    │
    └─ Redirect to success page
```

**File:** `app/Http/Controllers/VoteController.php`

```php
public function store(StoreVoteRequest $request)
{
    $code = Code::where('code', $request->code)
        ->where('user_id', Auth::user()->user_id)
        ->first();

    // Validate
    if (!$code || !$code->can_vote_now || $code->has_voted) {
        abort(403, 'Not eligible to vote');
    }

    // Record vote (anonymous - no user_id)
    $vote = Vote::create([
        'election_id' => $code->election_id,
        'verification_code' => $code->code,
        'selected_candidate_1' => $request->candidate_1,
        'selected_candidate_2' => $request->candidate_2,
        // ... up to 60
    ]);

    // Update status
    $code->update(['has_voted' => true]);
    Auth::user()->update(['has_voted' => true]);

    // Fire event
    event(new VoteRecorded($vote));

    return redirect()->route('vote.success', [
        'verification_code' => $vote->verification_code
    ]);
}
```

### 5. Vote Verification Flow

```
User visits /vote/verify to retrieve their vote
    ↓
User enters verification_code
    ↓
VoteController::show($verification_code)
    ├─ Find vote by verification_code (NOT user_id)
    ├─ Decrypt candidate selections
    ├─ Display vote anonymously
    │   ├─ No user identification
    │   ├─ Only shows: candidates selected, positions
    │   └─ Verifiable but anonymous
    │
    └─ Display: "Vote recorded successfully"
```

---

## Key Components

### 1. Election Model

**File:** `app/Models/Election.php`

```php
class Election extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'type',
        'start_date', 'end_date', 'is_active', 'settings'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function codes() { return $this->hasMany(Code::class); }
    public function votes() { return $this->hasMany(Vote::class); }
    public function candidacies() { return $this->hasMany(Candidacy::class); }

    // Key Methods
    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) return false;

        $now = now();
        return $now >= $this->start_date && $now <= $this->end_date;
    }

    public function isDemo(): bool { return $this->type === 'demo'; }
    public function isReal(): bool { return $this->type === 'real'; }
    public function totalVotesCast(): int { ... }
    public function voterTurnout(): ?float { ... }
}
```

**Usage:**
```php
// Get active elections
$activeElection = Election::where('is_active', true)
    ->where('type', 'real')
    ->first(fn($e) => $e->isCurrentlyActive());

// Check timing
if ($election->isCurrentlyActive()) {
    // Show voting interface
}

// Get statistics
$stats = $election->getStatistics();
```

### 2. Code Model

**File:** `app/Models/Code.php`

```php
class Code extends Model
{
    protected $fillable = [
        'user_id', 'election_id', 'code', 'voting_code_hash',
        'can_vote_now', 'has_voted', 'has_agreed_to_vote',
        'voting_started_at', 'voting_time_in_minutes'
    ];

    // Key Methods
    public function isValid(): bool
    {
        return $this->can_vote_now
            && !$this->has_voted
            && !$this->isExpired();
    }

    public function isExpired(): bool
    {
        $elapsed = now()->diffInMinutes($this->voting_started_at);
        return $elapsed > $this->voting_time_in_minutes;
    }

    public function timeRemaining(): int
    {
        return max(0,
            $this->voting_time_in_minutes
            - now()->diffInMinutes($this->voting_started_at)
        );
    }
}
```

**Usage:**
```php
$code = Code::where('code', $userEnteredCode)->first();

if (!$code->isValid()) {
    abort(403, 'Code is invalid or expired');
}

// Record voting start
$code->update(['voting_started_at' => now()]);

// Check time remaining
echo "Time left: " . $code->timeRemaining() . " minutes";

// Mark as voted
$code->update(['has_voted' => true]);
```

### 3. Vote Model (Anonymous Voting)

**File:** `app/Models/Vote.php` and `app/Models/DemoVote.php`

```php
class Vote extends Model
{
    protected $fillable = [
        'election_id', 'verification_code', 'voting_code_hash',
        'selected_candidate_1', 'selected_candidate_2', // ... up to 60
    ];

    // NOTE: No user_id field - votes are anonymous!
    // Identified only by verification_code

    public function election() { return $this->belongsTo(Election::class); }

    // Retrieve vote by verification code (anonymous lookup)
    public static function byVerificationCode($code)
    {
        return self::where('verification_code', $code)->first();
    }
}
```

**Key Concept: Why No user_id?**

```
❌ WRONG (Breaks anonymity):
Vote {
    user_id: 123,              // This identifies the voter!
    selected_candidate_1: 5,
}

✅ RIGHT (Maintains anonymity):
Vote {
    verification_code: "abc123def456...",  // Can't identify user from this
    selected_candidate_1: 5,
}

Verification Flow:
1. User has verification_code (from email/SMS)
2. User enters code to retrieve vote
3. Vote shown but no user identification
4. Voter can verify vote is recorded
5. Others cannot connect vote to voter
```

### 4. LoginResponse (Custom)

**File:** `app/Http/Responses/LoginResponse.php`

```php
class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        // ADMIN: Always to admin dashboard
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        // EVERYONE ELSE: To dashboard
        // Dashboard will check if voting is available
        return redirect()->route('dashboard');
    }
}
```

**Why custom LoginResponse?**

```
Default Laravel behavior: POST /login → redirect home (/)
Our behavior:
  - Admin user → admin.dashboard
  - Voter user → dashboard (checks election status)
  - Non-voter → dashboard

This centralizes routing logic in one place.
```

### 5. ElectionController (Dashboard Logic)

**File:** `app/Http/Controllers/Election/ElectionController.php`

```php
class ElectionController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $election = Election::where('type', 'real')
            ->where('is_active', true)
            ->first();

        // Route: Is voting available RIGHT NOW?
        if ($this->isVotingAvailable($user, $election)) {
            return Inertia::render('Election/ElectionPage', [
                'activeElection' => $election,
            ]);
        }

        // Route: Show dashboard
        return Inertia::render('Dashboard/ElectionDashboard', [
            'ballotAccess' => $this->determineBallotAccess($user),
        ]);
    }

    private function isVotingAvailable($user, $election): bool
    {
        return $election !== null
            && $election->isCurrentlyActive()
            && $user->is_voter;
    }

    private function determineBallotAccess($user): array
    {
        // Complex logic for ballot access determination
        // Returns detailed access info for UI
    }

    public function startDemo()
    {
        // Bypass all eligibility checks for demo
        $demo = Election::where('type', 'demo')
            ->where('is_active', true)
            ->first();

        return redirect()->route('code.create',
            ['vslug' => $demo->slug]);
    }
}
```

---

## Authentication & Authorization

### Login Flow (Fortify + Custom Response)

```
┌────────────────────────────────────┐
│   User submits login form           │
└──────────────┬─────────────────────┘
               ↓
┌────────────────────────────────────┐
│   Laravel Fortify processes login   │
│   - Validates credentials           │
│   - Creates session/JWT             │
└──────────────┬─────────────────────┘
               ↓
┌────────────────────────────────────┐
│   Custom LoginResponse triggered    │
│   - Checks user role               │
│   - Determines routing             │
└──────────────┬─────────────────────┘
               ↓
┌────────────────────────────────────┐
│   Redirect to appropriate page      │
│   - Admin → admin.dashboard        │
│   - Others → dashboard             │
└────────────────────────────────────┘
```

**Registration:** `app/Providers/FortifyServiceProvider.php`

```php
$this->app->singleton(
    LoginResponseContract::class,
    LoginResponse::class
);
```

### Role-Based Access

**Roles in system:**

| Role | Permissions | Use Case |
|------|-------------|----------|
| `admin` | All system operations | System administrator |
| `election_officer` | Elections management | Election coordinator |
| `voter` | Vote in elections | Regular members |
| `user` | Basic platform access | Platform members |

**How to check:**

```php
// Check single role
if (Auth::user()->hasRole('admin')) { }

// Check multiple roles
if (Auth::user()->hasAnyRole(['admin', 'election_officer'])) { }

// Check permission
if (Auth::user()->hasPermission('create-elections')) { }
```

### Voter Eligibility Chain

```
Level 1: User is logged in?
         └─ If NO → Redirect login

Level 2: User has is_voter flag?
         └─ If NO → Show non-voting dashboard

Level 3: Real election is active?
         └─ If NO → Show no-voting message

Level 4: Real election is currently active (time-based)?
         └─ If NO → Show waiting message

Level 5: User has valid voting code?
         └─ If NO → Prompt for code entry

Level 6: Code is valid (can_vote_now = 1)?
         └─ If NO → Show ineligible message

Level 6b: User hasn't already voted?
          └─ If YES → Show already-voted message

Result: User can vote ✓
```

---

## Voting System

### How Votes Are Recorded

```
Traditional System (Unsafe):
┌─────────────────────────────────┐
│ Vote {                          │
│   user_id: 123,        ← BREAKS │
│   selected_candidate_1: 5,      │ ANONYMITY!
│   selected_candidate_2: 8,      │
│ }                               │
└─────────────────────────────────┘

Public Digit System (Secure):
┌──────────────────────────────────────┐
│ Vote {                               │
│   verification_code: "abc123...",   │
│   selected_candidate_1: 5,           │
│   selected_candidate_2: 8,           │
│ }                                    │
│                                      │
│ Code → User mapping stored ONLY in  │
│ Code table (for vote verification,  │
│ but never linked to vote table)     │
└──────────────────────────────────────┘
```

### Vote Recording Process

```php
// 1. Get user's verification code
$code = Code::where('code', $userEnteredCode)
    ->where('user_id', Auth::user()->user_id)
    ->first();

// 2. Validate code
if (!$code || !$code->can_vote_now || $code->has_voted) {
    abort(403, 'Cannot vote');
}

// 3. Record vote (ANONYMOUS - no user_id)
$vote = Vote::create([
    'election_id' => $code->election_id,
    'verification_code' => $code->code,  // Use code, not user_id
    'selected_candidate_1' => $request->candidate_1,
    'selected_candidate_2' => $request->candidate_2,
    // ... etc
]);

// 4. Update tracking
$code->update(['has_voted' => true]);
Auth::user()->update(['has_voted' => true]);

// 5. Trigger results calculation
event(new VoteRecorded($vote));

// 6. Return verification code for later lookup
return response()->json([
    'verification_code' => $vote->verification_code,
    'message' => 'Vote recorded successfully'
]);
```

### Vote Verification (Lookup)

```php
// User wants to verify their vote
$userProvidedCode = request()->code;

// Query: Can't identify user, only by code
$vote = Vote::where('verification_code', $userProvidedCode)->first();

if (!$vote) {
    abort(404, 'Vote not found');
}

// Display vote (anonymously)
return response()->json([
    'verified' => true,
    'candidates_selected' => [
        'President' => $vote->selected_candidate_1,
        'Vice President' => $vote->selected_candidate_2,
    ],
    'note' => 'This vote is anonymous. Only you have the code.'
]);
```

### Vote Column Structure

```
Demo/Real Vote Models have 60 candidate columns:
selected_candidate_1
selected_candidate_2
...
selected_candidate_60

This allows voting for up to 60 different positions in one election.

Usage:
$vote->selected_candidate_1 = 5;  // Vote for candidate ID 5
$vote->selected_candidate_2 = 8;  // Vote for candidate ID 8
```

---

## Demo vs Real Elections

### Key Differences

| Aspect | Demo | Real |
|--------|------|------|
| **Table** | `demo_votes` | `votes` |
| **Data Isolation** | Complete | Separate |
| **Reset Capability** | Can reset anytime | Never reset |
| **Voter Checks** | Minimal | Strict |
| **Access** | `/election/demo/start` | Login flow |
| **Purpose** | Testing | Official |
| **Audit Trail** | Optional | Mandatory |
| **Public Results** | Can publish | Can publish |

### Demo Election Workflow

```
1. ANY authenticated user can access demo
   ↓
2. Navigate to /election/demo/start
   ↓
3. Enter verification code (generated)
   ↓
4. Vote recorded in demo_votes table
   ↓
5. Demo data can be reset/cleared anytime
   ↓
6. No impact on real election data
```

**Code:**

```php
// Demo access - no eligibility checks
public function startDemo()
{
    $demo = Election::where('type', 'demo')
        ->where('is_active', true)
        ->first();

    if (!$demo) {
        return redirect()->route('dashboard')
            ->with('error', 'Demo not available');
    }

    // Bypass all checks - go straight to code entry
    return redirect()->route('code.create',
        ['vslug' => $demo->slug]);
}
```

### Switching Between Demo and Real

```
// In ElectionController::dashboard()

// Check which election to show
$realElection = Election::where('type', 'real')
    ->where('is_active', true)
    ->first();

$demoElection = Election::where('type', 'demo')
    ->where('is_active', true)
    ->first();

// Real election gets priority (if active)
if ($realElection && $realElection->isCurrentlyActive()) {
    // Show real election voting page
}

// Otherwise fall back to dashboard
// (which may offer demo link)
```

---

## API Endpoints & Routes

### Web Routes (Inertia)

```
GET  /                          → dashboard
GET  /dashboard                 → dashboard
POST /login                     → process login
GET  /logout                    → process logout
GET  /pricing                   → pricing page
GET  /voting                    → voting.start
GET  /voting/election           → voting.election
```

### Election Routes

```
GET  /election/select           → show election selector (multiple elections)
GET  /election/demo/start       → start demo election
```

### Voting Routes

```
GET  /code/create/{slug}        → show code entry form
POST /code/verify               → verify code
GET  /vote/create/{slug}        → show voting form
POST /vote                      → record vote
GET  /vote/success              → success page
GET  /vote/verify               → verification page
POST /vote/verify-lookup        → lookup vote by code
```

### API Endpoints (JSON)

```
For mobile/external integrations:

POST /api/v1/auth/login         → login (returns token)
GET  /api/v1/elections          → list active elections
POST /api/v1/codes              → generate verification code
POST /api/v1/votes              → record vote
GET  /api/v1/votes/{code}       → verify vote by code
```

### Route Structure (web.php)

```php
// Home/dashboard routes
Route::get('/', [ElectionController::class, 'dashboard'])
    ->name('electiondashboard');

Route::get('/dashboard', [ElectionController::class, 'dashboard'])
    ->name('dashboard');

// Election routes
Route::get('/election/select', [ElectionController::class, 'selectElection'])
    ->middleware('auth')
    ->name('election.select');

Route::get('/election/demo/start', [ElectionController::class, 'startDemo'])
    ->middleware('auth')
    ->name('election.demo.start');

// Voting routes
Route::get('/code/create/{vslug}', [CodeController::class, 'create'])
    ->name('code.create');

Route::post('/vote', [VoteController::class, 'store'])
    ->name('vote.store');

Route::get('/vote/verify', [VoteController::class, 'showVerify'])
    ->name('vote.verify');
```

---

## Election Lifecycle

### State Transitions

```
┌──────────┐
│ CREATED  │ Election created in database
└────┬─────┘
     │ Admin configures election
     ↓
┌──────────────┐
│ CONFIGURED   │ Candidates added, voting rules set
└────┬─────────┘
     │ Admin activates election
     ↓
┌──────────────┐
│ ACTIVE       │ is_active = true
└────┬─────────┘
     │ Start date reached
     ↓
┌──────────────┐
│ VOTING OPEN  │ isCurrentlyActive() = true
└────┬─────────┘
     │ (Voting happens here)
     │
     │ End date reached
     ↓
┌──────────────┐
│ VOTING CLOSED│ isCurrentlyActive() = false
└────┬─────────┘
     │ Admin publishes results
     ↓
┌──────────────┐
│ RESULTS      │ Results public
└──────────────┘
```

### Key Timestamps

```php
Election {
    start_date:   when voting begins (isCurrentlyActive() checks this)
    end_date:     when voting ends
    created_at:   when record created
    updated_at:   when record last modified
}

Code {
    voting_started_at: when user started voting (used for timeout)
    created_at: when code generated
}

Vote {
    created_at: when vote recorded
    updated_at: when vote updated (usually not updated after creation)
}
```

### Election Status Checks

```php
// Election exists?
$election = Election::find($id);

// Election is marked active?
$election->is_active === true

// Election is currently in voting period?
$election->isCurrentlyActive()
  → Checks: is_active && now >= start_date && now <= end_date

// User eligible to vote?
$user->is_voter && $code->can_vote_now

// Already voted?
$code->has_voted === true

// All conditions met?
$can_vote = $election->isCurrentlyActive()
    && $user->is_voter
    && !$code->has_voted;
```

---

## Security Architecture

### 1. Authentication

```
Secure Mechanisms:
├─ Fortify: Laravel's built-in authentication
├─ Sanctum: For API tokens (mobile apps)
├─ Session: For web browser storage
├─ CSRF: Protected all POST requests
└─ Rate Limiting: Prevent brute force

Implementation:
  - Password hashing: bcrypt (Laravel default)
  - Session timeout: Configurable
  - Token expiration: Sanctum handles
```

### 2. Authorization

```
Role-Based Access Control:
├─ admin: All system access
├─ election_officer: Election management
├─ voter: Voting only
└─ user: Basic access

Permission-Based:
├─ create-elections
├─ view-results
├─ manage-candidates
└─ vote

Middleware checks access at controller level
```

### 3. Vote Anonymity

```
✅ Protected:
- No user_id stored in votes table
- Votes identified only by verification_code
- Code→User mapping in Code table (separate)
- Voter cannot be identified from vote record

🔒 Security:
- verification_code is cryptographic (hex string)
- voting_code_hash for additional security
- No plain-text codes in votes table
```

### 4. Code Security

```php
// Generate secure code
$code = bin2hex(random_bytes(16));  // 32-char hex string

// Store hash, not plain text
$voting_code_hash = hash('sha256', $code);

// Verify by hash
if (hash('sha256', $userInput) === $stored_hash) {
    // Valid
}
```

### 5. CSRF Protection

```
All POST/PUT/DELETE requests protected:
├─ POST /vote         - CSRF token required
├─ POST /code/verify  - CSRF token required
├─ POST /auth/logout  - CSRF token required
└─ All admin endpoints - CSRF token required

Implementation:
VerifyCsrfToken middleware in Kernel.php
```

### 6. Rate Limiting

```php
// app/Http/Kernel.php
protected $middlewareGroups = [
    'api' => [
        'throttle:60,1',  // 60 requests per minute
    ],
];

// RateLimiter in RouteServiceProvider.php
RateLimiter::for('login', function (Request $request) {
    return Limit::perMinute(5)  // 5 attempts per minute
        ->by($request->email . $request->ip());
});
```

### 7. Data Validation

```php
// Incoming requests validated with FormRequests
class StoreVoteRequest extends FormRequest
{
    public function rules()
    {
        return [
            'code' => 'required|string|exists:codes',
            'selected_candidate_1' => 'required|exists:candidacies,id',
            'selected_candidate_2' => 'required|exists:candidacies,id',
            // Etc.
        ];
    }
}
```

### 8. SQL Injection Protection

```
✅ Protected (using Eloquent ORM):
$vote = Vote::where('verification_code', $code)->first();

❌ Vulnerable (raw SQL):
$vote = DB::select("SELECT * FROM votes WHERE code = '$code'");
```

### 9. XSS Protection

```php
// Blade templates automatically escape
{{ $user->name }}  // Escaped

// Force escaping
{!! $html !!}      // Only for trusted HTML

// Vue components escape by default
{{ user.name }}    // Escaped in Vue
v-html="html"      // Only for trusted HTML
```

### 10. File Upload Security

```
Images stored:
├─ Not in web root (outside public/)
├─ With hashed filenames
├─ With type checking (image/jpeg, image/png)
├─ Size limits enforced
└─ Virus scanning recommended
```

---

## Error Handling

### Exception Hierarchy

```
Exception
├─ ValidationException
│  ├─ ElectionNotActiveException
│  ├─ InvalidVotingCodeException
│  └─ AlreadyVotedException
│
├─ AuthorizationException
│  ├─ UnauthorizedVoterException
│  └─ NotEligibleToVoteException
│
└─ ResourceNotFoundException
   ├─ ElectionNotFoundException
   ├─ CodeNotFoundException
   └─ VoteNotFoundException
```

### Error Responses

```php
// HTTP errors
abort(404, 'Vote not found');
abort(403, 'Not authorized to vote');
abort(422, 'Invalid voting code');

// Custom exceptions
throw new AlreadyVotedException(
    "User has already voted in this election"
);

// Validation failures
throw ValidationException::withMessages([
    'code' => 'Invalid verification code',
]);
```

### Logging

```php
// Log important events
Log::info('Vote recorded', [
    'election_id' => $vote->election_id,
    'verification_code' => $vote->verification_code,
    'timestamp' => now(),
]);

Log::warning('Multiple vote attempt', [
    'user_id' => $user->user_id,
    'election_id' => $election->id,
    'ip' => request()->ip(),
]);

Log::error('Code generation failed', [
    'election_id' => $election->id,
    'exception' => $e->getMessage(),
]);
```

---

## Testing Strategy

### Test Files

```
tests/
├── Feature/
│   ├── SingleElectionLoginFlowTest.php
│   ├── VotingFlowTest.php
│   ├── CodeGenerationTest.php
│   └── VoteVerificationTest.php
│
└── Unit/
    ├── ElectionTest.php
    ├── CodeTest.php
    ├── VoteTest.php
    └── ElectionServiceTest.php
```

### TDD Approach

```
For every feature:

1. RED: Write failing test
   ✓ Test describes desired behavior
   ✓ Test fails (feature not implemented)

2. GREEN: Write minimal code
   ✓ Code makes test pass
   ✓ Don't over-engineer

3. REFACTOR: Clean up code
   ✓ Keep tests green
   ✓ Improve quality

Example:
---
Test: test_eligible_voter_sees_election_page()
  1. Create voter with is_voter=true
  2. Create active election
  3. GET /dashboard
  4. Assert: ElectionPage rendered
  5. Assert: activeElection data passed

Implementation:
  public function dashboard()
  {
      if ($this->isVotingAvailable()) {
          return Inertia::render('Election/ElectionPage', [
              'activeElection' => $election,
          ]);
      }
  }
---
```

### Test Data Setup

```php
// Use DatabaseSeeder for test data
$this->seed(ElectionSeeder::class);
$this->seed(DatabaseSeeder::class);

// Create specific test users
$voter = User::factory()
    ->state(['is_voter' => true])
    ->create();

// Create elections
$election = Election::factory()
    ->state([
        'type' => 'real',
        'is_active' => true,
        'start_date' => now()->subDay(),
        'end_date' => now()->addDay(),
    ])
    ->create();
```

---

## Common Tasks

### Add a New Election

```php
// 1. Create election
$election = Election::create([
    'name' => 'Q2 Election',
    'slug' => 'q2-election',
    'type' => 'real',
    'description' => 'Second quarter election',
    'is_active' => false,  // Will activate later
    'start_date' => '2026-03-01 00:00:00',
    'end_date' => '2026-03-15 23:59:59',
]);

// 2. Add candidates (via CandidacyController)
foreach ($candidates as $candidate) {
    Candidacy::create([
        'election_id' => $election->id,
        'user_id' => $candidate['user_id'],
        'candidacy_id' => $candidate['candidacy_id'],
        'post_id' => $candidate['post_id'],
    ]);
}

// 3. Activate election
$election->update(['is_active' => true]);

// Election now available for voting (during time window)
```

### Check Election Status

```php
$election = Election::find($id);

echo "Active: " . ($election->is_active ? 'Yes' : 'No');
echo "Currently voting: " . ($election->isCurrentlyActive() ? 'Yes' : 'No');
echo "Type: " . ($election->isDemo() ? 'Demo' : 'Real');
echo "Votes cast: " . $election->totalVotesCast();
echo "Turnout: " . $election->voterTurnout() . "%";

// Get full statistics
$stats = $election->getStatistics();
```

### Generate Voting Code

```php
$code = bin2hex(random_bytes(16));  // 32-char hex

Code::create([
    'user_id' => $user->user_id,
    'election_id' => $election->id,
    'code' => $code,
    'voting_code_hash' => hash('sha256', $code),
    'can_vote_now' => true,
]);

// Send to user (email/SMS)
SendVerificationCode::dispatch($user, $code);
```

### Record a Vote

```php
// 1. Validate code
$code = Code::where('code', $userCode)
    ->where('user_id', $user->user_id)
    ->first();

// 2. Create vote (anonymous - no user_id)
$vote = Vote::create([
    'election_id' => $code->election_id,
    'verification_code' => $code->code,
    'selected_candidate_1' => $candidacy1,
    'selected_candidate_2' => $candidacy2,
]);

// 3. Update tracking
$code->update(['has_voted' => true]);
$user->update(['has_voted' => true]);
```

### Retrieve Vote by Code

```php
$code = '5b38f3329b812a79902bcda506bb79b9';

$vote = Vote::where('verification_code', $code)->first();

if (!$vote) {
    // Vote not found
    abort(404);
}

// Display vote
$candidates = [
    'President' => Candidacy::find($vote->selected_candidate_1),
    'VP' => Candidacy::find($vote->selected_candidate_2),
];
```

### Update User Voter Status

```php
$user = User::find($userId);

// Mark as voter
DB::table('users')
    ->where('id', $userId)
    ->update(['is_voter' => true, 'can_vote' => true]);

// Approve specific user for voting
$user->approvedBy = 'admin_001';
$user->save();

// Suspend voter (prevent voting)
DB::table('users')
    ->where('id', $userId)
    ->update([
        'can_vote' => false,
        'suspendedBy' => 'admin_001',
        'suspended_at' => now(),
    ]);
```

### Calculate Results

```php
$election = Election::find($electionId);

foreach ($election->candidacies as $candidacy) {
    $voteCount = Vote::where('election_id', $election->id)
        ->where('selected_candidate_1', $candidacy->id)
        ->orWhere('selected_candidate_2', $candidacy->id)
        ->count();

    Result::create([
        'election_id' => $election->id,
        'candidacy_id' => $candidacy->id,
        'vote_count' => $voteCount,
    ]);
}
```

---

## Troubleshooting

### "Vote not found" Error

**Cause:** Invalid verification code or vote not recorded

**Solution:**
```php
// 1. Check vote exists
$vote = Vote::where('verification_code', $code)->first();
if (!$vote) {
    Log::warning("Vote lookup failed", ['code' => $code]);
    // Regenerate code or check database
}

// 2. Check code is hashed correctly
$correctHash = hash('sha256', $userInput);
if ($correctHash !== $stored_hash) {
    // Code is wrong
}

// 3. Verify vote was actually recorded
DB::table('votes')->where('verification_code', $code)->first();
```

### "Not eligible to vote" Error

**Cause:** One of eligibility checks failed

**Solution:**
```php
// Check each condition
$code = Code::where('code', $userCode)->first();

echo "Code exists: " . ($code ? 'Yes' : 'No');
echo "can_vote_now: " . $code->can_vote_now;
echo "has_voted: " . $code->has_voted;
echo "Election active: " . $election->isCurrentlyActive();
echo "User is_voter: " . $user->is_voter;

// Fix the failing condition
if (!$code->can_vote_now) {
    // Code not approved yet
    $code->update(['can_vote_now' => true]);
}
```

### "Election not found" Error

**Cause:** Election slug doesn't exist

**Solution:**
```php
// Check election exists
$election = Election::where('slug', $slug)->first();
if (!$election) {
    Log::error("Election not found", ['slug' => $slug]);
}

// List available elections
Election::all(['slug', 'name', 'type', 'is_active'])->toJson();

// Verify spelling of slug
// Slugs are lowercase with hyphens: 'q2-election' not 'Q2 Election'
```

### "Voting window closed" Error

**Cause:** Current time is outside election start/end dates

**Solution:**
```php
$election = Election::find($id);

echo "Now: " . now();
echo "Start: " . $election->start_date;
echo "End: " . $election->end_date;

// Fix: Extend voting period
$election->update([
    'end_date' => now()->addDays(7)
]);

// Check isCurrentlyActive()
echo "Currently Active: " . $election->isCurrentlyActive();
```

### Votes Not Recorded

**Cause:** Database error or transaction rolled back

**Solution:**
```php
// Check votes table
DB::table('votes')->where('election_id', $election->id)->count();

// Enable query logging
DB::enableQueryLog();
// ... run vote recording code ...
foreach(DB::getQueryLog() as $query) {
    Log::info($query['query']);
}

// Check for validation errors
if ($validator->fails()) {
    Log::error($validator->errors());
}

// Verify foreign keys
// Make sure election_id actually exists in elections table
Election::where('id', $vote->election_id)->first();
```

### Code Expiration Issues

**Cause:** Code expires before voting completes

**Solution:**
```php
$code = Code::find($codeId);

echo "Started: " . $code->voting_started_at;
echo "Time allowed: " . $code->voting_time_in_minutes . " minutes";
echo "Time elapsed: " . now()->diffInMinutes($code->voting_started_at);
echo "Time remaining: " . $code->timeRemaining();

// Fix: Increase voting time
$code->update(['voting_time_in_minutes' => 45]);

// Or: Reset voting start time
$code->update(['voting_started_at' => now()]);
```

---

## Performance Considerations

### Database Optimization

```php
// ❌ SLOW: N+1 queries
$elections = Election::all();
foreach ($elections as $election) {
    echo $election->codes->count();  // Query per election!
}

// ✅ FAST: Eager loading
$elections = Election::with('codes')->get();
foreach ($elections as $election) {
    echo $elections->codes->count();  // Cached
}

// Index frequently queried columns
DB::table('codes')
    ->index('election_id')
    ->index('user_id')
    ->index('can_vote_now');

DB::table('votes')
    ->index('election_id')
    ->index('verification_code');
```

### Query Optimization

```php
// ❌ Fetches all columns
$votes = Vote::all();

// ✅ Only needed columns
$votes = Vote::select('id', 'election_id', 'selected_candidate_1')
    ->where('election_id', $id)
    ->get();

// ❌ Paginate without limit
$results = Election::where('type', 'real')->get();

// ✅ Paginate with limit
$results = Election::where('type', 'real')
    ->paginate(20);
```

### Caching

```php
// Cache election status
$election = Cache::remember('election:' . $id, 3600, function() {
    return Election::find($id);
});

// Cache statistics
$stats = Cache::remember('election:stats:' . $id, 600, function() {
    return $election->getStatistics();
});

// Invalidate cache on update
Event::listen(ElectionUpdated::class, function($event) {
    Cache::forget('election:' . $event->election->id);
    Cache::forget('election:stats:' . $event->election->id);
});
```

### API Response Optimization

```php
// Use resource classes for consistent formatting
return ElectionResource::collection($elections)
    ->with('meta', ['total' => $count]);

// Minimize data transfer
return $votes->select('id', 'verification_code', 'selected_candidate_1')
    ->where('election_id', $id)
    ->get();

// Compress responses
header('Content-Encoding: gzip');
```

---

## Future Enhancements

### Phase 2: Multi-Election Support

```
Current: 1 real + 1 demo
Future:  Multiple real elections simultaneously

Implementation:
├─ Re-enable SelectElection.vue (currently optional)
├─ Modify LoginResponse to show selector
├─ Update dashboard to handle multiple elections
└─ Add election switching logic
```

### Phase 3: Advanced Features

```
├─ Real-time result updates (WebSockets)
├─ Voter anonymization improvements
├─ Geographic distribution tracking
├─ Advanced reporting and analytics
├─ Multi-language UI expansion
└─ Mobile app integration
```

### Phase 4: Compliance & Auditing

```
├─ Blockchain vote verification
├─ Legal compliance modules
├─ Audit trail reports
├─ Regular expression auditing
├─ Certification badges
└─ GDPR compliance layer
```

---

## Development Workflow

### Setting Up Development Environment

```bash
# 1. Clone repository
git clone <repo-url>
cd nrna-eu

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Database setup
php artisan migrate
php artisan db:seed

# 5. Create test users
php artisan tinker
# [Create test users as shown in earlier sections]

# 6. Build frontend
npm run dev

# 7. Start server
php artisan serve
```

### Git Workflow

```bash
# Feature branch
git checkout -b feature/election-management
git add .
git commit -m "Add election management features"
git push origin feature/election-management

# Create PR for review
# After approval, merge to main
git checkout main
git pull
git merge feature/election-management
```

### Deployment

```bash
# Production deployment
php artisan migrate --force
php artisan db:seed --class=ElectionSeeder
php artisan cache:clear
php artisan config:cache
npm run build
```

---

## Conclusion

This developer guide provides comprehensive documentation of the single election architecture system. The key principles are:

1. **Simplicity:** One real + one demo election model
2. **Security:** Anonymous voting with code-based access
3. **Separation:** Demo and real data in separate tables
4. **Auditability:** Complete vote tracking without voter identification
5. **Extensibility:** Architecture supports future multi-election expansion

For questions or updates, refer to inline code documentation and test files.

**Last Updated:** 2026-02-04
**Version:** 1.0
**Maintainer:** Development Team
