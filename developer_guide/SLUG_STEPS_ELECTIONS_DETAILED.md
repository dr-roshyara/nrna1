# Detailed Implementation: Slug, Steps Table, Demo vs Real Elections

## Overview

This document explains the three core concepts:
1. **Voter Slugs** - URL-based anonymous voter identification
2. **Steps Table** - Persistent step tracking audit trail
3. **Demo vs Real Elections** - Physical separation of election types

---

## Part 1: Voter Slugs (URL-Based Anonymous Voting)

### What is a Voter Slug?

A **voter slug** is a randomly generated token that acts as an anonymous voter identifier in URLs.

**Purpose**: Allow voters to vote without logging in via a unique URL token

**Example URL**:
```
http://localhost:8000/v/t9xw8o_42FbkQeCwVANZDg3oY3qJOKW-5Dq/code/create
                           ↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑
                           This is the voter slug (unique token)
```

### Voter Slug Table Schema

```sql
CREATE TABLE voter_slugs (
    id BIGINT UNSIGNED PRIMARY KEY,
    user_id VARCHAR,                    -- Internal user reference
    slug VARCHAR UNIQUE,                -- The URL token (unique globally)
    election_id BIGINT UNSIGNED,        -- KEY CHANGE: NEW column
    expires_at TIMESTAMP,               -- URL expires after 30 min
    is_active BOOLEAN DEFAULT true,
    current_step INTEGER DEFAULT 0,
    step_meta JSON,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    INDEX(slug),
    INDEX(election_id),                 -- NEW: For filtering by election
    INDEX(expires_at),

    FOREIGN KEY(election_id) REFERENCES elections(id)  -- NEW
);
```

### Key Change: election_id Column

**Before**:
```php
voter_slugs had NO election_id
$voterSlug->election_id = NULL
// Could not determine which election voter was voting for
```

**After**:
```php
voter_slugs has election_id column
$voterSlug->election_id = 1  // Demo election
// or
$voterSlug->election_id = 2  // Real election
// Now can determine election context from slug alone
```

### VoterSlug Model

```php
// app/Models/VoterSlug.php

class VoterSlug extends Model
{
    protected $fillable = [
        'user_id',
        'slug',
        'election_id',      // NEW - Fill this when creating slug
        'expires_at',
        'is_active',
        'current_step',
        'step_meta',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'current_step' => 'integer',
        'step_meta' => 'array'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // NEW: Relationship to steps
    public function steps()
    {
        return $this->hasMany(VoterSlugStep::class, 'voter_slug_id');
    }

    public function scopeValid($query)
    {
        return $query->where('is_active', true)
                    ->where('expires_at', '>', now());
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return $this->is_active && !$this->isExpired();
    }
}
```

### Generating a Voter Slug

```php
// app/Services/VoterSlugService.php

class VoterSlugService
{
    public function generateSlugForUser($user, $electionId = null): VoterSlug
    {
        // If no election specified, use demo election
        if (!$electionId) {
            $election = Election::where('type', 'demo')->first();
            $electionId = $election ? $election->id : null;
        }

        // Generate random token
        $slug = $this->generateUniqueSlug();

        // Create voter slug with election_id
        return VoterSlug::create([
            'user_id' => $user->user_id,
            'slug' => $slug,
            'election_id' => $electionId,    // KEY: Set election context
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
        ]);
    }

    private function generateUniqueSlug(): string
    {
        do {
            // Generate random token like: "t9xw8o_42FbkQeCwVANZDg3oY3qJOKW-5Dq"
            $slug = Str::random(40);
        } while (VoterSlug::where('slug', $slug)->exists());

        return $slug;
    }
}
```

### Usage Example

```php
// Generate voter slug for demo election
$user = User::find(1);
$voterSlugService = new VoterSlugService();
$voterSlug = $voterSlugService->generateSlugForUser($user, $electionId = 1);

echo "Slug: " . $voterSlug->slug;                    // t9xw8o_42FbkQeCwVANZDg3oY3qJOKW-5Dq
echo "Election ID: " . $voterSlug->election_id;      // 1 (demo)
echo "Vote URL: /v/" . $voterSlug->slug . "/code/create";

// Voter visits URL: http://localhost:8000/v/t9xw8o_42FbkQeCwVANZDg3oY3qJOKW-5Dq/code/create
```

