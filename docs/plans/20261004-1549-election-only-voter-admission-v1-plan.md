# Plan — PBDIGIT-213 · Election-Only Voter Admission v1 (Identity Verification) — rev 3

## Progress (living)

| Slice | State |
|---|---|
| C0 | ✅ `112e1a15d` |
| C1 | ✅ GREEN 33/33. 45-suite before/after regression shows 0 delta (166 pre-existing failures, identical tests and causes). Committed; awaiting PO acceptance. As implemented, `standingOf()` / `decide()` / `VotingEntitlementReason` replace the plan's `VotingEntitlementDecision`, and `AdmissionDecision` moves to C2 (PO correction: C1 has no admission concepts). Also resolves the defect of `PBDIGIT-201` (recorded, not accepted by engineering) |
| C2 | ✅ GREEN 49/49 (C1+C2 82/82). 54-suite before/after: 0 per-test and 0 reason delta. Committed; awaiting PO acceptance. As implemented: the dedicated endpoint takes the business toggle `identity_verification_required`; `AdmissionPolicyChange` is the result type; no-op proposals are allowed even when locked; the C2 slice plan was approved separately (C2-D1 default OFF, C2-D2 Election-Only only) |
| C3 | ✅ GREEN 82/82 (C1+C2+C3 167/167). 77-suite regression: 1 changed test (`SimpleStoreTest`), resolved by a PO-approved fixture-only adaptation; everything else unchanged. Committed; awaiting PO acceptance. As implemented: brief `20261004_1802` added the `can_vote` A–G classification and the legacy-boundary tests; target rule C3-D2; the admission-aware cache uses a policy fingerprint plus `VoterVerification::booted()`; revoke shares the `verify_voter_identity` window; findings → PBDIGIT-214…219 |
| C4 | ✅ GREEN incl. the PO-approved correction (identity only; server-side `attested`; IP UI entry point removed → PBDIGIT-222). 89-suite regression vs `622ee91e9`: 0 changed results, 0 reason delta; the only per-test lines are the 3 new A2 tests (passing). Vitest: same 8 pre-existing failures, 370 passing. design-check 91. Guide `04_c4_ui_and_bulk_verification.md`. Awaiting PO review before commit |

## Rev 3 — final PO corrections + frontend brief (2026-10-04) — these OVERRIDE rev 2 where they differ

Sources:
- the PO's final-corrections message (points 1–11; point 11 was truncated at "→ voter becomes" and is read as "→ voter becomes entitled to vote");
- `docs/publicdigit/brainstorming/voter_management/frontend_voter_management.md`.

**R3-1 Primary product workflow: Voter Admission (settings page).**
- A dedicated card on `Elections/Settings/Index.vue`:
  - title **"Voter Admission"**, intro "Choose which verification requirements apply to voters.";
  - row **"Identity verification required"** with an ON/OFF toggle and the help text *"When enabled, every voter must be verified by the Election Chief or Deputy Chief before they can vote."*;
  - the transition-specific impact text;
  - its own **[Save Admission Policy]** button.
- When locked: **"ON — LOCKED"** / **"OFF — LOCKED"**, with *"The admission policy cannot be changed once the election is ready for voting."*
- No technical terms (`AdmissionRequirement`, gate) in the UI.
- Saving goes to a **dedicated endpoint**, `PATCH …/elections/{election}/admission-policy` (`can:manageSettings`) → `ChangeVoterAdmissionPolicyHandler` (application command). It is not mixed into the generic settings PATCH. The handler owns:
  - the lock check (D4, plus the model guard as defence in depth);
  - audit `admission_policy_changed` {from, to, actor, affected};
  - **no** writes to memberships (admission stays derived).
- **This replaces the rev 2 C2 "Settings flow" bullet.**

**R3-2 Impact text.**
- OFF→ON: *"N registered voters currently require identity verification."* (active voters without active evidence)
- ON→OFF: *"N registered voters would no longer need verification to vote."* (active voters not currently admitted)
- `edit()` passes both counts; the card shows the one for the pending transition.

**R3-3 Voter management: additive, legacy kept.**
- The existing **Verify / Re-verify** button and modal stay. The button is relabelled **"Verify Identity"** and is wired to `RecordIdentityVerificationHandler`.
- New **Admission** column, derived on the backend:

  | Policy | Evidence | Shown |
  |---|---|---|
  | OFF | any | "Not required" (neutral) |
  | ON | active | "✓ Identity verified" |
  | ON | revoked | "⚠ Re-verification required" |
  | ON | none | "⚠ Identity verification required" |

- The Membership/Status column is unchanged (shows Suspended etc.).
- When the policy is OFF, Verify stays available but is not styled as required. When ON, it becomes prominent for not-admitted voters.
- **"Suspend Identity"** = the business label of the **existing two-person Propose-suspension action** (PO decision). It is shown for active voters with no pending proposal; the existing Confirm/Cancel flow is unchanged. **Confirming** revokes the evidence's admission effect (C3.4).
- No other existing action is removed or replaced.
- Revoke-in-modal (rev 2 C4) is **dropped**: the only UI path to remove identity admission is the two-person suspension.

**R3-4 Suspension ≠ re-verification (PO decision).**
- After a confirmed suspension, **both** of these are required:
  - (a) the suspension is lifted through the existing reinstatement (`approve` → status active);
  - (b) the Chief/Deputy re-verifies identity.
- Either order works. Verification **never** changes membership status or lifts a suspension. Entitlement = status active ∧ admitted, so this falls out of the model.
- A test pins: verify-only → not entitled; approve-only → not entitled; both → entitled.

**R3-5 Historical evidence.** Unchanged from rev 2. `status=revoked` expresses the loss of the evidence's **current admission effect**; the historical fact "Chief X verified Y at T" is not deleted in meaning. G1 remains.

**R3-6 Product-level acceptance test** (C4, `tests/Feature/Election/VoterAdmissionProductFlowTest.php`):
1. The chief opens settings and sees impact N.
2. The chief turns "Identity verification required" ON and saves (the dedicated endpoint).
3. The manage page shows voters as "Identity verification required".
4. A voter is denied at `slug.code.create`.
5. The chief verifies via the Verify Identity endpoint.
6. The voter is entitled at `slug.code.create`.
7. The manage page shows "Identity verified".

