# Round 17 — Step 2 Investigation Plan

**Date:** 2026-06-07

**Status:** Ready for Execution

**Authority:** Architecture Review Board

---

## Purpose

This document describes how Step 2 will execute the approved Discovery Charter. It organizes investigation into focused streams, each with clear questions, evidence sources, collection methods, and expected findings.

This plan does not design architecture.

This plan investigates domain relationships.

---

## Investigation Organization

Six investigation streams, each organized as:

```
Question
    ↓
Evidence Sources
    ↓
Collection Method
    ↓
Expected Findings
    ↓
Open Questions
    ↓
Hypothesis Tracking
```

---

## Investigation Stream 1: Evidence Analysis

### Question

Why does Evidence recur across six independent discovery paths? What role does Evidence play in the system? What architectural interpretations remain possible?

### Evidence Sources

**Tier 1 (Observed Behavior):**
- Vote table structure and data persistence
- Result table updates (real-time vs batched)
- State machine audit trail generation
- Suspension justification capture
- Code table (voter identity separation)

**Tier 2 (Implementation Evidence):**
- Vote recording controller logic
- Result calculation code
- Audit trail persistence code
- State transition enforcement code
- Code validation logic

**Tier 3 (Documentation Evidence):**
- Architecture documentation (ADRs, design docs)
- Trust Requirements Workbook
- Trust Model Synthesis
- State Machine Analysis
- Governance Evidence Extraction

**Tier 4 (Interpretation Evidence):**
- Architect observations about Evidence role
- Workshop discussions about verification
- Design rationale documents

### Collection Method

1. **Code Analysis**
   - Trace vote table schema and usage
   - Trace result table calculation logic
   - Identify all evidence-producing operations
   - Map evidence flow through system

2. **Architecture Document Review**
   - Extract all references to Evidence, verification, audit, proof
   - Identify architectural assumptions about Evidence
   - Document Evidence mechanisms explicitly designed

3. **Scenario Analysis**
   - Walkthrough: How voter verifies their vote was recorded
   - Walkthrough: How election committee verifies results
   - Walkthrough: How officer justifies suspension decision
   - Document evidence requirements for each scenario

4. **Comparative Analysis**
   - Compare Evidence mechanisms in Voting vs Governance vs Audit
   - Identify common Evidence patterns
   - Identify Evidence variations by context

### Possible Outcomes

- Strong evidence of unified Evidence concept
- Strong evidence of distributed Evidence concerns
- Mixed evidence (unified in some areas, distributed in others)
- Insufficient evidence to determine Evidence structure
- Discovery of previously unknown Evidence-related concern

### Open Questions

- Is Evidence a single unified domain concept?
- Is Evidence distributed across multiple concerns?
- Is Evidence a capability used by multiple contexts?
- Is Evidence a bounded context?
- Is Evidence a cross-cutting concern?
- Is Evidence a shared kernel?
- Is Evidence something else?

### Hypothesis Tracking

Hypotheses to track:
- H1: Evidence is a governance concern
- H2: Evidence is a capability shared across contexts
- H3: Evidence is a bounded context
- H4: Evidence serves distinct purposes in different contexts

---

## Investigation Stream 2: Governance & Authority Relationship Analysis

### Question

How do Governance and Authority concepts relate to each other? Are they separate domains or aspects of the same domain? How do they relate to other recurring concepts?

### Evidence Sources

**Tier 1 (Observed Behavior):**
- Officer action authorization (who can do what)
- State transition constraints
- Suspension authority and process
- Permission model enforcement

**Tier 2 (Implementation Evidence):**
- ConstitutionalTransitionGuard logic
- Role-based access control implementation
- Permission model code
- Authority validation code

**Tier 3 (Documentation Evidence):**
- State Machine Analysis (14 constitutional actions)
- Governance Evidence Extraction
- Election Policy documents
- Trust Model Synthesis (authority relationships)

**Tier 4 (Interpretation Evidence):**
- Architect observations about authority separation
- Discussion notes about governance vs authority distinction

### Collection Method

1. **State Machine Analysis**
   - Document all officers (Chief, Deputy, Commissioner, Platform Admin)
   - Document all actions each officer can perform
   - Document preconditions for each action
   - Identify authority patterns (exclusive, delegable, constrained)

2. **Constitutional Rule Extraction**
   - Identify rules originating from constitutional structure
   - Identify rules originating from officer authority
   - Identify rules originating from election procedures
   - Identify rules originating from organizational practice

