# Membership implementation — principal review and next step

I have reviewed Claude’s attached report, `Eingefügter Text(20261010-201555).txt`, as an independent DDD architect and principal reviewer. The report is a planning result, not an implementation result. Claude has verified the relevant test inheritance and proposed exact test commands, but has not implemented F3d, run the test suites, changed production code, or created commits.&#x20;

Eingefügter Text.txt



## 1. My review verdict

The F-3 clarification plan is sufficiently detailed to proceed to the Human PO/ARB approval gate. I do not recommend another broad research round. The next step is a controlled decision followed, if authorised, by the narrowly scoped implementation.

What is sound in the plan

- It distinguishes idempotency-key outcomes from call validation.
- It tests both matching and differing fingerprints for a refused decision under an already-used key.
- It checks that member state, version, log and original applied-command record remain unchanged.
- It includes a sensitivity test to show that F3d detects the proposed regression.
- It preserves the unrelated three staged renames and excludes production changes, database work, pushes and context synchronisation.

What remains unproven

- F3d has not yet been implemented or executed.
- The sensitivity test has not yet demonstrated that the test fails under the temporary defect and passes after restoration.
- The PostgreSQL adapter’s F3d behaviour is predicted, not verified; it remains deferred to Part 2.
- No new evidence, regression results or commits exist for this clarification yet.

### The key architectural decision

The proposed contract wording makes an important distinction:

1. Call validation: a refused decision is rejected with `InvalidArgumentException`, even if its idempotency key has already been used.
2. Outcome-producing checks: for an accepted decision, the key lookup takes precedence over outcomes such as `AlreadyApplied`, `IdempotencyKeyConflict`, `ValidTimeNotStorable`, `MemberNotFound` and `ConcurrencyConflict`.

This distinction is coherent with the port contract described in the report. F3d should preserve it as an executable contract test rather than changing production behaviour unnecessarily.&#x20;

Eingefügter Text.txt



### Small execution controls I require

Before implementation, Claude must retain these controls:

- Record actual test commands, exit codes and results; treat the report’s counts as predictions until execution confirms them.
- Capture the temporary mutation and prove the test detects it; restore the file byte-for-byte and rerun F3d.
- Keep evidence credential-safe: no `.env` contents, secrets, tokens, passwords or connection settings.
- Use the temporary Git index as planned and verify that the three unrelated staged renames remain untouched.
- Stop on any unexpected test result, changed file outside scope, or failure to restore the mutation.
- Do not mark the architecture, F-3 or Membership implementation accepted merely because tests pass.

## 2. What to do now

The decisions already recorded are K-1 approved, K-2 approved and K-3 approved. K-4 remains pending: plan-first, implementation only after explicit Human PO/ARB authorisation. Context synchronisation remains deferred.&#x20;

The next sequence is:

1. Human PO/ARB reviews and explicitly authorises K-4. This is the current gate; Claude must not infer approval from the existence of a reviewed plan.
2. Claude implements only the approved F-3 clarification scope: F3d, the contract wording, the D2/D3 corrections, the developer guide and the specified evidence.
3. Claude runs the checks and reports actual results: baseline, F3d alone, sensitivity test, restored F3d, regression suites and evidence-integrity checks.
4. ChatGPT independently reviews the implementation evidence and diff. I will assess contract consistency, test quality, scope control and whether the evidence supports the conclusions.
5. Human PO/ARB decides the acceptance status and next authorisation. PostgreSQL Part 2 and subsequent work remain separate gates.

## 3. Prompt for Claude — next implementation step

Use this prompt after you explicitly approve K-4 as Human PO/ARB. Until then, the plan remains unimplemented.

# PBDIGIT-264 — F-3 Clarification: Controlled Implementation

## Roles and authority

Work under this governance model:

- **Claude — implementation engineer:** Execute the approved plan, inspect the repository, implement the scoped test and documentation changes, run tests, preserve evidence and report deviations.
- **ChatGPT — independent principal reviewer:** Review the architecture, contract semantics, test adequacy, implementation diff, regression results and evidence. Do not treat Claude's self-assessment as independent acceptance.
- **Human PO/ARB — final authority:** Authorise implementation, decide acceptance and approve subsequent work.

## Mandatory authorisation gate

Before modifying any file, verify that the Human PO/ARB has explicitly authorised K-4 implementation of the reviewed F-3 clarification plan.

If explicit authorisation is absent or ambiguous:

- Do not implement anything.
- Do not run the planned test suites or perform the temporary mutation.
- Report that implementation remains unauthorised and stop.

## Approved scope

Implement only the reviewed F-3 clarification plan in the report dated 2026-10-10.

1. Add F3d to the shared `MemberRepositoryContract.php` contract test. Test that a refused decision under an already-used idempotency key is rejected with `InvalidArgumentException` for both matching and differing fingerprints, and that repository state remains unchanged.
2. Clarify Slice 1a §7: accepted-decision validation precedes key lookup; key lookup precedes every outcome-producing check.
3. Correct the specified D2 and D3 wording in Slice 1a.
4. Add the F3d contract row to the Slice 1b contract documentation, clearly identifying PostgreSQL verification as Part 2 only.
5. Update developer guide 07 with the approved decision, rationale and test traceability.
6. Update the specified plan and create the credential-safe evidence folder and files described in the reviewed plan.
7. Create only the two proposed scoped commits, using the temporary-index safeguards, after all checks pass.

Do not broaden this scope.

## Execution procedure

### Phase A — establish the baseline

- Verify branch, HEAD, working tree and staged changes.
- Confirm that the unrelated three staged renames remain untouched.
- Reconfirm the shared contract's concrete test classes and exact test commands.
- Run the approved baseline suites and record actual outputs and exit codes.
- If baseline results differ from the plan's predictions, stop and report before proceeding.

