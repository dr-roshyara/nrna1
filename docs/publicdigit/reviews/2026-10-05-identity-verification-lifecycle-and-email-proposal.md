# Identity verification: lifecycle, separation from security, and email verification (discovery and proposal)

**Date:** 2026-10-05 · **Status:** **DECIDED (PO 2026-10-05).** Identity RED accepted; **Identity GREEN authorised (PBDIGIT-248)**; email design decided (PBDIGIT-249, RED to finalise); P-BINDING a separate story (PBDIGIT-247, RED / design only).

## Decisions (PO 2026-10-05)

| ID | Decision |
|---|---|
| **ID-D1** | A verified voter shows **Cancel Verification**, with no Re-verify action. A **confirmation step** is required; no mandatory reason. Reuse `RevokeIdentityVerificationHandler`, `revoked_by_officer`, and the existing audit / domain event. No new state model |
| **ID-D2** | The identity dialog has no IP, device fingerprint or device components and submits none of them. The backend **rejects** the legacy fields (validation error) and no longer persists them in new identity evidence. No IP binding, no Security Verdict Authority call |
| **ID-D3** | Keep the current row plus audit / domain-event history. Append-only evidence is **deferred** (future story if needed) |
| **COL-D1** | Each column only while its policy is active (Identity / Email in the Admission Policy, IP while P-BINDING is active). Policy OFF → column hidden, **evidence capability stays** (C4) |
| **IP-D1** | Binding is desired, but it is a separate Security Policy story, **PBDIGIT-247**: RED / design only, not authorised. **Q3-D6 superseded** for P-BINDING |
| **E-D1 … E-D4** | Election-scoped; dedicated storage; `EMAIL_VERIFICATION` requirement, optional unless the policy requires it; voter-initiated challenge; hashed, single-use, bound to election + voter + address, 60 min, superseded by a newer one, fail closed, no raw token in logs. Email ≠ identity. ALL-of unchanged (**PBDIGIT-249**) |
| Stories | PBDIGIT-248 (gaps 1 + 2) · PBDIGIT-249 (email) · PBDIGIT-247 (P-BINDING) |
**Placement:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)
**Repository state:** HEAD `902060e92` (+ uncommitted PBDIGIT-246 GREEN, unrelated — Security Policy)
**Author:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable). Evidence and recommendation only.
**Evidence labels:** [S] source · [D] committed test · [I] inference.
**Not reopened:** PBDIGIT-213 C4 (`9c482db85`). Evidence can be recorded whether or not the policy requires it; the policy-oriented column hides when nothing is required.

---

## A · Current identity-verification lifecycle

| State (`identityVerification[user]`, `ElectionVoterController::identityVerificationStates`) | UI action today | Request | Result |
|---|---|---|---|
| `verification_required`: no evidence | **Verify Identity** → `IdentityAttestationDialog` | `POST elections.voters.verify` → `VoterVerificationController::store` → `RecordIdentityVerificationHandler` → `EloquentIdentityEvidenceStore::recordActive` | row `status=active`, `verified_by`, `verified_at`, `method` |
| `verified`: active evidence | **Re-verify Identity** → the same dialog (`reverify=true`) | the same `POST` | `updateOrCreate` **overwrites** the row's `verified_*` fields (G1: one row per election + voter) |
| `reverification_required`: revoked evidence | Verify Identity (dialog in re-verify mode) | the same `POST` | row active again; `revoked_*` cleared |

**Cancellation already exists in the backend, but not in the UI** [S]:
- `DELETE elections.voters.verification.revoke` → `VoterVerificationController::revoke` → `RevokeIdentityVerificationHandler` (Chief / Deputy authority) → `revokeActive()`.
- `revokeActive()` sets `status=revoked`, `revoked_by`, `revoked_at` and `revocation_reason=revoked_by_officer`, and keeps `verified_by` / `verified_at`.
- It also raises a `VerificationRevokedEvent` and writes the audit entry `verification_revoked`.
- Tests: `VoterVerificationTest::test_admin_can_revoke_voter_verification` [D], `IdentityVerificationAdmissionTest::test_revoked_evidence_does_not_admit` [D].
- **No page calls it** (`resources/js`: only the Ziggy route list).

