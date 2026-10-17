# PublicDigit — Principal DDD architecture review and next mission

My conclusion is that we have moved from architecture discovery into evidence-based remediation and governance decision closure. The latest work is valuable because it has exposed failures that were hidden by the existing test setup. However, the online voting platform's adjudication and correction loop is not yet demonstrated to work end to end.

The next step should not be another broad discovery round or an immediate production fix. It should be a tightly controlled Mission 3: Decision Readiness and Evidence Integrity, preparing the repository for explicit ARB and Product Owner decisions while preserving all existing architecture constraints.

## 1. What the attached session log establishes

The latest session has done four useful things:

- Recorded the runtime evidence for T-1 and T-4, including a cross-tenant write observed in the isolated test environment.
- Added the T-2 requirement to the PBDIGIT-181 specification: challenge filing facts must be durably persisted when raised.
- Prepared T-3 and T-5 as backlog drafts without inventing story IDs.
- Updated the ARB decision package and PublicDigit project context, while leaving the ARB's questions unresolved.&#x20;

  Eingefügter Text.txt

  Eingefügter Text.txt



This is appropriate governance discipline: findings are documented, proposals are distinguished from decisions, and production implementation remains blocked.

### Principal review: the issues that matter most

1\. Tenant isolation is the highest-priority architectural risk.

T-1 confirms that message processing can fail because the relay does not establish the message's tenant. T-4 shows the more serious failure mode: an event for organisation A was processed under organisation B and marked completed. The immediate scheduler path reportedly does not expose this condition, but a future in-process relay or an incorrect fix could. The accepted invariant is that messaging must never cross tenant boundaries.

2\. T-5 undermines operational evidence.

The relay attempts to write failure records to `outbox_processing_errors`, but the table has no migration. The failure record can therefore be lost, and an attempted insert inside a transaction can have additional consequences. This needs separate ownership and schema-change authorization; it should not be bundled invisibly into the tenant fix.

3\. The decision package needs one final consistency pass.

The ARB package's earlier §6 still describes the relay tests as pending the dedicated database, while the later execution record documents that they were run. This stale statement should be corrected before the package is circulated. The final status must distinguish the completed isolated test run from the still-pending ARB ruling and production remediation.

4\. Separate the outstanding PBDIGIT-256 change.

The log identifies a PBDIGIT-256 test-factory change among the remaining uncommitted work. It must be reviewed and authorized separately from the adjudication evidence and tenant-context changes. The developer-guide warning also needs a factual disposition based on the actual diff, not merely an assumption that the warning is harmless.

One important distinction: the authoritative relay run is 5 tests, 3 failures, 1 pass and 1 incomplete. That is a useful characterization result, not a passing regression suite. The incomplete test remains subject to the ARB's Q4 decision.&#x20;

Eingefügter Text.txt



## 2. Recommended next step

Mission 3 — Decision Readiness and Evidence Integrity

The goal is to make the current evidence and pending decisions authoritative, consistent, and ready for action—not to implement the fixes.

There are two different decision authorities:

- ARB: decides the tenant-context model and operational semantics in Q1–Q7. Claude must not turn proposed options into accepted architecture.
- Product Owner: approves backlog ownership for T-3 and T-5, confirms the treatment of T-2, and authorizes the relevant commits or implementation work.

The binding sequence `PBDIGIT-178 → PBDIGIT-177 → PBDIGIT-174 → PBDIGIT-182` remains unchanged. No issuance-path caller may be introduced before PBDIGIT-178 is complete.&#x20;

Eingefügter Text.txt



## 3. Claude Code prompt for the next mission

Use this as the next focused instruction.

# Mission 3 — Decision Readiness and Evidence Integrity

## Role

Act as a principal DDD architect, senior requirements engineer, software assurance reviewer, and evidence-driven researcher for PublicDigit, a digitalization platform for organisations and political parties, with online voting as a core use case.

Your task is to prepare the adjudication correction-loop work for the next authorized decision. Do not reopen accepted DDD discovery, invent architectural decisions, or begin production implementation.

## 1. Re-establish the actual repository state

Before changing any files:

1. Inspect the current branch, HEAD, working tree, and complete diff.
2. Identify all uncommitted files and classify them as:
   - adjudication remediation evidence;
   - tenant-context test and PBDIGIT-211 clarification;
   - PBDIGIT-181 / T-2 annotation;
   - T-3 and T-5 draft backlog descriptions;
   - PBDIGIT-256 changes;
   - unrelated or concurrent-session work.
