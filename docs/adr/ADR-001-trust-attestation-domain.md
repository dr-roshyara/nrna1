# ADR-001: Trust Attestation as Separate Domain

**Status:** Accepted  
**Date:** May 30, 2026  
**Deciders:** Domain Architecture Team

---

## Context

Public Digit manages elections where trust in voters is critical. The system must verify identities, determine eligibility, and grant permissions to participate.

Early analysis (Phase 1) discovered that the current system already separates these concerns:

```
Verified (identity trustworthy)
  ↓
Eligible (meets process requirements)
  ↓
Authorized (has permission for action)
```

However, there was no explicit bounded context capturing trust decisions. Instead, trust logic was intertwined with eligibility and authorization checks.

This created ambiguity:
- What does "verified" actually mean?
- Who is responsible for trust decisions?
- What happens when trust changes?
- How do trust decisions affect past votes?

---

## Decision

Establish **Trust Attestation** as a separate, first-class bounded context with exclusive responsibility for:

1. **Verification decisions** (officer attests identity)
2. **Trust levels** (assurance degrees: Provisionally Trusted, Officer Verified, Organization Verified, High Assurance)
3. **Evidence capture** (facts supporting trust decision)
4. **Revocation** (withdrawal of prior attestation)

The context explicitly does NOT decide:
- Whether someone can vote (Eligibility Context)
- Whether someone can perform actions (Authorization Context)
- Whether past votes remain valid (Governance Context)

---

## Rationale

### 1. Scalability Across Organizations

Public Digit will serve:
- Political parties (may require High Assurance verification)
- NGOs (may use Provisionally Trusted model)
- Unions (may use Organization Verified model)
- Cooperatives (may use Officer Verified model)

Separating Trust Attestation allows each organization to define trust policies without affecting eligibility or authorization logic.

### 2. Clarity of Intent

When code says `verified: true`, it's ambiguous:
- Verified for what? Voting? Candidacy? Delegation?
- What evidence supports this?
- Who decided?

When code says `TrustLevel: Officer Verified`, it's explicit:
- Identity has been attested by officer
- Based on officer review of evidence
- Valid for defined period/scope

### 3. Revocation Semantics

If verification is mixed with eligibility:
```
Member.is_verified = false
  └── What happens to past votes?
  └── What happens to current eligibility?
  └── What happens to election results?
```

With separate context:
```
Trust Attestation Context emits: VerificationRevokedEvent
  └── Eligibility Context: Re-evaluates future participation
  └── Governance Context: Decides impact on past votes/results
```

Clear separation means clear responsibility.

### 4. Bootstrap Trust (Prevents Infinite Loops)

Current system has a potential issue: "Who verifies the verifier?"

With Trust Attestation as separate context:

```
Officer gets authority from: Governance role assignment (not from verification)
  └── Organization assigns ElectionOfficer role
  └── Officer inherits authority to make trust decisions
  └── Officer does NOT need to be verified themselves
```

This breaks the recursion.

---

## Consequences

### Positive

✅ **Clear responsibility:** Trust Attestation owns trust decisions, not Eligibility or Authorization

✅ **Scalable policy:** Organizations can define `TrustPolicy` (min trust level, evidence requirements, expiry) independently

✅ **Explicit events:** `IdentityAttested`, `VerificationRevoked`, `TrustLevelAssigned` become domain events

✅ **Testable:** Trust decisions can be tested independently of voting/membership logic

✅ **Auditable:** Officer decisions are explicit, not implicit in domain state

### Negative

⚠️ **Increased complexity:** Three contexts instead of one monolithic User/Member model

⚠️ **Event orchestration:** Changes in one context may trigger changes in others (requires event handlers)

⚠️ **Implementation effort:** Requires refactoring current VoterVerification logic to explicit bounded context

---

## Implementation Evidence

### Current Code Aligns With Decision

The codebase already implements trust attestation implicitly:

```php
// Trust Attestation already exists:
VoterVerification (model, controller, policy)

// Eligibility already exists:
Member.voting_rights, EloquentVoterEligibilityQueryService

// Authorization already exists:
ElectionPolicy, CapabilityPolicy, permissions
```

Decision formalizes this implicit structure explicitly.

---

## Relationship to Other ADRs

- **ADR-002:** Verified ≠ Eligible ≠ Authorized (consequence of this decision)
- **ADR-003:** Governance-Driven Revocation (consequence of separating contexts)

---

**Last Updated:** May 30, 2026  
**Related Files:** docs/architecture/trust-domain/UBIQUITOUS_LANGUAGE.md, BOUNDED_CONTEXTS.md
