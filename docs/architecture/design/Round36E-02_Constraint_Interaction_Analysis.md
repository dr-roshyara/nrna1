# Round 36E-02 — Constraint Interaction Analysis

**Program:** NRNA DDD Trustworthiness Research Program  
**Series:** 36E — Architecture Impact Assessment  
**Sub-Round:** 36E-02 of 05  
**Status:** APPROVED WITH REQUIRED REVISIONS — APPROVED  
**Governing Question:** How do the 30 architectural constraints interact? Which reinforce each other, which create dependencies, which create tension, and which may conflict?  
**Primary Deliverable:** Constraint Interaction Matrix → Architectural Pressure Map

**This round does NOT design solutions to constraint tensions.**  
**This round identifies interaction patterns only.**

**Predecessor:** Round 36E-01 — 30 Architectural Constraints (AC-01 through AC-30) established

---

## Section 1 — Scope and Method

### 1.1 Interaction Classification

Every constraint pair is classified into one of five types:

| Type | Name | Definition |
|------|------|-----------|
| **A** | Reinforcing | Constraint A and B mutually strengthen each other — satisfying one makes the other easier or more complete to satisfy |
| **B** | Dependency | Constraint A requires Constraint B as a structural prerequisite — B must be satisfied for A to be satisfiable |
| **C** | Neutral | No meaningful architectural interaction — satisfying one neither helps nor hinders the other |
| **D** | Tension | Both constraints are independently valid but satisfying both simultaneously creates architectural pressure — a design decision must balance them |
| **E** | Conflict | The constraints as stated cannot both be fully satisfied simultaneously — accepting one requires partially compromising the other |

**Important discipline:** Identifying Type D (Tension) or Type E (Conflict) is a finding, not a problem to be solved in this round. Solutions belong to 36E-04 (Architecture Options) and Round 37 (ADR Authoring).

**Important qualification for Type E (Conflict):** A constraint-vs-constraint conflict is internal to the discovered constitutional requirements. Some apparent conflicts arise between a constitutional constraint and an external domain requirement (privacy, receipt-freeness, operational security) not yet in the AC catalog. These are flagged separately as External Tensions.

### 1.2 Constraint Clusters

With 30 constraints, systematic pair analysis would produce 435 pairs. The analysis uses a clustered approach: constraints are grouped by constitutional concern; within-cluster interactions are analyzed fully; cross-cluster interactions are analyzed selectively for the highest-impact pairs.

**Eight clusters:**

| Cluster | Name | Constraints |
|---------|------|-------------|
| C1 | Behavioral vs Constitutional | AC-01, AC-02, AC-03 |
| C2 | Independent Authority Relationships | AC-04, AC-05, AC-06, AC-07 |
| C3 | Challengeability (L-3) | AC-08, AC-09, AC-10 |
| C4 | Revocability and Lifecycle (L-4/L-5) | AC-11, AC-12, AC-13 |
| C5 | Audit Independence (IR-H/Gap A-3) | AC-14, AC-15, AC-16, AC-17 |
| C6 | Authority Map vs Context Map | AC-18, AC-19, AC-20 |
| C7 | D43 Per-Instance | AC-21, AC-22, AC-23, AC-24, AC-25, AC-26, AC-27, AC-28 |
| C8 | Evidence Integrity | AC-29, AC-30 |

---

## Section 2 — Within-Cluster Interactions

### 2.1 Cluster C1: Behavioral vs Constitutional (AC-01, AC-02, AC-03)

**AC-01 + AC-02:** Type A (Reinforcing)  
AC-01 (distinguish behavioral quality from constitutional legitimacy) and AC-02 (no self-verification) address the same constitutional boundary from different angles. AC-01 establishes the categorical distinction; AC-02 applies it to self-verification specifically. Satisfying AC-01 creates the framework within which AC-02 becomes meaningful — a system that has not distinguished behavioral quality from constitutional legitimacy cannot even identify whether it is self-verifying in the constitutional sense.

**AC-01 + AC-03:** Type B (Dependency)  
AC-03 (L-1 through L-5 as first-class elements) depends on AC-01. Without the distinction between behavioral quality and constitutional legitimacy (AC-01), there is no conceptual basis for treating legitimacy structures as a distinct first-class concern — they would be collapsed into quality attributes. AC-01 is a prerequisite for AC-03 being architecturally meaningful.

**AC-02 + AC-03:** Type A (Reinforcing)  
Both address the constitutional character of authority structures. AC-02 prohibits self-verification; AC-03 requires constitutional legitimacy elements to be first-class. Together they establish that authority structure is architecturally visible and externally examinable — a system with first-class legitimacy elements (AC-03) that are not self-verified (AC-02) is constitutionally examinable from outside.

**C1 Summary:** C1 is internally coherent. AC-01 grounds AC-03 (Type B dependency); AC-01 and AC-02 reinforce each other; AC-02 and AC-03 reinforce each other. No tension within C1.

---

### 2.2 Cluster C2: Independent Authority Relationships (AC-04, AC-05, AC-06, AC-07)

**AC-04 + AC-05:** Type B (Dependency)  
AC-05 (one independent authority relationship per D43 function) depends on AC-04 (authority relationships as first-class concerns). An independent authority relationship cannot be architectural unless authority relationships are first-class elements. AC-04 is a structural prerequisite for AC-05.

