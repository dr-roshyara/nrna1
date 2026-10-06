# 06 — Capture IP Address and the "Voter's IP Verification" setting (PBDIGIT-222)

**Story:** PBDIGIT-222 · **Commit:** this step's commit · **Plan:** `.claude/plans/wobbly-foraging-pillow.md` · **Decision basis:** ADR_20261004_1549 inv. 4 (IP / device ≠ identity admission), ADR_20261005_0950 (security policy, IP-D3 = c)

## Purpose

Capturing a voter's IP address used to share the identity endpoint, so an IP capture became identity-admission evidence. It is now a **separate capability** with its own table, endpoint and election setting:

| Concept | Storage | Owner | Enforces anything? |
|---|---|---|---|
| Identity Verification | `voter_verifications` | Admission (PBDIGIT-248) | yes, when the admission policy requires it |
| **Voter's IP Verification** | `voter_ip_verifications` | **this step** | **no**: evidence only |
| IP Binding | — (not built) | Security Verdict Authority (PBDIGIT-247) | later |

Never merge these three.

## Key files

| Layer | File |
|---|---|
| Migrations | `database/migrations/2026_10_06_000001_create_voter_ip_verifications_table.php` · `2026_10_06_000002_add_ip_verification_enabled_to_elections.php` |
| Model | `app/Models/VoterIpVerification.php` · `Election::isIpVerificationEnabled()` |
| Endpoint | `app/Http/Controllers/Election/VoterIpCaptureController.php` → route `elections.voters.capture-ip` (`routes/organisations.php`, `import_voters` group) |
| Settings | `UpdateElectionSettingsRequest` (`ip_verification_enabled`; `voter_verification_mode` now `sometimes`) · `Pages/Elections/Settings/Index.vue` |
| Voter Management | `ElectionVoterController::index` (`captureIp`, `ipVerification`, `ipBinding`) · `Pages/Elections/Voters/Index.vue` · `Components/Election/VoterVerificationModal.vue` |

## How it works

**The setting.** `elections.ip_verification_enabled` (boolean, default `false`) decides whether officers may capture IPs in this election. It is **not** one of `Election::SECURITY_POLICY_FIELDS`, so the PBDIGIT-244 lock does not apply and it can change in any lifecycle state.

```php
public function isIpVerificationEnabled(): bool
{
    return (bool) ($this->ip_verification_enabled ?? false);
}
```

**The endpoint** (`VoterIpCaptureController::store`), in order:

1. `authorize('manageSettings', $election)`: the election's active Chief or Deputy.
2. **Fail closed** when the setting is off:
   ```php
   if (! $election->isIpVerificationEnabled()) {
       return back()->withErrors(['ip_verification' => 'IP verification is not enabled for this election.']);
   }
   ```
3. Validate:
   - `user_id` (uuid);
   - `notes` (nullable, ≤ 1000);
   - `ip_address`, `verified_ip` and the device fields are **`prohibited`**: the client never supplies the IP.
4. The target must have an **active** voter membership of this election.
5. The server reads `users.current_ip` (the last login) and accepts any valid IPv4 or IPv6 address (`FILTER_VALIDATE_IP`). An unknown or invalid IP is refused.
6. `VoterIpVerification::updateOrCreate(['election_id', 'user_id'], …)`: one current row per election + voter; a re-capture replaces it.
7. Audit `voter_ip_captured` (committee category).

It never writes `voter_verifications`, never changes admission or entitlement, and never calls the Security Verdict Authority.

**The page props** (`ElectionVoterController::index`):

```php
'captureIp'      => ['active' => $election->isIpVerificationEnabled()],
'ipVerification' => $this->ipCaptures($election, $userIds),   // user_id → {ip_address, captured_at, notes}; [] when off
'ipBinding'      => ['active' => false],                       // PBDIGIT-247; drives nothing
```

`Voters/Index.vue` shows the **IP Address** column only when `captureIp.active` is true. The cell shows:
- the captured state ("✓ Captured" + time, or "Not captured");
- **Verify IP Address** / **Re-capture IP Address**, which opens `VoterVerificationModal`;
- a short confirmation in the row after a successful capture.

The modal posts exactly `{user_id, notes}`.

## Legacy `voter_verification_mode`

It is non-authoritative and kept only for backward compatibility:
- the request accepts it optionally;
- its lock is unchanged;
- the Settings page no longer shows or sends it;
- PBDIGIT-247 does **not** depend on it.

Do not read it for new behaviour. `checksIp()` / `checksFingerprint()` still have no callers.

## Testing

| Suite | Covers |
|---|---|
| `tests/Feature/Election/VoterIpCaptureTest.php` | I1–I15: capture, isolation from identity / admission / SVA, authorisation, IPv4 / IPv6, client IP prohibited, election scope, suspended voter, **I15 fail-closed when disabled** |
| `tests/Feature/Election/VoterIpVerificationSettingTest.php` | S1–S5: enable / disable, changeable while VotingActive, PATCH without the legacy field, `captureIp` vs `ipBinding` props |
| `tests/Feature/Election/LegacyCaptureIpCharacterizationTest.php` | the identity endpoint refuses the legacy IP payload |
| `tests/Frontend/VoterIpCapture.spec.js` · `VoterVerificationModal.spec.js` · `ElectionSettingsIpVerification.spec.js` | column visibility (P1–P5), modal payload, Settings section |

## Pitfalls

- **Unapplied migrations break pages.** Saving Settings returns 500 (SQLSTATE 42703) until `2026_10_06_000002` is applied. Apply both migrations before using the feature.
- **Do not gate the column on `ipBinding`.** Capture is evidence; binding is enforcement (PBDIGIT-247).
- **Never let the client send the IP.** The server derives it; tests I11 and M2 guard this.
- **Visibility of IP addresses is undecided (VIS-D1 / VIS-D2).** Do not add IPs to new pages. The read-only voter list (PBDIGIT-250) shows states only.

**Traceability:** PBDIGIT-222 · PBDIGIT-248 (identity without IP) · PBDIGIT-244 (security-policy lock) · PBDIGIT-247 (IP Binding, open) · ADR-T11 (no voter ↔ ballot link: IP evidence concerns admission, never a vote).
