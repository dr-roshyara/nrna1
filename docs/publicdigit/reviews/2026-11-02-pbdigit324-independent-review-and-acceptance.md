# PBDIGIT-324: independent acceptance review and PO verdict (2026-11-02)

**Status:**
- §2 is the review report, recorded **verbatim** as returned.
- §3 is the **PO verdict: ACCEPTED WITH EXPLICIT RESIDUAL RISKS**, conditional on W2-2.

**Reviewer:** a fresh general-purpose Claude sub-agent, commissioned on 2026-11-02 by session `93000efc-ad03-4c1d-9b3f-40966981efc3` on the PO's instruction ("review PBDIGIT-324 for acceptance").
- It was read-only on the repository.
- It ran tests in its own clean worktree.
- It used containers only from the pinned image `postgres:15@sha256:7da5d534…a822`.

**Reviewed at:** commits `82184d62a` (steps 1–5) and `ae9e46768` (step 6), at HEAD `2b204b003` → `2952bef29` (docs-only movement during the review).

**Spot-checked** by the commissioning session:
- `assertConnectionsConfined` / `withoutUnconfinedPostgresConnections` were called only from the PBDIGIT-274 bases, not on the Membership path;
- `config/database.php:140-144` defines `testing` → `nrna_test`;
- guide 11 `:95` was wrong;
- no container was left.

## 1. Commission (summary)

AC-1…AC-9; isolation and safety; PBDIGIT-274 unaffected; script; test strength (mutants in a scratch copy only); evidence; documentation consistency. Report sections A–F.

## 2. Report (verbatim)

### A. Verdict recommendation
**ACCEPT WITH EXPLICIT RESIDUAL RISKS** — conditional on W2-2 (re-run step 6 on the production major version if it is not 15, as the README already commits). The isolation design is sound and fails closed on every path tested or read; PBDIGIT-274 behaviour is byte-for-byte preserved; I reproduced the suite myself on a fresh cluster (43 tests / 256 assertions, exit 0) and every evidence hash verifies. Not unconditional because AC-5 names a check (`assertConnectionsConfined`) that the Membership path never runs, the guide states a false fact about `pg_control_system()`, and several static script/allow-list tests have blind spots — none blocking.

### B. AC-1…AC-9

| AC | Status | Evidence |
|---|---|---|
| AC-1 | **Met** | VERIFIED: in a clean worktree the 274 guard tests ran 85/85 OK with the old guard (`82184d62a^`) swapped in and with the new one; `git diff 82184d62a^ HEAD -- tests/Isolated` touches only `Support/DatabaseIdentityGuard.php` (guard tests unchanged). `TestDatabaseSafetyTest` 19 OK, diff only adds cases. |
| AC-2 | **Met** | VERIFIED: `MembershipPgBootstrapTest` 15/15 OK (child process, `env -i`): 12 refusals (missing, empty, reserved, nrna_test forbidden, malformed, no creds, 274-only creds, config cache, wrong name, near name, system-ID missing/malformed), asserting no `[connection_failed]`, host = nonexistent socket dir. Nuance: config-cache case tests only the `APP_CONFIG_CACHE` override; the file branch relies on shared `DatabaseTargetRules` + the script's own refusal. |
| AC-3 | **Met** | REPORTED in `proof/` (hashes verified): `[system_id_mismatch]` exit 1; VERIFIED by code that it happens after connect and before any migration (`tests/bootstrap-membership-pg.php:59`, runs before Laravel boots). |
| AC-4 | **Met, weak test** | VERIFIED `TestDatabaseSafety.php:23` exactly three names; near names refused. Mutant: adding a 4th name `membership_pg_isolated_dev` → all tests still green (nothing pins "exactly"). |
| AC-5 | **Partial** | VERIFIED pinning: bootstrap does `applyEnvironment(environmentFor(...))` (`bootstrap-membership-pg.php:66`) → `DB_`/`DB_AUDIT_`/`DB_PGSQL_GEO_` + `DATABASE_URL=''`, and the live run shows the only endpoint used was the container. **But `assertConnectionsConfined` is never called on the Membership path** (grep: only `IsolatedDatabaseTestCase`, `IsolatedCommittedDatabaseTestCase`, `g6-worker.php`), and the hard-coded `testing` connection (`config/database.php:140-150`, `127.0.0.1:5432/nrna_test`) is not dropped. The only config-level check is the PBDIGIT-283 hook, which accepts any of the three allow-listed names. Mutant: deleting `applyEnvironment` from the bootstrap → all database-free tests still green. |
| AC-6 | **Met** | VERIFIED: no credential in any evidence file (scanned 32+/48-hex tokens and password assignments); script passes the password by name (`membership-pg.sh:56-59`) and has a runtime evidence check (`:111`). Limits: readable via `podman inspect` (VERIFIED `Config.Env`) to the same user while the container lives; static test regex does not catch `env > file` (mutant survived). |
| AC-7 | **Met** | VERIFIED by my own runs: after a successful run, a failed run (phpunit exit 2) and a SIGTERM mid-suite, `podman ps -a` was empty each time. SIGKILL cannot be handled (inherent). |
| AC-8 | **Met** | VERIFIED: `run.txt` has commit, image, digest, server_version, system_identifier; `suite.txt`/`suite.exit`/SHA256SUMS; `sha256sum -c` OK for run1, run2, proof. Evidence commit `f84653283` has no diff to HEAD in tests/scripts/config/app/database/phpunit xml/composer.lock. |
| AC-9 | **Met** | VERIFIED: the code diff `ac80fc373..ae9e46768` is only the §3 files + profile + 4 test files + docs/evidence; nothing in `app/`, `config/`, `database/`, `phpunit.xml`. |

