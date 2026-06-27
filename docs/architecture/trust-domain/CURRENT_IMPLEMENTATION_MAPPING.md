# Current Implementation Mapping

**Status:** Evidence of existing architecture  
**Date:** May 30, 2026  
**Scope:** How current code implements trust domain concepts

---

## Quick Reference

| Concept | Current Implementation | File Path | Status |
|---------|----------------------|-----------|--------|
| **Verified** | VoterVerification model + policy | app/Models/VoterVerification.php | ✅ Complete |
| **Eligible** | Member voting_rights + query service | app/Models/Member.php + app/Infrastructure/ | ✅ Complete |
| **Authorized** | ElectionPolicy + CapabilityPolicy | app/Application/Election/Capabilities/ | ✅ Complete |
| **Attestation** | VoterVerificationController | app/Http/Controllers/Verification/ | ✅ Complete |
| **Trust Level** | Implicit (active/revoked only) | VoterVerification.active | ⚠️ Binary only |
| **Evidence** | Notes field (free-text) | VoterVerification.notes | ⚠️ Unstructured |
| **Domain Events** | Not yet implemented | — | ❌ Missing |
| **Trust Policy** | No explicit model | — | ❌ Missing |

---

## 1. VERIFIED — Trust Attestation

### Current Model: VoterVerification

**File:** `app/Models/VoterVerification.php`

**Structure:**
```php
VoterVerification extends Model
  id (PK)
  election_id (FK)
  user_id (FK)
  verified_by (officer user_id)
  verified_at (timestamp)
  verified_ip (hashed)
  verified_device_fingerprint_hash (hashed)
  notes (text, free-form)
  revoked_by (officer user_id, nullable)
  revoked_at (timestamp, nullable)
  active (boolean - true if verified and not revoked)
  created_at
  updated_at
```

**Status:** ✅ Complete

**What Exists:**
- Officer attestation captured (verified_by, verified_at)
- Evidence context captured (IP, device fingerprint)
- Officer notes recorded
- Revocation tracked (revoked_by, revoked_at)
- State machine: verified or revoked

**What's Missing:**
- No explicit trust level values (only binary: active or not)
- Evidence is just IP/device fingerprint (no document tracking)
- No expiry mechanism (once verified, valid forever)
- No re-verification requirements

### Current Policy: VoterVerificationPolicy

**File:** `app/Application/Election/Capabilities/Policy/VoterVerificationPolicy.php`

**Behavior:**
```php
public function evaluate(ConstitutionalDecisionContext $context): ?CapabilityDecision
  if (verification_required && !verification.isComplete())
    return CapabilityDecision.prohibited("Voter verification required...")
  else
    return null (abstain)
```

**Status:** ✅ Complete

**What Exists:**
- Precondition gate (checks if verification complete)
- Policy returns explicit decision if condition unmet
- Allows other policies to decide if condition not checked

**What's Missing:**
- No trust level evaluation
- No evidence quality assessment
- No expiry checking

### Current Controller: VoterVerificationController

**File:** `app/Http/Controllers/Verification/VoterVerificationController.php`

**Behavior:**
```php
public function store(Request $request)
  create VoterVerification record with:
    verified_by = auth()->id()
    verified_at = now()
    verified_ip = Hash(request->ip())
    verified_device_fingerprint_hash
    notes = request.notes
    active = true

public function revoke()
  revoke VoterVerification
    revoked_by = auth()->id()
    revoked_at = now()
    active = false
```

**Status:** ✅ Complete

**What Exists:**
- Officer can create attestation
- Officer can revoke attestation
- Context captured (IP, device fingerprint)

**What's Missing:**
- No event emission (VerificationRevokedEvent, etc.)
- No governance notification
- No policy application (doesn't check organization's trust policy)

---

## 2. ELIGIBLE — Eligibility Rules

### Current Model: Member

**File:** `app/Models/Member.php`

**Relevant Attributes:**
```php
Member extends Model
  id
  user_id (FK)
  organisation_id (FK)
  membership_type_id (Full, Associate, etc.)
  status (active, expired, suspended)
  fees_status (paid, partial, unpaid, exempt)
  membership_starts_at
  membership_expires_at
  
  // Computed attribute:
  getVotingRightsAttribute()
    if (status != 'active')
      return 'none'
    if (fees_status not in ['paid', 'exempt'])
      return 'none'
    if (membership_type == 'full')
      return 'full'
    else
      return 'voice_only'
```

