# Complete Voting Engine Business Audit — CodeController, voting journey, hook integrity

**Date:** 2026-10-05 · **Type:** read-only audit (no production, test, configuration, route, migration, frontend or documentation change; this report is the only file created) · **Baseline:** HEAD `2937695db`; `VoteController.php` identical to accepted Phase 4 E4 `b1daf26a2`
**Prompt:** `docs/publicdigit/brainstorming/voting_engine/code_controller.md` · **Recorded by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable), acting as verification engineer / DDD architect. Engineering supplies evidence; decisions are the PO's / ARB's.

**Evidence levels used throughout:**
- **[D]** dynamically characterized (probe or test on synthetic data, this or earlier rounds today);
- **[S]** read directly in source by the author (file:line);
- **[S\*]** reported by a read-only research sub-audit with file:line and not independently re-read by the author;
- **[I]** inference from the code path (not executed).

No probe was run in this audit (the prompt forbids creating files); dynamic evidence cited comes from the Round 1 re-audit and the Phase 1 / Phase 4 suites.

---

## 1. Executive Summary

The **final cast** (`POST v/{vslug}/vote/verify` → `VoteController::store`) is the strongest part of the engine: one cast decision (`VotingAuthorization::forCast()`), fresh entitlement including lifecycle, one security authority on the cast, an atomic compare-and-set that lets one code produce at most one committed vote, fail-closed exits — all re-verified [D][S].

The **business process around it is not yet provably enforced**:

1. **Ballot content is not validated on the server** — selected posts and candidacies are taken from the client and stored without checking that they belong to the voter's election, post or region, or that a candidacy is not chosen twice [S]. (Not dynamically confirmed.)
2. **The code-verification validity window is not enforced at the final cast**; a direct final POST stores a vote with an expired code [D] (R1-F6a, decision brief pending).
3. **Ballot secrecy is not proven at system level.** The vote row carries no voter identity [D], but several existing channels link voter and ballot: the recomputable `vote_hash` with an **unconfigured (empty) salt** [S], the step-5 record with `vote_id` [D], logs pairing `vote_id` with IP / receipt [D], an audit "receipt_hash" computed from `vote_id + user_id + election_id` and written next to the voter's identity [S], and — newly identified — the **plaintext ballot left in the voter's database session after the cast** [S].
4. **Voting credentials are written to logs in plaintext** (submitted and expected codes, newly generated codes, the receipt secret) [S].
5. **Security / IP policy has several independent authorities** across the journey with different sources, defaults and comparators [D][S].
6. **Code verification has no single, clear meaning**: regeneration keeps "verified" while clearing the verification timestamp, so code entry can be skipped for a new code [D][S] (PBDIGIT-231).
7. **Several entry paths issue voting sessions without an entitlement check**, and a demo endpoint accepts a real election id [S][S\*] (PBDIGIT-214, PBDIGIT-217, new).
8. **Test coverage** proves the final-cast mechanics well but leaves important business rules untested or tested only in confounded ways [S\*].

**Management verdict: NOT YET PROVEN** (§ Management Summary).

## 2. Audit Scope

Real elections, from voter entry to post-cast pages. Demo elections only to establish separation. Out of scope: Phase 4 code changes (frozen), A5 / A6 redesign (Phase 2.0), UI wording.

## 3. Source Files / Routes / Middleware Reviewed

- Controllers: `CodeController.php` (all 18 methods), `VoteController.php` (`create`, `first_submission`, `verify_first_submission`, `vote_pre_check`, `verify`, `vote_post_check`, `second_code_check`, `store`, `save_vote`, `markUserAsVoted`, post-cast methods), `ElectionVotingController.php` (`show`, `start`, `resolveIpBlock`), `VoterSlugController.php`, `ElectionController::storeElection`, `Election/ElectionManagementController::startSpecificDemo`, `Admin/VotingSecurityController::generateRecoverySlug`, `VotingReceiptController.php`.
- Services / domain: `VotingAuthorization`, `CastAttempt`, `CastDecision`, `CastDecisionReason`, `EloquentVotingEntitlementQuery`, `CachedVotingEntitlementQuery`, `VoterSlugService`, `VoterStepTrackingService`, `VoterProgressService`, `ElectionAuditService`, `TrustPolicyEvaluator` chain, `Vote` / `BaseVote` models.
- Routes: `routes/election/electionRoutes.php` (entry 30–110, legacy 118–220, slug groups 497–541, demo 557–610), `routes/web.php:220`, `routes/organisations.php:307–309`.
- Middleware (aliases `bootstrap/app.php:85–100`): VerifyVoterSlug, ValidateVoterSlugWindow, VerifyVoterSlugConsistency, EnsureElectionVoter, EnsureVoterStepOrder, VoteEligibility, ValidateVotingIp, EnsureRealVoteOrganisation, EnsureVotingActive, CheckVotingWindow.
- Configuration: `config/app.php`, `config/voting.php`, `config/voting_security.php`, `config/election.php`, `config/election_steps.php`, `config/session.php`.
- Tests: `tests/Feature/Voting/*`, `tests/Feature/Characterization/Phase1/*`, `tests/Feature/Election/*`, `tests/Unit/Contexts/Elections/*`, `tests/Architecture/*`, legacy voting tests.

## 4. Complete Business Voting Journey (as implemented)

