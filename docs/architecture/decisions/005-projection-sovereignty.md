# ADR-005: Projection Sovereignty

## Status
Accepted — 2026-05-23

## Context

The frontend renders user-visible phases (administration, nomination, voting, results_pending, results) for grouping and display. These phases are **projections** — computed abstractions of the true constitutional lifecycle states.

Phase C.2.7 established:
- **Lifecycle States:** 12 constitutional runtime states stored in database, governed by state machine
- **Phases:** 5 UI projections computed from lifecycle states, used for display grouping only
- **phaseFor():** Single canonical function mapping lifecycle state → phase + metadata

**Problem:** Even after establishing phaseFor() as canonical, frontend components were still defining phase groupings independently, or the mapping lived in multiple locations.

## Decision

**Frontend renders projections derived from authoritative lifecycle state; frontend never defines or derives the lifecycle→phase mapping.**

### Projection Invariants

1. **phaseFor() is the single source of truth**
   - Located in `resources/js/Domain/Election/ElectionPhaseService.ts`
   - Receives lifecycle state, returns `{ phase, isOverlay, lifecycleState }`
   - No component may redefine this mapping inline

2. **Frontend must not derive lifecycle truth**
   - Frontend cannot determine state completion from timestamps
   - Frontend cannot determine state advancement from flags
   - Frontend receives `stateMachine.currentState` as ground truth
   - Frontend projects current state into phase via `phaseFor(stateMachine.currentState)`

3. **currentPhase is a stabilization seam**
   - Computed: `currentPhase = computed(() => phaseFor(props.stateMachine.currentState))`
   - Enables phase-based rendering without full phaseStates refactor
   - Will become the authoritative source once phaseStates is removed in C.2.8

4. **phaseStates is transitional debt (frozen)**
   - Still re-derives completion from timestamps/flags
   - Must NOT be expanded with new derivation logic
   - Marked with `@transitional C.2.8` comment
   - Removed entirely in C.2.8 when backend projects completedStates

### Structure

```typescript
// ElectionPhaseService.ts: Single source of lifecycle→phase mapping
export function phaseFor(state: ElectionLifecycleState): ElectionPhaseProjection {
  if (state === ElectionLifecycleStates.DRAFT) return { phase: 'administration', isOverlay: false, ... }
  if (state === ElectionLifecycleStates.VOTING_ACTIVE) return { phase: 'voting', isOverlay: false, ... }
  if (state === ElectionLifecycleStates.ARCHIVED) return { phase: null, isOverlay: true, ... }
  // ...
}

// StateMachinePanel.vue: Phases array calls phaseFor() actively
const phases = [
  {
    state: ElectionLifecycleStates.VOTING_ACTIVE,
    ...phaseFor(ElectionLifecycleStates.VOTING_ACTIVE),  // ← Active consumption
    icon: '🗳️',
    description: '...',
  },
  // ... more phases
]

// Components: Use projection anchor
const currentPhase = computed(() => phaseFor(props.stateMachine.currentState))
// UI groups by phase, compares lifecycle states
<div v-if="currentPhase.phase === 'voting'">{{ votingUI }}</div>
```

## Consequences

### Frontend
- ✅ Projection mapping centralized and testable
- ✅ No divergence risk (phaseFor is single entry point)
- ✅ Architecture tests enforce invariant
- ⚠️ phaseStates still re-derives from flags (transitional debt)
- ⚠️ currentPhase is seam; not yet authoritative

### Lifecycle States
- ✅ Never referenced in components except by ElectionLifecycleStates constants
- ✅ Comparisons use constants, not magic strings
- ✅ All 12 states have defined phase mappings

### Phases
- ✅ Rendered from projection, not derived independently
- ✅ results_pending is provisional naming (may become tabulation in C.2.8)
- ✅ isOverlay field distinguishes suspension/archival from normal phases
- ⚠️ Phase grouping can only change via phaseFor() update

