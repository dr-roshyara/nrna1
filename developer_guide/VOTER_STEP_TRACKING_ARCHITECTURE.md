# Voter Step Tracking Architecture - Developer Guide

**Document Version:** 1.0
**Date:** 2026-02-04
**Status:** Active Production

---

## Table of Contents

1. [Overview](#overview)
2. [Problem Statement](#problem-statement)
3. [Architecture](#architecture)
4. [Database Schema](#database-schema)
5. [Core Components](#core-components)
6. [Step Flow](#step-flow)
7. [Implementation Guide](#implementation-guide)
8. [API Reference](#api-reference)
9. [Testing Guide](#testing-guide)
10. [Troubleshooting](#troubleshooting)
11. [Best Practices](#best-practices)

---

## Overview

The **Voter Step Tracking System** is a persistent, audit-logged approach to managing voter progress through the voting workflow in a multi-election environment.

### Key Principles

✅ **Single Source of Truth**: The `voter_slug_steps` table is the authoritative record of completed steps
✅ **Audit Trail**: Every step completion is timestamped and data-rich
✅ **No State Sync Issues**: No fragile step calculations; just query the database
✅ **Multi-Election Support**: Each voter's progress is scoped per election
✅ **Idempotent Operations**: Completing a step twice is safe

---

## Problem Statement

### Legacy Issues (Before Implementation)

The old system had critical problems:

```
❌ VoterSlug.current_step could get out of sync
❌ VoterProgressService.advanceFrom() relied on fragile route config mapping
❌ No audit trail of when steps were completed
❌ Middleware redirected based on guesses, not facts
❌ Step data (e.g., "agreement accepted at X time") was lost
❌ Multi-election voters had step tracking conflicts
```

### Example of Old Problem

```php
// OLD: After code verification, user redirected back to code page
// Because advanceFrom() failed silently, current_step never updated
User verifies code → advanceFrom() called → current_step should = 2
But if config route name was wrong, current_step stayed = 1
Middleware saw: target=1, current=1 → allowed
User stuck in loop ❌
```

### New System Solution

```php
// NEW: Clear audit trail
User verifies code
  ↓
INSERT INTO voter_slug_steps (voter_slug_id, election_id, step=1, completed_at=NOW)
  ↓
SELECT MAX(step) FROM voter_slug_steps → returns 1 ✅
  ↓
Middleware calculates: next_allowed = 1 + 1 = 2
  ↓
User tries to access step 1 page → allowed (back navigation ok)
User tries to access step 2 page → allowed (next step)
User tries to access step 3 page → REDIRECT to step 2 ✅
```

---

## Architecture

### High-Level Flow

```
┌─────────────────────────────────────────────────────────────┐
│  USER VOTING JOURNEY (5 Steps)                              │
└─────────────────────────────────────────────────────────────┘

Step 1: Code Entry
  ↓ CodeController.store()
  ├─ Verify code
  ├─ INSERT voter_slug_steps (step=1, completed_at=NOW)
  ├─ Log event
  └─ Redirect to Step 2 ✅

Step 2: Agreement
  ↓ CodeController.storeAgreement()
  ├─ Accept terms
  ├─ INSERT voter_slug_steps (step=2, completed_at=NOW)
  ├─ Log event
  └─ Redirect to Step 3 ✅

Step 3: Vote Creation
  ↓ VoteController.create()
  ├─ Display candidates
  └─ [User selects candidates]

Step 4: Vote Verification
  ↓ VoteController.verify()
  ├─ INSERT voter_slug_steps (step=4, completed_at=NOW)
  └─ Show vote summary

Step 5: Completion
  ↓ VoteController.complete()
  ├─ Save vote
  ├─ INSERT voter_slug_steps (step=5, completed_at=NOW)
  └─ Show receipt

┌─────────────────────────────────────────────────────────────┐
│  MIDDLEWARE: ROUTE PROTECTION (on every request)            │
└─────────────────────────────────────────────────────────────┘

EnsureVoterStepOrder Middleware
  ↓
1. Get target_step from route config
2. Query: SELECT MAX(step) FROM voter_slug_steps
3. Calculate: next_allowed = highest_completed + 1
4. Check: is target_step <= next_allowed?
   ├─ YES → Allow access ✅
   └─ NO → Redirect to next_allowed step 🔄
```

---

## Database Schema

### Table: `voter_slug_steps`

```sql
CREATE TABLE voter_slug_steps (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    voter_slug_id BIGINT NOT NULL,          -- FK to voter_slugs
    election_id BIGINT NOT NULL,             -- FK to elections
    step TINYINT NOT NULL (1-5),            -- Step number
    step_data JSON NULL,                     -- Step-specific metadata
    completed_at TIMESTAMP,                  -- When step was completed
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    -- Foreign Keys
    FOREIGN KEY (voter_slug_id) REFERENCES voter_slugs(id) ON DELETE CASCADE,
    FOREIGN KEY (election_id) REFERENCES elections(id) ON DELETE CASCADE,

    -- Indexes
    UNIQUE KEY unique_step_per_voter (voter_slug_id, election_id, step),
    INDEX idx_voter_election (voter_slug_id, election_id),
    INDEX idx_election_completed (election_id, completed_at)
);
```

### Step Data Examples

```json
// Step 1: Code Verification
{
  "code_verified": true,
  "verified_at": "2026-02-04T14:35:17Z"
}

// Step 2: Agreement
{
  "agreement_accepted": true,
  "accepted_at": "2026-02-04T14:36:22Z",
  "agreement_version": "v1.0"
}

// Step 4: Vote Verification
{
  "candidates_selected": 5,
  "posts_voted": ["president", "vice_president", "secretary"],
  "verified_at": "2026-02-04T14:38:45Z"
}

// Step 5: Completion
{
  "vote_saved": true,
  "vote_id": 12345,
  "completed_at": "2026-02-04T14:39:12Z",
  "receipt_id": "RECEIPT-2026-0001"
}
```

---

## Core Components

### 1. Model: `VoterSlugStep`

**File:** `app/Models/VoterSlugStep.php`

```php
class VoterSlugStep extends Model
{
    protected $fillable = [
        'voter_slug_id', 'election_id', 'step', 'step_data', 'completed_at'
    ];

    protected $casts = [
        'step_data' => 'array',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function voterSlug()
    public function election()

    // Scopes
    public function scopeForVoterInElection($query, $voterSlugId, $electionId)
    public function scopeOrdered($query)
}
```

### 2. Service: `VoterStepTrackingService`

**File:** `app/Services/VoterStepTrackingService.php`

Core responsibility: Query and update step data.

#### Key Methods

```php
// Complete a step
public function completeStep(
    VoterSlug $voterSlug,
    Election $election,
    int $step,
    array $stepData = []
): VoterSlugStep

// Get highest completed step (0 if none)
public function getHighestCompletedStep(
    VoterSlug $voterSlug,
    Election $election
): int

// Get next step to proceed to (null if all complete)
public function getNextStep(
    VoterSlug $voterSlug,
    Election $election
): ?int

// Check if specific step completed
public function hasCompletedStep(
    VoterSlug $voterSlug,
    Election $election,
    int $step
): bool

// Get all completed steps with data
public function getCompletedSteps(
    VoterSlug $voterSlug,
    Election $election
): Collection

// Get next route name (from config/election_steps.php)
public function getNextStepRoute(
    VoterSlug $voterSlug,
    Election $election
): ?string

// Get timeline for audit
public function getStepTimeline(
    VoterSlug $voterSlug,
    Election $election
): array
```

### 3. Middleware: `EnsureVoterStepOrder`

**File:** `app/Http/Middleware/EnsureVoterStepOrder.php`

Protects routes by ensuring users can only access their current or completed steps.

```php
public function handle(Request $request, Closure $next): Response
{
    // 1. Determine target step from route name
    $targetStep = array_search($routeName, config('election_steps'), true);

    // 2. Query actual progress
    $stepTracker = new VoterStepTrackingService();
    $highestCompleted = $stepTracker->getHighestCompletedStep($voterSlug, $election);
    $nextAllowed = $highestCompleted + 1;

    // 3. Enforce access control
    if ($targetStep > $nextAllowed) {
        return redirect()->route($map[$nextAllowed], ['vslug' => $voterSlug->slug]);
    }

    return $next($request);
}
```

### 4. Config: `config/election_steps.php`

Maps steps to routes.

```php
return [
    1 => 'slug.code.create',      // Enter code
    2 => 'slug.code.agreement',   // Accept agreement
    3 => 'slug.vote.create',      // Select candidates
    4 => 'slug.vote.verify',      // Review votes
    5 => 'slug.vote.complete',    // Completion receipt
];
```

---

## Step Flow

### Complete Step-by-Step Example

**Scenario:** User votes in demo election

```
┌── REQUEST: GET /v/ABC123/code/create ──────────────────────┐
│                                                              │
│ EnsureVoterStepOrder Middleware:                            │
│  ├─ target_step = 1 (from route)                           │
│  ├─ highest_completed = 0 (new voter)                      │
│  ├─ next_allowed = 1                                       │
│  ├─ Check: 1 <= 1? YES → Allow ✅                          │
│  └─ Continue to controller                                 │
│                                                              │
│ CodeController.create():                                    │
│  ├─ Display code entry form                                │
│  └─ Return Inertia render                                  │
└─────────────────────────────────────────────────────────────┘

┌── REQUEST: POST /v/ABC123/code (submit code) ──────────────┐
│                                                              │
│ CodeController.store():                                     │
│  ├─ Validate code = "ABC123"                               │
│  ├─ Mark code as verified                                  │
│  ├─ Call VoterStepTrackingService.completeStep():          │
│  │   ├─ INSERT voter_slug_steps (                          │
│  │   │   voter_slug_id=5,                                  │
│  │   │   election_id=1,                                    │
│  │   │   step=1,                                           │
│  │   │   step_data={"code_verified": true},               │
│  │   │   completed_at=NOW()                               │
│  │   │ )                                                   │
│  │   └─ Log: "✅ Step 1 recorded"                          │
│  └─ Return: redirect('/v/ABC123/vote/agreement')          │
└─────────────────────────────────────────────────────────────┘

┌── REQUEST: GET /v/ABC123/vote/agreement ───────────────────┐
│                                                              │
│ EnsureVoterStepOrder Middleware:                            │
│  ├─ target_step = 2 (from route)                           │
│  ├─ Query: SELECT MAX(step) FROM voter_slug_steps           │
│  │   WHERE voter_slug_id=5 AND election_id=1              │
│  │   → Result: 1 ✅                                        │
│  ├─ highest_completed = 1                                  │
│  ├─ next_allowed = 2                                       │
│  ├─ Check: 2 <= 2? YES → Allow ✅                          │
│  └─ Continue to controller                                 │
│                                                              │
│ CodeController.showAgreement():                             │
│  ├─ Display agreement form                                 │
│  └─ Return Inertia render                                  │
└─────────────────────────────────────────────────────────────┘

┌── REQUEST: POST /v/ABC123/vote/agreement (accept) ─────────┐
│                                                              │
│ CodeController.storeAgreement():                            │
│  ├─ Validate agreement checkbox checked                    │
│  ├─ Mark code as agreement accepted                        │
│  ├─ Call VoterStepTrackingService.completeStep():          │
│  │   ├─ INSERT voter_slug_steps (step=2, ...)            │
│  │   └─ Log: "✅ Step 2 recorded"                          │
│  └─ Return: redirect('/v/ABC123/vote/create')             │
└─────────────────────────────────────────────────────────────┘

┌── REQUEST: GET /v/ABC123/vote/create ──────────────────────┐
│                                                              │
│ EnsureVoterStepOrder Middleware:                            │
│  ├─ target_step = 3                                        │
│  ├─ Query highest_completed → 2 ✅                         │
│  ├─ next_allowed = 3                                       │
│  ├─ Check: 3 <= 3? YES → Allow ✅                          │
│  └─ Continue to controller                                 │
│                                                              │
│ VoteController.create():                                    │
│  ├─ Fetch demo candidates                                  │
│  └─ Display ballot form                                    │
└─────────────────────────────────────────────────────────────┘

... [User selects candidates] ...

┌── REQUEST: POST /v/ABC123/vote (submit selections) ────────┐
│                                                              │
│ VoteController.first_submission():                          │
│  ├─ Validate selections                                    │
│  ├─ Store in session                                       │
│  ├─ Call VoterStepTrackingService.completeStep():          │
│  │   └─ INSERT voter_slug_steps (step=3, ...)            │
│  └─ Redirect to verification page                          │
└─────────────────────────────────────────────────────────────┘

... [Verification and completion flow continues with steps 4 & 5] ...
```

---

## Implementation Guide

### How to Add a New Step (if needed in future)

**Example:** Adding "Step 6: Feedback Survey"

#### 1. Update Config

**File:** `config/election_steps.php`

```php
return [
    1 => 'slug.code.create',
    2 => 'slug.code.agreement',
    3 => 'slug.vote.create',
    4 => 'slug.vote.verify',
    5 => 'slug.vote.complete',
    6 => 'slug.vote.feedback',    // ← NEW
];
```

#### 2. Create Route

**File:** `routes/election/voting.php` (or relevant file)

```php
Route::get('vote/feedback', [VoteController::class, 'showFeedback'])
    ->name('slug.vote.feedback');

Route::post('vote/feedback', [VoteController::class, 'storeFeedback'])
    ->name('slug.vote.feedback.store');
```

#### 3. Implement Controller Method

**File:** `app/Http/Controllers/VoteController.php`

```php
public function showFeedback(Request $request)
{
    $user = $this->getUser($request);
    $election = $this->getElection($request);
    $voterSlug = $request->attributes->get('voter_slug');

    return Inertia::render('Vote/Feedback', [
        'user_name' => $user->name,
        'election' => $election,
    ]);
}

public function storeFeedback(Request $request)
{
    $user = $this->getUser($request);
    $election = $this->getElection($request);
    $voterSlug = $request->attributes->get('voter_slug');

    // Validate feedback
    $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:500',
    ]);

    // ✅ Record step completion
    $stepTracker = new VoterStepTrackingService();
    $stepTracker->completeStep(
        $voterSlug,
        $election,
        6, // Step 6
        [
            'rating' => $request->rating,
            'comment' => $request->comment,
            'submitted_at' => now()->toIso8601String(),
        ]
    );

    return redirect()->route('dashboard')
        ->with('success', 'Thank you for voting and your feedback!');
}
```

#### 4. Middleware Automatically Protects

The `EnsureVoterStepOrder` middleware automatically protects step 6:

```php
// User can only access step 6 if they completed step 5
// No additional code needed! ✅
```

---

## API Reference

### VoterStepTrackingService

Complete reference for using the service.

#### `completeStep()`

Records when a voter completes a step.

```php
$stepTracker = new VoterStepTrackingService();

$stepTracker->completeStep(
    voterSlug: VoterSlug,      // Required
    election: Election,         // Required
    step: int,                  // Required (1-5)
    stepData: array = []        // Optional (metadata)
): VoterSlugStep

// Example
$stepTracker->completeStep(
    $voterSlug,
    $election,
    1,
    ['code_verified' => true, 'verified_at' => now()]
);
```

**Notes:**
- Idempotent: calling twice returns the first record
- Logs automatically
- Returns the VoterSlugStep model

#### `getHighestCompletedStep()`

Query the highest completed step.

```php
$highest = $stepTracker->getHighestCompletedStep(
    voterSlug: VoterSlug,
    election: Election
): int

// Examples
$highest = $stepTracker->getHighestCompletedStep($voterSlug, $election);
// Returns: 0 (no steps), 1, 2, 3, 4, or 5
```

#### `getNextStep()`

Determine what step to proceed to.

```php
$next = $stepTracker->getNextStep(
    voterSlug: VoterSlug,
    election: Election
): ?int

// Examples
$next = $stepTracker->getNextStep($voterSlug, $election);
// Returns: 1, 2, 3, 4, 5, or null (all complete)
```

#### `getNextStepRoute()`

Get the route name for next step.

```php
$route = $stepTracker->getNextStepRoute(
    voterSlug: VoterSlug,
    election: Election
): ?string

// Example
$route = $stepTracker->getNextStepRoute($voterSlug, $election);
// Returns: "slug.code.create", "slug.code.agreement", etc.

// Use in redirect
return redirect()->route($route, ['vslug' => $voterSlug->slug]);
```

#### `hasCompletedStep()`

Check if specific step was completed.

```php
$completed = $stepTracker->hasCompletedStep(
    voterSlug: VoterSlug,
    election: Election,
    step: int
): bool

// Example
if ($stepTracker->hasCompletedStep($voterSlug, $election, 2)) {
    // User accepted agreement
}
```

#### `getCompletedSteps()`

Get all completed steps with data.

```php
$steps = $stepTracker->getCompletedSteps(
    voterSlug: VoterSlug,
    election: Election
): Collection<VoterSlugStep>

// Example
foreach ($stepTracker->getCompletedSteps($voterSlug, $election) as $step) {
    echo "Step {$step->step} completed at {$step->completed_at}";
    if ($step->step_data) {
        print_r($step->step_data);
    }
}
```

#### `getStepTimeline()`

Get formatted timeline for audit/display.

```php
$timeline = $stepTracker->getStepTimeline(
    voterSlug: VoterSlug,
    election: Election
): array

// Returns
[
    [
        'step' => 1,
        'step_name' => 'slug.code.create',
        'completed_at' => '2026-02-04T14:35:17Z',
        'data' => ['code_verified' => true],
    ],
    [
        'step' => 2,
        'step_name' => 'slug.code.agreement',
        'completed_at' => '2026-02-04T14:36:22Z',
        'data' => ['agreement_accepted' => true],
    ],
]
```

---

## Testing Guide

### Unit Test Example

**File:** `tests/Unit/VoterStepTrackingServiceTest.php`

```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\VoterSlug;
use App\Models\Election;
use App\Services\VoterStepTrackingService;

class VoterStepTrackingServiceTest extends TestCase
{
    private VoterStepTrackingService $service;
    private VoterSlug $voterSlug;
    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new VoterStepTrackingService();
        $this->voterSlug = VoterSlug::factory()->create();
        $this->election = Election::factory()->create();
    }

    public function test_complete_step_records_in_database()
    {
        $this->service->completeStep(
            $this->voterSlug,
            $this->election,
            1,
            ['test_data' => true]
        );

        $this->assertDatabaseHas('voter_slug_steps', [
            'voter_slug_id' => $this->voterSlug->id,
            'election_id' => $this->election->id,
            'step' => 1,
        ]);
    }

    public function test_get_highest_completed_step_returns_max()
    {
        // Complete steps 1, 2, 3
        $this->service->completeStep($this->voterSlug, $this->election, 1);
        $this->service->completeStep($this->voterSlug, $this->election, 2);
        $this->service->completeStep($this->voterSlug, $this->election, 3);

        $highest = $this->service->getHighestCompletedStep(
            $this->voterSlug,
            $this->election
        );

        $this->assertEquals(3, $highest);
    }

    public function test_get_next_step_calculates_correctly()
    {
        $this->service->completeStep($this->voterSlug, $this->election, 2);

        $next = $this->service->getNextStep(
            $this->voterSlug,
            $this->election
        );

        $this->assertEquals(3, $next);
    }

    public function test_complete_step_is_idempotent()
    {
        $step1 = $this->service->completeStep(
            $this->voterSlug,
            $this->election,
            1
        );

        $step2 = $this->service->completeStep(
            $this->voterSlug,
            $this->election,
            1
        );

        // Should be same record
        $this->assertEquals($step1->id, $step2->id);

        // Should only be 1 record in DB
        $this->assertEquals(1, \DB::table('voter_slug_steps')
            ->where('voter_slug_id', $this->voterSlug->id)
            ->where('step', 1)
            ->count());
    }

    public function test_completed_steps_returns_in_order()
    {
        $this->service->completeStep($this->voterSlug, $this->election, 3);
        $this->service->completeStep($this->voterSlug, $this->election, 1);
        $this->service->completeStep($this->voterSlug, $this->election, 2);

        $completed = $this->service->getCompletedSteps(
            $this->voterSlug,
            $this->election
        );

        $this->assertEquals([1, 2, 3], $completed->pluck('step')->toArray());
    }
}
```

### Integration Test: Full Voting Flow

```php
public function test_full_voting_flow_records_all_steps()
{
    $voter = User::factory()->create();
    $voterSlug = VoterSlug::factory()
        ->for($voter)
        ->create();
    $election = Election::factory()->demo()->create();

    // Step 1: Code verification
    $response = $this->post("/v/{$voterSlug->slug}/code", [
        'voting_code' => 'ABC123'
    ]);
    $this->assertDatabaseHas('voter_slug_steps', [
        'step' => 1,
        'voter_slug_id' => $voterSlug->id,
    ]);

    // Step 2: Agreement
    $response = $this->post("/v/{$voterSlug->slug}/vote/agreement");
    $this->assertDatabaseHas('voter_slug_steps', [
        'step' => 2,
        'voter_slug_id' => $voterSlug->id,
    ]);

    // Verify user can access step 3 but not step 4
    $response = $this->get("/v/{$voterSlug->slug}/vote/create");
    $this->assertEquals(200, $response->status());

    $response = $this->get("/v/{$voterSlug->slug}/vote/verify");
    $this->assertEquals(302, $response->status()); // Redirected
}
```

### Manual Testing Checklist

```
□ New voter completes step 1 (code)
  → Verify: voter_slug_steps has 1 record with step=1
  → Verify: Service.getNextStep() returns 2

□ Voter tries to skip to step 3
  → Verify: Middleware redirects to step 2

□ Voter goes back to step 1
  → Verify: Allowed (can revisit completed steps)

□ Voter completes all 5 steps
  → Verify: 5 records in voter_slug_steps table
  → Verify: Service.getStepTimeline() returns all 5 with timestamps

□ Test in two different elections
  → Verify: Steps tracked separately per election
  → Verify: Step data for one election doesn't affect another
```

---

## Troubleshooting

### Issue: User Stuck on Step 1

**Symptom:** After entering code, user is redirected back to step 1

**Diagnosis:**

```bash
# Check: Is voter_slug_steps table populated?
SELECT * FROM voter_slug_steps
WHERE voter_slug_id = ? AND election_id = ?;

# Check: Did the code verification actually mark code as verified?
SELECT can_vote_now FROM codes
WHERE user_id = ? AND election_id = ?;

# Check: Are there logs indicating step completion failure?
tail -f storage/logs/laravel.log | grep "Step 1"
```

**Solution:**

```php
// 1. Verify code was marked as verified
$code = Code::find($codeId);
if (!$code->can_vote_now) {
    // Code verification didn't work, check CodeController.markCodeAsVerified()
}

// 2. Manually complete step if needed (debugging only)
$service = new VoterStepTrackingService();
$service->completeStep($voterSlug, $election, 1, [
    'code_verified' => true,
    'verified_at' => now(),
]);

// 3. Check middleware is allowing step 2
$next = $service->getNextStep($voterSlug, $election);
// Should return 2
```

### Issue: Voter Can Skip Steps

**Symptom:** User can directly access step 3 without completing step 2

**Diagnosis:**

```bash
# Check: Is EnsureVoterStepOrder middleware applied to route?
grep "slug.vote.create" routes/

# Check: Is middleware registered in HTTP kernel?
grep "EnsureVoterStepOrder" app/Http/Kernel.php
```

**Solution:**

1. Ensure middleware is applied to route:
```php
Route::get('vote/create', [VoteController::class, 'create'])
    ->middleware('ensure.voter.step.order') // ← Must be present
    ->name('slug.vote.create');
```

2. Check middleware registration in `app/Http/Kernel.php`:
```php
protected $routeMiddleware = [
    'ensure.voter.step.order' => \App\Http\Middleware\EnsureVoterStepOrder::class,
];
```

### Issue: Step Data Not Being Saved

**Symptom:** `voter_slug_steps.step_data` is always NULL

**Solution:**

Ensure `completeStep()` is being called with step data:

```php
// ❌ WRONG: No step data
$stepTracker->completeStep($voterSlug, $election, 1);

// ✅ CORRECT: Include step data
$stepTracker->completeStep($voterSlug, $election, 1, [
    'code_verified' => true,
    'verified_at' => now(),
]);
```

### Issue: Queries Are Slow

**Symptom:** Middleware is taking a long time

**Solution:**

Ensure indexes are created:

```bash
# Check indexes
SHOW INDEX FROM voter_slug_steps;

# Should see:
# - unique index on (voter_slug_id, election_id, step)
# - regular index on (voter_slug_id, election_id)
```

If missing, run migration:

```bash
php artisan migrate
```

### Issue: Multi-Election Conflict

**Symptom:** Steps from one election appear to affect another

**Diagnosis:**

```bash
# Check: Is election_id always included in queries?
SELECT * FROM voter_slug_steps WHERE voter_slug_id = 5;
# Should include election_id in WHERE clause!
```

**Solution:**

Always scope by election:

```php
// ❌ WRONG
$highest = VoterSlugStep::where('voter_slug_id', $voterSlugId)
    ->max('step');

// ✅ CORRECT
$highest = VoterSlugStep::where('voter_slug_id', $voterSlugId)
    ->where('election_id', $electionId)  // ← Critical!
    ->max('step');
```

---

## Best Practices

### 1. Always Use VoterStepTrackingService

✅ **DO:**
```php
$stepTracker = new VoterStepTrackingService();
$stepTracker->completeStep($voterSlug, $election, 1);
```

❌ **DON'T:**
```php
// Direct DB manipulation - loses logging, error handling
DB::table('voter_slug_steps')->insert([...]);
```

### 2. Include Meaningful Step Data

✅ **DO:**
```php
$stepTracker->completeStep($voterSlug, $election, 2, [
    'agreement_accepted' => true,
    'ip_address' => $request->ip(),
    'user_agent' => $request->userAgent(),
    'accepted_at' => now(),
    'agreement_version' => 'v1.0',
]);
```

❌ **DON'T:**
```php
// No context about what happened
$stepTracker->completeStep($voterSlug, $election, 2);
```

### 3. Always Scope by Election

✅ **DO:**
```php
$stepTracker->getHighestCompletedStep($voterSlug, $election);
```

❌ **DON'T:**
```php
// Might mix votes from different elections
$voterSlugSteps = $voterSlug->steps;
```

### 4. Log Significant Events

✅ **DO:**
```php
Log::info('✅ Step completed', [
    'voter_slug_id' => $voterSlug->id,
    'election_id' => $election->id,
    'step' => 2,
    'data' => $stepData,
]);
```

❌ **DON'T:**
```php
// Silent failures are hard to debug
$service->completeStep(...);
```

### 5. Use getStepTimeline() for Audits

✅ **DO:**
```php
// For audit reports
$timeline = $stepTracker->getStepTimeline($voterSlug, $election);
foreach ($timeline as $step) {
    echo "Step {$step['step']}: completed at {$step['completed_at']}";
}
```

❌ **DON'T:**
```php
// Raw DB queries lose formatting
$steps = DB::table('voter_slug_steps')->get();
```

### 6. Handle Idempotency Correctly

✅ **DO:**
```php
// Safe to call multiple times
$stepTracker->completeStep($voterSlug, $election, 1);
$stepTracker->completeStep($voterSlug, $election, 1); // Returns same record

// Always check highest completed, not previous step
$highest = $stepTracker->getHighestCompletedStep($voterSlug, $election);
```

❌ **DON'T:**
```php
// Assumes previous step, fragile
if ($code->can_vote_now) {
    $highest = 1;
    $next = 2;
}
```

### 7. Monitor for Data Integrity

**Recommended Monitoring:**

```bash
# Check for orphaned steps
SELECT vs.id, vs.voter_slug_id, vs.election_id
FROM voter_slug_steps vs
LEFT JOIN voter_slugs v ON vs.voter_slug_id = v.id
WHERE v.id IS NULL;

# Check for out-of-order steps (shouldn't happen)
SELECT voter_slug_id, election_id, GROUP_CONCAT(step ORDER BY step)
FROM voter_slug_steps
GROUP BY voter_slug_id, election_id
HAVING GROUP_CONCAT(step ORDER BY step) LIKE '%,%'
   AND GROUP_CONCAT(step ORDER BY step) NOT LIKE '1,2,3,4,5%';

# Check for duplicate steps (shouldn't happen - unique constraint)
SELECT voter_slug_id, election_id, step, COUNT(*)
FROM voter_slug_steps
GROUP BY voter_slug_id, election_id, step
HAVING COUNT(*) > 1;
```

---

## Migration Path from Old System

### For Existing Voters

If you have existing voters in the old system:

```php
// Migration: Populate voter_slug_steps from old data
// Based on their Code.can_vote_now status

foreach (VoterSlug::all() as $voterSlug) {
    $code = Code::where('user_id', $voterSlug->user_id)->first();

    if ($code && $code->can_vote_now) {
        // They completed at least step 1
        VoterSlugStep::firstOrCreate([
            'voter_slug_id' => $voterSlug->id,
            'election_id' => 1,
            'step' => 1,
        ], [
            'completed_at' => $code->code1_sent_at ?? now(),
            'step_data' => ['migrated' => true],
        ]);
    }
}
```

---

## Summary

The **Voter Step Tracking System** provides:

✅ **Audit Trail**: Every step is timestamped with data
✅ **No State Sync**: Database is source of truth
✅ **Multi-Election**: Per-election step tracking
✅ **Idempotent**: Safe to complete steps multiple times
✅ **Extensible**: Easy to add new steps
✅ **Debuggable**: Clear logs and timeline queries

This architecture ensures voters can't get stuck in routing loops and provides a complete audit trail of their voting journey.

---

**Document Revision History:**
- v1.0 (2026-02-04): Initial release

**Questions?** Check logs: `storage/logs/laravel.log`
