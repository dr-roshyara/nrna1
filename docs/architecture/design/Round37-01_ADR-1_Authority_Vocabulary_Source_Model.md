# Round 37-01 — ADR-1: Authority Vocabulary and Authority Source Model

**Program:** NRNA DDD Trustworthiness Research Program  
**Round:** 37 — ADR Authoring (first place selection is allowed)  
**Document:** ADR-1 of 7  
**Status:** APPROVED WITH REQUIRED REVISIONS — APPROVED  
**Governing Constraint:** OQ-03-05 (CPR-03), OQ-36E-04-04 (ElectionConstitution as shared L-1), OQ-36E-04-06 (vocabulary sufficiency)

**Predecessors:** 36E-05 Architecture Synthesis — APPROVED. This ADR is the dependency root for ADR-2 through ADR-7.

**Governance Instruction (binding):** Round 37 is the first place where architectural selection is allowed. This ADR must identify alternatives, evaluate alternatives against constitutional constraints, SELECT one alternative, record rationale for selection, and record rejected alternatives with reasons for rejection.

**Scope:** Conceptual Architecture only. No services, microservices, APIs, repositories, or deployment architecture.

---

## Part A — Problem Statement

### A.1 The Problem

The NRNA program has established 30 architectural constraints (AC-01 through AC-30) that constitutional trustworthiness requires of the election system's domain model. Central among these are:

- **AC-03:** L-1 through L-5 legitimacy dimensions must be first-class architectural elements
- **AC-04:** Authority relationships must be first-class in the domain model
- **AC-11:** Authority lifecycle (creation, operation, suspension, revocation, succession) must be explicitly modeled
- **AC-18:** Authority map design must be a separate activity from context map design
- **AC-19:** Authority partitioning must be separate from context partitioning
- **AC-20:** Trust concentration must be assessable at the authority map level

The existing DDD model (Rounds 33–35D) contains no first-class constitutional authority structures. The closest existing element is an `ElectionConstitution` reference inside `GovernanceState` — a weak L-1 signal that was identified in 36E-03 as the strongest such signal in the entire current model.

This ADR must answer two questions that together determine how all subsequent ADRs are expressed:

**Question 1 — Vocabulary:** What DDD modeling vocabulary is constitutionally sufficient for representing L-1/L-5 authority relationships?

**Question 2 — Source:** Does `ElectionConstitution` serve as the shared L-1 constitutional source for all five D43 functions (ENROLL, CRITERIA, AUDIT, GOV-AUTH, CERT), or does each function require a separate L-1 instrument?

These questions must be co-decided. The vocabulary decision constrains how the source model is expressed; the source model decision constrains what the vocabulary must be capable of representing.

### A.2 Why This ADR Must Come First

ADR-1 is the dependency root of the ADR authoring sequence. All subsequent ADRs assume a vocabulary for expressing constitutional authority relationships and a source model for those relationships. Without ADR-1:

- ADR-2 (Independence Form) cannot specify how independence is represented
- ADR-3 (Evidence/Verifier) cannot specify the constitutional authority behind verifier designation
- ADR-4 (Audit Scope) cannot specify the source of audit scope authority
- ADR-5 (Challenge Architecture) cannot specify the authority that receives challenges
- ADR-6 (Certification) cannot specify the certifying authority's constitutional ground
- ADR-7 (D42B Closure) cannot specify the VRC's authority boundary

Every subsequent ADR inherits the vocabulary and source model established here.

### A.3 Constitutional Evidence in Brief

The constitutional evidence chain for this ADR is:

```
CF-05-08 (CANDIDATE MAJOR): Behavioral Integrity ≠ Constitutional Legitimacy
    ↓
AC-03: L-1/L-5 must be first-class architectural elements
AC-04: Authority relationships must be first-class
    ↓
D43 Pattern (5 instances): all five authority functions have
    zero L-2/L-5 coverage in the current model
    ↓
OBS-36D-02-1 (GOVERNING): Authority Distribution ≠ Bounded Context Distribution
    ↓
AC-18/19/20: Authority map ≠ context map; designed in separate activity;
    trust concentration assessable at authority map level
    ↓
ADR-1 Question: What modeling vocabulary and source model
    realize this separation while remaining within DDD?
```

---

## Part B — Constitutional Evidence

### B.1 Evidence Supporting Vocabulary Requirements

