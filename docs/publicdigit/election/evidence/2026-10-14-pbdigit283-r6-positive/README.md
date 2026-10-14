# PBDIGIT-283 R6 positive evidence (G-RUN-POS), 2026-10-14

**Authorisation:** G-RUN-POS **stage 1, then stage 2** (each separately); `migrate:fresh` on `nrna_test` explicitly permitted by the Principal (2026-10-14). Stage 3 (Feature, Architecture) authorised and run 2026-10-14.

**Pre-run checks (all passed):**
- repository root, HEAD `812538b62`;
- no `bootstrap/cache/config.php`; `APP_CONFIG_CACHE` unset;
- the shell exports none of `DATABASE_URL`, `DB_URL`, `DB_HOST`, `DB_DATABASE`, `DB_CONNECTION`, `DB_AUDIT_DATABASE`, `DB_PGSQL_GEO_DATABASE` (checked set or unset only);
- `phpunit.xml`, `tests/bootstrap-test-database.php`, `tests/CreatesApplication.php`, `tests/TestCase.php`, `tests/Support/TestDatabaseSafety.php`, `tests/Support/DatabaseTargetRules.php`, `config/database.php` unchanged since commit;
- `phpunit.xml` pins `pgsql`, `pgsql_audit`, `pgsql_geo` to `127.0.0.1:5432/nrna_test`, `DATABASE_URL` empty.

