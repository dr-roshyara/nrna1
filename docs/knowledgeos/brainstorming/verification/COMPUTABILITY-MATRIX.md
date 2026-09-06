---
artifact: G · COMPUTABILITY-MATRIX
mandate: 20260830_1852 §14
date: 2026-08-30
status: EXECUTED — 14 of 16 computable, 2 blocked
---

# Computability Matrix

`n = |𝒜|`, `m = |ℛ|`.

| Object / predicate / operation | Formal definition | Inputs known? | Algorithm | Decidable? | Complexity | **Executed?** |
|---|---|---|---|---|---|---|
| **membership** `a ∈ K` | `a ∈ 𝒜` | yes | hash lookup | **YES** | `O(1)` | **YES** |
| **structural equality** | `(𝒜₁,ℛ₁) = (𝒜₂,ℛ₂)` | yes | set compare | **YES** | `O(n+m)` | **YES** |
| **semantic equality** | equal on `(P,c,t)`, ignoring `id,e,Π` | yes | projected set compare | **YES** | `O(n+m)` | **YES** |
| **`StructuralValid(K)`** | unique ids ∧ no dangling ∧ no inverted intervals ∧ **acyclic** | yes | scan + 3× DFS | **YES** | `O(n+m)` | **YES** (acyclicity added this phase) |
| **`SemanticallyValid(K)`** | + `WellFormed(P)`: `E∈ℰ, D∈𝒟, V∈V_D` | **only since CB-2 closed** | membership in `ℰ,𝒟,V_D` | **YES** | `O(n)` | **NO — not yet executed** |
| **`EpistemicallyValid(K)`** | + unexplained contradictions recorded | yes | pairwise within `(E,D)` buckets | **YES** | `O(n + Σᵢbᵢ²)` | partly |
| **`GovernanceValid(K,Γ)`** | every admission policy-satisfied | **requires History** | replay + check | **YES given History** | `O(|H|)` | **NO** |
| **contradiction** | `𝕂×𝒜×𝒜→Bool`, `ℛ`-discounted | yes | bucket by `(E,D)`, interval overlap, minus `ℛ` | **YES** | `O(n + Σᵢbᵢ²)`; `O(n²)` worst | **YES** |
| **conflict** | `supporting(e)≠∅ ∧ contradicting(e)≠∅` | **only with polarity in `e`** | scan `e` | **YES** | `O(\|e\|)` | **YES** |
| **supersession (current?)** | maximal element of `ℛ_sup` | yes | DAG topological max | **YES** | `O(n+m)` | **YES** |
| **assessment** | `P × Evidence × Context × Policy → Σ` | **Policy semantics UNSPECIFIED** | policy-dependent | **conditionally** | policy-dependent | **NO** |
| **status derivation** | `Σ = (dir, str)` from `e` | **needs polarity + a strength rule** | fold over `e` | **conditionally** | `O(\|e\|)` | partly |
| **transformation** | `𝕂×Op×Policy×Authority ⇀ 𝕂×Outcome` | yes | guard then apply | **YES** | `O(1)`–`O(n+m)` | **YES** |
| **replay** | `fold(T, ∅, History)` | yes | left fold | **YES** | `O(\|H\|)` | **YES** |
| **lineage** | ancestors in `ℛ_der ∪ ℛ_ref` | yes | reverse reachability | **YES** | `O(n+m)` | **YES** (47 tests, real code) |
| **provenance** | `Π(a)` | yes | field read | **YES** | `O(1)` | **YES** |

## The two blocked entries — exactly why

**`assessment` — BLOCKED, and not for want of an algorithm.**
`Assessment : P × Evidence × Context × Policy → Σ` is well-typed and would compute in `O(|e|)`. **What is
missing is the semantics of `Policy`.** No corpus passage specifies how a policy maps an evidence set to a
support level — how many independent sources make support "Strong", whether source quality weights, how
contradicting evidence offsets supporting. **The function is defined; its parameter is not.**
**This is the correct diagnosis of CB-3-adjacent failure: not incomputable, UNDER-SPECIFIED.**

**`status derivation` — CONDITIONALLY BLOCKED.** `dir` computes now. `str` requires a rule mapping evidence
to an ordinal level, and **that rule must be order-preserving only** — no arithmetic is admissible, because
no interval scale is established (artifact B §6).

## Complexity note — contradiction detection

The naive pairwise scan is `O(n²)`. **Bucketing by `(Entity, Dimension)` reduces it to `O(n + Σᵢ bᵢ²)`
where `bᵢ` is bucket size** — and `P = (E,D,V)` makes this bucketing *possible*, since `(E,D)` is a natural
key. **This is a concrete engineering payoff from closing CB-2 that was unavailable while `P` was opaque.**

## What is NOT computable, and why

| Item | Why |
|---|---|
| `Uncertainty` | **NOT DEFINED** — no representation, no probability space in 1468 files (CB-3) |
| `Assurance` | **CONTRADICTORY** — six incompatible types, one self-referential (CB-1) |
| `GovernanceValid(K)` from `K` alone | **not a property of `K`** — requires History. Not a defect; a boundary. |

## Classification

| Claim | Class |
|---|---|
| 14 of 16 have executing decision procedures | **EXECUTED** |
| `SemanticallyValid` decidable | **FORMALLY DERIVED** — **not yet executed** |
| Bucketed contradiction complexity | **FORMALLY DERIVED** |
| `assessment` is under-specified, not incomputable | **FORMALLY DERIVED** |
| No arithmetic on `str` | **FORMALLY PROVEN** |
| Uncertainty non-computable | **OPEN — CB-3** |
| Assurance non-computable | **REFUTED as definable — CB-1** |
