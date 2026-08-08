# PBDIGIT-63 — Verify the organisation-creation journey, and establish whether its verification estate can be trusted

**Type:** Verification + classification (**not** a repair ticket) · **Epic:** `PBDIGIT-EPIC-01` Organisation Management
**Created:** 2026-08-08 · **Reframed the same day** after Product Owner review — see *Correction* below
**Evidence:** [`20260808-create-organisation-and-visit-homepage-journey-report.md`](20260808-create-organisation-and-visit-homepage-journey-report.md) — static discovery + **runtime verification executed 2026-08-08**; zero files modified in either

---

## ⚠️ Correction — this ticket's own first framing was withdrawn

**It was first titled *"…and repair its test safety net"*, and its summary read *"the journey is not broken, it is unprotected."* Both are withdrawn.**

* *"Not broken"* asserts a runtime property **that was never observed**.
* *"Repair the tests"* **names the remedy before the diagnosis.** A test can diverge from the code because the product changed, the business requirement changed, the test is obsolete, the fixture drifted, the route contract changed, it tests the wrong layer, it asserts a mechanism rather than a business invariant — **or because the implementation is the thing that is wrong.** The evidence distinguishes none of these, and for three of the six divergences the answer is a *business* question.

**A ticket that has already chosen the remedy stops the investigation that would have chosen it correctly.** This is the same discipline `PBDIGIT-48` applied when it declined to "fix" a stale column and asked which representation was authoritative first.

---

## Business outcome

> **A person who wants their organisation on the platform creates it, and arrives on that organisation's homepage as its owner — and if that outcome ever stops working, the platform finds out before a customer does.**

**User story:** *"I want to put my organisation on PublicDigit and then stand on its homepage."*

## Why this matters

This is the **first segment of the Level 0 customer journey**. Everything downstream — committees, members, elections, votes — presumes an organisation exists and its owner can reach it. **It ran end to end on 2026-08-08 and was observed doing so** — the first recorded execution. **The remaining risk is not that it fails, but that a future failure would be invisible:** the tests describing its creation contract describe a different contract from the one the controller implements.

## Current state — at evidence strength

> **🟢 Runtime-verified end to end (server-side, 2026-08-08) · browser rendering not observed · the verification estate contains identified contract drift · several business decisions remain unresolved.**

**Four separate facts, deliberately not compressed into one verdict** — because this ticket exists to keep three different questions apart:

```
1. Does the business capability exist?        -- creation yes; its rules are open (D-1..D-6)
2. Does the implementation realize it?        -- YES, OBSERVED AT RUNTIME (server-side)
3. Can the verification estate be trusted?    -- six assertions drifted; suite-level UNDETERMINED
```

**Each part of this ticket answers exactly one of them: Part A answers 2, Part B answers 3, and D-1…D-6 answer 1.** Collapsing them is what produced this ticket's withdrawn first framing.

| Claim | Status |
|---|---|
| Every step is present in code and statically wired end to end | `OBSERVED IN CODE` |
| No statically detectable blocker on the happy path | `OBSERVED IN CODE` |
| **A customer can complete the journey** | **`OBSERVED AT RUNTIME`** 2026-08-08 — executed, persisted outcome verified |
| **A customer *sees* the homepage in a browser** | **`NOT VERIFIED`** — served page targets a Vite dev server that is not running (stale gitignored `public/hot`; environment, not product) |
| Six creation assertions describe a different contract from the controller | `CONTRACT DRIFT` — confirmed per row, `file:line` both sides |
| Whether the suite would catch a regression in this journey | **`UNDETERMINED`** — the suite was not run |
| Why the drift exists | **`MECHANISM NOT ESTABLISHED`** — no history analysis |

## Verified findings

