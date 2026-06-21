# Round 38C-04 — ARB Review: Principle/Form Classification Framework

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 38C-04 ARB Review
**Status:** SUBMITTED FOR ARB DECISION
**Purpose:** Review the Principle/Form Classification Framework. Do NOT perform classifications. Do NOT classify any ADR element. Review the framework itself.
**Date:** 2026-06-19

**Input:** Round38C-04_Principle_Form_Classification_Framework.md (SUBMITTED FOR ARB REVIEW)

**Governing invariant:** 38C01-INV-01 — all outputs remain hypotheses until ARB acceptance.
**Governing invariant:** 38C03-INV-01 — no existing ADR invariant loses protection pending classification.

**No classifications performed here. Review only.**

---

## Part A — Review Scope

This review covers seven areas:

| # | Area |
|---|------|
| A-1 | Definitions (Principle, Form, Ambiguous) |
| A-2 | Classification criteria (C.1 through C.10) |
| A-3 | Evidence standards (E-01 through E-09; confidence levels) |
| A-4 | Escalation rules (EscRule-01 through EscRule-05) |
| A-5 | Trust Root Evaluation (TR-01 through TR-05) |
| A-6 | OQ-38B05-05 Integration |
| A-7 | OBS-38B06-05 Application |

And four cross-cutting reviews:

| # | Area |
|---|------|
| B | Framework completeness — missing criteria |
| C | Internal consistency — contradictory outcomes possible? |
| D | Option C alignment |
| E | Protected question preservation (OQ-38A05-02) |

For each finding: **Accept** / **Accept with Observation** / **Revision Required**.

---

## Part B — Framework Completeness Review (A-2)

### B.1 — Missing Criterion: C-11 Misclassification Impact Test

**Finding: RV-38C04-01 — Revision Required**

The framework's ten criteria (C.1 through C.10) collectively determine whether an element is a Principle, Form, or Ambiguous. None of them ask: *what happens if the classification is wrong?*

This is a structural gap. Classification errors are not symmetric. The cost of misclassifying a Principle as a Form is different from the cost of misclassifying a Form as a Principle — and neither is negligible.

**False Form (under-classification — classifying a Principle as a Form):**
Cost: Loss of constitutional protection. The element is not added to EC at any tier. It remains architecturally revocable without constitutional challenge. If the element is related to independence, certification, anti-capture, or trust roots, degradation of that element by future architectural revision has no constitutional visibility. This is the pathway to TM-39 exploitation for independence-related elements.

**False Principle (over-classification — classifying a Form as a Principle):**
Cost: Governance ossification. The element is added to EC at an assigned tier and requires the corresponding amendment process to change. If a purely architectural choice is constitutionalized, correcting a technical error in that choice becomes constitutionally expensive. In the current program phase (early 38C discovery, OBS-38C-01 acknowledged), this is the pathway to premature constitutional locking.

**These costs are asymmetric and element-dependent.** For anti-capture (ADR7-INV-02) and trust root separation (OQ-38B05-05), the cost of False Form is catastrophic — TM-39/F-4 mitigation is lost. For specific CO object labels or succession chain specifics, the cost of False Principle may be manageable ossification. The framework currently has no mechanism to weight these asymmetries.

**Required addition (C-11 — Misclassification Impact Test):**

*If this element is misclassified as a Form when it should be a Principle (False Form), what is the constitutional consequence? If it is misclassified as a Principle when it should be a Form (False Principle), what is the governance consequence?*

Possible outcomes:
- **False Form cost DOMINANT:** The element should be treated conservatively — default to Principle designation under uncertainty (Ambiguous resolves toward Principle)
- **False Principle cost DOMINANT:** The element should be treated liberally — default to Form designation under uncertainty (Ambiguous resolves toward Form)
- **Costs roughly symmetric:** Standard Ambiguous rules apply; CIC adjudicates without directional bias

C-11 is not a classification criterion in the same sense as C.1–C.10. It does not determine the classification. It determines the **resolution direction for Ambiguous designations** when the classification exercise cannot achieve certainty. It is a tiebreaker criterion with asymmetric constitutional significance.

