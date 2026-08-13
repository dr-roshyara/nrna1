# ADR-002: Verified ≠ Eligible ≠ Authorized

**Status:** Accepted  
**Date:** May 30, 2026  
**Deciders:** Domain Architecture Team

---

## Context

When building election systems, it's tempting to model trust as a single property:

```
Member
  is_verified
  can_vote
  can_nominate
  can_delegate
```

This conflates three separate decisions into one aggregate. Problems emerge quickly:

1. **Ambiguity:** What does `is_verified = true` mean?
   - Verified for voting? For candidacy? For delegation?

2. **Inability to scale:** Different elections have different requirements
   - Election A: "Must be Officer Verified"
   - Election B: "Provisionally Trusted is sufficient"

3. **Revocation complexity:** When verification changes, what happens?
   - Past votes?
   - Election results?
   - Audit trails?

Phase 1 analysis discovered Public Digit **already separates** these:

```
Verified (identity is trusted) — Trust Attestation Context
Eligible (meets process requirements) — Eligibility Context
Authorized (has permission to act) — Authorization Context
```

But the separation was implicit, not documented.

---

## Decision

Explicitly establish three orthogonal decisions:

### 1. Verified
**Definition:** Officer has attested that identity can be trusted.

**Responsibility:** Trust Attestation Context

**Enabled By:** Officer review of evidence

**Scope:** Identity itself, not process-specific

**Example:**
```
Officer verifies voter based on membership application
Result: Verified (Officer Verified trust level)
```

### 2. Eligible
**Definition:** Participant meets requirements for a specific process.

**Responsibility:** Eligibility Context

**Enabled By:** Process-specific rules (membership status, fees, timing, scope)

**Scope:** Process-specific (different for Voting vs. Candidacy vs. Delegation)

**Example:**
```
Is this voter eligible for Election 2026?
Check: Active membership? Paid fees? Full member type? Enrolled? Window open?
Result: Eligible (for this election)
```

### 3. Authorized
**Definition:** Participant has permission to perform a specific action.

**Responsibility:** Authorization Context

**Enabled By:** Role assignment + permission grants

**Scope:** Action-specific

**Example:**
```
Can this person cast a vote?
Check: Role is Voter? Action is Cast Vote? Scope is this election?
Result: Authorized
```

---

## Rationale

### Why Separation Matters

**Example 1: High-Assurance Election**
```
Voted before?
  ├── Verified: High Assurance ✓
  ├── Eligible: Active + Paid ✓
  ├── Authorized: Voter role ✓
  └── Result: Can vote

Verification expires (re-verification required by policy):
  ├── Verified: Expired ✗
  ├── Eligible: Still Active + Paid ✓
  ├── Authorized: Still Voter role ✓
  └── Result: Cannot vote (must re-verify)

Later, governance decides past votes were valid:
  ├── Past vote: Remains in system
  ├── Election result: Unchanged
  └── Governance: Records decision (not Verification domain)
```

**Example 2: Eligibility Change (Fees Unpaid)**
```
Voted in Election A:
  ├── Verified: Officer Verified ✓
  ├── Eligible: Paid + Active ✓
  └── Result: Vote counted

Now fees are unpaid:
  ├── Verified: Officer Verified ✓ (still true)
  ├── Eligible: Unpaid ✗ (changed)
  └── Result: Cannot vote in Election B

Can you challenge Election A result?
  ├── No. Eligibility was true at vote time.
  ├── Governance decides consequence, not Eligibility.
```

### Why Orthogonality Prevents Bugs

If verification, eligibility, and authorization were combined:

```
❌ Bad:
Member
  is_verified
  can_vote (computed from is_verified + membership)
  
Problem: Changing is_verified affects can_vote retroactively
         Unclear what "verified for voting" even means
```

With orthogonal decisions:

```
✅ Good:
Member
  verification_status
  eligibility_status (computed from membership at check time)
  authorized_actions (computed from roles)

Clear: Each decision has explicit responsibility
       Changes are localized to their domain
       Retroactive impact requires governance decision
```

---

## Consequences

### Positive

✅ **Clarity:** Each decision is explicit and testable independently

✅ **Scalability:** Organizations can require different trust levels without affecting eligibility logic

✅ **Auditability:** When something changes, it's clear which domain changed it

✅ **Flexibility:** Revocation of verification doesn't automatically block all future participation

### Negative

⚠️ **Complexity:** Must reason about three decisions instead of one boolean

⚠️ **Coordination:** Authorization depends on both Verified AND Eligible AND Permission (three conditions)

⚠️ **Events:** Changes in one domain may require updates in others (eventual consistency)

---

## Authorization Formula

The complete authorization model:

```
CanPerformAction(actor, action) =
  Verified(actor) &&
  Eligible(actor, process) &&
  Permission(actor, action, scope)
```

All three must be true. Missing any one → no authorization.

---

## Current Implementation Evidence

Code already implements this separation:

```php
// Verified: VoterVerification + VoterVerificationPolicy
if ($voter->verification && $voter->verification->isActive()) {
  // Trust is established
}

// Eligible: Member voting_rights + EloquentVoterEligibilityQueryService
if ($member->voting_rights !== 'none') {
  // Eligibility is established
}

// Authorized: ElectionPolicy + CapabilityPolicy
if ($this->authorize('vote', $election)) {
  // Authorization is established
}
```

Decision formalizes the implicit separation.

---

## Relationship to Other ADRs

- **ADR-001:** Trust Attestation Domain (prerequisite for this decision)
- **ADR-003:** Governance-Driven Revocation (consequence of orthogonal decisions)

---

**Last Updated:** May 30, 2026  
**Related Files:** docs/architecture/trust-domain/UBIQUITOUS_LANGUAGE.md, TRUST_CHAIN.md

---

## Clarification (2026-08-13 — PO/ARB ruling; status annotation only, decision text above unchanged)

The PO accepted the following clarification verbatim (*"I accept sentences (i) and (ii) as written"*, Decision A / AD-2 closure; recorded in `docs/publicdigit/reviews/2026-08-13-election-only-governance-decision-package.md` §5d):

> **(i)** Voting-time voter entitlement is owned by the Election context; its resolution derives organisational scope from the election itself; ambient organisation context is a forbidden dependency for this resolution.
> **(ii)** This ADR's "Eligibility Context" responsibility is clarified to govern **admission-time (process-requirements) evaluation**; the voting-time entitlement decision of clause (i) is **Election-context-owned** and is not an "Eligible" evaluation in this ADR's sense.

This annotation resolves the ambiguity between this ADR's process-scoped "Eligible" (whose examples — membership status, fees, timing — describe requirements evaluation) and the later-adopted durable election entitlement model (Model B / `PBDIGIT-68`, `EM-ENT-001`…`007`, `EM-GOV-001`). It authorizes no implementation.
