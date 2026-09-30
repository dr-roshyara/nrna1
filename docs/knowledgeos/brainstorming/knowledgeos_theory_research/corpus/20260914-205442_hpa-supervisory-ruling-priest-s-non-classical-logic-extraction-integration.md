# HPA SUPERVISORY RULING: PRIEST'S NON-CLASSICAL LOGIC — EXTRACTION AND INTEGRATION

**Reviewer:** Senior Mathematician · Senior Statistician · Senior DDD Architect
**Date:** 2026-09-02
**Status:** ACCEPTED — RESEARCH EXTRACTION COMPLETE
**Authority:** HPA Supervisory

---

## Executive Summary

Priest's *An Introduction to Non-Classical Logic* is **highly relevant** to KnowledgeOS, and it arrived at exactly the right point in the research. Its greatest contribution is not "use paraconsistent logic" — it is:

> **Do not force epistemic reality into a single classical truth axis.**

The book provides **formal tools and methodological constraints** that directly illuminate our current unresolved problems, especially **Contr, Zero, semantic equivalence, identity, temporal state, and the separation between representation and evaluation.**

**Theory v1.2 remains unchanged. Contr remains the next experiment. FDE becomes a major external candidate for that experiment — not the answer.**

---

## Part 1: What Is Extracted

### 1.1 The Key Concepts

| Priest Concept | KnowledgeOS Relevance | Status |
|:---|:---|:---|
| **Paraconsistency** | Extremely high for Contr | `[EXT] → [PROP]` |
| **Gaps and gluts** | Extremely high for Zero/Contr | `[EXT] → [PROP]` |
| **FDE four-valued semantics** | Extremely high research candidate | `[EXT] → [PROP]` |
| **Extension / Anti-extension** | Very promising representation mechanism | `[PROP]` |
| **Non-explosion** | Directly relevant to contradiction handling | `[PROP]` |
| **Designated values** | Important distinction between representation and admissibility | `[PROP]` |
| **Relevant logic / relevance** | Important for evidence → conclusion relations | `[PROP]` |
| **Information-flow semantics** | Highly relevant to Evidence/Inference | `[PROP]` |
| **Possible-world semantics** | Relevant to context/time/alternative states | `[PROP]` |
| **Tense logic** | Relevant to \(K_t\), revision, and temporal identity | `[PROP]` |
| **Necessary vs contingent identity** | Very relevant to KnowledgeOS identity problem | `[PROP]` |
| **Free logic / existence** | Relevant to absent/nonexistent/unresolved entities | `[PROP]` |
| **Equivalence relations** | Directly relevant to FR-001 and \(\equiv_{sem}\) | `[EXT] → methodological constraint` |
| **Tableaux/countermodels** | Excellent methodology for falsification | `[EXT] → [PROP]` |
| **Soundness/completeness** | Potential KnowledgeOS verification methodology | `[PROP]` |
| **Methodological coda** | Extremely important for our research discipline | `[EXT] → methodological invariant` |

### 1.2 The Core Insight: Gaps and Gluts

Priest distinguishes:

| Concept | Meaning | KnowledgeOS Correspondence |
|:---|:---|:---|
| **Gap** | Neither true nor false | Unknown, Absent, NotAssessed, Unobservable |
| **Glut** | Both true and false | Contradiction, Conflicting evidence |

**This is extremely important because our Zero Lens already discovered that `UNKNOWN` and `CONTRADICTION` must remain distinct.**

### 1.3 The FDE Structure

FDE represents each proposition as:

$$
A \mapsto (\text{support-for-}A, \text{support-for-}\neg A)
$$

| Support for \(A\) | Support for \(\neg A\) | Semantic Status |
|:---|:---|:---|
| 1 | 0 | True only |
| 0 | 1 | False only |
| 1 | 1 | Both (glut) |
| 0 | 0 | Neither (gap) |

**This is a candidate representation for Contr, not a final theory.**

### 1.4 The Candidate KnowledgeOS Structure

```
EpistemicEvaluation
        =
Truth/Falsity Support
        ×
Boundary Condition
        ×
Context
        ×
Provenance
```

Where the first factor could be FDE-like:

$$
(S^+(p), S^-(p)) \in \{0,1\}^2
$$

**But this is `[PROP]`, not adopted.**

---

## Part 2: What Is Applied to the TODO Register

### 2.1 B — Contradiction (Strengthened)

The book significantly strengthens this lane.

**Candidate representation:**

$$
Contr(p) = Support^+(p) \land Support^-(p)
$$

**Test against:**

1. Four-valued FDE representation
2. Current 3-valued model
3. Structured `(value, reason, provenance)`
4. Four-way boundary model
5. Candidate fourth-value model

**Key principle:**

> Contradiction must be representable without global inferential collapse.

$$
Contr(P, \neg P) \not\Rightarrow \forall Q\; Q
$$

**Status:** `[PROP]` — to be tested in KR-CONTR-2026-09.

---

### 2.2 C — ⪰ (Reinforced)

Priest's distinction between **semantic value** and **designated value** reinforces:

$$
\text{admissibility} \neq \text{ranking} \neq \text{selection}
$$

We should not infer an epistemic ordering merely from semantic values.

**Status:** No change; reinforces existing position.

---

### 2.3 D — ≡sem (Strengthened)

Priest's equivalence relation discussion directly supports FR-001:

$$
\equiv
$$

must be:

1. Reflexive
2. Symmetric
3. Transitive

And every property defined on the quotient must be invariant under the equivalence relation:

