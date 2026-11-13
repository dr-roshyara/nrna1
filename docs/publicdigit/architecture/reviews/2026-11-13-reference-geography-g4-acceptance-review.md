# Reference Geography G-4: acceptance package for the Principal (PBDIGIT-346), final

**Date:** 2026-11-13 · **Author:** Session A (engineering supplies evidence; it does not accept its own work) · **Status:** for the Principal's decision.

**Plan:** `2026-11-13-reference-geography-g4-implementation-interpretation.md` (B-1 to B-9).
**Sources:** v3.11 §5.1, §5.3, §6 (PO-1 to PO-8), §8, §15; completion §4, §5; G-3 acceptance.
**Guides:** `developer_guide/reference_geography/13` to `19`; `developer_guide/database/02`.
**Final evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g4g/`.

## 1. What G-4 delivers, in business terms

**The official atlas is now stored, and changed only through one controlled door:**
- **The system knows who is acting** from the signed-in person; nobody can act as someone else.
- **Only an appointed person may act,** and an independent second person must approve exactly the version published.
- **At the moment of publication,** under one lock that every roster change also waits for, everything is checked again: appointments, conflicts of interest, Identity's knowledge of who is the same person, and whether anything relied on has changed.
- **When people act at the same moment, the result is always as if they had acted one after another.** Nothing is lost, duplicated or published on an out-of-date view.
- **Published history can't be changed, re-opened or withdrawn;** a correction is a new change.
- **The record has no gaps,** a timestamp never goes backwards, and a period declared complete stays complete.
- **The platform owner's power to appoint stewards stays switched off** until you decide who the platform owner is.

## 2. Slices and commits

| Slice | Commit | What |
|---|---|---|
| G-4a | `778fde018` | stream schema; database guards |
| G-4b | `62d47bdf2` | exact rebuild paths; tampering refused |
| G-4c | `4716a7432` | the roster under the lock; roster services; platform owner closed |
| kernel fix | `0b2ce6dae` | several non-overlapping claims of a slot in one publication |
| G-4d | `a0af88641` | revision repository; PostgreSQL atlas reader |
| G-4e | `226c1e4d7` | publication ledger under one lock; governance re-checked; staleness; clock; close |
| G-4f | `7033cad82` | use cases; trusted actor; production wiring; published revisions closed; no-bypass guard |
| G-4g | this commit | the concurrency matrix; verified mutation evidence; test-harness corrections |

## 3. The protocol, and what proves it

**Lock order** (the same for every writer, so no deadlock):
- publication: `refgeo_sequence` row (`FOR UPDATE`), then the revision row;
- roster writes: the `refgeo_sequence` row;
- revision saves: the revision row only;
- a close: `refgeo_sequence` with `NOWAIT`.

**Every one is its own READ COMMITTED transaction.**

| # | Invariant | Proved by |
|---|---|---|
| B-1 | at most once; nothing partial | G-4e (once; failure before commit; position reused); G-4a unique (revision, version); the G-4e double-publisher race |
| B-2 | published claims and revisions never change | G-4a; G-4f (application, repository, database trigger); G-4f and G-4g S1/S2 races |
| B-3 / PO-4 | gap-free, in order | G-4a (the insert advances the counter); **G-4g P1**; G-4g stress |
| B-4 / PO-6 | the clock read under the lock, never adjusted | G-4e (regression refused); **G-4g C2** |
| B-5 / PO-1 | W: complete below it | G-4a (only forward, never future); **G-4g C1** |
| B-6 | one lock for publication and the roster | **G-4g R1–R7, both orderings, deterministic**; G-4e races |
| B-7 | staleness under the lock | G-4e (frozen before-images equal the belief just before publication) |
| B-8 / PO-2 | single write path | G-4a triggers; G-4f no-bypass guard (fixtures and a real-tree probe) |
| B-9 | governance in the application; platform owner closed | G-4f (use cases; reflection: no person parameter as actor; production wiring) |
| PO-3 | recorded reads only | G-2 purity guard; G-4d (validation through PostgreSQL = through the reference atlas) |
| — | notice idempotency | **G7, three independent layers** (§4) |
| — | no deadlock, no lost update | the lock order above; G-4g stress (no error; no gap; every publication valid at its roster version; no roster entry crossing it) |

## 4. Mutation evidence: each kill verified BY REASON

**The rule applied:** a mutant counts as killed only if its intended test fails **on its assertion about the invariant**. A failure from a timeout, a broken helper, fixture setup or the environment is not a kill. Every run's failure messages were classified, and the setup and infrastructure markers were counted. **No source file was modified;** each mutant is a loaded copy.

| Set | Result (on the final code) | Notes |
|---|---|---|
| G-3 governance domain | 14 / 14 genuine | re-run in G-4g |
| G-4b rebuild paths | 5 / 5 genuine | re-run in G-4g |
| G-4c roster store (PostgreSQL) | 2 / 2 genuine | S1 by the roster race and lock-wait tests; S2 also by the part-way-failure atomicity test |
| G-4d repository, atlas (PostgreSQL) | 5 / 5 genuine | D5 by `…claims_are_frozen…`; the ledger's own "no frozen claims" refusal is a second safeguard |
| G-4e publication (PostgreSQL) | 7 / 7 genuine | L5: the G-4a trigger backstop refused the write when the clock rule was removed |
| G-4f application · persistence | 4 / 4 · 2 / 2 genuine | — |
| **G-4g concurrency** | **8 / 8 genuine** | the locks (publication, roster, revision row, close); governance at a stale roster version; the timestamp adjusted; notice idempotency; the roster read before the lock |

**Two runs that were not valid kills, found and corrected:**
1. **G4 (governance at the previous roster version) was first "killed" by a fixture setup failure** (`Revision: invalid`, 20 errors): the matrix fixture bootstrapped through the very code under mutation.
   - **All three Geography fixtures now share `CompetenceBootstrap`,** which publishes the bootstrap claim directly (SQL, under the same lock and guards).
   - The ledger and closure tests had still bootstrapped through `PublishRevision`. They were protected only by test order (the matrix ran first); that dependency is gone.
   - **G4 re-run: 15 assertion failures, 0 errors.** Genuine.
2. **The first G-4d to G-4f re-run hung for 22 minutes on D5:** two older test helpers waited forever for a background process that had failed. The helpers were fixed: a failed holder now signals and reports, and the wait has a 60-second deadline. The re-run completed, and D5 is a genuine kill. Evidence kept.

**Assertion kills and database protection are recorded separately.**
- G1 (no publication lock) first showed 1 *uncaught* error: the G-4a trigger refusing a colliding position in test P1.
- **P1 now asserts the requirement explicitly:** "the second publication must WAIT for the first, not collide with it". The database's refusal is reported in that message as separate evidence.
- **G1 re-run: 12 assertion failures, 0 errors.**
- Database-level protection shown by mutants: G1 (append trigger), L5 (clock trigger), P2 (closed-revision trigger), G7 (unique notice). **In each of these, an assertion-level kill also exists.**

**G7, notice idempotency, at three independent layers:**
- **domain:** `MergeNoticeTest::…replay_writes_nothing`;
- **application:** `RosterServicesTest::…consumed_once`. Both fail without a database when the domain check is removed (`g7-domain-level-mutation.out`);
- **database:** the unique constraint refuses a duplicate (G-4g G7 on PostgreSQL).

## 5. Regression (final)

| Run | Result |
|---|---|
| **Full isolated cluster** (Membership, Platform, Identity, Reference Geography), on the final code | **220 tests, 16,516 assertions, exit 0, none skipped;** 1 deprecation (pre-existing, in every earlier run). Evidence: `isolated-cluster-all/` (the run at `225e2ecf8` is kept as `isolated-cluster-all-at-225e2ecf8/`: 220 tests) |
| Database-free (Geography and Identity, with the purity, Identity-import and no-bypass guards), on the final code | **221 tests, 1,420 assertions, exit 0** (`db-free-all.txt`). **The test count is fixed. The assertion count varies by run** (1,389 to 1,420 seen), because randomised property tests (e.g. SlotKeyCollisionTest) make a varying number of assertions; `db-free-two-runs.txt` shows two consecutive runs |
| Architecture suite | 318 tests, **4 failures, identical by name to the pre-Geography slice 1 baseline and by message to the earlier runs;** 1 skipped (pre-existing) |

**The four known failures,** all outside Geography and not touched:
1. ElectionLifecycleStateConsistencyTest (voting-active state);
2. ElectionStateMachineConsistencyTest (election clock service);
3. GovernanceRuntime ConstitutionalBoundaryTest (code creation);
4. Phase_C25 (hard-coded "not eligible" denial reason).

## 6. Strict-TDD record and deviations

- **Genuine RED to GREEN cycles:** G-4a, G-4b, G-4c, G-4d (and the kernel fix), G-4e and G-4f, each with RED output kept.
- **Verification tests (passed on their first run; not REDs):** the 22 G-4g matrix tests, and some guard and atomicity tests (G-4c obligation, G-4f wait tests). **Their value is proven by mutation (§4).**
- **Deviations, all recorded in their slices:**
  1. G-4a: a vacuous first RED; the helper was tightened.
  2. G-4e: the before-image migration was written before RED.
  3. G-4f: a 10-line adapter was written before its test.
  4. G-4f: the connection-exhaustion harness defect.
  5. **G-4g:** the test-helper hang.
  6. **G-4g:** G4's first run was killed by fixture setup.
  7. **Evidence clarification:** a wrong assertion name in the P1 change (`assertNotIsString`) errored in the first run (kept as `*-1-assertion-name-defect*`), then was corrected.

  Each was found, kept as evidence and corrected.
- **No test was weakened or deleted.** Changed earlier assertions follow Principal decisions (F-2, D-229), or make a test stronger (G-4g C1: a missing row is now an assertion, not a PHP error).

## 7. Migration rollout: what actually protects production

**Fact:**
- nothing in the repository deploys to production;
- CI migrates only throwaway PostgreSQL databases;
- **no migration checks its environment.**

**The "test databases only" release gate in the migration headers is a convention, not a technical control.** It is shared by 8 migrations: Membership, Identity and Geography. Anyone running `php artisan migrate` against production would apply them.

**Options, for your decision before any production rollout (none implemented):**

| Option | What it means | Consequence |
|---|---|---|
| A. Keep the convention | deployment discipline only | no code change; the risk stays with whoever runs migrations |
| B. Environment guard in each gated migration | it refuses to run unless the environment is testing, or an explicit approval flag is set | strong and simple; every gated migration must carry it; the flag becomes the approval act |
| C. A separate migration path | gated migrations live outside `database/migrations` and run only with `--path` | not applied by a plain `migrate`; easy to forget when rolling out |

## 8. Not proven by G-4, and still open

| Item | Status |
|---|---|
| **Who D-84's platform owner is** | **your decision;** production authority stays closed |
| **Migration rollout control** | **your decision** (§7) |
| D-230: automatic detection of reasonable doubt | open: human flags only; "competing" is undefined |
| G-0: mutation proof of its race test | open (Identity) |
| PO-5, query by time | G-5 |
| PO-8, Membership's timestamp barrier | for Membership's tactical work |
| cases B and E; D-90 notices; objections as judgment flags | G-6 |
| steward screens or API | not designed; none built; the no-bypass guard covers any future one |

## 9. Recommendation

**ACCEPT WITH EXPLICIT CONDITIONS:** G-4 as the Reference Geography persistence, publication, application and concurrency baseline. The conditions:
1. **Production use waits for your two decisions:** the platform owner, and the migration control (§7).
2. **The open items in §8 are carried forward.**

This is not a claim of operational readiness: no screens, no read models (G-5) and no data (G-8) yet.

**Your decision:** accept G-4 (with or without these conditions), or ask for a revision.

## 10. Principal decision (recorded 2026-11-13)

> **G-4 accepted with conditions, for continued development.** The G-4 implementation and evidence are accepted as the current development baseline of Reference Geography's persistence, publication, application and concurrency.
> - **No production steward appointment or Geography publication** until the Platform Owner authority is formally established.
> - **No production database migration** until an explicit, technically enforced migration-approval control is established.
>
> This is not acceptance of the whole Reference Geography context: G-5 to G-8, D-230 automatic detection and the G-0 race mutation proof remain outstanding.

The Principal's words: "I accept the recommendations written in the document". The decision text is from the Principal DDD review of 2026-11-13, which recommends this conditional acceptance.
