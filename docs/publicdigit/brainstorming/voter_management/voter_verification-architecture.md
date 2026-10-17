Yes. I would make the architecture **vendor-neutral and requirement-driven**, while implementing only the first concrete gate now: **personal identity verification**.

The key DDD principle should be:

> **The election defines which admission requirements apply; the implementation provides different verification mechanisms that can satisfy those requirements.**

That means we should not design `IdentityVerification`, `EmailVerification`, `WhatsAppVerification`, etc. as unrelated workflows. We should establish a stable **Voter Admission domain model** and plug concrete mechanisms into it.

## 1. Target DDD architecture

I recommend this conceptual model:

```text
                         ┌─────────────────────┐
                         │      Election       │
                         └──────────┬──────────┘
                                    │
                                    ▼
                     ┌──────────────────────────┐
                     │   Voter Admission Policy │
                     │                          │
                     │ What must be satisfied? │
                     └────────────┬─────────────┘
                                  │
                    ┌─────────────┼──────────────┐
                    ▼             ▼              ▼
              Identity        Contact       Organisation
              Requirement     Requirement   Requirement
                    │             │              │
                    ▼             ▼              ▼
              ┌──────────┐  ┌──────────┐   ┌──────────┐
              │ Identity │  │  Email   │   │ Officer  │
              │ Methods  │  │  Phone   │   │Approval  │
              └────┬─────┘  └────┬─────┘   └────┬─────┘
                   │             │              │
                   ▼             ▼              ▼
              Evidence       Evidence       Evidence
                   │             │              │
                   └─────────────┼──────────────┘
                                 ▼
                       ┌──────────────────┐
                       │ Admission        │
                       │ Decision         │
                       └────────┬─────────┘
                                ▼
                     ┌────────────────────┐
                     │ Voting Entitlement │
                     └────────────────────┘
```

### Important distinction

There are actually **three different concepts** here:

| Concept | Question |
|---|---|
| **Admission Requirement** | What must this voter satisfy? |
| **Verification Mechanism** | How is that requirement satisfied? |
| **Admission Evidence** | What proves that it was satisfied? |

For example:

```text
Requirement:
    Identity must be verified

Mechanism:
    Election officer verifies identity

Evidence:
    OfficerIdentityVerificationEvidence
```

Later:

```text
Requirement:
    Contact ownership must be verified

Mechanism:
    WhatsApp OTP

Evidence:
    PhoneOwnershipVerificationEvidence
```

The domain should not become dependent on WhatsApp.

---

# 2. Vendor-specific requirements

This is particularly important for your Publicdigit business model.

Different customers/vendors may require different admission models:

### Vendor A

```text
Imported voter
      ↓
Officer verifies identity
      ↓
Can vote
```

### Vendor B

```text
Imported voter
      ↓
Email verification
      ↓
Can vote
```

### Vendor C

```text
Imported voter
      ↓
Phone verification
      ↓
Officer verification
      ↓
Can vote
```

### Vendor D

```text
Imported voter
      ↓
eID verification
      ↓
Organisation approval
      ↓
Can vote
```

The **Election Admission Policy** should express this.

Conceptually:

```text
VoterAdmissionPolicy
    ├── requirements
    ├── satisfaction rules
    └── revalidation rules
```

Not:

```text
if vendor == X
    ...
else if vendor == Y
    ...
```

That would be the wrong abstraction.

---

# 3. Don't create a generic "Gate Engine" yet

This is important based on the architecture work we've already done.

I would **not** build:

```text
GateEngine
GateHandler
GateFactory
GenericGate
AbstractGate
GateRegistry
```

at this stage.

That would be speculative architecture.

Instead create the smallest stable domain abstraction:

```text
AdmissionRequirement
AdmissionEvidence
VoterAdmissionPolicy
AdmissionDecision
```

Then introduce additional mechanisms only when a real requirement needs them.

This follows a good DDD rule:

> **Generalize the domain concept, not the implementation before the domain requires it.**

---

# 4. First concrete requirement

