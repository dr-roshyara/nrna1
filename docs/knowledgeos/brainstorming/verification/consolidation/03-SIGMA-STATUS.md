---
artifact: 03-SIGMA-STATUS
date: 2026-08-30
status: **Σ₀ CONFIRMED as a minimal 4-state space · one prior defect NOT fixed · D-4 now DERIVED and CLOSED**
---

# 03 · Σ Status — Independently Verified

Every result below is re-derived by execution (`exec/sigma.py`), not accepted from 272B.

## A. What is actually proven

### A1 · `Σ₀ = 𝒫({Support,Refute}) ≅ {0,1}²` — ✅ **CONFIRMED, and weaker than it looks**

```
|P(S)| = 4   states = ['Unknown','Supported','Refuted','Conflict']
bijection with {0,1}^2 : True
```

> **True by construction.** A powerset of a 2-set is `2²` — the isomorphism carries no information.
> **The mathematical content is the choice of exactly two independent predicates, not the four
> states.** 272B's real claim is that `Support` and `Refute` are the two, and that is what A2 tests.

### A2 · Four distinct valuations — ✅ **MINIMALITY CONFIRMED**

Each pairwise collapse breaks a **corpus-stated** non-collapse law:

| Collapse | Breaks |
|---|---|
| `Unknown ~ Refuted` | `UNKNOWN ≠ FALSE` (42×) · `UNKNOWN ≠ ABSENT` (19×) |
| `Unknown ~ Conflict` | §271.21 verbatim: **`∅ ≠ ContradictoryEvidence`** |
| `Supported ~ Conflict` | `UNRESOLVED ≠ INVALID` (9×) |
| `Refuted ~ Conflict` | *"insufficiency is not refutation"* |

**Minimality holds relative to those laws** — a genuine deletion argument, not a vocabulary choice.

### A3 · Is contradiction derivable? — 🟡 **YES for one sense, NO for the other**

`Conflict = {Support,Refute}` is **derived** from evidence polarity. ✅

**But executed:**
```
Sigma0(c1)=Supported   Sigma0(c2)=Supported     while (c1,c2,contradicts) holds
```
> **`Σ₀`'s `Conflict` and the corpus's `ℛ_contradicts` are DIFFERENT OBJECTS.** `Σ₀` is **unary** —
> it reads one assertion's evidence set. Contradiction *between* assertions is a **relation**.
>
> ### ⚠️ **The reduction to `Σ₀` did NOT fix the "Σ blind to `ℛ`" defect. It reproduced it.**
> Two assertions in an explicit `contradicts` edge both read `Supported` under `Σ₀`, exactly as they
> did under `(dir,str)`. **This is a NEW finding about `Σ₀` and is on no register.**

### A4 · OR-merge vs retract — ✅ **AND THIS CLOSES D-4**

```
merge(Supported, Refuted) = Conflict        (union: monotone, idempotent, commutative)
retract:  Conflict \ {Refute} = Supported   (requires SUBTRACTION — non-monotone)
```

> ## **PROOF: OR-merge and Retract are jointly satisfiable ONLY IF `Σ` is DERIVED.**
> Union is a join-semilattice operation and is **monotone**; retract is **non-monotone**. A *stored*
> `Σ` is a lattice element with no record of which evidence produced it, so retract cannot undo a
> join. A `Σ` **recomputed from the evidence set** retracts correctly by construction.
>
> **`Retract` / `Withdraw` is a FORCED operation** (`Remove ≠ Withdraw`, corpus), so this is not
> optional. **Therefore `Σ` is DERIVED, not stored — `DERIVED`, no normative choice required.**
>
> **D-4 is hereby closed by derivation and REMOVED from the decision dossier.** This corrects my own
> prior classification of D-4 as normative.

### A5 · Does `Γ` belong outside `Σ`? — ✅ **CONFIRMED**

Ten `(Σ,Γ)` cells all meaningful; decisive corpus case §271.20 — *"epistemically well-supported but
administratively rejected"*. A lossless single vocabulary needs `≥ 4·|Γ|` values.
272B's `Σ ⊥ Λ ⊥ Γ` agrees, independently.

### A6 · Is missingness representable by `Σ₀`? — 🔴 **NO**

```
never asked            -> no assertion  -> Sigma0 UNDEFINED (no carrier)
asked, no evidence     -> e = {}        -> Unknown
insufficient evidence  -> e != {}       -> Supported   (!!)
not applicable         -> no representation at all
```

> **`Σ₀` collapses "insufficient" into "Supported"** — because it has **no sufficiency axis**, `Q`
> having been dropped from the ratified 6-tuple. And *"never asked"* has no `Σ` value at all,
> because it has no assertion to carry one. **Missingness needs `D_t` + a sufficiency predicate;
> `Σ₀` cannot reach it.**

## B. Is "Σ is the remaining frontier" true?

# **NO.** `EXECUTED`

`Σ₀` settles the **epistemic-support space**. It does not settle:

| Still open | Where |
|---|---|
| `P`'s type | §262.15 *"remains an open sub-test"* |
| admissibility law | 42.9 vs 42.41; neither matches the ratified 6-tuple |
| authority boundary | D-2 |
| **missingness carrier** | §276.15, live |
| **`𝒪_core` ratification** | `01` — **not ratified** |
| **`Σ₀` ⊥ `ℛ`** | A3 — **new** |

> **"`Σ` has a minimal four-valued semantic space" ≠ "the state representation is determined."**
> The first is **proven**. The second is **false**: `Σ` was one of six blockers, and closing it
> leaves five — plus the one A3 just added.
