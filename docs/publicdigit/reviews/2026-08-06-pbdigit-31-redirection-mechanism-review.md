# PBDIGIT-31 — Redirection Mechanism Review (Discovery Only)

**Commission:** `docs/publicdigit/backlog/PBDIGIT-31-redirection-mechanism-review.md`
**Date:** 2026-08-06 · **Baseline:** `election-review` @ `6631ad7f` · **Mode:** Reviewer — **zero files modified**
**Question:** *is the current redirection mechanism correct, understandable, deterministic and robust?*
**Business input (not evaluated, only measured against):** `PBDIGIT-30` B1 — approved. Terminology: **Working Organisation**.

> **Constraints honoured:** discovery only · no code changed · no redesign · no new DDD model · facts / interpretation / recommendations separated · *Evidence not found* stated where it applies.
> **Scope:** the organisation redirection mechanism only. Elections, membership and multi-tenancy are **not** redesigned.

---

# 9. Overall Evaluation *(placed first — it is the answer to the commission's question)*

**The mechanism is sophisticated, defensively engineered, and correct for the business rules it was built for — which are not the rules B1 now states.**

It has a single entry point, a documented priority order, configurable caching, timeouts, emergency fallbacks, and analytics. It is not accidental. But **B1's determinism test** — *"ignore the bootstrap organisation, count the real ones, then 0 / 1 / 2+"* — is not the question this mechanism asks. It asks *"what kind of user is this?"* (voter · officer · role-holder · first-timer) and answers with nine ordered priorities.

| Criterion | Rating | Evidence |
|---|---|---|
| **Correctness** (against B1) | **2 / 5** | B1's bootstrap-exclusion is partly implemented (`handleMissingOrganisation` skips the platform org; `hasOwnOrganisation()` filters `type='tenant'`), but **neither B1.3 destination exists**: no *Create or Join* route, no *Organisation Selection* route (§5) |
| **Determinism** | **2 / 5** | the priority chain is deterministic *given the same inputs*, but two caches (`dashboard_resolution_ttl` 300 s; `organisation_data_ttl` 300 s) and a session-freshness threshold (60 s) mean **the same user with the same data can be routed differently depending on cache age** (§7 S-5) |
| **Simplicity** | **1 / 5** | nine priorities, ~1000 lines in one service, plus `LoginResponse` pre-checks, plus a config file, plus middleware, plus an emergency controller. The decision *"where does this user go?"* is spread over at least five collaborators (§4) |
| **Single Responsibility** | **2 / 5** | `DashboardResolver` decides destination **and** caches it **and** logs analytics **and** handles missing organisations **and** resolves voting sessions. `LoginResponse` also decides (rate-limit, verification, maintenance) before delegating |
| **DDD consistency** | **2 / 5** | there is no *Working Organisation* concept; the decision is expressed as routing priorities over Eloquent queries. Two competing definitions of "real organisation" coexist (`is_default` vs `type='tenant'`, §6 F-2) |
| **User Experience** | **2 / 5** | a user with two real organisations is asked which **role** they want, never which **organisation** (§5). A user with zero real organisations lands on a welcome/dashboard page, not on *Create or Join* |
| **Maintainability** | **2 / 5** | heavy logging and configuration aid diagnosis, but nine priorities with implicit business policy, and destinations named in five places (§4), make behaviour changes risky |
| **Robustness** | **4 / 5** | **the strongest dimension.** Timeouts (5 s total, 2 s query), failure counting, static-HTML fallback, an `EmergencyDashboardController`, maintenance-mode handling, `onDelete('restrict')` on the organisation FK, and 403/404 handling in `EnsureOrganisationMember` |

**Weighted verdict:** *robust and well-instrumented, but neither simple nor deterministic in B1's sense, and materially incorrect against B1.3.* **The gap is not quality — it is that the mechanism answers a different question than the business now asks.**

---

# 1. Customer Journey (business language, no code)

