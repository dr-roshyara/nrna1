# PublicDigit Membership — Principal Research Review and Next-Step Decision

## 1. Executive assessment

The attached file records a Slice 1b readiness review, not an implementation. Claude followed the required stop-before-coding rule and committed the review as `ca6e7f6fe`. No migration, database model, implementation story, or Slice 1b code was created. The principal blocker identified is EP-Q6: whether membership valid time is a calendar date or an exact instant.&#x20;

Eingefügter Text.txt



My verdict as principal reviewer: the review is directionally sound, and the stop was correct. However, we should distinguish three things before authorising implementation:

1. Domain truth: what it means for a person to be a member during a period.
2. Temporal semantics: whether those periods change by calendar date or by exact instant.
3. Persistence mechanics: how PostgreSQL enforces identity, ordering, tenant isolation, idempotency and concurrency.

A database schema must implement the first two; it must not decide them accidentally.

## 2. Current state and progress

Slice 0 — Domain kernel

Implemented; previously reviewed

Pure PHP semantic kernel for admission, ending, resolution and membership predicates. The current file does not report any change to this kernel.

Slice 1a — Application and repository contract

Implemented; previously reviewed

Repository port, command handlers, authorisation/re-decision flow, idempotency contract and in-memory reference adapter.

Slice 1b — Database adapter

Blocked by EP-Q6

The candidate schema, transaction design and test strategy have been drafted. Implementation has not started.

Governance / committee participation

Separate research track

Do not mix committee roles, positions, participation or forum permissions into the persistence slice.

The review explicitly preserves the existing Membership kernel and application handlers, defers legacy migration, and excludes Governance, Election, Finance, UI and later membership capabilities.&#x20;

Eingefügter Text.txt



## 3. The principal decision: EP-Q6

The unresolved question is not simply which SQL type we prefer. It is:

Does membership begin or end at an organisation-calendar date boundary, or can it begin and end at an exact instant?

T1

Calendar-date semantics — recommended for consideration

Membership changes at date boundaries. A term ending after 31 December is represented by an exclusive end of 1 January. There is no intra-day membership change.

Persistence: PostgreSQL `date` for valid-time values; the domain's `TimePoint` uses a date ordinal.

T2

Exact-instant semantics

Membership can change within a day. A query such as “Was this person a member on 31 December?” must define the relevant organisation-local date boundaries.

Persistence: PostgreSQL `timestamptz(6)` for valid-time values; the domain's `TimePoint` uses epoch microseconds.

The repository evidence favours neither option conclusively. Existing membership timestamps are populated using `now()`, renewals use calendar-month arithmetic, and fee due dates use `date`. These are clues about current implementation, not authoritative proof of the organisation's membership rules.&#x20;

Eingefügter Text.txt



### My recommendation

I recommend T1, calendar-date semantics, provisionally, provided the business confirms that membership cannot start or end part-way through a day.

Why it is a strong candidate:

- It matches the apparent calendar-based nature of membership terms and renewals.
- It makes statements such as “member on 31 December” easier to interpret consistently.
- It avoids making membership validity depend on time-zone conversions for every valid-time comparison.
- It is simpler to explain to administrators and members.

But this is a business rule, not a technical optimisation. If an organisation must revoke membership at 14:30, or a legally significant decision takes effect at a precise instant, T1 may be wrong.

Do not let Claude decide this alone from legacy column types. The responsible product/domain authority must ratify the semantic choice. If that authority has already made a decision elsewhere, Claude should locate and cite it before asking again.

One important distinction: `decidedAt` and `recordedAt` remain exact instants regardless of the chosen valid-time model. The attached review already separates these clocks.&#x20;

Eingefügter Text.txt



## 4. Principal review of the proposed database design

The candidate design has three tables: `org_memberships`, `org_membership_facts` and `org_membership_commands`. It proposes one Member identity per organisation/person, append-only facts, optimistic versioning, tenant-safe foreign keys and tenant-scoped idempotency.&#x20;

Eingefügter Text.txt



I support this direction in principle, with the following review conditions.

