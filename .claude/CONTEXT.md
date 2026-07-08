# Current Working State

**Updated:** 2026-07-07

## Active Work (structured — hooks parse these `Key:` lines; keep the format)
Ticket: PB-003
Plan: .claude/plans/PB-003-inbox.md
Branch: feature/pb003 (ACTIVE — baseline-release-1.1 tagged behind it)
Milestone: M1 — Messaging Infrastructure (49%, derived 17/35 WBS)
Priority: High

- **Detail:** PB-003 Inbox / Deduplication · Lifecycle: **VERIFIED / CERTIFIED (ARR, 2026-07-07)** · 18/18 WBS · ARR: `docs/implementation/PB-003_Architecture_Readiness_Report.md` · matrix: `docs/implementation/Messaging_Architecture_Verification.md`. **Next ticket: PB-004 (needs IDD).**

## What remains (EPIC-001)
PB-003 (Inbox) → **Platform Capability Certification (D-11 gate)** → PB-004 (Election Reaction, needs IDD) → PB-005 (Contestation Reaction, needs IDD) → PB-006 (IT-1..8) → PB-007 (Merge Gate incl. F-1 Deptrac, F-2 Infection)

## Blockers
None. PB-003 is Verified/Certified; PB-004 is unblocked (needs its IDD first).

## Next action (exactly one)
**Implementation (TDD) — prerequisite chain for PB-004. Step 1 ✔ DONE.**
- **✔ Step 1 (Contestation):** `TargetRef:string` → `ContestedOutcomeRef` VO (+ `ElectionId`/`TargetType`/`TargetId`); `Challenge::raise` + `ChallengeRaised` carry it (`ChallengeRaised` evolved in place — internal domain event, not published language; ADR-PL-01). RED→GREEN; ChallengeTest 12/12; Architecture 154✔/1 skip; greenfield PHPStan clean. ARB refinements folded (internal-event rationale; VO holds no transport — `fromParts` removed).
- **✔ Step 2 (Adjudication) — DONE (ARB Option A).** `DeterminationIssued` **payload schema version 2** (same event, additive optional `contestedOutcome`; NOT a new class). Adjudication LOCAL VOs (ADR-T16); `Determination` carries + emits the ref; hydrator dispatches vCurrent(2)+vPrevious(1); `OutboxEventAdapter` stamps schema_version 2; mapper/model + additive migration persist it (nullable). **TDD RED-first (corrected after ARB caught a lapse):** wrote failing behavior tests, confirmed RED with impl stashed, then GREEN. Adjudication 45✔; Architecture+Contestation+Adjudication 199✔/1 skip (2 risky pre-existing); greenfield PHPStan clean. Dev guide: `developer_guide/adjudication/`. Committed `f18d7ae37`.
- **✔ Step 3 (PB-004) — RED REFINED (ARB round 2); AWAITING REVIEW before GREEN.** Failing tests only (11 failed; no production, no Election context). `tests/Unit/Contexts/Election/`:
  - `ElectionCorrectionPolicyTest` (renamed from CorrectionTypeDecision — business policy): Dismissed→none (D-02); Upheld→ContainedOnly + `isForwardOnly()` (ADR-T8).
  - `ElectionTest` (aggregate): `applyDetermination()` (aggregate DECIDES; handler reacts); event emitted; Dismissed→no event; **forward-only invariant** (isForwardOnly + no reverse/rescind method); aggregate idempotency; **event boundary** (no `ChallengeId`/`challengeRef`) + anonymity.
  - `DeterminationIssuedReactionHandlerTest` (ARB round-3 rulings): consumes schema v2 + reconstructs local ElectionId (ADR-T16); **schema v1 → `DeterminationLacksElectionScope`** (business incompatibility, NOT generic PermanentInboxFailure — historically valid, insufficient); **unknown election → `CannotApplyDeterminationToUnknownElection`, NOT derived** (Election reacts, never provisions); **cross-org → never applied** (org-scoped resolution at the **repository boundary**; domain stays tenant-free per ADR-T16/Adjudication precedent — **org-placement flagged for ARB confirmation**).
  - Behavior-first (ER-07). RED 12 failed (context absent).
