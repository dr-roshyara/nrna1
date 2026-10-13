# 05: One current link per voter: the partial unique index (PBDIGIT-274 S2)

**Story:** `PBDIGIT-274` S2 · **Code:** `database/migrations/2026_10_12_000001_replace_voter_slugs_pair_unique_with_active_partial_index.php` · **Tests:** `tests/Isolated/Pbdigit274/Database/VoterSlugsActiveIndexMigrationTest.php`, `…/PgUniqueViolationShapeTest.php` (isolated database).

## Purpose
A voter keeps the **history** of their voting links: an expired link is replaced by a new row, and the old row is soft-deleted, never overwritten. The old constraint `voter_slugs_election_id_user_id_unique` allowed only one row per (election, user), history included. That is why the old code rewrote the same row in place.

The migration replaces it with a **partial** unique index:

```php
public const CREATE_INDEX_SQL = 'CREATE UNIQUE INDEX '.self::NEW_INDEX
    .' ON '.self::TABLE.' (election_id, user_id) WHERE deleted_at IS NULL';
```

So **at most one current row** may exist per (election, user), and any number of soft-deleted ones.

## Where it fits
- **Infrastructure (schema).** The index is the database's half of the invariant L-5 "at most one current link".
- **The application's half** is `VoterSlugIssuer` (guide 06).
- **When no current row exists, nothing can be locked.** The index then serialises two concurrent issuers: the loser gets a 23505 on `voter_slugs_active_election_user_unique`.

## How it works
`up()`:
- runs in its own transaction, which is a savepoint when the Migrator already opened one;
- sets `SET LOCAL lock_timeout`;
- checks explicit preconditions: PostgreSQL only; the old constraint exists on exactly `(election_id, user_id)`; the new index name is free; no duplicate current pairs. Otherwise it refuses with `"… refused: <reason>. Nothing was changed."`;
- then `DROP CONSTRAINT` (never `DROP INDEX`) and `CREATE UNIQUE INDEX … WHERE deleted_at IS NULL`.

`down()` refuses when history exists (duplicate pairs across soft-deleted rows) or the index has drifted from the expected definition. Re-creating the old constraint would otherwise fail halfway or silently lose meaning.

## Design decisions
- **History is kept**, never hard-deleted (Human PO).
- The S2 design clarifications are in plan section 11.0; the release runbook is section 11.1 (approved with **G8**, not yet executed).
- **The migration has not run on `nrna`.** It ran only on the isolated test database (G3 approved the design for that database only).

## Testing
```bash
. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml tests/Isolated/Pbdigit274/Database/VoterSlugsActiveIndexMigrationTest.php
```
The tests cover: the preconditions; failure after the drop (atomicity) in both invocation modes; the behaviour (archived plus current allowed, a second current rejected, several archived allowed, restoring an archived row next to a current one rejected); and the driver shape of a 23505 (`PgUniqueViolationShapeTest`, which the issuer's classification relies on).

## Pitfalls
- **Code that soft-deletes a link must not restore one** while another is current: the index rejects it, by design.
- **Don't rename the index without updating `VoterSlugIssuer::classify()`**, which matches the constraint name in the driver message.
- **`IsolatedCommittedDatabaseTestCase` refuses to run** unless this migration is recorded on the isolated database. It never migrates.

**Traceability:** `PBDIGIT-274` S2:
- migration `7ded36437`; tests `b740977aa`; evidence `126d036b4`;
- design and G3: `8b86967c4`;
- isolation and identity: guide 03, `c4b2ebcd4`.
