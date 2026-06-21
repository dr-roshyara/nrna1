# Round 38C — Authorization Decision

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 38C Authorization Decision
**Status:** APPROVED WITH REVISIONS (R1–R5 APPLIED; Senior DDD Architect + Constitutional Governance Architect + Online Voting Systems Architect 2026-06-18)
**Purpose:** Issue the formal ARB ruling on whether Round 38C Strategic DDD Discovery may begin
**Date:** 2026-06-18

**Input:** Round38C-Authorization-Review (SUBMITTED FOR ARB DECISION)

---

## Part A — Review of Authorization Review

The Authorization Review produced:

| Output | Verdict |
|--------|---------|
| Evaluation 1 — 38B sufficiency | CONFIRMED |
| Evaluation 2 — Mandatory scope | M-01 through M-07 defined |
| Evaluation 3 — Forbidden scope | ARB-CONSTRAINT-38C-01 in force |
| Evaluation 4 — Carry-forward constraints | 8 invariants confirmed |
| Evaluation 5 — OQ-38B05-05 handling | Mandate defined; options A/B/C for ARB |
| Evaluation 6 — OQ-38B05-07 handling | Mandate defined; early-requirement gate |
| Evaluation 7 — OQ-38A05-02 protection | Structural enforcement confirmed |
| Authorization recommendation | AUTHORIZE |
| Entry criteria EC-01 through EC-06 | ALL MET |
| Entry criterion EC-07 | PENDING THIS DECISION |

---

## Part B — Entry Criteria Confirmation

| # | Criterion | Status |
|---|-----------|--------|
| EC-01 | 38B-08 formally approved | CONFIRMED |
| EC-02 | OQ-38B05-05 carried as mandatory primary requirement | CONFIRMED |
| EC-03 | OQ-38B05-07 carried as mandatory early requirement | CONFIRMED |
| EC-04 | OBS-38B06-05 acknowledged as permanent | CONFIRMED |
| EC-05 | OQ-38A05-02 protection acknowledged | CONFIRMED |
| EC-06 | ARB-CONSTRAINT-38C-01 acknowledged | CONFIRMED |
| EC-07 | This authorization decision approved | THIS RULING |

All six prior conditions are met. EC-07 is resolved by this document.

---

## Part C — Authorization Ruling

```
ROUND 38C

AUTHORIZED TO COMMENCE
STRATEGIC DDD DISCOVERY

subject to Parts C–G of this document.

Effective: 2026-06-18

Scope: As defined in 38C Authorization Review Required Output B
       and further constrained by Parts D–G below.
       ARB-CONSTRAINT-38C-01 and ARB-CONSTRAINT-38C-02
       are both in force from first day.

Authorization is conditional on:

  (1) OQ-38B05-07 resolved before any 38C technical architecture ADR
      is written. OQ-38B05-07 is a foundational governance prerequisite
      affecting ADR authority, architecture authority, constitutional
      authority, and interpretation authority. It is not merely a gate —
      it is the governance relationship that all subsequent architecture
      decisions depend on.

  (2) OQ-38B05-05 addressed as a primary requirement.
      38C may not close without ARB adjudication of Options A, B, or C.

  (3) OQ-38A05-02 maintained PROTECTED throughout.
      No implicit resolution permitted.

  (4) OBS-38B06-05 applied to every 38C deliverable.
      Governance specification ≠ governance sufficiency.
      Technical realization must be evaluated explicitly.

Authorization does not constitute:

  Permission to produce bounded contexts
  Permission to produce aggregate designs
  Permission to produce event models
  Permission to produce API or protocol decisions
  Permission to produce cryptographic architecture

Those items require a further ARB decision within 38C
when Strategic DDD Discovery has produced sufficient
grounding evidence.
```

### C.4 — Discovery Discipline Constraint