**Status:** ✅ Complete

**What Exists:**
- Membership status tracking (active, expired, suspended)
- Fee tracking (paid, partial, unpaid, exempt)
- Membership type (Full, Associate, etc.)
- Computed voting rights (none, voice_only, full)

**What's Missing:**
- No process-specific eligibility rules
- No time-based eligibility (voting window)
- No scope-based eligibility (geographic, committee)
- No explicit eligibility aggregates

### Current Service: EloquentVoterEligibilityQueryService

**File:** `app/Infrastructure/Persistence/EloquentVoterEligibilityQueryService.php`

**Behavior:**
```php
public function isEligible(Member $member, Election $election): bool
  if (!$member)
    return false
  if (!Voter::where('member_id', $member->id)
           ->where('election_id', $election->id)
           ->exists())
    return false
  return true
```

**Status:** ⚠️ Partial

**What Exists:**
- Checks member exists
- Checks voter is enrolled for election

**What's Missing:**
- Doesn't check Member.voting_rights
- Doesn't check election voting window
- Doesn't check membership type specific rules
- Doesn't check fee status directly

### Current Usage in Controllers

**File:** `app/Http/Controllers/Vote/VoteController.php` (approx)

**Behavior:**
```php
public function create()
  uses EnsuresVoterMembership trait
    ├── checks Member exists
    ├── checks Member.voting_rights
    ├── checks Voter record exists
    └── returns eligible or ineligible

  if (eligible)
    render voting form
```

**Status:** ✅ Complete

**What Exists:**
- Pre-voting eligibility check
- Blocks ineligible voters from voting

**What's Missing:**
- No event emission (EligibilityGranted, EligibilityExpired)
- Eligibility computed at request time (good), not cached (good)

---

## 3. AUTHORIZED — Role-Based Access

### Current Model: ElectionOfficer

**File:** `app/Models/ElectionOfficer.php`

**Structure:**
```php
ElectionOfficer extends Model
  id
  election_id (FK)
  user_id (FK)
  role (chief, deputy, commissioner, observer)
  status (active, inactive)
  created_at
  updated_at
```

**Status:** ✅ Complete

**What Exists:**
- Officer role assignment (chief, deputy, commissioner, observer)
- Status tracking
- Election-scoped authority

### Current Model: UserOrganisationRole

**File:** `app/Models/UserOrganisationRole.php`

**Structure:**
```php
UserOrganisationRole extends Model
  id
  user_id (FK)
  organisation_id (FK)
  role (owner, admin, commission, voter, member)
  created_at
  updated_at
```

**Status:** ✅ Complete

**What Exists:**
- Organization-scoped roles
- Multiple role types

### Current Policies: ElectionPolicy, MembershipPolicy

**File:** `app/Application/Election/` and `app/Application/Membership/`

**Behavior:**
```php
ElectionPolicy
  public function vote(User $user, Election $election): bool
    check: user has Voter role for election
    check: election voting window open
    return boolean

MembershipPolicy
  public function approve(User $user, Application $app): bool
    check: user has commissioner role
    return boolean
```

**Status:** ✅ Complete

**What Exists:**
- Role-based authorization
- Action-specific policies
- Multiple policy types

**What's Missing:**
- Not explicitly composed with Eligible + Verified
- No formal authorization formula
- No delegated authority tracking

---

## 4. ATTESTATION — Officer Decision

### Current Implementation

**Trust Attestation = VoterVerification + VoterVerificationController**

**Status:** ✅ Complete

**Workflow:**
```
1. Officer reviews membership application (UI form)
2. Officer clicks "Verify"
3. VoterVerificationController.store() creates record with officer ID
4. VoterVerification persisted with:
   - verified_by (officer ID)
   - verified_at (now)
   - verified_ip, fingerprint, notes
5. Voter can now participate in election
```

**What Exists:**
- Officer makes explicit decision
- Decision is recorded with officer identity
- Revocation is tracked

**What's Missing:**
- No formal attestation event
- No governance notification
- No decision audit trail beyond database record

---

## 5. TRUST LEVEL — Assurance Degrees

### Current Implementation

**Status:** ❌ Missing (Binary only)

**Current State:**
```php
VoterVerification
  active (boolean)
  
// Implicit mapping:
active = true  → "Officer Verified" (only trust level)
active = false → "Revoked"
```

