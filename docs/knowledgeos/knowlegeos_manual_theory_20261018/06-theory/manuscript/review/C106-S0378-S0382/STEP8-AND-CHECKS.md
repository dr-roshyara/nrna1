# Window C106: measures, step 8, checks

*2026-11-03. Sources: S0378, S0379, S0380, S0381, S0382 (allowed list), grouped under the Principal's ruling of 2026-11-03 (up to five adjacent sources from one conversation: one work item and its amendment, one day). New: §8.100 (C1–C5), numbered under the Principal's ruling of 2026-11-03 (§8.100 onward; tools adjusted and tested first, `324693274`). No new concept. Consolidation gate: 5 of 25 since S0377. Changed theory since the last window review: the consolidation S0353–S0377 (`58f56cffc`) and the Ch. 10 consequences (`47c8c52cd`).*

| Measure | Value |
|---|---|
| Sources | S0378 75 lines; S0379 72; S0380 145; S0381 246; S0382 92 (630) |
| Depth | full, each (the Professor) |
| Professor, effort | see the phase log; the draft committed (`a8580bf7c`) before W was dispatched for step 1 |

## Step 8
- **Dependencies:** C1: §6.06 C4; §8.98 C6; §8.25 C7; §8.97 C5; §9.13.2; §9.15 G2; §9.19.2. C2: CON-0179 (§8.10 C5); CON-0196 (§8.12 C2); §8.98 C4; §8.82 C2; §8.79.3; §9.15 R3, L1a, L1b, K1. C3: CON-0151 (§8.09 C2); §8.74 C5; CON-0127; §8.98 C1. C4: §8.75 C5; §8.97 C3; §8.78 C2, C5; §8.81 C1; §8.76 C4; §8.97 C2; §9.13.2. C5: §8.98 C5; §8.29 C5.
- **Procedure:** brief committed first (`a8580bf7c`, with the copy list, the no-globs rule and the allowed list's full path), then reading and drafting, the draft committed, then W dispatched. No item from W's inventory. **Self-check applied:** the book searched for "provenance attribution", "four states", "eligible pool", "transcript", "subagent", "names a role", "freshness", "authorized for future", "holder field", "attributed authorship"; homes found and used: CON-0179, CON-0196, CON-0151, §6.06 C4, §8.76 C4, §8.74 C5. `selfcheck.py`: claim headings scoped; two plain-language flags are case false positives ("Within …" at the item's start); "identity" glossed as session identity in the lineage note.
- **R1:** no new rows. **R2 (`undefined_terms.py --no-corpus --scope 08.100`):** tiers A–C: none in scope; tier D ordinary or the sources' own terms (candidate-declaration, status-supersession, renderer). **R3 (proposed):** none.
- **Quote check (`corpus_quote_check.py`):** 32 found, 0 not found (one line range corrected before commit: S0381 L174 → L174–175).

**At settlement (v2).**
- **Quote check (`corpus_quote_check.py`, v2):** 36 found, 0 not found (one quotation split around an inline code span). §8.82, §8.98 R3 notes carry no bracketed quotation.
- **Self-check (`selfcheck.py`) on v2:** claim headings scoped; remaining flags are homonym reminders ("identity", glossed in the lineage note) and case false positives.
- **R1:** 0 flags. **R3:** §8.82 C2 (W-05), §8.98 C4 (W-05), §8.98 C6 (W-16): additive notes.
- **R/N:** R 9 (W-01, 02, 04, 07, 08, 10, 11, 15, 16), N 7 (W-03, 05, 06, 09, 12, 13, 14).
- **W's procedure:** copy by path from the copy list; no globs; `--help` runs of two tools on the scratch copy failed for missing files and were not used. No access problem.
