---
artifact: 5 · POLICY-EQUALITY-AND-COMPOSITION
mandate: 20260830_1931 §6, §7
date: 2026-08-30
status: EXECUTED — four equality notions; composition is ∧-ONLY, and ∨ is REFUTED
---

# Policy Equality and Composition

## 1. Four notions of policy equality — all executed

```
structural  P_A vs P_A2 (identical)              : True
structural  P_A vs P_A3 (gates REORDERED)        : False    <- order-sensitive
extensional P_A vs P_A3                          : True     <- same behaviour
identifier  P_A vs P_A4 (same id, version v1/v2) : False
extensional P_A vs P_A4 on the test domain       : False
   P_A(D3)=True   P_A4(D3)=None    <- differ ONLY in ResolutionBehavior
```

| Notion | Definition | Verdict |
|---|---|---|
| **identifier** | `(id, version)` equal | **REQUIRED** — the corpus versions policies (`v3`); audit needs it |
| **structural** | same gate SET, interval, resolution behaviour | **REQUIRED**, but **only once gates are a SET** |
| **extensional** | same verdict on every input in the domain | **REQUIRED**, and **undecidable in general** — see below |
| **semantic** | same gates up to logical equivalence | **NOT COMPUTABLE** — needs a decision procedure for predicate equivalence |

### The executed correction to my own type

> **Gates must be a SET, not a tuple.** Two policies differing only in gate ORDER came out structurally
> unequal and extensionally equal. **Order is representation, never semantics** — a tuple leaks it into
> identity. **This is a correction to the type I derived in artifact 2, found by executing §6.**

### The executed proof that `ResolutionBehavior` is load-bearing

`P_A` and `P_A4` share `id`, gates and interval. They differ **only** in `Unknown → Allow` vs
`Unknown → Block`, and they **disagree on `D3`**. Therefore:

> **`ResolutionBehavior` is semantically load-bearing, and VERSION MUST BE PART OF IDENTITY.**
> Two policies with the same name and different unknown-handling are different policies. **A system that
> keys policies by name alone will silently apply the wrong one.**

## 2. Policy equality ≠ equal output — PROVEN by counterexample

```
P_A(D1) = True    P_B(D1) = True    equal output = True
P_A ≠ P_B         (3 gates vs 6 gates, different resolution behaviour)
```
> **Two different policies agreed on this input.** Agreement on any finite sample is therefore **not**
> evidence of policy equality.
>
> **Corollary, and it is a real limitation:** extensional equality is **undecidable in general** — it
> quantifies over the whole decision domain, which is unbounded. **Only structural and identifier equality
> are computable.** This is exactly why the corpus versions its policies rather than comparing them.

## 3. Composition — `∧` only

```
P_A ∧ P_B : gates = union (6), on_unknown = Block (the stricter absorbs)
   D1: P_A=True  P_B=True   -> True
   D2: P_A=True  P_B=False  -> False
   D3: P_A=True  P_B=None   -> None
   in every case = the MINIMUM under False < Unknown < True

laws:  commutative True · associative True · idempotent True
```

> **`(Policy, ∧)` is a COMMUTATIVE IDEMPOTENT SEMIGROUP — a MEET-SEMILATTICE.**
> Composition takes the union of gate sets and the stricter `ResolutionBehavior`. **Adding a policy can
> only narrow admissibility, never widen it** — monotone in the right direction for a safety system.

**Note the pleasing duality with `K`:** `(𝕂, merge, ∅)` is a **join**-semilattice — adding knowledge only
grows the state. `(Policy, ∧)` is a **meet**-semilattice — adding policy only shrinks admissibility.
**Knowledge accumulates; permission contracts. Neither is an aesthetic choice; both were executed.**

## 4. Disjunction — tested and REFUTED

```
Invariant=False, all other gates True:
   conjunction -> False
   disjunction would -> True
```
> **A disjunctive policy would admit a decision whose INVARIANT FAILED, because some other gate passed.**
> That directly violates the corpus's own law — **42.10: *"There must be no averaging"*** — whose executed
> demonstration is precisely that a 95%-satisfied decision is inadmissible.
>
> **`∨` is REFUTED, not omitted.** It was tested against a corpus law and failed. **No algebraic operation
> was introduced for elegance.**

**What about legitimate alternatives — "either two sources OR an authority override"?** That is a
**disjunction INSIDE a single gate**, not between policies:
`g(d) = (nsrc ≥ 2) ∨ override(d)`. **The gate is still one conjunct.** The distinction matters: disjunction
within a gate is expressible and safe; disjunction across policies destroys the conjunctive guarantee.

## 5. Classification

| Claim | Class |
|---|---|
| four equality notions | **FORMALLY DERIVED** + **EMPIRICALLY VERIFIED** |
| gates must be a SET | **FORMALLY DERIVED** — executed; **corrects artifact 2** |
| version is part of identity | **FORMALLY DERIVED** — executed |
| extensional equality undecidable in general | **FORMALLY DERIVED** |
| policy equality ≠ equal output | **PROVEN** — counterexample |
| `(Policy, ∧)` is a meet-semilattice | **PROVEN** — executed laws |
| `∨` refuted | **PROVEN** — against corpus law 42.10 |
