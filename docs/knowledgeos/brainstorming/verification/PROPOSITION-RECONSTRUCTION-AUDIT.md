---
artifact: C · PROPOSITION-RECONSTRUCTION-AUDIT
mandate: 20260830_1918 §7
date: 2026-08-30
status: **`P=(E,D,V)` JUSTIFIED AND RETAINED** — with one measurement correction
---

# Proposition — Reconstruction Audit

## 1. The mandate's three suspicions, answered directly

§7 asks whether `V` is validation, whether `E` is evidence or epistemic state, and whether `D` is data,
domain or dimension. **The corpus settles all three unambiguously** — `question-14`, 2026-08-26, a table of
eleven typed objects:

| Symbol | **Is it…** | **Answer** | Source |
|---|---|---|---|
| `E` | evidence? epistemic state? | **NEITHER — `Entity`**, "anything that can be the subject of knowledge" | Q14 §2.1 |
| `D` | data? domain? dimension? | **`Dimension`**, "a semantic axis of variation or classification" — *domain* is a **field of** `D`, not `D` itself | Q14 §3.1 |
| `V` | validation? | **NO — `Value`**, "a specific position on a dimension" | Q14 §4.1 |

> **All three suspicions are resolved against the intuitive readings.** This is exactly why §7 forbade
> defining the letters by their names: **`E` is not Evidence and `V` is not Validation**, though both would
> have been natural guesses — and `Evidence` has its own separate symbol `ℰᵥ` in the same table.

## 2. Full specification

| Object | Type | Primitive or derived? | Observable? | Computable? | Role in `K` | Role in `T` |
|---|---|---|---|---|---|---|
| **Entity `E`** | element of `ℰ` | **primitive** | yes — entities are named | membership decidable | inside every assertion | subject of the operation |
| **Dimension `D`** | `(ID, Name, ValueSpace, Type, Domain)` | **primitive**, but *structured* | yes — declared | `admits(v)` decidable | inside every assertion | constrains admissible values |
| **Value `V`** | element of `V_D` | **derived from `D`** — `V` has no meaning without its dimension | yes | `V ∈ V_D` decidable | inside every assertion | the thing that changes |
| **Proposition `P`** | `(E,D,V)` | **derived** — a triple over the three | yes | `WellFormed` decidable | content of every assertion | operand |

**Well-formedness is a real gate, executed:**
```
D_VER = Dimension("Version", ("3.68","3.69","3.70"), scale="ordinal")
P1 = ("Nexus", D_VER, "3.69")   WellFormed = True
T: assert (Nexus, Version, "9.99")  ->  REJECTED(structure): V∉V_D
```
> **`WellFormed(P)` is not decorative — it rejects real inputs at the transformation boundary.** Executed in
> artifact H. This is the predicate that became available only when `P` was defined; before that, `T` could
> not reject an ill-formed proposition because "ill-formed" had no meaning.

## 3. The measurement correction — the one place I contest Q14

Q14 §3.4 classifies value-space types as **Nominal / Ordinal / Interval / Ratio** — correct measurement
theory, and correctly attached to the **Dimension** rather than the Value. **The scale type is a property of
the axis, which is exactly what licenses or forbids arithmetic.**

**But Q14 gives `version numbers` as its example of an INTERVAL scale. That is wrong:**

- `3.69 − 3.68` is not a meaningful magnitude — there is no unit of "version".
- Version ordering is **not** numeric ordering: `3.10 > 3.9` as versions, `3.10 < 3.9` as decimals.
- Interval requires equal intervals to mean equal differences. Versions have no such property.

> **Version is ORDINAL at best.** In my executed model I declared `scale="ordinal"` accordingly.
> **This matters because `Nexus.Version` is the corpus's most-used worked example** — an inadmissible-
> arithmetic hazard sits in the canonical illustration. **VERIFIER RECOMMENDATION — contests the corpus.**

## 4. Verdict on `P = (E,D,V)`

> **JUSTIFIED AND RETAINED.** It is not rejected.

It survives every test §7 sets: each symbol has a type; two are primitive and one is derived; all are
observable and computable; well-formedness is decidable and **executed as a real rejection**; the structure
participates in `K` as assertion content and in `T` as the operand; and it carries measurement discipline
that no rival candidate carries.

**Engineering payoff, concrete:** `(E,D)` is a natural bucketing key, which reduces contradiction detection
from `O(n²)` to `O(n + Σᵢbᵢ²)`. **This was unavailable while `P` was opaque.**

**What is NOT justified:** the eleven rival formulations (`(V_P,E_P)`, `(L,μ)`, `(S,ρ,O,Γ)`, `(E,≺)`,
`(V,E_P)`, `Proposition = Subject`, `proposition = assertion`). `(S,R,O)` is an isomorphic rename and is
subsumed rather than rejected.

## 5. Residual defect

**`E` and `V` are each doubly bound in the corpus** — `E` = Entity (Q14) and Events (025x); `V` = Value
(Q14) and Vertices (207). **A terminology defect, not a formal gap.** Recorded in artifact J.

## 6. Classification

| Claim | Class |
|---|---|
| `E`=Entity, `D`=Dimension, `V`=Value | **CORPUS ESTABLISHES** — Q14 §§2–5 |
| `P` excludes status, evidence, time, provenance | **CORPUS ESTABLISHES** — Q14 §5.3 |
| Scale type on the Dimension | **CORPUS ESTABLISHES** — Q14 §3.4 |
| `WellFormed(P)` rejects real input | **ENGINEERINGALLY VERIFIED** — executed |
| `(E,D)` bucketing reduces complexity | **MATHEMATICALLY VERIFIED** |
| **Version is not an Interval scale** | **CONTRADICTED — verifier contests the corpus** |
| `E`/`V` double binding | **OPEN** — terminology |