### Testing
- ✅ Unit tests for phaseFor() ensure mapping correctness (14+ tests covering all 12 states)
- ✅ Architecture tests prevent regression (grep for hardcoded phase strings)
- ✅ No component can redefine phase grouping
- ⚠️ Integration tests must validate full round-trip: state → phaseFor() → rendering

## Enforcement

**Architecture tests guard projection boundary:**

```typescript
// tests/js/Architecture/ElectionFrontendArchitectureTest.spec.ts

it('StateMachinePanel.vue imports phaseFor from @/Domain/Election/ElectionPhaseService', () => {
  const content = readFile('Pages/Election/Partials/StateMachinePanel.vue')
  expect(content).toContain('phaseFor')
  expect(content).toContain('@/Domain/Election/ElectionPhaseService')
})

it('phases array uses phaseFor() for phase field — not hardcoded phase strings', () => {
  const content = readFile('Pages/Election/Partials/StateMachinePanel.vue')
  const violations = codeLines(content, /phase:\s*'(administration|nomination|voting|results)'/)
  expect(violations).toHaveLength(0)
})
```

**Invariant tests for phaseFor():**

```typescript
// tests/js/Domain/Election/phaseFor.spec.ts
describe('phaseFor() — Projection Invariants', () => {
  it('all 12 lifecycle states map to defined projections', () => {
    const allStates = Object.values(ElectionLifecycleStates)
    expect(allStates).toHaveLength(12)
    allStates.forEach(state => {
      const result = phaseFor(state)
      expect(result).toBeDefined()
      expect(result.lifecycleState).toBe(state)
    })
  })
})
```

## C.2.8: Backend Projection of completedStates

### Completed States Derivation

Backend now projects `completedStates: string[]` from the lifecycle engine position.

```php
// Domain class (pure PHP, no Laravel)
ElectionLifecycleProjection::completedStatesFor($currentState): string[]
// Returns: all states earlier in the constitutional progression than currentState
```

Constitutional progression order (10 states — **linear only**):

1. draft
2. submitted_for_approval
3. approved
4. setup_administration
5. setup_nomination
6. ready_for_voting
7. voting_active
8. counting
9. results_published
10. archived

### Excluded from Progression

**REJECTED** — Terminal branch state
- Not in linear progression
- Election rejected returns to draft or abandoned
- Not a step forward in progression

**SUSPENDED** — Operational overlay state
- Not a lifecycle progression state
- Freezes capabilities only
- Pre-suspension position restoration deferred to C.2.9
- `projectionAvailable: false` disambiguates from "no completed states"

### Why Election::getProgress() Was Rejected

`Election::getProgress()` uses legacy `'setup'` state name (pre-Phase A.2 split). Cannot be safely reused.

Constitutional states after A.2:
- `setup_administration` (not `setup`)
- `setup_nomination` (not `setup`)

Using legacy method would generate wrong progressions.

### projectionAvailable Flag

Distinguishes semantic states:

```
projectionAvailable=true:  currentState in [draft…archived]
                          → completedStates reflects progression

projectionAvailable=false: currentState in [rejected, suspended, unknown]
                          → completedStates=[], projection unavailable
```

Without this flag, `completedStates=[]` is semantically ambiguous (conflates draft with suspended).

### Frontend Consumption

**Removed:**
- `phaseStates` computed (frontend derivation)
- Hardcoded `'voting_active'` template strings
- `PhaseCompletionRules.isCompleted()` — deprecated with delete instruction

**Updated:**
- `isPhaseCompleted()` → reads `stateMachine.completedStates` + guards with `projectionAvailable`
- `isPhaseUpcoming()` → still uses `isPhaseCompleted()`, now backend-sourced

### Controller Integration

```php
// ElectionManagementController::getStateMachineData()
$completedStates = ElectionLifecycleProjection::completedStatesFor($currentState);
$projectionAvailable = ElectionLifecycleProjection::isProjectionAvailable($currentState);

return [
    'currentState' => $currentState,
    'completedStates' => $completedStates,      // NEW
    'projectionAvailable' => $projectionAvailable, // NEW
    // ... other fields
];
```

### Transitional Note

