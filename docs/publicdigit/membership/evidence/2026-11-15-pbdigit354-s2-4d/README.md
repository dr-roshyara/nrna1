# Evidence: PBDIGIT-354 S2-4d, withdrawal at ledger time, MI-2 race, MI-4 visibility, routing and isolation (2026-11-15)

**Decisions:** D-201, D-204, D-209, D-223, D-239; M-TDD-01 §5.2, §5.3, §13 rows 11 / 17 / 18. **Guide:** `developer_guide/membership/32_scheduled_resignation_races_routing_and_isolation.md`.
**Author:** Claude (Session A), implementation-agent testing, not an independent review. **Throwaway PostgreSQL 15 cluster only.** No migration.
**Starting point:** `election-flow-updated` at `7aa09fd23` (S2-4a `d85fa0abe`, S2-4b `0904e6e43`, S2-4c `7aa09fd23` all ancestors).

| File | Shows |
|---|---|
| `s24d-01-red-unit.txt` | **RED (unit):** the organisation's date was ignored by the domain, and the store did not decide withdrawal (2 failures) |
| `s24d-00-arch-guard-first-run.txt` | the new wiring guard's first run flagged `DateOrdinal`'s `DateTimeZone('UTC')`, which is date-label arithmetic; G4 narrowed to time-zone **settings**, and the case planted as a non-violation |
| `s24d-08-scheduled-resignation-pg/` | all scheduled resignation PostgreSQL tests: 24 OK (store, execution with MI-4 and part D, race) |
| `s24d-07-routing-and-parity/` | `MembershipThroughRoutingTest` + `OrganisationMigrationsTest`: 19 OK (2 new: routed isolation A/B; production cannot build the use cases) |
| `s24d-02-full-cluster/`, `s24d-02-cluster-junit.xml` | **full cluster: 512 OK** (baseline 502 + 10 new). MI-2 free races: `MI-2 free races, winners: {"executed":8}` |
| `s24d-03-db-free.txt` | **1073 OK** (baseline 1071 + 2) |
| `s24d-04-architecture-full.*` | 367 tests (3 new, passing); **the same 4 known failures**; nothing new. **They are failures, not passes.** |
| `s24d-05-mutation-run.txt`, `mutants-run1/`, `mutation-check-s24d.php` | 8 mutants (below) |
| `s24d-06-membership-unit-full.*` | legacy Membership unit: compared with S2-4c only the known flaky `CommitteeSlugAvailabilityServiceTest` differs (one case newly failing, two now passing) |

## Mutation classification (8)

| Class | Mutants |
|---|---|
| assertion kill | D1 (the organisation date ignored), D2 (withdrawal on the date), **P1 (no clock-row lock in withdraw: the forced race catches it)**, P2 (the stated date decides), P3 (a UTC date) = **5** |
| killed by error, by design | L1 (a store refusal unmapped: the exhaustive `match` fails loudly), P4 (no calendar check: a null calendar crashes; nothing written either way) = **2** |
| equivalent (argued) | **P5** (the close's row lock on due declarations removed): both the close and every withdrawal take the organisation's clock row first, so the declaration row lock adds no ordering; kept as defence in depth = **1** |
| unexpected survivor / not run | 0 |

## Honest deviations

- `RoutedScheduledResignationStore` and its provider binding were written **before** their routing test. Its first run was green, so there is no RED for that adapter. The isolation and fail-closed properties are asserted by the routing test and the guard, and P1–P4 exercise the store underneath it.
- The PostgreSQL withdrawal tests were written before the adapter's `withdraw()`, but not run RED separately: the adapter would not have satisfied the port. Mutants P2–P4 show those tests fail without the behaviour.
- In the free race the close won all 8 rounds, because it reaches the lock first. Both orders are proved by the two forced-interleaving tests.

## Not completed: Q-5 (plan §7d)

An ending effective **on or after** a due-but-unexecuted declaration's date has no accepted rule. **S2-4 is not marked Done.**
