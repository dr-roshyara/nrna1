# Organisation Capability Review

**Capability:** Organisation Management (`PBDIGIT-EPIC-01`) · **Method:** the 9-phase capability review — **application #2** (Election was #1)
**Date:** 2026-08-06 · **Baseline:** `election-review` @ `bd9e1fde` · **Mode:** Reviewer — **zero files modified**
**Placement:** derived — `--scope=product-specific --domain=publicdigit` → `docs/publicdigit`
**Scope note:** *organisation context after login* is already covered by `PBDIGIT-29`/`PBDIGIT-30` and is **not repeated here** — this review covers creating, configuring and governing an organisation.

---

# Phase 0 — Customer journey (goals and expectations · no rules, no events, no code)

```
"We're an association. We want to govern ourselves on this platform."
        ↓
I create our organisation           → I expect to own it, immediately
        ↓
I set it up the way we work         → I expect to describe OUR structure:
                                       our membership rules, our languages,
                                       our geography, our committees
        ↓
I declare our governance            → I expect the platform to know how we
                                       are governed, and to enforce it
        ↓
I invite our people                 → I expect them to join and get roles
        ↓
I look at one screen                → I expect to see the state of my
                                       organisation at a glance
        ↓
We run elections                    → I expect our governance to apply
```

**The expectation carrying the most weight:** *"I declare our governance and the platform enforces it."* Phase 2 shows this is where the capability breaks.

---

# Phase 1 — Business lifecycle (business events)

Two lifecycles exist in this capability. The first is realised; **the second is declared but cannot run.**

```
LIFECYCLE A — Organisation existence
    Organisation Created  →  Owner Assigned  →  Members Join  →  Roles Assigned

LIFECYCLE B — Organisation governance (schema-declared)
    pending_setup  →  governance_configured  →  active
                                              ↘ suspended
```

Lifecycle B's states are declared verbatim in the schema: *"pending_setup, governance_configured, active, suspended"*, default **`pending_setup`** (`migrations/2026_05_07_000001_add_governance_status_to_organisations.php:12-15`).

---

# Phase 2 — Business events · FACTS

| Business event | Class exists? | Dispatched? | Listened to? | Who carries the consequence |
|---|---|---|---|---|
| **Organisation Created** | ✅ `app/Events/OrganisationCreated.php` | ✅ `OrganisationController.php:351` | ❌ **none** (absent from `$listen`; `shouldDiscoverEvents(): false`) | `OrganisationController::store()`, inline |
| **Owner Assigned** | ❌ no event | — | — | `UserOrganisationRole::create([... 'role' => 'owner'])` inside `store()` |
| **Governance Configured** | ❌ no event | — | — | `GovernanceSetupController:102` — **which is unreachable** (§3) |
| **Governance Activated** | ❌ no event | — | — | **nothing** — no code writes `'active'` |
| **Member Joined** | ❌ (Membership context has its own events) | — | — | join routes / invitation acceptance |

## 🔴 The capability's central finding

**Lifecycle B is declared, permissioned, and unreachable.**

| Fact | Evidence |
|---|---|
| Schema declares four states, defaults to `pending_setup` | migration `:12-15` |
| `GovernanceSetupController` writes `governance_configured` + `governance_configured_at` + `governance_configured_by` | `GovernanceSetupController.php:100-104` |
| **That controller has zero references anywhere in the repository** — no route, no test, no service call. Searched all of `.` excluding `vendor` | only its own class declaration matches |
| **No code anywhere writes `'active'`** | repository search |
| **No code anywhere writes `'suspended'`** | repository search |
| A policy grants permission to *set up* governance | `OrganisationPolicy::setupGovernance():65-71` (owner ∧ status ≠ `active`) |
| A policy grants permission to *activate* governance | `OrganisationPolicy::activateGovernance():77-83` (owner ∧ status **=== `governance_configured`**) |

**Consequence, stated plainly:** every organisation is permanently `pending_setup`. `activateGovernance` is **permanently unreachable** — it requires a state that no code can produce. The platform has *permissions* for a governance lifecycle, a *controller* for it, and a *schema* for it, but **no path through it.**

---

# Phase 3 — Route map

