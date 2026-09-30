# HPA SUPERVISORY RULING: KR-CONTR-FDE-2026-09 — IMPLEMENTATION PROTOCOL

**Reviewer:** Senior Mathematician · Senior Statistician · Senior DDD Architect
**Date:** 2026-09-02
**Status:** APPROVED — READY FOR IMPLEMENTATION
**Authority:** HPA Supervisory

---

## Executive Summary

**The FDE-inspired evaluation layer is APPROVED for implementation as a sandboxed research experiment.**

The key boundary is:

> **Implement the candidate evaluation mechanism, not "FDE as KnowledgeOS logic."**

The experiment must test whether independent positive/negative support plus an orthogonal boundary/reason dimension can preserve all distinctions that KnowledgeOS currently requires for Contr and Zero.

**Theory v1.2 remains unchanged. Kernel remains unchanged. No domain vocabulary is modified.**

---

## Part 1: What Is Implemented

### 1.1 The Core Structure

```
Evidence
   │
   ▼
EvidenceAssessment
   │
   ▼
Standing = (S⁺, S⁻)
   │
   ▼
Evaluation
   │
   ├── Boundary / Reason
   ├── Context
   └── Provenance
   │
   ▼
Determination
   │
   ▼
AttributedState (A_t)
```

### 1.2 The Standing Representation

```
Standing(p) = (S⁺, S⁻)
```

Where:

| S⁺ | S⁻ | Interpretation |
|:---|:---|:---|
| 1 | 0 | Positive support |
| 0 | 1 | Negative support |
| 1 | 1 | Conflict |
| 0 | 0 | No support |

**This is the useful part extracted from FDE: positive and negative support are represented independently.**

### 1.3 What Is Not Implemented

| ❌ Item | Reason |
|:---|:---|
| `enum KnowledgeState { TRUE, FALSE, BOTH, NEITHER }` | Premature ontology |
| FDE as KnowledgeOS logic | Not established |
| Four-valued logic as kernel | Not established |
| Contr definition as `S⁺ ∧ S⁻` | Tested as detector, not definition |

---

## Part 2: The Experiment Structure

### 2.1 Directory Structure

```
experiments/
└── KR-CONTR-FDE-2026-09/
    ├── README.md
    ├── scenarios/
    │   ├── 01-positive-evidence.md
    │   ├── 02-negative-evidence.md
    │   ├── 03-direct-contradiction.md
    │   ├── 04-no-evidence.md
    │   ├── 05-not-assessed.md
    │   ├── 06-unobservable.md
    │   ├── 07-underdetermined.md
    │   ├── 08-conflicting-sources.md
    │   ├── 09-superseded-evidence.md
    │   ├── 10-scope-exclusion.md
    │   ├── 11-temporal-conflict.md
    │   ├── 12-contradictory-rules.md
    │   ├── 13-contradictory-observations.md
    │   └── 14-contradictory-interpretations.md
    ├── models/
    │   ├── classical.py
    │   ├── k3.py
    │   ├── fde.py
    │   └── structured.py
    ├── evaluators/
    │   ├── standing.py
    │   ├── boundary.py
    │   └── determination.py
    ├── invariants/
    │   ├── separation.py
    │   └── preservation.py
    ├── witnesses/
    │   ├── countermodels.py
    │   └── separation-witnesses.py
    ├── results/
    │   ├── separation-matrix.json
    │   └── collapse-register.json
    └── verdict.md
```

### 2.2 The Four Adapters

| Model | Values | Description |
|:---|:---|:---|
| **Classical** | `{T, F}` | Baseline |
| **K3** | `{T, F, U}` | Gap-oriented |
| **FDE** | `{T, F, B, N}` | Independent support |
| **Structured** | `(S⁺, S⁻, Reason, Provenance, Context)` | Full candidate |

### 2.3 The 14 Scenarios

| # | Scenario | Purpose |
|:---|:---|:---|
| 1 | Positive evidence | Baseline support |
| 2 | Negative evidence | Baseline opposition |
| 3 | Direct contradiction | Core Contr test |
| 4 | No evidence | Separation from Contr |
| 5 | Not assessed | Separation from Contr |
| 6 | Unobservable | Separation from Contr |
| 7 | Underdetermined | Separation from Contr |
| 8 | Conflicting sources | Provenance sensitivity |
| 9 | Superseded evidence | Temporal sensitivity |
| 10 | Scope exclusion | Context sensitivity |
| 11 | Temporal conflict | Temporal Contr |
| 12 | Contradictory rules | Normative Contr |
| 13 | Contradictory observations | Observational Contr |
| 14 | Contradictory interpretations | Interpretive Contr |

---

## Part 3: The Measurement Protocol

### 3.1 What Is Measured

For each model and scenario:

1. **Does it preserve the required distinction?**
2. **Does it collapse the required distinction?**
3. **What is the reason for collapse?**
4. **What is the countermodel?**

### 3.2 The Separation Matrix

For each model, construct:

