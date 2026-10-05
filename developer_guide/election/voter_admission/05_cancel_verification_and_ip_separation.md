# 05 — Cancel Verification, and identity without IP / device data (PBDIGIT-248)

**Story:** PBDIGIT-248 · **RED:** `7ae825d1e` · **GREEN:** this step's commit · **Proposal:** `docs/publicdigit/reviews/2026-10-05-identity-verification-lifecycle-and-email-proposal.md` (ID-D1 … D3, COL-D1)

## Purpose

Two corrections to Voter Management:
- **Lifecycle:** a verified voter can now **cancel** the verification. "Re-verify" is no longer offered for a verified voter.
- **Boundary:** identity verification no longer carries **IP or device data**. Network and device belong to the Security Policy, not to Admission (ADR_20261004_1549 inv. 4).

## Lifecycle

```
NOT_VERIFIED —Verify Identity→ VERIFIED —Cancel Verification→ NOT_VERIFIED —Verify Identity→ …
```

- **Verify Identity:** `IdentityAttestationDialog` → `POST elections.voters.verify` with `{user_id, attested, notes}` → `RecordIdentityVerificationHandler` (unchanged).
- **Cancel Verification:** the row action for a voter whose state is `verified` opens a **confirmation** dialog. Confirming calls the **existing** `DELETE elections.voters.verification.revoke` → `RevokeIdentityVerificationHandler` with `revoked_by_officer`. **No new state model.**
- **Effect:**
  - the current evidence row gets `status=revoked`, `revoked_by`, `revoked_at`;
  - `verified_by` / `verified_at` stay, and the audit entry and `VerificationRevokedEvent` are written as before;
  - when the policy requires identity, the voter is no longer admitted.
- **History:** a later Verify overwrites the current row's `verified_*` fields; the audit log keeps every act. **Append-only evidence is deferred (ID-D3).**

## No IP / device in identity

| Layer | Behaviour |
|---|---|
| Dialog (`IdentityAttestationDialog.vue`) | no technical-details section, no IP shown, no fingerprint input. The payload is exactly `{user_id, attested, notes}` |
| Endpoint (`VoterVerificationController::store`) | `verified_ip`, `verified_device_fingerprint_hash`, `verified_device_components` are **`prohibited`**: a request carrying them is refused and nothing is recorded. New identity evidence stores none of them, and the audit metadata no longer has an IP |
| Security | identity verification never calls the Security Verdict Authority (`IdentityVerificationLifecycleTest` B3) |

IP capture is a **separate capability** (PBDIGIT-222, Capture IP Address → `voter_ip_verifications`). It never goes through this endpoint.

## Columns and C4

- The Identity Verification column is shown only while the admission policy requires identity.
- With the policy OFF, the column is hidden but **Verify / Cancel stay available**: evidence is recordable whatever the policy (PBDIGIT-213 C4, not reopened).

The **"Suspend"** label (formerly "Suspend Identity") is the two-person proposal to suspend the voter from voting. It is not an identity action.

## Testing

- `tests/Frontend/VoterIdentityLifecycle.spec.js`:
  - L1–L6: Verify, Cancel with confirmation, revoke route, policy OFF / ON;
  - S1–S2: no IP / device in the dialog.

  The Email / IP column tests are skipped until PBDIGIT-249 / PBDIGIT-247.
- `tests/Feature/Election/IdentityVerificationLifecycleTest.php`:
  - B1: cancel ends admission and keeps history and the event;
  - B2: legacy fields refused;
  - B3: no security-authority call;
  - B4: evidence without network data.
- **Adapted (PO-approved):**
  - 14 backend fixtures no longer send IP / device fields: `IdentityVerificationAuthorityTest`, `SimpleStoreTest`, `VoterVerificationEmitsEventsTest`;
  - 5 frontend tests that pinned "Re-verify Identity" or the C4 technical section: `IdentityAttestationDialog.spec.js`, `VoterIdentityEntryPoints.spec.js`.
- **97-suite regression:** 582 / 333 / 19, the same as the PBDIGIT-246 baseline, with no pass → fail.

## Pitfalls

- Never send IP / device data to `elections.voters.verify`; the endpoint refuses it by design.
- Cancel needs the verification id from the page's `verifications` map (active rows only).
- Do not re-add a "Re-verify" action for a verified voter. Cancel first, then Verify again.

**Traceability:** PBDIGIT-248 · PBDIGIT-213 C3 / C4 · PBDIGIT-222 (IP capture, separate) · ADR_20261004_1549 inv. 4.
