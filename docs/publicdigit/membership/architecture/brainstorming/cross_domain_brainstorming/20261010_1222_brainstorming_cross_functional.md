# Principal DDD review: Cross-domain relationships v0.2

I have read the attached Principal Review and Next-Step Execution: Cross-Domain Relationship Specification v0.1. The document is a meaningful step toward a rigorous organizational digitalization platform, but it is not yet an approved domain model or an implementation-ready specification.&#x20;

Eingefügter Text.txt



My next step is to challenge the current model, clarify the concepts that determine the architecture, and turn the unresolved questions into testable requirements.

The platform must support different organizational forms—including political parties, associations, federations and humanitarian organizations—without forcing them all into the same organizational rules.

## 1. Where we stand

Established foundation

Thirteen candidate cross-domain relationships, the separation of roles from permissions, the distinction between appointments and mandates, and the separation of organizational and geographical structures.

Needs further validation

The meaning of Participant and Affiliation, temporal semantics, policy precedence, and which rules are universal versus configurable.

Must remain undecided for now

Final bounded contexts, aggregate boundaries, identity sharing, tenancy, technology-specific implementation and production authorization design.

The document estimates progress at 30–35%, but that is a qualitative estimate, not a measured completion metric. More importantly, its scenario comparison is a useful hypothesis exercise—not yet evidence from real organizational statutes, policies or stakeholder decisions.&#x20;

Eingefügter Text.txt



## 2. Principal-review findings: five corrections before proceeding

| Finding           | Principal-review conclusion                                                                                                                                                        |
| ----------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Participant       | It is not yet proven that `Participant` should be a standalone entity. It may be a role played by a person or other eligible party in a specific governance context.               |
| Affiliation       | Model B is the strongest current candidate, but the examples risk conflating membership in a unit with volunteering, employment and other forms of participation.                  |
| Temporal model    | Valid time and recorded time are valuable, but `supersededBy` does not always mean the old relationship ends exactly when the new one begins. Gaps and overlaps may be legitimate. |
| Formal invariants | Several proposed rules are hypotheses, not universal truths. They must not become database constraints or code until their scope is established.                                   |
| Policy precedence | The proposed precedence algorithm is incomplete. Mandatory constraints, policy scope, rule applicability, conflicts and default behavior need a precise evaluation model.          |

These are not merely documentation details. Each can change the domain model, authorization behavior and data migration strategy.

## 3. Next-step deliverable A: Define Participant correctly

The most important modeling question is whether `Participant` is an entity, an aggregate, a relationship, or simply a domain role.

My current recommendation is not to create a global Participant entity prematurely.

### 3.1 Separate the concepts

Person

The human being. A person may have relationships with multiple organizations, subject to identity and privacy rules.

May be represented within an organization

Member

An organization-scoped representation of a person. Membership records the person's membership relationship and its history.

CommitteeAppointment

A governance fact connecting an eligible appointee to a committee for a defined period. The appointee may be a member or an eligible external person.

Can confer a time-bounded role or mandate

Authorization decision

Determines whether an authenticated actor may perform a particular action on a resource under applicable facts and policies.

These concepts answer different business questions:

- Person: Who is the human being?
- Member: What is the person's relationship with this organization?
- Participant: In what capacity may the person take part in a particular activity?
- CommitteeAppointment: What appointment has been established?
- Role: What organizational responsibility has been assigned?
- Mandate: What authority is valid during which period?
- Permission: Which application action is allowed?
- Account / actor: Which authenticated identity is making a request?

An account is not necessarily a person, and a person is not necessarily a member. Likewise, being a committee member does not automatically grant every permission associated with committee work.

### 3.2 Proposed definition

Participant — provisional domain term

A participant is a person or other explicitly eligible party taking part in a defined organizational activity in a particular capacity.

This is a conceptual definition, not yet a decision to create a database table or aggregate.

We must establish whether the platform needs to support only natural persons or also organizations as participants. The answer affects how committee appointments and external representation should be modeled.

### 3.3 Test the definition against real cases

