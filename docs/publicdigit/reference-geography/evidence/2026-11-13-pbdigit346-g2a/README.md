# PBDIGIT-346 G-2a: evidence (2026-11-13)

| File | Result |
|---|---|
| `cycles/red-a.txt` | **RED**: Tests: 11, Assertions: 0, Errors: 11.. Missing `Revision`. Before GREEN, two tests were corrected to v3.11's rule that assessments are per version |
| `cycles/green-a.txt` | **FAILED, a test defect** (Tests: 11, Assertions: 35, Failures: 1.): "same content" used two different claim ids, so the digest rightly differed |
| `cycles/green-a2.txt` | **GREEN**: OK (11 tests, 39 assertions) |
| `db-free-all.txt` | all G-0 / G-1 / G-2a database-free tests: OK (67 tests, 913 assertions) |
| `architecture-full.txt` / `.failures` | Tests: 316, Assertions: 964, Failures: 4, Deprecations: 4, PHPUnit Deprecations: 5, Skipped: 1. The same 4 pre-existing failures by name |
