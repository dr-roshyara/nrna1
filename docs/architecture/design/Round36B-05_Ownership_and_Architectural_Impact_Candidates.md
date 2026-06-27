# Round 36B-05 — Ownership and Architectural Impact Candidates

**Date:** 2026-06-13

**Phase:** Round 36B — Auditability Research

**Sub-document:** 36B-05 (Ownership and Architectural Impact Candidates)

**Authority:** Architecture Review Board

**Governance Foundation:**
- Round 36B-01 through 36B-04 — APPROVED (complete chain)
- 36B-CFI-01 — CONFIRMED: Auditability, Verifiability, and Certification are separable constitutional capabilities
- OBS-36B-05-0 (Preventive, binding from 36B-04 ARB authorization): Do NOT assume Auditability Owner = Verifiability Owner = Certification Owner
- OBS-36B-05-1 (Preventive, binding from 36B-04 ARB authorization): Do NOT assume ownership implies a new bounded context. Ownership discovery precedes bounded context discovery. 36B-05 discovers owners; 36E/37 determine whether existing or new contexts are required.
- OBS-36B-03-C (binding): Observer and Certifier are roles. Roles are not automatically bounded contexts.

**Purpose:**

```
36B-CFI-01 established that three capabilities are separable.

The question is now: in NRNA's discovered domain model,
who holds each capability?

This document maps the six ownership questions from 36B-04
against the discovered domain model.

It produces architectural impact candidates for Round 36E.
It does NOT produce design decisions.
It does NOT promote new bounded contexts.
```

**Governing Rule (binding from 36B-04 ARB authorization):**

```
Focus on ownership questions.

Do NOT discuss mechanisms:
  Merkle Trees, Bulletin Boards, ElectionGuard, Helios, Scantegrity

The mechanisms are already documented (36B-02, 36B-03).

The question now is: who owns what?
```

---

## Section 1 — The Six Ownership Questions

From 36B-04 Section 11 and ARB authorization:

| # | Question | Blocker |
|---|----------|---------|
| Q-A | Who owns Auditability? | Gap A-3 (Completeness) |
| Q-V | Who owns Verifiability? | D39 (Concern B only) |
| Q-C | Who owns Certification? | Authority undiscovered |
| Q-E | Who owns Evidence? | Gap A-3 affects completeness claim |
| Q-K | Who owns Certification Criteria? | Constitutional question |
| Q-X | Who owns Certification Authority? | No discovered aggregate |

**OBS-36B-05-0 (Preventive):**

```
The capabilities are separable.
Ownership may therefore also be separable.

A single owner for all three would collapse the separation
that 36B-CFI-01 confirmed as a cross-family pattern.

Before asking "who owns all three?"
first ask "which is separable in NRNA's constitution?"

This document applies that discipline.
```

---

## Section 2 — Discovered Domain Inventory

The ownership analysis is constrained to aggregates and contexts already discovered. No new structures may be introduced as owners in this document — that is 36E's work.

**Discovered aggregates (Round 33, confirmed through Round 35):**

| Aggregate | Classification | Core Authority |
|-----------|---------------|----------------|
| Vote | APPROVED DESIGN | Records individual ballot; VO-1 boundary |
| Verification | APPROVED DESIGN | Individual verification status |
| GovernanceState | APPROVED PATTERN | Governance transitions and freeze |
| RoleAssignment | CANDIDATE | Role/trust status — D43 blocker active |
| ReplaySession | CANDIDATE | Governance replay — ADGR-1/D35-D37 blockers active |

**Discovered contexts (Round 36A):**

| Context | Status | Core Authority |
|---------|--------|----------------|
| Audit context | OBSERVED (passive) | Event observation only — NOT an authority (OBS-36A-08-1) |
| Verification Representation Context | CANDIDATE (C-02) | Verification surface publication — Constitutional Enforcement scope |

**Binding constraints active during this analysis:**