**AC-04 + AC-07:** Type A (Reinforcing)  
AC-04 (authority relationships first-class) and AC-07 (authority relationships carry L-1/L-5 specifications) together produce a complete authority model: relationships are visible AND specified with constitutional detail. Each reinforces the other's completeness.

**AC-05 + AC-06:** Type B (Dependency)  
AC-06 (independence cannot be nominal) is a quality requirement on AC-05 (realize one independent authority relationship). AC-05 could be satisfied nominally (naming two things differently); AC-06 closes that gap. AC-05 creates the requirement; AC-06 specifies the quality standard for satisfying it.

**AC-05 + AC-07:** Type A (Reinforcing)  
AC-05 requires one independent authority relationship per function; AC-07 requires that relationship to be fully specified with L-1 through L-5. Together they close the gap between having an independent relationship (AC-05) and that relationship being constitutionally grounded (AC-07).

**AC-06 + AC-07:** Type A (Reinforcing)  
AC-06 (genuine independence) and AC-07 (L-1/L-5 specifications) together ensure that the independent authority relationship is both structurally real and constitutionally complete. Nominal independence (violating AC-06) would typically manifest as missing L-1 or L-3 specifications — AC-07 makes the deficiency explicit.

**C2 Summary:** C2 has a clear dependency chain (AC-04 → AC-05 → AC-06) with AC-07 reinforcing throughout. The cluster is internally coherent with no internal tensions.

---

### 2.3 Cluster C3: Challengeability (AC-08, AC-09, AC-10)

**AC-08 + AC-09:** Type B (Dependency)  
AC-09 (challenge reception structurally independent) depends on AC-08 (authority decisions externally addressable). A challenge reception mechanism has nothing to receive if authority decisions are not addressable. AC-08 is a structural prerequisite for AC-09.

**AC-09 + AC-10:** Type B (Dependency)  
AC-10 (challenge determination outside challenged authority) depends on AC-09 (challenge reception structurally independent). A determination mechanism presupposes a reception mechanism. AC-09 precedes AC-10 in the challenge lifecycle.

**AC-08 + AC-10:** Type B (Dependency — transitive)  
By transitivity of the above: AC-08 is a prerequisite for AC-10 as well. The entire challenge chain (addressability → independent reception → external determination) is a dependency sequence.

**C3 Summary:** C3 is a linear dependency chain: AC-08 → AC-09 → AC-10. No tensions within C3. However, note that C3 as a whole represents the architectural implementation of L-3 — and L-3's independence requirements create cross-cluster tensions examined in Section 3.

---

### 2.4 Cluster C4: Revocability and Lifecycle (AC-11, AC-12, AC-13)

**AC-11 + AC-12:** Type B (Dependency)  
AC-12 (revocation events from outside) depends on AC-11 (authority lifecycle explicitly modeled). A revocation event has no target state to update if authority lifecycle is not modeled. AC-11 is the structural prerequisite; AC-12 specifies the directional constraint on revocation within that lifecycle.

**AC-12 + AC-13:** Type A (Reinforcing)  
AC-12 (revocation from outside) and AC-13 (revocation pathway includes succession) together prevent two distinct constitutional failures: irremovable authority (if AC-12 fails) and authority vacuum on revocation (if AC-13 fails). Each constraint closes a gap the other leaves open; together they constitute a complete constitutional revocation model.

**AC-11 + AC-13:** Type A (Reinforcing)  
AC-11 (lifecycle model) provides the state machine within which AC-13 (succession at revocation) is executed. A succession mechanism is only meaningful if the lifecycle states (revoked, succeeded) are explicitly represented.

**C4 Summary:** C4 has a dependency (AC-11 → AC-12) and two reinforcing pairs (AC-12 + AC-13; AC-11 + AC-13). No tensions within C4.

---

### 2.5 Cluster C5: Audit Independence (AC-14, AC-15, AC-16, AC-17)

**AC-14 + AC-17:** Type A (Reinforcing — closely related)  
AC-14 (audit scope definition outside election system) and AC-17 (mechanism for externally defining expected evidence set) address the same constitutional requirement from principle (AC-14) and mechanism (AC-17). AC-14 states the constraint; AC-17 states that a mechanism realizing it must exist. They are near-identical constraints — satisfying one is necessary but not sufficient for satisfying the other; satisfying both confirms principle AND mechanism.

**AC-14 + AC-15:** Type A (Reinforcing)  
AC-14 (scope from outside) and AC-15 (election system externally accessible for audit) together establish the two-sided audit architecture: an external party defines what should be audited (AC-14) AND can access the evidence to verify it (AC-15). Each constraint is necessary but insufficient without the other — external scope without external access is definition without verification; external access without external scope definition is observation without constitutional completeness.

**AC-15 + AC-16:** Type A (Reinforcing)  
AC-15 (external audit access) and AC-16 (audit scope authority ≠ election execution authority) together ensure that the external accessor is constitutionally independent — not just technically capable of accessing the system. Access without independence is observer capture; independence without access is inoperative independence.

**AC-14 + AC-16:** Type A (Reinforcing)  
AC-14 (scope from outside) and AC-16 (scope authority ≠ execution authority) reinforce each other: external scope definition and independent scope authority are two aspects of the same constitutional requirement (IR-H applied to audit scope).

**C5 Internal Tension:** AC-15 creates an internal C5 tension worth noting:

