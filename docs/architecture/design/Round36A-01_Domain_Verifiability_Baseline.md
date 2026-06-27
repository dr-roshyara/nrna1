# Round 36A-01 — Domain Verifiability Baseline

**Date:** 2026-06-10

**Phase:** Round 36A — Verifiability Research

**Sub-document:** 36A-01 (Domain Baseline — must precede all literature review)

**Authority:** Architecture Review Board

**Governance Foundation:**
- All Rounds 17–35 discovery and design artifacts
- Literature Research Phasing Plan (BINDING)

**Purpose:** Catalogue every verifiability-related concept, mechanism, invariant, and gap already discovered in Rounds 17–35. This baseline becomes the evaluation standard against which all literature findings are measured.

**Governing Rule:** Literature review must not begin until this baseline exists. Without a domain baseline, literature drives the architecture rather than strengthening it.

---

## ARB Formal Decision

```
Round 36A-01 — Domain Verifiability Baseline

APPROVED WITH OBSERVATIONS

Research Discipline:      EXCELLENT
DDD Discipline:           EXCELLENT
Governance Discipline:    EXCELLENT
Literature Readiness:     VERY HIGH

Confidence: VERY HIGH
```

**Corrections applied:**
1. Section 2.7 — Round 24A elevated to blocking prerequisite
2. Section 8 Question 6 — removed architecture-neutral phrasing (algorithm exposure assumption removed)
3. Section 8 Question 8 — added: "Does verifiability require a verifier?"
4. Section 8 — duplicate numbering resolved

**Round 36A-01: APPROVED**
**Round 36A-02 (Verifiability Concept Catalog): AUTHORIZED** — Round 24A incorporated (blocking prerequisite fulfilled)

**ARB Governance Clarification (applied to all Round 36 work):**
The governance rule for Round 36 is not "protect the current architecture." It is "change the architecture for evidence-based reasons." Round 36 is an evaluation, not a defence. All three outcomes are acceptable: minor enhancements required; additional capabilities required; or major architectural changes required. Literature may demonstrate that new aggregates, new contexts, new responsibilities, or cryptographic verification mechanisms are necessary — and if so, they must be proposed through ADR review with evidence. See Section 10.

---

## Section 1 — What "Verifiability" Means in This Domain (Pre-Literature)

Before consulting any external source, the domain itself must be asked: what verifiability properties has discovery already established?

This section records only what discovery evidence supports — no literature, no assumptions.

---

## Section 2 — Discovered Verifiability Mechanisms

### 2.1 Receipt Hash (VO-3)

**Source:** Round 33 Aggregate Boundary Design, Vote Aggregate invariants

**What was discovered:**
- VO-3: "Receipt hash is stored"
- The Vote aggregate generates and stores a receipt hash at recording time
- This is an element of VoteRecorded, not a separate event

**Verifiability relevance:**
- A receipt hash provides a voter with a token that could, in principle, allow them to verify their vote was recorded
- The mechanism for using this hash to verify recording has NOT been discovered
- Whether this constitutes "individual verifiability" is D42B — unresolved

**Confidence:** HIGH (that VO-3 exists) / LOW (that VO-3 is sufficient for any verifiability guarantee)

**Gap:** The receipt hash exists. What a voter or external party does with it is not yet modeled.

---

### 2.2 Vote Anonymity Constraint (VO-1)

**Source:** Round 33 Aggregate Boundary Design

**What was discovered:**
- VO-1: "The vote-voter link must not exist"
- This is a design constraint to never create, not a security property to protect
- VoteRecorded contains no voter identity

**Verifiability tension:**
VO-1 creates an inherent tension with individual verifiability. If a voter cannot be linked to their vote:
- They cannot be told "your specific vote was recorded"
- They can only be told "a vote matching this receipt hash was recorded"
- Whether receipt hash is sufficient for individual verifiability without violating VO-1 is D42B

**Confidence:** HIGH (that tension exists)

---

### 2.3 Append-Only Verification Records (TA-3)

**Source:** Round 33, Trust Attestation aggregate invariants

**What was discovered:**
- TA-3: Verification records are append-only
- Once verification is REVOKED, it cannot be reversed

**Verifiability relevance:**
- Append-only records support audit trail integrity for identity attestation
- A third party can verify the history of a verification decision
- This is a limited form of verifiability: "Was this identity attested, and when?"

**Confidence:** HIGH

---

### 2.4 Evidence Integrity and Replay (D7)

**Source:** Round 29 Decision Catalog, Round 33 ReplaySession aggregate (CANDIDATE)

**What was discovered:**
- D7: "Was evidence integrity preserved?"
- ReplaySession aggregate is a CANDIDATE whose purpose is to replay historical evidence and detect divergence
- `DivergenceDetected` is a candidate event

