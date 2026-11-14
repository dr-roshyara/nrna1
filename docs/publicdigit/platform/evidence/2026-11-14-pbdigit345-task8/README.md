# Evidence: PBDIGIT-345 task 8, fan-out E-3 (2026-11-14)

| File | Shows |
|---|---|
| `t8-01-red.txt` | RED: the fan-out ports don't exist |
| `t8-02-green-unit.txt` | GREEN: Platform unit tests, 89 (T-5 rules: one each, skips with reasons, a failing dispatch recorded, an unreadable registry fails the run) |
| `t8-03-red-pg/` | RED: `PostgresRegisteredOrganisations` doesn't exist |
| `t8-04a-dotted-operation-name-read-as-nested-config/` | **a bug found by the test:** `config("…fan_out.{$op}")` read `t8.visit` as nested keys; the command now indexes the list directly |
| `t8-04-green-pg/` | the Platform PostgreSQL suite: **67 GREEN** (T-5 end to end with a **real** `queue:work organisation` worker) |
| `t8-05a-guard-flags-a-legitimate-dispatcher-call.txt` | the task 7 T-25 guard flagged `FanOutJobDispatch`'s call to `OrganisationJobDispatcher` (its pattern matched any `->dispatch(`) |
| `t8-05-db-free.txt` | database-free after the first refinement: 464 GREEN |
| `t8-06-green-pg-with-exit-and-refusals/` | added tests: a failing dispatch gives exit 1 with a report per organisation; distinct refusal messages (not an OrganisationJob; wrong constructor) |
| `t8-07-mutation-run.txt` | **an aborted first launch** (the driver had not been written: "Could not open input file"); nothing ran; kept as is |
| `t8-07-mutation-run-fanout.txt`, `mutation-check-t8.php`, `mutants/` | 7 copy-loaded mutants (no source modified): **6 KILLED by assertions** (F1 unit, F1 db, F3, F4, F5, F6); **F2 KILLED+ERRORS** (the rethrown `LogicException` escapes the run: an error kill, not an assertion kill, although the escape is the defect); **0 survivors, 0 equivalents** |
| `t8-08-isolated-cluster-all/` | the full cluster: **373, exit 0** |
| `t8-09-db-free.txt` | database-free: 465 GREEN |
| `t8-10-architecture-full.txt`, `.failures` | 361; **the same 4 pre-existing failures** |
| `t8-11-red-guard-file-level-heuristic.txt` | **Principal's review:** the refined guard allowed `->dispatch(` whenever the file named `OrganisationJobDispatcher`. Planted "names it, calls another object" and "one good call, one bad" cases: **RED** (both missed) |
| `t8-12-green-guard-per-call.txt` | the per-call rule (the receiver must be declared `OrganisationJobDispatcher $name` in the file, or `app(OrganisationJobDispatcher::class)`): GREEN, all planted cases distinguished |
| `t8-13-db-free-after-guard.txt` | database-free after the guard change: **465 GREEN** |
| `t8-14-architecture-full-after-guard.txt`, `.failures` | 361; **the same 4 pre-existing failures** (Election lifecycle ×2, GovernanceRuntime, Phase C25 sovereignty); not passing |

**The cluster run `t8-08` predates only the guard change, which touches no code the cluster runs** (an architecture test).

**Not proven here:**
- **T-24** stays open for task 9 (acknowledged sequencing, not a waiver).
- **No real periodic operation is configured** (`organisation_routing.fan_out` is empty).
- **Duplicate protection across runs** is the obligation of each future job (idempotency, design §3.4).