For the first implementation, define:

### Requirement

```text
OfficerIdentityVerification
```

Meaning:

> An authorized election officer has verified that the registered voter is the person represented by the voter record.

This should be an **admission requirement**, not simply a UI button.

The button is merely one way to create the evidence.

---

# 5. First gate lifecycle

I would model the first slice like this:

```text
Voter imported
       │
       ▼
REGISTERED
       │
       │ identity verification required
       ▼
ADMISSION_PENDING
       │
       │ officer verifies identity
       ▼
IDENTITY_VERIFIED
       │
       ▼
ADMITTED
       │
       ▼
VOTING_ENTITLED
```

But I would **not necessarily add all those states to `ElectionMembership.status`**.

That is one of the important architectural decisions.

The admission state should preferably be **derived from membership + policy + evidence**.

So:

```text
ElectionMembership
        │
        │ registered voter
        ▼
VoterAdmissionPolicy
        │
        +
VoterAdmissionEvidence
        │
        ▼
AdmissionDecision
        │
        ▼
VotingEntitlement
```

This avoids recreating the current problem where:

```text
status = active
```

means too many different things.

---

# 6. Suspension

Your clarification fits naturally into the model.

Suspension should **not delete the voter**.

Instead:

```text
ADMITTED
   │
   │ suspension confirmed
   ▼
SUSPENDED
   │
   │ entitlement withdrawn
   ▼
NOT ENTITLED
```

and then:

```text
SUSPENDED
   │
   │ revalidation required
   ▼
REVALIDATION_REQUIRED
   │
   │ identity verified again
   ▼
READMITTED
   │
   ▼
VOTING_ENTITLED
```

So suspension is **not another permanent gate**.

It creates a **revalidation requirement**.

That distinction should be explicitly documented in the architecture.

---

# 7. Future extension model

Eventually we can have:

```text
AdmissionRequirement
│
├── IdentityVerification
│   ├── OfficerVerification
│   ├── eIDVerification
│   ├── VideoIdentification
│   └── ExternalIdentityProvider
│
├── ContactOwnershipVerification
│   ├── Email
│   ├── SMS
│   └── WhatsApp
│
├── OrganisationVerification
│   ├── OfficerApproval
│   ├── MembershipDatabase
│   └── SSO
│
└── Other requirements
```

But these are **future implementations**, not things we should build now.

---

# 8. IP verification should remain separate

I would explicitly record this architectural decision.

IP is not identity.

```text
Identity verification
    = Who is this person?

IP/network constraint
    = From where is the request coming?
```

Therefore:

```text
Admission
    └── Identity / Contact / Organisation requirements

Voting Security
    └── IP / Device / Session / Network constraints
```

They can both contribute to `VotingEntitlement`, but they belong to different concepts.

This will prevent the model from becoming a generic "verification" bucket.

---

# 9. Prompt for Claude Code — First Gate Implementation

I would give Claude Code the following prompt. It deliberately tells Claude **not to redesign the whole system** and to preserve the extensibility we want.


# Publicdigit — Implement Voter Identity Admission Gate v1

You are working on Publicdigit as a senior DDD architect and senior software engineer.

## Objective

Implement the **first voter admission requirement** for Election-Only elections:

> **Officer Identity Verification**

An imported/registered voter must not automatically become entitled to vote when the election's admission policy requires identity verification.

An authorized election officer must verify the voter's personal identity before the voter becomes voting-entitled.

This is the first concrete admission requirement. The architecture MUST remain extensible so that future vendor/election requirements can add other admission requirements such as:

- email ownership verification
- telephone ownership verification
- SMS OTP
- WhatsApp OTP
- electronic identity verification
- organisational approval
- external identity-provider verification

Do NOT implement those future mechanisms now.

---

# 1. Architectural principles

Use DDD and preserve the following separation:

```text
Election
    ↓
VoterAdmissionPolicy
    ↓
AdmissionRequirement
    ↓
AdmissionEvidence
    ↓
AdmissionDecision
    ↓
VotingEntitlement
```

