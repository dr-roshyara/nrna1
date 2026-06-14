# Round 24A — Election Integrity Guarantee Map

**Date:** 2026-06-07

**Phase:** Governance-Level Guarantee Mapping (D42B Resolution Support)

**Status:** Complete — Awaiting ARB Review

**Purpose:** Map discovered evidence from Rounds 17-23 against formal election guarantee categories from literature. This is a governance exercise to clarify D42B, not an architecture or design activity.

---

## 1. Privacy Guarantees

### 1.1 Anonymity

**Definition:** Votes cannot be linked to individual voters. No one (including election officials) can determine how a specific person voted.

**Evidence from Rounds 17-23:**

| Evidence | Source | Classification |
|----------|--------|---------------|
| No `user_id` column in votes table | ADR_20260203 (Voting Security), Stream 3 | ✅ **Explicitly Supported** |
| Vote hash provides cryptographic unlinkability | BaseVote, Stream 3 | ✅ **Explicitly Supported** |
| Result records have no `user_id` | ADR_20260203, Stream 3 | ✅ **Explicitly Supported** |
| `save_vote()` does not accept or store `user_id` | ADR_20260203 implementation | ✅ **Explicitly Supported** |
| Security audit checklist verifies no user_id in votes/results | ADR_20260203, lines 465-480 | ✅ **Explicitly Supported** |
| Receipt hash can verify vote was recorded without revealing choice | BaseVote.verifyByReceipt, Stream 3 | ✅ **Explicitly Supported** |
| Participation proof verifies voting without revealing choice | BaseVote.proveParticipation, Stream 3 | ✅ **Explicitly Supported** |

**Candidate contexts potentially responsible:** Voting (primary), Audit (privacy in logging)

**Assessment:** Anonymity is the best-documented guarantee in the entire system. It was an explicit architectural mandate from the earliest ADR and is enforced at the database, model, and service levels. Multiple verification methods preserve anonymity while still allowing audit.

---

### 1.2 Receipt-Freeness

**Definition:** Voters cannot prove how they voted to third parties. This prevents vote buying and coercion by ensuring voters cannot produce evidence of their vote choice.

**Evidence from Rounds 17-23:**

| Evidence | Source | Classification |
|----------|--------|---------------|
| Voter receives receipt_hash for self-verification | BaseVote, Stream 3 | ⚠️ **Partially Contradicts** |
| Receipt verifies vote was recorded but not vote choice | BaseVote.verifyByReceipt, Stream 3 | ⚠️ **Partially Supports** |
| No explicit receipt-freeness requirement in governance documents | ADRs, Round 18 | ❌ **Not Addressed** |
| No discussion of receipt-freeness as design goal | All examined governance sources | ❌ **Not Addressed** |

**Analysis:** The system provides voters with receipt hashes for verification purposes. This is a verifiability mechanism, but it creates a potential receipt-freeness concern — a voter could show their receipt hash as proof of having voted. However, the receipt hash does NOT reveal vote choice (the paper confirms this is the correct trade-off: verifiability requires some evidence). The system does not document any explicit requirement for or against receipt-freeness.

**Candidate contexts potentially responsible:** Voting (receipt generation), Verification (receipt verification)

**Assessment:** Receipt-freeness is not explicitly addressed in governance documents. The current receipt mechanism prioritizes verifiability over receipt-freeness — a legitimate design trade-off. Whether this is acceptable depends on organizational requirements.

---

### 1.3 Coercion Resistance

**Definition:** Voters cannot be coerced into voting a particular way, nor can they prove to a coercer how they voted. Stronger than receipt-freeness — includes protection against coercion during the voting process itself.

**Evidence from Rounds 17-23:**

| Evidence | Source | Classification |
|----------|--------|---------------|
| Vote anonymity prevents proof-of-vote-choice | ADR_20260203, Stream 3 | ⚠️ **Partially Supports** |
| No explicit coercion resistance mechanism found | All examined sources | ❌ **Not Addressed** |
| No multi-channel verification or dummy voting | Not observed | ❌ **Not Observed** |
| No discussion of coercion as threat model | Governance documents | ❌ **Not Addressed** |
| Device fingerprint tracking could theoretically enable coercion | Stream 3 | ⚠️ **Potential Concern** |

**Candidate contexts potentially responsible:** Voting (anonymity mechanisms), Trust Attestation (verification process)

**Assessment:** Coercion resistance is not addressed as a governance requirement. The anonymity mechanisms provide basic protection (voter cannot prove vote choice), but no advanced coercion resistance mechanisms exist. This may be acceptable for the organization's threat model but is undocumented.

