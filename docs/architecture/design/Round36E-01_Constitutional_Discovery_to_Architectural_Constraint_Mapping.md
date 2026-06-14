# Round 36E-01 — Constitutional Discovery → Architectural Constraint Mapping

**Program:** NRNA DDD Trustworthiness Research Program  
**Series:** 36E — Architecture Impact Assessment  
**Sub-Round:** 36E-01 of 05  
**Status:** SUBMITTED FOR ARB REVIEW  
**Governing Question:** If the discoveries from 36A–36D are correct, what architectural constraints must any acceptable architecture satisfy?  
**Classification Scheme:** Architectural Constraint (AC) / Architectural Risk (AR) / Architectural Consequence (ACQ) / Open Architecture Question (OAQ)

**This round does NOT design architecture.**  
**This round identifies constraints, pressures, and architectural implications only.**

**Predecessor Series:**  
- Round 36A: Verifiability Research (9 sub-documents; 36A-01 through 36A-09)  
- Round 36B: Auditability Research (36B-01 through 36B-05 + Closure)  
- Round 36C: Threat Modeling (36C-01 through 36C-06 + Closure)  
- Round 36D: Trust Distribution (36D-01 through 36D-05)

---

## Section 1 — Scope and Method

### 1.1 What This Round Produces

This round produces four catalogs:

1. **Architecture Constraints Catalog** (AC-xx): What the architecture MUST satisfy
2. **Architectural Risk Catalog** (AR-xx): What happens if a constraint is violated
3. **Architectural Consequence Catalog** (ACQ-xx): What the constraint changes about the design space
4. **Open Architecture Questions Catalog** (OAQ-xx): What architectural questions remain after constraint identification

### 1.2 What This Round Does Not Produce

This round does not produce:
- Bounded contexts
- Aggregates
- Microservices
- Deployment topology
- ADRs
- Pattern selections
- Technology choices
- API designs

**Governing discipline:** Every finding must be expressible as a constraint on architectural choices — not as an architectural choice itself.

**Test for constraint vs. design:** If a finding names a specific architectural element (e.g., "ChallengeContext"), it is design. If a finding constrains the property of an architectural element without naming the element (e.g., "challenge reception must be architecturally separate from challenged authority"), it is a constraint.

### 1.3 Governing Observations Active Throughout

**OBS-36D-02-1 (GOVERNING):** Authority Distribution ≠ Bounded Context Distribution. Authority is a constitutional property; bounded context is a design partition. Constraints derived from authority requirements cannot automatically be mapped to context boundaries.

**OBS-36D-03-1 (GOVERNING):** IR-H — Audit function must be independent of the audited subject. This is more demanding than IR-A (independent of operator only).

**OBS-36D-01-2 (GOVERNING):** Authorization of a governance transition ≠ Execution of a governance transition.

---

## Section 2 — Focus Area 1: CF-05-08 — Behavioral Integrity ≠ Constitutional Legitimacy

**Discovery:** CF-05-08 (CANDIDATE — MAJOR): Behavioral integrity addresses the conduct of an authority holder. Constitutional legitimacy addresses the character of the authority relationship. These are categorically distinct. Behavioral claims cannot resolve structural constitutional gaps.

### 2.1 Architectural Assumptions That Become Invalid

If CF-05-08 is correct, the following architectural assumptions are invalid:

**Invalid Assumption 1:** "A well-behaved, well-tested system authority function is constitutionally trustworthy."
- Testing produces behavioral guarantees (BI-1: correctness, BI-2: completeness).
- Tests cannot establish L-1 (legitimacy source), L-3 (challenge), L-4 (revocation), or L-5 (succession).
- A system with 100% test coverage and no bugs may still be constitutionally untrustworthy if its authority relationships are ungrounded.

**Invalid Assumption 2:** "The same architectural element can hold primary authority AND verify its own operation."
- Self-verification is a behavioral integrity claim: "I checked myself and found no problems."
- If BI is categorically insufficient for constitutional legitimacy, self-verification cannot produce constitutional assurance.
- An architectural element that governs its own audit is the structural analog of SELF-REF in L-4.

**Invalid Assumption 3:** "Comprehensive logging and audit trails produce constitutional legitimacy."
- Audit trails are BI-4 (transparency) tools — they make behavior observable.
- Observability is a precondition for challenge (from 36D-05, CF-05-02), but it does not constitute a challenge mechanism.
- Logging cannot substitute for L-3 (challenge mechanism) or L-1 (legitimacy source).

**Invalid Assumption 4:** "Trust is an emergent property of correct system behavior."
- This assumption conflates behavioral trust with constitutional legitimacy.
- Constitutional legitimacy is a property of authority relationships, not of behavioral outcomes.
- An architecture built on this assumption will produce a system that behaves correctly but cannot establish constitutional standing for its authority functions.

### 2.2 Architectural Constraints from CF-05-08

