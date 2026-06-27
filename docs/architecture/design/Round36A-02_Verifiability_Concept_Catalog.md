# Round 36A-02 — Verifiability Concept Catalog

**Date:** 2026-06-10

**Phase:** Round 36A — Verifiability Research

**Sub-document:** 36A-02 (Concept Catalog — precedes all literature review)

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 36A-01 Domain Verifiability Baseline (APPROVED — prerequisite)
- ARB Governance Posture: Round 36 evaluates; does not defend

**Purpose:** Extract verifiability concepts from domain evidence alone. Identify gaps. Produce the concept→evidence→gap table that all literature findings will map into. This creates the filter: Discovery Evidence → Gap Analysis → Literature Evaluation → Candidate Enhancements.

**Governing Rule:** Concepts are extracted from domain evidence. If a concept has no domain evidence, it is recorded as a gap — not as a design target. Literature will later indicate which gaps matter and which do not. The domain does not borrow literature vocabulary until evidence establishes the concept independently.

---

## ARB Formal Decision

```
Round 36A-02 — Verifiability Concept Catalog

APPROVED WITH OBSERVATIONS

Concept Quality:         EXCELLENT
Research Discipline:     EXCELLENT
Governance Discipline:   VERY HIGH
DDD Alignment:           VERY HIGH

Confidence: VERY HIGH
```

**Observations applied:**
- OBS-36A-02-1: Section 3.5 — clarified as internal result integrity, not externally verifiable
- OBS-36A-02-2: Section 5 — D42B Option A qualified as research hypothesis, not preferred resolution
- OBS-36A-02-3: Verifiability Boundary Gap flagged for monitoring through all literature reviews

**Round 36A-02: APPROVED**
**Round 36A-03 (ElectionGuard Literature Review): AUTHORIZED**

---

## Section 1 — Concept-Evidence-Gap Table (Domain-Derived)

This table is the primary input for all Round 36A literature reviews (36A-03 through 36A-07). Every literature finding must be placed against a row in this table. A finding that does not map to any row is a candidate for a new row — but requires ARB authorization to add.

| Concept | Existing Domain Evidence | Evidence Strength | Primary Gap |
|---------|--------------------------|------------------|----|
| Individual Verifiability | `verifyByReceipt` (BaseVote); `verifyByCode` (BaseVote); Vote receipt issued to voter; result publication accessible | MEDIUM — mechanisms exist; formal sufficiency of receipt model unknown | Does receipt hash satisfy the formal definition? Can a voter verify their vote is included in the tally? |
| Universal Verifiability | None discovered | NONE | No external verification mechanism; internal infrastructure not publicly accessible |
| Cast-as-Intended | None discovered | NONE | No mechanism for voter to confirm ballot selection captured correctly at the UI boundary |
| Recorded-as-Cast | VoteRecorded (anonymous) + `verifyByReceipt` (proves recording occurred) | MEDIUM — recording is evidenced; proof model that links receipt to specific record is undiscovered | Can receipt hash prove "this vote as I cast it was recorded" without exposing choice? What is the proof model? |
| Tallied-as-Recorded | None discovered | NONE | D39 unresolved; Results/Tallying context not designed; no counting algorithm or verification mechanism |
| End-to-End Verifiability | Partial: Cast→Record (MEDIUM); Record→Tally (NONE) | LOW — chain is incomplete | Complete chain from ballot selection through recording through tallying is not modeled |
| Receipt-based Verification | `verifyByReceipt` (BaseVote, lines 291-294, Round 24A) | HIGH — concrete mechanism discovered and documented | Proof model: what database record does the hash verify against? Is the verification cryptographically sound? |
| Participation Proof | `proveParticipation` (BaseVote, Round 24A) | HIGH — concrete mechanism discovered | Whether this constitutes a formal non-interactive proof vs. a lookup is undiscovered |
| Anonymity (VO-1) | VO-1 invariant; no user_id in votes/results; `save_vote()` constraint (Round 24A) | HIGH — strongly evidenced and enforced | Formal anonymity under adversarial conditions; coercion channel via device fingerprint (Round 24A concern) |
| Anonymity-Verifiability Compatibility | VO-1 + `verifyByReceipt` + `proveParticipation` coexist (Round 24A) | MEDIUM — coexistence observed; sufficiency of compatibility not proven | Does receipt verification leak any voter-identifying information under adversarial conditions? |

---

## Section 2 — Primary Research Hypothesis

Before literature review begins, the baseline generates one primary research hypothesis that is likely to drive the most significant architectural decisions.

### Hypothesis RH-1: VO-1 and Individual Verifiability Are Compatible

