# Investigation — Election-Only voter verification gates and the Election Constitution

**Date:** 2026-10-04 · **Type:** domain-discovery investigation (read-only) · **Authority:** none — evidence and recommendation only; no implementation authorised
**Scope:** Election-Only mode (`elections.voter_source_strategy = 'election_only'`) · reference election `onf-2-f8ba4321` (Namaste Nepal GmbH)
**Method:** code trace (routes → middleware → controllers → services → models → migrations), read-only dev-DB inspection (anonymised), test runs with `--env=testing` only. No production code, migration, test or data was changed.

> **Notation.** **FACT** = verified in code/DB/test run, with citation. **INFERENCE** = derived, not directly observed. **RECOMMENDATION** = architectural opinion. Line numbers are as of commit `9254cb410`.

---

## 1. Executive Summary

- **FACT — Import = activation.** Election-Only import writes `ElectionMembership{role:'voter', status:'active'}` immediately (`app/Services/VoterImportService.php:302-312`). There is no registered-but-not-admitted state in use.
- **FACT — "Verify" is an officer attestation that is never enforced.** It upserts a `voter_verifications` row (IP / device binding + notes, `VoterVerificationController.php:20-92`). Nothing in the voting path reads `voter_verifications` or `elections.voter_verification_mode`. `Election::requiresVoterVerification()/checksIp()/checksFingerprint()` have **zero callers**, and the enforcement tests are `markTestSkipped` (`tests/Feature/Election/VoterVerificationTest.php:177-243`).
- **FACT — An imported, never-verified voter can vote** once the election is in `VotingActive`. The decisive gate is `User::isVoterInElection()` (`app/Models/User.php:316-337`): membership exists ∧ `role='voter'` ∧ `status='active'` ∧ the organisation matches ∧ the row is not soft-deleted.
- **FACT — `ElectionMembership.status` conflates registration, admission and exercisability.** `active` means "registered", "admitted" and "may vote" all at once. `inactive` is written by both the confirmed two-person suspension (`ElectionMembership.php:219`) and the one-actor `suspend` action. `invited` exists in the enum but no Election-Only path writes it.
- **FACT — The business question is already registered as open.** `EM-OPEN-001` (Election Manifesto §9): *"after Election-Only admission, is the `ElectionMembership` immediately `ACTIVE`, or initially `INVITED` requiring explicit Election Chief approval?"* It is marked 🔴 for "the Election-Only ADMISSION slice". The adopted rules `EM-ENT-003/005/007` already use the word **admission**.
- **FACT — A standing ruling restricts where this rule may live.** The Principal Architect ruling of 2026-08-13 (Manifesto §"Manifesto ≠ Constitution") says: *"Entitlement, ElectionMembership, suspension, credentials and voter-participation rules must remain OUTSIDE `ElectionConstitution` unless a future architectural decision explicitly establishes otherwise."*
- **FACT — The per-election rule set is flat columns on `elections`.** Only 7 security articles are snapshotted and hashed at creation (`Election.php:2328-2347`). Neither `voter_source_strategy` nor `voter_verification_mode` is snapshotted or immutability-guarded. `voter_verification_mode` can be changed during voting.
- **FACT — There are several backend paths that reactivate a voter.** `approve` (one actor, no `has_voted`/`removed` check) and `AssignVoterHandler` (restores removed/soft-deleted/inactive → `active`) both bypass the two-person suspension.
- **ANSWER (§19): YES — but a domain-model extension is required**, and it needs an architecture decision (ADR) first. The extension is an election-owned, creation-time-fixed **admission requirement**, enforced at the Election-context voting-entitlement boundary. It must not go into `ElectionConstitution::RULES`, unless an ADR explicitly overturns the 2026-08-13 ruling.
- **RECOMMENDATION: ADOPT** the separation Import ≠ Admission ≠ Exercisability, with the **smallest** model: one per-election requirement value plus the existing `VoterVerification` record as gate evidence, and no generic gate engine. Sequence: decide `EM-OPEN-001` → ADR → RED → GREEN.

---

## 2. Current Election-Only Voter Lifecycle

**FACT**, derived from code. Implicit transitions are marked ⚠.

```mermaid
stateDiagram-v2
    [*] --> Active : import (VoterImportService::importElectionOnly) ⚠ implicit admission
    [*] --> Active : assign single / bulk (AssignVoterHandler / BulkAssignVotersHandler)

    state Active {
        [*] --> NoSuspension
        NoSuspension --> SuspensionProposed : propose-suspension (actor 1)
        SuspensionProposed --> NoSuspension : cancel-proposal (proposer or admin)
    }

    Active --> Inactive : confirm-suspension (actor 2 ≠ actor 1, by NAME)
    Active --> Inactive : suspend ⚠ one-actor endpoint, no UI button
    Active --> Removed : destroy (status='removed', not soft-deleted)

    Inactive --> Active : approve ⚠ one actor undoes a two-person suspension
    Removed --> Active : approve ⚠ backend allows; UI hides
    Inactive --> Active : assign by UUID ⚠ AssignVoterHandler restoreAndUpdate
    Removed --> Active : assign by UUID ⚠ AssignVoterHandler restoreAndUpdate

    Active --> Active : Verify / Re-verify ⚠ writes voter_verifications only; no state change
    Active --> Voted : cast vote (codes.has_voted / voter_slugs.has_voted) ⚠ ElectionMembership untouched
    Voted --> [*]
```

