# Review of addendum r9 (independent subagent, read-only; Claude family)

**Verdict: FAIL.** The cause is a large derivable BLIND class and the §2 misstatement it produces. No silent false agreement was found.

**Reviewer disclosure:** the reviewer read sealed values. This review must never be routed to the checker.

## Findings
- **r8's changes: implemented.** No new silent path.
- **Derivable BLIND units:**
  - **About 55 V1b U5 verdicts.** In U5, c2 forces σ ≡ q and P = {p}, so every present w1 violates and H-B^R has no c2 history with w1. SUPP's H_U5h is a witness in R_B^R for all readings. Consequences:
    - (ii) is false for all 12 readings;
    - (i)-S is false for the 10 BLIND readings;
    - (iii)-L is false for 11 readings;
    - (i)-O follows from the B-rec analysis.
  - A few U (i)-S verdicts are immediate: R-c, R-g, R-g⁺, R-i and R-i′.
  - **36 K12 fine splits:** Lem 1.12 and the observation definitions; Lem 4.0a; the U5 axiom; K8.7 for U7; K10.7 for U9.
  - **K8.1:** plausibly derivable, a small residual.
- **Not derivable:**
  - the least counterexamples, which have no short class;
  - n_frame_histories, because 665,920 is not in the bundle.
- **Prose:** "cannot read off the spec" is materially false.

## Reconciliation (main analyst)
Every finding is accepted. r10 takes these units to EXPOSED:
- all K12 units and K8.1;
- all U5 form verdicts;
- the five immediate U (i)-S verdicts.

Least counterexamples are BLIND throughout, because they are computed objects. BLIND in P0 is now 91 units: 64 counterexamples, 26 U-frame verdicts and n.

- Comparator `4cadde94…`; selftest `dc21c52e…` PASS (Y7 added).

**This changes the picture at the level of the research design:** the cross-check's independent content is small. The analyst stops the review loop here and returns to the human (F-LOG-0236).
