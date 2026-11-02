# 11: The isolated PostgreSQL container for the Membership suite (PBDIGIT-324, W2)

**Story:** `PBDIGIT-324` · **Plan:** `docs/plans/20261102-1719-membership-isolated-postgres-container-plan.md` (approved 2026-11-02) · **Decision:** W2-1 = C
**Status:** steps 1–5 implemented (database-free, TDD). **Step 6 done on 2026-11-02:**
- image `postgres:15@sha256:7da5d534…a822`, PostgreSQL 15.19; **W2-2 provisional** (15 matches CI; production not yet confirmed);
- the live fail-closed proof passed;
- two suite runs, 43 tests, 256 assertions, exit 0;
- evidence: `docs/publicdigit/membership/evidence/2026-11-02-w2-membership-pg/`.

**Accepted with explicit residual risks** (PO, 2026-11-02, conditional on W2-2), after the independent review and the F1 fix (`e7503f8de`). Follow-ups: `PBDIGIT-325`.

## Purpose

The Membership history is stored in PostgreSQL (Slice 1b). Its four PostgreSQL tests (`tests/Feature/Contexts/Membership/Organisational/Postgres*.php`) have never run on an isolated database.

In 2026-10 a test run wiped the development database (PBDIGIT-283). This suite therefore runs on a **throwaway cluster**:
- it is created for one run and removed afterwards;
- it is reachable only on 127.0.0.1;
- a run refuses to start unless it can prove it reached that cluster.

## Where it fits

Test infrastructure only. It changes **no** application code, schema, `phpunit.xml` or default suite.

| File | Role |
|---|---|
| `tests/Support/MembershipPgIsolatedDatabase.php` | the Membership **profile**: approved name `membership_pg_isolated`; variables `MEMBERSHIP_PG_TEST_DB`, `MEMBERSHIP_PG_DB_*`, `MEMBERSHIP_PG_SYSTEM_ID` |
| `tests/Isolated/Pbdigit274/Support/DatabaseIdentityGuard.php` | the **existing** PBDIGIT-274 guard, generalised (see "Design decisions") |
| `tests/Support/TestDatabaseSafety.php` | the PBDIGIT-283 reset allow-list, plus the exact Membership name |
| `phpunit.membership-pg.xml` | only `tests/Feature/Contexts/Membership/Organisational`; no `DB_*` values |
| `tests/bootstrap-membership-pg.php` | the guard sequence, run before Laravel exists |
| `scripts/testing/membership-pg.sh` | starts the container, runs the suite, removes the container, writes evidence |

## Design decisions

1. **One guard, not two** (roadmap W2: "reusing the PBDIGIT-283 rules (no second guard)"). `DatabaseIdentityGuard` gained optional parameters, and the defaults reproduce PBDIGIT-274 exactly:
   - `wellFormedName($raw, $envName)` and `approvedName($raw, $approvedName, $envName)` label their messages with the profile's variable;
   - `connectionSettings($env, $prefix = 'PBDIGIT274_DB_')` reads only that prefix.
   
   PBDIGIT-274's guard tests pass unchanged (85 of 85, in a clean worktree, before and after).
2. **The cluster proves its identity.** `verifySystemIdentifier($expected, $lookup)` compares the cluster's `system_identifier`, captured by the script at start, with what a connection sees. It is checked twice:
   - in the bootstrap, on a raw PDO connection, before Laravel exists;
   - and (F1) in every test, on the live Laravel connection (see decision 5).
   
   A missing or non-digit expected value is refused **before** anything is asked of the database. A different cluster is refused even if it has a database with the same name. *(Wording corrected 2026-11-02, review finding F7.)*
3. **One exact new reset name.** `TestDatabaseSafety::RESETTABLE` gains `membership_pg_isolated`. Near names (`…_x`, `membership_pg`, other case, another driver) are refused.
4. **The password never leaves the process environment.** It is random per run (`openssl rand`) and handed to podman **by name** (`-e POSTGRES_PASSWORD`). It is never on a command line, never echoed, and never written to evidence. The script fails if it finds the password in its own evidence.
5. **In-run confinement** (independent review finding F1, fixed 2026-11-02).
   - The four PostgreSQL tests extend `Tests\Feature\Contexts\Membership\Organisational\MembershipPostgresTestCase`. In `setUpTraits()`, before Tests\TestCase's reset check and before `migrate:fresh`, it calls `MembershipPgIsolatedDatabase::confinedConnections()`.
   - **Only when `MEMBERSHIP_PG_TEST_DB` is set** (a Membership run), that call:
     - removes unconfined PostgreSQL connections, such as `config/database.php`'s hard-coded `testing` → `nrna_test`;
     - runs `assertConnectionsConfined` (the three connections target `membership_pg_isolated`; no URL override);
     - then checks `system_identifier` and `current_database()` on the live Laravel connection.
   - **Outside a run** (default `phpunit.xml`, CI) nothing changes.
   - This is the PBDIGIT-274 `IsolatedDatabaseTestCase` pattern with the same guard.

