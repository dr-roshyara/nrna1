# 07: The multi-database harness for slice 2 (PBDIGIT-345, task 0)

**Story:** `PBDIGIT-345` · **Plan:** `docs/plans/20261112-1844-release-1-build-slice-2-platform-routing-and-isolation-plan.md` §6 task 0 (approved 2026-11-12) · **Design:** delta revision 3 (`docs/publicdigit/architecture/reviews/2026-11-12-platform-organisation-routing-isolation-tactical-design.md`), D-178, D-219 (4)
**Status:** implemented with TDD (RED → GREEN, two cycles plus one default-suite fix). Evidence: `docs/publicdigit/platform/evidence/2026-11-12-pbdigit345-task0/`.

## Purpose

Slice 2 must prove that one operation reaches **exactly one** organisation's database. That needs a test environment with **one central database and three organisation databases**, all of them throwaway and provably isolated.

This guide covers **only that test environment.** Platform routing itself (registry, `OrganisationContext`, connection factory) arrives in tasks 1 to 3 and **must not use this class.**

## Where it fits

Test infrastructure only. **No `app/` code, no `config/` file, no production configuration changed.** It extends the PBDIGIT-324 throwaway cluster (`developer_guide/membership/11_isolated_postgres_container.md`) and reuses the PBDIGIT-274 identity guard; it is not a second guard (rules live once).

| File | Role |
|---|---|
| `scripts/testing/membership-pg.sh` | after the cluster is ready and its `system_identifier` is captured, creates `platform_pg_central`, `platform_pg_org_a`, `platform_pg_org_b`, `platform_pg_org_c`; exports `PLATFORM_PG_DATABASES`; records the list in `run.txt` |
| `phpunit.membership-pg.xml` | a second test suite `PlatformPostgres` (`tests/Feature/Contexts/Platform`) beside `MembershipPostgres` |
| `tests/Support/PlatformPgIsolatedDatabases.php` | test-only named connections `platform_central`, `platform_org_a/b/c`; identity checks; `reset()`; `isIsolatedRun()` |
| `tests/Feature/Contexts/Platform/Harness/PlatformPgProvisioningTest.php` | plain PHPUnit + raw PDO: the four databases exist on the captured cluster |
| `tests/Feature/Contexts/Platform/Harness/PlatformPgConnectionsTest.php` | Laravel: own database per connection, isolation, reset, default untouched, refusals |

## Design decisions

- **One cluster, four extra databases** mirrors D-219 (4): one PostgreSQL cluster with a database per organisation, plus the central one.
- **Fixed names, in a fixed order.** `register()` accepts exactly `PLATFORM_PG_DATABASES = platform_pg_central,platform_pg_org_a,platform_pg_org_b,platform_pg_org_c`. Anything else, including `nrna_test`, is refused.
- **Every connection proves its identity:** `current_database()` must equal its name, and `system_identifier` must equal `MEMBERSHIP_PG_SYSTEM_ID`, captured at container start. **On any refusal nothing stays registered.**
- **Credentials come from `MEMBERSHIP_PG_DB_*`** (the script's per-run random password), never from `DB_*` or `.env`.
- **Laravel's default connection is untouched.** It stays `membership_pg_isolated`, the slice 1 database (delta C-1).
- **Reset is schema-level** (`DROP SCHEMA public CASCADE; CREATE SCHEMA public`) and re-checks each identity before dropping anything.
- **Skip outside, fail inside.** Outside an isolated run (no `MEMBERSHIP_PG_SYSTEM_ID`), the harness tests skip with a reason, so `php artisan test` / `phpunit.xml` is unaffected. **Inside an isolated run anything missing fails, never skips.**

## How it works

```php
// tests/Support/PlatformPgIsolatedDatabases.php (excerpt)
public const CONNECTIONS = [
    'platform_central' => 'platform_pg_central',
    'platform_org_a' => 'platform_pg_org_a',
    'platform_org_b' => 'platform_pg_org_b',
    'platform_org_c' => 'platform_pg_org_c',
];

public static function register(array $env): void
{
    // exact list, else IsolatedDatabaseRefused('platform_databases', …)
    // per connection: config([...]); verifyIdentity(name); verifySystemIdentifier(captured id)
    // on refusal: purge and unset every connection registered so far, then rethrow
}
```

## How to use it

```php
final class SomePlatformPostgresTest extends MembershipPostgresTestCase
{
    protected function setUp(): void
    {
        if (!PlatformPgIsolatedDatabases::isIsolatedRun(getenv())) {
            $this->markTestSkipped(PlatformPgIsolatedDatabases::SKIP_REASON);
        }
        parent::setUp();
        PlatformPgIsolatedDatabases::register(getenv());
        PlatformPgIsolatedDatabases::reset();   // every test starts from four empty databases
    }
}
```

**Run:**

```bash
MEMBERSHIP_PG_IMAGE=docker.io/library/postgres:15@sha256:7da5d534f1ffcdb2f69efa6e466a7a29ff89eefd611c7b50c4d69aa77fd4a822 \
  bash scripts/testing/membership-pg.sh --testsuite PlatformPostgres     # or no argument: both suites
```

## Testing (executed 2026-11-12)

| Run | Result |
|---|---|
| cycle 1 RED | 2 failures: `PLATFORM_PG_DATABASES` not exported; `platform_pg_central` not reachable (the cluster guard itself passed) |
| cycle 1 GREEN | OK, 2 tests |
| cycle 2 RED | 7 errors: `Tests\Support\PlatformPgIsolatedDatabases` not found (Laravel booted, cluster confirmed) |
| cycle 2 GREEN | OK, 9 tests, 40 assertions |
| default `phpunit.xml`, before the guard | 9 fail (a regression of the normal suite) |
| default `phpunit.xml`, after the guard | 9 skipped |
| final isolated run, both suites | OK, 144 tests: **MembershipPostgres 135 (slice 1 unchanged)** + PlatformPostgres 9; nothing skipped |
| `tests/Architecture` | 4 failures, **identical by name** to the slice 1 HEAD list (pre-existing) |

## Pitfalls

- **Never point these connections at a non-throwaway database.** The identity checks are the protection; don't weaken them for convenience.
- **Don't use `PlatformPgIsolatedDatabases` from production code** or as the model for routing. Real routing derives connections only from the registry (ISO-2, delta §3.1).
- **Don't open a transaction across two of these connections** and expect atomicity: there is none (C-3).
- The one deprecation in every run comes from `config/database.php:66` (`PDO::MYSQL_ATTR_SSL_CA`, PHP 8.5). It is pre-existing and unrelated.

**Traceability:** PBDIGIT-345 task 0 · PBDIGIT-324 (cluster) · PBDIGIT-274 (guard) · D-178, D-219 (4) · delta revision 3 §3.1, §3.3.
