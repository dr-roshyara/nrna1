---
artifact: C · RELATION-ALGEBRA
mandate: 20260830_1852 §9
date: 2026-08-30
status: FORMALLY DERIVED · executed
---

# Relation Algebra for `ℛ`

## 0. The governing result

> **`ℛ` is not one relation type. It is THREE STRICT PARTIAL ORDERS + TWO labelled edge sets + THREE derived
> predicates that are never stored.** Treating `ℛ ⊆ 𝒜 × 𝒜 × RelationType` as a homogeneous set is the error
> that hides every property below.

**The old `RelationType` vocabulary was NOT inherited.** Each relation was re-derived against the mandate's
twelve questions; three were demoted to derived predicates and removed from storage.

## 1. The executed table

| Relation | Class | Directed | Transitive | Symmetric | Antisym | Time-dep | Needs evidence | Needs authority | Stored in `K`? |
|---|---|---|---|---|---|---|---|---|---|
| `equals` | **DERIVED** | n/a | yes | yes | no | no | no | no | **NO — computed** |
| `contradicts` | **DERIVED** | sym | **no** | yes | no | **yes** | no | no | **NO — computed, `K`-relative** |
| `conflictsWith` | **DERIVED** | sym | **no** | yes | no | yes | yes | no | **NO — computed from `e`** |
| `supports` | ASSERTED | dir | **no** | no | yes | no | **yes** | no | **YES** |
| `supersedes` | ASSERTED | dir | **yes** | no | yes | **yes** | no | **yes** | **YES** |
| `derivedFrom` | ASSERTED | dir | yes | no | yes | no | no | no | **YES** |
| `refines` | ASSERTED | dir | yes | no | yes | no | no | no | **YES** |
| `resolves` | ASSERTED | dir | no | no | yes | no | no | **yes** | **YES** |

## 2. Proof obligations — executed

**`supersedes` is transitive and must be ACYCLIC.**
```
{(a3,a1),(a5,a3)}  --transitive closure-->  {(a3,a1),(a5,a3),(a5,a1)}     SOUND
adding (a1,a5)     -->  (a1,a1) ∈ closure   SELF-SUPERSESSION CYCLE
```
> **Acyclicity of `supersedes` is an INVARIANT OF `K`, not an optional check.** A cycle makes "which
> assertion is current?" undefined. **This is a fourth `Valid(K)` predicate that my earlier three-predicate
> definition missed** — see artifact D.

**`supports` is NOT transitive.** Counterexample: `E supports A`, `A supports B` does **not** give
`E supports B`. This is the classical non-transitivity of confirmation, and it means **evidential support
cannot be propagated along chains** — any implementation that does so is unsound.

**`refines` and `derivedFrom` are transitive, antisymmetric and irreflexive → strict partial orders (DAGs).**

**`contradicts` is symmetric, irreflexive, and NOT transitive → a TOLERANCE relation, never an equivalence.**
Consequence: **there are no "contradiction classes."** Any construction that partitions `𝒜` by contradiction
is mathematically wrong.

## 3. Derivability — the storage test

For each relation: *is it reconstructible from `𝒜` alone?*

| Derived | Decision procedure |
|---|---|
| `equals` | compare fields — O(1) |
| `contradicts` | same `(E,D)`, different `V`, overlapping `t`, same `c` — **and not already explained by `ℛ`** |
| `conflictsWith` | `supporting(e) ≠ ∅ ∧ contradicting(e) ≠ ∅` |

| Asserted | Why not derivable |
|---|---|
| `supports` | an **evidential judgement** — no function of the two assertions yields it |
| `supersedes` | a **declared act** — `A₁=3.69, A₂=3.70` is equally consistent with supersession or with an independent observation. **Executed counterexample.** |
| `derivedFrom` | a **historical fact** about production |
| `refines` | a **semantic judgement** |
| `resolves` | a **governance act** |

## 4. Circularity

**`contradicts` is `K`-relative** — `Contradict : 𝕂 × 𝒜 × 𝒜 → Bool`, because a difference already explained
by a `supersedes` edge is not a contradiction (executed; see the closure report §8).

**Is that circular?** **NO.** `Contradict` reads `ℛ`; `ℛ` is stored, not computed from `Contradict`. The
dependency is one-way. **`Valid(K)` must not invoke `Contradict`** — and it does not; `Valid` is structural.
Were `Valid` to include consistency, the dependency would still be acyclic, but the predicate would stop
being decidable in the same complexity class.

## 5. The formal algebra

```
ℛ = ℛ_sup ⊎ ℛ_ref ⊎ ℛ_der ⊎ ℛ_supp ⊎ ℛ_res

ℛ_sup  ⊆ 𝒜×𝒜   strict partial order, ACYCLIC, authority-gated, time-dependent
ℛ_ref  ⊆ 𝒜×𝒜   strict partial order (DAG)
ℛ_der  ⊆ 𝒜×𝒜   strict partial order (DAG)
ℛ_supp ⊆ 𝒜×𝒜   labelled edges, NON-transitive, evidence-gated
ℛ_res  ⊆ 𝒜×𝒜   labelled edges, NON-transitive, authority-gated

derived, never stored:  equals · contradicts(K,·,·) · conflictsWith
```

**Closure conditions:** `ℛ_sup`, `ℛ_ref`, `ℛ_der` may be stored transitively reduced and expanded on
demand — **transitive reduction is lossless for a DAG, so this is a representation choice, not a semantic
one.** `ℛ_supp` and `ℛ_res` admit no such reduction.

## 6. Classification

| Claim | Class |
|---|---|
| The 8-relation classification | **FORMALLY DERIVED** |
| `supersedes` transitive; acyclicity required | **FORMALLY PROVEN** — executed closure |
| `supports` non-transitive | **FORMALLY PROVEN** — counterexample |
| `contradicts` is a tolerance relation | **FORMALLY PROVEN** |
| `contradicts` must be `K`-relative | **EXECUTED** |
| Asserted relations are not derivable | **FORMALLY PROVEN** — counterexample |
| `ℛ` decomposes into 3 DAGs + 2 edge sets | **FORMALLY DERIVED** |
| **Acyclicity is a missing `Valid(K)` predicate** | **EXECUTED — corrects my prior definition** |
| The relation vocabulary is complete | **OPEN** — 8 relations were audited; `knowledge-relationships.yaml` carries others (`implements`, `requires`, `depends_on`, `related_to`) that were **not** audited here |
