---
artifact: D · KNOWLEDGE-STATE-ALGEBRA
mandate: 20260830_1852 §8, §10
date: 2026-08-30
status: FORMALLY DERIVED · executed · **two corrections to my own prior results**
---

# Knowledge-State Algebra

## 1. §8 — `Valid` is FOUR predicates, not one

**My prior single `Valid(K)` conflated distinct concepts and was missing a predicate.**

```
StructuralValid(K)   ⟺  unique ids ∧ no dangling endpoints ∧ no inverted intervals
                         ∧ ACYCLIC(ℛ_sup) ∧ ACYCLIC(ℛ_ref) ∧ ACYCLIC(ℛ_der)     [4th conjunct: NEW]
SemanticallyValid(K) ⟺  StructuralValid(K) ∧ ∀a ∈ 𝒜 : WellFormed(P(a))
                         i.e. E ∈ ℰ, D ∈ 𝒟, V ∈ V_D                             [Q14 §5.4 — NEW]
EpistemicallyValid(K)⟺  SemanticallyValid(K) ∧ every unexplained contradiction is recorded
                         NOT the absence of contradiction — a knowledge base MAY hold conflict
GovernanceValid(K,Γ) ⟺  every assertion admitted under a satisfied policy by a competent authority
                         NOT a property of K alone — requires History
```

**Executed corrections to my own closure report:**
1. **Acyclicity was missing.** Derived in artifact C; a supersession cycle makes "which is current?"
   undefined. **`StructuralValid` had a hole.**
2. **Well-formedness was missing.** Q14 §5.4 supplies `WellFormed(P)`; `V ∈ V_D` is checkable only once
   `P = (E,D,V)` is known — **so this predicate became available only when CB-2 closed.**

**Is the corpus's `Valid(A,t,C,M)` the same concept? NO.**

| | Subject | Arity | Nature |
|---|---|---|---|
| `Valid(A,t,C,M)` — 025o | an **assertion** | 4 | temporal + contextual applicability |
| `StructuralValid(K)` | a **state** | 1 | integrity of the container |

> **Different objects, different arities, different questions. They must never share the word `Valid`.**
> Canonical: `Applicable(a,t,c)` for the assertion-level predicate; `…Valid(K)` for the state-level family.
> **This is a forbidden conflation — recorded in artifact F.**

**Only `StructuralValid` and `SemanticallyValid` are computable from `K` alone.** `EpistemicallyValid`
requires evidence polarity; `GovernanceValid` requires History and is **not a property of `K`**.

## 2. §10 — The twelve operations

Legend — **Det**: deterministic · **Auth**: authority required · **Gov**: governance check · **Hist**: history effect.

| Op | Input | Preconditions | Output | Postconditions | Det | Auth | Gov | Hist | Computable | Failure modes |
|---|---|---|---|---|---|---|---|---|---|---|
| **create** | — | none | `∅` | `StructuralValid(∅)` | yes | no | no | opens | **YES** | none |
| **assert** | `K, a` | `WellFormed(P(a))`; policy satisfied | `K'` | `a ∈ 𝒜'`; `𝒜 ⊆ 𝒜'` | yes | yes | yes | appends | **YES** | ill-formed `P`; policy reject; authority reject |
| **retract** | `K, a` | `a ∈ 𝒜` | `K'` | `a ∉ 𝒜'`; **`ℛ` cascade** | yes | yes | yes | appends | **YES** | **LOSSY — no inverse** (executed) |
| **relate** | `K, (a,b,ty)` | both endpoints ∈ `𝒜`; acyclicity preserved | `K'` | edge ∈ `ℛ'` | yes | ty-dep | ty-dep | appends | **YES** | dangling endpoint; **cycle** |
| **merge** | `K₁, K₂` | both structurally valid | `K'` | `𝒜'=𝒜₁∪𝒜₂`, `ℛ'=ℛ₁∪ℛ₂` | yes | yes | yes | appends | **YES** | **may introduce contradiction** (executed) |
| **supersede** | `K, old, new` | `old ∈ 𝒜`; acyclicity | `K'` | **`old` RETAINED** + edge | yes | **yes** | yes | appends | **YES** | cycle; missing `old` |
| **refine** | `K, base, ref` | `base ∈ 𝒜` | `K'` | DAG edge added | yes | no | no | appends | **YES** | cycle |
| **resolve** | `K, conflict, res` | conflict is derivable | `K'` | edge added | yes | **yes** | yes | appends | **YES** | no such conflict; authority reject |
| **remove** | `K, a` | `a ∈ 𝒜` | `K'` | as retract | yes | yes | yes | appends | **YES** | as retract |
| **replay** | `History` | history well-ordered | `K` | `Replay(H) = fold(T, ∅, H)` | **yes** | replays | replays | — | **YES** | **order-dependent** (executed) |
| **validate** | `K` | none | `Bool × Reason` | **`K` UNCHANGED** | yes | no | no | **none** | **YES** | — |
| **assess** | `P, e, c, Policy` | — | `Σ` | **`K` UNCHANGED** | yes | no | no | **none** | **YES** | — |

## 3. The four kinds of act — never conflate

| Kind | Signature | Changes `K`? | Appends History? |
|---|---|---|---|
| **transformation** | `𝕂 × Op × Policy × Authority ⇀ 𝕂` | **YES** | **YES** |
| **assessment** | `P × Evidence × Context × Policy → Σ` | **no** | no |
| **decision** | `Assessment × Authority → Determination` | no (until asserted) | **YES** |
| **governance action** | `Policy × Transformation → Admissible` | no | **YES** |

> **`validate` and `assess` are the only two operations that do not change `K`.** They are *queries* wearing
> operation names. **Any implementation in which validation mutates state has conflated assessment with
> transformation** — the exact error step 232.4 warns against (`Validation → Assessment`, not `K → K`).

## 4. Proven algebraic laws (executed)

```
(𝕂, merge, ∅)   commutative · associative · idempotent · identity ∅   ⇒ JOIN-SEMILATTICE
assert           idempotent · commutative · associative · MONOTONE
retract          idempotent · NOT monotone · NO TOTAL INVERSE          [counterexample executed]
split            LOSSY on ℛ                                            [counterexample executed]
merge            preserves StructuralValid, NOT EpistemicallyValid     [counterexample executed]
replay           deterministic · ORDER-DEPENDENT                       [counterexample executed]
query            pure · monotone in K · temporally correct
```

**`merge` is a join-semilattice but NOT a lattice** — no meet is defined, because intersecting two knowledge
states is not a knowledge-preserving operation (it discards assertions no one retracted). **Recorded as a
deliberate absence, not an oversight.**

## 5. Classification

| Claim | Class |
|---|---|
| `Valid` splits into four predicates | **FORMALLY DERIVED** |
| **Acyclicity is a missing conjunct** | **EXECUTED — corrects my prior `Valid(K)`** |
| **`WellFormed(P)` is a missing conjunct** | **CORPUS ESTABLISHES** (Q14 §5.4) — unavailable to me until CB-2 closed |
| `Valid(A,t,C,M) ≠ Valid(K)` | **FORMALLY PROVEN** — different arity and subject |
| Twelve operation signatures | **FORMALLY DERIVED** |
| Join-semilattice; no meet | **FORMALLY PROVEN** — executed |
| retract/split/merge/replay counterexamples | **EXECUTED** |
| `validate`/`assess` do not mutate | **CORPUS ESTABLISHES** (232.4) + derived |
| The operation set is complete | **OPEN** — twelve were mandated and audited; completeness is not proven |
