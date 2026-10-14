# PBDIGIT-293: `DB_URL` (Laravel 11's URL variable) is neither neutralised nor pinned by the test-database safety layers

**Type:** Test-database safety (follow-up to PBDIGIT-283) · **Epic:** none · **Created:** 2026-10-14 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from the first PBDIGIT-283 R6 negative-evidence run, which aborted before the canary. **Index row:** added 2026-10-14.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · creation approved by the Principal 2026-10-14 |
| **Classification** | **verified in source** (read-only). Whether `.env` sets `DB_URL` is **not checked** (`.env` isn't read) |
| **Scope** | **outside PBDIGIT-283's R1/R2 as approved.** R6 negative only empties and checks it in its own harness |

## Finding (verified in source)
- **Laravel 11 adds its own default connections** to the application's: `LoadConfiguration` merges `database.connections` from `vendor/laravel/framework/config/database.php` (`mergeableOptions`: `'database' => ['connections']`, `LoadConfiguration.php:94-127`). The application's `config/database.php` overrides `sqlite mysql pgsql sqlsrv` by name, but the framework's **`mariadb`** entry is added.
  - **The application doesn't use MariaDB.** This is only a configuration entry Laravel adds; nothing selects it.
- **That entry reads `DB_URL`,** Laravel 11's variable name, not this project's `DATABASE_URL`, and also `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_SOCKET`.
- **What the PBDIGIT-283 layers do with `DB_URL`:**
  - **R1 marker** (`DatabaseFreeEnvironment::variables()`): doesn't set `DB_URL`.
  - **R2 pinning** (`tests/bootstrap-test-database.php`, `phpunit.xml`, `phpunit-pgsql.xml`): doesn't pin `DB_URL`.
  - **R3 pre-provider check** (`DatabaseTargetRules::targetProblems()`): refuses a non-empty `url` on **any** loaded connection, so a non-empty `DB_URL` makes every Laravel-booting test refuse. That's **fail-closed**, not a reach into a database.
  - **R5** (`ConnectionReferences::configuredConnections()`): reads `config/database.php` textually, so it doesn't list the merged `mariadb`. Its checks still hold, because no application code names `mariadb`, but guide 05's configured set is incomplete.
- **R6 negative correction** (PBDIGIT-283): the harness sets `DB_URL=''` in its isolated child environment, and its pre-run check validates the merged `mariadb` entry strictly (dead socket, closed port, dead Unix socket, no URL).

## Impact
- **No database reach found:** R3 refuses before providers.
- **A `DB_URL` in `.env` would make every test refuse with a URL-override message,** which is confusing but safe. The documented coverage ("every target pinned or neutralised") is incomplete for this variable.

## Proposed direction (separate design; nothing decided)
1. **R1:** add `DB_URL => ''` to `DatabaseFreeEnvironment::variables()` (and its pre-boot check).
2. **R2:** pin `DB_URL` empty in `tests/bootstrap-test-database.php`, `phpunit.xml`, `phpunit-pgsql.xml` (and `DefaultSuitePinningTest`).
3. **R5:** list the configured connections from the **loaded** configuration semantics (application plus framework defaults), or document the merge explicitly.
4. **Optional:** decide whether the application's `config/database.php` should shadow the framework's `mariadb` entry. That's a production configuration change, needing its own approval.

## Acceptance criteria
1. **R1:** a marked test boots with `DB_URL` set and empty in `$_SERVER`, `$_ENV` and `getenv()`; the pre-boot check fails if it isn't; restored afterwards (test first).
2. **R2:** `DefaultSuitePinningTest` requires `DB_URL` empty in both XML files and in the bootstrap subprocess under a hostile `DB_URL`.
3. **R3:** a boot-level case with `DB_URL` set (to an unreachable target) is refused before any provider, naming `mariadb`.
4. **R5 and its guide** account for framework-merged connections; a test fails if a loaded connection exists that the rules don't know.
5. **No MariaDB service is installed, configured or connected** for any of this.
6. All runs use the database-free command (`vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php <path>`).

## Related
- `PBDIGIT-283`: plan §6 R6 (correction after the first aborted run); `tests/Support/NegativeEvidence.php`; guides `developer_guide/testing/01`, `02`, `05`, `06`.
- Evidence of the aborted run: `docs/publicdigit/election/evidence/2026-10-14-pbdigit283-r6-negative/run-output.txt`.
