# 08: Isolated PostgreSQL runs, evidence folders, copy-load mutants and the baseline comparison (PBDIGIT-345)

**Purpose:** how slice 2's proofs were produced, so the next slice can produce and check its own in the same way. It builds on guide 07 (the harness itself).
**Where it fits:** test infrastructure; nothing here is production code.
**Worked example:** `docs/publicdigit/platform/evidence/2026-11-15-pbdigit345-task13/` (and tasks 0–12 beside it).

## 1. Running the isolated suites

```
MEMBERSHIP_PG_IMAGE=docker.io/library/postgres:15@sha256:7da5d534f1ffcdb2f69efa6e466a7a29ff89eefd611c7b50c4d69aa77fd4a822 \
MEMBERSHIP_PG_EVIDENCE_DIR=<new folder> \
bash scripts/testing/membership-pg.sh [--testsuite PlatformPostgres|MembershipPostgres] [--filter X] [--log-junit <file>]
```

- **One run at a time.** Each run starts its own throwaway container. Two concurrent runs compete for CPU, and nothing coordinates their evidence folders.
- **The script writes:**
  - `run.txt`: commit, image digest, server version, system identifier;
  - `suite.txt`, `suite.exit`, `SHA256SUMS`.
  It refuses if the run's random password appears in the output.
- **`run.txt`'s commit is `HEAD` at run time.** A run made **before** its implementation commit names the parent commit plus uncommitted changes. For evidence that binds exactly to committed code, run again after the commit with a code-identical working tree: `git diff --quiet HEAD -- app config routes database scripts tests`.
- **Per-test results:** pass `--log-junit <file>`. `suite.txt` lists only failures.

### The cluster's own client tools (task 12b)
- The script exports **`MEMBERSHIP_PG_CONTAINER`**. Tests that need `pg_dump` / `pg_restore` run them **inside the container**: `podman exec -i -e PGPASSWORD <container> pg_dump …`, as `RestoreDrillTest` does.
- **Reason:** client tools must match the server's major version. The host's `pg_dump` 18 writes `SET transaction_timeout`, which PostgreSQL 15 rejects. Production has the same rule (`organisation_routing.restore_drill.tool_prefix`, admin notes).

## 2. Database-free runs

```
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php <paths>
```

**Only for tests that never touch a database** (guide 01). Slice 2's set (560 tests at `8b6fcd569`):
- `tests/Unit/Contexts/{Platform,ReferenceGeography,Identity}` and `tests/Unit/Contexts/Membership/Infrastructure/Organisational`;
- these architecture tests:
  - `ReferenceGeographyDomainPurity`, `IdentityG0Architecture`, `ReferenceGeographyNoBypass`;
  - `ArchitectureRatchet`, `OrganisationMigrationSet`, `OrganisationRoutingDefaultConnection`;
  - `MembershipLedgerIsolation`, `OrganisationProvisioningProcess`, `PlatformIdentityBoundary`;
  - `OrganisationEntryPaths`, `OrganisationQueueArchitecture`, `MembershipPlatformBoundary`;
  - `PlatformDomainPurity`.

**Config files are loaded by some architecture tests outside Laravel.** Never call app helpers such as `storage_path()` at the top level of a `config/*.php` file; use `dirname(__DIR__)` (task 12b found this).

## 3. Evidence folders

`docs/publicdigit/platform/evidence/<date>-pbdigit345-<task>/`:
- **A README** maps each file to what it shows, **including RED runs and failed or invalid runs, kept and marked.**
- **Raw output byte-exact.** `git diff --cached --check` reports trailing spaces in raw output, so run the check with `-- . ':(exclude)docs/publicdigit/platform/evidence'`.
- **Seal:**

  ```
  cd <folder> && find . -type f ! -name SHA256SUMS -print0 | sort -z | xargs -0 sha256sum > SHA256SUMS && sha256sum -c --quiet SHA256SUMS
  ```

- **Never overwrite** a historical run; add a new numbered file instead.
- **Verify all folders** (task 13 does this): every `SHA256SUMS` checks, and every file is covered by one (`03-evidence-verification/`).

## 4. Copy-load mutants

- **A mutant is a modified copy of one class**, written to a scratch folder and `require`d by a custom bootstrap **before** the autoloader, so PHP uses it instead of the real file. **No source file is edited.**
- **Drivers:** `mutation-check-t12.php`, `mutation-check-t12b.php` in the task 12 evidence. Modes:
  - `unit` (Platform unit tests);
  - `pg-platform:<Filter>` (the cluster, through `--bootstrap`);
  - `arch:<path>`.
- **Classification. Report each one, never only a total:**

  | Class | Meaning |
  |---|---|
  | assertion kill | failed by assertions only (`Errors: 0`) |
  | database refusal | killed because PostgreSQL refused (a constraint as backstop): **not** an assertion kill |
  | test error | killed by an unrelated error: **not** an assertion kill |
  | equivalent | survives, with a written argument why no behaviour changed |
  | survived | survives without such an argument: **a test is missing**; add it, rerun, keep both runs |
  | not run | the mutant's text was not found exactly once, or does not parse: **a driver defect**, fix and rerun |

- **Limits:**
  - mutants do not reach **child processes** (provisioning, repair) or **static scanners** (architecture tests read files, not loaded classes);
  - a mutant site must occur **exactly once** in its file. Task 12b's V1 occurred twice and was "not run" until the site was made unique.

## 5. The legacy baseline comparison

1. Baseline = the commit **before** the slice's first commit.
2. `git worktree add --detach <scratch>/baseline-<sha> <sha>`.
3. `cp -a vendor <worktree>/vendor`. **Never a symlink:** Composer's `$baseDir` would then resolve to the main repository, and the "baseline" would test HEAD's classes. That is the slice 1 correction.
4. `composer dump-autoload` inside the worktree. Check that a class resolves into the worktree (`ReflectionClass::getFileName()`).
5. Check `composer.lock` / `composer.json` / `phpunit.xml` are unchanged since the baseline. If they changed, `composer install` in the worktree instead of copying.
6. **The worktree needs the database login:** symlink the untracked `.env` (`ln -s <repo>/.env <worktree>/.env`). Do not read or copy it. Without it every database test fails with `fe_sendauth: no password supplied`, and the comparison is invalid (task 13's first run, kept as `…-INVALID`).
7. **Run the sides one after the other:** both use the same `nrna_test` database.
8. Compare **failures by name** (`comm`), not counts. For any difference, check whether the test and its classes changed (`git diff --stat <base> HEAD -- <files>`), and rerun the class several times on both sides to separate flakiness from regression.
9. Remove the worktree afterwards with `git worktree remove --force <path>`.

**Pitfalls:**
- A green total hides a survived mutant.
- A count comparison hides a swapped failure.
- A missing `.env` makes every baseline database test fail, which looks like a massive regression **of the baseline**.

**Traceability:** PBDIGIT-345 tasks 0–13 · slice 1 acceptance evidence (PBDIGIT-343) · guides 01 and 07.
