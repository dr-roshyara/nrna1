# Round 38C-05 — ARB Review of Classification Exercise

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 38C-05 ARB Review
**Status:** ARB DETERMINATION ISSUED
**Purpose:** Review the submitted Classification Exercise. Do NOT perform the Classification Ruling. Do NOT modify classifications. Do NOT resolve OQ-38B05-05.
**Date:** 2026-06-19

**Input:** Round38C-05_Principle_Form_Classification_Exercise.md (SUBMITTED FOR ARB REVIEW 2026-06-19)

**Governing discipline:**
- This is a review of a review-layer document. No classifications issued here.
- No rulings on OQ-38B05-05 or OQ-38A05-02.
- No tier assignments.
- No new Principles created.
- Review only.

---

## Part A — Review Criteria and Coverage

The ARB evaluates 38C-05 against the following ten questions:

| # | Criterion | Evaluated In |
|---|-----------|-------------|
| 1 | Classification Target Register completeness | Part B |
| 2 | Separability findings constitutional validity | Part C |
| 3 | Principle Anchor Requirement (R2) satisfaction | Part D |
| 4 | Whether any candidate was classified beyond exercise authority | Part E |
| 5 | Whether tier candidacies improperly became tier assignments | Part F |
| 6 | Whether CIC escalation requests are constitutional vs. architecture-governance | Part G |
| 7 | OQ-38A05-02 protection maintenance | Part H |
| 8 | OBS-38B06-05 respect | Part I |
| 9 | Option C consistent application | Part J |
| 10 | Whether any Principle lacks a governing constitutional source | Part K |

---

## Part B — Classification Target Register Completeness

### B.1 Assessment

The 38C-04 Appendix authorized 10 candidates. The Classification Target Register (38C-05 Part A) covers all 10. The register also provides a documented extended candidate set (trust root governance bodies, specific tier thresholds, governance authority elements) in Part A.3, clearly marked as deferred to 38C-05b.

The ARB evaluates whether the deferred items were appropriately deferred, and whether the primary 10-candidate scope is complete for the authorized purpose.

### B.2 Findings

**B-F-01: Primary scope is complete. ACCEPTED.**

The 10 primary candidates match the 38C-04 Appendix exactly. No authorized candidate was omitted.

**B-F-02: Deferred candidates are documented but not individually enumerated. OBSERVATION.**

Part A.3 categorizes the extended candidates but does not list them as individual items with individual deferral rationale. The effect is that "all governance authority elements" is a class, not a list. This creates a completeness ambiguity: a future 38C-05b exercise cannot confirm it has addressed all deferred items without reconstructing the list.

**B-F-03: The scope boundary is maintained. ACCEPTED.**

The exercise explicitly prohibits itself from evaluating extended candidates. No extended candidate was pulled into the primary evaluation.

**B-F-04: Register structure supports completeness assessment. ACCEPTED WITH OBSERVATION.**

The distinction between primary candidates (evaluated), extended candidates (documented, deferred), and out-of-scope items (architectural, DDD design, OQ-38A05-02) is correctly maintained throughout.

### B.3 Required Action

**R1 (Minor):** Add a line-item enumeration of the extended candidates in Part A.3 so that 38C-05b has a defined starting inventory, not a category description. This does not affect 38C-05's classification findings.

---

## Part C — Separability Findings Constitutional Validity

### C.1 Assessment

38C-05 produces separability findings for 6 of 10 candidates (CD-01, CD-02, CD-03, CD-04, CD-06, CD-07). Each separability finding produces a named Principle-component and a named Form-component. The ARB evaluates whether the separability cut is constitutionally valid — meaning whether the Principle-component is genuinely irreducible and whether alternative architectural realizations of the Form-component could satisfy the Principle.

### C.2 Findings

**C-F-01: CD-01 separability (Non-Substitution / Three-Stratum Model) is constitutionally valid. ACCEPTED.**

The non-substitution rule — that no evidence for one stratum may substitute for another stratum — is irreducible. No alternative formulation of this rule exists that is less restrictive while achieving the same constitutional purpose. The three-stratum model (Completeness/Presence/Authenticity) as the specific realization of this rule is substitutable: a four-dimensional or differently-named model could satisfy the same non-substitution principle. The cut is valid.

