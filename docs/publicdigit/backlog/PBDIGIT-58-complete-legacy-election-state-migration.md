# PBDIGIT-58 — Complete the legacy election-state migration

**Type:** Implementation epic · **Epic:** `PBDIGIT-EPIC-03` Election Management · **Created:** 2026-08-06
**Discovery:** [`PBDIGIT-48`](PBDIGIT-48-election-state-has-four-representations.md) — **complete**. This epic implements its conclusion and adds no new discovery.

| | |
|---|---|
| **Status** | ⬜ **AWAITING AUTHORISATION** — the strategy is approved; the work is not yet authorised |
| **Decision implemented** | **Option B — complete the migration.** Approved by the Product Owner, 2026-08-06 |
| **Not implemented** | Option A (transitional synchronisation) — **rejected**, because it would preserve two representations and contradict `DeprecationPolicy` |
| **Nothing in the domain changes** | The aggregate, `ElectionLifecycleState`, the engine and the projection are correct and stay as they are. **This is application- and infrastructure-layer work** |

---

## Why this epic exists

**The domain successfully evolved to a constitutional lifecycle. The migration of its legacy consumers remained incomplete.** `PBDIGIT-47` was the first customer-visible consequence: a voter could not reach a live ballot because login routing read `elections.status`, a **formally deprecated** field that no lifecycle transition maintains.

**Everything needed already exists** — the engine, the facade API, `DeprecationPolicy` with per-field replacements, `DeprecationAccessGuard`, `ElectionReadModel`, `BackfillElectionState`. **This epic connects what was built.**

## The target API — already available, no extension needed

`ElectionLifecycle` (facade) exposes: `state()` · `isActive()` · `isVotingPhase()` · `canVote()` · `canEdit()` · `canManageVoters()` · `canPublishResults()` · `canEditTimeline()` · `canActivate()` · `isTerminal()` · `isInSetup()` · `isLocked()` · `blockedReason()` · `allowedActions()` · `isActionAllowed()`.

`DeprecationPolicy` names the replacements verbatim:

| Legacy | Replacement |
|---|---|
| `elections.status` | `ElectionLifecycleEngine::compute($election)->state->value` |
| `elections.is_active` | `ElectionLifecycleEngine::compute($election)->isActive()` |

⚠️ **One mapping is NOT covered and must be resolved inside `58B`:** `OrganisationNewsletterController` filters `status != 'deleted'`. **`'deleted'` is not an `ElectionLifecycleState` member** and has no obvious lifecycle equivalent (`archived`? soft-delete?). **This is the one place where the existing API may be insufficient — establish it before migrating that consumer, and do not invent a mapping.**

---

## Slices

### `58A` · Make the consumers observable — **do this first**

**Wire `ElectionReadModel` / `DeprecationAccessGuard` so legacy access announces itself**, then run the product and collect what fires.

**Rationale, from `PBDIGIT-48`:** `status` and `is_active` are among the commonest column names here — **520 candidate statements** app-wide, and three successive text searches each corrected the previous one. **A runtime guard converts the inventory from a guess into a measurement**, and catches paths no grep would associate with elections: queues, notifications, exports, packages.

