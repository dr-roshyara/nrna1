# PBDIGIT-EPIC-06 — Results & Audit

**Journey segment:** `Close Voting → Count Votes → Publish Results → Audit → Archive`
**Epic status:** `IMPLEMENTED — NOT VERIFIED` · **Gated by** `PBDIGIT-00`; **depends on** `PBDIGIT-17`
**Baseline:** `main` @ `9158ef11` · **Created:** 2026-08-05

> **This epic carries the product's highest risk.** A wrong count or an unexplainable result is the worst possible failure for a voting platform, and counting is the least-protected capability in the codebase (`P-2`).

---

## PBDIGIT-18 — Close voting

| | |
|---|---|
| **Customer goal** | *"I want to close the ballot at the deadline, and be sure nothing more can be cast."* |
| **Business steps** | confirm the deadline has passed → close voting → no further votes accepted → state `counting` |
| **Route → code** | `elections.close-voting` `routes/election/electionRoutes.php:293` → `Election/ElectionManagementController` → constitution `close_voting` → event `VotingClosed` (dispatch map ~`Election.php:1695`); state `voting_active → counting`; auto-transitions `tests/Feature/Console/ProcessElectionAutoTransitionsTest.php` |
| **Business rules evidenced** | tutorial precondition: *"Ensure voting deadline has passed"* then *"No new votes can be cast after this point"* (`docs/election-management-tutorial.md`) |
| **Verification** | close → attempt a vote → expect refusal; confirm state `counting`; **confirm what happens to a voter who was mid-journey when closing occurred** |
| **Known findings** | **P-4** — the model has no concept of a voter mid-journey, so the mid-session case is undefined in the model. This is the one place where `P-4` becomes customer-visible: a voter could lose a partially-cast ballot with no defined outcome |
| **Status** | `IMPLEMENTED — NOT VERIFIED` |

## PBDIGIT-19 — Count the votes ⚠️ highest risk in the product

| | |
|---|---|
| **Customer goal** | *"I want a correct count that I can defend if it is challenged."* |
| **Business steps** | tally votes per post and per candidate → include the no-vote option → produce figures |
| **Route → code** | `app/Http/Controllers/ResultController::index():18` — the tally is computed **inline**: `groupBy('results.post_id','results.candidacy_id')` `:36` · `groupBy('post_id')` `:38` · `count()` `:29,67` · `array_sum(array_column($candidates,'vote_count')) + $noVoteCount` `:74`; models `app/Models/Result.php` · `BaseResult.php` · `DemoResult.php`; services `RealVotingService` / `DemoVotingService` (`getElectionStatistics():76`) |
| **Business rules evidenced** | results are per **post** and per **candidacy**; the **no-vote option** is counted; demo and real paths are separate models |
| **Verification** | cast a **known** ballot set (e.g. 7 ballots with a pre-computed distribution) → confirm the tally matches **exactly**, including no-votes → repeat with an abstention-heavy set → confirm `PBDIGIT-20`'s verification figures agree with `index` |
| **Known findings** | **P-2 — counting has no domain model.** No service, no aggregate, no invariant, no value object: the count is a query-builder aggregation inside a controller. **No test file in the repository is named for counting specifically.** For this product, that is the single most consequential gap — and note it is *architecture* debt with a *customer* consequence, which is why it lives in this backlog and not in the debt register |
| **Status** | `IMPLEMENTED — NOT VERIFIED` · **review this first (P-2)** |

## PBDIGIT-20 — Verify the count before publishing

| | |
|---|---|
| **Customer goal** | *"Before I publish, I want independent confirmation that the numbers are right."* |
| **Business steps** | run verification → compare against the tally → inspect statistics |
| **Route → code** | `ResultController::statisticalVerification($postId):106` (`count(DB::raw('DISTINCT vote_id')):111`, `groupBy('candidacy_id'):119`, average `:142`) · `ResultController::verifyResults($postId):170` (`groupBy('candidacy_id'):176`) · verification API `routes/election/electionRoutes.php:209`; integrity check `VotingService::verifyVoteIntegrity():64`; trust result `app/Domain/Election/Security/VotingTrustResult.php` |
| **Business rules evidenced** | verification counts **distinct vote ids** (a genuine cross-check against double counting) and is exposed as its own API, i.e. the product already offers a second opinion on its own numbers |
| **Verification** | confirm `verifyResults` and `index` agree on a known ballot set; **deliberately corrupt one result row and confirm verification detects the divergence** (the check that proves the check works) |
| **Known findings** | `Evidence not found`: whether publishing is **blocked** when verification disagrees, or whether verification is advisory only. For a defensible election, that distinction matters |
| **Status** | `IMPLEMENTED — NOT VERIFIED` |

## PBDIGIT-21 — Publish results