The key concepts are:

### Admission Requirement

Defines WHAT must be satisfied.

Example:

```text
OfficerIdentityVerification
```

### Verification Mechanism

Defines HOW the requirement is satisfied.

For this slice:

```text
Authorized election officer manually verifies identity
```

### Admission Evidence

Records the FACT that the requirement was satisfied.

For example:

```text
VoterIdentityVerificationEvidence
```

### Admission Decision

Determines whether the voter currently satisfies all required admission requirements.

### Voting Entitlement

Determines whether the voter is currently allowed to exercise the vote.

Do not conflate these concepts.

---

# 2. Do NOT build a generic Gate Engine

Do NOT introduce speculative abstractions such as:

- GenericGate
- GateEngine
- GateFactory
- GateRegistry
- AbstractGateHandler
- GenericVerificationEngine

unless the existing code already requires such a construct.

The domain abstraction should remain deliberately small:

```text
VoterAdmissionPolicy
AdmissionRequirement
AdmissionEvidence
AdmissionDecision
VotingEntitlement
```

Generalize the domain concept, not the implementation.

---

# 3. Election-Only scope

The current implementation scope is:

```text
Election-Only
```

Do not redesign the full-membership flow.

Do not introduce unrelated membership changes.

Reuse existing shared components where appropriate, but keep this feature scoped to Election-Only.

---

# 4. Current architectural situation

Before changing code, inspect the repository and verify the current implementation.

Known relevant areas include:

- Election-Only voter import
- voter management UI
- existing Verify action
- `VoterVerification`
- `ElectionMembership`
- `User::isVoterInElection()`
- `EnsuresVoterMembership`
- `ElectionVotingController`
- vote submission
- election lifecycle
- election configuration/settings
- existing election audit functionality
- existing architecture tests

Do not assume the existing investigation is still completely accurate. Verify the actual repository state first.

---

# 5. Critical existing problem to solve

Currently, Election-Only import can create an active voter membership.

That must no longer automatically imply voting entitlement when identity verification is required by the admission policy.

The voting path must enforce the admission requirement.

It is not sufficient to:

- show a Verify button
- create a verification database row
- display "verified" in the UI

The actual vote authorization path MUST reject a voter who has not satisfied the configured admission requirements.

---

# 6. Establish one Voting Entitlement decision point

Inspect all current implementations of voter eligibility.

Known duplicated paths include:

- `User::isVoterInElection()`
- `EnsuresVoterMembership`
- `ElectionVotingController@start`
- vote submission
- cached and uncached variants

Unify the admission-related decision into one explicit domain/application concept.

For example:

```text
VotingEntitlement
```

or an equivalent existing project convention if one already exists.

The exact implementation is your architectural decision, but there MUST be one authoritative rule for:

> "Is this voter currently entitled to vote in this election?"

Avoid four independent implementations of the same business rule.

---

# 7. Admission policy

Introduce an election-owned admission policy.

The policy must be capable of representing:

```text
no admission requirements
```

and:

```text
one or more admission requirements
```

For the first implementation the only requirement is:

```text
OFFICER_IDENTITY_VERIFICATION
```

Prefer a requirement/value-object model rather than a collection of boolean columns such as:

```text
requires_identity_verification
requires_email_verification
requires_phone_verification
...
```

Do not create database columns for future verification methods that are not yet required.

A JSON/structured policy representation is acceptable if consistent with the existing persistence architecture.

For example:

```json
{
  "requires": [
    "OFFICER_IDENTITY_VERIFICATION"
  ]
}
```

An empty requirement set should preserve current backwards-compatible behaviour:

```json
{
  "requires": []
}
```

Meaning:

> Registration is sufficient for admission, subject to the existing voting/security rules.

Do not silently change existing elections unless explicitly required by migration/business rules.

---

# 8. Identity verification evidence

Reuse/evolve the existing `VoterVerification` concept if appropriate.

The existing verification record should represent evidence that:

> An authorized election officer verified the identity of this voter for this election.