* **F-1** Six assertions in `OrganisationCreation{,Integration}Test.php` diverge from `store()`/`show()` — each pinned to a `file:line` pair on both sides. **Classified as drift; no verdict on which side is correct.**
* **F-2** `store()` writes the session cookie and CSRF token to `laravel.log` on every attempt (`OrganisationController.php:282-288`). **The write is certain. Its classification is not** — see `D-6`. *(This was first recorded as "a defect against a named, pre-existing invariant"; **withdrawn** — `PBDIGIT-37` concerns credentials in tracked files and never mentions logging, and the *"never log credentials"* rule came from the operator's private AI-instruction file, not from a PublicDigit contract. **No repository-wide logging policy was found.**)*
* **F-3** The raw exception message is shown to the customer on failure (`:387`).
* **F-7** `show():74-113` re-resolves the organisation, re-checks membership and re-sets the session — all already done by `ensure.organisation`; that controller branch is unreachable over HTTP.

## Unverified findings

* ~~Whether the journey executes at all~~ — **settled by Part A** at the server level; browser rendering remains unverified.
* Whether the drift has one cause or six (H-1 in the report).
* The realised exposure of F-2 — log retention and readership were not examined.

---

## Part A — ✅ EXECUTED 2026-08-08 (Product Owner authorised)

**The journey ran end to end at the server level.** Full evidence in the [report](20260808-create-organisation-and-visit-homepage-journey-report.md) → *Runtime verification*. No code, tests, fixtures or configuration were modified.

* [x] The creator reaches the create form, submits **name only**, and arrives on the new organisation's homepage — `200 → 302 → 200`, components `Organisations/Create` then `Organisations/Show`.
* [x] The organisation exists exactly once, with a unique slug, as a tenant organisation — `type=tenant`, one row for that slug.
* [x] The creator holds the ownership relationship — **observed `role = owner`**, one row. *(Recorded, not corrected: whether `owner` is what the business intends remains `D-2`.)*
* [x] The creator's working-organisation context is the new organisation — `users.organisation_id` switched; the homepage rendered behind `ensure.organisation`, which requires the session context.
* [x] Creating two organisations with the same name yields two reachable, distinctly-addressed homepages — `…-org` and `…-org-1`, both `200`.
* [ ] The same walk with an **unverified** e-mail account — **NOT DONE.** The authorised account is verified. `F-5`/`D-1` are untouched.

**🔴 Not established by Part A, and it must not be read as if it were:**

* **Browser rendering.** The server returns the correct page, but the HTML points at a Vite dev server that is not listening — a **stale, gitignored `public/hot`**, i.e. an environment condition, not a product defect. `public/hot` was deliberately not touched.
* **Anything about Part B.** Running the journey says nothing about whether a regression in it would be caught.

**Residue for disposal:** two organisations remain in the development database, named `pbdigit63-runtime-verification-org` and `…-org-1`, and `roshyara@gmail.com`'s working organisation now points at the second. **Not deleted** — deletion is destructive against the development database and would remove the evidence. **Disposal, including restoring the prior working organisation, is a Product Owner decision.**

## Part B — Classify the verification estate *(no changes to it)*

**✅ UNBLOCKED — Part A is done.** **Still not authorised to change anything.** For every test touching this journey, answer — **before anyone proposes changing a line of it**:

1. What business outcome was it written to protect?
2. What invariant does it assert, and is that invariant still the business's?
3. Which layer does it enter through, and is that the layer the customer uses?
4. Is it asserting business behaviour, or an implementation mechanism?
5. Is the fixture establishing the business state the assertion needs?

Classify each as: `VALID` · `OBSOLETE` · `CONTRACT DRIFT` · `FIXTURE DRIFT` · `WRONG LAYER` · `IMPLEMENTATION-COUPLED` · `COVERAGE GAP` · `UNDETERMINED`.

* [ ] Every organisation-creation test carries a classification and a one-line reason.
* [ ] **Coverage gaps are named as business outcomes left unprotected**, not as missing test files.
* [ ] The classification records, for each divergence, **whether the test or the implementation is the thing that moved** — or that this is `UNDETERMINED`.

> **Deliberately absent: any instruction to repair, rewrite or delete a test.** Part C cannot be written until Part B has run.

## Part C — Restore trustworthy verification *(NOT AUTHORISED; cannot be specified yet)*

Scope is whatever Part B's classification and the business decisions below justify — **and no more.** A test asserting a capability the product genuinely dropped may be the last surviving record that the capability existed; **deleting it to make a suite green destroys evidence.**

## Part D — Independent observations *(separable from A–C; **not** a pre-authorised repair list)*

**Renamed from "hygiene defects".** Two of the three cannot be called defects yet, for the same reason `F-6` could not be called `MISSING`: **no established obligation exists to be in breach of.**

* [ ] **F-2** — session cookie and CSRF token in `laravel.log`. **Blocked on `D-6`**, not independent. *(Previously listed here as "proceeds on its own evidence" — withdrawn with its invariant.)*
* [ ] **F-3** — raw exception text shown to the customer. **Is there an established expectation about what a customer sees on failure?** None was searched for. `UNDETERMINED`.
* [ ] **F-7** — duplicated, partly unreachable guard in `show()`. **The only item here needing no business contract:** the controller's non-member branch is unreachable over HTTP because middleware already rejected the request — that is a statement about control flow, not about what the product ought to do.

---

## 🟡 Business decisions required — engineering must not settle these

Each names **the layer that owns the answer**, not the file that would change.

| # | Question | Owner | Consequence of the answer |
|---|---|---|---|
| **D-1** | May an unverified e-mail address create an organisation and enter it? | **Policy / Authorization** | Today it can. *"Other routes require `verified`"* **does not establish** that creation must — both are coherent products |
| **D-2** | Which role does the creator hold — `owner` (code) or `admin` (tests)? | **Domain / Policy** | Decides which side of F-1 row 2 moved, and carries a permissions meaning |
| **D-3** | Must the platform record **who created** an organisation? | **Domain** | `created_by` exists with a relation and no writer. Yes → an invariant to enforce; no → remove the column and relation |
| **D-4** | Which definition of a valid organisation is authoritative — `StoreOrganisationRequest` (unused, 193 lines) or the inline rules? | **Domain** (rules) · **Application** (enforcement) | Choosing the more elaborate file *because* it is more elaborate would be an engineer silently setting product rules |
| **D-5** | Were **address capture** and **representative invitation** withdrawn deliberately, or lost? | **Product** | Decides whether those tests are obsolete records or evidence of lost capability |
| **D-6** | **Does PublicDigit have a policy on secrets and session material in application logs?** | **Policy / Governance** | **None was found anywhere in the repository.** Until one exists, `F-2` is an observed condition with no obligation to breach. **Engineering inventing the policy — however obviously sensible — is engineering setting security policy** |

---

## Acceptance criteria — stated as business behaviour

**Mechanisms are evidence, not criteria.** *"The controller returns 302"* is how a thing might be achieved; it is not the thing.

* [ ] **A person who creates an organisation arrives at that organisation's homepage without having to construct or guess its address**, and this has been *observed*, with the observation recorded and dated.
* [ ] **The person who created an organisation is recognised as its owner on arrival** — they see it, and the platform enforces it.
* [ ] **Two organisations created with the same name remain separately reachable**, and neither displaces the other.
* [ ] **A person entering an organisation sees only that organisation's information** — tenant context follows them from creation onward.
* [ ] **For every business outcome above, the platform detects a regression before a customer does** — and the evidence for that claim is a classification, not a count of test files.
* [ ] **No credential or session material is written to application logs** during any of it.
* [ ] **Every decision D-1…D-6 is answered by its owner**, and the answer is recorded where the next reader will find it.

## Explicit non-goals

* **Not** making any test suite green.
* **Not** repairing, rewriting or deleting tests — Part C is unauthorised and unspecifiable until Part B runs.
* **Not** deciding which side of any divergence is correct from code alone.
* **Not** implementing D-1…D-6.
* **Not** re-opening the organisation governance lifecycle (**G-1…G-4**) — referenced only, and the reason the new homepage shows its owner nothing about governance.
* **Not** refactoring `store()` (**G-9**), the route-binding styles (**G-8**), or the event with no listeners (**G-5**).

## Dependencies

* **Part A needs a runtime environment** — browser + provisioned PostgreSQL. Its absence is the same limitation recorded at PB003 certification, `PBDIGIT-29`, and the 2026-08-06 capability review.
* **Part B depends on Part A.** **Part C depends on Part B *and* on D-1…D-6.** **Part D is no longer independent: F-2 waits on D-6, F-3 on an expectation nobody has stated; only F-7 stands alone.**

## Authorization status

```
Authorised now      F-7 only — and only because it needs no business contract
                    (a controller branch unreachable behind middleware is a
                    control-flow fact, not a claim about what the product owes)
Done                Part A — EXECUTED 2026-08-08; journey verified server-side
Awaiting            Part B — now unblocked; classification of the estate
                    Browser-level render check (needs `npm run dev` or no
                    stale public/hot); F-5/D-1 unverified-user walk
                    Part C — NOT SPECIFIABLE until B and D-1..D-6 complete
                    F-2    — blocked on D-6 (no logging policy exists to breach)
                    F-3    — no stated expectation was found; UNDETERMINED
Code changed so far none
```

**This block shrank on 2026-08-08 rather than grew, and that is the honest direction.** It previously authorised all of Part D as *"independent hygiene defects"*. **Two of the three were not defects — they were differences with no established obligation behind them.**

## Related

* [Journey report](20260808-create-organisation-and-visit-homepage-journey-report.md) — evidence for every line here, including its own withdrawn wording.
* `../reviews/2026-08-06-organisation-capability-review.md` — **corrects rule `O1`'s automated-test evidence** (the rule stands; its evidence is weaker than recorded). **G-1…G-4** referenced, not re-raised.
* `PBDIGIT-00` — the same verification discipline on the voting journey; the precedent that a negative result is the deliverable.
* `PBDIGIT-36` — *"the E2E suites do not test the journey"*: **the same shape on a second journey — a recorded sighting, not a promoted pattern.**
* `PBDIGIT-37` — credential hygiene; **F-2 is a defect against that established invariant.**
* `PBDIGIT-48` — the precedent for classifying before repairing.

---

**Traceability:** `app/Http/Controllers/OrganisationController.php:54-57,69-271,280-388` · `app/Http/Middleware/EnsureOrganisationMember.php:62-149` · `app/Http/Middleware/TenantContext.php:66` · `app/Http/Requests/StoreOrganisationRequest.php` (unused) · `app/Models/Organisation.php:81` · `app/Models/User.php:40,47-48` · `routes/web.php:461,493-503,518` · `routes/organisations.php:90` · `resources/js/Pages/Organisations/Create.vue:288-355` · `resources/js/Pages/Organisations/Show.vue:422-441` · `tests/Feature/OrganisationCreation{,Integration}Test.php`
**Supersedes:** `PBDIGIT-63-verify-create-organisation-journey-and-repair-its-test-safety-net.md` (same ID, renamed 2026-08-08 — the ID names one thing permanently; only the framing changed).

---

# PART A — EXECUTED 2026-08-08. **The journey works end to end.**

**The recorded blocker was stale.** Part A's dependency read *"needs a runtime environment — its absence is the same limitation recorded at PB003 certification"*. **A runtime was built earlier the same day for `PBDIGIT-00`** (served app on `nrna_test`), so Part A became executable under its own standing authorisation — *"Part A where a runtime exists"*.

**Method:** real HTTP against a served application, two accounts (verified · unverified), page · route · DB rows · session recorded at each step. **No production code, test or fixture changed. No test executed.**

## Result — all six criteria

| # | Criterion | Observed |
|---|---|---|
| 1 | create form → submit **name only** → arrive on the new organisation's homepage | ✅ `GET /my-organisations/create` **200** `Organisations/Create` → `POST /organisations` **302 → `/organisations/acme-society`** → follow **200** `Organisations/Show` |
| 2 | exists exactly once · unique slug · tenant organisation | ✅ one row per creation · `type='tenant'` · `is_default=false` |
| 3 | creator holds the ownership relationship | ✅ `user_organisation_roles.role = **'owner'**` — **this is the observation `D-2` asked for** |
| 4 | working-organisation context is the new organisation, **in the user record and the session** | ✅ **both** — `users.organisation_id` updated, and the `sessions` payload carries `current_organisation_id` equal to the new organisation |
| 5 | two organisations with the same name → two reachable, distinctly-addressed homepages | ✅ `Acme Society` ×3 → `acme-society` · `acme-society-1` · `acme-society-2`, each rendering `Organisations/Show` |
| 6 | same walk with an **unverified** e-mail — observe, do not judge | ⚠️ **login redirects to `/email/verify`, and creation still succeeds**: `GET …/create` **200**, `POST /organisations` **302 → `/organisations/unverified-guild`**, organisation created, role `owner` |

> **The withdrawn framing is now settled on evidence rather than assumption: the journey is not merely "statically wired" — it executes, and it produces the intended business outcome.**

## What Part A adds to the open business decisions

| | |
|---|---|
| **`D-1`** | **Observed, not judged.** An unverified account **can** create an organisation and enter it — *and* its login redirects to `/email/verify`. **Both are true simultaneously**, which is the sharpest form of the question: the product both asks for verification and does not require it here. **Still a Policy decision** |
| **`D-2`** | **Observed: `owner`.** Code and runtime agree; **the tests asserting `admin` are the side that diverges.** *(Which is correct remains `D-2`'s to settle — this establishes only which side moved.)* |
| 🔴 **`D-3`** | **Its premise is wrong and is corrected here.** `D-3` reads *"`created_by` exists with a relation and no writer"*. **Measured: `organisations.created_by` exists in NEITHER the development NOR the fresh-migration schema.** What exists is `Organisation::creator()` (`app/Models/Organisation.php:81`) — `belongsTo(User::class, 'created_by')` — **a relation over a column that does not exist, with no caller found.** So the option *"no → remove the column and relation"* has **no column to remove**, and *"yes → an invariant to enforce"* means **adding a column, not writing to one** |

**`D-3`'s corrected form:** *does the platform record who created an organisation?* — **today it cannot**, and the relation implying it does is dead. **If ever called it would raise `SQLSTATE[42703]`.**

## Recorded, not judged

* Every account also holds `role='voter'` in the **bootstrap** organisation (`publicdigit`, `type='platform'`). Consistent with `PBDIGIT-30` B1.1 — bootstrap membership is infrastructure, not a working context.
* **Slug disambiguation is positional** (`-1`, `-2`), not creator- or date-derived. **No business rule for slug collision was found**; recorded as an observation, not a gap.

## Evidence limitations — stated so the result is not over-read

* **Executed against `nrna_test`**, not production or development data.
* **Via HTTP, not a browser** — no JavaScript executed, so **client-side validation and any SPA-only behaviour were not exercised.**
* **`Organisations/Show` renders** — its *content* was not asserted beyond the component name.
* The `Acme Society` count of three includes one organisation from an aborted first run; **the same-name behaviour is unaffected**, but the number is not "three deliberate attempts".
* **No test was executed.** Part A is a journey observation, not a suite run.

**PART A COMPLETE — THE JOURNEY EXECUTES AND PRODUCES THE INTENDED OUTCOME · `D-1` AND `D-2` NOW HAVE OBSERVATIONS · `D-3`'s PREMISE CORRECTED · PART B UNBLOCKED, NOT STARTED.**
