# Phase 3.2–3.3 State Machine Hardening: From Blocking Mutations to Sovereign Governance
**Type:** Architectural evolution + controlled authority boundary
**Scope:** Establish constitutional authority over state transitions (not block them, govern them)
**Date:** 2026-05-20 (Phase 3.2 Level 1 context)
**Goal:** Move from "advisory enforcement" to "sovereign constitutional governance" — ConstitutionalTransitionGuard controls ALL state mutations through authorized context, not exceptions

---

## Problem Statement: Split-Brain Persistence

**Architectural fracture line:** State persistence has authority, but constitutional governance doesn't.

Elections are stuck in inconsistent states because:
1. **Persistence layer** (`election.state`, `election.status`, `election.is_active`) has write authority
2. **Constitutional engine** (`ElectionLifecycleState`) can only observe and derive
3. **Console commands** mutate persistence directly, bypassing all governance
4. **ConstitutionalTransitionGuard** is advisory, not sovereign

**Real issue:** State is stored as a *cached interpretation*. Business facts (timestamps, completion flags) are authoritative. When persistence mutates state independently, it breaks the cache without updating the facts.

**Example:** 
```
election test-election-1779056138:
  FACTS (authoritative):
    administration_completed = false
    nomination_completed = false
    voting_starts_at = null
    voting_ends_at = null
  
  CACHED STATE (interpretation):
    DB.state = "import_voters" (stale cache)
    DB.status = "active" (stale cache)
  
  DERIVED STATE (what engine computes from facts):
    state = "draft" (correct)
    canVote = false (correct)
```

The cache diverged from facts because persistence has authority. Governance has none.

**Root cause:** ConstitutionalTransitionGuard cannot prevent mutations — it can only observe them.

---

## Vulnerability Audit

### 1. Direct State Mutations (CRITICAL)

**File:** `app/Console/Commands/ActivateElectionCommand.php`
```php
$election->update(['state' => 'administration']);  // BYPASSES ConstitutionalTransitionGuard
```
**Risk:** State mutation with zero enforcement checks
**Impact:** Elections can enter invalid states
**Guard Status:** NONE — direct DB update

**File:** `app/Console/Commands/BackfillElectionState.php`
```php
->update(['state' => $state]);  // Bulk mutation without validation
```
**Risk:** Batch operations creating inconsistent data
**Impact:** Data corruption at scale
**Guard Status:** NONE

### 2. Status/Is_Active Mutations (HIGH)

**File:** Multiple locations
```php
$election->update(['status' => 'active']);
$election->update(['is_active' => true]);
$election->status = 'active'; $election->save();
```
**Risk:** Old state machine can change independently
**Impact:** Divergence between old and new systems
**Guard Status:** NONE

### 3. Timestamp-Based State Transitions (MEDIUM)

Multiple places set `voting_starts_at`, `voting_ends_at`, `results_published_at` directly, which implicitly change SSOT state via `ElectionLifecycleEngine::getState()`. The UI may then read the old `election.state` column instead of the computed state.

**Risk:** UI shows stale state column while engine computes different state
**Impact:** Management page displays wrong actions
**Guard Status:** PARTIAL — no explicit ConstitutionalTransitionGuard check before timestamp updates

---

## Hardening Strategy: From Blocking to Sovereignty

**Key shift:** Move from "hard exceptions" to "authorized context boundaries."

The goal is **sovereign governance over mutations**, not prevention.

### Phase 1: Establish Authorized Write Boundary (IMMEDIATE)

#### 1.1 Introduce ElectionStateWriteContext (Authorized Boundary, Not Exception)

**Three Architectural Shifts:**

1. **Controlled Write Barrier** — Authorized writes with logging, not hard blocks
2. **State as Cached Interpretation** — Business facts (timestamps, completion flags) are authoritative; `election.state` is derived cache
3. **Graduated Enforcement** — Level 1 records violations silently, Level 4 throws exceptions

**Problem with hard exceptions:** Breaks hydration, factories, migrations, queue deserialization.

**Solution:** Controlled write barrier with authorized context. Elections reach correct state through ConstitutionalTransitionGuard, not by directly mutating the state column.

