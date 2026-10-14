# PBDIGIT-283 test-database safety remediation: implementation plan

**Status: CLOSED. PBDIGIT-283 `DONE`, closure approved by the Principal 2026-10-14** (`docs/publicdigit/reviews/2026-10-14-pbdigit283-closure-record.md`). Earlier status: **r2 APPROVED (G-PLAN-283) by the Principal, 2026-10-14, with D-283-1 to D-283-4 (G-DEC-283) and corrections C-1 to C-3.** The approval authorises no execution: no code, no tests (including database-free ones), no Artisan, no database access. G-RUN-DF, G-RUN-NEG and G-RUN-POS each need their own explicit authorisation (§4).

**Revisions:**
- r1 `b71137e62`;
- **r2 (2026-10-14):** the Principal-approved corrections F1–F7 from the read-only review (bootstrap-hook timing verified; `DATABASE_URL`/`url` for every connection; shared rules parameterised by the approved set; the marker before the hook; all 12 tests classified; fail-safe negative evidence; the default connection constrained).
- **r2 approved (2026-10-14):** G-PLAN-283 and G-DEC-283, with C-1 to C-3 from the source-level readiness review: C-1 fixes the G-RUN-DF command (§4); C-2 applies it to the regression runs (§6); C-3 splits R1(b) into a pure part (b1) and a boot-level part (b2) that aborts before boot unless every target is neutralised (§6).

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

**U-5, resolved [V] (readiness review, 2026-10-14):** `tests/Isolated/Pbdigit274/Guard/DatabaseIdentityGuardTest.php` is database-free **by source**:
- it extends `PHPUnit\Framework\TestCase` and never boots Laravel;
- it never calls `DatabaseIdentityGuard::pdoCurrentDatabase()` (the only `new PDO`, `Support/DatabaseIdentityGuard.php:126-133`); every identity lookup is a closure;
- its environment and temp-file side effects are random keys and `tempnam` files, removed in the test.

**Restriction:** its database-freedom holds only under the C-1 command (§4). Run through `phpunit.pbdigit274.xml`, the bootstrap `tests/bootstrap-isolated-database.php:27` opens a real PDO connection **before any test runs**.

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
| **G-PLAN-283** | this plan · **APPROVED 2026-10-14** | everything |
| **G-DEC-283** | decisions D-283-1 to D-283-4 (§5) · **APPROVED 2026-10-14** | the dependent steps |
| **G-RUN-DF** | running **database-free** PHPUnit tests (plain PHPUnit, or marker tests with every connection at a non-existent socket) · **NOT AUTHORISED** | R1–R5 RED/GREEN runs |
| **G-RUN-NEG** | criterion 4: the negative-evidence run (§6 R6) · **NOT AUTHORISED** | R6 negative |
| **G-RUN-POS** | criterion 5: the default suite on `nrna_test` · **NOT AUTHORISED** | R6 positive |

**C-1, the G-RUN-DF command (mandatory):** plain-PHPUnit tests run only as
`vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php <path>`.
**`phpunit.pbdigit274.xml` is prohibited under G-RUN-DF:** its bootstrap opens a PDO connection before any test runs (`tests/bootstrap-isolated-database.php:27`, U-5). Marker tests (R1(b2) onwards) run the same way: the marker, not a PHPUnit configuration, supplies the neutralisation.

**Never permitted:** `nrna`, `publicdigit`, or any shared or development database.

## 5. Decisions (APPROVED by the Principal, 2026-10-14, G-DEC-283: option (a) in each row, D-283-4 with (b) kept; scope unchanged)

