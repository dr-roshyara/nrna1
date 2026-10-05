# 10 — The election IP whitelist inside the Security Verdict Authority (PBDIGIT-246, 2B-1)

**Story:** PBDIGIT-246 · **RED:** `902060e92` · **GREEN:** this step's commit · **Decision:** Q3-D5 raw CIDR (`docs/publicdigit/reviews/2026-10-05-q3-security-policy-surface.md` §0)

## Purpose

`ip_whitelist` (P-WHITELIST) exempts listed networks from the density rule (P-DENSITY). Before this step, a non-empty whitelist made the authority throw. `json_decode()` was called on a value the model already casts to an array, so both voting steps refused, fail-closed. Even without that error, the hashed request IP was compared with raw entries and could never match. So configuring a whitelist blocked voting.

## How it works now

```php
// TrustPolicyEvaluator::evaluate() — the only place the raw request IP is used beyond hashing
$whitelisted = $election !== null && Ipv4Whitelist::fromEntries($election->ip_whitelist)->contains($rawIp);

// buildNetworkEvidence(): only the fact enters the evidence
$whitelist = $whitelisted ? [$ipHash] : null;   // NetworkTrustEvidence::isWhitelisted() unchanged
```

- **`App\Domain\Election\Security\Ipv4Whitelist`** (pure PHP) matches IPv4 CIDR ranges (`10.88.0.0/16`, `0`–`32` bits) and exact addresses. Malformed entries, prefixes above 32 and non-IPv4 request IPs **match nothing and never throw** (PO-confirmed). It accepts the array cast, a JSON string or `null`.
- **`NetworkBindingPolicy`'s existing bypass applies unchanged.** A whitelisted IP gets no density finding; any other IP keeps the density rule.
- **Privacy (Q3-D5):** the raw IP never appears in `NetworkTrustEvidence`, the envelope, the snapshot or the security event. The evidence holds only the current IP's hash (W6).

**Scope:** IPv4 only (PO). The start-page reader of the same list (`Election::isIpWhitelisted()`, `resolveIpBlock`) is unchanged; it belongs to 2B-5 / Q3-D4.

## Testing

- **`tests/Feature/Voting/SecurityWhitelistPolicyTest.php` W1–W9:**
  - CIDR, exact, outside, below the limit, malformed entries, privacy;
  - end to end: the cast and the code step;
  - controls.
- **`VotingSecurityVerdictAdapterTest` a07:** a whitelisted IP above the limit gets the same verdict (Allowed) from the adapter and the frozen oracle. It replaced the two known-defect tests (PO-approved).
- **`SecurityPolicySurfaceCharacterizationTest` C1** was replaced (PO-approved); C2–C6 are unchanged.
- **Regression (97 suites) vs the PBDIGIT-240 run:** 583/333/19 → 582/333/19, with no pass → fail. The −1 is the approved replacement of the two known-defect tests by a07.

## Pitfalls

- Never compare raw entries with the hashed IP. Match on the raw IP, then pass only the boolean.
- Do not log or store the raw request IP in security evidence.
- Fixtures on a voting election must write `ip_whitelist` with the query builder (PBDIGIT-244 model guard).

**Traceability:** Q3 §3 #8, §12 2B-1 · PBDIGIT-246 · plan `docs/plans/20261005-2333-pbdigit-246-whitelist-red-plan.md`.
