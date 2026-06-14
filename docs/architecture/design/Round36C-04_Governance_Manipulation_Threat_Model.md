# Round 36C-04 — Governance Manipulation Threat Model

**Date:** 2026-06-13

**Phase:** Round 36C — Threat Modeling Research

**Sub-document:** 36C-04 (Threat Class 3: Governance Manipulation)

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 36B Closure — APPROVED
- Round 36C-01 Threat Modeling Baseline — APPROVED
- Round 36C-02 Evidence Suppression — APPROVED WITH MAJOR OBSERVATIONS
- Round 36C-03 Evidence Fabrication — APPROVED WITH OBSERVATIONS
- Carried forward: TC1-DI-01, TC2-DI-01 (candidate), TF-36C-COMB-01 (program-level)
- OBS-36C-03-G: Evidence Authenticity ≠ Evidence Completeness — carry as two independent dimensions

**Governing Question:**

```
TC-1 asked: Can evidence disappear?
TC-2 asked: Can false evidence become trusted evidence?

TC-3 asks: Can trustworthiness fail without suppressing evidence
           and without fabricating evidence?

More precisely:
Can an election with complete, authentic, correctly recorded evidence
be constitutionally manipulated through governance alone?
```

**Preventive Governance Rule (binding):**

```
Do NOT propose mechanisms.

Do NOT introduce:
  Digital Signatures, Threshold Cryptography,
  Merkle Trees, Hash Chains, Zero-Knowledge Proofs,
  Blockchain, ElectionGuard, Helios

TC-3 investigates governance threats.
The attack surface is governance.
Solutions belong to 36E.

Governance question ≠ bounded context.
Ownership ≠ bounded context.
Authority ≠ bounded context.
Criteria ≠ bounded context.
Threat ≠ bounded context.
```

**OBS-36C-03-G Carry-Forward (binding):**

```
Evidence Authenticity and Evidence Completeness
are two independent constitutional dimensions.

Governance Manipulation may attack:
  Dimension 1 — Completeness: by redefining what evidence should exist.
  Dimension 2 — Authenticity: by redefining what "legitimate" means.

These attacks are constitutionally different.
This document must preserve the distinction.
Do NOT merge them.
```

---

## Section 1 — The TC-3 Analytical Frame

**What TC-3 assumes (for analytical isolation):**

```
TC-3 operates under a maximum-evidence assumption:

  The audit record is COMPLETE.
    (Gap A-3 has been addressed — assume for this analysis.)

  All recorded events are AUTHENTIC.
    (TC-2 fabrication threats have been addressed — assume for analysis.)

  No evidence has been suppressed.
    (TC-1 suppression threats have been addressed — assume for analysis.)

Under this assumption:
  Can an election still fail to be trustworthy?

If the answer is YES:
  Governance manipulation exists as an independent threat class.
  It is not derivative of TC-1 or TC-2.
  It attacks at a different layer.

TC-3 tests this claim.
```

**Why this matters constitutionally:**

```
TC-1 and TC-2 attack the evidence record.
TC-3 attacks the rules governing the record.

A system can have:
  Perfect evidence (complete, authentic, correctly recorded)
and still have:
  Constitutionally illegitimate governance.

These are independent failure modes.
Defense against TC-1 and TC-2 does not defend against TC-3.
Defense against TC-3 does not defend against TC-1 and TC-2.
All three must be addressed independently.
```

---

## Section 2 — TC3-Q1: Authority Manipulation

**Governing question:** Who can redefine who holds authority?

### 2.1 The Authority Problem (from 36B)

```
Round 36B-05 established:

  Q-X (Certification Authority): STRUCTURALLY EXTERNAL
    No operational aggregate can self-declare certification authority.
    Circular: the actor claiming authority would certify their own authority.

  NCQ-02 (still open):
    The authority requires structural independence from operational aggregates.
    Whether that independence is external or internal-but-constitutionally-separated
    remains an ARB constitutional determination.
```

**TC3-Q1 asks:** What if authority is claimed without that structural independence?

### 2.2 D43 — Enrollment Authority Gap

