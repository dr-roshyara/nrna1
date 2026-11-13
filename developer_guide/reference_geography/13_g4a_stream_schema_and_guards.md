# 13: G-4a, the publication stream schema and its database guards (PBDIGIT-346)

**Design:**
- v3.11 §6 (the sequence row, positions, `recordedAt`, W), §8 (the roster version in the same row), §15 (the single write path);
- completion §4;
- the G-4 interpretation (`docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-g4-implementation-interpretation.md`), B-2, B-3, B-4, B-8.

**Migration:** `database/migrations/2026_11_13_120000_create_refgeo_publication_stream.php`.
**Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g4a/`.

## In business terms

The official atlas has one numbered record of everything ever published, and one numbered record of who may act.
- **PostgreSQL itself refuses to skip a number, reuse one, or change or delete anything already recorded.**
- **A version of a change can be published only once.**
- **Its timestamp can never go backwards** or fall inside a period already declared complete.
- **That period can't be declared complete into the future.**

## Tables (central database)

| Table | Guard |
|---|---|
| `refgeo_sequence` | **one row** (`id = 1`), never deleted. `last_position`, `last_roster_version` and `last_recorded_at` **move only inside the append triggers** (trigger depth ≥ 2). `closed_through_time` (W) only forward and **never in the future** |
| `refgeo_publications` | insert-only. `position` must be `last_position + 1`, and the insert **advances the row**. `recorded_at ≥` the previous reading and `> W` (`CLOCK_NOT_PAST_BOUNDARY`). `(revision_id, content_version)` unique |
| `refgeo_claims` | immutable. No insert for a (revision, content version) that is already published |
| `refgeo_roster_entries` | append-only. `roster_version` must be `last_roster_version + 1`, and the insert advances the row. `(merge_id, kind)` unique |

**Why "the insert advances the row":** if the code advanced the counter and inserted separately, a forgotten insert would leave a gap, and a stray insert would sit ahead of the counter. **With the trigger doing both, neither can happen** (B-3), and no other code path can move a counter (B-8, the database half of PO-2).

**For G-4e:** the publication step is "`SELECT … FOR UPDATE` the row, read `clock_timestamp()`, check, `INSERT` the publication". The trigger re-checks and advances the row. A failed check raises, and the transaction rolls back completely.

## Testing

`tests/Feature/Contexts/ReferenceGeography/RefgeoSchemaGuardsTest.php` (6 tests, suite `ReferenceGeographyPostgres` in `phpunit.membership-pg.xml`), run with `scripts/testing/membership-pg.sh --testsuite ReferenceGeographyPostgres`.

**How refusals are checked:** a refusal must be a guard's (SQLSTATE `P0001` or class 23). Any other error fails the test, so there is no vacuous pass.

**History of the RED runs (all kept):**
1. **`red-1`:** one test passed **vacuously.** The helper accepted any error, including "table missing". The helper was tightened.
2. **`red-2`:** valid. Every test failed on the missing schema.
3. **`red-3`:** the tests were revised **before GREEN** to the stronger design (the insert advances the row). The first plan, a commit-time check, couldn't catch a stray insert. Valid RED.
4. **`green-1`:** one failure was **a test defect** (W was unset, so "moving W back" had nothing to move back from). The fix exposed a missing guard.
5. **`red-4`:** the guard against a **future W** was missing. Without it, the statement succeeded and blocked every later publication in the run.
6. **`green`:** 6 tests, 28 assertions.

**Regression:** the cluster runs 160 tests across all suites; 195 database-free tests pass; the architecture suite shows the same 4 pre-Geography failures by name.

**Not here:** the lock protocol, staleness, governance under the lock (G-4e), and concurrency proof (G-4g).

**Traceability:** PBDIGIT-346 G-4a · v3.11 §6, §8, §15 · completion §4.
