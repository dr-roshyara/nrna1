# Consolidation S0131–S0150: integration commission (Opus)

*2026-10-24. A short cross-chapter integration review, after two independent reviews. Read and follow `REVIEWER-NOTE.md` (binding), `SCOPE.md` and `03-methodology/REVIEWER-INSTRUCTIONS.md`. The book is the copy `BOOK-UNDER-REVIEW/` and nothing else.*

## Inputs

| Input | File | sha256 at receipt |
|---|---|---|
| Report A (Opus) | `REPORT-A.md` | `e97c331c22de51f0e631fb84675434cbe082b6306dcba58189fb38c9bc110bed` |
| Report B (Sonnet) | `REPORT-B.md` | `150473b7d7693e22a84431dba8ac623385a148c580375d3f3c00f058c02ce405` |
| R3 lists | `R3-OUTPUT.txt` (and `SCOPE.md` §R3) | — |

## Tasks

1. **Classify every finding** of A and B: found by **both** (name the pair), **A only**, **B only**, or the two **disagree** (state the disagreement).
2. **Check each one-only finding against the copy** (and against the cited corpus lines where needed). Mark it **holds**, **holds in part** or **does not hold**, with the evidence.
3. **Concept verdicts.** Where A and B differ, give your verdict with the reason:
   - CON-0239, 0240, 0242, 0245, 0253 (A: VALIDATED; B: PROVISIONAL);
   - CON-0256 (A: INVALID as stated; B: PROVISIONAL, wording);
   - CON-0063 (A: VALIDATED, rebased; B: PROVISIONAL);
   - CON-0038 (A: INVALID as stated; B: PROVISIONAL, circular);
   - CON-0010 (both: stale reason; B: PROVISIONAL);
   - CON-0007 (B: PROVISIONAL);
   - any other.
4. **For each proposed correction, its cross-chapter consequences.** Run R3 on the copy (`inbound_citations.py --root BOOK-UNDER-REVIEW …`), and name the sections and register rows a correction would touch. Name any correction that conflicts with another.
5. **The rotated chapters:**
   - the union of A's and B's Ch. 2 and Ch. 3 defects, per chapter: A only, B only, both, union;
   - an overlap estimate, labelled indicative only, since the reviewers are not independent;
   - **any defect in Ch. 2 or Ch. 3 that both missed and you find while checking**, counted separately.
6. **The coverage tables:** where A and B mark the same inventory item differently, which is right.
7. **Process notes.** Each reviewer's file list: does any entry reach a file `REVIEWER-NOTE.md` item 4 forbids? Record B's own disclosed deviations (`frozen-table.md`, `02-FILES.jsonl`, a folder listing).

## How

- **Open only:** the copy; the two reports; the R3 output; the four window inventories and the C28 settlement named in SCOPE; corpus sources on the allowed list; the tools in `W/tmp/tools/`.
- **Never:** REVIEWER-NOTE item 4's files; basis files; sealed, hold-out or quarantine files; `.env*`; `_batch_input*`; `chronological-read/*`.
- No corpus or book text outside. Edit nothing.
- Scratch scripts only in your scratchpad with the prefix `cons_i_`, run with `python3 -I`.

## Report

Deliver once, through the hand-back, beginning "CONSOLIDATION S0131-S0150 INTEGRATION REPORT". In tables:
- the classification;
- the one-only checks;
- the verdicts;
- consequences and conflicts;
- the rotated-chapter union and estimate;
- the coverage disagreements;
- process notes;
- a summary of at most 12 lines;
- at the end, **every file you opened and every search you ran**.
