# G-4g evidence (PBDIGIT-346, 2026-11-13): the final G-4 verification

**Acceptance package:** `docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-g4-acceptance-review.md`. **Guide:** `developer_guide/reference_geography/19_…`.

| File | Shows |
|---|---|
| `matrix-first-run.txt` | the 22 matrix tests passed on their first run (verification, not RED) |
| `mutation-check-pg.php`, `mutation-check-pg-1.out`, `-2.out` | the first G-4g mutant runs; **G4's first kill was a fixture setup failure** (see the final runs) |
| `matrix-after-fixture-fix.txt` | the matrix, unmutated, after the bootstrap fix: 22 pass |
| `mutation-check-pg-g4-after-fixture-fix.out` | G4 re-run: killed on assertions |
| `mutation-check-pg-final.out`, `mutation-check-pg-final-classification.out` | **all 8 G-4g mutants killed on their intended assertions; zero setup or infrastructure markers** |
| `g7-domain-level-mutation.out` | G7 without a database: killed by the domain and application tests |
| `mutation-rerun-db-free.out` | G-3 (14), G-4b (5), G-4f application (4) re-run on the final code: all killed |
| `mutation-rerun-pg-1-stopped-at-d5-hang.out`, `d5-hang-before-fix-partial-suite.txt` | **the first PostgreSQL re-run hung on D5 for 22 minutes** (a test-helper defect); stopped |
| `mutation-rerun-pg.out` (G-4c part of the first run), `mutation-rerun-pg-2.out` | the PostgreSQL re-runs after the helper fix: G-4c 2, G-4d 5, G-4e 7, G-4f 2, all killed; each classified by reason |
| `isolated-cluster-all/` | **the full cluster: 220 tests, exit 0, none skipped** |
| `db-free-all.txt` | 221 database-free tests |
| `architecture-full.txt` / `.failures` / `reasons.txt` | 318 tests; the same 4 pre-Geography failures by name and message |