**Missing:**
- No Provisionally Trusted level
- No Organization Verified level
- No High Assurance level
- No trust_level column

**Required Enhancement:**
```php
VoterVerification
  trust_level (enum: 'unverified', 'provisionally_trusted', 'officer_verified', 'organization_verified', 'high_assurance', 'revoked')
  trust_level_assigned_at (timestamp)
  trust_level_assigned_by (officer user_id)
  trust_level_evidence (json: what evidence justified this level)
```

---

## 6. EVIDENCE — Facts Supporting Trust

### Current Implementation

**Status:** ⚠️ Partial (Unstructured)

**Current State:**
```php
VoterVerification
  verified_ip (hashed)
  verified_device_fingerprint_hash (hashed)
  notes (text, free-form)
```

**What Exists:**
- Officer notes (unstructured)
- Device fingerprint (hashed)
- IP address (hashed for privacy)

**What's Missing:**
- No document evidence tracking (passport, ID, etc.)
- No evidence type field
- No evidence expiry
- No evidence quality assessment

**Required Enhancement:**
```php
VerificationEvidence (new table)
  id
  verification_id (FK)
  evidence_type (enum: 'officer_attestation', 'document', 'government_id', 'address_proof')
  evidence_source (file path, document ID, etc.)
  evidence_quality (low, medium, high)
  captured_at
  expires_at (null = never)
```

---

## 7. DOMAIN EVENTS — Missing

### Current Implementation

**Status:** ❌ Missing

**Events That Should Be Emitted:**
- `IdentityAttested` (when verification created)
- `TrustLevelAssigned` (when trust level determined)
- `VerificationRevoked` (when verification withdrawn)
- `VerificationExpired` (when trust period elapses)
- `EligibilityGranted` (when participant becomes eligible)
- `EligibilityExpired` (when eligibility ends)
- `AuthorizationGranted` (when permission assigned)

**Current State:**
No domain events. Controllers emit no notifications.

**Required Enhancement:**
```php
// In VoterVerificationController:
VoterVerification created
  → emit IdentityAttestedEvent

VoterVerification revoked
  → emit VerificationRevokedEvent

// In Vote controller:
Vote cast
  → emit VoteCastedEvent

// etc.
```

---

## 8. TRUST POLICY — Policy Not Formalized

### Current Implementation

**Status:** ❌ Missing

**What Should Exist:**
```php
TrustPolicy (value object or entity)
  organisation_id
  for_process (voting, candidacy, delegation)
  minimum_trust_level (provisionally_trusted, officer_verified, etc.)
  required_evidence (list of evidence types)
  verification_expiry_days (null = never)
  re_verification_required (boolean)
```

**Current State:**
No explicit trust policy. Verification is binary (active or not).

**Usage:**
```php
// When evaluating eligibility:
if (election.requires_verification && !voter.verified) {
  // Implicit policy: "Must be verified"
  return ineligible
}

// But no way to say:
// "This election requires High Assurance verification"
// "This election re-verifies every 30 days"
// "This election requires government ID evidence"
```

---

## Summary Table

| Domain Concept | Current File | Status | Notes |
|---|---|---|---|
| Verified | VoterVerification | ✅ | Active/revoked tracking exists |
| Eligible | Member + Service | ✅ | Computed from membership state |
| Authorized | ElectionPolicy | ✅ | Role-based access control exists |
| Attestation | VoterVerificationController | ✅ | Officer decision recording exists |
| Trust Level | N/A | ❌ | Binary only, not multi-level |
| Evidence | VoterVerification | ⚠️ | IP/device only, no docs |
| Events | N/A | ❌ | No domain event publishing |
| Policy | N/A | ❌ | No explicit trust policy model |

---

## Implementation Gaps (Priority Order)

### High Priority (Affects Trust)
1. **Domain Events** — Enable governance to react to verification changes
2. **Trust Levels** — Support multiple organizations' requirements
3. **Trust Policy** — Formalize what each election/org requires

### Medium Priority (Improves Evidence)
4. **Evidence Tracking** — Document types, quality, expiry
5. **Re-verification Triggers** — Automatic or manual reminders

### Low Priority (Nice-to-Have)
6. **Trust Transitions** — Formalize allowed state transitions
7. **Appeal Process** — Document how to challenge revocation

---

**Last Updated:** May 30, 2026  
**Next:** TRUST_DOMAIN_BACKLOG.md for actionable items
