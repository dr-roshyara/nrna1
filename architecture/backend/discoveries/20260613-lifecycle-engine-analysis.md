# Discovery: Lifecycle Engine + Capability Bridge

**Date:** 2026-06-13  
**Phase:** Phase 2 — Backend Domain Discovery (Round 3)  
**Status:** Complete — evidence collected

## Files Analyzed

| File | Lines | Role |
|------|-------|------|
| `ElectionLifecycleEngineImpl.php` | 329 | **Concrete implementation** — computes canonical state from business facts |
| `ElectionLifecycleSnapshot.php` | 58 | **Immutable value object** — what the frontend receives |
| `ElectionCapabilityResolver.php` | 97 | **Policy resolver** — evaluates layered capabilities for constitutional decisions |

## 1. How Lifecycle State Is Computed

`ElectionLifecycleEngineImpl::getState()` uses a **12-step derivation order** — NOT the `state` column:

```
Priority 1:  Suspended          → suspended_at !== null
Priority 2:  Archived            → archived_at !== null
Priority 3:  ResultsPublished   → results_published_at !== null
Priority 4:  Counting           → voting_ends_at passed + setup complete
Priority 5:  VotingActive       → voting window open NOW
Priority 6:  ReadyForVoting     → setup complete + voting window pending
Priority 7:  SetupNomination    → administration done, nomination in progress
Priority 8:  SetupAdministration → setup started, administration not done
Priority 9:  Approved           → approved_at set, setup not started
Priority 10: SubmittedForApproval → submitted, not approved/rejected
Priority 11: Rejected           → rejected, not approved
Priority 12: Draft              → fallback for new elections
```

**Key architectural fact:** The `state` column on the `elections` table is a **cache**, not the source of truth. The engine re-derives state from business facts every time (`suspended_at`, `voting_starts_at`, `administration_completed`, etc.). The comment reads: *"State column is compatibility cache, not truth."*

## 2. How Permissions Are Derived

The engine computes 5 boolean permissions via `derivePermissions()`:

```
canEdit, canVote, canManageVoters, canPublishResults, canEditTimeline
```

These are **state-driven permissions** — they change based on which of the 12 lifecycle states the election is in. For example:
- `canVote` is `true` only in `VotingActive`
- `canManageVoters` is `true` in Draft, Approved, Rejected, SetupAdministration — but `false` in SetupNomination and beyond
- `canPublishResults` is `true` only in `Counting`

## 3. How Capabilities Reach the Frontend

The chain from backend rules to frontend UI:

```
ElectionConstitution           Domain/     — Defines 14 actions with states/roles/preconditions
    ↓
ConstitutionalTransitionGuard  Application/— Enforces rules before state mutations
    ↓
Election::transitionTo()       Model/     — Executes transitions + side effects + events
    ↓
ElectionLifecycleEngineImpl    Application/— Computes canonical state from business facts
    ↓
ElectionLifecycleSnapshot      Domain/    — Immutable value object with state + permissions
    ↓
ElectionCapabilityResolver     Application/— Policy-based capability evaluation per user
    ↓
Frontend (Inertia props)       Vue/       — Capabilities displayed as UI state
```

**Finding:** The frontend does NOT receive raw state. It receives:
- `stateMachine.capabilities` — per-action capability snapshots (from `ElectionCapabilityResolver`)
- `stateMachine.currentState` — derived lifecycle state (from `ElectionLifecycleEngine`)
- `stateMachine.completedStates` — phase completion tracking

This means the frontend's `useElectionCapabilities` composable is correctly reading pre-computed snapshots rather than deriving authority. The system follows the distributed authority principle documented in `StateMachineContract.ts`.

## 4. Capability Resolver Architecture

`ElectionCapabilityResolver` uses a **policy chain** pattern:
- Policies are sorted by layer priority
- Each policy evaluates the context and returns a decision (allow, deny, short-circuit, abstain)
- First denial is final — short-circuit stops evaluation immediately
- If all policies allow or abstain, the action is authorized

Policy layers (by priority):
1. `LifecycleCapabilityBaselinePolicy` — baseline lifecycle checks
2. `OverlayCapabilityPolicy` — suspension/overlay conditions
3. `TrustCapabilityPolicy` — trust evaluation
4. `EvidenceCapabilityPolicy` — evidence requirements

**Finding:** The capability system has a pluggable policy architecture. New governance rules can be added as new policies without modifying existing code.

## 5. Evidence That Backend → Frontend Bridge Already Works

| Frontend Concept | Backend Source |
|-----------------|----------------|
| `capabilities[action].allowed` | `ElectionCapabilityResolver::evaluate()` |
| `capabilities[action].denial_reason` | `CapabilityDecision::reason` |
| `stateMachine.currentState` | `ElectionLifecycleEngineImpl::getState()` |
| `stateMachine.completedStates` | Administration/nomination completed flags |
| `stateMachine.capabilities_metadata.resolver_version` | `ElectionCapabilitySnapshot` metadata |
| `stateMachine.capabilities_metadata.constitution_hash` | `ElectionConstitution` hash |

## 6. Method Delegation in Election.php

| Method | Delegates To | Contains Own Rules? |
|--------|-------------|---------------------|
| `transitionTo()` | → `ConstitutionalTransitionGuard::assertAllowed()` | Orchestration only |
| `submitForApproval()` | → `processAutoApproval()` / `processManualApproval()` | Orchestration only |
| `approve()` | → `transitionTo()` | Orchestration only |
| `reject()` | → `transitionTo()` | Orchestration only |
| `completeAdministration()` | → `transitionTo()` + event dispatch | Orchestration only |
| `completeNomination()` | → Own validation logic | ✅ Has candidate-count rules |
| `forceCloseNomination()` | → Own candidacy rejection logic | ✅ Has force-close rules |
| `canEnterVotingPhase()` | → Own logic + config | ✅ Has `min_candidates` config rule |
| `validateTimeline()` | → Own validation | ✅ Has chronological + duration rules |
| `getCurrentStateAttribute()` | → State column (fallback) | Simple accessor (deprecated pattern) |

**Finding:** Of 36 business methods, most are orchestration that delegates to the Constitution/Guard. ~5-6 methods contain genuine rules that aren't currently delegated (completenomination, forceCloseNomination, timeline validation, capacity checks). The 2279-line model is large primarily because it contains many methods, each small — not because it's a God Object.

## 7. Architectural Conclusion

| Question | Answer |
|----------|--------|
| Is the frontend receiving commands, capabilities, or states? | ✅ **Capabilities** — the constitutional governance model exposes pre-computed capability snapshots |
| Is the lifecycle engine truly the SSOT? | ✅ Yes — re-derives state from business facts, ignores state column |
| Is Election.php a God Object? | ❌ **No** — most methods delegate. 5-6 methods contain genuine non-delegated rules. |
| Does the backend→frontend bridge already work? | ✅ Yes — the full chain from Constitution → Guard → Engine → Resolver → Snapshot → Frontend is operational |
| DDD maturity assessment | ✅ The system already contains mature DDD-inspired constitutional governance patterns (Domain policies, value objects, state machine, domain events, capability model). Further discovery required before assessing aggregate boundaries and bounded-context completeness. |
