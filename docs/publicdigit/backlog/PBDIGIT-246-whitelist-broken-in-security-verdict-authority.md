# PBDIGIT-246 — The election whitelist breaks the Security Verdict Authority (2B-1)

**Type:** Defect — security policy (P-WHITELIST) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** Q3 discovery `docs/publicdigit/reviews/2026-10-05-q3-security-policy-surface.md` (#8, §5 finding 2); characterization C1 (`SecurityPolicySurfaceCharacterizationTest`, `e7160076b`); `VotingSecurityVerdictAdapterTest` known-defect tests (PBDIGIT-239)
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Existing-story search (freeze rule):** `git grep -il whitelist docs/publicdigit/backlog` → PBDIGIT-240 (mentions it as out of scope), PBDIGIT-244 (locks the field). Neither owns the authority's whitelist evaluation. No owner exists.

| | |
|---|---|
| **Status** | 🟡 `OPEN` — **RED written (PO-authorised 2026-10-05)**; GREEN not authorised |
| **RED evidence (2026-10-05, uncommitted)** | `tests/Feature/Voting/SecurityWhitelistPolicyTest.php`: W1–W6 RED (`TypeError` in `json_decode`), W7 RED (cast refused), W8 RED (code step 302); controls W6b, W9 pass |
| **Defect (FACT [S] / [D])** | `TrustPolicyEvaluator::buildNetworkEvidence()` calls `json_decode()` on `ip_whitelist`, which the model already casts to an array, so a non-empty whitelist throws a `TypeError`. Both consumers then refuse fail-closed: the code step (`forEntry`) and the final cast (`store`). `NetworkTrustEvidence::isWhitelisted()` also compares the **hashed** request IP with **raw** list entries, so even without the `TypeError` nothing would match. Net effect: **configuring a whitelist blocks voting** instead of exempting networks |
| **Decision (Q3-D5)** | **Raw CIDR.** The list holds raw IPv4 CIDR ranges / exact IPs. The authority matches the raw request IP **inside the authority**; only the fact *whitelisted yes / no* enters the evidence. The raw IP must not appear in envelope, snapshot or security event |
| **Target contract (RED)** | **Request IP in the whitelist:** the density rule does not deny (P-WHITELIST bypass, as `NetworkBindingPolicy` already intends). **Not in the whitelist:** the density rule applies unchanged. **Non-empty whitelist:** never throws. **Empty whitelist:** unchanged behaviour. **Malformed entry:** matches nothing and doesn't throw (**confirmed by the PO 2026-10-05**). **End to end:** a whitelisted network can enter the code step and cast |
| **Scope decisions (PO 2026-10-05)** | **IPv4 only.** IPv6 ranges are out of this story's scope (the existing `Election::ipInRange()` is IPv4-only) · W5 malformed-entry rule confirmed |
| **Open points** | the start-page reader of the same list (#1) belongs to 2B-5 / Q3-D4 |
| **Out of scope** | P-DENSITY consolidation (#3, #5), `ip_restriction_*`, binding / device, tenant scope (Q3-D7), PBDIGIT-245 |
| **Replaces at GREEN** | `VotingSecurityVerdictAdapterTest` known-defect whitelist tests and characterization C1. They are replaced, not "kept green", with PO approval at GREEN |

**Traceability:** Q3 proposal §0 (D5), §3 #8, §12 2B-1 · ADR_20261005_0950 I3 · plan `docs/plans/20261005-2333-pbdigit-246-whitelist-red-plan.md`.
