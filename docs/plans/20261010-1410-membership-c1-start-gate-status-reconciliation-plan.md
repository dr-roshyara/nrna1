# Plan: C-1 start-gate status reconciliation (documentation only), PBDIGIT-268

**Status:** `IMPLEMENTED (revision 4 text, verbatim)`. Revision 4 was approved by the Principal Reviewer, and the Human PO / ARB gave explicit implementation clearance (2026-10-10). **G3 formal C-1 acceptance remains PENDING.** Previously revision 2, after the Human PO / ARB decisions A (approved, subject to the Principal Reviewer's wording review), B (deferred), C (approved) and the **confirmation of the identifier `C-STATUS-1`**. **Awaiting ChatGPT's wording review and explicit implementation clearance. No target file has been changed.** See §7 and §7.7.
**Story:** `PBDIGIT-268` · **Source:** independent diff-review evidence package (2026-10-10), finding 1 · commission `…/brainstorming/20261010_1410_implementatin.md` (second part) · **Baseline:** HEAD `21a2ff22f`, both target files clean.
**Authority so far:** the commission contains a *recommended* decision ("APPROVED IN PRINCIPLE — PLAN FIRST"). It is drafted by the Principal Reviewer and **not yet recorded** as given by the Human PO / ARB. This plan is prepared because the commission asks for planning only.
**Gates:** G1, G2 and G2-b are done (`b171768cc`, `21a2ff22f`). **G3 (formal C-1 acceptance) is PENDING and unaffected.**

## 1. Findings (read-only)

**F-A: contract, unnumbered "Decision register" (after §12), line 279.** Current text:
> `| C-1 | **CONDITIONALLY AUTHORISED by the PO 2026-10-10. BLOCKED** until the PO approves the C-1 plan and the gate is re-evaluated (§16) |`

- It describes the **start gate** (plan approval + gate re-evaluation), not acceptance.
- Both conditions are satisfied by repository evidence:
  - E1: C-1 plan `docs/plans/20261010-1009-membership-c1-annul-void-tdd-plan.md` line 3, "**APPROVED in principle by the PO, 2026-10-10, with conditions**";
  - E2: gate record line 57, "**C-1: ELIGIBLE TO START (gate re-evaluated 2026-10-10).** Conditions 1 – 7 are all evidenced";
  - E3: gate record "Gate re-evaluation (2026-10-10)", line 122, "**Determination: all applicable conditions are closed. C-1 is eligible to start.**"
- The contract's status line marks only §13 – §15 as historical, so this register is **not** covered. **The line is stale and inconsistent with §16**, which reads "Implemented, formal acceptance PENDING".
- The other rows of that register (C-2 / C-3 / C-4 BLOCKED, cutover BLOCKED, the OPEN items) are **still accurate**, so no change is proposed for them.

**F-B: gate record, row 4 (C-1-AUTH).**
- Status cell: "**CONDITIONALLY AUTHORISED; start conditions 1 – 3 OPEN**".
- Implication cell: "**C-1 BLOCKED** until conditions 1 – 3 are recorded".
- Both describe the **start gate**. They are superseded by the same record's own checklist (E2) and re-evaluation (E3); the row itself was never updated.

**F-C (new observation, outside the recommended decision): gate record line 122.** It still says "**Non-gate housekeeping still pending:** the `PBDIGIT-268` README index row (the README carries other sessions' uncommitted edits)". The row was added in `d90bf8af9`, so this note is stale too.

**F-D: C-AMEND-1 wording.**
- Criterion 1: "carries the caller's provenance, including populated authority evidence, unchanged". This concerns *preserving today's object* through a decision.
- Criterion 4: "The meaning and representation of 'reason', **and of the associated authority evidence**, are undecided". This concerns *business semantics and the required representation*.
- They are compatible on the intended reading. But criterion 4's phrase could be read as "the `AuthorityEvidence` object itself is undecided", which would seem to undercut criterion 1. **Mild ambiguity; no contradiction.**

## 2. Proposed patch: the two stale statuses (additive; historical text kept verbatim)

| # | File · location | Current text | Proposed text (additions in **bold italics** only here, to show them) | Evidence |
|---|---|---|---|---|
| P1 | contract · Decision register · C-1 row (line 279) | `\| C-1 \| **CONDITIONALLY AUTHORISED by the PO 2026-10-10. BLOCKED** until the PO approves the C-1 plan and the gate is re-evaluated (§16) \|` | `\| C-1 \| **CONDITIONALLY AUTHORISED by the PO 2026-10-10. BLOCKED** until the PO approves the C-1 plan and the gate is re-evaluated (§16) `***→ superseded 2026-10-10: plan approved by the PO (plan 20261010-1009) and start gate re-evaluated, so C-1 eligible to start (gate decision record: start-gate checklist; "Gate re-evaluation"). Since implemented (PBDIGIT-268). Formal acceptance PENDING (G3). Current status: §16. Reconciliation: register row 6, C-STATUS-1.***` \|` | E1, E2, E3; §16 |
| P2 | gate record · row 4 · Status cell | `**CONDITIONALLY AUTHORISED; start conditions 1 – 3 OPEN**` | `**CONDITIONALLY AUTHORISED; start conditions 1 – 3 OPEN**` `***→ superseded 2026-10-10: start conditions met; C-1 ELIGIBLE TO START (start-gate checklist; Gate re-evaluation). Row 6, C-STATUS-1***` | E2, E3 |
| P3 | gate record · row 4 · Implication cell | `**C-1 BLOCKED** until conditions 1 – 3 are recorded` | `**C-1 BLOCKED** until conditions 1 – 3 are recorded` `***→ superseded 2026-10-10: no longer blocked; implemented as PBDIGIT-268; formal acceptance is G3 (PENDING)***` | E2, E3; acceptance review §12 |
| P4 | gate record · **new row 6** after row 5 | — | `\| 6 \| **C-STATUS-1** \| Are the stale C-1 start-gate statuses (contract Decision register; row 4) reconciled with the recorded re-evaluation? \| **Human PO / ARB: APPROVED, <date>.** "<verbatim approval text>" \| **Human PO / ARB** \| <evidence of the decision> \| **APPROVED** \| none on acceptance (G3 pending); no change to implementation, C-AMEND-1, or the ratified act \| applied: superseding notes in the contract Decision register (C-1 row) and in row 4 (Status, Implication); historical text kept \|` | the established register format (rows 1 – 5) |
| P5 | contract · §16 · new summary row after C-AMEND-1 | — | `\| C-STATUS-1 \| **APPROVED by the Human PO / ARB <date>**: stale C-1 start-gate statuses superseded by additive notes (Decision register; register row 4). Register row 6 \|` | §16 is the summary of the register |

**Semantics.**
- The notes separate three states: **start eligibility** (re-evaluation), **implementation completion** (PBDIGIT-268) and **formal acceptance** (G3, PENDING).
- No historical word is deleted.
- The ratified act, C-AMEND-1, other decision rows and all code stay untouched.

## 3. Optional, separate items (each needs its own explicit approval)

| # | Item | Proposed text | Why separate |
|---|---|---|---|
| O1 | **C-AMEND-1 clarification (F-D)** | criterion 4: replace "and of the associated authority evidence" with "and which authority evidence is required and how it is represented"; criterion 1: append "(the existing `AuthorityEvidence` value object, preserved as supplied)" | it amends an **approved** annotation. It needs its own Human PO / ARB decision (e.g. C-AMEND-1a, a register row and a §16 row). Without that decision, the wording stays as it is and the intended reading is recorded only in this plan |
| O2 | **gate record line 122 (F-C)** | append "→ *done 2026-10-10:* index row added in `d90bf8af9`" | it lies outside the recommended decision's two locations |

## 4. Decision table

| Change | Covered by the recommended decision once it is given? | Needs additional approval? |
|---|---|---|
| P1, P2, P3 | **yes** (the two named locations: the Decision register; row 4 Status / Implication) | the exact wording must be approved (plan-first condition) |
| P4, P5 (recording C-STATUS-1) | **implied** by "record that …", in the established format | confirm the recording form (row 6 + §16 row) |
| O1 | **no** | yes: an amendment of the approved C-AMEND-1 |
| O2 | **no** | yes (small housekeeping) |

## 5. Risks, alternatives, preservation checks

**Risks:**
- A concurrent session edits a target file. Mitigation: re-check status immediately before committing; STOP on any change.
- Over-reach. Mitigation: additive notes only; diff review of every hunk.

**Alternatives:**
- **A (recommended):** additive superseding notes, as above.
- **B:** rewrite the stale cells. Rejected: it loses the audit trail.
- **C:** leave them and rely on §16. Rejected: contradictory governance evidence would remain.

**Preservation checks (as for G1 / G2):**
1. Snapshot status / staged / unstaged / untracked first. The other session's 3 staged renames must stay staged, byte-identical.
2. Precondition: no target path is staged.
3. Commit through a temporary index with explicit paths. **Read the complete staged patch**: only the P1 – P5 hunks (plus O1 / O2 if approved), only additions inside the existing cells, no `-` line except the one replaced line per changed table row.
4. Afterwards: the real index holds only the 3 renames; other sessions' unstaged diffs are identical to the snapshot; `git diff -- app tests database config` is empty.
5. Table column counts are consistent.
6. A semantic read of every "BLOCKED", "eligible", "accepted" and "PENDING" occurrence in the changed rows.
7. Commit subject `docs(membership): C-1 start-gate status reconciliation, C-STATUS-1 (PBDIGIT-268)`. No push.
8. Sanity: re-run the targeted 89 / 34 / 12 tests, as a no-change check.

## 6. Out of scope

- implementation, tests, the runner, adapter, schema, persistence;
- C-2, C-3, C-4 and PBDIGIT-269;
- the ratified architecture act;
- C-AMEND-1 semantics (O1 only if separately approved);
- G3, closing PBDIGIT-268, pushing.

## Progress

- 2026-10-10 14:10: plan written (the user asked that the plan be written down as a file). Target files unchanged. **STOP for review and approval.**

## 7. Revision 1: Human PO / ARB decisions and the FINAL PATCH (2026-10-10)

### 7.1 Decisions (verbatim in substance)

- **A, APPROVED:** "I authorise the additive, historical-text-preserving corrections P1–P5 … subject to ChatGPT's independent review of the exact wording." The records must distinguish:
  - plan approval in principle with conditions;
  - the subsequent start-gate re-evaluation and eligibility to start;
  - implementation under PBDIGIT-268;
  - formal acceptance, which remains PENDING under G3.

  The wording "must not imply unconditional plan approval". The decision is to be recorded in the register and its §16 summary.
- **B, DEFERRED:** no amendment to C-AMEND-1; CQ-3 remains OPEN. **O1 is dropped from this patch.**
- **C, APPROVED separately:** a minimal correction of the stale "README index row pending" note, recording the verified completion and the commit reference. **O2 becomes P6.**
- **G3 remains PENDING.** No acceptance, no closure, no runner / C-2 / C-3 / C-4 work.

### 7.2 Change from revision 0

P1 and P2 said "plan approved". They now say **"approved in principle by the PO, with conditions"**, following plan `20261010-1009` line 3 (decision A's wording condition).

### 7.3 Final patch: exact text (each change appends to an existing cell or line; nothing is deleted)

**P1 · contract `2026-10-09-…-semantic-contract-draft.md` · "Decision register" · C-1 row (line 279)**

Current:
```
| C-1 | **CONDITIONALLY AUTHORISED by the PO 2026-10-10. BLOCKED** until the PO approves the C-1 plan and the gate is re-evaluated (§16) |
```
Final:
```
| C-1 | **CONDITIONALLY AUTHORISED by the PO 2026-10-10. BLOCKED** until the PO approves the C-1 plan and the gate is re-evaluated (§16). → *Superseded 2026-10-10:* the C-1 plan was **approved in principle by the PO, with conditions** (plan `20261010-1009`); the subsequent start-gate re-evaluation **determined that all applicable conditions were closed and C-1 was eligible to start** (gate decision record: start-gate checklist; "Gate re-evaluation"); C-1 was then **implemented under `PBDIGIT-268`**; **formal acceptance PENDING (G3)**. Current status: §16. Reconciliation: gate decision record row 6, C-STATUS-1. |
```

**P2 · gate record · row 4 (C-1-AUTH) · Status cell**

> **FINAL (revision 4).** The appended text is the Principal Reviewer's exact wording, inserted verbatim: no paraphrase, shortening or added interpretation. It is appended after the unchanged historical cell text.

Current: `**CONDITIONALLY AUTHORISED; start conditions 1 – 3 OPEN**`

Final:
```
**CONDITIONALLY AUTHORISED; start conditions 1 – 3 OPEN** → *Superseded 2026-10-10:* the start-gate re-evaluation determined that all applicable conditions were closed and C-1 was eligible to start (start-gate checklist; “Gate re-evaluation”). See row 6, C-STATUS-1.
```

**P3 · gate record · row 4 · Implication cell**

Current: `**C-1 BLOCKED** until conditions 1 – 3 are recorded`

Final:
```
**C-1 BLOCKED** until conditions 1 – 3 are recorded → *Superseded 2026-10-10:* no longer blocked; implemented under `PBDIGIT-268`; formal acceptance is G3 (**PENDING**)
```

**P4 · gate record · new row 6, inserted directly after row 5**
```
| 6 | **C-STATUS-1** | Are the stale C-1 start-gate statuses (the contract "Decision register" C-1 row; row 4 Status / Implication) reconciled with the recorded start-gate re-evaluation, preserving the historical text? | **Human PO / ARB: APPROVED, 2026-10-10** (subject to the Principal Reviewer's wording review). "I authorise the additive, historical-text-preserving corrections P1–P5 in the proposed plan … The records must distinguish: Plan approval in principle with conditions. Subsequent start-gate re-evaluation and eligibility to start. Implementation under PBDIGIT-268. Formal acceptance, which remains PENDING under G3." | **Human PO / ARB** | Decision supplied in session `42e0287e…`, 2026-10-10; plan `docs/plans/20261010-1410-membership-c1-start-gate-status-reconciliation-plan.md` | **APPROVED** | none on acceptance (G3 pending); no change to the implementation, C-AMEND-1 or the ratified act | applied: superseding notes in the contract "Decision register" (C-1 row) and row 4 (Status, Implication); historical text kept; §16 summary row |
```

**P5 · contract · §16 · new summary row, inserted directly after the C-AMEND-1 row**
```
| C-STATUS-1 | **APPROVED by the Human PO / ARB 2026-10-10** (subject to the Principal Reviewer's wording review): the stale C-1 start-gate statuses (the "Decision register" C-1 row; register row 4) are marked superseded by additive notes: plan approved in principle with conditions → re-evaluation: all applicable conditions closed, eligible to start → implemented (`PBDIGIT-268`) → formal acceptance PENDING (G3). Historical text kept. Register row 6 |
```

**P6 (decision C) · gate record · "Gate re-evaluation (2026-10-10)", line 122, appended at the end of that paragraph**

Current tail: `**Non-gate housekeeping still pending:** the `PBDIGIT-268` README index row (the README carries other sessions' uncommitted edits).`

Appended:
```
 → *Done 2026-10-10 (Human PO / ARB decision C):* the row was added in commit `d90bf8af9` (verified: that commit adds exactly one README line, the `PBDIGIT-268` row, and removes none).
```

### 7.3a Identifier and evidence notes

- **`C-STATUS-1` is CONFIRMED by the Human PO / ARB** (2026-10-10, in the plan-finalisation authorisation). Decision A itself named no ID. It follows the register's existing pattern (`C-CONTRACT`, `C-1-AUTH`, `C-AMEND-1`).
- **Date:** 2026-10-10, the date the decision was supplied in session. No other date is used.
- **Wording sources:**
  - "approved in principle by the PO, with conditions": plan `20261010-1009` line 3;
  - "all applicable conditions … closed … eligible to start": gate record line 122 ("Determination: all applicable conditions are closed. C-1 is eligible to start.") and line 57 ("C-1: ELIGIBLE TO START (gate re-evaluated 2026-10-10)");
  - "implemented under `PBDIGIT-268`": commits `f7c251354` … `5765b9fa3` and the §16 C-1 row;
  - "formal acceptance PENDING (G3)": decisions G3, A, C;
  - README row: `d90bf8af9`.

### 7.4 Compliance with decision A's wording conditions

| Condition | Where it is met |
|---|---|
| plan approval **in principle with conditions** (not unconditional) | P1 "approved in principle by the PO, with conditions"; P4 quote; P5 "approved in principle with conditions". (P2 no longer restates it; reviewer wording, revision 4) |
| re-evaluation and eligibility to start | P1, P2, P5 |
| implementation under PBDIGIT-268 | P1, P3, P5 |
| formal acceptance PENDING (G3) | P1, P3, P5; P4 implication |
| historical text kept | P1 – P3 append after the unchanged original; P4 – P6 are additions |
| recorded in the register and §16 | P4, P5 |

### 7.5 Preservation plan (implementation, after ChatGPT confirms)

1. **Snapshot:** HEAD, `git status --porcelain=v1`, `git diff --cached` and per-file unstaged diffs, saved in the scratchpad.
2. **Precondition:** neither target file is staged or modified; otherwise STOP.
3. **Edit through an asserting script:** each anchor must match exactly once, or abort. The anchors are the line-279 row text, the row-4 cell texts (used only within the row starting `| 4 | **C-1-AUTH**`), the row-5 line (for the insertion), the C-AMEND-1 §16 row (for the insertion), and the line-122 tail.
4. **Temporary index with explicit paths:** the contract, the gate record, this plan (progress line) and the session log (only if its diff is entirely mine). **Read the full staged patch.** Expected `-` lines: exactly four (the P1 row, the row-4 line, the line-122 line and the plan's status line). Every `+` line must be one of P1 – P6, plus the plan and session lines.
5. **After the commit:**
   - align the real index for these paths only (they were not staged before);
   - the 3 renames are still staged, byte-identical;
   - other sessions' unstaged diffs are identical to the snapshot (ignoring blob-hash lines);
   - `git diff -- app tests database config routes resources …/c1_sensitivity` is empty;
   - table column counts are consistent;
   - every "BLOCKED", "eligible", "approved", "accepted" and "PENDING" in the changed lines is read in context.
6. **Commit:** `docs(membership): C-1 start-gate status reconciliation C-STATUS-1, README-row note done (PBDIGIT-268)`. No push.
7. **Sanity:** the targeted 89 / 34 / 12 tests, as a no-change check.

### 7.6 Out of scope (unchanged), plus B

- C-AMEND-1 wording (deferred, B);
- the other "Decision register" rows (still accurate);
- the ratified act, code, tests and runner;
- G3, closing PBDIGIT-268, pushing.

### Progress (revision 1)

- 2026-10-10: final patch written after decisions A / B / C. Target files unchanged. **STOP: awaiting ChatGPT's wording review.**
- 2026-10-10 14:30 (Principal Reviewer review `20261010_1430_implementation.md.md`):
  - **P4 decoupled.** Decision B's text was removed from row 6, so the row records decision A only. B (deferred) changes nothing and stays recorded only in this plan and the session log.
  - **`C-STATUS-1` stays PROPOSED** until the Human PO / ARB confirms or replaces it. In the implementation, the ID in P1 – P5 is the confirmed one.
  - Baseline re-checked (`21a2ff22f`; targets clean; only the 3 other-session renames staged). **STOP:** awaiting the ID confirmation, ChatGPT's wording review and implementation clearance.

### 7.7 Approval state (current, revision 2)

| Item | State | Source |
|---|---|---|
| A: reconciliation P1 – P5 | **APPROVED, subject to the Principal Reviewer's wording review** | Human PO / ARB decision A, 2026-10-10 |
| identifier `C-STATUS-1` | **CONFIRMED** | Human PO / ARB, plan-finalisation authorisation, 2026-10-10 |
| C: README-row note (P6) | **APPROVED** (separately); evidence verified (`d90bf8af9` adds exactly the PBDIGIT-268 row) | Human PO / ARB decision C, 2026-10-10 |
| B: C-AMEND-1 clarification | **DEFERRED**; not in this patch; C-AMEND-1, §14.5 and CQ-3 unchanged | Human PO / ARB decision B, 2026-10-10 |
| ChatGPT wording review of P1 – P6 | **PENDING** | — |
| implementation clearance | **PENDING** | — |
| G3 formal C-1 acceptance | **PENDING** | — |

**The approval qualifier is used consistently.** The proposed register entries that record decision A (P4, P5) both carry "(subject to the Principal Reviewer's wording review)". P1 – P3 do not record decision A's approval; they describe the C-1 plan's *approval in principle with conditions*, which is a different approval and is worded as such. P6 records decision C, which carried no review qualifier.

- 2026-10-10 (revision 2, plan only):
  - `C-STATUS-1` recorded as **confirmed**;
  - **P5 corrected** to carry "(subject to the Principal Reviewer's wording review)", like P4. This follows the Principal Reviewer's correction to P5; its text was not supplied to the developer, so it is inferred from the instruction "keep approval wording consistent … preserving the qualification that the original decision was subject to wording review";
  - approval-state table §7.7 added;
  - baseline re-checked: `21a2ff22f`; target files clean; only the 3 other-session renames staged.

  **STOP:** awaiting ChatGPT's wording review and explicit implementation clearance.

### 7.8 Principal Reviewer wording review (2026-10-10): outcome

| Change | Outcome | Applied in this plan |
|---|---|---|
| P1 | accept | unchanged |
| P2 | **refine** to the reviewer's exact shorter wording | **done (revision 4):** the reviewer's wording was inserted verbatim and appended to the unchanged historical cell text |
| P3 | accept | unchanged |
| P4 | accept (keeping the wording-review qualifier; decision A only) | unchanged (B already removed in revision 1) |
| P5 | accept with the confirmed qualifier | qualifier present: "(subject to the Principal Reviewer's wording review)", consistent with P4 |
| P6 | accept (decision C; evidence `d90bf8af9`) | unchanged |

**Confirmed:**
- `C-STATUS-1` is confirmed by the Human PO / ARB (§7.3a, §7.7);
- decision B (the C-AMEND-1 clarification) stays DEFERRED and outside this patch;
- the four states stay distinct: approved in principle with conditions; eligible to start after the re-evaluation; implemented under PBDIGIT-268; formal acceptance PENDING (G3).

**Open questions:**
1. ~~The exact P2 wording from the Principal Reviewer~~: **supplied and incorporated (revision 4).**
2. **ChatGPT's review of revision 4**, then **explicit Human PO / ARB implementation clearance**.

- 2026-10-10 (revision 3, plan only):
  - wording-review outcome recorded (§7.8);
  - P2 marked NOT FINAL, because the reviewer's exact wording was not received and the developer did not invent replacement text;
  - P5 qualifier verified;
  - `C-STATUS-1` confirmed;
  - baseline re-checked (`21a2ff22f`; targets unchanged; 3 renames staged).

  **STOP:** awaiting the P2 wording, then implementation clearance.
- 2026-10-10 (revision 4, plan only):
  - **P2 replaced** by the Principal Reviewer's exact wording, verbatim: `→ *Superseded 2026-10-10:* the start-gate re-evaluation determined that all applicable conditions were closed and C-1 was eligible to start (start-gate checklist; “Gate re-evaluation”). See row 6, C-STATUS-1.`, appended after the unchanged historical cell text;
  - the §7.4 compliance row is corrected, because P2 no longer restates the conditional approval (P1, P4 and P5 still do);
  - §7.8 and the open questions are updated;
  - P1 and P3 – P6 are unchanged; `C-STATUS-1` stays confirmed; decision B stays deferred;
  - baseline re-checked (`21a2ff22f`; targets unchanged; 3 renames staged).

  **STOP:** awaiting ChatGPT's review of revision 4 and implementation clearance.
- 2026-10-10 (implementation):
  - the Principal Reviewer **approved revision 4** (`…/brainstorming/20261010_1728_membershipC1 — revision 4 review.md`), and the **Human PO / ARB authorised implementation** of P1 – P6;
  - P1 – P6 were applied verbatim from §7.3: extracted programmatically from the code blocks; exact-match assertions; row-4 changes confined to the row-4 line;
  - the "(subject to the Principal Reviewer's wording review)" qualifier in P4 / P5 is kept as **historical context** for decision A. That review is now **complete** and implementation is **cleared**, as recorded here, in the session log and in the commit message. No register wording was added beyond P1 – P6.
