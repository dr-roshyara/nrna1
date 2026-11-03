# Window C100: measures, step 8, checks

*2026-11-03 (window opened 2026-11-02). Source: S0369 (allowed list). New: §8.93 (C1–C6). No new concept. Consolidation gate: 17 of 25 since S0352. Changed theory since the last window review: the C99 settlement (`1e4a4529f`; §8.92 v2).*

| Measure | Value |
|---|---|
| Sources | S0369 2506 lines |
| Depth | full (the Professor) |
| Professor, effort | see the phase log; the draft committed (`88c89abb0`) before W was dispatched for step 1 |

## Step 8
- **Dependencies:** C1: §8.90 C1; A1; §9.15 G2; §1.6 L48–50; §9.18 L111; T9-12 (c); C1 = constitute; §8.89 C4. C2: §8.92 C4; §9.13 L52, L129; §8.91 C6; §9.16 L93; T9-14. C3: C1 = constitute; §9.20 Th3; U2; §8.92 C1; §8.89.3 (iv); §9.18.6 (c2); §8.88 C2 (i). C4: §8.91 C2; §8.88 C2, C5; CON-0211; §9.18 L30; §9.13 L129. C5: §8.91 C1, C4; §8.89.3; §9.18 L111, L112; U3 (b). C6: §9.15 G1; §8.87 C2, C4; §8.91 C6; CON-0221; §9.18.2.
- **Procedure:** brief committed first (`8db4a221d`, with the copy list), then reading and drafting, the draft committed, then W dispatched. No item from W's inventory.
- **Lineage checks:** S0366 L941–965 (the twelve questions; allowed, already read); S0365 L1777.
- **Names:** "[IT]" for the incompleteness theorem's author, a lens name; provider and other lens names as the source gives them.
- **R1:** no new rows.
- **R2 (`undefined_terms.py --scope 08.93`):** tier C "determination", "contestation": the source's words. No tier A or B in scope.
- **R3 (proposed):** none.
- **Quote check (`qc.py` against S0369 and S0365 with "[IT]" substituted, S0366, §9.15, §9.16, §9.18):** 104 checked; every quote matched after five spans of the Professor's wording were unquoted, before the draft's commit.

**At settlement (v2).**
- **R2:** W's run (`--no-corpus`): no tier A or B; "determination" and "contestation" tier D there, tier C in the Professor's run with the corpus (corpus tiering). W's judgements adopted: "admit" qualified as "admit (a claim)" / "admitted (to D)"; "determination" split in C3; "material" refers to §8.91 C4.
- **Quote check (`qc.py`, v2, against S0369 and S0365 with "[IT]" substituted, §9.4, §9.15, §9.16, §9.18, the register):** 125 checked; the misses are quotations of the withdrawn draft in §8.93.5 and the "(W, step 1)" marker.
- **R3:** none (`inbound_citations.py`: no inbound citations of §8.93 C1–C6 beyond the index row; W).
- **Fork candidate for the map (W-13):** content mutability (amending a claim against a new item); not opened.
- **W's procedure:** copies by path from the list (252 files); tools on the scratch copy; `--help` on two scratch tools (no repository file read). No access problem.
