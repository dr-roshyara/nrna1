# Frontend DDD Discovery — Phase 1 Summary

**Date:** 2026-06-13  
**Status:** Completed  
**Phase Duration:** Single session (all discoveries within 2026-06-13)

## Pages Investigated

| Page | Lines | Script % | Method Count | Business Rules Found |
|------|-------|----------|-------------|---------------------|
| `Election/Management.vue` | 1598 | 29% | 16 | 1 (≤40 voter threshold) |
| `Election/Candidacy/Apply.vue` | 1076 | 10% | 4 | 0 |
| `Vote/CreateVote.vue` | 1408 | 33% | ~10 | 0 (backend-owned) |
| `Election/ElectionPhaseService.ts` | (Domain) | — | — | Already exists |
| `Composables/useElectionActions.ts` | (Application) | — | — | Transport mismatch |

## Candidates Evaluated

| Candidate | Verdict | Reason | Evidence |
|-----------|---------|--------|----------|
| `ElectionApprovalPolicy` | ✅ **Extracted** | Genuine governance rule with business meaning | Documentation |
| `useElectionActions` reuse | ❌ Blocked | Raw `fetch` ≠ Inertia `router` — not behaviourally equivalent | `election-actions-assessment.md` |
| `phaseInfo` → `phaseFor()` | ❌ Rejected | Different output shapes, different purposes. `phaseInfo` is correct presentation logic. | `phase-service-assessment.md` |
| `handleDatesUpdated` | ❌ Rejected | Column mapping is persistence knowledge, not domain logic. `convertToISO` extraction premature (only 1 consumer). | `handleDatesUpdated-assessment.md` |
| `Apply.vue` extraction | ❌ Rejected | 57% CSS, 10% script. All form logic is presentation-layer UX guards. No domain invariants. | `candidacy-apply-assessment.md` |
| `CreateVote.vue` hardcoded post IDs | ❌ Rejected (ADR-001) | Backend `posts` table already owns all post definitions. Frontend Domain extraction would duplicate and violate ADR-001. | `create-vote-assessment.md`, `election-post-source-analysis.md` |

## Extractions Performed

| Extraction | Layer | Lines | Risk |
|------------|-------|-------|------|
| `ElectionApprovalPolicy.shouldAutoSubmit()` | `Domain/Election/` | ~36 | Very low |

## Key Architecture Artifacts Created

| Artifact | Location | Purpose |
|----------|----------|---------|
| ADR-001 | `decisions/ADR-001-Reuse-Before-Create.md` | Principle: reuse before creating new abstractions |
| ADR-002 | `decisions/ADR-002-Incremental-Frontend-DDD-Adoption.md` | Strategy: incremental adoption, no rewrite |
| `ElectionApprovalPolicy.ts` | `Domain/Election/` | First production domain policy in frontend |
| `ElectionManagement.md` | `pages/` | Per-page architecture documentation |
| Discovery docs (5) | `discoveries/` | Full analysis trail |
| Migration plan | `migrations/` | Process template for future increments |

## Architectural Lessons Learned

1. **Large files ≠ complex domain.** Apply.vue (1076 lines) is 57% CSS. Management.vue (1598 lines) is mostly orchestration. Line count is a misleading metric for DDD value.

2. **Genuine domain logic is rare in Vue pages.** Only 1 of 6 candidates yielded an extraction. Most "business rules" in pages are UX validation guards that duplicate backend enforcement.

3. **Backend often owns the real domain.** The strongest frontend candidate (19 hardcoded post IDs) turned out to be a backend-consumption gap. The backend already has the `posts` table with all definitions.

4. **Application-layer adoption, not creation, is the gap.** `useElectionActions` already exists but cannot be consumed due to the `fetch`/Inertia `router` mismatch. The problem is not missing abstractions but mismatched ones.

5. **Discovery-cycle prevents wasted work.** 5 of 6 candidates (83%) were correctly rejected before any code was written.

## Future Triggers for Reopening Discovery

- New election governance features requiring frontend domain logic
- Backend API changes that expose post definitions or other currently hardcoded data
- The `useElectionActions` / Inertia `router` gap is resolved
- A new page with actual business-rule branching (not form validation) is introduced

## Related

- [ADR-001: Reuse Before Create](../decisions/ADR-001-Reuse-Before-Create.md)
- [ADR-002: Incremental Frontend DDD Adoption](../decisions/ADR-002-Incremental-Frontend-DDD-Adoption.md)
- [Migration: Increment 1](../migrations/20260613-election-management-increment-1.md)
