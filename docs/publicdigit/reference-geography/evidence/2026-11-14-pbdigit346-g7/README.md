# Evidence: G-7, CLDR display names (PBDIGIT-346), 2026-11-14

| File | Shows |
|---|---|
| `g7-01-red-domain.txt` | RED: the CLDR domain classes don't exist (7 errors) |
| `g7-02a-test-defect-invalid-locale.txt` | a test-data defect: `a_B` is not a CLDR locale; the strict parser rightly refused it (test fixed) |
| `g7-02-green-domain.txt` | GREEN: resolution tests with the purity guard, 13 tests |
| `g7-03a-test-defect-final-name.txt` | a test defect: a helper `name()` overrode PHPUnit's final `TestCase::name()` (renamed `nameSlot`) |
| `g7-03-red-validator.txt` | RED: malformed CLDR claims are not refused (3 failures; well-formed ones already pass) |
| `g7-04-green-validator.txt` | GREEN: all Geography unit tests, 316 |
| `g7-05a-*`, `g7-05b-*` | fixture defects: revisions were incomplete (no evidence for the basis), then the evidence lacked its issue date (D-227); fixed, so RED fails for the intended reason |
| `g7-05-red-pg/` | RED: `displayName` doesn't exist (4 errors) |
| `g7-06-green-pg/` | GREEN: `CldrDisplayNameReadTest`, 4 tests |
| `mutation-check-g7.php`, `g7-07-mutation.out`, `mutants/*/` | 12 mutants: 9 killed by assertions; **N6** refused by the intended domain guard (`ClaimValue::value()` on a withdrawal); **N11 survived** (a test gap: the release map was indistinguishable from truncation); **N12** killed by an error (read of null) |
| `g7-08-mutation-rerun.out` | after tightening the database test: N11 and N12 killed by assertions |
| `g7-09-unmutated-pg/` | the tightened test, unmutated: 4 tests GREEN |
| `g7-10-db-free.txt` | database-free: **354 GREEN** |
| `g7-11-isolated-cluster-all/` | **the full cluster: 324 tests, exit 0** |
| `g7-12-architecture-full.txt`, `.failures` | 343; **the same 4 pre-existing failures**; not passing, unrelated |

**Final mutation classification (12):** 11 killed by assertions; 1 refused by the intended domain guard (N6); 0 survived.
