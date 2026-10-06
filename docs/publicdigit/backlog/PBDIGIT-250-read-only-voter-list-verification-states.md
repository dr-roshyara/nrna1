# PBDIGIT-250 — Read-only voter list shows verification states

**Type:** Feature — display read-model · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-06
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable), at the PO's request (2026-10-06)
**Existing-story search (freeze rule):** PBDIGIT-248 owns identity evidence and PBDIGIT-222 owns IP evidence, both on the **Voter Management** page (`elections.voters.index`). Neither covers the **read-only voter list** (`organisations.elections.voters`, `OrganisationController::voters`, `Organisations/Voters.vue`).

| | |
|---|---|
| **Status** | 🟢 **RESOLVED 2026-10-06**: RED, then GREEN (PO-approved). The read-model `app/ReadModels/VoterVerificationStates.php` is shared with Voter Management; guide `developer_guide/election/voter_admission/07_read_only_voter_list_verification_states.md` |
| **Page** | `/organisations/{org}/elections/{election}/voters`. Its audience (`ChecksElectionAccess`): org owner / admin / commission, election officers, **and every active voter of the election** |
| **Identity Verification column** | Read-only. Shown **only while the admission policy requires `OFFICER_IDENTITY_VERIFICATION`**. Evidence state: `verified` · `verification_required` · `reverification_required`, the same read-model as Voter Management (PBDIGIT-213 C4-B) |
| **IP Address Verification column** | Read-only. Shown **only while "Voter's IP Verification" is enabled** (`ip_verification_enabled`, PBDIGIT-222). State only: `verified` (a `voter_ip_verifications` row exists) · `not_verified` |
| **Privacy (PO 2026-10-06)** | **State only.** The page never receives an IP address (captured or `users.current_ip`), capture notes, or who verified. VIS-D1 (who may see IPs) stays open and is **not** decided here |
| **Non-goals** | No actions on this page. Never an authorisation source: entitlement stays with `VotingEntitlementQuery`. IP Binding (PBDIGIT-247) is not involved |
| **Order** | PBDIGIT-222 (Capture IP + setting) is committed first; this story builds on its `ip_verification_enabled` and `voter_ip_verifications` |

**RED:**
- `tests/Feature/Organisation/ElectionVotersVerificationColumnsTest.php` (V1–V7)
- `tests/Frontend/OrganisationVotersVerificationColumns.spec.js` (C1–C4)

**Traceability:** PBDIGIT-213 C4-B (identity evidence states) · PBDIGIT-222 (IP evidence, setting) · ADR-T11 anonymity (no voter ↔ ballot link: the states describe admission evidence, never a vote).