**AC-03 (from CF-05-08 and 36D-04):**
L-1 through L-5 legitimacy dimensions must be first-class architectural elements. They are not metadata, annotations, or comments — they are constitutionally load-bearing structures. The vocabulary must support explicit representation of:
- L-1: Legitimacy Source (what constitutional instrument authorizes this authority?)
- L-2: Authority Holder (who holds the mandate?)
- L-3: Challenge Mechanism (how can the authority be contested?)
- L-4: Revocation Mechanism (how can the authority be withdrawn?)
- L-5: Succession Mechanism (how is the authority transferred?)

**AC-11 (from 36D-04 CF-04-04):**
Authority lifecycle must be explicitly modeled. The vocabulary must be capable of representing state transitions: authority created, operating, suspended, revoked, transferred. This is not a behavioral lifecycle (like VoteRecorded/VoteRejected) — it is a constitutional lifecycle.

**AC-18/AC-19 (from OBS-36D-02-1):**
The authority map must be designed in a separate activity from the context map. The vocabulary must make this separation natural and enforceable — not merely documentary.

**AC-20 (from 36E-01 ACQ-13):**
Trust concentration must be assessable at the authority map level. The vocabulary must permit a systematic reading of the authority map that reveals concentration risks — a function that annotations on a context map cannot perform.

### B.2 Evidence Supporting Source Question

**Existing signal (from 36E-03, AC-03 assessment):**
`ElectionConstitution` in `GovernanceState` is the strongest L-1 signal in the current model. It is weak — it is a reference to a constitutional document, not a first-class authority structure — but it establishes the pattern: constitutional authority is grounded in a named constitutional instrument.

**D43 across five functions (from 36D-04):**
| D43 Instance | Current L-1 Coverage | Current L-2/L-5 Coverage |
|---|---|---|
| ENROLL | None | None |
| CRITERIA | Membership ratification (CANDIDATE) | None |
| AUDIT | None (Gap A-3) | None |
| GOV-AUTH | None (ADH-1 unresolved) | None |
| CERT | None | None |

All five functions share the same structural legitimacy gap. A shared constitutional instrument (ElectionConstitution) is the only existing evidence of a solution pattern.

**AC-23 (criteria ratification):**
The constitution's ratification process (membership vote) is identified as the L-1 candidate for criteria authority. This ratification process naturally extends to other authority functions — the same constitutional instrument that ratifies eligibility criteria can define enrollment authority, audit mandate, and certification authority.

**AC-30 (criteria precision):**
The constitutional instrument must be sufficiently precise to constitute an authoritative scope definition for each authority function. Vague constitutional language is constitutionally exploitable (TF-36C-04-03).

**Operational observation (from 36E-05 dependency map):**
If each D43 function requires a separate L-1 instrument, the program requires five independently ratified constitutional instruments. The operational overhead of establishing, ratifying, and maintaining five instruments is significantly higher than one shared instrument. This is not a constitutional constraint — it is an operational constraint that the ADR should acknowledge.

---

## Part C — Alternative A: Domain Policies

### C.1 Description

Authority relationships are modeled as domain policies — named, explicit constitutional rules that aggregates and contexts consult when making authority decisions. The `ElectionConstitution` element in `GovernanceState` is the foundation. Each D43 function's authority is expressed as a named policy:

```
ElectionConstitution          (existing)
EnrollmentAuthorityPolicy     (new)
AuditAuthorityPolicy          (new)
CertificationAuthorityPolicy  (new)
GovernanceAuthorityPolicy     (new)
```

L-1/L-5 dimensions are expressed as properties of each policy element.

### C.2 Vocabulary Assessment Against Constitutional Constraints

| Constraint | Assessment |
|---|---|
| AC-03 (L-1/L-5 first-class) | Partial — policies are first-class DDD elements but their constitutional structure (L-1/L-5 as explicit properties) requires explicit design discipline; not guaranteed by the pattern |
| AC-04 (authority relationships first-class) | Partial — policies are first-class, but policies are typically stateless rules, not structured authority relationships with lifecycle |
| AC-11 (authority lifecycle) | Not naturally satisfied — policies do not have lifecycle; a separate mechanism must be introduced to model authority suspension, revocation, and succession |
| AC-18 (separate design activity) | Not satisfied — policies are typically designed alongside aggregates that use them, not in a separate authority modeling activity |
| AC-19 (separate partitioning) | Not satisfied — policy partitioning follows context partitioning |
| AC-20 (trust concentration assessable) | Requires additional analysis — the authority map would be constructed by reading all policies; concentration is assessable but not natively visible |

### C.3 Compatibility Profile

