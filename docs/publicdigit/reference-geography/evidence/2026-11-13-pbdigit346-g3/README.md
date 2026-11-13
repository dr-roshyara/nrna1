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
