# Erratum: assertion counts in this evidence (2026-11-16)

**What is affected:** `README.md` (row `07-…`: "**513 tests, 17,980 assertions, exit 0**") and file `07-final-isolated-cluster-all.txt`. Both were sealed on 2026-11-15, and both stay **unchanged** as history.

**Correction:**
- **513 tests passed with zero failures and zero skipped tests** in every observed full run.
- **The assertion count is not a fixed figure.** Six observed full runs on this branch gave 17,953; 17,958; 17,980; 17,937; 17,920; 17,947.
- So "17,980" was one run's count, not "the final" or stable result.

**Cause, established for the two runs with per-test logs (see `11-assertion-count-variation-2026-11-16.txt`):**
- Exactly one test varies: `PostgresClosedThroughRaceTest::test_a_reported_closed_through_is_never_contradicted_by_a_later_commit` (Membership, PBDIGIT-343; 280 vs 307).
- It is a concurrency stress test: its reader processes sleep for random intervals and assert once per reported boundary, so the count depends on process scheduling, by design.
- It keeps guards that fail if nothing really interleaved.

**Limits of this finding:**
- The four earlier runs have no per-test logs. That the same test caused their differences is **likely, but not proved**.
- All 65 Identity tests (268 assertions; `RedeemClaimTest` 20 tests / 77 assertions) were **identical** in both logged runs, and no test made zero assertions.

**What this erratum does not do:** it doesn't change any I-3 behaviour or test, and it doesn't rewrite the original files. Their SHA-256 entries in `SHA256SUMS` are unchanged.