AC-15 requires the election system to expose auditable data to external parties. The more comprehensive this exposure must be (to satisfy audit completeness requirements), the broader the external access surface becomes. AC-16 requires that external scope authority cannot share an audit authority boundary with election execution authority — but a broad external access surface increases the number of external parties who can observe the system, some of whom may not satisfy IR-H independence requirements. This is not a conflict within C5 but a **Type D tension** between comprehensiveness (AC-15 + AC-14) and access control (implied by IR-H and AC-16).

**C5 Summary:** Primarily reinforcing within-cluster. One internal tension: scope comprehensiveness vs. access control selectivity.

---

### 2.6 Cluster C6: Authority Map vs Context Map (AC-18, AC-19, AC-20)

**AC-18 + AC-19:** Type A (Reinforcing — near-tautological)  
AC-18 (context design and authority design are separate activities) and AC-19 (authority partitioning separate from context partitioning) state the same principle at different levels of abstraction. AC-18 addresses design process; AC-19 addresses design output. They reinforce each other: the same principle demands separate activities (AC-18) producing separate artifacts (AC-19).

**AC-18 + AC-20:** Type B (Dependency)  
AC-20 (trust concentration assessed at authority map level) depends on AC-18 (authority map exists as separate artifact). Without a separate authority map (created by following AC-18/AC-19), there is no artifact at which trust concentration can be assessed. AC-18 is a structural prerequisite for AC-20.

**AC-19 + AC-20:** Type B (Dependency — same as above)  
AC-20 depends on authority partitioning being designed separately (AC-19) — the separate partition is what is assessed in AC-20.

**C6 Summary:** AC-18 and AC-19 are reinforcing near-equivalents; both are prerequisites for AC-20. No tensions within C6.

---

### 2.7 Cluster C7: D43 Per-Instance (AC-21 through AC-28)

**General principle:** The D43 per-instance constraints (AC-21 through AC-28) are applications of the cross-cutting constraints (C1 through C6) to specific authority functions. They inherit the interaction patterns of their source constraints.

**AC-21 (Enrollment) + AC-22 (Criteria external revocation):** Type C (Neutral)  
Enrollment and criteria are distinct authority functions. Their per-instance constraints address different constitutional gaps and do not meaningfully interact.

**AC-22 + AC-23 (Criteria):** Type A (Reinforcing)  
AC-22 (external revocation for criteria authority) and AC-23 (criteria ratification as first-class) both address the constitutional completeness of the criteria authority. A criteria authority with external revocability (AC-22) and a ratifiable process (AC-23) is more constitutionally complete than one with only one of these properties.

**AC-25 + AC-26 (Governance Authorization):** Type A (Reinforcing)  
AC-25 (authorization authority ≠ execution authority) and AC-26 (authority holder identified as first-class concern) together address the two primary gaps in D43-GOV-AUTH. Structural separation (AC-25) without identification (AC-26) leaves the separated element ungrounded; identification (AC-26) without structural separation (AC-25) violates OBS-36D-01-2.

**AC-27 + AC-28 (Certification):** Type A (Reinforcing)  
AC-27 (external challenge for certification) and AC-28 (design awareness of complete legitimacy gap) together address the constitutional severity of certification: AC-27 provides the minimum L-3 requirement; AC-28 ensures that the complete legitimacy gap is architecturally visible. A certification element satisfying only AC-27 has a challenge pathway but may still leave L-4/L-5 unaddressed.

**AC-24 (Audit D43) interactions:**  
AC-24 is a composite of C5 constraints applied to D43-AUDIT. It therefore inherits C5's internal tension (comprehensiveness vs. access control) and all C5's reinforcing patterns.

**C7 Summary:** D43 constraints are primarily inherited applications of cross-cutting constraints. Within-cluster: AC-22 + AC-23 reinforce; AC-25 + AC-26 reinforce; AC-27 + AC-28 reinforce. No tensions within C7 alone — tensions emerge in cross-cluster analysis.

---

### 2.8 Cluster C8: Evidence Integrity (AC-29, AC-30)

**AC-29 + AC-30:** Type C (Neutral — at first order)  
AC-29 (presence ≠ authenticity) addresses evidence verification. AC-30 (criteria precision) addresses criteria representation. These address different constitutional concerns and do not directly interact at the constraint level.

**However:** A second-order interaction exists. AC-30 (precise criteria) enables AC-29 to be more deterministic: if criteria are ambiguous (violating AC-30), then "authentic evidence of criteria application" is itself ambiguous — the same action could simultaneously be correctly authentic under one interpretation and incorrectly authentic under another. Imprecise criteria (AC-30 violation) makes authenticity verification (AC-29) less meaningful.

**C8 Second-Order Interaction:** AC-30 (criteria precision) is a partial enabler for AC-29 (authenticity verification) — not a dependency, but a supportive relationship that becomes a Type D tension if AC-30 is weakly satisfied: in the presence of ambiguous criteria, evidence authenticity verification becomes constitutionally ambiguous.

---

## Section 3 — Cross-Cluster Interactions

### 3.1 C2 × C3: Independent Authority Relationships + Challengeability

**AC-05 + AC-09:** Type B (Dependency)  
AC-09 (challenge reception structurally independent) is a specific application of AC-05 (one independent authority relationship per D43 function). The challenge reception authority is precisely the independent authority relationship that AC-05 requires for the challenged function. AC-05 provides the generic requirement; AC-09 specifies one of its instantiations.

