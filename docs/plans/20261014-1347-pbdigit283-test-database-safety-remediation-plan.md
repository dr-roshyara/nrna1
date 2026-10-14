# PBDIGIT-283 test-database safety remediation: implementation plan

**Status: DRAFT, awaiting EP-01 approval.** It authorises nothing: no code, no tests (including database-free ones), no database access. Every run needs its own authorisation (§4).

**Created:** 2026-10-14 at the Principal's request ("plan the PBDIGIT-283 remediation") · **Backlog:** `docs/publicdigit/backlog/PBDIGIT-283-test-database-reset-guard.md`, "Remediation extension" (`3fd58af8c`) · **Related:** PBDIGIT-286 plan §13 (this remediation **blocks PBDIGIT-286 G-ISO**) · **Supersedes:** none.

**Evidence labels:** **[V]** verified in source (read-only) · **[I]** inference · **[U]** not verified.

---

## 1. Objective
- **No test can touch a non-test database,** however it's run: with `phpunit.xml`, with another configuration, or with `--no-configuration`.
- **On every connection** the application can use: `pgsql`, `pgsql_audit`, `pgsql_geo`.
- **Refuse before any database query.**

## 2. Background (read-only investigation, 2026-10-14)

### 2.1 What exists [V]
- **`Tests\TestCase`** applies `RefreshDatabase` (`tests/TestCase.php:18`); on PostgreSQL that runs `migrate:fresh` (`:49-55`).
- **The guard:** `setUpTraits()` (`:28-40`) checks `current_database()` of the **default** connection against `TestDatabaseSafety::RESETTABLE` (`nrna_test`, `pbdigit274_isolated_test`). It does so **after** Laravel has booted, and only for tests using reset traits.
- **Default-suite pinning:** `tests/bootstrap-test-database.php` and `phpunit.xml` pin **only** `DB_*`.
- **The other connections aren't pinned:**
  - `pgsql_audit` → `env('DB_AUDIT_DATABASE', env('DB_DATABASE'))` (`config/database.php:99-105`);
  - **`pgsql_geo` → `env('DB_PGSQL_GEO_DATABASE', 'publicdigit')`** (`:113-119`). **Its default is the development database that was wiped.**
- **Users of the extra connections:** only `ElectionSecurityEvent` uses `pgsql_audit` (`app/Models/ElectionSecurityEvent.php:21`). Nothing in `app`, `database`, `config` or `routes` uses `pgsql_geo` by name.
- **The isolated suite** (`tests/bootstrap-isolated-database.php`, `tests/Isolated/Pbdigit274/Support/DatabaseIdentityGuard.php`) already confines all three connections (`CONFINED_CONNECTIONS`, prefixes `DB_`, `DB_AUDIT_`, `DB_PGSQL_GEO_`), refuses a cached config, and runs **before** Laravel exists. **This is the canonical building block to reuse** (ES-005.4: consume or extend, never duplicate).
- **Booting Laravel:** `Tests\CreatesApplication::createApplication()` is `require bootstrap/app.php` + `Kernel::bootstrap()`. The console kernel loads the configuration (`vendor/.../Console/Kernel.php:121`) **before** registering and booting providers (`:125-126`), and `Application::afterBootstrapping()` exists (`vendor/.../Foundation/Application.php:380`).
- **Twelve unit tests boot Laravel through `CreatesApplication` outside `Tests\TestCase`:**
  - **Nine** move the default connection to a non-existent socket in `setUp()` (after boot): `TrustedProxyTest`, `VoterSlugStepTenantScopeTest`, `ElectionIpWhitelistTest`, `VoterSlugIssuerTest`, `DurationReaderRegressionTest`, `IpVoteLimitTest`, `VoterSlugServiceFixedLifetimeTest`, `VoterStepTrackingServiceTenantTest`, `VoterSlugsActiveIndexMigrationTest`.
  - **Three neutralise nothing:** `tests/Unit/Http/Middleware/VerifyVoterSlugTest.php`, `tests/Unit/Services/Voting/VoterSlugRouteLookupTest.php`, `tests/Unit/Services/Voting/VoterSlugLifetimeTest.php`. Whether they query is [U].
  - **None neutralises `pgsql_audit` or `pgsql_geo`.**

### 2.2 Most likely mechanism of the 2026-10-13 wipe (high confidence; not reproduced)
`--no-configuration --bootstrap vendor/autoload.php` skipped both pins, so Laravel used the `.env` database, and `RefreshDatabase` ran `migrate:fresh` on it.

