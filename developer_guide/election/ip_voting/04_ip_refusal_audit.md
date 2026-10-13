# 04: Every IP refusal in the election audit (PBDIGIT-288 item 5)

**Story:** `PBDIGIT-288` item 5 · **Code:** `IpVoteLimit::recordRefusal()`, `IpVoteLimit::limitSource()` (`app/Services/Voting/IpVoteLimit.php`). Called from `ElectionVotingController` (start), `CodeController::verifyCode`, `VoteController::vote_post_check` and `VoteController::store`. **Tests:** `tests/Isolated/Pbdigit274/Database/IpRefusalAuditTest.php`; `limitSource` cases in `tests/Unit/Services/Voting/IpVoteLimitTest.php`.

## Purpose
Election admins read the election audit (`ElectionAuditService`, JSONL per election), not the server log. Before this step:
- only the start step wrote an `ip_blocked` entry, and its `max` was the election setting even when that setting was off;
- refusals at code entry, verification and the vote were invisible to admins.

## How it works
One method writes the entry, so all four steps record the same thing:
```php
public static function recordRefusal(Election $election, ?User $user, string $step, string $ip, string $reason = 'limit_exceeded'): void
{
    app(ElectionAuditService::class)->log(
        election: $election,
        event: 'ip_blocked',
        user: $user,
        category: 'voters',
        ip: $ip,
        metadata: [
            'reason' => $reason,
            'step' => $step,
            'max' => self::limitFor($election, $ip),
            'limit_source' => self::limitSource($election, $ip),
        ]
    );
}
```

| Field | Values |
|---|---|
| `reason` | `limit_exceeded`, or `network_busy` (the vote could not get the network lock, guide 03) |
| `step` | `start`, `code_entry`, `verification`, `vote` |
| `max` | the limit that applied |
| `limit_source` | `election` (enabled restriction), `env` (valid `MAX_USE_IP_ADDRESS`), `default` (10, also when the env value is invalid) |

**At the vote** the entry is written after the cast transaction is rolled back. The audit is a file, so the rollback never removes it:
```php
$this->failCastClosed($outerTransactionLevel, $lock, $lockAcquired);
\App\Services\Voting\IpVoteLimit::recordRefusal($election, $auth_user, 'vote', $castIp,
    $ipRefusal === \App\Services\Voting\IpVoteLimit::BUSY_MESSAGE ? 'network_busy' : 'limit_exceeded');
```

## How to extend it
- **A new step that refuses by IP** must call `recordRefusal()` with its own `step` value. Never call `ElectionAuditService::log('ip_blocked', …)` directly.
- **Never put the IP into `metadata`.** The audit service's `ip` field is the only place, and its retention is `PBDIGIT-288` item 6.

## Testing
`IpRefusalAuditTest` swaps in an audit service that records entries in memory (no files). It covers:
- each of the four steps;
- the busy case (lock held from a second connection, as in guide 03);
- "no refusal, no entry".

Each test asserts the metadata exactly.

## Pitfalls
- **`limitSource()` must mirror `limitFor()`.** If you change the order of the rule, change both. `IpVoteLimitTest::test_the_limit_source_mirrors_the_limit_rule` pins it.
- **The constitutional limit's refusals are not `ip_blocked` entries.** They are a different limit (`PBDIGIT-286`).
- **Demo elections** never refuse by IP and write no entry.

**Traceability:** `PBDIGIT-288` item 5, 2026-10-13. RED 5 of 6 → GREEN 6/38; isolated suite 118, 0 regressions. The `limitSource` unit tests were written after the code; their RED was proven retroactively (recorded deviation). Evidence: `docs/publicdigit/election/evidence/2026-10-13-pbdigit288-ip-refusal-audit/`.
