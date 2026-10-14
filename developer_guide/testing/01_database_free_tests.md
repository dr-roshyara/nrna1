# 01: Database-free tests: shared target rules and the `DatabaseFreeTest` marker (PBDIGIT-283 R1)

**Story:** `PBDIGIT-283` remediation, step R1 · **Plan:** `docs/plans/20261014-1347-pbdigit283-test-database-safety-remediation-plan.md` (r2, approved 2026-10-14; decisions D-283-1 to D-283-4; corrections C-1 to C-3)

**Code (test infrastructure only; no production code):**
- `tests/Support/DatabaseTargetRules.php`: the shared, pure rules
- `tests/Support/DatabaseFreeEnvironment.php`: the neutralised environment, apply/restore, the pre-boot check
- `tests/Support/DatabaseFreeTest.php`: the marker interface
- `tests/CreatesApplication.php`: honours the marker before `bootstrap/app.php` is required
- `tests/Isolated/Pbdigit274/Support/DatabaseIdentityGuard.php`: now uses the shared identifier and config-cache rules

**Tests:**
- `tests/Unit/Testing/DatabaseTargetRulesTest.php` (R1(a), plain PHPUnit)
- `tests/Unit/Testing/DatabaseFreeEnvironmentTest.php` (R1(b1), plain PHPUnit, never boots)
- `tests/Unit/Testing/DatabaseFreeBootTest.php`, `DatabaseFreeBootRefusalTest.php` (R1(b2), boot level)

## Purpose
On 2026-10-13 a test run with `--no-configuration --bootstrap vendor/autoload.php` skipped `phpunit.xml`'s pinning. Laravel used the `.env` database, and `RefreshDatabase` ran `migrate:fresh` on the development database. Two gaps made that possible:
- Twelve unit tests boot Laravel **without** `Tests\TestCase`. They pointed `pgsql` at a dead socket only **after** boot, so boot itself ran on whatever the environment said.
- Nothing checked `pgsql_audit`, `pgsql_geo` or `DATABASE_URL`.

R1 gives those tests one mechanism that neutralises every database target **before** Laravel boots, under any PHPUnit configuration, and refuses to boot if it can't. It also extracts the target rules that R3 (the pre-provider hook) will apply to every test.

## Where it fits
- **Layer:** test infrastructure (`Tests\Support`). There is no application, domain or production change.
- **Two guards share one set of rules:**
  - the default suite uses the approved set `TestDatabaseSafety::RESETTABLE` (`nrna_test`, `pbdigit274_isolated_test`);
  - the PBDIGIT-274 isolated guard keeps its **own** approved name and forbidden list.
- Both call `DatabaseTargetRules`, so the rules can't drift apart (plan §8). The policies stay separate.

## Design decisions
- **D-283-3 (a):** a marker, not per-test `setUp()` code, so the neutralisation happens before boot and works under `--no-configuration`.
- **C-3:** R1(b) was split.
  - **(b1)** is pure: the map, apply/restore and the check are tested without booting.
  - **(b2)** boots only behind a fail-safe and checks every target before boot. RED never boots with an unneutralised environment, and nothing relies on boot being query-free.