---

## 2. Verifiability Guarantees

### 2.1 Individual Verifiability

**Definition:** Individual voters can verify that their own vote was correctly recorded and included in the final tally.

**Evidence from Rounds 17-23:**

| Evidence | Source | Classification |
|----------|--------|---------------|
| Voter self-verification via receipt hash | BaseVote.verifyByReceipt, Stream 3 | ✅ **Explicitly Supported** |
| Code-based verification of vote ownership | BaseVote.verifyByCode, Stream 3 | ✅ **Explicitly Supported** |
| VoterSlugStep tracks voter's 5-step workflow | Stream 3, VoterSlugStep model | ⚠️ **Partially Supports** |
| Vote receipt sent to voter after completion | ADR_202602033, Stream 3 | ✅ **Explicitly Supported** |
| Receipt hash comparison enables verification without exposing vote | BaseVote.verifyByReceipt, lines 291-294 | ✅ **Explicitly Supported** |
| Voter can verify vote was counted (via result publication) | ResultController, Stream 3 | ✅ **Explicitly Supported** |
| Voter cannot retrieve specific vote after submission (intentional for anonymity) | ADR_20260203, limitations section | ⚠️ **Design Trade-off** |

**Candidate contexts potentially responsible:** Voting (receipt generation, code verification), Results/Tallying (count inclusion verification), Verification (cross-check)

**Assessment:** Individual verifiability is reasonably well-supported. Voters can verify their vote was recorded (via receipt hash) and included (via published results). The anonymity constraint means voters cannot retrieve their specific vote after submission, which is a documented design trade-off. The mechanisms exist but may not meet formal E2E-V standards without additional infrastructure.

---

### 2.2 Universal Verifiability

**Definition:** Any observer (not just individual voters) can verify that all votes were correctly counted and the published result is correct. This typically requires cryptographic proofs, public bulletin boards, or independent audit mechanisms.

**Evidence from Rounds 17-23:**

| Evidence | Source | Classification |
|----------|--------|---------------|
| Result verification via count comparison | Vote.verifyResultsIntegrity, Stream 3 | ⚠️ **Partially Supports** (internal only) |
| Governance replay infrastructure exists but deferred | GovernanceReplayService, Stream 4, Stream 6B | ⚠️ **Partially Supports** (not operational) |
| Integrity checksum verification (SHA256) | Vote.calculateChecksum, Stream 3 | ⚠️ **Internal only** |
| No public bulletin board or external verifier | Not observed | ❌ **Not Observed** |
| No cryptographic proofs of correct tally | Not observed | ❌ **Not Observed** |
| No independent verification mechanism for third parties | Not observed | ❌ **Not Observed** |
| AuditElectionLog and SecurityEventRecorder record actions but are internal | Stream 4 | ⚠️ **Internal only** |
| GovernanceDecisionSnapshot provides integrity-verified records | Stream 6B | ⚠️ **Internal only** |

**Candidate contexts potentially responsible:** Governance Evidence Replay (replay verification), Audit (record provision), Results/Tallying (count publication)

**Assessment:** Universal verifiability is the weakest guarantee in the current system. The infrastructure for evidence preservation (checksums, replay envelopes, governance snapshots) exists but is internal — no external party can independently verify election outcomes. The GovernanceEvidenceReplay infrastructure is conceptually aligned with universal verifiability but is deferred and not publicly accessible. This is the most significant gap identified by the mapping exercise.

---

## 3. Additional Integrity Guarantees (Observed in System)

### 3.1 Eligibility Integrity

**Definition:** Only eligible voters can participate, and each eligible voter can participate only once.

**Evidence from Rounds 17-23:**

| Evidence | Source | Classification |
|----------|--------|---------------|
| ElectionConstitution preconditions check eligibility | Stream 5, ConstitutionalTransitionGuard | ✅ **Explicitly Supported** |
| ParticipationEligibilityEvidence frozen at evaluation time | TrustPolicyEvaluator, Stream 1 | ✅ **Explicitly Supported** |
| Vote hash uniqueness constraint (one vote per code) | BaseVote, Stream 3 | ✅ **Explicitly Supported** |
| Device fingerprint duplicate detection | Vote.hasDuplicateDevice, Stream 3 | ✅ **Explicitly Supported** |
| VoterSlugStep tracks step progression | Stream 3 | ✅ **Explicitly Supported** |
| Vote_hash ensures cryptographic uniqueness | BaseVote, Stream 3 | ✅ **Explicitly Supported** |

