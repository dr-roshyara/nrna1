# PBDIGIT-58 — Complete the legacy election-state migration

**Type:** Implementation epic · **Epic:** `PBDIGIT-EPIC-03` Election Management · **Created:** 2026-08-06 · **Rewritten capability-first 2026-08-06**
**Discovery:** [`PBDIGIT-48`](PBDIGIT-48-election-state-has-four-representations.md) — **complete**. This epic implements its conclusion and adds no new discovery.
**Pattern:** [`docs/pks/2026-08-06-legacy-consumer-migration-pattern-candidate.md`](../../pks/2026-08-06-legacy-consumer-migration-pattern-candidate.md) — filed as a KnowledgeOS **candidate**, not adopted.

| | |
|---|---|
| **Status** | ✅ **STRATEGY APPROVED** by the Product Owner, 2026-08-06 · ⬜ **`58A` not yet started** — it is the first slice and changes only the guard wiring |
| **Decision implemented** | **Option B — migrate the legacy consumers.** Approved by the Product Owner, 2026-08-06 |
| **Permitted during migration** | ✅ **A Legacy Compatibility Adapter.** Legacy fields *may* be written from the lifecycle **while remaining consumers are migrated** — see §Legacy Compatibility Adapter |
| **Rejected** | **Option A — permanent synchronisation.** Maintaining the legacy fields *indefinitely* so old readers can stay. That recreates today's condition rather than ending it |
| **Definition of Done** | 🎯 **Zero production readers of the legacy fields.** At that point the compatibility writes are removed and the fields retired |
| **Domain changes** | **None.** The aggregate, `ElectionLifecycleState`, the engine and the projection are correct. **This is application- and infrastructure-layer work only** |

---

## 🔒 Migration invariant

```
Every capability must obtain election state from the constitutional lifecycle.

No production capability may interpret lifecycle from legacy persistence fields.
```

**That single invariant explains the whole epic.** Every slice below either establishes it for one capability, or enforces it.

### The work is migrating consumers, not replacing variables

**`status`, `is_active` and `state` are persistence. They are not the problem — they are where the problem is visible.**

> **Move consumers of the Election State capability from the legacy representation to the authoritative representation.** Once no consumer remains, the legacy representation becomes removable as a consequence — **not as a task.**

**Why the wording matters:** "replace the legacy fields" invites someone to start with a migration that drops columns. **"Migrate the legacy consumers" puts the columns last, where they belong** — and makes the Definition of Done a property of the *consumers* (zero readers), not of the schema.

---

## Why this epic exists

**The domain successfully evolved to a constitutional lifecycle. The migration of its legacy consumers remained incomplete.**

`PBDIGIT-47` was the first customer-visible consequence: **a voter could not reach a live ballot**, because the capability that decides where a voter lands read `elections.status` — a **formally deprecated** field that no lifecycle transition maintains.

**Everything needed already exists**: the engine, its facade API, `DeprecationPolicy` with per-field replacements, `DeprecationAccessGuard`, `ElectionReadModel`, `BackfillElectionState`. **This epic connects what was already built.**

## Capabilities to restore

**Read this table instead of a file list.** Each row is a customer-visible capability that currently derives election state from a deprecated field.

| Capability | What breaks today | Legacy source | Risk | Slice |
|---|---|---|---|---|
| **Election Entry Resolution** — *where a voter lands after login* | 🔴 **a voter cannot reach a live ballot** (`PBDIGIT-47`) | `status` | **Critical** | `58B` |
| **Election Context Resolution** — *which election a request belongs to* | 🔴 **a request can be attributed to the wrong election, or to none** | `is_active` (severity **strict**) | **Critical** | `58B` |
| **Organisation Reporting** — *election counts on the organisation dashboard* | counts are **silently wrong** — no error, just wrong numbers | `status` | Medium | `58C` |
| **Election Management** — *the officer's election listing* | listings may omit or include the wrong elections | `is_active` | Medium | `58C` |
| **Commission Dashboard** — *election status shown to the commission* | **displays a stale value as fact** | `status` | Low | `58C` |
| **Member Communication** — *newsletter audience selection* | audience derived from a value the lifecycle has no equivalent for | `status != 'deleted'` | Low | `58C` ⚠️ |
| **Demo Platform** — *demo voting, public demo, demo provisioning* | demo gating and provisioning diverge from the real lifecycle | `status`, `is_active` | Medium | `58D` |

