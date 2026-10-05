# Final Cast Hardening — Developer Guide

**Phase:** 4 of the voting-integrity roadmap (`docs/plans/20261005-0647-voting-integrity-anonymity-and-authorization-roadmap-plan.md`)
**Plan:** `docs/plans/20261005-1105-phase4-final-cast-hardening-plan.md`
**Decision:** `docs/publicdigit/adr/ADR_20261005_0950_Voting_Authorization_Boundary.md` (approved, frozen) · PO decision record `docs/publicdigit/reviews/2026-10-05-phase4-final-cast-hardening-po-decision-record.md`
**Stories:** PBDIGIT-232 (E1) · PBDIGIT-233, 219 (E3) · PBDIGIT-230 (E4)

---

## The model in one picture

```
Authorization (may this voter cast now?)  ≠  Process validation (is this attempt valid?)  ≠  Participation consumption (CAS on codes.has_voted)
            forCast() → CastDecision ───────────────────────────────┘                              outside any decision
```

The final cast — `VoteController::store` — commits a vote only when the decision is `Allowed`, the compare-and-set on `codes.has_voted` changes exactly one row, the anonymous insert succeeds and the transaction commits. Every other outcome fails **closed**: no vote, `has_voted` unchanged, no HTTP 500.

## Steps

| File | Group | What it covers |
|---|---|---|
| [01_e1_fail_closed_exit.md](./01_e1_fail_closed_exit.md) | E1 | The fail-closed exit: UUID user id in the divergence tracker, `\Throwable`, transaction / lock ownership (`failCastClosed()`) |

E2 (`forCast()` / `CastDecision`), E3 (enforcement in `store()`) and E4 (CAS) follow as their slices land.

## Out of scope for the whole phase

A5 / A6, Model A/B, IP binding (the `users.voting_ip` branch is unreachable — PBDIGIT-234), device binding, `can_vote_now` semantics, persisted authorization flags, a new security engine, the `Vote` anonymity model, `election_memberships.has_voted`, receipts, demo-election behaviour, agreement beyond Q5 = A.