```php
// app/Application/Election/Governance/ElectionStateWriteContext.php
final class ElectionStateWriteContext
{
    private static ?bool $isAuthorized = false;

    /**
     * Execute a closure with state write authority.
     * Only ConstitutionalTransitionGuard can authorize writes.
     * 
     * NEVER use this directly. Always route through ConstitutionalTransitionGuard.
     */
    public static function authorize(\Closure $callback): mixed
    {
        $previous = self::$isAuthorized;
        self::$isAuthorized = true;
        
        try {
            return $callback();
        } finally {
            self::$isAuthorized = $previous;
        }
    }

    /**
     * Check if current context is authorized to mutate election state.
     */
    public static function isAuthorized(): bool
    {
        return self::$isAuthorized === true;
    }

    /**
     * Record unauthorized mutation attempt (violation).
     * Called at Level 1 (metrics strict) and above.
     */
    public static function recordViolation(string $field, string $context): void
    {
        app(\App\Application\Election\Monitoring\ConstitutionalMetricsContract::class)
            ->recordUnauthorizedStateMutation($field, $context);
    }
}
```

**In Election model mutator:**
```php
// app/Models/Election.php
public function setStateAttribute($value)
{
    // Check if this write is authorized (from ConstitutionalTransitionGuard or bootstrap)
    if (!ElectionStateWriteContext::isAuthorized()) {
        // At Level 1+ (metrics strict): record violation
        if (DeprecationPolicy::isEnforcementActive(1)) {
            ElectionStateWriteContext::recordViolation(
                'state',
                debug_backtrace()[1]['function'] ?? 'unknown'
            );
        }
        
        // At Level 4 (full strict): throw exception
        if (DeprecationPolicy::isEnforcementActive(4)) {
            throw new UnauthorizedStateMutationException(
                'State mutation requires ConstitutionalTransitionGuard authorization. '
                . 'State should be derived from business facts, not directly mutated.'
            );
        }
    }
    
    // Mutation allowed (either authorized or Level < 1)
    $this->attributes['state'] = $value;
}
```

**Governance Authority Flow:**

```
User Action
    ↓
ConstitutionalTransitionGuard (validates → authorizes transition)
    ↓
ElectionStateWriteContext::authorize() (opens write boundary)
    ↓
Election model saves new state (mutator allows it)
    ↓
ConstitutionalMetrics records (via recordViolation if not authorized)
```

**Why this approach:**
- Allows bootstrap operations (hydration, factories, migrations, queue) to work without friction
- **Records ALL unauthorized attempts** for metrics and audit trail
- **Graduated enforcement:** Level 1 silently logs, Level 4 throws exceptions
- **Sovereignty without breaking:** Only ConstitutionalTransitionGuard opens the barrier
- **No state divergence:** Business facts remain authoritative; state column is computed cache that can only be updated through governance

#### 1.2 Deprecate All Old State Columns

**CRITICAL:** The `state` column is a **cached interpretation**, not authoritative. Business facts (timestamps, completion flags) are the true SSOT.

Mark all old state columns as deprecated in DeprecationPolicy:

```php
// app/Application/Election/Deprecation/DeprecationPolicy.php
public const FIELDS = [
    'status' => [
        'severity' => 'warning',
        'replacement' => 'ElectionLifecycle::of($election)->state()->value',
        'reasoning' => 'Old state machine; use SSOT engine instead',
    ],
    'is_active' => [
        'severity' => 'strict',
        'replacement' => 'ElectionLifecycle::of($election)->isActive()',
        'reasoning' => 'Old state flag; use SSOT engine instead',
    ],
    'state' => [  // NEW — cached interpretation column
        'severity' => 'strict',
        'replacement' => 'ElectionLifecycle::of($election)->state()->value',
        'reasoning' => 'Cached state; derive from business facts via SSOT engine',
    ],
];
```

**Enforcement Behavior:**
- **Level 0-1 (observation):** DeprecationAccessGuard logs to metrics, allows access
- **Level 2-3 (query/lifecycle):** QueryPolicyGuard blocks queries, DeprecationAccessGuard logs
- **Level 4 (full strict):** All access throws exceptions; forces SSOT usage

**Why:** Each level reveals more coupling. Metrics (L1) show what's used. Queries (L2) block bulk operations. Access (L4) forces code changes.

#### 1.3 Fix ActivateElectionCommand (Route Through Constitutional Guard)

**File:** `app/Console/Commands/ActivateElectionCommand.php`  

**Problem:** Direct state mutation bypasses all governance.
```php
// ❌ WRONG - No authorization
$election->update(['state' => 'administration']);
```

