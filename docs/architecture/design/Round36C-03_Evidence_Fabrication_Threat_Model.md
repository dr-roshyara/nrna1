# Round 36C-03 — Evidence Fabrication Threat Model

**Date:** 2026-06-13

**Phase:** Round 36C — Threat Modeling Research

**Sub-document:** 36C-03 (Threat Class 2: Evidence Fabrication)

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 36B Closure — APPROVED
- Round 36C-01 Threat Modeling Baseline — APPROVED
- Round 36C-02 Evidence Suppression — APPROVED WITH MAJOR OBSERVATIONS
- Carried forward: TC1-DI-01, TC1-NCQ-01, TC1-NCQ-02, OBS-36C-02-6

**Governing Question:**

```
36C-02 asked: Can evidence disappear?

36C-03 asks: Can false evidence become trusted evidence?

The focus shifts from evidence absence
to evidence existence that should not be trusted.
```

**Preventive Governance Rule (binding):**

```
Do NOT propose mechanisms.

Do NOT introduce:
  Digital Signatures, Threshold Cryptography,
  Merkle Trees, Hash Chains, Zero-Knowledge Proofs,
  Blockchain, ElectionGuard, Helios

These are possible solutions.
Round 36C remains threat discovery.
Solutions belong to Round 36E.
```

**Architectural Discipline Warning (binding — from ARB authorization):**

```
This document repeatedly discovers:
  provenance / completeness / authority / criteria / ownership

These are governance concepts.

Do NOT allow findings to become bounded contexts.

  Provenance ≠ Provenance Context
  Ownership ≠ Ownership Context
  Threat ≠ Bounded Context
  Capability ≠ Bounded Context

The governance rule from 36B still applies.
Bounded contexts are 36E and 37 territory.
```

**Mandatory Distinction (binding):**

```
Evidence Suppression (TC-1): "Did evidence disappear?"
Evidence Fabrication (TC-2): "Can false evidence become trusted evidence?"

The constitutional consequences may overlap.
The threat mechanisms are different.
Do not merge them.
```

---

## Section 1 — The Two States Under Investigation

Round 36C-02 established four evidence states. This document investigates States 3 and 4.

**State 3 — Altered:**

```
The act occurred.
Evidence was produced.
The evidence was modified after production.

Example:
  A vote was cast. VoteRecorded was emitted.
  The candidate fields were modified
  before or after the Audit context received the event.

  The event exists. The event is wrong.
```

**State 4 — Fabricated:**

```
The act never occurred.
Evidence exists claiming it occurred.

Example:
  No vote was cast. VoteRecorded exists in the audit record.
  The vote count is inflated by a non-existent act.

  The event exists. The underlying act does not.
```

**The diagnostic question:**

```
Can the discovered model distinguish:

  Real Act + Real Evidence
from:
  No Act + Fabricated Evidence

or:
  Real Act + Altered Evidence
from:
  Real Act + Real Evidence

The answer must be based only on discovered structures.
No future mechanisms may be introduced.
```

---

## Section 2 — Constitutional Threat Level

**Why Evidence Fabrication is a constitutional threat first:**

TC1-DI-01 (from 36C-02) established: "Absence of evidence is not evidence of absence."

36C-03 investigates the symmetric principle as a candidate: "Presence of evidence is not evidence of truth."

If both hold — tested below — then:

```
Passive observation alone has not been shown to resolve
completeness ambiguity (TC1-DI-01 / Gap A-3)
or provenance ambiguity (TC2-DI-01 candidate).

This is a constitutional observation, not yet a constitutional conclusion.
NRNA may constitutionally decide to accept these limitations —
that remains an open constitutional question (TC2-NCQ-02).

What can be stated now:
  A system making strong trustworthiness claims
  must eventually address both ambiguities.
  Which claims NRNA intends to make determines
  which ambiguities must be resolved.
```

---

## Section 3 — TC2-Q1: Alteration Vulnerability Analysis (State 3)

Alteration is analyzed across three windows:
- **Pre-emission**: modification inside the producing aggregate before dispatch
- **In-transit**: modification between emission and observation
- **Post-observation**: modification of the Audit context's persisted record after receipt