**completedStates is transitional projection infrastructure.**

Target architecture is a full `phase_projection` runtime object containing:
- `current_phase` (computed)
- `completed_phases` (array)
- `display_status` (enum)
- `operational_overlay` (typed)

Until then, `completedStates` provides immediate relief from frontend derivation.

Do not canonize this intermediate form in external contracts.

## Related

- [[001-constitutional-capability-sovereignty]] — Capability authority principle
- [[002-frontend-anti-corruption-boundary]] — Frontend read-only boundary
- [[003-lifecycle-vs-phase-projection]] — Lifecycle/phase separation
- [[004-deterministic-capability-resolver]] — Resolver purity principle
- **Implementation:** `resources/js/Domain/Election/ElectionPhaseService.ts`, `resources/js/Pages/Election/Partials/StateMachinePanel.vue`, `resources/js/Components/Election/StateBadge.vue`, tests/js/Architecture/ElectionFrontendArchitectureTest.spec.ts, tests/js/Domain/Election/phaseFor.spec.ts, `app/Domain/Election/Projection/ElectionLifecycleProjection.php`

## Notes on Vocabulary

**results_pending:** Status is **provisional**. Neither a runtime lifecycle state nor a fully stable projection concept. May be renamed to `tabulation` or `counting_phase` in C.2.8 to better reflect constitutional vocabulary. Do not canonize this name in external contracts.

**isOverlay:** Current implementation is boolean flag. Future: should become typed operational overlay indicating state is non-terminal (suspended, archived) and overlays normal phase rendering. See [[future-operational-overlays]].

## Examples

### ✅ Correct: Projection Sovereignty

```typescript
// PhaseFor defines mapping (single source of truth)
export function phaseFor(state: ElectionLifecycleState): ElectionPhaseProjection {
  if (state === ElectionLifecycleStates.VOTING_ACTIVE) {
    return { phase: 'voting', isOverlay: false, lifecycleState: state }
  }
  // ...
}

// Component uses projection
const currentPhase = computed(() => phaseFor(props.stateMachine.currentState))
// Template groups by phase
<div v-if="currentPhase.phase === 'voting'">
  <!-- Voting UI uses phase projection, not lifecycle state -->
</div>
// Comparisons use lifecycle state
<div v-if="props.stateMachine.currentState === ElectionLifecycleStates.VOTING_ACTIVE">
  <!-- Specific logic for voting_active state -->
</div>
```

### ❌ Wrong: Hardcoded Mapping in Component

```typescript
// ❌ WRONG: Component redefines mapping
const currentPhase = computed(() => {
  if (props.stateMachine.currentState === ElectionLifecycleStates.DRAFT) return 'administration'
  if (props.stateMachine.currentState === ElectionLifecycleStates.VOTING_ACTIVE) return 'voting'
  // ... more mappings (duplicates phaseFor!)
})
// No single source of truth; divergence risk
```

### ❌ Wrong: Deriving Lifecycle State from Flags

```typescript
// ❌ WRONG: Frontend derives state completion from election flags
const isVotingComplete = computed(() =>
  props.election.voting_ends_at && new Date() > new Date(props.election.voting_ends_at)
  // This contradicts stateMachine.currentState!
)
// Use stateMachine.currentState instead (it's authoritative)
const isVotingComplete = computed(() =>
  [ElectionLifecycleStates.COUNTING, ElectionLifecycleStates.RESULTS_PUBLISHED].includes(props.stateMachine.currentState)
)
```

### ✅ Correct: Active phaseFor() Consumption

```typescript
// Phases array actively calls phaseFor()
const phases = [
  {
    state: ElectionLifecycleStates.VOTING_ACTIVE,
    ...phaseFor(ElectionLifecycleStates.VOTING_ACTIVE),  // Spreads { phase: 'voting', isOverlay: false, ... }
    icon: '🗳️',
    description: 'Members cast their votes...',
  },
  // More phases...
]
// Now templates can access p.phase without recomputing
<div v-if="phase.phase === 'voting'">{{ phase.description }}</div>
```