**AC-01:** Any acceptable architecture must distinguish between behavioral quality mechanisms (testing, logging, monitoring) and constitutional legitimacy structures (L-1 through L-5). These serve different functions and cannot substitute for each other in the architectural model.

**AC-02:** No authority function may be architecturally self-verifying for constitutional purposes. The element that holds authority and the element that validates the constitutional standing of that authority must be architecturally distinguishable.

**AC-03:** Constitutional legitimacy structures (L-1 through L-5) must be architecturally represented as first-class elements. They cannot be treated as implicit properties of well-behaved code or emergent consequences of comprehensive testing.

### 2.3 Architectural Risks from CF-05-08

**AR-01:** If AC-01 is violated — the architecture conflates behavioral quality with constitutional legitimacy — the system will produce behavioral guarantees that cannot be elevated to constitutional guarantees. Trust claims become circular. When challenged, the system's response is "we tested it thoroughly" — a behavioral claim that has no constitutional weight.

**AR-02:** If AC-02 is violated — authority functions are self-verifying — the architecture embeds the SELF-REF constitutional defect directly into the design. The system cannot be constitutionally audited because the auditor is the audited.

**AR-03:** If AC-03 is violated — constitutional legitimacy structures are not represented — the architecture becomes constitutionally opaque. The system may execute correctly but have no architectural pathway by which its constitutional legitimacy can be examined or challenged.

### 2.4 Architectural Consequences of CF-05-08

**ACQ-01:** The design space must include architectural elements specifically responsible for constitutional legitimacy structures, not only for functional execution. This may increase the number of distinct architectural concerns — functions that were previously treated as one concern (e.g., "enrollment system") must be decomposed into at least: enrollment execution, enrollment authority grounding, and enrollment challenge pathway.

**ACQ-02:** Test suites and monitoring infrastructure cannot serve as constitutional legitimacy evidence. The architecture must include a separate category of constitutional assurance distinct from operational quality assurance.

### 2.5 Open Architecture Questions from CF-05-08

**OAQ-01:** How are constitutional legitimacy structures (L-1 through L-5) represented in the DDD model? Are they domain concepts (value objects, domain services, aggregate invariants)? This question belongs to 36E-03 (DDD Impact Assessment), not this round.

**OAQ-02:** Can behavioral quality mechanisms (testing, monitoring) contribute evidence toward constitutional legitimacy characterization in any indirect way? The answer may be "yes, as supporting evidence but not as primary grounding" — but this requires further analysis.

---

## Section 3 — Focus Area 2: CF-05-19 — Independent Authority Relationship Per Function

**Discovery:** CF-05-19 (CANDIDATE — INDEPENDENT AUTHORITY RELATIONSHIP): Constitutional legitimacy appears to require at least one constitutionally independent authority relationship per authority function. The minimum structural realization remains unresolved.

### 3.1 What This Constraint Does and Does Not Say

**What CF-05-19 says:**
- Each D43 authority function requires at least one independent authority relationship
- "Independent" means: not controlled by, not dependent on, not revocable by the authority being grounded

**What CF-05-19 does NOT say:**
- That independence requires a separate bounded context
- That independence requires a separate service
- That independence requires a separate organizational entity
- That "at least one" means exactly one
- What the minimum structural realization looks like

This distinction is governed by OBS-36D-02-1: authority is not a context boundary. The independence requirement is a constitutional property; its architectural realization form is not yet determined.

### 3.2 Architectural Constraints from CF-05-19

**AC-04:** The architectural model must represent authority relationships as first-class architectural concerns, distinct from the execution of the functions they authorize. Authority-over-X is not the same architectural concern as executing-X.

**AC-05:** For each D43 authority function (enrollment, criteria, audit, governance authorization, certification), the architectural model must include at least one architectural element that is constitutionally independent of the primary authority holder for that function. The form of this element is TBD.

**AC-06:** Independence cannot be achieved by naming two components differently within the same authority boundary. Independence has architectural weight — it must be realized structurally in some form, though the specific form is not determined by this constraint.

**AC-07:** Authority relationships are architectural elements requiring specification: who holds authority, from what source, subject to challenge by whom, revocable by whom, with succession defined how. These five properties (L-1 through L-5) apply to each authority relationship in the architectural model.

### 3.3 Architectural Risks from CF-05-19

**AR-04:** If AC-04 is violated — authority relationships are implicit rather than first-class — constitutional authority structures are invisible in the architecture. Invisible structures cannot be governed, audited, or challenged. The architecture embeds constitutional ungroundedness as an invisible property.

**AR-05:** If AC-05 is violated — no independent element exists for a D43 function — the function is constitutionally concentrated. From CF-05-08, behavioral integrity cannot substitute. The function becomes a constitutional single point of failure.

**AR-06:** If AC-06 is violated — independence is achieved only nominally — the architecture creates "independence theater": two names, one authority boundary. The constitutional independent authority relationship does not exist structurally, only symbolically.

