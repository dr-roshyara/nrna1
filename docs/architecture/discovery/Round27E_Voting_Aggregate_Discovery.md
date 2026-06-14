# Round 27E — Voting Aggregate Discovery

**Date:** 2026-06-08

**Phase:** Tactical DDD — Aggregate Discovery (Context 5 of 9)

**Status:** Complete — Awaiting ARB Review

---

## 1. Investigation Scope

**Candidate Context:** Voting

**Acceptance Status:** ACCEPTED (Round 25)
**Boundary Stability:** PROVISIONAL (D42B — verifiability guarantee)

**Evidence Sources:** Stream 3 (Voting/Tally Findings), BaseVote.php, Vote.php, ADR_20260203 (Voting Security), VoteController, Stream 1 (evidence artifacts), Round 22 Relationship Analysis (R4/R5/R6), Round 27B (Eligibility — ElectionEnrollment boundary question).

---

## 2. Context Legitimacy

Voting owns the decision: **"Is this vote valid, anonymous, and correctly recorded?"** This is the core election business capability — the act of casting and recording a ballot. Voting depends on Authorization (gate) and feeds Results/Tallying (derived projection). Its boundary stability is PROVISIONAL pending D42B (verifiability guarantee) and D39 (counting state meaning).

---

## 3. Concept Inventory

### Concept 1: Vote

**Source:** BaseVote.php, Vote.php, Stream 3

**Description:** The anonymous record of a voter's candidate selections. The central concept of the Voting context.

**Evidence:**
- No user_id — vote anonymity is fundamental (ADR_20260203)
- 60 candidate columns storing JSON selection data
- vote_hash: cryptographic uniqueness proof
- receipt_hash: voter self-verification
- data_checksum: SHA256 integrity verification
- cast_at: timestamp of vote casting
- Vote model lifecycle: creating → saved → (creates results) → verified

---

### Concept 2: Ballot (Selection)

**Source:** BaseVote candidate columns, VoteController, Stream 3

**Description:** The set of candidate selections made by a voter for all posts in an election. A ballot is distinct from the vote record itself — the ballot is the content, the vote is the recorded evidence.

**Evidence:**
- Ballot content stored as JSON in candidate_01–60 columns
- Each column contains: post_id, candidates array (candidacy_id per candidate), no_vote flag
- Ballot is validated before submission (VoteController)
- Ballot content determines Result creation (createResultsFromCandidates)
- Ballot is the source of truth for result data

---

### Concept 3: Receipt

**Source:** BaseVote.php:136-149, 291-294, Stream 3

**Description:** The cryptographic proof provided to the voter that their vote was recorded. Enables individual verifiability without revealing vote choice.

**Evidence:**
- receipt_hash: SHA256 voter-specific proof
- verifyByReceipt(): voter self-verification
- verifyByCode(): code-based verification
- Receipt cannot reveal vote choice (anonymity preservation)
- Receipt is voter-facing; participation_proof is admin-facing

**Core attributes:** receipt_hash, verification_code dependency

---

### Concept 4: ParticipationProof

**Source:** BaseVote.php:306-310, Stream 3

**Description:** Admin-facing cryptographic proof that a voter participated, without revealing their vote choice.

**Evidence:**
- participation_proof: SHA256(user_id + ip + election_id + salt)
- proveParticipation(): admin verification method
- Distinct from receipt (admin vs voter facing)
- Supports eligibility audit without violating anonymity

---

### Concept 5: VoteIntegrityChecksum

**Source:** Vote.php:181-252, Stream 3

**Description:** SHA256 checksum over vote candidate data for integrity verification.

**Evidence:**
- calculateChecksum(): SHA256 over candidate JSON + app key
- verifyChecksum(): compares stored vs computed
- verifyResultsIntegrity(): checksum + result count validation
- Checksum is updated when results are created (createResultsFromCandidates line 120)

---

### Concept 6: ElectionEnrollment (Voting boundary)

**Source:** 27B analysis, Stream 3, VoterSlugStep, ElectionMembership model

**Description:** The participant's registration and step progression through the voting workflow. 27B concluded this likely belongs to Voting rather than Eligibility.

**Evidence from 27B:**
- Enrollment lifecycle (enroll → steps → vote → complete) tracks voting workflow
- VoterSlugStep records 5 voting steps: code_entry, agreement, vote_selection, verification, completion
- Step progression happens during voting, not during eligibility determination
- Enrollment state changes when vote is completed (hasVoted flag)

---

### Concept 7: DeviceFingerprint