**Verifiability relevance:**
- Evidence replay is a form of verifiability: "Does the current state match the historical record?"
- This is closer to audit integrity verification than voter-facing verifiability
- Who may invoke replay and what certification means is blocked by D36/ADGR-1/D35

**Confidence:** MEDIUM (concept is discovered; semantics are governance-debt-dependent)

---

### 2.5 Audit Evidence (D6)

**Source:** Round 29 Decision Catalog, Round 34C Aggregate Interaction Analysis

**What was discovered:**
- Audit context receives all domain events (eventual, fire-and-forget)
- Audit records every business fact with timestamp and attribution
- Audit does not influence domain decisions

**Verifiability relevance:**
- Audit provides an immutable log of all governance actions
- A third party with access to audit records could verify the sequence of events
- Whether audit records constitute "universal verifiability" is not established

**Confidence:** HIGH (that Audit records exist) / LOW (that they constitute formal verifiability)

---

### 2.6 VoteRecorded Event (Anonymous)

**Source:** Round 34A Domain Event Discovery

**What was discovered:**
- VoteRecorded is raised when a valid ballot is permanently recorded
- VoteRecorded carries no voter identity (VO-1 constraint on event payload)

**Verifiability relevance:**
- VoteRecorded is the evidence that a ballot was recorded
- It is the foundation of any "recorded-as-cast" verifiability claim
- Whether "recorded-as-cast" can be proven without voter identity is the core D42B question

**Confidence:** HIGH (that VoteRecorded exists) / UNRESOLVED (whether it supports verifiability claims)

---

### 2.7 Election Integrity Guarantee Map (Round 24A) — INCORPORATED

**Source:** Round 24A — Election Integrity Guarantee Map (Rounds 17-23 repository analysis)

**What Round 24A discovered:**

Round 24A systematically mapped discovered evidence (Rounds 17-23) against formal election guarantee categories. The following findings are directly relevant to this baseline.

#### Individual Verifiability — EXPLICITLY SUPPORTED

Evidence from `BaseVote` (Rounds 17-23 Stream 3):
- `verifyByReceipt` — voter self-verification via receipt hash
- `verifyByCode` — code-based verification of vote ownership
- `proveParticipation` — participation proof that verifies voting without revealing vote choice
- Vote receipt sent to voter after completion
- Voter can verify vote was counted via published results

**Design trade-off confirmed:** Voter cannot retrieve their specific vote after submission (VO-1 anonymity constraint). This is documented as intentional, not a gap.

**Assessment from Round 24A:** "Individual verifiability is reasonably well-supported. Voters can verify their vote was recorded (via receipt hash) and included (via published results). The anonymity constraint means voters cannot retrieve their specific vote after submission, which is a documented design trade-off."

#### Universal Verifiability — NOT OBSERVED

No public bulletin board. No cryptographic proofs for external verification. Governance Evidence Replay infrastructure exists but is internal-only. No external verifier discovered.

**Assessment from Round 24A:** "Universal verifiability is the weakest guarantee in the current system. The infrastructure for evidence preservation exists but is internal — no external party can independently verify election outcomes."

#### Receipt-Freeness — NOT ADDRESSED (intentional trade-off)

The receipt hash does NOT reveal vote choice. A voter could show their receipt hash as proof of having voted (but not how). Round 24A assessed this as an intentional design trade-off: verifiability was prioritised over receipt-freeness. No governance requirement for receipt-freeness was found.

#### Anonymity-Verifiability Tension — RESOLVED (at individual verifiability level)

The discovered model resolves the VO-1 tension for individual verifiability:
- `verifyByReceipt` proves recording without linking voter to vote choice
- `proveParticipation` proves participation without revealing choice
- Receipt hash proves "a vote matching this hash was recorded" — not "voter X voted for Y"

**VO-1 and individual verifiability are compatible in the discovered model.**

The tension remains unresolved for universal verifiability — which would require external access to evidence that the current model keeps internal.

#### Vote Integrity — EXPLICITLY SUPPORTED

- SHA256 checksum over candidate data (`calculateChecksum`, `verifyChecksum`)
- Encrypted vote data stored
- Results regenerable from vote JSON source of truth (`syncResults`)
- Soft deletes (vote not permanently removed — soft-delete preserves audit)

#### Eligibility Integrity — EXPLICITLY SUPPORTED

- Constitutional preconditions check eligibility at transition time
- `ParticipationEligibilityEvidence` frozen at evaluation time
- Vote hash uniqueness constraint (one vote per code)
- Device fingerprint duplicate detection

#### Auditability — EXPLICITLY SUPPORTED

- `ElectionAuditLog` records all state changes with old/new values
- `SecurityEventRecorder` logs trust evaluation events
- `GovernanceDecisionSnapshot` with integrity hashes
- `GovernanceReplayService` for evidence replay
- Email masking for privacy in audit logs

