# Reference Geography G-4: implementation interpretation (PBDIGIT-346)

**Date:** 2026-11-13 · **Author:** Session A · **Status:** the plan G-4's slices follow. **Nothing in it is new architecture.**

**Sources:**
- v3.11 §6 (the publication transaction, the boundaries, PO-1 to PO-8), §8 (roster ordering), §15 (write paths);
- the standalone completion §4 (tables, the [TU] answers), §5 (Identity inbox);
- G-3 assessment §6 (the obligations carried to G-4);
- the slice 1 ledger conventions (`PostgresMemberRepository`, `LedgerClockCloser`, the fork-based race tests).

## 1. Business invariants G-4 must prove

| # | Invariant | Source |
|---|---|---|
| B-1 | A revision version is published **at most once**; nothing becomes visible unless every step passed; a failure leaves no partial state | §6 steps 1–7; §15 |
| B-2 | **Published claims never change.** Publication adds a row; it changes no claim | completion §4, clarification A |
| B-3 | Positions are **gap-free and commit in order** | §6, PO-4 |
| B-4 | `recordedAt` is the database clock read under the lock, **never adjusted**; refused if ≤ W or below the last value | §6, PO-6, PO-7 |
| B-5 | `closedThroughTime` (W): every publication with `recordedAt ≤ W` is committed; none can be added later | §6, PO-1 |
| B-6 | **The final governance check and the append share one lock.** So does every roster write (appointment, end, reviewer, declaration, Identity notice). No roster change can fall between the check and the publication | §8; G-3 §6 |
| B-7 | **Staleness under the lock:** a publication committed after p₀ that matches a recorded predicate refuses publication and marks the revision `Stale` | §5.3, §6 |
| B-8 | **Single write path** for `refgeo_publications`, `refgeo_sequence`, published claims and the roster, enforced in code and by database guards | PO-2 |
| B-9 | **Governance is enforced in the application:**<br>- submission only after `maySubmit`;<br>- approval only after `mayApprove`;<br>- publication only after `mayPublish` under the lock;<br>- roster writes only through the platform-owner boundary, which stays **closed in production** | G-3 §6; G-3 acceptance item 3 |

## 2. Aggregates and transaction boundaries

| Transaction | Lock | Contents |
|---|---|---|
| **Prepare / validate / submit / approve** (any duration) | the revision row (optimistic, by its stored version) | the `Revision` aggregate only; validation reads the atlas at p₀ through the recording reader |
| **Publish** (short) | `refgeo_sequence` row `FOR UPDATE`, READ COMMITTED as the first statement | reload the revision and roster → `mayPublish` at the roster's current version → staleness (claims in (p₀, current]) → clock rule → insert the publication (p, revision, content version, roster version, rule, independence) → advance the row → commit |
| **Roster write** | the same row `FOR UPDATE` | reload the roster → domain write → insert the entry with roster version = the row's roster version + 1 → advance → commit |
| **Close through τ** | the same row `FOR UPDATE NOWAIT` (busy: keep W) | the clock ≥ τ → W = τ |

**One row** (`refgeo_sequence`) holds: the last position, W, the last `recordedAt` and the last roster version (§8 "its own gap-free counter, in the same row").

## 3. Persistence (completion §4 tables; `refgeo_*`, central database)

- **`refgeo_sequence`:** one row, created by the migration; a guard refuses a second row and every delete.
- **`refgeo_publications`:** insert-only. `position` is unique; **(revision, content version) is unique**, so a duplicate publication is impossible even if code goes wrong.
- **`refgeo_claims`:** immutable (a trigger refuses UPDATE and DELETE). The rows of one (revision, content version) can't be added to once that version is submitted.
- **`refgeo_revisions` and their children:** versions, claims per version, evidence, assessments, approvals, judgment flags, and the validation (p₀, structural flag, dependencies, before-images). The rows are stored as written. **Reloading rebuilds the aggregate exactly** (round-trip tests).
- **`refgeo_roster_entries`:** append-only, with a unique roster version and a unique (merge id, kind) for Identity notices.

