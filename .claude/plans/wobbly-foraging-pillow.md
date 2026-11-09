# Plan — "Voter's IP Verification" election setting (PBDIGIT-222, before commit)

## Context

The election Settings page has a **"Voter Verification Mode"** section (`resources/js/Pages/Elections/Settings/Index.vue` ≈381–430). It is backed by `elections.voter_verification_mode` (`none` / `ip_only` / `fingerprint_only` / `both`), is **required** by `UpdateElectionSettingsRequest`, and is one of PBDIGIT-244's locked `Election::SECURITY_POLICY_FIELDS`. Its options mix three concerns that are now separate:

- **Identity verification:** Admission, PBDIGIT-248.
- **IP verification:** evidence capture into `voter_ip_verifications`, PBDIGIT-222. Not enforcement.
- **IP binding:** a security policy, PBDIGIT-247. Not part of this change.

The Capture-IP UI is currently driven by `ipBinding.active` (always `false`), which conflates capture with binding. The goal: give **Voter's IP Verification** its own Enabled / Disabled election setting that controls Capture-IP availability. `ipBinding.active` stays `false` and independent.

**PO decisions (2026-10-06):**
- a **new dedicated boolean field**;
- the legacy `voter_verification_mode` **keeps its column but is hidden** (optional in the request; the PBDIGIT-244 lock is unchanged):
  - **correction (PO):** it is legacy / deprecated configuration, no longer authoritative for Capture-IP, kept only for backward compatibility;
  - its removal or migration is a separate future decision;
  - **PBDIGIT-247 does not depend on it**;
- the new setting is **changeable at any time**: it is not a security policy and is not locked;
- **change 8 is approved:** a server-side fail-closed gate, tested by I15 through a direct POST;
- settings use the existing `manageSettings` authorization, with no new permission mechanism.

## Changes

1. **Migration (new)** `database/migrations/2026_10_06_000002_add_ip_verification_enabled_to_elections.php`: `boolean ip_verification_enabled default false`; reversible. Applied to the dev DB **only by the PO**.
2. **`app/Models/Election.php`:**
   - add `ip_verification_enabled` to `$fillable` and to the casts (`boolean`);
   - add `isIpVerificationEnabled(): bool`;
   - **not** added to `SECURITY_POLICY_FIELDS`.
3. **`app/Http/Requests/Election/UpdateElectionSettingsRequest.php`:**
   - `ip_verification_enabled` → `['boolean']`;
   - `voter_verification_mode` → `['sometimes', 'in:none,ip_only,fingerprint_only,both']`, with the `required` message removed.

   Existing posts that send it keep working; the page no longer sends it.
4. **`resources/js/Pages/Elections/Settings/Index.vue`:**
   - replace the "Voter Verification Mode" section with **"Voter's IP Verification"**:
     - description: "Optional: Allow authorised election officers to verify and record a voter's IP address before voting.";
     - Disabled / Enabled choice;
     - the 6-step workflow (Voters tab → find voter → Verify IP Address → server captures IP → officer confirms → stored for this election);
     - the clarification: "IP verification evidence is stored separately from identity verification. Enabling IP verification does not by itself enforce IP binding for voting.";
   - form field `ip_verification_enabled`;
   - drop `voter_verification_mode` from the form and the old mode options (no device fingerprint option).
5. **Settings `edit()` props:** make sure `ip_verification_enabled` reaches the page (`ElectionSettingsController::edit`). The lock / refusal logic needs no change, because the field is not a security-policy field.
6. **`app/Http/Controllers/ElectionVoterController.php`:**
   - add `'captureIp' => ['active' => $election->isIpVerificationEnabled()]`;
   - keep `'ipBinding' => ['active' => false]`;
   - remove `voter_verification_mode` from the `election` prop. Index.vue no longer needs it; confirm it has no reader there first.
7. **`resources/js/Pages/Elections/Voters/Index.vue`:**
   - the IP Address column, the cell and the Verify IP Address button use `captureIp.active` instead of `ipBinding.active`;
   - new prop `captureIp` (default `{active: false}`);
   - `columnCount` follows `captureIp`.
