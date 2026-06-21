# Round 38B-04 — Authority Appointment Process Specification

**Program:** NRNA DDD Trustworthiness Research Program
**Round:** 38B-04 — Governance Specification
**Status:** APPROVED WITH MINOR REVISIONS APPLIED (ARB Review 2026-06-18; R1/R2/R3 applied)
**Gap Addressed:** Gap 6 — Authority Appointment Process Specification
**Governing Question:** Who appoints constitutional authorities, through what process, with what independence guarantees?
**Date:** 2026-06-17

**Predecessors:**
- 38B-01 — Constitutional Interpretation Authority (Gap 4) — APPROVED
- 38B-02 — AC-31 Governance Specification (Gap 5) — APPROVED WITH MINOR OBSERVATIONS APPLIED
- 38B-03 — GovernanceState Phase Record Governance (Gap 7) — APPROVED WITH INTEGRATIONS

**Binding Inputs:**
- Gap 6 ranked #5 in 38A-06 provisional gap ranking
- TM-04 (CAB Capture): C-F approaching F — appointment process capture enables adjudication capture
- TM-37 (Multi-Authority Coalition): FAIL — shared appointment roots enable sequential capture
- TM-38 (Sequential Cascade): FAIL — unspecified appointment processes enable cascade
- TM-39 (Independence Illusion): FAIL — constitutional independence without appointment independence verification
- ADR-2: independence forms (Option B Committee, Option C External, Option D Hybrid) are specified; appointment processes are not
- ADR7-INV-02: No authority may self-grant or restrict standing — appointment is a precursor to standing
- OBS-38B01-AI1: Each new authority adds governance burden — justify any new appointment body
- OBS-38B02-01: MA dependency increasing; track concentration tradeoffs at each specification step
- OBS-38B03-01: All three trust roots now governed; governance surface is complete
- AIC-36C-06-05: Designed Distribution ≠ Actual Distribution — designed independence does not guarantee actual independence if appointment processes reconcentrate authority

**Binding Carry-Forwards:**
- OQ-38A05-02 (Finality vs. Validity): PROTECTED — not implicitly resolved in this document; routes to CIC if triggered
- 38B01-INV-01: CIC interprets; CIC does not operationally govern; CAB adjudicates; neither governs operationally
- OBS-38B02-01: MA dependency tracking obligation — applies to every power assigned to MA in this round
- AA-01: MA legitimacy is pre-constitutional and terminal

**Scope:** Constitutional governance specification only. Who appoints, through what process, with what independence verification. Not operational HR, not staffing, not implementation.

---

## Part A — What Gap 6 Is

### A.1 The Gap

The constitutional architecture specifies THAT authorities must be independent. It specifies independence FORMS (ADR-2). It specifies WHAT independence means for each D43 authority. It does not specify WHO appoints them, through what PROCESS, with what VERIFICATION that the appointment process itself preserves independence.

**What exists:**
- Independence forms for each D43 authority (ADR-2)
- Constitutional safeguards for CAB (ADR-7)
- ADR7-INV-02: no self-dealing in standing
- CIC establishment and dissolution governance (38B-01)
- AC-31 governance — Tier 2 designation process (38B-02)
- GovernanceState phase governance (38B-03)

**What does not exist:**
- Who appoints each authority aggregate
- Whether appointment processes for different authorities are independent of each other
- Verification that an appointment process is genuinely independent (not just constitutionally labelled as such)
- What constitutes a valid appointment vs. an appointment that creates circular dependence
- What happens when an appointment process is captured or fails

### A.2 Why Gap 6 Matters

**TM-38 (Sequential Cascade)** demonstrated that unspecified appointment processes with shared governance roots enable sequential authority capture. If the same body appoints multiple authorities, capturing that body enables capturing all appointed authorities. Independence forms provide structural separation — but if appointment processes converge at a shared root, that separation is undermined at the source.

**TM-39 (Independence Illusion)** demonstrated that constitutional independence designations do not guarantee operational independence. Authorities can be constitutionally designated as independent while remaining appointment-dependent upon a body whose interests are not aligned with independence.

**AIC-36C-06-05 (Designed Distribution ≠ Actual Distribution):** Designed independence in authority forms does not guarantee actual independence if appointment processes reconcentrate authority at a single point.

**Gap 6 is about preventing the appointment process from being the vulnerability that undermines the independence forms specified in ADR-2.**

---

## Part B — Authorities Requiring Appointment Specification

| Authority | Independence Form (ADR-2) | Current Appointment Status |
|-----------|--------------------------|---------------------------|
| **EnrollmentAuthority** | Option B (Committee) | Unspecified |
| **CriteriaAuthority** | Option B (Committee) | Unspecified |
| **AuditAuthority** | Option D (Hybrid) | Unspecified |
| **GovernanceAuthority** | Option B (Committee) | Unspecified |
| **CertificationAuthority** | Option C (External) | External org designated — but which org, and who designates it? |
| **ChallengeAdjudicationBody** | Option B or C (ADR-7) | Unspecified |
| **Constitutional Interpretation Chamber** | Option B (Committee) | Unspecified; 38B-01 deferred appointment specification to Gap 6 |
| **AC-31 Tier 3 Verification** | Distributed (38B-02) | Uses existing authority aggregates — no new appointment required |

**Note on AC-31 Tier 3:** 38B-02 distributes Tier 3 verification across existing authority aggregates (GovernanceAuthority, CertificationAuthority, AuditAuthority). Their appointment is governed by the specifications for those aggregates in this document. No new appointment body is introduced. OBS-38B01-AI1 satisfied: no additional governance burden from AC-31 governance model.

---

## Part C — Four Types of Constitutional Independence

**Architectural requirement:** Appointment independence and authority independence are distinct constitutional properties. Prior rounds (ADR-2, 38B-01) addressed operational and interpretive independence. Gap 6 addresses appointment independence. A complete constitutional independence model requires all four types to be distinguished before appointment chains are specified.

### C.1 Operational Independence (ADR-2)

**Definition:** An authority's decisions are not subject to direction or override by other constitutional actors operating within the same domain.

**What it governs:** Decision autonomy — the authority decides without requiring permission from another actor.

**ADR-2 implementation:** Option B (Committee) = internal distributed governance; Option C (External) = organizational separation; Option D (Hybrid) = mixed.

**Limitation:** Operational independence does not specify who creates or maintains the authority's membership. An authority can have fully autonomous decisions while being appointment-dependent upon a body whose interests conflict with those decisions — and whose long-term influence operates through succession.