**Solution:** Route through ConstitutionalTransitionGuard, which authorizes via ElectionStateWriteContext.
```php
// ✅ CORRECT - Full governance chain
$guard = app(\App\Application\Election\Services\ConstitutionalTransitionGuard::class);
$lifecycle = \App\Application\Election\Facades\ElectionLifecycle::of($election);

// Validate transition is allowed
$guard->assertAllowed($election, 'start_administration', $lifecycle->snapshot());

// Guard authorizes the write via ElectionStateWriteContext::authorize()
// Then handler persists the state
$handler = app(\App\Contexts\Elections\Application\Handlers\UpdateElectionStateHandler::class);
$handler->handle(new UpdateElectionStateCommand(
    electionId: $election->id,
    action: 'start_administration',
));

// Result: election.state is now 'administration' via authorized path
```

**Why:** 
- State is only updated through ConstitutionalTransitionGuard authorization
- Mutations are recorded to ConstitutionalMetrics for audit trail
- SSOT engine can verify the new state is correct
- At Level 4 (full strict), direct state mutations throw exceptions, forcing this pattern

#### 1.4 Fix BackfillElectionState (Verify via SSOT, Don't Mutate)

**File:** `app/Console/Commands/BackfillElectionState.php`

**Problem:** Command tries to bulk-update state column, which will now be guarded. Direct mutations bypass ConstitutionalTransitionGuard.

**Strategic Approach (Phase 3.2-3.3):**
1. **For now (Phase 3.2 Level 1):** Use a backfill command that:
   - Computes correct state via SSOT engine
   - Verifies if divergence exists (audit only)
   - **Does NOT attempt to fix** (fixes must go through guard)
   - Logs divergences for manual review

2. **Later (Phase 3.3):** After all code routes through guard, run authorized backfill

**Current Implementation (Audit Only):**
```php
Election::chunkById(100, function($elections) {
    foreach ($elections as $election) {
        // Compute correct state via SSOT engine
        $engine = app(\App\Application\Election\Services\ElectionLifecycleEngineImpl::class);
        $correctState = $engine->getState($election);
        
        // Check for divergence (do NOT attempt to fix yet)
        if ($election->state !== $correctState->value) {
            Log::channel('constitutional_integrity')->warning("Election state divergence detected", [
                'election_id' => $election->id,
                'cached_state' => $election->state,
                'computed_state' => $correctState->value,
                'action' => 'Manual review required - do not auto-fix yet',
            ]);
            
            // Record to metrics for Phase 3.3 planning
            app(\App\Application\Election\Monitoring\ConstitutionalMetricsContract::class)
                ->recordDriftViolation('state_divergence', $election->id);
        }
    }
});
```

**After Phase 3.3 (when guard is canonical):**
```php
// Run ONLY after ConstitutionalTransitionGuard is enforced everywhere
// Use the same pattern as ActivateElectionCommand:
$guard = app(\App\Application\Election\Services\ConstitutionalTransitionGuard::class);

Election::where('state', '<>', computed_state)->chunkById(100, function($elections) use ($guard) {
    foreach ($elections as $election) {
        $correctState = ElectionLifecycle::of($election)->state();
        
        // Let guard authorize the correction
        $handler = app(\App\Contexts\Elections\Application\Handlers\UpdateElectionStateHandler::class);
        $handler->handle(new UpdateElectionStateCommand(
            electionId: $election->id,
            action: 'restore_correct_state',  // Guard validates this action
        ));
    }
});
```

**Why this phased approach:**
- **Phase 3.2:** Find all divergences, don't touch them yet
- **Phase 3.3:** Fix them through the authorized guard (now canonical)
- Prevents data corruption from forcing corrections before guard is fully enforced

### Phase 3.3: Establish Authorized Transition Boundary (IMMEDIATE AFTER 3.2)

**Goal:** Make ConstitutionalTransitionGuard the ONLY path to state mutations. ElectionStateWriteContext is the boundary.

**CRITICAL:** Do this immediately after Phase 3.2 Level 1 metrics are healthy (no unauthorized mutations detected).

#### 3.3.1 Implement recordUnauthorizedStateMutation()

Add method to ConstitutionalMetricsContract and ConstitutionalMetrics:
```php
public function recordUnauthorizedStateMutation(string $field, string $context): void {
    $this->incrementMetric('unauthorized_state_mutations', [
        'field' => $field,
        'context' => $context,
        'severity' => 'CRITICAL',
    ]);
}
```