**Source:** Vote.php:154-159, BaseVote.php

**Description:** Privacy-preserving device fingerprint hash for fraud detection.

**Evidence:**
- device_fingerprint_hash: SHA256 hash of device characteristics
- hasDuplicateDevice(): checks for duplicate voting from same device
- Device metadata is anonymized (device_metadata_anonymized)
- Cannot identify specific devices — only detects duplicates

---

## 4. Aggregate Candidate Evaluation

### Candidate A: Vote

| Test | Assessment |
|------|------------|
| **Business decision owned** | "Is this vote valid, anonymous, and correctly recorded?" — the core voting decision |
| **Invariant protected** | Vote must be anonymous (no user_id); vote_hash must be unique; checksum must cover candidate data; vote must be attributed to an election |
| **Transactional consistency required** | HIGH — vote creation + checksum calculation + result creation must be consistent (currently synchronous via saved event) |
| **If split** | Vote could be recorded without checksum, or results created without vote source |
| **Alternative classification** | **AGGREGATE** — Vote is the central aggregate of the Voting context |

**Result: AGGREGATE (HIGH confidence)**

---

### Candidate B: Receipt

| Test | Assessment |
|------|------------|
| **Business decision owned** | "Does this receipt match a recorded vote?" — verification decision, but tied to Vote identity |
| **Invariant protected** | Receipt hash must be unique per vote; receipt cannot reveal vote choice |
| **Transactional consistency required** | Receipt creation is part of vote creation — they happen together |
| **If split** | Vote could exist without verifiable receipt |
| **Alternative classification** | **Value Object (within Vote aggregate)** — receipt is an attribute of the Vote. Voter verification reads the receipt value stored on the Vote record. No independent lifecycle or decision ownership. |

**Result: Value Object — not an aggregate.**

---

### Candidate C: ParticipationProof

| Test | Assessment |
|------|------------|
| **Business decision owned** | None — proof is computed, not decided |
| **Invariant protected** | Hash determinism (same inputs → same proof) |
| **Transactional consistency required** | N/A — computed at vote time, immutable after creation |
| **If split** | No inconsistency — proof is a derived value |
| **Alternative classification** | **Value Object (within Vote aggregate)** — participation_proof is an attribute of the Vote. It is computed at vote creation and never modified. No independent decision ownership. |

**Result: Value Object — not an aggregate.**

---

### Candidate D: VoteIntegrityChecksum

| Test | Assessment |
|------|------------|
| **Business decision owned** | None — checksum is computed, not decided |
| **Invariant protected** | Checksum must be deterministic (same data → same hash) |
| **Transactional consistency required** | Checksum is updated with vote — they must be consistent |
| **If split** | Vote data could be modified without checksum detecting the change |
| **Alternative classification** | **Value Object (within Vote aggregate)** — checksum is an attribute of the Vote. It protects Vote integrity but does not make independent decisions. |

**Result: Value Object — not an aggregate.**

---

### Candidate E: ElectionEnrollment

| Test | Assessment |
|------|------------|
| **Business decision owned** | "Is this participant enrolled and progressing through the voting workflow?" |
| **Invariant protected** | One enrollment per election per participant; step progression is sequential (1→2→3→4→5) |
| **Transactional consistency required** | MEDIUM — step progression must be sequential; enrollment status must be consistent with vote completion |
| **If split** | Participant could vote without completing enrollment steps, or steps could progress out of order |
| **Alternative classification** | **AGGREGATE (MEDIUM confidence)** — Enrollment has its own lifecycle (enroll → steps → complete), persistent state (VoterSlugStep, ElectionMembership), and invariants (step ordering, one enrollment per election). It is distinct from the Vote aggregate — enrollment exists before voting and continues after. |

**Boundary resolution (from 27B):** Enrollment lifecycle tracks the voting workflow (steps 1-5), not eligibility determination. Step types (code_entry, agreement, vote_selection, verification, completion) are voting activities. 27B's unresolved question is resolved: ElectionEnrollment belongs to Voting.

**Result: AGGREGATE (MEDIUM confidence)** — ElectionEnrollment tracks the voter's journey through the voting process. Distinct from Vote (which is the anonymous record of selections).

---

### Candidate F: Ballot

| Test | Assessment |
|------|------------|
| **Business decision owned** | None — ballot content is the input to Vote creation, not a decision itself |
| **Invariant protected** | Ballot must contain valid candidate/abstention choices; ballot must conform to election rules (required_number per post) |
| **Transactional consistency required** | Ballot validation happens before vote creation — no transaction with Vote itself |
| **If split** | Ballot could be validated with different rules than those applied at vote time |
| **Alternative classification** | **Value Object (input to Vote creation)** — Ballot is the voter's selection set. It is validated before Vote creation and stored as JSON within the Vote columns. It is not an independent aggregate — it is the content of the Vote. |

