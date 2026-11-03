# Window C105: measures, step 8, checks

*2026-11-03. Sources: S0376, S0377 (allowed list), grouped under the Principal's ruling of 2026-11-03 (a review and its reviewed layer, adjacent). New: §8.98 (C1–C6). No new concept. Consolidation gate: 25 of 25 since S0352; the S0353–S0377 consolidation is next. Changed theory since the last window review: the C104 settlement (`d92306faf`; §8.97 v2; R3 on §8.82 C3 and §8.78 C5).*

| Measure | Value |
|---|---|
| Sources | S0376 139 lines; S0377 326 |
| Depth | full, each (the Professor) |
| Professor, effort | see the phase log; the draft committed (`c811ad9a3`) before W was dispatched for step 1 |

## Step 8
- **Dependencies:** C1: CON-0127; §8.06 C1, C3; §8.97 C3; §8.27 C3; §8.76 C4; §8.75 C5. C2: §8.06 C4 (CON-0128), C6; A1. C3: §8.71 C3; §8.79 C3; §8.78 C1; §8.81 C4; A1. C4: CON-0127; §9.15 K1. C5: §8.28 C6; §8.79 C2; §8.82 C3; §8.97 C5; A1. C6: §8.78 C1, C2, C5; §8.35; §8.27 C7; S0344 (lineage).
- **Procedure:** brief committed first (`6a58f0e9f`, with the copy list and the no-globs rule), then reading and drafting, the draft committed, then W dispatched. No item from W's inventory. **Self-check applied** (the Professor's proposal of 2026-11-03): the book searched whole for the work item before drafting; homes §8.71–§8.82, §8.97.
- **Lineage checks:** S0344 (on the allowed list, read at C85): phrase counts against S0377; "Human choice 1 is the authority act." at S0344 L557.
- **S0377's header** reports acts recorded in S0378–S0379 (after the gate, not read): reported, not shown.
- **R1:** no new rows. **R2 (`undefined_terms.py --scope 08.98`):** tiers A–C: none in scope. **R3 (proposed):** none.
- **Quote check (`qc.py` against S0376, S0377, the register):** 51 checked, all matched; one line reference corrected (S0344 L558 → L557).

**At settlement (v2).**
- **R2:** W's run (`--no-corpus`): tier A 0; 40 tier D, glossed or ordinary. "appointment" in two senses (an act, a registration document): C3.
- **Quote check (`qc.py`, v2, against S0344, S0375, S0376, S0377, §8.73, §8.79, the register):** 69 checked; the misses are quotations of the withdrawn draft in §8.98.5.
- **Self-check (`selfcheck.py`) on v2:** every claim heading scoped "within the estate"; the remaining flags are homonym reminders, each split in the text.
- **R3:** §8.82 C2 (W-01), §8.78 C1 (W-02), §8.73 C1 (W-08): additive notes; W lists the citing lines (§8.78 C1, 11; §8.73 C1, 33), none changed.
- **R/N (the Principal, 2026-11-03):** R 7 (W-01, 04, 05, 06, 09, 11, 12), N 5 (W-02, 03, 07, 08, 10).
- **Observation for the map (W):** whether a guard on a documented path counts as evidence for the invariant on the practised path (§8.29 C5); recorded, not opened.
- **W's procedure:** no globs; 258 files copied by path; its tools glob internally over the scratch copy (copy-list files only); one `ls -la` on two copied scratch files. No access problem.
