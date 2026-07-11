# Master Program Backlog

**Status:** living · PROGRAM level only — tickets live in epic files (split per Chief Architect review 2026-07-06, scales to 100+ tickets)
**Process:** `../Implementation_Process_v1.0.md` (FROZEN) — 15-step workflow, DoD, lifecycle/progress separation, derived-percentage rule
**Companions:** `../PROGRAM_STATUS.md` (steering one-pager) · `../IMPLEMENTATION_PROGRESS.md` (burn-up + capability dashboard) · `PB-xxx_PROGRESS.md` / frozen IDDs (per-ticket evidence) · `../DEVELOPMENT_LOG.md` (session journal)
**Synchronized:** 2026-07-10 — one-time, evidence-derived (ARB-amended execution order step 5). Evidence-source table at the bottom.

---

## Milestones

| Milestone | Scope | Progress (derived) | Status |
|-----------|-------|--------------------|--------|
| **M1 — Messaging Infrastructure** | PB-001..003 | 3/3 tickets closed | ✅ Complete (PB-003 CERTIFIED 2026-07-07) |
| **M2 — Correction Loop** | PB-004..005 | 2/2 tickets closed | ✅ Complete (PB-005 closed 2026-07-09) |
| **M3 — Integration & Merge Gate** | PB-006..007 | 2/2 tickets closed | ✅ Complete (PB-007 closed 2026-07-10) |

## Epics

| Epic | Title | Tickets | Progress (derived) | File |
|------|-------|---------|--------------------|------|
| EPIC-001 | Greenfield Core (correction loop) | PB-001..007 | 7/7 closed = **100%** · retrospective conducted 2026-07-11 (record: `../EPIC-001_Retrospective.md`) · **formal closure: PENDING explicit ARB decision** | `EPIC-001_Greenfield_Core.md` |
| EPIC-002 | **Strategic Discovery — candidate BC: Evidence** (discovery may conclude the candidate is too large, is two contexts, or merges elsewhere — it must not assume its own answer; ARB 2026-07-11) | — | not opened — **NEXT: Problem Statement → literature review → Strategic Discovery** | charter: `../EPIC-002_Problem_Statement.md` · opens after EPIC-001 retrospective |
| EPIC-003 | Voting (ballot casting, anonymous storage, tally — migration + verifiability) | — | not opened | after EPIC-002 |
| EPIC-004 | Appointment / Governance (delegates, mandates, authority chains) | — | not opened | after EPIC-003 |
| EPIC-005 | Read Models · Public Transparency | — | not opened | after EPIC-004 |
| EPIC-006 | Election/Lifecycle migration (legacy operational contexts) | — | not opened | sequenced at the retrospective |
| **EPIC-000** | **Engineering Process & Quality** (workstream, not a capability) | ENG-001..004 | — | see below |

*Epic renumbering (2026-07-10, ARB-approved, history-safe — none of EPIC-002..006 had been opened): numbering now matches the ARB roadmap (Evidence → Voting → Appointment → Read Models); the former "EPIC-002 Election/Lifecycle migration" is now EPIC-006.*

## EPIC-000 — Engineering Process & Quality

Engineering-governance workstream — distinct from capability tickets (PB-xxx). Improves tooling, process, and cross-cutting code quality. Never mixed into a capability ticket's commits. **Post-EPIC-001 governance (ARB): the engineering platform is a production subsystem — bug fixes · compatibility fixes · retrospective promotions only.**

| Ticket | Title | Status | Notes |
|--------|-------|--------|-------|
| ENG-001 | Process Learning System | Designed | Improvement Log (PI-xxx), retrospectives, metrics/estimate-calibration, Process v1.1 proposals, dashboard refinements (ADR coverage · Current Deliverable · risk reasons). Evidence stash: session-4 cwd bug, C1 est−58%, docs/code-split. |
| ENG-002 | Shared Infrastructure Static Analysis Alignment | Designed | Bring ALL Shared Infrastructure Eloquent models to PHPStan max **together** (OutboxEvent 7 findings + InboxEvent 11 findings + any siblings). Enforces ER-03. Opened from PB-003-C2 finding. |
| ENG-003 | `*Ref` Value-Object naming consistency sweep | Backlog | After `TargetRef → ContestedOutcomeRef` (ADR-UL-01), review sibling reference VOs for naming/shape consistency (ER-03). Sweep only — no behavior change. Opened from PB-004 step-1 ARB review (2026-07-08). |
| ENG-005 | Stream B Documentation Consolidation | Proposed — NOT activated (ARB 2026-07-11: rule adopted as Engineering Standard; sweep decoupled, priority Low, trigger-based: duplication-caused mistakes · onboarding difficulty · contradictions · slow navigation · maintenance cost) | From retrospective recommendation P-6 (2026-07-11): apply the documentation rule (IDD = implementation decisions · ADR = architectural decisions · retrospective = lessons · CONTEXT = current state only) — refresh stale c4 · remove superseded progress tables · de-duplicate rulings across record types (evidence O-10). No redesign (dependency map §6 Stream B). |
| ENG-004 | Mutation Ratchet 1 | Backlog | First deliberate MSI raise per the A-3 baseline-then-ratchet policy. **Validated baseline (F-7D-2, IDD §2e): MSI 50% · Mutation Code Coverage 77% · Covered-Code MSI (Test Strength) 65% · 224 escaped · 184 uncovered** *(the earlier 75%/96% figures were produced by an execution model that failed evidence validation — history only)*. Scope: extend coverage into the uncovered mutants, then hunt escapees — escapes concentrate in the messaging/persistence boundary of the correction loop (`ChallengeOutboxAdapter` · mappers · hydrators · `InboxExecutionEngine`), the ARB's named highest-value mutation territory. **Enabling sub-item: per-thread test databases via Infection `TEST_TOKEN`** (restores fast AND valid measurement — currently the validated model is 1-thread, ~1h17m). Raise `min-msi` only at completion (never retroactive, never lowered). Opened at ARB 7C acceptance; baseline corrected at F-7D-2 (2026-07-10). |

