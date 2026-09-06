---
artifact: 05-K-ATTACK
date: 2026-08-30
status: **`K=(𝒜,ℛ)` with a bare-triple `ℛ` is REFUTED as adequate — by construction, not by scoring**
---

# 05 · Attack on the Canonical K Model

Mandate §6: *do not score candidates by naming capabilities — construct them and test.*

## 1. The foundational-triple claim, verified

**Claim:** `K` is not foundational; the primitive foundation is `(ℰ, 𝒟, V_D)`.

**Verified — with a caveat the claim omits.** `𝒫 = {(E,D,V) | E∈ℰ, D∈𝒟, V∈V_D}` is genuinely
corpus-established (Q14, `20260826`, band A, pre-programme), properly typed, and `K` is genuinely
derived from it. **The ordering is right.**

**Caveat: `V_D` is measured in only 5 files corpus-wide.** A foundation asserted 5 times carries less
weight than the claim built on it. And Q14's *own* §4 records a contradiction in the value model
(*"Belongs to exactly one dimension … this is too restrictive"*).

**But the foundation is not the bottom.** `(ℰ,𝒟,V_D)` is the bottom of the *representation*. The
corpus has a layer beneath it — `W` and `Ω : W → O` (31.17–31.18) — which is the bottom of the
*referent*. **The claim "the foundational layer is `(ℰ,𝒟,V_D)`" is true of the representation and
false of the theory.** *(See `10` §2.)*

## 2. Constructed attack — `ℛ` as a bare triple

Executed (`attack.py` §A):

```
corpus relation fields   : ['E','E1','E2','Q','R','Sigma','T','tau']     (8, n-ary)
canonical relation fields: ('a1','a2','RelationType')                    (3, binary)
FIELDS DISCARDED         : ['E','Q','R','Sigma','tau']   (5 of 8)
```

**Test:** *is the claim "A₁ contradicts A₂" itself evidenced, dated and contestable?*

| Model | Answer |
|---|---|
| corpus `r = (E₁,E₂,T,R,Q,E,Σ,τ)` | **YES** — `E`, `Σ`, `τ` are fields of `r` |
| canonical `ℛ ⊆ 𝒜×𝒜×RelationType` | **NO** — the triple has no carrier for any of them |

> **A theory of governed knowledge in which a contradiction claim cannot itself be evidenced, dated,
> superseded or contested is not adequate to its own subject.** Contradiction is the *central*
> thing such a theory must reason about. **This refutes `ℛ`-as-triple on capability, not on score.**

**Downstream consequence, executed independently:** because `ℛ` has no `Σ`, and `Σ`'s signature is
`Assertion × Policy → (dir,str)` with no `ℛ` argument, **two assertions joined by an explicit
`contradicts` edge both evaluate to `('Supporting','Weak')`.** `K` can hold a fully-supported
inconsistency. *(`12-EXECUTION-RESULTS` §C.)*

## 3. Can `ℛ` be reduced to `𝒜`? — the circularity test

If relations were reducible to assertions, `ℛ` would not be primitive and the triple would be a
harmless encoding. **Test it.** Encode *"A₁ contradicts A₂"* as `P = (E,D,V)` with `E=A₁`,
`D='contradicts'`, `V=A₂`:

```
type-checks: yes
BUT: V must lie in V_D, the ValueSpace declared ON the dimension.
     V_D('contradicts') = the set of ALL assertions.
     So  V_D → 𝒫 → Assertion → 𝒜 → V_D.
=> CIRCULAR.
```

**`ℛ` is a genuine primitive and cannot be folded into `𝒜`.** Q14 reached the same conclusion by a
different route (*"it doesn't naturally represent … Assertion A contradicts Assertion B"*), and step
262.15 leaves it as an **open sub-test** with two undecided models. **Three independent routes, one
answer.**

## 4. Ontology vs representation — the distinction the claim never makes

| Reading of `K` | Holds? |
|---|---|
| **ontology** — claims what kinds of thing exist | **NO** — `K` has no category system; entities and dimensions arrive from layer 0 |
| **mathematical state** | **PARTLY** — `(𝕂, merge, ∅)` is a join-semilattice, but `merge` is set union and cannot deduplicate |
| **representation** | ✅ **the only defensible reading** — and it requires naming the referent, which is `W`, which no claimed model has |
| **abstraction** | yes, but the dropped detail is unrecorded — and this pass measures it: **5 relation fields + arity** |
| **domain projection** | asserted nowhere |

> **`K` is a representation whose referent is undeclared.** That is *why* adequacy could only ever be
> argued by scoring against a capability list, never by fidelity to anything. A scoring exercise
> ranks the candidates enumerated; it cannot detect a capability no candidate was scored on — which
> is exactly how five relation fields, ten status values and a whole referent layer went missing.

## 5. Thirteen operations, constructed and tested

| Operation | Holds in `K=(𝒜,ℛ_triple)`? |
|---|---|
| equality | ✅ five equalities; genuine equivalence relations |
| identity | 🔴 **contradictory** — `id=H(P,e,c,t,Π)` while `e.state` is declared mutable; executed: withdrawal re-keys the assertion and dangles every edge into it, violating `StructuralValid`'s own *no dangling* clause |
| membership | ✅ `O(1)` |
| merge | 🟡 union only — **no dedup**; `Π ∈ id` makes corroboration indistinguishable from duplication; merging never creates edges between semantic twins |
| deduplication | 🔴 **no operation exists** |
| update | 🟡 by replacement; no in-place semantics |
| conflict | 🟡 representable in `ℛ`, **invisible to `Σ`** |
| provenance | ✅ `Π` intrinsic; refined by step 265 (`π ∈ K`, object outside) |
| lineage | ✅ `Π ∘ ℛ_der*`, implemented, 47 tests |
| relations | 🔴 **5 of 8 fields and the arity lost** |
| uncertainty | 🔴 no carrier |
| missingness | 🔴 no `D_t` component — *not assessed* and *assessed, nothing found* are provably identical |
| temporal change | ✅ `t=[vf,vt)` bitemporal |
| replay | ✅ `fold(T, ∅, History)` |
| derivation | ✅ `ℛ_der` DAG |

**8 hold · 4 are defective · 3 are absent.**

## 6. Verdict

> **`K = (𝒜, ℛ)` is the right SHAPE and the wrong `ℛ`.**
>
> The two-component structure survives every attack: `ℛ` is provably not reducible to `𝒜` (§3), and
> the ordering `(ℰ,𝒟,V_D) → 𝒫 → Assertion → K` survives. **What does not survive is the reduction of
> `ℛ` to a bare triple**, which discards five corpus-established fields and the n-arity, and which
> causes at least three downstream failures (contradiction unevidenced, `Σ` blind to `ℛ`, relations
> undatable).
>
> **`K = (𝒜, ℛ)` with the corpus's own 8-tuple `ℛ` is a stronger candidate than either the claimed
> theory or any of the six scored alternatives — and it was never scored, because it was never
> enumerated.**
