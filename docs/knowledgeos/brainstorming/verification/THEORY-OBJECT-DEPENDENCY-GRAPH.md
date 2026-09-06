---
artifact: THEORY-OBJECT-DEPENDENCY-GRAPH
mandate: 20260830 §2
date: 2026-08-30
status: **the foundational layer is NOT `K` — it is `(ℰ, 𝒟, V_D)`**
---

# Theory Object Dependency Graph

## 0. The headline result

> **`K` is not foundational. It is derived, three levels up.**
> The true foundational layer is the **typed primitive triple `(ℰ, 𝒟, V_D)`** from Q14 — entities,
> dimensions, and dimension-indexed value spaces. Everything else, `K` included, is built on it.
>
> **The earlier circularity `T → K → Invariants → T` was an artefact of treating `K` as primitive.**
> Once `K` is derived and invariants are typed as **predicates over `𝕂`** rather than components of it,
> the cycle disappears. **Predicates over a structure are not part of the structure.**

## 1. Layers

```
LAYER 0  PRIMITIVE      ℰ (entities)   𝒟 (dimensions)   V_D (value spaces)   Time   Origin
                        └── base case: given, not defined in terms of anything else
LAYER 1  CONTENT        P = (E,D,V)                    Observation
LAYER 2  QUALIFIED      Evidence = (ref,polarity,state)          Context   Identity
LAYER 3  CLAIM          Assertion = (id,P,e,c,t,Π)
LAYER 4  STATE          𝒜 = Set(Assertion)      ℛ ⊆ 𝒜×𝒜×Type      K = (𝒜,ℛ)
LAYER 5  NORM           Policy = (id,ver,Gates,Interval,Resolution)      Authority
LAYER 6  DYNAMICS       T : 𝕂×Op×Policy×Authority ⇀ 𝕂×Outcome
LAYER 7  DERIVED        Assessment → Σ    Validation    Γ    Invariants (predicates over 𝕂)
LAYER 8  RECORD         History = 𝕂→Histories     Lineage = Π ∘ ℛ_der*
```

**Every arrow points upward. No arrow returns to a lower layer.**

## 2. The nineteen objects

| Object | Type | Primitive/Derived | Kind | Depends on | Base case? | Computable |
|---|---|---|---|---|---|---|
| `Time` | ordered set | **PRIMITIVE** | metadata | — | **yes** | yes |
| `Origin` | opaque label | **PRIMITIVE** | metadata | — | **yes** | yes |
| `Content` (`ℰ,𝒟,V_D`) | sets | **PRIMITIVE** | state | — | **yes** | membership decidable |
| `Observation` | `(sensor,at,reading)` | primitive | event | Time | yes | yes |
| `Evidence` | `(ref,polarity,state)` | derived | metadata | Observation | yes | yes **except the qualification predicate** |
| `Context` | named scope | primitive | metadata | — | yes | yes |
| `Provenance Π` | Origin | **primitive, intrinsic** | metadata | Origin | **yes — t=0-safe** | yes |
| `Identity` | `H(P,e,c,t,Π)` | derived | metadata | all assertion fields | yes | yes |
| `Assertion` | 6-tuple | derived | state | P, e, c, t, Π, id | yes | yes |
| `K` | `(𝒜,ℛ)` | **DERIVED** | state | Assertion | yes | yes |
| `Policy` | 5-tuple | primitive **at layer 5** | policy | — | **yes — but see G-P1** | yes |
| `Authority` | competence | primitive | policy | — | yes | **binding UNDEFINED** |
| `Invariant` | **predicate over `𝕂`** | derived | assessment | K | yes | yes |
| `Transformation T` | partial fn | derived | operation | K, Policy, Authority | yes | yes |
| `Assessment` | `P×e×c×Policy → Σ` | derived | assessment | Policy, Evidence | yes | yes, given a policy |
| `Σ` | `(dir,str)` | **DERIVED** | assessment | Assessment | yes | yes |
| `Γ` | GovState | derived | assessment | Authority acts | yes | yes |
| `Decision` | `Determination×Authority → commitment` | derived | event | Assessment, Authority | yes | yes |
| `History` | `𝕂 → Histories` | derived | metadata | T | yes | yes |
| `Lineage` | `Π ∘ ℛ_der*` | **DERIVED** | relation | Π, ℛ | yes | `O(n+m)` |

## 3. Circularity audit

| Suspected cycle | Verdict |
|---|---|
| `T → K → Invariants → T` | **DISSOLVED** — invariants are predicates *over* `𝕂`, not components *of* it. `T` reads `K`; invariants read `K`; neither reads the other |
| `Σ → K` | **NOT PRESENT** — `Σ` is layer 7, `K` is layer 4; `Σ ∉ K` |
| `contradicts → ℛ → contradicts` | **NOT A CYCLE** — `contradicts` *reads* `ℛ`; `ℛ` is stored, never computed from `contradicts` |
| `Valid → contradicts → K → Valid` | **NOT PRESENT** — `StructuralValid` is purely structural and never invokes `contradicts` |
| `Policy → T → Policy` | **NOT PRESENT for evaluation.** **BUT: a policy CHANGE is a transformation, which needs a policy** — this is the **G-P1 reflexivity loop**, and it is real. It terminates only at an **adopted constitution** |
| `Lineage → Π` and `Π` primitive | **TERMINATES** — `Π` is layer 0; `ℛ_der*` bottoms out at roots carrying `Π` |

> **Exactly one genuine loop exists in the entire theory, and it is at the governance level, not the
> semantic level: `Policy → T → Policy`.** Everything else is a strict order.

## 4. Why the base case matters

Every derived object bottoms out at layer 0, and **layer 0 is finite and enumerable**: a fixed entity set,
a fixed dimension set with declared value spaces, an ordered time, and opaque origin labels.
**That is what makes `WellFormed`, `StructuralValid`, membership and equality all decidable** — they reduce
to finite set membership.

> **The single most consequential structural fact: `V ∈ V_D` is decidable because `V_D` is declared.**
> Q14's decision to attach `ValueSpace` to `Dimension` is what makes the whole theory computable.
> Without it, `WellFormed(P)` is undecidable and `T` has no structural guard.