C-11 is especially load-bearing for:
- ADR7-INV-02 (False Form cost = TM-39 mitigation lost; False Principle cost = anti-capture ossification, manageable)
- Trust Root Structural Separation (False Form cost = OQ-38B05-05 resolution blocked; may leave roots unprotected; False Principle cost = trust root protection ossified at wrong tier, correctable)
- 38B05-INV-01 (False Form cost = amendment tier protections lose constitutional anchoring; False Principle cost = tier structure ossified at wrong granularity, moderately expensive to correct)

Without C-11, the framework's Ambiguous designation resolves by CIC adjudication alone, without providing the classification exercise with any provisional constitutional default. This leaves genuinely ambiguous elements — including Trust Root Structural Separation — without a constitutional safety posture during the period between classification exercise and CIC ruling.

---

### B.2 — Missing Criterion: Principle Anchor Test

**Finding: RV-38C04-02 — Revision Required (minor)**

The framework requires (E-05) that every Form designation identifies its governing Principle. However, the framework does not require that the governing Principle is itself classified as a Principle-level element.

This creates a potential chain failure: Form X identifies Principle Y as its governing Principle. Principle Y is classified as a Form. Principle Y identifies Principle Z. Principle Z is classified as Ambiguous. The chain has no constitutional anchor — no element in the chain is actually designated Principle-level.

This is not hypothetical. ADR3-INV-01 (evidence strata independence) is classified as Ambiguous in the 38C-02 Evaluation. If a Form ADR element designates ADR3-INV-01 as its governing Principle, and ADR3-INV-01 remains Ambiguous through the classification exercise, the Form element's constitutional grounding is suspended.

**Required addition:** A Principle Anchor Requirement. Every Form designation must trace its governing Principle chain to at least one element that is either:
(a) Classified as Principle-level in the same exercise, or
(b) Explicitly named in existing EC provisions (Gap 3-7 closures, Tier 3 Protected Core catalog from 38B-05), or
(c) Designated as a pending Principle candidate, with an explicit note that the Form's constitutional standing is provisional until the anchor Principle is resolved

Without this requirement, the classification exercise can produce a complete set of Form designations with no Principle anchors — constitutionally equivalent to Option A (all ADR elements architectural only), despite the Option C ruling.

---

### B.3 — Tier Assignment Criteria Missing

**Finding: RV-38C04-03 — Revision Required**

The framework handles Principle designation (is this element constitutional?) but does not provide criteria for Principle tier assignment (which EC tier — Tier 1, 2, or 3 — should this Principle be assigned?).

The 38C-03 ruling (38C03-CON-02) states: "The extension must be enacted via the amendment process appropriate to the tier assigned to each principle in the classification exercise." This confirms that tier assignment is a classification exercise output. But the framework provides no criteria for how tier assignment is made.

The 38B-05 Protected Core Catalog criteria apply to Tier 3 designation. The framework's Appendix mentions "proposed EC tier assignment (Tier 1, 2, or 3) with rationale, grounded in the 38B-05 Protected Core Catalog criteria" as a required output for Principle candidates — but the framework body never defines what those criteria are or how to apply them to new Principle candidates that are not already in the Protected Core.

**Required addition:** A Tier Assignment section specifying:
- Tier 3 criteria (from 38B-05): foundational rights, constitutional authority existence, amendment process itself, trust root anchors — elements whose removal or weakening would undermine the entire constitutional structure
- Tier 2 criteria: governance rules, authority appointment processes, operational procedures with constitutional significance — elements that require deliberation but not near-unanimity to change
- Tier 1 criteria: operational parameters with constitutional relevance — elements that require constitutional process but not supermajority
- Tier assignment conflict: when criteria point to different tiers, escalate to ARB

Tier assignment determines the constitutional cost of future amendment. Assigning a Principle to Tier 2 vs. Tier 3 is a constitutionally significant decision that cannot be left to classifier discretion without defined criteria.

---

### B.4 — Decision Rule 50/50 Split

**Finding: RV-38C04-04 — Revision Required (minor clarification)**

The Classification Decision Rule states: "An element should be designated Principle if the majority of criteria that yield a determinate answer indicate constitutional character."

