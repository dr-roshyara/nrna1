---
artifact: D · KNOWLEDGE-STATE-CANONICAL-MODEL
mandate: 20260830_1918 §4, §5, §10
date: 2026-08-30
status: DERIVED · **and the ontology/representation distinction is now settled**
---

# The Canonical Knowledge State

## 1. §5 — What KIND of thing is `K = (𝒜, ℛ)`?

**This is the mandate's sharpest question, and the EKP answers it empirically.**

The running EKP was parsed into `(𝒜, ℛ)`: **|𝒜| = 37, |ℛ| = 51**, relation types `related_to` (46),
`derived_from` (2), `requires` (3). Its assertion type is:

```
EKP assertion = (knowledge_id, title, bounded_context, status, authority, knowledge_type, owner)
```

**Compare the theory's:** `(id, P, e, c, t, Π)`.

| Theory field | In EKP? |
|---|---|
| `id` | **YES** — `knowledge_id`, unique + pattern, ERROR-enforced |
| `c` | **YES** — `bounded_context`, enum-enforced |
| `P` structured | **NO** — `title` is prose |
| `e` evidence | **NO** |
| `t` temporal validity | **NO — the schema has no temporal field at all** |
| `Π` provenance | **NO** — `authority` is a trust rank, not an origin |

> **VERDICT: the EKP's `(𝒜,ℛ)` is `E` — a bounded-context-specific model — and `B` — a representation.**
> **It is NOT `C` (a projection of the theory's `K`), because a projection cannot invent the fields it
> lacks.** It is a *different* `K` over a *different* assertion type: **governed documents**, not
> epistemic assertions.
>
> **Therefore: `ontology ≠ implementation representation`. The mandate's central warning is not merely
> avoided — it is empirically demonstrated.** The theory's `K` and the EKP's `K` share a *shape*
> — set of identified items plus typed relations — and differ in their *carrier*.

**Can the EKP's `(𝒜,ℛ)` reconstruct the nine items §5 lists?**

| Item | Reconstructible from EKP? |
|---|---|
| epistemic qualification | **NO** — absent |
| evidence references | **NO** — absent |
| context | **YES** — `bounded_context` |
| temporal validity | **NO** — absent |
| contradiction | **NO** — needs `(E,D,V)` and `t` |
| supersession | **YES** — `supersedes`/`superseded_by` with inverse |
| provenance | **NO** — `authority` is trust, not origin |
| identity | **YES** — `knowledge_id` |
| governance boundaries | **YES** — `bounded_context` + `boundary_consistency` |

**4 of 9 reconstructible. The EKP is an adequate governance-document store and an inadequate epistemic
store — and it was never built to be the latter.**

> **The most instructive fact:** a working knowledge platform was built **without** temporal validity,
> evidence, provenance or epistemic status. **The theory must explain that omission, not ignore it.** The
> honest explanation is that EKP's assertions are *documents under governance*, for which lifecycle and
> authority suffice; the theory's assertions are *claims under evidence*, for which they do not.

## 2. §4 — the five tests

### State-membership test — *can two histories yield the same `K` while differing in `X`?*

| `X` | Two histories differ in `X`, same `K`? | Verdict |
|---|---|---|
| order of assertion | **YES** — executed: `F(Hr) = F(Hs)` with reversed order | **historical metadata** |
| actor | **YES** — two architects, identical result | **historical metadata** |
| wall-clock of the transformation | **YES** | **historical metadata** |
| the assertions themselves | **NO** | **state-defining** |
| asserted relations | **NO** — executed counterexample (supersession vs independent observation) | **state-defining** |
| `Σ` | **YES** — recomputable from `e` | **derivable, not state-defining** |

### Transformation-sufficiency test
`F(H1) = F(H2) ⇒ F(T(H1,i)) = F(T(H2,i))` — **executed, congruent for every input tested.** See artifact F.

### Identity test — five distinct notions, all defined

| Notion | Definition | Executed |
|---|---|---|
| object identity | same reference | trivial |
| **structural equality** | `(𝒜₁,ℛ₁) = (𝒜₂,ℛ₂)` including `Π` | **YES** — equivalence relation proven |
| **semantic equivalence** | equal on `(P,c,t)`, ignoring `id,e,Π` | **YES** |
| **observational equivalence** | agree on every `Query` | derived from structural |
| **history equivalence** | `History(K₁) = History(K₂)` | **YES — strictly finer than all the above** |

`history equivalence ⊊ structural ⊊ semantic`. **Executed: `History(K) ≠ K`.**

### Membership test
`a ∈ K ⟺ a ∈ 𝒜`. Decidable, `O(1)`. **`ℛ` endpoints reference `id`s, so relation membership is
`(f,t,ty) ∈ ℛ` with `f,t ∈ ids(𝒜)` — enforced by `StructuralValid`.**

### Construction test — **an explicit finite valid `K`, executed**

```
D_VER = Dimension("Version", ValueSpace=("3.68","3.69","3.70"), scale=ordinal)
A1 = ((Nexus,Version,3.69), {ev c73b3d27:supports:active}, production, [2026-01-02,∞), nexus-api)  id=e2c73b5500
A2 = ((Nexus,Version,3.70), {ev …:supports:active},        production, [2026-06-01,∞), changelog)  id=8663022439
A3 = ((Nexus,Version,3.69), {ev c73b3d27:supports:active}, staging,    [2026-01-02,∞), nexus-api)  id=ec1a3ecb46
A4 = ((Nexus,Version,3.68), {ev …:contradicts:active},     production, [2026-01-03,∞), legacy-probe) id=126ee23104

K = ( {A1,A2,A3,A4}, {(8663022439, e2c73b5500, supersedes)} )
StructuralValid(K)   = (True,'ok')
SemanticallyValid(K) = (True,'ok')
```
**The definition is complete: a finite valid `K` exists and was constructed.**

## 3. §10 — minimality, tested only now that the model executes

| Component | Removal consequence | Verdict |
|---|---|---|
| `𝒜` | nothing remains | **NECESSARY** |
| `ℛ` | supersession indistinguishable from independent observation (executed) | **NECESSARY** |
| `id` (in Assertion) | supersession, dedup, replay inexpressible | **NECESSARY** |
| `P` | no content | **NECESSARY** |
| `e` | assessment has no input; withdrawn≠invalidated collapses | **NECESSARY** |
| `c` | `contradicts(A1,A3)` becomes true — **executed false positive** | **NECESSARY** |
| `t` | contradiction loses overlap test | **NECESSARY** |
| `Π` | origin unreconstructible at `t=0` | **NECESSARY** |
| `Σ` | recomputable from `e` | **DERIVED** |
| History | executed counterexample | **EXTERNAL** |
| Policy, Authority | `T` parameters | **EXTERNAL** |
| `𝒵` Zero, `ℒ` Lord Candidate | no operation consumes them | **REDUNDANT** for the specified `𝒯` |

> **The model is executable, so removal is testable. The phrase MINIMAL KERNEL is therefore earned —**
> but only in the sense step 254 names: **`Minimality(K | 𝒯)`, relative to the transformation set.**
> **Not ontological necessity.** A richer `𝒯` could force more.

## 4. Canonical statement

```
K = (𝒜, ℛ)
𝒜 = Set(Assertion)          Assertion = (id, P, e, c, t, Π)
                            P = (E, D, V),  E∈ℰ, D∈𝒟, V∈V_D
                            e ⊆ Evidence,   Evidence = (ref, polarity, state)
                            c ∈ Context,    t = [vf, vt),   Π ∈ Origin
ℛ = ℛ_sup ⊎ ℛ_ref ⊎ ℛ_der ⊎ ℛ_supp ⊎ ℛ_res ⊎ ℛ_rel
    DAGs: sup, ref, der      non-transitive: supp, res      symmetric: rel
DERIVED, never stored:  Σ · equals · contradicts · conflictsWith
EXTERNAL:               History · Policy · Authority · Γ
```

**`ℛ_rel` (symmetric `related_to`) is new — it comes from the EKP, which uses it for 46 of its 51 edges.**
It is symmetric, non-transitive, and **acyclicity must NOT be required of it** — executed: the real EKP graph
has a `related_to` cycle (`KNOWLEDGE-CONSTITUTION ↔ META-LIFECYCLE`), which is correct behaviour for a
symmetric relation, not a defect. **The theory had no symmetric relation family until the implementation
supplied one.**
