# PBDIGIT-283 test-database safety remediation: implementation plan

**Status: DRAFT r2, awaiting EP-01 approval.** It authorises nothing: no code, no tests (including database-free ones), no database access. Every run needs its own authorisation (§4).

**Revisions:**
- r1 `b71137e62`;
- **r2 (2026-10-14):** the Principal-approved corrections F1–F7 from the read-only review (bootstrap-hook timing verified; `DATABASE_URL`/`url` for every connection; shared rules parameterised by the approved set; the marker before the hook; all 12 tests classified; fail-safe negative evidence; the default connection constrained).

**Created:** 2026-10-14 at the Principal's request ("plan the PBDIGIT-283 remediation") · **Backlog:** `docs/publicdigit/backlog/PBDIGIT-283-test-database-reset-guard.md`, "Remediation extension" (`3fd58af8c`) · **Related:** PBDIGIT-286 plan §13 (this remediation **blocks PBDIGIT-286 G-ISO**) · **Supersedes:** none.

**Evidence labels:** **[V]** verified in source (read-only) · **[I]** inference · **[U]** not verified.

---

## 1. Objective
- **No test can touch a non-test database,** however it's run: with `phpunit.xml`, with another configuration, or with `--no-configuration`.
- **On every connection** the application can use.
- **Refuse before any database query.**

## 2. Background (read-only investigation, 2026-10-14)

### 2.1 What exists [V]
- **`Tests\TestCase`** applies `RefreshDatabase` (`tests/TestCase.php:18`); on PostgreSQL that runs `migrate:fresh` (`:49-55`).
- **The guard:** `setUpTraits()` (`:28-40`) checks `current_database()` of the **default** connection against `TestDatabaseSafety::RESETTABLE` (`nrna_test`, `pbdigit274_isolated_test`). It does so **after** Laravel has booted, and only for tests using reset traits.
- **Default-suite pinning:** `tests/bootstrap-test-database.php` (bootstrap of `phpunit.xml`) and `phpunit.xml` pin **only** `DB_*`. `phpunit-pgsql.xml` uses `vendor/autoload.php` as bootstrap and pins `DB_*` through `<server>` only.
- **The connections** (`config/database.php`):
  - `pgsql`: `url => env('DATABASE_URL')` (`:72`);
  - `pgsql_audit`: database `env('DB_AUDIT_DATABASE', env('DB_DATABASE'))` (`:99-105`);
  - **`pgsql_geo`: database `env('DB_PGSQL_GEO_DATABASE', 'publicdigit')`** (`:113-119`). **Its default is the development database that was wiped.**
  - also `mysql`, `sqlite`, `sqlsrv` (each with `url => env('DATABASE_URL')`, `:40,48,129`), and a hard-coded `testing` connection (`nrna_test`, `:140-144`);
  - the default connection is `env('DB_CONNECTION', 'mysql')` (`:18`).
- **Users of the extra connections:** only `ElectionSecurityEvent` uses `pgsql_audit` (`app/Models/ElectionSecurityEvent.php:21`). Nothing in `app`, `database`, `config` or `routes` uses `pgsql_geo` by name.
- **The isolated suite's guard** (`tests/Isolated/Pbdigit274/Support/DatabaseIdentityGuard.php`) is plain PHP and runs **before** Laravel exists. It:
  - confines all three PostgreSQL connections (`CONFINED_CONNECTIONS`; prefixes `DB_`, `DB_AUDIT_`, `DB_PGSQL_GEO_`);
  - **blanks `DATABASE_URL`** and checks each connection's `url` (`:142-149`, `:208`);
  - refuses a cached config (`refuseIfConfigCached`).

  But its name rules are **suite-specific**: `APPROVED_NAME` is the isolated database only, and `FORBIDDEN_NAMES` **includes `nrna_test`**.
- **Laravel bootstrap order** (`vendor/laravel/framework/src/Illuminate/Foundation/`):
  - `Application::bootstrapWith()` (`Application.php:335-346`) dispatches `'bootstrapping: X'`, runs `X->bootstrap()`, **then** dispatches `'bootstrapped: X'`;
  - `afterBootstrapping(X, …)` listens to `'bootstrapped: X'` (`:380-383`), so it runs **after** `X`;
  - the console kernel's order: `LoadEnvironmentVariables`, `LoadConfiguration`, `HandleExceptions`, `RegisterFacades`, `SetRequestForConsole`, `RegisterProviders`, `BootProviders` (`Console/Kernel.php:119-127`);
  - before `RegisterProviders` only the base providers (Event, Log, Context, Routing) are registered, in the constructor (`Application.php:299-305`), so **the database manager isn't registered yet**.
  - **So `afterBootstrapping(LoadConfiguration::class, …)` sees the fully loaded configuration, and runs before any provider can touch a database.** [V] (F1)
