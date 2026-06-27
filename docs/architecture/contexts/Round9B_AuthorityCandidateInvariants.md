# Round 9B — Candidate Invariant Exploration

**Strategic Design Exploration (NOT Tactical DDD)**

**Date:** 2026-06-04  
**Status:** Design Exploration — Stress Testing Models  
**Purpose:** Determine whether H-B or H-C maintains coherent invariants despite Appeals uncertainty  
**Methodology:** Test invariants under multiple Appeals interpretations

---

## Context

**From Round 9A:**
- H-B remains plausible
- H-C remains plausible
- Appeals interpretation remains uncertain (4 equally viable interpretations)

**Design Exploration Principle:**
Models must survive uncertainty, not require perfect information.

**Goal:** Determine whether either model maintains coherent invariants despite Appeals uncertainty.

---

## Section 1: H-B Candidate Invariants

**Hypothesis:** Authority is a context-family. Each context owns authority over its domain.

### Invariant 1.1: Authority Must Have Documented Origin

**Candidate Rule:** Every authority claim must trace to a documented source (Governance rules, Constitution, or Fairness principle).

**Supporting Evidence:** 
- Round8_AuthorityFlowAnalysis.md Section 6 (Authority Conservation Test): "All authority claims trace to documented sources"
- All five representative decisions show documented authority origin

**Contradicting Evidence:**
- None observed (invariant holds in all tested decisions)

**Dependency on Appeals Interpretation:**
- **If Appeals is Context:** Origin is Governance + Fairness principle (stable)
- **If Appeals is Responsibility:** Origin is Governance (stable)
- **If Appeals is Capability:** Origin is Governance (stable)
- **If Appeals is Process:** Origin is Governance (stable)

**Stability Assessment:** STABLE across all Appeals interpretations

**Confidence:** HIGH

---

### Invariant 1.2: Each Context Owns Its Decision Domain

**Candidate Rule:** Membership owns membership decisions. Election owns election decisions. Appeals owns reversal decisions. Governance owns rule-setting decisions.

**Supporting Evidence:**
- Round8_DecisionOwnershipMatrix.md: Each context listed as decision owner
- Round9A_AuthorityCandidateResponsibilities.md: Each context has distinct responsibility set

**Contradicting Evidence:**
- Appeals authority crosses all boundaries (contradicts "owns its domain")
- Governance authority affects all others (contradicts "context-local")

**Dependency on Appeals Interpretation:**
- **If Appeals is Context:** Creates 4-context family (problematic but possible)
- **If Appeals is Responsibility:** Violates assumption (Appeals not context-local)
- **If Appeals is Capability:** Still works (Capability doesn't violate ownership)
- **If Appeals is Process:** Doesn't fit (Process isn't context-owned)

**Stability Assessment:** UNSTABLE under Responsibility and Process interpretations; STABLE under Context and Capability

**Confidence:** MEDIUM

---

### Invariant 1.3: Authority Acceptance Is Context-Specific

**Candidate Rule:** Each context determines what makes authority legitimate within its domain.

**Supporting Evidence:**
- Round9A_AuthorityCandidateResponsibilities.md Section 2: "Authority acceptance criteria differ by context"
- Governance requires constitutional alignment; Membership requires eligibility verification; Election requires configuration validation

**Contradicting Evidence:**
- Round8_AuthorityFlowAnalysis.md Section 4: All decisions follow same lifecycle (suggests uniform acceptance)
- Phase 2 Pattern A2 suggests Authority Requires Recognition (cross-contextual, not context-specific)