Observations (**FACT**):
- **Verify is orthogonal to the lifecycle.** It changes no `ElectionMembership` field.
- **"Voted" is not a membership state.** `ElectionMembership::markAsVoted()` (`ElectionMembership.php:164`) has **no callers**, so `election_memberships.has_voted` is never set by voting. The manage page's post-vote lock (`Index.vue:304,318`, `v-if="!membership.has_voted"`) therefore **never engages**. Double voting is blocked by `codes.has_voted` and `voter_slugs` instead (`VoteController.php:1651-1664, 1795`).
- **`SuspensionProposed` voters can still vote.** `status` stays `active` while `suspension_status='proposed'`, and the voting path never reads `suspension_status`.
- **`expires_at` is not checked at vote time.** `ElectionMembership::isEligible()` (:158) has no callers. `scopeEligible()` (:126) additionally requires a `members` row, so it would exclude every Election-Only voter.

---

## 3. Import Flow

```
Import UI      resources/js/Pages/Elections/Voters/Import.vue
   ↓  POST /elections/{election}/voters/import/preview  → JSON preview
   ↓  POST /elections/{election}/voters/import          (confirmed=accepted)
Route          routes/organisations.php:261-267  middleware election.state:import_voters
               (OperationCapabilityMapper.php:38 → snapshot.canManageVoters)
Controller     app/Http/Controllers/Election/VoterImportController.php
               preview() :94   import() :118
Authorization  $this->authorize('manageVoters')  → ElectionPolicy.php:91-98
               (active ElectionOfficer, role chief|deputy, of THIS election)
State guard    assertAdministrationSetupState() :179  → 403 unless state = setup_administration
Validation     file: required|mimes:xlsx,xls,csv,txt|max:10240 ; confirmed: accepted   (:124-127)
Capacity       $election->assertCanAcceptVoters(1) (:131) ⚠ checks 1 only; the comment says the
               service enforces the real count — it does not (no call in VoterImportService)
Strategy       VoterSourceStrategy::fromElection($election)->isImportedVoterRegistry() (:140)
Service        VoterImportService::importElectionOnly()  app/Services/VoterImportService.php:237-345
   ↓ per row (no transaction around the row, exceptions collected as row errors)
User           User::firstOrCreate(['email'], [name, random password, organisation_id,
               email_verified_at => now()])                                    :266-276
Org links      UserOrganisationRole::firstOrCreate(role 'member')               :282-288
               OrganisationUser::firstOrCreate(status 'active')                 :291-297
Membership     ElectionMembership::firstOrCreate([election_id,user_id],
               [organisation_id, role 'voter', status 'active'])                :302-312
               ⚠ assigned_by / assigned_at NOT set (DB: all 5 rows on onf-2 have both null)
Invitation     new users only → VoterInvitation::firstOrCreate + SendVoterInvitation job :315-332
               (VoterInvitationController::setPassword only sets password/email_verified_at;
                it does NOT touch the membership — VoterInvitationController.php:35-66)
Audit          none — no ElectionAuditService call, no voter_audit log, no domain event
               (contrast AssignVoterHandler, which logs to voter_audit)
   ↓
Current status ElectionMembership.status = 'active'
   ↓
Can vote?      YES, as soon as the election reaches VotingActive (§5)
```

**Re-import / duplicate (FACT):** `firstOrCreate` never updates an existing row. Re-importing a `removed` or `inactive` voter leaves the status unchanged, so import does not reactivate. **Unverified INFERENCE:** a *soft-deleted* membership is invisible to `firstOrCreate` (SoftDeletes scope) and would attempt an insert against the `(election_id,user_id)` unique index. That row would then surface as a row error.

**Full-membership import path (outside scope, shared controller) — FACT defect:** `VoterImportService::import()` calls `ElectionMembership::bulkAssignVoters(...)` (`VoterImportService.php:134`), and that method no longer exists on the model. That path fatals. `CsvVoterImportTest::admin_can_import_voters_via_csv` (:77) only asserts a redirect, so it does not catch this.

---

## 4. Current Verify Flow

```
Vue button     resources/js/Pages/Elections/Voters/Index.vue:320-330  (shown for every voter not has_voted)
               @click="openVerificationModal(membership)" — prefills verified_ip from users.current_ip
Modal          resources/js/Components/Election/VoterVerificationModal.vue:239-248
               router.post(route('elections.voters.verify'), {user_id, verified_ip,
               verified_device_fingerprint_hash, notes});  submit disabled unless verified_ip set (:124)
Route          POST /elections/{election}/voters/verify    routes/organisations.php:300
               DELETE /elections/{election}/voters/{verification}/revoke  :301  (⚠ no UI calls it)
               middleware election.state:verify_vote → canVote || !isLocked  (OperationCapabilityMapper.php:45)
Controller     app/Http/Controllers/Election/VoterVerificationController.php  store() :20  revoke() :97
Authorization  authorize('manageSettings') → ElectionPolicy.php:46-53 (chief|deputy of THIS election)
Validation     user_id: required|uuid|exists:users,id ⚠ NOT checked to be a voter of this election (:29-35)
               verified_ip nullable|ipv4 (server-side optional; UI makes it mandatory)
Persistence    voter_verifications (migration 2026_04_12_000003; unique election_id+user_id)
               existing row → update in place: verified_by, verified_at, status 'active', revoked_* null
               no row       → create
Event          IdentityAttestedEvent / VerificationRevokedEvent (App\Contexts\Trust) — no listeners found
Audit          ElectionAuditService::log(event 'voter_verified', category 'committee') — file-based;
               records the OFFICER and the IP/fingerprint, ⚠ NOT the verified voter's user_id
ElectionMembership   unchanged
```

