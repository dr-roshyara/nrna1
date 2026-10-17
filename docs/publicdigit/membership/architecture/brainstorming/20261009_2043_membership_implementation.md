# PublicDigit Membership — Principal Architecture Review and Next-Step Direction

The attached report indicates that Claude has completed Membership Slice 1b: PostgreSQL persistence adapter and stopped before Slice 2 or Governance implementation. My recommendation is to treat this as a successful engineering milestone, but not yet as final architectural acceptance. The next task should be a focused principal review of the implementation's correctness and the remaining domain decisions—not another broad research cycle.

The most important achievement is that real concurrent-process tests exposed an idempotency defect that ordinary unit tests had not revealed. Claude reports fixing it in both the persistence adapter and, with your authorisation, `DecideAndAppend`. The implementation review records passing new acceptance tests and no new regressions against the baseline.&#x20;

Eingefügter Text.txt



## 1. Current state

Slice 0 — Semantic kernel

Implemented. Pure domain model, temporal semantics and membership predicates.

Slice 1a — Application and repository contract

Implemented. Port, command handlers, authority-check boundary, concurrency and idempotency flow. One authorised correction was made to `DecideAndAppend`.

Slice 1b — PostgreSQL persistence

Implemented. Database adapter, three tables, tenant-scoped constraints, temporal mappings, repository contract tests, property tests and concurrency tests.

Outstanding engineering obligations

Four pre-existing Architecture failures, legacy Membership test failures, documentation/index work, a test-isolation concern and production database verification remain. The new slice is reported green, but the entire repository is not.

The evidence and outstanding items are documented in Claude's implementation review.&#x20;

Eingefügter Text.txt

Eingefügter Text.txt



## 2. Principal review: what needs attention

### A. Idempotency is now a domain-application contract, not merely a database concern

The concurrent retry defect is the most valuable finding in this slice. A command can be applied successfully by one process while another process is still evaluating the same command. The second process must return the original successful outcome rather than a domain refusal such as `DuplicateFactId`.

Claude reports testing both relevant interleavings and updating the Slice 1a contract.&#x20;

Eingefügter Text.txt



My requirement: preserve the invariant that one idempotency key and command fingerprint produce at most one appended fact, and retries return the contract-defined original outcome. Review this at the application and repository boundary, not just in SQL.

### B. The regression claim is appropriately qualified

Claude reports that the failing legacy test sets match the baseline. That is useful evidence, but it is not equivalent to saying all tests pass. The baseline checkout shared `vendor/`, and certain legacy tests were excluded from the comparison because of the test-trait change.&#x20;

Eingefügter Text.txt



Require the review to retain that limitation and avoid claiming a stronger regression proof than the actual comparison supports.

### C. The persistence implementation must not become the Membership domain model

The three-table schema is infrastructure. It must not dictate whether a person is a member, whether membership is in force, or whether the person is entitled to vote. Those are derived from the approved domain semantics.

The next phase must preserve these distinctions:

- Member identity and recognised organisational membership.
- Membership period and its temporal validity.
- Lifecycle facts and their resolution.
- Committee participation, roles, positions and mandates.
- Financial obligations and payment facts.
- Election registration, voting entitlement and the ability to exercise that entitlement.

This is particularly important because PublicDigit must support both Election-Only organisations and Full Membership organisations. The Election-Only route must not become dependent on the Membership lifecycle.

### D. Do not automatically start Slice 2

Claude identifies unresolved decisions around consent, recognition and termination kinds, plus organisation time-zone handling. These are meaningful business rules, not implementation details.&#x20;

Eingefügter Text.txt



The correct next step is a bounded decision-discovery task followed by an explicit implementation contract. Do not let Claude invent an interim authority or consent policy simply to get the UI working.

## 3. Recommended next step

I recommend a Principal Acceptance and Membership Decision-Closure pass, followed by a small Slice 2 readiness review.

Why this order?

1. Confirm that Slice 1b satisfies the approved contract and document any residual risks.
2. Close the missing documentation and test-infrastructure records without expanding the implementation scope.
3. Resolve the business decisions that block admission and ending.
4. Keep organisational participation and Governance research as a separate, bounded track.
5. Authorise Slice 2 only after its semantic contract is reviewed.