| Artifact | Who produces | Who observes | Who could alter | Constitutional consequence if altered |
|----------|-------------|-------------|-----------------|--------------------------------------|
| VoteRecorded | Vote aggregate | Audit context | Vote aggregate actor (pre); routing (transit); Audit persistence actor (post) | Concern B: tally is wrong; Recorded-as-Cast claim is false; receipt may contradict audit record |
| GovernanceTransitionCompleted | GovernanceState | Audit context | GovernanceState actor (pre); routing (transit); Audit persistence (post) | Concern A: governance timeline falsified; Concern A Auditability based on false record; Concern A Certification: evidence present but wrong |
| GovernanceDecisionSnapshot | GovernanceState | ReplaySession (ADGR-1 blocked) | GovernanceState actor (pre); ReplaySession persistence (post — blocked) | Replay produces false governance history; appears to succeed but replays wrong configuration |
| GovernanceSuspended / GovernanceResumed | GovernanceState | Audit context | Same as GovernanceTransitionCompleted | Voting window boundaries falsified; governance timeline shows wrong suspension/resumption |
| VerificationGranted | Verification aggregate | Audit context | Verification actor (pre); routing (transit); Audit persistence (post) | Trust status record shows wrong eligibility decision |
| VerificationRevoked | Verification aggregate | Audit context | Same | Revocation record is wrong; ineligible voter may appear eligible or eligible voter may appear revoked |
| ElectionAuditLog (aggregated) | Audit context | Certifier / Auditor | Audit persistence actor (post-aggregation) | Candidate most dangerous alteration point — see below |
| Receipt hash | Vote aggregate | Voter; Verification Representation | Vote aggregate (pre); delivery layer (transit) | Voter holds wrong receipt; discrepancy between voter's receipt and audit record |

**Candidate most dangerous alteration point (OBS-36C-03-C):**

```
Post-observation alteration of the ElectionAuditLog (aggregated)
is a candidate for most dangerous alteration point.

Reason:
  The ElectionAuditLog is the final consolidated record.
  It is what the Certifier inspects.
  Alteration here propagates false evidence into all
  three constitutional capabilities simultaneously.

  This ranking may change as 36C-04 through 36C-06 investigate
  GovernanceDecisionSnapshot, Certification Criteria, and
  Certification Authority — which may prove even more dangerous.

Status: CANDIDATE most dangerous, not confirmed.
```

**Software defect as State 3:**

```
A software defect that records wrong candidate IDs in VoteRecorded
is constitutionally equivalent to deliberate pre-emission alteration.

The Audit context received the event and recorded it faithfully.
No distinction between defect and malice is possible from the audit record.

A trust model must survive incompetence, not only malice.
Alteration from software defect is in scope.
It produces identical constitutional consequences as deliberate alteration.
```

---

## Section 4 — TC2-Q2: Fabrication Vulnerability Analysis (State 4)

**Can evidence appear without the underlying act?**

| Artifact | Fabrication scenario | Who benefits | Who notices | Constitutional consequence |
|----------|---------------------|-------------|-------------|---------------------------|
| VoteRecorded (for non-voter) | Emit VoteRecorded without CastVote being processed | Actor inflating vote count | Not demonstrated detectable by currently discovered structures | Tally inflated; fictional votes appear legitimate |
| VoteRecorded (wrong candidates) | Emit VoteRecorded with different candidates than cast | Actor changing specific vote outcome | Voter may detect if receipt mechanism exists and they check | Individual vote altered; voter may or may not detect |
| GovernanceTransitionCompleted | Emit for a transition GovernanceState never performed | Actor falsifying governance history | Not demonstrated detectable by currently discovered structures | Governance history fabricated; Concern A Auditability based on false record |
| GovernanceDecisionSnapshot | Fabricate snapshot for configuration that was never set | Actor wanting replay to produce different result | Not demonstrated detectable | Replay produces false history; governance replay constitutionally void |
| VerificationGranted (ineligible voter) | Emit VerificationGranted for never-enrolled voter | Actor creating fake eligible voter (D43 relevance) | Not demonstrated detectable | Ineligible voter appears eligible in audit |
| VerificationRevoked (false revocation) | Emit VerificationRevoked for eligible voter | Actor disenfranchising specific voter | Not demonstrated detectable | Eligible voter appears revoked; may be denied participation |
| ElectionAuditLog (fabricated entries) | Insert events into log never produced by any aggregate | Actor with write access to Audit persistence | Not demonstrated detectable without independent records | Complete audit fabricated; all constitutional capabilities collapse |

