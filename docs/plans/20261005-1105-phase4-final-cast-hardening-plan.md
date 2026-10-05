# Plan — Phase 4: Final-cast hardening (`forCast()` → `CastDecision`, CAS, fail-closed)

**Status:** 🟡 **REVISION 3 — architecture and scope APPROVED by the PO (2026-10-05); P4-D1…D3 decided; P4-D4 RETIRED (option C); baseline accepted; E1 RED (R1, 4 contracts) authorised; E1 GREEN not authorised.** No production file changed. Nothing committed.
**Decision record:** `docs/publicdigit/reviews/2026-10-05-phase4-final-cast-hardening-po-decision-record.md`
**Stories:** PBDIGIT-230 (no DB guarantee against double voting) · PBDIGIT-232 (security-denial crash) · PBDIGIT-233 (`vote_post_check` ignored / missing view)
**Architecture baseline:** `docs/publicdigit/adr/ADR_20261005_0950_Voting_Authorization_Boundary.md` (APPROVED, frozen) §3.0–3.5, §5 I1–I10, §7, §8 Phase 4
**Roadmap:** `docs/plans/20261005-0647-voting-integrity-anonymity-and-authorization-roadmap-plan.md` (Phase 4)
**Evidence:** `docs/publicdigit/reviews/2026-10-05-phase1-characterization-evidence-report.md` · `tests/Feature/Characterization/Phase1/`

## 0 · PO decisions this plan rests on (2026-10-05)

| Q | Decision | Consequence for Phase 4 |
|---|---|---|
| **Q2** | **Deny** on `Deferred` / `Investigate` | `SecurityReviewRequired` stays a denial reason **for diagnostics only**; there is **no** human review workflow |
| **Q4** | **Demo elections stay excluded** from `forEntry()` / `forCast()` | The demo cast path is behaviourally unchanged; CAS applies to `type = real` only |
| **Q5** | **Option A** — agreement is a UI / process step only | No `AgreementMissing` reason; `has_agreed_to_vote` not read by `forCast()`; not an authorization invariant |
| Q1 | not needed for this phase | the existing Trust → Legitimacy chain is called where it is called today; nothing is renamed or relocated |
| **P4-D1** | **B** | The old `vote_post_check()` IP-density branch is **not** carried into `forCast()`; the Trust → Legitimacy chain is the single IP-density authority on the final cast. `MAX_USE_IP_ADDRESS` may stay relevant to code entry until the later security phase |
| **P4-D2** | **A** | `CodeMissing`, `BallotMissing` = fail-closed process reasons covered by ADR §3.4; recorded in the decision record; **the frozen ADR text is not modified** |
| **P4-D3** | **A** | A missing `Code` → `CodeMissing`, not HTTP 500; **PBDIGIT-219 is resolved by this Phase 4 behaviour** (status changed at E3 GREEN acceptance) |
| ~~**P4-D4**~~ | ~~A~~ → **RETIRED from Phase 4** (PO, 2026-10-05, rev 3 — option C) | **P4-D4 retired from Phase 4.** The historical IP-binding branch was investigated and found unreachable under the current schema because `users.voting_ip` has no persisted database representation. No synthetic in-memory state will be introduced to force execution of this branch. IP-binding behaviour remains outside Phase 4 and is deferred to the later security / IP-binding phase. Recorded as **PBDIGIT-234**. `validateVotingIpWithResponse()` and its call in `store()` are **not changed** by Phase 4. |

**Approved scope:** (1) `forCast()` → `CastDecision`; (2) PBDIGIT-230; (3) PBDIGIT-232; (4) PBDIGIT-233.
**Out of scope (PO, frozen):** A5 · A6 · Model A/B · IP-binding implementation · device binding · `can_vote_now` semantics · persisted authorization/security flags · new security engine · `Vote` anonymity model · `election_memberships.has_voted` · receipt architecture · demo-election behaviour · agreement beyond Q5 = A · migrations / schema changes · the `MAX_USE_IP_ADDRESS` ops task (separate deployment verification).

