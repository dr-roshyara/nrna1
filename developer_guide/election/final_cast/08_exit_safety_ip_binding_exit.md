# 08 — Final-cast exit safety: the IP-binding exit fails closed (PBDIGIT-243)

**Story:** PBDIGIT-243 · **RED:** `da54c03c5` · **GREEN:** this step's commit · **Found by:** real-election review F-V9; Phase 4 audit F7 / F9 · **Decision:** ADR_20261005_0950 §3.4 (fail-closed), I7

## Purpose

Every final-cast exit after `DB::beginTransaction()` must either commit or fail closed: the transaction is back at the caller's level, this path's own lock is released, nothing is consumed, and the response is never HTTP 500. Two exits in `VoteController::store()` returned without `failCastClosed()`. This step characterizes one and fixes the other.

## The two exits

| Exit | Position in `store()` | Outcome |
|---|---|---|
| **A** — fresh `ensureVoterMembership(…, useCache: false, inTransaction: true)` denies → `return $redirect` | after BEGIN, **before** the lock | **Already safe.** The trait itself calls `DB::rollBack()` when `inTransaction` is true and a transaction is active (`app/Traits/EnsuresVoterMembership.php`). No lock is held yet. Kept as a characterization (PO 2026-10-05), no code change |
| **B** — `validateVotingIpWithResponse()` returns `Vote/VoteDenied` → `return $ipValidation` | after BEGIN **and** the lock, after `forCast()` and VE-01 validation, before the CAS | **Was unsafe:** transaction left open and the slug lock held. **Fixed** |

Exit B is latent in production: the helper reads `users.voting_ip`, a column that does not exist, so it always passes (PBDIGIT-234). The fix makes the exit safe for the day the column exists.

## The change

```php
$ipValidation = validateVotingIpWithResponse();
if ($ipValidation instanceof \Inertia\Response) {
    // PBDIGIT-243: fail closed — roll back the cast transaction and release this path's lock.
    $this->failCastClosed($outerTransactionLevel, $lock, $lockAcquired);

    return $ipValidation; // Returns the denial response
}
```

`failCastClosed()` (guide 01) rolls back only when a transaction is active, down to the level recorded at entry, and releases the lock only when this path acquired it. The denial response is unchanged.

## Testing

`tests/Feature/Voting/FinalCastExitSafetyTest.php` — every test first proves its exit was the one taken (its own response), so a result cannot come from an earlier refusal.

| Test | Trigger (test-only) | Asserts |
|---|---|---|
| Exit A characterization | warm the cached standing, then set the membership `inactive` via the query builder (no model events) — only the fresh check sees it | transaction at baseline · another holder's lock untouched · no vote · `has_voted` false · not 500 |
| Exit B transaction | in-memory `voting_ip` on the authenticated user, different from the request IP; nothing saved | transaction level back at baseline |
| Exit B lock | same | the `voter_slug_transition:{slug}` lock is free afterwards |

**Validation evidence (2026-10-05, synthetic data):** `FinalCastExitSafetyTest` 3/3 · `CastFailClosedTest` 4/4 · `AtomicParticipationConsumptionTest` 7/7 · `CastDecisionEnforcementTest` 6/6 · 97-suite regression unchanged from the `33bef9f7d` baseline: 579 pass, 334 fail, 19 skipped; no test changed status. This shows no observed regression relative to that baseline; it does not establish correctness beyond what these tests cover.

## Pitfalls

- Every new early `return` in `store()` after BEGIN must go through `failCastClosed()` — or, like Exit A, provably roll back itself. Add a case to `FinalCastExitSafetyTest` when you add an exit.
- Do not "fix" Exit A by adding a second rollback: the trait already rolled back one level, and `failCastClosed()` rolls back only while the level is above the recorded entry level — but a double path makes the ownership rule harder to read.
- The Exit B double depends on the authenticated user instance being the one the helper reads (`Auth::user()`); if that changes, the precondition assertion will fail first.

## Still open

- The `users.voting_ip` column and IP-binding policy → PBDIGIT-234 (not changed here).
- IP-check consolidation (`verifyCode()`, `vote_post_check`, `ValidateVotingIp`) → Q3 / 2B.

**Traceability:** PBDIGIT-243 · plan `docs/plans/20261005-2139-final-cast-exit-safety-red-plan.md` · RED `da54c03c5` · review F-V9 (`docs/publicdigit/reviews/2026-10-05-real-election-code-and-vote-controller-review.md`) · Phase 4 audit F7 / F9 · guide 01 (`failCastClosed()`).
