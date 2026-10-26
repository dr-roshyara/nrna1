# 09: IP binding matches the voter's network (`IpBindingNetwork`, PBDIGIT-315)

**Story:** `PBDIGIT-315` · **Plan:** `docs/plans/20261026-2125-ip-binding-network-match-plan.md`

**Code:**
- `app/Domain/Election/Security/IpBindingNetwork.php` (pure PHP, domain layer)
- `TrustLegitimacySecurityVerdict::ipBindingSatisfied()` (`app/Contexts/Elections/Infrastructure/Security/`)

**Tests:**
- `tests/Unit/Domain/Election/Security/IpBindingNetworkTest.php` (database-free)
- `tests/Feature/Voting/IpBindingEntryTest.php` B-E8 to B-E11
- `tests/Feature/Voting/IpBindingCastTest.php` B-C4

## Purpose
IP binding (PBDIGIT-247) lets a voter enter the code step and cast only from their captured IP (`voter_ip_verifications`). It used to require the **exact** address (decision B3). Home routers and mobile networks change the last octet, so captured voters were refused. Since PBDIGIT-315 (PO 2026-10-26), binding matches the voter's **network**:

| Address | Network that satisfies binding |
|---|---|
| IPv4 `106.101.7.23` | `106.101.7.0/24`, so any `106.101.7.x` |
| IPv4-mapped `::ffff:106.101.7.23` | the same as its IPv4 address |
| IPv6 `2001:db8:1:2::10` | `2001:db8:1:2::/64` |
| unreadable or missing | none: never matches (fail closed, B1) |

**Unchanged:**
- B1: no capture means refused.
- Q1: the whitelist does not bypass binding.
- Q2: the voter sees a specific message, never the captured IP.
- Binding is decided only by the Security Verdict Authority, at code entry (`forEntry`) and at the cast (`forCast`).

## How it works
```php
// IpBindingNetwork — the network key "network/prefix", or null
IpBindingNetwork::of('106.101.7.23');        // '106.101.7.0/24'
IpBindingNetwork::of('2001:db8:1:2::10');    // '2001:db8:1:2::/64'
IpBindingNetwork::of('not-an-ip');           // null
IpBindingNetwork::matches($captured, $request); // false if either has no network
```
The authority only asks for the fact; the raw addresses never leave the method:
```php
$captured = VoterIpVerification::where('election_id', $election->id)
    ->where('user_id', $userId)
    ->value('ip_address');

return IpBindingNetwork::matches($captured, $clientIp);
```
The captured IP is still stored in full, so the rule also applies to captures made before PBDIGIT-315.

## Design decisions
- **A separate class, not `IpVoteGroup`** (guide 03). The per-IP vote limit counts an IPv4 address on its own; binding uses a /24. They are different rules and must be able to change independently.
- **Fixed prefixes** (`IPV4_PREFIX = 24`, `IPV6_PREFIX = 64`). They are not configurable and there is no per-election setting (PO 2026-10-26).
- **Anonymity (ADR-T11):** nothing is stored, and the assessment envelope carries no IP (B-A3).

## Pitfalls
- **Weaker by design:** anyone on the same /24 passes binding. The per-IP vote limits still apply.
- A voter whose network changes completely (for example, mobile `106.101.x` → `39.7.x`) is still refused. The officer re-captures after the voter logs in again (the login records `current_ip`, PBDIGIT-307).
- The test client's IP is `127.0.0.1`. A capture in `127.0.0.x` now satisfies binding in cast tests (B-C4). To test a refusal, use an address in a different /24.

## Traceability
PBDIGIT-315 (supersedes PBDIGIT-247 B3) · plan `20261026-2125-ip-binding-network-match-plan.md` · PBDIGIT-247 plan `20261006-2125-pbdigit-247-ip-binding-plan.md`.