| Topic                                  | Principal assessment                                  | Required treatment                                                                         |
| -------------------------------------- | ----------------------------------------------------- | ------------------------------------------------------------------------------------------ |
| One Member per person and organisation | Consistent with the adopted membership identity model | Enforce `UNIQUE (organisation_id, person_ref)`                                             |
| Fact history                           | Correct direction                                     | Append facts; do not overwrite historical facts                                            |
| Tenant isolation                       | Essential                                             | Explicit tenant filtering and composite foreign-key protection                             |
| Concurrency                            | Correct direction                                     | Atomic version check and append; stale decisions must be re-evaluated by the application   |
| Idempotency                            | Correct direction                                     | One tenant-scoped key maps to one command result; a conflicting reuse must be refused      |
| Initial membership term                | Important semantic detail                             | Preserve the three distinct cases: no term, bounded term and unlimited term                |
| Fact-ID uniqueness                     | Good defensive constraint                             | Keep the unique constraint; do not silently expand the application port                    |
| Legacy migration                       | Correctly deferred                                    | Do not migrate or infer historical facts in Slice 1b                                       |
| Event Sourcing                         | Explicitly excluded                                   | Implement the required fact history without introducing a generic event-sourcing framework |

The proposed `append` operation combines the version update, fact insertion and idempotency record in one transaction. The application—not the database adapter—must repeat authorisation and domain decision-making after a concurrency conflict.&#x20;

Eingefügter Text.txt



### Four points I would make explicit before implementation

1\. Resolve the initial-term representation against the domain contract.

The design distinguishes `none`, `until` and `unlimited`. This is necessary because a missing term and an unlimited term are not equivalent. The implementation must preserve the existing Slice 0 interpretation: an admission without an initial term is Lapsed, not automatically in force.

2\. Keep the repository port stable.

The review correctly identifies that translating a database FactId uniqueness violation into a new `DuplicateFactId` application outcome would change the port. Keep the database constraint as a defensive safeguard; unexpected violations should surface as infrastructure defects unless the contract is deliberately revised through a separate decision.

3\. Treat concurrency evidence proportionately.

A sequential contract suite alone does not demonstrate every real interleaving. Atomic compare-and-set operations and database constraints provide the core correctness guarantees; an optional two-connection test provides additional evidence. Do not describe the optional test as a substitute for sound transaction design.

4\. Verify the actual database engine and test setup.

The candidate review assumes PostgreSQL and references Laravel's test infrastructure. Before coding, verify the repository's actual database engine, connection configuration and test database. If these differ from the assumptions, report the discrepancy instead of quietly adapting the design.

The attached review includes the shared contract-suite refactor and typed temporal columns as proposed refinements, rather than changes to the domain kernel.&#x20;

Eingefügter Text.txt



## 5. Recommended next-step sequence

Do not start Slice 1b by writing migrations immediately. Use this order:

1

Ratify EP-Q6

Confirm calendar-date semantics or exact-instant semantics. Search existing approved decisions first; if none exists, raise the decision to the appropriate domain/product authority.

2

Freeze the Slice 1b implementation contract

Record the chosen temporal mapping, schema, atomic transaction, error mapping, append-only policy and test strategy. Resolve or explicitly accept the minor review points.

3

Implement one small persistence slice with TDD

Start with failing adapter contract tests, implement the minimal PostgreSQL adapter, then run the shared contract suite and database-specific invariant tests.

4

Verify and stop

Run the focused suite, independent reference-model checks, architecture tests and baseline regression comparison. Publish the review and stop before legacy migration or later capabilities.

This follows the intended engineering protocol: research and semantic agreement first, then a bounded implementation, verification and a formal stop. The existing review already has a candidate schema and test plan, so we should not repeat the entire research exercise unless the temporal decision exposes a contradiction.&#x20;

Eingefügter Text.txt



## 6. Copy-ready prompt for Claude

My recommendation is to give Claude a gated execution prompt. It should investigate whether EP-Q6 has already been decided, prepare the implementation contract, and implement only when the temporal decision is authoritative. This avoids another round of unnecessary research while preventing an unapproved business rule from entering the schema.

# PublicDigit — Membership Slice 1b: Ratify Temporal Semantics, Freeze the Contract, Implement with TDD

## 1. Your role and authority

Act as the implementation architect and researcher working under the Principal DDD Architect / Principal Reviewer.

The Principal Reviewer owns architecture governance. You investigate evidence, identify contradictions, propose technically sound solutions, implement only authorised decisions, and provide verifiable results.

You must not silently redefine the PublicDigit domain model, alter accepted contracts, or turn a technical recommendation into a business decision.

Follow this execution protocol:

**Existing decisions and evidence → Semantic contract → Implementation plan → TDD implementation → Verification → Principal review → STOP.**

## 2. Read the current evidence first

Read the following files completely, using the repository's actual paths:

