# TRANSFORMATION CONTRACT GAP

**Authority:** HPA mandate 2026-08-31 §1B/§3, recorded GN-77. **No transformation is defined, no
algebra selected, no pre/post-condition invented.**

## The finding in one sentence

> **The ratified canon defines when a transition would be ILLEGAL without ever defining what a
> transition IS.**

Two legality constraints are ratified — **I-12** (the ladder is a covering relation; skipping is
formally excluded) and **A6** (the Accepted→Committed boundary is crossed by an authority act,
never by evidence volume). Both constrain a transition relation that the canon never specifies.

## A · The five elements, on the mandate's ladder

`mentioned → specified → authorized → ratified → executable`

| Element | Highest rung reached | Evidence |
|---|---|---|
| **Preconditions** | **mentioned** (once) | The single occurrence in the entire governed surface is v0.1 §1's concept row: *"**Authorization** — precondition constraint filled via governance, never a processing step"* (grade COMPOSITION). It describes what Authorization *is*; it is not the precondition of any operation. |
| **Postconditions** | **not even mentioned** | Zero occurrences of `postcondition` / `post-condition` anywhere in `model/` or `final-architecture/`. |
| **Legal transition rules** | **ratified — but as constraints only** | I-12 and A6 are ratified legality laws. They quantify over transitions; no transition is defined. A law over an undefined relation is not executable. |
| **Preservation obligations** | **not established** | No artifact maps any operation to the invariants it must preserve. Compounded by the absence of a closed invariant register — the obligation set has no domain *and* no codomain. |
| **Rejection / invalid-transition semantics** | **not established** | The canon defines no rejection type and no failure vocabulary. It does not distinguish a structural refusal from a policy refusal from an authority refusal — a distinction its own DC 6-tuple implicitly needs. |

**Nothing reaches `authorized`, let alone `executable`, except the two constraint laws — and those
are constraints, not semantics.**

## B · What a canonical transformation contract must contain (shape only, unfilled)

```
Transformation
  signature                     —   [what it maps from and to]
  legality condition            —   [canon supplies TWO constraints, I-12 and A6;
                                     it does not supply the condition itself]
  preconditions                 —   [MISSING]
  postconditions                —   [MISSING]
  preservation obligations      —   [MISSING; depends on a closed invariant register]
  partiality                    —   [when is the transformation undefined?]
  rejection types               —   [MISSING — typed vocabulary required]
  composition rules             —   [MISSING — no algebraic law may be assumed]
  determinism                   —   [MISSING]
  replay behaviour              —   [MISSING; depends on state identity/equality]
  evidence effect               —   [MISSING]
  authority effect              —   [canon fixes ONE case: A6 — an authority act crosses the
                                     boundary; evidence never does]
```

## C · Three dependencies that make this un-fillable in isolation

1. **On the operation registry.** A transformation contract is written *per operation*. With zero
   operations defined, there is nothing to write contracts for. `TRANSFORMATION` is therefore
   blocked **behind** `OPERATIONS`, not merely alongside it.
2. **On state identity and equality.** A postcondition is a predicate over the resulting state.
   Until `K₁ = K₂` is decidable, no postcondition can be checked — and the canon defines neither
   identity nor equality.
3. **On a closed invariant register.** "Preserves I-n" presupposes that the set of I-n is closed.
   The canon asserts twelve invariants; it does not assert that they are all of them.

## D · Status declaration

```
Transformations = BLOCKED / NOT CANONICAL
Blocked behind:  the operation registry (primary)
                 state identity + equality (secondary)
                 a closed invariant register (secondary)
```

## E · Boundary

No transformation defined · no algebra chosen · no pre/post invented · no composition law assumed ·
no layer-2 candidate promoted.
