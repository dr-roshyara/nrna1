# Discovery: Election State Machine Analysis

**Date:** 2026-06-13  
**Phase:** Phase 2 — Backend Domain Discovery (Round 2)  
**Status:** Complete — evidence collected

## Files Analyzed

| File | Lines | Role |
|------|-------|------|
| `Constitution/ElectionConstitution.php` | ~190 | **SSOT** — defines all allowed transitions, roles, preconditions |
| `StateMachine/ElectionStateMachine.php` | 77 | **Deprecated** — legacy shell, superseded by ElectionConstitution |
| `Services/ElectionLifecycleEngine.php` | 42 | **Interface** — computes canonical lifecycle state |
| `Services/ConstitutionalTransitionGuard.php` | ~228 | **Enforcer** — hard gate that validates against Constitution before mutations |

## Question 1: Authoritative Source of Lifecycle Truth

| Layer | Authoritative? | Evidence |
|-------|---------------|----------|
| **`ElectionConstitution::RULES`** | ✅ **Yes** | "THE SINGLE SOURCE OF TRUTH for what actions are constitutionally allowed" — docblock |
| **`ConstitutionalTransitionGuard`** | ✅ **Yes (enforcer)** | Reads Constitution. No bypasses. Reports to ConstitutionalMetrics. |
| **`ElectionStateMachine`** | ❌ **Marked deprecated** | Docblock says "Compatibility shell only" |
| **`Election::transitionTo()`** | ⚠️ **Implements** | Delegates to Guard, then performs the actual transition + side effects |

**Finding:** The Constitution owns the rules. The Guard enforces them. Election.php orchestrates the execution. This is a clean separation — the codebase already has a well-architected state machine pattern.

## Question 2: Where Invariants Are Enforced

| Invariant | Enforced In | Type |
|-----------|-------------|------|
| Action defined in constitution | `Guard::assertAllowed()` check 1 | Hard gate |
| Action allowed in current state | `Guard::assertAllowed()` check 2 | Hard gate |
| User has required role | `Guard::assertAllowed()` check 3 | Hard gate |
| Preconditions met | `Guard::assertAllowed()` check 4 | Hard gate |
| Constitutional fields immutable | `Election::booted()` saving hook | Model event |
| State writes via authorization context | `Election::setStateAttribute()` | State write barrier |
| Timeline chronological order | `Election::validateTimeline()` | Validation method |

**Finding:** Invariants are enforced at multiple layers — constitutional rules in the Domain, guards in Application, and defensive checks in the Model. No single point of failure; the system uses defense in depth.

## Question 3: Dependency Graph

```
                    ┌──────────────────────┐
                    │ ElectionConstitution  │  ← SSOT: defines transitions, roles, preconditions
                    │ (Domain/)            │
                    └──────────┬───────────┘
                               │ reads
                    ┌──────────▼───────────┐
                    │ ConstitutionalGuard  │  ← Enforcer: validates action, state, role, preconditions
                    │ (Application/)      │      Reports denials to ConstitutionalMetrics
                    └──────────┬───────────┘
                               │ protects
                    ┌──────────▼───────────┐
                    │ Election::transitionTo()│  ← Orchestrator: lock → guard → validate → state change
                    │ (Model)              │      → side effects → events
                    └──────────┬───────────┘
                               │ computes
                    ┌──────────▼───────────┐
                    │ ElectionLifecycleEngine │  ← Read model: computes canonical state from ALL signals
                    │ (Domain/Services/)   │      (state column + time windows + completion flags)
                    └──────────┬───────────┘
                               │ produces
                    ┌──────────▼───────────┐
                    │ ElectionLifecycleSnapshot │  ← Immutable read model consumed by frontend
                    │ (ValueObjects/)      │
                    └──────────────────────┘
```

## Preconditions Registry

The Constitution defines 7 preconditions enforced by the Guard:

| Precondition | Evaluated In | Business Meaning |
|-------------|-------------|------------------|
| `has_posts` | `Guard::isPreconditionMet()` | At least one position defined |
| `has_voters` | `Guard::isPreconditionMet()` | At least one active voter |
| `has_chief` | `Guard::isPreconditionMet()` | Chief election officer assigned |
| `has_approved_candidates` | `Guard::isPreconditionMet()` | Nomination phase completed |
| `voting_window_defined` | `Guard::isPreconditionMet()` | Start/end dates set |
| `timezone_set` | `Guard::isPreconditionMet()` | Timezone configured |
| `capacity_eligibility` | `Guard::isCapacityEligible()` | ≤40 voters or payment authorized |

## Key Architectural Finding

The system already has a **Constitutional Governance Architecture** at the backend level:

1. **ElectionConstitution** — defines WHAT transitions are possible (rules)
2. **ConstitutionalTransitionGuard** — enforces THAT they are followed (policing)
3. **Election::transitionTo()** — executes the transition (action)
4. **ElectionLifecycleEngine** — computes the current state for consumers (reads)
5. **ConstitutionalMetrics** — monitors violations (observability)

This is more mature than many DDD projects that stop at the aggregate root pattern. The backend discovery confirms that the authoritative domain rules live here — the frontend correctly consumes capability snapshots rather than reinventing them.

## Evidence Summary

| Claim | Supported? | Evidence |
|-------|-----------|----------|
| Constitution is SSOT | ✅ Yes | Clear docblock, Guard exclusively reads Constitution |
| Guard is hard gate | ✅ Yes | Throws on any violation, no bypasses |
| Frontend consumes snapshots | ✅ Yes | `ElectionLifecycleSnapshot` → Inertia → frontend |
| Invariants are layered | ✅ Yes | Constitution + Guard + Model hooks |
| ElectionStateMachine deprecated | ✅ Yes | Explicitly marked, superseded by Constitution + Guard |
| Preconditions are centralized | ✅ Yes | All defined in Constitution RULES, checked in Guard |

## Not Yet Investigated

- `ElectionLifecycleEngineImpl.php` — the concrete implementation that computes snapshot
- `ElectionCapabilityResolver.php` — produces frontend capability snapshots
- Overall aggregate boundary determination