---

## 1 · Current-state characterization (FACT, `VoteController::store`, `app/Http/Controllers/VoteController.php:1463-1917`)

Order today, all inside one `DB::beginTransaction()`:

| # | Step | Line | Today's defect |
|---|---|---|---|
| 1 | Trust chain: `TrustPolicyEvaluator::evaluate()` → `ConstitutionalLegitimacyDecision::decide()` → `LegitimacyOutcome` (real only enforced) | 1492-1556 | **D3 / PBDIGIT-232:** on denial calls `trackSovereigntyDivergence(..., ?int $userId)` with a UUID → `TypeError` (an `Error`, not `Exception`) → **HTTP 500** before its own rollback |
| 2 | Lifecycle `ElectionLifecycle::of()->canVote()` | 1559-1583 | same `TypeError` path (1563) |
| 3 | Fresh entitlement `ensureVoterMembership(..., useCache: false, inTransaction: true)` → `EloquentVotingEntitlementQuery::standingOf()` (demo bypassed) | 1586-1598 | same `TypeError` path (1588) |
| 4 | Load `Code` / `DemoCode` | 1628-1637 | `$code` may be null → `$code->fresh()` fatal (PBDIGIT-219, see P4-D3) |
| 5 | `Cache::lock(voter_slug_transition:{slug}, 10)` | 1640-1649 | only concurrency guard (PBDIGIT-230) |
| 6 | Re-read `has_voted` (real only) | 1652-1666 | plain read, not atomic |
| 7 | `validateVotingIpWithResponse()` (user.voting_ip binding) | 1670-1673 | would return without rollback / lock release — **but unreachable**: `users.voting_ip` has no column (no migration creates it), so the attribute is always `null` and the helper always skips (rev 3 finding; P4-D4 retired; PBDIGIT-234) |
| 8 | Validate + verify submitted `voting_code` | 1685-1714 | — |
| 9 | `vote_post_check()` | 1726 | **D1:** result never read · **D10:** every error branch renders `view('components.error_message')`, which does not exist → throws · its IP branch compares against `config('app.max_use_clientIP')` (no default) |
| 10 | `save_vote()` — **INSERT vote** | 1761 | insert happens **before** participation is consumed |
| 11 | Step-5 record (stores `vote_id` — A6, unchanged) | 1770-1787 | — |
| 12 | `markUserAsVoted()` — `has_voted = true`, `can_vote_now = false`, `is_code_to_save_vote_usable = false`, `code_to_save_vote_used_at = now()` | 1790 | consumption after insert, unconditional update |
| 13 | slug `has_voted`/`status`, receipt, notification, `COMMIT`, lock release, audit | 1793-1862 | — |

Security IP checks on the cast today: **three in code** — (a) the trust chain's participation density (`NetworkBindingPolicy`: `votesFromThisIp <= election.max_votes_per_ip`, default 6); (b) `vote_post_check` density (`>= config('app.max_use_clientIP')`); (c) `validateVotingIpWithResponse()` binding — **(c) is unreachable** (no `users.voting_ip` column; the trust chain's `registeredIpHash` input is therefore always `null` too). ADR I3 says one security authority.

Phase 1 tests that currently assert the defects (they **will change** under GREEN — §6):
`FinalDenialCharacterizationTest::test_g2_known_gap_the_security_denial_branch_ends_in_an_uncaught_error`, `::test_i_known_gap_the_pre_check_result_is_never_read_by_store`, `::test_i_a_failing_pre_check_condition_still_blocks_through_a_missing_view_exception`.

---

## 2 · Target design (ADR §3.2–3.4, no new architecture)

