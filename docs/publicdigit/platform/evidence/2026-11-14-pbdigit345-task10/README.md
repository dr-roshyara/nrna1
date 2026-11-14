# Evidence: PBDIGIT-345 task 10, Platform's published routing contract and Membership M-1 … M-3 (2026-11-14)

**Decision D-233** (`b69742e44`; proposal §8: the D-3 correction and the consistency guarantee).

| File | Shows |
|---|---|
| `t10a-01-red.txt`, `t10a-02-green.txt` | Platform's adapter `ContainerRoutedOrganisation`: RED, then Platform unit tests 93 GREEN (current, re-asked registry, outage throws, connection for the current organisation only) |
| `t10b-01-red.txt` | Membership adapters RED. **The boundary test also found 2 pre-existing Membership→Platform references** (module installation), which are **recorded deviations** in the ratchet baseline; the test tolerates exactly those |
| `t10b-02-red-pg/` | RED: the four ports have no production binding |
| `t10b-03-green-unit-arch.txt`, `t10b-04-green-pg/` | GREEN: adapters and boundary (6); the Platform PostgreSQL suite: 88 |
| `t10b-05-slice1-tests-unchanged.txt`, `t10b-07-membership-slice1-suite/` | **T-12 part 1:** the slice-1 Membership tests unchanged; the `MembershipPostgres` suite **135 GREEN** |
| `t10b-06-isolated-cluster-all/` | the full cluster: **393, exit 0** |
| `t10b-08-db-free.txt` | database-free (with the ratchet: the 3 published types allowlisted, no new A-3): **540 GREEN** |
| `t10b-09-architecture-full.txt`, `.failures` | 364; **the same 4 pre-existing failures** (Election lifecycle ×2, GovernanceRuntime, Phase C25 sovereignty); not passing |
| `t10b-10-mutation-run.txt`, `mutants-t10run/` | **first mutation run (historical):** N1, N2 unit, N3, N4, N5 KILLED; N2 db and N6 KILLED+ERRORS (an exception stored by the probe; the scripted clock exhausted); N8 KILLED+ERRORS (`QueryException`); **N7 SURVIVED (a test gap: nothing checked WHERE the boundary was closed)** |
| `t10b-11-routing-test-tightened/`, `t10b-12-…`, `mutants-t10rerun/` | tests tightened: the boundary must be closed in A's own clock row and nothing written centrally; answers type-checked; extra clock readings. Rerun: **N7 KILLED by assertion**; N2 db and N6 still had 1 error each (the outage test without a reading) |
| `t10b-13-routing-test-final/`, `t10b-14-…`, `mutants-t10rerun2/` | the outage test given a reading. Rerun: **N2 db and N6 KILLED by assertions** |
| `t10b-15-platform-suite-final/` | the Platform PostgreSQL suite, final: **88 GREEN** |

## Final mutation classification (9)

| Kind | Mutants |
|---|---|
| **Assertion kills** | N1, N2 (unit and db), N3, N4, N5, N6, N7 |
| **Database-refusal kill (not an assertion kill)** | N8: pointed at the default connection, the repository's write is refused by the central foreign key to `organisations` |
| **Equivalent** | none |
| **Survivors** | none (N7's first survival closed by a test, above) |

## Proofs

- **T-7:** in a real E-2 job:
  - complete for A;
  - `CROSS_ORGANISATION` for B and outside any routed operation;
  - `SOURCE_NOT_LEDGER` after a mid-operation flip.
- **§8.2:** the in-flight call reads A only; the next call is refused; a registry outage raises.
- **T-12:** 135 unchanged, plus the organisation-database suite.
- **T-14 part 2:** `MembershipPlatformBoundaryTest`.
