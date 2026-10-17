# PublicDigit Membership — Independent Principal Review of the New Plan

I have reviewed Claude’s latest Option E design and readiness plan. It is more concrete than the previous version: it identifies the repository boundary, proposes explicit outcomes, specifies test-first work, and keeps PostgreSQL testing behind the existing safety gates.

My verdict: revise the plan before implementation. Option E remains a good candidate, but I would not approve the current design unchanged. In particular, the idempotency precedence question is a real contract issue, not a minor implementation detail.

## 1. What is sound in the plan?

- Preserving `TimePoint`: correct. A general temporal abstraction should not inherit database-specific limits merely to make one adapter convenient.
- Separating technical limits from business rules: correct. Option E should not decide whether a Membership date is legally or operationally plausible.
- Explicit persistence outcomes: sound, provided the outcome is represented consistently across the port, adapters, and application.
- Test-first implementation: correct. Demonstrate the current gap with a genuine assertion failure before implementing the fix.
- Failing loudly on an unhandled outcome: directionally correct. An unhandled result must never be silently interpreted as a concurrency conflict.
- Deferring PostgreSQL integration: appropriate, provided the change is limited to the no-database phase and the production-specific behaviour is subsequently verified under the approved safety procedure.

These findings are grounded in the attached plan; I have not independently inspected the live repository or run its tests in this turn.

## 2. Issues that must be resolved before implementation

### Issue A — Idempotency must not be accidentally weakened

Claude proposes checking whether a date is storable at the beginning of `append()`, before any read or write, and recommends that “not storable” take precedence over an idempotency-key conflict.

I do not endorse that precedence yet.

Consider a command submitted with an idempotency key that has already been used for a different command. If the new submission contains an unsupported date, should the caller receive:

- a storage-representability refusal, or
- an idempotency-key conflict?

The correct answer depends on the existing idempotency contract and its intended semantics. A precheck before every read could change the observable behaviour of that contract.

My recommendation is to have Claude inspect the existing idempotency rules and tests, then explicitly specify the ordering of:

1. Idempotency-key recognition and payload-conflict detection.
2. Date-representability validation.
3. Authorization, concurrency, and append behaviour, as required by the existing contract.

This is a requirements decision supported by code evidence, not something to settle merely because one order is easier to implement. Preserve the current contract unless an intentional change is approved.

### Issue B — A shared persistence contract must have a justified home

Claude proposes a small range class under `Application/Organisational/Ports`, referenced by both the PostgreSQL adapter and test double.

This is plausible, but the design needs to establish:

- Whether the repository port genuinely owns the storage capability being described.
- Whether a small framework-free contract definition can be shared without introducing an undesirable dependency.
- Whether `DateOrdinal` can use the shared definition while remaining the final conversion safeguard.
- Whether the test double and contract tests can reuse the same definition without making tests pass merely because they repeat the same implementation mistake.

Important testing principle: sharing the range definition is reasonable; sharing all the validation logic between production and test code can reduce the test’s ability to detect errors. Boundary expectations should be independently asserted.

### Issue C — Exhaustive mapping must protect the retry semantics

The plan proposes replacing the current switch with a `match` that fails loudly for an unhandled outcome.

That is a reasonable approach in PHP, but the test should verify more than the absence of a misleading final error. It should establish that:

- Only a concurrency conflict triggers the intended retry path.
- Non-retryable outcomes return their corresponding result without retrying.
- Every currently defined `AppendOutcome` has an explicit mapping.
- A future unmapped outcome cannot silently become `ConcurrentModification`.
- The existing retry limit and concurrency tests remain valid.

An `UnhandledMatchError` is a useful fail-fast mechanism, but it is not a substitute for a complete mapping test.

### Issue D — The range must be derived from the actual conversion contract

The plan gives the inclusive range as `0001-01-01` through `9999-12-31`, with corresponding ordinals. That matches the earlier reported `DateOrdinal` bounds.

Before implementation, Claude should verify the constants and conversion semantics directly. The contract should distinguish:

- A date that is representable and storable.
- A temporal value such as infinity that is not a finite calendar date.
- A domain-valid date that may nevertheless be technically unstorable.
- A storable date that may still be semantically wrong, such as a legacy open-ended placeholder.

Option E must not automatically reinterpret `9999-12-31` as infinity. That remains a separate migration-policy decision.

## 3. Prompt for Claude: revise the design before implementation

Send Claude this prompt. It explicitly incorporates the review findings and prevents implementation from starting prematurely.

# PublicDigit Membership — Option E Design Revision After Independent Principal Review

## Objective

Revise the existing “Option E: Implementation Design and Readiness Plan” in response to the independent ChatGPT Principal Review.

Act as a senior DDD architect, requirements engineer, persistence architect, test engineer, and research analyst.

The goal is to establish a robust Membership implementation, preserve existing approved invariants, and close the verified persistence-contract gap with the smallest justified change.

## 1. Roles and authority

**Claude — Investigator, planner, and later implementer**

