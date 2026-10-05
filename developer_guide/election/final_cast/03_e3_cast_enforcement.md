# E3 — `store()` decides through `forCast()`

**Stories:** PBDIGIT-233 (D1, D10 on the final cast) · PBDIGIT-219 · **RED:** `a818f2514` · **Plan:** §2.2 E3, §4 R3 · **Decisions:** P4-D1 = B, P4-D2 = A, P4-D3 = A, E2-D1…D4, option A (`vote_post_check()` demo-only)

## Purpose

Before E3 the final cast had no single decision for the attempt itself:

- `vote_post_check()` ran but its result was never read (D1); its refusals happened only by crashing on a missing view, and with `MAX_USE_IP_ADDRESS` unset its IP branch crashed **every** real vote (D10);
- a voter without a `Code` hit `$code->fresh()` on null (PBDIGIT-219);
- **a code whose code step was never completed (`can_vote_now = 0`) was accepted and a vote written** (found by E3 RED, R3-2).

E3 puts the E2 decision — `VotingAuthorization::forCast()` → `CastDecision` — inside the cast transaction for **real** elections, and turns every refusal into a reason.

## Where it fits

`app/Http/Controllers/VoteController.php` — `store()` plus three private helpers. Demo elections are excluded (Q4) and keep their unchanged path.

## How it works

After the slug lock is acquired (E1), `store()` reads the process facts fresh and null-safe, then — for real elections only — asks for the decision:

```php
$freshCode = $code?->fresh();
$session_name = $code?->session_name ?: ('vote_data_' . $auth_user->id);
$vote_data = $request->session()->get($session_name);

if ($election->type === 'real') {
    $decision = (new VotingAuthorization(app(EloquentVotingEntitlementQuery::class))) // fresh, uncached (ADR §3.5)
        ->forCast(new CastAttempt(
            voterId: $auth_user->id,
            electionId: $election->id,
            security: $this->securityVerdictOf($constitutionalOutcome),
            codeExists: $freshCode !== null,
            submittedCodeMatches: $freshCode !== null
                && $this->verify_submitted_code($freshCode->code_to_open_voting_form, trim((string) $request->input('voting_code'))),
            codeStepCompleted: (bool) $freshCode?->can_vote_now,
            alreadyVotedPreCheck: (bool) $freshCode?->has_voted,
            ballotPresent: $vote_data !== null,
        ));

    if (! $decision->isAllowed()) {
        if ($decision->reason === CastDecisionReason::CodeMismatch) {
            // Malformed input keeps its own messages, as before (ValidationException → fail-closed catch).
            $this->validateVotingCodeInput($request);
        }

        $this->failCastClosed($outerTransactionLevel, $lock, $lockAcquired);

        return $this->refuseCast($decision->reason, $auth_user, $election, $request);
    }
}
```

- **Entitlement** is read through the **uncached** `EloquentVotingEntitlementQuery` — a cached entry can never authorise the cast.
- **Security**: `securityVerdictOf()` maps the existing Trust → Legitimacy outcome (`Deferred` / `Investigate` → `ReviewRequired`, Q2). The chain's own pre-lock denial stays in place (E1 contract R1-A), so the verdict reaching `forCast()` is normally `Allowed`; the in-transaction decision still carries it.
- **`refuseCast()`** maps each reason to a response and logs voter-side facts only (`user_id`, `election_id`, `reason` — no vote exists on this path, I8). Existing messages are kept: already voted (dashboard redirect), wrong code (`voting_code` field), the two security messages. New reasons use the `vote` field.
- **`validateVotingCodeInput()`** holds the unchanged rules and messages of the input validation; it is called at the original place in `store()` and in the `CodeMismatch` branch, so a malformed code still gets "Code must be exactly 8 characters".
- The old real-election already-voted block is gone — `AlreadyVotedPreCheck` replaces it with the same redirect and message.

### `vote_post_check()` — demo only (option A)

```php
if ($election->type !== 'real') {
    $pre_check = $this->vote_post_check($auth_user, $code, $vote_data);
}
```

Every responsibility of `vote_post_check()` is covered for real elections: no code → `CodeMissing`; already voted → `AlreadyVotedPreCheck`; no ballot → `BallotMissing`; IP count → the Trust → Legitimacy chain (P4-D1 = B — the config-based count is deliberately **not** carried into `forCast()`). Keeping the call would have kept a second IP check that only crashes, and D10. Demo keeps the unchanged call; the method itself and the Demo / Deligate controllers' copies are untouched. (Demo elections normally use `DemoVoteController::store`; this branch is reachable only by a direct POST.)

## Behaviour change to know

- With `MAX_USE_IP_ADDRESS` unset, real votes no longer crash at the final cast (D10). Code entry still reads the setting until Phase 5/6 — the ops item stays open.
- A code whose code step was not completed is now **refused** (`CodeStepIncomplete`) instead of accepted.
- `can_vote_now` is only **read**; its meaning is unchanged.

## Testing

- `tests/Feature/Voting/CastDecisionEnforcementTest.php` — R3-1 BallotMissing, R3-2 CodeStepIncomplete, R3-3 CodeMissing (were RED), R3-4 CodeMismatch, R3-5 GUARD already voted, R3-7 GUARD (source): every `$this->vote_post_check(` in `store()` sits directly inside the demo-only guard, and `store()` calls `->forCast(`.
- R3-3 uses `forceDelete()`: `Code` is soft-deletable and `store()` loads it `withoutGlobalScopes()` — a soft-deleted code is still usable (**PBDIGIT-235**, out of scope).
- Phase 1 `test_i_a_failing_pre_check_…` (asserted the missing-view crash) retired — replaced by R3-1.
- Regression vs `d4d6b2890` (§6.1 set): 0 previously passing tests fail; pre-existing failures unchanged.

## Pitfalls

- Do not call `vote_post_check()` on the real path again, or add an IP check to `forCast()` — the trust chain is the one security authority (I3).
- Do not inject the cached `VotingEntitlementQuery` here.
- Do not log a refusal reason together with a vote id or receipt (I8).
- `AlreadyVotedPreCheck` is not authoritative — the compare-and-set on `codes.has_voted` (E4) is.

## Anonymity (interim 7-point rule)

Voter / process side only; the refusal log carries no ballot identifier; no field, timestamp, relation or derived value is added to the ballot. Passes all seven points.

**Traceability:** ADR_20261005_0950 §3.2–3.5, I3, I4, I8 · plan §2.2 E3 / §4 R3 · decision record §2, §2c · PBDIGIT-233, 219, 235 · RED `a818f2514`.