At minimum the evidence should be associated with:

- election
- voter/user
- verifying officer
- verification timestamp

Preserve existing useful audit metadata such as:

- IP
- device information
- notes

where already supported.

Do not use IP/device information as proof of identity.

Those are separate security/audit dimensions.

---

# 9. Important distinction: identity verification vs security binding

Do NOT interpret:

```text
IP verification
fingerprint/device verification
```

as identity verification.

Existing:

```text
voter_verification_mode
```

or equivalent IP/device binding configuration must remain a separate security concept.

Do not overload it to implement admission.

The architecture must allow both concepts to coexist:

```text
Admission:
    Identity verified

Security:
    Request/device/network satisfies configured constraints
```

---

# 10. Verification authorization

Inspect the existing Verify endpoint and UI.

Ensure that the verifier:

1. is authenticated
2. has the required election officer/administrative authority
3. belongs to the correct organisation/election context
4. is allowed to perform the verification under the election lifecycle rules
5. can only verify a voter who actually belongs to that election

Do not allow:

```text
/arbitrary-user-id/verify
```

to create evidence for a user who is not an Election-Only voter in the target election.

---

# 11. Admission decision

Implement the rule conceptually as:

```text
isAdmitted(voter, election)
```

which evaluates the election's configured admission policy against the available evidence.

For the first version:

```text
if policy requires no requirements:
    admission = satisfied

if policy requires OFFICER_IDENTITY_VERIFICATION:
    admission = satisfied only if valid identity verification evidence exists
```

Do not store a second redundant boolean such as:

```text
voter.is_admitted = true
```

unless the existing architecture proves that a materialized state is required.

Prefer deriving admission from:

```text
policy + evidence + voter registration state
```

---

# 12. Voting entitlement

The final voting decision should conceptually be:

```text
VotingEntitlement =
    voter is registered
    AND voter has voter role
    AND voter is not removed
    AND voter is not suspended
    AND admission requirements are satisfied
    AND election is in a votable lifecycle state
    AND existing voting/security constraints are satisfied
```

Do not move anonymous ballot mechanics into VotingEntitlement.

Do not mix:

- voter identity
- ballot anonymity
- has-voted state
- ballot storage
- voter slug/code
- IP/device security

into the admission model.

---

# 13. Suspension / revalidation

Do NOT implement the entire suspension redesign in this task unless required to make the admission rule correct.

However, the architecture MUST leave room for:

```text
Admission satisfied
        ↓
Suspension confirmed
        ↓
Voting entitlement withdrawn
        ↓
Revalidation required
        ↓
Identity verification again
        ↓
Admission satisfied
```

Suspension does NOT remove the voter from the election's voter registry.

Do not model suspension as deleting the voter.

Do not model suspension as a permanent second admission requirement.

It is a conditional revalidation trigger.

If the current suspension implementation cannot safely support this yet, document the gap rather than introducing an unrelated large refactor.

---

# 14. Historical evidence

Do not destroy historical verification meaning when re-verification eventually occurs.

The architectural target is:

```text
Verification #1
    verifiedAt = T1
    verifiedBy = Officer A

Suspension

Verification #2
    verifiedAt = T2
    verifiedBy = Officer B
```

The current database may have limitations such as a unique `(election_id,user_id)` verification record.

If changing that schema is outside the minimal implementation scope, document it as a follow-up architectural item.

Do not pretend that overwriting historical evidence is equivalent to immutable protocol history.

---

# 15. Election lifecycle

Inspect the actual election lifecycle rules before implementation.

Determine exactly when officer identity verification is allowed.

Do not blindly allow verification in every lifecycle state.

In particular investigate:

- Draft
- SetupAdministration
- SetupNomination
- ReadyForVoting
- VotingActive
- Suspended
- Closed

If the existing product requirement permits verification during VotingActive, preserve that behavior unless there is a clear architectural/security reason to change it.

Document the decision.

---

# 16. Backward compatibility

Existing elections may not have an admission policy.

Define and document the migration/default behavior.

