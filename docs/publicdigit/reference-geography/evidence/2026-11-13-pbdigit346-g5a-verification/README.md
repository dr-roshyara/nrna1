# Evidence: G-5a verification after the Principal review (PBDIGIT-346, PBDIGIT-348), 2026-11-13

| File | Shows |
|---|---|
| `v-01-red-occurrence-ceiling.txt` → `v-02-green-occurrence-ceiling.txt` | **A ratchet defect, found and fixed test-first:** raising an existing entry's `count` could hide a new occurrence (only the entry count was capped). Now `OCCURRENCE_CEILING` = 264. Ratchet: 20 tests GREEN |
| `v-03-domain-edge-cases.txt` | `PlaceBeliefTest` with a dispute withdrawn, R-2 successive merges, and an ending published later: 11 tests. **The 3 new ones passed on their first run** (verification of accepted rules; no defect found) |
| `v-04-postgres-edge-cases/` | the isolated cluster, `PostgresGeographyReadModelTest`: 10 tests. New: no query exposes an unpublished claim of any kind; successive merges are visible only from their own positions; an ending keeps earlier knowledge; a forged position is refused by every query. **They passed on their first run** |
| `v-05-isolated-cluster-all/` | **the full cluster on this code: 230 tests, exit 0** |
| `v-06-db-free.txt` | database-free: 264 tests GREEN |
| `v-07-architecture-full.txt`, `.failures` | 341 tests; **the same 4 pre-existing failures** |

**Not produced:** mutation evidence for G-5a. **Not verified:** any CI run (the branch is not pushed; `gh` is not authenticated here).
