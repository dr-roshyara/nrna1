# Round 8 — Context Architecture Mandate

**Date:** 2026-06-03  
**Status:** AUTHORIZED (Outcome B — Conditional)  
**Authority:** ARB Decision Session (Round7_ARB_Decision_Session.md)  
**Focus:** Decision Ownership as Centerpiece

---

## Mission

Round 8 is Strategic Architecture (not Discovery, not Tactical Design).

The purpose of Round 8 is to design **Context Architecture** based on **decision ownership**.

Round 8 is NOT:
- Discovery
- Tactical DDD
- Aggregate design
- Entity design
- Database design
- API design
- Implementation design

Round 8 focuses exclusively on:
- **Decision ownership** (who decides what?)
- Context relationships (how do they interact?)
- Context contracts (what are the agreements?)
- Context responsibilities (what does each own?)
- Authority flows (who grants permission?)
- Verification flows (who verifies what?)
- Evidence flows (who needs what?)
- Mode validation (both Election-Only and Full Membership)

---

## Governance Conditions (Must Acknowledge)

### Condition 1: Verification Architectural Role

**Status:** Unresolved (three options viable)
- Option A: Verification as bounded context
- Option B: Verification as infrastructure layer
- Option C: Verification as distributed capability

**Round 8 Decision:** Must either validate one option OR document why multiple remain viable.

---

### Condition 2: Authority Hypothesis

**Assumption:** Round 8 proceeds with **H-C (Cross-Cutting Authority)** as provisional working hypothesis.

**Alternative:** H-B (Authority Family) remains viable.

**Round 8 Responsibility:** Actively test whether H-C survives decision ownership modeling. If H-C fails, be explicit about it.

---

### Condition 3: Evidence Status

**Assumption:** Round 8 treats Evidence as **shared infrastructure**.

**Contradiction:** Phase 2 classified Evidence as "constitutional pillar."

**Round 8 Responsibility:** Record any contradictions discovered. Will be revisited if architectural experience contradicts.

---

## The Central Insight

> **Contexts are discovered through decision ownership, not through data modeling.**

In Strategic DDD:

```text
Question: "What are the entities in this context?"
Answer: Uncertain; depends on implementation

Question: "Who makes this decision?"
Answer: Clear; belongs to exactly one context
```

Clean context boundaries produce **clean decision ownership**.

If a decision has multiple owners → boundary is wrong.
If a decision requires multiple contexts to jointly own the final outcome → the boundary may be wrong and should be investigated.
If a decision can only be made by committee → check whether one context should own it while others participate.

---

## The Nine Steps (Execution Order)

**Execution Order:** Start with Decision Ownership Matrix. All other artifacts are derived from it.

```
Decision Ownership Matrix
      ↓
Context Responsibility Matrix
      ↓
Context Relationship Map
      ↓
Context Contracts
      ↓
Authority Flow Analysis
      ↓
Verification Placement Analysis
      ↓
Evidence Flow Analysis
      ↓
Election Mode Validation
      ↓
Round 8 Synthesis
```

---

### Step 1 — Decision Ownership Matrix (CENTERPIECE)

For every major constitutional decision, identify:

**Decision:** What is being decided?

**Decision Owner:** Which context owns it?

**Required Inputs:** Which contexts provide information?

**Authority Source:** Who grants permission for the decision?

**Evidence Required:** What evidence must exist?

**Verification Required:** What verification must occur?

**Appeal Path:** Can the decision be challenged?

**Temporal Impact:** Can the decision change later?

**Major Constitutional Decisions to Map:**

**Membership Context owns:**
- Approve membership
- Revoke membership
- Suspend membership
- Reinstate membership

**Election Context owns:**
- Create election
- Open election
- Close election
- Publish results

**Governance Context owns:**
- Define membership rules
- Define voting eligibility rules
- Define candidate eligibility rules
- Define voting procedures
- Publish voter list (Full Membership mode)

**Appeals Context owns:**
- Reverse membership decision
- Reverse eligibility decision
- Reverse count verification
- Reverse election results

**Authority decisions:**
- Grant authority
- Revoke authority
- Delegate authority

**Verification decisions:**
- Approve verification process
- Certify count
- Certify eligibility

**Success Criteria:** Every major constitutional decision has exactly one decision owner. If multiple owners appear, the boundary is likely wrong.

**Deliverable:** DecisionOwnershipMatrix.md

---

### Step 2 — Context Responsibility Matrix

For every context identify:

**Owns:** What decisions belong exclusively here?
**Consumes:** What decisions from others are required?
**Produces:** What outcomes are emitted?
**Cannot Decide:** What is outside its authority?

**Deliverable:** ContextResponsibilityMatrix.md

---

### Step 3 — Context Relationship Map

For Membership, Election, Governance, Appeals, identify:

**Upstream:** Who provides information?
**Downstream:** Who consumes information?
**Partnership:** Which contexts collaborate?
**Shared Concepts:** Which concepts cross boundaries?

**Deliverable:** ContextRelationshipMap_v1.md

---

### Step 4 — Context Contracts

For each relationship (Membership ↔ Election, Election ↔ Governance, etc.):

**Expectations:** What does each context expect from the other?
**Inputs:** What information flows in?
**Outputs:** What information flows out?
**Invariants:** What must always be true?
**Failure Conditions:** What breaks the contract?

**Deliverable:** ContextContracts_v1.md

---

### Step 5 — Authority Flow Analysis

Using H-C assumption (or H-B alternative):

