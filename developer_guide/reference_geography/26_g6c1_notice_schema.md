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
| `refgeo_publications_notice_iff_kind` | `kind = 'notice'` ⇔ a notice kind |
| `…_notice_shape` | a notice concerns an **earlier** position and never corrects |
| `…_finding_shape` / `…_confirmation_shape` / `…_closure_shape` | exactly their own fields (merge basis · confirmer + digest · what ended it) |
| `…_revision_shape` | a revision row carries no notice field |
| `…_corrects_backwards` | a correction points backwards |
| trigger `refgeo_notice_references` | a finding concerns a **revision** row; a confirmation or closure concerns a **finding**; a correction corrects a **revision** row |
| `…_one_confirmation`, `…_one_closure` | one of each per finding |
| `refgeo_publication_persons` (position, person, role ∈ approver · excluded · confirmer) | append-only (the G-4 `refgeo_refuse_change` trigger); indexed by person |
| `backfillPublicationPersons()` | **idempotent;** indexes the second-person publications written before G-6c: every approver of the published version and the excluded persons |

**Unchanged:** the G-4 triggers. Notices take the next gap-free position, obey the clock refusal, and are immutable like every row.

## The counted approver is not stored (design §17)

- The publication row never stored it, and rows are immutable.
- So the counted approver is **derived by replaying `PublicationGovernance::mayPublish` at the publication's roster version.** It's deterministic, because published revisions are closed and the roster is versioned.
- **The index stores every approver,** which is a superset; candidate selection only needs a superset.

## Testing

`tests/Feature/Contexts/ReferenceGeography/GovernanceNoticeSchemaTest.php` (isolated cluster):
- each notice shape accepted at gap-free positions;
- **17 forbidden shapes,** each refused by **its named constraint or trigger** (`REFUSED_BY`). A refusal for another reason fails the test, so no refusal can pass vacuously;
- immutability;
- the backfill on a real second-person publication (twice: idempotent).

## Pitfalls

- **Several CHECKs can apply to one bad row;** PostgreSQL reports one of them. To test a single constraint, build a row that **only it** refuses (see `REFUSED_BY`).
- **Never write notices outside `PostgresPublicationLedger`** (G-6c-3 adds the guard). The direct inserts in the test exist to prove the database's own refusals.

**Traceability:** PBDIGIT-346 G-6c-1 · design revision 3 §11, §17 · D-90 · v3.11 §6, §15.
