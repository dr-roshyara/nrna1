# ADR-003: Governance-Driven Revocation

**Status:** Accepted  
**Date:** May 30, 2026  
**Deciders:** Domain Architecture Team

---

## Context

When verification is revoked (officer withdraws attestation), ambiguity emerges:

```
Verification revoked for Voter A
  ├── Does past vote remain valid?
  ├── Does election result change?
  ├── Does audit get reopened?
  └── What are the consequences?
```

If Trust Attestation Context decides these questions, it owns governance policy (scope creep).

If Governance Context answers, then Trust Attestation is just a data provider.

Phase 1-3 analysis discovered Public Digit **should separate** these concerns:

```
Trust Attestation Context
  └── Decides: Verification is revoked

Governance Context
  └── Decides: Impact on past votes, election results, audits
```

Currently, this separation is implicit. Revocation is recorded but consequences are undefined.

---

## Decision

Establish explicit boundary:

### Trust Attestation Context Responsibility
```
Officer reviews evidence
  └── Decides: Verify or revoke
  └── Emits: VerificationRevokedEvent
  └── Updates: Verification status to revoked
  └── Does NOT decide: Consequences
```

### Governance Context Responsibility
```
Receives: VerificationRevokedEvent
  ├── Evaluates: Should past votes be invalidated?
  ├── Decides: Should election be challenged?
  ├── Decides: Should audit be reopened?
  ├── Decides: What notification goes to members?
  └── Implements: Governance policy consequences
```

### What Revocation Does NOT Automatically Do
```
❌ Invalidate past votes
❌ Change election results
❌ Reopen audits
❌ Void certifications
❌ Affect other voters
```

These are governance decisions, made by policy, not by verification logic.

---

## Rationale

### Separation of Concerns

**Bad Model (Verification owns governance):**
```
VoterVerification.revoke()
  ├── Invalidates past votes
  ├── Voids election results
  ├── Reopens audits
  └── Revocation logic couples trust to governance
```

Problem: Verification domain becomes responsible for election legitimacy.

**Good Model (Governance owns policy):**
```
VoterVerification.revoke()
  └── Emits: VerificationRevokedEvent

GovernancePolicy.handleVerificationRevoked(event)
  ├── Evaluate: Fraud? Duplicate registration? Good-faith error?
  ├── Check: How many votes affected?
  ├── Decide: Election still valid? Results change?
  └── Execute: Governance decision (e.g., "election is challenged")
```

Benefit: Each domain owns its logic.

### Real-World Example

**Scenario:** Officer discovers voter was registered twice

```
Time 1 (Day 1):
  Officer verifies Voter A for Election 2026
    └── Verification: Officer Verified ✓
  Voter A votes
    └── Vote recorded ✓

Time 2 (Day 5):
  Officer discovers Voter A has duplicate accounts
    └── Officer revokes verification for duplicate account
    └── Emits: VerificationRevokedEvent

Governance receives event:
  ├── Question 1: Was the duplicate account used to vote?
  │   └── Yes or No?
  ├── Question 2: Should Election 2026 result be invalidated?
  │   └── Governance decides: Yes (fraud detected)
  │       or No (only one account voted)
  └── Question 3: What's the consequence?
      └── Governance decides: Challenge election
                          or Recount votes
                          or Archive results with notation
```

**Critical:** Verification Context detects the problem. Governance Context decides impact.

---

## Consequences

### Positive

✅ **Clear responsibility:** Trust decides what happened. Governance decides what to do about it.

✅ **Testable:** Revocation can be tested independently of governance consequences

✅ **Auditable:** Decision trail is explicit (revocation + governance decision)

✅ **Flexible:** Different organizations can have different revocation policies without changing Trust Attestation code

✅ **Democratic:** Governance is policy-driven, not technical surprise

### Negative

⚠️ **Async coordination:** Revocation event must be handled by governance (eventual consistency)

⚠️ **Undefined behavior:** Without governance policy, revocation consequences are ambiguous

⚠️ **Complexity:** Requires governance policy framework (separate ADRs needed)

---

## Implementation Guidance

### Current Implementation

```php
// Trust Attestation Context:
VoterVerification.revoke(officer_id)
  ├── revoked_by = officer_id
  ├── revoked_at = now()
  ├── active = false
  └── return (does not emit event yet)

// Governance Context (missing):
// (No explicit governance policy handling revocation)
```

### Required Enhancement

```php
// Trust Attestation Context (no change):
VoterVerification.revoke(officer_id)
  ├── revoked_by = officer_id
  ├── revoked_at = now()
  └── emit VerificationRevokedEvent

// Governance Context (new):
class VerificationRevokedHandler {
  handle(VerificationRevokedEvent $event) {
    // Get revocation details
    $verification = $event->verification;
    
    // Evaluate impact
    $affectedVotes = Vote::where('voter_id', $event->voter_id)
                         ->where('election_id', $verification->election_id)
                         ->count();
    
    // Decide policy consequence
    if ($affectedVotes > 0) {
      // Governance policy: flag for review
      Election::log($verification->election_id, "Verification revoked for voter; $affectedVotes votes affected");
    }
    
    // Governance decides whether to:
    // - Challenge election
    // - Recount
    // - Invalidate
    // - Archive with notation
    // etc.
  }
}
```

---

## What This ADR Does NOT Decide

```
❌ Specific governance policies (handled by separate ADRs)
❌ How to handle different revocation reasons (policy decision)
❌ Whether elections are always valid after revocation (policy decision)
❌ Whether past votes can be invalidated (policy decision)
```

This ADR establishes the **boundary**. Governance ADRs will define the **policy**.

---

## Relationship to Other ADRs

- **ADR-001:** Trust Attestation Domain (prerequisite for this decision)
- **ADR-002:** Verified ≠ Eligible ≠ Authorized (consequence of this decision)

---

## Cross-Domain Communication

```
Trust Attestation Context
  └── Emits: VerificationRevokedEvent
      ├── voter_id
      ├── election_id
      ├── revoked_by (officer)
      ├── revoked_at
      └── reason (fraud, duplicate, etc.)

Governance Context (listener)
  └── Receives event
      ├── Queries: How many votes affected?
      ├── Checks: Election still running?
      ├── Evaluates: Impact severity
      └── Executes: Policy consequence
```

---

**Last Updated:** May 30, 2026  
**Related Files:** docs/architecture/trust-domain/BOUNDED_CONTEXTS.md, TRUST_CHAIN.md
