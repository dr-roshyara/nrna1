# Final Cast Hardening — Developer Guide (Phase 4)

**Phase:** 4 of the voting-integrity roadmap (`docs/plans/20261005-0647-voting-integrity-anonymity-and-authorization-roadmap-plan.md`) · **Status:** ✅ E1–E4 committed and accepted (2026-10-05)
**Plan:** `docs/plans/20261005-1105-phase4-final-cast-hardening-plan.md`
**Decision:** `docs/publicdigit/adr/ADR_20261005_0950_Voting_Authorization_Boundary.md` (approved, frozen) · PO decision record `docs/publicdigit/reviews/2026-10-05-phase4-final-cast-hardening-po-decision-record.md`
**Stories:** PBDIGIT-232 (E1) · PBDIGIT-233, 219 (E3) · PBDIGIT-230 (E4) — all resolved for the final cast · found on the way: PBDIGIT-234, PBDIGIT-235

---

## The model in one picture

```
Authorization (may this voter cast now?)  ≠  Process validation (is this attempt valid?)  ≠  Participation consumption
            └──────────── forCast() → CastDecision ────────────┘                             CAS on codes.has_voted
                                                                                              outside any decision
```

A real-election vote commits **only** when the decision is `Allowed`, the compare-and-set on `codes.has_voted` changes exactly one row, the anonymous insert succeeds and the transaction commits. Every other outcome fails **closed**: no vote, `has_voted` unchanged, a reason — never HTTP 500.

## The final cast after Phase 4 (`VoteController::store`, real election)

```
BEGIN (outer level recorded)                                                              E1
  Trust → Legitimacy chain ──deny──► failCastClosed · "vote" error (no lock taken yet)     E1
  lifecycle · fresh membership ──deny──► failCastClosed                                     E1
  load Code · slug lock (refused → "being processed", other holder's lock untouched)       E1
  read facts fresh + null-safe
  forCast(CastAttempt) ──not Allowed──► failCastClosed · refuseCast(reason)                E2 / E3
  IP-binding helper (unreachable — PBDIGIT-234) · input validation · code check            unchanged
  CAS: UPDATE codes SET has_voted = true WHERE id = ? AND has_voted = false
       ──0 rows──► failCastClosed · "already voted"                                         E4
  save_vote() (INSERT anonymous vote) · step 5 · markUserAsVoted · receipt · notification
COMMIT · release own lock
any \Throwable before COMMIT ──► failCastClosed (rollback, consumption undone) · generic error   E1
```

Demo elections are excluded from `forCast()` and the CAS (Q4); they normally use `DemoVoteController`, and the demo branch of `store()` keeps its unchanged `vote_post_check()` call.

## Steps

| File | Group | What it covers |
|---|---|---|
| [01_e1_fail_closed_exit.md](./01_e1_fail_closed_exit.md) | E1 | The fail-closed exit: UUID user id in the divergence tracker, `\Throwable`, transaction / lock ownership (`failCastClosed()`) |
| [02_e2_cast_decision.md](./02_e2_cast_decision.md) | E2 | `VotingAuthorization::forCast()` → `CastDecision`: order, 14-reason vocabulary, `CastAttempt` facts, security verdict as a fact |
| [03_e3_cast_enforcement.md](./03_e3_cast_enforcement.md) | E3 | `store()` decides through `forCast()` for real elections; reasoned refusals; `vote_post_check()` demo-only |
| [04_e4_atomic_participation_consumption.md](./04_e4_atomic_participation_consumption.md) | E4 | The compare-and-set on `codes.has_voted` before the vote insert; placement; rollback; evidence and its limits |
| [05_ve01_ballot_integrity_red_and_design.md](./05_ve01_ballot_integrity_red_and_design.md) | VE-01 (after Phase 4) | Ballot integrity: the defect, the RED contract, frozen decisions, the approved target design (PBDIGIT-237; GREEN → 07) |
| [06_log_anonymity_receipt_secret.md](./06_log_anonymity_receipt_secret.md) | PBDIGIT-241 | No receipt secret and no voter↔ballot pair in one log entry; the rule for new logging; A8 still open |
| [07_ve01_ballot_integrity_green.md](./07_ve01_ballot_integrity_green.md) | VE-01 GREEN | `BallotValidator` beside `forCast()` before the CAS; only a `ValidatedBallot` is persisted (`saveValidatedVote()`); D5 in `BallotAssemblyService`; GREEN `33bef9f7d` (PBDIGIT-237) |
| [08_exit_safety_ip_binding_exit.md](./08_exit_safety_ip_binding_exit.md) | PBDIGIT-243 | Exit A (fresh membership) already rolls back — characterization; Exit B (IP binding) now fails closed: transaction rolled back, lock released |
| [09_one_security_assessment.md](./09_one_security_assessment.md) | PBDIGIT-240 | The final cast consumes one `SecurityAssessment` from the Security Verdict Authority (I3 for the final cast); `verdictFor()` derived; no inline evaluation; checks #2–#6 deferred to Q3 / 2B |
| [10_ip_whitelist_raw_cidr.md](./10_ip_whitelist_raw_cidr.md) | PBDIGIT-246 | The election IP whitelist matched as raw IPv4 CIDR inside the authority; only "whitelisted" enters the evidence (Q3-D5, 2B-1) |

## Commits

| Group | RED | GREEN |
|---|---|---|
| E1 | — (in GREEN commit) | `7aa33df68` (+ docs `50fa01b2c`) |
| E2 | `f84e72ece` | `aa07a9160` |
| E3 | `a818f2514` | `8cea53c73` |
| E4 | `2afc6da5e` | `b1daf26a2` |

## Tests (all synthetic data)

| Suite | Contracts |
|---|---|
| `tests/Feature/Voting/CastFailClosedTest.php` | R1-A … R1-D — fail-closed exits, lock ownership |
| `tests/Unit/Contexts/Elections/VotingAuthorizationForCastTest.php` | R2 — 16 decision contracts |
| `tests/Feature/Voting/CastDecisionEnforcementTest.php` | R3 — reasoned refusals, `vote_post_check()` off the real path |
| `tests/Feature/Voting/AtomicParticipationConsumptionTest.php` | R4 / R5 / R6 — CAS order, zero rows, rollback, anonymity, demo |

Retired Phase 1 characterizations (they asserted the fixed defects): `test_g2_known_gap_…` (→ R1-A), `test_i_a_failing_pre_check_…` (→ R3-1). Regression vs baseline `d4d6b2890`: 0 previously passing tests fail.

## Behaviour changes to know

- Denials and internal errors at the final cast are reasoned refusals, never HTTP 500.
- A code whose code step was never completed is refused (it was accepted before).
- With `MAX_USE_IP_ADDRESS` unset, real votes no longer crash at the final cast (code entry still reads it — ops item).
- A competing cast that consumed participation first makes this cast fail with "already voted" instead of writing a second vote.

## Out of scope for the whole phase

A5 / A6, Model A/B, IP binding (unreachable — PBDIGIT-234), device binding, `can_vote_now` semantics, persisted authorization flags, a new security engine, the `Vote` anonymity model, `election_memberships.has_voted`, receipts, demo-election behaviour, agreement beyond Q5 = A, soft-deleted codes (PBDIGIT-235).