- **Test-side boot:** `Tests\CreatesApplication::createApplication()` is `require bootstrap/app.php` + `Kernel::bootstrap()`.
- **Laravel's environment repository is immutable:** values already present (`$_ENV`/`$_SERVER`/`putenv`) aren't overwritten by `.env`, and reads are live. [V by framework design; to be pinned by a test]
- **The twelve unit tests booting Laravel through `CreatesApplication` outside `Tests\TestCase`, classified (F5):**

  | Test | Database use | Current neutralisation |
  |---|---|---|
  | `tests/Unit/Http/TrustedProxyTest.php` | database-free | non-existent socket in `setUp()` |
  | `tests/Unit/Models/VoterSlugStepTenantScopeTest.php` | database-free | same |
  | `tests/Unit/Models/ElectionIpWhitelistTest.php` | database-free | same |
  | `tests/Unit/Services/Voting/VoterSlugIssuerTest.php` | database-free | same |
  | `tests/Unit/Services/Voting/DurationReaderRegressionTest.php` | database-free | same |
  | `tests/Unit/Services/Voting/IpVoteLimitTest.php` | database-free | same |
  | `tests/Unit/Services/Voting/VoterSlugServiceFixedLifetimeTest.php` | database-free | same |
  | `tests/Unit/Services/VoterStepTrackingServiceTenantTest.php` | database-free | same |
  | `tests/Unit/Database/VoterSlugsActiveIndexMigrationTest.php` | database-free | same |
  | `tests/Unit/Http/Middleware/VerifyVoterSlugTest.php` | database-free **by assertion** (`:545-565`: zero queries, no opened connection) | **none** |
  | `tests/Unit/Services/Voting/VoterSlugRouteLookupTest.php` | database-free by design (`hydrate()`, query listener) | **none** |
  | `tests/Unit/Services/Voting/VoterSlugLifetimeTest.php` | database-free by design (docblock: "never saved") | **none** |

  - **None** requires a database [V for design and assertions; actual zero-query behaviour under the marker is proven in R1, §6].
  - **None** neutralises `pgsql_audit`, `pgsql_geo` or `DATABASE_URL`.

### 2.2 Most likely mechanism of the 2026-10-13 wipe (high confidence; not reproduced)
`--no-configuration --bootstrap vendor/autoload.php` skipped both pins, so Laravel used the `.env` database, and `RefreshDatabase` ran `migrate:fresh` on it.

### 2.3 Unknowns
- **U-1:** which databases the audit and geo connections (and any `DATABASE_URL`) reach in default-suite runs (depends on `.env`; not read). **The remediation must not rely on `.env` being safe.**
- **U-3:** the full default-suite run with the guard.
- **U-4:** reset or migration reach into the audit and geo databases.

(U-2 is resolved by the classification above.)

## 3. Scope
**In scope:** backlog acceptance criteria 1–6:
- pinning **and** validating every connection, including `url`/`DATABASE_URL`;
- a fail-closed check before any query;
- enforced coverage;
- negative and positive evidence (runs separately authorised);
- no reach into audit or geo.

**Non-goals:**
- no change to production database configuration or `.env`;
- no change to `RESETTABLE` beyond an explicit decision;
- no change to PBDIGIT-286 code;
- no data recovery or inspection.

## 4. Gates (each separate)

| Gate | What it approves | Blocks |
|---|---|---|
| **G-PLAN-283** | this plan | everything |
| **G-DEC-283** | decisions D-283-1 to D-283-4 (§5) | the dependent steps |
| **G-RUN-DF** | running **database-free** PHPUnit tests (plain PHPUnit, or marker tests with every connection at a non-existent socket) | R1–R5 RED/GREEN runs |
| **G-RUN-NEG** | criterion 4: the negative-evidence run (§6 R6) | R6 negative |
| **G-RUN-POS** | criterion 5: the default suite on `nrna_test` | R6 positive |

**Never permitted:** `nrna`, `publicdigit`, or any shared or development database.

## 5. Decisions needed (recommendations; not approved)

