# Evidence: G-6a, candidates (PBDIGIT-346), 2026-11-13

Guide: `developer_guide/reference_geography/24_g6a_candidates.md`.

| File | Shows |
|---|---|
| `g6a-01-red-domain.txt` → `g6a-02-green-domain.txt` | `CandidateTest`: RED (no classes), then GREEN, 8 tests (with the domain-purity guard: 14) |
| `g6a-03-red-application.txt` → `g6a-04-green-application.txt` | `CandidateServiceTest`: RED (no port), then GREEN, 5 tests |
| `g6a-05-red-postgres/` → `g6a-06-green-postgres/` | `PostgresCandidateRepositoryTest` on the isolated cluster: RED (no table, adapter or binding), then GREEN, 6 tests |
| `g6a-08-db-free-1-no-bypass-guard-caught-write-ports.txt`, `g6a-09-architecture-full-1-no-bypass-guard.txt` | **the accepted no-bypass guard refused `CandidateService` holding the write-side `RevisionRepository` and `StewardRosterStore`.** Fixed by read-only ports `RosterReader` and `RevisionCatalogue` (the write ports extend them); the guard was not changed |
| `g6a-08-db-free.txt` | database-free after the fix: 295 tests GREEN |
| `g6a-09-architecture-full.txt`, `.failures` | 343 tests; the same 4 pre-existing failures |
| `g6a-07-isolated-cluster-all/` | **the full cluster on the final code: 272 tests, exit 0.** `…-before-port-segregation/` began before the fix (also exit 0); kept, but not of record |
