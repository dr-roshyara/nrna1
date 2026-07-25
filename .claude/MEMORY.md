# Project Memory (stable, long-term)

> Repo is the single source of truth. This file holds durable facts only — no tasks, no daily progress (those live in CONTEXT.md / sessions/).

## Authoritative documents (read before working)
- **Implementation process (FROZEN):** `docs/implementation/Implementation_Process_v1.0.md` — 15-step workflow, 14-box DoD, lifecycle≠progress, derived-% rule. No ticket skips it.
- **Architecture (FROZEN):** `docs/implementation/PushB_Architecture_Blueprint.md` v1.0 (changes only via v1.1) · ADR-T log (`docs/adr/ADR-T-LOG-Tactical-Implementation.md`) · `docs/implementation/Canonical_Event_Catalog_v1.0.md` · Round 50 docs (`docs/architecture/design/Round50-*`).
- **Work management:** `docs/implementation/backlog/BACKLOG.md` (program) → `EPIC-001_Greenfield_Core.md` (tickets) → `PB-xxx_PROGRESS.md` (WBS). One-pager: `docs/implementation/PROGRAM_STATUS.md`.
- **C4 views:** `docs/architecture/c4/` — documentation only, frozen artifacts win on conflict.
- **Governance track & historical memory:** archived in-repo at `.claude/memory/` (index: `INDEX.md`; full program history: `ddd_program_state.md`). Key outcome: 38C-15 ruling = Option B Functional Independence + safeguards S-1..S-5; OQ-38A05-02 PROTECTED (routes to CIC).

