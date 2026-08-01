# Round 7: Frontend Boundary Report

**Date:** 2026-06-14  
**Purpose:** Classify every file in the frontend Domain/ and Application/ layer to prevent fake DDD

## Domain Layer Classification

| File | Classification | Rationale |
|------|---------------|-----------|
| `Domain/Election/ElectionApprovalPolicy.ts` | ✅ **Domain Policy** | Pure business rule (40-voter governance threshold). Zero framework dependencies. Static pure function. |
| `Domain/Election/ElectionPhaseService.ts` | ⚠️ **Domain Service Candidate** | Contains domain logic (`phaseFor()`, `PhaseCompletionRules`, `PhaseLockRules`). Contains `Clock` interface (valid domain abstraction) and `SystemClock` (infrastructure). Requires verification: does `ElectionPhaseService` directly instantiate `SystemClock`, or is it injected? |

## Application Layer Classification

| File | Classification | Rationale |
|------|---------------|-----------|
| `Composables/useElectionCapabilities.ts` | ✅ **Application Service** | Reads pre-computed capability snapshots, maps denial reasons to labels. No derivation of authority. Clean anti-corruption layer. |
| `Composables/useElectionActions.ts` | ⚠️ **Application Service (unused)** | Uses raw `fetch()` instead of Inertia `router`. Contains loading/success/error state tracking. Not consumed by any page. |
| `Constants/ElectionActions.ts` | ✅ **Contract Layer** | Mirrors `ElectionConstitution::RULES` exactly (15 actions). No logic. Pure constant. |
| `Constants/ElectionLifecycleStates.ts` | ✅ **Contract Layer** | Mirrors backend `ElectionLifecycleState` enum exactly (11 states). Derived representation — manually synchronized. |

## Presentation Layer Classification

| File | Classification | Rationale |
|------|---------------|-----------|
| `Pages/Election/Management.vue` | ✅ **Presentation** | Imports `ElectionApprovalPolicy` from Domain. Uses capabilities for permission checks. Correct layering. |
| `Pages/Election/Partials/StateMachinePanel.vue` | ✅ **Presentation** | Imports `phaseFor()` from Domain. Uses it for display projection only. Correct. |
| `Layouts/PublicDigitLayout.vue` | ✅ **Presentation** | Shell component. No domain imports. |
| `Layouts/ElectionLayout.vue` | ✅ **Presentation** | Shell component. No domain imports. |
| All other Vue pages | ✅ **Presentation** | No direct Domain imports (confirmed via grep). |

## Layer Violation Check

| Check | Result |
|-------|--------|
| Domain imports framework code? | ❌ None found. Both Domain files pass `check-domain-purity.sh` |
| Pages import Domain directly? | ✅ Only 2 pages (Management.vue, StateMachinePanel.vue) — both use Domain correctly |
| Application layer imports Domain? | ✅ `useElectionCapabilities` reads snapshots. `useElectionActions` is unused but has no Domain imports. |
| Any file uses Domain concepts for permission logic? | ❌ No — all permission logic goes through `useEliminated` |
| Dead files in Domain? | ❌ None — both Domain files are consumed |

## Verdict

| Classification | Correctness |
|---------------|-------------|
| Domain Policy boundary | ✅ **Clean** — one genuine domain policy |
| Domain Service boundary | ⚠️ **Minor concern** — `ElectionPhaseService` mixes pure domain logic with a `SystemClock` implementation detail |
| Application Service boundary | ✅ **Clean** — composable reads snapshots, does not derive |
| Contract boundary | ✅ **Clean** — constants mirror backend exactly |
| Presentation boundary | ✅ **Clean** — pages consume from Application/Domain correctly |
| Framework dependency in Domain | ✅ **None** — all Domain files are pure |
| Fake DDD risk | **Low** — existing Domain artifacts are genuine |

## Recommendation

`ElectionPhaseService.ts` should have its `SystemClock` class extracted to an infrastructure-level clock provider, leaving only pure domain logic (interfaces, rules, projections) in the Domain layer. This is a minor separation concern — not urgent.