From 36E-05 compatibility matrix: Compatible with CPR-01 A and B; less compatible with CPR-01 C (external organization does not map naturally to a policy reference); creating tension with CPR-01 D (hybrid). The policy vocabulary naturalizes ElectionConstitution as shared L-1 but does not accommodate per-function L-1 differentiation without significant vocabulary extension.

---

## Part D — Alternative B: Dedicated Aggregates

### D.1 Description

Each authority relationship is modeled as a first-class aggregate in the domain model. Authority aggregates have their own identity, their own lifecycle, and their own invariants. L-1 through L-5 dimensions are value objects within the authority aggregate.

```
EnrollmentAuthority (aggregate)
    L1Source: ConstitutionalRef (value object)
    L2Holder: AuthorityHolder (value object)
    L3Challenge: ChallengePathway (value object)
    L4Revocation: RevocationMechanism (value object)
    L5Succession: SuccessionPlan (value object)
    status: AuthorityStatus (lifecycle state)
```

Authority aggregates do not produce operational domain events (VoteRecorded, VerificationGranted) — they produce constitutional events (AuthorityGranted, AuthorityRevoked, AuthorityChallenged, AuthorityTransferred are candidates). They can be designed in a separate modeling activity. OBS-36D-02-1 is respected: authority aggregates are NOT bounded contexts.

**Constitutional Event Taxonomy Discipline Note:** The candidate event names above follow from the L-2/L-4 lifecycle structure. They are NOT the final event taxonomy. ADR-2 (Independence Form) and ADR-5 (Challenge Architecture) will determine the final constitutional event set — the challenge event taxonomy in particular depends on the form of independence adopted and the EC-01 resolution. Subsequent ADRs must not treat these candidate names as frozen.

### D.2 Vocabulary Assessment Against Constitutional Constraints

| Constraint | Assessment |
|---|---|
| AC-03 (L-1/L-5 first-class) | Well-satisfied — L-1/L-5 are value objects within the aggregate; each dimension is explicit and typed |
| AC-04 (authority relationships first-class) | Well-satisfied — authority aggregates are the primary modeling element |
| AC-11 (authority lifecycle) | Well-satisfied — aggregates have explicit lifecycle states and transition events |
| AC-12 (revocation from outside) | Satisfied — L4Revocation specifies the revocation pathway; aggregate enforces it as an invariant |
| AC-13 (revocation + succession connected) | Satisfied — both L4 and L5 are value objects in the same aggregate; their relationship is explicit |
| AC-18 (separate design activity) | Partially satisfied — authority aggregates can be designed in a separate modeling session; not guaranteed by the pattern but naturally enabled |
| AC-19 (separate partitioning) | Partially satisfied — authority aggregates can be partitioned separately from operational aggregates; the authority map is the collection of authority aggregates |
| AC-20 (trust concentration assessable) | Satisfied — the authority map (collection of authority aggregates) directly supports trust concentration analysis; L2Holder across all aggregates shows concentration immediately |
| AC-08 (external challenge pathway) | More naturally satisfied — authority aggregate exposes challenge pathway as an aggregate behavior |

### D.3 Compatibility Profile

From 36E-05 compatibility matrix: REINFORCING with CPR-01 B (committee) and C (external organization); compatible with CPR-01 A (role mandate). Authority aggregates can model all four independence forms. Per-function L-1 differentiation is naturally accommodated — each aggregate's L1Source value object carries a different constitutional reference if needed. Shared L-1 is equally accommodated — all aggregates reference `ElectionConstitution` as their L1Source.

---

## Part E — Alternative C: Separate Authority Modeling Layer

### E.1 Description

Authority relationships are modeled in a distinct architectural layer that exists alongside the DDD domain model. The domain model handles behavioral correctness (operational aggregates, domain events, commands). The authority layer handles constitutional legitimacy (authority relationships, L-1/L-5 specifications, independence assessments). The two layers interact through explicit cross-layer references, with `GovernanceState → ElectionConstitution` as the existing template.

The authority layer requires a modeling vocabulary that may differ from standard DDD vocabulary — it describes constitutional structures, not business processes.

### E.2 Vocabulary Assessment Against Constitutional Constraints

| Constraint | Assessment |
|---|---|
| AC-03 (L-1/L-5 first-class) | Fully satisfied — L-1/L-5 are native concepts in the authority layer |
| AC-18 (separate design activity) | Best satisfied — separate layer is inherently a separate design activity |
| AC-19 (separate partitioning) | Best satisfied — authority partitioning is designed independently |
| AC-20 (trust concentration assessable) | Best satisfied — the authority layer IS the authority map |
| OAQ-13 (DDD tools for constitutional authority) | Directly addressed — separate layer may require a different vocabulary |
| AC-11 (authority lifecycle) | Can be fully satisfied — authority layer can model lifecycle explicitly |

