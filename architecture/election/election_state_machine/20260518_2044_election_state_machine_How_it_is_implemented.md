# Election State Machine: Implementation Deep Dive
**Date:** 2026-05-18 21:44  
**Scope:** Complete architecture, components, validation rules, and operational flows  
**Audience:** Backend developers, architects, QA engineers

---

## Executive Summary

The election state machine is a **robust, audit-able DDD implementation** that governs the complete lifecycle of an election from draft creation through final results publication. It enforces:

✅ **Deterministic state transitions** — No ambiguous transitions; every action maps to exactly one target state  
✅ **Role-based authorization** — Actions restricted to specific election roles (chief, deputy, admin, etc.)  
✅ **Business condition validation** — Can't enter voting without candidates; can't publish results without closing voting  
✅ **Immutable audit trail** — Every transition recorded with actor, timestamp, trigger (manual/time/system), and reason  
✅ **Atomic transitions** — State changes protected by database locks and transactions  
✅ **Domain events** — Workflow-relevant events dispatched after commit for listeners (email notifications, cache invalidation, etc.)

---

## State Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                           ELECTION LIFECYCLE                                 │
└─────────────────────────────────────────────────────────────────────────────┘

START (Election Created)
       ↓
    ┌──────────────────────────────────────────────────────────────┐
    │                         DRAFT                                 │
    │  • Configure election settings (name, dates, posts)          │
    │  • Manage committee members                                  │
    │  • Import voters                                             │
    │  • Set capacity (expected_voter_count)                       │
    │                                                               │
    │  Transitions:                                                │
    │  - submit_for_approval   → PENDING_APPROVAL (>40 voters)     │
    │  - auto_submit (system)  → ADMINISTRATION (≤40 voters)       │
    └──────────────────────────────────────────────────────────────┘
                         ↓
    ┌──────────────────────────────────────────────────────────────┐
    │                   PENDING_APPROVAL (Optional)                │
    │  • Awaiting PublicDigit platform admin review                │
    │  • Admin can approve → ADMINISTRATION                        │
    │  • Admin can reject → DRAFT                                 │
    │                                                               │
    │  Status: Manual approval path (for >40 expected voters)      │
    └──────────────────────────────────────────────────────────────┘
                         ↓ approve / ↖ reject
    ┌──────────────────────────────────────────────────────────────┐
    │                   ADMINISTRATION                              │
    │  • Import/manage voters                                      │
    │  • Manage election committee                                 │
    │  • Create election posts                                     │
    │                                                               │
    │  Prerequisites to complete:                                  │
    │  ✓ At least 1 post                                          │
    │  ✓ At least 1 voter                                         │
    │  ✓ At least 1 committee member                              │
    │                                                               │
    │  Transition: complete_administration → NOMINATION            │
    └──────────────────────────────────────────────────────────────┘
                         ↓
    ┌──────────────────────────────────────────────────────────────┐
    │                      NOMINATION                               │
    │  • Candidates can apply (via CandidacyApplication)           │
    │  • Election chief approves/rejects applications              │
    │  • View approved candidates                                  │
    │                                                               │
    │  Prerequisites to move forward:                              │
    │  ✓ Nomination completed (no pending applications)            │
    │  ✓ At least N approved candidates (configurable min)         │
    │  ✓ Voting dates configured                                  │
    │                                                               │
    │  Transition: open_voting → VOTING                            │
    └──────────────────────────────────────────────────────────────┘
                         ↓
    ┌──────────────────────────────────────────────────────────────┐
    │                       VOTING                                  │
    │  • Voters cast votes (Steps 1-5 workflow)                   │
    │  • Voting locked prevents date changes                       │
    │  • Votes recorded in election_memberships table             │
    │                                                               │
    │  Conditions:                                                 │
    │  • voting_starts_at ≤ now ≤ voting_ends_at                  │
    │  • Submittable: TRUE (election is_active)                   │
    │                                                               │
    │  Sub-Actions:                                                │
    │  - lock_voting  → remains VOTING (marks locked state)        │
    │  - close_voting → RESULTS_PENDING                            │
    └──────────────────────────────────────────────────────────────┘
                         ↓ close_voting
    ┌──────────────────────────────────────────────────────────────┐
    │                  RESULTS_PENDING                              │
    │  • Voting closed (no new votes accepted)                     │
    │  • Results being prepared/verified by chief                 │
    │  • Audit logs reviewed                                       │
    │                                                               │
    │  Transition: publish_results → RESULTS                       │
    └──────────────────────────────────────────────────────────────┘
                         ↓ publish_results
    ┌──────────────────────────────────────────────────────────────┐
    │                       RESULTS                                 │
    │  • Final state — results publicly available                  │
    │  • Voters can verify their votes via receipt codes           │
    │  • No further modifications possible                         │
    │                                                               │
    │  Status: TERMINAL — No further transitions                   │
    └──────────────────────────────────────────────────────────────┘

