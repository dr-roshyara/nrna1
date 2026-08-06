# PBDIGIT-48 — Election state has four representations and they disagree

**Type:** Defect (architecture · single source of truth) · **Epic:** `PBDIGIT-EPIC-03` Election Management · **Created:** 2026-08-06
**Found by:** diagnosing `PBDIGIT-47` on the live election `namaste 2026`

| | |
|---|---|
| **Status** | **OPEN — not authorised** |
| **Customer impact** | **`PBDIGIT-47` is this defect's first customer-visible consequence:** a voter could not reach a ballot during an open voting period |
| **Severity** | High — **any** consumer that picks the wrong representation behaves wrongly, and there is nothing to tell it which is right |

---

## The evidence — one election, four answers

`namaste 2026` (`namaste-2026-74d3721c`), observed simultaneously at ~15:20 UTC on 2026-08-06:

| Representation | Value | Where |
|---|---|---|
| **Lifecycle projection** | **`voting_active`** | `stateMachine.currentState`, drives the officer's management screen |
| `elections.state` | `setup_nomination` | column |
| `elections.status` | `planned` | column |
| `elections.is_active` | `true` | column |
| date window | open (`14:56 → 16:00 UTC`) | `start_date` / `end_date` |

**The projection is the one the product treats as authoritative:** its `completedStates` runs `draft → … → ready_for_voting`, and the **only forward action it offers is `close_voting`** — you cannot close what is not open.

**So three stored fields are stale, and they are stale in different directions:** `state` is two transitions behind, `status` uses a vocabulary that is not the lifecycle's at all, and `is_active` happens to agree by accident.

## The vocabularies do not even match

`ElectionLifecycleState` has **twelve** members: `draft`, `submitted_for_approval`, `approved`, `rejected`, `setup_administration`, `setup_nomination`, `ready_for_voting`, `voting_active`, `counting`, `results_published`, `archived`, `suspended`.

**`elections.status` contains `'planned'` and `'active'` — neither is a lifecycle state.** So `status` is not a stale copy of the lifecycle; **it is a second, older vocabulary for the same concept**, and no mapping between them is declared anywhere.

## Why this is the same pattern as two earlier findings

| Concern | Competing authorities |
|---|---|
| Votes per IP | constitutional snapshot vs a global env var (`PBDIGIT-45`) |
| Voter eligibility | `voters` vs `election_memberships` vs retired `User` flags (`PBDIGIT-35`, `PBDIGIT-49`) |
| **Election state** | **projection vs `state` vs `status` vs `is_active`** |

> **Three separate concerns, each with several authorities and no declared winner.** The recurrence is the finding — not any single instance of it.

## What must be decided

1. **Which representation is authoritative?** (Evidence points at the projection.)
2. **Are `state`, `status` and `is_active` derived read-models, or independent writable fields?** If derived, what keeps them in step, and what happens when it fails?
3. **Should `status` exist at all**, given its vocabulary is not the lifecycle's?
4. **Which consumers read which?** `getActiveElection()` reads `status`; the management UI reads the projection. **A full inventory is needed before anything is changed.**

## Acceptance criteria

* [ ] One authoritative representation named and recorded.
* [ ] Every consumer inventoried and pointed at it — **`getActiveElection()` first**, since it caused `PBDIGIT-47`.
* [ ] Redundant fields removed, or made provably derived with a test that fails when they drift.
* [ ] 🔴 **A check that fails when the representations disagree.** This election was divergent in production and nothing noticed — **the divergence itself must be detectable.**
* [ ] Existing divergent rows repaired **after** the mechanism is fixed, never before. *(Repairing first would hide the cause — the same reasoning as `PBDIGIT-47`.)*

---

**Traceability:** `PBDIGIT-47` (the consequence) · `namaste-2026-74d3721c` observed 2026-08-06 ~15:20 UTC · `app/Domain/Election/Enum/ElectionLifecycleState.php` · `app/Domain/Election/Projection/ElectionLifecycleProjection.php` · `app/Models/User.php:1301-1312` · `elections.status` / `.state` / `.is_active` · `PBDIGIT-45` · `PBDIGIT-35` · `PBDIGIT-49`
