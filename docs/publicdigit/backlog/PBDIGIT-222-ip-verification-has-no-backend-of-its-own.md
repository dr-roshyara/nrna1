# PBDIGIT-222 — "Verify IP Address" has no backend of its own: it records identity-admission evidence

**Type:** Defect (domain boundary) + security gap · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-04
**Raised by:** PO decision "UI split + IP backlog" during the PBDIGIT-213 C4 correction (2026-10-04)
**Recorded by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🟢 **RESOLVED 2026-10-06**: a separate Capture-IP capability (`voter_ip_verifications`) plus the "Voter's IP Verification" setting; see **Resolution** below. *(Was: OPEN, not authorised, ADR first.)* |
| **Domain rule** | IP / Device Verification ≠ Identity Admission Verification (ADR_20261004_1549 inv. 4). Only the Chief/Deputy identity attestation may create `OFFICER_IDENTITY_VERIFICATION` evidence. |
| **Problem** | The legacy IP verification (`VoterVerificationModal`) and the PBDIGIT-213 identity verification (`IdentityAttestationDialog`) post to the **same** endpoint, `elections.voters.verify` → `VoterVerificationController::store` → `RecordIdentityVerificationHandler` → `EloquentIdentityEvidenceStore::recordActive`. The result is an **active** `voter_verifications` row (one per election and voter, `unique(election_id, user_id)`), and an active row is exactly what `EloquentVotingEntitlementQuery::isAdmitted` counts. So an IP verification **is** an identity admission on the server, and an identity verification makes the IP look verified. |
| **Containment in PBDIGIT-213 C4 (2026-10-04)** | The legacy **"Verify IP Address" UI entry point was removed from Voter Management** (PO: *"IP verification backend/domain remains untouched. The legacy UI entry point is removed from the current voter-management workflow because it conflicts with the Identity Verification model."*). `VoterVerificationModal.vue` (retitled "Verify IP Address") and its spec stay in the codebase, unmounted. The IP backend fields stay untouched. **Restoring IP verification is this story** — see "How IP verification worked before it was removed". |
| **Residual risk** | Rows created by legacy "Verify" clicks **before** C4 stay in `voter_verifications` and count as identity evidence (C3 semantics); they cannot be told apart from attestations except via the `voter_verified` audit metadata. |
| **Interaction with PBDIGIT-213 Slice 3** | `store()` now requires `attested: accepted`; the legacy modal sends no `attested`, so it would be **refused** — it must get its own endpoint here before it is mounted again. |
| **IP data is not enforced anywhere** | `Election::checksIp()` / `checksFingerprint()` have no callers; the vote-time trust check uses `users.voting_ip`, not `voter_verifications.verified_ip`; the enforcement tests in `VoterVerificationTest` (`test_voter_on_wrong_ip_is_blocked`, `test_voter_on_wrong_device_is_blocked`, `test_voter_not_verified_is_blocked_when_mode_not_none`) are `markTestSkipped('Voting enforcement endpoint not yet implemented')`. The Settings page nevertheless says *"Voter must vote from the pre-verified IP address"* for `voter_verification_mode`. |
| **Existing-story search** | `grep` of `docs/publicdigit/backlog/` for `verified_ip`, `voter_verification_mode`, IP verification, device fingerprint: `PBDIGIT-45` (who owns the votes-per-IP rule — a different rule: participation density, not per-voter IP verification) and `PBDIGIT-91` (what the registered device fingerprint is — an input to decision 3 below), plus discovery notes. No story owns per-voter IP/device verification or its separation from admission evidence. |
| **Related** | `PBDIGIT-213` (C4 split the buttons) · `PBDIGIT-45` · `PBDIGIT-91` · `PBDIGIT-220` / `PBDIGIT-221` (same page). |

## How IP verification worked before it was removed (record for restoration)

Source of truth: commit `110387f63` (last before PBDIGIT-213 C3) for the backend, `622ee91e9` (last before C4) for the UI. Read them with `git show <commit>:<path>`.

