# Principal Review and Next-Step Execution: Cross-Domain Relationship Discovery

## 1. Principal Reviewer Assessment of the Cross-Domain Focus Document

### 1.1 What the Document Achieves

The cross-domain focus document is a significant improvement over the previous plans. It does three things well:

- **It reframes the problem correctly.** Instead of assuming bounded contexts and then fitting relationships into them, it derives contexts from relationships. This is the correct DDD sequencing.
- **It introduces the direct / derived / policy-authorized distinction.** This is the single most valuable conceptual contribution so far. It prevents the platform from collapsing three fundamentally different kinds of business meaning into one.
- **It prioritizes the foundational relationships first.** Starting with Member ↔ Membership ↔ OrganizationalUnit is correct, because these determine committee eligibility, geographic affiliation, forum access, identity model, and tenancy model.

### 1.2 Critical Strengths

1. **Relationship families as organizing principle.** Grouping into Identity & Belonging, Structure, Governance, Geography, Communication, and Authorization is more useful than starting with an entity list, because it exposes meaning.
2. **The "arrows are not all the same" insight.** Recognizing that arrows represent ownership, participation, authority, geography, or policy evaluation is essential. Most failed domain models conflate these.
3. **The repeatable question set.** The nine questions asked of each relationship (meaning, authority, type, lifecycle, cascade, invariants, owner, dependents, falsification scenario) form a rigorous discovery method.
4. **Explicit falsification.** Asking "what scenario would prove our proposed model wrong?" is scientific discipline rarely seen in domain modeling.

### 1.3 Critical Gaps and Corrections

The document is strong but has four gaps that must be corrected before we proceed.

**Gap 1 — The relationship catalogue is a table, not a specification.**
The table lists hypotheses and unresolved questions but does not yet define:
- Whether a relationship is an association, an entity, or a value object.
- Whether the relationship has its own identity.
- Whether the relationship can exist independently of either endpoint.
- Whether the relationship can be temporal (valid only for a period).
- Whether the relationship can be many-to-many with attributes.

Each row in the catalogue must eventually become a full specification, not a hypothesis.

**Gap 2 — The document does not yet classify relationships using the A/B/C taxonomy it proposes.**
The taxonomy (direct, derived, policy-authorized) is introduced in Section 4, but the catalogue in Section 3 does not apply it. Every relationship must be classified.

**Gap 3 — The document does not distinguish "membership in an organization" from "affiliation with a unit."**
This is the central ambiguity. Consider:

- Is belonging to a local branch a *consequence* of membership, or a *separate* fact?
- Can a person be a member of the national organization without belonging to any branch?
- Can a person be affiliated with a branch without being a member of the national organization?
- Can a person be affiliated with multiple branches?

The Phase 1 document hinted at this ("A member may be associated with multiple committees simultaneously") but did not resolve it. This must be resolved before any bounded context is drawn.

**Gap 4 — The document does not address role composition and delegation.**
The catalogue asks "Can an appointment carry multiple roles?" but does not address:
- Can a role be delegated temporarily?
- Can a role be shared (co-chairs)?
- Can a role be acting vs. permanent?
- Can a role be inherited from a position in a parent unit?

These are governance realities in political parties and federations.

### 1.4 Architectural Tension to Resolve

The central tension in this phase is:

- **Relationship minimalism** — fewer relationships reduce coupling and complexity.
- **Relationship expressiveness** — more relationships capture real organizational variety.

The A/B/C taxonomy is the tool that resolves this tension: direct relationships are minimal and explicit; derived relationships are computed, not stored; policy-authorized relationships are evaluated, not persisted. This keeps the core model small while allowing rich behavior.

---

## 2. The Next Step: Cross-Domain Relationship Specification v0.1

The next concrete deliverable is the **Cross-Domain Relationship Specification v0.1**, starting with the foundational relationships. I will produce its structure, its first specifications, and its validation method.

### 2.1 Specification Template

Every relationship will be specified using this template:

| Field | Description |
|---|---|
| **ID** | Stable identifier (e.g., R-01) |
| **Name** | Human-readable name |
| **Source** | Source concept |
| **Target** | Target concept |
| **Business meaning** | What this relationship means in the real organization |
| **Type** | Direct / Derived / Policy-authorized |
| **Representation** | Association, entity, value object, or computed |
| **Cardinality** | 1:1, 1:N, M:N |
| **Optionality** | Required / optional on each side |
| **Ownership** | Which domain owns the authoritative fact |
| **Lifecycle** | Created, modified, terminated; states |
| **Temporal validity** | Is the relationship time-bounded? |
| **Historical requirement** | Must history be preserved? |
| **Invariants** | What must always hold? |
| **Exceptions** | What configurations may violate the default? |
| **Cascade rules** | What happens when either endpoint changes or disappears? |
| **Dependents** | Which other domains consume this fact? |
| **Evidence status** | Observed / Derived / Hypothesis / Contested |
| **Falsification scenario** | What would prove this wrong? |
| **Open questions** | Unresolved decisions |

### 2.2 Foundational Relationship Specifications (v0.1)

I will now apply the template to the four most foundational relationships. These are the ones the cross-domain focus document correctly prioritizes.

---

#### R-01: Person ↔ Member

| Field | Value |
|---|---|
| **Business meaning** | A Person is a human being; a Member is that person's representation within a specific organization |
| **Type** | Direct |
| **Representation** | Member is an entity; Person is an entity |
| **Cardinality** | 1 Person : N Members (across organizations) |
| **Optionality** | A Person may exist without any Member record; a Member must reference exactly one Person |
| **Ownership** | Person: Identity context; Member: Membership context |
| **Lifecycle** | Member created when a Person first interacts with an organization; may be archived but not deleted if history matters |
| **Temporal validity** | Member record persists beyond membership episodes |
| **Historical requirement** | Yes — a Person's member records must be preserved even after all memberships end |
| **Invariants** | Every Member references exactly one Person; a Person may have at most one Member record per organization |
| **Exceptions** | Some organizations may require a Person to be re-registered after long absence |
| **Cascade rules** | If a Person is merged with another, Member records must be re-pointed; if a Member is archived, memberships remain |
| **Dependents** | Membership, Governance, Communication |
| **Evidence status** | Derived from Phase 1 |
| **Falsification scenario** | An organization treats Member as a global concept shared across organizations |
| **Open questions** | Is Person global or organization-scoped? What identity attributes are shared? |

---

#### R-02: Member ↔ Membership

| Field | Value |
|---|---|
| **Business meaning** | Membership is the relationship through which a Member belongs to an Organization; it is an episode, not a state |
| **Type** | Direct |
| **Representation** | Membership is an entity (aggregate candidate) |
| **Cardinality** | 1 Member : N Memberships over time; at most 1 active Membership per organization per policy |
| **Optionality** | A Member may have zero memberships (lapsed); a Membership must reference exactly one Member and one Organization |
| **Ownership** | Membership context |
| **Lifecycle** | Applied → Approved/Rejected → Active → Suspended → Terminated/Expired |
| **Temporal validity** | Yes — each Membership has start and end dates |
| **Historical requirement** | Yes — terminated memberships must be preserved |
| **Invariants** | A Membership must reference exactly one Member and one Organization; a Membership must have a valid MembershipType at all times |
| **Exceptions** | Some organizations allow overlapping memberships (e.g., dual membership in federated structures); some allow multiple active memberships |
| **Cascade rules** | If a Member is archived, active Memberships must be terminated; if an Organization is dissolved, Memberships must be terminated or transferred |
| **Dependents** | Governance (eligibility for appointments), Communication (forum access), Finance (dues) |
| **Evidence status** | Derived from Phase 1 |
| **Falsification scenario** | An organization treats membership as a continuous state, not an episode |
| **Open questions** | Can memberships overlap? Is rejoining a new episode or a continuation? |

---

#### R-03: Membership ↔ MembershipType

| Field | Value |
|---|---|
| **Business meaning** | MembershipType is the classification and policy that applies to a Membership |
| **Type** | Direct |
| **Representation** | MembershipType is a value object or policy; Membership references it |
| **Cardinality** | 1 MembershipType : N Memberships; 1 Membership : 1 MembershipType at any time |
| **Optionality** | Required — a Membership must always have a type |
| **Ownership** | Membership context |
| **Lifecycle** | MembershipType defined by organization; may be versioned; may be deprecated but not deleted if referenced |
| **Temporal validity** | Yes — a Membership may change type over time |
| **Historical requirement** | Yes — the type that applied at each period must be preserved |
| **Invariants** | A MembershipType must be defined by the organization that owns the Membership |
| **Exceptions** | Some organizations allow members to hold multiple types simultaneously (e.g., ordinary + honorary) |
| **Cascade rules** | If a MembershipType is deprecated, existing Memberships retain it but new ones cannot use it |
| **Dependents** | Finance (fees), Governance (eligibility), Communication (access) |
| **Evidence status** | Hypothesis |
| **Falsification scenario** | An organization allows membership without a defined type |
| **Open questions** | Can a membership have multiple types? How are type changes versioned? |

