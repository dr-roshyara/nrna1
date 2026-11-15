# Evidence: PBDIGIT-354 S2-4a, the scheduled resignation declaration and D-205's single exception (2026-11-15)

**Decisions:** D-201, D-204, D-205, D-223, D-239. **Guide:** `developer_guide/membership/29_scheduled_resignation_domain.md`.
**Author:** Claude (Session A), implementation-agent testing, not an independent review. **Domain only: no database, no migration.**

| File | Shows |
|---|---|
| `s24a-01-red-domain.txt` | **RED:** 16 tests; `ScheduledResignation`, `decideScheduledResignationEnd` and the refusal reasons did not exist |
| `s24a-02-green-domain.txt` | GREEN: the whole kernel suite, **279** (263 + 16) |
| `s24a-03-mutation-run.txt`, `mutants-run1/` | 15 mutants, 0 survivors; **K6 killed partly by a test error** (my test read an optional value without null-safety) |
| `s24a-04-mutation-rerun-k6.txt`, `mutants-run2/` | after the test reads null-safely: K6 an assertion kill |
| `mutation-check-s24a.php` | the copy-load driver |

**Also run (not copied: unchanged suites):**
- application 138 and infrastructure 83 unit tests, OK;
- architecture guards `MembershipSemanticKernelPurityTest`, `MembershipDecisionCreationTest` (the new End path is inside `Member`, as R7 requires), `MembershipStorableValidTimeCoverageTest`, `MembershipSlice1aBoundaryTest`, OK.

**No PostgreSQL run:** S2-4a touches no persistence. The full cluster, database-free and architecture regressions are run in S2-4b onward.

## Mutation classification (15, final)

| Class | Count |
|---|---|
| assertion kill | 15 (S1–S8, K1–K7; K6 after the test fix) |
| error / database refusal / equivalent / survived / not run | 0 |

**K7** ("an ordinary End may be future-dated") proves the D-205 exception does not leak into `decideEnd`.