1. `docs/publicdigit/reviews/2026-10-09-membership-slice1b-readiness-review.md`
2. `docs/publicdigit/membership/architecture/brainstorming/202610090914_implementation.md`
3. The Slice 0 Semantic Design Contract.
4. The Slice 0 implementation and review.
5. The Slice 1a contract, implementation review and developer guide.
6. EP-01 Revision 1, its Principal Design Review, and the relevant ratified decisions.
7. The current Membership repository port, application handlers, in-memory adapter, contract tests and reference-model tests.

Locate the authoritative versions in the repository. Do not assume that a prior session summary or a proposed document is itself an approved business decision.

Inspect the actual database engine, Laravel configuration, test database and existing migration conventions.

## 3. First gate: resolve EP-Q6 from authoritative evidence

EP-Q6 asks whether Membership valid time uses:

- **T1 — Calendar date:** date-based membership boundaries; no intra-day membership transitions.
- **T2 — Exact instant:** precise instant-based membership boundaries.

First search the existing approved decisions, requirements, statutes/business rules and architecture records.

Distinguish explicitly between:

- approved business decisions;
- ratified architecture constraints;
- repository observations;
- hypotheses and recommendations.

Legacy timestamps populated using `now()`, calendar-month renewal arithmetic, and fee `DATE` columns are evidence, not proof of the intended membership semantics.

If an authoritative decision already exists, cite its exact source and apply it.

If no authoritative decision exists, prepare a concise decision request that states the alternatives, consequences, recommendation and business question. Recommend T1 only as a hypothesis pending confirmation; do not ratify it yourself.

**STOP before schema implementation if EP-Q6 remains unresolved.** Report the missing decision and wait for the authorised decision. Do not create a story, migration or model merely to make progress.

## 4. Freeze the Slice 1b contract

Once EP-Q6 is authoritatively resolved, update the Slice 1b implementation contract before coding.

The contract must define:

- valid-time representation and mapping to `TimePoint`;
- `decidedAt` and `recordedAt` as separate instants;
- the distinction between no initial term, a bounded initial term and an unlimited term;
- one Member identity per `(TenantId, PersonRef)`;
- PeriodId derived from the Admit FactId;
- fact ordering and version semantics;
- tenant isolation and database constraints;
- atomic append and idempotency behaviour;
- error translation;
- append-only history;
- required tests and scope boundaries.

The contract must preserve the accepted Slice 0 and Slice 1a semantics. If an implementation requirement conflicts with them, document the conflict and stop instead of silently changing the contract.

## 5. Scope of authorised implementation

Implement only the real database adapter for the existing `MemberRepositoryPort`, its minimal required migrations, and its tests.

Do not redesign the domain kernel or application handlers.

Do not introduce:

- Event Sourcing infrastructure;
- a generic persistence framework;
- new domain outcomes merely to simplify database error handling;
- stored membership status as authoritative truth;
- legacy-data migration;
- Governance, Committee, Participation, Position, Tenure or Appointment functionality;
- Election, Finance, UI or API changes;
- Renew, ChangeType, Suspend, Restore, Correct, Annul or Void functionality.

Do not implement anything outside this scope without a separate decision and authorisation.

## 6. Persistence invariants

The implementation must preserve these guarantees:

**Identity**

- Enforce uniqueness of `(organisation_id, person_ref)`.
- Use explicit tenant identity in every repository operation.
- Prevent a fact from being associated with a Member belonging to another tenant.
- Enforce FactId uniqueness within a Member.

**Facts and time**

- Persist ordered facts without updating or deleting historical facts.
- Preserve `effectiveAt`, `decidedAt`, `recordedAt` and `seq` as distinct concepts.
- Use `seq = basedOnVersion + 1`; do not add a redundant `based_on_version` column unless a correctness argument demonstrates why it is necessary.
- Preserve the exact semantics of the initial-term states.
- Do not infer business dates from infrastructure timestamps.

**Concurrency**

- Perform version checking, fact insertion, idempotency registration and version advancement atomically.
- A stale decision must not be appended.
- On conflict, return the contract-defined result. The application must reload, re-authorise, rerun the domain guards and recompute the decision.
- Never retry a stale append using the old decision.
- Do not bypass the application-level decision flow.

**Idempotency**

- Scope keys to the tenant.
- Repeating the same key and command must return the contract-defined original result.
- Reusing a key for a different command must be refused.
- Preserve the accepted rule that refused commands are not permanently remembered as successful appends.

