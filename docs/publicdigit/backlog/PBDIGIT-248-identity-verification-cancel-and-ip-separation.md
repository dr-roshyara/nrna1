# PBDIGIT-248 — Identity verification: Cancel Verification, and no IP / device data in identity evidence

**Type:** Feature + domain-boundary correction · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Proposal:** `docs/publicdigit/reviews/2026-10-05-identity-verification-lifecycle-and-email-proposal.md` (§A–D, G)
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Existing-story search (freeze rule):**
- `PBDIGIT-213` C4 (`9c482db85`): evidence independent of the policy. **Not reopened.** ID-D2 deliberately reverses only C4's choice to keep IP / device as audit metadata in the identity dialog.
- `PBDIGIT-222`: IP ≠ identity; the legacy IP backend. This story removes network data from the *identity* path. 222 keeps the legacy IP verification.
- `PBDIGIT-247`: P-BINDING, the IP column's policy.

| | |
|---|---|
| **Status** | 🟢 **RESOLVED (2026-10-06)** — RED `7ae825d1e` · GREEN: Cancel Verification (confirmation → existing revoke), "Suspend" relabel, identity dialog without IP / device, identity endpoint `prohibited` for `verified_ip` / device fields. 19 superseded tests adapted (PO-approved). 97-suite regression 582/333/19, the same as the PBDIGIT-246 baseline, no pass → fail (run partly overlapped by Capture-IP additions; clean certification follows with PBDIGIT-222). Guide `developer_guide/election/voter_admission/05_cancel_verification_and_ip_separation.md` |
| **ID-D1 Cancel Verification** | A verified voter shows **Cancel Verification**, with no Re-verify action. Cancelling needs a **confirmation step**; no mandatory reason. It reuses `RevokeIdentityVerificationHandler` / `revoked_by_officer` and the existing audit and domain event. No new state model |
| **ID-D2 Identity ≠ IP / device** | The identity dialog shows and submits no IP, device fingerprint or device components. The identity endpoint **rejects** `verified_ip`, `verified_device_fingerprint_hash` and `verified_device_components` (validation error) and no longer persists them in new identity evidence. No IP binding, no Security Verdict Authority call, no security policy in Admission |
| **ID-D3 History** | The current row plus audit / domain-event history is kept. Append-only evidence is **deferred** (future story if needed) |
| **Columns (COL-D1)** | The Identity Verification column is shown only while the admission policy requires identity. Policy OFF → column hidden, but **Verify / Cancel stay available** (C4). The Email and IP columns belong to PBDIGIT-249 and PBDIGIT-247 |
| **RED** | `tests/Frontend/VoterIdentityLifecycle.spec.js`, `tests/Feature/Election/IdentityVerificationLifecycleTest.php` |

**Traceability:** proposal §A–D, G · PBDIGIT-213 C4 · PBDIGIT-222 · ADR_20261004_1549 inv. 4 (IP / device verification ≠ identity admission verification).
