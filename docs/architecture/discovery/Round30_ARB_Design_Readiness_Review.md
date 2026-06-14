# Round 30 — ARB Design Readiness Review

**Date:** 2026-06-08

**Phase:** Governance Readiness Assessment (Pre-Design Authorization)

**Status:** CONDITIONAL APPROVAL — 7 Corrections Required Before Filing

**Purpose:** Determine whether the discovered domain model is mature enough for design activities. Classify remaining unresolved items. Establish which debts must be resolved before design, and which can be resolved during or after design.

---

## Context

**Discovery Program Status:**
- ✅ Rounds 17–29 Complete
- ✅ Repository Discovery Approved
- ✅ Bounded Context Discovery Approved
- ✅ Aggregate Discovery Approved
- ✅ Strategic-to-Tactical Synthesis Approved

**Current Model State:**
- 9 Bounded Contexts (5 stable, 4 provisional)
- 5 Aggregates (3 stable, 2 provisional)
- 17 Domain Invariants (12 discovered, 5 provisional)
- 8 Core Business Decisions with clear ownership

**Remaining Unresolved Items:** 9 open questions

---

## Unresolved Items Classification

### Governance Debt (Authority & Decision Scope)

**D35: What Happens When Legitimacy = EXPIRED?**

| Classification | Assessment |
|---|---|
| **Current Status** | UNRESOLVED |
| **Impact Zone** | Arbitration, Replay, Governance |
| **Decision Required** | Is legitimacy consequence corrective, preventative, or advisory? |
| **Blocks Design** | YES — affects arbitration aggregate boundary |
| **Recommended Classification** | **MUST RESOLVE BEFORE DESIGN** |
| **Rationale** | Determines whether Arbitration aggregate can be designed with confidence |

---

**D36: Who Is Permitted to Invoke ConstitutionalArbitrationKernel?**

| Classification | Assessment |
|---|---|
| **Current Status** | UNRESOLVED |
| **Impact Zone** | Arbitration, Challenge Handling |
| **Decision Required** | Is invocation distributed, organizational, or automatic? |
| **Blocks Design** | YES — affects arbitration aggregate boundary |
| **Recommended Classification** | **MUST RESOLVE BEFORE DESIGN** |
| **Rationale** | Determines authority model for decision review |

---

**D37: How Is Legitimacy Determination Enforced?**

| Classification | Assessment |
|---|---|
| **Current Status** | UNRESOLVED |
| **Impact Zone** | Arbitration, Governance, Authority |
| **Decision Required** | Is enforcement automatic, officer-triggered, or advisory? |
| **Blocks Design** | YES — affects how legitimacy status influences operational decisions |
| **Recommended Classification** | **MUST RESOLVE BEFORE DESIGN** |
| **Rationale** | Enforcement model is prerequisite for aggregate and service design |

---

**ADH-1: What Is the Complete Authority Hierarchy?**

| Classification | Assessment |
|---|---|
| **Current Status** | UNRESOLVED |
| **Impact Zone** | All contexts requiring authorization |
| **Decision Required** | Chief vs deputy split, delegation scopes, escalation paths? |
| **Blocks Design** | PARTIAL — affects Authorization context boundary clarity |
| **Recommended Classification** | **MUST RESOLVE BEFORE DESIGN** |
| **Rationale** | Authorization is used by all 9 contexts; incomplete hierarchy creates design ambiguity |

---

**ADG-2: How Is Authority Delegated?**

| Classification | Assessment |
|---|---|
| **Current Status** | UNRESOLVED |
| **Impact Zone** | Constitutional Governance, Authorization |
| **Decision Required** | Delegation rules, reversibility, scope constraints? |
| **Blocks Design** | PARTIAL — refines ADH-1; depends on ADH-1 resolution |
| **Recommended Classification** | **CAN RESOLVE DURING DESIGN** |
| **Rationale** | Depends on ADH-1 resolution; can be clarified during GovernanceState and RoleAssignment aggregate design |

---

### Model Refinement Debt (Aggregate Boundaries)

**D42B: Who Owns the Verifiability Guarantee?**

| Classification | Assessment |
|---|---|
| **Current Status** | UNRESOLVED |
| **Impact Zone** | Voting, Verification, Results/Tallying |
| **Decision Required** | Does Voting, Verification, or new context own verifiability guarantee? |
| **Investigation Scope** | Candidates include: Voting aggregate, Governance Evidence Replay, PublicDigitalBallotBox projection, or separate Verification capability |
| **Blocks Design** | YES — affects Voting aggregate boundary (C6 provisional) and context ownership |
| **Recommended Classification** | **MUST RESOLVE BEFORE DESIGN** |
| **Rationale** | Voting context boundary is provisional; context boundary questions must be resolved before aggregate design begins |