- Inspect actual source code, tests, and existing decision records.
- Revise the architecture and requirements plan based on evidence.
- Do not implement until the revised plan has been independently reviewed and the Human PO/ARB has explicitly authorized implementation.

**ChatGPT — Independent Principal Reviewer**

- Challenge the revised plan, including idempotency semantics, persistence boundaries, error outcomes, retry behaviour, and evidence quality.
- Do not treat a recommendation as an approved decision.
- Review the actual proposed contract, not just its description.

**Human PO/ARB — Decision authority**

- Approve, revise, or reject the final architecture.
- Resolve business-policy decisions and record formal verdicts.
- Authorize implementation as a separate action.

No previous authorization automatically authorizes this increment.

## 2. Mandatory restriction: plan revision only

This phase is read-only.

Do not edit source files, tests, plans, documentation, or project-state files. Do not run tests or scripts, access databases, provision infrastructure, stage files, commit, or push.

Inspect the actual repository and current HEAD where available. Distinguish observed facts, inferences, recommendations, and decisions requiring approval.

Return the revised plan and stop.

## 3. Resolve idempotency precedence before proposing code

The previous plan proposes checking date representability at the start of repository append, before any read, and recommends that “not storable” take precedence over an idempotency-key conflict.

Do not assume this precedence is correct.

Inspect:

- The existing idempotency-key contract.
- How previously applied commands are recognized.
- How payload conflicts are detected.
- The order of checks in the current production repository.
- Existing idempotency and retry tests.
- Whether any proposed early return changes observable behaviour.

Provide a concrete decision table covering at least:

1. A new command with a representable date.
2. A new command with an unrepresentable date.
3. Reuse of a key with a different payload, where the new payload has an unrepresentable date.
4. A retry of an already-applied command.
5. A concurrency conflict.
6. An unauthorized or otherwise refused command, according to the current contract.

For each case, specify the expected result, the evidence supporting it, and whether the proposed Option E design changes current behaviour.

Recommend the correct ordering based on the existing contract. If requirements are ambiguous, state the ambiguity and request a Human PO/ARB decision rather than silently inventing semantics.

The date-range check must occur before any state mutation. It need not necessarily occur before every read: justify its placement against idempotency, concurrency, and the existing append contract.

## 4. Justify the persistence-contract boundary

Re-evaluate the proposed shared range definition under `Application/Organisational/Ports`.

Compare:

- A framework-free contract definition associated with the repository port.
- An existing shared persistence-contract abstraction, if one exists.
- An infrastructure-only definition.
- A domain-level restriction.

Use actual repository dependencies and architecture tests as evidence.

The design must:

- Preserve the general `TimePoint` abstraction and infinity semantics.
- Avoid adding PostgreSQL-specific concepts to the domain.
- Avoid unnecessary validation-service abstractions.
- Allow the production adapter and test double to honour the same documented persistence capability.
- Keep the conversion guard as a final defence.
- Avoid making test and production code share so much implementation logic that the contract tests become circular.

Select one option only after comparing the alternatives. Identify any architectural assumption that remains unverified until authorized tests run.

## 5. Specify outcomes and exhaustive mapping

Inspect the real `AppendResult`, `AppendOutcome`, `ApplicationRefusal`, and `CommandOutcome` types.

Propose the smallest consistent change that represents a non-storable valid-time value as a technical/application-level outcome, not a domain refusal.

Verify:

- Every outcome has an explicit application mapping.
- Only a concurrency conflict follows the retry path.
- Non-retryable outcomes do not retry.
- An unhandled future outcome cannot silently become `ConcurrentModification`.
- The existing retry limit and concurrency semantics remain intact.
- Unexpected infrastructure failures retain the existing, intentional failure semantics.

If using PHP `match`, explain how the implementation fails on an unmapped result and how tests will protect the complete mapping.

Do not choose names for new types or outcomes without checking project conventions.

## 6. Verify temporal boundaries

Inspect the current `DateOrdinal` constants and conversion implementation.

Verify the inclusive representable range and boundary conversion behaviour directly from the source. Treat the previous ordinal values as claims to verify, not unquestionable facts.

Specify tests for:

- The first supported finite date.
- The last supported finite date.
- One date below the supported range.
- One date above the supported range.
- Admission effective date.
- Finite initial term end.
- Membership end effective date.
- Infinity or other non-finite temporal values, where applicable.

Do not add the calendar range to `TimePoint` or impose a new domain calendar rule under Option E.

Keep these concepts distinct:

- Technical representability.
- Persistence-contract consistency.
- Domain calendar validity.
- Business plausibility.
- Backdating and scheduling policy.

## 7. Test-first acceptance plan

Produce a precise test matrix before any implementation.

It must cover:

**Repository contract**

- Unsupported values produce an explicit non-success result.
- Repository version and fact log remain unchanged.
- Supported boundaries remain storable and loadable.
- Idempotency behaviour remains correct.
- The test double and production adapter follow the documented contract.

**Application mapping**

- Every current append outcome is mapped.
- A non-storable result is not misclassified as a domain refusal or concurrency conflict.
- Only the intended concurrency outcome retries.
- Rejected outcomes do not trigger extra attempts.