**Answers to the seven discovery questions:**
1. **What represents the evidence:** `voter_verifications` (`VoterVerification`), keyed by election + voter.
2. **Mutable or append-only:** mutable, **one row per election + voter** (`unique(election_id, user_id)`). A re-verification overwrites the row; history survives only in the audit log (known gap **G1**).
3. **Cancellation:** `status=revoked` with `revoked_by`, `revoked_at` and a reason. The verification facts stay.
4. **AdmissionDecision:** yes, immediately. `EloquentVotingEntitlementQuery::isAdmitted()` counts only `status=active` rows.
5. **VotingEntitlement:** yes, immediately. Model writes invalidate the cached standing.
6. **Audit:** `verification_revoked` / `voter_verified` audit entries and the domain event.
7. **What produces "Re-verify Identity":** `Index.vue` ≈231–236, `state === 'verified' ? 'Re-verify Identity' : 'Verify Identity'`.

## B · Proposed lifecycle (smallest DDD-consistent change)

```
NOT_VERIFIED ──Verify Identity (officer attestation)──► VERIFIED ──Cancel Verification (officer)──► NOT_VERIFIED
     ▲                                                                                                │
     └────────────────────────────── Verify Identity again ─────────────────────────────────────────┘
```

- **"Cancel Verification" is the existing domain action** *revoke identity evidence* (`RevokeIdentityVerificationHandler`, reason `revoked_by_officer`). It is **not** a delete and **not** a new state model.
- For a verified voter, the UI action becomes **Cancel Verification**, calling the existing endpoint, with an optional confirmation step. A **Re-verify** action is no longer offered for a verified voter. Verify Identity is offered again once cancelled.
- **History:**
  - The row keeps `verified_by` / `verified_at` and records `revoked_by` / `revoked_at` / reason. The audit log keeps every act.
  - **Limitation:** a later Verify overwrites the row's `verified_*` fields. Only the audit log then holds the earlier verification. **G1, immutable evidence history, stays open** (decision **ID-D3**: accept audit-log history for now, or schedule append-only evidence).
- **Production change needed:** `Index.vue` (label and action) only. **No backend change.**

## C · Where the IP / network coupling is

```
Verify Identity (Index.vue row action or Identity column)
   → IdentityAttestationDialog.vue
       └─ "Technical details — audit only, not identity proof" (collapsible, lines ≈46–60):
            shows the voter's last-login IP; device-fingerprint input
   → POST elections.voters.verify  { user_id, attested, notes, verified_ip, verified_device_fingerprint_hash }   (dialog ≈118–128)
   → VoterVerificationController::store validates verified_ip / fingerprint / components (≈57–59)
   → RecordIdentityVerificationHandler → EloquentIdentityEvidenceStore::recordActive
        writes verified_ip, verified_device_fingerprint_hash, verified_device_components INTO the identity-evidence row
```

- **The legacy IP modal is not mounted on the page** [S]. `VoterVerificationModal.vue`, "Verify IP Address", was unmounted in C4 (PBDIGIT-222).
- **What a user sees as an "IP modal" when clicking Verify Identity** is the identity dialog's technical-details section. If a deployed environment still shows the full legacy modal, that environment predates `9c482db85`; please confirm.
- **Security does not read identity evidence** [S]. `TrustPolicyEvaluator::buildAttestationRecord()` hard-codes `attested = false`. The coupling runs one way only: network and device data flow *into* Admission evidence.
- **The C4 decision of 2026-10-04 placed this section there on purpose:** IP / device as audit-only metadata, pinned by `IdentityAttestationDialog.spec.js` tests ≈68–94 [D]. Removing it **reverses that PO decision**, which is decision **ID-D2**.

## D · Proposed separation

