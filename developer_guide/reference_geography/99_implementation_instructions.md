# Reference Geography: step-by-step implementation instructions

**For:** whoever implements the next slice (a developer or an AI session). **Status:** G-0, G-1 and G-2 are implemented and awaiting the Principal's acceptance. G-3 … G-8 follow, each only after the previous one is accepted.
**Authority, in order:**
1. v3.11 (`docs/publicdigit/architecture/reviews/2026-11-09-reference-geography-tactical-design-baseline-v3.11.md`, D-93);
2. D-224 … D-228;
3. the standalone completion (`docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-standalone-tactical-completion.md`, D-226), §8 slices, and its correction notes.

**Where a slice's text and v3.11 differ, v3.11 governs.**

## Part A: the routine for every slice (do not skip a step)

1. **Read before writing:** the v3.11 sections the slice implements, the decisions it cites, and the guides of the slices before it.
2. **Source check:** if a rule you need is not defined, or two texts conflict, **stop and report the exact text and the conflict.** Do not invent a rule. (This is how D-227 and D-228 came about.)
3. **Split the slice into steps** a reviewer can read in one sitting (G-2 had a, b, c, d-1 … d-4).
4. **For each step, strict TDD:**
   1. write the test file first: one test per rule, with valid, invalid, boundary and "not stale" cases;
   2. run it and keep the raw RED output. **RED must fail for the missing behaviour.** If it fails for another reason (a fixture breaking an existing rule, a name clash), fix the test, record it, and run RED again;
   3. write the smallest code that makes it GREEN; run the **whole** domain suite, not only the new file;
   4. refactor only with everything GREEN.
5. **Never weaken a rule or a guard to get GREEN.** If an old fixture breaks a new rule, fix the fixture (a competent instrument, a correct basis), never the rule. If the purity guard flags something, rename or reword, never relax it.
6. **Regression, every step:**

   ```bash
   # all Reference Geography + Identity database-free tests, with the purity and architecture guards
   vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php \
     tests/Unit/Contexts/ReferenceGeography tests/Unit/Contexts/Identity \
     tests/Architecture/ReferenceGeographyDomainPurityTest.php tests/Architecture/IdentityG0ArchitectureTest.php

   # the full architecture suite: compare failures with the baseline BY NAME AND MESSAGE (4 known failures)
   php -d memory_limit=-1 vendor/bin/phpunit tests/Architecture

   # any slice with database code: the isolated PostgreSQL cluster (never the development database)
   MEMBERSHIP_PG_IMAGE=docker.io/library/postgres:15@sha256:7da5d534f1ffcdb2f69efa6e466a7a29ff89eefd611c7b50c4d69aa77fd4a822 \
     bash scripts/testing/membership-pg.sh
   ```

7. **Evidence:** `docs/publicdigit/reference-geography/evidence/<date>-pbdigit346-<step>/` containing the raw RED and GREEN outputs, the regression outputs, a README saying what each file shows (defects included), and `SHA256SUMS`.
8. **Developer guide, every step:** `developer_guide/reference_geography/NN_<step>.md`, covering business terms first, then the code table, how it works, tests, pitfalls and traceability. Add it to `00_index.md`.
9. **Commit, one per step:** `feat(reference-geography): <step> … (PBDIGIT-346)` and the co-author line. **Never push.**
10. **Stop at the slice boundary** for the Principal's review. Do not start the next slice until it is accepted.

## Part B: rules that hold in every slice

- **Domain is pure PHP:** final classes; no Laravel, database, HTTP, queue, files, clock or randomness. Dates are received as values (`ValidDate`). Guarded by `ReferenceGeographyDomainPurityTest`.
- **New folder only:** `App\Contexts\ReferenceGeography`; tables `refgeo_*` in the central database. **Never edit the legacy `App\Contexts\Geography`**; it keeps its 15 readers.
- **Claims are insert-only.** A change is a new claim. An ending is a `none` claim on an instrument; a correction is a `none` claim on a correction basis (D-228).
- **The validator reads the atlas only through `DependencyRecordingReader`.** Any new rule that reads published facts reads them there, so staleness keeps working.
- **No personal data in Geography:** people are `PersonReference`s from Identity (D-225). Two references mean "not known to be the same person", never "proven different" (D-226 §5a).
- **No Organisation integration (D-147).** Geography never decides belonging, membership or entitlement.

## Part C: the next slices, in order

### G-3: stewardship and approval governance

- **Reads:** v3.11 §8 (D-84, D-84a, D-88, D-89, D-90); D-225; D-226 §5a.
- **Build (domain):**
  - **`StewardRoster`** aggregate: appointments, ends, matter reviewers, conflict declarations; a gap-free roster version; the permanent flag `secondStewardEverAppointed`.
  - **Identity notices:** records `PersonsMerged` / `PersonMergeReversed` (the completion §5 algorithm: idempotent by `(mergeId, kind)`; equivalence `E(v)` = merges recorded ≤ v minus reversals recorded ≤ v; order-insensitive).
  - **`PublicationGovernance::mayPublish(revision, roster at v)`:**
    - era A / B;
    - a structural revision needs a second person **not known-same** as anyone in `Revision::excludedPersons()` under `E(v)`;
    - matter-reviewer cases;
    - conflicts;
    - cases 1–5.
  - **Wire `ActingPerson`** → `IdentityActingPerson`, and bind the Identity ports in a service provider (G-0 deferred this to here).
