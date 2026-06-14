# Round 36C-02 — Evidence Suppression Threat Model

**Date:** 2026-06-13

**Phase:** Round 36C — Threat Modeling Research

**Sub-document:** 36C-02 (Threat Class 1: Evidence Suppression)

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 36C-01 (Threat Modeling Baseline) — APPROVED
- Governing question: "What must happen for a dishonest election to appear trustworthy?"
- Governing lens (binding from 36C authorization):
  Evidence Suppression = CONSTITUTIONAL THREAT first, ARCHITECTURAL THREAT second, TECHNICAL THREAT last

**OBS-36C-02-0 (Preventive, binding from ARB authorization):**

```
Do NOT assume that a completeness mechanism exists.

The purpose of 36C-02 is to determine:
  Whether completeness is constitutionally required.
  Who would need it.
  What threat it mitigates.

Mechanisms belong to 36E.

Do NOT introduce:
  Merkle Trees
  Hash Chains
  Bulletin Boards
  Event Ledgers
  Blockchain

Those are solutions.
36C is still determining the threat.
```

---

## Section 1 — The Four Evidence States

Before investigating suppression, the analysis must distinguish four states that are often conflated in election architectures. The clarity of these distinctions determines whether the threat model is rigorous.

```
State 1: Never Existed
  The evidence was never produced because the act never occurred.
  VoteRecorded is absent because no vote was cast.
  GovernanceTransitionCompleted is absent because no transition occurred.

State 2: Disappeared
  The act occurred. Evidence was produced or should have been produced.
  But the evidence was suppressed before it could be observed.
  VoteRecorded was either not emitted or was emitted and lost in transit.

State 3: Altered
  The act occurred. Evidence was produced and observed.
  But the evidence was modified after observation.
  (This is TC-2 Evidence Fabrication — not this document's subject.)

State 4: Fabricated
  The act did not occur. Evidence was produced saying it did.
  (This is also TC-2 Evidence Fabrication — not this document's subject.)
```

**The diagnostic question for 36C-02:**

```
Can the discovered model distinguish State 1 from State 2?

If NOT:
  The absence of evidence is constitutionally ambiguous.
  "No vote was recorded" is indistinguishable from "vote was suppressed."
  This ambiguity is the constitutional core of Gap A-3.

If YES (with some mechanism):
  The question becomes: how is the distinction made?
  Who holds that mechanism?
  Can it be trusted?

The current discovery suggests: State 1 and State 2 are NOT distinguishable
in the current model.
```

**TC1-DI-01 — DOMAIN INSIGHT CANDIDATE: Evidence Absence Ambiguity**

```
Absence of evidence is not evidence of absence.

In the context of a trustworthy election:
  The absence of a VoteRecorded event may mean a vote was not cast.
  It may equally mean a vote was cast but suppressed.

  These are constitutionally different facts with identical audit signatures.

  A system that cannot distinguish them cannot produce
  a constitutionally defensible claim about the completeness
  of its audit record.

This is a trustworthiness principle, not only a threat finding.

It implies that any system making completeness claims must hold
an independent reference for the expected set of events —
a reference that exists independently of the audit record itself.

TC1-DI-01 is proposed as a CANDIDATE DOMAIN INSIGHT.
Promotion to confirmed insight requires cross-round evidence
from 36C-03 (Fabrication) and 36D (Trust Distribution).
```

---

## Section 2 — Constitutional Threat Level

**Why Evidence Suppression is a constitutional threat first:**

Constitutional capabilities from 36B-CFI-01 (Auditability / Verifiability / Certification) all depend on evidence. Evidence suppression does not attack a mechanism — it attacks the precondition of all three constitutional capabilities simultaneously.

```
If evidence is suppressed:

  Auditability fails:
    Evidence cannot be inspected because it no longer exists.
    P-1 (Complete record) is violated.
    The audit produces a formally valid result against an incomplete record.

  Verifiability fails:
    The claim "outcome X is correct" cannot be verified against missing evidence.
    A tally that excludes suppressed votes is mathematically verifiable
    but constitutionally false.

  Certification fails:
    Certification requires auditable evidence as precondition.
    Certification against a suppressed record is constitutionally indefensible —
    confirmed in 36B-03 OBS-36B-04-3 and 36B Closure.
```

**Constitutional threat hierarchy:**

