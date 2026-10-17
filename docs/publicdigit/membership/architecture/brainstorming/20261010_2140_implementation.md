# Membership: what to do now

I have reviewed Claude's latest report. The most important discovery is that F-3 is not an undecided domain-policy question in the existing corpus. The report finds an earlier approved clarification: the idempotency-key lookup must precede every other check, and a `MemberId` collision with another holder must produce an infrastructure failure without changing state. What remains unresolved is the explicit implementation authorization and the authority trail for the policy decision.&#x20;

Eingefügter Text.txt



Claude also verified both Option E correction commits, the 32 evidence checksums, and the absence of production-code changes in those commits. No defects were found in that review.&#x20;

Eingefügter Text.txt



## 1. My independent review: the next decision gate

The immediate issue is the difference between an approved policy and permission to implement it.

F-3 policy

The existing clarification requires key-first behaviour and no state change on a `MemberId` collision. Claude's plan correctly recommends making the in-memory test double conform to that policy.

Implementation sequence

The approved PBDIGIT-264 sequence is `F-1 → F-5 → F-3 → F-4 → F-2`. Claude proposes moving F-3 before F-5 because F-3 needs no database. That may be technically practical, but it is still a departure from the approved sequence and requires an explicit decision.&#x20;

Eingefügter Text.txt



Authority

The Principal Reviewer’s approval does not by itself establish that the Product Owner separately authorized implementation. Preserve that distinction and do not infer permission from the previous Option E authorization.

My recommendation: retain the approved sequence unless the Human PO/ARB explicitly changes it. The immediate next step is to resolve the authorization and ordering gate, not to let Claude start coding.

## 2. Prompt for Claude — next step

This prompt asks Claude to refine the plan only where necessary, preserve the approved sequence, and wait for the Human PO/ARB. It also strengthens the test criteria for the MemberId collision.

# PublicDigit Membership — F-3 Authorization Gate and Implementation Readiness

## Objective

Continue the Membership work as a senior DDD architect, requirements engineer, formal-logic analyst, researcher, mathematician/statistician, and software verification engineer.

The immediate task is to prepare F-3 for a controlled implementation decision. The previous Option E correction review is complete. Do not repeat it unnecessarily.

**This stage is plan and decision preparation only. Do not modify source, tests, contracts, project status, or evidence.**

## 1. Roles and authority

**Claude**

- Inspect the authoritative corpus and current repository.
- Produce an evidence-backed implementation plan.
- Identify the minimum necessary changes and tests.
- Implement only the explicitly authorized scope.

**ChatGPT**

- Independently review the plan, contracts, counterexamples, and test adequacy.
- Challenge contradictions and hidden assumptions.
- Review the implementation evidence after execution.

**Human PO/ARB**

- Owns final domain-policy decisions, implementation authorization, changes to the approved sequence, risk acceptance, and formal acceptance.
- Existing Principal Reviewer approval must not be treated as proof of separate Product Owner implementation authorization.
- Silence and previous authorization for another increment do not constitute permission.

## 2. Preserve the established F-3 policy

The existing corpus reports the following approved clarification:

1. Idempotency-key lookup precedes every other check.
2. A MemberId collision with another holder is an infrastructure failure with no state change.

Locate and cite the exact Slice 1a contract, ratification pack, and PBDIGIT-264 records. Establish which authority approved the clarification and whether the required Product Owner authorization for implementation is recorded.

Do not reopen the approved policy as though no decision exists. If the authority trail is incomplete, identify the missing evidence and request a decision from the Human PO/ARB.

## 3. Preserve the approved implementation sequence

The reported sequence is:

`F-1 → F-5 → F-3 → F-4 → F-2`

The previous plan proposes F-3 before F-5 because the F-3 change is limited to the test double and does not require a database.

Do not silently change the sequence.

Provide two clearly distinguished paths:

- **Path A:** retain the approved sequence and execute F-5 before F-3.
- **Path B:** move F-3 before F-5, but only after explicit Human PO/ARB approval of the sequence deviation.

For each path, explain dependencies, database requirements, verification constraints, schedule implications, and risks. State which path you recommend and why. Do not execute either path.

## 4. Review the proposed F-3 increment

The proposed increment includes:

- Shared contract tests for idempotency lookup before member-existence and valid-time checks.
- Matching key and fingerprint for an unknown MemberId returns the original `AlreadyApplied` result.
- A reused key with a different fingerprint returns `IdempotencyKeyConflict`.
- An unknown MemberId with an unused key and an unsupported valid time returns `ValidTimeNotStorable`, according to the approved ordering.
- A create operation using a MemberId already owned by another holder produces an infrastructure failure without changing either holder's state.

Review the exact proposed control flow:

`idempotency lookup → valid-time check → member/version check → writes`

Ensure the contract and implementation do not confuse the relative ordering of these checks with the separately specified member/version operation.

## 5. Strengthen the MemberId collision test

The current plan proposes expecting a generic Throwable. Review whether that is sufficiently precise.

Prefer a stable, explicitly documented infrastructure-failure exception or contract-level failure type if the architecture already defines one. Do not invent a new domain error if the collision represents an infrastructure/programming defect rather than a legitimate domain refusal.

The test must prove:

- the operation fails;
- the existing holder's Member is unchanged;
- the colliding MemberId is not remapped to the second holder;
- no additional or overwritten Member state remains;
- no unrelated tenant's state changes.

If the existing architecture does not define the required failure type, document the smallest proposal and obtain approval before introducing a new type.