**Candidate contexts potentially responsible:** Eligibility (primary), Voting (enforcement), Trust Attestation (verification prerequisite)

**Assessment:** Eligibility integrity is well-supported through multiple mechanisms — constitutional preconditions, frozen evidence, cryptographic uniqueness, device fingerprinting, and step tracking.

---

### 3.2 Vote Integrity

**Definition:** Once recorded, votes cannot be modified, deleted, or lost without detection.

**Evidence from Rounds 17-23:**

| Evidence | Source | Classification |
|----------|--------|---------------|
| SHA256 checksum over candidate data | Vote.calculateChecksum, Stream 3 | ✅ **Explicitly Supported** |
| Checksum verification | Vote.verifyChecksum, Stream 3 | ✅ **Explicitly Supported** |
| Data integrity validation | Vote.verifyResultsIntegrity, Stream 3 | ✅ **Explicitly Supported** |
| Soft deletes (vote not permanently removed) | BaseVote SoftDeletes trait | ⚠️ **Partially Supports** |
| Encrypted vote data stored | BaseVote, Stream 3 | ✅ **Explicitly Supported** |
| Results regenerable from vote JSON source of truth | Vote.syncResults, Stream 3 | ✅ **Explicitly Supported** |

**Candidate contexts potentially responsible:** Voting (primary), Audit (integrity monitoring)

**Assessment:** Vote integrity is well-supported through checksums, encryption, and the ability to regenerate results from source data. Deleted votes are soft-deleted, preserving audit capability.

---

### 3.3 Result Integrity

**Definition:** Election results accurately reflect all valid votes.

**Evidence from Rounds 17-23:**

| Evidence | Source | Classification |
|----------|--------|---------------|
| Result count verification against expected count | Vote.verifyResultsIntegrity, Stream 3 | ✅ **Explicitly Supported** |
| Results can be regenerated from vote JSON source | Vote.syncResults, Stream 3 | ✅ **Explicitly Supported** |
| Publication gating ensures no premature release | ResultController, Stream 3 | ✅ **Explicitly Supported** |
| Vote counts computed on-demand from results table | ResultController SQL aggregation, Stream 3 | ⚠️ **Partially Supports** |
| No stored tally or materialized view | ResultController, Stream 3 | ⚠️ **Design characteristic** |
| Counting state exists in state machine | ADR-003, Stream 5 | ⚠️ **Constitutional meaning exists; operational realization unresolved** (D39) |

**Candidate contexts potentially responsible:** Results/Tallying (primary), Voting (source data), Constitutional Governance (publication gate)

**Assessment:** Result integrity is supported through source-of-truth regeneration, count verification, and publication gating. The close coupling to Voting and lack of an independent counting process are design characteristics, not necessarily weaknesses. D39 remains relevant for understanding whether counting is a separate business activity.

---

### 3.4 Auditability

**Definition:** Complete and tamper-evident records exist for all election activities, enabling post-election review and dispute resolution.

**Evidence from Rounds 17-23:**

| Evidence | Source | Classification |
|----------|--------|---------------|
| ElectionAuditLog records all state changes with old/new values | Stream 4, ElectionAuditLog model | ✅ **Explicitly Supported** |
| ElectionAuditService per-voter step tracking | Stream 4, ElectionAuditService | ✅ **Explicitly Supported** |
| SecurityEventRecorder logs trust evaluation events | Stream 4, SecurityEventRecorder | ✅ **Explicitly Supported** |
| Governance decision snapshots with integrity hashes | Stream 6B, GovernanceDecisionSnapshot | ✅ **Explicitly Supported** |
| GovernanceReplayService for evidence replay | Stream 4, Stream 6B | ✅ **Explicitly Supported** |
| GovernanceEvidenceReplay infrastructure for divergence detection | Stream 4, Stream 6B | ✅ **Explicitly Supported** |
| Email masking for privacy in audit logs | Stream 4, ElectionAuditService | ✅ **Explicitly Supported** |
| Log rotation at 100MB | Stream 4, ElectionAuditService | ⚠️ **Operational detail** |

**Candidate contexts potentially responsible:** Audit (primary), Governance Evidence Replay (verification), Governance (decision recording)

**Assessment:** Auditability is very well-supported. The system has comprehensive operational audit trails, governance replay capability, and integrity-verified decision snapshots. The separation between operational audit (active) and governance replay (deferred) is clear.

---

## 4. Evidence Mapping Summary