### E.3 Constraints and Costs

Alternative C requires the program to define a new modeling vocabulary for the authority layer. This is not merely a documentation choice — it affects:
- What tools can represent the authority model
- How the authority model is reviewed and maintained
- Whether the authority model can be validated against the DDD model

The new vocabulary must be defined before any authority modeling work can proceed. This is itself an ADR-level decision — what vocabulary does the authority layer use? If the answer is "a subset of DDD vocabulary," Alternative C collapses into a variant of Alternative B. If the answer is "a new constitutional modeling language," the program must design that language before designing the authority structures.

### E.4 Compatibility Profile

From 36E-05 compatibility matrix: Reinforcing with CPR-01 B/C/D; compatible with all source model options. The strongest AC-18/19/20 satisfaction of all alternatives. However: the vocabulary problem means Alternative C is not actionable as stated — it requires an additional vocabulary decision before it can be implemented.

---

## Part F — Evaluation Matrix

### F.1 Vocabulary Alternatives Against Constitutional Constraints

| Constraint | Alt A (Policies) | Alt B (Aggregates) | Alt C (Separate Layer) | Alt D (Annotations) |
|---|---|---|---|---|
| AC-03 (L-1/L-5 first-class) | Partial | Well-satisfied | Fully satisfied | Partial |
| AC-04 (authority relationships first-class) | Partial | Well-satisfied | Fully satisfied | Partial |
| AC-11 (lifecycle) | Not satisfied | Well-satisfied | Satisfied | Not satisfied |
| AC-12/13 (revocation + succession) | Not naturally satisfied | Satisfied | Satisfied | Not satisfied |
| AC-18 (separate activity) | Not satisfied | Partially satisfied | Best satisfied | Not satisfied |
| AC-19 (separate partitioning) | Not satisfied | Partially satisfied | Best satisfied | Not satisfied |
| AC-20 (trust concentration) | Requires additional analysis | Satisfied | Best satisfied | Requires tooling |
| AC-08 (challenge pathway) | Less natural | More natural | Satisfied | Not satisfied |
| DDD vocabulary compatibility | Highest (standard) | High (standard) | Requires new vocabulary | Highest (standard) |
| Actionability | Immediate | Immediate | Requires vocabulary ADR first | Immediate |

**Dominant observation:** Alternative D (Annotations) satisfies the fewest constraints and is not actionable for constitutional authority modeling at the required depth. Alternative C (Separate Layer) provides the strongest constitutional satisfaction but is not immediately actionable — it requires a prior vocabulary definition. Alternative A (Policies) satisfies AC-20 and AC-03 partially but fails AC-11, AC-18, and AC-19. Alternative B (Dedicated Aggregates) satisfies the most constraints with standard DDD vocabulary and is immediately actionable.

### F.2 Source Alternatives Against Constitutional Evidence

| Evidence / Constraint | Shared L-1 (ElectionConstitution) | Per-Function L-1 | Hybrid |
|---|---|---|---|
| Existing ElectionConstitution signal | Extends naturally | Requires additional instruments beyond the existing signal | Extends for some; requires new instruments for others |
| AC-23 (criteria ratification) | Ratification process extends to other functions | Each function requires separate ratification | Criteria/Governance ratification covers some; enrollment/certification separate |
| AC-30 (precision requirement) | High precision demand — one document must precisely specify all five authority mandates | Precision per instrument — lower per-instrument precision requirement | Moderate — shared instrument must cover its functions precisely |
| Operational overhead | Lowest — one instrument, one ratification | Highest — five instruments, potentially five ratifications | Moderate |
| D43-ENROLL L-1 coverage | Provided by ElectionConstitution if it explicitly includes enrollment | Requires separate enrollment constitutional instrument | Requires separate enrollment instrument even in hybrid |
| D43-CERT L-1 coverage | Provided by ElectionConstitution if it explicitly includes certification | Requires separate certification authority instrument | Requires separate certification instrument (highest risk) |
| ADR-1 scope constraint | ADR-1 is conceptual; this choice shapes the authority model | ADR-1 is conceptual; this choice multiplies ADR complexity | ADR-1 is conceptual; hybrid increases case complexity |

---

## Part G — Decision

### G.1 Vocabulary Decision: Alternative B — Dedicated Aggregates

**Selected:** Alternative B — Authority relationships modeled as dedicated aggregates with L-1/L-5 as value objects.

