# Round 27B — Eligibility Aggregate Discovery

**Date:** 2026-06-08

**Phase:** Tactical DDD — Aggregate Discovery (Context 2 of 9)

**Status:** Complete — Awaiting ARB Review

---

## 1. Investigation Scope

**Candidate Context:** Eligibility

**Acceptance Status:** ACCEPTED (Round 25)
**Boundary Stability:** STABLE

**Evidence Sources:** ADR-002 (Verified ≠ Eligible ≠ Authorized), TRUST_CHAIN.md, Stream 5 (Constitutional preconditions), Stream 1 (ParticipationEligibilityEvidence), TrustPolicyEvaluator (buildEligibilityEvidence), ElectionMembership model, Round 18 Governance Review.

---

## 2. Context Legitimacy Review

**Question:** Does a bounded context require a primary aggregate to be legitimate?

**Answer:** No. A bounded context is defined by ownership of a unique business decision and its supporting model — not by the presence of a traditional aggregate root.

Eligibility owns the decision: "Can this participant participate in this specific process at this time?" This is a major business decision. Its implementation is computational (policies, specifications, domain services) rather than state-based, but this does not diminish its legitimacy as a bounded context.

**Examples of computational domain contexts:**
- Fraud detection — anomaly scoring with no persistent aggregate
- Pricing — rate calculation from multiple inputs
- Risk assessment — policy-driven evaluation

Eligibility follows this pattern. It is a legitimate bounded context driven by policies and computation, not by aggregate state changes.

---

## 3. Decision Ownership Analysis

### Eligibility's Unique Decisions

| Decision | Owner | Notes |
|----------|-------|-------|
| **Can this participant participate in this process at this time?** | **Eligibility** | The core eligibility decision — unique to Eligibility |
| What voting rights does this participant have? | Eligibility (computed) | Derived from membership state at time of evaluation |
| Is the participant enrolled for this election? | **Unresolved** — see Enrollment Ownership Analysis below | |
| Is eligibility evidence sufficient? | Eligibility (via policy) | Determines SUFFICIENT vs INSUFFICIENT evidence |

### Decisions NOT owned by Eligibility

| Decision | Actual Owner | Evidence |
|----------|-------------|----------|
| Is this identity trustworthy? | Trust Attestation | ADR-002 confirmed |
| Is this participant an active member? | Membership/Party domain (external) | Membership status, fee status, type — all external |
| Is this participant authorized to perform this action? | Authorization | ADR-002 confirmed |

### Decision Chain

```
Trust Attestation owns:  "Is identity trustworthy?"
                         ↓ (prerequisite)
Eligibility owns:        "Can this participant participate?"
                         ↓ (prerequisite)
Authorization owns:      "Is this action permitted?"
```

Each decision is independent. Each decision is owned by a separate context. Each context is legitimate regardless of whether its primary implementation is aggregate-based or computation-based.

---

## 4. Enrollment Ownership Analysis

**Candidate:** ElectionEnrollment

**Question:** Does ElectionEnrollment belong to Eligibility or Voting?

### Arguments for Eligibility ownership

| Evidence | Source |
|----------|--------|
| Enrollment is a prerequisite for voting — gating concern | Stream 5 (has_voters precondition) |
| Enrollment status (active/suspended) affects eligibility evaluation | ElectionMembership model |
| Enrollment lifecycle begins before voting (setup phase) | Stream 5 (setup_administration → setup_nomination → voting) |
| Enrollment tracks participant registration | Stream 3, VoterSlugStep |

### Arguments for Voting ownership

| Evidence | Source |
|----------|--------|
| Enrollment lifecycle (enroll → verify → vote → complete) tracks the voting workflow | Stream 3, VoterSlugStep |
| VoterSlugStep (5 steps: code_entry, agreement, vote_selection, verification, completion) is voting workflow, not eligibility workflow | Stream 3 |
| Step progression happens during voting, not during eligibility determination | Stream 3 |
| Enrollment state changes when vote is completed (hasVoted flag) | Stream 3, VoteController |

### Cross-Context Nature

Enrollment appears to span both contexts:
- **Eligibility** cares about: is this participant enrolled for this election?
- **Voting** cares about: what is the participant's enrollment step progression? has this participant completed voting?

This suggests Enrollment may be a concept shared across contexts, or it may cleanly belong to Voting with Eligibility referencing it. The enrollment lifecycle is primarily driven by voting workflow, not eligibility evaluation.

**Assessment:** ElectionEnrollment likely belongs to Voting. Eligibility owns the evaluation (can this participant participate?) but Voting owns the enrollment state (is this participant enrolled and progressing through steps?). This boundary will be confirmed during Voting aggregate discovery (Round 27F).

---

## 5. Revised Aggregate Inventory

| Concept | Type | Confidence | Rationale |
|---------|------|------------|-----------|
| **EligibilityEvaluation** | **Domain Service (stateless)** | HIGH | Core eligibility decision — computational, policy-driven, no stored state. This IS the context's primary responsibility. |
| **EligibilityPolicy** | **Specification / Policy** | HIGH | Rules that determine eligibility (membership status, fee status, type checks, timing constraints). Encapsulates eligibility logic. |
| **ParticipationEligibilityEvidence** | **Value Object** | HIGH | Frozen, hashed, immutable observational snapshot |
| **VotingRights** | **Computation (derived value)** | HIGH | Computed at evaluation time from membership state |
| **ElectionEnrollment** | **Likely Voting aggregate** | MEDIUM | Enrollment lifecycle is primarily voting workflow. Will be confirmed in Round 27F. |
| **Membership** | **External (Membership/Party domain)** | HIGH | Data source, not owned by Eligibility |

---

## 6. Context Legitimacy Summary

**Is Eligibility a legitimate bounded context without a primary aggregate?**

**Yes.** Eligibility owns a unique business decision (eligibility determination) that no other context owns. Its implementation is a stateless decision model using policies, specifications, and domain services. This is a legitimate pattern for computational domain contexts.

The bounded context is defined by decision ownership, model boundaries, and language boundaries — not by the presence of a traditional aggregate root.

---

## 7. Aggregate Discovery Debt

| Debt | Question | Priority |
|------|----------|----------|
| ADE-1 | Does ElectionEnrollment belong to Voting or Eligibility? Current evidence suggests Voting, to be confirmed in Round 27F. | HIGH |
| ADE-2 | Should EligibilityEvaluation be a stateless Domain Service, or are there eligibility decisions that require persistent state (e.g., manual eligibility overrides, exception cases)? | MEDIUM |

---

## 8. Summary

| Concept | Type | Confidence |
|---------|------|------------|
| EligibilityEvaluation | **Domain Service** (stateless decision) | HIGH |
| EligibilityPolicy | **Specification / Policy** | HIGH |
| ParticipationEligibilityEvidence | **Value Object** | HIGH |
| VotingRights | **Computation** | HIGH |
| ElectionEnrollment | **Likely Voting aggregate** | MEDIUM (to be confirmed) |
| Membership | **External dependency** | HIGH |

**Key finding:** Eligibility is a legitimate bounded context defined by computational decision ownership, not by aggregate state. Its primary responsibility (eligibility determination) is implemented through policies and domain services. The only potential aggregate in scope (ElectionEnrollment) likely belongs to Voting.

---

**Round 27B Eligibility Aggregate Discovery (Revised) — READY FOR ARB REVIEW**

**0 aggregates within Eligibility. 1 Domain Service (core decision model). 1 Specification/Policy. 1 Value Object. 1 external dependency. 2 aggregate discovery debt items. Eligibility is recognized as a legitimate computation-based bounded context.**
