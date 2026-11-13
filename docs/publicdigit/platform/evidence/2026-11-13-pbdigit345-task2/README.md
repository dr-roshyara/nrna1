# Evidence: PBDIGIT-345 task 2, the registry and ResolutionService (T-2), 2026-11-13

| File | Shows |
|---|---|
| `t2-01-red-domain.txt` → `t2-02-green-domain.txt` | `ServedOrganisationSnapshotTest`: RED (no `snapshot`/`fromSnapshot`), then GREEN; Platform units 29 (the 21 task-1 tests unchanged) |
| `t2-03-red-application.txt` → `t2-04-green-application.txt` | `ResolutionServiceTest`: RED (no service), then GREEN; Platform units 35 |
| `t2-05-red-postgres*` → `t2-06-green-postgres*` | `PostgresServedOrganisationRepositoryTest` on the isolated cluster: RED (no migration, no adapter), then GREEN, 7 tests |
| `t2-07-red-wiring/` → `t2-08-green-postgres*` | production wiring: RED (`ServedOrganisationRepository` not instantiable), then GREEN. The whole Platform suite: 17 tests |
| `t2-09-isolated-cluster-all/` | **the full cluster on the final code: exit 0** (see `suite.txt`) |
| `t2-10-db-free-platform.txt` | Platform units: 35 GREEN |
| `t2-11-architecture-full.txt`, `.failures` | 341 tests; the same 4 pre-existing failures; the ratchet unchanged (no new violation) |