```
ARB-CONSTRAINT-38C-02

38C discovery shall focus on:

  - domain capability discovery
  - constitutional capability analysis
  - responsibility discovery
  - governance interaction discovery
  - trust-root interaction discovery

38C shall not produce:

  - bounded contexts
  - aggregates
  - entities
  - events
  - services
  - APIs
  - protocols
  - cryptographic selections

unless separately authorized by ARB.

This constraint exists to protect the governance
sequence established since Round 36. Strategic DDD
Discovery produces grounding evidence for future
architecture decisions. It does not produce those
decisions itself.
```

---

## Part D — Standing Constraints Carried Into 38C

The following are binding from first activity inside 38C:

| Constraint | Source | Force |
|------------|--------|-------|
| ARB-CONSTRAINT-38C-01 | 38B-08 Part C.3 | No technical architecture without further ARB decision |
| OQ-38B05-07 foundational governance prerequisite | 38B-07/08 | Must resolve before first 38C technical ADR; governs ADR/architecture/constitutional/interpretation authority |
| OQ-38B05-05 primary-requirement mandate | 38B-07/08 | Must address as primary requirement; cannot defer again without ARB |
| OQ-38A05-02 protection | 38A-06 | Structural — applies to all deliverables implicitly |
| OBS-38B06-05 (permanent) | 38B-06 | Applies to every 38C deliverable explicitly |
| 38B01-INV-01 (CIC interprets; CAB adjudicates) | 38B-01 | All capability designs must respect this |
| ADR3-INV-01 (evidence strata distinct) | ADR-3 | Evidence architecture may not collapse strata |
| ADR5-INV-01 (R-8 terminal) | ADR-5 | Challenge architecture carries unchanged |
| ADR6-INV-01 (CO-5 void without CO-2+CO-3+CO-4) | ADR-6 | Certification design carries unchanged |
| ADR7-INV-01 (suspension succession in EC) | ADR-7 | Governance state carries unchanged |
| ADR7-INV-02 (anti-capture invariant) | ADR-7 | No authority may self-expand standing |

---

## Part E — Carry-Forward Register (Confirmed)

### E.1 — Governance Questions

| Question | Mandate | 38C Status |
|----------|---------|------------|
| OQ-38B05-05 | MANDATORY PRIMARY | Must address; cannot close 38C without ARB adjudication of Options A/B/C |
| OQ-38B05-07 | MANDATORY EARLY (foundational governance prerequisite) | Must resolve before first 38C technical architecture ADR |
| OQ-38B04-04 | IMPORTANT | Address within 38C discovery |
| OQ-38B05-06 | IMPORTANT | Address within 38C discovery |
| OQ-38B05-02 | IMPORTANT | Address within 38C discovery |
| OQ-38B03-01 | IMPORTANT | Temporal challenge bootstrapping; address within 38C |

### E.2 — Standing Observations

| Observation | Character | 38C Application |
|-------------|-----------|-----------------|
| OBS-38B06-05 | PERMANENT | Applies to every 38C deliverable — specification ≠ sufficiency |
| OBS-38B07-03 | PERMANENT | F-4/TM-39 Independence Illusion is program-level risk; governance interface required in 38C |
| OBS-38B08-04 | BRIDGE | 38C evaluates whether technical realization amplifies or mitigates governance concerns |
| OBS-38C-01 | THIS DOCUMENT | 38C authorization ≠ confirmation that 38B specifications are correct; they are inputs, not proofs |

### E.3 — Binding Constraints

| Constraint | Source | Force |
|------------|--------|-------|
| ARB-CONSTRAINT-38C-01 | 38B-08 Part C.3 | No bounded contexts/aggregates/events/APIs/protocols/crypto without further ARB decision |
| ARB-CONSTRAINT-38C-02 | This document Part C.4 | Discovery scope discipline — domain/constitutional/responsibility/governance/trust-root only |

---

## Part E.4 — Authorization Observation

**OBS-38C-01 — Maturity vs. Correctness:**
The authorization of 38C does not imply that the governance specifications of 38B are correct. It implies only that they are sufficiently mature to become inputs to strategic discovery. This continues the discipline of OBS-38B06-05: specification completeness is not governance sufficiency; authorization for next-phase discovery is not confirmation of current-phase correctness. 38C will encounter places where 38B specifications prove incomplete, ambiguous, or in tension with technical realization. That is expected and does not require reopening 38B — it requires documenting new questions for ARB ruling.