- **Tests first:** era A / B; each permitted-combination case; merge, duplicate, reversal-first, overlap and partial-reversal fixtures (IG-P7); the approval record says "independent as known to Identity at roster version v".
- **Stop for:** acceptance of G-3.

### G-4: the publication ledger (PostgreSQL)

- **Reads:** v3.11 §6, §15; completion §4.
- **Build:**
  - central migrations for `refgeo_sequence` (one row), `refgeo_publications`, `refgeo_claims` (immutable trigger), revisions, approvals, roster; **functions with `CREATE OR REPLACE`** (G-0 lesson);
  - **`PostgresPublicationLedger`:** `SELECT … FOR UPDATE` on the sequence row at **READ COMMITTED**; governance re-check; staleness via `DependencySet::firstInvalidating`; `clock_timestamp()`, refusing if `≤ W` or below the last value; append; the W close step with `FOR UPDATE NOWAIT`;
  - **`PublishedAtlas`** PostgreSQL adapter.
- **Tests first (isolated cluster):** PO-1 … PO-7, with forked publishers and closers (the slice 1 race-test method); a stored before-image equals the belief just before publication.

### G-5: read models and the publication feed

**Design:** `docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-g5-read-models-design.md`. **Reads:** v3.11 §4, §6, §10, §12.

**G-5a: the published read model (implemented; guide 20).**
1. **Domain first (no database):** `PlaceBelief::of(unit, subjectSlots, disputes, t, p)` and `PlaceBelief::resolve(unit, reconciliation, p)`, plus additive `SlotHistory::latest/positionOf/slot`.
   - **Test these:** belief at (t, p); a withdrawal; an ending recorded later; a dispute only on the claim in force; a dispute cleared or withdrawn; a merge, its reversal, and R-2 successive merges.
2. **The published contract** in `Application\Published` uses strings, ints and its own DTOs only. Put every type on `tests/Architecture/baselines/published-language.json`. `ReferenceGeographyPublishedContractTest` must pass.
3. **`PublishedGeography`** (application): **re-check the position on every call;** τ → `TimeBoundary::closeThrough(τ)` → `positionAtTime(τ)`. **Depend on `TimeBoundary`, never `PublicationLedger`:** the no-bypass guard refuses it.
4. **`PostgresGeographyReadSource`:** published claims only (`refgeo_claims ⋈ refgeo_publications`, position ≤ p). **Build slot histories through `PostgresPublishedAtlas`.** Don't map rows yourself (the guard refuses `PostgresRevisionRepository` here).
5. **Bind** `GeographyReadModel`, `GeographyReadSource` and `TimeBoundary` in the provider.
6. **Isolated-cluster tests:**
   - publish fixture claims **directly** (`publishDirect`), freeze unpublished ones directly (`freezeUnpublished`);
   - prove: no query exposes an unpublished claim; an answer at p never changes; successive merges are visible only from their own positions; an ending keeps earlier knowledge; τ → position; a future τ or a forged position is refused by every query.
7. **PO-5 is met by immutability** (positions ≤ the committed one never change), not by a held `REPEATABLE READ` snapshot. The design §2.1 records this.

**G-5b: `PlaceLookup` (implemented; guide 21).**
1. **Domain first:** `PlaceMatcher`.
   - **Test the `pg_trgm` value against a known PostgreSQL result** before trusting the reimplementation.
   - **Copy the legacy algorithm, not its data:** no hard-coded variants, no designation stripping. Variants are published names (G-8).
2. **Contract:** `GeographyReadModel::lookup` and `Published\LookupMatch`. Add the DTO to `published-language.json`; the contract guard fails until you do.
3. **Application:**
   - candidates = names and codes believed at (t, p) of places existing at t;
   - optional transitive scope (place and scheme together);
   - limit 1 to 50;
   - results ordered by score, then whole before partial, then id.
4. **Adapter:** `slotsOfKind`, each history through `PostgresPublishedAtlas`.
5. **Isolated-cluster tests:** use a unique word per test, because the cluster is shared.

**G-5c: the publication feed (implemented; guide 23).**
1. **Domain:** `PublicationSummary::of(changes)` gives kinds, subjects and merges, sorted and distinct (test the sort by id, not by label).
2. **Contract:** `publicationsSince(after, k, limit)` and `Published\PublicationEntry`. Add it to `published-language.json`.
3. **Application:** refuse a negative position and a limit outside 1..500; return empty at or past the knowledge; re-check the knowledge.
4. **Adapter:** `publicationsBetween` uses the rows, then their claims' slot keys and values. **No claim mapping** (so the no-bypass guard is satisfied).
5. **Cluster tests:** gap-free; never unpublished work; provenance equals the row; paging loses and repeats nothing; reproducible at the same knowledge.
6. **Never** route it through the organisation-scoped outbox. Add a push only for a real consumer (D-147).

### G-6: candidates, objections and notices