```

---

## Complete States & Transitions Matrix

### State Definitions

| State | Database Value | Purpose | Entry Condition | Exit Criteria |
|-------|---|---------|-----------|---|
| **draft** | `draft` | Initial state after creation | Auto on create | submit_for_approval OR auto_submit (system) |
| **pending_approval** | `pending_approval` | Awaiting admin review (for large elections) | Manual submit when voters > 40 | approve OR reject |
| **administration** | `administration` | Setup phase: voters, posts, committee | Approval granted or auto-approved | complete_administration |
| **nomination** | `nomination` | Candidate application phase | Admin completes administration | open_voting |
| **voting** | `voting` | Live voting window | Nomination complete + candidates ready | close_voting |
| **results_pending** | `results_pending` | Post-voting, before publication | Voting closed + votes exist | publish_results |
| **results** | `results` | Final state, results published | Results published | — (terminal) |

### Transition Matrix (Actions → Target States)

```php
// Source: app/Domain/Election/StateMachine/TransitionMatrix.php

draft:
  ├─ submit_for_approval  → pending_approval  (roles: chief, admin, owner)
  └─ auto_submit (system) → administration    (roles: system)

pending_approval:
  ├─ approve  → administration  (roles: super_admin, platform_admin)
  └─ reject   → draft           (roles: super_admin, platform_admin)

administration:
  └─ complete_administration → nomination  (roles: chief, deputy)

nomination:
  └─ open_voting → voting  (roles: chief, deputy)

voting:
  ├─ lock_voting  → voting           (roles: chief, deputy)  [same state, side effects only]
  └─ close_voting → results_pending  (roles: chief, deputy)

results_pending:
  └─ publish_results → results  (roles: chief)

results:
  └─ [terminal — no transitions]
```

---

## Core Implementation Components

### 1. **TransitionMatrix** — Single Source of Truth
**File:** `app/Domain/Election/StateMachine/TransitionMatrix.php`

Encapsulates all valid transitions and role permissions:

```php
class TransitionMatrix
{
    const TRANSITIONS = [
        'draft' => [
            'submit_for_approval' => ['to' => 'pending_approval', 'roles' => [...]],
            'auto_submit'         => ['to' => 'administration',   'roles' => ['system']],
        ],
        // ... rest of states
    ];

    // Query methods
    canPerformAction($fromState, $action)      // Can perform this action from state?
    getResultingState($action)                 // What state results from this action?
    getAllowedActions($state)                  // What actions available in this state?
    getAllowedRoles($action)                   // Which roles can perform this action?
    actionRequiresRole($action, $role)         // Can this role perform this action?
    
    // Validation
    validate()                                 // Boot-time invariant check
}
```

**Critical guarantees:**
- ✓ No typos in state/action names (caught at boot via enum validation)
- ✓ All actions route to exactly one target state (no ambiguity)
- ✓ All roles in TRANSITIONS exist in ElectionRole enum
- ✓ All target states exist in ElectionState enum

### 2. **Transition** — Value Object (Immutable)
**File:** `app/Domain/Election/StateMachine/Transition.php`

Represents a single state transition with metadata:

```php
final class Transition
{
    public readonly string $action;
    public readonly string $actorId;      // String: user ID or 'system'
    public readonly ?string $reason;      // Audit reason
    public readonly TransitionTrigger $trigger;  // manual | time | grace_period | system
    public readonly array $metadata;      // Extra data (e.g., rejection reason)

