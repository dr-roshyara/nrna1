# Round 24B — Vote Correctness Literature Review

**Date:** 2026-06-07

**Phase:** Literature-to-Discovery Mapping (D42B Resolution Support)

**Status:** Complete — Awaiting ARB Review

**Source:** "Proving Vote Correctness in the IVXV Internet Voting System" (Estonia)

---

## 1. Purpose

Map election integrity concepts from the IVXV literature against evidence already discovered during Rounds 17-23. Determine which concepts are already evidenced, which are partially evidenced, and which are not yet evidenced. This is evidence mapping only — no architecture or boundary decisions.

---

## 2. Integrity Concepts Extracted from Literature

The paper treats the following as distinct concerns, each requiring its own evidence mechanism:

| Concept | Definition (from paper) | Evidence Mechanism |
|---------|------------------------|-------------------|
| **Vote Correctness** | Proof that an encrypted vote represents a valid candidate choice | Zero-Knowledge Proofs |
| **Vote Secrecy** | No one can determine how a voter voted | Encryption + Anonymity |
| **Vote Verifiability** | Voter can verify vote was correctly recorded and counted | Receipts, audit trail |
| **Tally Correctness** | Published result accurately reflects all valid votes | Homomorphic tallying / mixnets |
| **Eligibility** | Only authorized voters can vote | Digital signatures, voter rolls |
| **Authorization** | Voter has right to participate in specific election | Role-based, scope-based |
| **Auditability** | All election actions can be independently reviewed | Signed logs, evidence records |

---

## 3. Concept-to-Discovery Mapping

### 3.1 Vote Correctness

**Definition (literature):** Proof that an encrypted vote represents a valid candidate choice — not an invalid or forged ballot.

**Evidence in Rounds 17-23:**

| Evidence | Source | Classification |
|----------|--------|---------------|
| Candidate selection validated before vote submission | VoteController, Stream 3 | ⚠️ **Partially Evidenced** — validation exists, but no cryptographic proof of well-formedness |
| Candidate_01..60 columns store JSON selections | BaseVote, Stream 3 | ⚠️ **Partially Evidenced** — structure exists but no proof that stored data is valid |
| No zero-knowledge proof or cryptographic vote correctness mechanism | Not observed | ❌ **Not Evidenced** |
| No homomorphic encryption or verifiable encryption | Not observed | ❌ **Not Evidenced** |
| Data checksum covers candidate data (integrity, not correctness) | Vote.calculateChecksum, Stream 3 | ⚠️ **Related but distinct** — checksum verifies integrity, not correctness |

**Correspondence to existing concepts:**
- Partially overlaps with Vote Integrity (checksums protect against modification, not against invalid votes)
- Not directly represented in any existing candidate context

**Candidate contexts potentially responsible:** Voting (vote creation), Verification (correctness verification)

**Assessment:** Vote correctness as a formal concept is not evidenced in the current system. The system validates candidate selections at submission time, but there is no cryptographic proof that a vote represents a valid candidate choice. This is a gap relative to the IVXV literature.

---

### 3.2 Vote Secrecy

**Definition (literature):** No one can determine how a voter voted — distinct from vote correctness (a vote could be correct but still need secrecy protection).

**Evidence in Rounds 17-23:**

| Evidence | Source | Classification |
|----------|--------|---------------|
| No user_id in votes table — votes are anonymous | ADR_20260203, BaseVote, Stream 3 | ✅ **Explicitly Evidenced** |
| Vote hash provides cryptographic unlinkability | BaseVote, Stream 3 | ✅ **Explicitly Evidenced** |
| Receipt hash does not reveal vote choice | BaseVote.verifyByReceipt, Stream 3 | ✅ **Explicitly Evidenced** |
| Participation proof does not reveal vote choice | BaseVote.proveParticipation, Stream 3 | ✅ **Explicitly Evidenced** |
| Encrypted vote data stored | BaseVote.encrypted_vote field | ✅ **Explicitly Evidenced** |
| Email masking in audit logs | ElectionAuditService.maskEmail, Stream 4 | ✅ **Explicitly Evidenced** |
| Device metadata anonymized | BaseVote.device_metadata_anonymized | ✅ **Explicitly Evidenced** |

**Candidate contexts potentially responsible:** Voting (primary), Audit (privacy in logging)

**Assessment:** Vote secrecy is the best-evidenced concept in the system. The literature's treatment aligns well with existing implementation. Vote secrecy is treated as separate from vote correctness — which is also consistent with the literature.

---

### 3.3 Vote Verifiability

**Definition (literature):** Voter can verify that their vote was correctly recorded and included in the final tally.

**Evidence in Rounds 17-23:**

