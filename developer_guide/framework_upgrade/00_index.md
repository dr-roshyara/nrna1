# Framework Upgrades — Developer Guide

**Audience:** engineers who maintain PublicDigit's framework and dependency stack, or who will perform the next major upgrade.

**Purpose:** each upgrade gets one step guide. It covers what changed, what broke, how it was fixed, and how to repeat the method safely.

| Step | Guide | Upgrade | Decision record |
|---|---|---|---|
| 01 | [`01_laravel_12_upgrade.md`](./01_laravel_12_upgrade.md) | Laravel 11.41.3 → 12.70.0 · Laravel-Excel 3.1 → 4.0 (PhpSpreadsheet 1 → 5) · PHP floor ^8.2 → ^8.3 | `docs/publicdigit/adr/ADR_20261015_2346_Laravel_12_And_Excel_4_Upgrade.md` |

## The method (applies to every upgrade)

1. **Approve a plan first** (EP-01). The approval covers the plan, not just the request.
2. **Isolate:** use a dedicated branch and a clean working tree (stash unrelated work).
3. **Baseline before changing anything:**
   - full test suite with a JUnit log;
   - PHPStan (`phpstan-greenfield.neon`);
   - deptrac (`deptrac.yaml`);
   - tenant gate (`scripts/enforce-tenantid.sh`);
   - `composer audit`;
   - copies of `composer.json` / `composer.lock`.
4. **Targeted update only:** run `composer update <named packages> --with-all-dependencies`, always with `--dry-run` first. Never run an unscoped `composer update`.
5. **Compare per test case, not per total:** only `PASS → FAIL` transitions are upgrade effects.
6. **Stop on surprises:** any change the plan did not foresee goes back for approval before it is applied.
7. **Keep the Domain layer untouched:** the framework is infrastructure.

## Test-database safety
Run test suites only through `phpunit.xml` (database `nrna_test`). Never use `--no-configuration` on a folder; see the 2026-10-13 incident in `.claude/MEMORY.md`. Full runs need `php -d memory_limit=4G` (the 128 MB default aborts the suite; PBDIGIT-298).
