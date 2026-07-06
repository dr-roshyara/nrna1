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
- **Test DB:** PostgreSQL (`phpunit.xml`); RefreshDatabase only — never migrate:fresh against dev DB. NOTE: root `CLAUDE.md` tech table is stale (says Laravel 9/MySQL; actual Laravel 11/PostgreSQL).
- **Windows dev quirks:** shell-driven test helpers broke on `C:\` paths before (grep `:` split) — prefer pure-PHP file scanning in architecture tests.
- **Hydrator versioning rule:** vCurrent + vPrevious ONLY (`docs/implementation/Event_Registry.md`).

## User preferences (recurring)
- Chief-architect review loop: propose → review → apply refinements → freeze. Never start coding before the design artifact (IDD) is approved.
- TDD is mandatory and literal: RED confirmed before GREEN, every class.
- One micro-slice per session; stop at the ticket boundary; single NEXT ACTION always defined.
- Commits reference ticket IDs (`PB-003: ...`). Git identity: `=Dr. Nab Raj Roshyara`.
- Descriptive filenames always; renaming "Untitled" docs is expected housekeeping.
