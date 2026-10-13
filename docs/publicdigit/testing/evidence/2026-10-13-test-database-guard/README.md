# Incident and fix: a test run wiped the development database `publicdigit`, 2026-10-13

**Story:** `PBDIGIT-283`. **Authority:** Human PO: "I need to find the cause and make sure that it does not happen again" (no restore wanted).

## What happened (root cause, verified)
- **The trigger:** during `PBDIGIT-274`, Claude ran a "database-free" regression from the main checkout:
  ```
  vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Services/Voting …
  ```
- **The test that reset the database:** the folder contains the older `tests/Unit/Services/Voting/DeviceFingerprintServiceTest.php`, which extends **`Tests\TestCase`**. That class uses `RefreshDatabase`, and **on PostgreSQL it has no per-test transaction:** `beginDatabaseTransaction()` returns early. So `RefreshDatabase` runs **`migrate:fresh`, which drops every table.**
- **Why it hit `publicdigit`:** `--no-configuration` skipped `phpunit.xml` and its bootstrap, which pin tests to `nrna_test`. Laravel therefore used the main `.env` database, **`publicdigit`** (the development database the local server uses; there is no database named `nrna` on this server).
- **Two runs did it:**
  - S6 (`…/2026-10-13-pbdigit274-s6-duration-readers/05-database-free-regression.out`, finished 01:20:40);
  - S9c (`…/2026-10-13-pbdigit274-s9c-log-inventory/06-database-free.out`, finished 02:05:08).
- **Proof of timing:** the platform organisation, which the organisations migration inserts, was re-created at **02:04:56** local time, during the second run.
- **The mistake:** Claude had checked the base class of the test files it wrote itself, not every file in the folder.
- **A contributing factor:** `tests/TestCase.php` stated *"ZERO data corruption possible, even if wrong database is targeted"*. On PostgreSQL that was false.
- **Ruled out:**
  - the regression runs (isolated bootstrap; every connection pinned to `pbdigit274_isolated_test`; the role is `pg_hba`-confined to that database);
  - the runs on the isolated database.

## The fix
- **`tests/Support/TestDatabaseSafety.php`** (new, pure). A test may reset only `nrna_test` (`phpunit.xml`, `phpunit-pgsql.xml`) or `pbdigit274_isolated_test` (the isolated runs), on PostgreSQL. Anything else is refused with *"Refusing to reset database "…" … Nothing was changed. Run tests through phpunit.xml … never with --no-configuration …"*.
- **`tests/TestCase.php::setUpTraits()`** reads the **actual** database (`current_database()`) and calls the check **before** `RefreshDatabase`, `DatabaseMigrations` or `DatabaseTruncation` runs.
  - **Why it hooks `setUpTraits()`, not `refreshDatabase()`:** 471 test classes repeat `use RefreshDatabase;`, which would put the trait's own method back on the subclass and skip an override. No test redefines `setUpTraits()`.
- **The false comment** in `TestCase::setUp()` was replaced with an accurate warning.
- **`tests/Architecture/DatabaseResetIsGuardedTest.php`:**
  - every Laravel test class with a reset trait must inherit `Tests\TestCase` or `IsolatedDatabaseTestCase`; it checks more than 100 classes;
  - plain-PHPUnit classes that carry the trait are inert, because Laravel never runs it for them. They must not call a reset themselves, and the two known ones (`IpEvidenceDeterminismTest`, `IpEvidenceTopologyEquivalenceTest`) are pinned by name, so a new one is noticed.

## Runs
| # | File | Result |
|---|---|---|
| 01 | `01-unit-red.out` | `TestDatabaseSafetyTest` before the fix: **RED** (11 errors, class missing) |
| 02 | `02-e2e-red.out` | **the exact command that wiped `publicdigit`**, with only `DeviceFingerprintServiceTest`, aimed by environment variables at an empty **scratch SQLite file**. Before that, a read-only check confirmed the override hit that file. **RED:** the file got 5 tables (106,496 bytes) before a PostgreSQL-only migration stopped it, so the wipe path runs |
| 03 | `03-unit-green.out` | `TestDatabaseSafetyTest`: **11 / 22 GREEN** |
| 04 | `04-e2e-green.out` | the same command against a fresh, empty scratch file: **refused** (`Refusing to reset database …`); the file stayed **0 bytes** |
| 05 | `05-allowed-isolated.out` | the same test through `phpunit.pbdigit274.xml` on `pbdigit274_isolated_test`: **6 / 15 pass**; the guard allows it |
| 06a | `06a-architecture-first-run.out` | the architecture test's first run flagged the two plain-PHPUnit classes; found to be inert; the rule was refined |
| 06b | `06b-architecture.out` | `DatabaseResetIsGuardedTest`: **OK (1 test, 5 assertions)** |
| 07 | `07-isolated-all.out` | the whole isolated PBDIGIT-274 suite: **94 / 490** |

## Practice from now on (Claude)
- **Database tests run only through `phpunit.xml` or `phpunit.pbdigit274.xml`.**
- **`--no-configuration` only for files individually verified** to extend plain `PHPUnit\Framework\TestCase`, or the S1–S9 `BaseTestCase` tests that point `pgsql` at a dead socket. Never for a folder.
- **The guard is the safety net, not the plan.**

## Not covered
- **Production code or `artisan` commands** run against a real database. The guard protects test runs only.
- **The existing data in `publicdigit`:** the PO decided no restore.