**Actor benefits — cross-artifact pattern:**

```
Fabrication can serve two distinct strategic goals:

  Goal A: Outcome manipulation
    Change specific vote counts or governance history
    to produce a desired election result.

  Goal B: Delegitimization
    Introduce fabricated evidence that is later discovered.
    The election's legitimacy is destroyed
    even if the actual outcome was correct.

  Not every fabrication is outcome-targeted.
  A fabricated log that is detected later may be used
  to retroactively delegitimize a valid election.
  This is a constitutional threat independent of outcome manipulation.
```

---

## Section 5 — TC2-Q3: Can the Current Model Distinguish Real from Fabricated?

```
Real scenario:
  CastVote command processed by Vote aggregate.
  Vote aggregate emits VoteRecorded.
  Audit context receives VoteRecorded.
  Audit context records event.

Fabrication scenario:
  No CastVote command.
  Actor emits VoteRecorded directly into the event channel.
  Audit context receives VoteRecorded.
  Audit context records event.

From the Audit context's perspective:
  Both scenarios produce identical entries in the audit log.

The Audit context cannot determine:
  Was this VoteRecorded produced by the Vote aggregate
  in response to a legitimate CastVote command?
  Or was this VoteRecorded injected by an actor?

These two scenarios are NOT DISTINGUISHABLE
by any currently discovered structure.
```

**Why this is different from the suppression ambiguity:**

```
Suppression ambiguity (TC1-DI-01):
  Absence could mean: never occurred, or suppressed.
  The audit record is silent.

Fabrication ambiguity (TC2-DI-01 candidate):
  Presence could mean: real event, or fabricated event.
  The audit record is active.

Both ambiguities reflect the same structural property:
  The Audit context receives events passively.
  It cannot validate provenance — only record what it receives.

Passive observation alone has not been shown to resolve
either ambiguity with constitutional confidence.
```

**Formal bounding statement:**

```
For any event E in the audit record,
the current model cannot determine:
  source(E) ∈ {legitimate-aggregate-production, external-injection}

This is the fabrication detection gap.

Gap A-3 (completeness):
  The record may be smaller than legitimate reality.

Fabrication detection gap:
  The record may contain events that do not correspond to legitimate acts,
  or may contain wrong versions of real events.

Together they bound the audit record from both directions.
```

---

## Section 6 — TC2-Q4: Who Would Notice Fabrication?

**Audit context:**

```
Detection capability: NOT DEMONSTRATED.

The Audit context receives events.
It has no provenance checking.
It has no independent reference for validation.

A fabricated event and a legitimate event are indistinguishable.
```

**Voter:**

```
Detection capability: PARTIAL (limited to own VoteRecorded, requires receipt).

If a voter's vote is changed (State 3) or fabricated with wrong candidates (State 4),
the voter's receipt should disagree with the audit record.

Limitations:
  Receipt mechanism is a research candidate, not a confirmed design.
  Not all voters check receipts.
  Voter detection is per-voter — not election-level.

If VoteRecorded events are fabricated for non-existent voters (State 4):
  No voter will detect them. These voters do not exist.
  This fabrication mode is entirely outside voter detection capability.
```

**GovernanceState:**

```
Detection capability: NOT DEMONSTRATED.

GovernanceState produces its own events.
It does not observe the Audit context.
GovernanceState and the Audit context are constitutionally separate.
GovernanceState cannot audit its own audit trail.
```

**External Observer:**

```
Detection capability: CONDITIONAL.

An external observer with an INDEPENDENT copy of the event stream
could compare their copy against the audit log.

However:
  No independent copy mechanism exists in the discovered model.
  An external observer without an independent reference
  cannot distinguish legitimate events from fabricated ones.
```

**Certifier:**

