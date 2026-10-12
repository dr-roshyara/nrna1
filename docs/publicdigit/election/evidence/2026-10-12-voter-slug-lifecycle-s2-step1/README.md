# PBDIGIT-274, S2 step 1 (database-free): the isolated-database identity guard, evidence, 2026-10-12

**Authority:** Human PO, 2026-10-12. Only the database-free first step of S2 was authorised:
- the isolated test configuration and the database-identity guard;
- tests for empty, forbidden and mismatched names.

Not authorised:
- any PostgreSQL connection;
- the migration;
- database tests;
- commits.

**Plan:** `docs/plans/20261011-2302-voter-slug-lifecycle-plan.md` section 11.0, S2-3 (gates G4 / G5).

Outputs and exit codes are copied unchanged. **No database connection was made and no database command was run.** Every PHPUnit run of the guard tests used `--no-configuration --bootstrap vendor/autoload.php`, so the default `phpunit.xml` and its `nrna_test` bootstrap were never loaded.

| Step | Files | Result | Status |
|---|---|---|---|
| RED | `01-red-guard` | exit 2: 42 tests, 33 errors (classes and script missing), 8 failures, 1 warning. **One test passed vacuously:** the source scan read a missing file as empty. It was tightened with `assertFileExists` | executed |
| RED rerun | `02-red-guard-rerun` | exit 2: 42 tests, 33 errors, 9 failures, all failing | executed |
| GREEN, first run | `03-green-guard` | exit 1: 41 passed, 1 failed. The "empty name" script case saw `missing`, because PHP's `proc_open` drops empty environment variables. The guard was right to refuse; the **test harness** was fixed to pass the environment through `env -i` | executed |
| GREEN | `04-green-guard` | **42 tests, 96 assertions, exit 0** | executed, passed |
| Config refuses | `05-config-refuses-unsafe.out` | `vendor/bin/phpunit -c phpunit.pbdigit274.xml` with no name → `[missing]`, exit 1; with `nrna_test` → `[forbidden]`, exit 1. Both stop inside the bootstrap, before Laravel and before any connection | executed |
| Discovery | `06-discovery-static-check.txt` | no default configuration (`phpunit.xml`, `phpunit-pgsql.xml`, `composer test` → `artisan test`) lists `tests/Isolated` | static check |
| S1 regression | `07a`, `07b`, `07c` | 32 + 6 + 34 = **72 tests, 70 + 15 + 339 = 424 assertions**, each exit 0 (unchanged; S1 files untouched) | executed, passed |
| Credentials | `credential-check.txt` | no database credential in any new file; only the test `APP_KEY` already committed in `phpunit.xml` | checked |

**Two kinds of "connection" test, stated precisely:**
- **Mismatch and a live match** are proved against a **test double** of `current_database()` (`DatabaseIdentityGuardTest`). The real PDO lookup (`pdoCurrentDatabase`) has **not** been exercised against PostgreSQL. That needs G4.
- **The connection-failure case** points the bootstrap at a **non-existent Unix-socket directory**. The connection attempt fails locally; no network connection is made and no PostgreSQL server is contacted. It proves the run aborts with `[connection_failed]` and never prints the password.

**Not verified until G4 (with G5 evidence):**
- the real `SELECT current_database()` lookup and the exact-match pass;
- a wrong-database abort against a real server;
- the second check in `IsolatedDatabaseTestCase::refreshDatabase()` inside a booted Laravel. That class has no tests yet; its logic is `DatabaseIdentityGuard::confirmBeforeRefresh`, which is unit-tested.
