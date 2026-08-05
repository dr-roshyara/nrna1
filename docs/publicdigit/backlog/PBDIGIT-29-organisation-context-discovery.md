# PBDIGIT-29 — Organisation Context Discovery

**Type:** Discovery / Analysis (DDD) · **Epic:** `PBDIGIT-EPIC-01` Organisation Management
**Created:** 2026-08-06 · **Status:** `DISCOVERY COMPLETE — awaiting decisions; no code changed`
**Revision 3** — reordered **business-first** (lifecycle → events → implementation), and closes with a **Business Rule Matrix**. Rev 2 separated facts from interpretation and added the event trace; rev 1 was implementation-first. ID note: commissioned as "PBDIGIT-28"; that ID was already closed and IDs are never reused.

> **Mode honoured:** search/analysis automated · **zero files modified** · every fact cites a file/line · gaps stated as *Evidence not found*.
> **Reading order matters:** §1 states the *intended* business lifecycle (from rules R1–R6, **not** inferred from code). §2–§3 then ask what implements each step. The commission's rule — *"do not assume the lifecycle is correct"* — is honoured by keeping intent and implementation in separate sections and never letting one stand in for the other.

---

# 0. Executive summary

> ## The key insight
> **The platform answers *"which organisation scopes my queries?"* — it does not answer *"which organisation is the user working in?"***
>
> The first is tenancy, answered well: `users.organisation_id` is `NOT NULL` with a foreign key, enforced on every request by `TenantContext`. The second is what rules R3–R5 are about, and **no component answers it.** Almost every symptom follows from that gap.

Two supporting facts:

1. **The business event fires into the void.** `OrganisationCreated` is dispatched (`OrganisationController:351`) but has **no listeners** — absent from `EventServiceProvider::$listen`, with auto-discovery explicitly disabled. Its business consequence is hard-coded **13 lines earlier** at `:338`.
2. **Login routing is cached at two levels**, so identical data can produce different landings (`LoginResponse:161`; `DashboardResolver` cache; plus a 60 s organisation cache).

---

# 1. The intended business lifecycle (from rules R1–R6 — the reference, not a finding)

```
Customer registers
        ↓                                    R1: belongs to ≥1 organisation (default)
Default organisation assigned
        ↓
Customer creates an organisation
        ↓                                    R2: creator becomes a member/owner
Customer becomes owner
        ↓
NEW ORGANISATION BECOMES ACTIVE              R3: consequence of "Organisation Created"
        ↓
Customer works inside that organisation
        ↓
Customer logs out
        ↓
Customer logs in
        ↓                                    R4: one organisation → enter it
ACTIVE ORGANISATION RE-ESTABLISHED           R5: many →选 selection page
        ↓                                    R6: explicit, never guessed
Customer works
```

**This is the yardstick.** §2 and §3 measure the implementation against it; §5 scores each rule.

---

# 2. Business events — intended vs. present in code

| Intended business event | Event class exists? | Dispatched? | Listened to? | Who actually carries the consequence |
|---|---|---|---|---|
| **User Registered** | `Registered::class` (framework) | yes | **yes** — has `$listen` entries incl. `CreateUserOrganisationRole` | listener |
| **Organisation Created** | ✅ `app/Events/OrganisationCreated.php:9` (`readonly Organisation $organisation`) | ✅ once, `OrganisationController.php:351` | ❌ **no listeners** — absent from `$listen`; `shouldDiscoverEvents(): return false` (*"listeners are only registered via `$listen`"*) | **`OrganisationController::store()` itself, imperatively at `:338`** — `$user->update(['organisation_id' => $org->id])` |
| **Active Organisation Changed** | ❌ no such event class found | — | — | 15+ classes writing a session key (§3.2) |
| **Organisation Selected** (R5) | ❌ none | — | — | nothing — no selection surface exists |
| **Membership Revoked** | *Evidence not found* as an event | — | — | `TenantContext:73,79` aborts **403** when membership is absent |

