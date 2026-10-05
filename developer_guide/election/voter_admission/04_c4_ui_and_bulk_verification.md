# 04 — C4: Voter Admission UI, Identity Attestation and "Verify All"

**Slice:** `PBDIGIT-213` C4 (C4-A…D, correction slices 1–3) · **ADR:** `ADR_20261004_1549` · **PO decisions:** C4-D1…D4 (Verify All), 2026-10-04 corrections (identity only; `attested` contract; IP entry point removed) · **Plan:** `docs/plans/20261004-1549-election-only-voter-admission-v1-plan.md` §C4

---

## Purpose

C2 created the policy, and C3 enforced it. **C4 gives people the controls:**
- the **Chief or Deputy Chief** switches Voter Admission ON or OFF;
- they see each voter's **Identity Verification** state;
- they verify identities one by one (**Verify Identity**) or all at once (**Verify All**);
- they start **Suspend Identity**;
- the voter sees why they cannot vote yet.

**The UI is never the authorisation source.** Every action goes through the C2/C3 endpoints and handlers, which re-derive authority, the window and the target.

## One verification concept on this page

```
Verify Identity ─┐                                          (individual: method officer_attestation)
                 ├─► OFFICER_IDENTITY_VERIFICATION evidence ─► AdmissionDecision ─► VotingEntitlement
Verify All ──────┘   voter_verifications (status active)       (bulk: method officer_bulk_attestation + batch_id)
```

**IP verification is untouched legacy and not part of this page.**
- The old "Verify / Re-verify" button opened `VoterVerificationModal` (last-login IP, device hash, notes). It wrote the *same* `voter_verifications` row, so an IP click admitted a voter, and an identity verification made the IP look verified.
- The **UI entry point was removed** from Voter Management.
- `VoterVerificationModal.vue` (retitled "Verify IP Address") and the IP backend fields stay in the codebase.
- Restoring IP verification as its own legacy record is **`PBDIGIT-222`**, which documents exactly how it worked.

## Key files

| File | Role |
|---|---|
| `resources/js/Components/Election/VoterAdmissionCard.vue` | Settings card: ON/OFF switch, impact text, locked state, **Save Admission Policy** → `elections.admission-policy.update` (C2 endpoint) |
| `resources/js/Components/Election/IdentityVerificationCell.vue` | the column cell: Verified ✓ / ⚠ Identity verification required / ⚠ Re-verification required + **Verify Identity** |
| `resources/js/Components/Election/IdentityAttestationDialog.vue` | individual attestation: required checkbox, notes, collapsed "Technical details — audit only, not identity proof" |
| `resources/js/Components/Election/BulkVerifyDialog.vue` | Verify All: exact count + statement + checkbox |
| `resources/js/Components/Election/AdmissionPendingNotice.vue` | voter-facing notice on `Election/Show.vue` |
| `resources/js/Pages/Elections/Voters/Index.vue` | wiring: column, buttons, badge, dialogs |
| `app/Http/Controllers/ElectionVoterController.php` | `admission` and `identityVerification` display read-models |
| `app/Http/Controllers/Election/VoterVerificationController.php` | `store()` (individual, requires `attested`), `verifyAll()` |
| `app/Contexts/Elections/Application/Handlers/BulkRecordIdentityVerificationHandler.php` | the bulk command |
| `app/Contexts/Elections/Domain/ValueObjects/VerificationMethod.php` | `officer_attestation` · `officer_bulk_attestation` |
| `app/Contexts/Elections/Domain/Exceptions/BulkVerificationCountChanged.php` | refused when the eligible set changed |

## How it works

### Display read-models (C4-B)

`ElectionVoterController@index` sends two read-models. **They are for display only.** The decision is made by `VotingEntitlementQuery`.

```php
private function admissionProps(Election $election): array
{
    return [
        'required'            => $election->admissionPolicy()->requires(AdmissionRequirement::OfficerIdentityVerification),
        'available'           => VoterAdmissionPolicyController::isAvailableFor($election),
        'bulkVerifiableCount' => count(app(VoterRegistrationQuery::class)->bulkVerifiableVoterIds($election->id)),
    ];
}
```

**Policy vs evidence — two separate questions (PO correction, 2026-10-04):**

| Prop | Answers | Source |
|---|---|---|
| `admission.required` | *Is identity verification **required** in this election?* | the Voter Admission Policy (Election Settings) |
| `identityVerification[user_id]` | *Has this voter's identity **been verified**?* | the evidence row in `voter_verifications` — **independent of the policy** |