**Unchanged from rev 2:** §0-T mapping (G10), D1 window, D4 lock both layers, §2 versioned cache (both directions tested), VotingEntitlement as final decision only, future mechanisms not built.

## Context

Today an Election-Only import creates `ElectionMembership{role:voter, status:active}` (`VoterImportService.php:302-312`). That single row means *registered*, *admitted* and *entitled*. **Verify** writes `voter_verifications`, but no voting path reads that table, so an unverified imported voter can vote.

The brief `docs/publicdigit/brainstorming/voter_management/voter_verification-architecture.md` asks for:
- a vendor-neutral admission architecture;
- one concrete requirement now: personal identity verification;
- one authoritative voting-entitlement decision;
- no gate engine.

Evidence:
- `docs/publicdigit/reviews/2026-10-04-election-only-voter-admission-gate-investigation.md`
- `docs/publicdigit/reviews/2026-10-04-voter-admission-implementation-feasibility.md`

Rev 2 applies the PO's 14 review points (2026-10-04) and the final read-only verification (§0).

**PO decisions (2026-10-04):**
- **D1 Verification window:** allowed in SetupAdministration, SetupNomination, ReadyForVoting and VotingActive. Denied in Draft, SubmittedForApproval, Approved, Rejected, Suspended, Counting, ResultsPublished and Archived. Verification during VotingActive is legitimate late admission or re-admission, not a bypass.
- **D2 Authority:** *"Identity verification may be performed by the **Chief or Deputy Chief of the election**."* It is election-scoped; a global or org-wide officer role is never sufficient by itself. Authority is separate from policy. **§0-T resolved by the PO (2026-10-04):**
  - The intended domain concept is the **Election Committee leadership** (Election → Election Committee → Chief, Deputy Chief).
  - It is **not** the Manifesto's independent acceptance Committee (EM-GOV-029), which must not be used as the verifier authority.
  - v1 obtains the authority **technically** from the existing election-scoped `election_officers` `chief`/`deputy` assignments.
  - `election_officers` is **not** renamed or redefined as the Election Committee.
  - No complete Election Committee model is claimed or built. Formalising it is follow-up G10.
- **D3 Evidence:** records who verified, in which role, when, by which method, and notes. IP/device data is audit metadata only and never proof of identity.
- **D4 Policy lock:** the policy can change only in Draft, SetupAdministration and SetupNomination. It is immutable from ReadyForVoting onward. Enforced at application level **and** as model/domain defence in depth.
- **D5 Suspension:** a confirmed suspension ends the *current admission effect* of the identity evidence, so re-verification is required. The *historical fact* is not destroyed in meaning (see §1).
- **D6 Packaging:** one story PBDIGIT-213: ADR (C0) + C1…C4, one commit each. C3 is internally structured as C3.1–C3.5.

---

## 0. Final read-only verification (done 2026-10-04) — results

| Area | Finding | Effect on plan |
|---|---|---|
| **T — Committee vs officer terminology** | ⚠ **MATERIAL — needs a PO decision before C3.2.** Three different things are called "Election Committee" today:<br>**(1)** the adopted Manifesto *Election Committee* (EM-GOV-026/029/033): an independent **acceptance** body of at least 3 seats, appointed by the governance body, with **no Chief/Deputy roles** (`Contexts/Election/Domain/OperatingCore/Committee/ElectionCommittee.php`). The Manifesto lists *Election Chief · Deputy · Election Committee members* as **distinct** appointments (EM-GOV-026) and says *"The Election Chief has no appointment authority over the Deputy or the Committee"* (EM-GOV-028).<br>**(2)** `organisation_participants.participant_type='election_committee'`: an org-level participant category, not election-scoped, with no Chief/Deputy.<br>**(3)** `election_officers` (roles `chief`/`deputy`/`commissioner`, `election_id` nullable): election-scoped officers. The UI labels them EN "Election Chief/Deputy/Commissioner" and DE **"Vorsitzender / Stellvertreter / Beisitzer"**. The newsletter audience "Election Committee" resolves to this table (`NewsletterAudienceSegmentationTest:161`). The suspension UI calls them "committee members". `ElectionPolicy::manageSettings/manageVoters` = active chief/deputy **of this election** (an org-wide `election_id NULL` officer does not qualify).<br>**Conclusion:** the only modelled, election-scoped Chief and Deputy Chief are (3). Whether the PO's "Election Committee Chief/Deputy Chief" **is** (3), or a chair/deputy of body (1) that is not yet modelled, is a business question. It is **not** resolved silently. | **RESOLVED (PO, 2026-10-04):** neither a silent mapping onto (3) nor (1). The intended concept is the Election Committee **leadership** (Chief, Deputy Chief), which is not yet a complete domain model. v1 uses (3)'s election-scoped chief/deputy as the **technical authorisation source** behind a port. The ADR documents this and claims no complete Committee model. Follow-up G10 |
| Officer assignments | `onf-2-f8ba4321`: one active chief scoped to this election. Org-wide officers can exist (`election_id NULL`) and correctly do **not** pass the election-scoped check | Matches D2 ("global role never sufficient") |
| Lifecycle | `ElectionLifecycle::of($e)->isLocked()` is false only in Draft, SetupAdministration and SetupNomination (`ElectionLifecycleEngineImpl.php:295-304`). Operation `verify_vote` (`OperationCapabilityMapper.php:45`) is used **only** by the two verification routes | Matches D1/D4. A new explicit operation replaces `verify_vote` |
| Cache | Default store `file` (`config/cache.php:18`), `array` in tests. **No tag support**, so enumerating per-voter keys is not possible | §2 adopts **version-in-key** (option B) |
| VoterVerification | Unique `(election_id,user_id)`; update-in-place on re-verify; `exists:users,id` only | As planned |
| Suspension confirm | `ElectionVoterController::confirmSuspension` → `ElectionMembership::confirmSuspension()` sets `status=inactive`, `suspension_status=confirmed` (`ElectionMembership.php:211-227`) | As planned (C3.4) |
| Entitlement call sites | **5 copies:** `User::isVoterInElection` (`:316`; serves `EnsureElectionVoter:37`, `VoteEligibility:96`, trait cached branch at `CodeController:71,236,410,532` and `VoteController:234,475`), the trait's uncached branch (`VoteController:1586` store, `:2276` verify; no org check, tenant scope kept), `ElectionVotingController@show` (`:41-57`, treats `inactive`/`invited` as eligible), `@start` (`:113-125`) | As planned (C1) |