**Supporting detail — the code sites behind each capability** *(reference only; the capability is the unit of work)*: `User::getActiveElection():1303` · `ElectionMiddleware:113-114,127-128` · `OrganisationController ~192,193,565` · `ElectionManagementController:381` · `CommissionDashboardController:28` · `OrganisationNewsletterController:46,115` · `Demo/DemoVoteController:223,573,2461` · `Demo/PublicDemoController:239,451-454` · `SetupDemoElection` · `SetupPublicDemoElection` · `ListAllElections`.

## The target API — available today, no extension needed

`ElectionLifecycle` exposes `state()` · `isActive()` · `isVotingPhase()` · `canVote()` · `canEdit()` · `canManageVoters()` · `canPublishResults()` · `canEditTimeline()` · `canActivate()` · `isTerminal()` · `isInSetup()` · `isLocked()` · `blockedReason()` · `allowedActions()` · `isActionAllowed()`.

`DeprecationPolicy` names the replacements verbatim:

| Legacy field | Documented replacement |
|---|---|
| `elections.status` | `ElectionLifecycleEngine::compute($election)->state->value` |
| `elections.is_active` | `ElectionLifecycleEngine::compute($election)->isActive()` |

⚠️ **One capability has no mapping, and it must be settled inside `58C` — not invented.** **Member Communication** filters `status != 'deleted'`. **`'deleted'` is not an `ElectionLifecycleState` member.** Whether it means `archived`, a soft-delete, or a concept the lifecycle deliberately omits is **unknown**. **This is the single place where the existing API may be insufficient.**

## Legacy Compatibility Adapter — permitted, bounded, and not Option A

**Migrating every consumer in one change is not realistic.** During migration an **adapter** may write the legacy representation *from* the authoritative lifecycle, so unmigrated consumers keep working. **That is precisely what an Adapter does: it presents a new model in an old shape, for a bounded period.**

**This is not Option A. The difference is the exit condition, and it is the only difference that matters:**

| | Legacy Compatibility Adapter (permitted) | Permanent synchronisation (rejected) |
|---|---|---|
| Purpose | present the lifecycle in the legacy shape **while consumers are migrated** | keep legacy readers working **indefinitely** |
| Ends when | **zero production readers remain** | never |
| Consumers | shrinking, tracked | stable, untracked |
| Outcome | legacy fields retired | two representations forever |

**If the adapter is used it carries three obligations — otherwise "temporary" becomes permanent by default, which is how this situation arose:**

* [ ] **A named owner and a review date.** An adapter with neither is Option A with better manners.
* [ ] **The reader count is visible and falling** — `58A`'s guard already measures it, so this is free.
* [ ] **The adapter is removed in `58F`, not left to lapse.** Its removal is a task, not an assumption.

⚠️ **The loophole this closes explicitly:**

> **The Legacy Compatibility Adapter exists solely to protect *existing* legacy consumers during migration. It must never justify creating a new legacy consumer. Every new capability must consume the constitutional lifecycle directly.**

**Without that sentence, the adapter becomes an argument** — *"the field is still maintained, so I'll read it just this once."* **Each such once resets the migration**, because the Definition of Done is a reader count, and a new reader moves it the wrong way.

---

## Slices — each restores one capability

### `58A` · Observe Legacy Consumers — ✅ **COMPLETE** (2026-08-06)

> ✅ **Mechanism approved — Phase 1: SQL listener only.** Definition approved: **a legacy consumer is a capability that makes a business decision using the deprecated representation** — *obtaining is not depending*. Serialisation is supporting evidence, never a consumer. **Extend to attribute observation only if the observed inventory proves incomplete.**
>
> ⏸️ **Superseded review detail:** [`2026-08-06-58A-observation-mechanism-review.md`](../reviews/2026-08-06-58A-observation-mechanism-review.md) — **the SQL-listener-only design was insufficient**: `PBDIGIT-48`'s own inventory lists consumers that read the field after load, with no query predicate. **A mechanism that cannot see `$election->status` cannot certify "zero production readers".**
>
> **Recommended:** SQL listener **plus** a per-field attribute interceptor — a **custom cast** for `is_active` (which is cast to `boolean`, and a get-mutator would bypass that cast) and an **accessor** for `status` (no cast to bypass). **Awaiting the Product Owner's confirmation of the reader definition before implementation.**

Wire `ElectionReadModel` / `DeprecationAccessGuard` so every legacy read announces itself, exercise the product, and read the log.

