# Round 27A — Trust Attestation Aggregate Discovery

**Date:** 2026-06-08

**Phase:** Tactical DDD — Aggregate Discovery (Context 1 of 9)

**Status:** Complete — Awaiting ARB Review

---

## 1. Investigation Scope

**Candidate Context:** Trust Attestation

**Acceptance Status:** ACCEPTED (Round 25)
**Boundary Stability:** STABLE

**Evidence Sources:** ADR-001 (Trust Attestation Domain), ADR-002 (Verified ≠ Eligible ≠ Authorized), ADR-003 (Governance-Driven Revocation), UBIQUITOUS_LANGUAGE.md, TRUST_CHAIN.md, VoterVerification model, VoterVerificationPolicy.

---

## 2. Aggregate Candidate Validation

Each candidate is evaluated using the aggregate boundary test:

1. What business decision does this concept own?
2. What invariant does it protect?
3. What transactional consistency must be maintained?
4. What inconsistency becomes possible if it is split?
5. Could this concept instead be an entity, value object, policy, or supporting domain object?

---

### Candidate A: Verification

| Test | Assessment |
|------|------------|
| **Business decision owned** | "Is this identity trustworthy?" — the core trust decision |
| **Invariant protected** | One active verification per participant per organization; revocation must be attributed; verification decision is timestamped |
| **Transactional consistency required** | Verification status change (attested → revoked) must be atomic with officer attribution and timestamp |
| **If split** | Verification status could become inconsistent with revocation record |
| **Alternative classification** | None — this is the primary aggregate |

**Evidence for aggregate status:**
- Owns a unique business decision that no other candidate owns
- Has strong invariants (uniqueness, attribution, immutability of decision)
- ADR-001 and TRUST_CHAIN.md confirm it as the central trust decision
- VoterVerification model enforces active/inactive/revoked lifecycle

**Evidence against aggregate status:**
- None significant. Verification is the core aggregate.

**Result: HIGH CONFIDENCE — Aggregate**

---

### Candidate B: Trust Level

| Test | Assessment |
|------|------------|
| **Business decision owned** | None independently — trust level is assigned as part of verification |
| **Invariant protected** | Trust level progression follows defined path; expiration makes verification inactive |
| **Transactional consistency required** | Trust level change must be consistent with verification decision — they are always set together |
| **If split** | Trust level could be updated independently of verification status, creating inconsistency |
| **Alternative classification** | **Value Object within Verification** — trust level is an attribute of verification, not an independent decision |

**Evidence for aggregate status:**
- UBIQUITOUS_LANGUAGE.md defines Trust Level as a distinct concept
- Trust level has its own lifecycle (progression, expiry)

**Evidence against aggregate status:**
- No independent business decision — trust level is always assigned as part of verification
- Transactional consistency with Verification would be violated if split
- Current implementation has no explicit trust level (binary active/inactive)
- ADR-001 treats trust level as a property, not an aggregate

**Result: RECLASSIFY — Value Object within Verification aggregate**

---

### Candidate C: Attestation

| Test | Assessment |
|------|------------|
| **Business decision owned** | "Was evidence sufficient?" — this is a decision that accompanies verification, not independent of it |
| **Invariant protected** | Attestation must be attributed to an officer; must reference evidence reviewed; cannot be modified after recording |
| **Transactional consistency required** | Attestation and Verification decision must be consistent — they are made together in the same officer review |
| **If split** | Attestation could contradict verification decision (attestation says "insufficient" while verification says "attested") |
| **Alternative classification** | **Entity within Verification aggregate** — attestation records the "how and why" of a verification decision but does not own an independent decision |

**Evidence for aggregate status:**
- ADR-001 defines Attestation as: "An officer's formal statement that evidence has been reviewed and found sufficient"
- UBIQUITOUS_LANGUAGE.md defines Attestation as creating accountability
- Has its own invariants (attribution, evidence reference, immutability)

**Evidence against aggregate status:**
- No independent business decision — attestation accompanies a verification decision, it does not decide independently
- Transactional consistency with Verification would be violated if attestation could change independently of verification status
- Attestation cannot exist without a corresponding verification — they are made together
- The decision "was evidence sufficient" is part of the larger decision "is this identity trustworthy"

**Result: RECLASSIFY — Entity within Verification aggregate**

---

### Candidate D: EvidenceRecord

| Test | Assessment |
|------|------------|
| **Business decision owned** | None — evidence is input to a trust decision, not a decision itself |
| **Invariant protected** | Evidence must be timestamped; source must be recorded; cannot be modified after linkage |
| **Transactional consistency required** | Evidence does not require transactional consistency with Verification — evidence can be collected before a verification decision exists |
| **If split** | No inconsistency — evidence can exist independently of any specific verification |
| **Alternative classification** | **Supporting Domain Object or Entity** — evidence is input to trust decisions but does not own business decisions itself |

**Evidence for aggregate status:**
- UBIQUITOUS_LANGUAGE.md defines Evidence as a distinct concept
- Evidence quality hierarchy is documented
- Evidence can exist independently of attestation

