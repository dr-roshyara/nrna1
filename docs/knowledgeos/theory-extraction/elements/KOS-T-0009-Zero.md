# `KOS-T-0009` — `Zero`

**`[EXP]` · stress case: *cross-domain — mathematical vs KnowledgeOS meanings*.**
**Adjudicates nothing.**

**Scope:** as `KOS-T-0007`.

| | |
|---|---|
| **kind** | 🔴 **per definition** — `D-01`–`D-04` are `operation`/`relation` (predicates); `D-06` is a **`constant`** (an algebraic identity element). ⛔ **not assignable at record level** |
| **Status** | `[CT]` · ⭐ **and `[RF]` on two definitions** — see below |
| **Grounding** | **mixed** — `D-02`–`D-04` architecturally-grounded (four implementations); `D-06` `philosophical/literature-correspondence-only` |

## Definitions

| ID | definition | in-scope | kind |
|---|---|---:|---|
| `D-01` | `Zero(K, G, EC)` / `Zero(K, Q, C, EC)` — a **predicate over a normative comparison** | **261** | `relation` |
| `D-02` | `Zero ⟺ Δ_t = ∅` | **36** | `relation` |
| `D-03` | `Zero_strict` · `Zero_weak` · `Zero_reasoned` · `Zero_kleene` — **four non-equivalent readings**, chain `strict ⇒ reasoned ⇒ weak` | **32** | `relation` ×4 |
| `D-04` | `Zero_{T,Π}(S;D) ⟺ Π(T(D)) = Π(T(E_S(D)))` — **contract-relative eliminability** | **76** | `relation` |
| `D-05` | `ZeroLens(S,π) → B` — a **lens producing a boundary**; `Gap = π_Q(B)` | **55** | `operation` |
| **`D-06`** | ⭐ **`0_i ∈ 𝒟_i`** — an **algebraic zero ELEMENT** in a dimension | **2** | ⭐ **`constant`** |

## 🔴🔴 Two definitions are REFUTED, and the schema has nowhere to put that

The corpus records: of `Zero`'s seven candidate readings, **`Zero`-as-a-**state** and
`Zero`-as-a-**missingness-representation** are REFUTED** — *"`Zero` is coarser than the four-way
unknown taxonomy"* (12 in-scope files). And `Gap`/`DetectGap` is **derivable 12/12**, so `Zero`
behaves as a **predicate, not a primitive**.

$$\boxed{\begin{array}{c}\textbf{v2 carries epistemic status on the ELEMENT. } Zero \textbf{ needs it PER DEFINITION:}\\ \textbf{some readings are } \mathtt{[RF]} \textbf{ while others are live.}\end{array}}$$

⚠️ `[PROP]` **per-definition status field.** **Not applied** — stress report §"newly demanded status".

## ⭐ Cross-domain: `D-06` is a **different candidate**, preserved as such

`0_i ∈ 𝒟_i` is an **algebraic identity element**; `D-01`–`D-05` are **epistemic predicates**. The
corpus itself separates them — *"integrating an algebraic zero element … as an equivalence to
eliminability would re-introduce"* the conflation it had just removed.

$$\boxed{\textbf{Disposition 2 — different candidate sharing the name. ID reserved } \mathtt{KOS\text{-}T\text{-}0010}, \mathtt{kind: constant}.}$$

⛔ **Not forced into one concept.** ⚠️ And `0` as the **number** is disposition 4 throughout.

## Relationships

| pair | value |
|---|---|
| `D-01` ~ `D-02` | **`unresolved_equivalence`** — ⚠️ the corpus records `Zero ≠ Δ` explicitly (0485), so these may **conflict**; evidence insufficient to assert `contradiction` |
| `D-03`'s four | ⭐ **`refinement` chain, EVIDENCED** — `strict ⇒ reasoned ⇒ weak`, derived over **1 620 000 assignments, 0 counterexamples** |
| `D-04` ~ `D-01` | **`unresolved_equivalence`** — different parameter sets (`T,Π,S,D` vs `K,G,EC`) |
| `D-05` ~ `D-01` | **`unresolved_equivalence`** — `ZeroLens` produces a boundary; `Zero` is a predicate. ⚠️ possible **operation-vs-relation** kind mismatch |
| `D-06` ~ all | **disposition 2** — different candidate |

⭐ **`D-03` is the first EVIDENCED `refinement` chain in any record** — with a measured derivation.

## Implementation — **four competing definitions implemented side by side**

| ID | level | provenance | where |
|---|---|:--:|---|
| `D-03` | **`result-produced-on-it`** | **`stipulated`** | `kos12/phaseC.py` — **`zero_strict`, `zero_weak`, `zero_kleene`, `zero_reasoned` as FOUR functions** |
| `D-01` | **`operation-runs`** | **`stipulated`** | `kos/inquiry.py: def Zero(K,Q,C,EC,E=None)` |
| `D-04` | **`result-produced-on-it`** | **`stipulated`** | `zero-algebra/**` — the whole estate |
| `D-05` | **`type-exists`** | **`stipulated`** | `kos12/zerolens.py: class Boundary` |
| `D-02`, `D-06` | **`none`** | `n/a` | `n/a` |

🔴 ⭐ **A new implementation pattern:** four **competing** definitions are implemented
**simultaneously and deliberately, as comparison arms.** v2 assumed one implementation per definition
or none; it has no way to record *"implemented in order to be compared against its rivals."*
`[PROP]` stress report §F. **Not applied.**

## Latest · dependencies

**mention** 2026-09-06 · **implementation** 2026-09-02 · **refinement** the algebraic-zero integration
question, 2026-09-07 · **governed decision** 🔴 **`never`** *(applicable · searched `governance/` · no instance found)*.
**Depends on:** `K` · `Δ` · `I_t` / Ideal State · `Π` (**itself unresolved**, `KOS-T-0008`) · `T`/`E_S`
· `𝒟_t`. ⚠️ **No sequencing implied.**

| record type | content |
|---|---|
| **proposal** | `D-06`'s algebraic element — *"can and should be integrated"*, **and the same source declines it for now** |
| **decision** | 🔴 none |
| **implementation fact** | 4 definitions, 4+ implementations, **9 of 11 algebraic assumptions refuted** on `D-04` |
