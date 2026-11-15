# Evidence: PBDIGIT-345 task 14, index check and the acceptance package (2026-11-15)

**Verified:** the package committed at **`16f2cc9fb`** (task 13), over code **`8b6fcd569`**. **No code changed** in tasks 13 or 14.
**Author:** Claude, the implementer. **Not an independent review. The Principal decides acceptance.**

| File | Shows |
|---|---|
| `verify-t14.py` | the verification: **22 checks, reading committed content only** (`git show HEAD:…`) |
| `t14-01-verification-run1-script-defects.txt`, `verify-t14-run1.py` | **first run: 4 FAIL, all script defects**, not package defects. Kept as found:<br>- the register pattern missed rows `3b`, `3c` and `3d`;<br>- the account parser read the B-7, B-9 and B-10 sub-tables (scenario numbers 1–13 collided with section A's row ids);<br>- so the counts were off;<br>- the plan's `PBDIGIT-351/352/353` was not recognised as naming 353 |
| `t14-02-verification.txt` | **after fixing the script: 22 PASS, 0 FAIL** |

## What the 22 checks establish

| Area | Result |
|---|---|
| **Indexes** | platform 14, database 4, testing 8: every guide indexed, no dangling link |
| **Evidence seals** | 110 `SHA256SUMS` recomputed over **committed** bytes, 683 files: 0 mismatched, 0 uncovered |
| **JUnit** | cluster 410 and database-free 560 test cases, **0 failing, erroring or skipped**. The cluster run's `run.txt` commit is `8b6fcd569`, `suite.exit` 0. No code change between `8b6fcd569` and HEAD |
| **T-1 … T-26** | re-derived from the committed JUnit: every T has a passing test; 79 cited; equal to `05-t-matrix.txt` |
| **D-193** | the register still hashes `a3fe00f2…` (as D-193 (4) cites) and has 53 rows. The account has the **same 53 ids**, statuses **47 OUT OF SCOPE / 3 NOT PROVEN / 3 BLOCKED / 0 PROVEN / 0 FAILED**. All 26 cited test names exist |
| **Backlog** | PBDIGIT-351, 352 and 353: each one file, **OPEN, not authorised**, referenced from the story, the plan, the account and guide 13 |
| **Architecture** | exactly the 4 known failures, **recorded as failures**. No document claims the suite passes |
| **Commits** | every hash in the plan (10) and the story (1) is an ancestor of HEAD |

## The reported inconsistency: session log vs plan

- **Found:** the committed session log does **not** say the cluster JUnit run is pending. Its task 13 entry states the results, and the plan says DONE.
- **The real fault is order:** I wrote both **before** the run had finished, while my turn text still said I was waiting for it. The run finished (410, exit 0) before commit `16f2cc9fb`, and the checks above verify the committed XML.
- **So the records are correct, but they were written ahead of their evidence.** The correction is appended to the session log (`.claude/sessions/2026-11-15.md`, task 14 section); the earlier entry is not rewritten.
