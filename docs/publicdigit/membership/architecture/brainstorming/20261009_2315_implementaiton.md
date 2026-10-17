# Principal review of the latest Membership decision-readiness report

The attachment shows meaningful progress: Claude has consolidated the roadmap, investigated the correction problem against the in-memory kernel, and strengthened the PBDIGIT-266 test plan. It also identifies a critical safety condition around Laravel's cached configuration and destructive test-database setup.

My recommendation is accept the research report with conditions, but do not authorise test execution yet. The report itself says the work was not committed and no tests or database operations were performed under that commission. Its source findings are reported by Claude; they still need verification at execution time.

## 1. What changes the architecture

A. Correction-before-cutover is a real architectural gate

The kernel probes reportedly show that the current Admit/End model cannot repair retroactive date errors or incorrect admission facts. Ending a membership and readmitting it may create a different history rather than correct the original history.

Your accepted decision is therefore justified: correction and annulment must be implemented and verified before live writes and cutover. This includes migrated data—not just new UI entries.

B. Test-database safety is a hard execution gate

The report warns that cached Laravel configuration could cause tests to target the wrong database and that `migrate:fresh` could destroy data. The cache file was reported absent at the time of the investigation, but that is not sufficient proof of safety for a later run.

Claude must establish the effective database target and prevent fallback to the development database immediately before execution. If isolation cannot be demonstrated, stop without running tests.

C. PBDIGIT-266 has stronger security concerns than the earlier review suggested

The report identifies possible cross-tenant application-review exposure, debug routes disclosing election details, excessive pending-election visibility, and Finance tenant-context concerns. It also identifies route failures that must not be mistaken for secure denial.

These remain findings to validate. The next investigation must assert persisted state, tenant context, response data, and side effects—not just HTTP status codes.

D. Decision identifiers need a citation convention, not a repository-wide renaming exercise

The report says local `D-1` identifiers occur in many documents and have three meanings in the relevant Membership/context corpus. Preserve the ratified `D-1 = INV-FID`; propose `S2-D1`–`S2-D6` and qualify cross-document references. Do not mass-rename unrelated document-local decisions.

## 2. Prompt instructions for Claude — next step

The best next step is conditional execution of the already-authorised PBDIGIT-266 test-only investigation, combined with recording your five Human ARB decisions. Do not let the work drift into fixes or Membership implementation.

# Next Commission: Record Human ARB Decisions and Conditionally Execute PBDIGIT-266 Tests

## Role

Act as senior DDD architect, principal reviewer, security-testing lead, requirements engineer, and formal-methods researcher for PublicDigit Membership.

Use the latest decision-readiness report as research evidence. Reverify the live repository before relying on any reported state. Preserve all concurrent work.

## 1. Authoritative Human ARB decisions

Record these decisions in the appropriate authoritative project records, following repository conventions:

1. **Correction before cutover:** verified correction/annulment capability is mandatory before Membership live writes and cutover. This is an architectural gate, not implementation authorisation.
2. **PBDIGIT-266:** test-only investigation is authorised, conditional on verified test-database isolation. No fixes are authorised.
3. **Decision IDs:** preserve Slice 1a `D-1 = INV-FID`. Propose `S2-D1`–`S2-D6` for the Slice 2 decisions. The new naming convention remains a proposal until formally approved. Use qualified document-local identifiers for cross-document citations.
4. **Slice 1b:** remains conditionally accepted pending evidence and a formal acceptance act.
5. **TG-1–TG-5:** separate, explicitly authorised test-only work item; not included in this commission.

Do not silently change adopted decisions, rewrite historical records, or mark a proposal as ratified.

## 2. Re-establish current repository state

Before any action, verify:

- repository root, branch, HEAD, and working-tree status;
- the current decision-readiness document, PBDIGIT-266 plan, and backlog item;
- active sessions and concurrent work;
- ownership of uncommitted test factories and fixtures;
- current database configuration and cached configuration state.

The previous report says other sessions have uncommitted work and that `bootstrap/cache/config.php` was absent at the time. Neither fact proves the current state.

Preserve all pre-existing staged, unstaged, untracked, and concurrent-session work. Do not reset, clean, stash, overwrite, or commit other sessions' changes.

## 3. Record the decisions and correct the roadmap

Update only the appropriate decision/context records and the consolidated Membership roadmap.

Ensure the dependency order explicitly requires:

