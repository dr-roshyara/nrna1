# Round 36A-05 — Scantegrity / Prêt à Voter Literature Review

**Date:** 2026-06-13

**Phase:** Round 36A — Verifiability Research

**Sub-document:** 36A-05 (Literature Review — Scantegrity / Prêt à Voter)

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 36A-01 Domain Verifiability Baseline (APPROVED)
- Round 36A-02 Verifiability Concept Catalog (APPROVED)
- Round 36A-03 ElectionGuard Review (APPROVED)
- Round 36A-04 Helios/Benaloh Review (APPROVED)
- Cross-Source Confirmation Rule (36A-03 Section 0) — binding
- Literature Saturation Rule (36A-04 post-approval) — binding
- ARB Governance Posture: evaluate, do not defend

**Round 36A Representation Rule (established this review — binding):**

```
Do not assume:

  Verification Surface = Public Bulletin Board

The literature currently supports:

  Verification Surface
      =
  Publicly Accessible Verification Representation

The implementation form remains open.
This prevents premature architectural convergence
on one implementation style.
```

**Primary Purpose of This Review — Hypothesis Testing:**

| Test | Hypothesis | Target |
|------|-----------|--------|
| T1 | Does OBS-36A-02-3 pattern hold outside E2E-V homomorphic family? | Three-family pattern test |
| T2 | Can anonymity + individual verifiability coexist? | Four-system confirmation |
| T3 | Does receipt alone complete verification? | Scope and precision test |
| T4 | Is public publication required, or is accessible representation sufficient? | Key D42B question |

---

## ARB Formal Decision

```
Round 36A-05 — Scantegrity / Prêt à Voter Literature Review

APPROVED WITH REQUIRED CORRECTIONS APPLIED

Research Quality:        EXCELLENT
Cross-Family Analysis:   EXCELLENT
DDD Discipline:          VERY HIGH
Governance Discipline:   VERY HIGH (after corrections)

Confidence: VERY HIGH
```

**Corrections applied (ARB required):**
1. CAH-01 → CDI-04 — three families confirm a recurring pattern, not a required architecture; all reviewed systems remain within E2E-V broader family
2. 36A-DI-02 rewritten — "receipt alone does not complete verification" (not "proof of nothing")
3. OBS-36A-05-1 added — Vote Recording ≠ Verification Representation
4. Representation Rule established
5. 36A-DI-03 elevated to CANDIDATE DOMAIN INSIGHT (recurring pattern across three systems)

**Round 36A-05: APPROVED**
**Round 36A-06 (Risk Limiting Audits): AUTHORIZED**

**OBS-36A-05-2 (added post-approval):**

```
OBS-36A-05-2

Verification Representation
        ≠
Verification Ownership

All four reviewed systems demonstrate
that something is published for verification.

None of them answer:
  Who owns verification?

Do not conclude:

  Verification Representation exists
        therefore
  Verification Context exists

That would be a governance error.
Ownership is a domain question, not a literature question.
D42B remains open until domain analysis assigns ownership.
```

---

## Sources

**System 1:** Scantegrity / Scantegrity II (Chaum et al., 2008–2009)

**Primary documents:**
- Chaum, D. et al. (2008). Scantegrity: End-to-End Voter-Verifiable Optical-Scan Voting
- Chaum, D. et al. (2009). Scantegrity II: End-to-End Verifiability Using Invisible Ink Confirmation Codes
- Takoma Park 2009 municipal election deployment

**Nature:** Paper-ballot enhancement system. Adds invisible-ink confirmation codes to standard optical-scan paper ballots. A different architectural family from E2E-V homomorphic systems.

---

**System 2:** Prêt à Voter (Ryan et al., 2005+)

**Primary documents:**
- Ryan, P. et al. (2005). Prêt à Voter with Re-Encryption Mixes
- Ryan, P. & Schneider, S. (2006). Prêt à Voter with Paillier Encryption

**Nature:** Cryptographic paper ballot system using randomized candidate ordering and mixnet-based decryption. A third architectural family — distinct from both ElectionGuard/Helios (homomorphic) and Scantegrity (confirmation codes).

---

## Section 1 — System Overviews

### Scantegrity Overview

