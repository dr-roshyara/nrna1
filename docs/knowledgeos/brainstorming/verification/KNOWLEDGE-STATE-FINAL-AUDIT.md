---
artifact: KNOWLEDGE-STATE-FINAL-AUDIT
mandate: 20260830 re-verification §6
date: 2026-08-30
status: **`K = (𝒜, ℛ)` IS UNDER-SPECIFIED AND INTERNALLY CONTRADICTORY — 3 executed refutations**
---

# Final Audit of the Knowledge-State Shape

The claim under attack: `K = Set(Assertion) × ℛ` is correct because it scored highest (C/D = 15/19)
against six candidates over nineteen capabilities.

**A construction score is not a proof of adequacy.** A scoring exercise establishes *relative* rank
among the candidates enumerated; it establishes nothing about whether any candidate is sufficient,
and it cannot detect a capability that *no* candidate was scored on. **The three capabilities that
"no candidate could represent" are precisely the ones the scoring could not see** (see
`UNCERTAINTY-NONIDENTIFIABILITY-MISSINGNESS.md`).

---

## 1. The classification question, answered explicitly

The mandate requires stating what `K` **is**. The canonical theory never says, and the ambiguity is
load-bearing.

| Reading | Consequence if true | Held? |
|---|---|---|
| an **ontology** | `K` claims what kinds of thing exist | **NO** — `K` has no category system; entities and dimensions are given from layer 0 |
| a **mathematical state** | `K` is a point in a state space with an algebra | **PARTLY** — `(𝕂, merge, ∅)` is a join-semilattice, but `merge` is set union, which is not adequate (§3.2) |
| a **representation** | `K` encodes something; adequacy is judged against what it encodes | **THIS IS THE ONLY DEFENSIBLE READING** — and it requires naming the referent, which is `W`, which the theory does not have |
| an **abstraction** | detail is deliberately dropped | **YES, but the dropped detail is unrecorded** |
| a **domain-specific projection** | `K` is one view among several | asserted nowhere |

> **`K` is a representation whose referent is undeclared.** That is why adequacy could only be argued
> by *scoring against capabilities*, never by *fidelity to a referent*. Once `Ω : W → O` is present,
> adequacy becomes a real question with a real answer; without it, only ranking is possible.

---

## 2. Operation signatures — complete, with preconditions

`Merge : K × K → K` is *not sufficient*, as the mandate says. The full set:

| Operation | Signature | Preconditions | Postconditions | Determinism | Failure |
|---|---|---|---|---|---|
| `member` | `𝒜 × K → Bool` | — | — | total, det. | — |
| `add` | `K × Assertion ⇀ K` | `StructuralValid(K ∪ {a})` | `a ∈ 𝒜'` | det. | rejects on cycle / dangling |
| `merge` | `K × K → K` | none declared | `𝒜' = 𝒜₁∪𝒜₂`, `ℛ' = ℛ₁∪ℛ₂` | det., comm., assoc., idem. | **cannot fail — and that is the defect (§3.2)** |
| `supersede` | `K × 𝒜 × 𝒜 ⇀ K` | both in `𝒜`; `ℛ_sup ∪ {edge}` acyclic | edge added | det. | cycle |
| `contradicts` | `K × 𝒜 × 𝒜 → Bool` | same `(E,D)` bucket; intervals overlap | — | det. | — |
| `dedup` | — | — | — | — | **UNDEFINED — no such operation exists** |
| `Σ` | `Assertion × Policy → (dir,str)` | policy in force | pure | **conditionally** — the `str` rule is absent | — |
| `Γ` | `Assertion × GovCtx → Status` | — | pure | det. | — |
| `δ` | `𝕂 × Event ⇀ 𝕂` | `Pre(K,e)` | — | det. | **body undefined for the commit case (§3.4)** |
| `History` | `𝕂 → Histories` | — | external to `K` | det. | — |
| `Lineage` | `Π ∘ ℛ_der*` | — | — | det. | — |

**Three of eleven have no body**: `dedup` (does not exist), `Σ`'s strength rule, `δ`'s commit case.

---

## 3. Four executed refutations

*(Source: `kaudit.py`; every result below was produced by execution, not argument.)*

### 3.1 `id` is a hash over a **mutable** field — internal contradiction · **REFUTED**

The canonical field table declares `id` **derived** from `H(P,e,c,t,Π)` **and** `e.state` **mutable**.

```
id before withdrawal : 86a0330e2b65
id after  withdrawal : a9d84e7a43d8
SAME? False
R edge ('86a0330e2b65', …) now dangles: True
```

Withdrawing one evidence item **re-keys the assertion** and breaks every `ℛ` edge pointing at it —
violating `StructuralValid`'s own *"no dangling"* clause. **The theory's identity rule and its
mutability rule cannot both hold.** This is not a gap; it is a contradiction between two lines of the
same table.

*Corollary:* the audit's §7 amendment that added `state` to `e` **created** this contradiction, since
`state` is the field that must change on withdrawal.

### 3.2 `merge = union` cannot deduplicate — **REFUTED as adequate**

```
same proposition P=('svc','D.tls','1.3'), two sources -> 2 distinct assertions
ids: 86a0330e2b65  vs  f847660b1940
```

Because `Π` is inside `id`, **the same fact from two sources is two assertions**. Consequences:

- **Corroboration is indistinguishable from duplication.** Any sufficiency rule that counts
  supporting assertions over-counts; any that counts distinct propositions under-counts.
