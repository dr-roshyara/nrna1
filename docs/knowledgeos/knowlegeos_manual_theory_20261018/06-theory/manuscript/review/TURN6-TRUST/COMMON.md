# Theory turn 6: rules common to commissions A, B and C

*2026-10-20. By the Professor. The turn-6 brief became too long for one commission, so it is split in three, as the Principal allowed. All three are settled before turn 7.*
- **A:** §9.13 v4, in full;
- **B:** §9.14 v2, in full;
- **C:** §9.15, the trust boundary (new).

**The Principal's instruction (step 9, not narrowed):** "the turn-6 brief lists all changes of the turn-5 settlement as changed theory: §9.13 v4 and §9.14 v2 in full, including the new row and its proof. Not 'where used'."

## Access (binding)

- **Allowed:** the working book, register, map, outline, review folders, tools and git history of W. The decision record is read only.
- **Corpus:** only files listed in `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`, with sha256 matching.
- **No Ch. 0–2 basis files** (purpose "basis:" in `archive/00-access/READ-LOG.tsv`). A check that needs one is marked U.
  - The book's own chapter files (`06-theory/manuscript/chapter_0*/`) are **not** basis files: read them freely.
  - The basis files are the source documents listed in that log, such as GKC, RS, RM and SD.
- **Never:** `.env*`, `_batch_input*`, P3B-HOLDOUT, sealed or quarantine files, or `docs/knowledgeos/chronological-read/*`. No corpus or book text in any search. Edit nothing except your report.
- **Scratch:** scripts outside the repository, in the session scratchpad, run with `python3 -I`.

## Challenge by type (practice 5)

- **Mathematical results:** by a countermodel or an invalid step.
- **Definitions:** by boundary cases, coherence, consequences and a rival.
- **The Principal's decisions:** by the decision maker's words and the consequences, not by a falsifier.

## Verification marks (practice 6)

All the changed text was written by the Professor, read by the Professor, and its reasoning checked by the Professor. No helper session wrote any part. Reviewers of the turn-5 commissions found the defects that versions §9.13 v4 and §9.14 v2 correct; those corrections are **not yet re-reviewed**.

## Quote check (entry 60)

`corpus_quote_check.py`:
- §9.13: 10 found, 0 not found;
- §9.14: 2 found, 0 not found;
- §9.15: 14 found, 0 not found;
- §9.07–§9.09: unchanged against the baseline.

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
