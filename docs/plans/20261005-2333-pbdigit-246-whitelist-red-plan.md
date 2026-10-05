# Plan — 2B-1 RED: the election whitelist inside the Security Verdict Authority (PBDIGIT-246)

**Status:** RED authorised (PO 2026-10-05) · GREEN not authorised · **Story:** `docs/publicdigit/backlog/PBDIGIT-246-whitelist-broken-in-security-verdict-authority.md` · **Decision:** Q3-D5 raw CIDR

## Objective

Tests only. They define the whitelist contract inside the authority and fail at HEAD for the known reasons: the `TypeError`, and the hashed-vs-raw comparison.

## Tests: `tests/Feature/Voting/SecurityWhitelistPolicyTest.php` (synthetic data, Phase 1 cast fixtures)

**Fixtures:**
- `network_binding_strategy = ip_count`, `max_votes_per_ip = 6`, so an unverified voter's limit is 3. Every "above the limit" case uses 4 voted codes from the IP.
- The whitelist is written with the query builder, because the PBDIGIT-244 model guard refuses it on a voting election.

| ID | Case | Expected (contract) | At HEAD |
|---|---|---|---|
| W1 | IP inside a whitelisted CIDR, above the limit | `Allowed` | RED (`TypeError`) |
| W2 | IP equal to a whitelisted exact entry, above the limit | `Allowed` | RED |
| W3 | non-empty whitelist, IP outside it, above the limit | `Denied` | RED |
| W4 | non-empty whitelist, IP outside it, below the limit | `Allowed` | RED |
| W5 | malformed entry only, above the limit | `Denied`, no exception (PO-confirmed) | RED |
| W6 | whitelisted assessment: the raw request IP appears nowhere in the serialized envelope | holds | RED (`TypeError`) |
| W6b | control: empty whitelist, the raw IP appears nowhere in the envelope | holds | expected pass (guard) |
| W7 | end to end: whitelisted request network, above the limit → final cast succeeds | vote cast | RED (fail-closed refusal) |
| W8 | end to end: whitelisted request network, above the limit → code step page opens and a code is issued | 200 + code | RED (fail-closed refusal) |
| W9 | control: empty whitelist, above the limit | `Denied` (unchanged) | expected pass (guard) |

**Scope (PO 2026-10-05):** IPv4 only — IPv6 ranges are outside this story. W5 rule confirmed.

## Not changed in RED

- Production code.
- `VotingSecurityVerdictAdapterTest` known-defect tests and characterization C1. Their replacement is part of the GREEN approval.

## Task checklist

- [x] story + README row, Q3 §0 D5 recorded
- [x] suite written, linted, run → W1–W8 RED for the stated reason, W6b / W9 pass
- [x] report → STOP

## Progress (RED run 2026-10-05)

- **W1–W6: RED with `TypeError: json_decode(): Argument #1 ($json) must be of type string, array given`** (`TrustPolicyEvaluator.php:140`).
- **W7: RED.** The final cast is refused (session errors, no vote).
- **W8: RED.** The code step answers 302 instead of 200.
- **W6b and W9 (controls): pass.** Without a whitelist, the envelope carries no raw request IP and the density rule denies above the limit.
- **Production diff:** empty.