```
Detection capability: NOT DEMONSTRATED without independent reference.

The Certifier receives or inspects the ElectionAuditLog.
Without an independent source to compare against,
the Certifier cannot distinguish fabricated from legitimate events.

This is constitutionally significant:
  Certification against a fabricated record is constitutionally void.
  But the Certifier cannot detect this from the record alone.

A Certifier who certifies a fabricated record may be
genuinely unable to detect the fabrication — not corrupt.

This is the intersection with TC-4 (Certification Abuse).
```

**Finding TC2-Q4-F1:**

```
No currently discovered structure has been demonstrated
to detect evidence fabrication without an independent reference.

Voter detection is partial for own-vote State 3/4 only.
No structure provides election-level fabrication detection.

This mirrors TF-36C-02-01 (suppression detection):
  Both attacks exploit the passive reception of the Audit context.
  Suppression removes events. Fabrication adds or corrupts events.
  Passive observation alone does not detect either.
```

---

## Section 7 — TC2-Q5: Constitutional Claims Affected

| Constitutional Capability | Failure from State 3 (Altered) | Failure from State 4 (Fabricated) |
|--------------------------|-------------------------------|----------------------------------|
| **Auditability** | Evidence is inspectable but inspecting false records. Formally auditable, constitutionally void. | Same — record contains fabricated events. Formally auditable, constitutionally void. |
| **Verifiability** | Claims pass formal verification against altered record. "The tally is correct" = true against altered tally, false against legitimate acts. | Claims pass formal verification against fabricated record. The tally is "correct" according to a fabricated record. |
| **Certification** | Certification against altered evidence is constitutionally void. Authority was present; criteria were formally satisfied; evidence was false. | Certifier cannot detect fabrication. Certification proceeds in good faith against fraudulent record. Constitutionally void; not necessarily corrupt. |

**The most constitutionally dangerous property of fabrication:**

```
Fabrication may be more constitutionally dangerous than suppression
in one specific respect:

  Suppression creates absence.
    An auditor may notice unusual absence and investigate.
    Absence can trigger suspicion.

  Fabrication creates presence.
    A fabricated record appears complete.
    A complete-appearing record reduces auditor suspicion.
    Fabrication may pass scrutiny more easily than suppression.

A system with fabricated evidence may be:
  Formally auditable (inspectable record)
  Formally verifiable (mathematically consistent record)
  Formally certifiable (authority declares criteria met)

And constitutionally fraudulent at all three levels.

This is the definition of an election that appears trustworthy
without being trustworthy.

It is the exact scenario the governing question addresses.
```

---

## Section 8 — Actor Analysis

| Actor | State 3 (Alteration) | State 4 (Fabrication) | Benefit |
|-------|----------------------|----------------------|---------|
| Election operator | HIGH — controls aggregate triggering; can alter events pre-emission | HIGH — can emit events without legitimate commands if controls event bus | Outcome manipulation |
| Governance authority | CRITICAL — controls GovernanceState; can alter governance events pre-emission | CRITICAL — can emit GovernanceTransitionCompleted for transitions that never occurred | Governance history manipulation |
| System administrator | HIGH — write access to Audit persistence; alters post-observation | MEDIUM — may control event routing; inject events in-transit | Cover tracks or discredit election |
| Malicious insider | Depends on access level | Depends on access level | Outcome-targeted or delegitimization |
| Certification authority | MEDIUM — could alter evidence package before/during review | LOW | Certify non-compliant outcome |
| External attacker | LOW (requires infrastructure access) | LOW (requires event bus access) | Delegitimization |
| **Software defect** | **CRITICAL — produces wrong event content; indistinguishable from deliberate alteration** | **MEDIUM — duplicate processing / retry logic / race conditions produce fabricated events** | **None (non-malicious); identical constitutional consequences** |

**Software defect as fabrication source:**

```
Duplicate processing risk:
  A CastVote command processed twice due to at-least-once delivery
  produces two VoteRecorded events for one act.

  From the Audit context's perspective:
    Two legitimate-looking VoteRecorded events.
    One is real. One is a fabrication — unintentional but constitutionally identical.

Race condition risk:
  GovernanceState emits GovernanceTransitionCompleted for a
  transition initiated but rolled back.
  The audit record contains a false transition.
  No actor intended fraud. Constitutional effect is identical.

Therefore:
  Idempotency and at-most-once delivery are not merely engineering concerns.
  They are constitutional trustworthiness requirements.
```

