# ADR-003: Lifecycle States vs Phase Projections

## Status
Accepted — 2026-05-23

## Context

Elections progress through distinct stages driven by constitutional rules. Early versions conflated two separate concerns:

1. **Constitutional runtime state** — Legal state machine with 12 states: `draft`, `submitted_for_approval`, `approved`, `rejected`, `setup_administration`, `setup_nomination`, `ready_for_voting`, `voting_active`, `counting`, `results_published`, `archived`, `suspended`

2. **UI phase abstraction** — Display grouping into 5 logical phases: `administration`, `nomination`, `voting`, `results_pending`, `results`

The codebase created a `PhaseState` type that was **neither a lifecycle state nor a clear projection**:
- ❌ Could not be used in the database (wrong cardinality)
- ❌ Did not map cleanly to lifecycle states (ambiguous mapping)
- ❌ Created orphaned domain classes (no consumer)
- ❌ Broke architecture tests (false positives)

Example of confusion:
```typescript
// ❌ WRONG: Ambiguous type
export type PhaseState = 'administration' | 'nomination' | 'voting' | 'results' | 'results_pending'

// Is this a lifecycle state? No. A UI phase? Sort of. Used where?
if (currentState === 'administration') { ... }  // But `administration` is not in the database!
```

## Decision

**Formalize the separation: Lifecycle States are constitutional truth; Phases are UI projections.**

### Lifecycle States: Constitutional Runtime Truth
```typescript
export type ElectionLifecycleState = 
  | 'draft'
  | 'submitted_for_approval'
  | 'approved'
  | 'rejected'
  | 'setup_administration'
  | 'setup_nomination'
  | 'ready_for_voting'
  | 'voting_active'
  | 'counting'
  | 'results_published'
  | 'archived'
  | 'suspended'
```

- **Single source of truth:** Database column `elections.lifecycle_state`
- **Isomorphic across stack:** PHP enum → artisan codegen → TypeScript constants
- **Used in state machines, resolvers, queries:** Governs all business logic
- **Immutable:** Only PhP enum is source; frontend is generated

### Phase Projections: UI Abstractions
```typescript
export type ElectionPhase = 
  | 'administration'
  | 'nomination'
  | 'voting'
  | 'results_pending'
  | 'results'

export interface ElectionPhaseProjection {
  phase: ElectionPhase | null
  lifecycleState: ElectionLifecycleState | string
  isOverlay: boolean  // true for archived, suspended
}

export function phaseFor(state: ElectionLifecycleState): ElectionPhaseProjection {
  // Maps: 
  // DRAFT, SUBMITTED_FOR_APPROVAL, APPROVED, REJECTED, SETUP_ADMINISTRATION → 'administration'
  // SETUP_NOMINATION → 'nomination'
  // READY_FOR_VOTING, VOTING_ACTIVE → 'voting'
  // COUNTING → 'results_pending'
  // RESULTS_PUBLISHED → 'results'
  // ARCHIVED, SUSPENDED → null (overlay: true)
}
```

- **Projection function:** `phaseFor(state)` is the ONLY place that defines the mapping
- **Used in UI:** Display grouping, phase panels, progress indicators
- **Can change per feature:** UI can adapt phase grouping without backend changes
- **Null phase for overlays:** Archived and suspended elections have `phase: null` + `isOverlay: true`

### Vocabulary Isomorphism

Frontend constants must mirror the backend enum:

```typescript
// PHP Source of Truth
enum ElectionLifecycleState: string {
  case DRAFT = 'draft';
  case SUBMITTED_FOR_APPROVAL = 'submitted_for_approval';
  // ... 12 total
}

// Generated TypeScript (via artisan command)
export const ElectionLifecycleStates = {
  DRAFT: 'draft',
  SUBMITTED_FOR_APPROVAL: 'submitted_for_approval',
  // ... 12 total
} as const
```

**Long-term:** Artisan command regenerates automatically (C.2.8).
**Short-term (C.2.6):** Manual generation with `@generated` marker + regeneration test.

## Consequences

