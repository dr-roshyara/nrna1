# PBDIGIT-316: 14 voting-security unit tests fail on the current code

**Type:** test debt · **Epic:** none · **Created:** 2026-10-26 · **Raised by:** found while verifying PBDIGIT-315.

| | |
|---|---|
| **Status** | `OPEN` · not analysed |
| **Classification** | **observed:** the failures are the same with and without PBDIGIT-315 |
| **Scope** | `tests/Unit/Domain/Election/Security` |

## Observed (2026-10-26, `php artisan test tests/Unit/Domain/Election/Security`)
- `D5ResolverIntegrationTest`, 8 failures: `TypeError`. `Simplified\EvaluationEnvelope::__construct()` argument #2 must be a `ConstitutionalObservationContext`, but the tests pass an `OverlaySignal`. The tests use an older constructor.
- `OverlaySignalTest`, 5 failures: `Error`, not analysed.
- `SovereigntyConvergenceFitnessTest`, 1 failure (`f4 legitimacy outcome not used outside constitutional path`): "actual size 3 matches expected size 0". Three uses were found outside the allowed path.

## Next
- Read-only analysis: are the tests stale, or is the code wrong? This matters most for the fitness test.
- Then a plan per group.