```
ADMISSION                                   SECURITY POLICY
Identity Verification (officer attestation)  Network / IP security (P-DENSITY, P-WHITELIST, P-BINDING*) · Device (P-DEVICE*)
   → identity evidence (voter_verifications)    → Security Verdict Authority → SecurityAssessment / SecurityVerdict
   → AdmissionDecision → VotingEntitlement      → forEntry / forCast
                                               (* candidate policies, inert — Q3-D6)
```

- **Identity verification sends, shows and stores no IP / device data.**
  - Remove the technical-details section and the IP / fingerprint fields from the dialog.
  - The `store` endpoint stops writing them into identity evidence (ignore them or reject them: decision **ID-D2b**).
- **The `voter_verifications.verified_ip` / device columns stay for legacy rows.** Their future belongs to **PBDIGIT-222** (per-voter IP / device verification, its own endpoint and evidence) and Q3-D6. They are not dropped here.
- **No IP logic moves into Admission. No second security authority. No Q3 / 2B work.**

## E · Email verification: proposed model

**Facts that shape the decision [S]:**
- `User` implements `MustVerifyEmail`, and most routes use the `verified` middleware.
- But `users.email_verified_at` is **stamped without any challenge** by:
  - `VoterImportService` (≈276; the Election-Only voter import);
  - `OrganisationUserImportService` (≈325);
  - `ImportUsers`;
  - social / Google login;
  - the `VerifyUserEmail` console command.
- So the account flag is **not proof of control** for imported voters, and it cannot serve as admission evidence.

**Proposed model, reusing the Admission architecture with no generic framework:**

```
AdmissionRequirement (enum)
   ├── OFFICER_IDENTITY_VERIFICATION  ← evidence: officer attestation (voter_verifications)
   └── EMAIL_VERIFICATION (new case)  ← evidence: confirmed email challenge (new, election-scoped)
```

**Lifecycle (only what the architecture needs; no event sourcing):**

```
challenge issued (voter- or officer-initiated) → mail sent → voter confirms with the token
   → EmailVerificationEvidence {election, voter, email address snapshot, confirmed_at, method}
   → revoked when: officer cancels · the user's email address changes · (optional) policy re-run
```

| Question | Proposal |
|---|---|
| A new AdmissionRequirement, or optional evidence? | **A new requirement case `EMAIL_VERIFICATION`**, but required only when the election's policy lists it. Evidence can be recorded whatever the policy (C4 semantics) |
| Contribution to AdmissionDecision | `isAdmitted()` adds `EMAIL_VERIFICATION` to `AdmissionEvidence` when active email evidence exists; ALL-of semantics are unchanged |
| Can Identity and Email coexist? | yes: `requires: [OFFICER_IDENTITY_VERIFICATION, EMAIL_VERIFICATION]` → both needed |
| Does ALL-of support it naturally? | yes (`VoterAdmissionPolicy::isSatisfiedBy`). The policy UI is a single toggle today and needs a second toggle |
| Authority creating the evidence | **the voter**, by confirming a challenge. No officer attests it. Who may *issue* a challenge (voter self-service, officer, both): **decision E-D3** |
| Token lifecycle | random token, stored **hashed**, bound to election + voter + **email address**; single-use; expiry (e.g. 60 min, **E-D4**); invalidated by a newer challenge |
| Replay / expiry | a used, expired or superseded token → refused; a token for another election or address → refused |
| Revocation / re-verification | an officer can cancel (like identity); an **email address change** ends the evidence automatically; re-verify = a new challenge |
| Audit | challenge issued (by whom, to which address), confirmed (when, address hash), revoked (by whom, why). No token in logs |
| **Aggregate ownership** | **Election Admission, election-scoped** (election + voter), like identity evidence. **Evidence from one election never admits in another** unless the PO decides a cross-election rule (**E-D1**) |
| Email verified ⇒ identity verified? | **No.** Separate requirement, separate evidence, separate wording ("Email verified" ≠ "Identity verified") |

## F · Admission semantics

