# ADR-002: Frontend Anti-Corruption Boundary

## Status
Accepted — 2026-05-23

## Context

Vue.js components are tempted to process server data in ways that corrupt its semantic meaning:
1. **Permission augmentation** — Components receive capability snapshots but "help" by filtering, reordering, or adding new reasons
2. **State interpretation** — Components interpret `currentState` using local knowledge (e.g., "voting started = can now see results")
3. **Rule re-derivation** — Components duplicate business rules that belong in the backend

Example of corruption:
```typescript
// ❌ WRONG: Component augments capabilities
const canVote = computed(() => 
  props.capabilities.open_voting.allowed && 
  props.election.voting_window_minutes > 5
)
```

The component mixed two concerns:
- Backend authority: "open voting is allowed"
- Frontend UX: "but warn if less than 5 minutes left"

Backend said "allowed" was a capability. Frontend turned it into a conditional recommendation.

## Decision

**Frontend operates as a pure read adapter with an anti-corruption boundary:**

1. **Composables are read-only adapters** — Transform server data into computed refs, zero business logic
2. **Never augment, filter, or re-derive** — If backend says "allowed: false", frontend cannot add "unless X"
3. **Components destructure and display** — Components receive computed refs and render; no decision-making
4. **Separation of concerns** — UX constraints (warnings, disabled-until) are presentation, not authorization

### Composable Invariants

`useElectionCapabilities` is strictly a **data access layer:**

```typescript
// ✅ Correct: Pure adapter
const canOpenVoting = computed(() => 
  stateMachine.value.capabilities?.open_voting?.allowed ?? false
)

// ❌ Wrong: Logic re-derivation
const canOpenVoting = computed(() => 
  stateMachine.value.capabilities?.open_voting?.allowed &&
  election.value.administration_completed
)  // This is backend logic, not frontend adaptation
```

### Component Invariants

Components destructure refs and render:

```typescript
// ✅ Correct: Component receives, renders
const { canOpenVoting, openVotingBlockedReason } = useElectionCapabilities(...)
// Template: v-if="canOpenVoting", :disabled="!canOpenVoting"

// ❌ Wrong: Component applies additional filters
const visibleActions = computed(() => 
  capabilities.all.filter(c => c.allowed && shouldShow(c))
)  // Where "shouldShow" duplicates backend intent
```

## Consequences

### Frontend
- ✅ Simple, testable components (no business logic)
- ✅ No divergence risk (data flows read-only from server)
- ✅ Clear responsibility boundaries (render + interaction, nothing more)
- ⚠️ UX constraints must be expressed in backend capabilities (e.g., "allowed but warn")
- ⚠️ Cannot locally "fail open" when backend is conservative

### Backend
- ✅ All business logic in one place
- ✅ Easy to add new constraints (update resolver, no frontend changes)
- ⚠️ Must anticipate all UX concerns (cannot rely on frontend filtering)
- ⚠️ Resolver must be feature-complete on day 1

### Testing
- ✅ Composable tests are integration tests (read props, assert refs)
- ✅ Component tests do NOT test authorization (backend's job)
- ✅ Architecture tests prevent bypass patterns (grep/ESLint guards)
- ⚠️ Full-stack tests required to validate end-to-end flow

## Enforcement

**Architecture tests prevent corruption:**

```typescript
// tests/js/Architecture/ElectionFrontendArchitectureTest.spec.ts
it('useElectionCapabilities does not hardcode action strings', () => {
  const violations = grep('canDo.*hardcoded_string_here')
  expect(violations).toHaveLength(0)
})

it('Management.vue does not rewrap composable refs', () => {
  const violations = grep('const canOpenVoting = computed.*canOpenVoting')
  expect(violations).toHaveLength(0)
})
```

**Code review checklist:**
- [ ] Composable receives props, returns computed refs only
- [ ] Components destructure refs, do not transform them
- [ ] Templates render refs directly, no conditional logic
- [ ] If component needs different behavior → backend adds new capability

## Related

- [[001-constitutional-capability-sovereignty]] — Backend authority principle
- [[003-lifecycle-vs-phase-projection]] — How state differs from projection
- [[004-deterministic-capability-resolver]] — How resolver maintains purity
- **Implementation:** `resources/js/Composables/useElectionCapabilities.ts`, architecture tests

## Examples

### ✅ Correct Flow
```typescript
// Backend
return inertia('Management', [
  'stateMachine' => [
    'capabilities' => [
      'open_voting' => [
        'allowed' => true,
        'denial_reason' => null,
      ],
      'suspend' => [
        'allowed' => false,
        'denial_reason' => 'ELECTION_ALREADY_SUSPENDED',
      ],
    ],
  ],
]);

// Composable
export function useElectionCapabilities(stateMachine) {
  return {
    canOpenVoting: computed(() => 
      stateMachine.value.capabilities?.open_voting?.allowed ?? false
    ),
    openVotingBlockedReason: computed(() => 
      stateMachine.value.capabilities?.open_voting?.denial_reason ?? null
    ),
  }
}

// Component
const { canOpenVoting, openVotingBlockedReason } = useElectionCapabilities(...)
// Template: <button :disabled="!canOpenVoting">Open Voting</button>
```

### ❌ Corruption Patterns

**Pattern 1: Logic re-derivation**
```typescript
// Composable corrupts by adding backend rules
const canOpenVoting = computed(() => 
  stateMachine.value.capabilities?.open_voting?.allowed &&
  stateMachine.value.currentState === 'ready_for_voting'  // WRONG: duplicates backend
)
```

**Pattern 2: Data filtering**
```typescript
// Component filters capabilities (truncates authority)
const enabledActions = computed(() => 
  capabilities.all.filter(c => c.allowed)  // WRONG: backend already decided
)
```

**Pattern 3: Local augmentation**
```typescript
// Component adds conditional logic
const warnings = computed(() => 
  canOpenVoting.value && votingWindowMinutes.value < 5
    ? 'Less than 5 minutes left'
    : null
)  // WRONG: UX constraint should be in backend (return capability with metadata)
```