- **F3 / F7:** the rules are parameterised by the caller's approved set. The default connection must be `pgsql`. A `url` on **any** connection is refused.
- **Moved, not copied:** `NAME_PATTERN`, `RESERVED_WORDS` and the config-cache rule left `DatabaseIdentityGuard`. The guard maps the shared result codes to its own `IsolatedDatabaseRefused` reasons, and its messages are unchanged (`DatabaseIdentityGuardTest`, 71 tests, still green).
- **Cached configuration** is refused by the marker too: a cached config ignores every environment variable (the Principal's approval: "cover … cached configuration").

## How it works

### The shared rules (`DatabaseTargetRules`)
Pure functions that open no connection and report every problem, not only the first:

```php
DatabaseTargetRules::identifierProblem($raw);            // 'missing'|'empty'|'malformed'|'reserved'|null
DatabaseTargetRules::configCacheProblem($path, $env);    // 'config_cached'|null (file exists, or APP_CONFIG_CACHE set at all)
DatabaseTargetRules::targetProblems($appEnv, $default, $connections, $approved); // list<string>, empty = safe
```

`targetProblems()` refuses:
- `APP_ENV` other than `testing`;
- a default connection other than `pgsql`;
- any of `pgsql`/`pgsql_audit`/`pgsql_geo` that is missing, not PostgreSQL, or on a database outside `$approved` (exact and case-sensitive; this includes the geo default `publicdigit`);
- a non-empty `url` on any connection.

An empty approved set refuses everything. Problems name the connection, **never the URL**, which may carry a password.

### The neutralised environment (`DatabaseFreeEnvironment`)
`variables()` returns every variable that selects a database target, neutralised:
- `APP_ENV=testing`, `DB_CONNECTION=pgsql`, `DATABASE_URL=''` (set and empty, so `.env` cannot fill it in);
- `DB_`, `DB_AUDIT_`, `DB_PGSQL_GEO_` + `HOST` → `SOCKET` (`/nonexistent-283-database-free-socket-dir`); `PORT` → `5432`; `DATABASE` → `nrna_test` (approved, never reached);
- `DB_READ_HOST`, `DB_WRITE_HOST` → `SOCKET`.

A PostgreSQL host that starts with `/` is a socket directory. This one doesn't exist, so **any query fails loudly**.

`enter($configCachePath)` runs these steps:
1. **apply:** write to `$_SERVER`, `$_ENV` and `putenv`. Laravel's environment repository is immutable, so `.env` can't override these values.
2. **check:** `unsafeTargets($_SERVER, $_ENV, getenv())` requires every key to hold its neutralised value in **all three** sources. The cache rule must also pass.
3. **result:** on success, it returns the snapshot. Otherwise it **restores first**, then throws a `RuntimeException` that names keys, never values: "nothing was booted".

`restore($snapshot)` puts each source back exactly as found: set, empty or absent, with no cross-source leaks.

### The marker in `createApplication()`

```php
$snapshot = $this instanceof DatabaseFreeTest
    ? DatabaseFreeEnvironment::enter(__DIR__.'/../bootstrap/cache/config.php')
    : null;

try {
    $app = require __DIR__.'/../bootstrap/app.php';
    $app->make(Kernel::class)->bootstrap();
} finally {
    if ($snapshot !== null) {
        DatabaseFreeEnvironment::restore($snapshot);
    }
}
```

- **The configuration is loaded once during bootstrap.** Restoring afterwards changes nothing for the booted app, and nothing leaks into later tests.
- **Unmarked tests are unaffected.** `Tests\TestCase` keeps its post-boot reset guard (`TestDatabaseSafety`) until R3 adds the pre-provider hook.

## How to use it
A test that boots Laravel (`CreatesApplication`) without `Tests\TestCase` and must never touch a database:

```php
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\CreatesApplication;
use Tests\Support\DatabaseFreeTest;

final class MyDatabaseFreeTest extends BaseTestCase implements DatabaseFreeTest
{
    use CreatesApplication;
}
```

The 12 marked tests:
- `TrustedProxyTest`, `VerifyVoterSlugTest`
- `VoterSlugStepTenantScopeTest`, `ElectionIpWhitelistTest`
- the voting services: `VoterSlugIssuerTest`, `DurationReaderRegressionTest`, `IpVoteLimitTest`, `VoterSlugServiceFixedLifetimeTest`, `VoterSlugRouteLookupTest`, `VoterSlugLifetimeTest`
- `VoterStepTrackingServiceTenantTest`
- `tests/Unit/Database/VoterSlugsActiveIndexMigrationTest`

Their existing post-boot socket settings stay as a second layer.

**If a marked test fails with `connection to server on socket "/nonexistent-283-…"`, it reached for a database.** Don't remove the marker. Either make the test avoid the query, or, if it genuinely needs a database, move it to a guarded base (plan F5).

## Running them (G-RUN-DF, correction C-1)

```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php <path>
```

- **Never use `phpunit.pbdigit274.xml` for database-free runs:** its bootstrap opens a PDO connection before any test (`tests/bootstrap-isolated-database.php:27`).
- **Every run is gated** (plan §4). R1 used G-RUN-DF only.

## Testing
| Step | RED | GREEN |
|---|---|---|
| R1(a) `DatabaseTargetRulesTest` | 32 errors (class missing) | 32 tests, 45 assertions |
| R1(b1) `DatabaseFreeEnvironmentTest` | 8 errors, then 4 for `enter()` | 12 tests, 133 assertions |
| R1(b2) `DatabaseFreeBootTest` + `DatabaseFreeBootRefusalTest` | interface missing: the class can't load, nothing boots | 3 tests (51 assertions) + 1 (3) |

- **(b2)'s outer net:** before `createApplication()`, `setUp()` puts every target at a **different** dead socket (`/nonexistent-283-outer-net-socket-dir`) and verifies it, aborting without boot otherwise. Finding the marker's own socket in the booted configuration proves the marker did the neutralising.
- **The refusal test** sets `APP_CONFIG_CACHE` and asserts that no `Application` exists after the refusal.
- **Regression (C-1 command):**
  - all 12 marked tests (`VerifyVoterSlugTest` 34/34);
  - `DatabaseIdentityGuardTest` 71;
  - `TestDatabaseSafetyTest` 11;
  - `DatabaseResetIsGuardedTest` 1.

## Pitfalls
- **The marker works inside `createApplication()`.** A test that boots Laravel some other way isn't covered. R4 (architecture test) will enforce coverage.
- **`Route::gatherMiddleware()` constructs the route's controller.** R1 found `VerifyVoterSlugTest` gathering middleware on **every** route, which built `CreateCommitteeController`, whose handler binding queries `governance_level_definitions` when resolved. Under the marker that became a loud failure.
  - **Test fix:** gather only `{vslug}` routes (`slugRoutes()`).
  - **Production cause:** backlog `PBDIGIT-290`.
- **Don't reuse the marker's values for a fail-safe.** The outer net must use a different socket, or the test can't tell which layer neutralised.
- **Not yet covered (later steps):**
  - the default-suite pinning of audit/geo/`DATABASE_URL` (R2);
  - the pre-provider hook for every Laravel-booting test (R3);
  - coverage enforcement (R4);
  - migrations reaching audit/geo (R5);
  - negative and positive evidence runs (R6, gated by G-RUN-NEG / G-RUN-POS).

**Traceability:** `PBDIGIT-283` remediation R1, 2026-10-14. G-PLAN-283, D-283-1..4 and C-1..C-3 approved by the Principal; G-RUN-DF authorised (database-free runs only); R1 accepted by the Principal 2026-10-14. Plan approval commit `bda1192cd`. Follow-up `PBDIGIT-290`.