This prevents two opposite mistakes: continuing to research things that are already sufficiently settled, and implementing business rules that remain undecided.

## 4. Copy-ready prompt for Claude

# PublicDigit — Principal Acceptance Review, Membership Decision Closure and Next-Slice Readiness

## 1. Role and working principles

Work as the implementation architect and researcher under the Principal DDD Architect / Principal Reviewer.

The objective is to derive and implement a robust, practically usable Membership domain model—not merely to make tests pass or complete database tables.

Use DDD, requirements engineering, formal logic, temporal modelling, property-based testing, concurrency analysis and statistical/computational techniques where they provide meaningful evidence.

Maintain the distinction between:

- domain truth;
- business requirements and decisions;
- architecture contracts;
- application orchestration;
- persistence and infrastructure;
- derived queries and projections.

The current corpus is research material, not automatically authoritative truth. Respect ratified decisions and accepted contracts; challenge proposals and legacy assumptions when evidence warrants it.

## 2. Phase A — Principal acceptance review of Slice 1b

Read completely:

- `docs/publicdigit/reviews/2026-10-09-membership-slice1b-contract.md`
- `docs/publicdigit/reviews/2026-10-09-membership-slice1b-implementation-review.md`
- `docs/publicdigit/reviews/2026-10-09-membership-slice1a-contract.md`
- `developer_guide/membership/02_slice1a_repository_and_concurrency.md`
- `developer_guide/membership/03_slice1b_postgres_adapter.md`
- the Slice 0 contract and implementation review;
- the actual implementation, migration, tests and relevant commits.

Treat Claude's reported test results as evidence to inspect, not as proof that replaces review.

Check the following:

1. The domain kernel and repository port preserve the approved semantics.
2. `DecideAndAppend` returns the original successful outcome for an identical concurrent retry.
3. Idempotency keys, fingerprints, version checks and transaction boundaries are consistent across the application and database.
4. Tenant identity is enforced in every relevant repository operation and foreign key.
5. The three initial-term states and calendar-date semantics are preserved.
6. Unexpected database failures are not silently translated into domain refusals.
7. No stored status or persistence mechanism has become authoritative membership truth.
8. Architecture tests enforce the intended dependency direction.
9. The regression comparison accurately distinguishes new failures from baseline failures and documents its methodological limitations.

Issue an explicit verdict: ACCEPT, ACCEPT WITH CONDITIONS, or REJECT.

Do not repeat every successful test unnecessarily. Run further tests only where the review identifies a gap, contradiction or unverified claim.

## 3. Phase B — Close the implementation record

Inspect the actual project state before modifying anything.

Resolve the remaining Slice 1b administrative items:

- index rows for the relevant backlog stories;
- a backlog item for the misleading PostgreSQL test-transaction documentation;
- the production database-engine verification prerequisite;
- clear documentation that the migration has not yet been rolled out;
- any missing database developer-guide or session-state updates.

Do not modify production data or execute the migration against development or production as part of this task.

MySQL was legacy and is not a supported database. Correct obsolete defaults and dead compatibility code through appropriately scoped work; do not preserve incorrect architecture merely for historical compatibility.

Avoid duplicating documentation, backlog entries or contract decisions that already exist.

## 4. Phase C — Derive the next Membership domain decisions

Investigate the existing requirements, contracts, user-facing workflows, domain theory, tests and legacy implementation.

Focus on the decisions that block the next admission/ending application slice:

- BD-CONSENT / LQ-1: whether and how a person's declaration or consent is required;
- BD-REC0: when recognised Membership can first exist and whether any pending state is permitted;
- BD-3: which ending kinds are legitimate and what each means;
- the authority required to admit or end Membership;
- the organisation-local calendar semantics required when commands use "today".

For each decision, report:

1. The exact business question.
2. Evidence from existing approved sources.
3. Conflicting or incomplete evidence.
4. The candidate alternatives.
5. The consequences for domain invariants, lifecycle, history and application behaviour.
6. Your recommendation and why.
7. The authority that must ratify the decision.
8. Whether the decision blocks Slice 2 or can be deferred.