---

## 1. Final architecture

```
ELECTION (owns voting-time entitlement — ADR-002 (i), EM-ENT-001/007)
 │
 ├─ VoterAdmissionPolicy ........ WHAT must a registered voter satisfy?   {"requires":[...]}
 │                                 v1: [] | [OFFICER_IDENTITY_VERIFICATION]
 ├─ VerificationAuthority ....... WHO may create HUMAN-produced evidence, where a requirement needs a human
 │                                 v1: Chief / Deputy Chief of THIS election
 │                                 resolved via port VerificationAuthoritySource
 │                                   v1 adapter → election_officers (chief|deputy, status active, election_id = this)
 │                                   future   → Election Committee leadership model (G10), same port
 └─ ElectionLifecycle (existing)  isLocked · canVote · state

ADMISSION EVIDENCE (voter_verifications, evolved) .... WHAT actually happened
   historical fact : verified_by · verifier_role · method · verified_at · notes · (ip/device = audit only)
   current effect  : status active|revoked · revoked_by/at · revocation_reason
                     ("does this evidence currently satisfy the requirement?")

AdmissionDecision = VoterAdmissionPolicy.isSatisfiedBy(AdmissionEvidence)     (empty policy ⇒ admitted)

VOTING ENTITLEMENT — the FINAL decision; composes, owns none of its inputs
   registered ∧ role=voter ∧ status=active ∧ org(membership)=org(election) ∧ ¬deleted
   ∧ AdmissionDecision.admitted ∧ ElectionLifecycle.canVote()

BALLOT PATH (unchanged, outside entitlement) — trust gate · IP/device rules · one-ballot lock · anonymity
```

**ADR invariants (recorded verbatim):**
- Registration ≠ Admission ≠ Voting Entitlement.
- Suspension ≠ Removal.
- Verification Evidence ≠ Voting Entitlement.
- Identity Verification ≠ IP/Device Verification.
- The Election Admission Policy defines **what** must be satisfied.
- The Verification Authority defines **who** may create **human** verification evidence.
- The Evidence records **what actually happened**.
- VotingEntitlement determines whether the voter may vote **now**.
- Historical fact ≠ current admission effect. A suspension invalidates the effect, never the fact. The v1 single-row storage is a **technical limitation**, not protocol history (gap G1).

**Ownership boundaries:**
- `VotingEntitlement` composes registration, role, status, org scope, admission decision and lifecycle.
- It does **not** own:
  - the admission policy (`VoterAdmissionPolicy`);
  - verification authority (`VerificationAuthority`);
  - the suspension workflow (existing suspension code plus a C3.4 handler);
  - ballot anonymity or IP/device security (ballot path).

**Extensibility:**
- `AdmissionRequirement` = what · `AdmissionEvidence` = proof · `VerificationAuthority` = who, **only for human-produced evidence**. Authority is **not** a mandatory property of a requirement:
  - email/WhatsApp are voter-controlled or provider-produced;
  - eID is produced by an external identity provider.
- A new mechanism = a new requirement case + its evidence producer (+ an authority rule only if a human acts).
- OR semantics are a clause inside `VoterAdmissionPolicy`.

**Vocabulary (stable):** `VoterAdmissionPolicy`, `AdmissionRequirement`, `AdmissionEvidence`, `AdmissionDecision`, `VerificationAuthority`, `VotingEntitlement`.
- **No** GateEngine, GenericGate, GateFactory, GateRegistry or AbstractGate.
- "Gate" is not used in names, because it already means acceptance gates in `OperatingCore/Gate`.

---

## 2. Cache design (deterministic invalidation, no enumeration)

- **Cached:** only the *registration + admission* part, keyed
  `user.{u}.voter.v3.{e}.{policyFingerprint}.{evidenceStamp}`, where:
  - `policyFingerprint` = sha1 of the normalised policy JSON. A policy change produces a new key, so every old decision becomes unreachable without enumerating voters.
  - `evidenceStamp` = cheap per-(election,voter) evidence marker. **Decision at C3.1 RED:**
    - **(a)** `Cache::forget` of the single (u,e) key, which is deterministic because the hook knows u, e and the current policy; or
    - **(b)** include `voter_verifications.updated_at` in the key at the cost of one indexed read.

    Default (a). The test proves it.
- **Never cached:** the lifecycle `canVote()`, which is always evaluated fresh.
- **Membership changes:** the existing `ElectionMembership::booted` hook forgets the (u,e) key under the current fingerprint.
- **Authoritative fallback:** `VoteController::store` calls the **uncached** query inside the transaction.
- **Invariant tested:** a policy change or a verification change means no stale entitlement survives, in both directions:
  - denied → verified → allowed immediately;
  - allowed → revoked or suspended → denied immediately.

---

## 3. Slices

### C0 — ADR + story + plan copy (docs only)
- ADR `docs/adr/ADR-<next>-voter-admission-policy-and-voting-entitlement.md`. It contains:
  - the §1 model and invariants;
  - D1–D6;
  - the §0-T resolution: the intended concept is Election Committee leadership (Chief, Deputy Chief); it is not the acceptance Committee (EM-GOV-029); v1's technical source is election-scoped `election_officers` chief/deputy behind `VerificationAuthoritySource`; no complete Committee model is claimed (G10);
  - EM-OPEN-001, answered: *Election-Only registration does not admit when the policy requires evidence*;
  - ADR-002;
  - the 2026-08-13 "outside ElectionConstitution" ruling, which is respected.
- Backlog `docs/publicdigit/backlog/PBDIGIT-213-election-only-voter-admission-v1.md` (PBDIGIT-212 header format).
- Placement for both via `php scripts/doc-placement.php`.
- Copy this plan to `docs/plans/20261004-HHMM-election-only-voter-admission-v1-plan.md` (ES-004.2).