8. **Capture-IP backend:** contract unchanged. **One minimal wiring change, flagged for approval:** `VoterIpCaptureController::store` refuses when `isIpVerificationEnabled()` is false (a server-side gate, so the setting is not only a Vue-side gate). Without it, the endpoint stays open whatever the setting.

## Tests

- **New** `tests/Feature/Election/VoterIpVerificationSettingTest.php`:
  - Chief enables, then disables (stored);
  - changeable while VotingActive (not locked);
  - a settings PATCH without `voter_verification_mode` succeeds;
  - Voter Management props `captureIp.active` follow the setting while `ipBinding.active` stays false.
- **`VoterIpCaptureTest`:** I1–I14 fixtures enable the setting; new **I15**: capture is refused when IP verification is disabled (only if change 8 is approved).
- **`tests/Frontend/VoterIpCapture.spec.js`:** P1 / P2 drive `captureIp.active`; new P5: `ipBinding.active` alone shows no IP column.
- **Settings page spec** (new or existing): renders "Voter's IP Verification" Enabled / Disabled; no "Voter Verification Mode" or device options.
- **Unchanged and must stay green:** `ElectionSettingsControllerTest`, `ElectionSettingsTest`, `SecurityPolicyLifecycleLockTest` (PBDIGIT-244), `VoterAdmissionPolicyEndpointTest`, identity suites.

## RED step (approved, about to run; tests only, no production change)

1. **New** `tests/Feature/Election/VoterIpVerificationSettingTest.php`, with fixtures modelled on `ElectionSettingsControllerTest` (Chief officer, settings PATCH payload):
   - S1: enable stores `ip_verification_enabled = true`;
   - S2: disable stores `false`;
   - S3: changeable while VotingActive (not locked);
   - S4: a PATCH without `voter_verification_mode` succeeds;
   - S5: `elections.voters.index` props give `captureIp.active` following the setting, and `ipBinding.active === false`.
   - At HEAD these fail: column / rule / prop missing.
2. **`VoterIpCaptureTest`:**
   - the `setUp` fixture enables the setting with the query builder. Until the migration exists, I1–I14 error in setup because the column is missing; this is expected and reported as such;
   - add **I15**: a direct POST while disabled is refused (`ip_verification` error); no `voter_ip_verifications` row; no `voter_verifications` row; admission / entitlement standing unchanged; throwing Security Verdict Authority doubles never called; `codes` / `votes` counts and `has_voted` unchanged.
3. **`tests/Frontend/VoterIpCapture.spec.js`:** the page mount takes `captureIp` (P1 / P2 drive `captureIp.active`); new P5: `ipBinding.active = true` with `captureIp.active = false` shows no IP column. At HEAD, P1 / P5 fail.
4. **New** `tests/Frontend/ElectionSettingsIpVerification.spec.js`, mounting `Pages/Elections/Settings/Index.vue` with `ElectionLayout`, `VoterAdmissionCard` and `ToggleSwitch` stubbed:
   - shows "Voter's IP Verification" with Disabled / Enabled and the clarification text;
   - no "Voter Verification Mode", "Device Fingerprint" or "IP Address Only";
   - on submit (`form` submit → `router.patch`), the payload has `ip_verification_enabled` and no `voter_verification_mode`.
5. Run the four, then report the RED reasons, then STOP.

## Verification

1. Run the new tests RED, then implement, then GREEN.
2. Run the targeted suites: Capture-IP, settings, identity, admission, security, Phase 4.
3. Run the frontend suite.
4. Full 97-suite regression vs **582 / 333 / 19**, with no pass → fail.
5. The PO applies both migrations to the dev DB (`php artisan migrate --pretend`, then `migrate`) and checks the Settings page and the Voters page manually.
6. STOP; Capture-IP plus this setting are committed as PBDIGIT-222 only after PO approval.

## Out of scope

- PBDIGIT-247 (IP binding, `ipBinding.active` stays false);
- `ip_restriction_*`, `network_binding_strategy`, `max_votes_per_ip`;
- P-DEVICE, email (PBDIGIT-249), identity (PBDIGIT-248);
- dropping the `voter_verification_mode` column.