| Business step | Route | Controller |
|---|---|---|
| See my organisations | `GET /my-organisations` | `OrganisationController@index` (`routes/web.php:494`) |
| Create form | `GET /my-organisations/create` | `@create` (`web.php:496`, method `:52`) |
| **Create** | `POST /organisations` | `@store` (`web.php:498`, method `:264`) |
| View organisation | `GET /organisations/{slug}` | `@show` (`web.php:503`, method `:69`) |
| Join (public) | `GET`/`POST /organisations/{slug}/join` | `routes/organisations.php:54,58` |
| Accept participant invitation | `GET /participant-invitations/{token}` | `ParticipantInvitationController@accept` (`:66`) |
| Settings | `GET /settings` · `PATCH /settings/membership-mode` · `PATCH /settings/language` | `OrganisationSettingsController` (`:97-99`) |
| Geography config | `GET /geography/cascader-config` | `OrganisationGeographyController` (`:102`) |
| Participants | `GET`/`POST`/`DELETE /participants…` | `OrganisationParticipantController` (`:137-139`) |
| Roles | `GET /roles` · `POST /roles/assign-officer` · `POST /roles/remove-officer` | `OrganisationRoleController` (`:142-145`) |
| Participant import | `GET /import` · `/import/template` | `ParticipantImportController` (`:149-150`) |
| Membership types | `GET`/`POST /membership-types` | `MembershipTypeController` (`:111-113`) |
| Governance **levels** config | `/governance/levels…` (admin/owner) | `Admin\Governance*` (`:342-355`) |
| **Governance setup (Lifecycle B)** | ❌ **no route** | `GovernanceSetupController` — unreachable |
| Exports | `/participants/export` · `/members/export` · `/users/export` | `:182,186`; `web.php:529` |

**Route-quality observations:** organisation routes are split across `routes/web.php` (create/show) and `routes/organisations.php` (everything else), with `web.php` using `{slug}` and `organisations.php` using `{organisation:slug}` — two binding styles for the same concept.

---

# Phase 4 — Implementation map · FACTS

**`OrganisationController::store()` (`:264`)** — the only write path into Lifecycle A:

| Step | Evidence |
|---|---|
| Validates `name` (`required|string|min:3|max:255`), `email` (`nullable|email|max:255`) | `:275-277` |
| Wraps everything in `DB::transaction` | `:287` |
| Generates the slug with `Str::slug($request->name)`, then loops `while (Organisation::where('slug',…)->exists())` to de-duplicate | `:289,294` |
| Creates the organisation | `:305` |
| Creates the owner membership row `UserOrganisationRole{role: 'owner'}` | `:324` *(the second `'role' => 'owner'` nearby is a **log statement**, not a second write — verified)* |
| **Sets the user's active organisation imperatively** | `:338` — `$user->update(['organisation_id' => $org->id])` |
| Dispatches `OrganisationCreated` | `:351` — **no listeners** |

**Organisation aggregate state** (`app/Models/Organisation.php:17-38`): `name · email · slug · type · is_default · address · representative · settings · languages · default_language · logo · uses_full_membership · committee_structure · geographic_scope · allowed_countries · base_country_code · base_region_id · geographic_levels · governance_status · governance_configured_at · governance_configured_by`.

**Authorisation surface** (`OrganisationPolicy`, 12 capabilities): `view · update · manageMembership · manageCommittee · setupGovernance · activateGovernance · viewApplications · approveApplication · rejectApplication · recordFeePayment · manageMembershipTypes · initiateRenewal`.

---

# Phase 5 — DDD analysis · INTERPRETATION

1. **`Organisation` is a data-shaped aggregate, not a behaviour-shaped one.** It carries 21 fillable attributes spanning identity, branding, localisation, geography, membership policy and governance state, with no state-transition methods. Compare `Challenge`/`Determination` in the certified core, which expose guarded transitions and refuse illegal ones.
2. **Governance state has no owner.** A string column with four declared values, mutated (in principle) from a controller, with policies reading it. In the certified core the equivalent — election lifecycle — has a constitution, an enum, and an engine.
3. **The same event pattern as the Election capability recurs:** an event class exists, is dispatched, has no listeners, and its business consequence is hard-coded in the dispatching controller. **This is now observed twice** (`OrganisationCreated` here; the identical shape in `PBDIGIT-29`).
4. **Configuration is settings-driven, not policy-driven** — membership mode, languages and geography are PATCHed onto a model; nothing records *why* a configuration is valid, and no invariant constrains combinations.
5. **Interpretation of the unreachable lifecycle:** the schema comment, the controller and the two policy methods indicate a designed feature whose wiring was never completed — not an accidental omission. **Plausible, not demonstrated:** no history analysis was performed to establish when or why.

