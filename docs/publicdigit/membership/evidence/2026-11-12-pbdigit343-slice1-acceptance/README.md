# PBDIGIT-343 — Membership slice 1: acceptance evidence (2026-11-12)

**Purpose:** the raw test output behind the slice 1 acceptance handover. **Evidence only; the Principal decides acceptance.**

| | |
|---|---|
| **Repository** | branch `election-flow-updated`, HEAD **`58e8bb26d`** (final code commit); baseline **`a53ba8ea8`** (the commit before slice 1) |
| **PostgreSQL** | `docker.io/library/postgres:15@sha256:7da5d534f1ffcdb2f69efa6e466a7a29ff89eefd611c7b50c4d69aa77fd4a822` (15.19), throwaway cluster (PBDIGIT-324); `head/postgres/` holds the script's own evidence and `SHA256SUMS` |

## Commands and results at HEAD (`head/`)

| File | Command | Result |
|---|---|---|
| `kernel.txt` | `php -d memory_limit=-1 vendor/bin/phpunit tests/Unit/Contexts/Membership/Domain/Organisational` | OK (204 tests, 542532 assertions) |
| `application.txt` | `… tests/Unit/Contexts/Membership/Application/Organisational` | OK (92 tests, 119045 assertions) |
| `infrastructure.txt` | `… tests/Unit/Contexts/Membership/Infrastructure/Organisational` | OK (65 tests, 115 assertions) |
| `outbox.txt` | `… tests/Unit/Contexts/Membership/Infrastructure/Outbox` | OK (9 tests, 19 assertions) |
| `architecture-membership.txt` | `… tests/Architecture --filter Membership` | Tests: 106, Assertions: 144, Deprecations: 1, PHPUnit Deprecations: 5. |
| `architecture-full.txt` | `… tests/Architecture` | Tests: 300, Assertions: 937, Failures: 4, Deprecations: 4, PHPUnit Deprecations: 5, Skipped: 1. |
| `membership-unit-full.txt` | `… tests/Unit/Contexts/Membership` | Tests: 618, Assertions: 662037, Errors: 56, Failures: 1, Deprecations: 1, PHPUnit Deprecations: 46. |
| `postgres/` | `MEMBERSHIP_PG_IMAGE=<above> bash scripts/testing/membership-pg.sh` | exit 0; Tests: 135, Assertions: 16098, Deprecations: 1. |

## The baseline comparison (`baseline/`, and the `*.failures` lists)

**Method:**
- a detached worktree at `a53ba8ea8`, **with its own copy of `vendor/` and its own `composer dump-autoload`;**
- verified: `App\…\Member` resolves to the worktree's own file.

> **Correction of an earlier method (recorded, not hidden):** the comparisons reported during task 7 and task 9 used a worktree whose `vendor/` was a **symlink** to the main repository. Composer's `$baseDir` then resolves to the main repository, so baseline tests ran against HEAD's classes. **Those earlier conclusions were not established by sound evidence.** The comparison below repeats them correctly; its result happens to agree.

| Suite | Baseline | HEAD | Difference |
|---|---|---|---|
| `tests/Architecture` | 4 failing | 4 failing | **identical by name** (`architecture-full.failures`): Election ×2, GovernanceRuntime, Phase_C25. **Pre-existing; none introduced by slice 1** |
| `tests/Unit/Contexts/Membership` | 58 failing | 57 failing | **every HEAD failure also fails on the baseline**; the one baseline-only case is `CommitteeSlugAvailabilityServiceTest::test_check_returns_exists_for_taken_slug`, a class whose failing cases vary between runs (flaky legacy test). **None introduced by slice 1;** none in the ledger (`…\Organisational`) |

## Reconciliation note (2026-11-12, after the Principal's review)

**`LaravelBootIsGuardedTest`:**
- It appeared, as a fifth "baseline-only" failure, **only in the earlier, unsound comparison** (symlinked `vendor/`), which I reported in the session before this evidence was gathered.
- **In the corrected comparison above it does not fail** on either side. The committed lists hold exactly the same 4 names at `a53ba8ea8` and `58e8bb26d`.
- No file in this folder contains the earlier 5-name list.

**Developer guide for the `database` area:**
- It exists and is indexed: `developer_guide/database/00_index.md` → `01_postgresql_lock_waits.md` (commit `ba4c1f5c9`).
- The slice's migration is documented in `developer_guide/membership/16_ledger_clock_table_and_backfill.md`.
- **The reminder's warning was a false positive:** it reads only files changed with the Write/Edit tools (`.claude/runtime/<date>-files.log`), and the database guides were written another way, so it never saw them. Recorded as `PBDIGIT-344`; no guide change needed.
