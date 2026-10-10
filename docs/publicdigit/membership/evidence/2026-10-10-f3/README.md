# PBDIGIT-264 F-3 (key first, MemberId collision) — test evidence, 2026-10-10

Authority: PO decisions F3-1 … F3-4 (plan F-3 gate register; Path B). Outputs (`.out`) and exit status (`.exit`, from `$?`) copied unchanged. No database used; no production code changed.

| Stage | Code state | Command | Result |
|---|---|---|---|
| 1 baseline | HEAD `240107642` (`env-baseline.txt`) | Application suite; `MembershipStorableValidTimeCoverageTest` | exit 0: 50 / 1 |
| 2 RED | `240107642` + F3a, F3b-1, F3b-2, F3c in `MemberRepositoryContract` | Application suite (`--testdox`) | exit 1: 54 tests, **4 assertion failures** as predicted: F3a "null is an instance of RuntimeException" (no exception); F3b-1, F3b-2, F3c answered `member_not_found` |
| commit | `d908f93c4` | — | the tests |
| 3 GREEN | `d908f93c4` + test-double fix | Application suite (`--testdox`) | exit 0: 54 |
| commit | `d2a3f2b17` | — | the fix (test double only) |
| 4 regression | HEAD `d2a3f2b17`, clean `app/` and `tests/` (`env-regression.txt`) | Domain, Application, `DateOrdinalTest`, four Membership architecture tests | all exit 0: 89 / 54 (incl. the reference-model property test) / 5 / 4 + 3 + 5 + 1 |

**Not covered:** PostgreSQL. F3a–c run against the adapter only in Part 2 (separately authorised); they are predicted, not verified, to pass there without code change.
