# `KR-ZERO-ALGEBRA-2026-09`
## Empirical adjudication of transformation-relative Zero and the Elimination/Retention operator

**Role:** experimental executor. **The task was not to confirm the proposed Zero theory.**
**Theory v1.2 unchanged · no v1.3 · kernel NOT SELECTED · nothing ratified · no result converted to a
theorem or invariant.**

> ## Headline
>
> **`L` is not a projection. The tested algebra is a TERMINATING, NON-CONFLUENT rewriting process,
> and `Zero` is not element-wise in either direction.**

**8 of 11 algebraic hypotheses REFUTED · 2 CONFIRMED · 1 PARTIALLY CONFIRMED.**

| | |
|---|---|
| **H3** iteration reaches a fixed point | **CONFIRMED** — 0 cycles, 0 non-convergent, ≤ 2 iterations |
| **H4** iteration monotone decreasing | **CONFIRMED** — 0 violations |
| **H1** `L² = L` | **PARTIALLY CONFIRMED** — holds for `L_sequential`/`L_rule`, **REFUTED for `L_simultaneous`** |
| **H2** `L_A L_B = L_B L_A` | **REFUTED** |
| **H5** individual Zero ⟹ group Zero | **REFUTED** — 131 witnesses |
| **H6** group Zero ⟹ individual Zero | **REFUTED** — 63 witnesses |
| **H7** Zero invariant under reference change | **REFUTED** — 541 differences |
| **H8** Zero independent of contract detail | **REFUTED** — 495 differences |
| **H9** invariant ≡ Zero | **REFUTED** — near-orthogonal |
| **H10** `Remainder = D − Eliminated` | **REFUTED** |
| **H11** `RuleEliminable = CounterfactualZero` | **REFUTED** — **55.75 %** agreement |

## Contents

```
README.md              this file
experiment-spec.md     what was tested and how
implementation.md      the two paths, the operators, the vacuity guard
results.md             RQ1–RQ8, H1–H11, Q1–Q14 adjudication
counterexamples.md     the minimal witnesses
property-results.json  full machine-readable results
seeds.json             base seed + per-family offsets
witnesses/             persisted failing witnesses (JSON)
code/                  zero_algebra.py · generators.py · experiments.py · run.py
```

## Reproduce

```bash
cd code && python3 run.py        # deterministic; base seed 20260902; 1200 cases/family
```

## Status discipline

`[EXP]` observation · `[NEG]` refuted · `[PROP]` hypothesis · `[OPEN]` unresolved ·
`[DEFECT]` implementation/test defect.
**Not written anywhere:** *"Zero is proven"* · *"Zero is mathematically correct"* · *"the Vedic
principles prove Zero"*. The Vedic material was treated as **external methodological inspiration
only** and is **not** validated by this experiment.