**AC-07 + AC-08:** Type A (Reinforcing)  
AC-07 (authority relationships carry L-3 specification) and AC-08 (authority decisions externally addressable) reinforce each other: AC-07 requires that L-3 (challenge mechanism) is specified for each authority relationship; AC-08 specifies what that challenge mechanism requires in terms of decision addressability. Together they produce a fully specified and structurally realized challenge pathway.

**Interaction pressure:** C2 × C3 as a cluster interaction is primarily reinforcing but creates an architectural multiplier: for each of the five D43 functions (C7), there must be at least one independent authority relationship (AC-05 from C2) including a structurally independent challenge reception mechanism (AC-09 from C3). This multiplies the number of independent authority elements needed in proportion to the number of D43 functions.

---

### 3.2 C2 × C4: Independent Authority Relationships + Revocability

**AC-05 + AC-12:** Type A (Reinforcing)  
The independent authority relationship required by AC-05 must also hold the revocation authority over the primary authority (as specified by AC-12). AC-05 and AC-12 together specify the same independent entity from two perspectives: AC-05 requires its existence; AC-12 specifies one of its constitutional responsibilities.

**AC-07 + AC-11:** Type A (Reinforcing)  
AC-07 (L-1/L-5 specifications on authority relationships) and AC-11 (authority lifecycle modeled explicitly) reinforce each other: L-4 and L-5 in AC-07 are the specification; AC-11 is the architectural requirement that the lifecycle states those specifications reference are actually modeled.

**Interaction:** C2 × C4 produces the same multiplier as C2 × C3: for each D43 function, the independent authority relationship must also include revocation authority. The same independent entity (or another entity) must hold both L-3 (challenge) and L-4 (revocation) independent authority over the primary authority.

---

### 3.3 C3 × C4: Challengeability + Revocability

**AC-11 + AC-08:** Type A (Reinforcing)  
Authority lifecycle (AC-11) creates the state framework within which authority decisions (AC-08) are made. Lifecycle states (active, suspended, revoked) affect which authority decisions are constitutionally valid. Fully specified lifecycle (AC-11) makes authority decision addressability (AC-08) more constitutionally precise.

**AC-12 + AC-09:** Type A (Reinforcing)  
Both require the same structural property applied to different events: external origination. AC-12 requires revocation to originate externally; AC-09 requires challenge reception to be independent. They share the same independence requirement applied at different lifecycle moments (challenge of a decision vs. revocation of the authority).

**Structural observation from C3 × C4:** Challenge (C3) and revocation (C4) are both external, independent processes applied to authority. This creates an emerging pattern: the architecture must support at least two distinct inbound authority relationship types per D43 function — challenge pathways and revocation pathways. These may or may not share the same receiving architectural element; that question belongs to 36E-04.

---

### 3.4 C5 × C3: Audit Independence + Challengeability

**AC-15 + AC-08:** Type D (Tension)  
AC-15 (election system externally accessible for audit) and AC-08 (authority decisions externally addressable for challenge) both require the election system to expose information externally. However, they may require different access patterns, different access controllers, and different external parties. A single external access surface serving both audit and challenge access may create conflation risk (audit access ≠ challenge access); separate surfaces may increase architectural complexity.

**36E-02-TP-01 (Tension Pattern 1):** External accessibility is required for multiple independent constitutional purposes — audit (C5) and challenge (C3). The same external access surface satisfying both simultaneously may create access control challenges; separate surfaces increase complexity. The resolution form is unresolved and belongs to 36E-04.

---

### 3.5 C5 × C8: Audit Independence + Evidence Integrity

**AC-14 + AC-29:** Type A (Reinforcing)  
AC-14 (audit scope definition from outside) and AC-29 (presence ≠ authenticity) together demand that the external party defining audit scope must specify requirements for BOTH completeness (presence verification) and authenticity verification. A scope definition that specifies only what records must be present is constitutionally incomplete under AC-29.

**AC-17 + AC-29:** Type B (Dependency)  
The mechanism for externally defining the expected evidence set (AC-17) must include specifications for authenticity requirements, not only completeness requirements (AC-29). AC-29 expands the scope of what AC-17's mechanism must be capable of expressing.

**AC-15 + AC-29:** Type D (Tension)  
AC-15 (external audit accessibility) and AC-29 (presence ≠ authenticity) together create an architectural pressure: external audit access sufficient to verify authenticity may require access to the cryptographic or structural properties of records that go beyond simple presence checks. The depth of access required for authenticity verification may be significantly greater than for presence verification, creating a layered access requirement.

**36E-02-TP-02 (Tension Pattern 2):** Audit access depth — the external audit access required by AC-15 may need to be stratified: one access level for completeness verification, a deeper access level for authenticity verification. Whether these can be provided through a single access pathway or require architecturally distinct pathways is unresolved.

---

### 3.6 C6 × C2: Authority Map + Independent Authority Relationships

**AC-18 + AC-04:** Type A (Reinforcing)  
AC-18 (authority map as separate artifact from context map) and AC-04 (authority relationships first-class) are the process (AC-18) and content (AC-04) sides of the same requirement. The authority map is the artifact that contains the first-class authority relationships. They are mutually definitional.

**AC-20 + AC-05:** Type B (Dependency)  
Trust concentration assessment at the authority map level (AC-20) requires knowing what authority relationships exist and whether they are independent (AC-05). AC-05 is a prerequisite input for AC-20's assessment — without independent authority relationships being architecturally realized and visible, AC-20 cannot determine whether concentration exists.

