# Round 36A-03 — ElectionGuard Literature Review

**Date:** 2026-06-13

**Phase:** Round 36A — Verifiability Research

**Sub-document:** 36A-03 (Literature Review — ElectionGuard)

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 36A-01 Domain Verifiability Baseline (APPROVED)
- Round 36A-02 Verifiability Concept Catalog (APPROVED)
- ARB Governance Posture: evaluate, do not defend
- Cross-Source Confirmation Rule (Section 0) — binding for this and all subsequent reviews

**Mandatory Framework:** Every finding uses the Section 6 mapping template from Round 36A-02. Findings that cannot be expressed in this template are inadmissible.

**Governing Rule:** ElectionGuard is advisory only. Its patterns may strengthen the discovered model. They may not replace the discovered model. Every enhancement candidate requires: literature evidence + domain evidence + gap + justification + VO-1 compatibility + D42B impact.

---

## ARB Formal Decision

```
Round 36A-03 — ElectionGuard Literature Review

APPROVED WITH REQUIRED CORRECTIONS APPLIED

Research Quality:        HIGH
ElectionGuard Accuracy:  HIGH
DDD Discipline:          HIGH
Governance Discipline:   HIGH (after corrections)

Confidence: HIGH
```

**Corrections applied (ARB required):**
1. OBS-36A-02-3 downgraded: ElectionGuard-confirmed observation, not confirmed architectural finding
2. D42B impact certainty reduced: "may be required" not "are required"
3. Enhancement Type classification added to AEC-EG-01/02/03
4. Cross-Source Confirmation Rule established (Section 0)
5. OBS-36A-03-1 Trust Distribution Question added (carry to Round 36C)

**Round 36A-03: APPROVED**
**Round 36A-04 (Helios/Benaloh Literature Review): AUTHORIZED**

**OBS-36A-03-2 (added post-approval):**
ElectionGuard assumes a specific election trust model — multiple independent guardians, threshold key ceremony, public bulletin board. NRNA has not yet established whether its constitutional model requires the same trust assumptions. This observation must remain visible through Helios review and into Round 36C (Threat Modeling). Do not assume ElectionGuard's trust model is NRNA's trust model.

**Most Important Architectural Insight (ARB confirmation):**
The strongest outcome of this review is not homomorphic tallying or ZKPs. It is the separation of:

```
Receipt Generation  ≠  External Verification Surface
```

ElectionGuard consistently treats these as distinct architectural responsibilities. Whether NRNA ultimately adopts that pattern remains open — but ElectionGuard has provided the first serious evidence that receipt generation (Vote aggregate) and external verification access (potential new context) may not belong together.

---

## Section 0 — Cross-Source Confirmation Rule (BINDING)

Established by ARB before Helios review begins. Applies to all Round 36A literature reviews.

```
A finding discovered in a single source
does not become an architectural recommendation.

A finding confirmed by:
    ElectionGuard + Helios
or
    ElectionGuard + two independent sources

becomes a Candidate Architecture Recommendation (CAR).

Only after CAR status is reached may ADR evaluation begin.
```

**Consequence for this document:** All findings in Round 36A-03 are observations only. They may become CAR-eligible once confirmed by Helios (36A-04) or other independent sources. No finding in this document alone constitutes an architectural recommendation.

**Why this rule exists:** Prevents "ElectionGuard → Architecture" drift. Enforces "ElectionGuard → Cross-Source Confirmation → Candidate Recommendation → ADR."

---

## Source

**System:** ElectionGuard (Microsoft Research / CISA)

**Primary documents:**
- ElectionGuard specification (end-to-end verifiable election system)
- ElectionGuard SDK documentation
- Benaloh, J. et al. — ElectionGuard design papers

**Nature of source:** Open-source implementation of an end-to-end verifiable election system. Uses homomorphic encryption and zero-knowledge proofs to provide individual and universal verifiability while preserving ballot secrecy.

**Evaluation posture:** ElectionGuard solves a broader problem than NRNA currently scopes. Read for applicable verifiability patterns, not for wholesale adoption.

