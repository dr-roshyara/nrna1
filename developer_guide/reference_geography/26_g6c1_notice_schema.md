# 26: G-6c-1, the D-90 notice schema and the publication-person index (PBDIGIT-346)

**Design:** `docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-g6c-d90-notices-design.md` revision 3, **accepted 2026-11-14** (§11, §17). **Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-14-pbdigit346-g6c1/`.

## In business terms

**The official stream can now hold governance notices:** a finding, a confirmation or a closure.
- **The database itself refuses any malformed notice,** whoever writes it:
  - a notice must point back at the right kind of entry;
  - a finding can be confirmed once and closed once;
  - a notice is never changed.
- **A new index records who took part in each two-person publication** (every approver, every excluded person), so a later identity merge can find the affected publications quickly.

**Nothing writes notices yet.** That's the publication ledger, from G-6c-3.

## What's in the migration (`2026_11_14_100000_add_refgeo_governance_notices.php`)

| Element | Rule |
|---|---|
| `refgeo_publications` + `notice_kind`, `concerns_position`, `merge_basis`, `ended_by`, `confirmed_by`, `confirmed_digest`, `corrects_position` | additive. Existing rows are unchanged (all NULL) |
| `refgeo_publications_notice_iff_kind` | `kind = 'notice'` ⇔ a notice kind (`kind` is NOT NULL since G-4) |
| `refgeo_publications_notice_kind_known` | the notice kind is finding, confirmation or closure (explicitly named in `2026_11_14_110000`) |
| FKs `…_concerns_position_fkey`, `…_corrects_position_fkey` | the referenced position **exists.** The "backwards" CHECK only says it's earlier, so position 0 passes the CHECK and is refused by the FK |
| `…_notice_shape` | a notice concerns an **earlier** position and never corrects |
| `…_finding_shape` / `…_confirmation_shape` / `…_closure_shape` | exactly their own fields (merge basis · confirmer + digest · what ended it) |
| `…_revision_shape` | a revision row carries no notice field |
| `…_corrects_backwards` | a correction points backwards |
| trigger `refgeo_notice_references` | a finding concerns a **revision** row; a confirmation or closure concerns a **finding**; a correction corrects a **revision** row |
| `…_one_resolution` (migration `2026_11_14_110000`) | **a finding is resolved once:** at most one confirmation **or** closure per finding (it replaced the two separate indexes, which allowed both). Per *finding*, not per publication: one publication can have several finding episodes |
| `refgeo_publication_persons` (position, person, role ∈ approver · excluded · confirmer) | append-only (the G-4 `refgeo_refuse_change` trigger); indexed by person |
| `backfillPublicationPersons()` | **idempotent;** indexes the second-person publications written before G-6c: every approver of the published version and the excluded persons |

**Unchanged:** the G-4 triggers. Notices take the next gap-free position, obey the clock refusal, and are immutable like every row.

## Audit against the Principal's integrity pattern (2026-11-14)

- **NULL safety:** every shape CHECK is written as `kind/notice_kind … OR (explicit IS NULL / IS NOT NULL …)`. Comparisons appear only after an `IS NOT NULL` on the same column, so no CHECK can pass by evaluating to NULL.
- **Gaps found and closed (test-first):**
  - FK refusals weren't tested (now position 0);
  - a finding could be confirmed **and** closed (now `one_resolution`);
  - duplicate person associations weren't tested (now the PK);
  - an auto-named CHECK (now explicit).
- **The one jointly guarded case:** a revision row carrying a notice kind breaks both `notice_iff_kind` and `revision_shape`, because every notice kind needs `concerns_position`, which a revision row may not have. Its test accepts **exactly one of those two names.** Every other case is pinned to a single constraint or trigger.
- **Order of refusal:** PostgreSQL runs BEFORE triggers before CHECKs. A test of a CHECK must therefore give a reference the trigger accepts.
- **Existing data:** `ALTER TABLE … ADD CONSTRAINT` validates existing rows. A violating row makes the migration **fail loudly**; nothing is rewritten or deleted. Revision rows written before G-6c have all new columns NULL and pass. No notice row existed before G-6c.

## The counted approver is not stored (design §17)

- The publication row never stored it, and rows are immutable.
- So the counted approver is **derived by replaying `PublicationGovernance::mayPublish` at the publication's roster version.** It's deterministic, because published revisions are closed and the roster is versioned.
- **The index stores every approver,** which is a superset; candidate selection only needs a superset.

## Testing

`tests/Feature/Contexts/ReferenceGeography/GovernanceNoticeSchemaTest.php` (isolated cluster):
- each notice shape accepted at gap-free positions;
- **every forbidden shape, missing reference, double resolution and duplicate association** (9 tests, 33 assertions), each refused by **its named constraint or trigger** (`REFUSED_BY`). A refusal for another reason fails the test, so no refusal can pass vacuously;
- immutability;
- the backfill on a real second-person publication (twice: idempotent).

## Pitfalls

- **Several CHECKs can apply to one bad row;** PostgreSQL reports one of them. To test a single constraint, build a row that **only it** refuses (see `REFUSED_BY`).
- **Never write notices outside `PostgresPublicationLedger`** (G-6c-3 adds the guard). The direct inserts in the test exist to prove the database's own refusals.

**Traceability:** PBDIGIT-346 G-6c-1 · design revision 3 §11, §17 · D-90 · v3.11 §6, §15.