**Rationale:**

Alternative B satisfies the most constitutional constraints (AC-03, AC-04, AC-11, AC-12, AC-13, AC-20, AC-08) using standard DDD vocabulary that the program has already established. It is immediately actionable without requiring a prior vocabulary definition. It can support both shared and per-function L-1 source models without vocabulary changes — the `L1Source` value object accommodates either a single shared constitutional reference or function-specific references.

Alternative B also provides the clearest path to AC-18 and AC-19 satisfaction: authority aggregates are a distinct aggregate type from operational aggregates (Vote, Verification, GovernanceState), and their design can be conducted as a separate modeling activity. They produce constitutional events (AuthorityGranted, AuthorityChallenged, AuthorityRevoked, AuthorityTransferred) that are distinct from operational events. The authority map is the collection of authority aggregates — trust concentration (AC-20) is directly readable from the map without additional tooling.

Alternative C provides stronger AC-18/19 satisfaction but cannot be adopted in this ADR because it requires defining a new modeling vocabulary before any authority structures can be designed. Adopting Alternative C would make this ADR a vocabulary specification rather than an authority architecture decision — the appropriate sequence would require a new sub-ADR (ADR-0) to define the authority layer vocabulary. Given that the program has established DDD as its modeling language, the incremental cost of Alternative B (authority aggregates within DDD) is lower than the incremental cost of Alternative C (new vocabulary outside DDD).

Alternative A fails AC-11 (lifecycle), which is constitutionally load-bearing. Alternative D fails AC-18 and does not satisfy AC-20 without additional tooling.

**Governing discipline note:** The adoption of Alternative B does not preclude a future decision to represent authority aggregates in a separate visual layer for documentation purposes. This is a conceptual architecture decision. The visual representation of the authority map (as a diagram, document, or artifact separate from the context map) is a separate concern — it can achieve Option C's documentation goals while using Option B's modeling vocabulary.

### G.2 Source Decision: ElectionConstitution as Shared L-1

**Selected:** ElectionConstitution as the shared L-1 source for all five D43 functions.

**Rationale:**

The existing program evidence provides one clear L-1 signal in the entire model: the `ElectionConstitution` reference in `GovernanceState`. This signal emerged from independent discovery research (36A through 36D) without being designed for. It represents the constitutional instrument that the organization already implicitly relies upon.

A shared L-1 provides significant constitutional and operational advantages:
- Single ratification process covers all five authority functions (lowest operational overhead)
- The membership ratification pattern identified in 36D-04 as the L-1 candidate for Criteria extends naturally to Enrollment, Audit, Governance, and Certification
- ADR-2 through ADR-6 can reference a single, well-defined constitutional instrument rather than establishing separate instruments per function

The critical constraint this decision imposes is **precision**: the ElectionConstitution must explicitly enumerate the authority mandate for each D43 function. A vague or incomplete constitutional document that names the election system without specifying enrollment authority, audit mandate, or certification criteria would not satisfy AC-30 for any of the five functions it is expected to cover. This is a design requirement for the ElectionConstitution artifact itself — not a reason to reject the shared L-1 decision.

The per-function L-1 alternative is not constitutionally required by the evidence collected. Nothing in AC-01 through AC-30 mandates separate instruments; the requirement is for first-class L-1 specification. A single well-specified constitutional instrument satisfies that requirement for all five functions simultaneously.

The hybrid alternative introduces case complexity — some functions share L-1, others require separate instruments — without clear constitutional evidence that this differentiation is required. The hybrid approach would increase the ADR authoring burden for ADR-2 through ADR-6 without corresponding constitutional gain.

### G.3 Combined Decision Statement

**ADR-1 Decision (binding for ADR-2 through ADR-7):**

1. Authority relationships are modeled as **dedicated aggregates** in the domain model. L-1/L-5 legitimacy dimensions are **value objects** within each authority aggregate. Authority aggregates produce **constitutional events** — candidate taxonomy: AuthorityGranted, AuthorityChallenged, AuthorityRevoked, AuthorityTransferred; final taxonomy determined in ADR-2 and ADR-5.

2. **ElectionConstitution** is adopted as the **shared L-1 constitutional source** for all five D43 authority functions (ENROLL, CRITERIA, AUDIT, GOV-AUTH, CERT). The ElectionConstitution artifact must explicitly enumerate the authority mandate for each function. The `L1Source` value object in each authority aggregate references the ElectionConstitution as its constitutional ground.

