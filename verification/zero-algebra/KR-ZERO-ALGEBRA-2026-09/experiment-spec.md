# Experiment specification

## The definition under test

```
Zero_{T,Π}(x)   iff   Π(T(D)) = Π(T(D \ x))
```

**Nothing was assumed.** Not the direct sum, not projection, not idempotence, commutativity,
monotonicity or convergence. Those are the objects of the experiment.

## Factors

| | values |
|---|---|
| **Representation `R`** | `R1` token sequence · `R2` + metadata · `R3` graph · `R4` structured claim/evidence |
| **Transformation `T`** | `T1` stop-word · `T2` duplicate · `T3` normalization · `T4` context-dependent · `T5` reference-relative · `T6` metadata-preserving · `T7` metadata-destroying · `T8` interacting |
| **Contract `Π`** | `P1` result · `P2` +length · `P3` +required · `P4` +provenance · `P5` +uncertainty · `P6` +scope · `P7` +contradiction · `P8` composite · **`P9` balance** |

**`P9_balance` was added by the executor** — net `(#positive − #negative)`. **Rationale, stated
because adding a factor is a design choice:** adversarial case **J** (individually non-Zero, jointly
Zero) requires a contract that can *cancel*. Without a cancelling contract, case J is unreachable by
construction and `H6` would have "passed" vacuously. **It is declared, not smuggled.**

## Corpus

**1 200 generated cases per family**, base seed `20260902`, per-family offsets in `seeds.json`.
Shapes include every pathological form the spec requires: empty · singleton · repeated · nested
repetition · alternating · overlapping rules · metadata conflicts · duplicate metadata with different
provenance · identical values with different sources · contradictory claims · long · random.
**No single hand-written example carries any conclusion.**

## Elements are addressed by POSITION

Two occurrences of the same token are **distinct elements**. This is load-bearing: it is what makes
the duplicate witnesses meaningful rather than a naming artefact.

## Stop conditions honoured

The spec lists conditions under which the executor must **stop and report rather than invent a
solution**. One fired:

> **A property appearing true only because of an incoherent pairing.** `T7_meta_destroying × P9_balance`
> makes *every* element Zero — the transformation erases exactly what the contract reads. **Detected,
> quarantined by an explicit vacuity guard, and excluded from all algebraic conclusions.** 10 of 1 200
> cases. See `implementation.md`.

**Semantic equivalence was NOT invented.** All comparisons are exact structural equality on contract
outputs. **Semantic equality remains `[OPEN]`.**
