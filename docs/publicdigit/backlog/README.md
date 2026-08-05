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

## Review process per story (6 phases — never reversed)

`1 Customer journey (no code)` → `2 Process discovery (business step → code)` → `3 Runtime verification (actually run it)` → `4 Product review (can a customer do this?)` → `5 Technical review (quality/DDD, only if it affects this story)` → `6 Improvement backlog (bug · missing · UX · debt · nice-to-have)`

**The route is a consequence of the business step, never the starting point.**

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