**A. What does Verify change?** **FACT:** only `voter_verifications`, setting `status='active'`, the binding data (`verified_ip`, `verified_device_fingerprint_hash`, `verified_device_components`), `notes`, `verified_by` and `verified_at`. It does not set any `identity_verified` flag, and it does not change `ElectionMembership.status`.

**B. Does verification affect voting eligibility?** **FACT: No.** No middleware, controller, trait or service in the voting path reads `voter_verifications` or `voter_verification_mode`:
- The vote-time IP check uses `users.voting_ip` (`ValidateVotingIp`, `VoteController::store` :1667), not `voter_verifications.verified_ip`.
- `TrustPolicyEvaluator::buildAttestationRecord` (`TrustPolicyEvaluator.php:168-169`) reads a non-existent attribute `voter_verification_required` (always false) and hard-codes `attested=false`.
- `VoterVerificationPolicy` (`app/Application/Election/VoterVerification/Policies/VoterVerificationPolicy.php`) is registered nowhere.

**C. Can an imported Election-Only voter vote before verification?** **FACT: Yes.** The test agent confirmed this with an edited scratch copy of a test (not committed): an unverified voter on an election with `voter_verification_mode='ip_only'` got **200** at `slug.code.create`.

**D. What happens if verification is reversed?**
- **FACT:** `revoke` sets `status='revoked'`, `revoked_by` and `revoked_at`. Voting is unaffected, and no UI exposes revoke.
- **FACT:** Re-verify overwrites the single row in place. No history of earlier attestations is kept, apart from the file audit log, and that log omits the subject voter.

---

## 5. Current Voting Eligibility Flow

**FACT** (`routes/election/electionRoutes.php`, `bootstrap/app.php:85-103`).

```
Entry          elections.start → ElectionVotingController@start :104
               blocks: no membership | role≠voter | status='removed' (:117) | membership.has_voted (:122, never true)
               ⚠ 'invited' / 'inactive' get a voter slug here, then are stopped below
Step 1 group   (:490) voter.slug.verify · voter.slug.consistency · ensure.election.voter ·
               vote.eligibility · vote.organisation
Steps 2-5      (:507) + voter.slug.window · voter.step.order · validate.voting.ip · throttle:10,1
Steps 3-5      (:517) + voting.active (EnsureVotingActive → ElectionLifecycle::canVote())

THE MEMBERSHIP GATE — EnsureElectionVoter.php:37 → User::isVoterInElection() (User.php:316-337)
    ElectionMembership where election_id ∧ role='voter' ∧ status='active'
      ∧ organisation_id = election.organisation_id ∧ deleted_at IS NULL   (cached 300 s, invalidated on save/delete)
Re-check       EnsuresVoterMembership trait (app/Traits/EnsuresVoterMembership.php:23-60) in
               VoteController::store :1586 (uncached, inside the vote transaction)
Election gate  ElectionLifecycle::canVote() — true only in VotingActive (ElectionLifecycleEngineImpl.php:259)
Trust gate     VoteController::store :1520-1555 — LegitimacyOutcome::Allowed required (attestation always "not required")
Double vote    codes.has_voted under lock (:1651-1664) · voter_slugs.has_voted/status (:1795)
```

**NOT checked anywhere at vote time (FACT):**
- `voter_verifications` and `voter_verification_mode`
- `suspension_status`. A `proposed` suspension still votes; a `confirmed` one is blocked only because it sets `status='inactive'`.
- `ElectionMembership.expires_at`
- `ElectionMembership.has_voted`

**Placement of a future invariant (FACT plus RECOMMENDATION):** there is no `CanCastVote` concept today. The existing single choke point that runs at every step, and uncached inside the vote transaction, is the pair `User::isVoterInElection()` / `EnsuresVoterMembership`. Both already encode the adopted ruling `EM-ENT-001` + PBDIGIT-65/69: *"voting-time entitlement is owned by the Election context; scope derives from the election"*. That pair is the correct place to enforce admission. They are infrastructure-level helpers today; extracting a named Election-context query (`VotingEntitlement`) is a design question for the ADR.

---

## 6. Election Constitution Analysis

"Election Constitution" names **three different things** in this codebase. The proposal must say which one it means.

