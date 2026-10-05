# 02 — Entry authorization `forEntry()` (PBDIGIT-239 Stage A / B)

**Story:** PBDIGIT-239 · **Blueprint:** `ca9629b99` (`docs/publicdigit/reviews/2026-10-05-phase5-round2-forentry-blueprint.md`) · **RED:** `430d28742` · **Stage A:** `0578091f8` · **Stage B:** `d90131361` · **Stage C:** deferred → PBDIGIT-240

## Purpose

Before PBDIGIT-239 a code was created and e-mailed with no fresh decision about the voter: cached middleware checked *standing*, a lifecycle check was skipped whenever the session carried `selected_election_type = demo` (F-C2), and the only IP check was a duplicate count inside `verifyCode()`. The approved ADR already defined the answer — `VotingAuthorization::forEntry()` (ADR §3.1). There is **no** separate `PreVotingGate` or `PreVotingDecision`: "pre-voting gate" is just the business name of `forEntry()`.

## Where it fits

```
forEntry(EntryAttempt) → EntryAuthorization
  E1  VotingEntitlementQuery::decide()   standing incl. admission + lifecycle — fresh, exactly once (I2)
  E2  the SecurityVerdict in the attempt  supplied by the ONE Security Verdict Authority (I3)
Process guards outside it: code value · code validity window · early has_voted · final CAS · ValidatedBallot (VE-01)
```

## Key files

| File | Layer | Role |
|---|---|---|
| `app/Contexts/Elections/Application/Services/VotingAuthorization.php` | Application | `forEntry()` beside `forCast()` — pure composition, no state |
| `app/Contexts/Elections/Application/DTO/EntryAttempt.php`, `EntryAuthorization.php` | Application | input / result |
| `app/Contexts/Elections/Domain/ValueObjects/EntryAuthorizationReason.php` | Domain | 9 authorization reasons, values shared with `CastDecisionReason` |
| `app/Contexts/Elections/Application/Ports/VotingSecurityVerdictQuery.php` | Application port | the only source of the E2 fact |
| `app/Contexts/Elections/Application/DTO/SecurityAttemptContext.php` | Application | user, election, client IP, device fingerprint, session id |
| `app/Contexts/Elections/Infrastructure/Security/TrustLegitimacySecurityVerdict.php` | Infrastructure | the adapter over the existing Trust → Legitimacy chain |
| `app/Http/Controllers/CodeController.php` | HTTP | `refuseEntry()` and the two call sites |

## Design decisions

- **One Security Verdict Authority (Stage A).** The adapter is an *extraction* of the final cast's inline assessment. Invariant: for every characterized valid context it produces exactly the verdict of the inline Phase-4 path, with no evidence, policy, threshold, tenant or exception semantics added or removed. The only transformations: request IP / fingerprint / session id / user / election come from the context.
- **E2 before E1, on purpose.** The caller gets the verdict from the port and hands it to `forEntry()`; `forEntry()` never obtains or manufactures one (its constructor depends on the entitlement port only). So an entitlement denial may still produce security telemetry.
- **Tenant (decision (a), transitional).** The density count is tenant-scoped, as in the final cast. `create()` keeps its existing tenant binding before the verdict; `store()` now binds the election's organisation itself (request context, not authorization) instead of relying on an earlier request. The adapter binds no tenant.
- **`store()` validates input first** — no security evaluation for a malformed code.

## How it works

```php
public function forEntry(EntryAttempt $attempt): EntryAuthorization
{
    $entitlement = $this->entitlement->decide($attempt->voterId, $attempt->electionId);
    if (! $entitlement->isEntitled()) {
        return new EntryAuthorization(EntryAuthorizationReason::fromEntitlement($entitlement));
    }

    return new EntryAuthorization(match ($attempt->security) {
        SecurityVerdict::Denied         => EntryAuthorizationReason::SecurityDenied,
        SecurityVerdict::ReviewRequired => EntryAuthorizationReason::SecurityReviewRequired,
        SecurityVerdict::Allowed        => EntryAuthorizationReason::Allowed,
    });
}
```

In `CodeController::refuseEntry()` (called in `create()` after the tenant binding, and in `store()` after validation):

```php
$verdict = app(VotingSecurityVerdictQuery::class)->verdictFor(new SecurityAttemptContext(
    userId: $user->id,
    electionId: $election->id,
    clientIp: (string) $request->ip(),
    deviceFingerprint: is_string($fingerprint) ? $fingerprint : null,
    sessionId: (string) $request->session()->getId(),
));

$authorization = (new VotingAuthorization(app(EloquentVotingEntitlementQuery::class))) // fresh (ADR §3.5)
    ->forEntry(new EntryAttempt(voterId: $user->id, electionId: $election->id, security: $verdict));
```

A denial redirects to the dashboard with a per-reason message; any `\Throwable` gives a generic refusal — never HTTP 500, no code created, verified or sent. Log lines carry ids and the reason only.

## How to use / extend

- Another entry point that lets a voter enter the voting journey calls the same pair — port, then `forEntry()` — after establishing the election's tenant. Never compose your own security check.
- New entitlement or admission rules belong in `VotingEntitlement` (E1), new security rules in the Trust → Legitimacy chain (E2) — not in `forEntry()` or the controller.

## Testing

```bash
vendor/bin/phpunit tests/Unit/Contexts/Elections/VotingAuthorizationForEntryTest.php
php artisan test tests/Feature/Voting/VotingSecurityVerdictAdapterTest.php
php artisan test tests/Feature/Voting/CodeEntryAuthorizationTest.php
```

`CodeEntryAuthorizationTest` swaps the port for a recording double (`$this->app->instance(VotingSecurityVerdictQuery::class, …)`) to observe calls, context and the tenant at evaluation; one test uses the real adapter. The request IP is fixed with `withServerVariables(['REMOTE_ADDR' => …])`.

## Pitfalls

- **Intended behaviour change:** the existing IP-density rule (`elections.max_votes_per_ip`, halved for unverified voters — 3 by default) now also refuses at entry, before a code is created. Same policy that already refused at the final cast — moved earlier, not new.
- **Two IP limits still exist** until 2B: `verifyCode()` keeps its own count (`>= MAX_USE_IP_ADDRESS`, default 7). Removing it waits for Q3.
- **Known whitelist defects** (`ip_whitelist` cast to array but `json_decode()`d; hashed IP compared with raw entries): a non-empty whitelist makes the chain throw — at entry that is a fail-closed refusal. Not fixed here.
- **VoteController still has its own inline assessment** — it needs the four-valued outcome and the trust envelope (PBDIGIT-240). If you change that block, update the oracle in `VotingSecurityVerdictAdapterTest` in the same change.
- The security event written by `evaluate()` now also occurs at the code step (voter-side; no ballot data, no code value).

**Traceability:** PBDIGIT-239 · PBDIGIT-240 · ADR_20261005_0950 §3.0, §3.1, §3.5, I2, I3 · audit VE-07, C1, F4, F-C2.
