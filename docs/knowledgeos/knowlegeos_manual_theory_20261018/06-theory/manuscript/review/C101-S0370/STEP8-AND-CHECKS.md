# Window C101: measures, step 8, checks

*2026-11-03. Source: S0370 (allowed list), alone under the Principal's grouping ruling of 2026-11-03 (no adjacent sibling). New: §8.94 (C1–C6). No new concept. Consolidation gate: 18 of 25 since S0352. Changed theory since the last window review: the C100 settlement (`c61f8c12b`; §8.93 v2).*

| Measure | Value |
|---|---|
| Sources | S0370 1233 lines |
| Depth | full (the Professor) |
| Professor, effort | see the phase log; the draft committed (`9dab0bde4`) before W was dispatched for step 1 |

## Step 8
- **Dependencies:** C1: §9.20 Th3; §8.92 C1; §8.91 C1; §9.16 L93; §9.18 L111; §8.87 C4. C2: A1; §9.15 G2; §8.87 C2, C4; §8.92 C1; §8.93 C6. C3: §9.20 Th1, Th3, L155; §8.91 C1; §9.15 G3, K1; §8.90 C2. C4: §8.92 C4, C5; §8.93 C2, C5; §9.18 L111; §9.20 Th3; U3 (b); §8.88 C2 (iii); T9-14. C5: §9.20.8 L153; §9.15 G1; §8.91 C6; §8.92 C4; §9.18.2; §8.86 C5. C6: §8.91 C5; §9.20.8 L153; §8.92 C6; §9.15 K1, K5–K7; §8.89 C4; §8.91 C4; §9.13 L46–56; §8.88 C3; §9.15 G2.
- **Procedure:** brief committed first (`8d24383b6`, with the copy list), then reading and drafting, the draft committed, then W dispatched. No item from W's inventory.
- **Lineage check:** S0370 L7–104 against S0368 L961–1079: identical text, list numbering and markup aside (diff).
- **Names:** "[IT]" for the incompleteness theorem's author, a lens name; provider and other lens names as the source gives them.
- **R1:** no new rows.
- **R2 (`undefined_terms.py --scope 08.94`):** tier A "hundredth": ordinary English. No tier B or C in scope.
- **R3 (proposed):** none.
- **Quote check (`qc.py` against S0370 with "[IT]" substituted, §9.20):** 84 checked; every quote matched after two spans of the Professor's wording were unquoted, before the draft's commit.

**At settlement (v2).**
- **R2:** W's run (`--no-corpus`): no tier A in scope; 28 tier D judged. "hundredth" (tier A in the Professor's run with the corpus) is ordinary English; the difference is corpus tiering.
- **Quote check (`qc.py`, v2, against S0370, S0368 and S0369 with "[IT]" substituted, §9.13, §9.15, §9.20):** 106 checked; the misses are quotations of the withdrawn draft in §8.94.5 and the "(W, step 1)" marker.
- **R3:** none for §8.94 (no inbound citations beyond the index row; W). **Book item for the consolidation (W-10):** the pointer "no justification concept (§8.87 C4)" in §8.90 L44, §8.91 L62, L109, §8.92 L34, L35, L93 is stale (§8.87 C4 states only the absence of a confidence concept); the ground is the register (§8.89 C2). Fixed in §8.94 only.
- **Lineage (W-18):** S0370 L1–104 = S0368 L955–1079 (W's normalised comparison; the Professor's diff of L7–104 against L961–1079 agreed).
- **W's procedure:** copies by path from the list (251 files); tools on the scratch copy; `--help` on four scratch tools; one run failed for want of `02-FILES.jsonl` (not on the list) and was not used. No access problem.
