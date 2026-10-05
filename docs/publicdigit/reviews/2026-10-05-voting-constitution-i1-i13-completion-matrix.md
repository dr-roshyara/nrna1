# Voting constitution — I1–I13 completion matrix (evidence map)

**Date:** 2026-10-05 · **Status:** DRAFT for PO review (review gate) · **Placement:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)
**Source of the invariants:** `docs/publicdigit/adr/ADR_20261005_0950_Voting_Authorization_Boundary.md` §5, revision 3 (applied `22c3e5f40`) — statements quoted verbatim
**Repository state:** HEAD `5cbba6f15` · **Author:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable) — engineering evidence, not acceptance

## How to read this

- **Evidence labels:** [S] read in source at HEAD · [D] dynamic, a committed test run on synthetic data · [I] inference.
- **Test type:** *unit* (pure class) · *enforcement* (a feature test that drives the real route and asserts the rule holds — passes only because production enforces it) · *characterization* (pins observed behaviour, including known gaps) · *integration* (several components through the real route) · *regression* (the 97-suite status comparison).
- **Enforcement vs observation:** *enforces* = a test fails if the rule is broken on the tested path; *observes* = the test records behaviour without proving the rule holds generally.
- **Scope of any claim:** a passing test proves the rule only on the paths it drives. The 97-suite regression (last run: unchanged from `33bef9f7d`, 579 pass / 334 fail / 19 skipped) shows no observed regression, nothing more.
- **Not resolved or reinterpreted here:** A1, A5, A6, A8, D6, PBDIGIT-242, D4.1, Q3, Q6, Q7. Where an invariant depends on them, they are named only.

## Summary

| Inv. | Short name | Real final cast | Whole voting path | Open human decision |
|---|---|---|---|---|
| I1 | Commit iff Allowed ∧ ValidatedBallot ∧ CAS=1 ∧ exact content ∧ commit | 🟢 enforced + tested | — (final-cast rule) | PBDIGIT-242 |
| I2 | Admission once, in VotingEntitlement | 🟢 | 🟡 transitional gates and unchecked entry paths remain | Q6 |
| I3 | One security authority; no IP / device check outside it | 🟢 final-cast security assessment (PBDIGIT-240) | 🔴 checks #2–#6 outside it | Q3 (→ 2B) |
| I4 | `forCast()` in the cast transaction | 🟢 | — | — |
| I5 | Process facts are never authority | 🟢 in `forCast()` | 🟡 `isUserEligible()` gates on `can_vote_now` | R1-F6a, Q7 |
| I6 | Atomic CAS on `has_voted` before the insert; aggregate cardinality | 🟢 | — | — |
| I7 | Denial or error writes no vote, `has_voted` false | 🟢 every known exit tested | — | — |
| I8 | No auth / security outcome logged with a ballot-side id | 🟡 four contexts fixed and tested | 🔴 known linkages remain | A8, A1 |
| I9 | Security policy immutable from ReadyForVoting | 🟢 for the current four-field boundary (PBDIGIT-244) | 🟡 complete policy surface pending | Q3 |
| I10 | Decisions do not modify the Vote nor add voter-derived values | 🟡 decisions comply; persistence has open items | 🟡 | A5, A6 |
| I11 | Transport data untrusted | 🟢 | 🟡 only the final cast is the trust point | PBDIGIT-242 |
| I12 | Persistence accepts only a ValidatedBallot | 🟢 real path | — (demo out of scope, S2) | — |
| I13 | Auth + ballot validation before the CAS, one CastContext | 🟢 | — | D6 (CastContext region source) |

🟢 enforced and tested on the stated scope · 🟡 partly · 🔴 not met / not implemented

---

## I1 — the commit condition

