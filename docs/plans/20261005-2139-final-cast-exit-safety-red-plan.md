# Plan — Final-cast exit safety, RED only (PBDIGIT-243)

**Status:** RED authorised (PO 2026-10-05) · GREEN not authorised · **Story:** `docs/publicdigit/backlog/PBDIGIT-243-final-cast-exits-that-bypass-fail-closed.md`

## Objective

Prove, with synthetic data, whether the two `VoteController::store()` exits that do not call `failCastClosed()` are transaction- and lock-safe.

## Background

F-V9 (real-election review) and Phase 4 audit F7/F9. The two exits sit at different points, so each gets its own invariant (PO 2026-10-05):

| Exit | Position | Transaction | Lock | Partial state |
|---|---|---|---|---|
| A — fresh membership denied | after BEGIN, before the lock | back at the caller's level | not acquired — another holder's lock untouched | none |
| B — `validateVotingIpWithResponse()` denies | after BEGIN and the lock, before the CAS | back at the caller's level | released | none |

## Design decisions

- **One suite** `tests/Feature/Voting/FinalCastExitSafetyTest.php`, Phase 1 `CastFixtures`, `CastFailClosedTest` probes (transaction level vs the test's baseline; lock probe on `voter_slug_transition:{slug}`).
- **Every test first proves the exit was taken** (its own response), so a pass or fail can't come from an earlier refusal.
- **Exit A trigger:** warm the cached standing (the route middleware's view), then set the membership `inactive` through the query builder (no model events, so the cache keeps "allowed") — the fresh check in `store()` denies.
- **Exit B trigger (test-only double):** `users.voting_ip` does not exist (PBDIGIT-234). The authenticated user instance gets an in-memory `voting_ip` that differs from the request IP; nothing is saved. Any change in the trust evaluation caused by this attribute is reported, not adjusted.
- No production change; no change to VE-01, authorization or IP policy, PBDIGIT-240.

## Task checklist

- [x] backlog story PBDIGIT-243 + README row
- [x] this plan
- [x] RED tests written and run → report → STOP

## Progress

RED run 2026-10-05: Exit A characterization passes at HEAD (the trait rolls back itself; lock untouched; nothing consumed). Exit B precondition proven (`Vote/VoteDenied`, "IP Address Mismatch"); **RED** — transaction level 2 vs baseline 1 (leaked), lock still held; nothing consumed. No production change.

## Risks

- Exit B's double may be pre-empted by the trust gate (the registered IP feeds `TrustPolicyEvaluator`). Then Exit B is unreachable even with the column present — reported as evidence.
- Exit A is expected to pass at HEAD (the trait rolls back itself) — a characterization, not a defect proof.

## Next actions

Run the suite, report per test with the exit precondition, STOP for review.
