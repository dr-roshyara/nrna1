# Organizational Digitalization — DDD Architecture Brainstorming, Phase 1

We will pause Adjudication and work on the architecture of organizational digitalization. The goal is to discover a robust domain model for organizations such as political parties, the Red Cross, social associations, and other federated organizations.

We should not start by designing database tables or Laravel models. We should first understand the business concepts, their relationships, their rules, and their boundaries. Only then should we derive bounded contexts and an implementable architecture.

## 1. The central architectural question

How can one digital platform represent different kinds of organizations while preserving their individual structures, membership rules, responsibilities, geographical jurisdictions, and communication needs?

Examples include:

- A political party with national, regional, district, and local committees.
- A social organization with branches, specialist wings, volunteers, and local chapters.
- An association with members, membership categories, elected officers, financial obligations, and discussion forums.
- A federation in which local organizations have autonomy but remain part of a larger organization.

The challenge is to build a reusable organizational platform without forcing every organization into the same organizational model.

Our research should distinguish three things:

1. Universal concepts — concepts shared by most organizations.
2. Configurable policies — rules that differ by organization.
3. Organization-specific concepts — structures or behaviors that cannot be represented safely by configuration alone.

## 2. Analyse the domain terms

Let us begin with the seven concepts you identified, adding only the foundational concepts needed to make their relationships meaningful.