```
A. Entry          GET /elections/{slug} (show: standing + decision displayed, nothing written)
                  POST /elections/{slug}/start  — the ONLY entry with a full check:
                     standing (cached) → membership has_voted → lifecycle → IP limit (never blocks, §14) → slug issued / reused
                  other slug issuers WITHOUT entitlement check: /election/select, /voter/start (legacy user columns),
                     /voter/restart, /election/demo/select (accepts a real election id), /vote and /test/generate-slug (broken) [S*]
B. Code page      GET v/{vslug}/code/create — middleware: slug ownership, election consistency, entitlement (cached ≤300 s),
                     lifecycle (skipped if the session carries the demo flag), organisation presence
                  code created / re-sent / regenerated; e-mailed (or shown on page if no valid e-mail)
C. Code entry     POST v/{vslug}/code — code value, code usable, code age (config window), IP count (config ?? 7)
                     → "code step completed" (can_vote_now = true, used_at = now); skipped entirely if already completed
D. Agreement      GET/POST agreement — records has_agreed_to_vote (+ step 2)
E. Ballot form    GET v/{vslug}/vote/create — lifecycle, entitlement (cached), code step completed, agreement, legacy IP helper
F. Ballot submit  POST vote/submit — marks submitted, stores the ballot in the SESSION, counts selections only, step 3
G. Confirmation   GET vote/verify (step 4) — fresh entitlement, ballot present, legacy pre-check (IP count vs config; crashes
                     on refusal), code window / submission state, step-4 record
H. Final cast     POST vote/verify (step 5, not step-gated) — security chain, lifecycle, fresh entitlement, lock,
                     forCast(), legacy IP helper (dormant), code re-check, compare-and-set has_voted, save_vote,
                     step-5 record (vote id), markUserAsVoted, receipt, e-mail, COMMIT
I. After          verify_to_show / view-my-vote by receipt; committee receipt list after results
```

Second entry point into the final cast: **`POST /votes` (`vote.store`)** with only `auth`, `verified`, `vote.eligibility` (`electionRoutes.php:169`) [S\*] — protected by `store()`'s own checks; known as PBDIGIT-218.

## 5. CodeController Business Responsibilities

| Technical hook | Business purpose | Trigger | Reads | Writes | Decision | Authority | Later effect | Risk |
|---|---|---|---|---|---|---|---|---|
| `create()` 51–220 | Show code page; create / re-send / regenerate and deliver the voting code | GET slug.code.create | Code (tenant scope), slug | regeneration: new code, sent_at, has_code1_sent, usable, **used_at = null**, vote_submitted = false, **can_vote_now preserved**; slug extended, slug.can_vote_now = true | already voted → dashboard | navigation | a previously verified voter stays "verified" with a new, never-entered code | **High** (PBDIGIT-231); plaintext code logged (:181) and in page props without valid e-mail (:218) |
| `store()` 228–394 | Verify the code the voter types | POST slug.code | Code (by user, election, org) | via `markCodeAsVerified`; step 1 | **if already "verified" → skip verification entirely** (:295); else `verifyCode` | code-entry authority (no transaction) | sets the fact the final cast later reads (`can_vote_now`) | **High**; submitted code logged every attempt (:245) |
| `verifyCode()` 874–908 | Validate code value, usability, age, IP count | from store | Code, codes IP count | — | usable · exact match · age > window (config, not the column) · IP ≥ `max_use_clientIP ?? 7` | code-entry | — | expected + submitted code logged on mismatch (:885–886); separate IP authority |
| `markCodeAsVerified()` 910–935 | Record successful verification | from store | — | can_vote_now = true, used_at = now, client_ip, usable (strict mode never activates, §14) | — | — | enables ballot and final cast | — |
| `showAgreement()` / `submitAgreement()` 403–627 | Show / record the voter's agreement | GET/POST agreement | Code | has_agreed_to_vote, has_agreed_to_vote_at, voting_started_at; step 2 | code step completed; not voted | navigation (entitlement cached) | agreement read only by the ballot GET (`VoteController:299`) | low (Q5 = A accepted) |
| `getOrCreateCode()` 709–872 | Find / create / re-send the code row | from create | Code | create row (can_vote_now = false); re-send resets (can_vote_now = false) | early return if verified | — | — | plaintext code logged (:799, :863) |
| `isUserEligible()` 683–699 | Legacy eligibility | create, store | session flag, slug | — | **always true when a slug is present** | none | — | dead check on slug routes |
| `getElection()` 653–681 | Cross-check election vs slug | all | attributes | — | election / org match | integrity | — | — |
| `handleAlreadyVerified()` 945–1012 | Redirect a verified voter forward | store | steps | step 1 row | — | navigation | — | — |
| `generateUniqueCodeForOrganisation()` 1024–1042 | Unique 8-char code | create | codes | — | — | — | fallback returns 12 mixed-case chars (:1039) that cannot pass `size:8` [S] | low |
| route `/admin/ip-stats` → `getIPStatistics` | IP statistics | GET | — | — | — | — | **method does not exist** [S] | dead route |

All `CodeController` facts: [S\*], with the security-relevant ones (:245, :295, :885, :181, :799, :1039, ip-stats) re-read by the author [S].

## 6. Voting State Machine (derived from the code)

| Business state | Entry condition | Transition (by) | Authority | Persistent evidence | Can repeat? | Can bypass? | Risk |
|---|---|---|---|---|---|---|---|
| Registered voter | membership role voter, active, org | admin assignment | `VotingEntitlementQuery` | `election_memberships` | — | — | — |
| Admitted | policy requirements satisfied (identity evidence) | officer verification | inside entitlement | `voter_verifications` | yes (re-verify) | no | — |
| Voting session issued | slug for (election, user) | `start()` (checked) or other issuers (unchecked) | `start()` only | `voter_slugs` | yes (re-issue, slides expiry) | **yes — unchecked issuers** (code page still entitlement-gated) | Medium |
| Code issued | code row exists, has_code1_sent | `create()` / `getOrCreateCode()` | — | `codes` | yes (re-send / regenerate) | — | plaintext logs |
| Code step completed | `can_vote_now = true` | `CodeController::store()` | code entry | `codes.can_vote_now`, `used_at` | **survives regeneration** | **yes — regenerated code never entered** | High |
| Agreed | `has_agreed_to_vote = true` | `submitAgreement()` | navigation | `codes.has_agreed_to_vote` | overwritten | final cast does not require it (Q5 = A) | accepted |
| Ballot submitted | session ballot + `vote_submitted = true` | `first_submission()` | none (counts only) | session (database), `codes.vote_submitted` | yes | — | ballot content unvalidated |
| Confirmed (step 4) | step-4 record | `verify()` | navigation | `voter_slug_steps` | yes | **yes — final cast not step-gated** | — |
| **Participation consumed** | `codes.has_voted false → true` | compare-and-set in `store()` | **database** | `codes.has_voted` | **no** | **no** | GREEN |
| **Vote committed** | CAS = 1 row ∧ insert ∧ commit | `store()` transaction | DB transaction | `votes`, `results` | no | no | GREEN |
| Recorded as voted (projections) | after cast | `markUserAsVoted`, slug status | none | `codes.*`, `voter_slugs.has_voted` | — | — | `election_memberships.has_voted` never written (D9) |

