---
artifact: 2 · POLICY-TYPE-RECONSTRUCTION
mandate: 20260830_1931 §4
date: 2026-08-30
status: **DERIVED — corpus type + corpus evaluation law, both executed**
---

# Policy — Type Reconstruction

## 1. Candidate elimination

| Candidate | Domain → Codomain | Verdict |
|---|---|---|
| `Policy : K × Context → Criterion` | | **REJECTED** — makes a policy state-dependent. Executed counterexample: the same policy must judge two different states, and a policy that changes with `K` cannot be versioned or audited |
| `Policy : Observation → Decision` | | **REJECTED** — skips proposition, evidence and authority; conflates policy with decision (refuted at 202/206) |
| `Policy : Claim × Evidence × Context → Assessment` | | **REJECTED as the TYPE** — this is the signature of **Assessment**, which *takes* a policy. Confusing the two makes the parameter its own function |
| **`Policy = (Rules, ValidityInterval, ResolutionBehavior)`** | an **object**, applied by an evaluator | **ACCEPTED — corpus step 57.47, all three components executed** |
| `Policy = a set of predicates` | | **SUBSUMED** — this is exactly `Rules`; it omits temporal applicability and unknown-handling, both of which are executably load-bearing |

> **The decisive discrimination: a policy is an OBJECT that is applied, not a function that is called.**
> It has identity, a version, an applicability window, and it can be composed and compared. **A bare
> function has none of those.** Steps 13 and 25 name versioned policies (`Migration-Policy v3`,
> `ChangePolicy v3`) — **versioning is only meaningful for an object.**

## 2. The derived type

```
Policy = (id, version, Gates, ValidityInterval, ResolutionBehavior)

  Gates              : SET of three-valued predicates   g : Decision → {True, False, Unknown}
  ValidityInterval   : [vf, vt)          — when this policy applies          [57.47]
  ResolutionBehavior : Unknown → Block | Allow                               [42.12]

Apply : Policy × Decision ⇀ {True, False, Unknown}
Apply(p,d) = False    if ∃g ∈ p.Gates : g(d) = False          ← strict conjunction, NO averaging [42.10]
           = p.ResolutionBehavior(Unknown)  if ∃g : g(d) = Unknown
           = True    otherwise
Applicable(p,t) ⟺ p.vf ≤ t < p.vt
```

**`Gates` is a SET, not a tuple — PROVEN, not chosen.** Executed: two policies differing only in gate
order are structurally unequal but extensionally equal. **Order is representation; a tuple leaks it into
identity.**

## 3. Per-property analysis

| Property | Result | Basis |
|---|---|---|
| **domain** | `Decision` (or an assertion under judgement) | 42.9 |
| **codomain** | `{True, False, Unknown}` — **three-valued** | executed 42.12 |
| **totality** | **partial** — undefined outside `ValidityInterval` | 57.47 |
| **determinism** | **YES** given fixed gates | executed |
| **composability** | **∧ only** — meet-semilattice; **∨ REFUTED** | artifact 5 |
| **equality** | **four distinct notions** — see artifact 5 | executed |
| **identity** | `(id, version)`; **version is semantically load-bearing** | executed: same id, different `ResolutionBehavior`, different result |
| **versioning** | required — corpus names `v3` policies | steps 13, 25 |
| **applicability** | `ValidityInterval` + context scope | 57.47, 54 |
| **conflict handling** | `ResolutionBehavior`; conjunction under composition | 42.12 |
| **provenance** | who authored/authorised the policy — **NOT in 57.47; a gap** | see artifact 9 |
| **execution semantics** | strict three-valued conjunction, no averaging | **executed** |

## 4. Why three-valued, and why it matters

`Unknown` is not a convenience. **42.12 executed: `adm_early(True, None, True, True) → None (Block)`.**
A two-valued policy must map Unknown to either True or False *silently*. **The corpus makes that mapping an
explicit, named component of the policy object** — `ResolutionBehavior`.

> **This is the single most sophisticated thing in the corpus's policy treatment, and it is
> executable.** It means *"we don't know whether the invariant holds"* is handled by a **declared, auditable
> choice** rather than a default. And it connects directly to `Σ`'s `Neutral`/`Unknown` — the same
> epistemic humility appearing at two levels of the theory.

## 5. No averaging — a measurement-theoretic law, not a style preference

42.10: *"There must be no averaging."* Executed: `Pre=T, Assurance=T, Authorization=T, Invariant=F →
False`. **A 95%-admissible decision is inadmissible.**

**This is correct measurement theory and I confirm it independently:** the gates are **nominal/boolean**;
`JustificationStrength` is **ordinal**. **Neither admits arithmetic.** Averaging four booleans would require
an interval scale that does not exist. **The corpus's law and my measurement constraint are the same
constraint, derived twice by different routes.**

## 6. Classification

| Claim | Class |
|---|---|
| `Policy = (Rules, ValidityInterval, ResolutionBehavior)` | **CORPUS ESTABLISHES** — 57.47 |
| `Admissible = Pre ∧ Inv ∧ Assurance ∧ Auth` | **CORPUS ESTABLISHES + EMPIRICALLY VERIFIED** — executed |
| three-valued with `Unknown → Block` | **CORPUS ESTABLISHES + EMPIRICALLY VERIFIED** |
| no averaging | **CORPUS ESTABLISHES** + independently **FORMALLY DERIVED** |
| `Gates` must be a SET | **FORMALLY DERIVED** — executed order test |
| version is part of identity | **FORMALLY DERIVED** — executed |
| the four rejected candidate signatures | **REFUTED** |
| policy provenance | **UNRESOLVED** — absent from 57.47 |