## Program Health

| Dimension | Health | Basis |
|-----------|--------|-------|
| Architecture | 🟢 GREEN | Blueprint frozen · Deptrac fail-mode 0 violations · Architecture suite 146✔/1 skip |
| Tests | 🟢 GREEN | `composer merge-gate` PASS (fresh, PB-007 IDD §6.1) · GreenfieldCore 179✔/0F |
| Technical Debt | 🟢 GREEN | F-1/F-2 closed by PB-007; AD-006 quarantined with owner; F-7C-1..6 recorded for retrospective |
| Governance track | 🟢 GREEN | EPIC-001 ready for formal closure; retrospective input pack prepared |
| Migration | 🟡 YELLOW | not started by design — EPIC-006, sequenced at the retrospective |
| Production readiness | 🟡 YELLOW | merge gate + CI workflows wired (7E); first real CI run pending push; deployment doc still missing |
| Research | 🟢 GREEN | evidence chain complete; EPIC-002 literature review is the next research activity |

## Debt (separate workstream — never mixed into PB commits)

| ID | Item | Owner | Status |
|----|------|-------|--------|
| AD-006 | FeeTestFactory TenantId mismatch (pre-existing) | Membership | Open |
| F-1 | Deptrac install | Infra | **Closed** — PB-007 7A (`qossmic/deptrac-shim` 1.0.2, documented substitution; fail mode since 7D) |
| F-2 | Infection coverage driver | Infra | **Closed** — PB-007 7C + F-7D-2 (Xdebug per-invocation; validated 1-thread execution model) |
| F-7C-1..6 | Pre-existing repo debt found by 7C sweeps (dead test class · 2 non-parsing scaffold files · legacy test failures · 57 risky Feature tests) | Retrospective | Recorded — PB-007 IDD §2c-ii |

---

## NEXT ACTION (exactly one)

```text
Phase:         EPIC-001 closure decision (retrospective conducted; decisions pending)
Action:        EXPLICIT ARB DECISIONS on P-4 · P-6 · P-7 · D-2 + formal closure
               (record: ../EPIC-001_Retrospective.md)
Then:          EPIC-002 Strategic Discovery Phase 1 (literature review; charter
               ../EPIC-002_Problem_Statement.md; problem-space first; no code/IDD)
```

---

## Synchronization record (2026-07-10 — every change evidence-derived)

| Updated item | Evidence source |
|---|---|
| PB-003 CERTIFIED | ARR `../PB-003_Architecture_Readiness_Report.md` (2026-07-07) |
| PB-004 CLOSED | ARB closure 2026-07-08 · `../PB-004_Retrospective.md` |
| PB-005 CLOSED | ARB closure 2026-07-09 · `PB-005_Contestation_Reaction_Implementation_Design.md` |
| PB-006 CLOSED | ARB closure 2026-07-10 · `PB-006_Integration_Validation_Implementation_Design.md` · ADR-MP-06 |
| PB-007 CLOSED · M1–M3 complete · EPIC-001 100% | `PB-007_Merge_Gate_Implementation_Design.md` §6.5 (closure ruling) |
| Merge gate PASS basis | PB-007 IDD §6.1 (fresh executed run, exit 0) |
| Mutation baseline (ENG-004) | F-7D-2 evidence validation, PB-007 IDD §2e (validated 1-thread model) |
| F-1/F-2 closed | PB-007 7A (IDD §2a/7A records) · 7C/F-7D-2 (IDD §2c-i, §2e) |
| Production readiness 🔴→🟡 | 7E CI workflows exist (IDD §2f); deployment doc still absent |
| Epic renumbering | ARB approval 2026-07-10 (plan review) — EPIC-002..006 all unopened, history-safe |

*Master Program Backlog — milestones, epics, health, debt, ONE next action. Ticket detail lives in epic files; all values derived from closure rulings, gate outputs, or WBS.*