## 7. Complete Hook Inventory — "implemented correctly?"

| Hook | Exists | Reachable | Correct position | Correct decision | Result enforced | Verdict |
|---|---|---|---|---|---|---|
| `forCast()` | yes | yes | yes (in transaction) | yes | yes | GREEN |
| Compare-and-set `has_voted` | yes | yes | yes (before insert) | yes | yes | GREEN |
| `failCastClosed()` | yes | yes | all `store()` exits except dormant IP-binding exit | yes | yes | GREEN |
| Fresh entitlement in `store()` (`ensureVoterMembership` + `decide()`) | yes | yes | yes | yes | yes | GREEN |
| Trust → Legitimacy at cast | yes | yes | before lock | yes | yes | GREEN |
| `EnsureElectionVoter` (code page) | yes | yes | yes | standing only, cached ≤300 s | yes | YELLOW |
| `VoteEligibility` | yes | yes | yes | lifecycle only on slug routes | **bypassed by demo session flag** (PBDIGIT-217) | YELLOW |
| `EnsureVotingActive` | yes | steps 3–5 | yes | lifecycle | yes; no direct test | YELLOW |
| `EnsureVoterStepOrder` | yes | yes | GET steps only | step order | **POSTs (incl. final cast) pass through** | YELLOW (navigation) |
| `ValidateVotingIp` / `validateVotingIpWithResponse` | yes | yes | — | `users.voting_ip` (no column) | never fires | dormant (PBDIGIT-234) |
| `vote_pre_check()` time / state branches | yes | **no** from `first_submission` — `vote_submitted` is set to true just before (:545–551) [S] | — | — | — | **RED (unreachable)** |
| `second_code_check()` (code window) | yes | step 4 only | not at final cast | yes | only on the page path | **RED at final cast** (R1-F6a) |
| `vote_post_check()` IP branch (step 4) | yes | yes | — | separate IP authority, crashes on refusal | yes (by crash) | **RED** (R1-F1 / R1-F2) |
| `validate_candidate_selections()` | yes | yes | yes | **counts only** | yes | **RED** (no content validation) |
| `validateVoteIntegrity()` | yes | **no** (only from unrouted `second_submission`) [S\*] | — | — | — | **RED (unreachable)** |
| `ElectionVotingController::resolveIpBlock` | yes | yes | entry | counts `voter_slugs.step_1_ip` | **never written → never blocks** [S] | RED (ineffective) |
| `secureSlugGeneration()` (admin recovery, CLI) | **no** [S] | — | — | — | — | RED (broken) |
| `getIPStatistics()` | **no** [S] | — | — | — | — | dead route |
| `ElectionMembership::markAsVoted()` | yes | **no caller** [S\*] | — | — | — | RED (D9) |
| `/vote`, `/test/generate-slug` | yes | yes | — | construct `VoterSlugService` without required argument [S] | fail | broken (and a test route in production routes) |

## 8. Authority Matrix

| # | Business rule | Current check(s) | Authoritative check | Duplicates | Can disagree? | Final-cast protected? | Status |
|---|---|---|---|---|---|---|---|
| 1 | Voter entitlement | middleware (cached), `start()`, `verify()`, `store()` | `decide()` in `forCast()` | defensive | freshness only | yes | GREEN |
| 2 | Admission | inside entitlement | same | — | no | yes | GREEN |
| 3 | Lifecycle | VoteEligibility, voting.active, `store()`, `decide()` | `ElectionLifecycle::canVote()` | defensive | no (but demo-flag bypass at code / agreement) | yes | YELLOW |
| 4 | Votability | as 3 | as 3 | | | yes | YELLOW |
| 5 | Voter role | entitlement policy | entitlement | — | no | yes | GREEN (unit-tested only) |
| 6 | Organisation | entitlement, consistency middleware | entitlement | integrity | no | yes | GREEN (unit-tested only) |
| 7 | Security / trust | Trust → Legitimacy | same, at cast | — | — | yes | GREEN |
| 8 | IP density | trust chain; step 4 config; code entry config ?? 7; election start (never blocks) | **none common** | **competing** | **yes** | trust chain only | **RED** |
| 9 | Code value | code entry; `forCast()` match | `forCast()` | defensive | no | yes | GREEN |
| 10 | Code expiry | code entry (sent_at vs config), step 3 (unreachable), step 4 | **none at cast** | — | — | **no** | **RED** |
| 11 | Code regeneration | `create()` | none | — | — | partial (new value required) | **RED** (keeps "verified") |
| 12 | Voting-session validity | slug window (sliding) | `ValidateVoterSlugWindow` | — | — | yes (sliding) | YELLOW |
| 13 | Ballot presence | `forCast()` | `forCast()` | — | — | yes | GREEN |
| 13b | **Ballot content** | counts only | **none** | — | — | **no** | **RED** |
| 14 | Already voted | pre-checks; CAS | **CAS** | non-authoritative pre-checks | staleness only | yes | GREEN |
| 15 | Participation consumption | CAS | CAS | — | — | yes | GREEN |
| 16 | Vote persistence | `save_vote()` in transaction | transaction | — | — | yes | GREEN |
| 17 | Atomicity | DB transaction | transaction | — | — | yes | GREEN |
| 18 | Anonymity | vote row has no voter field | — | — | — | row only | **RED (system level)** |
| 19 | Receipt / confirmation | receipt = secret_voteId, stored plaintext | — | — | — | — | YELLOW (K2–K5 accepted) |
| 20 | Demo vs real separation | controller branches, middleware demo bypasses | type checks | — | — | `forCast` / CAS real-only | **YELLOW** (startSpecificDemo, F10) |

