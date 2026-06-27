# Round 17 — Step 2 Discovery Charter

**Date:** 2026-06-07

**Status:** Approved for Execution (pending ARB charter review)

**Authority:** Architecture Review Board

---

## 1. Charter Purpose

Step 2 exists to investigate relationships between candidate contexts, uncertainties identified during Round 16 reassessment, and recurring domain concepts that emerged across multiple independent discovery paths.

### What Step 2 Is

✅ A discovery activity investigating domain relationships and boundaries

✅ An investigation of five recurring concepts (Governance, Authority, Trust, Legitimacy, Evidence)

✅ An investigation of five candidate signal uncertainties

✅ A mapping of context relationships and dependencies

### What Step 2 Is NOT

❌ Step 2 is not bounded context design

❌ Step 2 is not aggregate design

❌ Step 2 is not entity or service design

❌ Step 2 is not solution architecture

❌ Step 2 does not authorize implementation

---

## 2. Inputs

Step 2 discovery builds on and remains consistent with:

1. **Round16_Step1C_Candidate_Reassessment.md**
   - Six candidate signal assessments
   - Five recurring domain concepts
   - Outstanding questions from reassessment

2. **Round16_ARB_Step1C_Review_Package.md**
   - Consolidated Round 16 findings
   - Trust, authority, evidence, legitimacy mechanisms discovered

3. **Round16_ARB_Decision_Record.md**
   - Five ARB strategic decisions
   - Investigation objectives and scope
   - Required outcomes

4. **Round17_ARB_Decision_Validation_Review.md**
   - Validation that ARB decisions remain governance decisions
   - Confirmation that discovery freedom is preserved
   - Approval for charter execution

Step 2 must remain aligned with all four input artifacts.

---

## 3. Authorized Objectives

Step 2 investigation focuses on three authorized objectives:

### Primary Objective: Context Relationship Mapping

Investigate how the six candidate contexts relate to each other.

**Investigation targets:**
- Do contexts overlap?
- Do contexts depend on each other?
- Are context boundaries clear or ambiguous?
- Do recurring concepts span multiple contexts?

### Secondary Objective: Evidence Investigation

Investigate the nature, role, and significance of Evidence as a recurring concept.

**Investigation targets:**
- Why does Evidence recur across multiple discovery paths?
- What role does Evidence play in the system?
- How do different candidates use or produce evidence?

### Targeted Objective: Dispute & Legitimacy Discovery

Investigate mechanisms for contesting elections and restoring legitimacy.

**Investigation targets:**
- How are election decisions challenged?
- Who has authority to challenge?
- What evidence is required to substantiate a challenge?
- How is legitimacy restored after a challenge?

Step 2 must not add objectives beyond these three.

---

## 3.5 Discovery Maturity

**Current Phase:** Late Strategic Discovery

Step 2 is not authorized to proceed to:

❌ Strategic Design (context definition)

❌ Bounded Context Discovery (aggregate/entity identification)

❌ Tactical Design (service and command design)

❌ Solution Architecture (technology selection)

❌ Implementation Planning (code-level decisions)

Step 2 remains exclusively in the discovery phase.

---

## 4. Investigation Scope

### Candidate Contexts Under Investigation

- Election Administration
- Governance & Authority
- Voting
- Audit
- Vote Tallying
- Voter Registration

**Important:** Investigation of these candidates does not imply they will become bounded contexts. Discovery determines the appropriate boundaries.

### Recurring Concepts Under Investigation

- Governance (domain concept)
- Authority (domain concept)
- Trust (cross-cutting concern)
- Legitimacy (domain concept)
- Evidence (strategic concern, architectural nature undetermined)

**Important:** Investigation of these concepts does not predetermine their architectural nature. Discovery determines whether they are contexts, capabilities, concerns, shared kernels, or other patterns.

### Candidate Uncertainties Under Investigation

- **Vote Tallying Independence** (HIGH): Relationship between vote recording, result projection, and counting workflow
- **Audit Ambiguity** (HIGH): Whether operational logging and governance replay represent one concern or two separate concerns
- **Voting Boundary** (HIGH): Real-time coupling between vote recording and result projection
- **Governance Problem-Space** (MEDIUM): Limited problem-space evidence relative to solution-space evidence
- **Registration Limitations** (MEDIUM): Missing evidence on voter eligibility determination rules

**Important:** Investigation of these uncertainties does not mandate their resolution. Discovery determines the actual relationships and boundaries.

---

## 5. Discovery Questions

### For Evidence