| Aspect | Legacy behaviour |
|---|---|
| **Entry point** | `resources/js/Pages/Elections/Voters/Index.vue`, Actions column: button "Verify" / "Re-verify" (title "Verify voter identity" / "Re-verify voter"), class `act-btn act-btn--verify`, hidden once the voter has voted; label chosen by `getVerificationStatus(user_id)` = `props.verifications[user_id]`. |
| **Name badge** | "Verified" pill next to the voter name whenever `verifications[user_id]` existed (`verification-badge--active`). |
| **Modal** | `resources/js/Components/Election/VoterVerificationModal.vue` (title was "Verify Voter Identity"): voter card; "Previously Verified" box (date + IP); read-only **"Voter's Verified IP Address (Captured at last login)"** filled from `membership.user.current_ip`; optional **Device Fingerprint Hash** (≤ 64); **Notes** (≤ 1000, e.g. "Called on mobile, using home WiFi"); submit "Verify Voter" / "Update Verification", **disabled without an IP** (`!form.verified_ip`); on success `router.reload()`. |
| **Page data** | `ElectionVoterController@index`: `verifications` = active `voter_verifications` keyed by `user_id` (`id, user_id, verified_ip, verified_device_fingerprint_hash, verified_at, notes`); users loaded with `current_ip, updated_at`. |
| **Write** | `POST /organisations/{org}/elections/{election}/voters/verify` (`elections.voters.verify`) → `VoterVerificationController::store`: validates `user_id` (uuid, exists), `verified_ip` (nullable, ipv4), `verified_device_fingerprint_hash` (nullable, ≤ 64), `verified_device_components` (nullable array), `notes` (nullable, ≤ 1000); **upserts one row** per (election, voter) with `verified_by`, `verified_at = now()`, `status = active`, clears `revoked_by/at`. |
| **Revoke** | `DELETE …/voters/{verification}/revoke` (`elections.voters.verification.revoke`) → status `revoked`, `revoked_by`, `revoked_at`. No UI button (backend only). |
| **Authority** | `authorize('manageSettings')` — Chief or Deputy Chief of the election. |
| **Lifecycle window** | route middleware `election.state:verify_vote` = `canVote || !isLocked` (`OperationCapabilityMapper`); C3 replaced it with `verify_voter_identity` and removed `verify_vote`. |
| **Events / audit** | `IdentityAttestedEvent` (Trust context) on store, `VerificationRevokedEvent` on revoke; audit `voter_verified` {verified_ip, fingerprint} and `verification_revoked`. An IP-only action must **not** emit the identity event. |
| **Setting** | `elections.voter_verification_mode` (`none` / `ip_only` / `fingerprint_only` / `both`), edited on the election Settings page with the descriptions "Voter must vote from the pre-verified IP address…" and a "Verification Workflow" box ("click the Verify button · conduct a video or audio call · record evidence"). `Election::requiresVoterVerification()/checksIp()/checksFingerprint()` exist. |
| **Enforcement** | None — no caller of `checksIp()/checksFingerprint()`; the three enforcement tests in `tests/Feature/Election/VoterVerificationTest.php` are skipped. |
| **Tests (legacy behaviour)** | `VoterVerificationTest`: save, save with IP only, revoke, re-verify updates the one record, non-admin refused. |

## PO decisions already taken (2026-10-04, input for the ADR)

- IP verification is a **separately bounded legacy capability** — not a DDD admission capability, isolated from the Identity Admission model.
- **Own persistence:** a new table (working name `voter_ip_verifications`); the IP action must never create, update, revoke or otherwise modify `voter_verifications`, and must not influence `VotingEntitlement` or `VoterAdmissionPolicy`.
- **Switch:** the existing `voter_verification_mode` (IP column shown for IP Address Only / Both).
- **Display only** at first (an "IP Verification" column with *Verified* / *Not verified*; a "Verify IP Address / Re-verify IP Address" button); enforcement is a later, separate decision.
- **Schema not yet fixed:** confirm the lifecycle (re-verify, revoke, reason, IP required or nullable) before the migration.
- Target UI agreed in discussion: two columns (IP Verification, Identity Verification), each hidden when its switch is off; the name badge means *all required verifications complete*.

## Resolution (2026-10-06): as implemented, supersedes the slice notes below

- **Status:** 🟢 RESOLVED by the PBDIGIT-222 commit (Capture IP + "Voter's IP Verification" setting). Full 97-suite regression 582 / 333 / 19, identical to the baseline; no pass → fail.
- **Final IP-D3 = (c), plus a dedicated setting (PO 2026-10-06):**
  - `elections.ip_verification_enabled` (boolean, default false) is the switch for Capture IP. It is evidence configuration, **not** security policy: it is not in `Election::SECURITY_POLICY_FIELDS` and can change at any time.
  - `ipBinding.active` stays `false` and drives nothing until PBDIGIT-247.
  - `voter_verification_mode` is legacy and non-authoritative. It is optional in the settings request, its PBDIGIT-244 lock is unchanged, the Settings page no longer shows or sends it, and PBDIGIT-247 does not depend on it. Removing it is a separate future decision.
- **Endpoint:** `POST elections.voters.capture-ip {user_id, notes?}` → `VoterIpCaptureController::store`:
  - `manageSettings` (Chief / Deputy);
  - **fail-closed** when the setting is off (`ip_verification` error, nothing written);
  - an active voter membership of this election only;
  - the IP comes from `users.current_ip` (IPv4 or IPv6), and client-sent IP / device fields are `prohibited`;
  - one row per election + voter in `voter_ip_verifications`, where a re-capture replaces it;
  - audit `voter_ip_captured`.
- **Lifecycle window:** the neutral `import_voters` route group (not `verify_voter_identity`).
- **Never touched:** `voter_verifications`, admission, entitlement and the Security Verdict Authority.
- **UI:**
  - Settings section "Voter's IP Verification" (Disabled / Enabled);
  - the Voter Management IP Address column (when enabled) with the captured / not-captured state, Verify / Re-capture IP Address and a row confirmation.
- **Guide:** `developer_guide/election/voter_admission/06_capture_ip_and_ip_verification_setting.md`.
- **Follow-ups:**
  - VIS-D1 / VIS-D2 (who may see IPs; `current_ip` already in the Voter Management page data);
  - the outdated tutorial text on "Voter Verification Modes";
  - PBDIGIT-250 (read-only voter list states).