## 9. Final-Cast Sequence (`VoteController::store`, real election)

| # | Step | Business purpose | Failure | Transaction | Written so far | Retry |
|---|---|---|---|---|---|---|
| 1 | middleware (slug, window, consistency, entitlement cached, step order pass-through, lifecycle, dormant IP, org, throttle, voting.active) | navigation / early refusal | redirect / exception | none | nothing | yes |
| 2 | `BEGIN` (outer level recorded) | | | open | — | |
| 3 | trust evaluation → legitimacy (real only) | security authority | reasoned refusal (E1) | rolled back | nothing | yes |
| 4 | lifecycle `canVote()` | | refusal | rolled back | nothing | yes |
| 5 | fresh entitlement | | redirect (self-rollback) | rolled back | nothing | yes |
| 6 | load code; slug lock | double-click guard | "being processed" | rolled back, foreign lock untouched | nothing | yes |
| 7 | read facts; `forCast()` | the cast decision | `refuseCast(reason)` | rolled back | nothing | yes |
| 8 | legacy IP helper | dormant | (would return without rollback) | — | nothing | — |
| 9 | input validation; code re-check | | reasoned | rolled back | nothing | yes |
| 10 | **compare-and-set `has_voted`** | consume participation | 0 rows → "already voted" | rolled back | nothing | no (already voted) |
| 11 | `save_vote()` | anonymous ballot + results | `\Throwable` → generic error | **rolled back incl. step 10** | — | yes |
| 12 | step-5 record (vote id), `markUserAsVoted`, slug voted, receipt, e-mail | bookkeeping | `\Exception` swallowed; `\Error` → rollback | in transaction | — | — |
| 13 | `COMMIT`, lock released | **vote is final** | — | committed | vote + consumption | no |
| 14 | audit logs (user, IP, "receipt_hash"), slug step 5, redirect | | `\Throwable` → generic error although committed (F11) | none | — | — |

## 10. Atomicity Audit

- **Invariant** "successful vote ⇔ CAS = 1 row ∧ insert ∧ commit": GREEN [D] (E4 R4-1…R4-4, re-audit).
- CAS: `WHERE id = ? AND has_voted = false`, writes `has_voted` only, `!== 1` refuses [S][D].
- Rollback: `save_vote()` has no try/catch or own transaction; `\Throwable` → `failCastClosed()` [S][D].
- Lock: released only by the acquiring path; cache lock is not the guarantee [D].
- Evidence classes: **database primitive** — tested; **same-connection interleaving** — tested (R4-2); **true concurrency** — **not tested** (single-process PHPUnit; `VotingConcurrencyProtectionTest` re-implements the logic and proves nothing about production) [S\*]; **theoretical** — PostgreSQL row lock on a conditional single-row UPDATE.
- Post-commit failures return a generic error for a committed vote (F11) [S].

## 11. Anonymity Audit

**Newly introduced by Phase 4: none** [D][S].

**Existing linkability (all pre-Phase-4):**

| ID | Channel | Evidence | Status |
|---|---|---|---|
| A5 | `vote_hash = sha256(code.id ‖ election.id ‖ code value ‖ cast timestamp ‖ app.vote_salt)` | `VoteController.php:2863–2869` [S]; Phase 1 K1 [D] | accepted interim risk — **detail: `app.vote_salt` is defined nowhere in `config/` or `.env.example` → always the empty string** [S]; setting a salt via `.env` alone cannot take effect |
| A6 | step-5 record stores `vote_id` with the voter's slug | `:1837–1841` [S]; K9 [D] | accepted interim risk |
| A1 | logs: receipt secret (`Log::info('private_key', ['user_id' => $private_key])` `:1786`), `vote_id` with slug / IP, `user_id` + `vote_id` (`:3454–3459`), full receipt + `user_id` (`:4328–4333`) | [S][S\*]; K8 [D] | accepted interim risk (partly) |
| **A7 (new)** | **plaintext ballot remains in the voter's session after the cast**: ballot stored under `session_name` (`:620`), never cleared (the only `forget` is commented out and targets another key, `:1958`); default `SESSION_DRIVER=database` (`config/session.php:21`) stores session payload with `user_id` | [S] (production driver value unknown) | **newly identified — requires PO / ARB decision** |
| **A8 (new detail)** | audit "receipt_hash" = `sha256(vote_id ‖ user_id ‖ election_id)` logged next to user, name, IP, user agent (`:1913–1929`) — computable for every (vote, voter) pair | [S] | **newly identified detail — requires decision** |
| A2 | voter-side timestamps (`code_to_save_vote_used_at`, step completed_at) vs `cast_at` | [D] Phase 1 | accepted |
| K2–K5 | receipt holder can view the ballot; committee sees all receipts after results | [D] Phase 1 | accepted |

The vote row itself has no voter field [D] — necessary but not sufficient.

## 12. Failure Semantics