---

**Round 24A D42B Assessment (incorporated):**

Six guarantees confirmed as explicitly or implicitly supported:
Anonymity, Individual Verifiability, Eligibility Integrity, Vote Integrity, Result Integrity, Auditability.

One guarantee not yet implemented: Universal Verifiability.

Two guarantees not addressed (policy decisions, not design gaps):
Receipt-Freeness, Coercion Resistance.

---

**Blocking prerequisite FULFILLED:** Round 24A findings incorporated.

---

## Section 3 — Verifiability-Adjacent Invariants Catalog

| Invariant | Aggregate | Verifiability Relevance | Confidence |
|-----------|-----------|------------------------|-----------|
| VO-1: No voter linkage | Vote | Creates tension with individual verifiability | HIGH |
| VO-2: Checksum valid | Vote | Internal integrity check; not externally visible | HIGH |
| VO-3: Receipt hash stored | Vote | Foundation for potential individual verifiability | HIGH |
| VO-4: Atomic recording | Vote | Ensures recording is complete or not at all | HIGH |
| TA-3: Append-only | Verification | Audit trail integrity for identity records | HIGH |
| CG-1: Deterministic transitions | GovernanceState | Verifiable process integrity | MEDIUM |

---

## Section 4 — Verifiability Properties: Discovery Status

Updated after Round 24A incorporation. Status reflects evidence from Rounds 17-33.

**Classification note:** "Supported" means a concrete discovered mechanism exists in the codebase. "Candidate Support" means discovered mechanisms are consistent with a literature concept but whether they satisfy the formal definition is a research question for Round 36A. Literature terminology must not be retrofitted onto discovery evidence — the mechanisms are what they are; formal concept mapping is Round 36A's purpose.

| Property | Status | Evidence | Notes |
|---------|--------|---------|-------|
| Individual Verifiability | CANDIDATE SUPPORT | `verifyByReceipt`, `verifyByCode` (BaseVote, Round 24A) | Mechanisms exist; whether they satisfy the formal literature definition is Round 36A's question |
| Universal Verifiability | NOT OBSERVED | No public bulletin board, no external verifier (Round 24A) | Internal-only infrastructure; no external party access |
| Cast-as-Intended | NOT DISCOVERED | No evidence | No mechanism for voter to confirm ballot selection captured correctly |
| Recorded-as-Cast | PARTIALLY SUPPORTED | VoteRecorded + `verifyByReceipt` (Round 24A, Round 34A) | Voter can verify recording occurred; cannot verify exact selection without violating VO-1 |
| Tallied-as-Recorded | NOT DISCOVERED | D39 unresolved; Results/Tallying not designed | Requires D39 resolution before property can be assessed |
| End-to-End Verifiability | INCOMPLETE CHAIN | Cast→Record: CANDIDATE SUPPORT; Record→Tally: D39 gap | No complete verifiable chain from cast to tally discovered |
| Receipt-based Verification | SUPPORTED | `verifyByReceipt` (BaseVote lines 291-294, Round 24A) | Concrete mechanism discovered; reveals recording occurred, not vote choice |
| Participation Proof | SUPPORTED | `proveParticipation` (BaseVote, Round 24A) | Concrete mechanism discovered; proves participation without revealing choice — VO-1-compatible |
| Receipt-Freeness | NOT ADDRESSED | Not in governance documents (Round 24A) | Intentional trade-off: verifiability prioritised over receipt-freeness |
| Coercion Resistance | NOT IN SCOPE | No constitutional requirement found | Research only until constitution requires it |

---

## Section 5 — D42B Verifiability Ownership Question

**D42B:** What is the scope of the Vote aggregate's verifiability responsibility?

**Current state from Round 33:**

> "Vote owns receipt_hash generation (VO-3); verifiability guarantee scope deferred"

**What this means:**
- The Vote aggregate owns the generation and storage of the receipt hash
- Whether the receipt hash is sufficient to make any verifiability guarantee is unresolved
- Whether verifiability extends beyond the Vote boundary (into a verification context, an audit context, or an external verifier) is unresolved

**Evidence from discovered domain pointing toward Vote owning more:**
- VO-3 already places receipt hash inside Vote
- VoteRecorded is the authoritative record of ballot recording

**Evidence from discovered domain suggesting Vote does not own verifiability guarantees:**
- Verifiability as a guarantee requires external validation mechanisms not yet discovered
- VO-1 creates a fundamental anonymity constraint on what Vote can expose
- The Replay mechanism (D7) suggests evidence verification may belong to a different concern

---

## Section 6 — Verifiability-Related Contexts

