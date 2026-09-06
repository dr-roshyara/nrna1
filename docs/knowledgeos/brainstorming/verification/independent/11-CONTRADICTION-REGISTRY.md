---
artifact: 11-CONTRADICTION-REGISTRY
date: 2026-08-30
status: **17 entries — 6 true contradictions · 4 supersessions · 3 drifts · 2 abstraction-level · 2 unresolved**
rule: every resolution carries evidence; "the newer definition is better" is not a resolution
---

# 11 · Contradiction Registry

| ID | Contradiction | Classification | Evidence for the classification |
|---|---|---|---|
| **C-01** | `id = H(P,e,c,t,Π)` **and** `e.state` mutable | 🔴 **TRUE CONTRADICTION** | Executed: withdrawal re-keys the assertion; every `ℛ` edge into it dangles; `StructuralValid`'s *no dangling* clause is violated **by a legal operation** |
| **C-02** | `P=(E,D,V)` **vs** `P=(S,ρ,O,Γ)` | 🔴 **TRUE CONTRADICTION, OPEN** | Q14 supplies the first and refutes it in its own §3 (*"the biggest mathematical problem"*); step 262.15 records *"remains an open sub-test"* with two undecided models. **The corpus never chose.** |
| **C-03** | `ℛ = (E₁,E₂,T,R,Q,E,Σ,τ)`, n-ary **vs** `ℛ ⊆ 𝒜×𝒜×RelationType`, binary | 🔴 **TRUE CONTRADICTION** | 4 corpus files carry the 8-tuple; the reduction discards 5 fields + the arity with **no recorded justification**. Not a refinement — a refinement adds constraints, it does not delete carriers |
| **C-04** | `Admissible` 42.9 (4 conjuncts) **vs** 42.41 (6 conjuncts) | 🔴 **TRUE CONTRADICTION** | Same name, same signature `Admissible(d,K,t)`, different bodies. Not a refinement pair: **`Assurance` maps to nothing in 42.41**, and 42.9 depends on `Assurance`, which the corpus has **`REFUTED as definable`** — so 42.9 is *uncomputable*, not merely older. **And neither matches the ratified 6-tuple** |
| **C-05** | `Σ` in `r` is a bare field **vs** `Σ` is policy-relative | 🔴 **TRUE CONTRADICTION** | Executed: identical evidence → `Supported` at `min_support=2`, `Unknown` at `3`. A policy-relative value stored with no policy parameter is ill-typed. **Defect in the corpus model, not only the claim** |
| **C-06** | `Ω` = Knowledge Space **vs** `Ω : W → O` observation function | 🔴 **TRUE CONTRADICTION** | Two *foundational* uses pointing opposite ways (superset-of-knowledge vs map-from-world). Combining them would make the non-identifiability result trivially false. **Unregistered by every prior pass** |
| **C-07** | `θ` = decision threshold **vs** `θ = (1/n)Σᵢ Xᵢ` sample mean | 🔴 **TRUE CONTRADICTION** | Measured. Estimator/threshold conflation across the statistics-policy boundary — the precise error Freedman (cited in this corpus) warns against |
| **C-08** | `Observation` is a **field of** `Evidence` (230.15) **vs** `Evidence = QualifiedObservation` (253) | 🔴 **TRUE CONTRADICTION (type error)** | A value cannot be both a component of `X` and the pre-image of `X`. Calling one model *"a projection of"* the other reconciles the *Evidence* shapes and leaves the *Observation* type error untouched |
| **C-09** | `Assurance` — ≥5 incompatible definitions, one self-referential | ✅ **RESOLVED — REFUTED as definable** | CB-1 adjudication, on record before this pass. **But the term remains a conjunct of law 42.9** ⇒ the refutation was never propagated |
| **C-10** | `Policy = Decision` (207) **vs** `Policy ≠ Decision` (202/206) | ✅ **RESOLVED — REFUTED** | 207 contradicts itself within the same step; self-inconsistency is decisive without appeal to recency |
| **C-11** | `H ∈ K` (253) **vs** `History(K) ≠ K` | ✅ **RESOLVED against 253** | Executed counterexample. **Preserved** as a valid observation that governance needs history — not deleted |
| **C-12** | Σ three-state **vs** Q14's `(A,S,R,V,C)` vector | ✅ **BOTH REFUTED** | The first cannot express degree; the second has no negative pole ⇒ cannot express refutation. Independent grounds, not recency |
| **C-13** | PF-6: `Accepted ∧ Contested` inexpressible | ✅ **DISSOLVED** by the `(Σ,Γ)` pair | Executed expressibility probe in `ladder_dc_reference.py` confirms it has no single-state representation in the layered model |
| **C-14** | `status ⊥ authority` **declared** vs **measured collinear** | 🟡 **ABSTRACTION-LEVEL** | Declaration is a *permission*; measurement is a *fact*. `draft ⇔ provisional` is 13/13 in both directions over all 39 governed docs. Not a contradiction — an **unexercised permission** |
| **C-15** | `Zero(K_t) = Ω \ Represented(K_t)` **vs** *"`Ω` itself may not be fully known"* | 🟡 **ABSTRACTION-LEVEL, flagged by the corpus itself** | The corpus states the caveat in the same file. `Zero` is well-defined **relative to a declared requirement set**, not to `Ω` — which is what `zero_reference.py` actually implements |
| **C-16** | Sufficiency *"undefined"* **vs** `Q` = epistemic sufficiency in `DC(d)` | 🟡 **TERMINOLOGY DRIFT + SUPERSESSION** | `Q` is typed at 42.40 and dropped from the ratified 6-tuple. **The gap is a ratification loss (`G4`), not an absence (`G3`)** |
| **C-17** | Missingness *"inexpressible"* **vs** ten executable statuses | 🟡 **TERMINOLOGY DRIFT** | The word `Missingness` has **0** definitional occurrences; the capability has four names and passing code. **A vocabulary gap presenting as a theory gap** |

## Self-corrections — findings of the *previous* pass that this pass corrects

| ID | Prior finding | Correction |
|---|---|---|
| **SELF-1** | *"`Σ` has no access to `ℛ`"* recorded as a novel defect of the claimed theory | **Incomplete.** The corpus's `ℛ` **carried a `Σ` field**. The real defect is that the reduction to a bare triple **deleted** it. Cause, not symptom — and the deleted field was itself ill-typed (`C-05`) |
| **SELF-2** | *"`Sufficient` has no signature anywhere"* | **Wrong.** `Q` = epistemic sufficiency is a typed component of `DC(d)` (42.40) and a conjunct at 42.41. It was **removed in ratification**, not absent |
| **SELF-3** | *"the smallest missing concept is `(W, Ω)`"* | **Overstated as a single answer.** `(W,Ω)` is required for non-identifiability. But `D_t` (missingness) and the `ℛ` 8-tuple (contradiction) are **independent** of it, and the `ℛ` restoration is smaller and closes more. See `14` §5 |

## Summary

**6 true contradictions open** (C-01…C-08 less the two resolved) · **4 resolved by evidence** ·
**3 terminology drifts** · **2 abstraction-level, correctly not contradictions** · **C-02 and C-04
are declared open by the corpus itself.**

> **Every "resolution by recency" was avoidable.** In each resolved case the ground was
> self-inconsistency (C-10), an executed counterexample (C-11, C-13), or independent expressive
> failure (C-12) — never that a later file existed.
