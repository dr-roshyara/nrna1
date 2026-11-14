# Evidence: G-8 evidenced-dataset importer, and the proposed sources (PBDIGIT-346), 2026-11-14

**Importer (option C):**

| File | Shows |
|---|---|
| `g8-01-red-parse.txt` | RED: the import parsing classes don't exist |
| `g8-02a-test-defect-casing.txt` | a test defect (expected "claim 1", the message says "Claim 1") |
| `g8-02-green-parse.txt` | GREEN: manifest parsing, 3 tests, 22 assertions |
| `g8-03-red-pg/` | RED: the importer doesn't exist (4 errors) |
| `g8-04-green-pg/` | GREEN: import into one draft, checksum and missing-file refusals, import once, appointed only, end-to-end publication through the governed path |
| `mutation-check-g8.php`, `g8-05-mutation.out`, `mutants/*/` | 9 mutants: 6 killed by assertions; I2 refused by a TypeError (a missing file still stops the import); I7 refused by the domain (an unresolved `@key` is no unit id); **I4 survived**: the test did not check that nothing was written when an unappointed actor was refused later |
| `g8-06-mutation-i4-rerun.out` | after tightening that test: I4 killed by assertion |
| `g8-07-db-free.txt` | database-free: 357 GREEN |
| `g8-08-isolated-cluster-all/` | full cluster: 328 tests, exit 0 |
| `g8-09-architecture-full.txt`, `.failures` | 343; the same 4 pre-existing failures |

**Proposed sources (option A):** `proposed-sources/SOURCES.md`. Every source is **PROPOSED**:
- **CLDR 47** names stored (Unicode License V3);
- **UN M49 not stored** (its terms restrict commercial and derivative use: a decision is needed);
- **ISO unreachable** (stopped);
- **Nepal unreachable** (a gap).
