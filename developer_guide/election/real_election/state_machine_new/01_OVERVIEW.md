# Election State Machine — Constitutional Architecture

## Executive Summary

The Election State Machine implements a **Single Source of Truth (SSOT)** architecture where election state is derived from constitutional facts rather than stored directly in a state column. This ensures consistency, prevents state corruption, and makes the system resilient to migration and evolution.

**Key Principle:** State is COMPUTED, not STORED. The `state` column exists only as a cache for query optimization.

---

## What Changed

### Old Architecture (Mutable State)
```
Database state column → Directly read by controllers and policies
❌ Stale values
❌ Inconsistent reads
❌ Hard to trace state changes
❌ Migration nightmare
```

### New Architecture (Constitutional Facts → SSOT Derivation)
```
Constitutional Facts (voting_starts_at, nomination_completed, etc.)
           ↓
    ElectionLifecycleEngine (Pure PHP)
           ↓
    ElectionLifecycleState enum (12 states)
           ↓
    ElectionLifecycle Facade (SSOT API)
           ↓
    Controllers, Policies, Vue Components
❌ Never reads stale state column
✅ Always gets derived state
✅ Consistent across all layers
```

---

## The 12 SSOT States (Constitutional Progression)

### Approval Phase
| State | Meaning | Derived From | Transition Action |
|-------|---------|--------------|-------------------|
| **draft** | Initial state, election being configured | `submitted_for_approval_at = NULL` | submit_for_approval |
| **submitted_for_approval** | Awaiting platform admin approval/rejection | `submitted_for_approval_at IS NOT NULL AND approved_at IS NULL AND rejected_at IS NULL` | approve / reject |

### Setup Phase
| State | Meaning | Derived From | Transition Action |
|-------|---------|--------------|-------------------|
| **approved** | Admin approved, ready for setup phases | `approved_at IS NOT NULL AND administration_completed = false` | begin_setup |
| **rejected** | Admin rejected the election (terminal) | `rejected_at IS NOT NULL` | — |
| **setup_administration** | Admin phase: configuring posts, voters, chief | `setup_started_at IS NOT NULL AND administration_completed = false` | complete_administration |
| **setup_nomination** | Nomination phase: candidates registering (time-based) | `administration_completed = true AND nomination_completed = false AND nomination_suggested_start ≤ NOW` | complete_nomination |

### Voting Phase
| State | Meaning | Derived From | Transition Action |
|-------|---------|--------------|-------------------|
| **ready_for_voting** | Setup complete, voting not yet started | `nomination_completed = true AND voting_starts_at > NOW` | open_voting |
| **voting_active** | Voting window open (NOW between voting_starts_at and voting_ends_at) | `voting_locked = true AND voting_starts_at ≤ NOW ≤ voting_ends_at` | close_voting |

### Results Phase
| State | Meaning | Derived From | Transition Action |
|-------|---------|--------------|-------------------|
| **counting** | Voting closed, results being finalized | `voting_ends_at ≤ NOW AND results_published_at IS NULL` | publish_results |
| **results_published** | Results published to voters (terminal) | `results_published_at IS NOT NULL` | archive |
| **archived** | Election is archived/historical (terminal) | `archived_at IS NOT NULL` | — |

### Operational States
| State | Meaning | Special Behavior |
|-------|---------|-----------------|
| **suspended** | Governance override: election paused operationally | Time-based + flag: `suspended_at IS NOT NULL`. Checked FIRST before all other rules. Can transition to any state via resume action. |

---

## Constitutional Facts (Not State)

The engine derives state by evaluating these facts. State is NEVER stored—it's always computed from these immutable facts:

### Approval & Rejection Timeline
- `submitted_for_approval_at` — When chief submitted for platform admin approval (NULL = not submitted)
- `approved_at` — When platform admin approved (NULL = not approved)
- `approved_by` — Which platform admin approved
- `rejected_at` — When platform admin rejected (NULL = not rejected)
- `rejected_by` — Which platform admin rejected
- `rejection_reason` — Why election was rejected