**Evidence against aggregate status:**
- No business decision owned — evidence is information, not a decision
- Adversarial test: "Would the business lose any decision capability if EvidenceRecord disappeared?" Evidence would need to be re-collected, but no decision would be lost
- Evidence is always consumed by another aggregate (Verification), never acting as a decision boundary itself
- Can exist independently, but independence alone does not justify aggregate status

**Result: RECLASSIFY — Supporting Domain Object (Entity or Value Object)**

---

## 3. Aggregate Boundary Validation Matrix

| Candidate | Owns Decision? | Invariant Protection | Transactional Boundary | Aggregate? | Reclassification |
|-----------|---------------|---------------------|----------------------|------------|-----------------|
| Verification | ✅ Yes — trust decision | ✅ Strong | ✅ Atomic status change | **YES** | — |
| Attestation | ❌ Part of verification | ✅ Moderate | ⚠️ Should be consistent with verification | **NO** | **Entity within Verification** |
| EvidenceRecord | ❌ None — input only | ✅ Moderate | ❌ Independent of verification | **NO** | **Supporting Domain Object** |
| ParticipantTrust | ❌ Assigned, not decided | ✅ Moderate | ⚠️ Must be consistent with verification | **NO** | **Value Object within Verification** |

---

## 4. Decision Ownership Review

| Decision | Owned By | Aggregate or Value? |
|----------|----------|---------------------|
| Is this identity trustworthy? | **Verification** | Aggregate |
| What trust level is appropriate? | **Verification** (TrustLevel VO) | Value Object |
| Was evidence sufficient? | **Verification** (Attestation entity) | Entity |
| What evidence was reviewed? | **Verification** (references Evidence) | Entity reference |
| Should verification be revoked? | **Verification** | Aggregate decision |

All trust-related decisions are owned by a single aggregate: **Verification**.

---

## 5. Revised Aggregate Inventory

| Aggregate | Type | Confidence | Rationale |
|-----------|------|------------|-----------|
| **Verification** | Aggregate Root | **HIGH** | Owns the core trust decision; strong invariants; clear transactional boundary |
| Attestation | Entity (within Verification) | HIGH | Records how and why — no independent decision |
| TrustLevel | Value Object (within Verification) | MEDIUM | Assigned attribute — no independent lifecycle |
| EvidenceRecord | Supporting Domain Object | MEDIUM | Input to trust decisions — no decision ownership |

---

## 6. Consistency Boundary

```
Verification Aggregate (root)
    │
    ├── VerificationId (VO)
    ├── ParticipantId (VO)
    ├── TrustLevel (VO) — provisionally trusted / officer verified / organization verified / high assurance
    ├── VerificationStatus (VO) — active / revoked
    ├── Attestation[] (Entity collection)
    │       ├── OfficerId (VO)
    │       ├── AttestedAt (VO)
    │       ├── EvidenceReference[] (VO collection)
    │       └── Notes (VO)
    ├── RevocationRecord (VO, optional)
    │       ├── RevokedBy (VO)
    │       ├── RevokedAt (VO)
    │       └── Reason (VO)
    └── CreatedAt (VO)
```

**Transactional boundary:** The entire Verification aggregate (root + entities + value objects) must be consistent within a single transaction. A verification decision cannot be updated without its attestation, trust level, and status changing together.

---

## 7. Aggregate Discovery Debt

| Debt | Question | Priority |
|------|----------|----------|
| ADT-1 | Is EvidenceRecord a true domain entity with its own lifecycle, or is evidence simply a value object referenced by Attestation? | MEDIUM |
| ADT-2 | Should Attestation be an entity collection or a single immutable record within Verification? Current evidence suggests a single attestation per verification with revocation creating a new state, not a new attestation. | MEDIUM |
| ADT-3 | If future contexts (Eligibility, Authorization) reference TrustLevel independently, does that justify promoting it to its own aggregate? | LOW |

---

## 8. Confidence Assessment

| Concept | Evidence Strength | Decision Ownership | Boundary Clarity | Overall |
|---------|-----------------|-------------------|-----------------|---------|
| **Verification** (aggregate) | HIGH | HIGH | HIGH | **HIGH** |
| Attestation (entity) | HIGH | N/A (entity) | HIGH | HIGH |
| TrustLevel (VO) | MEDIUM | N/A (VO) | MEDIUM | MEDIUM |
| EvidenceRecord (domain object) | MEDIUM | N/A (input) | MEDIUM | MEDIUM |

---

## 9. Summary

**Proven aggregates:**
1. **Verification** (HIGH confidence) — the single aggregate within Trust Attestation

**Proven supporting domain objects:**
2. **Attestation** — entity within Verification aggregate
3. **TrustLevel** — value object within Verification aggregate
4. **EvidenceRecord** — supporting domain object, entity or value object

**Result:** 1 aggregate, 1 entity, 1 value object, 1 supporting domain object. All trust-related decisions are owned by a single aggregate with consistent invariants and transactional boundary.

---

**Round 27A Trust Attestation Aggregate Discovery (Revised) — READY FOR ARB REVIEW**

**1 proven aggregate (Verification). 3 supporting domain objects (Attestation entity, TrustLevel value object, EvidenceRecord supporting object). 3 aggregate discovery debt items identified.**
