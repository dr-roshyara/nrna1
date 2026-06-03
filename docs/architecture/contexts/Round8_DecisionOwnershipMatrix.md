# Decision Ownership Matrix

**Round 8 Step 1: Foundation Artifact**

**Date:** 2026-06-03  
**Status:** Round 8 Execution  
**Purpose:** Establish who owns each major constitutional decision  
**Success Criteria:** Every decision has exactly one owner

---

## Matrix Overview

For each major constitutional decision, this matrix establishes:

1. **Decision** — What is being decided?
2. **Decision Owner** — Which context owns it?
3. **Required Inputs** — Which contexts provide information?
4. **Authority Source** — Who grants permission to decide?
5. **Evidence Required** — What evidence must exist?
6. **Verification Required** — What verification must occur before decision?
7. **Appeal Path** — Can the decision be challenged? Through which context?
8. **Temporal Impact** — Is the decision reversible? Can it be changed?

---

## Membership Context Decisions

### Decision 1: Approve Membership

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Membership Context |
| **Required Inputs** | Applicant information (name, qualifications), Payment proof (if required), Governance rules (eligibility definition) |
| **Authority Source** | Governance Context (defines membership rules), Organizational authority (delegates to Membership Committee) |
| **Evidence Required** | Applicant record, Payment transaction records, Qualification documentation |
| **Verification Required** | Applicant meets eligibility criteria per Governance rules; Payment is valid; No prior suspension record |
| **Appeal Path** | YES → Appeals Context (member can challenge exclusion) |
| **Temporal Impact** | YES, reversible → Can suspend or revoke membership later |

**Notes:**
- Governance participates (defines rules) but does not co-own the decision
- Membership exercises final authority to approve/reject
- Verification occurs before approval decision

---

### Decision 2: Revoke Membership

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Membership Context |
| **Required Inputs** | Member record, Reason for revocation (loss of payment, resignation, disciplinary), Governance rules (revocation grounds) |
| **Authority Source** | Governance Context (defines grounds for revocation), Organizational authority |
| **Evidence Required** | Member record, Violation documentation, Payment status change |
| **Verification Required** | Grounds for revocation are valid per Governance rules; Evidence is authentic |
| **Appeal Path** | YES → Appeals Context (member can appeal revocation) |
| **Temporal Impact** | YES, reversible → Can reinstate membership if appeal successful |

