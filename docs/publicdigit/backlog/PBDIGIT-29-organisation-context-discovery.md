# PBDIGIT-29 — Organisation Context Discovery

**Type:** Discovery / Analysis (DDD) · **Epic:** `PBDIGIT-EPIC-01` Organisation Management
**Created:** 2026-08-06 · **Status:** `DISCOVERY COMPLETE — awaiting decisions; no code changed`
**Revision 2** — restructured to separate **facts · interpretation · recommendations**, and extended with the business-event trace that rev 1 omitted (§2). ID note: commissioned as "PBDIGIT-28"; that ID was already closed and IDs are never reused.

> **Mode honoured:** search/analysis automated · **zero files modified** · every fact cites a file/line · gaps stated as *Evidence not found*.

---

# 0. Executive summary

> ## The key insight
> **The platform answers *"which organisation scopes my queries?"* — it does not answer *"which organisation is the user working in?"***
>
> The first is tenancy, and it is answered well: `users.organisation_id` is `NOT NULL` with a foreign key, and `TenantContext` enforces it on every request. The second is what business rules R3–R5 are about, and **no component answers it.** Almost every symptom follows from that gap.

Two supporting facts, both new in rev 2 or elevated from rev 1:

1. **The business event fires into the void.** `OrganisationCreated` is dispatched (`OrganisationController.php:351`) but **has no listeners** — it is absent from the `$listen` map and auto-discovery is explicitly disabled (`EventServiceProvider::shouldDiscoverEvents(): return false`). R3 is instead realised **imperatively, 13 lines earlier, in the same method**.
2. **Login routing is cached at two levels**, so identical data can produce different landings (`LoginResponse:161`, `DashboardResolver` cache, plus a 60 s organisation cache).

---

# 1. FACTS (evidence only — no interpretation)

### 1.1 What exists in code

| Fact | Evidence |
|---|---|
| **No class, interface, value object or aggregate named `OrganisationContext`, `CurrentOrganisation` or `ActiveOrganisation` was found** in `app/`. Those words occur only inside `app/Traits/BelongsToTenant.php` and `app/Services/DashboardResolver.php` as prose/log text | repository search |
| A session key `current_organisation_id` is read and written across `app/` | 21 files reference it |
| A column `users.organisation_id` exists, **`NOT NULL`, with a foreign key** | `database/migrations/2026_03_05_000002_create_uuid_users_table.php:13,23` |
| A membership table `user_organisation_roles` exists | `database/migrations/2026_03_05_000003_create_user_organisation_roles_table.php` |
| A container binding `current.organisation_id` is set per request | `app/Http/Middleware/TenantContext.php:53` |
| A cache key `user.{id}.organisation_id` is set with a **60-second** TTL | `TenantContext.php:65-69` |
| `TenantContext` reads the **column** (not the session), validates against `user_organisation_roles`, aborts 403 on mismatch, then writes session + container | `TenantContext.php:65,73,79,52-53,83` |
| **15+ distinct classes write the session key directly** | `OrganisationController` ×2 · `ElectionManagementController` ×5 (`:223,404,678,1117,1394`) · `DemoVoteController` ×3 · `DemoResultController` ×2 · `DemoCodeController` · `CodeController:66` · `VoteController:285` · `TenantContext` ×2 · `EnsureOrganisationMember:149` · `IdentifyTenantFromHeader:51` · `DemoSetupController:53` |
| Two of those writes occur while **rendering** an organisation page, not while changing organisation | `OrganisationController:111`, `:406` |

### 1.2 Login routing