**C-F-02: CD-02 separability (Challenge Terminality / R-8 Terminal Remedy) is constitutionally valid. ACCEPTED.**

Challenge terminality — the requirement that the challenge process must have a constitutional endpoint within an election cycle — is irreducible. Without it, TM-42 Complete Deadlock is unconditional. R-8 (Rerun) as the specific terminal remedy is substitutable: a different terminal remedy (R-7 Null Result, for example) could satisfy terminality. The cut is valid. OQ-38A05-02 proximity is correctly documented.

**C-F-03: CD-03 separability (CO-5 Derivation / CO-N Enumeration) is constitutionally valid. ACCEPTED.**

The derivation requirement — that CO-5 is void unless CO-2, CO-3, and CO-4 are independently satisfied — is constitutionally irreducible. Without it, CertificationAuthority can issue CO-5 without any of the three evidence dimensions being independently satisfied. The specific CO-N taxonomy (CO-1 through CO-5 with specific scope and evaluation requirements) is substitutable. A different taxonomy could realize the same derivation principle. The cut is valid. OQ-38A05-02 proximity is correctly documented with appropriate explicit language.

**C-F-04: CD-04 separability (Succession Pre-Designation / Specific Chain Design) is constitutionally valid. ACCEPTED.**

Succession pre-designation — the requirement that EC pre-designates successors for critical functions before vacancies arise — is constitutionally irreducible. Without it, TM-43→TM-42 Complete Deadlock is the consequence. The specific successor chain (which body succeeds which in which order) is substitutable. Different chain designs could satisfy the same pre-designation principle. The GovernanceAuthority prohibition (no self-succession) correctly remains in the Principle component, since it is not a form implementation decision — it is a constitutional constraint on any form implementation.

**C-F-05: CD-06 separability (Interpretation/Adjudication Separation / CIC/CAB Assignment) is constitutionally valid but requires observation. ACCEPTED WITH OBSERVATION.**

The separation between constitutional interpretation authority and constitutional adjudication authority is constitutionally irreducible. Any governance model where a single body both interprets the constitution and adjudicates claims made under it is self-referential. The principle is irreducible. However, the Form component (CIC/CAB assignment) requires careful treatment: CIC and CAB have been specified through the entire 38B cycle and are now load-bearing for all subsequent constitutional architecture. The "tight coupling" flag in 38C-05 is correct and appropriate. The ARB notes that this tight coupling may cross the Option C boundary from Form into constitutional principle in a way that requires CIC boundary determination, not merely ARB assessment. (See Part G.3 for the CIC escalation finding.)

**C-F-06: CD-07 separability (Appointment Independence / Specific Assignments) is constitutionally valid. ACCEPTED.**

Appointment independence — no self-appointment, no circular appointment, constitutional independence of appointer — is constitutionally irreducible. The specific appointment chain (MA appoints CriteriaAuthority; CriteriaAuthority appoints EnrollmentAuthority) is substitutable. Alternative constitutionally-independent appointment structures could satisfy the same independence principle. The cut is valid.

### C.3 Architectural Observation (Carry-Forward)

The six separability findings share a common pattern:

```
EXISTENCE of X is constitutionally required.
SPECIFIC FORM of X is architecturally chosen.
```

This pattern is the constitutional architecture of Option C operating at the constitutional discovery level. Each invariant in the ADR catalog carries both a constitutional EXISTENCE requirement (the principle) and an architectural FORM requirement (the implementation choice). The ARB registers this pattern as a carry-forward discovery for 38C-06: the classification exercise has revealed that nearly all ADR invariants are constitutional-plus-architectural hybrids, not purely constitutional or purely architectural. This finding validates Option C's theoretical foundation.

---

## Part D — Principle Anchor Requirement (R2) Satisfaction

### D.1 Assessment

R2 requires that every Form designation identifies a governing Principle that is either: (a) classified as Principle-level in the same exercise, (b) grounded in existing EC provisions, or (c) designated as a pending Principle candidate with explicit provisionality note.

### D.2 Findings