### 3.4 Architectural Consequences of CF-05-19

**ACQ-03:** The authority model (who governs what) must be designed as an independent layer of the architecture — separate from, but mapping onto, the functional domain model. The two layers interact but are not the same thing.

**ACQ-04:** DDD modeling alone is insufficient for constitutional trustworthiness. The standard DDD toolkit (aggregates, bounded contexts, domain events) must be augmented with an authority modeling layer. Whether that layer is part of DDD or above it is a 36E-03 question.

### 3.5 Open Architecture Questions from CF-05-19

**OAQ-03:** What is the minimum structural realization of an independent authority relationship? Is it a separate bounded context? A separate organizational role? A separate deployment unit? A different constitutional mandate held by an existing element? This cannot be answered at constraint level — it belongs to 36E-04 (Architecture Options).

**OAQ-04:** Can one independent entity hold L-3/L-4 authority over multiple D43 functions simultaneously, or must each function have a distinct independent authority? From 36D-05 CF-05-19, the minimum is "at least one per function" — whether a single entity can satisfy this for multiple functions is not determined.

---

## Section 4 — Focus Area 3: L-3/L-4 — Challengeability and Revocability

**Discoveries:**
- 36D-CF-05-16: L-3 (Challenge Mechanism) requires structural independence of the challenge function from the challenged authority
- 36D-CF-05-17: L-4 (Revocation Mechanism) requires structural independence of the revocation mechanism from the revoked authority
- 36D-CF-05-04: SELF-REF in L-4 (criteria authority revocation) is a structural defect, not behavioral

### 4.1 Architectural Implications of L-3

**Challenge requires three architectural preconditions:**

1. **Addressability of authority decisions:** A challenge cannot be made against a decision that is not architecturally accessible. Authority decisions must be represented as addressable domain facts — not purely internal state — so that they can be the subject of challenge. A decision that exists only inside an aggregate's private state is architecturally unchallengeble.

2. **A receiving entity independent of the challenged authority:** Challenge reception cannot be routed through the challenged authority. The receiving element must be architecturally independent — it cannot be a method on the same aggregate, a service in the same bounded context, or any element that the challenged authority controls.

3. **A determination mechanism:** Challenge must produce an outcome (upheld, dismissed, modified). The determination mechanism must itself be constitutionally grounded — it cannot be delegated back to the challenged authority.

**AC-08:** Authority decisions must be represented as addressable architectural facts (domain events or equivalent). They cannot be purely encapsulated internal state.

**AC-09:** Challenge reception and adjudication must be architecturally separate from the challenged authority — not merely a different class in the same boundary, but a structurally independent element with its own constitutional grounding.

**AC-10:** The challenge determination mechanism must produce a typed outcome that can be acted upon by the challenged authority — but the determination itself must originate outside the challenged authority's boundary.

### 4.2 Architectural Implications of L-4

**Revocation requires three architectural preconditions:**

1. **Authority lifecycle representation:** An authority that can be revoked has a lifecycle: it is granted, it operates, and it can be terminated. The architectural model must represent this lifecycle — an authority is not a static property but a temporal, revocable relationship.

2. **External revocation origination:** A revocation signal must arrive at the authority from outside the authority's own boundary. The architecture cannot allow an authority to process its own revocation through its own mechanisms (that is the SELF-REF defect).

3. **Succession at revocation:** When revocation occurs, authority must be transferred or suspended — it cannot simply vanish. The succession mechanism (L-5) is architecturally entangled with the revocation mechanism (L-4): revocation without succession creates an authority vacuum.

**AC-11:** Authority lifecycle (creation, active operation, suspension, revocation, succession) must be an explicitly modeled architectural concern, not an implicit temporal property.

**AC-12:** Revocation events must originate outside the authority boundary being revoked. The revoked authority cannot be the origin of its own revocation event.

**AC-13:** Revocation and succession must be architecturally connected: a revocation pathway must include a succession pathway. Architectures that support revocation without succession embed constitutional authority vacuums.

### 4.3 Architectural Risks from L-3/L-4

**AR-07:** If AC-08 is violated — authority decisions are internal state — challenge is architecturally impossible. The system may claim challenge capability but have no structural support for it. Challenges become administrative appeals to the same authority, not constitutional challenges.

**AR-08:** If AC-09 is violated — challenge is handled by the challenged authority — every challenge is self-adjudicated. This is the architectural instantiation of TC3-NCQ-04 (who governs the governors?).

**AR-09:** If AC-12 is violated — revocation is internally originated — the authority is constitutionally irremovable regardless of what the documentation says. Documented revocability without structural revocability is constitutional theater.

**AR-10:** If AC-13 is violated — revocation without succession — authority vacuums arise when authorities are revoked. Constitutional functions temporarily or permanently lose their authority holder, producing ungoverned gaps in the authority structure.

### 4.4 Architectural Consequences of L-3/L-4

