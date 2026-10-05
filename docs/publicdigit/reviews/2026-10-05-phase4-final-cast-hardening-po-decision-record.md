# PO Decision Record — Phase 4 Final-Cast Hardening

**Date:** 2026-10-05 · **Decided by:** PO (human act, this session) · **Recorded by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable). Engineering supplies the record; the decisions are the PO's.
**Plan:** `docs/plans/20261005-1105-phase4-final-cast-hardening-plan.md` (rev 2)
**Baseline:** `docs/publicdigit/adr/ADR_20261005_0950_Voting_Authorization_Boundary.md` (APPROVED, frozen — **not modified** by this record) · `docs/publicdigit/reviews/2026-10-05-phase3-authorization-boundary-po-arb-decision-record.md`

## 1. Scope

**Approved:** the Phase 4 architecture and scope — (1) `forCast()` → `CastDecision`; (2) PBDIGIT-230 atomic participation consumption; (3) PBDIGIT-232 security-denial crash; (4) PBDIGIT-233 `vote_post_check` result / missing view.
**Not yet authorised:** implementation. E1 RED starts only after a separate PO approval; every RED and GREEN step stops for review.

## 2. Decisions

| ID | Decision | Effect |
|---|---|---|
| **Q2** | **Deny** when the security authority returns `Deferred` / `Investigate` | `SecurityReviewRequired` may remain a denial reason **for diagnostics**; there is **no** human review workflow |
| **Q4** | **Demo elections remain excluded** from `forEntry()` / `forCast()` | current demo behaviour preserved |
| **Q5** | **Option A** | `has_agreed_to_vote` remains a UI / process step, **not** an authorization invariant |
| **P4-D1** | **B** | the old `vote_post_check()` IP-density branch is **not** carried into `forCast()`; the existing Trust → Legitimacy chain is the single IP-density authority on the final cast; `MAX_USE_IP_ADDRESS` may remain relevant to code entry until the later security phase |
| **P4-D2** | **A** | `CodeMissing` and `BallotMissing` are fail-closed process reasons covered by the existing ADR principle (§3.4: any missing input → rollback + reason). Recorded here explicitly; **the frozen ADR text is not changed** |
| **P4-D3** | **A** | a missing `Code` is handled as `CodeMissing` instead of HTTP 500; **PBDIGIT-219 is resolved by this Phase 4 behaviour** (story status changed at E3 GREEN acceptance) |
| ~~**P4-D4**~~ | ~~A~~ → **RETIRED** (see §2a) | originally: rollback / lock-release hygiene in the `validateVotingIpWithResponse()` exit only |

## 2a. P4-D4 retired from Phase 4 (PO, 2026-10-05 — option C)

> **P4-D4 retired from Phase 4.** The historical IP-binding branch was investigated and found unreachable under the current schema because `users.voting_ip` has no persisted database representation. No synthetic in-memory state will be introduced to force execution of this branch. IP-binding behaviour remains outside Phase 4 and is deferred to the later security / IP-binding phase.

- **Evidence (FACT):** no migration creates `users.voting_ip`; the first R1 run failed in the test's own setup with `SQLSTATE[42703]: column "voting_ip" of relation "users" does not exist`.
- **Consequences:** R1 contract #4 (IP-binding exit) removed — R1 has four contracts; `validateVotingIpWithResponse()` and its call in `store()` are not changed by Phase 4; the branch is recorded as **PBDIGIT-234**.
- **Explicitly not done:** no `voting_ip` migration, no change to `users`, no revival of legacy IP binding, no IP-binding semantics change, no dynamic/in-memory property to force the branch, no mock forcing an impossible production state, no move of IP validation into `VotingAuthorization`, no new security authority.

## 2b. Baseline (accepted by the PO)

§6.1 regression set run at `d4d6b2890` (29 files, `php artisan test --env=testing <file>` per file). Pre-existing failures (`VoterAuditTrailTest`, `VotingEngineIntegrationTest`, the demo-election suites, `DemoVoteVerificationFlowTest`, `DemoVoteControllerCreateTest`) are **baseline failures** and are **not** to be fixed in Phase 4 to obtain green.

## 2c. E2 contract decisions (PO, 2026-10-05, at E2 RED acceptance)

| ID | Decision |
|---|---|
| **E2-D1 — process-reason order** | `CodeMissing` → `AlreadyVotedPreCheck` → `CodeMismatch` → `CodeStepIncomplete` → `BallotMissing` (today's `store()` sequence, voter-visible messages preserved). **This order is an E2 contract and must not be changed without an explicit architectural decision.** |
| **E2-D2 — entitlement read** | `forCast()` calls `VotingEntitlementQuery::decide()` (standing ∧ lifecycle) **exactly once** and **never** `standingOf()`; it consumes the authoritative entitlement decision and does not reconstruct it. |
| **E2-D3 — vocabulary** | `CastDecisionReason` has exactly **14** cases: `Allowed` · 6 forwarded entitlement reasons (`NotRegistered`, `NotVoterRole`, `OrganisationMismatch`, `NotActive`, `NotAdmitted`, `ElectionNotVotable` — all `VotingEntitlementReason` cases except `Entitled`) · `SecurityDenied`, `SecurityReviewRequired` · `CodeMissing`, `AlreadyVotedPreCheck`, `CodeMismatch`, `CodeStepIncomplete`, `BallotMissing`. Derivation: ADR §3.3 (12, without `AgreementMissing` per Q5 = A) + P4-D2 (2). |
| **E2-D4 — security sub-reason (option A)** | No security sub-reason on `CastDecision` / `SecurityVerdict`. The detailed reason stays in the existing Trust → Legitimacy chain's own logging. Rationale: no new place where a denial detail could be recorded next to ballot data (I8). ADR §3.3's "carrying the security sub-reason" is satisfied by that existing log; **the frozen ADR text is not changed**. |
| **E2-D5 — placement** | `Application\Services\VotingAuthorization`, `Application\DTO\CastAttempt` / `CastDecision`, `Domain\ValueObjects\CastDecisionReason` / `SecurityVerdict` — approved **for E2 only**, not as a general rule for the Elections context. |
| **E2-D6 — R2a** | The architecture / fitness test (R2a) is **deferred** to a separate review; E2 is verified by the R2 unit contract only. |

## 3. Correction — transaction and lock ownership

> **Roll back only when a transaction is active, and release the cache lock only when this execution path actually acquired the lock.**

Failures before the lock is acquired (security, lifecycle, entitlement) must not release a lock they do not own. Explicit tests are required (plan §4 R1).

## 4. CAS / concurrency evidence wording

Three-part proof kept: (1) DB-level conditional update — first CAS 1 row, second 0 rows; (2) end-to-end consumed-code case rejected without a vote insert; (3) structural assertion that the CAS precedes `save_vote()`. **No claim that PHPUnit reproduced a true parallel race:** the database primitive and the production ordering are proven; a real parallel race is not reproduced by the single-process test environment.

## 5. Frozen (not touched by Phase 4)

A5 · A6 · Model A/B · IP-binding implementation · device binding · `can_vote_now` semantics · persisted authorization/security flags · new security engine · `Vote` anonymity model · `election_memberships.has_voted` · receipt architecture · demo-election behaviour · agreement beyond Q5 = A · migrations / schema changes.

**Traceability:** ADR_20261005_0950 §3.2–3.4, §5, §7, §8, §9 · PBDIGIT-219, 230, 232, 233, 234 · Phase 1 evidence report.