```
policy.requires = [OFFICER_IDENTITY_VERIFICATION]                    → identity evidence alone admits (today)
policy.requires = [EMAIL_VERIFICATION]                               → email evidence alone admits
policy.requires = [OFFICER_IDENTITY_VERIFICATION, EMAIL_VERIFICATION] → both needed (ALL-of)
policy.requires = []                                                 → registration suffices (unchanged)
```

Email verification is **never** required merely because it exists. Only the election's policy makes it a requirement, locked from ReadyForVoting like the rest of the admission policy.

## G · Voter Management columns (PO request 2026-10-05)

**Request:** *"make separate column for verify identification, email verification and capture ip address of the voter."*

| Column | Context | Shows | Action |
|---|---|---|---|
| **Identity Verification** | Admission | identity evidence state (verified / not verified / cancelled) | Verify Identity · Cancel Verification (gap 1) |
| **Email Verification** | Admission | email evidence state (verified / pending challenge / not verified) | send / re-send challenge · cancel (gap 3, after E-D1 … E-D4) |
| **IP Address** | **Security Policy** (not Admission) | the voter's captured IP | *see IP-D1* |

The three columns are separate on screen and in the model. The IP column never feeds identity or email evidence (gap 2), and the identity dialog stays free of IP data.

**PO decisions 2026-10-05:**
- **IP-D1 = (b) bind.** The voter's captured IP becomes a registered IP that the Security Verdict Authority enforces (P-BINDING).
  - ⚠️ **This changes Q3-D6**, which classified P-BINDING as "candidate, inert, implementation deferred".
  - P-BINDING needs its storage decision, which today is the missing `users.voting_ip` (PBDIGIT-234, PBDIGIT-222). It is a **Security Policy story**, evaluated only by the authority at `forEntry` / `forCast` (Q3-D2). It is **not part of the identity / email (Admission) work**.
  - The IP column is the Security Policy's UI on this page.
