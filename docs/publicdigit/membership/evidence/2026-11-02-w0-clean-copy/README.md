# Membership W0: clean-copy evidence at `a0d9d16bb` (2026-11-02)

**Authority:** the PO, 2026-11-02: "authorise the W0 clean-copy re-run" (roadmap W0: "database-free re-run at HEAD (clean export, not the shared tree)"; R-6: "the clean-copy run is still outstanding (it needs authorisation)").
**This folder records evidence only. It accepts nothing.**
- The W0 verdicts (ACC-268 C-1 G3, ACC-271 Slice 2a) and the PR review of the 2a hardening are separate steps.
- ACC-258 and ACC-259 were already accepted (R-6); this run does not reopen them.

## Run (E-RUN-W0)

**Provenance:** produced by Claude Code session `93000efc-ad03-4c1d-9b3f-40966981efc3` at 2026-11-02T17:00:47+01:00 (`env.txt`). Copied here unchanged from that session's scratchpad.

**Method:**
- a fresh **detached** `git worktree` at `a0d9d16bb8708582d64cc9cedfca1e4610f1f0fe` (`commit.txt`), outside the shared working tree. `git status --short` gave 0 lines at the start;
- dependencies installed from the committed `composer.lock` with `composer install --no-scripts`;
- `phpunit.xml` used, with `--do-not-cache-result`;
- **database-free:** every test class in the five paths extends `PHPUnit\Framework\TestCase`. A search of the paths for `Tests\TestCase`, `RefreshDatabase` and `DatabaseTransactions` found nothing. No database, `.env` or artisan command was used.

**Environment:** PHP 8.5.11, PHPUnit 11.5.6, Composer 2.10.3 (`env.txt`).
**Working tree after the run:** `worktree-status-after.txt` is empty (clean).

| # | Command (`N-*.cmd`) | Result (`N-*.txt`) | Exit (`N-*.exit`) |
|---|---|---|---|
| 1 | `tests/Unit/Contexts/Membership/Domain/Organisational/AnnulVoidTest.php` | OK (24 tests, 159 assertions) | 0 |
| 2 | `tests/Unit/Contexts/Membership/Domain/Organisational` | OK (117 tests, 112565 assertions) | 0 |
| 3 | `tests/Unit/Contexts/Membership/Application/Organisational` | OK (61 tests, 226 assertions) | 0 |
| 4 | `tests/Unit/Contexts/Membership/Infrastructure/Organisational` | OK (13 tests, 41 assertions) | 0 |
| 5 | the 7 `tests/Architecture/Membership*Test.php` files | OK (97 tests, 120 assertions) | 0 |

No warning, deprecation, risky, skipped or incomplete marker appears in any output.

## Comparison with E-RUN-V2 (`7f3c9896b`, folder `2026-11-02-v2-clean-copy/`)

- **Membership code** (`app/Contexts/Membership`), the Membership unit tests, `composer.lock` and `phpunit.xml`: **unchanged** between `7f3c9896b` and `a0d9d16bb` (`git diff --stat`).
- **The only change** in the tested paths is `tests/Architecture/MembershipElectionBoundaryTest.php` (S1 → S1b; `3aad77d20`, `95dcdb62b`).
- **Runs 1–4:** identical counts to E-RUN-V2.
- **Run 5:** 51 → 97 tests.
  - `MembershipElectionBoundaryTest.php` alone now gives 49 tests, 64 assertions (checked in the same worktree).
  - So the other six architecture files account for 97 − 49 = 48 tests.

## What this evidence supports, and what it does not

**Supports:**
- the clean-copy condition of W0 (R-6), at HEAD `a0d9d16bb`;
- the database-free part of the C-1 evidence (V-2 included).

**Does not cover:**
- PostgreSQL behaviour. The feature tests under `tests/Feature/Contexts/Membership/Organisational/` need an isolated database (ARB-SEC-01; roadmap W2) and were not run;
- the PR review of the 2a hardening;
- any acceptance.

## Verify

```bash
cd docs/publicdigit/membership/evidence/2026-11-02-w0-clean-copy
sha256sum -c SHA256SUMS        # the five outputs
sha256sum -c MANIFEST.sha256   # every archived file
```
