# 01: The per-IP vote limit, `IpVoteLimit` (PBDIGIT-278 item 8)

**Story:** `PBDIGIT-278` item 8 · **Code:** `app/Services/Voting/IpVoteLimit.php`, used by `ElectionVotingController` (start), `CodeController::verifyCode` (code entry) and `VoteController::vote_post_check` (verification page). **Tests:** `tests/Unit/Services/Voting/IpVoteLimitTest.php` (database-free), `tests/Isolated/Pbdigit274/Database/IpVoteLimitFlowTest.php` (real routes).

## Purpose
The rule: **how many completed votes may come from one network (IP) in one election.** Before item 8 there were four readings with four defaults (`null`, 0, 4, 7):
- verification refused **every** real voter when `MAX_USE_IP_ADDRESS` was unset (`0 >= null`);
- code entry had a hidden default of 7;
- start counted a column nothing writes, so it never enforced anything.

Now there is one rule, used at all three steps.

## The rule
```php
public const DEFAULT_LIMIT = 10;

/** The limit for this IP in this election, or null when the IP is whitelisted (no limit). */
public static function limitFor(Election $election, string $ip): ?int
{
    if ($election->ip_whitelist && $election->isIpWhitelisted($ip)) {
        return null;
    }
    $electionMax = $election->ip_restriction_max_per_ip;
    if ($election->isIpRestricted() && is_numeric($electionMax) && (int) $electionMax > 0) {
        return (int) $electionMax;
    }

    return self::configuredGlobalLimit();
}
```
**Order:**
1. a whitelisted IP has no limit (guide 02);
2. the election's own restriction, when enabled (`ip_restriction_enabled` + `ip_restriction_max_per_ip`, 1–50 in the settings);
3. `MAX_USE_IP_ADDRESS` from `.env` (`config('app.max_use_clientIP')`);
4. otherwise **10**.

**Reading `MAX_USE_IP_ADDRESS`** (`globalLimit()`):

| Value | Limit |
|---|---|
| not set or empty | 10 |
| a positive whole number (`1`, `25`) | exactly that (1 stays 1) |
| `0`, negative, `2.5`, `abc`, `10x`, `' 5'`, `07`, `true` | **invalid**: ignored (10), with `Log::warning('max_use_ip_address_invalid', ['reason' => …, 'type' => …])`, never the value |

**Counting** (`votesFrom()`): completed votes from this IP in this election, from `codes.client_ip` with `has_voted = true`. Since `PBDIGIT-288` (guide 03) the vote records the IP **at the vote** there, and IPv6 counts per /64.

## How to use it
```php
// at a step that must refuse when the limit is reached
$election = Election::withoutGlobalScopes()->find($code->election_id);
if ($election && ($refusal = IpVoteLimit::refusal($election, (string) $clientIp))) {
    return ['success' => false, 'message' => $refusal];   // "The maximum of N vote(s) from your network has been reached."
}
```
(from `CodeController::verifyCode`.) The start step keeps its own result shape (`blocked`, `message`, `remainingVotes`) because the election page shows the remaining votes. It still takes the limit from `limitFor()` and the count from `votesFrom()`.

**Refusals are plain, readable text.** `vote_post_check` (real and demo) used to render `view('components.error_message')`, a view that never existed, so every refusal became "System error". Now each branch returns its own reason, and `verify()` passes it on with `withErrors(['verification' => …])`. Never put a voting link into a refusal message: it ends up in the log (PBDIGIT-274 O-7).

## Design decisions
- **The two limits stay separate** (Human PO, 2026-10-13). The trust policy's constitutional limit is not this rule; at code entry the stricter applies. Pinned by `IpVoteLimitFlowTest::test_the_constitutional_trust_limit_stays_separate_and_can_be_stricter_at_code_entry`.
- **A disabled election restriction is not "no limit":** the `.env` value or 10 still applies (characterised by `SecurityPolicySurfaceCharacterizationTest` C3). Use the whitelist to exempt a network.
- **Demo elections are exempt.**

## Testing
- **`IpVoteLimitTest`** covers every value class of `MAX_USE_IP_ADDRESS`, election precedence, the whitelist and the message, without a database.
- **`IpVoteLimitFlowTest`** runs real routes:
  - below the limits, every step passes;
  - at 10, start refuses and issues no link;
  - a limit of 1 refuses at code entry and at verification with the readable reason, never "System error", with no vote and the link unchanged;
  - the election restriction wins;
  - the constitutional limit is separate.
- **Fixtures:** earlier votes from the same IP are other voters' code rows with `client_ip` = the test client's IP and `has_voted = true`.

## Pitfalls
- **Don't read `config('app.max_use_clientIP')` directly anywhere.** Go through `IpVoteLimit`.
- **`elections.ip_restriction_max_per_ip` defaults to 4 and is NOT NULL.** It only matters while the restriction is enabled.
- **`voter_slugs.step_1_ip` is never written for real elections.** Don't count on it.
- **The vote itself** is covered by `castRefusal()` under a per-network lock (guide 03). Don't call `refusal()` there: without the lock, simultaneous votes can exceed the limit.

**Traceability:** `PBDIGIT-278` item 8: code `7b3d15583`; tests `348c314fc`; evidence `210496eb8` (`docs/publicdigit/election/evidence/2026-10-13-pbdigit278-item8-ip-limit/`).