| # | Artifact | What it is | Per-election? | Immutable? |
|---|---|---|---|---|
| 1 | `App\Domain\Election\Constitution\ElectionConstitution::RULES` (`ElectionConstitution.php:23-156`) | Static registry of **lifecycle workflow rules** (states, roles, preconditions per action). ADR-001 home. | No — code | Yes (code) |
| 2 | `ConstitutionalArticlesSnapshot` → `elections.security_articles_snapshot` + `constitutional_hash` (`Election.php:2328-2347`, guard `:2267-2284`) | Creation-time snapshot of **7 security articles**: network_binding_strategy, max_votes_per_ip, device_binding_strategy, ballot_authorization_protocol, trust_overlay_active/priority/reason | Yes | Snapshot columns guarded. ⚠ The live source columns are still mutable and never re-checked against the hash |
| 3 | `Security\Simplified\ElectionConstitutionSnapshot` (5 fields **incl. `bool $verificationRequired`**) + `ElectionConstitutionHasher` + `VerificationPolicy` ("Article 6") | A parallel, **unwired** model | — | `new ElectionConstitutionSnapshot`, the hasher and the validator have **no callers in app/** |

The remaining per-election rules are flat `elections` columns:
- `voter_source_strategy`: set from `org.uses_full_membership` at creation (`ElectionManagementController.php:163`). It is documented as IMMUTABLE (`VoterSourceStrategy.php:30-32`), but it is in `$fillable` (`Election.php:116`) with no guard, and `BackfillVoterSourceStrategy.php:59` updates it.
- `voter_verification_mode` (`none|ip_only|fingerprint_only|both`): settable by chief or deputy via PATCH `/settings` at any time, including during voting (`ElectionSettingsController.php:104-109` only asks for confirmation).
- The IP-restriction columns, `selection_constraint_*` and `no_vote_option_*`.

Env-level settings: `TWO_CODES_SYSTEM` (`config/voting.php`) is not linked to the per-election `ballot_authorization_protocol`. `SELECT_ALL_REQUIRED` is only a fallback.

**Answers to the brief's §9 questions (FACT):**
1. Rules live in artifact 1 (workflow) and in `elections` columns (per-election), with a partial snapshot (artifact 2).
2. The `elections` columns are configurable per election; `ElectionConstitution::RULES` and two env flags are hard-coded.
3. Only the snapshot artifact (2) is immutable after creation.
4. The 7 articles are snapshotted; nothing else is.
5. Eligibility is *not* in the snapshot. It lives in `voter_source_strategy`, which is not snapshotted.
6. A verification requirement **could** technically be carried by artifact 2, and artifact 3 already models one. **But the 2026-08-13 ruling forbids placing voter-participation rules in `ElectionConstitution`** without a new architectural decision. That ruling names artifact 1 explicitly. Whether artifact 2 ("security articles") counts as the "ElectionConstitution" in that ruling is **an open interpretation question** (§15, Q2).

---

## 7. Domain Model Assessment

**Existing concepts (FACT):**

| Kind | Concept | Where | Note |
|---|---|---|---|
| Aggregate (de facto) | `ElectionMembership` — *"the election-specific entitlement record"* (`EM-ENT-001`) | `app/Models/ElectionMembership.php` | Anaemic Eloquent model; transitions spread across 3 controllers + 2 handlers + service |
| Entity / record | `VoterVerification` — officer **identity attestation** (ADR-002 "Verified") with optional IP/device binding | `app/Models/VoterVerification.php` | Emits `IdentityAttestedEvent` (Trust context) — semantically the ADR-002 *Verified* fact |
| Value object | `VoterSourceStrategy` (route to admission: `election_only` / `full_membership`) | `app/Domain/Election/Enum/VoterSourceStrategy.php` | `EM-ENT-005`: one entitlement concept, only the *route to admission* differs |
| Domain policy | `ElectionOnlyPolicy::decideForContext` (active ∧ ¬deleted) | `app/Contexts/Elections/Domain/Policies/ElectionOnlyPolicy.php` | **Registration**-time eligibility (who may be assigned), not vote-time |
| Infra query | `EloquentVoterEligibilityQueryService` (bound to `VoterEligibilityPolicy`) | `AppServiceProvider.php:140-143` | used by assign / import |
| Application handlers | `AssignVoterHandler`, `BulkAssignVotersHandler` | `app/Contexts/Elections/Application/Handlers/` | Import does **not** use them |
| Domain service (implicit) | voting entitlement = `User::isVoterInElection` + `EnsuresVoterMembership` | `User.php:316`, `app/Traits/` | The real vote-time invariant; not named as a domain concept |
| Lifecycle | `ElectionLifecycle::canVote()` | `ElectionLifecycleEngineImpl.php:259` | Election-level exercisability |

**Invariants that hold today (FACT):**
- No `ElectionMembership` → no vote.
- A non-`active` status → no vote.
- An org-mismatched membership → no vote.
- An election outside VotingActive → no vote.
- One ballot per code and per slug.

**Missing concepts (FACT: absent · RECOMMENDATION: needed):**
- **Registration ≠ Admission.** The adopted rules talk about *admission* (`EM-ENT-003`, `EM-ENT-005`, `EM-ENT-007`: "subject to the mode's admission prerequisite"). The code has no *admission prerequisite* concept; import performs admission implicitly.
- **An election-owned admission requirement.** Nothing records, per election, whether admission needs officer verification.
- **Exercisability as a distinct dimension** (`EM-ENT-002`, `EM-OPEN-003`). `status` currently carries it together with registration and admission. `inactive` is written by both suspension paths, and `EM-OPEN-007` (distinguishing suspended from voted at the gate) is unresolved.

**Model-integrity check (protocol rule):** "verification required" is a **new dimension** (an admission requirement). It is *not* another value of `voter_verification_mode`. That column is a *binding* dimension (which network or device the vote must come from), and overloading it would merge two orthogonal concepts. The new dimension is:
- **orthogonal** to binding: a voter can be verified with no binding;
- **necessary**: there is no other way to state the rule;
- **sufficient** for the first gate.

---

## 8. Gate Model Options

| Option | Shape | Fit with Publicdigit | Verdict |
|---|---|---|---|
| **A — boolean on membership** (`ElectionMembership.verified`) | One flag | Duplicates `voter_verifications` and loses who/when/revocation (breaks `EM-GOV-005` protocol-recording intent). The flag and the record can disagree | ❌ reject |
| **B — explicit status lifecycle** (`imported → pending_verification → verified → …`) | Extend the `status` enum | `status` already conflates three meanings (§7). Adding gate states multiplies them (each gate × suspended × removed). `invited` collides with `VoterInvitation` (email invite). `EM-OPEN-003` (where exercisability lives) is still undecided | ⚠ reject as the *primary* mechanism. A single "registered-not-admitted" value may still be the answer to `EM-OPEN-001` |
| **C — constitution-defined requirement** (`election: voter_admission_requirement`) | Per-election, creation-time-fixed rule | Matches `EM-GOV-001` (the election governs exercisability) and the existing snapshot pattern. **Placement is constrained by the 2026-08-13 ruling**: an election-owned rule, not `ElectionConstitution::RULES` | ✅ **adopt for the *requirement*** |
| **D — separate gate records** (`VoterGate{type,status,required,verified_by,…}`) | Generic gate table | A generic workflow for a single justified gate. `voter_verifications` already *is* the gate record for the one gate that exists (who, when, status, revocation) | ❌ reject now. Reconsider only when a 2nd gate is ratified (likely `EM-OPEN-001` manual approval) |

---

## 9. Recommended Model

**RECOMMENDATION:** a minimal hybrid of **C + the existing record**, with no new aggregate, no gate table and no workflow engine.

```
ELECTION  (Election context — owns voting-time entitlement: EM-ENT-001/007, ADR-002 clarification (i))
  │
  ├── voter_source_strategy            (existing)  route to admission: election_only | full_membership
  │
  └── voter_admission_requirement      (NEW, fixed at creation, snapshotted/hashed)
            none                       → registration = admission (today's behaviour)
            officer_verification       → admission requires an ACTIVE VoterVerification
                                          for (election, user)
                   │
                   ▼
ELECTION MEMBERSHIP  (registration; status = registered/withdrawn/suspended — unchanged)
                   │
                   ├── gate evidence: VoterVerification (existing; status active|revoked;
                   │                   binding fields optional — ADR-002 "Verified")
                   ▼
VOTING ENTITLEMENT (named Election-context query, today User::isVoterInElection)
   CanCastVote(user, election) =
         membership(election,user) ∧ role=voter ∧ status=active ∧ org = election.org ∧ ¬deleted
       ∧ ( requirement = none  ∨  ∃ active VoterVerification(election,user) )      ← NEW
       ∧ ElectionLifecycle::canVote()                                               (existing)
```

Why this is the smallest model:
- It adds **one** per-election value and **one** conjunct at an existing choke point.
- **Admission is derived, not stored.** There is no second source of truth to drift, and revocation un-admits automatically.
- `voter_verification_mode` stays a binding rule. Enforcing it (comparing `verified_ip`/fingerprint) is a **separate** follow-up and must not be merged into this decision.
- A second gate (manual Chief approval, `EM-OPEN-001`) would extend the enum value set, or become a conjunct over an existing record. Only at **three or more** ratified gates would Option D be justified.

**Alternative to decide in the ADR:** derive admission (above) versus record an explicit admission act (`admitted_at`/`admitted_by` on the membership). Derivation is smaller. An explicit record gives a stable, protocol-visible admission event (`EM-GOV-005`). Both are compatible with the requirement value.

---

## 10. Required Invariants

Proposed wording only; the business owner must adopt it (Manifesto style).

1. **INV-ADM-1** — Registering (importing or assigning) a person into an Election-Only election creates an election-specific entitlement record. *When the election's admission requirement is `officer_verification`*, that registration **is not** admission.
2. **INV-ADM-2** — The admission requirement is set when the election is created, belongs to the election, and does not change after the administration phase. *(Whether it may change during `setup_administration` is Q5.)*
3. **INV-ADM-3** — A registered but not admitted voter cannot obtain a voting code, cannot reach the ballot, and cannot have a vote stored.
4. **INV-ADM-4** — Only an active Chief or Deputy Election Officer **of that election** may pass the admission gate (create an active verification). Only a verification for a person **registered in that election** counts.
5. **INV-ADM-5** — Revoking a verification withdraws admission with effect from the next entitlement check. Votes already cast are unaffected.
6. **INV-ADM-6** — Every admission act and every revocation is recorded in the election protocol, identifying the subject voter and the acting officer (`EM-GOV-005`). Anonymity is preserved: the record is about entitlement only and never references a vote (ADR-T11).
7. **INV-ADM-7** — The backend enforces admission at the Election-context entitlement boundary. Hiding UI elements is never the enforcement.
8. **INV-ADM-8** — No officer action other than the admission gate itself (approve, re-assign, re-import) may make a non-admitted voter able to vote.

---

## 11. Security / Bypass Analysis

All items below are **FACT** unless marked otherwise.

| # | Bypass / gap | Evidence | Relevance to the gate |
|---|---|---|---|
| S1 | **Verification is never enforced.** Every imported voter can vote | §4B, §5 | the core gap |
| S2 | `voter_verification_mode` is mutable at any time, even during voting, and not snapshotted | `ElectionSettingsController.php:104-109` | the rule itself is not trustworthy as a constitutional fact |
| S3 | `approve` reactivates `inactive` (a confirmed two-person suspension) and `removed` memberships with one actor. There is no `has_voted`/`removed` check; the UI only hides the button | `ElectionVoterController.php:198-216` | would bypass INV-ADM-8 if approve ever means admission |
| S4 | Assign-by-UUID → `AssignVoterHandler` restores soft-deleted, `inactive` or `removed` → `active` | `AssignVoterHandler.php:66-81` | same as S3 |
| S5 | One-actor `POST …/suspend` endpoint still live (no UI caller) | `routes/organisations.php:277`, `ElectionVoterController.php:222-242` | bypasses the two-person rule (`EM-OPEN-002` undecided) |
| S6 | Verify `store` accepts any `user_id` that exists in `users`, including people not registered in the election or from other organisations | `VoterVerificationController.php:29-35` | would let an officer "admit" someone not registered (harmless only while registration is also required) |
| S7 | `destroy` lacks the `membership.election_id === election.id` check (same-org cross-election removal) | `ElectionVoterController.php:166-182` | integrity |
| S8 | `suspension_status='proposed'` voters can vote | §5 | by design? (`EM-OPEN-006/007`) |
| S9 | `expires_at` not enforced at vote time | §5 | integrity |
| S10 | `isVoterInElection` result cached 300 s. Invalidation is wired to `ElectionMembership` save/delete only | `ElectionMembership.php:255-259` | **a future gate must also invalidate on `VoterVerification` create/revoke**, or rely on the uncached re-check in `store` |
| S11 | *INFERENCE (subagent):* `session('selected_election_type')==='demo'` short-circuits `VoteEligibility` / `CodeController::isUserEligible` | `VoteEligibility.php:36-39`, `CodeController.php:686` | membership is still enforced by `EnsureElectionVoter`, so this is not an admission bypass |
| S12 | Import does not call capacity enforcement with the real row count (comment says it does) | `VoterImportController.php:129-135` | integrity, not admission |
| S13 | Post-vote lock on the manage page never engages (`markAsVoted` has no callers), so officers can suspend or remove voters who have voted | §2 | integrity |
| S14 | Import records no `assigned_by`/`assigned_at` and writes no audit entry | DB (5/5 rows null), §3 | the registration act is not protocol-recorded |

**Paths checked conceptually against "Import ≠ Admission":**
- Direct API, alternate controller and frontend manipulation all converge on `isVoterInElection` and the uncached `store` re-check. **Enforcing at that pair closes them all.**
- Bulk assign and re-import only produce *registration*, so under the recommended model they cannot admit.
- Status manipulation (S3/S4) cannot admit either, because admission is derived from `VoterVerification` and not from `status`. This is the main argument for deriving admission rather than storing it as a status value.

---

## 12. Test Coverage

The test agent ran 22 files with `--env=testing`. It also ran edited copies of 4 files from the scratchpad, with only the setup code fixed, to classify failures. No repository test was changed.

| Business rule | Existing test | Result | Missing |
|---|---|---|---|
| Import creates voter | `tests/Feature/Voter/VoterImportElectionOnlyTest.php:195` (asserts `status='active'`) | **PASS** | No test of the registration audit; none for soft-deleted re-import |
| Imported voter cannot vote before verification | — | **MISSING** | Behaviour absent (edited-copy test: 200 at `code.create`) |
| Verify admits voter | `VoterVerificationTest.php:64,82,101` (save/revoke only) | **FAIL** — fixture (cluster C) | No test links verification to entitlement |
| Re-verification works | `VoterVerificationTest.php:125` | **FAIL** — fixture; passes with the setup fixed | No re-verify-after-revoke; no history |
| Suspended voter cannot vote | `ElectionOnlyEntitlementPinTest.php:154,172,192`; workflow in `ElectionVoterSuspensionTest` (8/8 PASS) | **FAIL** — fixture (cluster A); passes with the setup fixed | No vote-store-level test |
| Removed voter cannot vote | `VotingMembershipIntegrationTest.php:227` | **FAIL** — fixture (A+B); passes with the setup fixed | Runs on a draft election (may pass for the wrong reason) |
| Invited voter cannot vote | — | **MISSING** | Behaviour holds (edited copy: 403) but is not pinned |
| Verification authorization | `VoterVerificationTest.php:150` | **PASS (vacuous)** — every request 403s | No positive chief/deputy test; no negative "other officer" test |
| Election boundary | `ElectionOnlyEntitlementPinTest.php:128` (FAIL, fixture); `ElectionMembershipTest.php:214` (PASS); `ElectionVoterManagementTest` cross-election approve (PASS) | mixed | Verify of a non-member (S6); destroy cross-election (S7) |
| Constitution controls admission | `ConstitutionalVotingProtectionTest` 12/12, `VoterStrategySnapshotTest` 5/5, `ElectionOnlyPolicyDecisionTest` 7/7 | **PASS** — but these cover *source strategy*, not admission | Admission requirement doesn't exist |
| Vote-time IP/device check per `voter_verification_mode` | `VoterVerificationTest.php:181-243` | **SKIPPED** ("not yet implemented") | Production gap |

**Fixture failures (not production defects):**
- **A — duplicate `user_organisation_roles`.** `UserFactory::configure()` already inserts the role (commit `34994a8d6`), and the tests insert it again via `$org->users()->attach()` or `UserOrganisationRole::create`. Affected: EntitlementPin 0/6, EnsureElectionVoter 0/7, VotingMembershipIntegration 0/6, ElectionMembershipSuspension 0/7, VoterVerificationControllerAudit 0/2.
- **B — stale `ElectionMembership::assignVoter()/bulkAssignVoters()` calls.**
- **C — wrong authority in the verification tests.** They set an `ElectionMembership` `role:'admin'` where the policy needs an `ElectionOfficer` chief or deputy.
- **D — factory default state.** The default is `draft`, `full_membership`.
- With A–D fixed in the scratch copies: 19/19 entitlement, middleware and integration tests pass, plus all non-skipped VoterVerification tests.

**Production defects surfaced by the tests:**
- `VoterImportService.php:134` → `bulkAssignVoters` no longer exists, so the full-membership import crashes.
- Verification is never enforced (S1).
- Verify accepts non-members (S6).

**Not triaged:** `ElectionMembershipTest` (8 failures, scope counts and cache), `VoterEligibilityTest` :332/:346/:607 (full mode), `CodeControllerTest` 6/12.

---

## 13. Recommended Implementation Boundary

**Not authorised.** These are the layers an approved slice would touch, in order.

1. **Governance (before any code):**
   - Decide `EM-OPEN-001`.
   - Decide placement (Q2).
   - ADR: *"Election-Only admission requirement"* (owner: Election context; dimension `voter_admission_requirement`; derived vs recorded admission; relation to ADR-002 *Verified*; supersession of the 2026-08-13 ruling only if artifact 2 is chosen).
   - Manifesto rule(s) INV-ADM-1…8 proposed for adoption.
2. **Domain:**
   - A value object `VoterAdmissionRequirement` (`none | officer_verification`), mirroring `VoterSourceStrategy` (`fromElection`, persistence boundary).
   - A pure predicate `isAdmitted(requirement, hasActiveVerification)`.
3. **Persistence:**
   - Migration: `elections.voter_admission_requirement` (default `none`).
   - Include it in the creation snapshot and hash (version bump), or give it its own immutability guard, per the ADR.
   - Index `voter_verifications(election_id,user_id,status)` (the unique key already exists).
4. **Application / entitlement boundary:**
   - `User::isVoterInElection()` and `EnsuresVoterMembership` gain the admission conjunct.
   - Cache invalidation on `VoterVerification` saved/deleted (S10).
   - Optionally extract a named `VotingEntitlement` query in the Election context.
5. **Verify command:**
   - Require the target to be a voter of this election (S6).
   - Audit the subject voter (INV-ADM-6).
   - Make the binding fields optional in the UI when the purpose is admission.
6. **Election creation / settings UI:** choose the requirement at creation and show it read-only afterwards.
7. **Manage page:**
   - Show an "Admitted / Awaiting verification" column (derived).
   - Expose revoke.
   - Keep the S3/S4/S5 fixes as **separate backlog items**. They are not part of this slice.
8. **Out of scope for this slice:**
   - Enforcing `voter_verification_mode` IP/device binding.
   - The full-membership mode.
   - The `bulkAssignVoters` crash.
   - Fixture repairs. These come first as their own story, so the RED tests stand on a working harness.

---

## 14. Migration / Backward Compatibility

- **Existing elections get `voter_admission_requirement = none`.** Behaviour is identical to today: registered = admitted. No existing voter loses the ability to vote. `onf-2-f8ba4321`: 5 voters, all `active`, 0 verifications → unchanged under `none`.
- **New elections created with `officer_verification`:** imported voters are *registered, not admitted* until verified. No back-fill is needed.
- **Existing imported voters + a requirement switched on later** (the case in the brief). The recommendation follows INV-ADM-2: this is allowed only while the election is in `setup_administration` (Q5). Existing registered voters then become **not admitted** until verified. There is no grandfathering, because grandfathering would mean "imported before the rule" acts as an implicit admission (exactly the conflation being removed).
  - For `onf-2`, switching on would mean 0 of 5 voters could vote until verified.
  - The UI must show this impact before the switch is confirmed.
- **Once the election has left `setup_administration`:** no change is permitted (the constitution is fixed).
- **Verifications created before the rule existed** (any election) count as admission evidence if their status is `active`. There are none on `onf-2`.
- **Data:** an additive column only. No rewrite of `election_memberships`. No change to `votes`/`results` (anonymity untouched).

---

## 15. Open Domain Questions (product / governance decisions)

1. **`EM-OPEN-001`** — after Election-Only import, is the membership immediately exercisable, or does it need an explicit act? If an explicit act is needed, is that act *officer identity verification* (this report), *Chief approval*, or both?
2. **Placement** — does the 2026-08-13 ruling ("voter-participation rules outside `ElectionConstitution`") cover the per-election security-articles snapshot (artifact 2), or only `ElectionConstitution::RULES` (artifact 1)? This decides whether the requirement joins the hashed snapshot or gets its own election-owned guard.
3. Is the admission requirement **per election** (chosen at creation) or **per organisation default**, copied into the election like `voter_source_strategy`?
4. Does "verified" for admission mean **identity attested** (ADR-002 *Verified*) only, or must it also capture an IP/device binding? The UI currently forces an IP.
5. May the requirement be changed during `setup_administration`, and with what protocol record?
6. Does revocation after voting opens remove the right to vote (INV-ADM-5), and must it be one-actor or two-actor (compare `EM-OPEN-002`)?
7. Who may verify: chief and deputy only (today), or also other officer roles (for example a "commissioner")? Is one officer enough?
8. Should `suspension_status='proposed'` block voting (`EM-OPEN-006/007`)? This is adjacent, not part of this slice.
9. Does Full-Membership mode ever need the same gate? `EM-ENT-005` says there is one entitlement concept and only the route to admission differs, which suggests the requirement value should be mode-neutral even if it is used only by Election-Only first.

---

## 16. Final Architectural Recommendation

**ADOPT**: the separation *Import (registration) ≠ Admission ≠ Exercisability*, realised as:
- an **election-owned, creation-time-fixed `voter_admission_requirement`** (`none | officer_verification`);
- the **existing `VoterVerification` record as the single gate's evidence**;
- **one new conjunct at the existing Election-context entitlement boundary** (`isVoterInElection` + `EnsuresVoterMembership` in `store`).

**Do not adopt** a status-enum lifecycle (B), a membership boolean (A) or a generic gate table / workflow engine (D) now.

**Why:**
- The adopted business language (`EM-ENT-003/005/007`, `EM-GOV-001`) already presumes an *admission prerequisite* that the code lacks.
- `EM-OPEN-001` names this exact decision.
- Every bypass path converges on one enforcement point.
- Deriving admission from the attestation record makes status manipulation (S3/S4) unable to admit.
- The change is additive, and `none` keeps every existing election unchanged.

**Preconditions:** `EM-OPEN-001` decided → placement (Q2) decided → ADR → fixture-repair story → RED.

---

## Summary chain

```
CURRENT MODEL
  Import ⇒ ElectionMembership{voter, active} ⇒ can vote in VotingActive.
  Verify ⇒ voter_verifications row (IP/device + notes) ⇒ read by nothing at vote time.
  status 'active' = registered ∧ admitted ∧ exercisable (one field, three meanings).
        ↓
DOMAIN GAP
  No admission prerequisite (EM-OPEN-001 open); no election-owned rule stating it;
  verification is an unenforced attribute, not a gate; voter_verification_mode is a
  mutable, unsnapshotted binding setting, not an admission rule; reactivation paths
  (approve, assign-restore) bypass governance.
        ↓
RECOMMENDED CONSTITUTION MODEL
  Election (owner of voting-time entitlement)
    └ voter_admission_requirement: none | officer_verification   (fixed at creation)
  Admission(user, election) = requirement = none ∨ active VoterVerification(election,user)
  CanCastVote = registered ∧ active ∧ org-scoped ∧ Admission ∧ ElectionLifecycle::canVote()
  — placed OUTSIDE ElectionConstitution::RULES per the 2026-08-13 ruling unless an ADR decides otherwise.
        ↓
MINIMAL IMPLEMENTATION BOUNDARY
  Governance (EM-OPEN-001, Q2, ADR) → VO VoterAdmissionRequirement → elections column
  (+snapshot/guard) → conjunct in isVoterInElection & EnsuresVoterMembership (+cache
  invalidation on VoterVerification) → Verify requires registered target + audits subject
  → creation UI + manage-page "Admitted" column. Nothing else.
        ↓
TEST STRATEGY
  0. Repair fixtures A–D (separate story) so the existing entitlement suite is green.
  1. RED: requirement=officer_verification ∧ unverified → denied at code.create, vote.create,
     vote.store (uncached path) — and at the domain predicate (unit).
  2. RED: verified → allowed; revoked → denied on next check (cache invalidated).
  3. RED: requirement=none → today's behaviour (regression pin).
  4. RED: approve / assign-restore / re-import cannot admit an unverified voter (S3/S4).
  5. RED: verify rejects non-registered user; only chief/deputy of this election (pos + neg).
  6. RED: requirement immutable after setup_administration; part of snapshot/hash if chosen.
  7. Tenant isolation: two organisations, two elections — verification in one admits nothing in the other.
```

**Traceability:**
- ADR-002 (+ clarification 2026-08-13).
- Election Manifesto `EM-ENT-001…007`, `EM-EO-001…004`, `EM-GOV-001/005`, `EM-OPEN-001…007`.
- The Principal Architect ruling "Manifesto ≠ Constitution" (2026-08-13).
- PBDIGIT-65/69 (entitlement scope).
- Predecessor plans: `docs/plans/20260808-1030-election-verification-execution-plan.md`, `docs/plans/20260812-1748-election-only-implementation-boundary-plan.md`.
