# Human ARB Decision Record and Conditional PBDIGIT-266 Execution

## Role

Act as senior DDD architect, requirements engineer, principal reviewer, and security-testing lead for PublicDigit Membership.

The Human ARB has explicitly accepted the following decisions. Treat them as authoritative constraints for this task.

## 1. Accepted Human ARB decisions

**ARB-MEM-01 — Correction before cutover**
Verified correction/annulment capability is required before Membership live writes and cutover. Reflect this as a mandatory architecture gate. Do not implement correction/annulment under this commission.

**ARB-SEC-01 — PBDIGIT-266**
Security tests are authorised only after isolated test-database readiness is confirmed. The scope is investigation and tests only. No application fixes, route changes, middleware changes, schema changes, or configuration changes are authorised.

**ARB-DEC-01 — Decision identifiers**
Preserve Slice 1a `D-1`, which identifies the adopted FactId uniqueness invariant (`INV-FID`). Propose `S2-D1` through `S2-D6` for the Slice 2 decisions. This naming proposal is not approved until the authoritative decision record says so. Do not silently rename ratified decisions.

**ARB-MEM-02 — Slice 1b**
Slice 1b remains conditionally accepted pending evidence and a formal acceptance act. Do not describe it as formally accepted.

**ARB-TEST-01 — Kernel test gaps**
TG-1 through TG-5 must be handled as a separate, explicitly authorised test-only work item. Do not execute those tests under this commission.

## 2. First: re-establish the current state

Before acting, inspect:

- Current repository root, branch, HEAD, working-tree status, and relevant commits.
- Active Claude sessions, concurrent work, and file ownership.
- The current PBDIGIT-266 test plan and backlog item.
- Current database configuration and any existing evidence of test-database isolation.
- The actual state of the shared `nrna_test` database and any ongoing test/database activity, using non-destructive inspection only.

Preserve all pre-existing, staged, unstaged, untracked, and concurrent-session work. Do not infer that the earlier reported repository state is still current.

## 3. Record the decisions safely

Record the five accepted decisions in the appropriate authoritative project decision/context records, following existing repository conventions.

- Preserve adopted decisions and their original identifiers.
- Mark `S2-D1`–`S2-D6` as a proposal pending formal approval.
- Record correction-before-cutover as a mandatory gate in the Membership dependency roadmap.
- Preserve the conditional status of Slice 1b.
- Keep TG-1 through TG-5 separate and unexecuted.

Do not overwrite or rewrite historical records to make them appear as if the decisions had been made earlier. Avoid unrelated documentation changes.

If a concurrent session owns a relevant file, stop and report the conflict rather than editing it.

## 4. Verify the PBDIGIT-266 database safety gate

The existing shared `nrna_test` database must not be disrupted.

Do not create, drop, reset, migrate, or modify any database merely to make the tests runnable under this commission.

Proceed with PBDIGIT-266 test execution only if you can establish that:

1. The tests target a dedicated isolated test database, not a development or shared database.
2. The effective database connection and target are unambiguous.
3. The database is not being used or reset by a concurrent session.
4. The test process cannot fall back to the development or shared database.
5. External side effects are controlled, including mail.
6. The existing tests and test plan can be executed within the authorised test-only scope.

If any condition is uncertain or false, **stop before running tests**. Report the missing evidence and the exact prerequisite needed. Do not provision an isolated database without separate authorisation.

## 5. Execute only the authorised PBDIGIT-266 tests if the gate passes

If and only if the safety gate is demonstrably satisfied, run the authorised PBDIGIT-266 investigation tests.

Prioritise requirements grounded in existing rules and route-health checks. For policy-dependent cases, distinguish characterisation of current behaviour from verification of intended policy. Do not invent answers to unresolved policy questions P1–P5.

For each test, report:

- Exact test name and result.
- The grounded invariant or unresolved policy question.
- HTTP result and resulting persisted state.
- Tenant/session context and side effects.
- Whether the result confirms a defect, refutes a hypothesis, identifies a route-health defect, or remains inconclusive.
- Relevant source locations and test evidence.

A 500 response is not proof of secure denial. Static suspicions are not confirmed vulnerabilities until supported by adequate evidence.

Do not fix any finding. Create no application changes. Report confirmed findings as candidates for separately authorised remediation stories.

## 6. Mandatory exclusions

Under this commission, do not:

- Implement correction/annulment.
- Perform Membership cutover or enable live Membership writes.
- Fix PBDIGIT-266 findings.
- Execute TG-1 through TG-5.
- Execute PBDIGIT-264 or PBDIGIT-265 work.
- Change routes, middleware, schema, runtime code, or configuration.
- Run migrations or destructive database operations.
- Commit, push, or overwrite another session's work.

## 7. Required final report

Return:

1. The recorded Human ARB decisions and exact files changed.
2. Repository state and concurrent-work conflicts.
3. Database-isolation evidence and a clear PASS/FAIL/UNKNOWN gate result.
4. PBDIGIT-266 tests executed, if authorised by the verified safety gate; otherwise, state that no tests were run and why.
5. Findings classified as confirmed, refuted, policy-dependent, route-health defect, or inconclusive.
6. Confirmation that no fixes were made.
7. Updated Membership dependencies and TODOs, including the mandatory correction-before-cutover gate.
8. Any remaining approval required.

Do not claim success where evidence is missing. Stop after reporting and wait for further authorisation.