An even split (equal number of criteria indicating Principle vs. Form) is not covered. The rule implies majority, not plurality — a 50/50 split leaves the decision undefined. Given that both C.9 and C.10 are mandatory and binding, an even split can occur in practice.

**Required clarification:** If the determinate criteria split exactly equally, the element defaults to Ambiguous. This is consistent with the spirit of the rule (Ambiguous is the designation when classification cannot be determined) and prevents classifier discretion from resolving constitutional uncertainty.

---

## Part C — Internal Consistency Review (A-1)

### C.1 — Definition Consistency: Principle vs. Form

**Finding: Accept**

The definitions are mutually exclusive and collectively exhaustive:
- Principle: what must be true regardless of realization
- Form: how the requirement is currently realized
- Ambiguous: genuine uncertainty between the two

One potential consistency tension: the definition of Ambiguous states "the element simultaneously exhibits both principle-like and form-like properties." An element that is simultaneously a Principle and a Form violates the mutual exclusivity of the two definitions.

This is resolved by the deeper reading: an Ambiguous element is not simultaneously both — it is an element whose classification *as* Principle or Form cannot be determined with constitutional confidence. The "simultaneously exhibits" language is describing the epistemic state of the classifier, not the ontological state of the element. The definitions are consistent; the Ambiguous language should be read as epistemically rather than ontologically ambiguous.

No revision required. The framework is internally consistent on this point.

### C.2 — Sequencing Dependency

**Finding: Accept with Observation**

The framework requires Forms to identify their governing Principles (E-05), and Principles to exist for Forms to be constitutionally grounded (RV-38C04-02 above). This creates a sequencing dependency: Principles must be evaluated before Forms that depend on them.

The framework does not specify evaluation order. If Forms are evaluated before their governing Principles, the Form designations cannot satisfy E-05 (Governing Principle Identification) completely — the Principle candidate may not yet have been evaluated.

**Observation (carry to classification exercise):** The classification exercise must process elements in dependency order: candidate Principles first, then Forms that cite those Principles. The framework need not specify this — it is an execution constraint for the classification exercise document to specify. No revision required to the framework itself.

### C.3 — Escalation Rule Coverage

**Finding: Accept with Observation**

EscRule-01 through EscRule-05 cover: trust root Ambiguous elements, anti-capture Ambiguous elements, OQ-38A05-02 interference, OQ-38B05-05 candidates, and boundary exploitation risk. These are the five highest-stakes escalation scenarios.

One potential gap: the framework has no escalation rule for Principle elements with contested tier assignment. If a Principle is agreed to be constitutional but classifiers disagree on whether it belongs at Tier 2 or Tier 3, the framework has no defined escalation path for that tier dispute.

**Observation (carry to Revision R3 — Tier Assignment Criteria):** The Tier Assignment criteria requested in RV-38C04-03 should include an escalation rule for tier disputes. Tier 3 vs. Tier 2 assignment disputes are constitutionally significant (near-unanimity vs. qualified majority) and should route to ARB with CIC advisory input when contested. This escalation rule belongs in the tier assignment criteria addition rather than as a standalone EscRule-06.

---

## Part D — Option C Alignment Review

### D.1 — Framework-to-Ruling Alignment

**Finding: Accept**

The 38C-03 ruling established:
- EC holds constitutional principles at assigned tiers (Tier 1, 2, or 3)
- ADRs hold implementation forms at the architectural level
- CIC adjudicates principle/form boundary disputes

The framework is consistent with all three:
- EC holds principles: Framework produces Principle candidates for EC designation (38C03-CON-02)
- ADRs hold forms: Framework produces Form candidates that remain in ADRs
- CIC adjudicates boundaries: Framework's escalation rules (EscRule-01 through EscRule-05) route to CIC appropriately

### D.2 — Governance Sequence Alignment

**Finding: Accept**

