# PBDIGIT-345 task 0 — multi-database test harness: evidence (2026-11-12)

**Raw output only; the Principal decides acceptance.**
- **Code under test:** working tree on `c8a584461` plus the task 0 changes (committed together with this folder).
- **Cluster:** `docker.io/library/postgres:15@sha256:7da5d534f1ffcdb2f69efa6e466a7a29ff89eefd611c7b50c4d69aa77fd4a822`, throwaway (PBDIGIT-324).
- **Credentials:** none in this folder; the script refuses to keep evidence containing its secret.

## TDD cycles (`cycles/`)

| Folder | Command | Result |
|---|---|---|
| `t0-red1` | `membership-pg.sh --testsuite PlatformPostgres` (provisioning test only) | **RED** exit 1: `PLATFORM_PG_DATABASES` not exported; `platform_pg_central` not reachable. The cluster guard passed (the harness worked; the capability was missing) |
| `t0-green1` | same, after the script creates the four databases | **GREEN** exit 0: OK (2 tests, 10 assertions) |
| `t0-red2` | same, with the connections test added | **RED** exit 2: 7 errors, `Tests\Support\PlatformPgIsolatedDatabases` not found; Laravel booted and the cluster was confirmed |
| `t0-green2` | same, after the support class | **GREEN** exit 0: Tests: 9, Assertions: 40, Deprecations: 1. |
| `t0-red3` | `vendor/bin/phpunit tests/Feature/Contexts/Platform` (**default** `phpunit.xml`) | **RED** (a regression of the normal suite): Tests: 9, Assertions: 1, Errors: 8, Failures: 1, Deprecations: 1. |
| `t0-green3` | same, after the skip-outside-isolation guard | **GREEN**: Tests: 9, Assertions: 0, Skipped: 9. |

## Final runs (after all changes)

| Folder | Result |
|---|---|
| `final-full-run` (both suites) | exit 0: Tests: 144, Assertions: 16179, Deprecations: 1. |
| `final-suite-MembershipPostgres` | exit 0: Tests: 135, Assertions: 16132, Deprecations: 1. **(slice 1: 135, as accepted)** |
| `final-suite-PlatformPostgres` | exit 0: Tests: 9, Assertions: 40, Deprecations: 1. |
| `architecture-full.txt` / `.failures` | Tests: 300, Assertions: 937, Failures: 4, Deprecations: 4, PHPUnit Deprecations: 5, Skipped: 1. **The 4 failure names are identical** to `docs/publicdigit/membership/evidence/2026-11-12-pbdigit343-slice1-acceptance/head/architecture-full.failures` |

- **`full-run`, `suite-*`:** the same runs made **before** the skip guard (kept, not overwritten): Tests: 144, Assertions: 16155, Deprecations: 1.
- **Assertion counts of the slice 1 suite vary between runs** (16 098 at slice 1 acceptance, 16 132 here), because its race tests loop until conditions are met. The test count (135) and the result (all pass) are the comparison.
- **The one deprecation** is pre-existing: `config/database.php:66`, PHP 8.5.