W2-2 (PostgreSQL 15.19, matching CI) is explicitly provisional — acceptance should be conditional on it, not counted as a defect.

### C. Findings

| ID | Sev | Location | Description | Disposition |
|---|---|---|---|---|
| F1 | major (doc/AC) | plan §6 AC-5; evidence `README.md` "Supports" | AC-5 names `assertConnectionsConfined` but the Membership path does not call it; the README claims AC-5 on `environmentFor` alone. `withoutUnconfinedPostgresConnections` is not applied either, so the `testing` connection (→ `nrna_test`, `127.0.0.1:5432`) stays reachable by name. INFERRED low practical risk (no Membership test/app code names `testing`). | PO: either reword AC-5 ("pinned via `environmentFor`") and accept, or backlog a confinement check (e.g. the PBDIGIT-274 `setUp` pattern in a Membership base, or an in-run `system_identifier` assertion). |
| F2 | minor (doc error) | `developer_guide/membership/11_isolated_postgres_container.md:95` | "`pg_control_system()` needs a superuser" — **false on PG 15**: a fresh non-superuser role has EXECUTE and read `system_identifier` (VERIFIED in a throwaway container). Safety unaffected (a mismatch or revoked privilege still fails closed). | Correct the guide. |
| F3 | minor | `tests/Support/TestDatabaseSafety.php:23` | The allow-list is name-only and global to all phpunit configs: any run whose actual `current_database()` is `membership_pg_isolated` on any server will be reset (e.g. `--no-configuration` + `.env`), with no `system_identifier` binding. Same class as the existing `pbdigit274_isolated_test` entry. Default `phpunit.xml` pins `nrna_test` via `<server>`, so a plain default run cannot reach it. | Accept as residual, or backlog: allow this name only when `MEMBERSHIP_PG_SYSTEM_ID` is set. |
| F4 | minor (test strength) | `tests/Unit/Testing/MembershipPgScriptTest.php`, `TestDatabaseSafetyTest.php` | Surviving mutants: extra allow-list name; `env > "$EVIDENCE/env.txt"`; `-P` publish-all; `--network=host`; trap moved after `podman run`; runtime leak-check removed; `applyEnvironment` removed. (Caught: system-ID check removed, check order swapped, `0.0.0.0` publish, no trap, `publicdigit` added, `set -x` — the latter only incidentally via the `set -euo pipefail` string.) | Optional hardening in a later slice. |
| F5 | minor | `scripts/testing/membership-pg.sh:89` | The script records the commit but not whether the tree was dirty; "clean worktree" is REPORTED, not evidenced. `$(git rev-parse HEAD)` failure would not abort. | Backlog: record `git status --porcelain` or refuse a dirty tree. |
| F6 | info | script / SIGTERM path | On SIGTERM, bash exits and removes the container but the phpunit child is orphaned; it then failed closed (43 connection errors, VERIFIED: `127.0.0.1`, port 33739, "database system is shutting down"). | None needed; optionally kill the child in `cleanup`. |
| F7 | info | guide `:41`, `:100` | Decision 2 says "the test's own connection" — it is the bootstrap's raw PDO connection. Traceability lists only AC-1, 2, 4, 6. | Doc touch-up. |
| F8 | info | `run.txt` | `image_digest` echoes the reference digest; `RepoDigests` also contains `sha256:40e8c839…`; the image ID (`245fc027…`) is not recorded. | Optional: record the image ID. |
| F9 | info | script `"$@"` | Pass-through args allow `--bootstrap` / `--no-configuration` to override the guard. No added risk beyond running phpunit directly; the PBDIGIT-283 hook still applies. | None. |