**Statement:** The combination of receipt hash (VO-3), `verifyByReceipt`, and `proveParticipation` may allow individual verifiability to be achieved without violating VO-1.

**Evidence supporting the hypothesis:**
- Receipt hash proves "a vote matching this hash was recorded" — not "voter X voted for Y"
- `proveParticipation` proves participation without revealing choice
- VO-1 forbids voter linkage; these mechanisms do not create that linkage

**Evidence that weakens or qualifies the hypothesis:**
- The proof model for `verifyByReceipt` is not yet documented — whether hash comparison is cryptographically sufficient is unknown
- Whether the receipt can serve as a coercion vector ("I have a receipt showing I voted, therefore I voted as instructed") is a separate question (receipt-freeness, NOT VO-1)
- "Individual verifiability" in formal literature may require stronger properties than receipt hash lookup

**What literature review must determine:**
- Do mature systems use receipt hash as the mechanism for individual verifiability?
- What is the minimum proof model required for formal individual verifiability?
- Does any literature system achieve individual verifiability under VO-1-equivalent constraints?

**Why this matters for D42B:** If RH-1 is confirmed, the Vote aggregate's current mechanisms may be sufficient for individual verifiability — D42B resolves toward "Vote aggregate owns individual verifiability, receipt hash mechanism is sufficient." If RH-1 is disconfirmed, D42B may require a new context or new capabilities.

---

## Section 3 — Concept Definitions (Domain-Derived, Pre-Literature)

These definitions are extracted from domain evidence and terminology already present in the discovered model. They do NOT import literature definitions.

### 3.1 Receipt Verification (SUPPORTED)

**Domain definition:** A voter holds a receipt hash issued at ballot recording time. They may compare this hash against a published record to confirm their ballot was recorded. The comparison reveals whether recording occurred — it does not reveal vote choice.

**Scope in discovered model:** Individual voter, post-recording, confirmed by `verifyByReceipt`.

**Open question:** What record is the hash compared against? The current model stores the hash inside the Vote aggregate, which is private. Is there a published record for comparison? If not, the verification is internal-only, which does not constitute an accessible verifiability mechanism.

---

### 3.2 Participation Proof (SUPPORTED)

**Domain definition:** A voter may generate a proof that they participated in the election without revealing their vote choice. This proof is VO-1-compatible.

**Scope in discovered model:** Individual voter, VO-1-preserving, confirmed by `proveParticipation`.

**Open question:** Is `proveParticipation` a non-interactive zero-knowledge proof, a deterministic lookup, or a challenge-response? The formal strength of the participation proof is not documented.

---

### 3.3 Vote Recording (SUPPORTED — VoteRecorded)

**Domain definition:** When `CastVote` is accepted, the Vote aggregate permanently records the ballot and emits `VoteRecorded`. This is the authoritative business fact that a ballot was recorded. `VoteRecorded` carries no voter identity (VO-1).

**Scope in discovered model:** Vote aggregate, at command acceptance time, atomic (VO-4).

**Open question:** The `VoteRecorded` event is the recording fact. Whether a voter can access this event or its evidence is not modeled.

---

### 3.4 Evidence Integrity (CANDIDATE — Replay)

**Domain definition:** The historical evidence record can be replayed to detect whether current state diverges from historical state. This is a form of audit integrity verification, not voter-facing verifiability.

**Scope in discovered model:** ReplaySession aggregate (CANDIDATE), `GovernanceReplayService`, D7.

**Open question:** Who may invoke replay, what the replay result certifies, and whether replay constitutes a formal audit mechanism are all blocked by D36/ADGR-1/D35.

---

### 3.5 Result Integrity (SUPPORTED — internal only)

**Domain definition:** Election results accurately reflect all valid recorded votes. Results can be regenerated from the vote JSON source of truth (`syncResults`). Count verification confirms result integrity (`verifyResultsIntegrity`).

**Scope in discovered model:** Results/Tallying (D39 unresolved), Voting (source data).

**OBS-36A-02-1 applied:** Result Integrity is currently an internal property, not an externally verifiable one. Internal regeneration and count verification exist. An external party has no mechanism to independently verify results. The distinction matters because "result integrity" in the literature context usually implies external verifiability — what the current model supports is internal consistency, not external proof.

**Open question:** Whether results can be independently verified by a third party is NOT supported. Internal regeneration exists; external verification does not.

---

## Section 4 — What Is Explicitly NOT in the Discovered Model

Concepts for which no domain evidence exists. These are pure gaps — literature may later indicate which gaps require resolution.

### 4.1 Cast-as-Intended Verification

No mechanism discovered for a voter to confirm that their ballot selection at the voting interface was correctly interpreted by the system. The gap is between the voter's intent and the system's recording of that intent.

