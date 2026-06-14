# Round 36B-04 — Cross-Family Pattern Extraction

**Date:** 2026-06-13

**Phase:** Round 36B — Auditability Research

**Sub-document:** 36B-04 (Cross-Family Pattern Extraction)

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 36B-01 (Auditability Baseline) — APPROVED
- Round 36B-02 (Auditability Concept Catalog) — APPROVED
- Round 36B-03 (Literature Family Evaluation) — APPROVED
- OBS-36B-03-3 — candidate cross-family insight under test in this document
- Mandatory Instructions (36B-03 ARB Decision): No architecture promotion; no new bounded contexts;
  test Auditability/Verifiability/Certification as distinct constitutional capabilities

**Central Question:**

```
OBS-36B-03-3 states that the literature increasingly treats
Auditability, Verifiability, and Certification as distinct
constitutional capabilities.

Round 36B-04 must determine whether this holds as a
genuine cross-family pattern — or whether it is an artifact
of the specific literature family examined last.
```

**Governing Rule:**

```
Compare constitutional capabilities.
Do NOT compare technologies.

Hash chains, Merkle trees, bulletin boards, and random beacons
are technology patterns.

"Can we inspect the evidence?"
"Can we verify the claims?"
"Can we accept the claims?"

These are constitutional questions.
```

---

## Section 1 — The Three Capabilities Under Test

From OBS-36B-03-3 (36B-03 Section 8, binding as candidate insight):

| Capability | Constitutional Question | 36B-02 Vocabulary |
|------------|------------------------|-------------------|
| Auditability | Can we inspect the evidence? | "Audit — can the evidence be inspected?" |
| Verifiability | Can we verify the claims? | "Verification — is this claim true?" |
| Certification | Can we accept the claims? | "Certification — has an authorized party attested that evidence satisfies criteria?" |

**What "separable" means for this test:**

The capabilities are separable if and only if:
- At least one family provides one capability without the others
- No family collapses them into a single undifferentiated concept
- The separation produces different architectural requirements

If every family requires all three simultaneously, they are not separable — they are co-requisite.
If every family treats one as sufficient for all three, they collapse.

---

## Section 2 — Family 2 as Infrastructure, Not a Peer Capability

Before the capability analysis, Family 2 (Evidence Chain and Tamper-Evidence) requires a structural clarification.

```
Family 2 provides audit infrastructure.
Family 2 does not itself constitute a constitutional capability.

Hash chains, Merkle trees, and append-only logs:
  ENABLE  auditability (records can be inspected and verified as intact)
  SUPPORT verifiability (record integrity is a precondition for valid verification)
  ARE REQUIRED FOR certification (certifier cannot rely on mutable evidence)

But:
  A hash chain that records nothing of value is not auditable.
  A Merkle tree over fraudulent records is tamper-evident but not verifiable.
  Tamper-evidence alone does not constitute certification.

Family 2's position in the capability space:

  Auditability: enables (does not constitute)
  Verifiability: enables (does not constitute)
  Certification: enables (does not constitute)
```

Family 2 is therefore excluded from the capability comparison table below — it is a cross-cutting infrastructure layer, not a peer with Families 1, 3, and 4 in the constitutional space.

This does not reduce its importance. It clarifies its role: Family 2 is the foundation on which the other three families' capabilities rest.

---

## Section 3 — Capability Analysis: Family 1 (RLA)

### What RLA Provides

RLA is a framework for statistical evidence production. An auditor samples records, computes a risk measure, and reaches a stopping decision: "the announced outcome is probably correct with at most [risk limit] probability of error."

### Capability Assessment

**Auditability:**

RLA provides Auditability directly. The framework requires auditors to physically access and inspect cast vote records (CVRs). The entire methodology is structured around evidence inspection — the auditor reviews records, not just outputs.

```
RLA Auditability: DIRECT AND PRIMARY
Mechanism: physical record inspection + statistical sampling
```

**Verifiability:**

RLA provides Verifiability via statistical evidence. The stopping criterion constitutes a probabilistic claim: "if the announced outcome is wrong, this audit would catch it with probability 1 − risk_limit."

```
RLA Verifiability: DIRECT (statistical form)
Mechanism: risk measure + stopping criterion
The claim is: outcome is correct with high statistical confidence
```

**Certification:**

