# Round 37-02 — ADR-2: Independence Form per D43 Function

**Program:** NRNA DDD Trustworthiness Research Program  
**Round:** 37 — ADR Authoring  
**Document:** ADR-2 of 7  
**Status:** APPROVED — Required Revisions Applied  
**ARB Verdict:** APPROVED WITH REQUIRED REVISIONS — 2026-06-15  
**Governing Question:** For each D43 authority function, what form of constitutional independence is required?

**Predecessors:**
- ADR-1 — Authority Vocabulary and Authority Source Model — APPROVED
- 36E-04 — Architecture Option Evaluation (CPR-01 Options A/B/C/D) — APPROVED
- 36E-05 — Architecture Synthesis (Architecture Families A/B/C) — APPROVED

**Binding Inputs from ADR-1:**
- Alternative B selected: authority relationships as dedicated aggregates; L-1/L-5 as value objects
- ElectionConstitution as shared L-1 source (with reversal clause: precision failure reopens per-function L-1)
- Five candidate authority relationship names (not frozen inventory): EnrollmentAuthority, CriteriaAuthority, AuditAuthority, GovernanceAuthority, CertificationAuthority
- Constitutional event taxonomy is provisional; ADR-2 finalizes independence-lifecycle events; ADR-5 finalizes challenge events
- ADR-2 must verify relevant ElectionConstitution provision exists and is precise for each function before L1Source is finalized

**Scope:** Conceptual Architecture only. No bounded contexts. No services. No microservices. No APIs. No deployment topology. No organizational charts. Independence form = constitutional requirement, not implementation prescription.

---

## Part A — Problem Statement

### A.1 The Core Question

ADR-1 established that authority relationships are represented as dedicated authority aggregates. It did not determine the independence properties those aggregates must carry.

ADR-2 answers: for each D43 authority function, who must be independent, from whom, to what degree, and why?

These questions are constitutional, not organizational. The answer to "what degree of independence is required" does not determine what org-chart boxes must exist, what services must be created, or what deployment boundaries must be drawn. It determines what the L2Holder value object must carry — what kind of constitutional mandate must be encoded in each authority aggregate.

### A.2 The Governing Warning

The most important discipline for this ADR:

```
Constitutional requirement ≠ organizational prescription.
```

ADR-2 may conclude that AUDIT independence requires a constitutional mandate that is "external to the election system's organizational control." That is a constitutional constraint on what the L2Holder value object must represent. It is not a statement that a separate company must be established, or that a new bounded context must be created, or that a separate deployment unit must exist.

Those implementation consequences belong to design rounds (Round 38+). ADR-2's selections constrain what the domain model must be able to express — not how any specific organization satisfies that expression.

### A.3 What ADR-2 Produces

For each of the five D43 functions:

1. An independence form selection (one of Options A/B/C/D from CPR-01)
2. The constitutional rationale for the selection
3. The L2Holder specification that the authority aggregate must carry
4. Verification that the required ElectionConstitution provision exists or must be specified
5. Rejected alternatives with rationale

Across all five functions:

6. Cross-function consistency analysis
7. Concentration risk assessment (AC-20)
8. Finalized independence-lifecycle constitutional event taxonomy
9. Consequences for ADR-3 through ADR-7

### A.4 Governing Constraints

From 36E-01:

| ID | Constraint | D43 Relevance |
|---|---|---|
| AC-02 | No authority function may be architecturally self-verifying | All functions — no function validates its own mandate |
| AC-03 | L-1/L-5 as first-class architectural elements | All functions — L2Holder must be explicit |
| AC-04 | Authority relationships distinct from function execution | All functions — independence form separates mandate from operation |
| AC-05 | At least one constitutionally independent authority relationship per D43 function | All functions — the core constraint this ADR satisfies |
| AC-06 | Independence cannot be achieved by naming alone | All functions — independence must be structurally realized |
| AC-12 | Revocation must originate outside the revoked authority | All functions — revocation pathway is part of independence specification |
| AC-18/19/20 | Authority map separate from context map; trust concentration assessable | Structural — independence selections must be readable at authority-map level |
| AC-24 | Audit: external scope definition, external access pathway, IR-H structure | AUDIT specifically |
| AC-25 | Governance authorization and execution must be architecturally distinguishable | GOV-AUTH specifically |
| AC-27 | Certification must include external challenge pathway | CERT specifically |
| AC-28 | Certification designed with awareness of complete legitimacy gap and terminal risk | CERT specifically |

From CF-05-19: At least one constitutionally independent authority relationship per function. The minimum structural realization form is what this ADR determines.

From OBS-36D-02-1: Authority ≠ Context. Independence form selections do not imply bounded context boundaries.

### A.5 Options Under Evaluation

From CPR-01 (36E-04, Section 5):

- **Option A — Role Independence:** constitutional mandate assigned to a specific constitutional role within the same organizational governance structure
- **Option B — Committee Independence:** constitutional mandate assigned to a constitutionally designated committee distinct from the primary authority holder; committee operates within the same organizational framework
- **Option C — External Organization Independence:** constitutional mandate held by a constitutionally independent organization external to the election governance structure
- **Option D — Hybrid Independence:** different independence forms applied to different aspects of the same D43 function, or different D43 functions using different forms

---

## Part B — Constitutional Evidence Inherited by This ADR

### B.1 D43 Legitimacy Profiles (from 36D-04)

| D43 Function | L-1 | L-2 | L-3 | L-4 | L-5 | Critical Finding |
|---|---|---|---|---|---|---|
| ENROLL | UNDEFINED | UNDEFINED | UNDEFINED | UNDEFINED | UNDEFINED | Complete legitimacy gap |
| CRITERIA | CANDIDATE (membership ratification) | UNDEFINED | UNDEFINED | SELF-REF | UNDEFINED | L-4 SELF-REF: irremovability defect |
| AUDIT | Partial (Gap A-3 unresolved) | UNDEFINED | Implicit only | UNDEFINED | UNDEFINED | IR-H: independent of audited subject |
| GOV-AUTH | CANDIDATE | UNDEFINED | UNDEFINED | UNDEFINED | UNDEFINED | OBS-36D-01-2: authorization ≠ execution |
| CERT | CANDIDATE | UNDEFINED | UNDEFINED | UNDEFINED | UNDEFINED | Most complete gap; terminal risk; TC-4 amplification |

### B.2 Independence Strength Required by Legitimacy Gap Severity

The wider the legitimacy gap, the more constitutional risk accumulates in the gap, and the stronger the independence form required to close it:

- **Complete gap (ENROLL):** All five dimensions undefined. Any independence form that provides genuine constitutional standing (not nominal) is required. Option A's AC-06 risk (nominal independence) is most dangerous here.
- **SELF-REF defect (CRITERIA):** L-4 SELF-REF is structurally load-bearing — criteria authority controls its own revocation. Independence form must break the SELF-REF cycle structurally, not only nominally.
- **IR-H (AUDIT):** The audit function must be independent of the audited subject — the entire election system. This is the most demanding independence standard. IR-H goes beyond IR-A (independent of operator only) — it requires independence from the system being observed.
- **Authorization ≠ Execution (GOV-AUTH):** The constitutional requirement is structural separability of authorization from execution, not full organizational independence. The independence form must support this separability as a constitutional invariant.
- **Terminal risk (CERT):** Certification is the final constitutional act. A L-3 defect (no challenge pathway) at the terminal function means the entire election result is constitutionally unchallengeable. Independence form must support external challenge routing.

### B.3 ElectionConstitution Reversal Clause Inheritance (from ADR-1)

ADR-1 requires each subsequent ADR to verify: does the ElectionConstitution contain (or must it contain) a provision that precisely specifies the authority mandate for this function?

This ADR tracks ElectionConstitution provision requirements per function. If any provision cannot be made sufficiently precise (AC-30), the shared L-1 decision is reversed and per-function L-1 evaluation is reopened for that function.

---

## Part C — ENROLL Independence Assessment

### C.1 Current State

**Current coverage:** No constitutional authority structure. Enrollment decisions (who may vote) are made with no identified L-1 source, no identified L-2 holder, no challenge pathway, no revocation pathway, and no succession mechanism. From AC-21: any enrollment implementation without these five elements has no constitutional standing.

**Legitimacy gap:** Complete. The EnrollmentAuthority aggregate (ADR-1 candidate) is currently an empty constitutional placeholder.

**Concentration risks:** Maximum. When no authority holder is identified, enrollment decisions effectively concentrate in whoever operates the system — an implicit SELF-REF of the most basic kind.

### C.2 Constitutional Requirements for ENROLL Independence

| Constraint | ENROLL-Specific Implication |
|---|---|
| AC-05 | EnrollmentAuthority aggregate must carry an L2Holder that represents a constitutionally independent mandate holder |
| AC-06 | The independence of that L2Holder cannot rest on naming alone — "enrollment officer" within the operational team does not satisfy AC-06 if that officer is appointed and removed by the same body that runs the election |
| AC-12 | If the enrollment authority is found to be exercising unconstitutionally, revocation must originate outside the enrollment function itself — the enrollment officer cannot be the origin of their own removal |
| AC-21 | All five legitimacy dimensions must be represented in EnrollmentAuthority — independence form must be expressible across all five |

