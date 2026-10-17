You are right. I mixed up two different domain concepts and allowed the existing Membership workstream to distract us from the actual objective. We should not make general Membership implementation verification the next step merely because it appears in the repository backlog.

Our current objective is to implement the Election Review Committee—or, if the domain model confirms a different name, the appropriate election-specific committee and its decision process.

## 1. These are different domain concepts

### A. General Membership

Separate domain capability

Manages the organisation's members: applications, membership approval, membership status and related organisational rules.

It is not our current implementation target.

### B. Election Membership

Election-specific concept

Determines which people may participate in a particular election under that organisation's election rules.

Election eligibility may depend on membership type, dues, disciplinary status, committee level and other applicable rules. General membership alone does not automatically establish election eligibility.

### C. Election Review Committee

Current focus

The election-specific body responsible for reviewing relevant cases and making authorised decisions—for example, reviewing eligibility objections, resolving disputed cases or approving decisions within its assigned authority.

Its exact mandate, decision rights and relationship to the Election Committee must be established from the existing domain model and requirements.

These concepts may interact, but they must not be merged into one aggregate, workflow or implementation task.

## 2. What we should implement now

The next step is not to verify general Membership progress. It is to establish the requirements and architecture for the election review committee.

| Step                       | Required work                                                                                                                                                                  |
| -------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| 1. Discover                | Inspect the repository, existing stories, ADRs, domain language and previous decisions for election review, objections, appeals, eligibility disputes and committee decisions. |
| 2. Define authority        | Establish who appoints the committee, who may submit a case, what it can decide, and whether decisions are binding or appealable.                                              |
| 3. Define lifecycle        | Model the review case lifecycle, such as submitted → under review → decided, with rejection, withdrawal, expiry or appeal only where supported by requirements.                |
| 4. Define domain model     | Identify aggregates, entities, value objects, policies, domain events and invariants. Do not assume a new aggregate is necessary before inspecting the existing model.         |
| 5. Define security         | Specify separation of duties, authorisation, quorum where applicable, conflicts of interest, audit evidence and tenant isolation.                                              |
| 6. Implement incrementally | Write acceptance criteria and tests first, then implement the smallest authorised vertical slice. Preserve existing architecture decisions.                                    |

One important boundary: the review committee may consume an election-specific eligibility decision or the evidence behind it, but it should not silently take ownership of general membership approval.

## 3. Correct the terminology before coding

The existing context distinguishes an Election Committee from an election-specific membership selection and eligibility process. It does not yet establish conclusively that “Election Review Committee” is the canonical name.

We should investigate whether the intended body is:

- Election Review Committee — if its mandate is to review contested or disputed matters.
- Election Committee — if it is the operational body managing the election and its decisions.
- Election Appeals Committee — if its primary mandate is to hear appeals against prior decisions.

These names are not interchangeable. We must select the term that matches the actual organisational authority and requirements, not simply choose the most plausible name.

## 4. Corrected instruction for Claude Code

# Mission: Discover and Implement the Election Review Committee

Act as a senior Domain-Driven Design architect, requirements engineer, formal-logic researcher and principal code reviewer.

## 1. Objective

Our current objective is to discover, specify and implement the election-specific review committee and its authorised decision process.

**Do not redirect this mission to general Membership implementation or verification.**

General Membership and Election Membership are separate domain concepts. Election Membership concerns participation in a particular election under election-specific eligibility rules. Neither concept should be conflated with the committee that reviews election cases.

## 2. Discovery before implementation

Inspect the current repository, existing requirements, stories, ADRs, domain documentation, aggregates, policies, authorisation rules, audit mechanisms and tests.

Search for existing concepts concerning:

- Election Committee and Election Review Committee
- Election Appeals Committee, objections and appeals
- Election Membership and election eligibility
- Eligibility decisions and disputed voter cases
- Committee appointments, roles, decision authority and separation of duties
- Quorum, approvals, vetoes and conflicts of interest, where applicable
- Constitutional transition guards, audit records and tenant isolation

Reuse existing domain concepts where appropriate. Do not invent a parallel architecture.

## 3. Establish the requirements

Determine and document:

1. The committee's canonical domain name and mandate.
2. Who establishes and appoints the committee.
3. Who may submit a review case and which decisions may be challenged.
4. The permitted decision types and their consequences.
5. The required approval, quorum and separation-of-duty rules, if any.
6. The case lifecycle, including terminal states and any appeal process.
7. Required evidence, auditability and confidentiality.
8. Tenant boundaries and authorisation requirements.
9. Dependencies on Election Membership and eligibility decisions.
10. Explicitly excluded responsibilities.

Distinguish repository evidence, approved requirements, unresolved questions and architectural recommendations. Do not treat a plausible assumption as an approved requirement.

## 4. Domain and formal analysis

Define the relevant actors, use cases, invariants, state transitions, aggregate boundaries, commands, events, policies and acceptance criteria.

Express critical rules as testable predicates where useful. Check for invalid transitions, conflicting decisions, unauthorised actors, duplicate processing, race conditions and cross-tenant access.

Do not introduce a new aggregate, saga, event-sourcing mechanism or persistence model without demonstrating why existing patterns are insufficient.

## 5. Implementation protocol

First produce a discovery report and a proposed vertical slice. Identify the existing story or backlog item that should own the work.

Do not modify general Membership, Election Membership, production code, ADRs or database schemas as part of discovery. Do not change shared test databases or run destructive migrations.

After the requirements and scope are authorised, implement the smallest coherent slice with failing tests first, then implementation, regression tests and audit verification.

Coordinate with concurrent repository work. Inspect current Git status and changes before editing; do not overwrite or revert another session's work.

## 6. Deliverables

Report:

- Canonical terminology and supporting repository evidence
- Domain mandate and responsibility boundaries
- Actors, use cases and decision-authority matrix
- Lifecycle/state-transition model
- Invariants and acceptance criteria
- Aggregate and bounded-context implications
- Security, audit and tenant-isolation analysis
- Existing stories/ADRs to reuse or update
- Proposed implementation slice and dependencies
- Tests, risks, unresolved questions and explicit stop conditions

Stop after discovery if authority or requirements remain ambiguous. Do not claim implementation completion without evidence from tests and repository state.

## 5. Corrected prioritisation

For the current workstream, the priority should be:

1. Election Review Committee discovery and requirements.
2. Establish its relationship to the existing Election Committee and election-specific eligibility decisions.
3. Define the authorised decision lifecycle and invariants.
4. Select the existing story or create a properly scoped backlog item after checking for duplicates.
5. Implement the first approved vertical slice.
6. Resume unrelated Membership backlog work only when it is independently prioritised or needed as a proven dependency.

The previous audit investigation of election lifecycle `from_state` remains a separate election-related concern. It should not automatically become the next production change for the review committee; first determine whether it blocks the committee's decision and audit requirements.

### Corrected status and TODOs

- Current objective: Election Review Committee requirements, domain boundaries and decision process.
- General Membership: Separate workstream; no new verification is required just because we are pursuing the committee.
- Election Membership: A separate election-specific concept and possible dependency, not the committee itself.
- Immediate TODO: Discover existing committee-related domain language, stories, ADRs and code.
- Next TODO: Define authority, lifecycle, invariants, security and acceptance criteria.
- Implementation TODO: Choose an authorised, test-first vertical slice.
- Deferred: General Membership defects and integration tasks unless the discovery demonstrates a direct dependency.

This restores the correct direction: we are designing the election governance decision-making capability, not continuing general Membership verification.&#x20;