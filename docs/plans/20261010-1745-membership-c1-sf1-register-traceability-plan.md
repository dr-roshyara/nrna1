# Plan: SF-1, register traceability for C-STATUS-1 (documentation only), PBDIGIT-268

**Status:** `IMPLEMENTED` (2026-10-10) with the **final approved wording in §11**, which supersedes the §4 drafts. Approved by the Human PO / ARB (SF-1 decision); reviewed by the Principal Reviewer. **G3 PENDING; PBDIGIT-268 not accepted or closed.**
**Source:** read-only commit audit of `76b1192de`, finding SF-1 · commission `…/brainstorming/20261010_1741_publicDigit_membership.md`.
**Written as a file** because of the user's standing instruction ("when you write plan you also need to write down"), which overrides the commission's "create no files". The file stays uncommitted.
**G3 is PENDING. PBDIGIT-268 is not accepted or closed.**

## 1. Baseline verified (read-only)

- **Branch and HEAD:** `knowelegeos-modelling`, HEAD `76b1192deb03a1ebfb101ac97c75b5f5fdbba21d` (as reported).
- **Real index:** only the 3 other-session renames.
- **Target files, both clean:**
  - `docs/publicdigit/reviews/2026-10-10-membership-c1-gate-decision-record.md` (SHA-256 prefix `321db03e…`);
  - `docs/publicdigit/reviews/2026-10-09-membership-correction-slice-semantic-contract-draft.md` (`3816069a…`).

## 2. Evidence and provenance

| Fact | Where recorded | Kind |
|---|---|---|
| decision A ("APPROVED … subject to the Principal Reviewer's wording review") | register row 6, decision cell; §16 C-STATUS-1 row | **authoritative** (committed `76b1192de`) |
| Principal Reviewer approved plan revision 4 | `docs/publicdigit/membership/architecture/brainstorming/20261010_1728_membershipC1 — revision 4 review.md` line 3: "revision 4 is approved for the narrowly scoped P1–P6 documentation implementation" | **primary source, but UNTRACKED** (not in git) |
| Human PO / ARB implementation clearance | the same untracked file, line 3: "The Human PO/ARB has now explicitly authorised implementation"; line 24 | **the Principal Reviewer's statement of your clearance**, supplied in session by the Human PO / ARB. It is not a text written by the Human PO / ARB. Untracked |
| both facts, restated | plan `20261010-1410` (status line; progress line 280), session log 2026-10-10, commit message of `76b1192de` | supporting traceability (committed) |
| implemented in `76b1192de` | `git show 76b1192de` (the audit: 11 / 11 conformance checks) | repository fact |

## 3. SF-1 analysis

**Why the record is ambiguous.** Row 6 and the §16 row say "APPROVED … (subject to the Principal Reviewer's wording review)". The register is the authoritative decision source, and §16 is its summary. Neither records that the condition was later satisfied. A reader of the authoritative record alone could take it as an **outstanding condition**.

**Whether each proposed clause is supported:**

| Proposed clause (audit) | Supported? | Recommendation |
|---|---|---|
| "wording review completed (the Principal Reviewer approved plan revision 4, 2026-10-10)" | **yes**, but its primary source is untracked | keep, and **cite the source file by path**, noting it is not tracked |
| "implementation cleared by the Human PO / ARB, 2026-10-10" | **partly.** The clearance is attested by the Principal Reviewer's document as supplied by the Human PO / ARB, not by a text written by the Human PO / ARB | **narrower wording:** "implementation clearance by the Human PO / ARB conveyed in session 2026-10-10 (stated in that review document)" |
| "implemented in 76b1192de" | **yes** (repository fact) | keep. Placed in the "applied" cell (column 9), which records execution, rather than the evidence cell |
| §16: "Wording review completed; implemented in 76b1192de." | yes | extend with the clearance clause and "G3 PENDING", so §16 stays a faithful summary |

**Identifier.** `C-STATUS-1` is the correct existing reference. These clauses record *satisfaction of its condition and its execution*, not a new decision. Precedent: row 5's "applied:" cell. **No new decision ID.**

## 4. Exact proposed changes (append only; existing text unchanged)

**S1 · gate record · row 6 · evidence cell (column 6).**

Current:
> `Decision supplied in session `42e0287e…`, 2026-10-10; plan `docs/plans/20261010-1410-membership-c1-start-gate-status-reconciliation-plan.md``

Proposed (appended):
> `; **wording review completed:** the Principal Reviewer approved plan revision 4, 2026-10-10; **implementation clearance** by the Human PO / ARB conveyed in session 2026-10-10 (both stated in `docs/publicdigit/membership/architecture/brainstorming/20261010_1728_membershipC1 — revision 4 review.md`, not tracked in git)`

**S2 · gate record · row 6 · applied cell (column 9).**

Current:
> `applied: superseding notes in the contract "Decision register" (C-1 row) and row 4 (Status, Implication); historical text kept; §16 summary row`

Proposed (appended):
> `; implemented in commit `76b1192de` (audit: P1 – P6 conform). Formal acceptance remains G3 (PENDING)`

**S3 · contract §16 · C-STATUS-1 row.** Current text ends with `… Historical text kept. Register row 6 |`. Proposed: insert before the closing ` |`:
> `. Wording review completed and implementation cleared (2026-10-10); implemented in `76b1192de`; **G3 PENDING**`

**Unchanged:** the qualifier "(subject to the Principal Reviewer's wording review)" in row 6 and §16 (historical); row 6 Status "**APPROVED**"; all other rows.

