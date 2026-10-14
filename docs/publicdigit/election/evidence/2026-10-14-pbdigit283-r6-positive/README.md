# PBDIGIT-283 R6 positive evidence (G-RUN-POS), 2026-10-14

**Authorisation:** G-RUN-POS **stage 1 only**; `migrate:fresh` on `nrna_test` explicitly permitted by the Principal (2026-10-14). Stages 2–3 (Unit, Feature, Architecture suites) are **not** authorised yet.

**Pre-run checks (all passed):**
- repository root, HEAD `812538b62`;
- no `bootstrap/cache/config.php`; `APP_CONFIG_CACHE` unset;
- the shell exports none of `DATABASE_URL`, `DB_URL`, `DB_HOST`, `DB_DATABASE`, `DB_CONNECTION`, `DB_AUDIT_DATABASE`, `DB_PGSQL_GEO_DATABASE` (checked set or unset only);
- `phpunit.xml`, `tests/bootstrap-test-database.php`, `tests/CreatesApplication.php`, `tests/TestCase.php`, `tests/Support/TestDatabaseSafety.php`, `tests/Support/DatabaseTargetRules.php`, `config/database.php` unchanged since commit;
- `phpunit.xml` pins `pgsql`, `pgsql_audit`, `pgsql_geo` to `127.0.0.1:5432/nrna_test`, `DATABASE_URL` empty.

| Stage | File | Command | Outcome |
|---|---|---|---|
| 1 | `stage-1.txt` | `vendor/bin/phpunit --configuration phpunit.xml tests/Feature/AuditEventTransactionIsolationTest.php` | **OK:** 3 tests, 6 assertions, exit 0, 9.3 s (3 deprecations, the pre-existing PHP 8.5 notice in Laravel's `config/database.php:61`). **Accepted by the Principal 2026-10-14.** |

## What stage 1 shows
- **R3 (pre-provider check) let the pinned configuration through.** Every connection was on an approved database, so the tests started.
- **`TestDatabaseSafety::assertResettable`** checked the **actual** `current_database()` of the default connection before `RefreshDatabase` and allowed it. It must be in `nrna_test` / `pbdigit274_isolated_test`; `phpunit.xml` pins `nrna_test`.
- **`RefreshDatabase` ran `migrate:fresh` on the default connection** (`nrna_test`) and the migrations succeeded.
- **`pgsql_audit` is reachable** (`select 1`) and isolated from the default connection; a real audit-constraint violation didn't abort the business transaction.

## Limits
- **Only one test file.** It doesn't show that the full suites run without refusals or PBDIGIT-283 regressions (stages 2–3).
- **No query printed `current_database()` for `pgsql_audit`.** Its target follows from the R2 pin (`nrna_test`) and the R3 confinement to the approved set.