```
Tier 1 (highest):
  Suppression of governance evidence.
  This attacks Concern A Auditability — the foundation of governance certification.
  If governance configuration history is incomplete, no governance claim can be certified.

Tier 2:
  Suppression of vote evidence.
  This attacks Concern B Verifiability (tally cannot be verified against
  a record that excludes some votes). Blocked also by D39.

Tier 3:
  Suppression of verification/eligibility evidence.
  This attacks the audit trail for enrollment and trust status decisions.
  D43 (enrollment authority unresolved) amplifies this risk.
```

---

## Section 3 — TC1-Q1: Who Benefits from Evidence Suppression?

**Actor analysis:**

The governing question requires asking who *benefits* — not just who *can* suppress. Benefit analysis exposes motive, which determines likelihood and constitutional risk.

| Actor | Suppression Capability | Benefit | Threat Level |
|-------|----------------------|---------|--------------|
| Election operator | Controls domain aggregate triggering — can prevent event emission | Desired outcome while maintaining appearance of compliance | HIGH |
| System administrator | Controls infrastructure — can intercept events between emission and observation | Deniability: "technical failure" | HIGH |
| Malicious insider | Depends on access level | Selective outcome manipulation | HIGH |
| Governance authority | Can prevent GovernanceDecisionSnapshot from being persisted or routed | Conceal governance decisions | CRITICAL — directly attacks Concern A |
| Certification authority | Can "lose" evidence before or during certification review | Certify without reviewing inconvenient evidence | HIGH (NCQ-02 relevance) |
| External attacker | If event infrastructure is compromised | Widespread delegitimization or controlled outcome | MEDIUM (requires infrastructure access) |
| **Software defect** | **Can prevent event emission at aggregate level** | **None (non-malicious) — but result is identical** | **CRITICAL — incompetence produces same constitutional effect as malice** |

**The software defect finding:**

```
A trust model must survive incompetence, not only malice.

If VoteRecorded is not emitted due to a bug in the Vote aggregate,
the constitutional consequence is identical to deliberate suppression:

  The audit record is incomplete.
  The tally cannot be verified against the record.
  Certification against the record is constitutionally indefensible.

The Audit context cannot distinguish:
  "VoteRecorded was suppressed by an actor"
from:
  "VoteRecorded was not emitted due to a software defect"

This means the threat model must treat software defects as suppression actors.
Robustness and trustworthiness are not separate concerns.
```

---

## Section 4 — TC1-Q2: What Evidence Can Currently Disappear?

**Suppression Matrix:**

For each discovered evidence artifact, the matrix maps: who produces it, who observes it, who consumes it, and what constitutional capability fails if it is absent.

| Evidence Artifact | Produced by | Observed by | Consumed by | Constitutional criterion supported | Constitutional consequence if absent |
|------------------|------------|-------------|-------------|-----------------------------------|--------------------------------------|
| VoteRecorded | Vote aggregate | Audit context | Audit log; (future) tally | Concern B: Tallied-as-Recorded; Recorded-as-Cast | Concern B Verifiability impossible; Concern B Certification impossible; Auditability partial |
| GovernanceTransitionCompleted | GovernanceState | Audit context | Audit log; GovernanceReplayService | Concern A: Governance process was followed; configuration freeze | Concern A Auditability partial; Concern A Certification impossible; Replay broken |
| GovernanceDecisionSnapshot | GovernanceState | ReplaySession (candidate) | Governance replay | Concern A: Governance history is complete and replayable | Governance history undemonstratable; Replay forgery undetectable |
| GovernanceSuspended | GovernanceState | Audit context | Audit log | Concern A: Voting window was constitutionally bounded | Suspension event invisible; audit record has unexplained voting gap |
| GovernanceResumed | GovernanceState | Audit context | Audit log | Concern A: Voting window was constitutionally bounded | Resumption event invisible; governance timeline has unexplained gap |
| VerificationGranted | Verification aggregate | Audit context | Audit log | Concern A + C: Eligible voters were properly enrolled and trusted | Eligibility audit trail broken; voter's trust status history incomplete |
| VerificationRevoked | Verification aggregate | Audit context | Audit log | Concern A + C: Revocations were properly recorded and honoured | Revocation invisible; voter may appear eligible in audit when revoked |
| ElectionAuditLog (aggregated) | Audit context | Certifier / Auditor | Certification review | All Concerns: Complete evidence record available for certification | Certifier works against incomplete evidence; Certification constitutionally unsound |
| Receipt hash | Vote aggregate | Voter / Verification Representation | Individual verifiability | Recorded-as-Cast: Individual voter can confirm vote was recorded as cast | Recorded-as-Cast cannot be individually verified |

