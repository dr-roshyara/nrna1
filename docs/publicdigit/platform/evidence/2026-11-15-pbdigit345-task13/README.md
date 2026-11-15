# Evidence: PBDIGIT-345 task 13, D-193 proof obligations, evidence verification and the baseline comparison (2026-11-15)

**Code under account:** `8b6fcd569` (task 12b). **Task 13 changes no code:** the working tree's `app/ config/ routes/ database/ scripts/ tests/` were identical to it for every run here.
**Author:** Claude, the implementer, **not independent.** These are evidence and recommendations; **the Principal decides acceptance** (task 14).

| File | Shows |
|---|---|
| `01-d193-proof-obligation-account.md` | **all 53 register obligations**, each with status, reason and evidence: **0 wholly PROVEN · 3 NOT PROVEN (B-7, B-9, B-10; partly proven) · 3 BLOCKED (E-7, E-8, F-1) · 47 OUT OF SCOPE · 0 FAILED** |
| `02-d166-backup-review.md` | the restore drill and in-place recovery against D-166, ISO-8 and M-6: **authority met; the copy controls (encryption, expiry, automated restore, credential restriction) missing** → PBDIGIT-351. Drill marked development and test only |
| `03-evidence-verification/` | every PBDIGIT-345 evidence folder (tasks 0–12, and the mechanism evaluation): **107 `SHA256SUMS` files verify; all 624 files covered; 0 uncovered** (`checksum-inventory.txt`, `checksum-coverage.txt`). **All 17 commits named by cluster runs are ancestors of HEAD;** all 94 runs used the pinned image (15.19) (`run-commits.txt`) |
| `04-baseline-comparison/` | legacy suites at baseline `5bd25c463` (own `vendor/` and autoloader) vs HEAD. **Architecture: the same 4 failures by method. Membership unit: 53 common failures; the only differences are within the unchanged, flaky `CommitteeSlugAvailabilityServiceTest`** (5 runs per side). **No regression.** The first, invalid runs are kept and marked `…-INVALID` (see its README) |
| `05-t-matrix.txt` | T-1 … T-26 → **79 named tests, all PASS** at `8b6fcd569`, from the JUnit logs; **no T without a passing test** |
| `06-architecture/` | the full architecture suite at HEAD: **364 tests, 4 failing (pre-existing; not passing)**, identical by class to the established list; no Laravel reference in Platform Domain or Application; the ratchet diff and its history since the baseline (**PBDIGIT-345 added 4 documented entries; no rule removed**) |
| `t13-01-isolated-cluster-at-head/` | the full isolated cluster, **`run.txt` commit = `8b6fcd569` exactly:** 410 tests, exit 0, no skips |
| `t13-02-db-free.txt` | the database-free set: 560 tests OK (task 12's 555 plus `PlatformDomainPurityTest`'s 5) |
| `t13-03-db-free-junit.*`, `t13-04-isolated-cluster-junit/`, `t13-04-cluster-junit.xml` | the same two runs with per-test JUnit results (the basis of `05-t-matrix.txt`). Checked: no output sections and no credential in the XML; "password" and "secret" occur only in test names |

## Not established here (honest limits)

- **The T-matrix matches tests by the `t<n>` token in their names.** A test proving a T-row without that token is not listed. This understates, never overstates.
- **Assertion counts vary between runs of the same code** (generated test data); test counts are stable.
- **Raw output contains this machine's absolute paths** (stack traces, JUnit `file=`), as in the earlier folders.