---

## Section 9 — TC2-DI-01: Candidate Domain Insight

**TC2-DI-01 — CANDIDATE DOMAIN INSIGHT: Evidence Presence Ambiguity**

```
Presence of evidence is not evidence of truth.

In the context of a trustworthy election:
  The presence of VoteRecorded in the audit record
  may mean a legitimate vote was cast and recorded.
  It may equally mean an event was fabricated, altered,
  or produced by a software defect.

  These are constitutionally different facts
  with identical audit signatures.

  A system that cannot distinguish them cannot produce
  a constitutionally defensible claim about the authenticity
  of its audit record.
```

**Paired with TC1-DI-01:**

```
TC1-DI-01:  Absence of evidence is not evidence of absence.
TC2-DI-01:  Presence of evidence is not evidence of truth.

Together these form a candidate pair of trustworthiness principles:

  The audit record can be constitutionally ambiguous
  in both directions simultaneously:
    It may be smaller than legitimate reality (TC1 / suppression).
    It may contain false representations of reality (TC2 / fabrication).

  Both ambiguities flow from the same structural property:
    The Audit context is a passive receiver.
    It records what it receives.
    It cannot validate completeness (TC1).
    It cannot validate provenance (TC2).

  Passive observation alone has not been shown to resolve
  either ambiguity with constitutional confidence.

This is the most important combined finding from TC-1 and TC-2.
```

**Promotion criteria (OBS-36C-03-A):**

```
TC2-DI-01 is a CANDIDATE DOMAIN INSIGHT.

Promotion to confirmed insight requires corroboration from
additional threat classes and/or trust-distribution research.

Corroborating sources may include (not limited to):
  36C-04 (Governance Manipulation)
  36C-05 (Certification Abuse)
  36C-06 (Trust Concentration)
  36D (Trust Distribution)
  36A/36B findings (if directly applicable)

Promotion is not bound exclusively to 36C completion.
```

---

## Section 10 — OBS-36C-03-D: Provenance as an Emerging Constitutional Concern

```
OBS-36C-03-D

Round 36C-03 repeatedly surfaces a common thread:

  Who is responsible for event provenance?

This is not a technical question about hashing or signing.

It is a constitutional question:
  If CastVote causes VoteRecorded,
  who is constitutionally responsible for ensuring
  this binding is legitimate?

  Who could testify that VoteRecorded corresponds
  to an actual CastVote?

  Who bears responsibility if VoteRecorded exists
  without a corresponding CastVote?

Provenance responsibility is emerging as a first-class governance question —
paralleling:

  Gap A-3 (Expected Evidence Set):
    Who defines what evidence should exist?
    Who is accountable for completeness?

  D43 (Authority Ownership):
    Who holds enrollment authority?
    Who is accountable for eligibility decisions?

  TC2-NCQ-01 (Event Provenance):
    Who holds provenance responsibility?
    Who is accountable when events cannot be traced to legitimate acts?

These are three different ownership gaps
exposing the same underlying constitutional pattern:

  Responsibility must be assigned
  before accountability can exist.
  Accountability must exist
  before trustworthiness can be claimed.

Carry provenance as a constitutional governance concern
into 36C-04 through 36D.
```

---

## Section 11 — Combined Program-Level Finding

**TF-36C-COMB-01 (Program-level — applies across 36C-04 through 36E):**

```
Evidence Suppression (TC-1) and Evidence Fabrication (TC-2)
create a symmetric threat pair against the Audit context.

  TC-1 attacks from below: the record may contain fewer events
    than legitimate reality (completeness gap / Gap A-3).

  TC-2 attacks from above: the record may contain more events,
    or wrong events, than legitimate reality (provenance gap).

  Neither direction is demonstrated detectable by currently discovered structures.

  The Audit context is the shared target of both threat classes.
  It is constitutionally exposed from both directions simultaneously.

  This symmetric exposure means:
    A complete audit record (TC-1 defended) may still contain false events (TC-2 undefended).
    An authentic audit record (TC-2 defended) may still be incomplete (TC-1 undefended).
    Defending against one threat class does not defend against the other.

Program-level implication:
  This finding will influence 36C-04, 36C-05, 36C-06, 36D, and 36E.

  It is not a TC-2 finding only.
  It is a structural finding about the Audit context's constitutional position
  within the trustworthiness program.

  Any governance manipulation (TC-3), certification abuse (TC-4),
  or trust concentration (TC-5) that exploits either the completeness gap
  or the provenance gap is ultimately attacking the same structural vulnerability.
```