Scantegrity adds a verification layer to existing optical-scan paper ballot systems without replacing them.

**Core mechanism:**
1. Ballots printed with hidden confirmation codes per bubble (invisible ink, revealed only when voter marks choice)
2. Voter marks their chosen candidate — the act of marking physically reveals the confirmation code
3. Voter records their confirmation code on a receipt stub
4. After election, all confirmation codes for all ballots are published on a public web bulletin board
5. Voter looks up their code on the bulletin board to verify it was recorded

**Key characteristics:**
- Requires physical paper ballots — confirmation code mechanism depends on physical invisible ink
- Codes are anonymous — "this selection was recorded" without revealing which voter
- No cryptographic proof of tally correctness — relies on traditional audit methods

### Prêt à Voter Overview

**Core mechanism:**
1. Ballots pre-printed with randomized candidate ordering (different per ballot)
2. Candidate ordering encoded in a cryptographic "onion" on a detachable left strip
3. Voter marks choice position on the right side; keeps the right side (receipt/"worm")
4. Left strip (decryption key) is destroyed at the booth
5. Receipt is submitted; all receipts committed to a public bulletin board
6. Mixnet of trustees shuffles and re-encrypts committed receipts, then decrypts
7. Voter verifies their receipt appears on the bulletin board

**Key characteristics:**
- Anonymity from randomized ordering — voter marks a position, not a candidate name
- Choice mapping destroyed when left strip is separated at booth
- Mixnet shuffling: anonymity through shuffling, not homomorphic computation
- Published record is a transformed, anonymised representation — not the original receipts in original form

---

## Section 2 — Hypothesis Test Results

### T1 — Does OBS-36A-02-3 Pattern Hold Outside E2E-V Homomorphic Family?

**Scantegrity result:**
YES. Public web bulletin board where all confirmation codes are published. Voters verify by looking up their code. Verification surface (public bulletin board) is architecturally distinct from vote recording (marked paper ballot + optical scan).

**Prêt à Voter result:**
YES. Public bulletin board where all submitted receipts are committed. Mixnet shuffles committed receipts into anonymized form for tallying. Voters verify their receipt is present on the bulletin board. Verification surface (public bulletin board) is architecturally distinct from vote recording.

**T1 Conclusion — PATTERN CONFIRMED ACROSS THREE FAMILIES:**

| Family | System | Verification Surface |
|--------|--------|---------------------|
| E2E-V homomorphic | ElectionGuard, Helios | Public record (encrypted ballots) |
| Confirmation codes | Scantegrity | Public record (confirmation codes) |
| Mixnet | Prêt à Voter | Public record (shuffled receipts) |

The pattern of separating internal vote recording from an externally accessible verification representation holds across three independent architectural families.

**Governance note:** All four systems remain within the broader family of "systems designed for verifiable elections." The pattern is consistent but it has not been tested against systems that are NOT specifically designed for E2E-V. Risk Limiting Audits (36A-06) will provide a different family perspective — traditional audit approach, not cryptographic. This will test whether the verification representation pattern is truly universal or specific to cryptographic E2E-V families.

**CDI-04 (promoted from CDI-01 cross-source work):** Three families confirm the pattern; CDI-04 replaces the earlier CDI-01 with stronger evidence but retains CDI status pending non-cryptographic family review.

---

### T2 — Can Anonymity + Individual Verifiability Coexist?

**Scantegrity result:**
YES. Confirmation codes reveal "this selection was recorded" — no voter identity or vote choice disclosed. Anonymity preserved; individual verifiability present.

**Prêt à Voter result:**
YES. Randomized ordering means receipt shows "I marked position X" — not "I voted for candidate Y." Choice mapping destroyed at booth. Anonymity and individual verifiability coexist through the split ballot design.

**T2 Conclusion:**
CONFIRMED ACROSS FOUR SYSTEMS. Mechanisms differ:
- Homomorphic systems: encrypted ballot reveals nothing
- Scantegrity: confirmation code is anonymous
- Prêt à Voter: randomized ordering destroys choice-position mapping

**RH-1 STATUS:** SUBSTANTIALLY SUPPORTED. Four systems demonstrate that anonymity and individual verifiability can coexist under certain design assumptions. The question is now which mechanism is appropriate for NRNA's digital context — not whether coexistence is achievable. "Demonstrated coexistence" is the correct characterisation, not "universal compatibility proven."

