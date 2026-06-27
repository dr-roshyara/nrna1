# Round 38C-04 — Principle/Form Classification Framework

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 38C-04 — Classification Framework
**Status:** SUBMITTED FOR ARB REVIEW
**Purpose:** Define the governance framework used to classify existing and future ADR elements as Principle, Form, or Ambiguous. This document does NOT perform classifications.
**Date:** 2026-06-18

**Authority:** 38C03-CON-01 — Classification Exercise Authorization (issued in Round38C-03_ARB_Ruling_OQ-38B05-07.md)

**Input:**
- Round38C-03 ARB Ruling (OQ-38B05-07 RESOLVED — Option C selected)
- Round38C-02 Evaluation (principle/form candidate observations in Part G)
- Round38C-02 ARB Review (RV-B-05, RV-B-06, RV-D-01 carry-forward)
- ADR-1 through ADR-7, 38B-01 through 38B-05

**Governing invariant:** 38C01-INV-01 — all outputs remain hypotheses until ARB acceptance.
**Governing invariant:** 38C03-INV-01 — no existing ADR invariant loses protection pending classification.

**No classifications performed here. No technical architecture. No bounded contexts. No aggregates. No domain events. No APIs. No protocols. No implementation decisions.**

---

## Part A — Authority Basis

### A.1 — Why This Framework Is Required Before the Classification Exercise

Option C (Hybrid Principle/Form Split) is the governing constitutional framework for the ADR-EC relationship, effective from the 38C-03 ruling. Option C is not self-executing.

Option C declares that:
- EC holds constitutional principles at assigned tiers
- ADRs hold implementation forms at the architectural level

It does not declare which specific elements of the existing ADR model are principles and which are forms. That determination is the work of the classification exercise (38C03-CON-01). The classification exercise cannot proceed without a governance framework that specifies how classification judgements are made.

Without a framework:
- Different classifiers could reach different conclusions using different criteria
- Classifications would have no defined evidence standard
- CIC would have no defined escalation trigger for contested elements
- The classification exercise output would not be ARB-reviewable because there is no baseline against which to assess its quality
- OQ-38B05-05 (Trust Root Structural Separation) could be implicitly resolved or implicitly dismissed depending on which criteria the classifier applies

This framework provides the methodology. The classification exercise applies the methodology to specific ADR elements.

### A.2 — What This Framework Is NOT

This framework does not:
- Classify any existing ADR element
- Pre-assign any element to principle or form status (except where 38C-03 ruling has already issued a conditional default — specifically ADR7-INV-02)
- Constrain the classification exercise to produce any particular outcome
- Resolve OQ-38B05-05

This framework provides classification criteria, evidence standards, escalation rules, trust root evaluation requirements, and output specifications. The classification exercise applies those rules to each ADR element and produces candidates. A separate ARB review and ruling then accepts, rejects, or amends those candidates.

### A.3 — Governing Sequence

```
38C-03 Ruling — Option C selected; classification exercise authorized
        ↓
38C-04 Classification Framework (THIS DOCUMENT)
        ↓
ARB review and approval of this framework
        ↓
Classification Exercise — apply framework to existing ADR elements
        ↓
ARB review of classification exercise outputs
        ↓
Classification Ruling — formal ARB designation of principles and forms
        ↓
EC Extension (38C03-CON-02) — add designated principles to EC
        ↓
First technical architecture ADR may proceed
```

---

## Part B — Definitions

These definitions are generic. They do not refer to any specific ADR content. They define the categories in constitutional governance terms so that the classification exercise can apply them to any ADR element, existing or future.

### B.1 — Principle

A **Principle** is a constitutional commitment that specifies what a governance structure must achieve — its purpose, its essential character, its constitutional function, or a constitutional constraint on how it may operate.

A Principle answers the question: *What must be true, regardless of how it is realized?*

A Principle is constitutionally binding. Its presence is required by the constitution. Its essential character is constitutionally protected. **How it is realized is not specified by the Principle.** Multiple different implementations may satisfy the same Principle; the Principle is satisfied as long as each one achieves the constitutionally required character.