### C.3 Option Analysis — ENROLL

#### Option A (Role Independence)

**Description for ENROLL:** A constitutional role — "Enrollment Oversight Officer" or equivalent — is designated in the ElectionConstitution. This role has constitutional authority over enrollment decisions, distinct from the operational election administrator.

**Advantages:** Lowest organizational complexity. Compatible with small organizations. ElectionConstitution can designate the role without creating new constitutional bodies.

**Constitutional pressures introduced:**
- AC-06 risk: If the body that appoints the Enrollment Oversight Officer is the same body that runs the election, the officer's independence may be nominal. Removal and appointment are in the same hands.
- AC-12 pressure: Revocation pathway routes through whoever appoints the role — if that is the same governance body, revocation independence is partial at best.

**Assessment:** Option A introduces AC-06 risk at the most structurally exposed position — a function with a complete legitimacy gap. The complete gap means any independence theater (AR-06) produces a function that appears constitutionally grounded but has no structural independence to invoke when challenged.

#### Option B (Committee Independence)

**Description for ENROLL:** A constitutionally designated Enrollment Review Committee holds the enrollment authority mandate. The committee is constituted by ElectionConstitution (giving it L-1 grounding), holds a mandate distinct from the election administration function, and operates with a defined quorum and decision procedure.

**Advantages:**
- Structural independence beyond naming: a committee is constituted by the same constitutional instrument that defines enrollment criteria — it is not subordinate to the election administrator.
- AC-12: Revocation of committee membership routes through constitutional governance (e.g., membership vote, Board action), not through the election administrator.
- AC-06: A committee structure has structural separability from the election administration function — appointment of individual committee members may involve the same governance body, but the committee's constitutional mandate is defined in ElectionConstitution, not granted by the election administrator.