The 38C-03 ruling specified four binding conditions (38C03-CON-01 through CON-04). The framework satisfies each:
- CON-01 (classification exercise authorized): Framework IS the methodology for that exercise; it is correctly positioned as prerequisite to the exercise, not part of it
- CON-02 (EC extension authorized): Framework's output (Principle candidates with tier assignments) feeds directly into CON-02 upon ARB approval
- CON-03 (CIC jurisdiction update): Framework EscRule-01/02/04 route to CIC appropriately; CON-03 update is a consequence of the classification ruling, not the framework
- CON-04 (interim protection rule): Framework Part H and 38C03-INV-01 preserve this throughout

### D.3 — ADR7-INV-02 Conditional Default

**Finding: Accept**

The 38C-03 ruling designated ADR7-INV-02 as conditionally Principle-level by default; C.6 is the primary criterion; override requires constitutional grounding. The framework's Appendix correctly reflects this conditional default and specifies C.6 as primary. The override condition ("classification exercise explicitly rules otherwise with constitutional grounding") is correctly stated.

C-11 (RV-38C04-01) will affect how Ambiguous resolution works for ADR7-INV-02: if C.6 is answered YES (anti-capture affects constitutional level) and the element is nonetheless contested, C-11 should confirm that False Form cost is dominant for ADR7-INV-02 (TM-39 mitigation lost), resolving Ambiguity toward Principle. This reinforces the conditional default rather than contradicting it.

---

## Part E — Protected Question Review

### E.1 — OQ-38A05-02 Protection

**Finding: Accept**

C-10 (OQ-38A05-02 Non-Interference Test) is correctly designated as mandatory. Its trigger ("Does classifying this element implicitly resolve OQ-38A05-02?") is hard — classification is blocked if YES, regardless of all other criteria.

The specific elements most likely to trigger C-10 are: ADR5-INV-01 (R-8 terminality — challenge finality proximity), ADR6-INV-01 (CO-5 void rule — certification finality proximity), and any element related to post-finality constitutional review. The framework correctly notes these in the Appendix.

**Observation (carry to classification exercise):** The classification exercise should open with a C-10 proximity checklist — a list of program-established OQ-38A05-02 indicators (drawn from 38A-05 and 38B-05) that classifiers must check before applying any other criteria to an element. This is an execution-level specification for the exercise, not a framework gap. No revision required.

### E.2 — OQ-38A05-02 in Escalation Rules

**Finding: Accept**

EscRule-03 explicitly blocks classification for OQ-38A05-02 interference and routes directly to CIC. This is consistent with the PROTECTED designation established in 38A-06 and maintained through 38B and 38C. Protection is structurally enforced.

---

## Part F — Trust Root Review (A-5)

### F.1 — TR-01 through TR-05 Adequacy

**Finding: Accept with Observation**

TR-01 (Legitimacy Root), TR-02 (Authenticity Root), and TR-03 (Temporal Root) are independently comprehensive. TR-04 (simultaneous impact) and TR-05 (trust root separation) correctly extend the analysis to interactions.

**Observation:** TR-05 is reflexive when applied to the mandatory Trust Root Structural Separation candidate (OQ-38B05-05). A property that is itself about trust root separation cannot straightforwardly evaluate its own impact on trust root separation via TR-05. The framework acknowledges this in Part G.3 ("a property that is itself about trust root separation has a reflexive character"). EscRule-04 correctly routes OQ-38B05-05 to CIC to resolve this reflexivity. No additional revision required — the escalation path handles the methodological issue.

### F.2 — Trust Root Coverage Completeness

**Finding: Accept**

The three trust roots (Legitimacy/Authenticity/Temporal) established in 38A-05 and confirmed through 38B-03 and 38B-06 are fully covered. TR-04 and TR-05 capture interaction effects. The trust root evaluation is comprehensive within the established constitutional model.

---

## Part G — Classification Governance Review

### G.1 — Governance Sequence Preserved

**Finding: Accept**

The framework correctly maintains:

```
Framework (this document)
→ Classification Exercise (applies framework)
→ ARB Review of Exercise
→ Classification Ruling (formal ARB designation)
→ EC Extension (38C03-CON-02)
```

Part I (ARB Decision Block) specifies that the exercise produces candidates, not finals. The re-classification mechanism in Part H correctly positions post-exercise corrections as ARB-level decisions. The governance sequence is intact.

### G.2 — Classification Exercise Performer

