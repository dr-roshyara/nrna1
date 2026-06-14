# Round 36A-04 — Helios / Benaloh Literature Review

**Date:** 2026-06-13

**Phase:** Round 36A — Verifiability Research

**Sub-document:** 36A-04 (Literature Review — Helios / Benaloh)

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 36A-01 Domain Verifiability Baseline (APPROVED)
- Round 36A-02 Verifiability Concept Catalog (APPROVED)
- Round 36A-03 ElectionGuard Review (APPROVED)
- Cross-Source Confirmation Rule (Section 0 of 36A-03) — binding
- ARB Governance Posture: evaluate, do not defend

**Mandatory Framework:** Every finding uses the Section 6 mapping template from Round 36A-02.

**Primary Purpose of This Review:**
1. Cross-source confirm or reject OBS-36A-02-3 (public verification surface necessity)
2. Cross-source confirm or refine RH-1 (VO-1 and individual verifiability compatible)
3. Determine whether Helios offers architectural alternatives to ElectionGuard's bulletin-board model
4. Evaluate whether Benaloh's challenge protocol confirms EG-05 observation

---

## ARB Formal Decision

```
Round 36A-04 — Helios / Benaloh Literature Review

APPROVED WITH REQUIRED CORRECTIONS APPLIED

Research Quality:        VERY HIGH
Literature Analysis:     VERY HIGH
DDD Discipline:          VERY HIGH
Governance Discipline:   VERY HIGH (after corrections)

Confidence: VERY HIGH
```

**Corrections applied (ARB required):**
1. CAR-01/02/03 renamed to CDI-01/02/03 — evidence does not yet reach architecture; we have patterns, not requirements
2. OBS-36A-02-3 retained as cross-source confirmed observation — CDI-01 is the investigation it warrants, not a recommendation
3. CDI-03 certainty language softened — SQL aggregation statement qualified pending D39
4. OBS-36A-03-1 retained as confirmed concern, not confirmed solution — distributed trust is confirmed, threshold cryptography is one implementation
5. Confirmed Pattern / Required Architecture distinction applied throughout
6. HB-03 elevated as the strongest domain insight: Cast-as-Intended ≠ Recorded-as-Cast holds regardless of cryptography

**Round 36A-04: APPROVED**
**Round 36A-05 (Scantegrity / Prêt à Voter): AUTHORIZED**

**Literature Saturation Rule (established post-approval — binding for all Round 36A reviews):**

```
A pattern confirmed by 3 independent literature families
may become a Candidate Architecture Hypothesis (CAH)
and become eligible for ADR evaluation.

Not before.

CDI = investigation warranted (1–2 sources)
CAH = ADR evaluation eligible (3+ independent families)
```

Current CDI status: ElectionGuard + Helios = 2 sources. Scantegrity or Prêt à Voter needed for saturation test.

---

## Section 8 — Domain Insights Register

Domain Insights are findings that survive the removal of all technology, cryptographic mechanisms, and system-specific designs. They are observations about election integrity as a domain, not about any particular implementation.

**36A-DI-01 — Cast-as-Intended and Recorded-as-Cast Are Independent Assurance Properties**

```
36A-DI-01

Cast-as-Intended verification
and
Recorded-as-Cast verification

are independent assurance properties.

They are separated by an irreversible commitment point.

Cast-as-Intended:
  Can only be verified before ballot commitment.

Recorded-as-Cast:
  Can only be verified after ballot recording.

This separation holds regardless of:
  - ElectionGuard or Helios
  - Homomorphic encryption or mixnets
  - ZK proofs or confirmation codes
  - Digital or physical voting systems

Source: Benaloh (2006) foundational protocol (HB-03)
Independently confirmed by: ElectionGuard (EG-05)

Visibility: Must remain visible through all subsequent
            Round 36A reviews, Round 36B, and Round 36D synthesis.
```

**Architectural implication:** The NRNA voting workflow has a single irreversible commit point (ballot submission, Step 3 of 5). Cast-as-Intended verification — if constitutionally required — must precede this step. Recorded-as-Cast verification must follow it. These cannot be the same mechanism.

---

## Sources

**System 1:** Helios (Ben Adida, 2008+)