```
Register
     ↓                       (the platform gives me a bootstrap membership I never see)
Log in
     ↓
The platform decides where I belong
     ↓
   ┌─ I have no real organisation      → I should be asked to create or join one
   ├─ I have exactly one               → I should simply be inside it
   └─ I have several                   → I should be ASKED which one
     ↓
I work inside one organisation
     ↓
I create another organisation          → I expect to be moved into it
     ↓
I log out
     ↓
I log in again                         → I expect to return where I was working
```

**Two expectations carry the weight:** *"ask me, don't guess"* and *"return me where I was working."*

---

# 2. Business Rule Matrix — rules currently implemented

| # | Rule as implemented | Where | Evidence | Verified? |
|---|---|---|---|---|
| **BR-1** | Unverified email → verification notice | `LoginResponse:110` · `DashboardResolver` P1 `:68` | file:line | inferred (not run) |
| **BR-2** | Too many login attempts → dashboard with error | `LoginResponse:96` | file:line | inferred |
| **BR-3** | Maintenance mode → maintenance route | `LoginResponse:115` · `config/login-routing.php:163-174` | file:line | inferred |
| **BR-4** | **A previously computed destination may be reused without re-deciding** | `LoginResponse:161` · `DashboardResolver:341-346` (`Cache::put`, TTL 300 s) | file:line | inferred |
| **BR-5** | A voter mid-ballot resumes the ballot before anything else | `DashboardResolver` P2 `:94` | file:line | inferred |
| **BR-6** | Election count decides: 1 → election page, 2+ → organisation page | P3 `:120,130` | file:line | inferred |
| **BR-7** | **The bootstrap organisation is excluded from "own organisation"** | `handleMissingOrganisation:967+` (*"User own organisation is platform org — skipping redirect"*) · `User::hasOwnOrganisation():1216` filters `type='tenant'` | file:line | inferred |
| **BR-8** | A user with an own (non-platform) organisation → that organisation's page | `handleMissingOrganisation` → `redirect()->route('organisations.show', $ownOrg->slug)` | file:line | inferred |
| **BR-9** | Not-yet-onboarded platform user → welcome | `handleMissingOrganisation` (`onboarded_at === null`) → `dashboard.welcome` | file:line | inferred |
| **BR-10** | Organisation without an active election → organisation page | P5 `:185` | file:line | inferred |
| **BR-11** | First-time user → welcome | P6 `:606` | file:line | inferred |
| **BR-12** | **Multiple roles → ROLE selection** | P7 `:626` | file:line | inferred |
| **BR-13** | Single role → routed by role | P8 `:683` | file:line | inferred |
| **BR-14** | Fallback → admin dashboard or dashboard | P9 `:702,713` | file:line | inferred |
| **BR-15** | Creating an organisation sets the user's organisation and session | `OrganisationController::store():338` + session write | file:line | inferred |
| **BR-16** | Viewing an organisation re-writes the working context | `OrganisationController:111,406` | file:line | inferred |
| **BR-17** | Non-member reaching an organisation URL → 403 / redirect to dashboard | `EnsureOrganisationMember:81,98,115,135-137` · `TenantContext:73,79` | file:line | inferred |
| **BR-18** | Framework default destination is `/dashboard/roles` | `RouteServiceProvider:24` · `config/fortify.php:63` | file:line | **bypassed** whenever `LoginResponse` runs |

**Every row is "inferred", none "verified"** — no runtime observation was possible. **B1.3's 0-org and 2+-org rules appear nowhere in this matrix** because no implementation of them exists.

---

# 3. Redirection Lifecycle

