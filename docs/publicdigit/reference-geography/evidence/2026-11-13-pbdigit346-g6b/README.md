# Evidence: G-6b, objections (PBDIGIT-346), 2026-11-13

| File | Shows |
|---|---|
| `g6b-01-red-domain.txt` → `g6b-02-green-domain.txt` | `ObjectionTest`: RED (no classes), then GREEN, 6 tests (with the purity guard: 12) |
| `g6b-03-red-application.txt` → `g6b-04-green-application.txt` | `ObjectionServiceTest`: RED (no port), then GREEN, 5 tests (with the no-bypass guard: 7) |
| `g6b-05-red-postgres/` → `g6b-06-green-postgres/` | `PostgresObjectionRepositoryTest` on the isolated cluster: RED, then GREEN, 6 tests |
| `mutation-check-g6.php`, `g6b-07-mutation-check.out`, `mutants/*/suite.txt` | copy-loaded mutants M1 to M8 (D-84a; new evidence; outcome versus findings; published only; candidate proposal; lifecycle before prepare; published versus frozen): **8 of 8 killed by assertions, 0 errors** |
| `g6b-07-mutation-check-1-driver-defect-M8-no-tests-executed.out` | **a driver defect, kept:** M8 first "survived" because a broken `--filter` ran no tests ("No tests executed!"). Fixed; M8 killed |
| `g6b-08-db-free.txt` | database-free: 307 GREEN |
| `g6b-09-isolated-cluster-all/` | **the full cluster on the final code: 279 tests, exit 0** |
| `g6b-10-architecture-full.txt`, `.failures` | 343; the same 4 pre-existing failures |
