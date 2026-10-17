# Membership implementation — independent principal review

I reviewed the attached Claude execution report for the F-3 increment. The immediate next step is not to start F-5 or broaden the implementation. It is to complete an independent review of F-3, resolve any contract or evidence inconsistencies, and then obtain the appropriate Human PO/ARB disposition before moving on to the PostgreSQL verification stage.

## 1. What the report establishes

F-3 implementation completed on the test double

Claude reports three commits: `d908f93c4` (RED tests), `d2a3f2b17` (test-double fix), and `52b69c4e8` (contracts, guide, gate register, evidence). No production code was changed, nothing was pushed, and no database was touched.

Local regression passed

The reported results are Domain 89, Application 54, DateOrdinal 5, and Architecture 13, all with exit code 0. The four new contract tests failed before the fix as predicted, then passed afterward.

PostgreSQL parity is not established

The shared tests are intended to run against the PostgreSQL adapter too, but the report explicitly says they have not yet run there. F-3 is implemented and locally tested, not formally accepted.

Human decisions are recorded

The report records F3-1 through F3-4 as adopted, approved, and authorised, including Path B: F-3 before F-5. These authorisations cover the F-3 increment, not the later PostgreSQL Part 2 execution.

## 2. My principal-review findings

### Finding 1 — Verify the “key first” rule against the actual final code

The adopted contract says the idempotency-key lookup precedes every other check in `append`. However, the report’s displayed test-double excerpt shows an accepted-decision validation before the key lookup, followed by the key lookup and valid-time check.

That may be an intermediate diff artifact, or it may expose a real discrepancy. The final committed code must decide this question, not the transcript. Claude should inspect the exact committed implementation and compare both adapters against the adopted contract. If any check still precedes the key lookup, it must identify the conflict and propose the smallest authorised correction.

### Finding 2 — Confirm the collision test really proves an exception and no state change

F3a is meant to establish that reusing a `MemberId` belonging to another holder in the same tenant raises a `RuntimeException` and leaves all relevant state unchanged. The report describes an adjustment to avoid accidentally catching PHPUnit’s own assertion failure.

The final review should confirm the committed test actually captures the thrown exception, asserts its type outside the `try`/`catch`, and verifies the original holder, version, fact log, holder mappings, and other-tenant state. The report’s test results are encouraging, but an independent reviewer should inspect the final code and evidence.

### Finding 3 — Check the contract documents for duplicate and stale wording

The transcript shows updates to the Slice 1a and Slice 1b contracts and guide 06. Some excerpts contain both old and replacement text, or repeated policy statements. Those could simply be patch-output artefacts; nevertheless, Claude should inspect the resulting files to ensure that:

- R-1a-6 has one authoritative, internally consistent definition.
- The former adapter-specific `MemberNotFound` precedence is clearly marked as superseded, not still presented as current policy.
- F-3’s collision and key-order rules are not duplicated inconsistently.
- Documentation distinguishes implemented locally, PostgreSQL unverified, and formally accepted.

### Finding 4 — Keep technical proof separate from formal acceptance

The evidence currently supports a narrow conclusion: F-3 passes on the in-memory test double and the reported local regression suites pass. It does not yet prove PostgreSQL conformance, and it does not constitute formal acceptance of F-3, Option E, or Slice 1b.

The three unrelated staged renames must remain untouched. The session log and project context should be synchronised only within the authorised scope; the report says the session log was left uncommitted and `CONTEXT.md` was not updated.

## 3. Prompt for Claude — next step

The next Claude task should be a read-only independent verification of the completed F-3 increment, followed by a correction plan if needed. Do not jump straight into further implementation.

# Claude Task: Independent F-3 Review and Next-Step Readiness — PublicDigit Membership

## 1. Your role

Act as a senior Domain-Driven Design architect, principal-level software reviewer, requirements engineer, test architect, and researcher with expertise in formal logic, mathematical reasoning, statistics, and software correctness.

This task belongs exclusively to the **PublicDigit Membership implementation workstream**.

## 2. Governance and authority

Respect this decision model:

- **Claude:** inspect the repository, establish facts, identify defects and inconsistencies, formulate a minimal correction plan, and implement only after the applicable plan and architecture have been reviewed and authorised.
- **ChatGPT:** independently challenge the evidence, domain contract, logic, tests, adapter parity, architecture, and proposed plan. Do not treat Claude's self-review as independent acceptance.
- **Human PO/ARB:** retains final authority over policy, scope, sequencing, implementation authorisation, and formal acceptance.

Previously recorded decisions F3-1 through F3-4 authorise the specified F-3 increment. They do not authorise PostgreSQL Part 2, F-5, F-4, F-2, production changes, pushing, or formal acceptance.

Preserve all unrelated staged work. Never discard, reset, overwrite, or include unrelated staged changes in commits.

## 3. Phase A — Read-only verification

Do not modify files, run database operations, create commits, push, or change project status during this phase.

