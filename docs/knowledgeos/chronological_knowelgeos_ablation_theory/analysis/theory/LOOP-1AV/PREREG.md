# 1av: blind pilot of the norm/act distinction (human act: option D, 2026-09-30). Frozen before any coding

- **Authority:** the human chose "D: pilot, then decide". **No schema is frozen or adopted by this pilot.**
- **Design:**
  - two fresh blind coders (P1, P2), each confined to its folder, Read/Write only;
  - the two-level form: `observation_type` *relative to the event's operation*, a mandatory `source_act`, plus `deontic` and `dual`;
  - the coders **do not** see the v1 outcomes, the audit classes, witness lists or any expectations.
- **Scorer:** `score_1av.py`, frozen now.
- **Review:** one reviewer subagent, afterwards.

## What it measures (the package's falsification criteria)

| Criterion | Measure |
|---|---|
| **F3** (r2 wrong if unreliable) | P1~P2 agreement and κ on `observation_type` (4-class and binary). **The adoption threshold is the human's.** For orientation only (not a decision rule): agreement ≥ 0.80 and κ ≥ 0.6 are conventional reference values |
| **F4** (dual nature frequent → a single type is insufficient) | the count of `dual = YES`, agreed by both coders |
| **F5** (UNKNOWN share) | the per-coder UNKNOWN share |
| **Convergence** | each coder vs the reconciled 1ar audit classes |

**Caveat, stated in advance:** all coders are the same model family; the agreement is an upper bound (F-LOG-0164).

**Sealed main-analyst expectation (bias check):**
- binary ACT vs non-ACT agreement ≥ 0.85; 4-class κ is lower, because of the UNKNOWN / GENERIC boundary;
- dual = YES for most NORM records (≥ 10), which would confirm F4 and favour the two-level option A;
- convergence with the audit ≥ 0.8.

Moderate confidence.
