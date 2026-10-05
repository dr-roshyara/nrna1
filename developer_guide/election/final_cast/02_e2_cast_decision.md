# E2 — `forCast()` → `CastDecision`

**Phase 4 · E2** · **RED:** `f84e72ece` · **Plan:** §2.1, §4 R2 · **Decisions:** decision record §2c (E2-D1 … E2-D6)

## Purpose

One application service answers *"is this final-cast attempt allowed and valid now?"* — fresh authorization plus process validation (ADR_20261005_0950 §3.2). It composes decisions that already exist; it adds no new authority, stores nothing and never decides "already voted" authoritatively (that is the compare-and-set in E4).

E2 delivers the service and its value types only. **Nothing calls it yet** — `VoteController::store` adopts it in E3.

## Where it fits

| Class | Layer | Namespace |
|---|---|---|
| `VotingAuthorization` | Application service | `App\Contexts\Elections\Application\Services` |
| `CastAttempt`, `CastDecision` | Application DTOs (`final readonly`) | `App\Contexts\Elections\Application\DTO` |
| `CastDecisionReason`, `SecurityVerdict` | Domain value objects (enums) | `App\Contexts\Elections\Domain\ValueObjects` |

All five are framework-free. The `DTO` / `Services` folders are approved for E2 only (E2-D5), not as a general rule for the context.

## How it works

```php
final class VotingAuthorization
{
    public function __construct(
        private readonly VotingEntitlementQuery $entitlement,
    ) {}

    public function forCast(CastAttempt $attempt): CastDecision
    {
        return new CastDecision($this->firstFailure($attempt) ?? CastDecisionReason::Allowed);
    }
    // …
}
```

First failure wins, in a fixed order:

1. **Entitlement** — `VotingEntitlementQuery::decide()` (standing ∧ lifecycle), called **exactly once**, never `standingOf()` (E2-D2). A failure is forwarded 1:1: `CastDecisionReason::fromEntitlement()` — the six forwarded cases share the names and values of `VotingEntitlementReason`.
2. **Security** — `SecurityVerdict` is a *fact* produced by the existing Trust → Legitimacy chain (the caller maps its `LegitimacyOutcome`). `Denied` → `SecurityDenied`; `ReviewRequired` (Deferred / Investigate) → `SecurityReviewRequired`, a denial (Q2). No sub-reason is carried (E2-D4, option A) — the detail stays in that chain's own log.
3. **Process** — in this order, which is a contract (E2-D1, today's `store()` sequence):

```php
return match (true) {
    ! $attempt->codeExists           => CastDecisionReason::CodeMissing,
    $attempt->alreadyVotedPreCheck   => CastDecisionReason::AlreadyVotedPreCheck,
    ! $attempt->submittedCodeMatches => CastDecisionReason::CodeMismatch,
    ! $attempt->codeStepCompleted    => CastDecisionReason::CodeStepIncomplete,
    ! $attempt->ballotPresent        => CastDecisionReason::BallotMissing,
    default                          => null,
};
```

### The vocabulary — exactly 14 (E2-D3)

`Allowed` · `NotRegistered`, `NotVoterRole`, `OrganisationMismatch`, `NotActive`, `NotAdmitted`, `ElectionNotVotable` · `SecurityDenied`, `SecurityReviewRequired` · `CodeMissing`, `AlreadyVotedPreCheck`, `CodeMismatch`, `CodeStepIncomplete`, `BallotMissing`.

Deliberately absent: `AgreementMissing` (Q5 = A), any IP-density reason (P4-D1 = B — the trust chain is the single density authority), "participation already consumed" (a CAS outcome, not a decision).

### `CastAttempt` facts

`voterId`, `electionId`, `security`, `codeExists`, `submittedCodeMatches`, `codeStepCompleted` (= `Code.can_vote_now`, read only), `alreadyVotedPreCheck` (= `Code.has_voted`, read only), `ballotPresent`. The caller gathers them inside the cast transaction (E3).

## How to use / extend

```php
$decision = $votingAuthorization->forCast(new CastAttempt(
    voterId: $user->id,
    electionId: $election->id,
    security: $verdict,               // mapped from the existing LegitimacyOutcome
    codeExists: $code !== null,
    submittedCodeMatches: $matches,
    codeStepCompleted: (bool) $code?->can_vote_now,
    alreadyVotedPreCheck: (bool) $code?->has_voted,
    ballotPresent: $ballot !== null,
));

if (! $decision->isAllowed()) {
    // fail closed (E1's failCastClosed()), message by $decision->reason
}
```

`Allowed` alone never commits a vote: the commit also needs the CAS to change exactly one row, the insert to succeed and the transaction to commit (I1).

Adding a reason, reordering the process checks, or reading `standingOf()` changes an approved contract — it needs an explicit architectural decision first, and the R2 test will fail on purpose.

## Testing

`php artisan test --env=testing tests/Unit/Contexts/Elections/VotingAuthorizationForCastTest.php` — 16 tests, 53 assertions; a plain PHPUnit test with an entitlement-port double that records its calls. RED 16/16 (`f84e72ece`) → GREEN 16/16 with the test unchanged. The architecture / fitness test (R2a) is deferred to a separate review (E2-D6).

## Pitfalls

- Do not inject the **cached** entitlement adapter at the cast: E3 must bind the uncached `EloquentVotingEntitlementQuery` (ADR §3.5 — fresh, in transaction).
- Do not log a `CastDecision` reason together with a vote id or receipt (I8).
- `forCast()` is not called for demo elections (Q4).

## Anonymity (interim 7-point rule)

Voter / process side only: no field, timestamp, relation, log or derived value touches the ballot (I10). Passes all seven points.

**Traceability:** ADR_20261005_0950 §3.0, §3.2, §3.3, I1, I5, I10 · plan §2.1 / §4 R2 · decision record §2c · RED `f84e72ece`.