---

# Phase 6 — Code quality (only where it touches this capability)

| Observation | Evidence |
|---|---|
| **Dead controller** — `GovernanceSetupController` is unreachable production code | §2 |
| **Unreachable policy method** — `activateGovernance()` can never return `true` | `OrganisationPolicy:77-83` |
| Two route-binding styles for one concept (`{slug}` vs `{organisation:slug}`) across two route files | Phase 3 |
| `store()` mixes validation, slug generation, persistence, role creation, context mutation, logging and event dispatch in one method | `:264-351` |
| Slug de-duplication is a query-in-a-loop | `:294` |
| **41 test files** match "Organisation", including a `tests/Feature/Organisation/` directory and dedicated creation tests (CSRF · database · email · error) — **and `.bak` duplicates of several** (`OrganisationCreationCsrfTest.php.bak`, etc.) | `tests/Feature/` listing |

---

# Phase 7 — Runtime verification

**Not performed. No browser, no database in this environment** — the same limitation recorded at PB003 certification and in `PBDIGIT-29`.

**What runtime verification must answer here:**
1. Does `POST /organisations` land the customer *inside* the new organisation? (`PBDIGIT-29` F-3 says maybe not.)
2. Is `governance_status` observably stuck at `pending_setup` for every organisation? (Predicted yes.)
3. Which capability is denied because `activateGovernance` cannot pass?
4. Does the organisation dashboard reflect real counts? (Root `CLAUDE.md` self-reports **Admin Dashboard 40%**.)

---

# Phase 8 — Business Outcome

```
Business Outcome

Today     A customer can create an organisation and own it, invite people, assign
          roles, and set membership mode, language and geography. But the
          organisation's GOVERNANCE can never be declared or activated: it is
          permanently "pending_setup". The platform holds permissions for a
          governance lifecycle it cannot execute.

Expected  A customer declares how their organisation is governed, activates it,
          and the platform enforces that governance from then on.
```

**In one sentence:** the platform lets a customer *own* an organisation, but not *constitute* one.

---

# Business Rule Matrix

| # | Business rule | Designed | Implemented | Verified | Automated test | Evidence |
|---|---|---|---|---|---|---|
| **O1** | A user can create an organisation and becomes its owner | ✅ | ✅ | ⬜ | ✅ dedicated creation tests (CSRF/database/email/error) | `store():264,324` · `tests/Feature/OrganisationCreation*Test.php` |
| **O2** | Organisation names/slugs are unique | ✅ | ✅ | ⬜ | *Evidence not found* for a rule-level test | `store():289,294` de-dup loop |
| **O3** | Only owners/admins may update the organisation | ✅ | ✅ | ⬜ | *Evidence not found* | `OrganisationPolicy::update():24` |
| **O4** | An organisation declares its membership mode, languages and geography | ✅ | ✅ | ⬜ | *Evidence not found* | `OrganisationSettingsController` (`:97-99`) · `OrganisationGeographyController` (`:102`) |
| **O5** | People can join and be given roles | ✅ | ✅ | ⬜ | *Evidence not found* | join `:54,58` · invitations `:66` · roles `:142-145` |
| **O6** | **An organisation's governance can be configured** | ✅ schema + controller + policy | ❌ **unreachable — no route** | ⬜ | ❌ none | `GovernanceSetupController:102`, zero references |
| **O7** | **Configured governance can be activated** | ✅ policy exists | ❌ **impossible** — requires a state nothing writes | ⬜ | ❌ none | `OrganisationPolicy:77-83` |
| **O8** | Governance can be suspended | ⚠️ declared in the schema comment only | ❌ | ⬜ | ❌ | migration `:15` |
| **O9** | The owner sees the organisation's state at a glance | ✅ | ⚠️ self-reported **40%** | ⬜ | *Evidence not found* | root `CLAUDE.md`; multiple dashboard controllers |

**Verified column: empty on every row** — nothing in this capability has been observed running.

---

# Findings Table

