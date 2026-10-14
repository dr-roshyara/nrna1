# 06: Negative evidence: the incident's shape is refused (PBDIGIT-283 R6, G-RUN-NEG)

**Story:** `PBDIGIT-283` remediation, step R6 (negative) · **Plan:** `docs/plans/20261014-1347-pbdigit283-test-database-safety-remediation-plan.md` §6 R6 (D-283-4, F6; **Option A**)

**Status: PREPARED (`eb18ccfa9`), CORRECTED after run 1 aborted before the canary; run 2 after the correction commit.** Results are recorded only in the evidence folder `docs/publicdigit/election/evidence/2026-10-14-pbdigit283-r6-negative/`.

**Earlier status, superseded: PREPARED, NOT EXECUTED.** The harness and canary exist and the pure rules are tested. **The evidence run hasn't happened**; it waits for the Principal's review of the script and command. Nothing below claims a result of that run.

**Files:**
- `tests/Evidence/Pbdigit283/negative-evidence.php`: the harness;
- `tests/Evidence/Pbdigit283/preflight.php`: the pre-run loader (environment and configuration only);
- `tests/Evidence/Pbdigit283/ResetCanaryTest.php`: the canary, a `Tests\TestCase`, **in no suite**;
- `tests/Support/NegativeEvidence.php`: the pure rules;
- `tests/Unit/Testing/NegativeEvidenceTest.php`: 34 tests of those rules;
- `tests/Architecture/LaravelBootIsGuardedTest.php`: `preflight.php` added as an allowed boot site, with its own check.

## Purpose
Criterion 4: show that a run shaped like the 2026-10-13 incident is refused **before any query**. That shape is a `Tests\TestCase` with `RefreshDatabase`, run with `--no-configuration` and a non-test database configured. The run must not be able to reach a database even if a guard is broken.

## Design
**Exit codes:** 0 = evidence; 1 = aborted before the canary; 2 = the canary run isn't the evidence.

