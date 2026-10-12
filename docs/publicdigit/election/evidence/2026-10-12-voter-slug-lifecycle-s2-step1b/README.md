# PBDIGIT-274, S2 step 1b (database-free): review corrections to the isolated-database guard, evidence, 2026-10-12

**Authority:** Human PO, 2026-10-12. Authorised: S2 step 1b only, i.e. the database-free safety corrections from the independent read-only review of step 1 (H1, M1, M2, L1, L2/L3, and L4 where executable).

Not authorised:
- any database connection or provisioning;
- setting or inventing `PBDIGIT274_*` variables;
- migrations or database tests;
- production code or configuration;
- staging or commits.

**Runs in this step.** Only the database-free guard suite (`--no-configuration`, so `phpunit.xml` and `nrna_test` were never loaded) and the two permitted refusal checks of the dedicated configuration. **No database connection was made.** `env.txt` records 0 `PBDIGIT274_*` variables in the shell.

| Step | Files | Result | Status |
|---|---|---|---|
| RED | `01-red` | exit 2: 69 tests, 30 errors (new methods missing), 15 failures. The 24 passing tests cover behaviour 1b does not change | executed, newly in this step |
| GREEN | `02-green` | **69 tests, 167 assertions, exit 0**, first run | executed, newly in this step |
| Config refuses | `03-config-refuses-unsafe.out` | `vendor/bin/phpunit -c phpunit.pbdigit274.xml`: no name → `[missing]`, exit 1; `PBDIGIT274_TEST_DB=nrna_test` → `[forbidden]`, exit 1 | executed, newly in this step |
| Credentials | `credential-check.txt` | no database credential, no approved name committed (`APPROVED_NAME = ''`) | checked |
| S1 regression | — | **not re-run in this step** (its command loads `phpunit.xml`; not permitted here). S1 files are unchanged since `4d83dffc0`. The last recorded result is in the step 1 evidence: 72 / 424 | not executed |

## What changed (review finding → correction → test)
- **H1:** `DatabaseIdentityGuard::APPROVED_NAME`, **empty until G4**. `approvedName()` refuses every name as `not_approved` until it is set, and afterwards anything but an exact match. Tests:
  - `test_no_database_is_approved_before_g4`;
  - `…_while_nothing_is_approved` (incl. `publicdigit`);
  - `test_only_the_exact_approved_name_passes`;
  - `test_an_approved_name_cannot_unlock_a_forbidden_database`;
  - script cases `well formed, not approved` and `repository default name`.
- **M1:** `null` / `true` / `false` / `empty`, any case → `reserved`. Tests: unit (9 variants), script (`null`, `TRUE`).
- **M2:** `APP_CONFIG_CACHE` set (even empty) → `config_cached`. Tests: unit (path and empty) and the script case `config cache override`.
- **L1:** `assertConnectionsConfined()` now checks every configured connection: no URL override; no PostgreSQL connection to another database; no connection of any driver naming a forbidden database. `withoutUnconfinedPostgresConnections()` drops unconfined PostgreSQL connections (the hard-coded `testing` → `nrna_test`) first, and `IsolatedDatabaseTestCase::refreshDatabase()` applies it before the check. Five new unit tests.
- **L4:**
  - the environment-writing loop is now `applyEnvironment()`, tested for `$_SERVER`, `$_ENV` and `getenv`, including empty values;
  - `config_cached` is now tested at script level.
  - **By design:** while nothing is approved, the bootstrap refuses `not_approved` before connecting, so the script-level `connection_failed` case is no longer reachable. It stays unit-tested with a stand-in (`test_a_failed_lookup_is_refused_without_leaking_its_message`).
- **Bootstrap order** is now: well-formed name → credentials → config cache → approved name → identity. So credential and cache problems are reported even before G4.

## L2: exact S1 regression commands of step 1 (recorded, not re-run)
The step 1 files `07a`, `07b` and `07c` came from these commands, run from the repository root with the default `phpunit.xml`. The three S1 test files are database-free: they extend `Illuminate\Foundation\Testing\TestCase` and use `DB::listen`.
```bash
vendor/bin/phpunit tests/Unit/Services/Voting/VoterSlugLifetimeTest.php      # 07a: 32 tests, 70 assertions
vendor/bin/phpunit tests/Unit/Services/Voting/VoterSlugRouteLookupTest.php   # 07b: 6 tests, 15 assertions
vendor/bin/phpunit tests/Unit/Http/Middleware/VerifyVoterSlugTest.php        # 07c: 34 tests, 339 assertions
```

## L3: wording correction for the step 1 evidence (that folder is left unchanged to keep its checksums)
The step 1 README lists "commits" as not authorised. That was true when the work was done. The commit (`2e9017487`) was made later, at the Human PO's separate request ("commit S2 step 1 separately"). The plan's status block now distinguishes the S2 step 1 / 1b authorisations from the still-missing authorisation for the database part of S2.

## Not verified (needs G4, then G5 evidence)
- The real `current_database()` lookup.
- A real wrong-database abort.
- The in-test check inside a booted app, including `withoutUnconfinedPostgresConnections` on the real config.
- The environment pinning seen by Laravel.
