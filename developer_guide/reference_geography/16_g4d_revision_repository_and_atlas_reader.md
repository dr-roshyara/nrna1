# 16: G-4d, the revision repository and the PostgreSQL atlas reader (PBDIGIT-346)

**Design:**
- the standalone completion §4 (tables; clarification A: published iff named by a publication row);
- v3.11 §5, §6, §15;
- the G-4 interpretation §3.

**Migration:** `2026_11_13_130000_create_refgeo_revisions.php`. **Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g4d/`.

## In business terms

- **A proposed change can be saved, the system closed, and the change reopened later exactly as it was.** That covers its claims, evidence, assessments, approvals, flags and validation.
- **If the stored data was altered behind the system's back, reopening it is refused, never quietly repaired.** For example, a submitted change whose contents no longer match its frozen fingerprint.
- **Two people editing the same change at once can't overwrite each other.**
- **A save that fails half-way leaves nothing behind.**
- **The validator reads the official atlas from the database:**
  - only what has actually been published, as at one fixed point;
  - never a draft, and never a change that's approved but unpublished.

## Code

| Element | Role |
|---|---|
| `Application\Ports\RevisionRepository` | `add`, `get` (gives a `StoredRevision`: the revision and its row version), `save(revision, expectedRowVersion)` |
| `Application\RevisionChangedConcurrently` | the row version moved, or the revision already exists. **Nothing was written** |
| `Infrastructure\PostgresRevisionRepository` | **save:** one transaction. Revision row (optimistic) → the parts being prepared, replaced → the parts that only grow, appended, with the stored ones required to be a prefix → a new content-version row → for a submitted or stale version, its claims **frozen in `refgeo_claims`**. **load:** rows → `RevisionSnapshot` → **only `Revision::fromSnapshot`**. Any mapping or rebuild failure gives `StoredRevisionCorrupt` |
| `Infrastructure\PostgresPublishedAtlas` | `PostgresPublishedAtlas::at(db, ?p0)`. `history(slot)`: published claims (joined to `refgeo_publications`) at positions ≤ p₀. `familySlots`: **the same meaning as `InMemoryPublishedAtlas`**, with an indexed (kind, value) prefilter |

**Tables** (central database, outside the knowledge stream, §15):

| Group | Tables |
|---|---|
| append-only | `refgeo_revision_versions` (number, digest, assessor), `refgeo_approvals`, `refgeo_revision_persons`, `refgeo_judgment_flags` |
| replaced per save | `refgeo_revision_claims`, `refgeo_revision_evidence`, `refgeo_assessments`, `refgeo_revision_validations` (p₀, structural, violations, before-images, disputed), `refgeo_dependency_predicates` (forms: slot, family, subject-family) |

## A kernel defect found and fixed here (`0b2ce6dae`)

**A publication position belongs to a whole revision** (v3.11 §6), and a revision may hold several non-overlapping claims of one slot (R-1). `SlotHistory` assumed one claim per position. It now allows several claims per position, provided they don't overlap; overlapping ones stay refused. The in-memory test atlas had hidden this by giving every claim its own position. **No rule changed and no earlier test changed.**

## Pitfalls

- **Never set private aggregate state from rows.** Rebuild only through `fromSnapshot`, which re-checks R-1, unique ids, `excluded(r)`, approval versions and the frozen digest.
- **Never read draft or unpublished claims as atlas facts.** The only published-claim query is the join in `PostgresPublishedAtlas`.
- **The atlas is fixed at p₀.** Make a new one to see later publications. G-4e re-reads under the lock.

## Testing (isolated cluster, `ReferenceGeographyPostgres`)

**`PostgresRevisionRepositoryTest` (8 tests):**
- every state (draft, submitted, stale, re-prepared, version 2, withdrawn) round-trips exactly;
- a hand-built validation with all three dependency forms, violations, a 3-segment before-image and disputed reliances round-trips;
- the reloaded aggregate behaves identically;
- frozen claims are written for versions 1 and 2;
- **five kinds of tampering are refused:** content (digest), an overlapping claim, an unknown dependency form, an incomplete dependency record, an unparseable slot key;
- **a failure injected part-way** (a temporary trigger) leaves the previous state;
- a concurrent change is refused, and nothing is written;
- a revision is added once.

**`PostgresPublishedAtlasTest` (4 tests):**
- **it answers exactly as `InMemoryPublishedAtlas`** for the same published claims: histories at 4 dates, boundaries, 4 family queries, and two claims of one slot in one publication;
- frozen-unpublished and draft claims never enter the atlas;
- the atlas is fixed at its p₀;
- **validating through it gives the same result as through the reference atlas.**

**`PostgresStewardRosterStoreTest`:** a new test **closes the G-4c obligation.** A multi-entry roster write whose second insert fails (an injected trigger) commits nothing. It passed on its first run, because the store's transaction already made the write atomic; the test now proves it.

**RED:** 12 errors (missing repository, reader and tables). **First GREEN:** one failure from **my test expectation** (`NONE` versus `none`), now asserted by meaning. **GREEN:** 24 tests.

**Mutation check on PostgreSQL** (`mutation-check-pg.php`): **5 of 5 killed, each by its intended test:**
- unpublished claims made visible;
- p₀ ignored;
- no optimistic check;
- no transaction;
- no frozen claims.

**Regression:** 212 database-free tests pass; every cluster suite passes (178 tests); the architecture suite shows the same 4 pre-Geography failures.

**Traceability:** PBDIGIT-346 G-4d · completion §4 · v3.11 §5, §6, §15 · G-4 interpretation §3.
