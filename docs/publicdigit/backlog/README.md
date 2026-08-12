# PublicDigit Product Backlog

**The product is an Organisation Governance Platform whose first major capability is running elections.** The customer journey therefore starts long before an election exists — it starts with an organisation.

**Placement:** derived — `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit`
**Distinct from** `docs/implementation/backlog/` (programme/engineering: `PB-nnn` tickets, `ENG-nnn` items, work packages). **Engineering debt lives there; what a customer can or cannot do lives here.**

---

## Level 0 — the customer journey

```
Create Organisation → Configure Organisation → Create Committees → Import Members → Approve Members
        ↓
Create Election → Configure Election → Candidate Registration → Candidate Approval → Voter Verification
        ↓
Open Voting → Vote → Close Voting → Count Votes → Publish Results → Audit → Archive
```

Every epic below is a segment of this one journey. Nothing in the backlog exists outside it.

---

## Epics

| Epic | Capability | Stories | Status |
|---|---|---|---|
| [`PBDIGIT-EPIC-01`](PBDIGIT-EPIC-01-organisation-management.md) | Organisation Management | `PBDIGIT-01`…`03` | `IMPLEMENTED — NOT VERIFIED` |
| [`PBDIGIT-EPIC-02`](PBDIGIT-EPIC-02-membership-management.md) | Membership Management | `PBDIGIT-04`…`08` | `IMPLEMENTED — NOT VERIFIED` |
| [`PBDIGIT-EPIC-03`](PBDIGIT-EPIC-03-election-management.md) | Election Management | `PBDIGIT-09`…`11` | `IMPLEMENTED — NOT VERIFIED` |
| [`PBDIGIT-EPIC-04`](PBDIGIT-EPIC-04-candidate-management.md) | Candidate Management | `PBDIGIT-12`…`14` | `IMPLEMENTED — NOT VERIFIED` |
| [`PBDIGIT-EPIC-05`](PBDIGIT-EPIC-05-voting.md) | Voting | `PBDIGIT-15`…`17` | `IMPLEMENTED — NOT VERIFIED` |
| [`PBDIGIT-EPIC-06`](PBDIGIT-EPIC-06-results-and-audit.md) | Results & Audit | `PBDIGIT-18`…`24` | `IMPLEMENTED — NOT VERIFIED` |

### Standalone defect stories

Small, low-risk defects get their own story file rather than waiting for their epic's review. They keep the same flat ID sequence.

