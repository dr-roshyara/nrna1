# Plan — PBDIGIT-247 IP Binding (P-BINDING), TDD first

**Story:** `docs/publicdigit/backlog/PBDIGIT-247-p-binding-registered-network-binding.md` · **Status:** PROPOSED, awaiting PO approval (EP-01). No RED and no production change before approval.
**Builds on:** PBDIGIT-222 (`voter_ip_verifications`, `ip_verification_enabled`, commit `284c9fdea`) · PBDIGIT-240 (one Security Verdict Authority) · PBDIGIT-244 (security-policy lock) · PBDIGIT-246 (whitelist inside the authority) · Q3-D1 / D2.

## Objective

When an election turns IP Binding on, a voter may get a voting code and cast the vote **only from the IP address an officer captured for them in this election**. Today IP verification is evidence only and blocks nothing.

## Business rule and PO decisions (2026-10-06)

| # | Decision |
|---|---|
| **B1 Rule** | **Captured AND matching, fail closed.** With binding on, a voter **without** a captured IP is refused, and a voter whose current IP **differs** from the captured IP is refused. |
| **B2 Switch** | **New locked field** `elections.ip_binding_enabled` (boolean, default false), added to `Election::SECURITY_POLICY_FIELDS` (locked from ReadyForVoting, PBDIGIT-244). It is orthogonal to the density rule (`network_binding_strategy`), which stays as it is. It explicitly extends the Q3-D1 policy set. |
| **B3 Match** | **Exact address.** IPv4 or IPv6, compared after canonicalisation (`inet_pton` → `inet_ntop`), so `2001:db8:0::1` equals `2001:db8::1`. |
| **B4 Re-capture** | **Allowed, audited, after ReadyForVoting.** The binding *policy* is locked; the captured *value* may be re-captured by the Chief / Deputy through the existing capture endpoint (server-side last-login IP, `voter_ip_captured` audit). |

## Strategic DDD

- **Owner:** the Elections context's **Security Policy**, evaluated only by the Security Verdict Authority (`TrustLegitimacySecurityVerdict`) at `VotingAuthorization::forEntry()` (code step) and `forCast()` (final cast), per Q3-D2.
- **Not Admission:** identity and email evidence and `VotingEntitlementQuery` are untouched. Binding never admits anyone; it can only refuse.
- **Evidence consumed:** the PBDIGIT-222 capture (`voter_ip_verifications`), which is read-only for the authority. IP verification stays an evidence setting; binding is the policy that consumes it.
- **Anonymity (ADR-T11):** no new voter ↔ ballot link. The check happens before the ballot and records no vote data. The cast-time refusal is logged without ballot content.

## Design

1. **Migration:** `ip_binding_enabled` boolean, default false, reversible. The PO applies it to the dev DB.
2. **`Election`:**
   - `ip_binding_enabled` in fillable / casts and in `SECURITY_POLICY_FIELDS`;
   - `isIpBindingEnabled()`.
3. **Settings:**
   - `UpdateElectionSettingsRequest`: `ip_binding_enabled` boolean;
   - **consistency rule:** binding may be on only while `ip_verification_enabled` is on. Turning binding on with verification off is refused, and so is turning verification off while binding is on, because otherwise re-capture becomes impossible;
   - Settings page: an "IP Binding" choice inside the "Voter's IP Verification" section, with the text "Voters can only get a voting code and vote from their verified IP address", shown locked from ReadyForVoting like the other security-policy fields.
4. **Authority (`TrustLegitimacySecurityVerdict::assess`):**
   - when `isIpBindingEnabled()`, read this election's `voter_ip_verifications.ip_address` for the voter;
   - canonicalise it and the request IP, then hash both with `TrustEvidencePrivacyPolicy::hashIp(…, election id)` as today;
   - carry the binding facts into `NetworkTrustEvidence`: a new `bindingRequired` flag, plus the existing `registeredIpHash`.
5. **Policy (`NetworkBindingPolicy`):**
   - when `bindingRequired`, a missing registered hash produces the finding `ip_binding_not_captured`, and a mismatch produces `ip_binding_mismatch`;
   - both are strong / high findings whose legitimacy outcome is **Denied** (a RED test proves the outcome);
   - the whitelist does **not** bypass binding (decision needed, see open question Q1).
   - The density rule is unchanged.