| Guarantee | Classification | Primary Candidate(s) | Confidence |
|-----------|---------------|---------------------|------------|
| **Anonymity** | ✅ Explicitly Supported | Voting | HIGH |
| **Receipt-Freeness** | ❌ Not Addressed | Voting | MEDIUM |
| **Coercion Resistance** | ❌ Not Addressed | Voting, Trust Attestation | MEDIUM |
| **Individual Verifiability** | ✅ Explicitly Supported | Voting, Verification | HIGH |
| **Universal Verifiability** | ❌ Not Observed | Governance Evidence Replay (deferred) | HIGH |
| **Eligibility Integrity** | ✅ Explicitly Supported | Eligibility, Voting | HIGH |
| **Vote Integrity** | ✅ Explicitly Supported | Voting | HIGH |
| **Result Integrity** | ✅ Explicitly Supported | Results/Tallying, Voting | HIGH |
| **Auditability** | ✅ Explicitly Supported | Audit, Governance Evidence Replay | HIGH |

---

## 5. D42B Impact Assessment

### What This Mapping Reveals

D42B asks: "What election integrity guarantees are intended by the system?"

The mapping reveals that **the answer is mixed**:

**Guarantees that are explicitly supported (and likely intended):**
- Anonymity (best-documented)
- Individual Verifiability (well-supported)
- Eligibility Integrity (well-supported)
- Vote Integrity (well-supported)
- Result Integrity (well-supported)
- Auditability (well-supported)

**Guarantees that are not addressed (and may or may not be intended):**
- Receipt-Freeness — system provides receipts as verifiability mechanism; trade-off appears intentional
- Coercion Resistance — no evidence of requirement

**Guarantees that are not observed (and clearly not yet implemented):**
- Universal Verifiability — infrastructure exists but is deferred and internal

### Impact on Candidate Contexts

| Context | D42B Impact | Assessment |
|---------|-------------|------------|
| **Voting** | HIGH — Whether Verification is separate depends on verifiability scope | Individual verifiability is present; universal verifiability would likely require additional Verification capabilities |
| **Governance Evidence Replay** | MEDIUM — Universal verifiability may elevate this from deferred to priority | If universal verifiability is required, Governance Evidence Replay becomes a priority context |
| **Audit** | LOW — Audibility is well-supported regardless of guarantee scope | No change to boundary |
| **Verification (potential context)** | HIGH — Would require explicit guarantee requirements | Currently a function within Voting; guarantee scope determines whether it becomes a separate context |

### D42B Updated Classification

**Previous status:** Partially Resolved

**New status:** **Substantially Clarified**

**What is now known:**
- Six guarantees are explicitly or implicitly supported with high confidence
- Two guarantees (receipt-freeness, coercion resistance) are not addressed — may be organizational policy decisions
- One guarantee (universal verifiability) is not observed — may be a future requirement
- The existing infrastructure for Governance Evidence Replay is conceptually aligned with universal verifiability even if not yet operational

**What remains unknown:**
- Whether universal verifiability is an intended future requirement
- Whether receipt-freeness is a concern for the organization
- What threat model (if any) exists for coercion

---

## 6. Open Governance Questions

**Q1:** Is universal verifiability a future requirement, or is individual verifiability sufficient for the organization's governance model?

**Q2:** Is receipt-freeness a concern, or is the current approach (voter can verify but cannot prove vote choice) acceptable?

**Q3:** Does the organization have a documented threat model that includes coercion resistance?

**Q4:** Should the Governance Evidence Replay infrastructure be elevated to support universal verifiability, or is it sufficient as an internal governance mechanism?

**Q5:** Are the six explicitly supported guarantees (anonymity, individual verifiability, eligibility, vote integrity, result integrity, auditability) sufficient for the organization's election integrity requirements?

---

## 7. Implications for Future Context Discovery

*Observations only — not architectural decisions.*

1. **Universal verifiability**, if required, would likely strengthen the case for Governance Evidence Replay as a separate context and may justify a public verification mechanism.

2. **Individual verifiability** does not require a separate Verification context — it is currently handled within Voting. A separate Verification context would only be warranted if universal verifiability or third-party verification is required.

3. **Receipt-freeness** is a policy decision, not a technical one. If required, it would affect the Voting context's receipt mechanism.

4. **These observations do not change current candidate context proposals.** They inform the confidence and priority assigned to each candidate during ARB deliberation.

---

**Round 24A Election Integrity Guarantee Map — READY FOR ARB REVIEW**

**D42B substantially clarified. Six guarantees confirmed as supported. One guarantee (universal verifiability) not yet implemented. Two guarantees (receipt-freeness, coercion resistance) not addressed. Impact on candidate contexts documented but no boundary changes proposed.**