| ID | Story | Epic | Status |
|---|---|---|---|
| [`PBDIGIT-25`](PBDIGIT-25-copy-button-must-not-submit-the-form.md) | Copy button must not scroll or submit the voting form (public demo) | `EPIC-05` | `FIXED — AWAITING RUNTIME VERIFICATION` |
| [`PBDIGIT-26`](PBDIGIT-26-copy-code-action-on-verification-page.md) | Add "Copy Code" action to the Voter Verification page | `EPIC-05` | `IMPLEMENTED — AWAITING BROWSER VERIFICATION` |
| [`PBDIGIT-27`](PBDIGIT-27-case-sensitive-import-paths-break-the-app-on-linux.md) | Case-sensitive import paths break the app on Linux (home page + build) | cross-cutting | `FIXED` — build green |
| [`PBDIGIT-28`](PBDIGIT-28-resolve-remaining-missing-module-imports.md) | Resolve the remaining missing-module imports (dead code, not runtime failures) | cross-cutting | `FIXED` — 0 unresolved live imports |
| [`PBDIGIT-29`](PBDIGIT-29-organisation-context-discovery.md) | **Organisation Context Discovery** — implementation discovery, no code (rev 2) | `EPIC-01` | `DISCOVERY COMPLETE — Q1–Q6 open` |
| [`PBDIGIT-30`](PBDIGIT-30-active-organisation-business-lifecycle-discovery.md) | **Working Organisation Business Lifecycle** — business decision record | `EPIC-01` | ✅ **CLOSED** — B1 · B2 · B3 · B10 approved; B4 withdrawn → `PBDIGIT-34`; B5–B7 answered by B1–B3 |
| [`PBDIGIT-31`](PBDIGIT-31-redirection-mechanism-review.md) | **Redirection Mechanism Review** — discovery only ([report](../reviews/2026-08-06-pbdigit-31-redirection-mechanism-review.md)) | `EPIC-01` | `REVIEW COMPLETE` — I-1 awaits authorisation; I-2 is now a one-line defect fix (RD-12) |
| [`PBDIGIT-32`](PBDIGIT-32-implement-working-organisation-routing.md) | **Implement Working Organisation Routing** — extend the resolver, do not redesign it | `EPIC-01` | ✅ **UNBLOCKED** — awaiting authorisation |
| [`PBDIGIT-34`](PBDIGIT-34-remember-the-working-organisation-across-logins.md) | **Remember the Working Organisation across logins?** — product *question*, not a requirement | `EPIC-01` | `OPEN — unprioritised`, blocks nothing |
| [`PBDIGIT-33`](PBDIGIT-33-fix-routing-cache-invalidation.md) | **Fix routing-cache invalidation** — observer was imported but never attached (+ UUID type fix) | cross-cutting | `FIXED — AWAITING RUNTIME VERIFICATION` |
| [`PBDIGIT-35`](PBDIGIT-35-retire-the-legacy-global-voter-flags.md) | **Production code reads retired global voter flags** — `is_voter`/`can_vote` exist in no database; eligibility is org+election scoped | `EPIC-02` | `OPEN` — found by `PBDIGIT-00` F-1 |
| [`PBDIGIT-36`](PBDIGIT-36-the-e2e-suites-do-not-test-the-journey.md) | **The "end-to-end" suites do not test the journey** — 10 of 21 tests never reach an assertion | cross-cutting | `OPEN` — found by `PBDIGIT-00` F-2/F-3 |
| [`PBDIGIT-37`](PBDIGIT-37-test-credential-and-safety-claim-hygiene.md) | **Committed test credentials + a safety claim that is false on PostgreSQL** | cross-cutting | `OPEN` — credential deduplicated; the rest open |
| [`PBDIGIT-38`](PBDIGIT-38-a-vote-cannot-be-saved.md) | ✅ **A vote cannot be saved** — audit write poisoned the vote transaction | `EPIC-05` | ✅ **FIXED & VERIFIED** — a vote persists, journey completes; I-2 residual → `PBDIGIT-42` |
| [`PBDIGIT-42`](PBDIGIT-42-define-audit-schema-semantics.md) | **Define `overlay_influence_chain` semantics** — decision required, not engineering's to make | `EPIC-06` | `OPEN` — blocks invariant I-2 only |
| [`PBDIGIT-43`](PBDIGIT-43-voting-code-never-populated.md) | **`demo_votes.voting_code` is never populated** — must derive from `vote_id`, never `code_id` (anonymity) | `EPIC-05` | `OPEN` — found verifying `PBDIGIT-38` |
| [`PBDIGIT-46`](PBDIGIT-46-components-registered-but-never-imported.md) | **Components registered but never imported crash their pages** — 5 live pages fixed | cross-cutting | ✅ **FIXED** · gate `OPEN` |
| [`PBDIGIT-47`](PBDIGIT-47-voter-not-routed-to-live-election.md) | 🔴 **A voter is sent to the organisation page during a live election** — routing reads a stale `status` column | `EPIC-05` | `OPEN` — **blocking**; workaround: `/election/select` |
| [`PBDIGIT-48`](PBDIGIT-48-election-state-has-four-representations.md) | **Login routing reads a deprecated field instead of the constitutional lifecycle** — an unfinished migration, not competing authorities | `EPIC-03` | ✅ **DISCOVERY COMPLETE** → `PBDIGIT-58` |
| [`PBDIGIT-58`](PBDIGIT-58-complete-legacy-election-state-migration.md) | **Complete the legacy election-state migration** — 7 capabilities to restore; `58A` observes before anything is migrated | `EPIC-03` | ⬜ **AWAITING AUTHORISATION** — Option B approved |
| [`PBDIGIT-59`](PBDIGIT-59-which-timestamps-are-constitutional.md) | 🟡 **Which timestamps are constitutional?** — the lifecycle reads `voting_*`, the legacy queries read `start_date`/`end_date`; they disagree for 4 of 4 elections | `EPIC-03` | **DECISION REQUIRED** — sequences `58B` |
| [`PBDIGIT-49`](PBDIGIT-49-voter-eligibility-has-two-homes.md) | **Voter eligibility has two homes** — `election_memberships` (3) vs `voters` (0); **corrects `PBDIGIT-35`** | `EPIC-02` | `OPEN` |
| [`PBDIGIT-50`](PBDIGIT-50-timezone-not-converted-for-display.md) | **Times not converted to the viewer's timezone** — device timezone, not residence (PO decision) | `EPIC-03` | `OPEN` |
| [`PBDIGIT-51`](PBDIGIT-51-error-pages-cannot-render.md) | **Error pages cannot render** — `symfony/config` missing, so every 500 hides its own cause | cross-cutting | `OPEN` |
| [`PBDIGIT-45`](PBDIGIT-45-unconfigured-safeguards-in-real-elections.md) | 🟡 **Who owns "votes per IP"?** — the Constitution does; the controller check is legacy H.3 with an unwired retirement flag ([discovery](../reviews/2026-08-06-who-owns-the-votes-per-ip-rule.md)) | `EPIC-05` | **DECISION REQUIRED** — do not implement |
| [`PBDIGIT-44`](PBDIGIT-44-locale-messages-break-pages-with-no-gate.md) | 🔴 **A locale string broke a public page** (`@` is vue-i18n syntax) — **fixed**; the missing i18n gate is open | cross-cutting | ✅ `@` class **FIXED — verified in production** · `{{ }}` class + gate `OPEN` |
| [`PBDIGIT-39`](PBDIGIT-39-unset-config-blocks-every-voter.md) | **IP vote limit wrongly applied to demo elections** + no safe default for real ones | `EPIC-05` | ✅ demo exemption **FIXED & VERIFIED** · real-election default `OPEN` |
| [`PBDIGIT-40`](PBDIGIT-40-completion-page-has-never-worked.md) | **`thank-you` route 500s** — undefined `$vote`. **NOT on the happy path** (severity corrected) | `EPIC-05` | `OPEN` — **Low** |
| [`PBDIGIT-41`](PBDIGIT-41-demo-provisioning-has-two-paths.md) | **Demo provisioning has two paths and the documented one is broken** | `EPIC-03` | `OPEN` — found by `PBDIGIT-00` (b) |
| [`PBDIGIT-65`](PBDIGIT-65-voter-eligibility-decided-by-tenant-context.md) | 🔴 **A voter's eligibility depends on which page they last visited** — a valid `ElectionMembership` is hidden by tenant scope, at the *enforcement* boundary. Root cause proven by A/B | `EPIC-05` | `OPEN` — repair owner undecided |
| [`PBDIGIT-66`](PBDIGIT-66-establish-election-only-mode-constitutionally.md) | 🟡 **Establish Election-Only in the constitution and the projection** — it works end to end and the constitution never mentions it; both modes converge at `ElectionMembership` ([investigation](../reviews/2026-08-09-election-constitution-voter-eligibility-traceability.md)) | `EPIC-03` | **DECISION REQUIRED** — `D-1`…`D-6`; not engineering's to resolve |
| [`PBDIGIT-70`](PBDIGIT-70-two-unparseable-files-under-app.md) | **Two files under `app/` cannot be parsed by PHP** — both dead/unreachable, but any full static-analysis sweep of `app/` fails; `ElectionUser` must not be mistaken for `Q3` prior art | cross-cutting | `OPEN` — delete-vs-repair is a disposition call |
| [`PBDIGIT-69`](PBDIGIT-69-voter-eligibility-cache-is-tenant-unaware.md) | 🔴 **The voter-eligibility cache key is tenant-unaware** — a wrong-context read poisons the ballot predicate for 300s, in **both** directions ([measured](../reviews/2026-08-12-g1-voter-suspension-enforcement.md)) | `EPIC-02` | `OPEN` — **`D-1` is a business decision**; makes `PBDIGIT-65` persist |
| [`PBDIGIT-68`](PBDIGIT-68-election-membership-is-a-durable-entitlement.md) | ✅ **`D-ENT-1` APPROVED — Model B** (PO 2026-08-12) · **`BR-1` PARTIALLY RESOLVED** — Option B recommended (termination retains the record, permanent to officers, admin-reversible); `revoke` is already taken with an inverted meaning; `ADR-T11` blocks post-vote reach; **authority does not track consequence** and **no governance act reaches the election audit trail** · **`ADR-002` amendment PROPOSED · `Q3` OPEN · IMPLEMENTATION NOT AUTHORISED** — closes Appendix E's `NOT SPECIFIED`. ⚠️ **G-1 MEASURED 2026-08-12: the ballot IS protected (403); enforcement is incidental** — `G-6` withdrawn, `G-5` escalated, and `admit`/`revoke`/`restore` have no constitutional names | `EPIC-03` | **DECISION RECORDED** · consequences `G-1`…`G-7` open; Constitution **not** amended |
| [`PBDIGIT-67`](PBDIGIT-67-election-schedule-input-interprets-local-time-as-utc.md) | 🔴 **The election schedule input interprets browser-local time as UTC** — an election opens 1–2 hours late (DST-variable); **corrects `PBDIGIT-50`'s "display gap, not a data defect"** ([trace](../reviews/2026-08-12-time-value-trace-browser-to-display.md)) | `EPIC-03` | `OPEN` — **not authorised**; `D-1`…`D-4` need business answers |
| [`PBDIGIT-64`](PBDIGIT-64-election-opens-voting-without-any-candidate.md) | 🔴 **An election can enter voting with no candidates** — `complete_nomination` enforces the rule, the automatic window transition does not. Reproduced end to end | `EPIC-03` | `OPEN` — PO-confirmed defect; found by IERVP runtime verification |
| [`PBDIGIT-63`](PBDIGIT-63-verify-the-organisation-creation-journey-and-its-verification-estate.md) | 🟢 **"Create an organisation → land on its homepage" — RUNTIME-VERIFIED end to end 2026-08-08** (first recorded execution; persisted outcome checked in the database). Six creation assertions still describe a different contract from the controller — **classify before repairing**; corrects rule `O1`'s automated-test *evidence* ([report](20260808-create-organisation-and-visit-homepage-journey-report.md)) | `EPIC-01` | **Part A ✅ DONE** · Part B unblocked, unauthorised · browser render + `F-5` walk still unverified · `D-1`…`D-6` need business answers |