    // Factories for different transition types:
    static function manual($action, $actorId, $reason)                           // User-initiated
    static function automatic($action, $trigger, $reason)                        // Scheduled/system
    static function gracePeriod($action, $reason)                               // Grace period expiration
}
```

**Immutability guarantee:** Once created, cannot be modified (only new instances can be created via factories).

### 3. **Election::transitionTo()** — Orchestrator
**File:** `app/Models/Election.php` (lines 1555–1653)

The **single entry point** for all state transitions. Implements a 7-step process:

```
Step 1: Lock acquisition (distributed lock via cache)
        ↓
Step 2: Validate — action allowed from current state?
        ↓
Step 3: Authorize — actor role allowed for this action?
        ↓
Step 4: Guard — business conditions met?
        ↓
Step 5: Audit — create ElectionStateTransition record
        ↓
Step 6: Execute — update election.state (only place in codebase)
        ↓
Step 7: Side effects — apply action-specific updates
        ↓
Step 8: Events — dispatch domain events (after transaction commit)
```

**Pseudocode:**

```php
public function transitionTo(Transition $transition): ElectionStateTransition
{
    $lock = Cache::lock("election_transition:{$this->id}", 10);
    
    return $lock->block(5, function () use ($transition) {
        return DB::transaction(function () {
            $freshElection = $this->fresh();  // Avoid stale data
            $fromState = $freshElection->current_state;
            
            // 1. Validate action allowed
            if (!TransitionMatrix::canPerformAction($fromState, $transition->action)) {
                throw new InvalidTransitionException(...);
            }
            
            // 2. Authorize role
            if (!$transition->isSystemTriggered()) {
                $actorRole = $freshElection->resolveActorRole($transition->actorId);
                if (!TransitionMatrix::actionRequiresRole($transition->action, $actorRole)) {
                    throw new DomainException(...);
                }
            }
            
            // 3. Validate business rules
            $freshElection->validateTransitionRules($transition);
            
            // 4. Create audit record
            $record = ElectionStateTransition::create([
                'election_id'  => $this->id,
                'from_state'   => $fromState,
                'to_state'     => TransitionMatrix::getResultingState($transition->action),
                'trigger'      => $transition->trigger->value,
                'actor_id'     => $transition->actorId === 'system' ? null : $transition->actorId,
                'reason'       => $transition->reason,
                'metadata'     => $transition->metadata,
            ]);
            
            // 5. Change state (only place)
            $this->updateQuietly(['state' => $toState]);
            
            // 6. Execute side effects (action-specific)
            match ($transition->action) {
                'open_voting'              => $this->applySideEffectsForOpenVoting(...),
                'close_voting'             => $this->applySideEffectsForCloseVoting(...),
                'approve'                  => $this->applySideEffectsForApprove(...),
                'complete_administration' => $this->applySideEffectsForCompleteAdministration(...),
                // ... more actions
            };
            
            return $record;
        });
    });
    
    // 7. Dispatch events (after transaction commit — guaranteed delivery)
    match ($transition->action) {
        'open_voting'  => event(new VotingOpened($this, ...)),
        'close_voting' => event(new VotingClosed($this, ...)),
        // ... more events
    };
}
```

### 4. **Business Rule Validation**
**File:** `app/Models/Election.php` (lines 938–1032)

Before entering each state, specific business conditions must be met:

#### Administration Phase Entry
```php
canEnterAdministrationPhase(): bool
{
    return $this->posts_count > 0
        && $this->voters_count > 0
        && $this->election_committee_members_count > 0;
}
```

#### Nomination Phase Entry
```php
canEnterNominationPhase(): bool
{
    return $this->administration_completed
        && $this->posts_count > 0
        && $this->voters_count > 0
        && $this->election_committee_members_count > 0;
}
```

#### Voting Phase Entry
```php
canEnterVotingPhase(): bool
{
    $minCandidates = config('election.min_candidates_for_voting', 1);
    $pendingCount = $this->pending_candidacies_count ?? 0;
    
    return $this->nomination_completed
        && ($this->candidates_count ?? 0) >= $minCandidates
        && $pendingCount === 0;  // No pending applications allowed
}
```

#### Counting (Results Pending) Phase Entry
```php
canEnterCountingPhase(): bool
{
    return $this->voting_ends_at && now()->gte($this->voting_ends_at)
        && $this->voting_locked
        && $this->votes_count > 0;
}
```

#### Results Phase Entry
```php
canEnterResultsPhase(): bool
{
    return $this->results_published_at !== null;
}
```

### 5. **Role Resolution & Authorization**
**File:** `app/Models/Election.php` (lines 1704–1740)

Determines the highest-priority role for a given actor in a given election:

```php
private function resolveActorRole(string $actorId): string
{
    if ($actorId === 'system') {
        return 'system';
    }

    // 1. Election-level role (highest priority)
    $electionRole = ElectionOfficer::where('user_id', $actorId)
        ->where('election_id', $this->id)
        ->where('status', 'active')
        ->value('role');

    if ($electionRole) {
        return $electionRole;  // chief, deputy, observer, etc.
    }

    // 2. Platform-level roles
    $user = User::find($actorId);
    if ($user?->isSuperAdmin()) {
        return 'super_admin';
    }
    if ($user?->platform_role === 'platform_admin') {
        return 'platform_admin';
    }

    // 3. Organization-level roles
    $orgRole = UserOrganisationRole::where('user_id', $actorId)
        ->where('organisation_id', $this->organisation_id)
        ->value('role');

    return in_array($orgRole, ['admin', 'owner']) ? $orgRole : 'observer';
}
```

**Priority hierarchy:**
1. Election-specific role (chief, deputy) — highest priority
2. Platform roles (super_admin, platform_admin)
3. Organization roles (admin, owner)
4. Default: observer (lowest priority, no special actions)

### 6. **ElectionStateTransition** — Immutable Audit Record
**File:** `app/Models/ElectionStateTransition.php`

Records every transition with complete audit information:

```php
class ElectionStateTransition extends Model
{
    protected $fillable = [
        'election_id',   // Which election
        'from_state',    // Previous state
        'to_state',      // New state
        'trigger',       // manual | time | grace_period | system
        'actor_id',      // User who initiated (null if system)
        'reason',        // Audit reason
        'metadata',      // Extra data
        'created_at',    // Timestamp
    ];

