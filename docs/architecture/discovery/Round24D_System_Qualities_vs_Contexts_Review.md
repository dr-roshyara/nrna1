# Round 24D — System Qualities vs. Bounded Contexts Review

**Date:** 2026-06-07

**Phase:** Pre-Decision Governance Support (Literature Integration)

**Status:** Complete

**Source:** "Bits or Paper: which should get to carry your vote?" — competing election system properties

---

## 1. Key Insight from Literature

The paper distinguishes between:

**System qualities** (cross-cutting, no single owner):
- Vote Secrecy
- Verifiability
- Ballot Box Integrity
- Transparency
- Trust Base
- Accessibility

**Operational capabilities** (specific business functions):
- Ballot issuance
- Voter authentication
- Vote casting
- Tallying
- Audit

The literature emphasizes that **system qualities compete with each other**:
- Secrecy vs. Auditability
- Privacy vs. Verifiability
- Trust vs. Transparency

These tensions must be balanced architecturally — they are not resolved by creating separate bounded contexts.

---

## 2. Classification Matrix

| Concept | Role | Context? | Evidence |
|---------|------|----------|----------|
| **Voting** | Business operation | ✅ Yes | Accepted candidate |
| **Eligibility** | Business operation | ✅ Yes | Accepted candidate |
| **Authorization** | Business operation | ✅ Yes | Accepted candidate |
| **Results/Tallying** | Observed election capability | Status: ARB Review Pending | Merger consideration |
| **Verifiability** | Quality attribute | ❌ Not a context | Achieved through Voting + Verification |
| **Verification (activities)** | Domain capability | ⚠️ Unresolved | Depends on universal verifiability requirement |
| **Audit** | Business operation / governance | ✅ Yes | Accepted candidate |
| **Privacy** | Quality attribute | ❌ No | Enforced across Voting, Audit |
| **Verifiability** | Quality attribute | ❌ No | Achieved through Voting + Verification |
| **Transparency** | Quality attribute | ❌ No | Cross-cutting |
| **Integrity** | Quality attribute | ❌ No | Achieved through checksums, evidence |
| **Trust** | Quality / governance | ❌ Not a context | Foundational property |
| **Accountability** | Quality / governance | ❌ Distributed | Audit + Arbitration + Governance |
| **Coercion Resistance** | Quality attribute | ❌ No | Not currently evidenced |

---

## 3. New ARB Question (Q9)

**Q9:** Is this candidate a business capability, a decision owner, a quality attribute, a governance constraint, or an architectural mechanism?

This classification prevents quality attributes (Privacy, Transparency, Trust) from being incorrectly modeled as bounded contexts.

---

## 4. Literature Sufficiency Assessment

**Election integrity literature:** Current literature appears sufficient for Round 25 Context Acceptance Review. Three papers independently validate discovered concepts. Additional literature may still be valuable for later phases (e.g., detailed aggregate design within specific contexts).

**Gap identified:** The remaining classification questions are **Strategic DDD questions**, not election-theory questions. The next useful reading is Evans/Vernon on bounded context criteria, subdomain classification, and aggregate design — at the appropriate phase.

---

**Round 24D complete. Classification matrix adds Q9 to ARB framework. Literature phase concluded. Next: ARB Context Acceptance Decisions (Round 25).**