**Primary documents:**
- Adida, B. (2008). Helios: Web-based Open-Audit Voting. *17th USENIX Security Symposium*
- Adida, B. et al. — Helios v2, v3 specifications and code
- heliosvoting.org

**Nature:** Web-based E2E-V election system. Earlier and simpler than ElectionGuard. Uses homomorphic encryption or mixnet-based decryption. Focuses on practicality and deployability — used in real elections by universities and professional associations.

---

**System 2:** Benaloh Challenge (foundational protocol)

**Primary documents:**
- Benaloh, J. (2006). Simple Verifiable Elections. *USENIX/ACCURATE Electronic Voting Technology Workshop*
- Benaloh, J. & Tuinstra, D. (1994) — original receipt-free voting
- Referenced in ElectionGuard design (as the challenge mechanism in EG-05)

**Nature:** A fundamental protocol establishing the ability to verify ballot encoding without compromising ballot secrecy. Used as a component in Helios, ElectionGuard, and multiple other systems. Reviewed here as a foundational source rather than a full system.

**Evaluation posture:** Helios is reviewed for cross-source confirmation of ElectionGuard findings. Benaloh is reviewed as an independent foundational source for the challenge protocol and the theoretical basis for Cast-as-Intended verification.

---

## Section 1 — Helios System Overview

### What Helios Does

Helios is an open-audit election system providing:

1. **Recorded-as-Cast:** Voters can verify their encrypted ballot appears on the public bulletin board
2. **Tallied-as-Recorded:** Anyone can verify the tally is computed from the published bulletin board
3. **Cast-as-Intended (optional):** Via the Benaloh Challenge mechanism — voters may audit their ballot before finalising

### Core Mechanisms

| Mechanism | Purpose | Approach |
|-----------|---------|---------|
| Encrypted Ballot | Hides vote choice while enabling verification | ElGamal encryption per contest |
| Tracking URL | Individual voter receipt | Hash of encrypted ballot → URL on bulletin board |
| Public Bulletin Board | Publicly accessible election record | All encrypted ballots published online |
| Homomorphic Tally | Verifiable count | Encrypted ballots summed; aggregate decrypted once |
| Trustee Shares | Distributed decryption | Multiple trustees hold partial decryption keys |
| Zero-Knowledge Proofs | Ballot validity without revealing choice | Proof of correct encryption range |
| Benaloh Challenge | Cast-as-Intended verification | Optional audit before finalising ballot |

### Key Difference from ElectionGuard

Helios is intentionally simpler and web-deployable. It targets organisations (universities, associations) that want verifiability without requiring specialised hardware or complex guardian ceremonies. ElectionGuard targets government elections requiring the highest assurance levels.

**Implication for NRNA:** Helios's deployment context — professional associations, universities — is closer to NRNA's use case than ElectionGuard's government election context.

---

## Section 2 — Benaloh Challenge: Foundational Protocol Review

Benaloh's foundational contribution is the separation of two independent questions:

```
Question 1 (Cast-as-Intended):
  Did the system correctly encode my selection?

Question 2 (Recorded-as-Cast):
  Was my encoded ballot included in the record?
```

These are separate verification moments requiring separate mechanisms.

**Key insight for NRNA:** The domain currently has partial evidence for Question 2 (`verifyByReceipt`) but no evidence for Question 1. Benaloh's work establishes that these are distinct architectural concerns — not two aspects of the same mechanism.

**VO-1 Relevance:** Benaloh (1994) established that receipt-free voting is achievable but requires restricting what evidence the voter can carry away after voting. This is the foundational source for the receipt-freeness concept. Benaloh's theoretical work separates: (1) anonymity of vote choice, (2) verifiability of recording, and (3) receipt-freeness — three independent properties that must be explicitly designed for simultaneously.

---

## Section 3 — Mapped Findings (Cross-Source Analysis)

### Finding HB-01: Public Bulletin Board — Cross-Source Confirmation of OBS-36A-02-3

**Literature Finding:**
Helios publishes all encrypted ballots to a publicly accessible bulletin board. Voters receive a tracking URL pointing to their encrypted ballot entry. After voting closes, anyone can download the bulletin board and verify: (a) all submitted ballots are present, (b) the homomorphic tally is computed correctly from those ballots, (c) the published results match the verified tally.

**Domain Evidence:**
No public bulletin board in the discovered model. Receipt hash mechanism exists (VO-3) but verification record is internal.