| Constraint | Effect |
|------------|--------|
| D39 | Tally ownership unresolved — all Concern B Verifiability deferred |
| D43 | Enrollment authority undiscovered |
| Gap A-3 | Completeness unresolved — Auditability is partial |
| Gap A-2 | Access pattern unresolved — Auditability is partial |
| VO-1 | Voter identity must never appear in audit record or vote event payload |
| OBS-36A-08-1 | Audit context is passive observer, NOT publication owner, NOT authority |

---

## Section 3 — Auditability Ownership (Q-A)

**The question:** Who in NRNA's discovered model holds the capability: "evidence can be inspected"?

Auditability requires three properties (from 36B-03 Section 6, P-1 through P-3):
- P-1: Complete record
- P-2: Tamper-evident record
- P-3: Independent access (without operator cooperation)

### 3.1 The Audit Context as Inspection Surface

The Audit context is the discovered structure closest to Auditability ownership. It receives domain events from GovernanceState, Vote, and Verification as a passive observer. These events constitute the audit record.

```
Audit context — current scope:
  Receives: GovernanceTransitionCompleted, VoteRecorded,
            VerificationGranted, VerificationRevoked,
            GovernanceSuspended, GovernanceResumed
  Produces: audit log
  Authority: NONE — passive observer only (OBS-36A-08-1)
```

The Audit context provides P-2 (tamper-evident record — infrastructure from F2) and P-3 (independent access — the Audit context is not the operational Vote or Governance system).

**The Audit context is the CANDIDATE Auditability owner for the inspection surface.**

### 3.2 The Completeness Gap (P-1) — Unowned

Gap A-3 (from 36B-03 OBS-36B-03-1) is the irreducible completeness problem: the Audit context cannot confirm that it has received all events it should have received. It records what it observes. It has no mechanism to assert what it should have observed.

No discovered aggregate in NRNA currently holds P-1 (complete record). The owner of completeness is undiscovered.

```
Auditability ownership split:

  CANDIDATE OWNER (P-2, P-3):
    Audit context
    (tamper-evident observation + independent access)

  UNDISCOVERED OWNER (P-1):
    Completeness mechanism — no current candidate
    (expected-event registry or completeness invariant)

  This split is a structural gap, not an implementation detail.
  The Audit context cannot assert completeness by observing passively.
```

### 3.3 Auditability Ownership Summary

| Property | Owner | Status |
|----------|-------|--------|
| P-1 Complete record | Undiscovered | GAP — primary auditability risk |
| P-2 Tamper-evident record | Infrastructure (F2 layer) | Enablement, not ownership |
| P-3 Independent access | Audit context | CANDIDATE |

**Auditability is partially owned (inspection surface) and partially ungapped (completeness).**

---

## Section 4 — Verifiability Ownership (Q-V)

**The question:** Who in NRNA's discovered model holds the capability: "a claim can be verified"?

Verifiability was the primary subject of Round 36A. The findings carry forward binding.

### 4.1 Concern A Verifiability (Governance Claims)

The claim: "the governance configuration was established before the election and never modified during voting."

```
Owner: GovernanceState aggregate
  Holds: configuration freeze invariant (36A-DI-05 confirmed)
  Evidence: GovernanceTransitionCompleted events
  Status: PRESUMPTIVE OWNER (OBS-36A-08-2, five-source two-family)

  Verifiability of governance claims is exercised via
  GovernanceReplayService (ReplaySession candidate aggregate)
  — but replay is blocked by ADGR-1/D35-D37.
  The invariant ownership remains with GovernanceState.
```

### 4.2 Concern B Verifiability (Tally Claims — Deferred)

Tallied-as-Recorded is blocked by D39. No ownership assignment is possible.

```
Owner: UNDISCOVERED (D39 active)
  The tally ownership question must resolve before Concern B
  Verifiability can be owned by any NRNA structure.
```

### 4.3 Individual Verifiability (Recorded-as-Cast)

The claim: "this specific ballot was recorded as cast."

```
Owner: Vote aggregate (confirmed in 36A-08)
  Holds: individual ballot record + participation proof mechanism
  VO-1 applies: no voter identity in the claim or its evidence
  Status: CONFIRMED SCOPE
```