| # | Finding | Type | Priority | Needs business decision | Needs code | Verified |
|---|---|---|---|---|---|---|
| **G-1** | **Governance lifecycle unreachable** — `GovernanceSetupController` has no route; `governance_status` never leaves `pending_setup` | **Product** | **High** | **Yes** — is Lifecycle B still wanted? | Yes | No |
| **G-2** | **`activateGovernance` is permanently false** — it requires `governance_configured`, which nothing can write | **Product** | **High** | Yes (follows G-1) | Yes | No |
| **G-3** | Two of four declared governance states (`active`, `suspended`) are written by nothing | **Product** | **High** | **Yes** — are they real states? | Yes | No |
| **G-4** | Organisation dashboard self-reported at **40%** — the owner's "state at a glance" is incomplete | **Product** | Medium | **Yes** — what must it show? | Yes | No |
| **G-5** | `OrganisationCreated` dispatched with zero listeners; consequence inline in the controller — **the second occurrence of this pattern** (also `PBDIGIT-29` F-9) | **Architecture** | Medium | Maybe | Yes | No |
| **G-6** | `Organisation` is a 21-attribute data aggregate with no state-transition behaviour | **Architecture** | Medium | No | Yes | No |
| **G-7** | Dead production code: `GovernanceSetupController` (unreachable) | **Technical** | Low | No | Yes | No |
| **G-8** | Two route-binding styles for one concept across two route files (`{slug}` vs `{organisation:slug}`) | **Technical** | Low | No | Yes | No |
| **G-9** | `store()` does seven things in one method (validate · slug · persist · role · context · log · dispatch) | **Technical** | Low | No | Yes | No |
| **G-10** | `.bak` duplicates of four organisation test files | **Technical** | Low | No | Yes | No |

**Product findings: G-1…G-4** — all four need a business answer before code.
**Architecture: G-5, G-6** · **Technical: G-7…G-10.**

---

# Cross-capability pattern watch (observation only — no new phase)

Two capabilities reviewed; **two recurrences already**:

| Pattern | Election (`PBDIGIT-29`) | Organisation (this review) |
|---|---|---|
| **Event dispatched with no listeners; consequence hard-coded in the dispatching controller** | F-9 (`OrganisationCreated`… same event, seen from the login side) | G-5 |
| **A declared state that no code can reach** | `begin_setup` has no HTTP route (rev 3 §3.1) | `governance_configured` / `active` / `suspended` (G-1…G-3) |

**Not promoted, not generalised.** Recorded so the third review (Membership) can confirm or refute. Per the promotion rule, two occurrences is *repeated observation*, not a standard.

---

# Recommended backlog items (for approval — not created by this review)

| Proposed | Title | Type |
|---|---|---|
| `PBDIGIT-31` | Organisation governance lifecycle: decide whether Lifecycle B is wanted, then wire or remove it (G-1…G-3, G-7) | business decision → code |
| `PBDIGIT-32` | Organisation dashboard completeness — define what the owner must see (G-4) | business decision → code |

**Not created yet:** per the End of Commission rule, discovered work becomes a backlog item — but these two need one word of approval on *scope* first, since G-1 could equally mean "delete the dead feature" or "finish it".

## Explicitly not done

no code changed · no refactoring · no ADR · no architecture proposed · no runtime verification · no backlog items created · **`PBDIGIT-29`/`30` scope not duplicated**.

---

**Traceability:** `app/Http/Controllers/OrganisationController.php:52,69,264,275-277,287,289,294,305,324,338,351` · `app/Http/Controllers/GovernanceSetupController.php:15,25,100-104` (unreachable) · `app/Policies/OrganisationPolicy.php:14,24,35,46,65-71,77-83,89,100,111,122,133,144` · `app/Models/Organisation.php:17-50` · `app/Events/OrganisationCreated.php` · `app/Providers/EventServiceProvider.php` · `database/migrations/2026_05_07_000001_add_governance_status_to_organisations.php:12-19` · `routes/web.php:494,496,498,503,529` · `routes/organisations.php:54,58,66,97-99,102,111-113,137-139,142-145,149-150,182,186,341-355` · `tests/Feature/OrganisationCreation{Csrf,Database,Email,Error}Test.php` · `tests/Feature/Organisation/` · root `CLAUDE.md` (dashboard 40%)
