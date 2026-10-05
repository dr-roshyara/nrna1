# CodeController Refactoring Blueprint v1.0 — R0 Responsibility Discovery (PBDIGIT-238)

**Date:** 2026-10-05 · **Gate:** R0 (discovery + characterization) · **Status:** ✅ **R0 APPROVED by principal-architect review (2026-10-05) with three amendments applied (§2a invariant, §6 R3 orchestrator / R4 semantic API, §7 boundary wording + R1 invariants).** No production change made. R1 not started.
**Source of truth:** `app/Http/Controllers/CodeController.php` at HEAD `ac5ed44fe` — **1,080 lines** (the suggestion document cites 2,161 lines; this blueprint uses the repository file).
**Plan:** `.claude/plans/wobbly-foraging-pillow.md` (approved with amendments 1–4) · suggestions `docs/publicdigit/brainstorming/voting_engine/refactor_code_controller.md`, `refactor_controller1.md`.
**Evidence:** characterization `tests/Feature/Voting/CodeControllerRealElectionCharacterizationTest.php` (13 tests, all GREEN at HEAD) · baseline §8.
**Prepared by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable). Engineering supplies evidence; the PO decides.
**Labels:** [S] read in source · [D] shown by a test · [I] inference. **CURRENT BEHAVIOUR + LEGACY/DEFECT** = pinned as it is, not adopted as architecture (amendment 3).

---

## 1. Amendment 1 — election types (R1 prerequisite)

**Result: only `real` and `demo` exist; `real` is the only type legitimately served by these routes. No STOP condition.** [S]

| Evidence | Value |
|---|---|
| Schema | `elections.type` enum `['demo','real']` (`2026_03_05_000004:17`), converted to varchar with the documented value list `'real','demo'` (`2026_04_19_000001:28`) |
| Writers | real: `ElectionManagementController:162` · demo: `DemoElectionCreationService:37`, `SetupDemoElection`, `SetupPublicDemoElection` — no other value written |
| Model | `Election::isDemo()` / `isReal()` only |
| Other literals | `central`, `geo_unit`, `static`, `platform`, `tenant` belong to Membership / Geography / Organisation models, not `Election` |
| Test note | `ElectionFactory::definition()` picks `type` **at random** from `['demo','real']` — every new test sets `type` explicitly |

## 2. Current authority map (overlapping authorities)

| Concern | Authorities in play today | Observed (CodeController) | Classification |
|---|---|---|---|
| "may this person use the code step?" | `ensure.election.voter` (`standingOf()`, no lifecycle) · trait `ensureVoterMembership()` (`standingOf()`) · `isUserEligible()` (demo session → true · voter slug → true · `users.can_vote`) | slug routes: membership by middleware [D r0_02]; controller eligibility = **slug presence** [D r0_03]; `users.can_vote` never reached on slug routes | CURRENT BEHAVIOUR + LEGACY — *slug ≠ entitlement*; target R3 `PreVotingGate` over `VotingEntitlement` |
| lifecycle / voting period | `vote.eligibility` (`ElectionLifecycle::canVote()` unless session `selected_election_type = demo`) · none in the controller | code step and agreement reachable outside voting when the demo session flag is set (F-C2) | DEFECT (middleware; follow-up item) — target R3 `VotingPeriodPolicy` |
| code verification state | `Code.can_vote_now` | set by `markCodeAsVerified()`; preserved across regeneration in `create()` | process state, **not** authorization; PBDIGIT-231 [D r0_10] |
| participation | `Code.has_voted` | four `real && has_voted` guards [D r0_04–06] | participation state; demo-conditional → R1 makes it unconditional |
| time | `config('voting.time_in_minutes')` (30) | code lifetime **and** slug-window extension | CURRENT BEHAVIOUR + LEGACY — 30 min ≠ election voting period [D r0_08] |
| IP policy | `verifyCode()` count of `has_voted = 1` by `client_ip`; limit `config('app.max_use_clientIP') ?? 7` | post-participation count at code entry [D r0_11] | CURRENT BEHAVIOUR (C1, F-C7) — target R3 Network policy |
| workflow state | `VoterStepTrackingService` (steps table) **and** legacy `VoterProgressService` | both written in `store()` / `submitAgreement()` | LEGACY duplication — target R5 |

### 2a. Architecture invariant (principal-architect review of R0, 2026-10-05)