**ACQ-05:** Every authority relationship in the architectural model must have an explicit lifecycle state machine. An authority without a lifecycle model cannot be revoked, challenged, or succeeded — and is therefore constitutionally irremovable.

**ACQ-06:** Domain events must be sufficient in scope to represent authority decisions as facts. Architectures that encapsulate authority decisions as internal aggregate state without corresponding domain events cannot support L-3 (challenge) architecturally.

**ACQ-07:** The design space must include architectural elements that can receive external signals (challenge outcomes, revocation decisions) and apply them to authority-holding elements. The directionality is: external decision → internal state change, not internal state → self-determined outcome.

---

## Section 5 — Focus Area 4: IR-H — Audit Authority Independent of Audited Subject

**Discovery:** IR-H (OBS-36D-03-1): The audit function must be independent of the audited subject — not only independent of the election operator (IR-A), but of the entire election system that is being audited.

**Supporting discovery:** Gap A-3 — the expected evidence set (what should be audited) is undefined; and from 36D-05, CF-05-05: an audit function that defines its own expected evidence set is constitutionally incomplete regardless of how accurately it observes within that self-defined scope.

### 5.1 The IR-H Architectural Challenge

IR-H is architecturally more demanding than IR-A because:
- IR-A requires the audit function to be outside the operator's control. This is a single independence relationship.
- IR-H requires the audit function to be outside the control of the audited subject — and the audited subject is the entire election system.

This creates a tension: the audit function needs access to election system data to audit it, but cannot be inside the election system for independence purposes. The architectural consequence is that audit must be able to observe from outside — which places constraints on how the election system exposes auditable data.

### 5.2 Architectural Constraints from IR-H

**AC-14:** Audit scope definition — the specification of what must be present in a complete audit (the expected evidence set) — must originate outside the election system's architectural boundary. The election system cannot define what constitutes a complete audit of itself.

**AC-15:** The election system must expose its auditable data through a pathway that is accessible to architecturally external entities. This is an outward-facing interface requirement on the election system — but the receiving end of that interface is outside the election system.

**AC-16:** The element that defines audit scope and the element that executes election functions cannot share the same architectural authority boundary. Applying OBS-36D-02-1: they may exist in contexts that overlap in other ways, but their audit-related authority relationship must be constitutionally independent.

**AC-17:** Gap A-3 (undefined expected evidence set) has an architectural correlate: any architectural model that treats audit completeness as internally determined by the audit function is architecturally admitting Gap A-3. The architecture must include a mechanism for externally defining the expected evidence set.

### 5.3 Architectural Risks from IR-H

**AR-11:** If AC-14 is violated — audit scope is internally defined — Gap A-3 is architecturally permanent. The system will always define its own audit completeness, which means it can never be constitutionally audited for completeness. The audit function observes what it has decided to observe, not what it should observe.

**AR-12:** If AC-15 is violated — auditable data is not externally accessible — IR-H cannot be satisfied regardless of how independent the auditor is. An independent auditor without access to the evidence base cannot audit.

**AR-13:** If AC-16 is violated — audit scope authority shares a boundary with election execution authority — the election system defines what audit should find. This is the architectural analog of the election operator controlling the audit: IR-A and IR-H simultaneously violated.

### 5.4 Architectural Consequences of IR-H

**ACQ-08:** The election system's external surface (API or equivalent) must be designed with audit accessibility in mind. Data that must be auditable must be accessible to entities that are constitutionally independent of the system. This is a constraint on the system boundary design.

**ACQ-09:** Audit architecture involves at minimum two distinct concerns: (a) audit data collection, which may be tightly coupled to the election system, and (b) audit scope validation, which must be independent. Any architectural approach that conflates these two concerns will violate IR-H.

---

## Section 6 — Focus Area 5: Authority vs Context (OBS-36D-02-1)

**Governing observation:** OBS-36D-02-1: Authority Distribution ≠ Bounded Context Distribution. Authority is a constitutional property; bounded context is a design partition. One authority can span multiple bounded contexts; multiple authority relationships can exist inside one bounded context; one authority relationship can span several bounded contexts.

### 6.1 Implication Analysis (Not Design)

**Can authority boundaries cross bounded contexts?**

Yes — by implication of OBS-36D-02-1. An authority relationship is a constitutional structure defined by: who holds the authority (L-2), from what source (L-1), challengeable by whom (L-3), revocable by whom (L-4), succeeded by whom (L-5). These five properties do not map to a single bounded context boundary. The enrollment authority's legitimacy source (L-1) may involve a constitutional body that spans multiple contexts; the challenge mechanism (L-3) may require elements from a membership context, an administration context, and a governance context.

**Can multiple authority relationships exist inside one bounded context?**

Yes — by implication. A governance context might be responsible for executing governance transitions (an authority relationship concerning who can authorize transitions) and for logging governance evidence (an authority relationship concerning who can define the expected evidence set). These are two distinct authority relationships that might exist in the same bounded context for functional reasons.