RLA has the Criteria element (the risk limit — defined before auditing, public, specific) and the Evidence element (CVRs, sample results). But it lacks the Authority element: no party makes an authorized declaration that the criteria were satisfied. The audit "passing" is a mathematical event, not a declaration.

Under the 36B-02 definition (Evidence + Criteria + Authority), RLA provides:

```
RLA Certification: PARTIAL
  Evidence:   YES (CVRs, sample results)
  Criteria:   YES (risk limit — pre-defined, public, specific)
  Authority:  NO (no authorized declarant; the stopping criterion is mathematical)

RLA achieves Verifiability without Certification.
This is a concrete example of the A/V/C separation.
```

### Family 1 Separation Finding

```
F1 (RLA) confirms:
  Auditability is separable from Verifiability.
    (You can inspect records without yet having run the statistical test.)

  Verifiability is separable from Certification.
    (RLA verifies statistically, but the Criteria element exists without
     an Authority element. The risk limit is the criterion. No party
     declares it satisfied — the mathematics do.)

  This means Certification requires something RLA does not provide:
    a party with authority to make the declaration.
```

---

## Section 4 — Capability Analysis: Family 3 (E2E-V)

### What E2E-V Provides

E2E-V systems (ElectionGuard, Helios, Scantegrity) provide end-to-end verifiability through cryptographic mechanisms: encrypted ballots on a bulletin board, cryptographic proofs of correct decryption and tallying, individual receipts.

### Capability Assessment

**Auditability:**

E2E-V systems provide Auditability through the public bulletin board. Any party can inspect the encrypted ballots, decryption proofs, and tally computation.

```
E2E-V Auditability: DIRECT AND PRIMARY
Mechanism: public bulletin board — records are externally accessible and inspectable
```

**Verifiability:**

E2E-V systems provide Verifiability through cryptographic proofs. The proofs can be independently verified by any party with the public data.

```
E2E-V Verifiability: DIRECT (cryptographic form)
Mechanism: zero-knowledge proofs, homomorphic tally proofs
The claim is: tally is mathematically provably correct
```

**Certification:**

E2E-V systems provide mathematical acceptance — anyone with the public data can verify the proofs. But they do not provide authorized declaration. There is no party in ElectionGuard, Helios, or Scantegrity that declares "the election is certified." The cryptographic proofs are self-certifying in a mathematical sense, but they are not certification under 36B-02 (Evidence + Criteria + Authority) because:
- The Criteria are implicit (cryptographic proof validity is the criterion, but it is not enumerated independently)
- The Authority is absent (no designated party makes a declaration)

```
E2E-V Certification: PARTIAL
  Evidence:   YES (public bulletin board, cryptographic proofs)
  Criteria:   IMPLICIT (proof validity — not independently enumerated)
  Authority:  NO (no authorized declarant)

E2E-V achieves Verifiability without Certification.
This mirrors the RLA finding.
```

### Family 3 Separation Finding

```
F3 (E2E-V) confirms:
  Auditability is separable from Verifiability.
    (The bulletin board is readable — auditable — before any verification
     is performed. Inspection precedes proof verification.)

  Verifiability is separable from Certification.
    (Cryptographic proofs provide mathematical Verifiability.
     No party declares the election certified — the proofs
     do the work, but Authority is absent.)

  F3 and F1 agree on a core pattern:
    Both provide Auditability + Verifiability.
    Neither provides Certification (in the full Evidence+Criteria+Authority sense).
    They differ in mechanism (statistical vs cryptographic) but agree on capability scope.
```

---

## Section 5 — The Cross-Family Certification Pattern

**Methodological Note (OBS-36B-04-1):**

```
Certification is not a literature family.

Certification emerged as a cross-family capability
appearing across multiple families — in F1 (RLA) as partial
(Criteria present, Authority absent), in F3 (E2E-V) as partial
(mathematical acceptance, Authority absent), and in the ISO/OSCE/CoE
standards literature as explicit (all three elements: Evidence,
Criteria, Authority).

This section analyzes Certification as a cross-family capability
pattern, not as an independent literature family peer to F1, F2, or F3.

The analytical source for this section is the certification-focused
literature across those families, not a fourth independent literature body.
```

### What the Certification Pattern Establishes

Where certification appears in the reviewed literature, it consistently involves three elements (from 36B-02 binding definition):

- **Evidence** — auditable record that can be inspected
- **Criteria** — standards defined before the election against which evidence is evaluated
- **Authority** — a designated party whose declaration constitutes acceptance