### 2.3 Unknowns
- **U-1:** which databases `pgsql_audit`/`pgsql_geo` reach in default-suite runs (depends on `.env`; not read);
- **U-2:** whether the three unneutralised tests query;
- **U-3:** the full default-suite run with the guard;
- **U-4:** reset or migration reach into the audit and geo databases.

## 3. Scope
**In scope:** backlog acceptance criteria 1–6:
- pinning or verifying every connection;
- a fail-closed check before any query;
- enforced coverage;
- negative and positive evidence (runs separately authorised);
- no reach into audit or geo.

**Non-goals:**
- no change to production database configuration or `.env`;
- no change to `RESETTABLE` beyond an explicit decision (D-283-1);
- no change to PBDIGIT-286 code;
- no data recovery or inspection.

## 4. Gates (each separate)

| Gate | What it approves | Blocks |
|---|---|---|
| **G-PLAN-283** | this plan | everything |
| **G-DEC-283** | decisions D-283-1 to D-283-4 (§5) | the dependent steps |
| **G-RUN-DF** | running **database-free** PHPUnit tests (plain PHPUnit, or a dead-socket or pre-neutralised base). This is listed separately because the Principal stopped all PHPUnit runs until this remediation is reviewed. | R1–R5 RED/GREEN runs |
| **G-RUN-NEG** | criterion 4: the negative-evidence run (§6 R6) | R6 negative |
| **G-RUN-POS** | criterion 5: the default suite on `nrna_test` (as PBDIGIT-286 G-AUTH-T) | R6 positive |

**Never permitted:** `nrna`, `publicdigit`, or any shared or development database.

## 5. Decisions needed (recommendations; not approved)

| ID | Question | Options | Recommendation |
|---|---|---|---|
| **D-283-1** | Where do `pgsql_audit` and `pgsql_geo` point in the default suite? | (a) the same test database as the default connection (`nrna_test`); (b) dedicated test databases (needs creation by operations) | **(a).** The isolated suite already confines all three to its single database; same approach, no new databases |
| **D-283-2** | Where does the fail-closed check run? | (a) a hook in `CreatesApplication`: `$app->afterBootstrapping(LoadConfiguration::class, …)`, after configuration is loaded and **before** any provider registers or boots, so before any application query; (b) only in the PHPUnit bootstrap file (skipped under `--no-configuration`); (c) in `Tests\TestCase::setUpTraits()` (after boot, as today) | **(a)**, because it runs on **every** Laravel-booting test, whatever the PHPUnit configuration. The pin in the bootstrap file (R2) stays as a second layer |
| **D-283-3** | How do database-free tests declare themselves? | (a) a marker (interface or trait, e.g. `Tests\Support\DatabaseFreeTest`) that makes the hook point **all three** connections at a non-existent socket **before** providers boot, replacing the per-test `setUp()` dead-socket code; (b) keep per-test `setUp()` code and enforce it by an architecture test | **(a):** one mechanism; neutralises before boot, not after |
| **D-283-4** | How is the negative evidence (criterion 4) produced safely? | (a) a subprocess runs a canary `Tests\TestCase` test with `--no-configuration` and an environment whose every connection points at a **non-existent host or socket** under a non-approved name, so even a guard failure can't reach a real database; it asserts refusal before any query; (b) an end-to-end run against a scratch SQLite file (as in the original 283 evidence) | **(a)** for PostgreSQL realism, plus (b) kept as is |

## 6. Test-first phases
Each step runs **RED → GREEN → REFACTOR → REGRESSION → REVIEW**, with a stop for review between steps.

