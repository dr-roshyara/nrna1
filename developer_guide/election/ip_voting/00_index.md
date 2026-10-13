# IP-based voting protections: developer guide index

Protections that use the voter's IP address. **None of them stores an IP on a vote:** the `votes` and `results` tables have no IP and no voter column (see `tests/Isolated/Pbdigit274/Database/ResultsAccuracyTest.php`).

## The map: which protection applies where

| Protection | What it limits | Start (`elections.start`) | Code entry | Verification page | The vote itself | Configured by |
|---|---|---|---|---|---|---|
| **Per-IP vote limit** (`IpVoteLimit`, guide 01) | completed votes per IP per election | ✔ | ✔ | ✔ | ✘ (planned, PBDIGIT-288) | `.env` `MAX_USE_IP_ADDRESS`; election "IP restriction" setting; default 10 |
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
| 02 | [The IP whitelist matcher (`Ipv4Whitelist`)](02_ip_whitelist.md) | `PBDIGIT-285` | implemented and tested; **not yet committed** |

## How the client IP is obtained
- **In code:** `$request->ip()` / `Request::getClientIp()`. The `true` argument some call sites pass is ignored by Symfony.
- **Proxy trust:** `bootstrap/app.php` currently trusts **every** proxy (`trustProxies(at: '*')`), so `X-Forwarded-For` decides the IP. **Production runs behind a single reverse proxy** (PO 2026-10-13), and only that proxy must be trusted (`PBDIGIT-287`, open).
- **In tests,** set the address with `withServerVariables(['REMOTE_ADDR' => '203.0.113.5'])`. The default is `127.0.0.1`.

## Open work (backlog)
| Item | What |
|---|---|
| `PBDIGIT-286` | the constitutional limit is hidden and effectively 3 (`attested` hard-coded false), and the admin switch doesn't control it. Needs a PO decision |
| `PBDIGIT-287` | trust only the reverse proxy |
| `PBDIGIT-288` | the per-IP vote limit at the vote itself with an IP-scoped lock, counting the IP **at the vote**, IPv6 grouped by **/64** (all decided 2026-10-13); audit at every refusal; IP retention; settings-page wording; dead IP code |
| `PBDIGIT-284` | parked: the vote hash and an audit entry can link a vote to its voter (not an IP issue, but found in the same review) |

## Running the tests
```bash
# database-free
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Services/Voting/IpVoteLimitTest.php
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Models/ElectionIpWhitelistTest.php
# real routes, isolated database only
. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml tests/Isolated/Pbdigit274/Database/IpVoteLimitFlowTest.php
```
**Never run a `Tests\TestCase` test with `--no-configuration`.** It resets whatever database `.env` points at; the guard refuses it since `PBDIGIT-283`. Only use it for files you have checked extend plain PHPUnit or the dead-socket `BaseTestCase`.
