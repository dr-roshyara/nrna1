# Frontend DDD Discovery Phase — Complete

**Date:** 2026-06-13  
**Status:** Phase complete — transitioning to backend discovery

## Summary

| Metric | Count |
|--------|-------|
| Pages investigated | 5 Vue pages + 3 domain services/composables |
| Discovery documents | 8 |
| Extractions performed | 1 (`ElectionApprovalPolicy`) |
| Architectural gaps found | 1 (`useElectionActions` vs Inertia `router`) |
| Dead code identified | 1 (`CreateVote.vue` — unreachable legacy page) |
| Refactorings prevented | 5 |

## Discoveries in Full

| # | Document | Target | Verdict |
|---|----------|--------|---------|
| 1 | `election-management-analysis.md` | Management.vue (1598 LOC) | ✅ Extracted `ElectionApprovalPolicy` |
| 2 | `election-actions-assessment.md` | `useElectionActions` composable | ⛔ Blocked — `fetch` ≠ Inertia `router` |
| 3 | `phase-service-assessment.md` | `phaseInfo` vs `ElectionPhaseService` | ✗ Not justified — different purposes |
| 4 | `handleDatesUpdated-assessment.md` | Date column mapping | ✗ Not justified — persistence knowledge |
| 5 | `candidacy-apply-assessment.md` | Apply.vue (1076 LOC) | ✗ Not justified — 57% CSS, 10% script |
| 6 | `create-vote-assessment.md` | CreateVote.vue (1408 LOC, 19 post IDs) | ✗ Backend already owns post data |
| 7 | `election-post-source-analysis.md` | Post ID origin trace | ✗ Backend `posts` table is source of truth |
| 8 | `createvote-contract-divergence.md` | Route analysis | ✗ Dead code — route commented out |

## Architecture Artifacts Created

| Artifact | Location |
|----------|----------|
| ADR-001: Reuse Before Create | `decisions/ADR-001-Reuse-Before-Create.md` |
| ADR-002: Incremental Frontend DDD Adoption | `decisions/ADR-002-Incremental-Frontend-DDD-Adoption.md` |
| ADR-003: Shell Visual Identity | `decisions/ADR-003-Shell-Visual-Identity.md` |
| ElectionApprovalPolicy (production code) | `resources/js/Domain/Election/ElectionApprovalPolicy.ts` |
| Governance scripts (6) | `scripts/` |
| Architecture documentation framework | `architecture/frontend/` |

## Key Lessons Learned

1. **Large files ≠ complex domain.** Apply.vue (1076 LOC) is 57% CSS. Management.vue (1598 LOC) is mostly orchestration.

2. **Genuine frontend domain logic is rare.** Only 1 of 7 candidates yielded an extraction. Most "business rules" in Vue pages are UX validation.

3. **Backend currently contains the majority of authoritative domain rules.** Every frontend domain investigation eventually pointed to backend artifacts — `Post`, `Election`, capability snapshots. Note: DDD does not mean "backend = domain, frontend = UI." The `ElectionApprovalPolicy` extraction proves the frontend can legitimately contain domain policies.

4. **Discovery prevents wasted work.** 6 of 7 candidates were correctly rejected before any code was written.

5. **Discovery should precede refactoring.** The discovery process produced one justified extraction and prevented multiple unnecessary abstractions. Evidence-driven architecture yielded better results than assumption-driven refactoring.

6. **Dead code hides in large files.** `CreateVote.vue` (1408 LOC) looked like a domain-rich target but was actually unreachable legacy code.

## Decision

Frontend DDD discovery is complete. Further frontend-only investigation has diminishing returns.

The next highest-value DDD work is backend domain evidence collection — not aggregate modeling:

1. `app/Models/Election.php` — model, policies, lifecycle
2. `app/Models/Candidacy.php` — nomination context
3. `app/Models/Vote.php` — voting context
4. `app/Policies/*` — authorization rules
5. `app/Services/*` — business logic currently in services

The discovery must determine whether Election, Vote, Candidacy, and Organisation are separate aggregates or part of a single bounded context. See the first backend discovery at `discoveries/20260613-election-domain-inventory.md`.
