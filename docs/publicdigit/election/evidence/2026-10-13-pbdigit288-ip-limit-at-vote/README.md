# PBDIGIT-288 items 1–4: the per-IP vote limit at the vote, 2026-10-13

**Authority:** Human PO ("yes, start step 2"; decisions of 2026-10-13: the counted IP is the IP at the vote, with a per-election, per-IP lock there; IPv6 grouped by /64, configurable).

## Change
- **Item 1, checked at the vote:** `VoteController::store` (real elections) calls `IpVoteLimit::castRefusal()` immediately before participation is consumed, after every other refusal. A refusal reads "The maximum of N vote(s) from your network has been reached.", persists no vote, and leaves participation unconsumed.
- **Item 2, one vote at a time per network:**
  - `castRefusal()` takes a PostgreSQL transaction-level advisory lock on (`ip_vote_limit`, election id + IP group) before counting.
  - The lock is held until the cast commits or rolls back.
  - It waits at most `voting.ip_lock_timeout_ms` (default 5000), then refuses safely ("…Please try again in a moment.").
  - A whitelisted IP takes no lock.
- **Item 3, the IP at the vote:** the participation update now also writes `codes.client_ip` = the IP at the vote.
- **Item 4, IPv6 by /64:**
  - New pure-PHP `App\Domain\Election\Security\IpVoteGroup`.
  - An IPv4 address counts on its own, and an IPv4-mapped IPv6 address counts as that IPv4 address.
  - An IPv6 address counts with its network of `voting.ipv6_vote_group_prefix` (default 64).
  - An unreadable value counts by exact match.
  - `IpVoteLimit::votesFrom()` uses the group, so start, code entry and verification count the same way.

## Runs
| # | File | Result |
|---|---|---|
| 01 | `01-red-group.out` | `IpVoteGroupTest` before the class existed: 9 of 9 error (class missing). The test file then gained a tenth case (IPv4-mapped addresses, found while implementing) before GREEN |
| 02 | `02-red-cast.out` | `IpVoteLimitAtCastTest` before the change: **6 of 8 fail**. Votes went through at the limit, from a voter who changed network, and from the same IPv6 /64; the code-entry IP stayed recorded; the lock API was missing. The 2 that passed are characterisations: below the limit, whitelist |
| 03 | `03-green-cast.out` | after the change: **8 / 34, exit 0** |
| 04 | `04-database-free.out` | `IpVoteGroupTest` 10/25, `IpVoteLimitTest` 18/56, `ElectionIpWhitelistTest` 33/77: all pass |
| 05 | `05-isolated-all.out` | the isolated suite: 112 tests, 110 pass. The **2 failures are the parked PBDIGIT-284 RED proof** (`VoteAnonymityLinkageTest`, uncommitted, fails by design), the same 2 as in the PBDIGIT-285 run (104 tests). The 8 new tests are the difference |

The deprecation notices come from the framework's `config/database.php` (`PDO::MYSQL_ATTR_SSL_CA` on PHP 8.5), not from this change. The isolated identity guard confirmed `pbdigit274_isolated_test`; no other database was used.

## Not in this item
- **The constitutional limit** (trust policy, `TrustLegitimacySecurityVerdict`) still counts by exact address, and before the lock. It is separate by PO decision and waits for `PBDIGIT-286`. It now counts the IP at the vote, because it reads the same column.
- **Demo elections** are unchanged (exempt).
- **Audit entries for refusals** (item 5) are plan step 3. These refusals are logged only (`voting_audit`, no IP in the entry).
- **Without PostgreSQL** the count runs without the lock. The platform runs on PostgreSQL.