Recommended default:

```text
missing policy
    =
no additional admission requirement
```

unless the business requirements explicitly state otherwise.

Do not make existing voters unable to vote merely because the new configuration does not exist.

---

# 17. UI

Keep the existing voter management Verify action if possible.

The UI should communicate the domain state clearly.

Prefer concepts such as:

```text
Identity verification required
Identity verified
```

rather than introducing technical terms such as:

```text
Gate 1
Gate 2
```

The UI is not the domain model.

---

# 18. Tests

Add tests at the appropriate layers.

At minimum cover:

### Policy

```text
No requirements → admitted
Identity requirement + no evidence → not admitted
Identity requirement + valid evidence → admitted
```

### Authorization

```text
Unauthorized user cannot verify
Authorized officer can verify
Officer cannot verify non-voter
```

### Voting

```text
Registered + identity not verified → cannot vote
Registered + identity verified → can vote
```

### Regression

```text
Existing election with no admission requirements → existing behaviour preserved
```

### Election isolation

```text
Verification in election A must not satisfy election B
```

### Cache

If entitlement is cached:

```text
not verified
    ↓
verify
    ↓
cached entitlement must be invalidated
    ↓
voter can vote
```

Do not leave a stale negative entitlement decision after verification.

---

# 19. Architecture tests

Add or update architecture fitness tests so that future code does not bypass the model.

The following should be prevented conceptually:

```text
Controller directly deciding voting eligibility
```

or:

```text
Different controllers implementing different admission rules
```

Prefer:

```text
Controller
    ↓
Application service / policy
    ↓
VotingEntitlement
```

---

# 20. Deliverables

Before changing code:

1. Inspect the repository.
2. Identify the current voter registration/import flow.
3. Identify every voting entitlement implementation.
4. Identify the existing `VoterVerification` model and persistence.
5. Identify the existing election settings/configuration mechanism.
6. Identify lifecycle restrictions.
7. Identify current tests.
8. Produce a short implementation assessment.

Then implement the smallest coherent slice.

After implementation provide:

### A. Files changed

List every file and explain why.

### B. Domain model

Show:

```text
Election
  ↓
VoterAdmissionPolicy
  ↓
OfficerIdentityVerification
  ↓
VoterVerification / AdmissionEvidence
  ↓
AdmissionDecision
  ↓
VotingEntitlement
```

### C. Data model changes

Explain migrations/defaults/backward compatibility.

### D. Voting enforcement

Show exactly where the voting path now checks admission.

### E. Test results

Report exact tests executed and their results.

### F. Remaining architectural gaps

Especially identify:

- suspension/revalidation
- immutable verification history
- ProtocolAppend/protocol-grade evidence
- future email/phone/eID mechanisms
- lifecycle policy decisions
- cache invalidation

Do not silently implement unrelated improvements.

---

# 21. Final architectural constraint

The implementation must make this future evolution possible:

```text
Vendor A
    OfficerIdentityVerification

Vendor B
    EmailOwnershipVerification

Vendor C
    PhoneOwnershipVerification
    +
    OfficerIdentityVerification

Vendor D
    ElectronicIdentityVerification
    +
    OrganisationApproval

Vendor E
    EmailOwnershipVerification
    OR
    PhoneOwnershipVerification
```

without redesigning `ElectionMembership`, the voting controller, or the entire election domain.

The first implementation is therefore:

> **One concrete admission requirement, implemented on top of a vendor-neutral admission architecture.**

Do not implement future verification mechanisms now. Build the smallest domain model that allows them to be added later without breaking the first implementation.

This prompt is deliberately **architecture-first**. It tells Claude to inspect the actual repository before coding, unify the voting entitlement decision, implement only identity verification, and preserve the extension point for vendor-specific admission policies.

The most important design decision is that **“gate” is not the domain abstraction**. The domain speaks in terms of **requirements, evidence, admission decisions, revalidation, and voting entitlement**. That gives us much more room to evolve Publicdigit for different election vendors without creating a rigid gate framework.