**Matrix findings:**

```
Every evidence artifact is owned by exactly one producer aggregate.

The Audit context observes every artifact as a passive receiver.

No artifact has a secondary observer.

This means:

  If the producer does not emit,
    the Audit context has no evidence of the act.

  If the event bus or routing fails,
    the Audit context has no evidence of the act.

  In both cases, the Audit context sees only absence.
  Absence is constitutionally ambiguous (State 1 vs. State 2).

Suppression at the producer level:
  Not demonstrated detectable by any currently discovered structure.

Suppression in transit:
  Not demonstrated detectable by any currently discovered structure.
```

**Hidden ownership gaps exposed:**

```
GovernanceDecisionSnapshot:
  Produced by GovernanceState.
  Observed by ReplaySession (candidate — ADGR-1 blocker).
  The observation path is currently blocked.

  If ReplaySession never becomes operational:
    GovernanceDecisionSnapshot is produced but never consumed
    for replay purposes.
    Suppression of this artifact has no current detection path.

Receipt hash:
  Produced by Vote aggregate.
  Observed by: no current observer in the audit chain.
  (The voter receives it. The Verification Representation Context
  publishes a surface for it. But no observer aggregates it
  into the audit record.)

  This is a gap in the ownership matrix that the suppression analysis
  makes visible. The receipt hash is produced but not audited.
```

---

## Section 5 — TC1-Q3: Who Is Capable of Noticing Suppression?

**This is the most important question in TC-1.**

The standard assumption is "Audit notices." 36B-03 and 36B-05 established that this assumption is incorrect for NRNA's current model.

**Detection capability analysis:**

| Observer | Suppression detection capability | Limitation |
|----------|----------------------------------|-----------|
| Audit context | Detects events it receives. Cannot detect events it did not receive. | Gap A-3: no expected-event registry. Absence ≠ detection. |
| Voter (individual) | Can detect absence of own receipt — "I voted but have no receipt" | Requires receipt mechanism. Not all voters check. Voter detection is not aggregate detection. |
| GovernanceState | Knows what transitions it made, but is not an auditor of its own emissions | Circular: the aggregate that should emit cannot verify that it emitted |
| External auditor | Can detect if they have an independent prior record to compare against | No independent prior record currently exists in the discovered model |
| Certifier | Can notice evidence absence — "I expected N evidence items, I received M" | Requires pre-stated expected evidence manifest. None exists. |
| No one | If suppression occurs at the emission point (before any observer) | This is the most dangerous suppression mode |

**The Audit context's fundamental limitation:**

```
The Audit context is a passive observer.
It sees what it receives.
It has no knowledge of what it should receive.

Formally:
  Audit.observedEvents ⊆ Reality.emittedEvents

The Audit context knows the left side.
No structure currently knows the right side.

The gap between these two sets is Gap A-3.

Detecting suppression requires knowing the right side.
Without it, the Audit context can only report:
  "These events occurred."

It cannot report:
  "All events that should have occurred, occurred."
```

**Finding TC1-Q3-F1:**

```
No currently discovered structure in NRNA has been demonstrated
to detect evidence suppression at the emission point.

Detection at the emission point would require knowledge of
what events should be emitted — an expected-event set.

No aggregate currently holds this knowledge.

This is the deepest structural expression of Gap A-3.

Note: Structures not yet operational (ReplaySession — ADGR-1 blocked,
Verification Representation Context — candidate, D43 resolution) may
provide partial detection paths. This finding is bounded by currently
discovered and operational structures.
```

**Finding TC1-Q3-F2:**

```
Individual voter detection (receipt check) is a partial mitigation for VoteRecorded only.

It does not address:
  GovernanceTransitionCompleted suppression
  GovernanceDecisionSnapshot suppression
  Verification event suppression

And it depends on:
  A receipt mechanism existing (currently a research candidate, not a confirmed design)
  Voters actually checking their receipts
  A mechanism to aggregate voter-level detection into election-level detection

Individual voter detection is necessary but not sufficient.
```

---

## Section 6 — TC1-Q4: Can Suppression Be Distinguished from Non-Existence?

**This is the deepest constitutional question in TC-1 and the heart of Gap A-3.**