---

## 🚧 [PBDIGIT-00](PBDIGIT-00-verify-the-journey-end-to-end.md) — Verify the journey end-to-end *(gates every epic above)*

| | |
|---|---|
| **Customer goal** | *"Prove that one complete journey works before anything is improved."* |
| **Why it is first** | every story below is evidenced in code and **none has been observed working**. Verification converts 24 inferences into facts at near-zero cost; building on unverified ground does not |
| **Evidence that already exists** | `tests/Feature/Phase6EndToEndIntegrationTest.php` · `tests/Feature/RealWorldVotingFlowTest.php` · `tests/Integration/CompleteVotingFlowIntegrationTest.php` · demo mode (`organisation_id = NULL`) · `docs/DEMO_ELECTION_GUIDE.md` |
| **Verification** | (a) run the three E2E suites against a provisioned Postgres; (b) walk the Level 0 journey once manually in demo mode, recording at each step: page · controller · DB rows · events · log files |
| **Status** | ✅ **(a) DONE · (b) DONE — 2026-08-06.** Both verifications ran. **The result is negative, and that is the deliverable** |
| 🔴 **What (a) established** | **There is NO automated end-to-end coverage of a complete election.** 21 tests: **10 errored before asserting anything**, 1 failed, 10 passed — and the 10 that pass test the *middleware chain*, not an election. → `PBDIGIT-35` · `PBDIGIT-36` · `PBDIGIT-37` |
| 🔴 **What (b) established** | **The journey was walked end to end for the first time. Steps 1–4 worked; the vote was never saved.** The security middleware chain and step-order enforcement work correctly. → `PBDIGIT-38` · `PBDIGIT-39` · `PBDIGIT-40` · `PBDIGIT-41` |
| ✅ **Since resolved** | **`PBDIGIT-38` is FIXED and VERIFIED (2026-08-06): a vote now persists and the journey completes.** This record is historical and is **not** rewritten — a closed verification is evidence with a date on it. Re-walking after a fix verifies *that fix*, not `PBDIGIT-00` |

