# Evidence: PBDIGIT-345 task 12a, outage refusal and recovery (T-11, T-18) (2026-11-15)

| File | Shows |
|---|---|
| `t12-01-first-run/` | **RED (the tests found two defects):** (1) A's database down left `org_<A>` registered after a failed establish; (2) the selection during a cluster outage answered **500** (a raw `PDOException`). Also a test defect: the T-18 test expected a selection to succeed during the outage; it must be 503 |
| `t12-02-after-fixes/` | GREEN: 3 outage tests (factory cleanup; E-1 and the selection treat `PDOException` as 503; the test corrected) |
| `t12-03-platform-suite/` | the Platform PostgreSQL suite: 99 GREEN |
| `t12-04-mutation-run.txt`, `mutants-t12run/` | 3 copy-loaded mutants: **U1, U3 KILLED (assertions); U2 SURVIVED** (the entry's `PDOException` catch was untested: the outages happened to surface as `QueryException` there) |
| `t12-05-raw-pdo-test/`, `t12-06-mutation-rerun-u2.txt`, `mutants-t12rerun/` | a deterministic test (a dependency raising a raw `PDOException`): 503, selection kept. Rerun: **U2 KILLED (assertion)** |
| `t12-07-isolated-cluster-all/` | the full cluster: **405, exit 0** |
| `t12-08-db-free.txt` | database-free: **551 GREEN** |
| `t12-09-architecture-full.txt`, `.failures` | 364; **the same 4 pre-existing failures**; not passing |

**Final mutation classification (3):** U1, U2, U3 assertion kills; no database-refusal kills, equivalents or survivors (U2's first survival closed by a test; the first run is kept).

**T-17 (the restore drill) is NOT in this evidence:** it awaits the Principal's decision (in place vs relocation).
