# Trust Chain Decision Flow

**Status:** Current Implementation  
**Date:** May 30, 2026  
**Scope:** How trust decisions flow through Public Digit system

---

## The Trust Chain

Public Digit's trust architecture follows a compositional decision flow:

```
Identity
    ↓
(Officer reviews evidence)
    ↓
Verification Decision
    ↓
Trust Level Assigned
    ↓
Eligibility Evaluated
    (membership, fees, type, timing, scope)
    ↓
Authorization Granted
    (role, action, scope, duration)
    ↓
Action Executed
    (vote cast, membership approved, etc.)
```

**Critical:** Each step is independent. Passing one step does not guarantee passing the next.

---

## Step 1: Identity Established

### What Happens
System has User record for person.

### Code Evidence
```php
User (Authenticatable)
  ├── email
  ├── name
  ├── password_hash
  ├── created_at
```

### Sufficient For
- Platform login
- Membership application submission

### Insufficient For
- Voting
- Candidacy
- Delegation
- Any privileged action

---

## Step 2: Officer Reviews Evidence

### What Happens
Verification Officer examines proof of identity.

### Evidence Types (Current Implementation)
1. **Membership Application Data** — applicant-provided (form fields, free-text)
2. **Officer Attestation Context** — IP address, device fingerprint, officer notes
3. **External Documents** — if organization requires (not formalized in current system)

### Code Evidence
```php
VoterVerificationController.store()
  ├── Officer reviews evidence (UI form)
  ├── Officer decides: verified or not verified
  ├── System captures:
  │   ├── verified_by (officer user_id)
  │   ├── verified_at (timestamp)
  │   ├── verified_ip (hashed for privacy)
  │   ├── verified_device_fingerprint_hash
  │   └── notes
  └── VoterVerification record created
```

### Officer Authority
**Bootstrap Model:**
```
Organization assigns ElectionOfficer role
  └── Officer inherits authority to make verification decisions
  └── Officer does NOT need to be verified themselves
  └── Authority comes from role, not from verification
```

---

## Step 3: Verification Decision Made

### What Happens
Officer explicitly attests or withholds verification.

### Decision States
- `Attested` — officer verified identity, Trusted Level assigned
- `Withheld` — officer could not verify, participant ineligible
- `Revoked` — previously attested verification withdrawn

### Code Evidence
```php
VoterVerification
  ├── active (boolean, true if attested)
  ├── revoked_by (user_id if revoked, null otherwise)
  ├── revoked_at (timestamp if revoked, null otherwise)

VoterVerificationPolicy.evaluate()
  └── if verification_required && !verification.isComplete
        return CapabilityDecision.prohibited("Voter verification required...")
      else
        return null (abstain, let other policies decide)
```

### Consequence
- **If Attested:** Proceed to Eligibility evaluation
- **If Withheld:** Block participation; participant ineligible
- **If Revoked:** See Revocation Impact below

---

## Step 4: Trust Level Assigned

### What Happens
System determines degree of assurance based on evidence quality and organization policy.

### Trust Level Values (Policy-Dependent)
- `Unverified` — no trust decision made
- `Provisionally Trusted` — identity plausible, awaiting confirmation
- `Officer Verified` — single officer attestation
- `Organization Verified` — multiple officers or documentation
- `High Assurance` — government-issued evidence
- `Revoked` — trust explicitly withdrawn

### Code Evidence (Current System)
```php
// Current system does not distinguish trust levels explicitly
// Verification is binary: active (trusted) or not active (not trusted)

VoterVerification
  ├── active (all that exists currently)
  └── implicit trust level = "Officer Verified" (single officer attestation)

// Future enhancement: explicit trust_level field
VoterVerification
  ├── trust_level ('provisionally_trusted', 'officer_verified', 'organization_verified', 'high_assurance', 'revoked')
  └── trust_level_assigned_at (timestamp of evaluation)
```

### Policy Control
```
Different organizations can require different trust levels:

Election A (political party)
  └── Minimum: Officer Verified

Election B (NGO)
  └── Minimum: Provisionally Trusted (lower barrier)

Election C (Union)
  └── Minimum: High Assurance (government ID required)
```

---

## Step 5: Eligibility Evaluated

### What Happens
System checks whether participant meets requirements for specific process.

### Eligibility Rules (Current Implementation)
```php
Member.voting_rights attribute:
  if active_status && (paid || exempt)
    if membership_type == 'full'
      return 'full'
    else
      return 'voice_only'
  else
    return 'none'

Voter eligibility (election-specific):
  if voting_rights == 'full'
    if enrolled_for_election
      if voting_window_open
        return eligible
```

### Code Evidence
```php
Vote/Create controller:
  uses EnsuresVoterMembership trait
    ├── checks Member exists
    ├── checks Member.voting_rights
    ├── checks Voter record for election
    ├── checks voting window
    └── returns eligible or not_eligible

EloquentVoterEligibilityQueryService.isEligible(member, election)
  ├── member.status == 'active' ?
  ├── member.fees_status in ['paid', 'exempt'] ?
  ├── voter.election_id present ?
  └── election voting window open ?
  return boolean
```

### Key Rule
**Eligibility is COMPUTED, not STORED**

Eligibility is derived fresh from Member state each time, not cached as aggregate state.

### Consequence
- **If Eligible:** Proceed to Authorization check
- **If Ineligible:** Block participation; cannot vote
- **If Eligibility Changes:** Automatically reflected on next check (fees unpaid, membership expired, voting window closed, etc.)

