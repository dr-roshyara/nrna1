# F-3 clarification (K-1, F3d, D2/D3) — test evidence, 2026-10-10

Authority: Human PO / ARB K-1 … K-4 (plan, "F-3 clarification"). Outputs (`.out`) and exit status (`.exit`, from `$?`) copied unchanged. **Database / connection configuration intentionally omitted; these suites do not connect to a database.** No `.env` or environment variables were read or captured. No production code changed.

| Stage | Code state | Command (`APP` = `tests/Unit/Contexts/Membership/Application/Organisational`) | Result |
|---|---|---|---|
| 1 baseline | HEAD `52b69c4e8` (`env-baseline.txt`) | `vendor/bin/phpunit $APP`; `… tests/Architecture/MembershipStorableValidTimeCoverageTest.php` | exit 0: 54 / 1 |
| 2 F3d alone | + F3d in `MemberRepositoryContract` | `vendor/bin/phpunit $APP/InMemoryMemberRepositoryTest.php --filter test_a_refused_decision_is_rejected_even_under_a_used_key` | exit 0: 1 test (characterisation) |
| 3 | same | `vendor/bin/phpunit $APP` | exit 0: 55 |
| 4 sensitivity | + **temporary mutant** in the double: the accepted-decision check moved behind the key lookup (`3-mutant-vs-head.diff`) | stage-2 command | exit 1: **1 assertion failure**, "null is an instance of class InvalidArgumentException" for `fp1` |
| restore | mutant removed by a deliberate edit | `sha256sum` | `3-double-sha-before-mutant.txt` = `3-double-sha-after-revert.txt` (byte-identical); 0 mutant markers; no diff in `tests/Doubles` |
| 5 restored | same | stage-2 command | exit 0: 1 test |
| 6 regression | `52b69c4e8` + F3d only (`env-regression.txt`) | Domain, Application, `DateOrdinalTest`, four Membership architecture tests | all exit 0: 89 / 55 / 5 / 4 + 3 + 5 + 1 |

**Not covered:** PostgreSQL (F3d runs through `PostgresMemberRepositoryTest` only in Part 2).