**The answer to *"who is responsible for changing the active organisation?"* is therefore a fact, not an opinion: the controller is, inline. The event carries no responsibility.**

A mailable `OrganisationCreatedMail` exists (`app/Mail/OrganisationCreatedMail.php:13`); **Evidence not found** that any listener sends it in response to the event.

---

# 3. FACTS — how each lifecycle step is implemented (evidence only)

## 3.1 Step-by-step mapping

| Business step (§1) | Implemented by | Evidence |
|---|---|---|
| Default organisation assigned at registration | database constraint — `users.organisation_id` is **`NOT NULL` + FK**; `Registered` listener creates the membership row | `migrations/2026_03_05_000002_create_uuid_users_table.php:13,23` · `EventServiceProvider` `$listen[Registered]` · `CreateUserOrganisationRole` |
| Creator becomes owner | membership write in `store()`, role read at `:109` | `OrganisationController.php` |
| **New organisation becomes active** | `$user->update(['organisation_id' => $org->id])` — imperative, inside `store()` | `OrganisationController.php:338` |
| Customer works inside the organisation | `TenantContext` middleware reads the **column** (cached 60 s), validates membership, then writes session + container | `TenantContext.php:65-69,73,79,52-53,83` |
| Logout | **Evidence not found** — no organisation-context teardown located | — |
| **Login re-establishes context** | `LoginResponse::toResponse()` → `resolveNormalDashboard()` → `DashboardResolver::resolve()`; **a cached URL may be returned without re-resolving** | `LoginResponse.php:83,149,161,165` |
| Landing destination chosen | `DashboardResolver`, **9 priorities**, first match wins: email-unverified → active-voting-session → elections-count → missing-org → org-without-election → first-time → multiple-roles → single-role → fallback | `DashboardResolver.php:68,94,120,130,159,185,606,626,683,702,713` |
| **Organisation selection (R5)** | **no route found** in `routes/`. A **role** selection destination exists (`role.selection`) | routing search · `DashboardResolver.php:626` |

## 3.2 Where "active organisation" actually lives

| Store | Written by | Lifetime | Read by |
|---|---|---|---|
| `users.organisation_id` | registration · `store():338` | durable (`NOT NULL` + FK) | **`TenantContext:65`** |
| session `current_organisation_id` | **15+ classes** (below) | session | `BelongsToTenant`, `Helpers/tenant.php:74`, `TenantHelper:55`, `ElectionAudit:139` |
| cache `user.{id}.organisation_id` | `TenantContext:65` | **60 s** | `TenantContext` |
| container `current.organisation_id` | `TenantContext:53` | request | global scope plumbing |
| `user_organisation_roles` | membership flows | durable | **access authority** — `TenantContext:73` → 403 at `:79` |

**Session-key writers:** `OrganisationController` ×2 (`:111`, `:406` — while *rendering* pages) · `ElectionManagementController` ×5 (`:223,404,678,1117,1394`) · `DemoVoteController` ×3 · `DemoResultController` ×2 · `DemoCodeController` · `CodeController:66` · `VoteController:285` · `TenantContext` ×2 · `EnsureOrganisationMember:149` · `IdentifyTenantFromHeader:51` · `DemoSetupController:53`.

## 3.3 Other facts

- **No class, interface, value object or aggregate** named `OrganisationContext`, `CurrentOrganisation` or `ActiveOrganisation` was found; those words occur only as prose/log text in `BelongsToTenant.php` and `DashboardResolver.php`.
- `RouteServiceProvider::HOME` (`:24`) and `config/fortify.php:63` both name `/dashboard/roles`; both are bypassed whenever `LoginResponse` runs.
- **Evidence not found:** the redirect target of `store()`.

---

# 4. INTERPRETATION (analysis — distinct from §1–§3)

