# Round 36A-06 — Risk Limiting Audits Literature Review

**Date:** 2026-06-13

**Phase:** Round 36A — Verifiability Research

**Sub-document:** 36A-06 (Literature Review — Risk Limiting Audits)

**Authority:** Architecture Review Board

**Governance Foundation:**
- Rounds 36A-01 through 36A-05 (all APPROVED)
- Cross-Source Confirmation Rule (36A-03 Section 0) — binding
- Literature Saturation Rule (36A-04 post-approval) — binding
- Representation Rule (36A-05) — binding
- ARB Governance Posture: evaluate, do not defend

**Why This Review Comes Now:**
The first four systems reviewed (ElectionGuard, Helios, Scantegrity, Prêt à Voter) are all designed for cryptographic verifiability. An implicit assumption has begun to form:

```
Verifiable Election = Cryptographic Election
```

Risk Limiting Audits (RLA) is the first body of literature that can test this assumption. RLA achieves statistical assurance of correct election outcomes through sampling — not cryptography. If RLA demonstrates a credible alternative path to Tallied-as-Recorded verification, it fundamentally changes what D39 needs to resolve.

**ARB-Specified Hypotheses (binding test targets):**

```
H1: Recorded-as-Cast ≠ Tallied-as-Recorded
    (confirms 36A-DI-03 from a non-cryptographic family)

H2: Configuration Freeze Pattern
    (confirms 36A-DI-Candidate-01 — does RLA require a
    frozen election definition before auditing begins?)

H3: Independent Verification Observer
    (confirms CDI-07 — does RLA independently confirm
    that external parties can verify without system cooperation?)
```

If RLA independently confirms H1/H2/H3, several current candidates will be ready for promotion to confirmed domain insights.

**Primary Tests:**

| Test | Question |
|------|---------|
| T5 | Can strong Tallied-as-Recorded assurance be achieved without cryptographic mechanisms? |
| T6 | Does RLA confirm or challenge CDI-04 (verification representation pattern)? |
| T7 | Does RLA confirm 36A-DI-03 (H1 — Recorded-as-Cast and Tallied-as-Recorded as independent assurance levels)? |
| T8 | What architectural requirements does RLA introduce — and are they compatible with the discovered domain? |
| H2 | Does RLA require a frozen election definition before auditing begins? (36A-DI-Candidate-01 test) |
| H3 | Does RLA support independent external verification without requiring system cooperation? (CDI-07 test) |

---

## ARB Formal Decision

```
Round 36A-06 — Risk Limiting Audits Literature Review

APPROVED WITH OBSERVATIONS

Research Quality:            EXCELLENT
Cross-Family Validation:     EXCELLENT
Governance Discipline:       VERY HIGH
Architecture Neutrality:     EXCELLENT

Confidence: VERY HIGH
```

**Promotions approved:**
- 36A-DI-03: Candidate → CONFIRMED (Recorded-as-Cast ≠ Tallied-as-Recorded)
- 36A-DI-05: Candidate → CONFIRMED (Governance Configuration Freeze)
- CDI-07: Eligible for CAH-02 promotion — subject to ownership analysis completion

**Adjustments required:**
- 36A-DI-04: OBSERVED → CANDIDATE DOMAIN INSIGHT (two-audience distinction now visible across multiple families)
- OBS-36A-06-2 added: Evidence Type ≠ Evidence Quality

**Next authorised step: Round 36A-08 — Ownership Candidate Matrix**

---

## Source

**Body of literature:** Risk Limiting Audits (RLA)

**Primary documents:**
- Stark, P. (2008). Conservative Statistical Post-Election Audits. *Journal of the American Statistical Association*
- Stark, P. & Wagner, D. (2012). Evidence-Based Elections. *IEEE Security & Privacy*
- Rivest, R. & Shen, E. (2012). A Bayesian Method for Auditing Elections
- Lindeman, M. & Stark, P. (2012). A Gentle Introduction to Risk-Limiting Audits. *IEEE Security & Privacy*
- Stark, P. et al. (2020). Sets, Voting, and Auditing

