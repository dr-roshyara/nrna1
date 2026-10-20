# Theory turn 7: rules common to commissions A, B, C and D

*2026-10-20. By the Professor. Turn 7 is the last turn of the critical-path chain; after it the chain ends and reading resumes at S0101. The brief lists all changed theory in full, as in turns 5 and 6. It is too long for one commission, so it is split in four. All four are settled together.*

| Commission | Object, in full | Changed since its last review |
|---|---|---|
| A | §9.13, the evaluator (v6) | v5 (turn-6 settlement, commission A, 12 rows) and v6 (the Principal's decisions after turn 6), and a turn-7 note on ties |
| B | §9.14, when pairs suffice (v3) | v3 (turn-6 settlement, commission B, 11 rows) |
| C | §9.15, the trust boundary (v3) | v2, a full rewrite (turn-6 settlement, commission C, 16 rows), and v3 (the Principal's decisions after turn 6), and three turn-7 notes |
| D | §9.16, the reassessment (new), and the Ext→Src corrections | all new |

**The Principal's instruction for this turn:** "Apply these as changed theory in turn 7. Then turn 7 as planned, with the limit: no new turns. After turn 7 the chain ends and reading resumes at S0101." The turn-7 deliverable: "Reassess what depends on these, including the history and trust results and where F22 and K4 belong."

**The Principal's decisions after turn 6, applied as changed theory (verbatim):**
1. "Decision 3 as you applied it: U only when nothing else decides the act; T with another intact basis; F when the link is already defeated; the reason names the act and the link. Roots cannot be annulled (turn 5), so they are not a case. A cited basis the act doesn't need does not matter, because reasons follow only what the answer rests on."
2. "Recorder and authentication status: D requires both fields on every entry, separately from the actor; 'unsigned' is an allowed value. Q1 marks answers that rest on transcribed or unsigned entries; it does not refuse them."
3. "Removal of a cited entry: by tombstone. The loss entry keeps identity, kind and the fields citations rely on, and drops the content. The record stays in D, and answers resting on it are marked 'rests on removed content'. Record this as a partial answer to F30."

**Where they are applied:** §9.13 v6 (D's field list, the tombstone bullets, the output and marks, decision 3 decided); §9.15 v3 (Status, R6); §9.12 (row I15); §9.8.8 (F30's partial answer); THEORY-MAP (F30). Commit `1cc1e002f`. The turn-7 draft is commit `155123f01` plus the R7 addition in §9.16.

## Access (binding)

- **Allowed:** the working book, register, map, outline, review folders, tools and git history of W (`docs/knowledgeos/knowlegeos_manual_theory_20261018/`). The decision record (`03-methodology/`) is read only.
- **Corpus:** only files listed in `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`, with sha256 matching.
- **No Ch. 0–2 basis files** (purpose "basis:" in `archive/00-access/READ-LOG.tsv`). A check that needs one is marked U.
  - The book's own chapter files (`06-theory/manuscript/chapter_0*/`) are **not** basis files: read them freely.
- **Never:** `.env*`, `_batch_input*`, P3B-HOLDOUT, sealed or quarantine files, or `docs/knowledgeos/chronological-read/*`. No corpus or book text in any search or outside service. Edit nothing except your report.
- **Scratch:** scripts outside the repository, in the session scratchpad, run with `python3 -I`.

## Challenge by type (practice 5)

- **Mathematical results:** by a countermodel or an invalid step.
- **Definitions:** by boundary cases, coherence, consequences and a rival.
- **The Principal's decisions:** by the decision maker's words and the consequences, not by a falsifier. Check that each is applied as worded, no more and no less.

## Verification marks (practice 6)

All the changed text was written, read and checked by the Professor. No helper session wrote any part. The turn-6 reviewers found the defects that §9.13 v5, §9.14 v3 and §9.15 v2 correct; **those corrections have not been re-reviewed**. §9.16 and v6 have not been reviewed at all.

## Quote check (entry 60), at the draft

`corpus_quote_check.py`, 0 not found in every changed section:
- §9.06 14; §9.08 20; §9.10 3; §9.11 10; §9.12 5;
- §9.13 10; §9.14 2; §9.15 13; §9.16 7.

The whole book has 14 NOT FOUND lines, all on the inherited baseline list (`tmp/tools/QUOTE-BASELINE.txt`); none is new.

## Models

- `tmp/tools/ce_model.py` v7, run `ce_model-run-20261020-v7.txt` (about 30 s), with checks 1–18 (17: fields, marks, tombstone; 18: merging records of several applications);
- `tmp/tools/helly_check.py` v3, run `helly_check-run-20261020-v3.txt`.

## Method and report

For each material claim:
- scope, definitions and assumptions;
- the derivation;
- the strongest counterexample, by type;
- a rival;
- the falsifier, where the claim is testable;
- labels Src / Ded / Int / Hyp / Ext.

Severity is critical, high or normal (Rulebook §6; R5). Give a proof-step table for every derivation you judge adequate (R4), and an observable "resolved when" for every finding.

**The report:** a verdict table, a findings table (ID · finding · severity · evidence · correction · resolved when) and a coverage section. Your final answer is a summary of at most 10 lines.