```
AUTHENTICATION (Fortify)
        ↓
LoginResponse::toResponse()                                    :83
   ├─ rate limited?        → route('dashboard') + error        :96
   ├─ email unverified?    → route('verification.notice')      :110
   ├─ maintenance mode?    → redirectToMaintenanceMode()       :115
   └─ resolveNormalDashboard()                                 :149
          ├─ CACHE HIT  → return cached URL, NO re-decision    :161
          └─ CACHE MISS → DashboardResolver::resolve()         :165
                              ↓
              NINE PRIORITIES, first match wins
              P1 unverified → verification.notice              :68
              P2 mid-ballot → slug.code.create                 :94
              P3 elections  → 1 ⇒ elections.show · 2+ ⇒ organisations.show  :120,130
              P4 no orgs    → handleMissingOrganisation()       :159 → :967
                                 ├─ own org is platform → skip
                                 ├─ own non-platform org → organisations.show
                                 ├─ not onboarded → dashboard.welcome
                                 └─ → dashboard / dashboard.welcome
              P5 org, no election → organisations.show          :185
              P6 first-time  → dashboard.welcome                :606
              P7 many roles  → role.selection                   :626
              P8 one role    → by role                          :683
              P9 fallback    → admin.dashboard / dashboard       :702,713
                              ↓
                    cacheResolution(url, ttl 300 s)             :341-346
                              ↓
                         REDIRECT EXECUTED
                              ↓
        TenantContext middleware (every later request)
          reads users.organisation_id (cache 60 s) → validates membership
          → 403 if absent → writes session + container          :52-53,65,73,79,83
                              ↓
                    WORKING SESSION BEGINS
```

**Note what is absent from this lifecycle:** any step that *counts the user's real organisations and asks them to choose*. B1.3's middle question is never posed.

---

# 4. Decision Point Matrix

| # | Decision made | By | Kind | Evidence |
|---|---|---|---|---|
| D-1 | rate-limit / verification / maintenance pre-checks | `LoginResponse` | response class | `:96,110,115` |
| D-2 | **reuse a cached destination without re-deciding** | `LoginResponse` + `Cache` | cache | `:161` |
| D-3 | the nine-priority destination choice | `DashboardResolver` | service | `:48-713` |
| D-4 | zero-organisation sub-decision (incl. platform-org exclusion) | `DashboardResolver::handleMissingOrganisation` | service (private) | `:967+` |
| D-5 | staleness / session-freshness threshold | `DashboardResolver` + `config/login-routing.php:99-117` (60 s) | config + service | `:267,315` |
| D-6 | cache TTLs (destination 300 s · organisation data 300 s · voting session 30 s) | `config/login-routing.php:15-38` | **configuration** | file |
| D-7 | timeouts and emergency fallback (5 s / 2 s / static HTML / 3 failures) | `config/login-routing.php:45-92` + `EmergencyDashboardController` | config + controller | file |
| D-8 | framework default destination `/dashboard/roles` | `RouteServiceProvider:24` · `config/fortify.php:63` | framework config | **bypassed** |
| D-9 | tenant scope + membership validation (403) | `TenantContext` middleware | middleware | `:65,73,79` |
| D-10 | organisation-URL access (403 / 404 / redirect) | `EnsureOrganisationMember` middleware | middleware | `:56,81,98,115,135` |
| D-11 | working context on organisation creation | `OrganisationController::store():338` + session | controller | file:line |
| D-12 | working context while **rendering** an organisation page | `OrganisationController:111,406` | controller side effect | file:line |
| D-13 | re-resolution after email verification | `Auth/VerificationController:38,69,87` | controller | file:line |
| D-14 | election-related context writes (5 sites) | `ElectionManagementController` | controller | `:223,404,678,1117,1394` |

**Fourteen decision points across five kinds** (response class · service · configuration · middleware · controller side effect). **No single authority.**

---

# 5. Route Map

| Purpose | Route | Note |
|---|---|---|
| **Entry** | Fortify login POST | → `LoginResponse` |
| Framework default | `/dashboard/roles` | `RouteServiceProvider::HOME`, `config/fortify.php:63` — **bypassed in practice** |
| Generic dashboard | `GET /dashboard` → `ElectionManagementController@dashboard` | `routes/web.php:332`, middleware `no.cache`. **The P9/BR-2/BR-17 fallback target** |
| Welcome | `GET /dashboard/welcome` → `WelcomeDashboardController` | `web.php:466` |
| **Role** selection | `role.selection` | `DashboardResolver:626` |
| Organisation page | `GET /organisations/{slug}` → `OrganisationController@show` | `web.php:503` |
| My organisations | `GET /my-organisations` | `web.php:494` |
| Create organisation | `GET /my-organisations/create` · `POST /organisations` | `web.php:496,498` |
| Election page | `elections.show` | via P3 |
| Ballot resume | `slug.code.create` | via P2 |
| Verification notice | `verification.notice` | via P1 |
| Maintenance | `maintenance` | `config/login-routing.php:174` |
| **Create or Join Organisation** (B1.3, 0 orgs) | ❌ **no route found** | searched `create-or-join`, `create_or_join`, `choose-organisation`, `select-organisation` |
| **Organisation Selection** (B1.3, 2+ orgs) | ❌ **no route found** | — |
| **Organisation switch** | ❌ **no route found** | confirmed in `PBDIGIT-29` |