**Result: Value Object — not an aggregate.**

---

### Candidate G: DeviceFingerprint

| Test | Assessment |
|------|------------|
| **Business decision owned** | "Does this vote originate from a device that has already voted?" — duplicate detection |
| **Invariant protected** | Hash is privacy-preserving (cannot be reversed to device identity) |
| **Transactional consistency required** | LOW — duplicate check is advisory for fraud detection, not a consistency requirement |
| **If split** | No invariant violation — duplicate detection is a read-side check, not a write-side constraint |
| **Alternative classification** | **Value Object (within Vote aggregate) or External Service** — device fingerprint is an attribute stored on the Vote. Duplicate detection is a query, not an aggregate. |

**Result: Value Object — not an aggregate.**

---

## 5. Aggregate Boundary Validation Matrix

| Candidate | Owns Decision? | Owns State? | Invariant Protection | Aggregate? | Reclassification |
|-----------|---------------|-------------|---------------------|------------|-----------------|
| **Vote** | **✅ Core voting decision** | **✅ Yes** | **✅ Anonymity, uniqueness, checksum** | **YES** | **Aggregate (HIGH)** |
| **ElectionEnrollment** | **✅ Enrollment + step progression** | **✅ Yes** | **✅ Sequential steps, uniqueness** | **YES** | **Aggregate (MEDIUM)** — resolved from 27B |
| Receipt | ❌ Verification attribute | N/A | ✅ Unique, unrevealing | **NO** | Value Object (within Vote) |
| ParticipationProof | ❌ Computed | N/A | ✅ Deterministic | **NO** | Value Object (within Vote) |
| VoteIntegrityChecksum | ❌ Computed | N/A | ✅ Deterministic | **NO** | Value Object (within Vote) |
| Ballot | ❌ Input content | N/A | ✅ Valid selections | **NO** | Value Object (input to Vote) |
| DeviceFingerprint | ❌ Advisory check | N/A | ✅ Privacy-preserving | **NO** | Value Object (within Vote) |

---

## 6. Decision Ownership Matrix

| Decision | Owned By | Classification |
|----------|----------|---------------|
| Is this vote valid, anonymous, and correctly recorded? | **Vote** | **Aggregate root** |
| Is this participant enrolled and progressing through steps? | **ElectionEnrollment** | **Aggregate root** |
| Does this voter's receipt verify their vote? | Vote (via receipt VO) | Value Object |
| Can admin verify participation without revealing vote? | Vote (via participation_proof VO) | Value Object |
| Has vote data been tampered with? | Vote (via checksum VO) | Value Object |
| Does this device show duplicate voting? | Vote (via device_fingerprint VO) | Value Object (query) |

---

## 7. Invariant Analysis

| Invariant | Owned By | Type | Evidenced? |
|-----------|----------|------|------------|
| Vote has no user_id (anonymity) | Vote | Architecture | ✅ Explicit (ADR_20260203) |
| Vote hash is unique | Vote | Uniqueness | ✅ Constraint |
| Receipt hash is computable for verification | Vote | Determinism | ✅ BaseVote.verifyByReceipt |
| Checksum covers candidate data | Vote | Integrity | ✅ Vote.calculateChecksum |
| Step progression is sequential (1→2→3→4→5) | ElectionEnrollment | Sequence | ✅ VoterSlugStep fields |
| One enrollment per election per participant | ElectionEnrollment | Uniqueness | ⚠️ Assumed (not verified in code) |
| Ballot content is valid before submission | Vote (controller) | Validation | ✅ VoteController validation |

---

## 8. Consistency Boundary

### Vote Aggregate

```
Vote Aggregate (root)
    │
    ├── VoteId (VO)
    ├── ElectionId (VO)
    ├── OrganisationId (VO)
    ├── BallotContent (VO) — candidate_01..60 JSON
    ├── VoteHash (VO) — cryptographic uniqueness (unique constraint)
    ├── ReceiptHash (VO) — voter verification
    ├── ParticipationProof (VO) — admin verification
    ├── DataChecksum (VO) — integrity verification
    ├── DeviceFingerprintHash (VO) — fraud detection
    ├── CastAt (VO)
    ├── NoVoteOption (VO)
    └── NoVotePosts (VO)
```