```
D43: Enrollment authority ownership UNRESOLVED.

Eligibility reads EnrollmentStatus.
Who writes EnrollmentStatus?
Who is constitutionally authorized to enroll voters?

Currently: no discovered aggregate owns this.

TC-3 implication:
  An undefined authority is an unclaimed authority.
  An unclaimed authority can be claimed by any actor.

Authority Vacuum scenario:
  Actor claims enrollment authority (legitimate-appearing).
  Actor enrolls ineligible voters or revokes eligible voters.
  All actions are recorded in the audit log.
  All events are authentic — they were produced by the actor.
  The audit record is complete — no events were suppressed.
  The election is constitutionally fraudulent.

Detection question:
  The audit record is complete and authentic.
  TC-1 and TC-2 defenses are satisfied.
  Who detects that enrollment authority was illegitimately claimed?

Currently: no discovered structure has this detection capability.
```

### 2.3 Authority Manipulation Scenarios

| Scenario | Mechanism | TC-1 or TC-2 involved | Constitutional consequence |
|----------|-----------|----------------------|---------------------------|
| False enrollment authority claim | Actor claims D43-undefined authority; enrolls/revokes voters | Neither — events are authentic | Ineligible voters participate; eligible voters excluded; audit record complete and authentic |
| Governance authority redefinition | GovernanceState authority ownership reassigned during election | Neither — GovernanceTransitionCompleted emitted legitimately by new authority | Governance history reflects authorized transitions; new authority may not be constitutionally valid |
| Certification authority substitution | Non-independent actor declares certification authority; certifies election | Neither — certification events are authentic | Certification is constitutionally void even though all evidence is perfect |
| Retroactive authority assignment | Authority assigned after election to justify post-hoc decisions | Neither | Constitutional timeline falsified; decisions made before authority was assigned now appear authorized |

**Finding TC3-Q1-F1:**

```
Where authority ownership is undefined,
authority claims cannot be evaluated for legitimacy.

A legitimate-appearing authority claim produces authentic events.
Authentic events from a false authority are constitutionally equivalent
to authentic events from a legitimate authority — in the audit record.

The audit record cannot distinguish:
  legitimate-authority action
from:
  false-authority action with identical event content.

This is Authority Fabrication — not event fabrication (TC-2)
but governance-layer fabrication of authority itself.
```

---

## Section 3 — TC3-Q2: Criteria Manipulation

**Governing question:** Can an election become constitutionally illegitimate because criteria were undefined, contradictory, or changed during execution?

### 3.1 The Criteria Problem (from 36B)

```
Round 36B-05 established:

  Q-K (Criteria ownership):
    Configuration criteria: GovernanceState — PRESUMPTIVE OWNER
    Constitutional validity criteria: UNDISCOVERED (NCQ-03)

  The distinction:
    Configuration criteria: "the election must have at least 3 candidates per post"
    Constitutional criteria: "the election meets the organization's democratic requirements"

    GovernanceState may own configuration.
    Who owns constitutional criteria is an open question.
```

**TC3-Q2 asks:** What if criteria are wrong, undefined, or changed?

### 3.2 Criteria Ambiguity (OBS-36C-01-B / Scenario S3-D)

```
OBS-36C-01-B from Round 36C-01:
  Criteria Ambiguity is a threat scenario.
  "Ambiguity, not corruption, is the most common real-world election failure."

Scenario S3-D:
  Criteria exist but are ambiguous.
  Two reasonable interpretations are possible.
  The interpretation applied validates a different outcome than the alternative.

TC-3 implication:
  Ambiguity is exploitable.
  An actor with criteria interpretation authority
  can choose the interpretation that serves their interest.
  This requires no evidence suppression.
  This requires no event fabrication.
  The manipulation is entirely in the interpretation layer.
```

### 3.3 Criteria Change During Execution

```
Scenario: Criteria modified AFTER voting begins but BEFORE certification.

  Phase 1: Election configured. GovernanceDecisionSnapshot records criteria.
  Phase 2: Voting begins. Criteria A in effect.
  Phase 3: Criteria changed. New GovernanceDecisionSnapshot recorded.
  Phase 4: Certification. Certifier uses Phase 3 criteria.

Constitutional questions:
  When are criteria locked? (36A-DI-05 — governance configuration freeze)
  Which snapshot is authoritative for certification?
  Can GovernanceState emit a legitimate transition that retroactively invalidates votes?

From 36A-DI-05: GovernanceState configuration must be immutable before vote collection begins.
TC-3 observation: what enforces this immutability?
Who notices if it is violated at certification time?

If no discovered structure enforces this:
  Criteria can be changed with complete, authentic evidence of the change.
  The audit record is complete. The criteria change is authentic.
  The election may still be constitutionally void.
```