| ID | Question | Options | Recommendation |
|---|---|---|---|
| **D-283-1** | Where do `pgsql_audit` and `pgsql_geo` point in the default suite? | (a) the same test database as the default connection (`nrna_test`); (b) dedicated test databases (needs creation by operations) | **(a).** The isolated suite already confines all three to its single database |
| **D-283-2** | Where does the fail-closed check run? | (a) a hook registered in `CreatesApplication::createApplication()` **after** `require bootstrap/app.php` and **before** `Kernel::bootstrap()`, as `$app->afterBootstrapping(LoadConfiguration::class, …)`, so it inspects the **loaded** configuration before any provider is registered (F1, §2.1); (b) only in the PHPUnit bootstrap file (skipped under `--no-configuration`); (c) in `Tests\TestCase::setUpTraits()` (after boot, as today) | **(a)**: it runs for **every** Laravel-booting test, whatever the PHPUnit configuration. R2 pinning stays as a second layer. |
| **D-283-3** | How do database-free tests declare themselves? | (a) a marker (e.g. `Tests\Support\DatabaseFreeTest`). For a marked test, `createApplication()` **before** `require bootstrap/app.php` sets (via `$_ENV`/`$_SERVER`/`putenv`, which win over `.env` because the repository is immutable): `APP_ENV=testing`, `DB_CONNECTION=pgsql`, `DATABASE_URL=''`, and host, port and database of **all three** PostgreSQL connections to a **non-existent socket** under an approved name. It restores the previous values after bootstrap, so nothing leaks into later tests. (b) keep per-test `setUp()` code, enforced by an architecture test. | **(a):** one mechanism, neutralises before boot, and works under `--no-configuration` |
| **D-283-4** | How is the negative evidence (criterion 4) produced safely? | (a) a subprocess runs a canary `Tests\TestCase` test with `--no-configuration` under a fully neutralised environment (§6 R6); (b) an end-to-end run against a scratch SQLite file (as in the original 283 evidence) | **(a)** with the safeguards in §6 R6, plus (b) kept as is |

## 6. Test-first phases
Each step runs **RED → GREEN → REFACTOR → REGRESSION → REVIEW**, with a stop for review between steps.

**Order changed in r2 (F4):** the marker (R1) comes **before** the hook (R3). Otherwise the hook would refuse all 12 database-free tests under the documented `--no-configuration` command.

