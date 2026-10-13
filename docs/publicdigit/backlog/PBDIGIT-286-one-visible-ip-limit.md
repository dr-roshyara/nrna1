# PBDIGIT-286: The constitutional IP limit is hidden, effectively 3, and not controlled by the admin switch

**Type:** Product / security design · **Epic:** none (voting) · **Created:** 2026-10-13 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from the IP voting strategy review (finding C). **Index row:** added 2026-10-13.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · **needs a PO decision first** |
| **Background decision** | Human PO 2026-10-13 (PBDIGIT-278 item 8): keep `IpVoteLimit` and the constitutional limit **separate**; at code entry the stricter applies. This item is about making the constitutional limit **visible and controllable**, not about merging them silently. |

## Finding (verified in code)
- **The trust policy** (`TrustPolicyEvaluator`, `TrustLegitimacySecurityVerdict`, `NetworkTrustEvidence`, `NetworkThresholdInterpreter`) applies `elections.max_votes_per_ip` (default 6) **scaled by trust level** at code-entry authorization and at the final cast.
- **The trust level is always the lowest:** `attested = false` is hard-coded (`TrustPolicyEvaluator.php:178`, "populated by controller from VoterVerification record", but it never is). So the effective limit is ⌊6/2⌋ = **3**. With its `>` comparison (`NetworkTrustEvidence.php:32`), it refuses from about the 5th vote per IP.
- **The admin switch doesn't control it:** it is turned on by `network_binding_strategy !== 'none'` (default `ip_count`), **not** by the admin's "IP restriction" toggle (`ip_restriction_enabled`).
- **Admins can't see it:** `max_votes_per_ip` is not exposed in any request, controller or settings page. An admin setting of 50 does not relax it. This is characterised in `SecurityPolicySurfaceCharacterizationTest` ("a loose settings limit does not relax the authority").
- **Inconsistent numbers:**
  - defaults 4 (`ip_restriction_max_per_ip`), 6 (`max_votes_per_ip`), 10 (`IpVoteLimit`) and 7 (dead demo field);
  - validation ranges 1–50 vs 1–100;
  - comparisons `>=` vs `>`.
- **What voters see:** "constitutional verification" denials don't mention the network.

## Decision needed
1. **Is the constitutional per-IP limit still wanted?** If so, show and set it on the election settings page, next to the IP restriction, with clear wording.
2. **`attested`:** populate it from the voter-verification record as designed, or remove the trust-level scaling.
3. **Which switch turns which limit on**, and aligned defaults, validation ranges and comparisons.

## Acceptance (after the decision)
- [ ] Admins can see and control every per-IP limit that can refuse a voter.
- [ ] Characterisation tests are updated to the decided behaviour (RED → GREEN).
- [ ] Refusal messages say that the network limit was reached.

## Related
`PBDIGIT-278` item 8 · `PBDIGIT-285` · `PBDIGIT-288`.