---

## Part 2: Voter Slug Steps Table

### What is the Steps Table?

The **voter_slug_steps** table is a persistent audit trail that records each step of the voting workflow.

**Purpose**: Track voter progress, enforce step order, provide audit trail

### Schema

```sql
CREATE TABLE voter_slug_steps (
    id BIGINT UNSIGNED PRIMARY KEY,
    voter_slug_id BIGINT UNSIGNED NOT NULL,
    slug VARCHAR NOT NULL,              -- Copy of slug for traceability
    election_id BIGINT UNSIGNED NOT NULL,
    step INTEGER(1-5) NOT NULL,         -- Which step (1-5)
    step_data JSON NULLABLE,            -- Step-specific metadata
    completed_at TIMESTAMP NOT NULL,    -- When step completed
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    -- Unique: Only one record per step per voter per election
    UNIQUE(voter_slug_id, election_id, step),

    -- Indexes for fast queries
    INDEX(voter_slug_id),
    INDEX(election_id),
    INDEX(step),
    INDEX(completed_at),

    -- Relationships
    FOREIGN KEY(voter_slug_id) REFERENCES voter_slugs(id),
    FOREIGN KEY(election_id) REFERENCES elections(id)
);
```

### Key Points

1. **Unique constraint**: `(voter_slug_id, election_id, step)`
   - Prevents duplicate step records
   - Only one Step 1, one Step 2, etc. per voter per election

2. **Slug copy**: Stored for traceability
   - Can identify voter even if voter_slugs.slug changes
   - Used for audit trail

3. **election_id**: Enforces election context
   - Separates demo and real election steps
   - Same voter can have steps in both elections

### Step Data Examples

```php
// Step 1: Code Verification
$step_data = [
    'code_verified' => true,
    'verified_at' => '2026-02-04T15:02:43Z',
    'ip_address' => '192.168.1.1'
];

// Step 2: Agreement Acceptance
$step_data = [
    'agreement_accepted' => true,
    'accepted_at' => '2026-02-04T15:06:55Z'
];

// Step 3: Vote Submission
$step_data = [
    'vote_submitted' => true,
    'submitted_at' => '2026-02-04T15:12:43Z',
    'posts_count' => 15,
    'posts_voted' => 14
];

// Step 4: Vote Verification
$step_data = [
    'vote_verified' => true,
    'verified_at' => '2026-02-04T15:14:22Z'
];

// Step 5: Final Submission
$step_data = [
    'vote_submitted_final' => true,
    'submitted_final_at' => '2026-02-04T15:15:52Z',
    'vote_id' => 12345
];
```

### VoterSlugStep Model

```php
// app/Models/VoterSlugStep.php

class VoterSlugStep extends Model
{
    use HasFactory;

    protected $table = 'voter_slug_steps';

    protected $fillable = [
        'voter_slug_id',
        'slug',
        'election_id',
        'step',
        'step_data',
        'completed_at',
    ];

    protected $casts = [
        'step_data' => 'array',
        'completed_at' => 'datetime',
    ];

    // Relationship: This step belongs to a voter slug
    public function voterSlug()
    {
        return $this->belongsTo(VoterSlug::class, 'voter_slug_id');
    }

    // Relationship: This step belongs to an election
    public function election()
    {
        return $this->belongsTo(Election::class, 'election_id');
    }

    // Scope: Get steps for a specific voter in an election
    public function scopeForVoterInElection($query, $voterSlugId, $electionId)
    {
        return $query->where('voter_slug_id', $voterSlugId)
                     ->where('election_id', $electionId);
    }

    // Scope: Get completed steps in order
    public function scopeOrdered($query)
    {
        return $query->orderBy('step', 'asc');
    }
}
```

### Recording Steps

