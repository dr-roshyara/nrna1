# 1an-r4: reconciliation. Compact task report

| | |
|---|---|
| Question | Which formally required guards generalize beyond the decisions that force them? |
| Frozen | `minimize_1an_r4.py` + `PREREG-r4.md` (`35a756123`); result + review task (`17650885e`) |
| Review (B-r4) | spec fidelity **OK** · recomputation **matches** · criterion faithful **NO** (D-5: disjoint support lacks cross-cluster / per-op counts; D-8: contrary recurrence ignored) · 16 disagreements |
| Bias check | every sealed expectation matched. The reviewer's corrections are therefore adopted wherever they are formal or scope corrections, **not** softened |

## Reconciled per-operation verdicts (FORMAL / MODEL-RELATIVE; strict observations only; not empirical proof)

| Operation | Guard | Evidence used for the verdict | Verdict |
|---|---|---|---|
| **START** | {s, t} (PARTIAL: R-72 × git unseparated) | s: recurrence GENERALIZING 4/4 pairs, **2 disjoint** clusters. **LOCO uninformative**: the (s, t) tuple is a lookup; 2/11 coverage; both predictions are one recurring tuple. t is **required-by-ignorance** (git's s = UNK, D-N1 / D-10) | **UNCLEAR.** s: *formal cross-cluster recurrence only*. t: does not generalize |
| RAISE | {a,e} / {e,t} | LOCO 4/8 is **at chance and below the always-PERFORMED baseline (5/8)**; all 4 correct predictions come from a route guard fitted on 2 events; e's one prediction was wrong; e recurs **contrarily** (D-8) | **DOES NOT GENERALIZE** |
| ADOPT | {a} / {a,c} | LOCO 0 correct; a and c are LOOKUP | **DOES NOT GENERALIZE** |
| SUPERSEDE | {k} (+R-44) | R-44 refutes {r}; k is an identity lookup; LOCO 0/1 | **DOES NOT GENERALIZE** |
| AUTHORIZE-IMPL · REGISTER · OPEN-WORK · ASSIGN-ID · ANNOTATE | — | < 3 clusters | **INSUFFICIENT** |

## Corrections adopted from the review

1. **Operation:** "MODEL-COND-REDUNDANT under MO" **withdrawn** (D-6, formal reasoning). Operation is confounded with k, a and r; 103 pairs are untested; one pair has o as its only known separator. **New label: operation NOT FORCED on comparable pairs; UNDETERMINED.**
2. **Disjoint support:** k = 3 is pooled (two within-cluster contrasts + one lookup); **cross-cluster-only k = 1** (D-5).
3. **LOCO validity** (formal reasoning, D-3 / D-13): on data this small, LOCO tests the *refit procedure*, not the guard. Singleton guards fitted on 2 events drive the predictions, and the voting rule is unspecified (D-1, coding). **LOCO results are diagnostic of instability, not evidence of generalization.**
4. **Stability ≠ predictive success** (D-14). It is uninformative for identity-valued guards.

## Model impact
- **No guard has yet been shown to generalize by prediction.**
- The single surviving positive is **START-s cross-cluster recurrence** (2 disjoint clusters). It is formal, target-indexed, and consistent with the empirically SUPPORTED state dimension.
- All other guards fit their forcing decisions only, or are untestable.

## Next (the reviewer judged it justified; it must be frozen before running: B-r4 start_coverage_assessment)

**1an-r5:** a guard-restricted **START {s}-only** diagnostic, as a secondary formal diagnostic and **not** a rescue:
- consistency defined as table consistency among s-known training events;
- fixed majority baseline;
- a single guard, so no voting.

It tests whether t adds predictive information or only a lookup.