---

## Section 1 — ElectionGuard System Overview

### What ElectionGuard Does

ElectionGuard is an E2E-V (end-to-end verifiable) election system that achieves three simultaneous properties:

1. **Cast-as-Intended:** Voters can verify their ballot was correctly captured before submission
2. **Recorded-as-Cast:** Voters can verify their ballot was recorded without modification
3. **Tallied-as-Recorded:** Anyone can verify that all recorded ballots were correctly counted

### Core Mechanisms

| Mechanism | Purpose | Cryptographic Foundation |
|-----------|---------|--------------------------|
| Encrypted Ballot | Hides vote while preserving verifiability | Exponential ElGamal encryption |
| Chaum-Pedersen Proof | Proves correct encryption without revealing choice | Zero-knowledge proof (ZKP) |
| Selection Limit Proof | Proves voter selected within allowed range | ZKP of ballot validity |
| Tracking Code | Individual receipt for voters | Hash of encrypted ballot |
| Election Record | Public bulletin board | All encrypted ballots + proofs |
| Decryption Shares | Threshold decryption for tally | Multi-guardian key ceremony |
| Verification Code | Post-election independent verification | Hash chain over election record |

### What ElectionGuard Requires

- **Pre-election:** Key ceremony with multiple independent guardians (threshold cryptography)
- **During voting:** Voter receives tracking code from encrypted ballot
- **Post-election:** All encrypted ballots published to public bulletin board
- **Tallying:** Homomorphic tallying over encrypted ballots; partial decryption by guardians
- **Verification:** Any party can download election record and independently verify tally

---

## Section 2 — Mapped Findings

All findings mapped against Round 36A-02 Concept-Evidence-Gap Table. All findings are observations only (Cross-Source Rule, Section 0).

---

### Finding EG-01: Tracking Code = Receipt Hash Equivalent

**Literature Finding:**
ElectionGuard issues voters a tracking code (hash of encrypted ballot) as their individual receipt. Voters use this code to look up their encrypted ballot on the public election record after the election. Confirms their ballot was included without revealing their choice.

**Domain Evidence:**
VO-3 receipt hash stored at recording time. `verifyByReceipt` mechanism discovered. Vote receipt issued to voter after completion (Round 24A).

**Gap:**
The domain model issues a receipt hash but the record it should verify against is internal to the Vote aggregate — not publicly accessible. ElectionGuard's tracking code maps to a public election record. NRNA's receipt hash has no public verification surface.

**Candidate Enhancement:** AEC-EG-01 (see Section 4).

**VO-1 Compatibility:**
COMPATIBLE. ElectionGuard's tracking code approach reveals: (1) "your ballot is in the record" and (2) the encrypted ballot — but not the decrypted choice. This is structurally equivalent to NRNA's receipt hash (recording confirmed; choice not disclosed). VO-1 is not threatened by this pattern.

**D42B Impact:**
ElectionGuard suggests that individual verifiability may require a public verification surface in addition to the Vote aggregate's receipt generation. Whether this constitutes a D42B scope change remains open pending additional source confirmation.

**Research Hypothesis RH-1 Assessment:**
PARTIALLY CONFIRMED, PARTIALLY QUALIFIED. The VO-1 compatibility is confirmed — receipt-based individual verifiability does not require exposing vote choice. However, ElectionGuard's implementation requires a public verification surface that the current model lacks. The receipt exists; the accessible record does not. Full confirmation or disconfirmation of RH-1 requires Helios review.

---

### Finding EG-02: Zero-Knowledge Proofs for Ballot Validity

**Literature Finding:**
ElectionGuard uses Chaum-Pedersen ZKPs to prove that each encrypted ballot is a valid ballot (selections within range, one choice per contest) without revealing the actual selections. These proofs are included in the public election record and can be verified by any party.

**Domain Evidence:**
VO-3 (receipt hash stored). No discovered evidence of cryptographic proof of ballot validity. `calculateChecksum` is an internal integrity check, not a public zero-knowledge proof.