**Nature:** Statistical post-election audit methodology. Uses probability theory and statistical sampling of ballots/records to determine with high confidence whether the announced election outcome is correct. Does not require cryptographic infrastructure — requires only accurate ballot records and a reliable random sampling mechanism.

**Architectural family:** DIFFERENT from all previously reviewed systems. RLA is not an E2E-V system. It does not address Cast-as-Intended or Recorded-as-Cast. It addresses Tallied-as-Recorded through statistical evidence, not cryptographic proof.

---

## Section 1 — RLA System Overview

### What Risk Limiting Audits Do

An RLA is a post-election audit that provides statistical guarantees about election outcome correctness. It answers one question:

```
If the announced winner actually lost,
what is the probability this audit would catch that?
```

The answer is 1 - risk_limit. If risk_limit = 5%, there is at most a 5% chance the audit fails to catch an incorrect outcome.

### Core Mechanisms

| Mechanism | Purpose |
|-----------|---------|
| Cast Vote Records (CVRs) | A digitally recorded audit trail of each ballot's interpretation |
| Random Sampling | Statistical sampling of ballots/CVRs to compare against physical records |
| Risk Measure | A p-value-like quantity accumulating evidence of outcome correctness |
| Stopping Rule | The audit stops when sufficient statistical evidence is accumulated |
| Escalation | If the risk threshold is not met, escalate to full hand count |

### RLA Variants

| Type | Method | Evidence Quality |
|------|--------|-----------------|
| Ballot Polling | Randomly sample physical ballots; tally sampled votes | Lower efficiency; higher sample size |
| Ballot Comparison | Compare CVR interpretation with physical ballot interpretation | Higher efficiency; requires accurate CVRs |
| Batch Comparison | Compare batch tallies with physical batch recounts | Intermediate |
| SHANGRLA | General framework unifying all RLA types | Highest theoretical rigor |

### What RLA Does NOT Do

- Does NOT verify Cast-as-Intended (before submission)
- Does NOT verify Recorded-as-Cast (at recording time)
- Does NOT replace cryptographic ballot integrity mechanisms
- Does NOT provide individual voter verification
- Addresses ONLY: was the announced outcome computed correctly from the ballots as recorded?

---

## Section 2 — Hypothesis Test Results

### T5 — Can Strong Tallied-as-Recorded Assurance Be Achieved Without Cryptography?

**RLA result:**
YES. RLA provides a statistically rigorous, publicly auditable method for verifying that the announced election outcome is correct — without homomorphic encryption, without zero-knowledge proofs, without a cryptographic bulletin board.

The statistical guarantee is formal and quantifiable: if the announced winner actually lost, the probability of the audit failing to detect this is bounded by the risk limit (e.g., 5%). This is a strong, meaningful assurance claim.

**T5 Conclusion:**
CONFIRMED. Strong Tallied-as-Recorded assurance is achievable without cryptographic mechanisms. RLA is the first reviewed literature family that demonstrates this.

**Impact on the emerging assumption:**
The pattern from ElectionGuard, Helios, Scantegrity, and Prêt à Voter suggested that verifiable elections require cryptographic infrastructure. RLA challenges this assumption specifically for Tallied-as-Recorded:

```
Cryptographic tallying (E2E-V homomorphic):
  Mathematical proof of correct count.
  Verifiable by anyone with the public record.

RLA:
  Statistical evidence of correct outcome.
  Verifiable by any auditor with access to physical ballots/CVRs.
```

Both provide strong assurance. They are different assurance models, not different quality levels.

**OBS-36A-06-2 — Evidence Type ≠ Evidence Quality (ARB-added):**

```
OBS-36A-06-2

Evidence Type
        ≠
Evidence Quality

Cryptographic proof and statistical evidence
are different assurance mechanisms.

They are not a quality hierarchy.

Cryptographic tallying:
  Mathematical proof of correct count.
  Verifiable by anyone with the public record.
  Requires cryptographic infrastructure.

Statistical auditing:
  Probabilistic evidence of correct outcome.
  Verifiable by any auditor with ballot/CVR access.
  Requires accurate records and trusted randomness.

Both provide strong, meaningful assurance.
The appropriate choice is a constitutional question,
not a preference for "stronger" vs "weaker."

Do not use this observation to pre-select
Option B over Option A in the D39 Resolution Framing.
```