#### 3.3.2 ConstitutionalTransitionGuard Authorizes Writes

Update handler to wrap state updates in authorized context:
```php
class UpdateElectionStateHandler {
    public function handle(UpdateElectionStateCommand $command): void {
        $election = $this->repository->find($command->electionId);
        
        // Validate transition through guard (throws if invalid)
        $this->guard->assertAllowed($election, $command->action, ...);
        
        // Authorize the write and persist
        ElectionStateWriteContext::authorize(function() use ($election) {
            $election->state = $newState;
            $election->save();
        });
    }
}
```

#### 3.3.3 All State Mutations Go Through Guard

Verify these commands all use the authorized boundary:
- ActivateElectionCommand (already fixed in 1.3)
- DeactivateElectionCommand
- StartNominationCommand
- OpenVotingCommand
- CloseVotingCommand
- PublishResultsCommand
- Queue-based state transitions

**Result:** Election state is ONLY changed through ConstitutionalTransitionGuard authorization.

---

### Phase 2: Eliminate Old State Columns (MEDIUM-TERM)

**Remove:** `election.state` column (currently stores import_voters, administration, nomination, etc.)

**Why:**
- Source of truth should be business logic, not a column
- Engine already derives state correctly from: `administration_completed`, `nomination_completed`, `voting_starts_at`, `voting_ends_at`, `results_published_at`
- Eliminates dual-truth problem

**Migration Plan:**
1. Create migration to remove `election.state` column
2. Update `getStateMachineData()` to not read `state` column:
   ```php
   // Before:
   'currentState' => $election->current_state,  // Reads stale column
   
   // After:
   'currentState' => ElectionLifecycle::of($election)->state()->value,  // Computes fresh
   ```
3. Update any views reading `election.state` to call `ElectionLifecycle` instead

### Phase 3: Migrate Console Commands to SSOT (MEDIUM-TERM)

**ActivateElectionCommand:**
- Should not directly set state
- Should trigger specific actions (e.g., "start_administration")
- Let ConstitutionalTransitionGuard and engine derive resulting state

**BackfillElectionState:**
- Remove if no longer needed (state is computed, not stored)
- If needed for auditing: only log divergences, don't update

---

## Implementation Order

**Phase 3.2 (Current — Graduated Strict Activation at Level 1):**
1. **Now:** Verify ElectionStateWriteContext mutator is in place
2. **Now:** Verify ConstitutionalMetrics has recordUnauthorizedStateMutation() method
3. **Monitor 24h:** Collect metrics on unauthorized mutations
4. **After 24h:** Review metrics. If violations < 5, proceed to Phase 3.3

**Phase 3.3 (Immediate After Level 1 Stabilizes):**
1. **Week 1:** Implement recordUnauthorizedStateMutation() in metrics contract
2. **Week 1:** Update ConstitutionalTransitionGuard to authorize writes via ElectionStateWriteContext
3. **Week 2:** Verify all state mutation commands use authorized boundary (1.3-1.4 fixes)
4. **Monitor 24h:** Run tests, verify SSOT engine derives correct state

**Phase 2 (Medium-term — After all mutations are guarded):**
1. **Week 3:** Create migration removing `election.state` column
2. **Week 3:** Update all code to use ElectionLifecycle facade instead of state column

**Phase 3 (Long-term — Eliminate old state machine):**
1. **Week 4:** Remove status and is_active columns
2. **Week 4:** Update console commands to use SSOT for all decisions

---

## Verification

### Before Hardening
```bash
# These should work but create inconsistency
$election->update(['state' => 'voting']);  # Direct bypass
$engine = ElectionLifecycle::of($election);
$engine->state();  # Returns 'draft' (diverged!)
```

### After Hardening (Phase 1)
```bash
# Direct mutation now blocked
$election->update(['state' => 'voting']);  # Throws exception

# Must use guard + handler
$guard->assertAllowed($election, 'start_voting', $snapshot);
$handler->handle($command);  # Only path that works
```

### After Hardening (Phase 2-3)
```bash
# Old column removed entirely
$election->state;  # Doesn't exist (column gone)

# Only SSOT available
$election->election_lifecycle = ElectionLifecycle::of($election);
$election->election_lifecycle->state();  # Single source of truth
```

