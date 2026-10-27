# Consolidation S0151–S0181: brief for reviewers A (Opus) and B (Sonnet)

*Read first, and follow:*
- `03-methodology/REVIEWER-INSTRUCTIONS.md` (consolidation mode);
- `review/CONSOLIDATION-S0151-S0181/REVIEWER-NOTE.md`, which binds;
- `review/CONSOLIDATION-S0151-S0181/SCOPE.md` (the object).

*W = the book's workspace (`docs/knowledgeos/knowlegeos_manual_theory_20261018/`). The book you review is `W/06-theory/manuscript/review/CONSOLIDATION-S0151-S0181/BOOK-UNDER-REVIEW/` and nothing else.*

## What to deliver

1. **Concept verdicts** for the concepts in SCOPE:
   - CON-0261, 0264, 0265, and the restated cores of CON-0262 and 0263;
   - the Ch. 4 and Ch. 5 concepts: a second, independent verdict on each VALIDATED, a verdict on each PROVISIONAL, and the INVALID cores checked as restated.
2. **The rotated chapters, Ch. 4 and Ch. 5, in full.** Definitions, derivations, assumptions, quotations (spot-check them against the corpus sources), cross-references, and text made stale by later decisions (for example §9.13's evaluator, §9.15, §9.16, §9.17). **Count the defects you find in Ch. 4 and in Ch. 5 separately.**
3. **Coverage against the window inventories.** For each numbered item in the five `STEP1-INVENTORY.md` files named in SCOPE, mark whether the book covers it: **COVERED / MISSING / DISTORTED / JUSTIFIABLY EXCLUDED**, with the book location. An item resting on a file the access rule excludes is JUSTIFIABLY EXCLUDED (access). Use a table per inventory, grouping items where one mark covers several.
4. **The sequence check (SEQ)** of the sections in scope: a concept used before it is defined, or never defined; a stale citation.
5. **The changed theory: the designation rule.** This covers §9.17 R3 (c), §9.13.2, CON-0264 and model v18. You may run `BOOK-UNDER-REVIEW/tmp/tools/ce_model.py` read-only (about 30 s).
   - Is the decision of 2026-10-27 (decision record `7ecf5a024`, quoted in §9.17) applied as worded?
   - Is the Professor's completion (carrier in force; kernel-set store; accepted entries only; the three D clauses) needed and correctly labelled?
   - Is its scope (one serialised kernel; across applications, detected not prevented) right?
   - **T9-10:** should a carrier have one coordination point for designations? Should a bypass entry be able to take the record out of D? Give a recommendation for each, and say whether it is technical or needs the Principal.
6. **The architecture view:**
   - What did C29–C33 and turn 8 add to the primitives, the laws or the kernel/application boundary, and what is only observation of one estate?
   - Are any parts isolated, circular or unsupported?
   - For each concept: kernel or application, minimal, stable.
7. **The previous settlement's changes:** spot-check `review/C33-S0177-S0181/SETTLEMENT.md`'s rows against the copy. You may open that file.
8. **Pattern checks.** Find any instance of:
   - a rule quoted but applied beyond its own text;
   - a derivation missing a premise (a converse taken, an unstated "for all");
   - an INVALID concept cited as a home;
   - a source's self-issued status adopted as shown;
   - a summary used as evidence for what it summarizes;
   - an authority's decision read through a producer's or registrar's gloss;
   - **content from a file not on the allowed list, reached through a summary.**

## How

- **The book:** the copy only, as `REVIEWER-NOTE.md` says, with its tools (book search in the copy; R1, R2, R3 and the quote check run with `--root` on the copy). Do not open the files that `REVIEWER-NOTE.md` item 4 lists.
- **Besides the copy you may open only:**
  - the five window inventories named in SCOPE;
  - the C33 settlement;
  - corpus sources on the allowed list (check each path first);
  - `03-methodology/REVIEWER-INSTRUCTIONS.md`;
  - the tools in `W/tmp/tools/`.
- **Never:**
  - S0155, S0156, S0157, S0161, S0170, S0174;
  - Ch. 0–2 basis files;
  - `.env*`, `_batch_input*`, P3B-HOLDOUT, sealed, hold-out or quarantine files, `docs/knowledgeos/chronological-read/*`.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- No corpus or book text outside. Edit nothing. Do not reproduce any private person's name.
- **Do not read the other reviewer's report.**
- Scratch scripts only in your scratchpad with the prefix `cons_a_` / `cons_b_`, run with `python3 -I`.

## Rules for findings (the Principal)

- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Report

Deliver once, through the hand-back, beginning with "CONSOLIDATION S0151-S0181 REPORT A" (or "B"). In tables:
- **verdicts;**
- **findings:** ID A-nn / B-nn · finding · severity · type · evidence · correction · resolved when. Rate a finding that is critical only by F4, with nothing depending on it, by the rule, and add "critical by F4 only, no dependants";
- **the rotated-chapter defect count** (Ch. 4, Ch. 5);
- **the coverage tables;**
- a summary of at most 15 lines;
- **at the end, the list of every file you opened and every search you ran** (`REVIEWER-NOTE.md` item 6).
