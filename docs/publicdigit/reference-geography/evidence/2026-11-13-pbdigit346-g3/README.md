# G-3a to G-3c evidence (PBDIGIT-346, 2026-11-13)

**Assessment:** `docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-g3-tactical-assessment.md`. **Guide:** `developer_guide/reference_geography/11_…`.

| File | Shows |
|---|---|
| `red-g3a.txt` / `green-g3a.txt` | roster: RED 6 errors (missing types) → GREEN 6 tests |
| `red-g3b.txt` / `green-g3b.txt` | merges: RED 4 errors and 1 failure (missing methods) → GREEN 11 (with G-3a) |
| `red-g3c-invalid-fixture.txt` | **an invalid first RED:** 4 errors from my own fixture (person labels weren't valid references); the helper was corrected |
| `red-g3c.txt` / `green-g3c.txt` | governance: valid RED 12 errors (missing types) → GREEN 12 tests |
| `mutation-check.php` / `.out` | 7 of 7 mutants of the safeguards are killed; no source file is modified (the mutants are loaded copies) |
| `domain.txt` | 163 domain tests pass |
| `db-free-all.txt` | 188 database-free tests pass (with the purity and architecture guards) |
| `isolated-cluster/` | 154 PostgreSQL tests pass (other contexts only) |
| `architecture-full.txt` / `.failures` / `reasons.txt` | 316 tests, 4 failures: names = the pre-Geography baseline; messages = the earlier runs |

**Also recorded:** a PHP 8.4 property hook, used in the first GREEN attempt, failed with a fatal error. The project supports PHP 8.3, so it was replaced with a plain property.

## G-3d (added)

| File | Shows |
|---|---|
| `red-g3d.txt` | RED: 7 errors (missing behaviour) |
| `green-g3d-first-run-old-fixtures.txt` | 11 G-3c tests failed: their fixtures had no conflict declarations (the D-84 / §8 invariant now enforced). The fixtures were updated; the expected outcomes are identical (12 tests, 29 assertions) |
| `green-g3d.txt` | GREEN: 170 domain tests |
| `mutation-check.php` / `.out` | **re-run: 14 of 14 mutants killed** (M8 to M14 are the G-3d safeguards) |
| `domain.txt`, `db-free-all.txt`, `architecture-full.*`, `isolated-cluster/` | **re-run after G-3d:** 170 domain, 195 database-free, 316 architecture (4 pre-Geography failures by name and message), 154 cluster (other contexts) |
