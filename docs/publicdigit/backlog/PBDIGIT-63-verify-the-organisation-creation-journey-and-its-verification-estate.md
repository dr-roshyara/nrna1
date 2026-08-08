# PBDIGIT-63 — Verify the organisation-creation journey, and establish whether its verification estate can be trusted

**Type:** Verification + classification (**not** a repair ticket) · **Epic:** `PBDIGIT-EPIC-01` Organisation Management
**Created:** 2026-08-08 · **Reframed the same day** after Product Owner review — see *Correction* below
**Evidence:** [`20260808-create-organisation-and-visit-homepage-journey-report.md`](20260808-create-organisation-and-visit-homepage-journey-report.md) — static evidence only, zero files modified

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

This is the **first segment of the Level 0 customer journey**. Everything downstream — committees, members, elections, votes — presumes an organisation exists and its owner can reach it. **It has never been observed running**, and the tests that describe its creation contract describe a different contract from the one the controller implements. The risk is not a known failure; **the risk is that a failure here would be invisible.**

## Current state — at evidence strength

| Claim | Status |
|---|---|
| Every step is present in code and statically wired end to end | `OBSERVED IN CODE` |
| No statically detectable blocker on the happy path | `OBSERVED IN CODE` |
| **A customer can complete the journey** | **`NOT VERIFIED`** — never executed |
| **A customer cannot complete the journey** | **`NOT VERIFIED`** — no blocker found |
| Six creation assertions describe a different contract from the controller | `CONTRACT DRIFT` — confirmed per row, `file:line` both sides |
| Whether the suite would catch a regression in this journey | **`UNDETERMINED`** — the suite was not run |
| Why the drift exists | **`MECHANISM NOT ESTABLISHED`** — no history analysis |

## Verified findings

* **F-1** Six assertions in `OrganisationCreation{,Integration}Test.php` diverge from `store()`/`show()` — each pinned to a `file:line` pair on both sides. **Classified as drift; no verdict on which side is correct.**
* **F-2** `store()` writes the session cookie and CSRF token to `laravel.log` on every attempt (`OrganisationController.php:282-288`). **A defect against a named, pre-existing invariant** (`PBDIGIT-37`; *never log credentials*) — not merely a difference between routes.
* **F-3** The raw exception message is shown to the customer on failure (`:387`).
* **F-7** `show():74-113` re-resolves the organisation, re-checks membership and re-sets the session — all already done by `ensure.organisation`; that controller branch is unreachable over HTTP.

## Unverified findings

* Whether the journey executes at all (Part A exists to settle this).
* Whether the drift has one cause or six (H-1 in the report).
* The realised exposure of F-2 — log retention and readership were not examined.

---

## Part A — Execute the journey and record what happens

**No code. This produces the evidence the ticket is missing.** Record page · route · DB rows · session at each step, **and record a negative result as faithfully as a positive one** — that is what `PBDIGIT-00` established.

* [ ] The creator reaches the create form, submits **name only**, and arrives on the new organisation's homepage.
* [ ] The organisation exists exactly once, with a unique slug, as a tenant organisation.
* [ ] The creator holds the ownership relationship the business intends *(which role that is, is a decision in `D-2` below — record what is observed, do not correct it)*.
* [ ] The creator's working-organisation context is the new organisation, in both the user record and the session.
* [ ] Creating two organisations with the same name yields two reachable, distinctly-addressed homepages.
* [ ] The same walk with an **unverified** e-mail account — **observe and record; do not treat either outcome as a fault** (`D-1`).

## Part B — Classify the verification estate *(no changes to it)*

**Blocked on Part A.** For every test touching this journey, answer — **before anyone proposes changing a line of it**:

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

## Part D — Independent hygiene defects *(separable from all of the above)*

* [ ] **F-2** — session cookie and CSRF token in `laravel.log`. **Proceeds on its own evidence; it does not wait for Part A.**
* [ ] **F-3** — raw exception text shown to the customer.
* [ ] **F-7** — duplicated, partly unreachable guard in `show()`.

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

---

## Acceptance criteria — stated as business behaviour

**Mechanisms are evidence, not criteria.** *"The controller returns 302"* is how a thing might be achieved; it is not the thing.

* [ ] **A person who creates an organisation arrives at that organisation's homepage without having to construct or guess its address**, and this has been *observed*, with the observation recorded and dated.
* [ ] **The person who created an organisation is recognised as its owner on arrival** — they see it, and the platform enforces it.
* [ ] **Two organisations created with the same name remain separately reachable**, and neither displaces the other.
* [ ] **A person entering an organisation sees only that organisation's information** — tenant context follows them from creation onward.
* [ ] **For every business outcome above, the platform detects a regression before a customer does** — and the evidence for that claim is a classification, not a count of test files.
* [ ] **No credential or session material is written to application logs** during any of it.
* [ ] **Every decision D-1…D-5 is answered by its owner**, and the answer is recorded where the next reader will find it.

## Explicit non-goals

* **Not** making any test suite green.
* **Not** repairing, rewriting or deleting tests — Part C is unauthorised and unspecifiable until Part B runs.
* **Not** deciding which side of any divergence is correct from code alone.
* **Not** implementing D-1…D-5.
* **Not** re-opening the organisation governance lifecycle (**G-1…G-4**) — referenced only, and the reason the new homepage shows its owner nothing about governance.
* **Not** refactoring `store()` (**G-9**), the route-binding styles (**G-8**), or the event with no listeners (**G-5**).

## Dependencies

* **Part A needs a runtime environment** — browser + provisioned PostgreSQL. Its absence is the same limitation recorded at PB003 certification, `PBDIGIT-29`, and the 2026-08-06 capability review.
* **Part B depends on Part A.** **Part C depends on Part B *and* on D-1…D-5.** **Part D depends on nothing.**

## Authorization status

```
Authorised now      nothing beyond Part D (independent hygiene defects)
Awaiting            Part A — a runtime environment and authorisation to use it
                    Part B — follows A
                    Part C — NOT SPECIFIABLE until B and D-1..D-5 complete
Code changed so far none
```

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
