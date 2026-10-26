# PBDIGIT-315: IP binding matches the voter's network, not the exact address

**Type:** voting security rule change · **Epic:** none · **Created:** 2026-10-26 · **Raised by:** the PO ("ip verification should be like: 106.101.7.000").

| | |
|---|---|
| **Status** | `IMPLEMENTED` · plan approved 2026-10-26; awaiting PO acceptance and deploy |
| **Plan** | `docs/plans/20261026-2125-ip-binding-network-match-plan.md` |
| **Supersedes** | PBDIGIT-247 decision **B3 "exact address"** only. B1 (fail closed), Q1 (the whitelist does not bypass binding) and Q2 (specific message, captured IP never shown) are unchanged |

## Problem
With IP binding on, the voter had to enter and vote from exactly the IP the officer captured. Home routers and mobile networks change the last number of the IP, so captured voters were refused and needed a re-capture.

## Rule now
| Captured | Request | Result |
|---|---|---|
| `106.101.7.23` | `106.101.7.88` | allowed (same /24) |
| `106.101.7.23` | `106.101.8.23` | refused |
| `2001:db8:1:2::10` | `2001:db8:1:2:aaaa:…` | allowed (same /64) |
| `2001:db8:1:2::10` | `2001:db8:1:3::10` | refused |
| nothing captured, or unreadable | anything | refused (fail closed) |

- An IPv4-mapped IPv6 address counts as its IPv4 address.
- Applies to every election with binding on. There is no new setting and no migration. The captured IP is still stored in full.

## Change
- New pure class `App\Domain\Election\Security\IpBindingNetwork` (`of()`, `matches()`).
- `TrustLegitimacySecurityVerdict::ipBindingSatisfied()` compares with `IpBindingNetwork::matches()` instead of the exact canonical address.
- No change to the per-IP vote limits, IP capture, the UI, or `votes`/`results`. The assessment envelope still carries no IP (B-A3).

## Evidence (TDD)
- **RED:**
  - `IpBindingNetworkTest` (6) failed because the class didn't exist.
  - B-E8, B-E10 and B-C4 failed because the voter was refused.
  - B-E9 and B-E11 already passed; they guard against loosening too far.
- **GREEN:**
  - `IpBindingNetworkTest` + `IpVoteGroupTest`: 16/16;
  - `IpBindingEntryTest` + `IpBindingCastTest` + `IpBindingSettingTest`: 24/24 (19 existing + 5 new).
- **Related suites:** `tests/Unit/Domain/Election/Security`, `VotingSecurityVerdictAdapterTest`. Same 14 failures with and without this change (pre-existing, **PBDIGIT-316**); +6 passes from the new unit tests.

## Risk accepted by the PO
- Weaker binding: others on the same /24 pass.
- A voter who moves to a different range is still refused and needs a re-capture.

## Deploy
`php artisan optimize`. Applies at once to IPs already captured.

## Developer guide
`developer_guide/election/ip_voting/09_ip_binding_network_match.md`