    // Immutability enforcement
    protected static function booted(): void
    {
        static::updating(fn() => throw new RuntimeException('Cannot update'));
        static::deleting(fn() => throw new RuntimeException('Cannot delete'));
    }
}
```

**Guaranteed properties:**
- ✓ Records created immutably (no updates/deletes)
- ✓ Complete chronological audit trail
- ✓ No transitions hidden or "rolled back"

### 7. **Domain Events** — After-Commit Dispatch
**File:** `app/Models/Election.php` (lines 1637–1644)

Events dispatched after transaction commit (guaranteed delivery):

| Event | Trigger | Listeners |
|-------|---------|-----------|
| `VotingOpened` | `open_voting` action | Email notifications, analytics |
| `VotingClosed` | `close_voting` action | Archive voting period, lock results |
| `ElectionApproved` | `approve` action | Update dashboard, notify chief |
| `ElectionRejected` | `reject` action | Notify election organizer |
| `ResultsPublished` | `publish_results` action | Public notification, archive |
| `ElectionStateChangedEvent` | Any other transition | General audit logging |

---

## State Approval Routing

Elections are **approved conditionally** based on expected voter capacity:

```
Election Created (state: draft)
    ↓
    Is expected_voter_count > 40 (config)?
    ├─ YES → submit_for_approval → pending_approval (manual review path)
    │         ↓
    │         Admin approves? → administration
    │         Admin rejects?  → draft (back to editing)
    │
    └─ NO  → auto_submit (system) → administration (self-service path)