### Capability Assessment

**Auditability:**

Certification requires that auditable evidence exists. The certifier, whether performing the audit directly or relying on trusted audit evidence, requires that records are auditable — inspectable and tamper-evident. Auditability is not the goal; it is a prerequisite for the Evidence element.

```
Certification Pattern — Auditability: REQUIRED AS PRECONDITION

Note (OBS-36B-04-2):
  The requirement is: auditable evidence must exist.
  The requirement is NOT: the certifier must personally perform the audit.

  Certification boards, election commissions, and courts often rely
  on delegated audit evidence.

  Therefore:
    Certification requires auditable evidence.
    Whether the certifier performs the audit
    or relies on trusted audit evidence
    is implementation-specific.

  This is the strongest statement yet that Auditability is separable:
  auditable evidence must exist before Certification can occur.
```

**Verifiability:**

The Certification pattern's relationship to Verifiability is conditional on what is being certified:
- To certify "the governance process was followed" (Concern A): auditable evidence is sufficient; Verifiability of a tally claim is not required.
- To certify "the tally is correct" (Concern B): Verifiability is required before Certification.

```
Certification Pattern — Verifiability: CONDITIONAL PRECONDITION
  For Concern A certification: NOT REQUIRED
  For Concern B certification: REQUIRED

This is a significant finding — see Section 7 (ordering analysis).
```

**Certification:**

The Certification pattern provides the complete three-element structure.

```
Certification Pattern — Certification: DIRECT AND PRIMARY
  Evidence:   required (auditable record must exist)
  Criteria:   required (defined before election)
  Authority:  required (designated independent party)
```

### Cross-Family Certification Finding

```
The Certification pattern confirms and sharpens the separation:

  Auditable evidence is required before Certification.
  But auditable evidence alone is insufficient for Certification.

  Verifiability is required before Concern B Certification.
  But Verifiability alone is insufficient for Certification
  (the Authority element is still missing in F1 and F3).

  Certification adds Authority to the picture.
  Authority is what makes the acceptance constitutional,
  not merely mathematical.

  A mathematical proof is Verifiable.
  A statistical audit result is Verifiable.
  Neither is Certified unless an authorized party declares it so.
```

---

## Section 6 — Cross-Family Capability Matrix

| Question | F1 (RLA) | F2 (Evidence Chain) | F3 (E2E-V) | Certification Pattern (cross-family) |
|----------|----------|---------------------|------------|--------------------------------------|
| **Does the family/pattern separate Auditability from Verifiability?** | YES — inspection of CVRs precedes statistical test | Infrastructure only — enables A; doesn't provide A or V | YES — bulletin board readable before proofs verified | YES — auditable evidence precedes certification; inspection precedes verification |
| **Does the family/pattern separate Verifiability from Certification?** | YES — RLA verifies statistically; no Authority element present | N/A | YES — cryptographic proofs verify; no Authority element present | YES — this is the pattern's core claim |
| **Can Auditability exist without Verifiability?** | YES — inspect CVRs without running statistical test | YES (infrastructure supports inspection; V is separate) | YES — read bulletin board without verifying proofs | YES — auditable evidence can exist without a verification claim |
| **Can Verifiability exist without Certification?** | YES — statistical verification is complete; Authority absent | N/A | YES — cryptographic proof is complete; Authority absent | YES — by definition: Certification adds Authority to what V left incomplete |
| **Does this family/pattern provide all three capabilities?** | NO — Auditability + Verifiability; Certification PARTIAL (no Authority) | NO — Infrastructure only | NO — Auditability + Verifiability; Certification PARTIAL (implicit criteria, no Authority) | YES — requires auditable evidence + Criteria + Authority; all three present |
| **What is the minimum constitutional capability this family/pattern implies?** | Auditability (to inspect) + Verifiability (to conclude) | Auditability enablement | Auditability + Verifiability | Auditability + context-dependent Verifiability + Certification |

**Key cross-family reading of the matrix:**

```
No family collapses the three capabilities into one.

Every family that provides Verifiability also provides Auditability.
  → Auditability appears to be a precondition for Verifiability.

No family that provides Verifiability automatically provides Certification.
  → The Authority element is the consistent gap.

The Certification pattern is the only structure that completes the picture.
It confirms: Certification adds nothing to the evidence or the proof.
It adds the authorized declaration — the constitutional act of acceptance.
```

