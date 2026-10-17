# Membership Option E — Principal Reviewer result

Claude has completed the authorized Option E implementation increment. The next step is independent result review, correction of two contract/test issues, and then a Human PO/ARB disposition. We should not proceed straight to PostgreSQL verification or mark Option E accepted yet.

The report records three commits:

- `0e4e5ac7a` — tests-first change (RED).
- `af495c838` — implementation (GREEN).
- `3ee8c2fe4` — contracts, developer guide and evidence.

Claude reports that the focused local tests and regression suites passed, the evidence manifest verified 41/41 checksums, and no database was used. PostgreSQL remains unverified and Option E remains formally unaccepted. These are results reported in the attachment, not a fresh execution by me.&#x20;

Eingefügter Text.txt

Eingefügter Text.txt



## 1. What is good

- The new persistence boundary is implemented without changing `TimePoint` or adding a business calendar rule.
- The new `ValidTimeNotStorable` outcome is explicitly mapped in the application layer.
- The test-first process produced the reported expected assertion failures before the fix.
- The regression results are reported as green for the domain, application, date conversion and Membership architecture suites.
- The P1 release gate is explicit: PostgreSQL verification is still required before acceptance, integration or production use.&#x20;

  Eingefügter Text.txt

  Eingefügter Text.txt



These are meaningful results. However, green local tests establish only the properties those tests actually exercise.

## 2. Two issues I want resolved before acceptance

Finding 1 — the shared contract overstates precedence

The Slice 1a amendment says `ValidTimeNotStorable` takes precedence over both `ConcurrencyConflict` and `MemberNotFound`. But the accepted F-3 limitation says the in-memory test double checks `MemberNotFound` first, whereas PostgreSQL checks the key and date before the version update.

Those statements cannot both be universal guarantees. The report acknowledges the difference, but the shared contract wording must distinguish the general repository contract from the PostgreSQL-specific precedence.&#x20;

Eingefügter Text.txt

Eingefügter Text.txt



Required action: propose a minimal wording correction. Preserve the approved date-before-version rule. State explicitly that precedence against `MemberNotFound` is adapter-specific while F-3 remains open.

Finding 2 — E4 does not demonstrate an identical retry

In the supplied test excerpt, E4 first appends an `Admit` decision with fingerprint `fp1`, then submits an `End` decision with the same key and manually supplied fingerprint `fp1`. Those are different command payloads, so this test does not establish that an identical retry with the same payload returns the original result.&#x20;

Eingefügter Text.txt



Required action: replace or supplement E4 with a controlled historical/legacy replay fixture or stub. It must demonstrate that the stored key and fingerprint genuinely correspond to the retried command, and that the original result is returned before the new date check. If the scenario represents a pre-Option-E record, document that explicitly.

These findings do not mean the whole implementation is wrong. They mean the evidence and contract need to describe the intended guarantees precisely before formal acceptance.

A further repository-hygiene point: Claude reports that three renames from another session remain staged. Therefore, the next operation must inspect the actual Git status and index; it must not reset, commit, or otherwise alter those unrelated staged changes.&#x20;

Eingefügter Text.txt



## 3. Prompt for Claude — focused correction and review

Do not ask Claude to rewrite the whole plan again. Ask for a small, plan-only correction proposal that addresses these two findings and protects the repository state.

# Membership Option E — Post-Implementation Review Findings

## Roles

- **Claude:** Investigate the findings, propose the smallest correction, and implement only within the scope approved by the Human PO/ARB.
- **ChatGPT:** Independently review the implementation, contracts, tests and evidence.
- **Human PO/ARB:** Owns contract decisions, residual-risk acceptance, formal acceptance and authorization of PostgreSQL verification.

## Current baseline

Option E (R-1a-6) has been implemented in three reported commits:

- `0e4e5ac7a` — tests-first change.
- `af495c838` — implementation.
- `3ee8c2fe4` — contracts, guide and evidence.

The report records green local regression tests, 41/41 evidence checksums verified, no database used, PostgreSQL unverified, and Option E not formally accepted.

Revalidate the actual HEAD, working tree and index before doing anything. Three unrelated staged renames were reported by the previous session. Preserve them exactly; do not reset, commit, stash, or modify them.

## Finding 1 — Contract precedence and F-3

The shared Slice 1a contract currently says `ValidTimeNotStorable` takes precedence over `MemberNotFound`, while the accepted F-3 limitation says the in-memory test double checks `MemberNotFound` first.

Propose the smallest wording correction that:

- Preserves key lookup → valid-time check → version check for the approved Option E behaviour.
- Preserves R-1a-6 precedence over `ConcurrencyConflict`.
- Does not falsely claim that valid-time refusal universally precedes `MemberNotFound`.
- Identifies the PostgreSQL-specific precedence and the test-double limitation.
- Keeps the Slice 1a and Slice 1b contracts consistent.

