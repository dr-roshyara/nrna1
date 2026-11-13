# G-4d evidence (PBDIGIT-346, 2026-11-13)

| File | Shows |
|---|---|
| `red-position.txt` / `green-position.txt` | the kernel fix (`0b2ce6dae`): RED 2 errors ("used once") → GREEN 193 Geography tests |
| `red-pg.txt` | RED: 12 errors (missing repository, reader, tables); the new G-4c-obligation test already passed (the transaction was atomic) |
| `green-1-test-expectation-defect.txt` | one failure from **my test expectation** ('NONE' vs 'none'); the equivalence assertion itself passed |
| `green-pg.txt` | GREEN: 24 PostgreSQL tests |
| `mutation-check-pg.php` / `.out` | 5 of 5 mutants killed on the isolated cluster, each by its intended test |
| `db-free-all.txt` | 212 database-free tests pass |
| `isolated-cluster-all/` | every cluster suite: 178 tests pass |
| `architecture-full.txt` / `.failures` | 316 tests, the same 4 pre-Geography failures by name |
