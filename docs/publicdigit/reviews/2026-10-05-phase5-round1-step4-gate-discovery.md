# Phase 5 · Round 1 Discovery — C1: the step-4 voting gate (`VoteController::verify()`)

**Date:** 2026-10-05 · **Type:** discovery only — no production, test, route, middleware, configuration or schema change; characterization by a temporary probe on the synthetic testing database (deleted, never committed)
**Charter:** `docs/plans/20261005-1442-phase5-discovery-charter-plan.md` (approved; Round 1 = real-election `VoteController::verify()` only)
**Frozen inputs:** ADR `ADR_20261005_0950_Voting_Authorization_Boundary.md` · Phase 4 plan · Phase 4 audit `2026-10-05-phase4-e1-e4-architecture-audit.md`
**Recorded by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable). Engineering supplies evidence; decisions are the PO's / ARB's.

---

## A. Executive finding

**Conflicting authority found** — one concern: **IP density**. Step 4 runs its own config-based IP-density decision (`vote_post_check()`), independent of the Trust → Legitimacy chain, with a different source, threshold, comparator and default; it can disagree with the final-cast authority in **both** directions, and it blocks the normal voting journey (with an unset `MAX_USE_IP_ADDRESS` it blocks **every** real voter).

All other step-4 checks are **redundant / defensive** reads of facts the final cast re-decides authoritatively — but they refuse only through an exception (missing view) and a generic "System error" message.

Structural fact that frames the question: **the final cast does not depend on step 4.** `slug.vote.store` is not in the step map, so the step-order middleware lets the final cast through without a step-4 record (that is how all Phase 4 casts ran). Step 4 is therefore *not able* to act as an authority for the cast; it can only block the UI journey.

## B. Call graph (real election)

```
GET v/{vslug}/vote/verify   routes/election/electionRoutes.php:530 (name slug.vote.verify)
  middleware (group :524): SubstituteBindings · voter.slug.verify · voter.slug.window · voter.slug.consistency
     · ensure.election.voter  → User::isVoterInElection() → VotingEntitlementQuery::standingOf()  [cached]
     · voter.step.order       → config/election_steps.php: step 4 = slug.vote.verify; requires highest
                                 completed step ≥ 3 (VoterStepTrackingService::getHighestCompletedStep)
                                 else redirect to the next allowed step (observed: code/create)
     · vote.eligibility       → slug present: ElectionLifecycle::canVote()
     · validate.voting.ip     → reads users.voting_ip (no column → null) → passes   [dormant, PBDIGIT-234]
     · vote.organisation · throttle:10,1 · voting.active (ElectionLifecycle::canVote())
  ↑ reached from step 3: first_submission() (VoteController:474–721)
       ensureVoterMembership (cached) :481 · code exists :527 · real & has_voted → dashboard :531
       · vote_pre_check($code) :551 (VoteController:3067 — no code, can_vote_now, has_voted, code-1 used,
         time window) · selection validation :596 · session put :620 · advance slug step · redirect to verify
  ↓
VoteController::verify()   VoteController.php:2406–2605   (whole body in try … catch (\Exception) :2594)
  1. ensureVoterMembership(useCache:false) :2415 ─ not entitled → redirect dashboard ("not assigned")
  2. load Code (withoutGlobalScopes) :2419–2421
  3. $vote_data = session[$code->session_name] :2430 ─ $code null → ErrorException ("Attempt to read property
     session_name on null") → catch → dashboard, errors.system
  4. no ballot in session :2433 → redirect slug.vote.create, errors.session ("Vote session expired …")
  5. vote_post_check($auth_user, $code, $vote_data, $voterSlug) :2449   (VoteController:3145)
       5a. $code === null :3153 ─ unreachable from verify (step 3 already threw)
       5b. IP density :3172–3207 — demo exempt (:3175); real: count codes(client_ip = request IP, election,
           has_voted) ≥ config('app.max_use_clientIP') :3188 → view('components.error_message') → throws
           (view missing) → catch → dashboard, errors.system; fallback without election id: check_ip_address()
       5c. $code->has_voted :3219 → view(...) → throws → catch → dashboard, errors.system
       5d. $vote === null :3229 ─ unreachable from verify (step 4 redirected first)
       error_message non-empty :2450 → dashboard, errors.verification   (never reached: every branch throws first)
       return_to non-empty :2460 → redirect                               (never set by vote_post_check)
  6. second_code_check($code) :2469 (VoteController:3245)
       expired (code-1 used + voting_time_in_minutes) :3259 → WRITES code (code-1 unusable, code2 unsent,
         vote_submitted=false) → return_to code.create → redirect
       !vote_submitted :3270 → WRITES code (both codes unusable) → return_to vote.create → redirect
  7. step-4 record: VoterStepTrackingService::completeStep(step 4) :2521 → INSERT voter_slug_steps
     (exceptions swallowed :2529) · ElectionAuditService::logVoterAction(step 4)
  8. Inertia::render('Vote/Verify') :2570 — props include debug_code = code_to_open_voting_form when the voter
     has no valid e-mail or env local/development (:2565, :2581)
```

