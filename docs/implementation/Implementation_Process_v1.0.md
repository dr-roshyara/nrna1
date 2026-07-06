# Implementation Process v1.0 — FROZEN

**Status:** FROZEN 2026-07-06 (Chief Architect approval: "approve this process as your implementation governance framework"). Changes only via Process v1.1 — never in-place. **No ticket may skip any step.**

## The Workflow (every PB ticket, in order)

```text
 1. Ticket          opened in epic file with Blueprint §/ADR refs, size, risk, dependencies
 2. IDD             17-section Implementation Design Document (IDD_Prompt_Template)
 3. Architecture Review   IDD approved by Chief Architect — lifecycle → Approved
 4. RED             failing tests first — no production class without a failing test
 5. GREEN           minimal implementation to green
 6. REFACTOR        clean up under green tests
 7. PHPStan         greenfield gate (phpstan-greenfield.neon) + ad-hoc max on new classes
 8. Architecture Tests    full suite; zero regressions
 9. Mutation        Infection (once F-2 wired; until then recorded as PENDING, never skipped silently)
10. Traceability    Matrix row updated; class docblocks carry Blueprint §/ADR/Matrix/Context
11. Decision Log    entry with Impact, or explicit "no new implementation decisions"
12. Progress Update PB-xxx_PROGRESS.md WBS ticked; ALL percentages derived (see rule below)
13. Development Log session entry (facts only)
14. PR              references ticket ID; Architecture Review Checklist answered
15. Merge           lifecycle → Verified
```

## Lifecycle vs Progress (two different concepts — never conflated)

- **Lifecycle (governance):** `Designed → Approved → In Development → Implemented → Verified → Released` · plus flag **Blocked (waiting on: X)** — a ticket whose dependency or an external factor prevents work; distinct from merely not-yet-started.
- **Progress (execution):** `completed WBS items / total WBS items`. **Percentages are NEVER written by hand — always derived.** Tickets without an approved IDD carry *(est.)* WBS counts from their size class; the estimate is replaced by the IDD's actual WBS on approval.

## Definition of Done (a ticket is 100% / Verified ONLY when every box is checked)

```text
□ IDD approved
□ All planned classes implemented
□ All RED tests turned GREEN
□ Refactoring complete
□ PHPStan clean (greenfield gate)
□ Architecture tests green (full suite, no regressions)
□ Integration tests green (if applicable)
□ Mutation tests green (or recorded PENDING-F-2 with Chief Architect visibility)
□ Traceability updated
□ Decision Log updated (or explicit "no decision")
□ Progress tracker updated (derived %)
□ Development log updated
□ PR reviewed (Architecture Review Checklist)
□ Merged
```

Only then: Progress = 100% and Lifecycle = Verified. No subjectivity, no partial credit.

## Milestones (communication layer above tickets)

| Milestone | Contents | Meaning |
|-----------|----------|---------|
| **M1 — Messaging Infrastructure** | PB-001 · PB-002 · PB-003 | events can be produced, delivered, and consumed effectively-once |
| **M2 — Correction Loop** | PB-004 · PB-005 | the constitutional loop closes end-to-end |
| **M3 — Integration & Merge Gate** | PB-006 · PB-007 | proven by IT-1..8; all gates green; merged |

A milestone completes when all its tickets reach Verified.

---
*Implementation Process v1.0 — FROZEN. Workflow (15 steps) · lifecycle/progress separation · derived-percentage rule · DoD · milestones. Referenced by BACKLOG.md and every PB ticket.*
