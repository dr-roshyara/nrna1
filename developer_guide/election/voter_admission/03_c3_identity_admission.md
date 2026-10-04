# 03 — C3: Identity Admission Enforced by the One Voting-Entitlement Decision

**Slice:** `PBDIGIT-213` C3 (C3.1–C3.5) · **ADR:** `ADR_20261004_1549` D1–D5, R3-3, R3-4, §2.2 · **PO decisions:** C3-D1 (fixture-only adaptation), C3-D2 (verifiable target), C3-D3 (C2 pin replaced) · **Brief:** `docs/publicdigit/brainstorming/voter_management/20261004_1802_can_vote_implementation.md`

---

## Purpose

C2 defined the requirement ("Identity verification required: ON/OFF"). **C3 makes it real:**
- With the policy ON, a registered Election-Only voter can vote only after the **Chief or Deputy Chief of the election** has verified their identity.
- A **confirmed suspension** ends that verification's *current* effect, and the voter is admitted again only after **both** reinstatement and re-verification.

Everything is enforced **server-side by the one `VotingEntitlementQuery`**.

## Final authority model

```
VoterAdmissionPolicy  (WHAT is required)        Election — "Identity verification required" ON/OFF
VerificationAuthority (WHO may verify)          Chief / Deputy Chief of THIS election
   └─ VerificationAuthoritySource (port)        v1 adapter: election_officers (chief|deputy, active, election_id = this)
AdmissionEvidence     (WHAT happened, current)  voter_verifications.status = active  (one row per election+voter)
AdmissionDecision     (satisfied?)              policy.isSatisfiedBy(evidence)
VotingEntitlement     (MAY VOTE NOW?)           registered → voter → organisation → active → ADMITTED → votable
```

**The order is fixed:** the first failing condition gives the single reason. `NotAdmitted` is checked only after registration, role, organisation and active status, and before the lifecycle.

| Term | Meaning | Owner |
|---|---|---|
| `VotingEntitlement` | the authoritative, election-scoped decision | `VotingEntitlementQuery` |
| `has_voted` | one-ballot condition; **not** part of entitlement | ballot path |
| `Code.can_vote_now` | voting-process gate after a verified voting code. **OUT OF SCOPE; unchanged** | voting workflow |
| `VoterSlug` | technical access/session mechanism; never a grant | slug routes |
| `User.can_vote` / `User.is_voter` | **old legacy columns, dropped from the schema**. Never an authority | — |

## Key files

| File | Role |
|---|---|
| `Domain/Policies/VotingEntitlementPolicy.php` | adds the `NotAdmitted` step |
| `Domain/ValueObjects/VotingEntitlementContext.php` | `isAdmitted` (default true = no requirement) |
| `Domain/Policies/VerificationAuthority.php` + `ValueObjects/{VerifierRole,ElectionLeadershipRole,RevocationReason}.php` | who may verify; stable evidence codes |
| `Domain/Exceptions/{VoterNotVerifiable,VerifierNotAuthorised}.php` | target and actor refusals |
| `Application/Handlers/{RecordIdentityVerificationHandler,RevokeIdentityVerificationHandler}.php` | the commands |
| `Application/Ports/{VoterRegistrationQuery,VerificationAuthoritySource,IdentityEvidenceStore}.php` | ports |
| `Infrastructure/Queries/{ElectionOfficerVerificationAuthoritySource,EloquentVoterRegistrationQuery}.php`, `Infrastructure/Repositories/EloquentIdentityEvidenceStore.php` | adapters |
| `Infrastructure/Queries/EloquentVotingEntitlementQuery.php` | reads `voter_verifications` when the policy is ON |
| `Infrastructure/Queries/VotingEntitlementCacheKey.php` | key `user.{u}.voter.v3.{e}.{policyFingerprint}` |
| `app/Http/Middleware/OperationCapabilityMapper.php` | `verify_voter_identity` window (replaces `verify_vote`) |
| `app/Http/Controllers/Election/VoterVerificationController.php` | the legacy **Verify** button, now delegating |
| `app/Http/Controllers/ElectionVoterController.php` (`confirmSuspension`) | revokes the current effect atomically |
| `app/Http/Controllers/ElectionVotingController.php` | `admissionPending` prop; the `start()` message |

## How it works

### Enforcement (C3.1)

```php
// EloquentVotingEntitlementQuery::isAdmitted()
$policy = $election->admissionPolicy();
if ($policy->isEmpty()) return true;                  // OFF ⇒ nothing required, evidence ignored
$active = DB::table('voter_verifications')->where('election_id', …)->where('user_id', …)
            ->where('status', 'active')->exists();      // revoked evidence does not count
return $policy->isSatisfiedBy($active
    ? AdmissionEvidence::satisfying(AdmissionRequirement::OfficerIdentityVerification)
    : AdmissionEvidence::none())->isAdmitted();
```