First establish the actual repository state:

1. Record the current branch, HEAD, working-tree status, staged paths, and latest F-3 commits.
2. Confirm the contents of:
   - `d908f93c4`
   - `d2a3f2b17`
   - `52b69c4e8`
3. Inspect the final committed files, not just the previous session transcript or patch summaries.
4. Confirm that the three unrelated staged renames remain untouched.
5. Confirm that no production code was changed by the F-3 commits.
6. Verify the recorded evidence and checksums against the actual repository state.

Report any mismatch between the recorded commit IDs and the actual repository. Do not assume the current HEAD or index is identical to the prior session.

## 4. Phase B — Verify the governing contract

Use the adopted F-3 policy and the ratified Membership architecture as the governing sources. Inspect the relevant Slice 1a and Slice 1b contracts, the ratification pack, the implementation plan, and developer guides 06 and 07.

Verify these rules:

### F-3 policy

- An idempotency-key lookup precedes every other check in `append`, in every adapter.
- A known key with the same fingerprint returns the original result without creating another fact.
- A known key with a different fingerprint returns `IdempotencyKeyConflict`.
- A `MemberId` already belonging to another holder in the same tenant causes an infrastructure failure compatible with `RuntimeException`; it must not overwrite existing state.
- Tenant isolation remains intact.

### R-1a-6 policy

- The key lookup precedes the storable-valid-time check.
- The storable-valid-time check precedes the member/version step.
- `ValidTimeNotStorable` takes precedence over `MemberNotFound` and `ConcurrencyConflict`, according to the adopted contract.
- The technical persistence limit must remain distinct from any business-calendar rule.
- `TimePoint` and unrelated domain concepts must not be changed.

### Critical code-order audit

Inspect the actual final `append` implementation in the test double and PostgreSQL adapter.

The previous transcript displayed accepted-decision validation before the key lookup in the test double. Determine whether this is merely a patch-output artefact or a real contract violation.

Do not dismiss it without inspecting the committed source. If any check occurs before the key lookup, document exactly what it is, whether it can affect an already-applied command, and whether the current domain types make that check redundant or necessary.

If the rule and implementation conflict, do not silently reinterpret the approved policy. Propose the smallest correction and explain its implications for both adapters and the tests.

## 5. Phase C — Test and evidence audit

Inspect the actual tests and determine whether each proves its intended property.

- **F3a:** MemberId collision raises the required exception and preserves the original holder, version, log, mappings, and other-tenant state.
- **F3b-1:** unknown MemberId plus a known key and matching fingerprint returns the original result without adding a fact.
- **F3b-2:** unknown MemberId plus a known key and different fingerprint returns `IdempotencyKeyConflict`.
- **F3c:** unknown MemberId plus an unused key and an unstorable valid time returns `ValidTimeNotStorable`.

Check particularly that F3a cannot accidentally catch its own PHPUnit assertion failure and report a false positive.

Assess whether the tests establish the approved contract rather than merely exercising the current implementation. Identify missing combinations, weak assertions, misleading test names, or tests that could pass despite a regression.

Do not expand scope into unrelated features. Distinguish:

- confirmed defects;
- missing evidence;
- optional future hardening.

Reconcile the recorded regression results with the actual evidence files. Do not rerun the database tests or access a database in this phase.

## 6. Phase D — Documentation and traceability audit

Inspect the final documents for stale, duplicate, or contradictory wording. In particular:

- Slice 1a R-1a-6 must have one clear authoritative rule.
- Historical adapter-specific wording must be labelled as superseded.
- The F-3 policy must not be duplicated with conflicting formulations.
- Slice 1b must clearly state that PostgreSQL conformance remains unverified until Part 2.
- The status of each artefact must distinguish implemented, locally tested, PostgreSQL verified, and formally accepted.
- Evidence must be traceable to exact commits, tests, commands, exit codes, and checksums.

Check the active project context and session log. Report missing synchronisation, but do not update them during this phase.

## 7. Phase E — Produce a decision-ready review

Return a report with these sections:

1. Repository state and scope integrity.
2. Commit-by-commit review.
3. F-3 policy compliance matrix: requirement, source, test, implementation, evidence, verdict.
4. R-1a-6 precedence audit for both adapters.
5. Test quality and evidence integrity.
6. Documentation and traceability findings.
7. Confirmed defects versus uncertainties.
8. Minimal correction plan, if needed.
9. PostgreSQL Part 2 readiness and outstanding prerequisites.
10. Recommended next action and the decisions still required.

Use explicit verdicts: **PASS**, **FAIL**, **NOT VERIFIED**, or **NOT APPLICABLE**. Never label unexecuted PostgreSQL tests as passing.

## 8. Formal reasoning and research discipline

Treat the corpus as research evidence, not as unquestionable truth. Preserve provenance and distinguish approved policy from hypotheses and historical observations.

Use counterexamples and state-transition reasoning where useful. Consider invariants such as:

