# Literature Research Phasing Plan

**Date:** 2026-06-08

**Authority:** Senior DDD Mentor / ARB Chair

**Status:** AUTHORITATIVE GOVERNANCE DECISION

**Purpose:** Record when and how external literature may be integrated into the NRNA design program. This document governs literature use across all subsequent rounds.

---

## Core Governance Principle

```
Discovery determines existence.

Design determines structure.

Literature provides options.

Technology provides implementation.
```

Literature research must not precede domain structure stabilization. Deep literature integration before aggregate boundaries are finalized will contaminate decision ownership, context boundaries, and invariants.

---

## Current Program Position

```
Discovery          ✓ COMPLETE
Assessment         ✓ COMPLETE
Governance         ✓ COMPLETE
Design Governance  ✓ COMPLETE
Aggregate Design   ← CURRENT (Round 33)
```

**Literature research beyond DDD/governance theory is NOT authorized during Round 33.**

---

## Phase A — Round 33: Aggregate Design (Current)

**Allowed Literature:**
- Domain-Driven Design (Vernon, Evans)
- Aggregate Design patterns
- Institutional Governance theory (Ostrom)
- Constitutional governance theory

**Reason:** These inform HOW to design aggregates. They do not redesign the discovered domain.

**Prohibited Literature (Until Phase B):**
- ElectionGuard
- Helios
- Scantegrity
- Prêt à Voter
- Risk Limiting Audits
- Coercion Resistance literature
- Social Choice Theory

**Why Prohibited Now:** Deep integration of election system literature before aggregate boundaries are stable will pull aggregate boundaries, decision ownership, and context boundaries away from what was discovered.

---

## Phase B — Rounds 34–35: Domain Events and Commands

**Trigger:** Aggregate boundaries approved by ARB.

**Allowed Literature (Structured Research):**
- ElectionGuard (question: how do mature systems model vote lifecycle events?)
- Helios (question: how is end-to-end verifiability expressed in domain events?)
- Scantegrity (question: how are receipt systems modeled?)
- Prêt à Voter (question: how are ballot events structured?)

**Research Question for Phase B:**
```
How do mature election systems model vote lifecycle events?
```

NOT:
```
Should we copy their architecture?
```

Literature findings in Phase B inform domain event design. They do not override discovered aggregates.

---

## Phase C — Round 36: Trustworthiness Research Program

**Trigger:** Domain events and commands approved by ARB.

**Purpose:** Systematic evaluation of trustworthiness literature against the already-discovered NRNA domain.

### Round 36A — Verifiability Research

**Primary Sources:**
- ElectionGuard: Cast as Intended / Recorded as Cast / Tallied as Recorded
- Helios: End-to-end verifiability, cryptographic receipts
- Scantegrity: Receipt systems, verification mechanisms

**Research Question:**
```
How do established election systems model verifiability?
```

**Note:** This is the primary input for **D42B** resolution.

---

### Round 36B — Auditability Research

**Primary Sources:**
- Risk Limiting Audits: Evidence validation, statistical confidence
- Ballot-level audit literature

**Research Question:**
```
What auditability properties should the NRNA domain expose?
```

---

### Round 36C — Threat Modeling Research

**Primary Sources:**
- Election attack literature
- Governance attack literature (authority abuse, legitimacy manipulation)
- Security engineering for constitutional systems

**Research Question:**
```
What attack surfaces does the constitutional governance model expose?
```

---

### Round 36D — Election Literature Review

**Primary Sources:**
- Broader election system literature
- Comparative constitutional voting literature

**Research Question:**
```
What domain concepts do mature systems use that NRNA has not yet discovered?
```

---

## Coercion Resistance — Deferred Until Constitutional Justification

Coercion resistance literature should NOT be studied until a constitutional requirement exists.

**Entry Criterion:**
```
The NRNA Constitution must state:
"Members must be protected against vote coercion."
```

**Then and only then study:**
- Civitas
- Benaloh challenge
- Juels fake credentials
- Revoting and ballot replacement schemes

Until that constitutional justification exists: **research only, no design.**

---

## Social Choice Theory — Deferred Until Results/Tallying Ownership Resolved

Social Choice Theory (Condorcet, Borda, IRV, STV, Approval Voting, D'Hondt) answers:

```
How are votes counted?
```

But the domain is still resolving:

```
Who owns counting? (D39 unresolved)
```

**Rule:** Ownership must be decided before algorithm. Social choice literature must not influence design until Results/Tallying decision ownership is established.

---

## Round 34A Governance Rule — Domain Event Discovery

**BINDING ARB INSTRUCTION:**

```
Events must be discovered from aggregate behavior.
Events must NOT be invented to satisfy integration needs.
```

**Wrong approach:**
```
Need a Kafka topic
    ↓
Create Event
```

**Correct approach:**
```
Business fact occurred
    ↓
Domain Event exists
```

Round 34A must produce only events that represent genuine business facts observed within approved aggregate lifecycles. Events named after persistence operations (VoteSaved, VerificationRowInserted, GovernanceUpdated) are NOT domain events.

For reference, plausible domain event candidates per approved aggregate:
- Verification: VerificationGranted, VerificationRevoked (only if they are genuine business facts)
- Vote: VoteRecorded, VoteRejected (only if they are genuine business facts)
- GovernanceState: GovernanceActivated, GovernanceSuspended, GovernanceTransitionCompleted (subject to governance debt constraints)

These are examples only. Round 34A must discover what events actually exist, not confirm a pre-decided list.

---

## Recommended Design Roadmap

```
Round 33    Aggregate Boundary Design              ✓ APPROVED

Round 34    Domain Event and Command Discovery
    34A     Domain Event Discovery
    34B     Command Discovery
    34C     Aggregate Interaction Analysis

Round 35    Command and Process Design

Round 36    Trustworthiness Research Program
    36A     Verifiability Research
    36B     Auditability Research
    36C     Threat Modeling Research
    36D     Election Literature Review

Round 37    D42B Resolution (using Round 36A findings)

Round 38    Trust Architecture ADRs

Round 39    Technical Architecture
```

**Why Round 34 Is Split (ARB Instruction):**
Domain events belong to the domain. Commands belong to application behavior. These are not the same thing and must not be designed as one activity.

**Key Ordering Rule:**
```
Literature Research (Round 36)
    comes BEFORE
Trust Architecture ADRs (Round 38)
    but AFTER
Aggregate Design (Round 33)
```

---

## Why This Ordering Matters

If literature is integrated before aggregate design is stable:

```
ElectionGuard → influences Vote aggregate boundary
Helios → influences Verification context boundary
Civitas → introduces coercion-resistance context before constitutional basis
STV → pulls Results/Tallying toward algorithm before ownership is established
```

Each of these violations would require unwinding decisions that were made on evidence (Rounds 17-29) in favor of decisions made on external literature.

The governance principle established throughout Rounds 17-32 must hold:

```
Discovery findings are authoritative.

Literature may influence design.

Literature may not rewrite discovery.
```

---

## Governance Authority

This phasing plan is binding on all subsequent design rounds.

Deviation from this plan requires:
1. ADR documenting the proposed deviation
2. Explicit justification for early literature integration
3. ARB approval