- **Invariant (verbatim):** "`Allowed` is the only reason that permits entry or a cast attempt; it is never persisted. `CastDecision = Allowed` alone does **not** commit a vote — a vote is committed **if and only if** `CastDecision = Allowed` ∧ a `ValidatedBallot` exists ∧ the CAS changes exactly one participation record ∧ exactly the `ValidatedBallot` content is persisted ∧ the transaction commits (§3.4)."
- **Enforcement point [S]:** `VoteController::store()` (real path), in order: `forCast()` → `BallotValidator` → CAS on `codes.has_voted` → `saveValidatedVote()` → `DB::commit()`. Every refusal goes through `failCastClosed()` or rolls back itself (Exit A). `CastDecision` is never stored (`CastDecisionReason` "derived, never stored", ADR §3.3).
- **Tests [D]:**
  - `tests/Unit/Contexts/Elections/VotingAuthorizationForCastTest.php` — unit (Allowed is the only success).
  - `tests/Feature/Voting/CastDecisionEnforcementTest.php` — enforcement.
  - `tests/Feature/Voting/FinalCastBallotInvariantTest.php` — enforcement (22).
  - `tests/Feature/Voting/AtomicParticipationConsumptionTest.php` — enforcement (R4).
  - `tests/Feature/Voting/CastFailClosedTest.php`, `FinalCastExitSafetyTest.php` — enforcement.
- **Enforces / observes:** enforces each conjunct on the driven paths. No single test asserts the full "if and only if"; the conjunction is covered by the suites together [I].
- **Remaining gap:** a `ValidatedBallot` with zero answers satisfies "a ValidatedBallot exists", so a fully empty ballot commits a participation-only vote [D] (PBDIGIT-242).
- **Human decision:** PBDIGIT-242.
- **Stories / commits:** Phase 4 (PBDIGIT-232 `7aa33df68`; 233/219 `a818f2514` → `8cea53c73`; 230 `2afc6da5e` → `b1daf26a2`) · PBDIGIT-237 `33bef9f7d` · PBDIGIT-243 `da54c03c5` → `5cbba6f15`.

## I2 — admission evaluated once

