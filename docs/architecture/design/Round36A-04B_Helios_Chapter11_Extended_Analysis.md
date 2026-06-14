# Round 36A-04B — Helios Chapter 11 Extended Analysis

**Date:** 2026-06-13

**Phase:** Round 36A — Verifiability Research

**Sub-document:** 36A-04B (Extended Analysis — Helios Chapter 11, Pereira)

**Authority:** Architecture Review Board

**Source:** Pereira, O. — Chapter 11: "Internet Voting with Helios" (from *Real-World Electronic Voting: Design, Analysis and Deployment*)

**Relationship to 36A-04:** This document extends Round 36A-04 (Helios/Benaloh Review) with Pereira's architectural analysis of Helios 1.0→2.0 evolution and the structural variants developed by the cryptographic community. It does not replace 36A-04 — it supplements it.

**Governance Foundation:**
- Rounds 36A-01 through 36A-06 governance framework — all binding
- ARB Governance Posture: evaluate, do not defend
- Representation Rule — do not assume verification surface = bulletin board
- Literature Saturation Rule — three independent families needed for CDI → CAH promotion

---

## ARB Formal Decision

```
Round 36A-04B — Helios Chapter 11 Extended Analysis

APPROVED WITH OBSERVATIONS APPLIED

Research Quality:          EXCELLENT
Architectural Extraction:  EXCELLENT
Governance Discipline:     VERY HIGH
Evidence Separation:       VERY HIGH

Confidence: VERY HIGH
```

**ARB corrections applied:**
1. Candidate A → CDI-05: "Late-Bound Identity Binding" (not "Late-Bound Authentication" — authentication is implementation language; identity binding is domain language)
2. Candidate B → CDI-06: "Governance Fingerprint Pattern" (not "Election Fingerprint" — NRNA is a constitutional governance platform; pattern generalises beyond elections)
3. Candidate C → RESEARCH OBSERVATION ONLY: Last-Ballot-Wins has significant constitutional implications (vote ownership, finality, coercion resistance) — requires constitutional analysis before CDI classification
4. OBS-36A-04B-1 elevated to 36A-DI-Candidate status (three-source pattern too consistent to remain only an observation)
5. CDI-07 added: Independent Verification Observer (recurring pattern across ElectionGuard Verifier, Helios Monitor, Prêt à Voter Verifier, Scantegrity Bulletin Board Observer)
6. OBS-36A-04B-2 added: Preparation Surface ≠ Submission Surface (lifecycle separation, distinct from CDI-05 identity separation)
7. Helios-C → Round 36C (Threat Modeling): authority separation insight is the transferable concept, not Registration Authority specifically
8. Trustee automation → Round 36D (Trust Distribution): distributed trust is confirmed; threshold cryptography remains one implementation

---

## Section 1 — Core Paradigm Shift: Helios 1.0 → 2.0

Pereira documents a fundamental change in Helios's tallying approach:

| Version | Mechanism | Anonymisation Strategy |
|---------|-----------|----------------------|
| Helios 1.0 | Verifiable shuffle (mix-net) | Ciphertexts shuffled; individual decryption |
| Helios 2.0+ | Homomorphic aggregation | Ciphertexts multiplied; aggregate decryption only |

**DDD implication (ARB extract):**
The shift from mix-net to homomorphic aggregation reveals a domain-level design principle:

```
Tallying Mechanism
        ≠
Anonymisation Strategy
```

If these are coupled, changing one requires changing the other. Helios 1.0 proved this — the mix-net approach constrained what question types were feasible. Helios 2.0 decoupled them by moving anonymity into the encryption layer rather than the shuffling layer.

**Relevance to NRNA:**
The Tallying Context (D39) and the Vote aggregate (anonymity via VO-1) must remain decoupled. The mechanism for anonymity (VO-1 as a design constraint, not a cryptographic mechanism) is separate from whatever tally computation is eventually adopted. This is already architecturally present in the discovered model — and Pereira confirms this as a deliberate design principle.

---

## Section 2 — New CDIs from This Analysis

### CDI-05: Late-Bound Identity Binding

**Source:** Helios BPS architecture — voter authentication occurs after ballot preparation, not before.

**Literature Evidence:**
Helios deliberately delays voter authentication to the end of the voting process. The Ballot Preparation System (BPS) runs unauthenticated — it does not know the voter's identity when it prepares the ballot. Authentication occurs only at the point of submission.