### 3.4 Retroactive Criteria Application

```
Scenario: Criteria defined or clarified AFTER the election concludes.

  Election completes. Results tabulated.
  Certifier then defines the criteria the election should have met.
  Election is certified against post-hoc criteria.

Constitutional consequence:
  No suppression occurred.
  No fabrication occurred.
  Criteria application is constitutionally dangerous
  because constitutional validity cannot be evaluated retroactively
  against criteria that did not govern the decisions they now claim to cover.
```

**Finding TC3-Q2-F1:**

```
Constitutional criteria have three independent failure modes:

  1. Undefined:
     No criteria exist at evaluation time.
     Certification proceeds against implicit or invented criteria.

  2. Ambiguous:
     Multiple interpretations are possible.
     The interpretation applied serves the interpreting actor's interest.

  3. Changed:
     Criteria mutate during or after execution.
     Evaluation applies wrong-epoch criteria.

All three modes require neither suppression nor fabrication.
They are purely governance-layer attacks.
```

---

## Section 4 — TC3-Q3: Ownership Ambiguity

**Governing question:** Can responsibility be avoided because ownership is undefined?

### 4.1 The Ownership Gap Pattern

```
Round 36C-03 (OBS-36C-03-D) identified three ownership gaps
with the same constitutional pattern:

  Gap A-3 (Expected Evidence Set):
    Who defines what evidence should exist?
    Who is accountable for completeness?

  D43 (Enrollment Authority):
    Who holds enrollment authority?
    Who is accountable for eligibility decisions?

  TC2-NCQ-01 (Event Provenance):
    Who holds provenance responsibility?
    Who is accountable when events cannot be traced?

Pattern: Responsibility must be assigned before accountability can exist.
         Accountability must exist before trustworthiness can be claimed.
```

**TC3-Q3 asks:** What happens constitutionally when ownership is undefined?

### 4.2 Two Ownership Failure Modes

**Mode 1 — Authority Vacuum:**

```
No actor owns the responsibility.
No actor can be held accountable.
The responsibility goes unperformed or performed without authorization.

Example (Gap A-3):
  No actor is responsible for completeness specification.
  When the audit record is incomplete:
    No actor can be investigated.
    No actor can be held accountable.
    The incompleteness may not be detected.
    Even if detected, it cannot be traced to a responsible party.

Example (D43):
  No actor owns enrollment authority.
  Enrollment decisions are made by any available actor.
  No enrollment decision can be evaluated for legitimacy.
  All enrollment events are authentic (the actor genuinely made them).
  None can be certified as constitutionally authorized.
```

**Mode 2 — Competing Claims:**

```
Multiple actors claim the same undefined ownership.
Conflicting decisions are made.
The audit record is authentic for each decision.
Which decision is constitutionally authoritative?

Example:
  GovernanceState and an external authority
  both claim criteria ownership.
  Their criteria conflict.
  The election is certified against one set.
  The other set challenges the certification.

  Both evidence sets are authentic.
  Both criteria sets exist.
  Neither is constitutionally dispositive without a prior ownership determination.
```

### 4.3 Ownership Ambiguity as TC-3 Leverage

```
An adversary need not attack the evidence.

Instead:
  Create ownership ambiguity before the election.
  After the election, claim ownership of the relevant authority.
  The claim is constitutionally unprovable as false
  because ownership was never assigned.

The audit record shows:
  The actor's decisions are recorded.
  The decisions appear authorized (the actor genuinely made them).
  No suppression occurred.
  No fabrication occurred.

The constitutional fraud is in the governance claim, not the evidence record.
```

---

## Section 5 — TC3-Q4: Evidence Governance Failure

**Governing question:** If nobody defines what evidence should exist, can trustworthiness fail before suppression occurs?

### 5.1 OBS-36C-02-6 Context

```
OBS-36C-02-6 (from Round 36C-02):
  Potential future threat class: Evidence Governance Failure.

  "If nobody defines what evidence should exist,
   the completeness question (Gap A-3) is constitutionally unanswerable."

  This is distinct from suppression:
    Suppression: evidence was defined as required; it disappeared.
    Evidence Governance Failure: evidence was never defined as required.
      It never existed. And nobody can say it was missing.
      Because nobody defined it as required.

Evidence Governance Failure is confirmed as a governance-layer threat in TC-3.
It does not belong to TC-1 (which requires prior definition).
It belongs here, in Governance Manipulation.
```

