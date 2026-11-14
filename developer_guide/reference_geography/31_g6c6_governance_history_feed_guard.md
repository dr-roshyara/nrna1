# 31: G-6c-6, the D-90 governance history, the feed fields and the notice-write guard (PBDIGIT-346)

**Design:** revision 3 §3, §10, §11, §12 (B10), §19, §20. **Evidence and the G-6c acceptance package:** `docs/publicdigit/reference-geography/evidence/2026-11-14-pbdigit346-g6c6/`.

## Business purpose

The governance consequences of D-90 (findings, confirmations, closures, corrections) are part of Geography's official history. G-6c-6 makes that history **readable and consumable** through the published contract, and makes it **impossible to write around the controls.**
- **Any context can ask:** "what was the approval status of publication p, as officially known at position k?" The answer for (p, k) **never changes**, whatever happens later.
- **The feed tells consumers** which entries are governance notices, what each concerns, and which revision corrected which publication. Provenance only, **never persons.**
- **An architecture guard fails the build** if anything but the locked ledger writes a notice or the publication-person index.

## 1. The history (`GeographyReadModel::approvalStatus`)

```php
$k = $geography->knowledgeAt($position);            // or knowledgeAtTime($tau)
$h = $geography->approvalStatus($publication, $k);  // ?ApprovalHistory
// $h->status: approved | approval-defective | confirmed | corrected | finding-closed
// $h->events: [['position' => 41, 'event' => 'finding'], ['position' => 44, 'event' => 'closure'], …]
// $h->correctedAt, $h->knownAt, $h->publication
```

- **Reads only entries at positions ≤ k.** That's enforced twice:
  - the SQL (`PostgresFindingLifecycles::read(…, upTo: k)`);
  - `FindingLifecycle::statusAt(k)`.
- **`FindingLifecycle` is the one definition.** The read side adds no lifecycle logic of its own, only the mapping to the published DTO.
- **Every episode is kept, in position order:** finding, closure, a new finding, a correction.
- **`null`** when no revision publication exists at that position at k (not yet published, or a notice).
- **`KnowledgeNotAvailable`** beyond the committed position (like every query).
- **`ApprovalHistory`** is a new published-language type: strings and ints only. It's listed in `tests/Architecture/baselines/published-language.json`.

## 2. The feed (`publicationsSince`)

**`PublicationEntry` gains three optional fields at the end**, so existing consumers are unaffected:

| Field | Revision | Finding | Confirmation / closure |
|---|---|---|---|
| `noticeKind` | null | `finding` | `confirmation` / `closure` |
| `concernsPosition` | null | the flagged publication | the finding |
| `correctsPosition` | the publication it fully corrects, or null | null | null |

