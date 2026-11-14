# Evidence: PBDIGIT-345 task 5c, founding step 5 (operational, L-1) (2026-11-14)

| File | Shows |
|---|---|
| `t5c-01-red.txt` | RED: probe and contacts ports, `missing()`, the Identity adapter missing (21 errors, 1 failure) |
| `t5c-02-green.txt` | GREEN: Platform unit tests and the Identity boundary, 66 (T-13 for each fact alone; earlier-step refusals; Identity unavailable; T-9 at step 5) |
| `t5c-03-red-pg/` | RED: `PostgresOrganisationReadinessProbe` missing |
| `t5c-04a-fixtures-refused-by-the-database/` | two planted defects refused by the database's own foreign keys; fixtures corrected (the wrong identity also removes the clock row, so both are named) |
| `t5c-04-green-pg/` | the Platform PostgreSQL suite: **43 GREEN** |
| `t5c-05-ratchet-red.txt` | the ratchet reports exactly the 3 new Platform-to-Identity imports; then recorded, ceilings 197→200 and 264→267 |
| `t5c-06a-db-free-probe-named-ledger-tables-R5.txt` | **R5 refused the first probe** (it named a ledger table); Membership's readiness function added (guard unchanged); inventory regenerated |
| `t5c-07a-isolated-cluster-all-before-R5-fix/` | the cluster before that change (348, exit 0) |
| `t5c-06-db-free.txt` | database-free: **432 GREEN** |
| `t5c-07-isolated-cluster-all/` | the full cluster: **348, exit 0** |
| `t5c-08-architecture-full.txt`, `.failures` | 352; **the same 4 pre-existing failures**; not passing |
| `t5c-08a-…` | the architecture suite before the fixes (ratchet inventory, R5) |
| `t5c-09-variance-1/2` with JUnit XML | two cluster runs: only 3 existing race tests (Membership, Geography) vary in assertion count; Platform is stable (43 tests, 157 assertions) |