```
                 Unknown  Absent  Conflict  NotAssess  Unobservable
Unknown             -       ?        ?         ?           ?
Absent              ?       -        ?         ?           ?
Conflict            ?       ?        -         ?           ?
NotAssessed         ?       ?        ?         -           ?
Unobservable        ?       ?        ?         ?           -
```

### 3.3 The Key Metric

> **Which distinctions are preserved? Which distinct KnowledgeOS situations collapse to the same representation?**

### 3.4 The Critical Measurement

Do **not** ask:

> "Which model has the highest number of states?"

Ask:

$$
\boxed{
\text{What is the minimum representation required to preserve all distinctions?}
}
$$

---

## Part 4: What Is Tested

### 4.1 Standing Representation

Test whether `(S⁺, S⁻) ∈ {0,1}²` preserves:

| Distinction | Expected |
|:---|:---|
| Positive ≠ Negative | Yes |
| Positive ≠ Conflict | Yes |
| Positive ≠ No Support | Yes |
| Negative ≠ Conflict | Yes |
| Negative ≠ No Support | Yes |
| Conflict ≠ No Support | Yes |
| Unknown ≠ Absent | No (collapses) |
| NotAssessed ≠ Unobservable | No (collapses) |

### 4.2 Boundary Representation

Test whether `Boundary = (Facet, Condition, Context, Provenance)` preserves:

| Distinction | Expected |
|:---|:---|
| Unknown ≠ Absent | Yes |
| NotAssessed ≠ Unobservable | Yes |
| Contradiction ≠ Unknown | Yes |
| Contradiction ≠ Absent | Yes |
| Contradiction ≠ NotAssessed | Yes |

### 4.3 The Full Candidate

Test whether `Standing + Boundary` preserves all distinctions.

---

## Part 5: The Non-Negotiable Rules

### Rule 1 — No Kernel Promotion

Do **not** add `Contr`, `C`, `FDE`, or `Both` to the kernel.

### Rule 2 — No Theory v1.3

Theory v1.2 remains unchanged.

### Rule 3 — No Enumeration as Ontology

Do **not** create:

```java
enum KnowledgeState { TRUE, FALSE, BOTH, NEITHER }
```

### Rule 4 — FDE is a Candidate, Not the Answer

FDE is tested, not adopted.

### Rule 5 — Separation is the Metric

Measure **distinction preservation**, not value count.

### Rule 6 — Countermodels are Required

For every collapsed distinction, provide a countermodel.

---

## Part 6: The Output Artifact

### 6.1 Verdict Structure

```
# KR-CONTR-FDE-2026-09

## 1. Executive Summary

## 2. Baseline and Scope

## 3. Models Tested

## 4. Scenarios Tested

## 5. Separation Matrix

## 6. Collapse Register

## 7. Countermodel Catalogue

## 8. Positive Results

## 9. Negative Results

## 10. Boundary Findings

## 11. Invariants Discovered

## 12. Candidate Formal Definitions

## 13. What Remains Open

## 14. Theory Impact

## 15. Kernel Impact

## 16. Decision Recommendation

## 17. Classification Register
```

### 6.2 The Final Table

| Model | Contradiction Preserved | Unknown/Absent Distinguished | Unobservable/NotAssessed Distinguished | Provenance Preserved | Temporal Preserved | Context Preserved |
|:---|:---|:---|:---|:---|:---|:---|
| Classical | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| K3 | ❌ | ❌ | ❌ | ❌ | ❌ | ❌ |
| FDE | ✅ | ❌ | ❌ | ❌ | ❌ | ❌ |
| Structured | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |

---

## Part 7: The Supervisory Verdict

### 7.1 Status

| Element | Status |
|:---|:---|
| FDE-inspired evaluation layer | **APPROVED FOR IMPLEMENTATION** |
| Standing = (S⁺, S⁻) | **APPROVED AS CANDIDATE** |
| Boundary = (Facet, Condition, Context, Provenance) | **APPROVED AS CANDIDATE** |
| Four adapters | **APPROVED** |
| 14 scenarios | **APPROVED** |
| Separation matrix | **REQUIRED** |
| Countermodels | **REQUIRED** |
| Theory v1.2 | **UNCHANGED** |
| Kernel | **UNCHANGED** |

### 7.2 The Final Statement

**The FDE-inspired evaluation layer is APPROVED for implementation as a sandboxed research experiment.**

The key boundary is:

> **Implement the candidate evaluation mechanism, not "FDE as KnowledgeOS logic."**

The experiment must answer:

> **Can independent positive/negative support plus an orthogonal boundary/reason dimension preserve all distinctions that KnowledgeOS currently requires for Contr and Zero?**

The implementation is experimental. Nothing is promoted to the kernel. Theory v1.2 remains unchanged.

---

**HPA Supervisory Ruling**
**Date: 2026-09-02**
**Status: APPROVED — READY FOR IMPLEMENTATION**
**Next: KR-CONTR-FDE-2026-09 EXECUTION**

---

*END OF RULING*