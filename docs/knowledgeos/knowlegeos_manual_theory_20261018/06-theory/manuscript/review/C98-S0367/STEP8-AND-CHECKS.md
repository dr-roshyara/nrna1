# Window C98: measures, step 8, checks

*2026-11-02. Source: S0367 (allowed list). New: §8.91 (C1–C6). No new concept. Consolidation gate: 15 of 25 since S0352. Changed theory since the last window review: the C97 settlement (`9c2809ffc`; §8.90 v2); the T9-02 turn (§9.20 v2, `2b204b003`); the Principal's ruling retyping T9-02's deliverable (`2620c585d`).*

| Measure | Value |
|---|---|
| Sources | S0367 1581 lines |
| Depth | full (the Professor) |
| Professor, effort | see the phase log; the draft committed (`9de39ee11`) before W was dispatched for step 1 |

## Step 8
- **Dependencies:** C1: §8.89 C2; §9.20 Th1, Th3, L155; §9.18.2; §9.13 L46–56, L129; K6; U3 (b). C2: §8.88 C2, C5; A1; C1 = constitute; §9.15 G3, K1; §9.16 R2; §9.18.4, §9.18.7; §8.90 C2; CON-0175; CON-0211. C3: §9.20 Th2, §9.20.7; the Principal's ruling of 2026-11-02; §8.90.3; T9-14; §8.87 C4. C4: §9.18 L111, G7, §9.18.2, §9.18.6 (c2), §9.18.7; T9-12 (c); T9-19; §9.16 R2; §8.86 C5; §8.88 C2 (i); §8.89 C4. C5: §8.51 C1; §8.89 C4; §8.88 C3; §9.13 L46–56, L81. C6: §9.13 L129; §9.10 Q1, Q2; CON-0221; §9.18.5; §1.6 L51; §9.18 L30; §8.88 C5; §8.65 C3; §8.89 C5.
- **Procedure:** brief committed first (`e2446032c`, with the copy list, the Principal's rule of 2026-11-02), then reading and drafting, the draft committed, then W dispatched. No item from W's inventory.
- **Lineage checks:** S0365 L1715, L1803, L1820, L1850 and S0366 L965 were checked by pattern search for the research question and the hypothesis (both sources on the allowed list and already read).
- **Names:** the incompleteness theorem's author, a lens name in (A) and (C), is replaced by "[IT]"; the names rule applies to persons only; provider and other lens names are kept as the source gives them.
- **R1:** no new rows.
- **R2 (`undefined_terms.py --scope 08.91`):** tier A "retyped" (ordinary English, as in §9.20); tier C "protect" (the source's word). Tier D judged as ordinary English.
- **R3 (proposed):** none.
- **Quote check (`qc.py` against S0367 with "[IT]" substituted, S0365, §9.20, §9.18, §1.6):** 84 checked; every quote matched after four quotations of paraphrase or wrong case were corrected and one span of the Professor's wording was unquoted, before the draft's commit.

**At settlement (v2).**
- **R2:** the Professor's run (corpus tiers on) gave tier A "retyped" and tier C "protect", both ordinary English; W's run with `--no-corpus` on the scratch copy gave no tier-A or tier-C candidates and 32 tier-D, judged ordinary English or homed (W's appendix); the difference is the corpus tiering, which `--no-corpus` turns off. v2 adds no term outside those judgements.
- **Quote check (`qc.py`, v2, against S0367 and S0365 with "[IT]" substituted, S0366, §9.10, §9.13, §9.15, §9.16, §9.18, §9.20, §1.6):** 130 checked. The misses are quotations of the withdrawn draft in §8.91.5, W's unfound citation quoted there as such, and the "(W, step 1)" marker; none is a source or book quotation.
- **R3:** none (`inbound_citations.py` for §8.91 C1–C6: none outside §8.91; W).
- **W's procedure:** W copied the 249 files on the copy list by path (no directory copy). Two `--help` runs on scratch tools read no repository file (one failed for want of CORPUS-ALLOWED.tsv under the scratch root, so no corpus file was read); one scratch script was not named `c98-w-*`. No access problem.