---

## Section 7 — The Ordering Question

The matrix reveals a potential ordering constraint. This section tests it explicitly.

### Ordering Constraint: Certification Requires Auditable Evidence

**Evidence:** The Certification pattern (cross-family) requires that auditable evidence exists before Certification can occur. F1 (RLA) auditors inspect CVRs. F3 (E2E-V) certifiers access bulletin board records. The ISO/OSCE certification literature requires access to auditable evidence as the starting point.

```
Certification requires auditable evidence.

Status: CONFIRMED across all families and the Certification pattern.
Evidence:
  F1: Auditors inspect CVRs before computing risk measure
  F2: Tamper-evidence enables inspection before any verification
  F3: Bulletin board records must be accessible before proof verification
  Certification pattern: auditable evidence is the first element (before Criteria, before Authority)

Important qualification (OBS-36B-04-2):
  The requirement is that auditable evidence must exist.
  The requirement is NOT that the certifier personally performs the audit.

  Certification boards, election commissions, and courts in real
  electoral systems often certify based on delegated audit evidence.

  Therefore:
    A system that produces no auditable evidence cannot be certified.
    A system that produces auditable evidence can be certified,
    whether the certifier audits directly or relies on trusted audit evidence.

  This is a constitutional constraint, not an operational workflow prescription.
```

### Conditional Ordering: Verifiability → Certification

**Evidence:** Family 4 reveals a conditional relationship. The conditioning factor is *what is being certified:*

```
If certifying Concern A (governance process):
  Auditability → Certification (Verifiability NOT required)

  Example: certifying that the governance configuration was
  established before the election, never modified during
  voting, and is accurately recorded in audit artifacts.
  This requires inspecting the audit record (Auditability)
  but does not require proving a tally claim (Verifiability).

If certifying Concern B (tally correctness):
  Auditability → Verifiability → Certification
  (Verifiability IS required)

  Example: certifying that the announced winner is correct.
  This requires a claim about the tally (Verifiability)
  in addition to inspection of the record (Auditability).

Conditional Ordering: CONFIRMED
```

### Architectural Implication of Conditional Ordering (Candidate Finding)

```
D39 blocks Concern B Verifiability (tally ownership unresolved).

D39 therefore blocks:
  Concern B Certification (tally correctness certification)

Current evidence suggests D39 may not block:
  Concern A Certification (governance process certification)

Reasoning:
  Concern A Certification path: auditable evidence → Certification
  Verifiability of a tally claim is not required in this path.

NRNA's discovered Concern A infrastructure
  (GovernanceDecisionSnapshot, GovernanceReplayService,
   ElectionAuditLog) may support Concern A Auditability.

CANDIDATE FINDING (OBS-36B-04-3):
  If Concern A Auditability gaps are resolved (Gaps A-3, A-4)
  and if a Certification mechanism is added (Evidence + Criteria + Authority),
  Concern A Certification may be achievable without resolving D39.

  This remains contingent upon resolution of Gap A-3 (Completeness).
  Until Gap A-3 is resolved, we cannot confirm that the Concern A
  audit record is complete — and Certification cannot proceed against
  an incomplete record.

  This candidate finding narrows the scope of D39's impact on the program.
  It does not confirm that Concern A Certification is achievable now.

This is not a design recommendation.
It is a candidate constitutional capability finding for 36B-05 to carry.
```

---

## Section 8 — Pattern Confirmation: OBS-36B-03-3

OBS-36B-03-3 (from 36B-03) stated:

```
The literature increasingly treats Auditability, Verifiability,
and Certification as distinct constitutional questions.

Round 36B-04 must determine whether NRNA also treats them as
distinct — or whether its constitution collapses them into one
requirement.
```

### Test Result

The cross-family analysis provides the following evidence:

| Test | Result |
|------|--------|
| Does any family collapse A/V/C into one? | NO — no family treats them as identical |
| Does any family provide one without the others? | YES — F1 and F3 provide A+V without C; F4 requires A and conditional V to provide C |
| Is the separation architecturally meaningful? | YES — each capability has different infrastructure requirements |
| Is the ordering constraint consistent? | YES — A precedes both V and C; V precedes C only for Concern B |
| Is this finding limited to one family? | NO — confirmed across F1, F3, and F4; F2 is infrastructure across all |

**Conclusion:**