**Can one authority relationship span several contexts?**

Yes — by implication. The certification authority relationship (who certifies, subject to challenge by whom, revocable by whom) may need to interact with the election result context, the public verification context, and the constitutional governance context. The authority relationship is one — its architectural realization may span several contexts.

### 6.2 Architectural Constraints from OBS-36D-02-1

**AC-18:** Bounded context design and authority boundary design are separate architectural activities. A bounded context map cannot be assumed to represent authority relationships. A separate authority map is required.

**AC-19:** Adding an authority to a bounded context does not automatically partition that authority. Splitting a bounded context does not automatically distribute the authority relationships within it. Authority partitioning must be designed separately from context partitioning.

**AC-20:** Authority concentration risk (TC-5, trust concentration) cannot be assessed by examining bounded context boundaries. It must be assessed by examining authority boundaries, which are a distinct architectural artifact.

### 6.3 Architectural Risks from OBS-36D-02-1

**AR-14:** If AC-18 is violated — context map is treated as authority map — authority concentrations become invisible. Two bounded contexts may appear to distribute authority, but if they share a common authority grounding they are constitutionally one concentrated authority. The architecture would fail to detect this because it was not looking at authority.

**AR-15:** If AC-20 is violated — trust concentration risk is assessed by context boundaries — the architecture may assess TC-5 risk as low (many small contexts) while constitutional trust is actually concentrated (all contexts report to one authority). The architectural assessment would be false.

### 6.4 Architectural Consequences of OBS-36D-02-1

**ACQ-10:** The architectural model requires two distinct maps: (a) a bounded context map (functional design partition) and (b) an authority map (constitutional authority structure). The relationship between these two maps is itself an architectural concern.

**ACQ-11:** The risk of authority concentration must be assessed at the authority map level, not the context map level. Architectural refactoring that splits contexts without examining authority will not reduce constitutional risk.

---

## Section 7 — D43 Instances: Per-Instance Architectural Constraints

For each of the five discovered D43 instances, the architectural constraints arising from their legitimacy gap profiles (from 36D-04).

### 7.1 D43-Instance-ENROLLMENT

**Legitimacy Profile:** L-1 UNDEFINED, L-2 UNDEFINED (no identified holder), L-3 UNDEFINED, L-4 UNDEFINED, L-5 UNDEFINED — complete legitimacy gap.

**AC-21:** Any architectural model that includes voter enrollment must include architectural representations for: the legitimacy source of enrollment authority (L-1), the identified authority holder (L-2), the challenge pathway (L-3), the revocation pathway (L-4), and the succession mechanism (L-5). An enrollment implementation without these five architectural elements has no constitutional standing.

**AR-16:** Implementing enrollment without AC-21 creates a constitutionally ungrounded enrollment function. Enrollment decisions (who may vote) would have no architectural challenge pathway. The system would make consequential eligibility determinations with no constitutional recourse for affected parties.

**ACQ-12:** Enrollment is architecturally more complex than it appears from a functional perspective. Functionally: "record who may vote." Constitutionally: record who may vote, under constitutionally grounded authority, challengeable by affected parties, revocable by independent authority, with succession defined. The constitutional scope is significantly larger than the functional scope.

### 7.2 D43-Instance-CRITERIA

**Legitimacy Profile:** L-1 CANDIDATE (membership ratification mechanism undefined), L-4 SELF-REF.

**AC-22:** The criteria architectural element must include an external revocation pathway that does not route through the criteria authority itself. A criteria authority that controls its own revocation is architecturally constitutionally irremovable (SELF-REF defect from 36D-05, CF-05-04).

**AC-23:** The criteria architectural element must represent the membership ratification mechanism as a first-class architectural concern — not merely "criteria are set by configuration" but "criteria are set by a constitutionally ratifiable process, whose ratification is architecturally representable."

**AR-17:** If AC-22 is violated — criteria authority controls its own revocation — TC3-NCQ-04 (who governs the governors?) is architecturally embedded. The architecture cannot remove the criteria authority even if its criteria become constitutionally contested.

### 7.3 D43-Instance-AUDIT

**Legitimacy Profile:** L-1 partially addressed (Gap A-3 unresolved), L-3 implicit (depends on Gap A-3 resolution).

**Key:** IR-H applies — audit must be independent of the audited subject (Section 5 above).

**AC-24:** The audit architectural element must include: a scope definition mechanism that is external to the election system (Gap A-3 resolution requirement — AC-14, AC-17); an evidence access pathway from the election system that is accessible to external entities (AC-15); an independence structure from the audited subject (IR-H, AC-16).

**AR-18:** Implementing audit as an internal system function that monitors itself violates IR-H, permanently embeds Gap A-3, and produces an audit function that is architecturally constitutionally incomplete regardless of how correctly it observes.

### 7.4 D43-Instance-GOVERNANCE-AUTHORIZATION

