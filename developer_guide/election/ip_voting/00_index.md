# IP-based voting protections: developer guide index

Protections that use the voter's IP address. **None of them stores an IP on a vote:** the `votes` and `results` tables have no IP and no voter column (see `tests/Isolated/Pbdigit274/Database/ResultsAccuracyTest.php`).

## The map: which protection applies where

| Protection | What it limits | Start (`elections.start`) | Code entry | Verification page | The vote itself | Configured by |
|---|---|---|---|---|---|---|
| **Per-IP vote limit** (`IpVoteLimit`, guides 01, 03) | completed votes per IP (IPv6: per /64) per election, counted at the IP of the vote | ✔ | ✔ | ✔ | ✔ under a per-network lock | `.env` `MAX_USE_IP_ADDRESS`; election "IP restriction" setting; default 10 |
| **Constitutional per-IP limit** (trust policy) | votes per IP, scaled by evidence level | | ✔ (`forEntry`) | | ✔ (`forCast`) | `elections.max_votes_per_ip` (default 6, **not shown in the UI**, PBDIGIT-286) |
| **IP whitelist** (`Ipv4Whitelist`, guide 02) | exempts listed IPv4 networks from **both** limits above | ✔ | ✔ | ✔ | ✔ | election settings, `ip_whitelist` |
| **IP binding** (PBDIGIT-247) | the voter may act only from their captured IP | | ✔ | | ✔ | election setting `ip_binding_enabled` (default off); the captured IP is in `voter_ip_verifications` |

**Key rules:**
- **The two per-IP limits are separate** (Human PO, 2026-10-13). At code entry the stricter one applies.
- **The whitelist exempts from both limits, but never from IP binding.**
- **Demo elections are exempt** from the per-IP vote limit.

## Guides
| # | Guide | Story | Status |
|---|---|---|---|
| 01 | [The per-IP vote limit (`IpVoteLimit`)](01_ip_vote_limit.md) | `PBDIGIT-278` item 8 | committed `7b3d15583` (code), `348c314fc` (tests) |
| 02 | [The IP whitelist matcher (`Ipv4Whitelist`)](02_ip_whitelist.md) | `PBDIGIT-285` | committed `e3ea8b0b5` (code), `2a92285ae` (tests) |
| 03 | [The limit at the vote: lock, voting IP, IPv6 /64](03_ip_limit_at_the_vote.md) | `PBDIGIT-288` items 1–4 | committed `6aeece4d4` (code), `197505f8d` (tests) |
| 04 | [Every IP refusal in the election audit](04_ip_refusal_audit.md) | `PBDIGIT-288` item 5 | committed `8dd041fc8` (code), `7cb27176d` (tests) |
| 05 | [Trusted proxy: where the client IP comes from](05_trusted_proxy.md) | `PBDIGIT-287` | committed `bb06acb4d` (code), `f16eb8ba7` (tests) |
| 06 | [The constitutional per-IP limit (System B) as it works today](06_constitutional_limit.md) | `PBDIGIT-286` (OPEN) | **current behaviour only**; PBDIGIT-286 changes are planned, not implemented |

## How the client IP is obtained
- **In code:** `$request->ip()` / `Request::getClientIp()` (no argument).
- **Proxy trust:** only the proxies in `TRUSTED_PROXIES` may set the client IP through `X-Forwarded-For`; a wildcard is refused (guide 05, `PBDIGIT-287`). **Set it before voting opens.**
- **In tests,** set the address with `withServerVariables(['REMOTE_ADDR' => '203.0.113.5'])`. The default is `127.0.0.1`.

## Open work (backlog)
| Item | What |
|---|---|
| `PBDIGIT-286` | the constitutional limit is hidden and effectively 3 (`attested` hard-coded false), and the admin switch doesn't control it (guide 06). Decision record and DRAFT plan committed (`e3d186434`); 16 decisions and the gates are open |
| `PBDIGIT-288` | items 1–5 done (guides 03, 04). Open: IP retention and keyed hashing; settings-page wording; the off-by-one between the two limits; dead IP code |
| `PBDIGIT-284` | parked: the vote hash and an audit entry can link a vote to its voter (not an IP issue, but found in the same review) |

## Running the tests
```bash
# database-free
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Services/Voting/IpVoteLimitTest.php
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Models/ElectionIpWhitelistTest.php
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Domain/Election/Security/IpVoteGroupTest.php
# real routes, isolated database only
. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml tests/Isolated/Pbdigit274/Database/IpVoteLimitFlowTest.php
. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml tests/Isolated/Pbdigit274/Database/IpVoteLimitAtCastTest.php
```
**Never run a `Tests\TestCase` test with `--no-configuration`.** It resets whatever database `.env` points at; the guard refuses it since `PBDIGIT-283`. Only use it for files you have checked extend plain PHPUnit or the dead-socket `BaseTestCase`.
