# PBDIGIT-32 — Implement Working Organisation Routing

**Type:** Implementation · **Epic:** `PBDIGIT-EPIC-01` Organisation Management
**Created:** 2026-08-06 · **Predecessors:** `PBDIGIT-29` (implementation discovery) · `PBDIGIT-30` (business decisions) · `PBDIGIT-31` (mechanism review)

| | |
|---|---|
| **Status** | **BLOCKED** |
| **Reason** | **B2–B9 are unanswered.** B1 is approved; the remaining eight business questions are not |
| **Owner of the block** | **Product Owner** (`PBDIGIT-30`) |
| **Engineering** | **may not start.** Implementing B1 alone would encode a partial ruleset and require re-work when B2–B9 land |
| **Unblocks when** | `PBDIGIT-30` records decisions for **B2–B9** |
| **Exception (candidate)** | **I-2 / RD-12** — the unwired cache invalidator — is a **defect**, not a rule implementation. It may warrant its own immediate story rather than waiting (see §Exception) |

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

## Exception candidate — RD-12, the unwired invalidator

`PBDIGIT-31` §10 found that `UserOrganisationObserver` clears `dashboard_resolution:{userId}` on `created`/`updated`/`deleted`/`restored` of `UserOrganisationRole`, is **imported** at `AppServiceProvider:18`, and is **never attached** (no `::observe()`, no `booted()`, no `#[ObservedBy]`).

**This is a defect with a one-line remedy, not a business rule awaiting decision.** Its consequence is customer-visible for up to 300 s after any organisation change.

**Recommendation:** treat it as its own small story (`PBDIGIT-33`) so it can be fixed and verified without waiting for B2–B9 — **but that is an authorisation decision, not an engineering one.** It is recorded here, not acted upon.

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