```

**Config key:** `election.self_service_voter_limit` (default: 40)

---

## Voting Phase Lifecycle Details

### Entry: open_voting Action

```php
applySideEffectsForOpenVoting($actorId): void
{
    DB::table('elections')->update([
        'status'                    => 'active',
        'nomination_completed'      => true,
        'nomination_completed_at'   => now(),
        // Auto-configure dates if not set
        'voting_starts_at'          => $this->voting_starts_at ?? now(),
        'voting_ends_at'            => $this->voting_ends_at ?? now()->addDays(4),
    ]);
}
```

**Effects:**
- Election becomes "active" (voters can now submit votes)
- Nomination auto-completes
- Voting dates auto-configured if not explicitly set
- VotingOpened event dispatched

### Sub-action: lock_voting (Remains in VOTING)

```php
applySideEffectsForLockVoting(): void
{
    DB::table('elections')->update([
        'voting_locked'    => true,
        'voting_locked_at' => now(),
    ]);
}
```

**Purpose:** Marks voting period as "officially started" — after this, dates cannot be edited by chief.

**State:** Remains in "voting" (not a state change, only side effects).

### Exit: close_voting Action

```php
applySideEffectsForCloseVoting(): void
{
    DB::table('elections')->update([
        'voting_ends_at'   => now(),  // Mark closed immediately
        'voting_locked'    => true,
        'voting_locked_at' => now(),
    ]);
}
```

**Validation before exit:**
- voting_ends_at must be in the past (voting window closed)
- Can close with 0 votes (valid outcome, logged as warning)

**Target state:** results_pending

---

## Date & Timeline Management

### Timeline Validation (Two Modes)

#### Strict Mode: validateTimelineForEdit()
Used during **initial creation** and **explicit date editing** (prevents past dates):

```
✗ Voting start in past (real elections only, not tests)
✓ Voting start before end
✓ Admin before nomination (chronological order)
✓ Minimum 24-hour phases
```

#### Permissive Mode: validateTimeline()
Used during **state transitions** (allows past dates since voting may be in progress):

```
✓ Voting start before end (no past-date check)
✓ Admin before nomination (chronological order)
✓ Minimum 24-hour phases
```

### Which Validation Applied When?

| Context | Validation | Used When |
|---------|-----------|-----------|
| Creation | **Strict** | `Election::create()` |
| Edit dates | **Strict** | Controller `update()` action |
| State transition | **Permissive** | `transitionTo()` (voting may be active) |
| Boot hook | **Permissive** | Model saving (avoid breaking existing states) |

---

## Action-Specific Business Rules

### open_voting Validation

```php
private function validateOpenVoting(Transition $transition): void
{
    if ($reason = $this->whyCannotOpenVoting()) {
        throw new DomainException($reason);
    }
}

public function whyCannotOpenVoting(): ?string
{
    if (!$this->nomination_completed) {
        return 'Nomination phase has not been completed.';
    }
    if (($this->candidates_count ?? 0) === 0) {
        return 'No candidates have been registered.';
    }
    if (($this->pending_candidacies_count ?? 0) > 0) {
        return 'There are pending candidacy applications.';
    }
    if ($this->voting_starts_at && now()->lt($this->voting_starts_at)) {
        return 'Voting phase has not yet started. Scheduled start: ...';
    }
    return null;
}
```

**Blocked reasons returned to UI:**
- `nomination_incomplete`
- `no_candidates`
- `insufficient_candidates_need_N`
- `pending_applications`

### close_voting Validation

```php
private function validateCloseVoting(Transition $transition): void
{
    if ($this->voting_ends_at && $this->voting_ends_at->lt(now())) {
        if (($this->votes_count ?? 0) === 0) {
            Log::warning('Voting closed with zero votes recorded', [...]);
        }
        return; // Allow closure
    }
}
```

**Note:** Voting can be closed with **0 votes** (valid election outcome). Logged as warning for audit.

### lock_voting Validation

```php
private function validateLockVoting(Transition $transition): void
{
    if ($this->voting_locked) {
        throw new DomainException('Cannot lock voting: Voting is already locked.');
    }
}
```

---

## Capacity & Approval System

### Voter Capacity Checks

```php
// Declaration (at creation)
expected_voter_count: int   // What the organizer expects

// Runtime checks
canAcceptVoters($additionalCount): bool
{
    $newTotal = $this->getEffectiveVoterCount() + $additionalCount;
    $expectedCap = $this->expected_voter_count > 0
        ? $this->expected_voter_count
        : PHP_INT_MAX;
    
    return $newTotal <= min($expectedCap, config('election.max_voters_per_election', 10000));
}

// Throws exception
assertCanAcceptVoters($additionalCount): void
{
    if (!$this->canAcceptVoters($additionalCount)) {
        throw new DomainException('Cannot add voters: would exceed capacity...');
    }
}
```

### Approval Routing

```php
requiresApproval(): bool
{
    return ($this->expected_voter_count ?? 0) > config('election.self_service_voter_limit', 40);
}

