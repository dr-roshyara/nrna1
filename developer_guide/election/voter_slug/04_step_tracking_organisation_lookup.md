# 04: Step tracking reads and writes by the slug's organisation (PBDIGIT-274)

**Story:** `PBDIGIT-274` · **Code:** `app/Services/VoterStepTrackingService.php` · **Tests:** `tests/Unit/Services/VoterStepTrackingServiceTenantTest.php`, `tests/Unit/Models/VoterSlugStepTenantScopeTest.php` (database-free); `tests/Isolated/Pbdigit274/Database/VoterSlugStepTenantIsolationTest.php` (real database; passed in the G5 run, 4 tests / 9 assertions).

## The invariant
A voter's completed steps are looked up by **the voter slug's own `organisation_id`**, never by the ambient tenant context (`TenantContext` or the session). So:
- the same step is never stored twice just because the current tenant differs from the slug's organisation;
- the step-order checks see the voter's real progress.

## How it works
Every step lookup in the service goes through one method:

```php
public function stepQuery($voterSlug, Election $election): Builder
{
    $StepModel = $voterSlug instanceof DemoVoterSlug ? DemoVoterSlugStep::class : VoterSlugStep::class;
    $table = (new $StepModel())->getTable();

    return $StepModel::withoutGlobalScope('tenant')
        ->where($table.'.organisation_id', $voterSlug->organisation_id)
        ->where('voter_slug_id', $voterSlug->id)
        ->where('election_id', $election->id);
}
```

- **Users:** `completeStep()` (the "already completed?" check), `getHighestCompletedStep()`, `hasCompletedStep()` and `getCompletedSteps()`. Indirectly also `getNextStep()`, `getNextStepRoute()` and `getStepTimeline()`.
- **What it removes:** only the `tenant` global scope (from `BelongsToTenant`). Exactly one explicit organisation filter takes its place, so this is no broad cross-tenant access.
- **Writes** still copy `organisation_id` from the slug (`completeStep`).

## Why
The `tenant` scope filters by `TenantContext::get() ?? session('current_organisation_id')`. When that differed from the slug's organisation, two things went wrong:
- the duplicate check missed the existing row, and with no unique constraint on `(voter_slug_id, election_id, step)` the step was stored again;
- the readers reported no progress.

## Pitfalls
- **New step queries go through `stepQuery()`.** Never use `VoterSlugStep::where(...)` directly in this service.
- **The model itself does not refuse a foreign `organisation_id` on create.** That is characterised in `VoterSlugStepTenantScopeTest`. Protection against cross-tenant writes comes from the request guards (`VerifyVoterSlug` ownership and organisation checks) before the service runs.
- **`stepQuery()` trusts `$voterSlug->organisation_id`.** Pass only slugs that the guard has already verified.

## Testing (database-free)
```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Services/VoterStepTrackingServiceTenantTest.php
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Models/VoterSlugStepTenantScopeTest.php
```
Both tests point `pgsql` at a non-existent socket, so nothing can reach a real database. The reader tests record the query `stepQuery()` built; the reader's own execution then fails on that socket. (`DB::pretend()` was tried and does open the connection for these calls.)

**Traceability:** `PBDIGIT-274`:
- fix of the duplicate check: `59ce21fbe`; tests: `6e01ce0c1`;
- readers switched to `stepQuery()`: `b1364337f`; tests: `e3d97b60f`;
- real-database test passed in the G5 run on 2026-10-12 (`…/2026-10-12-pbdigit274-g5-identity/`).