**D-F-01: CD-01-F through CD-07-F anchors are satisfied. ACCEPTED.**

The six Form components from separable candidates each trace to their Principle-component classified in the same exercise. The Principle Anchor Requirement is satisfied for all six.

**D-F-02: CD-09 anchor is PROVISIONAL — correctly flagged, but implication requires clarification. ACCEPTED WITH OBSERVATION.**

CD-09 (ADR-2 independence forms) traces to CF-05-19 + AC-04-07 + ADR7-INV-02.

- ADR7-INV-02 = CD-05, classified PRINCIPLE in this exercise. ANCHOR SATISFIED for this component.
- CF-05-19 = confirmed discovery finding, not yet an EC provision. ANCHOR NOT YET SATISFIED for this component.
- AC-04-07 = architectural constraints from 36E-01, not EC provisions. ANCHOR NOT YET SATISFIED for this component.

The document marks CD-09 as PROVISIONAL, which is correct. However, it does not state the constitutional consequence of CF-05-19 remaining undesignated after 38C-06: if the governing Principle is never EC-designated, CD-09 retains ADR-level protection only — it loses its Option C provisional protection entirely. This consequence should be explicit.

Additionally, the document states CD-09's Option C standing is "provisional pending EC designation of governing Principle" but does not identify who has authority to designate CF-05-19 as an EC provision, by what mechanism, and by what deadline.

### D.3 Required Action

**R2 (Moderate):** Add to CD-09's Principle Anchor Analysis: (a) the explicit consequence of CF-05-19 non-designation under Option C; (b) identification of what architectural step would establish the CF-05-19 Principle as an EC provision. The Form classification finding is not changed — only the implication statement.

---

## Part E — Classification Beyond Exercise Authority

### E.1 Assessment

38C-05's governing constraint: "This document performs classifications. It does NOT issue final rulings." The ARB evaluates whether any candidate received a final classification or determination beyond provisional hypothesis status.

### E.2 Findings

**E-F-01: CD-01 through CD-07 are correctly designated as provisional candidates. ACCEPTED.**

High confidence designations (e.g., CD-05: HIGH) refer to the confidence level of the evidence, not the finality of the classification. The document consistently labels these as provisional hypotheses. 38C01-INV-01 is acknowledged in the ARB Submission Statement.

**E-F-02: CD-05 conditional default confirmation is within exercise authority. ACCEPTED.**

The 38C-03 ruling established a conditional default designation for ADR7-INV-02 (PRINCIPLE-LEVEL). 38C-05 applies the framework (C.6 primary criterion) to confirm the conditional default. This is applying an existing ruling, not issuing a new one. The confirmation is within exercise authority.

**E-F-03: CD-08 and CD-10 Ambiguous designations are within exercise authority. ACCEPTED.**

Designating a candidate as Ambiguous and providing C-11 directional guidance is the correct outcome of applying the framework to genuinely ambiguous candidates. The C-11 guidance is explicitly provisional: "pending CIC ruling" and "defaults toward Principle" — not "is classified as Principle." This is within exercise authority.

**E-F-04: Trust Root Impact assessments do not constitute trust root rulings. ACCEPTED.**

The TR-01 through TR-05 assessments for each candidate evaluate impact, not determine trust root architecture. No trust root structural decision was made. OQ-38B05-05 remains unresolved.

**Finding: No candidate was classified beyond exercise authority.**

---

## Part F — Tier Candidacies vs. Tier Assignments

### F.1 Assessment

R3 (Tier Assignment Criteria) applies to Principle designations. The exercise is authorized to produce tier CANDIDACIES — identification of which tier a Principle plausibly belongs to, with evidence. It is NOT authorized to produce tier ASSIGNMENTS, which belong to 38C-06.

### F.2 Findings

**F-F-01: Output C (Candidate Principle Inventory) correctly labels tiers as candidates. ACCEPTED.**

The "Tier Candidate" column heading and all tier evaluations use "candidate," "primary candidate," "strong case," and "refer to CIC if contested" language. No tier is stated as assigned.

**F-F-02: CD-05 tier evaluation language is strong but not assignment-level. ACCEPTED WITH OBSERVATION.**

