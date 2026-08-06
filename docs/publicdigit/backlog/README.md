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

---

| [`PBDIGIT-35`](PBDIGIT-35-retire-the-legacy-global-voter-flags.md) | **Production code reads retired global voter flags** — `is_voter`/`can_vote` exist in no database; eligibility is org+election scoped | `EPIC-02` | `OPEN` — found by `PBDIGIT-00` F-1 |
| [`PBDIGIT-36`](PBDIGIT-36-the-e2e-suites-do-not-test-the-journey.md) | **The "end-to-end" suites do not test the journey** — 10 of 21 tests never reach an assertion | cross-cutting | `OPEN` — found by `PBDIGIT-00` F-2/F-3 |
| [`PBDIGIT-37`](PBDIGIT-37-test-credential-and-safety-claim-hygiene.md) | **Committed test credentials + a safety claim that is false on PostgreSQL** | cross-cutting | `OPEN` — credential deduplicated; the rest open |

---

## 🚧 [PBDIGIT-00](PBDIGIT-00-verify-the-journey-end-to-end.md) — Verify the journey end-to-end *(gates every epic above)*

| | |
|---|---|
| **Customer goal** | *"Prove that one complete journey works before anything is improved."* |
| **Why it is first** | every story below is evidenced in code and **none has been observed working**. Verification converts 24 inferences into facts at near-zero cost; building on unverified ground does not |
| **Evidence that already exists** | `tests/Feature/Phase6EndToEndIntegrationTest.php` · `tests/Feature/RealWorldVotingFlowTest.php` · `tests/Integration/CompleteVotingFlowIntegrationTest.php` · demo mode (`organisation_id = NULL`) · `docs/DEMO_ELECTION_GUIDE.md` |
| **Verification** | (a) run the three E2E suites against a provisioned Postgres; (b) walk the Level 0 journey once manually in demo mode, recording at each step: page · controller · DB rows · events · log files |
| **Status** | ✅ **(a) DONE 2026-08-06** — the suites ran. ⬜ **(b) PARTIAL** — needs a seeded demo election. **The environment block is lifted** |
| 🔴 **What (a) established** | **There is still NO automated end-to-end coverage of a complete election.** 21 tests: **10 errored before asserting anything**, 1 failed, 10 passed — and the 10 that pass test the *middleware chain*, not an election. Three findings became `PBDIGIT-35` · `PBDIGIT-36` · `PBDIGIT-37` |

**Until `PBDIGIT-00` completes, no story may be marked `VERIFIED`.** **It has not completed** — (b) is outstanding.

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
