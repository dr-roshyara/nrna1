# Real-election review — `CodeController` and `VoteController`

**Date:** 2026-10-05 · **Type:** read-only review (no code, test, configuration, route or data change; nothing executed) · **Scope:** real-election paths only — demo branches, `DemoCodeController` and `DemoVoteController` excluded (PO instruction).
**Baseline:** HEAD `ac5ed44fe`; `VoteController.php` unchanged since Phase 4 E4 `b1daf26a2`.
**Method:** two read-only review passes (one per controller), then manual spot-checks of every HIGH and the key MEDIUM findings against the source by this session. **Verification column:** ✔ re-read in source by this session · ○ reported by the review pass, not independently re-read.
**Recorded by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable). Engineering supplies evidence; decisions are the PO's / ARB's.

---

## 1. Real-election surface

| Controller · method | Route | Purpose |
|---|---|---|
| `CodeController::create` | GET `v/{vslug}/code/create` | get-or-create / regenerate the code, e-mail it, render |
| `CodeController::store` | POST `v/{vslug}/code` | verify the submitted code (`can_vote_now = true`) |
| `CodeController::showAgreement` / `submitAgreement` | GET `vote/agreement` · POST `code/agreement` | agreement step |
| `CodeController::getIPStatistics` | GET `/admin/ip-stats` | **method does not exist** (F-C1) |
| `VoteController::create` | GET `v/{vslug}/vote/create` | render the ballot |
| `VoteController::first_submission` | POST `v/{vslug}/vote/submit` | put the ballot into the session |
| `VoteController::verify` | GET `v/{vslug}/vote/verify` | confirmation page |
| `VoteController::store` | POST `v/{vslug}/vote/verify` **and** POST `/votes` | final cast (Phase 4 chain, frozen) |
| `verify_to_show`, `submit_code_to_view_vote`, `show`, `downloadVotePDF`, `confirmCorrect` | non-slug | view / confirm a vote by receipt |
| `verify_final_vote`, `index`, `thankyou` | non-slug | dead / empty / broken (F-V10) |

Middleware facts that shape the findings ✔: the code-step group runs `voter.slug.verify`, `voter.slug.consistency`, `ensure.election.voter`, `vote.eligibility`, `vote.organisation` — **no slug window, no throttle**. `ensure.election.voter` checks **standing only** (`standingOf()`), not the lifecycle. `vote.eligibility` returns early when `session('selected_election_type') === 'demo'`.

## 2. Findings