`identityVerificationStates()` maps the evidence status to `verified` (`active`), `reverification_required` (`revoked`) or `verification_required` (no row) — **whatever the policy**:

```php
private function identityVerificationStates(Election $election, array $userIds): array
{
    $evidenceStatus = VoterVerification::where('election_id', $election->id)
        ->whereIn('user_id', $userIds)
        ->pluck('status', 'user_id');
    // … active → verified · revoked → reverification_required · none → verification_required
}
```

Policy OFF means *"not required"*, **not** *"cannot be recorded"*: the handlers have no policy dependency, so evidence can be recorded while OFF and simply counts once the policy is switched ON (before ReadyForVoting). Admission itself is unchanged — with the policy OFF every registered active voter is admitted, with or without evidence.

### The page (`Voters/Index.vue`)

| | Policy OFF | Policy ON |
|---|---|---|
| **Verify Identity / Re-verify Identity** (Actions) | ✅ shown — label from the evidence state | ✅ shown |
| **Verify All** | ✅ when eligible voters exist | ✅ when eligible voters exist |
| **Identity Verification column** (status view) | hidden | shown |
| **"Identity verified" badge** next to the name | when the evidence is active | when the evidence is active |
| **Suspend Identity** (the existing two-person proposal, relabelled) | "Propose" | "Suspend Identity" |
| Admission / entitlement | unchanged — nothing required | evidence required |

- **Evidence operations are independent of the policy.** Verify Identity, Re-verify Identity and Verify All record evidence; only the **status column** is policy-oriented and hidden while OFF.
- **"Identity verified" is evidence, not final admission.** The badge text is "Identity verified" and its title says *"Identity-verification evidence recorded — not the complete admission decision"*. It shows one requirement's evidence; the final decision stays the server's `AdmissionDecision` / `VotingEntitlement`. Today identity is the only requirement, so they coincide — when a second requirement exists, a separate, server-derived "Admission status" will be needed. Do **not** compute final admission in the UI.
- **One source of state.** All identity UI state comes from `identityVerification[user_id]`. Never derive it from the older `verifications` prop: that map also contains rows written by the removed legacy IP action. The prop is still sent but unused, pending a later cleanup.
- **Re-verify mode.** The dialog opens in re-verify mode for `verified` and `reverification_required`; the Actions label reads "Re-verify Identity" only for `verified`.
- **Verify All** is offered when `admission.available && admission.bulkVerifiableCount > 0` — **not** dependent on `admission.required`. Its eligibility set (C4-D1) is unchanged.

### Individual attestation — the `attested` contract (correction slice 3)

```php
// VoterVerificationController::store
'attested' => 'accepted',   // required server-side — never only a UI checkbox
```

- **Missing or false `attested`** → validation error. No evidence is written and no audit entry is made.
- **Statements.** The individual statement is `RecordIdentityVerificationCommand::ATTESTATION_STATEMENT` (*"I confirm that this voter's identity has been verified according to the organisation's voter-registration process."*). Bulk uses `BulkRecordIdentityVerificationCommand::ATTESTATION_STATEMENT` (plural).
- **Audit.** `voter_verified` carries `subject_user_id`, `verifier_role`, `requirement`, `method`, `statement`, `policy`.
- **IP and device data** are optional audit metadata. The dialog sends `verified_ip` only for a valid IPv4 address, otherwise `null`.

### Verify All (C4-C, PO C4-D1…D4)

```php
// BulkRecordIdentityVerificationHandler::handle
$role = $this->authority->roleFor($this->leadership->leadershipRoleOf(...)) ?? throw VerifierNotAuthorised::forElection(...);
$candidates = $this->registry->bulkVerifiableVoterIds($command->electionId);
if (count($candidates) !== $command->expectedCount) {
    throw BulkVerificationCountChanged::between($command->expectedCount, count($candidates));
}
foreach ($candidates as $voterId) {
    $recorded[$voterId] = $this->evidence->recordActive(new IdentityEvidenceRecord(..., method: VerificationMethod::OfficerBulkAttestation));
}
```

- **Scope (C4-D1):** active voters of this election who have **never** had evidence. Revoked, suspended, invited and removed voters are excluded: a revoked voter must be re-verified **individually**.
- **Atomic.** The controller wraps the handler in `DB::transaction`. A changed count writes nothing, and the officer sees the new count.
- **Audit (C4-D4):** one `voter_verified` entry per voter (`method`, `batch_id`) plus one `voters_bulk_verified` summary (`batch_id`, `count`, `verifier_role`, `statement`, `policy`). The `batch_id` is audit-only; there is no column for it.

