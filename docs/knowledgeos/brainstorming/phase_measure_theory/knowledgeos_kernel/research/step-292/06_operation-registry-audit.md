# 06 — Operation Registry

## The candidate architecture the mandate offers

```
Operation Registry → Precondition evaluator → Effect evaluator
                   → Persistence/frame mechanism → δ → A_{t+1}
```

## What Reiter supports

`[EXT]` A **basic action theory** is exactly a registry: for each action, a **precondition axiom**
(`Poss`) and **effect axioms** (`γ⁺`, `γ⁻`), plus unique names and `S₀`. `A4` verifies that `Poss`
functions as a **gate**: a non-executable history is rejected at its first violating prefix, not
repaired.

> `[EXP]` **The shape `(name, precondition, positive effects, negative effects)` is formally
> supported.** This is the most directly transferable structure in the source.

## What blocks it

**Step 291 already audited the operation registry independently**, and its verdict stands: membership,
signatures (8/22), bodies (0/22), identity (0/22) and ratification (0/22) are **all OPEN**.

> **Reiter supplies the SHAPE of a registry entry. Step 291 established that KnowledgeOS does not yet
> know its MEMBERSHIP, its signatures, or its bodies.** A shape without members is not a registry.
>
> **`[NEG]` Reiter does not close step 291's gaps, and must not be cited as closing them.**

## The architectural question the mandate asks

> Must `Authority · Observation · Qualification · Provenance · Identity · Equality` sit **inside** or
> **outside** the transition mechanism?

`[PROP]`, on corpus evidence, **not** on Reiter:

| | position | basis |
|---|---|---|
| **Authority** | **OUTSIDE** | `R1` put verification outside; governance acts are not state transitions |
| **Provenance** | **INSIDE the state, outside the transition rule** | it must survive the transition (`KR-CONTR-EVAL`: the boundary component is indispensable) but does not gate it |
| **Identity / Equality** | **UNRESOLVED — cannot be placed** | `≡_sem` is `OPEN`; placing it now would decide it by implication |
| **Observation** | **UNRESOLVED** | `OPEN` in v1.2; see `07` |
| **Qualification** | **UNRESOLVED** | `P4` refuted the `Poss` identification |

> **Four of six cannot be placed.** Recording that is the result; guessing would be the error.