```php
// app/Services/VoterStepTrackingService.php

class VoterStepTrackingService
{
    /**
     * Record step completion
     */
    public function completeStep(
        VoterSlug $voterSlug,
        Election $election,
        int $step,
        array $stepData = []
    ): VoterSlugStep {
        // Check if step already completed
        $existingStep = VoterSlugStep::where('voter_slug_id', $voterSlug->id)
            ->where('election_id', $election->id)
            ->where('step', $step)
            ->first();

        if ($existingStep) {
            // Step already recorded, return existing
            Log::info('Step already completed', [
                'step' => $step,
                'completed_at' => $existingStep->completed_at,
            ]);
            return $existingStep;
        }

        // Create new step record
        $voterSlugStep = VoterSlugStep::create([
            'voter_slug_id' => $voterSlug->id,
            'slug' => $voterSlug->slug,               // Copy for traceability
            'election_id' => $election->id,           // Election context
            'step' => $step,                          // Step number 1-5
            'step_data' => $stepData,                 // Metadata
            'completed_at' => now(),
        ]);

        Log::info("Step $step recorded", [
            'voter_slug_id' => $voterSlug->id,
            'election_id' => $election->id,
        ]);

        return $voterSlugStep;
    }

    /**
     * Get highest completed step (0 if none)
     */
    public function getHighestCompletedStep(
        VoterSlug $voterSlug,
        Election $election
    ): int {
        $highest = VoterSlugStep::where('voter_slug_id', $voterSlug->id)
            ->where('election_id', $election->id)
            ->max('step');

        return $highest ?? 0;
    }

    /**
     * Get next step to proceed to
     */
    public function getNextStep(
        VoterSlug $voterSlug,
        Election $election
    ): ?int {
        $highestCompleted = $this->getHighestCompletedStep($voterSlug, $election);
        $nextStep = $highestCompleted + 1;

        // Check if next step is valid (1-5)
        if ($nextStep <= 5) {
            return $nextStep;
        }

        return null; // All steps completed
    }

    /**
     * Get complete audit trail
     */
    public function getStepTimeline(
        VoterSlug $voterSlug,
        Election $election
    ): array {
        $steps = VoterSlugStep::where('voter_slug_id', $voterSlug->id)
            ->where('election_id', $election->id)
            ->ordered()
            ->get();

        $stepNames = config('election_steps');  // Route name mapping

        return $steps->map(function ($step) use ($stepNames) {
            return [
                'step' => $step->step,
                'step_name' => $stepNames[$step->step] ?? "Unknown",
                'completed_at' => $step->completed_at->toIso8601String(),
                'data' => $step->step_data,
            ];
        })->toArray();
    }
}
```

### Middleware: Enforcing Step Order

```php
// app/Http/Middleware/EnsureVoterStepOrder.php

class EnsureVoterStepOrder
{
    public function handle($request, Closure $next)
    {
        // Get voter slug from URL
        $voterSlug = $request->attributes->get('voter_slug');

        // Get election from voter_slug.election_id (not from request)
        $election = Election::find($voterSlug->election_id);

        if (!$election) {
            abort(404, 'Election not found');
        }

        // Initialize step tracker
        $stepTracker = new VoterStepTrackingService();

        // Get highest completed step
        $highestCompletedStep = $stepTracker->getHighestCompletedStep(
            $voterSlug,
            $election
        );

        // Calculate next allowed step
        $nextAllowedStep = $highestCompletedStep + 1;

        // Get requested step from route
        $requestedStep = $this->getRequestedStep($request);

        // Security: Cannot skip steps
        if ($requestedStep > $nextAllowedStep) {
            Log::warning('Step skipping attempt blocked', [
                'voter_slug_id' => $voterSlug->id,
                'highest_completed' => $highestCompletedStep,
                'requested_step' => $requestedStep,
                'next_allowed' => $nextAllowedStep,
            ]);
            abort(403, 'This step is not yet available');
        }

        // Store in request for controller use
        $request->attributes->put('election', $election);
        $request->attributes->put('next_allowed_step', $nextAllowedStep);

        return $next($request);
    }

    protected function getRequestedStep($request): int
    {
        $path = $request->path();

        if (str_contains($path, '/code/create')) return 1;
        if (str_contains($path, '/code/agreement')) return 2;
        if (str_contains($path, '/vote/create')) return 3;
        if (str_contains($path, '/vote/verify')) return 4;
        if (str_contains($path, '/vote/store')) return 5;

        return 0;
    }
}
```

---

## Part 3: Demo vs Real Elections

