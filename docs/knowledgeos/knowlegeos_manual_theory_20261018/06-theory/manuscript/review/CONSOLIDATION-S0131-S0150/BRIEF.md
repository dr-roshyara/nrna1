# Consolidation S0131–S0150: brief for reviewers A (Opus) and B (Sonnet)

*Read first, and follow:*
- `03-methodology/REVIEWER-INSTRUCTIONS.md` (consolidation mode);
- `review/CONSOLIDATION-S0131-S0150/REVIEWER-NOTE.md`, which binds;
- `review/CONSOLIDATION-S0131-S0150/SCOPE.md` (the object).

*W = the book's workspace. The book you review is `W/06-theory/manuscript/review/CONSOLIDATION-S0131-S0150/BOOK-UNDER-REVIEW/` and nothing else.*

## What to deliver

1. **Concept verdicts** for the concepts in SCOPE:
   - CON-0238–0256, with the INVALID cores checked as restated;
   - the Ch. 2 and Ch. 3 concepts: a second, independent verdict on each VALIDATED, a verdict on each PROVISIONAL, and the INVALID cores checked.
2. **The rotated chapters, Ch. 2 and Ch. 3, in full.** Definitions, derivations, assumptions, quotations (spot-check them against the corpus sources), cross-references, and text made stale by later decisions (for example §9.13's evaluator, §9.15, §9.16). **Count the defects you find in Ch. 2 and in Ch. 3 separately.**
3. **Coverage against the window inventories.** For each numbered item in the four `STEP1-INVENTORY.md` files named in SCOPE, mark whether the book covers it: **COVERED / MISSING / DISTORTED / JUSTIFIABLY EXCLUDED**, with the book location. A table per inventory, items grouped where one mark covers several.
4. **The sequence check (SEQ)** of the sections in scope: a concept used before it is defined, or never defined; a stale citation.
5. **The architecture view:**
   - What did C25–C28 add to the primitives, the laws or the kernel/application boundary, and what is only observation of one estate?
   - Are any parts isolated, circular or unsupported?
   - For each concept: kernel or application, minimal, stable.
6. **The previous settlement's changes** (`review/C28-S0146-S0150/SETTLEMENT.md`, which is also one of the files you may open): spot-check its rows against the book.
7. **Pattern checks.** Find any instance of:
   - a rule quoted but applied beyond its own text;
   - a derivation missing a premise (a converse taken, an unstated "for all");
   - an INVALID concept cited as a home;
   - a source's self-issued status adopted as shown;
   - a measurement carried forward unexamined;
   - an authority's decision read through a producer's or registrar's gloss.

## How

- **The book:** the copy only, as `REVIEWER-NOTE.md` says, with its tools (book search in the copy; R1, R2, R3 and the quote check run with `--root` on the copy). Do not open the files that `REVIEWER-NOTE.md` item 4 lists.
- **Besides the copy you may open only:**
  - the four window inventories named in SCOPE;
  - the C28 settlement;
  - corpus sources on the allowed list;
  - `03-methodology/REVIEWER-INSTRUCTIONS.md`;
  - the tools in `W/tmp/tools/`.
- **Never:**
  - Ch. 0–2 basis files (purpose "basis:" in `W/archive/00-access/READ-LOG.tsv`; you need not open that log);
  - `.env*`, `_batch_input*`, P3B-HOLDOUT, sealed, hold-out or quarantine files, `docs/knowledgeos/chronological-read/*`.
- No corpus or book text outside. Edit nothing.
- **Do not read the other reviewer's report.**
- Scratch scripts only in your scratchpad with the prefix `cons_a_` / `cons_b_`, run with `python3 -I`.

## Report

Deliver once, through the hand-back, beginning with "CONSOLIDATION S0131-S0150 REPORT A" (or "B"). In tables:
- **verdicts;**
- **findings:** ID A-nn / B-nn · finding · severity · type · evidence · correction · resolved when. Rate a finding that is critical only by F4 (the book marks the premise undefined), with nothing depending on it, by the rule, and add "critical by F4 only, no dependants";
- **the rotated-chapter defect count** (Ch. 2, Ch. 3);
- **the coverage tables;**
- a summary of at most 15 lines;
- **at the end, the list of every file you opened and every search you ran** (`REVIEWER-NOTE.md` item 6).
