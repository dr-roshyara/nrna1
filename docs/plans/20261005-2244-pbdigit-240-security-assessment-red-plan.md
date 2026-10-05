# Plan — PBDIGIT-240 RED: one security assessment for the final cast

**Status:** ✅ complete — RED `c6490376c`, GREEN authorised and applied (PO 2026-10-05) · **Story:** PBDIGIT-240 · **Design:** `docs/publicdigit/reviews/2026-10-05-pbdigit-240-security-authority-design-proposal.md` (D-240-1 … D-240-4 accepted, `7d948969f`)

## Objective

Tests only, no production change. They prove today's gap (the final cast evaluates the security chain itself) and pin today's behaviour (messages, audit line, divergence records), so GREEN can be checked as behaviour-preserving.

## Contract the RED fixes (from D-240-1 / D-240-3)

| Name | Shape |
|---|---|
| `App\Contexts\Elections\Application\Ports\VotingSecurityAssessmentQuery` | `assess(SecurityAttemptContext): SecurityAssessment` |
| `App\Contexts\Elections\Application\DTO\SecurityAssessment` | `public readonly SecurityVerdict $verdict`, `LegitimacyOutcome $outcome`, `TrustEvaluationEnvelope $envelope` |
| `VotingSecurityVerdictQuery::verdictFor()` | unchanged signature; must equal `assess(...)->verdict` |

The tests reference the new names as strings, so a missing class fails a test instead of the suite.

## Tests

**New suite `tests/Feature/Voting/SecurityAssessmentConsolidationTest.php`** (Phase 1 cast fixtures, synthetic data)

*Test-only seam:* a recording subclass of `TrustPolicyEvaluator` (non-final, container singleton) bound with `app()->instance()`. It counts `evaluate()` calls, records the classes on the call stack, and can replace the trust state (INSUFFICIENT / REVIEW_REQUIRED / INCONCLUSIVE) to reach each D.0.3a outcome.

| ID | Test | Expected at HEAD |
|---|---|---|
| P1 | No controller except `DemoVoteController` (demo out of scope, S2 / Q4) references `TrustPolicyEvaluator`, `ConstitutionalLegitimacyDecision` or calls `->evaluate(` / `->decide(` on them | **RED** (`VoteController`) |
| P1b | IP-density queries (`where('client_ip'` / `where('step_1_ip'`) in controllers occur only in the known 2B debt methods `CodeController::verifyCode`, `VoteController::vote_post_check`, `ElectionVotingController::evaluateIpCount` | **RED** (`VoteController::store`) |
| P2 | One final cast evaluates the chain exactly once, and the call comes through the authority (a class implementing `VotingSecurityVerdictQuery` is on the stack; `VoteController` is not the direct caller) | **RED** (called directly by `VoteController`) |
| C1 | D.0.3a messages: forced Denied / Deferred / Investigate → the three existing `vote` errors, nothing consumed | pass (characterization) |
| C2 | Audit line "Trust evaluation completed in vote submission" carries `election_id`, `user_id`, `trust_result`, `constitutional_outcome`, `ip` | pass |
| C3 | Divergence records ("Sovereignty divergence detected"): gate Denied → none (outcomes match); gate Deferred / Investigate → one each; membership denial → one; lifecycle denial → one, reached via the F-C2 session flag (PBDIGIT-217) | pass, or reported if a site is unreachable |

**Additions to `tests/Feature/Voting/VotingSecurityVerdictAdapterTest.php`** (the equivalence oracle)

| ID | Test | Expected at HEAD |
|---|---|---|
| E1 | The `assess()` outcome and verdict equal the inline path, and `verdictFor()` equals `assess()->verdict`, for the characterized contexts (allowed, denied above the limit, strategy `none`) | **RED** (port missing) |

The oracle's line-verified inline copy is restructured once: `inlineOutcome()` holds the copy, and `inlineVerdict()` maps it. The existing tests are unchanged in behaviour.

## Out of scope

Production code · checks #2–#7 · Q3 / 2B · tenancy of the density count · demo · anonymity items.

## Task checklist

- [x] suite + oracle additions written, linted
- [x] run: P1, P1b, P2, E1 RED for the stated reason; C1–C3 pass (or reported)
- [x] the existing oracle tests still pass
- [x] report → STOP

## Progress (RED run 2026-10-05)

- **RED as intended:**
  - P1 — `VoteController` references `TrustPolicyEvaluator` and `ConstitutionalLegitimacyDecision`.
  - P1b — one unexpected density query, `VoteController::store`.
  - P2 — exactly one evaluation, but not through the authority (called by `VoteController` itself).
  - E1 ×3 — the assessment port does not exist.
- **Characterizations pass:**
  - C1 — the three D.0.3a messages, nothing consumed.
  - C2 — the audit line with its five keys, outcome `allowed`.
  - C3 gate — Denied: 0 divergence records (outcomes match); Deferred: 1; Investigate: 1.
  - C3 membership denial — 1 record.
- **Deviation from the plan (reported):** `store()`'s lifecycle denial site is **not reachable** through `slug.vote.store`. The route's `voting.active` middleware (`EnsureVotingActive`, no demo bypass) refuses a non-votable election first, even with the F-C2 demo flag. The planned "lifecycle → one record" test became a characterization of that fact: redirect to the dashboard, `store()` not entered (0 evaluations), 0 records, nothing consumed.
- **Existing oracle tests a01–a06 and the two known-defect tests:** unchanged results after the `inlineOutcome()` restructure.
- **No production change.**

## GREEN (PO-authorised 2026-10-05)

- Implemented per the contract above: `VotingSecurityAssessmentQuery`, `SecurityAssessment`, `assess()` in `TrustLegitimacySecurityVerdict` (bound to both ports), `verdictFor()` = `assess()->verdict`; `store()` consumes one assessment; inline chain, `securityVerdictOf()` and evaluator / resolver injection removed; oracle header records the frozen-reference (drift) rule.
- `SecurityAssessmentConsolidationTest` 12/12 · `VotingSecurityVerdictAdapterTest` 11/11 · `ContextBoundaryFitnessTest` 10 + 2 incomplete (unchanged) · Phase 4, exit-safety, ballot-invariant, code-entry suites green.
- 97-suite regression vs `5cbba6f15`: 579/334/19 → 583/333/19, no pass → fail (3 new E1 tests; 1 pre-existing `ArgumentCountError` on `new VoteController()` now passing because the constructor dependencies were removed).
- Semantic review (PO request): the `User::findOrFail()` reload is behaviour-preserving for the production security path — see guide `final_cast/09` §Semantic note.
- **Plan complete.** Checks #2–#6 untouched (Q3 / 2B).