| Stage | File | Command | Outcome |
|---|---|---|---|
| 1 | `stage-1.txt` | `vendor/bin/phpunit --configuration phpunit.xml tests/Feature/AuditEventTransactionIsolationTest.php` | **OK:** 3 tests, 6 assertions, exit 0, 9.3 s (3 deprecations, the pre-existing PHP 8.5 notice in Laravel's `config/database.php:61`). **Accepted by the Principal 2026-10-14.** |
| 2 | `stage-2-unit.txt` | `vendor/bin/phpunit --configuration phpunit.xml --testsuite Unit` (at `b6b7d67fa`) | **Exit 2, 6 m 41 s:** 4,352 tests, 453 errors, 93 failures, 1,176 risky, 12 incomplete, 5 skipped. **No PBDIGIT-283 refusal, no `TestDatabaseSafety` reset refusal, no dead-socket or "not configured" error.** None of the 190 failing test classes is among the 42 test files changed since `772d06a5d`. **Accepted by the Principal 2026-10-14** as evidence that PBDIGIT-283 neither refuses nor misdirects the Unit suite. It is not evidence that the suite is green. |
| 3a | `stage-3-feature.txt` | `vendor/bin/phpunit --configuration phpunit.xml --testsuite Feature` (at `fd573dd40`) | **INCOMPLETE, exit 255:** PHP fatal "Allowed memory size of 134217728 bytes exhausted" at **2,928 / 3,482 (84%)**, while booting the application for `tests/Feature/Trust/VoterVerificationEmitsEventsTest.php` (route registration). The PHP CLI `memory_limit` is the default 128M and `phpunit.xml` sets none. No summary was printed. **0 PBDIGIT-283 refusals, 0 reset refusals, 0 dead-socket / "not configured" errors** in the 84% that ran. ~554 tests didn't run. Superseded by 3a-rerun (kept as the record of the incomplete run). |
| 3a-rerun | `stage-3-feature-rerun.txt` | `php -d memory_limit=1G vendor/bin/phpunit --configuration phpunit.xml --testsuite Feature` (at `fd573dd40`; only the memory limit changed, authorised by the Principal) | **Complete, 3,482 / 3,482, exit 2, 9 m 18 s, peak 144.5 MB:** 1,026 errors, 301 failures, 649 risky, 26 skipped, 6 incomplete. **0 PBDIGIT-283 refusals, 0 reset refusals, 0 dead-socket / "not configured" errors, no fatal error.** None of the 236 failing classes is among the test files changed since `772d06a5d`. Secrets scan clean. **Accepted by the Principal 2026-10-14.** |
| 3b | `stage-3-architecture.txt` | `vendor/bin/phpunit --configuration phpunit.xml --testsuite Architecture` | **Exit 1, 20 s:** 246 tests, 5 failures, 1 skipped. **0 PBDIGIT-283 refusals.** `LaravelBootIsGuardedTest`, `NoReachIntoAuditOrGeoTest` and `DatabaseResetIsGuardedTest` **passed**. The 5 failures are unrelated: lifecycle voting-window state (2), code-creation redirect (1), a hard-coded denial reason in `ElectionController` (1), and `MembershipDecisionCreationTest` expecting `app/Http/Middleware/VoterSlugStep.php`, which **the Principal's uncommitted working-tree deletion** removed (1). **Accepted by the Principal 2026-10-14.** |

## What stage 1 shows
- **R3 (pre-provider check) let the pinned configuration through.** Every connection was on an approved database, so the tests started.
- **`TestDatabaseSafety::assertResettable`** checked the **actual** `current_database()` of the default connection before `RefreshDatabase` and allowed it. It must be in `nrna_test` / `pbdigit274_isolated_test`; `phpunit.xml` pins `nrna_test`.
- **`RefreshDatabase` ran `migrate:fresh` on the default connection** (`nrna_test`) and the migrations succeeded.
- **`pgsql_audit` is reachable** (`select 1`) and isolated from the default connection; a real audit-constraint violation didn't abort the business transaction.

## Limits
- **Only one test file.** It doesn't show that the full suites run without refusals or PBDIGIT-283 regressions (stages 2–3).
- **No query printed `current_database()` for `pgsql_audit`.** Its target follows from the R2 pin (`nrna_test`) and the R3 confinement to the approved set.

## Stage 2: triage summary (unrelated to PBDIGIT-283 as far as the output shows)
Problems grouped by the first line of the message (top groups):
- **Risky (1,176):** "Test code or tested code removed error handlers other than its own" (1,141), "did not remove its own error handlers" (33). This is the PHPUnit 11 / Laravel error-handler reporting. **Whether it predates PBDIGIT-283 isn't established;** there's no earlier full-suite baseline.
- **Schema or test-data drift:**
  - `column "is_voter" of relation "users" does not exist` (29);
  - MySQL-only `foreign_key_checks` on PostgreSQL (31);
  - NOT NULL on `organisation_id` / `vote_hash` (~38);
  - invalid UUID literals (13);
  - foreign-key violations (5).
- **Accumulated data:** `UniqueConstraintViolationException` (55). Consistent with `tests/TestCase.php` skipping transaction isolation on PostgreSQL, so `migrate:fresh` runs once per process and data persists between tests.
- **Code and test drift:** missing classes (68), constructor and type changes (`TypeError`, `ArgumentCountError`, ~110), removed methods (`Election::canTransitionTo`, 9), a missing Fortify interface (9), deliberate "pending" RED tests (6).
- **Assertion failures (93),** spread across modules.

**Limits:**
- The classification rests on message text and file overlap, not root-cause analysis.
- The failures are listed for separate triage and **not fixed in PBDIGIT-283.**
- **Secrets check before commit:** the tracked `testing` password doesn't occur (silent comparison). The 89 lines mentioning "password" are SQL column lists, Faker test rows (factory hashes, `example.com`) and two test names; no credential.
- **Stage 3 secrets check:** the tracked `testing` password doesn't occur in either log (silent comparison).

## Stage 3a-rerun: triage summary (unrelated to PBDIGIT-283 as far as the output shows)
- **Memory:** the peak was 144.5 MB, so the default PHP CLI limit (128M) is too small for the Feature suite in one process. `phpunit.xml` sets no `memory_limit`.
- **Risky:** error-handler notices (577 + 68), as in stage 2; baseline unknown.
- **Schema / data drift:**
  - undefined column (236);
  - NOT NULL violations (169);
  - invalid text, e.g. UUID (90);
  - undefined object, e.g. MySQL-only `foreign_key_checks` (68);
  - check violations (44).
- **Accumulated data:** unique violations (103).
- **Code drift:** `HasMany::attach()` called on a has-many relation (103); null property access (16).
- **HTTP expectation drift:** 422/201 expected, 302 received (40).
- **Assertion failures:** 301 in total, spread across modules.

**Limit:** the classification rests on message text and file overlap, not root-cause analysis. Listed for separate triage; **not fixed in PBDIGIT-283.**