## C. `vote_post_check()` responsibilities (as reached from `verify()`)

| # | Responsibility | Class | Authoritative source (frozen model) | Independent decision? | Can disagree? | Security-sensitive | Fits step 4? / only final cast? |
|---|---|---|---|---|---|---|---|
| 5a | Code exists | 3 Process validation | `forCast()` `CodeMissing` | no — unreachable from verify | — | low | advisory at most |
| 5b | IP density vs config | 2 Security / trust | Trust → Legitimacy (`NetworkBindingPolicy`, election `max_votes_per_ip`) | **yes** | **yes, both ways** (§D) | **yes** | a security decision belongs to the one authority (I3) — evidence: today it is a second one |
| 5c | Already voted | 4 Participation | compare-and-set on `codes.has_voted` (E4) | pre-check only (non-authoritative fact read) | only by staleness; cannot authorise | low | advisory (UX) |
| 5d | Ballot present | 5 Ballot / session | `forCast()` `BallotMissing` | no — unreachable from verify | — | low | advisory at most |
| — | Demo IP exemption | 6 Demo | — (Q4) | — | — | — | out of Round 1 |

## D. IP-limit configuration inventory (`MAX_USE_IP_ADDRESS` / `app.max_use_clientIP`)

| Reader | Location | Default when unset | Guard | Counts | Comparator | Can block a real voter | Part of Trust → Legitimacy |
|---|---|---|---|---|---|---|---|
| `config/app.php` | `:149` `env('MAX_USE_IP_ADDRESS')` | **none (null)** | — | — | — | (source) | no |
| `VoteController::vote_post_check()` | `:3173`, `:3188` (step 4 via verify; demo branch of `store()`) | **null → `0 >= null` true → always "exceeded"** | demo exempt | `codes` client_ip + has_voted, election | `>=` | **yes — at step 4** | **no (independent)** |
| `check_ip_address()` helper | `app/Helpers/helpers.php:92`, called `VoteController:3203` | as passed (null) | only without election id | `codes` (global) | `>=` | effectively unreachable (codes always have election_id) | no |
| `CodeController::verifyCode()` | `:39` (`?? 7`), `:902` (code entry) | **7** | real | `codes` client_ip + has_voted, election | `>=` | yes — at code entry (Round 2) | no |
| `DemoCodeController` | `:44` (`config(…, 7)`) | **7** | demo | — | — | demo only | no |
| `ElectionVotingController::resolveIpBlock()` | `:236`, `:249` (`(int) config(…, 0)`, used only if > 0) at election show/start `:72`, `:147` | **0 → global layer disabled** | whitelist; per-election `ip_restriction_*` first | `voter_slugs` step_1_ip + has_voted | `>=` | yes — at election start (Round 2) | no |
| `ElectionSettingsService::getEnvSetting()` | `:58` (`env(…, 4)`) | **4** | settings fallback | — | — | not a check (settings value) | no |
| **Trust → Legitimacy** | `Election.php:2356` (`max_votes_per_ip ?? 6`, strategy `ip_count` default) → `NetworkBindingPolicy`, `ParticipationDensityObservation` (trust-level-adjusted; Phase 1: unverified voter → 3) | election setting, default 6 | strategy-dependent | `codes` (votesFromThisIp, passed by `store()`) | `<=` (passes) | yes — at the final cast | **the authority** |

