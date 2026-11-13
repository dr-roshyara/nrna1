# PBDIGIT-346 G-0: evidence (2026-11-13)

**Raw output; the Principal decides acceptance.**
- **Code under test:** working tree on `4a7476fa8` plus the G-0 changes, committed together with this folder.
- **Cluster:** `postgres:15@sha256:7da5d534…a822`, throwaway.

| File | Cycle | Result |
|---|---|---|
| `cycles/red1.txt` | Identity domain + application, before the classes | **RED**: Tests: 8, Assertions: 0, Errors: 8.. All missing classes |
| `cycles/green1.txt` | after them | **GREEN**: OK (8 tests, 18 assertions) |
| `cycles/red2/` | PostgreSQL adapters, before them | **RED**: Tests: 10, Assertions: 2, Errors: 10, Deprecations: 1.. Missing classes and tables |
| `cycles/green2/` | first attempt | **FAILED, a test defect:** Tests: 10, Assertions: 27, Failures: 4, Deprecations: 1.. Table-wide counts saw other tests' rows (the harness keeps data between tests) |
| `cycles/green2b/` | counts scoped to each test's own ids | **GREEN**: Tests: 10, Assertions: 30, Deprecations: 1. |
| `cycles/red3.txt` | Geography port/adapter + architecture guards | **RED**: Tests: 11, Assertions: 8, Errors: 6, Failures: 2.. Missing classes, plus one over-broad guard (it flagged a factory) |
| `cycles/green3.txt` | after them, with the guard narrowed to lookups | **GREEN**: OK (19 tests, 36 assertions) |
| `full-run/` | all isolated suites | exit 0: Tests: 154, Assertions: 16206, Deprecations: 1. (Membership 135 + Platform 9 + Identity 10) |
| `architecture-full.txt` / `.failures` | `tests/Architecture` | Tests: 310, Assertions: 955, Failures: 4, Deprecations: 4, PHPUnit Deprecations: 5, Skipped: 1. The same 4 pre-existing failures by name |
| `final-unit-and-architecture.txt` | all G-0 database-free tests | OK (19 tests, 36 assertions) |

**Not proved:** that the race test would fail without the advisory lock. No mutation run was made; this is a known limit.