```
OBS-36B-03-3 is confirmed as a cross-family pattern.

The Auditability / Verifiability / Certification separation
is not an artifact of a single literature family.
It holds across three independent literature families (F1, F3)
and the Certification pattern that emerged across all families,
with F2 providing the infrastructure layer beneath all three.

OBS-36B-03-3 is promoted from:
  CANDIDATE CROSS-FAMILY INSIGHT

to:

  36B-CFI-01 — CONFIRMED CROSS-FAMILY INSIGHT (Auditability)

  Statement:
    The literature consistently treats Auditability, Verifiability,
    and Certification as separable constitutional capabilities.

    A system can be auditable without being verifiable.
    A system can be verifiable without being certified.

    The ordering constraints are:
      Certification requires auditable evidence
        (confirmed; independent of whether the certifier performs the audit).
      Verifiability is a conditional precondition for Certification
        — required when the claim being certified is a tally claim (Concern B);
          not required when certifying governance process (Concern A).

    The Authority element (who is authorized to declare) is the
    consistent gap in systems that provide Auditability and Verifiability.
    Authority is what Certification adds. Without it, the claim
    is mathematically verifiable but not constitutionally accepted.

  Evidence: confirmed across F1 (RLA), F3 (E2E-V), and the Certification
            pattern (cross-family); F2 (Evidence Chain) as infrastructure
  Status: CONFIRMED CROSS-FAMILY INSIGHT
  ARB Notation: 36B-CFI-01
```

---

## Section 9 — What This Means for NRNA (as constitutional questions)

These findings raise specific constitutional questions for NRNA. They are not design decisions. They are the questions that Round 36B-05 (Ownership and Architectural Impact Candidates) must carry.

**Q1: Which capabilities does the NRNA constitution require?**

```
Option A: Auditability only
  (Governance evidence can be inspected; no verification claim; no certification)

Option B: Auditability + Verifiability
  (Governance and/or tally evidence can be verified; no authorized certification)

Option C: All three
  (Evidence can be inspected, claims can be verified, and an authorized
  party can declare the election's constitutionality)

Option D: Auditability + Concern A Certification (without Concern B)
  (Governance process can be certified; tally certification deferred pending D39)

The NRNA constitution determines which option is required.
This document does not choose.
```

**Q2: Is the Auditability → Concern A Certification path achievable before D39 resolves?**

```
The cross-family analysis shows: possibly yes.

Concern A Certification does not require tally Verifiability.
It requires:
  Governance Auditability (Concern A gaps resolved: A-3, A-4, A-2)
  Criteria (governance process criteria defined pre-election)
  Authority (who is constitutionally authorized to certify governance?)

D43 constrains the enrollment dimension of Certification.
D39 does not constrain Concern A Certification directly.

Whether NRNA's constitution requires Concern A Certification
is a question for the ARB. This finding simply establishes
that D39 does not block it.
```

**Q3: What does the Authority gap mean for discovered domain roles?**

```
No discovered aggregate in NRNA holds the Authority element
required for Certification.

GovernanceState: authority over governance transitions — not Certification authority
Audit context: passive observer — explicitly not an authority (OBS-36A-08-1)
Verification context: manages verification evidence — not Certification authority

The Authority role is currently undiscovered in NRNA's domain.
Whether it belongs to an existing context, a new context, or
an external actor is a 36B-05 ownership question.

Context Explosion Risk (OBS-36B-03-C) applies:
Authority is a role. It is not automatically a bounded context.
```

---

## Section 10 — Divergences to Carry Forward

Two cross-family divergences from 36B-03 remain unresolved at the pattern level. They are deferred to 36B-05 (Ownership and Architectural Impact Candidates):

**Divergence 1: Record Location**
F1 (RLA) achieves external auditability with internal records + auditor access.
F3 (E2E-V) creates a dedicated external artifact.
Pattern extraction does not resolve which is appropriate for NRNA.
Resolved by constitutional requirement + ownership analysis in 36B-05.

**Divergence 2: Certification Mechanism**
F1 achieves implicit Criteria (risk limit) without explicit Authority.
F3 achieves implicit Criteria (proof validity) without explicit Authority.
F4 requires all three elements explicitly.
Pattern extraction confirms the Authority gap. Who holds Authority in NRNA is 36B-05.

---

## Section 11 — Findings Summary for 36B-05

