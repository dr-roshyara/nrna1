# PBDIGIT-32 — Implement Working Organisation Routing

**Type:** Implementation · **Epic:** `PBDIGIT-EPIC-01` Organisation Management
**Created:** 2026-08-06 · **Predecessors:** `PBDIGIT-29` (implementation discovery) · `PBDIGIT-30` (business decisions) · `PBDIGIT-31` (mechanism review)

| | |
|---|---|
| **Status** | ✅ **UNBLOCKED — ready for authorisation** |
| **Business rules settled** | **B1** identity · **B2** lifecycle · **B3** authority (+ traceability) · **B10** election-day exception — all approved, `PBDIGIT-30` **closed** 2026-08-06 |
| **Why implementable now** | B5/B6/B7 needed no separate ruling — B1–B3 already decide them. B8/B9 have their *rule* decided; only the *experience* is open, and that is design. **B4 was withdrawn** (product design, not discovery) → `PBDIGIT-34` |
| **Still open, and NOT prerequisites** | `PBDIGIT-34` cross-login memory *(an enhancement — this story must not implement it)* · `PBDIGIT-29` Q1/Q2 *(is the concept a field or a type; which store is authoritative)* · B8/B9 experience design |
| **Needs** | **authorisation to begin** — the rules are settled; the work is not yet approved |

---

## Goal

Make the routing mechanism answer the question B1 asks: **ignore the bootstrap organisation, count the real ones, then act** — `0 → Create or Join` · `1 → enter it` · `2+ → ask`.

**Every authenticated request must then execute inside exactly one Working Organisation** (the B1 invariant).

---

## Scope — and its hard boundaries

### In scope

1. **Extend the existing `DashboardResolver`** with the two missing destinations, as additional priorities keyed on the count of *real* organisations (`PBDIGIT-31` I-1).
2. Build the two missing surfaces: **Create or Join Organisation** and **Organisation Selection** (`PBDIGIT-31` RD-1, RD-2).
3. Implement whatever B2–B9 decide, once they exist.
4. **Regression tests** for each business rule, at the rule level — not merely at the class level.
5. **Browser verification** of the complete journey: 0 orgs · 1 org · 2+ orgs · after creating an organisation · logout → login.

### Explicitly OUT of scope — recorded so it cannot drift

| Not permitted | Why |
|---|---|
| Redesigning the routing mechanism | `PBDIGIT-31` rated robustness **4/5**; the mechanism is sound |
| Rewriting `DashboardResolver` | evidence does not justify it — two additive priorities suffice |
| Removing the nine-priority model | it works; it is missing two branches, not wrong in kind |
| Introducing a **Working Organisation** service, aggregate or value object | `PBDIGIT-29` Q1 is undecided, and `PBDIGIT-31` concluded the evidence does not justify a new concept |
| Redesigning elections, membership or multi-tenancy | out of the commission's scope |
| Choosing a source of truth among the four stores | `PBDIGIT-29` Q2 — a business/architecture decision, not this story's |

**The governing principle from `PBDIGIT-31`:** *"The evidence does not justify a new concept — the resolver is missing two destinations."* This story implements destinations, not architecture.

---

## Acceptance criteria

* [ ] A user with **0 real organisations** reaches a *Create or Join Organisation* surface.
* [ ] A user with **exactly 1 real organisation** enters it without being asked.
* [ ] A user with **2+ real organisations** is **asked which one** — and the system never guesses (B1.4).
* [ ] The **bootstrap organisation is excluded** from every count *(already partly implemented — `handleMissingOrganisation`, `hasOwnOrganisation()`; consolidate onto **one** definition of "real organisation", `PBDIGIT-31` I-3)*.
* [ ] After creating an organisation, the user is **inside it** (B1.2).
* [ ] After logout → login, the user **returns to the organisation they last worked in** (B1.5 / B4 — pending B4).
* [ ] The routing decision **does not outlive the business state that produced it** (RD-12 / I-2).
* [ ] Existing priorities (mid-ballot resume, elections, roles, fallbacks) still behave as before — **no regression**.
* [ ] Regression tests exist per business rule, and **each test names the rule it protects** *(the lesson of `PBDIGIT-29` F-2: a green test can lock in the wrong question)*.
* [ ] Browser-verified end-to-end, recorded — not inferred.

---

## RD-12 — separated out, no longer part of this story

The unwired cache invalidator became **[`PBDIGIT-33`](PBDIGIT-33-fix-routing-cache-invalidation.md)** and is **fixed**. It was a technical defect, not business behaviour, so it did not belong behind this story's block.

**It also turned out not to be a one-line fix:** attaching the observer alone would have thrown a `TypeError` on organisation creation, because `invalidateUserCaches(int $userId)` was typed `int` while `user_organisation_roles.user_id` is a UUID. See `PBDIGIT-33`.

## B10 — Election-Day Direct Entry (added to scope)

`PBDIGIT-30` **B10 is approved**, and it changes this story's routing order: *exactly one eligible election today → that election; otherwise → Working Organisation routing.* The exception applies **only at login or platform root**, never during ordinary navigation.

**B10 is largely implemented already** (`PBDIGIT-30` §B10 implementation note): `User::getActiveElection()` already scopes to the user's non-bootstrap organisations, to real (non-demo) elections, to `status='active'`, to today's date window, and excludes elections already voted in. P3 already routes 1 → `elections.show`.

**The gap is one branch:** the `2+` case routes to `organisations.show` (`DashboardResolver:130`) instead of the **existing** `GET /election/select` page.

⚠️ **One prerequisite inside this story's scope:** B10 forbids a second eligibility mechanism. `getActiveElection()`'s filter and `VoterEligibilityService`/`EligibilityEvaluator`/`EligibilitySnapshot` must be reconciled — **which is authoritative is unresolved** (`PBDIGIT-EPIC-02` MB-5 records the same concern). Settle it before implementing B10's branch.

---

## Sequence

```
PBDIGIT-30  B1 ✅ · B2–B9 ⬜        ← the block
        ↓
PBDIGIT-32  this story (implementation)
        ↓
regression tests per business rule
        ↓
browser verification
        ↓
PBDIGIT-00  end-to-end journey run
```

---

**Traceability:** `PBDIGIT-30` (B1 approved; terminology *Working Organisation*; B2–B9 open) · `PBDIGIT-31` (I-1 two additive priorities · I-2/RD-12 unwired invalidator · I-3 one definition of "real organisation" · robustness 4/5 · the refusal to propose a new concept) · `PBDIGIT-29` (Q1/Q2 undecided) · `app/Services/DashboardResolver.php` · `app/Observers/UserOrganisationObserver.php` · `app/Providers/AppServiceProvider.php:18`