3. The **authority map** is the collection of authority aggregates. It is a distinct artifact from the bounded context map. Trust concentration is assessable by reading L2Holder values across all authority aggregates.

4. This is a **conceptual architecture decision**. No services, APIs, repositories, or deployment structures are specified here.

---

## Part H — Rejected and Deferred Alternatives

**Alternative Status Summary:**
| Alternative | Status | Reason |
|---|---|---|
| Alt A (Domain Policies) | Rejected | Fails AC-11 (lifecycle), AC-18 (separate activity) |
| Alt B (Dedicated Aggregates) | **Selected** | Satisfies most constraints; standard DDD vocabulary; immediately actionable |
| Alt C (Separate Authority Layer) | **Deferred** | Not rejected — deferred: vocabulary prerequisite unresolved |
| Alt D (Annotations) | Rejected | Fails AC-18, AC-19, AC-20; inadequate for constitutional authority depth |

### H.1 Alternative A (Domain Policies) — Rejected

**Reason:** Fails AC-11 (authority lifecycle) and AC-18 (separate design activity). Policies are stateless rules — they cannot naturally model the constitutional lifecycle of authority (suspension, revocation, succession). The authority lifecycle is constitutionally load-bearing: AC-11/12/13 specify that it must be explicitly modeled. Alternative A cannot satisfy this requirement without extending beyond the policy pattern in ways that effectively converge on Alternative B.

**Retained value:** The policy pattern provides useful vocabulary for representing constitutional RULES (criteria, scope definitions, constitutional standards). These are distinct from authority RELATIONSHIPS (who holds the mandate, who can challenge, who can revoke). The distinction between constitutional rules (policies) and authority relationships (aggregates) will be maintained in subsequent ADRs. Policies remain valid for constitutional rule representation; aggregates are adopted for authority relationship representation.

### H.2 Alternative C (Separate Authority Layer) — **DEFERRED** (not rejected)

**Reason for deferral:** Alternative C is not defeated by constitutional evidence — it provides the strongest AC-18/19/20 satisfaction of all alternatives. It is deferred because it is not immediately actionable: it requires defining a new modeling vocabulary for the authority layer before any authority structure can be designed. Adopting Alternative C in this ADR would replace an architecture decision with a vocabulary design specification — inappropriate scope for ADR-1.

**Important: Alternative C is not rejected.** It is deferred. If Round 37 produces authority aggregates (Alternative B) whose constitutional coverage is found insufficient in practice, the program may revisit Alternative C in a future round. The separate authority layer concept — authority partitioning designed independently from context partitioning — is fully compatible with the authority aggregate model. Authority aggregates can always be grouped into a separate authority layer document that uses DDD vocabulary. The separation of concerns that Alternative C provides is achievable within Alternative B's vocabulary choice.

### H.3 Alternative D (Annotations) — Rejected

**Reason:** Fails AC-18 (separate design activity), AC-19 (separate partitioning), and AC-20 (trust concentration requires additional tooling to extract). Annotations on existing elements cannot satisfy the constitutional requirement that the authority map be a distinct architectural artifact. The authority map must stand independently — it is a constitutional document as much as a technical artifact.

### H.4 Per-Function L-1 — Rejected

**Reason:** No constitutional evidence requires separate L-1 instruments per function. AC-01 through AC-30 require first-class L-1 specification, not separate instruments. The operational overhead of five independently ratified constitutional instruments is disproportionate to the constitutional gain. The shared ElectionConstitution, if sufficiently precise, satisfies the L-1 requirement for all five functions.

**Condition and Reversal Clause:** This decision is conditional on the ElectionConstitution being sufficiently precise (AC-30 compliance). **If the constitutional drafting process reveals that a single document cannot be made sufficiently precise to cover all five authority mandates with the required constitutional specificity, the shared L-1 decision is reversed and Per-Function L-1 evaluation is reopened.** This reversal clause is not a weakness in ADR-1 — it is an explicit constitutional condition. The precision requirement is load-bearing. This condition must be explicitly acknowledged in ADR-2 through ADR-6 as a design prerequisite: each ADR that specifies an authority aggregate must verify that the relevant ElectionConstitution provision exists and is sufficiently precise before the aggregate's L1Source can be fully specified.

### H.5 Hybrid Source Model — Rejected

**Reason:** Introduces ADR authoring complexity (some functions reference one source, others another) without constitutional evidence that the differentiation is required. If the shared L-1 decision is adopted with full precision, the hybrid adds no constitutional protection while adding significant complexity to ADR-2 through ADR-7.

---

## Part I — Consequences

### I.1 Immediate Consequences for the Domain Model