### C.2 Interpretive Independence (38B-01)

**Definition:** An interpretive authority's constitutional interpretations are not subject to reversal by the constitutional actors being interpreted.

**What it governs:** The authority whose acts are interpreted cannot override the interpretation. Interpretation can only be overridden by EC amendment — a constitutional, not operational, process.

**38B-01 implementation:** CIC holds interpretive independence over all constitutional actors including MA.

**Limitation:** Interpretive independence does not address who appointed CIC, or whether the appointing body can indirectly influence future interpretation through control of CIC succession appointments.

### C.3 Appointment Independence (Gap 6 target)

**Definition:** An authority's membership is appointed through a process that is structurally independent of the entities the authority governs, adjudicates, or interprets.

**What it governs:** Whether the appointment pathway creates hidden dependence on the entities the authority is meant to be independent from.

**Constitutional requirements for appointment independence:**
1. No authority appoints its own members or successors
2. No authority appoints the body that adjudicates challenges to its own decisions
3. The appointment body must not be structurally subject to the appointed authority's constitutional mandate
4. Shared appointment roots are constrained by structural relationship rules, not numerical limits

**Distinction from operational independence:** An authority may have complete operational independence (decisions are autonomous) while lacking appointment independence (members were selected by a body with interests in those decisions). ADR-2 addresses the first; Gap 6 addresses the second.

### C.4 Governance Independence

**Definition:** An authority's internal governance — how it makes decisions, manages its members, and sustains its processes — is not controlled by external constitutional actors.

**What it governs:** Self-governance of the authority body after appointment.

**Relationship to appointment:** Appointment independence is a precondition for sustained governance independence. Once appointed, the authority's internal governance must be insulated. An authority with appointment independence but without governance independence remains vulnerable to indirect capture through procedural control of internal operations.

**This specification:** Governance independence after appointment is inherited from the ADR-2 independence form. Appointment specification adds the structural precondition: creating the initial membership through a process that does not predetermine governance outcomes.

### C.5 Independence Type Interaction Matrix

| Authority | Operational (ADR-2) | Interpretive (38B-01) | Appointment (38B-04) | Governance (ADR-2) |
|-----------|--------------------|-----------------------|----------------------|--------------------|
| EnrollmentAuthority | Option B | N/A | CriteriaAuthority | Option B internal |
| CriteriaAuthority | Option B | N/A | Membership Assembly | Option B internal |
| AuditAuthority | Option D | N/A | Membership Assembly | Option D internal |
| GovernanceAuthority | Option B | N/A | Membership Assembly | Option B internal |
| CertificationAuthority | Option C | N/A | Membership Assembly | External org governance |
| CAB | Option B/C | N/A | Membership Assembly | Option B/C internal |
| CIC | Option B | Yes (38B-01) | Membership Assembly | Option B internal |
| AC-31 Tier 3 | Distributed | N/A | Existing authorities | Existing |

---

## Part D — Appointment Model Evaluation

### D.1 Option A — Unified Appointment Body (REJECTED)

**Description:** A single constitutional body appoints all authority aggregates.

**Assessment:**
- Simple; clear accountability line
- Maximum concentration risk: capturing one body captures all appointment authority
- Directly enables TM-38 (Sequential Cascade) and TM-37 (Multi-Authority Coalition)
- OBS-38A06-01 check: if the appointment body is subject to any of the appointed authorities' decisions, self-referential risk exists

**Verdict: REJECTED.** Creates the shared appointment root vulnerability identified in 38A-03 threat analysis.

### D.2 Option B — Distributed Appointment by Function (SELECTED)

**Description:** Different authorities are appointed by different constitutional bodies, selected on the basis of the authority's function and the independence requirements of the appointment relationship.

**Assessment:**
- Reduces shared appointment root vulnerability
- Enables tailored independence requirements per authority function
- Prevents any single capture event from compromising all appointment authority simultaneously
- Complexity: multiple appointment processes to specify and maintain

**Selection basis:** Functional distribution of appointment authority addresses TM-38 and TM-37 without requiring a new constitutional body (OBS-38B01-AI1). Where MA appointment is constitutionally appropriate, it is used. Where functional distribution produces better appointment independence properties without MA concentration, it is used.

**Verdict: SELECTED.** See Part E.

### D.3 Option C — Self-Appointing Authorities (REJECTED, CATEGORICALLY)

**Description:** Each authority appoints its own successors or members.

**Assessment:**
- OBS-38A06-01: FAILS all three questions (validates own appointments? yes; self-determines succession? yes; adjudicates own legitimacy? potentially yes)
- Violates appointment independence definition: an authority cannot be independent of itself
- TM-14 applied to appointments: authorities perpetuate their own composition through successor selection
- ADR7-INV-02: no self-dealing in standing — self-appointment is the structural precursor to self-dealing

**Verdict: REJECTED CATEGORICALLY.** Self-appointment is structurally incompatible with constitutional independence. No exception.

### D.4 Option D — Membership Assembly Appoints All (NOT SELECTED as sole model)

**Description:** MA appoints all authority aggregates directly.

**Assessment:**
- Maximum democratic legitimacy; MA is constitutional sovereign and source-of-source (OBS-ADR7-SS1)
- Operational concern: MA is not a standing body; appointing seven authority aggregates requires frequent convening
- Concentration: MA already holds ratification, terminal appeal, and AC-31 designation authority — universal appointment authority adds seven functions simultaneously
- OBS-38B02-01 carry-forward: MA dependency already increasing through 38B-01, 38B-02, 38B-03; sole appointment model would be its maximum expression

**Verdict: NOT SELECTED as sole model.** MA appointment is constitutionally appropriate for the highest-legitimacy authorities requiring sovereign grounding. Reserved for those. One authority (EnrollmentAuthority) benefits from functional distribution to CriteriaAuthority. See Part E.

---

## Part E — Selected Model: Distributed Appointment with MA Designation for Highest-Legitimacy Authorities

### E.1 38B04-INV-01 (Revised): Appointment Independence Structural Constraint

**38B04-INV-01:** Appointment relationships must satisfy the following structural constraints:

1. **No self-appointment.** No authority may appoint its own members, successors, or replacement members.
2. **No circular appointment.** No authority may appoint the body that adjudicates challenges to its own decisions, nor the body that interprets the constitutional provisions that govern its own operations, for the purpose of influencing those adjudications or interpretations.
3. **No downward appointment capture.** No authority may appoint the body that has primary oversight authority over that authority's own operations.
4. **Constitutional independence of the appointer.** The appointment body must not be structurally subject to the appointed authority's constitutional mandate within the operational election domain.