**Structural observation from C6 × C2:** The authority map (C6) is the artifact within which independent authority relationships (C2) are visible. The authority map discipline (C6) and the independent authority requirement (C2) are complementary rather than independent.

---

### 3.7 C1 × All Clusters: Behavioral vs Constitutional (Cross-Cutting)

**AC-02 + AC-09, AC-12 (any external verification):** Type A (Reinforcing — C1 reinforces all independence constraints)  
AC-02 (no self-verification) reinforces every constraint that requires external independent entities (AC-05, AC-09, AC-12, AC-14, AC-16). AC-02 establishes the constitutional principle that self-verification is insufficient; every independence constraint in C2 through C5 is an application of this principle to specific functions.

**AC-03 + AC-07, AC-11, AC-18 (any first-class modeling requirement):** Type A (Reinforcing)  
AC-03 (L-1/L-5 as first-class elements) reinforces every constraint that requires explicit modeling of constitutional structures (AC-07, AC-11, AC-18, AC-26). These are all expressions of the same principle: constitutional structure must be architecturally visible, not implicit.

**Cross-cutting pressure:** C1 is the constitutional principle cluster. Every constraint in C2 through C8 is either an application or a refinement of C1's principles. This creates a coherent constitutional foundation, but also means that if C1 constraints are violated, all downstream constraints (C2–C8) lose their architectural grounding simultaneously.

---

## Section 4 — External Tensions

External tensions arise between discovered constitutional constraints and known domain requirements that are not yet in the AC catalog. These requirements are not yet formalized as constraints — they represent known election system properties that may interact with the discovered constitutional requirements.

### 4.1 External Tension: AC-15 vs. Vote Secrecy

**AC-15:** Election system must be externally accessible for audit purposes.  
**External Requirement:** Ballot secrecy — the content of any individual's vote must not be discernible from any audit access.

**Interaction:** Type D (Tension)  
External audit access sufficient to verify that all votes were correctly tallied might expose which votes were cast for whom. The resolution in E2E-V systems involves cryptographic separability — audit can verify inclusion and correctness without revealing vote content. But this cryptographic approach is not yet an architectural decision (belongs to 38+). At constraint level: AC-15 and ballot secrecy are in architectural tension. The tension has known resolution approaches but those belong in ADRs (Round 37).

**36E-02-ET-01 (External Tension 1):** External audit accessibility (AC-15) and vote secrecy are in tension. Any acceptable architecture must satisfy both simultaneously. The form of resolution is unresolved at this stage.

---

### 4.2 External Tension: AC-08 vs. Receipt-Freeness

**AC-08:** Authority decisions must be challengeable and externally addressable.  
When applied to vote recording: a vote decision must be addressable enough to support a challenge that "my vote was incorrectly recorded."  
**External Requirement:** Receipt-freeness — a voter must not be able to prove to a third party how they voted, which prevents coercion and vote-buying.

**Interaction:** Potential Tension — conflict status unproven  
If vote recording decisions are externally addressable for challenge purposes, a voter may be able to use that addressability as a receipt (proof of how they voted). The history of E2E-V architecture demonstrates serious attempts to satisfy individual verifiability and receipt-freeness simultaneously; the existence of those attempts means the conflict status is not established. Whether a satisfying resolution exists for this system is a later question.

**36E-02-ET-02 (External Tension 2):** Individual challenge addressability (AC-08 applied to vote recording) and receipt-freeness are in potential tension. Conflict status is unproven. Whether a resolution exists — as some E2E-V designs suggest — depends on the specific form of "externally addressable" required by AC-08 when applied to vote recording decisions. Carry to 36E-04.

---

### 4.3 External Tension: AC-14 vs. Operational Security

**AC-14:** Audit scope definition must originate outside the election system's architectural boundary.  
**External Requirement:** Operational security — the attack surface of the election system must be minimized; external parties should not have unbounded influence over system behavior.

**Interaction:** Type D (Tension)  
An external party that defines audit scope has indirect influence over what the system must evidence. A malicious external party could define scope so expansive that compliance becomes operationally impossible (denial of service), or define scope in ways that probe the system's internal structure. Satisfying AC-14 fully requires trusting the external scope-defining entity — which itself requires constitutional grounding of that entity (another D43-like gap).

**36E-02-ET-03 (External Tension 3):** External audit scope definition (AC-14) and operational security are in tension. The external scope-defining entity must itself be constitutionally grounded — a recursive application of the authority grounding requirement. This may represent a D43 recursion pattern: the audit scope authority may require its own L-1/L-5 legitimacy analysis.

**ARB Note:** ET-03 is a suggestive finding that the audit scope authority may instantiate a new D43-like gap, but the evidence is not yet sufficient to create a formal D43 instance. Do NOT create a new D43 instance at this stage. Carry to 36E-03 for legitimacy evaluation. 36E-03 will determine whether the pattern meets the D43 discovery threshold.

---

### 4.4 External Tension: AC-30 vs. Governance Flexibility

**AC-30:** Criteria must be represented with sufficient precision to preclude multiple valid interpretations.  
**External Requirement:** Governance flexibility — the election organization must retain the ability to adapt eligibility criteria over time in response to changing organizational circumstances.

**Interaction:** Type D (Tension)  
Highly precise, unambiguous criteria representation reduces flexibility. Formal or computational criteria definitions (which would satisfy AC-30) are typically harder to modify than natural-language descriptions. The more constitutionally precise the criteria, the more costly and formal the ratification process (AC-23) must be. Precision and flexibility are in inherent tension.