- **COL-D1 = each column only while its policy is active:**
  - **Identity Verification:** while the admission policy requires `OFFICER_IDENTITY_VERIFICATION` (today's C4-B rule);
  - **Email Verification:** while the policy requires `EMAIL_VERIFICATION`;
  - **IP Address:** while the election's P-BINDING policy is active. How "active" is configured is part of the P-BINDING design.
  - The row actions (Verify / Cancel Identity, email challenge) stay available as evidence operations whatever the policy (C4 semantics, not reopened).

**Decisions this request needed (now decided, above):**
- **IP-D1, what "capture IP address" means:**
  - **(a) display only:** show the voter's last-seen / last-login IP as audit information, no effect on voting;
  - **(b) bind:** record a registered IP for the voter that the Security Verdict Authority enforces. That is **P-BINDING**, a candidate policy that is inert and deferred (Q3-D6). Its storage is the PBDIGIT-234 / PBDIGIT-222 decision, so (b) cannot be built inside this story without reopening Q3-D6.
  - Recommendation: (a) now, (b) only through PBDIGIT-222 / Q3-D6.
- **COL-D1, visibility:**
  - Today the Identity column shows only while the policy requires identity (C4-B).
  - Should the three columns always show (evidence is recordable whatever the policy), or each only while its requirement / policy is active?
  - Recommendation: always show the Identity and Email columns as evidence state, with "not required" wording when the policy is off. Show the IP column always.

## Files and components involved

| Gap | Files |
|---|---|
| 1 Lifecycle | `resources/js/Pages/Elections/Voters/Index.vue` (label / action); backend unchanged (`VoterVerificationController::revoke`, `RevokeIdentityVerificationHandler`, `EloquentIdentityEvidenceStore`) |
| 2 Separation | `resources/js/Components/Election/IdentityAttestationDialog.vue`; `VoterVerificationController::store` validation / mapping; `RecordIdentityVerificationCommand` / `IdentityEvidenceRecord` (network fields); `EloquentIdentityEvidenceStore::recordActive`; `IdentityAttestationDialog.spec.js` (tests 68–94 replaced) |
| 3 Email | **new:** `AdmissionRequirement::EmailVerification`; email evidence storage + challenge table (migration); `IssueEmailChallenge` / `ConfirmEmailChallenge` handlers; notification; `EloquentVotingEntitlementQuery::isAdmitted()` (evidence assembly); admission-policy UI (second toggle); Voter Management column / action |

## RED test plan

**Written (uncommitted). They run after the PBDIGIT-246 regression finishes (no overlapping runs).**

| File | Test | Expected at HEAD |
|---|---|---|
| `tests/Frontend/VoterIdentityLifecycle.spec.js` | L1 an unverified voter offers Verify Identity | pass (guard) |
| | **L2** a verified voter offers Cancel Verification, not Re-verify | **RED** |
| | **L3** Cancel calls `elections.voters.verification.revoke` and opens no dialog | **RED** |
| | L4 after cancellation, Verify Identity again | pass (guard) |
| | **S1** the identity dialog shows no IP and no fingerprint input | **RED** (ID-D2) |
| | **S2** the identity dialog submits no IP / device fields | **RED** (ID-D2) |
| `tests/Feature/Election/IdentityVerificationLifecycleTest.php` | B1 Verify → Cancel: admission ends, history kept, revocation audited | pass (guard: the backend already does this) |
| | **B2** identity verification stores no IP / device data in identity evidence | **RED** (ID-D2) |
| | B3 identity verification does not consult the Security Verdict Authority | pass (guard) |

**RED run 2026-10-05:**
- **Frontend:** L1 ✓ · L2 ✗ ("Re-verify Identity" shown) · L3 ✗ (no Cancel button) · L4 ✓ · C1 ✗ (no Email Verification / IP Address column) · C2 ✓ · C3 ✗ · S1 ✗ (dialog shows 203.0.113.7) · S2 ✗ (payload has `verified_ip`).
- **Backend:** B1 ✓ (admission ends, history kept, `VerificationRevokedEvent` raised) · B2 ✗ (`verified_ip` stored) · B3 ✓.
- **Column tests C1–C3** use a proposed prop contract (`admission.emailRequired`, `ipBinding.active`), to be fixed at GREEN.
- **Audit check corrected while writing B1:** `ElectionAuditService::log()` writes to the election audit **file**, not the `election_audit_logs` table. B1 asserts the domain event instead.

**Email:** no RED yet. E-D1 … E-D4 shape the contract (scope, storage, who issues, expiry). Writing tests first would pre-empt those decisions. The test list from the review (challenge generated / sent / valid / invalid / expired / replayed, evidence, no identity equivalence, policy-only effect, election / organisation isolation) becomes the RED once they are decided.

## Open decisions

| ID | Decision |
|---|---|
| **ID-D1** | Cancel Verification: is a confirmation step required (recommended yes), and must the officer give a reason (the backend currently records only `revoked_by_officer`)? |
| **ID-D2** | **Reverse the C4 decision** (2026-10-04) that kept IP / device as audit-only metadata in the identity dialog? Recommended yes, per this review. **ID-D2b:** the `store` endpoint then ignores IP / device fields, or rejects them (422)? |
| **ID-D3** | G1 immutable evidence history: accept audit-log history for now, or schedule append-only evidence |
| **E-D1** | Email evidence scope: election-scoped (recommended), or a user-level verified-address fact reusable across elections (an explicit cross-election rule) |
| **E-D2** | Email evidence storage: its own table (recommended), or a typed row beside `voter_verifications` (which is unique per election + voter, so it would need a schema change) |
| **E-D3** | Who may issue a challenge: the voter (self-service), the officer, or both |
| **E-D4** | Token expiry and resend limits |
| Stories | Gap 1 → new story, or a PBDIGIT-213 follow-up slice · Gap 2 → extend **PBDIGIT-222** (owner of IP ≠ identity) · Email → new story |

## Out of scope

PBDIGIT-246, P-DENSITY, Q3-D7, IPv6, device binding, `voter_verification_mode`, `MAX_USE_IP_ADDRESS`, `verifyCode()` / `vote_post_check()`, anonymity A5 / A6 / A8, receipts, final-cast authorization, the Security Verdict Authority. No generic verification framework.
