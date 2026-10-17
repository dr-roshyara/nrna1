# Principal DDD review — Cross-Domain Relationship Specification v0.2

I have reviewed the new attachment, Principal Review and Next-Step Execution: Cross-Domain Relationship Specification v0.2. It advances the previous work by formalizing the conceptual type system, separating four kinds of person-to-organization relationships, introducing bi-temporal semantics, and specifying an authorization decision function.&#x20;

Eingefügter Text.txt



My role here is to challenge the corpus as a principal reviewer, not merely to accept its conclusions. The goal is a reusable digitalization platform for organizations and political parties, with organizational governance and election processes supported by explicit, testable business rules.

My principal-review conclusion: v0.2 is a strong research specification, but some of its formal definitions still need correction before we can safely derive bounded contexts or authorize implementation.

The next step should be a formal consistency review and requirements traceability pass, not another expansion of the glossary.

## 1. What the new document contributes

| Area                         | Assessment                                                                                                                            |
| ---------------------------- | ------------------------------------------------------------------------------------------------------------------------------------- |
| Conceptual types             | Useful distinction between entities, roles, policies and computed decisions; some classifications remain provisional.                 |
| Person-to-unit relationships | Correctly separates unit affiliation, participation, employment and committee appointment.                                            |
| Bi-temporal history          | Establishes the need to distinguish when a fact was valid from when the system recorded it.                                           |
| Authorization                | Provides a deterministic evaluation model, but its mandatory-rule semantics need correction.                                          |
| Election integration         | Identifies the foundational facts election processes consume without making those facts election-specific.                            |
| Evidence and testing         | Introduces a scenario matrix and falsification discipline, but the matrix is not yet populated with verified organizational evidence. |

The attachment itself still records bounded-context derivation, aggregate validation, identity and tenancy decisions as pending, with ARB approvals outstanding. Its 35–40% progress estimate is a qualitative estimate rather than an independently measured completion score.&#x20;

Eingefügter Text.txt



## 2. Four formal defects that must be corrected

These findings are derived from reviewing the actual definitions in v0.2, rather than simply restating its recommendations.&#x20;

Eingefügter Text.txt



### Defect 1 — Bi-temporal queries are incomplete

The document defines:

- `validFrom` / `validUntil`: when a fact was true in the organization.
- `recordedFrom` / `recordedUntil`: when the system considered a particular version to be its recorded knowledge.

That distinction is correct. The query definitions, however, need a more precise formulation.

Suppose a committee appointment was valid from 1 October, but the system did not record it until 15 October. On 10 October, the appointment was valid in reality but was not yet known to the system.

We need to distinguish three questions:

1. What is currently believed to have been true on 10 October?
2. What did the system know on 10 October about the appointment?
3. What did the system believe on 10 October was valid on 5 October?

A single `asOf(T)` method is ambiguous unless we specify which time dimension it refers to.

I recommend a two-parameter query:

\\[ F(v,k)= \left\\{ f\mid V_f^{start}\leq v\<V_f^{end} \land K_f^{start}\leq k\<K_f^{end} \right\\} \\]

Where:

- \\(v\\) is the valid-time instant being investigated.
- \\(k\\) is the transaction-time instant representing the system's knowledge.

For open-ended intervals, the missing upper bound is treated as infinity.

This gives us a precise basis for historical reports, audit investigations and reconstructing an eligibility decision. We must also define how corrections, retractions and duplicate versions behave.

### Defect 2 — The policy evaluation model mishandles abstention

The document allows `permit`, `deny` and `abstain` rules. Its pseudocode then finds the highest priority among all non-mandatory rules, including abstentions.

Consider this example:

| Rule   | Priority | Effect  |
| ------ | -------- | ------- |
| Rule A | 100      | Abstain |
| Rule B | 50       | Permit  |

If the highest-priority rule is an abstention, the current algorithm can return `Deny` even though a lower-priority applicable rule explicitly permits the action.

That may be intentional under a particular policy, but it contradicts the intended interpretation of abstention as a non-decision.

Correction: define the semantics first. Under a straightforward priority model, abstentions should be removed from the decisive candidate set. If no decisive rule remains, apply the documented default. Then resolve the highest-priority decisive rules, using an explicitly approved tie-break rule.

