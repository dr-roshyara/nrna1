# PROMPT INSTRUCTIONS: GAP UPDATE & METHODOLOGICAL STANDARDIZATION

**Date:** 2026-08-31  
**Status:** EXECUTION AUTHORIZED  
**Input Artifacts:** 
- `01-GAP-UPDATE-FROM-REVISED-286.md`
- `02-GAP-UPDATE-FROM-HPA-REVIEW-D285-5.md`

---

## Executive Summary

Two gap update artifacts have been produced. Together they:

1. **Close 3 gaps** via corpus recovery (Sañjaya layer recovered)
2. **Create 2 new gaps** (Sañjaya mis-prioritized; H-K identifier collisions)
3. **Reclassify 1 gap** (`G3 → G5` promotion)
4. **Identify 1 prompt defect** (H-K04/H-K06 collisions)
5. **Adopt methodological rule**: every D285-x artifact must specify its equality relation
6. **Apply the rule to my own work** and find a defect in D285-6
7. **Reformat 5 artifacts** to the mandated 7-section structure

---

## Part 1: The Methodological Rule to Adopt

**From D285-5 HPA Review:**

> **"A property stated without naming its equality relation is not a well-formed proposition."**

This is now the **governing rule** for all D285-x artifacts.

### Implementation:

Every D285-x artifact must include a **§5 — Equality Specification** section that states:

| Question | Answer |
|:---|:---|
| Which equality relation is used? | Structural / Semantic / Observational / Identity / None |
| Is the property non-vacuous? | Yes / No / Not applicable |
| If none, why? | Explain |

### Already Applied:

| Artifact | Equality Relation | Status |
|:---|:---|:---|
| D285-1 | Set equality on primitive NAMES | **WEAK** — records vocabulary disjointness, not conceptual disjointness |
| D285-2 | Identity persistence `𝒩(t) =_identity 𝒩(t+1)` | **HOLDS** |
| D285-4 | None — type equality only | **CORRECT** (not an equality claim) |
| D285-5 | `=_semantic` | **TEMPLATE** |
| D285-6 | `=_semantic` | **CORRECTED** — was unqualified |
| D285-7 | None — dependency trace | **CORRECT** (not an equality claim) |

---

## Part 2: The Sañjaya Recovery

**From `01-GAP-UPDATE-FROM-REVISED-286`:**

The Sañjaya layer was **already in the corpus** and closes three gaps:

### Gap 1: `(W, Ω)` referent layer

| Before | After |
|:---|:---|
| `G3` theoretical gap — "the smallest missing concept" | 🟢 **`G5` PROMOTION** — `W` = Domain reality, `Ω` = Sañjaya. Named, layered and diagrammed in the corpus. **Nothing to invent.** |

### Gap 2: `Observation` primitive absent from verification lane

| Before | After |
|:---|:---|
| Asymmetry #1 in D285-6 | 🟢 **CLOSED as a discovery gap** — it is a ratified primitive *and* has a corpus construct. The lane simply never imported it. |

### Gap 3: `Σ₀` cannot express `Unknown`/`Conflicting`/`Unresolved`

| Before | After |
|:---|:---|
| Open | 🟡 **`G5`** — `Sañjaya_K`'s six values do, **at the observation layer**, which is where they belong. `Σ₀` is an *assertion*-level object; the two are at different layers and were never rivals. |

### Sañjaya's Typed Status Vocabulary:

`Sañjaya_K = (Observed, Inferred, Reported, Unknown, Conflicting, Unresolved)`

**This is not exploratory. It is a ratified corpus construct with six formalized principles.**

---

## Part 3: The GK-13 Jñāna Correction

**From `01-GAP-UPDATE-FROM-REVISED-286`:**

The prompt maps `Jñāna → Knowledge`. **This is wrong.**

### The Correction:

| Gītā Term | Wrong Mapping | Correct Mapping | Evidence |
|:---|:---|:---|:---|
| **Jñāna** | Knowledge | **Knowing** (the transformation `δ`) | Tripuṭī: `Jñātā · Jñāna · Jñeya` = **knower · knowing · known** |

### The Tripuṭī:

```
Jñātā → Knower (already ratified as Kṣetrajña)
Jñeya → The known (Proposition/Assertion content)
Jñāna → The transformation δ / the act of knowing
```

### Gap Movement:

`GK-13` does **not** open a `Knowledge`-primitive gap. It **corroborates** `Knowledge`'s deliberate absence and relocates the term to the transformation layer.

### Classification:

`R4` — falsifiable, useful, reinforces the ratified decision rather than challenging it.

---

## Part 4: The D285-6 Correction

**From `02-GAP-UPDATE-FROM-HPA-REVIEW-D285-5`:**

Applying the equality rule to D285-6 found a defect:

### Before (Unqualified):

\[
(\mathcal{A}, \mathcal{R}) = \pi_K(K_t)
\]

### After (Qualified):

\[
(\mathcal{A}, \mathcal{R}) =_{\text{semantic}} \pi_K(K_t)
\]

### Why This Matters:

| Equality | Holds? | Why |
|:---|:---|:---|
| **structural** | 🔴 **FALSE** | `Assertion` is not a ratified primitive |
| **semantic** | ✅ **TRUE** | Only after unpacking `Assertion` and modulo the declared drop |
| **observational** | 🔴 **FALSE** | Replay, policy-eval, authorize need dropped primitives |

### The Corrected Statement:

> **Outcome B stands, and it is weaker than an unqualified `=` implied. "Projection" is the right word only for a lossy, semantic-level map, and the loss is now named: three primitives and three query classes.**

---

## Part 5: The Prompt Defects

### Defect 1: H-K Identifier Collisions

| Identifier | Register Says | A Later Part Says |
|:---|:---|:---|
| **H-K04** | GK-04 **Kṣetra-jña** (Part 3) | **Equanimity** (Part 15) |
| **H-K06** | GK-06 **Karma** (Part 6) | **Failed Action / Negative Knowledge** (Part 14) |

**Resolution (local, declared, not a proposal):**

- `H-K04` = Kṣetra-jña
- `H-K04e` = Equanimity
- `H-K06` = Karma/Phala
- `H-K06f` = Failed action
- `H-K13` = **Jñāna** (per the revised register)

### Defect 2: Sañjaya Mis-Prioritized

The revised prompt places `GK-16 Sañjaya` in **Priority 3 (exploratory)**.

**Evidence says Sañjaya belongs in Priority 1:**