---

**ADC-1: What Are Role Uniqueness and Exclusivity Rules?**

| Classification | Assessment |
|---|---|
| **Current Status** | UNRESOLVED |
| **Impact Zone** | RoleAssignment aggregate, Authorization context |
| **Decision Required** | Can user have multiple roles? Are some roles mutually exclusive? |
| **Blocks Design** | PARTIAL — affects RoleAssignment aggregate definition |
| **Recommended Classification** | **CAN RESOLVE DURING DESIGN** |
| **Rationale** | RoleAssignment is provisional (AU-3); aggregate design can clarify rules |

---

**ADC-2: Do Roles Have Temporal Validity?**

| Classification | Assessment |
|---|---|
| **Current Status** | UNRESOLVED |
| **Impact Zone** | RoleAssignment aggregate lifecycle |
| **Decision Required** | Are roles time-bounded? Can they be revoked mid-election? |
| **Blocks Design** | PARTIAL — refines ADC-1 |
| **Recommended Classification** | **CAN RESOLVE DURING DESIGN** |
| **Rationale** | Determines RoleAssignment state structure; can be designed with ADC-1 |

---

**ADGR-1: What Is ReplaySession Governance and Operational Authority?**

| Classification | Assessment |
|---|---|
| **Current Status** | UNRESOLVED |
| **Impact Zone** | ReplaySession aggregate, Governance Evidence Replay context |
| **Decision Required** | Who invokes replays? What are consequences of divergence? |
| **Blocks Design** | PARTIAL — affects ReplaySession aggregate design |
| **Recommended Classification** | **CAN RESOLVE DURING DESIGN** |
| **Rationale** | ReplaySession is provisional (GR-1, GR-2); design can refine governance scope |
| **Dependency** | Depends on D35, D36, D37 resolution |

---

## Debt Classification Summary

| Category | Items | Assessment |
|----------|-------|-----------|
| **Must Resolve Before Design** | D35, D36, D37, ADH-1, D42B | 5 items (Critical Path) |
| **Can Resolve During Design** | ADG-2, ADC-1, ADC-2, ADGR-1 | 4 items (Parallel Path) |
| **Can Become Architecture Debt** | (None at present) | — |

---

## ARB Design Authorization Decision Matrix

### Decision 1: Governance Debt Resolution (Must Resolve Before Design)

**Question:** Should ARB authorize resolution of D35, D36, D37, ADH-1, ADG-2 before permitting design activities?

**Recommendation:** YES

**Rationale:**
- These five items affect core governance architecture (Arbitration, Authority, Governance)
- Cannot design aggregates or services without clarity on legitimacy enforcement and authority hierarchy
- Resolution can begin immediately as parallel work to design preparation

**Resolution Phases:**

**Phase A — Arbitration Governance (D35, D36, D37)**
- Establish legitimacy consequences and invocation authority
- 1 governance review session
- Output: ARB decision on arbitration governance model

**Phase B — Authority Hierarchy Governance (ADH-1, ADG-2)**
- Establish complete authority hierarchy and delegation scopes
- 1 governance review session
- Output: ARB decision on authority model

**Phase C — Verifiability Investigation (D42B)**
- Open-ended evaluation of verifiability guarantee ownership
- Can proceed in parallel with Phases A and B
- Output: Investigation recommendation with candidate analysis

---

### Decision 2: D42B Investigation Authorization (Parallel Investigation)

**Question:** Should ARB authorize D42B investigation before design authorization, without presupposing solution?

**Recommendation:** YES

**Scope Definition:**
- Investigate who owns the verifiability guarantee
- Evaluate candidates WITHOUT presupposing any solution:
  - Voting aggregate owns receipt hash (established)
  - Verification context could extend trust guarantee to verifiability
  - Governance Evidence Replay could provide verification capability
  - PublicDigitalBallotBox is one candidate projection approach
  - Other alternatives may exist
- Investigation should be open-ended; no solution predetermined

**Investigation Scope:**
- Document advantages/tradeoffs of each candidate
- Identify which candidate aligns with discovered invariants
- Determine impact on Voting aggregate boundary (C6 provisional)
- Produce recommendation with full analysis

**Can Proceed In Parallel With:** Governance debt resolution

---

### Decision 3: RoleAssignment and ReplaySession Design (Conditional)

**Question:** Should ARB authorize design of provisional aggregates before all debts resolved?

**Recommendation:** CONDITIONAL

**Conditions:**
- ADC-1, ADC-2 (RoleAssignment) — can proceed after D35, D36, D37, ADH-1, ADG-2 resolved
- ADGR-1 (ReplaySession) — can proceed after D35, D36, D37 resolved (depends on arbitration model)

