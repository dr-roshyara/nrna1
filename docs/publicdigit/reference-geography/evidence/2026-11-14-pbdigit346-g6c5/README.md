# Evidence: G-6c-5, the full-coverage correction link (PBDIGIT-346), 2026-11-14

| File | Shows |
|---|---|
| `c5-01-red-domain.txt` | RED: `CorrectionCoverage` doesn't exist (8 errors) |
| `c5-02-green-domain.txt` | GREEN: `CorrectionCoverageTest` with the purity guard, 14 tests |
| `c5-03-red-pg/` | RED: `publish()` has no `corrects` (7 errors; the fixtures ran up to that call) |
| `c5-04-green-pg/` | GREEN: `CorrectionLinkTest`, 7 tests, 36 assertions (incl. two forked races and a failure injection) |
| `c5-05-g6c4-followups.txt` | G-6c-4 follow-ups (verification tests of existing behaviour, not RED): a real stale digest of an earlier submitted version is refused; a matter reviewer for the matter may confirm. 12 tests. **No defect** |
| `mutation-check-g6c5.php`, `c5-06-mutation-check.out`, `mutants/*/` | **L1 to L8 (G-6c-5) killed by assertions, 0 errors.** K1 to K7 against `ConfirmPublicationTest` (database): K2, K6, K7 killed; K1 killed, its 2 errors being the database's `refgeo_publications_one_resolution` refusing the duplicate resolution (the backstop working); **K3, K4, K5 survived** |
| `c5-07-k4-pg-test/` | the new database exclusion-only test (a second preparer not linked to the approver): `ConfirmPublicationTest` 9 tests GREEN |
| `c5-08-mutation-k4-rerun.out`, `mutants-k4-rerun/*/` | **K4 now killed at the database level** by that test, 0 errors. **K3 and K5 are equivalent in any consistent database** (argument in guide 30): the unit tests kill them |
| `c5-09-db-free.txt` | database-free: **343 GREEN** |
| `c5-10-isolated-cluster-all/` | **the full cluster: 315 tests, exit 0** |
| `c5-11-architecture-full.txt`, `.failures` | 343; **the same 4 pre-existing failures** (Election lifecycle ×2, Governance runtime, sovereignty leakage); not counted as passing |
