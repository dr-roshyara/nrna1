# Membership implementation: next step and independent review

My recommendation is to finalize the Option E correction plan, obtain the Human PO/ARB's explicit decisions, and only then authorize implementation. Do not start PostgreSQL Part 2 yet.

Claude's latest report is a plan, not an implementation result: it confirms that no source, test, contract, or documentation files were changed and no tests or database operations were run during this review. Option E remains unaccepted.&#x20;

Eingefügter Text.txt



## 1. My review as senior DDD architect and principal reviewer

Finding 1 — contract precedence

I agree with Claude's proposed correction. The shared contract must not promise that `ValidTimeNotStorable` always precedes `MemberNotFound` when the PostgreSQL adapter and test double behave differently. Preserve the approved precedence over `ConcurrencyConflict` and describe the unresolved F-3 behaviour accurately.

Finding 2 — E4 and E4-L

Claude correctly distinguishes the repository port's opaque fingerprint contract from a genuinely identical retry. Keep E4 as a test of key-plus-fingerprint idempotency, with an honest name. Add E4-L to characterize replay of a legacy record whose valid time would fail the new check.

Governance gate — still open

These changes appear to be contract clarifications and test improvements, not a new architecture decision. However, they alter approved contract text and add a test-only seeding API. They still require explicit authorization from the Human PO/ARB before implementation.

The proposed scope is appropriately narrow: no production-code changes, no change to `TimePoint`, no weakening of fingerprint verification, no database work in the correction phase, and no claim that PostgreSQL is verified.&#x20;

Eingefügter Text.txt



### Two additional controls I want in the next step

- E4-L must prove the actual observable contract: the retry returns the original result, creates no additional fact, and does not advance the sequence. The sensitivity check must demonstrate that the test fails if the date check is incorrectly moved before the idempotency lookup.
- Repository hygiene must be preserved: Claude reported three unrelated staged renames. They must remain untouched. Any commits must contain only the authorized correction files, not unrelated staged content.&#x20;

  Eingefügter Text.txt



The next step is therefore a controlled, authorization-gated correction, not a new Membership design phase.

## 2. Prompt instructions for Claude

This prompt keeps the roles explicit, requires planning before implementation, and prevents Claude from treating a proposed plan as authorization.

# PublicDigit Membership — Option E Correction and Verification

## 1. Objective

Continue the Membership implementation work as a senior DDD architect, requirements engineer, researcher, formal-logic analyst, and software verification engineer.

The immediate objective is to resolve the two identified Option E review findings with the smallest justified correction, preserve the approved Membership architecture, and establish reliable evidence for a later acceptance decision.

Do not broaden this task into other PublicDigit domains or unrelated technical work.

## 2. Mandatory roles and decision authority

**Claude — investigator and implementation agent**

- Inspect the repository, contracts, tests, and existing evidence.
- Develop a minimal, traceable correction plan.
- Identify contradictions, edge cases, test gaps, and implementation risks.
- Implement only the scope explicitly authorized by the Human PO/ARB.
- Report actual results and evidence; never report planned work as completed.

**ChatGPT — independent principal reviewer**

- Independently challenge the proposed reasoning, contracts, test design, and evidence.
- Identify counterexamples, precedence inconsistencies, hidden assumptions, and regressions.
- Review the proposed plan before implementation and the evidence after implementation.
- Do not treat Claude's conclusions as automatically correct.

**Human PO/ARB — final decision authority**

- Owns architecture and contract decisions, acceptance criteria, risk acceptance, implementation authorization, and formal acceptance.
- No blank verdict, recommendation, previous approval, or silence constitutes authorization.
- Claude must stop and wait when a required decision or authorization is missing.

## 3. Current baseline and repository safety

The previous report identified HEAD `86baabf23`, with three unrelated staged renames from another session. Revalidate the actual repository state before proceeding; do not assume that the previous HEAD or index state is still current.

Before any modification:

1. Record the actual HEAD, working-tree status, staged paths, and unstaged paths.
2. Identify the exact approved correction files.
3. Preserve all unrelated staged renames and existing user changes.
4. Do not reset, clean, stash, amend, or commit unrelated work.
5. Do not push anything.
6. Use an isolated temporary Git index for authorized commits if necessary, and verify the final commit file lists.

If the repository state differs from the report or safe isolation cannot be guaranteed, stop and report the discrepancy.

## 4. Finding C1 — correct contract precedence

Correct the overclaim in Slice 1a, section 4, row R-1a-6.

The contract must:

- Preserve `ValidTimeNotStorable` precedence over `ConcurrencyConflict` for every adapter.
- Explicitly state that precedence against `MemberNotFound` is adapter-specific while PBDIGIT-264 F-3 remains unresolved.
- Describe the PostgreSQL adapter and in-memory test-double behaviours accurately.
- Preserve the approved F-3 scope; do not silently resolve F-3 through documentation.
- Keep the Slice 1a and Slice 1b contracts consistent.
- Add the agreed parity-scope clarifications and update the developer guide's corresponding wording.

Preserve the approved Option E ordering:\
`idempotency-key lookup → valid-time representability check → version check → writes`.

