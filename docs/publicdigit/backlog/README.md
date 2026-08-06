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
| [`PBDIGIT-30`](PBDIGIT-30-active-organisation-business-lifecycle-discovery.md) | **Active Organisation Business Lifecycle** — business discovery, needs human answers | `EPIC-01` | `OPEN — awaiting business answers (B1–B9)` |

---

## 🚧 PBDIGIT-00 — Verify the journey end-to-end *(gates every epic above)*

| | |
|---|---|
| **Customer goal** | *"Prove that one complete journey works before anything is improved."* |
| **Why it is first** | every story below is evidenced in code and **none has been observed working**. Verification converts 24 inferences into facts at near-zero cost; building on unverified ground does not |
| **Evidence that already exists** | `tests/Feature/Phase6EndToEndIntegrationTest.php` · `tests/Feature/RealWorldVotingFlowTest.php` · `tests/Integration/CompleteVotingFlowIntegrationTest.php` — **never executed in a verified environment**; demo mode (`organisation_id = NULL`) exists for exactly this purpose (root `CLAUDE.md`); `docs/DEMO_ELECTION_GUIDE.md` |
| **Verification** | (a) run the three E2E suites against a provisioned Postgres; (b) walk the Level 0 journey once manually in demo mode, recording at each step: page · controller · DB rows · events · log files |
| **Status** | `BLOCKED` — needs a working environment (no Postgres credentials in the review environment) |

**Until `PBDIGIT-00` completes, no story may be marked `VERIFIED`.**

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

## Review process per capability (9 phases — never reversed)

The standard capability review, reusable for **any** capability (Elections, Membership, Finance, Appointments, …) — and, because it starts from the customer rather than from aggregates, for domains other than governance. Established by `PBDIGIT-29`; **customer-first, business-second, framework-last.**

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

### Every review closes with these three artifacts

**1 · Business Outcome** — the finding in one customer sentence, not one engineering sentence:

```
Business Outcome

Today     the customer cannot return to the organisation they were working in.
Expected  the customer always returns to the organisation they last chose.
```

**2 · Business Rule Matrix** — five independent levels of confidence:

`rule · designed · implemented · verified · automated test · evidence`

It is the bridge from discovery to testing. It separates *"code exists"* from *"it runs"* from *"a regression would be caught"* — and it asks of each test **which business rule it actually protects**, not merely whether a test exists.

**3 · Findings Table** — what happens next, for every finding:

| Finding | Type | Priority | Needs business decision | Needs code | Verified |
|---|---|---|---|---|---|
| *(one row per finding)* | Product · Technical · Architecture | High/Med/Low | Yes / No / Maybe | Yes / No | Yes / No |

**Product findings and Technical findings are never mixed.** A customer landing in the wrong organisation is a *product* finding; fifteen writers of a session key is a *technical* one. They compete for different attention and are prioritised differently.

### Three rules that make the method work

- **The route is a consequence of the business step, never the starting point.**
- **Facts · interpretation · decisions stay in separate sections.** State the evidence (*"no class of that name was found"*), then the reading of it (*"the concept appears implicit"*) — never one as the other.
- **Ask what a test protects, not whether one exists.** A green test can lock in the wrong behaviour (`PBDIGIT-29` §5, R5).

### ⛔ This method is FROZEN

**No further phases, templates or refinements** until it has been applied to **three** capabilities independently: **Election** ✅ (`PBDIGIT-29`) → **Organisation** ✅ (`../reviews/2026-08-06-organisation-capability-review.md`) → **Membership** ⬜.

**Cross-capability patterns so far (observation only, 2 occurrences each — not promoted):** an event dispatched with **no listeners** whose consequence is hard-coded in the dispatching controller · a **declared state that no code can reach**. The third review confirms or refutes them.

After three applications, it becomes eligible as a KnowledgeOS promotion candidate under `ES-006.1` — repository-independent, evidence-backed. Before that, it is a project convention with n=1. **Use it; do not improve it.**

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
