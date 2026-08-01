# ADR-002: Incremental Frontend DDD Adoption

**Status:** Accepted  
**Date:** 2026-06-13  
**Context:** NRNA is deep in development — a frontend rewrite is not viable

## Context

NRNA's frontend is ~226 Vue pages across 13 layouts. The codebase has grown organically with inconsistent application of architectural patterns. A complete DDD rewrite would risk months of delay with no immediate business value.

However, the codebase already contains emerging DDD artifacts (`ElectionPhaseService`, `useElectionCapabilities`, `useElectionActions`, etc.) that demonstrate the team is moving toward better separation of concerns.

Frontend architecture must support delivery, not replace it. The prioritization remains:

1. Constitutional correctness
2. Election integrity
3. Auditability
4. Architecture quality

## Current Architectural Baseline

Existing reusable artifacts (discovered during Election/Management.vue analysis):

| Artifact | Location | Layer |
|----------|----------|-------|
| `ElectionPhaseService` | `Domain/Election/` | Domain |
| `useElectionCapabilities` | `Composables/` | Application |
| `useElectionActions` | `Composables/` | Application |
| `ElectionActions` | `Constants/` | Domain |
| `ElectionLifecycleStates` | `Constants/` | Domain (generated) |
| `StateMachineContract` | `types/` | Domain |

Incremental DDD adoption builds upon these existing artifacts rather than replacing them.

## Decision

Adopt DDD incrementally:

1. **Preserve existing working code** unless there is a clear business, architectural, quality, or maintainability justification for change. No big-bang rewrite. No folder reorganization. No forced bounded contexts.
2. **New business logic goes into UseCases.** For new features, create `Application/<Context>/<UseCase>.ts`.
3. **Domain concepts go into `Domain/`.** Only for new concepts actually needed — no speculative models.
4. **Existing Pages stay as-is.** Only refactor a page when it is already being modified (Boy Scout Rule).
5. **Governance scripts protect quality.** `check-domain-purity.sh` and `structure-check.sh` currently advisory-only. Target: blocking for new code after maturity threshold reached.
6. **Architecture documentation is mandatory.** Every increment must produce a discovery document, migration plan, and update page documentation.

## Reuse First

Incremental DDD adoption does not imply creating new abstractions.

Existing domain services, composables, constants, and application services must be evaluated before introducing new structures. See [ADR-001](ADR-001-Reuse-Before-Create.md).

## Success Metrics

The following indicate successful adoption:
- Reduced duplicated business logic in Pages/
- Increased reuse of domain services and application services
- Growth of documented architectural decisions
- Governance scripts consistently passing on refactored pages
- New features following the documented architecture
- No regressions in existing page behavior

## Evolution Strategy

| Phase | Focus | Duration |
|-------|-------|----------|
| **Phase 1** | Discovery, documentation, governance framework | Current |
| **Phase 2** | Reuse existing abstractions (composables, services) | Next |
| **Phase 3** | Introduce UseCases where justified | After Phase 2 |
| **Phase 4** | Extract domain policies and services | Incremental |
| **Phase 5** | Reassess governance enforcement levels | After maturity |

## Consequences

**Positive:**
- No destabilizing rewrite
- Architectural direction established for 1–2 years
- Team can learn DDD patterns gradually
- Existing investment preserved

**Negative:**
- Existing pages remain non-DDD for the foreseeable future
- Architectural inconsistency between old and new pages during transition
- Requires discipline to not "just fix" old code when passing through

## Alternatives Considered

1. **Full frontend DDD rewrite** — Rejected. Too risky. Would delay constitutional governance work (Priority 1-3).
2. **No DDD adoption** — Rejected. Without architectural direction, the frontend will continue accumulating inconsistent patterns.
3. **Big-bang folder reorganization** — Rejected. Moving files without changing behavior creates churn with zero architectural value.

## Related

- [ADR-001: Reuse Before Create](ADR-001-Reuse-Before-Create.md)
- Architecture governance: `check-domain-purity.sh`, `structure-check.sh`
- [Discovery: Election Management Analysis](../discoveries/20260613-election-management-analysis.md)
