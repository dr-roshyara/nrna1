# Phase 3.2: SSOT Engine Reference

**Date:** 2026-05-20  
**Phase:** 3.2 (Graduated Strict Activation)  
**Status:** Complete and verified  
**Audience:** Architects, backend engineers, infrastructure team

---

## 🎯 What Is the SSOT Engine?

The **Single Source of Truth Engine** (`ElectionLifecycleEngineImpl`) derives the current election state from immutable business facts stored in the database. Instead of reading a cached `state` column directly, the engine computes state based on what actually happened.

### Core Principle

```
state ≠ column
state = function(facts)

Facts are authoritative. State is derived.
```

### Why This Matters

**Old (dangerous):**
```php
$election->state;  // 'import_voters' (invalid state, wrong!)
```

**New (safe):**
```php
ElectionLifecycle::of($election)->state();  // Computed from facts → 'draft' (correct)
```

---

## 📊 Business Facts (Immutable Sources of Truth)

The engine looks at these column values to determine state:

| Fact | Column | Meaning |
|------|--------|---------|
| Rejected? | `rejected_at` | Timestamp when election was rejected. If set → REJECTED state |
| Submitted? | `submitted_for_approval_at` | Timestamp when submitted for approval. If set → flows through approval states |
| Approved? | `approved_at` | Timestamp when approved. If set → election can proceed to setup |
| Admin done? | `administration_completed_at` | Timestamp when admin phase ended. If set → ready for nomination |
| Nomination done? | `nomination_completed_at` | Timestamp when nomination phase ended. If set → ready for voting window |
| Voting starts? | `voting_starts_at` | DateTime when voting opens. If set and now past → voting active |
| Voting ends? | `voting_ends_at` | DateTime when voting closes. If set and now past → counting phase |
| Results published? | `results_published_at` | Timestamp when results published. If set → results visible |
| Candidates approved? | (via SQL COUNT) | Count of approved candidates. If 0 → still in nomination |

### What About status, is_active, locked?

These columns are **deprecated**:
- `status` - Old system (ignore)
- `is_active` - Old system (ignore)
- `locked` - Old system (ignore)

The engine only looks at the timestamp/fact columns above.

---

## 🔍 State Derivation Logic (Priority Order)

The engine checks conditions in this exact order:

### Layer 1: Rejection (Highest Priority)

```
IF rejected_at IS NOT NULL
  → STATE = REJECTED
  → Stop checking, election is terminated in rejection
```

**Why first?** Rejected elections cannot transition forward. Rejection is terminal (until resubmitted).

### Layer 2: Approval Workflow

```
IF submitted_for_approval_at IS NOT NULL
  AND approved_at IS NULL
  AND rejected_at IS NULL
  → STATE = SUBMITTED_FOR_APPROVAL
  → Election is awaiting platform approval
```

```
IF approved_at IS NOT NULL
  AND administration_completed_at IS NULL
  → STATE = APPROVED
  → Election passed approval, ready to begin setup
```

**Why second?** Approval states are the gating layer between draft and setup.

### Layer 3: Setup Phase (Administration + Nomination)

```
IF administration_completed_at IS NOT NULL
  AND nomination_completed_at IS NULL
  AND approved_at IS NOT NULL
  → STATE = SETUP
  → Admin phase done, nomination in progress
```

**Why third?** Setup is a multi-phase state that spans both administration and nomination.

### Layer 4: Ready for Voting

```
IF nomination_completed_at IS NOT NULL
  AND voting_starts_at IS NOT NULL
  AND voting_starts_at > NOW()
  → STATE = READY_FOR_VOTING
  → Election waiting for voting window to open
```

**Why fourth?** Voting readiness is the bridge between setup and voting phases.

### Layer 5: Active Voting

```
IF voting_starts_at IS NOT NULL
  AND voting_starts_at <= NOW()
  AND voting_ends_at IS NOT NULL
  AND voting_ends_at > NOW()
  → STATE = VOTING_ACTIVE
  → Voters can currently vote
```

**Why fifth?** Active voting requires the voting window to be open NOW.

### Layer 6: Counting

```
IF voting_ends_at IS NOT NULL
  AND voting_ends_at <= NOW()
  AND results_published_at IS NULL
  → STATE = COUNTING
  → Voting window closed, tallying in progress
```

**Why sixth?** Counting begins after voting ends and before results.

