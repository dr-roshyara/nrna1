# E4 — Atomic participation consumption

**Story:** PBDIGIT-230 · **RED:** `2afc6da5e` · **GREEN:** `b1daf26a2` · **Plan:** §2.2 E4, §4 R4–R6, §5, §7 · **Decision record:** §4 (evidence wording), E4 placement (PO)

## Purpose

"One voter, one vote" used to rest on an application cache: a `Cache::lock` per voter slug plus a plain re-read of `codes.has_voted`, and the vote was inserted **before** `has_voted` was set — by an **unconditional** update in `markUserAsVoted()`. A cast that read `has_voted = false` while a competing cast consumed the same code still wrote a second vote (E4 RED, R4-2).

E4 makes the database the guarantee: one **conditional** update consumes participation, immediately before the vote insert, in the cast transaction. It is the **only authoritative "already voted"** (ADR_20261005_0950 §3.2: not a decision — a database transition).

## Where it fits

`app/Http/Controllers/VoteController.php::store()`, real elections only, after every refusal and direct-return path and immediately before `save_vote()`:

```
forCast() → Allowed → IP-binding helper · input validation · code check (unchanged)
          → [E4] compare-and-set codes.has_voted false → true   (exactly 1 row, else refuse)
          → save_vote() (INSERT anonymous vote) → step 5 · markUserAsVoted · receipt · notify → COMMIT
any refusal / \Throwable → failCastClosed() → ROLLBACK (the consumption is undone with the vote)
```

**Why here and not right after `forCast()`:** between the decision and the insert, `store()` still has direct-return paths — notably `validateVotingIpWithResponse()`, which returns without a rollback. Consuming participation before them could end a request with `has_voted` consumed and no vote saved (PO review). Placed immediately before `save_vote()`, the consumption is followed only by the insert and post-save bookkeeping, all inside the transaction.

## How it works

```php
if ($election->type === 'real') {
    $consumed = DB::table('codes')
        ->where('id', $freshCode->id)
        ->where('has_voted', false)
        ->update([
            'has_voted' => true,
        ]);

    if ($consumed !== 1) {
        Log::channel('voting_audit')->warning(
            'Final cast refused: participation already consumed',
            [
                'user_id' => $auth_user->id,
                'election_id' => $election->id,
            ]
        );

        $this->failCastClosed(
            $outerTransactionLevel,
            $lock,
            $lockAcquired
        );

        return redirect()->route('dashboard')
            ->withErrors([
                'vote' => 'You have already voted in this election. Each voter can only vote once.'
            ]);
    }
}
```

SQL observed for a successful cast (E4 GREEN evidence):

```
update "codes" set "has_voted" = ? where "id" = ? and "has_voted" = ?   -- [true, <code id>, false]
insert into "votes" (...)
update "codes" set "can_vote_now" = ?, "has_voted" = ?, ..., "updated_at" = ? where "id" = ?   -- markUserAsVoted, unchanged
```

- **`DB::table()`, not Eloquent:** an Eloquent update would add `updated_at` — a new voter-side timestamp in the cast (plan §7, interim anonymity rule point 2). The consumption writes `has_voted` only.
- **Keyed by id, guarded by `has_voted = false`:** at most one row can change; an already-consumed code changes zero rows.
- **Under true concurrency:** PostgreSQL takes a row lock for the conditional `UPDATE`; a second transaction blocks until the first commits, then re-evaluates `has_voted = false` and changes zero rows. This is reasoned from the database's semantics — **not reproduced by a test**.
- **`$freshCode->id` vs `$code`:** `$freshCode = $code?->fresh()` reloads the same row by primary key, so the ids are equal. The rest of `store()` keeps using `$code`; its only stale attribute that matters is `has_voted`, which `markUserAsVoted()` re-writes as `true` (Eloquent writes dirty attributes only). `save_vote()` loads its own copy and reads only `id` and the code value (for `vote_hash`, A5 unchanged).
- **Rollback:** `save_vote()` has no try/catch and no transaction of its own; any exception reaches `catch (\Throwable)` → `failCastClosed()` → the whole cast transaction, consumption included, is rolled back.
- **The cache lock** stays as a double-click guard only.
- **Demo:** unchanged (Q4) — no conditional consumption on `codes` or `demo_codes`.

## Testing

`tests/Feature/Voting/AtomicParticipationConsumptionTest.php` observes the cast's SQL through `DB::listen` (no production seam):

| Test | Proves |
|---|---|
| R4-1 | exactly one conditional consumption per committed real cast, **before** the vote insert |
| R4-2 | an interleaved consumption right after `store()` reads the code (same connection and transaction) → zero rows → refused with a `vote` error, **no vote** |
| R4-3 | the update is keyed to this code's id and guarded by `has_voted = false` |
| R4-4 | consumption succeeds, `save_vote()` then throws → rollback: `has_voted` back to `false`, no vote |
| R5 | the consumption writes `has_voted` only; the vote row holds no voter / code / slug value |
| R6 | a demo cast through `store()` issues no conditional consumption (guard) |

RED 5/7 (`2afc6da5e`, R4-4 added before GREEN) → GREEN 7/7. Phase 1 `test_b2_…` and R1-D also hold with the consumption in place (rollback restores `has_voted = false`). Regression vs `d4d6b2890`: 0 previously passing tests fail.

**What is not claimed:** PHPUnit does not reproduce a real parallel race. The evidence is the database primitive, the ordering, the affected-row contract and the refusal behaviour.

## Pitfalls

- Do not move the consumption before any path that can return without saving the vote.
- Do not switch it to an Eloquent update (adds `updated_at`) or add other columns to it.
- Do not treat `AlreadyVotedPreCheck` or the cache lock as the guarantee — only `$consumed === 1` is.
- Do not "simplify" `markUserAsVoted()` away in the same change: it still writes `can_vote_now`, `is_code_to_save_vote_usable`, `code_to_save_vote_used_at` (A2 — unchanged until Phase 2.0).
- Do not log a vote id with the refusal (I8).

## Anonymity (interim 7-point rule)

No field on `votes`; **no new voter-side timestamp** (the consumption writes `has_voted` only; `code_to_save_vote_used_at` is written by `markUserAsVoted()` exactly as before — A2 unchanged); the refusal log has no ballot identifier; no receipt / export change; nothing derivable; no new relation; A5 / A6 untouched. Passes all seven points.

**Traceability:** ADR_20261005_0950 §3.2, §3.4, I1, I6, I7, I10 · plan §2.2 E4 / §4 R4–R6 / §5 / §7 · decision record §4 · PBDIGIT-230 · RED `2afc6da5e`, GREEN `b1daf26a2`.
