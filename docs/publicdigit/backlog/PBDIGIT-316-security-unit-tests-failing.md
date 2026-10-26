# PBDIGIT-316: 14 voting-security unit tests fail on the current code

**Type:** test debt · **Epic:** none · **Created:** 2026-10-26 · **Raised by:** found while verifying PBDIGIT-315.

| | |
|---|---|
| **Status** | `OPEN` · causes found (read-only, 2026-10-26); no fix authorised |
| **Classification** | **observed:** the failures are the same with and without PBDIGIT-315 |
| **Scope** | `tests/Unit/Domain/Election/Security` |

## Observed (2026-10-26, `php artisan test tests/Unit/Domain/Election/Security`)
- `D5ResolverIntegrationTest`, 8 failures: `TypeError`. `Simplified\EvaluationEnvelope::__construct()` argument #2 must be a `ConstitutionalObservationContext`, but the tests pass an `OverlaySignal`. The tests use an older constructor.
- `OverlaySignalTest`, 5 failures: `Error`, not analysed.
- `SovereigntyConvergenceFitnessTest`, 1 failure (`f4 legitimacy outcome not used outside constitutional path`): "actual size 3 matches expected size 0". Three uses were found outside the allowed path.

## Causes (read-only analysis, 2026-10-26)
All three groups are **stale tests**, not wrong voting behaviour.

| Group | Cause | Since |
|---|---|---|
| `OverlaySignalTest` (5) | imports `App\Domain\Election\Security\OverlaySignal`, which no longer exists there. The class lives in `…\Security\Simplified\OverlaySignal` | `89914ce3a` (2026-05-29) |
| `D5ResolverIntegrationTest` (8) | calls `Simplified\EvaluationEnvelope` with an `OverlaySignal` where the constructor now takes a `ConstitutionalObservationContext` | `89914ce3a` (2026-05-29) |
| `SovereigntyConvergenceFitnessTest` F4 (1) | its allow-list for `LegitimacyOutcome` predates the Security Verdict Authority. The 3 "violations" are `app/Contexts/Elections/…/TrustLegitimacySecurityVerdict.php`, `…/DTO/SecurityAssessment.php` and `…/ValueObjects/SecurityVerdict.php` | PBDIGIT-239/240 (2026-10-05) |

## Next (needs authorisation)
- **OverlaySignal / D5:** update the tests to the current classes, or delete them if `Simplified` already covers them.
- **F4:** add the Security Verdict Authority to the allow-list. That files are moved into an allowed folder is an architecture decision (PBDIGIT-239 made the authority the one legitimacy consumer), not a test edit, so it needs an explicit decision.