| Step | Prerequisites | RED (first failing test) | Minimal GREEN | Database |
|---|---|---|---|---|
| **R0** | G-PLAN-283 | — | decisions recorded | — |
| **R1 Pure check** | G-DEC (D-283-1), G-RUN-DF | `TestDatabaseSafety` gains a pure `assertConnectionsSafe(array $connections, ?string $appEnv)`, tested with plain PHPUnit. Refuses: `APP_ENV` other than `testing`; any of `pgsql`/`pgsql_audit`/`pgsql_geo` targeting a non-approved database (including the geo default `publicdigit`); a cached config. Accepts the approved set. | the pure method, reusing `DatabaseIdentityGuard`'s name rules and `refuseIfConfigCached` where they apply, without copying them | none |
| **R2 Pinning** | R1, D-283-1 | including `tests/bootstrap-test-database.php` (in a separate process) leaves `DB_AUDIT_DATABASE` and `DB_PGSQL_GEO_DATABASE` pinned per D-283-1 | pin `DB_AUDIT_*` and `DB_PGSQL_GEO_*` in the bootstrap (and `phpunit.xml` / `phpunit-pgsql.xml` `<server>` entries) | none |
| **R3 Pre-provider hook** | R1, D-283-2 | creating the application with an unsafe environment (every connection at a non-existent socket under a non-approved name) throws **before** any provider boots (a canary provider isn't booted; no query listener fires) | `CreatesApplication` registers `afterBootstrapping(LoadConfiguration::class, …)` calling R1 | none (non-existent sockets only) |
| **R4 Database-free marker** | R3, D-283-3 | an architecture test fails: three tests (`VerifyVoterSlugTest`, `VoterSlugRouteLookupTest`, `VoterSlugLifetimeTest`) boot Laravel without a guarded base and without the marker | the marker + hook neutralisation of all three connections; move the 12 unit tests to the marker (their `setUp()` dead-socket code is removed deliberately) | none |
| **R5 No reach into audit or geo** | R1 | an architecture test fails if a migration targets `pgsql_audit`/`pgsql_geo` (e.g. `Schema::connection(...)`, `$connection`) without the approved pinning; a code-review record covers `migrate:fresh` scope (default connection only) and `ElectionSecurityEvent` | the architecture test + review note in the evidence | none |
| **R6 Evidence** | R1–R5; **G-RUN-NEG**, **G-RUN-POS** | **Negative:** per D-283-4, refused before any query, nothing reachable. **Positive:** the default suite (`phpunit.xml`) runs against `nrna_test` for every connection. | — | negative: none (non-existent hosts); positive: `nrna_test` |

**Regression after each step (all with G-RUN-DF):**
- `tests/Unit/Testing/TestDatabaseSafetyTest.php`;
- `tests/Architecture/DatabaseResetIsGuardedTest.php`;
- the twelve unit tests;
- the isolated suite's guard tests (`tests/Isolated/Pbdigit274/Guard/DatabaseIdentityGuardTest.php`).

The full isolated suite needs PBDIGIT-286's G-ISO-type authorisation, so it's listed here but **not** run without it.

## 7. Acceptance (backlog criteria → steps)
| Criterion | Step |
|---|---|
| 1 every connection pinned or verified | R1, R2 |
| 2 fail-closed before any query | R3 |
| 3 enforced coverage | R4 |
| 4 negative evidence | R6 (G-RUN-NEG) |
| 5 positive evidence | R6 (G-RUN-POS) |
| 6 no reach into audit or geo | R5 |

## 8. Risks
| Risk | Mitigation |
|---|---|
| A service provider queries during `RegisterProviders` | the R3 hook runs **after LoadConfiguration and before RegisterProviders** [V order]. A provider querying in `register()` would run after the check, which is acceptable because the check has already passed or refused |
| Isolated suite incompatibility | the isolated suite already pins all three connections to `pbdigit274_isolated_test`, which is in `RESETTABLE`. R3 must accept it (regression via `DatabaseIdentityGuardTest`) |
| A test author bypasses the marker | the R4 architecture test |
| The hook breaks tests that legitimately change connections after boot (dead-socket `setUp()`) | R4 replaces those with the marker in the same step |
| Running tests at all after the incident | every run is separately gated (§4); R1–R5 use no reachable database |

## 9. Task checklist
- [ ] G-PLAN-283 · [ ] D-283-1 to D-283-4 · [ ] G-RUN-DF
- [ ] R1 · R2 · R3 · R4 · R5 (each with evidence and a developer-guide step under `developer_guide/testing/`)
- [ ] G-RUN-NEG, G-RUN-POS → R6
- [ ] PBDIGIT-283 extension closed → PBDIGIT-286 G-ISO may be considered

## 10. Progress
- **2026-10-14:** drafted after a read-only investigation. No code, no runs.

## 11. Next actions
1. The Principal reviews this plan → G-PLAN-283 and D-283-1 to D-283-4.
2. Then G-RUN-DF, and R1 starts with its failing test.

**Traceability:** PBDIGIT-283 remediation extension (`3fd58af8c`) · PBDIGIT-286 database-safety review (session `.claude/sessions/2026-10-14.md`) · HEAD `eebc96adb`.
