# PBDIGIT-283 R6 positive evidence (G-RUN-POS), 2026-10-14

**Authorisation:** G-RUN-POS **stage 1, then stage 2** (each separately); `migrate:fresh` on `nrna_test` explicitly permitted by the Principal (2026-10-14). Stage 3 (Feature, Architecture) is **not** authorised yet.

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