Every authoritative real-election voting decision uses this one decision:
- `ensure.election.voter` → `User::isVoterInElection()`: code entry, ballot, vote store;
- the **uncached** in-transaction check in `VoteController::store`;
- `elections.show` (`isEligible`, `canVote`, `admissionPending`) and `elections.start`.

Legacy paths that still reference the dropped `User.can_vote` / `User.is_voter` are classified below as legacy boundaries, **not** as alternative entitlement authorities. They currently refuse access. Where remediation is required they are tracked separately (PBDIGIT-214/215/218).

On the protected real-election voting paths covered by C3 (`slug.code.create`, `slug.vote.create`, `slug.vote.store`, the in-transaction check, `elections.start`), an unadmitted voter receives **403 (or a refusal redirect at `start`) before reaching downstream voting code**. The unrelated missing-Code crash in `store` (PBDIGIT-219) remains a separate robustness issue and is **not fixed here**.

### Verify (C3.2/C3.3)

```
POST …/voters/verify   election.state:verify_voter_identity   (SetupAdministration · SetupNomination · ReadyForVoting · VotingActive)
  → authorize manageSettings (Chief/Deputy of THIS election)
  → RecordIdentityVerificationHandler
       target: voter role, not deleted, status ≠ removed        (else VoterNotVerifiable → 'user_id' error)
       actor : leadershipRoleOf(user, election) → VerifierRole (else VerifierNotAuthorised → 403)
       store : evidence active · verified_by · verifier_role · method=officer_attestation · revocation cleared
  → IdentityAttestedEvent (unchanged) · audit voter_verified {subject_user_id, verifier_role, requirement, policy, …}
```

- **The verifier role is derived from the election's leadership, never from the request.**
- **IP and device data stay audit metadata.** The IP field is optional.
- **Verification never changes membership status**, so it does not lift a suspension.

### Suspend Identity (C3.4)

"Suspend Identity" is the **existing two-person process**:
- **propose:** nothing happens to the evidence;
- **cancel:** nothing happens to the evidence;
- **confirm:** in one transaction, `confirmSuspension()` runs **and** `RevokeIdentityVerificationHandler(reason: suspension_confirmed)` runs. The voter stays registered. The row keeps `verified_by`/`verified_at` (the historical fact); only `status`/`revoked_*`/`revocation_reason` change (the current effect).

| After a confirmed suspension | Standing |
|---|---|
| nothing else | `NotActive` |
| reinstated (approve) only | `NotAdmitted` |
| re-verified only | `NotActive` |
| reinstated **and** re-verified (either order) | `Entitled` |

**A confirmed suspension revokes the current identity-evidence effect even when the admission policy is OFF.** So enabling the policy later cannot treat previously revoked evidence as active admission evidence. (Pinned by `SuspensionRevalidationTest::test_a_confirmed_suspension_ends_the_admission_effect_even_when_the_policy_is_off`.)

### Cache invalidation

| Event | Mechanism |
|---|---|
| admission policy change | new **policy fingerprint** in the key, so old standings are unreachable (no enumeration) |
| verification recorded / re-verified / revoked / suspension revocation | `VoterVerification::booted()` saved/deleted → `VotingEntitlementCacheKey::forgetCurrent(u, e)` |
| membership change (suspension confirmed, reinstatement, removal) | `ElectionMembership::booted()` → `forgetCurrent(u, e)` |
| final vote action | the uncached authoritative query inside the transaction |

The fingerprint is an MD5 **cache namespace only**: no integrity or security meaning.

**The cache must prove both transitions, and the tests do** (`IdentityVerificationAdmissionTest`):
- a cached `Entitled` becomes `NotAdmitted` immediately after revocation (`test_revocation_takes_effect_immediately_despite_the_cache`);
- a cached `NotAdmitted` becomes `Entitled` immediately after verification (`test_verification_takes_effect_immediately_despite_the_cache`);
- a cached `Entitled` becomes `NotAdmitted` immediately after the policy is turned ON (`test_a_policy_change_takes_effect_immediately_despite_the_cache`).

## Legacy `can_vote` classification (brief §16)

