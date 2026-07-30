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

## 3. Proposed Runtime CONTEXT.md — **v1, SUPERSEDED by §7's draft v2** (the runtime-usefulness verification found v1 fails the 90% density target)

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

## 7. F-2 Final Verification Report — Runtime Usefulness Test (PA commission, 2026-07-30)

**Test applied to every section of draft v1:** *if this section disappeared today, could a new engineering session still begin correctly from the remaining runtime artifacts?*

### 7.1 Updated classification table (new column: Operationally Needed Today?)

| Section (v1 draft) | Runtime? | Historical? | Traceable? | Needed Today? | Action |
|---|---|---|---|---|---|
| Header + Updated | Yes | No | — | **Yes** (artifact identity) | Keep |
| Program position (4 lines) | Yes | Pointer only | — | **Yes** — prevents misreading MEMORY's closed-epic entries as pending work | Keep (already minimal) |
| Active Work `Key:` block | Yes | No | — | **Yes** (hook-parsed contract) | Keep |
| Detail bullets (WP-2 keystones · EG status) | Yes | No | Roadmap + repair plan hold full text | **Yes, as pointers** | Keep (already compressed) |
| Next action / Blockers | Yes | No | — | **Yes** | Keep |
| Open items (6 lines) | Mixed | No | AD-M2→ADR-MP-05/Q5 · Jurisdiction→EPIC-004F+MEMORY · O-2→validation report · parked candidates→logs 07-26/30 | **Partially** — none needed for WP-2 *today*, but the parked-candidate timers (~WP-3/4) must resurface at session start | **COMPRESS 6→2 lines** |
| Engineering Platform block (15 lines verbatim) | Yes | Narrative-heavy | R-40 · decision papers · metamodel candidate · log 07-27 · reference map | **Yes, ~6 lines' worth** — a session opening on D-1..D-5 / C3 / OQ-ENG-004 / KnowledgeOS G-1 needs the pending-decision list + artifact pointers, NOT the narrative | **COMPRESS 15→6** |
| PKS block (4 lines) | Yes — **ACTIVE, not paused** (gated on an explicit pending DA/PA choice: Capabilities Pass vs ARB review) | Synthesis detail is narrative | Reports + log 07-27 | **Yes, 2 lines' worth** | **COMPRESS 4→2** |
| Parallel: C3/OQ-ENG-003 pointer (3 lines) | Yes — authorized-pending, fresh-session requirement | No | C3 plan holds the verbatim instruction ✔ | **Yes** | Keep (already 30→3) |
| Parallel: 38D-02 line | No — **future planning** ("after ARB digests 38D-01") | Yes | `.claude/memory/` governance archive | **No** | **MOVE → BACKLOG.md** (never silently delete) |
| Parallel: docs-candidates line | No — **future planning** | Yes | weak (logs only) | **No** | **MOVE → BACKLOG.md** |
| Automation (3 lines) | Yes | No | `.claude/scripts/README.md` | **Yes, 1 line's worth** — the `Plan:`-line injection contract is load-bearing; the rest duplicates the README | **COMPRESS 3→1** |

### 7.2 Runtime Density

| Draft | Total lines | Runtime-necessary | Density | vs >90% target |
|---|---|---|---|---|
| v1 (§3) | ~66 | ~52 | **~79%** | **FAIL** |
| **v2 (§7.4)** | **~44** | **~42** | **~95%** | **PASS** |

v1's failing weight: the 15-line platform narrative, 4-line PKS synthesis, 6-line open items, 2 future-planning lines, 3-line automation block.

### 7.3 Sections retained solely because operationally necessary

Program-position orientation (misreading guard) · `Key:` block (hook contract) · WP-2 springboard + EG pointer · pending-DA decision list + fresh-session pointers (C3/OQ-ENG-004) · PKS gate · parked-candidate timers (~WP-3/4) · the `Plan:`-line injection contract.