- **Not exposed:** confirmer, merge basis (Identity's merge ids), digest, ended-by. These are persons or internal provenance.
- **Order, gap-freeness and replay are unchanged (G-5c):** the same range always reads the same.

**Consumer obligations (unchanged, G-5c contract):**
- **store the last position processed in the same transaction as the work it caused;**
- **raise review signals only;** never change membership or belonging automatically.

Geography can't enforce or test these; **the first real consumer must** (backlog note in PBDIGIT-346).

## 3. The notice-write guard (`ReferenceGeographyNoBypassTest`, rule 7)

- **Rule 4 lets every Geography Infrastructure class touch `refgeo_` tables.** Without rule 7, an adapter could insert a confirmation directly, around the lock and the confirmer checks.
- **Rule 7 flags, outside the authorised writers:**
  - an `INSERT INTO refgeo_publications` or `refgeo_publication_persons`;
  - a query-builder `insert*`, `upsert`, `update*` or `delete` on those tables;
  - any `appendNotice` call.
- **Authorised writers:** `Ports/LockedLedger.php`, `IdentityNoticeConsumer`, `ConfirmPublication`, `PostgresPublicationLedger`, and `database/migrations/` (schema and backfill; **not** seeders or factories).
- **Reads stay free;** the read source reads `notice_kind`.
- **Proven by planted bypasses** in `tests/Fixtures/ReferenceGeographyBypass/`:
  - an infrastructure writer (`RogueNoticeWriter`);
  - an unauthorised application service calling `appendNotice` (`RogueFindingService`);
  - the existing `RogueSqlService`, which inserts into `refgeo_publications` and is now caught by rule 7 as well.

## Components and wiring

| Layer | Change |
|---|---|
| Published | `ApprovalHistory`; `GeographyReadModel::approvalStatus`; `PublicationEntry` + 3 optional fields |
| Application | `PublishedGeography::approvalStatus` (maps `FindingLifecycle::statusAt`) |
| Port | `GeographyReadSource::findingLifecycle(publication, p)`; `PublicationChanges` + 3 optional fields |
| Infrastructure | `PostgresFindingLifecycles::read(db, positions, ?upTo)`, the **one** lifecycle reader, used by the locked ledger (everything) and the read source (≤ k); `PostgresGeographyReadSource` |

**Wiring:** unchanged. `GeographyReadModel` is already bound to `PublishedGeography` (G-5).

## Testing

```
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Architecture/ReferenceGeographyNoBypassTest.php
MEMBERSHIP_PG_IMAGE=<pinned> MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite ReferenceGeographyPostgres --filter GovernanceHistoryReadTest
```

**`GovernanceHistoryReadTest` (5):**
- the status at every position through finding and confirmation;
- closure, then a new episode, then a correction, **never rewriting an earlier answer**;
- only revision publications known at k;
- the feed fields, gap-free and replay-stable, and the exact property list (no persons);
- **reading never writes** (queries run inside `SET TRANSACTION READ ONLY`).

`PublishedGeographyTest`: `approvalStatus` refuses a forged knowledge.

## Extending safely

- **A new governance event:**
  1. add it to `FindingLifecycle` (the one definition);
  2. write it only through `LockedLedger`;
  3. extend `PostgresFindingLifecycles`;
  4. if consumers need it, add an **optional** field at the end of `PublicationEntry`.
- **Never** expose persons in the feed, and never add a second lifecycle reader.

**Administrator: no administrator action required.** No migration, configuration or schedule.

**Traceability:** PBDIGIT-346 G-6c-6 · design revision 3 §10, §11, §12 · D-90 · G-5c contract.

## The full G-6c mutation run (G-6c-2 to G-6c-6)

**Driver:** `mutation-check-g6c-full.php`, 43 mutants. No source file is modified: each mutant is a copy-loaded class, and a multi-file mutant copy-loads several.

| Classification | Mutants |
|---|---|
| **Killed by assertions** (0 errors), each by its intended test | **37:** D2 to D7, C1 to C6, K1 to K9 (unit, plus K8 and K9 in the database), L1 to L8, R3, R4, R5, R12, and K2, K4, K6, K7 (database) |
| **Refused by the intended database constraint** (not counted as an assertion kill) | **1:** K1 (database). `refgeo_publications_one_resolution` refuses the duplicate resolution, the backstop. The unit tests kill K1 by assertion |
| **Equivalent** (argued) | **5:** D1 (before ⊆ after components, design §18). K3 and K5 (database): the G-6c-3 invariant means an open finding's defective approver is known-same as an excluded person now; the unit tests kill both. R1 and R2: the history is bounded twice (SQL ≤ k, and `statusAt(k)`); each alone is redundant, and **R12, removing both, is killed** |
| Survived, unexplained | **0** |

**Schema rules (G-6c-1) are not mutated:** the harness migrates inside the first test, so a constraint can't be dropped in between. They are covered by the isolated refusal-reason tests instead: each forbidden row can be refused **only** by its named constraint, so a missing constraint fails its test.

**Driver defect found and fixed:** a run that didn't execute (a `cd` failure) was first counted as a kill. Such a run is now `INCONCLUSIVE`, and C3 was re-run and killed by assertions.
