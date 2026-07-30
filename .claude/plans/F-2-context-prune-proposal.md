# F-2 Repository Hygiene Commission — CONTEXT.md Prune Proposal

**Created:** 2026-07-30 · **Status:** PROPOSED — awaiting PA review; **CONTEXT.md has NOT been edited**
**Commission:** PA instruction 2026-07-30 (resolve F-2; ES-004.3 FROZEN — this is hygiene, not governance)
**Mission:** CONTEXT.md answers exactly one question — *"if someone starts work now, what do they need to know today?"*

---

## 1. Classification table (current file: 161 lines)

| # | Section (lines) | Classification | Remove? | Destination (VERIFIED to exist) |
|---|---|---|---|---|
| 1 | Header (1–3) | Runtime | Keep (refresh stale `Updated: 2026-07-25`) | — |
| 2 | EPIC-002/003/004 chain (5–33) | Historical — all closed; the 29-bullet tactical-chain narrative | **REMOVE** | MEMORY durable baselines (all 4 key outcomes verified present: strategic-baseline-binding · four policies · frozen chain · APM/Q-2/roadmap/WP-1) · session logs 2026-07-25/26 · the `EPIC-004*` documents themselves (each bullet cites its own artifact) |
| 3 | Active Work `Key:` block (36–43) | Runtime | Keep — refresh values only: stale `Milestone: M1 — Messaging Infrastructure (49%)` → EPIC-004 roadmap position; trim the Detail line's historical tail | — |
| 4 | "What remains (EPIC-001)" (45–46) | Historical AND false (EPIC-001 closed 2026-07-11) | **REMOVE** | `BACKLOG.md` + EPIC-001 boards (synced `e51178f73`) · `EPIC-001_Retrospective.md` ✔ |
| 5 | Blockers (48–49) | Runtime, stale text (PB-003/PB-004 era) | Keep — rewrite to current ("None") | — |
| 6 | "Next action" + PB-004→PB-007 chain (51–110) | Historical — 58 lines of completed, ARB-closed work | **REMOVE** | Session logs 2026-07-08..07-12 ✔ · `PB-004_Retrospective.md` ✔ · PB-005/006/007 IDD + discovery docs ✔ · `Architecture_Handover_Release_2.0.md` ✔ · `EPIC-001_Retrospective.md` ✔ — **exception: lines 53–54 + 62 (dated 2026-07-07) → Risk R-1, preserved verbatim in §5** |
| 7 | `<!--superseded-->` / `<!--old-->` + PROCESS NOTE (112–118) | Historical | **REMOVE** | Superseded blocks: restated by the later bullets they yielded to; PROCESS NOTE (TDD breach, 07-07) → **R-1, preserved verbatim in §5**; the rule itself is in MEMORY ("TDD is mandatory and literal") ✔ |
| 8 | Architecture debt (120–123) | Split | AD-M1 (RESOLVED 07-07): **REMOVE → R-1 §5** · **AD-M2 + G-1: KEEP (open debt = Runtime)** | — |
| 9 | Eng Platform reconstruction + Phase A, 2026-07-27 (125–139) | Runtime — current track state, pending DA decisions | **Keep unchanged** | — |
| 10 | PKS fourth track (141–144) | Runtime — current, gated | **Keep unchanged** | — |
| 11 | Eng Platform consolidation 2026-07-11/12 (146–152) | Historical — block 9 declares itself "supersedes-in-currency" over it; its still-current residue (ratification pending · C3 queue) is already restated in block 9 | **REMOVE** | Session logs 07-11/07-12 ✔ · `STANDARDS_INDEX.md` · rulings register · three-track program board |
| 12 | Parallel tracks (154–157) | Mixed — the 30-line AIP/C3 paragraph is mostly historical narrative around one live pointer | **COMPRESS to 3 lines** | Verbatim C3 instruction lives in `.claude/plans/AIP-iteration-1-construction.md` (verified, 17 refs) ✔ · standing no-expansion instruction in MEMORY (R-37 block) ✔ · R-36 history in log 07-09 · 38D-02 + docs-candidates lines kept as-is |
| 13 | Automation (159–161) | Runtime/Reference pointer | **Keep unchanged** | — |