### Layer 7: Results Published

```
IF results_published_at IS NOT NULL
  → STATE = RESULTS_PUBLISHED
  → Final state before archival
```

**Why seventh?** Results are the culmination of voting.

### Layer 8: Archived (Fallback)

```
IF none of the above apply
  AND election is very old (>90 days after results_published_at)
  → STATE = ARCHIVED
  → Election is historical record
```

**Why last?** Archival is optional, manual, or time-based.

### Layer 9: Draft (Default Fallback)

```
IF no conditions above matched
  → STATE = DRAFT
  → New election, not yet submitted
```

**Why last?** Draft is the initial state for any election without facts set.

---

## 💻 Code Implementation

```php
// app/Application/Election/Services/ElectionLifecycleEngineImpl.php

public function getState(Election $election): ElectionLifecycleState
{
    // Layer 1: Rejection (terminal)
    if ($election->rejected_at !== null) {
        return ElectionLifecycleState::Rejected;
    }

    // Layer 2a: Submitted but not approved
    if ($election->submitted_for_approval_at !== null 
        && $election->approved_at === null) {
        return ElectionLifecycleState::SubmittedForApproval;
    }

    // Layer 2b: Approved but not in setup
    if ($election->approved_at !== null 
        && $election->administration_completed_at === null) {
        return ElectionLifecycleState::Approved;
    }

    // Layer 3: Setup phase
    if ($election->administration_completed_at !== null 
        && $election->nomination_completed_at === null) {
        return ElectionLifecycleState::Setup;
    }

    // Layer 4: Ready for voting (window not yet open)
    if ($election->nomination_completed_at !== null 
        && $election->voting_starts_at !== null 
        && $election->voting_starts_at > now()) {
        return ElectionLifecycleState::ReadyForVoting;
    }

    // Layer 5: Voting active (window currently open)
    if ($election->voting_starts_at !== null 
        && $election->voting_starts_at <= now() 
        && $election->voting_ends_at !== null 
        && $election->voting_ends_at > now()) {
        return ElectionLifecycleState::VotingActive;
    }

    // Layer 6: Counting (voting ended, results not published)
    if ($election->voting_ends_at !== null 
        && $election->voting_ends_at <= now() 
        && $election->results_published_at === null) {
        return ElectionLifecycleState::Counting;
    }

    // Layer 7: Results published
    if ($election->results_published_at !== null) {
        return ElectionLifecycleState::ResultsPublished;
    }

    // Layer 9: Default to Draft
    return ElectionLifecycleState::Draft;
}
```

---

## 🔄 State Transitions via Facts

When you want to move an election to the next state, you set the corresponding fact:

| Target State | Set This Fact | How |
|--------------|---------------|-----|
| SUBMITTED_FOR_APPROVAL | `submitted_for_approval_at = NOW()` | via `submit_for_approval` action |
| APPROVED | `approved_at = NOW()` | via `approve` action |
| REJECTED | `rejected_at = NOW()` | via `reject` action |
| SETUP | `administration_completed_at = NOW()` | via `complete_administration` action |
| READY_FOR_VOTING | `nomination_completed_at = NOW()` | via `complete_nomination` action |
| VOTING_ACTIVE | `voting_starts_at ≤ NOW()` | via `open_voting` action (automatically active) |
| COUNTING | `voting_ends_at ≤ NOW()` | via `close_voting` action |
| RESULTS_PUBLISHED | `results_published_at = NOW()` | via `publish_results` action |

### Example: Advancing Election to Setup

```php
// Current state: APPROVED
// Goal: SETUP

$election = Election::find($id);
$lifecycle = ElectionLifecycle::of($election);

// Validate preconditions
// - has_posts? yes
// - has_voters? yes
// - has_committee_members? yes

// Set the fact that moves state forward
$election->update([
    'administration_completed_at' => now(),
]);

// Engine now computes: STATE = SETUP (because admin_completed but nomination not)
$newState = ElectionLifecycle::of($election)->state();
// → ElectionLifecycleState::Setup
```

---

## ✅ Verifying State Derivation

### Audit a Single Election