- Why does Evidence recur across six independent discovery paths?
- What role does Evidence play within Voting, Governance, Audit, and other candidates?
- Is Evidence a domain concept owned by a specific context?
- Is Evidence a shared capability used by multiple contexts?
- Is Evidence a bounded context in its own right?
- Is Evidence a cross-cutting concern spanning multiple contexts?
- Is Evidence a shared kernel between contexts?
- Is Evidence something else not yet categorized?

**No answer is predetermined. Discovery determines the evidence interpretation.**

### For Governance, Authority, Trust, and Legitimacy

- How do Governance and Authority relate to Election Administration?
- How do Governance and Authority relate to each other?
- Is Trust a cross-cutting concern or a domain concept?
- Does Trust belong to a specific context or span multiple contexts?
- How does Legitimacy relate to Governance, Authority, Evidence, and Trust?
- Are Legitimacy and Governance separate concerns or aspects of the same concern?

**No answer is predetermined. Discovery determines actual relationships.**

### For Dispute Resolution

- How do organizations actually challenge election decisions?
- Who has authority to initiate a challenge?
- Who evaluates the challenge?
- What evidence is required to substantiate a challenge?
- How is a challenge resolved?
- Does challenge authority already exist in the system?
- Or does challenge authority represent a missing domain concept?
- How is legitimacy restored after a dispute?

**No resolution authority is assumed. Discovery determines actual mechanisms.**

### For Constitutional Rule Discovery

- What governance rules originate from constitutional structure?
- Which governance rules originate from officer authority?
- Which governance rules originate from election procedures?
- Which governance rules originate from organizational practice?
- Can elections continue to operate if constitutional rules are violated?
- Can authority continue when legitimacy is disputed?
- Do constitutional rules protect governance from officer overreach?
- Are constitutional rules themselves subject to dispute?

**Rationale:** Throughout Round 16, Governance, Authority, Trust, Legitimacy, and Evidence emerged as recurring concepts. Investigation should explore whether these cluster around a unified "Constitutional Rule System" or represent separate concerns.

**No conclusion is predetermined.** Discovery determines whether constitutional rules form a unified domain or whether governance is distributed across multiple concerns.

---

## 6. Discovery Methods

Step 2 may use any appropriate discovery methods:

✅ Repository document analysis (README, ADRs, design docs, migration guides)

✅ Code analysis (state machine, database schema, controller logic)

✅ Event flow analysis (how events trigger state changes)

✅ Scenario analysis (walkthroughs of contested elections, governance decisions)

✅ Domain workshops (if organizational stakeholders are available)

✅ Stakeholder interviews (if needed to understand problem-space governance)

✅ Comparative analysis (how similar domains handle similar concerns)

✅ Other methods appropriate to the investigation

No single method is prescribed. Discovery team selects methods appropriate to each investigation target.

---

## 7. Required Outcomes

Step 2 must produce the following outcomes:

### Outcome 1: Candidate Relationship Map

**Requirement:** Visual and textual documentation of how candidate domains relate.

**Content:**
- Candidate boundaries (clarified or ambiguous)
- Candidate dependencies and overlaps
- Recurring concepts and which candidates involve them
- Open questions about boundaries

**Not required:** Architectural design. Only investigation findings.

**Important:** Investigation of relationships does not determine whether candidates are bounded contexts. That determination belongs to future strategic design activities.

### Outcome 2: Evidence Role Determination

**Requirement:** Analysis of Evidence's role and architectural significance.

**Content:**
- Evidence's appearances across candidates
- Functions Evidence serves in the system
- Possible architectural interpretations
- Evidence supporting each interpretation
- Outstanding questions about Evidence's role

**Important:** All architectural outcomes remain possible:
- Bounded context
- Shared capability
- Cross-cutting concern
- Shared kernel
- Vocabulary artifact
- Other interpretations

**No outcome is predetermined.**

### Outcome 3: Voting/Tallying Boundary Clarification

**Requirement:** Investigation of whether vote recording and result projection are separate concerns or coupled concerns.

**Content:**
- Evidence for separate concerns
- Evidence for coupled concerns
- Observed consequences if separate
- Observed consequences if coupled
- Outstanding questions

**Not required:** Architectural decision. Only clarification of the actual relationship.

### Outcome 4: Audit Clarification

**Requirement:** Investigation of whether Audit is one concern or two separate concerns.

**Content:**
- Operational logging (voter/officer action tracking)
- Governance replay (vote/state verification and archaeology)
- Whether these are separate or unified
- Observed consequences of each interpretation
- Outstanding questions

**Not required:** Architectural decision. Only clarification of the concern structure.

### Outcome 5: Dispute & Challenge Understanding

