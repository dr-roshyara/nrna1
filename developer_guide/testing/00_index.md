# Test-database safety: developer guide index

How tests are kept away from non-test databases. **Background:** on 2026-10-13 a `--no-configuration` run skipped `phpunit.xml`'s pinning and `migrate:fresh` wiped the development database. Plan: `docs/plans/20261014-1347-pbdigit283-test-database-safety-remediation-plan.md`.

## The map: which protection covers which test

| Test kind | Protection | Where |
|---|---|---|
| Extends `Tests\TestCase` (resets the database) | the actual `current_database()` must be in `TestDatabaseSafety::RESETTABLE` before any reset (after boot) | `tests/TestCase.php`, `tests/Support/TestDatabaseSafety.php` |
| PBDIGIT-274 isolated suite | identity guard before boot and before refresh; only `pbdigit274_isolated_test` | `tests/bootstrap-isolated-database.php`, `DatabaseIdentityGuard` |
| Boots Laravel but must never touch a database | the `DatabaseFreeTest` marker: every target at a dead socket **before** boot (guide 01) | `tests/CreatesApplication.php`, `tests/Support/DatabaseFreeEnvironment.php` |
| Plain PHPUnit (no Laravel) | nothing to protect: no boot, no `.env` | — |

**Key rules:**
- **Database-free runs use one command (C-1):** `vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php <path>`. Never `phpunit.pbdigit274.xml`, whose bootstrap opens a PDO connection.
- **Every test run is gated** (plan §4: G-RUN-DF, G-RUN-NEG, G-RUN-POS).
- **Rules live once:** `DatabaseTargetRules`, parameterised by each guard's approved set.

## Guides
| # | Guide | Story | Status |
|---|---|---|---|
| 01 | [Database-free tests: shared target rules and the `DatabaseFreeTest` marker](01_database_free_tests.md) | `PBDIGIT-283` R1 | accepted 2026-10-14; not yet committed |

## Open work
| Item | What |
|---|---|
| `PBDIGIT-283` R2–R6 | default-suite pinning of audit/geo/`DATABASE_URL`; the pre-provider hook; coverage and audit/geo architecture tests; negative and positive evidence |
| `PBDIGIT-290` | the committee-creation handler binding queries the database when resolved (found by R1) |
