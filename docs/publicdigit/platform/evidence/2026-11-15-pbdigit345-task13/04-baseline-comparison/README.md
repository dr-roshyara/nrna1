# Legacy baseline comparison: slice 2 (PBDIGIT-345), 2026-11-15

**Rule (plan §7):** "legacy suites no worse than the slice 1 baseline". **Method:** that of `docs/publicdigit/membership/evidence/2026-11-12-pbdigit343-slice1-acceptance/README.md`.

| | |
|---|---|
| **Baseline** | **`5bd25c463`**, the commit before slice 2's first commit `c8a584461`. Slice 1's final head `58e8bb26d` is its ancestor |
| **HEAD** | **`8b6fcd569`** (task 12b), working tree code-identical to it |
| **Baseline tree** | a detached `git worktree` at `5bd25c463`, with **its own copy of `vendor/`** (`cp -a`; checked: not a symlink) and **its own `composer dump-autoload`**. **Checked:** `App\Contexts\Membership\Domain\Organisational\MemberId` resolves to the worktree's file, and no Platform context exists there |
| **Dependencies** | `composer.lock`, `composer.json` and `phpunit.xml` **unchanged** between baseline and HEAD (`git diff --stat` empty), so the copied `vendor/` is the baseline's own |
| **Database** | both sides run through `phpunit.xml`, which pins every PostgreSQL target to `nrna_test`. The worktree reaches it through a **symlink** to the repository's untracked `.env` (not read, not copied). **Sides run one after the other**, never in parallel |
| **Commands** (each side, from its own root) | `php -d memory_limit=-1 vendor/bin/phpunit --do-not-cache-result tests/Architecture`<br>`php -d memory_limit=-1 vendor/bin/phpunit --do-not-cache-result tests/Unit/Contexts/Membership` |
| **Known difference** | HEAD's working tree carries the user's own uncommitted edits to `bootstrap/cache/packages.php` and `services.php` (preserved, not part of this work). The worktree has the committed versions |

## Results

| Suite | Baseline `5bd25c463` | HEAD `8b6fcd569` | By name |
|---|---|---|---|
| `tests/Architecture` | 300 tests; **4 failing** (exit 1) | 364 tests; **4 failing** (exit 1) | **identical:** `Election\ElectionLifecycleStateConsistencyTest`, `ElectionStateMachineConsistencyTest`, `GovernanceRuntime\ConstitutionalBoundaryTest`, `Phase_C25_SovereigntyLeakagePreventionTest`. **Pre-existing, still failing, not passing.** The 64 new tests all pass |
| `tests/Unit/Contexts/Membership` | 618 tests; **55 failing** (54 errors, 1 failure) | 621 tests; **56 failing** (55 errors, 1 failure) | **53 the same.** The difference is entirely inside `CommitteeSlugAvailabilityServiceTest`: 3 cases failed only at HEAD, 2 only at the baseline |

**`CommitteeSlugAvailabilityServiceTest` is flaky, on both sides (`flaky/`).**
- The test file and the classes it imports are **unchanged** since the baseline.
- Five runs per side: the failing cases **vary from run to run on both sides**, from 4 to 6 of 8 at the baseline and 4 to 7 of 8 at HEAD.
- The errors are a foreign-key violation (`geo_administrative_units.country_code` → `countries`) from state the shared `nrna_test` database carries between tests.
- Slice 1's evidence records the same class as flaky.

**Conclusion:** no legacy regression attributable to slice 2. **The legacy suites are no worse than the baseline;** they are not "passing" (4 architecture failures and the Membership legacy failures remain, as before).

## Invalid runs, kept and marked (not used for the conclusion)

- **`baseline-run1-no-env-INVALID/`:** the first baseline run had no `.env` in the worktree, so every database-using test failed with `fe_sendauth: no password supplied` (72 architecture, 69 Membership errors).
- **`head-run1-parallel-INVALID/`:** ran at the same time as the first baseline run, against the same test database.
