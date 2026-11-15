# Evidence: PBDIGIT-354 S2-4b, schedule and withdraw a resignation, and the declaration store (2026-11-15)

**Decisions:** D-201, D-204, D-208 §2 (GC-1, GC-2, GC-12), D-238, D-239. **Guide:** `developer_guide/membership/30_schedule_and_withdraw_a_resignation.md`.
**Author:** Claude (Session A), implementation-agent testing, not an independent review. **Throwaway PostgreSQL 15 cluster only** (pinned image in each `run.txt`).

| File | Shows |
|---|---|
| `s24b-01-red-unit.txt` | **RED (unit):** 32 tests; the store double, `fromStorage`, `withdrawnBy` did not exist (16 errors) |
| `s24b-02-green-unit.txt` | GREEN: domain 18 + application 14 |
| `s24b-03-red-pg.txt`, `s24b-03-red-pg-cluster/` | **RED (PostgreSQL):** the adapter and the tables did not exist |
| `s24b-04-full-cluster/`, `s24b-04-cluster-junit.xml` | **final full isolated cluster: 470 OK** (449 at S2-3b + 7 this step + 14 from Session B's merged M1 tests: `GeographyExplorerHttpTest` 10, `PublishedHierarchyQueriesTest` 4) |
| `s24b-04a-platform-run-interrupted/` | an earlier `PlatformPostgres` run: JUnit 86 tests, 0 failures, 0 errors, but PHPUnit crashed while printing, because vendor files were missing **during** the run. Another session updated `vendor` / `composer.lock` concurrently (PHPUnit 11.5.57 → 11.5.58). Superseded by `s24b-04` |
| `s24b-05-db-free.txt` | database-free set: **1062 OK** |
| `s24b-06-architecture-full.*` | full architecture suite: **the same 4 known failures** as the task-13 baseline; nothing new. **They are failures, not passes.** |
| `s24b-07a-membership-unit-first-run-junit.xml` | **a defect found by the legacy Membership unit suite:** 23 errors "function `org_membership_scheduled_resignation_guard` already exists". `migrate:fresh` drops tables, not functions; fixed with `CREATE OR REPLACE FUNCTION` (both migrations) |
| `s24b-07-membership-unit-full.*` | after the fix: 773 tests; **no new failure vs S2-3b**. Against the task-13 baseline only the known flaky `CommitteeSlugAvailabilityServiceTest` differs; none in `…\Organisational` |
| `s24b-08-mutation-run.txt`, `mutants-run1/` | 19 mutants, 0 survivors (classification below) |
| `s24b-09-mutation-rerun-p5.txt`, `mutants-run2/` | P5 after the test fix |
| `mutation-check-s24b.php` | the copy-load driver |

## Mutation classification (19, final)

| Class | Mutants |
|---|---|
| assertion kill | W1, W2, A1–A9, R1, R2, P2, P5 (after the test asserts `isRecorded()` first; it had crashed on a refused outcome) = **15** |
| database refusal (defence in depth) | **P1** (no compare-and-set: the trigger `SCHEDULED_RESIGNATION_FINAL` refuses), **P3** (the one-pending index violation escapes untranslated), **P4** (no withdrawal actor: CHECK `…_state_check`) = **3** |
| fail-closed reload | **P6** (reload drops who withdrew: `fromStorage` refuses the inconsistent row, RuntimeException) = **1** |
| test error / equivalent / survived / not run | 0 |

**Limit:** copy-load mutants cannot reach migration files. The database rules are proved by direct SQL refusals in `PostgresScheduledResignationTest` (`test_the_database_*`), not by mutants.

## Not in this step

- execution on the date and the calendar (S2-4c);
- the withdraw-vs-execute race on PostgreSQL (MI-2), and proof through the organisation routing that the new handlers fail closed (S2-4d).

The store's compare-and-set and its one-pending conflict are proved here, single-threaded.