**Error mapping**

- Translate only the failures defined by the existing port.
- A holder-creation conflict maps to the existing `HolderExists` outcome.
- A version conflict maps to the existing concurrency outcome.
- An idempotency conflict maps to the contract-defined result.
- An unexpected FactId uniqueness violation remains an infrastructure error unless an approved contract change explicitly says otherwise.

## 7. TDD execution

Implement in the smallest useful increments.

For each increment:

1. Write a failing test describing one required behaviour.
2. Confirm the test fails for the intended reason.
3. Implement the minimum code needed to pass it.
4. Refactor without changing semantics.
5. Run the focused tests.
6. Run the shared repository contract tests.
7. Verify the relevant invariants and architecture boundaries.
8. Record the result before proceeding.

The existing contract suite must be reusable for the in-memory and database adapters. If a test-infrastructure refactor is needed, such as extracting the suite into a trait, preserve its semantics and test coverage.

## 8. Required verification

At minimum, test:

- the entire shared repository contract against the database adapter;
- creation uniqueness under repeated and conflicting requests;
- stale-version append rejection;
- exactly one fact for successful idempotent retries;
- conflicting idempotency-key reuse;
- atomic rollback when part of an append fails;
- fact ordering and version advancement;
- all three initial-term states;
- round-trip temporal precision according to ratified EP-Q6;
- monotonic `recordedAt` under a backward-moving clock;
- tenant isolation, including an attempted cross-tenant fact attachment;
- FactId uniqueness;
- prevention of fact updates and deletes through the adapter;
- handlers and persistence against the independent reference model;
- architecture tests proving that the kernel remains framework-free and the application does not depend on the database adapter.

Use the existing test database configuration. If real concurrency guarantees need further evidence, add a two-connection test where feasible. Do not claim that a sequential test alone demonstrates every possible interleaving.

Compare the regression results with the established baseline. Separate pre-existing failures, flaky tests, environment failures and new regressions. Never claim the full suite is green unless it actually passes.

## 9. Review and stop

Create or update the implementation report with:

- approved decisions and exact sources;
- the final contract and schema;
- changed files and commits;
- tests run and actual results;
- concurrency, idempotency and tenant-isolation evidence;
- regression comparison;
- remaining risks and unresolved decisions;
- any divergence from the plan.

Update the relevant project documentation and state synchronisation files.

Do not push changes unless explicitly authorised.

After Slice 1b passes its acceptance criteria, STOP. Do not automatically proceed to Slice 2 or Governance work.

Return the implementation report and the precise next recommended step for Principal Reviewer approval.

## 7. Can you tell Claude, “Now read the next implementation file and follow it”?

Yes, but I would not use that wording alone. “Follow it” can encourage Claude to treat an unratified proposal as authoritative, overlook contradictions, or begin coding before a required decision is made.

Use this shorter instruction when you want Claude to examine a new implementation file:

Now read the next implementation file completely. Treat it as a candidate implementation contract, not automatically as an approved instruction.

First compare it with the ratified architecture, Membership theory, Slice 0 and Slice 1a contracts, the current implementation, and all relevant decisions.

Report:

1. What is consistent and can be followed.
2. What conflicts with an existing decision or implementation contract.
3. Which business or architectural decisions remain unresolved.
4. Which assumptions need evidence.
5. The smallest safe next implementation slice and its TDD acceptance criteria.

Do not implement anything while a blocking decision remains unresolved. If the contract is consistent and all required decisions are authorised, implement only the smallest approved slice using RED → GREEN → refactor → verification. Preserve existing contracts, report regression results, document deviations, and stop for Principal Reviewer approval before moving to the next slice.

## 8. Final principal-researcher recommendation

The next action is not another broad Membership research round. The existing Slice 1b review already provides a credible candidate schema, transaction strategy and test plan.&#x20;

Eingefügter Text.txt



The immediate decision sequence is:

1. Resolve EP-Q6 from an authoritative source or obtain explicit domain/product ratification.
2. Confirm the proposed persistence refinements without expanding the repository contract.
3. Freeze the Slice 1b contract.
4. Implement the database adapter with TDD.
5. Verify invariants, concurrency behaviour and regression results.
6. Stop for principal review.

Keep the larger objective in view: a defensible Membership domain model, with identity and lifecycle semantics established before participation, committee responsibilities, financial obligations and service-access policies are implemented. Persistence should make that theory enforceable; it should not become the source of the theory.