### C1 — Unify Voting Entitlement and enforce election scope
- **Security/business-rule correction (stated in the ADR and commit):** the in-transaction check (`EnsuresVoterMembership` uncached branch, used by `vote.store` and `vote.verify`) currently omits *membership organisation = election organisation* and depends on the ambient tenant scope. C1 enforces election-derived scope there too (PBDIGIT-65/69, ADR-002 (i)). `show()` now reports `inactive`/`invited` as not eligible. Everything else is unchanged.
- **Domain** (`app/Contexts/Elections/Domain`, pure PHP, final):
  - `ValueObjects/VotingEntitlementDecision` (entitled + reason: `NotRegistered`, `NotVoterRole`, `NotActive`, `OrganisationMismatch`, `NotAdmitted`, `ElectionNotVotable`)
  - `Policies/VotingEntitlement::decide(RegistrationFacts, AdmissionDecision, bool votable)`
- **Application:** `Ports/VotingEntitlementQuery`.
- **Infrastructure:**
  - `Queries/EloquentVotingEntitlementQuery`: one query, `withoutGlobalScope('tenant')`, SoftDeletes kept, org derived from the election (the pattern at `User.php:326-335`).
  - `CachedVotingEntitlementQuery` (key per §2).
  - Both bound in `AppServiceProvider` (`:140`).
- **Re-pointed, signatures kept:** `User::isVoterInElection`, `User::invalidateVoterCache`, `EnsuresVoterMembership` (both branches), `ElectionVotingController@show` / `@start`, `ElectionMembership::booted` invalidation.
- In C1, `AdmissionDecision` is always admitted, because there is no policy yet.

### C2 — Admission policy + evidence schema
- **Migration A:** `elections.voter_admission_policy` json **not null default `{"requires":[]}`**, so existing elections are unchanged.
- **Migration B:** `voter_verifications` + `verifier_role`, `method` (default `officer_attestation`) and `revocation_reason`, all nullable for legacy rows. The unique `(election_id,user_id)` is kept (gap G1).
- **Domain:**
  - `AdmissionRequirement` (1 case)
  - `VoterAdmissionPolicy`: `none()`, strict `fromPersistence`, sorted/de-duplicated `toPersistence` (neutralises the `detectChanges` string diff), `isSatisfiedBy(AdmissionEvidence): AdmissionDecision`
  - `AdmissionEvidence`
  - `AdmissionDecision`
- **Policy lock (D4), both layers:**
  - **(i)** Application: `ElectionSettingsController::update` rejects a policy change when `ElectionLifecycle::of($e)->isLocked()`.
  - **(ii)** Model: in `Election::booted` `saving` (`:2267`), a dirty `voter_admission_policy` while locked → `LogicException`. This follows the existing snapshot-guard pattern.
- **Settings:**
  - `UpdateElectionSettingsRequest`: rule `voter_admission_policy.requires.*` `in:OFFICER_IDENTITY_VERIFICATION`.
  - The `settings_changes` + `settings_changed` audit is reused as is.
  - `edit()` passes `lifecycle.isLocked` and `admissionImpact`.
- **"Affected voters" (v1 boundary, documented in the ADR):** *registered voters whose effective voting entitlement would change under the proposed policy*, implemented only for the transition `none ↔ OFFICER_IDENTITY_VERIFICATION`:
  - `none → OIV`: registered voters (role voter, status active, not deleted) **without** active identity evidence. They would lose entitlement.
  - `OIV → none`: registered voters that are active and **not** admitted. They would gain entitlement.

  This is computed by an application query on the transition, not a generic "unverified" count.
- **Vue** `Elections/Settings/Index.vue`: a section "Voter admission" after `:428`, with:
  - a toggle;
  - disabled with an explanation when locked;
  - the transition-specific impact text.

### C3 — Admission enforcement & identity verification (one commit, internally structured)
- **C3.1 Admission enforcement:**
  - The entitlement query builds `AdmissionEvidence` from `EXISTS voter_verifications(election_id,user_id,status='active')`, only when the policy is non-empty.
  - Cache per §2, with `VoterVerification::booted` invalidation.
  - `show()` exposes `admissionPending`.
- **C3.2 Verification authority:**
  - Domain: `Policies/VerificationAuthority::roleFor(?ElectionLeadershipRole): ?VerifierRole`, with enum `VerifierRole` = `ELECTION_CHIEF` | `ELECTION_DEPUTY_CHIEF` and enum `ElectionLeadershipRole` = `Chief` | `DeputyChief`. Pure; it grants only those two roles.
  - Application port `Ports/VerificationAuthoritySource::leadershipRoleOf(userId, electionId): ?ElectionLeadershipRole`.
  - v1 adapter `Infrastructure/Queries/ElectionOfficerVerificationAuthoritySource`: active `election_officers` row with `election_id = this election` and role `chief`→Chief or `deputy`→DeputyChief. Org-wide (`election_id NULL`) and `commissioner` → null.
  - The adapter's docblock and the ADR state that it is the **current technical source** of Election Committee leadership, not the Committee model (G10).
  - The route keeps `authorize('manageSettings')` as the outer check. The handler is authoritative: it re-derives the role via the port and records `verifier_role` on the evidence.
- **C3.3 Verification lifecycle:**
  - New operation `verify_voter_identity` in `OperationCapabilityMapper` with the explicit D1 whitelist; routes `organisations.php:299-302` switch to it; `verify_vote` is removed (sole users).
  - `RecordIdentityVerification` / `RevokeIdentityVerification` commands (DTOs) + handlers. Their order of work:
    - (a) the target is a registered voter of this election (closes arbitrary user IDs);
    - (b) authority;
    - (c) evidence upsert (historical fields + current effect);
    - (d) `IdentityAttestedEvent` / `VerificationRevokedEvent` (existing);
    - (e) an audit entry with `subject_user_id`, `verifier_role`, requirement and policy.
- **C3.4 Suspension invalidation:**
  - `confirmSuspension` revokes the current effect of active identity evidence in the same transaction (`status=revoked`, `revocation_reason=suspension_confirmed`, revoker = the confirmer).
  - ADR text: *the fact "X verified Y at T" stands; its admission effect ends*.
  - Reinstatement requires a new verification.
