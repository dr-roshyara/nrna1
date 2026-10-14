# 02: Default-suite pinning of every PostgreSQL target (PBDIGIT-283 R2)

**Story:** `PBDIGIT-283` remediation, step R2 · **Plan:** `docs/plans/20261014-1347-pbdigit283-test-database-safety-remediation-plan.md` (decision D-283-1; finding F2)

**Code (test configuration only):**
- `tests/bootstrap-test-database.php`: the bootstrap of `phpunit.xml`
- `phpunit.xml`, `phpunit-pgsql.xml`: `<server>` entries

**Test:** `tests/Unit/Testing/DefaultSuitePinningTest.php` (plain PHPUnit; the bootstrap runs in a subprocess)

## Purpose
Before R2 the default suites pinned only `DB_*`, the `pgsql` connection. Two other PostgreSQL connections read **their own** variables (`config/database.php`):
- **`pgsql_audit`:** `DB_AUDIT_*`, falling back to `DB_*`;
- **`pgsql_geo`:** `DB_PGSQL_GEO_*`, whose database **defaults to `publicdigit`**, the development database;
- and `DATABASE_URL` overrides a connection's target entirely.

So a stray `.env` could point audit or geo traffic, or every connection through `DATABASE_URL`, away from `nrna_test`. R2 pins all of them (D-283-1 (a): the same test database as the default connection).

## Where it fits
| Layer of protection | Covers | Step |
|---|---|---|
| **Pinning** (this guide): the configuration sets every target | runs through `phpunit.xml` / `phpunit-pgsql.xml` | R2 |
| Marker (guide 01): pre-boot neutralisation | database-free tests, under any configuration | R1 |
| Pre-provider hook: refuses an unsafe loaded configuration | every Laravel-booting test, including `--no-configuration` | R3 (next) |

**Pinning alone doesn't help under `--no-configuration`,** which skips both the XML and its bootstrap. That's R3's job; R2 is the first layer, not the only one.

## How it works
Each file now sets, beside its existing `DB_*` pins:
- **`DATABASE_URL`:** set and **empty**, so `.env` can't fill it in;
- **`DB_AUDIT_HOST/PORT/DATABASE/USERNAME`** and **`DB_PGSQL_GEO_HOST/PORT/DATABASE/USERNAME`:** the database is `nrna_test`. Host, port and username **mirror that file's own `DB_*` values**:
  - `phpunit.xml` and the bootstrap: `127.0.0.1`, `5432`, `publicdigit_user`;
  - `phpunit-pgsql.xml`: `localhost`, `5432`, `postgres`.

**The bootstrap** writes the pins where Laravel's immutable environment repository reads them (`$_SERVER`, `$_ENV`, `putenv`):

```php
$otherTargets = ['DATABASE_URL' => ''];
foreach (['DB_AUDIT_', 'DB_PGSQL_GEO_'] as $prefix) {
    $otherTargets[$prefix . 'HOST'] = '127.0.0.1';
    $otherTargets[$prefix . 'PORT'] = '5432';
    $otherTargets[$prefix . 'DATABASE'] = 'nrna_test';
    $otherTargets[$prefix . 'USERNAME'] = $dbUsername;
}
foreach ($otherTargets as $key => $value) {
    $_SERVER[$key] = $value;
    $_ENV[$key] = $value;
    putenv($key . '=' . $value);
}
```

- **`<server>` entries are unconditional:** PHPUnit assigns `$_SERVER[...]` without checking (`vendor/phpunit/phpunit/src/TextUI/Configuration/PhpHandler.php:94`), and Laravel reads `$_SERVER` first. So they beat a shell-exported variable too.
- **Targeting only:** passwords stay unpinned (PBDIGIT-00, no database password in a tracked file). `DB_AUDIT_PASSWORD` falls back to `DB_PASSWORD`; `DB_PGSQL_GEO_PASSWORD` comes from the environment.

## How to extend it
- **A new PostgreSQL connection** in `config/database.php` with its own variables needs its pins in **all three places** (bootstrap, `phpunit.xml`, `phpunit-pgsql.xml`), plus a prefix in `DefaultSuitePinningTest::PREFIXES` and in `DatabaseTargetRules::CONNECTIONS` (guide 01).
- **Never pin a password here.**

## Testing
- **`test_the_configuration_pins_every_target_to_the_test_database`** (both XML files): every pin is present, with `nrna_test` and the mirrored host, port and username.
- **`test_the_bootstrap_pins_every_target_whatever_the_environment_says`:** runs the bootstrap in a **separate PHP process** started with hostile values:
  - `DATABASE_URL=pgsql://…/publicdigit`;
  - `DB_*_DATABASE=publicdigit`;
  - a foreign host.

  It asserts the pinned value in `$_SERVER`, `$_ENV` and `getenv()`. The subprocess only sets and prints variables: no Laravel, no connection.
- **Results:**
  - **RED:** 3 failures (neither file pins `DATABASE_URL`; the bootstrap kept the hostile values);
  - **GREEN:** 3 tests, 50 assertions;
  - **regression (C-1 command):** all R1 tests, the 12 marked tests, the guard, safety and reset-guard tests, all green.

## Pitfalls
- **R2 changes what real suite runs connect to:** audit and geo now go to `nrna_test`.
  - Verified only as configuration. **No run against `nrna_test` has happened**; that is R6 (G-RUN-POS).
  - With `phpunit.xml`, `pgsql_geo` authenticates as `publicdigit_user` with `DB_PGSQL_GEO_PASSWORD` from the environment. If that's unset, a geo-using test fails to authenticate rather than reaching another database (fail-closed).
- **`phpunit-pgsql.xml` still carries `DB_PASSWORD=postgres`** (pre-existing, outside R2's scope).
- **`phpunit.pbdigit274.xml` is untouched:** its identity guard already pins all three connections to `pbdigit274_isolated_test`.

**Traceability:** `PBDIGIT-283` remediation R2, 2026-10-14, under G-RUN-DF (C-1 command). Builds on R1 `164f74066`.