**Cross-Source Pattern Confirmation:**
OBS-36A-02-3 CONFIRMED AS PATTERN BY TWO SOURCES (ElectionGuard + Helios).

Both systems use the same architectural separation:

```
Vote Recording (internal)
        ≠
Verification Surface (external, public)
```

**Confirmed Pattern:** Both reviewed systems separate receipt generation from external verification access.

**Not Confirmed:** That this separation is required for NRNA. Both systems belong to the E2E-V family. Systems outside this family may handle verification differently. Scantegrity review will test this.

**CDI-01** (see Section 4) is the candidate design investigation this pattern warrants.

**VO-1 Compatibility:**
COMPATIBLE. Both systems achieve public verification while preserving ballot secrecy — the public record reveals "this encrypted ballot is present" — not "this voter voted for X." VO-1 is preserved by design in both systems.

**D42B Impact:**
Two sources now suggest verifiability may require an architectural boundary beyond the Vote aggregate. This reinforces D42B as an open and significant governance debt — it does not resolve it.

**RH-1 Assessment:**
VO-1 is CONFIRMED COMPATIBLE with receipt-based individual verifiability in Helios, as in ElectionGuard. However, Helios confirms the receipt alone is not the verification system — the publicly accessible record completes the verification. The receipt is the key; the external record is the lock. Both systems require both.

---

### Finding HB-02: Tracking URL = Receipt Equivalence (Helios)

**Literature Finding:**
Helios provides each voter with a tracking URL — a hash-derived link pointing directly to their encrypted ballot on the public bulletin board. The voter can click this link after voting and see their ballot in the public record. This confirms recorded-as-cast.

**Domain Evidence:**
VO-3 receipt hash. `verifyByReceipt` mechanism. Vote receipt issued to voter.

**Gap:**
Same gap as EG-01 — receipt hash exists; accessible record does not. Helios confirms the receipt is only meaningful when paired with a publicly accessible record.

**Cross-Source Confirmation Status:**
CONFIRMS EG-01. The "receipt alone is not the verification system" architectural insight from Round 36A-03 is independently confirmed by Helios.

**VO-1 Compatibility:** COMPATIBLE — same as ElectionGuard analysis.

**D42B Impact:** Continues to suggest Vote aggregate handles receipt generation; external record handles verification completion.

---

### Finding HB-03: Benaloh Challenge Confirms Cast-as-Intended as Distinct Concern

**Literature Finding (Benaloh foundational):**
Benaloh's 2006 work formally establishes that Cast-as-Intended verification requires a challenge-response protocol distinct from all other verifiability mechanisms. The voter must be able to challenge the system's encoding of their ballot before committing. Once committed, Cast-as-Intended can no longer be verified. This is an irreversible moment.

**Domain Evidence:**
No challenge mechanism. Voting workflow is linear (5-step), no challenge branch.

**Gap:**
Cast-as-Intended is not merely missing a mechanism — it requires a structural workflow change. The commit point in the voting process must support a challenge branch before finalisation. This is architectural, not cosmetic.

**Cross-Source Confirmation Status:**
CONFIRMS EG-05. Cast-as-Intended is confirmed by two independent sources (Benaloh foundational paper + ElectionGuard implementation) as requiring a challenge branch prior to ballot commitment.

**Candidate Architecture Recommendation CDI-02:**
Cast-as-Intended verification requires a challenge-response capability at the ballot commitment step. This is a structural workflow change — it cannot be achieved by modifying the recording mechanism alone.

**VO-1 Compatibility:**
COMPLEX (same analysis as EG-05). Challenged (spoiled) ballots are intentionally decrypted to prove encoding correctness. Spoiled ballots are not submitted votes — VO-1 covers submitted votes. Compatibility is achievable but requires explicit design.

**D42B Impact:**
MODERATE. Cast-as-Intended is NOT in the current Vote aggregate scope. If constitutionally required, it introduces a new pre-commitment verification step into the voting workflow — a capability not currently owned by any discovered aggregate.

---

### Finding HB-04: Helios Receipt-Freeness Limitation