- `K` **grows without bound under re-observation** — observing the same true fact daily adds an
  assertion daily.
- Idempotence of `merge` is technically true and **practically vacuous**: it holds only for
  byte-identical assertions.
- **`ℛ` is not maintained by merge.** Merging `K₁` and `K₂` unions the edge sets; edges between an
  assertion in `K₁` and its semantic twin in `K₂` are **never created**, so the merged state
  silently loses the relation between the duplicates.

**"Two knowledge states with identical assertions but different provenance": NOT equal** — `Π` is
inside `id`, so they are different assertion sets. **This is the right answer for auditability and
the wrong answer for merge**, and the theory does not acknowledge the trade.

### 3.3 `Σ` never consults `ℛ` — contradiction is invisible to epistemic status · **NEW FINDING**

```
R = [('37abc098f790', 'ee2e8bd28b1d', 'contradicts')]
Sigma(c1) = ('Supporting','Weak')
Sigma(c2) = ('Supporting','Weak')
```

Two assertions joined by an explicit `contradicts` edge are **both `Supporting`**.
`Σ : Assertion × Policy → (dir,str)` **has no access to `ℛ` by its own signature.** Unless a policy
gate independently re-derives contradiction, `K` can hold a **fully-supported inconsistency** with
every epistemic status reading positive.

This was not on the audit's 12-attack list and is not in the canonical theory's limitations.
**`ℛ` and `Σ` are two independent accounts of epistemic force that are never reconciled.**

### 3.4 `δ` has nowhere to write the commit — **the central act is undefined**

```
c_t = {'cmd':'commit', 'target':…, 'by':'N:architect', 'policyVersion':3}
e_t = {'event':'Committed', 'target':…}
K1 is K0 : True          ← the commit produced NO state change
```

`Γ : Assertion × GovCtx → {Uncommitted, Committed, …}` is **derived and is not a component of `K`**.
`δ(K, e)` must therefore record a commitment **somewhere in `𝒜` or `ℛ`**, and neither has a carrier.
The system's central act — crossing the decision boundary — **has no state transition**.

**This is the direct structural consequence of `Γ` being derived rather than stored**, and it is the
same trade the audit noted in reverse: candidates E and F *stored* the status and scored **lower**
because storage admits an update anomaly. The scoring recorded the cost of storing and **not the cost
of not storing**, which is that `δ` becomes undefined.

---

## 4. Equality — the mandate's questions, answered

| Question | Answer | Basis |
|---|---|---|
| identical assertions, **different histories** — equal? | **YES** | History is external to `K` (executed probe 4) |
| identical assertions, **different provenance** — equal? | **NO** | `Π ∈ id` (executed probe 2) |
| Does identity belong inside `Assertion` or outside? | **Inside, and it must exclude mutable fields** | probe 1 proves the current placement is inconsistent |

**Consequence of the first answer, stated plainly:** `GovernanceValid` requires History; History is
not a function of `K`; therefore **`GovernanceValid` is not a predicate over the declared state
space.** Two `K`-equal states can differ in governance validity. The canonical theory calls this
*"a boundary, not a defect."* It is a **boundary that makes one of the four declared validity
predicates undefined on the state space it is declared over** — which is a defect in the *typing*,
whatever it is in the architecture.

---

## 5. The thirteen tested capabilities

| Capability | Held in `K=(𝒜,ℛ)`? |
|---|---|
| identity | ⚠️ defined but **inconsistent with mutability** (3.1) |
| equality | ✅ five equalities, `history ⊊ structural ⊊ semantic` — genuine equivalence relations |
| membership | ✅ `O(1)` |
| merge | ⚠️ **union only; no dedup, loses cross-state relations** (3.2) |
| supersession | ✅ maximal element of `ℛ_sup`, DAG |
| contradiction | ⚠️ representable in `ℛ` but **invisible to `Σ`** (3.3) |
| temporal evolution | ✅ `t=[vf,vt)` bitemporal |
| deduplication | ❌ **no operation exists** |
| provenance | ✅ `Π` intrinsic, verified |
| evidence association | ✅ `e` by reference |
| unknown | ❌ `P=(E,D,V)` requires `V ∈ V_D`; no bottom element declared |
| missingness | ❌ **no `D_t` component** — provably indistinguishable states |
| non-identifiability | ❌ **no `W`, no `Ω`** — not statable |

**8 of 13 hold, 2 are defective, 3 are absent.**

---

## 6. The minimal repair, stated as a recommendation only

> **`VERIFIER RECOMMENDS` — not `CORPUS ESTABLISHES`.**

```
K = (D_t, 𝒜, ℛ)          D_t ⊆ 𝒟, the recognised-dimension set
```

with `id = H(P, e_refs, c, t, Π)` where `e_refs` projects out the mutable `state`.

**What this buys, in dependency order:**
1. `D_t` makes *"not assessed"* decidable → closes the missingness gap (7 of 8 cases).
2. Projecting `state` out of `id` removes the identity/mutability contradiction → `ℛ` stops dangling.
3. A `dedup` operator becomes definable on `(P,c,t)` with `Π` and `e` **unioned** rather than
   duplicated → corroboration becomes distinguishable from duplication.

**What it does not buy:** non-identifiability still needs `(W, Ω)`, which is a layer beneath `K`, not
a component of it. Uncertainty still needs `U(H)`. Neither is repairable inside `K`'s shape.

**Nothing here is adopted. This is a proposal, and it is smaller than the three the corpus already
contains — which should be consumed first (`ES-005.4`).**
