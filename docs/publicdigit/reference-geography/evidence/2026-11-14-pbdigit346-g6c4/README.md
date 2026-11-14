# Evidence: G-6c-4, confirming a D-90 finding (PBDIGIT-346), 2026-11-14

| File | Shows |
|---|---|
| `c4-10-red-domain.txt` | RED: `publishedApproval`, the new refusals and `FindingConfirmation` don't exist (9 errors) |
| `c4-11-green-domain.txt` | GREEN: the domain tests with the purity guard, 15 tests |
| `c4-12-red-pg/` | RED: `ConfirmPublication` doesn't exist (8 errors) |
| `c4-13-green-pg/` | GREEN: `ConfirmPublicationTest`, `IdentityNoticeImpactTest` and `ProductionWiringTest`: 21 tests, 155 assertions |
| `c4-14-green-domain-final.txt` | domain tests with the exclusion-only case: 10 tests. **A blind spot found by review:** every earlier "excluded" case was also known-same as the defective approver |
| `mutation-check-g6c4.php`, `c4-15-mutation-check.out`, `mutants/*/` | **K1 to K9 killed by assertions, 0 errors.** Unit runs executed 289 tests, PG runs 8. K4 is caught only by the exclusion-only test. K8 (the confirmer is not indexed) is caught only by the explicit index assertion: the confirmer row is the design's superset and isn't needed for impact detection, because excluded persons are indexed |
| `c4-16-db-free.txt` | database-free: **333 GREEN** |
| `c4-17-isolated-cluster-all/` | **the full cluster: 307 tests, exit 0** |
| `c4-18-architecture-full.txt`, `.failures` | 343; **the same 4 pre-existing failures** (Election lifecycle ×2, Governance runtime, sovereignty leakage); not counted as passing |