```
codes.can_vote_now  →  Code::isVerified()  →  "Has the code verification step been completed?"
                                   ≠
VotingEntitlement (policies orchestrated by PreVotingGate)  →  "May this voter participate?"
```

- `codes.can_vote_now` is the **code-verification / process state**, not authorization — its documented meaning is *"Has user verified the code?"* (historical `database/migrations_backup_20260305/2026_03_01_000007_create_codes_table.php:34`) and the model's scope *"verified codes (can_vote_now = 1)"* (`app/Models/Code.php:181–188`).
- `codes.has_voted` is the **participation state**. Neither flag is, or becomes, `Code::canVote()`.
- **`PreVotingGate` (R3) is an application-level orchestrator, not a new authority:** it evaluates the separate policies and returns a `PreVotingDecision`; **`VotingEntitlement` remains authoritative** for *"may this voter participate?"*; each policy keeps its own domain meaning.
- **Time concepts are distinct (architecture risk, R3 / R4 — not R1):** election voting period (`Election` voting start / end) ≠ voting-code validity (`VotingCode` issued / expires) ≠ voter-slug validity (`VoterSlug` activated / expires). Today one value (`voting.time_in_minutes`) serves the latter two and the period is checked only in middleware.

## 3. Responsibility Discovery Matrix

Marks: **K** keep · **M** move · **D** delete · **R** replace · **F** defer. Phase = target refactoring phase.

### 3.1 `create()` — GET `v/{vslug}/code/create` (51–220)

| Lines | Operation | Category | Current authority | Observed | Ref | Mark | Target owner | Phase |
|---|---|---|---|---|---|---|---|---|
| 53–55 | resolve user / election / slug | Context | `getUser`, `getElection`, request attribute | — | — | M | `VotingRequestContext` | R2 |
| 57–62, 91–97 | `Log::emergency` diagnostics | Audit | — | fires on every visit | F-C15 | D | — | R7 |
| 66 | write `current_organisation_id` to session on GET | Context | session | — | F-C13 | R | tenant context in `VotingRequestContext` | R2 |
| 71–73 | membership (skipped with slug) | Membership | trait `standingOf()` | slug path relies on middleware | — | M | `PreVotingGate` / MembershipPolicy | R3 |
| 76–78 | load Code (tenant-scoped) | Code Lifecycle | Eloquent | differs from `getOrCreateCode()` (unscoped) | F-C8 | M | VotingCode use case | R4 |
| 99–105 | already verified & **no** slug → redirect | Voting Journey | `can_vote_now` | unreachable on slug routes | F-C14 | F | VotingJourney | R5 |
| 108–115 | **`real &&`** has_voted → dashboard | Participation / **Demo** | `has_voted` | [D r0_04] | — | **R (R1)** → unconditional; then M | ParticipationPolicy | R1 / R3 |
| 118–120 | eligibility | Entitlement / **Legacy** | `isUserEligible()` | slug → true [D r0_03] | — | R | `PreVotingGate` | R3 |
| 123 | get or create Code (+ notify) | Code Lifecycle | `getOrCreateCode()` | [D r0_01] | — | M | `IssueVotingCode` | R4 |
| 126–138 | regeneration decision (`>=` window; or flags zeroed) | Code Lifecycle | `voting.time_in_minutes` | second regeneration path besides `getOrCreateCode()` (`>`) | F-C3, F-C15 | R | `RegenerateVotingCode` (one rule) | R4 |
| 151–160 | regenerate, **preserve `can_vote_now`** | Code Lifecycle / **Legacy** | — | known gap | PBDIGIT-231, VE-03 | F (decision) | `RegenerateVotingCode` | R4 |
| 163–169 | extend slug (`expires_at`, `is_active`, `can_vote_now`, `status`) | VoterSlug | controller | window re-opened after every expiry | F-C3, R1-F6a | M | VoterSlug lifecycle owner | R5 |
| 177–188 | notify + **log plaintext code (181)** | Code Lifecycle / Audit | — | secret in log | VE-06 | **R (R1)** drop value; then M | notification adapter | R1 / R4 |
| 195–219 | JSON / Inertia render; **code shown when no valid e-mail (218)** | HTTP / Code Lifecycle | — | code on page | F-C5 | K (HTTP) · F (218, decision) | CodeController presenter | R7 |

