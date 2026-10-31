# Window C64: measures, challenge records, step 8, checks

*2026-10-31. Sources: S0295, S0296, read in index order; both on the allowed list. New: §8.57 (C1–C5). No new concept. Changed theory since the last window review: the C59 and C60 settlements; the C61–C63 settlements pending at drafting (settled `a6bb12593`, `ffda36c93`, `1170579e9`; R3 applied before review at `a20e7da2c`).*

## Measures

| Measure | Value |
|---|---|
| Sources, lines (`wc -l`) | S0295 1,682 · S0296 53 = 1,735 |
| Depth | both full (the Professor and W) |
| Professor, reading (start/stop records) | S0295 15:26:07Z–15:26:42Z; S0296 15:26:42Z–15:26:52Z. Bookkeeping times; they understate reading |
| Reviewer W, step 1 | 82.0 s (harness), 8 tool uses |

## Step 8

- **Dependencies (as settled, W-11):**
  - C1: §8.44 C1; §8.45 C1 (L25); §8.47 C1; §8.54 C1; §8.55 C3; §8.56 C1; §8.61 C4.
  - C2: S0241 Art 2.3, Art 9.3; §8.31 C1; §8.37 L117; §9.10 Q4; §1.3; §8.51 C1; §8.52 C2; §8.56 C3.
  - C3: §8.56 C1 (as settled); §8.53 C1 (b), C2; S0241 Art 1.1–1.4, Art 5.1; S0294 L1055; §9.10 Q1, L93; §8.39 C3; G10; §8.55 C4.
  - C4: decision C1; §8.41 C2 (premise on the grant entry); A1; §8.55 C1, C2; §8.36 C1; §9.13.
  - C5: S0241 Art 1.2, Art 9, Art 11; §8.55 C4; §8.48 C1 (a different completeness).
  - *Draft list (superseded):* C5 had §1.16.2 and Art 11.2.
- **R1:** no new register rows; 241 homes, 1 flag (CON-0133, pre-existing).
- **R2 (§8.57):** tier A 0. At review W judged all thirteen tier-D candidates (`c64-w-r2.txt`): "scalar" needed Art 2.3's sense (now stated in C2); "registrant" as at §8.50, qualified as self-declared; the rest ordinary or the source's words. No TODO row.
- **R3 (settlement):** the draft's proposed annotations of §8.55 C3 and §8.56 C1 were applied before review (`a20e7da2c`). At settlement: the premise qualifier on "wrote is excluded" added at §8.54 C1, §8.55 C3, §8.56 lineage, §8.58 C1, §8.61 C4; §8.55 C1 (the PO/ARB's handoff 1 is the skipped step); §8.55 C5 and §8.58 C3 (confidence as settled); §8.60 C4 (within the act's content).
- **Quote check:** by hand. The question at S0295 L639 is quoted as written, spelling and spacing included (PL1 corrected at review, W-07). Line slips corrected at review: the answer starts at L640; "partially true" is at L642; the diagram runs L1558–1586 (W-11).

## The Principal's directions D1–D3 (I-013): meeting points in this window (for the report; not used as evidence, not written into the book)

| Direction | Where this window meets it | Label |
|---|---|---|
| D3 | S0295 L26–33, L1071–1091, L1660–1676: the grammar lens, the Semantic Invariance Layer and the semantic language are "not a kernel object", "Candidate Mechanism (Not Kernel)", "Representation / Reasoning Mechanism"; but the diagrams place "Semantic Identity" and the "Semantic Grammar Layer" directly under ZERO as "the stable middle" (L551–572, L1561–1586) | Src: agrees in wording with D3; the diagram placement is a possible difference, under one reading (open) |
| D1, D2 | not met (S0295's examples are engineering examples — architects, ADRs, security reviews — which fit D2's current domain; no statement on scope) | — |
