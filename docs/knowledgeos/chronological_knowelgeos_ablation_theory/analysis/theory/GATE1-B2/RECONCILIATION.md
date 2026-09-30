# 1ag run B2: reconciliation (SECONDARY, same-family; **not Gate 1 proper**)

| | |
|---|---|
| Question | Does a fresh Claude coder reproduce the Gate 1 / reserve coding? Is the earlier main-coder-bias finding confirmed? Why are V1 and V4 unstable? |
| Frozen | commissioning `717f0e238` · codings sealed `e515f9b35` · scoring + review task `bf3050926` |
| Review | scoring fidelity **OK** (27 κ / agreement values, 17 Jaccard rows and 2 coverage means checked by hand) · 30 classified disagreements |

## Reconciled findings

1. **Intra-family stability is high.** B1 and B2 agree closely on ops (Gate 1 Jaccard 1.0) and on V2, V3, V6 and V7. **Caveat (independence):** identical ops on all 28 rows indicate **correlated same-family priors**, not independent agreement.
2. **Main-coder bias: QUALIFIED, not simply confirmed.** The claim "the blind coders agree with each other more than with MAIN" holds for V2, V3, V4, V7 and ops. At row level, under the manual:
   - **V3:** MAIN is right in 4–5 rows; the blind coders share an inflation of AMBIGUOUS (**correlated blind error**);
   - **V2:** mixed; MAIN under-reads in 2 of 3 rows;
   - **ops:** mixed.

   **Reconciled:** MAIN resolves ambiguity toward determinate codes, sometimes with cross-record knowledge (RESERVE R-54). The blind coders share systematic tendencies of their own. F-LOG-0135/0136's "main-coder bias" is **real but bidirectional**: it is not the case that "MAIN is wrong".
3. **Coverage gap = an enumeration habit** (MAIN lists 8 not-modelled items vs B1's 23). It is **not** theory coverage. The provisional reading is withdrawn.
4. **V1 and V4 instability is mostly the instrument.** The V1 vacuous case (no guarded act) explains **15/20** MAIN–B2 and **15/17** B1–B2 Gate 1 V1 disagreements. V4 lacks an applicability rule.
5. **Violation detection is untested.** No blind coder coded VIOLATED in 41 rows. MAIN's single VIOLATED (RESERVE R-76) has no blind support.
6. **Baseline integrity.** MAIN's Gate 1 coding was done partly while manual v1.1 was being developed, so it is **not a clean sealed baseline**.
7. **Kappa paradoxes** (prevalence: V5, V1-MAIN, V3, V7, and RESERVE V5 not estimable). Report agreement with prevalence, not κ alone. V6 is mechanical and uninformative.
8. **Scorer caveats (inert here, latent risk):** the `norm()` truncation; empty ops sets score 1.0; rare ops (< 3) are hidden from the per-op output.

## Model impact
- The coding instrument is **stable within the family** but **under-specified in its manual** (V1, V4, V2 applicability, the AMBIGUOUS threshold).
- The per-judgement results V1 and V4 from earlier F-LOG entries should be read as **instrument-limited**.
- Nothing here is independent confirmation.

## Decision required (human): the manual r3 revision is a **frozen-protocol change**

The reviewer's seven clarifications are **recommendations only**; they are listed in REVIEW.json and include the V1 vacuous case, the V1 row-value precedence, the V4 actor rule and the AMBIGUOUS threshold. Adopting them changes the frozen coding protocol, and any new coding round (including Gate 1 proper) would then use r3.