### 5.2 Evidence Governance as Constitutional Requirement

```
For trustworthiness to be claimed:

  1. Evidence must exist.                       (TC-1 protection)
  2. Evidence must be true.                     (TC-2 protection)
  3. The expected evidence set must be defined.  (TC-3 prerequisite)

Claim: #3 is a governance requirement, not a technical requirement.

Who defines what evidence must exist?
  Not the audit system — it records what it receives.
  Not the aggregates — they produce events for their domain.
  Nobody currently owns this specification in the discovered model.

The absence of ownership for Evidence Specification (#3)
means that even a complete, authentic audit record
cannot be evaluated for trustworthiness completeness.
```

### 5.3 Evidence Specification Control as TC-3 Leverage

```
Evidence Governance Failure does not require an attacker.
It may be structural.

But it becomes exploitable:

Scenario:
  Actor designs election evidence requirements
  to exclude events that would expose governance failures.

  The voter verification process produces a critical event.
  But that event type was never specified as required.
  It is therefore never required.
  The audit record is "complete" by its own specification.
  The governance failure is invisible.

This is Governance Manipulation through Evidence Specification control.
  The attacker does not suppress evidence.
  The attacker ensures the required evidence set does not include
  the evidence that would reveal the manipulation.

TC-3 finding: whoever controls Evidence Specification
strongly influences what trustworthiness can be evaluated against
in that election.
```

**OBS-36C-03-G connection:**

```
Evidence Governance Failure attacks Dimension 1 (Completeness)
at the specification layer — before evidence is produced or suppressed.

If the specification is wrong or missing:
  The election may be 100% complete against specification.
  0% complete against constitutional requirements.

Evidence Authenticity (Dimension 2) is irrelevant
if Evidence Specification (the foundation of Dimension 1) is wrong.

This reveals three specification failure levels:
  Level 1: Specification (TC-3 / this section)
  Level 2: Completeness against specification (TC-1 / Gap A-3)
  Level 3: Authenticity of what is present (TC-2)

A trustworthiness claim must survive failure at any level.
```

---

## Section 6 — TC3-Q5: Constitutional Rule Manipulation

**Governing question:** Can all evidence be authentic, complete, and correctly recorded while the governing rules themselves make the election constitutionally illegitimate?

### 6.1 The Deepest TC-3 Attack Layer

```
TC-1: Remove evidence.
TC-2: Alter or fabricate evidence.
TC-3: Change the rules so that
      complete + authentic evidence
      certifies a constitutionally invalid election.

This is the deepest governance manipulation.
It operates below all evidence protections.
Evidence defenses (TC-1, TC-2) cannot address it.

Rule manipulation attacks the constitutional layer itself.
```

### 6.2 Constitutional Rule Manipulation Scenarios

| Rule Type | Manipulation Scenario | Evidence Impact | Constitutional Consequence |
|-----------|----------------------|-----------------|---------------------------|
| Eligibility rules | Redefine who may vote mid-election | No evidence change needed | Ineligible voters appear eligible under new rule; eligible voters excluded under new rule |
| Quorum rules | Lower quorum threshold after low turnout | GovernanceTransitionCompleted is authentic | Election validated with insufficient participation |
| Voting window | Extend voting window after observing partial results | GovernanceSuspended/Resumed are authentic | Strategic voting occurs; election fairness violated |
| Regional scope | Reclassify regional post as national mid-election | Configuration change is authentic | Regional voters gain/lose voting rights during active election |
| Result validation | Change what constitutes a valid vote after ballots cast | Criteria change is authentic | Valid votes invalidated or invalid votes validated retroactively |
| Certification threshold | Redefine threshold for certification after tally | No evidence change needed | Marginal outcome certified under changed criteria |

**All six scenarios share the same constitutional structure:**

```
Before rule change:
  Election behavior is recorded authentically.
  Audit record is complete.

After rule change:
  The same authentic, complete record
  now certifies a constitutionally different election.

The rule change is itself authentic and recorded.
The GovernanceDecisionSnapshot shows the change occurred.

But:
  Constitutional validity requires rules to be constitutionally fixed
  before the decisions they govern.
  Post-hoc rule changes retroactively redefine what "correct" means
  for decisions already made.
  A retroactive redefinition is itself a constitutional violation.
```