3. **Relationship Mapping**
   - How Governance constrains Authority
   - How Authority exercises Governance
   - Whether Governance and Authority are unified or separate
   - How Authority relates to Trust and Legitimacy
   - What observable consequences follow from each interpretation

4. **Gap Analysis**
   - What governance rules are enforced?
   - What governance rules are documented but not enforced?
   - What governance rules are assumed but not documented?

### Possible Outcomes

- Strong evidence of separation between Governance and Authority
- Strong evidence of unified Governance/Authority domain
- Mixed evidence of separation and unification
- Constitutional rules clearly distinct from operational rules
- Constitutional rules integrated with operational rules
- Ambiguous boundary between constitutional and operational

### Open Questions

- Are Governance and Authority unified or separate?
- Do constitutional rules form their own domain?
- What governs the governors?
- Where does legitimacy come from?

### Hypothesis Tracking

Hypotheses to track:
- H4: Constitutional rules form unified domain
- H5: Authority is constrained by Governance
- H6: Governance is exercised through Authority

---

## Investigation Stream 3: Voting/Tallying Boundary Analysis

### Question

Are vote recording and result projection separate concerns or tightly coupled aspects of a single concern? What architectural implications follow from each interpretation?

### Evidence Sources

**Tier 1 (Observed Behavior):**
- Vote recording persistence
- Result table real-time updates
- Partial results visibility control
- Vote verification capability
- Result recalculation capability

**Tier 2 (Implementation Evidence):**
- Vote controller logic
- Result calculation code
- Vote table schema
- Result table schema
- Real-time update mechanism

**Tier 3 (Documentation Evidence):**
- State Machine Analysis (voting and counting states)
- Trust Requirements Workbook (vote integrity, inclusion, correctness)
- Candidate Reassessment (Vote Tallying uncertainty)

**Tier 4 (Interpretation Evidence):**
- Architect notes on real-time coupling
- Design decision rationale

### Collection Method

1. **Data Flow Trace**
   - How votes flow from voter selection to result table
   - When result table is updated
   - Who can access results at each stage
   - Dependencies between vote recording and result calculation

2. **Timing Analysis**
   - Is result calculation triggered by vote submission?
   - Is result calculation batched or continuous?
   - Can results be recalculated from vote table?
   - What is the relationship between vote count and result update?

3. **Operational Workflow Analysis**
   - Is there a distinct "counting" workflow?
   - Is counting automated or manual?
   - Who initiates counting?
   - What triggers transition to "counting" state?

4. **Boundary Identification**
   - Can vote recording exist without result projection?
   - Can result projection exist without live vote data?
   - What operational differences follow from coupling vs separation?

### Possible Outcomes

- Strong evidence of computational coupling between vote recording and result projection
- Strong evidence of distinct operational concerns
- Mixed evidence of coupling and separation
- Real-time coupling serves specific operational purpose
- Real-time coupling is implementation convenience
- Operational coupling with conceptual separation

### Open Questions

- Are these separate concerns or unified?
- Does coupling serve a purpose or is it an implementation choice?
- What would separation require?
- What does coupling prevent?

### Hypothesis Tracking

Hypotheses to track:
- H7: Voting and Tallying are operationally coupled
- H8: Voting and Tallying are distinct concerns
- H9: Real-time coupling prevents certain attacks

---

## Investigation Stream 4: Audit Clarification

### Question

Is Audit a single unified concern or two separate concerns? Does the system contain operational logging and governance replay, and are they distinct or unified?

### Evidence Sources

**Tier 1 (Observed Behavior):**
- Officer action tracking (who did what)
- Vote recording tracking
- State transition recording
- Suspension justification recording
- Audit trail persistence

**Tier 2 (Implementation Evidence):**
- Audit logging code
- State transition logging
- Suspension justification schema
- Action tracking implementation

**Tier 3 (Documentation Evidence):**
- State Machine Analysis (suspension records)
- Trust Model Synthesis (audit trail)
- Candidate Reassessment (Audit ambiguity)

**Tier 4 (Interpretation Evidence):**
- Architect notes on replay vs audit
- Design discussions

### Collection Method

1. **Logging Type Analysis**
   - Identify operational logging (voter/officer actions)
   - Identify governance logging (decisions and justifications)
   - Identify state transition logging
   - Compare logging mechanisms and purposes

2. **Usage Pattern Analysis**
   - Who accesses operational logs?
   - Who accesses governance records?
   - What questions does each type answer?
   - Are they accessed together or separately?