| ID | Sev | Location | Finding (FACT → INFERENCE) | Relation | ✔/○ |
|---|---|---|---|---|---|
| **F-V7** | **HIGH** (anonymity) | `VoteController::confirmCorrect` ≈4328 | logs `receipt_code` **and** `user_id` in one line; the receipt is `private_key . '_' . vote_id` (≈1868) → a direct voter ↔ vote link in the log for every voter who confirms | new instance; refines A1 | ✔ |
| **F-V6** | **HIGH** (anonymity / secrecy) | `VoteController::store` 1786 | `Log::info('private_key', ['user_id' => $private_key])` — the receipt secret in plaintext; the same request logs `user_id` and the vote id → logs suffice to rebuild receipts, open ballots and tie them to voters | new instance; refines VE-06, A1 | ✔ |
| **F-V1** | **HIGH** | `VoteController::first_submission` 545–551, `vote_pre_check` ≈3084 | `vote_submitted = true` is saved **before** `vote_pre_check()`, whose first guard returns early when `vote_submitted === true` → every pre-check guard (code usable, time window, has_voted …) is dead on the real path; the flag is persisted before validation | new | ✔ |
| **F-C2** | **HIGH** | `VoteEligibility` middleware 36–39 | early return when the session's `selected_election_type` is `demo` (set by any demo visit) skips the lifecycle check; `ensure.election.voter` checks standing only → after a demo visit, a real code can be issued, e-mailed, verified and agreed outside the voting phase (final cast still checks the lifecycle) | refines Track B (same mixing, in middleware) | ✔ |
| **F-C3** | **HIGH** | `CodeController::create` 135–169 | once the window has lapsed (`>= voting time`), a GET regenerates the code — **also for an already verified code** (`can_vote_now` preserved) — and re-opens the slug (`expires_at = now()+T`, `is_active`, `can_vote_now`) → the window can be re-opened after every expiry; no hard end in this controller | refines R1-F6a, F5, PBDIGIT-231 | ✔ |
| F-V2 | MEDIUM | `first_submission` ≈603–691 | ballot put into the session, audit event, step 3 completed and slug advanced to step 4 **before** `verify_first_submission` checks agreement / user-id → a failed check still leaves a ballot in the session and the slug at step 4 | new; refines A7 | ○ |
| F-V3 | MEDIUM | `second_code_check` (only via `verify()`) | the per-code voting window is checked only on GET verify; `store()` never checks it | new | ○ |
| F-V4 | MEDIUM | route `POST /votes` → `store()` (`electionRoutes.php:169`) | second entry to the final cast with only `auth`, `verified`, `vote.eligibility`; without a slug `getElection()` falls back to `Election::where('type','real')->first()` (no ordering) → skips slug verify/window/step-order, voting.active, IP and organisation middleware and throttle; different lock key; `forCast()` + CAS still run | new | ✔ (route, fallback) |
| F-V5 | MEDIUM | `first_submission` 579 | session ballot key `session_name ?: 'vote_data_'.user_id` is not election-scoped → a voter in two elections can carry a ballot of election A into a cast for B | refines VE-01, A7 | ✔ |
| F-V8 | MEDIUM | `verify()` ≈2580 | `debug_code` shows the code when the voter has no valid e-mail **or** the environment is local/development | new | ○ |
| F-C4 | MEDIUM | `CodeController` 917 | `config('voting.two_codes_system') === true` while config yields the env string `"1"` (`is_strict` uses `== 1`) → strict two-code mode never marks code 1 unusable | new; same pattern as R1-F6b | ✔ |
| F-C5 | MEDIUM | `CodeController::create` 218 | with no valid e-mail the code is rendered on the page of the same session → the code step proves only session possession | new | ✔ |
| F-C6 | MEDIUM | `CodeController` 882 (+245, 524 logging) | plaintext code compared with `!==` (not hashed, not constant-time); submitted code and `$request->all()` logged | refines VE-06 | ○ |
| F-C7 | MEDIUM | `CodeController` 39 | `config('app.max_use_clientIP') ?? 7` — unset limit becomes 7 here (fail-open), unlike the middleware path | refines C1 / D10, ADR F4 | ✔ |
| F-C8 | MEDIUM | `CodeController` 422, 552, 714 vs 270 | `withoutGlobalScopes()` (also lifts soft deletes) in create / agreement, scoped query in store → a soft-deleted code can block a voter or pass the agreement | refines PBDIGIT-235 | ○ |
| F-C9 | MEDIUM | `CodeController` 295–297, 151–156 | verified code short-circuits store before `verifyCode`; regeneration keeps `can_vote_now` → regenerated code never checked | confirms PBDIGIT-231 / VE-03 | ✔ (151–156) |
| F-C1 | MEDIUM | route `/admin/ip-stats` | points to `CodeController::getIPStatistics`, which does not exist → 500 for any caller passing `can:admin` | new | ✔ |
| F-V9 | LOW | `store()` ≈1593, ≈1715 | two returns bypass `failCastClosed()` (transaction left open; one holds the lock up to 10 s); effectively fail-closed by connection rollback | refines Phase 4 E1 (second is latent, PBDIGIT-234) | ○ |
| F-V10 | LOW | various | dead / broken code: `verify_final_vote` (always fails; would log ballot + name), `thankyou` (public, undefined `$vote` → 500), uncalled `saveVoteTransactionally` (sets `$vote->user_id` — anonymity hazard if revived) and others; duplicate code check in `store()` | new | ○ |
| F-V11 | LOW | `verify_to_show` | receipt in GET query string (access logs, history, Referer); whole Vote model returned | refines A8 | ○ |
| F-V12 | LOW | `retrieve_vote_record` ≈3573 | skips the proof when `receipt_hash` is empty (legacy rows viewable by vote UUID) | new | ○ |
| F-C10–F-C15 | LOW | `CodeController` | agreement re-post resets `voting_started_at`; no transaction / lock; no throttle on code POST; GET writes tenant into session; unreachable `!$voterSlug` branches referencing undefined routes; inconsistent `>=`/`>` expiry, generic `\Exception` → 500, `Log::emergency` on every create | new (hygiene) | ○ |

**Confirmed known items:** VE-06 (codes logged), PBDIGIT-235 (`withoutGlobalScopes()` in both controllers), C1 (`verify()` runs `vote_post_check`), A6 (step-5 stores vote id), A7 (session ballot never cleared), PBDIGIT-231 / VE-03.

## 3. Reading

1. **Anonymity (F-V6, F-V7):** the constitutional invariant *"participation knowledge without ballot knowledge"* is broken **in the logs**, independently of A5 / A6: one log line pairs a user id with a vote-id-bearing receipt; another logs the receipt secret. These are concrete, small and separable from the Phase 2.0 model decision — they are candidates for priority handling, but **remain decisions for the PO** (A1-class items were accepted as interim risk; these two were not part of that acceptance — they were not known).
2. **Pre-cast gates are weaker than they look (F-V1, F-V2, F-V3, F-C2, F-C3):** several guards before the final cast are dead or bypassable. The Phase 4 final cast re-checks lifecycle, entitlement, `forCast()` and the CAS, so **casting** stays protected; what is weakened is the code / window / agreement process before it (Phase 5 territory, R1-F6a).
3. **Second cast entry (F-V4):** `POST /votes` reaches `store()` with far fewer layers and an implicit election choice — a decision whether it should exist at all.
4. **VE-01 relation (F-V5):** the unscoped session key is another way a ballot from the wrong election reaches the cast — VE-01 GREEN (validation against the election's authoritative ballot) closes its effect; the key itself stays a separate item.

## 4. Not verified

Bodies of `VerifyVoterSlug`, `EnsureVoterStepOrder`, `EnsureVotingActive`, `ValidateVotingIp`, `ValidateVoterSlugWindow` (whether they block F-V2 / F-C10); whether any frontend calls `POST /votes`; whether a permission named `admin` satisfies `can:admin`; trusted-proxy configuration for `client_ip`; log-channel retention (decides the real exposure of F-V6 / F-V7).

## 5. Status

No backlog items created, no fix proposed for implementation. Classification and backlog creation are PO decisions; nothing here changes VE-01, the ADR revision draft, D4 or D6.