| Scenario                                             | Required model behavior                                                                                                      |
| ---------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------- |
| Ordinary party member appointed to a committee       | Appointment may reference the member's organizational identity.                                                              |
| External legal adviser                               | Appointment must not require inventing a membership record.                                                                  |
| Member whose membership has expired                  | Appointment eligibility must be evaluated under the applicable rules; it must not be assumed automatically valid or invalid. |
| Person representing a federation member organization | The model must distinguish the representative from the represented organization.                                             |
| Former committee chair                               | Historical appointment and mandate information must remain auditable after authority ends.                                   |

Decision required: Is `Participant` a reusable domain entity, a role assigned to a person/party, or a term used only in the governance context?

My initial preference is to treat it as a domain role or reference to an eligible party, unless evidence shows that it needs an independent lifecycle and identity.

## 4. Next-step deliverable B: Resolve Affiliation without conflating relationships

The attached document recommends Model B: Affiliation as a separate relationship with its own lifecycle. It handles member-at-large, multiple units, and historical affiliations better than the alternatives.&#x20;

Eingefügter Text.txt



I agree that Model B should remain the leading hypothesis. However, I would refine it before approval.

### 4.1 The hidden problem in Model B

Consider these four relationships:

- A member belongs to a local party branch.
- A volunteer helps at a Red Cross local chapter.
- An employee works for a federation's shared-services organization.
- A person serves as an adviser to a committee.

All four connect a person to an organizational structure, but they do not mean the same thing.

If we model all four as a generic `Affiliation`, the relationship becomes semantically weak. Its lifecycle, eligibility rules, legal implications and effects on permissions may differ significantly.

### 4.2 Recommended candidate model

OrganizationalUnit

A branch, district, local chapter, youth wing, department or another defined organizational unit.

MemberUnitAffiliation

Represents a member's affiliation with one or more organizational units, if allowed by policy.

Participation / Assignment

Represents other relationships, such as volunteering, employment or external advisory participation, when the business requires them.

Candidate model only. These concepts may belong to different bounded contexts and must not be merged simply because they share similar attributes.

This gives us an important design principle:

Share structural concepts where the meaning is genuinely shared; do not share a generic relationship merely because its database columns look similar.

### 4.3 Affiliation decision matrix

| Business case                                 | Membership required?               | Independent lifecycle?                        | Initial modeling direction              |
| --------------------------------------------- | ---------------------------------- | --------------------------------------------- | --------------------------------------- |
| Party member without a local branch           | No unit affiliation required       | Membership continues independently            | Membership without affiliation          |
| Member of a local branch and youth wing       | Depends on organization rules      | Potentially yes                               | Multiple typed unit affiliations        |
| Volunteer who is not a member                 | Not necessarily                    | Likely yes                                    | Volunteer participation or assignment   |
| Committee representative                      | Not necessarily a unit affiliation | Appointment has its own lifecycle             | Committee appointment                   |
| Staff member of a federation's shared service | Not necessarily                    | Employment relationship has its own lifecycle | Employment or organizational assignment |

These are test scenarios, not verified universal facts. The actual organization may define different eligibility rules.

### 4.4 Candidate invariants

We should initially test the following propositions:

1. A membership can exist without a unit affiliation.
2. A unit affiliation can have a lifecycle independent of membership only where the relevant relationship type and policy permit it.
3. Ending a membership must not silently erase historical affiliation or appointment records.
4. An affiliation to a dissolved unit must preserve history; its current status and downstream effects must be explicitly determined.
5. An affiliation must not, by itself, grant committee authority or application permissions unless a policy explicitly defines that consequence.
6. Two affiliations with overlapping validity periods are allowed unless a specific rule prohibits the overlap.

Each proposition should have a counterexample search, a classification as universal/configurable/organization-specific, and a corresponding test.

Next research priority: validate these scenarios against actual rules from at least three organizational forms—such as a political party, a membership association or humanitarian organization, and a federation. Until then, Model B is a recommendation, not an approved decision.

## 5. Next-step deliverable C: Repair the formal logic and temporal model

The file's formalization is a good start, but some statements should not be copied directly into implementation.

### 5.1 Distinguish validity from knowledge