submitForApproval($submittedBy): void
{
    if ($this->requiresApproval()) {
        $this->processManualApproval($submittedBy);      // → pending_approval
    } else {
        $this->processAutoApproval($submittedBy);        // → administration (system)
    }
}
```

---

## Audit Trail & Logging

### Dual-Write Audit Strategy

State changes recorded in **two places** for redundancy and performance:

#### 1. JSON Column: election_state_audit_log

```php
public function logStateChange(string $action, array $metadata): void
{
    $log = $this->state_audit_log ?? [];
    
    $log[] = [
        'action'    => $action,
        'metadata'  => $metadata,
        'timestamp' => now()->toIso8601String(),
    ];
    
    // Keep last 200 entries (prevent bloat)
    $log = array_slice($log, -200);
    
    $this->update(['state_audit_log' => $log]);
}
```

**Strengths:** Fast queries, single row, denormalized  
**Weakness:** Limited to 200 entries (older ones dropped)

#### 2. Audit Logs Table: election_state_transitions

```php
// Immutable records, never deleted/updated
ElectionStateTransition::create([
    'election_id' => $this->id,
    'from_state'  => $fromState,
    'to_state'    => $toState,
    'trigger'     => $transition->trigger->value,
    'actor_id'    => $transition->actorId,
    'reason'      => $transition->reason,
    'metadata'    => $transition->metadata,
]);
```

**Strengths:** Complete audit trail, immutable, queryable  
**Weakness:** Requires joins for full context

### Querying Audit Trail

```php
// Get all transitions for an election
$transitions = ElectionStateTransition::forElection($electionId)
    ->orderBy('created_at', 'desc')
    ->with(['actor:id,name,email'])
    ->get();

// Get transitions by trigger type
$manualTransitions = $transitions->where('trigger', 'manual');
$systemTransitions = $transitions->where('trigger', 'system');
$timeTransitions = $transitions->where('trigger', 'time');
```

---

## Progress & Blocked State Visualization

### Progress Tracking for UI

```php
public function getProgress(): array
{
    $states = TransitionMatrix::getAllStates();
    $currentState = $this->state ?? 'draft';
    $currentIndex = array_search($currentState, $states);
    
    return collect($states)->map(function ($state, $index) use ($currentState) {
        if ($state === $currentState) {
            return $this->progressEntry($state, 'current');  // Current step
        }
        
        if ($index < $currentIndex) {
            return $this->progressEntry($state, 'completed'); // Past steps
        }
        
        if ($state === $nextState) {
            $reason = $this->getBlockedReasonForState($state);
            return $this->progressEntry($state, 
                $reason ? 'blocked' : 'future',  // Blocked or ready
                $reason
            );
        }
        
        return $this->progressEntry($state, 'future');  // Future steps
    })->toArray();
}
```

**Returns UI-friendly structure:**

```json
[
    {
        "state": "draft",
        "label": "Draft",
        "status": "completed"
    },
    {
        "state": "administration",
        "label": "Administration",
        "status": "completed"
    },
    {
        "state": "nomination",
        "label": "Nomination",
        "status": "current"
    },
    {
        "state": "voting",
        "label": "Voting",
        "status": "blocked",
        "blockedReason": "No candidates have been registered."
    },
    {
        "state": "results_pending",
        "label": "Results Pending",
        "status": "future"
    },
    {
        "state": "results",
        "label": "Results",
        "status": "future"
    }
]
```

---

## Atomic Transition Execution

### Distributed Lock Pattern

```php
public function transitionTo(Transition $transition): ElectionStateTransition
{
    // 1. Acquire distributed lock (prevents race conditions)
    $lock = Cache::lock("election_transition:{$this->id}", 10);  // 10 sec timeout
    
    return $lock->block(5, function () {  // 5 sec wait before giving up
        // 2. Execute inside lock
        return DB::transaction(function () {
            // All reads use fresh() to avoid stale data
            $freshElection = $this->fresh();
            
            // Validation, authorization, business rules...
            // State update...
            // Side effects...
            
            // Return audit record (created inside transaction)
        });
    });
}
```

**Guarantees:**
- ✓ Only one transition at a time (distributed lock)
- ✓ All-or-nothing (transaction)
- ✓ No stale reads (fresh() reload)
- ✓ No race conditions

---

## Complete State Action Matrix

### What actions are allowed in each state?

```
draft:
  ├─ configure_election       (read/write settings)
  └─ manage_settings          (IP restrictions, etc.)