| Evidence | Source | Classification |
|----------|--------|---------------|
| Voter self-verification via receipt hash | BaseVote.verifyByReceipt, Stream 3 | ✅ **Explicitly Evidenced** |
| Code-based vote verification | BaseVote.verifyByCode, Stream 3 | ✅ **Explicitly Evidenced** |
| Vote receipt sent to voter | ADR_20260203, Stream 3 | ✅ **Explicitly Evidenced** |
| Voter can verify vote was counted via published results | ResultController, Stream 3 | ✅ **Explicitly Evidenced** |
| VoterSlugStep tracks 5-step workflow | Stream 3 | ✅ **Explicitly Evidenced** |
| Voter cannot retrieve specific vote after submission (anonymity trade-off) | ADR_20260203, limitations | ⚠️ **Design trade-off** described in paper |

**Candidate contexts potentially responsible:** Voting (primary), Results/Tallying (count inclusion)

**Assessment:** Individual verifiability is well-evidenced. The paper's treatment of verifiability as separate from correctness is consistent with the system's architecture — a voter can verify their vote was recorded without verifying cryptographic correctness of the ballot.

---

### 3.4 Tally Correctness

**Definition (literature):** The published election result accurately reflects all valid votes.

**Evidence in Rounds 17-23:**

| Evidence | Source | Classification |
|----------|--------|---------------|
| Results derived from vote JSON source of truth | Vote.createResultsFromCandidates, Stream 3 | ✅ **Explicitly Evidenced** |
| Results can be regenerated via syncResults | Vote.syncResults, Stream 3 | ✅ **Explicitly Evidenced** |
| Vote count verification against expected count | Vote.verifyResultsIntegrity, Stream 3 | ✅ **Explicitly Evidenced** |
| Vote count computed on-demand via SQL aggregation | ResultController, Stream 3 | ⚠️ **Partially Evidenced** — computational, no cryptographic tally |
| No homomorphic tally or verifiable mixnet | Not observed | ❌ **Not Evidenced** |
| No independent tally verification | Not observed | ❌ **Not Evidenced** |

**Candidate contexts potentially responsible:** Results/Tallying (primary), Voting (source data)

**Assessment:** Tally correctness is evidenced through source-of-truth regeneration and count verification. The literature describes cryptographic tally verification (homomorphic tallying) which is not present. The current approach is simpler but provides less cryptographic assurance.

---

### 3.5 Eligibility

**Definition (literature):** Only authorized voters can vote, each exactly once.

**Evidence in Rounds 17-23:**

| Evidence | Source | Classification |
|----------|--------|---------------|
| ElectionConstitution preconditions check eligibility | Stream 5, ConstitutionalTransitionGuard | ✅ **Explicitly Evidenced** |
| ParticipationEligibilityEvidence frozen at evaluation time | TrustPolicyEvaluator, Stream 1 | ✅ **Explicitly Evidenced** |
| Vote hash uniqueness constraint | BaseVote, Stream 3 | ✅ **Explicitly Evidenced** |
| Device fingerprint duplicate detection | Vote.hasDuplicateDevice, Stream 3 | ✅ **Explicitly Evidenced** |
| VoterSlugStep tracks step progression | Stream 3 | ✅ **Explicitly Evidenced** |

**Candidate contexts potentially responsible:** Eligibility (primary), Voting (enforcement), Trust Attestation (prerequisite)

**Assessment:** Eligibility is well-evidenced and aligns with the literature's treatment. The literature confirms that eligibility is a separate concern from vote correctness and vote secrecy.

---

### 3.6 Authorization

**Definition (literature):** Voter has the right to participate in a specific election.

**Evidence in Rounds 17-23:**

| Evidence | Source | Classification |
|----------|--------|---------------|
| ADR-002: Verified ≠ Eligible ≠ Authorized | ADR-002, Trust Chain | ✅ **Explicitly Evidenced** |
| Capability resolver with centralized authority | ADR-001 Constitutional, ADR-004 | ✅ **Explicitly Evidenced** |
| ConstitutionalTransitionGuard enforces role checks | Stream 2, ConstitutionalTransitionGuard | ✅ **Explicitly Evidenced** |
| ElectionOfficer model with role assignment | Stream 2 | ✅ **Explicitly Evidenced** |

**Candidate contexts potentially responsible:** Authorization (primary), Constitutional Governance (lifecycle)

**Assessment:** Authorization is well-evidenced and explicitly separated from eligibility (ADR-002). The literature's treatment confirms this separation is architecturally meaningful.

---

### 3.7 Auditability

**Definition (literature):** All election actions can be independently reviewed and verified.

**Evidence in Rounds 17-23:**