---

## Part F — Protected Question Register (Confirmed)

| Question | Status | Routing |
|----------|--------|---------|
| OQ-38A05-02 (Finality vs. Validity) | PROTECTED — permanent | CIC when case arises |
| OQ-38B05-04 (OQ-38A05-02 × Amendment) | PROTECTED | CIC when case arises |
| AA-01 (MA Legitimacy) | PRE-CONSTITUTIONAL | Outside 38C scope; acknowledge dependency only |
| Gap 8 (Post-Finality Constitutional Review) | DEFERRED — OQ-38A05-02 dependent | Cannot advance until OQ-38A05-02 routes to CIC |

---

## Part G — Exit Criteria (Confirmed)

38C Strategic DDD Discovery may not close without:

| # | Criterion |
|---|-----------|
| XC-01 | OQ-38B05-05 adjudicated by ARB (Option A, B, or C selected, or re-deferred with explicit ARB authorization) |
| XC-02 | OQ-38B05-07 resolved (Options A, B, C evaluated; one selected) |
| XC-03 | Each constitutional capability area evaluated against OBS-38B06-05 |
| XC-04 | F-4/TM-39 Independence Illusion governance interface defined |
| XC-05 | OQ-38A05-02 protection confirmed maintained throughout 38C |
| XC-06 | Any new constitutional questions discovered in 38C submitted for ARB ruling |

---

## Part H — Program State After This Ruling

```
PROGRAM STATE — 2026-06-18

Round 36A–36E  Constitutional Discovery       COMPLETE
Round 37        ADR Authoring                 COMPLETE
Round 38A       Threat Validation             CLOSED
Round 38B       Constitutional Governance     CLOSED WITH DEFERRED QUESTIONS

Round 38C       Strategic DDD Discovery       AUTHORIZED
                                              Binding constraints: Parts C, D, E, F, G

Technical Architecture:                       NOT YET EVALUATED
                                              (requires further ARB decision within 38C)

Foundational governance prerequisite (resolve first):
  OQ-38B05-07 (ADR ↔ EC Relationship) —
  must be resolved before writing any 38C technical architecture ADR.
  This governs ADR authority, architecture authority,
  constitutional authority, and interpretation authority.
  It is not merely a sequencing gate.

Primary requirement inside 38C:
  Address OQ-38B05-05 (Trust Root Structural Separation).
```

---

## Part I — Governing Sequence

```
38B-08 Closure Decision             APPROVED
        ↓
38C Authorization Review            APPROVED
        ↓
38C Authorization Decision          THIS DOCUMENT
        ↓
38C-01 Strategic Discovery Charter  Define the discovery process
        (NOT discovery itself)
        ↓
38C-02+ Strategic DDD Discovery     Commence discovery
        (foundational prerequisite: OQ-38B05-07)
        (primary requirement: OQ-38B05-05)
        (throughout: ARB-CONSTRAINT-38C-01/02)
        ↓
ARB Review of Discovery Outputs
        ↓
[Future] Potential Authorization for Context Discovery
```

---

*Round 38C — Authorization Decision — APPROVED WITH REVISIONS (R1–R5 APPLIED; Senior DDD Architect + Constitutional Governance Architect + Online Voting Systems Architect 2026-06-18)*
*Research Program: NRNA DDD Trustworthiness*
*Date: 2026-06-18*
*Input: Round38C-Authorization-Review (APPROVED)*
*Ruling: AUTHORIZED TO COMMENCE STRATEGIC DDD DISCOVERY subject to Parts C–G*
*Revisions: R1 (ruling precision — "AUTHORIZED TO COMMENCE"); R2 (OQ-38B05-07 = foundational governance prerequisite, not merely gate); R3 (Part E split into Governance Questions / Standing Observations / Binding Constraints); R4 (Technical Architecture: NOT YET EVALUATED); R5 (ARB-CONSTRAINT-38C-02 added); OBS-38C-01 added (authorization ≠ confirmation of 38B correctness)*
