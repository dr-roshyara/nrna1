# Reference Geography (standalone): tactical completion for implementation — PROPOSED

| | |
|---|---|
| **Status** | **ACCEPTED by the Principal, 2026-11-13 (D-226)**, with Q-G-2 = yes (with the safeguard of §5a) and Q-G-1 as recommended (with the evidence rule of §9). **§4 and §5a carry the two clarifications the Principal DDD review required.** §8 is the approved implementation sequence |
| **Commission** | Principal DDD commission "Complete Reference Geography standalone tactical design" (2026-11-13); D-224 (2) |
| **Consumes, unchanged** | **v3.11** (`2026-11-09-reference-geography-tactical-design-baseline-v3.11.md`, ratified D-93) and §C of the strategic baseline; D-31, D-79 … D-92 (incl. D-84a), D-147, **D-224, D-225** (Identity contract IG-1 … IG-6); legacy assessment of `2026-11-09-reference-geography-baseline-closure-and-tactical-design.md` §C.2–C.3 |
| **Scope** | the central, platform-level Reference Geography context **on its own**. **Not:** Organisation coverage, allocation, belonging (D-147); addresses, streets (D-83); L-4c, Q-2, Japanese classification, naming policies, address ownership (deferred by D-93) |

## 0. Disagreements first

1. **This is a completion, not a second tactical design.** v3.11 is already the ratified tactical design: entities and identity (§2), claims and slots (§3), validation (§5), publication and boundaries (§6), governance (§8), CLDR (§9), aggregates (§16). **Re-describing it would create a competing text** (ES-005.4). This document:
   - **maps** each v3.11 element to code (§3), persistence (§4) and tests (§7);
   - **designs only what v3.11 left to implementation:** its §17 [TU] items, the merge consumption (§5) and the legacy coexistence (§2);
   - where this document and v3.11 differ, **v3.11 governs.**
2. **The existing `app/Contexts/Geography` is not this context, and it is not to be edited.** It is the legacy model (71 files, Eloquent in its Domain, organisation copies with new ids, mutation in place). The accepted assessment says **"the core model has to be replaced, not patched"** (closure §0 item 3, §C.2). **15 files outside it read its tables directly.** The new context is built **beside** it (§2).

## 1. Business explanation

Reference Geography is PublicDigit's **trusted atlas**: which official places exist, how they are officially arranged, how that changed, on what evidence, and **who approved each change.**
- **Nothing is ever overwritten.** A change is a **revision** of claims; publishing it gives it a **position** in one gap-free sequence.
- **Anyone can ask** "what was valid on date *t*, as PublicDigit knew it at τ?" and get a complete answer, or a refusal, never a guess.
- **Important changes need two different people:** one prepares and assesses, an independent second person approves (D-84, D-88, D-89). "Different" is checked with Identity's person references (D-225), never by names.
- **It decides nothing about people:** no membership, belonging, entitlement or sovereignty (v3.11 §1).

## 2. Placement and coexistence with the legacy model