### The voting-process boundary (correction slice 1)

```
VotingEntitlement (asked on EVERY request)  →  code create → code entered → code verified (can_vote_now) → agreement → ballot → vote
```

- **Every slug voting route** runs `ensure.election.voter`, which calls `User::isVoterInElection` → `VotingEntitlementQuery`, *before* the controller.
- **The final `VoteController::store`** re-checks fresh, uncached and inside its transaction.
- **`Code.can_vote_now`** only means *"the code-verification step has been completed"*. It never stands in for entitlement.
- **Pinned by** `VotingProcessEntitlementBoundaryTest` (41 cases): every step is refused to every kind of non-entitled voter, and revocation after the code step refuses the agreement, ballot and final vote while `can_vote_now` stays true.

## How to extend

- **A new admission requirement** (e.g. e-mail confirmation) is a new `AdmissionRequirement` plus its evidence. The column and dialog pattern can be repeated per requirement.
- **Do not** add a second admission mechanism next to `voter_verifications`.
- **Do not** read `verifications` for identity state.
- **IP verification** belongs in its own record (`PBDIGIT-222`). It must never write `voter_verifications`.

## Testing

| Test | Covers |
|---|---|
| `tests/Feature/Election/VoterManagementIdentityStateTest.php` | the evidence states with the policy ON **and OFF** (evidence state independent of the policy), `admission.*`, bulk count |
| `tests/Feature/Election/BulkIdentityVerificationTest.php` | C4-D1 scope matrix, method, statement, count mismatch, authority, window, audit, atomicity; Verify All records evidence **while the policy is OFF** |
| `tests/Feature/Election/VoterAdmissionProductFlowTest.php` | flows A (Verify Identity), A′ (Verify All), B (Suspend Identity → reinstatement + re-verification) |
| `tests/Feature/Election/IdentityVerificationAuthorityTest.php` | `attested` required; audit `method` + `statement` |
| `tests/Feature/Election/VotingProcessEntitlementBoundaryTest.php` | voting-process boundary (above) |
| `tests/Architecture/BulkIdentityVerificationArchitectureTest.php` | bulk command and handler framework-free; controller delegates |
| `tests/Frontend/VoterIdentityEntryPoints.spec.js` | page wiring, cases A–H: evidence actions with the policy OFF and ON, column only when ON, Verify All independent of the policy, "Identity verified" badge text and title, identity state as the only source, no IP action |
| `tests/Frontend/{VoterAdmissionCard,IdentityVerificationCell,IdentityAttestationDialog,BulkVerifyDialog,AdmissionPendingNotice,VoterVerificationModal}.spec.js` | components |

Run: `php artisan test --env=testing <file>` · `npx vitest run tests/Frontend/<spec>`.

## Pitfalls

- **Teleported dialogs re-render:** in a spec, re-query a button after `setValue` instead of keeping the old wrapper (see `BulkVerifyDialog.spec.js`).
- **Mounting `Voters/Index.vue` in Vitest** needs `global.mocks: { route, $page: { props: { errors: {} } } }`, because the template uses `route()` and `$page` through the component context.
- **The `attested` contract** means every test or client that posts to `elections.voters.verify` must send `attested: true`.
- **Legacy rows:** rows created by the old IP "Verify" before C4 stay identity evidence (C3 semantics). They cannot be told apart from attestations except through the `voter_verified` audit metadata (`PBDIGIT-222`).
- **Don't couple evidence actions to the policy.** `admission.required` may only hide the policy-oriented status column — never Verify Identity, Re-verify Identity or Verify All, and never the evidence state the server sends.
- **Don't call the badge "Verified".** It is identity evidence for one requirement; final admission is decided by the server.
- **Anonymity:** C4 adds nothing that links a voter to a vote — evidence and audit concern admission only (ADR-T11). The cast path itself has known anonymity defects (A1–A5, notably the computable `vote_hash`), documented in `docs/publicdigit/reviews/2026-10-05-voting-authorization-and-security-situation.md` and **out of C4 scope**.

**Traceability:** PBDIGIT-213 C4 · ADR_20261004_1549 · PO C4-D1…D4 and the 2026-10-04 corrections (identity only; server-side `attested`; evidence independent of the policy; "Identity verified") · related `PBDIGIT-220` (left column removed), `PBDIGIT-221` (assign members as voters), `PBDIGIT-222` (IP verification as its own legacy record).
