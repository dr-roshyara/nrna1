# 17: G-4e, the publication ledger under one lock (PBDIGIT-346)

**Design:**
- v3.11 §5.1 (before-images stored with the claim), §5.3 (stale), §6 (the publication transaction, the clock rule, close through τ), §8 (the roster shares the lock);
- completion §4;
- the G-4 interpretation B-1 to B-7.

**Migration:** `2026_11_13_140000_freeze_refgeo_claims_with_before_images.php`. **Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g4e/`.

## In business terms

**A change becomes official only if, at the very moment of publication, everything still holds:**
- the person publishing is appointed;
- the right independent second person approved exactly this version;
- nobody involved has since been found to be the same person, or has declared an interest in a disputed matter;
- nothing it relied on has changed in the meantime.

**If anything changed, publication is refused or the change is sent back as stale.**

**The guarantee:** while publication checks and records, **nobody can change the roster.** Roster changes wait their turn, and publications wait for roster changes in progress. A change is published once only. A failure leaves nothing behind. Nobody sees a publication before it is final. Timestamps never go backwards, and a period declared complete stays complete.

## How publication and roster changes are serialised

**Both take the same lock, in the same order:** their own transaction, `SET TRANSACTION ISOLATION LEVEL READ COMMITTED`, then `SELECT … FROM refgeo_sequence WHERE id = 1 FOR UPDATE`.
- Publication (`PostgresPublicationLedger::withLock`) and every roster write (`PostgresStewardRosterStore::writeUnderLock`, G-4c) both do this.
- **The lock is held to commit.** A waiter re-reads the committed row.
- **There is one lock and one order,** so no deadlock is possible between them.

## How governance is re-evaluated under the lock

`Application\PublishRevision::publish(id)`. Every decision is the domain's; the ledger only supplies locked reads and the append:
1. **Already published** (this content version) → `AlreadyPublished`; nothing written.
2. **The acting person** (`ActingPerson`) must be appointed for the matter at the roster's current version (D-84 "a steward assesses and publishes") → else `Refused [NotAppointed]`.
3. **`PublicationGovernance::mayPublish(revision, roster, roster.version())`.** This covers:
   - eras A and B;
   - the second person, under `excluded(r)` and E(v), approving this content version and appointed when approving;
   - conflict declarations;
   - D-230.

   If refused → `Refused [reasons]`; nothing written.
4. **Staleness:** `validation.dependencies.firstInvalidating(publishedAfter(p₀))`, using the frozen before-images. If stale → the revision is marked and kept `Stale` → `Stale`; nothing published.
5. **Append** → `Published(position, decision)`.

## How the clock and closing a period are enforced

- **`append`:** reads `c = clock_timestamp()` once. If `c ≤ W` or `c <` the last `recorded_at`, it refuses with `ClockNotPastBoundary`, **never adjusted**, and the whole transaction rolls back. Otherwise it inserts at `last_position + 1` with `recorded_at = c`, the decision's roster version, rule and independence statement. The G-4a trigger re-checks position and clock as a backstop.
- **`closeThrough(τ | null)`:**
  - a τ at or below W is confirmed without the lock;
  - otherwise it takes the lock with `FOR UPDATE NOWAIT` (if busy, `BoundaryNotClosed`) and reads the clock;
  - a future τ gives `BoundaryNotClosed`;
  - otherwise W is set to τ (or to now), only forward.

## Frozen before-images

Each submitted version's claims are frozen in `refgeo_claims` **with their before-image** (`before_image`, NOT NULL), per v3.11 §5.1. Staleness reads them for claims published after p₀. **A test proves the stored before-image equals the belief just before publication.**

## Pitfalls

- **Never publish except through `PublishRevision`.** It's the only path that re-checks governance under the lock (the architecture test is G-4f).
- **A published revision must not be re-prepared or withdrawn.** The `Revision` aggregate doesn't know it's published, so the application services must refuse it (G-4f).
- **Never call `withLock` inside another transaction.** It refuses: the lock must be its transaction's first act.

## Testing (isolated cluster)

**`PostgresPublicationLedgerTest`** (14 tests):
- published once and recorded exactly;
- governance re-evaluated under the lock: a merge, or an interest declared after approval, refuses;
- an approval of version 1 doesn't authorise version 2;
- only an appointed person may publish;
- stale → marked and kept;
- **clock regression refused**, then published once past it;
- **a failure before commit leaves nothing**, and the position is reused;
- **invisible until commit** (another connection, inside the transaction);
- close through τ;
- the before-image equals the belief just before publication;
- **a race:** a publisher against a process recording merges and declarations. Every publication is valid at its recorded roster version, and no roster entry crosses it in commit order;
- **deterministic:** a publication waits for a roster write in progress, then sees it;
- **deterministic:** a roster write waits for a publication in progress, and comes after it;
- **a race:** 3 publishers of one revision publish it exactly once.

**RED:** 12 errors (missing service and ledger). The before-image migration and repository change were written before RED; T10 covers them.

**First GREEN:** one error, **my SQL** (an untyped parameter).

**GREEN:** 38 PostgreSQL tests.

**Mutation check on PostgreSQL:** **7 of 7 killed, each by its intended test:**
- L1: no lock. Killed by the **deterministic** wait test.
- L2: no governance re-check.
- L3: staleness ignored.
- L4: no already-published check.
- L5: no clock rule. The trigger backstop still refused the write.
- L6: no actor check.
- L7: no transaction.

**Regression:**
- 212 database-free tests pass;
- every cluster suite passes (192 tests);
- the architecture suite shows the same 4 pre-Geography failures by name: ElectionLifecycleStateConsistencyTest (voting-active state), ElectionStateMachineConsistencyTest (election clock service), GovernanceRuntime ConstitutionalBoundaryTest (code creation), Phase_C25 (hard-coded denial reason).

**Traceability:** PBDIGIT-346 G-4e · v3.11 §5.1, §5.3, §6, §8 · completion §4 · G-4 interpretation B-1 to B-7.
