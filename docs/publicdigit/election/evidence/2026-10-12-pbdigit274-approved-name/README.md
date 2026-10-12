# PBDIGIT-274: APPROVED_NAME set to the G4 database, evidence, 2026-10-12

**Authority:** Human PO, 2026-10-12. Authorised: set `DatabaseIdentityGuard::APPROVED_NAME` = `pbdigit274_isolated_test` and commit it separately.

**Commit:** `bacef4220`. It contains exactly 3 files: `DatabaseIdentityGuard.php` (the constant and its docblock) and the two guard test files.

**Database:** none contacted. `.pbdigit274.env` was not loaded. The only connection attempt is inside `IsolatedBootstrapScriptTest`, which uses a non-existent Unix-socket directory as host, so it fails locally.

| Step | Files | Result |
|---|---|---|
| RED | `01-red` | exit 2: 85 tests, 1 error, 2 failures. Exactly the three approval-dependent tests failed; the other 82 (rules the approval must not relax) already passed |
| GREEN | `02-green` | **85 tests, 189 assertions, exit 0** |
| Config refuses | `03-config-refuses-unsafe.out` | `phpunit -c phpunit.pbdigit274.xml`: no name → `[missing]`; `nrna_test` → `[forbidden]`; both exit 1 |

**Not run:**
- the dedicated config with the real `.pbdigit274.env` (that would connect: the database part of S2, not authorised);
- the S1 regression (it loads `phpunit.xml`).
