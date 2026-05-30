# 🎯 Public Demo Results Page — Plan

**Date:** 2026-05-30
**Status:** Plan Phase

## Context

The `PublicDigitHeader` has a link to `/demo/result` (route `demo-result.index`), which shows **organisation-scoped** demo results. This requires authentication and an org session context — it doesn't work for anonymous visitors.

The platform has a **public demo** flow (`/public-demo/*`) that is fully anonymous via `PublicDemoSession`. Users complete a 5-step voting flow, get a receipt hash, and can verify their individual vote. But there is **no public aggregated results page** showing all votes cast in the demo election.

The public demo election belongs to the **Public Digit** organisation (the default platform org). All demo data (posts, candidates, votes) is stored under `organisation_id = 8c2a6070-...`. The existing `DemoResultController@indexGlobal` queries `organisation_id = NULL` records — it doesn't find the publicdigit org's data.

## What We Need

1. **Remove** the `/demo/result` link from `PublicDigitHeader` (both desktop nav and mobile menu)
2. **Add** a link to a new public demo results page — label: "Demo Results"
3. **Create** a new `PublicDemoController@publicResults` method that shows aggregated results for the public demo election
4. **Create** a new Vue component or reuse `Demo/Result/Index.vue` with a new public mode
5. **Route** at `/public-demo/results` (no auth middleware)
6. **TDD** — write tests first, then implement

## Architecture

### Backend — New method in PublicDemoController

```php
public function publicResults(): Response
{
    // 1. Resolve the public demo election (uses DemoElectionResolver)
    // 2. Load posts with candidates and vote counts
    // 3. Calculate results (copied pattern from DemoResultController)
    // 4. Return Inertia render
}
```

The demo election resolver `getPublicDemoElection()` already returns the correct election for the platform org. The existing `DemoResultController@getElectionResultsData()` logic calculates votes per candidate — reuse this pattern.

### Route — New public route

```php
Route::get('/public-demo/results', [PublicDemoController::class, 'publicResults'])->name('public-demo.results');
```

No auth middleware. Place it BEFORE the `{publicDemoSession}` parameterized routes to avoid slug conflict (same pattern as `/guide`).

### Frontend — Vue Component

Reuse `Demo/Result/Index.vue` with a new `mode: 'public'`. The component already handles:
- Stats dashboard (total votes, positions count)
- Per-post candidate results with vote counts and percentages
- Download PDF and Print buttons
- Empty state handling

Add a `'public'` mode to the ModeIndicator showing "Public Digit Demo Results".

### Header — Update links

- Remove lines 152-156 (desktop `/demo/result` link)
- Remove lines 252-256 (mobile `/demo/result` link)
- Add new link pointing to `route('public-demo.results')`

## Files to Modify

| File | Change |
|------|--------|
| `app/Http/Controllers/Demo/PublicDemoController.php` | Add `publicResults()` method |
| `routes/election/electionRoutes.php` | Add `/public-demo/results` route before `{publicDemoSession}` |
| `resources/js/Components/Jetstream/PublicDigitHeader.vue` | Replace `/demo/result` link with `route('public-demo.results')` |
| `resources/js/Pages/Demo/Result/Index.vue` | Add `'public'` mode support |
| `resources/js/Pages/Demo/Result/ModeIndicator.vue` | Add `'public'` mode variant |

## Files to Create

| File | Purpose |
|------|---------|
| `tests/Feature/PublicDemoResultsTest.php` | HTTP + feature tests for the new page |

## Test Plan (TDD)

Write tests first using `--env=testing`:

1. **Public demo results page loads without auth** — GET `/public-demo/results` returns 200
2. **Public demo results shows posts** — response contains post names
3. **Public demo results shows vote counts** — after casting votes, results show them
4. **Authenticated users can also access it** — same route works when logged in
5. **Header link points to correct route** — `route('public-demo.results')` resolves

## Verification

```bash
# Run the new tests
php artisan test --env=testing --filter=PublicDemoResults

# Manual check
# Visit /public-demo/results — should show aggregated results without login
# Header should show "Demo Results" link instead of the old /demo/result link
```
