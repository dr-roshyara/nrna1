---
source_track: TRACK-A-PHASE-MEASURE
input_artifacts: [KSME-08-D1R-GENERALIZED-DISTINCTION]
derived_from: []
cross_track_dependency: none
---

# KSME-08 — Relation-Separation Experiment (exact, synthetic)

**Tier: `SYNTHETIC` throughout.** These five finite models are constructed to test a mathematical
question (can ordering and behavioral equivalence coexist, and when does ordering descend to the
quotient), not to represent real KnowledgeOS semantics. No claim here says anything about what the real
Kernel is. Code: `.claude/scripts/knowledgeos-ksme/ksme08_relation_separation.py`. Results:
`results` folder JSON (same directory as the script). `∼_B` computed via the same fixed-point
partition-refinement primitive used in `KSME-04`/`KSME-05` (a known-correct method, reused not
re-derived).

## Results (exact, exhaustive — all models finite, fully enumerated)

| Model | Order relation | Classification | `∼_B` | Descends to `E/∼_B`? |
|---|---|---|---|---|
| M1 — pure equivalence, no order | — | — | equivalence | n/a |
| M2 — total order + discrete `∼_B` | `≤` | partial order | equivalence | **True** |
| M3 — order + non-trivial `∼_B`, compatible | order on hidden component | preorder | equivalence | **True** |
| M4 — genuine preorder (non-antisymmetric ties) + `∼_B` | rank-based | preorder | equivalence | **True** |
| M5 — order + `∼_B`, deliberately incompatible | tagged order | partial order | equivalence | **False** |

`∼_B` is confirmed, in every model, to satisfy reflexivity/symmetry/transitivity exactly (checked
computationally, not assumed) — it is a genuine equivalence relation throughout, regardless of what the
order relation looks like. This directly confirms the commissioning's premise.

## M5's counterexample certificate (the compatibility-failure witness)

```
E = {(0,A), (0,B), (1,A), (1,B)}
Observation: sees only the first component (x), not the tag.
=> ~B classes: {(0,A),(0,B)}, {(1,A),(1,B)}

Order (partial order, verified): reflexive edges, plus (0,A) <= (1,B).
NOT included: (0,B) <= (1,A).

Witness: (0,A) ~B (0,B), (1,A) ~B (1,B), (0,A) <= (1,B),
         but (0,B) <= (1,A) does NOT hold.
=> the order relation does not descend to a well-defined relation on E/~B.
```

This is the precise, computed instance of the compatibility condition the commissioning asked to test:
*"`x∼B x'` and `y∼B y'` and `x≼y` implies `x'≼y'`"* — shown to be a real, non-vacuous condition that can
fail, not a formality that always holds.

## Interpretation

1. **Central hypothesis confirmed**: semantic ordering (`R_ord`) and behavioral equivalence (`∼_B`) are
   independent mathematical layers. `∼_B` remains a genuine equivalence relation regardless of what
   order structure coexists with it (M2–M5 all confirm this exactly).
2. **Coexistence is not automatic**: M3/M4 show clean compatibility (the order and the observations
   "agree" on what's hidden vs. visible); M5 shows a concrete failure when they don't. The compatibility
   condition is real and must be checked case-by-case for any concrete `R_ord`, not assumed.
3. **What this does NOT show**: this experiment says nothing about which real KnowledgeOS distinctions
   need `R_ord` at all, nor what shape `R_ord` should take if needed. It only establishes the
   *mathematical possibility and the precise failure condition* — exactly the scope the commissioning
   asked for, no more.