**Note on numerical limits:** The candidate invariant (no body may appoint more than three authorities) was structurally inadequate — it would have been violated by the selected model. The constitutional concern is not the number of appointments but the relationship between appointer and appointee. MA appointing six operational/interpretive authorities is constitutionally appropriate because MA is the constitutional sovereign, not an operational participant subject to those authorities' mandates. The constraint is structural (which relationships are forbidden), not numerical (how many is too many).

**Compliance check for selected model:** MA appoints CriteriaAuthority, AuditAuthority, GovernanceAuthority, CertificationAuthority, CAB, and CIC. MA is not operationally subject to any of these authorities' mandates — MA is the source-of-source. All four structural constraints in 38B04-INV-01 are satisfied for the MA appointment chain.

### E.2 Appointment Distribution

| Authority | Appointment Body | Constitutional Justification |
|-----------|-----------------|------------------------------|
| **EnrollmentAuthority** | CriteriaAuthority | Functional alignment: criteria define eligibility; enrollment executes it. CriteriaAuthority is MA-appointed (sovereign legitimacy chain preserved); not subject to EnrollmentAuthority's mandate; no circular dependency. See E.3 for alternative evaluation. |
| **CriteriaAuthority** | Membership Assembly | Criteria define who may vote — who constitutes the membership. MA, as the membership sovereign, must hold appointment authority for the body that defines membership criteria. |
| **AuditAuthority** | Membership Assembly | IR-H independence requires external appointment. MA is constitutionally external to election operations. |
| **GovernanceAuthority** | Membership Assembly | Governance authorization affects the entire constitutional election framework. MA, as governance sovereign, must hold appointment authority. |
| **CertificationAuthority** | Membership Assembly | Terminal evaluator — the last constitutional word on election integrity. MA designates the external organization that holds this terminal position. |
| **ChallengeAdjudicationBody** | Membership Assembly | Adjudicates all constitutional challenges including challenges to MA decisions. MA is not an operational party subject to CAB adjudication. Structural tension documented in OBS-38B04-01. |
| **Constitutional Interpretation Chamber** | Membership Assembly | Interprets constitution, including provisions governing MA. MA appointing CIC creates the interpretive dependence chain analyzed in Part G. Safeguard specified. |
| **AC-31 Tier 3** | Existing authorities | No new appointment required — uses existing authority aggregates (GovernanceAuthority, CertificationAuthority, AuditAuthority). OBS-38B01-AI1 satisfied. |

### E.3 EnrollmentAuthority Appointment — Alternative Evaluation

The GovernanceAuthority (initial candidate) creates a circular dependency chain: GovernanceAuthority relies on GovernanceState for its operating phase authority; GovernanceState integrates voter eligibility records produced by EnrollmentAuthority; GA appointing EA creates GA → EA → GovernanceState → GA (indirect).

**Alternatives evaluated:**

| Appointment Body | Assessment | Verdict |
|------------------|------------|---------|
| **GovernanceAuthority** | Circular dependency via GovernanceState (as above). | NOT SELECTED |
| **Membership Assembly** | Maximum legitimacy; increases MA appointment count from 6 to 7; OBS-38B02-01 accumulation. | NOT SELECTED as primary for EA |
| **CriteriaAuthority** | Criteria define who is eligible; enrollment executes those criteria. Functional alignment without circular dependence. CriteriaAuthority is MA-appointed, preserving sovereign legitimacy chain without direct MA appointment of EA. | SELECTED |
| **New Appointment Panel** | New body — OBS-38B01-AI1 burden; not justified for single appointment function. | REJECTED |

**Selection: CriteriaAuthority appoints EnrollmentAuthority.** Distributes one appointment from MA. Aligns appointment authority with functional oversight. Avoids the GovernanceAuthority circular chain.

### E.4 Appointment Process Requirements

For each appointment, the following constitutional requirements apply:

**Nomination:**
- Nominations may originate from: MA members, constitutional observers, existing authority holders (other than the authority being filled), or any party with S-1 standing
- Self-nomination is prohibited for any authority that will oversee the nominator's own function
- Nomination records are constitutional records — preserved under 38B-03 GovernanceState governance

**Vetting:**
- Candidates must meet constitutional independence requirements for the specific authority (ADR-2 form requirements)
- For Option C authorities (CertificationAuthority), the external organization's independence structure must be verified — not merely designated
- CIC may be asked to interpret whether a candidate satisfies constitutional independence requirements
- Vetting records are constitutional records

**Confirmation:**
- MA confirmation: simple majority for operational authorities (AuditAuthority, GovernanceAuthority); supermajority for terminal and interpretive authorities (CertificationAuthority, CAB, CIC, CriteriaAuthority)
- CriteriaAuthority confirmation: internal CriteriaAuthority procedural majority (for EnrollmentAuthority appointment)
- Confirmation must precede the authority assuming its mandate
- Confirmation records are constitutional records — publicly accessible

**Term:**
- Authority holders serve fixed terms specified in ElectionConstitution
- Terms are staggered to prevent simultaneous vacancy across multiple authorities (mitigates OQ-38B04-04 emergency scenario under normal conditions)
- Re-appointment permitted; not automatic
- No authority holder may serve indefinitely — maximum consecutive terms EC-specified

**Removal:**
- Authority holders may be removed through L-4 revocation (ADR-5 remedy R-6)
- MA may remove MA-appointed authority holders through constitutional process (distinct from L-4 challenge-based revocation)
- CriteriaAuthority may remove EnrollmentAuthority holders through constitutional process
- Removal requires stated constitutional grounds — not discretionary dismissal
- Removal records are constitutional records

### E.5 Independence Verification

**Who verifies:** CIC interprets whether appointment processes satisfy constitutional independence requirements. CAB adjudicates challenges to specific appointments.

**What is verified:**
1. The appointment body was constitutionally authorized to make this appointment
2. The appointee meets independence requirements for the authority (ADR-2 form)
3. The appointment process was procedurally valid
4. The appointment body is constitutionally independent of the authority being appointed (38B04-INV-01)
5. The appointment does not create a relationship pattern that violates 38B04-INV-01 structural constraints

**Challenge pathway:** Any party with S-1/S-2/S-3 standing may challenge an appointment through CAB. CIC interprets constitutional questions about appointment validity. Special recusal conditions apply to CIC members whose own appointment is being challenged — see Part G.

---

## Part F — OBS-38A06-01 Self-Referential Review

**Question 1: Does the appointment model validate its own appointments?**