---

### T3 — Does Receipt Alone Complete Verification?

**Scantegrity result:**
The confirmation code (receipt) requires the public bulletin board to complete verification. The code is meaningful only when matched against the public record.

**Prêt à Voter result:**
The voter's receipt (the worm) proves submission but does not complete verification without checking the bulletin board.

**T3 Conclusion:**
Confirmed across all four systems. Corrected framing:

```
Receipt enables verification access.
Receipt alone does not complete verification.
```

**Important precision correction (ARB-required):**
The prior draft stated "receipt alone = proof of nothing." This was too strong. The domain already has `verifyByReceipt` and `proveParticipation` capabilities (Round 24A). A receipt is not nothing — it provides proof of participation and submission. What it does not provide, without an accessible record, is verification completion. That is the narrower and correct claim.

**36A-DI-02 — Domain Insight (revised):**

```
36A-DI-02

A receipt enables verification access.
A receipt alone does not complete verification.

Verification completion requires
a publicly accessible verification representation
against which the receipt can be validated.

This holds across four independent systems:
  - ElectionGuard (tracking code + election record)
  - Helios (tracking URL + bulletin board)
  - Scantegrity (confirmation code + web bulletin board)
  - Prêt à Voter (receipt/worm + bulletin board)

Distinction from earlier draft:
  The receipt is NOT "proof of nothing."
  The receipt proves participation and submission.
  It does not prove recording or inclusion without
  an accessible verification representation.

Source: T3 hypothesis test across four independent systems.
Visibility: Must remain visible through D42B resolution.
```

---

### T4 — Is Public Publication Required, or Is Accessible Representation Sufficient?

**Scantegrity result:**
Public publication is used. However, Scantegrity publishes confirmation codes — not the original marked choices or voter identities. The published representation is already transformed.

**Prêt à Voter result:**
Public publication is used. The bulletin board contains shuffled, re-encrypted receipts — not the original submitted receipts in their original form. The published representation is a privacy-preserving transformation.

**T4 Finding — NUANCED:**
All four systems use publicly accessible verification. However, none of them publish raw vote data. Every system publishes a transformed, anonymised representation:
- ElectionGuard: encrypted ballots (not decrypted choices)
- Helios: encrypted ballots (not decrypted choices)
- Scantegrity: confirmation codes (not marked choices)
- Prêt à Voter: shuffled re-encrypted receipts (not original receipts)

**T4 Conclusion:** The consistent pattern across four systems is not "public bulletin board" — it is "publicly accessible, privacy-preserving verification representation." The form varies; the accessibility and transformation properties are consistent.

**OBS-36A-05-1 — Vote Recording ≠ Verification Representation:**

```
OBS-36A-05-1

All four reviewed systems separate:

  Vote Recording
      (internal, contains actual selection)

from

  Verification Representation
      (external, publicly accessible,
       privacy-preserving transformation)

This is a stronger observation than "needs a bulletin board."

The pattern is:

  A privacy-preserving transformation of vote evidence
  is published to an accessible location.

The Vote aggregate's internal records are never
directly exposed in any reviewed system.

This observation has direct VO-1 implications:
  A Verification Representation context that publishes
  a transformed representation of vote evidence
  can be VO-1-compatible by design.

Carry through all subsequent reviews and into D42B resolution.
```

---

## Section 3 — New Findings

### Finding SP-01: Scantegrity Digital Applicability Limitation

**Finding:** Scantegrity's confirmation code mechanism depends on physical invisible ink — no direct digital equivalent exists.

**Domain Relevance:** NRNA is digital-native. Scantegrity cannot serve as a direct design blueprint. However, the architectural principle (a token issued at mark time, verifiable against public representation) transfers conceptually.

**Assessment:** Confirms the pattern; does not provide a directly applicable mechanism.

---

### Finding SP-02: Prêt à Voter Transformed Verification Representation

**Finding:** Prêt à Voter publishes a mixnet-processed bulletin board — shuffled, re-encrypted representations of original submissions. The published record is not the original votes.

