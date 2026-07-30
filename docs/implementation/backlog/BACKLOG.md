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
| EPIC-001 | Greenfield Core (correction loop) | PB-001..007 | 7/7 closed = **100%** · **FORMALLY CLOSED (explicit ARB decision, 2026-07-11** — record: `../EPIC-001_Retrospective.md`**)** | `EPIC-001_Greenfield_Core.md` |
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
| ENG-006 | **Engineering Platform developer guide (DoD gap)** | Backlog — **documentation debt** | EG-001..EG-003 (2026-07-26) shipped production-path code with **no developer guide**: `scripts/lib/config-guard.sh` (new) · four gate scripts · `.husky/pre-push` · `scripts/verify.sh` · `role-permission-verification.yml` · `PermissionSeeder.php`. `scripts/README.md` is a **hazard note** (jq impostor + self-check), not a guide — it lacks purpose · architectural role · design decisions/ADR refs · usage · extension · testing · pitfalls · traceability. **Scope:** fail-closed config parsing (`require_int`) · the jq authenticity hazard · role-gate relocation to CI (enforcement point matches what is validated) · gate-chain reliability. **Scheduled point:** alongside **EG-005 Engineering Platform Qualification** (its natural certification home). Opened from operational evidence, 2026-07-30. Does NOT block WP-3. |
| ENG-007 | **`dev-guide-reminder.sh` area-mapping refactor (tooling debt)** | Backlog — **tooling debt** | The hook's model of "implementation areas" is incomplete: it maps only `app/Contexts/<X>/`, `app/<X>/`, `database/migrations/` — so `scripts/`, `.husky/`, `.github/workflows/` produce **no** code-area and can never raise a missing-guide nudge (the ENG-006 gap passed undetected). **Both mapping defects are now observed in practice: too broad** (PB-007 false positives, deferred as "reminder refinement") **and too narrow** (this false negative) — the mapping should be treated as a maintained architectural component, not an ad-hoc regex list. **DA ruling (2026-07-30): do NOT add another over-general prefix rule.** `scripts/* → merge_gate` was proposed and **rejected** — it repeats the very defect being fixed, because ownership differs per path: `verify.sh`/`design-check.sh`/`check-design-tokens.sh`/`component-audit.sh`/`.husky/*`/`.github/workflows/*` → **merge_gate**; `scripts/lib/config-guard.sh` → **ai_platform**. **Required shape: an explicit per-path ownership map (a bounded-context router), preventing both false positives and false negatives.** **INVESTIGATION COMPLETE (2026-07-30): `engineering/verification/reports/2026-07-30-developer-guide-ownership-investigation.md`** — root cause is a **category error** (routes by directory; ownership is architectural — `scripts/design-check.sh` and `scripts/check_roles.php` share a directory but differ in owner, so no prefix rule can be correct). **Two proposed mappings REFUTED by evidence:** `design-check.sh → merge_gate` is wrong (the design chain and `composer merge-gate` are **disjoint** — 0 shared members; design-check belongs to the Design System / `color_theme/`), and `config-guard.sh → ai_platform` is wrong (it serves the project design chain, not `.claude` platform assets). **Option C REFUTED (2026-07-30, `docs/implementation/Artifact_Ownership_Decision_Paper.md`)** — the registry's own admission rule (*"an asset that cannot answer the five questions shall not be registered — and shall not exist"*) excludes project-side artifacts: they execute at no AI runtime moment and have no AIP-principle/PD/ADR-AIP-01 lineage. **Selected target: Option D — coverage declared by each `developer_guide/<area>/00_index.md`, the hook derives routing** (documentation-routing metadata belongs to the documentation model, not the platform runtime registry). Awaiting DA ratification. Formerly recorded as — the existing `.claude/platform/registry.yaml` (already `path → component → trace`, and already registers this very hook as **AST-006**) becomes the single ownership source; the hook becomes a reader. Fallback Option B if scope is withheld. **DEPENDENCY (flagged, not assumed): extending the registry's scope from `.claude` assets to project-side assets is a PLATFORM-GOVERNANCE ruling, not an engineering choice.** **Architectural invariant behind any option: *ownership is declared exactly once, and all engineering tooling derives its routing from that declaration*** (the ubiquitous-language principle applied to ownership metadata). Classification holds — ordinary engineering debt for the hook repair — but no longer purely mechanical. Phase-1 ownership map is this item's input. Does NOT block WP-3. |
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

## Parked / future planning (relocated from CONTEXT.md — F-2 hygiene commission [ES-004.3 validation finding, distinct from the closed Infection F-2 above], 2026-07-30)

- 38D-02 Capability Relationships — opens after the ARB digests 38D-01 (governance archive: `.claude/memory/`).
- Docs candidates: Domain Model Catalogue · root `CLAUDE.md` tech-table refresh.

---

## NEXT ACTION (exactly one)

```text
Phase:         EPIC-002 Strategic Discovery (EPIC-001 formally closed 2026-07-11)
Action:        Phase 1 — PROBLEM-SPACE LITERATURE REVIEW (researcher mode; charter
               ../EPIC-002_Problem_Statement.md; assumption-evidence table; FACT/
               INTERPRETATION/RECOMMENDATION/OPEN-QUESTION labels; falsify, not elaborate)
Constraints:   no code · no aggregates · no APIs · no IDD · STOP at session end
Then:          Phase 2 Strategic DDD -> STOP -> ARB review (IDD authorization decision)
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