- **C3.5 Bypass protection:** tests prove an unverified voter cannot become entitled via approve, assign-by-UUID, re-import, a direct call to any slug route, or a stale cache. No endpoint redesign (PBDIGIT-214+).

### C4 — UI (display only; never the authorisation source) — superseded by the C4 slice plan below
- Manage page: the controller passes `election.admission_policy` and a backend-computed per-voter `admission` map (`admitted` | `verification_required` | `not_applicable`).
- An "Admission" column after Status (`Index.vue:227/284`, `status-pill`): "Identity verified" / "Identity verification required", plus a filter.
- Revoke in the modal.
- The IP field becomes optional (`VoterVerificationModal.vue:124`).
- Voter-facing `Election/Show.vue`: when `admissionPending`, show *"Your identity must be verified by the Chief or Deputy Chief of the election before you can vote."*
- `npm run design-check`, reporting before and after.

---

### C4 slice plan (2026-10-04, PO: "C3 accepted, start C4"; then "add this to c4 plan") — APPROVED, implemented (see "C4 correction" below)

**Goal:** expose the C2/C3 model through the UI. The Chief or Deputy can:
- switch **Voter Admission** ON/OFF;
- see each voter's **Identity Verification** state;
- **Verify Identity** one by one;
- **Verify All** eligible voters in one bulk attestation;
- start **Suspend Identity**, which is the existing two-person process.

The voter sees why they cannot vote yet. **The UI is never the authorisation source.** Every action goes through the C2/C3 endpoints and handlers, and entitlement stays with `VotingEntitlementQuery`.

#### C4-A Voter Admission card (settings)
- New component `resources/js/Components/Election/VoterAdmissionCard.vue`, embedded in `Pages/Elections/Settings/Index.vue` after the verification-mode section. It uses the C2 props `admission.{identityVerificationRequired, isLocked, available, affectedVoters}`.
- **Contents:** title "Voter Admission"; intro "Choose which verification requirements apply to voters."; row "Identity verification required" with an ON/OFF toggle; help text *"When enabled, every voter must be verified by the Election Chief or Deputy Chief before they can vote."*
- **Impact text:**
  - OFF→ON: *"N registered voters currently require identity verification."*
  - ON→OFF: *"N registered voters would no longer need verification to vote."*
- **Save Admission Policy** → `router.patch(route('elections.admission-policy.update', slug), { identity_verification_required })`.
- **Locked:** "ON — LOCKED" / "OFF — LOCKED" with *"The admission policy cannot be changed once the election is ready for voting."*; toggle and save disabled.
- **Not Election-Only:** the card is hidden (`available = false`).
- No technical terms (no `AdmissionRequirement`, no "gate").

#### C4-B Identity Verification column (voter management)
- **Backend:** `ElectionVoterController@index` adds:
  - `admission = { required: bool, available: bool, bulkVerifiableCount: int }`;
  - a per-voter map `identityVerification[user_id]` with values:

    | Value | Meaning |
    |---|---|
    | `not_required` | policy OFF |
    | `verified` | active evidence |
    | `verification_required` | ON, no evidence |
    | `reverification_required` | ON, evidence revoked |

  These are display read-models only.
- **Component `IdentityVerificationCell.vue`:**

  | State | Shown |
  |---|---|
  | `verification_required` | **[Verify Identity]** (opens the existing `VoterVerificationModal`; the legacy Verify flow kept) |
  | `reverification_required` | **⚠ Re-verification required** + [Verify Identity] |
  | `verified` | **Verified ✓** (+ Re-verify available) |
  | `not_required` | "Not required" (neutral); the legacy Verify stays available but not emphasised |

- **Existing actions are kept.** Propose-suspension is labelled **"Suspend Identity"** when the policy is ON (R3-3; the two-person flow is unchanged). No other action is removed.
- **`VoterVerificationModal.vue`:** the IP field becomes optional (IP is audit metadata, never proof, D3/inv. 4).

#### C4-C Verify All (bulk attestation) — PO decisions C4-D1…D4 (2026-10-04)

One domain operation with **the same admission evidence** as the individual action:

```
Verify Identity ─┐
                 ├─► OFFICER_IDENTITY_VERIFICATION evidence ─► AdmissionDecision ─► VotingEntitlement
Verify All ──────┘
```

| # | Decision |
|---|---|
| **C4-D1 Scope** | **Active registered voters who have never had evidence**: role voter, status `active`, not soft-deleted, **no `voter_verifications` row at all** for this election. **Excluded:** inactive (suspended), invited, removed, already verified, and **anyone whose evidence was revoked** (for any reason, incl. `suspension_confirmed`). Those must be re-verified individually, so a bulk click can never undo the suspension-revalidation step. |
| **C4-D2 Method** | Same requirement (`OFFICER_IDENTITY_VERIFICATION`) and same `verifier_role` derivation, but **`method = officer_bulk_attestation`** (individual: `officer_attestation`). Admission effect identical; always distinguishable. |
| **C4-D3 Attestation** | The dialog shows the **exact count** and the statement *"I confirm that the identities of these voters have already been verified according to the organisation's voter-registration process."* The server requires `attested = accepted` **and** `expected_count`. If the eligible count differs at execution time, the request is **refused** (nothing written) and the dialog refreshes. The statement is stored in each evidence row's `notes` and in the audit. |
| **C4-D4 Audit** | **One `voter_verified` entry per voter** (`subject_user_id`, `verifier_role`, `method = officer_bulk_attestation`, `batch_id`) **plus one `voters_bulk_verified` summary** (actor, count, `batch_id`, statement). |

Backend design:
- **Domain:** enum `VerificationMethod` { `OfficerAttestation`, `OfficerBulkAttestation` }; exception `BulkVerificationCountChanged`.
- **Port `VoterRegistrationQuery`:** + `bulkVerifiableVoterIds(electionId): list<string>` (the C4-D1 set).
- **`IdentityEvidenceRecord`:** + `method` (default `OfficerAttestation`). `EloquentIdentityEvidenceStore` writes it (it is currently hard-coded).
- **Application:** `BulkRecordIdentityVerificationCommand` (`electionId`, `verifierId`, `expectedCount`, `statement`) → `BulkRecordIdentityVerificationHandler`. Its steps:
  1. **authority** (Chief/Deputy, as individual);
  2. candidates;
  3. count check;
  4. **one DB transaction**, each voter recorded with `method = officer_bulk_attestation`, `notes = statement`;
  5. return `{ batchId, voterIds, verifierRole }`.