| | |
|---|---|
| **Customer goal** | *"I want members to see the outcome, and to trust that it is final."* |
| **Business steps** | preview → verify accuracy → publish → results publicly visible |
| **Route → code** | `elections.publish` `routes/election/electionRoutes.php:280` → `Election/ElectionManagementController:855` → constitution `publish_results` (**chief only**) → event `ResultsPublishedEvent` (`app/Contexts/Elections/Domain/Events/`); console `app/Console/Commands/PublishResults.php`; docs `docs/publishing-election-results.md` · `docs/RESULTS_PUBLICATION_COMPLETION_REPORT.md`; state `results_published` |
| **Business rules evidenced** | *"Only chief can open voting or publish results"*; tutorial flow: *"Ensure all voting has concluded → verify result accuracy in the preview → Publish → confirm → results become publicly accessible"* |
| **Verification** | publish as chief → confirm public visibility and that figures match `PBDIGIT-19`; attempt as deputy → expect refusal |
| **Known findings** | **P-3 — `unpublish_results` is not a constitutional action**, yet unpublishing is implemented in six places (`app/Console/Commands/UnpublishResults.php` · `ElectionProcessController` · `Election/ElectionManagementController` · `ElectionService` · `ElectionPolicy` · `ResultsUnpublishedEvent`) and is documented **for customers** (`docs/election-management-tutorial.md` §"Unpublishing Results": *"Results are immediately hidden from public"*). Who may withdraw a published result — and whether the withdrawal is recorded — is a trust question, not a code-style question. **E-4:** two `ResultsPublished` classes exist; the legacy `Domain\Election\Events\ResultsPublished` is imported at `Election.php:29` but **never dispatched** |
| **Tests** | 14 files match results/publish |
| **Status** | `IMPLEMENTED — NOT VERIFIED` |

## PBDIGIT-22 — Export results and reports

| | |
|---|---|
| **Customer goal** | *"I want the result as a document I can circulate, archive, and submit to our members."* |
| **Business steps** | export results (PDF) → export supporting lists → circulate |
| **Route → code** | `ResultController::downloadPDF():225`; demo PDF routes `routes/election/electionRoutes.php:649,650,658`; voter export `routes/organisations.php:271`; participants/members export `:182,186`; statistics guide `docs/election/06-statistics-reports.md` |
| **Verification** | download the results PDF → confirm the figures match `PBDIGIT-19` and that the document is presentable to members |
| **Known findings** | ⚠️ `Evidence not found`: a **live (non-demo) results PDF route**. `downloadPDF` exists on the controller and demo download routes were located; the production route was not. If it is genuinely absent, a paying customer cannot export their own result — a small omission with a large customer consequence |
| **Status** | `PARTIAL` — export method evidenced; live route unconfirmed |

## PBDIGIT-23 — Audit the election

| | |
|---|---|
| **Customer goal** | *"If someone disputes the result, I want to prove what happened — without revealing how anyone voted."* |
| **Business steps** | inspect per-voter step trail → inspect state-transition history → answer the dispute |
| **Route → code** | `app/Helpers/ElectionAudit.php` · `app/Models/ElectionAuditLog.php` · `app/Models/ElectionStateTransition.php` · middleware `app/Http/Middleware/VoterSlugStep.php`; per-voter log files `storage/logs/organisation_{id}/{election_name}/{user_id}_{user_name}.log` with timestamp · IP · step completion times (root `CLAUDE.md`); constitutional divergence tooling `app/Domain/Election/Security/ConstitutionalDivergenceLedger.php` · `app/Console/Commands/ElectionConstitutionHealth.php` |
| **Business rules evidenced** | the audit records the **journey**, never the **ballot** — anonymity is a constitutional invariant the audit trail must preserve (ADR-T11); every state transition is persisted (`ElectionStateTransition`) |
| **Verification** | after `PBDIGIT-17`, confirm a per-voter log exists with timestamps and IP per step **and that no selection is attributable to any voter**; confirm the transition history reconstructs the full lifecycle |
| **Known findings** | **17 test files match audit — the best-tested capability in the product.** `Evidence not found`: whether a disputed *election result* can be routed into the PB003-certified **Contestation → Adjudication** flow, or whether that machinery is reserved for a different class of dispute. That connection is the difference between "we have logs" and "we have a governed dispute process" |
| **Status** | `IMPLEMENTED — NOT VERIFIED` |

## PBDIGIT-24 — Archive the election

| | |
|---|---|
| **Customer goal** | *"The election is over. I want it closed and preserved, not deleted."* |
| **Business steps** | archive → election becomes read-only historical record |
| **Route → code** | `archive` action `routes/organisations.php:199` (`PATCH /archive`) → constitution `archive`; state `archived`; reset tooling for demo elections `docs/ELECTION_RESET_GUIDE.md` |
| **Business rules evidenced** | `archived` is a terminal state in `ElectionLifecycleState` |
| **Verification** | archive → confirm no lifecycle action is accepted afterwards → confirm results and audit trail remain readable |
| **Known findings** | `Evidence not found`: whether archiving preserves the per-voter audit logs and result rows (retention), and whether an archived election can be un-archived |
| **Status** | `IMPLEMENTED — NOT VERIFIED` |

---

## Epic summary

| ID | Story | Status | Risk |
|---|---|---|---|
| `PBDIGIT-18` | Close voting | `IMPLEMENTED — NOT VERIFIED` | medium — **P-4** mid-journey voters undefined |
| `PBDIGIT-19` | **Count the votes** | `IMPLEMENTED — NOT VERIFIED` | **HIGHEST — P-2, no domain protection, no named test** |
| `PBDIGIT-20` | Verify the count | `IMPLEMENTED — NOT VERIFIED` | medium — advisory vs blocking unknown |
| `PBDIGIT-21` | Publish results | `IMPLEMENTED — NOT VERIFIED` | medium-HIGH — **P-3** unpublish ungoverned |
| `PBDIGIT-22` | Export results | `PARTIAL` | medium — live PDF route unconfirmed |
| `PBDIGIT-23` | Audit the election | `IMPLEMENTED — NOT VERIFIED` | low — best-tested capability |
| `PBDIGIT-24` | Archive | `IMPLEMENTED — NOT VERIFIED` | low |

**Recommended order:** `PBDIGIT-19` → `PBDIGIT-20` (prove the count, then prove the check on the count) → `PBDIGIT-21` → `PBDIGIT-22`. Counting before publishing, always.

**Nothing is authorized by this epic.**