$$
R_1 \equiv_{sem} R_2 \Rightarrow F(R_1) = F(R_2)
$$

**Status:** Reinforces FR-001; no change to D.

---

### 2.4 H — Projection/Invariant (Strengthened)

Priest's methodological coda:

> A mathematically defined semantics still has to earn its meaning.

This supports:

$$
WellDefined(M) \not\Rightarrow SemanticallyAdequate(M)
$$

$$
FormalElegance(M) \not\Rightarrow KnowledgeOSValidity(M)
$$

**Status:** Strengthens H as a research lane.

---

### 2.5 I — Reduction (Reinforced)

Priest's equivalence-class discussion reinforces:

> Reduction cannot safely proceed until the relevant equivalence relation is actually established.

**Status:** No change to I.

---

### 2.6 J — Kernel (Reinforced)

No kernel operator should be added because of this book.

**Status:** Kernel remains NOT SELECTABLE.

---

## Part 3: What Is Rejected

| Claim | Reason for Rejection |
|:---|:---|
| "KnowledgeOS should use FDE" | Not established |
| "KnowledgeOS has exactly four epistemic states" | No |
| "Contradiction is true" | Priest's dialetheism is not imported |
| "KnowledgeOS is paraconsistent" | Not yet demonstrated |
| "FDE is the KnowledgeOS kernel" | Absolutely not |
| "Zero = the fourth FDE value" | No |
| "Unknown = neither true nor false" | Too strong |
| "Contradiction = both true and false" | Possibly useful as semantic model, but not necessarily equivalent to epistemic contradiction |
| "Possible worlds = Knowledge Space" | No |
| "Relevant logic proves Evidence relevance" | No — the book itself warns that the information-flow interpretation needs justification |
| "Many-valued logic gives the correct KnowledgeOS semantics" | No |

---

## Part 4: The New Candidate Architecture

### 4.1 The Layered Model (Research Candidate)

```
Evidence
   ↓
Assessment
   ↓
Standing = (S⁺(p), S⁻(p))   ← FDE-inspired core
   ↓
Boundary = (Facet, Condition, Context, Provenance)   ← Zero Layer
   ↓
Hypothesis Space = {H₁, H₂, ...}
   ↓
Determination ⊆ Hypothesis Space
   ↓
Attribution = Γ(E_t, Q, C, EC)   ← Non-factive
```

### 4.2 The Key Distinctions

| Layer | Question | Candidate Representation |
|:---|:---|:---|
| **Standing** | What is the evidential status? | `(S⁺, S⁻) ∈ {0,1}²` |
| **Boundary** | Why is it incomplete? | `(Facet, Condition, Context, Provenance)` |
| **Hypothesis Space** | What alternatives remain? | `{H₁, H₂, ...}` |
| **Determination** | What is decided? | `⊆ Hypothesis Space` |
| **Attribution** | What is claimed? | `Γ(E_t, Q, C, EC)` |

**This is NOT Theory v1.3. It is the strongest research candidate from this extraction.**

---

## Part 5: The Next Experiment

### 5.1 KR-CONTR-FDE-2026-09

**Question:**

> Can an FDE-inspired two-channel semantic representation preserve the distinctions required by KnowledgeOS Contr and Zero without collapsing boundary reasons?

**Compare four models:**

| Model | Values |
|:---|:---|
| A — Classical | `{T, F}` |
| B — K3 | `{T, F, U}` |
| C — FDE | `{T, F, B, N}` |
| D — Structured | `(S⁺, S⁻, Reason, Provenance, Context)` |

**Test scenarios:**

1. Positive evidence
2. Negative evidence
3. Direct contradiction
4. No evidence
5. Not assessed
6. Unobservable
7. Underdetermined
8. Conflicting sources
9. Superseded evidence
10. Scope exclusion
11. Temporal conflict
12. Contradictory rules
13. Contradictory observations
14. Contradictory interpretations

**Crucial measurement:**

> **Which distinctions are preserved? Which distinct KnowledgeOS situations collapse to the same representation?**

### 5.2 What the Experiment Must Determine

$$
\boxed{
\text{What is the minimum evaluation domain required?}
}
$$

Not:

> "Which model is most elegant?"

---

## Part 6: The Supervisory Verdict

### 6.1 Status

| Element | Status |
|:---|:---|
| Priest extraction | **[EXT] → [PROP] COMPLETE** |
| FDE candidate | **[PROP] — to be tested** |
| Paraconsistency principle | **[PROP] — to be tested** |
| Two-channel representation | **[PROP] — to be tested** |
| Boundary orthogonal to Standing | **[PROP] — to be tested** |
| Theory v1.2 | **UNCHANGED** |
| Kernel | **UNCHANGED** |
| KR-CONTR-FDE experiment | **DESIGNED** |

### 6.2 The Final Statement

Priest's book is **highly relevant**, and it arrived at exactly the right point in the research. Its greatest contribution is not "use paraconsistent logic." It is:

> **Do not force epistemic reality into a single classical truth axis.**

The FDE-inspired two-channel representation — where a proposition can be supported, opposed, both, or neither — is a **strong candidate** for the Contr experiment. But it must be tested against the Zero Lens findings: Unknown, Absent, NotAssessed, Underdetermined, and Unobservable must remain distinct from Contradiction.

**Theory v1.2 remains unchanged. Contr remains the next experiment. FDE becomes a major external candidate for that experiment — not the answer.**

---

**HPA Supervisory Ruling**
**Date: 2026-09-02**
**Status: ACCEPTED**
**Next: KR-CONTR-FDE-2026-09**

---

*END OF RULING*