**Constitutional pressures introduced:**
- The committee itself must be constituted by a constitutionally grounded process. Who appoints committee members? This process must itself be specified in ElectionConstitution (otherwise the committee's L-1 is circular).
- Committee composition, quorum, and term limits are constitutional requirements, not operational details — they must appear in ElectionConstitution with AC-30 precision.

**Dependencies created:** ElectionConstitution must specify: (a) the enrollment committee mandate; (b) committee composition and appointment process; (c) quorum and decision procedure; (d) revocation pathway for committee members.

#### Option C (External Organization)

**Description for ENROLL:** A constitutionally independent external organization holds the enrollment authority mandate. This organization is not part of the election-administering entity.

**Advantages:** Strongest AC-06 and AC-12 satisfaction. Genuine organizational independence.

**Constitutional pressures introduced:**
- Highest operational complexity: inter-organizational agreement for enrollment decisions.
- New legitimacy dependencies: the external organization's own L-1/L-5 must be constitutionally grounded. What authorizes the external organization to hold enrollment authority for this election?
- Option C does not automatically avoid creating D43-like gaps in the external organization — it shifts them.

**Assessment:** Option C provides stronger independence than required for enrollment, at disproportionate operational cost. Enrollment independence is constitutionally required (complete gap) but enrollment decisions are organizationally internal — who may vote is determined by the organization's own constitutional criteria, not by an external body's constitutional standards.

#### Option D (Hybrid)

Not applicable for ENROLL as a single independent assessment — no sub-function separation within enrollment requires different independence forms. Option D would apply only if enrollment scope definition and enrollment execution required different independence levels, which is not established by the current evidence.

### C.4 Selection — ENROLL: **Option B (Committee Independence)**

**Rationale:**

Option A's AC-06 risk is unacceptable for a function with a complete legitimacy gap. When all five legitimacy dimensions are undefined, even a moderate AC-06 risk means the entire constitutional structure of enrollment rests on a single appointment decision that may be internally revocable. This is constitutionally insufficient.

Option C provides genuine independence but at operational cost disproportionate to the enrollment function's nature. Enrollment eligibility criteria are constitutional matters of the organization itself — they are defined within the organization's constitutional framework and applied by a body that is constitutionally grounded within that framework. An external organization introduces inter-organizational authority questions that the current evidence does not require.

Option B provides structural independence through committee constitution: the Enrollment Review Committee holds a mandate from ElectionConstitution, distinct from the election administrator, with revocation routing through constitutional governance rather than the administrator. This satisfies AC-05, reduces AC-06 risk, and provides a structurally realizable revocation pathway (AC-12).

**L2Holder specification:** EnrollmentAuthority.L2Holder must represent a constitutionally designated committee with: (a) a mandate enumerated in ElectionConstitution, (b) a defined constitutional appointment process independent of the election administration function, (c) a revocation pathway that routes through the organization's constitutional governance body.

**ElectionConstitution provision required (AC-30):**
- Enrollment committee mandate (scope: who qualifies for enrollment; authority: final enrollment decisions)
- Committee composition (minimum size, term limits, appointment process)
- Quorum and voting procedure for enrollment determinations
- Revocation pathway for committee members

If ElectionConstitution cannot be made sufficiently precise to define these provisions, the shared L-1 reversal clause from ADR-1 applies to ENROLL and per-function L-1 evaluation is reopened.

---

## Part D — CRITERIA Independence Assessment

### D.1 Current State

**Current coverage:** L-1 candidate (membership ratification mechanism identified but undefined); L-4 SELF-REF (critical defect). Four of five dimensions undefined.

**Critical defect — L-4 SELF-REF (from 36D-05, CF-05-04):** The criteria authority controls its own revocation. This is not a behavioral defect — it is a structural constitutional defect. The criteria authority cannot be constitutionally removed by any process that does not route through itself. AR-17: "TC3-NCQ-04 embedded in criteria governance."

**Concentration risk:** The SELF-REF in L-4 means that criteria authority, once in place, is constitutionally irremovable through any internal process. This is the most critical specific defect across all five D43 functions.

### D.2 Constitutional Requirements for CRITERIA Independence

| Constraint | CRITERIA-Specific Implication |
|---|---|
| AC-22 | Criteria authority must have an external revocation pathway that does not route through the criteria authority itself — this is the primary constraint SELF-REF violates |
| AC-23 | Membership ratification mechanism must be a first-class architectural concern — the L-1 candidate for CRITERIA requires structural representation |
| AC-06 | Independence must break the SELF-REF cycle structurally — renaming the criteria function does not break SELF-REF |
| AC-12 | Revocation of the criteria authority mandate must originate outside the criteria authority boundary |

### D.3 Option Analysis — CRITERIA

#### Option A (Role Independence)

**Description for CRITERIA:** A criteria oversight role is designated in ElectionConstitution, with authority to review and challenge criteria decisions.

**Constitutional pressures introduced:**
- AC-22: A role within the same organizational structure as the criteria-setting function does not automatically break SELF-REF. If the criteria-setting role and the criteria-oversight role are both appointed by the same governance body, and that governance body's composition is influenced by the criteria-setting function, SELF-REF persists through the appointment chain.
- The SELF-REF defect in CRITERIA is structural and recursive — it must be broken by a constitutional structure that is not reachable from within the criteria function.

**Assessment:** Option A is inadequate for CRITERIA because SELF-REF requires a structural break, not a naming break. AC-22 specifically requires that the revocation pathway "does not route through the criteria authority itself" — a role within the same governance sphere does not guarantee this without careful constitutional engineering that effectively amounts to Option B.

#### Option B (Committee Independence)

**Description for CRITERIA:** A Criteria Review Committee is constituted by ElectionConstitution with: (a) a mandate to review and challenge criteria decisions, (b) authority to initiate criteria revision or revocation, (c) a constitutional appointment process that is independent of the criteria-setting function.

**Key constitutional design for SELF-REF resolution:** The Criteria Review Committee must have a constitutional pathway to revoke or revise the criteria authority that does not route through the criteria-setting function. This means: the committee's revocation mandate is defined in ElectionConstitution; the criteria-setting function cannot prevent or delay a committee-initiated revocation; the committee's appointment process is not controlled by the criteria-setting function.

**Advantages:**
- AC-22: Committee-initiated revocation constitutionally originates outside the criteria authority — SELF-REF broken.
- AC-23: The membership ratification pattern (existing L-1 candidate) maps naturally onto committee constitution — the committee's mandate can be ratified through the same membership ratification process that ratifies the criteria themselves.
- AC-06: Structural independence — the committee has a distinct constitutional mandate, not merely a different label.

**Constitutional pressures introduced:**
- The committee's appointment independence from the criteria function is the load-bearing invariant. This requires ElectionConstitution to explicitly specify that the criteria committee is NOT appointed by or through the criteria-setting function.
- If criteria-setting involves elected or appointed leadership who also influence committee composition, SELF-REF may persist through an indirect pathway. ElectionConstitution must close this pathway explicitly.

#### Option C (External Organization)

**Description for CRITERIA:** An external body holds the authority to define, review, and revoke eligibility criteria.

**Advantages:** Breaks SELF-REF completely — external organization cannot be influenced by the criteria-setting function by definition of organizational independence.

**Constitutional pressures introduced:**
- Eligibility criteria are inherently internal to the organization's own constitutional framework. Having an external body define who may be a member or voter in an organization's election raises legitimacy questions in the other direction — what gives an external body the constitutional authority to define the organization's own membership criteria?
- This creates a new L-1 question: the external criteria authority's mandate must itself be constitutionally grounded in the organization's own constitutional framework — otherwise the organization has delegated criteria-setting without constitutional basis.

**Assessment:** Option C is constitutionally more complex for CRITERIA than for CERT, because criteria are an internal constitutional matter. An external body with CRITERIA authority must itself be constitutionally authorized by the organization — effectively an inter-organizational constitutional delegation. This is achievable but adds constitutional depth not required for SELF-REF resolution.

#### Option D (Hybrid)

**Description for CRITERIA:** Criteria-setting remains with an internal function (constitutionally designated); criteria revocation authority is held by a constitutionally independent body (committee or external). The two functions are separated: one holds L-2 for criteria setting, a different one holds L-4 revocation authority.

**Constitutional significance:** This directly addresses the SELF-REF defect with surgical precision — the SELF-REF is in L-4, not L-2. Separating L-4 revocation from L-2 setting breaks the SELF-REF without requiring that the criteria-setting function itself be held by an external or committee body.

**Assessment:** Option D for CRITERIA is conceptually sound but architecturally it is not a different independence form — it is a specification of how Option B is realized. The committee (or external body) holds L-4 revocation authority; the criteria-setting function holds L-2. This is the natural constitutional architecture within Option B (committee holds revocation mandate) rather than a distinct fourth category.

### D.4 Selection — CRITERIA: **Option B (Committee Independence)**

**Rationale:**

The primary constitutional defect in CRITERIA is SELF-REF in L-4. The independence selection must break this structurally. Option A fails because role independence within the same governance sphere does not guarantee that revocation cannot route through the criteria function. Option C introduces inter-organizational complexity not required by the constitutional evidence — the SELF-REF defect is resolvable within the organization's constitutional framework through a properly constituted committee.

Option B (Committee) provides the required structural break: the Criteria Review Committee's revocation mandate is constituted by ElectionConstitution independently of the criteria-setting function. The committee holds L-4 revocation authority; the criteria-setting function holds L-2. This is the minimum structural realization that breaks SELF-REF per AC-22.

The Option D analysis above demonstrates that the "hybrid" for CRITERIA is the natural realization of Option B, not a separate architecture — the key constitutional insight is that CriteriaAuthority aggregate needs two constitutionally independent mandate specifications:

```
CriteriaAuthority (aggregate)
    L1Source: ConstitutionalRef → ElectionConstitution (criteria mandate provision)
    L2Holder: AuthorityHolder → CriteriaSetting function (who sets criteria)
    L3Challenge: ChallengePathway → CriteriaReviewCommittee (who receives challenges)
    L4Revocation: RevocationMechanism → CriteriaReviewCommittee (who can revoke — SELF-REF broken)
    L5Succession: SuccessionPlan → ConstitutionallyDefined
```

The Criteria Review Committee appears in L3 and L4 — not as a separate D43 instance, but as the independent mandate holder for the challenge and revocation dimensions of the same CriteriaAuthority aggregate. This is the constitutional architecture that breaks SELF-REF.

**L2Holder specification:** CriteriaAuthority.L2Holder represents the criteria-setting mandate holder. Separately, L4Revocation must represent a constitutionally designated body (committee) whose mandate is independent of the L2Holder. The committee's constitutional independence from the L2Holder function is the load-bearing architectural invariant.

**ElectionConstitution provision required (AC-30):**
- Criteria-setting mandate: who holds it, by what process they are constituted
- Criteria Review Committee: mandate scope, appointment process explicitly independent of criteria-setting function, quorum, decision procedure
- Revocation pathway: criteria authority may be revoked by the Committee through a specified process that does not require consent of the criteria-setting function
- Membership ratification: the constitutional ratification process for criteria (existing L-1 candidate, AC-23) must be specified with precision

---

## Part E — AUDIT Independence Assessment

### E.1 Current State

**Current coverage:** L-1 partial (Gap A-3 unresolved — expected evidence set undefined); L-3 implicit (depends on Gap A-3 resolution). All other dimensions undefined.

**Critical finding — IR-H (OBS-36D-03-1):** The audit function must be independent of the audited subject — the entire election system. Not only of the operator (IR-A), but of the system being audited. This is a more demanding standard than any other D43 function faces.

**Gap A-3:** The expected evidence set — what a complete audit must find — is undefined. The audit function cannot constitutionally verify completeness without an externally defined expected evidence set (AC-14, AC-17). The independence architecture must accommodate Gap A-3 closure.

**Structural split (from ET-03 / CPR-02, 36E-04 Section 4):** Audit has two constitutionally distinct sub-functions:
1. **Audit scope definition:** determining what must be present in a complete audit (AC-14 — must originate outside the election system)
2. **Audit execution:** verifying that the actual evidence satisfies the defined scope (IR-H — must be independent of the audited subject)

### E.2 Constitutional Requirements for AUDIT Independence

| Constraint | AUDIT-Specific Implication |
|---|---|
| AC-14 | Audit scope definition must originate outside the election system's architectural boundary |
| AC-16 | Audit scope authority cannot share an architectural authority boundary with election execution authority |
| AC-17 | Mechanism for externally defining the expected evidence set is constitutionally required |
| AC-24 | Audit aggregate must include: external scope definition mechanism, external access pathway, IR-H independence structure |
| IR-H | Audit authority must be independent of the audited subject — the full election system |

### E.3 Option Analysis — AUDIT

#### Option A (Role Independence)

**Description for AUDIT:** An audit oversight role is designated constitutionally within the same organizational structure as the election administration.

**Constitutional pressures introduced:**
- IR-H: If the audit oversight role is held within the organization that administers the election, the role is NOT independent of the audited subject. IR-H requires independence of the audited subject — the election system itself — not only of the operator. A role within the administering organization does not satisfy IR-H even if the role is formally independent of the operators.
- AC-14: Audit scope definition by a role within the administering organization is scope definition inside the election system's organizational boundary. AC-14 requires this to originate outside.

**Assessment:** Option A fails IR-H structurally. The organization administering the election is part of the audited subject. A role within that organization cannot hold audit authority that satisfies IR-H regardless of nominal independence designation.

#### Option B (Committee Independence)

**Description for AUDIT:** An audit committee is constituted within the same organizational framework but with constitutional independence from election administration functions.

**Constitutional pressures introduced:**
- IR-H boundary question: If the organization administering the election constitutes the audit committee, the committee may be independent of the election administrators (IR-A satisfied) but not of the election system as a whole (IR-H at risk). The committee is part of the organization that is the audited subject.
- AC-14: Whether scope definition by an internal committee satisfies AC-14 depends on whether the committee's mandate is constitutionally designed to be external to the election system's operational boundary. An internal committee with a constitutionally defined scope mandate from ElectionConstitution may be the boundary case: the scope mandate comes from the constitutional document (external to operations), the committee applies it (internal to organization but external to election operations).

**Assessment:** Option B is borderline for AUDIT. It satisfies IR-A (independent of operators) but does not fully satisfy IR-H (independent of audited subject) if the organization itself is what is being audited. For elections where the organizational leadership is not what is being audited — where the election itself is the subject — Option B becomes more viable.

#### Option C (External Organization)

**Description for AUDIT:** An external organization holds audit authority. The external organization is constitutionally independent of the election-administering organization.

**Constitutional pressures introduced:**
- Highest constitutional strength for IR-H: an organization that is not the election system is definitionally independent of the audited subject.
- New legitimacy dependency: the external audit organization's mandate must be constitutionally grounded. By what constitutional instrument does an external organization have authority to audit this election? This must appear in ElectionConstitution as an explicit authorization.
- Access pathway (AC-15): external organization needs constitutional access to election evidence. This access must be constitutionally granted.

**Assessment:** Option C fully satisfies IR-H. The question is whether full external organization independence is required for both sub-functions (scope definition AND execution), or whether different independence levels are constitutionally sufficient.

#### Option D (Hybrid Independence)

**Description for AUDIT:** Audit scope definition and audit execution receive different independence treatments, corresponding to their different constitutional requirements:

- **Scope definition authority:** ElectionConstitution itself defines what must be audited (the "expected evidence set"). This is the Option C-quality independence for scope: the constitutional document is definitionally external to the operational election system. From CPR-02 Option C analysis: this leverages the existing ElectionConstitution reference and provides AC-14 satisfaction through constitutional rather than organizational separation.
- **Audit execution authority:** A constitutionally designated audit committee or external body verifies that actual evidence satisfies the constitutional scope definition. The execution authority need not define scope — it verifies against an already-defined scope.

**Constitutional significance:** This hybrid directly addresses the structural split established by ET-03. Scope definition is constitutional (ElectionConstitution), which satisfies AC-14 at the level of the constitutional document rather than requiring a separate organizational entity for scope. Execution requires genuine IR-H independence from the audited subject — which may be provided by a committee with strong structural independence or by an external organization.

**Advantages:**
- AC-14 satisfied: scope originates in ElectionConstitution (outside operational election system)
- AC-16 satisfied: scope authority (constitutional document) ≠ execution authority (independent body)
- AC-17 satisfied: ElectionConstitution provides the externally defined expected evidence set
- IR-H for execution: audit execution body is constitutionally independent of election administration
- AC-20: scope and execution independence are separately readable in authority map

**Critical constraint for the execution portion:** The execution body must satisfy IR-H — independent of the audited subject. If the organization itself is being audited (not just the election administrators), the execution body must be external to the organization, not merely internal but independent.

**ADR-2 Resolution for AUDIT execution:** ADR-2 selects that audit execution authority must have IR-H-grade independence, which for organizational elections means: the execution body must be constitutionally independent of the organization's election governance apparatus. This is achievable through Option B (committee) if the committee's mandate is constitutionally guaranteed to be independent of the election-governing functions — i.e., the committee is not appointed by, subordinate to, or removable by the election governing body. If this constitutional guarantee cannot be established, Option C (external organization) for execution is required.

ADR-2 therefore selects **Option D** for AUDIT with the following realization profile:
- Scope definition: ElectionConstitution as constitutional scope mandate (Option C-quality independence)
- Execution: constitutionally independent committee with IR-H-grade guarantee, with conditional escalation to external organization if the IR-H guarantee cannot be maintained within the organizational structure

### E.4 Selection — AUDIT: **Option D (Hybrid — constitutional scope + independent execution)**

**Rationale:**

Option A fails IR-H structurally. Option B alone is borderline — it addresses IR-A but IR-H satisfaction depends on organizational design specifics. Option C for both sub-functions adds organizational complexity beyond what the constitutional evidence requires for scope definition.

Option D matches the constitutional architecture revealed by ET-03: audit scope is a constitutional matter (expressed in ElectionConstitution, satisfying AC-14 through constitutional rather than organizational separation) and audit execution is an IR-H independence matter (requiring structural independence from the election system, satisfied by a committee with constitutional IR-H guarantees or by an external organization if such guarantees cannot be structured internally).

**AuditAuthority Candidate Refinement — REQUIRES ADR-4 CONFIRMATION:**

> **CANDIDATE REFINEMENT ONLY. This split is NOT established by ADR-2. ADR-4 (Audit Scope Authority Structure) must determine whether this refinement is constitutionally warranted. The analysis below follows from Option D's constitutional logic and is a hypothesis for ADR-4 evaluation — not a design decision.**

ADR-1 identified AuditAuthority as one candidate aggregate. Option D for AUDIT implies a possible candidate refinement: AuditAuthority may require two distinct authority aggregates to constitutionally represent scope independence and execution independence separately. This is a candidate hypothesis, not a confirmed finding. ET-03 was authorized for evaluation in 36E-03; the threshold for splitting was not evaluated and is not established here.

```
AuditScopeAuthority (aggregate — CANDIDATE ONLY — ADR-4 determines)
    L1Source: ConstitutionalRef → ElectionConstitution (audit scope mandate provision)
    L2Holder: AuthorityHolder → Constitutional document itself (scope is constitutional, not operational)
    L3Challenge: ChallengePathway → governance challenge process
    L4Revocation: RevocationMechanism → constitutional amendment process
    L5Succession: SuccessionPlan → constitutional revision cycle

AuditExecutionAuthority (aggregate — CANDIDATE ONLY — ADR-4 determines)
    L1Source: ConstitutionalRef → ElectionConstitution (audit execution mandate provision)
    L2Holder: AuthorityHolder → Constitutionally independent audit body (committee or external)
    L3Challenge: ChallengePathway → per ADR-5 (challenge architecture)
    L4Revocation: RevocationMechanism → constitutional governance (not election administration)
    L5Succession: SuccessionPlan → constitutionally defined
```

**ADR-1 inventory discipline note:** This candidate refinement does not create a new D43 instance. It hypothesizes a refinement of the existing D43-AUDIT candidate from one aggregate to two aggregate candidates. The D43 boundary (one authority function) remains unchanged. **ADR-4 (Audit Scope Authority Structure) will determine whether one or two aggregates are the constitutionally warranted design — this is not established by ADR-2.**

**ElectionConstitution provision required (AC-30):**
- Audit scope mandate: what constitutes a complete audit of this election (expected evidence set, AC-17)
- Audit execution mandate: who holds audit execution authority; appointment process independent of election governance
- IR-H guarantee: explicit constitutional specification that the audit body cannot be appointed or removed by election governance functions
- Access pathway: constitutional right of audit execution body to access election evidence (AC-15)

---

## Part F — GOV-AUTH Independence Assessment

### F.1 Current State

**Current coverage:** L-1 candidate; L-2 undefined; L-3 undefined. Three dimensions defined, two undefined.

**Critical finding — OBS-36D-01-2:** Authorization of a governance transition ≠ Execution of a governance transition. These are constitutionally distinct acts and must be architecturally distinguishable (AC-25).

**ADH-1 (unresolved):** The specific identity of the governance authorization authority holder has not been established. L-2 for GOV-AUTH is the most practically uncertain of the five functions.

### F.2 Constitutional Requirements for GOV-AUTH Independence

| Constraint | GOV-AUTH-Specific Implication |
|---|---|
| AC-25 | Governance authorization authority and execution authority must be architecturally distinguishable — not merely functionally distinct but constitutionally separable |
| AC-26 | Governance authorization authority holder (L-2) must be identified as a first-class architectural concern |
| OBS-36D-01-2 | Authorization ≠ execution; the constitutionally required separation is the architectural invariant |

**The core constitutional requirement:** AC-25 does not require that the authorization function be external to the organization or even structurally independent at the organizational level. It requires that authorization and execution be **architecturally distinguishable** — that in the domain model, these two concerns are represented as distinct authority structures such that one cannot be collapsed into the other without constitutional change.

This is the most precise specification in the ADR-2 mandate for GOV-AUTH: the independence requirement is structural distinguishability, not organizational separation.

### F.3 Option Analysis — GOV-AUTH

#### Option A (Role Independence)

**Description for GOV-AUTH:** A specific constitutional role (e.g., Board of Directors resolution, General Secretary approval) is constitutionally designated as the governance authorization authority. The election administration function executes phase transitions; this role authorizes them.

**Constitutional pressures introduced:**
- AC-25 satisfaction depends on whether the authorization role is constitutionally insulated from the execution function. If the person holding the authorization role is also the election administrator, AC-25 may be satisfied in text but not in structure — the same individual authorizes and executes.
- AC-06: A role that is occupied by the same person as the execution function collapses the distinction.

**Assessment:** Option A can satisfy AC-25 if the constitutional role is held by a constitutionally designated person or body different from the execution function, and if that person's authority to authorize is constitutionally defined and not delegable to the execution function. This is a weaker structural guarantee than Option B but may be constitutionally sufficient for GOV-AUTH given that the primary requirement (distinguishability, not full independence) is modest compared to AUDIT or CERT.

#### Option B (Committee Independence)

**Description for GOV-AUTH:** A Governance Authorization Committee holds the mandate to authorize phase transitions. The committee is constituted by ElectionConstitution, distinct from the election administration function, and operates with a defined quorum. Phase transitions cannot proceed without committee authorization; the committee does not execute transitions.

**Constitutional significance of Authorization-Execution separation in Option B:**

```
GovernanceAuthority (aggregate)
    L2Holder: AuthorityHolder → Governance Authorization Committee (AUTHORIZES)
    [Election Administration]  → executes (NOT in this aggregate)
```

The domain model must represent these as distinct authority structures. The Committee holds GovernanceAuthority. The election administration function holds no GovernanceAuthority. A phase transition requires: (1) authorization by the Committee (GovernanceAuthority aggregate state change), (2) execution by the election administration. Without step (1), step (2) is constitutionally ungrounded.

**Advantages:**
- AC-25: Committee authorization is structurally distinct from election administration execution — they cannot be collapsed without constitutional change.
- AC-26: Committee identity is a first-class constitutional element (GovernanceAuthority.L2Holder).
- OBS-36D-01-2: Authorization (Committee action) ≠ Execution (administration function) — preserved structurally, not only documentarily.

**Constitutional pressures introduced:**
- Committee appointment process must be defined. If the election administration function can appoint its own authorization committee, the structural distinction collapses through the appointment pathway.
- Quorum and decision procedure must prevent the committee from becoming a rubber stamp.

#### Option C (External Organization)

**Description for GOV-AUTH:** An external constitutional body must authorize phase transitions.

**Assessment:** Option C exceeds what the constitutional evidence requires for GOV-AUTH. The authorization vs. execution separation (OBS-36D-01-2, AC-25) does not mandate external organizational independence — it mandates structural distinguishability. Option C would provide it, but at operational cost (every phase transition requires external authorization from a separate organization). The constitutional evidence for GOV-AUTH does not establish a requirement at this level.

### F.4 Selection — GOV-AUTH: **Option B (Committee Independence)**

**Rationale:**

The primary constitutional constraint for GOV-AUTH is structural distinguishability between authorization and execution (AC-25, OBS-36D-01-2). Option B (Committee) satisfies this without the operational complexity of Option C.

**Critical AC-25 verification:** The selection satisfies AC-25 only if the following constitutional invariants are preserved:

1. **The committee authorizes; the operational system executes.** These cannot swap roles without constitutional change.
2. **The committee cannot delegate authorization to the operational function.** ElectionConstitution must specify that authorization is a committee act, not a delegable administrative function.
3. **Phase transitions that occur without committee authorization are constitutionally ungrounded.** The GovernanceAuthority aggregate must represent this as an invariant — an unauthorized phase transition is a constitutional violation, not merely an error.
4. **The committee's composition is not controlled by the election administration function.** If the same function that executes transitions also appoints the body that authorizes them, the structural distinction collapses.

These four invariants are the specific constitutional content AC-25 requires. Option B satisfies them; Option A may satisfy them if the constitutional role is properly insulated; Option C over-specifies them.

**L2Holder specification:** GovernanceAuthority.L2Holder represents the Governance Authorization Committee. The committee holds L-2 for authorization of phase transitions. L4Revocation and L5Succession must route through constitutional governance, not through the election administration function.

**ElectionConstitution provision required (AC-30):**
- Governance Authorization Committee mandate: scope limited to authorizing/blocking phase transitions, not executing them
- Committee appointment process: constitutionally designated and independent of election administration function
- Authorization procedure: quorum, decision process, timeline
- Explicit statement that phase transitions require committee authorization
- Delegation prohibition: authorization cannot be delegated to the execution function

**Carried-Forward Constitutional Question — GOV-AUTH (to ADR-5):**

The analysis above establishes that the Governance Authorization Committee must be constitutionally independent of the election administration function in its appointment and revocation. A second-order constitutional question is not fully resolved in ADR-2:

> Does the GovernanceAuthorizationCommittee itself require constitutionally independent challengeability?

The current analysis addresses the committee's authorization mandate and its independence from the execution function (AC-25). It does not fully analyze the committee's own L-3 challenge pathway: who may challenge a wrongful authorization, or a wrongful refusal to authorize? If the committee's challenge reception routes through the same governance apparatus that appointed it, a concentration risk persists at the authorization level — distinct from the authorization/execution separation that AC-25 addresses.

This question is carried forward to ADR-5 (Challenge Architecture). ADR-5 must address the challengeability of governance authorization decisions as a distinct constitutional question, separate from the challenge architecture for election results and enrollment decisions.

---

## Part G — CERT Independence Assessment

### G.1 Current State

**Current coverage:** L-1 candidate; all other dimensions undefined. The most complete legitimacy gap of the five functions.

**Critical findings:**
- **AC-27:** Certification must include an external challenge pathway (L-3). An architecture in which certification decisions are final and unchallengeable embeds the constitutional defect from CF-05-21.
- **AC-28:** Certification is the highest-risk authority function (36D-04, CF-04-04). TC-4 (Certification Abuse) amplifies all prior threat classes. This is the terminal constitutional act.
- **TF-36C-05-01:** Certifier legitimacy ≠ certification validity. A legitimate certifier can produce constitutionally problematic certifications if L-3/L-4/L-5 are absent.

**Terminal position:** Certification is the last constitutional act in the election process. All constitutional defects in ENROLL, CRITERIA, AUDIT, and GOV-AUTH that reach certification will propagate into the certified result. A certification authority with a complete legitimacy gap certifies the aggregated constitutional exposure of all prior functions.

### G.2 Constitutional Requirements for CERT Independence

| Constraint | CERT-Specific Implication |
|---|---|
| AC-27 | External challenge pathway to certification is constitutionally required. Challenge routing cannot terminate at the certifying authority itself |
| AC-28 | CERT independence must account for the complete legitimacy gap and terminal risk position |
| AC-05 | At least one constitutionally independent authority relationship — for CERT, this means genuinely external to the certification function |
| AC-09 | Challenge reception must be structurally independent of the challenged authority — for CERT, this means challenges to certification cannot be received by the certifying authority |
| AC-12 | Revocation of certification must originate outside the certifying authority |

### G.3 Option Analysis — CERT

#### Option A (Role Independence)

**Description for CERT:** A constitutional role within the organization holds the certification mandate.

**Constitutional pressures introduced:**
- AC-27: For an external challenge pathway to routing outside the certifying authority, the challenge reception must be held by a body that is genuinely independent of the certification role. A role within the same organization, appointed by the same governance body as the certification role, cannot constitute a constitutionally independent challenge reception point.
- AC-09: Self-adjudicated challenges. If certification is challenged and the challenge routes through the same governance apparatus that appointed the certifying role, the challenge is effectively self-adjudicated.
- Terminal risk (AC-28): The complete legitimacy gap plus terminal position means Option A for CERT creates a constitutionally terminal unchallengeble act. AR-20: "Constitutional contestation of election legitimacy structurally impossible."

**Assessment:** Option A fails for CERT. The combination of complete legitimacy gap, terminal risk, TC-4 amplification, and the AC-27/AC-09 requirements means that a role within the organizational governance apparatus cannot constitute a constitutionally independent certification authority.

#### Option B (Committee Independence)

**Description for CERT:** A Certification Committee within the organizational framework holds the certification mandate.

**Constitutional pressures introduced:**
- AC-27: An internal committee can provide an external challenge pathway relative to the certification function — but challenges still route through the same organizational governance structure that appointed the committee. Whether this satisfies AC-27's external challenge requirement depends on whether the constitutional framework makes the challenge pathway genuinely independent of the certification function.
- AC-09: If the committee's challenge response is decided by the committee itself or by the same governance body that appointed it, self-adjudication risk persists.
- AC-28: Terminal risk. An internal committee that is constitutionally susceptible to pressure from the organization's leadership (who benefit from or are responsible for the election outcome) may not provide sufficient constitutional independence at the terminal act.

**Assessment:** Option B is stronger than Option A for CERT but still leaves AC-27 and AC-09 under constitutional pressure. The terminal nature of certification means that any internal body that receives election outcome challenges has an inherent proximity to the organizational interests in the election outcome. This proximity may be constitutionally manageable through careful constitutional design, but it represents a structural weakness at the most consequential point.

#### Option C (External Organization)

**Description for CERT:** An external constitutional body holds the certification authority mandate. This organization is constitutionally independent of the election-administering organization.

**Constraint satisfaction:**

| Constraint | Assessment under Option C |
|---|---|
| AC-27 (external challenge pathway) | Fully satisfied — challenge reception is with an external body constitutionally independent of the election system |
| AC-09 (challenge reception independent of challenged authority) | Fully satisfied — external organization is constitutionally separate |
| AC-12 (revocation from outside) | Satisfied — external body's mandate is revocable through its own constitutional governance, not through the election system |
| AC-28 (terminal risk awareness) | Addressed — external body is not subject to organizational pressure on the election outcome |
| AC-05 (independent authority relationship) | Fully satisfied — organizational independence |
| AC-06 (independence not nominal) | Fully satisfied — organizational boundary provides genuine independence |

**Constitutional pressures introduced:**
- The external certification body's own constitutional mandate must be grounded. What authorizes an external organization to certify this election? This authorization must appear in ElectionConstitution as an explicit constitutional delegation.
- New legitimacy dependency: the external certification organization has its own L-1/L-5 — these must be constitutionally specified. This is not a new D43 instance (see discipline note below).
- Inter-organizational operational requirements: the election must expose results in a form that the external certification body can access and evaluate (AC-15).

**Discipline Note — Option C for CERT does NOT create a new D43 instance:** The external certification organization is the L2Holder of the CertificationAuthority aggregate — not a new D43 function. The five D43 functions (ENROLL, CRITERIA, AUDIT, GOV-AUTH, CERT) are the constitutional authority requirements of the election system. An external body that holds the CERT mandate is a realization of D43-CERT, not a new authority function. The external organization's own internal governance is outside the scope of this program's D43 analysis.

#### Option D (Hybrid)

**Description for CERT:** Internal certification function performs the operational certification act; an external body provides the constitutional validation and challenge reception.

**Assessment:** Option D for CERT effectively describes what certification under Option C looks like in operation: an internal function assembles the certification package; the external authority validates, signs, and receives challenges. This is operationally a realization of Option C — the external authority is the constitutionally load-bearing entity. ADR-2 selects Option C; how it is operationally realized belongs to design rounds.

### G.4 Selection — CERT: **Option C (External Organization)**

**Rationale:**

The complete legitimacy gap, terminal position, TC-4 amplification, and the specific AC-27 requirement (external challenge pathway) together constitute the strongest constitutional case for external organization independence of any of the five D43 functions.

AC-27 specifically requires that certification decisions are externally addressable for challenge. "External" in AC-27 means external to the certifying authority — for AC-09 to be satisfied, the challenge reception must be genuinely independent of the certification authority. An internal committee (Option B) whose challenge response routes back through the same organizational governance is constitutionally insufficient for the terminal act.

Option C provides: (a) genuine AC-09 satisfaction — the external body receives challenges without routing them back through the election system; (b) AC-12 satisfaction — revocation of certification mandate routes through the external organization's constitutional governance, not the election system's; (c) AC-28 satisfaction — the external body is not subject to organizational pressure on the election outcome; (d) AC-27 — challenge pathway is genuinely external.

**L2Holder specification:** CertificationAuthority.L2Holder must represent a constitutionally designated external organization. The mandate of that organization to certify this election must appear in ElectionConstitution as an explicit constitutional delegation. L3Challenge (via ADR-5) must route to the external organization. L4Revocation must route through the external organization's constitutional governance.

**ElectionConstitution provision required (AC-30):**
- Constitutional delegation to external certification authority: which external body, what mandate, what scope
- Challenge pathway: explicit constitutional specification that certification challenges route to the external body, not the election system
- Access pathway: external body's constitutional right to access election results and evidence (AC-15 for CERT)
- Revocation provision: how the external body's mandate over this election can be challenged or replaced

---

## Part H — Cross-Function Analysis

### H.1 Per-Function Independence Selections Summary

| D43 Function | Selected Form | AC-05 Realization | Primary Rationale |
|---|---|---|---|
| ENROLL | Option B (Committee) | Enrollment Review Committee with constitutional mandate | Complete gap requires structural independence beyond naming |
| CRITERIA | Option B (Committee) | Criteria Review Committee with L-4 revocation independence | SELF-REF in L-4 requires structural break in revocation pathway |
| AUDIT | Option D (Hybrid) | Constitutional scope (ElectionConstitution) + independent execution body | IR-H requires scope from outside election system; ET-03 structural split |
| GOV-AUTH | Option B (Committee) | Governance Authorization Committee | AC-25: authorization and execution must be architecturally distinguishable |
| CERT | Option C (External Organization) | External constitutional certification authority | Terminal risk + AC-27 + AC-09 require genuine external independence |

### H.2 Overall Architectural Posture: Option D at Authority-Map Level

The five per-function selections constitute a **Hybrid independence model (Option D) at the authority map level.** No single independence form was selected uniformly. The distribution reflects the constitutional risk gradient across the five functions:

```
AC-06 risk sensitivity (highest → lowest need for strong independence):
CERT (terminal) > AUDIT (IR-H) > CRITERIA (SELF-REF) > ENROLL (complete gap) > GOV-AUTH (AC-25)

Independence form selected:
CERT:     Option C (External) — highest
AUDIT:    Option D (Hybrid)  — scope external, execution independent
CRITERIA: Option B (Committee) — with SELF-REF-breaking invariant
ENROLL:   Option B (Committee) — structural independence
GOV-AUTH: Option B (Committee) — structural distinguishability
```

This gradient is constitutionally justified: stronger independence is required where constitutional risk is highest (terminal function, complete gaps, structural defects). Uniform application of Option C across all functions would exceed constitutional requirements for ENROLL and GOV-AUTH; uniform application of Option B would be insufficient for CERT.

### H.3 Concentration Risk at the ElectionConstitution Ratification Level (AC-20)

**Critical concentration observation:** Four of five functions (ENROLL, CRITERIA, AUDIT execution, GOV-AUTH) use committee structures grounded in ElectionConstitution. CERT's external organization also requires constitutional authorization through ElectionConstitution. This means:

**ElectionConstitution ratification is the single constitutional chokepoint for all five authority aggregates.**

The L1Source of every authority aggregate (all five) ultimately traces to the ElectionConstitution. The ratification process for ElectionConstitution is therefore the highest-concentration point in the entire authority map:

```
ElectionConstitution ratification process
    ↓ L1Source for all five:
    EnrollmentAuthority.L1Source
    CriteriaAuthority.L1Source
    AuditScopeAuthority.L1Source (via constitutional scope mandate)
    AuditExecutionAuthority.L1Source
    GovernanceAuthority.L1Source
    CertificationAuthority.L1Source
```

**This concentration is visible and named — it is not hidden.** AC-20 requires trust concentration to be assessable at the authority map level. The authority map reveals: all five authority aggregates share a single L1Source ratification chokepoint. This is constitutionally transparent.

**This concentration is not eliminated by this ADR.** It is a direct consequence of the shared L-1 decision in ADR-1. The ElectionConstitution precision requirement (AC-30) and the reversal clause are the constitutional safeguards. The ratification process itself must be constitutionally robust — but that is a governance design question, not a domain model question.

**Authority map representation:** The authority map must explicitly show the ElectionConstitution ratification pathway as the constitutional root from which all five L1Source values derive. Trust concentration assessors reading the authority map must be able to identify this chokepoint without additional analysis.

### H.3.1 Chokepoint Failure Implications

The concentration identified above has a critical failure mode that must be named explicitly.

**If the ElectionConstitution ratification process is constitutionally invalid, incomplete, or successfully challenged, every authority aggregate loses its L1Source grounding simultaneously:**

| Authority Aggregate | Failure Consequence |
|---|---|
| EnrollmentAuthority | L1Source invalid → Enrollment Review Committee holds no constitutional mandate |
| CriteriaAuthority | L1Source invalid → Criteria-setting and L-4 revocation have no constitutional mandate |
| AuditScopeAuthority (candidate) | L1Source invalid → Constitutional scope mandate has no valid basis |
| AuditExecutionAuthority (candidate) | L1Source invalid → Execution body's mandate has no constitutional source |
| GovernanceAuthority | L1Source invalid → Phase transition authorization has no constitutional mandate |
| CertificationAuthority | L1Source invalid → External body's constitutional delegation has no valid source |

**Consequence: total constitutional collapse of the authority map.** This is not a partial failure in which some functions remain grounded while others do not. It is a single-point failure of the entire governance architecture. The constitutional risk of all five D43 functions concentrates in the ratification process for ElectionConstitution.

**ADR-7 responsibility:** ADR-7 (GovernanceState/ElectionConstitution Boundary) must address what constitutional protections surround the ElectionConstitution ratification process itself. The question "who guards the guardian?" applies at the constitutional root: if ElectionConstitution is the L1Source ground for all authority, the process by which ElectionConstitution achieves valid ratification must itself be constitutionally robust and challengeable. This is not a domain model question in isolation — it is a governance design requirement that the domain model must be able to represent and that ADR-7 must specify.

### H.4 Architecture Family Convergence Observation

The per-function selections (ENROLL→B, CRITERIA→B, AUDIT→D, GOV-AUTH→B, CERT→C), when compared against the three Architecture Families proposed in 36E-05, produce an identifiable alignment:

- **Family A (Associational):** Primarily Option A and B for all functions, with authority relationships realized through existing associations and role designations. ADR-2 diverges from Family A at CERT (selects Option C, not B) and AUDIT (selects Option D, not B).
- **Family B (Delegated Constitutional):** Constitutional delegation to committees and external bodies for specific functions, calibrated to constitutional risk. ADR-2 aligns with Family B at CERT (C), approximates Family B at AUDIT (D), and uses B for the three lower-risk functions.
- **Family C (Structural Independence):** Primarily Option C for most or all functions, requiring organizational-level independence across the authority map. ADR-2 uses Option C only for CERT; the other four functions use B or D — making the overall posture significantly more restrained than Family C.

**Assessment:** The five selections most closely approximate **Architecture Family B (Delegated Constitutional)** from 36E-05, applied with constitutional restraint. Option C is reserved for CERT (the terminal function where AC-27 makes it constitutionally necessary) rather than applied broadly as Family C would prescribe. This alignment was an emergent result of constitutional risk gradient analysis, not a deliberate family pre-selection — the constitutional evidence produced the result.

**Consequence for ADR-3 through ADR-7:** Subsequent ADRs should proceed under the recognition that the program has entered Family B territory. Escalation to Family C elements (broader Option C application) would require CERT-level constitutional evidence for the function in question — complete legitimacy gap, terminal risk, and external challenge pathway requirement. Such evidence is not established for ENROLL, CRITERIA, or GOV-AUTH.

### H.5 Cross-Function Consistency Analysis

**Can all five functions use Option B uniformly?**
No. CERT requires Option C because AC-27/AC-09 demand that challenge reception be external to the certification authority. An internal committee (Option B) for CERT leaves AC-27 under constitutional pressure at the terminal function. AUDIT requires Option D because IR-H requires scope independence at the constitutional level, not merely the committee level.

**Are different forms constitutionally consistent?**
Yes. OBS-36D-02-1 (authority ≠ context) implies that different authority relationships may have different constitutional structures. The independence forms do not need to be uniform across D43 functions — each form is selected based on the specific constitutional requirements of each function.

**Tension: CERT external body + shared ElectionConstitution L-1**
CERT's external body holds a certification mandate authorized by ElectionConstitution (the election-administering organization's constitutional document). There is a residual constitutional question: if the election-administering organization controls ElectionConstitution revision, can it retroactively narrow or invalidate the external body's certification mandate? This tension is noted but not resolved here — it belongs to ADR-7 (GovernanceState/ElectionConstitution boundary design) and to the organization's constitutional revision governance.

