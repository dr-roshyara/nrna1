# Membership W2: first PostgreSQL run on the throwaway cluster (PBDIGIT-324 step 6, 2026-11-02)

**Authority:**
- the PO, 2026-11-02: "run step 6 from a clean copy" (G-RUN);
- PostgreSQL major version **15, provisional** (PO's choice; it matches the CI workflows `.github/workflows/*.yml`, `image: postgres:15`). W2-2 stays open until Ops confirms production's version. If production differs, step 6 is re-run on that version.

**This folder records evidence only.** It accepts nothing (G-ACC for PBDIGIT-324; ACC-260 for Slice 1b is a separate decision).

## Setup

- **Clean copy:** a fresh detached `git worktree` at `f84653283aba1762fef3ee204d56adb12ffd6ec7`, which contains `82184d62a` (steps 1–5). It had no working-tree changes and **no** `bootstrap/cache/config.php`. Dependencies came from the committed `composer.lock` (`composer install --no-scripts`). The worktree was removed afterwards.
- **Image:** `docker.io/library/postgres:15@sha256:7da5d534f1ffcdb2f69efa6e466a7a29ff89eefd611c7b50c4d69aa77fd4a822`, linux/amd64, **PostgreSQL 15.19** (Debian 15.19-1.pgdg13+2).
- **Host:** rootless podman; published on 127.0.0.1 only, on a random port; a random password per run, never printed. No `.env` was read, and no shared or development database was contacted: the run's only database endpoint was the container.

## Fail-closed proof against a live cluster (`proof/`, AC-3)

Produced by `proof/w2-proof.sh`, a scratch script kept here for reproducibility. It starts a container as `scripts/testing/membership-pg.sh` does, then runs the real bootstrap `tests/bootstrap-membership-pg.php` three times:

| Probe | Exit | Output |
|---|---|---|
| correct name, **wrong system ID** | 1 | `REFUSED [system_id_mismatch]`, after connecting, before any migration |
| **wrong name** (`pbdigit274_isolated_test`) | 1 | `REFUSED [not_approved]`, before connecting |
| correct name and ID | 0 | `cluster system_identifier and current_database() = 'membership_pg_isolated' confirmed` |

The proof script checks its outputs for the generated password and found none. The container was removed.

## Suite runs (`run1/`, `run2/`)

Each run is `bash scripts/testing/membership-pg.sh` on a **new** cluster, against `tests/Feature/Contexts/Membership/Organisational/` (`PostgresMemberRepositoryTest`, `PostgresMemberRepositoryInvariantsTest`, `PostgresConcurrencyRaceTest`, `PostgresReferenceModelPropertyTest`).

| Run | system_identifier | Result | Exit |
|---|---|---|---|
| run1 | 7703615330303832098 | OK, but there were issues: **43 tests, 256 assertions**, 1 deprecation | 0 |
| run2 (`--display-deprecations`) | 7703615528402100258 | same counts; identifies the deprecation | 0 |

**The guard line** `… confirmed.` is the first line of each `suite.txt`, so the guard passed before any test ran.

**The deprecation** is not in Membership code: `config/database.php:66` uses `PDO::MYSQL_ATTR_SSL_CA`, deprecated since PHP 8.5. It is recorded only.

**Self-checks:**
- the script verifies that its evidence contains no credential, and found none;
- a search for 48-hex tokens (the password's shape) in this folder found none;
- `podman ps -a` shows no leftover container after any run.

## What this supports, and what it does not

**Supports:**
- PBDIGIT-324 AC-3 (live refusal), AC-5 (all three connections pinned; the bootstrap applies `environmentFor`), AC-7 (container removed) and AC-8 (evidence);
- the first PostgreSQL evidence for the Membership kernel's persistence: the Slice 1b test files run green on an isolated cluster.

**Does not cover:**
- production version parity (W2-2 provisional);
- the PBDIGIT-264 Part 2 items (F-5 → F-4b → F-2), which are separately authorised;
- OQ-01 (production migration status);
- CI integration;
- any acceptance.

## Verify

```bash
cd docs/publicdigit/membership/evidence/2026-11-02-w2-membership-pg
(cd run1 && sha256sum -c SHA256SUMS); (cd run2 && sha256sum -c SHA256SUMS); (cd proof && sha256sum -c SHA256SUMS)
```

## Addendum: review finding F1 fixed (`f1-confinement/`, 2026-11-02)

**Why.** The independent review found that AC-5 named a confinement check (`assertConnectionsConfined`) that the Membership path did not run. The hard-coded `testing` connection to `nrna_test` therefore stayed reachable by name. AC-5 was **partial** in the runs above. The "Supports" claim for AC-5 above refers to pinning only.

**Fix.** `MembershipPostgresTestCase` together with `MembershipPgIsolatedDatabase::confinedConnections()`: in a Membership run, every test removes unconfined connections, checks confinement, and checks the cluster ID and the database on the live Laravel connection. Default and CI runs are unchanged. Tested database-free first (8 new cases).

**Evidence, from a clean worktree at the then-HEAD plus the uncommitted F1 change; same pinned image:**

| Folder | Content | Result |
|---|---|---|
| `f1-confinement/run/` | the full suite with the fix | 43 tests, 256 assertions, exit 0. Cluster `7703618967204040738`, PostgreSQL 15.19 |
| `f1-confinement/mutant-wrong-in-run-system-id/` | sensitivity check, in a scratch copy only: the base class's live lookup made to return a wrong ID | **all 43 tests refused** (`[system_id_mismatch]`), exit 2. This proves the in-run check runs for every test. The mutant was reverted |

Hashes verify. No credential is present. No container is left.