| Step | Prerequisites | RED (first failing test) | Minimal GREEN | Database |
|---|---|---|---|---|
| **R0** | G-PLAN-283 | — | decisions recorded | — |
| **R1 Shared rules + database-free marker** | G-DEC (D-283-1, D-283-3), G-RUN-DF | **(a) Pure rules (F3, F7):** plain-PHPUnit tests of a shared pure helper (e.g. `Tests\Support\DatabaseTargetRules`) **parameterised by the approved set**: identifier syntax and reserved words (moved out of `DatabaseIdentityGuard`, not copied); **a non-empty `url` is refused** for every connection; a cached config is refused; the **default connection must be `pgsql`** with an approved database; any of `pgsql`/`pgsql_audit`/`pgsql_geo` targeting a non-approved database (including the geo default `publicdigit`) is refused; `APP_ENV` other than `testing` is refused. `DatabaseIdentityGuard` keeps its own approved set and forbidden list and calls the helper; its tests (`tests/Isolated/Pbdigit274/Guard/DatabaseIdentityGuardTest.php`) must stay green. **(b) Marker:** a marked test under `--no-configuration`-like conditions boots with every connection at a non-existent socket and `DATABASE_URL` empty, and the environment is restored afterwards. | the helper; `DatabaseIdentityGuard` refactored to use it; the marker interface + pre-boot neutralisation in `createApplication()`; **all 12 tests marked**. Each test proves its database-freedom **under the marker**: any query fails loudly against the non-existent socket. A test that turns out to need a database moves to a guarded base instead (F5). | none |
| **R2 Pinning** | R1, D-283-1 | including `tests/bootstrap-test-database.php` (separate process) leaves `DATABASE_URL` empty and `DB_AUDIT_*`, `DB_PGSQL_GEO_*` pinned per D-283-1; `phpunit.xml` and `phpunit-pgsql.xml` carry the same `<server>` entries (F2) | pin in the bootstrap + both XML files | none |
| **R3 Pre-provider hook** | R1, R2, D-283-2 | creating the application with an unsafe configuration (each case: unsafe default database; unsafe audit; unsafe geo; non-empty `DATABASE_URL`; default connection not `pgsql`; `APP_ENV` not `testing`; cached config), **all at non-existent sockets**, throws **before any provider is registered** (a canary provider isn't registered; no query listener fires) | the `afterBootstrapping(LoadConfiguration::class, …)` hook in `createApplication()` calling the R1 helper with the approved set (`TestDatabaseSafety::RESETTABLE`) | none (non-existent sockets only) |
| **R4 Coverage** | R1, R3 | an architecture test fails for any test class that boots Laravel (`CreatesApplication`) without `Tests\TestCase`, an isolated base or the marker | the architecture test (all 12 already marked in R1) | none |
| **R5 No reach into audit or geo** | R1 | an architecture test fails if a migration targets `pgsql_audit`/`pgsql_geo` (`Schema::connection(...)`, `$connection`) without approved pinning; a code-review record covers `migrate:fresh` scope (default connection only) and `ElectionSecurityEvent` | the architecture test + review note in the evidence | none |
| **R6 Evidence** | R1–R5; **G-RUN-NEG**, **G-RUN-POS** | **Negative (D-283-4, fail-safe, F6):** the subprocess (1) **refuses to start** if `bootstrap/cache/config.php` exists or `APP_CONFIG_CACHE` is set; (2) sets **every** key that could select a target (`DB_CONNECTION`, `DATABASE_URL`=empty, `DB_HOST`/`PORT`/`DATABASE`/`USERNAME`, `DB_AUDIT_*`, `DB_PGSQL_GEO_*`, `DB_READ_HOST`/`DB_WRITE_HOST`) to a **non-existent socket path** and a closed port; (3) **before** running the canary, dumps the effective connection config of all connections and **aborts** if any host is reachable, unknown, or a `url` is set. Only then does it run the canary `Tests\TestCase` test with `--no-configuration` and assert refusal **before any query**. A broken guard can only meet unreachable targets. **Positive:** the default suite (`phpunit.xml`) runs with every connection on `nrna_test`. | — | negative: **none reachable**; positive: `nrna_test` |

**Regression after each step (all with G-RUN-DF):**
- `tests/Unit/Testing/TestDatabaseSafetyTest.php`;
- `tests/Architecture/DatabaseResetIsGuardedTest.php`;
- the twelve marked unit tests;
- `tests/Isolated/Pbdigit274/Guard/DatabaseIdentityGuardTest.php`.

The full isolated suite needs PBDIGIT-286's G-ISO-type authorisation, so it's listed but **not** run without it.

## 7. Acceptance (backlog criteria → steps)
| Criterion | Step |
|---|---|
| 1 every connection pinned or verified, including `url`/`DATABASE_URL` | R1 (rules), R2 (pinning) |
| 2 fail-closed before any query; default connection `pgsql` + approved | R3 (with R1 rules) |
| 3 enforced coverage | R1 (marker), R4 (architecture) |
| 4 negative evidence, fail-safe | R6 (G-RUN-NEG) |
| 5 positive evidence | R6 (G-RUN-POS) |
| 6 no reach into audit or geo | R5 |

## 8. Risks
| Risk | Mitigation |
|---|---|
| A provider queries during `RegisterProviders` | the R3 hook runs **after `LoadConfiguration` and before `RegisterProviders`** [V, §2.1]; the database manager isn't registered before that point |
| Marker environment leaks into later tests in a mixed run | R1: restore the previous values after bootstrap (asserted by a test) |
| Shared rules drift between the two guards | R1: one helper used by both; `DatabaseIdentityGuardTest` stays green |
| Isolated suite incompatibility | it already pins all three to `pbdigit274_isolated_test` (in `RESETTABLE`) and blanks `DATABASE_URL` |
| A test author bypasses the marker | R4 architecture test |
| The negative run reaches a real database through a broken guard | R6: cached config refused, every target key neutralised, effective config checked **before** the run |
| Running tests at all after the incident | every run separately gated (§4); R1–R5 use no reachable database |

## 9. Task checklist
- [ ] G-PLAN-283 · [ ] D-283-1 to D-283-4 · [ ] G-RUN-DF
- [ ] R1 · R2 · R3 · R4 · R5 (each with evidence and a developer-guide step under `developer_guide/testing/`)
- [ ] G-RUN-NEG, G-RUN-POS → R6
- [ ] PBDIGIT-283 extension closed → PBDIGIT-286 G-ISO may be considered

## 10. Progress
- **2026-10-14:**
  - r1 drafted after a read-only investigation (`b71137e62`);
  - r2: the corrections F1–F7 approved by the Principal.
- **No code, no runs.**

## 11. Next actions
1. The Principal reviews r2 → G-PLAN-283 and D-283-1 to D-283-4.
2. Then G-RUN-DF, and R1 starts with its failing test.

**Traceability:** PBDIGIT-283 remediation extension (`3fd58af8c`) · PBDIGIT-286 database-safety review (session `.claude/sessions/2026-10-14.md`) · corrections F1–F7 (Principal, 2026-10-14) · HEAD `b71137e62`.