One environment variable, **four interpretations when unset** (always exceeded · 7 · disabled · 4). Two pre-cast IP-density checks outside the authority (step 4, code entry) plus a third at election start; three different counting sources (`codes` vs `voter_slugs.step_1_ip`).

**Disagreement, both directions (step 4 vs final cast, default election, unverified voter):** config 3, count 3 → step 4 blocks while the final-cast limit (3) passes (`3 <= 3`); config 7, count 4 → step 4 passes while the final cast denies (`4 > 3`).

## E. Characterization matrix (probe, synthetic data, real election unless noted)

Fixture: voter with membership, active slug, code with code step completed (`can_vote_now = 1`, `vote_submitted = true`, code-1 used now), steps 1–3 completed via `VoterStepTrackingService`, ballot in session.

| Case | Input | `vote_post_check()` | `verify()` outcome | Response | Exception (logged) | DB writes | Logs |
|---|---|---|---|---|---|---|---|
| A | config 7, count 0 | passes | page | **200 `Vote/Verify`** | — | INSERT `voter_slug_steps` (step 4) | — |
| B | **config unset**, count 0 | IP branch (`0 >= null`) → view render | catch | **302 dashboard, `errors.system` "System error during verification…"** | `View [components.error_message] not found.` | none | warning "IP rate limit exceeded", error "Verify function encountered unexpected error" |
| C | config 3, count 2 (below) | passes | page | 200 `Vote/Verify` | — | INSERT step 4 | — |
| D | config 3, count 3 (equal) | IP branch → view render | catch | 302 dashboard, `errors.system` | view not found | none | IP warning + verify error |
| E | config 3, count 4 (above) | IP branch → view render | catch | 302 dashboard, `errors.system` | view not found | none | IP warning + verify error |
| F | already voted | has_voted branch → view render | catch | 302 dashboard, `errors.system` | view not found | none | verify error |
| G | Code row deleted | not reached | `$code->session_name` on null | 302 dashboard, `errors.system` | `Attempt to read property "session_name" on null` (ErrorException — caught, **no 500**) | none | verify error |
| H | no ballot in session | not reached | session check | 302 `vote/create`, `errors.session` "Vote session expired…" | — | none | warning "without session data" |
| I | **demo**, config unset (comparison) | IP branch exempt | page | 200 `Vote/Verify` | — | INSERT step 4 | — |

Pre-condition observed separately: without completed steps 1–3 every case is redirected by `voter.step.order` to `code/create` before `verify()` runs. All cases additionally log the warning "Constitutional violation: lifecycle_evaluations" without refusing (pre-existing; not investigated).

## F. Authority matrix — step 4 vs inherited authority

