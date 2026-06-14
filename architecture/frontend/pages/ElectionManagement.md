# ElectionManagement.vue — Page Documentation

## Purpose

Administration dashboard for election lifecycle management. Enables election officers to manage phases, approve governance transitions, configure settings, and publish results.

## Responsibilities

- Election lifecycle state display and control
- Governance workflow: submit, approve, reject, suspend, resume
- Phase transitions: complete administration, open/close voting
- Result management: publish, unpublish
- Voter management links
- Candidate/position navigation
- Logo upload

## Architecture

| Layer | Consumption |
|-------|-------------|
| Layout | `ElectionLayout` |
| Domain | `ElectionApprovalPolicy` (since 2026-06-13), `ElectionPhaseService` (not yet consumed) |
| Application | `useElectionCapabilities` (destructured), `useElectionActions` (not yet consumed) |
| Infrastructure | `router.post/patch` via Inertia |
| Design System | `Card`, `Button`, `StatusBadge`, `ActionButton`, `SectionCard`, `EmptyState` |
| Raw HTML | 5× `<button>`, 3× `<input>`, 1× `<textarea>`, 1× `<select>` |

## Discovery History

| Date | Focus | Finding | Outcome |
|------|-------|---------|---------|
| 2026-06-13 | `useElectionActions` composable reuse | Uses raw `fetch`, not Inertia `router` — not equivalent | ⛔ Migration blocked |
| 2026-06-13 | `ElectionPhaseService.phaseFor()` vs `phaseInfo` | Different output shapes, different purposes | ✗ No refactoring justified |
| 2026-06-13 | `handleDatesUpdated` extraction | Column mapping is persistence knowledge, not domain logic | ✗ No extraction justified |

## Refactoring History

| Date | Increment | Change | Risk | Status |
|------|-----------|--------|------|--------|
| 2026-06-13 | 1 | Extracted `ElectionApprovalPolicy` from inline `voterCount <= 40` check | Low | ✅ Complete |

## Architectural Conclusion

Management.vue discovery is complete. The page is healthier than initially assumed — most of its 1598 lines are presentation logic and application wiring, not hidden domain logic. No further justified extractions identified at this time.

## Technical Debt

| Debt | Severity | Notes |
|------|----------|-------|
| 1598-line page | High | Largest page in the codebase |
| Duplicated `router.post` pattern | Medium | 6 identical patterns, reuse candidate |
| Raw `<button>` / `<input>` | Low | Design system migration candidates |
| `phaseInfo` duplicates `ElectionPhaseService` | Low | Should consume existing domain service |
| `useElectionActions` not consumed | Low | Exists but uses raw `fetch` — not currently safe to migrate |

## References

- [ADR-001: Reuse Before Create](../decisions/ADR-001-Reuse-Before-Create.md)
- [ADR-002: Incremental Frontend DDD Adoption](../decisions/ADR-002-Incremental-Frontend-DDD-Adoption.md)
- [Discovery: Management Analysis](../discoveries/20260613-election-management-analysis.md)
- [Migration: Increment 1](../migrations/20260613-election-management-increment-1.md)