| Fact | Evidence |
|---|---|
| Fortify's login response delegates to `DashboardResolver` | `app/Http/Responses/LoginResponse.php:165` |
| **A cached redirect URL can be returned without re-resolving** | `LoginResponse.php:149-161` |
| `DashboardResolver` selects among **9 priorities**, first match wins: email-unverified → active-voting-session → elections-count → missing-org → org-without-election → first-time-user → multiple-roles → single-role → fallback | `DashboardResolver.php:68,94,120,130,159,185,606,626,683,702,713` |
| `DashboardResolver` caches its own resolution | `DashboardResolver.php:71-79`, `cacheResolution()` |
| `RouteServiceProvider::HOME` and `config/fortify.php` both specify `/dashboard/roles` | `RouteServiceProvider.php:24`, `config/fortify.php:63` |
| **No route matching organisation switch/selection was found** in `routes/` | routing search |
| A **role** selection destination exists (`role.selection`) | `DashboardResolver.php:626` |
| `dashboard.welcome` exists for first-time users | `DashboardResolver.php:606`, `routes/web.php:466` |
| **Evidence not found:** the redirect target of `OrganisationController::store()`; any logout-time context teardown | — |

---

# 2. FACTS — the business event trace *(new in rev 2)*

The question rev 1 failed to ask: **who is responsible for changing the active organisation after the business event?**

| Fact | Evidence |
|---|---|
| An event class `OrganisationCreated` exists, carrying `readonly Organisation $organisation` | `app/Events/OrganisationCreated.php:9,13-14` |
| It **is dispatched** — once | `OrganisationController.php:351` — `event(new \App\Events\OrganisationCreated($org))` |
| It appears in **no** `$listen` entry | `app/Providers/EventServiceProvider.php` — absent from the map |
| Listener auto-discovery is **explicitly disabled** | `EventServiceProvider::shouldDiscoverEvents(): return false` (with the comment *"Disable auto-discovery so listeners are only registered via `$listen`"*) |
| ⇒ **The event has zero listeners** | combination of the two facts above |
| The active organisation is instead changed **imperatively in the same method, 13 lines before the dispatch** | `OrganisationController.php:338` — `$user->update(['organisation_id' => $org->id])`, then `:351` dispatch |
| A mailable `OrganisationCreatedMail` exists | `app/Mail/OrganisationCreatedMail.php:13` — **Evidence not found** that any listener sends it in response to the event |

**So the answer to "who is responsible?" is a fact, not an opinion: `OrganisationController::store()` is, inline. The event carries no responsibility.**

---

# 3. INTERPRETATION (analysis — clearly distinct from §1–§2)

1. **The business concept appears to be represented implicitly**, spread across four storage mechanisms with no declared precedence. *(This is an interpretation of §1.1; the fact is only that no such class was found.)*
2. **The dispatched-but-unlistened event suggests an intention that was never completed.** Someone modelled "Organisation Created" as a business event, then implemented its consequence imperatively instead. That is consistent with the reported pattern of repeated re-implementation: the event *looks* like the extension point, but changing behaviour requires editing the controller.
3. **Two different questions are being answered by two different mechanisms**, and neither answers the business one (§0). Tenancy is enforced from the column; navigation is decided from roles and elections; "working context" is decided by whichever writer ran last.
4. **Cached routing decisions plausibly explain observed instability** — a cached URL survives data changes for the cache lifetime. *Plausible, not demonstrated:* no reproduction was attempted, and the cache keys/TTLs governing `LoginResponse` were not traced in this pass.
5. **`R6` ("explicit, never guessed") is not satisfied** — with four stores, no precedence, and fifteen writers, the effective value is whatever wrote last. *(This is close to fact, but it depends on reading R6 as requiring a single authority.)*

---

# 4. Rules vs implementation

| Rule | State | Basis |
|---|---|---|
| R1 every user belongs to ≥1 organisation | **satisfied structurally** | `users.organisation_id` is `NOT NULL` + FK |
| R2 creator becomes a member | **evidenced** | `store()` writes membership; `CreateUserOrganisationRole` listener exists |
| R3 after *Organisation Created*, the new organisation becomes active | **satisfied in effect, not by design** | done imperatively at `:338`; the event at `:351` has no listeners (§2) |
| R4 single organisation → enter automatically | **partially** | `DashboardResolver` P5 → `organisations.show`, but only after P2/P3 (voting, elections) take precedence |
| R5 multiple organisations → selection page | **not implemented** | no organisation-selection route exists; `role.selection` selects roles |
| R6 explicit, never guessed | **not satisfied** | §3.5 |