| Failure | Business meaning | HTTP / redirect | User message | Vote? | Consumed? | Transaction |
|---|---|---|---|---|---|---|
| not entitled / not admitted | not on the voter roll / not admitted | middleware redirect (dashboard / election dashboard) | "not assigned / not eligible" | no | no | none or rolled back |
| election not active | outside voting | redirect | lifecycle reason | no | no | rolled back |
| code missing (final cast) | no credential | back, `vote` | reasoned (E3) | no | no | rolled back |
| code wrong (final cast) | wrong credential | back, `voting_code` | "Submitted code is false…" | no | no | rolled back |
| code not verified (`can_vote_now = 0`) | code step not done | back, `vote` | reasoned | no | no | rolled back |
| **code expired** | window elapsed | **cast succeeds** (direct POST) [D] | — | **yes** | yes | committed — **technically unsafe vs intended rule** |
| code regenerated, not entered | new code never verified | cast succeeds with new value [I] (F12) | — | yes | yes | — |
| session / ballot missing | no ballot | back, `vote` | reasoned | no | no | rolled back |
| already voted | participation consumed | dashboard, `vote` | "already voted" | no | — | rolled back |
| security denied | trust chain | back, `vote` | reasoned | no | no | rolled back |
| security review required | Deferred / Investigate | back, `vote` | reasoned (no HTTP test) | no | no | rolled back |
| CAS loses | competing consumption | dashboard, `vote` | "already voted" | no | no (by this request) | rolled back |
| vote persistence fails | DB error | back | "An error occurred…" | no | no | rolled back |
| unexpected `\Throwable` | system error | back | generic | no | no | rolled back |
| step-4 refusals (IP limit, already voted, missing code) | business refusal | dashboard | **"System error during verification"** | no | no | — — **business-confusing** |
| `MAX_USE_IP_ADDRESS` unset | config | step 4 → dashboard | "System error" | no | no | — — **blocks every real voter** [D] |

## 13. Bypass Analysis

| Path | Result | Classification |
|---|---|---|
| Direct final POST without steps 3 / 4 | cast accepted (final cast not step-gated) [D] | navigation bypass — **harmless only if every validity rule is at the cast**; today it is not (code window, ballot content) → **actual business bypass** |
| Direct final POST with expired code | vote stored [D] | **actual business bypass** (pending R1-F6a decision) |
| Regenerated code, never entered | code entry skipped (`store()` :295); cast accepted with new value [D for code entry, Phase 1 F2][I for cast] | **actual business bypass** (PBDIGIT-231) |
| Crafted ballot (foreign / cross-region / duplicate candidacy) | stored as submitted [S] | **actual business bypass — ballot integrity** (not dynamically confirmed) |
| Skipping agreement | ballot GET requires it; submit and cast do not [S] | intentionally allowed (Q5 = A) |
| Back / refresh / duplicate submit | CAS / pre-check refuse the second | safe [D] |
| Concurrent submissions | lock + CAS | safe by reasoning; same-connection interleaving tested [D] |
| Missing session / code | reasoned refusal | safe [D] |
| Stale `can_vote_now` | survives regeneration | see regeneration |
| Stale `vote_submitted` | not read at cast | harmless at cast |
| Stale `has_voted` | CAS authoritative | safe |
| Unchecked slug issuers | code page still entitlement-gated (cached ≤300 s) | navigation bypass; PBDIGIT-214 |
| Demo session flag | lifecycle skipped at code / agreement; steps 3–5 still gated | partial bypass (PBDIGIT-217) |
| `/election/demo/select` with a real election id | real slug recreated (existing real slug for that election hard-deleted), demo flag set [S] | **actual bypass of entry checks + audit-trail loss [I]** (new) |
| `POST /votes` (legacy) | `store()` checks apply | covered by `store()` (PBDIGIT-218) |

## 14. Configuration Audit

| Configuration | Default | Runtime type | Reader(s) | Business meaning | Security meaning | Consistency |
|---|---|---|---|---|---|---|
| `MAX_USE_IP_ADDRESS` → `app.max_use_clientIP` | **none** | string | `vote_post_check` (none → always exceeded), CodeController (`?? 7`; empty string would block all — [I]), DemoCodeController (7), ElectionVotingController (`(int)… 0` → disabled), ElectionSettingsService (4) | votes per IP | IP density | **inconsistent (4 interpretations)** |
| election `max_votes_per_ip` | 6 | int | trust chain | votes per IP | the authority at cast | differs from the above |
| `TWO_CODES_SYSTEM` | 0 | **string** | real path `=== 1` / `!== 1` / `=== true` (never matches), demo `== 1`, `voting.is_strict` (unused) | second code | none in real elections | **inconsistent; design debt** |
| `VOTING_TIME_IN_MINUTES` → `voting.time_in_minutes` | 30 (`(int)`) | int | CodeController window, code column, slug lifetime, CheckVotingWindow | code / session window | — | **also `election.voting_time_in_minutes` default 20 — no reader** |
| `voting.slug_expiration_minutes` | **not defined** → 30 | — | `VoterSlugService:423`, `VoterSlug:157` | slug lifetime | — | not configurable |
| `app.vote_salt` | **not defined** → `''` | — | `save_vote`, `VoteStorageTrait` | — | `vote_hash` secrecy | **no salt possible without code change** |
| `CONTROL_IP_ADDRESS` | 1 (`voting_security`) / false (settings service) | string | ValidateVotingIp, ElectionProcessController | per-user IP control | dormant | inconsistent defaults |
| `VOTING_CONSTITUTIONAL_MODE` | true (config) / false (middleware fallback) | — | ValidateVotingIp | divergence-only mode | — | config wins |
| `SELECT_ALL_REQUIRED` | 'no' | string | only when no election (`VoteController:1228`) | selection rule | — | per-election constraint used instead |
| `SESSION_DRIVER` | database | string | Laravel | — | **ballot persists with user_id (A7)** | production value unknown |