**Why this matters:** "Cast-as-Intended" is typically the hardest verifiability property to achieve. It requires evidence to travel from the user interface to the vote record in a verifiable way.

### 4.2 Tallied-as-Recorded Verification

No mechanism discovered for verifying that the tally correctly reflects all recorded votes. The gap is between the set of recorded votes (Vote aggregate) and the published results.

**Why this matters:** Without tallied-as-recorded, even a system with perfect individual verifiability (voter knows their vote was recorded) cannot guarantee the tally is honest.

### 4.3 Public Bulletin Board or External Verifier

No published record exists that an external party could use to independently verify election outcomes. All current mechanisms are internal to the platform.

**Why this matters:** Universal verifiability — the ability for any observer to verify the election — typically requires a public record. The current model has no such mechanism.

### 4.4 Cryptographic Proof of Correct Tally

No cryptographic proof mechanism exists for the counting process. Results are computed by SQL aggregation and can be internally regenerated but not externally proven.

---

## Section 5 — D42B Concept Boundary Analysis

D42B asks: "What is the scope of the Vote aggregate's verifiability responsibility?"

From the concept analysis above, two distinct verifiability responsibilities emerge:

| Responsibility | Currently Owned By | Evidence |
|---|---|---|
| Ballot recording (the fact) | Vote aggregate | VoteRecorded, VO-4 |
| Receipt issuance | Vote aggregate | VO-3, `verifyByReceipt` |
| Vote-choice confidentiality | Vote aggregate | VO-1, no choice in event |
| Participation proof | Vote aggregate (implied) | `proveParticipation` |
| Published verification record | NOT OWNED | Gap |
| Tally verification | NOT OWNED | Gap (D39) |
| External verifiability access | NOT OWNED | Gap |

**Preliminary D42B interpretation:**
The Vote aggregate owns: the recording fact, the receipt, and the participation proof. It does NOT own any external verification surface. If individual verifiability requires an external check (comparing receipt against a public record), that responsibility belongs to a context that does not yet exist in the discovered model.

**OBS-36A-02-2 applied:** The following is a research hypothesis only — not a preferred resolution.

**Research Hypothesis D42B-RH-A:** Vote aggregate is sufficient for internal individual verifiability; a new context is required for externally-accessible verification. This hypothesis must be evaluated against ElectionGuard, Helios, Benaloh, and Scantegrity before any resolution is proposed.

**OBS-36A-02-3 — Verifiability Boundary Gap (monitor through all literature reviews):**
The gap "Published verification record NOT OWNED / External verification access NOT OWNED" is the first concrete evidence that D42B may require architectural scope beyond the Vote aggregate. This gap must be tracked through all 36A-03 through 36A-07 reviews. If two or more literature sources indicate that external verification access requires a dedicated architectural boundary, that constitutes sufficient evidence for a formal D42B resolution proposal.

---

## Section 6 — Literature Mapping Framework

All Round 36A literature findings (36A-03 through 36A-07) must be expressed in this form before they can affect the domain model:

```
Literature Finding:
    [Paper/System] states that [verifiability property]
    requires [mechanism].

Domain Evidence:
    The current model [supports / does not support] this mechanism
    via [discovered evidence].

Gap (if any):
    [What is missing from the current model.]

Candidate Enhancement:
    [What architectural change would close the gap, if any.]

VO-1 Compatibility:
    [Whether the proposed mechanism is compatible with VO-1.]

D42B Impact:
    [Whether the finding changes the resolution of D42B.]
```

No literature finding advances without this mapping. "ElectionGuard uses X" is not a finding — "ElectionGuard uses X, our model lacks X, X is required for property Y, Y maps to gap [Z] in Section 1" is a finding.

---

## Section 7 — Open Governance Items Visible During Concept Extraction

| Item | Impact on Verifiability |
|------|------------------------|
| D42B (Verifiability ownership scope) | Primary — determines what Vote aggregate is responsible for |
| D39 (Results/Tallying ownership) | Blocks tallied-as-recorded; blocks End-to-End verifiability |
| Candidate D43 / AUTHORITY-GAP-1 (Enrollment Authority) | Blocks cast-as-intended; eligibility precondition ungrounded |
| D36/ADGR-1/D35 (Replay governance) | Blocks evidence integrity formal modeling |

---

## ARB Review

**Status: APPROVED** — all three observations applied.

Round 36A-03 (ElectionGuard Literature Review): AUTHORIZED.

All literature reviews (36A-03 through 36A-07) must use the mandatory mapping framework from Section 6. OBS-36A-02-3 Verifiability Boundary Gap must be tracked in each review.