**Reads:** v3.11 §8 D-90, §15, §16. **Slices:** G-6a candidates · G-6b objections · G-6c D-90 governance notices.

**G-6a: candidates (implemented; guide 24).**
1. **Domain:** `Candidate` lifecycle (submitted → under verification → accepted · linked · rejected); `fromSnapshot` refuses impossible states.
2. **Application:** `CandidateService`.
   - Actor from `ActingPerson`; a steward at the current roster version.
   - **Read the roster through `RosterReader`; reach revisions only through `RevisionService`** (the no-bypass guard refuses the write-side ports).
   - **Accept → ProposeRevision:** acceptance *prepares* a new revision; it never attaches an existing one. A unique index allows one candidate per revision.
3. **Storage:** `refgeo_candidates` (CHECKs mirror the lifecycle), `PostgresCandidateRepository`, optimistic `row_version`.
4. **Not yet:** organisation submissions (D-147).

**G-6b: objections (implemented; guide 25).**
1. **Domain:**
   - `Objection`: submitted (cites ≥ 1 instrument) → assessed (authentic, mandate, applicable) → corrected or disputed (all positive) · rejected (≥ 1 negative, with a reason);
   - **D-84a: the recorder never assesses or decides.**
2. **Application:** `ObjectionService`.
   - The target must be **published** (`PublishedClaims`); reconsideration needs **new** evidence.
   - Outcomes **propose** a revision through `RevisionService` (lifecycle first, so there's no orphan).
3. **Storage:** `refgeo_objections` (CHECKs, recorder ≠ assessor or decider, one objection per revision).
4. **Mutation:** M1 to M8. **When a mutant "survives", check the run executed tests** (see the G-6b evidence: a driver defect once ran none).

**G-6c: D-90 governance notices (design revision 3 ACCEPTED 2026-11-14: full coverage; re-evaluate confirmers). Steps 1 to 5 done (guides 26 to 30); step 6 follows.**
- **Design:** `docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-g6c-d90-notices-design.md` §13 gives the six implementation steps.
- **Before writing any code:** confirm the Principal accepted revision 2.
- **Then, per step,** RED → GREEN → guide → commit, in this order:
  1. schema (columns, CHECKs, triggers, `refgeo_publication_persons`): **done.** Assert each refusal's REASON (guide 26, database guide 03);
  2. domain (`componentAt`, `ApprovalDefects`, `FindingLifecycle`, `GovernanceNotice`, `ApprovalStatus`): **done** (guide 27). The before ∪ after union equals the after-union (design §18);
  3. the ledger (`recordRoster`, `appendNotice`; `IdentityNoticeConsumer` under `withLock`): **done** (guide 28). The roster entry is the checkpoint; the counted approver is replayed (§17); no administrator action;
  4. `ConfirmPublication`; **Done** (guide 29); no administrator action.
  5. the correction link; **Done** (guide 30); no administrator action.
  6. the read model, feed fields, no-bypass rule and mutation run.
- **Invariants that must hold at every step:**
  - one lock and one transaction for a merge and its findings;
  - the checkpoint is the roster entry;
  - closure by re-evaluation at the new roster version;
  - **the affected persons = the components of the merge's persons BEFORE and AFTER the change** (design §5.1);
  - **one definition of an "open" finding** (design §8.1), used by the ledger, the confirmation, duplicate prevention and the read model; a PUBLISHED correction resolves it, a draft never does;
  - confirmation names an OPEN finding and its digest;
  - status reads only entries ≤ the knowledge position.

### G-7: CLDR display names (D-87)

- **Build:** CLDR names, locale inheritance and the platform default locale. These are non-structural.

### G-8: the initial evidenced data (D-226 Q-G-1)

- **Load:** World; the five M49 regions (UNSD names); ISO 3166-1 countries and areas; Nepal's scheme.
- **Rule:** every claim cites evidence competent for **that** fact and level (a competent issuer on the issue date, D-227). The legacy seeder is input, not evidence.
- **Existence (D-229):** every place rests on a competent establishing instrument, not necessarily the founding document. Record the duplicate search as an `AssessmentKind::DuplicateSearch` assessment, never as the basis. A boundary from a map uses `AttributedCartographicSource` (F-2).
- **How:** as revisions through the same validator and publication, never by inserting rows directly.

## Part D: where things are

- **Code:** `app/Contexts/ReferenceGeography/Domain`, `app/Contexts/Identity`.
- **Tests:** `tests/Unit/Contexts/ReferenceGeography/Domain`, `tests/Feature/Contexts/Identity`, `tests/Support/ReferenceGeography`.
- **Guides:** `developer_guide/reference_geography/00_index.md` (01 … 20), `developer_guide/identity/`, `developer_guide/architecture/` (the ratchet).
- **Evidence:** `docs/publicdigit/reference-geography/evidence/`.
- **Decisions:** `docs/publicdigit/reviews/2026-11-08-organisation-strategic-discovery-record-structure-mandates-conventions.md` (D-224 … D-228).
- **Story:** `docs/publicdigit/backlog/PBDIGIT-346-reference-geography-standalone-and-identity-g0.md`.
