# 03: Row-shape CHECKs and reference-kind triggers (one table, several entry kinds)

**Origin:** PBDIGIT-346 G-6c-1 (`refgeo_publications`: revisions and three kinds of notice in one gap-free stream).

## The pattern

When one append-only table holds several **kinds** of entry (so that they share one ordered sequence):
1. **one CHECK per kind** fixes exactly which columns that kind has, and which it must not have;
2. **a "kind ⇔ discriminator" CHECK** (`(kind = 'notice') = (notice_kind IS NOT NULL)`);
3. **backward references** as a CHECK (`concerns_position < position`) **plus a foreign key;**
4. **the KIND of the referenced row** can't be checked by a CHECK (it's another row), so it goes in a **BEFORE INSERT trigger**;
5. **"one X per Y"** becomes a partial unique index (`… WHERE notice_kind = 'confirmation'`).

## Writing the CHECKs

- **A CHECK passes when its expression is NULL.** Write each rule as `discriminator <> 'x' OR (col IS NOT NULL AND other IS NULL …)`. Compare columns (`a < b`) only next to an `a IS NOT NULL` in the same conjunction.
- **Name every constraint explicitly** (auto-names like `…_check` are unstable to test against).
- **Order:** BEFORE ROW triggers run first, then CHECKs, unique indexes and FKs. A test aimed at a CHECK must satisfy the trigger.

## Testing it

**Assert the refusal *reason*, not just a refusal.** A missing NOT NULL value also raises a `QueryException`, which would make the test vacuous.
- Map each forbidden case to the constraint or trigger that must refuse it.
- Build each case so that **only that one** can refuse it (several CHECKs can match one bad row, and PostgreSQL reports only one). **If a case is inherently guarded by two rules** (for example, a discriminator plus a shape that the discriminator's kind requires), assert **exactly one of those two names,** never a broad prefix.

Example: `tests/Feature/Contexts/ReferenceGeography/GovernanceNoticeSchemaTest.php` (`REFUSED_BY`).

## Immutability

Reuse the existing `refgeo_refuse_change()` trigger function for any new append-only table (`refgeo_publication_persons`). Never write a second one.