Changing a Principle changes what the governance structure is constitutionally required to achieve. This is a constitutional change. Removing a Principle removes a constitutional requirement. Both require EC amendment at the tier assigned to that Principle.

**Indicators that an element is a Principle:**
- Removal changes what the governance structure is required to achieve
- The element is derivable from a constitutional mandate, fundamental right, or anti-concentration requirement
- Multiple different implementation forms could satisfy the element
- The element is at risk of violation regardless of which implementation form is used
- Violation of the element is a constitutional failure, not merely an architectural failure

### B.2 — Form

A **Form** is an architectural decision that specifies how a Principle is realized — its structural mechanism, its operational procedure, its implementation architecture, or the specific way a constitutional requirement is fulfilled within a particular design.

A Form answers the question: *How is this Principle being realized in the current design?*

A Form is architecturally binding. Its Principle governs what it must achieve; the Form specifies the chosen mechanism. Changing a Form changes how the Principle is realized, not what the Principle requires. A Form must remain consistent with its governing Principle. If a Form is changed in a way that violates the Principle, the Principle (not the Form) is what is constitutionally compromised.

**Indicators that an element is a Form:**
- Removal changes how the governance structure operates, not what it is constitutionally required to achieve
- An alternative implementation could achieve the same constitutional purpose differently
- Removal creates an architectural gap, not a constitutional one
- Violation of the element is an architectural failure; the underlying constitutional requirement would survive if a different Form were substituted

### B.3 — Ambiguous

An element is **Ambiguous** when its constitutional character is genuinely unclear and classification criteria point in different directions, or when the element simultaneously exhibits both principle-like and form-like properties in a way that cannot be resolved by applying classification criteria alone.

Ambiguity is not indecision. An Ambiguous classification is a substantive finding: it means the element requires CIC adjudication before it can be constitutionally designated.

**Indicators that an element is Ambiguous:**
- Classification criteria produce split results (some indicate Principle; some indicate Form)
- The element appears to be a Form that realizes a principle, but it is also the most constitutionally precise available expression of that principle — removing the Form would leave the Principle without any constitutionally adequate realization
- Reasonable constitutional interpretations exist for both Principle and Form
- The element's character changes depending on how the governing Principle is framed

---

## Part C — Classification Criteria

The following criteria are applied to each ADR element. Each criterion is a question. The combined answers determine whether the element is classified as Principle, Form, or Ambiguous.

**Classification Decision Rule:** An element should be designated Principle if the majority of criteria that yield a determinate answer indicate constitutional character. It should be designated Form if the majority indicate implementation character. It should be designated Ambiguous if the criteria are approximately split or if any mandatory criteria (C-7, C-9, C-10) yield an indeterminate result.

### C.1 — Constitutional Change Test

*If this element were removed entirely from the current architecture, would the governance structure's constitutional obligations change?*

- YES: Indicates Principle
- NO: Indicates Form
- UNCLEAR: Contributes to Ambiguous

### C.2 — Realization Plurality Test

*Does a materially different architectural mechanism exist that could achieve the same constitutional purpose this element serves?*

- YES (alternative exists): Indicates Form — the element is one realization among possible realizations
- NO (this element is the only constitutionally adequate realization): Indicates Principle
- UNCLEAR: Contributes to Ambiguous

**Instruction:** When answering NO, the classification must identify the specific constitutional purpose and explain why no alternative realization is constitutionally adequate. A bare NO without constitutional grounding is insufficient.

### C.3 — Challenge Rights Test

*Does removing or changing this element affect the scope, standing, or availability of constitutional challenge procedures (S-1/S-2/S-3)?*

- YES — reduces challenge availability: Strong indicator of Principle
- YES — expands challenge availability: Indicates Form (the constitutional requirement is the floor; the element may be expanded or changed without affecting the minimum requirement)
- NO: Contributes to Form
- UNCLEAR: Contributes to Ambiguous

### C.4 — Constitutional Failure Threshold Test

*If this element were violated or ignored, would the result be a constitutional failure or an architectural failure?*