**Why observation precedes migration:** `status` and `is_active` appear in **520 candidate statements** app-wide. `PBDIGIT-48` produced **three successive text-based inventories, each correcting the last**, and two false claims survived two of them. **A runtime guard turns the inventory from a guess into a measurement**, and finds capabilities no text search associates with elections — queues, notifications, exports, scheduled work, packages.

* [ ] Legacy reads logged with caller context.
* [ ] The product exercised, including `PBDIGIT-00`'s journey, and the log reviewed.
* [ ] `PBDIGIT-48`'s capability table confirmed **or extended**. **Extensions are the expected outcome, not a failure of the discovery.**

### `58A` RESULT — observed 2026-08-06

**Mechanism ran with observation on, exercising: voter login · organisation page · election selection · election page · dashboards · officer login · election management · public demo · CLI listing.**

#### ✅ It found a decision site the static inventory missed

```
capability : Election Entry Resolution
fields     : status
caller     : app/Models/User.php:1353
stack      : User.php:1353 -> DashboardResolver.php:102 -> LoginController.php:97
sql        : select count(*) as aggregate from "elections" ...
```

**`User.php:1343-1353` is a SECOND decision site in the same capability** — a `count()` query filtering `status = 'active'`, distinct from `getActiveElection():1303`. **`PBDIGIT-48` listed only the latter.**

> **This is the justification for `58A` in one line: the static inventory was wrong about a Critical capability, and one login request proved it.** `Election Entry Resolution` has at least two decision sites, not one — and a migration that fixed only `getActiveElection()` would have left the other behind.

**Zero false positives.** Every hit mapped to a capability; no serialisation, no display noise.

#### 🔴 Two blind spots — characterised, so they can be closed without more instrumentation

**1 · In-memory Collection filters are invisible to a query listener.**

`OrganisationController:192-193` — `$realElections->where('status', 'active')->count()`. `$realElections` is **already fetched**, so this is `Illuminate\Support\Collection::where`, evaluated in PHP. **No SQL is emitted, so no listener can see it — and it is unambiguously a business filter** (it produces the dashboard counts).

**2 · Attribute-based decisions**, as anticipated: `PublicDemoController:454` · `SetupDemoElection:362` · `SetupPublicDemoElection:295`.

#### Recommendation — do NOT extend the mechanism

**Phase 1 proved incomplete, but the gap is enumerable statically, which the original problem was not.**

| | Text-searchable? |
|---|---|
| `where('status', 'active')` on a builder | 🔴 **no** — 520 candidates, indistinguishable from other models' `status` |
| `->where('status', …)` on a **Collection** | ✅ **yes** — the receiver is a fetched collection, syntactically distinctive |
| `$election->status ===` / `if (!$election->is_active)` | ✅ **yes** — an attribute access on an election variable |

**So the remaining consumers are findable by reading, and the ambiguous class is exactly the one the listener already covers.** Adding attribute interception would buy little and cost a mechanism to remove later — **signal quality beats completeness.**

⚠️ **What `58A` cannot claim:** coverage is limited to the flows exercised. **Queues, scheduled jobs and the voting flow itself were not exercised**, and the dev database holds one organisation and two elections. **`58A` extends the inventory; it does not close it.** The listener stays available (default off) for the next flow anyone runs.

#### Inventory going into `58B`

| Capability | Decision sites | Source |
|---|---|---|
| **Election Entry Resolution** | `User.php:1303` · **`User.php:1353`** | static · **observed** |
| **Election Context Resolution** | `ElectionMiddleware:113-114, 127-128` | static |
| **Organisation Reporting** | `OrganisationController:192, 193, 565` *(collection filters)* | static |
| **Demo Platform** | `PublicDemoController:454` · `SetupDemoElection:362` · `SetupPublicDemoElection:295` | static |
| **Member Communication** | `OrganisationNewsletterController:46, 115` | static |

**Display-only, not decisions — migrate for tidiness, never for correctness:** `CommissionDashboardController:28` · `ListAllElections:38` · `DemoVoteController:573, 2461` · `PublicDemoController:239`.

### `58B` · Restore **Election Entry Resolution** — ⏸️ **READY, sequenced behind one decision (`PBDIGIT-59`)**

> ⚠️ **Correction to my own framing.** I first recorded this slice as **BLOCKED because the migration is not behaviour-preserving**. That conflated two separable concerns:
>
> | Concern | Home |
> |---|---|
> | **Move the consumers onto the authority** | **this slice** — correct regardless of what the authority reads |
> | **What the authority computes from** — which timestamps define the voting window | **[`PBDIGIT-59`](PBDIGIT-59-which-timestamps-are-constitutional.md)** |
>
> **`58B` is not blocked in scope; it is sequenced.** Its scope is unchanged and small: two methods. **It waits only because migrating first would change voting windows silently** — not because the migration itself is in doubt.