## Capture-IP slice (PO 2026-10-06): RED written; decisions approved

**PO decision 2026-10-06 (latest, supersedes the (c) entry below): IP-D3 = (a).**
- `voter_verification_mode` set to `ip_only` / `both` is **the** switch for the IP Address column: `Election::checksIp()`, which previously had no callers.
- That field becomes the IP-binding switch PBDIGIT-247 builds on; its old, unused meaning is retired.
- No other policy authority.
- Display / evidence only; no enforcement.

**Earlier PO decisions 2026-10-06 (kept for history):**
- **IP-D3 = (c), later superseded by (a):** a typed "(a)" was superseded by the later, detailed PO instruction.
  - Neither `voter_verification_mode` nor `network_binding_strategy` becomes the P-BINDING switch.
  - The IP Address column stays policy-controlled, but **its switch is deferred to PBDIGIT-247**, and no temporary second policy authority is created. The page receives `ipBinding.active = false` until then.
  - **Consequence:** the restored action is not visible to users until PBDIGIT-247.
- **The approved contract:**
  - `POST elections.voters.capture-ip {user_id, notes?}`; the client never sends an IP;
  - the server reads `users.current_ip`, a valid **IPv4 or IPv6** address (the whitelist's IPv4-only rule does not apply here);
  - an unknown or invalid IP is refused;
  - Chief / Deputy only, registered voter of the election;
  - one current row per election + voter, where a re-capture replaces it;
  - no device fingerprint, no revoke, no enforcement (PBDIGIT-247).
- **Implementation choices not decided by the PO, flagged:**
  - the lifecycle window reuses the identity verification window (`election.state:verify_voter_identity`);
  - a registered voter = an `ElectionMembership` (role voter) of this election that is not removed;
  - client-sent IP fields are refused (`prohibited`).

## Capture-IP slice: original notes

- **Historical finding:** the April 2026 modal (`910e8ccda`, "Verify Voter Identity") combined an informal identity check (video call) with formal capture of the voting IP / device. C3 later made its `voter_verifications` row identity-admission evidence. The PO split it:
  - identity → PBDIGIT-248;
  - IP → this slice: `voter_ip_verifications`, display / evidence only, no enforcement (enforcement is PBDIGIT-247).
- **RED** (uncommitted):
  - `tests/Feature/Election/VoterIpCaptureTest.php`: I1–I8. Capture writes only `voter_ip_verifications`; it never touches `voter_verifications`, admission or the identity event; authority, scope, re-capture, unknown IP; the identity endpoint still rejects IP fields.
  - `tests/Frontend/VoterIpCapture.spec.js`: P1–P4 (IP column and action follow `ipBinding.active`; the IP modal opens independently of identity) and M1, M2 (the modal is IP-only and posts to the IP endpoint).
- **Proposed minimum contract, to approve:**
  - `POST elections.voters.capture-ip {user_id, notes?}`, Chief / Deputy only;
  - the **server** captures `users.current_ip`, and the client sends no IP;
  - one current row per election + voter, where a re-capture replaces it;
  - an unknown IP is refused;
  - no device fingerprint;
  - no revoke in this slice.
- **Blocking decision IP-D3, the visibility switch:** the IP column follows P-BINDING (COL-D1), but **no P-BINDING setting exists yet**. The historical switch `voter_verification_mode` (`ip_only` / `both`) is a settings-page field, already in the I9 locked set, with no runtime reader. Q3-D1 says it must not become a second policy model. **Options:**
  - (a) reconcile `voter_verification_mode` as *the* P-BINDING switch, the single authority, retiring its separate meaning;
  - (b) add a binding value to the constitutional `network_binding_strategy`, which today also drives P-DENSITY;
  - (c) defer the column until PBDIGIT-247 defines the switch.

## Decisions needed (ADR first)

1. **Where IP/device facts live:** separate fields/rows that never activate admission evidence (e.g. a technical-verification record), or a status on the existing row that `isAdmitted` ignores. A schema change implies a migration.
2. **Existing rows:** how to classify active rows created by an IP verification while the policy was OFF (they cannot be told apart from attestations today, except by the `voter_verified` audit metadata).
3. **Enforcement:** whether `voter_verification_mode` (IP / device / both) is to be enforced at vote time at all, or the setting and its Settings text removed.
4. **Who may record IP/device data**, and in which lifecycle window.

## Acceptance (TDD — RED first; refined after the ADR)

1. Verify IP Address records IP/device data **without** creating or changing admission evidence; with the policy ON a voter verified only by IP stays *NotAdmitted*.
2. Verify Identity alone creates `OFFICER_IDENTITY_VERIFICATION` evidence.
3. The "Verify IP Address" entry point (removed from Voter Management in PBDIGIT-213 C4) is restored on its own endpoint, with an IP Verification column whose state comes only from the IP record — independent of the Identity Verification column.
4. If enforcement is decided: a voter on the wrong IP/device is refused per `voter_verification_mode` (un-skip and implement the three skipped tests); otherwise the setting and its text are removed.
