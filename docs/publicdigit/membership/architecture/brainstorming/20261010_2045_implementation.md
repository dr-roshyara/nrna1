# PublicDigit Membership — Principal Review of the Final Option E Plan

Claude has addressed the main issues from the previous review and produced a much more complete plan. It now identifies the existing idempotency requirement, proposes a deliberate rule for stale versions, records the remaining F-3 discrepancy, and specifies amendments to both persistence contracts.

My verdict: conditionally approve the technical direction, but do not authorize implementation yet. There is one important issue in the proposed test sequence, and the decision about whether to include the PostgreSQL change now needs a clear release strategy.

## 1. Independent review findings

| Topic                                        | Assessment                                                                                                |
| -------------------------------------------- | --------------------------------------------------------------------------------------------------------- |
| Idempotency lookup before other checks       | Correct according to the cited existing contract.                                                         |
| Date check before version mutation           | Sound, provided the new precedence rule is explicitly approved.                                           |
| New `ValidTimeNotStorable` result            | Appropriate in principle; must remain distinct from domain refusal.                                       |
| `StorableValidTime` near the repository port | Plausible; architecture tests must verify the dependency boundary.                                        |
| F-3 discrepancy                              | Correctly recorded as an outstanding limitation, not a solved problem.                                    |
| Amendments to Slice 1a and 1b                | Necessary before code changes can be treated as contract-compliant.                                       |
| PostgreSQL change                            | Technically plausible, but shipping it before PostgreSQL verification needs an explicit release decision. |
| Test sequence                                | Needs correction before implementation authorization.                                                     |

## 2. The most important remaining issue: test ordering

Claude's plan proposes introducing the new outcome and temporarily observing an `UnhandledMatchError` before adding the application mapping. This is useful as a demonstration of fail-fast behaviour, but it is not necessary to make an unmapped outcome the centrepiece of the test sequence.

I recommend a cleaner test-first approach:

1. Establish the baseline with the existing tests.
2. Add contract tests for unsupported dates, boundary dates, idempotency, and stale-version precedence.
3. Add application tests for the new refusal and complete outcome mapping.
4. Confirm that the new tests fail because the required behaviour is absent, not because the test suite itself crashes or references an undefined enum case.
5. Implement the minimal contract and mapping change.
6. Run the full authorized regression suites.

The exhaustive mapping test should compare the enum's actual cases against the expected mapping table. Its purpose is to prevent future omissions, not to depend on a transient exception during development.

The new outcome must also be added to the test double and production adapter consistently. PostgreSQL-specific behaviour will remain unverified until the separately authorized integration phase.

## 3. One decision needs explicit Human PO/ARB approval

The proposed rule R-1a-6 says that a new command with an unsupported valid time is refused before version comparison. Consequently, `ValidTimeNotStorable` takes precedence over `ConcurrencyConflict`.

I consider this a reasonable technical rule because retrying cannot make the same command date representable. However, it changes the observable result for stale-version commands.

The Human PO/ARB should explicitly decide:

- Whether this precedence is the intended repository contract.
- Whether the known F-3 limitation is acceptable for this increment.
- Whether the PostgreSQL adapter change may be included now, with its integration verification deferred to Part 2, or should wait until Part 2.
- Whether the Slice 1a and Slice 1b contract amendments are approved.
- Whether implementation of this specific increment is authorized.

The business calendar/plausibility question remains separate. Option E does not decide it.

## 4. Prompt for Claude — final readiness correction

# PublicDigit Membership — Option E Final Readiness Correction

## Roles

- **Claude:** investigator and planner now; implementer only after explicit authorization.
- **ChatGPT:** independent Principal Reviewer, challenging architecture, requirements, and evidence.
- **Human PO/ARB:** final authority for contract amendments, architecture approval, and implementation authorization.

## Current phase: plan-only revision

Use the latest Option E final plan as the baseline. Do not edit files, run tests or scripts, access databases, commit, or push.

## Required corrections

### 1. Correct the test-first sequence

Revise the test plan so that each stage has a clear purpose and expected result:

1. Baseline: identify the existing suites and their prior evidence.
2. Contract tests: demonstrate unsupported-date rejection, boundary acceptance, unchanged state, idempotency preservation, and the specified stale-version precedence.
3. Application tests: demonstrate the new application refusal and exhaustive mapping.
4. Implementation: add the minimal shared contract and adapter checks, then map the new result.
5. Regression: execute the authorized domain, application, temporal-algebra, concurrency, idempotency, and architecture suites.

