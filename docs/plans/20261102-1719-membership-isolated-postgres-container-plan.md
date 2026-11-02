# Plan: isolated PostgreSQL test container for the Membership suite (PBDIGIT-324, roadmap W2)

**Status:** **APPROVED by the PO, 2026-11-02 (G-PLAN: "approve the plan and commit").** Nothing is implemented yet. *(Was: PROPOSED, awaiting PO approval.)* Approval of this plan authorises §5 steps 1–5, which are database-free code and tests. **It does not authorise step 6** (pulling an image, starting a container, any database run). That needs **G-RUN**.
**Story:** `docs/publicdigit/backlog/PBDIGIT-324-membership-isolated-postgres-test-container.md`
**Decision basis:** W2-1 = C (PO, 2026-11-02). Roadmap W2 (`…20261016_2219_…_completion_plan.md:153, :176, :194`); BR-20 / ARB-SEC-01.
**Baseline:** HEAD `f2bb00695`. Facts below were read at this baseline.

## 1. Objective

Run `tests/Feature/Contexts/Membership/Organisational/` on a PostgreSQL cluster that **exists only for one run**. The run must be:
- refused before anything connects if its target is anything else;
- evidenced with an identity marker.

This is the P-DB of roadmap W2. I-1 holds by design: the run's credentials exist only inside the container.

## 2. Facts (verified at the baseline)

| # | Fact | Source |
|---|---|---|
| F1 | The four held tests extend `Tests\TestCase` and use `DB::connection()` | `tests/Feature/Contexts/Membership/Organisational/Postgres*.php` |
| F2 | `Tests\TestCase` resets with `RefreshDatabase` (`migrate:fresh` on PostgreSQL). Before any reset it calls `TestDatabaseSafety::assertResettable` | `tests/TestCase.php:18, :31-34` |
| F3 | The reset allow-list is `RESETTABLE = ['nrna_test', DatabaseIdentityGuard::APPROVED_NAME]`, an exact match | `tests/Support/TestDatabaseSafety.php:20` |
| F4 | The PBDIGIT-274 bootstrap checks, in order: the name is well formed; credentials come from `PBDIGIT274_DB_*` (never `DB_*` or `.env`); no config cache; the name is the approved one; a raw PDO connection reports `current_database()` equal to it. Then it pins **all** PostgreSQL connections | `tests/bootstrap-isolated-database.php`; `tests/Isolated/Pbdigit274/Support/DatabaseIdentityGuard.php:42-214` |
| F5 | The guard's environment names are constants: `ENV_NAME = 'PBDIGIT274_TEST_DB'` and `PBDIGIT274_DB_*` in `connectionSettings`. `approvedName()` already takes an `$approvedName` parameter | `DatabaseIdentityGuard.php:25, 27, 59, 91-101` |
| F6 | The confined connections are `pgsql`, `pgsql_audit` and `pgsql_geo` | `DatabaseTargetRules::CONNECTIONS` (`tests/Support/DatabaseTargetRules.php:18`) |
| F7 | No migration needs a PostgreSQL extension (no `CREATE EXTENSION`, PostGIS, ltree, pgcrypto or citext found) | `grep` over `database/migrations` |
| F8 | `podman` is installed; no `postgres` image is present locally. PHP has `pdo_pgsql` and `pcntl` (the race test needs `pcntl`) | `which podman`; `podman images`; `php -m` |

## 3. Design

### 3.1 One guard, two profiles ("no second guard")

**Generalise `DatabaseIdentityGuard`** with a small **profile**: the environment-variable name for the database, the credential prefix, and the approved name.
- The existing static API keeps its current defaults. That is the PBDIGIT-274 profile, unchanged.
- The Membership profile is:
  - `MEMBERSHIP_PG_TEST_DB`;
  - `MEMBERSHIP_PG_DB_HOST`, `_PORT`, `_USERNAME`, `_PASSWORD`;
  - the approved name `membership_pg_isolated`.