```
BEGIN
  [real] trust chain (unchanged call) ──► LegitimacyOutcome  (fact passed to forCast)
  load code (in transaction)
  [real] forCast(CastAttempt) ──► CastDecision { reason }         ← pure composition, no state, no I/O except the entitlement port
           1. entitlement   VotingEntitlementQuery::decide()  (fresh, uncached adapter: standing ∧ lifecycle)
           2. security      LegitimacyOutcome → Allowed | SecurityDenied | SecurityReviewRequired (Q2)
           3. process       CodeMissing · CodeMismatch · CodeStepIncomplete · BallotMissing · AlreadyVotedPreCheck (non-authoritative)
         reason ≠ Allowed ─► fail-closed exit (§2.0) · audited voter-side reason · user message (no 500)
  [real] CAS  UPDATE codes SET has_voted = true WHERE id = :id AND has_voted = false
         affected ≠ 1 ─► fail-closed exit (§2.0) · "already voted" (the only authoritative already-voted)
  INSERT anonymous vote (save_vote, unchanged)
  existing post-insert writes unchanged (step 5, markUserAsVoted, slug, receipt, notification)
COMMIT
any \Throwable ─► fail-closed exit (§2.0) · generic reason (no 500)
[demo] today's path, unchanged (Q4)
```

### 2.0 Transaction and lock ownership rule (PO correction, rev 2)

> **Roll back only when a transaction is active, and release the cache lock only when this execution path actually acquired the lock.**

- The fail-closed exit checks `DB::transactionLevel() > 0` before `rollBack()` (some branches — e.g. `ensureVoterMembership(inTransaction: true)` — already rolled back).
- Lock ownership is an explicit local fact: `$lockAcquired = $lock->get()`; only `$lockAcquired === true` leads to `release()`. A lock that was refused (another submission holds it) is **never** released by this path; nor is a lock object that exists but was never acquired.
- Failures that occur **before** the lock is taken (trust/security denial, lifecycle, entitlement, missing code) roll back and release **nothing**.
- Failures **after** acquisition (code mismatch, `forCast` denial, CAS = 0, any `\Throwable`) roll back and release exactly once. *(The IP-binding exit is unreachable and not changed — P4-D4 retired.)*
- One private exit helper in `store()` applies the rule; every early return uses it (no re-implementation per branch).

### 2.1 New code (Elections context, Application layer — framework-free, final)

| File | Kind | Content |
|---|---|---|
| `app/Contexts/Elections/Application/Services/VotingAuthorization.php` | application service | `forCast(CastAttempt): CastDecision` only (`forEntry()` is Phase 5). Depends on the `VotingEntitlementQuery` **port** only. No facade, no Eloquent. |
| `app/Contexts/Elections/Application/DTO/CastAttempt.php` | readonly DTO | `voterId`, `electionId`, `SecurityVerdict $security`, `bool $codeExists`, `bool $submittedCodeMatches`, `bool $codeStepCompleted` (= `can_vote_now`, read only), `bool $alreadyVotedPreCheck` (= `has_voted`, read only), `bool $ballotPresent` |
| `app/Contexts/Elections/Application/DTO/CastDecision.php` | readonly VO | `CastDecisionReason $reason`; `isAllowed()` |
| `app/Contexts/Elections/Domain/ValueObjects/CastDecisionReason.php` | enum | `Allowed` · forwarded entitlement reasons (`NotRegistered`, `NotVoterRole`, `OrganisationMismatch`, `NotActive`, `NotAdmitted`, `ElectionNotVotable`) · `SecurityDenied` · `SecurityReviewRequired` · `CodeMissing` · `CodeMismatch` · `CodeStepIncomplete` · `BallotMissing` · `AlreadyVotedPreCheck` |
| `app/Contexts/Elections/Domain/ValueObjects/SecurityVerdict.php` | enum | `Allowed` · `Denied` · `ReviewRequired` — the controller maps the existing `LegitimacyOutcome` onto it (mapping only; not a decision, not an engine) |

Evaluation order (first failure wins, ADR §3.1/3.2): entitlement → security → process; process order **CodeMissing → AlreadyVotedPreCheck → CodeMismatch → CodeStepIncomplete → BallotMissing** — an E2 contract (decision record E2-D1). Entitlement via `decide()` exactly once, never `standingOf()` (E2-D2). Exactly 14 reasons (E2-D3). No security sub-reason on the decision (E2-D4, option A). R2a deferred (E2-D6). *Today the trust gate runs first; only the **reported reason** for a voter failing both changes, never allow/deny.*