**Regression**

- Domain tests, including temporal algebra and infinity.
- Application tests.
- Architecture-boundary tests.
- Existing concurrency and idempotency tests.

**PostgreSQL**

- Reserve adapter integration, rollback, and version-consistency tests for the separately authorized Part 2 procedure.
- State explicitly which properties cannot be claimed as verified without PostgreSQL evidence.

For each test, state the requirement, current expected result, intended result, dependencies, and evidence to capture. Require actual exit codes and test output.

Do not report any test as passing during this planning phase.

## 8. Keep policy and migration decisions separate

Option E must not silently approve a domain calendar or plausibility rule.

Keep B (domain calendar validity), C (business plausibility), D (B now and C later), and no additional business rule as separate Human PO/ARB choices.

Do not put these decisions under D-5(b) if that identifier concerns backdating or scheduling.

Keep the migration interpretation of legacy open-ended placeholders separate. A storable value such as `9999-12-31` may still be a placeholder; do not automatically convert it, correct it, annul it, or void it.

## 9. Required deliverable

Return:

1. Executive summary and recommendation.
2. Verified source references and current repository state.
3. Idempotency precedence analysis and decision table.
4. Persistence-boundary comparison and justified selection.
5. Proposed result vocabulary and exhaustive mapping.
6. Revised test-first sequence.
7. Acceptance criteria and evidence requirements.
8. PostgreSQL safety boundary.
9. Remaining risks, assumptions, and decisions required.
10. Human PO/ARB decision sheet with blank verdicts.
11. Exact proposed implementation scope and files.
12. A checklist for ChatGPT's independent review.

Keep the proposed change minimal. Do not expand into unrelated Membership slices or broad research work.

## 10. Stop condition

Stop after returning the revised plan.

Do not implement, edit, run tests, access a database, or commit.

Wait for ChatGPT's independent review, any required revisions, Human PO/ARB architecture approval, and explicit implementation authorization.

## 4. How far are we from the Membership implementation goal?

Based on the reports available, the current position is:

Membership domain rules core

Built and tested to a meaningful degree; includes Member, periods, facts, and core correction behaviour.

Repository and application foundations

Partially implemented. The storage contract, PostgreSQL verification, and formal acceptance gates still need work.

Operational Membership product

Substantial work remains: commands, queries, integrations, migration, user workflows, and release acceptance.

The latest report estimates 4 of 17 planned slices implemented in whole or in part. This is not equivalent to 24% completion: slices have different sizes, dependencies, and acceptance requirements. I would not publish an overall completion percentage until we define and measure a consistent rubric.

## 5. Remaining todos

### Immediate priority

- Get Claude to revise the Option E plan using the prompt above.
- Resolve the idempotency precedence question before finalizing the repository contract.
- Independently review the revised plan and confirm that the test strategy detects genuine defects.
- Obtain Human PO/ARB approval of the final architecture and explicit authorization before implementation.
- Keep the business calendar/plausibility policy separate from the technical storage decision.

### Technical implementation and verification

- Implement the approved Option E change test-first.
- Make append-outcome handling exhaustive and preserve retry semantics.
- Verify repository version, fact-log, and idempotency behaviour on rejection.
- Complete the authorized PostgreSQL safety and provisioning gates.
- Verify PostgreSQL adapter behaviour, rollback, and version consistency.
- Complete PBDIGIT-264 F-2 through F-5 and reconsider Slice 1b.
- Triage wider-suite failures with real exit codes, evidence, and dispositions.
- Resolve PBDIGIT-266 and review the status of optional PBDIGIT-267/269.

### Domain and application work

- Finalize the Slice 2 requirements decisions and implementation order.
- Complete Slices 2, 3, and 8 according to their dependencies.
- Implement and test the remaining correction semantics C-2, C-3, and C-4.
- Complete the application queries and operational commands.
- Complete the required integration work across Slices 9–12.
- Preserve the invariant that Membership does not itself grant voting entitlement.

### Migration and delivery

- Define the Slice 13 migration contract, including legacy open-ended placeholders.
- Generate a dry-run migration report and route ambiguous historical records to authorized human review.
- Test period overlap and re-entry invariants during migration.
- Implement Slice 14 operational UI and workflows.
- Complete Slice 15, regression, security review, acceptance evidence, and release readiness.
- Synchronize project-state documents through an authorized state-sync step.
- Establish a measured completion rubric based on requirements, tested invariants, integrated workflows, migration, and release gates.

### Research and theory

- Trace each important corpus finding to a candidate requirement, approved decision, invariant, test, and implementation.
- Use machine-learning clustering and contradiction detection as exploratory tools only where they materially improve the research.
- Verify all important research findings against original sources and human-approved decisions.
- Convert unresolved questions into explicit decisions instead of letting assumptions become code.

My recommendation: the next action is not to write code. It is to have Claude revise the Option E plan, particularly the idempotency semantics, then have ChatGPT review that revision. Once those issues are settled and the Human PO/ARB explicitly authorizes the increment, proceed with the small test-first implementation.