- The class stays where it is. Moving it would churn PBDIGIT-274.
- Its existing tests (`DatabaseIdentityGuardTest`) must stay green **unchanged**. That is the characterisation proof that PBDIGIT-274 is unaffected.

### 3.2 Server-identity marker (new check, same guard)

- The script captures the container cluster's `system_identifier` (`SELECT system_identifier FROM pg_control_system()`) immediately after start, and passes it as `MEMBERSHIP_PG_SYSTEM_ID`.
- The Membership bootstrap verifies, over the raw PDO connection, both `current_database()` **and** `system_identifier`. On any mismatch, or a missing value, it refuses.
- This is an **optional** guard step; PBDIGIT-274 does not use it. It proves the run reached this throwaway cluster, not merely a database with the right name.

### 3.3 Reset allow-list

- `TestDatabaseSafety::RESETTABLE` gains the Membership approved name. It must be an **exact** match: `membership_pg_isolated_x` and `membership_pg` are refused.
- This is the only change to PBDIGIT-283's guard. `TestDatabaseSafetyTest` gains the positive case and the near-name refusals.

### 3.4 Configuration

**`phpunit.membership-pg.xml`:**
- `bootstrap="tests/bootstrap-membership-pg.php"`;
- one testsuite, `tests/Feature/Contexts/Membership/Organisational`;
- the same side-effect pins as `phpunit.pbdigit274.xml` (array mail, sync queue, array session and cache);
- **no `DB_*` values** in the file.

**`tests/bootstrap-membership-pg.php`:** the PBDIGIT-274 bootstrap sequence with the Membership profile, plus §3.2.

### 3.5 Container script: `scripts/testing/membership-pg.sh`

| Concern | Rule |
|---|---|
| Image | `docker.io/library/postgres:<major>`, **pinned by digest** after the first pull (G-RUN). `<major>` is W2-2 |
| Network | `-p 127.0.0.1::5432`: a random host port, **loopback only**. The script refuses to run if the host is not `127.0.0.1` |
| Credentials | a random password per run (`openssl rand`), held only in the process environment. It is **never printed, logged or written to disk** |
| Database | `POSTGRES_DB=membership_pg_isolated`, created by the image |
| Lifecycle | start → wait for `pg_isready` → capture `system_identifier` → run `vendor/bin/phpunit -c phpunit.membership-pg.xml` → **always** stop and remove (`trap … EXIT`) |
| Evidence | output, exit code, image digest, `server_version`, `system_identifier`, commit, and a SHA256SUMS, written to a run folder |
| Refusals | refuse if `bootstrap/cache/config.php` exists, if an `APP_CONFIG_CACHE` value is set, or if `podman` is missing. Never fall back to `DB_*` |

## 4. Risks

| Risk | Mitigation |
|---|---|
| Version drift from production | W2-2 pins the major version to production; the run records `server_version` |
| PBDIGIT-274 behaviour changes through the generalisation | its guard tests run unchanged (AC-1). Defaults are kept; no rename or move |
| A credential leaks into evidence | the password is never echoed; the evidence lists are written by the script; a test checks the script never echoes the password variable (AC-6) |
| The suite reveals real PostgreSQL defects | out of scope: report them and stop; they go to PBDIGIT-264 Part 2 |
| Rootless podman port or cgroup issues | stop and report; fall back to option A only by a new PO decision |
| `RefreshDatabase` on a fresh cluster is slow | acceptable: the cluster is new on every run anyway |

## 5. Steps (TDD; RED before GREEN; each step stops on surprise)