---

### T6 — Does RLA Confirm or Challenge CDI-04 (Verification Representation Pattern)?

**RLA result:**
PARTIAL CONFIRMATION WITH IMPORTANT QUALIFICATION.

RLA requires Cast Vote Records (CVRs) — a digitally recorded representation of each ballot's interpretation. These CVRs are the audit trail that enables statistical sampling. For ballot comparison audits, CVRs must be publicly accessible so that auditors can compare them against physical ballots.

**Confirmation:** RLA confirms that an external, accessible representation of vote evidence is needed for verification. CVRs function as the verification representation in the RLA context.

**Important qualification:** In RLA, the verification representation (CVRs) serves a different purpose than in E2E-V systems. CVRs are not accessed by individual voters to verify their own vote — they are accessed by auditors to statistically verify the tally. The representation serves auditor verification, not individual voter verification.

**CDI-04 nuance from RLA:**
The verification representation pattern holds, but RLA distinguishes two audiences for that representation:
- Individual voter: needs receipt + accessible record (E2E-V pattern)
- External auditor: needs CVRs or ballot records (RLA pattern)

These may be different representations serving different verification purposes.

---

### T7 — Does RLA Confirm 36A-DI-03 (Recorded-as-Cast and Tallied-as-Recorded as Independent Assurance Levels)?

**RLA result:**
STRONGLY CONFIRMED.

RLA is entirely focused on Tallied-as-Recorded — it explicitly makes no claims about Recorded-as-Cast. The Stark & Wagner (2012) paper explicitly separates:
- Ballot integrity (were ballots recorded correctly?) — not RLA's concern
- Outcome correctness (was the tally correct?) — RLA's concern

RLA treats ballots as given and verifies the tally over those ballots. If ballots themselves were recorded incorrectly (Recorded-as-Cast failure), RLA will not detect this. RLA assumes Recorded-as-Cast has been addressed by other means.

**T7 Conclusion:**
36A-DI-03 CONFIRMED by an independent, non-cryptographic source.

**36A-DI-03 elevated from CANDIDATE DOMAIN INSIGHT to CONFIRMED DOMAIN INSIGHT:**

```
36A-DI-03 (CONFIRMED)

Recorded-as-Cast and Tallied-as-Recorded
are independent assurance levels.

A system may achieve Tallied-as-Recorded assurance
without addressing Recorded-as-Cast.

A system may achieve Recorded-as-Cast evidence
without cryptographic Tallied-as-Recorded.

Both are explicit in the literature:
  - Scantegrity: Recorded-as-Cast only (no cryptographic tally)
  - RLA: Tallied-as-Recorded only (takes Recorded-as-Cast as given)

Confirmed by two independent sources:
  Scantegrity (36A-05) and RLA (36A-06).
```

---

### T8 — What Architectural Requirements Does RLA Introduce?

**RLA result:**
RLA requires:

1. **Cast Vote Records (CVRs):** A digital record of each ballot's interpretation, accessible to auditors
2. **Random number generation:** A trusted source of randomness for sampling (often a public random beacon)
3. **Audit trail completeness:** Every ballot's CVR must be present and retrievable
4. **Physical ballot preservation:** Original physical ballots (or in digital systems, the authoritative ballot record) must be preserved and retrievable for comparison

**Domain compatibility assessment:**

| Requirement | NRNA Current State | Compatibility |
|-------------|-------------------|--------------|
| Ballot record (CVR equivalent) | Vote aggregate stores ballot content with checksum (VO-2, VO-3) | COMPATIBLE — vote record exists internally |
| Audit trail completeness | VoteRecorded event, Audit context (D6) | PARTIALLY COMPATIBLE — events recorded; accessible? |
| Auditor access to records | Internal audit records exist; external access not designed | GAP — accessibility for auditors not modeled |
| Trusted randomness source | Not discovered | GAP — no random beacon or equivalent |
| Physical ballot equivalent | Digital-native system; no physical ballot | DESIGN QUESTION — what is the authoritative audit record? |