| ID | Question | Options | Approved |
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
| **R1 Shared rules + database-free marker** | G-DEC (D-283-1, D-283-3), G-RUN-DF | **(a) Pure rules (F3, F7):** plain-PHPUnit tests of a shared pure helper (e.g. `Tests\Support\DatabaseTargetRules`) **parameterised by the approved set**: identifier syntax and reserved words (moved out of `DatabaseIdentityGuard`, not copied); **a non-empty `url` is refused** for every connection; a cached config is refused; the **default connection must be `pgsql`** with an approved database; any of `pgsql`/`pgsql_audit`/`pgsql_geo` targeting a non-approved database (including the geo default `publicdigit`) is refused; `APP_ENV` other than `testing` is refused. `DatabaseIdentityGuard` keeps its own approved set and forbidden list and calls the helper; its tests (`tests/Isolated/Pbdigit274/Guard/DatabaseIdentityGuardTest.php`) must stay green. **(b) Marker, split by C-3 so RED never boots Laravel with an unneutralised environment** (no reliance on boot being query-free):
- **(b1) Pure, no boot:** unit tests of the marker's neutralisation mapping (`APP_ENV`, `DB_CONNECTION`, `DATABASE_URL`=empty, `DB_*`, `DB_AUDIT_*`, `DB_PGSQL_GEO_*` → non-existent socket under an approved name, per D-283-3) and of restoring the previous `$_ENV`/`$_SERVER`/`putenv` values.
- **(b2) Boot-level, written after (b1) is GREEN:** **before** calling `createApplication()`, the test checks that every target (`DB_*`, `DB_AUDIT_*`, `DB_PGSQL_GEO_*`, `DATABASE_URL`) is neutralised as D-283-3 specifies. If any check fails, it **aborts before boot**, so at RED it fails on that check without booting. Once neutralised, it asserts the booted connections are at the socket and the environment is restored afterwards. | the helper; `DatabaseIdentityGuard` refactored to use it; the marker interface + pre-boot neutralisation in `createApplication()`; **all 12 tests marked**. Each test proves its database-freedom **under the marker**: any query fails loudly against the non-existent socket. A test that turns out to need a database moves to a guarded base instead (F5). | none |
| **R2 Pinning** | R1, D-283-1 | including `tests/bootstrap-test-database.php` (separate process) leaves `DATABASE_URL` empty and `DB_AUDIT_*`, `DB_PGSQL_GEO_*` pinned per D-283-1; `phpunit.xml` and `phpunit-pgsql.xml` carry the same `<server>` entries (F2) | pin in the bootstrap + both XML files | none |
| **R3 Pre-provider hook** | R1, R2, D-283-2 | creating the application with an unsafe configuration (each case: unsafe default database; unsafe audit; unsafe geo; non-empty `DATABASE_URL`; default connection not `pgsql`; `APP_ENV` not `testing`; cached config), **all at non-existent sockets**, throws **before any provider is registered** (a canary provider isn't registered; no query listener fires) | the `afterBootstrapping(LoadConfiguration::class, …)` hook in `createApplication()` calling the R1 helper with the approved set (`TestDatabaseSafety::RESETTABLE`) | none (non-existent sockets only) |
| **R4 Coverage** | R1, R3 | an architecture test fails for any test class that boots Laravel (`CreatesApplication`) without `Tests\TestCase`, an isolated base or the marker | the architecture test (all 12 already marked in R1) | none |
| **R5 No reach into audit or geo** | R1 | an architecture test fails if a migration targets `pgsql_audit`/`pgsql_geo` (`Schema::connection(...)`, `$connection`) without approved pinning; a code-review record covers `migrate:fresh` scope (default connection only) and `ElectionSecurityEvent` | the architecture test + review note in the evidence | none |
| **R6 Evidence** | R1–R5; **G-RUN-NEG**, **G-RUN-POS** | **Negative (D-283-4, fail-safe, F6):** the subprocess (1) **refuses to start** if `bootstrap/cache/config.php` exists or `APP_CONFIG_CACHE` is set; (2) sets **every** key that could select a target (`DB_CONNECTION`, `DATABASE_URL`=empty, `DB_HOST`/`PORT`/`DATABASE`/`USERNAME`, `DB_AUDIT_*`, `DB_PGSQL_GEO_*`, `DB_READ_HOST`/`DB_WRITE_HOST`) to a **non-existent socket path** and a closed port; (3) **before** running the canary, dumps the effective connection config of all connections and **aborts** if any host is reachable, unknown, or a `url` is set. Only then does it run the canary `Tests\TestCase` test with `--no-configuration` and assert refusal **before any query**. A broken guard can only meet unreachable targets. **Positive:** the default suite (`phpunit.xml`) runs with every connection on `nrna_test`. | — | negative: **none reachable**; positive: `nrna_test` |

**Regression after each step (all with G-RUN-DF, and only with the C-1 command, §4: `vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php <path>`; never `phpunit.pbdigit274.xml`) (C-2):**
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
- [x] G-PLAN-283 · [x] D-283-1 to D-283-4 · [x] C-1 to C-3 incorporated (2026-10-14)
- [x] G-RUN-DF (authorised 2026-10-14, C-1 command only) · [x] **R1** (accepted 2026-10-14; guide `developer_guide/testing/01_database_free_tests.md`)
- [x] **R2** (committed `6d394e6e1`; guide `developer_guide/testing/02_default_suite_pinning.md`)
- [x] **R3** (committed `17167dec3`; guide `developer_guide/testing/03_pre_provider_hook.md`)
- [x] **R4** (accepted by the Principal 2026-10-14, committed `b7138fafa`; guide `developer_guide/testing/04_laravel_boot_coverage.md`; scope extended by the Principal: 4 tests migrated, option A)
- [x] **R5** (committed `ac66fd940`; guide `developer_guide/testing/05_no_reach_into_audit_or_geo.md`)
- [x] G-RUN-NEG authorised (2026-10-14) · [x] R6 negative **prepared** (Option A) · [x] R6 negative **executed and accepted** (run 2, `4ce631f39`, 2026-10-14)
- [x] G-RUN-POS **stage 1** authorised (with an explicit permission for `migrate:fresh` on `nrna_test`), run and accepted 2026-10-14 · [x] **stage 2** (Unit) run and accepted 2026-10-14 · [x] **stage 3** (Feature re-run with `memory_limit=1G` + Architecture) run and accepted 2026-10-14
- [x] PBDIGIT-283 extension closed (2026-10-14) → PBDIGIT-286 G-ISO may be considered (separate authorisation)

## 10. Progress
- **2026-10-14:**
  - r1 drafted after a read-only investigation (`b71137e62`);
  - r2: the corrections F1–F7 approved by the Principal (`ba07ee8cd`);
  - readiness review (read-only): `DatabaseIdentityGuardTest` verified database-free by source under the C-1 command (U-5); the R1(b) RED boot risk found;
  - **r2 APPROVED** by the Principal: G-PLAN-283, D-283-1 to D-283-4, and C-1 to C-3 incorporated.
- **2026-10-14, R1 (G-RUN-DF only, C-1 command):**
  - R1(a) `DatabaseTargetRules`: RED 32 errors → GREEN 32; `DatabaseIdentityGuard` uses the shared rules, its test still 71 green;
  - R1(b1) `DatabaseFreeEnvironment`: RED 8 + 4 → GREEN 12;
  - R1(b2) marker in `createApplication()`, outer-net fail-safe: RED interface missing (nothing booted) → GREEN 3 + 1;
  - all 12 tests marked. **Finding:** `VerifyVoterSlugTest` read a database through `gatherMiddleware()` on all routes. Fixed in the test (only `{vslug}` routes, Principal option (a)); production cause → backlog `PBDIGIT-290`;
  - regression: 12 marked + guard 71 + `TestDatabaseSafetyTest` 11 + `DatabaseResetIsGuardedTest` 1, all green;
  - **R1 accepted by the Principal;** committed `164f74066`. No database reached; no Artisan, migration or reset.
- **2026-10-14, R2 (G-RUN-DF, C-1 command):**
  - `DefaultSuitePinningTest`: RED 3 failures → GREEN 3 tests, 50 assertions;
  - pins `DATABASE_URL` (empty) and `DB_AUDIT_*`/`DB_PGSQL_GEO_*` (host, port, database `nrna_test`, username; no passwords) in the bootstrap and both XML files, each mirroring its own `DB_*`;
  - regression: all green;
  - committed `6d394e6e1`. No database reached.
- **2026-10-14, R3 (G-RUN-DF, C-1 command):**
  - hook `afterBootstrapping(LoadConfiguration)` in `createApplication()` → `TestDatabaseSafety::refuseUnsafeConfiguration()` (shared rules + `RESETTABLE` + cached config);
  - `PreProviderHookTest`: RED 7 unsafe configurations booted at dead sockets → GREEN 8 tests (refused with `db` unbound, `AppServiceProvider` not loaded); `TestDatabaseSafetyTest` +2 pure cases (13 tests);
  - regression all green with the hook active;
  - **finding:** R1's `DatabaseFreeEnvironmentTest::test_enter_refuses_a_cached_configuration_and_restores_first` calls `fail()` inside a `catch (RuntimeException)` and its asserted text ("cached") is in the `fail()` message, so it can't detect a missing refusal. **Fixed with R3 on the Principal's instruction** (capture, assert outside the `try`; 12 tests, 134 assertions);
  - committed with R3 (`17167dec3`).
- **2026-10-14, R4 (G-RUN-DF, C-1 command):**
  - `tests/Architecture/LaravelBootIsGuardedTest.php`: only `CreatesApplication` (and the guarded isolated worker) requires `bootstrap/app.php`; every Laravel test case uses the trait's `createApplication()` and has a cover;
  - RED: four plain PHPUnit tests booted Laravel themselves (`VotingEligibilityMiddlewareRegressionTest`, `ConstitutionalTransitionGuardSystemRoleBypassTest`, `TrustPolicyEvaluatorNetworkRestrictionTest`, `OutboxEventAdapterTest`). The outbox one attempts a real INSERT. **Plan deviation, approved by the Principal (option A):** all four boot through `CreatesApplication` as database-free tests;
  - review hardening: the regex detector missed `$this->app =`, `static::$app =`, `include` and split-literal paths (old pattern run over 12 snippets: 4 of 8 executable forms missed). Replaced by a tokenizer detector with 12 snippet cases; check 5 added (anonymous `CreatesApplication` sites must be marked; one deliberate unmarked fixture, `PreProviderHookTest`);
  - GREEN 17 tests (12 snippets + 5 checks); the four tests green; regression green;
  - backlog `PBDIGIT-291` (the outbox test's weak assertion);
  - **accepted by the Principal** after a final source review: no blocking defect. Limits recorded in guide 04 (variable-only paths not detected; check 5 needs `use CreatesApplication;` first in the anonymous body; checks 3–4 read the first class per file). The database-free runs don't prove outbox persistence (PBDIGIT-291) or real-suite safety (R6);
  - committed `b7138fafa`.
- **2026-10-14, R5 (G-RUN-DF, C-1 command):**
  - `tests/Support/ConnectionReferences.php` + `tests/Architecture/NoReachIntoAuditOrGeoTest.php`: no migration in any loaded path (229 files; backups not loaded) names a connection or overrides `getConnection()`; every `loadMigrationsFrom()` points into a scanned root; every configured connection named by `app/` is confined by R3;
  - review note (guide 05): `migrate:fresh` → `db:wipe` drops the default connection only; no test widens it; `ElectionSecurityEvent` → `pgsql_audit` (pinned/confined); `landlord`/`tenant` are named in `app/` but not configured;
  - RED 12 errors (helper missing); the properties already held, so R5 pins them. GREEN 12/43; regression green;
  - committed `ac66fd940`.
- **2026-10-14, R6 negative, PREPARED (not executed):**
  - G-RUN-NEG authorised; preparation found that `config/database.php:140-150` hard-codes a `testing` connection (`127.0.0.1:5432/nrna_test`, user `nrna`, plaintext password in a tracked file) that no environment variable can neutralise, so §6 R6(3) "abort if any host is reachable" could never pass;
  - **Option A (Principal):** a recorded deviation from §6 R6(3). Exactly that connection is accepted (driver, host, port, database and username compared; password never read; no URL; not default). Every other rule stays strict (default, environment-controlled targets, URLs, unknown connections, the `publicdigit` canary). Security follow-up **`PBDIGIT-292`**;
  - artifacts: `tests/Evidence/Pbdigit283/{negative-evidence.php, preflight.php, ResetCanaryTest.php}`, `tests/Support/NegativeEvidence.php` (pure rules), `NegativeEvidenceTest` (RED 34 → GREEN 34/53); `LaravelBootIsGuardedTest` allows `preflight.php` with a check that it loads only environment + configuration (18 tests);
  - **not executed** at preparation; reviewed and committed `eb18ccfa9`. Guide `developer_guide/testing/06_negative_evidence.md`.
- **2026-10-14, R6 negative run 1 (G-RUN-NEG):** **aborted before the canary** (exit 1). The loaded connection set included `mariadb`, merged by Laravel 11 from its framework defaults (the application doesn't use MariaDB). The canary never ran; no connection was attempted. Evidence: `docs/publicdigit/election/evidence/2026-10-14-pbdigit283-r6-negative/run-1-aborted-before-canary.txt`.
- **Correction (Principal-authorised, test first):** `mariadb` added to the known loaded set with strict checks (dead socket, closed port, dead Unix socket, no URL); `DB_URL=''` in the child environment; Option A unchanged. `NegativeEvidenceTest` RED 2 → GREEN 39. **`DB_URL` gap in R1/R2 → backlog `PBDIGIT-293`** (R3 fails closed; no R1/R2 change here). Committed `4ce631f39`.
- **2026-10-14, R6 negative run 2 (G-RUN-NEG), EVIDENCE, accepted by the Principal:**
  - harness exit 0 at `4ce631f39`; pre-run check OK (8 loaded connections, every environment-controlled one at the dead socket and port 1, `testing` = exact Option A, not cached, db manager not bound);
  - the canary (`--no-configuration`) was refused by R3 for `pgsql`/`pgsql_audit`/`pgsql_geo` → `publicdigit` from the `bootstrapped: LoadConfiguration` event, before `RegisterProviders`;
  - no `SQLSTATE` or connection error; the canary body wasn't reached;
  - evidence: `docs/publicdigit/election/evidence/2026-10-14-pbdigit283-r6-negative/run-2.txt`. **Criterion 4 met.**
  - Limits: "no connection attempted" rests on the absence of driver errors plus the unreachable endpoints (not instrumented); Option A stays a recorded deviation until `PBDIGIT-292`.

- **2026-10-14, R6 positive stage 1 (G-RUN-POS):**
  - `vendor/bin/phpunit --configuration phpunit.xml tests/Feature/AuditEventTransactionIsolationTest.php` at `812538b62`: OK, 3 tests, 6 assertions, exit 0;
  - R3 let the pinned configuration through; `TestDatabaseSafety` allowed the actual default database; `migrate:fresh` ran on `nrna_test`; `pgsql_audit` reachable and isolated;
  - **accepted by the Principal.** Evidence: `docs/publicdigit/election/evidence/2026-10-14-pbdigit283-r6-positive/`. Limit: one file only; `pgsql_audit`'s `current_database()` not printed.

- **2026-10-14, R6 positive stage 2 (G-RUN-POS, Unit):**
  - `vendor/bin/phpunit --configuration phpunit.xml --testsuite Unit` at `b6b7d67fa`: exit 2, 4,352 tests, 453 errors, 93 failures, 1,176 risky;
  - **no PBDIGIT-283 refusal, no reset refusal, no dead-socket or "not configured" error;** none of the 190 failing classes among the test files changed since `772d06a5d`;
  - failures grouped as schema/test-data drift, accumulated data (no transaction isolation on PostgreSQL), code/test drift, PHPUnit 11 error-handler risky notices (baseline unknown);
  - **accepted by the Principal** as "PBDIGIT-283 neither refuses nor misdirects the Unit suite". Not evidence that the suite is green.

- **2026-10-14, R6 positive stage 3 (G-RUN-POS):**
  - **Feature:** the first run aborted at 2,928 / 3,482 (84%) on PHP's default 128M `memory_limit` (exit 255; kept as `stage-3-feature.txt`). The authorised re-run `php -d memory_limit=1G vendor/bin/phpunit --configuration phpunit.xml --testsuite Feature` completed 3,482 / 3,482, exit 2, peak 144.5 MB: 1,026 errors, 301 failures, 649 risky;
  - **Architecture:** 246 tests, 5 unrelated failures (one caused by the Principal's uncommitted `VoterSlugStep.php` deletion); `LaravelBootIsGuardedTest`, `NoReachIntoAuditOrGeoTest`, `DatabaseResetIsGuardedTest` passed;
  - **in all runs: 0 PBDIGIT-283 refusals, 0 reset refusals, 0 dead-socket / "not configured" errors;** no failing class among the test files changed since `772d06a5d`; secrets scans clean;
  - **accepted by the Principal.** **Criterion 5 met** in the sense "PBDIGIT-283 neither refuses nor misdirects the default suites"; the suites aren't green.

- **2026-10-14, closure:** closure record `docs/publicdigit/reviews/2026-10-14-pbdigit283-closure-record.md` (criteria 1–6 with evidence; deviations D-1..D-6; follow-ups `PBDIGIT-290`..`293`) **approved by the Principal. PBDIGIT-283 `DONE`.**

## 11. Next actions
1. **Done:** closure approved 2026-10-14.
2. **Backlog items for the unrelated failures** (stage 2 Unit, stage 3 Feature/Architecture, grouped by cause) and for the PHP `memory_limit` (the Feature suite peaks at 144.5 MB), if the Principal wants them.

**Traceability:** PBDIGIT-283 remediation extension (`3fd58af8c`) · PBDIGIT-286 database-safety review (session `.claude/sessions/2026-10-14.md`) · corrections F1–F7 (Principal, 2026-10-14) · approval of r2 + D-283-1..4 + C-1..C-3 (Principal, 2026-10-14) · r2 baseline `ba07ee8cd`.