Map authority across decision lifecycle:
- Authority Creation
- Authority Delegation
- Authority Verification
- Authority Challenge
- Authority Revocation

Evaluate: Does H-C survive? If not, document why.

**Deliverable:** AuthorityFlowAnalysis.md

---

### Step 6 — Verification Placement Analysis

Evaluate three options:

**Option A: Verification as Bounded Context**
- Directly addresses "constitutional pillar" designation
- Clear ownership
- Weak heuristic scores (no unique decisions)

**Option B: Verification as Infrastructure Layer**
- Centralizes verification logic
- Reduces context count
- Infrastructure layer becomes complex

**Option C: Verification as Distributed Capability**
- Each context owns its own legitimacy determination
- Minimal boundary pressure
- Requires consistency discipline

Compare: complexity, ownership, consistency, governance alignment, election mode support.

Do NOT select winner yet. Document tradeoffs.

**Deliverable:** VerificationPlacementAnalysis.md

---

### Step 7 — Evidence Flow Analysis

Map evidence across lifecycle:
- Evidence Creation
- Evidence Storage
- Evidence Access
- Evidence Verification
- Evidence Retention

Evaluate: Does Evidence behave as infrastructure, pillar, or both?

Document findings.

**Deliverable:** EvidenceFlowAnalysis.md

---

### Step 8 — Election Mode Validation

Validate entire architecture against both modes:

**Election-Only Mode:**
- Do context boundaries survive?
- Does decision ownership remain clean?
- Does authority model survive?
- Does verification model survive?

**Full Membership Mode:**
- Do context boundaries survive?
- Does decision ownership remain clean?
- Does public transparency change anything?
- Does distributed authority change anything?

Document differences and mode-specific requirements.

**Deliverable:** ElectionModeValidation.md

---

### Step 9 — Round 8 Synthesis

Synthesize all findings into context architecture v1:

- Context boundaries finalized (pending governance review)
- Decision ownership matrix validated
- Context relationships established
- Context contracts defined
- Authority model tested (H-C or H-B)
- Verification placement analyzed
- Evidence role clarified
- Both modes validated

**Deliverable:** Round8_ContextArchitecture_v1.md

---

## Success Criteria for Round 8

Round 8 succeeds if it produces:

✓ Context relationships explicit
✓ Context contracts explicit
✓ Context responsibilities explicit
✓ **Decision ownership matrix (every major decision has one owner)**
✓ Authority flows tested (H-C or alternative)
✓ Verification options compared (not yet chosen)
✓ Evidence role clarified (infrastructure vs pillar vs both)
✓ Both modes validated (Election-Only and Full Membership)
✓ All assumptions documented
✓ All unknowns listed
✓ All architectural risks identified

Round 8 does NOT require:
- Tactical DDD
- Aggregates designed
- Repositories designed
- APIs specified
- Database schemas
- Implementation details

---

## Round 8 Governance Review Questions

After Round 8 Context Architecture v1 is complete:

**Question 1:** Can every major constitutional decision be assigned to exactly one owner?
- If YES: boundaries are clean and ready for tactical design
- If NO: which decisions are ambiguous? Those signal boundary problems

**Question 2:** Do context contracts support that decision ownership?
- Can the owner access all required inputs?
- Can the owner provide required outputs?
- Can the owner accept appeals?

**Question 3:** Do authority and evidence flows support decision ownership?
- Can the owner verify authority to decide?
- Can the owner gather required evidence?
- Can the owner provide evidence to others?

**Question 4:** Do both modes (Election-Only and Full Membership) preserve clean ownership?
- If ownership changes between modes: is that acceptable?
- If ownership is the same: are both modes truly supported?

---

## Exit Criteria for Round 8

Before Round 9 Tactical DDD can begin:

✓ Context Architecture v1 documented
✓ Decision Ownership Matrix complete
✓ All eight artifacts produced
✓ Round 8 Governance Review completed
✓ Governance approval obtained
✓ Remaining assumptions documented
✓ Architectural risks identified

---

## The Centerpiece: Decision Ownership

Round 8 is centered on one question:

**WHO DECIDES WHAT?**

Not:
- What entities exist?
- What data flows?
- What APIs should exist?
- How are aggregates structured?

The answer to "WHO DECIDES WHAT?" determines:
- Context boundaries
- Context relationships
- Context contracts
- Authority flows
- Verification requirements
- Evidence needs
- Appeal paths
- Tactical design in Round 9

---

## Post-Round 8 Roadmap

```
Round 8 Context Architecture
  ↓
Context Relationship Map
Context Responsibility Matrix
Decision Ownership Matrix
Context Contracts
Authority Flow Analysis
Verification Placement Analysis
Evidence Flow Analysis
Election Mode Validation
  ↓
Round 8 Governance Review
  ↓
Round 9 Tactical DDD
  (Aggregate Design)
  (Repository Design)
  (Value Object Design)
  (Domain Event Design)
```

---

## Key Principles for Round 8

1. **Decision ownership comes first** — context boundaries are discovered through this question
2. **Every decision has one owner** — if not, the boundary is wrong
3. **Both modes must preserve ownership** — context boundaries survive mode changes
4. **Assumptions are explicit** — H-C, Evidence-as-infrastructure, Verification role are all provisional and documented
5. **No tactical decisions yet** — Round 8 is architecture only

---

**STATUS: ROUND 8 MANDATE FINALIZED**

**CENTERPIECE: DECISION OWNERSHIP MATRIX**

**NEXT STEP: BEGIN ROUND 8 CONTEXT ARCHITECTURE**

