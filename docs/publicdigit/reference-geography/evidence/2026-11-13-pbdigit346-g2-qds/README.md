# Q-DS / D-229: evidence (PBDIGIT-346, 2026-11-13)

**Report:** `docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-g2-acceptance-review.md` (revision 3). **Guide:** `developer_guide/reference_geography/10_…`.

| File | Shows |
|---|---|
| `red.txt` | **RED:** 4 tests, 3 errors (`AssessmentKind::DuplicateSearch` missing), 1 failure (`BasisKind::DuplicateSearchAssessment` still existed) |
| `green-before-old-tests.txt` | after the change: exactly 2 earlier assertions fail, because they encode the old I-4 reading (a duplicate search as a basis); updated under D-229 |
| `green.txt` | **GREEN:** 140 domain tests |
| `db-free-all.txt` | 165 database-free tests pass |
| `isolated-cluster/` | 154 PostgreSQL tests pass (other contexts only) |
| `architecture-full.txt` / `.failures` / `reasons.txt` | 316 tests, 4 failures: names = the pre-Geography baseline; messages = the earlier runs |
