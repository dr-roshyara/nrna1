---
artifact: D285-4 · TRANSFORMATION TAXONOMY
status: **6 classes · 4 kernel-relevant · the five-Θ algebra REJECTED on type-checking**
format: 7-section D285-x template (HPA review, 2026-08-31) · equality specified per the frozen protocol
---

# D285-4 · Transformation Taxonomy

## 1. The taxonomy (not an operator algebra)

| Class | Question | Kernel-relevant? | Corpus anchor |
|---|---|---|---|
| **Epistemic** | does the knowledge state change? | ✅ | `δ(K,e)`; `Assert/Retract/Supersede/Merge` |
| **Operational** | does an operation occur? | ✅ | `𝒪_sem` five families (272A) |
| **Evidential** | does evidence change? | ✅ | `Qualify`, `LinkEvidence`, `e.state` |
| **Normative** | does policy/authority status change? | ✅ | `Policy` versioning; `I-11` |
| **Agent / Knower** | does the actor's understanding change? | 🔴 **outside** | `Knower ∉ K` (D285-2) |
| **External world** | does the domain change? | 🔴 **outside** | `W`, `Ω` — not in any claimed model |

**Only the first four enter the kernel.** The last two are real and are outside — which is the
`Knower`/`World` boundary, stated once and not repeated per-operator.

## 2. The pipeline, with the boundaries the corpus already enforces

```
Intent → Guidance → Decision → Operation → Authorization → Execution → Transition → Result → Observation
                                            │                            │
                    Zero/Lord/Sārathi ∉ ────┘        only validated ─────┘
                    State Mutation Boundary          domain events mutate K
```

**Both annotations are standing corpus invariants, not proposals.**

## 3. Θ-algebra — **REJECTED**

The proposal `Θ_X ∘ Θ_I ∘ Θ_K ∘ Θ_T ∘ Θ_A` was type-checked as Step 286 §16 requires.

| Operator | Claimed domain → codomain | Type-checks in the chain? |
|---|---|---|
| `Θ_A` (action) | `𝕂 × 𝒪 ⇀ 𝕂` | — |
| `Θ_T` (transition) | `𝕂 × Event ⇀ 𝕂` | ⚠️ needs an `Event`, which `Θ_A` does not return |
| `Θ_K` (knower) | `𝒩 → 𝒩` | 🔴 **codomain `𝒩`, not `𝕂` — cannot compose** |
| `Θ_I` (inquiry) | `Q_t → Q_t` | 🔴 different carrier again |
| `Θ_X` (external) | `W → W` | 🔴 `W` is not in the model at all |

> **The composition cannot be type-checked: four different carriers (`𝕂`, `𝒩`, `Q_t`, `W`) appear in
> one chain.** Per §16's instruction — *"If it cannot be type-checked: reject the composition. Do not
> invent types to rescue it"* — **REJECTED (`RX`).**
>
> **What survives is the taxonomy, not the algebra.** Five names for transformations across five
> carriers is a **classification**; calling it a composable operator family was the error.

## 4. Dependencies among the four kernel classes

```
Evidential  ──→  Epistemic        (evidence must qualify before it can change K)
Normative   ──→  Operational      (policy gates which operations are admissible)
Operational ──→  Epistemic        (an operation is the occasion of a transition)
Epistemic   ──→  (History)        external record
```

**No cycle among the four** — the cycles found earlier
(`Evidence → Policy → K → Assertion → Evidence`) run through **objects**, not through these classes.
Recorded so the two graphs are not confused.

---

## Template conformance (HPA mandate, 2026-08-31)

### 1 · Property Statement
Six transformation classes exist; four are kernel-relevant. The five-Θ composition `Θ_X ∘ Θ_I ∘ Θ_K ∘ Θ_T ∘ Θ_A` is not well-formed.

### 2 · Trivial vs Substantive
**Trivial:** that transformations can be grouped. **Substantive:** that the grouping is a **taxonomy** and not a **composable algebra**.

### 4 · Qualification
The four kernel classes are acyclic *as classes*; the cycles found elsewhere run through **objects**, not classes. The two graphs must not be conflated.

### 5 · Equality Specification ⭐
**NONE REQUIRED — and stating that is part of the discipline.** The Θ rejection is a **type-checking** result (four carriers: `𝕂`, `𝒩`, `Q_t`, `W`), not an equality claim. Asserting an equality here would be a category error.

### 6 · Independence
**No Gītā input** for the taxonomy. The Θ proposal *came from* the lens and **fails on typing** — an independence test the lens loses.

### 7 · Classification (7-way, per the frozen protocol)
**mathematical:** Θ-algebra `RX` · **architectural:** taxonomy `R6` · **DDD:** four kernel classes map to service/command/event/policy layers · **analogy:** the five-fold naming is `R2` at best · **corroboration:** none · **unresolved:** none · **governance:** none