- It is the **only** new entry that touches a *named blocker* (`π_K`'s `Observation` component)
- `Observation` is **one of the 8 ratified primitives**
- It already has a **formalised corpus construct** with six typed principles
- Its `Knower ≠ Observer` principle is a **refinement of `GK-04`**, which the prompt itself ranks Priority 1

---

## Part 6: The Gap Register (Updated)

| # | Gap | Movement |
|:---|:---|:---|
| 1 | `(W, Ω)` referent layer | 🟢 **`G3` → `G5`** — recovered as `W` = Domain reality, `Ω` = Sañjaya |
| 2 | `Observation` absent from verification lane | 🟢 **CLOSED as discovery** — ratified primitive + corpus construct |
| 3 | observation-layer status vocabulary | 🟢 **`G5`** — `Sañjaya_K`, six values, uncertainty-preserving |
| 4 | `Knowledge` as a primitive | 🟢 **CORROBORATED ABSENT** — `GK-13` relocates `Jñāna` to transformation layer |
| 5 | `Qualify` has no body | 🔴 **UNCHANGED — `G1`, irreducible** |
| 6 | measurement / scale types | 🔴 unchanged; `GK-14` would touch it — flagged |
| 7 | **NEW** — Sañjaya mis-prioritised as exploratory | 🟡 recommendation to HPA |
| 8 | **NEW** — `H-K04`/`H-K06` identifier collisions | 🟡 prompt defect, disambiguated locally |

**Net: 3 gaps closed by recovery, 1 corroborated, 1 irreducible blocker unchanged, 2 new items — and no innovation used anywhere.**

---

## Part 7: The D285-x Template (Mandated)

**From the HPA Review of D285-5:**

### Required 7-Section Structure:

```
# D285-X · [Property Name]

## 1. Property Statement
[Formal proposition]

## 2. Trivial vs Substantive
[Which properties are trivial? Which are substantive?]

## 3. Execution
[Concrete test with results]

## 4. Qualification
[Hidden assumptions or conditions]

## 5. Equality Specification
[Which equality relation is used? Structural / Semantic / Observational / Identity / None]
[Is the property non-vacuous? Yes / No / Not applicable]

## 6. Independence
[Gītā vs KnowledgeOS derivation]
[Would KOS require this without the Gītā?]

## 7. Classification
[Verdict with R0–RX justification]
```

### Special Case:

- **D285-8** (Gītā appendix) is **deliberately NOT reformatted** — it is not a test artifact and asserts no property.

---

## Part 8: Immediate Actions Required

### Action 1 — Adopt the Methodological Rule

```
A property stated without naming its equality relation is not a well-formed proposition.
```

### Action 2 — Update the Gap Register

- Mark gaps 1-4 as closed/reclassified
- Mark gap 5 as unchanged (irreducible)
- Add gaps 7-8 as new items

### Action 3 — Correct the Jñāna Mapping

```
Jñāna → Knowing (δ), not Knowledge (K)
```

### Action 4 — Correct D285-6

```
(𝒜,ℛ) =_semantic π_K(K_t)
```
with the loss enumerated.

### Action 5 — Disambiguate H-K Identifiers

| Original | Disambiguated |
|:---|:---|
| H-K04 | Kṣetra-jña (H-K04) |
| H-K04 (Equanimity) | H-K04e |
| H-K06 | Karma/Phala (H-K06) |
| H-K06 (Failed action) | H-K06f |
| H-K13 | Jñāna |

### Action 6 — Reformat Remaining Artifacts

- D285-1, D285-2, D285-4, D285-6, D285-7: **REFORMATTED** with §5
- D285-8: **EXEMPT** (not a test artifact)

---

## Part 9: The Governing Principles (Updated)

From the two gap updates:

1. \[
\boxed{\text{Every D285 hypothesis must be typed before it can be tested.}}
\]

2. \[
\boxed{\text{Every equality-dependent hypothesis must specify its equality relation.}}
\]

3. \[
\boxed{\text{A property stated without naming its equality relation is not a well-formed proposition.}}
\]

4. \[
\boxed{\text{Corroboration} \neq \text{Derivation. Corroboration is stronger when the property is independently required.}}
\]

5. \[
\boxed{\text{Gītā provides candidate distinctions; KOS research determines whether they survive formalisation.}}
\]

6. \[
\boxed{\text{The corpus is richer than the model built from it.}}
\]

---

## Part 10: The Core Documents to Reference

| Document | Purpose |
|:---|:---|
| `01-GAP-UPDATE-FROM-REVISED-286.md` | Sañjaya recovery, GK-13 correction, falsification protocol |
| `02-GAP-UPDATE-FROM-HPA-REVIEW-D285-5.md` | Equality rule adoption, D285-6 correction, artifact reformatting |
| `D285-5` | Action/Result Formal Test — template artifact |
| `D285-6` | Projection Formal Test — corrected with equality |
| `GITA-KOS-TRANSLATION-REGISTER` | Translation register with hypotheses |

---

**HPA Execution Instructions**
**Date: 2026-08-31**
**Status: PROMPT INSTRUCTIONS COMPLETE**

---

*END OF PROMPT INSTRUCTIONS*