For every test, state whether it should fail before implementation and why. Avoid relying on a transient `UnhandledMatchError` as a required milestone. Test exhaustive mapping directly by comparing actual enum cases against the complete expected mapping table.

Do not claim that any test has passed without execution evidence.

### 2. Preserve the contract decisions

Keep the existing idempotency-key lookup before other checks.

Present R-1a-6—unsupported valid time taking precedence over a stale version—as an explicit Human PO/ARB decision. Do not mark it approved.

Keep the missing-member ordering discrepancy as an explicit F-3 limitation if it remains outside this increment. Do not claim full adapter parity.

### 3. Decide the PostgreSQL delivery boundary

Provide a short comparison:

- Include the PostgreSQL code change in the same implementation increment, with adapter integration verification deferred to Part 2.
- Defer the PostgreSQL code change until Part 2 can verify it.

For both options, explain the risk of shipping unverified adapter code, the effect on the test-double contract, and the evidence needed before release.

Recommend one option, but leave the final decision to Human PO/ARB.

### 4. Final deliverable

Return:

- The corrected test sequence.
- The revised acceptance criteria.
- The precise contract amendments for Slice 1a and Slice 1b.
- The PostgreSQL delivery recommendation.
- A decision sheet with blank verdicts.
- The exact proposed implementation scope.
- A checklist for ChatGPT's final independent review.

Stop after producing the revised plan. Wait for ChatGPT review, Human PO/ARB architecture approval, and explicit implementation authorization.

## 5. How far are we from the Membership implementation goal?

The current project evidence supports this summary:

## Membership implementation status

Domain core

Implemented and tested to a meaningful degree.

Persistence and acceptance

Partially implemented; Option E remains unimplemented, formal verdicts remain open, and PostgreSQL evidence is incomplete.

Operational product

Commands, queries, integrations, migration, UI, and release readiness remain ahead.

The latest inventory estimates 4 of 17 planned slices implemented in whole or in part. This is an inventory count, not an overall completion percentage. The available report says no slices have yet been formally accepted, integrated, or put into production; revalidate that statement against the current gate register before publishing it as current status.

## 6. Remaining todos

### Immediate: architecture and approval

- Correct the Option E test-first sequence.
- Finalize the R-1a-6 stale-version precedence decision.
- Decide whether the PostgreSQL code change is included now or deferred to Part 2.
- Approve or revise the Slice 1a and Slice 1b contract amendments.
- Obtain ChatGPT's final independent review.
- Obtain explicit Human PO/ARB authorization before implementation.
- Record formal verdicts for G3, Slice 0, and Slice 1a when evidence supports them.

### Technical correctness

- Implement Option E test-first after authorization.
- Verify exhaustive outcome mapping and retry semantics.
- Preserve idempotency behaviour and repository state on rejection.
- Complete PostgreSQL safety/provisioning gates and Part 2 integration verification.
- Complete PBDIGIT-264 F-2 through F-5 and reassess Slice 1b.
- Triage wider-suite failures and PBDIGIT-266.
- Decide the disposition of optional PBDIGIT-267/269.

### Domain and application implementation

- Finalize Slice 2 decisions and implementation order.
- Complete Slices 2, 3, and 8.
- Implement correction semantics C-2, C-3, and C-4.
- Complete queries and operational commands.
- Complete the required integrations across Slices 9–12.
- Preserve the invariant that Membership does not itself grant voting entitlement.

### Migration and release

- Approve the Slice 13 migration contract.
- Investigate legacy open-ended placeholders and ambiguous historical values.
- Produce a dry-run migration report and require human review of ambiguous records.
- Test period overlap and re-entry invariants.
- Implement Slice 14 UI and operational workflows.
- Complete Slice 15, regression, security review, acceptance evidence, and release readiness.
- Synchronize project-state documentation through an authorized step.

### Research and theory

- Maintain traceability from corpus findings to requirements, decisions, invariants, tests, and implementation.
- Use ML-based clustering and contradiction detection only where they offer concrete research value.
- Validate research findings against source documents and approved decisions.
- Keep unresolved business questions explicit rather than silently encoding assumptions.

Recommended next action: ask Claude for the final readiness correction above. Once that plan is reviewed, settle the outstanding Human PO/ARB decisions and authorize the smallest implementation increment. This preserves the project's central principle: sound Membership theory and explicit requirements first, implementation second.