# Option E (R-1a-6) — test evidence, 2026-10-10

Captured output (`.out`) and exit status (`.exit`, from `$?`) of every run, copied unchanged from the session scratchpad. `SHA256SUMS` covers every file here. No database was used: all suites are plain PHPUnit `TestCase`s.

| Stage | Code state | Command | Result |
|---|---|---|---|
| 1 baseline | HEAD `bab1a9fbb` (`env-baseline.txt`) | `vendor/bin/phpunit` on Application, Domain, `DateOrdinalTest`, three Membership architecture tests | all exit 0: 34 / 89 / 5 / 4 + 3 + 5 |
| 2a | `bab1a9fbb` + new tests | Application suite | **exit 255, malformed test**: the helper `run()` in `AppendOutcomeMappingTest` collided with PHPUnit's final `TestCase::run()`. Fixed by renaming it to `admitThrough()`; no behavioural deviation |
| 2b RED | same, corrected | Application suite (`--testdox`) | exit 1: 49 tests, **7 assertion failures** as predicted: E1a–d (`appended`), E5 (`concurrency_conflict`), E7 (appended), E9 coverage (case missing). Guard tests E2a–d, E3, E4, E8, E9 per-case pass |
| 2 E10 | same | `MembershipStorableValidTimeCoverageTest` | exit 0 (guard test) |
| commit | `0e4e5ac7a` | — | the tests |
| 3 GREEN | `0e4e5ac7a` + implementation | Application, `DateOrdinalTest`, E10 | all exit 0: 49 / 5 / 1 |
| commit | `af495c838` | — | the implementation |
| 4 regression | HEAD `af495c838`, clean `app/` and `tests/` (`env-regression.txt`) | Domain, Application, `DateOrdinalTest`, four Membership architecture tests | all exit 0: 89 (112487 assertions, identical to the baseline) / 49 / 5 / 4 + 3 + 5 + 1 |

**Not covered here:** any PostgreSQL behaviour. E1–E5 run against the adapter only in Part 2, under its isolation gates.
