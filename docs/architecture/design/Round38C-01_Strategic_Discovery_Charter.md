# Round 38C-01 — Strategic Discovery Charter

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 38C-01 — Strategic Discovery Charter
**Status:** APPROVED WITH REVISIONS (R1–R5 APPLIED; Senior DDD Architect + Constitutional Governance Architect + Online Voting Systems Architect 2026-06-18)
**Purpose:** Define how Strategic DDD Discovery will be conducted — not to perform discovery
**Date:** 2026-06-18

**Input:**
- Round38C-Authorization-Decision (APPROVED WITH REVISIONS R1–R5)
- All 38B governance specifications (38B-01 through 38B-05)
- All standing observations (OBS-38B06-05; OBS-38B07-01/02/03; OBS-38B08-01/02/03/04; OBS-38C-01)
- All binding constraints (ARB-CONSTRAINT-38C-01; ARB-CONSTRAINT-38C-02)

**This document defines the discovery process.**
**It does not perform discovery.**

---

## Required Output 1 — Discovery Objectives

The objectives of Round 38C Strategic DDD Discovery are:

**OBJ-01 — Constitutional Capability Translation**
Translate the constitutional governance specifications from 38B into DDD structural terms, identifying which constitutional requirements impose structural constraints on technical realization.

**OBJ-02 — OQ-38B05-07 Resolution**
Resolve the ADR ↔ EC Relationship Precedence Rule before any technical architecture ADRs are written. This is a foundational governance prerequisite: its resolution governs the authority of all subsequent architectural decisions.

**OBJ-03 — OQ-38B05-05 Primary Evaluation**
Evaluate Trust Root Structural Separation under technical realization perspective. Present Options A, B, and C for ARB adjudication. 38C may not close without this evaluation.

**OBJ-04 — OBS-38B06-05 Validation**
For each constitutional capability area, assess whether the 38B governance specification is or is not sufficient under technical realization. Document gaps explicitly — not as failures but as input to future rounds.

**OBJ-05 — OBS-38B08-04 Evaluation**
Assess whether technical realization amplifies or mitigates governance concentration, trust-root dependency, and authority legitimacy concerns identified in 38B. This assessment shapes the technical architecture authorization scope for future rounds.

**OBJ-06 — F-4/TM-39 Governance Interface Definition**
Define the operational governance interface for the Independence Illusion risk. Constitutional specification cannot mitigate F-4/TM-39 — but technical architecture must not make it worse.

**OBJ-07 — New Constitutional Question Discovery**
Document any new constitutional questions discovered during strategic DDD analysis and submit to ARB. Discovery that surfaces contradictions, ambiguities, or gaps in 38B does not reopen 38B — it creates new governance questions for future rounds.

---

## Required Output 2 — Discovery Scope

### In Scope

```
38C-01 authorizes discovery of:

  Domain Capability Discovery
    - What constitutional capabilities must exist in the domain?
    - Which capabilities are load-bearing (failure = constitutional failure)?
    - Which capabilities interact and how?

  Constitutional Capability Analysis
    - How do 38B governance specifications translate into domain terms?
    - Which authority aggregates (from ADR-1 through ADR-5) are
      structurally required by constitutional mandate vs. by design choice?
    - Where does constitutional mandate constrain DDD structural options?

  Responsibility Discovery
    - Which domain responsibilities are constitutionally assigned?
    - Which responsibilities are unassigned (carrying D43 risk)?
    - Where do responsibilities conflict or overlap across authority boundaries?

  Governance Interaction Discovery
    - How do the seven authority aggregates interact at a governance level?
    - Which governance interactions create concentration risk?
    - Which governance interactions create self-referential risk?

  Trust-Root Interaction Discovery
    - How do the three trust roots (Legitimacy/Authenticity/Temporal) interact
      under technical realization?
    - Where does technical realization create separation risk (OQ-38B05-05)?
    - Where does technical realization create shared-dependency risk?

  Capability Dependency Discovery
    - Which constitutional capabilities depend on which others?
    - Which dependencies are load-bearing (dependency failure = constitutional failure)?
    - Which dependencies create concentration chains (e.g., CO-5 → CO-2/CO-3/CO-4;
      CIC → EC; AC-31 → MA designation; GovernanceState → GovernanceAuthority)?
    - Which dependencies create circular validation risks?
    - Which dependencies were identified in 38A/38B and which are new to 38C?
```

