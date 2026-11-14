# Evidence: PBDIGIT-345 task 5a, founding steps 1–2 (2026-11-14)

| File | Shows |
|---|---|
| `t5a-01-red.txt` | RED: `FoundingApplication` and `FoundOrganisation` don't exist (8 errors) |
| `t5a-02-green.txt` | GREEN: 8 tests (validation, register/restore, T-14 part 1, steps 1–2, T-9 crash at each step, different input refused) |
| `t5a-03-red-pg/` | RED: the central migration doesn't exist |
| `t5a-04a-corrupt-row-fixture-needed-founding-columns/` | an existing test's planted corrupt row lacked the new required columns: given valid founding columns, the grant stays the only corrupt part |
| `t5a-04-green-pg/` | the Platform PostgreSQL suite GREEN: 30 tests |
| `t5a-05-unit.txt` | Platform unit tests: 49 GREEN |
| `t5a-06-migration-stop/` | the repository tests with the migration-stop test: 11 GREEN |
| `t5a-07-isolated-cluster-all/` | the full cluster: **336 tests, exit 0** |
| `t5a-08-db-free.txt` | database-free (Platform, Geography, Identity, guards): **411 GREEN** |
| `t5a-09-architecture-full.txt`, `.failures` | 347; **the same 4 pre-existing failures**; not passing |