---

#### R-04: Member ↔ OrganizationalUnit (Affiliation)

| Field | Value |
|---|---|
| **Business meaning** | A Member may be affiliated with one or more OrganizationalUnits (branch, wing, chapter) |
| **Type** | **Contested** — may be Direct (affiliation is a fact) or Derived (from membership in a unit) |
| **Representation** | Likely an explicit affiliation entity with its own lifecycle, not a direct FK |
| **Cardinality** | M:N — a Member may affiliate with multiple units; a unit may have many affiliated Members |
| **Optionality** | A Member may have zero affiliations (member-at-large); a unit may have zero affiliated Members (newly created) |
| **Ownership** | Organization Structure context (or Membership context, depending on resolution) |
| **Lifecycle** | AffiliationCreated → Active → Suspended → Ended |
| **Temporal validity** | Yes — affiliations may be time-bounded |
| **Historical requirement** | Yes — past affiliations must be preserved for audit |
| **Invariants** | An affiliation must reference exactly one Member and one OrganizationalUnit; the unit must belong to the same organization as the Member's membership (if membership is required) |
| **Exceptions** | Some organizations allow cross-organization affiliation (federated structures); some allow affiliation without membership |
| **Cascade rules** | If a unit is dissolved, affiliations must end; if a Member is archived, affiliations must end |
| **Dependents** | Governance (committee eligibility), Geography (jurisdictional affiliation), Communication (forum access) |
| **Evidence status** | Contested — this is the central unresolved question |
| **Falsification scenario** | An organization treats unit affiliation as identical to membership |
| **Open questions** | Is affiliation a consequence of membership, a separate fact, or a policy? Can a person be affiliated without being a member? Can a member be affiliated with multiple units? |

---

### 2.3 The Central Ambiguity: Membership vs. Affiliation

The four specifications above expose the central unresolved question:

**Is organizational affiliation part of membership, or a separate relationship?**

Three possible models:

**Model A — Affiliation is part of Membership.**
A Membership records which unit the member belongs to.
*Consequence:* A member belongs to exactly one unit (or the model must allow multiple units per membership).

**Model B — Affiliation is a separate relationship.**
A Member may have a Membership (belonging to the organization) and one or more Affiliations (belonging to units).
*Consequence:* Affiliation has its own lifecycle, can be time-bounded, can be multiple.

**Model C — Affiliation is derived from Membership.**
A Member's affiliation is computed from the unit structure the member is associated with.
*Consequence:* No explicit affiliation entity; affiliation is a projection.

**Recommendation:** Model B is most expressive and most consistent with the direct/derived/policy-authorized taxonomy. It allows:
- A member-at-large (membership without affiliation).
- A volunteer (affiliation without membership, if policy allows).
- A multi-unit member (multiple affiliations).
- A historical member (past affiliations preserved).

Model A is simpler but loses expressiveness. Model C is fragile because derivation rules vary by organization.

This decision must be made by the ARB with stakeholder evidence before the bounded contexts can be validated.

---

### 2.4 Classification of the Catalogue Using A/B/C Taxonomy

Applying the taxonomy from Section 4 of the cross-domain focus document:

| Relationship | Type | Rationale |
|---|---|---|
| Person → Member | Direct | Explicit registration |
| Member → Membership | Direct | Explicit application and approval |
| Membership → MembershipType | Direct | Explicit assignment |
| Member → OrganizationalUnit | **Contested** | May be Direct or Derived |
| OrganizationalUnit → OrganizationalUnit | Direct | Explicit hierarchy |
| Committee → OrganizationalUnit | Direct | Explicit governance assignment |
| Member → Committee | Direct | Explicit appointment |
| CommitteeAppointment → Role | Direct | Explicit role assignment |
| CommitteeAppointment → Mandate | Direct | Explicit mandate definition |
| Committee → GeographicJurisdiction | Direct | Explicit jurisdiction assignment |
| Member → Forum | **Derived** | Computed from membership, affiliation, appointment, role, and policy |
| Forum → GovernancePolicy | Direct | Explicit policy assignment |

This classification must be applied to every relationship in the catalogue.

---

### 2.5 Validation Method

For each relationship, the following validation steps are performed:

1. **Evidence check** — is there a source document supporting this relationship?
2. **Cardinality test** — does the proposed cardinality hold in real scenarios?
3. **Lifecycle test** — can the relationship be created, modified, and terminated independently?
4. **Cascade test** — what happens when either endpoint changes?
5. **Invariant test** — can the invariants be violated by a plausible scenario?
6. **Falsification attempt** — try to construct a scenario that breaks the specification.
7. **Cross-organization test** — does the relationship hold across at least two organization types?
8. **Formal check** — where the relationship involves temporal or structural constraints, verify with logic.

Relationships that fail validation are revised or reclassified.

---

### 2.6 Formal Logic and Mathematical Contributions

For the foundational relationships, the following formal techniques apply:

- **State machines** for Membership, Affiliation, Appointment, and Mandate lifecycles.
- **First-order logic** for cardinality and ownership constraints.
- **Graph theory** for OrganizationalUnit hierarchy (acyclicity, parent uniqueness).
- **Temporal logic** for mandate validity, membership periods, and affiliation durations.
- **Constraint satisfaction** for policy configurations (e.g., "at most one active chairperson per committee").
- **Model checking** for lifecycle transitions (e.g., "no Membership can become Active without Approval").
- **Set theory** for role composition and delegation.

These are applied **proportionately** — only where the state space and assumptions can be specified adequately.

---

### 2.7 Machine Learning Contributions (Assistive Only)

ML can assist in the cross-domain relationship discovery as follows:

- **Document clustering** — group organizational constitutions by structural similarity to identify relationship patterns.
- **Term disambiguation** — detect when the same term (e.g., "member") is used differently across documents.
- **Contradiction detection** — flag rules that conflict across documents.
- **Anomaly detection** — flag unusual membership or affiliation patterns for human review.
- **Synthetic scenario generation** — generate edge-case scenarios for relationship validation.
- **Relationship extraction** — propose candidate relationships from unstructured text for human validation.

ML must never:
- Decide cardinality or ownership.
- Approve or reject relationships.
- Replace stakeholder validation.
- Make authoritative decisions about membership, affiliation, or access.

All ML outputs are **hypotheses** requiring human review.

---

## 3. How Far We Are and What Remains

### 3.1 Current Status