**Legitimacy Profile:** L-1 CANDIDATE, L-2 UNDEFINED, L-3 UNDEFINED; OBS-36D-01-2 applies (authorization ≠ execution).

**AC-25:** The governance authorization architectural model must distinguish between: the element that holds authority to authorize phase transitions (constitutionally grounded authorization authority — L-2 must be identified), and the element that executes phase transitions (the governance execution mechanism). These must be architecturally separable even if they are co-located in the same bounded context.

**AC-26:** The governance authorization architectural model must represent the constitutional grounding of the authorization authority (L-1) — it cannot be "whoever runs the system." The identity of the authorization authority holder must be a first-class architectural concern.

**AR-19:** If AC-25 is violated — authorization authority and execution authority are the same element — then the system's authority over its own governance transitions is self-referential. The system authorizes its own phase transitions. This is the architectural analog of TC3-NCQ-04 applied to governance.

**ACQ-13:** Phase transition governance in NRNA is constitutionally more demanding than procedural state machine design. The state machine defines valid transitions. The constitutional governance model defines who can authorize each transition and under what constitutional basis. Both layers are required.

### 7.5 D43-Instance-CERTIFICATION

**Legitimacy Profile:** L-1 CANDIDATE (independence structure partially addressed), L-3 UNDEFINED, L-4 UNDEFINED, L-5 UNDEFINED — most complete legitimacy gap among the five instances.

**Key:** TF-36C-05-01 (from 36C-06): Certifier legitimacy ≠ certification validity. A legitimate certifier can produce constitutionally problematic certifications if L-3/L-4/L-5 are absent.

**AC-27:** The certification architectural element must include an external challenge pathway (L-3). An architecture in which certification decisions are architecturally final and unchallengeble embeds the constitutional defect from CF-05-21 ("correct certification ≠ constitutionally valid certification when L-3 UNDEFINED").

**AC-28:** Certification is architecturally the highest-risk authority function (from 36D-04, CF-04-04: highest terminal risk). The certification architectural element must be designed with explicit awareness that: it has the most complete legitimacy gap, it is the terminal constitutional act, and TC-4 failures (Certification Abuse) amplify all prior threat classes (TF-36C-05-11).

**AR-20:** Implementing certification as a final, unchallengeble architectural act creates a constitutional terminal node with no review. This is the architectural realization of the most complete legitimacy gap in the discovered authority structure. A challenge at this point has no architectural pathway — constitutional contestation of election legitimacy is structurally impossible.

---

## Section 8 — Architectural Constraints Summary Catalog

All architectural constraints from this round.

| ID | Constraint | Source | Risk if Violated |
|----|-----------|--------|-----------------|
| AC-01 | Distinguish behavioral quality mechanisms from constitutional legitimacy structures | CF-05-08 | AR-01: circular trust claims |
| AC-02 | No authority function may be architecturally self-verifying | CF-05-08 | AR-02: SELF-REF embedded in design |
| AC-03 | Constitutional legitimacy structures (L-1 to L-5) must be first-class architectural elements | CF-05-08 | AR-03: constitutionally opaque system |
| AC-04 | Authority relationships must be first-class architectural concerns, distinct from function execution | CF-05-19 | AR-04: invisible constitutional structure |
| AC-05 | Each D43 authority function must have at least one architecturally independent authority element | CF-05-19 | AR-05: constitutional single point of failure |
| AC-06 | Independence cannot be achieved by naming alone within the same authority boundary | CF-05-19 | AR-06: independence theater |
| AC-07 | Authority relationships must carry explicit L-1 to L-5 specifications | CF-05-19 | AR-04, AR-05 |
| AC-08 | Authority decisions must be represented as addressable architectural facts (domain events or equivalent) | L-3 | AR-07: challenge architecturally impossible |
| AC-09 | Challenge reception and adjudication must be architecturally separate from challenged authority | L-3 | AR-08: self-adjudicated challenges |
| AC-10 | Challenge determination must produce a typed outcome originating outside the challenged authority | L-3 | AR-08 |
| AC-11 | Authority lifecycle must be explicitly modeled (creation, operation, suspension, revocation, succession) | L-4 | AR-09, AR-10 |
| AC-12 | Revocation events must originate outside the authority boundary being revoked | L-4 | AR-09: irremovable authority |
| AC-13 | Revocation pathway must include succession pathway | L-4/L-5 | AR-10: authority vacuum on revocation |
| AC-14 | Audit scope definition must originate outside the election system's architectural boundary | IR-H, Gap A-3 | AR-11: Gap A-3 permanent |
| AC-15 | Election system must expose auditable data through an externally accessible pathway | IR-H | AR-12: IR-H cannot be satisfied |
| AC-16 | Audit scope authority cannot share an architectural authority boundary with election execution authority | IR-H | AR-13: IR-A and IR-H simultaneously violated |
| AC-17 | Architecture must include a mechanism for externally defining the expected evidence set | Gap A-3 | AR-11 |
| AC-18 | Bounded context design and authority boundary design are separate architectural activities requiring separate artifacts | OBS-36D-02-1 | AR-14: authority concentrations invisible |
| AC-19 | Authority partitioning must be designed separately from context partitioning | OBS-36D-02-1 | AR-14 |
| AC-20 | Trust concentration risk must be assessed at authority map level, not context map level | OBS-36D-02-1 | AR-15: false concentration assessment |
| AC-21 | Enrollment must include architectural representations for L-1, L-2, L-3, L-4, L-5 | D43-ENROLLMENT | AR-16: ungrounded enrollment |
| AC-22 | Criteria authority must include external revocation pathway not routing through criteria authority | D43-CRITERIA, L-4 SELF-REF | AR-17: irremovable criteria authority |
| AC-23 | Criteria ratification process must be a first-class architectural concern | D43-CRITERIA | AR-17 |
| AC-24 | Audit must include: external scope definition, external access pathway, IR-H independence structure | D43-AUDIT, IR-H | AR-18: constitutionally incomplete audit |
| AC-25 | Governance authorization authority and execution authority must be architecturally distinguishable | D43-GOV-AUTH, OBS-36D-01-2 | AR-19: self-authorized governance |
| AC-26 | Governance authorization authority holder must be identified as first-class architectural concern (L-2) | D43-GOV-AUTH | AR-19 |
| AC-27 | Certification must include external challenge pathway (L-3) | D43-CERTIFICATION | AR-20: unchallengeble final act |
| AC-28 | Certification element must be designed with awareness of complete legitimacy gap and terminal risk | D43-CERTIFICATION | AR-20 |

