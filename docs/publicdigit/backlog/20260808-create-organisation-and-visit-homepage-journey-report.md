# Journey check — a person creates an organisation and visits its homepage

**Story checked:** *"I want to put my organisation on the platform and then stand on its homepage."*
**Date:** 2026-08-08 · **Baseline:** branch `election-review` · **Mode:** Reviewer — **zero files modified, static evidence only** (no browser, no database in this environment — same limitation recorded at `PBDIGIT-29` and in the Organisation capability review)
**Builds on:** `../reviews/2026-08-06-organisation-capability-review.md` (application #2 of the frozen 9-phase method). **That review's scope is not repeated here** — this check walks *one narrow journey* through it and records what is new.
**Backlog item raised:** [`PBDIGIT-63`](PBDIGIT-63-verify-the-organisation-creation-journey-and-its-verification-estate.md)

---

## ⚠️ Correction to this report's own first wording (2026-08-08, same day)

**This report was first summarised as *"the journey is not broken, it is unprotected"*, and its Business Outcome asserted that *"a regression would NOT be caught"*. Both statements are withdrawn as overstated.** Neither is supported by the evidence: no runtime execution was observed, so *"not broken"* was never established; and no measurement of the suite's actual regression-detection was performed, so *"would not be caught"* was a universal claim from a sample of six assertions. **The standing rule is explicit — the strongest statement made must never exceed the strength of the available evidence.** The corrected formulations below replace them. *(Product Owner review, 2026-08-08.)*

**A second correction followed** (same day, second Product Owner review): **`F-6`'s class `MISSING` is withdrawn**, because *missing* is a **normative** claim requiring a business contract that was never found — see F-6. **Two rounds of correction on one short report, both in the same direction: the findings held, the labels and the summary did not.**

**What changed in these corrections, and what deliberately did not.** Every edit **removes a claim, weakens a claim, or removes a prescribed remedy**. **No new analytical machinery was added** — the layered capability-ownership analysis, the full evidence-classification scheme and the expanded report structure proposed at the same review are **parked, not applied**, because the Product Capability Review method is under a freeze whose gate is a Method Retrospective at 6 applications (currently 3). See *Parked, not adopted* at the foot of this report.

---

## Business Outcome (read this first)

```
Business Outcome

Today     Every step of "create an organisation -> land on its homepage" is
          present in code and statically wired end to end, and no statically
          detectable blocker was found on the happy path. The journey has never
          been executed and observed, so whether a customer completes it is NOT
          ESTABLISHED. Separately, the tests that describe the creation contract
          assert a different contract from the one the controller implements,
          so those particular tests cannot be relied on as evidence about
          today's product.

Expected  A customer creates their organisation and reaches its homepage as
          owner, and the verification estate can be trusted to detect a
          regression in that outcome.
```

### Verdict — stated as four separate facts, not one label

> **Statically wired · runtime unverified · the verification estate contains identified contract drift · several business decisions remain unresolved.**

**⚠️ `Ready for verification` is withdrawn as this journey's verdict (third correction, 2026-08-08).** It reads as a status but is a **readiness judgement** — and *ready* is normative, exactly like *missing* in `F-6`: it asserts that nothing further is required before verification, which this report cannot support while `D-1`…`D-5` are open. **The four clauses above say strictly what was found and nothing more.** *(The label is retained in the 2026-08-06 capability review, which is a dated record and is not rewritten.)*

### The three questions this journey keeps collapsing into one

**This is the report's most useful structural finding — more useful than any individual `F-n`:**

```
1. DOES THE BUSINESS CAPABILITY EXIST?          -- partly: creation yes; the rules
                                                   around it are unresolved (D-1..D-5)
                 |
2. DOES THE IMPLEMENTATION REALIZE IT?          -- statically wired; NOT VERIFIED at runtime
                 |
3. CAN THE VERIFICATION ESTATE BE TRUSTED
   TO PROTECT IT?                               -- six assertions drifted; suite-level
                                                   strength UNDETERMINED
```

**All three can hold different values at once, and this journey proves it:** the implementation can be wired while the tests describe a former contract while the journey has never run. **A single verdict for the journey necessarily overstates at least two of the three** — which is precisely how `Ready for verification` and *"not broken, just unprotected"* both went wrong.

### Evidence classification used below

Every claim in this report carries one of these. **They are not interchangeable, and the weaker ones are not failures — they are the honest state of the evidence.**

| Label | Means |
|---|---|
| **OBSERVED IN CODE** | read directly at a `file:line`; says nothing about runtime |
| **NOT VERIFIED** | no runtime execution was observed |
| **CONTRACT DRIFT** | two artifacts describe different versions of the product; **which one is correct is not decided here** |
| **BUSINESS DECISION REQUIRED** | cannot be settled from code at all |
| **MECHANISM NOT ESTABLISHED** | an effect is visible, its cause is not proven |
| **UNDETERMINED** | examined and left open on purpose |

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

**Conclusion of section 1 — stated at the strength the evidence carries:**

> **The chain *create form → store → redirect → homepage render* is statically wired end to end, and no statically detectable blocker was found on the happy path. Runtime execution has not been established.** `OBSERVED IN CODE` · `NOT VERIFIED`

**These are two different claims and this report does not merge them.** Static wiring is not proof of runtime correctness: it cannot detect a failing database constraint, a container binding that resolves differently at request time, a middleware ordering effect, a Vite build failure, or a JavaScript error that leaves the page blank after a `200`. **Nobody has recorded this journey being executed** — the capability review's Phase 7 was not performed, and `PBDIGIT-00` walked the *voting* journey, not this one.

---

## 2 · New findings — beyond the 2026-08-06 capability review

Facts first, reading of them second. Class per the frozen method: **BROKEN** = exists but cannot execute · **MISSING** = wanted, never built · **INCONSISTENT** = works, described more than once.

### F-1 🟠 Six assertions in the creation tests describe a different contract from the one the controller implements

**Customer impact — stated at evidence strength:** *for the specific outcomes listed below*, the named tests cannot report on today's product; they would pass or fail on properties the controller no longer has. **Whether the suite as a whole would catch a regression in this journey was NOT MEASURED.** `UNDETERMINED`

**Each row is `CONTRACT DRIFT`: two artifacts describe different product versions. This report does not decide which one is correct** — that is the reviewer's overreach the method warns against, and for three of the six rows the answer is a business question, not a code question.

| # | The test asserts | The code does | Classification | Evidence |
|---|---|---|---|---|
| 1 | `201` + JSON `success: true` + JSON `redirect` key | `302` redirect, no JSON | **CONTRACT DRIFT** — HTTP contract; the Inertia 2.0 migration is the plausible cause, `MECHANISM NOT ESTABLISHED` | `OrganisationCreationIntegrationTest.php:66-67,87` · `OrganisationCreationTest.php:97-98` vs `store():380` |
| 2 | creator pivot role **`admin`** | role **`owner`** | **CONTRACT DRIFT** — and **which role the creator should hold is a `BUSINESS DECISION`**, not a naming detail | `IntegrationTest:78` vs `store():343` |
| 3 | `created_by` set | never written | **CONTRACT DRIFT** + `BUSINESS DECISION REQUIRED` (F-6: is creator provenance required?) | `OrganisationCreationTest.php:105` |
| 4 | `address` persisted | neither validated nor written | **CONTRACT DRIFT** + `BUSINESS DECISION REQUIRED` — was address dropped deliberately? | `OrganisationCreationTest.php:108-109` vs `store():291-299,321-331` |
| 5 | `RepresentativeInvitationMail` sent | no mail sent; the one event has no listeners | **CONTRACT DRIFT** + `BUSINESS DECISION REQUIRED` — was representative invitation withdrawn, or lost? | `IntegrationTest:84` vs `store():367` |
| 6 | `GET /organisations/{slug}` returns JSON | returns an Inertia page | **TEST WRONG LAYER** — it enters through an interface the journey does not use | `IntegrationTest:90-93` vs `show():248` |

**What is *not* claimed here:** that these tests are "wrong"; that they should be repaired; that they should be deleted; or that the controller is correct. **A test asserting a capability the product genuinely dropped may be the only surviving record that the capability once existed** — which is why classification precedes any change to it.

**Correction to previous evidence.** The 2026-08-06 capability review's Business Rule Matrix row **O1** cited *"✅ dedicated creation tests"* as automated-test evidence for *"a user can create an organisation and becomes its owner."* **New evidence causing the correction:** the assertions above, read against the current controller — six of them describe a former contract, including the role assertion that is precisely what O1 claims to protect. **The review's conclusion that the rule is implemented still stands; only its automated-test evidence is weaker than recorded.** Same shape as `PBDIGIT-36` on the voting journey — **a second sighting, recorded, not promoted.**

**Class:** INCONSISTENT · **Type:** Product (it concerns the journey's evidence base) · **Priority:** High · **Confidence:** High *for each individual row* — every one is a `file:line` pair on both sides. **Low** for any inference about total suite coverage, which was not measured.

### F-2 🟡 Session cookie and CSRF token are written to the application log on every creation attempt

**Observed fact** `OBSERVED IN CODE`: `store()` logs `$request->all()` **and** `$request->headers->all()` at `info` level before doing anything (`OrganisationController.php:282-288`). The `Cookie` header carries the session id and the CSRF token, so both reach `laravel.log` on every submission, valid or not.

**The invariant this rests on, stated rather than assumed:** *credentials and session material must not be written to application logs.* **This is not invented for this report** — the repository already acts on it (`PBDIGIT-37`, committed-test-credential hygiene) and the operating instructions state it directly (*never log raw API keys*). **Because a named invariant exists, this is a defect against it and not merely a difference between two routes.**

**What is NOT established:** log retention, who can read `laravel.log` in production, and therefore the realised exposure. `MECHANISM NOT ESTABLISHED` for impact; the write itself is certain.
**Class:** INCONSISTENT (debug logging left in production code) · **Type:** Technical (hygiene) · **Priority:** Medium-High · **Confidence:** High *for the write*, `UNDETERMINED` for the exposure.

### F-3 The customer is shown the raw exception message

On any failure, `back()->withErrors(['error' => 'Creation failed: ' . $e->getMessage()])` (`:387`) surfaces database/internal error text to the customer. **Class:** INCONSISTENT · **Type:** Technical · **Priority:** Medium · **Confidence:** High.

### F-4 Two competing definitions of a valid organisation

`app/Http/Requests/StoreOrganisationRequest.php` (193 lines: array-format rules, custom closures, input normalisation, translated attribute names) has **zero references outside its own file** — `store()` validates inline with a weaker rule set (`:291-299`). **Class:** INCONSISTENT · **Type:** Architecture · **Priority:** Medium · **Confidence:** High.
*Pattern note:* this is a further sighting of the 3/3 cross-capability pattern **"multiple competing representations of one business concept"** (README pattern table) — recorded here, not promoted.

### F-5 An unverified e-mail address may create and enter an organisation — is that intended?

**Observed fact** `OBSERVED IN CODE`: create, store and the homepage require only `auth` (`routes/web.php:461,493-503`); sibling organisation routes require `verified` as well (`routes/web.php:518` · `routes/organisations.php:90`). A person who has never confirmed their e-mail can create an organisation and stand on its homepage, then meets a verification wall one click deeper (settings, imports, voter hub).

> ⚠️ **This is explicitly NOT called a security defect, and the reasoning matters.** *"Other organisation routes require verified users"* **does not establish** *"organisation creation must require verified users."* Deliberately admitting an unverified user to creation — so that signing up and setting up are not gated on an e-mail round-trip — is a coherent product choice. **No repository source was found that settles which boundary is intended**, so no invariant exists to violate. `BUSINESS DECISION REQUIRED`

**Class:** INCONSISTENT (the boundary is drawn in two places) · **Type:** Product · **Priority:** Medium · **Confidence:** High *for the observation*, **not applicable** for a severity judgement that cannot be made until the invariant exists.

### F-6 The creator's identity is not written to the organisation record

**Observed fact** `OBSERVED IN CODE`: `organisations.created_by` exists as a column with a `belongsTo` relation (`app/Models/Organisation.php:81`); the live creation path never writes it, and it is not fillable. Creator identity survives only as the owner pivot row, which a later role change would overwrite.

> ⚠️ **Second correction (2026-08-08).** This finding was first classified **`MISSING`** and typed *"Product (audit trail)"*. **Both are withdrawn.** *Missing* is a **normative** claim: it asserts that something which ought to be recorded is not. **That requires a business contract stating creator provenance must be retained — and no such contract was found.** Likewise *audit trail* classifies the record's business meaning from its **shape** (a nullable id column plus a relation), which is not evidence of what it is for: nobody reads it, so no business decision depends on it today.
>
> **Corrected statement:** *the creation path writes no creator reference to the organisation record, and whether that constitutes a gap is `UNDETERMINED` pending `D-3`.* Coverage is an observed fact; completeness is a judgement against a contract that does not yet exist.

**Class:** ⛔ **not classifiable yet** — `MISSING` and `INCONSISTENT` presuppose opposite answers to `D-3`, and choosing either here would answer a business question by picking a label. **Type:** Product · **Confidence:** High *for the observation*; **no severity is asserted.**

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

---

## 3a · Interpretation — kept separate from the facts above

**Nothing in this section is evidence.** It is the reading of the evidence, and it can be wrong without any fact in sections 1–2 being wrong.

* The six drifted assertions look like **one migration event, not six independent decay events** — an Inertia 2.0 / JSON-API transition would explain rows 1 and 6 together, and possibly the rest. `MECHANISM NOT ESTABLISHED`: no history analysis was run, and the alternative — several unrelated product changes, each leaving its test behind — fits the same evidence.
* `StoreOrganisationRequest` (F-4) reads like the **intended** contract and the inline rules like the **surviving** one, because the FormRequest is the more elaborated artifact (translated attribute names, normalisation, closures). **That is an aesthetic inference, not evidence.** The opposite reading — a FormRequest built for a design that was abandoned — is equally consistent.
* `created_by` (F-6) with a `belongsTo` relation and no writer has the same shape as the review's `GovernanceSetupController` finding: **designed, wired part-way, never completed.** Plausible; not demonstrated.

## 3b · Hypotheses — explicitly unproven

| # | Hypothesis | What would settle it |
|---|---|---|
| H-1 | The drift arose from the Laravel 11 / Inertia 2.0 migration | `git log`/`git blame` on the controller and the two test files |
| H-2 | The representative-invitation capability was deliberately withdrawn, not lost | product history, or a Product Owner answer |
| H-3 | The suite still catches *some* regressions in this journey despite the drifted assertions | run the organisation tests and read what actually passes and why |

## 3c · Business decisions required — and who owns each

**A technical mechanism is not the owner of a business decision.** Each row names the layer that must answer, not the file that would change.

| # | Question | Owned by | Why it is not engineering's to settle |
|---|---|---|---|
| **F-5** | May an unverified e-mail address create an organisation and enter it? | **Policy / Authorization** | Both boundaries are coherent products; no repository source settles it |
| **F-6** | Must the platform record who created an organisation? | **Domain** (is creator provenance a business fact the organisation carries?) | If yes it is an invariant; if no, the column and relation should go — opposite outcomes from the same evidence |
| **F-4** | Which definition of a valid organisation is authoritative? | **Domain** (the rules) · **Application** (where they are enforced) | Choosing the stricter file because it is more elaborate would be an engineer silently setting product rules |
| **F-1 row 2** | Which role does a creator hold — `owner` or `admin`? | **Domain / Policy** | The tests and the code disagree; the *name* carries a permissions meaning |
| **F-1 rows 4-5** | Were address capture and representative invitation withdrawn deliberately? | **Product** | Determines whether those tests are obsolete records or evidence of lost capability |

## 3d · What this report did NOT establish

Stated plainly, because a discovery report's honesty is measured by this list:

1. **That a customer can complete the journey.** No runtime execution was observed. `NOT VERIFIED`
2. **That a customer cannot.** Equally unestablished — no blocker was found.
3. **Whether the test suite would catch a regression here.** Six assertions were read; the suite was **not run** and its coverage **not measured**. `UNDETERMINED`
4. **Why the drift exists.** No history analysis. `MECHANISM NOT ESTABLISHED`
5. **Whether any of F-4, F-5, F-6 is a defect.** Each awaits a business answer; until then they are differences, not faults.
6. **The realised impact of F-2** — the write is certain, the exposure is not.
7. **Anything about the organisation's governance lifecycle** beyond noting that the homepage payload omits `governance_status`; **G-1…G-4 are referenced, not re-investigated.**

---

## 4 · Authorization Boundary

```
Code changed          none
Tests changed         none — including the six drifted assertions, deliberately
Fixtures/migrations   none
Architecture proposed none
Solutions recommended none — no remedy is named for any finding
Test suite            NOT RUN (so its coverage is UNDETERMINED, not "absent")
Backlog items created PBDIGIT-63 (per the End-of-Commission rule)
Status                STOPPED — awaiting (1) authorisation for runtime
                      verification, (2) the business decisions in 3c
```

**On the six drifted assertions specifically: they were left exactly as they are.** Correcting a test is a change to what the product is asserted to be, and three of the six turn on questions only the Product Owner can answer.

## Method assessment — what surprised us

| Expected | Observed |
|---|---|
| The creation tests would protect the current controller (row `O1` said so) | **Six assertions describe a different contract** — and three of them turn on unanswered *business* questions, not stale code (F-1) |
| A journey this central would have at least one recorded successful run | **None exists anywhere in the record** |
| The heavy `show()` read-model composition would be the fragile point for a fresh organisation | It is **null-safe end to end** (empty geo chain, zero counts); the uncertainty sits in the evidence base, not the page |
| The report's own conclusions would be safe once every finding carried a `file:line` | **They were not.** Two summary sentences — *"not broken, just unprotected"* and *"a regression would not be caught"* — outran evidence that was itself sound. **Well-evidenced findings do not make a well-calibrated summary; the summary is a separate discipline** |

---

## 🅿️ Parked, not adopted — the review that produced this correction

The same Product Owner review proposed a substantially stronger discovery commission: a layered analysis (**business outcome → capability → domain invariants → decision ownership → application → authorization → interface → persistence → verification**), a full evidence-classification scheme, a test-classification taxonomy, a 22-section report structure and a specified ticket structure.

**It is recorded, and it is not applied here.** The Product Capability Review method is under a freeze whose gate is explicit: *refinements may only be adopted at a Method Retrospective, never during or immediately after a review*, with the next retrospective eligible at **6 applications** (currently **3**). The README records that the freeze has already been declared and breached four times, every round individually justified — **which is exactly how this one would read too.**

**What was applied instead, and why it is not a breach:** every correction above **removes a claim, weakens a claim, or removes a prescribed remedy**. Those are obligations under the *existing* rules — *"the strongest statement made must never exceed the strength of the available evidence"* and *"a discovery report describes reality; it never prescribes implementation."* **No new analytical machinery was added.** Adopting a weaker claim is always permitted; adding a new phase is not.

**Placement of the proposed commission is `PENDING`.** `php scripts/doc-placement.php --scope=cross-product --maturity=research` returns *"PENDING — placement unruled (rule: `cross-product-research`, ref: `ADR:OQ-2`). Record PENDING and escalate. Do not guess a location."* **`OQ-2` — where cross-product research lives — is the same open question the README names as unruled.** The verbatim text is therefore preserved in the session log (`.claude/sessions/2026-08-08.md`, a ruled home) and indexed as a parked candidate, rather than filed into a folder this repository has not yet ruled on. **One Product Owner sentence declaring a Method Retrospective, or an explicit exception, adopts it — that is a governance act, not an engineering one.**

---

**Traceability:** `routes/web.php:265,461,493-503,518` · `routes/organisations.php:90` · `app/Http/Controllers/OrganisationController.php:54-57,69-271,280-388` · `app/Http/Middleware/EnsureOrganisationMember.php:62-149` · `app/Http/Middleware/TenantContext.php:66` · `app/Http/Requests/StoreOrganisationRequest.php` (unused) · `app/Models/User.php:40,47-48` · `app/Models/Organisation.php:81` · `app/Contexts/Membership/Domain/Member/MemberId.php:11-15` · `app/Contexts/Membership/Infrastructure/Query/SessionMemberGeoPathProvider.php:27-29` · `resources/js/Pages/Organisations/Create.vue:288-355` · `resources/js/Pages/Organisations/Show.vue:422-441` · `tests/Feature/OrganisationCreation{,Integration}Test.php` · `../reviews/2026-08-06-organisation-capability-review.md` · `PBDIGIT-00` · `PBDIGIT-36` · `PBDIGIT-37`