**Literature Finding:**
Helios does not provide strong receipt-freeness. A voter receives a tracking URL that can be used to confirm their ballot is on the bulletin board. This tracking URL could serve as a coercion vector — "show me your URL and I can verify you submitted a ballot." However, the URL does NOT reveal vote choice (encrypted ballot is public; decryption is not). This is a known and accepted limitation in Helios.

**Domain Evidence:**
VO-1 prevents vote-choice linkage. Receipt hash mechanism exists. Receipt-Freeness listed as NOT ADDRESSED (Round 24A — intentional trade-off).

**Cross-Source Confirmation:**
Helios independently confirms the same trade-off identified in Round 24A: verifiability (via receipt/tracking URL) and receipt-freeness are in tension. The NRNA model's documented trade-off (verifiability prioritised over receipt-freeness) is consistent with Helios's design choice.

**VO-1 Compatibility:**
The trade-off confirmed by Helios is: voter can prove they submitted a ballot (receipt-freeness concern) but cannot prove how they voted (VO-1 preserved). NRNA's current design makes the same trade-off.

**D42B Impact:**
LOW. This confirms that the current NRNA design posture is aligned with mature election systems on the anonymity-verifiability trade-off. No change to D42B scope from this finding.

---

### Finding HB-05: Helios Deployment Context — Professional Associations

**Literature Finding:**
Helios was designed specifically for organisations that need verifiable elections without the complexity of government-scale systems. It has been used by: IACR (International Association for Cryptologic Research), Princeton University, UCL, and various professional bodies. These are exactly the NRNA target use cases.

**Domain Evidence:**
NRNA target use cases from CLAUDE.md: corporate boards, non-profit organizations, professional associations, educational institutions.

**Gap:**
NONE. This is a deployment context alignment, not a gap finding.

**Significance:**
Helios confirms that E2E-V techniques are practically deployable in association and university contexts — they are not exclusively for government elections. If NRNA pursues verifiability enhancements, Helios provides proof-of-concept at the correct organisational scale.

**D42B Impact:**
NONE directly. Indirectly: the deployment context alignment weakens any argument that E2E-V techniques are impractical for NRNA's use case.

---

### Finding HB-06: Helios Homomorphic Tally Confirms EG-06

**Literature Finding:**
Helios uses homomorphic tallying — the same approach as ElectionGuard — where encrypted ballots are summed without decryption and the aggregate result is decrypted once. Any party can verify the tally is correctly computed from the published bulletin board.

**Domain Evidence:**
SQL aggregation (`syncResults`). No homomorphic computation. No mathematical proof of correct tally.

**Cross-Source Confirmation Status:**
CONFIRMS EG-06. Two independent systems converge on homomorphic tallying as the mechanism for Tallied-as-Recorded verification.

**Candidate Architecture Recommendation CDI-03 (blocked by D39):**
Verifiable tallying requires either (a) homomorphic computation over recorded ballots or (b) an equivalent mathematically provable count mechanism. SQL aggregation alone does not constitute verifiable tallying. This recommendation is blocked by D39 — Results/Tallying ownership must be resolved before design is warranted.

**VO-1 Compatibility:** COMPATIBLE — same as EG-06 analysis.

**D42B Impact:** SIGNIFICANT but blocked by D39. Flags for D42B resolution: if tally verifiability is in scope, it cannot reside in the Vote aggregate alone.

---

### Finding HB-07: Trustee Model — Distributed Decryption (Helios)

**Literature Finding:**
Helios uses multiple trustees who each hold a partial decryption key. The tally can only be decrypted if a threshold of trustees cooperate. Like ElectionGuard's guardian model, this prevents any single party from unilaterally learning election results prematurely.

**Domain Evidence:**
No trustee or threshold model discovered. Single election authority in current model.

**Cross-Source Confirmation Status:**
CONFIRMS OBS-36A-03-1 (Trust Distribution Question). Two independent systems (ElectionGuard guardians + Helios trustees) use threshold trust distribution as a design pattern for election authority.

**OBS-36A-03-1 Update:**
Confirmed by two independent sources: distributed trust in election authority is a real design concern in mature election systems. Both ElectionGuard (guardian model) and Helios (trustee model) use threshold-based trust distribution. The question "who is trusted to certify election legitimacy?" is confirmed as architecturally significant — not NRNA-specific.

