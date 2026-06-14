# Round 27I — Voting Aggregate Discovery (Final Context)

**Date:** 2026-06-08

**Phase:** Tactical DDD — Aggregate Discovery (Context 9 of 9)

**Status:** Complete — Ready for ARB Challenge Review

---

## 1. Investigation Scope

**Candidate Context:** Voting

**Acceptance Status:** ACCEPTED (Round 25)
**Boundary Stability:** PROVISIONAL (D42B — verifiability guarantee)

**Note:** This is the **9th and final context** in the aggregate discovery sequence. All previous contexts (Trust Attestation, Eligibility, Authorization, Constitutional Governance, Audit, Results/Tallying, Governance Evidence Replay, Arbitration/Legitimacy) have completed aggregate discovery and challenge review.

**Evidence Sources:** Stream 3, BaseVote.php, Vote.php, ADR_20260203, VoteController, Round 22 Relationship Analysis (R4/R5/R6), Round 25 Context Acceptance, all previous 8 aggregate discovery rounds.

---

## 2. Context Legitimacy

Voting owns the decision: **"Is this vote valid, anonymous, and correctly recorded?"** This is the core election business capability. The Vote aggregate was already identified in Round 27E with HIGH confidence and survived challenge review. This round provides the final consolidated analysis.

---

## 3. Concept Inventory

### Concept 1: Vote (Core)

**Source:** BaseVote.php, Vote.php, Stream 3

**Description:** The anonymous record of a voter's candidate selections. Central concept of the Voting context.

**Evidence:** No user_id, 60 candidate JSON columns, vote_hash, receipt_hash, data_checksum, cast_at, encrypted_vote, device_fingerprint_hash.

---

### Concept 2: Ballot

**Source:** BaseVote candidate columns, VoteController

**Description:** The voter's candidate selections before they become a permanent Vote record.

**Evidence:** Built in session before vote submission; validated before casting; stored as candidate_XX JSON columns on the Vote record after casting. Does not persist independently.

---

### Concept 3: Receipt

**Source:** BaseVote.php:136-149, 291-294

**Description:** Cryptographic proof provided to the voter for self-verification.

**Evidence:** receipt_hash attribute on Vote; verifyByReceipt() comparison. Value Object within Vote aggregate (confirmed Round 27E challenge review).

---

### Concept 4: ParticipationProof

**Source:** BaseVote.php:306-310

**Description:** Admin-facing cryptographic proof of voter participation.

**Evidence:** participation_proof attribute on Vote; proveParticipation() comparison. Value Object within Vote aggregate.

---

### Concept 5: VoteIntegrityChecksum

**Source:** Vote.php:181-252

**Description:** SHA256 checksum over vote candidate data.

**Evidence:** calculateChecksum() at vote creation; verifyChecksum() for validation. Value Object within Vote aggregate.

---

### Concept 6: DeviceFingerprint

**Source:** Vote.php:154-159

**Description:** Privacy-preserving device fingerprint hash.

**Evidence:** device_fingerprint_hash attribute on Vote; hasDuplicateDevice() query. Value Object within Vote aggregate.

---

## 4. Aggregate Candidate Validation

### Candidate: Vote

| Test | Assessment |
|------|------------|
| **Business decision owned** | "Is this vote valid, anonymous, and correctly recorded?" |
| **Invariants** | Anonymity (no user_id), uniqueness (vote_hash), integrity (checksum), verifiability (receipt_hash), election attribution (election_id FK) |
| **Consistency requirements** | Vote creation must be atomic with hash, checksum, receipt, proof — all set at creation time |
| **If split** | Vote data could exist without integrity protection, or receipt without vote record |
| **Owns truth?** | **Creates truth** — the vote did not exist before casting. This is fundamentally different from Arbitration (which records truth) or Eligibility (which computes truth). |
| **Reconstructable?** | **No** — vote_hash, receipt_hash, participation_proof depend on runtime context (voter identity, code, timestamp) that is not stored in any other aggregate |

**Result: AGGREGATE (HIGH confidence)** — confirmed.

---

## 5. Aggregate Inventory (Final)

| Context | Aggregate(s) | Confidence | Notes |
|---------|-------------|------------|-------|
| 27A — Trust Attestation | **Verification** | HIGH | 1 aggregate |
| 27B — Eligibility | *(none)* | — | Domain Service pattern |
| 27C — Authorization | **RoleAssignment** (provisional) | MEDIUM-LOW | Aggregate candidate |
| 27D — Constitutional Governance | **GovernanceState** | MEDIUM-HIGH | Lifecycle + suspension |
| 27E — Audit | *(none)* | — | Fire-and-forget observability |
| 27F — Results/Tallying | *(none)* | — | Merger evidence |
| 27G — Governance Evidence Replay | **ReplaySession** (provisional) | MEDIUM | Aggregate candidate |
| 27H — Arbitration/Legitimacy | *(none)* | — | Decision Record pattern |
| **27I — Voting** | **Vote** | **HIGH** | Core election aggregate |

**Total: 5 aggregates (3 confirmed, 2 provisional) across 9 contexts. 4 contexts with no aggregate (Domain Service / Decision Record / Read Model patterns).**

---

## 6. Summary

| Concept | Type | Confidence |
|---------|------|------------|
| **Vote** | **Aggregate** | **HIGH** |

**Vote is the strongest aggregate discovered in the entire system.** It is the only aggregate that simultaneously satisfies all criteria: it creates business truth (not just records it), owns non-reconstructable invariants (hash, receipt, proof depend on runtime context), enforces multiple invariants within a single consistency boundary, and represents the core business capability of the election platform.

---

**Round 27I Voting Aggregate Discovery — COMPLETE**

**All 9 contexts have completed aggregate discovery. Aggregate inventory across 9 contexts: 5 aggregates (3 confirmed, 2 provisional). 4 contexts with no aggregate. Voting is the strongest aggregate in the system. Ready for cross-context aggregate consolidation review (Round 28).**