**New element type: Authority Aggregate**

The domain model now includes a class of aggregates dedicated to constitutional authority representation. These are NOT operational aggregates (they do not record votes, grant verifications, or record audit observations). They are constitutional aggregates that represent the authority relationships the election system depends upon.

Current operational aggregates (Vote, Verification, GovernanceState, Audit Context) are unchanged by this decision. Authority aggregates are additive — they extend the model without modifying existing elements.

**Five authority relationship candidates for aggregate realization (not frozen aggregate inventory):**
| D43 Function | Authority Relationship (candidate name) |
|---|---|
| D43-ENROLL | EnrollmentAuthority |
| D43-CRITERIA | CriteriaAuthority |
| D43-AUDIT | AuditAuthority |
| D43-GOV-AUTH | GovernanceAuthority |
| D43-CERT | CertificationAuthority |

**ADR-1 Aggregate Inventory Discipline Note:** ADR-1 selects the vocabulary pattern (dedicated aggregates) — it does NOT finalize the aggregate inventory. ADR-2 through ADR-6 may discover that one D43 function requires multiple aggregates, or that multiple D43 functions share a single aggregate. The five candidates above are starting points for each subsequent ADR's authority aggregate design — they are not frozen boundaries. Authority aggregate design (value objects, invariants, events) belongs to ADR-2 through ADR-7.

### I.2 Immediate Consequences for the Authority Map

The authority map is now defined as: **the collection of all five authority aggregates, their L-1/L-5 value objects, and the relationships between them.**

The authority map is the primary artifact for trust concentration analysis (AC-20). It is distinct from the bounded context map produced in Rounds 33–35D. Both maps exist as architectural artifacts; neither subsumes the other (OBS-36D-02-1 preserved).

### I.3 Immediate Consequences for ElectionConstitution

The `ElectionConstitution` reference in `GovernanceState` is elevated from a weak L-1 signal to the shared constitutional source for all five authority aggregates. This elevation has two implications:

1. **Precision requirement activated (AC-30):** The ElectionConstitution artifact must explicitly specify the authority mandate for each D43 function. This is a design requirement for the constitutional drafting process, not the domain model. However, domain model designs in ADR-2 through ADR-7 will reference the ElectionConstitution by its specific article or section for each function — undefined provisions are not authoritative.

2. **The `L1Source` value object:** Each authority aggregate's L1Source value object will carry a reference to the ElectionConstitution (by provision, article, or section) as its constitutional ground. This reference must be resolvable — it cannot be a generic reference to "the constitution." The ElectionConstitution's precision requirement follows directly from this.

   **L1Source Governance Rule (binding for all subsequent ADRs):** `L1Source` is not a document reference field. It is the representation of the constitutional grant from which authority derives legitimacy — it encodes *who authorized this authority to exist*, not merely *which document mentions this authority*. Implementation teams must not reduce `L1Source` to a bare string or document citation. Its value object structure must be capable of carrying: the constitutional instrument identity, the specific provision or article, the ratification date or process, and the granting body. The constitutional legitimacy encoded in `L1Source` is load-bearing — it is what distinguishes a constitutionally grounded authority aggregate from an operationally defined role.

### I.4 Constraints Inherited by ADR-2 through ADR-7

All subsequent ADRs must:

- Express authority relationships as authority aggregate specifications
- Reference ElectionConstitution as the L1Source for their pressure area
- Specify L-2 through L-5 value objects as part of each authority aggregate's design
- Produce constitutional events (not operational events) for authority state changes
- Maintain the authority map as a distinct artifact from the context map

### I.5 Open Questions Created by This Decision