**Gap:**
The current model has no mechanism for proving ballot validity to an external party. Internal checksum is not a public proof. The concept of "provably valid ballot without revealing choice" does not appear in the discovered model.

**Candidate Enhancement:** AEC-EG-02 (see Section 4).

**VO-1 Compatibility:**
COMPATIBLE in principle. ZKPs by design reveal no information beyond the validity claim. However, ZKP infrastructure is a major architectural addition requiring cryptographic capability not currently present.

**D42B Impact:**
ElectionGuard suggests ballot validity proofs may belong in the Vote aggregate (at recording time) or in a new cryptographic service — if such proof capability is constitutionally required. This is an open question, not a finding, pending constitutional requirement discovery.

---

### Finding EG-03: Public Election Record (Bulletin Board)

**Literature Finding:**
ElectionGuard publishes all encrypted ballots, proofs, and tally data to a publicly accessible election record. Any independent verifier can download this record and verify: (1) all ballots are valid, (2) the tally is correct, (3) the tally was computed from the published record.

**Domain Evidence:**
No discovered public election record. `ElectionAuditLog` and `GovernanceDecisionSnapshot` exist but are internal. No external party access to vote records is designed.

**Gap:**
Universal verifiability requires a mechanism for external parties to verify election outcomes. The current model has no external verification surface. This is the most significant gap identified by ElectionGuard review.

**Candidate Enhancement:** AEC-EG-01 (see Section 4).

**VO-1 Compatibility:**
REQUIRES CAREFUL DESIGN. Publishing full encrypted ballots (ElectionGuard approach) enables universal verifiability but introduces a public record that must be cryptographically designed to prevent voter-vote linkage. If only receipt hashes and tally counts are published (a simpler approach), VO-1 is more straightforwardly preserved but universal verifiability is weaker.

**D42B Impact:**
ElectionGuard demonstrates that a public record concern is separate from the Vote aggregate concern. If NRNA requires universal verifiability, ElectionGuard suggests this may require a new architectural element. Whether this is necessary for NRNA remains an open question pending confirmation from additional sources and constitutional requirement discovery.

---

### Finding EG-04: Threshold Key Ceremony (Guardian Model)

**Literature Finding:**
ElectionGuard requires a pre-election key ceremony where multiple independent guardians each hold a share of the decryption key. The tally can only be decrypted if a threshold of guardians cooperate. No single party can unilaterally decrypt individual ballots.

**Domain Evidence:**
No discovered key ceremony or guardian model. Election administration is a single authority in the current model. No threshold cryptography discovered.

**Gap:**
The current model assumes a single trusted election authority. The guardian model distributes trust across multiple independent parties.

**Candidate Enhancement:** None at this stage — governance question precedes architecture.

**VO-1 Compatibility:**
NOT DIRECTLY RELEVANT — the guardian model does not affect VO-1 (voter-vote linkage). It affects trust distribution and legitimacy.

**D42B Impact:**
LOW for D42B specifically. However, ElectionGuard raises the broader governance question: who is trusted to certify election legitimacy?

**OBS-36A-03-1 — Trust Distribution Question:**
ElectionGuard's guardian model reveals a domain governance question: "Who is trusted to certify election legitimacy?" This sounds closely related to discovered concepts of governance authority and legitimacy (D35/D37/ADH-1). This observation is NOT a D42B concern — it is a governance architecture concern. Carry to Round 36C (Threat Modeling) and flag for GovernanceState research. The question may already be partially answered by the discovered constitutional governance model.

---

### Finding EG-05: Challenge-Response Audit (Benaloh Challenge)

**Literature Finding:**
ElectionGuard supports the Benaloh Challenge: a voter may, before finalising their ballot, challenge the system to decrypt their current ballot to prove it matches their intent. The voter then re-casts a new ballot. The spoiled (challenged) ballot is published as proof that the system correctly encrypts ballots.

**Domain Evidence:**
No challenge mechanism discovered. The voting workflow is a linear 5-step process with no challenge-response branch.

