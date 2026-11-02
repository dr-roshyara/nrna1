# Window C79: measures, step 8, checks

*2026-11-02. Sources: S0325, S0326, S0327 (allowed list). New: §8.72 (C1–C5). No new concept. Consolidation gate: 25 of 25 since S0302 (the consolidation S0303–S0327 follows). Changed theory since the last window review: the C78 settlement (`912afc95a`; §8.71 v2).*

| Measure | Value |
|---|---|
| Sources | S0325 123 lines (11,488 bytes); S0326 110 (8,622); S0327 125 (13,486) |
| Depth | full, all three (the Professor and W) |
| Professor, effort (phase.py; waiting apart) | reading 03:04:42Z–03:05:45Z (three files read whole in parallel); drafting from 03:05:45Z |

## Step 8

- **Dependencies:**
  - C1: §8.71.3, §8.71 lineage; §1.13.2 (H1′); §8.50 C3.
  - C2: §8.71 C5; §8.67 C2; §8.67.3 (L118).
  - C3: §8.71 C3–C4; §8.69 C4; §8.06 C1; §8.28 C2; INV-ATTR.
  - C4: §9.17 R1 (K2-d); §8.69 C5; §8.67.3 (S0315 L168); §8.66 C5.
  - C5: A1; §1.12; §8.27 C1; §8.28 C3; §8.66 C4; §8.06 C1; §8.71 C1; §8.67 C1, C5; §9.19 T7.
- **Taken from W's inventory before step 2:** listed in §8.72.5.
- **R1:** no new rows.
- **R2 (§8.72):** `undefined_terms.py --scope 08.72`: no tier A, B or C flag names §8.72.
- **R3 proposed:**
  - **§8.71.3 (V-numbers) and §8.71 lineage:** decidable from allowed text (S0325 L26, L73; S0326 L55): S0324 as read contains correction text in §5, §9, §11. W-01's git evidence stays unused.
  - **§8.71 C5 (an act read from a state):** the premise "START is the only transition into ACTIVE" is false (S0325 L40: START/CONTINUATION); the reading is sound under "every path into ACTIVE passes through a START", not stated in the window.
- **Quote check:** a script compared the quoted spans with S0325–S0327, S0324 and S0320; every source quote matched.
