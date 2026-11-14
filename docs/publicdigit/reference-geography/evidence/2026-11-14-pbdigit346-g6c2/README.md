# Evidence: G-6c-2, the D-90 domain (PBDIGIT-346), 2026-11-14

| File | Shows |
|---|---|
| `g6c2-01-red-domain.txt` | RED: the classes don't exist (14 errors, 1 failure) |
| `g6c2-02-green-domain.txt` | GREEN: `ApprovalDefectsTest` and `FindingLifecycleTest` with the purity guard, 21 tests, 180 assertions (A8: 300 random rounds against a full-scan oracle) |
| `mutation-check-g6c2.php`, `g6c2-03-mutation-check.out`, `mutants/*/suite.txt` | copy-loaded mutants. **D2 to D7 killed by assertions, 0 errors, each by its intended test; every run executed 278 tests.** **D1 "survived" and is an EQUIVALENT mutant:** the before-components are contained in the after-components of the named persons (design §18). Test A8b was added to pin that equivalence, and it passes |
| `g6c2-04-db-free.txt` | database-free (with A8b): 323 GREEN |
| `g6c2-05-isolated-cluster-all/` | **the full cluster on the final code: 285 tests, exit 0** |
| `g6c2-06-architecture-full.txt`, `.failures` | 343; the same 4 pre-existing failures |