## 5. Semantic safeguards

The five facts stay separate:
1. **plan approval with conditions:** unchanged (P1, P4, P5);
2. **wording review completed:** S1, S3;
3. **implementation clearance:** S1, S3, with its provenance stated;
4. **implemented in `76b1192de`:** S2, S3;
5. **G3 PENDING:** S2, S3, plus the existing row 6 implication "none on acceptance (G3 pending)".

No text says or implies that C-1 is accepted or closed, or that clearance equals acceptance.

## 6. Scope and preservation

- **May change after approval:** the gate record (S1, S2), the contract §16 (S3), this plan (progress), and the session log (appended entry).
- **Excluded:**
  - code, tests, DB, config, routes, resources, models and the runner;
  - the ratified act; C-AMEND-1, §14.5, CQ-3;
  - all other register rows and contract sections;
  - committing the untracked review file (only with separate approval; see §8, question 2).
- **Preservation:** as in plan `20261010-1410` §7.5:
  - snapshot first, and re-check;
  - no target path staged;
  - an asserting script: S1 / S2 anchored within the line `| 6 | **C-STATUS-1** |` only, S3 within the line `| C-STATUS-1 |` only;
  - a temporary index with explicit paths;
  - the 3 renames stay staged, byte-identical;
  - other sessions' unstaged diffs stay unchanged.

## 7. Verification plan (after implementation; not run now)

1. **Reconstruction check:** parent + exactly S1 – S3 must equal the committed files (as in the P1 – P6 audit).
2. **The historical qualifier is still present,** unchanged, in row 6 and §16.
3. **Table column counts** are unchanged in both files.
4. **Status screen:** read every "APPROVED", "completed", "cleared", "PENDING" and "accepted" in the changed lines in context.
5. **Provenance:** the cited file path exists in the working tree; the commit hash `76b1192de` resolves.
6. **Excluded paths are unchanged;** nothing is pushed.
7. **Tests:** a test rerun is **not** part of SF-1 (documentation only). A separate read-only rerun with exit-code capture can be authorised on its own.

## 8. Risks and open questions

1. **Provenance quality.** The completion and clearance facts rest on an **untracked** document, authored by the Principal Reviewer and conveying your clearance. Do you want the clearance recorded as worded in S1 ("conveyed in session … stated in …"), or will you supply your own explicit clearance statement to quote?
2. **Track the source?** Should `…/20261010_1728_membershipC1 — revision 4 review.md` be committed, so the authoritative record cites a versioned source? It is outside SF-1 and needs separate approval, and the brainstorming folder is shared corpus.
3. **Cell placement.** I put the execution fact in column 9 (applied) and the satisfaction of the condition in column 6 (evidence). The reviewer may prefer everything in one cell.

## 9. Approval gates

1. The Principal Reviewer reviews this plan.
2. The Human PO / ARB explicitly approves the final wording, including the answers to §8 questions 1 – 2.
3. Only then do I implement S1 – S3, run the §7 checks and stop. **Previous approval of P1 – P6 does not authorise SF-1.**

## 10. Recommendation

**Approve with the narrowed S1 wording.** It closes SF-1 additively, keeps the historical qualifier, states the provenance honestly (including that the source is untracked), creates no new decision, and keeps G3 explicitly PENDING.

## Progress

- 2026-10-10 17:45: plan written. Targets unchanged. **STOP for review and approval.**

## 11. Final approved wording and decision (2026-10-10; supersedes §4)

**Human PO / ARB decision, SF-1 (verbatim in substance):**
- "I approve the SF-1 documentation-only change, subject to the clarified S1 wording proposed by the Principal Reviewer."
- S1 – S3 are approved; G3 stays explicitly PENDING; only the gate decision record, the contract §16 C-STATUS-1 summary, and plan / session-log traceability are authorised.
- The untracked source review document is not to be committed. No code, test, DB, runner, C-AMEND-1, §14.5, CQ-3 or ratified-act change; no push; no test rerun.

**Final text, as applied:**
- **S1, row 6, evidence cell, appended:**
  > `; Implementation authorization (conditional, decision A): “Implementation may proceed only after that review confirms compliance with these decisions” (Human PO/ARB, session 2026-10-10). Prerequisite satisfied for the P1–P6 documentation implementation: the Principal Reviewer approved plan revision 4 on 2026-10-10, authorizing the narrowly scoped P1–P6 documentation implementation. Source: docs/publicdigit/membership/architecture/brainstorming/20261010_1728_membershipC1 — revision 4 review.md (currently untracked).`
- **S2, row 6, applied cell, appended:**
  > `; P1 – P6 executed in commit `76b1192de` (audit: all 11 conformance checks passed). Formal acceptance remains G3 (**PENDING**)`
- **S3, §16 C-STATUS-1 row, before the closing pipe:**
  > `. Condition satisfied and P1 – P6 executed in `76b1192de` (provenance: register row 6); **G3 PENDING**`

**Developer's note (disclosed, not acted on):**
- The approved S1 says the reviewer's approval was "authorizing" the P1 – P6 implementation. The source document says "approved for". Under the role model only the Human PO / ARB authorises.
- In context it is consistent: under decision A, the reviewer's approval satisfies the condition of your conditional authorisation.
- It was implemented verbatim, as approved (including the US spelling "authorization"). A wording refinement would need a separate decision.

- 2026-10-10: S1 – S3 implemented verbatim; see the session log and the commit. **STOP:** the evidence goes to the Principal Reviewer before any G3 decision.
