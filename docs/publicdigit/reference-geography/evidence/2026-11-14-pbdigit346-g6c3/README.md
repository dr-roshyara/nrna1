# Evidence: G-6c-3, Identity merges and reversals with their D-90 notices as one act (PBDIGIT-346), 2026-11-14

| File | Shows |
|---|---|
| `c3-10-red/` | RED: the consumer cannot be built on the ledger (9 errors); the person index is not written at publication; the rollback test fails |
| `c3-11-green/` | GREEN: `IdentityNoticeImpactTest` with the roster-store consumer test: 11 tests, 94 assertions |
| `mutation-check-g6c3.php`, `c3-12-*`, `c3-13-*` | first mutation run. C3 did not parse (a driver defect, fixed). **Some kills were errors** (tests indexed `[0]` before checking the count): A1, A2 and A9 were tightened with `assertCount` |
| `c3-14-mutation-check-final.out`, `mutants/*/` | **final run: C1 to C6 all killed by assertions, 0 errors, 10 tests executed in each run** |
| `c3-15-unmutated/` | the unmutated `IdentityNoticeImpactTest`: 10 tests, 95 assertions, GREEN |
| `c3-16-db-free.txt` | database-free (unit, Identity, guards, ratchet): **323 GREEN** |
| `c3-17-isolated-cluster-all/` | **the full cluster: 299 tests, exit 0** |
| `c3-18-architecture-full.txt`, `.failures` | 343; **the same 4 pre-existing failures** (Election lifecycle ×2, Governance runtime, sovereignty leakage). Unchanged and unrelated; not counted as passing |

**Intermediate failures on the way to the final runs** (each fixed without weakening anything):
- **The no-bypass guard flagged two comments that named the ledger class and a table outside their allowed files:** reworded.
- **The G-6c-1 schema test asserted "not yet indexed (written by the ledger from G-6c-3)":** now it asserts the ledger indexes at publication, and that the backfill agrees and adds nothing.