**Concentration pattern across functions:**
The four Option B (Committee) functions share the same structural pattern: a committee constituted by ElectionConstitution, with L2Holder, L3Challenge, L4Revocation, and L5Succession specified within the committee's mandate. This structural similarity may be exploited for modeling efficiency in ADR-3 through ADR-6 — but similarity of form does not imply similarity of constitutional content. Each committee's specific mandate, appointment process, and revocation pathway are distinct.

---

## Part I — Independence-Lifecycle Constitutional Event Taxonomy

### I.1 ADR-1 Inheritance

ADR-1 established candidate event names: AuthorityGranted, AuthorityChallenged, AuthorityRevoked, AuthorityTransferred. ADR-1 specified that final taxonomy is determined in ADR-2 (independence lifecycle) and ADR-5 (challenge architecture).

**ARB Required Revision (applied):** This part establishes candidate independence-lifecycle events, not finalized events. Finalization is deferred until ADR-3 through ADR-5 have validated the event set against evidence architecture, audit scope architecture, and challenge architecture. Challenge events (AuthorityChallenged and outcomes) are deferred to ADR-5.

### I.2 Independence-Lifecycle Events — Candidates

The authority aggregate lifecycle requires events that correspond to the constitutional transitions mandated by L-1/L-4/L-5:

**AuthorityConstituted**
- Trigger: a new authority aggregate is constitutionally created — committee formed per ElectionConstitution, or external body's mandate formally established
- Constitutional significance: L-2 is now identified; L-1 provision is referenced; the authority aggregate exists
- Replaces candidate: "AuthorityGranted" — "Constituted" more precisely captures that the authority is established through a constitutional process, not merely assigned

**AuthorityActivated**
- Trigger: the constituted authority begins operating (e.g., election opens; mandate period begins)
- Constitutional significance: L-2 holder is now actively exercising the authority; the distinction between constitution and activation is load-bearing for suspension analysis
- No ADR-1 candidate — this event is newly identified. The lifecycle (AC-11) requires explicit modeling of the transition from existence to operation.

**AuthoritySuspended**
- Trigger: authority mandate is temporarily removed; holder loses operative capacity but mandate is not terminated
- Constitutional significance: distinct from Revocation. Suspension has a defined return pathway (L-5 is not triggered); Revocation does not. Constitutional frameworks distinguish suspension (temporary, process-defined) from revocation (permanent, requires succession). Both must be explicitly modeled per AC-11.
- No ADR-1 candidate — this event is newly identified and constitutionally necessary.

**AuthorityRevoked**
- Trigger: authority mandate is terminated through the constitutionally designated external revocation pathway
- Constitutional significance: L-4 Revocation mechanism is exercised; L-5 Succession must be triggered
- Replaces candidate: "AuthorityRevoked" — same name, confirmed as final