| Step-4 concern | Current step-4 check | Inherited authority | Duplicate? | Can disagree? | Classification | Phase 5 question |
|---|---|---|---|---|---|---|
| Entitlement | `ensure.election.voter` (cached) + `ensureVoterMembership(useCache:false)` | `VotingEntitlementQuery::decide()` in `forCast()` | yes (same query) | only by freshness; cannot authorise the cast | defensive / navigation | keep as advisory? |
| Lifecycle | `vote.eligibility`, `voting.active` | `ElectionLifecycle::canVote()` (also in `decide()`) | yes (same authority) | no | defensive | — |
| **IP density** | `vote_post_check()` config count | Trust → Legitimacy | **different authority** | **yes, both ways** | **conflicting authority** | KEEP / DELEGATE / REMOVE / RESTRUCTURE (Q3) |
| IP binding | `validate.voting.ip` (dormant) | — | — | — | dormant (PBDIGIT-234) | out of Round 1 |
| Already voted | `vote_post_check()` has_voted | compare-and-set (E4) | yes (fact read) | staleness only | defensive pre-check (fails by crash) | advisory + reasoned? |
| Code existence | property read throws | `forCast()` `CodeMissing` | yes | no | defensive (fails by crash) | reasoned? |
| Ballot / session | `verify()` :2433 | `forCast()` `BallotMissing` | yes | no | defensive, **reasoned** | — |
| Code expiry / submission state | `second_code_check()` | **none at the final cast** (`forCast()` has no expiry predicate) | no | — | **only pre-cast** (and writes on GET) | finding R1-F6 |
| Demo | demo IP exemption | Q4 | — | — | separate | out of Round 1 |

## G. Failure semantics

