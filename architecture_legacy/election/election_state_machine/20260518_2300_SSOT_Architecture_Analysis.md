# Election SSOT Architecture Analysis & Clean Design

**Date:** 2026-05-18  
**Status:** Architectural Foundation Document  
**Scope:** Single Source of Truth Layer for Election Lifecycle  

---

## 🧠 Executive Summary

The current election system has **three competing lifecycle systems** fighting for authority:

1. **State Machine** (domain truth — intended but incomplete)
2. **Status System** (legacy workflow — `status = 'active'`)
3. **Flag System** (boolean authority — `is_active = true` by default)

**Result:** Non-deterministic behavior where UI, voting engine, and controllers see different truths.

**Solution:** Introduce **ElectionLifecycleEngine** as the single canonical truth source that computes state from multiple input signals but provides one authoritative API.

---

## 🔴 Current Architecture Problem

### Problem 1: Three Parallel Lifecycles

```yaml
LIFECYCLE 1 (State Machine):
  state: draft → administration → nomination → voting → results_pending → results
  Responsibility: Workflow validation only
  Reality: Used everywhere for truth-seeking

LIFECYCLE 2 (Status System):
  status: 'active' | 'completed' (etc.)
  Responsibility: Workflow control
  Reality: Parallel to state machine, independent logic

LIFECYCLE 3 (Flag System):
  is_active: boolean (default: true!)
  start_date / end_date windows
  Responsibility: Time-window activation
  Reality: Overrides everything, defaults to active

RESULT: 
  - Election can be state='draft' && status='active' && is_active=true
  - UI sees: "Election is running"
  - State machine sees: "Election hasn't started"
  - Voting engine confused
```

### Problem 2: Authority Conflict

```php
// Question: Is voting open?

// Answer from State Machine:
$election->state === 'voting'  // false

// Answer from Status:
$election->status === 'active'  // true

// Answer from Flag:
$election->is_active && now()->between($start, $end)  // true

// System behavior: INDETERMINATE
```

### Problem 3: Non-Deterministic Queries

```sql
-- Which elections are active?

-- Query 1 (Status):
SELECT * FROM elections WHERE status = 'active'

-- Query 2 (Flag):
SELECT * FROM elections WHERE is_active = true 
  AND now() BETWEEN start_date AND end_date

-- Query 3 (State):
SELECT * FROM elections WHERE state = 'voting'

-- Results: DIFFERENT SETS
```

---

## ✅ SSOT Architecture Solution

### Core Principle

> **The database stores facts.**  
> **The domain computes truth.**  
> **The system exposes ONE lifecycle interpretation API.**

### Architecture Layers

```
┌─────────────────────────────────────────────────────────────┐
│                    APPLICATION LAYER                         │
│  (Controllers, Vue Components, API Endpoints)               │
│  All use: election.lifecycle().compute() → Snapshot         │
└──────────────────────┬──────────────────────────────────────┘
                       │
┌──────────────────────▼──────────────────────────────────────┐
│              DOMAIN TRUTH LAYER (NEW)                        │
│  ┌──────────────────────────────────────────────────────┐   │
│  │ ElectionLifecycleEngine (THE SOURCE OF TRUTH)        │   │
│  │ - Receives: Election entity                          │   │
│  │ - Returns: ElectionLifecycleSnapshot (immutable)     │   │
│  │ - Computes: State + Permissions + Allowed Actions    │   │
│  └──────────────────────────────────────────────────────┘   │
└──────────────────────┬──────────────────────────────────────┘
                       │
┌──────────────────────▼──────────────────────────────────────┐
│         INPUT SIGNALS (Database Facts)                       │
│  ┌──────────────────────────────────────────────────────┐   │
│  │ State Machine (state = 'draft', 'voting', etc.)      │   │
│  │ Timestamps (voting_starts_at, voting_ends_at, etc.)  │   │
│  │ Verification Flags (admin_posts_verified, etc.)      │   │
│  │ Publication Flags (results_published_at, etc.)       │   │
│  │ System Rules (organisation.timezone, etc.)           │   │
│  └──────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

---

## 🏗️ Clean Architecture Design

### Layer 1: Domain (Pure DDD)

#### 1.1 ElectionLifecycleState Enum

```php
namespace App\Domain\Election\Enum;

