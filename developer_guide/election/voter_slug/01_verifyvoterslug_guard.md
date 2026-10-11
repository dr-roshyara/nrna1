# 01: `VerifyVoterSlug`, the server-side voter-slug guard

**Story:** `PBDIGIT-273` · **Plan:** `docs/plans/20261011-1318-verifyvoterslug-hardening-plan.md` · **Evidence:** `docs/publicdigit/election/evidence/2026-10-11-verifyvoterslug/` · **Status:** implemented with database-free tests green; request-path tests written, not run; committed 2026-10-11 (`6d3b9e1e0`), not pushed; **merge to main and deploy only with `PBDIGIT-274`**.

## Purpose

A voter slug (`VoterSlug` for real elections, `DemoVoterSlug` for demo ones) is a voter's 30-minute voting session for **one** election. `App\Http\Middleware\VerifyVoterSlug` (alias `voter.slug.verify`) decides, on every `v/{vslug}/…` voting request, whether that session may be used. Nothing downstream should ever see a slug this guard hasn't accepted.

## Where it sits

```
web group (StartSession → TenantContext → SubstituteBindings: the {vslug} binding, RouteServiceProvider)
  → voter.slug.verify            ← this guard (V-1 … V-6)
  → voter.slug.window / voter.slug.consistency / ensure.election.voter / voter.step.order
  → vote.eligibility / vote.organisation (/ voting.active, election.demo)
```

A test pins this order (`test_every_slug_route_runs_the_guard_before_step_order_and_entitlement`). The only `{vslug}` route without the guard is the broken `test.voter.page` (`PBDIGIT-278`).

## The rules, in order

| # | Rule | Failure |
|---|---|---|
| V-1 | authenticated user, **before** any lookup | `AuthenticationException` |
| V-2 | slug type matches the route: routes carrying `election.demo` take a `DemoVoterSlug`, all others a `VoterSlug`; no fallback between the two | `InvalidVoterSlugException` (404) |
| V-3 | `(string) user_id === (string) auth()->id()` | `SlugOwnershipException` (403) |
| V-5 | not soft-deleted, `is_active`, `status !== 'expired'` | `InvalidVoterSlugException` |
| V-4 | valid only while `now < min(expires_at, created_at + voting.time_in_minutes)`; a missing timestamp counts as expired | `ExpiredVoterSlugException` (403) |
| V-6 | election exists and isn't deleted; organisation present and equal on slug and election; `isDemo()` equals the route type | `ElectionNotFoundException` (404), `OrganisationMismatchException`, `ElectionMismatchException` (500, see `PBDIGIT-275`) |

The verified slug is stored as the `voter_slug` request attribute **only after** all checks pass.

```php
// app/Http/Middleware/VerifyVoterSlug.php
private function isExpired(Model $voterSlug): bool
{
    if ($voterSlug->expires_at === null || $voterSlug->created_at === null) {
        return true;
    }
    $lifetimeEnd = $voterSlug->created_at->copy()->addMinutes((int) config('voting.time_in_minutes', 30));

    return now()->greaterThanOrEqualTo($voterSlug->expires_at) || now()->greaterThanOrEqualTo($lifetimeEnd);
}
```

## Design decisions
- **The fixed lifetime is capped at `created_at`** (Human PO, 2026-10-11). Code elsewhere that still rewrites `expires_at` can no longer lengthen a slug's life. The real fix, no in-place extension and replacement by a new row, is `PBDIGIT-274`.
- **The boundary is `>=`:** at exactly the deadline the slug is already expired. The `{vslug}` binding uses `isPast()` (strict), so at that exact instant the guard is the check that rejects.
- **Demo routes are marked by `election.demo`,** not by guessing. The binding picks the model by "demo" in the route name. A test runs the guard on the 19 **registered** routes to prove both classifications agree, and that `gatherMiddleware()` keeps the alias at runtime.
- **`withoutGlobalScopes()` is used deliberately** for the string lookup. It drops exactly two scopes: the `tenant` scope (`BelongsToTenant`) and soft-deletes (`VoterSlug`). Both are re-imposed explicitly: soft-delete by V-5, tenant by V-3 plus V-6. With the scopes on, a legitimate demo session would fail, because the session's tenant can differ from the slug's organisation.
- **No raw slug in this guard's logs or exception context;** the renderer logs that context. Only the slug's database id is used. The renderer itself still logs the URL (`PBDIGIT-276`).

## How to extend
- **New voting route:** put it in a group that carries `voter.slug.verify`. For demo, also put it under `election.demo` and give it a name containing `demo`. The route tests fail if the two disagree.
- **Changing the duration:** only `config('voting.time_in_minutes')` (`VOTING_TIME_IN_MINUTES`). Don't add another source.
- **Don't** add eligibility or step-order logic here. They live in `ensure.election.voter` / `vote.eligibility` and `voter.step.order`.

## Testing

```bash
vendor/bin/phpunit tests/Unit/Http/Middleware/VerifyVoterSlugTest.php          # database-free
# request path — ONLY on a dedicated test database (RefreshDatabase wipes it):
# vendor/bin/phpunit tests/Feature/Voting/VerifyVoterSlugRequestPathTest.php
```

The unit test deliberately extends `Illuminate\Foundation\Testing\TestCase`, not `Tests\TestCase`, whose `setUp` writes to the database. It uses `GenericUser` because building `App\Models\User` runs schema-introspection queries.

## Pitfalls
- **`ElectionVotingController::start` restarts a session on the old row** (old `created_at`), and can reuse a soft-deleted row. Under this guard both lock the voter out until `PBDIGIT-274`, so **don't deploy one without the other**.
- **`VoterSlugFactory`** sets no `election_id` / `organisation_id`. Pass them, or fixtures hit V-6.
- **In the unit tests, the production election lookup** (`Election::withoutGlobalScopes()->find`) is only exercised by the request-path test.

**Traceability:** `PBDIGIT-273` · Human PO decisions 2026-10-11 (fixed lifetime, `>=` boundary, cap now, scope, test database) · Principal Reviewer conditions 1–5 · follow-ups `PBDIGIT-274` … `278` (backlog only).
