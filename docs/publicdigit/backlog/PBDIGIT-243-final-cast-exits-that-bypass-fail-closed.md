# PBDIGIT-243 — Two final-cast exits return without the fail-closed exit (F-V9)

**Type:** Defect (transaction / lock safety) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** real-election review `docs/publicdigit/reviews/2026-10-05-real-election-code-and-vote-controller-review.md` (F-V9); Phase 4 architecture audit `2026-10-05-phase4-e1-e4-architecture-audit.md` (F7, F9)
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Existing-story search (freeze rule):** `PBDIGIT-232` (Phase 4 E1, `failCastClosed()`, RESOLVED) covers the exits that existed as fail-closed paths; its suite `CastFailClosedTest` states the IP-binding exit is not covered. `PBDIGIT-234` owns the missing `users.voting_ip` column (why Exit B is latent), not the exit's transaction/lock behaviour. No story owns these two exits.

| | |
|---|---|
| **Status** | 🟢 **RESOLVED (2026-10-05)** — RED `da54c03c5` · GREEN (PO-authorised): Exit B calls `failCastClosed()` before returning the denial; Exit A kept as a characterization. Evidence: `FinalCastExitSafetyTest` 3/3 · `CastFailClosedTest` 4/4 · `AtomicParticipationConsumptionTest` 7/7 · `CastDecisionEnforcementTest` 6/6 · 97-suite regression unchanged from the `33bef9f7d` baseline (579 pass, 334 fail, 19 skipped; no test changed status) — no observed regression relative to that baseline, no broader claim. Guide `developer_guide/election/final_cast/08_exit_safety_ip_binding_exit.md` |
| **Invariant** | Every final-cast exit after `DB::beginTransaction()` either commits or fails closed: transaction rolled back to the caller's level, this path's own lock released, no vote, `has_voted` unchanged, never HTTP 500 (ADR_20261005_0950 §3.4, I7) |
| **Exit A** | `VoteController::store()` ≈1604 — fresh `ensureVoterMembership(…, useCache: false, inTransaction: true)` denies → `return $redirect`. After BEGIN, **before** the lock. Source: the trait itself calls `DB::rollBack()` when `inTransaction` and a transaction is active (`app/Traits/EnsuresVoterMembership.php`). Reachable (cached middleware standing allowed, fresh standing denied) |
| **Exit B** | `store()` ≈1736 — `validateVotingIpWithResponse()` returns a `Vote/VoteDenied` response → `return $ipValidation`. After BEGIN **and after the lock**, after `forCast()` and VE-01 validation, before the CAS. Latent: `users.voting_ip` does not exist (PBDIGIT-234) |
| **RED evidence (2026-10-05, synthetic data)** | `FinalCastExitSafetyTest`: Exit A **passes at HEAD** (transaction back at baseline, another holder's lock untouched, nothing consumed) — **kept as a characterization (PO 2026-10-05), no fix**. Exit B (precondition `Vote/VoteDenied` "IP Address Mismatch" proven): **RED** — transaction level 2 vs baseline 1 and the slug lock still held; nothing consumed (exit is before the CAS) |
| **GREEN (authorised and applied 2026-10-05)** | Exit B only: `failCastClosed($outerTransactionLevel, $lock, $lockAcquired)` before `return $ipValidation`; response unchanged |
| **Out of scope** | authorization policy · IP policy and the `users.voting_ip` column (PBDIGIT-234) · VE-01 · PBDIGIT-240 · refactoring of `store()` |

**Traceability:** plan `docs/plans/20261005-2139-final-cast-exit-safety-red-plan.md` · RED `tests/Feature/Voting/FinalCastExitSafetyTest.php`.
