# Journey check — a person creates an organisation and visits its homepage

**Story checked:** *"I want to put my organisation on the platform and then stand on its homepage."*
**Date:** 2026-08-08 · **Baseline:** branch `election-review` · **Mode:** Reviewer — **zero files modified, static evidence only** (no browser, no database in this environment — same limitation recorded at `PBDIGIT-29` and in the Organisation capability review)
**Builds on:** `../reviews/2026-08-06-organisation-capability-review.md` (application #2 of the frozen 9-phase method). **That review's scope is not repeated here** — this check walks *one narrow journey* through it and records what is new.
**Backlog item raised:** [`PBDIGIT-63`](PBDIGIT-63-verify-create-organisation-journey-and-repair-its-test-safety-net.md)

---

## Business Outcome (read this first)

```
Business Outcome

Today     Every step of "create an organisation → land on its homepage" exists in
          code, is wired end-to-end, and nothing was found that would statically
          stop a customer from completing it. But it has NEVER been observed
          running — and the automated tests that claim to protect it assert a
          product that no longer exists (a JSON API returning 201, an 'admin'
          role, invitation mails), so a regression would NOT be caught.

Expected  A customer creates their organisation, lands on its homepage as owner,
          and a broken step would fail a test before it reaches them.
```

**Verdict for the journey: `Ready for verification` — with a safety net that protects the wrong product.**

---

## 1 · The journey as implemented — FACTS (step → route → code)

### Step 0 — the person finds the create form

| Fact | Evidence |
|---|---|
| Entry links exist on the welcome dashboard, the "My Organisations" page and a dedicated tutorial page | `resources/js/Pages/Dashboard/Welcome.vue` · `resources/js/Pages/Organisations/Index.vue` · route `/organisation-create-tutorial` (`routes/web.php:265`) |
| The form requires login but **not** a verified e-mail address | routes sit inside the `Route::middleware(['auth'])` group only (`routes/web.php:461,493-499`) |

### Step 1 — the create form

| Fact | Evidence |
|---|---|
| `GET /my-organisations/create` → `OrganisationController@create` → Inertia page `Organisations/Create` | `routes/web.php:496` · `OrganisationController.php:54-57` |
| The page exists and submits with `router.post(...)` + `FormData` — the correct Inertia 2.0 pattern, not raw fetch | `resources/js/Pages/Organisations/Create.vue:288,339-355` |
| Fields offered: name (required), email, representative, languages, logo, `uses_full_membership` | `Create.vue:294-301` |

### Step 2 — creation

`POST /organisations` → `OrganisationController::store()` (`routes/web.php:498` · `OrganisationController.php:280`):

| What happens | Evidence |
|---|---|
| Inline validation (`name` required, others optional) | `:291-299` |
| Everything inside one `DB::transaction` | `:303` |
| Slug generated and de-duplicated in a query loop | `:305-312` |
| `Organisation::create` with `type = 'tenant'` | `:321-331` |
| Creator becomes **owner** via `UserOrganisationRole` pivot row | `:340-344` |
| The user's working organisation is switched (`users.organisation_id`) | `:354` |
| `OrganisationCreated` event dispatched — **zero listeners** (already on record as G-5) | `:367` · `EventServiceProvider` |
| The `TenantContext` cache for this user is cleared — key matches the middleware's key exactly | `:378` ↔ `app/Http/Middleware/TenantContext.php:66` |
| **Redirect to the organisation homepage** | `:380` — `redirect()->route('organisations.show', $org->slug)` |

### Step 3 — the organisation homepage

| Fact | Evidence |
|---|---|
| `GET /organisations/{slug}` sits behind `auth` + `ensure.organisation` | `routes/web.php:501-503` |
| The middleware resolves the slug, requires a `user_organisation_roles` row, sets `session(current_organisation_id)` — the pivot row from step 2 satisfies it | `app/Http/Middleware/EnsureOrganisationMember.php:62-149` |
| `show()` renders `Organisations/Show` with stats, elections, officers, membership widget | `OrganisationController.php:69-271` |
| **All 14 imports of `Show.vue` resolve** (all 8 partials present; `ElectionLifecycleStates` exists as `.ts`, which Vite resolves by default) — the `PBDIGIT-27`/`PBDIGIT-46` failure classes do not apply to this page today | `resources/js/Pages/Organisations/Show.vue:422-441` · `Partials/` listing · `resources/js/Constants/ElectionLifecycleStates.ts` |
| **Fresh-organisation edge cases are null-safe:** a creator with no residence geo unit gets an empty geo chain, not an exception; zero members/elections produce zero counts | `SessionMemberGeoPathProvider.php:27-29` · `show():191-209` |
| `users.id` is a UUID, so `MemberId::fromString((string) $user->id)` (which requires UUID/ULID) accepts every real user | `app/Models/User.php:40,47-48` · `MemberId.php:11-15` |

**Conclusion of section 1:** the chain *create form → store → redirect → homepage render* is complete in code. **No statically detectable blocker was found on the happy path.** That is a statement about code, not about runtime: **nobody has recorded this journey working** (capability review Phase 7 was not performed; `PBDIGIT-00` walked the *voting* journey, not this one).

---

## 2 · New findings — beyond the 2026-08-06 capability review

Facts first, reading of them second. Class per the frozen method: **BROKEN** = exists but cannot execute · **MISSING** = wanted, never built · **INCONSISTENT** = works, described more than once.

### F-1 🔴 The journey's automated tests assert a product that no longer exists

**Customer impact: a regression in organisation creation would not be caught before a customer hits it.**

| The tests assert | The code does | Evidence |
|---|---|---|
| `201` + JSON `success: true` + JSON `redirect` key | `302` redirect, no JSON | `tests/Feature/OrganisationCreationIntegrationTest.php:66-67,87` · `OrganisationCreationTest.php:97-98` vs `store():380` |
| creator pivot role **`admin`** | role **`owner`** | `IntegrationTest:78` vs `store():343` |
| `created_by` set on the organisation | never written by `store()` | `OrganisationCreationTest.php:105` |
| `address` persisted (`address['city']`) | `store()` neither validates nor writes an address | `OrganisationCreationTest.php:108-109` vs `store():291-299,321-331` |
| `RepresentativeInvitationMail` sent | no mail is sent; the only event has no listeners | `IntegrationTest:84` vs `store():367` |
| `GET /organisations/{slug}` returns JSON | it returns an Inertia page | `IntegrationTest:90-93` vs `show():248` |

**Class:** INCONSISTENT · **Type:** Product (it is the journey's safety net) · **Priority:** High · **Confidence:** High — every row is a `file:line` pair.
**This corrects the record:** the capability review's Business Rule Matrix row **O1** cited "✅ dedicated creation tests" as automated-test evidence. The files exist, but they encode a *former* JSON-API contract — the evidence is weaker than recorded. Same pattern `PBDIGIT-36` established for the voting journey, now observed on this journey too.

### F-2 🟡 Session cookies and CSRF token are written to the application log on every creation attempt

`store()` logs `$request->all()` **and** `$request->headers->all()` at `info` level before doing anything (`OrganisationController.php:282-288`) — the `Cookie` header (session id) and CSRF token land in `laravel.log` for every submission, valid or not.
**Class:** INCONSISTENT (debug logging left in production code) · **Type:** Technical (hygiene) · **Priority:** Medium-High · **Confidence:** High. Belongs to the same hygiene family as `PBDIGIT-37`.

### F-3 The customer is shown the raw exception message

On any failure, `back()->withErrors(['error' => 'Creation failed: ' . $e->getMessage()])` (`:387`) surfaces database/internal error text to the customer. **Class:** INCONSISTENT · **Type:** Technical · **Priority:** Medium · **Confidence:** High.

### F-4 Two competing definitions of a valid organisation

`app/Http/Requests/StoreOrganisationRequest.php` (193 lines: array-format rules, custom closures, input normalisation, translated attribute names) has **zero references outside its own file** — `store()` validates inline with a weaker rule set (`:291-299`). **Class:** INCONSISTENT · **Type:** Architecture · **Priority:** Medium · **Confidence:** High.
*Pattern note:* this is a further sighting of the 3/3 cross-capability pattern **"multiple competing representations of one business concept"** (README pattern table) — recorded here, not promoted.

### F-5 An unverified e-mail address may create and enter an organisation — is that intended?

Create, store and the homepage require only `auth` (`routes/web.php:461,493-503`); sibling organisation routes require `verified` as well (`routes/web.php:518` · `routes/organisations.php:90`). A person who has never confirmed their e-mail can create an organisation and stand on its homepage, then hits a verification wall one click deeper (settings, imports, voter hub). **Class:** INCONSISTENT · **Type:** Product · **Priority:** Medium · **Confidence:** High · **Needs a business decision** — either boundary may be the intended one; the code currently draws it in two places.

### F-6 The creator's identity is not recorded on the organisation

`organisations.created_by` exists as a column with a `belongsTo` relation (`app/Models/Organisation.php:81`), but the live creation path never writes it (and it is not fillable). Creator identity survives only as the owner pivot row — which a later role change would overwrite. **Class:** MISSING · **Type:** Product (audit trail) · **Priority:** Low-Medium · **Confidence:** High.

### F-7 The homepage guard runs twice, and one of the two can never fire

`show()` re-resolves the organisation, re-checks membership and re-sets the session (`:74-113`) — all three already done by `ensure.organisation` (`EnsureOrganisationMember.php:62-149`). The controller's non-member branch (`:102-110`) is unreachable over HTTP. **Class:** INCONSISTENT · **Type:** Technical · **Priority:** Low · **Confidence:** High.

### Already on record — referenced, not re-raised

Governance permanently `pending_setup` (**G-1…G-3**) — and consistent with **G-4**, the homepage payload carries **no `governance_status` at all** (`show():249-271`), so the owner standing on their new homepage cannot see that their organisation is unconstituted. Event-with-no-listeners (**G-5**), two route-binding styles (**G-8**), seven-responsibility `store()` (**G-9**), `.bak` test duplicates (**G-10**).

---

## 3 · What runtime verification must record (the checklist `PBDIGIT-63` carries)

1. Login → `GET /my-organisations/create` renders → submit **name only** → expect `302 → /organisations/{slug}` → homepage renders `200` with the owner's name and zero-state stats.
2. Database after the run: one `organisations` row (`type='tenant'`, unique slug) · one `user_organisation_roles` row (`role='owner'`) · `users.organisation_id` switched to the new organisation.
3. Session: `current_organisation_id` = new organisation id.
4. Duplicate name submitted twice → second slug carries `-1` suffix, both homepages reachable.
5. The same walk with an **unverified** account — the observed behaviour decides F-5.

---

## 4 · Authorization Boundary

```
Code changed          none
Architecture proposed none
Solutions recommended none
Backlog items created PBDIGIT-63 (per the End-of-Commission rule)
Status                STOPPED — awaiting (1) runtime verification authorisation,
                      (2) the F-5 business decision
```

## Method assessment — what surprised us

| Expected | Observed |
|---|---|
| The creation tests would protect the current controller (O1 row said so) | **They assert a former JSON-API product** — 201/`success`/`admin`/mails — every one contradicted by the live code (F-1) |
| A journey this central would have at least one recorded successful run | **None exists anywhere in the record** |
| The heavy `show()` read-model composition would be the fragile point for a fresh organisation | It is **null-safe end to end** (empty geo chain, zero-counts); the fragility sits in the *safety net*, not the page |

---

**Traceability:** `routes/web.php:265,461,493-503,518` · `routes/organisations.php:90` · `app/Http/Controllers/OrganisationController.php:54-57,69-271,280-388` · `app/Http/Middleware/EnsureOrganisationMember.php:62-149` · `app/Http/Middleware/TenantContext.php:66` · `app/Http/Requests/StoreOrganisationRequest.php` (unused) · `app/Models/User.php:40,47-48` · `app/Models/Organisation.php:81` · `app/Contexts/Membership/Domain/Member/MemberId.php:11-15` · `app/Contexts/Membership/Infrastructure/Query/SessionMemberGeoPathProvider.php:27-29` · `resources/js/Pages/Organisations/Create.vue:288-355` · `resources/js/Pages/Organisations/Show.vue:422-441` · `tests/Feature/OrganisationCreation{,Integration}Test.php` · `../reviews/2026-08-06-organisation-capability-review.md` · `PBDIGIT-00` · `PBDIGIT-36` · `PBDIGIT-37`
