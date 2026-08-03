# WP-4C — Engineering Readiness Review (EP-03)

**Produced by:** engineering, 2026-08-04. **Authority to exist:** **R-87** permits WP-4C to be **CONSIDERED** under normal governance. **Consideration is not authorization (R-80) — this review authorizes nothing and requests nothing be built.**
**Purpose:** determine whether WP-4C is executable as scoped, and if not, name precisely what is missing.

---

## 1. What WP-4C is, per the roadmap

> **WP-4C — `AdjudicationFailureDeclared` + counterpart + hydrator + catalog entry**
> *(`EPIC-004_Architecture_to_Implementation_Roadmap.md`, annotation of 2026-08-02, added under R-68)*

Four deliverables. **They do not share one owner, and that is the finding of this review.**

## 2. Discovery — what exists today

| Element | State | Evidence |
|---|---|---|
| `AdjudicationFailureDeclared` (the event class) | ⛔ **does not exist** | `grep -rln "AdjudicationFailureDeclared" app/ tests/` → no matches |
| `AdjudicationProcessState::concludeFailureDeclared()` | ✅ **exists** | `app/Contexts/Adjudication/Application/Process/AdjudicationProcessState.php:207` — PM-7, delivered in WP-2 |
| The hydrator | ⛔ does not exist | precedent exists: `AdjudicationExpiredHydrator` (WP-6) |
| The catalog entry | ⛔ does not exist | `Canonical_Event_Catalog_v1.0.md` is **FROZEN** |
| Contestation's reaction (the "counterpart") | ⛔ **does not exist, and is not designed** | see §3 |

**Note the shape this repeats.** As in WP-4B, the *state transition* already exists and the *announcement* does not: PM-7 can conclude a failure today, and nothing tells anyone.

## 3. FINDING — the "counterpart" is an OPEN question owned by another context

EPIC-004K **§15, open questions, item 3** (carried forward, named owner):

> **"Contestation's reaction to `AdjudicationFailureDeclared` — challenge disposition on declared failure: Contestation-side design."**

And §10 records the consumer set as *"Contestation (challenge disposition — **open question §15**) and Collection-side (the renewed-collection demand, COL-5a)."*

**So one of WP-4C's four deliverables is an open design question in a different bounded context.** It is not blocked on engineering capacity, and it is not a gap engineering may close: **challenge disposition is Contestation's to model.**

## 4. FINDING — the catalog is FROZEN; an entry is evolution by succession

`Canonical_Event_Catalog_v1.0.md` is a frozen artifact. **Adding an entry is therefore an ARB-sanctioned change, not an edit** — the house rule is *"evolution by succession, no frozen artifact edited."*

**This is not a blocker: precedent exists.** WP-3A delivered a catalog entry for `ChallengeRouted` and was accepted unconditionally (**R-67**). **The path is known and was walked four days ago** — it needs following, not inventing.

## 5. Executability assessment

| Deliverable | Owner | Executable now? |
|---|---|---|
| **the event** `AdjudicationFailureDeclared` | Adjudication | ✅ **yes** — PM-7's transition exists; the event announces a fact Adjudication already owns |
| **the hydrator** | Adjudication | ✅ **yes** — `AdjudicationExpiredHydrator` is the precedent |
| **the catalog entry** | Adjudication, with ARB sanction | ✅ **yes**, via the R-67 succession path |
| **the counterpart** (Contestation's reaction) | **Contestation** | ⛔ **no** — §15.3 is an open Contestation-side design question |

## 6. Verdict

> **WP-4C IS PARTIALLY EXECUTABLE. Three of its four deliverables are executable now; the fourth is not engineering's to build.**

**The announcement half — event, hydrator, catalog entry — is executable and coherent on its own:** it announces a fact PM-7 can already produce, and announcing it is Adjudication's obligation regardless of who consumes it. **WP-6 established exactly this precedent** — `AdjudicationExpired` was announced as published language while its consumer (WP-6B) remained separate.

**The counterpart half is blocked on §15.3**, which is Contestation-side design and owned there.

## 7. Recommendation to governance — subdivision, not a request to build

**Engineering recommends WP-4C be SUBDIVIDED**, on the method the programme has already used twice (WP-3 → 3A/3B under R-68; §WP-4 → 4A/4B/4C/4D under R-68):

| | Scope | State |
|---|---|---|
| **WP-4C-1** | `AdjudicationFailureDeclared` — the event, its hydrator, its catalog entry (succession path per R-67) | **candidate for authorization** |
| **WP-4C-2** | the counterpart — Contestation's challenge disposition on declared failure | **blocked on §15.3**, Contestation-side design |

**What this recommendation is not:** it is not a request to authorize WP-4C-1, and it does not decide the subdivision — **subdividing a work package is a governance act (R-68's precedent), not engineering's.** No plan is produced, no EP-01 submitted, and nothing is built until authorization exists.

**Why the subdivision matters rather than being bookkeeping:** without it, WP-4C cannot be accepted, because one quarter of it is a question in another context. **§WP-4 would then remain open on a dependency no amount of Adjudication work can discharge** — and §WP-4's closure is what WP-8 waits on (R-79).

## 8. Prerequisites and blockers, stated plainly

| Item | Status |
|---|---|
| PM-7's state transition | ✅ exists (WP-2) |
| Hydrator precedent | ✅ exists (`AdjudicationExpiredHydrator`, WP-6) |
| Catalog succession path | ✅ exists (WP-3A / R-67) |
| **§15.3 — Contestation's disposition** | ⛔ **OPEN, another context's design** |
| Authorization for any WP-4C work | ⛔ **none. R-87 permits consideration only** |
| The consumer set's second member (Collection-side, COL-5a) | ⚠️ recorded in §10; **not assessed here** — no evidence was gathered on its state, and this review makes no claim about it |

## 9. Traceability

**R-87** (WP-4C may be considered) · **R-80** (consideration ≠ authorization) · **R-68** (the subdivision precedent) · **R-67** (WP-3A's catalog entry accepted — the succession path) · **R-79** (WP-8 deferred pending §WP-4) · EPIC-004K **§10** (messages produced) · **§15.3** (the open question) · roadmap `EPIC-004_Architecture_to_Implementation_Roadmap.md` annotation 2026-08-02 · `Canonical_Event_Catalog_v1.0.md` (frozen) · EP-03.