3. Do not reset, stash, overwrite, revert, or discard another session's changes.
4. Treat the actual repository state as authoritative. Previous session summaries are evidence to verify, not substitutes for inspection.

## 2. Reconcile the evidence package

Review these records and their actual repository locations:

- `2026-10-09-adjudication-programme-status-recovery.md`
- `2026-10-09-adjudication-remediation-gate.md`
- `2026-10-09-arb-decision-request-tenant-context-non-request-execution.md`
- the PBDIGIT-211 specification and addendum;
- the PBDIGIT-181 completeness-audit annotation;
- `.claude/CONTEXT-publicdigit.md`;
- `.claude/sessions/2026-10-09.md`;
- `tests/Feature/Contexts/Shared/Messaging/RelayTenantContextTest.php`.

Correct factual inconsistencies only when supported by the repository.

In particular:

- Reconcile the earlier ARB package wording that says the tests are awaiting a dedicated database with the later execution record.
- Record that the authoritative run used `nrna_test_adjudication` and produced 5 tests: 3 failures, 1 pass, 1 incomplete.
- Keep the incomplete test explicitly dependent on the Q4 decision.
- Keep T-1 and T-4 marked runtime-confirmed, with their observed scope and limitations stated precisely.
- Keep T-5 separate from the tenant-context defect and identify the missing migration as a schema-related finding.
- Distinguish a test created, a test executed, a result observed, and a production defect fixed. Do not conflate these states.
- Ensure every proposed ARB answer remains marked as a proposal until a real ARB ruling is recorded.
- Check that PBDIGIT-181 explicitly requires durable persistence of challenge filing facts at raise time, regardless of the separate decision about event publication.
- Keep T-3 and T-5 as drafts without invented IDs unless the PO authorizes backlog creation.

Do not change the meaning of accepted ADRs or alter frozen architecture records to make the documents appear consistent. If an actual contradiction remains, record it as unresolved.

## 3. Prepare the decision gate

Produce a concise decision table covering:

### ARB decisions

- Q1: authoritative tenant establishment for message execution.
- Q2: acceptability and safety requirements of ambient tenant state.
- Q3: behaviour when ambient and message tenants differ, including nested scopes.
- Q4: treatment of soft-deleted organisations, parking deadlines, terminal states, retention, and replay.
- Q5: missing-tenant behaviour and the scope of the existing platform fallback, including HTTP request paths.
- Q6: ownership of the scoped-execution capability.
- Q7: consistency between scheduled jobs, relay, redrive, and inbox handlers.

For each question, show:

- the proposed direction;
- supporting evidence;
- consequences and unresolved trade-offs;
- the exact decision still required from the ARB.

Do not issue a ruling on behalf of the ARB.

### Product Owner decisions

Present separate approval rows for:

1. PBDIGIT-211 scope clarification and the tenant-context remediation gate.
2. T-3 backlog ownership and its draft acceptance criteria.
3. T-5 backlog ownership, priority, operational evidence contract, and separate schema-change authorization.
4. The isolated RT test and associated review documents.
5. PBDIGIT-256, separately from the adjudication work.

Show what is already applied, what exists only as a draft, and what still requires authorization. Do not assume that approval of one row approves the others.

## 4. Preserve architecture and safety constraints

The following are binding:

- DDD discovery for Adjudication remains frozen; do not restart it.
- `Challenge` belongs to Contestation; `Determination` belongs to Adjudication.
- The Adjudication Process Manager records authoritative rulings; it does not calculate or invent them.
- Governance owns constitutional delegation and authority.
- Messaging preserves tenant isolation and must never process an event under a foreign organisation.
- Election-domain identities remain opaque and tenant-free in accordance with ADR-T16.
- The implementation order remains **PBDIGIT-178 → PBDIGIT-177 → PBDIGIT-174 → PBDIGIT-182**.
- No production caller may invoke the issuance path before PBDIGIT-178 is complete.
- No production-code or schema changes are authorized by this mission.

Do not run the relay tests again unless separately authorized. The isolated database and previous results must be preserved.

## 5. Reconcile the Definition-of-Done warnings

Inspect the actual diff that triggered the database developer-guide warning.

Determine whether the PBDIGIT-256 test-factory change is the only change in the affected area, whether it alters production behaviour, and whether a database developer-guide update is genuinely required under the repository's Definition of Done.

Do not modify the database guide merely to silence the hook. If the guide is required, report the precise missing content and await authorization before expanding scope.

Reconcile the `CONTEXT.md` warning as well. Preserve the existing project-specific context and unrelated session updates.

## 6. Required output

