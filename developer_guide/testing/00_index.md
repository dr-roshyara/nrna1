# Test-database safety: developer guide index

How tests are kept away from non-test databases. **Background:** on 2026-10-13 a `--no-configuration` run skipped `phpunit.xml`'s pinning and `migrate:fresh` wiped the development database. Plan: `docs/plans/20261014-1347-pbdigit283-test-database-safety-remediation-plan.md`.

## The map: which protection covers which test

| Test kind | Protection | Where |
|---|---|---|
| Extends `Tests\TestCase` (resets the database) | the actual `current_database()` must be in `TestDatabaseSafety::RESETTABLE` before any reset (after boot) | `tests/TestCase.php`, `tests/Support/TestDatabaseSafety.php` |
| PBDIGIT-274 isolated suite | identity guard before boot and before refresh; only `pbdigit274_isolated_test` | `tests/bootstrap-isolated-database.php`, `DatabaseIdentityGuard` |
| Boots Laravel but must never touch a database | the `DatabaseFreeTest` marker: every target at a dead socket **before** boot (guide 01) | `tests/CreatesApplication.php`, `tests/Support/DatabaseFreeEnvironment.php` |
| Every test booting Laravel through `CreatesApplication` | the loaded configuration is checked before any provider is registered; unsafe → refused (guide 03) | `tests/CreatesApplication.php`, `TestDatabaseSafety::refuseUnsafeConfiguration` |
| Plain PHPUnit (no Laravel) | nothing to protect: no boot, no `.env` | — |
| Any run through `phpunit.xml` / `phpunit-pgsql.xml` | every PostgreSQL target (`DB_*`, `DB_AUDIT_*`, `DB_PGSQL_GEO_*`) pinned to `nrna_test`; `DATABASE_URL` set and empty (guide 02) | `tests/bootstrap-test-database.php`, both XML files |

**Key rules:**
- **Database-free runs use one command (C-1):** `vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php <path>`. Never `phpunit.pbdigit274.xml`, whose bootstrap opens a PDO connection.
- **Every test run is gated** (plan §4: G-RUN-DF, G-RUN-NEG, G-RUN-POS).
- **Rules live once:** `DatabaseTargetRules`, parameterised by each guard's approved set.

## Guides
| # | Guide | Story | Status |
|---|---|---|---|
| 01 | [Database-free tests: shared target rules and the `DatabaseFreeTest` marker](01_database_free_tests.md) | `PBDIGIT-283` R1 | accepted 2026-10-14; committed `164f74066` |
| 02 | [Default-suite pinning of every PostgreSQL target](02_default_suite_pinning.md) | `PBDIGIT-283` R2 | committed `6d394e6e1` |
| 03 | [The pre-provider hook](03_pre_provider_hook.md) | `PBDIGIT-283` R3 | committed `17167dec3` |
| 04 | [Every Laravel boot goes through `CreatesApplication` and has a cover](04_laravel_boot_coverage.md) | `PBDIGIT-283` R4 | accepted 2026-10-14; committed `b7138fafa` |
| 05 | [No test reset reaches the audit or geo database](05_no_reach_into_audit_or_geo.md) | `PBDIGIT-283` R5 | committed `ac66fd940` |
| 06 | [Negative evidence: the incident's shape is refused](06_negative_evidence.md) | `PBDIGIT-283` R6 (G-RUN-NEG) | prepared `eb18ccfa9`; run 1 aborted before the canary (merged `mariadb`); corrected `4ce631f39`; **run 2 evidence accepted 2026-10-14** |

## Open work
| Item | What |
|---|---|
| `PBDIGIT-283` R6 | negative and positive evidence |
| `PBDIGIT-290` | the committee-creation handler binding queries the database when resolved (found by R1) |
| `PBDIGIT-291` | an outbox adapter test passes on any database failure (found by R4) |
| `PBDIGIT-292` | **security:** a hard-coded `testing` connection with a tracked plaintext password (found by R6; Option A exception) |
| `PBDIGIT-293` | `DB_URL` (read by Laravel's merged `mariadb` entry) isn't neutralised by R1 or pinned by R2; R3 fails closed (found by R6 run 1) |