1. **The business concept appears to be represented implicitly** — spread across four stores with no declared precedence. *(The fact is only that no such class was found.)*
2. **The dispatched-but-unlistened event suggests an intention never completed.** Someone modelled "Organisation Created" as a business event, then implemented its consequence imperatively. That is consistent with repeated re-implementation: the event *looks* like the extension point, but behaviour changes require editing the controller.
3. **Two different questions are answered by two different mechanisms**, and neither answers the business one (§0).
4. **Cached routing decisions plausibly explain the observed instability** — a cached URL survives data changes for its lifetime. **Plausible, not demonstrated:** no reproduction was attempted and `LoginResponse`'s cache keys/TTL were not traced.
5. **R6 is not satisfied** — four stores, no precedence, fifteen writers ⇒ the effective value is whatever wrote last. *(Depends on reading R6 as requiring a single authority.)*

---

# 5. Business Rule Matrix

Four independent levels of confidence: **designed** (a recorded intent) · **implemented** (code exists) · **verified** (observed running) · **automated test** (protected against regression).

| # | Business rule | Designed | Implemented | Verified | Automated test | Evidence |
|---|---|---|---|---|---|---|
| **R1** | Every user belongs to ≥1 organisation (default) | ✅ schema constraint | ✅ | ⬜ | *Evidence not found* for a rule-level test | `migrations/…create_uuid_users_table.php:13,23` (`NOT NULL` + FK) · `Registered` → `CreateUserOrganisationRole` |
| **R2** | Creating an organisation makes the user a member/owner | ✅ | ✅ | ⬜ | *Evidence not found* | `OrganisationController::store()`; role read `:109` |
| **R3** | After *Organisation Created*, that organisation becomes active | ⚠️ **modelled as an event, realised imperatively** | ⚠️ in effect | ⬜ | ❌ none found | `:338` update · `:351` dispatch with **no listeners** (§2) |
| **R4** | Single organisation on login → enter it automatically | ✅ | ⚠️ conditional — P2/P3 outrank it | ⬜ | ✅ **`test_user_with_single_org_role_redirects_to_organisation`** | `tests/Feature/Auth/DashboardResolverTest.php:44` · `DashboardResolver` P5 |
| **R5** | Multiple organisations → selection page | ❌ no surface designed | ❌ | ⬜ | ⚠️ **a test exists — for the wrong question**: `test_user_with_multiple_roles_redirects_to_role_selection` asserts **role** selection | `DashboardResolverTest.php:66` |
| **R6** | Active organisation is explicit, never guessed | ❌ | ❌ | ⬜ | ⬜ | 4 stores · no precedence · 15+ writers (§3.2) |

**Legend:** ✅ present · ⚠️ partial / present-but-misaligned · ❌ absent · ⬜ not assessed. **The Verified column is empty for every row** — no runtime verification was performed here (no browser, no reproduction). That is `PBDIGIT-00`'s job.

**What the matrix makes visible:**
- Two rules are unmet (R5, R6); two are met only incidentally (R3, R4).
- **Nothing is verified** — so even ✅ rows are claims about code, not behaviour.
- **R5's row is the sharpest finding in this story:** a regression test exists and it *locks in the substitute*. `DashboardResolverTest:66` asserts that multiple **roles** lead to **role** selection. So the code isn't merely missing organisation selection — the current behaviour is **protected by a test that encodes the wrong question**. Changing it will require changing a passing test, which is exactly why the behaviour keeps reverting.

## ⚖️ Correction to §4.4 — the caching is deliberate, not accidental

`tests/Feature/Auth/DashboardResolverTest.php` contains **`test_dashboard_resolution_is_cached`** (`:100`) and **`test_stale_cache_not_used`** (`:120`), plus `test_active_voting_session_takes_priority` (`:141`) and `test_tenant_context_is_set_on_organisation_redirect` (`:239`).

**This materially tempers interpretation §4.4 and inconsistency I-7.** Cached routing and the priority order are **intentional, tested design decisions** — someone considered staleness explicitly. So:

- The honest statement is **not** *"caching causes instability"* but *"caching is intended, and whether the intent matches the business rule is an open question (Q6)"*.
- I-7 (implicit priority policy) stands as an observation about *where* the policy is expressed, **not** as a claim that it was unconsidered.