---

## Section 12 — New Constitutional Questions

**TC2-NCQ-01: Who is responsible for event provenance?**

```
Is there an authority in NRNA that binds each domain event
to the act that caused it?

Currently: no discovered aggregate holds this responsibility.

The Vote aggregate produces VoteRecorded.
The Vote aggregate's production cannot testify to its own legitimacy.
That would be circular: the actor who fabricates could also certify authenticity.

Provenance responsibility requires an authority external to the producing aggregate.
Who this authority is, and whether it belongs to an existing or new structure,
is a constitutional question.

TC2-NCQ-01 joins NCQ-01 through NCQ-04 (from 36B) and TC1-NCQ-01, TC1-NCQ-02 (from 36C-02)
as constitutional questions requiring ARB determination before Round 36E can complete.
```

**TC2-NCQ-02: Is passive reception of the Audit context constitutionally sufficient?**

```
Given that passive reception alone has not been shown to resolve
completeness or provenance ambiguity:

Is passive reception sufficient for NRNA's intended trustworthiness claims?

This is not a design question. It is a constitutional question.

If YES:
  NRNA constitutionally accepts that its trustworthiness claims are bounded
  by passive observation — formally auditable but not provably authentic or complete.

If NO:
  Additional constitutional mechanisms are required.
  What those mechanisms are belongs to 36E and 37.

TC2-NCQ-02 requires ARB constitutional determination.
```

---

## Section 13 — Threat Findings Summary

| Finding | Type | Priority |
|---------|------|----------|
| TF-36C-03-01: Audit context cannot distinguish real from fabricated events | Constitutional | CRITICAL |
| TF-36C-03-02: Fabricated VoteRecorded events inflate tally — not demonstrated detectable | Constitutional | CRITICAL |
| TF-36C-03-03: Fabricated GovernanceTransitionCompleted falsifies governance history; Concern A Auditability based on false record | Constitutional | CRITICAL |
| TF-36C-03-04: Post-observation alteration of ElectionAuditLog falsifies complete record — candidate most dangerous alteration point | Constitutional | CRITICAL |
| TF-36C-03-05: Pre-emission alteration not demonstrated detectable by currently discovered structures | Constitutional | HIGH |
| TF-36C-03-06: Software defect producing wrong event content is constitutionally equivalent to deliberate alteration | Constitutional | HIGH |
| TF-36C-03-07: Software defect duplicate-processing creates fabricated events — idempotency is a constitutional trustworthiness requirement | Constitutional | HIGH |
| TF-36C-03-08: GovernanceDecisionSnapshot fabrication enables replay forgery — replay succeeds against false governance history | Architectural | HIGH |
| TF-36C-03-09: Voter receipt detection is partial mitigation for own-vote only; does not address non-voter fabrication | Architectural | MEDIUM |
| TF-36C-03-10: TC2-NCQ-01 — event provenance responsibility constitutionally undefined | Constitutional | CRITICAL |
| TF-36C-03-11: TC2-NCQ-02 — passive reception sufficiency requires ARB constitutional determination | Constitutional | CRITICAL |
| **TF-36C-COMB-01: TC-1 and TC-2 form a symmetric threat pair; Audit context exposed from both directions — PROGRAM-LEVEL FINDING** | Constitutional | CRITICAL |

**Candidate Domain Insight:** TC2-DI-01 (Presence of evidence is not evidence of truth)

**Architectural Impact Candidates (for 36E):**