enum ElectionLifecycleState: string
{
    case Draft = 'draft';              // Setup not started
    case Setup = 'setup';              // Setup in progress (administration + nomination)
    case ReadyForVoting = 'ready_for_voting';  // Approved candidates, ready to open voting
    case VotingActive = 'voting_active';       // Voting window is open NOW
    case Counting = 'counting';        // Voting ended, results being tallied
    case ResultsPublished = 'results_published';  // Results published to members
    case Archived = 'archived';        // Election complete, closed
    
    public function label(): string
    {
        return match($this) {
            self::Draft => 'Draft Setup',
            self::Setup => 'Setup in Progress',
            self::ReadyForVoting => 'Ready for Voting',
            self::VotingActive => 'Voting Active',
            self::Counting => 'Counting Votes',
            self::ResultsPublished => 'Results Published',
            self::Archived => 'Archived',
        };
    }
}
```

**Key:** This is the ONLY lifecycle state enum. No more `status` column, no more computed `is_active`.

---

#### 1.2 ElectionLifecycleSnapshot (Read Model / DTO)

```php
namespace App\Domain\Election\ValueObjects;

final class ElectionLifecycleSnapshot
{
    /**
     * @param array<string> $allowedActions Possible next actions (e.g., ['open_voting', 'close_voting'])
     */
    public function __construct(
        public readonly ElectionLifecycleState $state,
        
        // Permissions (can user/system do X?)
        public readonly bool $canEdit,                    // Can configuration be changed?
        public readonly bool $canVote,                    // Can voting happen now?
        public readonly bool $canManageVoters,            // Can voters be added/removed?
        public readonly bool $canPublishResults,          // Can results be published?
        
        // Locking (has this state been frozen?)
        public readonly bool $isLocked,
        
        // Why blocked? (if not allowed)
        public readonly ?string $blockedReason,
        
        // Next possible actions
        /** @var array<string> */
        public readonly array $allowedActions,
    ) {}
    
    public function isActive(): bool
    {
        return $this->state === ElectionLifecycleState::VotingActive;
    }
    
    public function isInSetup(): bool
    {
        return in($this->state, [
            ElectionLifecycleState::Draft,
            ElectionLifecycleState::Setup,
        ]);
    }
    
    public function canTransitionTo(string $action): bool
    {
        return in_array($action, $this->allowedActions);
    }
}
```

**Key:** This is the immutable truth that all layers read. Zero branching logic in consumers.

---

#### 1.3 ElectionLifecycleEngine Interface

```php
namespace App\Domain\Election\Services;

interface ElectionLifecycleEngine
{
    /**
     * Compute authoritative snapshot for this election.
     * PURE: No side effects, no queries beyond the Election entity.
     */
    public function compute(Election $election): ElectionLifecycleSnapshot;
    
    /**
     * Get only the state (lightweight, no full snapshot).
     */
    public function getState(Election $election): ElectionLifecycleState;
    
    /**
     * Validate that a transition is allowed.
     * Throws if preconditions not met.
     */
    public function assertCanTransition(
        Election $election,
        string $action,
    ): void;
}
```

---

### Layer 2: Application (Orchestration)

#### 2.1 ElectionLifecycleEngineImpl

```php
namespace App\Application\Election\Services;

final class ElectionLifecycleEngineImpl implements ElectionLifecycleEngine
{
    public function __construct(
        private readonly ElectionTimezoneResolver $timezoneResolver,
        private readonly TransitionMatrix $transitionMatrix,
    ) {}
    
    public function compute(Election $election): ElectionLifecycleSnapshot
    {
        $state = $this->deriveState($election);
        
        return new ElectionLifecycleSnapshot(
            state: $state,
            canEdit: $this->canEdit($election),
            canVote: $this->canVote($election),
            canManageVoters: $this->canManageVoters($election),
            canPublishResults: $this->canPublishResults($election),
            isLocked: $this->isLocked($election),
            blockedReason: $this->getBlockReason($election),
            allowedActions: $this->getAllowedActions($state),
        );
    }
    
