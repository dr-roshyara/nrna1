# 08 — IP Binding (PBDIGIT-247)

**Story:** PBDIGIT-247 · **Commit:** this step's commit · **Plan:** `docs/plans/20261006-2125-pbdigit-247-ip-binding-plan.md` · **Builds on:** 06 (Capture IP, `voter_ip_verifications`) · **Decisions:** B1–B4, Q1 = no, Q2 = yes, capture window option (a), E2-D4 annotation (PO 2026-10-06)

## Purpose

When an election turns **IP Binding** on, a voter may get a voting code (`forEntry`) and cast the vote (`forCast`) **only from the IP address an officer captured for them in this election**.

| Situation (binding on) | Result |
|---|---|
| captured IP = request IP (exact, canonicalised) | allowed (other security rules still apply) |
| captured IP ≠ request IP | refused: `IpBindingDenied` |
| nothing captured | refused: `IpBindingDenied` (fail closed) |
| binding off | unchanged behaviour |

It is **Security Policy**, not Admission. Identity verification (05) and IP capture (06) stay separate evidence. Binding never admits anyone; it can only refuse.

## Key files

| Layer | File |
|---|---|
| Migration | `database/migrations/2026_10_06_000003_add_ip_binding_enabled_to_elections.php` |
| Model | `Election::isIpBindingEnabled()`; `ip_binding_enabled` ∈ `Election::SECURITY_POLICY_FIELDS` (locked from ReadyForVoting, PBDIGIT-244) |
| Settings | `UpdateElectionSettingsRequest::after()` (consistency) · `ElectionSettingsController` lock normaliser · `Pages/Elections/Settings/Index.vue` (IP Binding choice) |
| Evidence | `NetworkTrustEvidence::$ipBindingSatisfied` (`?bool`, fact only) |
| Policy | `NetworkBindingPolicy::IP_BINDING_NOT_SATISFIED` finding |
| Authority | `TrustLegitimacySecurityVerdict::ipBindingSatisfied()` + `verdictOf()` |
| Vocabulary | `SecurityVerdict::IpBindingDenied` → `EntryAuthorizationReason` / `CastDecisionReason::IpBindingDenied` |
| Messages | `CodeController::refuseEntry()` · `VoteController` (D.0.3a gate and cast refusal) |
| Capture window | `OperationCapabilityMapper` `capture_voter_ip` (SetupAdministration → VotingActive) · `routes/organisations.php` |

## How it works

**1. The match runs inside the authority, and only the fact leaves it** (same rule as the PBDIGIT-246 whitelist):

```php
private function ipBindingSatisfied(Election $election, string $userId, string $clientIp): ?bool
{
    if (! $election->isIpBindingEnabled()) {
        return null;                                   // binding not required
    }
    $captured = VoterIpVerification::where('election_id', $election->id)
        ->where('user_id', $userId)->value('ip_address');
    // canonicalise both (inet_pton → inet_ntop): 2001:0db8::0001 === 2001:db8::1
    …
    return $bound !== null && $bound === $canonical($clientIp);   // no capture ⇒ false
}
```

**2. The policy turns `false` into a finding,** before the whitelist and density checks (Q1: the whitelist never bypasses binding):

```php
if ($ctx->network->ipBindingSatisfied === false) {
    return new PolicyFinding(ConstitutionalConcernLevel::HIGH, EvidenceWeightCategory::STRONG,
        self::IP_BINDING_NOT_SATISFIED, ['ip_binding_required' => true], $this->identifier());
}
```

The chain maps the finding to `INSUFFICIENT_EVIDENCE`, which gives `LegitimacyOutcome::Denied`.

**3. The verdict names the reason.** This is the one narrow exception to E2-D4:

```php
LegitimacyOutcome::Denied => $reason === NetworkBindingPolicy::IP_BINDING_NOT_SATISFIED
    ? SecurityVerdict::IpBindingDenied
    : SecurityVerdict::Denied,
```

`VotingAuthorization` maps it to `IpBindingDenied`. `CodeController` and `VoteController` show *"You can only vote from your verified IP address. Please contact the election committee."* They never show the captured IP.

**4. Settings consistency** (`after()`): the effective values (request, else stored) may not end as *binding on + verification off*.
- If binding is being turned on, the error is on `ip_binding_enabled`.
- If verification is being turned off, the error is on `ip_verification_enabled`.

**5. Re-capture while locked (B4):** the policy is locked from ReadyForVoting, but the captured **value** may be re-captured by the Chief / Deputy through `elections.voters.capture-ip`. That route uses its own `capture_voter_ip` window, which deliberately does not reuse `verify_voter_identity`.

## How to use / operate

1. Settings → Voter's IP Verification: **Enabled**. Then IP Binding: **Enabled** (before Ready for Voting).
2. For each voter, during the pre-vote check (video call), the officer clicks **Verify IP Address**. The server captures the voter's last-login IP.
3. If a voter's IP changes, they log in from the new network and the officer re-captures.

## Settings across the lifecycle (PBDIGIT-254)

| State | Security policy (IP restriction, whitelist, IP Binding) | Other settings (IP verification, no-vote, selection) |
|---|---|---|
| before Ready for Voting | editable | editable |
| Ready for Voting | **locked**: refused, shown disabled with a note | editable |
| **Voting active** | **frozen**: every change refused (*"Election settings cannot be changed while voting is in progress."*); Save and all fields disabled | frozen |

- `ElectionSettingsController::edit` passes `securityPolicyLocked` and `settingsFrozen`.
- The page shows the server's refusal messages (`settings-save-errors`).
- **Freezing the policy does not stop voter actions:** officers still verify identity and capture IPs on Voter Management during voting (PO: "IP address can be verified but policy must remain same").

## Testing

| Suite | Covers |
|---|---|
| `tests/Feature/Voting/IpBindingEntryTest.php` | B-E1–E7 (code step), B-A1–A3 (authority, whitelist, envelope privacy) |
| `tests/Feature/Voting/IpBindingCastTest.php` | B-C1–C3 (final cast, nothing consumed on refusal) |
| `tests/Feature/Election/IpBindingSettingTest.php` | B-S1–S6 (consistency, lock, re-capture while voting, `ipBinding.active`) |
| `tests/Frontend/ElectionSettingsIpBinding.spec.js` | B-UI1–4 |
| `VotingAuthorizationForEntryTest` / `ForCastTest` | vocabulary guards, now including `IpBindingDenied` |

## Pitfalls

- **Dynamic IPs:** an exact match refuses a voter whose provider changed their IP; re-capture is the remedy.
- **Proxies:** binding compares `request()->ip()`. Trusted-proxy configuration must be correct in production, or every voter behind the proxy looks like the proxy.
- **Voter Management during voting:** the page itself is still 403 from SetupNomination (`import_voters` gate), so the re-capture button cannot be reached in the UI yet. This is **PBDIGIT-251**.
- **Do not add other sub-reasons to `SecurityVerdict`.** The E2-D4 exception is for IP Binding only.

**Traceability:** PBDIGIT-247 · PBDIGIT-222 · PBDIGIT-240 / 244 / 246 · Q3-D1 / D2 · E2-D4 annotation 2026-10-06 · PBDIGIT-251 · ADR-T11 (no voter ↔ ballot link: binding is checked before the ballot, and the refusal reason is never stored next to ballot data).