**I.5.1:** What is the exact constitutional event set for authority aggregates? (AuthorityGranted, AuthorityChallenged, AuthorityRevoked, AuthorityTransferred are candidates — the exact set belongs to ADR-2's authority aggregate design.)

**I.5.2:** What is the relationship between the ElectionConstitution as L1Source and the `GovernanceState` aggregate that currently holds the ElectionConstitution reference? Does GovernanceState become a projection of ElectionConstitution, a consumer of ElectionConstitution authority, or something else? This belongs to ADR-7 (D42B and GovernanceState boundary design).

**I.5.3 (AC-30 Condition):** What is the minimum precision standard for the ElectionConstitution to satisfy the L1Source requirement for each function? This is a governance question, not a domain model question. It must be addressed before authority aggregates can be fully specified — it is a prerequisite for ADR-2 through ADR-6 completion.

---

## Section — ARB Decision Block

**[APPROVED WITH REQUIRED REVISIONS — APPROVED]**

**ARB Verdict Date:** 2026-06-15

### Decisions Made in This ADR

1. **Vocabulary:** Alternative B — Dedicated Aggregates adopted. Authority relationships are first-class aggregates with L-1/L-5 as value objects.
2. **Source:** ElectionConstitution as shared L-1. All five D43 authority functions reference ElectionConstitution as their L1Source.
3. **Alternative Status:** A = Rejected; B = Selected; C = Deferred (not rejected); D = Rejected.
4. **Authority Aggregates:** Five candidate authority relationships (not frozen aggregate inventory); ADR-2 through ADR-6 finalize boundaries.
5. **Constitutional Events:** Candidate taxonomy (AuthorityGranted, AuthorityChallenged, AuthorityRevoked, AuthorityTransferred); final taxonomy in ADR-2 and ADR-5.

### ARB Required Revisions (Applied)

**Revision 1 — Aggregate Inventory Not Frozen:** Section I.1 changed from "Five authority aggregates implied" to "Five authority relationship candidates for aggregate realization." Discipline note added: ADR-1 selects vocabulary pattern, not final aggregate inventory. ADR-2 through ADR-6 may expand or consolidate boundaries.

**Revision 2 — L1Source as Constitutional Grant:** L1Source governance rule added to Section I.3: L1Source is not a document reference field. It represents the constitutional grant from which authority derives legitimacy. Must carry: constitutional instrument identity, specific provision, ratification date/process, and granting body.

**Revision 3 — Constitutional Event Taxonomy as Candidates:** Discipline note added to Alternative B description and to Part G decision statement: candidate event names are not frozen; final event taxonomy is determined in ADR-2 (Independence Form) and ADR-5 (Challenge Architecture).

**Revision 4 — Shared L-1 Reversal Condition Made Explicit:** Condition in H.4 strengthened to reversal clause: "If ElectionConstitution cannot be made sufficiently precise to cover all five authority mandates, the shared L-1 decision is reversed and Per-Function L-1 evaluation is reopened." Acknowledgment requirement added: ADR-2 through ADR-6 must verify relevant ElectionConstitution provision exists and is precise enough before finalizing each authority aggregate's L1Source.

**Revision 5 — Alternative C Status Formalized as Deferred:** H.2 header changed to "DEFERRED (not rejected)." Summary table added at top of Part H listing all four alternatives with explicit status labels.

### ARB Responses to OQs

**OQ-37-01-01 (Accept Alternative B?):** Yes. Alternative B is accepted. The rationale for deferring Alternative C (vocabulary prerequisite unresolved, not constitutional weakness) is accepted. Alternative C remains available for future rounds if Alternative B proves constitutionally insufficient in practice.

**OQ-37-01-02 (Accept shared L-1 with precision condition?):** Yes, conditionally. ElectionConstitution as shared L-1 is accepted. The reversal clause (Revision 4) makes the condition explicit. ADR-2 through ADR-6 inherit this condition as a design prerequisite.

**OQ-37-01-03 (Five D43 functions correctly scoped?):** Mostly yes. The five D43 functions are valid authority relationship candidates. They are not yet guaranteed aggregate boundaries — ADR-2 through ADR-6 may discover one function requires multiple aggregates or multiple functions share one aggregate. The candidates are correct starting points.

### Decisions Deferred to Subsequent ADRs

- Authority aggregate design (L-2/L-5 value object structures, invariants, events): ADR-2 through ADR-6
- Final constitutional event taxonomy: ADR-2 and ADR-5
- ElectionConstitution precision verification per function: ADR-2 through ADR-6 (each must verify)
- GovernanceState / ElectionConstitution relationship: ADR-7
- Authority map visual representation: separate tooling decision, deferred

### Authorization

**ADR-1: APPROVED — CLOSED**

**ADR-2: AUTHORIZED**

Round 37-02 — ADR-2: Independence Form per D43 Function.
ADR-2 inherits: Alternative B vocabulary, shared L-1 with reversal clause, candidate authority aggregate names, candidate constitutional event taxonomy.

---

*Round 37-01 — ADR-1: Authority Vocabulary and Authority Source Model — APPROVED WITH REQUIRED REVISIONS — APPROVED*  
*ARB Approval Date: 2026-06-15*  
*Research Program: NRNA DDD Trustworthiness*  
*Document: Round37-01_ADR-1_Authority_Vocabulary_Source_Model.md*  
*Successor: Round 37-02 — ADR-2: Independence Form per D43 Function — AUTHORIZED*