```
Consider VoteRecorded absent in the audit record.

Two interpretations:
  Interpretation A: This voter did not cast a vote.
    (State 1: Never Existed)

  Interpretation B: This voter cast a vote; VoteRecorded was suppressed.
    (State 2: Disappeared)

In the current model:
  The Audit context sees: VoteRecorded for this voter is absent.
  The Audit context cannot determine: which interpretation is correct.

Formally:
  Audit.observedEvents contains no VoteRecorded for Voter X.
  This observation is consistent with BOTH interpretations.
  The observation does not favor either interpretation.
  Therefore: absence of VoteRecorded is ambiguous evidence.
```

**Why this is constitutional, not technical:**

```
The ambiguity between State 1 and State 2 is not resolvable by
changing the technical infrastructure while leaving the constitutional
question unanswered.

Even with a perfect Merkle tree, a complete append-only log,
or a cryptographic event ledger:

  If the event was never emitted, the ledger records nothing.
  The ledger is complete — it contains every event that was emitted.
  But it says nothing about events that should have been emitted
  but were not.

The constitutional question is:
  What constitutes the expected set of evidence events?
  Who defines it?
  Who is accountable for its completeness?

  Until those questions are answered:
    No technical mechanism can provide the expected set.
    Therefore no technical mechanism can detect the gap.
```

**The four-state resolution requirement:**

```
For NRNA to make strong trustworthiness claims, the discovered model
must eventually be able to distinguish all four states:

  State 1: No vote was cast.
    Evidence: enrollment record shows voter did not participate.
    Constitutional consequence: correct — no VoteRecorded is appropriate.

  State 2: Vote was cast, VoteRecorded suppressed.
    Evidence: some external reference shows vote was expected but not recorded.
    Constitutional consequence: critical failure — audit record is incomplete.

  State 3: Vote was cast, VoteRecorded emitted and then altered.
    Evidence: tamper-detection in the record shows modification.
    Constitutional consequence: evidence integrity failure.

  State 4: Vote was not cast, VoteRecorded fabricated.
    Evidence: commitment scheme shows no vote act produced this event.
    Constitutional consequence: evidence fabrication — TC-2 domain.

Currently:
  State 1 and State 2 are indistinguishable.
  State 3 is detectable (P-2 infrastructure).
  State 4 is currently undetectable (commitment scheme not yet discovered).

36C-02 establishes: States 1 and 2 are the primary constitutional gap.
36C-03 will establish: States 3 and 4 are the secondary constitutional gap.
```

---

## Section 7 — Ownership Boundaries Affected

Evidence suppression affects specific ownership boundaries in the discovered model:

**Boundary 1: Vote aggregate → Audit context**

```
Attack point: VoteRecorded event production or transit.
Ownership responsibility: Vote aggregate produces; Audit context observes.
Gap: no cross-boundary verification that all VoteRecorded events arrived.
Constitutional consequence: Concern B Verifiability evidence base is incomplete.
```

**Boundary 2: GovernanceState → Audit context**

```
Attack point: GovernanceTransitionCompleted, GovernanceSuspended,
              GovernanceResumed event production or transit.
Ownership responsibility: GovernanceState produces; Audit context observes.
Gap: no cross-boundary verification. GovernanceState cannot confirm Audit received its events.
Constitutional consequence: Concern A Auditability is partial; Concern A Certification impossible.

This is the highest-priority boundary because:
  Concern A Certification is the achievable path before D39 resolves.
  If Concern A Auditability is partial, Concern A Certification is blocked.
  The D39 narrowing finding (candidate, OBS-36B-04-3) is further weakened
  if governance evidence can be suppressed without detection.
```

**Boundary 3: GovernanceState → ReplaySession (candidate)**

```
Attack point: GovernanceDecisionSnapshot routing.
Ownership responsibility: GovernanceState produces; ReplaySession (blocked by ADGR-1) should consume.
Gap: ReplaySession is not operational. Snapshots may be produced but never consumed.
Constitutional consequence: governance replay is unavailable; governance history cannot be demonstrated.
```

**Boundary 4: Vote aggregate → Voter (receipt)**

```
Attack point: receipt hash delivery or generation.
Ownership responsibility: Vote aggregate produces; voter receives.
Gap: receipt is not currently aggregated into the Audit context's record.
Constitutional consequence: individual Recorded-as-Cast verification has no aggregate audit trail.
```

---

## Section 8 — Constitutional Consequence Analysis

**What fails when evidence is suppressed:**

