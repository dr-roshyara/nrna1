# Election Product Readiness — Overview

**The one question this document answers:** *Can a real organization run an election with this software today?*
**Date:** 2026-08-05 · **Baseline:** `main` @ `9158ef11` · **Lens:** Product (Business → User Journey → Implementation → Architecture → Quality)
**Companion:** `2026-08-05-election-process-review-phase1.md` — the **Architecture Discovery Report** (internal consistency). This document deliberately does **not** repeat it.
**Placement:** derived — `--scope=product-specific --domain=publicdigit`

---

## ⚠️ The honest answer first

**Every capability below is evidenced as *implemented in code*. None is evidenced as *working at runtime* — and that gap is the single most important thing in this report.**

Three **end-to-end flow tests already exist**: `tests/Feature/Phase6EndToEndIntegrationTest.php` · `tests/Feature/RealWorldVotingFlowTest.php` · `tests/Integration/CompleteVotingFlowIntegrationTest.php`. They are the decisive evidence for "can a customer run an election" — and **they could not be executed in this environment** (no Postgres credentials; the same limitation recorded at PB003 certification).

> **So the highest-value next action is not writing code. It is running the election end-to-end once, in a working environment.** Until that happens, "ready to ship" is an inference; after it happens, it is a fact.

---

## 1. The eight customer questions

| # | Question | Answer | Evidence |
|---|---|---|---|
| 1 | Can an organization **create an election**? | **YES** (code) | `ElectionManagementController`; lifecycle `draft → submitted_for_approval → approved` with platform approval routes `routes/platform.php:21,24`; free plan ≤40 voters auto-approves (`ElectionConstitution`); `ElectionCreated` dispatched `Election.php:2276`; 3 test files |
| 2 | Can **candidates register**? | **YES** (code) | `CandidacyController` · `CandidacyApplicationController` · `Candidacy` model; constitutional action `apply_candidacy` with route `routes/organisations.php:281`; 7 test files |
| 3 | Can administrators **approve candidates**? | **YES** (code) | `Election/CandidacyReviewController` · `Election/CandidacyManagementController` — **this corrects the Architecture Discovery Report, which recorded "Evidence not found" for candidate verification** |
| 4 | Can **voters be verified**? | **YES** (code) | `VoterlistController` with approve/reject routes `routes/election/electionRoutes.php:202,203`; voter list management docs `docs/election/02-…04-…`; 5 test files |
| 5 | Can **voters vote**? | **YES** (code) | 5-step slug journey `electionRoutes.php:487-520` (`code/create` → `code` → `vote/agreement` → `vote/create` → `vote/submit` → `vote/verify`) via `CodeController`/`VoteController`; eligibility middleware `vote.eligibility`; double-vote prevention (3 test files + `docs/DOUBLE_VOTE_PREVENTION_ANALYSIS.md`); 11 test files |
| 6 | Can votes be **counted**? | **YES** (code) — *thin* | `ResultController::index` computes the tally inline: `groupBy('results.post_id','results.candidacy_id')` + `count()` + `array_sum` (`:29,36,38,67,74`); plus `statisticalVerification($postId)` `:106` and `verifyResults($postId)` `:170` |
| 7 | Can **results be published**? | **YES** (code) | `elections.publish` route `electionRoutes.php:280`; `PublishResults`/`UnpublishResults` console commands; `ResultsPublishedEvent` dispatched `ElectionManagementController.php:855`; PDF export `ResultController::downloadPDF:225`; 14 test files |
| 8 | Can an election be **audited**? | **YES** (code) | `app/Helpers/ElectionAudit.php` · `app/Models/ElectionAuditLog.php` · per-voter step tracking `app/Http/Middleware/VoterSlugStep.php`; per-voter log files documented in root `CLAUDE.md`; **17 test files — the highest count of any capability** |

**All eight are YES in code.** That is a materially more positive answer than the architecture review implied — because the architecture lens looked at events and the constitution, while the *work* is done by controllers and services.