6. **Entry / cast:**
   - no new evaluation sites (Q3-D2);
   - `CodeController::refuseEntry` and `VoteController` already map `SecurityDenied` to a refusal.
   - **Message:** add an IP-binding-specific message only if the verdict carries the reason; otherwise the existing "contact the election committee" text stands (open question Q2).
7. **Voter Management:** `ipBinding.active` = `isIpBindingEnabled()` (today hard-coded `false`), displayed only. The IP column stays driven by `captureIp`.

**Unchanged:**
- `NetworkContinuityObservation` (an observation overlay only);
- the inert legacy copies (`validate.voting.ip` middleware, `validateVotingIpWithResponse`, `users.voting_ip`), whose retirement stays 2B-6 / PBDIGIT-234;
- device binding;
- `voter_verification_mode`.

## RED (tests first, after approval)

**`tests/Feature/Voting/IpBindingEntryTest.php`** (entry: `slug.code.create` / `forEntry`):
- **B-E1:** binding on, captured, same IP → allowed.
- **B-E2:** binding on, captured, different IP → refused; no code created or sent.
- **B-E3:** binding on, nothing captured → refused (fail closed).
- **B-E4:** binding off, different IP → allowed (unchanged behaviour).
- **B-E5:** IPv6 written two ways (canonical vs. expanded) → allowed.
- **B-E6:** a capture in **another** election does not bind or unbind this one.
- **B-E7:** after an officer re-captures (new IP), the voter is allowed from the new IP and refused from the old one.

**`tests/Feature/Voting/IpBindingCastTest.php`** (final cast: `forCast`):
- **B-C1:** binding on, the IP changes between the code step and the cast → cast refused; no vote row; `has_voted` unchanged.
- **B-C2:** a matching IP at the cast → the vote is recorded.

**`tests/Feature/Election/IpBindingSettingTest.php`:**
- **B-S1:** the Chief enables binding while IP verification is on → stored.
- **B-S2:** enabling binding with IP verification off → refused.
- **B-S3:** turning IP verification off while binding is on → refused.
- **B-S4:** changing binding from ReadyForVoting → refused (PBDIGIT-244 lock; `ip_binding_enabled` in `SECURITY_POLICY_FIELDS`).
- **B-S5:** re-capture after ReadyForVoting with binding on → allowed (B4).
- **B-S6:** Voter Management `ipBinding.active` follows the setting.

**Authority unit / adapter:** `VotingSecurityVerdictAdapterTest`'s oracle stays green with binding off; new cases give a binding mismatch → `SecurityVerdict::Denied`, and binding off → identical to the oracle.

**Frontend:** a Settings spec for the binding choice (disabled while IP verification is off; payload carries `ip_binding_enabled`).

## Verification

1. Run RED, then STOP for review.
2. GREEN, then STOP.
3. Targeted suites (`CodeEntryAuthorizationTest`, `CastDecisionEnforcementTest`, `VotingSecurityVerdictAdapterTest`, `SecurityPolicyLifecycleLockTest`, PBDIGIT-222 / 250 suites), then the frontend suite and `design-check`.
4. Full 97-suite regression vs 582 / 333 / 19, with no pass → fail.
5. The PO applies the migration and checks by hand: binding on, voting from a different IP is refused at the code step.

## Risks

- **Voter lock-out on dynamic IPs:** exact match (B3) refuses a voter whose provider changed their IP. The mitigation is officer re-capture (B4); the Settings text must say so.
- **The re-capture source is the last-login IP:** a voter must log in from the voting network before the officer re-captures.
- **Proxies / load balancers:** the binding compares `request()->ip()`; trusted-proxy configuration must be correct in production (Operational observation, not changed here).
- **Changing the authority's oracle:** binding must be a pure addition (off ⇒ identical outcome). The adapter test proves it.

## Decided at approval (PO 2026-10-06)