### Domain Model
- ✅ Clear separation between runtime and projection
- ✅ No ambiguity in type signatures (always know if you're working with lifecycle or phase)
- ✅ Orphaned classes deleted (PhaseStateAggregator, PhaseProgressTracker)
- ⚠️ Domain must use ElectionLifecycleState (not phase) in validators, rules
- ⚠️ New phases require new `phaseFor()` mapping entries

### Frontend
- ✅ Explicit projection: `phaseFor(currentState)` → `ElectionPhaseProjection`
- ✅ Type safety: Components cannot accidentally use lifecycle state as phase
- ✅ Architecture tests prevent regression (check phaseFor() is the only mapper)
- ⚠️ Components must use `ElectionLifecycleStates` constants for state comparisons
- ⚠️ Phase-specific UI uses projection, not state directly

### Testing
- ✅ Architecture test suite validates separation (grep tests for now, ESLint later)
- ✅ Unit tests for `phaseFor()` ensure mapping correctness
- ✅ Domain tests use lifecycle states exclusively (no phase leakage)
- ⚠️ Must test all 12 lifecycle states map to expected phases
- ⚠️ Integration tests validate projection is used where needed

### Vocabulary Authority
- ✅ Frontend vocabulary (`ElectionLifecycleStates.ts`) is generated from backend
- ✅ No divergence possible (code generation enforces isomorphism)
- ✅ Regeneration test catches out-of-sync vocabulary
- ⚠️ Must remember to regenerate if PHP enum changes
- ⚠️ Long-term: automate regeneration in CI

## Enforcement

**Architecture tests guard the boundary:**

```typescript
// Check phaseFor() is the only mapper
it('ElectionPhaseService exports phaseFor() function', () => {
  const content = readFile('Domain/Election/ElectionPhaseService.ts')
  expect(content).toContain('export function phaseFor')
})

// Check old ambiguous type is gone
it('does not export the old PhaseState type', () => {
  const content = readFile('Domain/Election/ElectionPhaseService.ts')
  expect(content).not.toContain('export type PhaseState')
})

// Check orphaned classes are deleted
it('does not contain PhaseStateAggregator', () => {
  expect(content).not.toContain('class PhaseStateAggregator')
})

// Check vocabulary isomorphism
it('ElectionLifecycleStates.ts has all 12 states', () => {
  const required = [
    'DRAFT', 'SUBMITTED_FOR_APPROVAL', 'APPROVED', 'REJECTED',
    'SETUP_ADMINISTRATION', 'SETUP_NOMINATION', 'READY_FOR_VOTING',
    'VOTING_ACTIVE', 'COUNTING', 'RESULTS_PUBLISHED', 'ARCHIVED', 'SUSPENDED'
  ]
  required.forEach(state => {
    expect(content).toContain(`${state}:`)
  })
})
```

## Related

- [[001-constitutional-capability-sovereignty]] — State machine is authority
- [[002-frontend-anti-corruption-boundary]] — Frontend uses projections, never derives state
- [[004-deterministic-capability-resolver]] — Resolver uses lifecycle states
- **Implementation:** `resources/js/Domain/Election/ElectionPhaseService.ts`, `resources/js/Constants/ElectionLifecycleStates.ts`, `app/Console/Commands/ExportLifecycleVocabulary.php`

## Examples

### ✅ Correct: Lifecycle States + Phases

```typescript
// Backend resolves using lifecycle states
$allowed = $constitution->canOpenVoting(
  $election->lifecycle_state === 'ready_for_voting',  // ← lifecycle state
  $user
);

// Frontend receives current lifecycle state
const currentState = computed(() => props.stateMachine.currentState)  // 'ready_for_voting'

// Frontend projects to phase for display
const phase = computed(() => {
  const proj = phaseFor(currentState.value)
  return proj.phase  // 'voting'
})

// Template uses phase for grouping, lifecycle state for comparisons
<div v-if="phase === 'voting'">{{ votingUI }}</div>
<div v-if="currentState === ElectionLifecycleStates.VOTING_ACTIVE">Active now</div>
```

### ❌ Wrong: Lifecycle State as Phase

```typescript
// ❌ WRONG: Using lifecycle state as phase
if (currentState === 'administration') { ... }  // 'administration' is not a lifecycle state!

// ❌ WRONG: Hardcoding mapping in components
const phase = currentState === 'draft' ? 'administration' : 'voting'

// ❌ WRONG: Orphaned class with unclear mapping
class PhaseStateAggregator {
  static getPhaseViewModel(state) { ... }  // What does this return? What does it mean?
}
```

### ✅ Correct: Explicit Projection

```typescript
// Only place where mapping lives
export function phaseFor(state: ElectionLifecycleState): ElectionPhaseProjection {
  if (state === ElectionLifecycleStates.DRAFT) return { phase: 'administration', ... }
  if (state === ElectionLifecycleStates.VOTING_ACTIVE) return { phase: 'voting', ... }
  if (state === ElectionLifecycleStates.ARCHIVED) return { phase: null, isOverlay: true, ... }
  // ...
}

// Components use projection
const proj = phaseFor(currentState.value)
// UI uses proj.phase for grouping, proj.isOverlay for overlay states
```
