# 1an-r5: reconciliation. Compact task report. **The formal-generalization branch STOPS here**

| | |
|---|---|
| Question | Does START's fixed guard {s} predict held-out decision clusters? |
| Frozen | `diag_1an_r5.py` + `PREREG-r5.md` (`b3dc5348c`; a pre-run self-test toy expectation corrected, disclosed); result + review task (`a37488aa5`) |
| Worker result | {s}: 8/8 correct, 0 wrong, coverage 0.73, 6 folds → "DIAGNOSTICALLY PREDICTIVE" under the frozen rule. {s,t}: NOT SHOWN. Identical across variants. It matched the sealed expectation |
| Review (B-r5) | fidelity OK · faithful to the r4 proposal · **recomputation matches** · 16 disagreements, of which **D-1, D-10, D-11 and D-16 change the reading** |

## Reconciled reading (the reviewer's corrections are adopted; my instrument flaw is disclosed)

1. **Baseline defect (my design; D-1, formal reasoning).** On 5 PERFORMED / 6 REFUSED data, the LOCO majority baseline is structurally anti-correlated: its maximum is 2. So "correct > baseline" is met by any predictor with ≥ 3 correct. **The frozen interpretation rule was too weak.** The verdict label stands as a frozen output, but carries **no evidential weight beyond lookup consistency** (D-15).
2. **Near-definitional guard (D-10, formal reasoning).** s is coded as *the authorization state of the act's target*. The untrained rule "authorized ⇒ PERFORMED" scores 10/10. **The result shows the coding is consistent, not that anything was discovered.**
3. **Circularity (D-3, D-4, D-5: source interpretation).** 4 events have an UNCLEAR pre-act basis. Without them: 4 correct, 3 folds, **exactly at threshold**. Excluding any one of R47a, R47b, R56 or R58a → NOT SHOWN (D-11).
4. **Post hoc recode (D-6, coding).** R47b's s was recoded in r3 from non-separating to separating, after the data had been seen. The strict verdict depends on it.
5. **Independence (D-9).** 6 clusters, but **2 decision episodes** (the 7-series and §12), forming a closed, mutually supporting set of two value classes. "authorized" pools target-execution authorization with batch release (D-16, substantive).
6. **Reverse-circularity risk (D-7, evidence scope).** Some REFUSED outcomes may themselves be inferred from the state, with no attempted act visible.

## Result classification
- **START-s:** **CODING-CONSISTENT (formal), NOT a generalization finding.** Robustness: knife-edge.
- **F-LOG-0157 stands: no guard is shown to generalize by prediction.**

## Theoretical observation (INFERENCE → HYPOTHESIS; not promoted)

The strongest-looking guard turns out to be **analytic**: true by the definition of the coded variable, because "authorized for its target" is the START permission. The other guards are either **synthetic but unsupported** (RAISE, ADOPT, SUPERSEDE: lookup or chance) or **untestable** (< 3 clusters).

**H-AS:** the corpus's governance rules divide into
- **analytic guards** (constitutive definitions: *a START needs an authorization of its target*), whose empirical content lies entirely in *whether the state is independently established*;
- **synthetic guards** (*promotion needs ≥ θ evidence*), which are testable by prediction.

**Consequence for method:** prediction tests are uninformative for analytic guards. For those, the right test is **independent establishment of the premise** (the circularity check), not LOCO.

## Why the branch stops (stop condition: evidence cannot discriminate)

The formal-generalization branch (1an-r4, r5) is exhausted on current data:
- the available clusters are too few (four operations INSUFFICIENT; START rests on 2 episodes);
- the only positive is analytic.

**Designed observation:** ≥ 3 *independent decision episodes* per operation, with the pre-act state established by a source statement separate from the act. This is the same need as Gate 1 plus new releases.

## Next (dynamic queue)

| Candidate | Discrimination | Why |
|---|---|---|
| **H-AS classification** of all guards (analytic / synthetic / unknown) | **MEDIUM–HIGH** | it decides which guards need prediction tests vs premise tests, and whether RAISE is the only synthetic guard |
| evidence split (repetition × breadth) | MEDIUM | it bears on RAISE, the main synthetic candidate |
| kind split (object × act) | MEDIUM | — |
| more clusters | HIGH value, but **needs human-commissioned evidence** (Gate 1 / releases) | — |