### 6.3 GovernanceDecisionSnapshot as Constitutional Evidence

```
From Round 34A and Round 35:
  GovernanceDecisionSnapshot is the Replay mechanism's constitutional record.
  ReplaySession uses it to validate governance history.
  ADGR-1: ReplaySession is CANDIDATE, blocked.

TC-3 observation:
  GovernanceDecisionSnapshot records WHAT the rules were.
  It does not record whether they were applied WHEN they were required to be.

  A complete, authentic snapshot record showing:
    Rule A until T1
    Rule B from T1 forward

  Cannot by itself establish:
    Whether T1 occurred at a constitutionally permissible moment.
    Whether the transition from A to B was constitutionally authorized.
    Whether the transition was reviewed before or after the outcome was known.

This is the governance oracle problem:
  The record of governance is not the same as governance legitimacy.
  Governance legitimacy requires external criteria for WHEN changes are permissible.
  Those criteria are currently UNDISCOVERED (NCQ-03).
```

---

## Section 7 — The Governance Threat Independence Demonstration

```
TC-3 has tested its governing question:

  "Can trustworthiness fail without suppressing evidence
   and without fabricating evidence?"

Answer: YES.

The demonstrations:

  TC3-Q1 (Authority): A false authority claim produces authentic events.
    The audit record is complete and authentic.
    The governance is fraudulent.

  TC3-Q2 (Criteria): Changed or ambiguous criteria exploit authentic records.
    The audit record is complete and authentic.
    The certification against wrong criteria is constitutionally void.

  TC3-Q3 (Ownership): Undefined ownership cannot be evaluated for legitimacy.
    All events are authentic.
    No accountability can be established.

  TC3-Q4 (Evidence Governance): Insufficient evidence specification
    produces "complete" records that are not constitutionally sufficient.
    No suppression occurred.
    The record satisfies its own specification.
    It may not satisfy constitutional requirements.

  TC3-Q5 (Rule Manipulation): Rules changed after decisions are made
    retroactively redefine constitutional validity.
    All evidence is authentic and complete.
    The constitutional framework is fraudulent.

All five demonstrations require neither suppression nor fabrication.
TC-3 exists as an independent threat class.

This validates the five-threat-class ordering from 36C-01.
```

**Candidate third constitutional dimension:**

```
TC-1 and TC-2 established two evidence dimensions:
  Dimension 1: Completeness (did all expected evidence appear?)
  Dimension 2: Authenticity (did observed evidence originate from legitimate acts?)

TC-3 demonstrates that governance-related threats exist
independently of both dimensions.

This is consistent with a Candidate Dimension 3:
  Candidate Dimension 3 — Governance Legitimacy:
    Were the rules constitutionally established?
    Was authority constitutionally recognized?
    Were criteria constitutionally valid at application time?

This remains a CANDIDATE dimension.
It has not been demonstrated that these governance concerns
collapse into a single coherent constitutional dimension.
What has been demonstrated:
  Governance threats exist independently of TC-1 and TC-2.
  They are not reducible to evidence completeness or evidence authenticity.

Whether they form a single coherent dimension
requires corroboration from TC-4, TC-5, and 36D.
```

---

## Section 8 — TC3 Actor Analysis

| Actor | Authority Manipulation | Criteria Manipulation | Ownership Ambiguity | Evidence Governance Failure | Constitutional Rule Manipulation |
|-------|----------------------|----------------------|---------------------|-----------------------------|---------------------------------|
| Election operator | HIGH — runs execution; can claim D43 authority | HIGH — controls GovernanceState configuration | HIGH — occupies vacuums in normal ops | HIGH — controls what evidence is produced | MEDIUM — depends on rule-change access |
| Governance authority | CRITICAL — holds GovernanceState; can transition rules | CRITICAL — owns criteria configuration presumptively | CRITICAL — can preemptively claim undefined authority | CRITICAL — can specify evidence requirements | CRITICAL — the authoritative rule source |
| Certifier | MEDIUM — can claim criteria interpretation authority | HIGH — evaluates against criteria; chooses interpretation | MEDIUM — can claim undefined criteria ownership | LOW | MEDIUM — applies criteria; retroactive application possible |
| External actor | LOW — requires access | LOW — unless criteria unprotected | MEDIUM — can claim undefined authority during vacuum | LOW | LOW |
| **Software defect / procedural failure** | **MEDIUM — produces authority confusion through ordering defect** | **HIGH — applies wrong-epoch criteria; version mismatch** | **HIGH — no actor claims responsibility; vacuum persists by default** | **CRITICAL — if evidence specification is never formally produced** | **HIGH — applies governance rules in wrong sequence** |