Do not turn an unapproved recommendation into a domain rule.

Avoid broad re-research of already ratified Membership identity and temporal semantics unless a specific contradiction is discovered.

## 5. Phase D — Preserve the canonical Membership model

Keep the following distinctions explicit:

`Person ≠ OrganisationAffiliation ≠ Membership/Member ≠ MembershipPeriod ≠ ElectionRegistration ≠ Admission ≠ VotingEntitlement ≠ Exercisability`.

Respect the adopted vocabulary: Organisation Membership is represented by the `Member` aggregate.

A Member can later participate in committees, hold roles or positions, receive mandates, have obligations, and obtain access to organisational services. These relationships must not be collapsed into the basic Membership identity.

Membership does not itself grant voting entitlement.

Election-Only organisations must remain able to run elections without implementing or creating organisational Membership.

Do not introduce a new Membership aggregate or rewrite the identity model without an explicit, evidence-based architectural decision.

## 6. Phase E — Separate organisational participation research

Do not implement committee participation as part of Membership Slice 2.

Prepare a bounded research contract answering:

How does an organisation represent the relationship between a Member and its committees, roles, positions or mandates, and how can these relationships derive responsibilities, obligations and access to services?

Investigate:

- cardinalities and temporal validity;
- appointment, assignment, termination and succession;
- authority and decision provenance;
- responsibilities versus permissions;
- financial obligations versus actual payments;
- committee/forum access as a derived policy or projection;
- ownership boundaries between Membership, Governance, Finance and service contexts.

Use formal models or bounded computational checks when they can expose counterexamples, but state the bounds and assumptions. Use machine learning only as an advisory tool for discovery, clustering or migration analysis; never as the authority for business truth.

Return the research contract for Principal Reviewer approval. Do not implement Governance slices yet.

## 7. Phase F — Prepare Slice 2, but do not implement prematurely

Once the blocking business decisions have been ratified, prepare the smallest practical TDD implementation contract for application-level admission and ending.

The contract must identify:

- exact commands and use cases;
- actor and authority boundaries;
- consent and recognition rules;
- organisation time-zone responsibilities;
- domain refusals and application outcomes;
- persistence port interactions;
- HTTP/service integration boundaries;
- acceptance tests and negative cases;
- required concurrency and idempotency tests;
- out-of-scope behaviour.

Do not redesign Slice 0 or Slice 1b to accommodate application wiring.

Implement only after the contract is approved. Use RED → GREEN → refactor → verification and stop after the authorised slice.

## 8. Required output

Produce one concise but evidence-rich report containing:

1. Slice 1b acceptance verdict.
2. Verified findings and unresolved defects.
3. Remaining documentation and technical-debt actions.
4. The decision register for Slice 2.
5. The separate organisational-participation research contract.
6. The recommended next implementation slice and its entry criteria.
7. An explicit STOP point.

Do not push changes unless authorised. Do not claim acceptance, test success, ratification or deployment verification without evidence.

Optimise for domain correctness, architectural coherence and verified progress—not the number of documents, tests or completed tasks.

## 5. What I would tell Claude now

Use the long prompt above if you want to establish the next phase formally. If you want to keep momentum with a shorter instruction, use this:

Slice 1b is ready for Principal Reviewer acceptance. First review the implementation against its approved contract and the existing Membership theory; verify the important claims and preserve the limitations of the regression evidence. Then close the remaining documentation and project-state items.

Next, investigate only the business decisions that block Slice 2: consent, recognition, ending kinds, authority and organisation-local date handling. Present the evidence, alternatives, recommendation and exact ratification needed. Do not implement Slice 2 until its semantic contract is approved.

Keep organisational participation, committees, roles, obligations and service access as a separate research track. Preserve the canonical Member aggregate and the Election-Only mode.

Work proportionately: reuse settled decisions, avoid unnecessary research and documentation, use TDD for implementation, and stop at the approved boundary.

My principal recommendation is to accept the reported engineering progress provisionally, verify the key concurrency fix and regression evidence, and then close the business decisions needed for Slice 2. The Governance participation track should proceed as separate research so that the platform's longer-term domain model continues to improve without blocking the next useful Membership capability.