**Architectural insight (domain language, not implementation language):**
The actual pattern is not "late authentication." The pattern is:

```
Identity Boundary
        ≠
Vote Preparation Boundary
```

These are separate concerns that must be separated by design. If they merge, the ballot preparation system can target specific voters — manipulating ballot content based on who is voting.

**Alignment with discovered NRNA model:**
This pattern is already present in the NRNA domain structure:

```
Trust Attestation     → Who are you?
        ↓
Eligibility           → Are you eligible?
        ↓
CastVote command      → What is your vote?
```

Voter identity is resolved before the vote boundary, not inside it. VO-1 enforces this at the command boundary. CDI-05 confirms this separation is architecturally intentional — not just a technical constraint.

**What CDI-05 adds to discovered model:**
The separation is discovered. What is not yet confirmed is whether the BPS equivalent (the voting UI / ballot presentation layer) is architecturally isolated from the identity layer. CDI-05 suggests this isolation should be explicit — the ballot preparation surface should be unable to observe voter identity at preparation time.

**D42B Impact:** LOW — reinforces existing VO-1 boundary, does not change scope.
**VO-1 Compatibility:** CONFIRMED COMPATIBLE — pattern specifically designed to preserve anonymity.
**Status:** CANDIDATE DESIGN INVESTIGATION.

---

### CDI-06: Governance Fingerprint Pattern

**Source:** Helios Election Fingerprint mechanism — deterministic hash over all election parameters, broadcast before voting begins.

**Literature Evidence:**
Helios freezes election configuration (questions, voter eligibility boundaries, trustee public keys) and computes a deterministic hash — the Election Fingerprint — across all parameters. The BPS independently recomputes this hash at runtime to verify configuration integrity. Any parameter manipulation changes the fingerprint.

**ARB generalisation — Governance Fingerprint:**
Pereira documents this as an "Election Fingerprint." For NRNA — a constitutional governance platform — the pattern generalises:

```
Governance Fingerprint Pattern

Immutable Governance Configuration
        ↓
Deterministic Hash (Fingerprint)
        ↓
All subsequent aggregates / events reference it
        ↓
Any configuration corruption changes the fingerprint
```

This provides an anchor for:
- Audit context: all events are anchored to the fingerprint
- Governance Evidence Replay: replay can verify all events against the fingerprint
- Legitimacy: illegitimate governance configuration is detectable via fingerprint divergence

**Alignment with discovered NRNA model:**
`GovernanceDecisionSnapshot` (Round 24A) already has integrity hashes. `GovernanceReplayService` already replays evidence. CDI-06 suggests these capabilities could be anchored to a single immutable Governance Fingerprint emitted at election/governance configuration time.

**OBS-36A-04B-1 relevance:** This is a cross-system pattern — see Section 3.

**D42B Impact:** MODERATE — fingerprint anchors may require a GovernanceState or configuration-certification capability.
**VO-1 Compatibility:** NOT RELEVANT — configuration fingerprinting does not affect voter-vote linkage.
**Status:** CANDIDATE DESIGN INVESTIGATION.

---

### CDI-07: Independent Verification Observer

**Source:** Helios Election Monitor (de Marneffe) — independent background service polling the public API, verifying ballot proofs, mirroring the bulletin board.

**Literature Evidence:**
The Helios Monitor is an independent service that:
- Continuously polls the public election record
- Verifies mathematical proofs on newly submitted ballots in real-time
- Mirrors the bulletin board for redundancy

**Cross-system pattern recognition:**
This pattern appears in multiple reviewed systems:
- ElectionGuard: independent verifier specification
- Helios: Election Monitor (de Marneffe)
- Prêt à Voter: external verifier specification
- Scantegrity: public web bulletin board supports independent polling

**The transferable insight (ARB extract):**
Not "build a polling service." The pattern is:

```
Independent Observer

An external party with read-only access to the
verification representation can independently verify
election integrity without system cooperation.
```

This is a constitutional governance property, not just a technical feature. It aligns with:
- Audit context (D6) — records are available
- GovernanceReplayService — evidence is replayable
- Legitimacy (D35/D37) — independent verification supports legitimacy claims

