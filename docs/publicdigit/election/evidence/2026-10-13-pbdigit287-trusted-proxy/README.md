# PBDIGIT-287: trust only the configured reverse proxy, 2026-10-13

**Authority:** Human PO ("start PBDIGIT-287"; decision of 2026-10-13: a single reverse proxy, trusted from configuration, never `'*'`).

## Change
- **`bootstrap/app.php`:** `trustProxies(at: '*')` is replaced by the app's own `App\Http\Middleware\TrustProxies`, which was unregistered before.
- **`App\Http\Middleware\TrustProxies`:**
  - trusts only `trustedproxy.proxies` (new `config/trustedproxy.php`, env `TRUSTED_PROXIES`: comma-separated addresses or CIDR ranges);
  - not set means no proxy is trusted;
  - a wildcard (`*`, `**`, or Laravel's `REMOTE_ADDR`) is refused with the warning `trusted_proxies_wildcard_refused` and trusts no proxy;
  - only `X-Forwarded-For/-Host/-Port/-Proto` are read, not `Forwarded`.
- **Dead settings removed:**
  - `voting_security.trust_proxies` / `VOTING_TRUST_PROXIES` (both branches of `ValidateVotingIp::getClientIp()` returned `$request->ip()`);
  - the ignored `true` argument in `\Request::getClientIp(true)` (`CodeController`, `DemoCodeController`, `VoteController::vote_post_check`).

## Runs
| # | File | Result |
|---|---|---|
| 01 | `01-red.out` | `TrustedProxyTest` before the change: **7 of 11 fail**. A spoofed `X-Forwarded-For` from any address (`1.2.3.4`) became the client IP: without a proxy, from a non-proxy, outside a configured range, and with every wildcard. The 4 that passed are characterisations: from the proxy, a prepended chain, the `Forwarded` header, the direct address |
| 02 | `02-green.out` | after the change: **11 / 49, exit 0** |
| 03 | `03-red-dead-settings.out` | `ClientIpHasOneSourceTest` before the clean-up: **2 of 2 fail** (the dead setting; `getClientIp(true)`) |
| 04 | `04-green-dead-settings.out` | after the clean-up: **2 / 2** |
| 05 | `05-database-free.out` | the proxy and guard tests plus `DatabaseResetIsGuardedTest` and the IP tests (`IpVoteLimitTest`, `IpVoteGroupTest`, `ElectionIpWhitelistTest`): all pass |
| 06 | `06-isolated-all.out` | the isolated suite, every request through the new middleware: 118 tests, 116 pass. The **2 failures are the parked PBDIGIT-284 RED proof**, the same 2 as before |

## Deviations, recorded
- **A test read `.env.example`.** The first version of `ClientIpHasOneSourceTest` had a third case that read it. That breaks the standing rule never to read `.env.*` files. Its failure message copied the file into the output; that output was deleted unread and the case removed. The earlier search for the dead setting also printed one line of it (`VOTING_TRUST_PROXIES=false`). **`.env.example` is not changed by this story.** The owner should remove `VOTING_TRUST_PROXIES` and add `TRUSTED_PROXIES=`.
- **The `getClientIp` guard pattern was narrowed after GREEN.** The first pattern also matched `ValidateVotingIp`'s own helper of the same name (a false positive). It now matches calls on the request only, and still matches all three original sites (checked against HEAD).

## Deployment (must be done before voting opens)
- **Set `TRUSTED_PROXIES`** to the reverse proxy's address or range, then `php artisan config:clear`.
- **If it is not set behind a proxy,** every voter appears as the proxy's address. All voters then share one per-IP limit and are refused almost at once.

The isolated identity guard confirmed `pbdigit274_isolated_test`; no other database was used.