There is a second distinction: a mandatory rule is not just a high-priority rule. It represents a non-overridable constraint. A mandatory prohibition must never be defeated by a mandatory permission. Conflicting mandatory rules should cause a configuration error or a safe denial, according to the approved operational policy.

We should therefore model mandatory constraints separately from ordinary permit/deny policy rules, rather than relying only on a Boolean `mandatory` field.

### Defect 3 — The type system still contains unresolved alternatives

The conceptual type table marks several concepts as either an entity or a value object, or as globally identified while simultaneously leaving their identity scope undecided.&#x20;

Eingefügter Text.txt



These alternatives matter:

- Person: global identity versus organization-scoped identity has privacy, matching and data-isolation consequences.
- Account: identity-provider account versus platform account versus organization-specific account requires clarification.
- MembershipType: a classification is not automatically a policy. The type definition and the rules attached to that type may need separate models.
- Role: a role definition may be versioned, but a role assignment is a separate fact with its own lifecycle.
- Mandate: a mandate may carry an independent identity, authority scope, validity period and evidence of authorization. Calling it a value object prematurely could erase meaningful lifecycle behavior.

I would change the type table's status column from a single proposed classification to:

`Candidate type → Evidence → Decision status → Consequences`.

This prevents a hypothesis from becoming an accidental architecture decision.

### Defect 4 — Election eligibility cannot be only a live projection

The attachment says election eligibility is a projection of organizational facts, not a new fact.&#x20;

Eingefügter Text.txt



That is a useful starting point, but incomplete for a real election process.

A robust process may need to preserve:

- the eligibility rules and versions used;
- the relevant organizational facts at the decision time;
- the electorate or voter-roll snapshot, where required;
- exceptions, corrections and appeals;
- the authority that approved a decision;
- the evidence supporting the final eligibility outcome.

The current organizational facts must remain owned by their authoritative contexts. However, an election may need to persist an election-specific eligibility determination or frozen voter-roll version as an auditable record.

That does not mean the election context should rewrite Membership or Governance. It means that a historical election decision must be reproducible even after membership, appointments or jurisdictions change.

## 3. Revised conceptual model: Facts, policies and decisions

The architecture should distinguish three different categories. This is the key to preventing cross-domain coupling.

A. Authoritative business facts

Person, Member, Membership, MemberUnitAffiliation, OrganizationalUnit, Committee, CommitteeAppointment, RoleAssignment, Mandate and GeographicJurisdiction.

Each fact has an authoritative owner, lifecycle and history.

B. Policies and rules

Membership eligibility, appointment eligibility, mandate rules, geographical responsibility, forum access policy and election eligibility rules.

Policies define how facts are interpreted. Organization-specific rules must be distinguishable from platform-wide constraints.

C. Derived decisions and projections

Effective permissions, committee rosters, forum access, jurisdiction-based membership lists and election eligibility determinations.

Derived results must identify their source facts, applicable policy versions and freshness or snapshot semantics.

This classification is more useful than simply organizing every concept into a database table. It identifies which context may change a fact, which context interprets it, and which context consumes the result.

For example, a committee appointment may be owned by Governance, while effective forum access is calculated by an authorization capability using the appointment, mandate and forum policy. The forum must not become a second owner of the appointment.

## 4. The next research step: Build a cross-domain rule and evidence matrix

I recommend that we stop expanding the relationship catalogue for a moment and use it to test real business scenarios.

The following matrix is the starting point. The scenarios are hypotheses to validate, not claims about the legal rules of every organization.

| Scenario                                   | Facts involved                           | Required business question                                                                           |
| ------------------------------------------ | ---------------------------------------- | ---------------------------------------------------------------------------------------------------- |
| A member has no branch affiliation         | Membership, Affiliation                  | Can membership remain valid without a unit?                                                          |
| A person is a volunteer but not a member   | Person, Participation                    | Which activities can the person join, and what authority do they receive?                            |
| A committee chair's mandate expires        | Appointment, RoleAssignment, Mandate     | Which powers end, and at what exact time?                                                            |
| A geographical boundary changes            | Jurisdiction, Committee                  | Which historical and current responsibilities are affected?                                          |
| A committee member loses forum access      | Appointment, Mandate, ForumPolicy        | How quickly must access be revoked, including cached access?                                         |
| A membership is corrected retroactively    | Membership, transaction history          | Can an earlier decision be reconstructed using the facts known at that time?                         |
| A voter challenges an eligibility decision | Membership, jurisdiction, election rules | Can the system explain and reproduce the decision without altering authoritative membership history? |

