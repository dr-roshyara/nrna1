# Election SSOT Refactoring Plan
**Type:** Constitutional Architecture Stabilization (DDD + SSOT)  
**Scope:** Replace three parallel lifecycle systems with single source of truth  
**Approach:** TDD-First · Strangler Fig (Dual-Read) · Zero Regression  
**Date:** 2026-05-18  
**Total Estimated Time:** ~20 hours (safe, zero-downtime)

---

## Context

Current system has **three competing lifecycle systems** creating non-deterministic behavior:

1. **State Machine** (`state = 'draft'...`'voting'...`) — intended truth, incomplete
2. **Status System** (`status = 'active'`) — legacy workflow controller
3. **Flag System** (`is_active = true` by default) — boolean override

**Problem:** Election can be `state='draft' && status='active' && is_active=true` simultaneously.

**Solution:** Introduce `ElectionLifecycleEngine` as the single authoritative truth source that computes state from multiple input signals but provides one API.

---

## Architecture Overview

```
┌─────────────────────────────────────────────────────┐
│  APPLICATION (Controllers, Vue, API)                │
│  All ask: election.lifecycle().compute()            │
└─────────────────┬───────────────────────────────────┘
                  │
┌─────────────────▼───────────────────────────────────┐
│  DOMAIN TRUTH LAYER (NEW)                           │
│  ┌──────────────────────────────────────────────┐   │
│  │ ElectionLifecycleEngine                      │   │
│  │ - Computes: ElectionLifecycleSnapshot        │   │
│  │ - State + Permissions + Allowed Actions      │   │
│  └──────────────────────────────────────────────┘   │
└─────────────────┬───────────────────────────────────┘
                  │
┌─────────────────▼───────────────────────────────────┐
│  INPUT SIGNALS (Database Facts)                     │
│  ├─ state machine (state column)                    │
│  ├─ timestamps (voting_starts_at, voting_ends_at)   │
│  ├─ verification flags                              │
│  ├─ publication flags (results_published_at)        │
│  └─ legacy flags (status, is_active) [temp]         │
└─────────────────────────────────────────────────────┘
```

---

## Phase 0: Safety Net (COMPLETED)

**File:** `tests/Feature/Election/StateMachine/CurrentBehaviorTest.php`

**Tests:**
- ✅ draft_to_administration_auto_approves_when_voters_under_limit
- ✅ draft_to_pending_approval_when_voters_over_limit
- ✅ administration_to_nomination_requires_posts_voters_committee
- ✅ nomination_to_voting_requires_approved_candidates
- ✅ voting_to_results_pending_requires_end_date
- ✅ results_pending_to_results_requires_publication
- ✅ all_seven_states_are_accessible_via_factory
- ✅ transition_matrix_defines_valid_paths

**Status:** Baseline locked. All subsequent phases must maintain these tests passing.

---

## Phase 1: Introduce SSOT Layer (Priority 1)

**Goal:** Implement the single source of truth layer. No removals yet. Parallel read period.

### 1.1 Create Domain Enums & Value Objects

**Files to create:**

```
app/Domain/Election/Enum/ElectionLifecycleState.php
app/Domain/Election/ValueObjects/ElectionLifecycleSnapshot.php
```

**RED Tests (write first):**

```php
tests/Unit/Domain/Election/ElectionLifecycleStateTest.php
- enum_has_seven_cases
- enum_draft_case_has_correct_value
- enum_voting_active_case_has_correct_value
- label_returns_human_readable_string

tests/Unit/Domain/Election/ElectionLifecycleSnapshotTest.php
- snapshot_is_immutable
- can_vote_true_in_voting_active_state
- can_vote_false_in_draft_state
- allowed_actions_empty_for_archived
- blocked_reason_set_when_canvote_false
- is_active_returns_true_only_for_voting_active_state
```

**Production Code:**

```php
// app/Domain/Election/Enum/ElectionLifecycleState.php
enum ElectionLifecycleState: string
{
    case Draft = 'draft';
    case Setup = 'setup';
    case ReadyForVoting = 'ready_for_voting';
    case VotingActive = 'voting_active';
    case Counting = 'counting';
    case ResultsPublished = 'results_published';
    case Archived = 'archived';
    
    public function label(): string { ... }
}

// app/Domain/Election/ValueObjects/ElectionLifecycleSnapshot.php
final class ElectionLifecycleSnapshot
{
    public function __construct(
        public readonly ElectionLifecycleState $state,
        public readonly bool $canEdit,
        public readonly bool $canVote,
        public readonly bool $canManageVoters,
        public readonly bool $canPublishResults,
        public readonly bool $isLocked,
        public readonly ?string $blockedReason,
        public readonly array $allowedActions,
    ) {}
    
    public function isActive(): bool { ... }
    public function canTransitionTo(string $action): bool { ... }
}
```

---

### 1.2 Create ElectionLifecycleEngine Interface

**File:**
```
app/Domain/Election/Services/ElectionLifecycleEngine.php
```

**RED Tests:**

```php
tests/Unit/Domain/Election/ElectionLifecycleEngineTest.php
- interface_requires_compute_method
- interface_requires_get_state_method
- interface_requires_assert_can_transition_method
```

**Production Code:**

```php
interface ElectionLifecycleEngine
{
    public function compute(Election $election): ElectionLifecycleSnapshot;
    public function getState(Election $election): ElectionLifecycleState;
    public function assertCanTransition(Election $election, string $action): void;
}
```

---

### 1.3 Create ElectionLifecycleEngineImpl

**File:**
```
app/Application/Election/Services/ElectionLifecycleEngineImpl.php
```

**RED Tests (comprehensive):**

```php
tests/Unit/Application/Election/ElectionLifecycleEngineImplTest.php

STATE DERIVATION:
- derives_draft_state_for_new_election
- derives_setup_state_when_election_in_administration
- derives_ready_for_voting_when_setup_complete
- derives_voting_active_when_current_time_in_voting_window
- derives_counting_when_voting_ended_results_not_published
- derives_results_published_when_results_published_at_set

PERMISSIONS:
- can_edit_true_only_in_draft_and_setup
- can_vote_true_only_in_voting_active
- can_manage_voters_in_draft_setup_ready
- can_publish_results_only_in_counting

BLOCKING:
- blocked_reason_explains_why_action_not_allowed
- allowed_actions_empty_when_archived

TIMEZONE HANDLING:
- voting_active_check_uses_organisation_timezone
- time_window_comparison_respects_timezone

TRANSITION VALIDATION:
- assert_can_transition_throws_for_invalid_action
- assert_can_transition_passes_for_valid_action
```

**Production Code:**

```php
final class ElectionLifecycleEngineImpl implements ElectionLifecycleEngine
{
    public function __construct(
        private readonly ElectionTimezoneResolver $timezoneResolver,
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
    
    private function deriveState(Election $election): ElectionLifecycleState
    {
        // See SSOT_Architecture_Analysis.md for detailed derivation logic
        // Key principle: Terminal states first, time windows second, defaults last
        ...
    }
    
    // canEdit(), canVote(), canManageVoters(), etc.
    ...
}
```

---

### 1.4 Register in Service Provider

**File:** `app/Providers/AppServiceProvider.php`

```php
public function register(): void
{
    $this->app->bind(
        ElectionLifecycleEngine::class,
        ElectionLifecycleEngineImpl::class
    );
}
```

---

### 1.5 Create Constitutional Transition Guard (Critical for Production Safety)

**Files to create:**

```
app/Domain/Election/ElectionConstitution.php
app/Domain/Election/Services/TransitionGuard.php
app/Application/Election/Services/ConstitutionalTransitionGuardImpl.php
```

**Purpose:** Enforce hard gate that prevents illegal transitions. No state change happens unless constitutionally valid.

**RED Tests:**

```php
tests/Unit/Domain/Election/ElectionConstitutionTest.php
- rules_define_all_known_actions
- rules_reference_valid_states
- rules_reference_valid_requirements

tests/Unit/Application/Election/ConstitutionalTransitionGuardTest.php
- assert_allowed_passes_for_valid_transition
- assert_allowed_throws_for_invalid_state
- assert_allowed_throws_for_missing_prerequisites
- why_not_allowed_explains_missing_requirements
- guard_requires_voting_window_defined_before_open_voting
- guard_requires_approved_candidates_before_open_voting
```

**Production Code:**

```php
// app/Domain/Election/ElectionConstitution.php
final class ElectionConstitution
{
    public const RULES = [
        'submit_for_approval' => [
            'allowed_states' => ['draft'],
            'requires' => [],
        ],
        'complete_administration' => [
            'allowed_states' => ['setup'],
            'requires' => [
                'administration_completed',
                'administration_posts_verified',
                'administration_voters_verified',
                'administration_committee_verified',
            ],
        ],
        'open_voting' => [
            'allowed_states' => ['ready_for_voting'],
            'requires' => [
                'nomination_completed',
                'voting_window_defined',
                'has_approved_candidates',
            ],
        ],
        // ... etc (see Constitutional_Transition_Guard_Layer.md)
    ];
}

// app/Domain/Election/Services/TransitionGuard.php
interface TransitionGuard
{
    public function assertAllowed(
        Election $election,
        string $action,
        ElectionLifecycleSnapshot $snapshot
    ): void;
    
    public function whyNotAllowed(
        Election $election,
        string $action,
        ElectionLifecycleSnapshot $snapshot
    ): array;
}

// app/Application/Election/Services/ConstitutionalTransitionGuardImpl.php
final class ConstitutionalTransitionGuardImpl implements TransitionGuard
{
    public function assertAllowed(...) { ... }
    private function evaluateRequirement(...) { ... }
}
```

---

### 1.6 Register Guard in Service Provider

**File:** `app/Providers/AppServiceProvider.php`

Add to `register()` method:

```php
$this->app->bind(
    \App\Domain\Election\Services\TransitionGuard::class,
    \App\Application\Election\Services\ConstitutionalTransitionGuardImpl::class
);
```

---

### 1.7 Update Factory to Support New States

**File:** `database/factories/ElectionFactory.php`

**Add factory state for results:**

```php
public function inResultsState()
{
    return $this->state(function (array $attributes) {
        return [
            'state' => 'results',
            'voting_locked' => true,
            'results_locked' => true,
            'results_published_at' => now(),
            'results_published_by' => fake()->uuid(),
        ];
    });
}
```

---

## Phase 2: Deprecate Legacy Truth Sources (Dual-Read)

**Goal:** Mark legacy columns as deprecated. No removals yet. System starts using Engine.

### 2.1 Mark Columns Deprecated

**Files to modify:**

```php
// app/Models/Election.php

/**
 * @deprecated Use ElectionLifecycleEngine instead
 * See: architecture/election/election_state_machine/20260518_2300_SSOT_Architecture_Analysis.md
 */
private bool $is_active;

/**
 * @deprecated Use ElectionLifecycleEngine instead
 */
private string $status;
```

---

### 2.2 Add Deprecation Helpers

```php
// app/Models/Election.php

/**
 * @deprecated Use $election->lifecycle()->compute()->isActive() instead
 */
public function isActive(): bool
{
    // Keep working for backward compatibility during Phase 2
    return $this->is_active && $this->isCurrentlyActive();
}
```

---

## Phase 3: Migrate Consumers (Replace all readers)

**Goal:** All business logic, controllers, and UI use ElectionLifecycleEngine.

### 3.1 Migrate Controllers

**Files to modify:**

```
app/Http/Controllers/Election/ElectionViewController.php
app/Http/Controllers/Election/ElectionVotingController.php
app/Http/Controllers/Election/ElectionManagementController.php
app/Http/Controllers/Api/Elections/VoteController.php
```

**Pattern:**

```php
// BEFORE (using legacy fields):
if ($election->is_active && $election->status === 'active') {
    return view('voting', ['canVote' => true]);
}

// AFTER (using Engine):
$snapshot = $this->engine->compute($election);

return view('voting', [
    'canVote' => $snapshot->canVote,
    'blockedReason' => $snapshot->blockedReason,
    'allowedActions' => $snapshot->allowedActions,
]);
```

---

### 3.2 Migrate Middleware

**File:** `app/Http/Middleware/EnsureElectionActive.php`

```php
// BEFORE:
if (!$election->is_active) {
    abort(403);
}

// AFTER:
$snapshot = $this->engine->compute($election);
if (!$snapshot->canVote) {
    abort(403, $snapshot->blockedReason);
}
```

---

### 3.3 Migrate Vue Components

**Files to modify:**

```
resources/js/Pages/Organisations/Show.vue
resources/js/Pages/Election/Dashboard.vue
resources/js/Pages/Voting/VotingForm.vue
resources/js/Components/Election/ElectionCard.vue
```

**Pattern:**

```vue
<!-- BEFORE: -->
<button v-if="election.is_active && election.status === 'active'" @click="vote">
  Vote
</button>

<!-- AFTER: -->
<button v-if="lifecycle.canVote" @click="vote">
  Vote
</button>

<div v-if="!lifecycle.canVote && lifecycle.blockedReason" class="alert">
  {{ lifecycle.blockedReason }}
</div>
```

---

### 3.4 RED Tests for Consumers

```php
tests/Feature/Election/ElectionLifecycleConsumerTest.php

CONTROLLERS:
- voting_controller_uses_engine_not_is_active_flag
- election_view_receives_lifecycle_snapshot
- api_election_show_returns_lifecycle_state

MIDDLEWARE:
- ensure_active_middleware_uses_engine
- middleware_rejects_with_blocked_reason

UI:
- vote_button_hidden_when_snapshot_says_cannot_vote
- blocked_reason_displayed_when_provided
- allowed_actions_rendered_from_snapshot
```

---

## Phase 4: Remove Legacy Truth Sources (Cleanup)

**Goal:** Delete `status` and `is_active` columns. No more rollback path.

### 4.1 Create Migration to Remove Columns

**File:** `database/migrations/2026_05_20_000001_remove_legacy_lifecycle_columns.php`

```php
Schema::table('elections', function (Blueprint $table) {
    $table->dropColumn('is_active');
    $table->dropColumn('status');
});
```

---

### 4.2 Clean Up Model

**File:** `app/Models/Election.php`

Remove:
- `is_active` property
- `status` property
- `isActive()` method
- `status` relationship
- Deprecated comments

---

### 4.3 Remove activate() Controller

**File:** `app/Http/Controllers/Election/ElectionManagementController.php`

Delete:
- `activate()` method (replace with state transition)

---

---

## Phase 5: State Machine Becomes Validator Only

**Goal:** Demote state machine from truth-seeking to transition validation.

### 5.1 Update TransitionMatrix

```php
// app/Domain/Election/StateMachine/TransitionMatrix.php

// BEFORE: Used to answer "what can I do now?"
// AFTER: Used to validate "is this transition allowed?"

public function isValidTransition(string $fromState, string $action): bool
{
    // Only validates: does this action exist for this state?
    // Permission checking moved to ElectionLifecycleEngine
}
```

---

### 5.2 Remove State-Seeking Queries

**Pattern:**

```php
// BEFORE (bad - consulting state machine for truth):
if ($election->state === 'voting') {
    allowVote();
}

// AFTER (good - state machine only validates):
$transitionMatrix->isValidTransition('voting', 'close_voting');
```

---

## Phase 6: Add Temporal Rules (from original Stream 1)

**Goal:** Add timezone support and clock abstraction.

### 6.1 Timezone Columns

**Migration:** `database/migrations/2026_05_20_000002_add_timezone_to_organisations_and_elections.php`

```php
Schema::table('organisations', fn($t) => $t->string('timezone')->default('UTC')->after('country_code'));
Schema::table('elections', fn($t) => $t->string('timezone')->nullable()->after('voting_ends_at'));
```

---

### 6.2 Clock Abstraction

```
app/Domain/Election/Contracts/Clock.php
app/Infrastructure/Clock/SystemClock.php
app/Infrastructure/Clock/TestClock.php
app/Infrastructure/Election/ElectionTimezoneResolver.php
```

---

### 6.3 Update ElectionLifecycleEngineImpl to Use Clock

```php
// Already done in Phase 1.3 — just use timezone resolver
$now = now()->tz($this->timezoneResolver->resolve($election));
```

---

## Phase 7: Add Verification System (from original Stream 3)

**Goal:** Normalized workflow checkpoints replacing nullable timestamp explosion.

### 7.1 Create election_phase_verifications Table

**Migration:** `database/migrations/2026_05_20_000003_create_election_phase_verifications_table.php`

```php
Schema::create('election_phase_verifications', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->uuid('election_id');
    $table->string('phase', 50);  // administration, nomination, voting
    $table->string('verification_type', 80);  // posts_verified, candidates_verified, etc.
    $table->uuid('verified_by')->nullable();
    $table->timestamp('verified_at');
    $table->json('metadata')->nullable();
    $table->timestamps();
    $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
    $table->unique(['election_id', 'phase', 'verification_type']);
    $table->index(['election_id', 'phase']);
});
```

---

### 7.2 Create ElectionPhaseVerification Model

```
app/Models/ElectionPhaseVerification.php
```

---

### 7.3 Create ElectionPhaseVerificationPolicy

```
app/Domain/Election/Policies/ElectionPhaseVerificationPolicy.php
```

---

### 7.4 Update ElectionLifecycleEngineImpl

```php
// Use verification policy in deriveState():
private function isSetupComplete(Election $election): bool
{
    return $this->verificationPolicy->isAdministrationComplete($election)
        && $this->verificationPolicy->isNominationReady($election);
}
```

---

## 🧪 Testing Strategy (All Phases)

### Unit Tests

```
tests/Unit/Domain/Election/
├── ElectionLifecycleStateTest.php
├── ElectionLifecycleSnapshotTest.php
├── ElectionLifecycleEngineTest.php
└── Clock/
    ├── SystemClockTest.php
    ├── TestClockTest.php
    └── ElectionTimezoneResolverTest.php
```

### Integration Tests

```
tests/Feature/Election/
├── ElectionLifecycleIntegrationTest.php
├── StateMachine/CurrentBehaviorTest.php
├── Controllers/ElectionLifecycleConsumerTest.php
└── Verification/ElectionPhaseVerificationTest.php
```

### Zero-Regression Protocol

**Run after each phase:**

```bash
php artisan test tests/Feature/Election/StateMachine/CurrentBehaviorTest.php
php artisan test tests/Unit/Domain/Election/
php artisan test  # Full suite
```

---

## Execution Checklist

### Phase 1 Definition of Done
- [ ] ElectionLifecycleState enum tests GREEN
- [ ] ElectionLifecycleSnapshot tests GREEN
- [ ] ElectionLifecycleEngine tests GREEN
- [ ] ElectionConstitution rules registry created
- [ ] TransitionGuard interface + implementation tests GREEN
- [ ] Guard enforces all constitutional rules
- [ ] Guard prevents illegal transitions
- [ ] Guard explains why transitions blocked
- [ ] Factory inResultsState() method added
- [ ] Guard registered in AppServiceProvider
- [ ] All 8 Phase 0 tests still GREEN
- [ ] Zero regressions in full suite

### Phase 2 Definition of Done
- [ ] Deprecation comments added
- [ ] isActive() deprecation helper works
- [ ] Phase 0 tests still GREEN

### Phase 3 Definition of Done
- [ ] All controllers migrated to use Engine
- [ ] All middleware updated
- [ ] Vue components updated
- [ ] Consumer tests GREEN
- [ ] Phase 0 tests still GREEN
- [ ] Full test suite GREEN

### Phase 4 Definition of Done
- [ ] Migration runs cleanly
- [ ] Model cleaned
- [ ] activate() controller removed
- [ ] Phase 0 tests still GREEN

### Phase 5 Definition of Done
- [ ] State machine demoted to validator
- [ ] No state-seeking queries remain
- [ ] Phase 0 tests still GREEN

### Phase 6 Definition of Done
- [ ] Timezone migrations run
- [ ] Clock abstraction working
- [ ] ElectionTimezoneResolver integrated
- [ ] Phase 0 tests still GREEN

### Phase 7 Definition of Done
- [ ] Verification table created
- [ ] PhaseVerification model created
- [ ] VerificationPolicy implemented
- [ ] ElectionLifecycleEngine uses policy
- [ ] Phase 0 tests still GREEN
- [ ] Full suite GREEN

---

## Anti-Patterns Explicitly Avoided

| Anti-Pattern | Solution |
|---|---|
| Removing legacy before SSOT proven | Dual-read period (Phase 2-3) ensures safety |
| Big-bang refactor | Seven independent phases, each shippable |
| Distributed business logic | ElectionLifecycleEngine centralizes all rules |
| Hidden state in queries | Explicit Snapshot DTO with no branching |
| State machine as truth source | State machine only validates transitions |

---

## Key Principles

1. **SSOT First:** Introduce Engine before removing legacy
2. **Dual-Read Safe:** Keep old columns during Phase 2-3 for rollback
3. **TDD-First:** RED tests written before production code each phase
4. **Zero Regression:** Phase 0 tests never fail, full suite never fails
5. **DDD-Aligned:** Domain logic in Domain layer, not scattered
6. **Time-Aware:** Timezone handling built into core logic
7. **Extensible:** New rules added to Engine, not scattered across code

---

## Timeline Estimate

| Phase | Work | Estimate |
|-------|------|----------|
| Phase 0 | Safety Net | 1 h (complete) |
| Phase 1 | SSOT Layer | 4 h |
| Phase 2 | Deprecate Legacy | 1 h |
| Phase 3 | Migrate Consumers | 4 h |
| Phase 4 | Remove Legacy | 1 h |
| Phase 5 | Validate SM | 1 h |
| Phase 6 | Temporal Rules | 3 h |
| Phase 7 | Verification System | 2 h |
| **Total** | | **~20 h** |

---

## Why This Order?

1. **Phase 0 → 1:** Establish baseline, then build truth layer
2. **Phase 1 → 2 → 3:** Introduce new system, mark old as deprecated, gradually migrate
3. **Phase 3 → 4:** Only remove after all consumers migrated
4. **Phase 4 → 5:** Clean up, then demote state machine
5. **Phase 5 → 6 → 7:** Add advanced features (temporal, verification) on solid SSOT foundation

This is **constitutional reform of a domain system, not just cleanup.**

---

**Ready to execute Phase 1. Proceed with RED tests first.**
