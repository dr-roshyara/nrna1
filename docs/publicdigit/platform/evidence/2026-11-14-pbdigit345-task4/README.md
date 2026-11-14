# Evidence: PBDIGIT-345 task 4, the organisation migration set (2026-11-14)

| File | Shows |
|---|---|
| `t4-01-red-arch.txt` | RED: no organisation set; the checker missed `DB::connection(` in its own planted fixture (fixed) |
| `t4-02a-test-defect-imports/` | a test defect (wrong namespaces for `DatabaseIdentityGuard`, `TenantId`) |
| `t4-02-red-pg/` | RED: the runner doesn't exist (5 errors) |
| `t4-03a-checker-false-positive-comments.txt` | the checker read comments as SQL (now stripped first, like the ratchet) |
| `t4-03-green-arch.txt`, `t4-03b-referenced-tables.txt` | GREEN; the tables the checker sees per organisation migration |
| `t4-04-green-pg/` | GREEN: 5 database tests (isolation, history, refusals, T-8, rollback, parity) with the production wiring |
| `t4-07a-architecture-ledger-isolation-caught-misplacement.*` | **`MembershipLedgerIsolationTest` caught the first placement** of the ledger's organisation migration outside Membership; moved into Membership (as the plan's file table says), guard unchanged |
| `mutation-check-t4.php`, `t4-08-mutation.out`, `mutants/*/` | runner mutants: O1, O5 killed by assertions; O3, O4 killed partly by errors; **O2 survived** (test gap) |
| `t4-09-mutation-o2-rerun.out` | after adding the misrouted-locator test: O2 killed (by error: the mutant really tried to migrate the central database) |
| `t4-05-isolated-cluster-all/` | the full cluster: **333 tests, exit 0** |
| `t4-06-db-free.txt` | database-free (Geography, Identity, guards, ratchet, the three organisation-isolation tests): **365 GREEN** |
| `t4-07-architecture-full.txt`, `.failures` | 347; **the same 4 pre-existing failures**; not passing |