### D. Safety analysis
**None of the paths found lets these changes send the Membership, default or PBDIGIT-274 suite to a non-throwaway database, except by allow-listed name (F3).** Bootstrap order (VERIFIED): well-formed name → creds (`MEMBERSHIP_PG_DB_*` only) → config cache → exact name → system ID present + matching (first PDO connection) → `current_database()` (second PDO connection) → pinning; every refusal exits 1 before Laravel boots. Pinning writes all 15 `DB_*` + `DB_CONNECTION` + `DATABASE_URL=''` into `$_SERVER`/`$_ENV`/`putenv`; Laravel's immutable dotenv cannot override set variables, so `.env`/`.env.testing` cannot redirect them; the XML sets no `DB_*`. libpq env vars (e.g. `PGHOSTADDR`) would affect the bootstrap's PDO and Laravel's connections alike, so the system-ID check still covers that route (INFERRED). Forked race-test children inherit the pinned config (`DB::purge`/reconnect). `system_identifier` is per-initdb: strong against accidental misrouting, but checked once at bootstrap, not per connection. `pg_control_system()` is PUBLIC-executable on PG 15, so a non-throwaway server with a matching name would still need a matching 64-bit ID. Reset allow-list: a dev or shared database literally named `membership_pg_isolated` could be reset by a `--no-configuration` run or any run whose effective database has that name; the default `phpunit.xml` run cannot reach it (`<server>` pins `nrna_test`). The PBDIGIT-283 incident path (`publicdigit`) is still refused. PBDIGIT-274 is unaffected (defaults and messages identical).

### E. Commands run (summary as reported)
Read-only reads; `sha256sum -c` OK for run1/run2/proof; secret scans clean. Scratch worktree `/tmp/rev324` (no `config.php`, `composer install --no-scripts`): MembershipPgIsolation 20, MembershipPgBootstrap 15, MembershipPgScript 8, TestDatabaseSafety 19, 274 Guard 85, `tests/Unit/Testing` 160 — all OK; old guard swapped in: 85 OK; default-profile message/result fingerprint identical. 13 mutants: 6 caught, 7 not (F4); all restored. Container (pinned image only): full run exit 0, 43/256, server 15.19; failure run exit 2 + container removed; SIGTERM rc 143 + container removed, orphan failed closed; SIGINT test invalid (background job ignores SIGINT); probe container: `podman port` → `127.0.0.1:<random>`, non-superuser read `pg_control_system()`, `POSTGRES_PASSWORD` visible in `podman inspect` Config.Env. Final: `podman ps -a` empty; worktree removed; main tree `git status` unchanged except the parent session's concurrent untracked plan file. Temporary outputs left in `/tmp/rev324-ev*` and some `/tmp/*.bak` / `*.php` copies (the sandbox refused `rm`); no credentials.

### F. Residual risks if accepted
1. W2-2: version parity with production unconfirmed.
2. AC-5 met in substance by pinning, not by the named confinement check; the `testing` connection stays reachable by explicit name (F1).
3. Name-only, global reset allow-list (F3).
4. Server identity verified once at bootstrap, not per Laravel connection.
5. The password is visible to same-user processes via `podman inspect` while the container runs (loopback-only, short-lived).
6. The clean-tree claim is reported, not machine-evidenced (F5).
7. SIGKILL can leave a container behind.
8. Blind spots in the static script tests (F4).
9. The guide error (F2) could mislead about how strong the check is on shared servers.

## 3. PO decisions and verdict (2026-11-02)

1. **"B, fix F1 first"** (on the verdict note).
   - **F1 fixed** in `e7503f8de`: `MembershipPostgresTestCase` together with `MembershipPgIsolatedDatabase::confinedConnections()`.
   - In a Membership run, every test removes unconfined connections (including `testing`), runs `assertConnectionsConfined`, and checks `system_identifier` and `current_database()` on the live Laravel connection.
   - A sensitivity mutant (a wrong in-run ID) gets all 43 tests refused.
   - **This also removes residual risks 2 and 4** (the identity is now checked per test as well).
   - F2 and F7 were corrected in the same commit.
2. **"commit and accept":** **PBDIGIT-324 ACCEPTED WITH EXPLICIT RESIDUAL RISKS**, conditional on **W2-2**. If production's PostgreSQL major version is not 15, step 6 is re-run on that version.

**Dispositions:**

| Finding / risk | Disposition |
|---|---|
| F1, F2, F7 | **fixed** (`e7503f8de`) |
| F3, F4, F5 | **PBDIGIT-325** (backlog; not authorised) |
| F6, F8, F9 | recorded only |
| Risks 1, 5, 7 | **accepted** (W2-2 provisional; `podman inspect` exposure while the container runs, loopback-only and short-lived; SIGKILL may leave a container) |
| Risks 3, 6, 8 | carried by PBDIGIT-325 |
| Risk 9 | resolved (F2) |

**Scope of the acceptance:** test infrastructure only. No claim about Slice 1b (ACC-260), the PBDIGIT-264 Part 2 items, or production.