*(Recorded as a correction rather than edited away — the earlier reading was weaker than the evidence now supports.)*

---

# 6. Inconsistencies

| # | Inconsistency | Evidence |
|---|---|---|
| **I-1** | 15+ classes write the context key directly, bypassing `TenantContext` | §3.2 |
| **I-2** | `TenantContext` reads the **column**; helpers/`BelongsToTenant`/audit read the **session** | `TenantContext:65` vs `Helpers/tenant.php:74`, `TenantHelper:55` |
| **I-3** | R5 has no implementation surface | §3.1 |
| **I-4** | Routing decisions cached at two levels (+60 s org cache) | `LoginResponse:161`; `DashboardResolver:71-79` |
| **I-5** | Context rewritten as a side effect of *rendering* a page | `OrganisationController:111,406` |
| **I-6** | Two framework defaults name `/dashboard/roles` but are bypassed — dead config that looks authoritative | `RouteServiceProvider:24`; `config/fortify.php:63` |
| **I-7** | Priority order encodes policy implicitly — mid-ballot (P2) outranks organisation context; elections (P3) outrank organisations (P5) | `DashboardResolver:85-185` |
| **I-8** | `OrganisationCreated` dispatched with zero listeners; consequence hard-coded in the dispatching method | §2 |
| **I-9** | No logout teardown of organisation context | *Evidence not found* |

---

# 7. Open questions for decision — *not* recommendations

**Discovery does not decide these, and does not presuppose that a new concept is required.** One well-owned field may suffice.

| # | Question | Grounding |
|---|---|---|
| Q1 | Is "active organisation" **one field**, or a concept with behaviour? | §3.3 — nothing is named today |
| Q2 | Which store is authoritative, and what precedence do the others have? | §3.2. Evidence *favours* `users.organisation_id` (NOT NULL, FK, already trusted by `TenantContext`) — **the evidenced candidate, not a recommendation** |
| Q3 | Build R5 (organisation selection) or amend it (role selection is intended)? | I-3 |
| Q4 | Should `OrganisationCreated` carry the consequence (a listener), or is imperative handling intended? | I-8 — determines whether the event is an extension point or decoration |
| Q5 | May rendering a page change the active organisation? | I-5 |
| Q6 | Is cached navigation acceptable? | I-4 |

**Successor:** `PBDIGIT-30 — Active Organisation Business Lifecycle Discovery` (B1–B9) answers the *business* side before any of this becomes design.

## Explicitly not done

no code changed · no refactoring · no ADR · no architecture proposed · no source of truth chosen · no redirect altered · **no concept named** · **no runtime verification** (see the empty Verified column, §5).

---

**Traceability:** `app/Events/OrganisationCreated.php:9,13` · `app/Providers/EventServiceProvider.php` (`$listen`, `shouldDiscoverEvents`) · `app/Mail/OrganisationCreatedMail.php:13` · `app/Http/Controllers/OrganisationController.php:106,109,111,338,351,401,406` · `app/Http/Responses/LoginResponse.php:83,96,110,115,149,161,165` · `app/Services/DashboardResolver.php:48,64,68,71,85,94,108,120,130,148,159,185,606,626,683,702,713` · `app/Http/Middleware/TenantContext.php:52,53,65,73,79,83` · `app/Http/Middleware/{EnsureOrganisationMember.php:149, IdentifyTenantFromHeader.php:51}` · `app/Http/Controllers/Election/ElectionManagementController.php:223,404,678,1117,1394` · `app/Traits/BelongsToTenant.php` · `app/Helpers/{tenant.php:74, TenantHelper.php:55, ElectionAudit.php:139}` · `app/Providers/RouteServiceProvider.php:24` · `config/fortify.php:63` · `database/migrations/2026_03_05_000002_create_uuid_users_table.php:13,23` · `database/migrations/2026_03_05_000003_create_user_organisation_roles_table.php` · `routes/web.php:466`