CD-05's tier evaluation states: "Tier 3 (Challenge Rights protection — Tier 3 Protected Core item 2). Strong case. CIC assessment recommended given stakes."

This is the strongest tier candidacy statement in the document. It correctly does not use "assigned" or "determined." However, the combination of "Tier 3" as the declared candidate tier and "Strong case" may create the impression that the tier is effectively determined and CIC review is merely procedural. The ARB notes that this is a well-reasoned candidacy position, not an overreach, but the language should be slightly tempered to make clear that Tier 3 candidacy means "the exercise's best-evidenced position" rather than "near-final determination."

**F-F-03: CD-08 and CD-10 tier evaluations are appropriately conditional. ACCEPTED.**

Both candidates carry "provisional pending CIC ruling" language for their tier positions. No tier candidacy overreach.

**F-F-04: Output F (Tier Assignment Candidates) is correctly titled and scoped. ACCEPTED.**

Output F is called "Tier Assignment Candidates" — meaning it produces input for the tier assignment process, not tier assignments themselves. The distinction is maintained throughout.

### F.3 Required Action

None mandatory. Optional: temper CD-05 Tier 3 language slightly to prevent reading as near-final.

---

## Part G — CIC Escalation Authority

### G.1 The Governing Boundary

This is the ARB's most important evaluation criterion for 38C-05.

Under the constitutional architecture:
- CIC holds **constitutional interpretation authority**: CIC interprets what the constitution means, requires, and permits
- CAB holds **adjudication authority**: CAB adjudicates claims brought under constitutional interpretations
- ARB holds **architectural governance authority**: ARB evaluates whether specifications and exercises conform to the governing framework, and rules on contested findings
- The discovery team (38C) holds **discovery authority**: produces hypotheses and candidates; does not determine

The key boundary: CIC can answer "what does the constitution require?" CIC CANNOT answer "does this architecture satisfy the requirement?" The second question is an architecture-governance question belonging to ARB and the discovery process.

A second boundary: CIC can interpret constitutional provisions that exist. CIC CANNOT create constitutional provisions that do not yet exist. If the constitution is silent, CIC interprets silence — but the remedy for silence is constitutional design (EC amendment), not CIC ruling.

### G.2 CD-08 (Three Amendment Tiers) — CIC Escalation Review

The document routes CD-08 to CIC with three questions:

| # | Question | CIC Authority? |
|---|----------|---------------|
| 1 | Is the three-tier structure itself constitutionally required, or one form of differential protection? | **YES** — constitutional interpretation question. The constitution either requires this structure or does not. CIC interprets. ✓ |
| 2 | What amendment procedure applies when reclassifying the tier structure itself? | **CONDITIONAL** — if EC specifies this procedure, CIC interprets. If EC is silent, no EC provision exists for CIC to interpret. Silence cannot be resolved by CIC ruling; it must be resolved by EC design. ⚠️ |
| 3 | Does the three-tier structure require Tier 3 protection? | **YES** — this is a constitutional protection-level question. The scope of Tier 3's Protected Core is a constitutional interpretation question. CIC authority applies. ✓ |

**G-F-01: CD-08 Question 2 is architecture-design dependent, not purely CIC-interpretable. FINDING REQUIRES REVISION.**

If the constitution is silent on what procedure applies when reclassifying the tier structure itself, CIC cannot rule on it. The question must be restructured: CIC should be asked "Does 38B05-INV-01 (or any provision) specify the procedure for tier structure reclassification?" If yes, CIC interprets. If no, the gap should be flagged as a constitutional design gap for EC, not forwarded as a CIC question requiring an answer.

### G.3 CD-10 (Trust Root Structural Separation) — CIC Escalation Review

The document routes CD-10 to CIC with four questions:

| # | Question | CIC Authority? |
|---|----------|---------------|
| 1 | Is structural separation constitutionally required? | **YES** — this is the core OQ-38B05-05 constitutional interpretation question. ✓ |
| 2 | If constitutionally required: at what EC tier? | **YES** — tier assignment of a constitutional requirement is a constitutional protection-level question within CIC authority. ✓ |
| 3 | Is trust root separation a single constitutional provision or an emergent property? | **YES** — constitutional structure question; CIC interprets constitutional architecture. ✓ |
| 4 | Does the current architecture's three-root differentiation satisfy a constitutional separation requirement? | **NO — ARCHITECTURE-GOVERNANCE QUESTION** — This question asks whether an architectural design (the three-root differentiation described in OBS-38B06-02) satisfies a constitutional requirement. This is NOT a constitutional interpretation question. It is an assessment of architectural conformity. CIC interprets what "structural separation" means constitutionally; ARB/38D assesses whether the current architecture achieves it. |

**G-F-02: CD-10 Question 4 is an architecture-governance question. Must be removed from CIC escalation and rerouted. FINDING REQUIRES REVISION.**

CD-10 CIC Question 4 should be removed from the CIC escalation list and reformulated as:
- An ARB question: "Having determined what structural separation constitutionally requires (via CIC ruling), does the 38B specification architecture (OBS-38B06-02) satisfy that requirement?"
- This is a post-38C-06 question, not a pre-38C-06 CIC question.

### G.4 CD-06-F (CIC/CAB Institutional Assignment) — Missed CIC Escalation

38C-05 identifies CD-06-F's tight coupling as an "ARB assessment" item rather than a CIC boundary determination candidate. This is a governance boundary error.

Under Option C, CIC adjudicates principle/form boundary disputes. The tight coupling between CD-06-F (CIC/CAB institutional assignment) and CD-06-P (Interpretation/Adjudication Separation Principle) is exactly a principle/form boundary dispute: the question is whether CIC/CAB institutional assignment has crossed from Form into constitutional principle through the specificity and binding nature of the 38B specification cycle.

This is a question about where the principle/form boundary lies — which is precisely CIC's mandate under Option C (38C03-CON-03: CIC jurisdiction update). Routing it to ARB instead of CIC is incorrect.

**G-F-03: CD-06-F tight coupling should be flagged as a CIC boundary determination candidate under Option C. FINDING REQUIRES REVISION.**

The document should add to the CD-06 escalation check: "CD-06-F tight coupling is a candidate CIC boundary determination question under 38C03-CON-03. ARB may route this to CIC rather than retaining it as an ARB assessment item."

---

## Part H — OQ-38A05-02 Protection

### H.1 Findings

**H-F-01: OQ-38A05-02 was maintained as PROTECTED throughout. ACCEPTED.**

The document provides explicit OQ-38A05-02 protection language at every point of proximity:
- CD-02-P: "challenge process terminality ≠ post-finality constitutional review"
- CD-03-P: "CO-5 Derivation Requirement does not address whether TS-1 can be constitutionally reopened after CO-5 was issued meeting all derivation requirements"
- ARB Submission Statement: "OQ-38A05-02 PROTECTED THROUGHOUT"

The language is not merely boilerplate — it precisely separates the classified element's scope from OQ-38A05-02's scope for both candidates where proximity exists.

**H-F-02: The proximity documentation for CD-02-P and CD-03-P is the strongest OQ-38A05-02 protection in the program so far. ACCEPT WITH COMMENDATION.**

The explicit language "challenge process terminality ≠ constitutional finality of TS-1 validity" and "issuance validity vs. post-finality discovery" demonstrates sophisticated understanding of the distinction. The ARB registers this as a model for future documents where OQ-38A05-02 proximity arises.

---

## Part I — OBS-38B06-05 Respect

### I.1 Findings

**I-F-01: OBS-38B06-05 was respected. ACCEPTED.**

Part N (OBS-38B06-05 Acknowledgment) goes beyond acknowledgment. It:
- Identifies separability findings as the category most subject to future challenge
- Notes that technical realization may surface misclassifications in both directions
- Explicitly states all classifications are provisional candidates
- Does not claim classification completeness implies classification correctness

**I-F-02: Output H (Misclassification Risk Register) operationalizes OBS-38B06-05 constructively. ACCEPT.**

Rather than merely acknowledging that misclassification is possible, the exercise provides an explicit register of the highest-risk misclassifications (FALSE FORM CRITICAL: CD-05, CD-10; FALSE FORM HIGH: CD-01-P, CD-03-P, CD-04-P, CD-08). This register is valuable input for both 38C-06 and future adversarial review.

