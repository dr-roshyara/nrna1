# Round 24F — Requirement-to-Candidate Mapping (Q10)

**Date:** 2026-06-07

**Phase:** Pre-Acceptance ARB Lens (Final)

**Status:** Complete

---

## 1. Purpose

Add one final ARB evaluation criterion (Q10) before Round 25: for each candidate bounded context, identify which constitutional/election requirement it primarily protects. This provides an additional lens for acceptance review — not a new discovery stream.

---

## 2. ARB Question Q10

**Q10:** Which constitutional/election requirement does this candidate primarily protect?

This question tests whether each candidate maps to a meaningful election requirement. Candidates that protect no clear requirement may not warrant bounded context status.

---

## 3. Requirement-to-Candidate Mapping

| Election Requirement | Primary Candidate(s) | Supporting Candidate(s) | Status |
|---------------------|---------------------|------------------------|--------|
| **Vote Integrity** | Voting | Results/Tallying, Audit | ✅ Context exists |
| **Secrecy / Anonymity** | Voting | Audit (privacy in logging) | ✅ Context exists |
| **Eligibility** | Eligibility | Trust Attestation, Voting (enforcement) | ✅ Context exists |
| **Uniqueness** | Voting (hash constraint) | Eligibility | ✅ Context exists |
| **Authorization Control** | Authorization | Constitutional Governance | ✅ Context exists |
| **Individual Verifiability** | Voting | Results/Tallying | ✅ Context exists |
| **Universal Verifiability** | Potentially supported by Governance Evidence Replay, Audit, and related capabilities | — | ⚠️ Context classification unresolved |
| **Accountability** | Audit | Governance Evidence Replay, Arbitration | ✅ Context exists |
| **Legitimacy** | Arbitration/Legitimacy | Constitutional Governance | ⚠️ Boundary unresolved |
| **Fairness** | Constitutional Governance | — | ✅ Context exists |
| **Auditability** | Audit | Governance Evidence Replay | ✅ Context exists |
| **Archiving** | Constitutional Governance (archive state) | Audit | ✅ Context exists |
| **Availability** | System quality — no single context | — | ❌ Not a context |
| **Usability / Accessibility** | System quality — no single context | — | ❌ Not a context |
| **Understandability** | System quality — no single context | — | ❌ Not a context |

---

## 4. Candidates Without Clear Requirement Protection

| Candidate | Requirement(s) Protected | Assessment |
|-----------|------------------------|------------|
| **Challenge/Dispute** | Accountability (distributed) | Evidence currently suggests distributed responsibility. Final classification remains an ARB decision. |
| **Results/Tallying** | Vote Integrity, Individual Verifiability | Shares requirements with Voting. Whether this supports merger, separation, or reclassification remains an ARB decision. |

---

## 5. Impact on Round 25

**No candidates are added or removed by this mapping.** The mapping confirms that all currently proposed candidates protect at least one meaningful election requirement. Three requirements (Availability, Usability, Understandability) are system qualities with no single context — consistent with Round 24D/24E conclusions.

---

## 6. Requirement Coverage Gaps

The following election requirements have no clearly accepted candidate owner:

| Requirement | Gap | Priority |
|-------------|-----|----------|
| **Universal Verifiability** | Ownership unresolved — Governance Evidence Replay is aligned but deferred and boundary unresolved | HIGH |
| **Coercion Resistance** | Not evidenced in any candidate context | MEDIUM |
| **Receipt-Freeness** | Not evidenced — current receipt mechanism prioritizes verifiability | MEDIUM |
| **Long-Term Secrecy** | Not evidenced — no cryptographic protection against future decryption | LOW |

---

## 7. Impact on Round 25

The requirement-to-candidate mapping confirms that all currently proposed candidates protect at least one meaningful election requirement. Four requirements have no clearly accepted owner, with universal verifiability being the most significant gap. The ARB may consider whether this gap affects acceptance decisions for related candidates (Governance Evidence Replay, Verification).

---

**Round 24F complete. All literature lenses closed. Ready for Round 25 — ARB Context Acceptance Review.**