- Constitutional failure (the violation would invalidate the constitutional standing of the governance structure's outputs): Indicates Principle
- Architectural failure (the violation creates an operational problem but the governance structure remains constitutionally operative): Indicates Form
- UNCLEAR: Contributes to Ambiguous

**Instruction:** Apply this test at the level of the specific element, not the broader governance structure. Ask whether violation of this element — in isolation — constitutes a constitutional failure.

### C.5 — Trust Root Integrity Test

*Does removing or changing this element affect the integrity, separation, or independence of any of the three trust roots (Legitimacy/Authenticity/Temporal)?*

- YES — affects structural integrity of a trust root: Strong indicator of Principle
- YES — affects implementation of a trust root but not its structural integrity: Indicates Form
- NO: Neutral (does not contribute to either classification independently)

Full trust root evaluation is required regardless of this criterion's outcome — see Part F.

### C.6 — Anti-Capture Test

*Does removing or changing this element reduce the constitutional protection against self-referential authority — specifically, the ability of an authority to self-grant expanded standing, self-certify its own compliance, or self-ratify its own appointment?*

- YES — reduces anti-capture protection at the constitutional level: Strong indicator of Principle
- NO: Contributes to Form
- UNCLEAR: Contributes to Ambiguous

Note: ADR7-INV-02 (anti-capture invariant) has been conditionally designated Principle-level by the 38C-03 ruling. The classification exercise must evaluate this designation using C.6 as the primary criterion and may override the conditional default only if C.6 is answered NO with constitutional grounding.

### C.7 — Independence Existence vs. Independence Form Test (MANDATORY)

*Does this element define the existence/character of an independence requirement (that independence is required and must be of a constitutional character), or does it define the implementation mechanism through which that independence is achieved?*

- Existence/character (independence must exist and must be structurally independent): Indicates Principle
- Implementation mechanism (this specific organizational structure achieves independence): Indicates Form

**This criterion is mandatory for any element relating to authority independence, separation of functions, or anti-concentration requirements.** TM-39/F-4 originates from form-level independence degradation; any element that could be degraded while satisfying a narrow existence-only reading must be classified using this criterion explicitly.

### C.8 — Constitutional Source Traceability Test

*Is this element directly traceable to a constitutional mandate, fundamental right, confirmed gap closure, or confirmed threat finding in the program record?*

- YES, directly traceable with specific reference: Strengthens Principle designation
- YES, traceable via inference chain: Weakens Principle designation (may still be Principle; inference chain must be stated)
- NO traceable constitutional source: Indicates Form; may indicate element has no constitutional standing and is architectural only

**Instruction:** State the specific constitutional source. "Derived from general governance principles" is not a constitutional source. A constitutional source is a specific gap (Gap 3–7), a confirmed threat finding (TM-XX), a confirmed ADR invariant, or a named EC provision.

### C.9 — Governance Survivability Test (MANDATORY)

*If the governing MA (Membership Assembly) were temporarily unavailable or compromised (AA-01 failure scenario), would removing or changing this element affect the governance system's ability to function in a degraded but constitutionally operative mode?*

- YES — reduces survivability: Indicates Principle (the element is load-bearing for constitutional continuity)
- NO: Neutral contribution
- UNCLEAR: Contributes to Ambiguous

**This criterion is mandatory for any element that involves succession, deadlock resolution, or operational continuity.**

### C.10 — OQ-38A05-02 Non-Interference Test (MANDATORY)

*Does classifying this element as Principle or Form implicitly resolve OQ-38A05-02 (Finality vs. Validity)?*

- YES — classification would implicitly resolve OQ-38A05-02 in either direction: Classification is BLOCKED. The element must be designated Ambiguous pending CIC adjudication of OQ-38A05-02.
- NO: Classification may proceed.

**This criterion is mandatory for any element involving certification finality, post-finality constitutional review, challenge windows, or TS-1 terminal status. OQ-38A05-02 is PROTECTED throughout 38C.**

---

## Part D — Evidence Standards

### D.1 — Required Evidence for Principle Designation

A Principle designation requires:

**E-01 — Constitutional Source Citation**
At minimum one primary constitutional source: a confirmed gap (Gap 3–7), a confirmed threat finding (TM-XX from 38A), an ADR invariant with explicit constitutional grounding, or a named EC provision. The citation must specify which aspect of the element the source grounds.

**E-02 — Constitutional Change Demonstration**
A concrete description of what constitutional obligation changes if the element is removed. "Governance would be weaker" is insufficient. The demonstration must specify which constitutional requirement, right, or protection is affected.

**E-03 — Realization Plurality Assessment**
An explicit assessment of whether alternative implementations could realize the same constitutional purpose. If no alternative exists, explain why — the explanation constitutes additional Principle evidence. If alternatives exist, the element is more likely a Form.

**E-04 — Trust Root Impact Assessment**
Per Part F — required for all candidates regardless of preliminary classification.

### D.2 — Required Evidence for Form Designation

A Form designation requires:

**E-05 — Governing Principle Identification**
Every Form must identify its governing Principle. A Form without an identified governing Principle is constitutionally unanchored and must be escalated to Ambiguous.

**E-06 — Principle Satisfaction Confirmation**
Demonstration that the Form satisfies its governing Principle. The Form may change as long as it continues to satisfy the Principle; E-06 confirms the current Form does so.

**E-07 — Alternative Realization Evidence**
At minimum one plausible alternative implementation that could satisfy the same Principle. This is evidence that the element is a Form (one realization among possible realizations). The alternative need not be preferred or endorsed; it must be constitutionally adequate.

### D.3 — Required Evidence for Ambiguous Designation

An Ambiguous designation requires:

**E-08 — Split Criteria Documentation**
Documentation of which criteria indicate Principle and which indicate Form, with rationale for each split.

**E-09 — CIC Escalation Trigger**
Identification of the specific constitutional question that CIC must adjudicate to resolve the ambiguity. A bare "we cannot determine" is insufficient. The escalation trigger must specify what constitutional interpretation is contested.

### D.4 — Confidence Levels

Each classification candidate must carry a confidence level:

| Level | Criteria | Meaning |
|-------|----------|---------|
| HIGH | Multiple independent constitutional sources; all determinate criteria point in same direction; trust root evaluation confirms | Classification is constitutionally well-grounded |
| MEDIUM | Single constitutional source, or criteria mostly aligned with one or two neutral; trust root evaluation neutral | Classification is reasonable but should receive additional scrutiny in ARB review |
| LOW | Weak or inferential constitutional source; some criteria split; trust root evaluation inconclusive | Classification should be treated as preliminary; ARB may require additional analysis |
| AMBIGUOUS | Criteria are split, or mandatory criterion (C-7, C-9, C-10) yields indeterminate result | CIC adjudication required before designation |

---

## Part E — Escalation Rules

### E.1 — When CIC Involvement Is Mandatory

CIC adjudication is mandatory before a final classification ruling when:

**EscRule-01 — Ambiguous Designation on Trust Root Element**
An element is designated Ambiguous AND the element affects any of the three trust roots (Legitimacy/Authenticity/Temporal). Trust root elements with contested classification cannot be resolved by ARB alone — CIC must interpret the constitutional boundary.

**EscRule-02 — Ambiguous Designation on Anti-Capture Element**
An element is designated Ambiguous AND it directly relates to self-referential authority protection (C.6). Anti-capture is a structural constitutional requirement derived from Tier 3 challenge rights and CIC existence; contested classification of anti-capture elements requires interpretive authority.

**EscRule-03 — OQ-38A05-02 Interference**
Any element where C.10 (OQ-38A05-02 Non-Interference Test) returns YES. Classification is blocked and routes directly to CIC.

**EscRule-04 — OQ-38B05-05 Candidate**
Any element proposed as a candidate for trust root structural separation (OQ-38B05-05 evaluation) routes to CIC as part of the OQ-38B05-05 adjudication process, regardless of confidence level. Trust root structural separation is a mandatory primary requirement for 38C and cannot be resolved by the classification exercise alone.

**EscRule-05 — Boundary Exploitation Risk**
If the classification exercise identifies that a proposed principle/form boundary could be exploited to reclassify the element without constitutional amendment (boundary contestability per RV-B-06 from the ARB review), CIC adjudication of that specific boundary is required before the boundary is finalised.

### E.2 — Classification Outcomes and What Each Requires

| Outcome | Next Step |
|---------|-----------|
| Principle (HIGH confidence) | ARB review; if approved → EC designation at assigned tier |
| Principle (MEDIUM/LOW confidence) | ARB review with enhanced scrutiny |
| Form (any confidence) | ARB review; if approved → governing Principle must be documented |
| Ambiguous (EscRule not triggered) | ARB review → if ARB cannot resolve → CIC |
| Ambiguous (EscRule triggered) | Mandatory CIC adjudication before ARB ruling |
| C.10 blocks (OQ-38A05-02) | Mandatory CIC adjudication; classification suspended |

---

## Part F — Trust Root Evaluation

Every classification candidate — regardless of preliminary Principle/Form/Ambiguous designation — must pass through Trust Root Evaluation. This is not optional.

### F.1 — Three Required Assessments

For each candidate element, the classification exercise must evaluate:

**TR-01 — Legitimacy Root Assessment**

*Does removing or changing this element affect the integrity, scope, or independence of the Legitimacy Root (ElectionConstitution)?*

- Does the element define what EC must contain or protect?
- Does the element affect the process by which EC is amended?
- Does the element affect the authority of CIC to interpret EC?
- Does the element affect the EC's standing as the constitutional ground of all authority relationships?

**TR-02 — Authenticity Root Assessment**

*Does removing or changing this element affect the integrity, constitutional standing, or governance of the Authenticity Root (AC-31)?*

- Does the element define an AC-31 constitutional requirement?
- Does the element affect multi-party governance of AC-31 (38B-02)?
- Does the element affect the detectability or reversibility of AC-31 capture?
- Does the element affect the authentication ratchet (TM-19 / TM-47 pathway)?

**TR-03 — Temporal Root Assessment**

*Does removing or changing this element affect the integrity, phase record governance, or constitutional corroboration requirements of the Temporal Root (GovernanceState)?*

- Does the element affect phase transition authorization?
- Does the element affect corroboration requirements for GovernanceState records?
- Does the element affect the constitutional detectability of GovernanceState rollback?
- Does the element relate to the temporal challenge bootstrapping problem (OQ-38B03-01)?

### F.2 — Trust Root Interaction Assessment

In addition to the three individual assessments, the classification exercise must evaluate:

**TR-04 — Simultaneous Impact Assessment**

*Does removing or changing this element affect more than one trust root simultaneously?*

Elements that affect multiple trust roots simultaneously carry higher constitutional weight. A Form that has simultaneous multi-root impact should be flagged for ARB scrutiny even if it is legitimately a Form — the classification may be correct, but the Principle it serves may need elevated tier assignment.

**TR-05 — Trust Root Separation Assessment**

*Does this element contribute to, or detract from, the structural separation of the three trust roots?*

Any element that contributes to trust root separation (OQ-38B05-05 scope) must be flagged as an OQ-38B05-05 candidate, regardless of its individual Principle/Form classification. Trust root structural separation is not a single element — it is a property of the constitutional arrangement as a whole. Elements that contribute to it may be individually classified as Forms while the separation property itself is a Principle-level candidate.

---

## Part G — OQ-38B05-05 Integration

### G.1 — Mandatory Candidate Status

Trust Root Structural Separation (OQ-38B05-05) must appear as a mandatory candidate in the classification exercise. This is required by:
- 38C-03 ruling (OQ-38B05-05 remains a mandatory primary requirement; mechanism now available via Option C; classification exercise must include trust root separation as mandatory candidate)
- 38C Authorization Decision (XC-01: OQ-38B05-05 must be adjudicated before 38C closure)

**The classification exercise may not close without explicitly addressing OQ-38B05-05 as a candidate.**

### G.2 — What Trust Root Structural Separation Is as a Candidate

Trust Root Structural Separation as a candidate Principle states:

*The three trust roots (Legitimacy, Authenticity, Temporal) must be constitutionally protected as structurally distinct — such that no single governance action (Tier 2 coalition, single authority compromise, or adversarial coordination) can simultaneously invalidate two or more roots without triggering the near-unanimity threshold (Tier 3).*

This is a candidate formulation for the classification exercise to evaluate. The classification exercise does not accept or reject this candidate — it evaluates it using Parts C–F of this framework and produces a classification recommendation with confidence level and evidence standard.

### G.3 — What the Classification Exercise Must Produce for OQ-38B05-05

The classification exercise must produce:

1. A preliminary Principle/Form/Ambiguous designation for Trust Root Structural Separation
2. The specific criteria results from Part C (applied to the candidate as stated in G.2)
3. The trust root evaluation from Part F (TR-01 through TR-05) — noting that a property that is itself about trust root separation has a reflexive character that must be handled explicitly
4. A confidence level per Part D.4
5. If Ambiguous: the specific constitutional question for CIC escalation
6. If Principle: the proposed EC tier assignment (Tier 1, 2, or 3) with rationale, grounded in the 38B-05 Protected Core Catalog criteria

**No conclusion may be drawn about OQ-38B05-05 in this framework document. G.1 through G.3 define the evaluation obligation only.**

---

## Part H — OBS-38B06-05 Application

**OBS-38B06-05 (PERMANENT):** Specification completeness ≠ governance sufficiency.

This applies to the classification exercise directly:

### H.1 — Classification Completeness vs. Correctness

**Classification completeness** means: every ADR element has been evaluated and assigned a Principle/Form/Ambiguous designation with the required evidence.

**Classification correctness** means: the designations are constitutionally adequate — that the chosen principle/form boundaries will withstand adversarial challenge, governance evolution, and technical realization.

These are not the same. A complete classification exercise that assigns every element to a category has not thereby proven that those assignments are constitutionally correct. The classifications are proposals for ARB review. The ARB review assesses correctness within the limits of current program knowledge. OBS-38B06-05 means that even an ARB-approved classification may prove insufficient under subsequent technical realization or adversarial challenge.

### H.2 — Required Acknowledgment

Every output document from the classification exercise must include the following acknowledgment:

```
OBS-38B06-05 ACKNOWLEDGMENT:
Classification completeness does not constitute classification
correctness. These classifications represent the ARB's best
constitutional assessment at the time of designation. Technical
realization (38D+) and adversarial review may surface cases where
a Form-classified element should have been designated Principle,
or where a Principle boundary was drawn too broadly or too narrowly.
Such findings are expected, not failures. They route to ARB for
re-classification or to CIC if disputed.
```

### H.3 — Re-Classification Mechanism

A re-classification mechanism must be established before the classification exercise closes. Re-classification criteria:
- Discovery during technical realization that an element designated Form has been degraded in a way that violates its governing Principle
- Adversarial challenge that successfully argues an element's classification is constitutionally incorrect
- CIC interpretation that changes the constitutional character of an element's governing source

Re-classification of a Principle designation to Form, or of a Form designation to Principle, requires an ARB ruling. Re-classification does not require EC amendment unless the element has already been added to EC as a designated principle.

---

## Part I — ARB Decision Block

### I.1 — Review Questions for This Framework

The ARB review of this framework must address:

1. Are the definitions in Part B (Principle, Form, Ambiguous) constitutionally adequate and appropriately generic?
2. Are the ten classification criteria in Part C sufficient to produce consistent results across different classifiers?
3. Are the mandatory criteria (C-7, C-9, C-10) correctly identified?
4. Are the escalation rules in Part E appropriately scoped? Are there missing escalation triggers?
5. Is the Trust Root Evaluation in Part F sufficient to surface OQ-38B05-05 candidates?
6. Is the OBS-38B06-05 application in Part H adequate?
7. Are the evidence standards in Part D sufficient to support ARB review of classification exercise outputs?

### I.2 — Framework Outputs

The classification exercise, once this framework is approved, will produce for each ADR element:

| Output | Description |
|--------|-------------|
| Candidate designation | Principle / Form / Ambiguous |
| Confidence level | HIGH / MEDIUM / LOW / AMBIGUOUS |
| Constitutional source | Specific reference (gap, TM, ADR invariant, EC provision) |
| Criteria results | Answer to each applicable criterion (C.1 through C.10) |
| Trust root evaluation | TR-01 through TR-05 results |
| Governing Principle | (For Form designations only) |
| CIC escalation trigger | (For Ambiguous designations only) |
| OBS-38B06-05 acknowledgment | Required for all outputs |
| Unresolved questions | Any questions that arose from applying the framework |

The classification exercise does NOT produce:
- Final classifications (those require a separate ARB ruling)
- EC amendment proposals (those follow the classification ruling)
- Technical architecture decisions
- Trust root structural separation conclusions (OQ-38B05-05 remains a mandatory primary requirement separate from the classification exercise)

### I.3 — Possible ARB Determinations

**Framework Approved:** The classification exercise is authorized to begin using this framework. No element may be classified without applying all applicable criteria, evidence standards, and trust root evaluations defined here.

**Framework Approved with Revisions:** Specific revisions are required before the exercise begins. The classification exercise must use the revised framework.

**Framework Rejected:** The framework contains constitutional inadequacies that would produce unreliable classification outputs. A new framework must be produced.

---

## Appendix — Classification Candidate Register

The following ADR elements are identified as candidates for the classification exercise, per the 38C-02 Evaluation (Part G) and 38C-03 ruling. This register does not constitute pre-classification. The register lists what must be evaluated; the exercise determines how.

| Candidate | Source | Preliminary 38C-02 Observation | Mandatory CIC Criteria |
|-----------|--------|-------------------------------|------------------------|
| ADR3-INV-01 (evidence strata independence) | ADR-3 | Ambiguous: distinction requirement vs. three-stratum realisation | — |
| ADR5-INV-01 (R-8 terminality) | ADR-5 | Appears separable: terminality principle vs. taxonomy form | C-10 (certification finality proximity) |
| ADR6-INV-01 (CO-5 void rule) | ADR-6 | Appears separable: validity principle vs. CO structure form | C-10 (CO-5 certification finality proximity) |
| ADR7-INV-01 (succession pre-designation) | ADR-7 | Appears separable: pre-designation requirement vs. chain specification | C-9 (mandatory — governance survivability) |
| ADR7-INV-02 (anti-capture invariant) | ADR-7 | **Conditional default: PRINCIPLE-LEVEL** (38C-03 ruling); Ambiguous in 38C-02 | C-6 (mandatory — anti-capture), EscRule-02 if Ambiguous |
| 38B01-INV-01 (CIC interprets; CAB adjudicates) | 38B-01 | Appears separable: function separation principle vs. routing form | EscRule-01 if Ambiguous (affects Legitimacy Root) |
| 38B04-INV-01 (no self-appointment) | 38B-04 | Appears separable: independence requirement vs. specific constraints | — |
| 38B05-INV-01 (three amendment tiers) | 38B-05 | **Genuinely ambiguous:** three-tier structure may itself be the principle | EscRule-01 (Legitimacy Root impact) |
| ADR-2 per-function independence forms | ADR-2 | Independence existence vs. form — primary C-7 candidate | C-7 (mandatory — independence existence vs. form) |
| Trust Root Structural Separation | OQ-38B05-05 | **Mandatory candidate per 38C-03 ruling** | EscRule-04 (mandatory CIC; OQ-38B05-05 is mandatory primary requirement) |

**Note on ADR7-INV-02 conditional default:** The 38C-03 ruling designated ADR7-INV-02 as Principle-level by default for TM-39 mitigation reasons. The classification exercise must evaluate this designation using C.6 (anti-capture test) as the primary criterion. The conditional default may be overridden only if C.6 is answered NO with constitutional grounding and EscRule-02 does not apply.

---

*Round 38C-04 — Principle/Form Classification Framework — SUBMITTED FOR ARB REVIEW*
*Research Program: NRNA DDD Trustworthiness*
*Date: 2026-06-18*
*Authority: 38C03-CON-01 (Classification Exercise Authorization)*
*Purpose: Define HOW classification is performed — not perform it*
*38C01-INV-01: All outputs are hypotheses until ARB acceptance*
*38C03-INV-01: No existing ADR invariant loses protection pending classification*
*OQ-38A05-02 PROTECTED: C.10 is a mandatory blocking criterion for any element touching finality/validity*
*OQ-38B05-05 MANDATORY PRIMARY REQUIREMENT: Trust Root Structural Separation must appear as a candidate in the classification exercise*
*Next: ARB review of this framework → approval → classification exercise begins*
