# PBDIGIT-285: the IP whitelist never exempts an address it does not list, 2026-10-13

**Authority:** Human PO ("implement everything related to IP address"; plan step 1).

## Fix
- **`Election::isIpWhitelisted()`** now delegates to the trust policy's matcher, `App\Domain\Election\Security\Ipv4Whitelist` (one matcher; IPv4 only, as decided by the PO on 2026-10-05). The broken `ipInRange()` is removed. It used `ip2long()`, whose `false` for IPv6 or malformed input made `0 === 0` true.
- **`Ipv4Whitelist::isValidEntry()`** (new) accepts an exact IPv4 address or an IPv4 CIDR range with a prefix of 0–32, using the same parser as matching.
- **`UpdateElectionSettingsRequest`:** `ip_whitelist.*` is validated on the server with `isValidEntry()`. Before, any string was accepted.

## Runs
| # | File | Result |
|---|---|---|
| 01 | `01-red.out` | `ElectionIpWhitelistTest` (database-free) before the fix: **21 of 33 fail**: IPv6 / malformed entries matched; the election and trust-policy matchers disagreed; no validation |
| 02 | `02-green.out` | after the fix: **33 / 77, exit 0** |
| 03 | `03-database-free.out` | `IpVoteLimitTest`, `ElectionIpWhitelistTest`, both architecture guards: **53 / 151, exit 0** |
| 04 | `04-isolated-all.out` | the isolated suite: 104 tests, 102 pass. The **2 failures are the parked PBDIGIT-284 RED proof** (`VoteAnonymityLinkageTest`, uncommitted, fails by design) |

**Related existing tests:** 13 files touching the whitelist or settings were run on their own and compared per test with their previous state: **121 tests, 0 regressions**. 10 failed before as well: `ElectionIpRestrictionTest` (8 errors) and one each in `ElectionSettingsTest` and `ElectionSettingsServiceTest`.

The isolated identity guard confirmed `pbdigit274_isolated_test`. No other database was used.

## Not in this item
- **IPv6 whitelisting** stays unsupported (IPv4-only scope, PO 2026-10-05). **IPv6 counting by /64** is decided and belongs to PBDIGIT-288.
- **Existing stored whitelists** with entries that are now invalid are not rewritten. They simply match nothing.