### 2.2 Production edit groups (each its own RED → GREEN → STOP)

| Group | Story | Files | Change |
|---|---|---|---|
| **E1** | 232 | `VoteController.php` (`trackSovereigntyDivergence` signature; `store()` exits and catch) | `?int $userId` → `?string $userId`; `catch (\Exception)` → `catch (\Throwable)`; the §2.0 ownership rule as one exit helper used by every early return **except** the unreachable `validateVotingIpWithResponse()` branch, which stays untouched (P4-D4 retired). Independent of E2–E4. |
| **E2** | — (`forCast`) | new files §2.1 only | domain/application pieces, unit-tested in isolation; no caller yet |
| **E3** | 233 + 219 + `forCast` | `VoteController.php::store` | real path: gather `CastAttempt` facts in the transaction, call `forCast()`, map reasons to `back()->withErrors()`; missing `Code` → `CodeMissing` (resolves PBDIGIT-219); **stop calling `vote_post_check()` from `store()`** (its process conditions now live in `forCast()`; its IP branch dropped per P4-D1). `vote_post_check()` itself and the other controllers' copies (Demo, Deligate) **untouched**. |
| **E4** | 230 | `VoteController.php::store` | CAS before `save_vote()` (real only); remove the now-redundant plain re-read; keep the cache lock as a UX guard (not authority); `markUserAsVoted()` unchanged (its `has_voted = true` becomes an idempotent re-write; A2 timestamp unchanged in value and timing class). |

No migration. No schema change. No change to `Code` / `Vote` models, `markUserAsVoted`, `save_vote`, receipts, step-5 record, logs' content, demo controllers, `CodeController`, middleware.

---

## 3 · Invariants enforced by this phase

| ID | Statement | Test group |
|---|---|---|
| I1 | Only `Allowed` lets the cast proceed; commit only on `Allowed` ∧ CAS = 1 row ∧ insert ∧ commit | R3, R4 |
| I4 | `forCast()` is evaluated in the cast transaction, uncached | R2 (unit), R3 (structural) |
| I5 | `can_vote_now`, `has_voted` are read as facts only; `forCast()` writes nothing | R2, R5 |
| I6 | exactly one `has_voted` false→true per committed real cast, before the insert | R4 |
| I7 | any denial / `\Throwable` writes no vote and leaves `has_voted` false; lock released; no 500 | R1, R3 |
| I8 | no decision reason is logged together with a ballot-side identifier | R5 |
| I10 | no `Vote` change, no voter-derived value in the ballot | R5 |
| Q4 | demo cast behaviour identical before/after | R6 |

---

## 4 · RED test groups and exact contracts