## 15. Test-to-Invariant Matrix (summary; full mapping by the test sub-audit [S\*])

| Business invariant | Production enforcement | Test evidence | Type | Gap |
|---|---|---|---|---|
| Entitled only | middleware + `forCast()` | `VotingProcessEntitlementBoundaryTest`, `VotingEntitlementUnificationTest`, unit policy tests | F / U | no HTTP test where middleware passes but `store()` refuses; NotVoterRole / OrganisationMismatch unit-only |
| Admitted only | entitlement | `IdentityVerificationAdmissionTest`, `VotingProcessEntitlementBoundaryTest` | F | **no positive end-to-end cast with policy ON** |
| Lifecycle required | middleware + `store()` + `decide()` | `ConstitutionalVotingProtectionTest`, `TOCTOUTest`, `ConstitutionalBoundaryTest` | F | **confounded** (no membership created → refused for entitlement, not lifecycle); `EnsureVotingActive` untested |
| At most one vote | CAS | Phase 1 integrity, R3-5, R4-2 | C / DB | no true parallel test; `DemoAndRealElectionVotingTest::real_election_blocks_second_vote` misnamed (inserts rows directly) |
| No vote without consumption | CAS before insert | R4-1, R4-3, R5 | DB | — |
| No consumption without vote | rollback | R4-4, B2, R1-D | DB | — |
| Ballot row anonymous | schema | R5, K9, `VoteStorageTest` | DB | system-level linkability only pinned as known gaps |
| Direct POST cannot bypass | `forCast()` | R3-1…R3-7, R1, G1 | F | **code expiry untested (and unenforced); ballot content untested** |
| Code-entry semantics | CodeController | regeneration tests, Phase 1 F2 | F / C | **no HTTP test of a correct code, a well-formed wrong code, or an expired code at code entry** |
| Security denial | trust chain | R1-A, unit | F / U | ReviewRequired has no HTTP test |
| Fail closed | E1 | R1, R3, R4 | F / DB | lifecycle / membership exits inside `store()` untested |
| Demo separation | branches | R6 | DB | `startSpecificDemo` with real id untested; type partition unpinned |
| Retry safe | CAS / lock | Phase 1, R1-B, R4-2 | C | `VotingConcurrencyProtectionTest` tautological |
| Step order | middleware (GET only) | — | — | **no meaningful test**; POST pass-through unpinned |
| Agreement | ballot GET | — | — | no test of the ballot GET refusal |

Skipped / stale: `IpValidationTest` (8), `RealElectionWorkflowTest` (11), `VoterVerificationTest` (5 enforcement tests), `VoteSubmissionWorkflowTest` (40 methods calling non-existent routes), 17 `.bak` files, `tests/Integration` and `tests/Boundary` not in any test suite [S\*].

## 16. Business Invariants (derived from the code)

| # | Business invariant | Where enforced | When | Authoritative | Evidence | Test evidence | Gap / verdict |
|---|---|---|---|---|---|---|---|
| 1 | Only entitled voters can vote | `decide()` in `forCast()`; middleware | final cast (fresh) | YES | [S][D] | F + U | in-store refusal untested → **PROVEN at cast (YELLOW tests)** |
| 2 | Only admitted voters can vote | inside entitlement | final cast | YES | [S][D] | F | no positive policy-ON cast → **PROVEN (YELLOW)** |
| 3 | At most one committed vote per voter | CAS on `codes.has_voted` + one code per (election, user) | final cast | YES | [D] | DB / C | parallelism reasoned → **PROVEN (with stated limit)** |
| 4 | No vote without consumption | CAS before insert | final cast | YES | [D] | DB | **PROVEN** |
| 5 | No consumption without vote | transaction rollback | final cast | YES | [D] | DB | **PROVEN** |
| 6 | Anonymous ballot data contains no voter identity | vote row | persistence | YES (row) | [D] | DB | row **PROVEN**; voter↔ballot **unlinkability NOT PROVEN — contradicted** (A5, A6, A1, A7, A8) |
| 7 | Direct final POST cannot bypass required final-cast conditions | `forCast()` | final cast | partial | [D][S] | F | **NOT PROVEN — contradicted** (code window; ballot content) |
| 8 | Code verification has a clearly defined meaning | CodeController | code entry | — | [S][D] | C | **NOT PROVEN** (survives regeneration; timestamp cleared) |
| 9 | Code validity has a clearly defined authority | none at cast | — | NO | [D] | — | **NOT PROVEN** (decision brief pending) |
| 10 | Security decisions have one authority | trust chain at cast | final cast | at cast only | [D] | F | **NOT PROVEN across the journey** (IP) |
| 11 | Election lifecycle has one authority | `ElectionLifecycle::canVote()` | several | YES | [S] | confounded | GREEN for the cast; demo-flag bypass at steps 1–2 → **YELLOW** |
| 12 | Failure paths fail closed | E1 at cast | final cast | YES | [D] | F / DB | cast **PROVEN**; step 4 crash-driven (YELLOW) |
| 13 | Demo elections cannot alter real-election rules | type branches | various | partial | [S] | DB | **NOT PROVEN** (`startSpecificDemo` real id; F10) |
| 14 | Retry behaviour is deterministic and safe | CAS / lock | final cast | YES | [D] | C | **PROVEN for double voting**; `first_submission` writes state before validating (YELLOW) |

## 17. Findings