**AuthorityTransferred**
- Trigger: authority mandate moves from current L-2 holder to a successor (L-5 mechanism activated)
- Constitutional significance: succession event following Revocation or end of mandate term; the new holder is identified
- Replaces candidate: "AuthorityTransferred" — same name, confirmed as final

### I.3 Challenge Events — Deferred to ADR-5

AuthorityChallenged and associated challenge outcome events depend on:
- EC-01 resolution (challengeability vs receipt-freeness)
- The specific challenge architecture for each D43 function
- The form of the external challenge body (different per function based on ADR-2 selections)

ADR-5 (Challenge Architecture) will finalize the challenge event set.

### I.4 Candidate Independence-Lifecycle Event Taxonomy

| Event | Trigger | L-Dimension | Status |
|---|---|---|---|
| AuthorityConstituted | Constitutional creation of authority aggregate | L-1/L-2 | CANDIDATE (ADR-2) |
| AuthorityActivated | Mandate becomes operative | L-2 | CANDIDATE (ADR-2) |
| AuthoritySuspended | Temporary removal of mandate | L-4 partial | CANDIDATE (ADR-2) |
| AuthorityRevoked | Permanent termination of mandate | L-4 full | CANDIDATE (ADR-2) |
| AuthorityTransferred | Succession of mandate to new holder | L-5 | CANDIDATE (ADR-2) |
| AuthorityChallenged | [Deferred] | L-3 | ADR-5 |
| AuthorityChallengeOutcome | [Deferred] | L-3 | ADR-5 |