**Transactional boundary:** Vote creation must be atomic with checksum, receipt, proof, and (currently) result creation.

### ElectionEnrollment Aggregate

```
ElectionEnrollment Aggregate (root)
    │
    ├── EnrollmentId (VO)
    ├── ParticipantId (VO)
    ├── ElectionId (VO)
    ├── CurrentStep (VO) — 1-5 progression
    ├── StepHistory — collection of VoterSlugStep records
    ├── Status (VO) — enrolling / active / completed
    └── HasVoted (VO) — boolean
```

**Transactional boundary:** Step progression must be sequential. Enrollment must be consistent with voting completion.

---

## 9. Revised Aggregate Inventory

| Concept | Type | Confidence | Rationale |
|---------|------|------------|-----------|
| **Vote** | **Aggregate** | **HIGH** | Core voting decision; strong invariant evidence; explicit design mandate (anonymity) |
| **ElectionEnrollment** | **Aggregate** | **MEDIUM** | Enrollment lifecycle; step progression; boundary resolved from 27B (belongs to Voting) |
| Ballot | Value Object | HIGH | Input content stored in Vote |
| Receipt | Value Object | HIGH | Verification attribute within Vote |
| ParticipationProof | Value Object | HIGH | Computed attribute within Vote |
| VoteIntegrityChecksum | Value Object | HIGH | Integrity attribute within Vote |
| DeviceFingerprint | Value Object | HIGH | Fraud detection attribute within Vote |

---

## 10. Aggregate Discovery Debt

| Debt | Question | Priority |
|------|----------|----------|
| ADV-1 | Should Vote creation be transactionally consistent with Result creation? Currently synchronous (BaseVote.saved event → createResultsFromCandidates). If results are eventually merged into Voting, this coupling is natural. If results remain separate, the transactional boundary should be reconsidered. | MEDIUM (depends on D39) |
| ADV-2 | Does ElectionEnrollment require its own aggregate, or could it be an entity within Voting? Current evidence supports separate aggregate (distinct lifecycle, different timing), but the enrollment/vote interaction pattern should be confirmed during detailed design. | MEDIUM |
| ADV-3 | Is the 60-candidate-column design an aggregate concern or a storage concern? The current storage design (60 VARCHAR columns with JSON) is atypical. Aggregate discovery should note this as a potential future refinement. | LOW |

---

## 11. Confidence Assessment

| Concept | Evidence Strength | Decision Ownership | Boundary Clarity | Overall |
|---------|-----------------|-------------------|-----------------|---------|
| **Vote** (aggregate) | HIGH | HIGH | HIGH | **HIGH** |
| **ElectionEnrollment** (aggregate) | MEDIUM | MEDIUM | MEDIUM | **MEDIUM** |
| Ballot (VO) | HIGH | N/A | HIGH | HIGH |
| Receipt (VO) | HIGH | N/A | HIGH | HIGH |
| Checksum (VO) | HIGH | N/A | HIGH | HIGH |

---

## 12. Summary

| Concept | Type | Confidence |
|---------|------|------------|
| **Vote** | **Aggregate** | HIGH |
| **ElectionEnrollment** | **Aggregate** | MEDIUM |
| Ballot | Value Object | HIGH |
| Receipt | Value Object | HIGH |
| ParticipationProof | Value Object | HIGH |
| VoteIntegrityChecksum | Value Object | HIGH |
| DeviceFingerprint | Value Object | HIGH |

**Key findings:**
1. **Vote is the primary aggregate** — HIGH confidence. Strong invariant evidence, explicit design mandate (anonymity), clear transactional boundary.
2. **ElectionEnrollment is a second aggregate** — MEDIUM confidence. Boundary resolved from 27B (belongs to Voting, not Eligibility). Distinct lifecycle (enroll → steps → vote → complete).
3. **All other concepts are Value Objects** within the Vote aggregate — ballot content, receipt, participation proof, checksum, device fingerprint. No independent decision ownership or lifecycle.

**Provisional boundary note (D42B):** The Voting context boundary with a potential future Verification context (for universal verifiability) remains unresolved. This affects whether receipt and verification attributes stay within the Vote aggregate or move to a separate context, but does not affect the Vote aggregate's internal structure.

---

**Round 27E Voting Aggregate Discovery — READY FOR ARB REVIEW**

**2 aggregates (Vote — HIGH, ElectionEnrollment — MEDIUM). 5 Value Objects. 3 aggregate discovery debt items (ADV-1 through ADV-3). Boundary provisional pending D42B.**