```bash
# Check what the SSOT engine computes
php artisan tinker

>>> $election = Election::find(123);
>>> $engine = app(\App\Application\Election\Services\ElectionLifecycleEngineImpl::class);
>>> $computed = $engine->getState($election);
>>> echo $computed->value;  # e.g., "setup"

# Check the facts
>>> echo $election->rejected_at;           # null
>>> echo $election->submitted_for_approval_at;  # 2026-05-15 10:00:00
>>> echo $election->approved_at;           # 2026-05-15 10:30:00
>>> echo $election->administration_completed_at;  # 2026-05-16 14:20:00
>>> echo $election->nomination_completed_at;    # null
# → Facts show: approved=yes, admin=done, nomination=not done
# → Engine correctly returns: STATE = SETUP
```

### Audit All Elections

```bash
# Find divergences between cached state and computed state
php artisan app:backfill-election-state --audit-only

# Output shows any elections where:
#   $election->state (cached column) ≠ ElectionLifecycleEngineImpl::getState()
```

### Repair Divergences

```bash
# Fix all divergences in one pass
php artisan app:backfill-election-state

# This updates the state column to match computed state
# Uses ElectionStateWriteContext::authorize() for all updates
```

---

## 🔑 Key Concepts

### Facts vs State

```php
// Facts: What actually happened (immutable once set)
$election->voting_starts_at = '2026-06-01 09:00:00';  // FACT
$election->voting_ends_at = '2026-06-01 17:00:00';    // FACT

// State: Computed interpretation of facts
ElectionLifecycle::of($election)->state()  // → VotingActive or ReadyForVoting (depends on NOW())
```

### Why Priority Order Matters

The priority order ensures:
1. **Rejection is terminal** - Rejected elections don't flow into other states
2. **Approval gates everything** - Can't do setup without approval
3. **Time-based states are last** - Voting states depend on current time

### Edge Case: What If Multiple Facts Are Set?

The engine uses the first matching condition:

```php
// Example: All facts are set
$election->submitted_for_approval_at = '2026-05-15 10:00:00';
$election->approved_at = '2026-05-15 11:00:00';
$election->rejected_at = '2026-05-15 11:30:00';  // Set AFTER approval
$election->administration_completed_at = '2026-05-16 14:00:00';

// Engine checks in order:
// Layer 1: rejected_at != null? YES
// → Return REJECTED (stop here)

// Result: STATE = REJECTED
// The rejection is the latest fact chronologically AND has priority
```

---

## 📈 State Permissions (What Can Happen In Each State)

The engine also derives which **actions are allowed** in each state:

```php
public function getBlockedReason(Election $election): ?string
{
    $state = $this->getState($election);
    
    return match($state) {
        ElectionLifecycleState::Rejected => 'Election was rejected. Revise and resubmit to continue.',
        ElectionLifecycleState::SubmittedForApproval => 'Election is awaiting platform approval.',
        ElectionLifecycleState::Approved => 'Election approved. Begin setup to continue.',
        ElectionLifecycleState::Setup => 'Election in setup phase. Complete setup to proceed.',
        ElectionLifecycleState::ReadyForVoting => 'Awaiting voting window start.',
        ElectionLifecycleState::VotingActive => 'Voting in progress.',
        ElectionLifecycleState::Counting => 'Votes being counted.',
        ElectionLifecycleState::ResultsPublished => 'Election complete.',
        // ...
    };
}
```

---

## 🧪 Testing State Derivation

### Unit Test Example

```php
// tests/Unit/Application/Election/ElectionLifecycleEngineTest.php

class ElectionLifecycleEngineTest extends TestCase
{
    /** @test */
    public function derives_draft_state_from_empty_facts()
    {
        $election = Election::factory()->create([
            'submitted_for_approval_at' => null,
            'rejected_at' => null,
            'approved_at' => null,
            'administration_completed_at' => null,
        ]);

        $engine = app(ElectionLifecycleEngineImpl::class);
        $this->assertEquals(ElectionLifecycleState::Draft, $engine->getState($election));
    }

    /** @test */
    public function derives_voting_active_when_window_open()
    {
        $election = Election::factory()->create([
            'voting_starts_at' => now()->subHour(),
            'voting_ends_at' => now()->addHour(),
        ]);

        $engine = app(ElectionLifecycleEngineImpl::class);
        $this->assertEquals(ElectionLifecycleState::VotingActive, $engine->getState($election));
    }

    /** @test */
    public function rejection_takes_priority_over_other_facts()
    {
        $election = Election::factory()->create([
            'submitted_for_approval_at' => now()->subHour(),
            'approved_at' => now()->subMinutes(30),
            'rejected_at' => now(),  // Latest fact
        ]);

        $engine = app(ElectionLifecycleEngineImpl::class);
        $this->assertEquals(ElectionLifecycleState::Rejected, $engine->getState($election));
    }
}
```