**T8 Conclusion:**
RLA is technically compatible with the discovered domain model in principle — the Vote aggregate's internal records (ballot content, checksums, VoteRecorded events) are structurally equivalent to CVRs. However, RLA requires these records to be accessible to auditors — which is currently an internal-only capability (same gap identified by OBS-36A-02-3).

---

## Section 3 — New Findings from RLA

### Finding RLA-01: Two Distinct Verification Audiences

**Finding:**
RLA explicitly distinguishes between individual voter verification and auditor verification. Individual voters verify their own vote (individual verifiability). Auditors verify the tally is correct (outcome verifiability). These serve different audiences and may require different verification representations.

**Domain Relevance:**
SIGNIFICANT. The Round 36A literature has so far treated "verification" as a single concern. RLA introduces a formal distinction:

```
Individual Verifiability:
  Voter → "Was my vote recorded?"

Outcome Verifiability (Tallied-as-Recorded):
  Auditor → "Is the announced result correct?"
```

These may require different architectural elements. OBS-36A-05-2 (Verification Representation ≠ Verification Ownership) becomes even more important: ownership of individual verification may be different from ownership of outcome verification.

**36A-DI-04 — Domain Insight:**

```
36A-DI-04

Verifiability has at least two distinct audiences:

  Individual Voter:
    "Was my vote recorded and included?"
    (Individual Verifiability)

  Election Auditor / Observer:
    "Is the announced outcome correct?"
    (Outcome Verifiability / Tallied-as-Recorded)

These audiences may require different:
  - Verification representations
  - Verification mechanisms
  - Ownership responsibilities

Source: RLA literature (Stark & Wagner 2012),
        confirmed against E2E-V literature pattern.

Visibility: Must inform D42B resolution and ownership analysis.
```

---

### Finding RLA-02: Statistical vs Cryptographic Assurance Trade-offs

**Finding:**
RLA and cryptographic E2E-V approaches represent different assurance models with different trade-offs:

| Dimension | E2E-V Cryptographic | RLA Statistical |
|-----------|--------------------|----|
| Assurance type | Mathematical proof | Statistical bound |
| Verification access | Anyone with public record | Auditors with ballot access |
| Individual verifiability | Yes (built-in) | No |
| Tally verifiability | Yes (homomorphic) | Yes (statistical) |
| Infrastructure cost | High (cryptographic) | Lower (statistical sampling) |
| Physical record dependency | No (digital-native) | Yes (or equivalent) |
| Deployment context | Any digital system | Requires auditable records |

**Domain Relevance:**
NRNA is digital-native. RLA's traditional form requires physical ballots for comparison. However, the statistical principle applies to digital systems if Cast Vote Records (CVRs) are available and auditable. The question for NRNA is: are the Vote aggregate's internal records sufficient to serve as CVR equivalents for statistical audit?

---

### Finding RLA-03: Public Random Beacon Requirement

**Finding:**
Strong RLA implementations require a publicly verifiable random number generator (a "random beacon") for selecting which ballots to audit. Without a trusted, unpredictable random source, the audit can be manipulated by pre-arranging which ballots will be sampled.

**Domain Relevance:**
NRNA has no discovered random beacon or equivalent. If RLA-style auditing is pursued, this infrastructure gap must be addressed. A public random beacon is itself a form of "publicly accessible verification artifact" — but one that serves the audit process rather than individual voter verification.

**OBS-36A-06-1 — Random Beacon Gap:**
If statistical tally auditing is constitutionally required, a public random beacon or equivalent trusted randomness source becomes an infrastructure requirement. This is not in any discovered aggregate's scope.

---

### Finding RLA-04: RLA Does Not Replace Individual Verifiability

**Finding:**
RLA literature explicitly states that RLA does not provide individual voter verification. A voter cannot use RLA to verify their own vote was counted. Stark & Wagner (2012): "RLA addresses the question of outcome correctness, not the question of whether any particular ballot was recorded as intended."