| Context | Verifiability Role (discovered) | Gap |
|---------|--------------------------------|-----|
| Voting (Vote aggregate) | Generates receipt hash (VO-3); records ballot anonymously | Verifiability guarantee scope is D42B |
| Audit | Records all events; provides audit trail | Whether this constitutes verifiability is undefined |
| Governance Evidence Replay | Could verify evidence integrity | D36/ADGR-1/D35 block full modeling |
| Results/Tallying | Would verify tallied-as-recorded | D39 unresolved |
| Trust Attestation | Verifies identity trustworthiness | Append-only; limited scope |

---

## Section 7 — Candidate D43 / AUTHORITY-GAP-1 Relevance

Enrollment Authority ownership is unresolved. This has direct verifiability implications:

- "Cast-as-Intended" verifiability requires that the voter was eligible to cast the ballot they cast
- Without knowing who owns enrollment authority, the eligibility precondition for verifiability is ungrounded
- Any literature-based verifiability claim that involves "eligible voter cast this ballot" touches the enrollment gap

**Round 36A must keep Candidate D43 visible throughout.**

---

## Section 8 — Questions This Baseline Generates For Literature Review

The following questions are generated from domain evidence and will guide literature review in 36A-02 through 36A-07:

1. Can individual verifiability be achieved without violating VO-1? How do mature systems reconcile anonymity with voter-verifiability?

2. What does "recorded-as-cast" mean for a system where votes carry no voter identity? Is receipt hash sufficient?

3. How do mature systems model the boundary between vote recording (like VO-3) and external verification? Is there an architectural seam?

4. What is the difference between evidence integrity verification (D7 — Replay) and vote verifiability (D42B)? Are they the same domain or different?

5. Does "universal verifiability" require public disclosure of vote records? How does that interact with the discovered constitutional anonymity constraint (VO-1)?

6. What evidence must be available to support tallied-as-recorded verification? How does D39 unresolved ownership constrain what can be designed?

7. What trust assumptions do mature election systems make? Do those assumptions conflict with the NRNA constitutional governance model?

8. Does verifiability require a verifier? If yes, who owns verification? This question sits at the heart of D42B: Vote generates a receipt hash, but a hash without a designated verifier does not constitute a verifiability guarantee.

---

## Section 9 — Pre-Literature Summary (Updated After Round 24A)

**Concrete discovered mechanisms:**
- `verifyByReceipt` (BaseVote) — receipt hash verification without revealing vote choice
- `verifyByCode` (BaseVote) — code-based vote ownership verification
- `proveParticipation` (BaseVote) — participation proof preserving VO-1 anonymity
- SHA256 checksum (`calculateChecksum`, `verifyChecksum`) — vote integrity
- `GovernanceReplayService` — internal evidence replay capability
- VO-3 receipt hash stored at recording time — formal token exists

**What remains undiscovered:**
- Whether discovered mechanisms satisfy formal literature definitions of verifiability
- Universal verifiability — no external verification mechanism exists
- Cast-as-Intended — no ballot capture verification mechanism
- Complete chain from cast to tally (D39 gap)
- Ownership of verifiability as a formal responsibility

**D42B in one sentence:**
Concrete verification mechanisms exist in the discovered model — but whether they constitute a formal verifiability guarantee, and what responsibilities they confer on which context, is the unresolved question that Round 36A must answer.

---

## Section 10 — Round 36 Governance Posture (ARB Clarification)

**Source:** ARB clarification 2026-06-10 — binding for all Round 36 work.

Round 36 is an **evaluation** of the current architecture, not a defence of it.

**Acceptable Round 36 outcomes:**
- **Outcome A:** Current model sufficient — minor enhancements required
- **Outcome B:** Additional capabilities required — new aggregates, new contexts, or new responsibilities
- **Outcome C:** Major architectural changes required — current model insufficient for trustworthiness guarantees

All three outcomes are acceptable. The architecture is not sacred. The evidence is.

**What Round 36 may propose (with evidence):**
- New aggregates or contexts for verifiability
- New aggregate capabilities for existing aggregates
- Cryptographic verification mechanisms
- External verifier integration patterns
- Audit mechanisms not yet discovered
- Trustworthiness infrastructure not yet designed

**The governance rule:**
Do not silently import patterns. Do not reject patterns automatically. Show the literature evidence. Show the discovered-domain evidence. Show the gap. Explain why the change is required. Submit architectural changes through ADR review.

**The constraint that does not change:**
Changes must be submitted with evidence, not imported from literature without justification. "ElectionGuard does it this way" is not sufficient. "Our current model cannot provide property X, which is required by constitutional requirement Y, and ElectionGuard provides a proven pattern for Z" is the required form.

---

## ARB Review

**Status: APPROVED**

Round 24A incorporated (blocking prerequisite fulfilled 2026-06-10).
Round 36 governance posture clarification applied (Section 10).
Section 4 classification revised: literature terminology not retrofitted onto discovered mechanisms.

**Round 36A-02 Verifiability Concept Catalog: AUTHORIZED.**