**Finding: Accept with Observation**

The framework does not specify who conducts the classification exercise or what type of document it is. This is implicit (same document/review pattern as all 38C deliverables) but unstated.

**Observation (carry to classification exercise):** The 38C-05 classification exercise document should open by explicitly stating that it is a program document produced under this framework and submitted for ARB review — making the governance pattern explicit rather than implicit. No revision required to this framework.

---

## Part H — Review Findings

| Code | Area | Finding | Verdict |
|------|------|---------|---------|
| RV-38C04-01 | C.11 Misclassification Impact Test | Critical gap — Ambiguous resolution has no directional default under asymmetric constitutional cost | **Revision Required** |
| RV-38C04-02 | Principle Anchor Test | Form chains may have no Principle anchor; constitutional grounding can be suspended | **Revision Required** |
| RV-38C04-03 | Tier Assignment Criteria | Principle designation and tier assignment are two separate decisions; framework handles first only | **Revision Required** |
| RV-38C04-04 | Decision Rule 50/50 | Even split defaults undefined; should default to Ambiguous | **Revision Required (minor)** |
| RV-38C04-05 | Definition Ambiguous language | "Simultaneously exhibits" is epistemic, not ontological; definitions are consistent | **Accept** |
| RV-38C04-06 | Sequencing dependency | Principle-before-Form ordering is an execution constraint, not a framework gap | **Accept with Observation** |
| RV-38C04-07 | Tier dispute escalation | Tier 2 vs. Tier 3 disputes should route to ARB/CIC; belongs in tier assignment criteria (RV-38C04-03) | **Accept with Observation** |
| RV-38C04-08 | TR-05 reflexivity | EscRule-04 handles methodological reflexivity for OQ-38B05-05; no additional action needed | **Accept** |
| RV-38C04-09 | OQ-38A05-02 protection | C-10 is mandatory and hard; EscRule-03 enforces; protection is structurally sound | **Accept** |
| RV-38C04-10 | Option C alignment | Framework is consistent with 38C-03 ruling on all four binding conditions | **Accept** |
| RV-38C04-11 | Governance sequence | Framework → Exercise → Review → Ruling preserved throughout | **Accept** |
| RV-38C04-12 | OBS-38B06-05 application | Classification completeness ≠ correctness; re-classification mechanism defined | **Accept** |

**Summary:** Four Revision Required findings (RV-38C04-01 through RV-38C04-04). All four are structural additions or clarifications rather than corrections of existing content. No existing content needs to be removed or contradicted.

---

## Part I — ARB Determination

### I.1 — Determination

```
38C-04 Classification Framework

OUTCOME B: FRAMEWORK APPROVED WITH REVISIONS REQUIRED
(R1 through R4)

The framework's core architecture is sound.
Definitions, criteria, escalation rules, trust root
evaluation, and OBS-38B06-05 application are all
accepted without substantive change.

Four revisions are required before the
classification exercise may begin.

Revisions may be applied within the framework
document rather than requiring a new document.
No new ARB review is required after revisions —
the classification exercise may proceed once
R1 through R4 are confirmed applied.
```

### I.2 — Required Revisions

**R1 — Add C-11: Misclassification Impact Test (RV-38C04-01)**

Add a criterion C-11 to Part C. C-11 asks:
*If this element is misclassified as a Form when it should be a Principle (False Form), what is the constitutional consequence? If misclassified as a Principle when it should be a Form (False Principle), what is the governance consequence?*

C-11 is an Ambiguous resolution tiebreaker:
- If False Form cost is dominant: Ambiguous defaults toward Principle
- If False Principle cost is dominant: Ambiguous defaults toward Form
- If costs are roughly symmetric: Standard Ambiguous rules apply; CIC adjudicates without directional bias

C-11 must be applied to all Ambiguous designations before CIC escalation. It provides constitutional safety posture during the period between classification exercise and CIC ruling.

**R2 — Add Principle Anchor Requirement (RV-38C04-02)**

Add to Part D (Evidence Standards) or Part E (Escalation Rules):