### 4.4 Verification Surface

The structure through which verification evidence is made accessible:

```
Owner: Verification Representation Context (C-02 resolved)
  Classification: Constitutional Enforcement Context
  Holds: verification surface — what is published for external inspection
  Does NOT own: verification decisions (those belong to Verification aggregate)
  Status: CANDIDATE CONTEXT (authorized for 36A-09, carrying into 36B)
```

### 4.5 Verifiability Ownership Summary

| Verifiability Type | Owner | Status |
|-------------------|-------|--------|
| Governance claims (Concern A) | GovernanceState | PRESUMPTIVE |
| Tally claims (Concern B) | Undiscovered | D39 BLOCKED |
| Individual ballot (Recorded-as-Cast) | Vote aggregate | CONFIRMED |
| Verification surface | Verification Representation Context | CANDIDATE |

**Verifiability is distributed across multiple owners. OBS-36B-05-0 is confirmed for this capability: Verifiability ownership is not singular.**

---

## Section 5 — Evidence Ownership (Q-E)

**The question:** Who produces and holds the evidence that a certifier would inspect?

Evidence in the Certification sense (36B-02) is the auditable record: the set of artifacts from which a certifier can assess whether criteria were satisfied.

### 5.1 Evidence Production

Evidence is produced by domain events from multiple sources:

```
GovernanceState → GovernanceTransitionCompleted (governance evidence)
Vote            → VoteRecorded (voting evidence, VO-1 applies)
Verification    → VerificationGranted, VerificationRevoked (trust evidence)
```

No single aggregate produces all evidence. Evidence production is distributed across every aggregate that acts.

### 5.2 Evidence Aggregation

The Audit context is the only discovered structure that receives evidence from multiple sources and holds it in one place. Under the current model, it is the evidence aggregation point.

```
Evidence aggregation CANDIDATE: Audit context

However:
  Audit context is a passive observer (OBS-36A-08-1).
  It aggregates what it receives.
  It cannot assert completeness (Gap A-3).
  It does not evaluate evidence against criteria.
  It does not make declarations.

The Audit context is a candidate evidence holder,
not a candidate certifier.
```

### 5.3 Evidence Ownership Summary

```
Evidence production: distributed (GovernanceState, Vote, Verification)
Evidence aggregation: Audit context (CANDIDATE)
Evidence completeness: undiscovered owner (Gap A-3)
Evidence integrity: F2 infrastructure layer (tamper-evidence)

The Audit context can hold evidence.
The Audit context cannot assure evidence completeness.
The Audit context cannot evaluate evidence.
The Audit context cannot certify.
```

---

## Section 6 — Certification Criteria Ownership (Q-K)

**The question:** Who in NRNA's model defines and holds the criteria against which evidence is evaluated for Certification?

### 6.1 What Certification Criteria Are

From 36B-02 (binding): Certification Criteria are the standards defined before the election against which evidence is evaluated. The certifier must have criteria to assess.

Examples of criteria a certification body might evaluate:
- Was governance configuration established before voting opened?
- Was the configuration immutable during voting?
- Were all participating voters enrolled through authorized means?
- Was the evidence record complete?

### 6.2 Configuration Criteria — GovernanceState

The governance configuration itself is a partial form of criteria. GovernanceState holds configuration and its freeze invariant. The "criteria" element here overlaps with the governance configuration — a certifier evaluating governance compliance would examine whether the configuration criteria were met.

```
Governance criteria (partial): GovernanceState (PRESUMPTIVE)
  The freeze invariant IS a criterion: configuration must not change during voting.
  GovernanceState owns this invariant.
  Whether a certifier can access this evidence is the Gap A-2 question.
```

### 6.3 Constitutional Criteria — Undiscovered

The broader constitutional criteria (what constitutes a valid NRNA election) are not held by any discovered aggregate. This is a question at the constitutional layer — above any individual aggregate.

