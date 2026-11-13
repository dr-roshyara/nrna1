# G-2 findings F-1 and F-2: evidence (PBDIGIT-346, 2026-11-13)

**Report:** `docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-g2-acceptance-review.md` (revision 2). **Guide:** `developer_guide/reference_geography/09_…`.

| File | Shows |
|---|---|
| `red-CountryChangeSubtreeTest.txt` | **RED F-1:** 10 tests, 7 failures (the missing behaviour); the 3 guards against over-reach passed |
| `red-BoundaryBasisTest.txt` | **RED F-2:** 2 errors (the missing basis kind) and 1 failure (`Other` still accepted) |
| `green-first-run-fixture-defect.txt` | first GREEN run: 1 failure from **my own new fixture** (a unit with no country also triggers D-83); corrected to assert that D-82_BASIS is present, with an instrument control |
| `green.txt` | **GREEN:** 136 domain tests |
| `probe-cross-country-move-after.out` | the revision 1 probe now reports `[G-4]` |
| `db-free-all.txt` | 161 database-free tests pass |
| `isolated-cluster/` | 154 PostgreSQL tests pass (other contexts; Geography has no database code yet) |
| `architecture-full.txt` / `.failures` / `reasons.txt` | 316 tests, 4 failures: names = the pre-Geography slice 1 baseline; messages = the G-2d-2 run |