Every Form designation must trace its governing Principle chain to at least one element that is:
(a) Classified as Principle-level in the same exercise, OR
(b) Explicitly grounded in existing EC provisions (Gap closures, Protected Core catalog from 38B-05), OR
(c) Designated as a pending Principle candidate, with an explicit note that the Form's constitutional standing is provisional until the anchor Principle is resolved

Form designations without a Principle anchor are constitutionally ungrounded. They must be escalated to Ambiguous and then to CIC if no anchor can be identified.

**R3 — Add Tier Assignment Criteria (RV-38C04-03)**

Add a new Part (between current Part E and Part F, or as Part E.2) defining tier assignment criteria for Principle-designated elements:

- **Tier 3 criteria:** Elements that are foundational to the constitutional structure itself — removal would undermine the basis on which all constitutional authority rests. Apply the 38B-05 Protected Core reasoning: would the absence of this Principle enable Constitutional Self-Destruction (OBS-38A06-SD1)? Does it protect challenge rights, trust root existence, constitutional interpretation authority, or the amendment process itself?
- **Tier 2 criteria:** Elements that define governance rules requiring deliberation but not near-unanimity — qualified majority and deliberation period sufficient. Authority appointment processes, governance procedures, and operational rules with constitutional significance.
- **Tier 1 criteria:** Operational parameters with constitutional relevance — simple majority sufficient. Parameters within a governance structure that require constitutional anchoring but not supermajority protection.
- **Tier dispute escalation:** When classifiers cannot agree on tier assignment, or when Tier 2 vs. Tier 3 is contested, the dispute routes to ARB with CIC advisory input before the classification ruling is issued.

**R4 — Clarify 50/50 Decision Rule Default (RV-38C04-04)**

Add to Part C (Classification Decision Rule):

*If the determinate criteria split exactly equally (equal number of criteria indicating Principle and Form), the element defaults to Ambiguous. Classifier discretion may not resolve a 50/50 constitutional classification split.*

### I.3 — What Changes After Revisions

After R1 through R4 are applied to Round38C-04_Principle_Form_Classification_Framework.md:

- The classification exercise (38C-05) may begin
- All ten existing candidates in the Appendix are subject to the revised framework
- C-11 applies to every element designated Ambiguous in the exercise, before CIC escalation
- The Principle Anchor chain must be traced for every Form designation
- Every Principle designation must carry a proposed tier assignment

### I.4 — Governing Sequence After This Review

```
38C-04 Framework (APPROVED WITH REVISIONS R1–R4)
        ↓
Apply R1–R4 to framework document
        ↓
38C-05 Classification Exercise
  (applies revised framework to 10 Appendix candidates)
        ↓
ARB Review of Classification Exercise
        ↓
38C-06 Classification Ruling
  (formal ARB designation of each element)
        ↓
OQ-38B05-05 Evaluation
  (mandatory primary requirement; mechanism now available)
        ↓
EC Extension (38C03-CON-02)
        ↓
CIC Jurisdiction Update (38C03-CON-03)
        ↓
Strategic DDD Discovery proper
```

### I.5 — Protected Questions Confirmed

| Question | Status |
|----------|--------|
| OQ-38A05-02 (Finality vs. Validity) | PROTECTED — C-10 structurally enforced; EscRule-03 routes to CIC |
| OQ-38B05-05 (Trust Root Structural Separation) | MANDATORY PRIMARY REQUIREMENT — mandatory candidate in classification exercise; EscRule-04 routes to CIC |
| AA-01 (MA Legitimacy) | PRE-CONSTITUTIONAL — acknowledged; outside classification scope |

---

*Round 38C-04 ARB Review — SUBMITTED FOR ARB DECISION*
*Research Program: NRNA DDD Trustworthiness*
*Date: 2026-06-19*
*Input: Round38C-04_Principle_Form_Classification_Framework.md*
*Determination: OUTCOME B — Framework Approved With Revisions R1–R4*
*38C01-INV-01: All outputs are hypotheses until ARB acceptance*
*OQ-38A05-02 PROTECTED throughout*
*OQ-38B05-05 MANDATORY PRIMARY REQUIREMENT — unchanged*
*Next: Apply R1–R4 to framework → 38C-05 Classification Exercise*