[People avatars faces vector collection](https://images.openai.com/static-rsc-4/I6tadKSsnTA-EY5Apw2t72ESLF6RyqrvEpam1mDOyMIIB0KOCIbLGXN-DVWDy_FhUDbv5QbKL7FNMkCBEe-Hk3amXvBKZdl0tDSKlnWtLDqgSWooARcG9FxeUP1Ck48ylKyX_4guGikMk_nQKCwFcRz_77X9l0JqNt9IHplzRsFnUvAFhODRXVIhEI-HoFS1?purpose=inline)

1\. Member

A person recognized by an organization as a participant or potential participant in its organizational life.

- Represents the person, not the person's current membership status.
- May belong to several organizations.
- May participate in several committees within one organization.
- May hold different responsibilities in different committees.

DDD question: Is `Member` a person record, or an organization-specific representation of a person? We should distinguish the global person identity from the organization's member record if the platform needs to support multiple organizations.

[Membership certificate and card form](https://images.openai.com/static-rsc-4/ggLzvt8PHYFArai5W68D-wtxpGwQlSO1K8uxgUsZgDJ7VR1-F8vhSXDUnDMmKYxU2yg4X1q9IPSvVLxyBUoxIpZtKYoJP4OyF3lo_JinRpPSEmoxYus50MC1n-3lkl3f3RynH72e4akU81g5od94U9paO9Af3ZkJtR-lRn4TsR0?purpose=inline)

2\. Membership

The relationship through which a member belongs to a particular organization.

Membership is not the same as Member. A person can remain in the system even when a membership expires or is terminated.

Potential attributes include start date, status, approval, expiry, membership number, and applicable conditions.

DDD rule: Membership establishes organizational belonging. It does not automatically establish a committee position or an administrative permission.

[Royal Air Maroc Airlines - Royal Air Maroc](https://images.openai.com/static-rsc-4/qGsj_a2cYa5vWriX4DfMtFWzo0fMJBT95YwFT7qMhcp8JptYpf-AZcMT96JirbcdA_3eaK0iZXOl0XkrwIGa5zHGan3snSMDLfxQy7Gt8vJQgPMY7Kz1D9H6np30Glue6fPo1KqAZgnSbi7_e6F-zZ6Feq-WW8HpgRM8y-en61o?purpose=inline)

3\. Membership Type

A classification or policy for memberships, such as ordinary member, supporting member, honorary member, or student member.

A type might determine fees, eligibility, renewal rules, or participation rights.

Important distinction: The type describes the category and associated policy. The individual Membership records which category applies and its actual status.

We must not assume that every organization uses the same membership categories or gives them the same meaning.

[The Escalating Political Confrontation in Education Committe](https://images.openai.com/static-rsc-4/3wI1lZYISZ8UFdylo39nWXVkAAFlqNsArjDqWFHZYaEVecUWLZ0SRsdmkoh7WutY88iqTtt_sJzkn2OTJAJuyr73NC0RYD360Iy04ePVzG_hfJCO4gAPooZjwslF1AM8MZAJuQykB19a3bneqEVeWjvxe-jXykhfYxgZrMrAFlU?purpose=inline)

4\. Committee

A formally constituted group that has a defined responsibility, scope, membership, and possibly decision-making authority.

Examples: executive committee, finance committee, regional committee, election committee.

A committee has a lifecycle and rules of its own. It may have a chairperson, secretary, treasurer, and ordinary committee participants.

DDD rule: A committee is an organizational governance concept, not merely a list of users.

[TOMi.digital](https://images.openai.com/static-rsc-4/J9L1F80zaivvJ78nzkeIrKvsF0sG5aOqnmLFGj609EKWSl1AVT9GDEb9qEftB_e4h2Qs5B4TG0dVmbsHok4Azk3ykR2VNv39PM7_s_ubwtBQzZY6i2PILe2i2mQX1c9UDA6I6yHhIX0yA8s9gHzZOm-kOAf_vKsnZknC02RZzCs?purpose=inline)

5\. Geographical Committee

A committee whose organizational responsibility is tied to a defined geographical area or jurisdiction.

Examples: national committee, provincial committee, district committee, municipality committee, local ward committee.

We should investigate whether geographical committees are simply committees with a geographic scope or whether certain organizations require specialized rules for geographic hierarchy and authority.

Initial hypothesis: Model a committee's geographic jurisdiction separately from the committee itself. That allows non-geographical committees to exist without artificial locations.

[Board Responsibilities | PDF](https://images.openai.com/static-rsc-4/4Kdw1IvjEjHHmPi-42DSoZm6yk1FKcVoDj7DFboK1k92stA4DCsekovTfTlVODMsUSVKAF-k6oLQ4P-c0fBJ0UH3NziDJABpPSpayZ042vNT1PRBvZLg9I_b7udQ-tCtIkvqI7FbNdlmJwGspBmF0QqHriQzBdl7dMFJSdLO674?purpose=inline)

6\. Role

A defined responsibility or position associated with a person in a specific organizational context.

Examples: chairperson of a committee, secretary of a local branch, treasurer of a regional committee, organization administrator.

We must distinguish:

- Organizational position: chairperson, secretary, treasurer.
- Participation role: committee member, volunteer, adviser.
- Application permission: ability to approve a membership, edit a record, or moderate a forum.

These are related, but they are not interchangeable.

[Norilco - Norsk forening for stomi reservoar og mage- og tarmkreft](https://images.openai.com/static-rsc-4/XxpqpNh2d06Nkk0BJdcWP62fPF1O_4nJjiKhNkxVWZJmpXMcsZP1Y_BJLV6N44kMpqvHC4n2_8Y2iOtZmGc8jLvnvErtQhvF8_0HWKH-QJPAC9dHieiKw_BBKQm0bT65BIBAkffOU9FsG3OLGYpste8AcEWMMLYBTmKxOyHmjCg?purpose=inline)

7\. Discussion Forum

A communication space in which eligible participants can exchange information, discuss issues, and make contributions.

A forum might be public, organization-wide, committee-specific, or restricted to a particular role.

DDD rule: A forum should not become the authoritative source of membership or committee assignments. Access should be determined from authoritative organizational facts and communication policies.

We also need two additional concepts:

- Organization: the entity that owns the organizational structure and defines its policies.
- Organizational Unit: a branch, wing, department, chapter, or other structural unit that may or may not be a committee.

The distinction between organizational units and committees is particularly important. A youth wing, women's wing, or regional branch may be a permanent organizational unit, while its executive committee is a governance body within that unit.

## 3. Establish the fundamental relationships

Here is our first conceptual relationship model. It is deliberately a hypothesis for brainstorming, not a certified architecture.

\#chatgpt-mermaid-\_r_6le\_{font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";font-size:16px;fill:rgb(13, 13, 13);}@keyframes edge-animation-frame{from{stroke-dashoffset:0;}}@keyframes dash{to{stroke-dashoffset:0;}}#chatgpt-mermaid-\_r_6le\_ .edge-animation-slow{stroke-dasharray:9,5!important;stroke-dashoffset:900;animation:dash 50s linear infinite;stroke-linecap:round;}#chatgpt-mermaid-\_r_6le\_ .edge-animation-fast{stroke-dasharray:9,5!important;stroke-dashoffset:900;animation:dash 20s linear infinite;stroke-linecap:round;}#chatgpt-mermaid-\_r_6le\_ .error-icon{fill:rgb(243, 243, 243);}#chatgpt-mermaid-\_r_6le\_ .error-text{fill:rgb(13, 13, 13);stroke:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_6le\_ .edge-thickness-normal{stroke-width:1px;}#chatgpt-mermaid-\_r_6le\_ .edge-thickness-thick{stroke-width:3.5px;}#chatgpt-mermaid-\_r_6le\_ .edge-pattern-solid{stroke-dasharray:0;}#chatgpt-mermaid-\_r_6le\_ .edge-thickness-invisible{stroke-width:0;fill:none;}#chatgpt-mermaid-\_r_6le\_ .edge-pattern-dashed{stroke-dasharray:3;}#chatgpt-mermaid-\_r_6le\_ .edge-pattern-dotted{stroke-dasharray:2;}#chatgpt-mermaid-\_r_6le\_ .marker{fill:rgb(143, 143, 143);stroke:rgb(143, 143, 143);}#chatgpt-mermaid-\_r_6le\_ .marker.cross{stroke:rgb(143, 143, 143);}#chatgpt-mermaid-\_r_6le\_ svg{font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";font-size:16px;}#chatgpt-mermaid-\_r_6le\_ p{margin:0;}#chatgpt-mermaid-\_r_6le\_ .entityBox{fill:rgb(222, 234, 251);stroke:rgb(83, 154, 248);}#chatgpt-mermaid-\_r_6le\_ .relationshipLabelBox{fill:rgb(243, 243, 243);opacity:0.7;background-color:rgb(243, 243, 243);}#chatgpt-mermaid-\_r_6le\_ .relationshipLabelBox rect{opacity:0.5;}#chatgpt-mermaid-\_r_6le\_ .labelBkg{background-color:rgba(243, 243, 243, 0.5);}#chatgpt-mermaid-\_r_6le\_ .edgeLabel{background-color:rgb(252, 252, 252);}#chatgpt-mermaid-\_r_6le\_ .edgeLabel .label rect{fill:rgb(252, 252, 252);}#chatgpt-mermaid-\_r_6le\_ .edgeLabel .label text{fill:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_6le\_ .edgeLabel .label{fill:rgb(83, 154, 248);font-size:14px;}#chatgpt-mermaid-\_r_6le\_ .label{font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";color:rgb(13, 13, 13);}#chatgpt-mermaid-\_r_6le\_ .edge-pattern-dashed{stroke-dasharray:8,8;}#chatgpt-mermaid-\_r_6le\_ .node rect,#chatgpt-mermaid-\_r_6le\_ .node circle,#chatgpt-mermaid-\_r_6le\_ .node ellipse,#chatgpt-mermaid-\_r_6le\_ .node polygon{fill:rgb(222, 234, 251);stroke:rgb(83, 154, 248);stroke-width:1px;}#chatgpt-mermaid-\_r_6le\_ .relationshipLine{stroke:rgb(143, 143, 143);stroke-width:1px;fill:none;}#chatgpt-mermaid-\_r_6le\_ .marker{fill:none!important;stroke:rgb(143, 143, 143)!important;stroke-width:1;}#chatgpt-mermaid-\_r_6le\_ [data-look=neo].labelBkg{background-color:rgba(243, 243, 243, 0.5);}#chatgpt-mermaid-\_r_6le\_ .node .neo-node{stroke:rgb(83, 154, 248);}#chatgpt-mermaid-\_r_6le\_ [data-look="neo"].node rect,#chatgpt-mermaid-\_r_6le\_ [data-look="neo"].cluster rect,#chatgpt-mermaid-\_r_6le\_ [data-look="neo"].node polygon{stroke:url(#chatgpt-mermaid-\_r_6le\_-gradient);filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_6le\_ [data-look="neo"].swimlane.cluster rect{filter:none;}#chatgpt-mermaid-\_r_6le\_ [data-look="neo"].node path{stroke:url(#chatgpt-mermaid-\_r_6le\_-gradient);stroke-width:1px;}#chatgpt-mermaid-\_r_6le\_ [data-look="neo"].node .outer-path{filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_6le\_ [data-look="neo"].node .neo-line path{stroke:rgb(83, 154, 248);filter:none;}#chatgpt-mermaid-\_r_6le\_ [data-look="neo"].node circle{stroke:url(#chatgpt-mermaid-\_r_6le\_-gradient);filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_6le\_ [data-look="neo"].node circle .state-start{fill:#000000;}#chatgpt-mermaid-\_r_6le\_ [data-look="neo"].icon-shape .icon{fill:url(#chatgpt-mermaid-\_r_6le\_-gradient);filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_6le\_ [data-look="neo"].icon-shape .icon-neo path{stroke:url(#chatgpt-mermaid-\_r_6le\_-gradient);filter:drop-shadow( 1px 2px 2px rgba(185,185,185,1));}#chatgpt-mermaid-\_r_6le\_ :root{--mermaid-font-family:-apple-system-body,ui-sans-serif,-apple-system,system-ui,"Segoe UI",Helvetica,"Apple Color Emoji",Arial,sans-serif,"Segoe UI Emoji","Segoe UI Symbol";}ORGANIZATIONMEMBER_RECORDPERSONMEMBERSHIPMEMBERSHIP_TYPEORG_UNITCOMMITTEECOMMITTEE_APPOINTMENTROLEGEOGRAPHIC_SCOPEMEMBERSHIP_OBLIGATIONFORUMFORUM_ACCESS_POLICYrecognizesrepresented_bygrantshasclassifiescontainsparent_ofhostshasparticipatesdefinesassignedincursprotects

The diagram makes several important distinctions:

- A `Person` may be represented in more than one organization.
- A `Membership` establishes belonging to an organization.
- A `CommitteeAppointment` represents participation in a committee with a role and potentially a defined period.
- An `OrganizationalUnit` represents structural organization, while a `Committee` represents a governance body.
- Geographic scope, financial obligations, and forum access are associated concepts, not necessarily fields that belong inside the Member entity.

One refinement is essential: a committee participant may need to be a non-member, such as an external adviser or invited expert. Therefore, the model should not impose a universal rule that every committee appointment requires active membership. That requirement should be an explicit organizational policy.

## 4. Discover the candidate bounded contexts

A bounded context defines where a model has a particular meaning and where its business rules are authoritative. It is not automatically a separate application or microservice.

I propose the following initial architecture landscape.

## Organizational Digitalization Platform

Organization-scoped configuration, policies and shared identity references

Membership Context

Member records, memberships, membership types, lifecycle, eligibility for membership benefits.

Organization Structure Context

Organizations, wings, branches, units, parent-child structural relationships.

Governance Context

Committees, appointments, organizational positions, mandates, decision authority.

Geography & Jurisdiction Context

Geographical hierarchy, boundaries, jurisdictions, territorial responsibility.

Communication Context

Forums, topics, posts, moderation, subscriptions, discussion access policies.

Identity & Access Context

Authentication, application accounts, permissions, authorization enforcement.

Potential supporting contexts

Finance & Obligations · Activities & Events · Notifications · Audit & Compliance · External Integrations

These are candidate bounded contexts, not a final decision to create six or more independently deployed services. We should validate their boundaries against concrete business scenarios first.

For an initial implementation, a modular monolith with explicit domain boundaries is a reasonable hypothesis. The existing architectural direction also distinguishes Membership from Governance: committee write operations belong in Governance rather than being absorbed into Membership.&#x20;

### Who owns what?

| Business fact                                               | Proposed authoritative context                                            |
| ----------------------------------------------------------- | ------------------------------------------------------------------------- |
| Who is recognized as a member?                              | Membership                                                                |
| What kind of membership does the person have?               | Membership                                                                |
| Which wings and branches exist?                             | Organization Structure                                                    |
| Which committees exist and what authority do they have?     | Governance                                                                |
| Which committee appointment and mandate does a person hold? | Governance                                                                |
| Which territory does a geographical committee cover?        | Geography & Jurisdiction, with Governance owning the committee assignment |
| What permissions does a user have in the application?       | Identity & Access, informed by authoritative business facts               |
| Which discussions can the user access?                      | Communication policy enforced using authoritative facts and access rules  |
| What dues are owed and paid?                                | Finance & Obligations, if financial management is in scope                |

The critical architectural principle is that one context owns a business fact; other contexts consume it through defined contracts or projections.

For example, Communication should not maintain an independent, manually edited copy of committee membership as its source of truth. It may maintain a read projection for efficient forum authorization, provided revocation and policy changes are handled correctly.

## 5. Important relationships that need explicit business rules

The relationship diagram is only the beginning. We must investigate the following rules before finalizing the model.

### A. Member versus Membership

Consider a person who joins an organization, leaves, and later rejoins.

Questions:

- Does the same member record persist?
- Is each joining episode a new Membership record?
- Can a person have more than one active membership in the same organization?
- What happens to historical membership types, dues, and committee appointments after termination?

My initial hypothesis is to preserve the member record and model membership periods or episodes separately where the business requires them. This retains history without making the person synonymous with an active membership.

### B. Membership versus Committee participation

A member may be associated with multiple committees simultaneously.

For example, a member could be:

- A regular member of the national organization.
- A chairperson in a district committee.
- A treasurer in a local committee.
- A participant in a specialist wing.

These are separate relationships with different responsibilities and lifecycles.

The architecture must answer whether membership is a prerequisite for each appointment, whether a person may hold multiple roles within one committee, and whether an appointment requires approval or an election.

### C. Organizational unit versus Committee

A wing is not necessarily a committee.

A wing may contain local branches, activities, and its own committees. A committee may govern a wing, a geographical unit, or the organization as a whole.

We should therefore avoid a rigid hierarchy such as:

`Organization → Committee → Subcommittee`

as the only possible organizational structure.

Instead, model the organizational hierarchy flexibly and represent governance bodies explicitly. Then define which units a committee governs and what authority it possesses.

### D. Role versus Permission

A chairperson is a business role. The ability to approve a membership is an application permission.

A chairperson may have authority over a committee without having global administrator rights. A system administrator may maintain the software without having any organizational mandate.

Therefore:

- Organizational positions and mandates belong to Governance.
- Application permissions belong to Identity & Access.
- Authorization rules map relevant business authority to permitted operations.

We should avoid treating a role name alone as sufficient proof of authority.

### E. Geographical committee versus geographical jurisdiction

A geographical committee is still a committee. Its territory is a separate concern.

For example, a district committee may cover several municipalities. The geographical model must support jurisdiction boundaries, parent-child relationships, and potentially changing boundaries over time.

An important distinction: a geographical hierarchy is not necessarily identical to the organization's internal hierarchy. We should not assume that every administrative territory has a corresponding committee.

### F. Membership versus forum access

Forum access should be derived from explicit policy, potentially using:

- Active membership status.
- Organization or organizational-unit affiliation.
- Committee appointment.
- Role or mandate.
- Forum-specific restrictions.
- Moderation or suspension decisions.

Membership alone should not automatically grant access to every forum. Nor should an expired appointment necessarily terminate general membership or access to unrelated discussions.

## 6. Derive the model using business scenarios

As DDD architects and requirements engineers, we should validate concepts against scenarios rather than accepting the first diagram as the final model.

Consider this illustrative scenario:

A political party has a national organization, provincial branches, district committees, local committees, and a youth wing. One person belongs to the party, serves as secretary of a district committee, participates in the youth wing, and can access some but not all discussion forums.

We can use this to test the model.

## Scenario validation

1. Membership: Is the person an active member of the national organization? What membership policy applies?
2. Structure: Is the youth wing a branch, a specialized unit, or an independent affiliated organization?
3. Governance: Was the person appointed or elected as district secretary? When does the mandate begin and end?
4. Geography: Which territory falls under the district committee's responsibility?
5. Authorization: Which actions may the district secretary perform, and over which resources?
6. Communication: Which forums are available to the person, and what happens when the district appointment ends?

We should repeat the exercise with counterexamples:

- A person belongs to two organizations with different membership types.
- A member belongs to several committees at the same organizational level.
- A volunteer participates in activities without being a formal member.
- A committee is dissolved while its members remain active in the organization.
- A geographical boundary changes while historical appointments must remain auditable.
- A member's membership expires, but the person still has historical posts in a forum.
- A committee chairperson's mandate ends while their general membership remains active.

These scenarios reveal missing concepts, ambiguous rules, and potential aggregate boundaries.

## 7. Initial aggregate hypotheses

Bounded contexts and aggregates are different architectural concepts. An aggregate defines a consistency boundary within a context.

| Candidate aggregate    | Responsibility                                      | Important invariants to investigate                             |
| ---------------------- | --------------------------------------------------- | --------------------------------------------------------------- |
| Membership             | Membership lifecycle and applicable classification  | Valid lifecycle transitions; applicable membership rules        |
| OrganizationalUnit     | Structural unit and its lifecycle                   | Valid parent-child relationships; no invalid structural cycles  |
| Committee              | Committee identity and governance configuration     | Valid status, mandate, and governance configuration             |
| CommitteeAppointment   | Assignment of a participant to a committee and role | Valid appointment period, approval, and appointment constraints |
| GeographicJurisdiction | Territorial scope and hierarchy                     | Valid jurisdiction relationships and temporal changes           |
| Forum                  | Forum lifecycle and discussion configuration        | Valid forum status, moderation, and configuration               |

These are candidates, not a mandate to create six aggregates immediately. For example, a CommitteeAppointment might initially be an entity within a Committee aggregate if the committee's rules require that consistency boundary. Alternatively, it could become an independent aggregate if appointment lifecycles and concurrency requirements justify it.

Likewise, membership type changes should not silently rewrite historical facts. The domain must define whether classification policies are versioned and how changes affect existing memberships.

## 8. Research method for the next brainstorming round

I recommend the following sequence.

1. Domain vocabulary: Define precise meanings for Person, Member, Membership, MembershipType, OrganizationalUnit, Committee, Role, Mandate, Jurisdiction, and Forum.
2. Relationship discovery: Identify cardinality, ownership, optionality, lifecycle, and historical requirements for each relationship.
3. Business rules: Discover constraints, invariants, exceptions, approval processes, and policy variation between organizations.
4. Event and lifecycle modelling: Explore events such as MembershipApproved, MembershipSuspended, CommitteeEstablished, AppointmentGranted, and MandateExpired.
5. Bounded-context discovery: Group concepts according to business meaning, authority, language, and consistency requirements.
6. Context mapping: Define which context owns each fact, how other contexts obtain it, and how changes propagate.
7. Implementation architecture: Only after validation, derive aggregates, application services, APIs, persistence, authorization policies, and tests.

Machine learning may help cluster domain vocabulary, identify recurring patterns across organizational constitutions, and detect inconsistencies in large collections of documents. Formal logic and constraint modelling can test invariants and relationship rules. Neither technique replaces domain evidence or business-owner validation.

## 9. How far are we, and what remains?

We have established an initial conceptual model and several plausible context boundaries. We have not yet validated them against enough organizational scenarios to certify the architecture.

The following status is therefore qualitative, not a measured percentage.

Domain vocabulary

Initial draft

Core terms identified; definitions and edge cases remain to be validated.

Relationships

Hypotheses

Main relationships identified; cardinalities, lifecycles, and invariants remain open.

Bounded contexts

Candidate landscape

Initial context candidates proposed; boundaries and ownership require validation.

Implementation design

Not yet established

No final aggregate model, context contracts, or implementation plan has been derived from this brainstorming alone.

### Remaining TODOs

- Define the ubiquitous language and precise meaning of each domain term.
- Clarify the distinction between Person, Member, and Membership.
- Define MembershipType, its policies, and its versioning requirements.
- Model organizational units, wings, branches, and committees without forcing them into one hierarchy.
- Define committee participation, appointments, roles, mandates, and authority.
- Establish geographical jurisdiction relationships and historical boundary handling.
- Define the separation between organizational roles and application permissions.
- Specify forum-access rules and how membership or appointment changes affect access.
- Build a scenario catalogue, including exceptional and historical cases.
- Validate candidate bounded contexts and their authoritative responsibilities.
- Derive aggregate boundaries, invariants, domain events, and context contracts.
- Assess multi-organization tenancy, isolation, and organization-specific configuration.
- Produce a context map, a validated domain model, and a prioritized implementation backlog.
- Create acceptance criteria and tests for the most important business invariants.

Recommended next step: Begin with the relationship model for `Person`, `Member`, `Membership`, and `MembershipType`. These are foundational concepts. Once their meanings and lifecycles are clear, we can derive how committee participation and organizational roles should relate to them without coupling membership to governance.