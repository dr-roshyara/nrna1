# 03: The per-IP vote limit at the vote: lock, voting IP, IPv6 /64 (PBDIGIT-288 items 1–4)

**Story:** `PBDIGIT-288` items 1–4 · **Code:**
- `app/Domain/Election/Security/IpVoteGroup.php` (pure PHP)
- `app/Services/Voting/IpVoteLimit.php` (`castRefusal`, `lockKey`, group-aware `votesFrom`)
- `VoteController::store`
- `config/voting.php`

**Tests:**
- `tests/Unit/Domain/Election/Security/IpVoteGroupTest.php` (database-free)
- `tests/Isolated/Pbdigit274/Database/IpVoteLimitAtCastTest.php` (real cast route)

## Purpose
Guide 01 left three gaps, all at the moment the vote is cast:
1. the limit was not checked there;
2. voters on one network casting at the same moment could all pass the check;
3. the counted IP was the one from code entry.

Separately, every IPv6 address counted on its own, so a household could rotate addresses. All four are closed here.

## How it works
**At the vote** (real elections), the check sits immediately before participation is consumed, after every other refusal:
```php
$castIp = (string) $request->ip();
if ($ipRefusal = \App\Services\Voting\IpVoteLimit::castRefusal($election, $castIp)) {
    // log (no IP), fail closed (rollback + release the voter lock), readable refusal
    return back()->withErrors(['vote' => $ipRefusal]);
}

$consumed = DB::table('codes')
    ->where('id', $freshCode->id)
    ->where('has_voted', false)
    ->update([
        'has_voted' => true,
        'client_ip' => $castIp,   // the IP at the vote is what is counted
    ]);
```

**`castRefusal()`:**
1. A whitelisted IP has no limit and takes no lock.
2. Otherwise it takes a PostgreSQL **transaction-level advisory lock** on (`IpVoteLimit::LOCK_NAMESPACE`, `lockKey($election, $ip)`). The key is `election id | IP group`.
3. It then runs the same `refusal()` as start, code entry and verification.

**Why the lock works:**
- It is released only when the cast transaction commits or rolls back, so votes from one network are counted and recorded **one at a time**.
- Each waiting cast counts after the previous one has committed (PostgreSQL's default read-committed isolation).

**When the lock isn't free:** if it is not free within `voting.ip_lock_timeout_ms`, PostgreSQL raises `55P03`. The vote is then refused with `IpVoteLimit::BUSY_MESSAGE`, nothing is stored, and the voter can retry.

**Grouping** (`IpVoteGroup::of($ip, $prefix)`):

| Address | Group |
|---|---|
| `203.0.113.7` | `203.0.113.7` (its own) |
| `::ffff:203.0.113.7` (IPv4-mapped) | `203.0.113.7` |
| `2001:db8:1:2::10`, `2001:DB8:1:2:aaaa::1` | `2001:db8:1:2::/64` |
| `not-an-ip` | `not-an-ip` (exact match, as before) |

**How `votesFrom()` counts each kind of address:**
- **IPv4:** one exact-match query over both stored spellings (`spellingsOfIpv4()`).
- **IPv6:** it reads the election's voted IPv6 values and compares groups in PHP.
- **Unreadable values:** exact match.

Start, code entry and verification use `votesFrom()` too, so all four steps count alike.

## Configuration
| Key | Env | Default | Meaning |
|---|---|---|---|
| `voting.ipv6_vote_group_prefix` | `VOTING_IPV6_VOTE_GROUP_PREFIX` | 64 | IPv6 network size that counts as one (1–128; anything else → 64) |
| `voting.ip_lock_timeout_ms` | `VOTING_IP_LOCK_TIMEOUT_MS` | 5000 | how long a vote waits for another vote from the same network |

Run `php artisan config:clear` after changing `.env`.

## How to extend it
- **Any new place that counts votes per IP** must use `IpVoteLimit::votesFrom()` or `IpVoteGroup`. Never write `where('client_ip', $ip)` again.
- **`castRefusal()` must run inside the cast transaction.** It throws a `LogicException` otherwise, because a session-level lock would outlive the request.
- **Keep it last before the participation update,** so the lock is held as briefly as possible.

## Testing
**`IpVoteLimitAtCastTest`** covers:
- refusal at the vote;
- below the limit;
- recording the voting IP;
- a voter who changed network;
- IPv6 /64;
- the lock;
- the lock key per election and network;
- the whitelist.

The lock test holds the advisory lock from a **second connection** to the isolated database (as a concurrent vote would). With `voting.ip_lock_timeout_ms = 300` it checks:
- a vote from that network is refused safely;
- another network is not held up;
- after release, the retry counts.

## Pitfalls
- **The constitutional limit** (`TrustLegitimacySecurityVerdict`) still counts **by exact address** and is evaluated at the start of `store()`, outside this lock. It is separate by PO decision and its future depends on `PBDIGIT-286`.
- **Locks need PostgreSQL.** On another driver the count runs without the lock.
- **Never log the IP or the lock key.** The key contains the IP group.
- **Demo elections** are exempt from the per-IP vote limit and unchanged.
- **Refusals are not in the admin audit yet** (plan step 3, `PBDIGIT-288` item 5).

**Traceability:** `PBDIGIT-288` items 1–4, 2026-10-13. RED 6 of 8 (cast) and 9 of 9 (group) → GREEN 8/34 and 10/25; isolated suite 112, 0 regressions. Evidence: `docs/publicdigit/election/evidence/2026-10-13-pbdigit288-ip-limit-at-vote/`.