## Constraints that bite (learned, verified)
- **Engine is sovereign:** `Election.state` column is a compatibility cache; always assert/derive via `ElectionLifecycleEngine` / `currentState()`.
- **One transaction = one aggregate root + its outbox row(s)** (ADR-T1). The correction loop is 5 causally-linked transactions — never a saga (ADR-T8).
- **Cross-context identity crosses as strings only**; each context reconstructs its LOCAL VO (ADR-T16). Contestation's `DeterminationId` ≠ Adjudication's.
- **Anonymity (CI-5/Q7/VO-1)** is always-invariant: no payload/log/dead-letter row may allow voter↔vote linkage. `votes` has no user_id by design.
- **Governance vocabulary:** never hardcode denial strings like 'not eligible' in controllers (Phase_C25 guard); use resolver denial_reason or approved wording.
- **PHPStan gate:** `phpstan-greenfield.neon` (Contestation+Adjudication, max) is the official gate; run it, not ad-hoc paths.
- **Test DB:** PostgreSQL (`phpunit.xml`); RefreshDatabase only — never migrate:fresh against dev DB. (Root `CLAUDE.md` tech table refreshed 2026-07-11 — Laravel 11/PHP 8.2/PostgreSQL/Sanctum.)
- **Windows dev quirks:** shell-driven test helpers broke on `C:\` paths before (grep `:` split) — prefer pure-PHP file scanning in architecture tests.
- **Hydrator versioning rule:** vCurrent + vPrevious ONLY (`docs/implementation/Event_Registry.md`).
- **EPIC-002 strategic baseline is BINDING (ARB Resolution, 2026-07-25):** the accepted EPIC-002 artifacts (Canonical Context Map — 5 ratified BCs; Relationship Pattern Selection with adversarial review) are the authoritative strategic baseline for Tactical DDD. Changes route ONLY through the recorded falsifiability conditions / CB-1/CB-4 reversal condition / COL-3a revision trigger / new domain evidence — **never implicitly during implementation** (strategic-erosion caution recorded in `EPIC-002_Relationship_Pattern_Selection.md` §ARB RESOLUTION). Tactical entry items: Adjudication-name reconciliation (vs. implemented `app/Contexts/Adjudication`) + hybrid per-stream integrity assignments.

## AI/PublicDigit Engineering Platform (durable — Baseline v1.0, 2026-07-08)
- **Authority:** `engineering/architecture/adr/ADR-AIP-01` (Baseline + Construction Addendum) + `ADR-AIP-02` (AIP-14 Product Primacy) · corpus `engineering/architecture/baseline/Phase-0*.md` (FROZEN) · rulings register (Living, append-only): `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` (R-30..; R-1..R-29 historical in sealed Phase-02.5 §6).
- **Namespace (EM-001, 2026-07-10):** `docs/`+`architecture/` = Product · `engineering/` = Engineering Platform (entry: `engineering/README.md`; audit: `engineering/MIGRATION_REPORT.md`) · `.claude/` = runtime mount point (never moves). Create folders only when the first artifact arrives — no speculative namespaces.
- **Registry-first workflow is BINDING:** every `.claude` artifact: register in `.claude/platform/registry.yaml` (CMP/AST ids, five-question trace: capability→context→principle→decision→ADR) → review → implement → verify. Guide: `developer_guide/ai_platform/01_registry_first_workflow.md`.
- **Verification Engine = measuring instrument (R-26):** run → capture → PASS/FAIL → stop; no interpretation inside the instrument.
- **GOVERNANCE FREEZE (R-27):** no new platform principles/architecture docs until the PB-004 usage retrospective; amendments only (AIP-13); platform-only work needs ARB approval (AIP-14); retrospective includes a deletion goal.
- **STRUCTURAL FREEZE (R-37, 2026-07-10) — Engineering Platform Baseline v1.0 is frozen** (baselines freeze; architectures evolve — never say "the architecture is frozen"). Until C3 + PB-004 + retrospective: `engineering/` permits only bugfix/broken-link/typo; burden of proof reversed — *every architectural idea is guilty until proven necessary by implementation evidence*; expansion proposals without demonstrated insufficiency are rejected by default.
- Platform serves PublicDigit delivery — the standing question: *smallest platform change that unlocks the next PublicDigit capability?*
- **AIP observation stop (ARB, 2026-07-10):** unless implementation exposes a genuine deficiency in the Engineering Platform, do NOT add further AIP observations — record only implementation evidence required to complete the current ticket. Everything else waits for the retrospective.
- **Implementation-first default (ARB, 2026-07-10):** the architecture and engineering platform have demonstrated stability — every new ticket is an IMPLEMENTATION ticket by default, not an architecture ticket. The question is *"can I implement this capability within the approved architecture?"* — only a NO (with implementation evidence of insufficiency) opens an ADR/ARB discussion.

## User preferences (recurring)
- Chief-architect review loop: propose → review → apply refinements → freeze. Never start coding before the design artifact (IDD) is approved.
- TDD is mandatory and literal: RED confirmed before GREEN, every class.
- One micro-slice per session; stop at the ticket boundary; single NEXT ACTION always defined.
- **Developer guide per step is Definition of Done** (do NOT wait to be asked): one file per step under `developer_guide/<area>/` (+ `00_index.md`), snippets grounded in committed code, ending with a Traceability line. Standing rule in `.claude/CLAUDE.md`; reminder hook `.claude/scripts/dev-guide-reminder.sh`.
- **Development discipline is fixed: Business → DDD → Architecture → Tests → Implementation.** Never jump to a test/class before the DDD model + Architecture Decision that justifies it. Discovered gap: `Finding → Architecture Decision → RED → GREEN → Certification`. Architecture produces tickets; tickets never accrete into architecture (model the platform capability first). Standing rule in `.claude/CLAUDE.md`; tripwire hook `.claude/scripts/discipline-gate-reminder.sh`.
- **Engineering Standards are the constitutional truth — MEMORY holds hints only (consolidation 2026-07-11, PROPOSED):** canonical rule homes = `engineering/governance/STANDARDS_INDEX.md` → ES-001..ES-006. Quick hints (text lives in the ES docs, NOT here):
  · Rule parsimony + documents-record-governance → **ES-001**
  · Implementation-first default + observation stop (EEP/EP/ER registered) → **ES-002**
  · Qualification lifecycle (never fix in-run; PASS-AFTER-CORRECTION; F-/CR-/OQ- series) + score-persistence stop + measurement conventions → **ES-003**
  · Retrospectives-recommend + record conventions (append-only logs; ids not filenames; one responsibility per record type) → **ES-004**
  · Three-concern separation + folder rule + placement litmus → **ES-005**
  · Promotion ladder + research freeze (no Level 5) + harvest discipline + harvest question ("did this work REVEAL reusable engineering knowledge?" — No is the healthy default) → **ES-006 Engineering Knowledge Governance**
  · **Plans are TWO concepts (ADOPTED 2026-07-11):** Work Plan (Runtime, ephemeral, `.claude/plans/` = plansDirectory) vs Engineering Plan (governed EP-01 deliverable, `docs/plans/`, `YYYYMMDD-HHMM-<what>-plan.md`) — paper: `docs/implementation/Plan_Concept_Decision_Paper.md`; rulings register now R-30..R-38
- **EP-01-Light gate (hint):** below IDD weight, present the form and WAIT; record-only exempt; every implementation ends with the EP-02 report. Rule text: `Implementation_Process_v1.1_Draft.md` (registered in ES-002).
- Pending ARB (knowledge track): assembly-report review · EKP disposition · pilot authorization.
- Commits reference ticket IDs (`PB-003: ...`). Git identity: `=Dr. Nab Raj Roshyara`.
- Descriptive filenames always; renaming "Untitled" docs is expected housekeeping.