| # | Step | RED | GREEN | Database? |
|---|---|---|---|---|
| 1 | Guard profile | new `DatabaseIdentityGuard` tests for the Membership profile: name, env, credential prefix, approval; the PBDIGIT-274 tests unchanged | profile parameters with PBDIGIT-274 defaults | no |
| 2 | Server-identity check | tests: a missing ID is refused; a mismatch is refused; a match passes. The PDO call is replaced by a closure, as `verifyIdentity` does today | `verifySystemIdentifier` | no |
| 3 | Reset allow-list | `TestDatabaseSafetyTest`: the Membership name is resettable; near names are refused | `RESETTABLE` + 1 | no |
| 4 | Config + bootstrap | a database-free test that the bootstrap **refuses** (exit ≠ 0, no connection) when: the name is missing; the name is wrong; credentials are missing; a config cache is present; the system ID is missing. Run as a subprocess with a scrubbed environment | `phpunit.membership-pg.xml`, `tests/bootstrap-membership-pg.php` | no |
| 5 | Script | `bash -n`; a test that the script contains no `echo` / `printf` of the password variable, binds only `127.0.0.1`, and has an EXIT trap that removes the container | `scripts/testing/membership-pg.sh` | no |
| 6 | **G-RUN:** first run | the fail-closed proof against the live container: a wrong `MEMBERSHIP_PG_TEST_DB` is refused; a wrong `MEMBERSHIP_PG_SYSTEM_ID` is refused. Then the Membership PostgreSQL suite | evidence folder `docs/publicdigit/membership/evidence/<date>-w2-membership-pg/` | **yes (container only)** |

**After each of steps 1–5:**
- the existing database-free regression runs: Membership `Organisational` suites, the 7 architecture files, `tests/Unit/Testing`, and the PBDIGIT-274 guard tests;
- a developer-guide step (`developer_guide/membership/11_isolated_postgres_container.md`).

## 6. Acceptance criteria

| AC | Criterion |
|---|---|
| AC-1 | `DatabaseIdentityGuardTest` and `TestDatabaseSafetyTest` pass **unchanged** for PBDIGIT-274 and `nrna_test`, plus the new cases |
| AC-2 | the Membership bootstrap refuses, with no connection attempted, for: missing name; wrong name; forbidden `nrna_test`; missing credentials; config cache; missing system ID |
| AC-3 | with a live container: a wrong system ID is refused **after** connecting, before any migration (step 6) |
| AC-4 | the reset allow-list accepts exactly `membership_pg_isolated` in addition to the existing two |
| AC-5 | all three PostgreSQL connections are pinned to the container database (`assertConnectionsConfined`) |
| AC-6 | no credential appears in any output, log or evidence file; the script never echoes the password variable |
| AC-7 | the container is removed after every run, including a failed run; `podman ps -a` shows no leftover |
| AC-8 | step 6 evidence: commit, image digest, `server_version`, `system_identifier`, suite output and exit code, SHA256SUMS |
| AC-9 | no application, schema, migration or `phpunit.xml` change (`git diff --stat` is limited to the files in §3) |

## 7. Gates and owners

| Gate | Content | Owner |
|---|---|---|
| G-PLAN | approve this plan (authorises steps 1–5) | PO |
| W2-2 | the production PostgreSQL major version (read-only) | Ops / PO |
| G-RUN | pull `postgres:<major>`, pin its digest, start the container (step 6) | PO |
| G-ACC | acceptance of PBDIGIT-324 on the evidence; independent review | PO; reviewer |

## 8. Traceability

- Roadmap W2, P-DB, I-1, BR-20, the fail-closed proof (`:194`); ARB-SEC-01 gate evidence (`2026-10-09-membership-human-arb-decision-record.md:33-50`).
- PBDIGIT-283 closure record; PBDIGIT-274 guard guide `developer_guide/election/voter_slug/03_isolated_database_guard.md`.
- W2 brief (conversation, 2026-11-02); W2-1 = C.

## 9. Progress

