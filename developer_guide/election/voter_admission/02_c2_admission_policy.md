# 02 — C2: The Voter Admission Policy and the Evidence Schema

**Slice:** `PBDIGIT-213` C2 · **ADR:** `ADR_20261004_1549` D3, D4, R3-1, R3-2 · **PO decisions:** C2-D1 (default OFF), C2-D2 (Election-Only only)

---

## Purpose

C2 introduces the first piece of *Voter Admission*: an **election-owned, lock-protected policy** that says what a registered Election-Only voter must satisfy. In v1 that is a single choice, **"Identity verification required: ON / OFF"**. C2 also extends `voter_verifications`, so that C3 can treat it as admission evidence.

> **C2 defines the requirement. C3 makes evidence satisfy it.** In C2, turning the policy ON has **no effect on voting yet**. This is pinned by `AdmissionPolicyC2BoundaryTest`, and C3.1 replaces that pin in the open.

## Where it fits

```
HTTP     PATCH /elections/{slug}/admission-policy  (elections.admission-policy.update, can:manageSettings)
           UpdateVoterAdmissionPolicyRequest  — client sends the business toggle identity_verification_required
           VoterAdmissionPolicyController     — demo → 404 · Election-Only only · audit on change
             │
Application  ChangeVoterAdmissionPolicyHandler(ChangeVoterAdmissionPolicyCommand) → AdmissionPolicyChange
             │      ports: VoterAdmissionPolicyStore · AdmissionImpactQuery
Domain       VoterAdmissionPolicy · AdmissionRequirement · AdmissionEvidence · AdmissionDecision · AdmissionPolicyLocked
             │
Infra        EloquentVoterAdmissionPolicyStore · EloquentAdmissionImpactQuery
Model        Election::admissionPolicy() · saving guard (locked ⇒ LogicException)
DB           elections.voter_admission_policy  json default {"requires":[]}
             voter_verifications.verifier_role · method (default officer_attestation) · revocation_reason
```

## Key files

| File | Layer |
|---|---|
| `app/Contexts/Elections/Domain/ValueObjects/AdmissionRequirement.php` | Domain: **what** (v1: `OFFICER_IDENTITY_VERIFICATION`) |
| `app/Contexts/Elections/Domain/ValueObjects/VoterAdmissionPolicy.php` | Domain: the set of requirements; ALL-of |
| `app/Contexts/Elections/Domain/ValueObjects/AdmissionEvidence.php`, `AdmissionDecision.php` | Domain: current effect, and the satisfied or missing requirements |
| `app/Contexts/Elections/Domain/Exceptions/AdmissionPolicyLocked.php` | Domain: change refused while locked |
| `app/Contexts/Elections/Application/Handlers/ChangeVoterAdmissionPolicyHandler.php` | Application command handler |
| `app/Contexts/Elections/Application/Ports/{VoterAdmissionPolicyStore,AdmissionImpactQuery}.php` | Application ports |
| `app/Contexts/Elections/Infrastructure/Repositories/EloquentVoterAdmissionPolicyStore.php` | Infrastructure |
| `app/Contexts/Elections/Infrastructure/Queries/EloquentAdmissionImpactQuery.php` | Infrastructure |
| `app/Http/Controllers/Election/VoterAdmissionPolicyController.php`, `app/Http/Requests/Election/UpdateVoterAdmissionPolicyRequest.php` | HTTP |
| `database/migrations/2026_10_04_00000{1,2}_*.php` | Additive schema |

## How it works

### The policy (pure domain)

```php
$policy = VoterAdmissionPolicy::requiring(AdmissionRequirement::OfficerIdentityVerification);

$policy->isSatisfiedBy(AdmissionEvidence::none())->isAdmitted();        // false; missing = [OIV]
$policy->isSatisfiedBy(AdmissionEvidence::satisfying(
    AdmissionRequirement::OfficerIdentityVerification))->isAdmitted();  // true
VoterAdmissionPolicy::none()->isSatisfiedBy(AdmissionEvidence::none())->isAdmitted(); // true (OFF)

VoterAdmissionPolicy::fromPersistence(null)->isEmpty();                 // true — legacy / missing = OFF
$policy->toPersistence();   // ['requires' => ['OFFICER_IDENTITY_VERIFICATION']]  sorted, unique
```

- `fromPersistence()` rejects unknown codes with `InvalidArgumentException`.
- The persisted form is deterministic, so equal policies serialise identically and `equals()` is reliable.

### Changing the policy (application)

```php
$change = $handler->handle(new ChangeVoterAdmissionPolicyCommand($election->id, $proposed, $actorId));
// identical proposal → $change->changed === false (no write, no audit) — even when locked
// locked election    → AdmissionPolicyLocked
// otherwise          → affected voters counted, policy saved, AdmissionPolicyChange returned
```

