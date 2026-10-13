# 02: The IP whitelist matcher, `Ipv4Whitelist` (PBDIGIT-285)

**Story:** `PBDIGIT-285` · **Code:**
- `app/Domain/Election/Security/Ipv4Whitelist.php` (pure PHP, domain layer)
- `Election::isIpWhitelisted()`
- `app/Http/Requests/Election/UpdateElectionSettingsRequest.php`

**Test:** `tests/Unit/Models/ElectionIpWhitelistTest.php` (database-free).

## Purpose
An election can list trusted networks (offices, universities) whose voters share one internet address. Voters from a listed network are **exempt from both per-IP limits**: `IpVoteLimit` (guide 01) and the constitutional limit in the trust policy. They are **not** exempt from IP binding.

**The defect fixed here:** `Election::isIpWhitelisted()` had its own matcher built on `ip2long()`. For IPv6 or malformed input `ip2long()` returns `false`, so `false & mask` compared `0 === 0`. An IPv6 or malformed entry therefore whitelisted **every IPv6 client**. Now there is one matcher for both limits.

## How it works
- **Entries:** `elections.ip_whitelist` is a JSON list. An entry is an exact IPv4 address (`198.51.100.7`) or an IPv4 range (`203.0.113.0/24`).
- **Safe by design:** an entry or address the matcher cannot read **matches nothing**, and it never throws.
- **IPv4 only** (PO 2026-10-05): IPv6 voters are never whitelisted.

```php
// Election::isIpWhitelisted() — delegates to the one matcher
return \App\Domain\Election\Security\Ipv4Whitelist::fromEntries($this->ip_whitelist)->contains($ip);
```
The trust policy uses the same class (`TrustPolicyEvaluator`):
```php
$whitelisted = $election !== null && Ipv4Whitelist::fromEntries($election->ip_whitelist)->contains($rawIp);
```

**Validation on save** uses the matcher's own parser, so nothing it cannot read is stored:
```php
'ip_whitelist.*' => ['string', function (string $attribute, mixed $value, \Closure $fail) {
    if (! is_string($value) || ! \App\Domain\Election\Security\Ipv4Whitelist::isValidEntry($value)) {
        $fail('Each whitelist entry must be an IPv4 address (e.g. 203.0.113.7) or an IPv4 range (e.g. 10.0.0.0/8).');
    }
}],
```
`isValidEntry()` accepts an IPv4 address, or an IPv4 address plus `/` and a prefix of 0–32 written as digits only.

## How to use or extend it
- **Need "is this IP exempt?"** Call `$election->isIpWhitelisted($ip)` or `Ipv4Whitelist::fromEntries(...)->contains($ip)`. Never write a second matcher.
- **IPv6 whitelisting** would be an extension of this class (parse with `inet_pton`, compare prefixes) and needs a PO decision, because it reverses the IPv4-only scope.

## Testing
`ElectionIpWhitelistTest`:
- **attack inputs:** an IPv6 range against an unrelated IPv6 client, malformed ranges, a prefix over 32, an empty prefix. None may match;
- **valid behaviour** is unchanged;
- **both matchers agree** on every case;
- **the settings request** accepts and rejects exactly what `isValidEntry()` does.

## Pitfalls
- **`0.0.0.0/0` is valid** and whitelists every IPv4 address, which turns the IP limits off for that election. Use narrow ranges.
- **The settings form shows the whitelist only while "IP restriction" is on,** but `IpVoteLimit` honours it either way, so a hidden list stays active (`PBDIGIT-288` item 7).
- **Stored entries that are now invalid** (saved before validation existed) are not rewritten. They simply match nothing.
- **Settings are locked during voting.** Set the whitelist before voting starts.

**Traceability:** `PBDIGIT-285`, implemented 2026-10-13. RED 21 of 33 → GREEN 33 / 77; 0 regressions in 121 related tests. Evidence: `docs/publicdigit/election/evidence/2026-10-13-pbdigit285-ip-whitelist/`. **Not yet committed.**
