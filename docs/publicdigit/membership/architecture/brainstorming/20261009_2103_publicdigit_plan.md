# PublicDigit: Current progress and remaining work

My assessment as your Senior DDD Architect and Principal Reviewer: we have made substantial progress in establishing the theoretical foundations and implementing the first Membership slices. However, we have not yet achieved the complete goal of a robust, organisation-specific governance and membership platform.

The key distinction is that we have made progress in three different dimensions: domain understanding, architectural decisions, and working implementation. These are related, but they are not interchangeable.

## 1. Where we are now

## Membership implementation

Slice 0 — Semantic domain kernel

Implemented: temporal model, resolution logic, invariants, and domain tests.

Implemented

Slice 1a — Application layer

Implemented: admission and ending handlers, ports, authority-check abstraction, and in-memory reference adapter.

Implemented

Slice 1b — PostgreSQL persistence

Implemented: tenant-scoped schema, transactional writes, idempotency, concurrency safeguards, and persistence tests.

Implemented

Principal acceptance and decision closure

The next gate: verify the implementation evidence, finish the documentation and backlog record, and settle the business decisions required for the next slice.

Next gate

Slice 2 and beyond

Application wiring, real use cases, remaining lifecycle behaviour, Governance integration, Election and Finance integration, migration, and legacy retirement.

Not complete

These statuses are based on the latest implementation report available from 9 October 2026. They are not a claim that I have independently inspected the current repository or that every reported test has passed in the current working tree.

### What the implementation has demonstrated

The reported evidence includes:

- A pure PHP Membership domain kernel with extensive unit, invariant, property-based, and bounded-model verification.
- Application handlers for admitting and ending membership.
- A PostgreSQL adapter with tenant-scoped database constraints and transactional persistence.
- Concurrency testing that exposed and helped correct a real idempotency defect.
- No reported new regression failures relative to the documented baseline.

There is an important qualification: the overall test suite is not green. Existing failures remain, and the regression comparison has documented limitations. We must not confuse “no new regression reported” with “all tests pass.”

## 2. How close are we to the final goal?

Our final goal is broader than implementing a Membership module. PublicDigit should support organisations with different structures, rules, membership lifecycles, committees, roles, elections, and digital services while preserving tenant isolation and constitutional governance.

| Workstream                                  | Current assessment                          | What remains                                                                      |
| ------------------------------------------- | ------------------------------------------- | --------------------------------------------------------------------------------- |
| Membership domain theory                    | Advanced working model                      | Close outstanding business decisions and preserve the adopted vocabulary          |
| Membership domain kernel                    | Implemented for initial scope               | Principal acceptance and future lifecycle capabilities                            |
| Application and persistence                 | Initial slices implemented                  | Complete application wiring, production readiness, and lifecycle use cases        |
| Domain invariants and formal verification   | Strong initial foundation                   | Continue verification as the model expands; resolve regression baseline           |
| Organisational participation and Governance | Research/discovery stage                    | Define committee, role, position, mandate, authority, and participation semantics |
| Election integration                        | Existing architecture decisions established | Implement and verify the Membership integration without breaking Election-Only    |
| Finance integration                         | Target boundary established                 | Separate fee/payment responsibilities and integrate evidence                      |
| Audit and evidence                          | Target direction established                | Implement fact-stream consumption and audit projections                           |
| Migration and legacy retirement             | Significant plan exists                     | Dry run, parity, cutover, rollback, and removal of conflicting legacy paths       |
| Complete user-facing product                | Not complete                                | Application/API/UI wiring, permissions, workflows, and end-to-end verification    |

I would not assign a precise overall completion percentage at this stage. We have no single, evidence-based denominator covering theory, business decisions, implementation, migration, integration, and production readiness. A number such as 70% would create false precision.

The better assessment is: the foundational Membership model and its first technical slices are substantially established; the end-to-end product and organisational Governance model remain unfinished.

## 3. The remaining TODOs, in priority order

