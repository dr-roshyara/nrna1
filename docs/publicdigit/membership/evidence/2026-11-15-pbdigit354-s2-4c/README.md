# Evidence: PBDIGIT-354 S2-4c, executing scheduled resignations in the ledger close (2026-11-15)

**Decisions:** D-204, D-205 (2), D-209, D-223, D-239; M-TDD-01 §5.2; LC-4. **Guide:** `developer_guide/membership/31_executing_scheduled_resignations_in_the_close.md`.
**Author:** Claude (Session A), implementation-agent testing, not an independent review. **Throwaway PostgreSQL 15 cluster only.** No migration.

| File | Shows |
|---|---|
| `s24c-00-post-merge-baseline/` | **before S2-4c, on the merged code** (Identity I-1/I-2 `30d438592`, M1 test guide `f372518cd`): cluster **493 OK**; architecture: the 4 known failures. First attempt refused: a cached Laravel configuration (`php artisan optimize`, 15:53) made the test guard refuse every run (it would have pointed at the development database). Cleared with `php artisan optimize:clear` **with the Human Arb Chief's approval**. That also emptied the development application cache |
| `s24c-01-red-pg.txt` | **RED:** 9 tests; `ScheduledResignationExecutor` did not exist |
| `s24c-02-full-cluster/`, `s24c-02-cluster-junit.xml` | **final full cluster: 502 OK** (493 + 9) |
| `s24c-03-db-free.txt` | database-free set: **1071 OK** |
| `s24c-04-architecture-full.*` | **the same 4 known failures**; nothing new; the one-clock-reader guard still holds. **They are failures, not passes.** |
| `s24c-05-mutation-run.txt`, `mutants-run1/` | 8 mutants, 0 survivors; X1 and X2 killed partly by test errors (the tests read `ends(…)[0]` without asserting an End exists) |
| `s24c-06-mutation-rerun-x1-x2.txt`, `mutants-run2/` | after the `onlyEnd()` helper: both assertion kills |
| `s24c-07-membership-unit-full.*` | legacy Membership unit: compared with S2-4b, only 3 cases of the known flaky `CommitteeSlugAvailabilityServiceTest` differ; none in `…\Organisational` |
| `mutation-check-s24c.php` | the copy-load driver |

## Mutation classification (8, final): all assertion kills

| Mutant | Proves |
|---|---|
| X1 the UTC date instead of the calendar | the organisation-local date decides (D-239) |
| X2 `<` instead of `<=` | due ON its date |
| X3 any state taken as due | a withdrawn declaration is never executed, and the close advances |
| X4 a fresh clock reading | recordedAt = the close's reading (inside closedThrough) |
| X5 an ended period fails the close | MI-3 supersede |
| X6 not marked executed | the declaration names its End |
| X7 effective on the recording date | D-205 (2) / D-223 |
| X8 a failed execution swallowed by the closer | MI-10 / LC-4 hold, and one transaction |

## Not in this step (S2-4d)

- withdraw vs execute as two PostgreSQL sessions (MI-2);
- the routing proof: production keeps `NoDueScheduledResignations`, and the new handlers fail closed;
- organisation A cannot affect organisation B.