- At most one Member per `(TenantId, PersonRef)`.
- A collision cannot change the existing holder's state.
- An idempotent retry cannot append a duplicate fact.
- Tenant boundaries cannot be crossed.
- Refusal outcomes must respect the approved precedence contract.

Use mathematics, statistics, property-based testing, or machine-learning techniques only when they provide measurable value. Machine learning may suggest cases or classify evidence, but it must not decide domain policy or replace deterministic tests and explicit PO/ARB decisions.

## 9. Phase F — Stop for independent review

This is a read-only review task.

Do not implement corrections, change the gate register, update acceptance status, provision PostgreSQL, run database tests, commit, or push.

If a defect is found, provide the smallest correction plan with:

- exact files and expected changes;
- the contract or decision authorising each change;
- tests that should fail before the fix and pass after it;
- regression and evidence requirements;
- scope exclusions and rollback considerations.

Stop and present the report to ChatGPT for independent review. Any further implementation must follow the reviewed plan and applicable Human PO/ARB authorisation.

## 10. Explicit stopping boundary

Do not start F-5, F-4, or F-2. Under the approved Path B sequence, PostgreSQL Part 2 must be separately provisioned and authorised before that verification stage proceeds. The reported F-3 authorisation does not grant that permission.

The immediate objective is to establish whether the completed F-3 increment is internally consistent, correctly tested on the double, accurately documented, and ready for independent review.

## 4. Where we stand against the Membership implementation goal

The following assessment is based on the attached report and the project context available here; it is not a claim that every repository file has been independently inspected.

| Area                                              | Current status                                                                             |
| ------------------------------------------------- | ------------------------------------------------------------------------------------------ |
| Membership domain and ratified architecture       | Ratified and frozen for implementation                                                     |
| Slice 0 kernel                                    | Implemented; formal verdict remains outstanding in the report                              |
| Slice 1a contract/application                     | Implemented and locally tested; formal verdict remains outstanding                         |
| Slice 1b PostgreSQL adapter                       | Implemented, but PostgreSQL behaviour is unverified                                        |
| Option E / storable valid time                    | Implemented and locally tested; correction evidence reported as verified, but not accepted |
| F-3 key-first and collision behaviour             | Implemented and tested on the double; PostgreSQL unverified; not accepted                  |
| C-1 correction                                    | Implemented; acceptance remains outstanding                                                |
| F-5, F-4 and F-2                                  | Not completed; constrained by the approved sequence and PostgreSQL Part 2 gate             |
| Later integration, migration, UI and release work | Substantial work remains                                                                   |

The key distinction is that implementation progress is ahead of verification and formal acceptance. Passing local tests is necessary evidence, but it is not sufficient evidence for PostgreSQL conformance or for declaring the increment accepted.

## 5. Remaining todos

### P0 — Complete the independent F-3 review

- Give Claude the read-only review prompt above.
- Verify the exact final code for key-first ordering in both adapters.
- Verify F3a cannot pass by catching its own assertion failure.
- Resolve any confirmed contract, test, documentation, or evidence defects through a reviewed, authorised correction plan.
- Have ChatGPT independently review Claude’s findings and proposed corrections.
- Keep F-3 formally unaccepted until the required review and acceptance process is completed.

### P1 — Prepare PostgreSQL Part 2

- Provision and separately authorise the PostgreSQL verification environment.
- Run the shared F3a–F3c contract tests against PostgreSQL.
- Verify Option E tests E1–E5 and legacy replay E4-L.
- Run the authorised state-preservation, rollback, invariant, and race-condition tests.
- Capture reproducible evidence, exit codes, environment details, and checksums.
- Review the results independently before issuing formal verdicts for Option E and Slice 1b.

### P2 — Continue the approved implementation sequence

- Execute F-5 after PostgreSQL Part 2 is provisioned and authorised.
- Proceed to F-4, then F-2, in the approved order.
- Preserve the existing approved sequencing unless the Human PO/ARB explicitly changes it.
- Do not treat authorisation for one increment as authorisation for subsequent work.

### P3 — Close architecture and backlog decisions

- Obtain the outstanding formal verdicts for G3, Slice 0, and Slice 1a where their evidence is sufficient.
- Resolve the open business-calendar rule, BD-NORM migration placeholders, Slice 2 decisions D-1 to D-5, and C-2 to C-4.
- Plan Slices 2, 3, and 8, then the Slices 9–12 integrations.
- Plan Slice 13 migration, Slice 14 UI, and Slice 15 release.
- Maintain traceability from business rule → invariant → contract → test → implementation → evidence → acceptance.
- Synchronise the project context, session log, roadmap, and acceptance register within the relevant authorisation boundaries.

My recommendation: start with the read-only F-3 review. It is the smallest step that can materially improve confidence without risking unrelated work. After the review, correct only confirmed issues, then move to the separately authorised PostgreSQL verification stage.