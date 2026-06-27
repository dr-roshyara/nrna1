# Round 36B — Closure Summary

**Date:** 2026-06-13

**Phase:** Round 36B — Auditability Research (COMPLETE)

**Sub-documents covered:**
- 36B-01 Auditability Baseline — APPROVED
- 36B-02 Auditability Concept Catalog — APPROVED
- 36B-03 Auditability Literature Family Evaluation — APPROVED
- 36B-04 Cross-Family Pattern Extraction — APPROVED
- 36B-05 Ownership and Architectural Impact Candidates — SUBMITTED FOR ARB REVIEW

**Purpose:**

```
This document freezes the output of Round 36B
before Round 36C (Threat Modeling) begins.

It prevents discovery loss when the research stream shifts.

It ensures that 36C is built on a frozen, approved foundation —
not on working documents still open to revision.

It does NOT introduce new findings.
It records what was confirmed, what was gapped, and what was deferred.
```

---

## Section 1 — Confirmed Findings

These findings are approved and frozen. They are carried into 36C and 36E as confirmed inputs.

**36B-CFI-01 — Separable Constitutional Capabilities (confirmed cross-family insight):**

```
The literature consistently treats Auditability, Verifiability,
and Certification as separable constitutional capabilities.

A system can be auditable without being verifiable.
A system can be verifiable without being certified.

"Separable" — not "distinct." The evidence shows separation;
it does not prove absolute independence.

Evidence: F1 (RLA), F3 (E2E-V), Certification pattern (cross-family).
F2 (Evidence Chain) = infrastructure layer beneath all three.
```

**Confirmed finding: Certification requires auditable evidence.**

```
The requirement is on the evidence, not on the certifier's activity.
Whether the certifier audits directly or relies on delegated audit evidence
is implementation-specific.
Certification against incomplete evidence is constitutionally indefensible
regardless of cryptographic technique, audit method, or governance procedure.
```

**Confirmed finding: Certification Authority requires structural independence.**

```
No operational aggregate can self-declare election validity.
The independence requirement (P-5 from 36B-03) requires that the Authority
element is structurally independent of the operational system being certified.

Current evidence suggests Certification Authority requires structural
independence from operational aggregates.

Whether that independence is external to NRNA,
or internal but constitutionally separated (e.g., an independent
constitutional review board or election oversight committee within NRNA),
remains an open constitutional question (NCQ-02).

What is confirmed: the Authority element cannot be owned by an
operational aggregate that also operates the election.
What remains open: whether "independence" means external or internal-but-separated.
```

**Confirmed finding: Ownership distribution.**

```
Auditability Owner ≠ Verifiability Owner ≠ Certification Owner.

The three capabilities do not share an owner in NRNA's discovered model.
This is confirmed by 36B-05 ownership analysis and is consistent with
36B-CFI-01 (the capabilities are separable).
```

**Candidate finding: D39 currently affects Concern B Certification more than Concern A.**

```
This remains a candidate finding — not confirmed (OBS-36B-04-3).

Rationale: D39 is unresolved. The future tally ownership decision may produce
outcomes (simple tally, cryptographic tally, statistical tally, external tally
verification) that change the relationship between D39 and Certification scope.

Current evidence suggests:
  D39 blocks tally Verifiability → currently blocks tally Certification (Concern B).
  The Concern A Certification path (governance process) may not require
  tally Verifiability — but DOES require Gap A-3 resolution first.

Frozen as CANDIDATE: the claim holds under current understanding of D39.
It may be refined when D39 resolves.
```

**Confirmed ordering constraints:**

```
Certification requires auditable evidence (universal).
Verifiability → Certification (conditional — Concern B claims only).
```

**Five cross-family properties (P-1 through P-5, confirmed from 36B-03):**

All literature families require these properties in any auditable system:

| Property | Description |
|----------|-------------|
| P-1 | Complete record |
| P-2 | Tamper-evident record |
| P-3 | Independent access (without operator cooperation) |
| P-4 | Pre-audit criterion definition |
| P-5 | Independence of auditor from operator |

---

## Section 2 — Confirmed Gaps

These gaps were identified during 36B research. They are confirmed as open problems. None of them is resolved. They are carried into 36C and 36E as active risks.

| Gap | Description | Status | Priority |
|-----|-------------|--------|----------|
| Gap A-3 | Completeness: audit record may not contain all events that should be present | CONFIRMED OPEN | **HIGHEST — primary auditability risk** |
| Gap A-2 | Access Pattern: internal+access vs. external artifact — NRNA's required pattern undecided | CONFIRMED OPEN | HIGH |
| Gap A-4 | Post-Election Access: unknown (placeholder from 36B-01) | OPEN — not investigated in 36B | MEDIUM |
| Gap A-1 | Evidence definition gap | PARTIAL (36B-02 Audit Evidence concept added) | LOW |

**Gap A-3 elaboration:**