---

## Section 9 — Architectural Risk Catalog

| ID | Risk | Constraint Violated | Constitutional Consequence |
|----|------|--------------------|-----------------------------|
| AR-01 | Circular trust claims — system defends itself with behavioral evidence when challenged | AC-01 | System cannot establish constitutional standing when contested |
| AR-02 | SELF-REF embedded in architecture | AC-02 | Authority verifies itself; TC3-NCQ-04 instantiated structurally |
| AR-03 | Constitutionally opaque system | AC-03 | No architectural pathway for constitutional examination |
| AR-04 | Invisible constitutional structure | AC-04 | Authority relationships ungovernable, unauditable |
| AR-05 | Constitutional single point of failure | AC-05 | TC-5 trust concentration instantiated per D43 function |
| AR-06 | Independence theater | AC-06 | Constitutional independence claimed but not realized |
| AR-07 | Challenge architecturally impossible | AC-08 | L-3 structurally unimplementable |
| AR-08 | Self-adjudicated challenges | AC-09/10 | TC3-NCQ-04 instantiated in challenge mechanism |
| AR-09 | Irremovable authority | AC-12 | L-4 SELF-REF; constitutional irremovability despite documented revocability |
| AR-10 | Authority vacuum on revocation | AC-13 | Constitutional function loses authority holder; ungoverned gap |
| AR-11 | Gap A-3 permanent | AC-14/17 | Audit completeness architecturally undefinable |
| AR-12 | IR-H unsatisfiable | AC-15 | External auditor cannot access evidence |
| AR-13 | IR-A and IR-H simultaneously violated | AC-16 | Audit scope defined by audited subject |
| AR-14 | Authority concentrations invisible | AC-18/19 | TC-5 risk unassessable from architecture alone |
| AR-15 | False concentration assessment | AC-20 | Architecture appears distributed; authority is concentrated |
| AR-16 | Ungrounded enrollment | AC-21 | Enrollment decisions have no constitutional standing |
| AR-17 | Irremovable criteria authority | AC-22/23 | TC3-NCQ-04 embedded in criteria governance |
| AR-18 | Constitutionally incomplete audit | AC-24 | IR-H violated; Gap A-3 permanent; audit scope self-defined |
| AR-19 | Self-authorized governance | AC-25/26 | System authorizes its own phase transitions; OBS-36D-01-2 violated |
| AR-20 | Unchallengeble certification (terminal) | AC-27/28 | Constitutional contestation of election legitimacy structurally impossible |

---

## Section 10 — Architectural Consequence Catalog

Consequences that arise from satisfying the constraints — changes to the design space.