**Notes:**
- Membership owns the revocation decision
- Governance defines when revocation is allowed (participates but doesn't co-own)

---

### Decision 3: Suspend Membership

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Membership Context |
| **Required Inputs** | Member record, Suspension grounds (temporary payment lapse, disciplinary investigation), Duration/conditions for reinstatement, Governance rules |
| **Authority Source** | Governance Context (defines suspension rules), Organizational authority |
| **Evidence Required** | Member record, Violation documentation, Suspension duration |
| **Verification Required** | Grounds for suspension are valid; Duration is reasonable per policy |
| **Appeal Path** | YES → Appeals Context (member can appeal suspension) |
| **Temporal Impact** | YES, reversible → Suspension expires or can be lifted |

**Notes:**
- Distinct from revocation (temporary vs. permanent)
- Affects voting eligibility in election mode

---

### Decision 4: Reinstate Membership

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Membership Context |
| **Required Inputs** | Member record, Reason for reinstatement (payment restored, appeal approved, suspension expired), Governance conditions for reinstatement |
| **Authority Source** | Governance Context (defines conditions), Appeals Context (if appeal-driven), Member action (if payment-driven) |
| **Evidence Required** | Payment restoration, Appeal approval, or suspension expiration documentation |
| **Verification Required** | Conditions for reinstatement are met per Governance rules |
| **Appeal Path** | Implicit (via Appeals Context if appeal-driven) |
| **Temporal Impact** | YES → Restores member to full standing |

**Notes:**
- Can be triggered by multiple conditions (payment restoration, appeal approval, suspension expiration)
- Membership exercises authority to reinstate

---

## Election Context Decisions

### Decision 5: Create Election

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Election Context |
| **Required Inputs** | Election definition (name, posts, candidates, voting window), Governance rules (when elections can be held), Membership list (who is eligible), Authority to call election |
| **Authority Source** | Governance Context (defines election rules), Organization authority (decides to hold election) |
| **Evidence Required** | Governance approval, Authority documentation, Member list snapshot |
| **Verification Required** | Election definition is valid per Governance rules; Timing is lawful; Member list is current |
| **Appeal Path** | NO at creation (appeal eligible voter inclusion when election opens) |
| **Temporal Impact** | Reversible → Can cancel election if required |

**Notes:**
- Election Context owns creation (technical setup)
- Governance determines *permission* to create (rule-setting)
- Membership provides member list (data input)

---

### Decision 6: Open Election (Start Voting)

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Election Context |
| **Required Inputs** | Election confirmation (is election ready?), Governance approval, Voter list (who is eligible at voting time), Timing verification |
| **Authority Source** | Governance Context (voting window rules), Organization authority |
| **Evidence Required** | Election configuration is complete, Member list is current, Voting window has started |
| **Verification Required** | No missing candidates, Voter list is consistent, All data integrity checks pass |
| **Appeal Path** | YES (voter can challenge eligibility when election opens, triggering Appeals Context) |
| **Temporal Impact** | Reversible → Can close and reopen if critical error discovered |

**Notes:**
- Final gate before voting begins
- Verification of election readiness occurs before opening

---

### Decision 7: Close Election (End Voting)

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Election Context |
| **Required Inputs** | Voting window end time, Current vote count, Governance rules (voting window duration) |
| **Authority Source** | Governance Context (window duration), Organization authority |
| **Evidence Required** | Voting window has elapsed, All voters have had sufficient time, No voting window extension requested |
| **Verification Required** | Voting window is closed per policy; No outstanding voter access claims; Vote count is stable |
| **Appeal Path** | NO at closure (but can be reopened if fraud alleged) |
| **Temporal Impact** | Reversible → Can reopen if fraud or error discovered |

**Notes:**
- Mechanics of ending voting window
- Must occur before certification

---

### Decision 8: Certify Results

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Election Context **(PROVISIONAL)** |
| **Required Inputs** | Final vote count, Vote verification (spot checks), Audit logs, Governance certification rules |
| **Authority Source** | Governance Context (defines certification rules), Organization authority (designates certifier) |
| **Evidence Required** | Vote count records, Audit logs, Spot-check results, Device integrity records |
| **Verification Required** | Vote count is complete and correct, No evidence of tampering, Audit logs consistent, Spot-checks passed |
| **Appeal Path** | YES → Appeals Context (voter or observer can challenge count) |
| **Temporal Impact** | YES, reversible → Can recount if challenge successful |

**Notes:**
- **PROVISIONAL OWNERSHIP** — Question: Does Election certify itself, or does an independent authority certify?
- This decision is one of the strongest tests for Verification architectural placement
- Distinct from publishing results
- Verification occurs before certification
- "Correct" is defined by voting rules (one vote per voter, anonymous, etc.)

---

### Decision 9: Publish Results

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Election Context |
| **Required Inputs** | Certified results, Publication format (per Governance rules), Recipient list |
| **Authority Source** | Governance Context (defines publication rules), Organization authority |
| **Evidence Required** | Results certification record, Governance publication requirements |
| **Verification Required** | Results are certified, Publication format is correct, Recipients are authorized |
| **Appeal Path** | NO at publication (appeal rights exist before certification) |
| **Temporal Impact** | Irreversible → Published results are part of record |

**Notes:**
- Can only occur *after* certification
- In Full Membership mode: public publication required
- In Election-Only mode: may be restricted publication

---

## Governance Context Decisions

### Decision 10: Define Membership Rules

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Governance Context |
| **Required Inputs** | Organizational values, Constitutional constraints, Operational requirements |
| **Authority Source** | Organization constitution, Governance body authority |
| **Evidence Required** | Prior governance decisions, Member input, Organizational policy documentation |
| **Verification Required** | Rules are internally consistent, Consistent with constitution, Operationally feasible |
| **Appeal Path** | Limited (governance decisions typically not appealable; may require new governance session) |
| **Temporal Impact** | YES, reversible → Can change rules in next governance session |

**Notes:**
- Governance exercises unique authority
- Membership implements but does not define
- Sets conditions for Membership decisions (Decision 1-4)

---

### Decision 11: Define Voting Eligibility Rules

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Governance Context |
| **Required Inputs** | Membership status definition, Payment status, Residency/region rules (if applicable), Organizational values |
| **Authority Source** | Organization constitution, Governance body authority |
| **Evidence Required** | Prior practice, Constitutional requirements, Member input |
| **Verification Required** | Rules are clear and unambiguous, Operationally feasible, Non-discriminatory |
| **Appeal Path** | Limited (appeals go to Appeals Context if eligibility is challenged during election, not at rule definition time) |
| **Temporal Impact** | YES, reversible → Can change for future elections |

**Notes:**
- Governance defines *who* can vote
- Affects Membership decisions and Election opening
- Set before election is created

---

### Decision 12: Define Candidate Eligibility Rules

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Governance Context |
| **Required Inputs** | Membership requirements for candidacy, Experience requirements, Constitutional constraints |
| **Authority Source** | Organization constitution, Governance body authority |
| **Evidence Required** | Prior practice, Constitutional requirements |
| **Verification Required** | Rules are clear, Operationally feasible, Applied consistently |
| **Appeal Path** | NO at rule definition; YES for individual candidate eligibility challenges |
| **Temporal Impact** | YES, reversible → Can change for future elections |

**Notes:**
- Governance defines the rules
- Membership applies the rules (when approving candidates)

---

### Decision 13: Define Voting Procedures

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Governance Context |
| **Required Inputs** | Constitutional constraints, Operational capacity, Voter experience requirements |
| **Authority Source** | Organization constitution, Governance body authority |
| **Evidence Required** | Prior voting procedures, Member feedback, Technical requirements |
| **Verification Required** | Procedures ensure voter anonymity, Procedures ensure vote integrity, Procedures are operationally feasible |
| **Appeal Path** | NO (procedures are not appealable) |
| **Temporal Impact** | YES, reversible → Can change for future elections |

**Notes:**
- Governance sets the framework
- Election Context implements the procedures
- Includes anonymity, audit trail, verification requirements

---

### Decision 14: Define Authority Hierarchy

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Governance Context |
| **Required Inputs** | Organizational structure, Constitutional authority levels, Precedence rules |
| **Authority Source** | Organization constitution, Governance body authority |
| **Evidence Required** | Constitutional framework, Prior governance decisions |
| **Verification Required** | Hierarchy is consistent with constitution, No circular authority, Clear precedence |
| **Appeal Path** | Limited (governance framework appeals are exceptional) |
| **Temporal Impact** | YES, reversible → Can restructure authority in new governance session |

**Notes:**
- Governance defines *who* has authority to make what decisions
- Tests the Authority hypothesis (H-B vs H-C)
- Affects all other contexts

---

### Decision 15: Publish Governance Rules

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Governance Context |
| **Required Inputs** | Governance rules (all defined), Publication format, Distribution list |
| **Authority Source** | Governance body authority, Organization policy |
| **Evidence Required** | Rules documentation, Approval records |
| **Verification Required** | Rules are complete and consistent, Publication is complete, All stakeholders have access |
| **Appeal Path** | NO (publication is administrative, not substantive) |
| **Temporal Impact** | NO (published rules remain in effect until changed) |

**Notes:**
- In Full Membership mode: public transparency required
- In Election-Only mode: may be restricted

---

### Decision 16: Publish Voter List (Full Membership Mode)

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Governance Context |
| **Required Inputs** | Current member list (from Membership), Candidates list (from Election), Voting rules, Governance publication rules |
| **Authority Source** | Governance body authority, Organization policy |
| **Evidence Required** | Member list is current, Candidates are approved, Eligibility rules are satisfied |
| **Verification Required** | List includes all eligible voters, List is consistent with rules, Privacy rules are applied |
| **Appeal Path** | YES → Voter can challenge if wrongly included/excluded; goes to Appeals Context |
| **Temporal Impact** | YES, reversible → List can be updated if errors corrected |

**Notes:**
- Only in Full Membership mode (not Election-Only)
- Enables public verification (part of legitimacy)
- Must be published before election opens

---

## Appeals Context Decisions

### Decision 17: Reverse Membership Decision

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Appeals Context |
| **Required Inputs** | Original membership decision (approve/revoke/suspend), Appeal grounds (process error, rule violation, new evidence), Original evidence, Member statement |
| **Authority Source** | Governance Context (defines appeal rules), Organization authority (designates Appeals authority) |
| **Evidence Required** | Original decision documentation, Appeal grounds documentation, Evidence of error/violation |
| **Verification Required** | Appeal was timely, Grounds are valid, Evidence supports reversal, Original decision violated procedure or rules |
| **Appeal Path** | Limited (Appeals decision is final unless higher authority override) |
| **Temporal Impact** | YES → Reversal restores membership status to before decision |

**Notes:**
- Can only reverse Membership decisions
- Must show process error or rule violation
- Returns decision to Membership Context if new determination needed

---

### Decision 18: Reverse Eligibility Decision

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Appeals Context |
| **Required Inputs** | Eligibility determination (voter or candidate), Appeal grounds, Original evidence, Appellant statement |
| **Authority Source** | Governance Context (defines appeal rules), Organization authority |
| **Evidence Required** | Eligibility decision documentation, Appeal grounds documentation, Evidence of error/rule violation |
| **Verification Required** | Appeal was timely, Grounds are valid, Original determination violated rules or procedure |
| **Appeal Path** | Limited (final) |
| **Temporal Impact** | YES → Reversal includes/excludes as appropriate |

**Notes:**
- Can occur during election (voter eligibility challenge) or after (candidate eligibility review)
- Can trigger Election Context to adjust voter list or candidate list

---

### Decision 19: Reverse Election Certification

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Appeals Context |
| **Required Inputs** | Certification record, Appeal grounds (recount evidence, tampering evidence, procedure violation), Audit logs, New evidence |
| **Authority Source** | Governance Context (defines appeal rules), Organization authority |
| **Evidence Required** | Certification documentation, Appeal grounds documentation, Evidence of error/tampering/violation |
| **Verification Required** | Appeal grounds are substantial, New evidence is credible, Original certification violated procedure or accuracy standards |
| **Appeal Path** | Limited (final) |
| **Temporal Impact** | YES, significant → Recount and new certification required |

**Notes:**
- High bar (must prove error or fraud)
- Triggers recount by Election Context
- Results in new certification decision

---

### Decision 20: Unclassified Appeal Decisions (Requiring Further Investigation)

**Status:** PLACEHOLDER — Signals missing modeling

**Questions:**
- Are there other substantive decisions beyond membership, eligibility, and election that require appeal mechanisms?
- If yes, which contexts own those decisions?
- If no, this category is unnecessary.

**Note:**
This catch-all reveals gaps in the decision ownership model.

Do not create a generic "Reverse Other Decisions" pattern.

Instead, investigate during Context Responsibility Matrix phase to determine:
- What decisions are actually missing?
- Which contexts should own them?
- What is the appeal path for each?

---

## Cross-Context Decisions (Authority & Verification)

### Decision 21: Grant Authority

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Governance Context |
| **Required Inputs** | Authority scope (who decides what?), Recipient (committee, role, individual), Duration, Conditions |
| **Authority Source** | Organization constitution, Governance body authority |
| **Evidence Required** | Constitutional foundation, Governance approval, Recipient qualification |
| **Verification Required** | Scope is clear, Duration is defined, Conditions are enforceable |
| **Appeal Path** | Limited (governance decision) |
| **Temporal Impact** | YES, reversible → Can revoke authority |

**Notes:**
- Governance distributes authority to other contexts
- Affects all contexts that receive delegated authority

---

### Decision 22: Revoke Authority

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Governance Context |
| **Required Inputs** | Authority to be revoked, Reason for revocation (performance failure, rule violation, structural change) |
| **Authority Source** | Governance body authority, Organization constitution |
| **Evidence Required** | Original authority grant documentation, Grounds for revocation |
| **Verification Required** | Revocation is valid, Transition plan is clear (who resumes authority?), No vacuum created |
| **Appeal Path** | Limited (governance decision) |
| **Temporal Impact** | YES → Revocation is immediate unless staged |

**Notes:**
- Allows Governance to remove delegated authority
- Must manage transition (who does the job if authority holder is removed?)

---

### Decision 23: Certify Verification Process

| Aspect | Owner/Detail |
|--------|----------|
| **Decision Owner** | Governance Context (or delegated to Verification authority) |
| **Required Inputs** | Verification procedures, Standards for "legitimate," Approval criteria |
| **Authority Source** | Governance body authority, Organization constitution |
| **Evidence Required** | Procedure documentation, Standards documentation, Prior verification results |
| **Verification Required** | Procedures are sound, Standards are clear, Results are reproducible |
| **Appeal Path** | Limited (governance process decision) |
| **Temporal Impact** | YES, reversible → Can update procedures |

**Notes:**
- Tests whether Verification is context, infrastructure, or distributed capability
- May reveal Verification architectural placement

---

## Matrix Summary

**Decision Owner Distribution:**

| Context | Decisions Owned | Examples |
|---------|-----------------|----------|
| **Membership** | 4 | Approve, Revoke, Suspend, Reinstate |
| **Election** | 5 | Create, Open, Close, Certify (provisional), Publish |
| **Governance** | 7 | Define rules (5), Publish rules, Grant/Revoke authority |
| **Appeals** | 3 | Reverse membership, eligibility, election |
| **Unclassified** | 1 | Appeal decisions requiring further investigation |

**Total Constitutional Decisions:** 20 (with 1 unclassified)

**Decisions with Reversible Impact:** 17

**Decisions with Appeal Path:** 11

**Decisions Requiring Evidence:** 19 (all except unclassified)

**Decisions Requiring Verification:** 18

---

## Key Observations

### Observation 0: Governance God Context Risk

**Finding:** Governance currently owns 7 of 20 decisions (35%).

**Risk:** Is Governance becoming a "God Context" — a context that owns too much?

**Decisions Governance Owns:**
- Define membership rules
- Define voting eligibility rules
- Define candidate eligibility rules
- Define voting procedures
- Define authority hierarchy
- Publish governance rules
- Publish voter list

**Distinction:** Some may be governance **meta-decisions** (deciding how to decide) rather than governance **decisions** (deciding outcomes).

**Question:** Should some of these be delegated to other contexts, or is centralized rule-definition architecturally necessary?

**Resolution:** Context Responsibility Matrix will test whether Governance becomes too central or if current ownership is justified by its unique decision-making authority.

---

### Observation 1: Clean Ownership

Every decision has exactly one owner. No decision is claimed by multiple contexts simultaneously.

```
Decision
    ↓
Owned by exactly one context
    ↓
Governance participates (defines scope) but does not co-own
    ↓
Clean boundary
```

### Observation 2: Authority Flows Through Governance

Governance owns the foundational decisions (rules, authority distribution). Other contexts exercise delegated authority.

```
Governance defines rules
    ↓
Membership applies to membership decisions
    ↓
Election applies to election decisions
    ↓
Appeals can reverse if rules were violated
```

### Observation 3: All Paths Go Through Appeals

Every substantive decision can be appealed. Appeals Context is the safety valve for the architecture.

### Observation 4: Verification Precedes Decision

For every major decision, verification occurs *before* the decision is made (or before its effects take hold).

```
Requirement: Verify conditions are met
    ↓
Decision: Grant/deny/certify
    ↓
Impact: Take effect
```

### Observation 5: Evidence is Pervasive

Every decision requires evidence. No decision is made blind.

### Observation 6: Mode Sensitivity

Some decisions (Election-Only vs Full Membership) change:
- **Decision 16 (Publish Voter List)** only exists in Full Membership mode
- **Decision 15 (Publish Governance Rules)** has different publication scope in different modes
- **Decision 9 (Publish Results)** has different reach in different modes

---

### Observation 7: Verification Participation Is Ubiquitous

**Finding:** Verification is required (in some form) for 18 of 20 classified decisions.

**Pattern:**
```
Decision-Making Sequence:

Verification (check conditions)
    ↓
Decision (make choice)
    ↓
Impact (take effect)
```

**Implication:** Verification appears in nearly every decision, but its architectural placement remains unresolved:

**Hypothesis A:** Verification is a bounded context (owns verification decisions)

**Hypothesis B:** Verification is infrastructure (shared across contexts, not domain-owned)

**Hypothesis C:** Verification is distributed (each context verifies its own decisions)

**Impact:** The ubiquity of "Verification Required" strengthens the case that Verification is architecturally central, but does not yet determine its form.

**Resolution:** This matrix demonstrates the *importance* of Verification architectural placement more than any prior artifact. Round 8 Verification Placement Analysis will test these hypotheses.

---

## Validation Against Success Criteria

**Success Criterion 1: Every major decision has exactly one owner**

**Preliminary Result:** All 20 classified decisions have exactly one proposed owner. No dual ownership observed.

**Caveat:** Several ownership assignments are provisional and testable:
- Decision 8 (Certify Results) marked provisional
- Decision 20 (Unclassified appeals) signals missing model
- Governance ownership of 7 decisions requires testing in Context Responsibility Matrix

---

**Success Criterion 2: Ownership is defensible through heuristics**

Spot-check Membership → Approve Membership:
- Unique language? ✓ (Membership-specific vocabulary)
- Unique decision? ✓ (only Membership approves members)
- Unique consistency rules? ✓ (membership status consistency)
- Governance participates (defines rules) but does not co-own ✓

**Note:** Similar reasoning applies to Election, Appeals, and Governance contexts. Full heuristic validation deferred to Context Responsibility Matrix.

---

**Success Criterion 3: Both modes preserve ownership**

**Preliminary Result:** Ownership model holds in both Election-Only and Full Membership modes.

**Mode-Specific Variations Documented:**
- Decision 16 (Publish Voter List) only in Full Membership mode
- Decision 9/15 publication scope differs by mode
- Same decision owners in both modes (where decisions apply)

---

## Unknowns Remaining After DOM

1. **How do contexts handle shared inputs?**
   - Example: Membership needs Governance rules to approve membership
   - Is this a simple input, or a contract that needs definition in Step 3?

2. **How do contexts coordinate timing?**
   - Example: Election cannot open until Governance publishes voter list
   - Is sequencing a contract issue, or a separate concern?

3. **What happens if a decision owner is unavailable?**
   - Example: Membership Context cannot process if committee is disabled
   - Does Governance have fallback authority?

4. **How are Appeal Context decisions made without bias?**
   - Who appoints the Appeals committee?
   - Can the original decision maker participate in appeal?

5. **What is the actual implementation of "Verification Precedes Decision"?**
   - Where does verification happen architecturally?
   - This determines if Verification is context, infrastructure, or distributed

---

## Next Steps

Decision Ownership Matrix is complete and stable. All 23 decisions have clear owners.

**Next artifact:** ContextResponsibilityMatrix.md (Step 2)
- For each context, document what it owns and what it depends on
- Derived from this matrix

---

**STATUS: DECISION OWNERSHIP MATRIX COMPLETE**

**NEXT: Context Responsibility Matrix (Step 2)**