For each scenario, we should capture the following evidence:

1. Source: constitution, bylaws, policy, stakeholder statement or operational record.
2. Business rule: the precise proposition supported by the evidence.
3. Scope: universal, organization-specific, configurable or legally mandatory.
4. Counterexample: a case that would invalidate an overly broad interpretation.
5. Owner: the bounded context responsible for the underlying fact or rule.
6. Test: an executable example, property-based test or formal constraint.
7. Decision status: hypothesis, contested, recommended, approved or rejected.

Without this evidence trail, formal logic can make an incorrect assumption appear mathematically rigorous. A proof only establishes what follows from the assumptions; it does not establish that the assumptions represent the organization correctly.

## 5. How we should use mathematics, statistics and machine learning

These techniques should support different stages of the research rather than be introduced as a single AI feature.

| Technique              | Immediate use                                                                                                      |
| ---------------------- | ------------------------------------------------------------------------------------------------------------------ |
| First-order logic      | Formalize cardinality, scope and integrity rules.                                                                  |
| Graph theory           | Test organizational and geographical hierarchies for cycles and ambiguous parent relationships.                    |
| Temporal logic         | Test mandate expiry, reinstatement, overlapping appointments and retroactive corrections.                          |
| Constraint solving     | Detect contradictory policy configurations and impossible combinations of rules.                                   |
| Property-based testing | Generate combinations of membership, affiliation, role and date values to discover counterexamples.                |
| NLP and LLMs           | Extract candidate rules from statutes, bylaws and organizational documents, with citations to the source passages. |
| Statistical analysis   | Identify anomalies in real data, such as unexpected overlapping mandates or missing relationships.                 |
| Model checking         | Explore bounded lifecycle and authorization states to verify safety properties.                                    |

The machine-learning output remains a research hypothesis. In particular, an unusual membership pattern must not automatically trigger rejection, loss of permissions or an adverse election decision.

## 6. Updated priority plan

P0

Correct and freeze the formal semantics

Resolve the bi-temporal query contract, mandatory policy constraints, abstention, role assignment semantics and identity-scope alternatives. Freeze only the definitions that have sufficient evidence.

P0

Validate the relationship model

Test the four distinct person-to-organization relationships against political-party, association or humanitarian, and federation scenarios. Document coexistence rules and independent lifecycles.

P1

Create traceable tests and evidence

Connect each accepted rule to a source, a counterexample analysis and an executable test. Include historical, concurrent and revocation scenarios.

P1

Validate election-relevant scenarios

Check eligibility, committee authority, jurisdiction, voter-roll reproducibility and auditability against the foundational model without merging election decisions into membership facts.

P2

Derive bounded contexts and aggregates

Only after the semantic and evidence review, identify authoritative owners, transactional invariants, context contracts and consistency requirements.

P3

Decide identity, tenancy and implementation

Compare global versus organization-scoped identity, tenant isolation options and deployment architecture against validated requirements. Obtain the required architecture and implementation approvals before coding.

## 7. Principal architect's final recommendation

The next artifact should be Cross-Domain Relationship Specification v0.3 — Formal Consistency and Requirements Traceability.

It should not merely repeat v0.2. It should:

- correct the temporal and policy semantics;
- distinguish unresolved conceptual classifications from approved decisions;
- define the source-of-truth owner for each business fact;
- add election-relevant scenarios to the validation matrix;
- establish traceability from evidence to business rules to formal invariants to tests;
- record the remaining approval gates explicitly.

I would not derive final bounded contexts yet. The next meaningful milestone is not more terminology or a higher completion percentage; it is demonstrating that the model can explain real organizational cases, survive counterexamples and preserve historical decisions correctly.

Once those conditions are met, we can derive bounded contexts from the actual business responsibilities and consistency boundaries—and then evaluate whether a modular monolith or another architecture best supports the platform.