The proposed temporal model includes `validFrom`, `validUntil`, `recordedAt` and `supersededBy`.&#x20;

Eingefügter Text.txt



I recommend two explicit time dimensions:

- Valid time: When was this fact true in the organization?
- Transaction time: When did the system record or revise its knowledge of that fact?

For example, a committee appointment might be entered on 15 October but be legally effective from 1 October. The system must be able to answer both:

- What appointment was valid on 10 October?
- What did the system know about that appointment on 10 October?

These are different queries.

A better provisional model is:

```
TemporalRelationship
  id
  validFrom
  validUntil          // exclusive end, nullable
  recordedFrom
  recordedUntil       // transaction-history end, nullable
  status
  relationshipType
  organizationScope
```

This is a conceptual model, not a mandated schema. It also assumes that `validUntil` is exclusive; the convention must be agreed and used consistently.

### 5.2 Correct the invariant assumptions

| Existing proposal                                                  | Principal-review correction                                                                                                                   |
| ------------------------------------------------------------------ | --------------------------------------------------------------------------------------------------------------------------------------------- |
| A superseded relationship ends exactly when its replacement starts | Not universal. Replacement may leave a gap or overlap the old period if the domain allows it.                                                 |
| Every membership must always have a membership type                | Unverified. Some organizations may permit an unclassified membership or may classify membership through a separate policy.                    |
| Every appointment must have a role                                 | Unverified. A provisional, advisory or otherwise role-less appointment may be legitimate.                                                     |
| A committee–unit relationship has one relationship kind at a time  | Potentially too restrictive. A committee could represent and advise the same unit concurrently if those are distinct, valid responsibilities. |
| A cached authorization decision only needs a validity period       | Incomplete. It also needs invalidation/revocation semantics and defined behavior when authoritative facts are unavailable.                    |

The rule is not to weaken invariants indiscriminately. It is to state each rule at the correct level and validate it against real cases.

### 5.3 Make formal propositions executable

For example, if a particular organizational hierarchy must be acyclic:

\\[ \forall u \in U,\quad u \notin Ancestors(u) \\]

That is a graph invariant that can be tested.

If appointments must not authorize actions after their mandate expires:

\\[ \forall a,t,\quad \neg ValidMandate(a,t) \Rightarrow \neg AuthorizedByMandate(a,t) \\]

This proposition concerns authority derived from a mandate; it does not imply that the same actor cannot have another independent authorization.

For a membership episode, we might require:

\\[ validFrom < validUntil \\]

when both boundaries exist. We should not, however, impose a global “only one active membership per person” rule unless the relevant organization has established that rule.

The appropriate progression is:

1. Define the business proposition in plain language.
2. Identify its scope and assumptions.
3. Express it formally.
4. Generate counterexamples.
5. Implement a test.
6. Use model checking when the state space is bounded and the rule's risk justifies it.

## 6. How the relationships support the platform's authorization model

The cross-domain relationships are valuable because they let the platform derive access decisions from authoritative facts rather than from manually maintained permission lists.

For example, a person requests permission to publish an announcement in a district committee forum.

The system may need to evaluate:

- Is the requesting actor authenticated?
- Which organization does the request belong to?
- Is the forum associated with the relevant committee or unit?
- Does the person have an applicable appointment?
- Is the mandate currently valid?
- Does the relevant role authorize publication?
- Does the forum policy permit this action?
- Is any mandatory restriction applicable?
- Are the facts sufficiently fresh to make a safe decision?

The conceptual decision function is:

\\[ Decision = Evaluate(Actor, Action, Resource, Facts, Policy, Time) \\]

The result should be explicit—such as `Permit` or `Deny`—with a reason and the applicable policy/version recorded where required.

Important: a role name is not itself a permission, and a valid appointment does not necessarily grant unrestricted access. The policy must define the relationship between them.

### Proposed policy evaluation sequence

1. Resolve the authenticated actor and organization scope.
2. Obtain the authoritative appointment, mandate, membership and other relevant facts.
3. Determine which policy rules apply to the action and resource.
4. Apply mandatory constraints and the approved precedence model.
5. Return the decision, its reason and the required audit evidence.
6. Define caching and revocation behavior separately, including the response to stale or unavailable facts.

