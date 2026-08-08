# PBDIGIT-63 — Verify "create an organisation → land on its homepage", and repair the test safety net that no longer protects it

**Type:** Verification + defect · **Epic:** `PBDIGIT-EPIC-01` Organisation Management · **Created:** 2026-08-08
**Raised by:** the journey check [`20260808-create-organisation-and-visit-homepage-journey-report.md`](20260808-create-organisation-and-visit-homepage-journey-report.md) — **static evidence only, zero files modified**

| | |
|---|---|
| **Customer goal** | *"I put my organisation on the platform, and I land on its homepage as its owner."* |
| **Status** | `OPEN` — **not authorised.** Part A needs a runtime environment; Part B needs the F-5 decision below |
| **Customer impact** | 🟡 **Not known to be broken — known to be unprotected.** Every step is wired in code and nothing statically blocks the happy path, but **no successful run has ever been recorded**, and the tests that claim to cover it assert a product the code no longer implements. A regression here would reach a customer before it reached a test |
| **Severity** | **Medium** — no reported failure; the risk is that a failure would be invisible |

---

## Why this ticket exists

The Organisation capability review (2026-08-06) marked rule **O1** — *"a user can create an organisation and becomes its owner"* — as ✅ implemented with ✅ dedicated automated tests. **The first half stands. The second half does not:** the test files exist, but they encode a former JSON-API contract. **This ticket corrects that record and closes the gap.**

`PBDIGIT-00` walked the *voting* journey end to end. **The organisation-creation journey has never been walked** — it is the first segment of the Level 0 journey, and it is unverified.

---

## Part A — Verify the journey (no code change; produces evidence)

Walk it once, in a browser, against a provisioned database, recording page · route · DB rows · session at each step.

* [ ] Login → `GET /my-organisations/create` renders the form.
* [ ] Submit with **name only** → `302 → /organisations/{slug}` → homepage renders `200`.
* [ ] `organisations`: exactly one new row, `type='tenant'`, slug unique.
* [ ] `user_organisation_roles`: exactly one new row, `role='owner'` — **`owner`, not `admin`** (`OrganisationController.php:343`).
* [ ] `users.organisation_id` switched to the new organisation (`:354`).
* [ ] Session `current_organisation_id` = the new organisation id.
* [ ] Submit the **same name twice** → second slug carries the `-1` suffix; both homepages reachable (`:305-312`).
* [ ] Repeat the walk with an **unverified** e-mail account — the observed behaviour is the input to F-5 below.

**Record the result whether positive or negative.** A negative result is the deliverable, exactly as in `PBDIGIT-00`.

## Part B — Repair the safety net (code; blocked on Part A)

**The tests must be corrected to the current product, not the product corrected to the tests.** Every row below is the test asserting one thing and `store()` doing another:

| # | Test asserts | Code does | Evidence |
|---|---|---|---|
| 1 | `201` + JSON `success` + JSON `redirect` | `302` redirect, no JSON | `OrganisationCreationIntegrationTest.php:66-67,87` · `OrganisationCreationTest.php:97-98` vs `store():380` |
| 2 | pivot role `admin` | role `owner` | `IntegrationTest:78` vs `store():343` |
| 3 | `created_by` is set | never written | `OrganisationCreationTest.php:105` vs `store():321-331` |
| 4 | `address` persisted | never validated, never written | `OrganisationCreationTest.php:108-109` |
| 5 | `RepresentativeInvitationMail` sent | no mail sent; the one event has no listeners | `IntegrationTest:84` vs `store():367` |
| 6 | `GET /organisations/{slug}` returns JSON | returns an Inertia page | `IntegrationTest:90-93` vs `show():248` |

**Acceptance for Part B**

* [ ] A test asserts the **real** contract: `POST /organisations` → `302` to `organisations.show`, following the redirect to a `200` Inertia page.
* [ ] A test asserts the creator holds **`owner`**.
* [ ] A test asserts the slug de-duplication rule (rule **O2**, which the review recorded as having *no* rule-level test).
* [ ] The `.bak` duplicates of the organisation creation tests are removed (**G-10**).
* [ ] **No test is deleted to make the suite green** — a test asserting a capability the product genuinely dropped (mails, `created_by`, `address`) is either re-pointed at the current contract or turned into an explicit, dated record that the capability is gone.

## Part C — Hygiene defects found on this path (small, independent)

* [ ] **F-2 🔴 Secrets in the log.** `store()` logs `$request->all()` **and** `$request->headers->all()` at `info` on every attempt — the session cookie and CSRF token are written to `laravel.log` (`OrganisationController.php:282-288`). Same family as `PBDIGIT-37`.
* [ ] **F-3** The customer is shown the raw exception text on failure (`:387`).
* [ ] **F-7** `show():74-113` re-resolves the organisation, re-checks membership and re-sets the session — all already done by `ensure.organisation`; the controller's non-member branch is unreachable over HTTP.

---

## 🟡 Business decisions required — engineering must not settle these

**F-5 · May a person with an unverified e-mail address create an organisation and enter its homepage?**
Today: **yes.** Create, store and the homepage require `auth` only (`routes/web.php:461,493-503`), while sibling organisation routes require `verified` as well (`routes/web.php:518` · `routes/organisations.php:90`). The customer therefore creates an organisation, lands on it, and hits a verification wall one click deeper. **Either boundary may be intended; the product currently draws it in two places.** Not a defect until the business says which is right.

**F-6 · Must the platform record who created an organisation?**
`organisations.created_by` exists as a column with a `belongsTo` relation (`Organisation.php:81`) and is never written. Creator identity survives only as the owner pivot row, which a later role change would overwrite. **Is creator provenance a business requirement?** If yes it is a small write; if no, the column and relation should go.

**F-4 · Which definition of a valid organisation is the real one?**
`StoreOrganisationRequest` (193 lines — array-format rules, custom closures, input normalisation, translated attribute names) has **zero references outside its own file**; `store()` validates inline with a weaker rule set (`:291-299`). One of the two is the intended contract. A further sighting of the 3/3 pattern *"multiple competing representations of one business concept"* — **recorded, not promoted.**

---

## Related

* [`20260808-…-journey-report.md`](20260808-create-organisation-and-visit-homepage-journey-report.md) — the evidence behind every line above.
* `../reviews/2026-08-06-organisation-capability-review.md` — **rule O1's automated-test evidence is corrected by this ticket**; **G-1…G-3** (governance permanently `pending_setup`) and **G-4** are the reason the new homepage shows the owner nothing about their organisation's governance — *not re-raised here*.
* `PBDIGIT-00` — the same verification discipline, applied to the voting journey.
* `PBDIGIT-36` — *"the E2E suites do not test the journey"*: **the same finding, now observed on a second journey.**
* `PBDIGIT-37` — test-credential and safety-claim hygiene; F-2 joins that family.

---

**Traceability:** `app/Http/Controllers/OrganisationController.php:54-57,69-271,280-388` · `app/Http/Middleware/EnsureOrganisationMember.php:62-149` · `app/Http/Middleware/TenantContext.php:66` · `app/Http/Requests/StoreOrganisationRequest.php` (unused) · `app/Models/Organisation.php:81` · `routes/web.php:461,493-503,518` · `routes/organisations.php:90` · `resources/js/Pages/Organisations/Create.vue:288-355` · `resources/js/Pages/Organisations/Show.vue:422-441` · `tests/Feature/OrganisationCreation{,Integration}Test.php`
