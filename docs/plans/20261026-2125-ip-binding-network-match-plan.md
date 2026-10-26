# Plan: IP binding matches the voter's network, not the exact address (PBDIGIT-315)

**Status:** IMPLEMENTED · approved by the PO 2026-10-26 ("yes, go ahead with strict tdd first way"); awaiting acceptance
**Story:** `docs/publicdigit/backlog/PBDIGIT-315-ip-binding-network-match.md`
**Supersedes (one decision only):** PBDIGIT-247 decision **B3 "exact address"** (`docs/plans/20261006-2125-pbdigit-247-ip-binding-plan.md`). B1 (fail closed), Q1 (the whitelist does not bypass binding) and Q2 (specific message, captured IP never shown) stay.

## Objective
With IP binding on, a voter captured at `106.101.7.23` may enter and vote from any `106.101.7.x`. A different network is still refused.

## Background
- Binding compares the request IP with the captured IP (`voter_ip_verifications`) exactly (`TrustLegitimacySecurityVerdict::ipBindingSatisfied()`).
- Home routers and mobile networks change the last octet, so captured voters are refused and need a re-capture.
- PO request 2026-10-26: "ip verification should be like: 106.101.7.000".

## Scope
- **The rule:**
  - IPv4 matches on its /24 network (first three numbers).
  - An IPv4-mapped IPv6 address counts as its IPv4 address.
  - IPv6 matches on its /64 network, the same grouping as the per-IP vote limit.
  - An unreadable address never matches, so binding still fails closed.
- **Where it applies:** every election with `ip_binding_enabled`. No per-election setting and no migration (PO 2026-10-26).
- **Code:**
  - new pure value class `App\Domain\Election\Security\IpBindingNetwork`;
  - the one comparison in `TrustLegitimacySecurityVerdict::ipBindingSatisfied()` uses it.
- **Not in scope:**
  - the per-IP vote limits (`IpVoteLimit`, System B);
  - IP capture (`VoterIpCaptureController`);
  - the stored captured IP (still the full address);
  - the UI;
  - the legacy `ValidateVotingIp`.
- **Anonymity (ADR-T11):** nothing new is stored, and nothing is written to `votes`/`results`. The assessment envelope still carries no IP (B-A3).

## Design decisions
- **D1:** use a /24 (IPv4) and /64 (IPv6) network key. The constants live in the new class; they are not configurable (YAGNI).
- **D2:** a separate class rather than reusing `IpVoteGroup`. Vote-limit grouping (IPv4 exact) and binding (IPv4 /24) are different rules and must be able to change independently.
- **D3:** an unreadable address gives `null`, and `null` never matches (fail closed, B1).

## TDD
- **RED, unit** (`tests/Unit/Domain/Election/Security/IpBindingNetworkTest.php`, database-free):
  - same /24 equal, adjacent /24 different;
  - mapped IPv4 equals plain;
  - IPv6 same /64 equal, different /64 different;
  - unreadable → null and never matches.
- **RED, feature:**
  - `IpBindingEntryTest`:
    - B-E8: captured `.23`, enters from `.88` → allowed;
    - B-E9: adjacent /24 → refused;
    - B-E10: IPv6 same /64 → allowed;
    - B-E11: IPv6 other /64 → refused.
  - `IpBindingCastTest` B-C4: captured `127.0.0.55`, cast from `127.0.0.1` → recorded.
- **GREEN:** the minimal change above.
- **Regression:** all 19 existing binding tests stay green (their IPs are in different /24 networks).

## Task checklist
- [x] Plan and story
- [x] RED unit and feature tests (failing for the right reason)
- [x] GREEN
- [x] Binding suites, `IpVoteGroupTest`, and the security unit folder. 14 failures there are pre-existing (identical without the change), recorded as PBDIGIT-316
- [x] Developer guide `developer_guide/election/ip_voting/09_ip_binding_network_match.md` and index
- [x] Session log, CONTEXT-publicdigit, backlog index; one commit `(PBDIGIT-315)`

## Risks
- Weaker binding: other people on the same /24 (for example the same mobile operator range) pass. Accepted by the PO.
- A mobile voter who moves to a different range is still refused and needs a re-capture.

## Operational note
- Applies from deploy (`php artisan optimize`) and to captures already made, because the stored full IP is compared by network.

## Open questions
None.