```
Constitutional Criteria owner: UNDISCOVERED

This is a fundamental constitutional question:
  Who in NRNA defines what a valid election means?
  Is this a domain concept (a GovernanceState property)?
  Is this an external standard (imported from election law)?
  Is this an organizational constitution (NRNA's founding documents)?

The answer determines whether Criteria are a domain-owned concept
or an externally-defined standard.

This document cannot resolve this question.
It surfaces it for ARB and 36E.
```

### 6.4 Criteria Ownership Summary

| Criteria Type | Owner | Status |
|--------------|-------|--------|
| Configuration immutability | GovernanceState | PRESUMPTIVE |
| Enrollment authorization | Undiscovered (D43 active) | BLOCKED |
| Record completeness | Undiscovered (Gap A-3 active) | GAP |
| Constitutional validity | Undiscovered | CONSTITUTIONAL QUESTION |

---

## Section 7 — Certification Authority Ownership (Q-X)

**The question:** Who in NRNA's model holds the authority to declare that evidence satisfies criteria and that the election is accepted?

### 7.1 The Authority Gap

This is the most significant ownership gap found in the 36B research stream.

No discovered aggregate in NRNA holds Certification Authority.

```
GovernanceState:
  Holds authority over governance transitions.
  Does NOT hold external declaration authority.

Audit context:
  Passive observer (OBS-36A-08-1).
  Explicitly not an authority.

Verification Representation Context:
  Constitutional Enforcement Context.
  Holds invariants and policies.
  Does NOT make external declarations.

Vote aggregate:
  Records ballots.
  Does NOT declare election validity.

RoleAssignment:
  Manages trust status.
  Does NOT declare election validity.
  Blocked by ADC-1, ADC-2, ADH-1.

ReplaySession:
  Governance replay.
  Blocked by ADGR-1.
  Would provide evidence of governance correctness — not declaration authority.

No discovered aggregate: holds Certification Authority.
```

### 7.2 Why No Internal Aggregate Can Hold Certification Authority

Authority, in the constitutional sense, cannot be internally self-declared. An election cannot certify itself.

The independence requirement (P-5 from 36B-03 Section 6) states:

```
Certifier must be independent of operator.
```

If NRNA's operational system declares its own election valid, the declaration is not independent. The constitutional purpose of Certification Authority is that it comes from outside the operational system.

This is not an implementation constraint. It is a constitutional constraint.

```
Certification Authority is structurally external to NRNA's operational domain.

This finding does not mean NRNA cannot model anything about Certification.
It means the Authority element cannot be owned by any operational aggregate.

What NRNA can own:
  Evidence (Audit context — candidate)
  Criteria (partial — GovernanceState; constitutional criteria undiscovered)

What NRNA cannot own:
  The declaration itself (external actor)
```

### 7.3 Modeling Options for Certification Authority

These are candidate options for 36E — not decisions:

```
Option A: External actor only
  NRNA produces evidence and criteria.
  An external body (election commission, ARB, organizational governance board)
  makes the declaration outside the NRNA system.
  NRNA models the external actor as an upstream authority.
  No Certification subdomain in NRNA.

Option B: Certification Submission subdomain
  NRNA models the act of submitting evidence for certification.
  The submission is a domain concept; the declaration is external.
  A Certification Submission context handles the boundary.

Option C: Certification Record
  After an external authority certifies, NRNA records that certification
  as a domain fact (CertificationReceived event).
  The external declaration becomes a domain event.
  NRNA does not produce the declaration; it acknowledges it.

Context Explosion Risk applies (OBS-36B-03-C):
  Certifier is a role.
  Certification Authority is a role.
  Neither is automatically a bounded context.

Whether any of these options is needed depends on whether
NRNA's constitution requires Certification at all (Q1 from 36B-04).
The ARB must determine this before 36E can design for it.
```

---

## Section 8 — Certification Ownership: Full Assembly

Combining Q-C, Q-E, Q-K, Q-X:

| Certification Element | Owner | Status |
|----------------------|-------|--------|
| Evidence (record exists) | Audit context | CANDIDATE |
| Evidence completeness | Undiscovered | GAP (A-3) |
| Criteria: configuration | GovernanceState | PRESUMPTIVE |
| Criteria: enrollment | Undiscovered | D43 BLOCKED |
| Criteria: constitutional validity | Undiscovered | CONSTITUTIONAL QUESTION |
| Authority (declaration) | External actor | STRUCTURALLY EXTERNAL |

