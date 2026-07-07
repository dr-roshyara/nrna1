# Master Program Backlog

**Status:** living · PROGRAM level only — tickets live in epic files (split per Chief Architect review 2026-07-06, scales to 100+ tickets)
**Process:** `../Implementation_Process_v1.0.md` (FROZEN) — 15-step workflow, DoD, lifecycle/progress separation, derived-percentage rule
**Companions:** `../PROGRAM_STATUS.md` (steering one-pager) · `../IMPLEMENTATION_PROGRESS.md` (burn-up + capability dashboard) · `PB-xxx_PROGRESS.md` (per-ticket WBS) · `../DEVELOPMENT_LOG.md` (session journal)

---

## Milestones

| Milestone | Scope | Progress (derived from epic WBS) | Status |
|-----------|-------|----------------------------------|--------|
| **M1 — Messaging Infrastructure** | PB-001..003 | 17/35 WBS = 49% | In progress (PB-003 next) |
| **M2 — Correction Loop** | PB-004..005 | 0/46 *(est.)* = 0% | Waiting on M1 |
| **M3 — Integration & Merge Gate** | PB-006..007 | 0/22 *(est.)* = 0% | Waiting on M2 |

## Epics

| Epic | Title | Tickets | Progress (derived) | File |
|------|-------|---------|--------------------|------|
| EPIC-001 | Greenfield Core (correction loop) | PB-001..007 | 17/103 = **17%** | `EPIC-001_Greenfield_Core.md` |
| EPIC-002 | Election/Lifecycle migration | — | not opened | opens after Push B |
| EPIC-003 | Evidence context | — | not opened | opens after Push B |
| EPIC-004 | Voting context migration | — | not opened | opens after Push B |
| EPIC-005 | Read Models · Audit · Replay | — | not opened | opens after Push B |
| **EPIC-000** | **Engineering Process & Quality** (workstream, not a capability) | ENG-001, ENG-002 | — | see below |

## EPIC-000 — Engineering Process & Quality

Engineering-governance workstream — distinct from capability tickets (PB-xxx). Improves tooling, process, and cross-cutting code quality. Never mixed into a capability ticket's commits.

| Ticket | Title | Status | Notes |
|--------|-------|--------|-------|
| ENG-001 | Process Learning System | Designed | Improvement Log (PI-xxx), retrospectives, metrics/estimate-calibration, Process v1.1 proposals, dashboard refinements (ADR coverage · Current Deliverable · risk reasons). Evidence stash: session-4 cwd bug, C1 est−58%, docs/code-split. |
| ENG-002 | Shared Infrastructure Static Analysis Alignment | Designed | Bring ALL Shared Infrastructure Eloquent models to PHPStan max **together** (OutboxEvent 7 findings + InboxEvent 11 findings + any siblings). Enforces ER-03. Opened from PB-003-C2 finding: ad-hoc max is inconsistent with the mirror convention + official gate excludes `Shared` ("widen as contexts migrate"). Evidence: no ADR/Blueprint mandates Shared exclusion — it is current gate scope, an engineering observation, not an architectural decision. |
| ENG-003 | `*Ref` Value-Object naming consistency sweep | Backlog | After `TargetRef → ContestedOutcomeRef` (ADR-UL-01), review sibling reference VOs (`RaiserStandingRef`, and any `*Ref` in other contexts) for naming/shape consistency (ER-03). Sweep only — no behavior change; batch under EPIC-000. Opened from PB-004 step-1 ARB review (2026-07-08). |

## Program Health

| Dimension | Health | Basis |
|-----------|--------|-------|
| Architecture | 🟢 GREEN | Blueprint frozen; zero open architecture questions in Push B |
| Tests | 🟢 GREEN | Arch 133/133 · greenfield PHPStan clean · all PB gates passing |
| Technical Debt | 🟢 GREEN | AD-001..005 cleared; AD-006 quarantined with owner |
| Governance track | 🟢 GREEN | 38C closed; 38D-01 accepted |
| Migration | 🟡 YELLOW | not started by design — sequenced after Push B |
| Production readiness | 🔴 RED | F-1 Deptrac + F-2 Infection unwired; no deployment doc; expected at this stage |
| Research | 🟢 GREEN | evidence chain complete; external validation optional |

## Debt (separate workstream — never mixed into PB commits)

| ID | Item | Owner | Status |
|----|------|-------|--------|
| AD-006 | FeeTestFactory TenantId mismatch (pre-existing) | Membership | Open |
| F-1 | Deptrac PHAR install | Infra | Open — M3 gate |
| F-2 | Infection coverage driver | Infra | Open — M3 gate |

---

## NEXT ACTION (exactly one)

```text
Milestone:     M1 — Messaging Infrastructure (49%)
Ticket:        PB-003 — Inbox / Deduplication
Lifecycle:     Approved  ·  Progress: 0/18 (derived)
Branch:        feature/pb003
Dependencies:  all met (PB-001 ✔ PB-002 ✔)
First step:    Process step 4 (RED) — IDD §12 step 1: port package unit tests
```

---
*Master Program Backlog — milestones, epics, health, debt, ONE next action. Ticket detail lives in epic files; all percentages derived from WBS.*
