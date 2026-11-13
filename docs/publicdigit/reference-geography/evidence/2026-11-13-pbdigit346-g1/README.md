# PBDIGIT-346 G-1: evidence (2026-11-13)

**Raw output; the Principal decides acceptance.** Database-free unless stated.

| File | Cycle | Result |
|---|---|---|
| `cycles/red1.txt` | ids, dates, intervals | **RED**: Tests: 6, Assertions: 0, Errors: 6.. Missing classes |
| `cycles/green1.txt` | | **GREEN**: OK (6 tests, 27 assertions) |
| `cycles/red2.txt` | claim kinds + slot keys + collision proofs | **RED**: Tests: 15, Assertions: 28, Errors: 8, Failures: 1.. Missing `ClaimKind`, `SlotKey` |
| `cycles/green2.txt` | first attempt | **FAILED** (Tests: 15, Assertions: 94, Errors: 1, Failures: 1.): a test defect (the random generator made a whitespace-only component, correctly refused) and a code defect (`PLATFORM_DEFAULT_LOCALE`'s key had no separator after its kind). Both fixed |
| `cycles/green2b.txt` | after the fixes (run 3 times; randomised) | **GREEN**: OK (15 tests, 795 assertions) |
| `cycles/red3.txt` | claims, basis, I-4, supersession | **RED**: Tests: 9, Assertions: 0, Errors: 9. |
| `cycles/green3.txt` | | **GREEN**: OK (24 tests, 814 assertions) |
| `cycles/red4.txt` | I-1, I-2, I-3 | **RED**: Tests: 7, Assertions: 3, Errors: 5, Failures: 1.. **I-2 passed at once:** structural from cycle 2 (a regression check, not new behaviour) |
| `cycles/green4.txt` | | **GREEN**: OK (31 tests, 807 assertions) |
| `cycles/purity.txt`, `purity2.txt` | purity guard (after the code; planted violations prove it) | first: a helper name clashed with PHPUnit's final `matches()` (fatal; renamed). Then one finding: `$name` in `SlotKey`, a component label, renamed `$label` rather than weakening the personal-data check. Final: OK (6 tests, 9 assertions) |
| `final-db-free.txt` | all G-0 + G-1 database-free tests | OK (56 tests, 871 assertions) |
| `architecture-full-RED-g0-migration-defect.txt` | `tests/Architecture` | **72 errors: a G-0 defect,** found here. The G-0 migration's `CREATE FUNCTION` broke the second `migrate:fresh` of `nrna_test`. Fixed in `67425de0e` |
| `architecture-full-run1.txt`, `run2.txt` / `.failures` | after the fix, two consecutive runs | Tests: 316, Assertions: 964, Failures: 4, Deprecations: 4, PHPUnit Deprecations: 5, Skipped: 1. The same 4 pre-existing failures by name |
| `identity-postgres-after-fix/` | `IdentityPostgres` on a fresh cluster | exit 0: Tests: 10, Assertions: 30, Deprecations: 1. |