    /**
     * STATE DERIVATION: The core logic that computes truth from signals.
     * 
     * Order matters:
     * 1. Terminal states override everything
     * 2. Time windows are signals, not states
     * 3. Verification flags gate transitions
     * 4. Defaults to safest state
     */
    private function deriveState(Election $election): ElectionLifecycleState
    {
        // 1. TERMINAL: Results published → archived state
        if ($election->results_published_at !== null) {
            return ElectionLifecycleState::ResultsPublished;
        }
        
        // 2. VOTING ACTIVE: Time window signal
        if ($this->isVotingActiveNow($election)) {
            return ElectionLifecycleState::VotingActive;
        }
        
        // 3. COUNTING: Voting window closed, results pending
        if ($this->isCountingNow($election)) {
            return ElectionLifecycleState::Counting;
        }
        
        // 4. READY: Setup complete, candidates approved, ready to open
        if ($this->isSetupComplete($election)) {
            return ElectionLifecycleState::ReadyForVoting;
        }
        
        // 5. DEFAULT: Still in setup (doesn't matter if draft or admin/nomination)
        return ElectionLifecycleState::Setup;
    }
    
    private function isVotingActiveNow(Election $election): bool
    {
        // Voting window is defined AND we're inside it
        if ($election->voting_starts_at === null || $election->voting_ends_at === null) {
            return false;
        }
        
        $tz = $this->timezoneResolver->resolve($election);
        $now = now()->tz($tz);
        
        return $now->between(
            $election->voting_starts_at,
            $election->voting_ends_at
        );
    }
    
    private function isCountingNow(Election $election): bool
    {
        // Voting has ended but results not yet published
        if ($election->voting_ends_at === null) {
            return false;
        }
        
        $tz = $this->timezoneResolver->resolve($election);
        $now = now()->tz($tz);
        
        return $now->gt($election->voting_ends_at)
            && $election->results_published_at === null;
    }
    
    private function isSetupComplete(Election $election): bool
    {
        // All verifications done + at least 1 approved candidate
        return $election->administration_completed
            && $election->nomination_completed
            && $election->candidates_count > 0;
    }
    
    // Permissions (based on state)
    
    private function canEdit(Election $election): bool
    {
        return in($this->deriveState($election), [
            ElectionLifecycleState::Draft,
            ElectionLifecycleState::Setup,
        ]);
    }
    
    private function canVote(Election $election): bool
    {
        return $this->deriveState($election) === ElectionLifecycleState::VotingActive;
    }
    
    private function canManageVoters(Election $election): bool
    {
        return in($this->deriveState($election), [
            ElectionLifecycleState::Draft,
            ElectionLifecycleState::Setup,
            ElectionLifecycleState::ReadyForVoting,
        ]);
    }
    
    private function canPublishResults(Election $election): bool
    {
        return $this->deriveState($election) === ElectionLifecycleState::Counting;
    }
    
    private function isLocked(Election $election): bool
    {
        return $election->voting_locked || $election->results_locked;
    }
    
    private function getBlockReason(Election $election): ?string
    {
        $state = $this->deriveState($election);
        
        return match($state) {
            ElectionLifecycleState::Draft => 'Election setup not started',
            ElectionLifecycleState::Setup => 'Election setup incomplete',
            ElectionLifecycleState::ReadyForVoting => 'Voting period not yet open',
            ElectionLifecycleState::VotingActive => null, // Not blocked
            ElectionLifecycleState::Counting => 'Voting has ended',
            ElectionLifecycleState::ResultsPublished => 'Election complete',
            ElectionLifecycleState::Archived => 'Election archived',
        };
    }
    
    private function getAllowedActions(ElectionLifecycleState $state): array
    {
        return match($state) {
            ElectionLifecycleState::Draft => [
                'submit_for_approval',
                'delete',
            ],
            ElectionLifecycleState::Setup => [
                'add_voters',
                'add_posts',
                'add_candidates',
                'complete_administration',
                'open_voting',  // If setup complete
            ],
            ElectionLifecycleState::ReadyForVoting => [
                'open_voting',
                'modify_voting_window',
            ],
            ElectionLifecycleState::VotingActive => [
                'close_voting',
            ],
            ElectionLifecycleState::Counting => [
                'publish_results',
            ],
            ElectionLifecycleState::ResultsPublished => [
                'archive',
            ],
            ElectionLifecycleState::Archived => [],
        };
    }
    
    public function assertCanTransition(Election $election, string $action): void
    {
        $snapshot = $this->compute($election);
        
        if (!in_array($action, $snapshot->allowedActions)) {
            throw new InvalidTransitionException(
                "Cannot perform '{$action}' in state '{$snapshot->state->value}'. "
                . "Reason: {$snapshot->blockedReason}"
            );
        }
    }
}
```

---

### Layer 3: Infrastructure (Persistence)

#### 3.1 Service Registration

```php
// app/Providers/AppServiceProvider.php
$this->app->bind(
    ElectionLifecycleEngine::class,
    ElectionLifecycleEngineImpl::class
);
```

---

### Layer 4: Interface (HTTP/UI)

#### 4.1 Controller Usage

```php
// app/Http/Controllers/Election/ElectionViewController.php