**Until `PBDIGIT-00` completes, no story may be marked `VERIFIED`.** **It has now run — and nothing may be marked `VERIFIED`, because the journey does not complete.** `PBDIGIT-38` is the gate that replaced it.

**One box did close as a side effect:** `GET /` returns **200 / 89 550 bytes**, so the home page renders — `PBDIGIT-27`'s last unchecked item.

---

## ID conventions

**Stories: `PBDIGIT-nn`** — one flat sequence across the whole product backlog (`PBDIGIT-00`…`PBDIGIT-24` today; the next epic continues from `PBDIGIT-25`). Uppercase, matching the repository's existing ID style (`PB-003`, `ENG-012`, `ES-004`, `ADR-T20`).
**Epics: `PBDIGIT-EPIC-nn`.**
**Never reuse a number**, even for a cancelled story — an ID names one thing permanently (precedent: the `R-89`/`R-90` collision recorded in the platform rulings register).
**`PBDIGIT-nn` ≠ `PB-nnn`** — different backlogs, different authorities.

## How a story is written

Every story answers one question: **"Could a customer complete this step today?"** — and carries its own evidence.

| Section | Contains |
|---|---|
| **Customer goal** | one sentence, in the customer's words. No framework nouns |
| **Business steps** | what the customer does, in order |
| **Route → code** | business step → route → controller → service/domain → model → events (`file:line` where known) |
| **Business rules evidenced** | the rules the code actually enforces |
| **Verification** | the concrete actions that would prove it works |
| **Known findings** | product gaps (`P-n`) and architecture debt touching this story |
| **Status** | see legend |

## Status legend

| Status | Meaning |
|---|---|
| `IMPLEMENTED — NOT VERIFIED` | code + routes (+ tests) evidenced; never observed working end-to-end |
| `PARTIAL` | some steps evidenced, others `Evidence not found` |
| `VERIFIED` | a human ran it and recorded the result |
| `BLOCKED` | waiting on a named dependency |

## Product Capability Review — 9 phases, never reversed

