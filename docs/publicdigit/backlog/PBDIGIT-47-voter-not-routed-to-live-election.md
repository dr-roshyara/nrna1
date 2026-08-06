# PBDIGIT-47 — A voter is sent to the organisation page during a live election

**Type:** Defect (blocking a real election) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-08-06
**Reported by:** Product Owner, during the live election **`namaste 2026`** (`namaste-2026-74d3721c`, Namaste Nepal GmbH)

| | |
|---|---|
| **Status** | **OPEN — reproduced, root cause established, not repaired** |
| **Customer impact** | 🔴 **A voter logging in during an open voting period lands on the organisation homepage and is given no route to the ballot.** For a real election, that is a lost vote unless the voter finds the election themselves |
| **Severity** | **Blocking** — it happened in a real election with a live voting window |

---

## ✅ Immediate workaround (usable now, no deploy)

**The voter can reach the ballot at `/election/select`** — verified 200, and it lists `namaste 2026` with badge `OFFICIAL`. **Login routing is what is broken; the election itself is reachable and open.**

## Reproduced

```
POST /login  (roshyara@gmail.com)
  -> 302  /organisations/namaste-nepal-gmbh        # the organisation homepage
```

Reproduced for **both** a member and the election officer, so it is not role-specific.

## Root cause — the routing reads a stale column, not the authoritative state

`User::getActiveElection()` (`app/Models/User.php:1301-1312`) filters on **`elections.status = 'active'`**.

**Condition-by-condition for `namaste 2026`:**

| Condition | Result |
|---|---|
| organisation is a tenant org the user belongs to | ✅ |
| `type === 'real'` | ✅ |
| **`status === 'active'`** | 🔴 **actual value: `'planned'`** |
| `start_date <= now` | ✅ |
| `end_date >= now` | ✅ |
| user has not already voted | ✅ |

**Removing only the `status` filter makes the query return `namaste 2026`. So `status` is the single blocker** — proved by running the same query with that one condition dropped.

## 🔑 But the election *is* active — the column is stale, not the election

**The lifecycle projection, which the officer's management screen reads, says the election is open:**

```
stateMachine.currentState  = voting_active
completedStates            = draft -> submitted_for_approval -> approved
                             -> setup_administration -> setup_nomination -> ready_for_voting
allowed actions            = close_voting, suspend      <- only forward action is to CLOSE it
projectionAvailable        = true
```

**Meanwhile the database columns say otherwise:**

| Source | Says |
|---|---|
| **lifecycle projection** (authoritative — drives the officer UI and the capability set) | **`voting_active`** |
| `elections.state` | `setup_nomination` 🔴 stale |
| `elections.status` | `planned` 🔴 stale |
| `elections.is_active` | `true` |
| date window | open — `14:56 → 16:00 UTC` (**18:00 Berlin**), now ~15:20 UTC |

> **The election has been opened for voting. Two denormalised columns never followed, and login routing trusts one of them.**

**So this is not "the officer forgot to start the election".** `close_voting` being the only available forward action proves the projection considers voting open.

## Why this is a routing defect and not a data fix

**Correcting the two columns by hand would make the symptom disappear and leave the cause in place**: the next election to be opened will diverge the same way, because nothing keeps `status`/`state` in step with the projection, and the routing would still be reading a copy instead of the authority.

**The routing decision should be derived from the lifecycle state that the rest of the product treats as authoritative.**

## Acceptance criteria

* [ ] A voter logging in during an open voting period is routed to the ballot — verified in a browser, in a real election.
* [ ] The routing decision derives from the **authoritative** lifecycle state, not from `elections.status`.
* [ ] `B10` is honoured (`PBDIGIT-30`): exactly one eligible election today ⇒ enter it; several ⇒ `/election/select`; none ⇒ normal organisation routing.
* [ ] A regression test with an election whose projection is `voting_active` **while `status` is stale**, asserting the voter still reaches the ballot. **That is the case this defect describes, and it must be the case the test encodes.**
* [ ] The routing cache (`PBDIGIT-33`, 300 s) does not preserve a pre-activation decision after voting opens.

## Related

* **`PBDIGIT-48`** — **an unfinished migration**, not competing authorities: `elections.status` is a **formally deprecated field** (`DeprecationPolicy::FIELDS`) whose documented replacement is `ElectionLifecycleEngine::compute($election)->state->value`. **That is the underlying condition; this ticket is its first customer-visible consequence.**
* **`PBDIGIT-58`** — **the fix lives here**, as slice `58B`. `PBDIGIT-47` is closed by that migration, not by a patch to `getActiveElection()`.
* **`PBDIGIT-32`** — B10 implementation, still unauthorised. This defect is evidence for its priority.
* **`PBDIGIT-33`** — the routing cache, already fixed but relevant to the last criterion.

---

**Traceability:** reproduced 2026-08-06 ~15:20 UTC · `app/Models/User.php:1286-1313` (`getActiveElection`) · `app/Services/DashboardResolver.php` · management projection `stateMachine.currentState` · `elections.status` / `.state` / `.is_active` for `namaste-2026-74d3721c` · `PBDIGIT-30` B10 · `PBDIGIT-32` · `PBDIGIT-48`