3. **Schema Analysis**
   - What data is captured in operational logs?
   - What data is captured in governance records?
   - Are they in same table or separate tables?
   - What relationships exist between them?

4. **Concern Separation Analysis**
   - Could operational logging exist without governance replay?
   - Could governance replay exist without operational logging?
   - Are they unified for implementation convenience or functional unity?

### Possible Outcomes

- Strong evidence of two distinct audit concerns (operational and governance)
- Strong evidence of unified audit trail
- Mixed evidence of distinction and unification
- Different data captured by each type
- Different access patterns and purposes
- Ambiguous boundary between operational and governance audit

### Open Questions

- Are these one concern or two?
- If two, are they related or independent?
- What would separation require?
- Does unification serve a purpose?

### Hypothesis Tracking

Hypotheses to track:
- H10: Operational logging and governance replay are unified
- H11: Operational logging and governance replay are distinct concerns

---

## Investigation Stream 5: Constitutional Rule Discovery

### Question

What governance rules originate from constitutional structure vs organizational practice? Is there a unified Constitutional Rule System or are governance rules distributed?

### Evidence Sources

**Tier 1 (Observed Behavior):**
- Officer actions that are prevented vs allowed
- State transitions that are enforced vs permitted
- Preconditions that must be satisfied
- Authority that is concentrated vs delegated

**Tier 2 (Implementation Evidence):**
- ElectionConstitution.php RULES
- ConstitutionalTransitionGuard logic
- Permission model code
- Precondition enforcement code

**Tier 3 (Documentation Evidence):**
- Election Policy documents
- Architecture documents
- State Machine Analysis
- Governance Evidence Extraction

**Tier 4 (Interpretation Evidence):**
- Architect notes on constitutional design
- Design rationale documents

### Collection Method

1. **Rule Classification**
   - Identify rules that must always apply (constitutional)
   - Identify rules that can be overridden (operational)
   - Identify rules that come from law vs organization
   - Classify by source

2. **Rule Enforcement Analysis**
   - Which rules are enforced by code?
   - Which rules are documented but not enforced?
   - Which rules are assumed but not documented?
   - What happens when rules conflict?

3. **Constitutional Structure Analysis**
   - Do constitutional rules form a unified system?
   - Or are they scattered across multiple concerns?
   - What is the relationship between constitutional and operational rules?
   - Can governance continue if constitutional rules are violated?

4. **Authority Chain Analysis**
   - Who can change rules?
   - Can officers override constitutional rules?
   - Who governs the governors?
   - What constraints apply to all authority?

### Possible Outcomes

- Strong evidence of distinct constitutional rules separate from operational rules
- Strong evidence of unified governance rules undifferentiated from operational rules
- Mixed evidence (distinct in some areas, unified in others)
- Clear hierarchy of rule precedence and authority
- Ambiguous or unclear governance structure
- Discovery of rule categories not previously identified

### Open Questions

- Is Constitutional Rule System unified or distributed?
- What makes a rule constitutional vs operational?
- Can authority exist when legitimacy is disputed?
- Who enforces rules that apply to rule-makers?

### Hypothesis Tracking

Hypotheses to track:
- H4: Constitutional rules form unified domain (continued)
- H12: Constitutional rules are separate from operational rules
- H13: Constitutional rules protect governance from officer overreach

---

## Investigation Stream 6: Dispute & Challenge Discovery

### Question

How do elections get contested? Who challenges results? What evidence is required? How is legitimacy restored after a challenge?

### Evidence Sources

**Tier 1 (Observed Behavior):**
- Challenge mechanisms (if any exist in code)
- Dispute resolution processes
- Appeals procedures
- Conflict resolution workflows

**Tier 2 (Implementation Evidence):**
- Code that handles challenges
- Controllers for appeals
- Dispute resolution logic
- Legitimacy restoration procedures

**Tier 3 (Documentation Evidence):**
- Election Policy documents (challenge sections)
- Business requirements (dispute handling)
- Design documents (if any)
- User guides (if dispute procedures exist)

**Tier 4 (Interpretation Evidence):**
- Architect notes on dispute handling
- Workshop discussions about challenges
- Stakeholder feedback on dispute scenarios

### Collection Method

1. **Challenge Scenario Analysis**
   - What disputes could arise?
   - How would a candidate challenge a result?
   - How would a voter challenge a result?
   - How would an officer challenge a decision?
   - What evidence would be required?

