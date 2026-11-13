# 18: G-4f, the application use cases, production wiring and the no-bypass guard (PBDIGIT-346)

**Design:**
- v3.11 §8 (roles: the preparer is "any steward"), §15 (the single write path);
- D-84, D-225, D-228 (corrections are new revisions);
- G-3 acceptance item 3;
- the G-4 interpretation B-8, B-9.

**Migration:** `2026_11_13_150000_close_published_refgeo_revisions.php`. **Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-13-pbdigit346-g4f/`.

## In business terms

**Every change to the official atlas now goes through one controlled door:**
- the system itself works out who is acting, from the signed-in person, so **nobody can claim to be someone else**;
- that person must be appointed for the matter;
- submitting and approving follow the governance rules;
- publishing re-checks everything under the lock.

**Once a change is published, it is closed for good.** Nobody can re-open, withdraw or alter it through any path; a correction is a new change.

**An automatic check fails the build** if anyone later adds a screen, command, job or service that reaches around this door. A deliberate set of fake bypasses proves the check works.

## Entry points found

**There were none.** No controller, command, job, listener or route references the new context, and no code outside it touches its tables. Steward screens and APIs aren't designed, so **G-4f invents none.**
- It provides the use cases and their production wiring.
- The no-bypass guard covers every delivery path added later.

## The use cases (Application)

| Use case | Acts |
|---|---|
| `RevisionService` | `prepare`, `addClaim`, `removeClaim`, `attachEvidence`, `recordAssessment`, `flagJudgmentMatter`, `validate` (atlas at the committed position), `submit` (`maySubmit`), `approve` (`mayApprove`; the approval records the roster version), `reprepare`, `withdraw` |
| `PublishRevision` | publication (G-4e) |
| `StewardRosterService`, `IdentityNoticeConsumer` | roster writes (G-4c) |

**Every act:**
- takes its actor from **`ActingPerson`**. A reflection test proves no use case has a person parameter as its actor; the roster service names only the *subject* of an appointment;
- needs the actor appointed for the matter (`GeographyActRefused [NotAppointed]`);
- is refused if a version of the revision is published (`RevisionAlreadyPublished`);
- is saved with its loaded row version.

## A published revision is closed, at three levels

1. **Application:** `StoredRevision::$published` (from the repository) means every act is refused.
2. **Repository:** `save()` refuses a published revision. A trigger refusal (below) maps to `RevisionAlreadyPublished`.
3. **Database:** the trigger `refgeo_revision_closed_when_published` refuses any UPDATE or DELETE of a published revision's row. Every save updates that row first, so the whole aggregate is closed to any writer.

**The race the application alone could miss:** a re-prepare loads the revision before a publication commits and saves after it. **Publication now locks the revision's row** (`FOR UPDATE`, after the sequence row; a save takes only the revision row, so no deadlock). The racing save waits for the publication, then the trigger refuses it. A deterministic test proves this.

## Production wiring (`config/app.php`)

**`IdentityServiceProvider`** (Identity's ports, deferred from G-0): `CurrentAccount` → `LaravelCurrentAccount`; `AccountDirectory` → `PostgresAccountDirectory`; `ContextPersonRefs` → `PostgresContextPersonRefs`. All on the central (default) connection.

**`ReferenceGeographyServiceProvider`:**
- `ActingPerson` → `IdentityActingPerson`;
- **`PlatformOwnerAuthority` → `UnestablishedPlatformOwnerAuthority` (closed);**
- the repository, roster store, ledger (no test seams) and atlas source → their PostgreSQL adapters;
- the use cases autowire.

## The no-bypass guard (`tests/Architecture/ReferenceGeographyNoBypassTest.php`)

It scans `app`, `routes`, `config` and `database`. **Six rules, each with an explicit allow-list:**

| Rule | Allowed users |
|---|---|
| 1. the publication ledger | `PublishRevision`, the ledger, the provider |
| 2. the revision repository | `RevisionService` and the Geography adapters |
| 3. the roster store | the roster services, `RevisionService` (read) and the adapters |
| 4. any `refgeo_` table name | Geography's Infrastructure and its migrations |
| 5. the `Revision` aggregate | Domain, the use cases and the two adapters |
| 6. delivery code (HTTP, console, jobs, listeners, routes) | **never Geography's Infrastructure or Ports** |

**It is proven to bite twice:**
- `tests/Fixtures/ReferenceGeographyBypass` (deliberate bypasses, never loaded) makes **every rule fire**;
- a temporary rogue controller dropped into the real `app/Http` made the real-tree test fail (`nobypass-real-tree-probe.txt`). The probe was then removed.

**Adding a legitimate user of a port is a deliberate allow-list change.**

## Pitfalls

- **Never rebind `PlatformOwnerAuthority`** to a fixture or a role.
- **New delivery code calls the use cases,** never a port or adapter.
- **A test-harness lesson:** Geography PostgreSQL tests extend `RefgeoPostgresTestCase`, which closes its connections after each test. Without it, the full multi-suite run exhausted the cluster ("too many clients already").

## Testing

**`RevisionServiceTest`** (7, no database):
- the lifecycle with the acting person as actor throughout;
- **no person parameter as actor**;
- not appointed → refused;
- no acting person → refused;
- governance on submit and approve;
- **a published revision closed to every act**;
- a correction is a new revision.

**`PostgresPublishedRevisionClosureTest`** (3):
- the repository reports and refuses;
- the database trigger refuses;
- **deterministic:** a save racing a publication waits, then is refused.

**`ProductionWiringTest`** (3):
- every port resolves to its production adapter;
- nobody signed in → refused;
- **signed in, the actor is the person's own opaque reference, and appointing is still refused** (the closed boundary).

**`ReferenceGeographyNoBypassTest`** (2).

**RED:**
- service: missing types;
- closure: 3 failures. **The race save didn't wait (72 ms).**
- wiring: unbound ports;
- no-bypass: **my fixture expectation was incomplete.** The guard found one more real violation than I listed.

**Mutation:**
- application: **4 of 4 killed** (closure, appointment, `maySubmit`, `mayApprove`);
- PostgreSQL: **2 of 2 killed** (no revision-row lock; trigger refusal unmapped), both by the deterministic race test.

**Regression:**
- 221 database-free tests pass;
- the full cluster passes (198 tests), after the connection fix;
- the architecture suite has 318 tests, with the same 4 pre-Geography failures.

**Traceability:** PBDIGIT-346 G-4f · v3.11 §8, §15 · D-84, D-225, D-228 · G-3 acceptance item 3 · G-4 interpretation B-8, B-9.
