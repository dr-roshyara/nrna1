# Window C69: measures, step 8, checks

*2026-11-01. Sources: S0303, S0304, S0305, all on the allowed list. New: §8.62 (C1–C5). No new concept. Consolidation gate: 3 of 25 since S0302. Changed theory since the last window review: the consolidation S0278–S0302 stage-2 edits (`e3ac4df54`), the names rule (`504115b61`, `90a678e93`), §9.19 v2 and its R3 notes in §9.10 and §9.18, the U1–U6 rulings.*

## Measures

| Measure | Value |
|---|---|
| Sources, lines (`wc -l`) | S0303 1811 (24,474 bytes); S0304 132; S0305 98 |
| Depth | full, all three (the Professor and W) |
| Professor, effort log (the Principal's instruction 2, 2026-11-01: four categories, waiting apart) | reading 17:34:25Z–17:35:12Z; drafting from 17:35:12Z. Phase boundaries are logged at tool calls; reading time before the first call of a phase counts to the previous phase |
| Reviewer W, step 1 | 103.3 s (harness), 9 tool uses |

## Step 8

- **Dependencies:** C1: §8.57 C1; §8.55 C3–C4; §8.45 C1, L25; §8.44 C1; §8.61 C4; §8.33. C2: §8.41 C1–C2; §8.50 C3–C4; §8.57 C4; §8.47 C1. C3: §8.61 C5 (W-08); S0300 L1398; S0302 L82, L86, L97; §8.57 C4. C4: §5.4 C2, C9; T5-08; §8.39 C1; §8.51 C1; §1.11; §8.50 C4. C5: §8.44 C4; §8.39 C3; T9-18; T9-21; §9.13 L52; §9.19 U6; §9.10; S0916 L87; §8.56 C1; §8.55 C4, §8.55.3; §8.54 C3; §8.57 C2; §8.45 L64; §9.4.
- **A line comparison (C1):** S0303 L656–1292 against S0295 L1–640, `difflib.SequenceMatcher` on lines: ratio 0.9977; the only non-equal block is S0295 L638–640. *(Corrected at settlement, W-17: L640 is the next answer's first line, not part of the question; over the exact span S0295 L1–637 the ratio is 1.0, W's recomputation.)*
- **Earlier sources re-opened for checking (already read in order):** S0290 (L3, L4, L11, L47, L85), S0295 (the comparison), S0301 (L43, L95), S0302 (L82, L86, L97).
- **Taken from W's inventory into the draft before step 2 (disclosed in §8.62.5):** the context/identity tension, "Invariant + Judgment", the case-role drift, S0304's two deadlines, the bar beside "no Phase 5 start", the wisdom equation as a list.
- **R1:** no new register rows; 241 homes, 1 flag (CON-0133, pre-existing).
- **R2 (§8.62):** tier A 0. Tier D candidates in §8.62: ordinary words (reproduce, addressed, framing, recommended, beyond, beside, quoted, agree, keep, drift, expression, wisdom, registration, adviser, discharged), "parser" (S0303's term, quoted), "waiver" (glossed in C3 (b)), "neutral-origin" (Z1, §8.45 L64). No TODO row.
- **R3 (proposed for settlement):** §8.55 C4 (S0290 L47 is the recorder's sentence, C1); §8.61 C5 W-08 (the act now exists and does not answer it, C3); §8.57 lineage (S0295 L1–637 also in S0303); T5-08 (one instance of criticality set by an act, C4).
- **Quote check:** `corpus_quote_check.py` finds no bracket-form quote in §8.62 (0/0); the line-cited quotes were checked by hand against the sources.
