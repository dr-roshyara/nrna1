# Consolidation S0131–S0150: scope (fixed before the reviewers start)

*2026-10-24. The consolidation due at S0150 (Rulebook v1.3 §12, entries 57–60), after window C28. It covers the windows read since the S0101–S0130 consolidation: C25–C28, S0131–S0150. Plan approved by the Principal (`PLAN.md`, 2026-10-24).*

**The object** is the frozen copy `BOOK-UNDER-REVIEW/` (prepared by the Rule Reviewer), and only it. Files and hashes: `frozen-table.md`. **The working book is frozen** from the commit that adds this file until the Rule Reviewer has scored.

**The arrangement:**
- two reviewers on different models (A = Opus, B = Sonnet), independently; neither sees the other's report;
- the rotated chapters **Ch. 2 and Ch. 3** in full;
- then a short integration review (Opus) with the R3 lists;
- **coverage basis** (the Principal): the saved window inventories, each item marked COVERED / MISSING / DISTORTED / JUSTIFIABLY EXCLUDED against the copy;
- entry 58: harness time of the consolidation and of its settlement (two stages), and the defects in the rotated chapters.

## Sections in scope (in the copy)

- **Written in C25–C28:** `chapter_08/08.18-…`, `08.19-…`, `08.20-…`, `08.21-…`, and the Ch. 8 index rows for them.
- **Rotated, in full:**
  - Ch. 2: `chapter_02/02.00-index`, `02.00a`, `02.01`–`02.11`, `02.99`, `TODO`;
  - Ch. 3: `chapter_03/03.00-index`, `03.01`–`03.10`, `03.99`, `TODO`.
- **Also:** `BOOK-OUTLINE.md` (the rotation section and the Ch. 8 marks table); `THEORY-MAP.md` §9 (position, counts) and the rows touched by C25–C28 (R_K "What the kernel manages"); the register rows below.

## Concepts

- **From the windows, without a consolidation verdict (19): CON-0238–CON-0256.**
  - **PROVISIONAL (15):** 0238, 0239, 0240, 0241, 0242, 0243, 0244, 0245, 0248, 0249, 0250, 0251, 0253, 0254, 0256. A verdict is asked for each.
  - **INVALID as stated (4):** 0246 (restated as 0248), 0247 (→ 0249), 0252 (→ 0254), 0255 (→ 0256). Check the restated cores against the book; do not re-litigate the INVALID verdicts.
- **Homed in the rotated chapters:**
  - **Ch. 2 (9):** CON-0034, 0040, 0055, 0056, 0061, 0062, 0063, 0082, 0083 (2 VALIDATED, 2 PROVISIONAL, 5 INVALID).
  - **Ch. 3 (44):** 5 VALIDATED, 12 PROVISIONAL, 27 INVALID. Rows whose book location is in `chapter_03/` or Ch. 3 in the copy's register, including the rows marked "not reviewed here" at the S0101–S0130 consolidation.
  - A verdict is asked for each VALIDATED (a second, independent one) and each PROVISIONAL. The INVALID cores are checked as restated.

## Sources

- **In index order:** S0131–S0150, read in C25–C28.
  - Skimmed: S0147 (a diagram) and S0149. S0149's skim was found to have lost theory at C28's review; it is repaired in §8.21 C13.
  - Paths are in `02-FILES.jsonl`. Allowed list: `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`.
- **Coverage basis, the blind window inventories (the Principal's addition):**
  - `review/C25-S0131-S0135/STEP1-INVENTORY.md`
  - `review/C26-S0136-S0140/STEP1-INVENTORY.md`
  - `review/C27-S0141-S0145/STEP1-INVENTORY.md`
  - `review/C28-S0146-S0150/STEP1-INVENTORY.md`

  These four files are named here and are the only files outside the copy that reviewers may open, besides corpus sources and the tools.

## R3 (Rulebook §12d), run on the copy before this review

- `inbound_citations.py --root BOOK-UNDER-REVIEW CON-0246 CON-0247 CON-0252 CON-0255` gives 49 lines, saved in `R3-OUTPUT.txt`.
- **Direct dependents of the four INVALID-as-stated concepts:** their restatements (CON-0248, 0249, 0254, 0256), their home sections (§8.19, §8.20, §8.21) and the Ch. 8 index rows. No other chapter cites them.
- The transitive list through sections is not run: at S0125 it reached 220 items per concept and was uninformative. The reviewers judge implicit dependencies.
- *Limit:* explicit citations only.

## No new blind step (the Professor's choice, for review)

Every source in scope was inventoried blind in its window, and those inventories are the coverage basis. Ch. 2 rests on Ch. 0–2 basis files, which reviewers may not read.
