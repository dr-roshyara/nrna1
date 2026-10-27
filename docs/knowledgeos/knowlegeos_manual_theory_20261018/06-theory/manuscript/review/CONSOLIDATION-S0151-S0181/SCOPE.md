# Consolidation S0151–S0181: scope (fixed before the reviewers start)

*2026-10-27. The consolidation due after 25 allowed sources in index order since S0150 (Rulebook v1.4 §12; the tool's gate). Windows C29–C33. The Principal's instruction of 2026-10-25: rotate Ch. 4 and Ch. 5 in full; coverage against the saved window inventories; two reviewers on different models; then the short integration review with R3 lists; the entry-58 record; **no planted-gap test**. Access answer: `review/C33-S0177-S0181/ACCESS-NOTE.md`.*

**The object** is the frozen copy `BOOK-UNDER-REVIEW/` (made by the Professor; no planted gaps), and only it. Files and hashes: `frozen-table.txt`. **The working book is frozen** from the commit that adds this file until the settlement.

## Sections in scope (in the copy)

- **Written in C29–C33:** `chapter_08/08.22-…` to `08.26-…`, and their Ch. 8 index rows.
- **Changed since the S0150 consolidation (changed theory):**
  - `chapter_09/09.17-…` (turn 8; the decision of 2026-10-25; the ruling `7ecf5a024` and its completion);
  - `chapter_09/09.13-…` §9.13.2 (kind designate);
  - `chapter_09/09.15-…` (K1, K2, L11 cells);
  - TODO T9-08, T9-09, T9-10, T8-04;
  - the model `tmp/tools/ce_model.py` v18 with its run `tmp/tools/ce_model-run-20261027-v18.txt`.
- **Rotated, in full:**
  - Ch. 4: `chapter_04/04.00`–`04.03`, `04.99`, `TODO`;
  - Ch. 5: `chapter_05/05.00`–`05.07`, `05.99`, `TODO`.
- **Also:**
  - `BOOK-OUTLINE.md` (the rotation section);
  - `THEORY-MAP.md` (position, counts, the lines for C29–C33 and turn 8).

## Concepts

- **Without an independent consolidation verdict:**
  - CON-0261, CON-0264, CON-0265 (PROVISIONAL);
  - the restated cores of CON-0262 and CON-0263 (INVALID as stated; do not re-litigate).
- **Homed in the rotated chapters:**
  - **Ch. 4 (6):** CON-0068, 0069, 0070, 0071, 0107 (VALIDATED), 0113 (INVALID).
  - **Ch. 5 (22):**
    - VALIDATED (4): 0048, 0049, 0077, 0131;
    - PROVISIONAL: 0079, 0080, 0101, 0123, 0165, 0168, 0174, 0175, 0187;
    - INVALID: 0050, 0052, 0053, 0078, 0081, 0109, 0124, 0144, 0145.
  - A verdict is asked for each VALIDATED (a second, independent one) and each PROVISIONAL. The INVALID cores are checked as restated.

## Sources

- **In index order, on the allowed list:**
  - S0151–S0154, S0158–S0160, S0162–S0169, S0171–S0173, S0175–S0181;
  - not on the list: S0155, S0156, S0157, S0161, S0170, S0174.
  - Paths are in `02-FILES.jsonl`; the allowed list is `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`.
- **Coverage basis, the blind window inventories:**
  - `review/C29-S0151-S0158/STEP1-INVENTORY.md`
  - `review/C30-S0159-S0164/STEP1-INVENTORY.md`
  - `review/C31-S0165-S0169/STEP1-INVENTORY.md`
  - `review/C32-S0171-S0176/STEP1-INVENTORY.md`
  - `review/C33-S0177-S0181/STEP1-INVENTORY.md`
- **Access (binding):**
  - S0155 is exposed; nothing from it is ever used.
  - S0156, S0157, S0161, S0170 and S0174 are exposed through summaries (the Professor's reading of the Principal's answer).
  - Never open any of these files. **Do not rely on anything the allowed summaries (S0175–S0182) or the inventories say about them.** Where an inventory item rests on one of them, mark it JUSTIFIABLY EXCLUDED (access).

## R3 (Rulebook §12d), run on the copy before this review

- `inbound_citations.py --root BOOK-UNDER-REVIEW` on the INVALID-as-stated concepts in scope (CON-0262, 0263, 0113, 0050, 0052, 0053, 0078, 0081, 0109, 0124, 0144, 0145) gives 64 lines, saved in `R3-OUTPUT.txt`.
- The transitive list is not run (uninformative at S0125). The reviewers judge implicit dependencies.
- *Limit:* explicit citations only.

## No new blind step (the Professor's choice, for review, as at S0125 and S0150)

Every source in scope was inventoried blind in its window. Ch. 4 and Ch. 5 rest partly on Ch. 0–2 basis files, which reviewers may not read.