**What CDI-07 adds:**
The discovered model has audit infrastructure. CDI-07 suggests the audit representation should be designed for independent external consumption — not just internal replay. This is related to CDI-04 (verification representation surface) but focuses on the observer pattern rather than the voter-facing receipt pattern.

**D42B Impact:** LOW — verification surface question (CDI-04) handles the external access concern; CDI-07 focuses on observer design.
**VO-1 Compatibility:** COMPATIBLE if the public representation is a transformation (OBS-36A-05-1).
**Status:** CANDIDATE DESIGN INVESTIGATION.

---

## Section 3 — OBS-36A-04B-1 / 36A-DI-Candidate: Configuration Freeze Pattern

**Evidence:**
Across three reviewed systems, election/governance configuration becomes immutable before any voter transactions occur:

| System | Mechanism | Immutability Point |
|--------|-----------|-------------------|
| ElectionGuard | Key ceremony + election parameters published | Before voting opens |
| Helios | Election Fingerprint computed and broadcast | Before voting opens |
| Prêt à Voter | Ballot printing commits candidate ordering | Before voting opens |

**Elevated status — 36A-DI-Candidate:**

```
36A-DI-Candidate-01

Configuration Freeze Pattern

Governance configuration
must become immutable before
vote collection begins.

Observed independently across three systems
(ElectionGuard, Helios, Prêt à Voter).

The pattern is too consistent to remain only an observation.
Elevated to Domain Insight Candidate pending one additional
confirmation source (Risk Limiting Audits or similar).

Not confirmed. Not a design decision.
Strong enough to track explicitly.
```

**NRNA relevance:**
The GovernanceState aggregate already has state transitions — `OpenVoting` marks the transition to voter-interaction phase. CDI-06 and this DI-Candidate together suggest this transition point is also a configuration-freeze point: once `OpenVoting` is reached, election configuration is immutable and fingerprinted.

This could also resolve the Round 34B open question around `TransitionGovernanceState` — the `OpenVoting` transition may be the configuration-certification and fingerprint-emission point, not just a governance phase change.

**Status:** 36A-DI-CANDIDATE — one additional source confirms; RLA review is the natural confirmation test.

---

## Section 3B — OBS-36A-04B-2: Preparation Surface ≠ Submission Surface

**Observation:**
Helios Chapter 11 consistently separates two voter lifecycle phases:

```
Ballot Preparation Phase
  → Voter assembles their choices
  → Voter may review, challenge, or re-prepare
  → Identity is not yet bound (CDI-05)

Ballot Submission Phase
  → Voter commits their ballot irreversibly
  → Identity is bound at this moment
  → Submission is the irreversible commitment point
```

**Why this is distinct from CDI-05:**
CDI-05 is about identity — who is doing the voting. This observation is about lifecycle — what phases a voter moves through before the irreversible commitment point.

```
OBS-36A-04B-2

Preparation Surface
        ≠
Submission Surface

These are separate lifecycle phases.

Preparation is reversible — voter can change,
challenge, or discard before submission.

Submission is irreversible — the commitment point
after which no changes are possible.

A voter may: Prepare → Review → Verify → Challenge → Submit
as distinct lifecycle steps.
```

**Alignment with 36A-DI-01:**
36A-DI-01 established that Cast-as-Intended verification occurs before the irreversible commitment point. OBS-36A-04B-2 sharpens this: the "commitment point" is the Submission Surface, and everything before it is Preparation Surface — potentially including challenge, review, and verification steps.

**NRNA relevance:**
The NRNA voting workflow has five steps. Step 3 is ballot selection; Step 4 is verification/confirmation. This maps structurally onto the Preparation/Submission separation:
- Steps 1-4 may belong to the Preparation Surface
- Step 5 (completion) may be the Submission Surface

Whether the NRNA workflow intentionally separates these is a discovery question, not a design conclusion. OBS-36A-04B-2 is an observation to carry forward.

**Status:** SINGLE-SOURCE OBSERVATION. Consistent with discovered 5-step workflow structure but not yet cross-source confirmed.

---

## Section 3C — OBS-36A-04B-3: Configuration Freeze ≠ Configuration Certification

**Observation (added post-approval):**
The literature frequently bundles two distinct governance actions:

```
Freeze     = no further changes permitted to configuration

Certification = configuration recognised as legitimate and valid
```

These are different events that may occur in different orders:

```
Option A:  Freeze → Certification
           Configuration is locked, then formally certified as valid

Option B:  Certification → Freeze
           Configuration is certified as valid, then locked against changes
```