- **Cache:** each evidence write invalidates through `VoterVerification::booted()` (C3 mechanism, unchanged).
- **HTTP:** `POST …/elections/{election}/voters/verify-all` → `elections.voters.verify-all`, in the **same `election.state:verify_voter_identity` group** (window D1) and `manageSettings`. The controller writes the per-voter + summary audit.
- **UI:** a **[Verify All Voters]** button above the table, shown only when the policy is ON and `bulkVerifiableCount > 0`, with component `BulkVerifyDialog.vue`. The dialog shows "Verify N voters?" with the statement, a required confirmation checkbox, and [Cancel] [Confirm]. Confirm stays disabled until the box is checked.

#### C4-D Voter-facing notice
- Component `AdmissionPendingNotice.vue` on `Election/Show.vue` when `admissionPending` (C3 prop): *"Your identity must be verified by the Chief or Deputy Chief of the election before you can vote."*

#### C4 TDD (RED first; full source shown and linted before placing)

| # | File (new) | Cases |
|---|---|---|
| 1 | `tests/Feature/Election/VoterManagementIdentityStateTest.php` | `identityVerification` map for all four states; OFF → all `not_required`; `admission.required/available`; `bulkVerifiableCount` = C4-D1 set |
| 2 | `tests/Feature/Election/BulkIdentityVerificationTest.php` | **Scope matrix:** active-never-verified ✓; active-verified ✗; active-revoked (incl. `suspension_confirmed`) ✗; inactive ✗; invited ✗; removed ✗; other election ✗. `method = officer_bulk_attestation`; `notes` = statement; `verifier_role` derived. `attested` missing → refused; count mismatch → refused, nothing written; commissioner / other-election chief / org-wide chief → 403; window ✓ SetupAdministration/VotingActive, ✗ Draft/Counting. Audit: N per-voter entries + 1 summary sharing a batch id. Cache: bulk-verified voters are entitled immediately (policy ON). Atomicity: a failure mid-batch writes nothing |
| 3 | `tests/Feature/Election/VoterAdmissionProductFlowTest.php` | **Flow A (R3-6):** settings impact N → turn ON → manage page `verification_required` → voter refused at `slug.code.create` → Verify Identity → `verified` → voter reaches code entry (election opened by lifecycle facts). **Flow A′:** same with Verify All. **Flow B (Suspend Identity):** verified → propose + confirm → `reverification_required` and suspended → approve alone ⇒ still not entitled → re-verify ⇒ entitled |
| 4 | `tests/Frontend/VoterAdmissionCard.spec.js` | ON/OFF rendering, help text, transition-specific impact text, locked rendering + disabled controls, hidden when unavailable, save → `router.patch(...admission-policy.update…, {identity_verification_required})` |
| 5 | `tests/Frontend/IdentityVerificationCell.spec.js` | four states → labels/buttons; Verify Identity emits `verify` |
| 6 | `tests/Frontend/BulkVerifyDialog.spec.js` | count + statement shown; Confirm disabled until checkbox; confirm → `router.post(...verify-all…, {attested: true, expected_count: N})` |
| 7 | `tests/Frontend/AdmissionPendingNotice.spec.js` | notice text |
| 8 | `tests/Frontend/VoterVerificationModal.spec.js` | submit enabled with no IP |
| 9 | extend `tests/Architecture/IdentityVerificationArchitectureTest.php`? **No.** New `tests/Architecture/BulkIdentityVerificationArchitectureTest.php`: bulk handler/command framework-free; the bulk controller action delegates (no direct `VoterVerification::create`) |

#### C4 RED-phase findings and confirmed constraints (recorded 2026-10-04; no production code yet)

**Authorization (Verify All)**
- `ElectionPolicy::manageSettings` (`app/Policies/ElectionPolicy.php:46-53`) was inspected. It matches the C3 authority predicate **exactly** (an active `ElectionOfficer`, role `chief`|`deputy`, `election_id` = this election).
- It therefore remains the **outer route-level check** for `Verify All`, as for individual Verify.
- **`VerificationAuthoritySource` stays authoritative.** The bulk handler independently enforces the target-election Chief/Deputy authority and refuses with `VerifierNotAuthorised` otherwise.
- **PO decision (GREEN approval):** **no artificial route-pass/handler-refusal test.** Both predicates intentionally represent the same Chief/Deputy target-election rule. Independent handler authority is proven instead by:
  - the architecture test (the controller delegates to the handler);
  - the handler resolving authority through `VerificationAuthoritySource` itself.
- The existing matrix stays: **commissioner, other-election Chief and organisation-wide Chief are refused**, and nothing is written.