**Gap:**
Cast-as-Intended verification requires a challenge mechanism — the ability for a voter to confirm their ballot was correctly captured before committing. This capability is entirely absent from the discovered model.

**Candidate Enhancement:** Noted; no AEC at this stage — requires constitutional requirement for Cast-as-Intended before design is warranted.

**VO-1 Compatibility:**
COMPLEX. Spoiled (challenged) ballots are decrypted and published — by design. They reveal the voter's test selection, but that ballot is deliberately not the submitted vote. VO-1 covers submitted votes; spoiled ballots are not submitted. Compatibility requires further analysis if this capability is pursued.

**D42B Impact:**
MODERATE. Cast-as-Intended is currently NOT DISCOVERED in the domain. If NRNA's constitutional requirements demand Cast-as-Intended, the Benaloh Challenge pattern is one candidate approach. Does not change D42B scope directly but introduces new workflow concerns.

---

### Finding EG-06: Homomorphic Tallying

**Literature Finding:**
ElectionGuard tallies votes homomorphically — encrypted ballots are summed without decryption, then the aggregate is decrypted once. This produces a mathematical proof that the tally is correct without decrypting individual ballots.

**Domain Evidence:**
Results computed via SQL aggregation (`syncResults`, `verifyResultsIntegrity`). No homomorphic computation. No mathematical proof of correct tally.

**Gap:**
The current tally is a database count — correct if the database is honest, but not independently verifiable. Homomorphic tallying provides a mathematical proof of correctness independent of database integrity.

**Candidate Enhancement:** AEC-EG-03 (see Section 4).

**VO-1 Compatibility:**
COMPATIBLE. Homomorphic tallying is designed to preserve ballot secrecy — individual ballots are never decrypted. VO-1 is preserved by design.

**D42B Impact:**
Significant if Tallied-as-Recorded with cryptographic proof is required. Blocked by D39. Cannot advance until Results/Tallying ownership is resolved.

---

## Section 3 — ElectionGuard vs NRNA Model: Assessment

| ElectionGuard Property | NRNA Current State | Gap Size | VO-1 Risk | Status (Cross-Source Rule) |
|---|---|---|---|---|
| Individual Verifiability (tracking code) | CANDIDATE SUPPORT | MEDIUM — public surface missing | None | OBSERVATION — confirm in 36A-04 |
| Cast-as-Intended (Benaloh Challenge) | NOT DISCOVERED | LARGE — new workflow required | Complex | OBSERVATION |
| Recorded-as-Cast (ballot on public record) | PARTIALLY SUPPORTED | MEDIUM — public record missing | Design needed | OBSERVATION — confirm in 36A-04 |
| Tallied-as-Recorded (homomorphic proof) | NOT DISCOVERED | LARGE — fundamental tally change | None | OBSERVATION — blocked by D39 |
| Universal Verifiability (public record) | NOT OBSERVED | LARGE — new context candidate | Design needed | OBSERVATION — confirm in 36A-04 |
| Ballot Validity Proof (ZKP) | NOT DISCOVERED | LARGE — new crypto required | None | OBSERVATION |
| Guardian/Threshold Trust | NOT DISCOVERED | LARGE — trust model question | None | OBS-36A-03-1 (Round 36C) |

---

## Section 4 — Architecture Enhancement Candidates (from ElectionGuard)

These are candidates only. Status: SINGLE-SOURCE OBSERVATION. Not Candidate Architecture Recommendations until confirmed by a second independent source.

---

### AEC-EG-01: Public Receipt Verification Surface

**Type:** NEW CONTEXT CANDIDATE (Published Election Record)

**What:** A publicly accessible record where voters can verify their receipt hash is present, confirming their vote was included in the election.

**Minimum Implementation:** Published, integrity-protected list of receipt hashes with timestamps. Does not require full ElectionGuard encryption to provide basic recorded-as-cast verification.

**VO-1 Compatibility:** COMPATIBLE if only receipt hashes are published (no voter linkage possible). Requires careful analysis if encrypted ballots are published.