### 3.2 `store()` — POST `v/{vslug}/code` (228–394)

| Lines | Operation | Category | Current authority | Observed | Ref | Mark | Target owner | Phase |
|---|---|---|---|---|---|---|---|---|
| 230–232 | resolve context | Context | helpers | — | — | M | `VotingRequestContext` | R2 |
| 236–238 | membership (skipped with slug) | Membership | trait | — | — | M | MembershipPolicy | R3 |
| 240–247 | log incl. **`input` = submitted code (245)** | Audit | — | secret in log | VE-06 | **R (R1)** | — | R1 |
| 250–261 | validate `voting_code` (size 8), normalise | HTTP | FormRequest-like | — | — | K | `VerifyVotingCodeCommand::fromRequest` | R4/R7 |
| 264–266 | eligibility | Entitlement / Legacy | `isUserEligible()` | slug → true | — | R | `PreVotingGate` | R3 |
| 270–282 | load Code (tenant-scoped + org filter) | Code Lifecycle | Eloquent | soft-deleted code not found here but found in create | F-C8, PBDIGIT-235 | M | VotingCode repository | R4 |
| 285–292 | **`real &&`** has_voted → error | Participation / **Demo** | `has_voted` | [D r0_05] | — | **R (R1)** | ParticipationPolicy | R1 / R3 |
| 295–297 | already verified → short-circuit **before** value check | Code Lifecycle / **Defect** | `can_vote_now` | [D r0_10] | PBDIGIT-231 | F (decision) | `VerifyVotingCode` | R4 |
| 300–304 | `verifyCode()` | Code Lifecycle + IP | helper | [D r0_07, r0_08, r0_11] | — | M (split) | `VerifyVotingCode` + Network policy | R3/R4 |
| 312 | `markCodeAsVerified()` | Code Lifecycle | helper | [D r0_09] | F-C4 | M | `VerifyVotingCode` | R4 |
| 323–354 | step 1 via `VoterStepTrackingService` (failure swallowed) | Voting Journey | step table | fails open | — | M | VotingJourney | R5 |
| 357–359 | legacy `VoterProgressService` via `advanceSlugStep()` | Voting Journey / **Legacy** | progress service | duplicate state | — | D (after characterization) | — | R5 |
| 366–372 | audit `code_verified` | Audit | `ElectionAuditService` | — | — | K/M | audit application service | R4 |
| 375–393 | redirect (+ unreachable non-slug `code.agreement`) | HTTP | — | — | F-C14 | K (slug) · D (non-slug) | presenter | R7 |

### 3.3 `showAgreement()` — GET `v/{vslug}/vote/agreement` (403–510)

| Lines | Operation | Category | Current authority | Observed | Ref | Mark | Target owner | Phase |
|---|---|---|---|---|---|---|---|---|
| 405–412 | context + membership (always) | Context / Membership | trait | — | — | M | context + MembershipPolicy | R2/R3 |
| 422–425 | load Code **unscoped** (`withoutGlobalScopes`) | Code Lifecycle | Eloquent | also lifts soft deletes | PBDIGIT-235 | F | VotingCode repository | R4 |
| 428–431 | **`real &&`** has_voted → dashboard | Participation / **Demo** | `has_voted` | [D r0_06] | — | **R (R1)** | ParticipationPolicy | R1 / R3 |
| 433–440 | require `can_vote_now` | Voting Journey | `can_vote_now` (process state) | — | — | M | VotingJourney | R5 |
| 443–490 | agreed → **auto-record step 2 on GET** → redirect to ballot | Voting Journey / **Defect** | step table | state mutation on GET | — | R | explicit journey command | R5 |
| 493–509 | render agreement (texts inline) | HTTP / Agreement | — | [D r0_12] | — | K | presenter | R6/R7 |

### 3.4 `submitAgreement()` — POST `v/{vslug}/code/agreement` (518–627)