**VO-1 Impact:** POSITIVE and significant. Prêt à Voter demonstrates that a public verification surface can be architecturally sound while preserving voter anonymity. The transformation layer is the key: publishing a privacy-preserving representation is not the same as publishing raw vote data.

**This is the most architecturally valuable finding in this review.** It directly resolves a concern about whether OBS-36A-05-1 is VO-1-compatible: if the Verification Representation context publishes a transformation (not raw Vote data), VO-1 can be preserved while external verifiability is supported.

---

### Finding SP-03: Scantegrity Tally Separation

**Finding:** Scantegrity does NOT provide cryptographic tally verification. It achieves Recorded-as-Cast verifiability but relies on traditional audit methods (paper ballot recounts, risk-limiting audits) for Tallied-as-Recorded. These are explicitly separable in Scantegrity's design.

**Domain Relevance:** Important for D39. A system can achieve strong Recorded-as-Cast without cryptographic Tallied-as-Recorded. These are independent assurance choices, not bundled requirements.

---

## Section 4 — Domain Insights Register (Updated)

| Insight | Statement | Evidence | Status |
|---------|-----------|---------|--------|
| 36A-DI-01 | Cast-as-Intended ≠ Recorded-as-Cast — independent assurance properties separated by irreversible commit point | Benaloh + ElectionGuard | CONFIRMED |
| 36A-DI-02 | Receipt enables verification access; receipt alone does not complete verification | Four systems | CONFIRMED |
| 36A-DI-03 | Recorded-as-Cast and Tallied-as-Recorded are independent assurance levels | Scantegrity + ElectionGuard + Helios (implicit) | CANDIDATE DOMAIN INSIGHT (three systems, recurring pattern) |

**36A-DI-03 elevation note:** Scantegrity explicitly implements Recorded-as-Cast without Tallied-as-Recorded. ElectionGuard and Helios implement both — but their tally verification is architecturally independent of their recording verification. Three systems implicitly confirm the separation. Elevated from OBSERVED to CANDIDATE DOMAIN INSIGHT pending confirmation from Risk Limiting Audits review.

---

## Section 5 — CDI Status Summary

| CDI | Status | Note |
|-----|--------|------|
| CDI-04 (Verification Representation Surface) | THREE-FAMILY PATTERN — CDI status maintained | All reviewed systems within E2E-V broader family; RLA review will test outside this family |
| CDI-02 (Cast-as-Intended Challenge Mechanism) | TWO-SOURCE CDI | Scantegrity/Prêt à Voter do not add independent Benaloh Challenge confirmation |
| CDI-03 (Verifiable Tallying Mechanism) | TWO-SOURCE CDI, BLOCKED BY D39 | SP-03 confirms Tallied-as-Recorded is separable and optional — strengthens the case that D39 must resolve before this CDI can advance |

---

## Section 6 — What Round 36A-05 Does NOT Resolve

| Item | Status |
|------|--------|
| D42B — verifiability ownership scope | CDI-04 + OBS-36A-05-1 substantially inform this; formal resolution in 36A-08/09 |
| D39 — Results/Tallying ownership | NOT RESOLVED — SP-03 deepens the framing: what assurance level does NRNA require? Tallied-as-Recorded is not required just because Recorded-as-Cast is desired |
| Candidate D43 | NOT RELEVANT to these systems |
| Constitutional requirement for external verifiability | NOT ESTABLISHED — governance gate for CDI-04 not yet cleared |
| T4 — private-only verification alternative | Open — no reviewed system omits accessible representation; absence of evidence is not evidence of absence |

---

## ARB Review

**Status: APPROVED** — all corrections applied.

**Round 36A-06 (Risk Limiting Audits): AUTHORIZED.**

Key tests for Round 36A-06:
1. Does RLA literature confirm or challenge OBS-36A-02-3/CDI-04? (Tests whether the pattern holds outside cryptographic E2E-V families)
2. Does RLA provide an alternative to cryptographic Tallied-as-Recorded verification? (Direct D39 relevance)
3. Does RLA confirm 36A-DI-03 (Recorded-as-Cast and Tallied-as-Recorded as independent assurance levels)?
4. Does RLA offer any evidence about minimum architecture for verifiability that differs from E2E-V systems?