No. MA appoints; CIC interprets constitutional validity of appointment provisions; CAB adjudicates specific appointment challenges. No body validates its own appointment decisions. MA does not adjudicate challenges to its own appointments — CAB does.

**Residual structural tension:** MA appoints CAB, which adjudicates MA decisions. This is not self-adjudication — MA does not adjudicate its own decisions — but the appointer determines the composition of the adjudicator of the appointer's own decisions. This is constitutionally analogous to political appointment of judges: accepted in constitutional systems, but noted as a structural tension. Documented in OBS-38B04-01.

**Question 2: Does the appointment model determine its own succession?**

No. Terms are EC-specified. MA appoints successors to MA-appointed authorities. CriteriaAuthority appoints successors to EnrollmentAuthority. No authority appoints its own successor.

**Question 3: Does the appointment model adjudicate challenges to its own legitimacy?**

No. Challenges to specific appointments go through CAB with CIC interpretation. Challenges to the appointment constitutional framework itself (the model in 38B-04) require EC amendment. Challenges to MA's authority to appoint are pre-constitutional (AA-01) — MA is the constitutional sovereign.

**OBS-38A06-01 Verdict:** No self-referential chain identified that fails the three-question test. One structural tension documented (MA → CAB composition → MA adjudication) — analogous to constitutional judicial appointment norms; documented in OBS-38B04-01, not eliminated by architectural assertion.

---

## Part G — OBS-38B04-01: CIC Interpretive Dependence Chain

**Description:** MA appoints CIC. CIC interprets constitutional validity of MA actions, including MA's appointment decisions and the constitutional provisions governing MA's appointment authority. This creates the following chain:

```
MA appoints CIC members
    ↓
CIC interprets whether MA appointment actions are constitutionally valid
    ↓
CIC interprets the EC provisions that govern MA's appointment authority
    ↓
If CIC's own appointment is challenged, CIC must interpret the constitutional
provisions that govern CIC's own appointment process (candidate self-reference)
```

**Is this self-referential invalidity?** No — but it is a candidate interpretive dependence chain that must be acknowledged and safeguarded.

**Distinction from self-reference:** CIC interpreting the rules of its own appointment is constitutional interpretation, not self-dealing. This is constitutionally analogous to a supreme court interpreting the constitution's appointments clause despite being itself appointed under it. The legitimacy of the interpretation comes from the institutional independence of the interpretive body, not from the appointment chain being closed.

**Constitutional acceptability conditions:**
1. CIC's appointment challenge pathway routes through CAB adjudication, not CIC self-adjudication
2. Any CIC member whose own appointment is being challenged before CAB is automatically recused from CIC deliberations regarding the constitutional interpretation of that challenge
3. The ElectionConstitution must explicitly specify CIC recusal conditions for self-referential appointment challenges

**Required EC safeguard (must be specified in ElectionConstitution):** "Any CIC member whose own appointment is subject to challenge before CAB is automatically recused from CIC deliberations regarding the constitutional interpretation of that challenge. The remaining CIC members constitute a quorum for that interpretation. If all CIC members' appointments are simultaneously challenged, CIC is recused in its entirety and CAB adjudicates on the basis of prior CIC interpretive precedents."

**OBS-38B04-01 conclusion:** The interpretive dependence chain is constitutionally manageable, not constitutionally invalidating. The EC safeguard is required before this model is constitutionally complete. This is a monitoring observation with a required EC action, not a blocking architectural defect.

---

## Part H — OBS-38B04-02: MA Concentration Accumulation (Primary Architectural Finding)

**This is the primary architectural finding of 38B-04, fulfilling the OBS-38B02-01 tracking obligation.**

Through 38B-01, 38B-02, 38B-03, and now 38B-04, the Membership Assembly has accumulated constitutional functions at each specification step. Each individual assignment was constitutionally justified. The cumulative effect must be named as a primary finding, not a footnote.

**MA constitutional functions at close of 38B-04 (see full inventory in Part J):**

*From ADR baseline:*
1. EC ratification
2. ADR ratification (as constitutional amendments)
3. Terminal appeal authority (ADR-5, R-6)
4. Source-of-source constitutional legitimacy (OBS-ADR7-SS1)

*Accumulated through 38B rounds:*
5. CIC establishment ratification (38B-01)
6. CIC dissolution approval (38B-01)
7. AC-31 Tier 2 designation (38B-02)
8. Background terminal phase dispute authority (38B-03)
9. CriteriaAuthority appointment (38B-04)
10. AuditAuthority appointment (38B-04)
11. GovernanceAuthority appointment (38B-04)
12. CertificationAuthority designation (38B-04)
13. CAB appointment (38B-04)
14. CIC appointment (38B-04)

**Assessment of concentration:**

MA is the constitutional sovereign. Sovereign functions concentrate in the sovereign body — this is what sovereignty means. The concentration is not intrinsically pathological.

But three facts demand explicit naming:

**Fact 1:** MA started the 38B round with 4 baseline functions. It ends 38B-04 with 14 functions. The 38B specification rounds added 10 new MA functions — a 250% increase in assigned constitutional functions in a single specification cycle.

**Fact 2:** TM-07 (MA Capture) becomes more consequential with each additional MA function. An actor that captures MA at the close of 38B-04 gains: appointment authority over six constitutional bodies, interpretive body ratification, AC-31 designation, terminal appeal, and constitutional ratification. This is comprehensive constitutional governance capture.

**Fact 3:** MA is not a standing body. Constitutional governance that depends on MA to convene is governance that can stall if MA cannot convene. OQ-38B04-04 (emergency appointment protocol) is a direct consequence.

**OBS-38B04-02 conclusion:** MA concentration has reached a level requiring explicit assessment in 38B synthesis. The question "Did we solve appointment concentration or merely transfer it to MA?" cannot be fully answered within 38B-04. The answer depends on the 38B synthesis evaluation of cumulative MA dependency across all rounds.

---

## Part I — OBS-38B04-03: Appointment Independence vs. Authority Independence

**Appointment independence and authority independence are distinct constitutional properties.**

**Authority independence (ADR-2):** An authority's decisions are not subject to direction or override by other constitutional actors. This is the decision dimension — the authority acts autonomously.

**Appointment independence (38B-04):** An authority's members are appointed through a process that is structurally independent of the entities the authority governs, adjudicates, or interprets. This is the structural dimension — the authority's composition is not controlled by the entities it oversees.

**Why the distinction matters:**

