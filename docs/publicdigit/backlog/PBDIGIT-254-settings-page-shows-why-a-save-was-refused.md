# PBDIGIT-254 — Election Settings shows why a save was refused

**Type:** Defect (silent failure) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-06
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable), from the PO's report: "I select ip binding enabled and then save. but the selection is not saved" → "it should at least show the error why setting can not be changed"
**Existing-story search (freeze rule):** none. The silent Settings page was noted as an open item on 2026-10-06 (session log) without a story.

| | |
|---|---|
| **Status** | 🟢 **RESOLVED 2026-10-06** (GREEN, PO-approved; commit pending approval) |

**GREEN:**
- `ElectionSettingsController`:
  - `edit` sends `securityPolicyLocked` (`ElectionLifecycle::isLocked`) and `settingsFrozen` (state = VotingActive);
  - `update` refuses every change while frozen (`settings` error, nothing written), before any other check.
- `Settings/Index.vue`:
  - the frozen / locked notice;
  - a `settings-save-errors` alert listing the server's messages, cleared on success;
  - IP restriction, max per IP, whitelist and IP Binding are disabled when locked or frozen;
  - IP Verification is disabled when frozen;
  - the whole form `<fieldset>` is natively disabled when frozen;
  - Save is disabled when frozen.
- `ToggleSwitch.vue`: an optional `disabled` prop (backward-compatible).
- **Existing tests moved VotingActive → ReadyForVoting, intent and assertions kept:**
  - `SecurityPolicyLifecycleLockTest` "saves other settings" and "refused security change writes nothing";
  - `VoterIpVerificationSettingTest` S3, renamed "changeable after the security lock";
  - `IpBindingSettingTest` B-S4, renamed "locked from ready for voting".
  - `ElectionSettingsControllerTest` needed no change.
- **Results:**
  - V1–V5, K-P1 / K-P2, E1–E3, K1–K4 pass;
  - the settings, lock, admission and binding suites pass;
  - frontend 322 passed / 3 skipped;
  - design-check 91;
  - the full regression is reported separately.
| **Finding [S]** | `Pages/Elections/Settings/Index.vue` `submit()` → `router.patch(…, { onError: () => { isValidating = false } })`. The server's validation errors are **discarded**; only the client-side whitelist error is ever shown. A refused save (for example the PBDIGIT-244 lock: *"The security policy cannot be changed once the election is ready for voting."*, or the PBDIGIT-247 consistency rule) looks like "the selection is not saved" |
| **Case observed [D]** | onf-2 is **VotingActive**. Saving IP Binding = Enabled is refused by the lock (decision B2, working as designed). Stored `ip_binding_enabled = false`; nothing is shown |
| **Scope (PO: "at least show the error")** | The page shows the server's refusal messages after a failed save, and clears them after a successful one. **Out of scope:** greying out locked fields (optional follow-up) and allowing binding to change during voting (would reverse B2) |

**Scope extended (PO 2026-10-06, "lock locked fields + show errors"):** from ReadyForVoting the security-policy fields the server refuses (IP Binding, IP restriction, max per IP, whitelist) are shown **disabled** with *"Locked while the election is ready for voting or in progress"*. Non-security settings (Voter's IP Verification, no-vote option, selection rules) stay editable, as the server allows. Not chosen: a whole read-only page (it would freeze settings the server allows and need a server-side freeze).

**RED, extended:**
- `tests/Feature/Election/ElectionSettingsLockedPropTest.php` K-P1 / K-P2: the `securityPolicyLocked` prop from `ElectionLifecycle::isLocked()`. Both fail: the prop is missing.
- `ElectionSettingsSaveErrors.spec.js`:
  - K1 the locked fields are disabled and the note is shown: fails;
  - K2 IP Verification stays changeable: a guard;
  - K3 unlocked = editable: a guard.

**Scope extended again (PO 2026-10-06, "Both" + "Ip address can be verified but policy must remain same"):**
- During the **voting period (VotingActive)** the election settings are **frozen**. The server refuses every settings change with *"Election settings cannot be changed while voting is in progress."* (error key `settings`), Save is disabled and every setting is shown disabled.
- **Capturing / verifying a voter's IP on Voter Management still works** (an action, not a policy change).
- ReadyForVoting keeps today's rule: the security policy is locked, other settings stay editable.
- **Supersedes** "Voter's IP Verification changeable at any time" (PBDIGIT-222) for the voting period.

**RED (voting freeze):**
- `tests/Feature/Election/ElectionSettingsFrozenDuringVotingTest.php`:
  - V1 a non-security change is refused while voting;
  - V2 IP verification cannot be changed while voting;
  - V4 the `settingsFrozen` prop;
  - all three fail.
  - V3 (ReadyForVoting still editable) and V5 (IP capture still works while voting) are guards and pass.
- `ElectionSettingsSaveErrors.spec.js` K4 (Save and all fields disabled, reason shown) fails.

**Existing tests that encode the superseded rule (to adjust at GREEN, keeping their intent):**
- `VoterIpVerificationSettingTest::test_s3_the_setting_is_changeable_while_voting_is_active`: move it to **ReadyForVoting** (still changeable after the security lock); V2 covers voting.
- `SecurityPolicyLifecycleLockTest::test_a_locked_election_saves_other_settings_when_the_security_policy_is_resubmitted_unchanged`: VotingActive → **ReadyForVoting** (still locked, other settings still savable).
- `IpBindingSettingTest::test_b_s4_binding_is_locked_while_voting_is_active`: asserts the `ip_binding_enabled` error key while voting, which now becomes the `settings` freeze. Move it to **ReadyForVoting** so it keeps testing the lock.
- `ElectionSettingsControllerTest` / `ElectionSettingsTest`: the "active election with votes → confirm" flow. Checked at GREEN; adjusted only if the fixture is truly VotingActive.

**RED (original):** `tests/Frontend/ElectionSettingsSaveErrors.spec.js`
- E1: a refused save shows the server's message;
- E2: a refusal of several fields lists each message;
- E3: a later successful save clears the messages.

**Traceability:** PBDIGIT-244 (security-policy lock) · PBDIGIT-247 (B2, consistency rule) · PBDIGIT-222 (settings page).
