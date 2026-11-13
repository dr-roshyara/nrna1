# G-4c evidence (PBDIGIT-346, 2026-11-13)

| File | Shows |
|---|---|
| `red-app.txt` / `green-app.txt` | application services: RED 6 errors (missing port) → GREEN 6 |
| `red-pg.txt` / `green-pg.txt` | PostgreSQL store: RED (missing class; G-4a still green) → GREEN 11 tests, 83 assertions, incl. the 4×5 forked race |
| `mutation-check-pg.php` / `.out`, `mutant-*-suite.txt` | 2 of 2 mutants killed on the isolated cluster, both by the race test; S1's failure shows the G-4a trigger refusing the colliding write |
| `db-free-all.txt` | 209 database-free tests pass |
| `isolated-cluster-all/` | every cluster suite: 165 tests pass |
| `architecture-full.txt` / `.failures` | 316 tests, the same 4 pre-Geography failures by name |

**Not proven:** atomicity of a multi-entry write failing part-way through the inserts (see guide 15).