**36E-02-ET-04 (External Tension 4):** Criteria precision (AC-30) and governance flexibility are in tension. Both are desirable; an acceptable architecture must balance them. The resolution may involve formal change-control processes for criteria modification, but this is an ADR-level decision.

---

## Section 5 — Constraint Interaction Matrix (Clustered)

The matrix shows the dominant interaction type between constraint clusters. Individual within-cluster interactions are covered in Section 2.

**Legend:**  
A = Reinforcing | B = Dependency | C = Neutral | D = Tension | E = Conflict | — = Same cluster

| | C1 | C2 | C3 | C4 | C5 | C6 | C7 | C8 |
|---|---|---|---|---|---|---|---|---|
| **C1** | — | A | A | A | A | A | A | A |
| **C2** | A | — | A/B | A | A | A | B | C |
| **C3** | A | A/B | — | A | D | C | B | C |
| **C4** | A | A | A | — | C | C | B | C |
| **C5** | A | A | D | C | — | C | B | A/D |
| **C6** | A | A | C | C | C | — | B | C |
| **C7** | B | B | B | B | B | B | — | C |
| **C8** | A | C | C | C | A/D | C | C | — |

**Notes on significant cross-cluster interactions:**
- C2 × C3: Both A (reinforcing) and B (dependency) — the independent authority relationship (C2) is a prerequisite for the challenge mechanism (C3)
- C3 × C5: Type D Tension — multiple external access requirements for different constitutional purposes (Tension Pattern 1)
- C5 × C8: Both A (reinforcing) and D (tension) — audit and evidence integrity reinforce conceptually but create access depth tension (Tension Pattern 2)
- C7 × all clusters: Type B Dependency — D43 per-instance constraints depend on all cross-cutting constraints being satisfied

---

## Section 6 — Architectural Pressure Map

Architectural pressure is defined as the degree to which satisfying a cluster or pair of constraints simultaneously creates design decisions that cannot be deferred. Higher pressure = constraint satisfaction requires specific structural commitments.

### 6.1 Pressure Classification

| Pressure Level | Definition |
|---------------|-----------|
| Critical | Constraints that cannot be satisfied without structural commitments unlikely to be reversed; satisfying these creates the most lasting architectural decisions |
| High | Constraints creating significant design pressure; satisfying them constrains multiple subsequent decisions |
| Medium | Constraints creating moderate pressure; satisfying them narrows the design space but does not preclude multiple valid approaches |
| Low | Constraints creating minimal pressure; satisfying them is compatible with wide design space |

### 6.2 Critical Pressure Areas

**CPR-01: External Independence Cluster (C2+C3+C4 applied to C7)**

Pressure source: AC-05 (one independent authority relationship per D43 function) combined with AC-09 (independent challenge reception) and AC-12 (revocation from outside) — applied across all five D43 functions.

Assessment: **Critical**

This cluster cannot be satisfied without constitutionally independent authority relationships for each of the five D43 functions. Whether those relationships require external entities, separate mandates, separate authority holders, or separate architectural structures remains unresolved — that question belongs to 36E-04. This is the single highest pressure area in the constraint catalog.

Structural implication (not design): An architecture in which all authority relationships are constitutionally self-referential cannot satisfy this cluster. At least one constitutionally independent authority relationship per D43 function is necessary. The form of that independence is unresolved.

---

**CPR-02: Audit Scope External Definition (C5 — AC-14, AC-16, AC-17)**

Pressure source: The audit scope definition must come from outside the election system, be held by an authority independent of election execution, and be realized through an architectural mechanism.

Assessment: **Critical**

Gap A-3 (the undefined expected evidence set) is not merely a data problem — it is a constitutional authority problem. Until there is a constitutionally grounded external authority that defines what constitutes a complete audit, Gap A-3 cannot be architecturally closed. This creates pressure for an external audit scope authority — an entity with constitutional standing to define what must be evidenced in an election.

Structural implication: The architecture must include a pathway by which an external, constitutionally independent entity defines audit scope. This is not a technical API decision — it is a constitutional mandate requirement.

---

**CPR-03: Dual Authority Map (C6)**

Pressure source: AC-18/AC-19/AC-20 require that bounded context design and authority boundary design are separate activities producing separate artifacts.

Assessment: **High**

Most DDD projects produce one model. This cluster requires producing two models — a functional model (bounded context map) and an authority model (authority map). These are not subsets of each other and cannot be derived from each other. The authority map must be designed with explicit awareness that authority relationships cross context boundaries (OBS-36D-02-1). This creates pressure because the authority map must be designed simultaneously with (not after) the context map.

---

**CPR-04: Certification Terminal Node (AC-27, AC-28 + TF-36C-05-11)**

Pressure source: Certification is the terminal constitutional act; it amplifies all prior threat classes; it has the most complete legitimacy gap (L-3/L-4/L-5 all undefined); and it must include an external challenge pathway.

Assessment: **High**

An unchallengeble certification is the highest-risk constitutional state the system can reach. Any architecture that reaches a state where certification is final without challenge is architecturally in the highest risk zone from the discovered threat model. This creates pressure to design certification last and with the most careful constitutional grounding.

---

**CPR-05: Evidence Integrity Decomposition (AC-29, AC-15 + AC-14)**