**Validation Required:** These candidate events must be validated by ADR-3 (evidence and verifier architecture), ADR-4 (audit scope authority), and ADR-5 (challenge architecture) before finalization. The event names and lifecycle triggers may require revision once challenge architecture and audit scope architecture are determined. These are not final per this ADR — finalization belongs to the ADR that most directly governs each event's constitutional trigger.

---

## Part J — Comparative Option Matrix

### J.1 Per-Function Option Selections

| Function | Option A | Option B | Option C | Option D | Selected |
|---|---|---|---|---|---|
| ENROLL | Fails AC-06 at complete gap | Satisfies AC-05/06/12 | Exceeds requirement | N/A | **Option B** |
| CRITERIA | Fails AC-22 SELF-REF break | Satisfies AC-22/23 with L-4 separation | Exceeds requirement | Collapses to B | **Option B** |
| AUDIT | Fails IR-H structurally | Borderline IR-H | Satisfies fully | Constitutional scope + independent execution | **Option D** |
| GOV-AUTH | May satisfy AC-25 with care | Satisfies AC-25 structurally | Exceeds requirement | N/A | **Option B** |
| CERT | Fails AC-27/AC-09/AC-28 | Leaves AC-27/09 under pressure | Satisfies AC-27/09/12/28 | Operationally = Option C | **Option C** |

### J.2 Constitutional Constraint Coverage Summary

| Constraint | ENROLL | CRITERIA | AUDIT | GOV-AUTH | CERT |
|---|---|---|---|---|---|
| AC-02 (no self-verification) | Committee mandate ≠ election admin | Committee L-4 ≠ L-2 | Scope external; execution independent | Committee ≠ execution | External body ≠ election system |
| AC-05 (independent relationship) | Enrollment Committee | Criteria Committee | Scope: constitutional; Execution: audit body | Governance Committee | External certification body |
| AC-06 (not nominal) | Structural — committee mandate | Structural — L-4 separate | Structural — scope in constitution | Structural — committee ≠ execution | Organizational — external body |
| AC-12 (revocation from outside) | Constitutional governance | Criteria Committee revokes L-2 | Constitutional amendment; audit governance | Constitutional governance | External body governance |
| AC-24 | — | — | Satisfied by Option D | — | — |
| AC-25 | — | — | — | Committee authorizes; system executes | — |
| AC-27 | — | — | — | — | External body receives challenges |

---

## Part K — Consequences for ADR-3 through ADR-7

### K.1 Consequences for ADR-3 (Evidence and Verifier Architecture)

- ADR-2 selects Option D for AUDIT — scope via ElectionConstitution, execution via independent body
- ADR-3 inherits: AuditExecutionAuthority aggregate candidate (distinct from AuditScopeAuthority)
- ADR-3 must address EH-01 (verifier independence) in light of the IR-H-grade independence required for AuditExecutionAuthority
- ADR-3 must address CPR-05 (evidence integrity options) and their compatibility with the selected audit execution structure
- The DataChecksum (VO-2) and ReceiptHash (VO-3) elements must be evaluated against the execution authority's access pathway requirements

### K.2 Consequences for ADR-4 (Audit Scope Authority)

- ADR-4 directly receives the AUDIT Option D consequence: AuditScopeAuthority as distinct aggregate candidate
- ADR-4 must finalize whether AuditScopeAuthority is a distinct aggregate or a specification-level element within ElectionConstitution
- ADR-4 must address CPR-02 (audit scope authority options) in light of the selected scope-from-ElectionConstitution model
- AC-16 satisfaction (scope ≠ execution boundary) must be verified in ADR-4

### K.3 Consequences for ADR-5 (Challenge Architecture)

- ADR-5 inherits five challenge reception points with different independence profiles:
  - ENROLL challenges: Enrollment Committee (Option B)
  - CRITERIA challenges: Criteria Review Committee (Option B)
  - AUDIT execution challenges: Audit execution body (independent, IR-H grade)
  - GOV-AUTH challenges: Governance Authorization Committee (Option B)
  - CERT challenges: External certification body (Option C) — this is the primary EC-01 case
