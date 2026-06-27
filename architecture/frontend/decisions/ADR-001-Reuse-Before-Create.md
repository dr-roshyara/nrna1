# ADR-001: Reuse Before Create

**Status:** Accepted  
**Date:** 2026-06-13  
**Context:** Frontend DDD adoption strategy

## Context

During discovery of `Election/Management.vue`, we identified 7 inline `router.post` orchestration calls with an identical pattern (`isLoading → router.post → onFinish`).

Simultaneously, we discovered that the codebase already contains `useElectionActions` — an application-layer composable that encapsulates this exact orchestration pattern with loading/success/error state tracking and audit logging.

This duplication means:
- The team may not know the existing abstraction exists
- Maintenance cost is higher than necessary
- Architectural layering appears flatter than it actually is

## Decision

This principle applies to all architectural assets, including:

- Components
- Composables
- Domain services
- Application services
- Utilities and helpers
- Contracts and types
- State machines
- Governance scripts
- Documentation templates

The goal is to maximize architectural coherence and minimize parallel implementations.

Before introducing any new frontend asset, existing ones must be discovered and evaluated for reuse.

**The burden of proof is on creating a new abstraction.**

A new abstraction may only be introduced when:

1. No suitable abstraction exists.
2. Existing abstractions cannot be extended without violating their responsibility.
3. Reuse would introduce greater complexity than creation.

This applies specifically to:
- Composables (`useElectionCapabilities`, `useElectionActions`, etc.)
- Domain services (`ElectionPhaseService`)
- Constants and types (`ElectionActions`, `ElectionLifecycleStates`, `StateMachineContract`)

## Evidence

Evidence discovered during `Election/Management.vue` analysis:

Existing reusable artifacts already in the codebase:

| Artifact | Location | Layer |
|----------|----------|-------|
| `ElectionPhaseService` | `Domain/Election/` | Domain |
| `useElectionCapabilities` | `Composables/` | Application |
| `useElectionActions` | `Composables/` | Application |
| `ElectionActions` | `Constants/` | Domain |
| `ElectionLifecycleStates` | `Constants/` | Domain (generated) |
| `StateMachineContract` | `types/` | Domain |

These artifacts demonstrate that architectural reuse opportunities already exist within the codebase, and that creating parallel abstractions would increase architectural entropy.

## Verification

- `check-domain-purity.sh` — confirms `Domain/Election/ElectionPhaseService.ts` has zero framework dependencies ✅
- `structure-check.sh` — shows Election context has Domain coverage but no Application/UseCases yet
- `useElectionActions` — exists in `Composables/` with 0 consumers in `Pages/` (confirmed by grep)

## Consequences

**Positive:**
- Prevents parallel architectural layers from diverging
- Encourages team discovery of existing codebase
- Reduces overall abstraction count
- Validates existing abstractions by consuming them

**Negative:**
- May require small adapters when existing abstractions don't perfectly match
- Requires team to know what exists before creating new code

## Alternatives Considered

1. **Create new UseCase files immediately** — Rejected. Would increase architectural entropy before existing abstractions are leveraged.
2. **Leave all orchestration inline** — Rejected. Duplicated patterns increase maintenance cost and hide the application layer.

## Related

- [ADR-002: Incremental Frontend DDD Adoption](ADR-002-Incremental-Frontend-DDD-Adoption.md)
- [Discovery: Election Actions Assessment](../discoveries/20260613-election-actions-assessment.md)
- [Discovery: Election Management Analysis](../discoveries/20260613-election-management-analysis.md)