### Out of Scope

```
38C-01 explicitly excludes from discovery:

  Bounded context definitions
  Context maps
  Aggregate designs (beyond what exists in ADR-1 through ADR-7)
  Domain event catalog expansions
  Service designs
  API designs
  Protocol selections
  Cryptographic architecture
  Infrastructure topology
  Deployment architecture
  Implementation specifications
```

---

## Required Output 3 — Discovery Exclusions (Binding)

The following are binding exclusions that apply to every 38C document:

**EXCL-01:** No bounded context may be named, defined, or implied as a design output.

**EXCL-02:** No aggregate may be added to the constitutional model beyond the eight authority aggregates already established in ADR-1 through ADR-7 and 38B-01. If new aggregates appear warranted, that is a new question for ARB — not a discovery output.

**EXCL-03:** No cryptographic protocol, algorithm, or scheme may be selected or recommended. Cryptographic concerns may be identified as constitutional requirements, but selection belongs to future rounds.

**EXCL-04:** No deployment or infrastructure topology may be proposed.

**EXCL-05:** No implicit resolution of OQ-38A05-02 (Finality vs. Validity). Any 38C document that touches evidence finality, certification finality, or post-completion constitutional review must carry an explicit non-resolution note.

**EXCL-06:** No claim that any 38B specification is sufficient under adversarial conditions. OBS-38B06-05 and OBS-38C-01 apply — discovery will surface sufficiency questions, not certify sufficiency.

---

## Required Output 4 — Evidence Requirements

Each 38C discovery document must satisfy:

**ER-01 — Constitutional Grounding**
Every discovery finding must be traceable to a constitutional source: a confirmed gap (3–7), a standing OBS, a confirmed threat finding (from 38A), an ADR invariant, or a constitutional constraint (AC-01 through AC-31). Ungrounded findings are observations, not constitutional discoveries.

**ER-02 — OBS-38B06-05 Acknowledgment**
Every capability-area assessment must explicitly state whether the 38B specification is provisionally sufficient, insufficiently specified, or creates new questions under technical realization.

**ER-03 — OQ-38A05-02 Non-Resolution Marker**
Any document that evaluates evidence finality, challenge finality, or post-election review must carry:
```
OQ-38A05-02 PROTECTED: This analysis does not resolve Finality vs. Validity.
```

**ER-04 — New Question Registration**
Any new constitutional question discovered in 38C must be immediately registered with a unique identifier (OQ-38C-XX) and submitted for ARB ruling before being used as a foundation for further analysis.

**ER-05 — AA-01 Dependency Acknowledgment**
Any discovery finding that increases or decreases MA functional dependency must explicitly state the direction of change and update the MA concentration inventory.

---

## Required Output 5 — Governance Interaction Model

### The Eight Authority Aggregates (from ADR-1 through ADR-7; 38B-01)

The following eight authority aggregates carry into 38C as the established authority map. Discovery may identify new governance interaction patterns among them but may not redesign them without ARB authorization.

| Aggregate | Constitutional Source | Primary Function |
|-----------|-----------------------|------------------|
| EnrollmentAuthority | 38B-04 / ADR-2 | Voter enrollment governance |
| CriteriaAuthority | 38B-04 / ADR-2 | Election criteria governance |
| AuditScopeAuthority | ADR-4 | Audit constitutional scope |
| AuditExecutionAuthority | ADR-4 | Audit execution independence |
| GovernanceAuthority | 38B-04 / ADR-2 | Phase transition authorization |
| CertificationAuthority | ADR-6 | Election result certification |
| ChallengeAdjudicationBody | ADR-5 | Challenge adjudication |
| ConstitutionalInterpretationChamber (CIC) | 38B-01 | Constitutional interpretation; CIC interprets, CAB adjudicates (38B01-INV-01) |

**Note on CIC:** CIC is structurally essential to OQ-38B05-07 analysis — the ADR-EC relationship determines which body has jurisdiction over architectural disputes, and CIC's interpretive role is central to that determination. OQ-38B05-07 cannot be analyzed correctly without CIC in the authority inventory.

**GovernanceState** is not an authority aggregate — it records constitutional authority; it does not hold it (ADR-7).

### Governance Interaction Discovery Focus

38C discovery must evaluate:

