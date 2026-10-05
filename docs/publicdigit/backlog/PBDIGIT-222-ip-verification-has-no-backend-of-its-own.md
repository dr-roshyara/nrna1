# PBDIGIT-222 — "Verify IP Address" has no backend of its own: it records identity-admission evidence

**Type:** Defect (domain boundary) + security gap · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-04
**Raised by:** PO decision "UI split + IP backlog" during the PBDIGIT-213 C4 correction (2026-10-04)
**Recorded by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

| | |
|---|---|
| **Status** | 🟡 **OPEN — not authorised.** Needs an architecture decision (ADR) before a plan: it touches evidence semantics and probably the `voter_verifications` schema, both frozen for PBDIGIT-213. |
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
