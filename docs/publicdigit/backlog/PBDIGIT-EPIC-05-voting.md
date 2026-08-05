# PBDIGIT-EPIC-05 — Voting

**Journey segment:** `Voter Verification → Open Voting → Vote`
**Epic status:** `IMPLEMENTED — NOT VERIFIED` · **Gated by** `PBDIGIT-00`; **depends on** `PBDIGIT-07` (chief must exist to open voting), `PBDIGIT-08` (eligibility), `PBDIGIT-13` (approved candidates)
**Baseline:** `main` @ `9158ef11` · **Created:** 2026-08-05

> **The constitutional guarantee this epic must never break:** the `votes` table has **no `user_id`** — no voter↔vote linkage (ADR-T11 / Q7), enforced by fitness test `AT-Q7-001` in `tests/Architecture/GreenfieldCoreArchitectureTest.php:69`, which scans both the core and the messaging surface for any linkage token.

---

## PBDIGIT-15 — Verify voters and issue codes

| | |
|---|---|
| **Customer goal** | *"I want to confirm exactly who may vote, and get each of them their voting credentials."* |
| **Business steps** | review the voter list → approve or reject each voter → issue voting codes → invite voters |
| **Route → code** | voter list `routes/election/electionRoutes.php:198,199` · approve `:202` · reject `:203` → `VoterlistController` · `ElectionVoterController` · `Election/VoterImportController` · `VoterInvitationController` · `VoterDashboardController` · `VoterSlugController`; voter export `routes/organisations.php:271`; user guides `docs/election/02-voter-list-overview.md` … `05-bulk-operations.md` |
| **Business rules evidenced** | **two-use voting code** — `is_code1_usable`, `code1_used_at` (first use, code entry), `code2_used_at` (second use, vote submission); `TWO_CODES_SYSTEM` env switch; `VOTING_TIME_MINUTES` bounds the voting window (root `CLAUDE.md`) |
| **Verification** | approve one voter, reject one → confirm the rejected voter cannot reach code entry → confirm codes are issued and delivered → confirm a code cannot be used after `code2_used_at` is set |
| **Known findings** | voter verification has **no constitutional action and no domain event** — a consequential step invisible to the lifecycle model; 5 test files match voter-list/verification |
| **Status** | `IMPLEMENTED — NOT VERIFIED` |

## PBDIGIT-16 — Open the ballot

| | |
|---|---|
| **Customer goal** | *"As chief, I want to open voting when everything is ready."* |
| **Business steps** | confirm candidates and voters finalised → open voting → voters can begin |
| **Route → code** | `elections.open-voting` `routes/election/electionRoutes.php:289` → `Election/ElectionManagementController` → constitution `open_voting` (**chief only**, from `ready_for_voting`) → event `VotingOpened` (dispatch map ~`Election.php:1695`); state `voting_active`; auto-transition support `app/Console/Commands/…` + `tests/Feature/Console/ProcessElectionAutoTransitionsTest.php` |
| **Business rules evidenced** | *"Only chief can open voting or publish results"* (`ElectionConstitution`) · tutorial preconditions: candidates registered, voter lists finalised (`docs/election-management-tutorial.md`) · `EnsureElectionState` middleware guards state-dependent routes |
| **Verification** | attempt as deputy → expect refusal; then as chief → confirm `voting_active` + `VotingOpened` fired + voters can enter codes; attempt to open twice → expect constitutional refusal |
| **Status** | `IMPLEMENTED — NOT VERIFIED` |

## PBDIGIT-17 — A voter casts a vote (the 5-step journey)

| | |
|---|---|
| **Customer goal** | *"I want to vote easily, privately, and be sure my vote was recorded."* |
| **Business steps** | enter code → accept the voting agreement → select candidates → verify the selection → complete (receipt/thank-you) |
| **Route → code** | slug-scoped journey `routes/election/electionRoutes.php:487-520`: `code/create` (`slug.code.create`) → `POST code` (`slug.code.store`) → `vote/agreement` (`slug.code.agreement`) → `POST code/agreement` → `vote/create` (`slug.vote.create`) → `POST vote/submit` (`slug.vote.submit`) → `vote/verify` (`slug.vote.verify`) → thank-you `:216`; controllers `CodeController` · `VoteController`; middleware `vote.eligibility` (`VoteEligibility`), `VoterSlugStep`; services `RealVotingService` / `DemoVotingService` (`VotingService::verifyVoteIntegrity():64`); denial page `:123` |
| **Business rules evidenced** | 5-step slug workflow with per-step progress tracking (`VoterSlugStep`) · double-vote prevention (3 test files + `docs/DOUBLE_VOTE_PREVENTION_ANALYSIS.md`, `PHASE_2_DOUBLE_VOTE_PREVENTION_STATUS.md`) · regional filtering of posts by voter region · `required_number` / `SELECT_ALL_REQUIRED` selection rule · no-vote option supported · **anonymity: no `user_id` on votes** |
| **Verification** | vote once end-to-end → confirm: ballot stored, **no voter identity attached**, code exhausted, per-voter audit log written with timestamps + IP, receipt shown; then attempt a second vote with the same code → expect refusal; attempt with an ineligible voter → expect refusal **with a recorded reason** |
| **Known findings** | **P-5 (customer-visible):** `/vote/create`, `/vote/submit`, `/vote/verify` are defined **twice in the same file** — anonymous closures at `:151,155,163` and slug controllers at `:487-520` — with **precedence unverified**; `/vote/submit_seleccted` (`:159`) is a misspelled public path; commented-out route blocks remain at `:408-460`. **P-4:** the lifecycle model has no concept of *"a voter is mid-journey"*, so closing voting during an open session is undefined in the model |
| **Tests** | 11 voting-flow files + 3 double-vote files + E2E: `RealWorldVotingFlowTest`, `CompleteVotingFlowIntegrationTest` |
| **Status** | `IMPLEMENTED — NOT VERIFIED` |

---

## Epic summary

| ID | Story | Status | Risk |
|---|---|---|---|
| `PBDIGIT-15` | Verify voters & issue codes | `IMPLEMENTED — NOT VERIFIED` | medium — invisible to the lifecycle model |
| `PBDIGIT-16` | Open the ballot | `IMPLEMENTED — NOT VERIFIED` | low — clearest constitutional coverage in the product |
| `PBDIGIT-17` | Voter casts a vote | `IMPLEMENTED — NOT VERIFIED` | **HIGH — P-5 duplicate route definitions on the voter path; anonymity must be re-confirmed at runtime** |

**Related defect story:** [`PBDIGIT-25`](PBDIGIT-25-copy-button-must-not-submit-the-form.md) — Copy button submitted the form on `/public-demo/{slug}/code` (step 1 of the demo voter journey). **Fixed 2026-08-05**, awaiting runtime verification.

**Recommended order:** `PBDIGIT-17` first, and within it the two checks that matter most: **which handler actually serves `/vote/submit`** (P-5), and **that no voter identity reaches the votes table** (the product's central promise — currently guaranteed by a fitness test, but never observed in a live run).

**Nothing is authorized by this epic.**
