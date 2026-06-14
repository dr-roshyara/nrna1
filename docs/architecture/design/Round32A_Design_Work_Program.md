# Round 32A — Design Work Program

**Date:** 2026-06-08

**Phase:** Design Planning

**Type:** Work Program Document

**Authority:** Architecture Review Board

**Governance Foundation:** Round 32 Design Governance Charter (APPROVED)

**Purpose:** Define how the approved design phase will be organized under the Round 32 Design Governance Charter. This document organizes design work. It does NOT perform design.

---

## 1. Purpose

### Why the Design Phase Exists

The discovery program (Rounds 17-29) produced a stable, evidence-based domain model. The design phase refines the discovered domain model into design artifacts under governance discipline.

Design is authorized to begin because:
- Discovery is complete (Round 29 ARB Closure Statement)
- Readiness assessment is complete (Round 31)
- Conditional design authorization is granted (Round 31A — Option B)
- Governance charter is approved (Round 32)

### Relationship to Round 32 Design Governance Charter

Round 32 governs HOW design proceeds.

Round 32A establishes HOW design work will be organized under that governance.

All design activities must comply with the Round 32 Design Governance Charter.

### Relationship to Subsequent Phases

Round 32A is the work program. Subsequent design phases are to be determined by ARB. No future phase structure is authorized by this document.

---

## 2. Design Streams

Four streams organize categories of design work. Streams identify scope categories. They do NOT classify which contexts or aggregates belong within them. That classification belongs to design execution.

### Stream A — Design Activities

**Scope:** Design work for which governance dependencies are not currently blocking.

**Governance Constraint:** All design decisions require ADRs. Context, aggregate, or invariant changes require ADR + ARB approval.

---

### Stream B — Governance-Constrained Design Activities

**Scope:** Design work for which active governance dependencies (D35, D36, D37, ADH-1) must be resolved before design can be finalized.

**Governance Constraint:** Design conclusions depending on unresolved governance items may not be approved until those items are resolved and ARB confirms resolution.

---

### Stream C — D42B Investigation

**Scope:** Investigate the nature, responsibility, and implications of verifiability within the accepted domain model.

**Foundation:** D42B reclassified as Design Knowledge Gap (Round 30C.1). Investigation uses approved evidence sources under literature governance rules.

**Governance Constraint:** Investigation follows the Round 32 Design Governance Charter literature governance rules. Literature may not override discovery findings. Recommendation requires ARB review.

---

### Stream D — Cross-Context Integration Review

**Scope:** Review how discovered contexts interact at boundaries. Identify integration candidates, not design them.

**Foundation:** Round 29 Bounded Context Catalog (context relationships).

**Governance Constraint:** Cross-context design may not merge, split, or redefine contexts without ADR + ARB approval.

---

## 3. Candidate ADR Register

The following ADR categories are expected during design. These are categories only. Specific ADR topics will be identified during design execution.

| Category | Description | Governance Sensitivity |
|----------|-------------|----------------------|
| **Governance** | ADRs integrating D35/D36/D37/ADH-1 resolution into design | HIGH — ARB approval required |
| **D42B** | ADR recording verifiability ownership decision | HIGH — ARB approval required |
| **Boundaries** | ADRs addressing context or aggregate boundary decisions | HIGH — ARB approval required |
| **Invariants** | ADRs refining or formalizing discovered invariants | HIGH — ARB approval required |
| **Model Refinement** | ADRs refining ADG-2, ADC-1, ADC-2, ADGR-1 | MEDIUM — ARB review required |
| **Integration** | ADRs addressing cross-context integration decisions | HIGH — ARB approval required |

The complete ADR inventory will be discovered during design execution. This register defines expected categories, not the complete ADR set.

---

## 4. Governance Dependency Register

Active governance dependencies must be tracked throughout the design phase. No dependency may be treated as resolved until ARB confirms resolution.