```
AIC-36C-03-01: Event provenance mechanism — how is each domain event
  bound to the act that caused it? Who holds this responsibility?
  (TC2-NCQ-01 input for 36E)

AIC-36C-03-02: Audit context constitutional scope — passive reception
  or active provenance validation? (TC2-NCQ-02 input for 36E)

AIC-36C-03-03: Idempotency and at-most-once delivery as constitutional
  requirements for all domain event-producing aggregates.
  (Not a design decision — a candidate impact for 36E assessment)
```

---

## ARB Decision

```
Round 36C-03 — Evidence Fabrication Threat Model

APPROVED WITH OBSERVATIONS

Research Discipline:        HIGH
Threat Modeling Quality:    HIGH
Governance Discipline:      VERY HIGH
Architecture Neutrality:    GOOD
Confidence:                 HIGH

Observations incorporated:

  OBS-36C-03-A: TC2-DI-01 promotion criteria broadened.
    Promotion requires corroboration from additional threat classes
    and/or trust-distribution research — not bound to 36C only.

  OBS-36C-03-B: "Passive observation is insufficient" replaced throughout.
    Now states: "Passive observation alone has not been shown to resolve
    completeness or provenance ambiguity." Constitutional sufficiency
    remains an open question (TC2-NCQ-02) for ARB determination.

  OBS-36C-03-C: "Most dangerous alteration point" downgraded.
    ElectionAuditLog alteration is CANDIDATE most dangerous —
    not confirmed. GovernanceDecisionSnapshot, Certification Criteria,
    Certification Authority may prove more dangerous in 36C-04 through 36C-06.

  OBS-36C-03-D: Provenance as emerging constitutional concern.
    Provenance responsibility parallels Gap A-3 (completeness)
    and D43 (authority ownership). Three different ownership gaps,
    one constitutional pattern: responsibility must precede accountability.

  OBS-36C-03-E: Software defect section preserved unchanged.
    "A trust model must survive incompetence, not only malice."

  OBS-36C-03-F: TF-36C-COMB-01 elevated to PROGRAM-LEVEL FINDING.
    Symmetric threat pair (TC-1 + TC-2) against the Audit context
    influences 36C-04 through 36E.

  OBS-36C-03-G: Evidence Authenticity and Evidence Completeness
    are TWO INDEPENDENT constitutional dimensions.

    Dimension 1 — Completeness:
      Did all expected evidence appear?
      (Gap A-3, TC1-NCQ-01, TC1-NCQ-02)

    Dimension 2 — Authenticity:
      Did observed evidence originate from legitimate acts?
      (TC2 provenance problem, TC2-NCQ-01)

    These are independent dimensions:
      100% complete + 0% authentic = constitutionally possible.
      100% authentic + 50% complete = constitutionally possible.

    Governance Manipulation (TC-3) may attack either dimension independently.
    36C-04 must preserve the distinction and not accidentally merge them.
    Carry explicitly through 36C-04 into 36D and 36E.

Central findings:
  TF-36C-03-01: Audit context cannot distinguish real from fabricated.
  TF-36C-COMB-01: TC-1 and TC-2 form symmetric threat pair (program-level).
  TC2-DI-01: "Presence of evidence is not evidence of truth" (CANDIDATE INSIGHT).
  TC2-NCQ-01 and TC2-NCQ-02: new constitutional questions for ARB.

Governing Rule Compliance:
  No digital signatures, Merkle trees, ZK proofs, or E2E-V systems introduced.
  All findings are threat findings, not design decisions.
  Architecture deferred to 36E.

Round 36C-03: APPROVED

Governing Instructions for Round 36C-04 (ARB binding):

  OBS-36C-03-G carry-forward (binding):
  Evidence Authenticity ≠ Evidence Completeness.
  Both dimensions must be tracked independently.
  TC-3 (Governance Manipulation) may attack either.
  Do NOT merge them.

  Focus on:
    Authority Manipulation
    Criteria Manipulation
    Ownership Ambiguity
    D43 Enrollment Ownership Gap
    Evidence Governance Failure (OBS-36C-02-6)
    Constitutional Rule Manipulation

  Before discussing any technical attack.
  Governance threats first. Cryptographic threats later.

  The dominant trustworthiness risks are governance risks,
  not cryptographic risks.

Round 36C-04 (Governance Manipulation): AUTHORIZED
```