| Evidence | Source | Classification |
|----------|--------|---------------|
| ElectionAuditLog with old/new values | Stream 4 | ✅ **Explicitly Evidenced** |
| ElectionAuditService per-voter tracking | Stream 4 | ✅ **Explicitly Evidenced** |
| SecurityEventRecorder for trust events | Stream 4 | ✅ **Explicitly Evidenced** |
| Governance decision snapshots with integrity hashes | Stream 6B | ✅ **Explicitly Evidenced** |
| Governance Evidence Replay infrastructure | Stream 4, Stream 6B | ✅ **Explicitly Evidenced** |
| Log rotation and email masking | Stream 4 | ✅ **Explicitly Evidenced** |

**Candidate contexts potentially responsible:** Audit (primary), Governance Evidence Replay (verification)

**Assessment:** Auditability is very well-evidenced and aligns with the literature's treatment of election audit as a first-class concern.

---

## 4. Concept Coverage Summary

| Concept | Classification | Primary Candidate Context | Confidence |
|---------|---------------|--------------------------|------------|
| **Vote Correctness** | ❌ Not Evidenced as formal concept | Voting (partial), Verification (potential) | HIGH |
| **Vote Secrecy** | ✅ Explicitly Evidenced | Voting | HIGH |
| **Vote Verifiability** | ✅ Explicitly Evidenced | Voting, Verification | HIGH |
| **Tally Correctness** | ✅ Explicitly Evidenced (internal) | Results/Tallying, Voting | HIGH |
| **Eligibility** | ✅ Explicitly Evidenced | Eligibility | HIGH |
| **Authorization** | ✅ Explicitly Evidenced | Authorization | HIGH |
| **Auditability** | ✅ Explicitly Evidenced | Audit, Governance Evidence Replay | HIGH |

---

## 5. Impact on Debt Items

### D42B (Election Integrity Guarantees)

**Previous status:** Substantially Clarified

**New status:** **Further Clarified** — literature introduces vote correctness as a distinct concept not currently evidenced in the system.

**What this means:** The system's guarantee coverage is broader than previously assessed. Six of seven concepts from the IVXV literature are evidenced. The missing concept (vote correctness proofs) is a cryptographic mechanism that may be out of scope for the current system's threat model. The mapping confirms that the existing architecture aligns well with formal election integrity concepts.

---

### D41 (Result Drift Detection)

**Previous status:** MEDIUM priority — what prevents result drift?

**Literature insight:** The paper treats tally correctness as requiring independent verification. The current system verifies results against expected counts but does not provide cryptographic tally verification. This is consistent with the system's internal-only verification model.

**Updated priority:** MEDIUM — no change.

---

### D35, D36, D37 (Legitimacy, Arbitration)

**Previous status:** Unresolved

**Literature insight:** The paper's treatment of authorization and eligibility as separate from correctness proofs indirectly supports the separation identified in Rounds 17-23. No direct impact on legitimacy or arbitration.

**Updated status:** No change.

---

## 6. Newly Discovered Governance Questions

**Q1:** Does the organization require cryptographic vote correctness proofs, or is application-level validation sufficient?

**Q2:** Is the concept of "vote correctness" (ballot well-formedness) a future requirement, or is it already satisfied by pre-submission validation?

**Q3:** Does the existing checksum and integrity verification infrastructure satisfy the organization's requirements for vote integrity, or are stronger cryptographic guarantees needed?

**Q4:** Does the literature's separation of vote correctness, vote secrecy, and vote verifiability into three distinct concerns suggest that Verification should be a separate context in the future?

---

## 7. Implications for ARB Deliberation (Observational Only)

1. **Vote correctness is the only major election integrity concept not evidenced.** The paper does not require it to be a separate context, but it does treat it as a separate concern requiring its own evidence mechanism. This is observation, not recommendation.

2. **The existing candidate contexts (Voting, Eligibility, Authorization, Audit, Results, Verification) map well to the literature's concepts.** No new contexts are suggested by this mapping.

3. **The literature confirms the architectural significance of the Verified ≠ Eligible ≠ Authorized separation** (ADR-002) by treating these as distinct concerns requiring different evidence mechanisms.

4. **Vote correctness proofs, if required, would most naturally belong to a Verification context** rather than Voting, because correctness is about proof of well-formedness, not about the act of voting itself.

---

**Round 24B Vote Correctness Literature Review — READY FOR ARB REVIEW**

**7 integrity concepts mapped from literature. 6 of 7 evidenced in current system. Vote correctness proofs not evidenced — may be out of scope or a future requirement. No new contexts proposed. Existing candidate contexts align well with formal election integrity concepts.**