- ADR-5 must finalize the challenge event set (AuthorityChallenged, AuthorityChallengeOutcome) for each function's challenge architecture
- EC-01 resolution (challengeability vs receipt-freeness) is the governing question for ADR-5; the CERT external body is the strongest case for a constitutionally independent challenge pathway
- **GOV-AUTH carried-forward question (from F.4):** Does the GovernanceAuthorizationCommittee require its own constitutionally independent L-3 challenge pathway? ADR-5 must determine whether wrongful authorizations or wrongful refusals to authorize are independently challengeable, and through what pathway

### K.4 Consequences for ADR-6 (Certification Architecture — D39 provisional)

- ADR-6 inherits: CertificationAuthority aggregate with external L2Holder
- ADR-6 must detail the L3Challenge (external body receives), L4Revocation (external body governance), and L5Succession for the CertificationAuthority aggregate
- ADR-6 is provisional pending D39 resolution (Results/Tallying domain model)
- The external certification body's access pathway (AC-15 for CERT) must be specified in ADR-6

### K.5 Consequences for ADR-7 (D42B Closure / GovernanceState Boundary)

- ADR-7 must address the concentration tension identified in H.3: ElectionConstitution ratification is the chokepoint for all five L1Source values
- ADR-7 must resolve the GovernanceState/ElectionConstitution relationship (from ADR-1, I.5.2)
- ADR-7 inherits: CERT external body's mandate is ElectionConstitution-derived; if ElectionConstitution revision is controlled by the election-administering organization, what constitutional protections prevent retroactive narrowing of the external body's mandate?

### K.6 Authority Map State After ADR-2

```
Authority Map (conceptual, not context map):
┌────────────────────────────────────────────────────────────────────────────┐
│                     ElectionConstitution (shared L1Source)                  │
│                     [Ratification chokepoint — AC-20 visible]               │
└──────────┬──────────┬──────────┬──────────┬──────────────────────────────┘
           │          │          │          │                    │
    EnrollmentA  CriteriaA   AuditScope  GovernanceA    CertificationA
    [L2=Cmte]   [L2=Cmte    [L2=Const.  [L2=Cmte]     [L2=External]
                 L4=Cmte]    document]                        │
                                 │                     [External body
                             AuditExec                  holds challenge
                             [L2=Indep               reception — AC-27]
                              body]
```

---

## Section — ARB Decision Block

**[APPROVED — Required Revisions Applied 2026-06-15]**

**Revisions Applied:**
1. E.4 — AuditScopeAuthority/AuditExecutionAuthority split downgraded from implied finding to CANDIDATE REFINEMENT requiring ADR-4 confirmation.
2. F.4 — GovernanceAuthorizationCommittee independent challengeability carried forward to ADR-5 as explicit constitutional question.
3. H.3.1 — ElectionConstitution chokepoint failure implications expanded: total constitutional collapse if ratification fails; ADR-7 responsibility named.
4. I.4 — Independence-lifecycle event taxonomy downgraded from FINAL to CANDIDATE pending ADR-3/ADR-4/ADR-5 validation.
5. H.4 (new) — Architecture Family convergence observation added: per-function selections align with Family F2 (Structural Distribution) from 36E-05 as emergent result.

### Decisions Made in This ADR

1. **ENROLL:** Option B (Committee Independence). Enrollment Review Committee constituted by ElectionConstitution.
2. **CRITERIA:** Option B (Committee Independence). Criteria Review Committee with L-4 revocation mandate independent of L-2 criteria-setting function. SELF-REF broken structurally.
3. **AUDIT:** Option D (Hybrid). Scope: ElectionConstitution as constitutional scope mandate (AC-14/16/17). Execution: constitutionally independent audit body with IR-H-grade independence guarantee.
4. **GOV-AUTH:** Option B (Committee Independence). Governance Authorization Committee — authorizes phase transitions; operational system executes. AC-25 structural distinguishability confirmed as binding invariant.
5. **CERT:** Option C (External Organization). External constitutional certification body with AC-27 external challenge reception. External body is L2Holder of CertificationAuthority — NOT a new D43 instance.
6. **Overall authority-map posture:** Option D (Hybrid) applied at authority-map level — independence form calibrated to constitutional risk gradient per function.
7. **Constitutional event taxonomy (independence-lifecycle):** AuthorityConstituted, AuthorityActivated, AuthoritySuspended, AuthorityRevoked, AuthorityTransferred — CANDIDATE pending ADR-3/ADR-4/ADR-5 validation. Challenge events deferred to ADR-5. Finalization belongs to the ADR most directly governing each event's constitutional trigger.
8. **AuditAuthority aggregate inventory refinement:** AuditAuthority (ADR-1 candidate) may become AuditScopeAuthority + AuditExecutionAuthority. Not a new D43 instance. ADR-4 finalizes.
9. **ElectionConstitution concentration risk (AC-20):** Named and visible. Ratification process is the single constitutional chokepoint. Authority map must make this explicit.

### Open Questions for ARB

**OQ-37-02-01: Can one independence model satisfy all D43 functions?**

ADR-2 conclusion: No. CERT requires Option C (AC-27 external challenge reception cannot be satisfied by internal committee). AUDIT requires Option D (IR-H scope independence cannot be satisfied by operational-level committee). The remaining three functions (ENROLL, CRITERIA, GOV-AUTH) can use Option B (Committee) with function-specific constitutional invariants.

**OQ-37-02-02: Which D43 function requires the strongest independence?**

ADR-2 conclusion: CERT (Option C — external organization; terminal risk; TC-4 amplification; complete legitimacy gap; AC-27/28) and AUDIT scope (constitutional-level independence for Gap A-3 closure; IR-H) jointly require the strongest independence structures. CERT is the terminal function; AUDIT scope is the evidence foundation for CERT.

**OQ-37-02-03: Does any option create new authority relationships requiring explicit modeling?**

ADR-2 conclusion: AUDIT's Option D implies an aggregate inventory refinement (AuditScopeAuthority + AuditExecutionAuthority from one AuditAuthority candidate). CERT's Option C implies that the external organization's mandate must be explicitly enumerated in ElectionConstitution. These are not new D43 instances — they are authority aggregate design consequences.

**OQ-37-02-04: Are additional constitutional authority aggregates required beyond current candidates?**

ADR-2 conclusion: Possibly one additional aggregate (AuditScopeAuthority), subject to ADR-4 determination. The current five D43 candidates may become six aggregates (AUDIT splits). This is within ADR-1's aggregate inventory discipline (one D43 function may produce multiple aggregates).

**OQ-37-02-05: Does any selected independence form reopen the deferred Alternative C authority-layer question?**

ADR-2 conclusion: CERT's Option C (external organization) and AUDIT's Option D (constitutional scope definition) bring the program closest to Alternative C territory — they require that some authority relationships are constitutionally defined at a level external to the operational DDD model. These are expressible within Alternative B vocabulary (dedicated aggregates with L1Source referencing constitutional provisions), but the external reference complexity may make the case for Alternative C's separate modeling layer more visible. ADR-2 does not reopen Alternative C — it notes the observation for future rounds.

### Decisions Deferred to Subsequent ADRs

- AuditScopeAuthority vs single AuditAuthority aggregate: ADR-4 (E.4 split is CANDIDATE ONLY — not established by ADR-2)
- Independence-lifecycle event taxonomy finalization (AuthorityConstituted through AuthorityTransferred): ADR-3/ADR-4/ADR-5 validation (events are CANDIDATE in this ADR)
- Challenge event taxonomy (AuthorityChallenged, AuthorityChallengeOutcome): ADR-5
- GovernanceAuthorizationCommittee independent challengeability question (L-3 for GOV-AUTH): ADR-5
- CertificationAuthority L3/L4/L5 details: ADR-6
- ElectionConstitution revision governance, ratification protection, and CERT mandate protection: ADR-7 (see H.3.1)
- External body access pathway (AC-15 for CERT, AC-15 for AUDIT): ADR-3 (evidence) and ADR-6 (certification)

### Authorization Requested

ADR-3: Evidence and Verifier Architecture (CPR-05, EH-01)
ADR-4: Audit Scope Authority Structure (CPR-02, ET-03)
ADR-5: Challenge Architecture (EC-01, AC-09/10)

ADR-3 and ADR-4 may be authorized in parallel (they have no dependency on each other). ADR-5 depends on ADR-2 and ADR-3. The conditional ADR-3 → ADR-5 dependency established in 36E-05 remains active: if ADR-3 selects CPR-05 Option C (cryptographic commitment), ADR-5 must address the CT-1 cross-tension (EC-01 × CPR-05 Option C).

---

*Round 37-02 — ADR-2: Independence Form per D43 Function — APPROVED (revisions applied 2026-06-15)*  
*Research Program: NRNA DDD Trustworthiness*  
*Document: Round37-02_ADR-2_Independence_Form_per_D43_Function.md*  
*Predecessor: Round 37-01 — ADR-1 — APPROVED*  
*Successors pending ARB: ADR-3, ADR-4, ADR-5*