**D42B Impact:** If confirmed, introduces a new "Published Election Record" concern separate from the Vote aggregate. Vote generates the receipt; the Published Record makes it externally verifiable. D42B may scope to include this boundary.

**Governance Gate:** Requires constitutional requirement establishing external verifiability as a goal. Not yet confirmed.

**Cross-Source Rule Status:** SINGLE-SOURCE OBSERVATION. Requires Helios confirmation to advance to Candidate Architecture Recommendation.

---

### AEC-EG-02: Ballot Validity Proof at Recording Time

**Type:** NEW CAPABILITY CANDIDATE (Cryptographic Proof Infrastructure)

**What:** A cryptographic proof that each recorded ballot is valid (correct number of selections, within bounds) generated at recording time.

**Minimum Implementation:** ZKP infrastructure in the Vote aggregate or a new cryptographic service.

**VO-1 Compatibility:** COMPATIBLE — ZKPs reveal validity claim, not choice.

**D42B Impact:** Vote aggregate would need new cryptographic capabilities, or a cryptographic service context would be introduced.

**Governance Gate:** Requires NRNA constitutional requirement for provable ballot validity. Not yet confirmed.

**Cross-Source Rule Status:** SINGLE-SOURCE OBSERVATION.

---

### AEC-EG-03: Tallied-as-Recorded Verification

**Type:** RESULTS/TALLYING CANDIDATE CAPABILITY (blocked by D39)

**What:** A verifiable tally mechanism where the count can be independently verified without trusting the database.

**Minimum Implementation:** Either homomorphic encryption (high assurance, high complexity) or a hash-chain approach over vote records (lower assurance, lower complexity).

**VO-1 Compatibility:** COMPATIBLE for both approaches.

**D42B Impact:** HIGH — but cannot advance until D39 (Results/Tallying ownership) is resolved.

**Governance Gate:** BLOCKED BY D39.

**Cross-Source Rule Status:** SINGLE-SOURCE OBSERVATION — also blocked by D39.

---

## Section 5 — What ElectionGuard Does NOT Resolve

| Item | Status After EG Review |
|------|----------------------|
| D42B — verifiability ownership scope | PARTIALLY INFORMED — ElectionGuard suggests public surface needed; scope remains open pending multiple sources |
| D39 — Results/Tallying ownership | NOT RESOLVED — EG reveals the gap; D39 must be resolved before tally verifiability can be designed |
| Candidate D43 — Enrollment Authority | NOT RELEVANT to EG — Eligibility is a precondition; EG does not address enrollment governance |
| RH-1 — VO-1 and individual verifiability compatible | PARTIALLY CONFIRMED: receipt-based individual verifiability does not require exposing choice; public surface still needed; await Helios |

---

## Section 6 — OBS-36A-02-3 Update (Verifiability Boundary Gap)

**Status after ElectionGuard review:** CONFIRMED BY ONE SOURCE — awaiting confirmation.

ElectionGuard demonstrates that its approach to verifiability (at every level) requires an external, public record. A Vote aggregate by itself — however well designed — cannot provide ElectionGuard-style external verifiability. A public verification surface is required in ElectionGuard's architecture.

**Governance Note:** This is an observation from one source. ElectionGuard does NOT prove that all verifiability architectures require a public record. Other approaches may exist. Helios (36A-04) must independently confirm whether a public record (or equivalent mechanism) is architecturally necessary for verifiability — or whether it is one design choice among several.

**OBS-36A-02-3 remains OPEN until second-source confirmation.**

---

## ARB Review

**Status: APPROVED** — all corrections applied.

**Round 36A-04 (Helios/Benaloh Literature Review): AUTHORIZED.**

Key confirmation targets for Helios review:
1. Does Helios also require a public election record for verifiability? (OBS-36A-02-3 confirmation target)
2. Does Helios support receipt-based individual verifiability without violating voter anonymity? (RH-1 confirmation target)
3. Does Helios offer an alternative to the public bulletin board approach? (Tests whether ElectionGuard's pattern is the only option)
4. Does Helios address the Trust Distribution question? (OBS-36A-03-1 secondary check)
