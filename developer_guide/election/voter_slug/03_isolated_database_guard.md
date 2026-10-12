# 03: The isolated test database guard (PBDIGIT-274, S2 steps 1 and 1b)

**Story:** `PBDIGIT-274` · **Plan:** `docs/plans/20261011-2302-voter-slug-lifecycle-plan.md` section 11.0, S2-3 (gates G4 / G5) · **Evidence:** `docs/publicdigit/election/evidence/2026-10-12-voter-slug-lifecycle-s2-step1/` and `…-s2-step1b/` · **Status:** step 1 committed 2026-10-12 (`2e9017487`); step 1b (independent review corrections) committed 2026-10-12 with database-free tests passing, not pushed. G4 met 2026-10-12; `APPROVED_NAME = 'pbdigit274_isolated_test'` committed in `bacef4220`. **G5 passed 2026-10-12:** the real bootstrap identity check confirmed `pbdigit274_isolated_test`; a wrong name was refused before any connection; the first database run (4 tests) passed behind the in-app check. Evidence `docs/publicdigit/election/evidence/2026-10-12-pbdigit274-g5-identity/`.

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
| `tests/bootstrap-isolated-database.php` | runs before Laravel exists: well-formed name → credentials → no config cache → **approved name** → raw PDO `current_database()` → then pin the connections |
| `tests/Isolated/Pbdigit274/Support/DatabaseIdentityGuard.php` | the plain-PHP rules (no Laravel) |
| `tests/Isolated/Pbdigit274/Support/IsolatedDatabaseRefused.php` | refusal with a reason code; never contains a credential |
| `tests/Isolated/Pbdigit274/IsolatedDatabaseTestCase.php` | base class for the later database tests; checks again before `RefreshDatabase` |
| `tests/Isolated/Pbdigit274/Guard/*Test.php` | the guard's database-free tests |

## How it works

**Environment variables (none have defaults except the port):**
```bash
PBDIGIT274_TEST_DB=<name approved by the PO>     # must equal DatabaseIdentityGuard::APPROVED_NAME
PBDIGIT274_DB_HOST=…  PBDIGIT274_DB_USERNAME=…   # required
PBDIGIT274_DB_PORT=5432  PBDIGIT274_DB_PASSWORD=… # optional
vendor/bin/phpunit -c phpunit.pbdigit274.xml     # only after G4 and an authorisation
```

**The approved name (step 1b, review finding H1).** `DatabaseIdentityGuard::APPROVED_NAME` holds the one database the PO approved under G4. Since G4 (2026-10-12, `bacef4220`) it is **`pbdigit274_isolated_test`**. Every other name is refused as `not_approved`, including case variants and other shared or development databases such as `publicdigit`. Changing it again needs a new G4 approval and its own reviewable commit; `test_the_approved_database_is_the_g4_name` pins the value. An empty value refuses every name.

**Refusal reasons** (in the order the bootstrap checks them):

| Reason | When |
|---|---|
| `missing` | the name is not set |
| `empty` | the name is empty or whitespace |
| `malformed` | not a plain identifier of at most 63 characters; never trimmed or corrected |
| `reserved` | `null`, `true`, `false`, `empty` in any case: Laravel's `env()` turns them into special values |
| `forbidden` | `nrna_test`, any case |
| `credentials_missing` | host or username missing, or the port isn't numeric |
| `config_cached` | `bootstrap/cache/config.php` exists, or `APP_CONFIG_CACHE` is set at all; Laravel would ignore the variables |
| `not_approved` | the name isn't exactly `APPROVED_NAME` (always, until G4) |
| `connection_failed` | the lookup failed; only the exception class is reported |
| `mismatch` | `current_database()` isn't exactly the approved name |
| `not_confined` | inside the tests: the default isn't a confined connection; any connection has a URL override; any PostgreSQL connection targets another database; or any connection of any driver names a forbidden database |

**Pinning connections.** After the check, every PostgreSQL connection gets the approved database: `pgsql` (`DB_*`), `pgsql_audit` (`DB_AUDIT_*`) and `pgsql_geo` (`DB_PGSQL_GEO_*`). `DATABASE_URL` is blanked. Laravel's dotenv loader never overwrites variables that are already set, so `.env` can't redirect any of them.

**Second check in the tests:**
```php
// tests/Isolated/Pbdigit274/IsolatedDatabaseTestCase.php
public function refreshDatabase(): void
{
    // unconfined PostgreSQL connections (e.g. `testing` → nrna_test) are removed first
    config(['database.connections' => DatabaseIdentityGuard::withoutUnconfinedPostgresConnections(config('database.connections'))]);

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
- **Only `RefreshDatabase` is guarded.** Don't use `DatabaseMigrations`, `DatabaseTruncation` or a direct `migrate:fresh` in these tests (review note I4).

## Testing (database-free)
```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Isolated/Pbdigit274/Guard
```
`--no-configuration` keeps the default `phpunit.xml` (and its `nrna_test` bootstrap) out of the run. The script test runs the real bootstrap in a child process through `env -i`, because PHP's `proc_open` drops empty environment variables. While nothing is approved, the script-level `connection_failed` case can't be reached; it is unit-tested with a stand-in.

## Pitfalls
- **A cached config** (`php artisan config:cache`) makes Laravel ignore the variables; the guard refuses.
- **Mismatch tests use a stand-in for `current_database()`.** The real lookup and the second check inside a booted app are only proved after G4.
- **On plain PostgreSQL the `current_database()` check proves the database exists and the credentials work, not that it is isolated** (review note I1). Isolation comes from `APPROVED_NAME`.
- **`env()` defaults in `config/database.php`** (for example `forge`) are never reached for the three pinned connections, because every variable is set. A new PostgreSQL connection must be added to `CONFINED_CONNECTIONS` and to the variables the bootstrap pins.

**Traceability:** PBDIGIT-274 plan section 11.0 (S2-3), gates G4 / G5 · Human PO authorisations 2026-10-12 (S2 step 1 and step 1b, database-free only) · independent review findings H1, M1, M2, L1, L4 · evidence `…-s2-step1/`, `…-s2-step1b/`.