### P0 — Establish a trustworthy baseline and close Slice 1b

These are the immediate acceptance tasks. They should precede new feature development.

### Slice 1b acceptance checklist

0 of 6 checked

Complete Principal Review of Slice 1b

Verify the PostgreSQL adapter, transaction boundaries, version checks, tenant-scoped constraints, append-only facts, idempotency under concurrent retries, and error handling against the frozen contract.

Record and classify test failures

Preserve the documented baseline comparison; identify pre-existing failures, flaky tests, excluded legacy tests, and any genuinely new failures. Do not label the complete suite green.

Close implementation documentation

Add backlog index entries for PBDIGIT-258 through PBDIGIT-262, remove duplicate Slice 1a guide entries and duplicated checklist/status lines, and add the missing database developer guidance.

Resolve the test-isolation concern

Document and fix the PostgreSQL test-transaction behaviour so persistent test data cannot contaminate later tests. Keep the solution aligned with the existing test architecture.

Verify deployment prerequisites

Confirm the intended PostgreSQL configuration in the target environment. The migration is deployable but has not been run in development or production according to the latest report; no production migration is authorised by that report.

Track PostgreSQL-only cleanup

Keep PBDIGIT-261 (database defaults and documentation) and PBDIGIT-262 (dead MySQL migration branches) as explicit technical-debt items. MySQL is legacy, not a supported target.

These checkboxes are a working checklist for this conversation, not a declaration that the repository tasks have been completed.

### P1 — Close the business decisions that block Slice 2

We must not allow implementation convenience to decide membership policy implicitly.

| Decision area                            | What must be clarified                                                                                                                                                             |
| ---------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Consent / declaration (BD-CONSENT, LQ-1) | Is an explicit person declaration required before membership admission? What evidence is acceptable, and how does it interact with applications, invitations, and imports?         |
| Recognition before membership (BD-REC0)  | Can a person be a recognised Member before a formal admission decision, or must recognition always be established through that decision?                                           |
| Termination semantics (BD-3)             | Which ending kinds are valid, what distinguishes them, and which authorised actors or organs may invoke them?                                                                      |
| Authority                                | Which organisational authority permits admission or ending? What evidence must be recorded? What is the legitimate interim implementation before the Governance model is complete? |
| Organisation-local date                  | How is “today” determined for an organisation? Membership transitions use calendar dates; `decidedAt` and `recordedAt` remain exact instants.                                      |

For each decision, Claude should provide source evidence, conflicting evidence, alternatives, consequences, a recommendation, the person or body authorised to ratify it, and whether it blocks implementation.

Important: a recommendation from Claude or from me is not automatically an adopted business rule. These decisions require explicit ratification by the appropriate authority.

### P2 — Define and implement Slice 2

Once the relevant decisions are ratified, prepare and approve the Slice 2 semantic and implementation contract.

Expected work includes:

- Wire the application use cases to the real PostgreSQL repository.
- Implement the agreed admission and ending workflows, including authority, consent, recognition, and refusal behaviour.
- Define application, HTTP/API, and Inertia/UI boundaries without leaking persistence details into the domain.
- Test accepted and refused commands, duplicate requests, stale versions, concurrent commands, and tenant isolation.
- Ensure the handlers do not bypass the domain decision process.
- Verify that persisted facts and the resolved Membership state remain consistent.

Slice 2 is not simply “add more CRUD.” It is the point at which the domain model starts to become a usable application workflow. The business decisions above must determine its behaviour.

### P3 — Complete the Membership lifecycle

The current kernel and persistence slices are deliberately narrow. The later roadmap still includes:

- Renewal and membership-period handling.
- Membership-type assignment and changes.
- Suspension and restoration semantics.
- Membership-number assignment and uniqueness.
- Correction, annulment, and voiding of recorded facts.
- Application and command idempotency across the complete lifecycle.
- Resolution of the remaining temporal and history edge cases.
- Verification of the invariant preventing conflicting membership periods.

