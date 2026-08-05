# PBDIGIT-29 — Organisation Context Discovery

**Type:** Discovery / Analysis (DDD) · **Epic:** `PBDIGIT-EPIC-01` Organisation Management
**Created:** 2026-08-06 · **Status:** `DISCOVERY COMPLETE — awaiting decisions; no code changed`
**ID note:** commissioned as "PBDIGIT-28"; that ID was already closed (missing-module imports) and IDs are never reused, so this is **PBDIGIT-29**.

> **Mode honoured:** search/analysis automated · **zero files modified** · every conclusion cites evidence · gaps stated as *Evidence not found*.

---

## Story

**As a user** I want to automatically enter the correct organisation after login **so that** I continue working in the organisation I belong to without unnecessary navigation.

## The business rules under test (from the commission)

| Rule | Statement |
|---|---|
| R1 | Every user belongs to at least one organisation (default: PublicDigit) |
| R2 | Creating an organisation makes the user a member of it |
| R3 | **After the business event "Organisation Created", the new organisation becomes the active context** *(the commission's own refinement — adopted: event-phrased, not "latest wins")* |
| R4 | Single organisation on login → enter it automatically |
| R5 | Multiple organisations on login → present an organisation selection page |
| R6 | The active organisation is explicit, never guessed |

---

# 0. Headline answer

**The behaviour is unstable because the decision is *cached*, *distributed*, and *not modelled*.**

Three findings, in order of explanatory power:

1. **`Organisation Context` does not exist as a domain concept.** There is no class, value object, aggregate or service representing it. It exists as *a session key* + *a column on `users`* + *a container binding* + *two caches*. **Nothing can be authoritative because there is no thing to be authoritative.**
2. **Login routing is decided by a 9-priority resolver whose result is cached at two levels** — so the same user with the same data can land somewhere different depending on cache state. This is the most direct explanation for *"it changes every time we touch it."*
3. **R5 is not implementable as written:** there is **no organisation-selection page and no switch route anywhere in the routing table**. What exists is a **role** selection page (`/dashboard/roles`). The platform asks *"which role?"*; the business rule asks *"which organisation?"* — **different questions.**

---

# 1. The six DDD questions

| # | Question | Answer (evidence) |
|---|---|---|
| 1 | **What is an Organisation Context?** | **Not modelled.** No `OrganisationContext` type exists. Its realisation: session key `current_organisation_id` · column `users.organisation_id` · container binding `current.organisation_id` (`TenantContext.php:53`) · cache key `user.{id}.organisation_id`, 60 s (`:65-69`). The words `CurrentOrganisation` / `ActiveOrganisation` occur **only** inside `app/Traits/BelongsToTenant.php` and `app/Services/DashboardResolver.php` — never as types |
| 2 | **Who owns it?** | **No owner.** `app/Http/Middleware/TenantContext.php` is closest to an authority (it validates access, then sets context), but **15+ other classes write the same session key directly**, bypassing it (§4) |
| 3 | **When is it created?** | At registration — `users.organisation_id` is **`NOT NULL` with a foreign key** (`database/migrations/2026_03_05_000002_create_uuid_users_table.php:13,23`), so every user always has exactly one column-level organisation (**R1 satisfied structurally**). Re-established on every authenticated request by `TenantContext` |
| 4 | **When is it changed?** | Uncontrolled — on organisation creation (`OrganisationController::store()`: `$user->update(['organisation_id' => $org->id])`), on **viewing** an organisation (`:111`, `:406`), in `ElectionManagementController` (**5 sites**: `:223, :404, :678, :1117, :1394`), `VoteController:285`, `CodeController:66`, `EnsureOrganisationMember:149`, `IdentifyTenantFromHeader:51`, and several Demo controllers |
| 5 | **When is it persisted?** | Four lifetimes simultaneously: **session** · **`users.organisation_id`** (durable) · **cache** (60 s) · **container** (per request). No declared precedence |
| 6 | **Who decides the active organisation?** | **Three deciders:** `DashboardResolver` decides *where the user lands*; `TenantContext` decides *what tenant scope applies to queries*; and **the last controller to write the session decides what applies next**. None defers to another |

---

# 2. Organisation Context lifecycle (as implemented — discovered, not assumed)

```
REGISTRATION
  users.organisation_id assigned (NOT NULL + FK)          ← R1 met structurally
        │
LOGIN  → Fortify → app/Http/Responses/LoginResponse.php:83 toResponse()
        │           ├─ rate-limit guard   → route('dashboard')            :96
        │           ├─ unverified email   → route('verification.notice')  :110
        │           ├─ maintenance mode   → redirectToMaintenanceMode()   :115
        │           └─ resolveNormalDashboard()                           :149
        │                 ├─ ⚠️ CACHED redirect returned without re-resolving   :161
        │                 └─ DashboardResolver::resolve($user)            :165
        ▼
DASHBOARD RESOLVER — 9 priorities, first match wins (app/Services/DashboardResolver.php)
  P1 email unverified        → verification.notice                       :68
  P2 active voting session   → slug.code.create  (resume ballot)         :94
  P3 elections, count-based  → 1 ⇒ elections.show · 2+ ⇒ organisations.show   :120,:130
  P4 no active organisations → handleMissingOrganisation()               :159
  P5 org, no active election → organisations.show                        :185
  P6 first-time user         → dashboard.welcome                         :606
  P7 multiple roles          → role.selection                            :626
  P8 single role             → route by role                             :683
  P9 fallback                → admin.dashboard / dashboard               :702,:713
        │
        ▼
TENANT CONTEXT (every authenticated request) — app/Http/Middleware/TenantContext.php
  reads users.organisation_id (Cache 60 s, :65)
  validates membership against user_organisation_roles (:73) → 403 if absent (:79)
  writes session + container (:52-53, :83)
        │
ORGANISATION CREATED → OrganisationController::store()
  $user->update(['organisation_id' => $org->id])     ← R2/R3 realised via the COLUMN
  session(['current_organisation_id' => …])          ← and, separately, via the SESSION
        │
VIEWING AN ORGANISATION → OrganisationController:111 / :406 rewrite the context as a SIDE EFFECT
        │
LOGOUT → Evidence not found (no context teardown located)
        │
LOGIN AGAIN → back to LoginResponse — possibly served from CACHE, not re-resolved
```

---

# 3. Redirect map

| Trigger | Decider | Destination |
|---|---|---|
| Login (normal) | `LoginResponse:165` → `DashboardResolver` | one of nine (§2) |
| Login (cached) | `LoginResponse:161` — **returns a cached URL without re-resolving** | whatever was cached |
| Framework default | `RouteServiceProvider::HOME = '/dashboard/roles'` (`:24`) and `config/fortify.php:63` | `/dashboard/roles` — **bypassed whenever `LoginResponse` runs** |
| Organisation created | `OrganisationController::store()` | **Evidence not found** — `store()` sets `users.organisation_id` and the session; its redirect target was not located in this pass. Sibling failure paths redirect to `route('dashboard')` (`:106`, `:401`) |
| Viewing an organisation | `OrganisationController:111`, `:406` | no redirect — **silently rewrites the context** |
| Multiple organisations | — | **no organisation-selection route exists.** Closest behaviour: `role.selection` (`DashboardResolver:626`) |
| First-time user | `DashboardResolver:606` | `dashboard.welcome` (`routes/web.php:466` → `WelcomeDashboardController`) |
| Logout | — | **Evidence not found** |

---

# 4. Source-of-truth analysis

**Four candidate stores. No declared precedence.**

| Store | Written by | Lifetime | Role it plays |
|---|---|---|---|
| `users.organisation_id` | registration · `store()` | durable, `NOT NULL` + FK | *de facto* truth — `TenantContext` trusts **this**, not the session |
| `session('current_organisation_id')` | **15+ classes** (below) | session | what `BelongsToTenant`, helpers and audit read |
| `Cache: user.{id}.organisation_id` | `TenantContext:65` | **60 s** | shortcut over the column |
| Container `current.organisation_id` | `TenantContext:53` | request | global-scope plumbing |
| `user_organisation_roles` | membership flows | durable | **the access authority** — validated at `TenantContext:73`, 403 at `:79` |

**Session-key writers in `app/`:** `OrganisationController` ×2 · `ElectionManagementController` ×5 · `DemoVoteController` ×3 · `DemoResultController` ×2 · `DemoCodeController` · `CodeController` · `VoteController` · `TenantContext` ×2 · `EnsureOrganisationMember` · `IdentifyTenantFromHeader` · `DemoSetupController`.

> **R6 is not met.** With four stores, no precedence and fifteen writers, the effective value is *whatever wrote last* — a guess by construction.

---

# 5. DDD responsibility analysis

| Question | Finding |
|---|---|
| Single authority? | **No.** `TenantContext` is positioned as one but is routinely bypassed |
| Is it a domain concept? | **No.** No type; infrastructure state referenced by name across ~21 files |
| Multiple redirect deciders? | **Yes** — `LoginResponse` (+cache), `DashboardResolver` (9 priorities +cache), `RouteServiceProvider::HOME`, `config/fortify.php`, plus every controller redirecting after writing context |
| Duplicated responsibility? | **Yes.** *Tenant scope* (`TenantContext`) and *landing page* (`DashboardResolver`) are different concerns; both read organisation state, neither owns it |
| Where does the decision belong? | With a named concept owned by one component — **not proposed here** (a modelling decision, §7) |

**Deeper diagnosis.** The code answers two questions well: *"which organisation scopes my queries?"* (tenancy — the column) and *"where should this user land?"* (navigation — roles and elections). **Neither answers *"which organisation is the user working in?"*** — the question R3–R5 are about. **The concept the rules describe has no representation, so every change re-implements it slightly differently. That is the mechanism behind "changed multiple times without stabilising."**

---

# 6. Inconsistencies

| # | Inconsistency | Evidence |
|---|---|---|
| **I-1** | No owner: 15+ classes write the context key directly | §4 |
| **I-2** | Competing stores: `TenantContext` reads the **column**; everything else reads the **session** | `TenantContext:65-69` vs `BelongsToTenant`, `Helpers/tenant.php:74`, `TenantHelper:55` |
| **I-3** | **R5 unimplementable as written** — no organisation-selection/switch route exists; `/dashboard/roles` selects **roles** | grep over `routes/`; `RouteServiceProvider:24`; `DashboardResolver:626` |
| **I-4** | **Routing decisions cached at two levels** (+ a 60 s org cache) — identical data can yield different landings | `LoginResponse:149-166`; `DashboardResolver:71-79` |
| **I-5** | Context mutated as a **side effect of rendering** (viewing an org, managing an election) | `OrganisationController:111,406`; `ElectionManagementController` ×5 |
| **I-6** | Two framework defaults point at `/dashboard/roles` but are bypassed — dead configuration that *looks* authoritative | `RouteServiceProvider:24`; `config/fortify.php:63` |
| **I-7** | Priority order encodes business policy implicitly: a voter mid-ballot (P2) outranks organisation context; elections (P3) outrank organisations (P5) | `DashboardResolver:85-185` |
| **I-8** | No logout teardown of organisation context | *Evidence not found* |

---

# 7. Recommendations (discovery-level only — no implementation, no ADR)

Ordered so each answer unblocks the next. **All are decisions for the domain owner, not engineering choices.**

1. **Name the concept.** Decide whether *Organisation Context* becomes a first-class domain concept (a type, one owner) or deliberately stays infrastructure state. **Everything else depends on this.**
2. **Declare one source of truth** among the four in §4, and state precedence for the rest. Candidate on the evidence: `users.organisation_id` (`NOT NULL`, foreign-keyed, already trusted by `TenantContext`) with the session as a derived cache — **a decision, not a finding.**
3. **Resolve R5 at the concept level:** build an organisation-selection page (the rule as written), or amend R5 to state that role selection is the intended experience. **Today the code answers a different question than the rule asks.**
4. **Review the caching of routing decisions (I-4)** — the most likely cause of the instability that prompted this story. Whether cached navigation is acceptable is a product decision.
5. **Decide whether rendering a page may change the active organisation (I-5).** If not, those writes belong behind whichever component owns the concept.
6. **Then** a tactical-modelling story may follow. **This story proposes no code.**

## Explicitly not done

no code changed · no refactoring · no ADR · no architecture proposed · no source of truth chosen · no redirect altered.

---

**Traceability:** `app/Http/Responses/LoginResponse.php:83,96,110,115,149,161,165` · `app/Services/DashboardResolver.php:48,64,68,71,85,94,108,120,130,148,159,185,606,626,683,702,713` · `app/Http/Middleware/TenantContext.php:52,53,65,73,79,83` · `app/Traits/BelongsToTenant.php` · `app/Providers/RouteServiceProvider.php:24` · `config/fortify.php:63` · `app/Http/Controllers/OrganisationController.php:106,111,401,406` + `store()` · `app/Http/Controllers/Election/ElectionManagementController.php:223,404,678,1117,1394` · `app/Http/Middleware/EnsureOrganisationMember.php:149` · `app/Http/Middleware/IdentifyTenantFromHeader.php:51` · `app/Helpers/{tenant.php:74, TenantHelper.php:55, ElectionAudit.php:139}` · `database/migrations/2026_03_05_000002_create_uuid_users_table.php:13,23` · `database/migrations/2026_03_05_000003_create_user_organisation_roles_table.php` · `routes/web.php:466`