## 2. Estimated reduction

**161 lines → ~66 lines (≈59% fewer lines; ≈75–80% less content by volume — the removed bullets are the longest).** Injection cost per session start drops proportionally (~28k → ~8k tokens).

## 3. Proposed Runtime CONTEXT.md (full replacement text)

```markdown
# Current Working State

**Updated:** 2026-07-30

## Program position (one paragraph, pointers only)
Strategic + tactical baselines FROZEN and implementation is EXECUTING: EPIC-001..004 formally
closed; Determination tactical chain, APM architecture (ADR-T21/22/23), Q-2 gate, and the
8-WP implementation roadmap are the governing baseline — durable outcomes + constraints live
in `.claude/MEMORY.md`; history lives in `.claude/sessions/` + the EPIC/PB documents.

## Active Work (structured — hooks parse these `Key:` lines; keep the format)
Ticket: WP-2 (EPIC-004 APM core) — AUTHORIZED, opens in a fresh session with its own work plan; WP-1 ACCEPTED+CLOSED (ARB 2026-07-27)
Plan: .claude/plans/WP-1-evidenceset-v3.md
Branch: feature/pb003 (ACTIVE — baseline-release-1.1 tagged behind it)
Milestone: EPIC-004 Architecture-to-Implementation roadmap — WP-1 ✔ accepted · WP-2 authorized (1/8 WPs closed)
Priority: High

- **Detail:** WP-2 keystones (roadmap §WP-1..8): exactly-once conclusion under concurrent
  redelivery · no admission after conclusion · unique-active-per-challenge under race.
  Execution contract pattern: `.claude/plans/WP-1-evidenceset-v3.md` (Auto mode · RED first ·
  STOP conditions). The WP-2 session creates its own plan and re-points the `Plan:` line.
- **Engineering platform:** operational for the identified defects (EG-001..003 ✔); EG-002b
  (ARB yes/no) · EG-004 (ERE+recalibration, coupled) · EG-005 (qualification, terminal) tracked
  in `docs/plans/20260726-2056-engineering-platform-repair-plan.md` — independent, non-blocking.

## Next action (exactly one)
**WP-2, fresh session, RED first** (per the recorded WP-1-pattern contract; slice acceptance
before anything else opens).

## Blockers
None.

## Open items (current only)
- **AD-M2** — Outbox formal Application port / hexagonal symmetry (ADR-MP-05/Q5) — future ADR, only under business pressure.
- **G-1** (trivial) — non-colliding release tag for the governance addition.
- **Jurisdiction semantics** — flagged EPIC-004F, non-blocking.
- **Parked candidates** (evidence-before-promotion, revisit ~WP-3/WP-4): slice ledger · Implementation Execution Contract extraction.
- **O-2 watch item** (ES-004.3 validation): composite artifacts — reopen on a second occurrence.

## Engineering Platform — reconstruction cycle + Phase A knowledge-architecture governance (2026-07-27)
[BLOCK 9 KEPT VERBATIM — lines 125–139 of the current file, unchanged]

## PKS — Product Knowledge System Strategic Discovery (fourth track, 2026-07-27; Phase 1 COMPLETE, gated)
[BLOCK 10 KEPT VERBATIM — lines 141–144, unchanged]

## Parallel tracks (not active this session)
- **AI Engineering Platform:** track next action = fresh session runs **C3 + OQ-ENG-003**
  (C3 plan APPROVED AS WRITTEN — verbatim instruction in `.claude/plans/AIP-iteration-1-construction.md`;
  protocol `engineering/verification/qualification/OQ-ENG-003-...md`); then OQ-ENG-004 (fresh
  session) → A5 Transition Plan → platform freeze. Standing constraints (R-37 freeze · no-expansion
  instruction · rulings register) — see MEMORY.
- Governance: 38D-02 Capability Relationships (after ARB digests 38D-01)
- Docs candidates: Domain Model Catalogue · root CLAUDE.md tech-table refresh

## Automation (hooks, .claude/settings.json — see .claude/scripts/README.md)
[BLOCK 13 KEPT VERBATIM — lines 159–161, unchanged]
```