Update only the existing review records and project context where a factual correction is necessary. Create no additional documents unless an existing record cannot reasonably hold the decision table.

Finish with:

1. Repository state and exact diff classification.
2. Evidence consistency results.
3. ARB decision table.
4. PO approval table.
5. Definition-of-Done warning disposition.
6. Remaining blockers and the next authorized action.
7. Implementation progress and a prioritized TODO list.

For every changed file, state why it changed. Do not commit anything.

## Stop condition

Stop when the evidence package is internally consistent and the decision gate is ready for human review.

If the ARB ruling is absent, do not choose an option on its behalf. If the PO has not authorized backlog creation or commits, do not perform those actions. Do not begin production implementation, alter the schema, change ticket status, or reorder the binding implementation sequence.

The purpose of this mission is to establish exactly what is known, what remains undecided, who has authority to decide it, and what work can safely follow.

## 4. How far are we from the implementation goal?

The goal is not merely to make individual tests green. It is to deliver a trustworthy, auditable, organisation-specific election platform whose governance rules, challenge process, authority decisions, and correction outcomes are enforced in production.

The current evidence supports this assessment:

## Adjudication correction loop — maturity assessment

DDD discovery and conceptual architecture

Largely established

The main aggregates, context responsibilities, authority ownership, and forward-only correction-loop approach are established. Remaining governance questions should be resolved without restarting discovery.

Integration and tenant isolation

Blocked

Runtime evidence confirms missing tenant propagation and a cross-tenant write under a pre-existing tenant context. The relay failure-recording path also lacks its table migration.

End-to-end correction loop

Incomplete

Filing, authority intake, determination finality, lapse/horizon handling, and terminal failure outcomes remain incomplete or blocked. The real correction path has not been validated end to end.

Production readiness

Not demonstrated

The latest tests are diagnostic evidence, not a passing regression suite. The ARB ruling, PO approvals, implementation, and end-to-end verification remain outstanding.

I would not assign a reliable overall platform-completion percentage from this session log alone. The architecture and discovery work is substantially ahead of implementation, and a single percentage would obscure that difference.

## 5. Prioritized TODOs

### P0 — Resolve safety and governance blockers

- Complete Mission 3 and reconcile stale or contradictory evidence records.
- Obtain the ARB ruling for Q1–Q7, including tenant mismatch, soft-deleted organisation, replay, and platform-fallback semantics.
- Obtain PO authorization for the PBDIGIT-211 remediation scope.
- Preserve the isolated test evidence and ensure the fix is validated without exposing shared databases or allowing cross-tenant processing.
- Assign T-5 to a backlog item and authorize its schema design separately.

### P1 — Complete tenant-context and messaging reliability

- Implement the approved scoped tenant-execution model under PBDIGIT-211.
- Ensure the tenant comes from the authoritative message organisation and is restored after every invocation.
- Prevent nested or pre-existing foreign tenant contexts from enabling cross-tenant writes.
- Define durable relay failure recording, including correlation, organisation, retention, and operational visibility.
- Resolve T-3 by classifying permanent Election rejection outcomes correctly.
- Run the isolated regression tests and the appropriate broader test suite after implementation is authorized.
- Update the relevant database and messaging developer guides according to the actual changes and Definition of Done.

### P1 — Resume the correction loop in its accepted order

- Complete PBDIGIT-178 first, including its transaction-boundary requirements.
- Then complete PBDIGIT-177, including the authorized authority-decision intake path.
- Continue with PBDIGIT-174.
- Complete PBDIGIT-182 with real end-to-end correction-loop validation.
- Integrate Governance authority persistence and its approved contract after the required governance gate.
- Ensure challenge filing facts are durably persisted at raise time.
- Complete determination finality and the horizon/lapse trigger.
- Resolve admissibility, filing standing, and terminal outcome semantics through the appropriate governance decisions.
- Validate that adjudication and correction preserve election integrity, auditability, and tenant isolation.

### P2 — Close the broader programme

- Resolve the bounded-context baseline and Governance/Appointment authority-contract ambiguity through the proper architecture governance process.
- Review and authorize PBDIGIT-256 independently.
- Update project-state records when the active ticket or next authorized action changes.
- Complete full regression and resolve remaining known failures.
- Revisit deferred governance and documentation cleanup only after the critical correction loop is demonstrably reliable.

Principal recommendation: proceed with Mission 3 now. Its exit criterion is not more documentation for its own sake; it is a consistent, evidence-backed decision package that lets the ARB and PO make explicit decisions. Only then should Claude implement the approved tenant-context fix and resume the existing adjudication sequence.