| Lines | Operation | Category | Current authority | Observed | Ref | Mark | Target owner | Phase |
|---|---|---|---|---|---|---|---|---|
| 520–525 | log incl. `$request->all()` (before context) | Audit | — | request dump | follow-up | F | — | later |
| 527–534 | context + membership | Context / Membership | trait | — | — | M | R2/R3 | R2/R3 |
| 544–549 | validate `agreement` accepted | HTTP | — | — | — | K | command | R6 |
| 552–555 | load Code unscoped | Code Lifecycle | Eloquent | — | PBDIGIT-235 | F | repository | R4 |
| 558–561 | **`real &&`** has_voted → dashboard | Participation / **Demo** | `has_voted` | [D r0_06] | — | **R (R1)** | ParticipationPolicy | R1 / R3 |
| 563–566 | require `can_vote_now` (dereferences `$voterSlug->slug`) | Voting Journey | — | null-unsafe | F-C14 | M | VotingJourney | R5 |
| 569–573 | record agreement, **overwrite `voting_started_at`** | Agreement | Code columns | no "already agreed" guard | F-C10 | M | `AcceptVotingAgreement` | R6 |
| 576–606 | step 2 tracker + legacy progress | Voting Journey / Legacy | both services | duplicate state | — | M / D | VotingJourney | R5 |
| 613–618 | audit `agreement_accepted` | Audit | `ElectionAuditService` | — | — | K/M | audit service | R6 |
| 621–626 | redirect to ballot | HTTP | — | [D r0_13] | — | K | presenter | R7 |

### 3.5 Helpers

| Lines | Helper | Category | Observed | Ref | Mark | Target owner | Phase |
|---|---|---|---|---|---|---|---|
| 36–41 | constructor: client IP, `max_use_clientIP ?? 7`, `time_in_minutes` | IP / time | unset limit → 7 (fail-open vs middleware) | F-C7 | R | Network policy config | R3 |
| 639–651 | `getUser` | Context | — | — | M | `VotingRequestContext` | R2 |
| 653–681 | `getElection`: requires middleware election; slug ↔ election ↔ org consistency; generic `\Exception` (500) | Context | runs **first** in every public method (54, 231, 406, 528) — R1 boundary site | F-C15 | K + **R1 guard** | context resolver | R1 / R2 |
| 683–699 | `isUserEligible`: **demo session → true (686)** · slug → true · `users.can_vote` | Entitlement / **Demo** / **Legacy** | demo line neutral on slug routes (slug branch also true) | — | **D demo line (R1)** · R rest | `PreVotingGate` | R1 / R3 |
| 709–872 | `getOrCreateCode`: unscoped lookup · **demo re-vote reset (720–756, resets `has_voted`)** · create + notify + logs (**799 code**, 783/790 e-mail) · resend path `>` window, resets `can_vote_now` (**863 new_code**) · unused `$voteNotSubmitted`; inconsistent `restart_after_use` reason | Code Lifecycle / **Demo** / Audit | | VE-06, F-C15 | **D demo block (R1)** · **R log values (R1)** · M rest | `IssueVotingCode` / `RegenerateVotingCode` | R1 / R4 |
| 874–908 | `verifyCode`: usable · `!==` match (**885–886 expected/submitted logged**) · expiry `>` · **IP count** | Code Lifecycle + IP | not constant-time; mixes IP policy | VE-06, F-C6, C1 | **R log values (R1)** · M split | `VerifyVotingCode` + Network policy | R1 / R3 / R4 |
| 910–935 | `markCodeAsVerified`: `can_vote_now = true`, two-code flag `=== true` vs env string | Code Lifecycle | strict mode never active | F-C4 | M (+ decision) | `VerifyVotingCode` | R4 |
| 937–943 | `advanceSlugStep` → legacy progress | Legacy | — | — | D | — | R5 |
| 945–1012 | `handleAlreadyVerified`: records step 1 if missing, routes by highest step; non-slug branch to undefined route | Voting Journey | — | F-C14 | M | VotingJourney | R5 |
| 1014–1042 | `generateCode` / `generateUniqueCodeForOrganisation`: SELECT-then-INSERT uniqueness; **fallback `generateCode().Str::random(4)` = 12 chars (fails `size:8` validation) and is taken whenever `attempts` reaches 10, even if the 10th attempt was unique** | Code Lifecycle | | new (low) | R | DB-enforced unique (org, code) + retry | R4 |
| 1044–1080 | `redirectToDashboard`, `handleValidationError`, `jsonOrRedirect` | HTTP | — | — | K | presenter | R7 |
| 1–19 | imports `DB`, `Hash`, `VotingServiceFactory`, `Validator`, `Carbon` unused | hygiene | — | — | D | — | R7 |

## 4. VoterSlug lifecycle trace (amendment 4 — discovery only)