**Governance note:** Distributed trust is confirmed as a concern. Threshold cryptography is confirmed as one implementation. Whether threshold cryptography is the right implementation for NRNA is NOT confirmed — it depends on the NRNA constitutional model's trust requirements, which have not yet been fully discovered. Round 36C must investigate what NRNA's trust model requires, not assume it must match ElectionGuard/Helios's approach.

**OBS-36A-03-1 status:** CONFIRMED CONCERN — not confirmed solution. Carry to Round 36C with elevated priority.

**VO-1 Compatibility:** NOT DIRECTLY RELEVANT — trust distribution does not affect voter-vote linkage.

**D42B Impact:** LOW for D42B. Significant for constitutional governance architecture (Round 36C).

---

## Section 4 — Candidate Design Investigations (Evidence-Based)

**Governance note:** These are Candidate Design Investigations (CDIs), not architecture recommendations. We have evidence of confirmed patterns from literature. We do not yet have confirmed requirements for NRNA. The distinction matters:

```
Confirmed Pattern:
  ElectionGuard + Helios both use a public verification surface.

Required Architecture:
  NRNA requires a public verification surface.
```

The first is confirmed. The second is not yet confirmed — it requires constitutional requirement discovery and further source review. CDI items identify where investigation is warranted, not where architecture has been decided.

---

### CDI-01: Public Verification Surface Investigation

**Type:** NEW CONTEXT CANDIDATE INVESTIGATION

**Confirmed Pattern:** ElectionGuard (EG-01/EG-03) + Helios (HB-01/HB-02) both use a publicly accessible verification surface architecturally distinct from internal vote recording.

**Confirmed Pattern Statement:** Both reviewed systems separate receipt generation (internal, Vote aggregate equivalent) from verification access (external, publicly accessible record).

**Not Yet Confirmed:** That NRNA requires this pattern. Scantegrity and other systems must be reviewed to determine whether this separation is universal or specific to the E2E-V homomorphic encryption family.

**Gap in NRNA model:** Receipt hash exists (VO-3); external verification surface does not.

**Minimum viable form if required:** Published, integrity-protected list of receipt hashes. Does not require full encrypted ballot publication.

**VO-1 Compatibility:** COMPATIBLE if only receipt hashes are published.

**D42B Impact:** If investigation confirms this is required, D42B would scope to include: Vote aggregate → receipt generation; Published Verification Record → external access. This is an open investigation, not a resolution.

**Governance Gate:** Constitutional requirement for external verifiability not yet confirmed.

**Status:** CANDIDATE DESIGN INVESTIGATION — eligible for ADR evaluation after full Round 36A completion and constitutional requirement discovery.

---

### CDI-02: Cast-as-Intended Challenge Mechanism Investigation

**Type:** NEW VOTING WORKFLOW CAPABILITY CANDIDATE INVESTIGATION

**Confirmed Pattern:** Benaloh foundational (HB-03) + ElectionGuard (EG-05) independently establish that Cast-as-Intended verification requires a pre-commitment challenge-response capability.

**Strongest Domain Insight from This Review (ARB note):** HB-03 establishes something that survives the removal of all cryptographic technology — Cast-as-Intended and Recorded-as-Cast are fundamentally different concerns separated by an irreversible commitment point. This is a domain insight, not a technology insight. It holds regardless of whether ZKP, encryption, or any other mechanism is used.

**Confirmed Pattern Statement:** Cast-as-Intended cannot be verified after ballot commitment. The challenge-response must occur before commitment. This is a structural workflow constraint, not an implementation detail.

**Not Yet Confirmed:** That NRNA requires Cast-as-Intended verification. This is a constitutional requirement question.

**Gap in NRNA model:** Linear 5-step voting workflow has no challenge branch before commitment.

**VO-1 Compatibility:** COMPLEX — spoiled (challenged) ballots are intentionally published; VO-1 applies to submitted votes, not spoiled ballots. Requires explicit design.

**D42B Impact:** Would introduce a new pre-commitment verification concern not currently in any aggregate's scope.

**Governance Gate:** Constitutional requirement for Cast-as-Intended not yet confirmed.

**Status:** CANDIDATE DESIGN INVESTIGATION.

---

### CDI-03: Verifiable Tallying Mechanism Investigation

**Type:** RESULTS/TALLYING CANDIDATE CAPABILITY INVESTIGATION