---

## 🔍 Common Divergence Scenarios

### Scenario 1: Cached State Never Updated

**Cause:** Developer called `$election->update(['administration_completed_at' => now()])` but forgot to update `state` column.

**Detection:**
```
Cached (column):  'nomination'
Computed (SSOT):  'setup'
Divergence: YES
```

**Fix:** Run `php artisan app:backfill-election-state` to sync.

### Scenario 2: Fact Set by Database Trigger

**Cause:** Database trigger sets `administration_completed_at` automatically, but application code doesn't know.

**Detection:**
```
Cached (column):  'setup'  (old)
Computed (SSOT):  'ready_for_voting'  (trigger just fired)
Divergence: YES
```

**Fix:** Update code to read from SSOT engine instead of column, or sync via backfill.

### Scenario 3: Concurrent Updates

**Cause:** Two processes set facts simultaneously. Column gets overwritten.

**Detection:**
```
Cached (column):  'voting'
Computed (SSOT):  'counting'  (voting_ends_at was set by other process)
Divergence: YES
```

**Fix:** Use ElectionStateWriteContext to serialize all updates.

---

## 📊 Metrics & Monitoring

### Track State Computations

```php
// In ElectionLifecycleEngineImpl
$metrics = app(ConstitutionalMetricsContract::class);

// Count computations by state
$metrics->incrementStateComputedCount($state->value);

// Track divergences
if ($election->state !== $computed->value) {
    $metrics->recordDivergence($election->id, $election->state, $computed->value);
}
```

### Dashboards to Build

```
Election State Machine Health Dashboard
├── State Distribution (pie chart)
│   ├── Draft: 45 elections
│   ├── Submitted: 12 elections
│   ├── Approved: 8 elections
│   ├── Setup: 3 elections
│   ├── VotingActive: 2 elections
│   └── Archived: 156 elections
│
├── Approval Metrics (bar chart)
│   ├── Auto-approved (free ≤40): 38/50
│   ├── Manually approved (paid): 10/50
│   └── Rejected: 2/50
│
├── Divergence Tracking (time series)
│   ├── 24h divergences: 0
│   ├── 7d divergences: 1
│   └── 30d divergences: 3
│
└── Average Time in State (histogram)
    ├── Draft: 2.3 days
    ├── Setup: 5.1 days
    └── Voting: 1 day
```

---

## 🚀 Using the SSOT Engine in Controllers

### In Election Controllers

```php
// DON'T DO THIS:
$state = $election->state;  // ❌ Reading cached column

// DO THIS:
$lifecycle = ElectionLifecycle::of($election);
$state = $lifecycle->state();  // ✅ Computed from facts
$label = $state->label();      // "Setup Phase"
$blocked = $lifecycle->getBlockedReason();  // or null if not blocked
```

### In API Responses

```php
// ElectionResource.php
public function toArray(Request $request): array
{
    $lifecycle = ElectionLifecycle::of($this->resource);
    
    return [
        'id' => $this->resource->id,
        'name' => $this->resource->name,
        'state' => $lifecycle->state()->value,      // ✅ Computed
        'state_label' => $lifecycle->state()->label(),
        'is_locked' => $lifecycle->isLocked(),
        'blocked_reason' => $lifecycle->getBlockedReason(),
    ];
}
```

---

## 🎯 Summary

**The SSOT Engine:**
1. ✅ Derives state from immutable business facts
2. ✅ Guarantees consistency (same facts always produce same state)
3. ✅ Detects divergences (column ≠ computed)
4. ✅ Provides audit trail (facts show what happened)
5. ✅ Enables recovery (backfill syncs column to computed)

**Key files:**
- `ElectionLifecycleEngineImpl.php` - The engine
- `ElectionLifecycleState.php` - The enum with state logic
- `ElectionLifecycle.php` - Facade for common operations
- `BackfillElectionState.php` - Audit and repair command

**Next step:**
- Understand the write barrier: see `04-controlled-write-barrier.md`
- Test state machine: see `testing.md`