2. **Resolution Process Analysis**
   - Who decides if a challenge is valid?
   - What evidence is required to overturn a result?
   - How are disputes resolved?
   - Who has authority to resolve disputes?

3. **Legitimacy Restoration Analysis**
   - How is legitimacy restored after a challenge?
   - What evidence proves the challenge was fair?
   - How are disputed results handled?
   - Can results be modified after publication?

4. **Gap Identification**
   - What challenge mechanisms are missing?
   - What dispute authorities are undefined?
   - What evidence standards are unclear?
   - What legitimacy restoration processes are absent?

### Possible Outcomes

- Strong evidence of explicit challenge and dispute mechanisms
- Strong evidence of absence of challenge procedures
- Partial documentation of challenge procedures
- Clear authority for dispute resolution
- Ambiguous or unclear dispute authority
- Evidence standards for challenges clearly defined
- Evidence standards unstated or unclear
- Discovery of conflict resolution mechanisms not previously identified

### Open Questions

- How are elections challenged in practice?
- What authority decides disputes?
- What evidence is required for proof?
- How is legitimacy restored?
- Can challenge authority itself be challenged?

### Hypothesis Tracking

Hypotheses to track:
- H14: Challenge authority exists but not documented
- H15: Challenge authority is missing from system design
- H16: Legitimacy is restored through documented evidence

---

## Evidence Classification & Quality

All findings will classify evidence by tier:

```
Tier 1: Observed Behavior (Highest confidence)
Tier 2: Implementation Evidence (Strong)
Tier 3: Documentation Evidence (Useful)
Tier 4: Interpretation Evidence (Lowest confidence)
```

Higher tiers outweigh lower tiers in case of conflict.

---

## Hypothesis Tracking

All hypotheses discovered during investigation will be tracked in:

**Round17_Hypothesis_Register.md**

with Evidence For / Evidence Against / Status

---

## Assumption Validation

All foundational assumptions will be tracked in:

**Round17_Assumption_Register.md**

with Risk Assessment / Validation Method

---

## Checkpoints & Review

Investigation proceeds through phases. Duration is determined by evidence sufficiency, not calendar.

### Phase 1: Evidence Gathering
- Collect evidence from all six investigation streams
- Classify evidence by tier (1-4)
- Update hypothesis register with evidence for/against
- Document sources and evidence classification

### Phase 2: Synthesis
- Synthesize findings by investigation stream
- Identify patterns and relationships across streams
- Update hypothesis status based on accumulated evidence
- Identify conflicting or ambiguous evidence

### Phase 3: ARB Checkpoint Review
- Present interim findings to ARB
- Validate investigation approach and evidence collection
- Confirm continued relevance of investigation streams
- Adjust scope or focus if needed

### Phase 4: Deep Investigation
- Investigate hypothesis conflicts
- Strengthen supporting evidence for remaining open hypotheses
- Resolve weakening hypotheses toward rejection or acceptance
- Answer open questions from earlier phases

### Phase 5: Final Synthesis
- Consolidate all findings across six streams
- Finalize hypothesis status (Open / Strengthening / Weakening / Rejected / Inconclusive)
- Document assumptions validated or flagged
- Prepare comprehensive findings for ARB review

### Phase 6: ARB Review
- Present Step 2 findings to ARB
- Answer investigation questions with evidence-based conclusions
- Recommend subsequent discovery activities
- Receive ARB decision on next phase

---

## Success Criteria

Step 2 succeeds when:

✅ All six investigation streams produce findings

✅ Findings cite evidence classified by tier

✅ All hypotheses tracked with evidence for/against

✅ All assumptions validated or flagged

✅ Outstanding questions documented

✅ Architectural possibilities remain open

✅ No premature architectural conclusions made

---

## Constraints

Step 2 does NOT:

❌ Design bounded contexts

❌ Design aggregates

❌ Select technologies

❌ Make architectural decisions

❌ Conclude that Evidence is a context

❌ Conclude that Governance is separate from Authority

❌ Conclude anything that closes investigation space

---

## Next Steps After Investigation

1. Investigation complete
2. Hypothesis register finalized
3. Findings documented
4. ARB review of Step 2 findings
5. ARB decision on subsequent discovery

Not implementation.

Not bounded context discovery.

Not strategic design.

Only what ARB authorizes next.

---

**Status: Ready for Execution**

**Next Artifact: Round17_Hypothesis_Register.md**

