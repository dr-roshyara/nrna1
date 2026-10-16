# Membership A1: database-free evidence refresh at the committed baseline (2026-10-16)

**Authority:** the Human PO, 2026-10-16 (session 2), approved all of the following:
- the baseline;
- isolation by `git archive`;
- dependencies via `composer install` from the baseline's lock, inside the copy only;
- the 27-file scope;
- the run mode;
- this folder;
- stop on the first failure.

**Purpose:** fresh evidence for the committed code. It is for the C-1 (`PBDIGIT-268`) G3 decision and the Slice 2a (`PBDIGIT-271`) Principal Reviewer review. It replaces nothing; the 2026-10-11 evidence stays as history.

## Baseline and isolation
- **Baseline:** `83a059f98eccc5f36b9fb00c361b7029c4852037`, extracted with `git archive` into a session scratchpad copy. No `.git`, and **the shared working tree was not used**.
- **Excluded by construction:** every uncommitted change in the shared tree. That includes the other session's `UNPARSABLE_KNOWN = []` and the `VoterSlugStep.php` deletion (`PBDIGIT-70`) and its `composer.*` change. The copy has HEAD's `UNPARSABLE_KNOWN = ['app/Http/Middleware/VoterSlugStep.php']`.
- **Isolation check:** by reflection, `App\Contexts\Membership\Domain\Organisational\Member` and `Tests\Architecture\MembershipDecisionCreationTest` load from the copy.
- **Dependencies:**
  - command: `composer install --no-interaction --no-scripts --no-progress --prefer-dist`, from the baseline's `composer.lock` (`lock.sha256`, verified unchanged afterwards);
  - **`--no-scripts`**, because `post-autoload-dump` runs `php artisan package:discover` and Artisan was not authorised. **Disclosure (added 2026-10-16 with the PO's authorisation):** the developer added `--no-scripts` during execution. **It was not part of the PO's approval**, which said "Composer install from HEAD lock". As a result, Laravel's package-discovery cache was not built and `ComposerScripts::postAutoloadDump` did not run in the copy. No run with Composer scripts enabled was made. The effect on these plain-PHPUnit tests is expected to be none, but that is not demonstrated;
  - installed versions match the lock: PHPUnit 11.5.6, nikic/php-parser 5.9.0, Laravel 12.70.0. **No vendor deviation.**

## Run mode (database-free)
- **Command:** `vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php <path>`, exactly as in `commands.txt`.
- **Not loaded:** `phpunit.xml` and its database bootstrap.
- **Why no database:** all 27 files import `PHPUnit\Framework\TestCase`. None uses `Tests\TestCase`, a Laravel testing trait, `DB::`, `Schema::` or PDO, apart from text in scanner patterns and planted source strings.
- **Difference from 2026-10-11:** that run used `phpunit.xml`. This mode is stricter.

## Results (all exit 0)
| File | Scope | Result |
|---|---|---|
| `01-domain` | `tests/Unit/Contexts/Membership/Domain/Organisational` (14 files) | OK (114 tests, 112541 assertions) |
| `02-application` | `…/Application/Organisational` (5 files) | OK (61 tests, 226 assertions) |
| `03-infrastructure` | `…/Infrastructure/Organisational` (2 files) | OK (13 tests, 41 assertions) |
| `04-dateordinal` | `DateOrdinalTest.php` alone | OK (5 tests, 7 assertions) |
| `05-c1-g6` | `C1ExhaustiveDifferentialTest.php` + `G6CorrectionOracleGoldenTest.php` | OK (2 tests, 5786 assertions) |
| `06-arch-*` (6 files) | `tests/Architecture/Membership{DecisionCreation, LedgerIsolation, SemanticKernelPurity, Slice1aBoundary, Slice1bPersistenceBoundary, StorableValidTimeCoverage}Test.php` | 34 + 2 + 5 + 4 + 3 + 1 = **49 tests**, all OK |

**C-1 differential:**
- 15,530 decisions and 0 mismatches are **asserted, not printed**: `assertSame(15530, $decisions)` at `C1ExhaustiveDifferentialTest.php:64`, plus empty-mismatch assertions.
- They are shown only through the passing run of `05-c1-g6` (and `01-domain`).

**Compared with 2026-10-11:**
- the same counts in every group;
- `MembershipDecisionCreationTest` now runs **without** the 3 php-parser deprecations (php-parser 5.9.0, against 5.4.0 then).

## Delta at HEAD `9582de324` (`delta-9582de324/`, authorised by the PO 2026-10-16)
- **Why:** after the baseline, commit `f311c97c4` (`PBDIGIT-70`, another session) deleted `app/Http/Middleware/VoterSlugStep.php`. It also changed `tests/Architecture/MembershipDecisionCreationTest.php`: `UNPARSABLE_KNOWN = []` and the guard test removed. No other Membership code or test changed between `83a059f98` and `9582de324`.
- **Run:** only `MembershipDecisionCreationTest.php`, at `9582de3241cc6e8bbadf1f8cfb3c00bcd93b9d5c`, in a new `git archive` copy, with `composer install --no-scripts` (explicitly authorised for this run) from that commit's lock. Versions: PHPUnit 11.5.6, php-parser 5.9.0, deptrac 4.7.2. Same run mode.
- **Result:** `OK (33 tests, 34 assertions)`, exit 0 (34 tests at the baseline, minus the removed guard test). The subfolder has its own `SHA256SUMS`.

## Limits (not shown by this evidence)
- PostgreSQL persistence or concurrency;
- HTTP or route authorisation (`PBDIGIT-266`, S-1 … S-11);
- real-data correctness;
- mutation score;
- the wider Membership suite (A1b, which has known errors outside `Organisational`);
- production readiness;
- **any acceptance.** Formal decisions stay with the Principal Reviewer and the Human PO / ARB.

## Files
- `NN-*.out` / `.exit`: PHPUnit output and exit code, copied unchanged;
- `commands.txt`: the exact commands;
- `env.txt`: baseline, toolchain and dependency method (path redacted);
- `lock.sha256`: the baseline lock hash;
- `SHA256SUMS`: hashes of every file in this folder except itself.