| Aspect | Finding [S] |
|---|---|
| **CodeController creates slugs?** | **No.** |
| Creation (real path) | `ElectionVotingController::start()` (POST `/elections/{slug}/start`, 105–213): reuses / refreshes an existing slug or `VoterSlug::create(…)` (194), then redirects to `slug.code.create` |
| Creation (service) | `VoterSlugService::generateSlugForUser()` (32, `VoterSlug::create` 79) and `getOrCreateSlug()` (262; returns `VoterSlug\|DemoVoter`) — called by `VoterSlugController` (139, 252), `ElectionManagementController` (421, 643 — demo start), `ElectionController` (129 `storeElection` — unrouted; 236) |
| Resolution | middleware `VerifyVoterSlug` (95) and `EnsureVoterSlugWindow` (109) set the `voter_slug` request attribute; `VerifyVoterSlugConsistency` sets `election` from it |
| Lifecycle mutation in CodeController | `create()` 163–169 only (expiry extension, `is_active`, `can_vote_now`, `status`) — plus step records via `VoterStepTrackingService` |
| Owning authority | none explicit — creation is split between `ElectionVotingController` (inline) and `VoterSlugService`; target: one owner for slug provisioning (R5 / later), **not** CodeController; no new service in R1 |
| Observation | `app/Http/Middleware/VoterSlugStep.php` contains `test_…` methods that create slugs (433–494) — test code inside `app/` (hygiene, out of scope) |

## 5. Defect / legacy classification

| Item | Class | Owner of the fix |
|---|---|---|
| Demo re-vote reset resets consumed participation (720–756) | **DEFECT (real path) — R1** | R1 |
| Demo session bypass in `isUserEligible` (686) | DEMO / LEGACY — R1 | R1 |
| `real &&` participation guards (108, 285, 428, 558) | DEMO coupling — R1 | R1 |
| Plaintext code in logs (181, 245, 799, 863, 885–886; 745 inside demo block) | **DEFECT (VE-06) — R1** | R1 |
| Demo election reaches real routes (no reverse type check; `DashboardResolver` without type filter) | **DEFECT** — boundary in R1, routing root cause = follow-up item | R1 + follow-up |
| Slug presence as eligibility | LEGACY | R3 |
| `users.can_vote` branch | LEGACY (dead on slug routes) | R3 / Phase E |
| `can_vote_now` preserved on regeneration; short-circuit before value check | DEFECT (PBDIGIT-231 / VE-03) — decision | R4 |
| 30-minute value = code lifetime = slug window | LEGACY overload | R3 / R4 |
| IP count inside `verifyCode`, `?? 7` fallback | CURRENT BEHAVIOUR (C1, F-C7) | R3 |
| Two workflow trackers; step repair on GET | LEGACY / DEFECT | R5 |
| Code uniqueness fallback (12 chars) | DEFECT (low) | R4 |
| `voting_started_at` overwritten on re-post | DEFECT (low, F-C10) | R6 |
| Code shown on page without valid e-mail (218) | CURRENT BEHAVIOUR — decision (F-C5) | R4 |
| `$request->all()` and e-mail logging | DEFECT (low) — follow-up item | later |
| `VoteEligibility` demo-session lifecycle skip | DEFECT (F-C2, middleware) — follow-up item | later |

## 6. Target ownership mapping (R2–R7, not authorised)

| Phase | Capability | Takes over |
|---|---|---|
| R2 | `VotingRequestContext` (User · Election · VoterSlug?) | `getUser`, `getElection`, attribute reads, session tenant write |
| R3 | `PreVotingGate` → `PreVotingDecision` (reasons) — **application-level orchestrator** over Membership · VotingPeriod · Participation · Identity · Network policies; `VotingEntitlement` stays the authority (§2a) | trait membership, `isUserEligible`, `has_voted` guards, IP count, lifecycle |
| R4 | `IssueVotingCode` · `VerifyVotingCode` · `RegenerateVotingCode` + **VotingCode semantic API** (target, no migration): `codes.can_vote_now` → `Code::isVerified()` / `Code::markAsVerified()`; `codes.has_voted` → `Code::hasVoted()` / `Code::markParticipationConsumed()` — no direct flag access in use cases; a column rename (`can_vote_now → is_verified`) is a separate decision | `getOrCreateCode`, regeneration in `create`, `verifyCode` (code part), `markCodeAsVerified`, generation + DB uniqueness, notification adapter |
| R5 | VotingJourney | step tracking, `advanceSlugStep`/legacy progress (retire), `handleAlreadyVerified`, slug lifecycle mutation, GET repair |
| R6 | `AcceptVotingAgreement` | agreement record + audit |
| R7 | thin CodeController (context → use case → HTTP) | presenters, dead code, imports |