These should be introduced in separately reviewed slices, not in one large implementation.

The adopted model remains:

- One `Member` aggregate for the person's organisational membership.
- One new Membership Period for each admission/re-entry.
- Re-entry creates a new period under the same Member.
- Membership status is derived from authoritative facts rather than maintained as a competing truth.
- Financial standing, committee participation, and election entitlement are not synonyms for Membership.

### P4 — Research and design Organisational Participation / Governance

This is one of the most important remaining pieces because your final product must represent how people actually participate in an organisation, not merely maintain a list of members.

The domain research must answer:

- Can a Member belong to multiple committees at the same time?
- How are committee membership, roles, positions, mandates, appointments, and delegated authority distinguished?
- Who appoints or removes a participant, and under which organisational rules?
- Are participation periods effective-dated? What happens when a role, committee, or mandate ends?
- Which responsibilities, activities, and financial obligations follow from each association?
- How are access rights to committee-specific discussion forums, documents, meetings, tasks, and notifications derived?
- Can a committee participant be a non-member, where the organisation's rules permit it?
- How are changes to committee membership reflected in permissions without erasing historical evidence?

The working conceptual model is:

Organisation Membership — `Member`

Recognised organisational belonging

Organisational Participation

Committee · Role · Position · Mandate · Effective period

Responsibilities, Obligations and Authority

Derived from authorised assignments and applicable rules

Access Policy and Service Projections

Forums · Documents · Meetings · Tasks · Notifications

This is a research model, not yet an approved implementation contract. In particular, forum access must not become the authoritative source of organisational authority, and Membership must not absorb the entire Governance domain.

Use formal models and bounded computation to test temporal consistency, role combinations, appointment conflicts, and permission derivation where the rules are sufficiently defined. Machine learning can help identify legacy records, candidate duplicates, and migration anomalies, but it must not decide legitimate membership, authority, or permissions.

### P5 — Complete Election integration without violating existing decisions

The Election boundary already has important adopted rules. Remaining work is to implement and test them end to end.

- Preserve Election-Only: an organisation can conduct an election without adopting the Membership module.
- Preserve Full Membership: the organisation's Membership registry supplies the relevant population.
- Keep Registration, Admission, VotingEntitlement, and Exercisability conceptually distinct.
- Keep VotingEntitlement as a derived concept; Membership does not grant it by itself.
- Apply the adopted rule for membership removal in Full Membership mode, including the established suspension behaviour for ElectionMembership.
- Preserve election-owned `voter_source_strategy` snapshots and existing architecture decisions.
- Verify that Membership changes, eligibility evaluation, and election lifecycle transitions behave consistently.
- Preserve voter anonymity: no voter identity in the Vote event.

The goal is not to rebuild the Election domain. It is to integrate Membership through an explicit contract while preserving the existing election model.

### P6 — Complete Finance and Audit boundaries

Finance

- Move fee/payment and settlement responsibilities to the Finance context as planned.
- Define the relationship between fee evidence and the relevant Membership Period.
- Establish one authoritative representation of fee/payment state.
- Publish the agreed integration evidence, such as `FeePaid`, for Finance consumption.
- Do not make payment a universal admission blocker without an adopted business rule.

Audit

- Consume authoritative Membership facts through the agreed integration boundary.
- Build audit projections that preserve actor, authority evidence, valid time, and recorded time.
- Distinguish corrections to historical records from the original decisions.
- Ensure Audit is a consumer of the relevant fact stream, not a competing owner of Membership truth.

### P7 — Migration, legacy retirement, and security

This is a significant delivery workstream, not a final housekeeping task.