The precedence algorithm in the file should not yet be treated as final. For instance, a configurable priority rule must not accidentally override a legal or otherwise non-overridable restriction. We need an explicit model for rule scope, applicability, conflict and default-deny or other approved default behavior.

## 7. Where the election process fits

Your stated final goal also mentions deriving a robust election process. The attached file, however, concentrates on organizational cross-domain relationships rather than specifying the election lifecycle itself.

I recommend treating the election process as a downstream business capability that consumes these foundational relationships, while we validate the relationships needed for it.

For example, an election may need to determine:

- which organizational scope is holding the election;
- which committee or authority is responsible for administering it;
- which membership facts and eligibility rules apply;
- who is authorized to nominate, administer or observe;
- which geographical jurisdiction is covered;
- which discussions and announcements are accessible to which participants;
- what evidence must be retained for an auditable decision.

These are examples of requirements to investigate, not approved election rules. We should not assume that committee membership, ordinary membership or forum access automatically determines voting eligibility. Voting eligibility, ballot secrecy, counting and dispute handling require their own explicit requirements and controls.

## 8. Optimized execution plan

I would revise the TODO order slightly. The attached document recommends Participant definition and Affiliation validation first; I agree, but the invariant review should happen alongside that work rather than be treated as a completely separate later activity.

## P0

1\. Resolve the semantic foundation

Define Person, Member, Participant, Membership, Affiliation, Appointment, Role, Mandate, Account and Actor. Distinguish actual domain entities from roles, relationships and decisions.

## P0

2\. Validate Affiliation

Test member-at-large, multi-unit membership, volunteers, external advisers and historical changes against real organizational evidence. Record the chosen model and exceptions.

## P0

3\. Establish a testable rule catalogue

For R-01–R-13, record each rule's statement, scope, evidence, counterexample, formal proposition, test method and decision status.

## P1

4\. Specify temporal and authorization semantics

Define valid-time and transaction-time conventions, state machines, rule precedence, policy conflict detection, freshness and revocation behavior.

## P1

5\. Derive bounded contexts from evidence

Use fact ownership, lifecycle ownership, invariants and consistency needs to derive context boundaries—not the other way around. Then identify aggregates and context-map relationships.

## P2

6\. Validate election and platform requirements

Derive election requirements from the foundational model, then assess identity, tenancy, audit, privacy and implementation architecture against explicit scenarios.

### Where machine learning and formal methods help

- Machine learning / NLP: extract candidate terminology and rules from statutes, bylaws and policy documents; cluster similar terms across organizations; flag possible contradictions.
- Statistics: analyze actual membership, affiliation and appointment data for unexpected overlaps, missing relationships or anomalous state transitions. An anomaly is a signal for investigation, not proof of an error.
- Property-based testing: generate combinations of memberships, affiliations, appointments, dates and revocations to test invariants.
- Constraint solving / model checking: test policy conflicts and lifecycle edge cases after assumptions are explicit.
- Human governance: decide the actual meaning of the rules and approve policy choices. ML must not decide membership eligibility, appointments, voting rights or access autonomously.

## 9. Decision status and immediate next action

The current relationship catalogue remains provisional. In particular, the attached file records ARB-1 approval as pending, ARB-2 architecture approval as not started, and ARB-3 implementation authorization as not started.&#x20;

Eingefügter Text.txt



My recommendation is therefore to proceed with research and specification, not implementation.

The immediate deliverable should be a revised Cross-Domain Relationship Specification v0.2 containing:

1. A precise conceptual definition of Participant.
2. A refined Affiliation model that does not conflate unit affiliation with volunteering, employment or committee appointment.
3. A scenario-and-evidence matrix for at least three organizational forms.
4. Corrected formal invariants and temporal semantics.
5. An explicit list of unresolved decisions and their required evidence.
6. A traceability link from each business rule to its eventual test.

Only after this is reviewed should we derive bounded contexts and aggregates. This sequencing minimizes the risk of implementing a clean technical architecture around the wrong business concepts.