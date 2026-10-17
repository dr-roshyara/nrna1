# Principal review: Adjudication programme status — 9 October 2026

My assessment is that the substantial DDD discovery has already been completed and frozen. We should not restart discovery or redesign Adjudication. We should now close the gap between the accepted architecture and a production-operational election challenge process.&#x20;

Eingefügter Text.txt



## 1. What the attached report tells us

DDD discovery: complete and frozen

The accepted design uses `Challenge` in Contestation, `Determination` in Adjudication, an Adjudication Process Manager that records rather than computes the authority's ruling, and Governance-owned authority delegation. The correction loop is forward-only.&#x20;

Eingefügter Text.txt



Critical infrastructure gap: tenant context

The scheduled outbox/inbox relay appears not to establish the organisation context required by its consumers. This was confirmed by static code inspection, but has not yet been reproduced at runtime. It may prevent the production process from running outside an HTTP request.&#x20;

Eingefügter Text.txt



Two additional defects need ownership

`ChallengeMapper::toRow` appears to omit the challenge's filing data. Election rejection exceptions also appear to lack the permanent-failure classification expected by the inbox processing contract. Both are static findings, not runtime-reproduced defects.&#x20;

Eingefügter Text.txt



The end-to-end process is not production-ready

Challenge domain logic, messaging components and portions of the process manager exist. However, production challenge filing, ruling intake, determination finality, scheduled horizon/lapse handling and complete end-to-end verification remain incomplete.&#x20;

Eingefügter Text.txt



## 2. The correct next step

I recommend a focused remediation and decision-preparation mission—not another research round and not immediate implementation of PBDIGIT-178 or PBDIGIT-177.

The first priority is to:

1. Prepare the factual correction to PBDIGIT-211 so it explicitly covers the relay's missing tenant context.
2. Prepare the Architecture Review Board (ARB) decision on how organisation context must be established for scheduled jobs and message consumers.
3. Plan an isolated, serialised regression test to reproduce the defect without interfering with concurrent work.
4. Propose appropriate ownership for the other two findings without creating duplicate stories or changing backlog status without approval.
5. Preserve the existing binding implementation sequence: 178 → 177 → 174 → 182. Do not connect a production caller to the issuance path before the transaction-boundary repair is authorised and completed.&#x20;

   Eingefügter Text.txt

   Eingefügter Text.txt



One further architectural issue must remain visible: the bounded-context baseline lineage and Appointment/Governance ownership need an ARB ruling, but the report's analysis indicates that this ambiguity does not itself block the correction loop. It should not distract us from the immediate tenant-context issue.&#x20;

Eingefügter Text.txt



## 3. Next Claude Code prompt

# Mission 2 — Adjudication Remediation Gate and Decision Preparation

## Role

Act as the Principal DDD Architect, requirements engineer, formal-methods researcher and principal software reviewer for PublicDigit, our digitalisation platform for organisations and political parties, especially online voting.

## Starting point

Read the completed status report:

`docs/publicdigit/reviews/2026-10-09-adjudication-programme-status-recovery.md`

Also inspect its referenced architecture decisions, backlog items, code and test infrastructure.

The previous mission recovered the discovery history and stopped without implementation. Continue from that evidence. Do not restart strategic or tactical DDD discovery.

## Objective

Prepare the immediate next step toward a production-operational challenge-to-adjudication correction loop.

The primary concern is T-1: the scheduled outbox/inbox relay appears not to establish the organisation context required by downstream consumers.

This is a static finding. Runtime reproduction is still required.

## Step 1 — Re-establish the current state

- Inspect Git status, HEAD, concurrent changes and current project instructions.
- Re-read PBDIGIT-211, PBDIGIT-263 and the relevant developer guides.
- Verify whether T-1, T-2 or T-3 have already been addressed by subsequent changes.
- Confirm the test database isolation strategy before executing tests.
- Do not assume the 9 October report still reflects the current code.

## Step 2 — Prepare the PBDIGIT-211 correction

Prepare a minimal, additive proposed scope clarification for PBDIGIT-211.

The proposal must cover:

- Organisation-context establishment for scheduled, non-HTTP execution.
- The outbox/inbox relay and its downstream consumers.
- The relationship between message organisation identifiers and the authoritative tenant context.
- Missing-context, unknown-organisation and invalid-context failure behaviour.
- Prevention of cross-tenant processing.
- Compatibility with existing Membership jobs and other non-request execution.
- Isolation, serialisation and test requirements.

Do not prescribe ambient global state, per-message context switching or another mechanism before checking the accepted architecture and ADRs.

