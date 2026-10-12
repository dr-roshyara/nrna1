# PBDIGIT-274 S8: the voting-link lifecycle end to end, 2026-10-13

**Authority:** Human PO ("start S8").

**Scope:**
- `pbdigit274_isolated_test` only: the committing test base, no migration, and the identity check on every run.
- **No production code changed.** S8 is a verification slice: one HTTP journey over the behaviour delivered by S3–S7.
- No password appears in this folder.

## What the test proves (`tests/Isolated/Pbdigit274/Database/VoterSlugLifecycleFlowTest.php`)
Time passes through the clock (`travel`), not by rewriting timestamps, so the issuer, the guard and the stored rows all see the same "now".

**Test 1: one voter from the first link to having voted**
| Step | Voter does | Outcome asserted |
|---|---|---|
| 1 | starts voting | one link; redirect to step 1; step 1 opens (200) |
| 2 | starts again 10 min later | the same link; rows byte-identical (no extension) |
| 3 | another entitled voter opens the link | 404, as for an unknown link (O-5); nothing created for them |
| 4 | opens the link after 31 min | redirect to the election page with "Your voting session has expired, please start again."; the row is unchanged |
| 5 | starts again | a new link; the old one is soft-deleted (history); exactly one current link |
| 6 | opens the old link, then the new one | 404, then 200 |
| 7 | starts again after voting | redirect to the election page; nothing issued, nothing changed |

**Test 2: a voter loses entitlement**

The voter starts and opens step 1, then their membership becomes inactive. Starting again redirects to the election page. The issuer retires the current link (O-3; soft-deleted, not hard-deleted), issues no new one, and the old link answers 404.

## Limits
- **Step 7 sets `has_voted`** on the voter's codes row (created by step 1) the way a committed cast leaves it. **The cast itself is S9's subject (G7).**
- **Test 2's final assertions were pinned after one probe run** that printed the actual outcome (404, link retired). The probe was then removed. Before that, the assertion was only "not 200".
- **No RED run exists**, because S8 adds no behaviour. These tests guard against regressions of S3–S7.

## Runs
| # | File | Result |
|---|---|---|
| 01 | `01-flow.out` | `VoterSlugLifecycleFlowTest`: 2 / 37 |
| 02 | `02-isolated-all.out` | the whole isolated PBDIGIT-274 suite: 74 / 353 |

Checksums: `SHA256SUMS`.