**Loops:** none proven. **Structural risk noted:** `/dashboard` is the fallback for rate-limiting, P9 and both middlewares' error paths, and it is served by `ElectionManagementController@dashboard` — which is *not* the resolver, so a redirect loop is unlikely. **Evidence not found** for any executed loop test.
**Unreachable:** `/dashboard/roles` as a *login* destination (bypassed).
**Duplicated:** organisation routes split across `web.php` (`{slug}`) and `organisations.php` (`{organisation:slug}`) — two binding styles.

---

# 6. Implementation Review (facts, no recommendations)

| # | Observation | Evidence |
|---|---|---|
| **F-1** | The destination decision is spread over **five kinds of collaborator** and 14 decision points (§4) | §4 |
| **F-2** | **Two competing definitions of "a real organisation"**: `is_default = true` / `Organisation::isPlatform()` (`Organisation.php:177,196`, cached in `BelongsToTenant::$platformOrgIdCache`) versus `type = 'tenant'` (`User::hasOwnOrganisation():1216-1221`) | file:line |
| **F-3** | Business policy is encoded as **priority order**: a mid-ballot voter (P2) outranks organisation context; elections (P3) outrank organisations (P5) | `:94,120,185` |
| **F-4** | **A destination can be reused without being re-decided** (cache hit at `LoginResponse:161`), TTL 300 s, configurable | `:161`, `config:20` |
| **F-5** | `DashboardResolver` carries five responsibilities: decide · cache · log analytics · handle missing organisation · resolve voting sessions | `:48-1010` |
| **F-6** | Robustness machinery is real and configurable: timeouts 5 s/2 s/1 s, `max_failures_before_emergency` 3, static-HTML fallback, analytics thresholds 2 s/5 s | `config/login-routing.php:45-155` |
| **F-7** | Session freshness is validated against `last_activity_at` with a 60 s threshold | `config:99-117`, `DashboardResolver:315` |
| **F-8** | `users.organisation_id` FK is `onDelete('restrict')` — an organisation with members **cannot be deleted** | migration `:23-26` |
| **F-9** | `EnsureOrganisationMember` distinguishes JSON (400/404/403) from web (redirect + error) responses | `:78,81,95,98,112,115,135` |
| **F-10** | Heavy structured logging with emoji priority markers throughout the resolver | `:64,89,131,156,179,198,216,233,247` |

---

# 7. Runtime Scenario Matrix

| # | Scenario | Expected (per B1) | Current behaviour | Evidence |
|---|---|---|---|---|
| **S-1** | User has **0 real** organisations | *Create or Join Organisation* page | `handleMissingOrganisation` → own-org checks → `dashboard.welcome` or `dashboard`. **No create/join page exists** | `:967+`; no route |
| **S-2** | User has **1 real** organisation | enter it automatically | usually P5 → `organisations.show` — **but P2 (mid-ballot) and P3 (elections) take precedence** | `:94,120,185` |
| **S-3** | User has **2+ real** organisations | **Organisation Selection** page | P3 sends 2+ *elections* to `organisations.show`; multiple **roles** → `role.selection`. **The user is never asked which organisation** | `:130,626` |
| **S-4** | Only the bootstrap organisation | treated as having none | ✅ **honoured** — platform org skipped | `:967+` |
| **S-5** | **Stale cached destination** | re-decide | a cached URL is returned without re-deciding for up to **300 s**; a 60 s session-freshness check mitigates but does not eliminate | `:161,341-346`; `config:20,105` |
| **S-6** | **Membership revoked** while session live | graceful re-route | **hard 403** from `TenantContext:79`; `EnsureOrganisationMember` redirects web requests to `/dashboard` with an error | `:73,79`; `:135` |
| **S-7** | **Organisation deleted** | graceful re-route | **cannot happen while members exist** — FK `onDelete('restrict')`. Soft-deleted case: `EnsureOrganisationMember` returns *"organisation no longer available"* (404/redirect) | migration `:23`; `:112,115` |
| **S-8** | **Invalid / unknown slug** | 404 or graceful | JSON 404 · web redirect to `/dashboard` with error | `:95,98` |
| **S-9** | **Bookmarked organisation URL, not a member** | refuse clearly | 403 (`:135-137`) or redirect with error | file:line |
| **S-10** | **Infinite redirect** | impossible | **not proven possible** — `/dashboard` is served by a controller, not the resolver. `Evidence not found` for an executed test | §5 |
| **S-11** | Resolver failure / timeout | degrade safely | ✅ timeouts, failure counting, `EmergencyDashboardController`, static-HTML fallback | `config:45-92` |
| **S-12** | Maintenance mode | inform the user | ✅ dedicated route + allow-list | `config:163-180` |