---

## Critical Files for Hardening

| File | Action | Phase |
|------|--------|-------|
| `app/Models/Election.php` | Add state mutator guard | 1.1 |
| `app/Application/Election/Deprecation/DeprecationPolicy.php` | Deprecate state column | 1.2 |
| `app/Console/Commands/ActivateElectionCommand.php` | Use ConstitutionalTransitionGuard | 1.3 |
| `app/Console/Commands/BackfillElectionState.php` | Use SSOT for validation | 1.4 |
| `database/migrations/2026_05_XX_XXXXXX_remove_election_state_column.php` | Drop column | 2 |
| `app/Http/Controllers/Election/ElectionManagementController.php` | Use ElectionLifecycle not state column | 2 |
| `app/Console/Commands/` | All commands use SSOT | 3 |

---

## Success Criteria

✅ **Phase 3.2 Level 1 (Metrics Strict) — Current:**
- [ ] ElectionStateWriteContext mutator is active in Election model
- [ ] DeprecationPolicy STRICT_LEVEL = 1
- [ ] ConstitutionalMetrics records unauthorized state mutations
- [ ] All 4 GraduatedStrictActivationTest tests pass
- [ ] 24h observation: unauthorized mutations < 5 (safe to proceed)

✅ **Phase 3.3 (Authorized Boundary) — Immediate Next:**
- [ ] recordUnauthorizedStateMutation() implemented in metrics
- [ ] ConstitutionalTransitionGuard authorizes via ElectionStateWriteContext::authorize()
- [ ] ActivateElectionCommand routes through guard (Phase 1.3 fix)
- [ ] BackfillElectionState audits divergences without mutating (Phase 1.4 fix)
- [ ] All state transition commands use authorized boundary
- [ ] 24h observation: SSOT engine correctly derives state
- [ ] Ready to increase STRICT_LEVEL from 1 → 2

✅ **Phase 2 Complete (Eliminate Persistence Authority):**
- [ ] `election.state` column removed from database
- [ ] All code uses ElectionLifecycle::of($election)->state()->value instead
- [ ] No code reads `$election->state` directly (ElectionLifecycle facade only)
- [ ] Metrics show zero access to removed column

✅ **Phase 3 Complete (SSOT Sovereignty):**
- [ ] `status` and `is_active` columns removed
- [ ] All console commands use SSOT for state decisions
- [ ] Election state is single source of truth (computed from business facts, not stored)
- [ ] Test election shows consistent state across all systems
- [ ] STRICT_LEVEL = 4 (full strict): all unauthorized mutations throw exceptions

---

## Phase 3.2 → Phase 3.3 Integration

**Graduated Escalation Strategy:**

| Phase | Level | Enforcement | Outcome |
|-------|-------|-------------|---------|
| **3.2** | 1 (Metrics Strict) | Record mutations silently | Identify what bypasses guard |
| **3.3** | 2 (Query Guard Strict) | Block bulk queries, log others | Force individual-transaction routing |
| **3.3+** | 3 (Lifecycle Strict) | Block field access entirely | Force ElectionLifecycle facade usage |
| **Final** | 4 (Full Strict) | Throw exceptions on any bypass | Zero legacy code paths remain |

**How Phase 3.2 Metrics Drive Phase 3.3 Hardening:**

1. **Level 1 (now):** Collect baseline metrics for 24h
   - `recordUnauthorizedStateMutation()` captures all direct state mutations
   - Which commands bypass guard?
   - Which tests try to mutate state directly?

2. **Level 1 → 3.3:** After metrics stable, activate guard authorization
   - ElectionStateWriteContext barrier now ENFORCES guard routing
   - Metrics from Level 1 show exactly which code must change
   - Fix those code paths to use guard

3. **Level 3.3 → 2:** Increase enforcement
   - QueryPolicyGuard blocks old queries
   - Forces individual record processing through guard
   - Metrics show if bulk operations are still attempted

4. **Level 2+ → 4:** Progressively tighten
   - Each level removes one escape hatch
   - Metrics guide which code to fix next
   - End state: Zero legacy state mutations possible

**Why This Works:**
- Metrics (Level 1) reveal real coupling without breaking code
- Guard (Phase 3.3) makes violations impossible once enforced
- Graduated escalation gives time for code migration between levels
- No silent failures — all violations recorded and visible