Rejected (both suggestions): `PrecheckController`, `CodeManager` / `VoterManager`, authorization in VoterSlug, IP policy in Code, ballot validation in CodeController, simultaneous VoteController refactor.

## 7. R1 readiness

- **Boundary before all Code / session / domain state changes confirmed:** `getElection()` precedes Code reads/writes, session mutation (66), code generation, notification and step recording in `create` (54), `store` (231), `showAgreement` (406), `submitAgreement` (528). **`submitAgreement()` still emits a pre-boundary request log (520–525, `$request->all()`)**, separately addressed by the sensitive-logging follow-up.
- **R1 invariants (asserted on database state, not only HTTP):** demo election on any of the four routes → 403 **and** `codes` count unchanged **and** no Code row modified **and** no voter progress / step record written · a code with `has_voted = true` → request rejected **and** `has_voted` remains true · the plaintext code value never occurs in any log message or context (create, correct verify, wrong verify, regeneration).
- **Reachability confirmed:** a demo election passes `VerifyVoterSlugConsistency` (only *demo route + real election* refused) and `ensure.election.voter` (demo bypass) → R1's `abort(403)` is the first refusal on this path.
- **Amendment 2 respected:** R1 touches only the six approved items; `isUserEligible`'s slug and `users.can_vote` branches, `can_vote_now`, regeneration, IP and step tracking stay.

## 8. Evidence

**Characterization (new, HEAD):** `CodeControllerRealElectionCharacterizationTest` — **13 passed, 42 assertions** (reported "deprecated" only because of the pre-existing `PDO::MYSQL_ATTR_SSL_CA` notice). No test/fixture correction was needed; no production change.

**Baseline (HEAD, sequential, no overlap):** see §8.1 (filled from the run).

### 8.1 Baseline results

Run at HEAD `ac5ed44fe`, one file at a time (no overlap), 46 files. Per-test status kept for the R1 before/after comparison (session scratchpad `r0_baseline_head.txt`). "Deprecated" = passed with the pre-existing PDO notice.

**Totals: 177 passed · 166 failed · 24 skipped/incomplete** (passed includes the 13 new characterization tests). **Failures are pre-existing and stay baseline**; causes not investigated in R0 (many report 0 assertions = setup / fixture errors in older suites).

| Suite group | Passed | Failed | Skipped / incomplete |
|---|---|---|---|
| Code step & entitlement pins: `CodeRegenerationCharacterizationTest`, `VotingProcessEntitlementBoundaryTest`, `LegacyVotingFlagsBoundaryTest` | 52 | 0 | 0 |
| Older code-step suites: `CodeControllerTest` (6/6), `CodeControllerSlugRedirectTest` (0/4), `ExpiredCodeAutoRegenerationTest` (2/4), `ElectionOnlyEntitlementPinTest` (0/6), `ElectionShowControllerTest` (7/5), `VotingMembershipIntegrationTest` (0/6), `VoterWorkflowIntegrationTest` (0/6), `VoterStepWorkflowTest` (0/7), `PreventMultipleVotingTest` (0/9), `MultipleVotePreventionTest` (0/5) | 15 | 58 | 0 |
| `tests/Feature/Demo/*` (20 files) | 42 | 81 | 5 |
| `tests/Feature/Voting/*` (13 files, incl. new characterization; VE-01 RED contract 16 and T1 8 failures are intended) | 68 | 27 | 19 |

**R1 rule:** after R1 the same files are re-run; no test passing here may fail, except tests whose change is the approved R1 behaviour (demo election on real code routes → 403; no demo reset) — those are reported individually, not adapted.

## 9. Decisions for the PO (none taken here)

1. Approve the blueprint and the R0 evidence → open R1 (RED first).
2. Later-phase decisions surfaced (not for R1): PBDIGIT-231 regeneration semantics; F-C5 code-on-page; F-C4 two-code mode; one owner for VoterSlug provisioning.