1. Which authority interactions among the eight aggregates are constitutionally mandated vs. architecturally chosen?
2. Which interactions create self-referential risk (ADR7-INV-02)?
3. Which interactions create concentration risk not yet documented in 38B?
4. Which interactions depend on OQ-38B05-07 resolution (ADR-EC relationship)?

---

## Required Output 6 — Trust-Root Analysis Model

### The Three Trust Roots (from 38A-05; 38B-03; 38B-06)

| Trust Root | Anchor | Constitutional Governance |
|------------|--------|---------------------------|
| Legitimacy | ElectionConstitution | 38B-01 (CIC interpretation); 38B-05 (amendment protection) |
| Authenticity | AC-31 | 38B-02 (Multi-Party Tiered Governance) |
| Temporal | GovernanceState | 38B-03 (EC-Anchored Phase Specification) |

**Structural differentiation status:** Confirmed as structurally differentiated in current design. Independence under adversarial conditions: not yet validated (OBS-38B06-02). Trust root separation does not have explicit constitutional protection below Tier 3 — a Tier 2 coalition could collapse two roots without triggering Tier 3 (OQ-38B05-05, open).

### Trust-Root Analysis Focus

38C discovery must evaluate:

1. Does technical realization preserve or erode trust root structural differentiation?
2. Where do trust roots share a common dependency (AA-01; MA; EC)?
3. Which constitutional capability realizations create trust root coupling risk?
4. What evidence does technical realization provide for OQ-38B05-05 Options A, B, or C?

---

## Required Output 7 — OQ-38B05-07 Evaluation Framework

**Question:** What is the relationship between ADR architectural decisions and EC tier provisions? Until resolved, CA independence form and non-CIC/CAB D43 independence requirements have ambiguous constitutional protection.

**Why this is a foundational governance prerequisite, not merely a sequencing gate:**
- If ADRs are architecturally independent of EC (Option A): architectural decisions can diverge from constitutional requirements without triggering amendment processes
- If ADRs are EC Tier 2 instruments (Option B): architectural decisions carry constitutional weight and amendment procedures apply
- If hybrid principle/form distinction (Option C): EC provides independence principles; ADRs provide independence forms; each has distinct constitutional standing

All three affect which body has authority to change architectural decisions, whether constitutional challenge procedures apply to architectural decisions, and whether future 38C technical architecture ADRs are constitutionally protected or merely advisory.

**Evaluation methodology:**

| Step | Action |
|------|--------|
| Step 1 | Enumerate all existing ADRs (ADR-1 through ADR-7) and classify each by its relationship to EC provisions |
| Step 2 | Identify which ADR decisions would require EC amendment procedures if they needed to change |
| Step 3 | Identify which ADR decisions could change without EC amendment under current ambiguity |
| Step 4 | Assess each Option (A/B/C) against constitutional survivability requirements |
| Step 4b | Identify every existing ADR invariant whose interpretation changes under Option A, B, or C — at minimum: ADR3-INV-01 (evidence strata), ADR5-INV-01 (R-8 terminal), ADR6-INV-01 (CO-5 void), ADR7-INV-01 (suspension succession), ADR7-INV-02 (anti-capture). Invariant impact analysis is required before ARB can select an option. |
| Step 5 | Present findings and invariant impact analysis to ARB for selection |

**Output required before first 38C technical architecture ADR:**
A formal OQ-38B05-07 resolution document presenting all three options with constitutional analysis, submitted to ARB for decision.

---

## Required Output 8 — OQ-38B05-05 Evaluation Framework

**Question:** Does trust root structural separation require explicit constitutional protection, or is architectural design sufficient?

**Why this is a primary requirement:**
38B identified three structurally differentiated trust roots but did not evaluate whether their separation requires constitutional enforcement. The current Protected Core Catalog (Tier 3) does not include trust root structural separation as an item. A Tier 2 coalition could collapse the Legitimacy and Authenticity roots (e.g., MA simultaneously controls EC and AC-31 designation) without triggering the Tier 3 near-unanimity threshold.

**Evaluation methodology:**

