# 1ak-3c: reconciliation. Compact task report

| | |
|---|---|
| Question | Is "evidence alone determines promotion" still falsified when evidence is a vector (repetition, breadth)? |
| Frozen | `evector.py` `9ca25087c` (rule, models, variants), committed before its first run; review task `01e6e6c96` |
| Execution | deterministic script; independent formal reviewer (Claude, SECONDARY). The reviewer recomputed by hand: **rule fidelity OK, recomputation matches** |
| Frozen result | M_rep, M_br and M_vec are **FALSIFIED in all four variants** (V0 · VX · VE25 · VX+VE25) |

## Critical dependence (reviewer; POST HOC sensitivity, labelled)

- In the strict variant VX+VE25 (exceptions outside the guard; the contested E25 dropped), **every falsifying pair runs through E01** (ES-004.3, PA, coded 1 instance).
- The sources establish **1–2** instances: provenance 1, plus "the first checklist execution the same day caught a second instance". The five codings so far split: R1-A UNK · R1-B UNK · R2-A UNK · R2-B 1 · this reviewer 1–2.
- With E01 = UNK or 1–2: **M_rep, M_br and M_vec are NOT FALSIFIED** in VX+VE25.

## Consequences (reconciled)

1. **Correction of the F-LOG-0146 reading.** F-LOG-0146's text is not rewritten; this note corrects how it is read.
   - M_E's "robust" falsification used R-39 (the pairs with E02 and E20), and **R-39 carries a recorded exception**.
   - Once exceptions are placed outside the monotone guard (H-X), those pairs do not count.
   - **So M_E and H-X are not rivals.** The candidate that survives on current data is
     **G_RAISE: evidence(vector) ≥ θ ∨ recorded exception by an actor holding exception capability.**
   - M_A (an actor-specific threshold) is **not required** by any strict pair once exceptions are separated. It is not falsified either.
2. **E01 is now the pivot of the whole RAISE question.** Its **decision-time** evidence decides it:
   - **= 1 (the second instance came after adoption):** a PA raise below the stated bar with **no recorded exception**. G_RAISE is FALSIFIED and authority matters beyond exceptions (M_A / M_AT).
   - **= 2 (the second instance was known at decision time):** consistent with G_RAISE, if θ(repetition) ≤ 2 for this target.
   - Formal note: a guard is evaluated in the state **at the act**, δ(X_t, ·). An instance observed after adoption cannot enter X_t. The question is therefore **temporal order**, not interpretation of the count.
3. **D5 (substantive, preserved):** pooling instances, occurrences and slices on one repetition axis is itself an assumption. It underpins every pair.
4. **D6 (formal reasoning):** equal point vectors with opposite outcomes refute *any* function of the vector, not only monotone ones. This strengthens the conditional claim.

## Surviving / eliminated
- **Surviving:** G_RAISE (= M_E-vector + H-X) · M_A · M_AT.
- **Eliminated:** none unconditionally. The F-LOG-0146 M_E elimination is **conditional on counting exception acts as ordinary promotions.**

## Next (highest information: decides G_RAISE vs M_A)

**1ak-3d:** the temporal order of the ES-004.3 adoption (the PA instruction and the hosting in ES-004) relative to the first checklist execution that caught the second instance.
- Evidence genre: the git history of ES-004-Documentation.md and of the R-41 row on 2026-07-30 (locate: commit times and subjects), plus the S1/S2 text.
- Run under the blind loop.