**Rationale:** Provisional aggregates cannot be designed with confidence until governance context is established.

---

### Decision 4: Permitted Activities Until Design Authorization

**PERMITTED (During Governance Debt Resolution):**
- Governance review sessions (D35, D36, D37, ADH-1)
- D42B investigation and candidate evaluation (open-ended)
- Model documentation and clarification
- Invariant verification
- Boundary refinement for provisional contexts
- Technical research (NOT architectural decisions)

**FORBIDDEN (Until ARB Authorizes Design):**
No design activities are authorized until governance debts and D42B investigation are complete.

This includes:
- Aggregate design
- Domain event definition
- Command/Query specification
- Service boundaries
- API design
- Database schema
- Architecture patterns
- Implementation technology selection

**PERMITTED (After Governance Authorization):**
- Aggregate design
- Domain event definition
- Command/Query specification
- Technical architecture selection

---

## Design Readiness Assessment

**Current State:** NOT READY FOR DESIGN

**Why:** Five critical governance questions remain unresolved:
- D35, D36, D37 (Arbitration governance)
- ADH-1 (Authority hierarchy)
- D42B (Verifiability ownership)

These affect core domain model boundaries.

**Prerequisites Before Design Authorization:**
1. **Phase A:** D35, D36, D37 resolved with ARB decision
2. **Phase B:** ADH-1 resolved with ARB decision
3. **Phase C:** D42B investigation completed with recommendation

**Exit Criteria for Design Authorization:**
- Governance debts D35, D36, D37, ADH-1, D42B resolved with ARB approval
- Provisional contexts (C6, C7, C8, C9) re-evaluated after governance clarity
- Provisional aggregates (RoleAssignment, ReplaySession) re-assessed for boundary certainty
- ARB explicit authorization: "Round 31 Design Phase Authorized"

---

## ARB Recommendation

**Current Assessment:**

**DO NOT AUTHORIZE DESIGN AT THIS TIME**

**Authorize Instead:**

**Phase A — Arbitration Governance Resolution**
- Resolve D35, D36, D37 with explicit ARB decisions
- Output: Arbitration governance model approved

**Phase B — Authority Hierarchy Governance Resolution**
- Resolve ADH-1 with explicit ARB decision
- Output: Authority hierarchy model approved

**Phase C — Verifiability Guarantee Investigation**
- Execute open-ended investigation of D42B candidates
- Output: Recommendation on verifiability ownership
- Can proceed in parallel with Phases A and B

**After Phases A–C Complete:**
- Round 31 ARB Design Authorization Review
- If approved: Begin aggregate and domain event design

---

## Governance Resolution Sequence

**Phase A — Arbitration Governance (D35, D36, D37)**
1. ARB governance review session
2. Establish legitimacy consequences (D35)
3. Establish invocation authority (D36)
4. Establish enforcement mechanisms (D37)
5. Output: ARB-approved arbitration governance model

**Phase B — Authority Hierarchy Governance (ADH-1)**
1. ARB governance review session
2. Establish complete authority hierarchy
3. Clarify chief/deputy authority split
4. Clarify delegation scopes and limits
5. Output: ARB-approved authority model

**Phase C — Verifiability Guarantee Investigation (D42B)**
1. Execute open-ended investigation
2. Evaluate all candidates without presupposition
3. Document tradeoffs and evidence
4. Produce recommendation with full analysis
5. Output: D42B investigation report with recommendation

**After All Phases Complete:**
1. Re-evaluate provisional contexts (C6, C7, C8, C9) with governance clarity
2. Re-assess provisional aggregates (RoleAssignment, ReplaySession)
3. ARB Design Authorization Review
4. If approved: Begin Round 31 Design Phase

---

## ARB Approval Checkpoint

**Status:** READY FOR ARB REVIEW

**Critical Questions for ARB:**

1. **D42B Priority:** Should D42B investigation be classified as MUST RESOLVE BEFORE DESIGN (as recommended) or CAN RESOLVE DURING DESIGN?

2. **Governance Authorization:** Do you authorize Phases A–C (Arbitration, Authority, Verifiability) as the path to design authorization?

3. **ADG-2 Deferral:** Should ADG-2 (delegation rules) be resolved in Phase B or deferred to design phase after ADH-1 is settled?

4. **Design Phase Timing:** After all phases complete, shall ARB conduct a final Round 31 Design Authorization Review before design activities begin?

---

**Round 30 — ARB Design Readiness Review**

**Status:** CONDITIONAL APPROVAL

**Next Step:** ARB review and decision on 4 critical questions above.

**Upon ARB Approval:** Begin Phase A Governance Resolution (D35, D36, D37)