**Domain Relevance:**
RLA and E2E-V are complementary, not competing. A complete verifiable election system might combine:
- E2E-V mechanisms for individual voter verification (Cast-as-Intended, Recorded-as-Cast)
- RLA for statistical outcome verification (Tallied-as-Recorded)

Neither replaces the other. This is the clearest evidence yet that the three assurance levels (36A-DI-01, 36A-DI-03) are genuinely independent and may require distinct architectural solutions.

---

## Section 4 — Domain Insights Register (Updated)

| Insight | Statement | Evidence | Status |
|---------|-----------|---------|--------|
| 36A-DI-01 | Cast-as-Intended ≠ Recorded-as-Cast — irreversible commit point separates them | Benaloh + ElectionGuard | CONFIRMED |
| 36A-DI-02 | Receipt enables verification access; receipt alone does not complete verification | Four E2E-V systems | CONFIRMED |
| 36A-DI-03 | Recorded-as-Cast and Tallied-as-Recorded are independent assurance levels | Scantegrity + RLA (explicit) | CONFIRMED |
| 36A-DI-04 | Individual verifiability and outcome verifiability serve different audiences; may require different mechanisms | RLA (explicit) + E2E-V literature (implicit across four systems) | CANDIDATE DOMAIN INSIGHT — visible across multiple families; not yet fully confirmed |
| 36A-DI-05 | Governance configuration must become immutable before vote collection or outcome verification begins | EG + Helios + PàV + RLA (H2 confirmed) | CONFIRMED — promoted from DI-Candidate-01 |

---

## Section 5 — D39 Impact

D39 asks: who owns Results/Tallying?

RLA introduces a critical framing for this question:

**Before RLA review:** The literature suggested Tallied-as-Recorded required cryptographic homomorphic tallying — a major computational infrastructure change.

**After RLA review:** Strong Tallied-as-Recorded assurance is achievable via statistical audit — requiring accessible vote records and trusted sampling, not cryptographic computation.

**D39 Resolution Framing (updated after RLA):**

When D39 is eventually resolved, the following question must now be included:

```
What assurance level does the NRNA constitution require
for Tallied-as-Recorded?

Option A: Statistical outcome assurance (RLA-style)
  — requires accessible CVR-equivalent records and trusted sampling

Option B: Mathematical cryptographic proof (E2E-V homomorphic)
  — requires encrypted ballots and public tally computation

Option C: Both (for defence in depth)
  — highest assurance; highest cost

The constitutional requirement determines the option.
The domain does not choose the assurance level.
The constitution does.
```

---

## Section 6 — CDI and Pattern Status Summary

| Item | Status After RLA Review |
|------|------------------------|
| CDI-04 (Verification Representation) | CONFIRMED PATTERN — RLA confirms accessible records needed; adds auditor audience to the picture |
| CDI-02 (Cast-as-Intended Challenge) | NO CHANGE — RLA does not address Cast-as-Intended |
| CDI-03 (Verifiable Tallying) | SUBSTANTIALLY INFORMED — RLA provides non-cryptographic alternative; D39 must determine required assurance level |
| OBS-36A-02-3 | CONFIRMED PATTERN — RLA uses CVR accessibility; consistent with pattern |
| OBS-36A-05-1 (Vote Recording ≠ Verification Representation) | CONFIRMED — RLA distinguishes CVRs (verification representation) from ballot content (internal record) |
| OBS-36A-05-2 (Representation ≠ Ownership) | REINFORCED — RLA introduces auditor as second verification audience; ownership question deepens |

---

## Section 6B — H2 and H3 Hypothesis Results

### H2 — Configuration Freeze Pattern (36A-DI-Candidate-01 Test)

**RLA result:**
YES. RLA audits are performed against a defined, fixed election — the set of valid ballots, the cast vote records (CVRs), and the announced outcome are all fixed before the audit begins. An RLA cannot begin while the election definition is still mutable. If CVRs can be modified after the audit starts, the statistical guarantee collapses.

The Stark & Wagner (2012) framing makes this explicit: the audit tests a specific, declared outcome against a specific, fixed set of records. Mutability of either invalidates the audit.

