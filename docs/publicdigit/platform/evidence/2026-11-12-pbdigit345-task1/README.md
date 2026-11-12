# PBDIGIT-345 task 1 — ServedOrganisation domain model: evidence (2026-11-12)

**Raw output only; the Principal decides acceptance.**
- **Code under test:** the working tree on `4ff8ac325` (task 0's commit) plus the task 1 changes, **committed together with this folder**.
- *(Likewise, task 0's evidence names `c8a584461` because it was produced before its own commit, `4ff8ac325`.)*
- **Database-free:** `vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php …`.

| File | Cycle | Result |
|---|---|---|
| `cycles/red1.txt` | value objects, before the classes exist | **RED**: Tests: 12, Assertions: 8, Errors: 4, Failures: 8.. Every failure is a missing class |
| `cycles/green1.txt` | after the four value objects | **GREEN**: OK (12 tests, 21 assertions) |
| `cycles/red2.txt` | aggregate tests added | **RED**: Tests: 21, Assertions: 21, Errors: 9.. All 9 errors: `ServedOrganisation` not found |
| `cycles/green2.txt` | after the aggregate | **GREEN**: OK (21 tests, 65 assertions) |
| `cycles/purity.txt` | architecture guard; written after the domain, so it is shown non-vacuous by planted violations, not by a RED on real code | OK (5 tests, 9 assertions) |
| `final-platform-unit-and-purity.txt` | all task 1 tests | OK (26 tests, 74 assertions) |
| `architecture-full.txt` / `.failures` | `tests/Architecture` | Tests: 305, Assertions: 946, Failures: 4, Deprecations: 4, PHPUnit Deprecations: 5, Skipped: 1. The 4 failure names are identical to task 0 and slice 1 (pre-existing) |

- The Membership PostgreSQL suite was **not** re-run: task 1 changes no Membership, infrastructure or test-harness file.