final class ElectionViewController
{
    public function __construct(
        private readonly ElectionLifecycleEngine $engine,
    ) {}
    
    public function show(Election $election)
    {
        $snapshot = $this->engine->compute($election);
        
        return inertia('Election/Show', [
            'election' => $election,
            'lifecycle' => [
                'state' => $snapshot->state->value,
                'isActive' => $snapshot->isActive(),
                'canVote' => $snapshot->canVote,
                'allowedActions' => $snapshot->allowedActions,
                'blockedReason' => $snapshot->blockedReason,
            ],
        ]);
    }
}
```

#### 4.2 Vue Component Usage

```vue
<template>
  <div>
    <ElectionHeader :election="election" />
    
    <!-- Show state badge -->
    <div class="badge" :class="`badge--${lifecycle.state}`">
      {{ $t(`election.state.${lifecycle.state}`) }}
    </div>
    
    <!-- Only show vote button if voting is active -->
    <button v-if="lifecycle.canVote" @click="startVoting">
      Cast Vote
    </button>
    
    <!-- Show blocked reason if voting not allowed -->
    <div v-if="!lifecycle.canVote && lifecycle.blockedReason" class="alert">
      {{ lifecycle.blockedReason }}
    </div>
  </div>
</template>

<script setup>
const { election, lifecycle } = defineProps(['election', 'lifecycle']);
</script>
```

---

## 🗄️ Database Schema (Unchanged)

The beauty of SSOT: **No database schema changes needed** for Phase 1.

The `elections` table keeps:
- `state` (state machine)
- `voting_starts_at`, `voting_ends_at`
- `administration_completed`, `nomination_completed`
- `results_published_at`
- All verification flags

**Future (Phase 5):** After proven, remove `status` and `is_active` columns.

---

## 🧪 Testing Strategy

### Unit Tests (ElectionLifecycleEngineImpl)

```php
tests/Unit/Domain/Election/ElectionLifecycleEngineTest.php

- derives_draft_state_for_new_election
- derives_setup_state_when_admin_started
- derives_ready_for_voting_when_setup_complete
- derives_voting_active_when_in_time_window
- derives_counting_when_voting_ended
- derives_results_published_when_published_at_set
- snapshot_allows_correct_actions_per_state
- blocking_reason_clear_when_not_allowed
- timezone_respected_in_window_checks
```

### Integration Tests (Controllers)

```php
tests/Feature/Election/ElectionLifecycleIntegrationTest.php

- vote_button_shows_only_when_voting_active
- edit_form_disabled_in_counting_state
- results_publishable_only_in_counting_state
- archived_election_read_only
```

---

## 🔀 Migration Path (Safe)

### Phase 1: Introduce SSOT (Parallel read)
- Add `ElectionLifecycleEngine`
- Controllers compute snapshot
- Vue receives snapshot
- Keep `status` and `is_active` for rollback safety

### Phase 2: Replace Consumers
- All queries use engine
- All UI uses snapshot
- Controllers use snapshot

### Phase 3: Deprecate Legacy
- Mark columns deprecated
- Add warnings in code

### Phase 4: Remove Legacy
- Delete `status` column
- Delete `is_active` column
- Remove activate() controller

---

## 🎯 Key Benefits

1. **Single Truth:** One place to answer "Is voting open?"
2. **Testable:** Pure function, no hidden state
3. **Deterministic:** Same input always produces same output
4. **DDD-Aligned:** Domain logic in domain layer
5. **Safe Migration:** Dual-read period ensures rollback safety
6. **Time-Aware:** Timezone-respecting windows built in
7. **Extensible:** New rules added to engine, not scattered across code

---

## ⚠️ Critical Rules

1. **ElectionLifecycleEngine is pure:** No side effects, no queries beyond Election entity
2. **Snapshot is immutable:** Read-only DTO
3. **No direct field access in business logic:** Always use `lifecycle().compute()`
4. **State derivation order matters:** Terminal states first, time windows second
5. **Database has facts, domain has truth:** Keep separate mentally

---

**This design makes the system constitutionally consistent, not just refactored.**
