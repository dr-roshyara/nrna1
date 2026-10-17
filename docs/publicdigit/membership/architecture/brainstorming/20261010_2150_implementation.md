# Membership: my decision recommendation and next step

After reviewing Claude's latest report, I recommend Path B: implement F-3 before F-5, provided you explicitly approve the change to the established implementation sequence.

Claude has identified a material scheduling distinction: F-5 requires PostgreSQL verification infrastructure that is not yet provisioned or authorized, whereas F-3 is a test-double and shared-contract increment that does not require a database. The report identifies no technical dependency between the two increments.&#x20;

Eingefügter Text.txt



This is a reasonable, narrowly scoped sequence change—not permission to bypass governance.

## 1. My independent principal-review assessment

The F-3 policy is well specified

The proposed rule is that idempotency-key lookup happens first. A Member ID collision with another holder is an infrastructure failure with no state change. However, the Product Owner's approval of the policy and authorization of its implementation are not recorded in the reviewed authority trail.&#x20;

Eingefügter Text.txt



The test plan is appropriately specific

F3a verifies that a collision does not overwrite or remap an existing Member. F3b verifies idempotency replay and fingerprint conflict behaviour for an unknown Member ID. F3c verifies the result for an unsupported valid time when the key is unused. These are observable contract tests, not merely implementation-detail tests.&#x20;

Eingefügter Text.txt



The governance gate remains open

Claude has produced an implementation plan, not an authorization. The next step is for you, as Human PO/ARB, to decide the policy authority, sequence deviation, contract consequence, and implementation scope explicitly.

## 2. Recommended Human PO/ARB decisions

Here is my recommendation for each of Claude's four decision points.

| Decision                            | Recommendation                                                                                    |
| ----------------------------------- | ------------------------------------------------------------------------------------------------- |
| F3-1 — Adopt the F-3 policy         | Approve, after recording the Product Owner's explicit decision                                    |
| F3-2 — Work sequence                | Approve Path B: F-3 before F-5                                                                    |
| F3-3 — Contract precedence          | Approve universal `ValidTimeNotStorable` precedence over `MemberNotFound` once F-3 is implemented |
| F3-4 — Implementation authorization | Authorize only the stated F-3 tests, test-double changes, documentation, and evidence             |

These are my recommendations, not yet your recorded verdicts.

If you approve Path B, the sequence becomes:

F-3 — now

Shared contract tests, in-memory test-double correction, documentation and evidence

F-5 — after PostgreSQL Part 2 is authorized

Nested-call refusal and transaction/isolation verification

F-4 → F-2

Follow the approved dependencies and verification gates

This preserves the remaining work while allowing the independent, database-free increment to proceed sooner. PostgreSQL verification remains a separate authorization gate.&#x20;

Eingefügter Text.txt



## 3. Prompt for Claude for the next step

The next prompt should no longer ask Claude to investigate the same questions again. It should require Claude to check the recorded PO/ARB verdicts, implement only if the required decisions are present, and stop otherwise.

# PublicDigit Membership — F-3 Controlled Implementation

## Objective

Implement the approved F-3 increment in the Membership context, following the reviewed plan in the latest F-3 authorization-gate report.

Roles:

- **Claude:** repository executor and implementation agent.
- **ChatGPT:** independent reviewer of the plan, diff, tests, and evidence.
- **Human PO/ARB:** final authority for policy, sequence, scope, authorization, and acceptance.

## 1. Mandatory authorization check

Before changing any file, read the current F-3 gate register and verify explicit Human PO/ARB verdicts for all four decisions:

- F3-1: adopt the key-first policy and infrastructure-failure behaviour for a Member ID collision.
- F3-2: authorize Path B, moving F-3 before F-5.
- F3-3: approve universal `ValidTimeNotStorable` precedence over `MemberNotFound` after F-3.
- F3-4: authorize the narrowly scoped implementation.

Do not infer approval from recommendations, the Principal Reviewer's disposition, or previous Option E authorization.

If any required decision is missing, ambiguous, or inconsistent, make no changes. Report the missing decision and stop.

## 2. Revalidate repository safety

- Confirm actual HEAD, staged paths, unstaged paths, and the relevant commits.
- Preserve the three unrelated staged renames and all other existing user changes.
- Do not reset, clean, stash, amend, push, or include unrelated changes in commits.
- Use an isolated temporary Git index for authorized commits where necessary.
- Do not modify production code or touch a database in this increment.

## 3. Implement the approved F-3 contract

Use the reviewed control-flow order:

`idempotency-key lookup → valid-time representability check → member/version check → writes`

Ensure the relative ordering of the checks is explicit in the contract. Do not confuse the member/version step with the internal mechanics of the PostgreSQL compare-and-set.

### Required tests

**F3a — Member ID collision**

- Seed an existing Member with a fact and known version.
- Attempt to create a second holder using the same Member ID within the same tenant.
- Assert a `RuntimeException`-compatible infrastructure failure, using the project's existing exception conventions.
- Assert that the original holder, version, and fact log remain unchanged.
- Assert that the second holder has not been mapped.
- Assert that another tenant's corresponding state is unchanged.

**F3b-1 — Matching idempotency key and fingerprint**