| Decision | Choice | Why |
|---|---|---|
| **Namespace** | **`App\Contexts\ReferenceGeography`** (Domain / Application / Infrastructure), a **new** folder | the legacy `App\Contexts\Geography` keeps serving its 15 readers unchanged; one name per model avoids mixing the two |
| **Database** | **the central database** (Laravel's default connection, D-222), tables prefixed **`refgeo_`** | Geography is platform-level and central (D-31, baseline §4a). **Not** `pgsql_geo` (a legacy connection), never an organisation database |
| **Legacy tables** | **untouched;** no foreign key either way; no shared table | the cutover of the 15 readers to the new contract is **Organisation integration, a later release (D-147)** |
| **Kept from legacy, by copy into the new namespace, not by import** (closure §C.2 "keep") | `LocalizedName`, `CountryCode`, the fuzzy matcher (as `PlaceLookup` match quality) | the new context must not depend on the legacy model; an architecture test enforces it |
| **Architecture tests** | `ReferenceGeography` imports nothing from `App\Contexts\Geography`, `Organisation`, `Membership`, `Governance` or `Identity` internals; no other context reads `refgeo_*` tables | D-147; v3.11 §16; IG-P8 |

## 3. Domain model: v3.11 element → code

**Domain layer (pure PHP, final classes, no clock):**

| v3.11 | Code element | Kind | Invariants enforced here |
|---|---|---|---|
| §2 identity | `ReferenceUnitId` | value object (opaque, minted once) | I-3 never reused |
| §2 entity types | `EntityType` enum: World · StatisticalRegion · CountryOrArea · TerritorialUnit | enum | admission rules per type (D-81, D-85, D-86) |
| §3 claim | `Claim` (kind, `SlotKey`, value, `ValidInterval [from, to)`, `Basis`) | value object, **insert-only** | — |
| §3 slot key | `SlotKey` per `ClaimKind` (23 kinds, §3 table) | value object | **key starts with the kind**; the collision proof of §3 is a unit test per kind |
| §3 supersession | `SlotHistory::resolve(slot, t, upToPosition)` | domain service | supersession only within one slot and only for the valid time covered; withdrawal = claim with value "none" in the target's slot; disputes keyed by target claim |
| §2 I-1, I-2 | `IdentifierInvariants` | domain service | one live subject per value and namespace; one value per subject and namespace |
| §4 belief | `Belief` (published, disputed, reconciled) | value object | — |
| §12 reconciliation | `Reconciliation` claim kind + `ResolveIdentity(B) → A` at p | domain service | B's claims and identity remain; earlier τ shows B |
| §13 profiles | `CountryScheme` from PROFILE_* claims | read-side value | one meaning per position (G-6) |
| §5 validation | `RevisionValidator` returning violations **and the dependency predicates read** | domain service over a read port | §5.2 matrix; reads **only** through `DependencyRecordingReader` (§6, PO-3) |
| §16 | **`Revision`** aggregate | aggregate | draft → submitted (frozen content version) → approved → published / stale / withdrawn; R-1 internal completeness; approvals bound to (revision, content version, roster version); re-preparation voids earlier assessments and approvals |
| §16 | **`StewardRoster`** aggregate | aggregate | appointments over time; `secondStewardEverAppointed` (permanent, D-89); matter reviewers (D-84a); conflict declarations; recorded identity notices (§5); **`excluded(r)` at a roster version** |
| §16 | **`Candidate`**, **`Objection`** | aggregates | v3.11 §16 lifecycles; effect on knowledge only via a revision |
| §8 | `PublicationGovernance::mayPublish(revision, rosterAtVersion)` | domain service | era A / B; structural (§14, D-91, D-92) needs a second person ∉ `excluded(r)`; conflict rules (D-84a); cases 1–5 |
| §8 D-90 | `GovernanceNotice` (finding · confirmation · closure) | value object, published at its own position | no claims |
| §6 | `PublicationEntry` (position, revision \| notice, `recordedAt`) | value object | gap-free; `positionAt(τ)` |

**Application layer (pure PHP; ports only, DTO commands):**

| Command / query | Port(s) | Note |
|---|---|---|
| `PrepareRevision`, `AddClaim`, `AttachEvidence`, `AssessRevision`, `SubmitRevision` | `RevisionRepository`, `EvidenceStore` | before the lock; any duration |
| `ApproveRevision` (second person) | `RosterRepository`, **`ActingPerson`** (→ IG-2) | records the roster version |
| `PublishRevision` | `PublicationLedger` (§4) | the only write path to published knowledge (PO-2) |
| `PublishGovernanceNotice` | `PublicationLedger` | D-90 |
| `AppointSteward`, `EndAppointment`, `AppointMatterReviewer`, `DeclareConflict` | `RosterRepository`, `ActingPerson` | roster writes take the publication lock (v3.11 §8) |
| `RecordIdentityNotice` (IG-4 / IG-5) | `RosterRepository`, inbox | §5 |
| `SubmitCandidate`, `DecideCandidate`, `SubmitObjection`, `DecideObjection` | their repositories | operational, outside the knowledge stream (§15) |
| `CloseThroughTime(τ)` | `PublicationLedger` | the W step (§4) |
| **Queries** `PlacesAt(t, p\|τ)`, `PlaceLookup(text, t, p\|τ)`, `HierarchyAt`, `BeliefOf`, `DisplayName`, `PositionAt(τ)` | `GeographyReadModel` | refuse beyond the boundary; never partial |

**Domain events** (central outbox): `RevisionPublished`, `GovernanceNoticePublished`, `CandidateDecided`, `ObjectionDecided` (v3.11 §10), one per position for the first two.

## 4. Persistence and concurrency (central database, PostgreSQL)

| Table | Content | Rule |
|---|---|---|
| `refgeo_sequence` | **one row:** last committed position, `closed_through_time` (W), last `recorded_at` | the publication lock is `SELECT … FOR UPDATE` on this row (v3.11 §6). **Not a PostgreSQL sequence** |
| `refgeo_publications` | position, revision id or notice, `recorded_at timestamptz(6)` | insert-only; `position` unique; trigger refuses UPDATE / DELETE |
| `refgeo_claims` | claim rows of one content version of a revision (kind, slot key, value, interval, basis, revision, content version) | **fully immutable:** a trigger refuses every UPDATE and DELETE. **Clarification A (2026-11-13):** there is **no** `published_position` column. A claim is published **iff** its (revision, content version) is the one named by a `refgeo_publications` row. Publication **adds a row; it changes no claim** |
| `refgeo_revisions`, `refgeo_revision_versions`, `refgeo_assessments`, `refgeo_approvals`, `refgeo_dependency_predicates` | the `Revision` aggregate | approvals carry (revision, version, roster version, `ContextPersonRef`) |
| `refgeo_roster_entries` | append-only roster writes (appointment, end, matter reviewer, declaration, identity notice), each with a gap-free **roster version** | written only under the publication lock (v3.11 §8) |
| `refgeo_candidates`, `refgeo_objections`, `refgeo_evidence` | operational records | outside the knowledge stream |
| projections (`refgeo_view_*`) | browse, hierarchy, belief, display names | rebuilt from claims; never identity |

**Implementation answers to v3.11 §17 [TU]:**

| [TU] item | Answer | Proof |
|---|---|---|
| `FOR UPDATE` re-read under the chosen isolation | the publication and close transactions run at **READ COMMITTED**, set explicitly (`SET TRANSACTION ISOLATION LEVEL READ COMMITTED`) as slice 1's ledger repository does; a waiter re-reads the committed row | PO-1, PO-4 tests with forked processes (the slice 1 race-test method) |
| single write path for published claims | one Infrastructure class (`PostgresPublicationLedger`) is the only writer of `refgeo_publications` and `refgeo_sequence`; claims are immutable after insert, and a submitted content version's claims cannot be added to (trigger); an architecture test finds no other writer | PO-2 |
| validator reads only via dependency-recording queries | `RevisionValidator` receives a `DependencyRecordingReader` port and nothing else; every method records its predicate; architecture test: the validator's namespace imports no repository or connection | PO-3 |
| clock and regression | `recorded_at = clock_timestamp()` read **under the lock**; if `c ≤ W` or `c < last recorded_at` → **refuse** (`CLOCK_NOT_PAST_BOUNDARY`), roll back, retry later; **never adjusted** (D-209 pattern) | PO-6, PO-7 |
| the W step (`closedThroughTime`) | `CloseThroughTime` takes the same row lock (`FOR UPDATE NOWAIT`; busy → keep the stored W, as slice 1's closer), reads the clock, sets W only forward | PO-1, PO-5 |
| one snapshot per reader | read models answer in one `REPEATABLE READ READ ONLY` transaction: read the boundary, then the data | PO-5 |
| host time slewing (refusal frequency) | monitoring of `CLOCK_NOT_PAST_BOUNDARY` counts; operational, not a design change | ops |

**No transaction spans Geography and another context.** Identity notices arrive through an inbox (§5); `RevisionPublished` leaves through the central outbox.

## 5. Identity integration (D-225)

**Identity side (the first implementation slice; Identity context, not Geography):**
- **IG-1:** table `identity_context_person_refs (person_id, context, ref)`, unique `(person_id, context)` and unique `ref`; `ref` random and opaque, issued on first request, never reused;
- **IG-2:** `ActingContextRef(context)`: session Account → `active`? → Person (survivor if `mergedInto`) → issue or return the ref. Refused if the Account is not active. Identity unavailable → the caller refuses;
- **IG-3:** no reverse lookup is offered at all. The port exposes `actingRef()` only.

**Geography side:** the `ActingPerson` port, whose infrastructure adapter calls IG-2 with `context = 'reference-geography'`.

**Merge consumption (IG-4 / IG-5), designed and fixture-tested now, live only when Identity's merge procedure exists:**

| Rule | Mechanism |
|---|---|
| **Duplicate notice** | inbox idempotency key `(mergeId, kind)`. **A replay writes nothing:** no new roster entry, no new roster version |
| **Recording** | the first receipt of each notice is **one roster entry** (append-only) under the publication lock, with the next roster version |
| **Equivalence at roster version v** | `E(v)` = the **transitive closure** of the links `{r ~ r'}` of every merge recorded at a version ≤ v **whose reversal is not recorded** at a version ≤ v. **`survivorRef` plays no part** (it matters only to IG-2) |
| **Reversal before its merge** | both are recorded when they arrive. **`E` excludes a merge whose reversal is recorded at or before v**, so the later-arriving merge has no effect. Order-insensitive, and nothing is rewritten |
| **Overlapping merges** | m₁ {a, b} and m₂ {b, c} give {a, b, c}. **Reversing m₁ alone** gives {b, c} with a separate; m₂ untouched |
| **A reversal affects only its own merge** | it names `mergeId`; `E` drops exactly that merge's links |
| **Unknown references in a notice** | recorded anyway (harmless; a later appointment of such a reference is then already covered) |
| **`excluded(r)` at version v** | the people who prepared, assessed or submitted any version of r, **mapped through `E(v)`**; the second person must not be equivalent to any of them at the roster version used at publication (v3.11 §8) |
| **History** | earlier approvals keep the roster version they were given under; v3.11 cases A–E judge them; findings are D-90 notices |

## 5a. What the identity mapping can and cannot prove (clarification B, safeguard on Q-G-2)

- **G-0 maps each legacy `users` row to one Identity Person, lazily.** This is a **transitional technical mapping** (I-TDD-02 §18), **not evidence that one human holds one account.**
- **Two `ContextPersonRef`s mean "not known to be the same person", never "proven different people".** The only knowledge of sameness is Identity's recorded merges (IG-4, IG-5).
- **The independence check is named and recorded accordingly:**
  - `PublicationGovernance` asks "is the second person **known-same** (under `E(v)`, §5) as anyone in `excluded(r)`?";
  - the publication records **"independent as known to Identity at roster version v"**;
  - no record, report or screen states "verified distinct person".
- **This is the accepted residual risk of D-225** (two accounts of one person that Identity never merges are not detected). No identity-verification capability is invented.
- **"Active Account" for a legacy `users` row (technical interpretation):** **not soft-deleted and e-mail verified** (`deleted_at IS NULL AND email_verified_at IS NOT NULL`). The table has no suspension state; this is the closest reading of I-TDD-02's "active, verified login identifier". It is replaced by the Account state when the full Identity model exists.

## 6. Failure and refusal

| Situation | Behaviour |
|---|---|
| a query beyond `closedThroughPosition` / `closedThroughTime` | refused (`BOUNDARY_NOT_CLOSED`); never a partial answer |
| clock regression or `c ≤ W` | publication or close refused; retried later; no timestamp adjusted |
| lock busy at close | the stored W stays; queries beyond it are refused |
| stale revision (delta check finds a conflict) | rolled back, marked `Stale`; re-preparation voids earlier approvals |
| `ActingPerson` refused (inactive Account, Identity down) | the roster or approval act is refused |
| unknown or unappointed actor | refused |
| a structural revision without an eligible second person | waits (D-89); never published |
| Geography unavailable to a consumer | the consumer refuses beyond its copy (baseline §8:399); no consumer exists in release 1 (D-147) |

## 7. Proof-obligation matrix (all NOT EXECUTED)

| # | Guarantee | Executable test | Failure = |
|---|---|---|---|
| **PO-1** | the lock is held from the clock reading to commit, by every writer and every close | forked publisher + closer race on the isolated cluster | a close commits W ≥ a writer's `recordedAt` while that writer is open |
| **PO-2** | single write path | architecture test + guard triggers attempting direct writes | any write outside `PostgresPublicationLedger` succeeds |
| **PO-3** | dependency-only reads | architecture test on the validator; unit test that every reader call records a predicate | a validation read without a recorded predicate |
| **PO-4** | gap-free, in-order positions | concurrent publishers; rollback reuses the position | a visible p+1 while p is not visible |
| **PO-5** | one snapshot per reader | reader with a concurrent publication | an answer mixing two snapshots |
| **PO-6** | refuse on regression, never adjust | scripted clock reader (slice 1's `ScriptedLedgerClockReader` pattern) | a stored `recorded_at` ≠ the reading, or ≤ W |
| **PO-7** | one clock for all writers | architecture test: only `clock_timestamp()` | two clocks feeding `recorded_at` |
| §3 collision proof | slot keys never collide across or within kinds | one unit test per kind | one kind supersedes another |
| I-1 … I-4 | identity invariants | unit tests | a reused id, a namespace value on two live subjects |
| §8 rules | eras A/B; second person; matter reviewer; conflicts; cases 1–5 | unit tests on `PublicationGovernance` | a structural revision published by one person in era B |
| **IG-P7** | merged references count as one in `excluded(r)` from the merge's version; distinct again from the reversal's | fixture notices: duplicate, reversal-first, overlapping, partial reversal, replay | a merged pair passes as independent, or a replay creates a roster version |
| **IG-P8** | Geography holds references only; imports no Identity internals | architecture test | an Identity class or `PersonId` in Geography |
| IG-P1 … IG-P6 | Identity contract | Identity slice tests | as D-225 |
| no data leakage | no person data beyond references; no organisation data | architecture + schema test (`refgeo_*` has no name / e-mail / organisation column) | such a column or import |

## 8. Implementation slices (each RED → GREEN → guide; separate from Platform slice 2)

| # | Slice | Depends on | Without the merge procedure? |
|---|---|---|---|
| **G-0** | **Identity IG-1 … IG-3** (new `App\Contexts\Identity`): the minimal Person (one per `users` row, lazily), `ContextPersonRef`, `ActingContextRef` | **Q-G-2** | yes |
| **G-1** | Domain kernel: ids, claims, slot keys (23 kinds), intervals, supersession, identity invariants, collision proofs | — | yes |
| **G-2** | `Revision` aggregate + validator with dependency predicates | G-1 | yes |
| **G-3** | `StewardRoster` + `PublicationGovernance` + `excluded(r)` + **merge consumption with fixtures** | G-1, G-0 (port only) | yes (fixtures) |
| **G-4** | `PostgresPublicationLedger`: sequence row, lock, staleness, clock, W step; guard triggers; PO-1 … PO-7 on the isolated cluster | G-2, G-3 | yes |
| **G-5** | Read models: `PlacesAt`, `PlaceLookup`, hierarchy, belief, `PositionAt`; outbox event `RevisionPublished` | G-4 | yes |
| **G-6** | `Candidate`, `Objection`, D-90 notices | G-4 | yes |
| **G-7** | CLDR display names (D-87) and the platform default locale | G-5 | yes |
| **G-8** | **Initial evidenced data** as revisions (closure §C.3 item 4) | G-4, G-5; **Q-G-1** | yes |

**The test harness is the task 0 cluster** (central database), extended only if a slice needs it.

## 9. Findings by kind

| Kind | Items |
|---|---|
| **Already accepted** | everything in v3.11 and D-225; the legacy verdict (closure §C.2) |
| **Tactical choices made here** | §2 namespace, central database, `refgeo_` tables, no legacy coupling; §4 isolation, guard triggers, single writer; §5 equivalence algorithm; §8 slices |
| **Finding (verified)** | **No Identity context exists in code:** no `app/Contexts/Identity`, no Person, Account or reference tables; logins are the legacy `users` table. I-TDD-02 §18 states the principle "each existing `users` row → one Account + one Person"; its migration ADR is "later". **So G-0 cannot only add a reference; it needs a minimal Person behind each login** |
| **Principal decisions (decided 2026-11-13, D-226)** | **Q-G-2 = YES (blocks G-0): may G-0 create the minimal Identity Person, one per existing `users` row, created lazily on its first `ActingContextRef` call, as I-TDD-02 §18's principle, ahead of the full users migration ADR?**<br>- Only `PersonId` + state (INV-ID-1); the `users` row acts as the Account; nothing else of I-TDD-02 is built.<br>- **Recommendation: yes.** It is the smallest step that satisfies "one reference per Person" without inventing a mapping that would later need changing. A later finding that two `users` rows are one person is exactly a merge (IG-4).<br>- **Alternative:** build I-TDD-02's Account model first, a larger Identity slice before any Geography code.<br><br>**Q-G-1 = as recommended (blocks only G-8). Evidence rule:** every published claim needs a source competent for **that** fact and level. The existing seeder is input material, not evidence; Schedule 4 is cited only for what it establishes (provinces and districts), not for lower units.  which data is loaded first?** Recommendation: World; the five M49 regions; ISO 3166-1 countries and areas (D-86); **Nepal's** territorial scheme (the existing `NepalGeographySeeder` content, re-entered as evidenced revisions with Schedule 4 of the Constitution); a second country when a customer needs it. **v3.11 §17's [OQ] on the names of the level-1 regions is part of this data decision** (recommendation: UNSD M49 names as source-attributed names) |
| **Not a decision now** | the first steward's appointment: an operational act by the platform owner (D-84) once G-3 exists |

## 10. Readiness

- **The design is ready for review.**
- **G-1 … G-7 can be implemented once accepted;** no business decision blocks them. G-3 uses the `ActingPerson` port with a test double until G-0 exists.
- **G-0 waits for Q-G-2.**
- **G-8 waits for Q-G-1.**
- **Geography–Organisation integration stays later** (D-147).

**Traceability:** D-31, D-73, D-75, D-79 … D-92, D-93, D-147, D-164, D-209, D-222, D-224, D-225 · v3.11 §1–§17 · closure §0, §C.2, §C.3 · slice 1 (PBDIGIT-343) lock and clock patterns.