- **Q1 = no:** the IP whitelist does **not** bypass binding.
- **Q2 = yes:** a specific voter message, *"You can only vote from your verified IP address. Please contact the election committee."*, at the code step and at the cast. It never reveals the captured IP. **Consequence for GREEN:** the refusal reason must reach `CodeController` / `VoteController`, because today `SecurityVerdict` carries only Allowed / Denied / ReviewRequired.

## Out of scope

Device binding (P-DEVICE) · retiring the inert P-BINDING copies (2B-6 / PBDIGIT-234) · subnet matching · voter self-capture · Q3-D4 / D7.

## Traceability

PBDIGIT-247 · PBDIGIT-222 · PBDIGIT-234 · PBDIGIT-240 / 244 / 246 · Q3 proposal §4 P-BINDING (D6 superseded) · ADR_20261005_0950 I3 / I9 · ADR-T11.

## Progress

- [x] Readiness (code read: authority, evaluator, NetworkBindingPolicy, continuity overlay, Q3); PO decisions B1–B4
- [x] PO approval of this plan, Q1 = no, Q2 = yes (2026-10-06)
- [x] RED written (2026-10-06):
  - `tests/Feature/Voting/IpBindingEntryTest.php` (B-E1–E7, B-A1–A3), `IpBindingCastTest.php` (B-C1–C3), `IpBindingFixtures.php`;
  - `tests/Feature/Election/IpBindingSettingTest.php` (B-S1–S6);
  - `tests/Frontend/ElectionSettingsIpBinding.spec.js` (B-UI1–4).
  - All 23 fail: 18 on the missing `ip_binding_enabled` column, B-S2 on the missing refusal, B-UI1–4 on the missing choice.
- **Finding at RED (needs a PO decision before GREEN):**
  - the capture route is in the `import_voters` group, and `canManageVoters` is false from SetupNomination onward, so today an officer cannot capture or re-capture after SetupAdministration;
  - **B4 requires a capture window up to VotingActive** (B-S5 encodes it).
  - **Resolved, PO option (a):** a new `capture_voter_ip` operation (SetupAdministration → VotingActive); the capture route has its own group.
- **Decision at GREEN (PO 2026-10-06):**
  - Q2 conflicted with E2-D4 option A ("no sub-reason"). Resolved by **one new verdict value**, `SecurityVerdict::IpBindingDenied` → `Entry/CastDecisionReason::IpBindingDenied`, annotated in the E2-D4 record.
  - Consequences:
    - the B-A1 / A2 / E7 assertions expect `IpBindingDenied`;
    - the vocabulary guards in `VotingAuthorizationForEntryTest` / `VotingAuthorizationForCastTest` gained exactly that value;
    - the cast's D.0.3a gate shows the binding message.
- [x] GREEN (2026-10-06):
  - migration `2026_10_06_000003`;
  - `Election` (`SECURITY_POLICY_FIELDS`, `isIpBindingEnabled`);
  - settings request `after()` consistency rule, and the lock normaliser;
  - `NetworkTrustEvidence::ipBindingSatisfied` (fact only);
  - `NetworkBindingPolicy` finding `ip_binding_not_satisfied` (before the whitelist and density checks);
  - `TrustLegitimacySecurityVerdict` (canonicalised exact match, verdict mapping);
  - `VotingAuthorization`, Code / Vote controller messages;
  - `capture_voter_ip` window;
  - `ipBinding.active` prop;
  - the Settings choice.
  - Results:
    - 19 + 8 PBDIGIT-247 tests pass;
    - the neighbouring suites pass;
    - the unit security suites are identical to the earlier commit (6 pre-existing failures);
    - frontend 316 passed / 3 skipped;
    - design-check 91.
- **Discovered (not in scope, backlog `PBDIGIT-251`):**
  - the Voter Management page itself is 403 from SetupNomination (the `import_voters` gate blocks GET), so officers cannot reach the identity / IP buttons during voting;
  - PO constraint: contact data unchangeable, verification allowed.
- **Recorded, not done (PO "don't combine now"):** one combined identity + IP officer action. Separate buttons are correct for now.
- [ ] Full regression → STOP · commit (PBDIGIT-247) on approval
- [ ] RED → STOP · GREEN → STOP · regression → STOP · commit (PBDIGIT-247) on approval
