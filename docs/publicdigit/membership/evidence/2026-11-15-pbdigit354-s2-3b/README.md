# Evidence: PBDIGIT-354 S2-3b, resign now and record a death (2026-11-15)

**Decisions:** D-194, D-201, D-203, D-208 §2 (GC-1 … GC-4), D-238. **Guide:** `developer_guide/membership/28_resign_now_and_record_a_death.md`.
**Author:** Claude, the implementer (Session A); **implementation-agent testing, not an independent review.** PostgreSQL 15.19, pinned image. **No migration.**

| File | Shows |
|---|---|
| `s23b-01-red-application.txt` | **RED:** 13 tests; `ResignMembershipHandler`, `RecordDeathHandler` and `DeathEvidenceRequired` did not exist |
| `s23b-02-full-cluster/`, `s23b-02-cluster-junit.xml` | **the full cluster: 449, exit 0** (445 + 3 PostgreSQL + 1 routing). **The PostgreSQL and routing tests were written after the handlers and passed on their first run:** they are evidence of persistence and isolation, and the mutants below show that they can fail |
| `s23b-03-mutation-run.txt`, `mutants-run1/` | 12 mutants, 0 survivors; M3 killed partly by a test error (a test read an optional field without null-safety) |
| `s23b-04-mutation-rerun-m3.txt`, `mutants-run2/` | after the test reads null-safely: M3 an assertion kill |
| `s23b-05-db-free.txt` | database-free: **1028 OK** |
| `s23b-06-architecture-full.txt`, `.failures` | 364 tests, **the same 4 pre-existing failures** as the task 13 list, by method; not passing |
| `s23b-07-membership-unit-full.txt`, `.failures` | legacy Membership unit: 741 tests; **no new failure** (only the known flaky `CommitteeSlugAvailabilityServiceTest` differs); none in `…\Organisational` |
| `mutation-check-s23b.php` | the copy-load driver |

**Unit results at the end:** domain 263, application 138, infrastructure 83.

## Mutation classification (12)

| Class | Count | Mutants |
|---|---|---|
| assertion kill | 10 | M1, M3 (after the test fix), M4, M6–M11, P1 |
| error, not an assertion kill | 2 | **M2:** without the period guard, the kernel's type signature refuses a null period (`TypeError`). **M5:** a blank evidence reference past the boundary is refused by `AuthorityRequest`'s own guard (`InvalidArgumentException`): defence in depth, not a graceful refusal |
| database refusal / equivalent / survived / not run | 0 | |

## Found in this step

- **A gap in S2-3a:** D-208 §2 names an evidence reference in the `record-death` request; S2-3a's `AuthorityRequest` had none. Closed additively (`evidenceRef`, required for `record-death` only).
- **`EndMembership` could record a death without evidence** (GC-4). Now refused; deaths go through `RecordDeath`.
- **A count error in the S2-3a report:** infrastructure was 83, not 71 (correction filed in the S2-3a folder).
- **Outside this step:** PBDIGIT-355 (the decision-creation guard does not list Renew / Suspend / Restore).
