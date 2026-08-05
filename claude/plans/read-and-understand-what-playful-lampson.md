# PB-003-C1 Execution Plan — v2 (architect's 10 governance refinements incorporated)

## Context

Pre-flight caught 61 uncommitted changes on `election-audit` (all from this program's sessions; tree was clean at conversation start). Architect approved the plan direction (9.5/10) with mandatory governance refinements: baseline freeze before any PB-003 work, code/docs commit separation, full commit traceability, RED/GREEN evidence not claims, rollback paths, explicit class ownership, scoped percentages, an implementation baseline register, and stop-after-C1 cadence with reviews at ticket boundaries.

## Part A — BASELINE FREEZE (gate — no PB-003 work until ALL ✔)

Four baseline commits on `election-audit`, each message carrying ticket refs + Co-Authored-By trailer:

1. `AD-001..AD-005: repair architecture guards; fix admin approval enum bug` — tests/Architecture (Phase_C25, Vocabulary, ElectionLifecycle), tests/Feature/Middleware, controllers vocabulary + AdminElectionController + VoteEligibility, Committee VOs, ElectionActions.ts, Architecture_Debt_Backlog.md
2. `PB-001 PB-002: Event Registry + Relay Registry — implements PB-001, PB-002 / Blueprint §6, §7-F2 / ADR-T3, ADR-T5 / Matrix: Event Registry, Relay Registry / Contexts: Shared Infra, Adjudication, Membership` — registry classes, processor refactor, hydrators, providers, all outbox/registry tests incl. EventRegistryCompletenessTest, Event_Registry.md
3. `docs: Push B Blueprint v1.0 (frozen) + program management layer + C4 model + governance rounds` — docs/implementation/** (Blueprint, Decision Log, Process v1.0, backlog/, dashboards, IDD, renamed docs), docs/architecture/c4/**, design Rounds 38C-13A..38D-01, external-validation, audit_system renames
4. `chore(.claude): repo-first project OS (memory, context, sessions, plans, hooks)` — .claude/**, .gitignore, claude/plans/*

**Gate verification (all must pass before Part B):**
```text
BASELINE FREEZE
✔ previous work committed (4 commits)
✔ git status --short EMPTY
✔ architecture documents frozen (Blueprint v1.0, Process v1.0 — already frozen)
✔ backlog updated (already current)
✔ baseline recorded (Part A.5)
```
5. Create `docs/implementation/IMPLEMENTATION_BASELINE.md` — the official implementation history register. Seed with: baseline entry (date, 4 commit hashes, gates green: Arch 133/133, greenfield PHPStan PASS) + retroactive Verified lines for PB-001 and PB-002 (date, gates, commit hash from #2). Rule going forward: ONE line appended per ticket reaching Verified. Commit as 5th baseline commit: `docs: implementation baseline register`.

## Part B — Governance updates to tracker (before implementation)

Edit `docs/implementation/backlog/PB-003_PROGRESS.md`:
- Commit IDs `PB-003-C1..C6` + `PB-003-DOC` (docs-only commits), Estimate/Actual per commit
- Per-commit DoD (RED evidence ☑ GREEN evidence ☑ PHPStan ☑ purity ☑) — bookkeeping/docs are per-commit EDITS but committed separately in PB-003-DOC
- Review checkpoints: Architecture Review ✔ (IDD) · Implementation Review □ (at ticket boundary) · Merge Review □
- Executable process state: `Current: C1 step RED · Allowed next: GREEN · Forbidden: jumping to C2/architecture tests`
- Rollback column: every commit lists `git revert <hash> → prior tests green`
- **Percentage scoping rule** stated: all % in this file = TICKET level (PB-003 x/18 WBS); epic % lives in EPIC-001 file; program % in IMPLEMENTATION_PROGRESS.md — never unscoped
- IDD clarification note: registry test belongs to C4 (registry = Infrastructure), C1 = port classes only

## Part C — PB-003-C1 (code only · Estimate 1h)

1. `git checkout -b feature/pb003` (only after Baseline Freeze gate ✔)
2. **RED with EVIDENCE**: write `tests/Unit/Contexts/Shared/Inbox/InboxMessageTest.php` + `InboxClassificationTest.php`; run; capture actual output (test count, failure count, failure reason "class not found") — recorded in session report, not just claimed
3. **GREEN with EVIDENCE**: implement 6 pure-PHP classes in `app/Contexts/Shared/Application/Inbox/` — `InboxMessage` (final readonly) · `InboxHandler` · `InboxOutcome` · `CausalPreconditionMissing` · `IdempotentReplay` · `PermanentInboxFailure`. Every class docblock declares: **Owner** (Shared Application) · **Layer** (Application port — NOT Infrastructure, NOT Domain) · **Responsibility** (one line) · **Traceability** (Blueprint §6/§8 · ADR-T4 · Matrix: Inbox). Capture green output.
4. **Gates with EVIDENCE**: `phpstan-greenfield.neon` clean + ad-hoc max on new classes; purity `grep -r Illuminate app/Contexts/Shared/Application/Inbox/` = empty; Architecture suite regression run.
5. **Commit** (code + its tests only):
   ```
   PB-003-C1: inbox port package (message, handler, outcome, classification markers)

   Implements: PB-003 (Inbox / Deduplication)
   Blueprint: §6, §8 · ADR: ADR-T4 · Matrix: Inbox · Context: Shared Application
   Rollback: git revert <this hash> → prior suite green (port has no consumers yet)
   ```
6. **PB-003-DOC commit**: tracker WBS ticks (1.1–1.5, 5.1-unit) + derived ticket-% + process-state → "next C2"; DEVELOPMENT_LOG entry; session log; EPIC board Last-updated. Message: `PB-003-DOC: progress/session records after C1`.
7. **STOP.** C1 done ≠ PB-003 Verified. Next session: C2. Architecture/Implementation Review happens at the PB-003 TICKET boundary (after C6 + gates), not per commit.

## Session Summary (mandatory final output — facts only)

```
Ticket / Commit / Classes added / Tests added (RED evidence / GREEN evidence)
Gates: PHPStan / Purity / Architecture — with actual results
Progress: PB-003 ticket-level x/18 (scoped) · Rollback: hashes
Remaining: C2..C6 + DOC · Next: PB-003-C2
```

## NOT in this plan
No C2+ · no push · no Verified claim for PB-003 · no new architecture.

## Verification
Baseline gate transcript · RED output → GREEN output shown verbatim · gate outputs shown · `git log --oneline -7` shows baseline series + C1 + DOC.