Pressure source: Evidence access, completeness verification, and authenticity verification must be architecturally distinct concerns; external access is required for both.

Assessment: **High**

Audit architecture is architecturally more complex than "expose a log." The constraints require: external access to evidence (AC-15), external definition of what evidence must be present (AC-14/AC-17), and a distinction between verifying presence and verifying authenticity (AC-29). These three together create pressure for a stratified evidence architecture — not a flat log, but a constitutionally structured evidence exposure system.

---

### 6.3 High vs. Medium vs. Low Pressure Summary

| Pressure | Cluster/Area | Primary Constraints |
|---------|-------------|-------------------|
| Critical | External Independence | AC-05, AC-09, AC-12 × five D43 functions |
| Critical | Audit Scope Authority | AC-14, AC-16, AC-17 |
| High | Dual Authority Map | AC-18, AC-19, AC-20 |
| High | Certification Terminal Node | AC-27, AC-28 |
| High | Evidence Integrity Decomposition | AC-29, AC-15, AC-14 |
| Medium | Lifecycle Modeling | AC-11, AC-13 |
| Medium | Criteria Precision | AC-30, AC-23 |
| Medium | Challenge Addressability | AC-08, AC-10 |
| Low | Behavioral vs Constitutional Distinction | AC-01, AC-03 |
| Low | Nominal Independence Prevention | AC-06 |

---

## Section 7 — Emerging Hypothesis: Verifier Independence

**ARB instruction (from 36E-01):** Investigate whether "Verifier Independence" — the principle that the entity that creates evidence should not be the sole entity capable of proving its authenticity — emerges naturally from interaction analysis.

### 7.1 Interaction Chain Analysis

The following constraint interaction chain is identified:

```
AC-02 (no self-verification)
+
AC-29 (presence ≠ authenticity — distinct concerns)
+
AC-15 (election system externally accessible for audit)
+
IR-H (audit independent of audited subject)
↓
Emerging Hypothesis: Verifier Independence
```

**Step-by-step derivation:**

1. **AC-02:** The architecture cannot allow an authority function to verify its own constitutional standing. Applied to evidence: the election system cannot verify the authenticity of its own evidence for constitutional purposes.

2. **AC-29:** Authenticity verification is a distinct concern from presence verification. The entity that produces evidence also knows what evidence it produced — which means it can always verify that what is present matches what it produced. But this is presence verification disguised as authenticity verification: the system says "yes, I produced that record, and it is present." This is constitutionally equivalent to self-verification (AC-02 violation).

3. **AC-15 + IR-H:** External access and independence of the audited subject together require that an entity independent of the election system can examine the evidence. This is **derived**: an independent external verifier is constitutionally necessary.

4. **Synthesis (Hypothesis):** Independent verification by an external entity is derived. However, independent verification is only constitutionally meaningful if the verifier has access to a reference standard that the system did not unilaterally control. That second claim — that the reference standard must itself be independent — is a hypothesis, not yet directly derived from the constraint chain. It follows from the logical structure of the derivation but has not been tested against alternative interpretations.

### 7.2 Formal Emergence

**Emerging Hypothesis: Verifier Independence**

The interaction chain derives one claim and hypothesizes a second:

- **Derived:** An entity independent of the election system must be able to perform evidence authenticity verification. (AC-02 + AC-15 + IR-H)
- **Hypothesized:** That independent verifier must have access to a reference standard that the election system did not unilaterally produce or control. (follows from AC-29 + step 4 above — strong hypothesis, not yet proven)

The distinction matters. The first claim constrains who can verify. The second claim constrains what they verify against. Both are potentially significant architectural constraints; only the first is established by direct derivation at this stage.

**36E-02-EH-01 (Emerging Hypothesis — Verifier Independence):** Constitutional evidence authenticity verification appears to require an independent verifier (derived) AND may require an independent reference standard (hypothesized). The specific form of the independent verifier and of any independent reference standard is unresolved. 36E-03 should test whether alternative interpretations can satisfy the derivation without requiring an independent reference standard.

This hypothesis is closely related to — but distinct from — TC2-DI-01 (AC-29). TC2-DI-01 identifies that presence and authenticity are different concerns. Verifier Independence hypothesizes that the reference standard for authenticity must also be independent. They are complementary discoveries.

**ARB Note:** 36E-02-EH-01 is not yet AC-31. Whether it can be promoted to a constraint depends on 36E-03 validation of the hypothesis. It is presented here as an emerging hypothesis from interaction analysis.

---

## Section 8 — Tension and Conflict Summary

### 8.1 Type D Tensions (both valid, create architectural pressure)

| ID | Constraints | Tension Description | Resolution Belongs To |
|----|-------------|--------------------|-----------------------|
| TP-01 | C3 × C5 (AC-08 × AC-15) | Multiple constitutional purposes require external access; single vs. separate surfaces | 36E-04 |
| TP-02 | C5 × C8 (AC-15 × AC-29) | Completeness access vs. authenticity access depth may differ | 36E-04 |
| ET-01 | AC-15 × Vote Secrecy | External audit access vs. ballot confidentiality | Round 37 (ADR) |
| ET-02 | AC-08 × Receipt-Freeness | Challenge addressability vs. vote receipt-freeness | 36E-04 (conflict status unproven) |
| ET-03 | AC-14 × Operational Security | External scope definition vs. attack surface control | 36E-03/04 |
| ET-04 | AC-30 × Governance Flexibility | Criteria precision vs. adaptability | Round 37 (ADR) |
| C5-Int | AC-14/AC-15 × IR-H | Scope comprehensiveness vs. access control selectivity | 36E-04 |