The handler **never writes an `ElectionMembership`**. Admission is derived from policy and evidence, never stored.

### The lock: two layers

| Layer | Where | Rule |
|---|---|---|
| Application | `ChangeVoterAdmissionPolicyHandler` (via `VoterAdmissionPolicyStore::isLocked()`) | refused from **ReadyForVoting** onward |
| Model | `Election::booted()` `saving` | dirty `voter_admission_policy` on a locked election → `LogicException` |

Editable states are Draft, SetupAdministration and SetupNomination. Locked means `ElectionLifecycle::of($e)->isLocked()`.

### Who and where

- **Who:** the active Chief or Deputy **of this election** (`ElectionPolicy::manageSettings`, election-scoped). Organisation-wide officers, commissioners, other elections' chiefs and voters get 403.
- **Where:** real **Election-Only** elections only (C2-D2). Full-membership elections get a validation error, and demo elections get 404.
- **Not via** the generic `PATCH /settings`: it writes only its validated keys, and `voter_admission_policy` is not one of them (pinned by a test).

### Audit

An effective change logs `admission_policy_changed` (category `committee`) through `ElectionAuditService`, with `from`, `to` and `affected_voters`. A no-op is not logged.

### Affected voters (v1 boundary)

`EloquentAdmissionImpactQuery` counts memberships of this election that are **role voter, status active, not soft-deleted, with organisation = the election's organisation**, and that have **no active** `voter_verifications` row. For `none ↔ OFFICER_IDENTITY_VERIFICATION` these are the voters whose effective voting entitlement **would change under the proposed policy once admission enforcement is active (C3)**:
- they would need verification when the policy is turned ON;
- they would no longer need it when it is turned OFF.

In C2 itself no entitlement changes; the count describes the policy's effect, not a current one.

Other transitions are gap G9.

### Settings page props (the card itself is C4)

`ElectionSettingsController::edit()` passes:

```php
'admission' => [
    'identityVerificationRequired' => bool,
    'isLocked'                     => bool,
    'available'                    => bool,  // Election-Only real election
    'affectedVoters'               => int,
]
```

### Evidence schema

| Column | Meaning |
|---|---|
| `verifier_role` | **Who** verified, in which role (`ELECTION_CHIEF` / `ELECTION_DEPUTY_CHIEF`). Written from C3; legacy rows are null |
| `method` | **How**; defaults to `officer_attestation` |
| `revocation_reason` | **Why** the evidence's *current admission effect* ended (e.g. `suspension_confirmed`). The historical fact is not deleted |

The unique `(election_id, user_id)` remains. Overwriting on re-verification is a technical limitation, not protocol history (gap G1).

## How to extend

- **A new requirement (e.g. email ownership):** add a case to `AdmissionRequirement`, plus its evidence producer (C3 pattern) and, only if a human acts, an authority rule. The policy, its persistence and the lock need no change.
- **OR semantics (Vendor E):** add a clause type inside `VoterAdmissionPolicy`; callers keep using `isSatisfiedBy()`.

## Testing

| Test | Proves |
|---|---|
| `tests/Unit/Contexts/Elections/VoterAdmissionPolicyTest.php` | ALL-of semantics, OFF default, strict codes, deterministic persistence |
| `tests/Unit/Contexts/Elections/ChangeVoterAdmissionPolicyHandlerTest.php` | lock refusal, no-op, impact passthrough (ports mocked) |
| `tests/Feature/Election/VoterAdmissionPolicyEndpointTest.php` | authorization matrix, lifecycle matrix (each state asserted as a precondition), model guard, scope, validation, audit, no membership writes, generic PATCH no bypass, settings props incl. `affectedVoters` |
| `tests/Feature/Election/AdmissionEvidenceSchemaTest.php` | evidence columns and default |
| `tests/Feature/Election/AdmissionPolicyC2BoundaryTest.php` | C2 does not enforce (superseded by C3.1) |
| `tests/Architecture/VoterAdmissionDomainPurityTest.php` | no framework in domain/application; no `*Gate*` classes |

## Pitfalls

- **Don't write the policy through the generic settings PATCH**, and don't add it to `UpdateElectionSettingsRequest`. Use the dedicated endpoint, which audits and computes the impact.
- **The model guard fires on any dirty save** of `voter_admission_policy` while the election is locked, including seeders and console code. That is intended.
- **Before C3, ON is not enforced.** Don't advertise it as a voting gate until C3 lands.
- **Fixtures:** to reach a locked state, use the lifecycle facts (`setupAdministration()` + `administration_completed`/`nomination_completed`/voting window for ReadyForVoting; `suspended_at` for Suspended) and assert the derived state as a precondition.

**Traceability:** `PBDIGIT-213` C2 · `ADR_20261004_1549` D3/D4/R3-1/R3-2 · C2-D1/C2-D2 (PO, 2026-10-04).