**H2 Conclusion:**
36A-DI-Candidate-01 (Configuration Freeze Pattern) CONFIRMED by a fourth independent source from a non-cryptographic family.

```
36A-DI-Candidate-01 → CONFIRMED DOMAIN INSIGHT

36A-DI-05

Governance Configuration Freeze

Governance/election configuration
must become immutable
before vote collection or outcome verification begins.

Confirmed across four independent sources:
  - ElectionGuard (key ceremony + parameter publication)
  - Helios (election fingerprint broadcast)
  - Prêt à Voter (ballot commitment printing)
  - Risk Limiting Audits (fixed CVRs required for audit validity)

This is a domain principle, not a technology choice.
It applies regardless of cryptographic approach.
```

---

### H3 — Independent Verification Observer (CDI-07 Test)

**RLA result:**
YES. RLA is explicitly designed for independent verification — the entire framework is structured so that any party with access to the cast vote records (CVRs), the reported outcome, and the random seed can independently reproduce the audit. The auditor does not need system cooperation beyond access to the public records.

Stark (2008) frames this as a foundational property: the audit must be reproducible by an independent party. An audit that can only be performed by the election authority is not a meaningful audit.

**H3 Conclusion:**
CDI-07 (Independent Verification Observer) CONFIRMED by a fourth independent source.

All four reviewed systems (ElectionGuard, Helios, Prêt à Voter, Scantegrity, RLA) independently converge on the same pattern:

```
External independent verification
without requiring system cooperation
is a recurring constitutional transparency property
across all reviewed election integrity systems.

CDI-07 now has four-source confirmation.
Consider for CDI → CAH promotion pending
Literature Saturation Rule assessment.
```

**Literature Saturation Rule check:** CDI-07 now has four independent sources across three architectural families (E2E-V homomorphic, mix-net, statistical audit). This exceeds the three-family threshold. CDI-07 is eligible for promotion to **Candidate Architecture Hypothesis (CAH-02)** — ARB approved this promotion.

**Governance clarification (ARB):**

```
CAH-02

Independent Verification Observer

Promoted from CDI-07.
Literature Saturation Rule met: four sources, three families.

CAH-02 remains a Candidate Architecture Hypothesis
until ownership analysis is completed.

The literature proves the pattern exists.
The literature does not prove who owns it inside NRNA.

That is a D42B-related question.
Resolution required before CAH-02 can become ADR-eligible.
```

---

## Section 7 — What RLA Does NOT Resolve

| Item | Status |
|------|--------|
| D42B — verifiability ownership scope | Substantially informed by 36A-DI-04 (two audiences); formal resolution in 36A-08/09 |
| D39 — Results/Tallying ownership | D39 resolution framing updated; assurance level options clarified; D39 still unresolved |
| Candidate D43 | NOT RELEVANT |
| Constitutional requirement for external verifiability | NOT ESTABLISHED |
| Individual verifiability mechanism for NRNA | NOT ADDRESSED by RLA; RLA focuses on outcome verifiability |

---

## ARB Decision

```
Round 36A-06 — Risk Limiting Audits Literature Review

APPROVED

All corrections applied.
```

**Confirmed:**
- T5 (RLA achieves strong Tallied-as-Recorded without cryptography): APPROVED
- T7 / H1 (36A-DI-03 Confirmed): APPROVED
- H2 (36A-DI-05 Confirmed — RLA confirms configuration immutability requirement across non-E2E-V family): APPROVED
- H3 (CDI-07 → CAH-02 eligible, four-source, three-family): APPROVED
- 36A-DI-04 elevated to CANDIDATE DOMAIN INSIGHT: APPLIED
- OBS-36A-06-2 added: APPLIED
- D39 Resolution Framing (Option A/B/C): APPROVED as framing, not design decision
- OBS-36A-06-1 (random beacon gap): APPROVED as observation only

**Round 36A-08 (Ownership Candidate Matrix): AUTHORIZED**

The largest remaining question is no longer what patterns exist. It is who owns verification, auditability, and assurance inside NRNA. That is a synthesis problem. Proceed to 36A-08.