---

## Part J — Option C Consistent Application

### J.1 Findings

**J-F-01: Principle-to-EC-tier pathway is maintained. ACCEPTED.**

Every Principle designation includes a tier candidacy evaluation. The exercise does not classify Principles without identifying their candidate tier for EC extension purposes (38C03-CON-02).

**J-F-02: Form-to-governing-Principle traceability is maintained. ACCEPTED.**

Every Form designation identifies its governing Principle. The Principle Anchor Requirement (R2) is applied to each Form candidate. See Part D for the CD-09 provisionality finding.

**J-F-03: Ambiguous-to-CIC escalation pathway is maintained. ACCEPTED.**

CD-08 (EscRule-01) and CD-10 (EscRule-04) are correctly escalated. No Ambiguous designation was resolved by classifier discretion. R4 (50/50 defaults to Ambiguous) is respected.

**J-F-04: Option C's implication for CD-09 requires clarification. ACCEPTED WITH OBSERVATION (see Part D.3 R2).**

Under Option C, a Form's constitutional protection level depends on the constitutional status of its governing Principle. CD-09's governing Principle (CF-05-19) is not yet an EC provision. The exercise acknowledges this as PROVISIONAL, but the downstream Option C implication — that CD-09's constitutional protection is exactly as strong as its weakest anchor's constitutional status — should be made explicit.

**J-F-05: CIC boundary determination role is partially under-applied. FINDING REQUIRES REVISION (see Part G.4 R3).**

Option C grants CIC boundary determination authority over principle/form disputes. CD-06-F's tight coupling is such a dispute and should be routed accordingly.

---

## Part K — Principles and Constitutional Sources

### K.1 Findings

**K-F-01: All classified Principles have constitutional source evidence. ACCEPTED.**

The nine Principles produced by 38C-05 each cite constitutional source evidence:
- Non-Substitution: ADR3-INV-01, 36B-CFI-01, AC-02, ADR6-INV-01, TM-47 ✓
- Challenge Terminality: ADR5-INV-01, TM-42 (unconditional FAIL), 38B-05 Protected Core ✓
- CO-5 Derivation: AC-02, ADR3-INV-01, TM-47, F-1 ✓
- Succession Pre-Designation: ADR7-INV-01, TM-43, TM-42 ✓
- Anti-Capture: ADR7-INV-02, TM-39/F-4, 38B-05 Protected Core, F-3 ✓
- Interpretation/Adjudication Separation: 38B01-INV-01, OBS-38B04-01, TM-01 ✓
- Appointment Independence: 38B04-INV-01, OBS-38B04-02, TM-06 ✓
- Three Amendment Tiers (pending): 38B05-INV-01, OBS-38A06-SD1 ✓
- Trust Root Structural Separation (pending): 38A-05, OBS-38B06-02, TM-39/F-4 ✓

**K-F-02: Constitutional sources are currently discovery-level evidence, not enacted EC provisions. OBSERVATION (expected at this stage).**

The constitutional sources for all nine Principles are discovery findings (threat model findings, ARB observations, architectural constraints) rather than enacted EC provisions. This is expected — the classification exercise is identifying what should become EC provisions, not verifying that EC provisions already exist.

OBS-38B06-05 applies: the discovery-level evidence is sufficient for classification candidacy; it does not confirm that the resulting EC provisions will be correctly scoped. The 38C-06 ruling and EC extension process (38C03-CON-02) must assess whether each Principle's EC provision, as drafted, correctly captures the constitutional requirement identified by the evidence.

**K-F-03: The Anti-Capture Invariant (CD-05) has the strongest constitutional source evidence in the program. OBSERVATION.**

CD-05 traces to: TM-39/F-4 (dominant program FAIL-class risk), F-3 (unconditional structural FAIL), 38B-05 Protected Core item 2 (Tier 3 challenge rights), and is the only candidate where the Principle IS the constitutional requirement rather than realizing one. The ARB registers this as the most constitutionally well-evidenced Principle in the exercise. Its Tier 3 candidacy has the strongest evidence basis.

---