1. Resolve relevant authority and correction-policy decisions.
2. Approve the Slice 2 contract.
3. Implement and verify the correction/annulment capability.
4. Complete cutover machinery and migration dry runs/parity.
5. Enable live HTTP/UI integration only after the required security and readiness gates.

Do not implement correction, annulment, or Slice 2 under this commission.

Keep PBDIGIT-264 and PBDIGIT-266 as independent workstreams. Do not run TG-1–TG-5. Do not claim formal Slice 1b acceptance.

If a concurrent session owns a relevant file, coordinate or stop rather than editing it.

## 4. Test-database safety gate — mandatory

Before executing any test, establish that:

- the effective test connection targets an existing, dedicated isolated test database;
- the database name and connection are unambiguous;
- no development or shared database can be selected through cached configuration, fallback settings, or environment mismatch;
- no concurrent session is using or resetting that database;
- the test process cannot execute `migrate:fresh` against development or shared data;
- mail and notification side effects are faked;
- required fixtures can be used without overwriting another session's work.

Do not create, drop, reset, or migrate any database to satisfy this gate. Database provisioning requires separate authorisation.

If any prerequisite is unproven, record the gate as **FAIL** or **UNKNOWN**, do not run tests, and report exactly what is missing. An absent Laravel configuration-cache file alone is insufficient proof.

## 5. Execute PBDIGIT-266 only if the gate passes

If all safety prerequisites are proven, execute only the authorised PBDIGIT-266 test investigation.

Prioritise tests grounded in existing requirements and route-health checks. Keep policy-dependent cases clearly labelled as characterisation until the relevant policy is decided.

Use the corrected test plan from the latest review. In particular:

- distinguish UUID and slug routes and exercise their actual route behaviour;
- test organisation membership and cross-tenant isolation;
- inspect both response data and persisted state;
- assert session tenant context even when a request fails;
- test the tenant-header-to-session-to-Finance path as a controlled sequence;
- check pending-election disclosure, including soft-deleted records;
- test both debug routes, `debug-tenant` and `auth-test`;
- control notifications using the mechanism actually used by the application;
- isolate known fatal test cases in a separate process when justified;
- coordinate any shared test fixtures with their current owner.

For every test, report the exact name, result, relevant requirement, persisted-state assertions, tenant/session assertions, and side effects.

Classify each finding as:

- confirmed defect;
- refuted hypothesis;
- policy-dependent;
- route-health defect;
- inconclusive.

A 500 error does not demonstrate secure denial. Static evidence alone does not establish exploitability.

## 6. Keep unresolved policy questions explicit

P1–P5 remain unresolved unless an authoritative decision record proves otherwise:

- P1: who may see the platform-wide pending-election list?
- P2: who may review committee applications, and is self-approval forbidden?
- P3: who may add or remove committee members?
- P4: who may record Finance entries?
- P5: under what conditions may `X-Tenant-Id` and debug routes be used?

Do not adopt the proposed defaults on the PO's behalf. Run grounded tests where safe; record policy-dependent results without pretending they settle intended behaviour. Prepare the remaining decision requests for the appropriate owners.

## 7. Strict exclusions

No application fixes, route or middleware changes, schema changes, configuration changes, or unrelated implementation work.

Do not execute PBDIGIT-264, PBDIGIT-265, TG-1–TG-5, or correction/annulment implementation.

Do not perform Membership cutover, enable live Membership writes, commit, or push.

## 8. Required final report

Report:

1. The five Human ARB decisions recorded and the exact files changed.
2. Repository state, concurrent work, and any conflicts.
3. The database safety gate with evidence and PASS/FAIL/UNKNOWN.
4. PBDIGIT-266 tests executed, or why execution was blocked.
5. Findings with precise evidence and classifications.
6. Confirmation that no fixes or unrelated implementation changes were made.
7. The consolidated Membership roadmap and remaining dependencies.
8. P1–P5 decisions still required, Slice 1b acceptance evidence, and the separate TG-1–TG-5 work item.

Be explicit about what was actually verified versus merely reported. Stop after reporting and wait for further authorisation.

## 3. What remains for you as Human ARB

You have already decided the five immediate architectural gates. You do not need to decide P1–P5 before Claude can start checking the database-safety prerequisites or run grounded tests if the gate passes.

You will still need to decide P1–P5, with input from the relevant Product Owner, Governance, Finance, and Security owners. The next major architecture decision is whether the proposed correction model satisfies the adopted Membership semantics before the team implements it.

My recommendation: use this prompt next. It converts your existing authorisation into a controlled next step while protecting the shared database and preserving the rule that a broken route is never evidence of correct security.