**Why this distinction matters for NRNA:**
The `GovernanceState` aggregate governs state transitions. The transition to `OpenVoting` could be:
- The Freeze point (no more configuration changes)
- The Certification point (governance formally declares the election valid)
- Both simultaneously

If these are separate governance acts, they may belong to different commands, different transitions, or even different aggregates. Collapsing them prematurely creates a hidden invariant that may contradict the constitutional governance model.

**Intersection with discovered governance debts:**
- D35 (legitimacy consequences) — Certification is a legitimacy claim
- D37 (legitimacy enforcement) — who has authority to certify?
- ADH-1 (authority hierarchy) — certification authority is unresolved

**Status:** SINGLE-SOURCE OBSERVATION. Track through GovernanceState research and D35/D37/ADH-1 resolution. Do not assume Freeze and Certification are the same event.

---

## Section 4 — Research Observations (Not CDIs)

### RO-01: Last-Ballot-Wins (Ballot Replacement)

**Literature Evidence:**
Helios allows a voter to submit multiple ballots; only the last-received ballot is counted in the tally. Previous ballots are archived.

**Why NOT a CDI:**
The constitutional implications are significant:

- **Vote ownership:** Does the voter own their vote up until the close of voting, or only until submission?
- **Vote finality:** Can a voter change their vote? At what point is a vote final?
- **Coercion resistance:** Last-ballot-wins could be exploited by coercers — force the voter to submit a "correct" ballot after their genuine one
- **Election legitimacy:** Allowing ballot replacement may require explicit constitutional authorisation

**Classification:** RESEARCH OBSERVATION. Record the pattern. Do not advance to CDI until constitutional analysis addresses vote finality in the NRNA governance model. This is a constitutional question, not an architecture question.

---

### RO-02: Mix-Net vs Homomorphic Trade-off for Question Types

**Literature Evidence:**
Helios 2.0 uses homomorphic tallying for standard first-past-the-post elections. Ranked choice, approval voting, and write-in questions require mix-net variants — homomorphic multiplication fails for complex preference structures.

**Why NOT a CDI:**
The NRNA constitutional voting model (what question types are supported) is not yet fully discovered. Until the constitutional question types are confirmed, the tally mechanism cannot be selected.

**Classification:** RESEARCH OBSERVATION. Record for D39 resolution context — the tally mechanism depends on the constitutional question types, not the other way around.

---

## Section 5 — Deferred Items

| Item | Target Round | Key Insight to Carry |
|------|-------------|---------------------|
| Helios-C (ballot stuffing resistance) | Round 36C (Threat Modeling) | Authority separation is the transferable concept — not "Registration Authority" specifically |
| Mobile Threshold Automation | Round 36D (Trust Distribution) | Distributed trust is confirmed; threshold cryptography is one implementation; UX for non-technical trustees is a real concern |
| Election Monitor (external polling) | Round 36D or Audit supporting context | Independent Observer pattern (CDI-07) carries this forward |

---

## Section 6 — CDI Summary (Updated Across All Reviews)

| CDI | Name | Source | Status |
|-----|------|--------|--------|
| CDI-04 | Verification Representation Surface | EG + Helios + Scanteg + PàV | Three-family pattern |
| CDI-02 | Cast-as-Intended Challenge | Benaloh + ElectionGuard | Two-source |
| CDI-03 | Verifiable Tallying | EG + Helios | Two-source, blocked D39 |
| CDI-05 | Late-Bound Identity Binding | Helios Ch.11 | Single-source |
| CDI-06 | Governance Fingerprint | Helios Ch.11 | Single-source |
| CDI-07 | Independent Verification Observer | EG + Helios + PàV + Scanteg | Four-source pattern |

---

## ARB Review

**Status: APPROVED** — all corrections applied.

Note: Round 36A-06 (Risk Limiting Audits) has already been created in the same session. Proceed to ARB review of Round 36A-06 before moving to the Ownership Candidate Matrix.

Key items to carry into 36A-06:
- OBS-36A-04B-1 (Configuration Freeze Pattern) — test whether RLA literature confirms this
- 36A-DI-03 confirmation test — RLA is expected to confirm Recorded-as-Cast ≠ Tallied-as-Recorded
- D39 assurance level question — RLA may provide a non-cryptographic path
