# 19: G-4g, the concurrency matrix and the consolidated mutation evidence (PBDIGIT-346)

**Design:**
- v3.11 §6 (the protocol table, PO-1, PO-4, PO-6), §8 ("Checks": an act stands once given; publication re-reads the roster under the lock);
- the G-4 interpretation B-1 to B-9.

**Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g4g/`. **Acceptance package:** `docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-g4-acceptance-review.md`.

## In business terms

**When several people act at the same moment** (appointing, ending, declaring a conflict, Identity merging two people, saving, publishing), **the result is always as if they had acted one after another, in the right order.**
- **A publication never uses an out-of-date view** of who may act.
- **Nothing is lost or duplicated,** and the record never has a gap.
- **Removing any of these safeguards on purpose makes a test fail.**

## How the races are made deterministic

`PublishingFixtures::whileHeld(holder, contender)`:
1. **The holder runs in a forked process.** It takes the lock and keeps it: a roster write that sleeps inside `writeUnderLock`, a publication that sleeps in `beforeCommit`, or a save that sleeps inside an open transaction.
2. **200 ms later, the contender starts here.**
3. **The test asserts:**
   - **that the contender waited** (> 400 ms);
   - **what it decided,** judged with or without the holder's write;
   - **the persisted state:** publication rows, roster versions and the order of `recorded_at` values.

**No outcome depends on timing luck.**

## The matrix (`PostgresConcurrencyMatrixTest`, 22 tests)

| Case | Both orderings? | Expected (from the sources) |
|---|---|---|
| R1 appoint the actor | yes | write first → published; publication first → refused (not appointed) |
| R2 end the actor | yes | write first → refused; publication first → published, the end recorded after |
| R3 end the approver | yes | **published either way:** the approval stands (§8) |
| R4 appoint a matter reviewer as actor | yes | like R1 |
| R5 the approver declares an interest (judgment matter) | yes | write first → refused (materially conflicted); publication first → published |
| R6 an Identity merge of approver and preparer | yes | write first → refused (not independent); publication first → published |
| R7 the reversal of that merge | yes | write first → published; publication first → refused |
| R8 the same notice delivered 3 times at once | — | one entry |
| S1, S2 a re-prepare or withdrawal in progress | the reverse is G-4f | the publication waits, then is refused (not submitted) |
| P1 two publications at once | — | the second waits; positions consecutive; p+1 never visible early |
| C1 a close during an uncommitted publication | — | refused; after the commit, the close includes it |
| C2 equal clock readings | — | allowed, ordered by position, stored exactly (not adjusted) |
| C3 a read by position beyond the committed one | — | refused |
| X a mixed stress run | — | no error or deadlock; no gaps; every publication valid at its roster version; no roster entry crossing it |

## Testing and evidence

**All 22 passed on their first run** (`matrix-first-run.txt`). They verify what G-4a to G-4f built, so they are **not REDs.**

**Their value is proven by mutation:** `mutation-check-pg.php` (G-4g), **8 of 8 killed, each by its intended test:**
- the publication lock;
- the roster lock;
- the revision-row lock;
- governance at the previous roster version;
- a close without the lock;
- the timestamp adjusted;
- notice idempotency removed (the database's unique constraint is the backstop, so the replay errors instead of being recorded twice);
- the roster read before the lock.

**Every earlier mutation set was re-run against the final code:**
- database-free (`mutation-rerun-db-free.out`): G-3 (14), G-4b (5), G-4f application (4), all killed;
- PostgreSQL (`mutation-rerun-pg.out`): G-4c, G-4d, G-4e, G-4f.

**Traceability:** PBDIGIT-346 G-4g · v3.11 §6, §8 · G-4 interpretation.

## Verification-quality lessons (final G-4g run)

**1. A mutation counts only if its intended test fails on its assertion.**
- Every run's failures are classified by message, and the setup and infrastructure markers are counted (`mutation-check-pg-final-classification.out`).
- **Mutant G4 was first "killed" by fixture setup** (`Revision: invalid`): the fixture bootstrapped its competence claim through `PublishRevision`, the code under mutation.
- **The fix:** the bootstrap now publishes directly, in SQL, under the same lock and through the G-4a guards. A fixture must never depend on the code a mutation test changes.

**2. A failed background holder must fail the test, never hang it.**
- The first re-run hung for 22 minutes, because older helpers (ledger and closure tests) waited forever for a holder that had failed.
- **Every `whileHeld` now:** the holder always signals, in `finally`; its error is reported; the wait has a 60-second deadline; the child is killed and reaped on timeout.

**3. G7 is proven at three layers:** domain (`MergeNoticeTest`), application (`RosterServicesTest`), both without a database; and the database's unique constraint.

**Final:**
- all mutation sets genuine (G-3 14, G-4b 5, G-4c 2, G-4d 5, G-4e 7, G-4f 6, G-4g 8);
- the full cluster passes (220 tests);
- 221 database-free tests pass;
- the architecture suite has 318 tests, with the same 4 baseline failures.