| ID | Severity | Concern | Evidence | Class | Status |
|---|---|---|---|---|---|
| **VE-01** | **CRITICAL (integrity)** | Ballot content not validated on the server: client-supplied post / candidacy ids stored in `votes` and `results` without checking election, post, region, duplicates; the integrity validator exists but is unreachable | `validate_candidate_selections` (counts only), `save_vote` (verbatim JSON), `Vote.php:74–106` results from client ids; FKs only require existence [S]; `validateVoteIntegrity` only from unrouted `second_submission` [S\*] | A (existing) | **RED — dynamic confirmation required first** |
| **VE-02** | HIGH | Code-verification window not enforced at final cast | Round 1 re-audit probe A [D] | A (pre-Phase-4), C (found) | RED — decision brief R1-F6a |
| **VE-03** | HIGH | Regenerated code keeps "verified"; code entry skipped for the new code | `CodeController.php:149–160`, `:295` [S]; Phase 1 F2 [D] | A (PBDIGIT-231) | RED |
| **VE-04** | HIGH (secrecy) | Plaintext ballot persists in the voter's database session after the cast (A7) | `:620`, `:1958`, `config/session.php:21` [S] | A (existing), C (newly identified) | RED — PO decision |
| **VE-05** | HIGH (secrecy) | Audit "receipt_hash" from `vote_id ‖ user_id ‖ election_id` logged next to identity (A8) | `:1913–1929` [S] | A, C | RED — PO decision |
| **VE-06** | HIGH (credential) | Voting codes and the receipt secret written to logs in plaintext | `CodeController.php:181, 245, 799, 863, 885–886`; `VoteController.php:1786` [S] | A | RED |
| **VE-07** | HIGH | Multiple independent IP-density authorities; step 4 blocks all real voters if `MAX_USE_IP_ADDRESS` is unset | R1-F1 / R1-F2 [D] | A, C | RED — Round 1 (paused) |
| VE-08 | MEDIUM | `vote_salt` undefined → `vote_hash` unsalted (A5 detail) | config search [S] | D (A5 accepted) | accepted risk — inform PO |
| VE-09 | MEDIUM | `/election/demo/select` accepts a real election id: recreates the real slug (hard-deleting the existing one), sets the demo flag (lifecycle bypass at steps 1–2) | `ElectionManagementController::startSpecificDemo` [S] | A, C | RED |
| VE-10 | MEDIUM | Slug issuers without entitlement check | [S\*] | A (PBDIGIT-214) | YELLOW |
| VE-11 | MEDIUM | Demo session flag skips lifecycle at code / agreement | [S\*] | A (PBDIGIT-217) | YELLOW |
| VE-12 | MEDIUM | Step-3 expiry / state branches unreachable (`vote_submitted` set before `vote_pre_check`); `first_submission` writes state before validating | `:543–551` [S] | A | YELLOW |
| VE-13 | MEDIUM | Step-4 refusals crash into a generic "System error" | R1 [D] | A | YELLOW |
| VE-14 | MEDIUM (security debt) | `debug_code` / `verification_code` show the plaintext code for voters without valid e-mail and in local / development | [S] | A | YELLOW — separate decision |
| VE-15 | MEDIUM | Election-start IP limit never blocks (`step_1_ip` never written) | [S] | A | YELLOW |
| VE-16 | LOW | Broken or dead paths: `/vote`, `/test/generate-slug` (test route in production routes), `/admin/ip-stats`, admin / CLI recovery (`secureSlugGeneration` missing), `thankyou` (unauthenticated, undefined variable), `verify_final_vote` | [S][S\*] | E | design debt |
| VE-17 | LOW | `election_memberships.has_voted` never written (D9) | [S\*][D] | A | known |
| VE-18 | LOW | Configuration inconsistencies (§14) | [S] | E | design debt |
| VE-19 | INFO | Two-code mode not implemented for real elections | [S] | E | design debt |
| VE-20 | INFO | Test gaps (§15) | [S\*] | F | — |

## 18. Existing vs New Findings

- **A. Existing defects (present before Phase 4):** VE-01, VE-02, VE-03, VE-04, VE-05, VE-06, VE-07, VE-09, VE-10, VE-11, VE-12, VE-13, VE-14, VE-15, VE-16, VE-17.
- **B. Phase 4 changes:** resolved PBDIGIT-232, 233 (final cast), 219, 230; introduced **no** new voter↔ballot link and no new authority [D].
- **C. Phase 5 findings (discovered today, all pre-existing in code):** VE-01 (newly identified), VE-02 (confirmed), VE-04 / A7 and VE-05 / A8 (newly identified), VE-06 (newly consolidated), VE-08 detail, VE-09, VE-15, VE-16 details.
- **D. Accepted interim risks:** A1, A2, A5, A6, K2–K5 (Gate 1 / Phase 3 decisions); VE-08 extends A5.
- **E. Design debt:** VE-16, VE-18, VE-19, R1-F8.
- **F. Unknown:** production `SESSION_DRIVER`, `MAX_USE_IP_ADDRESS`, `APP_ENV`, `TWO_CODES_SYSTEM` values; dynamic behaviour of VE-01 and F12; true concurrency.

## 19. Critical Unknowns