---

## 2. Readiness dashboard

| Capability | Business | Implementation | Tests present | Runtime verified | Assessment |
|---|---|---|---|---|---|
| Create election | ✅ | ✅ | ✅ (3) | ⬜ | Implemented — verify |
| Candidate registration | ✅ | ✅ | ✅ (7) | ⬜ | Implemented — verify |
| Candidate approval | ✅ | ✅ | ⚠️ counted within candidacy (7) | ⬜ | Implemented — verify |
| Voter verification | ✅ | ✅ | ✅ (5) | ⬜ | Implemented — verify |
| Voting (5-step) | ✅ | ✅ | ✅ (11 + 3 double-vote) | ⬜ | Implemented — verify |
| **Counting** | ✅ | ⚠️ **tally is an inline controller query; no counting service or domain model** | ⚠️ within results (14) | ⬜ | **Implemented but architecturally thin — the one capability where a defect would be silent and consequential** |
| Results publication | ✅ | ✅ | ✅ (14) | ⬜ | Implemented — verify |
| Audit | ✅ | ✅ | ✅ (17) | ⬜ | Implemented — verify |

**Legend:** ✅ evidenced · ⚠️ evidenced with a caveat · ⬜ not verifiable in this environment. **No cell claims "Ready"** — that word requires runtime evidence nobody has yet produced.

**Test counts are file counts, not coverage.** Business-step coverage remains **Evidence not found** (§6 of the Architecture Discovery Report explains why the measurement attempt was invalid).

---

## 3. Product Gaps vs Architecture Debt

The distinction you asked for, applied to every finding of both reviews.

### 3a. Product gaps — could affect a customer running an election

| # | Gap | Customer-visible consequence | Evidence |
|---|---|---|---|
| **P-1** | **No end-to-end run has been executed** (or at least none is recorded) | Nobody can state that an election completes successfully today. This is a *knowledge* gap, not a code gap — and it gates every other judgement | three E2E test files exist, unexecuted here |
| **P-2** | **Counting has no domain model** — the tally is a query-builder aggregation inside `ResultController` | A wrong count is the worst possible failure for a voting product, and this is the least-protected part of the system: no domain service, no aggregate, no invariant, and (per §2) no dedicated counting test file by name | `ResultController.php:29,36,38,67,74` |
| **P-3** | **`unpublish_results` is ungoverned by the constitution** | A published result can be withdrawn through a path the constitution does not describe; authorization rests on `ElectionPolicy` alone. For an election product, "who may un-publish results, and is it recorded?" is a customer-trust question | absent from `ElectionAction`/`ElectionConstitution`; implemented in 6 places (§5b of the architecture report) |
| **P-4** | **Voter journey ↔ lifecycle vocabularies are disjoint** | Operational risk: nothing in the constitutional model expresses "voters are mid-journey", so the meaning of closing voting while sessions are open is undefined in the model | E-3, architecture report |
| **P-5** | **`/vote/submit_seleccted`** — misspelled public path, and voter-journey paths defined twice with unverified precedence | Possible wrong handler serving a voter action; a public URL carrying a typo | `electionRoutes.php:151-163` vs `:487-520` |
| **P-6** | Deployment documentation absent | Nothing describes how to stand this up for a customer | recorded in `PROGRAM_STATUS.md` (production readiness 🟡) |

### 3b. Architecture debt — does **not** block release

`L-1` unenforced *"NOWHERE ELSE"* invariant · `L-2` near-dead `ElectionState` enum · `E-1` three event namespaces (`Election` vs `Elections`) · `E-4` duplicate `ResultsPublished`, legacy class orphaned · `E-5` two dispatch idioms · `R-1` three route files / two prefixes · `R-4` commented-out route blocks · `C-1` no constitution→code reachability test · plus the carried PB003 register (M-1…M-4, guardrail chain, ~21 Membership test failures).

**None of these stops a customer from running an election.** They raise the cost of *changing* the software, not of *using* it.