---

# 5. Inconsistencies

| # | Inconsistency | Evidence |
|---|---|---|
| **I-1** | 15+ classes write the context key directly, bypassing `TenantContext` | §1.1 |
| **I-2** | `TenantContext` reads the **column**; helpers, `BelongsToTenant` and audit read the **session** | `TenantContext:65` vs `Helpers/tenant.php:74`, `TenantHelper:55` |
| **I-3** | R5 has no implementation surface: no organisation-selection or switch route | §1.2 |
| **I-4** | Routing decisions cached at two levels (+60 s org cache) | `LoginResponse:161`, `DashboardResolver:71-79` |
| **I-5** | Context rewritten as a side effect of rendering pages | `OrganisationController:111,406` |
| **I-6** | Two framework defaults name `/dashboard/roles` but are bypassed when `LoginResponse` runs | `RouteServiceProvider:24`, `config/fortify.php:63` |
| **I-7** | Priority order encodes policy implicitly — mid-ballot voter (P2) outranks organisation context; elections (P3) outrank organisations (P5) | `DashboardResolver:85-185` |
| **I-8** | `OrganisationCreated` dispatched with no listeners; its consequence hard-coded in the dispatching method | §2 |
| **I-9** | No logout teardown of organisation context located | *Evidence not found* |

---

# 6. Open questions for decision — *not* recommendations to implement

**Discovery does not decide these, and deliberately does not presuppose that a new concept is required.** A single well-named field may be sufficient; that is a modelling decision to be made *after* the business lifecycle is settled (`PBDIGIT-30`).

| # | Question | Why it must be decided first |
|---|---|---|
| Q1 | **Is "active organisation" one field, or a concept with behaviour?** | rev 1 recommended "name the concept"; that presumed the answer. If the rules are simple, a field with one owner may suffice |
| Q2 | Which of the four stores is authoritative, and what precedence do the others have? | four stores, no precedence (§1.1). Evidence *favours* `users.organisation_id` (NOT NULL, FK, already trusted by `TenantContext`) — **stated as the evidenced candidate, not a recommendation** |
| Q3 | Should R5 be built (organisation selection) or amended (role selection is the intended experience)? | today the code answers a different question than the rule asks (I-3) |
| Q4 | Should `OrganisationCreated` carry the consequence (a listener), or is imperative handling intended? | I-8 — this determines whether the event is an extension point or decoration |
| Q5 | May rendering a page change the active organisation? | I-5 |
| Q6 | Is cached navigation acceptable? | I-4 |

**Successor story:** `PBDIGIT-30 — Active Organisation Business Lifecycle Discovery` answers the *business* side of Q1/Q3 before any of this becomes design.

## Explicitly not done

no code changed · no refactoring · no ADR · no architecture proposed · no source of truth chosen · no redirect altered · **no concept named**.

---

**Traceability:** `app/Events/OrganisationCreated.php:9,13` · `app/Providers/EventServiceProvider.php` (`$listen`, `shouldDiscoverEvents`) · `app/Http/Controllers/OrganisationController.php:106,111,338,351,401,406` · `app/Http/Responses/LoginResponse.php:83,96,110,115,149,161,165` · `app/Services/DashboardResolver.php:48,64,68,71,85,94,108,120,130,148,159,185,606,626,683,702,713` · `app/Http/Middleware/TenantContext.php:52,53,65,73,79,83` · `app/Http/Middleware/{EnsureOrganisationMember.php:149, IdentifyTenantFromHeader.php:51}` · `app/Http/Controllers/Election/ElectionManagementController.php:223,404,678,1117,1394` · `app/Traits/BelongsToTenant.php` · `app/Helpers/{tenant.php:74, TenantHelper.php:55}` · `app/Providers/RouteServiceProvider.php:24` · `config/fortify.php:63` · `database/migrations/2026_03_05_000002_create_uuid_users_table.php:13,23` · `database/migrations/2026_03_05_000003_create_user_organisation_roles_table.php` · `routes/web.php:466`