**36B-CFI-01 (Confirmed Cross-Family Insight):**
Auditability / Verifiability / Certification are separable constitutional capabilities with confirmed ordering constraints (Certification requires auditable evidence; V conditional precondition for C only for Concern B).

**D39 Impact Narrowing (Candidate Finding — OBS-36B-04-3):**
D39 blocks Concern B Certification. Current evidence suggests D39 may not block Concern A Certification. This remains contingent upon resolution of Gap A-3 (Completeness). The path "Concern A auditable evidence → Concern A Certification" is a candidate path for 36B-05 to evaluate.

**Authority Gap:**
No discovered aggregate holds Certification Authority. This is the primary ownership question for 36B-05.

**Infrastructure Layer:**
Family 2 (tamper-evidence, inclusion proofs, append-only logs) is the infrastructure for all three capabilities. It is not a peer constitutional capability.

**Open Constitutional Questions (for ARB via 36B-05):**
- Q1: Which capabilities does NRNA require?
- Q2: Is Concern A Certification achievable before D39 resolves?
- Q3: Who holds Certification Authority in NRNA?
- Q4: Which record-access pattern (internal+access vs external artifact) does NRNA require?

---

## ARB Decision

```
Round 36B-04 — Cross-Family Pattern Extraction

APPROVED

Research Discipline:        HIGH
Governance Discipline:      HIGH
DDD Discipline:             HIGH
Architecture Neutrality:    HIGH
Confidence:                 HIGH

Observations applied (all corrections improved precision):

  OBS-36B-04-1: Certification is a cross-family capability, not a
                literature family. Section 5 renamed to "Cross-Family
                Certification Pattern." Scientific validity preserved:
                literature analysis produces patterns, not invented families.

  OBS-36B-04-2: Ordering constraint: "Certification requires auditable
                evidence" — not "certifier must personally audit."
                Constitutional requirement is on the evidence.
                Operational workflow (direct vs. delegated audit) is
                implementation-specific. Constitutional / procedural
                separation maintained.

  OBS-36B-04-3: D39 narrowing remains a candidate finding only.
                Contingent upon Gap A-3 (Completeness) resolution.
                Cannot certify an incomplete record regardless of D39 scope,
                audit technique, or cryptographic mechanism.

36B-CFI-01 (Confirmed Cross-Family Insight) — APPROVED:
  The literature consistently treats Auditability, Verifiability,
  and Certification as separable constitutional capabilities.

  A system can be auditable without being verifiable.
  A system can be verifiable without being certified.

  "Separable" is the correct characterization — not "distinct."
  Separable matches the evidence. Distinct overstates the independence
  the literature actually demonstrates.

  Evidence: F1 (RLA), F3 (E2E-V), Certification pattern (cross-family)
            with F2 (Evidence Chain) as infrastructure.

Mandatory Instruction Compliance:
  No architecture promoted. No bounded contexts created.
  Auditability/Verifiability/Certification tested as separable
  constitutional capabilities — confirmed by cross-family evidence.

Governing Instructions for Round 36B-05 (ARB binding):

  1. Focus on ownership questions. Do NOT discuss mechanisms
     (Merkle Trees, Bulletin Boards, ElectionGuard, Helios, Scantegrity).

  2. Six ownership questions to answer:
       Who owns Auditability?
       Who owns Verifiability?
       Who owns Certification?
       Who owns Evidence?
       Who owns Certification Criteria?
       Who owns Certification Authority?

  3. OBS-36B-05-0 (Preventive — binding from authorization):
       Do NOT assume:
         Auditability Owner = Verifiability Owner = Certification Owner

       36B-CFI-01 established that these capabilities are separable.
       Ownership may therefore also be separable.
       Ownership discovery must determine this — not assumption.

  4. OBS-36B-05-1 (Preventive — binding from authorization):
       Do NOT assume that ownership implies a new bounded context.

       Ownership discovery precedes bounded context discovery.
       They are not the same activity.

       36B-05 discovers: Owner / Responsibility / Decision Authority /
       Evidence Authority.

       36E and 37 determine: existing context vs. new context.

       The failure mode to prevent:
         Auditability Owner → Auditability Context
         Verifiability Owner → Verifiability Context
         Certification Owner → Certification Context

       That would be premature. Ownership is not bounded context design.

Round 36B-04: APPROVED
Round 36B-05 (Ownership and Architectural Impact Candidates): AUTHORIZED
```