**Capability boundary established first, per the rule that a capability is the migration unit and must never be left half-migrated.**

| Site | Verdict |
|---|---|
| `User::getActiveElection():1303` | ✅ **live consumer — migrate** |
| `User::countActiveElections():1345` | ✅ **live consumer — migrate** *(found by `58A`, missing from the static inventory)* |
| `User::hasActiveElection():1276` | delegates to `getActiveElection()` — **not a separate site** |
| `DashboardResolver:102, 105` | **callers, not consumers.** They already delegate to the two methods above — **do not touch** |
| `DashboardResolver::getActiveElectionForUser():751` + its own `where('status','active')` at `:781` | 🪦 **DEAD CODE.** `private`, and its only two occurrences in the entire repository are its own declaration and a log string inside itself. **No caller in `app/`, `tests/` or `routes/`.** → separate cleanup ticket, **not migrated** |

> **The Product Owner's caller/implementation/duplicate distinction cut this slice by a third**: what looked like three query sites is two live consumers plus one corpse.

#### Why it is sequenced — the two representations disagree for **every election in the database**

**Measured, not inferred (2026-08-06 19:16 UTC):**

| Election | legacy `status` | engine state |
|---|---|---|
| Demo Election | `planned` | **`draft`** |
| Demo Election — Namaste Nepal GmbH | **`active`** | **`draft`** |
| Demo Election — Public Digit | **`active`** | **`draft`** |
| namaste 2026 | `planned` | **`results_published`** |

**4 of 4 disagree, and in both directions.** So `where('status','active')` → lifecycle is **not a substitution; it is a behaviour change for every row.**

#### The underlying cause — extracted to `PBDIGIT-59`, not solved here

`VotingActive` is derived from **`voting_starts_at` / `voting_ends_at`** (`ElectionClockService::isVotingOpen`), while the legacy queries filter **`start_date` / `end_date`**:

| Election | `start_date` → `end_date` | `voting_starts_at` → `voting_ends_at` |
|---|---|---|
| namaste 2026 | `14:56` → **`16:00`** | `14:56` → **`15:28:44`** |
| all three demo elections | set | **`NULL` / `NULL`** |

**So this is `PBDIGIT-48`'s pattern one level down: the same concept — "when is voting open?" — held in two column pairs that disagree.** For `namaste 2026` the two windows differ by **31 minutes**; for demo elections the lifecycle window does not exist at all.

⚠️ **And a customer-visible consequence, recorded as an observation because its cause is not established:** `voting_ends_at` = `15:28:44` and `results_published_at` = `15:29:08` — **24 seconds apart** — while the officer stated the election ran until **18:00 Berlin (16:00 UTC)**. **The election closed ~31 minutes before its stated end and results followed immediately.** The likeliest explanation is two deliberate clicks in the management UI; **that has not been confirmed, and no autonomous cause has been shown.**

#### The single prerequisite

**[`PBDIGIT-59`](PBDIGIT-59-which-timestamps-are-constitutional.md) — which timestamps are constitutional?** That ticket owns the three questions (which pair is authoritative · what happens to demo elections whose lifecycle window is `NULL` · whether the resulting behaviour change is acceptable).

**On decision, `58B` implements immediately:**

* [ ] `User::getActiveElection()` reads the lifecycle.
* [ ] `User::countActiveElections()` reads the lifecycle. **Both, in one change — a capability is never left half-migrated.**
* [ ] `DashboardResolver` untouched — it is a caller, and already delegates.
* [ ] Browser-verified: a voter logging in during an open window reaches the ballot (**closes `PBDIGIT-47`**).
* [ ] Regression test with a stale `status` and a `voting_active` lifecycle.
* [ ] **No optimisation, no cleanup, no refactoring** in the same change.

**Engineering has written no code for `58B`.** Establishing the capability boundary and extracting `PBDIGIT-59` was the slice's first task, and it produced a decision rather than a diff — **which is a legitimate output, not a stall.**


**Election Entry Resolution** and **Election Context Resolution** both derive from the lifecycle.