Do not silently change the approved architecture or broaden F-3.

## Finding 2 — E4 replay semantics

Review E4 in the shared repository contract suite. The supplied excerpt first appends an `Admit` command with fingerprint `fp1`, then retries with an `End` command while manually reusing `fp1`.

Those payloads differ. The test therefore does not establish the claimed identical-retry behaviour.

Propose a reliable test using a controlled repository stub or a seeded historical/legacy record. Ensure the stored key and fingerprint genuinely correspond to the retried command. If the case models a command accepted before Option E, document that assumption explicitly.

The test must prove that a valid existing idempotency record returns its original result before the new date check. It must not weaken fingerprint verification or change the idempotency contract.

## Operating mode

For this step, PLAN/REVIEW ONLY:

- Do not edit source, tests, contracts or documentation.
- Do not run tests or touch a database.
- Do not commit or push.
- Do not change unrelated staged work.

Return:

1. The exact contract clauses needing correction.
2. The revised E4 setup, expected result and evidence required.
3. Whether either finding requires a substantive architecture decision or only clarification within the approved contract.
4. The smallest proposed diff and the affected files.
5. A verification plan, including PostgreSQL Part 2 requirements.
6. Any additional concrete defects discovered while checking the relevant code and tests.

Then stop and wait for ChatGPT's review and any required Human PO/ARB decision or authorization.

Do not mark Option E accepted. Do not claim PostgreSQL verification.

## 4. What happens after Claude responds?

1. ChatGPT reviews the correction proposal. Confirm that the contract no longer contradicts F-3 and E4 really tests replay semantics.
2. Human PO/ARB decides whether the wording and test correction are approved. Keep the existing architectural decisions unchanged unless a genuine new decision is required.
3. Claude applies the narrowly scoped correction under the appropriate authorization, runs the relevant tests, captures evidence and reports the final diff.
4. Obtain separate authorization for PostgreSQL Part 2. Follow the isolated-database and connection restrictions already specified in the plan.
5. Review Part 2 evidence before making a formal Option E or Slice 1b acceptance decision.

## 5. How far are we from the Membership implementation goal?

The current report supports this status:

| Area                        | Status                                                                          |
| --------------------------- | ------------------------------------------------------------------------------- |
| Membership domain core      | Implemented, with substantial test evidence                                     |
| Slice 0 and Slice 1a        | Implemented; formal verdicts outstanding                                        |
| Slice 1b PostgreSQL adapter | Implemented; PostgreSQL verification and formal disposition outstanding         |
| C-1 Annul/Void              | Implemented; formal G3/acceptance remains outstanding                           |
| Option E                    | Implemented and locally tested; review corrections and formal acceptance remain |
| Slices 2–15                 | Substantial work remains, mostly not started                                    |

The implementation has progressed beyond the previous plan-only stage. But the overall Membership capability is not complete, and a reliable completion percentage is not available from the reported slice counts.&#x20;

Eingefügter Text.txt



### Remaining todos

Immediate review and acceptance

- Correct the shared-contract wording for F-3 precedence.
- Repair E4 so it proves genuine identical-retry semantics.
- Review the minimal correction with ChatGPT.
- Obtain any required Human PO/ARB approval.
- Run the authorized correction tests and capture evidence.
- Keep Option E formally unaccepted until the required gates close.

Persistence and existing foundation

- Authorize and complete PostgreSQL Part 2 under the required isolation controls.
- Verify E1–E5 on PostgreSQL, state preservation, rollback, contract, invariants and race suites.
- Resolve F-3 and then the remaining F-2, F-4 and F-5 issues in the agreed sequence.
- Obtain the formal G3, Slice 0, Slice 1a, Option E and Slice 1b verdicts as applicable.
- Complete the authorized project-state and ticket-status synchronization.

Remaining Membership implementation

- Finalize Slice 2 decisions and progress Slices 2, 3 and 8.
- Complete C-2, C-3 and C-4.
- Implement and verify integration work in Slices 9–12.
- Finalize Slice 13 migration/normalization, including legacy placeholder handling and dry-run review.
- Complete Slice 14 UI.
- Complete Slice 15 regression, security and release acceptance.

Membership theory and research

- Maintain traceability from corpus evidence to requirements, invariants, contracts, tests and approved decisions.
- Use formal logic, boundary analysis and counterexamples to identify gaps and contradictions.
- Use machine learning for exploratory clustering and contradiction hypotheses where useful, validating every finding against original evidence.
- Keep business calendar validity, historical plausibility and backdating/scheduling decisions separate from technical persistence limits.

The next step is a focused correction proposal, not another architecture redesign. Preserve the approved scope, resolve the two review findings, and then proceed through PostgreSQL verification and formal acceptance.&#x20;