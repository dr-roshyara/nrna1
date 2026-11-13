# 15: G-4c, the roster in PostgreSQL and the roster services (PBDIGIT-346)

**Design:**
- v3.11 §8 ("Roster and publication ordering");
- D-84, D-84a;
- G-3 acceptance item 3;
- the G-4 interpretation §2, §4, §5, B-6.

**Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g4c/`.

## In business terms

- **Who may act for the atlas is now stored safely.** Every change to the roster waits its turn behind the same lock that publication will use, so a roster change can never slip in between the final check and a publication (completed in G-4e).
- **Only the platform owner may appoint or end stewards and appoint single-matter reviewers.** Until you decide who the platform owner is, **nobody can**: the production setting refuses everyone.
- **People declare their own conflicts of interest.**
- **Identity's merge notices are applied once,** and a repeated notice changes nothing.

## Code

| Element | Layer | Role |
|---|---|---|
| `Ports\StewardRosterStore` | Application | `current()`; `writeUnderLock(Closure)`, all or nothing |
| `Ports\PlatformOwnerAuthority` | Application | the D-84 boundary: `isPlatformOwner(person)` |
| `StewardRosterService` | Application | `appointSteward`, `endSteward`, `appointMatterReviewer` (the acting person must be the platform owner, else `PlatformOwnerRequired`, and nothing is written); `declareConflict(matter, interest)` for the acting person only. **The acting person comes from `ActingPerson`, never from the caller** |
| `IdentityNoticeConsumer` | Application | `merged(mergeId, persons)` and `reversed(mergeId)`, with no acting person. A duplicate returns `null` |
| `PostgresStewardRosterStore` | Infrastructure | its own transaction, `SET TRANSACTION ISOLATION LEVEL READ COMMITTED`, then **`refgeo_sequence FOR UPDATE`** (the one publication lock), then rebuild with `fromEntries`, run the write, insert the new entries (the G-4a trigger requires the next version), commit |
| `UnestablishedPlatformOwnerAuthority` | Infrastructure | **the production binding: refuses everyone** (G-3 acceptance item 3). It assumes no Product Owner, organisation owner or tenant administrator |

**Binding note:** the services are not yet bound in a service provider. That's G-4f, with the publication services. Bind `PlatformOwnerAuthority` to `UnestablishedPlatformOwnerAuthority`. **Never bind a test fixture in production.**

## Pitfalls

- **Call `writeUnderLock` outside any transaction.** It refuses otherwise (`LogicException`), because the isolation level and the lock must be its first statements.
- **Never insert into `refgeo_roster_entries` from anywhere else.** The trigger would refuse anything but the next version, and the architecture test (G-4f) will look for other writers.

## Testing

**`RosterServicesTest` (6, no database):**
- the platform owner may act;
- anyone else is refused, and nothing is written;
- the production authority refuses everyone;
- with no acting person, nothing is written;
- a declaration is for the acting person only;
- notices are applied once.

**`PostgresStewardRosterStoreTest` (5, isolated cluster):**
- the next versions, and an exact reload;
- a refused write writes nothing;
- a notice is applied once;
- a write inside an open transaction is refused;
- **4 forked processes × 5 writes:** no lost write, no gap, and the history replays.

**RED:**
- application: 6 errors (missing port);
- PostgreSQL: 5 failures (missing store).

**GREEN:** 6 application tests, and 11 PostgreSQL tests with G-4a.

**Mutation check on PostgreSQL** (`mutation-check-pg.php`): **2 of 2 killed**, both by the race test:
- **S1, the lock removed:** the G-4a trigger refused the colliding write ("version 5 is not the next version 6"). So the database guard is a real backstop.
- **S2, no transaction:** the lock doesn't hold.

**Not proven:** atomicity of a multi-entry write that fails part-way through the inserts. A refused *domain* write writes nothing even without the transaction, because inserts happen only after the write succeeds.

**Regression:**
- 209 database-free tests pass, with the Identity-import architecture rule;
- every cluster suite passes (165 tests);
- the architecture suite shows the same 4 pre-Geography failures.

**Traceability:** PBDIGIT-346 G-4c · v3.11 §8 · D-84, D-84a · G-3 acceptance item 3.