---

## 4. What this changes about "what to build next"

Ordered by what the evidence supports, not by preference:

1. **Verify, don't build** — run the three existing E2E tests plus one manual election in a working environment. Cheapest possible action; it converts every ⬜ above into ✅ or a real bug list. **P-1.**
2. **Then, if and only if verification passes:** the two findings that touch customer trust rather than developer comfort — **P-2** (counting has no protection) and **P-3** (unpublish is ungoverned).
3. **Then** the small hygiene items with customer surface: **P-5** (typo/duplicate voter routes), **P-6** (deployment doc).
4. **Not now:** everything in §3b.

**No implementation is proposed or started here.** Items above are candidates for separate, individually authorized commissions.

---

## 5. Proposed review series (the sequence you outlined)

| # | Review | Question it answers | Status |
|---|---|---|---|
| 00 | Architecture Discovery | is the architecture internally consistent? | ✅ complete (`…-election-process-review-phase1.md`, rev 2) |
| — | **Product Readiness Overview** | can a customer run an election today? | ✅ **this document** |
| 01 | Election Creation | could a customer complete this step today? | not started |
| 02 | Candidate Management | " | not started |
| 03 | Nomination | " | not started |
| 04 | Voter Verification | " | not started |
| 05 | Voting | " | not started |
| 06 | **Counting** | " | not started — **highest risk (P-2), recommended first** |
| 07 | Results | " | not started |
| 08 | Audit | " | not started |

Each per-capability review would answer: implemented? · quality? · tested? · production-ready? — and end with a stop.

---

## Authorization boundary

| | |
|---|---|
| Code changed | **none** |
| Architecture proposed | **none** |
| Implementation started | **none** |
| Status | overview complete — **STOPPED**, awaiting selection of the next review or of the verification run |

**Traceability:** `app/Http/Controllers/{ResultController:18,29,36,38,67,74,106,170,225 · CandidacyController · CandidacyApplicationController · Election/CandidacyReviewController · Election/CandidacyManagementController · Election/ElectionManagementController:855 · VoterlistController}` · `app/Services/{RealVotingService,DemoVotingService,VotingService}.php` · `app/Helpers/ElectionAudit.php` · `app/Models/{ElectionAuditLog,Result,Candidacy}.php` · `app/Http/Middleware/VoterSlugStep.php` · `app/Console/Commands/{PublishResults,UnpublishResults}.php` · `routes/election/electionRoutes.php:151-163,202,203,280,487-520` · `routes/organisations.php:281` · `routes/platform.php:21,24` · `tests/Feature/{Phase6EndToEndIntegrationTest,RealWorldVotingFlowTest}.php` · `tests/Integration/CompleteVotingFlowIntegrationTest.php` · `docs/DOUBLE_VOTE_PREVENTION_ANALYSIS.md` · `docs/election/`, `docs/election_management/`

---

# §B — Appendix: alignment with the completed method (added 2026-08-06)

This document predates the method's four mandatory closing artifacts. **Aligned by addition; the body above is unchanged.** Unlike the Architecture Discovery Report, a customer journey *is* legitimately derivable here — the eight customer questions in §1 already are one, in question form. It is restated as a journey below, not invented.

## B.1 — Customer journey

```
Organisation created
        ↓
Election created                    → approved by the platform
        ↓
Election configured                 → posts, selection rules
        ↓
Candidates register                 → and are approved
        ↓
Voters registered                   → and verified, codes issued
        ↓
Voting opened  →  votes cast  →  voting closed
        ↓
Votes counted                       → and the count verified
        ↓
Results published                   → and exportable
        ↓
Election audited                    → and archived
```

Each step maps 1:1 to a question in §1 and a row in §2.

## B.2 — Capability readiness verdict

One line per capability — the executive answer the dashboard implied but never stated.

