# Membership implementation — Principal Reviewer assessment

I have reviewed the latest attached Claude transcript, including the finalisation of Revision 3 of the PostgreSQL verification and far-date plan.

My recommendation: the next step is to make the Part 1 approval decision, not to start another broad research round or begin PostgreSQL testing.

Claude has completed the plan-level review described in the transcript. The plan remains explicitly PROPOSED — NOT APPROVED FOR EXECUTION. No tests, database operations or application-code changes were reported during that plan-finalisation step.&#x20;

Eingefügter Text.txt



## 1. What has changed, and what remains unresolved?

Revision 3 is more precise

It specifies the isolated database role's required permissions, distinguishes inspected facts from assumptions, and expands the provisioning evidence required before PostgreSQL testing.

Part 1 is independent of PostgreSQL

The proposed far-date correction uses boundary tests and a numeric range guard in `DateOrdinal::toDate()`. It can be authorised separately without first provisioning a database.

The database isolation gate is not yet satisfied

The transcript acknowledges that the dedicated role can still connect to `nrna_test` unless the stronger `CONNECT` restriction is applied. Non-ownership reduces destructive risk, but it does not prevent access to data visible to that role.&#x20;

Eingefügter Text.txt



Formal acceptance is still pending

Claude's recommendation that Part 1 is ready is not authorisation to execute it. G3 and the Slice 0/Slice 1a dispositions also remain pending; Slice 1b is deferred.

### My additional technical condition

For Part 2, I recommend that the test role must be unable to connect to `nrna_test`, not merely unable to drop its tables. Infrastructure must choose a safe way to enforce that without unexpectedly disrupting other users. If the required isolation cannot be demonstrated, do not run the PostgreSQL suite.

For Part 1, I have no plan-level objection to the proposed bounded approach based on the evidence in the transcript. The actual implementation and test results still need review.

## 2. Prompt for Claude — next step

The plan exists already. Claude should not create another plan or reopen the architecture. The next action is to wait for explicit Human PO/ARB approval of Part 1 and then execute only that approved increment.

# PublicDigit Membership — Part 1 Execution Gate

## Roles

- **Claude:** Repository investigator and implementation engineer.
- **ChatGPT:** Independent Principal DDD Architect, requirements reviewer and technical critic.
- **Human PO / ARB:** Sole authority for execution approval, risk acceptance and formal architecture or acceptance decisions.

## Current baseline

Use the existing plan:

`docs/plans/20261010-1830-membership-pg-verification-and-far-date-plan.md`

Baseline HEAD: `ce8bf3109db3acfcc7551c6baa2f609078b2f659`.

The plan is finalised at Revision 3 but remains PROPOSED — NOT APPROVED FOR EXECUTION.

## Step 1 — Before execution

Do not change code or run tests until explicit Human PO / ARB approval for Part 1 has been recorded.

If approval has not been recorded, report that the next required action is the approval decision and stop.

## Step 2 — Execute Part 1 only, after approval

Implement only PBDIGIT-264 F-1 as specified in the approved plan:

1. Add boundary tests for the supported ordinal range.
2. Demonstrate RED against the unmodified implementation.
3. Add the minimal numeric range guard to `DateOrdinal::toDate()`.
4. Demonstrate GREEN.
5. Run only the specifically authorised Domain, Application and Architecture regression groups, together with the new boundary tests.
6. Capture the complete output, exit codes, environment and version evidence, and SHA-256 hashes.

Keep the correction in the approved infrastructure mapping. Do not introduce a new domain-level `effectiveAt` rule or change `fromDate()` unless a separate decision authorises it.

## Strict scope boundaries

- Do not run PostgreSQL tests or connect to any database.
- Do not change the database schema, `phpunit.xml`, or `tests/bootstrap-test-database.php`.
- Do not implement PBDIGIT-264 F-2 to F-5, C-2 to C-4, or other Membership slices.
- Do not modify the review/disposition document or record formal acceptance.
- Do not commit or push unless separately authorised.
- If a test exposes an unrelated failure, record it and stop that issue from expanding the scope.

## Step 3 — Report for independent review

Report:

- Exact files changed and why.
- The RED → GREEN evidence.
- Results and exit codes for each authorised regression group.
- Any deviations, unresolved risks or unrelated failures.
- Whether the implementation meets the approved Part 1 acceptance criteria.

Stop after reporting. Wait for ChatGPT's independent review and further Human PO / ARB direction.

## Later work — not authorised by this instruction

Part 2 requires the isolated PostgreSQL database, independently reviewed provisioning evidence, enforced isolation from `nrna_test`, the infrastructure decision on I-1 and separate Human PO / ARB approval.

Part 3 requires separate permission for each named database and verified read-only conditions.

Neither Part 2 nor Part 3 is authorised by approval of Part 1.

## 3. How far are we from the Membership goal?

I would not report a precise completion percentage as a fact. We have architectural work, implementation work, acceptance work and production-readiness work, and they do not advance at the same rate.

Claude's latest transcript estimates approximately 20–25% built against a 17-slice plan, with zero slices accepted, integrated or used in production. That is a rough planning estimate, not a measured completion metric.&#x20;

Eingefügter Text.txt



My qualitative assessment is that the core Membership theory and implementation foundation are substantially established, but the overall implementation and acceptance programme is still in progress.

## 4. Remaining todos

### Immediate — approval and bounded implementation

- Record explicit Human PO / ARB approval for Part 1.
- Implement the far-date boundary tests using TDD: RED → GREEN.
- Run the authorised targeted regression groups and preserve reproducible evidence.
- Obtain ChatGPT's independent review of the implementation and test results.

### PostgreSQL and persistence

- Provision the isolated PostgreSQL database and dedicated test role.
- Verify the role can create, migrate and clean up its own test schema.
- Enforce and independently verify that the role cannot connect to `nrna_test`.
- Review the provisioning evidence before authorising Part 2.
- Run the approved PostgreSQL verification only after all isolation and approval gates pass.
- Run the separately authorised containment check and record `CONTAINED`, `NOT CONTAINED` or `NOT VERIFIED`.

### Membership acceptance

- Investigate and disposition the reported wider Membership suite failures.
- Resolve the remaining PBDIGIT-264 items in their own approved increments.
- Complete applicable persistence and security acceptance conditions.
- Record formal G3, Slice 0 and Slice 1a decisions; do not infer approval from technical recommendations.
- Reassess Slice 1b against its explicit re-entry criteria.
- Continue with subsequent Membership slices only after their requirements, architecture and execution plans have been reviewed and authorised.

### Research and theory

- Keep the established Membership invariants as explicit, testable specifications.
- Use property-based testing, boundary-value analysis and independent differential checks to strengthen confidence in the implementation.
- Use mathematical models to reason about membership-period overlap, valid-time boundaries, corrections and reconstruction of historical truth.
- Use machine learning, where useful, to cluster the existing research corpus, identify contradictory statements and locate unaddressed requirements. Treat its findings as hypotheses requiring deterministic verification, not as domain decisions.
- Avoid reopening frozen architectural decisions without new evidence that justifies doing so.

The decision now: approve Part 1, or return it for a specific correction. Once that decision is recorded, Claude can execute the bounded increment under the existing plan.