```
Certification in NRNA is currently:
  Two elements partially owned (Evidence, partial Criteria)
  One element structurally external (Authority)
  Multiple subelements undiscovered or blocked

Certification is not an ownership question for a single aggregate.
It is a multi-party constitutional question.
```

---

## Section 9 — Ownership Summary Matrix

| Capability | Owner | Gap / Blocker |
|------------|-------|---------------|
| Auditability — inspection surface | Audit context (CANDIDATE) | Gap A-2 (access pattern) |
| Auditability — completeness | Undiscovered | Gap A-3 (irreducible — 36B-03) |
| Verifiability — governance | GovernanceState (PRESUMPTIVE) | Gap A-3 affects evidence quality |
| Verifiability — individual ballot | Vote aggregate (CONFIRMED) | — |
| Verifiability — surface | Verification Representation Context (CANDIDATE) | C-02 resolution path |
| Verifiability — tally | Undiscovered | D39 BLOCKED |
| Evidence — production | Distributed (GovernanceState, Vote, Verification) | — |
| Evidence — aggregation | Audit context (CANDIDATE) | — |
| Evidence — completeness | Undiscovered | Gap A-3 |
| Criteria — configuration | GovernanceState (PRESUMPTIVE) | — |
| Criteria — enrollment | Undiscovered | D43 BLOCKED |
| Criteria — constitutional | Undiscovered | CONSTITUTIONAL QUESTION |
| Authority — declaration | External actor | STRUCTURALLY EXTERNAL |

**OBS-36B-05-0 confirmed:** Auditability, Verifiability, and Certification are not owned by the same structure. The ownership landscape is distributed, partially ungapped, and partially external.

---

## Section 10 — Architectural Impact Candidates for Round 36E

The following architectural impact candidates are produced by 36B-05 for evaluation in Round 36E. They are not design decisions. They are questions with architectural consequences that require impact assessment before ADR authoring.

**AIC-36B-01: Completeness Mechanism**

```
The completeness gap (Gap A-3) requires a mechanism not found in
any current NRNA structure: an expected-event registry or completeness
invariant that can assert "all events that should have been observed
have been received."

Architectural question: where does this mechanism live?
  Is it part of the Audit context (extending its scope beyond passive)?
  Is it a separate structure?
  Is it a GovernanceState invariant (GovernanceState knows what events to expect)?

This is one of the highest-priority unresolved structural questions in 36B.
Impact: HIGH. Gap A-3 is the primary auditability risk.
```

**AIC-36B-02: Certification Authority as External Actor**

```
Certification Authority is structurally external to NRNA's operational domain.

Architectural question: how does NRNA model the boundary with external authority?
  Option A: no model (external certification is entirely outside NRNA's scope)
  Option B: Certification Submission context (models the submission act)
  Option C: Certification Record (models the acknowledged declaration as a domain event)

Context Explosion Risk applies: do not create a context for a role.
Impact: HIGH if NRNA's constitution requires Certification. MEDIUM if not.
```

**AIC-36B-03: Constitutional Criteria Definition**

```
NRNA does not currently have a domain concept for "what constitutes a valid election."

This may be appropriate — constitutional criteria may be externally defined.
Or it may be a gap — NRNA may need to model its electoral constitution as a domain concept.

Architectural question: is "election validity criteria" a domain concept in NRNA?
  If yes: which aggregate holds it? GovernanceState extension? A new ElectionConstitution value object?
  If no: criteria are imported, not owned.

Impact: HIGH. Without criteria, Certification cannot be modeled even partially.
```

**AIC-36B-04: Audit Context Role Expansion**