| Item | Question | Status | Design Impact | Escalation Path |
|------|----------|--------|---------------|-----------------|
| **D35** | What happens when legitimacy = EXPIRED? | CONFIRMED — consequences unknown | Blocks finalization of legitimacy-consequence design | Escalate to ARB |
| **D36** | Who may invoke ConstitutionalArbitrationKernel? | UNRESOLVED | Blocks finalization of arbitration-invocation design | Escalate to ARB |
| **D37** | How is legitimacy determination enforced? | PARTIALLY RESOLVED | Blocks finalization of enforcement-integration design | Escalate to ARB |
| **ADH-1** | What authority hierarchy is evidenced? | PARTIALLY RESOLVED | Blocks finalization of authority-hierarchy design | Escalate to ARB |
| **D42B** | Nature, responsibility, and implications of verifiability | DESIGN KNOWLEDGE GAP | Blocks finalization of verifiability-related design | ARB review after Stream C |
| **ADG-2** | Delegation scope refinement | MODEL REFINEMENT | Refinable during design per ADR process | ADR + ARB review |
| **ADC-1** | Role rule refinement | MODEL REFINEMENT | Refinable during design per ADR process | ADR + ARB review |
| **ADC-2** | Role rule refinement | MODEL REFINEMENT | Refinable during design per ADR process | ADR + ARB review |
| **ADGR-1** | ReplaySession governance refinement | MODEL REFINEMENT (governance-dependent) | Refinable only after governance resolution | ADR + ARB approval |

---

## 5. Design Artifact Catalog

The following artifact categories are expected at design phase close. This catalog defines artifact types. Artifacts have not been created and will not be created until design execution phases.

| Artifact Category | Description |
|-------------------|-------------|
| Aggregate Designs | Design documents per discovered aggregate |
| Value Object Catalog | Catalog of domain value objects |
| Domain Event Catalog | Catalog of domain events |
| Command Catalog | Catalog of domain commands |
| Invariant Formalization | Formalized invariant specifications |
| Context Interaction Models | Context relationship and integration models |
| Published Language Definitions | Per-context published language |
| ADR Collection | All ADRs produced during design phase |
| D42B Investigation Report | Stream C investigation conclusion |
| Governance Dependency Resolution Register | Final status of all governance dependencies |

**Note:** Artifact categories listed here do not imply authorization, sequencing, or mandatory production. The catalog defines expected artifact types for planning purposes only.

---

## 6. Design Review Framework

### Periodic ARB Design Reviews

ARB conducts periodic governance reviews throughout the design phase per the Round 32 Design Governance Charter.

Each review assesses:
- ADR compliance
- Governance question handling
- Discovery model boundary compliance
- Continuation authorization

Review frequency and triggers are determined by ARB, not by this work program.

### ADR Review Process

Per Round 32 Design Governance Charter:

1. Design team authors ADR as PROPOSED
2. Design team submits to ARB for review
3. ADRs affecting context boundaries, aggregate boundaries, invariant ownership, D35/D36/D37/ADH-1, or D42B require ARB approval before implementation
4. Model-refinement ADRs require ARB review before being marked APPROVED

### Governance Compliance Verification

At each ARB review, compliance is verified per the Round 32 Design Governance Charter compliance definition.

---

## 7. Round 32A Exit Criteria

Round 32A closes when:

✅ Work program reviewed and approved by ARB
✅ Design streams approved by ARB
✅ Candidate ADR Register approved by ARB
✅ Governance Dependency Register approved by ARB
✅ Design Artifact Catalog approved by ARB
✅ Design Review Framework approved by ARB

Subsequent design phases may begin after ARB approves Round 32A.

---

## ARB Work Program Review

Round 32A is submitted for ARB review.

ARB must determine:

- Are the design streams correctly scoped?
- Is the Candidate ADR Register complete?
- Is the Governance Dependency Register accurate?
- Is the Design Artifact Catalog appropriate?
- Is the Design Review Framework sufficient?
- Is Round 32A ready to close?

Upon ARB approval of Round 32A: design execution may begin.