## Part L — Governance Boundary Findings

These findings address the CIC/ARB/discovery boundary as a governance discipline matter, independent of the individual candidate reviews above.

**L-F-01: The exercise correctly prevented premature DDD design.**

No bounded contexts, aggregates, events, or architectural designs appear in 38C-05. The governing constraint from the 38C-05 charter is maintained throughout.

**L-F-02: The exercise correctly prevented premature trust root ruling.**

OQ-38B05-05 is not resolved. CD-10 is designated Ambiguous with EscRule-04 mandatory escalation. The C-11 directional guidance ("toward Principle") is explicitly provisional pending CIC ruling.

**L-F-03: The CIC/ARB boundary requires a governance protocol. CARRY-FORWARD DISCOVERY.**

38C-05's CIC escalation questions reveal that the program lacks a formal boundary definition between:
- CIC constitutional interpretation questions ("what does the constitution require?")
- ARB architecture-governance questions ("does this architecture satisfy the requirement?")
- EC design questions ("how should the constitution be written to address this gap?")

CD-10 Question 4 fell into the architecture-governance category but was routed to CIC. CD-08 Question 2 fell into the EC design category but was routed to CIC. This boundary confusion will recur throughout 38C-06 and beyond if not formalized.

**CARRY-FORWARD: Before 38C-06, the ARB should define or confirm a three-way routing protocol:**
1. CIC: What does an existing constitutional provision mean? What constitutional protection level applies to an existing provision?
2. ARB: Does a proposed specification satisfy an established constitutional requirement?
3. EC Design: What constitutional provision should be created to address a constitutional gap?

**L-F-04: The separability pattern is a program-level architectural discovery. CARRY-FORWARD.**

The consistent finding that ADR invariants decompose into Principle-component (existence requirement) + Form-component (specific realization) is the most significant constitutional architectural discovery of 38C-05. This pattern:
- Validates Option C's theoretical premise
- Confirms that the ADR catalog has been functioning as a hybrid constitutional-plus-architectural document throughout the program
- Implies that the EC extension process (38C03-CON-02) must carefully scope each Principle-component's EC provision to capture the existence requirement WITHOUT constitutionalizing the form component

The ARB registers this as an input to the EC extension work, not a constraint on 38C-06.

---

## Part M — Required Revisions Summary

| Code | Finding | Revision | Mandatory? |
|------|---------|---------|-----------|
| R1 | B-F-02: Extended candidates not individually enumerated | Add line-item inventory of extended candidates in Part A.3 | Minor |
| R2 | D-F-02: CD-09 Option C consequence not explicit | Add explicit statement of consequence of CF-05-19 non-designation and the mechanism for EC designation | Moderate |
| R3 | G-F-01: CD-08 CIC Question 2 is EC-design dependent | Restructure CD-08 CIC Q2: CIC asked whether EC specifies the procedure; if silent, flag as constitutional design gap, not CIC question | REQUIRED |
| R4 | G-F-02: CD-10 CIC Question 4 is architecture-governance | Remove CD-10 CIC Q4 from CIC escalation list; reformulate as post-38C-06 ARB question | REQUIRED |
| R5 | G-F-03: CD-06-F tight coupling not routed to CIC | Add CD-06-F as CIC boundary determination candidate under 38C03-CON-03 | REQUIRED |

---

## Part N — Carry-Forward Discoveries

These are not findings requiring revision. They are observations generated by the ARB review process that should inform 38C-06 and subsequent work.