**Requirement:** Investigation of mechanisms for contesting elections and resolving disputes.

**Content:**
- Discovered challenge mechanisms (if any)
- Challenge authorities and their decision processes
- Evidence standards for substantiating challenges
- Legitimacy restoration mechanisms
- Missing mechanisms identified in Round 16
- Whether challenge represents a missing domain concern

**Not required:** Dispute resolution design. Only investigation findings.

### Outcome 6: Governance Problem-Space Validation

**Requirement:** Investigation of how organizations actually experience governance constraints.

**Content:**
- Problem-space governance evidence
- How governance rules are applied in practice
- Gaps between solution-space (architecture) and problem-space (organizational experience)
- Organizational governance workflows
- Officer decision-making processes

**Purpose:** Supplement Round 16's solution-space governance evidence with problem-space evidence.

### Outcome 7: Recommendation for Subsequent Discovery

**Requirement:** Recommendation regarding what should be investigated next.

**Content:**
- Unresolved candidates from Step 2 investigation
- Unresolved concepts (Governance, Authority, Trust, Legitimacy, Evidence)
- Unresolved relationships
- Recommended priorities for future discovery
- Recommendation regarding whether architectural decisions are ready

**Not required:** Architectural design or decisions. Only discovery recommendations.

---

## 8. Constraints

Step 2 discovery is explicitly forbidden from:

❌ Creating bounded contexts

❌ Confirming bounded contexts exist

❌ Rejecting bounded contexts

❌ Merging candidates

❌ Splitting candidates

❌ Designing aggregates

❌ Designing entities

❌ Designing services

❌ Designing APIs

❌ Selecting technologies

❌ Selecting databases

❌ Selecting cryptographic mechanisms

❌ Creating implementation plans

❌ Recommending specific architectural patterns

❌ Making architectural decisions

These activities require authorized follow-on activities (bounded context discovery, strategic design, implementation planning). Step 2 does not authorize them.

---

## 9. Success Criteria

Step 2 succeeds when:

✅ Investigation questions are answered with evidence from the repository

✅ Candidate relationships are documented with supporting evidence

✅ Candidate uncertainties are clarified with findings

✅ Recurring concepts are analyzed for their role and significance

✅ Previously unknown governance questions are identified

✅ Remaining unknowns are documented for future investigation

✅ Findings are prepared for Architecture Review Board review

✅ Recommendations for subsequent discovery are provided

**Note:** In strategic discovery, discovering better questions is as valuable as answering known questions. Step 2 succeeds when it surfaces previously unknown governance concerns that inform future discovery priorities.

**Step 2 does NOT succeed when:**

❌ Architectural patterns are selected

❌ Contexts are designed

❌ Implementation plans are created

❌ Technology decisions are made

Success is measured by investigation depth and evidence quality, not by architectural conclusions.

---

## 10. Governance Chain

```
Step 2 Investigation
        ↓
Step 2 Findings Complete
        ↓
Findings Submitted to Architecture Review Board
        ↓
ARB Review of Step 2 Findings
        ↓
ARB Decision on Subsequent Discovery Activities
        ↓
Authorization for Next Phase (if approved)
        ↓
Next Discovery or Design Activity (if authorized)
```

Step 2 does not authorize:
- Implementation activities
- Bounded context discovery (requires ARB decision)
- Strategic design (requires ARB decision)
- Any architectural work (requires ARB decision)

---

## Critical Governance Rule

**This charter defines what Step 2 investigates.**

**This charter does NOT embed preferred architectural answers.**

Evidence is important to the organization.

Evidence may or may not become a bounded context.

Evidence may be a shared capability.

Evidence may be a cross-cutting concern.

Evidence may be a vocabulary artifact.

Evidence may be something else entirely.

**Discovery determines the answer.**

This keeps Step 2 aligned with the governance discipline established in Round 16:

```
Discovery discovers what exists
        ↓
ARB decides what matters
        ↓
Discovery investigates what matters
        ↓
ARB evaluates discovery findings
        ↓
Architecture begins after ARB review
```

That separation between discovery, governance, and architecture is what prevents premature architectural conclusions and preserves the integrity of future design decisions.

---

## Charter Status

**Approved by:** Architecture Review Board (pending)

**Date:** 2026-06-07

**Next Step:** ARB Charter Review

**Condition for Execution:** ARB approval of this charter confirms:
- Charter aligns with five ARB strategic decisions
- Charter preserves discovery freedom
- Charter contains no hidden architectural commitments
- Investigation scope is appropriate

After ARB charter approval, Step 2 investigation may begin.