- `vote_post_check()` never *returns* an error from `verify()`: every error branch calls `view('components.error_message')->render()`, the view does not exist, the render throws. The return-value handling in `verify()` (`:2450`, `:2460`) is therefore dead for these branches.
- `verify()` catches `\Exception` (`:2594`) → redirect to the dashboard with **"System error during verification. Please try again or contact support."** — the same text for an IP limit, an already-voted code, a missing code or a real system error.
- Reasoned refusals exist only for: missing ballot (`errors.session`), code expiry / not submitted (redirect + message), membership (dashboard flash), step order (redirect).
- **No HTTP 500 observed**, including the missing-Code case (PHP's null-property warning is converted to an `ErrorException`, which is an `\Exception`). An `\Error` (not an `Exception`) inside `verify()` would still escape the catch — not observed in these cases.
- Step 4 **writes on GET**: the step-4 record (always on success) and, in `second_code_check()`, code-state writes on expiry / not-submitted — outside any transaction.

## H. Anonymity impact

- Step 4 runs before any vote exists: no vote id is read, logged or stored by `verify()`; the step-4 record stores `vote_verified`, `verified_at` (voter-side timestamp, before the cast — no new correlation path; the existing A2 / A6 concerns are about the cast and step 5).
- None of the decision outcomes for Q1 / Q3 (KEEP / DELEGATE / REMOVE / RESTRUCTURE) would need ballot-side data; a delegation to the Trust → Legitimacy chain must not add new IP logging next to voter identity beyond what the chain logs today (interim rule point 3).
- Not an anonymity issue, but security-relevant: `debug_code` (R1-F7).

## I. Findings

| ID | Severity | Evidence | Architectural significance | Phase 5 relevance |
|---|---|---|---|---|
| **R1-F1** | **High** | §D, §E-B/D/E: config-based IP density at step 4, independent of Trust → Legitimacy; different source, threshold, comparator, default; disagrees both ways | **Conflicting security authority** (ADR I3) — the same pattern P4-D1 removed from the final cast | Core of Q1 / Q3 |
| **R1-F2** | **High (operational)** | §E-B: config unset → every real voter refused at step 4 with "System error" | Operational blocker of the voting journey (D10 located at step 4) | Q4; ops verification (charter §8.3) |
| R1-F3 | Medium | §D: one variable, four unset interpretations (null-always-exceeded / 7 / disabled / 4); three pre-cast IP checks (step 4, code entry, election start) on two counting sources | Security-policy boundary undefined (ADR Q3) | Q4; Round 2 (code entry, election start) |
| R1-F4 | Medium | §G: step-4 refusals from `vote_post_check()` are exception-driven, generic "System error" | Failure semantics not reasoned (cf. E3 at the final cast) | Q5 |
| R1-F5 | Info | `config/election_steps.php`: `slug.vote.store` not mapped; Phase 4 casts ran without step records | **The final cast does not depend on step 4** → step 4 cannot act as an authority for the cast; its checks only gate the UI journey | Q1 (decisive evidence) |
| R1-F6 | Medium (unverified by characterization) | §B-6: code expiry (`voting_time_in_minutes`) and submission state are checked at step 3 (`vote_pre_check`) and step 4 (`second_code_check`), **not** in `forCast()` | A process rule enforced only before the final cast; a direct final-cast POST is not subject to it | Outside Round 1 scope (Phase 4 frozen) — report for a separate decision |
| R1-F7 | Medium (security) | `:2565`, `:2581`: `debug_code` = `code_to_open_voting_form` rendered on the step-4 page when the voter has no valid e-mail, or in `local` / `development` | The code that proves e-mail possession is shown on screen — the factor collapses for voters without e-mail | Separate decision (not C1) |
| R1-F8 | Low | §G: step 4 writes on GET (step record; code state in `second_code_check()`), outside a transaction | Side effects on a page view | Q5 / later |
| R1-F9 | Info | §B: `vote_post_check()` branches 5a, 5d unreachable from `verify()`; `return_to` never set; `error_message` handling dead | Dead code paths around the gate | Q2 |
| R1-F10 | Info | §E: "Constitutional violation: lifecycle_evaluations" warning on every request without refusal | Noise / unexplained monitor | not investigated |
| — | Contradiction with charter §4 | charter lists no-code and missing-ballot among the step-4 `vote_post_check()` effects | from `verify()` those two branches are unreachable (verify checks earlier); the charter's facts are otherwise confirmed | wording only; charter not changed |

## J. Charter questions — answered only as far as the evidence goes

1. **Role of step 4.** Evidence: the final cast does not depend on step 4 (R1-F5) and re-decides every concern step 4 checks (entitlement, lifecycle, security, already-voted, code, ballot). **Evidence supports** that step 4 currently *cannot* be an authority for the cast; whether it *should* remain a pure preparation / confirmation step (ADR §3.1 "advisory … UX only") is the PO's decision. *Evidence sufficient for the factual role; intended role — decision deferred to the PO.*
2. **Each `vote_post_check()` responsibility.** Classified in §C: code-exists and ballot unreachable; already-voted = advisory pre-check; IP density = independent security decision. *Evidence sufficient for classification; KEEP / DELEGATE / REMOVE / RESTRUCTURE per item — decision deferred.*
3. **IP density at step 4 vs I3.** Evidence: independent authority that disagrees both ways with Trust → Legitimacy (R1-F1). *Evidence sufficient that it is a second security authority; the remedy — decision deferred.*
4. **Operational contract of `MAX_USE_IP_ADDRESS`.** Evidence: unset blocks every real voter at step 4; other readers default to 7 / disabled / 4 (R1-F2, R1-F3). Whether the setting is needed at all after Round 2: *Evidence insufficient — architectural decision deferred (code entry and election start are Round 2).*
5. **Failure semantics.** Evidence: exception-driven, generic (R1-F4). *Evidence sufficient that step-4 refusals are not reasoned; the target semantics — decision deferred.*
6. **Demo / Deligate copies.** Out of Round 1 (PO). Comparison only: a demo election through `VoteController::verify()` is exempt from the IP branch and renders the page with the setting unset (§E-I). *No demo decision made.*
7. **Relation to `forEntry()`.** *Evidence insufficient — architectural decision deferred (Round 2).*

## Decision proposal (for PO / ARB — not a decision)

Based on R1-F1 and R1-F5, the evidence points towards treating step 4 as a **preparation / confirmation step without an independent security authority** — i.e. for IP density either **DELEGATE** to the Trust → Legitimacy chain (advisory) or **REMOVE** from step 4 — with the remaining step-4 checks kept as **advisory, reasoned** refusals. R1-F6 and R1-F7 are outside C1 and should each get a separate decision / backlog item rather than being folded into Round 1.

**Traceability:** charter §4 (Round 1) · ADR_20261005_0950 §3.1, §3.5, I3 · Phase 4 audit C1 · PBDIGIT-233 (final cast resolved; step-4 remainder), PBDIGIT-234.

---

## Addendum 1 — Re-audit and corrections (2026-10-05, later the same day)

*Appended; the report above is left unchanged as the original record. Where this addendum and the body differ, the addendum is the current interpretation.*

**Re-audit (read-only) — Phase 4 baseline: PASS.**
- HEAD `27d1e3a54`; the last commit touching `VoteController.php` is the accepted E4 commit `b1daf26a2`; `git diff b1daf26a2 HEAD -- app routes config database bootstrap` is empty; the working tree has no change to `VoteController.php`.
- E1–E4 invariants re-checked against the current source; suites re-run green (E4 7/7, R1 4/4, R3 6/6, E2 16/16, Phase 1 26/26).

**R1-F6a — confirmed, narrowed to the code-verification validity window.**
- Probe (synthetic data, temporary, deleted): a direct `POST slug.vote.store` **without any step records** stored a vote for an **expired** code (code used 45 min ago, window 30) and for **`vote_submitted = false`** (control: valid code also stored a vote).
- Narrowing: the **slug window** (`voter_slugs.expires_at`) *is* enforced on the final POST (`voter.slug.window`), but it **slides** (+30 min on re-entry via `getOrCreateActiveSlug()`); the **fixed** code window (`code_to_open_voting_form_used_at` + `voting_time_in_minutes`) is not evaluated at the final cast.
- `vote_submitted`: at the final cast it is redundant with the ballot check (`BallotMissing`); its distinct role is the expiry reset in `second_code_check()`.
- Pre-existing: neither the pre-Phase-4 `store()` (`133f435d7`) nor the current one reads the window — not introduced by Phase 4.
- Related: `used_at = null` evaluates as "now" (not expired); code regeneration sets `used_at = null` and keeps `can_vote_now` (**PBDIGIT-231**) — see the decision brief, fact F12.
- Severity revised: **MEDIUM–HIGH, process integrity** (no double vote, no non-entitled vote). Decision brief: `docs/publicdigit/reviews/2026-10-05-r1-f6a-code-validity-window-decision-brief.md`.

**R1-F6b — corrected: not a current real-election security gap.**
- The re-audit probe accepted code 1 with `voting.two_codes_system` forced to the integer `1` — a state an `.env` cannot produce: `env('TWO_CODES_SYSTEM')` yields the string `"1"`, while the real path compares `=== 1` / `!== 1` / `=== true` (`VoteController.php:3109`, `:3251`, `:2075`; `CodeController.php:917`); `voting.is_strict` is never read.
- The real flow never generates or sends a second code (the only `send_second_voting_code()` call is commented out in the unrouted `second_submission()`, `VoteController.php:846`).
- Real elections therefore always run the documented simple ("two-use code") mode; accepting code 1 at the final cast is that design. Reclassified as **design debt** (a flag suggesting a working feature); a two-code process would be a new feature story.

**R1-F7 (`debug_code`) — clarified.** `Vote/Verify.vue` declares the prop but does not render it; the value reaches the browser in the Inertia page data when the voter has no valid e-mail (the same code is already shown deliberately on the code-entry page, `CodeController.php:217–218`) or in `local` / `development`. Classified **security debt**, separate decision.

**Not changed by this addendum:** no architectural decision has been made; no production, test, configuration, route or migration change; the Round 1 decision proposal above is **paused** until R1-F6a is decided (re-audit: PAUSE — SECURITY FINDING).