## How it works

**Run order of the bootstrap** (each step refuses with `[reason]` and exit 1):
1. the name is well formed (`missing`, `empty`, `reserved`, `forbidden` for `nrna_test`, `malformed`);
2. the credentials are present: `credentials_missing` (PBDIGIT-274's variables do not count);
3. no configuration cache: `config_cached`;
4. the exact approved name: `not_approved` (PBDIGIT-274's database is refused);
5. the expected system ID is present (`system_id_missing`) and matches (`system_id_mismatch`, `connection_failed`);
6. `current_database()` matches (`mismatch`).

Then every PostgreSQL connection is pinned to the container database.

**The script:**
- **refuses** if the image is not pinned by digest (`…@sha256:…`), if podman or openssl is missing, or if a config cache exists;
- **starts** `-p 127.0.0.1::5432` (a random host port, loopback only);
- **waits** for `pg_isready`;
- **checks** that the published address is 127.0.0.1;
- **captures** `system_identifier`, `server_version` and the image digest;
- **runs** `vendor/bin/phpunit -c phpunit.membership-pg.xml`;
- **writes** `run.txt`, `suite.txt`, `suite.exit` and `SHA256SUMS`;
- **removes** the container on every exit (`trap cleanup EXIT`).

## How to use

The pinned image used on 2026-11-02 was `docker.io/library/postgres:15@sha256:7da5d534f1ffcdb2f69efa6e466a7a29ff89eefd611c7b50c4d69aa77fd4a822`. If the main tree has a cached configuration, run from a clean worktree (`git worktree add --detach <dir> HEAD`, then `composer install --no-scripts` there).

```bash
MEMBERSHIP_PG_IMAGE='docker.io/library/postgres:<major>@sha256:<digest>' \
MEMBERSHIP_PG_EVIDENCE_DIR=/path/to/evidence \
bash scripts/testing/membership-pg.sh
```

Do **not** run `phpunit.membership-pg.xml` directly: without the script's variables the bootstrap refuses. It must be run as `bash …`, because the executable bit is not set.

## Testing

All database-free and container-free (`tests/Unit/Testing`):

| Test | Covers |
|---|---|
| `MembershipPgIsolationTest` (28) | the profile; approval and near names; credentials only from `MEMBERSHIP_PG_DB_*`; system-ID refusals and match; **F1 in-run confinement**: outside a run nothing changes; inside one, `testing` is removed, unconfined or URL connections are refused before the database is asked, the live cluster and database must match; all four PostgreSQL tests extend the confining base |
| `TestDatabaseSafetyTest` (+6) | the exact new reset name; near names refused; the message names the new configuration |
| `MembershipPgBootstrapTest` (15) | the real bootstrap as a child process with `env -i`: 12 refusals before any connection; the approved name with an unreachable socket fails closed; no Laravel boot; the configuration runs only the Membership suite and holds no `DB_*` |
| `MembershipPgScriptTest` (8) | `bash -n`; `set -euo pipefail`; loopback-only publishing; the EXIT trap removes the container; the password is never printed or on the command line (a planted `echo "$SECRET"` is caught); no `.env` or `DB_*`; unset or unpinned images are refused before podman |

## Pitfalls

- **A cached Laravel configuration** (`bootstrap/cache/config.php`) makes this bootstrap, the PBDIGIT-274 bootstrap and the PBDIGIT-283 hook all refuse. That is intended. On 2026-11-02 the main working tree had such a file (dated 2026-10-19, git-ignored, not created by this work). In that tree, `MembershipPgBootstrapTest`, `IsolatedBootstrapScriptTest` and some `DatabaseFreeBoot` tests report `[config_cached]`. Run them in a tree without a config cache. The evidence for this step was taken in a clean worktree.
- `pg_control_system()` is executable by ordinary roles on PostgreSQL 15. The independent review checked this with a non-superuser role in a throwaway container. *(Corrected 2026-11-02, review finding F2; an earlier version wrongly said it needs a superuser.)* The strength of the check comes from the 64-bit `system_identifier`, which is unique per cluster, not from privileges.
- PBDIGIT-274 does not use the system-ID check, and its behaviour is unchanged.

## Traceability

PBDIGIT-324 · plan §3, §5 steps 1–6, AC-1…AC-9 · independent review 2026-11-02 (F1 fixed, F2 / F7 corrected) · roadmap W2 (P-DB, I-1, BR-20) · PBDIGIT-283 · PBDIGIT-274 guard guide `developer_guide/election/voter_slug/03_isolated_database_guard.md`. No voter or vote data is involved (ADR-T11 unaffected).