**Confirmed Pattern:** ElectionGuard (EG-06) + Helios (HB-06) both use homomorphic tallying as their verifiable count mechanism.

**Confirmed Pattern Statement:** Both reviewed systems use a mathematically provable count mechanism that can be independently verified without trusting the computing infrastructure.

**Not Yet Confirmed:** That NRNA's tally requires the same assurance level. D39 is unresolved — Results/Tallying ownership is unknown, and therefore what tally verification should mean in NRNA is not yet discovered.

**Corrected Statement:** ElectionGuard and Helios both use mathematically provable tally mechanisms. Whether NRNA requires the same assurance level remains unresolved pending D39. SQL aggregation may be appropriate for NRNA's constitutional context, or it may not — that determination requires D39 resolution first.

**VO-1 Compatibility:** COMPATIBLE.

**D42B Impact:** HIGH if tally verifiability is in scope — but cannot be assessed until D39 is resolved.

**Governance Gate:** BLOCKED BY D39.

**Status:** CANDIDATE DESIGN INVESTIGATION — also blocked by D39.

---

## Section 5 — What Helios / Benaloh Do NOT Resolve

| Item | Status After Helios/Benaloh Review |
|------|-----------------------------------|
| D42B — verifiability ownership scope | SUBSTANTIALLY INFORMED — CDI-01/03 provide candidate resolution paths; formal D42B resolution in 36A-08/09 |
| D39 — Results/Tallying ownership | NOT RESOLVED — blocks CDI-03; Helios confirms the gap is real |
| Candidate D43 — Enrollment Authority | NOT RELEVANT — neither Helios nor Benaloh addresses enrollment governance |
| D35/D37/ADH-1 — Legitimacy/Governance | PARTIALLY TOUCHED — OBS-36A-03-1 elevated by trustee model confirmation |
| RH-1 — VO-1 and individual verifiability | CONFIRMED COMPATIBLE — but receipt alone is not sufficient; bulletin board required |

---

## Section 6 — OBS-36A-02-3 Status After Helios Review

**OBS-36A-02-3: CROSS-SOURCE CONFIRMED OBSERVATION — investigation warranted.**

Cross-source pattern confirmation:
- ElectionGuard: public election record used for external verifiability
- Helios: public bulletin board used for external verifiability

Two independent systems, different cryptographic approaches, different deployment contexts, same pattern: external verifiability implemented via a publicly accessible record distinct from internal vote recording.

**Governance note:** Both systems belong to the E2E-V homomorphic encryption family — they share design assumptions. A third source from a different architectural family (mixnet, paper ballot audit, challenge-response only) must be reviewed before this pattern is treated as universal. Scantegrity and Prêt à Voter will provide that test.

**OBS-36A-02-3 status:** PATTERN CONFIRMED BY TWO SOURCES — warranting investigation (CDI-01). Not yet a required architecture. The investigation question is: is a public verification surface architecturally necessary for NRNA's verifiability goals, or is it one pattern among several?

---

## Section 7 — Scantegrity / Prêt à Voter Preview

Before Round 36A-05, note what Scantegrity and Prêt à Voter add to this analysis:

- **Scantegrity** is a paper-ballot audit overlay — primarily relevant for physical election contexts. Its verification model (confirmation codes printed on ballots) may have limited applicability to a digital-native system like NRNA.

- **Prêt à Voter** uses a mixnet approach (rather than homomorphic encryption) for tallying. If mixnet-based counting provides architectural alternatives to homomorphic tallying, it may inform CDI-03 resolution options.

These previews inform the scope of 36A-05 without prejudging its findings.

---

## ARB Review

Round 36A-04 is submitted for ARB review.

ARB must confirm:
- Does CDI-01 correctly represent the two-source confirmation of OBS-36A-02-3?
- Is the RH-1 status update (compatible confirmed; public surface still required) accurate?
- Are CDI-02 and CDI-03 correctly classified and gated?
- Is the OBS-36A-03-1 elevation (two-source trust distribution confirmation) accurate?
- Should 36A-05 proceed to Scantegrity / Prêt à Voter review, or has sufficient evidence accumulated for the Ownership Candidate Matrix (36A-08)?

**Upon ARB approval: 36A-05 (Scantegrity / Prêt à Voter) or 36A-08 (Ownership Candidate Matrix) may begin.**
