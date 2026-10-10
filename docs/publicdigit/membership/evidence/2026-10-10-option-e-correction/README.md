# Option E correction (E4 rename, E4-L legacy replay) — test evidence, 2026-10-10

Authority: Human PO / ARB R1–R4 (plan gate register). Outputs (`.out`) and exit status (`.exit`, from `$?`) copied unchanged from the session scratchpad. No database used; no production code changed.

| Stage | Code state | Command | Result |
|---|---|---|---|
| 1 baseline | HEAD `86baabf23` (`env-baseline.txt`) | Application suite; `MembershipStorableValidTimeCoverageTest` | exit 0: 49 / 1 |
| 2 E4 + E4-L | `86baabf23` + correction tests | `InMemoryMemberRepositoryTest --filter 'legacy_record\|known_key_with_a_matching'` | exit 0: 2 tests (characterisation; the behaviour already existed, so no failing phase) |
| 3 sensitivity | same + **temporary mutant** in the double: R-1a-6 check moved above the key lookup (`3-mutant-vs-head.diff` = file at that moment vs HEAD) | E4-L, E4, E3 | exit 1: **3 assertion failures**, each answering `valid_time_not_storable` instead of `already_applied` / `idempotency_key_conflict` |
| revert | mutant removed with a deliberate edit | `sha256sum` | `3-double-sha-before-mutant.txt` = `3-double-sha-after-revert.txt` (byte-identical); no mutant text remains |
| 4 restored | same | E4-L, E4, E3 | exit 0: 3 tests |
| 5 | same | Application suite | exit 0: 50 tests |
| commit | `fb85c6e24` | — | tests only (the mutant never committed) |
| 6 regression | HEAD `fb85c6e24`, clean `app/` and `tests/` (`env-regression.txt`) | Domain, Application, `DateOrdinalTest`, four Membership architecture tests | all exit 0: 89 (112487 assertions) / 50 / 5 / 4 + 3 + 5 + 1 |

**Not covered:** PostgreSQL (E1–E5 and the E4-L variant run only in Part 2, separately authorised).