- **Invariant:** "Admission is evaluated once, inside `VotingEntitlement`; no other component restates it."
- **Enforcement point [S]:** `VotingEntitlementPolicy` (`isAdmitted` from the election's `VoterAdmissionPolicy` + evidence) behind the `VotingEntitlementQuery` port; consumed by `forEntry()` and `forCast()` (fresh `decide()`), by `ensureVoterMembership()` (`standingOf()`), and by the cached middleware.
- **Tests [D]:**
  - `tests/Feature/Election/VotingEntitlementUnificationTest.php` — enforcement (one decision; scope from the election).
  - `tests/Feature/Election/IdentityVerificationAdmissionTest.php`, `SuspensionRevalidationTest.php` — enforcement.
  - `tests/Feature/Election/VotingProcessEntitlementBoundaryTest.php` — characterization of the voting-process boundary (41).
  - `tests/Feature/Election/LegacyVotingFlagsBoundaryTest.php` — characterization of legacy flags.
  - `tests/Unit/Contexts/Elections/VotingAuthorizationForEntryTest.php`, `tests/Feature/Voting/CodeEntryAuthorizationTest.php` — unit + enforcement at the code step.
- **Enforces / observes:** enforces at the code step and at the final cast. Legacy flags and the process boundary are characterized, not removed.
- **Remaining gap [S]:** transitional gates still run beside the entitlement decision — `isUserEligible()` (R3) and the cached middleware (Q6). Entry paths without an entitlement check are open: PBDIGIT-214 (`voter.restart`), 217 (demo session flag skips the lifecycle check), 218 (legacy `POST /votes`).
- **Human decision:** Q6 (middleware); R3 is authorised work, not a decision.
- **Stories / commits:** PBDIGIT-213 (`f97ddae59`, `110387f63`, `622ee91e9`, `9c482db85`) · PBDIGIT-239 (`430d28742`, `d90131361`).

## I3 — one security authority

- **Invariant:** "One security authority; no IP / device check outside it."
- **Enforcement point [S]:** `VotingSecurityVerdictQuery` + `TrustLegitimacySecurityVerdict` (Trust → Legitimacy) — consumed by `forEntry()` at the code step.
- **Tests [D]:** `tests/Feature/Voting/VotingSecurityVerdictAdapterTest.php` — characterization (extraction oracle: the adapter gives the same verdict as the inline chain) · `CodeEntryAuthorizationTest.php` — enforcement at entry.
- **Update 2026-10-05 (PBDIGIT-240):** **I3: one security authority is enforced for the final-cast security assessment.** `VoteController::store()` consumes one `SecurityAssessment` from `VotingSecurityAssessmentQuery::assess()` (same adapter; `verdictFor()` derived); the inline chain is removed. Tests: `tests/Feature/Voting/SecurityAssessmentConsolidationTest.php` P1 / P1b / P2 (enforcement, property + dynamic: one evaluation through the authority), C1–C3 (characterization); `VotingSecurityVerdictAdapterTest` E1 (outcome + verdict equivalence with the frozen oracle). RED `c6490376c`, GREEN (commit carrying this update). **The remaining IP / security checks (#2–#6) remain outside this story and are deferred to Q3 / 2B.**
- **Enforces / observes:** enforces one authority at the code step and the final cast; checks #2–#6 below still evaluate their own rules.
- **Remaining gap [S]:**
  - ~~`VoteController::store()` keeps its own inline Trust → Legitimacy assessment~~ — resolved by PBDIGIT-240.
  - IP checks outside the authority: `CodeController::verifyCode()` IP count (`max_use_clientIP`, default 7); `validateVotingIpWithResponse()` in `store()` (latent, PBDIGIT-234; now fails closed, PBDIGIT-243); `ValidateVotingIp` middleware; `vote_post_check()` — off the final cast (demo-only at `store()`), but **on the real path at step 4**: `VoteController::verify()` calls it and it counts voted codes from the IP against `config('app.max_use_clientIP')` for real elections (corrected 2026-10-05, D-240-4; PBDIGIT-240 proposal §Q5); `ElectionVotingController::resolveIpBlock` (counts `step_1_ip`, never written — VE-15).
- **Human decision:** Q3 (policy boundary → 2B). The PBDIGIT-240 design choice is decided (D-240-1 … 4, `7d948969f`).
- **Stories / commits:** PBDIGIT-239 Stage A `0578091f8`, Stage B `d90131361` · PBDIGIT-240 RED `c6490376c`, GREEN · PBDIGIT-234 (open).

## I4 — `forCast()` inside the cast transaction

- **Invariant:** "`forCast()` is evaluated in the cast transaction; nothing evaluated earlier authorises the cast."
- **Enforcement point [S]:** `store()` calls `forCast()` after `DB::beginTransaction()` with `new VotingAuthorization(app(EloquentVotingEntitlementQuery::class))` — the uncached query (ADR §3.5); facts read fresh after the lock.
- **Tests [D]:** `CastDecisionEnforcementTest.php` — enforcement · `AtomicParticipationConsumptionTest.php` R4-2 (participation consumed between the fact read and the cast is refused) — enforcement · `VotingAuthorizationForCastTest.php` — unit (`decide()` once, never `standingOf`).
- **Enforces / observes:** enforces.
- **Remaining gap [S]:** the security verdict passed into `forCast()` comes from the inline chain evaluated earlier in the same transaction; on the real path the D.0.3a gate refuses first, so `forCast()`'s security branches are not reached (recorded finding, not a violation of I4 [I]).
- **Human decision:** none.
- **Stories / commits:** Phase 4 E2 `f84e72ece` → `aa07a9160`, E3 `a818f2514` → `8cea53c73`.

## I5 — process facts are never authority

- **Invariant:** "`Code.can_vote_now`, `has_agreed_to_vote`, `has_voted` are process facts; none of them is authority."
- **Enforcement point [S]:** in `forCast()` they enter only as `CastAttempt` process facts (`codeStepCompleted`, `alreadyVotedPreCheck`); the authoritative "already voted" is the CAS.
- **Tests [D]:** `VotingAuthorizationForCastTest.php` — unit · `CastDecisionEnforcementTest.php` — enforcement (a code whose code step was never completed is refused).
- **Enforces / observes:** enforces inside `forCast()`.
- **Remaining gap [S]:** `VoteController::isUserEligible()` still returns eligibility from a `Code` with `can_vote_now = true` (≈`VoteController.php:184–188`) — a transitional gate (R3). Code validity window and regeneration semantics are open (R1-F6a; Q7 / PBDIGIT-231: a regenerated code keeps `can_vote_now`).
- **Human decision:** R1-F6a, Q7.
- **Stories / commits:** Phase 4 E2/E3 · R3 (not started) · PBDIGIT-231 (open).

## I6 — atomic participation consumption

- **Invariant:** "`Code.has_voted` is consumed by an atomic compare-and-set before the vote insert. For every successfully committed **ordinary** voting transaction, exactly one `codes.has_voted` false → true transition corresponds to exactly one anonymous vote insertion; therefore the committed production voting population satisfies the participation / ballot cardinality invariant (aggregate only, never per code). …"
- **Enforcement point [S]:** `store()`: `UPDATE codes SET has_voted = true WHERE id = ? AND has_voted = false`; zero rows → fail closed "already voted"; the vote insert follows.
- **Tests [D]:**
  - `AtomicParticipationConsumptionTest.php` R4-1…R4-4 (once, before the insert; concurrent consumption refused; keyed to this code with `has_voted = false`; rollback after a later failure — via the throwing double) — enforcement.
  - `tests/Feature/Characterization/Phase1/VotingIntegrityCharacterizationTest.php` H (committed votes equal consumed codes after successful and failed casts, aggregate) — characterization; B2 rollback — characterization.
- **Enforces / observes:** enforces the CAS and rollback; the aggregate cardinality is observed on the test population only.
- **Remaining gap [S/D]:** `election_memberships.has_voted` is not written by a cast (characterization "d known gap"); the vote path takes no database row lock — cache lock + CAS (characterization "a structural"). Neither breaks I6 as worded [I]. Production-data cardinality is unverified (D4.1).
- **Human decision:** D4.1 (historical forensics), not needed for the rule itself.
- **Stories / commits:** PBDIGIT-230 E4 `2afc6da5e` → `b1daf26a2` · test repair `33bef9f7d`.

## I7 — denial and error write nothing

- **Invariant:** "A denial or error writes no vote and leaves `has_voted` false."
- **Enforcement point [S]:** `failCastClosed()` (rollback to the entry level, own lock released) on every refusal; `catch (\Throwable)`; Exit A rolls back inside `ensureVoterMembership(inTransaction: true)`.
- **Tests [D]:** `CastFailClosedTest.php` R1-A…D · `FinalCastExitSafetyTest.php` (Exit A characterization, Exit B transaction + lock) · `FinalCastBallotInvariantTest.php` (every invalid ballot: no vote, no results, no CAS) · `CastDecisionEnforcementTest.php` · `AtomicParticipationConsumptionTest.php` R4-4 · `FinalDenialCharacterizationTest.php` — enforcement + characterization.
- **Enforces / observes:** enforces on every exit known in `store()` at HEAD.
- **Remaining gap:** a new early `return` added later would not be covered automatically [I].
- **Human decision:** none.
- **Stories / commits:** PBDIGIT-232 `7aa33df68` · PBDIGIT-237 `33bef9f7d` · PBDIGIT-243 `5cbba6f15`.

## I8 — no outcome logged with a ballot-side identifier (interim)

- **Invariant:** "No authorization or security outcome is logged together with a ballot-side identifier (interim anonymity rule)."
- **Enforcement point [S]:** log contexts in `VoteController` after PBDIGIT-241; the VE-01 rejection log carries `user_id`, `election_id`, `reason` only.
- **Tests [D]:** `tests/Feature/Voting/VoterBallotLogLinkageTest.php` L1–L4 (no receipt secret; no entry pairing the voter with the vote at the final cast, receipt confirmation and vote viewing) — enforcement on those four contexts.
- **Enforces / observes:** enforces on the four tested contexts only. The VE-01 rejection log content is checked by source reading [S], not by a test.
- **Remaining gap [S]** (recorded in PBDIGIT-241, not resolved here): A8 audit hash `sha256(vote_id . user_id . election_id)` logged with user and IP; vote id + `voter_slug_id` in one entry; vote id + client IP (A1); cross-entry correlation within one request; the receipt stored in clear in `receipt_codes`.
- **Human decision:** A8, A1 and the other PBDIGIT-241 linkages.
- **Stories / commits:** PBDIGIT-241 `19f0d3356` → `826a8f70d`.

## I9 — security policy immutable from ReadyForVoting

- **Invariant:** "The security policy is immutable from ReadyForVoting (like the admission policy) — the policy boundary itself is Q3."
- **Update 2026-10-05 (PBDIGIT-244):** at `5cbba6f15` no enforcement existed (source reading, then proven by RED `58b0ec769`: 9/9 locked-state changes stored). Status now: **enforced for the currently defined four-field security-policy boundary; Q3 open for completeness of the policy surface.**
- **Enforcement point [S]:** `Election::SECURITY_POLICY_FIELDS` (`ip_restriction_enabled`, `ip_restriction_max_per_ip`, `ip_whitelist`, `voter_verification_mode`). Application layer: `ElectionSettingsController::update()` refuses the whole PATCH on a real change to a listed field when `ElectionLifecycle::isLocked()` (`SecurityPolicyLocked`). Model guard: `Election::booted()` `saving` → `\LogicException`. Same two-layer pattern as the admission policy (PBDIGIT-213 C2).
- **Tests [D]:** `tests/Feature/Election/SecurityPolicyLifecycleLockTest.php` 26/26 — enforcement (editable before ReadyForVoting, refused from it, model guard, no false refusal, whole-PATCH refusal).
- **Enforces / observes:** enforces, at both layers, for the four listed fields.
- **Remaining gap:** the policy surface itself — other settings (e.g. `max_votes_per_ip`, `network_binding_strategy`) are not covered until Q3 decides.
- **Human decision:** Q3 (which settings form the security policy).
- **Stories / commits:** PBDIGIT-244 RED `58b0ec769` · GREEN (commit carrying this update).

## I10 — decisions stay on the voter side

- **Invariant:** "Authorization and process decisions operate entirely on the voter / process side: they do **not** modify the `Vote` aggregate and introduce **no** voter-derived value into the ballot record."
- **Enforcement point [S]:** `forEntry()`, `forCast()`, `BallotValidator` return decisions only; the ballot record is written by `save_vote()` with no `user_id`.
- **Tests [D]:** `AtomicParticipationConsumptionTest.php` R5 ("the vote row holds no value of the voter or the code" — asserts no voter-side identifier appears among the vote row's values; "the consumption update writes has_voted only") — enforcement.
- **Enforces / observes:** enforces that no raw voter-side identifier is stored in the vote row.
- **Remaining gap [S]** (named, not reinterpreted): A5 — `vote_hash` is computed from code id, election id, the code value, a timestamp and a salt (`save_vote()`); A6 — the step-5 record on the voter side stores the vote id. Whether these fall under I10 is part of the A5 / A6 decisions.
- **Human decision:** A5, A6.
- **Stories / commits:** Phase 4 E4 (R5) · Phase 1 findings A5 / A6.

## I11 — transport data untrusted

- **Invariant:** "Session, request or any other transport data is untrusted input. It may carry ballot state but never establishes ballot validity; validity is established only against the server-side authoritative ballot and the ballot-integrity rules, at the final cast, whatever the transport."
- **Enforcement point [S]:** `SubmittedBallot::fromTransport()` → `BallotValidator::validate()` against `AuthoritativeBallotQuery::ballotFor()` in `store()`.
- **Tests [D]:** `FinalCastBallotInvariantTest.php` rules 1–7, D2, D5, N1–N5 — enforcement · `tests/Feature/Voting/BallotIntegrityCharacterizationTest.php` (8 cases now passing, kept unchanged) — characterization.
- **Enforces / observes:** enforces at the final cast.
- **Remaining gap:** a missing regional list is treated as empty (PO decision, transport contract `nullable|array`); a fully empty ballot is accepted (PBDIGIT-242); `BallotAssemblyService` uses `withoutGlobalScopes()` — soft-deleted posts / candidacies not assessed [S, note only].
- **Human decision:** PBDIGIT-242.
- **Stories / commits:** PBDIGIT-237 `33bef9f7d`.

## I12 — persistence accepts only a ValidatedBallot

- **Invariant:** "Vote persistence (today `save_vote()`) accepts only a `ValidatedBallot`. It never accepts a raw request payload, session ballot, client candidacy ids or client post ids; stored ballot content is derived from validated content only."
- **Enforcement point [S]:** real path → `saveValidatedVote(ValidatedBallot …)`, which builds the payload from validated content and calls the existing writer; the demo path keeps `save_vote($vote_data)` (S2: demo out of scope).
- **Tests [D]:** `FinalCastBallotInvariantTest.php` (valid ballots persisted; two regional persistence characterizations: exact result rows) — enforcement + characterization.
- **Enforces / observes:** enforces on the real final cast. That `saveValidatedVote()` is the only real-path caller is established by source reading [S], not by an architecture test.
- **Remaining gap:** none within I12's scope (the real final-cast path). *Separate concern, outside I12 (PO review 2026-10-05):* `save_vote()` remains public and accepts arrays for the demo / legacy paths; on the real path only the call site keeps persistence on the validated route [S/I].
- **Human decision:** none.
- **Stories / commits:** PBDIGIT-237 `33bef9f7d`.

## I13 — both before the CAS, one CastContext

- **Invariant:** "Authorization (`forCast()` → `Allowed`) and ballot validation (→ `ValidatedBallot`) both complete successfully **before** the participation CAS, and both derive from **one CastContext** (Organisation · User · Election · ElectionVoter). Their relative order is not constitutional."
- **Enforcement point [S]:** both run in the `if ($election->type === 'real')` block before the CAS, from the same `$auth_user->id` and `$election->id`.
- **Tests [D]:** `FinalCastBallotInvariantTest.php` (every invalid ballot: the CAS statement is not executed) — enforcement · `CastDecisionEnforcementTest.php` (reasoned refusals consume nothing) — enforcement.
- **Enforces / observes:** enforces.
- **Remaining gap:** the regional part of the authoritative ballot reads the mutable `users.region` (interim source).
- **Human decision:** D6 (PBDIGIT-236).
- **Stories / commits:** PBDIGIT-237 `33bef9f7d`.

---

## Open human decisions referenced (named only, not resolved here)

| Item | Invariants affected |
|---|---|
| PBDIGIT-242 — fully empty ballot | I1, I11 |
| Q3 — security-policy boundary (→ 2B) | I3, I9 |
| PBDIGIT-240 design — verdict-only vs richer assessment, telemetry owner | I3 |
| Q6 — middleware role | I2 |
| R1-F6a, Q7 / PBDIGIT-231 — code validity and regeneration | I5 |
| A8, A1, other PBDIGIT-241 linkages | I8 |
| A5, A6 | I10 |
| D6 / PBDIGIT-236 — regional authority | I13 |
| D4.1 — historical forensics environment | I6 (production data only) |

## Limits of this matrix

- It maps invariants to the code and committed tests at `5cbba6f15`; it adds no new test and changes no code.
- Test-type and enforcement labels are engineering judgement on the cited suites [I]; the suites themselves are the evidence.
- Demo elections are outside I1, I4, I6, I11–I13 by decision (S2, Q4) and are not assessed.
- Acceptance is the PO's act (EP-02); this document is evidence and recommendation only.
