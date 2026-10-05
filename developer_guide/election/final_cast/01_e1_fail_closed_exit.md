# E1 — The final cast fails closed

**Story:** PBDIGIT-232 · **Commit:** `7aa33df68` · **Plan:** §2.0, §2.2 E1, §4 R1

## Purpose

Before E1, two failure paths of `VoteController::store` ended in **HTTP 500**:

1. every security, lifecycle or membership **denial** called `trackSovereigntyDivergence(..., ?int $userId)` with the voter's UUID → `TypeError`;
2. any `\Error` (not `\Exception`) inside the cast escaped `catch (\Exception)`.

Neither wrote a vote, but both failed closed only by accident: no reason for the voter, no rollback by the code itself, and the slug lock released (or not) by chance. E1 makes every exit deliberate.

## Where it fits

Interface / HTTP layer — `app/Http/Controllers/VoteController.php`. No domain or application class changes; the authorization architecture (`forCast()`) is E2/E3.

## Key files

| File | Role |
|---|---|
| `app/Http/Controllers/VoteController.php` | `store()`, `failCastClosed()`, `trackSovereigntyDivergence()` |
| `tests/Feature/Voting/CastFailClosedTest.php` | contracts R1-A … R1-D |
| `tests/Feature/Characterization/Phase1/FinalDenialCharacterizationTest.php` | G2 (asserted the old 500) retired — replaced by R1-A |

## The rule (plan §2.0)

> **Roll back only when a transaction is active, and release the cache lock only when this execution path actually acquired the lock.**

## How it works

`store()` records two facts before it begins its transaction:

```php
$outerTransactionLevel = DB::transactionLevel();
$lock = null;
$lockAcquired = false;

DB::beginTransaction();
```

Lock acquisition stores its result instead of testing it inline:

```php
$lock = Cache::lock($lockKey, 10);
$lockAcquired = $lock->get();

if (!$lockAcquired) {
    // Another execution owns the lock — roll back, never release it.
    $this->failCastClosed($outerTransactionLevel, $lock, $lockAcquired);
    return back()->with('error', 'Please wait, your vote is being processed. Another submission is in progress.');
}
```

Every early return and both catch blocks leave through one helper:

```php
private function failCastClosed(int $outerTransactionLevel, ?\Illuminate\Contracts\Cache\Lock $lock, bool &$lockAcquired): void
{
    if (DB::transactionLevel() > $outerTransactionLevel) {
        DB::rollBack();
    }

    if ($lockAcquired && $lock !== null) {
        $lock->release();
        $lockAcquired = false;
    }
}
```

- **Transaction:** "active" means *the cast's own* transaction — the level is compared with the level *outside* it, so a branch that already rolled back (e.g. `ensureVoterMembership(inTransaction: true)`) is not rolled back twice, and a surrounding transaction (the test's `RefreshDatabase`) is never touched.
- **Lock:** `$lockAcquired` is passed by reference and cleared on release, so a lock is released **exactly once** — the success path also clears it after its own `release()`.
- **`catch (\Throwable)`:** an `\Error` now reaches the same exit and the generic "An error occurred…" message.

The divergence tracker takes the id type the application actually uses:

```php
?string $userId, // user ids are UUIDs (PBDIGIT-232: ?int raised a TypeError on every denial)
```

## Exit matrix

| Exit | Lock state | Rolls back | Releases |
|---|---|---|---|
| security / lifecycle / no user denial | not taken | ✓ | — |
| lock refused (another holder) | held by someone else | ✓ | **never** |
| already voted, wrong code, validation error, `\Throwable` before commit | acquired here | ✓ | ✓ once |
| `\Throwable` after commit (e.g. audit logging) | already released | — (no active cast transaction) | — |
| IP-binding mismatch (`validateVotingIpWithResponse`) | — | **unchanged** — branch unreachable (no `users.voting_ip` column, PBDIGIT-234; P4-D4 retired) | — |

## Testing

`php artisan test --env=testing tests/Feature/Voting/CastFailClosedTest.php` — 4/4:

- **R1-A** denial before the lock while another holder owns it → redirect with a `vote` error, no 500, transaction back at baseline, the other holder still owns its lock (was RED);
- **R1-B** refused acquisition → the holder keeps its lock (guard);
- **R1-C** wrong code after acquisition → own lock released (guard);
- **R1-D** an injected `\Error` (notification dispatcher double) after acquisition → rollback, generic error, no vote, no receipt, lock released (was RED).

Each test compares `DB::transactionLevel()` with its own baseline taken before the request. Synthetic data from the Phase 1 `CastFixtures` trait.

## Pitfalls

- **Do not** reintroduce `DB::rollBack()` / `$lock->release()` pairs in new branches — call `failCastClosed()`.
- **Do not** release a lock because a lock *object* exists (`isset($lock)` was the old bug-prone pattern); only `$lockAcquired` decides.
- **Side effect worth knowing:** a voter without a `Code` (PBDIGIT-219) now gets the generic error instead of a 500 — the reasoned `CodeMissing` refusal arrives with E3.
- A `\Throwable` *after* `DB::commit()` still reports the generic error although the vote is committed — unchanged behaviour from the `\Exception` era; the rollback guard prevents any attempt to undo it.

## Anonymity (interim 7-point rule)

E1 adds no field, timestamp, log line, relation or derived value on either side; denial logs are unchanged and no vote id exists on a denial path. Passes all seven points.

**Traceability:** ADR_20261005_0950 §3.4, I7 · plan §2.0 / §2.2 E1 / §4 R1 · PBDIGIT-232 · commit `7aa33df68`.
