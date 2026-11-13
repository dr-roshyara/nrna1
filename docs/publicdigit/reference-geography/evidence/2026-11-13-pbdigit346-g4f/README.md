# G-4f evidence (PBDIGIT-346, 2026-11-13)

| File | Shows |
|---|---|
| `red-service.txt` / `green-service.txt` | RevisionService: RED (missing types) → GREEN 200 Geography unit tests |
| `red-closure-pg.txt` / `green-closure-pg.txt` | closure at the persistence boundary: RED 3 failures, **including the race save not waiting (72 ms)** → GREEN 41 |
| `red-wiring.txt` / `green-wiring.txt` | production wiring: RED (unbound ports) → GREEN 3 |
| `nobypass-1-expectation-incomplete.txt` / `nobypass.txt` | the guard found one more real violation than my expectation listed → GREEN |
| `nobypass-real-tree-probe.txt` | a temporary rogue controller in the real app/Http made the real-tree test fail (then removed) |
| `mutation-check-app.php` / `.out` | 4 of 4 application mutants killed |
| `mutation-check-pg.php` / `.out` | 2 of 2 PostgreSQL mutants killed by the deterministic race test |
| `isolated-cluster-all-before-fix/`, `too-many-clients-diagnosis.txt` | **the full run failed 2 tests: "too many clients already"** (connections kept by earlier test applications); diagnosed with a copied, unmodified-in-repo harness guard |
| `isolated-cluster-all/` | after `RefgeoPostgresTestCase` closes connections per test: the full cluster passes 198 tests |
| `db-free-all.txt` | 221 database-free tests pass (with the no-bypass, purity and Identity guards) |
| `architecture-full.txt` / `.failures` | 318 tests, the same 4 pre-Geography failures by name |

**Order notes:** `PostgresPublishedAtlasSource` (a 10-line adapter) was written with the closure GREEN, before the wiring test that covers it. The no-bypass guard is an architecture test: its RED is the deliberate-bypass fixture plus the real-tree probe.