| Step | Action |
|------|--------|
| Step 1 | Map all shared dependencies across the three trust roots (AA-01, MA, ElectionConstitution) |
| Step 2 | Identify adversarial scenarios under which two or more trust roots could simultaneously fail |
| Step 2b | Map each trust-root separation option (A/B/C) against relevant 38A threat findings — at minimum: TM-06 (EC + CA full capture), TM-19 (AC-31 capture chain), TM-39 (Independence Illusion), TM-42 (Operational Deadlock), TM-44 (Temporal Concentration), TM-47 (Certification Chain Self-Reference). Trust root separation originated in threat analysis; evaluation must return to its threat basis. |
| Step 3 | Evaluate whether existing Tier 2 amendment process provides adequate protection |
| Step 4 | Assess each Option against constitutional survivability: Option A (Tier 3 protection), Option B (Tier 2 provision), Option C (architectural design reliance) |
| Step 5 | Present findings to ARB for adjudication — mandatory before 38C closure |

**This evaluation may not be deferred within 38C without explicit ARB authorization.**

---

## Required Output 9 — ARB Review Checkpoints

38C is subject to mandatory ARB review at the following points:

| Checkpoint | Trigger | Required Before |
|------------|---------|-----------------|
| CP-01 | OQ-38B05-07 resolution document produced | Any 38C technical architecture ADR |
| CP-02 | OQ-38B05-05 evaluation complete | 38C closure |
| CP-03 | OBS-38B06-05 assessed for all constitutional capability areas | 38C closure |
| CP-04 | F-4/TM-39 governance interface defined | 38C closure |
| CP-05 | Any new constitutional question (OQ-38C-XX) registered | Use of that question as analytical foundation |
| CP-06 | Any discovery output that implies a bounded context, aggregate, or service | Prohibited without prior ARB authorization |

---

## Required Output 10 — Closure Criteria for 38C-01

This charter document (38C-01) closes when:

| # | Criterion |
|---|-----------|
| CC-01 | All ten required outputs confirmed complete and internally consistent |
| CC-02 | Discovery objectives OBJ-01 through OBJ-07 confirmed in scope |
| CC-03 | Discovery exclusions EXCL-01 through EXCL-06 confirmed in force |
| CC-04 | Evidence requirements ER-01 through ER-05 confirmed applicable |
| CC-05 | OQ-38B05-07 evaluation framework confirmed sufficient to produce CP-01 output |
| CC-06 | OQ-38B05-05 evaluation framework confirmed sufficient to satisfy XC-01 exit criterion |
| CC-07 | ARB review checkpoints CP-01 through CP-06 confirmed |
| CC-08 | ARB approval of this charter issued |

**38C-02 and subsequent discovery documents may not begin until 38C-01 is approved.**

---

## Discovery Invariant

```
38C01-INV-01 — Discovery Is Not Decision

Discovery findings shall not be treated as architectural decisions.

All discovery outputs remain hypotheses
until explicitly accepted by ARB.

This invariant applies to every document in Round 38C.
It is non-waivable within 38C scope.
It may not be overridden by individual architects,
working groups, or document authors.

Consequence: any 38C document that presents
a discovery finding as a settled architectural decision —
without explicit ARB acceptance —
is in violation of this invariant.
```

---

## Summary

```
38C-01 PURPOSE:

Define HOW Strategic DDD Discovery will be conducted.
Not to perform discovery.

GOVERNANCE DISCIPLINE:

OQ-38B05-07 must resolve before any technical architecture ADR.
OQ-38B05-05 must be addressed as primary requirement before 38C closes.
OQ-38A05-02 remains PROTECTED throughout.
ARB-CONSTRAINT-38C-01 and ARB-CONSTRAINT-38C-02 in force throughout.
OBS-38B06-05 applies to every deliverable.
OBS-38C-01: authorization ≠ confirmation of 38B correctness.

NEXT STEP (pending ARB approval of this charter):

38C-02 — OQ-38B05-07 Resolution
  (foundational governance prerequisite for all subsequent 38C work)
```

---

*Round 38C-01 — Strategic Discovery Charter — APPROVED WITH REVISIONS (R1–R5 APPLIED; Senior DDD Architect + Constitutional Governance Architect + Online Voting Systems Architect 2026-06-18)*
*Research Program: NRNA DDD Trustworthiness*
*Date: 2026-06-18*
*Input: Round38C-Authorization-Decision (APPROVED WITH REVISIONS R1–R5)*
*Revisions: R1 (8 authority aggregates — CIC added); R2 (Capability Dependency Discovery added to scope); R3 (OQ-38B05-07 Step 4b — ADR invariant impact analysis); R4 (OQ-38B05-05 Step 2b — 38A threat correlation); R5 (38C01-INV-01 — discovery ≠ decision, non-waivable)*
*Next step: 38C-02 OQ-38B05-07 Resolution*