**Name (proposed, 2026-08-06):** *Product Capability Review*. Earlier working names — "Architecture Discovery", "Capability Review" — no longer describe it: every application now starts from the customer, measures a business capability, separates product issues from technical debt, and ends in evidence-based product readiness. **The name is proposed for use at promotion time; the documents already written keep their titles** (renaming them would break the record of the method's evolution).

Reusable for **any** capability (Elections, Membership, Finance, Appointments, …) — and, because it starts from the customer rather than from aggregates, for domains other than governance. Established by `PBDIGIT-29`; **customer-first, business-second, framework-last.**

| Phase | Question it answers | Output |
|---|---|---|
| **0 Customer journey** | what is the customer *trying to accomplish*? goals and expectations — **not** rules, **not** events, **no code** | the human story: "I want my association on the platform… I come back tomorrow and expect to be where I left off" |
| **1 Business lifecycle** | which **business events** happen, in order? | the yardstick everything else is measured against |
| **2 Business events** | which business events exist, who owns each consequence? | event table: class exists? · dispatched? · **listened to?** · who actually carries the consequence |
| **3 Route map** | which routes correspond to each business step? | step → route |
| **4 Controller / implementation map** | route → controller → service → domain → model → persistence | step-by-step mapping with `file:line` |
| **5 DDD analysis** | is there one authority? is the concept modelled? is responsibility duplicated? | interpretation, kept separate from facts |
| **6 Code quality** | duplication · dead code · complexity — **only where it affects this capability** | findings, not a general audit |
| **7 Runtime verification** | does a customer actually get through it? | observed behaviour, or an explicit *not verified* |
| **8 Improvement backlog** | what follows? | new `PBDIGIT-nn` items — never prose advice |

### Every review closes with these four artifacts

*(plus a **Method Assessment** that must answer **"what surprised us?"** — an expected-vs-observed table. A surprise is evidence the method investigated rather than confirmed; its absence is a warning sign.)*

**1 · Business Outcome** — the finding in one customer sentence, not one engineering sentence:

```
Business Outcome

Today     the customer cannot return to the organisation they were working in.
Expected  the customer always returns to the organisation they last chose.
```

**2 · Business Rule Matrix** — five independent levels of confidence:

`rule · designed · implemented · verified · automated test · evidence`

It is the bridge from discovery to testing. It separates *"code exists"* from *"it runs"* from *"a regression would be caught"* — and it asks of each test **which business rule it actually protects**, not merely whether a test exists.

**3 · Capability Verdict + Findings Table** — the thirty-second read, then what happens next:

*Capability Verdict* — one line per area: `Ready for verification` · `PARTIAL` · `NOT OPERATIONAL` · `IMPOSSIBLE`. **Never "Ready"** without runtime evidence.

| Finding | Class | Type | Priority | Confidence | Needs business decision | Needs code | Verified |
|---|---|---|---|---|---|---|---|
| *(one row)* | BROKEN · MISSING · INCONSISTENT | Product · Architecture · Technical | High/Med/Low | High/Med/Low | Yes / No / Maybe | Yes / No | Yes / No |

- **Class** encodes the **remediation path**, which Type does not: *BROKEN* = exists but cannot execute (wire it or delete it) · *MISSING* = wanted but never built (decide, then build) · *INCONSISTENT* = works, described more than once (consolidate).
- **Confidence** states how strongly the evidence supports the conclusion. Anything resting on an interpretation, or on someone else's estimate rather than a `file:line` fact, is **not High**.
- **Product findings lead with customer impact** — *"the customer cannot declare how their organisation is governed"*, not *"`activateGovernance` is unreachable"*. Technical findings stay technical.

**Product findings and Technical findings are never mixed.** A customer landing in the wrong organisation is a *product* finding; fifteen writers of a session key is a *technical* one. They compete for different attention and are prioritised differently.

**4 · Authorization Boundary** — mandatory, stated as a table of negatives:

```
Code changed          none
Architecture proposed none
Solutions recommended none
Status                STOPPED — awaiting <named decision>
```

It makes the commission's scope unambiguous *afterwards*, not only in intent — and it is the artifact that stops a review from quietly becoming a redesign.

### Three rules that make the method work

- **The route is a consequence of the business step, never the starting point.**
- **Facts · interpretation · decisions stay in separate sections.** State the evidence (*"no class of that name was found"*), then the reading of it (*"the concept appears implicit"*) — never one as the other.
- **Ask what a test protects, not whether one exists.** A green test can lock in the wrong behaviour (`PBDIGIT-29` §5, R5).
- **A discovery report describes reality; it never prescribes implementation.** Every finding states *what is true · why it matters to the business · what decision is required* — and stops. Naming a mechanism ("add these priorities", "attach this observer", "extract this responsibility") quietly moves a decision from the implementation story to the reviewer, and it is often wrong: `PBDIGIT-31` called one such remedy a "one-line fix" that turned out to need two changes, the first of which would have broken organisation creation. **Rank findings by impact and risk — that is the reviewer's job; choosing the mechanism is not.**
- **Business discovery finds the rules a product already implies; it never designs the product's future.** The moment a discovery story starts answering *"what would be a good product?"* instead of *"what does this product already require?"*, it has become design and must be split into an enhancement story. Warning sign, observed for real: a discovery draft writing *"this creates work"* — a business rule had begun driving architecture, when architecture exists to support the business. *(`PBDIGIT-30` B4 → `PBDIGIT-34`.)*
- **Lead with customer impact, not class names.** *"The customer may return to the wrong organisation for up to five minutes"* comes before `DashboardResolver`. Evidence follows the impact; it never replaces it.

### ⛔ This method is FROZEN

**Applied to three capabilities independently — the freeze's condition is now met:** **Election** ✅ (`PBDIGIT-29`) → **Organisation** ✅ (`../reviews/2026-08-06-organisation-capability-review.md`) → **Membership** ✅ (`../reviews/2026-08-06-membership-capability-review.md`).

**The planned validation cycle is COMPLETE. Evidence is now sufficient for a Decision Authority review** — *not* for promotion, which does not follow automatically from validation:

```
Validation complete  →  Evidence available  →  Decision Authority review  →  possible promotion
```

**Three applications inside one repository cannot demonstrate repository-independence** — that is exactly the question the Decision Authority must weigh, and `OQ-2` (where cross-product research lives) remains unruled. Evidence from application #3: high-value findings **without** any of the five parked refinements, and the method **refuted its own leading hypothesis**.

**Cross-capability patterns after three applications (observation only — nothing promoted).** Each is stated at the abstraction that explains **all three** sightings, not the symptom of the first:

| Pattern (abstracted) | Election | Organisation | Membership | Verdict |
|---|---|---|---|---|
| Event dispatched with no listeners, consequence hard-coded in the controller | ✅ | ✅ | ❌ | **refuted at 2/3** — a legacy-code trait, not a repository trait |
| **The business lifecycle has no single authoritative representation** | ✅ | ✅ | ✅ | **3/3** — repeated observation |
| **Multiple competing representations of one business concept** | ✅ | ✅ | ✅ | **3/3** — repeated observation |

*(Rows 2–3 were first phrased as "a declared state that cannot be reached" and "the same concept defined twice" — the symptoms of their first sighting, which could not explain Membership's competing-state-models form. Re-abstracted after application #3; recorded rather than silently substituted.)*

Three observations is *repeated observation*, not a standard (`ES-006.1`). The **refutation** is the most useful row: it shows the method discriminates rather than confirming what it looked for first.

## ⛔ The freeze, with an actual gate

**Honest record: the freeze has been declared and then breached four times** (Phase 0 + Business Outcome + Findings Table → "what surprised us" + pattern re-abstraction → Authorization Boundary + naming → Capability Verdict + Class + Confidence). Every round was individually justified. **That is what methodology inflation looks like from the inside** — nobody ever adds a phase for a bad reason.

**The cause is structural, and it is the same defect this method keeps finding in the code:** a rule that is *declared* but has no *enforcement point*. See the 3/3 pattern *"the business lifecycle has no single authoritative representation"* — our own process had exactly that shape.

**So the freeze now has a gate rather than an intention:**

| | |
|---|---|
| **Refinements may only be adopted** | at a **Method Retrospective**, never during or immediately after a review |
| **A retrospective may be held** | after **three further applications** (target capabilities: Finance · Appointment · Audit · Notification · Authentication — any three) |
| **Applications to date** | **3** — Election · Organisation · Membership |
| **Next retrospective eligible at** | **6 applications** |
| **In between** | refinement candidates are **parked in writing** (see the parked list above) and **not applied**, no matter how well justified |
| **Exception** | a refinement may be adopted mid-cycle **only** if a review could not be completed without it — a blocking defect, not an improvement |

**Promotion is further out still:** ~8–10 applications, then a Decision Authority review of whether the value is repository-independent. Validation ≠ promotion.

**Process status: ROUTINE USE — parked, not evolving.**

---

## Product gaps carried across epics

| # | Gap | Epic |
|---|---|---|
| **P-1** | no recorded end-to-end run | `PBDIGIT-00` |
| **P-2** | counting has no domain model — tally is an inline controller query | `EPIC-06` |
| **P-3** | `unpublish_results` is not a constitutional action | `EPIC-06` |
| **P-4** | voter journey ↔ lifecycle vocabularies disjoint | `EPIC-05`/`06` |
| **P-5** | voter-journey routes defined twice; `/vote/submit_seleccted` misspelled | `EPIC-05` |
| **P-6** | no deployment documentation | cross-cutting |

**Architecture debt is deliberately excluded from this backlog** (`L-1`, `L-2`, `E-1`, `E-4`, `E-5`, `R-1`, `R-4`, `C-1`, and the PB003 register `M-1`…`M-4`). It raises the cost of *changing* the software, not of *using* it — see `../reviews/2026-08-05-election-process-review-phase1.md`.

**Source reviews:** `../reviews/2026-08-05-election-product-readiness-overview.md` (product lens) · `../reviews/2026-08-05-election-process-review-phase1.md` (architecture discovery)

### 🅿️ Post-freeze refinement candidates — RECORDED, NOT APPLIED

Proposed during the review of application #2 (Organisation). **Deliberately not applied: the freeze forbids refinement before three applications.** Re-evaluate only after Membership, and only if the third application shows they are needed.

| # | Candidate | Rationale offered |
|---|---|---|
| C-1 | **Capability Summary** right after Phase 0 — purpose · primary actor · business value · success criteria · current status | a reader knows what the capability *is* before reading 20 pages |
| C-2 | **Capability Readiness** verdict — *can a customer use this today: YES / PARTIALLY / NO*, with "customer can / customer cannot" lists | one executive answer instead of inference across sections |
| C-3 | Split the confidence ladder further: **designed → implemented → reachable → used → verified** | the Organisation governance finding proves *implemented ≠ reachable* |
| C-4 | Structured **cross-capability pattern table** with a count column across all reviewed capabilities | accumulated evidence, not prose observations |
| C-5 | Rename/duplicate `Verified` as **`Observed`** — someone actually watched it happen, vs. a test asserting it | *(the proposer's own note: consider after more applications, not now)* |

**Do not apply these while the freeze stands.** If the Membership review produces high-value findings without them, that is evidence they are optional — which is itself worth knowing.

### 🅿️ Second parked set — proposed 2026-08-08 (Principal Architect / DDD discovery commission)

Proposed by the Product Owner at the review of the organisation-creation journey check ([report](20260808-create-organisation-and-visit-homepage-journey-report.md) · `PBDIGIT-63`). **Recorded verbatim in `.claude/sessions/2026-08-08.md`; NOT applied.**

| # | Candidate | Rationale offered |
|---|---|---|
| C-6 | **Start from the business outcome, never from "is it broken?"** — the latter frames discovery around implementation failure before the capability is defined | a journey is a business object; the route is evidence |
| C-7 | **Layered ownership analysis** — business outcome → capability → domain invariants → **decision ownership** → application → authorization → interface → persistence → verification, never inverted | prevents a technical mechanism being mistaken for the owner of a business decision |
| C-8 | **Explicit evidence classification** — `OBSERVED IN CODE` · `OBSERVED AT RUNTIME` · `TESTED` · `INFERRED` · `NOT VERIFIED` · `BUSINESS DECISION REQUIRED` · `MECHANISM NOT ESTABLISHED` | makes "statically wired" structurally impossible to report as "works" |
| C-9 | **Test-classification taxonomy** — `VALID` · `OBSOLETE` · `CONTRACT DRIFT` · `FIXTURE DRIFT` · `WRONG LAYER` · `IMPLEMENTATION-COUPLED` · `COVERAGE GAP` · `UNDETERMINED`, applied *before* any repair is contemplated | a divergent test has ~8 possible causes; "repair it" presumes one |
| C-10 | **Specified report structure** (22 sections) and **specified ticket structure**, with acceptance criteria stated as business behaviour rather than mechanism | *"the creator reaches the homepage without reconstructing the URL"*, not *"the controller returns 302"* |
| C-11 | **Severity discipline** — *absence of verification is not proof of failure* · *static wiring is not proof of runtime correctness* · *a failing test is not proof production is wrong* · establish the business invariant **before** naming a security defect | the 2026-08-08 review found the report's *summary* outran evidence its *findings* had earned |

**Revised twice more the same day.** The commission went through three drafts on 2026-08-08: **v1** (filed verbatim, commit `b6467e35`) → **v2** (general; added the self-limiting governance clause — never filed, superseded before it was) → **v3** (the operative one: scoped to the *next session* on this journey; verbatim in `.claude/sessions/2026-08-08.md`). **v2 is recorded but not reproduced** — it was an unfiled intermediate draft, and reproducing every draft would bury the operative one. Its one durable contribution is `C-12`.

**Additional candidates from v2/v3 — same freeze, same parking:**

| # | Candidate | Rationale offered |
|---|---|---|
| C-12 | **Governance precedence — the commission defers to the repository's adopted method and forbids implicit governance** | *"Governance must never be inferred from the fact that a better method would be useful"* — **the proposal limits itself**, which is what makes it safe to park *and* safer to adopt |
| C-13 | **Coverage ≠ completeness** — *complete · missing · gap* are **normative** claims requiring a business contract; without one, record `UNDETERMINED` | applied immediately in the claim-weakening direction: it withdrew `F-6`'s `MISSING` class |
| C-14 | **Persisted-record classification by writers *and readers*, never by shape** — immutable + actor + reason ≠ audit record; a reader's decision is what confers meaning | **an observation only.** It resembles questions open in `PBDIGIT-42` and `PBDIGIT-59`, **and it does not govern them** — those belong to a different workstream and are not reinterpreted from here |
| C-15 | **`GREEN` ≠ verified · `RED` ≠ production defect** — trace the mechanism before classifying either | a test can pass because middleware was bypassed or a compatibility shim supplied the value |
| C-16 | **Summary calibration as a first-class discipline** with its own pre-write interrogation of every sentence | the 2026-08-08 evidence: findings held, the summary did not |
| C-17 | **Keep three questions apart** — *does the capability exist? · does the implementation realize it? · can the verification estate be trusted?* | all three held different values on one journey; a single verdict overstates at least two |

### 🔴 Open governance question raised by the proposal itself

**`OQ-M1` — the commission forbids adopting the machinery it simultaneously instructs.** v3 §1 and §20 say *"do not adopt the parked C-6…C-11"* and *"do not introduce new taxonomies"*; v3 §7, §10 and §19 then **require** a test-classification taxonomy, an evidence taxonomy and a 17-item completion checklist — which **are** `C-8`, `C-9` and `C-10`. A fresh session meets a direct contradiction and will resolve it silently, **which is the exact implicit-governance failure §1 exists to prevent.**

**Engineering will not resolve this.** Two clean resolutions exist, and both are Product Owner acts: **(a)** invoke the freeze's own mid-cycle clause — *"only if a review could not be completed without it"* — and adopt `C-8`/`C-9`/`C-10` **scoped to this journey**, or **(b)** mark those sections as applying **only in the claim-weakening direction**, which is already permitted and needs no adoption at all. **(b) costs nothing and is available today.**

**Also parked, and noted rather than acted on:** the method's own Capability Verdict vocabulary includes **`Ready for verification`**, which `C-13` shows is itself a **normative** label — *ready* asserts nothing further is required. The 2026-08-08 report withdrew it as *its own* verdict and replaced it with four factual clauses, **but the method's vocabulary at *Capability Verdict* above is untouched**: changing adopted method vocabulary is governance, not engineering. **Retrospective candidate.**

### 🔴 `OQ-M2` — the three-question separation is simultaneously "a permanent programme principle" and on the do-not-adopt list

At the 2026-08-08 review the Product Owner wrote of `C-17`: *"I would make this a permanent programme principle. It is more fundamental than any particular taxonomy."* **The same message's commission §1 lists `C-17` among the candidates not to be silently adopted.**

**Engineering will not resolve this either — it is the same class of question as `OQ-M1`.** What engineering *has* done needs no adoption: the distinction is **used descriptively** in the 2026-08-08 report and in `PBDIGIT-63` to state findings more precisely. **Using an analytical distinction to make a weaker, truer claim is always permitted; declaring it a permanent programme principle is a governance act and requires one explicit sentence.** *"I would"* is an intention; it is not read as ratification.

### 📌 The governing rule this review produced, recorded verbatim

> **A technically observable difference is not yet a business defect. A business obligation must exist before its absence can be called a defect.**

**Applied to itself the same day, it withdrew four of this report's own classifications:** `Ready for verification` (readiness is normative), `F-6 MISSING` (completeness is normative), **`F-2` as a security defect** (the invariant cited turned out to be `PBDIGIT-37`, which concerns credentials in *tracked files* and never mentions logging, plus a line from the operator's *private* AI-instruction file — **not a PublicDigit contract**), and **Part D as a pre-authorised repair list** (two of its three items had no obligation behind them). **`D-6` now asks whether the platform has any policy on secrets in application logs. None was found.**

### 🔀 Workstream boundary — `PBDIGIT-63` and `PBDIGIT-48` are separate, and one rule now has two governance states

**Two investigations ran concurrently on 2026-08-08:** `PBDIGIT-63` (organisation-creation journey — this backlog) and `PBDIGIT-48` (election verification estate — `docs/plans/…election-verification-execution-plan.md`). **Both independently reached similar analytical conclusions. That is methodological convergence, and convergence is evidence, not authorisation.**

**Observed, and deliberately left unresolved:** the rule *"coverage is descriptive, completeness is normative"* is **`BOUND`** in the `PBDIGIT-48` execution plan (commit `5e9dc5f5`) and **`PARKED` as `C-13`** here. **One rule, two governance states, one repository.** Whether that is a contradiction or simply two streams legitimately governed by their own adopted rules **is a governance question, not an engineering one** — recorded so a future reader does not find the two states and assume one is a mistake.

**The boundary has held so far, and was checked rather than assumed:** `5e9dc5f5` touched only that stream's plan and nothing in `docs/publicdigit/backlog/`; the `PBDIGIT-63` artifacts have been touched only by the four `PBDIGIT-63` commits. **Neither stream's findings are absorbed, superseded or reinterpreted by the other**, and nothing here claims authority over `PBDIGIT-42`, `PBDIGIT-59` or the election plan.

### ⚠️ A practical cost of `OQ-2` is now visible

**Four commission drafts were produced on 2026-08-08.** Because `doc-placement.php --scope=cross-product --maturity=research` returns **unruled (`OQ-2`)**, each operative draft has been parked verbatim in the **append-only** session log. **That log now carries two full commissions and will carry more.** This is a workaround, not a home: **the placement question has moved from theoretical to costly, and ruling `OQ-2` would let successive drafts supersede one another in one artifact instead of accumulating.** Recorded as evidence for that ruling; **no folder was invented in the meantime.**

**Three of these (C-8, C-9, C-11) were partially exercised in the 2026-08-08 correction — and that is not adoption.** They were used only in the *claim-weakening* direction the existing rules already require (*"the strongest statement made must never exceed the strength of the available evidence"*). **No new phase, section or mandatory artifact was added.** Adopting a weaker claim is always permitted; adding machinery is what the gate governs.

**Placement of the full commission text is `PENDING`** — `php scripts/doc-placement.php --scope=cross-product --maturity=research` returns *unruled, ref `ADR:OQ-2`*, the same open question this README already records. **Escalated, not guessed at.**

**Applications to date remain 3.** The retrospective is still eligible at **6**. **One Product Owner sentence — declaring a Method Retrospective, or an explicit mid-cycle exception — adopts any of C-1…C-11. That is a governance act and engineering will not perform it implicitly.**
