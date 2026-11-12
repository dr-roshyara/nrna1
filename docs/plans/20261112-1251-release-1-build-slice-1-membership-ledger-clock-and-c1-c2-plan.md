# Release 1, build slice 1: Membership ledger clock (D-209) and the C1 / C2 provider

| | |
|---|---|
| **Status** | **APPROVED for implementation by the Principal, 2026-11-12 (EP-01): "Approve, start TDD". Revision 2.** Revision 2 applies the Principal DDD review of 2026-11-12 (refinements A, B, C). Revision 1: `…-rev1.md` (`719ea775…`). **No code until approved; strict TDD (RED before GREEN) once approved** |
| **Build order** | **approved by the Principal** (2026-11-12, accepting the review's recommendation). It supersedes §3 of the unapproved `docs/plans/20261110-1751-minimum-package-build-and-organisation-structure-slice-1-plan.md`; see §13 |
| **Story** | **PBDIGIT-343** (to be created on approval). *(PBDIGIT-342 is reserved by the 2026-11-10 plan.)* |
| **Authority** | **D-162** (C1/C2 spec) as amended by **D-209**; **D-215** (K-C1); **D-161**; **D-160**; D-73; D-210 §4 (K-1, K-2), §11; RA. **Unchanged:** the C1/C2 Published Language, the meaning of `closedThrough` ("every fact with `recordedAt ≤ closedThrough` is committed and visible, and none can still be committed later", CR-01 :28), D-147 |
| **Design texts** | C1/C2 spec §3, §5, §6, §8, §11; ledger-clock amendment rev 2 §2; blueprint rev 4 §6 |

## 1. Objective

**Make the committed Membership kernel conform to D-209, and provide the accepted C1 feed and C2 query,** proved on the isolated PostgreSQL test cluster.

**Slice 1 builds and proves a capability in isolation. It does not make Membership live for any organisation:**
- the source-of-truth port refuses every organisation (`SOURCE_NOT_LEDGER`) until slice 2 configures the gate C-1 flag at joining (D-160);
- no migration reaches the development or production database.

## 2. Readiness review (EP-03)

| Question | Answer | Source |
|---|---|---|
| Business capability | provable membership history "as known at τ", which forum access, newsletter audiences and placement depend on | D-161, D-162 |
| Owning context / other contexts | **Membership.** Organisation consumes C1 and C2 in a later slice; **no change to it here** | D-73, D-142 |
| Published Language | **the accepted C1 and C2; shapes unchanged** | D-162, D-209 §3 |
| Ownership or context-map change | **none** | D-214 |
| Reused, not duplicated | `Member::resolve()`, `knownAt(τ)`, `ResolvedPeriod::lifetime()`, `ResolvedMember::isMember()`; `DecideAndAppend` (bounded loop); the `outbox_events` table, relay and `EventHydratorRegistry`; the isolated cluster suite (PBDIGIT-324) | §3 |
| Stable / may change | **stable:** RA invariants, C1/C2 shapes, `closedThrough` meaning. **May change:** the repository clock mechanism (D-209) | D-209 §3 |
| Gates | plan approval; isolated cluster available; **no dev-database migration** | CLAUDE.md |

## 3. Repository evidence (HEAD `a53ba8ea8`)

| # | Fact | Evidence |
|---|---|---|
| F-1 | `recordedAt = max(application clock, last + 1 µs)` per membership | `PostgresMemberRepository.php:216–220`; constructor `\Closure $clock` `:50–51` |
| F-2 | append order: idempotency → valid time → version compare-and-set → fact → command row; one transaction at READ COMMITTED; refuses nesting (F-5 guard) | same file `:155–234` |
| F-3 | no clock row, `closedThrough`, C1 or C2 | `2026-11-12-release-1-blueprint-b05-repository-evidence.md` |
| F-4 | retry loop: `MAX_ATTEMPTS = 3`, retries only `ConcurrencyConflict` | `DecideAndAppend.php:27, 46–70`; `AppendOutcome.php` |
| F-5 | `OutboxWriter` and every context's outbox adapter write through Eloquent `OutboxEvent` (default connection) | `Shared/Infrastructure/Outbox/OutboxWriter.php`; `Contestation/…/ChallengeOutboxAdapter.php` |
| F-6 | no gate C-1 flag | B-05 evidence |
| F-7 | seven test classes construct the repository with a clock closure | `tests/Feature/Contexts/Membership/Organisational/Postgres*Test.php` |
| F-8 | the ledger is not live (migrations exist, not rolled out) | C1/C2 spec §0 |
| **F-9** | **`outbox_events`:**<br>- `id uuid` PK, `event_id uuid` unique, `organisation_id uuid`, `aggregate_type varchar(100)`, **`aggregate_id uuid`**, `event_type varchar(150)`, `payload json`;<br>- `status` ∈ {pending, processing, completed, failed} (default pending), `attempts int` (default 0), `available_at` (default now), `created_at` (default now), `processed_at` null;<br>- `correlation_id uuid` null, `causation_id uuid` null | `2026_05_04_000001_create_outbox_events_table.php:12–26`; `2026_07_10_000001_add_provenance_to_outbox_events.php:20–21` |
| **F-10** | **the relay** (`outbox:process` → `OutboxEventProcessor`):<br>- reads `status = 'pending' AND available_at <= now()` by `created_at`, 100 at a time, **through Eloquent (default connection)**;<br>- hydrates by `event_type` through `EventHydratorRegistry`;<br>- dispatches the domain event and an `IntegrationEvent` envelope, then `completed`;<br>- **an unregistered `event_type` is dead-lettered immediately** | `OutboxEventProcessor.php:24–60, 137–160`; `EventHydratorRegistry.php`; `OutboxEvent.php:76–79` |
| **F-11** | **Membership registers its hydrators in `MembershipServiceProvider`** (e.g. `FeePaidHydrator`, line 583) | `MembershipServiceProvider.php:583` |
| **F-12** | **`org_memberships.id` is `text`; `MemberId` accepts any non-empty string supplied by the caller**, so it is not guaranteed to be a UUID | `2026_10_09_100000_create_org_membership_tables.php:27`; `MemberId.php:12–21`; `AdmitMemberHandler.php:48` |
| **F-13** | **`org_memberships.organisation_id` has a foreign key to the central `organisations` table** | same migration `:34` |

## 4. Scope

**In:**
1. **Kernel (pure PHP):**
   - `ResolvedMember::coverage()` (INV-COV);
   - public `inForceIntervals()`;
   - `suspendedIntervals()`, **always empty until Suspend exists (slice 5)** and stated as an empty set, not omitted.
2. **Schema:** a new migration with `org_membership_ledger_clocks` and its backfill (D-10). **Applied only on the isolated cluster** by the test suite.
3. `LedgerClockReader` port; a PostgreSQL adapter on the repository's own connection.
4. The append rewrite (D-2), with K-C1.
5. The retryable refusal and bounded retry (D-3).
6. `LedgerClockCloser`, the `closedThrough` step (D-4), with the empty scheduled-resignation hook.
7. **The C1 writer on the repository's connection (D-5), and `MembershipCoverageResolvedHydrator` registered by Membership (D-6),** so the relay does not dead-letter it.
8. The C2 query service and Published Language DTOs (D-7, D-8).
9. `MembershipSourceOfTruth` port, **fail-closed default** (no production storage).
10. Architecture tests; the developer guide.

**Out:**
- Organisation's consumer side and inbox;
- per-organisation routing;
- gate C-1 flag storage;
- **relay redesign** (slice 2, §12);
- Renew / Suspend / Restore, scheduled resignations, the D-205 guard exception (slice 5);
- Governance;
- Forum, Newsletter;
- HTTP or UI;
- **any migration on the development or production database** (gate C-10, separate approval);
- any change to RA, C1/C2 or the shared outbox schema.

## 5. Design decisions

### D-1 Record time only from the database clock
- `LedgerClockReader::now(Connection)` runs `SELECT clock_timestamp()` **on the repository's connection, after the clock-row lock** (D-2 step 5).
- The constructor's `\Closure $clock` no longer reaches `recorded_at`.
- **Tests:**
  - **regression and equality** tests inject a scripted reader;
  - **concurrency proofs use the real database clock.**
- **An architecture test fails if production binds any other reader** (LC-5).

### D-2 Append, one transaction, READ COMMITTED (unchanged), refusing nesting (unchanged)

| # | Step |
|---|---|
| 1 | idempotency answer (unchanged; a replay writes nothing) |
| 2 | valid-time check (unchanged) |
| 3 | ensure the clock row: `INSERT … ON CONFLICT (organisation_id) DO NOTHING` |
| 4 | **lock it: `SELECT … FOR UPDATE`** (waits, never NOWAIT) |
| 5 | `t` = `LedgerClockReader::now` |
| 6 | **refuse `RecordTimeRefused`** if `t ≤ closed_through`, `t < last_recorded_at`, **or `t` = this membership's last `recordedAt` (K-C1)** → rollback |
| 7 | version compare-and-set (IDEM-3 re-read kept) |
| 8 | insert the fact, `recordedAt = t` |
| 9 | `last_recorded_at = t` |
| 10 | C1 row if coverage, `inForce` or `suspended` changed (D-5) |
| 11 | command row |
| 12 | commit |

**The lock order is clock row, then version** (spec §11 correction note).

### D-3 Retry and refusal
- `AppendOutcome::RecordTimeRefused` → `DecideAndAppend` treats it **as a fresh attempt: re-load, re-authorise, re-decide** (never reuse, R-1a-2).
- **Exhaustion → `ApplicationRefusal::RecordTimeUnavailable`** (fail closed).
- **No timestamp is ever adjusted.**
- A regression is flagged as a structured warning `membership.ledger_clock.regression` (organisation id and the readings; no personal data).

### D-4 The `closedThrough` step under lock contention (refinement A)

**PostgreSQL aborts the whole transaction when `FOR UPDATE NOWAIT` cannot take the lock** (SQLSTATE `55P03`). The step therefore **never reuses that transaction:**

```
closeOrRead(tenant):
  refuse if called inside a transaction                      (like append's F-5 guard)
  statement (autocommit): INSERT … ON CONFLICT DO NOTHING     (ensure the row)
  try:
    BEGIN; SET TRANSACTION ISOLATION LEVEL READ COMMITTED
    SELECT last_recorded_at, closed_through … FOR UPDATE NOWAIT
    t = clock_timestamp()
    if t < last_recorded_at or (closed_through not null and t < closed_through):
        ROLLBACK; flag regression; goto READ                   (no advance)
    (a) due scheduled resignations: none in slice 1            (slice 5: record each End, recordedAt = t)
    (b) closed_through = max(closed_through, t)
    COMMIT; return closed_through
  on SQLSTATE 55P03 (busy):
    the transaction is rolled back by Laravel; never touched again
  READ:
    new statement, outside any transaction:
    SELECT closed_through …                                    (READ COMMITTED: the last committed value)
    return it (null = nothing closed yet)
```

**Why the answer is always safe:**
- **Only committed values are returned:** the busy path reads in a new statement after the rollback.
- **A stored value is never contradicted:** every append refuses `t ≤ closed_through` under the row lock (D-2 step 6). Every fact with `recordedAt ≤` a committed `closed_through` was committed before it.
- **A stale value can only cause refusal:** if τ exceeds it, C2 refuses `BOUNDARY_NOT_CLOSED`. It never yields a false complete answer.
- **Two closers:** the second is busy and reads a committed value; both values are valid.

### D-5 C1 row: written through the repository's connection (refinement B)

**The shared `OutboxWriter` is not used (F-5), so that the fact, the clock update and the event are atomic on the same connection.** The row matches F-9 exactly:

| Column | Value | Why |
|---|---|---|
| `id` | new UUID v4 | PK (Eloquent `HasUuids` equivalent) |
| `event_id` | new UUID v4 | the relay's idempotency key; unique |
| `organisation_id` | the tenant's UUID | tenant isolation; the relay envelope |
| `aggregate_type` | `Membership` | — |
| **`aggregate_id`** | **UUID v5 (fixed Membership namespace, `organisationId + ":" + membershipId`)** | **the column is `uuid`, but `MemberId` is free text (F-12).** Deterministic per membership; **the true `membershipId` travels in the payload** |
| `event_type` | `MembershipCoverageResolved` | the hydrator key |
| `payload` | `{eventId, organisationId, membershipId, personRef, seq, coverage[], inForce[], suspended[], recordedAt (µs), schemaVersion: 2}`; **intervals as calendar dates `[from, to)`, `to` null when open-ended** (EP-Q6 = T1, `DateOrdinal`) | CR-01 C1 + CR-02; spec K-6 |
| `status` | `pending` | the relay picks it up |
| `attempts`, `available_at`, `created_at` | column defaults | as other writers |
| `correlation_id`, `causation_id` | `null` | nullable by design ("chain start"); the command key is not a UUID |

**Atomicity and no duplication:**
- the row is inserted in the append transaction (D-2 step 10);
- **rollback leaves no fact, no clock change and no event;**
- a replayed command answers from its key at step 1 and writes nothing;
- the relay delivers at least once, and consumers order by `(membershipId, seq)` (CR-01 C1 rules).

### D-6 C1 hydrator (refinement B)
- `MembershipCoverageResolvedHydrator` (Membership Infrastructure) is registered in `MembershipServiceProvider` beside `FeePaidHydrator`. **Membership is the one hydration authority for its event.**
- It hydrates to the Published Language event object.
- **With no listener yet, the relay marks it `completed`. That is harmless:** the ledger is not live, and Organisation's first build rebuilds from C2 at `τ = closedThrough` (spec §7).

### D-7 C2 procedure
1. tenant check;
2. `MembershipSourceOfTruth` → `SOURCE_NOT_LEDGER` (the default refuses);
3. **`closeOrRead` (D-4), committed;**
4. τ > the returned value (or none) → `BOUNDARY_NOT_CLOSED` with it;
5. **then, in new statements,** load facts, `knownAt(τ)`; per key `{seq_τ, coverage, inForce, suspended}`.

- An unknown `personRef` → empty, `seq` 0. An unknown `membershipId` → error.
- **Batch limit 1 000** [TU].
- **Never partial.**

### D-8 Where "now" comes from
- **A caller that wants a current answer uses the boundary C2 returns (or a `currentBoundary()` call, the same `closeOrRead`) as its τ.** It does not use the application clock.
- An application clock ahead of the database clock would otherwise be refused every time.
- **Units (K-6):** `recordedAt`, τ and `closedThrough` are instants in microseconds. **Coverage, `inForce` and `suspended` are calendar dates `[from, to)`** (EP-Q6 = T1: `TimePoint` = days since 1970-01-01, `DateOrdinal`); `to` null = open-ended.

### D-9 Tests only on the isolated cluster
`membership_pg_isolated` through the PBDIGIT-324 guard. **No SQLite, no development database.**

### D-10 Migration and backfill, safe per organisation database (refinement C)
- **Table:** `org_membership_ledger_clocks(organisation_id uuid PRIMARY KEY, last_recorded_at timestamptz NULL, closed_through timestamptz NULL)`. **No foreign key to `organisations`**, so the table works unchanged in a per-organisation database, where the central registry is absent.
- **Backfill (same migration):** `INSERT … SELECT organisation_id, max(recorded_at), NULL FROM org_membership_facts GROUP BY organisation_id ON CONFLICT DO NOTHING`.
  - It reads only the database it runs in, so it is correct per database and idempotent.
  - `closed_through` starts null; the first close sets it.
- **`down()`** drops the table. **It is reversible only together with the code** (stated in the guide).

## 6. Tests first (RED before GREEN) — isolated cluster unless "unit"

| # | Test | Invariant | Proof |
|---|---|---|---|
| 1 | kernel properties:<br>- coverage = {t : some lifetime ∋ t};<br>- INV-COV;<br>- prefix (INV-14);<br>- Annul and Void restore; Correct moves;<br>- `inForce` = {t : `isMember(t)`};<br>- suspension changes neither (vacuous until slice 5) | spec §3, §11 | unit |
| 2 | stored `recordedAt` = its reading | no adjustment | LC-1 |
| 3 | scripted regression → refused, retried; never ≤ `closed_through` | D-209 | LC-2 |
| 4 | **two appends and a reader interleaved** (real clock); a reported `closedThrough` never contradicted; **a reader between a writer's lock and its commit**; **REPEATABLE READ misuse fails closed** | A1–A3 | LC-3 |
| 5 | no application clock reaches `recorded_at` / `closed_through` | one clock | LC-5 |
| 6 | closing step: no advance when `closed_through < t < last_recorded_at`, **stored value unchanged**; may advance at `t = last_recorded_at` | regression guard | LC-6 |
| 7 | same-membership equal reading → refused, retried | V-5 | KC1-1 |
| 8 | different memberships share a reading → allowed | D-209 | KC1-2 |
| 9 | refusal → fresh attempt (re-load, re-decide); exhaustion → `RecordTimeUnavailable`; no duplicate acceptance | R-1a-2, IDEM | G1d-1 |
| **10** | **NOWAIT busy:** a second session holds the row lock; `closeOrRead` returns the last **committed** value; the aborted transaction is not reused (a following statement on the same connection succeeds); **an uncommitted advance by the holder is never returned** | refinement A | new |
| **11** | **stale boundary is safe:** with τ beyond a stale value → `BOUNDARY_NOT_CLOSED`; with τ within it → a complete answer equal to one computed after all writers commit | complete or refused | new |
| 12 | fact, clock and C1 atomic (forced rollback leaves none); C1 only on change; **C1 written on the repository's connection** (a test connection distinct from the default) | atomicity | spec §8 |
| **13** | **the C1 row satisfies F-9:**<br>- every column type and constraint;<br>- `aggregate_id` the UUID v5 for a **non-UUID `MemberId`**;<br>- payload exactly the D-5 fields | outbox contract | new |
| **14** | **relay round trip:** `OutboxEventProcessor::processEvent` on a C1 row → hydrated, dispatched, `completed`; **not dead-lettered** | relay compatibility | new |
| **15** | **replay:** the same command twice → one fact, one C1 row | no duplicate | new |
| 16 | **two organisations on one cluster:** independent clock rows, boundaries and C1 rows; **no cross-organisation effect under concurrent load** | isolation | spec §8; K-4 |
| **17** | **migration:**<br>- the backfill on a database holding facts of two organisations → one row each with the right maximum;<br>- re-run → no change;<br>- `down` then `up` → same result | refinement C | new |
| 18 | C2:<br>- `BOUNDARY_NOT_CLOSED`;<br>- `SOURCE_NOT_LEDGER` (default port);<br>- cross-tenant refused;<br>- the **CR-01 §7 scenario** (τ before / after End, Annul, Correct);<br>- `personRefs[]`; unknown person → empty | spec §8 | — |
| 19 | the seven existing suites pass with the reader swapped in; **assertions unchanged** | regression | — |

## 7. Task checklist (each RED → GREEN → refactor; one commit per group, `(PBDIGIT-343)`)

- [x] 0. Create PBDIGIT-343; open the developer-guide step file.
- [x] 1. Test 1 → kernel functions.
- [x] 2. Test 17 → migration and backfill.
- [x] 3. Tests 2, 5, 19 → `LedgerClockReader`; swap the clock in the repository and the seven test classes.
- [x] 4. Tests 3, 7, 8, 12, 13, 15, 16 → append rewrite (D-2), C1 writer (D-5).
- [x] 5. Test 9 → `RecordTimeRefused`, loop mapping, `RecordTimeUnavailable` (D-3).
- [x] 6. Tests 4, 6, 10, 11 → `LedgerClockCloser` (D-4).
- [x] 7. Test 14 → hydrator and registration (D-6).
- [ ] 8. Test 18 → C2 service, DTOs, source port (D-7, D-8).
- [ ] 9. Architecture tests; full Membership PostgreSQL and unit suites; diff review.
- [ ] 10. Developer-guide index final check; evidence to the Principal; **acceptance by the Principal.**
- **Every task ships its developer guide in the same step** (Principal, 2026-11-12; CLAUDE.md Definition of Done): guides 15–19 cover tasks 1–5.

## 8. Progress

- 2026-11-12: revision 1; Principal DDD review.
- 2026-11-12: revision 2 (refinements A, B, C). **Approved; PBDIGIT-343 created; implementation started (task 0 done).**
- 2026-11-12, **deviation noted (no change of direction):** revision 2 gave the C1 payload a single unit "µs". **Verified in code:** valid time is a calendar date (EP-Q6 = T1, `DateOrdinal.php`). D-5 and D-8 now state both units. Applies spec K-6 as written.
- 2026-11-12: **tasks 0–3 done**:
  - `2bbd9b1b9` docs;
  - `36f8304a6` kernel functions (unit suite 204 green);
  - `2b6302e3b` clock table and backfill;
  - `78af8627a` `LedgerClockReader` (Infrastructure only; Domain and Application Laravel-free, hexagonal);
  - PostgreSQL suite 102 green; Membership architecture 105 green.
- **Note:** the wider `tests/Unit/Contexts/Membership` run shows 55 errors and 1 failure, **all in legacy Committee / Fee / Member code, none in the ledger.** They are pre-existing and outside this slice (first: an `AssignMemberToCommitteeHandler` constructor mismatch). Recommended as a separate backlog item.
- 2026-11-12, **deviation 2 (Principal decision, option B):** test 19 assumed "assertions unchanged". **Three approved tests assert the COR-4 Q-2 +1 µs bump that D-209 forbids and D-215 (K-C1) replaces with refusal:**
  - `MemberRepositoryContract` (2 tests, shared by the PostgreSQL adapter and the in-memory double);
  - `PostgresMemberRepositoryInvariantsTest` T6.

  **Decided (B):**
  - the in-memory double (`tests/Doubles/InMemoryMemberRepository.php`) follows the same rule as the database: refuse a non-advancing reading, never bump;
  - the three assertions become "refused, nothing stored, a later reading is stored exactly";
  - the four application test files with a frozen clock get an advancing one.

  **V-5 (strictly increasing per membership) stays asserted.** Port docblock updated (R-1a-3 unchanged; refusal added).
- 2026-11-12: **task 4 done** (`79252d9ac`):
  - the D-209 append; the atomic C1 row on the repository's connection; the lock order proved with forked processes.
  - **RED:** 9 of the 10 new PostgreSQL tests fail on the old code (KC1-2 passes there, correctly).
  - **GREEN:** PostgreSQL suite 112; kernel 204; C1 unit 4; Membership architecture 105.
  - **Known RED for task 5:** `AppendOutcomeMappingTest` (2) requires the new outcome's mapping in `DecideAndAppend`.
- 2026-11-12: **task 5 done** (`ad4d5275b`):
  - a `RecordTimeRefused` is a fresh attempt (re-load, re-decide, fresh reading); exhaustion → `RecordTimeUnavailable`; otherwise the last attempt's reason.
  - **RED:** the existing mapping guard, 4 unit and 3 PostgreSQL tests.
  - **GREEN:** application 80; kernel 204; infrastructure 65; Membership architecture 105; PostgreSQL suite 115.
- 2026-11-12, **deviation 3 (Principal instruction):** developer guides are written **per step**, not only at task 10. Guides 15–19 written for tasks 1–5. Guide 14 carries a pointer note: its `max(clock, last + 1 µs)` is superseded.
- 2026-11-12: **task 6 done** (`afad064ec`, guide 20):
  - `LedgerClockCloser::closeOrRead`: free / busy (55P03, no reuse, committed value) / regression / hook rollback; refused in a transaction.
  - **Found and fixed during GREEN:** the unconditional `INSERT … ON CONFLICT` made the closer wait behind a writer's uncommitted update; it now ensures the row only after a non-blocking read.
  - **RED 9/9; GREEN:** PostgreSQL suite 124 (race test 4 of 4 runs); application 80; infrastructure 65; Membership architecture 105.
  - **Test 11's C2 half** (τ beyond a stale boundary → `BOUNDARY_NOT_CLOSED`) belongs to task 8.
- 2026-11-12, **deviation 4 (Principal review of task 6):**
  - **The defect:** the append created the clock row inside its transaction; on a first append, a closer's `INSERT … ON CONFLICT` waited as long as that append. The closer also never bounded its insert.
  - **The fix** (`9477113e1`): `LedgerClockRow::ensure` (plain read first; insert in its own committed statement; the closer bounded by `lock_timeout` 200 ms, fail closed). The append ensures before its transaction (D-2 step 3 moved out; meaning unchanged: the row is empty and idempotent).
  - **The F-5 test** states its invariant precisely (first statement inside the transaction).
  - **RED:** the closer hit the 3 s guard; the append created at level 1.
  - **GREEN:** PostgreSQL 130 (97 s); race group 13 tests, 3 of 3 runs (27–31 s); application 80; infrastructure 65; kernel 204; architecture 105.
  - **Guides:** 20 and 18 updated; new `developer_guide/database/01_postgresql_lock_waits.md`.
- 2026-11-12, **task 6 gate 1 (Principal review):**
  - the committed code was re-read and matches the handover;
  - added the assertion that the connection is usable after the lock timeout, and that `SET LOCAL` does not leak (`d9b2b33b1`);
  - **task 6 recorded complete.**
- 2026-11-12: **the race test's non-vacuity was not guaranteed:** one run failed "the boundary advanced"; the safety property held. Readers now close until the writers finish, and a mid-run boundary is required (`6305f4366`); 5 of 5 runs green.
- 2026-11-12: **task 7 done** (`6fd1128c5`, guide 21):
  - the Published Language event `MembershipCoverageResolved` and `CoverageInterval` (Application, pure);
  - `MembershipCoverageResolvedHydrator` (Infrastructure), registered in `MembershipServiceProvider`;
  - the event type added to `EventRegistryCompletenessTest`; the relay unchanged.
  - **RED:** guard, unit, round trip. **GREEN:** PostgreSQL 131 (86 s); hydrator 9; architecture as before.
- **Pre-existing, outside the story** (verified on a clean checkout of `a53ba8ea8`): 4 failures in the full `tests/Architecture` suite (Election ×2, GovernanceRuntime, Phase_C25). Also the 55 errors and 1 failure in legacy Membership unit tests.

## 9. Risks

| # | Risk | Mitigation |
|---|---|---|
| R-1 | appends of one organisation serialise on the clock row | low volume; measured in test 4 |
| R-2 | NOWAIT contention: the boundary lags, C2 refuses | fail closed (D-209 §4); no retry added [TU] |
| R-3 | a host clock regression blocks appends | flag (D-3); monitoring [EXT] C-04 before launch |
| R-4 | the relay reads the default connection | correct with one database; **slice 2 (§12)** |
| R-5 | the scripted clock in production | architecture test (D-1) |
| R-6 | adapted tests weakened | only the clock source changes; assertions unchanged (test 19) |
| R-7 | backfill on existing facts | per-database, idempotent (test 17); the ledger is not live |
| R-8 | the isolated cluster unavailable | the guard refuses; no fallback |
| **R-9** | **C1 events processed with no listener before Organisation exists** | harmless: Organisation rebuilds from C2 (D-6) |
| **R-10** | **a caller uses the application clock for τ** | D-8; documented in the guide; a C2 test with τ ahead of the database clock → refused |

## 10. Open questions

**For the Principal:**
- **only approval of this plan for implementation.**

**Technical choices, documented and tested** [TU]:
- batch limit 1 000;
- no NOWAIT retry;
- the regression flag is a log warning;
- `aggregate_id` UUID v5;
- `correlation_id` / `causation_id` null.

**No business decision is open.**

## 11. Geography

- **D-147 is preserved.** Slices 1–7 contain no Reference Geography work.
- **Organisation units (slice 3) are not official geographic units** (baseline v1.1 §4a).

## 12. Slice 1 / slice 2 boundary (refinement C)

| Concern | Slice 1 | What slice 2 must do |
|---|---|---|
| **clock row** | keyed by `organisation_id`; **no foreign key** (D-10); one row per organisation | **nothing.** In a per-organisation database the table holds one row |
| **migration** | idempotent, per-database backfill (test 17) | **run the Membership migrations in each organisation database** through the multi-database design's migration process (`schemaState: migrating`, D-178, D-193) |
| **connections** | every repository, closer and C2 statement uses **the connection it is given**; nothing reads the default connection for Membership data | **supply each organisation's connection** from the Platform locator |
| **isolation** | proved between two organisations on one cluster (test 16) | prove it across databases (ISO-1 … ISO-11) |
| **outbox relay** | unchanged; reads the default connection | **iterate organisation connections** (or one relay per organisation database), with `OutboxEvent` bound to that connection |
| **existing foreign key** `org_memberships.organisation_id → organisations` (F-13) | unchanged (not in scope) | **replace it in the organisation-database schema:** the central registry is not there |
| **source flag** | port, refuses all | set "ledger" at joining (D-160); bind the real adapter |

**Why slice 1 does not depend on slice 2:** every Membership operation already takes its connection explicitly. Slice 1 adds no new global connection, no central read and no routing assumption.

## 13. Build order (approved, 2026-11-12)

| Slice | Content |
|---|---|
| **1** | **this plan** |
| 2 | Platform routing and isolation; gate C-1 flag; per-organisation relay; organisation-database schema (F-13); founding records and registrar (D-148, D-193 M-3) |
| 3 | Organisation structure |
| 4 | Governance (AuthorityCheck, G-Q1, K-7, T-1) |
| 5 | Membership lifecycle with real authority + Organisation belonging, T-2, T-4 |
| 6 | Forum |
| 7 | Newsletter |

**Traceability:**
- D-160, D-161, D-162, D-209, D-210, D-214, D-215, D-216;
- IR-R1C-01, -R1, -R2;
- blueprint rev 4 (`c08ebc35…`);
- B-05 evidence;
- Principal DDD review of slice 1 (2026-11-12);
- supersedes §3 of `20261110-1751-minimum-package-build-and-organisation-structure-slice-1-plan.md`.