* [ ] Legacy reads are logged with caller context.
* [ ] The product is exercised (including `PBDIGIT-00`'s journey) and the log reviewed.
* [ ] `PBDIGIT-48`'s verified inventory is confirmed or extended. **Extensions are expected, not a failure.**

### `58B` · Migrate the two Critical consumers

| Consumer | Capability | Replacement |
|---|---|---|
| `User::getActiveElection():1303` | login routing | lifecycle state instead of `status = 'active'` |
| `ElectionMiddleware:113-114, 127-128` | **election resolution for the voting flow** | lifecycle instead of `is_active` |

* [ ] **`PBDIGIT-47` is fixed as a consequence** — a voter logging in during an open window reaches the ballot, verified in a browser.
* [ ] `B10` still honoured (`PBDIGIT-30`): one eligible election ⇒ enter it; several ⇒ `/election/select`; none ⇒ organisation routing.
* [ ] A regression test with a **stale `status` and a `voting_active` projection** — the exact shape of `PBDIGIT-47`.
* [ ] The `'deleted'` mapping question above is answered before `OrganisationNewsletterController` is touched.

### `58C` · Migrate the remaining production readers

`OrganisationController` statistics · `CommissionDashboardController` display · `ElectionManagementController:381` · `Demo/DemoVoteController` · `Demo/PublicDemoController` · `OrganisationNewsletterController` (after `58B`'s question is settled).

* [ ] Dashboard counts derive from the lifecycle. *(They are silently wrong today — no error, just wrong numbers.)*

### `58D` · Migrate demo and CLI tooling

`SetupDemoElection` · `SetupPublicDemoElection` · `ListAllElections`.

⚠️ **These are the only writers of `elections.status`.** Once they stop writing it, the field is written by nothing — which is the precondition for `58F`.

### `58E` · Raise deprecation enforcement

Advance `DeprecationPolicy::STRICT_LEVEL` one level at a time, honouring its own documented **12–24 h observation** per level, to Level 4 (*"all violations throw"*).

* [ ] Each advance observed before the next.
* [ ] **Reaching Level 4 without violations is the proof that `58A`–`58D` are complete** — the enforcement mechanism, not a code review, certifies the migration.

### `58F` · Retire the legacy fields

* [ ] `elections.status` and `elections.is_active` removed.
* [ ] **`elections.state` reconsidered** — it is a manually-backfilled cache of the engine (`PBDIGIT-48` §5). Either it becomes provably derived, or it goes too. **`BackfillElectionState` becomes unnecessary and should be removed with it.**
* [ ] Divergent rows are irrelevant by then, because the fields are gone. **This is the only correct time to stop caring about them.**

---

## Architectural Definition of Done

```
[ ] No production code reads elections.status
[ ] No production code reads elections.is_active
[ ] All consumers obtain election state from the lifecycle engine
[ ] DeprecationPolicy::STRICT_LEVEL raised to full strict with no violations
[ ] Legacy fields removed from the schema
[ ] elections.state either provably derived or removed
```

## Ordering constraint

```
58A  observe          -> must precede everything (the inventory is otherwise a guess)
58B  Critical         -> fixes PBDIGIT-47
58C  remaining        -\
58D  tooling          -/  both must precede 58E
58E  enforce          -> certifies 58A-58D
58F  retire           -> only after 58E is clean
```

**`58E` before `58C`/`58D` would throw on paths not yet migrated. `58F` before `58E` would remove the fields while readers remain.**

## Explicitly out of scope

* **Any change to the domain model.** The lifecycle enum, engine, projection and constitution are correct.
* **Transitional synchronisation.** Option A was considered and rejected; **re-introducing a sync path during migration would recreate the condition this epic exists to end.**
* **Hand-repairing `namaste 2026`'s columns.** `58B` makes it unnecessary; doing it earlier destroys the live evidence.
* **The other two instances of this pattern** — `PBDIGIT-45` (votes per IP) and `PBDIGIT-49` (voter eligibility). **Same shape, separate epics.** *(If this epic's approach works, it is the template for both — but that is a claim to make afterwards, not now.)*

---

**Traceability:** [`PBDIGIT-48`](PBDIGIT-48-election-state-has-four-representations.md) (discovery, complete) · `PBDIGIT-47` (the reported defect this fixes) · `PBDIGIT-30` B10 · `PBDIGIT-32` · `app/Application/Election/Deprecation/{DeprecationPolicy,ElectionReadModel,DeprecationAccessGuard}.php` · `app/Application/Election/Facades/ElectionLifecycle.php` · `app/Application/Election/Services/ElectionLifecycleEngineImpl.php` · `app/Console/Commands/BackfillElectionState.php` · `app/Models/User.php:1301-1312` · `app/Http/Middleware/ElectionMiddleware.php:113-128` · `PBDIGIT-45` · `PBDIGIT-49`