| Class | Occurrences (examples) | C3 action |
|---|---|---|
| **A** authoritative entitlement | `VotingEntitlementQuery` only | — |
| **B** final/legacy action gate | `VotingSecurityService::canIssueVotingSlug` (`voter.start`), recovery (`VotingSecurityController:160`, `GenerateRecoverySlug:59`), `VoteEligibility` legacy branch, `vote.direct` | none. They read **dropped columns**, so `null` refuses everyone; no bypass (pinned) |
| **C** obsolete writes | `approveForVoting`/`suspendVoting`, `VoterlistController`, the bulk approve/disapprove commands | none. Writes are dropped (`$guarded`) |
| **D** voting-process gate | `Code.can_vote_now` | **OUT OF SCOPE**, zero changes |
| **E** presentation | the `HandleInertiaRequests` shared `user.can_vote`, voter-list badges | none. No page authorises with it |
| **F** security | IP results in `VotingSecurityService` | none |
| **G** dead | `ElectionProcessController` (unrouted), `VoterRecoveryService`, several `User` helpers, demo branches | documented only |

## Findings raised (not fixed in C3)

| Item | Finding |
|---|---|
| `PBDIGIT-214` | `voter.restart` mints a slug without an entitlement check (blocked at use) |
| `PBDIGIT-215` | recovery is destructive and calls a missing method (currently unreachable) |
| `PBDIGIT-216` | emergency lockdown calls missing methods (separate from identity suspension) |
| `PBDIGIT-217` | a leftover demo session flag skips the lifecycle check at step 1 on real slugs |
| `PBDIGIT-218` | legacy `POST /votes` may judge the wrong election (refused today) |
| `PBDIGIT-219` | `VoteController::store` crashes with 500 without a Code |

## Testing

| Test | Proves |
|---|---|
| `tests/Unit/Contexts/Elections/VotingEntitlementAdmissionTest.php` | `NotAdmitted` order; OFF default |
| `tests/Unit/Contexts/Elections/VerificationAuthorityTest.php` | Chief/Deputy → roles; nothing else |
| `tests/Unit/Contexts/Elections/IdentityVerificationHandlersTest.php` | target, actor, evidence record, revoke |
| `tests/Feature/Election/IdentityVerificationAdmissionTest.php` | OFF/ON × no/active/revoked evidence; 403 at code/ballot/store; cache (both directions); isolation; show/start; bypass via approve/assign/re-import |
| `tests/Feature/Election/IdentityVerificationAuthorityTest.php` | authority matrix, target rule, lifecycle window, audit subject, re-verify, revoke |
| `tests/Feature/Election/SuspensionRevalidationTest.php` | two-person flow; reinstatement ≠ re-verification |
| `tests/Feature/Election/LegacyVotingFlagsBoundaryTest.php` | `can_vote`, `can_vote_now`, slug, recovery, `voter.start`, `has_voted` cannot compete |
| `tests/Architecture/IdentityVerificationArchitectureTest.php` | framework-free domain; no legacy flags in the decision; controller delegates |

**Superseded:** `AdmissionPolicyC2BoundaryTest` was deleted in this commit (approved, C3-D3). Its assertion "ON does not block" is replaced by the `NotAdmitted` tests above.

**Fixture-only adaptations (PO-approved, assertions byte-identical):**
- `tests/Feature/Trust/VoterVerificationEmitsEventsTest.php` (C3-D1)
- `tests/Feature/Trust/SimpleStoreTest.php` (found by the C3 regression)

Both verified a non-member in a Draft election, which D1/D3 now refuse. They now use a SetupAdministration election and a registered voter. Each passes on the pre-C3 code and on C3, so the fixture change alters no outcome. Classification: **expected fixture adaptation caused by approved C3 D1/D3 target-eligibility rules**, not a regression.

## Pitfalls

- Never add `has_voted`, `can_vote`, `can_vote_now` or `is_voter` to the decision. The architecture test fails if you do.
- Don't write `voter_verifications` from controllers; use the handlers.
- Don't "fix" a legacy `can_vote` gate by restoring the columns. Route it through `VotingEntitlementQuery`; see PBDIGIT-214/215/218.
- **Revoke shares the verification window** (`verify_voter_identity`). It is allowed during VotingActive and refused in Counting, pinned by `IdentityVerificationAuthorityTest::test_revocation_shares_the_verification_window_and_is_allowed_during_voting` / `…_is_refused_outside_the_verification_window`.
- **The two-person suspension routes have their own, pre-existing gate.** Propose, confirm and approve sit in `election.state:import_voters` (`routes/organisations.php:261-282`), i.e. `canManageVoters`. That is true only in Draft, Approved, Rejected and SetupAdministration (`ElectionLifecycleEngineImpl.php:208-290`). So **a suspension can't be confirmed from SetupNomination onward**. This is existing behaviour that C3 does not change. During voting, the current admission effect can be ended only by **revoke**. The C3 suspension tests run in SetupAdministration; the `import_voters` gate itself is not separately pinned by a C3 test.

**Traceability:** `PBDIGIT-213` C3 · `ADR_20261004_1549` · C3-D1/D2/D3 · brief `20261004_1802` · backlog `PBDIGIT-214`…`219`.