- Use a known key and matching fingerprint with an unknown Member ID.
- Assert `AlreadyApplied` and the original result.
- Assert that no new fact is written.

**F3b-2 — Different fingerprint**

- Reuse the key with a different fingerprint and an unknown Member ID.
- Assert `IdempotencyKeyConflict`.
- Assert that state remains unchanged.

**F3c — Unused key and unsupported valid time**

- Use an unknown Member ID, an unused key, and an unsupported valid-time ordinal.
- Assert `ValidTimeNotStorable`, in accordance with the approved check ordering.

Use tests that assert observable outcomes and state preservation. Do not weaken idempotency verification or introduce production bypasses.

## 4. Required implementation workflow

1. Capture the baseline Application suite and E10 results.
2. Add F3a, F3b-1, F3b-2, and F3c tests first.
3. Demonstrate the expected failing tests against the unfixed double.
4. Make the smallest test-double correction necessary to conform to the approved contract.
5. Update the Slice 1a and Slice 1b contracts, developer guide, index, gate register, and status records only within the authorized scope.
6. Run the Application, Domain, DateOrdinal, architecture, and relevant property tests.
7. Capture commands, exit codes, outputs, environment information, and SHA-256 evidence.
8. Inspect the final diff and commit contents. Verify that unrelated staged renames remain untouched.

Do not mark Option E, Slice 1b, or the Membership architecture formally accepted.

## 5. PostgreSQL boundary

Do not provision, connect to, migrate, seed, or modify a database.

The F-3 PostgreSQL cases remain in the separately authorized Part 2 scope. Clearly distinguish predictions from verified results.

Do not mark F-5 complete or reorder any further increment without the relevant Human PO/ARB decision.

## 6. Stop gate and final report

If a new architecture decision is needed, stop and present the decision for approval.

After implementation, report:

- exact changes and commits;
- tests and evidence;
- failures, deviations, and unresolved risks;
- confirmation of repository safety;
- the remaining PostgreSQL and acceptance gates.

Stop for ChatGPT's independent review. Do not declare formal acceptance.

If the required verdicts are not recorded, stop before implementation and return the authorization gaps.

## 4. How far are we from the Membership implementation goal?

The latest report indicates that the core domain and early persistence increments are substantially implemented, but formal acceptance and later functionality remain outstanding. No overall completion percentage is justified by the evidence.

| Workstream                    | Current status                                                                |
| ----------------------------- | ----------------------------------------------------------------------------- |
| Domain model and architecture | Ratified; implementation foundation exists                                    |
| Slice 0, Slice 1a and C-1     | Implemented/tested locally; formal verdicts outstanding                       |
| Option E                      | Corrections committed; local regression reported green; not formally accepted |
| Slice 1b / PostgreSQL         | Adapter implemented; relevant PostgreSQL verification outstanding             |
| F-3                           | Plan ready; explicit PO/ARB verdicts and implementation authorization pending |
| F-4, F-5 and F-2              | Remaining work and verification gates                                         |
| Later Membership slices       | Substantial implementation remains                                            |

The status distinction is important: passing local tests is evidence of behaviour under those tests, not proof of PostgreSQL behaviour or formal acceptance.&#x20;

Eingefügter Text.txt



## 5. Remaining todos

### P0 — F-3 and governance

- Record explicit Human PO/ARB decisions for F3-1 through F3-4.
- If Path B is approved, implement F-3 before F-5.
- Verify idempotency ordering, Member ID collision safety, and tenant isolation.
- Run regression tests and preserve evidence.
- Obtain ChatGPT's independent review of the completed F-3 increment.

### P1 — PostgreSQL and formal acceptance

- Obtain separate authorization and provisioning for PostgreSQL Part 2.
- Verify Option E E1–E5 and E4-L against PostgreSQL.
- Verify F3a–c against PostgreSQL.
- Run state-preservation, rollback T7, invariant, and race/concurrency suites.
- Implement and verify F-5, then F-4 and F-2, following the authorized sequence.
- Complete the formal verdicts for G3, Slice 0, Slice 1a, Option E, and Slice 1b.

### P2 — Remaining Membership capabilities

- Finalize Slice 2 decisions D-1 to D-5.
- Implement Slices 2, 3, and 8.
- Complete C-2, C-3, and C-4.
- Complete integration Slices 9–12.
- Complete Slice 13 migration and normalization, including legacy placeholder review.
- Implement Slice 14 UI.
- Complete Slice 15 regression, security, and release acceptance.

### P3 — Research and theory quality

- Maintain evidence-to-requirement-to-invariant-to-contract-to-test traceability.
- Use formal logic and counterexamples to expose missing cases.
- Use statistical analysis for migration anomalies and data-quality assessment.
- Use machine learning to suggest candidate inconsistencies only when useful, and validate each against primary evidence.
- Keep technical date representability separate from business calendar validity and historical interpretation.
- Synchronize the project state, decision register, evidence index, and implementation backlog.

Bottom line: the next action is to finalize the four F-3 decisions. My recommendation is to approve Path B because it unblocks an independent, database-free increment while PostgreSQL work remains gated. Once you explicitly authorize it, Claude should implement the narrowly scoped change, and ChatGPT should independently review the evidence before the next milestone proceeds.