```
The Audit context currently plays a passive observer role (OBS-36A-08-1).

36B-05 identifies it as the candidate owner for two things:
  Evidence aggregation (Q-E)
  Auditability inspection surface (Q-A, partial)

These roles are consistent with a passive observer.
But addressing Gap A-3 (completeness) may require expanding its role.

Architectural question: can the Audit context gain completeness responsibility
without violating its passive-observer constitutional role?
  If yes: it becomes an active completeness validator.
  If no: a separate structure must hold completeness.

This is a constitutional question about the Audit context's invariant.
Impact: HIGH. Determines whether Auditability can ever be fully owned.
```

**AIC-36B-05: D39 and Concern B Certification**

```
D39 blocks Concern B Verifiability and therefore Concern B Certification.

The candidate finding from 36B-04 (OBS-36B-04-3) suggests Concern A
Certification may not require D39 resolution — contingent on Gap A-3.

Architectural question: does NRNA need to choose one path now?
  Path A: Concern A Certification only (before D39) — requires Gap A-3 resolution
  Path B: Full Certification (after D39) — requires both Gap A-3 and D39 resolution
  Path C: No Certification (Auditability + Verifiability only)

This choice determines which structures 36E must assess.
Impact: HIGH. Shapes the entire post-36E design program.
```

---

## Section 11 — Open Constitutional Questions

These questions cannot be answered by ownership analysis. They require ARB constitutional determination.

**NRNA Constitutional Question NCQ-01:**
Does NRNA's constitutional mandate require Certification of elections, or only Auditability + Verifiability?

**NRNA Constitutional Question NCQ-02:**
Who in NRNA's organizational constitution holds Certification Authority? Is this an internal role (e.g., an ARB function) or an external authority?

**NRNA Constitutional Question NCQ-03:**
Are NRNA's election validity criteria a domain-owned concept or an externally-defined standard?

**NRNA Constitutional Question NCQ-04:**
Is Concern A Certification required before Concern B Certification is possible, or can NRNA operate in a state where governance is certified but tally is not (during D39 resolution)?

These questions are deferred to the ARB. They are not resolvable by literature analysis or domain discovery. They require constitutional determination.

---

## ARB Decision

```
Round 36B-05 — Ownership and Architectural Impact Candidates

SUBMITTED FOR ARB REVIEW

Central Findings:

  36B-CFI-01 (Separability — from 36B-04) is reinforced by 36B-05:
  Auditability, Verifiability, and Certification are not only
  separable in the literature — they are separately owned
  (or ungapped) in NRNA's discovered model.

  Auditability: Audit context (inspection — CANDIDATE)
                + undiscovered owner (completeness — Gap A-3)

  Verifiability: distributed across Vote, GovernanceState,
                 Verification Representation Context; D39 blocks Concern B

  Certification: partial (Audit — evidence candidate;
                 GovernanceState — configuration criteria);
                 external (Authority);
                 undiscovered (constitutional criteria, enrollment criteria)

  OBS-36B-05-0 confirmed: the three capabilities do not share an owner.

Primary Structural Gap:
  Gap A-3 (Completeness) is the binding constraint on both
  Auditability and Concern A Certification.
  No discovered structure resolves it.
  AIC-36B-01 (Completeness Mechanism) is the highest-priority
  impact candidate for 36E.

Constitutional Gap:
  Certification Authority is structurally external.
  Constitutional Criteria are undiscovered.
  NCQ-01 through NCQ-04 require ARB constitutional determination.

5 Architectural Impact Candidates produced (AIC-36B-01 through AIC-36B-05):
  For evaluation in Round 36E Architecture Impact Assessment.

4 Constitutional Questions produced (NCQ-01 through NCQ-04):
  For ARB determination before or during Round 36E.

Mandatory Instruction Compliance:
  No mechanisms discussed (no Merkle trees, bulletin boards, E2E-V systems).
  No new bounded contexts created.
  Ownership mapped to discovered domain structures only.
  OBS-36B-05-0 applied throughout.

Round 36B-05: APPROVED
Round 36B (Auditability Research): CLOSED — see Round36B-Closure_Summary.md
Round 36C (Threat Modeling Research): AUTHORIZED
Opening threat class: Evidence Suppression (Gap A-3)
```