1. **VE-01 dynamic confirmation** — does a crafted ballot (foreign election candidacy, other region's post, duplicate candidacy) get stored and counted? (A synthetic-data probe is needed; not run because this audit may create no files.)
2. Production configuration values (`SESSION_DRIVER`, `MAX_USE_IP_ADDRESS`, `APP_ENV`, `TWO_CODES_SYSTEM`) — ops verification.
3. Whether results tallying filters or groups in a way that would include foreign / duplicate candidacies (results-counting code not audited here).
4. F12 (regenerated, never-entered code at the final cast) — derived, not executed.
5. True concurrent behaviour of the CAS — reasoned only.
6. Log retention and access (who can read the logs that hold codes and receipt secrets).

## 20. Recommended Decisions (PO / ARB)

1. **VE-01:** authorise a synthetic-data characterization of ballot content integrity; then decide that the server must validate every selection against the voter's assembled ballot (election, post, region, uniqueness, counts per post).
2. **R1-F6a / VE-02:** decide the decision brief (code validity precondition).
3. **VE-03 / PBDIGIT-231:** decide the meaning of "code verified" across regeneration (together with R1-F6a).
4. **A7 / A8 (VE-04, VE-05):** decide whether these are accepted interim risks or must be removed before a real election (they were not part of the earlier acceptance).
5. **VE-06:** decide that voting credentials and receipt secrets must never be logged; decide on existing log retention.
6. **C1 / VE-07:** resume Round 1 (IP authority) after the above.
7. **VE-09:** decide that demo endpoints must refuse real elections.

## 21. Recommended Future Work (descriptions only — nothing implemented)

- Server-side ballot validation against the assembled ballot (VE-01); reuse or re-home `validateVoteIntegrity` after a decision.
- Final-cast validity precondition (R1-F6a) and regeneration semantics (VE-03).
- Clear the session ballot after the cast or store it outside the voter's persisted session (A7) — after the anonymity decision.
- Remove credential / secret logging (VE-06) and replace the identity-linked audit "receipt_hash" (A8) — after decision.
- Demo endpoint type guard (VE-09); entitlement check on all slug issuers (VE-10, PBDIGIT-214); demo-flag isolation (VE-11, PBDIGIT-217).
- Tests: lifecycle refusal of a cast-ready voter; correct / wrong / expired code at code entry; step-order behaviour; positive cast with policy ON; ballot-content contracts; `POST /votes`.
- Retire dead / broken routes and the test route (VE-16); consolidate configuration (VE-18).

## 22. Evidence Appendix (key references)

- Final cast: `VoteController.php:1469–1979` (CAS `:1797–1823`, `forCast` `:1674–1698`, `failCastClosed` helper).
- Code entry: `CodeController.php:228–394` (skip `:295`), `:874–908`, regeneration `:149–160`.
- Ballot: `VoteController.php:474–721` (`:543–551`, `:596`, `:620`), `validate_candidate_selections` `:1217–1301`, `save_vote` `:2786–2928`, `Vote.php:74–106`, results FKs `2026_03_05_000012_create_uuid_results_table.php:22–43`.
- Anonymity: `:2863–2869` (vote_hash), `:1786`, `:1837–1841`, `:1913–1929`, `:1958`, `config/session.php:21`.
- Entry: `ElectionVotingController.php:105–213`, `ElectionManagementController::startSpecificDemo`, `VoterSlugService.php:19, 32–99, 172–194, 403–479`.
- Middleware: `EnsureVoterStepOrder.php:74–79`, `VoteEligibility.php:36–40`, `EnsureElectionVoter.php:31–54`.
- Configuration: `config/app.php:149`, `config/voting.php:14–34`, `config/election.php:30`, `config/voting_security.php`.
- Dynamic evidence: Phase 1 characterization suite; Phase 4 suites (E1–E4); Round 1 probes and re-audit probes (2026-10-05).

## 23. Audit Limitations

- No probe was run in this audit (no files may be created); VE-01, F12 and several entry-path effects rest on source reading.
- Parts of the inventory were produced by read-only sub-audits and are marked [S\*]; the security-relevant ones were re-read by the author.
- Production configuration and log retention are unknown (`.env` not read).
- Results tallying, demo controllers in depth, frontend ballot assembly and admin / committee tooling beyond the receipt list were not audited.
- True concurrency is reasoned, not tested.

---

## Management Summary

### Can we currently trust the voting engine?
**NOT YET PROVEN.**

### What is definitely safe?
- A voter's participation can be consumed only once; a vote cannot be committed without it and it is not consumed without a committed vote.
- Only voters who are entitled (and admitted, where the policy requires it) can cast, checked fresh at the moment of casting.
- The election must be open at the moment of casting.
- Failures at the final cast leave no vote and no consumed participation and produce no server error.
- The stored ballot row contains no voter identity.

### What is definitely unsafe?
- The server stores whatever ballot selections it receives (not yet confirmed by execution, but clear in the code).
- A vote can be cast with an expired voting code by calling the final step directly (confirmed).
- After a code is regenerated, the new code need not be verified (confirmed at code entry).
- Voting codes and receipt secrets are written to logs in plaintext.
- Several existing channels can link a voter to their ballot, including two newly identified ones (ballot left in the session; identity-linked audit hash).
- Missing IP configuration blocks every real voter at the confirmation step.

### What is not yet proven?
- Ballot-content integrity under crafted requests; true concurrent behaviour; production configuration values; effects of regeneration at the final cast; a full positive cast with identity verification required.

### Which business decisions are still required?
- Ballot validation rule (VE-01); code validity precondition (R1-F6a); meaning of code verification across regeneration (VE-03); acceptance or removal of A7 / A8; no credentials in logs (VE-06); one IP-security authority (C1); demo endpoints must refuse real elections (VE-09).

### Which technical fixes are required (after decisions)?
- Server-side ballot validation; final-cast validity precondition; regeneration semantics; session ballot handling; logging hygiene; demo type guard; entitlement on all session issuers; reasoned step-4 refusals.

### Which issues are merely design debt?
- Two-code mode flag; dead and broken routes; configuration inconsistencies; unreachable legacy checks; GET writes at step 4.

### What must be completed before a real election?
- VE-01 characterized and decided (and fixed if confirmed); R1-F6a and VE-03 decided and enforced; A7 / A8 decided; credential logging removed or explicitly accepted with controlled log access; `MAX_USE_IP_ADDRESS` (and session driver, `APP_ENV`) verified in the live environment; the existing accepted anonymity risks (A1, A5, A6) re-confirmed by the PO with the new details (empty salt, A7, A8).

### What must NOT be changed without PO / ARB approval?
- The Phase 4 final cast (cast decision, compare-and-set, transaction and lock handling) — frozen; the cast-decision reason vocabulary (E2-D3); the anonymity model (Phase 2.0); demo-election behaviour (Q4); the agreement rule (Q5 = A); IP binding (Phase 7 blocked).
