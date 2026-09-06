---
artifact: 09-PROVENANCE-LINEAGE-ATTACK
date: 2026-08-30
status: **SUSTAINED — the strongest result in the theory, and it survives every attack**
---

# 09 · Attack on Provenance and Lineage

## 1. The `t=0` counterexample — a genuine proof

At `t=0`, `History` is empty. An **imported** assertion has an origin, and **no transformation
produced it**. Therefore:

- under a model where provenance **is** transformation history, the origin is **unrecoverable**;
- therefore provenance **cannot be** transformation history;
- therefore `Π` must be **intrinsic** to the assertion.

**This is a proof, not a preference**, and it is the load-bearing result of the whole provenance
model. It survives.

**Independent corpus confirmation, arrived at separately:** step 265 (*PROVENANCE PLACEMENT*):
> *"Internal transformation history cannot replace external provenance **because it is empty at the
> initial state**."*
> `π ∈ K` · `ProvenanceObject ⇏ K` · **`Provenance ≠ History(T)`**

## 2. The four objects — verified distinct, each independently anchored

| Object | Definition | Anchor | Verified |
|---|---|---|---|
| **`Π` AssertionOrigin** | where the claim came from; intrinsic; `t=0`-safe | the `t=0` proof + step 265 | ✅ |
| **EvidenceProvenance** | chain of custody of one evidence item | `230.15` — `provenance` is a **required field** of `E_q` | ✅ quotation verbatim |
| **`History(T)`** | ordered record of system transformations; starts at `t=0` | executed `History(K) ≠ K` | ✅ |
| **MessageProvenance** | `(correlationId, causationId)`, integration events | **measured: 18 PHP files** under `app/Contexts/**` | ✅ |

**Four objects, four names, no shared word.** This is the only place in the theory where an overload
was fully resolved, and it is the model for how the other six should be (`04` §B).

## 3. Refinement this pass adds

Step 265 sharpens the placement in a way no prior artifact recorded:

```
π ∈ K                    a stable provenance REFERENCE lives in the assertion
ProvenanceObject ⇏ K     the detailed provenance GRAPH lives in a provenance/audit subsystem
```

**This is compatible with `Π` intrinsic and strictly more precise**: what is intrinsic is the
*reference*, not the *object*. The claimed theory says "`Π` is intrinsic" without the distinction,
which invites an implementer to embed the whole graph in `K`.

## 4. `Lineage = Π ∘ ℛ_der*` — verified, with one consequence the claim omits

The composition is correct: origin, then transitive closure over derivation edges. Implemented as
`GovernanceLineageGraph`, **47 tests**.

**But `ℛ_der` is a bare triple** (`05` §2). Therefore:

> **Lineage edges carry no evidence, no time and no epistemic status.** *"A was derived from B"* is
> an unevidenced, undated, uncontestable claim in the claimed model — while the corpus's own
> relation `r = (E₁,E₂,T,R,Q,E,Σ,τ)` would carry all three.
>
> **The lineage *formula* is right; the *edges it composes over* have been stripped.**

## 5. Is lineage intrinsic, external, historical, derived, or composite?

**COMPOSITE, and the corpus supports the decomposition exactly:**

| Part | Nature | Lives |
|---|---|---|
| `Π` | **intrinsic** (reference) | in the assertion |
| provenance object | **external** | in a provenance subsystem (step 265) |
| `ℛ_der*` | **derived** | over `K`'s relation set |
| `History(T)` | **historical** | outside `K` |

**No single classification is correct, and the theory's four-way split is right.**

## 6. Verdict

> ✅ **SUSTAINED — unchanged by attack.** The `t=0` argument is a proof; the four objects are
> genuinely distinct and independently anchored; step 265 refines the placement rather than
> contradicting it; `MessageProvenance` is measured in running code.
>
> 🟡 **One inherited defect:** lineage composes over relation edges that have been stripped of
> evidence, status and time. **The defect is in `ℛ` (`05`), not in the provenance model.**
