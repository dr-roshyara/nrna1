# PBDIGIT-264 Part 2 (F-5, F-4b, F-2 and step 4a): PostgreSQL evidence (2026-11-02)

**Plan:** `docs/plans/20261102-1750-membership-pbdigit264-part2-postgres-followups-plan.md` (approved 2026-11-02 with P2-0; revision R1 = step 4a).
**This folder records evidence only.** It accepts nothing: PBDIGIT-264 and ACC-260 need an independent review and the PO's verdict.

## Setup (every run)

- **Database:** the PBDIGIT-324 throwaway cluster (`bash scripts/testing/membership-pg.sh`). Image `docker.io/library/postgres:15@sha256:7da5d534f1ffcdb2f69efa6e466a7a29ff89eefd611c7b50c4d69aa77fd4a822`, PostgreSQL 15.19 (W2-2 provisional). Loopback only, random password, container removed after each run.
- **Code:** a fresh detached `git worktree` at the commit named in each `run.txt`, plus the step's uncommitted change copied in (for RED / GREEN). No config cache; `composer install --no-scripts` from the committed lock.
- **Each subfolder** holds `run.txt` (commit, image digest, `server_version`, `system_identifier`), `suite.txt`, `suite.exit` and `SHA256SUMS` (all verified).
- **Checks:** no credential-shaped token was found; `podman ps -a` was empty after every run.

## Runs

| Folder | Step | What it shows | Result |
|---|---|---|---|
| `step0-testdox-coverage` | 0 | `--testdox` names: the F-1 / Option E cases (E1a–c, E2a–c, the End boundary) and the F-3 cases ran on PostgreSQL | 43 OK |
| `step2-f5-red` | 2 RED | REPEATABLE READ races → `SQLSTATE[40001]`; nested append not refused; SET not first | 4 failures (47 tests) |
| `step2-f5-green` | 2 GREEN | N1 refusal; READ COMMITTED first; the REPEATABLE READ races return contract outcomes | 47 OK |
| `step3-f4b-red` | 3 RED | migration absent (2 errors); copied columns present; orphan insert fails with NOT NULL instead of 23503 | 2 errors + 2 failures (52 tests) |
| `step3-f4b-green` | 3 GREEN | no copied columns; orphan rejected (23503); answers from the fact; migration `up → down → up` preserves rows and answers; `up()` aborts on a planted mismatch | 52 OK |
| `step4-f2-red-f2a-defect` | 4 | **defect found:** F-2(a), 4 identical Admits for a new person, gives a `UniqueConstraintViolationException` on `org_memberships_pkey` from `create()`. F-2(b) green | 1 failure (2 tests) |
| `step4a-green` | 4a (R1) | `create()` with `ON CONFLICT DO NOTHING` plus the explicit F-3 branch; F-2(a), F-2(b) and the F-3 contract test green | 54 OK |
| `step4-mutant-a-race-group` | 4 sensitivity | mutant (a): no key re-read after a domain refusal (`DecideAndAppend`). **Caught** by the existing identical-retries race (round 4) **in this run**. *(Review R-5, 2026-11-02: the PostgreSQL detection is probabilistic, caught in 1 of 3 of the reviewer's runs; the deterministic catch is the database-free `ConcurrencyAndRaceTest`.)* | 1 failure |
| `step4-mutant-a-f2a-only-1`, `-2` | 4 sensitivity | the same mutant, F-2(a) only: **not caught** in 2 × 8 rounds (its losers resolve through the in-transaction key check). Recorded as a limitation of F-2(a) | 1 OK each |
| `step4-mutant-b-f2b` | 4 sensitivity | mutant (b): the commands-PK collision is no longer answered. **Caught** by F-2(b) (round 0: unique-violation errors) | 1 failure |
| `step5-final-run1..3` | 5 | three consecutive full runs on fresh clusters (IDs `…894370`, `…366626`, `…528610`) | 54 OK × 3 |
| `c1-green` | review condition C1 | a fresh worktree at `444414e3d` with the C1 / R-4 tests copied in: a later fact's key answers with its own fact; a `recorded_at`-only mismatch aborts `up()` | 56 OK |
| `c1-mutant-m6-caught` | C1 sensitivity | the reviewer's surviving mutant M6 (join without `seq`) is now **caught** by the C1 contract test | 1 failure |
| `step4b-e4l-green` | 4b (R2) | E4-L on PostgreSQL: a legacy `10000-01-01` Admit and its key row; the identical replay → `AlreadyApplied` with the original fact id, seq and recordedAt; one fact; version 1. A fresh worktree at `b3b05c616` with the test copied in | 57 OK |
| `step4b-e4l-mutant-date-check-first` | 4b sensitivity | the R-1a-6 date check moved before the key lookup (scratch) → E4-L **fails** (`ValidTimeNotStorable` instead of `AlreadyApplied`) | 1 failure |
| `c1-mutant-m1-caught` | C1 sensitivity | the reviewer's surviving mutant M1 (mismatch check without `recorded_at`) is now **caught** by the R-4 case | 1 error |

**Mutants** were applied only in the scratch worktree, restored by copy and checked with `cmp`. **Excluded:** one invalid RED run for step 3 (the reused worktree ran the old state: 43 tests); it is not evidence (plan §11).

**The one deprecation in every run** is not Membership code: `config/database.php:66` uses `PDO::MYSQL_ATTR_SSL_CA` (deprecated in PHP 8.5).

## Verify

```bash
cd docs/publicdigit/membership/evidence/2026-11-02-pbdigit264-part2
for d in */; do (cd "$d" && sha256sum -c SHA256SUMS); done
```