1. **No configuration cache.** The harness refuses to start if `bootstrap/cache/config.php` exists or `APP_CONFIG_CACHE` is set.
2. **The child environment is built from scratch.** Only `PATH` and `HOME` are inherited (`NegativeEvidence::childEnvironment()`). Every target variable is set:
   - `APP_ENV=testing`, `DB_CONNECTION=pgsql`, `DATABASE_URL=''`, **`DB_URL=''`** (Laravel 11's URL variable, read by the merged `mariadb` entry; `PBDIGIT-293`);
   - `DB_`, `DB_AUDIT_`, `DB_PGSQL_GEO_` + `HOST` → `/nonexistent-283-negative-evidence-socket-dir`; `PORT` → `1` (closed); `DATABASE` → **`publicdigit`** (the incident's database, so R3 must refuse); `USERNAME` → `nobody`; `PASSWORD` → empty;
   - `DB_READ_HOST`, `DB_WRITE_HOST`, `DB_SOCKET` → the dead socket.

   Laravel's environment repository is immutable, so `.env` can't override any of these.
3. **The pre-run check.**
   - **What `preflight.php` does:** `require bootstrap/app.php`, then `bootstrapWith([LoadEnvironmentVariables::class, LoadConfiguration::class])`. No provider is registered, so no database manager exists. It prints the effective database configuration as JSON, **with every password removed**.
   - **What `NegativeEvidence::preflightProblems()` requires:**
     - not cached; `APP_ENV=testing`; default `pgsql`; `db` **not bound**; the SQLite target file **doesn't exist** (Laravel's SQLite connector refuses a missing file and never creates it);
     - the **loaded** connection set is **exactly** `sqlite mysql mariadb pgsql pgsql_audit pgsql_geo sqlsrv testing`. `mariadb` is added by Laravel 11 from its framework defaults (`LoadConfiguration` merges `database.connections`); the application doesn't use MariaDB, and the entry is checked like any environment-controlled connection;
     - no `url` on any connection, and no password anywhere in the report;
     - `pgsql`, `pgsql_audit`, `pgsql_geo`, `mysql`, `mariadb`, `sqlsrv` are at the dead socket with port `1`, and MySQL's write host and `unix_socket` and MariaDB's `unix_socket` too;
     - the default database is `publicdigit`;
     - **Option A:** `testing` matches exactly (see below).
   - **Any problem aborts with exit 1;** the canary never starts.
4. **The canary**, only then, in a separate process with the same environment:

   ```bash
   php vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Evidence/Pbdigit283/ResetCanaryTest.php
   ```

   The canary's body is a `fail()` that's never reached when R3 works.
5. **Evidence** (`NegativeEvidence::refusalProblems()`) requires all of:
   - a non-zero exit;
   - the output contains the R3 refusal, the reason `Connection 'pgsql' targets 'publicdigit', which is not approved.`, the canary's name and `Tests: 1,`;
   - it **doesn't** contain `SQLSTATE`, `connection to server`, or the canary-body marker.

## The Option A exception (Principal, 2026-10-14)
- **The problem:** `config/database.php:140-150` hard-codes a `testing` connection that no environment variable can neutralise. It's reachable, so the plan's original rule "abort if any host is reachable" could never pass.
- **The exception:** that **one** connection is accepted only if it's **exactly** `driver=pgsql`, `host=127.0.0.1`, `port=5432`, `database=nrna_test`, `username=nrna`, with no `url`, and it isn't the default.
  - Its password is never read, compared or printed.
  - Any difference, a missing connection or any additional connection stops the run.
- **Why it's narrowly safe:**
  - It points at an approved **test** database, never `publicdigit` or `nrna`.
  - Nothing selects it (search 2026-10-14).
  - The canary uses the default connection, which is refused before providers exist.
- **Not weakened:** the default connection, every environment-controlled target, `DATABASE_URL` and per-connection URLs, unknown connections, and the unsafe `publicdigit` canary all keep the strict rules.
- **The connection itself and its tracked plaintext password** are backlog `PBDIGIT-292` (credential review, rotation if warranted, removal). Removing it also removes this exception.

## Proposed execution (G-RUN-NEG, after the Principal's review)
```bash
php tests/Evidence/Pbdigit283/negative-evidence.php
```
- Run from the repository root.
- Output kept as evidence under `docs/publicdigit/election/evidence/` (path to be fixed on execution).
- **Before running, verify** (read-only): no `bootstrap/cache/config.php`; `APP_CONFIG_CACHE` unset; `git diff` of `tests/Evidence/Pbdigit283/`, `tests/Support/NegativeEvidence.php`, `tests/CreatesApplication.php`, `tests/Support/TestDatabaseSafety.php` unchanged since review.

## Testing so far (database-free, C-1 command)
- **`NegativeEvidenceTest`:** RED 34 errors (class missing) → GREEN 34 tests, 53 assertions. Covered:
  - the child environment;
  - the expected report passes;
  - 21 unsafe or unexpected reports stop the run, plus the missing exception;
  - no URL is echoed;
  - the expected refusal is evidence, and 7 look-alikes aren't.
- **`LaravelBootIsGuardedTest`:** RED, it caught `preflight.php` as a new boot site. GREEN 18 tests after the allow-list entry plus the check "only `LoadEnvironmentVariables` + `LoadConfiguration`; no `Kernel`, `->bootstrap()`, `->boot()`, provider registration".
- **Regression:** `DatabaseResetIsGuardedTest` 1, `NoReachIntoAuditOrGeoTest` 12, `tests/Unit/Testing` 106, all green.
- **`php -l`** on every new file.

## Run history
- **Run 1** (`eb18ccfa9`): **aborted before the canary** (exit 1). The loaded set had `mariadb`, which the first design didn't expect, and `DB_URL` wasn't set. The fail-safe worked; it's not evidence of refusal.
- **Correction** (test first): `NegativeEvidenceTest` RED 2 failures → GREEN 39 tests. `mariadb` added to the known loaded set with strict checks; `DB_URL=''`. Option A unchanged.
- **Run 2:** after the correction commit; see the evidence folder.

## Limits
- **The harness, preflight and canary have never run.** Their behaviour beyond the pure rules is unproven until G-RUN-NEG executes.
- **`preflight.php` runs application bootstrap code** (`bootstrap/app.php`, configuration files) under the child environment. It registers no providers.
- **The evidence is textual** (output and exit code), not an instrumented proof. "No connection attempted" rests on the absence of driver errors plus the dead endpoints.
- **Option A is a recorded deviation** from plan §6 R6(3) until `PBDIGIT-292` removes the connection.

**Traceability:** `PBDIGIT-283` R6 negative, prepared 2026-10-14; G-RUN-NEG authorised but **execution held for review**; Option A approved by the Principal. Builds on R1–R5 (`164f74066`, `6d394e6e1`, `17167dec3`, `b7138fafa`, `ac66fd940`). Follow-up `PBDIGIT-292`.
