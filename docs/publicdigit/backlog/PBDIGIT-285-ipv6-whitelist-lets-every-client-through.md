# PBDIGIT-285: An IPv6 or malformed whitelist entry exempts every IPv6 client from the IP limit

**Type:** Security defect · **Epic:** none (voting) · **Created:** 2026-10-13 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from the IP voting strategy review (finding B). **Index row:** added 2026-10-13.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · priority high |
| **Classification** | verified in code (PHP semantics); not yet reproduced by a test |

## Finding
- **The broken comparison:** `Election::ipInRange()` (`app/Models/Election.php`, called by `isIpWhitelisted`) uses `ip2long()`.
  - `ip2long()` returns `false` for an IPv6 address or a malformed subnet.
  - So `($ip & $mask) === ($subnet & $mask)` compares `0 === 0` and is **true**.
  - Result: an IPv6 range, or a malformed range, in an election's `ip_whitelist` matches **every IPv6 client**, and some malformed IPv4 entries match more than intended.
- **Exact entries** compare raw strings, without canonicalisation.
- **No server-side validation:** `UpdateElectionSettingsRequest` only checks `ip_whitelist.*` as `string`. Only the Vue form checks IPv4 format, and only in the browser.
- **Effect:** `IpVoteLimit::limitFor()` returns "no limit" for those clients (PBDIGIT-278 item 8 honours the whitelist).
- **A different matcher elsewhere:** the trust policy uses `Ipv4Whitelist` (IPv4 only), so the same list behaves differently in the two limits.

## Acceptance
- [ ] **RED first:** a test shows that an IPv6 / malformed entry whitelists an unrelated IPv6 client.
- [ ] **One matcher**, shared by `IpVoteLimit` and the trust policy:
  - handles IPv4 and IPv6 (`inet_pton`, prefix comparison);
  - canonicalises addresses;
  - never matches on a parse failure.
- [ ] **Server-side validation** of whitelist entries (valid IP or CIDR; prefix within range).
- [ ] **Decision:** whether IPv6 counting groups by a prefix (for example /64) instead of the exact address (see `PBDIGIT-288`).

## Related
`PBDIGIT-286` (one visible IP limit) · `PBDIGIT-278` item 8 (`IpVoteLimit`).
