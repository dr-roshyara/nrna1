# Trust Domain Bounded Contexts

**Status:** Current Implementation Mapping  
**Date:** May 30, 2026  
**Scope:** Three core bounded contexts discovered in Phase 1 analysis

---

## Overview

Public Digit's trust architecture is organized into three distinct bounded contexts, each with clear responsibility boundaries:

```
Trust Attestation Context
    ↓ (emits events)
Eligibility Context
    ↓ (emits events)
Authorization Context
```

Each context owns its own decision-making logic. Communication between contexts happens via domain events.

---

## 1. Trust Attestation Context

### Responsibility
Officer attestation of identity trustworthiness.

### Primary Concepts
- **Identity** — the person being verified
- **Evidence** — facts supporting the identity claim
- **Verification Officer** — the attester (human with authority)
- **Attestation** — the formal decision
- **Trust Level** — assurance degree (Provisionally Trusted, Officer Verified, Organization Verified, High Assurance)
- **Revocation** — withdrawal of prior attestation

### What It Decides
```
"Is this identity trustworthy?"
```

### What It Does NOT Decide
- Whether person can vote (Eligibility Context decides)
- Whether person can perform action (Authorization Context decides)
- Whether past votes remain valid after revocation (Governance decides)

### Domain Events Emitted
- `IdentityAttested` — officer created attestation
- `TrustLevelAssigned` — trust level determined
- `VerificationRevoked` — attestation withdrawn
- `VerificationExpired` — trust period elapsed

### Domain Events Consumed
- (None from other contexts — this is upstream)

### Current Implementation Evidence
- **Model:** `VoterVerification` (app/Models/VoterVerification.php)
- **Controller:** `VoterVerificationController` (app/Http/Controllers/Verification/)
- **Policy:** `VoterVerificationPolicy` (app/Application/Election/Capabilities/Policy/)

### Key Implementation
```php
VoterVerification
  ├── attestation_id (links to attestation)
  ├── verified_by (officer user_id)
  ├── verified_at (timestamp)
  ├── verified_ip (hashed)
  ├── verified_device_fingerprint_hash
  ├── notes
  ├── revoked_by (officer user_id or null)
  ├── revoked_at (timestamp or null)
  └── active (boolean state)

VoterVerificationPolicy.evaluate()
  └── returns CapabilityDecision.prohibited() if verification required but incomplete
```

### Bootstrap Model
```
TrustedOfficer
  ├── User (has identity)
  ├── ElectionOfficer role (governance-assigned)
  ├── Can create attestations (inherent to role)
  └── Does NOT require IdentityVerification
```

**Critical:** Officer authority comes from governance, not verification.

---

## 2. Eligibility Context

### Responsibility
Evaluation of whether participant meets requirements for a specific process.

### Primary Concepts
- **Participant** — person potentially eligible
- **Process** — the action being evaluated for (Voting, Candidacy, Delegation)
- **Eligibility Rules** — requirements specific to process and organization
- **Eligibility Decision** — the evaluation outcome

### What It Decides
```
"Does this participant meet requirements for this process?"
```

### What It Does NOT Decide
- Whether identity is trustworthy (Trust Attestation Context decides)
- Whether participant can perform action (Authorization Context decides)

### Eligibility Dimensions
1. **Membership Status** (Active/Expired/Suspended)
2. **Fee Payment Status** (Paid/Partial/Unpaid/Exempt)
3. **Membership Type** (Full/Associate/etc.)
4. **Election Assignment** (enrolled for specific election)
5. **Timing** (voting window open, deadline passed, etc.)
6. **Geographic Scope** (member of correct organization/region)

### Domain Events Emitted
- `EligibilityGranted` — participant meets requirements
- `EligibilityRevoked` — participant no longer meets requirements
- `EligibilityExpired` — time-based eligibility elapsed

### Domain Events Consumed
- `IdentityAttested` (from Trust Attestation) → triggers eligibility evaluation
- `VerificationRevoked` (from Trust Attestation) → may require eligibility re-evaluation

### Current Implementation Evidence
- **Service:** `EloquentVoterEligibilityQueryService` (app/Infrastructure/Persistence/)
- **Logic:** `Member.getVotingRightsAttribute()` (computed from membership state)
- **Evaluation:** In Vote/Election controllers before action permitted

