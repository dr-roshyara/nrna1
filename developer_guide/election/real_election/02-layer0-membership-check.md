# 02 — Layer 0: Membership Check Implementation

**Implemented:** 2026-03-19

---

## What Was Changed

Five files were modified and two were created to wire Layer 0 into the voting engine.

### Files Modified

| File | Change |
|------|--------|
| `app/Http/Middleware/EnsureElectionVoter.php` | `resolveElection()` reads `$request->attributes` first |
| `routes/election/electionRoutes.php` | `ensure.election.voter` inserted into slug route chain |
| `app/Http/Controllers/CodeController.php` | Trait added; Layer 0 check in 4 methods |
| `app/Http/Controllers/VoteController.php` | Trait added; Layer 0 check in 4 methods |
| `app/Models/ElectionMembership.php` | `remove()` enhanced with `$removedBy` + audit log |
| `app/Http/Controllers/ElectionVoterController.php` | `destroy()` wrapped in transaction + row lock |

### Files Created

| File | Purpose |
|------|---------|
| `app/Traits/EnsuresVoterMembership.php` | Shared Layer 0 trait for controllers |
| `tests/Feature/Integration/VotingMembershipIntegrationTest.php` | Integration test suite (6 tests) |

---

## Critical Fix: resolveElection() for Slug Routes

The `v/{vslug}` route group has **no `{election}` route parameter**. The election model
is resolved by `VerifyVoterSlugConsistency` and stored in `$request->attributes['election']`.

The original `resolveElection()` only checked route parameters — it could never find the
election on slug routes, causing the middleware to `abort(404)` on every request.

**Fix** (`app/Http/Middleware/EnsureElectionVoter.php`):

```php
private function resolveElection(Request $request): ?Election
{
    // 1. Request attributes — set by VerifyVoterSlugConsistency for slug routes
    //    (slug routes have no {election} route parameter, so this must come first)
    $fromAttributes = $request->attributes->get('election');
    if ($fromAttributes instanceof Election) {
        return $fromAttributes;
    }

    // 2. Route parameters — for org-prefixed routes with explicit {election}
    $value = $request->route('election')
        ?? $request->route('electionId')
        ?? $request->route('id');

    if (! $value) {
        return null;
    }

    if ($value instanceof Election) {
        return $value;
    }

    return Election::withoutGlobalScopes()->find($value);
}
```

**Why `withoutGlobalScopes()`?** The `BelongsToTenant` global scope filters by
`session('current_organisation_id')`. In middleware, the `TenantContext` middleware may
not have run yet, so this scope could filter out valid elections.

---

## Middleware Position in the Chain

The middleware must sit **after** `voter.slug.consistency` (which sets the election in
attributes) and **before** `voter.step.order` (fail-fast principle — don't run step
checks for someone who shouldn't be there at all).

**`routes/election/electionRoutes.php` (line 362):**

```php
Route::prefix('v/{vslug}')
    ->middleware([
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'voter.slug.verify',
        'voter.slug.window',
        'voter.slug.consistency',  // ← sets election in request attributes
        'ensure.election.voter',   // ← Layer 0: membership check (NEW)
        'voter.step.order',
        'vote.eligibility',
        'validate.voting.ip',
        'vote.organisation',
    ])
    ->group(function () { ... });
```

> **Important:** Do NOT add `ensure.election.voter` to the demo route group
> (`v/{vslug}/demo-*` routes, lines 421–457). Demo elections bypass the check entirely.

---

## The EnsuresVoterMembership Trait

**`app/Traits/EnsuresVoterMembership.php`**

All 8 controller methods share a single trait method:

```php
protected function ensureVoterMembership(
    Election $election,
    User $user,
    bool $useCache = true,
    bool $inTransaction = false
): ?RedirectResponse
```

| Parameter | Default | When to change |
|-----------|---------|----------------|
| `$useCache` | `true` | Set to `false` for `verify()` and `store()` — fresh DB required |
| `$inTransaction` | `false` | Set to `true` for `store()` — open transaction must be rolled back |

### Return value

- `null` → user is eligible, proceed normally
- `RedirectResponse` → user is blocked, return the redirect immediately

### Usage pattern

```php
// Early steps — cache acceptable
if ($redirect = $this->ensureVoterMembership($election, $user)) {
    return $redirect;
}

// Final vote store — fresh check + inside active transaction
if ($redirect = $this->ensureVoterMembership($election, $auth_user, false, true)) {
    return $redirect;
}
```

### What happens on block

1. Logs a `warning` to the `voting_security` channel (365-day retention)
2. If `$inTransaction = true` and a transaction is open, calls `DB::rollBack()`
3. Redirects to `dashboard` with an `error` flash

---

## CodeController Layer 0 Coverage

Four methods in `app/Http/Controllers/CodeController.php` each have a Layer 0 check
immediately after `$user` and `$election` are resolved:

| Method | Step | Cache |
|--------|------|-------|
| `create()` | 1 — Enter code | 5 min |
| `store()` | 1 — Submit code | 5 min |
| `showAgreement()` | 2 — View agreement | 5 min |
| `submitAgreement()` | 2 — Accept agreement | 5 min |

---

## VoteController Layer 0 Coverage

Four methods in `app/Http/Controllers/VoteController.php`:

| Method | Step | Cache | Notes |
|--------|------|-------|-------|
| `create()` | 3 — View ballot | 5 min | |
| `first_submission()` | 3 — Submit ballot | 5 min | |
| `verify()` | 4 — Verify selections | **Fresh** | Voter may be removed before this step |
| `store()` | 4 — Confirm vote | **Fresh** | Inside `DB::beginTransaction()` at line 1279 |

The `store()` method is the most critical. It opens a `DB::beginTransaction()` at line 1279.
The Layer 0 check runs inside this transaction. If the voter is ineligible, the trait calls
`DB::rollBack()` (guarded by `DB::transactionLevel() > 0`) before returning the redirect.

---

## ElectionMembership::remove() Enhancement

The `remove()` method now accepts an optional `$removedBy` user and logs a critical
alert if the election is currently active:

```php
public function remove(?string $reason = null, ?User $removedBy = null): void
{
    $this->update([
        'status'   => 'removed',
        'metadata' => array_merge($this->metadata ?? [], [
            'removed_at'       => now()->toIso8601String(),
            'removed_reason'   => $reason,
            'removed_by'       => $removedBy?->id,
            'removed_by_email' => $removedBy?->email,
        ]),
    ]);

    if ($this->election && $this->election->status === 'active') {
        Log::channel('voting_security')->critical('Voter removed from ACTIVE election', [...]);
    }
}
```

Callers should pass `auth()->user()` as `$removedBy`:

```php
$membership->remove('Reason text', auth()->user());
```

---

## ElectionVoterController::destroy() Row Lock

The `destroy()` method is now wrapped in a transaction with `lockForUpdate()`:

```php
DB::transaction(function () use ($membership) {
    $locked = ElectionMembership::where('id', $membership->id)
        ->lockForUpdate()
        ->firstOrFail();

    $locked->remove('Removed by ' . auth()->user()->name, auth()->user());
});
```

**Why the lock matters:** If a voter's `VoteController::store()` is simultaneously holding
an open transaction (which it always is — `DB::beginTransaction()` is line 1279), then
`lockForUpdate()` on the same membership row will block until one side commits. This
eliminates the race window between the Layer 0i fresh check and the final vote write.
