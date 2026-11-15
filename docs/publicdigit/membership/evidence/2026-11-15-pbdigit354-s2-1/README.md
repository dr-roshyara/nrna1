# Evidence: PBDIGIT-354 S2-1, Renew and the admission-term rule (2026-11-15)

**Decisions:** D-163, D-177, D-205 (3), D-236. **Guide:** `developer_guide/membership/25_renew_and_the_admission_term_rule.md`.
**Author:** Claude, the implementer; not independent. PostgreSQL 15.19, pinned image, throwaway cluster.

| File | Shows |
|---|---|
| `s21-01-red-domain.txt` | **RED (kernel):** 21 tests, 20 errors, 1 failure: `decideRenew`, `Renew` and the refusal reasons do not exist |
| `s21-02-green-domain.txt` | GREEN: the whole kernel suite, 225 tests (204 existing + 21). *The 22nd Renew test was added later (run 2 of the mutants)* |
| `s21-03-red-application.txt` | **RED (application):** `RenewMembershipHandler` missing; a term-less admission still accepted |
| `s21-04-unit-{Domain,Application,Infrastructure}.txt` | GREEN after implementation: 225 / 104 / 68. **Not kept:** two intermediate failing runs of `ReferenceModelPropertyTest` were overwritten in this file. They showed (1) the independent model still accepting a term-less admission, and (2) a leftover empty Member record changing a later `member_not_found` into `period_not_found`; both were fixed (the model refuses too; no record is created) |
| `s21-05-red-pg/` | **RED (PostgreSQL):** the `renew_until_kind` column does not exist; `Unsupported fact kind: Renew` |
| `s21-06-green-pg/` | **1 failure, a real defect in the new CHECK:** a renew row with no term was **accepted** (a CHECK that evaluates to NULL is satisfied) |
| `s21-07-green-pg-check-fixed/` | GREEN after adding `renew_until_kind IS NOT NULL`: 12 tests |
| `s21-08-full-cluster/` | the full cluster: 423 tests, **1 failure, a defect in my test** (C1 events ordered by `created_at`, second precision, random id) |
| `s21-09-full-cluster-green/` | GREEN after ordering by the event's own `seq`: 423, exit 0 |
| `s21-10-mutation-run.txt`, `mutants-run1/` | 21 mutants: **2 SURVIVED** (K6, R1) and K3 killed only by a test error |
| `s21-11-mutation-rerun.txt`, `mutants-run2/` | after three test corrections and one new test: **0 survivors** |
| `s21-12-full-cluster-final/`, `s21-12-cluster-junit.xml` | **the full cluster on the final code: 423 tests, exit 0**, with per-test results. Checked: no output sections; "password" occurs only in a test name |
| `s21-13a-db-free-ledger-guard-red.txt`, `s21-14a-architecture-full-ledger-guard-red.txt` | **a 5th architecture failure:** `MembershipLedgerIsolationTest` flagged the new **central** ledger migration (it names `org_membership_facts` outside the Membership folder) |
| `s21-13-db-free.txt` | after adding that one exact file to the guard's list of the ledger's own migrations: **942 tests OK** |
| `s21-14-architecture-full.txt`, `.failures` | the full architecture suite: 364 tests, **4 failing: the same 4 pre-existing failures** as the task 13 list, by method; not passing |
| `s21-15-membership-unit-full.txt`, `.failures` | the legacy Membership unit suite: 655 tests, 58 failing. **No new failure** against the task 13 list: the differences are inside the known flaky `CommitteeSlugAvailabilityServiceTest`; none in `…\Organisational` |
| `mutation-check-s21.php` | the copy-load driver (no source file is modified) |

## Mutation classification (final run, 21 mutants)

| Class | Count | Mutants |
|---|---|---|
| assertion kill | 17 | K1–K4, K6, K8, R1–R3, A1–A6, P3, P4 |
| database refusal (PostgreSQL CHECK `23514`), not an assertion kill | 2 | P1, P2 |
| kernel error, not an assertion kill | 2 | **K5:** the `Renew` constructor's own invariant refuses the empty segment the removed guard let through. **K7:** without the refusal, correcting a Renew throws a `LogicException` in the kernel |
| survived / equivalent / not run | 0 | first run: K6 and R1 survived, K3 was a test-error kill; all closed by test corrections (both runs kept) |

**Not mutated (limit):** the migrations' CHECKs (migrations are loaded by path, not as classes). They are tested directly by 8 malformed-row cases in `PostgresRenewTest`.

## Not proven here

- **D-177 row 20** (Correct a Renew's `until`): refused pending Q-2.
- **The push shape** (grouped renewals from one resolution): S2-6.
- **Authority in production:** none bound until Governance S3; proven only that the use case cannot be built without one.