### 8.2 Unresolved Tension Candidates (conflict status unproven)

| ID | Constraints | Description | Analysis Needed |
|----|-------------|-------------|-----------------|
| EC-01 | AC-08 × Receipt-Freeness (ET-02) | Challenge addressability and receipt-freeness in potential tension; conflict status unproven | 36E-04 must assess whether satisfying resolution exists |

**Note on EC-01:** The history of E2E-V voting demonstrates serious architectural attempts to satisfy individual verifiability and receipt-freeness simultaneously. Conflict status is therefore unproven. Whether a satisfying resolution exists for this system depends on the specific form of "externally addressable" required by AC-08 when applied to vote recording decisions. EC-01 is an unresolved tension candidate — not a confirmed Type E conflict. The determination belongs to 36E-04.

---

## Section 9 — Implications for Round 36E-03 (DDD Impact Assessment)

The interaction analysis produces the following priority inputs for 36E-03:

**36E-03 must assess:**

1. How are the critical-pressure areas (CPR-01 through CPR-05) reflected in the existing DDD model? Which existing aggregates and contexts are already constrained by these pressure areas?

2. Does the existing DDD model support a separate authority map (CPR-03 / C6)? If not, what is the structural gap?

3. How does AC-24 (audit D43 constraints) interact with the existing Audit context design from 36B? Are the audit constraints already architecturally represented?

4. Does the emerging Verifier Independence hypothesis (EH-01) require a new DDD concept? Does 36E-03 evidence confirm or refute the hypothesis that an independent reference standard is required (not only an independent verifier)?

5. What is the DDD representation of the constitutionally independent authority relationship required by CPR-01 — and does the form of independence (separate entity, separate mandate, separate authority holder) affect whether an existing modeling element can represent it?

6. Does ET-03 (external audit scope authority) meet the D43 discovery threshold? If the audit scope-defining authority requires its own L-1/L-5 legitimacy grounding, does this constitute a new D43 instance — or is it a sub-function of D43-AUDIT already discovered in 36B?

---

## Section 10 — ARB Decision Block

**[APPROVED]**

ARB Assessment: Research Quality High | Constraint Discipline Very Good | Architecture Neutrality Good (after revisions) | ARB Readiness: Approved

### Required Revisions Applied

1. **CPR-01 external authority wording weakened** — "some form of external-to-internal constitutional authority is necessary" removed; replaced with "constitutionally independent authority relationships are necessary; form of independence unresolved"
2. **EP-01 downgraded from Emerging Pattern to Emerging Hypothesis (EH-01)** — independent verifier = derived; independent reference standard = hypothesized; distinction between the two made explicit
3. **EC-01 downgraded from conflict candidate to unresolved tension candidate** — conflict status unproven; E2E-V precedent acknowledged; determination deferred to 36E-04
4. **ET-03 ARB note added** — possible D43 recursion pattern noted; new D43 instance NOT created; legitimacy evaluation deferred to 36E-03
5. **Section 9 renumbered and extended** — six distinct items, no duplicates; item 6 added for ET-03 D43 threshold assessment

### Summary of Deliverables

**Produced:**
- Constraint Interaction Matrix (Section 5) — 8×8 clustered
- Architectural Pressure Map (Section 6) — 2 Critical, 3 High, 3 Medium, 2 Low areas identified
- Tension Catalog (Section 8.1) — 7 tensions identified
- Unresolved Tension Candidates (Section 8.2) — 1 candidate (EC-01), conflict status unproven
- Emerging Hypothesis: Verifier Independence (Section 7) — EH-01
- External Tension ET-03 flagged as possible D43 recursion

**Not produced (by discipline):** solutions, designs, bounded contexts, aggregates, ADRs.

### ARB Governing Decision

**Round 36E-02: APPROVED**  
**Round 36E-03: AUTHORIZED**

36E-03 is the first round where Constitutional Findings are analyzed directly against the DDD model. 36E-03 will determine whether the existing bounded contexts, aggregates, and context map can satisfy the constitutional constraints discovered in 36A–36D. This is one of the most consequential rounds in the program.

### Open Questions Carried Forward

**OQ-36E-02-01 (carried to 36E-03):** Can EH-01 (Verifier Independence) be promoted to a constraint? Does 36E-03 DDD analysis confirm or refute the hypothesis that an independent reference standard is required, not only an independent verifier?

**OQ-36E-02-02 (carried to 36E-04):** Is EC-01 (challenge addressability vs. receipt-freeness) Type D or Type E? Resolution depends on the form of "externally addressable" required by AC-08 applied to vote recording.

**OQ-36E-02-03 (carried to 36E-03):** Does ET-03 meet the D43 discovery threshold? Is the audit scope authority a sub-function of D43-AUDIT or a new D43 instance?

**OQ-36E-02-04 (carried to 36E-03):** Is CPR-01's "constitutionally independent authority relationship" a deployment architecture constraint (separate system) or only an authority architecture constraint (separate mandate)? The form of independence question must be tested against existing DDD model elements.

---

*Round 36E-02 — Constraint Interaction Analysis — Submitted for ARB Review*  
*Research Program: NRNA DDD Trustworthiness*  
*Document: Round36E-02_Constraint_Interaction_Analysis.md*
