# Consolidation S0101–S0130: scope (fixed before the reviewers start)

*2026-10-23. The consolidation due at S0125 (Rulebook v1.3 §12, entries 57–58), held after window C24 (the Principal, 2026-10-23). It covers the windows read since the S0100 review: C19–C24, S0101–S0130.*

**The Principal's arrangement:**
- two reviewers on different models (A = Opus, B = Sonnet), independently; neither sees the other's report;
- plus the rotated chapters in full: **Ch. 0 and Ch. 1** (BOOK-OUTLINE.md, "Rotation of older chapters");
- then a short cross-chapter integration review with the R3 lists;
- entry 58: the settlement records the harness time and the defects found in the rotated chapters;
- **no planted-gap test** (next at S0150, prepared by the Rule Reviewer).

**Frozen** at the commit that adds this file (see the git log). Nothing in scope is edited until both reports are in. Files and hashes: `frozen-table.md`.

## Sections in scope

- **Written in the windows C19–C24:** §8.12, §8.13, §8.14, §8.15, §8.16, §8.17.
- **Changed since the S0100 review and reviewed only in parts:** §9.13 (v10–v14 notes: owner, transfer, single use, the work act as the use), §9.15 (v7–v10: K4-k/K4-o, L10, G4), §9.16 (R4a–R4d), §9.8.6 item 7. The earlier versions were reviewed in turn 7 and in C19's commission T; review here **what changed after C19**.
- **Rotated, in full:** Ch. 0 (`chapter_00/00.00`–`00.09`), Ch. 1 (`chapter_01/01.00`–`01.17`).
- **Also:** `BOOK-OUTLINE.md` (with the new rotation section); `THEORY-MAP.md` §9 (Position) and rows F22, F24; `CONCEPT-REGISTER.tsv` rows CON-0184–CON-0237.

## Concepts (54: CON-0184 … CON-0237; 6 VALIDATED, 39 PROVISIONAL, 9 INVALID)

- **VALIDATED by one transition review only (6)**, which need a second, independent verdict: CON-0209, CON-0213, CON-0219, CON-0220, CON-0226, CON-0232.
- **PROVISIONAL (39):** a verdict is asked for each.
  - 0184–0196, 0199–0204, 0206, 0207, 0211, 0212, 0214–0217, 0221–0225, 0228–0231, 0233, 0236, 0237.
  - CON-0184–0190 belong to Ch. 3, Ch. 1, Ch. 5 and Ch. 9 and were settled at S0100 or in turns. Give a verdict where the material is in scope (CON-0186, Ch. 1); otherwise mark "not reviewed here".
- **INVALID (9):** 0197, 0198, 0205, 0208, 0210, 0218, 0227, 0234, 0235. Check their valid cores, as restated, against the book. Do not re-litigate them.

## Sources

- **In index order:** S0101–S0130, read in the windows C19–C24. S0123 and S0125 were skimmed, and S0125's skim was found to have lost theory.
- **Topic-full:** S0071, S0137, S0183 (F22).
- Paths are in `02-FILES.jsonl` and the access record.
- The blind inventories of each window are saved in `review/C19-…` to `review/C24-…/STEP1-INVENTORY.md`.

## R3 (Rulebook §12d), run before this review

- **Direct dependents of the INVALID concepts:** only their restatements and home sections.
  - CON-0197 → CON-0200; CON-0198 → CON-0201; CON-0205 → CON-0207; CON-0208 → CON-0213; CON-0210 → CON-0214; CON-0218 → CON-0224; CON-0227 → CON-0230; CON-0234 → CON-0237; CON-0235 → CON-0236.
  - Homes: §8.12–§8.17.
- **The transitive list through sections** reaches 220 items per concept, since every section cites others. Too wide to be informative; the reviewers judge implicit dependencies.
- *Limit:* the graph sees explicit references only.

## No blind step (the Professor's choice, for review)

- Every source in scope was inventoried blind in its window, by an independent reviewer, and those inventories are saved.
- The rotated chapters rest on Ch. 0–2 basis files, which reviewers may not read.
- So this consolidation has no new blind inventory. The reviewers judge the book against the sources and the saved inventories.