**Four scenarios diverge from B1** (S-1, S-2, S-3, S-5). **Five are handled better than the business rule requires** (S-7, S-8, S-9, S-11, S-12).

---

# 8. Findings and Improvement Opportunities

## 8a. Findings

| # | Finding — *Product findings lead with customer impact* | Class | Type | Priority | Confidence | Business decision | Code | Verified |
|---|---|---|---|---|---|---|---|---|
| **RD-1** | **A user with several organisations is never asked which one to work in** — they are asked which *role*. B1.4's "never guess" is not implemented | **MISSING** | **Product** | **High** | **High** — no route exists | No — B1 already decided | Yes | No |
| **RD-2** | **A user with no real organisation is not invited to create or join one** — they land on a welcome or generic dashboard | **MISSING** | **Product** | **High** | **High** | No — B1 decided | Yes | No |
| **RD-3** | **Where a user lands can depend on a decision taken up to 5 minutes earlier**, not on their current data — **because the invalidation written for exactly this purpose is never wired** (see RD-12) | **BROKEN** | **Product** | **High** | **High** — TTL 300 s in config; observer unattached | **No** — the business intent is already evident from the code that was written | Yes | No |
| **RD-12** | **The routing-cache invalidation exists and is not connected.** `UserOrganisationObserver` handles `created`/`updated`/`deleted`/`restored` on `UserOrganisationRole` and clears eight keys including `dashboard_resolution:{userId}` — it is **imported** at `AppServiceProvider:18` and **never attached**: no `::observe()` call anywhere in `app/`, `bootstrap/`, `config/`; the model has no `booted()` and no `#[ObservedBy]`. **So joining, leaving or changing an organisation does not clear the routing decision** | **BROKEN** | **Product** | **High** | **High** — repo-wide search for the attachment | No | Yes — **one line** | No |
| **RD-4** | **A user whose membership is revoked mid-session meets a hard 403** rather than being moved to another organisation | **INCONSISTENT** | **Product** | Medium | **High** | **Yes** — B9 | Yes | No |
| **RD-5** | Business policy (*a ballot outranks organisation context*) is expressed only as priority order in code | INCONSISTENT | **Architecture** | Medium | **High** | **Yes** — B6 | Yes | No |
| **RD-6** | **Two competing definitions of "a real organisation"** (`is_default` vs `type='tenant'`) | INCONSISTENT | **Architecture** | Medium | **High** | No | Yes | No |
| **RD-7** | The destination decision spans 14 points across 5 collaborator kinds; no single authority | INCONSISTENT | **Architecture** | Medium | **High** | No | Yes | No |
| **RD-8** | `DashboardResolver` holds five responsibilities in ~1000 lines | INCONSISTENT | **Technical** | Medium | **High** | No | Yes | No |
| **RD-9** | `/dashboard/roles` is declared as the framework default and never used for login | INCONSISTENT | **Technical** | Low | **High** | No | Yes | No |
| **RD-10** | Two route-binding styles for organisations across two route files | INCONSISTENT | **Technical** | Low | **High** | No | Yes | No |
| **RD-11** | No executed test of redirect-loop safety | — | **Technical** | Low | **Medium** — absence of evidence, not evidence of a loop | No | No | No |