## 4. Risk assessment

| ID | Risk | Disposition |
|---|---|---|
| **R-1** | **The 2026-07-07 session log does not exist** (sequence jumps 07-06 → 07-08). Three items dated 07-07 have no session-log home: AD-M1 resolution · the TDD-breach PROCESS NOTE · PB-004 Steps 1–2 narratives (CONTEXT lines 53–54, 62-partial, 118, 120–121) | Preserved **verbatim in §5 of this proposal** (a Historical artifact once committed). Never fabricate a backdated log (ES-004.3 Historical Integrity). The durable *rules* they produced are already in MEMORY (TDD literal; ER-08) and the code/tests (AT-Q7 guard). |
| R-2 | Hooks parse the `Key:` lines and scan CONTEXT | Format preserved exactly (all 5 keys, same order); only values refreshed. Post-prune sanity: run one SessionStart to confirm injection + Stop-hook sync report behave. |
| R-3 | `Plan:` still points at the closed WP-1 plan | Intentional transition state — the WP-2 session re-points it when its plan exists (recorded in the Detail line). |
| R-4 | Parallel tracks (KnowledgeOS/PKS) actively edit CONTEXT from other sessions | Blocks 9/10 kept byte-identical to eliminate merge risk; execute the prune promptly after approval; re-read the file immediately before editing. |
| R-5 | `Milestone:` value changes meaning (M1 49% → roadmap position) | The old value was stale-false (M1 completed 2026-07-07). New value derived from the roadmap (1/8 WPs). Flagged since hooks may display it. |

## 5. R-1 verbatim preservation appendix (content whose only home was CONTEXT.md)

> - **AD-M1 — RESOLVED (2026-07-07) · category: Architecture Fitness Evolution** (no domain change — only *where* the fitness guard lives). Anonymity guard relocated to the constitutional suite (`GreenfieldCoreArchitectureTest::test_at_q7_001_no_voter_vote_linkage` now scans the Shared messaging surface incl. Outbox); property #11 removed from the Inbox test. RED→GREEN proven; Architecture suite 142✔/1 skip; greenfield PHPStan clean. First fitness change validating PGP-03.
> - **PROCESS NOTE (2026-07-07):** TDD-first was breached on step 2's first attempt (production written before the failing test). Corrected via stash→RED→pop→GREEN. Lesson reinforced: RED **before** code, every step.
> - **✔ Step 1 (Contestation, 2026-07-07):** `TargetRef:string` → `ContestedOutcomeRef` VO (+ `ElectionId`/`TargetType`/`TargetId`); `Challenge::raise` + `ChallengeRaised` carry it (evolved in place — internal domain event, not published language; ADR-PL-01). RED→GREEN; ChallengeTest 12/12; Architecture 154✔/1 skip; greenfield PHPStan clean. ARB refinements folded (internal-event rationale; VO holds no transport — `fromParts` removed).
> - **✔ Step 2 (Adjudication, 2026-07-07 — ARB Option A):** `DeterminationIssued` payload schema v2 (additive optional `contestedOutcome`). Adjudication LOCAL VOs (ADR-T16); hydrator vCurrent(2)+vPrevious(1); `OutboxEventAdapter` stamps v2; mapper/model + additive migration. TDD RED-first (corrected after ARB caught the lapse). Adjudication 45✔. Dev guide `developer_guide/adjudication/`. Committed `f18d7ae37`.

## 6. Success criteria check (pre-declared)

☐ CONTEXT.md contains only active execution context · ☐ historical narrative preserved elsewhere (destinations verified in §1; R-1 items in §5) · ☐ no knowledge lost · ☑ ES-004.3 unchanged (untouched by this commission).

## STOP

Awaiting PA review of: the classification table (§1) · the proposed removals · the traceability evidence (§1 destinations + §5) · the Runtime outline (§3). The cleanup executes only after approval.
