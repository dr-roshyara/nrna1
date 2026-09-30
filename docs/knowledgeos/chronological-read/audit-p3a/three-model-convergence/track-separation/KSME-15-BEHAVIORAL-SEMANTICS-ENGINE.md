---
source_track: CROSS-TRACK-COMPARISON (the engine itself is semantic-neutral)
input_artifacts: [KSME-14-REPORT]
derived_from: [ksme04.py, ksme05.py, ksme08_relation_separation.py -- the fixed-point partition refinement primitive, generalized]
cross_track_dependency: none -- BSE never asserts identity between lanes, only shared machinery
---

# KSME-15 — Behavioral Semantics Engine (BSE): Formal Specification

**Code**: `.claude/scripts/knowledgeos-ksme/bse.py`.

## Formal contract

$$
R=(E,\mathcal T,\mathcal O,H)
$$

`E`: finite state space (any hashable Python objects — deliberately untyped at the engine level; typing
is the caller's semantic commitment, never the engine's). `𝒯`: dict `name → (fn: (state,ctx)→state,
contexts)`. `𝒪`: dict `name → fn: state→value`. `H`: horizon (`None` = run to fixed point).

**Semantic neutrality, enforced by design, not merely stated**: the engine has no knowledge of what `E`'s
elements represent. It has been fed, in this pass, three purely synthetic systems, Lane-B's real evidence/
`Standing` objects, and (attempted) Track-B's real `Obj`/`State` objects — without any code change. A
`Standing` from Lane-B is never treated as identical to a `State` from Track-B or a historical Track-A
object merely because both pass through the same engine; identity is a claim the caller must justify with
provenance, never something the engine infers from shared interface.

## Core operations

- **`observational_partition()`**: groups states by their observation-tuple. The starting point for
  `behavioral_partition`'s fixed-point refinement.
- **`behavioral_partition(record_steps=False)`**: the coarsest congruence contained in `∼_O` under `𝒯*`
  — computed by the same fixed-point partition-refinement primitive already used in KSME-04/05/08,
  formalized here as one reusable method instead of being re-derived per script.
- **`behaviorally_equivalent(x, y, max_len)`**: exhaustive pairwise check with an automatically-produced
  `CounterexampleCertificate` on failure — the exact operation sequence, observation, and diverging outputs.
- **`congruence_test(F)`**: tests `F(x)=F(y) ⟹ F(T(x,c))=F(T(y,c))` for every `T,c`. Certificate on failure.
- **`sufficiency_test(F)`**: tests `F(x)=F(y) ⟹ x∼_B y` — KSME-14's own "dangerous direction," now a
  reusable primitive rather than a one-off narrative claim.
- **`necessity_test(F)`**: tests `x∼_B y ⟹ F(x)=F(y)` — detects over-distinguishing abstractions.
- **Three minimality notions, kept explicitly separate** (per the commission's own §9, never collapsed):
  `component_minimality` (smallest sufficient component subset, smallest-first search with a
  `sufficiency_test` on each candidate projection), `partition_minimality` (the behavioral partition
  itself — by construction the coarsest behavior-preserving partition), `representation_minimality`
  (min-cost sufficient candidate among named abstractions, cost function supplied and disclosed by the
  caller, never invented by the engine).

## `CounterexampleCertificate`

```python
@dataclass
class CounterexampleCertificate:
    abstraction: str
    state_x: Any; state_y: Any
    abstraction_x: Any; abstraction_y: Any
    operation_sequence: tuple
    observation: str
    output_x: Any; output_y: Any
    violated_property: str
```
Every failed test (`congruence_test`, `sufficiency_test`, `necessity_test`, `behaviorally_equivalent`)
produces one of these, never a bare `False`. This is the commission's own required schema (§5, §18.5),
implemented as one dataclass reused everywhere rather than ad-hoc per test.

## Validation — three independent synthetic systems (§17, exact ground truth known in advance)

| System | Ground truth | BSE result | Match |
|---|---|---|---|
| `S1` (no aliasing, `Z/6`, `+1 mod 6`) | observational = behavioral = 6 classes | 6 = 6 | ✅ |
| `S2b` (genuine observational aliasing, no-op transitions, `parity` observation on 4 states) | 2 behavioral classes, `{0,2},{1,3}` | exact match | ✅ |
| `S3` (observational equivalence NOT a congruence — `A,B` agree pre-transition, diverge post-transition to `X,Y`) | naive `F` is NOT a congruence; true behavioral partition is 4 singleton classes | `naive_is_congruence=False` with exact counterexample (`A` vs `B`, `step`, outputs `X` vs `Y`); behavioral partition = 4 singletons | ✅ |

One real bug found and fixed during construction, disclosed not hidden: `S3`'s first draft accidentally
made the naive observation trivially congruent (both post-states mapped to the same coarse label) —
caught by the engine actually running the test and returning `True` when `False` was expected, not by
inspection. Fixed by redesigning the post-states to diverge under the same coarse `F`. This is exactly
the kind of self-correcting discipline this investigation has practiced throughout — a genuine bug,
disclosed, fixed, verified.

## Validation — minimality notions (synthetic, unambiguous ground truth by construction)

State `(a,b,c)∈{0,1}³`; only `a` is behaviorally relevant (`b`,`c` are flipped by transitions that never
touch `a` or the sole observation `O(state)=a`). Ground truth: `S*={a}`, `|S*|=1`; 2 behavioral classes
of size 4 each; among candidate projections `{π_a, π_ab, π_full, π_b_only}`, `π_a` is the min-cost
sufficient one and `π_b_only` is correctly rejected (with an exact counterexample: `(0,0,0)` vs `(1,0,0)`
agree under `π_b_only` but diverge under the real observation). **All three notions validated exactly,
including the negative case** (`π_b_only` correctly flagged insufficient, not merely omitted).

## What this document does not do

Does not claim BSE is validated against Track-B's full computation (see `KSME-15-TRACK-B-VALIDATION.md`
for the honest, partial verdict). Does not select or imply any KnowledgeOS Kernel. Does not merge any
lane's semantics with any other's.