## 6. Repository safety

Revalidate actual HEAD, staged and unstaged paths, and the existing correction commits.

The previous report identified three unrelated staged renames. Preserve them. Do not reset, clean, stash, amend, commit, or push anything during this stage.

No database commands, production changes, test changes, or status changes are permitted.

## 7. Required deliverables

Return:

1. Evidence for the approved F-3 policy and its authority trail.
2. Whether Product Owner implementation authorization is recorded or still required.
3. A comparison of Path A and Path B, with the recommendation clearly labelled.
4. A file-by-file implementation plan for the selected path, pending authorization.
5. Exact acceptance criteria for the shared contract tests and MemberId collision.
6. The precise exception/assertion strategy for the infrastructure failure.
7. Regression and evidence requirements.
8. Dependencies on PostgreSQL Part 2 and the remaining PBDIGIT-264 items.
9. The exact Human PO/ARB decisions still required.

## 8. Mandatory stop gate

Stop after delivering the plan.

Do not implement F-3, change the sequence, modify the approved policy, touch a database, create commits, or update completion status.

Wait for ChatGPT's independent review and the Human PO/ARB's explicit decisions and authorization.

## 3. Decisions required from the Human PO/ARB

The next decision package should be limited to these points:

- F3-1 — Authority: Confirm that the approved F-3 clarification is the governing policy and that the required Product Owner authorization is recorded or supplied.
- F3-2 — Sequence: Retain `F-1 → F-5 → F-3 → F-4 → F-2`, or explicitly authorize moving F-3 before F-5.
- F3-3 — Contract: Confirm that key-first behaviour is universal and that `ValidTimeNotStorable` precedence over `MemberNotFound` becomes universal once F-3 is implemented.
- F3-4 — Implementation: Authorize the narrowly scoped F-3 tests, test-double correction, documentation updates, and evidence. No production code, database, push, or formal acceptance should be included unless separately approved.

Claude has already laid out the corresponding alternatives and proposed implementation scope.&#x20;

Eingefügter Text.txt



## 4. How far are we from the Membership implementation goal?

The latest report supports the following status. This is a qualitative assessment, not an invented completion percentage.

| Area                                               | Status                                                                                                                   |
| -------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------ |
| Membership architecture and domain theory          | Ratified architecture; core model substantially implemented                                                              |
| Slice 0, Slice 1a, and C-1                         | Implemented/tested locally; formal acceptance outstanding                                                                |
| Option E                                           | Corrections committed and locally tested; not formally accepted                                                          |
| Slice 1b / PostgreSQL                              | Adapter implemented; required PostgreSQL verification outstanding                                                        |
| F-3                                                | Policy clarification approved in the reported corpus; implementation authorization and sequence decision need resolution |
| F-4 and F-5                                        | Decisions reported, implementation/verification gates remain                                                             |
| F-2, calendar rule, BD-NORM, and Slice 2 decisions | Open decisions                                                                                                           |
| Remaining features                                 | Slices 2, 3, 8–15 still require the work identified in the project backlog                                               |

The report explicitly records no formally accepted slices yet in this status view, and identifies the remaining open decisions and unstarted slices.&#x20;

Eingefügter Text.txt



## 5. Remaining todos

### P0 — Immediate governance and F-3

- Confirm the F-3 approval and Product Owner authorization trail.
- Have ChatGPT independently review the F-3 plan.
- Decide whether to retain the approved implementation sequence or explicitly authorize a deviation.
- Finalize the F-3 contract-test matrix and precise infrastructure-failure assertion.
- Implement F-3 only after explicit authorization.
- Run regression tests and preserve reproducible evidence.
- Review the results and obtain the appropriate disposition.

### P1 — Persistence and formal acceptance

- Obtain separate authorization and isolation controls for PostgreSQL Part 2.
- Verify Option E E1–E5 and E4-L against PostgreSQL.
- Verify F-3 cases against PostgreSQL as included in the approved Part 2 scope.
- Run state-preservation, rollback T7, invariant, and concurrency/race tests.
- Progress F-5, F-4, and F-2 according to the approved sequence and their respective gates.
- Complete the applicable formal verdicts for G3, Slice 0, Slice 1a, Option E, and Slice 1b.

### P2 — Remaining Membership implementation

- Finalize Slice 2 decisions D-1 to D-5.
- Implement Slices 2, 3, and 8.
- Complete correction cases C-2, C-3, and C-4.
- Complete integration Slices 9–12.
- Complete Slice 13 migration and normalization, including legacy date-placeholder classification, provenance, and dry-run review.
- Implement Slice 14 UI.
- Complete Slice 15 regression, security, and release acceptance.

### P3 — Research and architecture quality

- Maintain evidence → requirement → invariant → contract → test → decision traceability.
- Use formal logic, counterexamples, and boundary analysis to find untested cases.
- Use statistical methods for migration-data quality and anomaly analysis where appropriate.
- Use machine learning to suggest candidate contradictions or clusters only when useful; validate each against primary evidence.
- Keep technical storage limits separate from business calendar validity, historical plausibility, and backdating rules.
- Synchronize the project roadmap, decision register, evidence, and implementation status.

## Final recommendation

The next action is to finalize the F-3 authorization and sequencing plan, not to start coding immediately. Preserve the existing approved policy and implementation sequence unless the Human PO/ARB explicitly decides otherwise.

This keeps the work aligned with the agreed responsibilities: Claude investigates and implements, ChatGPT independently reviews, and the Human PO/ARB retains final decision authority.&#x20;