### Physical Separation Pattern

The system uses **physical table separation** rather than logical filtering:

```
REAL ELECTIONS (type='real')
├── elections (id=2, type='real')
├── candidacies (links to real elections)
├── votes (links to real elections)
└── results (links to real elections)

DEMO ELECTIONS (type='demo')
├── elections (id=1, type='demo')
├── demo_candidacies (separate table)
├── demo_votes (separate table)
└── demo_results (separate table)

SHARED (both elections use these)
├── voter_slugs (with election_id)
├── voter_slug_steps (with election_id)
├── posts (election positions)
├── codes (verification codes)
└── elections (store type='demo' or 'real')
```

### Elections Table

```sql
CREATE TABLE elections (
    id BIGINT UNSIGNED PRIMARY KEY,
    name VARCHAR,
    type VARCHAR,  -- 'demo' or 'real'
    description TEXT,
    status VARCHAR, -- 'active', 'closed', etc.
    start_date TIMESTAMP,
    end_date TIMESTAMP,
    voting_time_in_minutes INTEGER DEFAULT 30,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,

    INDEX(type),
    INDEX(status)
);

-- Sample data
INSERT INTO elections VALUES
(1, 'Demo Election', 'demo', 'For testing', 'active', ...),
(2, 'Student Union Election 2026', 'real', 'Actual voting', 'active', ...);
```

### Demo vs Real: Candidates

**Real Elections**:
```sql
-- candidacies table (real elections)
SELECT * FROM candidacies WHERE election_id = 2;

// Access in code
$candidates = Candidacy::where('election_id', 2)->get();
```

**Demo Elections**:
```sql
-- demo_candidacies table (demo elections only)
SELECT * FROM demo_candidacies WHERE election_id = 1;

// Access in code
$candidates = DemoCandidate::where('election_id', 1)->get();
```

### Demo vs Real: Votes

**Real Elections**:
```sql
-- votes table (real elections, anonymous)
SELECT * FROM votes WHERE election_id = 2;
-- Has: id, election_id, voting_code (hashed)
-- NO: user_id
```

**Demo Elections**:
```sql
-- demo_votes table (demo elections, anonymous)
SELECT * FROM demo_votes WHERE election_id = 1;
-- Has: id, election_id, voting_code (hashed)
-- NO: user_id
```

### Demo vs Real: Results

**Real Elections**:
```sql
-- results table (real elections)
SELECT * FROM results WHERE election_id = 2;
-- Stores: vote_id, post_id, candidacy_id
```

**Demo Elections**:
```sql
-- demo_results table (demo elections)
SELECT * FROM demo_results WHERE election_id = 1;
-- Stores: vote_id, post_id, candidacy_id
```

### Service Factory Pattern

```php
// app/Http/Controllers/VoteController.php

class VoteController
{
    public function getVotingService(Election $election)
    {
        if ($election->type === 'demo') {
            return new DemoVotingService($election);
        }

        return new VotingService($election);
    }

    public function save_vote($input_data, $hashed_voting_key, $election = null)
    {
        // Get appropriate service based on election type
        $votingService = $this->getVotingService($election);

        // Get model classes from service
        $voteModel = $votingService->getVoteModel();      // DemoVote or Vote
        $resultModel = $votingService->getResultModel();  // DemoResult or Result

        // Create vote using appropriate model
        $vote = new $voteModel;
        $vote->voting_code = $hashed_voting_key;
        $vote->election_id = $election->id;
        $vote->save();

        // Create results
        foreach ($selected_candidates as $candidate) {
            $result = new $resultModel;
            $result->vote_id = $vote->id;
            $result->election_id = $election->id;
            $result->candidacy_id = $candidate['candidacy_id'];
            $result->save();
        }
    }
}
```

### Base Services

