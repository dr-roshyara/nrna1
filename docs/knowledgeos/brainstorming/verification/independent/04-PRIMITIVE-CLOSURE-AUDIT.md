---
artifact: 04-PRIMITIVE-CLOSURE-AUDIT
date: 2026-08-30
status: **A structurally open · B semantically open · C conditionally closed · D closed at an external act**
---

# 04 · The Four Closures, Assessed Separately

Mandate §4 forbids using "complete" as one word. Each closure is assessed on its own evidence.

---

## A · STRUCTURAL CLOSURE — *are all dependencies connected?*

### 🔴 **NOT CLOSED — three dangling primitives and one missing layer**

| Primitive | Depends on | Present? |
|---|---|---|
| `Observation` | `World` | 🔴 **`W` is in the corpus (31.17) and in no claimed model** |
| `Evidence` | `Qualify(Observation, Policy)` | 🔴 **`Qualify` has no body — 1 corpus hit, undefined** |
| `Command 𝒞` | `Authorize(𝒩, 𝒜, Policy)` | 🔴 **`𝒩` never defined; `Authorize` has no body** |
| `Γ` write | `δ(K, e)` | 🔴 **no carrier in `K`; commit executes as a no-op** |
| `Admissible` | its own definition | 🔴 **the term is never defined; only two rival laws are cited** |

**Plus one graph defect:** the dependency graph **contains 3 cycles** (`03` §2), so it is not a DAG
and cannot be topologically ordered. **Structural closure requires a DAG. It is not present.**

---

## B · SEMANTIC CLOSURE — *does every primitive have a precise meaning?*

### 🔴 **NOT CLOSED — measured, not asserted**

| Symbol | Distinct global senses in live use |
|---|---|
| **`Ω`** | **≥4** — sample space `(Ω,ℱ,P)` · Knowledge Space `Zero(K_t)=Ω\Represented` · observation function `Ω:W→O` · residual possibilities; plus `Ω_D`, `Ω_E`, `Ω_A` |
| **`θ`** | **≥5** — a numeric threshold · `f(G,λ)` · **the sample mean `(1/n)Σᵢ Xᵢ`** · a temporal quantity · `θ:G→…`, `θ:V_t→…` |
| **`E`** | Entity · Events · Evidence (the `E` conjunct of `DC(d)`) · `E₁,E₂` participants |
| **`V`** | Value · Vertices |
| **`Q`** | qualifiers in `r` · **epistemic sufficiency in `DC(d)`** · questions `Q_t` in Sārathi |
| **`Assurance`** | **≥5 incompatible definitions**, one self-referential; `REFUTED as definable` (CB-1) — **and still a conjunct of admissibility law 42.9** |

> **`θ` used for both a decision threshold and a sample mean is a measurement-theoretic error the
> corpus has the tools to catch and never applied to itself.** It cites Roberts on meaningfulness and
> its own `no averaging` law forbids arithmetic on ordinal scales — yet numeric thresholds
> (`0.1, 0.6, 0.73, 0.8, 0.95, 10, 10.2`) appear with **no scale type declared anywhere**.
> **`Threshold` has no type in the corpus.** *(New finding; on no prior register.)*

---

## C · COMPUTATIONAL CLOSURE — *can every operation be evaluated for a fully specified instance?*

### 🟡 **CONDITIONALLY CLOSED — and the condition is larger than previously stated**

**Executed and computable:** membership · structural/semantic equality · `StructuralValid` ·
contradiction detection (bucketed `O(n+Σbᵢ²)`) · supersession · replay · lineage (47 tests) ·
provenance lookup · policy conjunction (three-valued) · **`Zero` — total and terminating over finite
`R`** (8/8 tests, executed this pass).

**Not computable, with the reason in each case:**

| Operation | Why |
|---|---|
| `Qualify` | **no body** |
| `Authorize` | **no body**; codomain declared `𝒞` while 3 of 4 outcomes are not commands |
| `δ` (commit case) | **no carrier for `Γ` in `K`** |
| `Assessment` | **two rival signatures**; and its `Policy` parameter has no evidence→strength rule |
| `Σ.str` | **no order-preserving rule anywhere** |
| `Admissible` via 42.9 | **depends on `Assurance`, which is `REFUTED as definable`** |
| `Identifiable(g,Ω)` | ✅ **definable** (31.20) but needs `W`, `Ω` — absent from every claimed model |

`zero_reference.py` states its own boundary precisely, and this is the honest form of the whole
computability claim:

> *"Zero is computable, total and terminating over finite R, **RELATIVE to per-requirement
> evaluators**. What is NOT established: the evaluators themselves (Γ semantics, HumanAuthorization
> oracles) and η (EC construction)."*

> **The theory is computable down to its oracles, and the oracles are where the epistemics live.**

---

## D · GOVERNANCE CLOSURE — *who authorises changes to the governing regime?*

### ✅ **CLOSED — and it was closed before the verification programme began**

**Two independent terminations, both in the corpus:**

1. **Stratification (ratified).** `canonical-architecture-v0.2` `R-1` + `I-11` (GN-19, 2026-08-28):
   `Policy-as-content ∈ K_t` is distinguished from `Policy-in-force (versioned)`, and
   *"No in-force policy — AcceptancePolicy and constitution included — changes without a governed,
   versioned approval decision."* The model states: *"Self-modification of the in-force policy
   without governed approval is excluded by construction."*
2. **Externalisation (implemented).** *"The mechanism records authority; it does not grant
   authority."* Measured this pass: **132/132 grants across 22 work items carry a `humanActRef`.**

**Typed stopping point: `EXTERNALLY GOVERNED`.** The regress terminates outside the system at a
recorded human act, and the reflexive loop is severed inside the system by versioned stratification.

⚠️ **But the evidence is weaker than the count suggests.** `humanActRef` is a **free-text string** (83
prose / 49 path-bearing), with no schema, no identity and no verification. It records a *discipline*.
The estate's own record shows the discipline failing: `ASD-001` (a hand-composed append) and **two
distinct authority acts sharing one `grantId`** — *"a silent loss of an authority act."*

> **Governance closure is achieved at the level of the regime and NOT at the level of the individual
> act, because the act has no identity.** That is `TG-01` / `G3-05`, and it is a *structural* gap
> inside a *closed* governance regime.

---

## Summary

| Closure | Verdict |
|---|---|
| **A · Structural** | 🔴 **OPEN** — 5 dangling primitives, graph is not a DAG |
| **B · Semantic** | 🔴 **OPEN** — 6 symbols with ≥2 global senses; `Threshold` untyped |
| **C · Computational** | 🟡 **CONDITIONAL** — closed above the oracles, open at them |
| **D · Governance** | ✅ **CLOSED at the regime level** — open at the act level |
