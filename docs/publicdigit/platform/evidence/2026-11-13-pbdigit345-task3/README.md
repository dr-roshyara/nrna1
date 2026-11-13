# Evidence: PBDIGIT-345 task 3, OrganisationContext and one connection per organisation (T-26, T-10 part 1), 2026-11-13

| File | Shows |
|---|---|
| `t3-02-red-postgres-before-rename/` | RED on the isolated cluster (no factory). The unit test file could not be written under its first name, because the project's permission rules block file names containing "Credentials"; the class was renamed `EnvironmentDatabaseLogin` |
| `t3-01-red-unit.txt` → `t3-03-green-unit.txt` | `OrganisationConnectionNamingTest`: RED (no classes), then GREEN; Platform units 41 |
| `t3-04-green-postgres/` | the Platform PostgreSQL suite GREEN: 23 tests, including `OrganisationConnectionFactoryTest` (own database, default untouched, two organisations isolated, **T-26**, release, refusals, wiring) |
| `t3-05-t10-architecture.txt` | T-10 part 1: 2 tests. The detector is proven on a bad snippet; the real classes are clean. **A verification test:** the code already complied |
| `t3-06-isolated-cluster-all/` | **the full cluster on the final code: exit 0** (see `suite.txt`) |
| `t3-07-architecture-full.txt`, `.failures` | 343 tests; the same 4 pre-existing failures |