An authority can have full authority independence (all decisions are constitutionally its own) while lacking appointment independence (its members were selected by a body whose interests intersect with those decisions). ADR-2 addresses the first property at the decision level; 38B-04 addresses the second property at the structural level.

**Appointment independence as a precondition for sustained authority independence:** An authority granted operational independence may have that independence eroded over time if appointment processes are controlled by entities with interests in the authority's decisions. This is the mechanism underlying TM-39 (Independence Illusion) — formal independence that decays through appointment composition. The 38B04-INV-01 structural constraint protects the precondition for sustained operational independence.

**Note on sovereign appointment:** MA appointing multiple authorities does not violate appointment independence in the constitutional sense. MA is the constitutional sovereign — not an operational actor subject to the appointed authorities' mandates. The appointment independence concern applies when an appointing body is subject to the appointee's governance or adjudication. MA is not operationally subject to CriteriaAuthority, AuditAuthority, GovernanceAuthority, CertificationAuthority, CAB, or CIC. The sovereign appoints constitutional actors; the sovereign is not governed by them in its sovereign function.

---

## OBS-38B04-04: AA-01 Dependency Accumulation *(R1 Applied)*

**Every additional constitutional power assigned to Membership Assembly increases the architecture's dependence on eventual AA-01 resolution.**

AA-01 (38A-01 Hidden Assumption Register): MA legitimacy is assumed but not constitutionally grounded within the architecture. MA is the constitutional sovereign, but the architecture does not specify what makes MA itself constitutionally legitimate — who granted MA its authority, through what process, and whether that grant can be contested.

At the opening of 38B-01, MA held 4–5 baseline constitutional functions. At the close of 38B-04, MA holds 14–15.

**The dependency chain:**

```
Every MA constitutional function requires MA to be constitutionally legitimate
    ↓
MA constitutional legitimacy depends on AA-01 resolution
    ↓
AA-01 is unresolved and pre-constitutional
    ↓
Every additional MA function adds one more constitutional dependency
on an unresolved pre-constitutional assumption
```

**This is not a new risk** — AA-01 was recognized in 38A-01 and has been carried through all 38B rounds. But the magnitude of that dependency has increased substantially. At 14 MA functions, AA-01 is no longer a background assumption — it is the foundational premise of the entire constitutional governance model that 38B has constructed.

**OBS-38B04-04 conclusion:** The architecture must not attempt to resolve AA-01 within any 38B specification — it is pre-constitutional. But the architecture must explicitly acknowledge that the constitutional governance model constructed in 38B-01 through 38B-04 is entirely contingent on MA legitimacy, and that MA legitimacy is contingent on AA-01. This is not a defect in the 38B specifications; it is the constitutional bedrock dependency that 38B syntax has made visible at scale.

**Carried to 38B synthesis and 38B-05:** Any constitutional floor specification (OBS-38A06-SD1) must account for the fact that the floor is built on MA legitimacy — which is itself grounded in AA-01. Gap 3 (EC Amendment Governance) touches this directly: EC amendment authority traces to MA ratification authority traces to AA-01.

---

## Part J — Full MA Concentration Inventory (Cross-Document)

*Required by architectural review framework: "Produce a complete inventory of all powers currently assigned to Membership Assembly across ADR-1 through ADR-7, 38B-01 through 38B-04."*

### J.1 Constitutional Framework Functions (ADR Baseline)

| Function | Source | Nature |
|----------|--------|--------|
| EC ratification | ADR-1 (constitutional founding) | Foundational sovereignty — MA creates the constitutional frame |
| EC amendment ratification | ADR-1 / Gap 3 (38B-05 pending) | Amendment sovereignty — MA ratifies constitutional changes |
| ADR ratification as constitutional amendments | ADR-1 through ADR-7 (pattern) | Governance sovereignty — MA ratifies architectural decisions |
| Source-of-source constitutional legitimacy | OBS-ADR7-SS1 | Meta-constitutional — MA legitimacy grounds all other legitimacy |
| Terminal appeal authority (L-4 revocation R-6) | ADR-5 | Remedial sovereignty — MA is the terminal remedy |

### J.2 Round 38B Accumulated Functions

| Function | Source Round | Nature |
|----------|-------------|--------|
| CIC establishment ratification | 38B-01 | Interpretive sovereignty — MA authorizes the interpretive body to exist |
| CIC dissolution approval | 38B-01 | Interpretive sovereignty — MA authorizes interpretive body termination |
| AC-31 Tier 2 designation | 38B-02 | Authenticity sovereignty — MA designates AC-31 governance parties |
| Background terminal phase dispute | 38B-03 | Temporal sovereignty — MA as background sovereign when temporal governance deadlocks |
| CriteriaAuthority appointment | 38B-04 | Membership sovereignty — MA appoints the body defining membership criteria |
| AuditAuthority appointment | 38B-04 | Audit sovereignty — MA appoints the external audit body |
| GovernanceAuthority appointment | 38B-04 | Governance sovereignty — MA appoints the operational governance body |
| CertificationAuthority designation | 38B-04 | Certification sovereignty — MA designates the terminal evaluation body |
| CAB appointment | 38B-04 | Adjudicative sovereignty — MA appoints the challenge adjudication body |
| CIC appointment | 38B-04 | Interpretive sovereignty — MA appoints the constitutional interpretation body |

### J.3 MA Concentration Summary by Constitutional Dimension

| Dimension | MA Functions | Count |
|-----------|-------------|-------|
| **Foundational** | EC ratification, ADR ratification, source-of-source | 3 |
| **Remedial** | Terminal appeal (R-6) | 1 |
| **Interpretive** | CIC establishment, CIC dissolution, CIC appointment | 3 |
| **Authenticity** | AC-31 Tier 2 designation | 1 |
| **Temporal** | Background phase dispute | 1 |
| **Appointment** | CriteriaAuthority, AuditAuthority, GovernanceAuthority, CertificationAuthority, CAB, CIC | 6 |
| **TOTAL** | | **15** |

*Note: CIC appears twice (establishment/dissolution as 38B-01 functions; appointment as 38B-04 function) — counted separately by source to preserve traceability.*

### J.4 Functions NOT Held by MA

| Domain | Holder | Why Not MA |
|--------|--------|------------|
| Constitutional interpretation | CIC | CIC is independent — MA cannot self-interpret |
| Operational election governance | GovernanceAuthority | Operational — not sovereign |
| Voter enrollment execution | EnrollmentAuthority | Operational — CriteriaAuthority appointment chain |
| Audit execution | AuditAuthority | Operational — MA-appointed but not MA-executed |
| Challenge adjudication | CAB | Adjudicative — MA-appointed but adjudicates independently |
| AC-31 Tier 3 verification | Existing authorities | Distributed (38B-02) |
| GovernanceState maintenance | GovernanceAuthority | Operational |
| Vote certification decisions | CertificationAuthority | Terminal evaluation — MA designates the org, not individual decisions |
| Individual appointment of EnrollmentAuthority | CriteriaAuthority | Functional alignment distribution (38B-04) |