Do not change the ordering or business semantics without a separate decision.

## 5. Finding C2 — E4 and E4-L

### E4: accurately named repository-port contract test

Rename the existing test to describe what it actually proves: a known key with a matching fingerprint is answered without examining the new decision.

Keep the fingerprint opaque at the repository port. The repository must not reconstruct the command fingerprint or weaken fingerprint comparison.

Expected result:

- `AlreadyApplied`;
- the original result and version are returned;
- no new fact is appended.

The test's comment and documentation must not incorrectly call the scenario a genuinely identical command when the test payloads differ.

### E4-L: legacy idempotent replay

Add a test using a test-only seeding mechanism to represent a record written before Option E existed.

The seeded record must contain a key, its matching fingerprint, and a decision with an unsupported stored valid-time ordinal. Retry the same decision using the same key and fingerprint.

Assert that:

- the retry returns `AlreadyApplied`;
- the returned result is exactly the original result;
- no additional fact is written;
- the stored sequence number does not advance;
- the date representability check does not prevent the replay.

Document that the unsupported stored record is a legacy state. New writes after Option E must not create such records.

Keep the bypass strictly test-only. Do not introduce a production bypass or weaken the normal append checks.

For the later PostgreSQL Part 2, plan a corresponding legacy-record test using a carefully isolated raw insert. Assert the replay through the supported append and applied-command interfaces; do not rely on `load()` if it cannot represent the legacy date.

### Test sensitivity

Demonstrate that E4-L can detect a regression in check ordering:

- make a narrowly scoped temporary change that incorrectly checks valid-time representability before idempotency lookup;
- run E4-L and capture the expected failure;
- revert the temporary change;
- verify the working tree and diff to prove that no mutation remains.

Do not leave the deliberately defective implementation in any commit. Record the exact commands, outcomes, and evidence hashes.

## 6. Required verification

After authorization, run the agreed sequence:

1. Capture baseline results for the Application suite and `MembershipStorableValidTimeCoverageTest`.
2. Run E4 and E4-L and capture the sensitivity-check failure and restored passing result.
3. Run the Application suite after the E4 rename.
4. Run the Domain suite, Application suite, DateOrdinal tests, and all four architecture tests.
5. Inspect the final diff, contract consistency, test names, and test-only API boundaries.
6. Verify evidence checksums.
7. Verify that unrelated staged renames and other existing user changes remain untouched.
8. Report exact commands, exit codes, counts, commit IDs, and evidence paths.

Do not claim PostgreSQL verification from in-memory tests.

PostgreSQL Part 2 remains a separate authorization gate. Its future verification must include the agreed Option E cases, E4-L legacy replay, state preservation on refusal, rollback test T7, invariant suites, and race/concurrency suites under the existing isolation controls.

## 7. Research and reasoning discipline

Treat the research corpus as evidence and brainstorming material, not automatically approved domain truth.

Maintain traceability:

`source evidence → requirement → invariant → contract → test → result → decision`

Use formal logic, boundary analysis, counterexamples, and mathematical reasoning to detect contradictions. Machine-learning techniques may help cluster findings or identify candidate inconsistencies, but every finding must be verified against the underlying source and approved contract. Do not use model-generated similarity or confidence scores as authority.

Keep technical storage representability separate from domain calendar validity, historical plausibility, backdating, and scheduling. Do not add a business calendar rule under this task.

## 8. Scope restrictions

Unless explicitly authorized, do not:

- modify production code;
- touch a database or start PostgreSQL Part 2;
- push changes;
- mark Option E, Slice 1a, Slice 1b, Slice 0, or G3 formally accepted;
- resolve F-3 or other open policy questions implicitly;
- modify unrelated staged or unstaged work;
- begin unrelated Membership slices;
- update project status to show completion without the required evidence and decision.

## 9. Required workflow and stop gates

**Stage A — Plan only**

- Revalidate repository state.
- Produce the exact proposed wording and minimal diff.
- Specify E4/E4-L setup, assertions, and sensitivity test.
- List verification commands and expected outcomes.
- Identify every required decision and authorization.
- Make no file changes and run no tests or database commands.

**Stage B — Human PO/ARB decision**\
Wait for explicit decisions on:

- R1: wording corrections C1, C2, C4, C5;
- R2: E4 rename and E4-L test-only seeding plus sensitivity check;
- R3: PostgreSQL E4-L included in the separately gated Part 2 scope;
- R4: authorization for the two correction commits, with no database, push, or acceptance.

Do not infer any verdict.

**Stage C — Authorized correction**\
Only after the required explicit authorization, implement the approved minimal scope and run the authorized checks. If implementation reveals a new architecture or contract decision, stop and request that decision.

**Stage D — Independent review**\
Return the exact diff, test evidence, hashes, commit IDs, and any remaining uncertainty for ChatGPT's independent review. Do not declare formal acceptance yourself.

## 10. Final report format

Return:

1. Repository baseline and safety findings.
2. Exact proposed contract changes.
3. E4 and E4-L test design and expected results.
4. Minimal file-by-file diff.
5. Verification plan and any unresolved risks.
6. Required Human PO/ARB decisions.
7. Explicit statement of what was not changed or run.