- Inventory every legacy Member representation, status vocabulary, membership-number format, creation path, fee field, and reader/writer.
- Reconcile legacy data without inventing dates, silently deleting records, or automatically trimming overlapping intervals.
- Produce deterministic migration dry runs and exception reports.
- Compare legacy and target results and establish explicit parity criteria.
- Implement the single-writer and reverse-projection strategy required during cutover.
- Add write guards that cover bulk updates as well as ordinary writes.
- Plan per-organisation freeze, cutover, rollback, and retirement.
- Verify tenant isolation on every relevant read and write, using explicit organisation/tenant identifiers.
- Address the previously identified tenant-header and tenant-context risks through a separate security workstream.

Migration must preserve the distinction between what the legacy database says, what the evidence establishes, and what the new domain can legitimately conclude.

### P8 — Finish end-to-end product delivery

Once the domain and integration contracts are stable, the product still needs:

- Membership application and management screens.
- Member admission, renewal, suspension, restoration, and ending workflows.
- Committee and role administration.
- Organisation-specific rules and authority configuration.
- Forum and service access based on authorised participation.
- API validation, authentication, authorisation, and error handling.
- End-to-end tests for multiple organisations and tenant isolation.
- Operational logging, monitoring, deployment documentation, and support procedures.

This is where the architecture becomes a complete, usable platform for organisations rather than a set of technically sound domain components.

## 4. Recommended execution sequence

The safest path is to finish each gate before expanding scope.

## 01

Acceptance and evidence closure

Do now

Review Slice 1b, verify the concurrency fix, document regression limitations, close technical records, and confirm deployment prerequisites.

## 02

Business decision closure

Resolve consent, recognition, termination, authority, and organisation-local date semantics. Record which decisions are adopted and who ratified them.

## 03

Slice 2 contract and implementation

Approve the use cases, invariants, port interactions, refusal conditions, and tests before implementing the workflows.

## 04

Parallel bounded research

Continue Organisational Participation and Governance discovery without mixing unapproved Governance concepts into Membership implementation.

## 05

Lifecycle, integration, and migration

Extend Membership incrementally, integrate Election, Finance and Audit through explicit contracts, then migrate and retire legacy behaviour.

## 06

End-to-end product acceptance

Validate organisation-specific workflows, permissions, security, migration parity, operational readiness, and regression behaviour.

## 5. What I would prioritise as Principal Reviewer

Three principles should govern the next phase.

1\. Do not confuse theoretical completeness with business approval. A formal model can prove consistency under specified assumptions; it cannot decide what an organisation's rules ought to be.

2\. Do not confuse a working adapter with production readiness. Transaction and concurrency tests are valuable, but deployment configuration, test isolation, regression confidence, migration, and operational safeguards remain necessary.

3\. Do not implement every domain at once. Stabilise Membership, resolve the decisions blocking Slice 2, and research Governance as a separate bounded workstream. Integrate them only when their contracts are explicit.

The adopted Membership model and Election-Only constraint must remain stable unless an explicit decision changes them.&#x20;

## 6. How we will know the goal has been achieved

The project should be considered complete only when we can demonstrate all of the following:

- The Membership rules are explicit, approved, and traceable to requirements.
- The Membership lifecycle behaves correctly for admission, re-entry, terms, ending, suspension, and historical correction.
- Invariants and concurrency guarantees are tested, and regression status is understood.
- Organisational participation and authority are represented without overloading Membership.
- Access to organisational services is derived from authoritative assignments and approved policy.
- Election-Only works independently of Membership; Full Membership integrates correctly with elections.
- Finance and Audit have clear ownership and verified integration contracts.
- Legacy data has been reconciled and migrated with evidence, parity checks, and rollback capability.
- Tenant isolation, authorisation, and security boundaries have passed appropriate verification.
- End-to-end organisation workflows work in the deployed product.
- Legacy paths that conflict with the target architecture have been retired safely.

My immediate recommendation: do not start a broad new implementation phase yet. First obtain a Principal Acceptance verdict for Slice 1b, close the blocking Membership decisions, and prepare the Slice 2 contract. In parallel, continue the bounded Governance participation research. That gives us a controlled path from the current technical foundation toward the actual PublicDigit product goal.