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
- **⏸ Step 2 (Adjudication) — HALTED for ARB ruling (inconsistency surfaced).** Implementation-first evidence: the frozen versioning convention (`Event_Registry.md` + ADR-T5 + Blueprint §12) is **`schema_version` inside the payload (absent=1), hydrator supports vCurrent+vPrevious; a new event NAME is only for BREAKING changes**. `contestedOutcome` is **additive/convertible-from-old** → it is **`DeterminationIssued` schema_version 2** (nullable/absent-tolerant field; hydrator dispatches v1+v2), **NOT a new `DeterminationIssued v2` class**. This contradicts ADR-PL-01's "v2 (new version)" wording. **Recommended:** clarify ADR-PL-01 to "schema_version 2, additive, same event" and implement accordingly (Adjudication local VOs `ContestedOutcomeRef` etc.; `Determination` carries it; `DeterminationIssued`+hydrator+`OutboxEventAdapter` gain the additive field at schema_version 2; mapper/migration if persisted). **Awaiting ARB ruling before code.**
- Step 3: register v2 hydrators (Event Registry, vCurrent+vPrevious). Step 4: **PB-004 RED (C1 Domain)**.
Messaging unaffected (consume-only); anonymity preserved. Traceable to ADR-UL-01/ADR-PL-01. No new architecture unless implementation reveals a genuine inconsistency.

## Architecture debt (tracked)
- **AD-M1 — RESOLVED (2026-07-07) · category: Architecture Fitness Evolution** (no domain change — only *where* the fitness guard lives). Anonymity guard relocated to the constitutional suite (`GreenfieldCoreArchitectureTest::test_at_q7_001_no_voter_vote_linkage` now scans the Shared messaging surface incl. Outbox); property #11 removed from the Inbox test. RED→GREEN proven; Architecture suite 142✔/1 skip; greenfield PHPStan clean. First fitness change validating PGP-03.
- **AD-M2** — decide Outbox formal Application port / hexagonal symmetry (ADR-MP-05/Q5) — future ADR, only under business pressure.
- **G-1** (trivial, pending) — release numbering for the governance addition ("AKB 1.2" collides with roadmap §4); tag neutralized; recommend a non-colliding tag.

## Parallel tracks (not active this session)
- **AI Engineering Platform — Baseline v1.0, Reference Implementation Pending (ADR-AIP-01 SIGNED 2026-07-08 incl. Addendum):** architecture FROZEN as baseline; rulings R-1..R-22 in `Phase-02.5-Certification-Plan.md` §6 (append-only). Corpus: `docs/architecture/proposals/ai-platform/` (6 artifacts; 02.6 freeze conditional on **OI-1** terminology review — exemplary ambiguity to resolve: "Review") + `docs/adr/ADR-AIP-01-*.md`. **Phases ended → Iterations. Track next action: OI-1 → then PLATFORM CONSTRUCTION Iteration 1 = minimal platform capable of supporting PB-004 end-to-end; incremental commits each usable/green/testable (root+registry → rules → knowledge → hooks → commands → agents), slice→review→merge, NEVER one session (R-21). Then Platform Qualification via PB-004.** No new architecture docs — amendments only (AIP-13). Session log: `.claude/sessions/2026-07-08.md`.
- Governance: 38D-02 Capability Relationships (after ARB digests 38D-01)
- Docs candidates: Domain Model Catalogue · root CLAUDE.md tech-table refresh

## Automation (hooks, .claude/settings.json — see .claude/scripts/README.md)
- SessionStart auto-injects MEMORY.md + CONTEXT.md + the active plan THIS FILE declares (first `.claude/plans/*.md` path above) + today's log. Stop prints a sync report when state is stale. PostToolUse(Write|Edit) records modified files to `.claude/runtime/YYYY-MM-DD-files.log` + `-state.json` (gitignored) and refreshes plan `Last Updated:` headers.
