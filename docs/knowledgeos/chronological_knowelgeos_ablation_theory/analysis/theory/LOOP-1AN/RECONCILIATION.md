# 1an: reconciliation (r1 → r2 → r3). Compact task report

> **Every result here is FORMAL and MODEL-RELATIVE.** "Required" means *required under the named model and the tested separating semantics*. "Redundant" means *model-conditional redundancy*. Nothing here is empirical falsification. Empirical status is imported unchanged.

| | |
|---|---|
| Question | What is the smallest per-operation guard family, and the smallest predictive state, consistent with the strict observations? Which variables are formally required, which are model-conditionally redundant, and which are untestable? |
| Instruments | `minimize_1an.py` r1 (`3d767bb07`); r2 (a label-defect fix, disclosed); `minimize_1an_r3.py` (`61a0b38c5`: separating / hitting-set semantics, after review r2) |
| Reviews | B-r2: spec OK · recomputation OK · 16 disagreements (D1 substantive: consistency-by-ignorance). B-r3: **recomputation matches** · spec fidelity **false** on one point (D-N2: the code's operation-test rule differs from its docstring; UNTESTABLE either way) · 14 disagreements |

## Reconciled results

### Per-operation guards (r3, separating semantics; union across variants: {a, c, e, h, k, s, t})

| Operation | Guard | Robust across variants? | Disjoint independent support | Generalization (reviewer's criterion, applied by hand) |
|---|---|---|---|---|
| START | {s, t} | yes | s: 2 · t: 1 (a star on one git event) | **s GENERALIZING** · t LOOKUP |
| ADOPT | {a, c} (BASE: {a}) | c is variant-dependent | a: 1 · c: 1 | a HALF-LOOKUP · c HALF-LOOKUP |
| AUTHORIZE-IMPL | {a} | yes (depends on NOT-IN-FORCE ≡ refused, D6) | 1 | INSUFFICIENT (2 events) |
| OPEN-WORK · REGISTER | {k} | yes | within one cluster | INSUFFICIENT |
| RAISE | {e, t} (alternative {a, e, r}) | e yes · t variant-dependent | e: 1 (R-36 only) | e SINGLE-CLUSTER · t LOOKUP |
| ASSIGN-ID | {h} | yes | 1 | INSUFFICIENT |
| SUPERSEDE | {k} | the {r} alternative is an **evidence-scope artifact**: r4 lacks the R-44 event that F-LOG-0145 used | 1 | LOOKUP |
| ANNOTATE | — | unconstrained | — | — |

### Variable status

| Variable | Formal (model-relative) | Empirical (imported) | Reconciled |
|---|---|---|---|
| a | required: ADOPT, AUTHORIZE-IMPL | SUPPORTED | consistent; **generalization unshown** |
| k | required: OPEN-WORK, REGISTER, SUPERSEDE | SUPPORTED | consistent; separations are within-cluster or lookups |
| s | required: START | SUPPORTED | **the only generalizing guard variable** |
| t | required: START, RAISE (REV) | possible witness | **required-by-ignorance / lookup** (D-N1); not a generalizing variable on current data |
| e | required: RAISE | WEAK | single cluster (R-36) |
| c | required: ADOPT (REV, REV-TYPE) | WEAK | variant-dependent; 1 cluster |
| h | required: ASSIGN-ID | absorbed (formal) | 1 cluster; the LTS absorbs it into status |
| r, x | never required (r only via the scope artifact) | NOT DEMONSTRATED | **model-conditional redundancy** under r3 on r4 data (+R-44) |
| o | **UNTESTABLE** (no fully comparable cross-operation pair) | NOT DEMONSTRATED | untestable, **not** redundant |

### Bisimulation (r2; 250 → 80 classes, reproduced with operation-name labels)
- `st`, `sup` and `ann` are behaviour-relevant; `iss`, `reg`, `text`, `by` and `deleg` are model-conditionally redundant.
- Caveats, open: redundancy passes vacuously for constant or determined families (D8); `ann` rests on the state cap (D9); action-only observation (D16).

## Main finding (FORMAL CONSEQUENCE + INFERENCE)
- The strict data admit a small per-operation guard family (7 variables in union). But **almost none of it is shown to generalize**: most separations are within one cluster, rest on a single event, or memorize object identity.
- **Formal minimality ≠ a generalizing theory.** In ML terms, the guard family fits the training decisions, but has leave-one-cluster-out support only for START{s}.
- This is the strongest statement the evidence allows. It points the next work at **cross-cluster generalization**, not at further minimization.

## Model equivalences (reviewer)
ADOPT {a,c} vs alternatives · RAISE {e,t} vs {a,e,r} · SUPERSEDE {k} vs {r}. The separating future observations are recorded in B-r3/REVIEW.md §6.

## Next (dynamic queue, by discrimination)
1. **1an-r4 (HIGH):**
   - freeze the reviewer's generalization criterion as an instrument: cross-cluster value recurrence plus leave-one-cluster-out (LOCO) prediction;
   - add the R-44 SUPERSEDE event (the scope fix);
   - fix D-N2 (the operation-test rule);
   - compute disjoint support mechanically.

   This turns "which guards generalize" from a hand judgement into a frozen, reviewable computation.
2. **Evidence split test (MEDIUM):** repetition vs breadth, as orthogonal / necessary / sufficient.
3. Gate 1 (external) is unchanged.