| Suppressed artifact | Auditability | Verifiability | Certification |
|--------------------|-------------|--------------|---------------|
| VoteRecorded (any) | PARTIAL (P-1 violated) | PARTIAL-BLOCKED (Concern B) | BLOCKED (incomplete evidence) |
| GovernanceTransitionCompleted | PARTIAL (P-1 violated) | PARTIAL (Concern A replay broken) | **BLOCKED (Concern A path closed)** |
| GovernanceDecisionSnapshot | PARTIAL | BLOCKED (no replay source) | BLOCKED |
| VerificationGranted/Revoked | PARTIAL | PARTIAL (eligibility trail incomplete) | BLOCKED |
| ElectionAuditLog (via Audit context) | **TOTAL FAILURE** | TOTAL FAILURE | TOTAL FAILURE |

**Candidate most dangerous single suppression scenario (OBS-36C-02-5):**

```
Suppression of GovernanceTransitionCompleted events.

This is a strong candidate for highest impact.
Other artifacts (GovernanceDecisionSnapshot, ElectionAuditLog,
future Certification Evidence, future D39 evidence) have not yet
been fully analyzed. The ranking below may change in 36C-03 through 36C-06.

Why this is a strong candidate:
  1. Governance Auditability is the foundation of Concern A Certification.
  2. Concern A Certification was identified (OBS-36B-04-3) as the potentially
     achievable Certification path before D39 resolves.
  3. If GovernanceTransitionCompleted events can be suppressed without detection,
     Concern A Auditability is partial.
  4. Partial Auditability → Certification against incomplete evidence → constitutionally indefensible.
  5. The D39 narrowing candidate finding collapses.

Single governance event suppression can therefore invalidate
the entire Concern A Certification path.

This is not a technical failure. It is a constitutional structural gap:
the governance audit trail has no completeness guarantee.
```

---

## Section 8B — Structural Observation: Potential Future Threat Class

**OBS-36C-02-6:**

```
Round 36C-02 repeatedly returned to three related questions:

  What evidence should exist?
  Who defines it?
  Who is accountable for its completeness?

These questions are not about suppression.

Suppression presupposes that the expected set is known,
and then asks whether the actual set matches it.

But what if the expected set is never defined?

What if the election has no agreed definition of what evidence should exist?

Then:
  No suppression is detectable — not because detection failed,
  but because no one defined what was expected.
  The attack surface is the definition layer, not the evidence layer.

This may constitute a future threat class:

  Evidence Governance Failure:
    The election has no agreed definition of what evidence should exist.
    Without a definition, completeness cannot be claimed.
    Without completeness, Auditability, Verifiability, and Certification
    all rest on undefined foundations.

This is NOT the same as Evidence Suppression.
Suppression assumes defined evidence. Governance Failure precedes definition.

OBS-36C-02-6 is recorded as a potential future threat class
for consideration in the Round 36C threat class sequence.
Do not create a new threat class now — record the observation.
Governance Manipulation (TC-3) may partially address this,
or it may warrant a dedicated extension.
```

---

## Section 9 — Open Constitutional Questions Surfaced by TC-1

TC1-NCQ-01 (new, surfaced by TC1-Q4):

```
What constitutes the expected set of evidence events for an NRNA election?

Is this set defined by:
  Constitutional mandate (N events must be recorded for an election with M voters)?
  Governance configuration (each GovernanceState transition produces one event)?
  Enrollment records (each enrolled voter is expected to produce a VoteRecorded)?
  Some combination?

Until this is answered, the expected set cannot be constructed.
Without the expected set, Gap A-3 cannot be closed.
```

TC1-NCQ-02 (new, surfaced by TC1-Q3):

```
Who is constitutionally responsible for evidence completeness?

Is this:
  The emitting aggregate (Vote, GovernanceState) — responsible for ensuring emission?
  The observing structure (Audit context) — responsible for verifying receipt?
  An external observer — responsible for comparing independent records?
  A shared responsibility requiring constitutional separation?

This is a governance question, not a design question.
It cannot be answered by choosing an architecture.
```

These join NCQ-01 through NCQ-04 from 36B as constitutional questions requiring ARB determination before 36E.

---

## Section 10 — Threat Findings Summary