```php
// app/Services/VotingService.php
class VotingService
{
    protected $election;

    public function __construct(Election $election)
    {
        $this->election = $election;
    }

    public function getVoteModel(): string
    {
        return Vote::class;  // Real elections use Vote model
    }

    public function getResultModel(): string
    {
        return Result::class;  // Real elections use Result model
    }
}

// app/Services/DemoVotingService.php
class DemoVotingService extends VotingService
{
    public function getVoteModel(): string
    {
        return DemoVote::class;  // Demo elections use DemoVote model
    }

    public function getResultModel(): string
    {
        return DemoResult::class;  // Demo elections use DemoResult model
    }

    /**
     * Demo-specific: Can reset all votes
     */
    public function reset(): array
    {
        $voteCount = $this->deleteAllVotes();
        $resultCount = DemoResult::where('election_id', $this->election->id)->delete();

        return [
            'votes_deleted' => $voteCount,
            'results_deleted' => $resultCount,
            'election_id' => $this->election->id,
            'cleared_at' => now(),
        ];
    }
}
```

### Demo Election: Skip Voter Registration

```php
// app/Http/Controllers/VoteController.php::verify_first_submission()

public function verify_first_submission(Request $request, &$code, $auth_user)
{
    $isDemoElection = $election && $election->type === 'demo';

    // Real elections: Check voter registration
    if (!$isDemoElection && $auth_user->is_voter != 1) {
        $errors['is_voter'] = 'You are not registered as a voter.';
    }

    if (!$isDemoElection && $auth_user->can_vote != 1) {
        $errors['can_vote'] = 'You are not eligible to vote.';
    }

    // Demo elections: Skip these checks
    // Voters can vote without registration in demo

    if (!empty($errors)) {
        return ['error_message' => 'Validation failed'];
    }

    // Continue with vote submission
    return ['error_message' => ''];
}
```

---

## Example Flow: Demo Election Voting

```
1. USER VISITS
   http://localhost:8000/v/t9xw8o_42FbkQeCwVANZDg3oY3qJOKW-5Dq/code/create

2. MIDDLEWARE (EnsureVoterStepOrder)
   - Gets voter_slug with slug = "t9xw8o_42FbkQeCwVANZDg3oY3qJOKW-5Dq"
   - Finds election_id = 1 (demo)
   - Queries voter_slug_steps for highest completed step = 0
   - Next allowed step = 1
   - Requested step = 1 (code/create)
   - ✅ Access allowed

3. CONTROLLER (CodeController::store)
   - Receives $voterSlug (id=1, election_id=1, slug="t9x...")
   - Receives $election (id=1, type='demo')
   - Validates code from email
   - Records Step 1:
     ```
     VoterSlugStep::create([
         'voter_slug_id' => 1,
         'election_id' => 1,
         'step' => 1,
         'step_data' => ['code_verified' => true],
         'completed_at' => now()
     ])
     ```
   - Redirects to agreement page (Step 2)

4. CONTINUED... Step 2-5 same pattern

5. FINAL SUBMISSION (Step 5)
   - Gets DemoVotingService (because election->type = 'demo')
   - Gets DemoVote model class (not Vote)
   - Saves to demo_votes table:
     ```
     INSERT INTO demo_votes (election_id, voting_code, candidate_01, ...)
     VALUES (1, '$2y$10$...hashed...', '...json...', ...)
     ```
   - Saves results to demo_results:
     ```
     INSERT INTO demo_results (vote_id, election_id, candidacy_id, ...)
     VALUES (12, 1, 'cand_001', ...)
     ```
   - Records Step 5
   - Redirects to thank you page

6. AUDIT TRAIL
   Query voter_slug_steps:
   ```
   SELECT * FROM voter_slug_steps
   WHERE voter_slug_id=1 AND election_id=1
   ORDER BY step;

   // Returns 5 rows (Steps 1-5) with timestamps
   ```
```

---

## Summary: How They Work Together

| Component | Purpose | Election Context |
|-----------|---------|------------------|
| **voter_slug** | URL token for anonymous access | Links to specific election via election_id |
| **voter_slug_steps** | Persistent step tracking | Records steps per (voter, election, step) |
| **election (type)** | Determines which service/models to use | 'demo' uses Demo*, 'real' uses real tables |
| **Middleware** | Enforces step progression | Gets election from voter_slug.election_id |
| **Services** | Handle election type logic | DemoVotingService vs VotingService |

**Key Insight**: The `election_id` column on `voter_slugs` is the linchpin that ties everything together, allowing the system to determine which election a voter is voting in directly from the slug.

---

Last Updated: **2026-02-04**