| Code | Discovery | Disposition |
|------|----------|------------|
| CF-38C05-01 | Separability pattern: all ADR invariants decompose into Principle (existence requirement) + Form (specific realization). Option C validated at the constitutional discovery level. | Input to 38C-06 EC extension design. |
| CF-38C05-02 | CIC/ARB/EC-design three-way routing protocol is undefined. CD-10 Q4 and CD-08 Q2 surfaced the gap. | 38C-06 must define or confirm this routing protocol before issuing rulings. |
| CF-38C05-03 | CD-05 Anti-Capture Invariant has the strongest constitutional source evidence and the strongest Tier 3 candidacy. Among all nine Principles, CD-05 most unambiguously warrants Tier 3 protection. | 38C-06 to note this in Classification Ruling. |
| CF-38C05-04 | CD-09 Option C standing depends on CD-05 Principle classification. If CD-05 is downgraded, CD-09's constitutional anchor loses its Principle-level component. These two are constitutionally coupled. | 38C-06 to note this dependency in Ruling. |
| CF-38C05-05 | The OQ-38A05-02 proximity documentation in 38C-05 (CD-02-P / CD-03-P) establishes the clearest boundary articulation for OQ-38A05-02 in the program so far. This language should be preserved and referenced in future documents. | Carry forward as model language. |
| CF-38C05-06 | OBS-38B06-05 has been operationalized in Output H (Misclassification Risk Register). This register should be maintained and updated as classification rulings are issued. | 38C-06 to include updated risk register. |

---

## Part O — Accepted Findings Summary

| Finding | Assessment |
|---------|-----------|
| Primary candidate coverage | ACCEPTED |
| All 6 separability findings constitutionally valid | ACCEPTED |
| CD-02-P, CD-03-P OQ-38A05-02 proximity language | ACCEPTED WITH COMMENDATION |
| No candidate classified beyond exercise authority | ACCEPTED |
| Tier candidacies appropriately labeled | ACCEPTED |
| OQ-38A05-02 protection maintained throughout | ACCEPTED |
| OBS-38B06-05 respected and operationalized | ACCEPTED |
| Option C application consistent (with observations) | ACCEPTED WITH OBSERVATION |
| All Principles have constitutional source evidence | ACCEPTED |
| No premature DDD design | ACCEPTED |
| CD-05 conditional default confirmation within authority | ACCEPTED |
| Output structure (A through I) complete and correctly scoped | ACCEPTED |

---

## Part P — ARB Determination

```
Round 38C-05 — Principle/Form Classification Exercise

OUTCOME B: CLASSIFICATION EXERCISE ACCEPTED WITH REVISIONS REQUIRED (R1 through R5)

The Classification Exercise is accepted as constitutionally disciplined.

The following revisions are required before 38C-06 may issue the
Classification Ruling:

REQUIRED REVISIONS:
  R3 — CD-08 CIC Question 2: restructure to distinguish CIC interpretive
       authority from EC design authority (constitutional gap vs. CIC question)
  R4 — CD-10 CIC Question 4: remove from CIC escalation list; reformulate
       as post-38C-06 ARB question about architectural conformity
  R5 — CD-06-F tight coupling: add as CIC boundary determination candidate
       under 38C03-CON-03

MINOR REVISIONS:
  R1 — Part A.3: add line-item inventory of extended candidates
  R2 — CD-09 Principle Anchor: add explicit Option C consequence statement

These revisions do NOT change any classification finding.
They adjust escalation routing and implication completeness only.

The nine provisional Principles are accepted as constitutionally
evidenced candidates for 38C-06 ruling.

The two Ambiguous designations (CD-08, CD-10) are accepted as
correctly produced with appropriate C-11 directional guidance.

CRITICAL: 38C-06 Classification Ruling may NOT begin until:
  (a) R3, R4, and R5 are applied to the 38C-05 document
  (b) The three-way routing protocol (CIC/ARB/EC-design) is established
      per CF-38C05-02 (see Part L-F-03)
  (c) OQ-38B05-05 remains NOT RESOLVED entering 38C-06

OQ-38A05-02: PROTECTED. Does not enter 38C-06 in any direction.
OBS-38B06-05: PERMANENT. Applies to 38C-06 Classification Ruling equally.
38C01-INV-01: All outputs remain hypotheses until ARB acceptance.
```

---

*Round 38C-05 — ARB Review — COMPLETE*
*Research Program: NRNA DDD Trustworthiness*
*Date: 2026-06-19*
*Determination: OUTCOME B — Accepted With Revisions R1–R5*
*Next: Apply R1–R5 → Establish CIC/ARB/EC-design routing protocol → 38C-06 Classification Ruling*
*OQ-38B05-05: MANDATORY PRIMARY REQUIREMENT — NOT RESOLVED*
*OQ-38A05-02: PROTECTED THROUGHOUT*
