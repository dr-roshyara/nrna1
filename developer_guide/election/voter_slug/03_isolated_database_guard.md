# 03: The isolated test database guard (PBDIGIT-274, S2 step 1)

**Story:** `PBDIGIT-274` · **Plan:** `docs/plans/20261011-2302-voter-slug-lifecycle-plan.md` section 11.0, S2-3 (gates G4 / G5) · **Evidence:** `docs/publicdigit/election/evidence/2026-10-12-voter-slug-lifecycle-s2-step1/` · **Status:** implemented with database-free tests passing; committed 2026-10-12, not pushed; never run against PostgreSQL (needs G4).

## Purpose
S2 onwards needs real PostgreSQL tests: the migration, concurrency, the vote contract. Those tests wipe their database (`migrate:fresh`). The normal test setup is pinned to the shared `nrna_test`:
- `phpunit.xml` sets the name;
- `tests/bootstrap-test-database.php` forces it again;
- `tests/TestCase.php` runs `RefreshDatabase` for every test.

So PBDIGIT-274's database tests get their own setup, which **refuses to start** unless it is provably connected to the one database the PO approved.

## Key files
| File | Role |
|---|---|
| `phpunit.pbdigit274.xml` | the only configuration that runs `tests/Isolated/Pbdigit274/Database`; sets no `DB_*` value |
| `tests/bootstrap-isolated-database.php` | runs before Laravel exists: validate → credentials → no config cache → raw PDO `current_database()` → then pin the connections |
| `tests/Isolated/Pbdigit274/Support/DatabaseIdentityGuard.php` | the plain-PHP rules (no Laravel) |
| `tests/Isolated/Pbdigit274/Support/IsolatedDatabaseRefused.php` | refusal with a reason code; never contains a credential |
| `tests/Isolated/Pbdigit274/IsolatedDatabaseTestCase.php` | base class for the later database tests; checks again before `RefreshDatabase` |
| `tests/Isolated/Pbdigit274/Guard/*Test.php` | the guard's database-free tests |

## How it works

**Environment variables (none have defaults except the port):**
```bash
PBDIGIT274_TEST_DB=<name approved by the PO>     # the only accepted database
PBDIGIT274_DB_HOST=…  PBDIGIT274_DB_USERNAME=…   # required
PBDIGIT274_DB_PORT=5432  PBDIGIT274_DB_PASSWORD=… # optional
vendor/bin/phpunit -c phpunit.pbdigit274.xml     # only after G4 and an authorisation
```

**Refusal reasons:**

| Reason | When |
|---|---|
| `missing` | the name is not set |
| `empty` | the name is empty or whitespace |
| `forbidden` | `nrna_test`, any case |
| `malformed` | not a plain identifier of at most 63 characters; never trimmed or corrected |
| `credentials_missing` | host or username missing, or the port isn't numeric |
| `config_cached` | `bootstrap/cache/config.php` exists; Laravel would ignore the variables |
| `connection_failed` | the lookup failed; only the exception class is reported |
| `mismatch` | `current_database()` isn't exactly the approved name |
| `not_confined` | inside the tests: a PostgreSQL connection points elsewhere, or the default connection isn't one of them |

**Pinning connections.** After the check, every PostgreSQL connection gets the approved database: `pgsql` (`DB_*`), `pgsql_audit` (`DB_AUDIT_*`) and `pgsql_geo` (`DB_PGSQL_GEO_*`). `DATABASE_URL` is blanked. Laravel's dotenv loader never overwrites variables that are already set, so `.env` can't redirect any of them.

**Second check in the tests:**
```php
// tests/Isolated/Pbdigit274/IsolatedDatabaseTestCase.php
public function refreshDatabase(): void
{
    DatabaseIdentityGuard::confirmBeforeRefresh(
        config('database.connections'),
        DatabaseIdentityGuard::approvedName(getenv(DatabaseIdentityGuard::ENV_NAME)),
        (string) config('database.default'),
        fn () => DB::connection()->selectOne('SELECT current_database() AS name')->name,
    );

    $this->refreshDatabaseAfterIdentityCheck();
}
```

## How to use it
- **New PBDIGIT-274 database test:** put it in `tests/Isolated/Pbdigit274/Database/` and extend `IsolatedDatabaseTestCase`. **Never** `Tests\TestCase`.
- **Never add `tests/Isolated` to `phpunit.xml`** or any other configuration.

## Testing (database-free)
```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Isolated/Pbdigit274/Guard
```
`--no-configuration` keeps the default `phpunit.xml` (and its `nrna_test` bootstrap) out of the run. The script test runs the real bootstrap in a child process through `env -i`, because PHP's `proc_open` drops empty environment variables.

## Pitfalls
- **A cached config** (`php artisan config:cache`) makes Laravel ignore the variables; the guard refuses.
- **Mismatch tests use a stand-in for `current_database()`.** The real lookup and the second check inside a booted app are only proved after G4.
- **`env()` defaults in `config/database.php`** (for example `forge`) are never reached for the three pinned connections, because every variable is set. A new PostgreSQL connection must be added to `CONFINED_CONNECTIONS` and to the variables the bootstrap pins.

**Traceability:** PBDIGIT-274 plan section 11.0 (S2-3), gates G4 / G5 · Human PO authorisation 2026-10-12 (S2 step 1, database-free only) · evidence `…-s2-step1/`.