### Key Implementation
```php
Member::getVotingRightsAttribute()
  ├── Active status? → continue : return 'none'
  ├── Paid/exempt fees? → continue : return 'none'
  ├── Full member type? → return 'full' : return 'voice_only'

EloquentVoterEligibilityQueryService.isEligible(member, election)
  ├── Check membership status
  ├── Check fee status
  ├── Check membership type
  └── return boolean
```

### Eligibility is Computed, Not Stored
Eligibility is **derived** from Member state at evaluation time, not persisted as aggregate state. This is correct — eligibility should be computed freshly, not cached.

---

## 3. Authorization Context

### Responsibility
Granting permission to perform specific actions.

### Primary Concepts
- **Actor** — the participant requesting action
- **Action** — the specific operation (Cast Vote, Approve Membership, Create Election, etc.)
- **Scope** — the boundary of authority (Election-specific, Organization-wide, Committee-scoped)
- **Role** — the grant mechanism (Chief Officer, Deputy, Commissioner, Member, Voter)
- **Permission** — the formal grant

### What It Decides
```
"May this participant perform this action?"
```

### What It Does NOT Decide
- Whether identity is trustworthy (Trust Attestation Context)
- Whether participant meets process requirements (Eligibility Context)

### Authorization Decision Formula
```
Authorization(actor, action) =
  Verification(actor) AND
  Eligibility(actor, process) AND
  Permission(actor, action, scope)
```

All three must be true. Missing any one → no authorization.

### Domain Events Emitted
- `AuthorizationGranted` — permission assigned
- `AuthorizationRevoked` — permission withdrawn
- `RoleAssigned` — governance granted role

### Domain Events Consumed
- `EligibilityGranted` (from Eligibility Context) → may enable authorization
- `EligibilityRevoked` (from Eligibility Context) → may revoke authorization

### Current Implementation Evidence
- **Models:** `ElectionOfficer`, `UserOrganisationRole` (app/Models/)
- **Policies:** `MembershipPolicy`, `ElectionPolicy` (app/Application/)
- **Enforcement:** Controllers use `$this->authorize()` and capability checks

### Key Implementation
```php
ElectionOfficer
  ├── election_id
  ├── user_id
  ├── role (chief, deputy, commissioner, observer)
  ├── status (active/inactive)

UserOrganisationRole
  ├── user_id
  ├── organisation_id
  ├── role (owner, admin, commission, voter, member)

Authorization evaluation happens at:
  ├── Route model binding (implicit checks)
  ├── Controller authorization ($this->authorize())
  ├── Capability system (TrustPolicyEvaluator)
```

---

## Inter-Context Communication

### Trust Attestation → Eligibility
```
IdentityAttested event
  └── triggers re-evaluation of eligibility in Eligibility Context
```

### Eligibility → Authorization
```
EligibilityGranted event
  └── enables authorization checks in Authorization Context

EligibilityRevoked event
  └── may revoke existing authorizations
```

### Authorization → Voting/Action
```
AuthorizationGranted event
  └── enables action execution in domain (Vote, Approve, Create Election)
```

### Revocation Impact (Governance-Driven)
```
VerificationRevoked event
  └── Eligibility Context: may trigger re-evaluation
  └── Authorization Context: depends on governance policy
  └── Governance: decides impact on past votes, election validity, etc.
```

**Critical:** Revocation is Trust Attestation domain's event. Impact on results/votes is Governance domain's responsibility.

---

## Architectural Boundaries

### Trust Attestation Context
```
app/Application/Election/Capabilities/Policy/VoterVerificationPolicy.php
app/Http/Controllers/Verification/VoterVerificationController.php
app/Models/VoterVerification.php
```

### Eligibility Context
```
app/Infrastructure/Persistence/EloquentVoterEligibilityQueryService.php
app/Models/Member.php (voting_rights attribute)
Vote/Election controllers (eligibility checks)
```

### Authorization Context
```
app/Application/Election/Capabilities/Policy/CapabilityPolicy.php
app/Http/Controllers/ (authorization via policies)
app/Models/ElectionOfficer.php
app/Models/UserOrganisationRole.php
```

---

## Next Steps

1. Verify each context's current implementation against code evidence
2. Identify missing event publishing between contexts
3. Define explicit domain event interfaces
4. Map remaining governance logic (revocation consequences)

---

**Last Updated:** May 30, 2026  
**Domain:** Trust Attestation  
**Next:** Review TRUST_CHAIN.md for decision flow