| Capability | Verdict |
|---|---|
| Create election | **Ready for verification** |
| Configure election | **Ready for verification** |
| Candidate registration | **Ready for verification** |
| Candidate approval | **Ready for verification** |
| Voter registration | **Ready for verification** |
| Voter verification | **Ready for verification** |
| Open / close voting | **Ready for verification** |
| Vote (5-step journey) | **Ready for verification — with a known route defect** (P-5) |
| **Count votes** | **⚠️ HIGH RISK** — no domain model, no named test (P-2) |
| Verify the count | **Ready for verification** |
| Publish results | **Ready for verification — governance gap** (P-3 unpublish) |
| Export results | **PARTIAL** — live PDF route unconfirmed |
| Audit | **Ready for verification** — best-tested capability |

**No capability is "Ready".** Eleven are *ready for verification*, one is high-risk, one is partial.

## B.3 — Risk heat map (business risk, not code severity)

| Risk | Finding | Why it ranks here |
|---|---|---|
| **Critical** | **Counting has no domain model** (P-2) — a query-builder aggregation in a controller, no named test | a wrong count is the worst possible failure for a voting product, and this is its least-protected capability |
| **High** | **No end-to-end run has ever been recorded** (P-1) | product readiness is *unknown*, not merely unproven — every other judgement inherits this |
| **High** | **Unpublishing sits outside the constitution** (P-3) | a published result can be withdrawn through an ungoverned path — a customer-trust question |
| Medium | Voter journey ↔ lifecycle vocabularies disjoint (P-4) | closing voting mid-ballot has no defined meaning in the model |
| Medium | Duplicate voter-journey route definitions (P-5) | a voter action may reach the wrong handler; precedence unverified |
| Medium | No deployment documentation (P-6) | blocks going live, not using the product |
| Low | `/vote/submit_seleccted` typo | cosmetic on a public path |
| Low | Architecture debt (§3b) | raises the cost of *changing*, not of *using* |

## B.4 — Business Outcome

```
Business Outcome

Today     The software appears capable of supporting a complete election —
          every one of the eight customer questions answers YES in code.
          But nobody has demonstrated that the complete journey succeeds at
          runtime, and the capability that produces the RESULT is the least
          protected part of the system.

Therefore Product readiness is UNKNOWN — not low, not high. Unknown.

Expected  One recorded end-to-end run, after which readiness becomes a fact
          instead of an inference, and counting is protected in proportion to
          its consequence.
```

## B.5 — Findings Table

| # | Finding | Type | Priority | Needs business decision | Needs code | Verified |
|---|---|---|---|---|---|---|
| **P-1** | No recorded end-to-end run — readiness is unknown | **Product** | **Critical** | No — needs an *environment*, not a decision | No | No |
| **P-2** | Counting has no domain model, service or named test | **Product** | **Critical** | Maybe — how defensible must the count be? | Yes | No |
| **P-3** | `unpublish_results` outside the constitution | **Product** | **High** | **Yes** — who may withdraw a result? | Yes | No |
| **P-4** | Voter journey ↔ lifecycle vocabularies disjoint | **Product** | Medium | **Yes** — what happens to a mid-ballot voter? | Yes | No |
| **P-5** | Voter-journey routes defined twice; precedence unverified | **Product** | Medium | No | Yes | No |
| **P-6** | No deployment documentation | **Technical** | Medium | No | Yes | No |
| **P-7** | `/vote/submit_seleccted` misspelled public path | **Product** | Low | No | Yes | No |
| — | Architecture debt register (§3b: `L-1`, `L-2`, `E-1`, `E-4`, `E-5`, `R-1`, `R-4`, `C-1`, `M-1`…`M-4`) | **Architecture / Technical** | Low–Medium | No | Yes | No |

## B.6 — Authorization boundary (appendix)

| | |
|---|---|
| Code changed | **none** |
| Architecture proposed | **none** |
| Solutions recommended | **none** — B.2/B.3 rank existing findings, they do not add any |
| Body of the original report | **unchanged** |
| Status | **STOPPED** — awaiting the verification run (`PBDIGIT-00`) |
