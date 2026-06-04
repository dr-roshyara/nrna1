# External Framework Evaluation Rule

**Governance Process for Evaluating External Frameworks**

**Date:** 2026-06-04  
**Type:** Governance Rule  
**Scope:** Applies to all external papers, frameworks, standards, methodologies, patterns  
**Status:** ACTIVE

---

## Purpose

Prevent framework-first thinking while remaining open to external evidence.

Ensure external frameworks are evaluated systematically without derailing ongoing architectural work.

---

## Process

### Step 1: Classify

Determine the category of external framework:

| Category | Examples | Decision Power |
|----------|----------|---|
| **Evidence** | Research papers, domain analysis | Informational |
| **Pattern** | Design patterns, anti-patterns | Advisory |
| **Reference Architecture** | Published architectures, standards | Comparative |
| **Implementation Technique** | Algorithms, data structures, libraries | Tactical (if approved) |
| **Mathematical Framework** | Formal models, theorem sets | Analytical |

### Step 2: Assess

Does the framework explain observed behavior in the domain?

**Questions:**
- What does it claim to explain?
- Does observed domain behavior match the framework's model?
- Are there contradictions or gaps?
- What evidence would prove it applicable or inapplicable?

**Output:**
- Applicability Assessment (Yes / Partial / No / Unknown)
- Confidence Level (HIGH / MEDIUM / LOW)

### Step 3: Compare

Does it explain observed behavior **better** than the current model?

**Questions:**
- Does it explain more phenomena?
- Does it explain phenomena more elegantly?
- Does it reduce unexplained exceptions?
- Does it create new exceptions?

**Decision Gate:**
Only proceed if framework explains **more** or **better** than current model.

### Step 4: Recommend

Based on assessment and comparison, develop recommendation for ARB:

| Recommendation | Meaning |
|----------|---------|
| **Recommend Adoption** | Framework provides superior explanation; recommended for ARB consideration as architectural foundation |
| **Recommend Partial Adoption** | Framework concepts may enhance current model; recommended for ARB consideration |
| **Recommend Rejection** | Framework does not explain domain behavior better; proceed with current model |
| **Recommend Monitoring** | Framework under evaluation; insufficient evidence yet; defer to future phase |

### Step 5: Record Rationale

Document in Evidence Catalog:

```
E-XXX
[Framework Name]
[Author/Source]

Category:
[Classification from Step 1]

Applicability:
[Assessment from Step 2]

Comparative Value:
[Comparison from Step 3]

Decision:
[Adopt / Partial / Reject / Monitor]

Rationale:
[Specific reasons for decision]

Review Date:
[When to re-evaluate if Monitor]
```

---

## Critical Safeguards

### Safeguard 1: Evidence Drives Questions

Framework discovery must NOT change the architectural questions under investigation.

**Wrong:** "Paper discusses metric spaces, so let's study metric spaces."

**Right:** "Paper discusses metric spaces; let's evaluate whether metric spaces help answer our existing questions."

### Safeguard 2: Authority Hierarchy

External frameworks do NOT override:
- Approved evidence from domain exploration (Rounds 8-11)
- ARB decisions
- Approved scope
- Active revision triggers

### Safeguard 3: Scope Preservation

External frameworks may be **input** to architectural analysis.

External frameworks must NOT become:
- The basis for architectural questions
- The foundation for domain modeling
- The definition of scope
- The goal to achieve

### Safeguard 4: Decision Separation

Framework evaluation is done **within** a phase, not **between** phases.

External frameworks discovered during Round 14 are evaluated during Round 14, not used to redefine Round 13 or authorize Round 15.

### Safeguard 5: Framework Neutrality

External frameworks may support, contradict, or have no relationship to current hypotheses.

All three outcomes are acceptable.

The goal is understanding, not adoption.

**Valid Outcomes:**
- Framework supports current model → May recommend adoption
- Framework contradicts current model → May recommend rejection or revision  
- Framework has no relationship to domain → Valid to recommend monitoring or rejection

**Invalid Outcome:**
- "We found a paper, therefore we must find value in it"

Intellectual honesty requires accepting:

```
Interesting framework.
Not applicable to our domain.
```

as a completely valid analytical conclusion.

---

## Application to E-014

### Framework Registration

```
E-014
Constitutional Governance in Metric Spaces
Authors: Shapiro & Talmon

Category:
Mathematical Framework + Explanatory Model

Status:
Under Evaluation (Round 14)

Authority:
Informational only

Decision Power:
None

May Influence:
Round 14 analysis of Governance ↔ Decision Lineage relationship

May Not:
Override approved evidence
Override ARB decisions
Change Round 14 questions
Establish new scope
```

### Evaluation Timeline

**Round 14 Step 1:**
- Assess whether metric spaces explain Governance ↔ Lineage relationship
- Determine applicability to domain

**Round 14 Completion:**
- Record recommendation (Recommend Adoption / Partial / Rejection / Monitoring)
- If Recommend Adoption/Partial: incorporate into findings and present to ARB
- If Recommend Rejection: document rationale and proceed with original analysis
- If Recommend Monitoring: defer evaluation to future phase; note in evidence catalog

---

## Governance Principle

**External frameworks are tools for understanding the domain.**

**The domain is the source of truth.**

**Never reverse this relationship.**

---

**STATUS: External Framework Evaluation Rule ACTIVE**

**APPLIES TO:** All external evidence introduced during Round 14+

**FIRST APPLICATION:** E-014 Constitutional Governance in Metric Spaces