**A domain addition is needed, and is tested first.** The aggregates have no rebuild path yet. G-4 adds:
- `SlotKey::decode`;
- `StewardRoster::fromEntries`, which checks the versions are gap-free and replays the entries;
- an explicit `Revision` snapshot and rebuild.

This adds mechanism only. **No rule changes.**

## 4. The Identity consumption boundary

- **Identity is a separate context.** Geography never takes part in Identity's transaction.
- **A notice is applied locally** by `RecordIdentityNotice` under the Geography lock, as a roster entry. A duplicate (by (merge id, kind)) writes nothing.
- **A publication's independence is "as known to Identity at roster version v"**: exactly the notices Geography had recorded by v.
- **A delayed notice** affects only later roster versions. Earlier approvals and publications keep the version they were judged at; cases B and E are G-6.
- **No distributed atomicity is claimed.**

Identity's merge procedure doesn't exist yet, so the entry point is tested with fixture notices (completion §5).

## 5. Application services and ports

**Services:**
- `SubmitRevision`, `ApproveRevision`, `PublishRevision`, `CloseThroughTime`;
- the roster writes: `AppointSteward`, `EndSteward`, `AppointMatterReviewer`, `DeclareConflict`, `RecordIdentityNotice`.

**Ports and how they're used:**
- **`ActingPerson`** (bound to `IdentityActingPerson`) gives the acting person. No service accepts a person from the caller, except notices, which come from Identity.
- **`PlatformOwnerAuthority`** is a new port, for appointments.
  - **Its production binding refuses everyone** until the Principal decides who the platform owner is (G-3 acceptance item 3).
  - Tests use a fixture.
  - **Conflict declarations are made by the declarer themselves** (D-84a), not by the platform owner.

## 6. Slices (strict TDD; real PostgreSQL on the isolated cluster for G-4b onward)

| Slice | Content | Proof |
|---|---|---|
| **G-4a** | migrations and database guards | insert-only and immutability triggers, one sequence row, unique (revision, version), unique roster version and notice |
| **G-4b** | domain rebuild paths: `SlotKey::decode`, `StewardRoster::fromEntries`, the `Revision` snapshot | round trips; tampered history refused |
| **G-4c** | `PostgresStewardRoster` and the roster-write services under the lock; `PlatformOwnerAuthority` (production: deny) | reload; gap-free under forked writers; duplicate notice; deny-by-default |
| **G-4d** | `PostgresRevisionRepository` and the `PublishedAtlas` adapter | round trip incl. validation, before-images, approvals; validation reads at p₀ |
| **G-4e** | `PostgresPublicationLedger`: the publish protocol, staleness, clock rule, close through τ | B-1 to B-7; PO-1, PO-4, PO-6 |
| **G-4f** | application services and wiring; architecture test for the single write path | B-8, B-9; no bypass |
| **G-4g** | concurrency proofs with forked processes; mutation check | the concurrency list in the Principal's instruction §6 |

## 7. D-230 traceability (instruction §7)

| D-230 condition | Path today | Kind |
|---|---|---|
| dispute | a DISPUTE claim in the revision | **automatic** |
| precedence | a precedence assessment of the current version | **automatic** |
| objection, missing-unit submission | G-6: the Candidate and Objection lifecycles raise the flag | **automatic once G-6 exists** (planned); flag-only until then |
| anyone involved flags | `flagJudgmentMatter` | **explicit human flag** |
| competing evidence leaves reasonable doubt | `JudgmentReason::CompetingInstruments` / `UncertainClassification` flags | **explicit human flag only.** **No automatic detection exists, and none is claimed.** |

**The gap:**
- **What the system can detect:** a revision that claims a slot whose published value, for an overlapping interval, rests on a different instrument. That's a *candidate* signal.
- **Where it belongs:** it needs a rule on what counts as "competing", **which the sources don't define.** Recorded as an open D-230 obligation, for the Principal to schedule. It is **not part of G-4.**

**Traceability:** PBDIGIT-346 G-4 · v3.11 §5.3, §6, §8, §15 · completion §4, §5 · G-3 §6 · D-230.
