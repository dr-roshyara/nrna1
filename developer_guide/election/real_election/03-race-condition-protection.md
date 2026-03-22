# 03 — Race Condition Protection

**Implemented:** 2026-03-19

---

## The Race Condition

```
Time ──────────────────────────────────────────────────────────────────▶

  Voter                          Admin
    │                              │
    │── GET /v/{slug}/vote/verify ─▶ Layer 0h (fresh check) ✓ PASS
    │                              │
    │                              │── DELETE /elections/{id}/voters/{member}
    │                              │   Voter's membership → status='removed'
    │                              │
    │── POST /v/{slug}/vote/verify ─▶ Layer 0i (fresh check) ✗ BLOCK ← protected
    │
```

Without fresh DB checks on `verify()` and `store()`, a 5-minute cache result from
`first_submission` would let the voter complete the vote after being removed.

---

## How It Is Prevented

### Fresh DB check on verify() and store()

Both methods skip the 5-minute `User::isVoterInElection()` cache and query directly:

```php
ElectionMembership::where('user_id', $user->id)
    ->where('election_id', $election->id)
    ->where('role', 'voter')
    ->where('status', 'active')
    ->exists();
```

This runs on every request to these two endpoints, regardless of cache.

### Row lock on destroy()

`ElectionVoterController::destroy()` acquires a `SELECT ... FOR UPDATE` lock before
changing the membership status:

```php
DB::transaction(function () use ($membership) {
    $locked = ElectionMembership::where('id', $membership->id)
        ->lockForUpdate()
        ->firstOrFail();

    $locked->remove('Removed by ' . auth()->user()->name, auth()->user());
});
```

`VoteController::store()` also runs inside a `DB::beginTransaction()`. When both
operations target the same `election_memberships` row simultaneously, the database
serialises them — one will wait for the other to commit.

**Scenario A — Admin wins the lock:**
Admin removes the voter first → voter's `store()` runs its fresh check → sees
`status='removed'` → Layer 0i blocks the vote.

**Scenario B — Voter wins the lock:**
Voter's `store()` commits the vote first → admin's removal proceeds → vote was valid
at the time it was cast, removal takes effect going forward.

Both outcomes are correct and deterministic.

---

## Audit Trail

Every Layer 0 block is logged to the `voting_security` channel:

```php
Log::channel('voting_security')->warning('⛔ Layer 0 (controller): Voter membership check failed', [
    'user_id'     => $user->id,
    'election_id' => $election->id,
    'use_cache'   => $useCache,
    'url'         => request()->fullUrl(),
    'ip'          => request()->ip(),
]);
```

Every admin removal of a voter from an **active** election is logged at `critical` level:

```php
Log::channel('voting_security')->critical('Voter removed from ACTIVE election', [
    'user_id'     => $this->user_id,
    'election_id' => $this->election_id,
    'reason'      => $reason,
    'removed_by'  => $removedBy?->email,
    'timestamp'   => now()->toIso8601String(),
]);
```

**Log location:** `storage/logs/voting_security-{YYYY-MM-DD}.log`
**Retention:** 365 days (configured in `config/logging.php`)

---

## Remaining Limitations

| Scenario | Protected? | Notes |
|----------|-----------|-------|
| Voter not assigned tries `code/create` | ✅ Yes | Layer 0a middleware blocks |
| Voter removed before `verify()` | ✅ Yes | Layer 0h fresh check |
| Voter removed during `store()` | ✅ Yes | Layer 0i fresh check + row lock |
| Voter removed after vote cast | ✅ N/A | Vote already recorded (correct behaviour) |
| Admin removes voter in <5 min of `first_submission` | ✅ Yes | Fresh check on verify/store |
| Two admins concurrently assigning/removing same voter | ⚠️ Partial | `assignVoter()` uses 3-retry transaction; remove uses row lock; edge cases possible under extreme concurrency |