- **→ STOP. Do NOT write GREEN/production** until ARB reviews the refined RED (esp. confirm the two flagged decisions: v1→PermanentInboxFailure; unknown-election→derive). Then GREEN = `app/Contexts/Election/{Domain,Application}` minimal slice. Messaging unaffected (consume-only, ARR PASS).

**PROCESS NOTE (this turn):** TDD-first was breached on step 2's first attempt (production written before the failing test). Corrected via stash→RED→pop→GREEN. Lesson reinforced: RED **before** code, every step.

## Architecture debt (tracked)
- **AD-M1 — RESOLVED (2026-07-07) · category: Architecture Fitness Evolution** (no domain change — only *where* the fitness guard lives). Anonymity guard relocated to the constitutional suite (`GreenfieldCoreArchitectureTest::test_at_q7_001_no_voter_vote_linkage` now scans the Shared messaging surface incl. Outbox); property #11 removed from the Inbox test. RED→GREEN proven; Architecture suite 142✔/1 skip; greenfield PHPStan clean. First fitness change validating PGP-03.
- **AD-M2** — decide Outbox formal Application port / hexagonal symmetry (ADR-MP-05/Q5) — future ADR, only under business pressure.
- **G-1** (trivial, pending) — release numbering for the governance addition ("AKB 1.2" collides with roadmap §4); tag neutralized; recommend a non-colliding tag.

## Parallel tracks (not active this session)
- **AI Engineering Platform — Baseline v1.0, Reference Implementation Pending (ADR-AIP-01/02 signed; rulings R-1..R-27 in `Phase-02.5-Certification-Plan.md` §6):** Construction Iteration 1 under way — **C1 DONE+ACCEPTED** (`.claude/platform/registry.yaml`: registry-first workflow BINDING, CMP/AST ids, VERIFY-based adoption; dev guide `developer_guide/ai_platform/01_registry_first_workflow.md`). **GOVERNANCE FREEZE in force (R-27):** no new platform principles until the PB-004 retrospective. **Track next action: EXECUTE slice C3 (plan APPROVED AS WRITTEN by ARB 2026-07-08 — do not modify; instruction verbatim in the plan). C3 = the first operational qualification: fresh session boots via CONTEXT injection, follows EP-01, implements ONLY AST-010 `run-gates.sh` (measuring instrument: greenfield PHPStan → capture → PASS/FAIL, stop-on-fail, then Architecture suite; NO interpretation), verifies + falsifiability, EP-02 Completion Review, STOP. No other capabilities/refactorings/governance. Rulings now live in `docs/adr/ADR-AIP-LOG-Platform-Rulings.md` (R-30..R-34; proposals sealed). Then PB-004 (Operational Readiness v1.0, R-33) → retrospective → C2.** After retrospective: Continuous Evolution (feature → observation → one amendment if justified). OI-1 (02.6 terminology review) still open. Plan: `.claude/plans/AIP-iteration-1-construction.md` · session log: `.claude/sessions/2026-07-08.md`.
- Governance: 38D-02 Capability Relationships (after ARB digests 38D-01)
- Docs candidates: Domain Model Catalogue · root CLAUDE.md tech-table refresh

## Automation (hooks, .claude/settings.json — see .claude/scripts/README.md)
- SessionStart auto-injects MEMORY.md + CONTEXT.md + the active plan THIS FILE declares (first `.claude/plans/*.md` path above) + today's log. Stop prints a sync report when state is stale. PostToolUse(Write|Edit) records modified files to `.claude/runtime/YYYY-MM-DD-files.log` + `-state.json` (gitignored) and refreshes plan `Last Updated:` headers.
