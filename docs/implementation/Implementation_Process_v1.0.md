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
 7. PHPStan         CAPABILITY GATE: official greenfield gate (phpstan-greenfield.neon) — MANDATORY (see Gate Classes below)
 8. Architecture Tests    full suite; zero regressions
 9. Mutation        Infection (once F-2 wired; until then recorded as PENDING, never skipped silently)
10. Traceability    Matrix row updated; class docblocks carry Blueprint §/ADR/Matrix/Context
11. Decision Log    entry with Impact, or explicit "no new implementation decisions"
12. Progress Update PB-xxx_PROGRESS.md WBS ticked; ALL percentages derived (see rule below)
13. Development Log session entry (facts only)
14. PR              references ticket ID; Architecture Review Checklist answered
15. Merge           lifecycle → Verified
```

## Gate Classes — Capability · Architecture · Engineering (process lesson, PB-003-C2, 2026-07-06)

A capability ticket (PB-xxx) is verified against **Capability + Architecture Gates only**. Tooling/code-quality standards are **Engineering Improvement Gates** and belong to EPIC-000 engineering tickets — a discovered quality opportunity never blocks a business feature.

| Gate | Class | Required for a PB ticket? |
|------|-------|---------------------------|
| Unit/feature/integration tests (RED→GREEN) | **Capability** | ✅ Yes |
| Official greenfield PHPStan (`phpstan-greenfield.neon`) | **Capability** | ✅ Yes |
| Architecture Fitness Tests (full suite) | **Architecture** | ✅ Yes |
| Architecture Review (human checkpoint) | **Architecture** | ✅ Yes (ticket boundary) |
| Ad-hoc `phpstan --level=max` on individual files | **Engineering Improvement** | Only when explicitly scoped (tooling/quality ticket) |
| Mutation (Infection) | **Engineering Improvement** | PB-007 / when F-2 wired |

Commit rule: commit a capability ticket after all **Capability + Architecture** gates pass, **while recording any non-blocking engineering observations as EPIC-000 backlog items** (transparency without hidden debt).
Report wording: "Architecture Fitness Tests N/N PASS" (fitness tests) is reported separately from "Architecture Review" (human checkpoint) — they are different things.
*(Candidate for EKP promotion under ENG-001 — this taxonomy + the ER rules below are reusable engineering knowledge, not PB-003-specific.)*

### Engineering Rules (ER) — stable IDs (consolidated 2026-07-06 per Principal Architect numbering)

**ER-01 — Architecture Before Implementation.** Capability implementation must not redefine approved architecture (Blueprint, ADRs, IDD are frozen). Drift discovered during a ticket → STOP, document, request an ADR/Blueprint review — never solve architecture problems inside a capability commit.

**ER-02 — Evidence Before Governance.** Never infer architectural intent from current implementation or tooling configuration. When a tool/test/gate appears inconsistent: (1) search authoritative evidence (ADR · Blueprint · AKB/EKP · implementation docs); (2) if evidence exists, follow it; (3) if none exists, document the observation; (4) **absence of evidence is not evidence of intent**; (5) if governance must change, propose an ADR/engineering-process update — do not silently change the workflow. *(Origin: PB-003-C2 — "Shared excluded from PHPStan" was current gate scope ["widen as contexts migrate"], not a documented decision; intentional-scope / incremental / historical-accident were indistinguishable without evidence.)*

**ER-03 — Maintain Sibling Consistency.** When improving a shared technical standard within an architectural layer: upgrade **all sibling components together**, OR defer to a dedicated EPIC-000 engineering backlog item. Do not introduce divergent standards between equivalent components (Outbox↔Inbox, Challenge↔Determination, repository implementations, mappers, registries, transaction decorators, future operational contexts) unless an ADR explicitly justifies the difference. *(Origin: PB-003-C2 — holding InboxEvent to ad-hoc max while its mirror OutboxEvent is not would have created accidental divergence; deferred to ENG-002.)*

**ER-04 — Run doc/script commands from repo root (operational).** Absolute `cd` to repo root for hooks/doc updates. *(Origin: session-4 — a working-directory-dependent sed batch silently skipped.)*

*(ID reconciliation: this consolidates the rules first minted earlier in commit bfb7e4ca9 [then ER-01 repo-root · ER-02 pointer · ER-03 sibling · ER-04 validate-intent] onto the Principal Architect's numbering. Mapping: old ER-04 → ER-02 · old ER-03 → ER-03 · old ER-01 → ER-04 · new ER-01 formalizes the frozen-architecture rule. No rule content was lost; done under ER-02 — evidence = architect's explicit numbering, recorded not silent.)*

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