```
Gap A-3 is the most serious known risk in the trustworthiness program.

The Audit context can prove what it received.
The Audit context cannot prove what should have been received.

Observed events ≠ Complete events.

No discovered aggregate in NRNA holds an expected-event registry
or completeness invariant.

Until Gap A-3 is resolved:
  - Auditability is partial (P-1 is ungapped)
  - Concern A Certification is unachievable
  - Every audit result is conditional on unverifiable completeness

Gap A-3 resolution is the prerequisite for complete auditability
and defensible certification.

Rounds 36C, 36D, and 36E can proceed without solving Gap A-3.
The gap blocks claims, not research.
```

---

## Section 3 — Ownership Findings (Frozen)

This is the ownership matrix produced by 36B-05. It is frozen as the 36C starting point.

| Capability | Owner | Status |
|------------|-------|--------|
| Auditability — inspection surface | Audit context | CANDIDATE |
| Auditability — completeness (P-1) | Undiscovered | **GAP A-3 — unowned** |
| Verifiability — governance (Concern A) | GovernanceState | PRESUMPTIVE |
| Verifiability — individual ballot | Vote aggregate | CONFIRMED |
| Verifiability — surface | Verification Representation Context | CANDIDATE |
| Verifiability — tally (Concern B) | Undiscovered | D39 BLOCKED |
| Evidence — production | Distributed (GovernanceState + Vote + Verification) | CONFIRMED |
| Evidence — aggregation | Audit context | CANDIDATE |
| Evidence — completeness | Undiscovered | **GAP A-3 — unowned** |
| Criteria — configuration | GovernanceState | PRESUMPTIVE |
| Criteria — enrollment | Undiscovered | D43 BLOCKED |
| Criteria — constitutional validity | Undiscovered | NCQ-03 |
| Authority — declaration | External actor | STRUCTURALLY EXTERNAL |

**What this matrix means for 36C:**

```
The threat model must map against ownership.

A threat against Auditability is a threat against:
  the Audit context (inspection surface)
  AND the unknown completeness mechanism (Gap A-3)

A threat against Certification is a threat against:
  the external authority
  AND the criteria definition process
  AND the evidence chain

You cannot model threats properly
until you know who owns what.

That is why 36B-05 precedes 36C.
```

---

## Section 4 — Questions Carried to Round 36C

These questions emerged from 36B and require threat modeling input.

**TC-01: Threat against completeness**

```
Gap A-3 exposes the system to evidence suppression.

If an event that should be observed is never emitted,
the Audit context cannot detect the absence.

36C must model: who can suppress events? How? What is the consequence?
```

**TC-02: Threat against external authority**

```
Certification Authority is structurally external.
External actors are a threat surface.

36C must model: can Certification Authority be corrupted, coerced, or spoofed?
What happens if the authority certifies a non-compliant election?
```

**TC-03: Threat against criteria**

```
Criteria must be defined before the election.
Constitutional criteria ownership is undiscovered.

36C must model: what happens if criteria are modified after definition?
Who can modify criteria? What is the detection mechanism?
```

**TC-04: Threat against ownership boundaries**

```
Ownership is distributed. Each ownership boundary is a potential threat surface.

GovernanceState → Audit context: can this boundary be bypassed?
Vote → Audit context: can VoteRecorded be suppressed?
External authority → NRNA: can the authority interface be manipulated?
```

---

## Section 5 — Questions Carried to Round 36E

These are the Architectural Impact Candidates (AICs) from 36B-05, frozen here for 36E.

| AIC | Description | Priority |
|-----|-------------|----------|
| AIC-36B-01 | Completeness Mechanism — who holds the expected-event registry? | HIGH |
| AIC-36B-02 | Certification Authority boundary — how does NRNA model the external boundary? | HIGH |
| AIC-36B-03 | Constitutional Criteria — is election validity criteria a domain concept in NRNA? | HIGH |
| AIC-36B-04 | Audit Context role expansion — can it gain completeness responsibility without violating passive observer rule? | HIGH |
| AIC-36B-05 | D39 path — Concern A Certification before D39, or full Certification after D39? | HIGH |

All five AICs require 36C (threat modeling) and 36D (trust distribution) evidence before 36E can assess architectural impact.

---

## Section 6 — Constitutional Questions (NCQ-01 through NCQ-04)

These four questions cannot be answered by literature research, domain discovery, or threat modeling.

```
Literature cannot answer them.
ElectionGuard cannot answer them.
DDD cannot answer them.

Only NRNA governance can answer them.
```

They are presented here for ARB constitutional determination. They are not deferred indefinitely — they must be resolved before Round 36E (Architecture Impact Assessment) can complete.

**NCQ-01: Does NRNA's constitutional mandate require Certification?**

```
Or only Auditability + Verifiability?

This is the most fundamental question in 36B.

If the answer is NO (Certification not required):
  AIC-36B-02, AIC-36B-03 become low priority.
  The external authority modeling question does not arise.

If the answer is YES (Certification required):
  Who certifies? When? Under what criteria?
  All five AICs become binding.

The entire shape of 36E depends on this answer.
```

**NCQ-02: Who in NRNA's organizational constitution holds Certification Authority?**

```
Is this an internal role (e.g., a governance board, an ARB function)?
Or an external authority (government election commission, independent auditor)?

This determines whether AIC-36B-02 (external boundary modeling) is necessary
or whether Authority resides within NRNA's own constitutional structure.
```