- 2026-11-02: plan prepared; awaiting G-PLAN.
- 2026-11-02: **G-PLAN approved by the PO.** Steps 1–5 are authorised (database-free). Step 6 still needs G-RUN and W2-2.
- 2026-11-02: **Steps 1–5 implemented (TDD, database-free).**
  - **Step 1** (guard profile):
    - RED: the profile class was missing, so 20 errors. With the class in place: 3 assertion failures (message names, credential prefix) plus 11 errors for step 2;
    - `approvedName` already took the approved name as a parameter, so those cases were characterisations;
    - GREEN: optional `$envName` / `$prefix` parameters whose defaults are PBDIGIT-274's.
  - **Step 2** (`verifySystemIdentifier` + `pdoSystemIdentifier`): GREEN; 20 tests in `MembershipPgIsolationTest`.
  - **Step 3** (reset allow-list): RED 1 error + 1 failure; GREEN; +6 cases in `TestDatabaseSafetyTest`.
  - **Step 4** (`phpunit.membership-pg.xml`, `tests/bootstrap-membership-pg.php`): RED 15 failures; GREEN 15 (`MembershipPgBootstrapTest`).
  - **Step 5** (`scripts/testing/membership-pg.sh`): RED 8 failures; GREEN 8 (`MembershipPgScriptTest`).
- **Deviations:**
  - (a) `chmod +x` was refused by the session's permission settings. The script is run as `bash scripts/testing/membership-pg.sh`, and the executable-bit check was dropped from the test.
  - (b) The test helper was first named `run()`, which collides with PHPUnit's final method; renamed `runScript()`.
  - (c) The first loopback regex also matched `pg_isready -p 5432` inside the container; narrowed to publish mappings `…:5432`.
  - (d) A self-check found the "never printed" regex did not cover `$SECRET`; tightened, and a planted `echo "$SECRET"` mutant is now caught (in a scratch copy).
  - (e) The main working tree has a cached Laravel configuration (`bootstrap/cache/config.php`, dated 2026-10-19, git-ignored, not created by this work). The bootstrap tests and some existing PBDIGIT-283 boot tests therefore report `[config_cached]` there. All evidence below was taken in a **clean worktree**. The cache file was **not** removed (not this work's to remove).
- **Regression, in a clean detached worktree at `009691140` with the changed files copied in:**
  - `tests/Unit/Testing` 160 OK (HEAD alone: 111; the same pre-existing deprecations);
  - PBDIGIT-274 guard 85 OK (85 before and after the change, AC-1);
  - Membership Domain 117, Application 61, Infrastructure 13, architecture 97: all OK.
  - No container was started (`podman ps -a`: none).
- **AC status:** AC-1, AC-2, AC-4 and AC-6 (static) met. AC-3, AC-5, AC-7 and AC-8 need step 6. AC-9 is met (only the §3 files plus tests and docs changed).
- 2026-11-02: **Step 6 done** (PO: "run step 6 from a clean copy").
  - **Version:** W2-2 = **PostgreSQL 15, provisional** (the PO's choice; it matches CI; open until Ops confirms production).
  - **Image:** `postgres:15@sha256:7da5d534…a822`, PostgreSQL 15.19.
  - **Clean worktree:** at `f84653283`, with no config cache.
  - **Fail-closed proof (live):** a wrong system ID gives `[system_id_mismatch]` (after connecting, before migration); a wrong name gives `[not_approved]`; correct values are confirmed.
  - **Suite runs:** run1 and run2 on fresh clusters, **43 tests, 256 assertions, exit 0**. One deprecation, not Membership (`config/database.php:66`, `PDO::MYSQL_ATTR_SSL_CA`, PHP 8.5).
  - **Hygiene:** no credential in the evidence; no leftover container; worktree removed.
  - **Evidence:** `docs/publicdigit/membership/evidence/2026-11-02-w2-membership-pg/`.
- **AC status after step 6:**
  - AC-1…AC-9 met, with W2-2 provisional;
  - AC-6 is also checked at runtime (the evidence self-check);
  - AC-8: the commit, image digest, `server_version`, `system_identifier`, outputs, exit codes and SHA256SUMS are archived.
- **Remaining:** G-ACC (independent review, then the PO's acceptance); W2-2 confirmation.