* [ ] **`PBDIGIT-47` closes as a consequence** — a voter logging in during an open window reaches the ballot, **verified in a browser**.
* [ ] A request is attributed to the correct election regardless of the legacy fields' values.
* [ ] `B10` still honoured (`PBDIGIT-30`): one eligible election ⇒ enter it; several ⇒ `/election/select`; none ⇒ organisation routing.
* [ ] 🔴 **A regression test with a stale `status` and a `voting_active` lifecycle** — the exact shape of `PBDIGIT-47`. **If the test would pass with the legacy field correct, it does not protect this capability.**

### `58C` · Restore Election Management and Organisation Reporting

**Election Management**, **Organisation Reporting**, **Commission Dashboard**, **Member Communication**.

* [ ] Dashboard counts derive from the lifecycle. *(Today they are silently wrong — the worst failure mode, because nobody is told.)*
* [ ] ⚠️ **The `'deleted'` question is answered before Member Communication is touched.** No invented mapping.

### `58D` · Restore Demo Platform

Demo voting, public demo gating, demo provisioning, CLI listings.

⚠️ **The provisioning commands are the only writers of `elections.status`.** Once they stop writing it, **the field is written by nothing** — which is the precondition for `58F`.

### `58E` · Enforce Deprecation

Advance `DeprecationPolicy::STRICT_LEVEL` one level at a time, honouring its own documented **12–24 h observation per level**, to Level 4 (*"all violations throw"*).

* [ ] Each advance observed before the next.
* [ ] **Reaching Level 4 with no violations is the proof that `58A`–`58D` are complete.** **The enforcement mechanism certifies the migration — not a code review, and not this epic's checkboxes.**

### `58F` · Remove Legacy State

* [ ] **The Legacy Compatibility Adapter removed first** — its writes exist only to serve readers, so it goes when the last reader does.
* [ ] `elections.status` and `elections.is_active` removed.
* [ ] **`elections.state` reconsidered** — a manually-backfilled cache of the engine (`PBDIGIT-48` §5). Either provably derived, or removed. **`BackfillElectionState` becomes unnecessary and goes with it.**
* [ ] Divergent rows stop mattering, because the fields are gone. **This is the only correct moment to stop caring about them.**

---

## Architectural Definition of Done

```
[ ] ZERO production readers of elections.status          <- the target
[ ] ZERO production readers of elections.is_active       <- the target
[ ] Every capability obtains election state from the constitutional lifecycle
[ ] Legacy Compatibility Adapter removed (it exists only while readers remain)
[ ] DeprecationPolicy::STRICT_LEVEL at full strict, with no violations
[ ] Legacy fields removed from the schema
[ ] elections.state provably derived, or removed
```

**Read the first two lines as the definition and the rest as consequences.** Retiring a field is easy; **having no reader left to break is the achievement.**

## Ordering constraint

```
58A  observe   -> precedes everything; without it the inventory is a guess
58B  Critical  -> closes PBDIGIT-47
58C  reporting -\
58D  demo      -/  both precede 58E
58E  enforce   -> certifies 58A-58D
58F  retire    -> only after 58E is clean
```

**`58E` before `58C`/`58D` throws on unmigrated paths. `58F` before `58E` removes fields while readers remain.**

## Explicitly out of scope

* **Any domain change.** The lifecycle enum, engine, projection and constitution are correct.
* **Permanent synchronisation of the legacy fields** (Option A). **A Legacy Compatibility Adapter is permitted and is scoped above** — what is out of scope is a sync path with no exit condition.
* **Hand-repairing `namaste 2026`'s columns.** `58B` makes it unnecessary; doing it sooner destroys the live evidence.
* **The other two instances of this pattern** — `PBDIGIT-45` (votes per IP) and `PBDIGIT-49` (voter eligibility). **Same shape, separate epics.** *(If this epic works, it is the template for both — a claim to make afterwards, on evidence, not now.)*

---

**Traceability:** [`PBDIGIT-48`](PBDIGIT-48-election-state-has-four-representations.md) (discovery, complete) · `PBDIGIT-47` (the defect `58B` closes) · `PBDIGIT-30` B10 · `PBDIGIT-32` · `docs/pks/2026-08-06-legacy-consumer-migration-pattern-candidate.md` · `app/Application/Election/Deprecation/{DeprecationPolicy,ElectionReadModel,DeprecationAccessGuard}.php` · `app/Application/Election/Facades/ElectionLifecycle.php` · `app/Application/Election/Services/ElectionLifecycleEngineImpl.php` · `app/Console/Commands/BackfillElectionState.php` · `PBDIGIT-45` · `PBDIGIT-49`