### 7.4 Draft v2 — the runtime-dense replacement (~44 lines)

    # Current Working State

    **Updated:** 2026-07-30

    ## Runtime Baseline
    Strategic + tactical baselines FROZEN; implementation EXECUTING. EPIC-001..004 formally closed;
    the governing baseline (Determination chain · APM ADR-T21/22/23 · Q-2 gate · 8-WP roadmap),
    durable constraints, and preferences live in `.claude/MEMORY.md`; history lives in
    `.claude/sessions/` and the EPIC/PB documents. Do not re-derive; do not reopen without a
    recorded reversal condition.

    ## Active Work (structured — hooks parse these `Key:` lines; keep the format)
    Ticket: WP-2 (EPIC-004 APM core) — AUTHORIZED, opens in a fresh session with its own work plan; WP-1 ACCEPTED+CLOSED (ARB 2026-07-27)
    Plan: .claude/plans/WP-1-evidenceset-v3.md
    Branch: feature/pb003 (ACTIVE — baseline-release-1.1 tagged behind it)
    Milestone: EPIC-004 Architecture-to-Implementation roadmap — WP-1 ✔ accepted · WP-2 authorized (1/8 WPs closed)
    Priority: High

    - **Detail:** WP-2 keystones (roadmap §WP-2): exactly-once conclusion under concurrent redelivery ·
      no admission after conclusion · unique-active-per-challenge under race. Execution-contract
      pattern: `.claude/plans/WP-1-evidenceset-v3.md` (Auto mode · RED first · STOP conditions).
      The WP-2 session creates its own plan and re-points the `Plan:` line.
    - **Engineering platform:** operational for the identified defects (EG-001..003 ✔); EG-002b ·
      EG-004 · EG-005 tracked in `docs/plans/20260726-2056-engineering-platform-repair-plan.md` — non-blocking.

    ## Next action (exactly one)
    **WP-2, fresh session, RED first** (slice acceptance before anything else opens).

    ## Blockers
    None.

    ## Open items (inventory — full text at the cited homes)
    - Tracked: AD-M2 (ADR-MP-05/Q5, only under business pressure) · G-1 release tag · Jurisdiction
      semantics (EPIC-004F, non-blocking) · O-2 composite-artifact watch (ES-004.3 validation report).
    - **Timers:** parked candidates resurface ~WP-3/WP-4 — slice ledger · Implementation Execution
      Contract extraction (logs 2026-07-26/30).

    ## Engineering Platform track (state 2026-07-27; full record: session log 07-27 + cited artifacts)
    - **Awaiting DA (the track's next action):** Placement Rule decisions **D-1..D-5**
      (`docs/implementation/Placement_Rule_Decision_Paper.md`) + the ES ratification batch; metamodel
      stays CANDIDATE (`engineering/architecture/reference/Engineering_Platform_Knowledge_Metamodel.md`).
    - **Then, each in a FRESH session:** C3 + OQ-ENG-003 (C3 plan APPROVED AS WRITTEN —
      `.claude/plans/AIP-iteration-1-construction.md`) · OQ-ENG-004 (PD-10) · A5 Transition Plan → platform freeze.
    - **KnowledgeOS:** prepared stack in `docs/implementation/KnowledgeOS_*`; ONE gate — G-1 charter
      approval (three asks); nothing executes until it passes. Navigational map (consult, don't refine):
      `engineering/architecture/c4/Engineering_Knowledge_System_Reference_Model.md`.

    ## PKS track (Phase 1 COMPLETE, gated)
    Two Phase-1 reports + PA-endorsed synthesis await the DA/PA choice: commission the **Capabilities
    Pass** or convene the ARB review. Strategic Modeling unauthorized until then. (Record: log 2026-07-27.)

    ## Automation
    SessionStart injects MEMORY + CONTEXT + the plan the `Plan:` line above declares + today's log;
    details and other hooks: `.claude/scripts/README.md`.

### 7.5 Pre-edit conversions required (execution additions beyond CONTEXT.md)

1. **BACKLOG.md gains two lines** (future planning relocated, not deleted): 38D-02 Capability Relationships (after ARB digests 38D-01) · docs candidates (Domain Model Catalogue · root CLAUDE.md tech-table refresh).

### 7.6 Final recommendation

☑ **Identify-and-convert path taken: draft v2 supersedes v1.** With v2: every removal traceable (§1, §7.1) · every retained section passes the usefulness test (§7.3) · density ~95% > 90% **PASS** · no historical narrative remains except the 2-line orientation guard · ES-004.3 untouched.

## 8. PA RULING (2026-07-30): APPROVED WITH MINOR EDITORIAL AMENDMENTS — execution authorized

1. **"Program position" renamed "Runtime Baseline"** (the section states runtime assumptions, not program narrative) — applied to §7.4 above.
2. **Runtime Density = commission evidence ONLY, never a standing governance metric.** The ~95% figure stays in this report; no percentage target is institutionalized anywhere — the governing rule remains qualitative (*runtime artifacts contain only information operationally necessary for current execution*, ES-004.3 Runtime role). CONTEXT.md itself carries no density language (verified: draft v2 contains none).
3. **Reusable observation recorded (not minted into a standard — evidence-before-promotion):** three distinct hygiene review lenses — Traceability (*removable safely?*) · Runtime Usefulness (*should it exist here?*) · Governance Consistency (*obeys ES-004.3?*) — kept separate, reusable for future repository hygiene beyond CONTEXT.md.

**Executed per the ruling's instruction list:** amendments → R-4 re-read → v2 replacement → BACKLOG relocation → SessionStart sanity check → commit. (Push = user action; passphrase-protected key.)

---

## 9. Implementation Fidelity Verification (PA commission, 2026-07-30) — RESULT: EXACT, no corrective edit required

**The commission's premise does not hold.** `CONTEXT.md` on disk is **byte-identical to approved draft v2**; no historical material survived the prune. Evidence below; no edit was performed (none is warranted — a "corrective" rewrite would risk damaging a correct file).

### 9.1 Section comparison (Step 1–3 of the commission's method)

| Approved section (draft v2) | Present in actual? | Extra material? | Matches? |
|---|---|---|---|
| `# Current Working State` + Updated | ✅ Yes | No | ✅ |
| `## Runtime Baseline` | ✅ Yes | No | ✅ |
| `## Active Work` (Key: block) | ✅ Yes | No | ✅ |
| Detail bullets (WP-2 keystones · EG status) | ✅ Yes | No | ✅ |
| `## Next action (exactly one)` | ✅ Yes | No | ✅ |
| `## Blockers` | ✅ Yes | No | ✅ |
| `## Open items` (2 lines) | ✅ Yes | No | ✅ |
| `## Engineering Platform track` (6 lines) | ✅ Yes | No | ✅ |
| `## PKS track` (2 lines) | ✅ Yes | No | ✅ |
| `## Automation` (1 line) | ✅ Yes | No | ✅ |

**Extra headings in actual but not in draft v2: NONE** (Step 4 yields an empty set — so Step 5's usefulness test has no inputs).

### 9.2 Machine evidence

1. **Byte-level diff** — draft v2 extracted programmatically from §7.4 vs the file on disk: **54 lines vs 54 lines, zero differences** (`difflib.unified_diff` → empty).
2. **Residual-history probe** — combined grep for `EPIC-002 track` · `What remains (EPIC-001)` · `superseded` · `PB-004..PB-007` · `Architecture debt` · `PROCESS NOTE` · `constitutional consolidation COMPLETE` · `Parallel tracks`: **0 matches**.
3. **Commit `aee8a4948` diffstat: 42 insertions, 148 deletions** — the deleted headings include exactly the sections classified for removal (`## EPIC-002 track — CLOSED`, `## What remains (EPIC-001)`, `## Architecture debt (tracked)`, `## Engineering Platform — constitutional consolidation COMPLETE`, `## Parallel tracks`); the added headings are exactly v2's. **Replacement, not merge** — the file was written whole, not appended to.

### 9.3 The three critical questions

1. **Was Runtime Draft v2 implemented exactly?** **Yes** — byte-identical (§9.2 evidence 1).
2. **What additional sections remain?** **None** (§9.2 evidence 2 and 3).
3. **Are those remaining sections operationally required today?** **Not applicable** — the set is empty.

### 9.4 Most probable source of the reported divergence (recorded so the record is honest, not defensive)

The corrective-edit turn contains, immediately before the write, a full `Read` of the **pre-prune** 161-line `CONTEXT.md` (the R-4 guard required reading the file before editing). That historical content appears in the transcript directly adjacent to the write call and is easy to read as the write's payload. The write payload itself was the 54-line v2 body — confirmed by the diffstat's 148 deletions.

### 9.5 Acceptance criteria (commission's own list)

☑ every approved section present · ☑ every actual section is in the approved draft · ☑ no historical narrative remains · ☑ no approved section present in modified form · ☑ ES-004.3 unchanged.

### 9.6 Observation worth keeping (recorded, NOT minted as a rule)

The commission articulated a real distinction: **design validation** asks *"is this the right design?"*; **implementation verification** asks *"was the approved design implemented faithfully?"* — different quality gates. This instance is also evidence for a second, subtler point: **implementation verification must be evidence-led, because a reviewer's reading of a transcript is not the state of the repository.** Filed alongside the three hygiene lenses (§8.3) as reusable observations; no standard minted (evidence-before-promotion).

**F-2 COMMISSION CLOSED — design approved, implementation verified exact.**
