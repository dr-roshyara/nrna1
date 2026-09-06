---
artifact: 04-MISSINGNESS-STATUS
date: 2026-08-30
status: **RECONSTRUCTION, not innovation — 4 of 4 distinctions have corpus carriers; 3 EXECUTE. One structural component is missing.**
---

# 04 · Missingness Archaeology

**Method:** search corpus **and** implementation before declaring anything missing (mandate §5).

## 1. The four mandated distinctions, against the corpus

| Distinction | Corpus carrier | Source | Executes? |
|---|---|---|---|
| **never asked** | `DIMENSION = not assessed` | Zero lens `20260826-105229` | — |
| **asked but no evidence** | `Unknown` — *"no evidence either way"* | `25D.7 case A` | ✅ `zero_reference.py` |
| **insufficient evidence** | `Insufficient` | `25D.4` | ✅ `zero_reference.py` |
| **possibly not applicable** | `NotApplicable` | `25D.4` | ✅ `zero_reference.py` |

**All four have a named corpus construct. Three are running, passing code** (8/8 falsification tests,
re-executed this pass, exit 0).

The corpus additionally supplies the **ten-value set** `25D.4 + 25D.7`
(`Satisfied · PartiallySatisfied · Unknown · Insufficient · Conflicted · Stale · Invalid ·
Prohibited · NotApplicable · Missing`), **five non-collapse laws** including
**`NOT_ASSESSED ≠ LOW_CONFIDENCE`** (*"Not checked ≠ low confidence"*), the boxed
`UnknownValue(D) ≠ UnknownDimension(D)`, and the four-fold **abhāva** ontological typology with an
`AbsenceClaim` record shape.

## 2. It is live in the corpus right now, not abandoned

Step 276 §276.15 (`G9 — Missingness`) `CORPUS`:
> *"Do not introduce a generic `Missing` property merely for convenience. Investigate: never asked;
> never asserted; absent; not assessed; unavailable evidence; absent evidence; insufficient evidence;
> unknown. **Determine the minimum layer required to preserve the distinctions the theory actually
> needs.**"*

**An 8-way list, opened and not yet closed.** Steps 277–280 do not return to it.

## 3. What is actually missing — one structural component, precisely

`EXECUTED` — In `K = (𝒜, ℛ)` and in `K = (A,R,Σ,E_L)`, *"never asked"* and *"asked, nothing found"*
**both render as: no assertion mentioning that dimension.** They are **provably indistinguishable**,
because absence-of-an-assertion is one value, not two.

**And `Σ₀` cannot rescue it** (`03` §A6): *"insufficient"* collapses into `Supported` because `Σ₀`
has no sufficiency axis.

The Zero model does not have this problem — it carries the layer:
```
Ω    potential knowledge space          D_t  dimensions currently RECOGNISED
K_t  state over recognised dimensions   Z_t  = Ω \ Represented(K_t)
```
With `D_t`: *"not assessed"* is `D ∈ D_t ∧ ¬∃a ∈ 𝒜 : a.P.D = D` — **decidable in `O(n)`**.

## 4. Classification

| | |
|---|---|
| **Category** | **4 — Refinement / reconstruction.** The constructions exist in the corpus; the canonical model lost them |
| **NOT category 5** | nothing here needs inventing; `D_t` is corpus, the ten-value set is corpus **and executes** |
| **Minimum additional object required** | **`D_t ⊆ 𝒟`, the recognised-dimension set, as a component of the state** — plus a **sufficiency predicate** (`Q`, already typed at §42.40 and dropped in ratification) to separate *insufficient* from *supported* |

**I do not propose the final representation** (mandate §5). Whether `D_t` is a stored set, an index,
or derived from a declared requirement set `R` (as `zero_reference.py` does) is a **representation
choice** — and the executable witness shows at least one working answer already exists.
