# Evidence: PBDIGIT-354 S2-4d-Q5, D-240 "a pending scheduled resignation takes effect first" (2026-11-15/16)

**Decision:** D-240 (Q-5 Option A, Human Arb Chief, 2026-11-15; register §11.140, commit `83cfb890d`). **Guide:** `developer_guide/membership/33_a_pending_scheduled_resignation_takes_effect_first.md`.
**Author:** Claude (Session A), implementation-agent testing, not an independent review. **Throwaway PostgreSQL 15 cluster only.** No migration.
**Baseline:** `0b25d6643` (S2-4d) → `83cfb890d` (D-240 recorded) → this step.

| File | Shows |
|---|---|
| `q5-01-red-domain.txt` | **RED:** `RefusalReason::ScheduledResignationTakesEffectFirst` did not exist |
| `q5-02-red-pg.txt`, `q5-02-red-pg-cluster/` | **RED (PostgreSQL), 6 tests:** 3 errors (the reason / outcome did not exist) and **1 failure showing the old behaviour**: a competing resignation (8 Nov) and `End(death)` (5 Nov), which D-240 now refuses, were recorded; the later End stood and the member's date (3 Nov) was lost |
| `q5-03-red-mapping.txt` | **RED:** `AppendOutcome::RefusedByDomain` absent from the mapping table |
| `q5-04-scheduled-resignation-pg/` | all scheduled resignation PostgreSQL tests: **34 OK** (precedence 7, race 5, store 10, execution 12) |
| `q5-06-full-cluster/`, `q5-06-cluster-junit.xml` | **full cluster: 522 OK** (S2-4d 512 + 10). `D-240 free races, the competing End's refusals: {"period_not_found":8}` |
| `q5-07-db-free.txt` | **1074 OK** (1073 + 1) |
| `q5-08-architecture-full.*` | 367 tests: **the same 4 known failures**; nothing new. **They are failures, not passes.** |
| `q5-09-membership-unit-full.*` | legacy Membership unit: compared with S2-4d, only the known flaky `CommitteeSlugAvailabilityServiceTest` differs |
| `q5-05-mutation-run.txt`, `mutants-run1/`, `mutation-check-q5.php` | 7 mutants, **all assertion kills** (below) |
| `q5-10-code.diff` | the code and test change of this step |

## Mutants (7)

| Mutant | Proves |
|---|---|
| Q1 an End ON the scheduled date allowed | "on or after", not "after" |
| Q2 a final declaration still refuses | only a pending declaration counts |
| Q3 the append does not check | the rule is enforced (all paths) |
| Q4 the confirming step's own End not exempt | execution still works |
| Q5 another period's declaration counts | scoped to the declaration's period |
| **Q6 no membership-row lock** | **a scheduling in flight would be missed: the forced race catches it** |
| Q7 a record-time refusal retried as a conflict | the refusal is returned with its reason |

## Honest notes

- **No person death fact exists in the code.** D-240 is applied to the death-related Membership End (`RecordDeath` → `End(death)`). Ownership and naming are PBDIGIT-356.
- **The exclusion push path is S2-6 (not built).** The rule is proved on an `End(exclusion)` appended directly; S2-6 maps the refusal to "inapplicable".
- **In the free races the confirming step always reached the lock first.** The competing-End-first order (refused while pending, nothing written) is proved by the sequential precedence tests; "execution first" and "scheduling in flight" are forced.
