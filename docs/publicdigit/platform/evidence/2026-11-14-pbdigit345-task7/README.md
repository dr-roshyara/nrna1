# Evidence: PBDIGIT-345 task 7, the organisation queue E-2 (2026-11-14)

**Step 1** (T-19 before any change; commit `668469223`): see `README-step1.md`.

| File | Shows |
|---|---|
| `t7-03-red-envelope.txt` | RED: `OrganisationJob` doesn't exist |
| `t7-04-red-pg/` | RED: the `organisation_jobs` migration doesn't exist (8 errors) |
| `t7-05-green-unit.txt` | Platform unit tests: 86 GREEN |
| `t7-06-green-pg/` | the Platform PostgreSQL suite: **60 GREEN**, with real `queue:work organisation` child workers |
| `t7-07-t19-unchanged.txt`, `t7-07-t19-step4-after-change/` | **T-19 byte-identical to step 1**; re-run after the change: GREEN (with T-19 step 2) |
| `t7-08-db-free.txt` | database-free, with `OrganisationQueueArchitectureTest`: 461 GREEN |
| `t7-09-mutation-run.txt`, `mutation-check-t7.php`, `mutants/` | 10 copy-loaded mutants (no source file modified): 7 KILLED; Q8 KILLED+ERRORS (a `QueryException`: the overridden job reached the missing `jobs` table, so not an assertion kill); Q9 SURVIVED as an argued equivalent (the connection check right after refuses the same objects with the same exception); **Q2 SURVIVED as a test gap** |
| `t7-10-q5-in-process/`, `t7-11-mutation-q2-rerun.txt` | Q2's cause: T-23 runs in a CHILD worker, which copy-loaded mutants never reach. A new in-process test of Q-5 was added; Q2 is then **KILLED** |
| `t7-12a-cluster-failed-jobs-rows-outlive-the-test-transaction/` | the full cluster had 1 failure: the in-process worker's failer writes `failed_jobs` outside the test transaction, so rows from an earlier test were counted. Both tests now count only their own rows |
| `t7-12-isolated-cluster-all/` | the full cluster: **366, exit 0** |
| `t7-13-db-free.txt` | database-free: **461 GREEN** |
| `t7-14-architecture-full.txt`, `.failures` | 360; **the same 4 pre-existing failures**; not passing |
| `t7-15-mutation-q2-q3-after-test-fix.txt` | Q2 and Q3 re-run after that test fix: both KILLED |

**Limit of the copy-load method:** child worker processes (T-3, T-22, T-23) always run the real code, so the mutants are judged by the in-process tests. Every mutated safeguard has an in-process test.

**Not run here: T-24.** It is carried to task 9 (the organisation outbox and relay do not exist yet).