| Finding | Type | Priority |
|---------|------|----------|
| TF-36C-02-01: Evidence suppression at emission point is undetectable by any current structure | Constitutional | CRITICAL |
| TF-36C-02-02: Audit context cannot detect absence — it can only record presence | Constitutional | CRITICAL |
| TF-36C-02-03: State 1 (never existed) and State 2 (suppressed) are constitutionally indistinguishable | Constitutional | CRITICAL |
| TF-36C-02-04: Software defects produce identical constitutional consequences as malicious suppression | Constitutional | HIGH |
| TF-36C-02-05: GovernanceTransitionCompleted suppression closes the Concern A Certification path | Architectural | CRITICAL |
| TF-36C-02-06: Receipt hash is produced but not aggregated into the audit record — gap in the ownership matrix | Architectural | HIGH |
| TF-36C-02-07: GovernanceDecisionSnapshot has no operational consumer (ADGR-1 blocker) | Architectural | HIGH |
| TF-36C-02-08: Individual voter receipt detection is partial mitigation only — not aggregate detection | Architectural | MEDIUM |
| TF-36C-02-09: TC1-NCQ-01 — expected evidence set is constitutionally undefined | Constitutional | CRITICAL |
| TF-36C-02-10: TC1-NCQ-02 — evidence completeness responsibility is constitutionally unassigned | Constitutional | CRITICAL |

**Findings for 36E (architectural impact candidates from TC-1):**

```
AIC-36C-02-01: Completeness Mechanism — who holds the expected-event set?
  Carries forward AIC-36B-01 with increased specificity.
  The expected-event set must be defined constitutionally before
  any mechanism can be designed to detect completeness gaps.

AIC-36C-02-02: Emission confirmation — can emitting aggregates confirm delivery?
  Technical question with constitutional implications.
  Belongs to 36E impact assessment.

AIC-36C-02-03: Receipt aggregation — how does the receipt hash enter the audit record?
  Currently: produced, not audited.
  A gap between Vote aggregate and Audit context that suppression exploits.
```

---

## ARB Decision

```
Round 36C-02 — Evidence Suppression Threat Model

APPROVED WITH MAJOR OBSERVATIONS

Constitutional Threat Level: CRITICAL
Architectural Threat Level: HIGH
Technical Threat Level: DEFERRED TO 36E

Observations applied:

  OBS-36C-02-1: "Undetectable" replaced throughout with "not demonstrated
    detectable by currently discovered structures." Finding scope is bounded
    by currently operational structures. Candidate structures (ReplaySession,
    D43 resolution, future D39 work) may provide partial detection paths.

  OBS-36C-02-2: TC1-DI-01 (Evidence Absence Ambiguity) added as candidate
    domain insight. "Absence of evidence is not evidence of absence."
    This is a trustworthiness principle, not only a threat finding.
    Promotion requires cross-round evidence from 36C-03 and 36D.

  OBS-36C-02-3: Constitutional criteria column added to suppression matrix.
    Every evidence artifact now shows which constitutional criterion
    it supports — enabling traceability from evidence to capability
    for use in 36E and 37.

  OBS-36C-02-4: Software defect section unchanged (positive finding).
    "A trust model must survive incompetence, not only malice."

  OBS-36C-02-5: GovernanceTransitionCompleted downgraded from "most dangerous
    scenario" to "candidate most dangerous scenario." Other artifacts
    not yet fully analyzed may rank higher after 36C-03 through 36C-06.

  OBS-36C-02-6: Potential future threat class recorded — Evidence Governance
    Failure: the election has no agreed definition of what evidence should
    exist. Not the same as suppression. Suppression presupposes a defined
    expected set. Governance Failure precedes definition. Carried as
    observation; not promoted to threat class yet.

Central findings (unchanged):

  TF-36C-02-01: Suppression at emission not demonstrated detectable.
  TF-36C-02-03: State 1 (never existed) and State 2 (suppressed) are
    constitutionally indistinguishable — the heart of Gap A-3.
  TF-36C-02-05: GovernanceTransitionCompleted suppression is a candidate
    for closing the Concern A Certification path.
  TF-36C-02-09/10: TC1-NCQ-01 and TC1-NCQ-02 added as constitutional
    questions for ARB determination.

TC1-DI-01 (Evidence Absence Ambiguity): CANDIDATE DOMAIN INSIGHT

OBS-36C-02-0 compliance confirmed:
  No completeness mechanism proposed.
  No Merkle trees, hash chains, bulletin boards, event ledgers introduced.
  All findings are threat findings, not design decisions.

Round 36C-02: APPROVED
Round 36C-03 (Evidence Fabrication): AUTHORIZED
```
