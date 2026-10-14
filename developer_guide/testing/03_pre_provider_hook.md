# 03: The pre-provider hook: every Laravel-booting test refuses an unsafe configuration (PBDIGIT-283 R3)

**Story:** `PBDIGIT-283` remediation, step R3 · **Plan:** `docs/plans/20261014-1347-pbdigit283-test-database-safety-remediation-plan.md` (decision D-283-2; finding F1)

**Code (test infrastructure only):**
- `tests/CreatesApplication.php`: registers the hook
- `tests/Support/TestDatabaseSafety.php`: `refuseUnsafeConfiguration()`

**Tests:**
- `tests/Unit/Testing/PreProviderHookTest.php` (boot level)
- `tests/Unit/Testing/TestDatabaseSafetyTest.php` (two pure cases added)

## Purpose
- **R1** neutralises database-free tests (the marker).
- **R2** pins targets when a run goes through `phpunit.xml` / `phpunit-pgsql.xml`.
- **Neither helps a test that isn't marked, run with `--no-configuration`:** exactly the 2026-10-13 incident. That test boots on the `.env` database, and `Tests\TestCase`'s reset guard checks only the default connection, only **after** boot.

R3 closes this gap. **Every** test that boots Laravel through `CreatesApplication` has its **loaded** configuration checked **before any service provider is registered**. An unsafe configuration throws, and nothing has connected.

## Where it fits
| Layer | When | Covers |
|---|---|---|
| R2 pinning | before boot, via the PHPUnit configuration | runs through the two default XML files |
| R1 marker | before `bootstrap/app.php` | database-free tests, any configuration |
| **R3 hook** | **after `LoadConfiguration`, before `RegisterProviders`** | **every Laravel-booting test, any configuration** |
| `TestDatabaseSafety::assertResettable` | after boot, before a reset | the actual `current_database()` (unchanged) |

## Design decisions
- **D-283-2 (a):** the hook is registered in `createApplication()` after `require bootstrap/app.php` and before `Kernel::bootstrap()`, as `afterBootstrapping(LoadConfiguration::class, …)`.
  - Laravel's console kernel runs `LoadEnvironmentVariables`, `LoadConfiguration`, `HandleExceptions`, `RegisterFacades`, `SetRequestForConsole`, `RegisterProviders`, `BootProviders` in that order.
  - So the hook sees the final configuration while the database manager doesn't exist yet (plan §2.1, verified).
- **One set of rules:** the hook calls `DatabaseTargetRules::targetProblems()` (guide 01) with `TestDatabaseSafety::RESETTABLE` (`nrna_test`, `pbdigit274_isolated_test`). So the default suite, the isolated suite and marked tests all pass, and anything else is refused.
- **A cached configuration is refused**, whether through the cache file or `APP_CONFIG_CACHE` set at all: it would bypass every environment pin.

## How it works

```php
$app = require __DIR__.'/../bootstrap/app.php';

$app->afterBootstrapping(LoadConfiguration::class, static function ($app): void {
    TestDatabaseSafety::refuseUnsafeConfiguration(
        $app->environment(),
        (string) $app['config']->get('database.default'),
        (array) $app['config']->get('database.connections', []),
        $app->getCachedConfigPath(),
        getenv(),
    );
});

$app->make(Kernel::class)->bootstrap();
```

`refuseUnsafeConfiguration()` refuses:
- `APP_ENV` other than `testing`;
- a default connection other than `pgsql`;
- `pgsql`, `pgsql_audit` or `pgsql_geo` off the approved set;
- a `url` on any connection;
- a cached configuration.

It throws a `RuntimeException` that lists every problem (never a URL) and names the safe ways to run.

**Order with the marker:** a marked test is neutralised first (R1), so its loaded configuration is at the dead socket under `nrna_test` and passes the hook.

## How to use it
Nothing to do: any test using `Tests\CreatesApplication` gets the hook.
- **Refused under `--no-configuration`?** Either run it through `phpunit.xml` (database-backed tests), or mark it `DatabaseFreeTest` if it must never touch a database.
- **Adding a PostgreSQL connection** means adding it to `DatabaseTargetRules::CONNECTIONS` (guide 01) and to the pins (guide 02).

## Testing
**`PreProviderHookTest`:** each case starts from the outer net (every target at a dead socket), changes **one** aspect and verifies the environment before boot. The fixture is **unmarked**, so the marker can't mask the case. The seven unsafe cases:
- the default database, the audit database or the geo database is `publicdigit`, each at the dead socket;
- `DATABASE_URL` is set, to a `.invalid` host whose query string also points at a socket;
- the default connection is `pgsql_audit`;
- `APP_ENV=local`;
- `APP_CONFIG_CACHE` is set.

Each one must:
- throw the PBDIGIT-283 refusal naming the problem;
- echo no URL;
- leave `db` **unbound** and `AppServiceProvider` **not loaded**, which proves the refusal came before any provider.

A safe configuration boots.

**Results:**
- **RED:** all 7 unsafe configurations booted without refusal, at dead sockets; the two pure cases failed with "undefined method".
- **GREEN:** `PreProviderHookTest` 8 tests (51 assertions); `TestDatabaseSafetyTest` 13 tests (29 assertions).
- **Regression (C-1 command):** the 12 marked tests, all R1/R2 tests, `DatabaseIdentityGuardTest` 71 and `DatabaseResetIsGuardedTest` 1, all green with the hook active.

## Pitfalls
- **Don't put `$this->fail()` inside a `try` that catches `RuntimeException`.** PHPUnit's own failures extend `RuntimeException`, so the catch swallows the missing-exception failure. Capture the exception, then assert outside the `try`, as `PreProviderHookTest` does. The same fix was applied to R1's `DatabaseFreeEnvironmentTest::test_enter_refuses_a_cached_configuration_and_restores_first`, whose `fail()` text contained the asserted word "cached".
- **The hook covers `CreatesApplication` only.** A test that boots Laravel another way isn't covered; R4's architecture test enforces this.
- **Tests that change `database.*` config after boot** (e.g. a temporary SQLite probe connection) aren't affected: the hook checks the loaded configuration once, at boot.
- **Not yet exercised against `nrna_test`:** suite runs (`phpunit.xml`) will now boot through the hook. That is R6 (G-RUN-POS).

**Traceability:** `PBDIGIT-283` remediation R3, 2026-10-14, under G-RUN-DF (C-1 command). Builds on R1 `164f74066` and R2 `6d394e6e1`.
