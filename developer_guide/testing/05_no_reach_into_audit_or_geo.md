# 05: No test reset reaches the audit or geo database (PBDIGIT-283 R5)

**Story:** `PBDIGIT-283` remediation, step R5 · **Plan:** `docs/plans/20261014-1347-pbdigit283-test-database-safety-remediation-plan.md` (criterion 6; unknown U-4)

**Code (test infrastructure only):** `tests/Support/ConnectionReferences.php`: pure source reading, which removes comments with the tokenizer, collects named connections, detects a `getConnection()` override, and lists the configured connections.

**Test:** `tests/Architecture/NoReachIntoAuditOrGeoTest.php` (plain PHPUnit; reads sources, never runs them)

## Purpose
`pgsql_audit` and `pgsql_geo` are separate connections. U-4 asked whether a test reset or a migration could reach the databases behind them. R5 records the answer from source and pins it, so that a future change can't silently widen a reset.

## Review note: what a test reset touches (verified in source)
- **`RefreshDatabase` runs `migrate:fresh` with no `--database` option** (`Illuminate/Foundation/Testing/RefreshDatabase.php:66`, options from `CanConfigureMigrationCommands`). `FreshCommand` calls `db:wipe` with that empty option (`FreshCommand.php:71-83`), and `WipeCommand` drops all tables on **the default connection** (`WipeCommand.php:42-73`).
- **No test widens that scope:** none uses `migrateFreshUsing`, `connectionsToTransact`, `connectionsToTruncate`, `--database`, or another `migrate:*`/`db:wipe` command.
- **No migration names a connection** in any loaded migration path, so every table is created on the default database. That covers `database/migrations` (200 files, including `landlord/`) and the context paths under `app/Contexts/*/Infrastructure/Database/Migrations` (29 files). The paths come from the 9+ `loadMigrationsFrom()` calls. The two `database/migrations_backup_*` folders (289 files) are **not** loaded.
- **`ElectionSecurityEvent`** (`app/Models/ElectionSecurityEvent.php:21`, `protected $connection = 'pgsql_audit'`, ADR_20260806_1340) is the only application code that reaches a non-default **configured** connection. It writes outside the vote transaction. In tests, `pgsql_audit` is:
  - `nrna_test` in the default suites (R2 pinning);
  - `pbdigit274_isolated_test` in the isolated suite;
  - a dead socket under the database-free marker (R1).

  Any other target is refused before providers by R3. Its table (`election_security_events`) is created by default-connection migrations. In tests that is the same database, so `migrate:fresh` resets it with everything else.
- **`landlord` and `tenant`** are named by Geography and Membership services (`DB::connection('landlord'|'tenant')`, `GeographyLookupService::$connection`). They are **not configured** (`config/database.php` has `sqlite mysql pgsql pgsql_audit pgsql_geo sqlsrv testing`), so using them fails with "not configured". They can't reach a database today.

## What the architecture test enforces
1. **The detector** finds connection names given as literals (`->connection('x')`, `::connection("x")`, `$connection = 'x'` properties) and `getConnection()` overrides. It ignores comments, the default `connection()` and variable arguments. Nine snippet cases pin this.
2. **Every `loadMigrationsFrom()` argument in `app/`** points into a scanned root (`Database/Migrations`, or `database_path('migrations…')`), never into a backup folder. At least 9 calls are expected.
3. **No migration names a connection or overrides `getConnection()`.** At least 229 files are scanned.
4. **Every configured connection named by application code is one R3 confines** (`DatabaseTargetRules::CONNECTIONS`). Unconfigured names can't connect; a newly configured one fails this check until it's added to the rules (guide 01) and the pins (guide 02).

## Testing
**Command (G-RUN-DF, C-1):**

```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php <path>
```

- **RED:** 12 errors (the helper class missing). **The codebase already satisfied the properties:** R5 pins the current state, it doesn't fix a defect.
- **GREEN:** 12 tests, 43 assertions. My first migration floor (>400) was wrong because it counted the unloaded backups; it was corrected to the measured 229.
- **Regression:** `LaravelBootIsGuardedTest` 17, `DatabaseResetIsGuardedTest` 1, `DatabaseIdentityGuardTest` 71, `tests/Unit/Testing` 72, all green.

## Pitfalls and limits
- **Source reading, not execution:**
  - a connection name built at runtime (e.g. from a variable or config value) isn't detected;
  - a mention inside a string literal may count (safe direction).
- **Configured connections are read textually** from `config/database.php` (entries with a `'driver'` key). A connection added at runtime (`config(['database.connections.x' => …])`) isn't seen. None exists in `app/` today.
- **`landlord`/`tenant` stay unchecked while unconfigured.** If they're ever configured, check 4 fails, by design.
- **No database was exercised.** That runs against `nrna_test` is safe is R6 (G-RUN-POS).

**Traceability:** `PBDIGIT-283` remediation R5, 2026-10-14, under G-RUN-DF (C-1 command). Builds on R1 `164f74066`, R2 `6d394e6e1`, R3 `17167dec3`, R4 `b7138fafa`.