**Product: RD-1…RD-4.** **Architecture: RD-5…RD-7.** **Technical: RD-8…RD-11.**

## 8b. Improvement Opportunities

**Recommendations improve the existing mechanism. No new architectural concept is proposed** — the evidence does not show the current design *cannot* satisfy B1; it shows two destinations are missing and one decision is cached.

| # | Improvement | Evidence | Business impact | Risk | Priority |
|---|---|---|---|---|---|
| **I-1** | Add the two missing destinations (*Create or Join*, *Organisation Selection*) as **new priorities inside the existing resolver**, keyed on the count of real organisations | RD-1, RD-2; the resolver already has a priority mechanism and already excludes the platform org (`:967+`) | **High** — makes B1.3 real; removes guessing | **Low** — additive; no existing priority changes | **1** |
| **I-2** | **Attach `UserOrganisationObserver` to `UserOrganisationRole`** — the invalidation already written for the routing cache | RD-12; observer clears `dashboard_resolution:{userId}` on created/updated/deleted/restored; imported but never attached | **High** — the routing decision stops outliving the business facts | **Very low** — one line, and the code it activates was written for this purpose | **2** |
| **I-3** | Choose **one** definition of "real organisation" and have both call sites use it | RD-6 — `is_default` vs `type='tenant'` | Medium — prevents the two rules diverging | Low | **3** |
| **I-4** | Decide (business) the revoked-membership experience, then align middleware to it | RD-4, S-6 | Medium — affects trust at a sensitive moment | Medium — touches authorisation paths | **4** |
| **I-5** | Extract the destination decision from the resolver's other four responsibilities | RD-8 | Low for the customer; Medium for change-cost | Medium — the class is central and heavily used | **5** |
| **I-6** | Add one executed redirect-loop test | RD-11 | Low — confirms an assumption | Very low | **6** |

**Deliberately not recommended:** a new *Working Organisation* domain concept, a rewrite of `DashboardResolver`, or removal of the priority model. **The evidence does not justify any of them** — I-1 and I-2 alone close the B1 gap, and both work with the existing mechanism.

---

# Business Outcome

```
Business Outcome

Today     The platform decides where a user lands using nine ordered priorities,
          and it decides well: it excludes the bootstrap organisation, resumes
          interrupted ballots, survives timeouts, and degrades safely. But it
          never asks the user which organisation they want to work in, never
          offers to create one when they have none, and may reuse a routing
          decision taken up to five minutes earlier.

Expected  Ignore the bootstrap organisation, count the real ones, and then:
          none → invite them to create or join · one → put them inside it ·
          several → ASK. Every authenticated request then executes inside
          exactly one Working Organisation.
```

**In one sentence:** the mechanism is robust at *executing* a destination and silent at *asking* for one.

---

# Authorization Boundary

| | |
|---|---|
| Code changed | **none** |
| Architecture proposed | **none** — I-1/I-2 extend the existing resolver and its config |
| New DDD model introduced | **none** |
| Elections / membership / multi-tenancy redesigned | **none** |
| Business rules evaluated | **none** — B1 was taken as input |
| Runtime verification | **not performed** — no browser, no database |
| Status | **STOPPED** — awaiting a decision on I-2 (cached navigation) and authorisation for I-1 |

---

# Method note (application #4)

The frozen 9-phase method was applied with the commission's own phase names, which map onto it 1:1 (customer journey · business rules · lifecycle · decision points · routes · implementation · runtime · improvements · evaluation). **No refinement was adopted** — the freeze gate holds; next retrospective at application #6.

**What surprised us:** I expected B1's bootstrap-exclusion rule to be absent. **It is already implemented** (`handleMissingOrganisation` skips the platform organisation; `hasOwnOrganisation()` filters `type='tenant'`). The gap is narrower and more actionable than predicted — two missing destinations, not a missing concept. Had I assumed absence, I-1 would have been proposed as a redesign instead of two additional priorities.

---

