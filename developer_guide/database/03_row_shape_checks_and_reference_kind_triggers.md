# 03: Row-shape CHECKs and reference-kind triggers (one table, several entry kinds)

**Origin:** PBDIGIT-346 G-6c-1 (`refgeo_publications`: revisions and three kinds of notice in one gap-free stream).

## The pattern

When one append-only table holds several **kinds** of entry (so that they share one ordered sequence):
1. **one CHECK per kind** fixes exactly which columns that kind has, and which it must not have;
2. **a "kind ⇔ discriminator" CHECK** (`(kind = 'notice') = (notice_kind IS NOT NULL)`);
3. **backward references** as a CHECK (`concerns_position < position`) **plus a foreign key;**
4. **the KIND of the referenced row** can't be checked by a CHECK (it's another row), so it goes in a **BEFORE INSERT trigger**;
5. **"one X per Y"** becomes a partial unique index (`… WHERE notice_kind = 'confirmation'`).

## Testing it

**Assert the refusal *reason*, not just a refusal.** A missing NOT NULL value also raises a `QueryException`, which would make the test vacuous.
- Map each forbidden case to the constraint or trigger that must refuse it.
- Build each case so that **only that one** can refuse it (several CHECKs can match one bad row, and PostgreSQL reports only one).

Example: `tests/Feature/Contexts/ReferenceGeography/GovernanceNoticeSchemaTest.php` (`REFUSED_BY`).

## Immutability

Reuse the existing `refgeo_refuse_change()` trigger function for any new append-only table (`refgeo_publication_persons`). Never write a second one.