**Software defect as governance manipulation actor:**

```
Software defects produce governance manipulation without malicious intent.

Authority ordering defect:
  System applies enrollment authority from actor A
  but certification validation requires actor B.
  The defect is not malice.
  The constitutional consequence is identical.

Criteria version mismatch:
  GovernanceDecisionSnapshot from Epoch 2 applied to votes cast in Epoch 1.
  The wrong criteria are used because snapshots are indexed incorrectly.
  The audit record shows the correct snapshot.
  The application of it was wrong.

Temporal ordering defect:
  Governance transition recorded at T1; applied from T0 due to clock error.
  Votes cast in window T0-T1 were validated against criteria not yet in effect.

A trust model must survive procedural and software errors,
not only deliberate manipulation.
Governance manipulation from software defect is in scope.
It produces identical constitutional consequences as deliberate manipulation.
```

---

## Section 9 — Candidate Domain Insights

**TC3-DI-01 — CANDIDATE DOMAIN INSIGHT: Governance-related threats exist independently of evidence completeness and authenticity**

```
An election with complete, authentic evidence
can be constitutionally manipulated through governance.

This has been demonstrated across five governance attack vectors
(TC3-Q1 through TC3-Q5).

The governance dimension — however it is finally characterized —
is not reducible to TC-1 or TC-2.

Status: CANDIDATE — consistent with a third constitutional dimension.
Whether governance concerns form a single coherent dimension
requires corroboration from TC-4, TC-5, 36D.
```

**TC3-DI-02 — CANDIDATE DOMAIN INSIGHT: Undefined ownership is an attack surface**

```
Where ownership is undefined:
  Authority vacuums can be claimed by any actor.
  Responsibility vacuums remove accountability entirely.

Undefined ownership is not neutral.
It is constitutionally equivalent to an exploitable gap in the governance model.

This applies uniformly to:
  D43 (Enrollment Authority)
  Gap A-3 (Evidence Completeness Ownership)
  TC2-NCQ-01 (Event Provenance Ownership)
  NCQ-03 (Constitutional Criteria Ownership)

Status: CANDIDATE — requires corroboration from TC-4, TC-5, 36D.
```

**TC3-DI-03 — CANDIDATE DOMAIN INSIGHT: Constitutional criteria must be fixed before the decisions they govern**

```
Constitutional validity cannot be evaluated against criteria
that did not govern the decisions under evaluation.

This is not an absolute requirement that all criteria must exist
before execution begins. Constitutions may allow:
  clarifications, procedural interpretations, administrative notices.

The stronger claim is:
  Constitutional criteria must be constitutionally fixed
  before the decisions they govern.

  Retroactive application of criteria to decisions already made
  is constitutionally dangerous
  because it allows the governing rules to be shaped
  by knowledge of the outcome.

This extends 36A-DI-05 (Governance Configuration Freeze)
from the configuration domain to the constitutional criteria domain.

Status: CANDIDATE — requires corroboration from TC-4, 36D.
```

---

## Section 10 — New Constitutional Questions

**TC3-NCQ-01: When does the governance framework become constitutionally locked?**

```
36A-DI-05 established that configuration must be immutable before vote collection.

TC-3 asks: Is there a constitutional moment at which the entire governance framework
  (not just technical configuration) becomes locked for a given election?

  Before that moment: governance changes may be legitimate.
  After that moment: governance changes may be constitutionally prohibited.

What is that moment?
Who establishes it?
Who enforces it?
What constitutes a violation?

TC3-NCQ-01 requires ARB constitutional determination.
```

**TC3-NCQ-02: Who is constitutionally authorized to interpret ambiguous criteria?**

```
OBS-36C-01-B established criteria ambiguity as a threat scenario.

If criteria are ambiguous:
  Who has constitutional authority to resolve the ambiguity?
  Is interpretation authority the same as criteria ownership?
  Can interpretation be disputed? If yes, by whom? Through what process?

TC3-NCQ-02 requires ARB constitutional determination.
```