Present the problem, evidence, acceptance criteria and architectural options. Mark any new rule requiring ARB approval explicitly.

Do not silently rewrite the approved story or change its status.

## Step 3 — Prepare a runtime reproduction plan

Design the smallest deterministic regression test that demonstrates whether a relay consumer fails when invoked without HTTP middleware or test-setup tenant context.

The test must:

- Use a dedicated or safely serialised test database.
- Avoid destructive operations against a database shared with another session.
- Demonstrate the missing-context failure before the fix.
- Verify correct organisation selection after the authorised fix.
- Verify that an event for one organisation cannot be processed under another organisation.
- Verify cleanup and context reset after success and failure.
- Exercise the actual relay/consumer path rather than mocking away the suspected defect.

Inspect PBDIGIT-263 and resolve the testing prerequisite before running the test. Do not claim runtime confirmation unless the test actually reproduces the defect.

## Step 4 — Classify T-2 and T-3

For each finding, re-inspect the current code and determine whether the defect remains:

- T-2: challenge filing data appears to be dropped by `ChallengeMapper::toRow`.
- T-3: Election rejection exceptions appear to be treated as retryable rather than permanent inbox failures.

Recommend an existing story or an appropriate backlog destination for each. Avoid duplicate stories and overlapping ownership. Do not create new IDs or alter backlog records without approval.

## Step 5 — Preserve the binding architecture and delivery order

Do not bypass the accepted sequence:

**PBDIGIT-178 → PBDIGIT-177 → PBDIGIT-174 → PBDIGIT-182**

In particular:

- No production caller may reach the determination-issuance path ahead of the authorised transaction-boundary repair.
- Authority must remain owned by Governance and consumed through the approved contract.
- The Adjudication Process Manager records the authority's decision; it must not compute the ruling.
- Preserve the Challenge and Determination models and existing context boundaries.
- Do not start committee implementation, challenge UI, finality triggers or horizon processing as part of this mission.

Prepare the ARB decision request for the tenant model and identify the exact prerequisites that must be resolved before implementation proceeds.

## Step 6 — Deliver and stop

Create a dated remediation-gate report under the existing PublicDigit review-document convention. Preserve previous reports.

Include:

1. Current repository and story status.
2. Verified evidence for T-1, T-2 and T-3.
3. The proposed PBDIGIT-211 scope clarification.
4. Runtime test design and actual results, if authorised and safely executable.
5. ARB decisions required.
6. Proposed ownership for the additional defects.
7. Dependencies and the preserved implementation sequence.
8. Acceptance criteria, risks and rollback considerations.
9. One recommended next action.

Do not change production code, database schemas, ADRs or backlog status in this mission. Do not run destructive migrations, interfere with concurrent work or claim tests passed without execution evidence.

Stop after delivering the decision package. Wait for explicit authorisation before implementation.

## 4. How far are we from the implementation goal?

The report establishes that the foundational DDD design is substantially established, but the operational process is incomplete. A reliable percentage for the entire platform would be misleading because the evidence covers multiple independent capabilities and the complete production flow has not been verified.

## Current status

DDD discovery and tactical design

Frozen

Core Challenge / Determination models

Implemented

Production correction loop

Incomplete

Runtime confirmation of T-1

Pending

Governance authority integration

Blocked

End-to-end production verification

Not achieved

## 5. Remaining TODOs

- Reconcile the report with the current repository and concurrent changes.
- Prepare the additive PBDIGIT-211 correction for T-1.
- Obtain the ARB decision on tenant context for non-HTTP execution.
- Establish safe, isolated test execution under PBDIGIT-263.
- Reproduce T-1 at runtime and verify the eventual correction.
- Assign and resolve T-2 challenge-data persistence.
- Assign and resolve T-3 permanent inbox-failure classification.
- Obtain authorisation and repair the issuance transaction boundary (PBDIGIT-178).
- Complete authority-decision intake (PBDIGIT-177), provenance work (PBDIGIT-174) and end-to-end verification (PBDIGIT-182) in the established order.
- Resolve Governance authority persistence and contract questions.
- Implement determination finality and horizon/lapse processing.
- Complete challenge filing, admissibility and terminal-outcome handling.
- Resolve the outstanding bounded-context baseline and governance decisions.
- Update the project-state records and relevant developer guides when authorised implementation changes are made.

Principal recommendation: Give Claude the Mission 2 prompt. Our immediate objective is to establish a safe, evidence-backed remediation gate for the tenant-context defect, then resume the already-approved Adjudication delivery sequence without reopening the frozen DDD design.&#x20;