# Evidence: PBDIGIT-345 task 12b, the restore drill and in-place recovery (T-17, D-234) (2026-11-15)

**Decision:** D-234, Option A, restore in place (`091b5ed72`). The locator is not changed.

| File | Shows |
|---|---|
| `t12b-01-red-unit.txt` | **RED:** the use-case tests for `restore-drill` and `take-out-for-restore` fail (port missing) |
| `t12b-02-green-unit.txt` | GREEN: Platform unit tests 108 |
| `t12b-03-red-pg/` | **RED:** `RestoreDrillTest` fails (adapter missing) |
| `t12b-04-green-pg/` | **A real finding:** the host's pg_dump 18 writes `SET transaction_timeout`, which PostgreSQL 15 rejects. The drill and the procedure must use tools of the server's major version |
| `t12b-05-restore-pg/` | GREEN with the cluster's own tools (podman exec): 4 tests |
| `t12b-06-mutation-run.txt`, `mutants-t12brun/` | 8 mutants. **V1 not run** (a driver defect: its text occurs twice; no suite file). V2–V7 assertion kills. **V8 SURVIVED:** a copy of a healthy database equals the live one, so no test showed whether the drill read the copy |
| `t12b-07-copy-not-live-pg/` | a new test: the copy is corrupted in transit (its identity table dropped after pg_restore) while A stays healthy; the drill must fail. 5 tests GREEN |
| `t12b-08-mutation-rerun.txt`, `mutants-t12brerun/` | rerun of all 8 (V1's site corrected): **8 of 8 assertion kills** |
| `t12b-09-isolated-cluster-all/` | the full cluster before the config edit: 410, exit 0 |
| `t12b-10a-db-free-config-red.txt` | the config used `storage_path()`, which an architecture test reading config outside Laravel cannot call: 2 errors |
| `t12b-10-db-free.txt` | after the fix (`dirname(__DIR__)`): **555 GREEN** |
| `t12b-11-architecture-full.txt`, `.failures` | 364; **the same 4 pre-existing failures** as the baseline; not passing |
| `t12b-12-isolated-cluster-final/` | the full cluster on the final code: **410, exit 0** |

**Final mutation classification (8):**
- 8 assertion kills;
- no database-refusal kills, equivalents, survivors or test errors.

V8's first survival was closed by a test, and V1's first non-run by fixing the driver. Both first runs are kept.

**T-17 criteria → tests:** see guide 12 (`developer_guide/platform/12_outage_and_restore.md`), section "Proofs".