| ID | Consequence | Arising From | Design Space Effect |
|----|------------|--------------|---------------------|
| ACQ-01 | Architecture must include constitutional legitimacy elements distinct from functional execution | CF-05-08 | Increased architectural scope; functions decompose into execution + authority grounding |
| ACQ-02 | Separate category of constitutional assurance required, distinct from operational QA | CF-05-08 | New architectural concern type; test suites insufficient |
| ACQ-03 | Authority model must be designed as independent layer mapping onto domain model | CF-05-19 | Two-layer architecture minimum: functional + constitutional |
| ACQ-04 | DDD toolkit must be augmented with authority modeling layer | CF-05-19 | Standard DDD alone insufficient for constitutional trustworthiness |
| ACQ-05 | Every authority relationship requires explicit lifecycle state machine | L-4 | Authority lifecycle is a first-class domain concern |
| ACQ-06 | Domain events must be sufficient to represent authority decisions as addressable facts | L-3 | Event model scope must cover constitutional authority decisions |
| ACQ-07 | Architecture must support external-to-internal decision signals on authority relationships | L-3/L-4 | Inbound authority signals are architectural first-class concern |
| ACQ-08 | Election system external surface must support audit accessibility by independent entities | IR-H | Audit interface is architectural, not operational |
| ACQ-09 | Audit = data collection (tightly coupled) + scope validation (independent). Cannot be conflated | IR-H/Gap A-3 | Audit decomposes into two distinct architectural concerns |
| ACQ-10 | Architecture requires two distinct maps: bounded context map + authority map | OBS-36D-02-1 | Two architectural planning artifacts, not one |
| ACQ-11 | Trust concentration risk assessment belongs to authority map, not context map | OBS-36D-02-1 | Standard architectural review insufficient for TC-5 risk |
| ACQ-12 | Enrollment constitutional scope >> enrollment functional scope | D43-ENROLLMENT | Enrollment implementation is significantly more complex than functional requirements suggest |
| ACQ-13 | Governance requires two layers: state machine (valid transitions) + constitutional model (authorized transitions) | D43-GOV-AUTH | Governance implementation decomposes; constitutional layer is new architectural concern |

---

## Section 11 — Open Architecture Questions Catalog

Questions that arise from the constraint mapping but cannot be resolved at this level.

| ID | Question | Arising From | Belongs To |
|----|---------|--------------|------------|
| OAQ-01 | How are L-1 to L-5 represented in the DDD model? (value objects, domain services, aggregate invariants?) | CF-05-08, CF-05-19 | 36E-03 (DDD Impact) |
| OAQ-02 | Can behavioral quality mechanisms contribute supporting evidence toward constitutional legitimacy characterization? | CF-05-08 | 36E-03 |
| OAQ-03 | What is the minimum structural realization of an independent authority relationship? | CF-05-19 | 36E-04 (Architecture Options) |
| OAQ-04 | Can one independent entity hold L-3/L-4 authority over multiple D43 functions simultaneously? | CF-05-19 | 36E-04 |
| OAQ-05 | How do the 28 architectural constraints interact? Do any conflict? Which pairs create the highest constraint pressure? | All | 36E-02 (Constraint Interaction) |
| OAQ-06 | Can audit data collection (tightly coupled) and audit scope validation (independent) share any architectural boundary? | IR-H, ACQ-09 | 36E-03/04 |
| OAQ-07 | How does the authority map relate to the bounded context map? What are the valid crossing patterns? | OBS-36D-02-1, ACQ-10 | 36E-03 |
| OAQ-08 | Which architectural elements in the current NRNA model would require modification to satisfy the 28 constraints? | AC-01 to AC-28 | 36E-03 |
| OAQ-09 | Which constraints from this catalog create the highest implementation risk for enrollment, certification, and audit? | AC-21, AC-24, AC-27/28 | 36E-04/05 |
| OAQ-10 | Do any of the 28 constraints conflict with each other? (e.g., does AC-14 conflict with any existing system boundary?) | Constraint interaction | 36E-02 |
| OAQ-11 | What architectural elements are candidates for the "Track C — Foundational Platform" (stable, unlikely to be affected by constraint resolution)? | All constraints | 36E-05 (Architecture Recommendation) |
| OAQ-12 | Which D43 instances have the highest architectural constraint density? Do they cluster around specific bounded contexts? | AC-21 to AC-28 | 36E-03 |

---

## Section 12 — ARB Decision Block

**[PENDING ARB REVIEW]**

Submitted for ARB assessment on completion of Round 36E-01.

### Open Questions for ARB

**OQ-36E-01-01:** Do the 28 architectural constraints faithfully translate the constitutional discoveries from 36A-36D without introducing architectural design decisions?

**OQ-36E-01-02:** Is the constraint discipline maintained throughout — do any constraints inadvertently name specific architectural solutions?

**OQ-36E-01-03:** Are any major constitutional findings from 36A-36D missing from the constraint mapping? (Candidates: TF-36C-04-03 criteria ambiguity exploitability; AIC-36B-01 completeness mechanism; TC2-DI-01 presence ≠ truth)

**OQ-36E-01-04:** Is the scope of Round 36E-02 (Constraint Interaction Analysis) well-defined given the 28 constraints identified here?

---

*Round 36E-01 — Constitutional Discovery → Architectural Constraint Mapping — Submitted for ARB Review*  
*Research Program: NRNA DDD Trustworthiness*  
*Document: Round36E-01_Constitutional_Discovery_to_Architectural_Constraint_Mapping.md*
