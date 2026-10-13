# 06: `VoterSlugIssuer`, issue, reuse or replace a voting link (PBDIGIT-274 S3, G6)

**Story:** `PBDIGIT-274` S3 · **Code:**
- `app/Services/Voting/VoterSlugIssuer.php`
- `app/Services/Voting/VoterSlugIssuance.php`
- `app/Exceptions/Voting/VoterSlugIssuanceConflict.php`

**Tests:**
- `tests/Unit/Services/Voting/VoterSlugIssuerTest.php` (database-free)
- `tests/Isolated/Pbdigit274/Database/VoterSlugIssuerTest.php`
- `…/VoterSlugIssuerRealConcurrencyTest.php` (G6, real processes)

## Purpose
The single place that gives a voter their voting link for a **real** election. The business rule: an entitled voter holds exactly one current, valid link.

| Situation | Outcome | Writes |
|---|---|---|
| a valid current link | `REUSED` | nothing (no extension, no rewrite) |
| no link, or an expired / deactivated one | `ISSUED` | the old row is retired (soft-deleted, steps kept); one new row is inserted |
| voted (codes row, or any slug row incl. soft-deleted) | `ALREADY_VOTED` | nothing |
| not entitled (fresh check) | `NOT_ENTITLED` | the current link is retired (O-3); no new one |

## How to use it
```php
try {
    $issuance = VoterSlugIssuer::fresh()->issue($user, $election);
} catch (VoterSlugIssuanceConflict $e) {
    return redirect()->route('elections.show', $slug)->with('error', $e->getUserMessage());
}

if ($issuance->outcome === VoterSlugIssuance::ALREADY_VOTED) {
    return redirect()->route('elections.show', $slug)->with('info', 'You have already voted.');
}
if (! $issuance->hasLink()) {
    // $issuance->refusal is the VotingEntitlementReason
}
$voterSlug = $issuance->slug;
```
(from `ElectionVotingController::start`)

- **Always `VoterSlugIssuer::fresh()`.** It builds the issuer with the uncached `EloquentVotingEntitlementQuery`; the container's `VotingEntitlementQuery` is the cached one.
- **Call it outside any transaction.** Otherwise it throws a `LogicException`: each attempt is its own transaction, so a retirement is committed before a refusal is returned.
- **Real elections only** (decision D1). Demo elections keep `VoterSlugService`.

## How it works (one attempt, `decide()`)
1. **`lockForUpdate`** the voter's `codes` row. The cast's participation update writes the same row, so issuer and cast serialise there.
2. **`lockForUpdate`** the current slug row, read with `VoterSlug::withoutEvents(...)` so the `retrieved` hook cannot write on a refusal path.
3. **Voted?** → `alreadyVoted()`.
4. **Fresh entitlement.** If not entitled → `retire()` the current link, then `notEntitled()`.
5. **`VoterSlugLifetime::isReusable()`** (guide 02) → `reused()`.
6. **Otherwise** `retire()` the old row and insert a new one: `expires_at = now + T`, `created_at = now`, 32 random characters.

`issue()` retries on a unique violation, classified by constraint name in `classify()`:
- **`voter_slugs_slug_unique`:** a new string, at most three attempts;
- **`voter_slugs_active_election_user_unique`:** a concurrent issuer won, so decide again once;
- **anything else:** `VoterSlugIssuanceConflict` (409, "Your voting link could not be prepared just now. Please try again.").

## Concurrency (G6, passed)
`VoterSlugIssuerRealConcurrencyTest` runs separate PHP processes (`tests/Isolated/Pbdigit274/Concurrency/g6-worker.php`), each with its own PostgreSQL connection.

**Forcing the order:** the test holds a PostgreSQL advisory lock, and the workers wait on it at test-only pause points. These are the protected no-op seams `afterLocks()` and `beforeInsert()` (decision D3). The test reads from `pg_locks` / `pg_stat_activity` that a worker is really blocked before releasing it.

**Proven cases:**
- no row: two issuers end with one link;
- an expired row: two issuers end with one replacement;
- cast first, then issuer: `ALREADY_VOTED`, nothing written;
- issuer first, then cast: the accepted O-13 state.

**Limit:** the "cast" there is the D2 stand-in, which does the codes compare-and-set and marks the slug; it is not the full cast path.

## Pitfalls
- **Never extend or rewrite a link in place.** That was the lockout bug: the guard caps the lifetime at `created_at + T`.
- **Out of date:** the class docblock still says "Not wired to any caller yet" and that concurrency safety is not claimed until G6. Both are outdated: S4 and S5 wired the callers (guide 07), and G6 passed.
- **The seams `transactionLevel()`, `newSlugString()`, `beforeInsert()` and `afterLocks()` exist for tests.** Production code must not override them.

**Traceability:** `PBDIGIT-274` S3:
- issuer `0cb7c1111`; tests `c18a7bac6`; evidence `292576616`;
- G6: pause point `dd9725197`, tests `f3447a931`, evidence `172d9934f`.
