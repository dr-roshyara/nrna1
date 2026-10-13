# PBDIGIT-288: IP limit enforcement gaps, visibility and IP data minimisation (medium findings)

**Type:** Security / privacy hardening · **Epic:** none (voting) · **Created:** 2026-10-13 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from the IP voting strategy review (medium and low findings). **Index row:** added 2026-10-13.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · priority medium |
| **Classification** | verified in code unless marked (H) |

## Findings
| # | Finding | Evidence |
|---|---|---|
| 1 | **`IpVoteLimit` is not checked at the final cast.** Only the trust policy applies there; `vote_post_check` is off the real-election cast path | `VoteController.php:1767-1772` |
| 2 | **Race:** the cast lock is per voter, not per IP. Several voters on one IP who are mid-flow at the same time can all cast and exceed a limit (exploitation: H) | `VoteController.php:1636-1642`; `TrustLegitimacySecurityVerdict.php:68-71` |
| 3 | **`codes.client_ip` is the code-entry IP, not the casting IP.** A voter who changes network between the two is counted under the earlier IP | `CodeController.php:798, :939`; the cast's compare-and-set doesn't write `client_ip` |
| 4 | **IPv6 is counted per exact address:** no /64 grouping or canonicalisation, so one household can rotate addresses (bypass in practice: H) | `IpVoteLimit::votesFrom` |
| 5 | **Admin visibility:** an `ip_blocked` audit entry is written only at start; code-entry and verification refusals are only logged; its `max` metadata records `ip_restriction_max_per_ip` even when another limit applied | `ElectionVotingController.php:148-161` |
| 6 | **Privacy:** full IPs are stored (`codes.client_ip`, `voter_ip_verifications.ip_address`, audit JSONL "stored in full", `voting_audit` log) with **no retention or purge**; the IP "hash" is unkeyed `sha256(ip . electionId)`, which can be brute-forced | `TrustEvidencePrivacyPolicy.php:7-10`; `ElectionAuditService.php` |
| 7 | **Settings page:** it doesn't say that the global and constitutional limits still apply when the restriction is off; a stale whitelist stays active while hidden | `Elections/Settings/Index.vue` |
| 8 | **Off-by-one between the limits:** the trust policy refuses at `>`, `IpVoteLimit` at `>=` | `NetworkTrustEvidence.php:32`; `IpVoteLimit.php` |
| 9 | **Dead IP code:** `users.voting_ip` (no such column) behind `ValidateVotingIp` and `validateVotingIpWithResponse` (no-ops); `check_ip_address` (string-built SQL, reachable only without an election id); `step_1_ip` (never written); `voting_ip_mode`. Writers of `voting_ip` (`ElectionProcessController`, `BulkApproveVoters`) may fail on PostgreSQL (H) | as cited in the review |

## Acceptance
- **Split into stories** once `PBDIGIT-286` decides the limit model.
- **Suggested order:**
  1. cast-time enforcement with an IP-scoped lock, and `client_ip` recorded at the vote (1–3);
  2. audit at every refusal (5);
  3. a retention policy and keyed hashing (6, together with `PBDIGIT-281`);
  4. UI wording (7);
  5. dead-code removal (9).

## Related
`PBDIGIT-284` · `PBDIGIT-285` · `PBDIGIT-286` · `PBDIGIT-287` · `PBDIGIT-281` (retention) · `PBDIGIT-278` items 9–10.

## Decisions (Human PO, 2026-10-13)
- **The counted IP is the IP at the vote** (not the code-entry IP). The check and a per-election, per-IP lock happen at the vote.
- **IPv6 is grouped by /64** for counting (configurable prefix); IPv4 stays per address.
- **Order:** plan step 2 (items 1–3), then step 3 (item 5), then the rest.

## Progress (2026-10-13)
- **Items 1–4 implemented (plan step 2), not yet committed:**
  - `IpVoteLimit::castRefusal()` at the vote under a PostgreSQL advisory lock per election and IP group;
  - `codes.client_ip` = the IP at the vote;
  - IPv6 counted per /64 (`IpVoteGroup`, configurable).
- **Evidence:** `docs/publicdigit/election/evidence/2026-10-13-pbdigit288-ip-limit-at-vote/`; guide `developer_guide/election/ip_voting/03_ip_limit_at_the_vote.md`.
- **Still open:** items 5–9. Next is item 5 (plan step 3).
- **Unchanged by this step:** the constitutional limit still counts by exact address (`PBDIGIT-286`).
- **Item 5 implemented (plan step 3), not yet committed.** `IpVoteLimit::recordRefusal()` writes one `ip_blocked` audit entry at start, code entry, verification and the vote, recording `reason`, `step`, `max` (the limit that applied) and `limit_source`. Evidence: `docs/publicdigit/election/evidence/2026-10-13-pbdigit288-ip-refusal-audit/`; guide `04_ip_refusal_audit.md`.