### Setup Phase (Administration & Nomination)
- `setup_started_at` — When begin_setup transition occurred (marks entry to setup_administration)
- `administration_completed` — boolean, Admin phase complete?
- `administration_completed_at` — Timestamp when administration was marked complete
- `nomination_suggested_start` — When nomination phase should open (time-based)
- `nomination_suggested_end` — When nomination phase should close
- `nomination_completed` — boolean, Nomination phase complete?
- `nomination_completed_at` — Timestamp when nomination was marked complete

### Voting Window
- `voting_starts_at` — When voting begins (NULL = not configured)
- `voting_ends_at` — When voting ends (NULL = not configured)
- `voting_locked` — boolean, Is voting locked/started?
- `voting_locked_at` — When voting was locked
- `voting_locked_by` — Which chief locked voting

### Results & Archival
- `results_published_at` — When results published (NULL = not published)
- `archived_at` — When archived (NULL = not archived)

### Configuration
- `timezone` — Election timezone (precondition for approval)
- `expected_voter_count` — For capacity eligibility check
- `suspended_at` — Operational pause (checked FIRST; overrides all derivation rules)

---

## Three-Layer Architecture

```
┌─────────────────────────────────────────────────────────┐
│ Layer 1: Controllers, Vue Components, Policies           │
│ ├─ USE: ElectionLifecycle::of($election)->state()       │
│ └─ NEVER: Read $election->state directly                │
└─────────────────────────────────────────────────────────┘
                         ↓
┌─────────────────────────────────────────────────────────┐
│ Layer 2: ElectionLifecycle Facade (API Gateway)          │
│ ├─ state() → Get current state                          │
│ ├─ canVote() → Can voting happen?                       │
│ ├─ canEditTimeline() → Can dates be edited?             │
│ ├─ allowedActions() → What transitions are allowed?     │
│ └─ snapshot() → Get full lifecycle snapshot             │
└─────────────────────────────────────────────────────────┘
                         ↓
┌─────────────────────────────────────────────────────────┐
│ Layer 3: ElectionLifecycleEngine (Pure PHP, No Laravel) │
│ ├─ compute($election) → Derive state from facts         │
│ ├─ 12 derivation rules (progression + suspended)        │
│ └─ Returns ElectionLifecycleSnapshot                    │
└─────────────────────────────────────────────────────────┘
```

---

## When State Column is Synced

The `state` column is updated in exactly these scenarios:

1. **After `transitionTo()` completes** — State machine writes the new state
2. **In ElectionScenarioFactory** — After deriving state in tests, sync to DB
3. **Never in other places** — No raw DB updates, no silent column writes

```php
// ✅ Correct: State is synced after transition
$election->transitionTo(Transition::manual('open_voting', $userId, 'reason'));
// transitionTo() internally updates state column

// ✅ Correct: Tests verify and sync
$state = ElectionLifecycle::of($election)->state()->value;
$election->update(['state' => $state->value]);

// ❌ Wrong: Direct column write
$election->update(['state' => 'voting_active']); // NEVER DO THIS
```

---

## Key Principles

### 1. **SSOT is Sacred**
Always read state from the engine, never from the database column. If you're tempted to read `$election->state`, read `ElectionLifecycle::of($election)->state()` instead.

### 2. **Facts Don't Lie**
The 10 constitutional facts (voting_starts_at, nomination_completed, etc.) are the source of truth. State is just a computed view of those facts.

### 3. **State Changes Are Transitions**
Never change state directly. Use `transitionTo()` which:
- Validates the transition is allowed
- Runs authorization checks (ConstitutionalTransitionGuard)
- Applies side effects (e.g., lock voting when opening)
- Creates audit trail (ElectionStateTransition record)
- Syncs state column to database

