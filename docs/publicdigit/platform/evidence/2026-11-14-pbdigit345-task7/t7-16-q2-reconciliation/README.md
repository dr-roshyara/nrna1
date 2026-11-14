# Reconciliation of mutant Q2 (Principal's review, 2026-11-14)

**Finding to reconcile:** `../t7-09-mutation-run.txt` records **Q2 ("an unroutable organisation runs without a context") as SURVIVED**, while the Task 7 report said Q2 was caught.

**Why both are true:**
- `t7-09` is the **first** run. At that time Q-5 was proven only by T-23, which runs in a **child** `queue:work` process.
- Copy-loaded mutants exist only in the PHPUnit process, so the child always ran the real middleware, and Q2 survived. That was a test gap.
- The in-process test `OrganisationQueueTest::test_q5_an_unroutable_organisation_job_is_failed_with_its_reason_and_never_runs` was then added.
- The reruns `../t7-11-mutation-q2-rerun.txt` and `../t7-15-mutation-q2-q3-after-test-fix.txt` (already in commit `30c5514fd`) show Q2 **KILLED**.
- `t7-09` is kept unchanged as the historical first result.

**Fresh rerun (this folder):** `t7-16-q2-reconciliation-rerun.txt`.
- Run against the committed HEAD in `head.txt` (`app/`, `tests/` and `config/` clean), with the committed driver `../mutation-check-t7.php`.
- `mutant-q2-suite.txt` and `mutant-q2-EstablishOrganisationContext.php` are the PHPUnit output and the exact mutant.

| Mutant | Result | Kind |
|---|---|---|
| Q2 | **KILLED by an assertion**: 1 failure, **0 errors** ("failed at once, not left for retries: actual size 0 matches expected size 1") | assertion kill |

## Task 7 mutation summary, reconciled

| Mutant | Final classification |
|---|---|
| Q1, Q3, Q4, Q5, Q6, Q7, Q10 | KILLED by assertions |
| **Q2** | **KILLED by an assertion** after the in-process test (this folder); first run SURVIVED (test gap) |
| **Q8** | **KILLED+ERRORS:** an error (`QueryException`: the overridden job reached the missing `jobs` table), **not an assertion kill** |
| **Q9** | **SURVIVED, argued equivalent:** the connection check right after refuses the same objects with the same exception |
| unexplained survivors | **none** |
