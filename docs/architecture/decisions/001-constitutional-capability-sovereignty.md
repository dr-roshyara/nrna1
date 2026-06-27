# ADR-001: Constitutional Capability Sovereignty

## Status
Accepted — 2026-05-23

## Context

The voting platform must manage complex authorization decisions based on constitutional rules (who can perform which election governance actions). These rules change based on:
- Current election state (draft, voting active, results published, etc.)
- User roles (chief, officer, observer)
- Constitutional preconditions (administration completed, voting window closed, etc.)

Early implementations either:
1. **Scattered logic** — authorization checks scattered across frontend components and backend controllers
2. **Frontend inference** — frontend attempted to derive permissions from state (violates SoC)
3. **Dual authority** — both frontend and backend made independent permission decisions

All three approaches led to divergence, security holes, and inconsistent UX.

## Decision

**Capability authority is centralized and non-negotiable:**

1. **Backend is the sole authority** — All permission checks originate from the backend capability resolver
2. **Frontend is passive** — Frontend reads capabilities, never infers or re-derives permissions
3. **Capabilities are immutable snapshots** — API returns a point-in-time snapshot of allowed/denied actions with denial reasons
4. **Composable is a pure read adapter** — `useElectionCapabilities` composes the snapshot into computed refs, never augments

### Backend Flow
```
Request → Capability Resolver → ElectionConstitution rules applied → 
  Overlay policies (role, preconditions) → Capabilities map returned in Inertia props
```

### Frontend Flow
```
Inertia props received → useElectionCapabilities composes capabilities → 
  Components destructure computed refs → Templates render enabled/disabled state
```

**Critical Invariants:**
- Frontend NEVER checks `if (user.role === 'chief')` in governance decisions
- Frontend NEVER evaluates `if (election.administration_completed)`
- Frontend NEVER runs capability rules code

### Capability Resolution Constraints

The resolver must be **deterministic and side-effect-free**:
- ❌ Cannot call `auth()` (user context comes via Inertia props)
- ❌ Cannot use `now()` directly (time comes via props, if needed)
- ❌ Cannot query Eloquent models (all context must be passed as arguments)
- ✅ Pure function that receives context and returns capabilities

## Consequences

### Frontend
- ✅ No permission inference bugs (impossible to diverge from backend)
- ✅ Consistent authorization UX (all users see same enabled/disabled buttons)
- ⚠️ Frontend cannot "fail open" — must wait for backend decision
- ⚠️ Any permission change requires full page reload or Inertia re-fetch

### Backend
- ✅ Single source of truth for permissions
- ✅ Easy to audit and test (pure function)
- ⚠️ Resolver performance is critical (computed for every request)
- ⚠️ Must include all context in resolver inputs (no lazy lookups)

### Testing
- ✅ Architecture tests verify frontend never reads deprecated `allowedActions`
- ✅ Unit tests for resolver rules are simple (no mocking infrastructure)
- ⚠️ Integration tests must validate round-trip: backend decision → Inertia props → frontend computation

## Related

- [[002-frontend-anti-corruption-boundary]] — How frontend is isolated from permission decisions
- [[003-lifecycle-vs-phase-projection]] — How constitutional state differs from UI projections
- [[004-deterministic-capability-resolver]] — How resolver maintains determinism
- **Implementation:** `resources/js/Composables/useElectionCapabilities.ts`, `app/Domain/Election/Constitution/ElectionConstitution.php`

## Examples

### ✅ Correct: Frontend reads capability
```typescript
const { canOpenVoting, openVotingBlockedReason } = useElectionCapabilities(computed(() => props.stateMachine))
// Template uses: v-if="canOpenVoting" or @click with error handler
```

### ✅ Correct: Backend resolves authority
```php
$allowed = $this->constitution->canResolveAction('open_voting', $election, auth()->user());
return inertia('ElectionManagement', [
  'stateMachine' => ['capabilities' => ['open_voting' => ['allowed' => $allowed]]]
]);
```

### ❌ Wrong: Frontend re-derives permission
```typescript
const canOpenVoting = computed(() => 
  stateMachine.value.currentState === 'ready_for_voting' && 
  auth().user.role === 'chief'
)  // WRONG: duplicates backend logic
```

### ❌ Wrong: Backend infers state, frontend enforces
```typescript
if (!props.capabilities.open_voting.allowed) {
  // Correct to respect this, but frontend CANNOT have asked for it
  // Backend decision is law
}
```