### 4. **Tests Set Facts, Verify State**
```php
// ✅ Correct test pattern
$election->update([
    'voting_starts_at' => now()->subHour(),  // Fact
    'voting_ends_at' => now()->addHour(),    // Fact
    'voting_locked' => true,                 // Fact
]);
$state = ElectionLifecycle::of($election)->state(); // Verify derivation
$this->assertEquals('voting_active', $state->value);
```

### 5. **No Stale Reads**
Every time you need state, derive it fresh:
```php
// ❌ Wrong: State is stale after update
$election->update(['voting_starts_at' => now()]);
$state = $election->state; // Might be OLD value

// ✅ Correct: Always re-derive
$election->update(['voting_starts_at' => now()]);
$state = ElectionLifecycle::of($election->fresh())->state();
```

---

## Common Patterns

### Check Current State
```php
$state = ElectionLifecycle::of($election)->state();
if ($state->value === 'voting_active') {
    // Can accept votes
}
```

### Check If Action is Allowed
```php
$lifecycle = ElectionLifecycle::of($election);
if ($lifecycle->canVote()) {
    // Allow voting
}

if ($lifecycle->canTransitionTo('close_voting')) {
    // Show close voting button
}
```

### Get All Allowed Actions
```php
$actions = ElectionLifecycle::of($election)->allowedActions();
// ['open_voting', 'close_voting', 'archive'] etc.
```

### Perform State Transition
```php
try {
    $election->transitionTo(
        Transition::manual(
            action: 'open_voting',
            actorId: auth()->id(),
            reason: 'Officer opened voting'
        )
    );
    return back()->with('success', 'Voting opened');
} catch (InvalidTransitionException $e) {
    return back()->with('error', $e->getMessage());
}
```

---

## Files Organization

```
app/Application/Election/
├── Facades/
│   └── ElectionLifecycle.php          ← Use this in controllers/policies
├── Services/
│   └── ElectionLifecycleEngineImpl.php ← Engine computes state
├── Domain/
│   ├── Enum/ElectionLifecycleState.php ← The 10 states
│   └── ValueObjects/ElectionLifecycleSnapshot.php ← Snapshot
└── Constitution/
    └── ElectionConstitution.php        ← Transition rules

tests/Support/
└── ElectionScenarioFactory.php         ← Build test scenarios by facts

app/Models/
├── Election.php                        ← Model (minimized)
└── ElectionStateTransition.php         ← Audit trail
```

---

## What NOT to Do

| Don't | Do Instead |
|--------|-----------|
| `$election->state` | `ElectionLifecycle::of($election)->state()->value` |
| `Election::where('status', 'active')` | Filter by derived state in PHP or use ElectionClockService |
| `$election->update(['state' => 'voting'])` | `$election->transitionTo(...)` |
| Check `$election->is_active` | `ElectionLifecycle::of($election)->isActive()` |
| Read `current_state` property | Use `ElectionLifecycle::of($election)->state()` |

---

## Glossary

- **Constitutional Fact** — Business logic attribute (voting_starts_at, nomination_completed, etc.)
- **SSOT** — Single Source of Truth. Facts are the SSOT; state is a computed view
- **Snapshot** — Immutable object containing current state and capabilities (canVote, allowedActions, etc.)
- **Transition** — Change from one state to another via `transitionTo()`
- **Side Effect** — Action that happens when transitioning (e.g., locking voting)
- **Transition Matrix** — Legacy system, DEPRECATED. Use ElectionConstitution instead

---

## Next Steps

1. **For Developers:** Read `02_STATE_TRANSITIONS.md` — see state diagram and transitions
2. **For API Integration:** Read `03_DEVELOPER_API.md` — how to use ElectionLifecycle in code
3. **For Troubleshooting:** Read `04_TROUBLESHOOTING.md` — common issues and fixes
4. **For Examples:** Read `05_CODE_RECIPES.md` — copy-paste examples
5. **For Migration:** Read `06_MIGRATION_GUIDE.md` — migrating from old system

---

**Last Updated:** May 21, 2026
**Status:** Constitutional Hardening Phase 3.1 — STABLE
