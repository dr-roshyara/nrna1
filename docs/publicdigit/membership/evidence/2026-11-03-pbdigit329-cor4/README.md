# PBDIGIT-329 (COR-4): PostgreSQL evidence (2026-11-03)

**Plan:** `docs/plans/20261103-0440-membership-cor4-correction-persistence-tdd-plan.md` (approved 2026-11-03 with Q-1…Q-7).
**This folder records evidence only.** It accepts nothing: PBDIGIT-329 needs an independent review and the PO's verdict.

## Setup (every run)

- **Database:** the PBDIGIT-324 throwaway cluster (`bash scripts/testing/membership-pg.sh`):
  - image `docker.io/library/postgres:15@sha256:7da5d534f1ffcdb2f69efa6e466a7a29ff89eefd611c7b50c4d69aa77fd4a822`, PostgreSQL 15.19 (W2-2 provisional);
  - loopback only, a random password, the container removed after each run.
- **Code:** a fresh detached `git worktree` at the commit named in each `run.txt`, plus that step's uncommitted change copied in (RED / GREEN). No config cache; `composer install --no-scripts` from the committed lock. Each worktree was removed afterwards.
- **Each subfolder** holds `run.txt` (commit, image digest, `server_version`, `system_identifier`), `suite.txt`, `suite.exit` and `SHA256SUMS`. All were verified with `sha256sum -c` when archived.
- **Checks:** no credential-shaped token in any file; `podman ps -a` empty after the final runs.

## Runs

| Folder | Step | What it shows | Result |
|---|---|---|---|
| `s4-red` | 4 RED | `PostgresCorrectionSchemaTest` before the migration: `42703` (no `target_fact_id`), the migration file missing | 17 failures + 2 errors (19) |
| `s4-green2` | 4 GREEN | the migration plus the adapter rename: all schema tests and every pre-existing test green. **The only 3 errors are step 5's RED:** `Correct::effectiveAt()` undefined, which is ACC-260 K-2 reproduced | 81 tests, 3 errors |
| `s5-red` | 5 RED | `PostgresCorrectionDecodeTest`: no `StoredMemberLogInvalid`, no decoding of the new kinds | 7 errors (7) |
| `s5-green` | 5 | the first GREEN: the only failure is `test_t6`, which pinned the pre-Q-2 tie value (updated, annotated) | 1 failure (88) |
| `s5-green2` | 5 GREEN | the adapter for all five kinds; `StoredMemberLogInvalid`; strictly increasing `recordedAt` | 88 OK |
| `s6-a` | 6 | `PostgresCorrectionRaceTest`: 3 races × 8 rounds × 4 processes | 3 OK (340 assertions) |
| `s6-mutant-er-off` | 6 sensitivity | ER disabled (scratch copy): the lost-update race **fails**; round 0 stored both a Correct and an End | 1 failure |
| `s7-a` | 7 | the reload differential on PostgreSQL (every 10th bounded C-2 history) | 1 OK (14,827 assertions, 44 s) |
| `s7-mutant-drop-trims` | 7 sensitivity | `ConsequencesJson::decode()` drops every trim (scratch copy): the differential **fails** (history D541) | 1 failure |
| `final-run1..3` | 8 | three full runs on fresh clusters at `96d71183e` (system ids `…638818`, `…160738`, `…672034`) | 92 OK × 3 |
| `cond-red` | review C-1 RED | the new damaged-provenance cases against the **unfixed** code (worktree at `884f979c1`; code identical to `acdedf1cd` for Membership, plus the decode test copied in): `ErrorException` / `TypeError` instead of `StoredMemberLogInvalid`, as the review found (F-1). The ∞-trim test (F-7) passes already, because it closes a coverage gap, not a defect | 3 failures (11) |
| `cond-green` | review conditions GREEN | the full suite with C-1, C-2, F-3, C-4 and F-7 in place (`884f979c1` plus the uncommitted fixes) | 96 OK |

**Mutants** were applied only in scratch worktree copies, which were then removed.

**Excluded, not evidence:**
- `s4-debug`, `s4-debug2`: diagnosis of my own `down()` test defect (step 4), run with a debug-modified test copy;
- `s7-mutant-noop`: an invalid mutant of mine (`return [] ?: array_map(…)` changed nothing).

**Assertion counts** differ slightly between the final runs (15,674 / 15,674 / 15,678), because the race tests assert per stored fact and the winners vary between runs.

**The one deprecation in every run** is not Membership code: `config/database.php:66` uses `PDO::MYSQL_ATTR_SSL_CA` (deprecated in PHP 8.5).

## Verify

```bash
cd docs/publicdigit/membership/evidence/2026-11-03-pbdigit329-cor4
for d in */; do (cd "$d" && sha256sum -c --quiet SHA256SUMS) && echo "$d OK"; done
```