### Phase B — implement and validate F3d

- Add the test exactly as specified in the reviewed plan.
- Run F3d alone and the complete relevant Application suite.
- Report actual test counts and outcomes; never present predicted counts as observed results.

### Phase C — perform the sensitivity check

- Record the test double's checksum.
- Introduce only the specified temporary mutation, clearly marked as a sensitivity-test mutation.
- Run F3d and verify that it fails for the intended assertion.
- Restore the original file byte-for-byte.
- Verify the before/after checksum matches, the mutation marker is absent and no unintended diff remains.
- Rerun F3d and verify that it passes.
- If the test does not detect the mutation, or restoration cannot be proven, stop. Do not commit.

### Phase D — documentation and regression

- Apply only the approved documentation corrections.
- Run all regression commands specified in the reviewed plan.
- Verify evidence checksums and credential-safety checks.
- Never copy `.env` files, secrets, tokens, passwords, environment variables or database connection settings into the evidence.
- Do not provision or connect to PostgreSQL as part of this task.

### Phase E — scope and commit controls

- Review every changed path and the complete diff.
- Use the reviewed temporary-index procedure so the unrelated staged renames are not included.
- Create the two proposed commits only if every required check passes.
- Do not push.
- Do not alter production code, schema or database.
- Do not update `.claude/CONTEXT-publicdigit.md` or the session log; context sync remains deferred.
- Do not change any acceptance status or start another Membership task.

## Stop conditions

Stop immediately and report if:

- baseline or regression results differ unexpectedly;
- F3d does not detect the temporary mutation;
- the original test-double file cannot be restored exactly;
- unrelated staged changes or out-of-scope files are altered;
- evidence cannot be verified or may contain credentials;
- a required test cannot be run.

Do not silently work around a failed gate.

## Final report

Provide:

1. The explicit authorisation checked.
2. Files changed and the purpose of each change.
3. Exact commands, actual exit codes and actual test counts.
4. Sensitivity-test failure, restoration proof and successful rerun.
5. Regression results and evidence-integrity results.
6. Commit hashes and their file lists, if commits were created.
7. Any deviations, unresolved risks and checks not run.
8. A clear statement that PostgreSQL Part 2 remains unverified.
9. A recommendation for ChatGPT's independent review.

Stop after this scoped task. Do not proceed to F-5, F-4, F-2 or any other Membership work without separate authorisation.

## 4. How far are we from the Membership implementation goal?

The overall Membership architecture has been ratified and frozen for implementation. That is a significant milestone, but architecture ratification is not the same as completing and accepting the implementation.

## Current progress

Architecture and domain design

Ratified / frozen

The canonical Membership Architecture is ratified. The immediate architectural issue is the F-3 contract clarification, not reopening the whole design.

F-3 clarification

Plan ready; implementation pending

The plan is detailed and ready for the Human PO/ARB gate. F3d, its sensitivity check and the documentation/evidence changes have not yet been executed.

PostgreSQL verification

Deferred to Part 2

PostgreSQL behaviour is not verified by the current in-memory plan. Database provisioning, test execution and evidence remain separate work.

The previous workflow reports describe Slice 0, Slice 1a, the in-memory F-3 increment and related work as implemented or locally tested, but formal acceptance remains outstanding for relevant slices. The current report does not establish a verified percentage for the complete Membership implementation, so I would not claim that the overall goal is, for example, 80% or 90% complete.

## 5. Remaining todos

### P0 — Current gate

- Human PO/ARB explicitly approves or rejects K-4 implementation authorisation.
- If approved, Claude implements F3d and the specified contract/documentation changes.
- Run the sensitivity check and prove exact restoration of the test double.
- Run the agreed regression suites and capture actual results.
- Produce credential-safe, verifiable evidence and the two scoped commits.
- ChatGPT performs independent review of the diff, tests and evidence.
- Human PO/ARB makes the formal acceptance decision.

### P1 — PostgreSQL verification

- Prepare and verify an isolated PostgreSQL test environment under the separate Part 2 process.
- Preserve the existing restriction against unintended use of `nrna_test`.
- Obtain explicit authorisation before database provisioning or test execution.
- Run the planned PostgreSQL suites, including F3d, where applicable.
- Verify state preservation, rollback, invariants and relevant concurrency behaviour.
- Record evidence and make a formal verification decision.

### P2 — Continue the approved implementation sequence

- Proceed to F-5 only after the required PostgreSQL Part 2 work is complete and separately authorised.
- Proceed to F-4 after F-5, following the approved sequence.
- Proceed to F-2 after F-4, following the approved sequence.
- Obtain explicit review and acceptance decisions at the appropriate gates.

### P3 — Complete the broader Membership goal

- Resolve outstanding formal verdicts for G3, Slice 0 and Slice 1a.
- Resolve documented business-rule and contract questions, including business-calendar behaviour, BD-NORM placeholders and D-1 through D-5 and C-2 through C-4.
- Continue the remaining Membership slices, including Slices 2, 3 and 8.
- Complete the required integration work for Slices 9–12.
- Complete Slice 13 migration, Slice 14 UI and Slice 15 release work.
- Maintain traceability between domain rules, requirements, contracts, tests, evidence and acceptance decisions.
- Revisit context/session-log synchronisation only as a separately controlled task, preserving the other session's edits.

My recommendation: do not start another broad theory or machine-learning research round now. The immediate risk is not a lack of theory; it is whether the approved domain contract is made executable, verified and supported by trustworthy evidence. Complete that small, controlled gate first, then use the verified results to decide the next step.