| Area | Status |
|---|---|
| Phase 1 — Conceptual brainstorming | Foundation established (~20–25%) |
| Gate 0 — Corpus verification | Completed |
| Gate 1 — Research plan | Completed (revised) |
| Cross-domain focus | Completed (this document's input) |
| Cross-Domain Relationship Specification v0.1 | **Started in this response** |
| — R-01 to R-04 specified | Done |
| — Remaining relationships | Not started |
| A/B/C classification of full catalogue | Partially applied |
| Central ambiguity (Membership vs. Affiliation) | Identified, not resolved |
| Bounded-context derivation | Not started |
| Aggregate validation | Not started |
| Context map | Not started |
| Tenancy decision | Not started |
| Identity model decision | Not started |
| ML scope | Drafted, not approved |
| ARB-1 approval | Pending |

**Overall assessment:** We are approximately **25–30%** of the way toward an implementable organizational domain model. The relationship discovery method is now rigorous; the first four foundational relationships are specified; the central ambiguity is exposed.

### 3.2 Remaining TODOs (Prioritized)

**P0 — Complete the Cross-Domain Relationship Specification v0.1**
- Specify R-05 through R-13 (OrganizationalUnit hierarchy, Committee governance, Appointment-Role, Appointment-Mandate, Committee-Jurisdiction, Member-Forum, Forum-Policy, and Authorization links).
- Apply the A/B/C taxonomy to every relationship.
- Classify every relationship by representation (association, entity, value object, computed).
- Document cardinality, optionality, ownership, lifecycle, temporal validity, historical requirement, invariants, exceptions, cascade rules, dependents, evidence status, falsification scenario, and open questions.

**P0 — Resolve the central ambiguity**
- Decide Model A (affiliation part of membership), Model B (affiliation separate), or Model C (affiliation derived).
- Gather evidence from at least three organization types.
- Obtain ARB decision.
- Document rationale and consequences.

**P1 — Extend the relationship catalogue**
- Specify Member ↔ CommitteeAppointment ↔ Role ↔ Mandate.
- Specify Committee ↔ GeographicJurisdiction.
- Specify Member, Governance, Structure ↔ Forum.
- Specify all relationships to Identity & Access.

**P1 — Validate relationships against scenarios**
- Build scenarios that test each relationship.
- Attempt falsification for each specification.
- Record counterexamples and revise.

**P1 — Apply formal logic selectively**
- State machines for Membership, Affiliation, Appointment, Mandate.
- Graph properties for OrganizationalUnit hierarchy.
- Temporal logic for mandate and membership periods.
- Constraint satisfaction for policy configurations.
- Model checking for critical lifecycle rules.

**P2 — Derive bounded contexts from relationships**
- Group relationships by ownership and lifecycle.
- Identify context boundaries from relationship clusters.
- Validate each context against at least two scenarios.
- Document context responsibilities, aggregates, contracts, events.

**P2 — Validate aggregates**
- Determine aggregate boundaries from transactional invariants.
- Decide CommitteeAppointment, MembershipType, Jurisdiction, Affiliation aggregate status.
- Validate with concurrency scenarios.

**P2 — Produce the context map**
- Map upstream/downstream dependencies.
- Define integration patterns and contracts.
- Specify consistency expectations.

**P3 — Decide identity and tenancy models**
- Compare global Person vs. organization-scoped Member records.
- Compare shared schema, schema-per-tenant, database-per-tenant.
- Base decisions on requirements, not preference.
- Obtain ARB approval.

**P3 — Define ML scope and oversight**
- Finalize assistive ML use cases.
- Define human oversight rules.
- Exclude ML from authoritative decisions.

**P3 — Prepare architecture decision package**
- Assemble glossary, relationships, rules, events, scenarios, contexts, aggregates, map, tenancy, ML scope.
- Mark every decision as proposed / accepted / rejected / unresolved.
- Submit for independent ChatGPT review.
- Obtain ARB-2 architecture approval.

**P4 — Implementation planning (post-approval)**
- Produce dependency-ordered implementation plan.
- Obtain ARB-3 implementation authorization.
- Implement incrementally with tests and evidence.

### 3.3 Principal Recommendation

Approve the Cross-Domain Relationship Specification v0.1 direction. The next concrete deliverable is:

1. **R-05 through R-13 specifications** for the remaining cross-domain relationships.
2. **A/B/C classification** applied to every relationship.
3. **A decision on the Membership vs. Affiliation ambiguity**, backed by evidence from at least three organization types.
4. **Scenario-based validation** of the foundational specifications.

Do not derive bounded contexts, aggregates, or tenancy models until the relationship catalogue is validated. The relationship catalogue is the foundation; everything else is derived from it.

The immediate next response should produce R-05 through R-13 and a first falsification attempt on R-01 through R-04.
#
# ######################### 
# review . 
# Principal review: Cross-Domain Relationship Specification v0.1

I have reviewed the attached document. My assessment is that we have now reached the point where relationship modelling—not technology selection or implementation—is the central architectural task. The document has correctly identified the distinction between direct, derived, and policy-authorized relationships and exposed a fundamental ambiguity: whether organizational-unit affiliation is part of membership or a separate business relationship.&#x20;

Eingefügter Text.txt



I would proceed with the proposed R-05 through R-13 specifications, but with one important refinement: we must not confuse a relationship's business meaning with its technical representation.

For example, an affiliation may be a direct business fact, but whether it is stored as a separate entity, embedded in an aggregate, or represented in a database table is a separate design decision.

## 1. My review of the existing R-01 through R-04

| Relationship                     | Principal review                                                                                                                                                                                                       |
| -------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| R-01 Person ↔ Member             | Keep as a hypothesis. The one-member-record-per-organization invariant requires validation, particularly where duplicate registration and identity privacy matter.                                                     |
| R-02 Member ↔ Membership         | The distinction is useful. However, the proposed lifecycle combines application, approval, activation, suspension, and termination. We must verify which are actual states and which are separate decisions or events. |
| R-03 Membership ↔ MembershipType | A membership classification is useful, but one type per membership is not universal. Versioning the type definition and preserving the type applicable at a particular time are different requirements.                |
| R-04 Member ↔ OrganizationalUnit | This is the highest-priority unresolved relationship. An explicit affiliation is expressive, but we must validate its necessity and lifecycle against actual organizational scenarios.                                 |

A further correction: ending an affiliation or dissolving a unit should not automatically terminate a membership. These relationships have different lifecycles unless an explicit business rule connects them.

## 2. Principal architectural correction: refine the A/B/C taxonomy

The attached document treats Direct, Derived, and Policy-authorized as three relationship types. I recommend refining this classification before applying it to the entire catalogue.

They are not entirely mutually exclusive:

- A committee appointment can be a direct business fact.
- Forum access can be a derived authorization decision based on that appointment.
- The appointment itself may have been granted only after a policy-authorized approval.

Therefore, we should classify relationships along two separate dimensions.

### Dimension 1 — How is the relationship established?

- Explicitly recorded business fact.
- Derived from other authoritative facts.
  ### Dimension 2 — What governs it?
- Unconditional integrity constraint.
- Configurable organizational policy.
- Organization-specific rule.
- External legal or regulatory constraint, where applicable.

Authorization should then be modelled as a separate decision: given the facts, policy, actor, resource, and requested action, is the operation permitted?

This refinement prevents us from classifying a relationship as “policy-authorized” when it is actually an explicit business fact created through an authorized process.

## 3. Cross-Domain Relationship Specification v0.1 — R-05 through R-13

The following specifications are provisional architectural hypotheses. They extend the attached catalogue; they are not approved business rules.&#x20;

Eingefügter Text.txt



### R-05 — OrganizationalUnit ↔ OrganizationalUnit

Explicit structural relationship

Business meaning: One organizational unit is structurally related to another, for example a local branch belonging to a regional branch.

| Attribute           | Provisional specification                                                                                |
| ------------------- | -------------------------------------------------------------------------------------------------------- |
| Representation      | Explicit structural association; potentially temporal                                                    |
| Cardinality         | A unit may have zero or one parent in a tree-based model; a parent may have many children                |
| Ownership           | Organization Structure                                                                                   |
| Lifecycle           | Created, moved, reorganized, dissolved                                                                   |
| History             | Preserve structural changes where audit or historical reporting requires them                            |
| Invariant candidate | A unit cannot be its own ancestor                                                                        |
| Policy variation    | Multiple parents or cross-organization affiliations may be permitted by particular organizational models |
| Dependents          | Governance, Geography, Communication, reporting                                                          |

Falsification scenario: A federation allows one local chapter to participate in two regional structures for different purposes. A strict single-parent tree would fail to represent that structure.

Principal decision: Do not assume that all organizational relationships form a single tree. Distinguish structural parentage from affiliations and functional relationships.

### R-06 — Committee ↔ OrganizationalUnit

Explicit governance assignment

Business meaning: A committee governs, represents, advises, or serves an organizational unit.

| Attribute           | Provisional specification                                                                                            |
| ------------------- | -------------------------------------------------------------------------------------------------------------------- |
| Representation      | Explicit assignment with a defined relationship kind                                                                 |
| Cardinality         | Potentially many-to-many                                                                                             |
| Ownership           | Governance owns the committee and its governance assignment; Organization Structure owns unit identity and hierarchy |
| Lifecycle           | Established, assigned, changed, ended                                                                                |
| History             | Preserve the units governed and the period of responsibility                                                         |
| Invariant candidate | An assignment must reference valid entities within an allowed organizational scope                                   |
| Policy variation    | A committee may govern one unit, multiple units, or an entire organization                                           |
| Dependents          | Authorization, Communication, reporting                                                                              |

Falsification scenario: A national finance committee oversees several regional units without being a child of any one of them.

Principal decision: Distinguish `governs`, `represents`, `advises`, and `serves`. They should not all be encoded as an undifferentiated association.

### R-07 — Member ↔ CommitteeAppointment

Explicit participation fact

Business meaning: A person represented by a member record is formally appointed or otherwise authorized to participate in a committee.

| Attribute           | Provisional specification                                                                      |
| ------------------- | ---------------------------------------------------------------------------------------------- |
| Representation      | Appointment or participation record with stable identity if independently managed              |
| Cardinality         | A member may hold multiple appointments; a committee may have multiple appointees              |
| Ownership           | Governance                                                                                     |
| Lifecycle           | Proposed, approved where required, active, suspended, ended, revoked; exact states remain open |
| History             | Preserve appointment history and decision provenance                                           |
| Invariant candidate | Each appointment identifies one committee and one participant                                  |
| Policy variation    | Membership prerequisite, election or appointment method, approval process, acting appointments |
| Dependents          | Authorization, Communication, governance reporting                                             |

Falsification scenario: An external adviser participates in a committee without holding organizational membership.

Principal decision: Separate appointment eligibility from appointment existence. Whether a participant must be an active member is a policy question, not a universal invariant.

### R-08 — CommitteeAppointment ↔ Role

Explicit role assignment

Business meaning: A role describes the responsibility or position held through an appointment.

| Attribute           | Provisional specification                                                                           |
| ------------------- | --------------------------------------------------------------------------------------------------- |
| Representation      | Role assignment; potentially an entity if independently managed or historically tracked             |
| Cardinality         | One appointment may carry one or several roles; a role definition may be used in many appointments  |
| Ownership           | Governance owns organizational positions; Identity & Access owns application permission definitions |
| Lifecycle           | Assigned, changed, delegated where permitted, ended                                                 |
| History             | Preserve role assignment periods and changes                                                        |
| Invariant candidate | A role assignment must be valid within the relevant committee and organizational scope              |
| Policy variation    | Co-chairs, acting officers, shared responsibilities, delegation                                     |
| Dependents          | Authorization, Communication, reporting                                                             |

Falsification scenario: Two people are jointly appointed as co-chairs. A rule requiring exactly one active chairperson would reject a valid organizational arrangement.

Principal decision: Do not model a business role as an application permission. Delegation and acting authority also need explicit semantics, including scope and expiry.

### R-09 — CommitteeAppointment ↔ Mandate

Temporal authority

Business meaning: A mandate defines the period and scope during which an appointment carries organizational authority.

| Attribute           | Provisional specification                                                         |
| ------------------- | --------------------------------------------------------------------------------- |
| Representation      | Temporal value object or independently identified mandate, depending on lifecycle |
| Cardinality         | An appointment may have one or more successive mandates                           |
| Ownership           | Governance                                                                        |
| Lifecycle           | Granted, effective, extended, suspended, expired, revoked                         |
| History             | Preserve the mandate and all material changes                                     |
| Invariant candidate | Where both endpoints exist, the end must follow the start                         |
| Policy variation    | Fixed-term, indefinite, renewable, acting, or immediately revocable mandates      |
| Dependents          | Authorization, Communication, audit                                               |

Falsification scenario: A committee officer is reappointed for a new term while the same person and committee remain unchanged.

Principal decision: Distinguish the appointment relationship from its authority period. A role title alone must not establish that authority is currently valid.

### R-10 — Committee ↔ GeographicJurisdiction

Explicit territorial assignment

Business meaning: A committee has responsibility for a defined geographical area.

| Attribute           | Provisional specification                                                                                         |
| ------------------- | ----------------------------------------------------------------------------------------------------------------- |
| Representation      | Explicit jurisdiction assignment, potentially temporal                                                            |
| Cardinality         | Many-to-many is possible                                                                                          |
| Ownership           | Geography owns geographical definitions and boundaries; Governance owns the committee's responsibility assignment |
| Lifecycle           | Assigned, amended, replaced, ended                                                                                |
| History             | Preserve the effective jurisdiction for historical decisions and reporting                                        |
| Invariant candidate | The assigned jurisdiction must be valid for the relevant organization and period                                  |
| Policy variation    | Overlapping jurisdictions, multiple committees per territory, exceptional responsibilities                        |
| Dependents          | Governance, Communication, reporting, authorization                                                               |

Falsification scenario: Two committees share responsibility for the same geographical area but have different subject-matter responsibilities.

Principal decision: Keep geographical hierarchy separate from organizational hierarchy. A territory and the committee responsible for it are different domain concepts.

### R-11 — Member or Participant ↔ Forum

Participation and access

Business meaning: A participant can access or participate in a discussion forum under defined conditions.

| Attribute           | Provisional specification                                                                    |
| ------------------- | -------------------------------------------------------------------------------------------- |
| Representation      | Access decision; explicit subscription or invitation may be a separate business relationship |
| Cardinality         | Many-to-many                                                                                 |
| Ownership           | Communication owns forum participation and discussion rules; authorization evaluates access  |
| Lifecycle           | Eligible, granted, denied, suspended, revoked; persistent subscription is separate           |
| History             | Preserve relevant moderation and authorization audit records                                 |
| Invariant candidate | Access must satisfy applicable policy and current authorization requirements                 |
| Policy variation    | Organization-wide, unit-specific, committee-specific, invitation-only, public                |
| Dependents          | Identity & Access, Membership, Governance, Organization Structure                            |

Falsification scenario: A former committee chair retains ordinary member access to a general forum but loses access to a restricted committee forum.

Principal decision: Do not persist all effective access as independent membership facts. If access decisions are cached, define freshness, revocation, and failure behavior. Explicit subscriptions and invitations may still need their own lifecycle.

### R-12 — Forum ↔ ForumAccessPolicy

Policy association

Business meaning: A forum has rules that determine who may read, post, moderate, or administer it.

| Attribute           | Provisional specification                                                                               |
| ------------------- | ------------------------------------------------------------------------------------------------------- |
| Representation      | Policy definition and association, potentially versioned                                                |
| Cardinality         | A forum may have one or more composable policy rules; a reusable policy may apply to multiple forums    |
| Ownership           | Communication owns forum-specific rules; authorization evaluates them against authoritative facts       |
| Lifecycle           | Defined, published, revised, retired                                                                    |
| History             | Preserve relevant policy versions and decision evidence                                                 |
| Invariant candidate | Policy combinations must be interpretable and must not accidentally bypass mandatory access constraints |
| Policy variation    | Inherited restrictions, explicit exceptions, moderation rights                                          |
| Dependents          | Identity & Access, Membership, Governance, Organization Structure                                       |

Falsification scenario: A forum inherits a unit-level restriction but also has an explicit exception for invited external advisers.

Principal decision: Specify rule precedence and conflict resolution. “Most permissive rule wins” and “most restrictive rule wins” are different business policies; neither should be assumed universally.

### R-13 — Organizational Facts ↔ Authorization Decision

Derived decision

Business meaning: An authorization decision evaluates whether a particular actor may perform an action on a resource at a given time.

| Attribute           | Provisional specification                                                                                  |
| ------------------- | ---------------------------------------------------------------------------------------------------------- |
| Representation      | Decision produced by a policy evaluation; optionally audited or cached                                     |
| Cardinality         | One decision evaluates one requested action against relevant facts and policies                            |
| Ownership           | Identity & Access owns the authorization mechanism; domain contexts own their authoritative business facts |
| Lifecycle           | Evaluated per request; cached decisions have a defined validity period                                     |
| History             | Record consequential decisions where required for auditability                                             |
| Invariant candidate | A decision must use the correct organization scope and applicable policy                                   |
| Policy variation    | Role-based, attribute-based, relationship-based, or hybrid authorization                                   |
| Dependents          | Membership, Governance, Organization Structure, Geography, Communication                                   |

Falsification scenario: A user has a global application administrator permission but attempts to modify a committee in an organization outside the user's authorized scope.

Principal decision: Authentication proves an identity or account control; authorization determines whether the requested action is permitted. Neither alone establishes organizational membership or a committee mandate.

## 4. Falsification review of R-01 through R-04

A principal reviewer should actively try to break the proposed model, not merely complete its fields.

### R-01 — Person ↔ Member

Counterexample: The same person registers with two organizations using different names or contact details.

What breaks: Assuming one globally shared person record automatically establishes identity equivalence across organizations.

Required refinement: A global identity link must be justified by identity, privacy, and consent requirements. Organization-scoped member records should not require unrestricted sharing of personal data.

Status: Open.

### R-02 — Member ↔ Membership

Counterexample: A member's membership expires, and the person rejoins six months later.

What breaks: Treating Membership solely as a current status field loses the distinction between separate periods of belonging.

Required refinement: Define whether rejoining creates a new membership episode, reactivates an existing record, or follows a policy-specific process. Preserve historical periods.

Status: Episode-based modelling remains a useful hypothesis, not a universal rule.

### R-03 — Membership ↔ MembershipType

Counterexample: An honorary classification coexists with ordinary membership, or an organization retires a membership type that existing members still hold.

What breaks: A universal one-type-only constraint or destructive updates to historical type definitions.

Required refinement: Separate classification identity, applicable policy version, and the classification held by a particular membership. Investigate whether multiple concurrent classifications are meaningful.

Status: Open.

### R-04 — Member ↔ OrganizationalUnit

Counterexample: A person is an active national member but has no local branch; another person volunteers at a local branch without being a formal member.

What breaks: Treating organizational membership and unit affiliation as identical.

Required refinement: Model affiliation as a distinct candidate business relationship when it has its own lifecycle, authority, or history. Confirm whether non-members may be affiliated and whether members may belong to multiple units.

Status: Model B — separate affiliation — is the leading hypothesis to test, not yet an approved decision.

There is one additional structural correction: R-07 should ultimately be modelled as Participant ↔ CommitteeAppointment, where Participant can be an organization member or an eligible external person. The member record, when present, is a related fact. This avoids forcing external advisers into the membership domain merely to represent committee participation.

## 5. Consolidated relationship classification

This is the initial classification after applying the refined model. It is a working catalogue, not a final specification.

| ID   | Relationship                                                        | Establishment                            | Governing concern                 |
| ---- | ------------------------------------------------------------------- | ---------------------------------------- | --------------------------------- |
| R-01 | Person ↔ Member                                                     | Explicit                                 | Identity and organization scope   |
| R-02 | Member ↔ Membership                                                 | Explicit                                 | Membership lifecycle policy       |
| R-03 | Membership ↔ MembershipType                                         | Explicit assignment                      | Classification and versioning     |
| R-04 | Member ↔ OrganizationalUnit                                         | Explicit or derived; unresolved          | Affiliation policy                |
| R-05 | OrganizationalUnit ↔ OrganizationalUnit                             | Explicit                                 | Structural integrity              |
| R-06 | Committee ↔ OrganizationalUnit                                      | Explicit assignment                      | Governance authority              |
| R-07 | Participant ↔ CommitteeAppointment                                  | Explicit                                 | Eligibility and appointment rules |
| R-08 | CommitteeAppointment ↔ Role                                         | Explicit assignment                      | Position and delegation rules     |
| R-09 | CommitteeAppointment ↔ Mandate                                      | Explicit temporal fact                   | Authority validity                |
| R-10 | Committee ↔ GeographicJurisdiction                                  | Explicit assignment                      | Territorial responsibility        |
| R-11 | Participant ↔ Forum                                                 | Access decision or explicit subscription | Communication policy              |
| R-12 | Forum ↔ ForumAccessPolicy                                           | Explicit policy association              | Policy composition and precedence |
| R-13 | Actor + resource + action + facts + policy → authorization decision | Derived evaluation                       | Access control                    |

Notice that R-13 is not an ordinary association between two entities. It is a decision function. Treating it as such gives us a more precise foundation for authorization logic.

## 6. The central relationship to investigate next: Membership ↔ Affiliation

I recommend making R-04 the next focused research task because it influences the remaining relationship model.

Consider three alternatives:

### Model A — Affiliation is part of Membership

Simpler if membership always implies one organizational unit. Potentially restrictive for members-at-large, multi-unit participation, and volunteers.

### Model B — Affiliation is a separate relationship

Supports independent lifecycle, multiple affiliations, and historical changes. Introduces another business concept and its own rules.

### Model C — Affiliation is derived

Avoids a separate affiliation record if the organization can reliably derive affiliation from other authoritative facts. Fails if affiliation is independently assigned or changes independently.

My recommendation is to investigate Model B first, without prematurely approving it. We should compare all three models using real scenarios from at least three organization types and evaluate:

- Whether membership can exist without unit affiliation.
- Whether affiliation can exist without membership.
- Whether multiple affiliations are allowed.
- Whether affiliations have separate approval and termination rules.
- Whether affiliation affects committee eligibility, geographical responsibility, or forum access.
- Whether historical affiliation must remain valid after a unit is dissolved or reorganized.

We should not use a global membership rule to compensate for missing affiliation semantics.

## 7. Updated progress and prioritized TODOs

The relationship catalogue now covers R-01 through R-13 at a preliminary specification level. That does not mean the relationships are validated or the architecture is implementation-ready.

### P0 — Relationship integrity

- Refine the direct/derived classification and keep authorization as a separate decision function.
- Resolve Membership versus Affiliation using cross-organization scenarios and stakeholder evidence.
- Validate R-01 through R-04 with explicit counterexamples.
- Specify R-05 through R-13 using the complete relationship template.
- Correct R-07 to support eligible non-member participants where policy permits.
- Distinguish structural hierarchy, unit affiliation, committee governance, and geographical jurisdiction.

### P1 — Cross-domain consistency

- Define how membership changes affect committee eligibility without automatically destroying historical appointments.
- Define how appointment and mandate changes affect permissions and forum access.
- Specify policy precedence, authorization freshness, and revocation behavior.
- Define temporal and historical semantics for affiliations, appointments, roles, mandates, and jurisdiction assignments.
- Build normal, exceptional, historical, and adversarial scenarios for every relationship.

### P2 — Derive the architecture

- Derive bounded contexts from validated relationship ownership and lifecycle boundaries.
- Validate aggregate boundaries against transactional invariants and concurrent changes.
- Define cross-context contracts, events, and consistency expectations.
- Evaluate identity and tenancy options against the validated domain requirements.
- Derive acceptance criteria and tests from the approved invariants.
- Submit the relationship specification for independent review and ARB decisions before implementation.

Principal architect's conclusion: The most valuable progress is not the number of relationships documented. It is that we are distinguishing explicit business facts, derived facts, and authorization decisions. The next objective is to validate those distinctions against real organizational scenarios, beginning with Membership and Affiliation. Only then should we derive the final bounded contexts and implementation model.
# #################
# Principal Review and Next-Step Execution: Cross-Domain Relationship Specification v0.1

## 1. Principal Reviewer Assessment

### 1.1 What This Document Achieves

The Principal Review of the Cross-Domain Relationship Specification is the strongest artifact in the corpus so far. It does four things that materially advance the architecture:

- **It refines the A/B/C taxonomy into two orthogonal dimensions.** The original direct/derived/policy-authorized classification was useful but conflated establishment with governance. Splitting into "how is it established" (explicit vs. derived) and "what governs it" (invariant, configurable policy, organization-specific, legal) is a genuine architectural correction. It also correctly separates authorization as a decision function, not a relationship type.
- **It specifies R-05 through R-13 with the full relationship template.** The catalogue is no longer a table of hypotheses; it now has structure, ownership, lifecycle, invariants, and falsification scenarios.
- **It performs a real falsification review of R-01 through R-04.** The counterexamples (duplicate registration, rejoining, honorary + ordinary type, member-at-large, volunteer non-member) are the kind of adversarial thinking that prevents silent model defects.
- **It introduces R-13 as a decision function, not an association.** This is the most important conceptual move in the document. Authorization is not a relationship between two entities; it is a function of actor, resource, action, facts, and policy. Treating it as such gives the platform a precise foundation for access control.

### 1.2 Critical Strengths

1. **R-07 reframed as Participant ↔ CommitteeAppointment.** This is correct. The earlier model forced every committee participant to be a member, which is factually wrong for external advisers, invited experts, and statutory non-members.
2. **R-05 principal decision: do not assume a single tree.** Federations genuinely have multiple structural relationships (parent, affiliation, functional participation). Collapsing them into one hierarchy loses meaning.
3. **R-06 principal decision: distinguish governs, represents, advises, serves.** This prevents the platform from treating all committee-unit relationships as identical.
4. **R-08 principal decision: business role ≠ application permission.** This is the correct separation and must remain invariant across the architecture.
5. **R-09 principal decision: appointment ≠ mandate.** Distinguishing the relationship from its authority period is essential for acting officers, temporary appointments, and mandate expiry.
6. **R-10 principal decision: geographical hierarchy ≠ organizational hierarchy.** This is now firmly established.
7. **R-11 principal decision: do not persist all effective access as membership facts.** Caching is permitted only with defined freshness, revocation, and failure semantics.
8. **R-12 principal decision: specify rule precedence explicitly.** "Most permissive" and "most restrictive" are different business policies. Neither is universal.
9. **R-13 principal decision: authentication ≠ authorization ≠ organizational mandate.** Three different things that must never be conflated.

### 1.3 Critical Gaps and Corrections

The document is strong but has six remaining gaps that must be addressed before bounded-context derivation.

**Gap 1 — The two-dimension classification is introduced but not fully applied.**
Section 5's table applies the refined classification to R-01 through R-13, but the specifications in Section 3 still use the old single-dimension language in places. Every specification must be updated to reflect the two dimensions.

**Gap 2 — R-07's Participant concept is introduced but not defined.**
If Participant is broader than Member, it needs its own specification: what is a Participant, how is it created, how does it relate to Person, Member, and external actors? This is now a foundational concept and must be defined.

**Gap 3 — Affiliation (R-04) remains the central unresolved relationship.**
The document correctly identifies this and proposes Model B as the leading hypothesis, but the three models are not yet compared against real scenarios from at least three organization types. This comparison is the highest-priority research task.

**Gap 4 — Temporal semantics are mentioned but not formalized.**
R-09 (mandate), R-02 (membership episodes), R-04 (affiliation periods), R-10 (jurisdiction periods), and R-05 (structural changes over time) all require temporal reasoning. The document notes the requirement but does not yet provide a formal temporal model. This is where temporal logic becomes directly applicable.

**Gap 5 — Policy precedence (R-12) is flagged but not specified.**
The document correctly says "most permissive" and "most restrictive" are different policies. But it does not yet specify:
- How precedence is determined when multiple policies apply.
- Whether precedence is configurable per organization.
- How conflicts are detected at configuration time vs. evaluation time.
- How precedence interacts with mandatory constraints (e.g., a legal constraint that cannot be overridden).

**Gap 6 — No formal invariants have been stated as testable propositions.**
The specifications list "invariant candidates" but not yet as formal, testable propositions. For example, "A unit cannot be its own ancestor" is a candidate; the formal version is "For all units u, u is not in the transitive closure of u's parent relationship." This formalization is required before model checking or property-based testing.

### 1.4 Architectural Tension to Resolve

The central tension now is:

- **Relationship richness** — the domain has many relationships with subtle distinctions (governs/represents/advises/serves; role/mandate/appointment; member/participant/affiliate).
- **Model tractability** — too many distinctions make the model hard to reason about, test, and implement.

The resolution is not to reduce distinctions but to **classify them correctly** using the two-dimension taxonomy. Relationships that are explicit and invariant belong in the core model. Relationships that are derived belong in projections. Relationships that are policy-governed belong in policy evaluation. This keeps the core small while preserving expressiveness.

---

## 2. Next-Step Execution: Formalization, Affiliation Resolution, and Temporal Semantics

The next concrete deliverable is a three-part specification:

1. **Formal invariants** for R-01 through R-13, stated as testable propositions.
2. **Affiliation Model Comparison** (Models A, B, C) against real scenarios from at least three organization types.
3. **Temporal Semantics Specification** for time-bounded relationships.

I will produce each part with the rigor the document demands.

### 2.1 Formal Invariants for R-01 through R-13

Each invariant is stated as a formal proposition, with its scope (universal, configurable, organization-specific), its falsification condition, and its validation method.

**Notation:**
- `∀` — for all
- `∃` — there exists
- `∈` — is a member of
- `→` — implies
- `∧` — and
- `∨` — or
- `¬` — not
- `T(u)` — the transitive closure of parent relationship for unit u
- `active(x)` — x is in an active state
- `time(t)` — the temporal context

---

**R-01 — Person ↔ Member**

| Invariant | Formal Statement | Scope | Validation |
|---|---|---|---|
| I-01-1 | ∀ member m, ∃ exactly one person p such that m.person = p | Universal | Constraint |
| I-01-2 | ∀ person p, ∀ organization o, at most one member record m such that m.person = p ∧ m.organization = o | Configurable | Constraint |
| I-01-3 | ∀ member m, m.organization is defined | Universal | Constraint |

**Falsification:** An organization allows duplicate member records for the same person, or a member record exists without a person.

**Status:** Open — I-01-2 is a hypothesis, not a universal rule.

---

**R-02 — Member ↔ Membership**

| Invariant | Formal Statement | Scope | Validation |
|---|---|---|---|
| I-02-1 | ∀ membership ms, ∃ exactly one member m and one organization o such that ms.member = m ∧ ms.organization = o | Universal | Constraint |
| I-02-2 | ∀ member m, ∀ organization o, at most one active membership at any time t (unless policy allows overlap) | Configurable | Constraint + temporal |
| I-02-3 | ∀ membership ms, ms.startDate < ms.endDate when both exist | Universal | Temporal |
| I-02-4 | ∀ membership ms, state transitions follow the defined state machine | Universal | State machine |

**Falsification:** An organization allows overlapping memberships, or a membership has no member reference.

**Status:** Open — I-02-2 must be validated against dual-membership organizations.

---

**R-03 — Membership ↔ MembershipType**

| Invariant | Formal Statement | Scope | Validation |
|---|---|---|---|
| I-03-1 | ∀ membership ms, at all times t during ms, there is at least one applicable membership type | Universal | Temporal |
| I-03-2 | ∀ membership ms, the set of applicable types at time t is non-empty | Universal | Temporal |
| I-03-3 | ∀ membership type mt, mt is defined by exactly one organization | Universal | Constraint |
| I-03-4 | ∀ membership ms, if ms.type changes at time t, the previous type remains historically valid for [start, t) | Universal | Temporal + history |

**Falsification:** A membership exists without any type, or a type change erases historical type information.

**Status:** Open — I-03-2 is contested; some organizations may allow memberships without a type.

---

**R-04 — Member ↔ OrganizationalUnit**

| Invariant | Formal Statement | Scope | Validation |
|---|---|---|---|
| I-04-1 | ∀ affiliation a, ∃ exactly one member m and one unit u such that a.member = m ∧ a.unit = u | Universal (if Model B) | Constraint |
| I-04-2 | ∀ affiliation a, a.startDate < a.endDate when both exist | Universal (if Model B) | Temporal |
| I-04-3 | ∀ member m, ∀ organization o, the set of affiliations is consistent with the membership policy | Configurable | Policy evaluation |

**Falsification:** A person is affiliated with a unit without being a member (volunteer case), or a member is affiliated with multiple units (multi-unit case).

**Status:** Contested — depends on Model A/B/C resolution.

---

**R-05 — OrganizationalUnit ↔ OrganizationalUnit**

| Invariant | Formal Statement | Scope | Validation |
|---|---|---|---|
| I-05-1 | ∀ unit u, u ∉ T(u) (no unit is its own ancestor) | Universal | Graph property |
| I-05-2 | ∀ unit u, u has at most one parent at any time t (unless policy allows multiple) | Configurable | Constraint + temporal |
| I-05-3 | ∀ unit u, all ancestors of u belong to the same organization as u (unless cross-organization is policy-permitted) | Configurable | Constraint |

**Falsification:** A federation allows a local chapter to belong to two regional structures.

**Status:** Open — I-05-2 must be validated against federated structures.

---

**R-06 — Committee ↔ OrganizationalUnit**

| Invariant | Formal Statement | Scope | Validation |
|---|---|---|---|
| I-06-1 | ∀ committee c, ∀ unit u, if c governs u, then c and u belong to compatible organizational scopes | Universal | Constraint |
| I-06-2 | ∀ committee c, ∀ unit u, the relationship kind (governs/represents/advises/serves) is explicit and singular per (c, u, time) | Universal | Constraint + temporal |

**Falsification:** A committee has an undefined relationship with a unit.

**Status:** Open — the taxonomy of relationship kinds must be validated.

---

**R-07 — Participant ↔ CommitteeAppointment**

| Invariant | Formal Statement | Scope | Validation |
|---|---|---|---|
| I-07-1 | ∀ appointment a, ∃ exactly one participant p and one committee c such that a.participant = p ∧ a.committee = c | Universal | Constraint |
| I-07-2 | ∀ appointment a, a.participant ∈ {members of the organization} ∪ {eligible external participants} | Configurable | Policy evaluation |
| I-07-3 | ∀ appointment a, a.startDate < a.endDate when both exist | Universal | Temporal |

**Falsification:** An external adviser participates without membership, and the policy allows it.

**Status:** Open — I-07-2 depends on the Participant concept definition.

---

**R-08 — CommitteeAppointment ↔ Role**

| Invariant | Formal Statement | Scope | Validation |
|---|---|---|---|
| I-08-1 | ∀ role assignment ra, ∃ exactly one appointment a and one role r such that ra.appointment = a ∧ ra.role = r | Universal | Constraint |
| I-08-2 | ∀ appointment a, ∀ time t, the set of active roles is non-empty (unless policy allows role-less appointments) | Configurable | Temporal |
| I-08-3 | ∀ role r, r is defined within a specific organizational scope | Universal | Constraint |

**Falsification:** A co-chair arrangement requires two people in the same role, or a role exists without an appointment.

**Status:** Open — I-08-2 must be validated against co-chair and shared-role scenarios.

---

**R-09 — CommitteeAppointment ↔ Mandate**

| Invariant | Formal Statement | Scope | Validation |
|---|---|---|---|
| I-09-1 | ∀ mandate m, ∃ exactly one appointment a such that m.appointment = a | Universal | Constraint |
| I-09-2 | ∀ mandate m, m.startDate < m.endDate when both exist | Universal | Temporal |
| I-09-3 | ∀ appointment a, at any time t, at most one active mandate (unless policy allows overlap) | Configurable | Temporal |
| I-09-4 | ∀ mandate m, if m is suspended, m cannot authorize actions during the suspension period | Universal | Temporal + authorization |

**Falsification:** A mandate is active while its appointment is terminated, or two mandates overlap without policy permission.

**Status:** Open — I-09-3 must be validated against acting/co-chair scenarios.

---

**R-10 — Committee ↔ GeographicJurisdiction**

| Invariant | Formal Statement | Scope | Validation |
|---|---|---|---|
| I-10-1 | ∀ jurisdiction assignment ja, ∃ exactly one committee c and one jurisdiction j such that ja.committee = c ∧ ja.jurisdiction = j | Universal | Constraint |
| I-10-2 | ∀ jurisdiction j, j belongs to a defined organizational scope | Universal | Constraint |
| I-10-3 | ∀ jurisdiction j, the set of parent jurisdictions is acyclic | Universal | Graph property |
| I-10-4 | ∀ jurisdiction assignment ja, ja.startDate < ja.endDate when both exist | Universal | Temporal |

**Falsification:** A jurisdiction is assigned to a committee outside its organizational scope, or jurisdiction hierarchy contains a cycle.

**Status:** Open — I-10-3 must be validated against historical boundary changes.

---

**R-11 — Participant ↔ Forum**

| Invariant | Formal Statement | Scope | Validation |
|---|---|---|---|
| I-11-1 | ∀ access decision d, d is derived from (participant, forum, policy, facts) | Universal | Policy evaluation |
| I-11-2 | ∀ access decision d, if the underlying facts change, d must be re-evaluated within the freshness SLA | Configurable | Temporal + operational |
| I-11-3 | ∀ explicit subscription s, s.subscriber is a valid participant and s.forum is an active forum | Universal | Constraint |

**Falsification:** A participant retains access after their appointment ends, beyond the freshness SLA.

**Status:** Open — I-11-2 depends on the freshness SLA definition.

---

**R-12 — Forum ↔ ForumAccessPolicy**

| Invariant | Formal Statement | Scope | Validation |
|---|---|---|---|
| I-12-1 | ∀ forum f, the effective policy is a deterministic function of the applicable policy rules | Universal | Policy evaluation |
| I-12-2 | ∀ policy rule p, p has a defined precedence relative to other rules | Universal | Constraint |
| I-12-3 | ∀ policy rule p, if p is mandatory (legal or universal), it cannot be overridden by a more permissive rule | Universal | Constraint |

**Falsification:** Two policies produce contradictory decisions without a defined precedence.

**Status:** Open — I-12-2 and I-12-3 must be validated against real policy configurations.

---

**R-13 — Organizational Facts ↔ Authorization Decision**

| Invariant | Formal Statement | Scope | Validation |
|---|---|---|---|
| I-13-1 | ∀ decision d, d = evaluate(actor, action, resource, facts, policy) | Universal | Function definition |
| I-13-2 | ∀ decision d, d uses the correct organization scope | Universal | Constraint |
| I-13-3 | ∀ decision d, if d is cached, the cache has a defined validity period | Configurable | Operational |
| I-13-4 | ∀ decision d, d does not grant authority beyond what the underlying facts and policies permit | Universal | Constraint |

**Falsification:** A cached decision grants access after the underlying appointment is revoked, or a decision grants authority outside the actor's organizational scope.

**Status:** Open — I-13-3 and I-13-4 require operational validation.

---

### 2.2 Affiliation Model Comparison

The central unresolved relationship is R-04. Three models are possible. Each is evaluated against scenarios from three organization types.

**Organization types for comparison:**
- **Type 1 — Political party:** national, provincial, district, local committees; youth wing; specialist wings.
- **Type 2 — Red Cross:** national society, regional branches, local chapters, specialist units (youth, disaster response).
- **Type 3 — Federation:** autonomous member organizations, shared services, joint committees.

**Scenarios to test:**

| Scenario | Type 1 | Type 2 | Type 3 |
|---|---|---|---|
| Member-at-large (member without unit) | Party member with no local branch | National supporter with no local chapter | Individual member of federation |
| Multi-unit member | Member of local branch and youth wing | Member of local chapter and disaster response unit | Member of two member organizations |
| Volunteer non-member | Volunteer at local campaign office | Volunteer at local chapter without membership | Contractor at shared service |
| Independent affiliation | Local branch affiliation without national membership (rare) | Local chapter volunteer coordinator | Member organization staff |
| Historical affiliation | Past district secretary, now member-at-large | Former branch chair, now honorary | Former member organization representative |

**Model A — Affiliation is part of Membership**

| Criterion | Assessment |
|---|---|
| Member-at-large | Fails — membership requires a unit |
| Multi-unit member | Fails — one membership, one unit |
| Volunteer non-member | Fails — no membership means no affiliation |
| Independent affiliation | Fails |
| Historical affiliation | Possible if membership history is preserved |
| **Verdict** | **Rejected** — fails four of five scenarios |

**Model B — Affiliation is a separate relationship**

| Criterion | Assessment |
|---|---|
| Member-at-large | Supported — membership without affiliation |
| Multi-unit member | Supported — multiple affiliations |
| Volunteer non-member | Supported — affiliation without membership (if policy allows) |
| Independent affiliation | Supported |
| Historical affiliation | Supported — affiliations have their own lifecycle |
| **Verdict** | **Leading hypothesis** — supports all scenarios |

**Model C — Affiliation is derived**

| Criterion | Assessment |
|---|---|
| Member-at-large | Supported — no derivation source |
| Multi-unit member | Supported — multiple derivation sources |
| Volunteer non-member | Fails — no membership to derive from |
| Independent affiliation | Fails — no source |
| Historical affiliation | Fails — derived facts are not historically preserved by default |
| **Verdict** | **Rejected** — fails three of five scenarios |

**Recommendation:** Model B is the leading hypothesis. It must be validated against real organizational documents and stakeholder evidence before approval. The decision must be recorded by the ARB.

**Consequences of Model B:**
- Affiliation becomes a new entity with its own identity and lifecycle.
- Affiliation may have its own approval process, if the organization requires it.
- Affiliation may be a prerequisite for certain committee appointments.
- Affiliation may influence forum access, geographic responsibility, and reporting.
- Affiliation must be preserved historically.

**Open questions for Model B:**
- Can a person be affiliated without being a member? (Policy question)
- Can a member be affiliated with multiple units? (Policy question)
- Does affiliation require approval? (Policy question)
- Does affiliation end when membership ends? (Policy question — default is no, unless explicitly linked)
- Does affiliation end when the unit is dissolved? (Default is yes, but history must be preserved)

---

### 2.3 Temporal Semantics Specification

Five relationships are time-bounded: Membership (R-02), Affiliation (R-04), Appointment (R-07), Mandate (R-09), and Jurisdiction (R-10). A consistent temporal model is required.

**Temporal model:**

Every time-bounded relationship has:
- `validFrom` — the instant the relationship becomes valid
- `validUntil` — the instant the relationship ceases to be valid (nullable for open-ended)
- `recordedAt` — the instant the fact was recorded in the system
- `supersededBy` — reference to the relationship that supersedes this one, if any

**Temporal invariants:**

| Invariant | Formal Statement |
|---|---|
| T-1 | ∀ relationship r, r.validFrom < r.validUntil when both exist |
| T-2 | ∀ relationship r, r.recordedAt ≤ now |
| T-3 | ∀ relationship r, if r.supersededBy = r', then r.validUntil = r'.validFrom |
| T-4 | ∀ relationship r, if r is terminated, r.validUntil is defined |
| T-5 | ∀ relationship r, the state of r at time t is determinable from its history |

**Temporal operators:**

- `active(r, t)` — r is valid at time t
- `overlaps(r1, r2)` — r1 and r2 have overlapping validity periods
- `precedes(r1, r2)` — r1.validUntil ≤ r2.validFrom
- `during(r, t1, t2)` — r is valid throughout [t1, t2]

**Temporal queries:**

- What was the state of a relationship at time t?
- Which relationships were active during [t1, t2]?
- Did two relationships overlap?
- Did a relationship change state during a period?

**Temporal logic:**

For critical rules, temporal logic (LTL or CTL) can express constraints such as:

- "Once a mandate expires, it cannot become active again without a new appointment."
- "If an affiliation is suspended, it cannot be reactivated without an explicit reinstatement event."
- "A membership cannot be terminated and reactivated without a new approval event, unless policy allows."

**Formalization:**

```
G (mandate.expired → ¬mandate.active U newAppointment)
G (affiliation.suspended → ¬affiliation.active U reinstatement)
G (membership.terminated → ¬membership.active U (newApproval ∨ policy.reactivationAllowed))
```

**Validation method:**

- State machine models for each relationship lifecycle.
- Model checking for critical constraints (where state space is bounded).
- Property-based testing for temporal invariants.
- Scenario testing for historical correctness.

---

### 2.4 Policy Precedence Specification

For R-12, precedence must be explicitly defined. The following model is proposed.

**Policy rule structure:**

```
PolicyRule {
  id
  scope: universal | organization | unit | forum
  effect: permit | deny
  priority: integer
  mandatory: boolean
  condition: logical formula over facts
}
```

**Precedence rules:**

1. **Mandatory rules override all others.** If a mandatory rule denies, the decision is deny.
2. **Among non-mandatory rules, priority determines precedence.** Higher priority wins.
3. **Among equal priority rules, explicit deny overrides permit.** This is the "deny wins" default.
4. **Organization-specific policy may set a different precedence model** (e.g., "permit wins" or "most permissive").
5. **Conflicts must be detectable at configuration time**, not only at evaluation time.

**Conflict detection:**

- Two rules conflict if they apply to the same scope and produce opposite effects for the same condition.
- Conflicts are reported to administrators when policies are configured.
- Unresolvable conflicts block policy activation.

**Formalization:**

```
effectiveDecision(f, p, t) =
  if ∃ mandatory deny rule → deny
  else if ∃ rule with highest priority → its effect
  else if ∃ deny rule → deny
  else → permit
```

**Validation:**

- Constraint satisfaction to detect conflicts.
- Model checking to verify that no policy configuration produces contradictory decisions.
- Scenario testing with real policy configurations from at least three organization types.

---

## 3. Machine Learning and Formal Logic Contributions

### 3.1 Machine Learning (Assistive Only)

| Use Case | Method | Output | Human Oversight |
|---|---|---|---|
| Vocabulary clustering | Embedding + clustering | Grouped terms across organizations | Required |
| Rule extraction | NLP + LLM | Candidate rules from documents | Required |
| Contradiction detection | Logical + statistical | Flagged conflicting rules | Required |
| Anomaly detection | Statistical + ML | Flagged unusual patterns | Required |
| Synthetic scenarios | Generative models | Edge-case scenarios | Required |

ML outputs are hypotheses. They must never make authoritative decisions about membership, affiliation, appointment, mandate, jurisdiction, or access.

### 3.2 Formal Logic (Proportionate Use)

| Technique | Applied To | Justification |
|---|---|---|
| State machines | Membership, Affiliation, Appointment, Mandate lifecycles | Well-defined states and transitions |
| First-order logic | Cardinality, ownership, structural constraints | Precise and testable |
| Graph theory | OrganizationalUnit hierarchy, Jurisdiction hierarchy | Acyclicity, parent uniqueness |
| Temporal logic | Mandate validity, membership periods, affiliation durations | Time-bounded relationships |
| Constraint satisfaction | Policy configurations | Conflict detection |
| Model checking | Critical lifecycle rules | Bounded state spaces |
| Proof theory | Authorization decisions | Verifiable audit trails |

Formal methods are applied only where the state space and assumptions can be specified adequately. They prove correctness relative to the model, not correctness relative to reality.

---

## 4. How Far We Are and What Remains

### 4.1 Current Status

| Area | Status |
|---|---|
| Phase 1 — Conceptual brainstorming | Foundation established (~25%) |
| Gate 0 — Corpus verification | Completed |
| Gate 1 — Research plan | Completed |
| Cross-domain focus | Completed |
| Cross-Domain Relationship Specification v0.1 | Completed (R-01 to R-13) |
| Formal invariants | **Started in this response** |
| Affiliation Model Comparison | **Started in this response** |
| Temporal Semantics Specification | **Started in this response** |
| Policy Precedence Specification | **Started in this response** |
| Participant concept definition | Not started |
| Bounded-context derivation | Not started |
| Aggregate validation | Not started |
| Context map | Not started |
| Identity model decision | Not started |
| Tenancy model decision | Not started |
| ML scope approval | Not started |
| ARB-1 approval | Pending |
| ARB-2 architecture approval | Not started |
| ARB-3 implementation authorization | Not started |

**Overall assessment:** We are approximately **30–35%** of the way toward an implementable organizational domain model. The relationship catalogue is specified; formal invariants are stated; the central ambiguity (Affiliation) has a leading hypothesis; temporal semantics and policy precedence are drafted.

### 4.2 Remaining TODOs (Prioritized)

**P0 — Resolve the Affiliation ambiguity**
- Define the Participant concept formally.
- Validate Model B against real scenarios from at least three organization types.
- Obtain ARB decision on Model A/B/C.
- Document rationale, consequences, and open questions.

**P0 — Formalize all invariants**
- State every invariant as a testable proposition.
- Apply the two-dimension taxonomy to every specification.
- Update R-01 through R-13 with the refined classification.

**P0 — Validate temporal semantics**
- Model state machines for Membership, Affiliation, Appointment, Mandate.
- Apply temporal logic to critical constraints.
- Test with property-based testing and model checking.

**P1 — Specify policy precedence**
- Formalize policy rule structure.
- Define precedence rules.
- Implement conflict detection.
- Validate with real policy configurations.

**P1 — Define cross-domain consistency rules**
- How do membership changes affect committee eligibility?
- How do appointment changes affect permissions and forum access?
- What is the freshness SLA for cached authorization decisions?
- What happens to affiliations when a unit is dissolved?

**P1 — Build the scenario catalogue**
- Normal scenarios for each relationship.
- Edge cases (multi-unit, member-at-large, volunteer non-member).
- Historical scenarios (rejoining, boundary changes, mandate expiry).
- Adversarial scenarios (policy conflicts, unauthorized access, stale cache).

**P2 — Derive bounded contexts**
- Group relationships by ownership and lifecycle.
- Identify context boundaries from validated relationship clusters.
- Validate each context against at least two scenarios.
- Document responsibilities, aggregates, contracts, events.

**P2 — Validate aggregates**
- Determine aggregate boundaries from transactional invariants.
- Decide Affiliation, CommitteeAppointment, MembershipType, Jurisdiction aggregate status.
- Validate with concurrency scenarios.

**P2 — Produce the context map**
- Map upstream/downstream dependencies.
- Define integration patterns and contracts.
- Specify consistency expectations.

**P3 — Decide identity and tenancy models**
- Compare global Person vs. organization-scoped Member records.
- Compare shared schema, schema-per-tenant, database-per-tenant.
- Base decisions on requirements, not preference.
- Obtain ARB approval.

**P3 — Prepare architecture decision package**
- Assemble glossary, relationships, invariants, events, scenarios, contexts, aggregates, map, tenancy, ML scope.
- Mark every decision as proposed / accepted / rejected / unresolved.
- Submit for independent ChatGPT review.
- Obtain ARB-2 architecture approval.

**P4 — Implementation planning (post-approval)**
- Produce dependency-ordered implementation plan.
- Obtain ARB-3 implementation authorization.
- Implement incrementally with tests and evidence.

### 4.3 Principal Recommendation

The next concrete deliverable is the **Participant concept definition and the Affiliation Model Validation**, because:

1. Participant is now a foundational concept (introduced in R-07) and must be defined before R-07 can be validated.
2. Affiliation is the central unresolved relationship and blocks bounded-context derivation.
3. Both require evidence from real organizational documents and stakeholder validation.

Once these are resolved, the relationship catalogue is complete enough to derive bounded contexts. Only then should aggregate validation, context mapping, and tenancy decisions proceed.

Do not derive bounded contexts from the current catalogue until Affiliation is resolved. The bounded-context boundaries depend on whether Affiliation is part of Membership, a separate relationship, or a derived projection. Choosing prematurely will produce a context map that must be redrawn.

**Authorization required:** ARB approval to proceed from relationship specification to bounded-context derivation, contingent on Affiliation resolution.