### J.5 MA Concentration Risk Assessment

**Primary risk — TM-07 (MA Capture):** If MA is captured by a single actor or coalition, that actor gains access to all 15 MA constitutional functions. This includes the ability to appoint (or force removal of) all six authority bodies, ratify EC amendments to consolidate power, override terminal appeals through procedural mechanism, and control the interpretive body through succession appointment. Capture of MA at the close of 38B-04 is comprehensive constitutional governance capture.

**Secondary risk — MA Deadlock:** MA is not a standing body. Governance functions requiring MA convening are blocked when MA cannot achieve quorum or reach decision threshold. With six appointment authorities now requiring MA action for vacancies, MA unavailability creates appointment blockage across the constitutional architecture simultaneously. This is OQ-38B04-04.

**Existing mitigations:** MA decisions are challengeable before CAB. MA acts within EC bounds interpreted by CIC. MA is a membership body — capturing MA requires capturing the membership itself, which has diffuse distribution. These mitigations exist but are bounded; none eliminates the systemic risk at scale.

**Carried to 38B synthesis:** The question whether MA concentration constitutes an unacceptable systemic single point of governance failure must be addressed before 38B concludes.

---

## Part K — Four Independence Types Mapped to Each Authority

*Required by architectural review framework: "Evaluate appointment independence separately from authority independence."*

| Authority | Operational (ADR-2) | Interpretive (38B-01) | Appointment (38B-04) | Governance (ADR-2) |
|-----------|--------------------|-----------------------|----------------------|--------------------|
| **EnrollmentAuthority** | Option B committee | N/A (operational) | CriteriaAuthority; EA not subject to CA's operational authority; no circular dependency | Option B internal governance |
| **CriteriaAuthority** | Option B committee | N/A (operational) | Membership Assembly; MA is not subject to CA mandate | Option B internal governance |
| **AuditAuthority** | Option D hybrid | N/A (operational) | Membership Assembly; MA is external to election operations | Option D hybrid governance |
| **GovernanceAuthority** | Option B committee | N/A (operational) | Membership Assembly; MA is governance sovereign, not GA | Option B internal governance |
| **CertificationAuthority** | Option C external | N/A (operational) | Membership Assembly designates external org; MA is not subject to CA terminal evaluation | External org internal governance |
| **CAB** | Option B or C | N/A (adjudicative) | Membership Assembly; structural tension noted (MA appoints adjudicator of MA decisions) — OBS-38B04-01 scope | Option B/C internal governance |
| **CIC** | Option B | Yes (38B-01) | Membership Assembly; interpretive dependence chain analyzed in Part G; EC recusal safeguard required | Option B internal governance |
| **AC-31 Tier 3** | Distributed | N/A | Existing authority aggregates | Existing |

**Finding from this mapping:** All four independence types are satisfied or explicitly bounded for every authority. No authority lacks specification across all four types. The two structural tensions (MA → CAB composition, MA → CIC interpretive dependence) are documented, analyzed, and safeguarded — not resolved by architectural assertion.

---

## Part L — Concentration Map: Before and After 38B-04

*Required by architectural review framework: "Produce an updated concentration map."*

### L.1 Concentration Map Before 38B-04 (After 38B-03)

```
CONSTITUTIONAL CONCENTRATION — BEFORE 38B-04:

MA  ── EC ratification
     ── ADR ratification
     ── Terminal appeal (ADR-5 R-6)
     ── Source-of-source (OBS-ADR7-SS1)
     ── CIC establishment ratification (38B-01)
     ── CIC dissolution approval (38B-01)
     ── AC-31 Tier 2 designation (38B-02)
     ── Background phase dispute (38B-03)

MA FUNCTION COUNT: 8

APPOINTMENT AUTHORITY:
     ALL 7 operational authority aggregates: UNSPECIFIED
     AC-31 Tier 3: distributed (38B-02, existing authorities)

APPOINTMENT GAP: OPEN across all 7 operational authorities
```

### L.2 Concentration Map After 38B-04

```
CONSTITUTIONAL CONCENTRATION — AFTER 38B-04:

MA  ── EC ratification
     ── ADR ratification
     ── Terminal appeal (ADR-5 R-6)
     ── Source-of-source (OBS-ADR7-SS1)
     ── CIC establishment ratification (38B-01)
     ── CIC dissolution approval (38B-01)
     ── AC-31 Tier 2 designation (38B-02)
     ── Background phase dispute (38B-03)
     ── CriteriaAuthority appointment [NEW]
     ── AuditAuthority appointment [NEW]
     ── GovernanceAuthority appointment [NEW]
     ── CertificationAuthority designation [NEW]
     ── CAB appointment [NEW]
     ── CIC appointment [NEW]

MA FUNCTION COUNT: 14

CriteriaAuthority (MA-appointed)
     ── EnrollmentAuthority appointment [NEW]

APPOINTMENT AUTHORITY:
     6 of 7 operational authorities: appointed by MA
     1 of 7 operational authorities: appointed by CriteriaAuthority (MA-appointed)
     AC-31 Tier 3: distributed (38B-02, existing authorities)

APPOINTMENT GAP: CLOSED
```

### L.3 Concentration Delta

| Category | Before 38B-04 | After 38B-04 | Delta |
|----------|--------------|-------------|-------|
| MA functions | 8 | 14 | +6 (+75%) |
| Appointment specifications closed | 0/7 | 7/7 | +7 |
| Authorities with full independence specification | 0 | 8 | +8 |
| MA-appointed authority aggregates | 0 | 6 | +6 |
| Non-MA-appointed (distributed) | 0 | 1 (EA via CA) | +1 |
| Unspecified appointment authorities | 7 | 0 | -7 |

**Assessment of the delta:**

38B-04 closes all appointment gaps. It does so by concentrating six appointment functions in MA — constitutionally appropriate for the highest-legitimacy functions. One appointment (EnrollmentAuthority) is distributed to CriteriaAuthority, reducing MA's appointment count from a hypothetical seven to six.