---

## Step 6: Authorization Granted

### What Happens
System verifies participant has permission for specific action in specific scope.

### Authorization Formula
```
Can(actor, action) =
  Verified(actor) AND
  Eligible(actor, process) AND
  Permission(actor, action, scope)
```

All three conditions must be true.

### Code Evidence
```php
Vote controller:
  before action can proceed:
    ├── TrustPolicyEvaluator.evaluate()
    │   ├── checks device fingerprint continuity
    │   ├── checks network (IP) continuity
    │   ├── checks verification status
    │   └── returns TrustEvaluationEnvelope
    ├── $this->authorize('vote', $election)
    │   ├── checks ElectionPolicy.vote()
    │   └── checks capability system
    └── if all pass: action allowed

Role-based authorization:
  Chief Officer
    ├── can manage election
    ├── can publish results
  Deputy Officer
    ├── can manage election
    ├── cannot publish results
  Commissioner
    ├── view-only access
  Voter
    ├── can cast vote
```

### Consequence
- **If Authorized:** Proceed to action execution
- **If Not Authorized:** Block action, return 403/Unauthorized

---

## Step 7: Action Executed

### What Happens
Participant performs action (cast vote, approve candidacy, etc.).

### Code Evidence
```php
Vote submission:
  if all previous checks passed
    ├── record vote
    ├── mark voter as having voted
    ├── store encrypted ballot
    ├── emit VoteCastEvent
    └── return success

Membership approval:
  if officer authorized
    ├── approve application
    ├── create Member record
    ├── emit MemberApprovedEvent
    └── return success
```

### Audit Trail
```
VoterSlugStep records progression:
  ├── step_1: code_entry (timestamp, ip)
  ├── step_2: agreement (timestamp)
  ├── step_3: vote_selection (timestamp, selected candidates)
  ├── step_4: vote_verification (timestamp)
  └── step_5: completion (timestamp)
```

---

## Revocation Impact (Governance-Driven)

### When Revocation Occurs
Officer withdraws attestation: `VoterVerification.revoke()`

### What Revocation Affects
**Immediately:**
- Future eligibility checks will see revoked status
- Future authorization will fail (verification required but incomplete)

**Does NOT Immediately Affect (Governance decides):**
- Past votes remain in system (governance policy)
- Election results remain valid (governance policy)
- Audit trails remain intact (governance policy)
- Appeal or challenge process (governance policy)

### Code Evidence
```php
VoterVerification.revoke(officer_user_id)
  ├── revoked_by = officer_user_id
  ├── revoked_at = now()
  ├── active = false
  └── emit VerificationRevokedEvent

Future vote attempts:
  VoterVerificationPolicy.evaluate()
    └── if verification_required && revoked
          return CapabilityDecision.prohibited("Verification revoked...")

Past votes:
  // Remain unchanged in votes table
  // System does not retroactively invalidate
  // Governance context handles any election legitimacy questions
```

### Revocation Does NOT Determine
```
❌ Whether past votes are valid
❌ Whether election results are legitimate
❌ Whether audits need reopening
❌ Whether consequences apply
```

Those are governance questions, not verification questions.

---

## The Complete Flow: Example

### Scenario: Officer Verifies Voter in Election

```
1. User logs in as Voter
   └── Identity established (User exists)

2. Voter enters election voting page
   └── System checks: Verification required?

3. Officer reviews Voter's membership application
   └── Officer examines: name, email, fee status, membership type

4. Officer decides: Verified
   └── Officer attestation recorded with IP, device fingerprint, notes
   └── Trust Level: Officer Verified
   └── IdentityAttested event emitted

5. System evaluates eligibility
   ├── Active member? YES
   ├── Fees paid? YES
   ├── Member type: Full? YES
   ├── Enrolled for election? YES
   ├── Voting window open? YES
   └── Result: Eligible

6. System grants authorization
   ├── Voter role present? YES (implicit from eligibility)
   ├── Action: Cast Vote? YES (permitted for Voter role)
   └── Result: Authorized

7. Voter casts vote
   ├── Ballot recorded (encrypted)
   ├── Receipt hash generated
   ├── marked_as_voted = true
   ├── VoteCastedEvent emitted
   └── Audit trail recorded

8. (Later) Officer revokes verification
   ├── Reasons: Found evidence of fraud / duplicate registration / etc.
   ├── revoked_by = officer_id
   ├── revoked_at = now()
   ├── VerificationRevokedEvent emitted
   └── Past vote: remains in system, governance decides impact
```

---

## Key Architectural Principles

### 1. Verification Does Not Grant Rights
```
Verified
  ├── enables eligibility evaluation
  ├── does NOT enable voting
  ├── does NOT enable candidacy
  ├── does NOT enable delegation
```

### 2. Each Step Is Independent
```
Verified + Ineligible = Cannot participate
Verified + Eligible + Unauthorized = Cannot act
```

### 3. Revocation Is Localized
```
Revocation affects:
  ├── Future verification checks
  └── Future eligibility evaluation

Revocation does NOT affect:
  ├── Past votes (governance decides)
  ├── Election legitimacy (governance decides)
  ├── Audit validity (governance decides)
```

### 4. Bootstrap Trust
```
Officer authority = governance role assignment
  └── Does NOT require officer to be verified
  └── Authority is granted, not earned through verification
```

---

**Last Updated:** May 30, 2026  
**Domain:** Trust Attestation  
**Next:** Map to current implementation and identify gaps