At the end of Stage A, stop and wait. Do not implement until the plan is finalized and the Human PO/ARB has explicitly authorized the work.

## 3. Decisions to make before Claude proceeds

Claude's report proposes four decisions. My independent recommendation is:

| Decision                                  | My recommendation                                                                             | Authority    |
| ----------------------------------------- | --------------------------------------------------------------------------------------------- | ------------ |
| R1 — Correct contract wording             | Approve                                                                                       | Human PO/ARB |
| R2 — Rename E4 and add E4-L               | Approve, subject to the stated assertions and test-only isolation                             | Human PO/ARB |
| R3 — Include PostgreSQL E4-L in Part 2    | Approve its inclusion in the future test scope, but do not authorize Part 2 execution yet     | Human PO/ARB |
| R4 — Authorize the two correction commits | Authorize only the narrowly scoped correction after confirming the plan and repository safety | Human PO/ARB |

These are recommendations, not recorded decisions. The existing report explicitly leaves the verdicts blank.&#x20;

Eingefügter Text.txt



## 4. How far are we from the Membership implementation goal?

The goal is to establish a robust, auditable Membership domain model and implement it safely in PublicDigit, with traceable requirements, reliable persistence, controlled corrections, migration, integrations, and eventual release acceptance.

The following is a qualitative status, not a percentage estimate.

Domain theory and core model

Substantially implemented

The architecture is ratified and frozen for implementation. Core Membership concepts, temporal modelling, append-only facts, derived current state, and correction constraints provide the foundation.

Domain code and persistence foundation

Partially implemented

The domain core, Slice 0, Slice 1a and Slice 1b work, and Option E have been reported as implemented to varying stages. Option E has reported local test evidence, but the latest correction review is still pending authorization and PostgreSQL verification remains open.

Architecture and release acceptance

Outstanding gates

Formal verdicts, unresolved contract questions, adapter verification, and release evidence still need to be completed. Implementation must not be equated with acceptance.

Remaining end-to-end Membership capability

Significant work remains

The later slices cover additional domain behaviour, integrations, migration, UI, and full regression and release acceptance. These remain part of the overall goal.

The current Option E status and the remaining formal gates are supported by the latest report.&#x20;

Eingefügter Text.txt

&#x20;The wider slice backlog is based on the previously established Membership project state.&#x20;



## 5. Remaining todos — prioritized

### P0 — Finish the immediate Option E review and correction

- Finalize the exact wording changes for Slice 1a, Slice 1b, and developer guide 06.
- Obtain explicit Human PO/ARB decisions on R1–R4.
- Rename E4 so its name accurately describes the repository-port contract.
- Add E4-L with a genuine legacy replay scenario and the required assertions.
- Run the controlled sensitivity check, revert the deliberate defect, and verify the final diff.
- Run the authorized regression tests and produce traceable evidence with hashes.
- Preserve the three unrelated staged renames.
- Obtain ChatGPT's independent review of the completed correction.
- Keep Option E unaccepted until its required gates close.

### P1 — Complete persistence and contract verification

- Obtain separate authorization for PostgreSQL Part 2.
- Verify E1–E5 and E4-L against PostgreSQL.
- Verify that refusals preserve the expected state and that rollback, invariant, and race/concurrency tests pass.
- Resolve F-3 and the remaining F-2, F-4, and F-5 questions through explicit decisions and evidence.
- Complete the applicable formal verdicts for G3, Slice 0, Slice 1a, Option E, and Slice 1b.
- Update PBDIGIT-264 and project status through the established status-synchronization process.

### P2 — Continue the remaining Membership implementation

- Finalize decisions for Slice 2 and progress Slices 2, 3, and 8.
- Complete the C-2, C-3, and C-4 correction cases.
- Complete integration Slices 9–12.
- Implement Slice 13 migration and normalization, including legacy date-placeholder classification, dry-run reporting, provenance, and registrar review.
- Implement Slice 14 UI capabilities.
- Complete Slice 15 regression, security, and release acceptance.

### P3 — Strengthen the Membership theory and research corpus

- Maintain traceability from evidence to requirements, invariants, contracts, tests, and approved decisions.
- Use formal logic and counterexamples to identify contradictions and unspecified behaviour.
- Use mathematical and statistical methods for boundary analysis, consistency checks, and migration-data analysis.
- Use machine learning only to generate candidate clusters, anomalies, and contradictions; validate findings against the source evidence.
- Keep storage representability, domain calendar rules, historical plausibility, and backdating/scheduling as distinct concerns.
- Keep the implementation roadmap, decision register, evidence index, and project state synchronized.

## 6. Final recommendation

The immediate next milestone is not another Membership feature. It is a controlled Option E correction with explicit authorization and reproducible evidence.

Once the Human PO/ARB authorizes R1–R4, Claude can execute the narrowly scoped correction, ChatGPT can independently review the results, and the Human PO/ARB can decide whether the relevant acceptance gates are satisfied.

Only after that should we proceed to the separately authorized PostgreSQL verification. This sequence protects the ratified architecture, avoids accidental policy decisions, and keeps the final Membership implementation auditable.