The tradeoff is explicit: the appointment gap was real and constitutionally dangerous (TM-37, TM-38, TM-39 all rated FAIL). Closing it cost +6 MA constitutional functions. The gap cost was diffuse systemic vulnerability; the closure cost is concentrated sovereign power. Whether this tradeoff is acceptable is the central question for 38B synthesis — not for this round.

---

## Part M — Gap 6 Status Assessment

**Gap 6 is PARTIALLY SPECIFIED.**

**What is specified:**
- Appointment bodies identified for all eight constitutional authorities (Part E.2)
- EnrollmentAuthority appointment evaluated across alternatives (Part E.3)
- Appointment process requirements: nomination, vetting, confirmation, term, removal (Part E.4)
- Independence verification pathway: CIC interprets, CAB adjudicates (Part E.5)
- Structural constraint on appointment relationships: 38B04-INV-01 (revised)
- CIC interpretive dependence chain: analyzed and safeguard specified (Part G)
- MA concentration accumulated: named as primary finding (Part H, Parts J, L)

**What remains open:**
- OQ-38B04-01: MA appointment concentration acceptability (38B synthesis required)
- OQ-38B04-02: EC numerical constraint supplement (design question for EC)
- OQ-38B04-03: CertificationAuthority external independence verification across term (EC implementation)
- OQ-38B04-04 (NEW): Emergency appointment protocol — no specification yet

**Final resolution requires:**
- 38B-05 (Gap 3 — EC Amendment Governance): amendment governance may affect appointment provisions; staggered terms and maximum term limits are EC-specified, so they depend on amendment governance being properly specified
- 38B synthesis: MA concentration assessment across all 38B rounds

---

## Part N — Open Questions

**OQ-38B04-01 (Primary):** Does MA appointment of six authorities (CriteriaAuthority, AuditAuthority, GovernanceAuthority, CertificationAuthority, CAB, CIC) create an unacceptable concentration of appointment power in the constitutional sovereign? This is the cumulative MA dependency question carried forward from OBS-38B02-01.

**OQ-38B04-04 (Primary — Elevated by ARB, R2):** Emergency appointment protocol: If MA cannot convene (quorum failure, emergency suspension, dispute) and one or more authority vacancies exist, what is the provisional appointment authority? MA now controls six of seven appointment functions. MA unavailability therefore blocks appointment governance across nearly the entire constitutional authority structure simultaneously. This is constitutionally analogous to the G.4 deadlock-breaking mechanism specified in 38B-03 — temporal governance has a provisional recovery mechanism; appointment governance does not yet have an equivalent. Elevated to co-primary with OQ-38B04-01 because MA controls almost all appointment authority; the question is no longer a secondary operational concern but a primary constitutional vulnerability.

**OQ-38B04-02:** Should 38B04-INV-01 (structural constraint) be supplemented with an EC-specified function-count limit per appointment body? If so, what is the constitutional justification for any exception granted to MA?

**OQ-38B04-03:** How are CertificationAuthority (Option C External) independence credentials verified constitutionally across the authority's term? The external organization's own governance structure is outside the architecture's direct control — how is its ongoing independence monitored and constitutionally confirmed?

### Synthesis Carry-Forwards

**SCF-38B04-01 — CAB Appointment Tension (R3):** MA appoints CAB. CAB adjudicates MA decisions. This is one of the last remaining structural concentration tensions in the constitutional governance model. It is constitutionally analogous to judicial appointment by political bodies — not self-adjudication, but not full appointment independence from the adjudicated body either. The tension is not invalid; it is how constitutional systems with democratic appointment norms function. But it must receive explicit treatment in 38B synthesis. The question is not whether the arrangement is constitutionally permissible (it is) but whether the 38B architecture as a whole creates sufficient adjudicative independence to resist the appointment chain's long-term influence. Carried forward: 38B synthesis.

**SCF-38B04-02 — AA-01 Dependency Scale:** The constitutional governance model constructed in 38B-01 through 38B-04 is entirely contingent on MA legitimacy, which is itself contingent on AA-01 (OBS-38B04-04). 38B synthesis must produce an honest characterization of what the constitutional governance model achieves and what it assumes — specifically that all fourteen MA constitutional functions are contingent on a pre-constitutional assumption not yet grounded within the architecture. Carried forward: 38B synthesis and 38B-05 (Gap 3).

---

## ARB Self-Review — Six Required Evaluations

*This section constitutes the ARB review framework evaluation per the architect's specification. The six evaluations are conducted by the document author before ARB submission to confirm completeness and self-consistency.*

### Evaluation 1 — Re-evaluate 38B04-INV-01

**The candidate invariant** (no body may appoint more than three authorities) was structurally inadequate. It addressed the number of appointments rather than the constitutional concern — the relationship between appointer and appointee.

**The revised invariant** (structural constraint: no self-appointment, no circular appointment, no downward capture, constitutional independence of appointer) is:
- Satisfied by the selected model across all four structural constraints
- Coherent with the constitutional role of MA as sovereign (MA is not subject to the operational mandates of any authority it appoints)
- Stronger than a numerical limit because it addresses the underlying constitutional concern
- Self-consistent: the selected model violates none of its own invariant's four constraints

**Evaluation 1 verdict:** 38B04-INV-01 self-contradiction resolved. Revised invariant is constitutionally coherent and satisfied by the selected model. No residual self-contradiction.

### Evaluation 2 — Full MA Concentration Inventory

**Conducted in Part J.** MA holds 14 constitutional functions at close of 38B-04 (counted distinctly across source documents; 15 by dimensional analysis when CIC functions are counted across 38B-01 and 38B-04 separately).

**The inventory is complete.** Each function traced to its source document. Each has an individual constitutional justification. The collective concentration is significant and elevated as the primary architectural finding of 38B-04 in Part H.

**Evaluation 2 verdict:** MA concentration inventory complete. Cross-document traceability established. Concentration is real, individually justified, and collectively significant. 38B synthesis assessment required per OBS-38B04-02.

### Evaluation 3 — Four Independence Types Distinguished

**Conducted in Parts C and K.** Four independence types defined (operational, interpretive, appointment, governance) with the constitutional concern each type addresses. Mapped to every authority aggregate in Part K (Independence Type Interaction Matrix).

**Key findings from the mapping:** All four independence types are satisfied or explicitly bounded for every authority. Appointment independence complements but does not replace ADR-2 operational independence. The two structural tensions (MA → CAB; MA → CIC) are documented and safeguarded.

**Evaluation 3 verdict:** Independence types distinguished and systematically mapped. Appointment independence is architecturally addressed as a distinct constitutional property from authority independence.

### Evaluation 4 — CIC Appointment Dependency Chain