administration:
  ├─ manage_posts             (create/edit posts)
  ├─ import_voters            (bulk voter assignment)
  ├─ manage_committee         (add/remove members)
  ├─ configure_election       (still editable)
  └─ manage_settings

nomination:
  ├─ apply_candidacy          (candidates apply)
  ├─ approve_candidacy        (chief approves apps)
  ├─ view_candidates          (list approved)
  ├─ configure_election       (still editable)
  └─ manage_settings

voting:
  ├─ cast_vote                (submit votes)
  ├─ verify_vote              (check vote recorded)
  ├─ configure_election       (only if NOT locked)
  └─ manage_settings          (only if NOT locked)

results_pending:
  └─ verify_vote              (check recorded votes)

results:
  ├─ view_results             (public results)
  ├─ verify_vote              (verify own vote)
  └─ download_receipt         (receipt code proof)
```

**Key:** Some actions remain available across multiple states (e.g., verify_vote from voting → results).

---

## Implementation Patterns & Principles

### 1. Single Responsibility
- **TransitionMatrix:** Encodes all valid transitions
- **Transition:** Immutable transition value object
- **Election::transitionTo():** Orchestrates the 8-step process
- **ElectionStateTransition:** Audits all transitions

### 2. Immutability
- `Transition` is read-only (factories only)
- `ElectionStateTransition` cannot be updated/deleted
- State changes atomic (all-or-nothing via transaction)

### 3. Fail-Fast Validation
1. State machine rules (first check — cheap)
2. Role authorization (second check — identity lookup)
3. Business conditions (last check — expensive DB queries)

### 4. Audit-First
- Every transition creates immutable record
- Dual-write for resilience (JSON column + separate table)
- Domain events for workflow integration

### 5. Event-Driven
- Events dispatch **after** transaction commit (guaranteed delivery)
- Listeners decouple state machine from side effects (email, caching, etc.)
- No event failures can rollback state change

---

## Testing Strategy

### Unit Tests: TransitionMatrix

```php
test('can determine valid actions from any state')
test('rejects invalid actions')
test('maps action to correct target state')
test('role validation works')
test('boot-time validation detects config errors')
```

### Feature Tests: Election Transitions

```php
test('cannot transition when business rules unmet')
test('authorization prevents non-admin transitions')
test('audit record created for every transition')
test('distributed lock prevents race conditions')
test('events dispatched after commit')
test('state visible immediately after transition')
```

### Regression Tests: State Flows

```
draft → administration → nomination → voting → results_pending → results
draft → pending_approval → administration → ... (approval path)
draft → draft (reject path)
voting → voting (lock_voting action, no state change)
```

---

## Common Workflows

### Scenario 1: Self-Service Election (≤ 40 voters)

```
1. Create election (expected_voter_count: 35)
2. System auto-approves → draft → administration
3. Admin completes administration → nomination
4. Candidates apply, admin approves → nomination (wait state)
5. Admin opens voting → voting
6. Voting window closes, admin closes voting → results_pending
7. Admin publishes results → results (DONE)
```

### Scenario 2: Large Election (> 40 voters, manual approval)

```
1. Create election (expected_voter_count: 1500)
2. Admin submits for approval → pending_approval
3. Platform admin reviews, approves → administration
4. ... (rest same as self-service)
```

### Scenario 3: Admin Rejects Election

```
1. Create election (expected_voter_count: 100)
2. Admin submits → pending_approval
3. Platform admin rejects → draft (back to editing)
4. Admin makes changes, re-submits → pending_approval
5. Admin approves → administration
6. ... (continue)
```

### Scenario 4: Locked Voting Period

```
1. Voting open (state: voting, voting_locked: false)
2. Admin locks voting → voting (state unchanged, voting_locked: true)
3. Chief can no longer edit dates (canUpdatePhaseDates returns false)
4. Voting window ends, admin closes → results_pending
```

---

## Key Guarantees

| Guarantee | Mechanism |
|-----------|-----------|
| **No invalid transitions** | TransitionMatrix enum-validated at boot |
| **No unauthorized actions** | Role check in transitionTo() |
| **No silent business rule violations** | validateTransitionRules() throws before state change |
| **No stale reads** | fresh() reload inside lock |
| **No race conditions** | Distributed cache lock |
| **Complete audit trail** | Immutable ElectionStateTransition records |
| **No lost events** | Events dispatched after transaction commit |
| **Atomic transitions** | DB::transaction() wraps all mutations |

---

## Configuration

### Environment Variables

```php
// config/election.php
'self_service_voter_limit' => env('ELECTION_SELF_SERVICE_LIMIT', 40),
'min_candidates_for_voting' => env('ELECTION_MIN_CANDIDATES_FOR_VOTING', 1),
'max_voters_per_election' => env('ELECTION_MAX_VOTERS', 10000),
```

### Approval Thresholds

```
If expected_voter_count ≤ 40:      Auto-approve (self-service)
If expected_voter_count > 40:      Manual admin approval required
```

---

## Database Schema

### election_state_transitions Table

```sql
CREATE TABLE election_state_transitions (
    id              UUID PRIMARY KEY,
    election_id     UUID NOT NULL REFERENCES elections(id),
    from_state      VARCHAR(50) NOT NULL,
    to_state        VARCHAR(50) NOT NULL,
    trigger         VARCHAR(50) NOT NULL,           -- manual | time | grace_period | system
    actor_id        UUID,                           -- NULL if system-triggered
    reason          TEXT,
    metadata        JSON,
    created_at      TIMESTAMP NOT NULL,
    
    UNIQUE(election_id, created_at),                -- Prevents state collision
    INDEX(election_id, created_at DESC),            -- Common query pattern
    INDEX(trigger),                                 -- Filter by trigger type
);
```

### elections Table (State Machine Columns)

```
state                       VARCHAR(50)     -- Current state (draft, administration, ...)
administration_completed    BOOLEAN         -- Flag: admin phase finished?
administration_completed_at TIMESTAMP       -- When admin phase finished
nomination_completed        BOOLEAN         -- Flag: nomination phase finished?
nomination_completed_at     TIMESTAMP       -- When nomination phase finished
voting_starts_at           TIMESTAMP       -- Voting period start
voting_ends_at             TIMESTAMP       -- Voting period end
voting_locked              BOOLEAN         -- Is voting period locked?
voting_locked_at           TIMESTAMP       -- When voting locked
voting_locked_by           UUID            -- Who locked voting
results_locked             BOOLEAN         -- Are results locked?
results_locked_at          TIMESTAMP       -- When results locked
results_published          BOOLEAN         -- Are results public?
results_published_at       TIMESTAMP       -- When results published
state_audit_log            JSON            -- Last 200 state changes (denormalized)
expected_voter_count       INT             -- Capacity (used for approval routing)
```

---

## Monitoring & Operations

### Check Current State

```sql
SELECT id, name, state, voting_starts_at, voting_ends_at 
FROM elections 
WHERE organisation_id = ? 
ORDER BY created_at DESC;
```

### List All Transitions

```sql
SELECT * FROM election_state_transitions 
WHERE election_id = ? 
ORDER BY created_at DESC;
```

### Find Blocked Elections

```sql
SELECT id, name, state 
FROM elections 
WHERE state = 'nomination' 
  AND pending_candidacies_count > 0
ORDER BY created_at;
```

### Audit Who Changed States

```sql
SELECT est.created_at, est.from_state, est.to_state, u.name, est.reason
FROM election_state_transitions est
LEFT JOIN users u ON est.actor_id = u.id
WHERE est.election_id = ?
ORDER BY est.created_at DESC;
```

---

## Future Enhancements

### Phase D: Automatic Time-Based Transitions
- Cron job monitoring voting end times
- Auto-close voting when voting_ends_at reached
- Trigger: `time` (not manual)

### Phase E: Grace Periods
- Brief window after voting ends before results
- Manual review of audit logs
- Trigger: `grace_period`

### Phase F: Webhooks
- External systems notified of state changes
- `voting.opened`, `voting.closed`, `results.published` events

---

**Document Version:** 1.0  
**Last Updated:** 2026-05-18  
**Status:** Production — Election system live