| Group | File (new unless noted) | Contracts |
|---|---|---|
| **R1** (E1) | `tests/Feature/Voting/CastFailClosedTest.php` — **4 contracts (rev 3)** | **R1-A** security denial before the lock while **another holder owns the slug lock** → redirect with a `vote` error, **not 500**; no vote; `has_voted` false; that lock **still held** and still owned by the other holder (RED today — PBDIGIT-232) · **R1-B** refused acquisition ("being processed") → the existing holder's lock still held and owned (guard — passes today) · **R1-C** post-acquisition denial (wrong code) → this path's own lock released (guard — passes today) · **R1-D** an `\Error` (not `\Exception`) after acquisition → rollback, generic error, no 500, no vote, no receipt, lock released (RED today) · **transaction:** after every exit `DB::transactionLevel()` equals the test's own baseline · ~~IP-binding exit~~ removed (P4-D4 retired; unreachable; PBDIGIT-234) |
| **R2** (E2) | `tests/Unit/Contexts/Elections/VotingAuthorizationForCastTest.php` | each entitlement reason forwarded 1:1; `SecurityVerdict::Denied` → `SecurityDenied`; `ReviewRequired` → `SecurityReviewRequired` (Q2); each process fact → its reason; first-failure order entitlement → security → process; all true → `Allowed`; `alreadyVotedPreCheck` → `AlreadyVotedPreCheck`; **no `AgreementMissing` case exists** (Q5 = A); `forCast` uses `decide()` (lifecycle included); spy port called exactly once |
| **R2a** | `tests/Architecture/VotingAuthorizationArchitectureTest.php` | `VotingAuthorization`, `CastAttempt`, `CastDecision`, `CastDecisionReason`, `SecurityVerdict` framework-free (no `Illuminate\`, no facades, no `App\Models`); final; no persistence call; no class named `*SecurityEngine*` / no second security evaluator introduced |
| **R3** (E3) | `tests/Feature/Voting/CastDecisionEnforcementTest.php` | real cast: unverified voter under admission policy → `NotAdmitted` error, no vote; election not votable → error; missing session ballot → `BallotMissing` error **(no view exception)**; wrong code → `CodeMismatch` (existing message kept); code step incomplete (`can_vote_now = 0`) → `CodeStepIncomplete`; missing code row → `CodeMissing` error, not 500 (P4-D3); all paths: no vote, `has_voted` false; structural: `store()` no longer calls `vote_post_check`, calls `VotingAuthorization::forCast` |
| **R4** (E4) | `tests/Feature/Voting/AtomicParticipationConsumptionTest.php` | successful real cast: exactly one `has_voted` false→true and exactly one vote; **consumption guarantee** (§5 a–c): the CAS statement returns 1 then 0 on the same row; with the cache lock store flushed and `has_voted` already true → rejected, **no vote inserted**, transaction rolled back; aggregate: votes = consumed codes after N successful + M refused casts; cache lock absent (lock store flushed) still yields one vote; structural: the CAS statement precedes `save_vote` |
| **R5** (anonymity) | `tests/Feature/Voting/CastAnonymityBoundaryTest.php` | `votes` columns unchanged vs a frozen list; no new column on `codes`; denial logs carry no `vote_id`/receipt; `CastDecision` / reason never persisted (no table/column named like `cast_decision`/`authorization`); no new voter-side timestamp written by the CAS (only `has_voted`) |
| **R6** (Q4) | `tests/Feature/Voting/DemoCastUnchangedTest.php` | demo cast succeeds as today; demo re-cast behaviour as today (characterize first, then freeze); `forCast` **not** invoked for demo (spy) |

Expected RED: R1-A and R1-D (500 today; R1-B and R1-C are ownership guards that pass today), R2/R2a (classes absent), R3 (reasons absent / view exception / 500), R4 race case (insert happens; no CAS), R6 spy (class absent). R5 and parts of R4/R6 may already pass — reported honestly as guards, not forced RED.

Phase 1 characterization tests whose assertions describe the defect (`test_g2_known_gap…`, `test_i_known_gap…`, `test_i_a_failing_pre_check…`) are **retired in the same GREEN group** that fixes them and replaced by the R1/R3 contracts — listed explicitly in each GREEN report, never edited silently.

---

## 5 · Transaction / concurrency design

- **One transaction** (existing). `forCast()` runs inside it (I4); entitlement via the uncached adapter (`EloquentVotingEntitlementQuery`, as `ensureVoterMembership(useCache:false)` does today).
- **CAS:** `Code::withoutGlobalScopes()->whereKey($code->id)->where('has_voted', false)->update(['has_voted' => true])` → affected rows. PostgreSQL row-level write lock makes a concurrent CAS block until commit, then re-evaluate `has_voted = false` → 0 rows. That is the authoritative guarantee; it does not depend on the cache.
- **Order:** CAS **before** `save_vote()` (PO-frozen). On 0 rows → rollback, "already voted".
- **Cache lock:** kept (prevents a double click from even reaching the DB; UX), **not** authority. Released only by the path that acquired it (§2.0).
- **What is and is not proven:** the tests prove the **database primitive** (conditional update: 1 row, then 0) and the **production ordering** (CAS before `save_vote()`). **A real parallel race is not reproduced** by the single-process PHPUnit environment; no report may claim otherwise. The guarantee under true concurrency rests on PostgreSQL row-level locking semantics for a single-row conditional `UPDATE`, which is reasoned, not tested.
- **Race test technique:** no production seam is added for tests. A true parallel race is not reproducible in a single-process PHPUnit run (Phase 1 finding). R4 therefore proves the guarantee in three parts: (a) a DB-level test of the CAS statement itself — two CAS on the same row → 1, then 0; (b) an end-to-end cast with the cache lock store flushed and `has_voted` already true on the row → rejected, no vote; (c) a structural assertion that the CAS precedes `save_vote`. Note: in (b) the non-authoritative `AlreadyVotedPreCheck` refuses first, so the CAS's own contribution is proven by (a) + (c) only. This limit is stated in the RED/GREEN reports, not hidden.
- **Isolation level:** default READ COMMITTED is sufficient for a single-row conditional UPDATE; no `SERIALIZABLE`, no `lockForUpdate` needed.

## 6 · Regression strategy

1. Before RED: baseline the cast-related suites at HEAD — `tests/Feature/Voting/*`, `tests/Feature/Characterization/Phase1/*`, `VotingProcessEntitlementBoundaryTest`, `IdentityVerificationAdmissionTest`, `SuspensionRevalidationTest`, `VoterAdmissionProductFlowTest`, `tests/Feature/Trust/*`, demo voting suites.
2. After each GREEN group: same set, per-test diff vs baseline; every changed result explained (expected flip, or STOP).
3. At close: full before/after run against the HEAD-at-approval worktree (probe-proven method, as for C4); Vitest unaffected (no frontend change) — run once for confirmation.

## 7 · Anonymity-boundary checks (interim 7-point rule, per edit group)

| Group | 1 votes field | 2 joinable time | 3 voter+ballot log | 4 receipt/notification | 5 derivable | 6 relation | 7 A5/A6 | Verdict |
|---|---|---|---|---|---|---|---|---|
| E1 | ✓ | ✓ | ✓ (denial logs unchanged; no vote id exists on denial) | ✓ | ✓ | ✓ | ✓ | passes |
| E2 | ✓ | ✓ | ✓ (no logging inside) | ✓ | ✓ | ✓ | ✓ | passes |
| E3 | ✓ | ✓ | ✓ **only if** reason logs carry no vote id (no vote exists before insert) | ✓ | ✓ | ✓ | ✓ | passes, with I8 (R5) |
| E4 | ✓ | ✓ CAS writes only `has_voted`; `code_to_save_vote_used_at` written as today by `markUserAsVoted` (A2 unchanged) | ✓ | ✓ | ✓ | ✓ | ✓ | passes, with constraint (R5) |

## 8 · Rollback considerations

- No migration, no data change → code-only revert of each group's commit restores previous behaviour exactly.
- Groups are independently revertible in reverse order (E4 → E3 → E2 → E1). E1 alone is a safe hotfix.
- Behavioural risk on deploy: votes that previously ended in a 500 now end in a reasoned denial (intended); with P4-D1 option B, the config limit `MAX_USE_IP_ADDRESS` no longer affects the final cast.
- No persisted state is introduced, so no clean-up is ever needed after a revert.

---

## 9 · Decisions before RED (P4-D) — ✅ DECIDED by the PO 2026-10-05: D1 = B · D2 = A · D3 = A · D4 = A (see §0 and the decision record; options kept below for traceability)

| ID | Question | Options | Recommendation (rev 1) |
|---|---|---|---|
| **P4-D1** | `vote_post_check`'s **IP-density branch** (config `max_use_clientIP`, `>=`) duplicates the trust chain's density (`max_votes_per_ip`, `<=`) | **A** enforce it as a second check (contradicts I3; keeps `MAX_USE_IP_ADDRESS` relevant) · **B** do not carry it into `forCast()`; the trust chain is the one density authority on the cast (I3) · **C** carry it as a transitional `SecurityDenied` sub-reason until Phase 6 | **B** — I3; today this branch only ever *crashes* (D10), so it has never produced a reasoned denial. The behaviour change is that the config limit stops applying at the final cast (code entry still uses it — Phase 5). |
| **P4-D2** | Reason vocabulary: `CodeMissing` and `BallotMissing` are not listed in ADR §3.3 | **A** treat as covered by §3.4 ("any missing input → reason") · **B** a recorded ADR revision act first | **A**, recorded in the decision record — they are process reasons for missing inputs, not new authorization concepts |
| **P4-D3** | A missing `Code` row → today a 500 (= **PBDIGIT-219**) | **A** handled as `CodeMissing` here (fail-closed requires it) and PBDIGIT-219 closed by this phase · **B** keep PBDIGIT-219 separate; Phase 4 tests avoid the case | **A** — §3.4 makes it unavoidable; story pointer added to 219 |
| **P4-D4** | `validateVotingIpWithResponse()` (IP binding) exits without rollback / lock release | **A** fail-closed hygiene only: rollback + release before returning, decision and page unchanged · **B** leave untouched (IP binding is out of scope) | **A** — no IP-binding semantics change, only the I7 exit discipline; if the PO reads "IP binding out of scope" strictly, **B** and record a backlog item |

## 10 · Task checklist

- [x] PO approval of architecture and scope + P4-D1…D4 (2026-10-05, rev 2)
- [x] PO approval to start E1 RED
- [x] Baseline regression run (§6.1) — `d4d6b2890`, accepted
- [x] P4-D4 retired (option C), PBDIGIT-234 recorded
- [x] E1: R1 RED → report → STOP · GREEN → report → STOP — **accepted, committed `7aa33df68`**; PBDIGIT-232 RESOLVED; guide `developer_guide/election/final_cast/01_e1_fail_closed_exit.md`
- [x] E2: R2 RED → STOP · GREEN → STOP — **accepted**; RED `f84e72ece`, GREEN committed with guide `developer_guide/election/final_cast/02_e2_cast_decision.md`; R2a deferred (E2-D6)
- [x] E3: R3 RED → STOP · GREEN → STOP — **accepted**; RED `a818f2514`, GREEN committed with guide `developer_guide/election/final_cast/03_e3_cast_enforcement.md`; PBDIGIT-233 resolved for the final cast, PBDIGIT-219 resolved
- [ ] E4: R4 (+R5, R6) RED → STOP · GREEN → STOP
- [ ] Full regression vs worktree; developer guide `developer_guide/voting/` (cast decision + CAS); decision record (Q2/Q4/Q5 + P4-D); stories 230/232/233 (+219) status; session log; CONTEXT-publicdigit
- [ ] Commit per approved group (story IDs in subject), only after PO review

## 11 · Risks

- **R-a** Race test may need a seam → mitigated per §5 without production test hooks; honest fallback stated.
- **R-b** Phase 1 characterization flips could mask an unrelated regression → each flip named in the GREEN report.
- **R-c** `store()` is 450 lines with mixed concerns → edits are confined to the listed steps; no refactor beyond them (ER-08).
- **R-d** Reason-order change (entitlement before security) alters the *message* for voters failing both → accepted per ADR order; covered by R2.

## Progress
- 2026-10-05: plan drafted from code reading (no production edits).
- 2026-10-05 (rev 2): PO approved architecture and scope; P4-D1 = B, D2 = A, D3 = A, D4 = A; correction §2.0 (transaction / lock ownership) added with R1 tests; CAS proof wording sharpened (§5). Story-status changes planned (§12), none applied yet.

- 2026-10-05 (rev 3): baseline §6.1 run at `d4d6b2890` (29 files, accepted by the PO; pre-existing failures stay baseline failures and are not fixed in Phase 4). R1 first run: R1-A ⨯ (500), R1-B ✓, R1-C ✓, IP-binding test invalid (`users.voting_ip` column missing), R1-D ⨯ (500). PO chose C: P4-D4 retired, contract #4 removed, PBDIGIT-234 recorded.

- 2026-10-05 (E1): R1 RED → GREEN 4/4 (`VoteController`: `?string $userId`, `catch (\Throwable)`, `failCastClosed()`); regression vs `d4d6b2890`: 0 previously passing tests fail (Phase 1 G2 known-gap test retired, replaced by R1-A); PO accepted; committed `7aa33df68`. Side effect recorded: a missing `Code` now yields the generic error instead of 500 (PBDIGIT-219 still resolved only at E3).

- 2026-10-05 (E2 RED): `tests/Unit/Contexts/Elections/VotingAuthorizationForCastTest.php` — 16/16 RED (E2 classes absent); PO accepted; decisions E2-D1…D6 recorded (process order, `decide()` once, 14 reasons, no security sub-reason, placement, R2a deferred).
- 2026-10-05 (E2 GREEN): five new framework-free classes (`VotingAuthorization`, `CastAttempt`, `CastDecision`, `CastDecisionReason`, `SecurityVerdict`); R2 16/16 GREEN (53 assertions) with the test unchanged; Elections unit suite 62/62 and 13 scanning architecture tests green; no caller yet (E3). PO accepted.
- 2026-10-05 (E3 RED): `tests/Feature/Voting/CastDecisionEnforcementTest.php` — R3-1 BallotMissing, R3-2 CodeStepIncomplete, R3-3 CodeMissing RED; R3-4 CodeMismatch passes today; R3-5, R3-7 labelled GUARD (R3-7 fails until GREEN); R3-6 (`MAX_USE_IP_ADDRESS`) removed — outside E3 (PO). Findings: **the final cast accepts a code whose code step was never completed** (R3-2, fixed by E3 GREEN within the approved plan); **a soft-deleted Code is still usable** (`withoutGlobalScopes()` lifts `SoftDeletingScope`) → **PBDIGIT-235**, option B, out of E3. Phase 1 characterization tests not retired in RED.
- 2026-10-05 (E3 GREEN): `store()` asks `forCast()` in the transaction for real elections (uncached entitlement; security verdict from the existing chain; `refuseCast()` reasoned refusals, existing messages kept; `validateVotingCodeInput()` keeps the input-validation rules); `vote_post_check()` demo-only (**option A**, PO); R3-7 narrowed to "not invoked on the real-election path" (PO). R3 6/6, R1 4/4, E2 16/16, Phase 1 26/26 after retiring `test_i_a_failing_pre_check_…` (PO; replaced by R3-1). Regression vs `d4d6b2890`: 0 previously passing tests fail. PO accepted.

## 12 · Story-status changes (planned — applied only at the GREEN acceptance of the named group)

| Story | Change | When |
|---|---|---|
| PBDIGIT-232 | OPEN → resolved | E1 GREEN accepted |
| PBDIGIT-233 | OPEN → resolved for the cast path (D1, D10 on `store()`); the `MAX_USE_IP_ADDRESS` ops item and `vote_post_check` copies in Demo/Deligate controllers stay recorded as remaining | E3 GREEN accepted |
| **PBDIGIT-219** | OPEN → **resolved by Phase 4 behaviour** (missing `Code` → `CodeMissing`, not 500), pointer to this plan | E3 GREEN accepted |
| PBDIGIT-230 | OPEN → resolved (CAS before insert; the parallel-race limit stated) | E4 GREEN accepted |
| PBDIGIT-234 (new) | created OPEN — not authorised: `users.voting_ip` has no column, so IP binding (`validateVotingIpWithResponse`, the trust chain's `registeredIpHash`, and other readers) is unreachable; deferred to the security / IP-binding phase | rev 3 (now) |
| PBDIGIT-235 (new) | created OPEN — not authorised: soft-deleted `Code` still usable at the final cast; semantics decision required | E3 RED (now) |
| PBDIGIT-231, 223–229 | unchanged | — |

## Next actions
E4 RED (R4 + R5, R6) → RED report → STOP; E4 GREEN only on separate approval.
