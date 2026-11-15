# Evidence: PBDIGIT-354 S2-2, Suspend and Restore (2026-11-15)

**Decisions:** D-163, D-177, D-205 (3), D-236. **Guide:** `developer_guide/membership/26_suspend_and_restore.md`.
**Author:** Claude, the implementer; not independent. PostgreSQL 15.19, pinned image, throwaway cluster.
**Two commits:** S2-2a `358e66b59` (the kernel only; this README then said "incomplete") and the completing S2-2 commit.

| File | Shows |
|---|---|
| `s22-01-red-domain.txt` | **RED (kernel):** 27 tests; `decideSuspend`, `Suspend`, `Restore` and the refusal reasons did not exist |
| `s22-02-green-domain.txt` | GREEN: the kernel suite, 253 (S2-2a) |
| `s22-03-red-application.txt` | **RED (application):** the two handlers did not exist |
| `s22-04-green-application.txt` | GREEN: the application suite, 113 |
| `s22-05-red-interim-authority.txt` | **RED, a latent defect from S2-1:** the unbound `InterimRoleAuthority` crashed (`UnhandledMatchError`) on Renew, Suspend and Restore instead of denying them |
| `s22-06-red-pg/` | **RED (PostgreSQL):** no `restriction_until_kind` column; `Unsupported fact kind: Suspend` |
| `s22-07-green-pg/` | GREEN: `PostgresSuspendRestoreTest`, 16 |
| `s22-08-full-cluster/` | the full cluster after the routing test: 440, exit 0 |
| `s22-09-mutation-run.txt`, `mutants-run1/` | 22 mutants, 0 survivors; **A3 killed partly by a test error** (a test read the log without first asserting the restore was recorded) |
| `s22-10-mutation-rerun-a3.txt`, `mutants-run2/` | after the test asserts it: A3 an assertion kill |
| `s22-11a-cluster-before-wording-fix/`, `s22-11a-cluster-junit-before-wording-fix.xml` | the full cluster before the fix below: 440, exit 0 (kept; superseded) |
| `s22-12a-db-free-vocabulary-guard-red.txt`, `s22-13a-architecture-full-vocabulary-guard-red.txt` | **a 5th failure, caused by my wording:** `MembershipSlice1bPersistenceBoundaryTest` refused the word "governance" that I had put in the interim adapter's rule reference. Fixed by changing my wording ("authority-owner"); **the guard is unchanged** |
| `s22-10b-mutation-rerun-i1.txt`, `mutants-run3/` | mutant I1 rerun after that change: an assertion kill |
| `s22-12-db-free.txt` | database-free: **981 OK** |
| `s22-13-architecture-full.txt`, `.failures` | 364 tests, **the same 4 pre-existing failures** as the task 13 list, by method; not passing |
| `s22-11-full-cluster-final/`, `s22-11-cluster-junit.xml` | **the full cluster on the final code: 440 test cases, 0 failing or erroring**, exit 0. No output sections in the XML |
| `s22-14-membership-unit-full.txt`, `.failures` | the legacy Membership unit suite: 694 tests; **no new failure** against the task 13 list (the differences are inside the known flaky `CommitteeSlugAvailabilityServiceTest`); none in `…\Organisational` |
| `mutation-check-s22.php` | the copy-load driver (no source file is modified) |

## Mutation classification (22 mutants, final classification of each)

| Class | Count | Mutants |
|---|---|---|
| assertion kill | 18 | K1, K3–K10, R1–R3, A1–A3, I1, P3, P4 |
| database refusal (PostgreSQL CHECK `23514`), not an assertion kill | 2 | P1, P2 |
| kernel error, not an assertion kill | 2 | **K2:** the `Suspend` constructor refuses the empty restriction the removed guard let through. **K11:** without the refusal, correcting a Suspend throws a `LogicException` in the kernel |
| survived / equivalent / not run | 0 | |

**Not mutated (limit):** the migrations' CHECKs (loaded by path). They are tested directly by 11 malformed-row cases.

## Not proven here

- **Correcting the dates of Suspend and Restore** (D-177 row 14): refused until S2-5.
- **Suspension pushed from a resolution:** S2-6.
- **Authority in production:** none bound until Governance S3. Proven only that neither use case can be built without one.