**Dependency on Appeals Interpretation:**
- **If Appeals is Context:** Context-specific acceptance works (4th context has fairness criteria)
- **If Appeals is Responsibility:** Uniform acceptance across contexts (undermines invariant)
- **If Appeals is Capability:** Context-specific still works (Capability doesn't change acceptance)
- **If Appeals is Process:** Uniform acceptance required (undermines invariant)

**Stability Assessment:** STABLE under Context and Capability; UNSTABLE under Responsibility and Process

**Confidence:** MEDIUM

---

## Section 2: H-C Candidate Invariants

**Hypothesis:** Authority is cross-cutting. Present in all contexts; orthogonal to domain boundaries.

### Invariant 2.1: All Authority Must Follow Same Lifecycle

**Candidate Rule:** Claim → Origin → Exercise → Challenge → Revocation is the invariant pattern for all authority.

**Supporting Evidence:**
- Round8_AuthorityFlowAnalysis.md Section 7: All five decisions show this pattern
- Round9A_AuthorityCandidateResponsibilities.md: Uniform lifecycle observed across contexts

**Contradicting Evidence:**
- Acceptance stage missing from all five decisions (breaks expected universal pattern)
- Authority acceptance differs by context (suggests non-uniform lifecycle)

**Dependency on Appeals Interpretation:**
- **If Appeals is Context:** Lifecycle still uniform (Appeals follows same pattern as others)
- **If Appeals is Responsibility:** Lifecycle is uniform (supports H-C)
- **If Appeals is Capability:** Lifecycle still uniform (Capabilities follow same pattern)
- **If Appeals is Process:** Lifecycle becomes procedural (may not fit pattern)

**Stability Assessment:** STABLE under Context, Responsibility, and Capability; UNSTABLE under Process

**Confidence:** HIGH

---

### Invariant 2.2: Authority Crosses All Context Boundaries

**Candidate Rule:** Authority is orthogonal to domain boundaries. Governance authority affects all contexts. Any context's authority can be challenged by Appeals.

**Supporting Evidence:**
- Governance authority constrains Membership, Election, Appeals
- Appeals authority reverses decisions from other contexts
- No context is authority-local (all are authority-crossed)

**Contradicting Evidence:**
- Context-specific authority types suggest family model, not cross-cutting
- Authority acceptance differs by context (suggests local authority rules)

**Dependency on Appeals Interpretation:**
- **If Appeals is Context:** Doesn't naturally cross boundaries (problematic for H-C)
- **If Appeals is Responsibility:** Naturally crosses (supports H-C)
- **If Appeals is Capability:** Could cross if provided to all (depends on Governance model)
- **If Appeals is Process:** Crosses in workflow sequence (supports H-C)

**Stability Assessment:** STABLE under Responsibility and Process; UNSTABLE under Context; DEPENDS under Capability

**Confidence:** MEDIUM

---

### Invariant 2.3: Authority Cannot Be Exercised Without Legitimacy Validation

**Candidate Rule:** Verification (legitimacy check) precedes or accompanies authority exercise in all contexts.

**Supporting Evidence:**
- Round8_AuthorityFlowAnalysis.md Section 4: Verification occurs before or with Exercise
- All five decisions show verification stage

**Contradicting Evidence:**
- Some decisions show Act-First pattern (execution then challenge)
- Verification stage not always explicit (sometimes implicit)

**Dependency on Appeals Interpretation:**
- **If Appeals is Context:** Verification is domain-specific (each context verifies its authority)
- **If Appeals is Responsibility:** Verification is cross-cutting (all contexts participate)
- **If Appeals is Capability:** Verification could be provided by capability
- **If Appeals is Process:** Verification is part of workflow sequence

**Stability Assessment:** STABLE across all interpretations (invariant holds regardless)

**Confidence:** HIGH

---

## Section 3: Appeals Dependency Matrix

**For each invariant, stability under different Appeals interpretations:**

| Invariant | Context | Responsibility | Capability | Process |
|---|---|---|---|---|
| **H-B 1.1: Documented Origin** | Stable | Stable | Stable | Stable |
| **H-B 1.2: Domain Ownership** | Stable | Unstable | Stable | Unstable |
| **H-B 1.3: Context-Specific Acceptance** | Stable | Unstable | Stable | Unstable |
| **H-C 2.1: Universal Lifecycle** | Stable | Stable | Stable | Unstable |
| **H-C 2.2: Cross-Boundary Authority** | Unstable | Stable | Depends | Stable |
| **H-C 2.3: Legitimacy Validation** | Stable | Stable | Stable | Stable |

---

## Section 4: Invariant Stability Analysis

### H-B Under Different Appeals Interpretations

**If Appeals = Context (4-context family):**
- Invariants 1.1, 1.2, 1.3 all hold
- H-B remains coherent (though Appeals is problematic anomaly)
- Confidence: MEDIUM (Appeals doesn't fit family pattern well)

**If Appeals = Responsibility (cross-cutting):**
- Invariant 1.1 holds
- Invariants 1.2 and 1.3 fail (undermine context-family assumption)
- H-B becomes incoherent
- Confidence: LOW (fundamental contradiction)

**If Appeals = Capability (owned by another context):**
- Invariants 1.1, 1.2, 1.3 all hold
- H-B remains coherent
- Confidence: MEDIUM (Capability model possible but untested)

**If Appeals = Process (workflow):**
- Invariant 1.1 holds
- Invariants 1.2 and 1.3 fail (process doesn't have context ownership)
- H-B becomes incoherent
- Confidence: LOW (fundamental contradiction)

**H-B Overall Assessment:** STABLE under 2 of 4 Appeals interpretations; INCOHERENT under 2 of 4 interpretations

---

### H-C Under Different Appeals Interpretations

**If Appeals = Context (separate bounded context):**
- Invariant 2.1 holds
- Invariant 2.2 weakens (context doesn't naturally cross-cut)
- Invariant 2.3 holds
- H-C remains mostly coherent (but weaker)
- Confidence: MEDIUM (Cross-cutting nature less obvious)

**If Appeals = Responsibility (cross-cutting responsibility):**
- Invariants 2.1, 2.2, 2.3 all hold
- H-C strongly coherent
- Confidence: HIGH (Cross-cutting fits naturally)

**If Appeals = Capability (provided by another context):**
- Invariant 2.1 holds
- Invariant 2.2 depends on Governance structure (could work)
- Invariant 2.3 holds
- H-C remains coherent (under right Governance assumptions)
- Confidence: MEDIUM (Works if Governance provides Appeals to all)

**If Appeals = Process (workflow that all participate in):**
- Invariant 2.1 holds (lifecycle still uniform)
- Invariant 2.2 holds (process crosses boundaries)
- Invariant 2.3 holds (validation in workflow)
- H-C strongly coherent
- Confidence: HIGH (Cross-cutting process fits naturally)

**H-C Overall Assessment:** COHERENT under 3 of 4 Appeals interpretations; WEAKER under 1 of 4 interpretations

---

## Section 5: Open Contradictions

**Contradictions that remain unresolved by invariant analysis:**

### Contradiction 1: Acceptance Stage Absence

**Observation:** Invariant 1.3 (H-B) assumes context-specific acceptance. But Acceptance stage is missing from all decisions.

**Implication:** Either acceptance is implicit (supports H-B assumption) or absent (contradicts assumption).

**Deferred To:** Round 10 or implementation phase (requires deeper investigation).

---

### Contradiction 2: Authority Uniformity vs Variation

**Observation:** H-C 2.1 assumes universal lifecycle. But authority acceptance differs by context.

**Implication:** Lifecycle is uniform but acceptance is context-specific. Can both be true?

**Deferred To:** Round 10 or implementation phase (requires deeper investigation).

---

### Contradiction 3: Governance Centrality in H-C

**Observation:** H-C 2.2 assumes authority crosses all boundaries orthogonally. But Governance centrality suggests non-orthogonality.

**Implication:** Authority may not be truly cross-cutting if Governance is foundational source.

**Deferred To:** Round 10 or implementation phase (requires deeper investigation).

---

## Section 6: Decision Gate

**Question:** Does either model maintain coherent invariants despite Appeals uncertainty?

### Assessment

**H-B Status:**
- Maintains coherence under 2 Appeals interpretations (Context, Capability)
- Becomes incoherent under 2 interpretations (Responsibility, Process)
- Overall: CONDITIONALLY VIABLE (depends on Appeals being Context or Capability)

**H-C Status:**
- Maintains coherence under 3 Appeals interpretations (Responsibility, Process, and partially Capability)
- Remains plausible under all 4 interpretations
- Overall: ROBUST (works under most Appeals interpretations)

### Provisional Finding

**Within the explored Appeals interpretations, H-C remained coherent under more scenarios than H-B.**

This does NOT mean H-C is correct. It means that under the four Appeals interpretations tested in this analysis, H-C maintained invariant coherence across more scenarios than H-B.

**This is evidence from design exploration, not a model selection.**

---

## Section 7: Summary for Round 10

### What We Know

**H-B and H-C both remain plausible, but with different robustness profiles:**
- H-B: Conditional on Appeals being Context or Capability
- H-C: Robust across most Appeals interpretations

**Uncertainty is not eliminated, but now quantified:**
- Quantified by how many Appeals interpretations each model survives

### What Remains Uncertain

**Three major contradictions identified:**
1. Acceptance stage absence (implicit or non-existent?)
2. Authority uniformity vs variation (can both coexist?)
3. Governance centrality in cross-cutting model (truly orthogonal?)

**These do not block further exploration. They are inputs to next phase.**

### What Happens Next

**ARB Decision Required:**

The ARB must decide between three paths:

**Option A:** Select H-C as the working model for further exploration

**Option B:** Select H-B as the working model for further exploration

**Option C:** Continue exploring both models in parallel through further strategic design phases

---

**STATUS: Round 9B Invariant Exploration Complete**

**FINDINGS: Within explored Appeals interpretations, H-C remained coherent across more scenarios than H-B**

**BOTH MODELS REMAIN VIABLE** — Evidence produced but strategic choice remains with ARB

**AWAITING:** ARB Review and Strategic Model Selection Decision