**TC3-NCQ-03: Is evidence specification itself a constitutionally required artifact?**

```
Gap A-3 depends on the existence of an "expected evidence set."

Who produces that specification?
Is its production constitutionally mandatory?

If evidence specification is optional:
  Gap A-3 may be permanently unanswerable.
  Completeness claims become meaningless without a reference.

If evidence specification is mandatory:
  Who must produce it?
  When must it be produced?
  What happens if it is incomplete or absent?

TC3-NCQ-03 requires ARB constitutional determination.
```

**TC3-NCQ-04: Who governs the governors?**

```
36C-04 repeatedly identifies:
  Authority ownership
  Criteria ownership
  Interpretation authority
  Certification authority

But every identified authority creates the same recursion:
  Who authorizes the authority?
  Who validates the validator?
  Who certifies the certifier?

This is deeper than D43.

D43 asks: who holds enrollment authority?
TC3-NCQ-04 asks: who constitutionally authorizes that authority
  to hold that role?

If every authority traces to another authority,
and that chain must terminate:
  Where does constitutional authority ultimately ground?
  External to the system (organizational constitution)?
  Internal with explicit constitutional separation?

This question affects TC-4 (Certification Abuse) and TC-5 (Trust Concentration)
and ultimately shapes 36E's architectural impact assessment.

TC3-NCQ-04 requires ARB constitutional determination.
```

---

## Section 11 — Threat Findings Summary

| Finding | Type | Priority |
|---------|------|----------|
| TF-36C-04-01: Where authority ownership is undefined, authority claims cannot be evaluated for legitimacy (D43 primary instance) | Constitutional | CRITICAL |
| TF-36C-04-02: Authentic audit records cannot distinguish legitimate-authority action from false-authority action with identical event content | Constitutional | CRITICAL |
| TF-36C-04-03: Criteria ambiguity is exploitable without any evidence manipulation | Constitutional | CRITICAL |
| TF-36C-04-04: Criteria changed before the decisions they govern are made produce constitutionally suspect certification even with perfect evidence | Constitutional | CRITICAL |
| TF-36C-04-05: Whoever controls Evidence Specification strongly influences what trustworthiness can be evaluated against in that election | Constitutional | CRITICAL |
| TF-36C-04-06: Evidence specification absence is an election-design-time governance failure — not a suppression event | Constitutional | HIGH |
| TF-36C-04-07: Constitutional rule manipulation produces constitutionally invalid elections with complete, authentic evidence | Constitutional | CRITICAL |
| TF-36C-04-08: GovernanceDecisionSnapshot records what rules were; it does not certify that changes were constitutionally permissible when applied | Architectural | HIGH |
| TF-36C-04-09: Software defect produces governance manipulation consequences constitutionally indistinguishable from deliberate manipulation | Constitutional | HIGH |
| TF-36C-04-10: TC3-NCQ-01 — constitutional lock moment for governance framework undefined | Constitutional | CRITICAL |
| TF-36C-04-11: TC3-NCQ-02 — criteria interpretation authority undefined | Constitutional | HIGH |
| TF-36C-04-12: TC3-NCQ-03 — whether evidence specification is constitutionally mandatory is undetermined | Constitutional | CRITICAL |
| TF-36C-04-13: TC3-NCQ-04 — who governs the governors? Constitutional authority grounding undefined | Constitutional | CRITICAL |
| TF-36C-04-14: Governance-related threats demonstrated to be independent of TC-1 and TC-2; consistent with candidate third constitutional dimension | Constitutional | CRITICAL |

**Candidate Domain Insights:**

```
TC3-DI-01 (CANDIDATE): Governance-related threats exist independently of evidence completeness and authenticity
TC3-DI-02 (CANDIDATE): Undefined ownership is an attack surface — not a neutral state
TC3-DI-03 (CANDIDATE): Constitutional criteria must be fixed before the decisions they govern
```

**Architectural Impact Candidates (for 36E):**