**NCQ-03: Are NRNA's election validity criteria a domain-owned concept?**

```
Or are they externally defined standards imported into NRNA?

If domain-owned: which aggregate holds them?
  GovernanceState extension? A new ElectionConstitution value object?
  Or are they embedded in configuration?

If externally defined: where do they come from?
  Organizational bylaws? Statutory law? International electoral standards?
```

**NCQ-04: Must Concern A Certification precede Concern B Certification?**

```
Or is NRNA constitutionally required to certify both simultaneously?

This affects the D39 resolution path.

If Concern A can be certified independently:
  Gap A-3 resolution + D39 resolution are independent work streams.
  NRNA can certify governance compliance before tally ownership resolves.

If both must be certified together:
  D39 becomes a blocker for all Certification.
  The entire trustworthiness program must wait for D39.
```

---

## Section 6B — Structural Observation: Where the Risk Concentration Lies

**OBS-36B-CLOSURE-1:**

```
Round 36B produced more governance findings than technical findings.

The major unresolved questions in the trustworthiness program are:

  Authority (who declares?)
  Ownership (who holds each capability?)
  Criteria (what constitutes a valid election?)
  Completeness (Gap A-3 — is the evidence record whole?)

not:

  Encryption
  Hash mechanisms
  Bulletin board design
  Mixnet architecture

This is significant.

It means the primary risk concentration in NRNA's trustworthiness
program is currently in the governance layer, not the cryptographic layer.

Round 36C should therefore begin with governance threats
before cryptographic threats.

Evidence Suppression (Gap A-3) is the correct opening threat class
precisely because it is a governance/ownership failure mode, not a
cryptographic one: the system may fail to emit events, may fail to
route them, or may silently lose them — none of which cryptography
can detect if the events never arrive.
```

---

## Section 7 — What Round 36B Does NOT Contain

Explicitly not in 36B (to prevent scope confusion in 36C, 36D, 36E):

- No threat models (36C is the source)
- No trust distribution analysis (36D is the source)
- No architecture decisions (36E is the source)
- No ADRs (37 is the source)
- No Merkle tree, bulletin board, or ElectionGuard implementation design
- No new bounded contexts or aggregates proposed
- No tally design (D39 blocks this throughout)

---

## Section 8 — 36B Program Discipline Assessment

The governing rule for Round 36 (binding from Round 35):

```
For every literature finding:
"Does this strengthen the discovered model,
or does it attempt to replace the discovered model?"
Only findings that strengthen may advance.
```

36B applied this rule across all five sub-documents. The result:

```
36B strengthened the discovered model by:
  Confirming that Auditability, Verifiability, and Certification
  are separable capabilities.

  Identifying that completeness (Gap A-3) is the binding constraint
  on the entire trustworthiness claim.

  Confirming that Certification Authority is structurally external —
  not a context to be designed, but a constitutional relationship to be modeled.

36B did NOT replace the discovered model. No aggregate boundaries were
changed. No contexts were promoted. The discovered model stands.
```

---

## ARB Decision

```
Round 36B — Auditability Research

APPROVED WITH OBSERVATIONS (Closure Document)

Corrections applied:

  CORRECTION-1: Certification Authority statement softened.
    "Structurally external" was overstated given NCQ-02 is open.
    Now states: "requires structural independence from operational aggregates."
    Whether that independence is external or internal-but-constitutionally-separated
    remains NCQ-02.

  CORRECTION-2: D39 Certification finding demoted from confirmed to
    candidate finding. D39's resolution may produce tally patterns
    (statistical, cryptographic, external) that change the relationship.

  CORRECTION-3: Gap A-3 scope corrected.
    "Prerequisite for everything downstream" → "prerequisite for complete
    auditability and defensible certification."
    Rounds 36C, 36D, and 36E can proceed without solving Gap A-3.
    The gap blocks claims, not research.

Added:
  OBS-36B-CLOSURE-1: Round 36B produced more governance findings than
    technical findings. Primary risk concentration is in governance
    (Authority, Ownership, Criteria, Completeness), not cryptography.
    Round 36C must begin with governance threats.

Frozen outputs (FINAL):
  36B-CFI-01 (Separable constitutional capabilities) — CONFIRMED
  Ownership matrix (Section 3) — FROZEN
  5 AICs for 36E (AIC-36B-01 through AIC-36B-05) — FROZEN
  4 NCQs for ARB constitutional determination (NCQ-01 through NCQ-04) — OPEN

NCQ status:
  NCQ-01 through NCQ-04 require ARB constitutional determination.
  They are not blocked by literature or design constraints.
  They require NRNA governance to answer.
  They must be resolved before Round 36E (Architecture Impact Assessment) completes.

Round 36B: CLOSED
Round 36C (Threat Modeling Research): AUTHORIZED

Opening threat class for Round 36C:
  Evidence Suppression (Gap A-3)

  Gap A-3 is the most serious known risk in the trustworthiness program.
  Every audit, verification, and certification claim ultimately depends
  on whether the evidence record is complete.
  Gap A-3 must be the first threat class in 36C.
```