**Traceability:** `app/Http/Responses/LoginResponse.php:83,96,110,115,149,161,165` · `app/Services/DashboardResolver.php:48,64,68,89,94,120,130,131,156,159,179,185,198,216,233,247,267,315,341-346,606,626,683,702,713,903,967+` · `config/login-routing.php:15-38,45-92,99-117,124-155,163-180` · `app/Http/Middleware/TenantContext.php:52,53,65,73,79,83` · `app/Http/Middleware/EnsureOrganisationMember.php:56,78,81,95,98,112,115,135-137` · `app/Models/Organisation.php:177,196` · `app/Models/User.php:1216-1221` · `app/Traits/BelongsToTenant.php:36,52-57,75` · `app/Http/Controllers/OrganisationController.php:111,338,406` · `app/Http/Controllers/Auth/VerificationController.php:38,69,87` · `app/Http/Controllers/EmergencyDashboardController.php:27` · `app/Providers/RouteServiceProvider.php:24` · `config/fortify.php:63` · `routes/web.php:332,466,494,496,498,503` · `database/migrations/2026_03_05_000002_create_uuid_users_table.php:23-26` · business input: `PBDIGIT-30` B1

---

# §10 — Amendment: the cache question, reframed and answered (2026-08-06)

**The reframing, from review of this report:** the right question is not *"should navigation be cached?"* but

> **"Should a routing decision ever outlive the business state that produced it?"**

— and the answer depends entirely on whether the cache is **invalidated when those business facts change**. That reframing is better than the original I-2, and it turned out to be **directly testable**.

## The evidence

| Fact | Where |
|---|---|
| A routing decision is cached for **300 s** (configurable) | `DashboardResolver:341-346` · `config/login-routing.php:20` |
| The cache key prefix is `dashboard_resolution:` | `config/login-routing.php:38` |
| **An invalidator was written for exactly this** — `UserOrganisationObserver` clears `dashboard_resolution:{userId}` plus seven related keys, on `created` / `updated` / `deleted` / `restored` of `UserOrganisationRole` | `app/Observers/UserOrganisationObserver.php:27,47,68,88,135-150` |
| It is **imported** into the service provider | `AppServiceProvider:18` — `use App\Observers\UserOrganisationObserver;` |
| **It is never attached.** No `::observe()` call in `app/`, `bootstrap/` or `config/`; `UserOrganisationRole` has no `booted()` and no `#[ObservedBy]` | repo-wide search |
| A *neighbouring* cache **is** invalidated at organisation creation | `OrganisationController:362` — `Cache::forget("user.{$user->id}.organisation_id")` (the TenantContext cache, **not** the routing cache) |

## What this changes

1. **The answer to the reframed question, for this codebase, is: *yes, it does outlive it* — up to 300 s, on every business change that should have reset it.** Joining, leaving, or changing an organisation does not clear the routing decision.
2. **RD-3 is re-classed from `INCONSISTENT` to `BROKEN`** and no longer needs a business decision: the intent is already visible in the code that was written. The defect is a missing wire, not an undecided policy.
3. **I-2 changes from a policy question to a one-line fix** — attach the observer. Its own docblock names `dashboard_resolution:{user_id}` as *"Main routing cache"*.
4. The deeper cache-policy discussion (*is any navigation caching acceptable at all?*) is **deferred to the implementation review**, as recommended — because once invalidation actually fires, the question may not need answering.

## Correction to this report

**My original RD-3 and I-2 were weaker than the evidence supports.** I framed a stale-cache *risk* and asked for a *business decision*; the reality is an **unwired invalidator**, which is a defect with an owner and a one-line remedy. I also stated in the first version of §7 (S-5) that the freshness check "mitigates but does not eliminate" — that stands, but it understated the cause.

**Observation:** this review and its companions identified **three independent cases where functionality had been implemented but never connected to the execution path** — `GovernanceSetupController` (exists, no route) · `OrganisationCreated` (dispatched, no listeners) · `UserOrganisationObserver` (imported, not attached). **Each should be verified and resolved individually**, on its own evidence; they are listed together only because the same check finds them, not because they share a remedy. *(No pattern count is claimed here — pattern accounting belongs to a method retrospective, not to a product review.)*
