# Evidence: PBDIGIT-345 task 11, the operator channel E-4, technical only (2026-11-15)

| File | Shows |
|---|---|
| `t11-01-red.txt`, `t11-02-green-unit.txt` | `OperateTechnically`: RED, then Platform unit tests 104 GREEN (T-16.1, the refusals including T-16.3 and T-16.5, nothing-to-repair, T-16.6, T-16.8) |
| `t11-03-red-pg/` | RED: the central `platform_technical_operations` migration doesn't exist |
| `t11-04a-test-compared-rows-by-identity/` | a test defect (`assertSame` on database rows); fixed by comparing migration names |
| `t11-04-green-pg/` | the Platform PostgreSQL suite: **96 GREEN**, through the real `platform:operate` and a real repair child |
| `t11-05-db-free.txt` | database-free (ratchet, R5, the process guard extended to `repair()`, the default-connection guard): **551 GREEN** |
| `t11-06-architecture-full.txt`, `.failures` | 364; **the same 4 pre-existing failures** (Election lifecycle ×2, GovernanceRuntime, Phase C25 sovereignty); not passing |
| `t11-07-isolated-cluster-all/` | the full cluster: **401, exit 0** |
| `t11-08-mutation-run.txt`, `mutants-t11run/` | 9 copy-loaded mutants. **First run:** 8 KILLED by assertions; **O3 (db) KILLED+ERRORS** (an `ErrorException`: a test indexed an empty record list) |
| `t11-09-operator-test-tightened/`, `t11-10-mutation-rerun-o3.txt`, `mutants-t11rerun/` | the test now asserts the record exists first. Rerun: **O3 (db) KILLED by assertion** |
| `t11-11-platform-suite-final/` | the Platform PostgreSQL suite, final: **96 GREEN** |

## Final mutation classification (9)

| Kind | Mutants |
|---|---|
| assertion kills | O1, O2 (unit and db), O3 (unit and db), O4 (unit and db), O5, O7 |
| database-refusal or error kills | none (after the rerun) |
| equivalent | none |
| survivors | none |

## Method limits (stated, not hidden)

- **Copy-loaded mutants never reach CHILD processes.** The repair itself runs in `platform:prepare-organisation-database --repair`, so its "no business read" safety is proven:
  - by the **runtime** counter test on the real child (T-16.2, with a positive control);
  - statically by R5 (`MembershipLedgerIsolationTest`: Platform code cannot name a ledger table).
- **A mutant of a static scanner is meaningless,** because the scanner reads files on disk. None was used.