**Admission evidence**
- Confirmed: `VotingEntitlementQuery` reads only what admission needs (the evidence's existence and `status`).
- **`notes` (incl. the attestation statement) and the verification `method` must NOT influence entitlement.**
- `BulkIdentityVerificationArchitectureTest::test_the_entitlement_decision_does_not_read_notes_or_method` already **passes**. It stays as a permanent regression/architecture boundary.

**Batch id (PO decision, 2026-10-04): audit only.** The shared `batch_id` is carried in every per-voter `voter_verified` audit entry and in the `voters_bulk_verified` summary. There is **no `voter_verifications.batch_id` column and no migration in C4**. Evidence rows are linked to the batch through the audit plus `method = officer_bulk_attestation`.

**C4 RED baseline (recorded)**

| Suite | Result | Classification |
|---|---|---|
| PHPUnit (`VoterManagementIdentityStateTest`, `BulkIdentityVerificationTest`, `VoterAdmissionProductFlowTest`, `BulkIdentityVerificationArchitectureTest`) | **22 failing, 1 passing** | 13 × missing `verify-all` route · 5 × missing `admission.*` props · 2 × missing `identityVerification` display state · 2 × missing bulk-verification classes / controller delegation (`VerificationMethod`, the bulk handler/controller path). The 1 pass proves that entitlement ignores evidence `notes`/`method` |
| Vitest (`VoterAdmissionCard`, `IdentityVerificationCell`, `BulkVerifyDialog`, `AdmissionPendingNotice`, `VoterVerificationModal` specs) | **5 specs RED** | 4 × new component missing · 1 × existing `VoterVerificationModal` blocks submission without an IP (2 tests) |

- **No accidental RED:** all failures are expected C4 implementation gaps, not production regressions.
- Tests are **not** weakened to pass.
- C3 entitlement, authority, policy, suspension and verification-window semantics are **not** altered to satisfy RED.

**`VoterVerificationModal`**
- The existing component blocks submission while `form.verified_ip` is falsy (`:disabled="submitting || !form.verified_ip"`).
- Its default `voterCurrentIp` is `'—'`, which isn't an IPv4 address.
- **C4 requirement:** identity verification does not require IP capture. The test proves that submission works without an IP and sends `verified_ip: null`.
- IP capture and security semantics are otherwise **unchanged**. This only removes IP as a prerequisite for identity verification.

**Staged implementation order (each stage: smallest change for the failing tests; GREEN run before the next stage)**
1. ✅ RED baseline recorded (this section). **GREEN approved by the PO in backend-first slices.**
2. Domain/application bulk-verification types and handler (`VerificationMethod`, `BulkVerificationCountChanged`, `BulkRecordIdentityVerificationCommand`/`Handler`, port method `bulkVerifiableVoterIds`, `IdentityEvidenceRecord.method`).
3. Route/controller delegation (`elections.voters.verify-all`), **preserving both authorization layers** (route `manageSettings` + handler `VerificationAuthoritySource`) and the verification window. Transaction at the HTTP boundary; per-voter + summary audit.
4. Backend admission-management read model and props (`admission.*`, `identityVerification`).
5. Frontend components and the existing modal adjustment; wire them into the settings, voter-management and election pages.
6. GREEN tests incrementally after each stage.
7. The full C4 regression (worktree at `622ee91e9`, no overlapping test runs) **only after** focused GREEN is complete.

**Gates (PO):**
- **GREEN slice 1 = backend only** (stages 2–3: bulk types, candidate port, handler, audit batch correlation, atomic transaction, `expected_count` check, route/controller delegation). It stops after the focused PHPUnit run.
- **Frontend (stage 5) waits for manual approval** of the backend GREEN result.
- The six Stop-hook area-guide reminders remain a documentation follow-up outside this slice.

**Baselines recorded before C4 code:**
- Vitest: 8 failing tests in 12 files, 326 passing (pre-existing).
- `npm run design-check`: 92 violations (threshold 150).
- PHPUnit: the C3 77-suite result.

**Regression:** the same worktree method (at `622ee91e9`); no tests run while it runs. Vitest and design-check are compared before and after.

**Out of scope:**
- `Code.can_vote_now`, the Code workflow;
- `users.can_vote`/`is_voter`;
- PBDIGIT-214…219;
- the C2 dev-DB migration (separate confirmation);
- the area-guide follow-up;
- any change to entitlement, policy or suspension semantics.

**Files (expected):**
- **Backend:**
  - `ElectionVoterController` (index props + `verifyAll`);
  - routes (1);
  - `VerificationMethod`, `BulkVerificationCountChanged`;
  - bulk command, handler and result;
  - port + adapter method;
  - `IdentityEvidenceRecord`/store `method`.
- **Frontend:**
  - 5 new components;
  - `Settings/Index.vue`, `Voters/Index.vue`, `VoterVerificationModal.vue`, `Election/Show.vue`;
  - locale strings (en/de/np) where pages use i18n.
- **Tests and guide:** 9 test files; developer guide `04_c4_ui_and_bulk_verification.md`.

#### C4 correction (2026-10-04, after C4 GREEN, before commit) — PO-approved, implemented

**Finding:** the C4 "Verify Identity" reused `VoterVerificationModal` (last-login IP, device hash, notes) and wrote the same `voter_verifications` row as the legacy IP "Verify". So IP data looked like identity proof, an IP click admitted a voter, and an identity verification made the IP look verified (one row per election and voter).

| Slice | Result |
|---|---|
| **1 — voting-process characterization** (tests only) | `VotingProcessEntitlementBoundaryTest` 41/41, no production change. Every slug voting step × every non-entitled kind is refused (403, nothing written). Revocation after the code step refuses the agreement, ballot and final vote, while `Code.can_vote_now` stays true. An external CodeController review was answered with evidence: the controller skips its own check on slug requests because route middleware `ensure.election.voter` → `VotingEntitlementQuery` already ran; `isUserEligible()`'s `can_vote` branch is unreachable (non-slug routes are commented out). PO: no CodeController change; `can_vote_now` = "code step completed". The suspension-after-code sequence is unreachable in the product flow (suspension only before voting), so it is tested as an end state. |
| **2 — IdentityAttestationDialog** | New dialog: required attestation gates Confirm; sends `attested: true`; IP/device only in a collapsed "Technical details — audit only, not identity proof". |
| **A1 — identity only on the page** | PO: *"Identity Verification is the only verification concern in this increment"*. The legacy "Verify IP Address" UI entry point and the modal mount were removed (`VoterVerificationModal.vue` kept, retitled). The identity column, actions and name badge are shown only while the policy is ON. All identity UI state comes from `identityVerification` only, never from `verifications` (the prop stays, unused). |
| **A2 — `attested` contract** | `store()` requires `attested` (accepted). The audit adds `method` + `statement` (`RecordIdentityVerificationCommand::ATTESTATION_STATEMENT`). Fixture-only `attested: true` in 6 files (the audit test fails before its request, pre-existing; the non-admin test is refused before validation). |
| **Follow-ups** | `PBDIGIT-220` (left column removed, separate commit), `PBDIGIT-221` (assign members as voters), `PBDIGIT-222` (IP verification as its own legacy record; how it worked + PO decisions recorded). |

---

## 4. Tests (RED → GREEN per slice)

Fixtures: `ElectionScenarioFactory::votingActive()` / `setupAdministrationWithChief()` + `ElectionMembership::create` + `VoterSlug::factory()` (the `ElectionOnlyEntitlementPinTest:45-100` pattern). Never `$org->users()->attach()` for factory users, and never `assignVoter`.

| Slice | File | Cases |
|---|---|---|
| C1 | `tests/Unit/Contexts/Elections/VotingEntitlementTest.php` | Table: registered/role/status (active, inactive, invited, removed)/deleted/org/admitted/votable → exactly one reason each |
| C1 | `tests/Feature/Election/VotingEntitlementUnificationTest.php` | All 5 call sites agree; org mismatch denied at `slug.vote.store` (uncached, rollback); invited/removed/inactive denied; `show` reports them ineligible. `VotingEntitlementAmbientInvarianceTest` TE1–TE6 must stay green |
| C2 | `tests/Unit/Contexts/Elections/VoterAdmissionPolicyTest.php` | none → admitted; requires + no evidence → not admitted (missing listed); + evidence → admitted; unknown code rejected; round-trip normalised |
| C2 | `tests/Feature/Election/VoterAdmissionPolicySettingsTest.php` | Chief/deputy can change it in Draft/SetupAdministration/SetupNomination; rejected in ReadyForVoting/VotingActive/Suspended (app layer); model guard throws when locked; commissioner/outsider 403; audit recorded; impact counts for both transitions |
| C3 | `tests/Feature/Election/IdentityVerificationAdmissionTest.php` | Unverified denied at `slug.code.create`, `slug.vote.create`, `slug.vote.store`; verified allowed; **cache:** denied → verify → allowed at once; allowed → revoke → denied at once; policy flip invalidates; **regression:** empty policy = today; **isolation:** evidence in election A (org A) admits nothing in election B (org B) under any ambient session |
| C3 | `tests/Feature/Election/IdentityVerificationAuthorityTest.php` | Chief ✓ / deputy ✓ (`verifier_role` recorded); commissioner ✗; org-wide officer (`election_id NULL`) ✗; chief of another election ✗; non-registered target ✗; window ✓ SetupAdministration/SetupNomination/ReadyForVoting/VotingActive, ✗ Draft/Suspended/Counting/ResultsPublished |
| C3 | (same) | Suspension confirmed → evidence effect revoked (`revocation_reason`) → approve → still **not entitled** → re-verify → entitled |
| C3 | (same) | Bypass pins: approve / assign-by-UUID / re-import / stale cache cannot entitle an unverified voter |
| C1+C3 | `tests/Architecture/VotingEntitlementSinglePointTest.php` | No `where('status','active')` on election memberships in `app/Http` or `app/Traits`; no controller reads `voter_verifications` for decisions; new domain classes import no `Illuminate\` (GreenfieldCoreArchitectureTest style) |
| C4 | Inertia prop assertions (`admission`, `admissionPending`, `admissionImpact`) | — |

Run only `php artisan test --env=testing <files>`. Regression suites, with before/after counts:
- `VotingEntitlementAmbientInvarianceTest`
- `ConstitutionalVotingProtectionTest`
- `ElectionOnlyModeTest`
- `ElectionVoterManagementTest`
- `ElectionVoterSuspensionTest`
- `VoterImportElectionOnlyTest`
- `VoterVerificationEmitsEventsTest`
- `tests/Architecture`

---

## 5. Documented gaps — not in scope (become PBDIGIT-214+)
- G1 Immutable verification history (unique row; overwriting is not protocol history).
- G2 `ProtocolAppend` adapter / protocol-grade evidence.
- G3 One-actor reactivation paths (approve/suspend/assign-restore).
- G4 The `destroy` cross-election check; the `bulkAssignVoters` crash.
- G5 Repairing legacy fixtures A–D.
- G6 `voter_verification_mode` IP/device enforcement.
- G7 Email/phone/eID mechanisms and OR clauses.
- G8 Full-membership adoption.
- G9 Impact calculation beyond `none ↔ OIV`.
- G10 **Formalise the Election Committee leadership domain** (Election → Election Committee → Chief, Deputy Chief): its lifecycle and appointment rules, its relation to `election_officers` and to the acceptance Committee (EM-GOV-029). Then re-point `VerificationAuthoritySource` to it. Raised as its own backlog item and ADR follow-up.

## 6. Definition of Done (each slice)
- RED → GREEN, with outputs reported.
- Developer guide `developer_guide/election/voter_admission/` (`00_index.md` + one file per slice).
- Commit `…(PBDIGIT-213)` with the Co-Authored-By trailer.
- `.claude/CONTEXT-publicdigit.md` and `.claude/sessions/2026-10-04.md` updated.
- Manual Read→Edit only (no sed/regex rewrites).
- Session Completion Report at the end.
- Order: C0 → C1 → C2 → C3 → C4.
- **Stop and report** if implementation contradicts this plan.

## 7. Verification
1. New test files plus regression suites (`--env=testing`).
2. `npm run design-check` and `npm run build`.
3. Dev app with `onf-2-f8ba4321` (`setup_administration`):
   - enable the policy → impact "5 voters lose entitlement";
   - the manage page shows 5 × "verification required";
   - the chief verifies one → "Identity verified", and the audit entry carries the subject and role.

   `php artisan migrate` (additive) runs only after explicit confirmation; no destructive DB commands.

## Critical files
- `app/Models/User.php:316-345`
- `app/Traits/EnsuresVoterMembership.php`
- `app/Http/Controllers/ElectionVotingController.php`
- `app/Http/Controllers/Election/VoterVerificationController.php`
- `app/Http/Controllers/ElectionVoterController.php`
- `app/Http/Controllers/Election/ElectionSettingsController.php`
- `app/Http/Requests/Election/UpdateElectionSettingsRequest.php`
- `app/Http/Middleware/OperationCapabilityMapper.php`
- `app/Models/{Election,ElectionMembership,VoterVerification}.php`
- `routes/organisations.php:299-302`
- `app/Providers/AppServiceProvider.php`
- `resources/js/Pages/Elections/{Settings,Voters}/Index.vue`
- `resources/js/Components/Election/VoterVerificationModal.vue`
- `resources/js/Pages/Election/Show.vue`
- new: `app/Contexts/Elections/{Domain,Application,Infrastructure}/…` and 2 migrations