```
AIC-36C-04-01: Governance legitimacy evaluation mechanism —
  how does the system evaluate whether governance rules were constitutionally applied?
  Who holds this responsibility? (TC3-NCQ-01 input)

AIC-36C-04-02: D43 resolution — enrollment authority must be assigned
  before enrollment decisions can be constitutionally evaluated.

AIC-36C-04-03: Constitutional criteria specification artifact —
  is there a constitutionally required artifact that specifies what criteria apply?
  When must it exist? (TC3-NCQ-03 input)

AIC-36C-04-04: Governance framework lock enforcement —
  design-level enforcement of 36A-DI-05 extended to constitutional criteria.

AIC-36C-04-05: Evidence Specification artifact —
  who authors it, when must it exist, who holds the specification authority?
  (Gap A-3 / TC3-Q4 input for 36E)

AIC-36C-04-06: Constitutional authority grounding —
  how does the system trace authority to a constitutionally recognized source?
  (TC3-NCQ-04 input for 36E and 37)
```

---

## ARB Decision

```
Round 36C-04 — Governance Manipulation Threat Model

APPROVED

Research Discipline:        HIGH
Threat Modeling Quality:    HIGH
Governance Discipline:      VERY HIGH
Architecture Neutrality:    VERY HIGH
Confidence:                 HIGH

Observations pre-applied (all four corrections incorporated before submission):

  OBS-36C-04-1: Governance Legitimacy = CANDIDATE third dimension.
  OBS-36C-04-2: Evidence Specification influence wording softened.
  OBS-36C-04-3: TC3-DI-03 criteria timing generalized.
  OBS-36C-04-4: TC3-NCQ-04 "Who governs the governors?" added.

Post-approval research notes (non-blocking carry-forwards):

  NOTE-36C-04-A: "Authority Fabrication" (TC3-Q1) may be more precisely
    named "Authority Legitimacy." The actor in TC3-Q1 may genuinely exist,
    genuinely act, and genuinely emit authentic events — the problem is the
    constitutional legitimacy of the authority claim, not fabrication.
    36D should evaluate whether Authority Legitimacy is the more accurate
    abstraction.

  NOTE-36C-04-B: TC3-NCQ-04 ("Who governs the governors?") is HIGH PRIORITY
    FOR 36D. It links D43, authority ownership, criteria ownership,
    certification authority, and trust concentration into one constitutional
    recursion. Trust distribution research is exactly where this question belongs.

  NOTE-36C-04-C: TF-36C-04-14 is elevated to CANDIDATE PROGRAM-LEVEL INSIGHT.
    The finding: complete + authentic evidence + illegitimate governance =
    untrustworthy election — changes the trust model itself.
    Before 36C, the implicit assumption was: complete + authentic = trustworthy.
    36C-04 has refuted that assumption.
    Carry this elevation into 36D, 36E, and the ADR phase.

Central findings:
  TF-36C-04-01: Undefined authority = exploitable vacuum.
  TF-36C-04-03: Criteria ambiguity = exploitable without evidence manipulation.
  TF-36C-04-05: Evidence Specification control strongly influences trustworthiness scope.
  TF-36C-04-07: Perfect evidence + manipulated rules = constitutional fraud.
  TF-36C-04-14 (CANDIDATE PROGRAM-LEVEL): Governance threats independent of TC-1 and TC-2 — demonstrated.

  TC3-DI-01/02/03: Three candidate domain insights.
  TC3-NCQ-01/02/03/04: Four new constitutional questions.

Governing Rule Compliance:
  No digital signatures, Merkle trees, ZK proofs, or cryptographic mechanisms.
  No bounded contexts created.
  No architecture decisions made.
  Threats discovered and classified only.

Round 36C-04: APPROVED

Governing Instructions for Round 36C-05 (ARB binding):

  TC-4 investigates Certification Abuse.
  The governing question for TC-4:
    Can certification make an untrustworthy election appear trustworthy?

  Focus on:
    NCQ-02 (structural independence requirement for Certification Authority)
    TC3-NCQ-04 (who governs the governors — applied to Certification layer)
    Certification against fabricated record (TC-2 × TC-4 intersection)
    Certification against manipulated criteria (TC-3 × TC-4 intersection)
    False certification (certifier corrupted, incompetent, or coerced)
    Certification without authority (procedural authority substitution)

  Carry:
    Candidate Dimension 3 (Governance Legitimacy) — test whether Certification
    Abuse is a sub-category of governance manipulation or an independent threat.
    TC3-NCQ-04 — does Certification provide the constitutional grounding
    of authority? Or does it also require a prior grounding?

  Governance threats first.
  No cryptographic mechanisms.
  Discover threats only.

Round 36C-05 (Certification Abuse): AUTHORIZED
```