**Conducted in Part G.** Chain: MA appoints CIC → CIC interprets MA appointment rules → CIC interprets provisions governing its own appointment (candidate self-reference when CIC appointment is challenged).

**Assessment:** Constitutionally analogous to supreme court interpretation of the appointments clause. Not self-referential invalidity. Constitutionally acceptable provided: (a) CIC appointment challenges route through CAB adjudication, not CIC self-adjudication; (b) CIC members recuse from deliberations about their own appointment challenges; (c) EC specifies recusal conditions explicitly.

**Required EC safeguard:** Specified in Part G. This is a required EC action — the model is constitutionally incomplete without it.

**Evaluation 4 verdict:** CIC dependency chain is constitutionally manageable. Safeguard specified. Required EC action identified. Not a blocking architectural defect; monitoring observation with concrete EC requirement.

### Evaluation 5 — Reassess Gap 6 Status

**Conducted in Part M.** Gap 6 is PARTIALLY SPECIFIED. Appointment governance model is complete for all eight constitutional authorities. Process requirements are specified. Independence verification pathway is specified. OQs 38B04-01 through 38B04-04 remain open. OQ-38B04-04 (emergency appointment protocol) is newly identified in this round.

**Evaluation 5 verdict:** Gap 6 honestly characterized as partially specified. No overclaiming. Final resolution requires 38B synthesis and 38B-05 (EC Amendment Governance).

### Evaluation 6 — Updated Concentration Map

**Conducted in Part L.** Before 38B-04: MA holds 8 functions; appointment governance unspecified for all seven operational authorities. After 38B-04: MA holds 14 functions; appointment governance specified for all eight authorities (seven operational + AC-31 Tier 3 via existing).

**The tradeoff:** Closing the appointment gap (+7 specification closures) cost +6 MA constitutional functions. Gap closure cost is real. Appointment gap cost was also real — all three dominant capture threats (TM-37, TM-38, TM-39) rated FAIL under the gap condition.

**Evaluation 6 verdict:** Concentration map complete. Delta is explicit and quantified. Tradeoff is not hidden. 38B synthesis must evaluate whether the closure cost is acceptable relative to the gap cost.

---

## ARB Decision Block

**[APPROVED WITH MINOR REVISIONS APPLIED]**

### ARB Decision Record

**ARB Review:** 2026-06-18
**Verdict:** APPROVED WITH MINOR REVISIONS

**Revisions required and applied:**
- **R1:** OBS-38B04-04 added — AA-01 dependency accumulation; every additional MA constitutional function increases the architecture's dependence on a pre-constitutional assumption that is unresolved and not within 38B's scope to resolve
- **R2:** OQ-38B04-04 elevated to primary status alongside OQ-38B04-01 — MA now controls six of seven appointment functions; MA unavailability is a primary constitutional vulnerability, not a secondary operational concern
- **R3:** CAB appointment tension explicitly carried forward to 38B synthesis as SCF-38B04-01; the MA → CAB → MA adjudication arrangement is constitutionally analogous to democratic judicial appointment norms but is the last remaining concentration tension requiring synthesis treatment

**Verdict rationale:** All six pre-submission evaluations were completed correctly. The document demonstrates architectural discipline throughout: the invariant self-contradiction is resolved without weakening the structural concern; MA concentration is named as the primary architectural finding rather than a footnote; Gap 6 is honestly characterized as partially specified with four open questions named. The three minor revisions sharpen the forward-looking signals without requiring architectural restructuring or reconsidering the selected model.

**38B-04 APPROVED.**
**38B-05 AUTHORIZED: Gap 3 — Constitutional Amendment Governance Specification.**

---

### Appointment Model Decision

**Distributed Appointment with MA Designation for Highest-Legitimacy Authorities selected.**

- 38B04-INV-01 (structural constraint): satisfied by selected model
- EnrollmentAuthority appointed by CriteriaAuthority (functional alignment; distributes from MA; avoids GovernanceAuthority circular chain)
- Six operational/interpretive authorities appointed by MA (constitutional sovereign; each justified by constitutional function requiring sovereign grounding)
- AC-31 Tier 3: no new appointments (existing authority aggregates per 38B-02)
- All four independence types specified for each authority (Part C, Part K)

### Observations Produced

**OBS-38B04-01:** CIC interpretive dependence chain — constitutionally manageable; EC recusal safeguard required before model is constitutionally complete.

**OBS-38B04-02 (Primary Finding):** MA concentration accumulation — 14 constitutional functions at close of 38B-04; 250% increase in MA functions during the 38B specification cycle; TM-07 (MA Capture) consequence is now comprehensive constitutional governance capture. Requires 38B synthesis assessment.

**OBS-38B04-03:** Appointment independence ≠ authority independence — distinct constitutional properties; appointment independence is a precondition for sustained authority independence; both required for the constitutional architecture to resist TM-39 (Independence Illusion).

**OBS-38B04-04 (R1):** AA-01 dependency accumulation — every additional MA constitutional function increases dependence on pre-constitutional AA-01 resolution; at 14 functions AA-01 is the foundational premise of the entire constitutional governance model, not a background assumption. Pre-constitutional; not within 38B's scope to resolve. Carried to 38B synthesis and 38B-05.

### Gap 6 Status

**PARTIALLY SPECIFIED.** Appointment architecture designed and specified. Final resolution pending 38B synthesis MA concentration assessment and 38B-05 EC Amendment Governance.

### Open Questions Carried Forward

- OQ-38B04-01 (Primary): MA appointment concentration acceptability (38B synthesis)
- OQ-38B04-04 (Primary — Elevated R2): Emergency appointment protocol — MA unavailability blocks six of seven appointment functions; unresolved; analogous gap to G.4 deadlock-breaking; requires specification
- OQ-38B04-02: EC numerical supplement to structural constraint (EC design)
- OQ-38B04-03: CertificationAuthority external independence monitoring (EC implementation)
- SCF-38B04-01: CAB appointment tension → 38B synthesis (R3)
- SCF-38B04-02: AA-01 dependency scale → 38B synthesis + 38B-05

### Authorization Requested

**38B-05: Gap 3 — Constitutional Amendment Governance Specification** (conditional on ARB approval of this document)

---

*Round 38B-04 — Gap 6 Authority Appointment Process Specification — APPROVED WITH MINOR REVISIONS APPLIED*
*Research Program: NRNA DDD Trustworthiness*
*Submission: 2026-06-17 | ARB Review: 2026-06-18 | R1/R2/R3 Applied*
